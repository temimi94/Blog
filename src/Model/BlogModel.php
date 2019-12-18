<?php


namespace App\Model;


require_once 'ConnectPDO.php';

class BlogModel extends MainModel
{

    private $db;


    public static function selectArticle($id_post)
    {
        $db = ConnectPDO::getPDO();
        $req = $db->prepare('SELECT Article.id_article, Article.title, Article.content, Article.date, Article.chapo, Article.date_update, User.pseudo FROM Article 
        INNER JOIN User ON Article.author_id = User.id_user
        WHERE id_article ='.$id_post);
        //var_dump($req);
        $req->execute();
        //var_dump($req);
        return $req = $req->fetch();
    }

    public static function selectId_article()
    {
        $db = ConnectPDO::getPDO();
        $req = $db->prepare('SELECT id_article FROM Article');
        $req->execute();
        return $req = $req->fetchAll();
    }

    public static function selectCommentByArticle($id_article){
        $db = ConnectPDO::getPDO(); /* Ajouter de quoi selectionner le pseudo du créateur du commentaire */
        $req = $db->prepare('SELECT Comment.content, Comment.id_user, Comment.date, Comment.date_update, User.pseudo FROM Comment
        INNER JOIN Article ON Article.id_article = Comment.id_article
        INNER JOIN User ON Comment.id_user = User.id_user
        WHERE Article.id_article = ' .$id_article);
        $req->execute();
        return $req = $req->fetchAll();
    }



}
