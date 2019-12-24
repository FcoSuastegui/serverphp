<?php namespace Config;

class Post
{
    public function __construct()
    {
        
    }

    public function input($input)
    {
        return (isset($_POST[$input])) ? $_POST[$input]: "";
    }

    private function cleanSQL($input){
        $array_sentence = [
            "SELECT", "COPY", "DROP","DUMP",
            "OR", "%", "LIKE", "^", "[", "]",
            "\\", "¡","!", "?", "=", "&", "--",
            "*", "1=1", "=", ";", "FROM", "WHERE",
            "SET"
        ];
        foreach ($array_sentence as $key) {
            $input = str_replace($key, "", $input);
        }
        return $input;
    }
}
