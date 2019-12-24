<?php
    
    // ini_set('xdebug.max_nesting_level', 9999);
    date_default_timezone_set('America/Mexico_City');
    setlocale(LC_TIME, "es_MX.UTF-8");
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT', dirname(__FILE__) . DS );
    
    require_once "Config/Autoload.php";
    
    Config\Autoload::run();
    Config\Router::run( new Config\Request() );


