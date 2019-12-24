<?php

namespace App\Model;


class AdminModel extends MainModel{

    public function deleteArticle($id_article){
        $statement = 'DELETE FROM Article WHERE id_article ="'.$id_article.'"';
        return $this->delete($statement);
    }

    public function updateArticle($id_article, $article_title, $article_chapo, $article_content){
        $date = date("Y-m-d H:i:s");

        $statement = 'UPDATE Article SET Article.title =?, Article.chapo =?,
        Article.content =?, Article.date_update =? WHERE Article.id_article = '.$id_article;
        $array = array($article_title, $article_chapo, $article_content, $date);
        return $this->update($statement, $array);
    }

    public function getAllComment(){
        return $this->readAll('SELECT Comment.id_comment, Comment.id_comment, Comment.id_article,
        Comment.content, Comment.date, Comment.id_user, Article.title, User.pseudo, Comment.validate 
        FROM Comment INNER JOIN Article ON Comment.id_article = Article.id_article
        INNER JOIN User ON Comment.id_user = User.id_user ');
    }

    public function approveComment($id_comment){
        $statement = 'UPDATE Comment SET Comment.validate = 1 WHERE Comment.id_comment = ' .$id_comment;
        return $this->update($statement);
    }

    public function deleteComment($id_comment){
        $statement = 'DELETE FROM Comment WHERE Comment.id_comment = '.$id_comment;
        return $this->update($statement);
    }

}

/**
$statement = 'UPDATE Article SET Article.title =' .$article_title. ', Article.chapo = '.$article_chapo.',
        Article.content = '.$article_content.'; Article.date_update ='.$date.' WHERE Article.id_article = '.$id_article;**/