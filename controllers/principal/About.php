<?php
class About extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        //$data = [get_nombre_dia(date('Y-m-d'))];
        //print_r($data);
        $data['title'] = 'Acerca de';
        $this->views->getView('principal/about/index', $data);
    }
}
