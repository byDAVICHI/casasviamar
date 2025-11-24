<?php
// DETECCIÓN AUTOMÁTICA DE ENTORNO
$isProduction = (
    isset($_SERVER['HTTP_HOST']) && 
    (
        $_SERVER['HTTP_HOST'] === 'www.casasviamar.com' || 
        $_SERVER['HTTP_HOST'] === 'casasviamar.com' ||
        strpos($_SERVER['HTTP_HOST'], 'hostinger') !== false
    )
);

// CONFIGURACIÓN DINÁMICA POR ENTORNO
define('ADMIN', 'admin');

if ($isProduction) {
    // CONFIGURACIÓN DE PRODUCCIÓN
    define('RUTA_PRINCIPAL', 'https://www.casasviamar.com/');
    define('HOST', 'localhost');
    define('USER', 'u204448082_root');
    define('PASS', 'di1234YA');
    define('DATABASE', 'u204448082_reservas');
    define('ENVIRONMENT', 'production');
} else {
    // CONFIGURACIÓN DE DESARROLLO
    define('RUTA_PRINCIPAL', 'http://localhost/casasviamar/');
    define('HOST', 'localhost');
    define('USER', 'root');
    define('PASS', '');
    define('DATABASE', 'reservas');
    define('ENVIRONMENT', 'development');
}

define('RUTA_ADMIN', RUTA_PRINCIPAL . ADMIN . '/');
define('CHARSET', 'charset=utf8');
define('TITLE', 'VIA-MAR');
// CREDENCIALES PAYPAL
define('MONEDA_PAYPAL', 'USD');
define('CLIENTE_ID', 'ARbJgmCyDLc0AaAYm8r-QGI3kWvrawyt-jITk-pOhH6sh_bkPHwa0Qr5j7CVWLUVGp7OHP1wFPG08Ko2');
// CREDENCIALES MERCADO PAGO
define('PUBLIC_KEY', 'TESTUSER2075515844');
define('ACCESS_TOKEN','5pv5EilDTs');
