<?php

class PerfilModel extends Query
{
    public function __construct()
    {
        parent::__construct();
    }

    // Obtener usuario por ID
    public function getUsuarioById($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ?";
        return $this->select($sql, [$id]);
    }

    // Verificar si el correo ya existe (excluyendo el usuario actual)
    public function verificarCorreoExistente($correo, $idExcluir)
    {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE correo = ? AND id != ?";
        $result = $this->select($sql, [$correo, $idExcluir]);
        return $result['total'] > 0;
    }

    // Verificar si el usuario ya existe (excluyendo el usuario actual)
    public function verificarUsuarioExistente($usuario, $idExcluir)
    {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE usuario = ? AND id != ?";
        $result = $this->select($sql, [$usuario, $idExcluir]);
        return $result['total'] > 0;
    }

    // Actualizar datos del usuario
    public function actualizarUsuario($id, $nombre, $apellido, $usuario, $correo)
    {
        $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, usuario = ?, correo = ? WHERE id = ?";
        return $this->save($sql, [$nombre, $apellido, $usuario, $correo, $id]);
    }

    // Actualizar contraseña
    public function actualizarPassword($id, $password)
    {
        $sql = "UPDATE usuarios SET clave = ? WHERE id = ?";
        return $this->save($sql, [$password, $id]);
    }

    // Actualizar foto de perfil
    public function actualizarFoto($id, $foto)
    {
        $sql = "UPDATE usuarios SET foto = ? WHERE id = ?";
        return $this->save($sql, [$foto, $id]);
    }

    // Obtener reservas del usuario
    public function getReservasUsuario($idUsuario)
    {
        $sql = "SELECT r.*, h.estilo, h.foto, h.precio, h.direccion, h.capacidad
                FROM reservas r
                INNER JOIN habitaciones h ON r.id_habitacion = h.id
                WHERE r.id_usuario = ?
                ORDER BY r.fecha_ingreso DESC";
        $result = $this->selectAll($sql, [$idUsuario]);
        return $result ? $result : [];
    }

    // Obtener una reserva específica
    public function getReservaById($id, $idUsuario)
    {
        $sql = "SELECT r.*, h.estilo, h.foto, h.precio, h.direccion
                FROM reservas r
                INNER JOIN habitaciones h ON r.id_habitacion = h.id
                WHERE r.id = ? AND r.id_usuario = ?";
        return $this->select($sql, [$id, $idUsuario]);
    }
}
