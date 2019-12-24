<?php namespace Config;

class Token 
{
    private $post;
    private $session;

    public function __construct()
    {
        $this->post     = new Post();
        $this->session  = new Session();    
    }

    public function __destruct()
    {
        $this->post;
        $this->session;
    }

    public function validarToken()
    {
        return ($this->post->input('token') == $this->session->userdata('TOKEN')) ? true : false;
    }

    public function createToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
    
}
