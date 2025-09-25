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
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
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
        .fc-event {
            cursor: pointer;
        }
        .fc-toolbar-title {
            font-size: 1.5rem !important;
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
                        <a class="nav-link active" href="<?php echo RUTA_ADMIN; ?>reservas">
                            <i class="fas fa-calendar-alt me-2"></i>Calendario de Reservas
                        </a>
                        <a class="nav-link" href="<?php echo RUTA_ADMIN; ?>nueva_reservacion">
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
                        <h2 class="mb-0">Calendario de Reservas</h2>
                        <p class="text-muted mb-0">Visualización de todas las reservaciones en calendario interactivo</p>
                    </div>
                    <a href="<?php echo RUTA_ADMIN; ?>nueva_reservacion" class="btn btn-success">
                        <i class="fas fa-plus-circle me-2"></i>Nueva Reservación
                    </a>
                </div>

                <!-- Filtros -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <label for="filtroHabitacion" class="form-label">Filtrar por Casa:</label>
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
                                        <button type="button" class="btn btn-outline-primary" onclick="calendar.refetchEvents()">
                                            <i class="fas fa-filter me-2"></i>Aplicar Filtro
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar me-2"></i>Calendario de Reservas
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="calendar"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/es.js"></script>
    
    <script>
        const base_url = '<?php echo RUTA_ADMIN; ?>';
        let calendar;
        
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
            console.log('📅 Inicializando calendario de reservas...');
            
            // Inicializar Select2 para filtros
            setTimeout(() => {
                $('#filtroHabitacion').select2({
                    theme: 'default',
                    width: '100%'
                });
            }, 500);

            // Inicializar calendario
            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    const habitacion = $('#filtroHabitacion').val();
                    let url = base_url + 'getReservas';
                    if (habitacion) {
                        url += '?habitacion=' + habitacion;
                    }

                    fetch(url, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => {
                        console.error('Error:', error);
                        failureCallback(error);
                    });
                },
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    Swal.fire({
                        title: 'Información de la Reserva',
                        html: `
                            <div class="text-start">
                                <p><strong>ID:</strong> #${info.event.id}</p>
                                <p><strong>Usuario:</strong> ${props.usuario}</p>
                                <p><strong>Casa:</strong> ${props.habitacion}</p>
                                <p><strong>Fecha Ingreso:</strong> ${info.event.start}</p>
                                <p><strong>Fecha Salida:</strong> ${info.event.end}</p>
                                <p><strong>Precio Total:</strong> $${props.precio.toFixed(2)}</p>
                                <p><strong>Estado:</strong> ${props.estado === 1 ? 'Activa' : 'Inactiva'}</p>
                            </div>
                        `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fas fa-edit"></i> Gestionar Reserva',
                        cancelButtonText: 'Cerrar',
                        confirmButtonColor: '#28a745'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = base_url + 'nueva_reservacion';
                        }
                    });
                },
                height: 'auto'
            });
            calendar.render();

            // Event listeners
            $('#filtroHabitacion').on('change', function() {
                calendar.refetchEvents();
            });
        });
    </script>
</body>
</html>
