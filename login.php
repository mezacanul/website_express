<?php 
$home_url = str_replace("login.php", "", $_SERVER["REQUEST_URI"]);
if( isset($_COOKIE["current_session"]) ){ header("Location: $home_url"); } 
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/login.css">

<body class="darkMode container">
    <head>
        <title>Website Express</title>
        <link rel="icon" type="image/x-icon" href="files/img/app/favicon.ico">
    </head>

    <div class="title row">
        <h1 class="dba pt-5">Website Express</h1>
    </div>

    <form id="login" action="#" class="row h-100 pt-5">
        <div class="col-md-4 mx-auto pt-5">
            <div class="mb-3 mt-3 sm">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
            </div>
            <div class="mb-3">
                <span class="has-float-label pass_show">
                <label for="pwd" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
            </div>
            <div class="form-check mb-3">
                <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="remember"> Remember me
                </label>
            </div>

            <div class="alert alert-success login-success fade-anim">
                <span><strong>Login Successful!</strong></span>
            </div>

            <div class="alert alert-danger login-fail fade-anim">
                <span></span>
            </div>
            <button type="button" class="btn btn-primary" onclick="login()" id="btn-login">Submit</button>
        </div> 
    </form>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="lib/jquery/jquery-3.6.0.min.js"></script>
<script src="js/login.js"></script>