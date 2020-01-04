<?php


namespace App\Model;


require_once 'ConnectPDO.php';

class ListBlogModel extends MainModel
{

    public function selectAllArticle()
    {
        return $this->readAll('SELECT Article.id_article, Article.title, Article.content, 
        Article.date, Article.chapo, Article.date_update, 
        Article.author_id, User.id_user, User.pseudo, User.email 
        FROM Article INNER JOIN User ON Article.author_id = User.id_user
        WHERE Article.validated = 1');
    }



}
