<?php
include_once 'views/template/header-cliente.php';

$nombreUsuario = $data['nombre_usuario'] ?? $_SESSION['nombre_usuario'] ?? 'Huésped';
?>

<div class="main-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Card de Bienvenida -->
                <div class="card-airbnb text-center py-5">
                    <div class="card-body">
                        <!-- Icono decorativo -->
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle" 
                                 style="width: 100px; height: 100px; background: linear-gradient(135deg, #FF385C15, #E31C5F10);">
                                <i class="fas fa-umbrella-beach" style="font-size: 48px; color: var(--airbnb-pink);"></i>
                            </div>
                        </div>
                        
                        <!-- Mensaje de bienvenida -->
                        <h2 class="fw-bold mb-3">¡Hola, <?php echo htmlspecialchars($nombreUsuario); ?>!</h2>
                        <p class="text-muted mb-4 fs-5">
                            Aún no tienes viajes planeados.<br>
                            <span class="fw-medium">¿Listo para tu próxima aventura?</span>
                        </p>
                        
                        <!-- Botón principal -->
                        <a href="<?php echo RUTA_PRINCIPAL; ?>catalogo" class="btn btn-airbnb btn-lg px-5">
                            <i class="fas fa-search me-2"></i> Explorar Casas
                        </a>
                        
                        <!-- Links secundarios -->
                        <div class="mt-4 pt-4 border-top">
                            <div class="row g-3 justify-content-center">
                                <div class="col-auto">
                                    <a href="<?php echo RUTA_PRINCIPAL; ?>perfil" class="text-decoration-none text-muted">
                                        <i class="fas fa-user me-1"></i> Mi Perfil
                                    </a>
                                </div>
                                <div class="col-auto">
                                    <span class="text-muted">•</span>
                                </div>
                                <div class="col-auto">
                                    <a href="<?php echo RUTA_PRINCIPAL; ?>perfil/reservas" class="text-decoration-none text-muted">
                                        <i class="fas fa-history me-1"></i> Historial de Viajes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sugerencias -->
                <div class="mt-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-lightbulb me-2" style="color: #ffc107;"></i> 
                        Descubre nuestras propiedades
                    </h5>
                    <div class="card-airbnb">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-home" style="font-size: 32px; color: var(--airbnb-pink);"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">Casas frente al mar</h6>
                                <p class="mb-0 text-muted small">Disfruta de las mejores vistas en Tecolutla, Veracruz</p>
                            </div>
                            <a href="<?php echo RUTA_PRINCIPAL; ?>catalogo" class="btn btn-outline-airbnb btn-sm">
                                Ver <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/template/footer-cliente.php'; ?>
</body>
</html>