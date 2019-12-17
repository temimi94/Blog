<?php


namespace App\Model;


require_once 'ConnectPDO.php';

class BlogModel extends MainModel
{

    private $db;


    public static function selectArticle($id_post)
    {
        $db = ConnectPDO::getPDO();
        $req = $db->prepare('SELECT * FROM Article WHERE id_article = '.$id_post);
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
        $req = $db->prepare('SELECT * FROM Comment RIGHT JOIN Article ON Comment.id_article = Article.id_article WHERE Article.id_article =' . $id_article );
        $req->execute();
        return $req = $req->fetch();
    }



}
