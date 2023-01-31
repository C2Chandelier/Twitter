<?php
require_once(__DIR__ . '/MyPDO.php');

class UserLogin
{
    private static $instance = null;
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserLogin();
        }
        return self::$instance;
    }
    public function logIn()
    {
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $password = hash('ripemd160', 'vive le projet tweet_academy' . $password);
            $baseQuery = 'SELECT user.id, user.handle, user.email, user.password, profile.avatar, profile.username, profile.theme FROM user INNER JOIN profile ON user.id = profile.id_user';
            session_start();
            if ($this->verifyEmail($login)) {
                $query = $baseQuery . ' WHERE email LIKE :login';
                $statement = MyPDO::getInstance()->prepare($query);
                $statement->execute([':login' => $login]);
                $result = $statement->fetch();
                if ($statement->rowCount() == 1) {
                    if ($password == $result['password']) {
                        $_SESSION['loggedIn'] = true;
                        $_SESSION['id'] = $result['id'];
                        $_SESSION['handle'] = $result['handle'];
                        $_SESSION['username'] = $result['username'];
                        $_SESSION['avatar'] = $result['avatar'];
                        $theme = json_decode($result['theme'], true);
                        $_SESSION['color'] = $theme['color'];
                        echo json_encode(['state' => 'success', 'id' => $_SESSION['id']]);
                    } else {
                        echo json_encode(['state' => 'incorrect password']);
                    }
                } else {
                    echo json_encode(['state' => 'incorrect email']);
                }
            } else {
                $query = $baseQuery . ' WHERE handle LIKE :login';
                $statement = MyPDO::getInstance()->prepare($query);
                $statement->execute([':login' => '@' . $login]);
                $result = $statement->fetch();
                if ($statement->rowCount() == 1) {
                    if ($password == $result['password']) {
                        $_SESSION['loggedIn'] = true;
                        $_SESSION['id'] = $result['id'];
                        $_SESSION['handle'] = $result['handle'];
                        $_SESSION['username'] = $result['username'];
                        $_SESSION['avatar'] = $result['avatar'];
                        $theme = json_decode($result['theme'], true);
                        $_SESSION['color'] = $theme['color'];
                        echo json_encode(['state' => 'success', 'id' => $_SESSION['id']]);
                    } else {
                        echo json_encode(['state' => 'incorrect password']);
                    }
                } else {
                    echo json_encode(['state' => 'incorrect username']);
                }
            }
        } else {
            echo json_encode(['state' => 'empty']);;
        }
    }

    public function verifyEmail($email)
    {
        $user   = '[a-zA-Z0-9_\-\.\+\^!#\$%&*+\/\=\?\`\|\{\}~\']+';
        $domain = '(?:(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.?)+';
        $ipv4   = '[0-9]{1,3}(\.[0-9]{1,3}){3}';
        $ipv6   = '[0-9a-fA-F]{1,4}(\:[0-9a-fA-F]{1,4}){7}';
        return preg_match("/^$user@($domain|(\[($ipv4|$ipv6)\]))$/", $email);
    }
}
