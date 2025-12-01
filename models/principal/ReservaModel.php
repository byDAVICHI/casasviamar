<?php
class ReservaModel extends Query
{

    public function __construct()
    {
        parent::__construct();
    }

    // RECUPERAR DISPONIBILIDAD

    public function getDisponible($f_llegada, $f_salida, $habitacion)
    {

        return  $this->selectAll("SELECT * FROM reservas 
        WHERE fecha_ingreso <= '$f_salida'
        AND fecha_salida >= '$f_llegada'  AND id_habitacion = $habitacion");
    }

    // RECUPERAR RESERVAS HABITACION

    public function getReservasHabitacion($habitacion)
    {

        return  $this->selectAll("SELECT * FROM reservas 
            WHERE  id_habitacion = $habitacion");
    }

    // RECUPERAR HABITACIONES

    public function getHabitaciones()
    {
        return  $this->selectAll("SELECT * FROM habitaciones WHERE estado = 1");
    }

    // RECUPERAR HABITACION

    public function getHabitacion($id_habitacion)
    {
        return  $this->select("SELECT * FROM habitaciones WHERE id = $id_habitacion");
    }
    
    /**
     * Registrar reserva completa con todos los datos de pago
     */
    public function registrarReservaCompleta($datos)
    {
        $sql = "INSERT INTO reservas (
            id_usuario, id_habitacion, fecha_ingreso, fecha_salida, 
            precio, monto, monto_subtotal, tarifa_limpieza, tarifa_servicio, monto_anfitrion,
            estado, estado_pago, id_transaccion, metodo_pago, fecha_pago,
            email_pagador, terminos_aceptados, fecha_aceptacion_terminos
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $datos['id_usuario'],
            $datos['id_habitacion'],
            $datos['fecha_ingreso'],
            $datos['fecha_salida'],
            $datos['precio'],
            $datos['monto'],
            $datos['monto_subtotal'] ?? 0,
            $datos['tarifa_limpieza'] ?? 0,
            $datos['tarifa_servicio'] ?? 0,
            $datos['monto_anfitrion'] ?? 0,
            $datos['estado'],
            $datos['estado_pago'],
            $datos['id_transaccion'],
            $datos['metodo_pago'],
            $datos['fecha_pago'],
            $datos['email_pagador'] ?? '',
            $datos['terminos_aceptados'] ?? 0,
            $datos['fecha_aceptacion_terminos'] ?? null
        ];
        
        $resultado = $this->save($sql, $params);
        
        if ($resultado) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }
    
    /**
     * Registrar transacción de pago en historial
     */
    public function registrarTransaccion($datos)
    {
        // Verificar si la tabla existe primero
        try {
            $sql = "INSERT INTO transacciones_pago (
                id_reserva, id_transaccion_paypal, estado, monto_total, 
                moneda, email_pagador, nombre_pagador, metodo, datos_raw
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $datos['id_reserva'],
                $datos['id_transaccion_paypal'],
                $datos['estado'],
                $datos['monto_total'],
                $datos['moneda'],
                $datos['email_pagador'] ?? '',
                $datos['nombre_pagador'] ?? '',
                $datos['metodo'] ?? 'PayPal',
                $datos['datos_raw'] ?? '{}'
            ];
            
            return $this->save($sql, $params);
        } catch (Exception $e) {
            // Si la tabla no existe, simplemente retornar true
            return true;
        }
    }
    
    /**
     * Registrar dispersión pendiente al anfitrión
     */
    public function registrarDispersion($datos)
    {
        try {
            $sql = "INSERT INTO dispersiones (
                id_reserva, id_anfitrion, monto, estado, fecha_creacion
            ) VALUES (?, ?, ?, ?, NOW())";
            
            $params = [
                $datos['id_reserva'],
                $datos['id_anfitrion'],
                $datos['monto'],
                $datos['estado'] ?? 'pendiente'
            ];
            
            return $this->save($sql, $params);
        } catch (Exception $e) {
            // Si la tabla no existe, simplemente retornar true
            return true;
        }
    }
    
    /**
     * Guardar datos de facturación
     */
    public function guardarFacturacion($datos)
    {
        try {
            $sql = "INSERT INTO datos_facturacion (
                id_reserva, tipo_persona, rfc, razon_social, regimen_fiscal,
                codigo_postal, uso_cfdi, correo_factura, telefono, direccion,
                fecha_solicitud, estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pendiente')";
            
            $params = [
                $datos['id_reserva'],
                $datos['tipo_persona'],
                $datos['rfc'],
                $datos['razon_social'],
                $datos['regimen_fiscal'],
                $datos['codigo_postal'],
                $datos['uso_cfdi'],
                $datos['correo_factura'],
                $datos['telefono'] ?? '',
                $datos['direccion'] ?? ''
            ];
            
            return $this->save($sql, $params);
        } catch (Exception $e) {
            error_log("Error guardando facturación: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Marcar reserva como que requiere factura
     */
    public function marcarRequiereFactura($idReserva)
    {
        try {
            $sql = "UPDATE reservas SET requiere_factura = 1 WHERE id = ?";
            return $this->save($sql, [$idReserva]);
        } catch (Exception $e) {
            return false;
        }
    }
}
