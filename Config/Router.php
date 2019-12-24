<?php namespace Config;

use Config\Session;

class Router 
{
    
    public static function run(Request $request) {
        $controller =  $request->getController() . "Controllers";
        $method     = $request->getMethod();
        $argument   = $request->getArgument();
        
        if( $method == "index.php") $method = "index";

        $routes = [
            'sesion',
            'login',
            'acceso',
            'inicio'
        ];
        
        if (Session::validateSession() || in_array($request->getController(), $routes) ) {
           
            if(!empty($request->getApiController())) {
                $route = ROOT . "Api" . DS . $request->getController() . DS . $controller . ".php";
                if( file_exists($route) ){
                    require_once $route;
                    $class = "Api\\". $request->getController() ."\\".$controller;
                    $controller = new $class;
                    if( !isset($argument) )
                        call_user_func(array($controller, $method));
                    else
                        call_user_func_array(array($controller, $method), $argument);
                } else {
                    $controller = "error404Controllers";
                    $method     =  "index";
                    $route = ROOT . "Api" . DS . "error" . DS . "error404Controllers.php";
                    require_once $route;
                    $class = "Api\\". "error\\". $controller;
                    $controller = new $class;
                    call_user_func(array($controller, $method));
                }

            } else {
                $route = ROOT . "Controllers". DS . $request->getController() . DS . $controller . ".php";
                if( file_exists($route) ){
                    require_once $route ;
                    $class = "Controllers\\". $request->getController() ."\\".$controller;
                    $controller = new $class;
                    if( !isset($argument) ){
                        call_user_func(array($controller, $method));
                    } else {
                        call_user_func_array(array($controller, $method), $argument);
                    }
    
                } else {
                    $controller = "error404Controllers";
                    $method     =  "index";
                    $route = ROOT . "Controllers" . DS . "error" . DS . "error404Controllers.php";
                    require_once $route;
                    $class = "Controllers\\". "error\\". $controller;
                    $controller = new $class;
                    call_user_func(array($controller, $method));
                }   
            }
        } else {
            $error404 = ROOT . "Public/view/error404/error404.php";
            require($error404);
        }
    }
}
