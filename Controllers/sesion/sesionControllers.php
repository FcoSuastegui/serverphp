<?php namespace Controllers\sesion;

use Config\Controllers;

class sesionControllers extends Controllers {

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
        $this->iniciarSesion();
    }

    public function iniciarSesion()
    {
        $data = [
            'titulo' => 'Iniciar sesión',
        ];

        $this->view('login/index', $data);

    }

    public function login()
    {
        $repuesta = [
            $this->post->input('username'),
            $this->post->input('password')
        ];

        return print json_encode($repuesta);
    }


    public function logout()
    {
        
    }


}