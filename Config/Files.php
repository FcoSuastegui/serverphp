<?php namespace Config;

class Files 
{
    private $upload_path;
    private $allowed_types;
    private $max_size;
    private $max_width;
    private $max_height;
    private $file_name;



    public function __construct()
    {
    
    }


    public function file(){
        $files = $_FILES;
        $files2 = [];
        foreach ($files as $input => $infoArr) {
            $filesByInput = [];
            foreach ($infoArr as $key => $valueArr) {
                if (is_array($valueArr)) { // file input "multiple"
                    foreach($valueArr as $i=>$value) {
                        $filesByInput[$i][$key] = $value;
                    }
                }
                else { // -> string, normal file input
                    $filesByInput[] = $infoArr;
                    break;
                }
            }
            $files2 = array_merge($files2,$filesByInput);
        }
        $files3 = [];
        foreach($files2 as $file) { // let's filter empty & errors
            if (!$file['error']) $files3[] = $file;
        }
        return $files3;
    }


    public function do_upload(array $config){
    
    }
    
}
