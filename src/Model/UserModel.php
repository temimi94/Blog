<?php

namespace App\Model;


/**
 * Class UserModel
 * @package App\Model
 */
class UserModel extends MainModel
{
    /**
     * @param $userId
     * @return array
     */
    public function getUserComment($userId)
    {
        return $this->readAll('SELECT Comment.idComment, Comment.idComment, Comment.idArticle,
        Comment.content, Comment.date, Comment.idUser, Article.title, User.pseudo, Comment.validate 
        FROM Comment INNER JOIN Article ON Comment.idArticle = Article.idArticle
        INNER JOIN User ON Comment.idUser = User.idUser WHERE Comment.idUser = ' . $userId);
    }


    /**
     * @param $idComment
     * @return bool|\PDOStatement
     */
    public function deleteComment($idComment)
    {
        $statement = 'DELETE FROM Comment WHERE Comment.idComment = ' . $idComment;
        return $this->execArray($statement);
    }



    /**
     * @param $idUser
     * @return mixed
     */
    public function getUserPassword($idUser)
    {
        return $this->read('SELECT User.password FROM User WHERE User.idUser =' . $idUser);
    }

    /**
     * @param $new_password
     * @param $idUser
     * @return bool|\PDOStatement
     */
    public function changeUserPassword($new_password, $idUser)
    {
        $statement = 'UPDATE User SET User.password =? WHERE User.idUser = ' . $idUser;
        $array = array($new_password);
        return $this->execArray($statement, $array);
    }
}
