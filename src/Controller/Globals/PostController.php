<?php

namespace App\Controller\Globals;


/**
 * Class PostController
 * @package App\Controller\Globals
 */
class PostController
{
    /**
     * @var mixed
     */
    private $post;

    /**
     * PostController constructor.
     */
    public function __construct()
    {
        $this->post = filter_input_array(INPUT_POST);
    }

    /**
     * @return mixed
     */
    public function getPostArray()
    {
        return $this->post;
    }

    /**
     * @param string $var
     * @return mixed
     */
    public function getPostVar(string $var)
    {
        return $this->post[$var];
    }

    /**
     * Verify if $_POST is empty or less than 5 character
     * @return bool|string
     */
    public function verifyPost(){
        $post = $this->getPostArray();
        foreach ($post as $k => $v ){
            if(empty($v)){
                $error = $k .' est vide.';
                $error = $this->errorPostMessage($error);
                return $error;
            }
            elseif(strlen(trim($v)) <= 5)
            {
                $error =  $k .' est trop court.';
                $error = $this->errorPostMessage($error);
                return $error;
            }
        }
        return true;
    }

    /**
     * @param $error
     * @return array
     * return a correct error message
     */

    private function errorPostMessage($error){ /* TODO Recréer cette fonction */
        $error = explode(' ', $error);
        switch($error[0]) {
            case 'name':
                $error[0] = 'Le nom';
                break;
            case 'content';
                $error[0] = 'Le contenu';
                break;
            case 'title';
                $error[0] = 'Le titre';
                break;
            case 'email';
                $error[0] = 'L\'email';
                break;
            case 'comment';
                $error[0] = 'Le commentaire';
                break;
            case 'chapo';
                $error[0] = 'L\'extrait';
                break;
            case 'pseudo';
                $error[0] = 'Le pseudo';
                break;
            case 'password';
                $error[0] = 'Le mot de passe';
                break;
        }
        $error = implode(' ', $error);
        return $error;
    }

}