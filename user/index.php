<?php
session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    header("location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/409735ab78.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link href="../styles/index.css" rel="stylesheet" type="text/css">
    <title>Tweet Académie</title>
</head>

<body class="bg-dark">

    <header class="position-fixed col-3 h-100">

        <ul class="d-grid pe-3 h-100">

            <li>
                <button class="hover-btn">
                    <a href="./index.php">
                        <i class="bi bi-twitter h2 text-light"></i>
                    </a>
                </button>
            </li>

            <li class="text-light d-flex  fw-bold">
                <a class="nav-link" href="./index.php">
                    <i class="bi bi-house-door-fill h3 text-light"></i>
                    <span class="ms-3">Accueil</span>
                </a>
            </li>

            <li class="text-light d-flex">
                <a class="nav-link" href="./search.php">
                    <i class="bi bi-search h3 text-light"></i>
                    <span class="ms-3">Explorer</span>
                </a>
            </li>

            <li class="text-light d-flex">
                <a class="nav-link" href="#">
                    <i class="bi bi-bell h3 text-light"></i>
                    <span class="ms-3">Notifications</span></a>
            </li>

            <li class="text-light d-flex">
                <a class="nav-link" href="./messagerie.php">
                    <i class="bi bi-envelope h3 text-light"></i>
                    <span class="ms-3">Messages</span>
                </a>
            </li>

            <li class="text-light d-flex">
                <a class="d-flex" href="./profile.php?handle=<?php echo substr($_SESSION['handle'], 1) ?>">
                    <i class="bi bi-person h3 text-light"></i>
                    <span class="ms-3">Profil</span>
                </a>
            </li>


            <li class="text-light d-flex">
                <a class="nav-link" href="#">
                    <i class="bi bi-three-dots h3 text-light"></i>
                    <span class="ms-3">Plus</span>
                </a>
            </li>

            <button class="btn btn-primary my-5" id="btn_tweet">Tweeter</button>

            <a class="d-flex" href="./profile.php?handle=<?php echo substr($_SESSION['handle'], 1) ?>">
                <img class="avi" style="content: url('../medias/profile_pictures/<?php echo $_SESSION['avatar']; ?>">
                <div class="px-2 align-middle">
                    <div class="d-flex flex-column text-secondary">
                        <p class="text-light mb-0"><?php echo $_SESSION['username']; ?></p>
                        <p class="mb-0"><?php echo $_SESSION['handle']; ?></p>
                    </div>
                </div>
            </a>

        </ul>

    </header>

    <div class="container-sm content border border-top-0 border-secondary">

        <div class="d-flex pe-5 py-1 sticky-top bg-dark transparent border-top border-secondary">
            <a onclick="goBack()" id="back" class="nav-link me-5 ms-2">
                <i class="bi bi-arrow-left h3 text-light"></i>
            </a>
            <div>
                <h4 id="heading" class="text-light my-2">Accueil</h4>
            </div>
        </div>

        <div id="make_tweet" class="input-group border-bottom border-secondary py-3">
            <form class="d-flex align-items-center mx-auto" method="post" action="../php/class/make_tweet.php" id="formulaire">
                <a class="d-flex" href="#">
                    <img class="pfp" style="content: url('../medias/profile_pictures/<?php echo $_SESSION['avatar']; ?>">
                </a>
                <input type="text" class="form-control bg-dark me-3 border-0 text-light" id="input_tweet" name="make_tweet" placeholder="Quoi de neuf ?" minlength="1" maxlength="140">
                <input class="btn btn-primary" type="submit" value="Tweeter" id="btn-tweet" disabled>
            </form>
        </div>

        <p id="countTweet" class="text-primary text-center mb-0 py-2 border-bottom border-secondary"></p>

        <div id="tweets">

        </div>

        <div id="tweet-select">
            <div id="selected">
                <div id="tweets-list">

                </div>

                <div id="make_reply" class="input-group border-bottom border-secondary py-3">
                    <form class="d-flex align-items-center mx-auto" method="post" action="../php/class/make_reply.php" id="formulaire">
                        <a class="d-flex" href="./profile.php?handle=<?php echo substr($_SESSION['handle'], 1) ?>">
                            <img class="avi" style="content: url('../medias/profile_pictures/<?php echo $_SESSION['avatar']; ?>">
                        </a>
                        <input type="text" class="form-control bg-dark me-3 border-0 text-light" id="input_reply" name="make_reply" placeholder="Tweeter votre réponse" minlength="1" maxlength="140">
                        <input type="hidden" id="tweet_reply" name="tweet_reply">
                        <input class="btn btn-primary" type="submit" value="Tweeter" id="btn-reply" disabled>
                    </form>
                </div>
            </div>

            <div id="replys"></div>

            <div class="modal fade" id="modal_retweet" tabindex="-1" role="dialog" aria-labelledby="modal_retweet_label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content bg-dark text-light">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_retweet_label">Retweeted by</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body" id="retweeted">
                            ...
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal_likes" tabindex="-1" role="dialog" aria-labelledby="modal_likes_label" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content bg-dark text-light">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal_likes_label">Liked by</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body" id="liked">
                            ...
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="aside position-fixed">
            <div class="input-group my-3">
                <span class="input-group-text" id="basic-addon1">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control" placeholder="Recherche Twitter" aria-label="Username" aria-describedby="basic-addon1">
            </div>

            <div class="list-group border border-secondary mb-3">
                <h4 class="text-light m-3">Tendances</h4>
                <a href="#" class="list-group-item list-group-item-action list-group-item-dark border-secondary" aria-current="true">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">#ONEPIECE1054</h6>
                    </div>
                    <small>56.6k tweets</small>
                </a>
                <a href="#" class="list-group-item list-group-item-action list-group-item-dark border-secondary" aria-current="true">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">#ONEPIECE1054</h6>
                    </div>
                    <small>56.6k tweets</small>
                </a>
            </div>

        </div>

        <script src="../bootstrap/js/bootstrap.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="../scripts/click_tweet.js"></script>
        <script src="../scripts/create_tweet.js"></script>
        <script src="../scripts/feed.js" defer></script>
        <script src="../scripts/show_stats.js"></script>
        <script src="../scripts/utils/btn_tweet.js"></script>
</body>

</html>