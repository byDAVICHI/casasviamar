<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="shortcut icon" href="#" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Estilo personalizado -->
    <link rel="stylesheet" href="estilos.css">
    <!-- FontAwesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="dashboard/img/logodefinitivo.png">
</head>

<body class="bg-light">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-lg" style="width: 400px; border-radius: 20px;">
            <div class="card-body text-center">
                <h3 class="mb-4 text-primary">Bienvenido</h3>
                <form id="formLogin" action="" method="post">
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuario" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña" required>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary w-100 mb-3">Iniciar Sesión</button>
                </form>
                <!-- Botón para regresar -->

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="jquery/jquery-3.3.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="popper/popper.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="codigo.js"></script>
</body>

</html>