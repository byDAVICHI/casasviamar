<?php
class Login extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }

    public function index()
    {
        $data['title'] = 'Login';


        $this->views->getView('principal/login', $data);
    }

    public function verify()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (validarCampos(['usuario', 'clave'])) {
                $usuario = strClean($_POST['usuario']);
                $clave = strClean($_POST['clave']);


                // VERIFICAR ACCESO
                $verificar = $this->model->validarAcceso($usuario);
                if (empty($verificar)) {
                    $res = ['tipo' => 'warning', 'msg' => 'EL USUARIO NO EXISTE'];
                } else {
                    if (password_verify($clave, $verificar['clave'])) {
                        // CREAR SESIONES
                        crearSession([
                            'id_usuario' => $verificar['id'],
                            'usuario' => $verificar['usuario'],
                            'correo' => $verificar['correo'],
                            'nombre' => $verificar['nombre'] . ' ' . $verificar['apellido'],
                            'rol' => $verificar['rol']
                        ]);
                        // Agregar foto y nombre a la sesión
                        $_SESSION['foto_usuario'] = $verificar['foto'] ?? null;
                        $_SESSION['nombre_usuario'] = $verificar['nombre'];
                        $_SESSION['usuario'] = $verificar['id'];
                        
                        // Determinar URL de redirección según el rol
                        $rol = strtolower($verificar['rol']);
                        if ($rol === 'admin') {
                            $redirect = 'admin/dashboard';
                        } else {
                            // Usuario/Huésped → directo a reserva pendiente
                            $redirect = 'reserva/pendiente';
                        }
                        
                        $res = [
                            'tipo' => 'success', 
                            'msg' => 'BIENVENIDO',
                            'redirect' => $redirect,
                            'rol' => $rol
                        ];
                    } else {
                        $res = ['tipo' => 'warning', 'msg' => 'CONTRASEÑA INCORRECTA'];
                    }
                }
            } else {
                $res = ['tipo' => 'warning', 'msg' => 'TODOS LOS CAMPOS CON * SON REQUERIDOS'];
            }
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    
    // Cerrar sesión
    public function logout()
    {
        session_destroy();
        header('Location: ' . RUTA_PRINCIPAL);
        exit;
    }
}
