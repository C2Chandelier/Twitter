<?php

require_once('MyPDO.php');

class Like
{
    protected $id_tweet = null;
    protected $id_user = null;
    private static $instance = null;

    public function __construct()
    {
        if (isset($_POST['id_tweet']) && $_POST['id_tweet'] != null) {
            $this->id_tweet = $_POST['id_tweet'];
        }
        /*         session_start();
        $this->id_user = $_SESSION['id']; */
        $this->id_user = '1';
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Like();
        }
        return self::$instance;
    }

    public function liked()
    {
        try {
            $query = "SELECT * FROM favorite WHERE id_user = " . $this->id_user . " AND id_tweet =" . $this->id_tweet . ";";
            $statement = MyPDO::getInstance()->query($query);
            if ($statement->rowCount() == 0) {
                $query2 = "INSERT INTO `favorite` (`id_user`, `id_tweet`) VALUES ('" . $this->id_user . "', '" . $this->id_tweet . "');";
                MyPDO::getInstance()->query($query2);
            } else {
                $query3 = "DELETE FROM favorite WHERE id_user = " . $this->id_user . " AND id_tweet =" . $this->id_tweet . ";";
                MyPDO::getInstance()->query($query3);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
