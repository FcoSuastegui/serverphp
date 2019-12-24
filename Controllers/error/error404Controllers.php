<?php namespace Controllers\error;

class error404Controllers {

    public function index()
    {
        return print json_encode([
            'status' => false,
            'error' => 'Error, no se encontro la pàgina solicitada',
            'code' => 404

        ]);
    }
}