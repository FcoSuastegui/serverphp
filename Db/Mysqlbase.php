<?php namespace Db;

use Config\Env;

class Mysqlbase extends Env {



    // private $server    = 'localhost';
    // private $user      = 'midesa_develop';
    // private $password  = '2RaQwxkK3wefJplK7'; //'cGoX2xWt5Tfx0';
    // private $db        = 'midesa_develop';
    
    private $server;  //'localhost';
    private $user; //'desarrollodb';
    private $password;  //'cGoX2xWt5Tfx0'; //'cGoX2xWt5Tfx0';
    private $db; //'desarrollodb';
    
    private $conn;

    public function __construct()
    {
        parent::__construct();
        $this->server   = $this->env->DB_HOST;
        $this->user     = $this->env->DB_USERNAME;
        $this->password = $this->env->DB_PASSWORD;
        $this->db       = $this->env->DB_DATABASE;
        
        if( !empty($this->db)){
            try {
                $this->conn = new \mysqli($this->server, $this->user, $this->password,$this->db); 
                $this->conn->query("SET CHARACTER SET UTF8");
            } catch (\mysqli_sql_exception $e) {
                throw $e;
            }
        }

    }

    public function __destruct(){
        if( !empty($this->db)){
            $this->conn->kill($this->conn->thread_id);
            $this->conn->close();
        }
    }

    public function consult($query) {
        $res = new \stdClass();
        $res->status = false;

        if(!$result = $this->conn->query($query)){
            $res->error = $this->conn->error;
            return $res;
        }
        $this->conn->more_results();
        $res->status = true;
        $res->result = $result;
        return $res;
    }

    public function CallProcedure($procedure, $data = "" ) {
        $res = new \stdClass();
        $res->status = false;
        $consult = "CALL $procedure(";
        if (!empty($data) && is_array($data)) {
            $parameters = "";
            foreach ($data as $key) {
                if( is_int($key) || is_float($key)){
                    $parameters .= "$key,";
                } else {
                    $parameters .= "'$key',";
                }
            }
            
            $parameters = trim($parameters, ',');
            $consult .= $parameters;
        }
        $consult .= ");";
        if( !$result = $this->conn->query($consult)) {
            $res->error = $this->conn->error;
            return $res;
        }
        $this->conn->next_result();
        $res->status = true;
        $res->result = $result;
        return $res;
    }

    public function insert($table, $data) {
        // Se crea un objeto para retornar su valor
        $res = new \stdClass();
        $res->status = false;

        $columns = '';
        $field   = '';

        $consult = "INSERT INTO $table (";

        foreach ($data as $key => $value) {
            $columns .= "$key,";
            if( $value == 'NOW()') {
                $field .= "$value,";
            } else {
                $field .= "'$value',";
            }
        }

        $columns = trim($columns, ',');
        $field   = trim($field, ',');

        $consult .= "$columns ) VALUES (";
        $consult .= "$field ) ";

        if(!$this->conn->query($consult)) {
            $res->error = $this->conn->error;
            return $res;
        }
        
        $this->conn->more_results();
        $res->status = true;
        $res->insert_id = $this->conn->insert_id;
        return $res;

    }


    public function update($table, $data, $wheres ){
        $res = new \stdClass();
        $res->status = false;

        $columns = '';

        $consult = "UPDATE $table SET ";

        foreach ($data as $key => $value) {
            if( $value == 'NOW()') {
                $columns .= "$key = $value,";
            } else {
                $columns .= "$key = '$value',";
            }
        }
        unset($key, $value);
        
        $columns .= trim($columns, ',');
        $consult .= $columns;
        
        $conditions = " WHERE ";
        foreach ($wheres as $key => $value) {
            if($key == $this->endKey($wheres) ){
                $conditions .= "$key = '$value'";
            } else {
                $conditions .= "$key = '$value' AND ";
            }
        }
        unset($key, $value);

        $consult .= $conditions;

        if( !$this->conn->query($consult)) {
            $res->error = $this->conn->error;
            return $res;
        }
        
        $this->conn->more_results();
        $res->status = true;
        $res->affected_rows = $this->conn->affected_rows;
        return $res;

    }

    public function delete($table, $wheres ) {
        $res = new \stdClass();
        $res->status = false;

        $consult = "DELETE FROM $table";
        if( !empty($wheres) && is_array($wheres)){
            $conditions = " WHERE ";
            foreach ($wheres as $key => $value) {
                if($key == $this->endKey($wheres) ){
                    $conditions .= "$key = '$value'";
                } else {
                    $conditions .= "$key = '$value' AND ";
                }
            }
            unset($key, $value);
            $consult .= $conditions;
        }

        if( !$this->conn->query($consult)) {
            $res->error = $this->conn->error;
            return $res;
        }
        
        $this->conn->more_results();
        $res->status = true;
        $res->affected_rows = $this->conn->affected_rows;
        return $res;
    }

    private function endKey( $array ){
        end( $array );
        return key( $array );
    }

}
