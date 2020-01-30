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
     * Return true if verif passed, false if null ou empty, else return the error message
     */
    public function verifyPost(){
        $post = $this->getPostArray();
        if($post == null OR empty($post)){
            return false;
        }
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
     * $error[0] is the name of the input
     */

    private function errorPostMessage($error){
        $error = explode(' ', $error);
        $array = array('name' => 'Le nom',
            'content' => 'Le contenu',
            'title' => 'Le titre',
            'email' => 'L\'email',
            'mail' => 'L\'email',
            'comment' => 'Le commentaire',
            'chapo' => 'L\'extrait',
            'pseudo' => 'Le pseudo',
            'password' => 'Le mot de passe',
            'password1' => 'Le mot de passe',
            'password2' => 'Le mot de passe',
            'oldpassword' => 'Le mot de passe');

        $error[0] = $array[$error[0]];
        $error = implode(' ',$error);

        return $error;
    }

}