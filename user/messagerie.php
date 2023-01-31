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
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link href="../styles/index.css" rel="stylesheet" type="text/css">
    <link href="../styles/messagerie.css" rel="stylesheet" type="text/css" media="screen">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
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

            <li class="text-light d-flex">
                <a class="nav-link" href="./index.php">
                    <i class="bi bi-house-door h3 text-light"></i>
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

            <li class="text-light d-flex  fw-bold">
                <a class="nav-link" href="./messagerie.php">
                    <i class="bi bi-envelope-fill h3 text-light"></i>
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

    <div class="container col-6 h-100">

        <form class="d-flex pb-2" autocomplete="off">
            <div class="autocomplete" id="form_div" style="width:300px;">
                <input class="form-control bg-dark rounded" id="search_handle" type="text" placeholder="Chercher un membre">
            </div>
            <button class="btn btn-primary ms-2 text-light" id="new_conv_button">Nouvelle conversation</button>
        </form>

        <div class="d-flex text-light">

            <div id="list_conv flex-grow-1">
                <hr>
                <ul class="p-0 mt-2" id="list">
                </ul>
            </div>


            <div id="conv">
                <ul class="p-2 border rounded" id="list_mess">

                </ul>
            </div>
        </div>
    </div>

</body>
<script src="../scripts/messagerie.js"></script>
<script src="../scripts/utils/btn_tweet.js"></script>

</html>