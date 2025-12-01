<?php
class Admin extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }

    public function login()
    {
        $data['title'] = 'Administrador - Login';
        $this->views->getView('admin/login', $data);
    }

    public function verify()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (validarCampos(['usuario', 'clave'])) {
                $usuario = strClean($_POST['usuario']);
                $clave = strClean($_POST['clave']);

                // VERIFICAR ACCESO DE ADMINISTRADOR
                $verificar = $this->model->validarAccesoAdmin($usuario);
                if (empty($verificar)) {
                    $res = ['tipo' => 'warning', 'msg' => 'EL USUARIO ADMINISTRADOR NO EXISTE'];
                } else {
                    if (password_verify($clave, $verificar['clave'])) {
                        // VERIFICAR QUE SEA ADMINISTRADOR (acepta 'admin' o 1)
                        if ($verificar['rol'] == 'admin' || $verificar['rol'] == 1) {
                            // CREAR SESIONES DE ADMINISTRADOR
                            crearSession([
                                'id_admin' => $verificar['id'],
                                'usuario_admin' => $verificar['usuario'],
                                'correo_admin' => $verificar['correo'],
                                'nombre_admin' => $verificar['nombre'] . ' ' . $verificar['apellido'],
                                'rol_admin' => $verificar['rol']
                            ]);
                            $res = ['tipo' => 'success', 'msg' => 'BIENVENIDO ADMINISTRADOR'];
                        } else {
                            $res = ['tipo' => 'warning', 'msg' => 'NO TIENES PERMISOS DE ADMINISTRADOR'];
                        }
                    } else {
                        $res = ['tipo' => 'warning', 'msg' => 'CONTRASEÑA INCORRECTA'];
                    }
                }
            } else {
                $res = ['tipo' => 'warning', 'msg' => 'TODOS LOS CAMPOS SON REQUERIDOS'];
            }
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function dashboard()
    {
        // VERIFICAR SESIÓN DE ADMINISTRADOR
        if (!isset($_SESSION['id_admin'])) {
            header('Location: ' . RUTA_ADMIN);
            exit;
        }
        
        // Debug temporal: verificar si existe nombre_admin
        if (!isset($_SESSION['nombre_admin'])) {
            // Si no existe, intentar recrearlo desde otros datos de sesión
            if (isset($_SESSION['usuario_admin'])) {
                $_SESSION['nombre_admin'] = 'Administrador del Sistema';
            }
        }
        
        $data['title'] = 'Dashboard - Administrador';
        $data['habitaciones'] = $this->model->getHabitaciones();
        $data['total_reservas'] = $this->model->getTotalReservas();
        $data['reservas_hoy'] = $this->model->getReservasHoy();
        $data['usuarios_total'] = $this->model->getTotalUsuarios();
        
        $this->views->getView('admin/dashboard', $data);
    }

    public function reservas()
    {
        // VERIFICAR SESIÓN DE ADMINISTRADOR
        if (!isset($_SESSION['id_admin'])) {
            header('Location: ' . RUTA_ADMIN);
            exit;
        }
        
        $data['title'] = 'Calendario de Reservas - Administrador';
        $data['habitaciones'] = $this->model->getHabitaciones();
        
        $this->views->getView('admin/reservas', $data);
    }

    public function nueva_reservacion()
    {
        // VERIFICAR SESIÓN DE ADMINISTRADOR
        if (!isset($_SESSION['id_admin'])) {
            header('Location: ' . RUTA_ADMIN);
            exit;
        }
        
        $data['title'] = 'Nueva Reservación - Administrador';
        $data['habitaciones'] = $this->model->getHabitaciones();
        
        $this->views->getView('admin/nueva_reservacion', $data);
    }

    public function getReservas()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['error' => 'No autorizado']);
            die();
        }

        $habitacion = isset($_GET['habitacion']) ? intval($_GET['habitacion']) : 0;
        
        // Verificar si es para el calendario o para la tabla
        $formato = isset($_GET['formato']) ? $_GET['formato'] : 'calendario';
        
        if ($habitacion > 0) {
            $reservas = $this->model->getReservasPorHabitacion($habitacion);
        } else {
            $reservas = $this->model->getTodasReservas();
        }

        if ($formato === 'tabla') {
            // Devolver datos para la tabla con todos los campos necesarios
            $results = [];
            foreach ($reservas as $reserva) {
                $datos = [
                    'id' => $reserva['id'],
                    'fecha_ingreso' => $reserva['fecha_ingreso'],
                    'fecha_salida' => $reserva['fecha_salida'],
                    'nombre_habitacion' => $reserva['nombre_habitacion'],
                    'nombre_usuario' => $reserva['nombre_usuario'],
                    'precio_total' => floatval($reserva['precio'] ?: $reserva['monto'] ?: 0),
                    'precio' => floatval($reserva['precio'] ?: $reserva['monto'] ?: 0),
                    'monto' => floatval($reserva['monto'] ?: $reserva['precio'] ?: 0),
                    'estado' => intval($reserva['estado']),
                    'num_transaccion' => $reserva['num_transaccion'] ?? '',
                    'cod_reserva' => $reserva['cod_reserva'] ?? '',
                    'descripcion' => $reserva['descripcion'] ?? '',
                    'metodo' => intval($reserva['metodo'] ?? 0),
                    'fecha_reserva' => $reserva['fecha_reserva'] ?? ''
                ];
                $results[] = $datos;
            }
        } else {
            // Devolver datos para el calendario (formato original)
            $results = [];
            foreach ($reservas as $reserva) {
                $datos = [
                    'id' => $reserva['id'],
                    'title' => 'Reserva - ' . $reserva['nombre_usuario'],
                    'start' => $reserva['fecha_ingreso'],
                    'end' => $reserva['fecha_salida'],
                    'color' => '#28a745',
                    'extendedProps' => [
                        'usuario' => $reserva['nombre_usuario'],
                        'habitacion' => $reserva['nombre_habitacion'],
                        'precio' => floatval($reserva['precio'] ?: $reserva['monto'] ?: 0),
                        'estado' => $reserva['estado']
                    ]
                ];
                $results[] = $datos;
            }
        }

        echo json_encode($results, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function eliminarReserva()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = intval($_POST['id']);
            
            if ($id > 0) {
                $resultado = $this->model->eliminarReserva($id);
                if ($resultado) {
                    $res = ['tipo' => 'success', 'msg' => 'RESERVA ELIMINADA CORRECTAMENTE'];
                } else {
                    $res = ['tipo' => 'error', 'msg' => 'ERROR AL ELIMINAR LA RESERVA'];
                }
            } else {
                $res = ['tipo' => 'error', 'msg' => 'ID DE RESERVA INVÁLIDO'];
            }
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function editarReserva()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = intval($_POST['id']);
            $fecha_ingreso = strClean($_POST['fecha_ingreso']);
            $fecha_salida = strClean($_POST['fecha_salida']);
            
            if ($id > 0 && !empty($fecha_ingreso) && !empty($fecha_salida)) {
                $resultado = $this->model->editarReserva($id, $fecha_ingreso, $fecha_salida);
                if ($resultado) {
                    $res = ['tipo' => 'success', 'msg' => 'RESERVA ACTUALIZADA CORRECTAMENTE'];
                } else {
                    $res = ['tipo' => 'error', 'msg' => 'ERROR AL ACTUALIZAR LA RESERVA'];
                }
            } else {
                $res = ['tipo' => 'error', 'msg' => 'DATOS INCOMPLETOS'];
            }
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function getFacturacion($id_reserva = '')
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }
        
        $id = intval($id_reserva);
        if ($id <= 0) {
            echo json_encode(['tipo' => 'error', 'msg' => 'ID inválido']);
            die();
        }
        
        $datos = $this->model->getDatosFacturacion($id);
        
        if ($datos) {
            echo json_encode([
                'tipo' => 'success',
                'datos' => $datos
            ]);
        } else {
            echo json_encode([
                'tipo' => 'error',
                'msg' => 'No se encontraron datos de facturación'
            ]);
        }
        die();
    }

    public function crearReserva()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (validarCampos(['usuario', 'habitacion', 'fecha_ingreso', 'fecha_salida', 'precio'])) {
                $usuario = intval($_POST['usuario']);
                $habitacion = intval($_POST['habitacion']);
                $fecha_ingreso = strClean($_POST['fecha_ingreso']);
                $fecha_salida = strClean($_POST['fecha_salida']);
                $precio = floatval($_POST['precio']);
                
                // Verificar disponibilidad
                $disponible = $this->model->verificarDisponibilidad($fecha_ingreso, $fecha_salida, $habitacion);
                
                if (empty($disponible)) {
                    $resultado = $this->model->crearReserva($usuario, $habitacion, $fecha_ingreso, $fecha_salida, $precio);
                    if ($resultado) {
                        $res = ['tipo' => 'success', 'msg' => 'RESERVA CREADA CORRECTAMENTE'];
                    } else {
                        $res = ['tipo' => 'error', 'msg' => 'ERROR AL CREAR LA RESERVA'];
                    }
                } else {
                    $res = ['tipo' => 'warning', 'msg' => 'LAS FECHAS NO ESTÁN DISPONIBLES'];
                }
            } else {
                $res = ['tipo' => 'warning', 'msg' => 'TODOS LOS CAMPOS SON REQUERIDOS'];
            }
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function getUsuarios()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['error' => 'No autorizado']);
            die();
        }

        $usuarios = $this->model->getUsuarios();
        echo json_encode($usuarios, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getHabitaciones()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['error' => 'No autorizado']);
            die();
        }

        $habitaciones = $this->model->getHabitaciones();
        echo json_encode($habitaciones, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function actualizarReserva()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_POST) {
            $id = $_POST['id'];
            $fecha_ingreso = $_POST['fecha_ingreso'];
            $fecha_salida = $_POST['fecha_salida'];
            
            if (!empty($id) && !empty($fecha_ingreso) && !empty($fecha_salida)) {
                $data = $this->model->editarReserva($id, $fecha_ingreso, $fecha_salida);
                if ($data == 1) {
                    $res = ['tipo' => 'success', 'msg' => 'RESERVA ACTUALIZADA CORRECTAMENTE'];
                } else {
                    $res = ['tipo' => 'error', 'msg' => 'ERROR AL ACTUALIZAR LA RESERVA'];
                }
            } else {
                $res = ['tipo' => 'warning', 'msg' => 'TODOS LOS CAMPOS SON REQUERIDOS'];
            }
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }


    // ==================== GESTIÓN DE CASAS VACACIONALES ====================
    
    public function casas()
    {
        // VERIFICAR SESIÓN DE ADMINISTRADOR
        if (!isset($_SESSION['id_admin'])) {
            header('Location: ' . RUTA_ADMIN);
            exit;
        }
        
        $data['title'] = 'Gestión de Casas Vacacionales - Administrador';
        $data['casas'] = $this->model->getCasasVacacionales();
        
        $this->views->getView('admin/casas', $data);
    }

    public function getCasas()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['error' => 'No autorizado']);
            die();
        }

        $casas = $this->model->getCasasVacacionales();
        echo json_encode($casas, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function crearCasa()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            error_log("=== CREARCASA COMPLETO v3 ===");
            
            if (validarCampos(['estilo', 'numero', 'capacidad', 'precio', 'descripcion'])) {
                
                // Generar slug automático si está vacío
                $slug = strClean($_POST['slug'] ?? '');
                if (empty($slug)) {
                    $slug = $this->generarSlug($_POST['estilo']);
                }
                
                // Obtener TODOS los valores del formulario
                $estilo = strClean($_POST['estilo']);
                $numero = intval($_POST['numero']);
                $capacidad = intval($_POST['capacidad']);
                $habitaciones_num = intval($_POST['habitaciones_num'] ?? 1);
                $camas = intval($_POST['camas'] ?? 1);
                $banos = intval($_POST['banos'] ?? 1);
                $descripcion = strClean($_POST['descripcion']);
                $precio = floatval($_POST['precio']);
                $estado = intval($_POST['estado'] ?? 1);
                $foto = strClean($_POST['foto'] ?? '');
                $video = strClean($_POST['video'] ?? '');
                $direccion = strClean($_POST['direccion'] ?? '');
                
                // LATITUD Y LONGITUD
                $latitud = null;
                $longitud = null;
                if (!empty($_POST['latitud']) && is_numeric($_POST['latitud'])) {
                    $latitud = floatval($_POST['latitud']);
                }
                if (!empty($_POST['longitud']) && is_numeric($_POST['longitud'])) {
                    $longitud = floatval($_POST['longitud']);
                }
                
                error_log("crearCasa - estilo: $estilo, lat: " . ($latitud ?? 'NULL') . ", lng: " . ($longitud ?? 'NULL'));
                
                // Crear con todos los campos
                $resultado = $this->model->crearCasaConUbicacion(
                    $estilo, $numero, $capacidad, $habitaciones_num, $camas, $banos,
                    $slug, $descripcion, $precio, $direccion, $latitud, $longitud, $estado, $foto, $video
                );
                
                if ($resultado) {
                    $res = [
                        'tipo' => 'success', 
                        'msg' => 'CASA VACACIONAL CREADA CORRECTAMENTE',
                        'id_casa' => $resultado
                    ];
                } else {
                    $res = ['tipo' => 'error', 'msg' => 'ERROR AL CREAR LA CASA VACACIONAL'];
                }
            } else {
                $res = ['tipo' => 'warning', 'msg' => 'TODOS LOS CAMPOS OBLIGATORIOS SON REQUERIDOS'];
            }
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function editarCasa()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            error_log("=== EDITARCASA COMPLETO v3 ===");
            error_log("POST: " . json_encode($_POST));
            
            $id = intval($_POST['id'] ?? 0);
            
            if ($id > 0 && validarCampos(['estilo', 'numero', 'capacidad', 'precio', 'descripcion'])) {
                
                // Generar slug automático si está vacío
                $slug = strClean($_POST['slug'] ?? '');
                if (empty($slug)) {
                    $slug = $this->generarSlug($_POST['estilo']);
                }
                
                // Obtener valores del formulario - TODOS LOS CAMPOS
                $estilo = strClean($_POST['estilo']);
                $numero = intval($_POST['numero']);
                $capacidad = intval($_POST['capacidad']);
                $habitaciones_num = intval($_POST['habitaciones_num'] ?? 1);
                $camas = intval($_POST['camas'] ?? 1);
                $banos = intval($_POST['banos'] ?? 1);
                $descripcion = strClean($_POST['descripcion']);
                $precio = floatval($_POST['precio']);
                $estado = intval($_POST['estado'] ?? 1);
                $video = strClean($_POST['video'] ?? '');
                $direccion = strClean($_POST['direccion'] ?? '');
                
                // LATITUD Y LONGITUD - Convertir a float o null
                $latitud = null;
                $longitud = null;
                if (!empty($_POST['latitud']) && is_numeric($_POST['latitud'])) {
                    $latitud = floatval($_POST['latitud']);
                }
                if (!empty($_POST['longitud']) && is_numeric($_POST['longitud'])) {
                    $longitud = floatval($_POST['longitud']);
                }
                
                error_log("Latitud recibida: " . ($_POST['latitud'] ?? 'VACIO') . " -> " . ($latitud ?? 'NULL'));
                error_log("Longitud recibida: " . ($_POST['longitud'] ?? 'VACIO') . " -> " . ($longitud ?? 'NULL'));
                
                // Obtener foto actual si no se proporciona una nueva
                $foto = strClean($_POST['foto'] ?? '');
                if (empty($foto)) {
                    $casaActual = $this->model->getCasa($id);
                    $foto = $casaActual['foto'] ?? '';
                }
                
                // Actualizar usando query directa con TODOS los campos
                $resultado = $this->model->actualizarCasaConUbicacion(
                    $id, $estilo, $numero, $capacidad, $habitaciones_num, $camas, $banos,
                    $slug, $descripcion, $precio, $direccion, $latitud, $longitud, $estado, $foto, $video
                );
                
                error_log("Resultado actualizarCasaConUbicacion: " . ($resultado ? "OK" : "FALLO"));
                
                if ($resultado) {
                    $res = [
                        'tipo' => 'success', 
                        'msg' => 'CASA VACACIONAL ACTUALIZADA CORRECTAMENTE',
                        'id_casa' => $id
                    ];
                } else {
                    $res = ['tipo' => 'error', 'msg' => 'ERROR AL ACTUALIZAR LA CASA VACACIONAL'];
                }
            } else {
                $res = ['tipo' => 'warning', 'msg' => 'DATOS INCOMPLETOS O INVÁLIDOS. ID: ' . $id];
            }
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    
    // Función auxiliar para generar slug
    private function generarSlug($texto)
    {
        $slug = strtolower(trim($texto));
        $slug = preg_replace('/[áàäâã]/u', 'a', $slug);
        $slug = preg_replace('/[éèëê]/u', 'e', $slug);
        $slug = preg_replace('/[íìïî]/u', 'i', $slug);
        $slug = preg_replace('/[óòöôõ]/u', 'o', $slug);
        $slug = preg_replace('/[úùüû]/u', 'u', $slug);
        $slug = preg_replace('/[ñ]/u', 'n', $slug);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s_]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
    
    // Subir múltiples fotos a la galería
    public function subirFotosGaleria()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagenes']) && isset($_POST['id_habitacion'])) {
            $id_habitacion = intval($_POST['id_habitacion']);
            
            // Verificar cuántas fotos ya tiene
            $fotosActuales = $this->model->getFotosPropiedad($id_habitacion);
            $cantidadActual = count($fotosActuales);
            
            if ($cantidadActual >= 10) {
                echo json_encode(['tipo' => 'warning', 'msg' => 'Ya tienes el máximo de 10 fotos permitidas']);
                die();
            }
            
            $archivos = $_FILES['imagenes'];
            $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $fotosSubidas = [];
            $errores = [];
            
            // Calcular cuántas fotos se pueden subir
            $espacioDisponible = 10 - $cantidadActual;
            $cantidadArchivos = count($archivos['name']);
            
            if ($cantidadArchivos > $espacioDisponible) {
                echo json_encode([
                    'tipo' => 'warning', 
                    'msg' => "Solo puedes subir $espacioDisponible foto(s) más. Tienes $cantidadActual de 10."
                ]);
                die();
            }
            
            $directorioDestino = 'assets/principal/images/propiedades/';
            if (!file_exists($directorioDestino)) {
                mkdir($directorioDestino, 0777, true);
            }
            
            for ($i = 0; $i < $cantidadArchivos; $i++) {
                if ($archivos['error'][$i] !== UPLOAD_ERR_OK) {
                    $errores[] = "Error al subir: " . $archivos['name'][$i];
                    continue;
                }
                
                // Validar tipo
                if (!in_array($archivos['type'][$i], $tiposPermitidos)) {
                    $errores[] = "Formato no válido: " . $archivos['name'][$i];
                    continue;
                }
                
                // Validar tamaño (5MB)
                if ($archivos['size'][$i] > 5 * 1024 * 1024) {
                    $errores[] = "Archivo muy grande: " . $archivos['name'][$i];
                    continue;
                }
                
                // Generar nombre único
                $extension = pathinfo($archivos['name'][$i], PATHINFO_EXTENSION);
                $nombreArchivo = 'prop_' . $id_habitacion . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
                $rutaCompleta = $directorioDestino . $nombreArchivo;
                
                if (move_uploaded_file($archivos['tmp_name'][$i], $rutaCompleta)) {
                    // Guardar en BD
                    $orden = $cantidadActual + $i + 1;
                    $this->model->agregarFotoPropiedad($id_habitacion, $nombreArchivo, 0, $orden);
                    
                    $fotosSubidas[] = [
                        'nombre' => $nombreArchivo,
                        'ruta' => $rutaCompleta
                    ];
                } else {
                    $errores[] = "No se pudo guardar: " . $archivos['name'][$i];
                }
            }
            
            if (count($fotosSubidas) > 0) {
                $msg = count($fotosSubidas) . " foto(s) subida(s) correctamente";
                if (count($errores) > 0) {
                    $msg .= ". Errores: " . implode(", ", $errores);
                }
                echo json_encode([
                    'tipo' => 'success',
                    'msg' => $msg,
                    'fotos' => $fotosSubidas,
                    'total' => $cantidadActual + count($fotosSubidas)
                ]);
            } else {
                echo json_encode([
                    'tipo' => 'error',
                    'msg' => 'No se pudo subir ninguna foto. ' . implode(", ", $errores)
                ]);
            }
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Datos incompletos']);
        }
        die();
    }

    public function eliminarCasa()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = intval($_POST['id']);
            
            if ($id > 0) {
                // Verificar si la casa tiene reservas activas
                $reservasActivas = $this->model->verificarReservasActivasCasa($id);
                
                if ($reservasActivas > 0) {
                    $res = ['tipo' => 'warning', 'msg' => 'NO SE PUEDE ELIMINAR: LA CASA TIENE RESERVAS ACTIVAS'];
                } else {
                    $resultado = $this->model->eliminarCasa($id);
                    if ($resultado) {
                        $res = ['tipo' => 'success', 'msg' => 'CASA VACACIONAL ELIMINADA CORRECTAMENTE'];
                    } else {
                        $res = ['tipo' => 'error', 'msg' => 'ERROR AL ELIMINAR LA CASA VACACIONAL'];
                    }
                }
            } else {
                $res = ['tipo' => 'error', 'msg' => 'ID DE CASA INVÁLIDO'];
            }
            
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function getCasa()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['error' => 'No autorizado']);
            die();
        }

        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $casa = $this->model->getCasa($id);
            echo json_encode($casa, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['error' => 'ID no proporcionado']);
        }
        die();
    }

    public function subirImagenCasa()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagen'])) {
            $archivo = $_FILES['imagen'];
            
            // Validar que sea una imagen
            $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($archivo['type'], $tiposPermitidos)) {
                echo json_encode(['tipo' => 'error', 'msg' => 'Solo se permiten archivos de imagen (JPG, PNG, GIF, WEBP)']);
                die();
            }
            
            // Validar tamaño (máximo 5MB)
            if ($archivo['size'] > 5 * 1024 * 1024) {
                echo json_encode(['tipo' => 'error', 'msg' => 'El archivo es demasiado grande. Máximo 5MB']);
                die();
            }
            
            // Crear directorio si no existe
            $directorioDestino = 'assets/principal/images/';
            if (!file_exists($directorioDestino)) {
                mkdir($directorioDestino, 0777, true);
            }
            
            // Generar nombre único
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombreArchivo = 'casa_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            $rutaCompleta = $directorioDestino . $nombreArchivo;
            
            // Mover archivo
            if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
                echo json_encode([
                    'tipo' => 'success', 
                    'msg' => 'Imagen subida correctamente',
                    'nombre_archivo' => $nombreArchivo,
                    'ruta_completa' => $rutaCompleta
                ]);
            } else {
                echo json_encode(['tipo' => 'error', 'msg' => 'Error al subir la imagen']);
            }
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'No se recibió ningún archivo']);
        }
        die();
    }

    public function eliminarImagenCasa()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre_archivo'])) {
            $nombreArchivo = strClean($_POST['nombre_archivo']);
            $rutaArchivo = 'assets/principal/images/' . $nombreArchivo;
            
            if (file_exists($rutaArchivo)) {
                if (unlink($rutaArchivo)) {
                    echo json_encode(['tipo' => 'success', 'msg' => 'Imagen eliminada correctamente']);
                } else {
                    echo json_encode(['tipo' => 'error', 'msg' => 'Error al eliminar la imagen']);
                }
            } else {
                echo json_encode(['tipo' => 'warning', 'msg' => 'La imagen no existe']);
            }
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Nombre de archivo no proporcionado']);
        }
        die();
    }

    // ==================== GESTIÓN DE FOTOS MÚLTIPLES ====================
    
    public function getFotosPropiedad()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $fotos = $this->model->getFotosPropiedad($id);
            echo json_encode(['tipo' => 'success', 'fotos' => $fotos]);
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'ID no válido']);
        }
        die();
    }

    public function subirFotoPropiedad()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imagen']) && isset($_POST['id_habitacion'])) {
            $archivo = $_FILES['imagen'];
            $id_habitacion = intval($_POST['id_habitacion']);
            $es_principal = isset($_POST['es_principal']) ? intval($_POST['es_principal']) : 0;
            
            $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($archivo['type'], $tiposPermitidos)) {
                echo json_encode(['tipo' => 'error', 'msg' => 'Solo se permiten archivos de imagen']);
                die();
            }
            
            if ($archivo['size'] > 5 * 1024 * 1024) {
                echo json_encode(['tipo' => 'error', 'msg' => 'El archivo es demasiado grande. Máximo 5MB']);
                die();
            }
            
            $directorioDestino = 'assets/principal/images/propiedades/';
            if (!file_exists($directorioDestino)) {
                mkdir($directorioDestino, 0777, true);
            }
            
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
            $nombreArchivo = 'prop_' . $id_habitacion . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            $rutaCompleta = $directorioDestino . $nombreArchivo;
            
            if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
                // Guardar en base de datos
                $this->model->agregarFotoPropiedad($id_habitacion, $nombreArchivo, $es_principal);
                
                echo json_encode([
                    'tipo' => 'success',
                    'msg' => 'Imagen subida correctamente',
                    'nombre_archivo' => $nombreArchivo,
                    'ruta' => $rutaCompleta
                ]);
            } else {
                echo json_encode(['tipo' => 'error', 'msg' => 'Error al subir la imagen']);
            }
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Datos incompletos']);
        }
        die();
    }

    public function eliminarFotoPropiedad()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_foto'])) {
            $id_foto = intval($_POST['id_foto']);
            $foto = $this->model->eliminarFotoPropiedad($id_foto);
            
            if ($foto) {
                // Eliminar archivo físico
                $rutaArchivo = 'assets/principal/images/propiedades/' . $foto['url_imagen'];
                if (file_exists($rutaArchivo)) {
                    unlink($rutaArchivo);
                }
                echo json_encode(['tipo' => 'success', 'msg' => 'Foto eliminada correctamente']);
            } else {
                echo json_encode(['tipo' => 'error', 'msg' => 'Error al eliminar la foto']);
            }
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'ID de foto no proporcionado']);
        }
        die();
    }

    public function setFotoPrincipal()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_foto']) && isset($_POST['id_habitacion'])) {
            $id_foto = intval($_POST['id_foto']);
            $id_habitacion = intval($_POST['id_habitacion']);
            
            $this->model->setFotoPrincipal($id_foto, $id_habitacion);
            echo json_encode(['tipo' => 'success', 'msg' => 'Foto principal actualizada']);
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Datos incompletos']);
        }
        die();
    }

    // ==================== GESTIÓN DE AMENIDADES ====================
    
    public function getAmenidades()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        $amenidades = $this->model->getAmenidades();
        echo json_encode(['tipo' => 'success', 'amenidades' => $amenidades]);
        die();
    }

    public function getAmenidadesPropiedad()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $amenidades = $this->model->getAmenidadesPropiedad($id);
            echo json_encode(['tipo' => 'success', 'amenidades' => $amenidades]);
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'ID no válido']);
        }
        die();
    }

    public function guardarAmenidadesPropiedad()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_habitacion = isset($_POST['id_habitacion']) ? intval($_POST['id_habitacion']) : 0;
            $amenidades = isset($_POST['amenidades']) ? $_POST['amenidades'] : [];
            
            if ($id_habitacion > 0) {
                $this->model->setAmenidadesPropiedad($id_habitacion, $amenidades);
                echo json_encode(['tipo' => 'success', 'msg' => 'Amenidades guardadas correctamente']);
            } else {
                echo json_encode(['tipo' => 'error', 'msg' => 'ID de propiedad no válido']);
            }
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'Método no permitido']);
        }
        die();
    }

    // ==================== GESTIÓN EXTENDIDA DE PROPIEDADES ====================
    
    public function getCasaCompleta()
    {
        if (!isset($_SESSION['id_admin'])) {
            echo json_encode(['tipo' => 'error', 'msg' => 'No autorizado']);
            die();
        }

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $casa = $this->model->getCasaCompleta($id);
            if ($casa) {
                echo json_encode(['tipo' => 'success', 'casa' => $casa], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(['tipo' => 'error', 'msg' => 'Casa no encontrada']);
            }
        } else {
            echo json_encode(['tipo' => 'error', 'msg' => 'ID no válido']);
        }
        die();
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . RUTA_ADMIN);
        exit;
    }
}
