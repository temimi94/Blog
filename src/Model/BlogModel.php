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
        return $this->fetchAll('SELECT Article.id_article, Article.title, Article.content, 
        Article.date, Article.chapo, Article.date_update, 
        Article.author_id, User.id_user, User.pseudo, User.email 
        FROM Article INNER JOIN User ON Article.author_id = User.id_user
        WHERE Article.validated = 1
        ORDER BY CASE WHEN Article.date_update > Article.date
        THEN Article.date_update ELSE Article.date END DESC');
    }


    /**
     * @param $id_post
     * @return mixed
     */
    public function selectArticle($id_post)
    {
        return $this->fetch('SELECT Article.id_article, Article.title, Article.content, Article.date, Article.chapo,
        Article.date_update, User.pseudo FROM Article 
        INNER JOIN User ON Article.author_id = User.id_user
        WHERE id_article =' . $id_post);
    }

    /**
     * @return array
     */
    public function selectId_article()
    {
        return $this->fetchAll('SELECT id_article FROM Article');
    }

    /**
     * @param $id_article
     * @return array
     */
    public function selectCommentByArticle($id_article)
    {
        return $this->fetchAll('SELECT Comment.content, Comment.id_user, Comment.date, User.pseudo FROM Comment
        INNER JOIN Article ON Article.id_article = Comment.id_article
        INNER JOIN User ON Comment.id_user = User.id_user
        WHERE Comment.validate = 1 AND Article.id_article = ' . $id_article);

    }

    /**
     * @param $article_title
     * @param $article_content
     * @param $article_chapo
     * @param $author_id
     * @return bool|\PDOStatement
     */
    public function createArticle($article_title, $article_content, $article_chapo, $author_id)
    {
        $statement = 'INSERT INTO Article (title, content, date, chapo, author_id) VALUES (?, ?, ?, ?, ?)';
        $date = date("Y-m-d H:i:s");
        $article = array($article_title, $article_content, $date, $article_chapo, $author_id);
        return $this->execArray($statement, $article);
    }

    /**
     * @param $id_article
     * @param $content
     * @param $id_user
     * @return bool|\PDOStatement
     */
    public function createComment($id_article, $content, $id_user)
    {
        $statement = 'INSERT INTO Comment (id_article, content, date, id_user) VALUES (?, ?, ?, ?)';
        $date = date("Y-m-d H:i:s");

        $comment = array($id_article, $content, $date, $id_user);
        return $this->execArray($statement, $comment);
    }

}
