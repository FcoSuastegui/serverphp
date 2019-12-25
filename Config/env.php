<?php namespace Config;

class Env 
{
    protected $env;

    public function __construct() {
        $archivo = ROOT . '.env';
        $this->env = (object) parse_ini_file($archivo, false);
    }
}
