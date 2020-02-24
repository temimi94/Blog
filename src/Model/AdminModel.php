<?php

namespace App\Model;


/**
 * Class AdminModel
 * @package App\Model
 */
class AdminModel extends MainModel
{
    /**
     * @return array
     */
    public function selectAllUser()
    {
        $database = ConnectDB::getPDO()->prepare('SELECT * FROM User');
        $database->execute();
        $req = $database->fetchAll();
        return $req;
    }



    /**
     * @param $idArticle
     * @return bool|\PDOStatement
     */
    public function deleteArticle($idArticle)
    {
        $statement = 'DELETE FROM Comment WHERE idArticle =' .$idArticle . ';';
        $this->exec($statement);
        $statement = 'DELETE FROM Article WHERE idArticle =' . $idArticle . ';';
        return $this->exec($statement);
    }

    /**
     * @param $idArticle
     * @param $article_title
     * @param $article_chapo
     * @param $article_content
     * @return bool|\PDOStatement
     */
    public function updateArticle($idArticle, $article_title, $article_chapo, $article_content)
    {
        $date = date("Y-m-d H:i:s");

        $statement = 'UPDATE Article SET Article.title =?, Article.chapo =?,
        Article.content =?, Article.dateUpdate =? WHERE Article.idArticle = ' . $idArticle;
        $array = array($article_title, $article_chapo, $article_content, $date);
        return $this->execArray($statement, $array);
    }

    /**
     * @return array
     */
    public function getAllComment()
    {
        return $this->readAll('SELECT Comment.idComment, Comment.idComment, Comment.idArticle,
        Comment.content, Comment.date, Comment.idUser, Article.title, User.pseudo, Comment.validate 
        FROM Comment INNER JOIN Article ON Comment.idArticle = Article.idArticle
        INNER JOIN User ON Comment.idUser = User.idUser ');
    }

    /**
     * @param $idComment
     * @return bool|\PDOStatement
     */
    public function approveComment($idComment)
    {
        $statement = 'UPDATE Comment SET Comment.validate = 1 WHERE Comment.idComment = ' . $idComment;
        return $this->execArray($statement);
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
     * @return array
     */
    public function selectArticleAdmin()
    {
        return $this->readAll('SELECT Article.idArticle, Article.title, Article.content, 
        Article.date, Article.chapo, Article.dateUpdate, 
        Article.authorId, Article.validated, User.idUser, User.pseudo, User.email 
        FROM Article INNER JOIN User ON Article.authorId = User.idUser');
    }

    /**
     * @param $article_id
     * @return bool|\PDOStatement
     */
    public function approveArticle($article_id)
    {
        $statement = 'UPDATE Article SET Article.validated = 1 WHERE Article.idArticle = ' . $article_id;
        return $this->exec($statement);
    }

    /**
     * @param $idUser
     * @return mixed
     */
    public function getAdminPassword($idUser)
    {
        return $this->read('SELECT User.password FROM User WHERE User.idUser =' . $idUser);
    }

    /**
     * @param $new_password
     * @param $idUser
     * @return bool|\PDOStatement
     */
    public function changeAdminPassword($new_password, $idUser)
    {
        $statement = 'UPDATE User SET User.password =? WHERE User.idUser = ' . $idUser;
        $array = array($new_password);
        return $this->execArray($statement, $array);
    }

}
