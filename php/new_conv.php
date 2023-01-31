<?php

require_once('../php/class/MyPDO.php');

class NewConv
{
    protected $id_user = null;
    protected $handle = null;
    protected $content = null;

    public function __construct()
    {
        if (isset($_POST['handle']) && $_POST['handle'] != '') {
            $this->handle = $_POST['handle'];
        }
        if (isset($_POST['content']) && $_POST['content'] != '') {
            $this->content = $_POST['content'];
        }
        session_start();
        $this->id_user = $_SESSION['id'];
    }

    public function start_new_conv()
    {
        $query = "SELECT id FROM user WHERE handle LIKE '$this->handle';";
        $statement = MyPDO::getInstance()->query($query);
        $result = $statement->fetch();
        $id_dest = $result['id'];

        $query2 = "INSERT INTO message (id_user_exp,id_user_dest,content, date) VALUES ('$this->id_user','$id_dest','$this->content', CURRENT_TIME())";
        MyPDO::getInstance()->query($query2);
        echo "success";
    }
}

$res = new NewConv;
$res->start_new_conv();
