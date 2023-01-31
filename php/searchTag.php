<?php

require_once('../php/class/MyPDO.php');

class search_Tag
{
    protected $tags = null;
    protected $id_user = null;

    public function __construct()
    {
        if (isset($_POST['tags']) && $_POST['tags'] != null) {
            $this->tags = $_POST['tags'];
        }
        /* session_start();
        $this->id_user = $_SESSION['id']; */
        $this->id_user = 4;
    }

    public function search_tag()
    {
        $array_tags = explode(" ", $this->tags);

        $query = "SELECT T.id, T.id_user, T.content, T.published_date, T.id_tweet_reply, U.handle, P.username, IF(T.id_tweet_reply IS NOT NULL, (SELECT U2.handle FROM tweet as T2 INNER JOIN user AS U2 ON U2.id = T2.id_user WHERE T2.id = T.id_tweet_reply), NULL) AS 'handlereply'
        FROM tweet AS T INNER JOIN user AS U ON T.id_user = U.id
        
        INNER JOIN profile AS P ON P.id_user = U.id
        
        INNER JOIN tag AS T2 on T.id = T2.id_tweet
        
        WHERE tagname LIKE '" . $array_tags[0] . "'";

        if (count($array_tags) > 1) {
            for ($i = 1; $i < count($array_tags); $i++) {
                $query .= " AND id_tweet IN(SELECT id_tweet FROM tag WHERE tagname LIKE '" . $array_tags[$i] . "')";
            }
        }
        $query .= " ORDER BY published_date;";

        $statement = MyPDO::getInstance()->query($query);
        $result = $statement->fetchAll();
        $array = [];
        $reply = array();
        foreach ($result as $row) {
            //var_dump($row);
            $statement4 = MyPDO::getInstance()->query("SELECT tweet.id, tweet.id_user, tweet.content, tweet.published_date, tweet.id_tweet_reply, user.handle, profile.username FROM tweet INNER JOIN user ON tweet.id_user = user.id INNER JOIN profile ON profile.id_user = user.id WHERE id_tweet_reply =" . $row["id"]);
            $count4 = $statement4->rowCount();
            $result4 = $statement4->fetchAll();
            foreach ($result4 as $row2) {
                //var_dump($row2);
                $reply[] = $this->getTweetDetails($row2);
            }
            $array[] = $this->getTweetDetails($row, $count4);
        }
        $json = json_encode(array('data' => $array, 'replies' => $reply));

        echo $json;
    }
    public function getTweetDetails($row, $compteur = NULL)
    {
        //var_dump($row);
        $statement2 = MyPDO::getInstance()->query("SELECT * FROM tweet INNER JOIN favorite ON id_tweet = tweet.id WHERE id_tweet =" . $row["id"]);
        $result2 = $statement2->rowCount();
        $statement3 = MyPDO::getInstance()->query("SELECT * FROM tweet INNER JOIN retweet on id_tweet = tweet.id WHERE id_tweet =" . $row["id"]);
        $result3 = $statement3->rowCount();
        $date = [];
        $date[] = $row['published_date'];

        foreach ($date as $key => $value) {
            $date_du_jour = new DateTime(date("Y-m-d H:i:s"));
            $date_du_mess = new DateTime($value);
            $interval = $date_du_jour->diff($date_du_mess);
            switch (true) {
                case ($interval->y != 0):
                    $date[$key] = substr($value, 0, -8);
                    break;
                case ($interval->m != 0):
                    $date[$key] = substr($value, 0, -8);
                    break;
                case ($interval->d != 0):
                    $date[$key] = substr($value, 0, -8);
                    break;
                case ($interval->h != 0):
                    $date[$key] = "Il y a " . $interval->h . "h ";
                    break;
                case ($interval->i != 0):
                    $date[$key] = "Il y a " . $interval->i . "min ";
                    break;
                case ($interval->s != 0):
                    $date[$key] = "Il y a " . $interval->s . "s ";
                    break;
                default:
                    $date[$key] = "A l'instant ";
                    break;
            }
        }
        $tempArr = array(
            "id_tweet" => $row['id'],
            "name" => $row["username"],
            "handle" => $row["handle"],
            "content" => $row["content"],
            "date" => $date[0],
            "reply" => $compteur,
            "likes" => $result2,
            "retweet" => $result3,
            "id_tweet_reply" => $row["id_tweet_reply"],
        );
        if ($row['handlereply'] = NULL) {
            $tempArr["handle_reply"] = $row['handlereply'];
        } else {
            $tempArr["handle_reply"] = 'null';
        }
        $array[] = $tempArr;
        // var_dump($array);
        return $array;
    }
}


$res = new search_Tag;
$res->search_tag();
