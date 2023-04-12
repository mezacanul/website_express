<?php 

// -- Current file name
// echo basename(__FILE__, '.php'); 

// -- Current URL from wich the request was made
$request_arr = explode("/", $_SERVER['PHP_SELF']);
$current_page = str_replace(".php", "", $request_arr[count($request_arr) -1]);

?>

<head>
    <title>Website Express</title>
    <link rel="icon" type="image/x-icon" href="files/img/app/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<div class="title">
    <h1 class="dba">Website Express</h1>
    <!-- <img src="files/img/app/arrows.png" alt=""> -->
</div>

<hr class="title-menu">
<div class="menu" >
    <a href="./">Home</a>
    <a href="https://shark-app-pmtlw.ondigitalocean.app" target="_blank">Products</a>
    <a href="templates.php">Templates</a>
    <a href="backgrounds.php">Backgrounds</a>
    <a href="colors.php">Colors</a>
    <a href="taglines.php">Taglines</a>
    <a href="types.php">Types</a>
    <a href="returnaddress.php">Return Address</a>
    <a href="appconfig.php">App Configuration</a>
    <a href="admin.php">Admin</a>
    <!-- <ul>
        <li>Prices</li>
        <li>Types</li>
        <li>Descriptor</li>
        <li>Price Ranges</li>
        <li>Return Address</li>
    </ul> -->
</div>
