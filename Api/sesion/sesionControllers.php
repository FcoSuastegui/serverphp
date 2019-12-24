<?php namespace Api\sesion;

use Config\Controllers;

class sesionControllers  extends Controllers {

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index()
    {
        return print json_encode([
            'status' => true,
            'index' => 'Sesion'
        ]);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        return print json_encode([
            'status' => true,
        ]);   
    }
}