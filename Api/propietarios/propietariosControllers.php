<?php namespace Api\propietarios;

use Config\Controllers;

class propietariosControllers  extends Controllers {

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
            'index' => 'propietarios'

        ]);
    }

    public function datosGenerales()
    {
        $respuesta = ['status' => false ];

        $params = [$this->session->userdata('ID_USER')];
        $procedure = $this->conn->CallProcedure('PropietariosDatosGeneralesApp', $params);
        if( $procedure->status ) {
            $respuesta['status'] = true;
            while($rows = $procedure->result->fetch_object()){
                $respuesta['data'] = $rows;
            }

        } else {
            $respuesta['error'] = $procedure->error;
            $respuesta['erroruser'] = 'Error al consultar sus datos generales';
        }

        return print json_encode($respuesta);
    }

    public function datosEscrituras()
    {
        $respuesta = ['status' => false ];

        $params = [$this->session->userdata('ID_USER')];
        $procedure = $this->conn->CallProcedure('PropietariosDatosEscriturasApp', $params);
        if( $procedure->status ) {
            $respuesta['status'] = true;
            $respuesta['data'] = $procedure->result->fetch_object();

        } else {
            $respuesta['error'] = $procedure->error;
            $respuesta['erroruser'] = 'Error al consultar sus datos generales';
        }

        return print json_encode($respuesta);
    }

    public function saldos()
    {
        $respuesta = ['status' => false ];

        //datos
        $params = [$this->session->userdata('idEntidad')];

        $procedure = $this->conn->CallProcedure('SeleccionarPropietarioCobranza', $params);
        if( $procedure->status ) { 
            while ($rows = $procedure->result->fetch_object()) {
                $respuesta["data"][] = $rows;
            }

            $id = $this->session->userdata('idEntidad');
            $respuesta["saldos"] = $this->saldo($id);                 
        } else {
            $respuesta['error'] = $procedure->error;
            $respuesta['erroruser'] = 'Error al consultar sus datos generales';
        }

        $params = [$this->session->userdata('ID_USER')];
        $procedure = $this->conn->CallProcedure('PropietariosDatosSaldoApp', $params);
        if( $procedure->status ) {
            $respuesta['status'] = true;
            $respuesta['cuotas'] = $procedure->result->fetch_object();

        } else {
            $respuesta['error'] = $procedure->error;
            $respuesta['erroruser'] = 'Error al consultar sus cuotas';
        }

        return print json_encode($respuesta);
    }


    public function saldo($id = 0)
    {
        
        $resultados = [];
        $procedure =  $this->conn->CallProcedure('SaldosPropietarios', [$id]);
        if($procedure->status ){
            while ( $rows = $procedure->result->fetch_object() ) {
                array_push( $resultados, $rows);
            } 
        } else {
            $resultados['error'] = $procedure->error;
            $resultados['erroruser'] = 'Error al consultar sus saldos';
        }

        return $resultados;
    }


    public function editarperfil()
    {
        $respuesta = ['status' => false ];

        $params = [
            $this->session->userdata('ID_USER'), 
            $this->post->input('nombre_usuario'),
            $this->post->input('correo_usuario'),
        ];

        $procedure = $this->conn->CallProcedure('PropietariosEditarPerfilApp', $params);
        if( $procedure->status ) {
            $respuesta['status'] = true;
        } else {
            $respuesta['error'] = $procedure->error;
            $respuesta['erroruser'] = 'Error al consultar sus datos generales';
        }

        return print json_encode($respuesta);
    }

    public function cambiarImagen()
    {
        $respuesta = ['status' => false ];

        //Saco el id propietario
        $params = [$this->session->userdata('ID_USER')];
        $procedure = $this->conn->CallProcedure('UserConsultarIdPropietario', $params);
        if( $procedure->status ) {
            $respuesta['status'] = true;
            $idpropietario = $procedure->result->fetch_object()->id;

        } else {
            $respuesta['error'] = $procedure->error;
            $respuesta['erroruser'] = 'Error al consultar su id';
        }

        unset($procedure);

        //datos para guardar la imagen
        $targetPath  = "recursos/propietarios/imagenes/";
        $elemname    = "modimagensubida";
        $imgName     = basename( $_FILES[$elemname]["name"] );
        $imgTmpName  =  $_FILES[$elemname]["tmp_name"];
        $ext         = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
        $newFilename = "img-" . $idpropietario . "." . $ext;
        $targetFile  = $targetPath . $newFilename;
        $respuesta["imagen"] = strval($targetFile);
        $respuesta["unixtime"] = time();

        //Validacion de formato de imagen
        if ($ext == 'png' || $ext == 'jpeg' || $ext == 'jpg' || $ext == 'gif'  || $ext == 'webp'){
            if(file_exists($targetFile)) { unlink($targetFile); }

            move_uploaded_file($imgTmpName, $targetFile);

            $params = [
                $this->session->userdata('ID_USER'),
                $idpropietario,
                $targetFile
            ];

            $procedure = $this->conn->CallProcedure('PropietarioCambiarImagenApp', $params);

            if ( $procedure->status ) {
                    $respuesta['exito']  = 'Ha modificado la imagen';
                    $respuesta['status'] = true;
            } else {
                $respuesta['error'] = $procedure->error;
                $respuesta['erroruser'] = 'Error al guardar la imagen en la base de datos';
            }

        } else {
            $respuesta['error'] = 'El formato del arhivo no está permitido';
            $respuesta['erroruser'] = 'Solo se aceptan imagenes con formato jpg, png, webp y gif';
        }

        return print json_encode($respuesta);
    }
 
    public function cambiarContrasena()
    {
        $respuesta = ['status' => false ];

        $params = [
            $this->session->userdata('ID_USER'),
            $this->post->input('pass'),
            $this->post->input('new-pass')
        ];

        $procedure = $this->conn->CallProcedure('PropietarioCambiarContrasenaApp', $params);
        if( $procedure->status ) {
            $response = $procedure->result->fetch_object();

            if( $response->status ){
                $respuesta['exito']  = 'Ha modificado la contraseña correctamente';
                $respuesta['status'] = true;
            } else {
                $respuesta['erroruser'] = $response->text;
            }

        } else {
            $respuesta['error'] = $procedure->error;
            $respuesta['erroruser'] = 'Error al consultar sus datos generales';
        }

        return print json_encode($respuesta);
    }

    public function documentos() {       
        $respuesta = ['status' => false ];

        //datos
        $params = [$this->session->userdata('ID_USER')];
        $procedure = $this->conn->CallProcedure('UserConsultarIdPropietario', $params);
        if( $procedure->status ) {
            $respuesta['status'] = true;
            $idpropietario = $procedure->result->fetch_object()->id;

        } else {
            $respuesta['error'] = $procedure->error;
            $respuesta['erroruser'] = 'Error al consultar su id';
        }

        $params = [$idpropietario, $this->session->userdata('SISTEMA')];
        $procedure = $this->conn->CallProcedure('PropietariosConsultarDocumentos', $params);
        if( $procedure->status ) {
            $respuesta['status'] = true;
            while($rows = $procedure->result->fetch_object()){
                $respuesta['documentos'][] = $rows;
            }

        } else {
            $respuesta['error'] = $procedure->error;
            $respuesta['erroruser'] = 'Error al consultar sus documentos';
        }

        return print( json_encode($respuesta) );       
    }

    public function avisos() {       
        $respuesta = ['status' => false ];

        //datos
        $params = [$this->session->userdata('SISTEMA')];
        $procedure = $this->conn->CallProcedure('PublicacionesInicio', $params);
        if( $procedure->status ) {
            $respuesta['status'] = true;
            while($rows = $procedure->result->fetch_object()){
                $respuesta['data'][] = $rows;
            }


        } else {
            $respuesta['error'] = $procedure->error;
            $respuesta['erroruser'] = 'Error al consultar los avisos';
        }


        return print( json_encode($respuesta) );       
    }


}