<?php


namespace App\Model;


/**
 * Class BlogModel
 * @package App\Model
 */
class BlogModel extends MainModel
{
    /**
     * @return array
     */
    public function selectAllArticle()
    {
        return $this->readAll('SELECT Article.idArticle, Article.title, Article.content, 
        Article.date, Article.chapo, Article.dateUpdate, 
        Article.authorId, User.idUser, User.pseudo, User.email 
        FROM Article INNER JOIN User ON Article.authorId = User.idUser
        WHERE Article.validated = 1
        ORDER BY CASE WHEN Article.dateUpdate > Article.date
        THEN Article.dateUpdate ELSE Article.date END DESC');
    }


    /**
     * @param $idArticle
     * @return mixed
     */
    public function selectArticle($idArticle)
    {
        return $this->read('SELECT Article.idArticle, Article.title, Article.content, Article.date, Article.chapo,
        Article.dateUpdate, User.pseudo FROM Article 
        INNER JOIN User ON Article.authorId = User.idUser
        WHERE idArticle =' . $idArticle);
    }

    /**
     * @return array
     */
    public function selectIdArticle()
    {
        return $this->readAll('SELECT idArticle FROM Article');
    }

    /**
     * @param $idArticle
     * @return array
     */
    public function selectCommentByArticle($idArticle)
    {
        return $this->readAll('SELECT Comment.content, Comment.idUser, Comment.date, User.pseudo FROM Comment
        INNER JOIN Article ON Article.idArticle = Comment.idArticle
        INNER JOIN User ON Comment.idUser = User.idUser
        WHERE Comment.validate = 1 AND Article.idArticle = ' . $idArticle);

    }

    /**
     * @param $article_title
     * @param $article_content
     * @param $article_chapo
     * @param $authorId
     * @return bool|\PDOStatement
     */
    public function createArticle($article_title, $article_content, $article_chapo, $authorId)
    {
        $statement = 'INSERT INTO Article (title, content, date, chapo, authorId) VALUES (?, ?, ?, ?, ?)';
        $date = date("Y-m-d H:i:s");
        $article = array($article_title, $article_content, $date, $article_chapo, $authorId);
        return $this->execArray($statement, $article);
    }

    /**
     * @param $idArticle
     * @param $content
     * @param $idUser
     * @return bool|\PDOStatement
     */
    public function createComment($idArticle, $content, $idUser)
    {
        $statement = 'INSERT INTO Comment (idArticle, content, date, idUser) VALUES (?, ?, ?, ?)';
        $date = date("Y-m-d H:i:s");

        $comment = array($idArticle, $content, $date, $idUser);
        return $this->execArray($statement, $comment);
    }

}
