<?php
class Admin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }

    public function login()
    {
        $data['title'] = 'Administrador - Login';
        $this->views->getView('admin/login', $data);
    }

    public function verify()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (validarCampos(['usuario', 'clave'])) {
                $usuario = strClean($_POST['usuario']);
                $clave = strClean($_POST['clave']);

                // VERIFICAR ACCESO DE ADMINISTRADOR
                $verificar = $this->model->validarAccesoAdmin($usuario);
                if (empty($verificar)) {
                    $res = ['tipo' => 'warning', 'msg' => 'EL USUARIO ADMINISTRADOR NO EXISTE'];
                } else {
                    if (password_verify($clave, $verificar['clave'])) {
                        // VERIFICAR QUE SEA ADMINISTRADOR (acepta 'admin' o 1)
                        if ($verificar['rol'] == 'admin' || $verificar['rol'] == 1) {
                            // CREAR SESIONES DE ADMINISTRADOR
                            crearSession([
                                'id_admin' => $verificar['id'],
                                'usuario_admin' => $verificar['usuario'],
                                'correo_admin' => $verificar['correo'],
                                'nombre_admin' => $verificar['nombre'] . ' ' . $verificar['apellido'],
                                'rol_admin' => $verificar['rol']
                            ]);
                            $res = ['tipo' => 'success', 'msg' => 'BIENVENIDO ADMINISTRADOR'];
                        } else {
                            $res = ['tipo' => 'warning', 'msg' => 'NO TIENES PERMISOS DE ADMINISTRADOR'];
                        }
                    } else {
                        $res = ['tipo' => 'warning', 'msg' => 'CONTRASEÑA INCORRECTA'];
                    }
                }
            } else {
                $res = ['tipo' => 'warning', 'msg' => 'TODOS LOS CAMPOS SON REQUERIDOS'];
            }
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function dashboard()
    {
        // VERIFICAR SESIÓN DE ADMINISTRADOR
        if (!isset($_SESSION['id_admin'])) {
            header('Location: ' . RUTA_ADMIN);
            exit;
        }
        
        // Debug temporal: verificar si existe nombre_admin
        if (!isset($_SESSION['nombre_admin'])) {
            // Si no existe, intentar recrearlo desde otros datos de sesión
            if (isset($_SESSION['usuario_admin'])) {
                $_SESSION['nombre_admin'] = 'Administrador del Sistema';
            }
        }
        
        $data['title'] = 'Dashboard - Administrador';
        $data['habitaciones'] = $this->model->getHabitaciones();
        $data['total_reservas'] = $this->model->getTotalReservas();
        $data['reservas_hoy'] = $this->model->getReservasHoy();
        $data['usuarios_total'] = $this->model->getTotalUsuarios();
        
        $this->views->getView('admin/dashboard', $data);
    }

    public function reservas()
    {
        // VERIFICAR SESIÓN DE ADMINISTRADOR
        if (!isset($_SESSION['id_admin'])) {
            header('Location: ' . RUTA_ADMIN);
            exit;
        }
        
        $data['title'] = 'Calendario de Reservas - Administrador';
        $data['habitaciones'] = $this->model->getHabitaciones();
        
        $this->views->getView('admin/reservas', $data);
    }

    public function nueva_reservacion()
    {
        // VERIFICAR SESIÓN DE ADMINISTRADOR
        if (!isset($_SESSION['id_admin'])) {
            header('Location: ' . RUTA_ADMIN);
            exit;
        }
        
        $data['title'] = 'Nueva Reservación - Administrador';
        $data['habitaciones'] = $this->model->getHabitaciones();
        
        $this->views->getView('admin/nueva_reservacion', $data);
    }

    public function getReservas()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['error' => 'No autorizado']);
            die();
        }

        $habitacion = isset($_GET['habitacion']) ? intval($_GET['habitacion']) : 0;
        
        // Verificar si es para el calendario o para la tabla
        $formato = isset($_GET['formato']) ? $_GET['formato'] : 'calendario';
        
        if ($habitacion > 0) {
            $reservas = $this->model->getReservasPorHabitacion($habitacion);
        } else {
            $reservas = $this->model->getTodasReservas();
        }

        if ($formato === 'tabla') {
            // Devolver datos para la tabla con todos los campos necesarios
            $results = [];
            foreach ($reservas as $reserva) {
                $datos = [
                    'id' => $reserva['id'],
                    'fecha_ingreso' => $reserva['fecha_ingreso'],
                    'fecha_salida' => $reserva['fecha_salida'],
                    'nombre_habitacion' => $reserva['nombre_habitacion'],
                    'nombre_usuario' => $reserva['nombre_usuario'],
                    'precio_total' => floatval($reserva['precio'] ?: $reserva['monto'] ?: 0),
                    'precio' => floatval($reserva['precio'] ?: $reserva['monto'] ?: 0),
                    'monto' => floatval($reserva['monto'] ?: $reserva['precio'] ?: 0),
                    'estado' => intval($reserva['estado']),
                    'num_transaccion' => $reserva['num_transaccion'] ?? '',
                    'cod_reserva' => $reserva['cod_reserva'] ?? '',
                    'descripcion' => $reserva['descripcion'] ?? '',
                    'metodo' => intval($reserva['metodo'] ?? 0),
                    'fecha_reserva' => $reserva['fecha_reserva'] ?? ''
                ];
                $results[] = $datos;
            }
        } else {
            // Devolver datos para el calendario (formato original)
            $results = [];
            foreach ($reservas as $reserva) {
                $datos = [
                    'id' => $reserva['id'],
                    'title' => 'Reserva - ' . $reserva['nombre_usuario'],
                    'start' => $reserva['fecha_ingreso'],
                    'end' => $reserva['fecha_salida'],
                    'color' => '#28a745',
                    'extendedProps' => [
                        'usuario' => $reserva['nombre_usuario'],
                        'habitacion' => $reserva['nombre_habitacion'],
                        'precio' => floatval($reserva['precio'] ?: $reserva['monto'] ?: 0),
                        'estado' => $reserva['estado']
                    ]
                ];
                $results[] = $datos;
            }
        }

        echo json_encode($results, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminarReserva()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = intval($_POST['id']);
            
            if ($id > 0) {
                $resultado = $this->model->eliminarReserva($id);
                if ($resultado) {
                    $res = ['tipo' => 'success', 'msg' => 'RESERVA ELIMINADA CORRECTAMENTE'];
                } else {
                    $res = ['tipo' => 'error', 'msg' => 'ERROR AL ELIMINAR LA RESERVA'];
                }
            } else {
                $res = ['tipo' => 'error', 'msg' => 'ID DE RESERVA INVÁLIDO'];
            }
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function editarReserva()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = intval($_POST['id']);
            $fecha_ingreso = strClean($_POST['fecha_ingreso']);
            $fecha_salida = strClean($_POST['fecha_salida']);
            
            if ($id > 0 && !empty($fecha_ingreso) && !empty($fecha_salida)) {
                $resultado = $this->model->editarReserva($id, $fecha_ingreso, $fecha_salida);
                if ($resultado) {
                    $res = ['tipo' => 'success', 'msg' => 'RESERVA ACTUALIZADA CORRECTAMENTE'];
                } else {
                    $res = ['tipo' => 'error', 'msg' => 'ERROR AL ACTUALIZAR LA RESERVA'];
                }
            } else {
                $res = ['tipo' => 'error', 'msg' => 'DATOS INCOMPLETOS'];
            }
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function crearReserva()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (validarCampos(['usuario', 'habitacion', 'fecha_ingreso', 'fecha_salida', 'precio'])) {
                $usuario = intval($_POST['usuario']);
                $habitacion = intval($_POST['habitacion']);
                $fecha_ingreso = strClean($_POST['fecha_ingreso']);
                $fecha_salida = strClean($_POST['fecha_salida']);
                $precio = floatval($_POST['precio']);
                
                // Verificar disponibilidad
                $disponible = $this->model->verificarDisponibilidad($fecha_ingreso, $fecha_salida, $habitacion);
                
                if (empty($disponible)) {
                    $resultado = $this->model->crearReserva($usuario, $habitacion, $fecha_ingreso, $fecha_salida, $precio);
                    if ($resultado) {
                        $res = ['tipo' => 'success', 'msg' => 'RESERVA CREADA CORRECTAMENTE'];
                    } else {
                        $res = ['tipo' => 'error', 'msg' => 'ERROR AL CREAR LA RESERVA'];
                    }
                } else {
                    $res = ['tipo' => 'warning', 'msg' => 'LAS FECHAS NO ESTÁN DISPONIBLES'];
                }
            } else {
                $res = ['tipo' => 'warning', 'msg' => 'TODOS LOS CAMPOS SON REQUERIDOS'];
            }
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function getUsuarios()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['error' => 'No autorizado']);
            die();
        }

        $usuarios = $this->model->getUsuarios();
        echo json_encode($usuarios, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getHabitaciones()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['error' => 'No autorizado']);
            die();
        }

        $habitaciones = $this->model->getHabitaciones();
        echo json_encode($habitaciones, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function actualizarReserva()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_POST) {
            $id = $_POST['id'];
            $fecha_ingreso = $_POST['fecha_ingreso'];
            $fecha_salida = $_POST['fecha_salida'];
            
            if (!empty($id) && !empty($fecha_ingreso) && !empty($fecha_salida)) {
                $data = $this->model->editarReserva($id, $fecha_ingreso, $fecha_salida);
                if ($data == 1) {
                    $res = ['tipo' => 'success', 'msg' => 'RESERVA ACTUALIZADA CORRECTAMENTE'];
                } else {
                    $res = ['tipo' => 'error', 'msg' => 'ERROR AL ACTUALIZAR LA RESERVA'];
                }
            } else {
                $res = ['tipo' => 'warning', 'msg' => 'TODOS LOS CAMPOS SON REQUERIDOS'];
            }
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }


    public function logout()
    {
        session_destroy();
        header('Location: ' . RUTA_ADMIN);
        exit;
    }
}
