<?php namespace Config;

use Db\Mysqlbase;
use Helpers\Log;
use Helpers\Helpers;
use Helpers\Correo;

abstract class Controllers extends Env
{
    protected $helper;
    protected $session;
    protected $log;
    protected $post;
    protected $conn;
    protected $token;
    protected $files;
    protected $correo;
    private $data;
    private $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $this->conn     = new Mysqlbase($this->env->DB_HOST,$this->env->DB_USERNAME,$this->env->DB_PASSWORD,$this->env->DB_DATABASE);
        $this->helper   = new Helpers($this->conn);
        $this->session  = new Session();
        $this->log      = new Log($this->conn);
        $this->post     = new Post();
        $this->token    = new Token();
        $this->files    = new Files();
        $this->correo   = new Correo($this->env->MAIL_USERNAME, $this->env->MAIL_PASSWORD,$this->env->MAIL_HOST,$this->env->MAIL_PORT,
                                     $this->env->MAIL_SMTPDEBUG,$this->env->MAIL_SMTPAUTH,$this->env->MAIL_SMTPSECURE,$this->env->MAIL_CHARSET);
    }

    public function __destruct()
    {
        $this->helper;
        $this->session;
        $this->log;
        $this->post;
        $this->conn;
        $this->token;
        $this->files; 
        $this->correo;
    }

    protected function views($vista = '', $datos = ''){

        $html = file_get_contents("Public/view/$vista.php"); 
        $html = str_replace('<url>', $this->helper->getServer() . 'Public/', $html);

        print $html;

    }

    public function view($vista = '', $datos = '')
    {
        $this->baseUrl = $this->helper->getServer() . 'Public/';
        $this->data = !empty($datos) && is_array($datos) ? (object) $datos : '';
        
        if(file_exists("Public/view/$vista.php"))
            require("Public/view/$vista.php");
        else
            require("Public/view/error404/error.php");
    }


}
