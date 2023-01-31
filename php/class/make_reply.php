<?php
include "MyPDO.php";
class reply_tweet
{
    public function get_content()
    {
        if (isset($_POST["make_reply"])) {
            session_start();
            $this->content = $_POST["make_reply"];
            $_SESSION["make_reply"] = $this->content;
        }
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["make_reply"])) {
            $this->content = $_SESSION["make_reply"];
        }
        return $this->content;
    }
    public function get_id()
    {
        if (isset($_POST["tweet_reply"])) {
            session_start();
            $this->id = $_POST["tweet_reply"];
            $_SESSION["tweet_reply"] = $this->id;
        }
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["tweet_reply"])) {
            $this->id = $_SESSION["tweet_reply"];
        }
        return $this->id;
    }
    public function create($value, $value_id)
    {
        if (strlen($value) != 0) {
            $replace = str_replace('"', '\"', $value);
            try {
                $filter = [':replace' => $replace, ':id' => $value_id, ':user' => $_SESSION['id']];
                $query = "INSERT INTO tweet (id_user,content,is_reply,id_tweet_reply) VALUES (:user,:replace,1,:id)";
                $statement = MyPDO::getInstance()->prepare($query);
                $result = $statement->execute($filter);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        header('Location: ../../user/index.php');
        exit;
    }
}
$exec = new reply_tweet();
$value = $exec->get_content();
$value_id = $exec->get_id();
$exec->create($value, $value_id);
