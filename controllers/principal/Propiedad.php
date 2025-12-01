<?php
class Propiedad extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // Vista de detalle de propiedad
    public function detalle($id = null)
    {
        if (!$id) {
            header('Location: ' . RUTA_PRINCIPAL . 'catalogo');
            exit;
        }

        $data['title'] = 'Detalle de Propiedad - Via-Mar';
        
        // Obtener propiedad completa
        $data['propiedad'] = $this->model->getCasaDetalle($id);
        
        if (!$data['propiedad']) {
            header('Location: ' . RUTA_PRINCIPAL . 'catalogo');
            exit;
        }
        
        $data['title'] = $data['propiedad']['estilo'] . ' - Via-Mar';
        
        // Verificar si es favorito del usuario actual
        if (isset($_SESSION['id_usuario'])) {
            $data['es_favorito'] = $this->model->esFavorito($id, $_SESSION['id_usuario']);
        } else {
            $data['es_favorito'] = false;
        }
        
        // Fechas de disponibilidad si vienen en la URL
        $data['fecha_llegada'] = isset($_GET['f_llegada']) ? $_GET['f_llegada'] : '';
        $data['fecha_salida'] = isset($_GET['f_salida']) ? $_GET['f_salida'] : '';
        
        // Obtener fechas ocupadas para el calendario
        $data['fechas_ocupadas'] = $this->model->getFechasOcupadas($id);
        
        $this->views->getView('principal/propiedad_detalle', $data);
    }

    // Vista por slug
    public function ver($slug = null)
    {
        if (!$slug) {
            header('Location: ' . RUTA_PRINCIPAL . 'catalogo');
            exit;
        }

        $data['title'] = 'Detalle de Propiedad - Via-Mar';
        
        // Obtener propiedad por slug
        $data['propiedad'] = $this->model->getCasaDetalleBySlug($slug);
        
        if (!$data['propiedad']) {
            header('Location: ' . RUTA_PRINCIPAL . 'catalogo');
            exit;
        }
        
        $data['title'] = $data['propiedad']['estilo'] . ' - Via-Mar';
        
        // Verificar si es favorito del usuario actual
        if (isset($_SESSION['id_usuario'])) {
            $data['es_favorito'] = $this->model->esFavorito($data['propiedad']['id'], $_SESSION['id_usuario']);
        } else {
            $data['es_favorito'] = false;
        }
        
        $data['fecha_llegada'] = isset($_GET['f_llegada']) ? $_GET['f_llegada'] : '';
        $data['fecha_salida'] = isset($_GET['f_salida']) ? $_GET['f_salida'] : '';
        $data['fechas_ocupadas'] = $this->model->getFechasOcupadas($data['propiedad']['id']);
        
        $this->views->getView('principal/propiedad_detalle', $data);
    }

    // Agregar a favoritos
    public function agregarFavorito()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Debes iniciar sesión']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_habitacion'])) {
            $id_habitacion = intval($_POST['id_habitacion']);
            $id_usuario = $_SESSION['id_usuario'];
            
            $this->model->agregarFavorito($id_habitacion, $id_usuario);
            echo json_encode(['tipo' => 'success', 'msg' => 'Agregado a favoritos']);
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Datos incompletos']);
        }
        die();
    }

    // Quitar de favoritos
    public function quitarFavorito()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Debes iniciar sesión']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_habitacion'])) {
            $id_habitacion = intval($_POST['id_habitacion']);
            $id_usuario = $_SESSION['id_usuario'];
            
            $this->model->quitarFavorito($id_habitacion, $id_usuario);
            echo json_encode(['tipo' => 'success', 'msg' => 'Eliminado de favoritos']);
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Datos incompletos']);
        }
        die();
    }

    // Toggle favorito
    public function toggleFavorito()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'Debes iniciar sesión']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_habitacion'])) {
            $id_habitacion = intval($_POST['id_habitacion']);
            $id_usuario = $_SESSION['id_usuario'];
            
            if ($this->model->esFavorito($id_habitacion, $id_usuario)) {
                $this->model->quitarFavorito($id_habitacion, $id_usuario);
                echo json_encode(['tipo' => 'success', 'msg' => 'Eliminado de favoritos', 'accion' => 'removed']);
            } else {
                $this->model->agregarFavorito($id_habitacion, $id_usuario);
                echo json_encode(['tipo' => 'success', 'msg' => 'Agregado a favoritos', 'accion' => 'added']);
            }
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Datos incompletos']);
        }
        die();
    }

    // Lista de favoritos del usuario
    public function favoritos()
    {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . RUTA_PRINCIPAL . 'login');
            exit;
        }

        $data['title'] = 'Mis Favoritos - Via-Mar';
        $data['propiedades'] = $this->model->getFavoritosUsuario($_SESSION['id_usuario']);
        
        $this->views->getView('principal/catalogo', $data);
    }

    // Verificar disponibilidad (AJAX) - Retorna JSON completo
    public function verificarDisponibilidad()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_habitacion = intval($_POST['id_habitacion'] ?? 0);
            $fecha_inicio = $_POST['fecha_inicio'] ?? '';
            $fecha_fin = $_POST['fecha_fin'] ?? '';
            
            if ($id_habitacion && $fecha_inicio && $fecha_fin) {
                // Verificar disponibilidad
                $reservasConflicto = $this->model->getReservasConflicto($id_habitacion, $fecha_inicio, $fecha_fin);
                $disponible = empty($reservasConflicto);
                
                // Obtener datos de la habitación para calcular precio
                $habitacion = $this->model->getCasaDetalle($id_habitacion);
                
                // Calcular noches y precio
                $fecha1 = new DateTime($fecha_inicio);
                $fecha2 = new DateTime($fecha_fin);
                $noches = $fecha2->diff($fecha1)->days;
                
                $precioNoche = floatval($habitacion['precio'] ?? 0);
                $tarifaLimpieza = floatval($habitacion['tarifa_limpieza'] ?? 0);
                $subtotal = $precioNoche * $noches;
                $tarifaServicio = $subtotal * 0.12;
                $total = $subtotal + $tarifaLimpieza + $tarifaServicio;
                
                // Obtener días ocupados si no está disponible
                $diasOcupados = [];
                if (!$disponible) {
                    foreach ($reservasConflicto as $reserva) {
                        $diasOcupados[] = [
                            'inicio' => $reserva['fecha_ingreso'],
                            'fin' => $reserva['fecha_salida']
                        ];
                    }
                }
                
                echo json_encode([
                    'tipo' => 'success',
                    'disponible' => $disponible,
                    'mensaje' => $disponible ? '¡Fechas disponibles!' : 'Estas fechas no están disponibles',
                    'noches' => $noches,
                    'precio_noche' => $precioNoche,
                    'subtotal' => $subtotal,
                    'tarifa_limpieza' => $tarifaLimpieza,
                    'tarifa_servicio' => $tarifaServicio,
                    'precio_total' => $total,
                    'dias_ocupados' => $diasOcupados
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['tipo' => 'error', 'mensaje' => 'Datos incompletos']);
            }
        } else {
            echo json_encode(['tipo' => 'error', 'mensaje' => 'Método no permitido']);
        }
        die();
    }
    
    // Obtener reservas para el calendario (formato FullCalendar)
    public function getReservasCalendario()
    {
        header('Content-Type: application/json');
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $f_llegada = $_GET['f_llegada'] ?? null;
        $f_salida = $_GET['f_salida'] ?? null;
        
        $eventos = [];
        
        if ($id > 0) {
            $reservas = $this->model->getReservasHabitacion($id);
            
            foreach ($reservas as $reserva) {
                $eventos[] = [
                    'id' => $reserva['id'],
                    'title' => 'OCUPADO',
                    'start' => $reserva['fecha_ingreso'],
                    'end' => $reserva['fecha_salida'],
                    'color' => '#dc3545',
                    'textColor' => '#fff'
                ];
            }
            
            // Si hay fechas de consulta, mostrarlas en amarillo
            if ($f_llegada && $f_salida) {
                $eventos[] = [
                    'id' => 'consulta',
                    'title' => 'TU SELECCIÓN',
                    'start' => $f_llegada,
                    'end' => $f_salida,
                    'color' => '#ffc107',
                    'textColor' => '#000'
                ];
            }
        }
        
        echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
        die();
    }

    // Obtener fechas ocupadas (AJAX)
    public function getFechasOcupadas()
    {
        header('Content-Type: application/json');
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $fechas = $this->model->getFechasOcupadas($id);
            echo json_encode(['tipo' => 'success', 'fechas' => $fechas]);
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'ID no válido']);
        }
        die();
    }
}
