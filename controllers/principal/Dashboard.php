<?php
class Dashboard extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar que el usuario esté logueado
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: ' . RUTA_PRINCIPAL . 'login');
            exit;
        }
    }
    
    public function index()
    {
        // Si hay una reserva pendiente en sesión, redirigir directamente
        if (!empty($_SESSION['reserva'])) {
            header('Location: ' . RUTA_PRINCIPAL . 'reserva/pendiente');
            exit;
        }
        
        // Si no hay reserva pendiente, mostrar pantalla de bienvenida
        $data['title'] = 'Mi Cuenta';
        $data['nombre_usuario'] = $_SESSION['nombre_usuario'] ?? 'Huésped';
        $data['tiene_reserva'] = false;
        
        $this->views->getView('principal/clientes/index', $data);
    }
    
    public function salir()
    {
        session_destroy();
        header('Location: ' . RUTA_PRINCIPAL . 'login');
        exit;
    }
}
