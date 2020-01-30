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
        $database = ConnectPDO::getPDO()->prepare('SELECT * FROM User');
        $database->execute();
        $req = $database->fetchAll();
        return $req;
    }



    /**
     * @param $id_article
     * @return bool|\PDOStatement
     */
    public function deleteArticle($id_article)
    {
        $statement = 'DELETE FROM Comment WHERE id_article =' .$id_article . ';';
        $this->exec($statement);
        $statement = 'DELETE FROM Article WHERE id_article =' . $id_article . ';';
        return $this->exec($statement);
    }

    /**
     * @param $id_article
     * @param $article_title
     * @param $article_chapo
     * @param $article_content
     * @return bool|\PDOStatement
     */
    public function updateArticle($id_article, $article_title, $article_chapo, $article_content)
    {
        $date = date("Y-m-d H:i:s");

        $statement = 'UPDATE Article SET Article.title =?, Article.chapo =?,
        Article.content =?, Article.date_update =? WHERE Article.id_article = ' . $id_article;
        $array = array($article_title, $article_chapo, $article_content, $date);
        return $this->execArray($statement, $array);
    }

    /**
     * @return array
     */
    public function getAllComment()
    {
        return $this->fetchAll('SELECT Comment.id_comment, Comment.id_comment, Comment.id_article,
        Comment.content, Comment.date, Comment.id_user, Article.title, User.pseudo, Comment.validate 
        FROM Comment INNER JOIN Article ON Comment.id_article = Article.id_article
        INNER JOIN User ON Comment.id_user = User.id_user ');
    }

    /**
     * @param $id_comment
     * @return bool|\PDOStatement
     */
    public function approveComment($id_comment)
    {
        $statement = 'UPDATE Comment SET Comment.validate = 1 WHERE Comment.id_comment = ' . $id_comment;
        return $this->execArray($statement);
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
     * @return array
     */
    public function selectArticleAdmin()
    {
        return $this->fetchAll('SELECT Article.id_article, Article.title, Article.content, 
        Article.date, Article.chapo, Article.date_update, 
        Article.author_id, Article.validated, User.id_user, User.pseudo, User.email 
        FROM Article INNER JOIN User ON Article.author_id = User.id_user');
    }

    /**
     * @param $article_id
     * @return bool|\PDOStatement
     */
    public function approveArticle($article_id)
    {
        $statement = 'UPDATE Article SET Article.validated = 1 WHERE Article.id_article = ' . $article_id;
        return $this->exec($statement);
    }

    /**
     * @param $id_user
     * @return mixed
     */
    public function getAdminPassword($id_user)
    {
        return $this->fetch('SELECT User.password FROM User WHERE User.id_user =' . $id_user);
    }

    /**
     * @param $new_password
     * @param $id_user
     * @return bool|\PDOStatement
     */
    public function changeAdminPassword($new_password, $id_user)
    {
        $statement = 'UPDATE User SET User.password =? WHERE User.id_user = ' . $id_user;
        $array = array($new_password);
        return $this->execArray($statement, $array);
    }

}
