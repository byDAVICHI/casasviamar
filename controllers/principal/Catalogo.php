<?php
class Catalogo extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // Vista principal del catálogo
    public function index()
    {
        $data['title'] = 'Casas Vacacionales - Via-Mar';
        
        // Obtener filtros de la URL
        $filtros = [
            'precio_min' => isset($_GET['precio_min']) ? floatval($_GET['precio_min']) : null,
            'precio_max' => isset($_GET['precio_max']) ? floatval($_GET['precio_max']) : null,
            'habitaciones' => isset($_GET['habitaciones']) ? intval($_GET['habitaciones']) : null,
            'capacidad_min' => isset($_GET['capacidad_min']) ? intval($_GET['capacidad_min']) : null,
        ];
        
        // Obtener propiedades del catálogo
        $data['propiedades'] = $this->model->getCasasParaCatalogo($filtros);
        $data['filtros'] = $filtros;
        
        $this->views->getView('principal/catalogo', $data);
    }
}
