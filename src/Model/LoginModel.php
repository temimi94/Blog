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
        return $this->read('SELECT * FROM user WHERE user.email ="' . $user_email . '"');
    }

    /**
     * @param $idUser
     * @return mixed
     */
    public function getUserById($idUser){
        return $this->read('SELECT * FROM user WHERE user.idUser =' .$idUser);
    }

    /**
     * @param $pseudo
     * @param $email
     * @param $password
     * @return bool|\PDOStatement
     */
    public function createUser($pseudo, $email, $password)
    {
        $statement = 'INSERT INTO user (pseudo, email, password, dateRegister) VALUES (?, ?, ?, ?)';
        $date = date("Y-m-d H:i:s");

        $comment = array($pseudo, $email, $password, $date);
        return $this->execArray($statement, $comment);
    }

    /**
     * @param $token
     * @param $idUser
     * @return bool|\PDOStatement
     * @throws \Exception
     */
    public function createForgotToken($token, $idUser){
        $date = new \DateTime('+ 15 minutes');
        $date = $date->format('Y-m-d H:i:s');
        $statement = 'UPDATE User SET user.forgotToken =?, user.forgotTokenExpiration =? WHERE user.idUser = ' . $idUser;
        $array = array($token, $date);
        return $this->execArray($statement, $array);
    }

    /**
     * @param $token
     * @param $idUser
     * @return bool|\PDOStatement
     * @throws \Exception
     */
    public function createAuthToken($token, $idUser){
        $date = new \DateTime('+ 1 weeks');
        $date = $date->format('Y-m-d H:i:s');
        $statement = 'UPDATE user SET user.authToken=?, user.authTokenExpiration=? WHERE user.idUser=' . $idUser;
        $array = array($token, $date);
        return $this->execArray($statement, $array);
    }

    /**
     * @param $token
     * @return mixed
     */
    public function searchAuthToken($token){
        $statement = "SELECT * FROM user WHERE user.authToken= '" .$token."'";
        return $this->read($statement);
    }

    /**
     * @param $new_password
     * @param $idUser
     * @return bool|\PDOStatement
     */
    public function changePassword($new_password, $idUser)
    {
        $statement = 'UPDATE user SET user.password =? WHERE user.idUser = ' . $idUser;
        $array = array($new_password);
        return $this->execArray($statement, $array);
    }

}