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
}
