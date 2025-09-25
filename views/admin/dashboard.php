<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="icon" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/logodefinitivo.png" type="image/png">
    <link rel="shortcut icon" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/logodefinitivo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            border-radius: 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            padding: 2rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-card-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        .stat-card-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .navbar-brand {
            font-weight: bold;
            color: #667eea !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-user-shield me-2"></i>Admin Panel
                    </h4>
                    <hr class="text-white">
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="<?php echo RUTA_ADMIN; ?>dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="<?php echo RUTA_ADMIN; ?>reservas">
                            <i class="fas fa-calendar-alt me-2"></i>Gestión de Reservas
                        </a>
                        <hr class="text-white">
                        <a class="nav-link" href="<?php echo RUTA_ADMIN; ?>logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Dashboard</h1>
                    <div>
                        <span class="text-muted">Bienvenido, </span>
                        <strong><?php echo isset($_SESSION['nombre_admin']) ? $_SESSION['nombre_admin'] : 'Administrador'; ?></strong>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-check fa-2x mb-3"></i>
                                <h3 class="mb-1"><?php echo $data['total_reservas']; ?></h3>
                                <p class="mb-0">Total Reservas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card-success">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-day fa-2x mb-3"></i>
                                <h3 class="mb-1"><?php echo $data['reservas_hoy']; ?></h3>
                                <p class="mb-0">Reservas Hoy</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x mb-3"></i>
                                <h3 class="mb-1"><?php echo $data['usuarios_total']; ?></h3>
                                <p class="mb-0">Total Usuarios</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card-info">
                            <div class="card-body text-center">
                                <i class="fas fa-home fa-2x mb-3"></i>
                                <h3 class="mb-1"><?php echo count($data['habitaciones']); ?></h3>
                                <p class="mb-0">Casas Disponibles</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <a href="<?php echo RUTA_ADMIN; ?>reservas" class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-calendar-plus me-2"></i>Gestionar Reservas
                                        </a>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="<?php echo RUTA_PRINCIPAL; ?>" class="btn btn-outline-primary btn-lg w-100" target="_blank">
                                            <i class="fas fa-external-link-alt me-2"></i>Ver Sitio Web
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Habitaciones Overview -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-home me-2"></i>Casas Vacacionales
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($data['habitaciones'] as $habitacion): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-title"><?php echo isset($habitacion['estilo']) ? $habitacion['estilo'] : 'Casa Vacacional'; ?></h6>
                                                <p class="card-text text-muted"><?php echo isset($habitacion['descripcion']) ? substr($habitacion['descripcion'], 0, 100) . '...' : 'Sin descripción'; ?></p>
                                                <p class="card-text">
                                                    <strong>Precio: $<?php echo number_format($habitacion['precio'], 2); ?>/noche</strong>
                                                </p>
                                                <a href="<?php echo RUTA_ADMIN; ?>reservas?habitacion=<?php echo $habitacion['id']; ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    Ver Reservas
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        const base_url = '<?php echo RUTA_ADMIN; ?>';
        
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
    </script>
</body>
</html>
