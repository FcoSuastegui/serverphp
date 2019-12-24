<?php namespace Config;

class Autoload 
{
    public static function run() {
        spl_autoload_register(function($class){
            $class = str_replace("\\", "/", $class) . ".php";
            if( !file_exists($class) )
                throw new \Exception("Error al cargar la clase: ". $class);
            require_once $class;
        });
    }
}

