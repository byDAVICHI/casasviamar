<?php
include_once 'views/template/header-cliente.php';
error_reporting(0);

// Variables de precio (calculadas en backend para seguridad)
$precioNoche = floatval($data['habitacion']['precio'] ?? 0);
$noches = intval($data['noches'] ?? 0);
$subtotal = floatval($data['subtotal'] ?? 0);
$tarifaLimpieza = floatval($data['tarifa_limpieza'] ?? 0);
$tarifaServicio = floatval($data['tarifa_servicio'] ?? 0);
$total = floatval($data['total'] ?? 0);

// Token de seguridad para el pago (evita manipulación)
$tokenPago = hash('sha256', $total . $_SESSION['id_usuario'] . $_SESSION['reserva']['habitacion'] . date('Y-m-d'));
$_SESSION['token_pago'] = $tokenPago;

// Datos para PayPal (en USD para sandbox, o MXN para producción)
$moneda = MONEDA_PAYPAL;
$totalPaypal = $moneda === 'USD' ? round($total / 17.5, 2) : $total; // Conversión aproximada si es USD

?>

<div class="main-wrapper">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo RUTA_PRINCIPAL; ?>" class="text-decoration-none" style="color: var(--airbnb-pink);">Inicio</a></li>
                <li class="breadcrumb-item active">Reserva Pendiente</li>
            </ol>
        </nav>
        
        <?php if (!empty($_SESSION['reserva'])) { ?>
            <!-- Alerta de reserva pendiente -->
            <div class="alert d-flex align-items-center mb-4" role="alert" style="background: linear-gradient(135deg, #FF385C15, #E31C5F10); border: 1px solid #FF385C30; border-radius: 12px;">
                <i class="fas fa-clock me-3" style="color: var(--airbnb-pink); font-size: 24px;"></i>
                <div>
                    <strong style="color: var(--airbnb-pink);">Reserva Pendiente de Pago</strong>
                    <p class="mb-0 text-muted small">Completa tu pago para confirmar la reservación</p>
                </div>
            </div>
            
            <div class="row g-4">
                <!-- Columna Izquierda - Detalles de la Propiedad -->
                <div class="col-lg-7">
                    <div class="card-airbnb">
                        <div class="card-body p-0">
                            <!-- Imagen principal -->
                            <img src="<?php echo obtenerRutaImagenCasa($data['habitacion']['foto']); ?>"
                                 class="w-100"
                                 style="height: 280px; object-fit: cover;"
                                 alt="<?php echo htmlspecialchars($data['habitacion']['estilo']); ?>"
                                 onerror="this.src='<?php echo RUTA_PRINCIPAL; ?>assets/principal/images/default-casa.jpg'">
                            
                            <div class="p-4">
                                <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($data['habitacion']['estilo']); ?></h4>
                                <p class="text-muted mb-4">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?php echo htmlspecialchars($data['habitacion']['direccion'] ?? 'Tecolutla, Veracruz'); ?>
                                </p>
                                
                                <!-- Fechas -->
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <div class="p-3 rounded-3" style="background: var(--airbnb-bg);">
                                            <small class="text-muted d-block">LLEGADA</small>
                                            <strong><?php echo fechaPerzo($_SESSION['reserva']['f_llegada']); ?></strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 rounded-3" style="background: var(--airbnb-bg);">
                                            <small class="text-muted d-block">SALIDA</small>
                                            <strong><?php echo fechaPerzo($_SESSION['reserva']['f_salida']); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Características -->
                                <div class="d-flex flex-wrap gap-3 mb-4">
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                        <i class="fas fa-user-friends me-1"></i> <?php echo $data['habitacion']['capacidad']; ?> huéspedes
                                    </span>
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                        <i class="fas fa-home me-1"></i> Casa #<?php echo $data['habitacion']['numero']; ?>
                                    </span>
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                        <i class="fas fa-moon me-1"></i> <?php echo $noches; ?> noche<?php echo $noches > 1 ? 's' : ''; ?>
                                    </span>
                                </div>
                                
                                <!-- Descripción -->
                                <?php if (!empty($data['habitacion']['descripcion'])): ?>
                                <p class="text-muted">
                                    <?php echo htmlspecialchars($data['habitacion']['descripcion']); ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Columna Derecha - Resumen y Pago -->
                <div class="col-lg-5">
                    <!-- Card de Resumen de Pago -->
                    <div class="card-airbnb mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-receipt me-2" style="color: var(--airbnb-pink);"></i> Resumen de Pago</h5>
                        </div>
                        <div class="card-body">
                            <!-- Desglose -->
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">$<?php echo number_format($precioNoche, 2); ?> MXN x <?php echo $noches; ?> noche<?php echo $noches > 1 ? 's' : ''; ?></span>
                                <span>$<?php echo number_format($subtotal, 2); ?> MXN</span>
                            </div>
                            
                            <?php if ($tarifaLimpieza > 0): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tarifa de limpieza</span>
                                <span>$<?php echo number_format($tarifaLimpieza, 2); ?> MXN</span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Tarifa de servicio</span>
                                <span>$<?php echo number_format($tarifaServicio, 2); ?> MXN</span>
                            </div>
                            
                            <hr>
                            
                            <!-- Total -->
                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="fs-5">Total</strong>
                                <strong class="fs-4" style="color: var(--airbnb-pink);">$<?php echo number_format($total, 2); ?> MXN</strong>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card de Términos y Condiciones -->
                    <div class="card-airbnb mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-file-contract me-3 mt-1" style="color: var(--airbnb-pink); font-size: 20px;"></i>
                                <div class="flex-grow-1">
                                    <p class="mb-2 small text-muted">
                                        Antes de continuar, por favor lee y acepta nuestros términos de uso.
                                    </p>
                                    <button type="button" class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#modalTerminos">
                                        <i class="fas fa-book-open me-1"></i> Leer Términos y Condiciones
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="aceptoTerminos" onchange="toggleBotonPago()">
                                <label class="form-check-label small" for="aceptoTerminos">
                                    <strong>He leído y acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#modalTerminos" style="color: var(--airbnb-pink);">Términos y Condiciones</a></strong>, 
                                    incluyendo las políticas de cancelación, reglas de convivencia y cargos por daños.
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card de Método de Pago -->
                    <div class="card-airbnb" id="cardPago">
                        <div class="card-header">
                            <h5><i class="fas fa-credit-card me-2" style="color: var(--airbnb-pink);"></i> Método de Pago</h5>
                        </div>
                        <div class="card-body">
                            <!-- Mensaje de bloqueo -->
                            <div id="bloqueoPago" class="text-center py-4">
                                <i class="fas fa-lock" style="font-size: 40px; color: #ccc;"></i>
                                <p class="text-muted mt-2 mb-0">Acepta los términos y condiciones para habilitar el pago</p>
                            </div>
                            
                            <!-- Contenedor PayPal (oculto inicialmente) -->
                            <div id="paypal-button-container" style="display: none;"></div>
                            
                            <div class="text-center mt-3" id="infoPago" style="display: none;">
                                <small class="text-muted">
                                    <i class="fas fa-lock me-1"></i> Pago seguro procesado por PayPal
                                </small>
                                <div class="mt-2">
                                    <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" alt="PayPal" class="me-2">
                                    <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" alt="PayPal" height="20">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botón Facturación (Opcional) -->
                    <div class="card-airbnb mt-4">
                        <div class="card-body text-center">
                            <i class="fas fa-file-invoice text-primary mb-2" style="font-size: 24px;"></i>
                            <p class="small text-muted mb-2">¿Necesitas factura para esta reservación?</p>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFacturacion">
                                <i class="fas fa-receipt me-1"></i> Solicitar Factura
                            </button>
                        </div>
                    </div>
                    
                    <!-- Botón cancelar -->
                    <div class="text-center mt-4">
                        <a href="<?php echo RUTA_PRINCIPAL; ?>reserva/cancelar" class="btn btn-outline-airbnb" onclick="return confirm('¿Estás seguro de cancelar esta reserva?');">
                            <i class="fas fa-times me-2"></i> Cancelar Reserva
                        </a>
                    </div>
                </div>
            </div>
            
        <?php } else { ?>
            <!-- Sin reservas pendientes -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-calendar-check" style="font-size: 80px; color: var(--airbnb-light-gray);"></i>
                </div>
                <h3 class="fw-bold mb-2">No tienes reservas pendientes</h3>
                <p class="text-muted mb-4">¿Listo para tu próxima aventura? Explora nuestras casas disponibles.</p>
                <a href="<?php echo RUTA_PRINCIPAL; ?>catalogo" class="btn btn-airbnb">
                    <i class="fas fa-search me-2"></i> Explorar Casas
                </a>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Modal de Términos y Condiciones -->
<div class="modal fade" id="modalTerminos" tabindex="-1" aria-labelledby="modalTerminosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #FF385C, #E31C5F); color: white;">
                <h5 class="modal-title" id="modalTerminosLabel">
                    <i class="fas fa-file-contract me-2"></i>Términos y Condiciones de Uso
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                <h5 class="text-primary">1. Política de Reservación</h5>
                <p>Al realizar una reservación en Casas Via-Mar, usted acepta los siguientes términos y condiciones. La reservación se considera confirmada únicamente después de recibir el pago completo.</p>
                
                <h5 class="text-primary mt-4">2. Política de Cancelación</h5>
                <ul>
                    <li><strong>Cancelación con más de 7 días de anticipación:</strong> Reembolso del 100% menos tarifa de servicio.</li>
                    <li><strong>Cancelación entre 3-7 días:</strong> Reembolso del 50%.</li>
                    <li><strong>Cancelación con menos de 3 días:</strong> No hay reembolso.</li>
                </ul>
                
                <h5 class="text-primary mt-4">3. Reglas de Convivencia</h5>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>El incumplimiento de estas reglas puede resultar en la cancelación inmediata de su reserva sin reembolso.</strong>
                </div>
                <ul>
                    <li>Horario de silencio: 10:00 PM - 8:00 AM</li>
                    <li>No se permiten fiestas ni eventos sin autorización previa.</li>
                    <li>El número máximo de huéspedes debe respetarse según la capacidad de la propiedad.</li>
                    <li>No se permite fumar dentro de las instalaciones.</li>
                    <li>Las mascotas deben ser autorizadas previamente.</li>
                    <li>El huésped es responsable de mantener la propiedad en buenas condiciones.</li>
                </ul>
                
                <h5 class="text-primary mt-4">4. Cargos Extras por Daños Materiales</h5>
                <div class="alert alert-danger">
                    <i class="fas fa-gavel me-2"></i>
                    <strong>IMPORTANTE: Responsabilidad por Daños</strong>
                </div>
                <p>Al aceptar estos términos, usted reconoce y acepta que:</p>
                <ul>
                    <li>Cualquier <strong>daño a la propiedad, mobiliario o equipamiento</strong> será cobrado al huésped.</li>
                    <li>Se realizará una inspección de la propiedad al check-out.</li>
                    <li>Los daños serán evaluados y el costo de reparación o reemplazo será cargado a la tarjeta de crédito/débito registrada.</li>
                    <li>En caso de daños mayores, nos reservamos el derecho de emprender acciones legales.</li>
                </ul>
                <p><strong>Ejemplos de cargos extras:</strong></p>
                <table class="table table-sm table-bordered">
                    <tr><td>Limpieza extraordinaria</td><td>$500 - $2,000 MXN</td></tr>
                    <tr><td>Daño a mobiliario</td><td>Costo de reparación/reemplazo</td></tr>
                    <tr><td>Pérdida de llaves</td><td>$500 MXN</td></tr>
                    <tr><td>Manchas permanentes</td><td>$1,000 - $5,000 MXN</td></tr>
                </table>
                
                <h5 class="text-primary mt-4">5. Horarios de Check-in y Check-out</h5>
                <ul>
                    <li><strong>Check-in:</strong> A partir de las 3:00 PM</li>
                    <li><strong>Check-out:</strong> Antes de las 12:00 PM</li>
                </ul>
                <p>Horarios flexibles pueden solicitarse con anticipación y están sujetos a disponibilidad.</p>
                
                <h5 class="text-primary mt-4">6. Tarifa de Servicio</h5>
                <p>La tarifa de servicio del 12% cubre los costos operativos de la plataforma, soporte al cliente 24/7, y la garantía de reservación segura.</p>
                
                <h5 class="text-primary mt-4">7. Privacidad y Datos Personales</h5>
                <p>Sus datos personales serán tratados conforme a nuestra política de privacidad y la Ley Federal de Protección de Datos Personales.</p>
                
                <h5 class="text-primary mt-4">8. Aceptación de Términos</h5>
                <p>Al marcar la casilla de aceptación y proceder con el pago, usted confirma que ha leído, entendido y aceptado todos los términos y condiciones aquí descritos.</p>
                
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Contacto:</strong> Para cualquier duda o aclaración, contáctenos a 
                    <a href="mailto:xander.3010@gmail.com" class="text-decoration-none fw-bold">xander.3010@gmail.com</a> 
                    <br>
                    <i class="fas fa-phone-alt me-1"></i> <a href="tel:+527822141920" class="text-decoration-none fw-bold">+52 782 214 1920</a>
                    <span class="mx-2">|</span>
                    <a href="tel:+527661151203" class="text-decoration-none fw-bold">+52 766 115 1203</a>
                    <div class="mt-3 d-flex gap-2 flex-wrap">
                        <a href="https://wa.me/527822141920?text=Hola,%20tengo%20una%20consulta%20sobre%20mi%20reservación" 
                           target="_blank" 
                           class="btn btn-success btn-sm">
                            <i class="fab fa-whatsapp me-2"></i>782 214 1920
                        </a>
                        <a href="https://wa.me/527661151203?text=Hola,%20tengo%20una%20consulta%20sobre%20mi%20reservación" 
                           target="_blank" 
                           class="btn btn-success btn-sm">
                            <i class="fab fa-whatsapp me-2"></i>766 115 1203
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-airbnb" data-bs-dismiss="modal" onclick="document.getElementById('aceptoTerminos').checked = true; toggleBotonPago();">
                    <i class="fas fa-check me-1"></i> He leído y Acepto
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Facturación -->
<div class="modal fade" id="modalFacturacion" tabindex="-1" aria-labelledby="modalFacturacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalFacturacionLabel">
                    <i class="fas fa-file-invoice me-2"></i>Solicitar Factura
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Complete los siguientes datos fiscales para generar su factura. La factura será procesada después de confirmar su pago.
                </div>
                
                <form id="formFacturacion">
                    <!-- Tipo de Persona -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tipo de Contribuyente <span class="text-danger">*</span></label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="tipo_persona" id="personaFisica" value="fisica" checked>
                            <label class="btn btn-outline-primary" for="personaFisica">
                                <i class="fas fa-user me-2"></i>Persona Física
                            </label>
                            <input type="radio" class="btn-check" name="tipo_persona" id="personaMoral" value="moral">
                            <label class="btn btn-outline-primary" for="personaMoral">
                                <i class="fas fa-building me-2"></i>Persona Moral (Empresa)
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <!-- RFC -->
                        <div class="col-md-6 mb-3">
                            <label for="rfc" class="form-label">RFC <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" id="rfc" name="rfc" 
                                   maxlength="13" placeholder="XAXX010101000" required 
                                   pattern="^[A-ZÑ&]{3,4}\d{6}[A-Z0-9]{3}$"
                                   title="Ingrese un RFC válido">
                            <small class="text-muted">13 caracteres para persona física, 12 para moral</small>
                        </div>

                        <!-- Código Postal -->
                        <div class="col-md-6 mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" 
                                   maxlength="5" placeholder="12345" required pattern="^\d{5}$">
                        </div>
                    </div>

                    <!-- Razón Social / Nombre -->
                    <div class="mb-3">
                        <label for="razon_social" class="form-label">
                            <span id="labelRazonSocial">Nombre Completo</span> <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="razon_social" name="razon_social" 
                               placeholder="Como aparece en su constancia de situación fiscal" required>
                    </div>

                    <div class="row">
                        <!-- Régimen Fiscal -->
                        <div class="col-md-6 mb-3">
                            <label for="regimen_fiscal" class="form-label">Régimen Fiscal <span class="text-danger">*</span></label>
                            <select class="form-select" id="regimen_fiscal" name="regimen_fiscal" required>
                                <option value="">Seleccionar...</option>
                                <optgroup label="Persona Física" id="regimenesFisica">
                                    <option value="605">605 - Sueldos y Salarios</option>
                                    <option value="606">606 - Arrendamiento</option>
                                    <option value="612">612 - Personas Físicas con Actividades Empresariales</option>
                                    <option value="621">621 - Incorporación Fiscal</option>
                                    <option value="625">625 - Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas</option>
                                    <option value="626">626 - Régimen Simplificado de Confianza</option>
                                </optgroup>
                                <optgroup label="Persona Moral" id="regimenesMoral">
                                    <option value="601">601 - General de Ley Personas Morales</option>
                                    <option value="603">603 - Personas Morales con Fines no Lucrativos</option>
                                    <option value="620">620 - Sociedades Cooperativas de Producción</option>
                                    <option value="622">622 - Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras</option>
                                    <option value="623">623 - Opcional para Grupos de Sociedades</option>
                                    <option value="624">624 - Coordinados</option>
                                </optgroup>
                            </select>
                        </div>

                        <!-- Uso CFDI -->
                        <div class="col-md-6 mb-3">
                            <label for="uso_cfdi" class="form-label">Uso de CFDI <span class="text-danger">*</span></label>
                            <select class="form-select" id="uso_cfdi" name="uso_cfdi" required>
                                <option value="">Seleccionar...</option>
                                <option value="G01">G01 - Adquisición de mercancías</option>
                                <option value="G03">G03 - Gastos en general</option>
                                <option value="D01">D01 - Honorarios médicos, dentales y gastos hospitalarios</option>
                                <option value="D02">D02 - Gastos médicos por incapacidad o discapacidad</option>
                                <option value="D04">D04 - Donativos</option>
                                <option value="P01">P01 - Por definir</option>
                                <option value="S01">S01 - Sin efectos fiscales</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Correo para factura -->
                        <div class="col-md-6 mb-3">
                            <label for="correo_factura" class="form-label">Correo para recibir factura <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="correo_factura" name="correo_factura" 
                                   placeholder="correo@ejemplo.com" required>
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-6 mb-3">
                            <label for="telefono_factura" class="form-label">Teléfono (opcional)</label>
                            <input type="tel" class="form-control" id="telefono_factura" name="telefono" 
                                   placeholder="10 dígitos">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <label for="direccion_fiscal" class="form-label">Dirección Fiscal (opcional)</label>
                        <textarea class="form-control" id="direccion_fiscal" name="direccion" rows="2" 
                                  placeholder="Calle, número, colonia, ciudad, estado"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="guardarDatosFacturacion()">
                    <i class="fas fa-save me-1"></i> Guardar Datos de Facturación
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Cambiar label según tipo de persona
document.querySelectorAll('input[name="tipo_persona"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const label = document.getElementById('labelRazonSocial');
        const rfcInput = document.getElementById('rfc');
        if (this.value === 'moral') {
            label.textContent = 'Razón Social';
            rfcInput.maxLength = 12;
            rfcInput.placeholder = 'XXX010101000';
        } else {
            label.textContent = 'Nombre Completo';
            rfcInput.maxLength = 13;
            rfcInput.placeholder = 'XAXX010101000';
        }
    });
});

