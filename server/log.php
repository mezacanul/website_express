<?php 
require_once("credentials.php");
require_once("db-routines.php");

switch ($_POST["log"]) {
    case 'demos':
        echo json_encode(addToDemosLog());
        break;
    default:
        break;
}

function addToDemosLog(){
    date_default_timezone_set("America/Mexico_City");

    $url = $_POST["url"];
    $corp = $_POST["corp"];
    $type = $_POST["type"];
    $template = $_POST["template"];
    $products = $_POST["products"];
    $action = $_POST["action"];
    $error = $_POST["error"];
    $ip = $_SERVER['REMOTE_ADDR'];
    $browser = $_SERVER['HTTP_USER_AGENT'];
    $time = date("Y-m-d H:i:s", substr(time(), 0, 10));

    
    $addDemosLog = "INSERT INTO log_demos (id, ip, browser, time, url, corp, type, template, products, action, error) VALUES (UUID(), '$ip', '$browser', '$time', '$url', '$corp', '$type', '$template', '$products', '$action', '$error')";
    $add = addQuery($addDemosLog);
    
    return $add;
}

?>