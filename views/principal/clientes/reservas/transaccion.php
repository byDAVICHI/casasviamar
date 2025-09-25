<?php
// INSERTAR RESERVA
include_once 'models/principal/RegistroModel.php';
session_start();
$total = $_SESSION['total'];
$f_llegada = $_SESSION['reserva']['f_llegada'];
$f_salida = $_SESSION['reserva']['f_salida'];
$habitacion = $_SESSION['habitacionR'];
$usuario = $_SESSION['usuario'];

$model = new RegistroModel();
$model->transaccion($total, $f_llegada, $f_salida, $habitacion, $usuario);
