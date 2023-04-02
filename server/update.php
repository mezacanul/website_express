<?php

require_once("credentials.php");
require_once("db-routines.php");

switch ($_POST["action"]) {
    case "updateTemplate":
        echo json_encode(updateTemplate($_POST["templateInfo"]));
        break;
    default:
      exit();
}

function updateTemplate($templateInfo){
    $id = $templateInfo['id'];
    $url = $templateInfo["url"];
    $preview = $templateInfo["preview"];

    $bgsSQL = ", bgs = '". ((count(json_decode($templateInfo["bgs"])) > 0) ? $templateInfo["bgs"] : "") ."'";
    $colorsSQL = ", colors = '". ((count(json_decode($templateInfo["colors"])) > 0) ? $templateInfo["colors"] : "") ."'";
    $taglinesSQL = ", taglines = '". ((count(json_decode($templateInfo["taglines"])) > 0) ? $templateInfo["taglines"] : "") ."'";

    $cssSQL = ", css = '". $templateInfo["css"] ."'";
    $jsSQL = ", js = '". $templateInfo["js"] ."'";
    
    $updateTemplate = "UPDATE templates SET url = '$url', preview = '$preview' $bgsSQL $colorsSQL $taglinesSQL $cssSQL $jsSQL  WHERE id = '$id'";
    $all = updateQuery($updateTemplate);
    return "ok";
}

?>