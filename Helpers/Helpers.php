<?php namespace Helpers;

class Helpers {

    private $log;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn; 
    }
    
    public function GetIP(){

        if (isset($_SERVER["HTTP_CLIENT_IP"])){
            return $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
            return $_SERVER["HTTP_X_FORWARDED"];
        }
        elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
            return $_SERVER["HTTP_FORWARDED_FOR"];
        }
        elseif (isset($_SERVER["HTTP_FORWARDED"])){
            return $_SERVER["HTTP_FORWARDED"];
        }
        else{
            return $_SERVER["REMOTE_ADDR"];
        }
    }

    public function getServer() {
        $server = isset($_SERVER['HTTPS']) ? "https://" : "http://";
        if( $_SERVER["SERVER_NAME"] == "localhost")
            $server .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "/";
        else
            $server .= $_SERVER["SERVER_NAME"] . "/";
        return $server;
    }

    /**dar formato de fecha dd/mm/yyyy => yyyy-mm-dd */
    public function FormatoFecha($fecha){
        $fecha = str_replace('/', '-', $fecha);
        $date = new \DateTime($fecha);
        return $date->format('Y-m-d');
    }

    public function GuardarImagen($datos) {
        $this->log = new Log($this->conn);
        $elemname = $datos['elemname'];
        if(empty($_FILES[$elemname])){  
            $resultados["status"] = false;
        }else{

            $id          = $datos['id'];
            $modulo      = $datos['modulo'];
            $targetPath  = $datos['folder'];
            $tabla       = $datos['tabla'];
            
            $imgName     = basename( $_FILES[$elemname]["name"] );
            $imgTmpName  =  $_FILES[$elemname]["tmp_name"];
            $ext         = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
            $newFilename = "img-" . $id . "." . $ext;
            $targetFile  = $targetPath . $newFilename;
            $resultados["imagen"] = strval($targetFile);

            //Hace falta validación del formato
            if($ext == 'png' || $ext == 'jpeg' || $ext == 'jpg' || $ext == 'gif'  || $ext == 'webp'){
                if(file_exists($targetFile)) { unlink($targetFile); }

                move_uploaded_file($imgTmpName, $targetFile);
                $data  = ['logo' => $targetFile];
                $where = ['id' => $id];
                $result = $this->conn->update($tabla, $data, $where);
                if( $result->status ) {
                    $resultados["status"]   = true;
                    $log                    = "Guardó la imagen $newFilename.";
                    $resultados["log"]      = $this->log->UserLog('c', $log);
                    $resultados["unixtime"] = time();
                } else {
                    $resultados["status"]    = false;
                    $resultados["error"]     = $result->error;
                    $resultados["erroruser"] = "Ocurrió un error al guardar la imagen $imgName.";
                }  

            } else {  
                $resultados["status"] = false; 
                $resultados["erroruser"] = "El archivo $imgName no es un formato válido de imagen.";
            }            
        }
        
        if(!$datos["nuevo"]){
            return print( json_encode($resultados) );
        } else {
            return ($resultados);
        }
    }

    public function SanitizarDatos($datos){
        foreach($datos as $dato) {
            $dato = addslashes(addslashes($dato));
        }  
        return $datos;
    }
}