<?php
class AdminModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    public function validarAccesoAdmin($usuario)
    {
        $sql = "SELECT * FROM usuarios WHERE estado = 1 AND (rol = 'admin' OR rol = 1) AND (usuario = '$usuario' OR correo = '$usuario')";
        return $this->select($sql);
    }

    public function getHabitaciones()
    {
        $sql = "SELECT * FROM habitaciones WHERE estado = 1";
        return $this->selectAll($sql);
    }

    public function getTotalReservas()
    {
        $sql = "SELECT COUNT(*) as total FROM reservas WHERE estado = 1";
        $result = $this->select($sql);
        return $result['total'];
    }

    public function getReservasHoy()
    {
        $hoy = date('Y-m-d');
        $sql = "SELECT COUNT(*) as total FROM reservas WHERE fecha_ingreso = '$hoy' AND estado = 1";
        $result = $this->select($sql);
        return $result['total'];
    }

    public function getTotalUsuarios()
    {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 1 AND rol = 'cliente'";
        $result = $this->select($sql);
        return $result['total'];
    }

    public function getTodasReservas()
    {
        $sql = "SELECT r.id,
                       r.monto,
                       r.num_transaccion,
                       r.cod_reserva,
                       r.fecha_ingreso,
                       r.fecha_salida,
                       r.fecha_reserva,
                       r.descripcion,
                       r.estado,
                       r.metodo,
                       r.facturacion,
                       r.id_habitacion,
                       r.id_usuario,
                       r.precio,
                       CONCAT(u.nombre, ' ', u.apellido) as nombre_usuario,
                       h.estilo as nombre_habitacion,
                       h.precio as precio_por_noche
                FROM reservas r 
                INNER JOIN usuarios u ON r.id_usuario = u.id 
                INNER JOIN habitaciones h ON r.id_habitacion = h.id 
                WHERE r.estado = 1
                ORDER BY r.fecha_ingreso DESC";
        return $this->selectAll($sql);
    }

    public function getReservasPorHabitacion($habitacion)
    {
        $sql = "SELECT r.id,
                       r.monto,
                       r.num_transaccion,
                       r.cod_reserva,
                       r.fecha_ingreso,
                       r.fecha_salida,
                       r.fecha_reserva,
                       r.descripcion,
                       r.estado,
                       r.metodo,
                       r.facturacion,
                       r.id_habitacion,
                       r.id_usuario,
                       r.precio,
                       CONCAT(u.nombre, ' ', u.apellido) as nombre_usuario,
                       h.estilo as nombre_habitacion,
                       h.precio as precio_por_noche
                FROM reservas r 
                INNER JOIN usuarios u ON r.id_usuario = u.id 
                INNER JOIN habitaciones h ON r.id_habitacion = h.id 
                WHERE r.estado = 1 AND r.id_habitacion = $habitacion
                ORDER BY r.fecha_ingreso DESC";
        return $this->selectAll($sql);
    }

    public function getReserva($id)
    {
        $sql = "SELECT r.*, 
                       CONCAT(u.nombre, ' ', u.apellido) as nombre_usuario,
                       h.estilo as nombre_habitacion,
                       h.precio
                FROM reservas r 
                INNER JOIN usuarios u ON r.id_usuario = u.id 
                INNER JOIN habitaciones h ON r.id_habitacion = h.id 
                WHERE r.id = $id";
        return $this->select($sql);
    }

    public function eliminarReserva($id)
    {
        $sql = "DELETE FROM reservas WHERE id = ?";
        $array = [$id];
        return $this->save($sql, $array);
    }

    public function editarReserva($id, $fecha_ingreso, $fecha_salida)
    {
        // Primero obtener los datos de la reserva para calcular el nuevo precio
        $reserva = $this->getReserva($id);
        if (!$reserva) {
            return false;
        }
        
        // Calcular el número de noches
        $fecha_inicio = new DateTime($fecha_ingreso);
        $fecha_fin = new DateTime($fecha_salida);
        $diferencia = $fecha_inicio->diff($fecha_fin);
        $total_noches = $diferencia->days;
        
        // Obtener el precio por noche de la habitación (no de la reserva)
        $precio_por_noche = floatval($reserva['precio']); // Este es el precio de la habitación desde el JOIN
        $precio_total = $precio_por_noche * $total_noches;
        
        // Actualizar la reserva con las nuevas fechas y el precio recalculado
        $sql = "UPDATE reservas SET fecha_ingreso = ?, fecha_salida = ?, precio = ?, monto = ? WHERE id = ?";
        $array = [$fecha_ingreso, $fecha_salida, $precio_total, $precio_total, $id];
        return $this->save($sql, $array);
    }

    public function crearReserva($id_usuario, $id_habitacion, $fecha_ingreso, $fecha_salida, $precio)
    {
        // Generar código de reserva único
        $cod_reserva = 'RES-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $num_transaccion = 'TXN-' . time() . '-' . rand(100, 999);
        
        $sql = "INSERT INTO reservas (id_usuario, id_habitacion, fecha_ingreso, fecha_salida, precio, monto, estado, cod_reserva, num_transaccion, fecha_reserva) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $array = [$id_usuario, $id_habitacion, $fecha_ingreso, $fecha_salida, $precio, $precio, 1, $cod_reserva, $num_transaccion];
        return $this->save($sql, $array);
    }

    public function getUsuarios()
    {
        $sql = "SELECT * FROM usuarios WHERE estado = 1 ORDER BY nombre ASC";
        return $this->selectAll($sql);
    }

    public function verificarDisponibilidad($fecha_ingreso, $fecha_salida, $habitacion, $excluir_id = null)
    {
        $sql = "SELECT * FROM reservas 
                WHERE fecha_ingreso <= '$fecha_salida'
                AND fecha_salida >= '$fecha_ingreso'  
                AND id_habitacion = $habitacion 
                AND estado = 1";
        
        if ($excluir_id) {
            $sql .= " AND id != $excluir_id";
        }
        
        return $this->selectAll($sql);
    }

    // ==================== MÉTODOS CRUD PARA CASAS VACACIONALES ====================
    
    public function getCasasVacacionales()
    {
        // Incluir todos los campos para el panel admin
        $sql = "SELECT * FROM habitaciones ORDER BY fecha DESC";
        return $this->selectAll($sql);
    }

    public function getCasa($id)
    {
        $sql = "SELECT * FROM habitaciones WHERE id = $id";
        return $this->select($sql);
    }

    public function crearCasa($estilo, $numero, $capacidad, $slug, $foto, $video, $descripcion, $precio, $estado)
    {
        $sql = "INSERT INTO habitaciones (estilo, numero, capacidad, slug, foto, video, descripcion, precio, estado, fecha) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $array = [$estilo, $numero, $capacidad, $slug, $foto, $video, $descripcion, $precio, $estado];
        return $this->insert($sql, $array);
    }
    
    // NUEVO: Crear casa con ubicación (latitud/longitud)
    public function crearCasaConUbicacion($estilo, $numero, $capacidad, $habitaciones_num, $camas, $banos,
                                          $slug, $descripcion, $precio, $direccion, $latitud, $longitud, $estado, $foto, $video)
    {
        $sql = "INSERT INTO habitaciones (
                estilo, numero, capacidad, habitaciones_num, camas, banos,
                slug, descripcion, precio, direccion, latitud, longitud, estado, foto, video, fecha
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $params = [
            $estilo, $numero, $capacidad, $habitaciones_num, $camas, $banos,
            $slug, $descripcion, $precio, $direccion, $latitud, $longitud, $estado,
            $foto, $video
        ];
        
        error_log("=== crearCasaConUbicacion ===");
        error_log("Lat: " . ($latitud ?? 'NULL') . ", Lng: " . ($longitud ?? 'NULL'));
        
        return $this->insert($sql, $params);
    }
    
    // Método completo para crear casa con todos los campos extendidos
    public function crearCasaCompleta($estilo, $numero, $capacidad, $habitaciones_num, $camas, $banos, 
                                       $slug, $descripcion, $precio, $direccion, $latitud, $longitud, $estado, $foto, $video)
    {
        try {
            $sql = "INSERT INTO habitaciones (
                    estilo, numero, capacidad, habitaciones_num, camas, banos,
                    slug, descripcion, precio, direccion, latitud, longitud, estado, foto, video, fecha
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $params = [
                $estilo, $numero, $capacidad, $habitaciones_num, $camas, $banos,
                $slug, $descripcion, $precio, $direccion, $latitud, $longitud, $estado,
                $foto, $video
            ];
            
            error_log("crearCasaCompleta - SQL: $sql");
            error_log("crearCasaCompleta - Params: " . print_r($params, true));
            
            return $this->insert($sql, $params);
        } catch (Exception $e) {
            error_log("crearCasaCompleta - Error: " . $e->getMessage());
            return 0;
        }
    }

    public function editarCasa($id, $estilo, $numero, $capacidad, $slug, $foto, $video, $descripcion, $precio, $estado)
    {
        $sql = "UPDATE habitaciones 
                SET estilo = ?, numero = ?, capacidad = ?, slug = ?, foto = ?, video = ?, descripcion = ?, precio = ?, estado = ? 
                WHERE id = ?";
        $array = [$estilo, $numero, $capacidad, $slug, $foto, $video, $descripcion, $precio, $estado, $id];
        return $this->save($sql, $array);
    }
    
    // NUEVO: Método que incluye ubicación (latitud/longitud)
    public function actualizarCasaConUbicacion($id, $estilo, $numero, $capacidad, $habitaciones_num, $camas, $banos,
                                                $slug, $descripcion, $precio, $direccion, $latitud, $longitud, $estado, $foto, $video)
    {
        $sql = "UPDATE habitaciones SET 
                estilo = ?, 
                numero = ?, 
                capacidad = ?, 
                habitaciones_num = ?, 
                camas = ?, 
                banos = ?,
                slug = ?, 
                descripcion = ?, 
                precio = ?, 
                direccion = ?, 
                latitud = ?, 
                longitud = ?, 
                estado = ?,
                foto = ?,
                video = ?
                WHERE id = ?";
        
        $params = [
            $estilo, $numero, $capacidad, $habitaciones_num, $camas, $banos,
            $slug, $descripcion, $precio, $direccion, $latitud, $longitud, $estado,
            $foto, $video, $id
        ];
        
        error_log("=== actualizarCasaConUbicacion ===");
        error_log("ID: $id, Lat: " . ($latitud ?? 'NULL') . ", Lng: " . ($longitud ?? 'NULL'));
        
        return $this->save($sql, $params);
    }
    
    // Método completo para editar casa con todos los campos extendidos
    public function editarCasaCompleta($id, $estilo, $numero, $capacidad, $habitaciones_num, $camas, $banos, 
                                        $slug, $descripcion, $precio, $direccion, $latitud, $longitud, $estado, $foto, $video)
    {
        try {
            $sql = "UPDATE habitaciones SET 
                    estilo = ?, 
                    numero = ?, 
                    capacidad = ?, 
                    habitaciones_num = ?, 
                    camas = ?, 
                    banos = ?,
                    slug = ?, 
                    descripcion = ?, 
                    precio = ?, 
                    direccion = ?, 
                    latitud = ?, 
                    longitud = ?, 
                    estado = ?,
                    foto = ?,
                    video = ?
                    WHERE id = ?";
            
            $params = [
                $estilo, $numero, $capacidad, $habitaciones_num, $camas, $banos,
                $slug, $descripcion, $precio, $direccion, $latitud, $longitud, $estado,
                $foto, $video, $id
            ];
            
            error_log("editarCasaCompleta - SQL: $sql");
            error_log("editarCasaCompleta - Params: " . print_r($params, true));
            
            return $this->save($sql, $params);
        } catch (Exception $e) {
            error_log("editarCasaCompleta - Error: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarCasa($id)
    {
        // Eliminación lógica (cambiar estado a 0) para mantener integridad referencial
        $sql = "UPDATE habitaciones SET estado = 0 WHERE id = ?";
        $array = [$id];
        return $this->save($sql, $array);
    }

    public function verificarReservasActivasCasa($id_habitacion)
    {
        $sql = "SELECT COUNT(*) as total FROM reservas 
                WHERE id_habitacion = $id_habitacion 
                AND estado = 1 
                AND fecha_salida >= CURDATE()";
        $result = $this->select($sql);
        return $result['total'];
    }

    public function getCasasDisponibles()
    {
        $sql = "SELECT COUNT(*) as total FROM habitaciones WHERE estado = 1";
        $result = $this->select($sql);
        return $result['total'];
    }

    // ==================== MÉTODOS PARA GESTIÓN DE FOTOS ====================
    
    public function getFotosPropiedad($id_habitacion)
    {
        $sql = "SELECT * FROM fotos_propiedad WHERE id_habitacion = ? ORDER BY es_principal DESC, orden ASC";
        return $this->selectAll($sql, [$id_habitacion]);
    }

    public function agregarFotoPropiedad($id_habitacion, $url_imagen, $es_principal = 0, $orden = 0)
    {
        // Si es principal, quitar el estado principal de las demás
        if ($es_principal == 1) {
            $this->save("UPDATE fotos_propiedad SET es_principal = 0 WHERE id_habitacion = ?", [$id_habitacion]);
        }
        
        $sql = "INSERT INTO fotos_propiedad (id_habitacion, url_imagen, es_principal, orden) VALUES (?, ?, ?, ?)";
        return $this->save($sql, [$id_habitacion, $url_imagen, $es_principal, $orden]);
    }

    public function eliminarFotoPropiedad($id_foto)
    {
        // Obtener info de la foto antes de eliminar
        $foto = $this->select("SELECT * FROM fotos_propiedad WHERE id = ?", [$id_foto]);
        if ($foto) {
            $this->save("DELETE FROM fotos_propiedad WHERE id = ?", [$id_foto]);
            return $foto;
        }
        return false;
    }

    public function setFotoPrincipal($id_foto, $id_habitacion)
    {
        $this->save("UPDATE fotos_propiedad SET es_principal = 0 WHERE id_habitacion = ?", [$id_habitacion]);
        return $this->save("UPDATE fotos_propiedad SET es_principal = 1 WHERE id = ?", [$id_foto]);
    }

    // ==================== MÉTODOS PARA AMENIDADES ====================
    
    public function getAmenidades()
    {
        $sql = "SELECT * FROM amenidades WHERE estado = 1 ORDER BY categoria, nombre";
        return $this->selectAll($sql);
    }

    public function getAmenidadesPropiedad($id_habitacion)
    {
        $sql = "SELECT a.* FROM amenidades a 
                INNER JOIN propiedad_amenidades pa ON a.id = pa.id_amenidad 
                WHERE pa.id_habitacion = ? AND a.estado = 1
                ORDER BY a.categoria, a.nombre";
        return $this->selectAll($sql, [$id_habitacion]);
    }

    public function setAmenidadesPropiedad($id_habitacion, $amenidades = [])
    {
        // Eliminar amenidades actuales
        $this->save("DELETE FROM propiedad_amenidades WHERE id_habitacion = ?", [$id_habitacion]);
        
        // Insertar nuevas amenidades
        if (!empty($amenidades)) {
            foreach ($amenidades as $id_amenidad) {
                $this->save("INSERT INTO propiedad_amenidades (id_habitacion, id_amenidad) VALUES (?, ?)", 
                    [$id_habitacion, $id_amenidad]);
            }
        }
        return true;
    }

    // ==================== MÉTODOS PARA EVALUACIONES ====================
    
    public function getEvaluacionesPropiedad($id_habitacion, $limite = 10)
    {
        $sql = "SELECT e.*, u.nombre as nombre_usuario, u.correo as correo_usuario
                FROM evaluaciones e
                INNER JOIN usuarios u ON e.id_usuario = u.id
                WHERE e.id_habitacion = ? AND e.estado = 1
                ORDER BY e.fecha_evaluacion DESC
                LIMIT $limite";
        return $this->selectAll($sql, [$id_habitacion]);
    }

    public function getEstadisticasEvaluaciones($id_habitacion)
    {
        $sql = "SELECT 
                    AVG(calificacion_general) as promedio_general,
                    AVG(limpieza) as promedio_limpieza,
                    AVG(veracidad) as promedio_veracidad,
                    AVG(llegada) as promedio_llegada,
                    AVG(comunicacion) as promedio_comunicacion,
                    AVG(ubicacion) as promedio_ubicacion,
                    AVG(calidad_precio) as promedio_calidad_precio,
                    COUNT(*) as total_evaluaciones
                FROM evaluaciones 
                WHERE id_habitacion = ? AND estado = 1";
        return $this->select($sql, [$id_habitacion]);
    }

    // ==================== MÉTODOS EXTENDIDOS PARA PROPIEDADES AIRBNB ====================
    
    public function getCasaCompleta($id)
    {
        $sql = "SELECT h.*, 
                (SELECT url_imagen FROM fotos_propiedad WHERE id_habitacion = h.id AND es_principal = 1 LIMIT 1) as foto_principal
                FROM habitaciones h WHERE h.id = ?";
        $casa = $this->select($sql, [$id]);
        
        if ($casa) {
            $casa['fotos'] = $this->getFotosPropiedad($id);
            $casa['amenidades'] = $this->getAmenidadesPropiedad($id);
            $casa['evaluaciones'] = $this->getEvaluacionesPropiedad($id, 5);
            $casa['estadisticas'] = $this->getEstadisticasEvaluaciones($id);
        }
        
        return $casa;
    }

    public function getCasasParaCatalogo($filtros = [])
    {
        $sql = "SELECT h.*, 
                (SELECT url_imagen FROM fotos_propiedad WHERE id_habitacion = h.id AND es_principal = 1 LIMIT 1) as foto_principal,
                (SELECT AVG(calificacion_general) FROM evaluaciones WHERE id_habitacion = h.id AND estado = 1) as rating,
                (SELECT COUNT(*) FROM evaluaciones WHERE id_habitacion = h.id AND estado = 1) as num_evaluaciones
                FROM habitaciones h 
                WHERE h.estado = 1";
        
        $params = [];
        
        // Filtros opcionales
        if (!empty($filtros['capacidad_min'])) {
            $sql .= " AND h.capacidad >= ?";
            $params[] = $filtros['capacidad_min'];
        }
        
        if (!empty($filtros['precio_max'])) {
            $sql .= " AND h.precio <= ?";
            $params[] = $filtros['precio_max'];
        }
        
        if (!empty($filtros['habitaciones'])) {
            $sql .= " AND h.habitaciones_num >= ?";
            $params[] = $filtros['habitaciones'];
        }
        
        $sql .= " ORDER BY h.es_favorito_huespedes DESC, h.calificacion_promedio DESC, h.fecha DESC";
        
        return empty($params) ? $this->selectAll($sql) : $this->selectAll($sql, $params);
    }

    public function actualizarCasaExtendida($id, $datos)
    {
        // Construir SQL dinámicamente para incluir foto y video solo si están presentes
        $campos = "estilo = ?, numero = ?, capacidad = ?, habitaciones_num = ?, camas = ?, banos = ?,
                   slug = ?, descripcion = ?, precio = ?, tarifa_limpieza = ?,
                   direccion = ?, latitud = ?, longitud = ?, estado = ?, es_favorito_huespedes = ?";
        
        $params = [
            $datos['estilo'], $datos['numero'], $datos['capacidad'], 
            $datos['habitaciones_num'] ?? 1, $datos['camas'] ?? 1, $datos['banos'] ?? 1,
            $datos['slug'], $datos['descripcion'], $datos['precio'], $datos['tarifa_limpieza'] ?? 0,
            $datos['direccion'] ?? '', $datos['latitud'] ?? null, $datos['longitud'] ?? null,
            $datos['estado'], $datos['es_favorito_huespedes'] ?? 0
        ];
        
        // Agregar foto si está presente
        if (isset($datos['foto'])) {
            $campos .= ", foto = ?";
            $params[] = $datos['foto'];
        }
        
        // Agregar video si está presente
        if (isset($datos['video'])) {
            $campos .= ", video = ?";
            $params[] = $datos['video'];
        }
        
        $params[] = $id;
        $sql = "UPDATE habitaciones SET $campos WHERE id = ?";
        
        return $this->save($sql, $params);
    }
    
    // Obtener el último ID insertado (ya no se usa, insert() devuelve el ID)
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    // Contar fotos de una propiedad
    public function contarFotosPropiedad($id_habitacion)
    {
        $sql = "SELECT COUNT(*) as total FROM fotos_propiedad WHERE id_habitacion = ?";
        $result = $this->select($sql, [$id_habitacion]);
        return $result ? intval($result['total']) : 0;
    }

    public function crearCasaExtendida($datos)
    {
        $sql = "INSERT INTO habitaciones (
                estilo, numero, capacidad, habitaciones_num, camas, banos,
                slug, foto, video, descripcion, precio, tarifa_limpieza,
                direccion, latitud, longitud, estado, es_favorito_huespedes, fecha
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $params = [
            $datos['estilo'], $datos['numero'], $datos['capacidad'],
            $datos['habitaciones_num'] ?? 1, $datos['camas'] ?? 1, $datos['banos'] ?? 1,
            $datos['slug'], $datos['foto'] ?? '', $datos['video'] ?? '', $datos['descripcion'],
            $datos['precio'], $datos['tarifa_limpieza'] ?? 0,
            $datos['direccion'] ?? '', $datos['latitud'] ?? null, $datos['longitud'] ?? null,
            $datos['estado'], $datos['es_favorito_huespedes'] ?? 0
        ];
        
        // Usar insert() que devuelve el lastInsertId
        return $this->insert($sql, $params);
    }

    // Actualizar calificación promedio de una propiedad
    public function actualizarCalificacionPropiedad($id_habitacion)
    {
        $stats = $this->getEstadisticasEvaluaciones($id_habitacion);
        if ($stats) {
            $sql = "UPDATE habitaciones SET calificacion_promedio = ?, total_evaluaciones = ? WHERE id = ?";
            return $this->save($sql, [$stats['promedio_general'] ?? 0, $stats['total_evaluaciones'] ?? 0, $id_habitacion]);
        }
        return false;
    }
}
