<?php

require_once('../php/class/MyPDO.php');

class NewMess
{
    protected $id_user = null;
    protected $handle = null;
    protected $content = null;

    public function __construct()
    {
        if (isset($_POST['handle']) && $_POST['handle'] != null) {
            $this->handle = $_POST['handle'];
        }
        if (isset($_POST['content']) && $_POST['content'] != null) {
            $this->content = str_replace("'", "\'", $_POST['content']);
        }
        session_start();
        $this->id_user = $_SESSION['id'];
    }

    public function continue_conv()
    {
        $query = "SELECT id FROM user WHERE handle LIKE :handle";
        $statement = MyPDO::getInstance()->prepare($query);
        $statement->execute([':handle' => '@' . $this->handle]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $id_dest = $result['id'];
        $query2 = "INSERT INTO message (id_user_exp,id_user_dest,content, date) VALUES ('$this->id_user','$id_dest','$this->content', CURRENT_TIME())";
        MyPDO::getInstance()->query($query2);
        echo "success";
    }
}

$res = new NewMess;
$res->continue_conv();
