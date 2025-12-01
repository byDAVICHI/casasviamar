<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo TITLE . ' | ' . $data['title']; ?></title>
    <link rel="icon" href="<?php echo RUTA_PRINCIPAL . 'assets/principal/'; ?>images/logodefinitivo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- SweetAlert2 JS (cargado en head para que esté disponible) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --airbnb-pink: #FF385C;
            --airbnb-dark: #222222;
            --airbnb-gray: #717171;
            --airbnb-light-gray: #DDDDDD;
            --airbnb-bg: #F7F7F7;
        }
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        body {
            background-color: var(--airbnb-bg);
            color: var(--airbnb-dark);
        }
        
        /* Navbar Superior */
        .navbar-cliente {
            background: #fff;
            border-bottom: 1px solid var(--airbnb-light-gray);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-logo img {
            height: 36px;
        }
        
        .navbar-logo span {
            color: var(--airbnb-pink);
            font-weight: 700;
            font-size: 1.3rem;
            margin-left: 8px;
        }
        
        /* Avatar del usuario */
        .user-menu-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 12px 6px 16px;
            border: 1px solid var(--airbnb-light-gray);
            border-radius: 24px;
            background: #fff;
            cursor: pointer;
            transition: box-shadow 0.2s;
        }
        
        .user-menu-btn:hover {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--airbnb-light-gray);
        }
        
        .user-avatar-placeholder {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--airbnb-pink), #E31C5F);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }
        
        /* Dropdown menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 16px rgba(0,0,0,0.12);
            border-radius: 12px;
            padding: 8px 0;
            min-width: 240px;
        }
        
        .dropdown-item {
            padding: 12px 16px;
            font-size: 14px;
            color: var(--airbnb-dark);
            cursor: pointer;
        }
        
        .dropdown-item:hover {
            background: var(--airbnb-bg);
        }
        
        .dropdown-item i {
            width: 20px;
            margin-right: 12px;
            color: var(--airbnb-gray);
        }
        
        .dropdown-divider {
            margin: 8px 0;
        }
        
        /* Contenedor principal */
        .main-wrapper {
            padding: 40px 0;
            min-height: calc(100vh - 80px);
        }
        
        /* Menu lateral de perfil */
        .profile-nav {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
        }
        
        .profile-nav-item {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            border-radius: 12px;
            color: var(--airbnb-dark);
            text-decoration: none;
            margin-bottom: 4px;
            transition: background 0.2s;
        }
        
        .profile-nav-item:hover {
            background: var(--airbnb-bg);
            color: var(--airbnb-dark);
        }
        
        .profile-nav-item.active {
            background: var(--airbnb-bg);
            font-weight: 600;
        }
        
        .profile-nav-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 18px;
            color: var(--airbnb-gray);
        }
        
        .profile-nav-item.active i {
            color: var(--airbnb-pink);
        }
        
        /* Cards */
        .card-airbnb {
            background: #fff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .card-airbnb .card-header {
            background: #fff;
            border-bottom: 1px solid var(--airbnb-light-gray);
            padding: 20px 24px;
        }
        
        .card-airbnb .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
        }
        
        .card-airbnb .card-body {
            padding: 24px;
        }
        
        /* Botones */
        .btn-airbnb {
            background: linear-gradient(to right, #E61E4D, #E31C5F, #D70466);
            border: none;
            color: #fff;
            padding: 14px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-airbnb:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(227, 28, 95, 0.4);
            color: #fff;
        }
        
        .btn-outline-airbnb {
            border: 1px solid var(--airbnb-dark);
            color: var(--airbnb-dark);
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            background: transparent;
        }
        
        .btn-outline-airbnb:hover {
            background: var(--airbnb-dark);
            color: #fff;
        }
        
        /* Formularios */
        .form-control-airbnb {
            border: 1px solid var(--airbnb-light-gray);
            border-radius: 8px;
            padding: 14px 16px;
            font-size: 16px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .form-control-airbnb:focus {
            border-color: var(--airbnb-dark);
            box-shadow: 0 0 0 2px rgba(34, 34, 34, 0.1);
        }
        
        .form-label-airbnb {
            font-weight: 500;
            font-size: 14px;
            color: var(--airbnb-dark);
            margin-bottom: 8px;
        }
        
        /* Reserva Card */
        .reserva-card {
            display: flex;
            gap: 20px;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--airbnb-light-gray);
            transition: box-shadow 0.2s;
        }
        
        .reserva-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .reserva-card-img {
            width: 140px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
        }
        
        .reserva-card-content {
            flex: 1;
        }
        
        .reserva-card-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }
        
        .reserva-card-dates {
            color: var(--airbnb-gray);
            font-size: 14px;
        }
        
        .reserva-card-price {
            font-weight: 600;
            font-size: 16px;
            margin-top: 8px;
        }
        
        .badge-pendiente {
            background: #FFF3CD;
            color: #856404;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-confirmada {
            background: #D4EDDA;
            color: #155724;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .profile-nav {
                margin-bottom: 24px;
            }
            
            .reserva-card {
                flex-direction: column;
            }
            
            .reserva-card-img {
                width: 100%;
                height: 160px;
            }
        }
    </style>
    
    <script>
        // Función para cerrar sesión
        function cerrarSesion() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que deseas salir?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FF385C',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?php echo RUTA_PRINCIPAL; ?>login/logout';
                }
            });
        }
        
        // Función para navegar
        function navegarA(url) {
            window.location.href = url;
        }
    </script>
</head>

<body>
    <?php 
    // Obtener foto del usuario o mostrar inicial
    $fotoUsuario = $_SESSION['foto_usuario'] ?? null;
    $nombreUsuario = $_SESSION['nombre_usuario'] ?? 'Usuario';
    $inicialUsuario = strtoupper(substr($nombreUsuario, 0, 1));
    ?>
    
    <!-- Navbar Superior -->
    <nav class="navbar-cliente">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Logo -->
                <a href="<?php echo RUTA_PRINCIPAL; ?>" class="navbar-logo text-decoration-none d-flex align-items-center">
                    <img src="<?php echo RUTA_PRINCIPAL; ?>assets/principal/images/logodefinitivo.png" alt="Via-Mar">
                    <span>Via-Mar</span>
                </a>
                
                <!-- Menu Usuario -->
                <div class="dropdown">
                    <button class="user-menu-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                        <?php if ($fotoUsuario && file_exists('assets/img/usuarios/' . $fotoUsuario)): ?>
                            <img src="<?php echo RUTA_PRINCIPAL . 'assets/img/usuarios/' . $fotoUsuario; ?>" alt="<?php echo $nombreUsuario; ?>" class="user-avatar">
                        <?php else: ?>
                            <div class="user-avatar-placeholder"><?php echo $inicialUsuario; ?></div>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo RUTA_PRINCIPAL; ?>perfil"><i class="fas fa-user"></i> Mi Perfil</a></li>
                        <li><a class="dropdown-item" href="<?php echo RUTA_PRINCIPAL; ?>perfil/reservas"><i class="fas fa-suitcase"></i> Mis Viajes</a></li>
                        <li><a class="dropdown-item" href="<?php echo RUTA_PRINCIPAL; ?>reserva/pendiente"><i class="fas fa-clock"></i> Reservas Pendientes</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo RUTA_PRINCIPAL; ?>catalogo"><i class="fas fa-home"></i> Explorar Casas</a></li>
                        <li><a class="dropdown-item" href="<?php echo RUTA_PRINCIPAL; ?>"><i class="fas fa-globe"></i> Ir al Inicio</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="cerrarSesion()"><i class="fas fa-sign-out-alt text-danger"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Contenido Principal -->
