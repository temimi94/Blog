<?php


namespace App\Model;


class MainModel{

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


    public function read($statement){
        $req = ConnectPDO::getPDO()->prepare($statement);
        $req->execute();
        return $req->fetch();
    }

    public function readAll($statement){
        $req = ConnectPDO::getPDO()->prepare($statement);
        $req->execute();
        return $req->fetchAll();
    }




}