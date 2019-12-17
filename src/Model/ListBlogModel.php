<?php


namespace App\Model;


require_once 'ConnectPDO.php';

class ListBlogModel
{

    private $db;


    public static function selectAllArticle()
    {
        $db = ConnectPDO::getPDO();
        $req = $db->prepare('SELECT * FROM Article');
        $req->execute();
        return $req = $req->fetchAll();
    }

    public static function selectAllArticleWithAuthor()
    {
        $db = ConnectPDO::getPDO();
        $req = $db->prepare('SELECT * FROM Article INNER JOIN User ON Article.author_id = User.id_user');
        $req->execute();
        return $req = $req->fetchAll();
    }


}
