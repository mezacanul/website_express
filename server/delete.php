<?php

require_once("credentials.php");
require_once("db-routines.php");

function deleteTemplate($id){
    $deleteTemplate = "DELETE FROM templates WHERE id = '$id'";
    $all = deleteQuery($deleteTemplate);
    return "ok";
}

switch ($_POST["action"]) {
    case "deleteTemplate":
        echo json_encode(deleteTemplate($_POST["templateId"]));
        break;
    default:
      exit();
}

?>