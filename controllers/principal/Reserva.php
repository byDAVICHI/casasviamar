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
        header('Content-Type: application/json');
        
        // Verificar que sea una petición AJAX
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            echo json_encode(['tipo' => 'error', 'msg' => 'Acceso no autorizado']);
            die();
        }
        
        // Obtener datos del POST
        $datos = file_get_contents('php://input');
        $data = json_decode($datos, true);
        
        if (empty($data) || !isset($data['orderData']) || !isset($data['token'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Datos incompletos']);
            die();
        }
        
        // Verificar token de seguridad
        if (!isset($_SESSION['token_pago']) || $data['token'] !== $_SESSION['token_pago']) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Token de seguridad inválido. Intenta de nuevo.']);
            die();
        }
        
        // Verificar que haya una reserva pendiente en sesión
        if (empty($_SESSION['reserva'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No hay reserva pendiente']);
            die();
        }
        
        // Verificar que aceptó términos
        if (!isset($data['terminosAceptados']) || !$data['terminosAceptados']) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Debe aceptar los términos y condiciones']);
            die();
        }
        
        // Extraer datos de PayPal
        $orderData = $data['orderData'];
        $idTransaccion = $orderData['id'] ?? '';
        $estadoPago = $orderData['status'] ?? '';
        
        // Verificar que el pago fue exitoso
        if ($estadoPago !== 'COMPLETED') {
            echo json_encode(['tipo' => 'error', 'msg' => 'El pago no fue completado. Estado: ' . $estadoPago]);
            die();
        }
        
        // Obtener datos del pagador
        $emailPagador = $orderData['payer']['email_address'] ?? '';
        $nombrePagador = ($orderData['payer']['name']['given_name'] ?? '') . ' ' . ($orderData['payer']['name']['surname'] ?? '');
        
        // Recalcular precios en el backend (SEGURIDAD - no confiar en frontend)
        $habitacion = $this->model->getHabitacion($_SESSION['reserva']['habitacion']);
        $fecha1 = new DateTime($_SESSION['reserva']['f_llegada']);
        $fecha2 = new DateTime($_SESSION['reserva']['f_salida']);
        $noches = $fecha2->diff($fecha1)->days;
        
        $precioNoche = floatval($habitacion['precio']);
        $tarifaLimpieza = floatval($habitacion['tarifa_limpieza'] ?? 0);
        $comisionPorcentaje = floatval($habitacion['comision_plataforma'] ?? 12);
        
        $subtotal = $precioNoche * $noches;
        $tarifaServicio = $subtotal * ($comisionPorcentaje / 100);
        $total = $subtotal + $tarifaLimpieza + $tarifaServicio;
        
        // Calcular monto para el anfitrión (subtotal + limpieza, sin comisión)
        $montoAnfitrion = $subtotal + $tarifaLimpieza;
        
        // Generar código de reserva único
        $codigoReserva = 'RES-' . date('Ymd') . '-' . strtoupper(substr(md5($idTransaccion), 0, 6));
        
        // Preparar datos para insertar
        $datosReserva = [
            'id_usuario' => $_SESSION['id_usuario'],
            'id_habitacion' => $_SESSION['reserva']['habitacion'],
            'fecha_ingreso' => $_SESSION['reserva']['f_llegada'],
            'fecha_salida' => $_SESSION['reserva']['f_salida'],
            'precio' => $total,
            'monto' => $total,
            'monto_subtotal' => $subtotal,
            'tarifa_limpieza' => $tarifaLimpieza,
            'tarifa_servicio' => $tarifaServicio,
            'monto_anfitrion' => $montoAnfitrion,
            'estado' => 1, // Activa
            'estado_pago' => 'pagado',
            'id_transaccion' => $idTransaccion,
            'metodo_pago' => 'paypal',
            'fecha_pago' => date('Y-m-d H:i:s'),
            'email_pagador' => $emailPagador,
            'terminos_aceptados' => 1,
            'fecha_aceptacion_terminos' => date('Y-m-d H:i:s'),
            'codigo_reserva' => $codigoReserva
        ];
        
        // Registrar la reserva en la base de datos
        $resultado = $this->model->registrarReservaCompleta($datosReserva);
        
        if ($resultado) {
            // Registrar transacción en historial
            $this->model->registrarTransaccion([
                'id_reserva' => $resultado,
                'id_transaccion_paypal' => $idTransaccion,
                'estado' => $estadoPago,
                'monto_total' => $total,
                'moneda' => MONEDA_PAYPAL,
                'email_pagador' => $emailPagador,
                'nombre_pagador' => $nombrePagador,
                'metodo' => 'PayPal',
                'datos_raw' => json_encode($orderData)
            ]);
            
            // Registrar dispersión pendiente al anfitrión
            $idPropietario = $habitacion['id_propietario'] ?? null;
            if ($idPropietario) {
                $this->model->registrarDispersion([
                    'id_reserva' => $resultado,
                    'id_anfitrion' => $idPropietario,
                    'monto' => $montoAnfitrion,
                    'estado' => 'pendiente'
                ]);
            }
            
            // Guardar datos de facturación si existen
            if (isset($_SESSION['facturacion']) && !empty($_SESSION['facturacion'])) {
                $facturacion = $_SESSION['facturacion'];
                $this->model->guardarFacturacion([
                    'id_reserva' => $resultado,
                    'tipo_persona' => $facturacion['tipo_persona'],
                    'rfc' => $facturacion['rfc'],
                    'razon_social' => $facturacion['razon_social'],
                    'regimen_fiscal' => $facturacion['regimen_fiscal'],
                    'codigo_postal' => $facturacion['codigo_postal'],
                    'uso_cfdi' => $facturacion['uso_cfdi'],
                    'correo_factura' => $facturacion['correo_factura'],
                    'telefono' => $facturacion['telefono'] ?? '',
                    'direccion' => $facturacion['direccion'] ?? ''
                ]);
                
                // Marcar reserva como que requiere factura
                $this->model->marcarRequiereFactura($resultado);
                
                unset($_SESSION['facturacion']);
            }
            
            // Limpiar sesión de reserva pendiente
            unset($_SESSION['reserva']);
            unset($_SESSION['total']);
            unset($_SESSION['token_pago']);
            unset($_SESSION['habitacionR']);
            
            echo json_encode([
                'tipo' => 'success',
                'msg' => 'Reservación registrada exitosamente',
                'codigo_reserva' => $codigoReserva,
                'id_transaccion' => $idTransaccion
            ]);
        } else {
            echo json_encode([
                'tipo' => 'error',
                'msg' => 'Error al registrar la reservación. El pago fue procesado. ID: ' . $idTransaccion
            ]);
        }
        die();
    }
    
    /**
     * Cancelar reserva pendiente
     */
    public function cancelar()
    {
        // Limpiar sesión de reserva pendiente
        unset($_SESSION['reserva']);
        unset($_SESSION['total']);
        unset($_SESSION['token_pago']);
        unset($_SESSION['habitacionR']);
        
        header('Location: ' . RUTA_PRINCIPAL . 'catalogo');
        exit;
    }
    
    /**
     * Guardar datos de facturación
     */
    public function guardarFacturacion()
    {
        header('Content-Type: application/json');
        
        // Verificar sesión
        if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['reserva'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Sesión no válida']);
            die();
        }
        
        // Obtener datos del formulario
        $tipo_persona = strClean($_POST['tipo_persona'] ?? '');
        $rfc = strtoupper(strClean($_POST['rfc'] ?? ''));
        $razon_social = strClean($_POST['razon_social'] ?? '');
        $regimen_fiscal = strClean($_POST['regimen_fiscal'] ?? '');
        $codigo_postal = strClean($_POST['codigo_postal'] ?? '');
        $uso_cfdi = strClean($_POST['uso_cfdi'] ?? '');
        $correo_factura = strClean($_POST['correo_factura'] ?? '');
        $telefono = strClean($_POST['telefono'] ?? '');
        $direccion = strClean($_POST['direccion'] ?? '');
        
        // Validar campos obligatorios
        if (empty($tipo_persona) || empty($rfc) || empty($razon_social) || 
            empty($regimen_fiscal) || empty($codigo_postal) || empty($uso_cfdi) || 
            empty($correo_factura)) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Complete todos los campos obligatorios']);
            die();
        }
        
        // Validar RFC
        $rfcLength = ($tipo_persona === 'moral') ? 12 : 13;
        if (strlen($rfc) !== $rfcLength) {
            echo json_encode(['tipo' => 'error', 'msg' => 'El RFC no tiene la longitud correcta']);
            die();
        }
        
        // Guardar en sesión para asociar después del pago
        $_SESSION['facturacion'] = [
            'tipo_persona' => $tipo_persona,
            'rfc' => $rfc,
            'razon_social' => $razon_social,
            'regimen_fiscal' => $regimen_fiscal,
            'codigo_postal' => $codigo_postal,
            'uso_cfdi' => $uso_cfdi,
            'correo_factura' => $correo_factura,
            'telefono' => $telefono,
            'direccion' => $direccion
        ];
        
        echo json_encode([
            'tipo' => 'success',
            'msg' => 'Datos de facturación guardados correctamente'
        ]);
        die();
    }
}
