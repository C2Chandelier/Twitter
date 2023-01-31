<?php
session_start();

if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] == true) {
    header("location: ./user/profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="./scripts/utils/bootstrap-maxlength.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style_index.css">
    <title>Tweet Academie</title>
</head>

<body class='bg-dark'>
    <section class="vh-100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 text-light">
                    <div class="px-5 ms-xl-4">
                        <i class="fas fa-crow fa-2x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
                        <span class="h1 fw-bold mb-0">Tweet Academie</span>
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center h-custom-2 px-5">
                        <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Happening Now</h3>
                        <h4 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Join us today</h4>
                        <div class="pt-1 mb-4">
                            <button class="btn btn-info btn-lg btn-block" type="button" id="login-modal-button" data-bs-toggle="modal" data-bs-target="#login">Login</button>
                        </div>
                        <div class="d-flex">
                            <hr> Or
                            <hr>
                        </div>
                        <div class="pt-1 mb-4">
                            <button class="btn btn-info btn-lg btn-block" type="button" id="signup-modal-button" data-bs-toggle="modal" data-bs-target="#signup">Sign up</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 px-0 d-none d-sm-block">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/img3.webp" alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
                </div>
            </div>
        </div>
    </section>
    <div class="modal" id='login' tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sign in to Tweet Academie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id='login-form1' class="d-flex flex-column align-items-center">
                        <div class="form-floating my-4 input">
                            <input type="text" class="form-control" id="login-used" placeholder="">
                            <label for="login-email">Email or username</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-dark btn-lg px-5" id="login-next-button" data-bs-toggle="modal" data-bs-target="#login-2">Next</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id='login-2' tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enter your password</h5>
                    <button type="button" class="btn btn-arrowleft" data-bs-toggle="modal" data-bs-target="#login">
                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9973 7.99833C15.9973 8.26348 15.892 8.51778 15.7045 8.70528C15.517 8.89277 15.2627 8.9981 14.9975 8.9981H3.41413L7.70717 13.2891C7.80013 13.3821 7.87386 13.4925 7.92417 13.6139C7.97448 13.7354 8.00037 13.8655 8.00037 13.997C8.00037 14.1284 7.97448 14.2586 7.92417 14.3801C7.87386 14.5015 7.80013 14.6119 7.70717 14.7048C7.61422 14.7978 7.50386 14.8715 7.38241 14.9218C7.26096 14.9721 7.13079 14.998 6.99933 14.998C6.86787 14.998 6.7377 14.9721 6.61625 14.9218C6.4948 14.8715 6.38444 14.7978 6.29149 14.7048L0.292829 8.70617C0.199724 8.6133 0.125854 8.50297 0.0754524 8.38151C0.0250508 8.26004 -0.000892639 8.12983 -0.000892639 7.99833C-0.000892639 7.86682 0.0250508 7.73661 0.0754524 7.61514C0.125854 7.49368 0.199724 7.38335 0.292829 7.29048L6.29149 1.29182C6.47922 1.10409 6.73384 0.998627 6.99933 0.998627C7.26482 0.998627 7.51944 1.10409 7.70717 1.29182C7.8949 1.47956 8.00037 1.73417 8.00037 1.99967C8.00037 2.26516 7.8949 2.51978 7.70717 2.70751L3.41413 6.99855H14.9975C15.2627 6.99855 15.517 7.10388 15.7045 7.29138C15.892 7.47887 15.9973 7.73317 15.9973 7.99833Z" fill="black" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="login-form2" class="d-flex flex-column align-items-center needs-validation" novalidate>
                        <div class="form-floating my-4 input">
                            <input type="email" class="form-control" id="login-login" value="" disabled required>
                            <label for="login-login" id='login-login-label'>Username/Email</label>
                        </div>
                        <div class="form-floating mb-4 input">
                            <input type="password" class="form-control" id="login-password" placeholder="*********" required>
                            <label for="login-password">Password</label>
                            <small><a href="">Forgot your password ?</a></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex flex-column">
                    <button class="btn btn-dark btn-lg px-5" id='login-button'>Log In</button>
                    <small>Don't have an account? <a href="">Sign up</a></small>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id='signup' tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create your account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="signup-form1" class="d-flex flex-column align-items-center">
                        <div class="form-floating my-4 input">
                            <input type="text" class="form-control" id="signup-name" placeholder="name@example.com" maxlength="50">
                            <label for="signup-name">Name</label>
                            <small></small>
                        </div>
                        <div class="form-floating mb-4 input">
                            <input type="text" class="form-control" id="signup-email" placeholder="name@example.com">
                            <label for="signup-email">Email</label>
                            <small></small>
                        </div>
                        <div class="form-floating mb-4 input">
                            <input type="date" class="form-control" id="signup-date" placeholder="name@example.com">
                            <label for="">Date</label>
                            <small></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-dark btn-lg px-5" id="signup-next-button">Next</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id='signup-2' tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create your account</h5>
                    <button type="button" class="btn btn-arrowleft" data-bs-toggle="modal" data-bs-target="#signup">
                        <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M15.9973 7.99833C15.9973 8.26348 15.892 8.51778 15.7045 8.70528C15.517 8.89277 15.2627 8.9981 14.9975 8.9981H3.41413L7.70717 13.2891C7.80013 13.3821 7.87386 13.4925 7.92417 13.6139C7.97448 13.7354 8.00037 13.8655 8.00037 13.997C8.00037 14.1284 7.97448 14.2586 7.92417 14.3801C7.87386 14.5015 7.80013 14.6119 7.70717 14.7048C7.61422 14.7978 7.50386 14.8715 7.38241 14.9218C7.26096 14.9721 7.13079 14.998 6.99933 14.998C6.86787 14.998 6.7377 14.9721 6.61625 14.9218C6.4948 14.8715 6.38444 14.7978 6.29149 14.7048L0.292829 8.70617C0.199724 8.6133 0.125854 8.50297 0.0754524 8.38151C0.0250508 8.26004 -0.000892639 8.12983 -0.000892639 7.99833C-0.000892639 7.86682 0.0250508 7.73661 0.0754524 7.61514C0.125854 7.49368 0.199724 7.38335 0.292829 7.29048L6.29149 1.29182C6.47922 1.10409 6.73384 0.998627 6.99933 0.998627C7.26482 0.998627 7.51944 1.10409 7.70717 1.29182C7.8949 1.47956 8.00037 1.73417 8.00037 1.99967C8.00037 2.26516 7.8949 2.51978 7.70717 2.70751L3.41413 6.99855H14.9975C15.2627 6.99855 15.517 7.10388 15.7045 7.29138C15.892 7.47887 15.9973 7.73317 15.9973 7.99833Z" fill="black" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="signup-form2" class="d-flex flex-column align-items-center">
                        <div class="form-floating mb-4 input">
                            <input type="password" class="form-control" id="signup-password" placeholder="*********">
                            <label for="signup-password">Password</label>
                            <small></small>
                        </div>
                        <div class="form-floating mb-4 input">
                            <input type="password" class="form-control" id="signup-confirm-password" placeholder="*********">
                            <label for="signup-confirm-password">Confirm Password</label>
                            <small></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-dark btn-lg px-5" id="signup-submit-button">Next</button>
                </div>
            </div>
        </div>
    </div>
    <script src="scripts/index/login.js"></script>
    <script src="scripts/index/signup.js"></script>
</body>

</html>