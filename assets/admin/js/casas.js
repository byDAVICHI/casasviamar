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

// Variables globales
let casasData = [];
let modoEdicion = false;

// Función para mostrar alertas
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

// Función para manejar respuestas del servidor
function handleServerResponse(response) {
    return response.text().then(text => {
        try {
            return JSON.parse(text);
        } catch (parseError) {
            console.error('Error parsing JSON:', parseError);
            console.log('Server response:', text);
            return {
                tipo: 'error',
                msg: 'Error: El servidor devolvió una respuesta inválida. Respuesta: ' + text.substring(0, 100)
            };
        }
    });
}

// Inicialización del DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Inicializando Gestión de Casas...');
    console.log('Base URL:', base_url);
    
    // Cargar casas iniciales
    cargarCasas();
    
    // Event listeners
    $('#btnNuevaCasa, #btnPrimeraCasa').on('click', abrirModalNuevaCasa);
    $('#btnGuardarCasa').on('click', guardarCasa);
    $('#buscarCasa').on('input', filtrarCasas);
    $('#filtroEstado').on('change', filtrarCasas);
    
    // Event listeners para gestión de imágenes
    $('#btnSubirImagen').on('click', subirImagen);
    $('#btnEliminarImagen').on('click', eliminarImagenActual);
    $('#inputImagen').on('change', previsualizarImagen);
    
    // Limpiar formulario al cerrar modal
    $('#modalCasa').on('hidden.bs.modal', limpiarFormulario);
    
    // Auto-generar slug cuando se escribe el nombre
    $('#estilo').on('input', function() {
        if ($('#slug').val() === '') {
            const slug = $(this).val().toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            $('#slug').val(slug);
        }
    });
});

