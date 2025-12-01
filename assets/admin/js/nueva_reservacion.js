// DETECCIÓN AUTOMÁTICA DE ENTORNO
const base_url = (() => {
    const hostname = window.location.hostname;
    const pathname = window.location.pathname;
    
    // Detectar si estamos en producción
    const isProduction = (
        hostname === 'www.casasviamar.com' || 
        hostname === 'casasviamar.com' ||
        hostname.includes('hostinger')
    );
    
    if (isProduction) {
        // PRODUCCIÓN: https://www.casasviamar.com/admin/
        return window.location.origin + '/admin/';
    } else {
        // DESARROLLO: http://localhost/casasviamar/admin/
        return window.location.origin + '/casasviamar/admin/';
    }
})();

console.log('🌍 Entorno detectado:', window.location.hostname.includes('casasviamar.com') ? 'PRODUCCIÓN' : 'DESARROLLO');
console.log('🔗 Base URL configurada:', base_url);

function alertSW(mensaje, tipo) {
    Swal.fire({
        position: "top-end",
        icon: tipo,
        title: mensaje,
        showConfirmButton: false,
        timer: 3500,
        toast: true
    });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando Nueva Reservación...');
    console.log('Base URL:', base_url);
    
    // Inicializar Select2
    setTimeout(() => {
        $('#filtroHabitacion, #habitacionReserva').select2({
            theme: 'default',
            width: '100%'
        });
        console.log('🎨 Select2 inicializado');
    }, 500);

    // Cargar reservaciones en la tabla
    cargarReservacionesTabla();

    // Event listeners
    $('#fechaIngreso, #fechaSalida, #habitacionReserva').on('change', calcularPrecio);
    $('#btnLimpiarForm').on('click', limpiarFormulario);
    $('#filtroHabitacion').on('change', cargarReservacionesTabla);
});

function cargarReservacionesTabla() {
    console.log('📋 Cargando reservaciones en tabla...');
    
    const habitacion = $('#filtroHabitacion').val();
    let url = base_url + 'getReservas?formato=tabla';
    if (habitacion) {
        url += '&habitacion=' + habitacion;
    }

    fetch(url, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('📡 Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.text();
    })
    .then(text => {
        console.log('📄 Raw response:', text.substring(0, 200) + '...');
        try {
            const data = JSON.parse(text);
            console.log('📊 Reservaciones recibidas:', data);
            llenarTablaReservaciones(data);
        } catch (parseError) {
            console.error('❌ Error parsing JSON:', parseError);
            console.error('📄 Full response:', text);
            alertSW('Error: El servidor devolvió una respuesta inválida', 'error');
        }
    })
    .catch(error => {
        console.error('💥 Error cargando reservaciones:', error);
        alertSW('Error al cargar reservaciones', 'error');
    });
}

