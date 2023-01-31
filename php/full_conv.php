<?php

require_once('../php/class/MyPDO.php');

class MyConv
{
    protected $handle = null;
    protected $id_user = null;

    public function __construct()
    {
        if (isset($_POST['handle']) && $_POST['handle'] != null) {
            $this->handle = $_POST['handle'];
        }
        session_start();
        $this->id_user = $_SESSION['id'];
    }

    public function getConv()
    {
        $query = "SELECT id FROM user WHERE handle LIKE :handle";
        $statement = MyPDO::getInstance()->prepare($query);
        $statement->execute([':handle' => '@' . $this->handle]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $id_friend = $result['id'];
        $query2 = "SELECT * FROM message WHERE (id_user_exp = $this->id_user AND id_user_dest = $id_friend) OR (id_user_exp = $id_friend AND id_user_dest = $this->id_user) ORDER BY date;";
        $statement2 = MyPDO::getInstance()->query($query2);
        $order = array();
        $content = array();
        $date = array();

        foreach ($statement2 as $row) {
            if ($row['id_user_exp'] != $this->id_user) {
                $order[] = "left";
            } else {
                $order[] = "right";
            }
            $content[] = $row['content'];
            $date[] = $row['date'];
        }

        foreach ($date as $key => $value) {
            $date_du_jour = new DateTime(date("Y-m-d H:i:s"));
            $date_du_mess = new DateTime($value);
            $interval = $date_du_jour->diff($date_du_mess);
            switch (true) {
                case ($interval->d != 0):
                    $date[$key] = "Le " . substr($value, 8, 2) . "-" . substr($value, 5, 2) . "-" . substr($value, 0, 4);
                    break;
                case ($interval->h != 0):
                    $date[$key] = "Il y a " . $interval->h . "h";
                    break;
                case ($interval->i >= 2):
                    $date[$key] = "Il y a " . $interval->i . "min";
                    break;
                default:
                    $date[$key] = "";
                    break;
            }
        }

        $result = array();

        for ($compt2 = 0; $compt2 < $statement2->rowCount(); $compt2++) {
            $result[$compt2] = ['order' => $order[$compt2], 'content' => $content[$compt2], 'date' => $date[$compt2]];
        }

        echo json_encode($result);
    }
}

$mess = new MyConv;
$mess->getConv();