// Función para cargar todas las casas
function cargarCasas() {
    console.log('📋 Cargando casas vacacionales...');
    
    fetch(base_url + 'getCasas', {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(handleServerResponse)
    .then(data => {
        if (Array.isArray(data)) {
            casasData = data;
            renderizarCasas(data);
            actualizarContador(data.length);
            console.log('✅ Casas cargadas:', data.length);
        } else {
            console.error('❌ Error al cargar casas:', data);
            alertSW('Error al cargar las casas', 'error');
        }
    })
    .catch(error => {
        console.error('❌ Error de red:', error);
        alertSW('Error de conexión al cargar las casas', 'error');
    });
}

// Función para renderizar las casas en el DOM
function renderizarCasas(casas) {
    const container = document.getElementById('casasContainer');
    
    if (casas.length === 0) {
        container.innerHTML = `
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-home fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay casas vacacionales</h4>
                    <p class="text-muted">No se encontraron casas con los filtros aplicados</p>
                </div>
            </div>
        `;
        return;
    }
    
    container.innerHTML = casas.map(casa => `
        <div class="col-lg-4 col-md-6 casa-item" data-casa-id="${casa.id}">
            <div class="card casa-card">
                <!-- Imagen de la casa -->
                <div class="card-img-top">
                    ${casa.foto ? 
                        `<img src="${window.location.origin}${window.location.pathname.includes('casasviamar') ? '/casasviamar' : ''}/assets/principal/images/${casa.foto}" 
                             class="casa-image w-100" alt="${casa.estilo}" onerror="this.parentElement.innerHTML='<div class=\\'casa-placeholder\\'><i class=\\'fas fa-home\\'></i></div>'">` :
                        `<div class="casa-placeholder"><i class="fas fa-home"></i></div>`
                    }
                </div>
                
                <!-- Información de la casa -->
                <div class="casa-info">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="casa-titulo">${casa.estilo}</h5>
                        <span class="${casa.estado == 1 ? 'estado-activo' : 'estado-inactivo'}">
                            ${casa.estado == 1 ? 'Activa' : 'Inactiva'}
                        </span>
                    </div>
                    
                    <p class="casa-descripcion">${casa.descripcion}</p>
                    
                    <div class="casa-detalles">
                        <div class="detalle-item">
                            <div class="detalle-valor">#${casa.numero}</div>
                            <div class="detalle-label">Número</div>
                        </div>
                        <div class="detalle-item">
                            <div class="detalle-valor">${casa.capacidad}</div>
                            <div class="detalle-label">Personas</div>
                        </div>
                        <div class="detalle-item">
                            <div class="detalle-valor precio-destacado">$${parseFloat(casa.precio).toFixed(2)}</div>
                            <div class="detalle-label">Por noche</div>
                        </div>
                    </div>
                    
                    <!-- Acciones -->
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button class="btn btn-outline-primary btn-action btn-sm" onclick="verCasa(${casa.id})">
                            <i class="fas fa-eye"></i> Ver
                        </button>
                        <button class="btn btn-outline-warning btn-action btn-sm" onclick="editarCasa(${casa.id})">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn btn-outline-danger btn-action btn-sm" onclick="eliminarCasa(${casa.id}, '${casa.estilo}')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Función para filtrar casas
function filtrarCasas() {
    const busqueda = $('#buscarCasa').val().toLowerCase();
    const estadoFiltro = $('#filtroEstado').val();
    
    let casasFiltradas = casasData.filter(casa => {
        const coincideBusqueda = busqueda === '' || 
            casa.estilo.toLowerCase().includes(busqueda) ||
            casa.descripcion.toLowerCase().includes(busqueda) ||
            casa.capacidad.toString().includes(busqueda) ||
            casa.precio.toString().includes(busqueda);
            
        const coincideEstado = estadoFiltro === '' || casa.estado == estadoFiltro;
        
        return coincideBusqueda && coincideEstado;
    });
    
    renderizarCasas(casasFiltradas);
    actualizarContador(casasFiltradas.length);
}

// Función para actualizar contador
function actualizarContador(total) {
    document.getElementById('totalCasas').textContent = total;
}

// Función para abrir modal de nueva casa
function abrirModalNuevaCasa() {
    modoEdicion = false;
    limpiarFormulario();
    $('#modalCasaTitle').html('<i class="fas fa-home me-2"></i>Nueva Casa Vacacional');
    $('#btnGuardarCasa').html('<i class="fas fa-save me-2"></i>Guardar Casa');
    $('#modalCasa').modal('show');
}

// Función para limpiar formulario
function limpiarFormulario() {
    $('#formCasa')[0].reset();
    $('#casaId').val('');
    $('#inputImagen').val('');
    $('#imagenPreview').hide();
    $('#imagenActual').attr('src', '');
    $('#nombreImagenActual').text('');
    modoEdicion = false;
}

// Función para ver detalles de una casa
function verCasa(id) {
    const casa = casasData.find(c => c.id == id);
    if (!casa) {
        alertSW('Casa no encontrada', 'error');
        return;
    }
    
    Swal.fire({
        title: casa.estilo,
        html: `
            <div class="text-start">
                ${casa.foto ? `<img src="${window.location.origin}${window.location.pathname.includes('casasviamar') ? '/casasviamar' : ''}/assets/principal/images/${casa.foto}" class="img-fluid rounded mb-3" style="max-height: 200px; width: 100%; object-fit: cover;">` : ''}
                <p><strong>Número:</strong> #${casa.numero}</p>
                <p><strong>Capacidad:</strong> ${casa.capacidad} personas</p>
                <p><strong>Precio:</strong> $${parseFloat(casa.precio).toFixed(2)} por noche</p>
                <p><strong>Estado:</strong> <span class="badge ${casa.estado == 1 ? 'bg-success' : 'bg-danger'}">${casa.estado == 1 ? 'Activa' : 'Inactiva'}</span></p>
                <p><strong>Slug:</strong> ${casa.slug || 'No definido'}</p>
                <p><strong>Descripción:</strong></p>
                <p class="text-muted">${casa.descripcion}</p>
                ${casa.video ? `<p><strong>Video:</strong> <a href="${casa.video}" target="_blank">Ver video</a></p>` : ''}
                <p><strong>Fecha de creación:</strong> ${new Date(casa.fecha).toLocaleDateString()}</p>
            </div>
        `,
        width: '600px',
        confirmButtonText: 'Cerrar',
        confirmButtonColor: '#667eea'
    });
}

// Función para editar casa
function editarCasa(id) {
    console.log('✏️ Editando casa ID:', id);
    
    fetch(base_url + 'getCasa?id=' + id, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(handleServerResponse)
    .then(data => {
        if (data && data.id) {
            modoEdicion = true;
            
            // Llenar formulario con datos de la casa
            $('#casaId').val(data.id);
            $('#estilo').val(data.estilo);
            $('#numero').val(data.numero);
            $('#capacidad').val(data.capacidad);
            $('#slug').val(data.slug);
            $('#foto').val(data.foto);
            $('#video').val(data.video);
            $('#descripcion').val(data.descripcion);
            $('#precio').val(data.precio);
            $('#estado').val(data.estado);
            
            // Mostrar imagen actual si existe
            mostrarImagenActual(data.foto);
            
            // Cambiar título del modal
            $('#modalCasaTitle').html('<i class="fas fa-edit me-2"></i>Editar Casa Vacacional');
            $('#btnGuardarCasa').html('<i class="fas fa-save me-2"></i>Actualizar Casa');
            
            // Mostrar modal
            $('#modalCasa').modal('show');
            
            console.log('✅ Datos de casa cargados para edición');
        } else {
            console.error('❌ Error al cargar datos de la casa:', data);
            alertSW('Error al cargar los datos de la casa', 'error');
        }
    })
    .catch(error => {
        console.error('❌ Error de red:', error);
        alertSW('Error de conexión al cargar la casa', 'error');
    });
}

// Función para guardar casa (crear o editar)
function guardarCasa() {
    const formData = new FormData(document.getElementById('formCasa'));
    const metodo = modoEdicion ? 'editarCasa' : 'crearCasa';
    
    console.log('💾 Guardando casa...', modoEdicion ? 'Editando' : 'Creando');
    
    // Validaciones básicas
    if (!formData.get('estilo') || !formData.get('numero') || !formData.get('capacidad') || 
        !formData.get('precio') || !formData.get('descripcion')) {
        alertSW('Por favor completa todos los campos obligatorios', 'warning');
        return;
    }
    
    if (parseFloat(formData.get('precio')) <= 0) {
        alertSW('El precio debe ser mayor a 0', 'warning');
        return;
    }
    
    if (parseInt(formData.get('capacidad')) <= 0) {
        alertSW('La capacidad debe ser mayor a 0', 'warning');
        return;
    }
    
    // Deshabilitar botón mientras se procesa
    const btnGuardar = document.getElementById('btnGuardarCasa');
    const textoOriginal = btnGuardar.innerHTML;
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
    
    fetch(base_url + metodo, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(handleServerResponse)
    .then(data => {
        console.log('📥 Respuesta del servidor:', data);
        
        if (data.tipo === 'success') {
            alertSW(data.msg, 'success');
            $('#modalCasa').modal('hide');
            cargarCasas(); // Recargar la lista
        } else {
            alertSW(data.msg || 'Error al guardar la casa', data.tipo || 'error');
        }
    })
    .catch(error => {
        console.error('❌ Error de red:', error);
        alertSW('Error de conexión al guardar la casa', 'error');
    })
    .finally(() => {
        // Rehabilitar botón
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;
    });
}

// Función para eliminar casa
function eliminarCasa(id, nombre) {
    console.log('🗑️ Solicitando eliminación de casa ID:', id);
    
    Swal.fire({
        title: '¿Estás seguro?',
        html: `¿Deseas eliminar la casa vacacional <strong>"${nombre}"</strong>?<br><br>
               <small class="text-muted">Esta acción no se puede deshacer.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            ejecutarEliminacion(id, nombre);
        }
    });
}

