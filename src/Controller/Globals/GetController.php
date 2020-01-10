<?php

namespace App\Controller\Globals;


class GetController
{

    private $get;

    public function __construct()
    {
        $def = array(
            'page' => FILTER_SANITIZE_SPECIAL_CHARS,
            'idarticle' => FILTER_SANITIZE_NUMBER_INT,
            'idblog' => FILTER_SANITIZE_NUMBER_INT,
            'idcomment' => FILTER_SANITIZE_NUMBER_INT,
            'token' => FILTER_SANITIZE_SPECIAL_CHARS,
            'iduser' => FILTER_SANITIZE_NUMBER_INT
        );
        $this->get = filter_input_array(INPUT_GET, $def);
    }

    public function getGetArray()
    {
        return $this->get;
    }

    public function getGetVar(string $var)
    {
        return $this->get[$var];
    }

}