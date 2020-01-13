<?php

namespace App\Model;


/**
 * Class UserModel
 * @package App\Model
 */
class UserModel extends MainModel
{

    /**
     * @param $id_article
     * @param $user_id
     * @return bool|\PDOStatement
     */
    public function deleteUserArticle($id_article, $user_id)
    {
        $statement = 'DELETE FROM Article WHERE id_article =' . $id_article . ' AND Article.author_id = ' . $user_id;
        return $this->exec($statement);
    }

    /**
     * @param $id_article
     * @param $article_title
     * @param $article_chapo
     * @param $article_content
     * @return bool|\PDOStatement
     */
    public function updateUserArticle($id_article, $article_title, $article_chapo, $article_content)
    {
        $date = date("Y-m-d H:i:s");

        $statement = 'UPDATE Article SET Article.title =?, Article.chapo =?,
        Article.content =?, Article.date_update =? WHERE Article.id_article = ' . $id_article;
        $array = array($article_title, $article_chapo, $article_content, $date);
        return $this->execArray($statement, $array);
    }

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
     * @param $id_article
     * @return bool|\PDOStatement
     */
    public function deleteCommentByArticle($id_article)
    {
        $statement = 'DELETE FROM Comment WHERE Comment.id_article =' . $id_article;
        return $this->execArray($statement);
    }

    /**
     * @param $user_id
     * @return array
     */
    public function selectArticleUser($user_id)
    {
        return $this->fetchAll('SELECT Article.id_article, Article.title, Article.content, 
        Article.date, Article.chapo, Article.date_update, 
        Article.author_id, Article.validated, User.id_user, User.pseudo, User.email 
        FROM Article INNER JOIN User ON Article.author_id = User.id_user WHERE Article.author_id =' . $user_id);
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
