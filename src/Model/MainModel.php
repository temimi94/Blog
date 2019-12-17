<?php


namespace App\Model;


require_once 'ConnectPDO.php';

class MainModel{

    private $db;


    public function selectAllUser(){
        $db = ConnectPDO::getPDO();
        $req = $db->prepare('SELECT * FROM User');
        $req->execute();
        return $req = $req->fetchAll();
    }

    public static function selectAuthorById($author_id){
        $db = ConnectPDO::getPDO();
        $req = $db->prepare('SELECT * FROM User WHERE id_user = '.$author_id);
        $req->execute();
        return $req = $req->fetch();

    }

    public static function selectArticleWithAuthor(){
        $db = ConnectPDO::getPDO();
        $req = $db->prepare('SELECT * FROM Article LEFT JOIN User ON Article.author_id = User.id_user');
        $req->execute();
        return $req = $req->fetch();
    }



}