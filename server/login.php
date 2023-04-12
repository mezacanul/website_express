<?php 
session_start();
require_once("credentials.php");
require_once("db-routines.php");
require_once("main-tools.php");
require_once("session_log.php");

switch ($_POST["action"]) {
    case 'login':
        login($_POST);
        break;
    case 'logout';
        logout("closed");
    default: break;
}

function login($user) {
    try {
        $email = $user["email"];
        $getUser = "SELECT * FROM user WHERE email = '$email'";
        $user_data = getQuery($getUser);
        if(count($user_data) < 1){
            sendStatus(401);
        } else {
            $user_data = $user_data[0];
            if($user_data["password"] != $user["password"] || $user_data["status"] != "active"){
                sendStatus(401);
            } else {
                $expires = (isset($user["remember"]) && $user["remember"] == "on") ? (time() + (86400 * 7)) : (time() + 43200);
                $send = [];
                $send["session_id"] = initializeCookies();
                $send["redirect"] = str_replace("server/login.php", "", ($_SERVER["REQUEST_URI"]));
                // -- TO DO: Close all active sessions with same user_id
                addSessionLog($send["session_id"], $user_data["id"], "active", $expires);
                initializeSession($send["session_id"]);
                sendStatus(200, $send);
            }
        }   
    } catch (\Throwable $th) {
        sendStatus(400, [], $th);
    }
}

function initializeCookies(){
    session_regenerate_id();
    $session_id = session_id();
    
    setcookie("current_session", $session_id, (time() + (86400 * 7)), "/");
    return $session_id;
}

function initializeSession($session_id){
    $getUser = "SELECT session_log.user_id, session_log.session_id AS current_session, user.type AS user_type, user.folder AS user_folder
    FROM session_log INNER JOIN user ON session_log.user_id = user.id
    WHERE session_log.session_id = '$session_id'";
    $user = getQuery($getUser);
    if($user > 0){
        $user = $user[0];
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["current_session"] = $user["current_session"];
        $_SESSION["user_type"] = $user["user_type"];
        $_SESSION["user_folder"] = $user["user_folder"];
    }
    return true;
}

function logout($state){
    $session_id = $_COOKIE["current_session"];
    $send["close_session"] = closeSession($_COOKIE["current_session"], $state);
    setcookie("current_session", $session_id, (time() - 3600), "/");
    $send["redirect"] = str_replace("server/login.php", "", ($_SERVER["REQUEST_URI"]));

    session_unset(); 
    session_destroy(); 
    sendStatus(200, $send);
}

?>