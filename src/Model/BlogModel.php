<?php


namespace App\Model;




class BlogModel extends MainModel
{

    public function selectArticle($id_post)
    {
        return $this->read('SELECT Article.id_article, Article.title, Article.content, Article.date, Article.chapo,
        Article.date_update, User.pseudo FROM Article 
        INNER JOIN User ON Article.author_id = User.id_user
        WHERE id_article ='.$id_post);
    }

    public function selectId_article()
    {
        return $this->readAll('SELECT id_article FROM Article');
    }

    public function selectCommentByArticle($id_article){
        return $this->readAll('SELECT Comment.content, Comment.id_user, Comment.date, User.pseudo FROM Comment
        INNER JOIN Article ON Article.id_article = Comment.id_article
        INNER JOIN User ON Comment.id_user = User.id_user
        WHERE Article.id_article = ' .$id_article);

    }

    public function createArticle($article_title, $article_content, $article_chapo, $author_id){
        $statement = 'INSERT INTO Article (title, content, date, chapo, author_id) VALUES (?, ?, ?, ?, ?)';
        $date = date("Y-m-d H:i:s");
        if($author_id === 'Administrateur') $author_id = 1;
        elseif($author_id === 'Utilisateur') $author_id = 2;
        $article = array($article_title, $article_content, $date, $article_chapo, $author_id);
        return $this->add($statement, $article);
    }



}
