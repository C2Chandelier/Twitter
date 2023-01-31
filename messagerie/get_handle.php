<?php

require_once('../php/class/MyPDO.php');

class getHandle
{
    protected $id_user = null;

    public function __construct(){
        /* session_start();
        $this->id_user = $_SESSION['id']; */
        $this->id_user =4;
    }

    public function handle(){
        $query = "SELECT handle FROM user WHERE id != $this->id_user AND is_active = 1;";
        $statement = MyPDO::getInstance()->query($query);
        $list = array();
        foreach($statement as $row){
            $list[] = $row['handle'];
        }
        echo json_encode($list);
    }
}

$res = new getHandle;
$res->handle();