// Guardar datos de facturación
function guardarDatosFacturacion() {
    const form = document.getElementById('formFacturacion');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const formData = new FormData(form);
    
    Swal.fire({
        title: 'Guardando datos...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });
    
    fetch(base_url + 'reserva/guardarFacturacion', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.tipo === 'success') {
            Swal.fire({
                icon: 'success',
                title: '¡Datos Guardados!',
                text: 'Sus datos de facturación han sido registrados. La factura se procesará después de confirmar su pago.',
                confirmButtonColor: '#0d6efd'
            });
            bootstrap.Modal.getInstance(document.getElementById('modalFacturacion')).hide();
            // Marcar visualmente que se solicitó factura
            document.querySelector('[data-bs-target="#modalFacturacion"]').innerHTML = '<i class="fas fa-check-circle me-1"></i> Factura Solicitada';
            document.querySelector('[data-bs-target="#modalFacturacion"]').classList.remove('btn-outline-primary');
            document.querySelector('[data-bs-target="#modalFacturacion"]').classList.add('btn-success');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.msg || 'No se pudieron guardar los datos',
                confirmButtonColor: '#dc3545'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor',
            confirmButtonColor: '#dc3545'
        });
    });
}
</script>

<?php include_once 'views/template/footer-cliente.php'; ?>

