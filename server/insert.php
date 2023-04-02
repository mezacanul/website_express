<?php 
require_once("credentials.php");
require_once("db-routines.php");

switch ($_POST["action"]) {
    case "addTemplate":
        echo json_encode(addTemplate($_POST["preview"], $_POST["url"]));
        break;
    default:
      exit();
}

function addTemplate($preview, $url){
    $addTemplate = "INSERT INTO templates (preview, url, id) VALUES ('$preview', '$url', UUID())";
    $add = addQuery($addTemplate);
    
    return $add;
}

?>