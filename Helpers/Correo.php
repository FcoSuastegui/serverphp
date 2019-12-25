<?php namespace Helpers;

use PHPMailer\PHPMailer;

class Correo
{
    private $MAIL_USERNAME;
    private $MAIL_PASSWORD;
    private $MAIL_HOST;
    private $MAIL_PORT;
    private $MAIL_SMTPDEBUG;
    private $MAIL_SMTPAUTH;
    private $MAIL_SMTPSECURE;
    private $MAIL_CHARSET;

    public function __construct($username, $password,$host,$port,$smtpdebug,$smtpauth,$smtpsecure,$charset)
    {
        $this->MAIL_USERNAME = $username;
        $this->MAIL_PASSWORD = $password;
        $this->MAIL_HOST = $host;
        $this->MAIL_PORT = $port;
        $this->MAIL_SMTPDEBUG = $smtpdebug;
        $this->MAIL_SMTPAUTH = $smtpauth;
        $this->MAIL_SMTPSECURE = $smtpsecure;
        $this->MAIL_CHARSET = $charset;
    }

    public function EnvioCorreo($asunto,$email,$usuario,$contenido){

        $mail = new PHPMailer();

        $mail->SetLanguage("es", "PHPMailer/idiomas/");//ruta de la libreria
        $mail->Subject = $asunto; //"Envio de Accesos a Midesarrollo.com.mx"
        $mail->Username = $this->MAIL_USERNAME;
        $mail->Password = $this->MAIL_PASSWORD;
        $mail->Host = $this->MAIL_HOST;
        $mail->Port = $this->MAIL_PORT;
        $mail->IsSMTP(); 
        $mail->SMTPDebug = $this->MAIL_SMTPDEBUG;  
        $mail->SMTPAuth = $this->MAIL_SMTPAUTH; 
        $mail->SMTPSecure = $this->MAIL_SMTPSECURE; 
        $mail->isHTML(true);
        $mail->SetFrom('soporte@midesarrollo.com.mx','Accesos');
        $mail->AddAddress($email, $usuario);
        $mail->MsgHTML($contenido);
        
        $mail->CharSet = $this->MAIL_CHARSET;

        if($mail->Send())
            $msj= "OK";// si el correo se envio regresar un estatu true
        else
            $msj= 'Error: '.$mail->ErrorInfo;
        return $msj;
    } 

    public function plantilla($plantilla = '')
    {
        if( empty($plantilla) ) return $plantilla;
        $plantilla = file_get_contents('./Recursos/correo/plantilla/' . $plantilla. '.html');
        return $plantilla;
    }

}



?>