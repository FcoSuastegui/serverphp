<?php namespace Config;

session_start();
class Session {

    public function __construct()
    {
    }

    public static function validateSession(){
        if( isset($_SESSION) && !empty($_SESSION))
            return true;
        else
            return false;
    }


    public function set_userdata($data = []){
        if ( !empty($data) && is_array($data) ) {
            foreach ($data as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
    }

    public function userdata($data){
        return isset($_SESSION[$data]) ? $_SESSION[$data] : '';
    }

    public function sess_destroy(){
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
                );
        }
        session_destroy();
    }


}