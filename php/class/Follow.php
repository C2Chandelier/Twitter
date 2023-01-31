<?php

require_once('../class/MyPDO.php');

class Follow
{
    private static $instance = null;
    protected $id_user = null;
    protected $handle = null;
    public function __construct()
    {
        session_start();
        $this->id_user = $_SESSION['id'];
        if ($_POST['handle'] != null && $_POST['handle'] != "") {
            $this->handle = "@" . $_POST['handle'];
        }
    }
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Follow();
        }
        return self::$instance;
    }
    public function iFollow()
    {
        $query = "SELECT id FROM user WHERE handle LIKE '$this->handle'";
        $statement = MyPDO::getInstance()->query($query);
        $result = $statement->fetch();
        $id_follow = $result['id'];

        $query1 = "SELECT * FROM follow WHERE id_user = :id_user AND followed_id_user = :id_follow ;";
        $statement1 = MyPDO::getInstance()->prepare($query1);
        $statement1->execute([':id_user' => $this->id_user, ':id_follow' => $id_follow]);

        if ($statement1->rowCount() == 0) {
            $query2 = "INSERT INTO follow (id_user, followed_id_user) VALUES (:id_user, :id_follow);";
            $statement2 = MyPDO::getInstance()->prepare($query2);
            $statement2->execute([':id_user' => $this->id_user, ':id_follow' => $id_follow]);
            echo "ajout";
        } else {
            $query3 = "DELETE FROM follow WHERE id_user = :id_user AND followed_id_user = :id_follow;";
            $statement3 = MyPDO::getInstance()->prepare($query3);
            $statement3->execute([':id_user' => $this->id_user, ':id_follow' => $id_follow]);
            echo "supprime";
        }
    }
}
