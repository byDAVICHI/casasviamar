<?php
class Views
{
    public function getView($vista, $data = "")
    {
        require 'views/' . $vista . '.php';
        /*
        if ($ruta == 'principal') {
        $vista = 'views/' . $vista . '.php';
        } else {
            $vista = 'views/' . $ruta . '/' . $vista . '.php';
        }
        require $vista;
        */
    }
}
