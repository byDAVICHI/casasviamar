<?php
require_once 'config/config.php';
require_once 'helpers/funciones.php';
// CAPTURAR LA URL ACTUAL
// $currentPageUrl = $_SERVER['REQUEST_URI'];
// VERIFICAR SI EXISTE LA RUTA ADMIN
//$isAdmin = strpos($currentPageUrl, '/' . ADMIN) !== false;
$isAdmin = strpos($_SERVER['REQUEST_URI'], '/' . ADMIN) !== false;
// COMPROBAR SI EXISTE GET PARA CREAR URLS AMIGABLES
$ruta = empty($_GET['url']) ? 'principal/index' : $_GET['url'];
// CREAR UN ARRAY A PARTIR DE LA RUTA
$array = explode('/', $ruta);
// VALIDAR SI NOS ENCONTRAMOS EN LA RUTA ADMIN
if ($isAdmin) {
    // Para rutas de admin, siempre usar el controlador Admin
    $controller = 'Admin';
    
    // Si solo es 'admin' o 'admin/', usar método login
    if (count($array) == 1 || (count($array) == 2 && empty($array[1]))) {
        $metodo = 'login';
    } else {
        // Si hay más partes en la URL, la segunda parte es el método
        $metodo = isset($array[1]) && !empty($array[1]) ? $array[1] : 'login';
    }
} else {
    $indiceUrl = 0;
    $controller  = ucfirst($array[$indiceUrl]);
    $metodo = 'index';
}

// VALIDAR METODOS (solo para rutas no admin)
if (!$isAdmin) {
    $metodoIndice = 1;
    if (!empty($array[$metodoIndice]) && $array[$metodoIndice] != '') {
        $metodo = $array[$metodoIndice];
    }
}

// VALIDAR PARAMETROS
$parametro = '';
$parametroIndice = ($isAdmin) ? 2 : 2;
if (!empty($array[$parametroIndice]) && $array[$parametroIndice] != '') {
    for ($i = $parametroIndice; $i < count($array); $i++) {
        $parametro .= $array[$i] . ',';
    }
    $parametro = trim($parametro, ',');
}

// LLAMAR AUTOLOAD
require_once 'config/app/Autoload.php';

// VALIDAR DIRECTORIO DE CONTROLADORES

$dirControllers = ($isAdmin) ? 'controllers/admin/' . $controller . '.php' : 'controllers/principal/' . $controller . '.php';
if (file_exists($dirControllers)) {
    require_once $dirControllers;
    $controller = new $controller();
    if (method_exists($controller, $metodo)) {
        $controller->$metodo($parametro);
    } else {
        echo 'METODO NO EXISTE';
    }
} else {
    echo 'CONTROLADOR NO EXISTE';
}
