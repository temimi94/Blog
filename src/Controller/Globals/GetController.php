<?php

namespace App\Controller\Globals;


/**
 * Class GetController
 * @package App\Controller\Globals
 */
class GetController
{

    /**
     * @var mixed
     */
    private $get;

    /**
     * GetController constructor.
     */
    public function __construct()
    {
        $def = array(
            'page' => FILTER_SANITIZE_SPECIAL_CHARS,
            'idArticle' => FILTER_SANITIZE_NUMBER_INT,
            'idComment' => FILTER_SANITIZE_NUMBER_INT,
            'token' => FILTER_SANITIZE_SPECIAL_CHARS,
            'idUser' => FILTER_SANITIZE_NUMBER_INT
        );
        $this->get = filter_input_array(INPUT_GET, $def);
    }

    /**
     * @return mixed
     */
    public function getGetArray()
    {
        if(empty($this->get)){
            return false;
        }
        return $this->get;
    }

    /**
     * @param string $var
     * @return mixed
     */
    public function getGetVar(string $var)
    {
        if(empty($this->get[$var])){
            return false;
        }
        return $this->get[$var];
    }

}