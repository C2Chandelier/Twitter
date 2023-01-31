<?php

require_once('MyPDO.php');

class RTweet
{
    protected $id_tweet = null;
    protected $id_user = null;
    private static $instance = null;


    public function __construct()
    {
        if (isset($_POST['id_tweet']) && $_POST['id_tweet'] != null) {
            $this->id_tweet = $_POST['id_tweet'];
        }
        session_start();
        $this->id_user = $_SESSION['id'];

    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new RTweet();
        }
        return self::$instance;
    }

    public function retweet()
    {   
        try{
            $query = "SELECT * FROM retweet WHERE id_user_retweet = " . $this->id_user . " AND id_tweet =" . $this->id_tweet . ";";
            $statement = MyPDO::getInstance()->query($query);
            if ($statement->rowCount() == 0) {
                $query2 = "INSERT INTO `retweet` (`id_user_retweet`, `id_tweet`) VALUES ('" . $this->id_user . "', '" . $this->id_tweet . "');";
                MyPDO::getInstance()->query($query2);
            } else {
                $query3 = "DELETE FROM retweet WHERE id_user_retweet = " . $this->id_user . " AND id_tweet =" . $this->id_tweet . ";";
                MyPDO::getInstance()->query($query3);
            }
        echo 'success';
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}