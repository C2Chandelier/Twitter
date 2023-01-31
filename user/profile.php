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
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/409735ab78.js" crossorigin="anonymous"></script>
    <link href="../styles/profile.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>Tweet Académie</title>
</head>

<body class="bg-dark">

    <header class="position-fixed col-3 h-100" id="ad">

        <ul class="d-grid pe-3 h-100">

            <li>
                <button class="hover-btn">
                    <a href="index.php">
                        <i class="bi bi-twitter h2 text-light"></i>
                    </a>
                </button>
            </li>

            <li class="text-light d-flex">
                <a href="./logout.php" class="nav-elem nav-links theme">Se déconnecter</a>
            </li>

            <li class="text-light d-flex  fw-bold">
                <a class="nav-link" href="index.php">
                    <i class="bi bi-house-door h3 text-light"></i>
                    <span class="ms-3">Accueil</span>
                </a>
            </li>

            <li class="text-light d-flex">
                <a class="nav-link" href="search.php">
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
                <a class="nav-link" href="messagerie.php">
                    <i class="bi bi-envelope h3 text-light"></i>
                    <span class="ms-3">Messages</span>
                </a>
            </li>


            <li class="text-light d-flex">
                <a class="nav-link" href="profile.php">
                    <i class="bi bi-person-fill h3 text-light"></i>
                    <span class="ms-3">Profil</span>
                </a>
            </li>

            <li class="text-light d-flex">
                <a class="nav-link" href="#">
                    <i class="bi bi-three-dots h3 text-light"></i>
                    <span class="ms-3">Plus</span>
                </a>
            </li>

            <button class="btn btn-primary my-5 theme" id="btn_tweet">Tweeter</button>

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
            <a class="nav-link me-5 ms-2">
                <svg class="text-light" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
            </a>
            <div>
                <h3 class="text-light mb-0 name"></h3>
                <p class="text-secondary mb-0"><a class="text-secondary" id="count"></a>tweets</p>
            </div>
        </div>

        <div class="container-sm background mb-5">
            <div class="profile">
                <img class="pfp" id="profile-picture">
            </div>
            <div class="edit d-flex">
                <button class="btn btn-secondary text-light followedstate mx-3" id="followedstate"></button>
                <button type="button" class="btn btn-primary theme" id="edit-profile-modal" data-bs-toggle="modal" data-bs-target="#edit">
                    Éditer le profil
                </button>
                <button type="button" class="btn" id="follow-button" style="display:none; ">
                </button>
            </div>
            <!-- Button trigger modal -->


            <!-- Modal -->
            <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark border-secondary">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title text-light">Éditer le profil</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0 border-secondary container-fluid">
                            <form id="edit-profile-form" class=" d-flex flex-column align-items-center">
                                <div class="form-floating input background-edit mb-5">
                                    <div id="svg-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" data-target="banner-upload" class="text-light bi bi-camera-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                            <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="text-light bi bi-x" viewBox="0 0 16 16">
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </div>
                                    <div class="profile">
                                        <img class="pfp">
                                        <input class="d-none text-light" type="file" name='banner' id='banner-upload' accept="image/png, image/jpeg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" data-target="profile-picture-upload" class="text-light bi bi-camera-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                            <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1zm9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z" />
                                        </svg>
                                    </div>
                                    <small></small>
                                </div>
                                <input class="d-none text-light" type="file" name='profile-picture' id='profile-picture-upload' accept="image/png, image/jpeg">
                                <div class="form-floating my-4 input">
                                    <input type="text" class="form-control name" id="username" maxlength="50">
                                    <label for="signup-name">Username</label>
                                    <small></small>
                                </div>
                                <div class="form-floating mb-4 input">
                                    <input type="text" class="form-control handle" id="handle" maxlength="15">
                                    <label for="signup-name">Handle</label>
                                    <small></small>
                                </div>
                                <div class="form-floating mb-4 input">
                                    <textarea class="form-control biography" placeholder="Bio" id="biography" max="160"></textarea>
                                    <label for="signup-email">Bio</label>
                                    <small></small>
                                </div>
                                <div class="form-floating mb-4 input">
                                    <input type="text" class="form-control localisation" placeholder="Localisation" id="localisation" max="40">
                                    <label for="">Localisation</label>
                                </div>
                                <div class="form-floating mb-4 input">
                                    <input type="text" class="form-control link" id="link" placeholder="name@example.com" max="150">
                                    <label for="signup-email">Website</label>
                                    <small></small>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer border-secondary">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="edit-profile-button">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-sm pt-4 mt-5 profile-body">

            <h3 class="text-light mb-0 name" id="name"></h3>
            <h6 class="text-white-50 fw-normal handle"></h6>

            <p class="text-light text-break biography" id="biography"></p>

            <div class="d-flex">
                <p class="text-white-50 fw-light mb-0 localisation"><svg class="ms-2 me-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                    </svg>
                </p>

                <p class="text-white-50 fw-light mb-0"><svg class="ms-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
                        <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
                        <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
                    </svg>
                    <a class='link theme'></a>
                </p>
            </div>

            <p class="text-white-50 fw-light creation_date" id="creation_date"><svg class="mx-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar3" viewBox="0 0 16 16">
                    <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z" />
                    <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                </svg>A rejoint Twitter en
            </p>


            <div class="d-flex">
                <p class="text-secondary"><a class="text-light ms-1 text-decoration-none follow" id="followed" data-bs-toggle="modal" data-bs-target="#profile-followed"></a>abonnements</p>
                <p class="text-secondary ms-2"><a class="text-light ms-1 text-decoration-none followers" id="followers" data-bs-toggle="modal" data-bs-target="#profile-followers"></a>abonnés</p>
            </div>

            <nav>
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#tweets" type="button" role="tab" aria-controls="tweets" aria-selected="true">Tweets</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#replys" type="button" role="tab" aria-controls="reponse" aria-selected="false">Tweets et
                        réponses</button>
                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#medias" type="button" role="tab" aria-controls="medias" aria-selected="false">Médias</button>
                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#likes" type="button" role="tab" aria-controls="likes" aria-selected="false">J'aime</button>
                </div>
            </nav>
        </div>


        <div class="tab-content d-flex">

            <div class="tab-pane fade show active" role="tabpanel" id="tweets">

            </div>

            <div class="tab-pane fade" role="tabpanel" id="replys">

            </div>

        </div>

        <div class="aside position-fixed">
            <div class="input-group my-3">
                <span class="input-group-text" id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                    </svg></span>
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

            <div class="list-group border border-secondary">
                <h4 class="text-light m-3">Suggestions</h4>
                <a class="d-flex m-2" href="#">
                    <img class="pfp">


                    <div class="px-2 align-middle">
                        <div class="d-flex flex-column text-secondary">
                            <p class="text-light mb-0">R2J</p>
                            <p class="mb-0">@aretwojay</p>
                        </div>
                    </div>
                </a>

                <a class="d-flex m-2" href="#">
                    <img class="pfp">
                    <div class="px-2 align-middle">
                        <div class="d-flex flex-column text-secondary">
                            <p class="text-light mb-0">R2J</p>
                            <p class="mb-0">@aretwojay</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!-- Modal Followers -->
        <div class="modal fade" id="profile-followers" tabindex="-1" aria-labelledby="profile-followers" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Abonnés</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0 border-secondary container-fluid" id="followers-content">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Followed -->
        <div class="modal fade" id="profile-followed" tabindex="-1" aria-labelledby="profile-followed" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Abonnements</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0 border-secondary container-fluid" id="followed-content">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="../bootstrap/js/bootstrap.bundle.js"></script>
        <script src="../scripts/profile/loadProfile.js"></script>
        <script src="../scripts/profile/loadModals.js"></script>
        <script src="../scripts/profile/loadTweets.js"></script>
        <script src="../scripts/profile/editProfile.js"></script>
        <script src="../scripts/utils/theme.js"></script>
        <script src="../scripts/utils/btn_tweet.js"></script>

</body>

</html>