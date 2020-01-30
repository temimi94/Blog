<?php

namespace App\Model;


/**
 * Class UserModel
 * @package App\Model
 */
class UserModel extends MainModel
{
    /**
     * @param $user_id
     * @return array
     */
    public function getUserComment($user_id)
    {
        return $this->fetchAll('SELECT Comment.id_comment, Comment.id_comment, Comment.id_article,
        Comment.content, Comment.date, Comment.id_user, Article.title, User.pseudo, Comment.validate 
        FROM Comment INNER JOIN Article ON Comment.id_article = Article.id_article
        INNER JOIN User ON Comment.id_user = User.id_user WHERE Comment.id_user = ' . $user_id);
    }


    /**
     * @param $id_comment
     * @return bool|\PDOStatement
     */
    public function deleteComment($id_comment)
    {
        $statement = 'DELETE FROM Comment WHERE Comment.id_comment = ' . $id_comment;
        return $this->execArray($statement);
    }



    /**
     * @param $id_user
     * @return mixed
     */
    public function getUserPassword($id_user)
    {
        return $this->fetch('SELECT User.password FROM User WHERE User.id_user =' . $id_user);
    }

    /**
     * @param $new_password
     * @param $id_user
     * @return bool|\PDOStatement
     */
    public function changeUserPassword($new_password, $id_user)
    {
        $statement = 'UPDATE User SET User.password =? WHERE User.id_user = ' . $id_user;
        $array = array($new_password);
        return $this->execArray($statement, $array);
    }
}
