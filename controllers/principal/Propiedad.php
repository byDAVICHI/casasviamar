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

    // Verificar disponibilidad (AJAX)
    public function verificarDisponibilidad()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_habitacion = intval($_POST['id_habitacion'] ?? 0);
            $fecha_inicio = $_POST['fecha_inicio'] ?? '';
            $fecha_fin = $_POST['fecha_fin'] ?? '';
            
            if ($id_habitacion && $fecha_inicio && $fecha_fin) {
                $disponible = $this->model->verificarDisponibilidad($id_habitacion, $fecha_inicio, $fecha_fin);
                echo json_encode([
                    'tipo' => 'success',
                    'disponible' => $disponible,
                    'msg' => $disponible ? 'Fechas disponibles' : 'Fechas no disponibles'
                ]);
            } else {
                echo json_encode(['tipo' => 'error', 'msg' => 'Datos incompletos']);
            }
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Método no permitido']);
        }
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