<?php if (!empty($_SESSION['reserva'])): ?>
<!-- PayPal SDK - Con tarjetas de crédito/débito habilitadas (sin disable-funding=card) -->
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENTE_ID; ?>&currency=<?php echo MONEDA_PAYPAL; ?>&intent=capture"></script>

<script>
// Variables de datos seguros desde PHP (calculados en backend)
const datosReserva = {
    token: '<?php echo $tokenPago; ?>',
    total: <?php echo $totalPaypal; ?>,
    moneda: '<?php echo $moneda; ?>',
    habitacion: <?php echo intval($_SESSION['reserva']['habitacion']); ?>,
    fechaLlegada: '<?php echo $_SESSION['reserva']['f_llegada']; ?>',
    fechaSalida: '<?php echo $_SESSION['reserva']['f_salida']; ?>',
    subtotal: <?php echo $subtotal; ?>,
    tarifaServicio: <?php echo $tarifaServicio; ?>,
    tarifaLimpieza: <?php echo $tarifaLimpieza; ?>
};

// Función para toggle del botón de pago
function toggleBotonPago() {
    const checkbox = document.getElementById('aceptoTerminos');
    const paypalContainer = document.getElementById('paypal-button-container');
    const bloqueoPago = document.getElementById('bloqueoPago');
    const infoPago = document.getElementById('infoPago');
    
    if (checkbox.checked) {
        bloqueoPago.style.display = 'none';
        paypalContainer.style.display = 'block';
        infoPago.style.display = 'block';
    } else {
        bloqueoPago.style.display = 'block';
        paypalContainer.style.display = 'none';
        infoPago.style.display = 'none';
    }
}

