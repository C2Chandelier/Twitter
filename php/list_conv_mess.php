<?php

require_once('../php/class/MyPDO.php');

class MyMessage
{
    protected $id_user = null;

    public function __construct()
    {
        session_start();
        $this->id_user = $_SESSION['id'];
    }

    public function getCouples()
    {
        $query = "SELECT * from message WHERE (id_user_exp = $this->id_user AND id_user_exp > id_user_dest) OR id_user_dest = $this->id_user AND id_user_dest < id_user_exp ORDER BY date DESC;";
        $statement = MyPDO::getInstance()->query($query);
        $id_exp_dest = array();
        $content = array();
        $date = array();
        $last_message = array();
        foreach ($statement as $row) {
            if ($row['id_user_exp'] != $this->id_user) {
                $id_exp_dest[] = $row['id_user_exp'];
            }
            if ($row['id_user_dest'] != $this->id_user) {
                $id_exp_dest[] = $row['id_user_dest'];
            }
            $content[] = $row['content'];
            $date[] = $row['date'];
        }
        $list = "";

        for ($compt = 0; $compt < count($id_exp_dest); $compt++) {
            $list .= $id_exp_dest[$compt] . ",";
        }
        $list = rtrim($list, ",");
        $query2 = "SELECT user.id, username,handle, avatar FROM user INNER JOIN profile ON user.id = profile.id_user WHERE user.id IN ($list) ORDER BY FIELD(user.id,$list);";
        $statement2 = MyPDO::getInstance()->query($query2);
        $username = array();
        $id = array();
        foreach ($statement2 as $row) {
            $id[] = $row['id'];
            $username[] = $row['username'];
            $handle[] = $row['handle'];
            $avatar[] = $row['avatar'];
        }

        $array_last_message = [];
        $array_last_date = [];
        foreach ($id as $value) {
            $sql_last_message = "SELECT * FROM message WHERE id_user_exp in($this->id_user, $value) and id_user_dest IN($value, $this->id_user) order by id desc limit 1";
            $last_message = MyPDO::getInstance()->query($sql_last_message);
            foreach ($last_message as $row) {
                $array_last_message[] = $row['content'];
                $array_last_date[] = $row['date'];
            }
        }

        foreach ($array_last_date as $key => $value) {
            $timezone = (new DateTimeZone('Europe/Paris'));
            $date_du_jour = new DateTime('now', $timezone);
            $date_du_mess = new DateTime($value);
            $date_du_jour->format('Y-m-d H:i:s');
            $interval = $date_du_jour->diff($date_du_mess);
            switch (true) {
                case ($interval->y != 0):
                    $array_last_date[$key] = "Il y a " . $interval->y . "ans";
                    break;
                case ($interval->m != 0):
                    $array_last_date[$key] = "Il y a " . $interval->m . "mois";
                    break;
                case ($interval->d != 0):
                    $array_last_date[$key] = "Il y a " . $interval->d . "j";
                    break;
                case ($interval->h != 0):
                    $array_last_date[$key] = "Il y a " . $interval->h . "h";
                    break;
                case ($interval->i != 0):
                    $array_last_date[$key] = "Il y a " . $interval->i . "min";
                    break;
                case ($interval->s != 0):
                    $array_last_date[$key] = "Il y a " . $interval->s . "s";
                    break;
                default:
                    $array_last_date[$key] = "A l'instant";
                    break;
            }
        }

        $result = array();

        for ($compt2 = 0; $compt2 < $statement2->rowCount(); $compt2++) {
            $result[$compt2] = [
                'username' => $username[$compt2],
                'handle' => $handle[$compt2],
                'message' => $content[$compt2],
                'avatar' => $avatar[$compt2],
                'date' => $array_last_date[$compt2],
                'last' => $array_last_message[$compt2]
            ];
        }

        echo json_encode($result);
    }
}

$mess = new MyMessage;
$mess->getCouples();
