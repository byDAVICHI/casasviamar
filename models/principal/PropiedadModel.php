<?php
class PropiedadModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    // Obtener detalle completo de una propiedad
    public function getCasaDetalle($id)
    {
        $sql = "SELECT h.* FROM habitaciones h WHERE h.id = ? AND h.estado = 1";
        $casa = $this->select($sql, [$id]);
        
        if ($casa) {
            $casa['fotos'] = $this->getFotosPropiedad($id);
            $casa['amenidades'] = $this->getAmenidadesPropiedad($id);
            $casa['evaluaciones'] = $this->getEvaluacionesPropiedad($id, 5);
            $casa['estadisticas'] = $this->getEstadisticasEvaluaciones($id);
        }
        
        return $casa;
    }

    // Obtener propiedad por slug
    public function getCasaDetalleBySlug($slug)
    {
        $sql = "SELECT h.* FROM habitaciones h WHERE h.slug = ? AND h.estado = 1";
        $casa = $this->select($sql, [$slug]);
        
        if ($casa) {
            $casa['fotos'] = $this->getFotosPropiedad($casa['id']);
            $casa['amenidades'] = $this->getAmenidadesPropiedad($casa['id']);
            $casa['evaluaciones'] = $this->getEvaluacionesPropiedad($casa['id'], 5);
            $casa['estadisticas'] = $this->getEstadisticasEvaluaciones($casa['id']);
        }
        
        return $casa;
    }

    // Obtener fotos de una propiedad
    public function getFotosPropiedad($id_habitacion)
    {
        $sql = "SELECT * FROM fotos_propiedad WHERE id_habitacion = ? ORDER BY es_principal DESC, orden ASC";
        $result = $this->selectAll($sql, [$id_habitacion]);
        return $result ? $result : [];
    }

    // Obtener amenidades de una propiedad
    public function getAmenidadesPropiedad($id_habitacion)
    {
        $sql = "SELECT a.* FROM amenidades a 
                INNER JOIN propiedad_amenidades pa ON a.id = pa.id_amenidad 
                WHERE pa.id_habitacion = ? AND a.estado = 1
                ORDER BY a.categoria, a.nombre";
        $result = $this->selectAll($sql, [$id_habitacion]);
        return $result ? $result : [];
    }

    // Obtener evaluaciones de una propiedad
    public function getEvaluacionesPropiedad($id_habitacion, $limite = 10)
    {
        $sql = "SELECT e.*, u.nombre as nombre_usuario
                FROM evaluaciones e
                INNER JOIN usuarios u ON e.id_usuario = u.id
                WHERE e.id_habitacion = ? AND e.estado = 1
                ORDER BY e.fecha_evaluacion DESC
                LIMIT $limite";
        $result = $this->selectAll($sql, [$id_habitacion]);
        return $result ? $result : [];
    }

    // Obtener estadísticas de evaluaciones
    public function getEstadisticasEvaluaciones($id_habitacion)
    {
        $sql = "SELECT 
                    COALESCE(AVG(calificacion_general), 0) as promedio_general,
                    COALESCE(AVG(limpieza), 0) as promedio_limpieza,
                    COALESCE(AVG(veracidad), 0) as promedio_veracidad,
                    COALESCE(AVG(llegada), 0) as promedio_llegada,
                    COALESCE(AVG(comunicacion), 0) as promedio_comunicacion,
                    COALESCE(AVG(ubicacion), 0) as promedio_ubicacion,
                    COALESCE(AVG(calidad_precio), 0) as promedio_calidad_precio,
                    COUNT(*) as total_evaluaciones
                FROM evaluaciones 
                WHERE id_habitacion = ? AND estado = 1";
        $result = $this->select($sql, [$id_habitacion]);
        return $result ? $result : [
            'promedio_general' => 0,
            'total_evaluaciones' => 0
        ];
    }

    // Verificar disponibilidad de fechas
    public function verificarDisponibilidad($id_habitacion, $fecha_inicio, $fecha_fin, $excluir_reserva = null)
    {
        $sql = "SELECT COUNT(*) as total FROM reservas 
                WHERE id_habitacion = ? 
                AND estado = 1
                AND fecha_ingreso < ? 
                AND fecha_salida > ?";
        $params = [$id_habitacion, $fecha_fin, $fecha_inicio];
        
        if ($excluir_reserva) {
            $sql .= " AND id != ?";
            $params[] = $excluir_reserva;
        }
        
        $result = $this->select($sql, $params);
        return $result['total'] == 0;
    }

    // Obtener fechas ocupadas para calendario
    public function getFechasOcupadas($id_habitacion)
    {
        $sql = "SELECT fecha_ingreso, fecha_salida FROM reservas 
                WHERE id_habitacion = ? AND estado = 1 AND fecha_salida >= CURDATE()
                ORDER BY fecha_ingreso";
        $result = $this->selectAll($sql, [$id_habitacion]);
        return $result ? $result : [];
    }

    // Agregar a favoritos
    public function agregarFavorito($id_habitacion, $id_usuario)
    {
        $sql = "INSERT IGNORE INTO favoritos (id_habitacion, id_usuario) VALUES (?, ?)";
        return $this->save($sql, [$id_habitacion, $id_usuario]);
    }

    // Quitar de favoritos
    public function quitarFavorito($id_habitacion, $id_usuario)
    {
        $sql = "DELETE FROM favoritos WHERE id_habitacion = ? AND id_usuario = ?";
        return $this->save($sql, [$id_habitacion, $id_usuario]);
    }

    // Verificar si es favorito
    public function esFavorito($id_habitacion, $id_usuario)
    {
        $sql = "SELECT COUNT(*) as total FROM favoritos WHERE id_habitacion = ? AND id_usuario = ?";
        $result = $this->select($sql, [$id_habitacion, $id_usuario]);
        return $result['total'] > 0;
    }

    // Obtener favoritos del usuario
    public function getFavoritosUsuario($id_usuario)
    {
        $sql = "SELECT h.*, h.foto as foto_principal
                FROM habitaciones h
                INNER JOIN favoritos f ON h.id = f.id_habitacion
                WHERE f.id_usuario = ? AND h.estado = 1
                ORDER BY f.fecha_agregado DESC";
        $result = $this->selectAll($sql, [$id_usuario]);
        return $result ? $result : [];
    }

    // Obtener reservas en conflicto con las fechas dadas
    public function getReservasConflicto($id_habitacion, $fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT * FROM reservas 
                WHERE id_habitacion = ? 
                AND estado = 1
                AND fecha_ingreso < ? 
                AND fecha_salida > ?
                ORDER BY fecha_ingreso";
        $result = $this->selectAll($sql, [$id_habitacion, $fecha_fin, $fecha_inicio]);
        return $result ? $result : [];
    }

    // Obtener todas las reservas de una habitación
    public function getReservasHabitacion($id_habitacion)
    {
        $sql = "SELECT * FROM reservas 
                WHERE id_habitacion = ? AND estado = 1
                ORDER BY fecha_ingreso";
        $result = $this->selectAll($sql, [$id_habitacion]);
        return $result ? $result : [];
    }
}