function llenarTablaReservaciones(reservaciones) {
    const tbody = document.getElementById('bodyReservaciones');
    const contador = document.getElementById('contadorReservas');
    tbody.innerHTML = '';

    if (!reservaciones || reservaciones.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No hay reservaciones</td></tr>';
        contador.innerHTML = '<i class="fas fa-list me-1"></i>0 reservaciones';
        return;
    }

    console.log('🔍 Procesando reservaciones para tabla:', reservaciones);
    contador.innerHTML = `<i class="fas fa-list me-1"></i>${reservaciones.length} reservación${reservaciones.length !== 1 ? 'es' : ''}`;

    reservaciones.forEach(reserva => {
        console.log('📋 Procesando reserva:', reserva);
        
        const id = reserva.id || 'N/A';
        const fechaIngresoStr = reserva.fecha_ingreso || '';
        const fechaSalidaStr = reserva.fecha_salida || '';
        
        let fechaIngresoFormateada = 'Sin fecha';
        let fechaSalidaFormateada = 'Sin fecha';
        let noches = 0;
        
        if (fechaIngresoStr && fechaSalidaStr) {
            try {
                const fechaIngreso = new Date(fechaIngresoStr + 'T00:00:00');
                const fechaSalida = new Date(fechaSalidaStr + 'T00:00:00');
                
                if (!isNaN(fechaIngreso.getTime()) && !isNaN(fechaSalida.getTime())) {
                    fechaIngresoFormateada = fechaIngreso.toLocaleDateString('es-ES');
                    fechaSalidaFormateada = fechaSalida.toLocaleDateString('es-ES');
                    noches = Math.ceil((fechaSalida - fechaIngreso) / (1000 * 60 * 60 * 24));
                }
            } catch (error) {
                console.error('❌ Error procesando fechas:', error);
                fechaIngresoFormateada = fechaIngresoStr;
                fechaSalidaFormateada = fechaSalidaStr;
            }
        }
        
        const precioTotal = parseFloat(reserva.monto || reserva.precio_total || reserva.precio || 0);
        const nombreCasa = reserva.nombre_habitacion || 'Casa Sin Nombre';
        const estadoInt = parseInt(reserva.estado || 1);
        const estadoTexto = estadoInt === 1 ? 'Activa' : 'Inactiva';
        const estadoClase = estadoInt === 1 ? 'bg-success' : 'bg-danger';
        const codReserva = reserva.cod_reserva || '';
        
        // Datos de pago
        const estadoPago = reserva.estado_pago || 'pendiente';
        const idTransaccion = reserva.id_transaccion || '';
        const metodoPago = reserva.metodo_pago || '';
        const fechaPago = reserva.fecha_pago || '';
        const emailPagador = reserva.email_pagador || '';
        const montoSubtotal = parseFloat(reserva.monto_subtotal || 0);
        const tarifaLimpieza = parseFloat(reserva.tarifa_limpieza || 0);
        const tarifaServicio = parseFloat(reserva.tarifa_servicio || 0);
        const montoAnfitrion = parseFloat(reserva.monto_anfitrion || 0);
        
        // Datos de facturación
        const requiereFactura = parseInt(reserva.requiere_factura || 0);
        
        // Generar HTML de columna de pago
        let pagoHtml = '';
        if (estadoPago === 'pagado') {
            pagoHtml = `
                <button class="btn btn-sm btn-success" onclick="verDetallePago(${id}, '${idTransaccion}', '${metodoPago}', '${fechaPago}', '${emailPagador}', ${montoSubtotal}, ${tarifaLimpieza}, ${tarifaServicio}, ${precioTotal}, ${montoAnfitrion})" title="Ver detalles del pago">
                    <i class="fas fa-check-circle"></i>
                </button>
            `;
        } else if (estadoPago === 'reembolsado') {
            pagoHtml = `<span class="badge bg-warning text-dark"><i class="fas fa-undo me-1"></i>Reembolsado</span>`;
        } else {
            pagoHtml = `<span class="badge bg-secondary"><i class="fas fa-clock me-1"></i>Pendiente</span>`;
        }
        
        // Generar HTML de columna de factura
        let facturaHtml = '';
        if (requiereFactura === 1) {
            facturaHtml = `
                <button class="btn btn-sm btn-primary" onclick="verFacturacion(${id})" title="Ver datos de facturación">
                    <i class="fas fa-file-invoice"></i>
                </button>
            `;
        } else {
            facturaHtml = `<span class="badge bg-light text-muted"><i class="fas fa-minus"></i></span>`;
        }
        
        const fila = document.createElement('tr');
        fila.id = `reserva-${id}`;
        fila.setAttribute('data-reserva-id', id);
        
        fila.innerHTML = `
            <td>
                <strong>#${id}</strong>
                ${codReserva ? `<br><small class="text-muted">${codReserva}</small>` : ''}
            </td>
            <td><span class="badge bg-primary">${nombreCasa}</span></td>
            <td><strong>${fechaIngresoFormateada}</strong></td>
            <td><strong>${fechaSalidaFormateada}</strong></td>
            <td><span class="badge bg-info">${noches > 0 ? noches : '0'} noche${noches !== 1 ? 's' : ''}</span></td>
            <td><strong class="text-success">$${precioTotal.toFixed(2)}</strong></td>
            <td>${pagoHtml}</td>
            <td>${facturaHtml}</td>
            <td><span class="badge ${estadoClase}">${estadoTexto}</span></td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-warning" onclick="editarReserva(${id})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger" onclick="eliminarReserva(${id})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(fila);
    });
}

// Función para ver detalle de pago
function verDetallePago(id, idTransaccion, metodoPago, fechaPago, emailPagador, subtotal, limpieza, servicio, total, anfitrion) {
    document.getElementById('detalle_id_transaccion').textContent = idTransaccion || 'N/A';
    document.getElementById('detalle_metodo').textContent = metodoPago ? metodoPago.toUpperCase() : 'N/A';
    document.getElementById('detalle_fecha_pago').textContent = fechaPago ? new Date(fechaPago).toLocaleString('es-ES') : 'N/A';
    document.getElementById('detalle_email').textContent = emailPagador || 'N/A';
    document.getElementById('detalle_subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('detalle_limpieza').textContent = '$' + limpieza.toFixed(2);
    document.getElementById('detalle_servicio').textContent = '$' + servicio.toFixed(2);
    document.getElementById('detalle_total').textContent = '$' + total.toFixed(2);
    document.getElementById('detalle_anfitrion').textContent = '$' + anfitrion.toFixed(2);
    document.getElementById('detalle_comision').textContent = '$' + servicio.toFixed(2);
    
    new bootstrap.Modal(document.getElementById('modalDetallePago')).show();
}

function limpiarFormulario() {
    document.getElementById('frmNuevaReserva').reset();
    $('#habitacionReserva').val('').trigger('change');
    $('#precioTotal').val('0.00');
    console.log('🧹 Formulario limpiado');
}

function editarReserva(id) {
    console.log('✏️ Editando reserva ID:', id);
    
    const fila = document.querySelector(`tr[data-reserva-id="${id}"]`);
    if (!fila) return;
    
    const celdas = fila.querySelectorAll('td');
    const fechaIngreso = celdas[2].textContent.trim();
    const fechaSalida = celdas[3].textContent.trim();
    
    const fechaIngresoISO = convertirFechaAISO(fechaIngreso);
    const fechaSalidaISO = convertirFechaAISO(fechaSalida);
    
    celdas[2].innerHTML = `<input type="date" class="form-control form-control-sm" value="${fechaIngresoISO}" id="edit-fecha-ingreso-${id}">`;
    celdas[3].innerHTML = `<input type="date" class="form-control form-control-sm" value="${fechaSalidaISO}" id="edit-fecha-salida-${id}">`;
    celdas[7].innerHTML = `
        <div class="btn-group btn-group-sm">
            <button class="btn btn-success" onclick="guardarEdicion(${id})" title="Guardar">
                <i class="fas fa-save"></i>
            </button>
            <button class="btn btn-secondary" onclick="cancelarEdicion(${id})" title="Cancelar">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    fila.classList.add('table-warning');
}

function convertirFechaAISO(fechaTexto) {
    const partes = fechaTexto.split('/');
    if (partes.length === 3) {
        return `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
    }
    return fechaTexto;
}

function guardarEdicion(id) {
    console.log('💾 Guardando edición de reserva ID:', id);
    
    const fechaIngreso = document.getElementById(`edit-fecha-ingreso-${id}`).value;
    const fechaSalida = document.getElementById(`edit-fecha-salida-${id}`).value;
    
    if (!fechaIngreso || !fechaSalida) {
        alertSW('Por favor complete ambas fechas', 'warning');
        return;
    }
    
    const formData = new FormData();
    formData.append('id', id);
    formData.append('fecha_ingreso', fechaIngreso);
    formData.append('fecha_salida', fechaSalida);
    
    fetch(base_url + 'actualizarReserva', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.text();
    })
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.tipo === 'success') {
                alertSW(data.msg, 'success');
                cargarReservacionesTabla();
            } else {
                alertSW(data.msg, 'error');
            }
        } catch (parseError) {
            console.error('❌ Error parsing JSON:', parseError);
            console.error('📄 Response:', text);
            alertSW('Error: Respuesta inválida del servidor', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alertSW('Error al actualizar reserva', 'error');
    });
}

function cancelarEdicion(id) {
    console.log('❌ Cancelando edición de reserva ID:', id);
    cargarReservacionesTabla();
}

function eliminarReserva(id) {
    console.log('🗑️ Eliminando reserva ID:', id);
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará permanentemente la reservación de la base de datos',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            
            fetch(base_url + 'eliminarReserva', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.text();
            })
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.tipo === 'success') {
                        alertSW(data.msg, 'success');
                        cargarReservacionesTabla();
                    } else {
                        alertSW(data.msg, 'error');
                    }
                } catch (parseError) {
                    console.error('❌ Error parsing JSON:', parseError);
                    console.error('📄 Response:', text);
                    alertSW('Error: Respuesta inválida del servidor', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alertSW('Error al eliminar reserva', 'error');
            });
        }
    });
}

function calcularPrecio() {
    const fechaIngreso = $('#fechaIngreso').val();
    const fechaSalida = $('#fechaSalida').val();
    const habitacionSelect = $('#habitacionReserva');
    const habitacionId = habitacionSelect.val();
    
    console.log('🔄 Iniciando cálculo de precio...');
    console.log('📋 Datos iniciales:', { fechaIngreso, fechaSalida, habitacionId });
    
    if (!habitacionId) {
        $('#precioTotal').val('0.00');
        console.log('⚠️ No se ha seleccionado ninguna casa');
        return;
    }
    
    const selectedOption = habitacionSelect.find('option:selected');
    const dataPrecio = selectedOption.attr('data-precio');
    const precio = parseFloat(dataPrecio);
    
    console.log('🔍 Datos del option seleccionado:', { 
        habitacionId, 
        dataPrecio, 
        precio
    });

    if (fechaIngreso && fechaSalida && !isNaN(precio) && precio > 0) {
        const fecha1 = new Date(fechaIngreso);
        const fecha2 = new Date(fechaSalida);
        const diferencia = Math.ceil((fecha2 - fecha1) / (1000 * 60 * 60 * 24));
        
        console.log('📅 Cálculo de días:', { fecha1, fecha2, diferencia });
        
        if (diferencia > 0) {
            const total = diferencia * precio;
            $('#precioTotal').val(total.toFixed(2));
            console.log('✅ PRECIO CALCULADO EXITOSAMENTE!');
            console.log(`📊 Desglose: ${diferencia} noches × $${precio.toFixed(2)} = $${total.toFixed(2)}`);
        } else {
            $('#precioTotal').val('0.00');
            console.warn('⚠️ Diferencia de días inválida:', diferencia);
        }
    } else {
        $('#precioTotal').val('0.00');
        console.log('❌ No se puede calcular precio - Datos faltantes:', {
            fechaIngreso: !!fechaIngreso,
            fechaSalida: !!fechaSalida,
            precio: precio,
            precioValido: !isNaN(precio) && precio > 0
        });
    }
}

// Manejar formulario de nueva reserva
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('frmNuevaReserva').addEventListener('submit', function(e) {
        e.preventDefault();
        
        console.log('🚀 Enviando formulario de nueva reserva');
        
        const habitacion = $('#habitacionReserva').val();
        const fechaIngreso = $('#fechaIngreso').val();
        const fechaSalida = $('#fechaSalida').val();
        const precio = $('#precioTotal').val();
        
        console.log('📋 Validando campos:', { habitacion, fechaIngreso, fechaSalida, precio });
        
        if (!habitacion || !fechaIngreso || !fechaSalida) {
            alertSW('Por favor complete: Casa, Fecha de Ingreso y Fecha de Salida', 'warning');
            return;
        }
        
        if (!precio || precio === '0.00') {
            alertSW('El precio total debe ser mayor a 0. Verifique la casa seleccionada.', 'warning');
            return;
        }
        
        const formData = new FormData(this);
        console.log('Datos del formulario:', {
            usuario: formData.get('usuario'),
            habitacion: formData.get('habitacion'),
            fecha_ingreso: formData.get('fecha_ingreso'),
            fecha_salida: formData.get('fecha_salida'),
            precio: formData.get('precio')
        });
        
        fetch(base_url + 'crearReserva', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.text();
        })
        .then(text => {
            console.log('📄 Raw response:', text.substring(0, 200) + '...');
            try {
                const data = JSON.parse(text);
                console.log('Respuesta del servidor:', data);
                if (data.tipo === 'success') {
                    alertSW(data.msg, data.tipo);
                    limpiarFormulario();
                    cargarReservacionesTabla();
                } else {
                    alertSW(data.msg, data.tipo);
                }
            } catch (parseError) {
                console.error('❌ Error parsing JSON:', parseError);
                console.error('📄 Full response:', text);
                alertSW('Error: El servidor devolvió una respuesta inválida', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertSW('Error en la conexión', 'error');
        });
    });
});

// Función para ver datos de facturación
function verFacturacion(idReserva) {
    fetch(base_url + 'getFacturacion/' + idReserva)
        .then(response => response.json())
        .then(data => {
            if (data.tipo === 'success' && data.datos) {
                const d = data.datos;
                
                // Llenar modal
                document.getElementById('fact_tipo_persona').textContent = d.tipo_persona === 'moral' ? 'Persona Moral (Empresa)' : 'Persona Física';
                document.getElementById('fact_rfc').textContent = d.rfc || 'N/A';
                document.getElementById('fact_razon_social').textContent = d.razon_social || 'N/A';
                document.getElementById('fact_regimen').textContent = d.regimen_fiscal || 'N/A';
                document.getElementById('fact_cp').textContent = d.codigo_postal || 'N/A';
                document.getElementById('fact_uso_cfdi').textContent = d.uso_cfdi || 'N/A';
                document.getElementById('fact_correo').textContent = d.correo_factura || 'N/A';
                document.getElementById('fact_telefono').textContent = d.telefono || 'No especificado';
                document.getElementById('fact_direccion').textContent = d.direccion || 'No especificada';
                
                // Estado de factura con badge
                let estadoBadge = '';
                switch(d.estado) {
                    case 'pendiente':
                        estadoBadge = '<span class="badge bg-warning">Pendiente</span>';
                        break;
                    case 'procesada':
                        estadoBadge = '<span class="badge bg-info">Procesada</span>';
                        break;
                    case 'enviada':
                        estadoBadge = '<span class="badge bg-success">Enviada</span>';
                        break;
                    default:
                        estadoBadge = '<span class="badge bg-secondary">Sin estado</span>';
                }
                document.getElementById('fact_estado').innerHTML = estadoBadge;
                
                // Mostrar modal
                new bootstrap.Modal(document.getElementById('modalFacturacion')).show();
            } else {
                alertSW(data.msg || 'No se pudieron obtener los datos de facturación', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertSW('Error al obtener datos de facturación', 'error');
        });
}
