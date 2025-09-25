<?php
class Blog extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        //$data = [get_nombre_dia(date('Y-m-d'))];
        //print_r($data);
        $data['title'] = 'Blog';

        $this->views->getView('principal/blog/index', $data);
    }
}
