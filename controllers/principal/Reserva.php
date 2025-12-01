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
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Método index - Redirige a pendiente por defecto
     */
    public function index()
    {
        // Redirigir a reserva/pendiente
        header('Location: ' . RUTA_PRINCIPAL . 'reserva/pendiente');
        exit;
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
                exit;
            }
            
            // Verificar disponibilidad
            $reserva = $this->model->getDisponible($f_llegada, $f_salida, $habitacion);
            
            if (empty($reserva)) {
                // DISPONIBLE - Guardar en sesión y redirigir a pendiente
                $_SESSION['reserva'] = [
                    'f_llegada' => $f_llegada,
                    'f_salida' => $f_salida,
                    'habitacion' => $habitacion
                ];
                
                // Redirigir directamente a la página de pago
                header('Location: ' . RUTA_PRINCIPAL . 'reserva/pendiente');
                exit;
            } else {
                // NO DISPONIBLE - Redirigir de vuelta a la propiedad con mensaje
                header('Location: ' . RUTA_PRINCIPAL . 'propiedad/detalle/' . $habitacion . '?error=nodisponible');
                exit;
            }
        } else {
            // Sin parámetros - redirigir al catálogo
            header('Location: ' . RUTA_PRINCIPAL . 'catalogo');
            exit;
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
        $data['total'] = 0;
        $data['noches'] = 0;
        $data['tarifa_servicio'] = 0;
        
        if (!empty($_SESSION['reserva'])) {
            $data['habitacion'] = $this->model->getHabitacion($_SESSION['reserva']['habitacion']);
            
            // CALCULAR PRECIO ANTES de renderizar la vista
            $fecha1 = new DateTime($_SESSION['reserva']['f_llegada']);
            $fecha2 = new DateTime($_SESSION['reserva']['f_salida']);
            $diferencia = $fecha2->diff($fecha1);
            $noches = $diferencia->days;
            
            $precioNoche = floatval($data['habitacion']['precio'] ?? 0);
            $tarifaLimpieza = floatval($data['habitacion']['tarifa_limpieza'] ?? 0);
            
            // Cálculos
            $subtotal = $precioNoche * $noches;
            $tarifaServicio = $subtotal * 0.12; // 12% tarifa de servicio
            $total = $subtotal + $tarifaLimpieza + $tarifaServicio;
            
            // Asignar a data y sesión
            $data['noches'] = $noches;
            $data['subtotal'] = $subtotal;
            $data['tarifa_limpieza'] = $tarifaLimpieza;
            $data['tarifa_servicio'] = $tarifaServicio;
            $data['total'] = $total;
            $_SESSION['total'] = $total;
        }
        
        // Renderizar vista DESPUÉS de calcular el precio
        $this->views->getView('principal/clientes/reservas/pendiente', $data);
    }

    public function registrarReserva()
    {
        $datos = file_get_contents('php://input');
        $array = json_decode($datos, true);
        print_r($array);
    }
}
