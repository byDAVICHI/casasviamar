<?php
include_once 'views/template/header-cliente.php';
error_reporting(0);

// Variables de precio
$precioNoche = floatval($data['habitacion']['precio'] ?? 0);
$noches = intval($data['noches'] ?? 0);
$subtotal = floatval($data['subtotal'] ?? 0);
$tarifaLimpieza = floatval($data['tarifa_limpieza'] ?? 0);
$tarifaServicio = floatval($data['tarifa_servicio'] ?? 0);
$total = floatval($data['total'] ?? 0);
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
                    
                    <!-- Card de Método de Pago -->
                    <div class="card-airbnb">
                        <div class="card-header">
                            <h5><i class="fas fa-credit-card me-2" style="color: var(--airbnb-pink);"></i> Método de Pago</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">Completa tu pago de forma segura con PayPal</p>
                            
                            <!-- Contenedor PayPal -->
                            <div id="paypal-button-container"></div>
                            
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-lock me-1"></i> Pago seguro procesado por PayPal
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botón cancelar -->
                    <div class="text-center mt-4">
                        <a href="<?php echo RUTA_PRINCIPAL; ?>catalogo" class="btn btn-outline-airbnb">
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


<?php
include_once 'views/template/footer-cliente.php';
?>

<script src="https://www.paypal.com/sdk/js?client-id=test&currency=MXN"></script>
<script src="https://sandbox.paypal.com/sdk/js?client-id=<?php echo CLIENTE_ID; ?>"></script>
<!-- <script src="https://sdk.mercadopago.com/js/v2"> </script> -->
<script>
    // Render the PayPal button into #paypal-button-container
    paypal.Buttons({

        // Call your server to set up the transaction
        createOrder: function(data, actions) {
            return actions.order.create({
                aPplication_context: {
                    shipping_preference: 'NO_SHIPPING',
                },
                purchase_units: [{
                    amount: {
                        curreny_code: '<?php echo MONEDA_PAYPAL; ?>',
                        value: '<?php echo $_SESSION['total']; ?>',
                    }
                }]
            })
        },

        // Call your server to finalize the transaction
        onApprove: function(data, actions) {
            console.log(data);
            return actions.order.capture().then(function(orderData) {
                fetch(base_url + 'reserva/registrarReserva', {
                    method: 'post',
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        reserva: orderData
                    })
                }).then(function(res) {
                    alert('TRANSACCION EXITOSA');

                    return res.json();
                    // FUNCION
                    /* dato.append('total', total);
                     dato.append('f_llegada', f_llegada);
                     dato.append('f_salida', f_salida);
                     dato.append('habitacion', habitacion);
                     dato.append('usuario', usuario);
                     fetch('../reservas/transaccion.php', {
                         method: "POST",
                         body: dato
                     }).then(res => res.text()) */
                    // Redirige a una nueva pantalla
                    window.location.href = '<?php echo RUTA_PRINCIPAL . 'views/principal/clientes/reservas'; ?>transaccion.php"';
                }).then(function(orderData) {

                });
            })

        }

    }).render('#paypal-button-container');

    /* MERCADO PAGO
    const mp = new MercadoPago('<?php echo PUBLIC_KEY; ?>');
    const bricksBuilder = mp.bricks();

    mp.bricks().create("wallet", "wallet_container", {
        initialization: {
            preferenceId: "<?php echo $data['preference_id']; ?>",
        },
        customization: {
            texts: {
                valueProp: 'smart_option',
            },
        },
    });
    */
</script>




</body>

</html>