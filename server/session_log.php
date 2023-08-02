<?php 
require_once("credentials.php");
require_once("db-routines.php");
date_default_timezone_set("America/Mexico_City");

function addSessionLog($session_id, $user_id, $status, $expires){
    $date_time = date("Y-m-d H:i:s", substr(time(), 0, 10));
    $expires = $expires;
    $addLog = "INSERT INTO session_log (session_id, user_id, current_state, created, expires, closed) VALUES ('$session_id', '$user_id', '$status', '$date_time', $expires, 0)";
    $insertResult = addQuery($addLog);
    return $insertResult;
}

function closeSession($session_id, $state){
    $date_time = date("Y-m-d H:i:s", substr(time(), 0, 10));
    $closeSession = "UPDATE session_log SET current_state = '$state', closed = '$date_time' WHERE session_id = '$session_id'";
    $closeResult = updateQuery( $closeSession );
    return $closeResult;
}


?>