<?php namespace Helpers;

use Config\Env;
use PHPMailer\PHPMailer;

class Correo extends Env
{
    public function __construct()
    {
        parent::__construct();
    }

    public function EnvioCorreo($asunto,$email,$usuario,$contenido){

        $mail = new PHPMailer();

        $mail->SetLanguage("es", "PHPMailer/idiomas/");//ruta de la libreria
        $mail->Subject = $asunto; //"Envio de Accesos a Midesarrollo.com.mx"
        $mail->Username = $this->env->DB_HOST;
        $mail->Password = $this->env->MAIL_PASSWORD;
        $mail->Host = $this->env->MAIL_HOST;
        $mail->Port = $this->env->MAIN_PORT;
        $mail->IsSMTP(); 
        $mail->SMTPDebug = $this->env->MAIL_SMTPDEBUG;  
        $mail->SMTPAuth = $this->env->MAIL_SMTPAUTH; 
        $mail->SMTPSecure = $this->env->MAIL_SMTPSECURE; 
        $mail->isHTML(true);
        $mail->SetFrom('soporte@midesarrollo.com.mx','Accesos');
        $mail->AddAddress($email, $usuario);
        $mail->MsgHTML($contenido);
        
        $mail->CharSet = $this->env->MAIL_CHARSET;

        if($mail->Send())
            $msj= "OK";// si el correo se envio regresar un estatu true
        else
            $msj= 'Error: '.$mail->ErrorInfo;
        return $msj;
    } 

    public function plantilla($plantilla = '')
    {
        if( empty($plantilla) ) return $plantilla;
        $plantilla = file_get_contents('./recursos/correo/plantilla/' . $plantilla. '.html');
        return $plantilla;
    }

}



?>