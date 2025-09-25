<?php
class Dashboard extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }
    public function index()
    {
        $data['title'] = 'Perfil Cliente';
        $this->views->getView('principal/clientes/index', $data);
    }
    public function salir()
    {
        session_destroy();
        redirect(RUTA_PRINCIPAL . 'login');
    }
}