// Inicializar PayPal Buttons
paypal.Buttons({
    style: {
        layout: 'vertical',
        color: 'gold',
        shape: 'rect',
        label: 'paypal',
        tagline: false
    },
    
    // Crear la orden - los montos vienen del backend (seguros)
    createOrder: function(data, actions) {
        return actions.order.create({
            application_context: {
                shipping_preference: 'NO_SHIPPING',
                user_action: 'PAY_NOW',
                brand_name: 'Casas Via-Mar'
            },
            purchase_units: [{
                description: 'Reservación Casa Vacacional - Via-Mar',
                custom_id: datosReserva.token,
                amount: {
                    currency_code: datosReserva.moneda,
                    value: datosReserva.total.toFixed(2),
                    breakdown: {
                        item_total: {
                            currency_code: datosReserva.moneda,
                            value: (datosReserva.total - (datosReserva.tarifaServicio / 17.5)).toFixed(2)
                        },
                        handling: {
                            currency_code: datosReserva.moneda,
                            value: (datosReserva.tarifaServicio / 17.5).toFixed(2)
                        }
                    }
                },
                items: [{
                    name: 'Reservación Casa Vacacional',
                    quantity: '1',
                    unit_amount: {
                        currency_code: datosReserva.moneda,
                        value: (datosReserva.total - (datosReserva.tarifaServicio / 17.5)).toFixed(2)
                    }
                }]
            }]
        });
    },

    // Capturar el pago aprobado
    onApprove: function(data, actions) {
        // Mostrar loading
        Swal.fire({
            title: 'Procesando pago...',
            html: 'Por favor espera mientras confirmamos tu reservación.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
        
        return actions.order.capture().then(function(orderData) {
            console.log('Pago capturado:', orderData);
            
            // Enviar datos al servidor para registrar la reserva
            fetch(base_url + 'reserva/registrarReserva', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    orderData: orderData,
                    token: datosReserva.token,
                    terminosAceptados: document.getElementById('aceptoTerminos').checked
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.tipo === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Reservación Confirmada!',
                        html: `
                            <p>Tu pago ha sido procesado exitosamente.</p>
                            <p><strong>ID de Transacción:</strong> ${orderData.id}</p>
                            <p>Recibirás un correo con los detalles de tu reservación.</p>
                        `,
                        confirmButtonText: 'Ver mis reservaciones',
                        confirmButtonColor: '#FF385C'
                    }).then(() => {
                        window.location.href = base_url + 'perfil/reservas';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.msg || 'Hubo un problema al registrar tu reservación. Contacta a soporte.',
                        confirmButtonColor: '#FF385C'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'warning',
                    title: 'Pago Exitoso',
                    html: `
                        <p>Tu pago fue procesado pero hubo un error de conexión.</p>
                        <p><strong>ID de Transacción:</strong> ${orderData.id}</p>
                        <p>Guarda este ID y contacta a soporte si no recibes confirmación.</p>
                    `,
                    confirmButtonColor: '#FF385C'
                });
            });
        });
    },

    // Manejar errores
    onError: function(err) {
        console.error('Error PayPal:', err);
        Swal.fire({
            icon: 'error',
            title: 'Error en el Pago',
            text: 'Hubo un problema con PayPal. Por favor intenta de nuevo.',
            confirmButtonColor: '#FF385C'
        });
    },

    // Cancelación por el usuario
    onCancel: function(data) {
        Swal.fire({
            icon: 'info',
            title: 'Pago Cancelado',
            text: 'Has cancelado el proceso de pago. Tu reserva sigue pendiente.',
            confirmButtonColor: '#FF385C'
        });
    }
    
}).render('#paypal-button-container');
</script>
<?php endif; ?>

</body>
</html>