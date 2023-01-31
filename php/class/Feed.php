<?php

include 'MyPDO.php';
// (SELECT profile.username FROM profile INNER JOIN user ON user.id = profile.id_user INNER JOIN retweet ON retweet.id_user_retweet = user.id)
$query =
    "SELECT tweet.id, tweet.id_user, tweet.content, tweet.published_date,  NULL as 'original_published_date', NULL as 'retweeter', user.handle, profile.username, profile.avatar, tweet.id_tweet_reply 
    FROM tweet INNER JOIN user ON tweet.id_user = user.id 
    INNER JOIN profile ON profile.id_user = user.id
    WHERE tweet.id_user IN (SELECT user.id FROM user INNER JOIN follow ON user.id = follow.followed_id_user WHERE follow.id_user = 1) OR tweet.id_user = 1
        UNION
        SELECT tweet.id, tweet.id_user, tweet.content, retweet.retweet_date, tweet.published_date, retweet.id_user_retweet AS 'retweeter', user.handle, profile.username, profile.avatar, tweet.id_tweet_reply 
        FROM tweet INNER JOIN user ON tweet.id_user = user.id 
        INNER JOIN profile ON profile.id_user = user.id 
        INNER JOIN retweet ON retweet.id_tweet = tweet.id
        WHERE retweet.id_user_retweet IN (SELECT user.id FROM user INNER JOIN follow ON user.id = follow.followed_id_user WHERE follow.id_user = 1) OR retweet.id_user_retweet = 1
    ORDER BY published_date DESC";
$statement = MyPDO::getInstance()->query($query);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
$array = [];
foreach ($result as $row) {
    //var_dump($row['retweeter']) . PHP_EOL;
    $answers = MyPDO::getInstance()->query("SELECT * FROM tweet WHERE id_tweet_reply =" . $row["id"] . " ORDER BY published_date DESC");
    $answersCount = $answers->rowCount();
    $likes = MyPDO::getInstance()->query("SELECT * FROM tweet INNER JOIN favorite ON id_tweet = tweet.id WHERE id_tweet =" . $row["id"] . " ORDER BY published_date DESC");
    $likesCount = $likes->rowCount();
    $retweets = MyPDO::getInstance()->query("SELECT * FROM tweet INNER JOIN retweet on id_tweet = tweet.id WHERE id_tweet =" . $row["id"] . " ORDER BY published_date DESC");
    $retweetsCount = $retweets->rowCount();
    if ($row['retweeter'] != NULL) {
        $retweeterName = MyPDO::getInstance()->prepare('SELECT profile.username FROM profile INNER JOIN retweet ON profile.id_user = retweet.id_user_retweet WHERE profile.id_user = :retweeter');
        $retweeterName->execute([':retweeter' => $row['retweeter']]);
        $rtname = $retweeterName->fetch(PDO::FETCH_ASSOC);
    } else {
        $rtname['username'] = 'NULL';
        //var_dump($rtname);
    }
    // $retweeterName = MyPDO::getInstance()->prepare('SELECT profile.username FROM profile INNER JOIN retweet ON profile.id_user = retweet.id_user_retweet WHERE retweet.id LIKE :retweeter');
    //$retweeterName->execute([':retweeter' => $row['retweeter']]);
    // $rtname = $retweeterName->fetch(PDO::FETCH_ASSOC);
    $array[] = array(
        "content" => $row["content"],
        "id_tweet" => $row["id"],
        "id_tweet_reply" => $row["id_tweet_reply"],
        "username" => $row["username"],
        "handle" => $row["handle"],
        "published_date" => $row["published_date"],
        "original_published_date" => $row["original_published_date"],
        "retweeter_name" => $rtname["username"],
        "avatar" => $row["avatar"],
        "replys" => $answersCount,
        "likes" => $likesCount,
        "retweets" => $retweetsCount
    );
}
$json = json_encode($array);

echo $json;
