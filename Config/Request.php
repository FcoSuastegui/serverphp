<?php namespace Config;

class Request 
{
    private $apicontroller;
    private $controller;
    private $method;
    private $argument;

    public function __construct()
    {
        if( isset($_GET['url'])){
            $ruta = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
            $ruta = explode("/", $ruta);
            $ruta = array_filter($ruta);
            $controller = strtolower(array_shift($ruta));
            switch ($controller):
                case 'api':
                    $this->apicontroller = $controller;
                    $this->controller = strtolower(array_shift($ruta));
                    $this->method     = strtolower(array_shift($ruta));
                    break;
                
                default:
                    $this->controller = $controller;
                    $this->method     = strtolower(array_shift($ruta));
                    break;
            endswitch;
                
            if( !$this->method ){
                $this->method = "index";
            }

            $this->argument  = $ruta;
        } else {
            $this->controller   = "sesion";
			$this->method       = "index";
        }
    }

    public function getApiController()
    {
        return $this->apicontroller;
    }

    public function getController(){
        return $this->controller;
    }

    public function getMethod () {
        return $this->method;
    }

    public function getArgument(){
        return $this->argument;
    }


}
