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
                        $res = ['tipo' => 'success', 'msg' => 'BIENVENIDO'];
                        $_SESSION['usuario'] = $verificar['id'];
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
}