// Función para ejecutar la eliminación
function ejecutarEliminacion(id, nombre) {
    const formData = new FormData();
    formData.append('id', id);
    
    fetch(base_url + 'eliminarCasa', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(handleServerResponse)
    .then(data => {
        console.log('📥 Respuesta de eliminación:', data);
        
        if (data.tipo === 'success') {
            alertSW(data.msg, 'success');
            cargarCasas(); // Recargar la lista
        } else if (data.tipo === 'warning') {
            Swal.fire({
                title: 'No se puede eliminar',
                text: data.msg,
                icon: 'warning',
                confirmButtonColor: '#667eea'
            });
        } else {
            alertSW(data.msg || 'Error al eliminar la casa', 'error');
        }
    })
    .catch(error => {
        console.error('❌ Error de red:', error);
        alertSW('Error de conexión al eliminar la casa', 'error');
    });
}

// ==================== FUNCIONES PARA GESTIÓN DE IMÁGENES ====================

// Función para mostrar imagen actual
function mostrarImagenActual(nombreArchivo) {
    if (nombreArchivo && nombreArchivo.trim() !== '') {
        const rutaImagen = window.location.origin + 
            (window.location.pathname.includes('casasviamar') ? '/casasviamar' : '') + 
            '/assets/principal/images/' + nombreArchivo;
        
        $('#imagenActual').attr('src', rutaImagen);
        $('#nombreImagenActual').text(nombreArchivo);
        $('#imagenPreview').show();
        
        console.log('🖼️ Mostrando imagen actual:', rutaImagen);
    } else {
        $('#imagenPreview').hide();
    }
}

// Función para previsualizar imagen seleccionada
function previsualizarImagen() {
    const archivo = document.getElementById('inputImagen').files[0];
    
    if (archivo) {
        // Validar tipo de archivo
        const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!tiposPermitidos.includes(archivo.type)) {
            alertSW('Solo se permiten archivos de imagen (JPG, PNG, GIF, WEBP)', 'warning');
            $('#inputImagen').val('');
            return;
        }
        
        // Validar tamaño (máximo 5MB)
        if (archivo.size > 5 * 1024 * 1024) {
            alertSW('El archivo es demasiado grande. Máximo 5MB', 'warning');
            $('#inputImagen').val('');
            return;
        }
        
        // Crear preview
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#imagenActual').attr('src', e.target.result);
            $('#nombreImagenActual').text(archivo.name + ' (Nueva imagen)');
            $('#imagenPreview').show();
        };
        reader.readAsDataURL(archivo);
        
        console.log('👁️ Previsualizando imagen:', archivo.name);
    }
}

