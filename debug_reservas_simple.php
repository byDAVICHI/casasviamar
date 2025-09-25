<?php
require_once 'config/config.php';
require_once 'helpers/funciones.php';
require_once 'config/app/Autoload.php';
require_once 'config/app/Conexion.php';
require_once 'config/app/Query.php';
require_once 'models/admin/AdminModel.php';

session_start();
$_SESSION['id_admin'] = 4;

header('Content-Type: application/json');

try {
    $model = new AdminModel();
    $reservas = $model->getTodasReservas();
    
    // Agregar información de debug
    $debug = [
        'total_reservas' => count($reservas),
        'primera_reserva' => !empty($reservas) ? $reservas[0] : null,
        'campos_disponibles' => !empty($reservas) ? array_keys($reservas[0]) : [],
        'reservas' => $reservas
    ];
    
    echo json_encode($debug, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'mensaje' => $e->getMessage(),
        'archivo' => $e->getFile(),
        'linea' => $e->getLine()
    ], JSON_PRETTY_PRINT);
}
?>
