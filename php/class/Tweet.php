<?php

require_once('MyPDO.php');
class Tweet
{
    private static $instance = null;
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Tweet();
        }
        return self::$instance;
    }
    public function loadTweets()
    {
        if (isset($_POST['handle'])) {
            $handle = '@' . $_POST['handle'];
            $filter = [':handle' => $handle];
            try {
                $usernameQuery = MyPDO::getInstance()->prepare('SELECT profile.username, user.id FROM profile INNER JOIN user ON user.Id = profile.id_user WHERE user.handle LIKE :handle');
                $usernameQuery->execute($filter);
                $username = $usernameQuery->fetch((PDO::FETCH_ASSOC));
                $filter = [':id' => $username['id']];
                $query =
                    "SELECT tweet.id, tweet.id_user, tweet.content, tweet.published_date, NULL as 'original_published_date', tweet.is_reply, tweet.id_tweet_reply, tweet.media, user.handle, profile.username, profile.avatar
            FROM tweet INNER JOIN user ON tweet.id_user = user.id 
            INNER JOIN profile ON profile.id_user = user.id 
            WHERE tweet.id_user = :id
                UNION 
                    SELECT tweet.id, tweet.id_user, tweet.content, retweet.retweet_date, tweet.published_date, tweet.is_reply, tweet.id_tweet_reply, tweet.media, user.handle, profile.username, profile.avatar
                    FROM tweet INNER JOIN retweet ON tweet.id = retweet.id_tweet 
                    INNER JOIN user ON tweet.id_user = user.id 
                    INNER JOIN profile ON profile.id_user = user.id 
                    WHERE id_user_retweet = :id
            ORDER BY published_date DESC";
                $statement = MyPDO::getInstance()->prepare($query);
                $statement->execute($filter);
                $result = $statement->fetchAll();
                $array = [];
                foreach ($result as $row) {
                    $replies = MyPDO::getInstance()->query("SELECT * FROM tweet WHERE id_tweet_reply =" . $row["id"]);
                    $repliesCount = $replies->rowCount();
                    $favorites = MyPDO::getInstance()->query("SELECT * FROM tweet INNER JOIN favorite ON favorite.id_tweet = tweet.id WHERE id_tweet =" . $row["id"] . " AND tweet.id_user = 1 ORDER BY tweet.published_date");
                    $favoritesCount = $favorites->rowCount();
                    $retweets = MyPDO::getInstance()->query("SELECT * FROM tweet INNER JOIN retweet ON retweet.id_tweet = tweet.id WHERE id_tweet =" . $row["id"]);
                    $retweetsCount = $retweets->rowCount();
                    $tweetCount = $statement->rowCount();

                    $array[] = array(
                        //infos tweet
                        "id" => $row["id"],
                        "content" => $row["content"],
                        "username" => $row["username"],
                        "handle" => $row["handle"],
                        "is_reply" => $row["is_reply"],
                        "published_date" => $row["published_date"],
                        "original_published_date" => $row["original_published_date"],
                        "avatar" => $row['avatar'],
                        "replies" => $repliesCount,
                        "likes" => $favoritesCount,
                        "retweets" => $retweetsCount,
                        "count" => $tweetCount,

                    );
                }
                echo json_encode(['username' => $username['username'], 'tweets' => $array]);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
}
