<?php 
session_start();
require_once("server/credentials.php");
require_once("server/db-routines.php");
require_once("server/session_log.php");
require_once("server/main-tools.php");
// exit();

// print_r("Session State: ".getCurrentSessionState($_COOKIE["current_session"])); echo "<br>";
// echo "PHP session_id(): ".session_id(); echo "<br>";
// print_r("Cookie current_session: ".$_COOKIE["current_session"]); echo "<br>";
// echo "Cookie VAR: ";
// print_r($_COOKIE); echo "<br>";
// exit();

if( isset($_COOKIE["current_session"]) ){
    // -- TO DO: Verify session_state is active
    $sessionState = getCurrentSessionState($_COOKIE["current_session"]);
    switch ($sessionState) {
        case 'active':
            if(session_id() != $_COOKIE["current_session"]){
                // -- TO DO: Close all active sessions with same user_id
                $session_id = $_COOKIE["current_session"];
                session_id($session_id);
                
                $userConfig = getUserConfig($session_id);
                setSessionVars($userConfig);
            }
            break;
        case 'expired':
            // -- TO DO: Close expired session
            $logout = logout($_COOKIE["current_session"], "expired");
            if($logout == true){
                header("Location: login.php");
            } else {
                print_r($logout);
            }
            break;
        case 'closed':
            header("Location: login.php");
            break;
        default: 
            header("Location: login.php");
            break;
    }
} else {
    header("Location: login.php");
};

function logout($session_id, $state){
    try {
        $send["close_session"] = closeSession($_COOKIE["current_session"], $state);
        setcookie("current_session", $session_id, (time() - 3600), "/");
        $send["redirect"] = str_replace("server/login.php", "", ($_SERVER["REQUEST_URI"]));

        session_unset(); 
        session_destroy(); 
        return true;
    } catch (\Throwable $th) {
        return $th;
    }
    // sendStatus(200, $send);
}

function getUserConfig($session_id){
    $getUserConfig = "SELECT session_log.user_id, session_log.session_id AS current_session, user.type AS user_type, user.folder AS user_folder
    FROM session_log INNER JOIN user ON session_log.user_id = user.id
    WHERE session_log.session_id = '$session_id'";
    $result = getQuery($getUserConfig);
    if($result > 0){
        $userConfig = $result[0];
        return $userConfig;
    }
}

function setSessionVars($userConfig){
    try {
        $_SESSION["user_id"] = $userConfig["user_id"];
        $_SESSION["current_session"] = $userConfig["current_session"];
        $_SESSION["user_type"] = $userConfig["user_type"];
        $_SESSION["user_folder"] = $userConfig["user_folder"];
        return true;
    } catch (\Throwable $th) {
        return false;
    }
}

function getCurrentSessionState($session_id){
    $getState = "SELECT current_state, expires FROM session_log WHERE session_id = '$session_id'";
    $sessionState = getQuery($getState);
    if($sessionState > 0){
        $sessionState = $sessionState[0];
    }
    $currentState = time() < $sessionState["expires"] ? $sessionState["current_state"] : "expired";
    return $currentState;
}
?>