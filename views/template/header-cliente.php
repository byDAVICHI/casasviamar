<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo TITLE . ' | ' . $data['title']; ?></title>
    <link rel="icon" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/logodefinitivo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=|Roboto+Sans:400,700|Playfair+Display:400,700">

    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/animate.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/aos.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/jquery.timepicker.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/fancybox.min.css">

    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>fonts/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>fonts/fontawesome/css/font-awesome.min.css">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
        rel="stylesheet" />

    <!-- Theme Style -->
    <link rel="stylesheet" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            width: 250px;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        .sidebar a {
            color: #ddd;
            text-decoration: none;
            display: block;
            padding: 15px;
            font-size: 1rem;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .sidebar h3 {
            padding: 20px 15px;
            margin: 0;
            font-size: 1.5rem;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        /* Ajustes para pantallas pequeñas */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar a {
                text-align: center;
                padding: 10px;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }

        /* Botón para ocultar/mostrar la barra lateral */
        .toggle-sidebar {
            position: fixed;
            top: 15px;
            left: 15px;
            font-size: 1.5rem;
            z-index: 1100;
            background: none;
            border: none;
            color: #343a40;
            cursor: pointer;
            display: none;
        }

        @media (max-width: 768px) {
            .toggle-sidebar {
                display: block;
            }

            .sidebar {
                display: none;
            }

            .sidebar.active {
                display: block;
            }
        }
    </style>


</head>

<body>
    <!-- Botón para alternar la barra lateral -->
    <button class="toggle-sidebar" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h3 class="text-center mt-3"><?php echo $data['title']; ?></h3>

        <a href="#" class="d-flex align-items-center dropdown-toggle text-decoration-none">
            <i class="fas fa-user-circle me-2" style="font-size: 1.5rem;"></i>
            <span><?php echo $_SESSION['nombre_usuario']; ?></span>
        </a>

        <a href="<?php echo RUTA_PRINCIPAL . 'reserva/pendiente'; ?>"><i class="fas fa-clock me-2"></i> Reservas Pendientes</a>
        <a onclick="cerrarSesion()"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a>
    </div>

    <!-- Contenido principal -->

    <script>
        // Función para alternar la visibilidad de la barra lateral
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
    </script>