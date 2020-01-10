<?php

namespace App\Model;


class LoginModel extends MainModel
{
    public function getUser($user_email)
    {
        return $this->read('SELECT * FROM User WHERE User.email ="' . $user_email . '"');
    }

    public function getUserById($id_user){
        return $this->read('SELECT * FROM User WHERE User.id_user =' .$id_user);
    }

    public function createUser($pseudo, $email, $password)
    {
        $statement = 'INSERT INTO User (pseudo, email, password, date_register) VALUES (?, ?, ?, ?)';
        $date = date("Y-m-d H:i:s");

        $comment = array($pseudo, $email, $password, $date);
        return $this->update($statement, $comment);
    }

    public function createToken($token, $id_user){
        $date = new \DateTime('+ 15 minutes');
        $date = $date->format('Y-m-d H:i:s');
        $statement = 'UPDATE User SET User.token =?, User.token_expiration =? WHERE User.id_user = ' . $id_user;
        $array = array($token, $date);
        return $this->update($statement, $array);
    }

    public function changePassword($new_password, $id_user)
    {
        $statement = 'UPDATE User SET User.password =? WHERE User.id_user = ' . $id_user;
        $array = array($new_password);
        return $this->update($statement, $array);
    }

}