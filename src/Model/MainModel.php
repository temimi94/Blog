<?php


namespace App\Model;


class MainModel
{

    public function selectAllUser()
    {
        $database = ConnectPDO::getPDO()->prepare('SELECT * FROM User');
        $database->execute();
        return $req = $database->fetchAll();
    }

    public static function selectAuthorById($author_id)
    {
        $database = ConnectPDO::getPDO()->prepare('SELECT * FROM User WHERE id_user = ' . $author_id);
        $database->execute();
        return $req = $database->fetch();

    }

    public static function selectArticleWithAuthor()
    {
        $database = ConnectPDO::getPDO()->prepare('SELECT * FROM Article LEFT JOIN User ON Article.author_id = User.id_user');
        $database->execute();
        return $req = $database->fetch();
    }


    public function read($statement)
    {
        $req = ConnectPDO::getPDO()->prepare($statement);
        $req->execute();
        return $req->fetch();
    }

    public function readAll($statement)
    {
        $req = ConnectPDO::getPDO()->prepare($statement);
        $req->execute();
        return $req->fetchAll();
    }

    public function delete($statement)
    {
        $req = ConnectPDO::getPDO()->prepare($statement);
        $req->execute();
        return $req;
    }

    public function update($statement, $array = [])
    {
        $req = ConnectPDO::getPDO()->prepare($statement);
        $req->execute($array);
        return $req;
    }

    public function add($statement)
    {
        $req = ConnectPDO::getPDO()->prepare($statement);
        $req->execute();
        return $req;
    }


}