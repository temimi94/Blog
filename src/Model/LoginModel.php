<?php

namespace App\Model;


/**
 * Class LoginModel
 * @package App\Model
 */
class LoginModel extends MainModel
{
    /**
     * @param $user_email
     * @return mixed
     */
    public function getUser($user_email)
    {
        return $this->fetch('SELECT * FROM User WHERE User.email ="' . $user_email . '"');
    }

    /**
     * @param $id_user
     * @return mixed
     */
    public function getUserById($id_user){
        return $this->fetch('SELECT * FROM User WHERE User.id_user =' .$id_user);
    }

    /**
     * @param $pseudo
     * @param $email
     * @param $password
     * @return bool|\PDOStatement
     */
    public function createUser($pseudo, $email, $password)
    {
        $statement = 'INSERT INTO User (pseudo, email, password, date_register) VALUES (?, ?, ?, ?)';
        $date = date("Y-m-d H:i:s");

        $comment = array($pseudo, $email, $password, $date);
        return $this->execArray($statement, $comment);
    }

    /**
     * @param $token
     * @param $id_user
     * @return bool|\PDOStatement
     * @throws \Exception
     */
    public function createForgotToken($token, $id_user){
        $date = new \DateTime('+ 15 minutes');
        $date = $date->format('Y-m-d H:i:s');
        $statement = 'UPDATE User SET User.forgot_token =?, User.forgot_token_expiration =? WHERE User.id_user = ' . $id_user;
        $array = array($token, $date);
        return $this->execArray($statement, $array);
    }

    /**
     * @param $token
     * @param $id_user
     * @return bool|\PDOStatement
     * @throws \Exception
     */
    public function createAuthToken($token, $id_user){
        $date = new \DateTime('+ 1 weeks');
        $date = $date->format('Y-m-d H:i:s');
        $statement = 'UPDATE User SET User.auth_token=?, User.auth_token_expiration=? WHERE User.id_user=' . $id_user;
        $array = array($token, $date);
        return $this->execArray($statement, $array);
    }

    /**
     * @param $token
     * @return mixed
     */
    public function searchAuthToken($token){
        $statement = "SELECT * FROM User WHERE User.auth_token= '" .$token."'";
        return $this->fetch($statement);
    }

    /**
     * @param $new_password
     * @param $id_user
     * @return bool|\PDOStatement
     */
    public function changePassword($new_password, $id_user)
    {
        $statement = 'UPDATE User SET User.password =? WHERE User.id_user = ' . $id_user;
        $array = array($new_password);
        return $this->execArray($statement, $array);
    }

}