<?php
include "MyPDO.php";
class make_tweet
{
    public function get_input()
    {
        if (isset($_POST["make_tweet"])) {
            session_start();
            $this->tweet = $_POST["make_tweet"];
            $_SESSION["make_tweet"] = $this->tweet;
        }
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["make_tweet"])) {
            $this->tweet = $_SESSION["make_tweet"];
        }
        return $this->tweet;
    }
    public function create($value)
    {
        if (strlen($value) != 0) {
            $replace = str_replace('"', '\"', $value);
            MyPDO::getInstance()->query("INSERT INTO tweet (id_user,content) VALUES (1,\"$replace\")");
        }
        header('Location: ../../user/index.php');
        exit;
    }
}
$exec = new make_tweet();
$value = $exec->get_input();
$exec->create($value);
echo $value;
