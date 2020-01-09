<?php

namespace App\Model;


class LoginModel extends MainModel
{
    public function getUser($user_email)
    {
        return $this->read('SELECT * FROM User WHERE User.email ="' . $user_email . '"');
    }

    public function createUser($pseudo, $email, $password)
    {
        $statement = 'INSERT INTO User (pseudo, email, password, date_register) VALUES (?, ?, ?, ?)';
        $date = date("Y-m-d H:i:s");

        $comment = array($pseudo, $email, $password, $date);
        return $this->update($statement, $comment);
    }

}