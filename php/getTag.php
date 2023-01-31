<?php

require_once('../php/class/MyPDO.php');

class getTag
{

    public function __construct()
    {
        $query = "SELECT tagname,COUNT(*) FROM tag GROUP BY tagname;";
        $statement = MyPDO::getInstance()->query($query);
        $list = array();
        foreach ($statement as $row) {
            $list[] = $row['tagname'];
        }
        echo json_encode($list);
    }
}

$res = new getTag;
