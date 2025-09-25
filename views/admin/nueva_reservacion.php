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
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
            padding-left: 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .form-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .table-section {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
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
                        <a class="nav-link" href="<?php echo RUTA_ADMIN; ?>dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="<?php echo RUTA_ADMIN; ?>reservas">
                            <i class="fas fa-calendar-alt me-2"></i>Calendario de Reservas
                        </a>
                        <a class="nav-link active" href="<?php echo RUTA_ADMIN; ?>nueva_reservacion">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Reservación
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
                    <div>
                        <h2 class="mb-0">Nueva Reservación</h2>
                        <p class="text-muted mb-0">Gestiona las reservaciones de las casas vacacionales</p>
                    </div>
                    <a href="<?php echo RUTA_ADMIN; ?>reservas" class="btn btn-outline-primary">
                        <i class="fas fa-calendar-alt me-2"></i>Ver Calendario
                    </a>
                </div>

                <!-- Formulario de Nueva Reserva -->
                <div class="form-section">
                    <h4 class="text-primary mb-4">
                        <i class="fas fa-plus-circle me-2"></i>Crear Nueva Reservación
                    </h4>
                    <form id="frmNuevaReserva" class="row g-3">
                        <input type="hidden" name="usuario" value="<?php echo $_SESSION['id_admin']; ?>">
                        
                        <div class="col-md-4">
                            <label for="habitacionReserva" class="form-label">
                                <i class="fas fa-home me-1"></i>Casa Vacacional:
                            </label>
                            <select id="habitacionReserva" name="habitacion" class="form-select" required>
                                <option value="">Seleccionar casa...</option>
                                <?php foreach ($data['habitaciones'] as $habitacion): ?>
                                <option value="<?php echo $habitacion['id']; ?>" data-precio="<?php echo $habitacion['precio']; ?>">
                                    <?php echo isset($habitacion['estilo']) ? $habitacion['estilo'] : 'Casa Vacacional'; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="fechaIngreso" class="form-label">
                                <i class="fas fa-calendar-plus me-1"></i>Fecha Ingreso:
                            </label>
                            <input type="date" id="fechaIngreso" name="fecha_ingreso" class="form-control" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="fechaSalida" class="form-label">
                                <i class="fas fa-calendar-minus me-1"></i>Fecha Salida:
                            </label>
                            <input type="date" id="fechaSalida" name="fecha_salida" class="form-control" required>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="precioTotal" class="form-label">
                                <i class="fas fa-dollar-sign me-1"></i>Precio Total:
                            </label>
                            <input type="number" id="precioTotal" name="precio" class="form-control" step="0.01" readonly>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Crear Reservación
                                </button>
                                <button type="button" id="btnLimpiarForm" class="btn btn-secondary">
                                    <i class="fas fa-broom me-1"></i>Limpiar Formulario
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Filtros -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <label for="filtroHabitacion" class="form-label">
                                            <i class="fas fa-filter me-1"></i>Filtrar por Casa:
                                        </label>
                                        <select id="filtroHabitacion" class="form-select">
                                            <option value="">Todas las casas</option>
                                            <?php foreach ($data['habitaciones'] as $habitacion): ?>
                                            <option value="<?php echo $habitacion['id']; ?>">
                                                <?php echo isset($habitacion['estilo']) ? $habitacion['estilo'] : 'Casa Vacacional'; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-outline-primary" onclick="cargarReservacionesTabla()">
                                            <i class="fas fa-filter me-2"></i>Aplicar Filtro
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Reservaciones -->
                <div class="table-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-primary mb-0">
                            <i class="fas fa-table me-2"></i>Gestión de Reservaciones
                        </h4>
                        <div class="badge bg-info fs-6" id="contadorReservas">
                            <i class="fas fa-list me-1"></i>0 reservaciones
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tablaReservaciones">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                    <th><i class="fas fa-home me-1"></i>Casa</th>
                                    <th><i class="fas fa-calendar-plus me-1"></i>Fecha Ingreso</th>
                                    <th><i class="fas fa-calendar-minus me-1"></i>Fecha Salida</th>
                                    <th><i class="fas fa-moon me-1"></i>Noches</th>
                                    <th><i class="fas fa-dollar-sign me-1"></i>Precio Total</th>
                                    <th><i class="fas fa-info-circle me-1"></i>Estado</th>
                                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="bodyReservaciones">
                                <!-- Las reservaciones se cargarán aquí dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo RUTA_PRINCIPAL; ?>assets/admin/js/nueva_reservacion.js"></script>
</body>
</html>
