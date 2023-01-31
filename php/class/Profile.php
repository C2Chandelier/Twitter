<?php

require_once('MyPDO.php');
class Profile
{
    private static $instance = null;
    public function __construct()
    {
        session_start();
        $this->userId = $_SESSION['id'];
        $this->color = $_SESSION['color'];
    }
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Profile();
        }
        return self::$instance;
    }
    public function loadProfile()
    {
        if (isset($_POST['handle'])) {
            $handle = '@' . $_POST['handle'];
            try {
                $filter = [':handle' => $handle];
                $getIdQuery = MyPDO::getInstance()->prepare('SELECT user.id FROM user WHERE user.handle LIKE :handle');
                $getIdQuery->execute($filter);
                $getOtherId = $getIdQuery->fetch(PDO::FETCH_ASSOC);
                $otherUserId = $getOtherId['id'];
                $filter = [':id' => $otherUserId];
                $userQuery = MyPDO::getInstance()->prepare('SELECT user.id, user.handle, user.creation_date, user.is_active, profile.username as "name", profile.biography, profile.localisation, profile.link, profile.avatar, profile.banner, profile.theme FROM user INNER JOIN profile ON user.id = profile.id_user WHERE user.id LIKE :id');
                $userQuery->execute($filter);
                $userInfo = $userQuery->fetch(PDO::FETCH_ASSOC);
                $followers = MyPDO::getInstance()->prepare("SELECT * FROM follow INNER JOIN user ON follow.followed_id_user = user.id WHERE user.id LIKE :id");
                $followers->execute($filter);
                $followed = MyPDO::getInstance()->prepare("SELECT * FROM follow INNER JOIN user ON follow.id_user = user.id WHERE user.id LIKE :id");
                $followed->execute($filter);
                $tweets = MyPDO::getInstance()->prepare("SELECT * from tweet INNER JOIN user ON tweet.id_user = user.id INNER JOIN profile ON profile.id_user = user.id WHERE user.id LIKE :id");
                $tweets->execute($filter);
                $followersCount = $followers->rowCount();
                $followedCount = $followed->rowCount();
                $tweetsCount = $tweets->rowCount();
                $data = ['sessionid' => $this->userId, 'userInfo' => $userInfo, 'followed' => $followedCount, 'followers' => $followersCount, 'tweetsCount' => $tweetsCount];
                if ($this->userId != $otherUserId) {
                    $filter = [':me' => $this->userId, ':otherUser' => $otherUserId];
                    $checkFollow = MyPDO::getInstance()->prepare('SELECT * FROM follow WHERE id_user LIKE :me AND followed_id_user LIKE :otherUser');
                    $checkFollow->execute($filter);
                    $countFollow = $checkFollow->rowCount();
                    if ($countFollow > 0) {
                        $data['followState'] = true;
                    } else {
                        $data['followState'] = false;
                    }
                    $checkFollowed = MyPDO::getInstance()->prepare('SELECT * FROM follow WHERE id_user LIKE :otherUser AND followed_id_user LIKE :me');
                    $checkFollowed->execute($filter);
                    $countFollowed = $checkFollowed->rowCount();
                    if ($countFollowed > 0) {
                        $data['followedState'] = true;
                    } else {
                        $data['followedState'] = false;
                    }
                }
                echo json_encode($data);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
    public function loadFollowers()
    {
        $followersQuery = 'SELECT user.id, profile.username, user.handle, profile.biography, profile.avatar FROM user INNER JOIN profile ON user.id = profile.id_user INNER JOIN follow ON user.id = follow.id_user WHERE follow.followed_id_user =' . $this->userId;
        $statement = MyPDO::getInstance()->query($followersQuery);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    }
    public function loadFollowed()
    {
        $followedQuery = 'SELECT user.id, profile.username, user.handle, profile.biography, profile.avatar FROM user INNER JOIN profile ON user.id=profile.id_user INNER JOIN follow ON profile.id_user = follow.followed_id_user WHERE follow.id_user =' . $this->userId;
        $statement = MyPDO::getInstance()->query($followedQuery);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    }
    public function editProfilePicture()
    {
        $uploads_dir = '../../../medias/profile_pictures';
        if ($_FILES["profile-picture"]["error"] == 0) {
            $tmp_name = $_FILES["profile-picture"]["tmp_name"];
            $path = $_FILES['profile-picture']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $name = $this->userId . '.' . $ext;
            if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
                $query = 'UPDATE profile SET avatar = :avatar WHERE id_user = :id';
                $statement = MyPDO::getInstance()->prepare($query);
                $statement->execute([':id' => $this->userId, ':avatar' => $name]);
                echo 'success';
            } else {
                echo 'fail';
            }
        }
    }

    public function editBanner()
    {
        $uploads_dir = '../../../medias/banners';
        if ($_FILES["banner"]["error"] == 0) {
            $tmp_name = $_FILES["banner"]["tmp_name"];
            $path = $_FILES['banner']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $name = $this->userId . '.' . $ext;
            if (!file_exists("$uploads_dir/$name")) {
                if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
                    $query = 'UPDATE profile SET banner = :banner WHERE id_user = :id';
                    $statement = MyPDO::getInstance()->prepare($query);
                    $statement->execute([':id' => $this->userId, ':banner' => $name]);
                    echo 'success';
                } else {
                    echo 'fail';
                }
            } else {
                if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
                    echo 'success';
                } else {
                    echo 'fail';
                }
            }
        }
    }
    public function editProfile()
    {
        if (isset($_POST['username']) && isset($_POST['handle']) && isset($_POST['biography']) && isset($_POST['localisation']) && isset($_POST['link'])) {
            $username = $_POST['username'];
            $handle = $_POST['handle'];
            $biography = $_POST['biography'];
            $localisation = $_POST['localisation'];
            $link = $_POST['link'];
            if (substr($handle, 0, 1) != '@') {
                $handle = '@' . $handle;
            }
            try {
                $statement = MyPDO::getInstance()->prepare('UPDATE user SET handle = :handle WHERE user.id = :id');
                $statement->execute([':handle' => $handle, ':id' => $this->user]);
                $query = 'UPDATE profile SET username = :username, localisation = :localisation, biography = :biography, link = :link WHERE profile.id_user = :id';
                $profileUpdate = MyPDO::getInstance()->prepare($query);
                $profileUpdate->execute([':id' => $this->userId, ':username' => $username, ':localisation' => $localisation, 'biography' => $biography, ':link' => $link]);
                echo 'success';
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
}
