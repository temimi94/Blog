<?php

namespace App\Model;


class LoginModel extends MainModel
{
    public function getUser($user_email){
        return $this->read('SELECT * FROM User WHERE User.email ="'.$user_email.'"');
    }

}