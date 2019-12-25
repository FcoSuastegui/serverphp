<?php namespace Helpers;

use Config\Session;

class Log
{
    private $conn;
    private $helper;
    private $session;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->session = new Session();
    }
    
    public function UserLog( $accion = 'x', $log = false ) {
        $this->helper = new Helpers($this->conn);

        $resultados = ['ok' => false];
        if ( isset($log) && $log != false ){
            $userId          = $this->session->userdata("ID_USER");
            $userName        = $this->session->userdata("NAME");
            $date            = date("Y-m-d H:i:s");
            $userIp          = $this->helper->GetIP();
            $id_users_accion = Log::ObtenerTipoAccion($accion);

            $data = [
                'idUser' => $userId,
                'userName' => $userName,
                'createdDate' => $date,
                'actionDescription' => $log,
                'ip' => $userIp,
                'id_users_accion' => $id_users_accion,
            ];
            
            $result = $this->conn->insert('USERS_LOG_ACCIONES', $data);
            
            if ( $result->status ) {
                $resultados["ok"] = true;
                $resultados["message"] = "Correcto";
            } else {
                $resultados["ok"] = false;
                $resultados["message"] = "Nada para insertar";
            }
        }
        return $resultados;
    }
    
    /**obtiene del catalogo de acciones que tipo de accion realizo un usuario (CRUD) */
    public function ObtenerTipoAccion($accion){
        $id = 0;
        $result = $this->conn->consult("SELECT id FROM CATALOGO_USERS_ACCIONES WHERE clave='$accion';");
        if ( $result->status ) {
            $row = $result->result->fetch_object();             
            $id = $row->id; 
        }
        return $id;
    }
}
