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
    <link href="../styles/search.css" rel="stylesheet" type="text/css" media="screen">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="./scripts/utils/bootstrap-maxlength.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
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

            <li class="text-light d-flex">
                <a class="nav-link" href="./index.php">
                    <i class="bi bi-house-door h3 text-light"></i>
                    <span class="ms-3">Accueil</span>
                </a>
            </li>

            <li class="text-light d-flex  fw-bold">
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

    <div class="container-sm content">
        <form autocomplete="off">
            <div class="autocomplete" id="form_div" style="width:300px;">
                <input id="search_tag" class="form-control" type="text" placeholder="Entrez un ou plusieurs tags">
            </div>
            <button id="search_button" class="btn btn-primary">Rechercher par tag</button>
        </form>

        <form autocomplete="off">
            <div class="autocomplete" id="form_div" style="width:300px;">
                <input id="search_handle" class="form-control" type="text" placeholder="Entrez un handle">
            </div>
            <button id="find_handle" class="btn btn-primary">Recherche par handle</button>

        </form>



        <button id="retour" class="btn btn-sm btn-primary">Retour</button>

        <div class="container" id="div_container">

        </div>

        <div class="container" id="div_reply">

        </div>
    </div>

    <script src="../scripts/search_tag.js"></script>
    <script src="../scripts/search_handle.js"></script>
    <script src="../scripts/utils/btn_tweet.js"></script>
</body>

</html>