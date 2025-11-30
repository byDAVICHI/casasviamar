<?php
class CatalogoModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    // Obtener propiedades para el catálogo con filtros opcionales
    public function getCasasParaCatalogo($filtros = [])
    {
        $sql = "SELECT h.*, 
                COALESCE(h.calificacion_promedio, 0) as rating,
                COALESCE(h.total_evaluaciones, 0) as num_evaluaciones
                FROM habitaciones h 
                WHERE h.estado = 1";
        
        $params = [];
        
        // Aplicar filtros
        if (!empty($filtros['precio_min'])) {
            $sql .= " AND h.precio >= ?";
            $params[] = $filtros['precio_min'];
        }
        
        if (!empty($filtros['precio_max'])) {
            $sql .= " AND h.precio <= ?";
            $params[] = $filtros['precio_max'];
        }
        
        if (!empty($filtros['habitaciones'])) {
            $sql .= " AND COALESCE(h.habitaciones_num, 1) >= ?";
            $params[] = $filtros['habitaciones'];
        }
        
        if (!empty($filtros['capacidad_min'])) {
            $sql .= " AND h.capacidad >= ?";
            $params[] = $filtros['capacidad_min'];
        }
        
        $sql .= " ORDER BY h.es_favorito_huespedes DESC, h.calificacion_promedio DESC, h.id DESC";
        
        $result = $this->selectAll($sql, $params);
        return $result ? $result : [];
    }
}
