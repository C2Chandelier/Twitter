<?php

require_once('MyPDO.php');

class Answer
{
    protected $id_tweet = null;
    protected $id_user = null;
    protected $content = null;
    private static $instance = null;


    public function __construct()
    {
        if (isset($_POST['id_tweet']) && $_POST['id_tweet'] != null) {
            $this->id_tweet = $_POST['id_tweet'];
        }
        if (isset($_POST['content']) && $_POST['content'] != null) {
            $this->content = $_POST['content'];
        }
        session_start();
        $this->id_user = $_SESSION['id'];
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Answer();
        }
        return self::$instance;
    }

    public function sendAnswer()
    {
        $query = 
        "INSERT INTO `tweet` (`id_user`, `content`, `is_reply`, `id_tweet_reply`) VALUES (:iduser, :content, '1', :idtweet);";
        $statement = MyPDO::getInstance()->prepare($query);
        $statement->execute([':iduser'=>$this->id_user, ':content'=>$this->content,':idtweet'=>$this->id_tweet]);
        $last_id = MyPDO::getInstance()->lastInsertId();



        $array = explode(" ",$this->content);
        $tags = array();
        foreach($array as $value){
            if($value[0] == "#"){
                $tags[] = $value;
            }
        }
        if(count($tags) > 0){
            foreach($tags as $tagvalue){
                $query3 = "INSERT INTO `tag` (`id_tweet`, `tagname`) VALUES (:id_tweet, :tagname);";
                $statement2 = MyPDO::getInstance()->prepare($query3);
                $statement2->execute([':id_tweet'=>$last_id, ':tagname'=>$tagvalue]);
            }
        }
        
    }
}