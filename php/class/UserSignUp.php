<?php
require_once(__DIR__ . '/MyPDO.php');

class UserSignUp
{
    private static $instance = null;
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserSignUp();
        }
        return self::$instance;
    }
    public function checkIfUserExists()
    {
        if (isset($_POST['email'])) {
            $sample = [':email' => $_POST['email']];
            $query = 'SELECT * FROM user WHERE email LIKE :email';
            $statement = MyPDO::getInstance()->prepare($query);
            $statement->execute($sample);
            if ($statement->rowCount() == 0) {
                echo 'continue';
            } else {
                echo 'already exists';
            }
        }
    }
    function verifyEmail($email)
    {
        return preg_match("/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $email);
    }
    function verifyPassword($password)
    {
        return preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $password);
    }
    function verifyConfirmPassword($password, $confirmpassword)
    {
        return $password === $confirmpassword;
    }
    function verifyForm($email, $password, $confirmpassword)
    {
        if ($this->verifyEmail($email) && $this->verifyPassword($password, $confirmpassword)) {
            return true;
        } else {
            return false;
        }
    }
    public function register()
    {
        if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['birthdate']) && isset($_POST['password']) && isset($_POST['confirm-password'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $birthdate = $_POST['birthdate'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm-password'];

            if ($this->verifyForm($email, $password, $confirmPassword)) {
                $handle = preg_replace("/(\W)+/", "", $name);
                $number = '';
                for ($i = 0; $i < 7; $i++) {
                    $number .= random_int(0, 9);
                }
                $handle .= $number;
                $password = hash('ripemd160', 'vive le projet tweet_academy' . $password);
                try {
                    $filter = [':handle' => "@" . $handle, ':email' => $email, 'birthdate' => $birthdate, ':password' => $password];
                    $query = 'INSERT INTO user (handle, birthdate, email, password) VALUES (:handle, :birthdate, :email, :password)';
                    $statement = MyPDO::getInstance()->prepare($query);
                    $result = $statement->execute($filter);
                    $get_id = MyPDO::getInstance()->lastInsertId();

                    $filter = [':id_user' => $get_id, ':username' => $name, ':avatar' => "default.png"];
                    $profile_query = 'INSERT INTO profile (id_user, username, avatar) VALUES (:id_user, :username, :avatar)';
                    $statement = MyPDO::getInstance()->prepare($profile_query);
                    $result = $statement->execute($filter);

                    $getInfo = MyPDO::getInstance()->query('SELECT user.handle, profile.username, profile.avatar FROM user INNER JOIN profile ON user.id = profile.id_user WHERE user.id =' . $get_id);
                    $info = $getInfo->fetch(PDO::FETCH_ASSOC);

                    session_start();
                    $_SESSION["loggedIn"] = true;
                    $_SESSION["id"] = $get_id;
                    $_SESSION["handle"] = $info['handle'];
                    $_SESSION["username"] = $info['username'];
                    $_SESSION["avatar"] = $info['avatar'];
                    echo 'success';
                } catch (PDOException $e) {
                    echo $e;
                }
            }
        }
    }
}
