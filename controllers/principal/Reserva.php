<?php
// require_once 'vendor/autoload.php';
// SDK de Mercado Pago

// use MercadoPago\Client\Preference\PreferenceClient;
// use MercadoPago\MercadoPagoConfig;

class Reserva extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['total'])) {
            session_start();
        }
    }

    public function verify()
    {
        if (isset($_GET['f_llegada']) && isset($_GET['f_salida']) && isset($_GET['habitacion'])) {
            $f_llegada = strClean($_GET['f_llegada']);
            $f_salida = strClean($_GET['f_salida']);
            $habitacion = strClean($_GET['habitacion']);
            $_SESSION['habitacionR'] = $habitacion;
            if (empty($f_llegada) || empty($f_salida) || empty($habitacion)) {
                header('Location: ' . RUTA_PRINCIPAL . '?respuesta=warning');
            } else {
                $reserva = $this->model->getDisponible($f_llegada, $f_salida, $habitacion);
                $data['title'] = 'Reservas | Verificar Disponibilidad';
                $data['disponible'] = [
                    'f_llegada' => $f_llegada,
                    'f_salida' => $f_salida,
                    'habitacion' => $habitacion
                ];
                if (empty($reserva)) {
                    // CREAR SESION DE LA HABITACION
                    $_SESSION['reserva'] = $data['disponible'];
                    $data['mensaje'] = 'DISPONIBLE';
                    $data['tipo'] = 'success';
                } else {
                    $data['mensaje'] = 'NO DISPONIBLE';
                    $data['tipo'] = 'danger';
                }
                $data['habitaciones'] = $this->model->getHabitaciones();
                $data['habitacion'] = $this->model->getHabitacion($habitacion);
                $this->views->getView('principal/reservas', $data);
            }
        }
    }

    public function listar($paremetros)
    {
        $array = explode(',', $paremetros);
        $f_llegada = (!empty($array[0])) ? $array[0] : null;
        $f_salida = (!empty($array[1])) ? $array[1] : null;
        $habitacion = (!empty($array[2])) ? $array[2] : null;
        $results = [];
        if ($f_llegada != null && $f_salida != null && $habitacion != null) {
            $reservas = $this->model->getReservasHabitacion($habitacion);

            for ($i = 0; $i < count($reservas); $i++) {
                $datos['id'] = $reservas[$i]['id'];
                $datos['title'] = 'OCUPADO';
                $datos['start'] = $reservas[$i]['fecha_ingreso'];
                $datos['end'] = $reservas[$i]['fecha_salida'];
                $datos['color'] = '#dc3545';
                array_push($results, $datos);
            }
            $data['id'] = $habitacion;
            $data['title'] = 'COMPROBANDO';
            $data['start'] = $f_llegada;
            $data['end'] = $f_salida;
            $data['color'] = '#ffc107';
            array_push($results, $data);
            echo json_encode($results, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function pendiente()
    {
        $data['title'] = 'Reserva Pendiente';
        $data['habitacion'] = [];
        if (!empty($_SESSION['reserva'])) {
            $data['habitacion'] = $this->model->getHabitacion($_SESSION['reserva']['habitacion']);
        } else {
            # code...
        }
        // MERCADO PAGO
        // Agrega credenciales

        $this->views->getView('principal/clientes/reservas/pendiente', $data);

        // CAPTURAR LA CANTIDAD

        $fecha1 = new DateTime($_SESSION['reserva']['f_llegada']);
        $fecha2 = new DateTime($_SESSION['reserva']['f_salida']);


        $cantidad = $fecha2->diff($fecha1);
        $precio = floatval($data['habitacion']['precio']);

        $data['total'] = $cantidad->d * $precio;
        $_SESSION['total'] = $cantidad->d * $precio;
    }

    public function registrarReserva()
    {
        $datos = file_get_contents('php://input');
        $array = json_decode($datos, true);
        print_r($array);
    }
}
