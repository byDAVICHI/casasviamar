<?php
include_once 'views/template/header-cliente.php';

$fotoUsuario = $_SESSION['foto_usuario'] ?? null;
$nombreCompleto = $_SESSION['nombre_usuario'] ?? 'Usuario';
$inicialUsuario = strtoupper(substr($nombreCompleto, 0, 1));
$reservas = $data['reservas'] ?? [];
?>

<div class="main-wrapper">
    <div class="container">
        <div class="row g-4">
            <!-- Columna Izquierda - Menú de Navegación -->
            <div class="col-lg-3">
                <div class="profile-nav">
                    <!-- Avatar grande -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <?php if ($fotoUsuario && file_exists('assets/img/usuarios/' . $fotoUsuario)): ?>
                                <img src="<?php echo RUTA_PRINCIPAL . 'assets/img/usuarios/' . $fotoUsuario; ?>" 
                                     alt="<?php echo htmlspecialchars($nombreCompleto); ?>" 
                                     class="rounded-circle"
                                     style="width: 100px; height: 100px; object-fit: cover; border: 3px solid var(--airbnb-light-gray);">
                            <?php else: ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--airbnb-pink), #E31C5F); color: #fff; font-size: 36px; font-weight: 600; margin: 0 auto;">
                                    <?php echo $inicialUsuario; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h5 class="mt-3 mb-1 fw-bold"><?php echo htmlspecialchars($nombreCompleto); ?></h5>
                    </div>
                    
                    <!-- Links de navegación -->
                    <a href="<?php echo RUTA_PRINCIPAL; ?>perfil" class="profile-nav-item <?php echo ($data['active'] ?? '') === 'perfil' ? 'active' : ''; ?>">
                        <i class="fas fa-user"></i> Información Personal
                    </a>
                    <a href="<?php echo RUTA_PRINCIPAL; ?>perfil/seguridad" class="profile-nav-item <?php echo ($data['active'] ?? '') === 'seguridad' ? 'active' : ''; ?>">
                        <i class="fas fa-shield-alt"></i> Seguridad
                    </a>
                    <a href="<?php echo RUTA_PRINCIPAL; ?>perfil/reservas" class="profile-nav-item <?php echo ($data['active'] ?? '') === 'reservas' ? 'active' : ''; ?>">
                        <i class="fas fa-suitcase"></i> Mis Viajes
                    </a>
                    <a href="<?php echo RUTA_PRINCIPAL; ?>reserva/pendiente" class="profile-nav-item">
                        <i class="fas fa-clock"></i> Reservas Pendientes
                    </a>
                </div>
            </div>
            
            <!-- Columna Derecha - Contenido -->
            <div class="col-lg-9">
                <div class="card-airbnb">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-suitcase me-2" style="color: var(--airbnb-pink);"></i> Mis Viajes</h5>
                        <span class="badge rounded-pill" style="background: var(--airbnb-bg); color: var(--airbnb-dark);">
                            <?php echo count($reservas); ?> reserva<?php echo count($reservas) != 1 ? 's' : ''; ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <?php if (count($reservas) > 0): ?>
                            <div class="d-flex flex-column gap-3">
                                <?php foreach ($reservas as $reserva): 
                                    $fechaIngreso = new DateTime($reserva['fecha_ingreso']);
                                    $fechaSalida = new DateTime($reserva['fecha_salida']);
                                    $noches = $fechaSalida->diff($fechaIngreso)->days;
                                    $esPasada = $fechaSalida < new DateTime();
                                    $esActiva = $fechaIngreso <= new DateTime() && $fechaSalida >= new DateTime();
                                ?>
                                <div class="reserva-card">
                                    <img src="<?php echo obtenerRutaImagenCasa($reserva['foto']); ?>" 
                                         alt="<?php echo htmlspecialchars($reserva['estilo']); ?>"
                                         class="reserva-card-img"
                                         onerror="this.src='<?php echo RUTA_PRINCIPAL; ?>assets/principal/images/default-casa.jpg'">
                                    <div class="reserva-card-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="reserva-card-title"><?php echo htmlspecialchars($reserva['estilo']); ?></h6>
                                                <p class="reserva-card-dates mb-1">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <?php echo $fechaIngreso->format('d M Y'); ?> - <?php echo $fechaSalida->format('d M Y'); ?>
                                                </p>
                                                <p class="text-muted small mb-0">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    <?php echo htmlspecialchars($reserva['direccion'] ?? 'Tecolutla, Veracruz'); ?>
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <?php if ($esActiva): ?>
                                                    <span class="badge-confirmada">
                                                        <i class="fas fa-check-circle me-1"></i> Activa
                                                    </span>
                                                <?php elseif ($esPasada): ?>
                                                    <span class="badge bg-secondary px-3 py-2 rounded-pill" style="font-size: 12px;">
                                                        Completada
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge-confirmada">
                                                        <i class="fas fa-calendar-check me-1"></i> Próxima
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <span class="text-muted small">
                                                <i class="fas fa-moon me-1"></i> <?php echo $noches; ?> noche<?php echo $noches > 1 ? 's' : ''; ?>
                                            </span>
                                            <span class="reserva-card-price" style="color: var(--airbnb-pink);">
                                                $<?php echo number_format($reserva['precio'] ?? 0, 2); ?> MXN
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <!-- Sin reservas -->
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-suitcase-rolling" style="font-size: 64px; color: var(--airbnb-light-gray);"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Aún no tienes viajes</h5>
                                <p class="text-muted mb-4">Cuando reserves una casa, aparecerá aquí.</p>
                                <a href="<?php echo RUTA_PRINCIPAL; ?>catalogo" class="btn btn-airbnb">
                                    <i class="fas fa-search me-2"></i> Explorar Casas
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/template/footer-cliente.php'; ?>

</body>
</html>
