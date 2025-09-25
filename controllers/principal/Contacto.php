<?php
class Contacto extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        //$data = [get_nombre_dia(date('Y-m-d'))];
        //print_r($data);
        $data['title'] = 'Contacto';
        $this->views->getView('principal/contactos/index', $data);
    }
}