// Función para subir imagen
function subirImagen() {
    const archivo = document.getElementById('inputImagen').files[0];
    
    if (!archivo) {
        alertSW('Por favor selecciona una imagen primero', 'warning');
        return;
    }
    
    const formData = new FormData();
    formData.append('imagen', archivo);
    
    // Deshabilitar botón mientras se sube
    const btnSubir = document.getElementById('btnSubirImagen');
    const textoOriginal = btnSubir.innerHTML;
    btnSubir.disabled = true;
    btnSubir.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Subiendo...';
    
    console.log('📤 Subiendo imagen:', archivo.name);
    
    fetch(base_url + 'subirImagenCasa', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(handleServerResponse)
    .then(data => {
        console.log('📥 Respuesta de subida:', data);
        
        if (data.tipo === 'success') {
            // Actualizar campo oculto con el nombre del archivo
            $('#foto').val(data.nombre_archivo);
            $('#nombreImagenActual').text(data.nombre_archivo);
            $('#inputImagen').val(''); // Limpiar input file
            
            alertSW(data.msg, 'success');
            console.log('✅ Imagen subida correctamente:', data.nombre_archivo);
        } else {
            alertSW(data.msg || 'Error al subir la imagen', data.tipo || 'error');
        }
    })
    .catch(error => {
        console.error('❌ Error de red:', error);
        alertSW('Error de conexión al subir la imagen', 'error');
    })
    .finally(() => {
        // Rehabilitar botón
        btnSubir.disabled = false;
        btnSubir.innerHTML = textoOriginal;
    });
}

// Función para eliminar imagen actual
function eliminarImagenActual() {
    const nombreArchivo = $('#foto').val();
    
    if (!nombreArchivo || nombreArchivo.trim() === '') {
        alertSW('No hay imagen para eliminar', 'warning');
        return;
    }
    
    Swal.fire({
        title: '¿Eliminar imagen?',
        text: '¿Estás seguro de que deseas eliminar la imagen actual?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            ejecutarEliminacionImagen(nombreArchivo);
        }
    });
}

// Función para ejecutar eliminación de imagen
function ejecutarEliminacionImagen(nombreArchivo) {
    const formData = new FormData();
    formData.append('nombre_archivo', nombreArchivo);
    
    console.log('🗑️ Eliminando imagen:', nombreArchivo);
    
    fetch(base_url + 'eliminarImagenCasa', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(handleServerResponse)
    .then(data => {
        console.log('📥 Respuesta de eliminación:', data);
        
        if (data.tipo === 'success') {
            // Limpiar campos y ocultar preview
            $('#foto').val('');
            $('#inputImagen').val('');
            $('#imagenPreview').hide();
            $('#imagenActual').attr('src', '');
            $('#nombreImagenActual').text('');
            
            alertSW(data.msg, 'success');
            console.log('✅ Imagen eliminada correctamente');
        } else {
            alertSW(data.msg || 'Error al eliminar la imagen', data.tipo || 'error');
        }
    })
    .catch(error => {
        console.error('❌ Error de red:', error);
        alertSW('Error de conexión al eliminar la imagen', 'error');
    });
}
