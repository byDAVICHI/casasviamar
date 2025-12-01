<?php

class Perfil extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Verificar que el usuario esté logueado
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . RUTA_PRINCIPAL . 'login');
            exit;
        }
    }

    // Vista principal del perfil
    public function index()
    {
        $data['title'] = 'Mi Perfil';
        $data['active'] = 'perfil';
        $data['usuario'] = $this->model->getUsuarioById($_SESSION['id_usuario']);
        $this->views->getView('principal/perfil/index', $data);
    }

    // Vista de seguridad (cambiar contraseña)
    public function seguridad()
    {
        $data['title'] = 'Seguridad';
        $data['active'] = 'seguridad';
        $data['usuario'] = $this->model->getUsuarioById($_SESSION['id_usuario']);
        $this->views->getView('principal/perfil/seguridad', $data);
    }

    // Vista de reservas del usuario
    public function reservas()
    {
        $data['title'] = 'Mis Viajes';
        $data['active'] = 'reservas';
        $data['reservas'] = $this->model->getReservasUsuario($_SESSION['id_usuario']);
        $this->views->getView('principal/perfil/reservas', $data);
    }

    // Actualizar datos personales (AJAX)
    public function actualizarDatos()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['tipo' => 'error', 'msg' => 'Método no permitido']);
            die();
        }

        $id = $_SESSION['id_usuario'];
        $nombre = strClean($_POST['nombre'] ?? '');
        $apellido = strClean($_POST['apellido'] ?? '');
        $usuario = strClean($_POST['usuario'] ?? '');
        $correo = strClean($_POST['correo'] ?? '');

        // Validaciones
        if (empty($nombre) || empty($usuario) || empty($correo)) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Todos los campos son obligatorios']);
            die();
        }

        // Verificar que el correo no esté en uso por otro usuario
        $existeCorreo = $this->model->verificarCorreoExistente($correo, $id);
        if ($existeCorreo) {
            echo json_encode(['tipo' => 'error', 'msg' => 'El correo ya está registrado por otro usuario']);
            die();
        }

        // Verificar que el usuario no esté en uso
        $existeUsuario = $this->model->verificarUsuarioExistente($usuario, $id);
        if ($existeUsuario) {
            echo json_encode(['tipo' => 'error', 'msg' => 'El nombre de usuario ya está en uso']);
            die();
        }

        // Actualizar datos
        $resultado = $this->model->actualizarUsuario($id, $nombre, $apellido, $usuario, $correo);
        
        if ($resultado) {
            // Actualizar sesión
            $_SESSION['nombre_usuario'] = $nombre;
            echo json_encode(['tipo' => 'success', 'msg' => 'Datos actualizados correctamente']);
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Error al actualizar los datos']);
        }
        die();
    }

    // Cambiar contraseña (AJAX)
    public function cambiarPassword()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['tipo' => 'error', 'msg' => 'Método no permitido']);
            die();
        }

        $id = $_SESSION['id_usuario'];
        $passwordActual = $_POST['password_actual'] ?? '';
        $passwordNueva = $_POST['password_nueva'] ?? '';
        $passwordConfirmar = $_POST['password_confirmar'] ?? '';

        // Validaciones
        if (empty($passwordActual) || empty($passwordNueva) || empty($passwordConfirmar)) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Todos los campos son obligatorios']);
            die();
        }

        if ($passwordNueva !== $passwordConfirmar) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Las contraseñas no coinciden']);
            die();
        }

        if (strlen($passwordNueva) < 6) {
            echo json_encode(['tipo' => 'error', 'msg' => 'La contraseña debe tener al menos 6 caracteres']);
            die();
        }

        // Verificar contraseña actual
        $usuario = $this->model->getUsuarioById($id);
        if (!password_verify($passwordActual, $usuario['clave'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'La contraseña actual es incorrecta']);
            die();
        }

        // Actualizar contraseña
        $passwordHash = password_hash($passwordNueva, PASSWORD_DEFAULT);
        $resultado = $this->model->actualizarPassword($id, $passwordHash);
        
        if ($resultado) {
            echo json_encode(['tipo' => 'success', 'msg' => 'Contraseña actualizada correctamente']);
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Error al cambiar la contraseña']);
        }
        die();
    }

    // Subir foto de perfil (AJAX)
    public function subirFoto()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['tipo' => 'error', 'msg' => 'Método no permitido']);
            die();
        }

        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No se recibió ninguna imagen']);
            die();
        }

        $id = $_SESSION['id_usuario'];
        $archivo = $_FILES['foto'];
        
        // Validar tipo de archivo
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($archivo['type'], $tiposPermitidos)) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Tipo de archivo no permitido. Use JPG, PNG, GIF o WEBP']);
            die();
        }

        // Validar tamaño (máx 5MB)
        if ($archivo['size'] > 5 * 1024 * 1024) {
            echo json_encode(['tipo' => 'error', 'msg' => 'La imagen no debe superar 5MB']);
            die();
        }

        // Crear directorio si no existe
        $directorio = 'assets/img/usuarios/';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        // Eliminar foto anterior si existe
        $usuarioActual = $this->model->getUsuarioById($id);
        if (!empty($usuarioActual['foto']) && file_exists($directorio . $usuarioActual['foto'])) {
            unlink($directorio . $usuarioActual['foto']);
        }

        // Generar nombre único
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = 'user_' . $id . '_' . time() . '.' . $extension;
        $rutaDestino = $directorio . $nombreArchivo;

        // Mover archivo
        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            // Actualizar en BD
            $resultado = $this->model->actualizarFoto($id, $nombreArchivo);
            
            if ($resultado) {
                // Actualizar sesión
                $_SESSION['foto_usuario'] = $nombreArchivo;
                echo json_encode([
                    'tipo' => 'success', 
                    'msg' => 'Foto actualizada correctamente',
                    'foto' => RUTA_PRINCIPAL . $rutaDestino
                ]);
            } else {
                echo json_encode(['tipo' => 'error', 'msg' => 'Error al guardar en la base de datos']);
            }
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Error al subir la imagen']);
        }
        die();
    }

    // Eliminar foto de perfil (AJAX)
    public function eliminarFoto()
    {
        header('Content-Type: application/json');
        
        $id = $_SESSION['id_usuario'];
        $directorio = 'assets/img/usuarios/';
        
        // Obtener foto actual
        $usuario = $this->model->getUsuarioById($id);
        
        if (!empty($usuario['foto']) && file_exists($directorio . $usuario['foto'])) {
            unlink($directorio . $usuario['foto']);
        }
        
        // Actualizar BD
        $resultado = $this->model->actualizarFoto($id, null);
        
        if ($resultado) {
            $_SESSION['foto_usuario'] = null;
            echo json_encode(['tipo' => 'success', 'msg' => 'Foto eliminada correctamente']);
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Error al eliminar la foto']);
        }
        die();
    }
}
