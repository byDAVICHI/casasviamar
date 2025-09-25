<?php
// Test para verificar las correcciones del sistema de administración
require_once 'config/config.php';
require_once 'helpers/helpers.php';

echo "<h2>🔧 Test de Correcciones del Sistema Admin</h2>";
echo "<hr>";

// Conectar a la base de datos
try {
    $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DB, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ <strong>Conexión a BD exitosa</strong><br><br>";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

echo "<h3>📊 Estado Actual de Reservas</h3>";

// Mostrar todas las reservas actuales
$sql = "SELECT r.id, r.fecha_ingreso, r.fecha_salida, r.precio, r.monto, r.estado,
               CONCAT(u.nombre, ' ', u.apellido) as usuario,
               h.estilo as habitacion, h.precio as precio_habitacion
        FROM reservas r 
        LEFT JOIN usuarios u ON r.id_usuario = u.id 
        LEFT JOIN habitaciones h ON r.id_habitacion = h.id 
        ORDER BY r.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($reservas)) {
    echo "⚠️ <strong>No hay reservas en la base de datos</strong><br>";
    echo "Para probar las funcionalidades, primero crea algunas reservas desde el panel admin.<br><br>";
} else {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f0f0f0;'>
            <th>ID</th>
            <th>Usuario</th>
            <th>Habitación</th>
            <th>Fecha Ingreso</th>
            <th>Fecha Salida</th>
            <th>Precio Total</th>
            <th>Monto</th>
            <th>Estado</th>
            <th>Precio/Noche</th>
          </tr>";
    
    foreach ($reservas as $reserva) {
        $estado_texto = $reserva['estado'] == 1 ? 'Activa' : 'Inactiva';
        $color_estado = $reserva['estado'] == 1 ? 'green' : 'red';
        
        echo "<tr>";
        echo "<td>{$reserva['id']}</td>";
        echo "<td>{$reserva['usuario']}</td>";
        echo "<td>{$reserva['habitacion']}</td>";
        echo "<td>{$reserva['fecha_ingreso']}</td>";
        echo "<td>{$reserva['fecha_salida']}</td>";
        echo "<td>$" . number_format($reserva['precio'], 2) . "</td>";
        echo "<td>$" . number_format($reserva['monto'], 2) . "</td>";
        echo "<td style='color: {$color_estado};'><strong>{$estado_texto}</strong></td>";
        echo "<td>$" . number_format($reserva['precio_habitacion'], 2) . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
}

echo "<h3>🧮 Test de Cálculo de Precios</h3>";

// Función para calcular noches y precio
function calcularPrecioReserva($fecha_ingreso, $fecha_salida, $precio_noche) {
    $fecha_inicio = new DateTime($fecha_ingreso);
    $fecha_fin = new DateTime($fecha_salida);
    $diferencia = $fecha_inicio->diff($fecha_fin);
    $total_noches = $diferencia->days;
    $precio_total = $precio_noche * $total_noches;
    
    return [
        'noches' => $total_noches,
        'precio_total' => $precio_total
    ];
}

// Ejemplos de cálculo
$ejemplos = [
    ['2024-01-15', '2024-01-18', 150.00], // 3 noches
    ['2024-02-01', '2024-02-07', 200.00], // 6 noches
    ['2024-03-10', '2024-03-15', 175.00]  // 5 noches
];

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #e8f4fd;'>
        <th>Fecha Ingreso</th>
        <th>Fecha Salida</th>
        <th>Precio/Noche</th>
        <th>Total Noches</th>
        <th>Precio Total</th>
      </tr>";

foreach ($ejemplos as $ejemplo) {
    $calculo = calcularPrecioReserva($ejemplo[0], $ejemplo[1], $ejemplo[2]);
    echo "<tr>";
    echo "<td>{$ejemplo[0]}</td>";
    echo "<td>{$ejemplo[1]}</td>";
    echo "<td>$" . number_format($ejemplo[2], 2) . "</td>";
    echo "<td>{$calculo['noches']} noches</td>";
    echo "<td>$" . number_format($calculo['precio_total'], 2) . "</td>";
    echo "</tr>";
}
echo "</table><br>";

echo "<h3>🎯 Instrucciones para Probar las Correcciones</h3>";
echo "<div style='background-color: #f9f9f9; padding: 15px; border-left: 4px solid #007bff;'>";
echo "<h4>1. Test de Eliminación:</h4>";
echo "• Ve al panel admin: <a href='" . RUTA_ADMIN . "reservas' target='_blank'>Gestión de Reservas</a><br>";
echo "• Selecciona una reserva y haz clic en 'Eliminar'<br>";
echo "• <strong>Verifica que la reserva desaparezca completamente</strong> (no solo cambie estado)<br>";
echo "• Recarga esta página para confirmar que ya no aparece en la tabla<br><br>";

echo "<h4>2. Test de Edición de Fechas:</h4>";
echo "• En el panel admin, selecciona una reserva y haz clic en 'Editar'<br>";
echo "• Cambia las fechas de ingreso y salida<br>";
echo "• <strong>Verifica que el precio total se recalcule automáticamente</strong><br>";
echo "• Guarda los cambios y confirma que el nuevo precio aparece en la tabla<br><br>";

echo "<h4>3. Verificación en Base de Datos:</h4>";
echo "• Las reservas eliminadas deben desaparecer completamente de la tabla 'reservas'<br>";
echo "• Las reservas editadas deben tener el precio y monto actualizados correctamente<br>";
echo "</div><br>";

echo "<h3>🔍 Consultas SQL para Verificación Manual</h3>";
echo "<div style='background-color: #f8f9fa; padding: 10px; font-family: monospace;'>";
echo "<strong>Ver todas las reservas:</strong><br>";
echo "SELECT * FROM reservas ORDER BY id DESC;<br><br>";

echo "<strong>Ver reservas con detalles:</strong><br>";
echo "SELECT r.*, CONCAT(u.nombre, ' ', u.apellido) as usuario, h.estilo as habitacion<br>";
echo "FROM reservas r<br>";
echo "LEFT JOIN usuarios u ON r.id_usuario = u.id<br>";
echo "LEFT JOIN habitaciones h ON r.id_habitacion = h.id<br>";
echo "ORDER BY r.id DESC;<br>";
echo "</div><br>";

echo "<div style='background-color: #d4edda; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;'>";
echo "✅ <strong>Correcciones Implementadas:</strong><br>";
echo "1. Eliminación real de reservas (DELETE en lugar de UPDATE estado=0)<br>";
echo "2. Recálculo automático de precio total al editar fechas<br>";
echo "3. Actualización correcta de columnas precio y monto<br>";
echo "</div>";

?>
