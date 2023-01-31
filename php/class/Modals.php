<?php 

require_once("MyPDO.php");

class modals{
    public function modal_likes(){
        $id = $_POST["id"];
        $query = "SELECT handle,username,biography,avatar FROM favorite INNER JOIN user ON favorite.id_user = user.id INNER JOIN profile ON favorite.id_user = profile.id_user where id_tweet = $id ORDER BY favorite.id DESC";
        $statement = MyPDO::getInstance()->query($query);
        $result = $statement->fetchAll();
        $array = [];
        foreach($result as $row){
            $array [] = Array (
                "handle" => $row["handle"],
                "username" => $row["username"],
                "biography" => $row["biography"],
                "avatar" => $row["avatar"]
            );
        }
        $json = json_encode(array('data' => $array));

        echo $json;
    }
    public function modal_retweet(){
        $id = $_POST["id"];
        $query = "SELECT handle,username,biography, avatar FROM retweet INNER JOIN user ON retweet.id_user_retweet = user.id INNER JOIN profile ON retweet.id_user_retweet = profile.id_user WHERE id_tweet = $id";
        $statement = MyPDO::getInstance()->query($query);
        $result = $statement->fetchAll();
        $array = [];
        foreach($result as $row){
            $array [] = Array (
                "handle" => $row["handle"],
                "username" => $row["username"],
                "biography" => $row["biography"],
                "avatar" => $row["avatar"]
            );
        }
        $json = json_encode(array('data' => $array));

        echo $json;
    }
}