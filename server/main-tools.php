<?php

function sendStatus($status, $response = [], $error = []){
    switch ($status) {
        case 200:
            $server["response"] = $response;
            $server["status"] = "ok";
            $server["error"] = [];
            break;
        case 400:
            $server["status"] = "not ok";
            $server["error"] = $error;
            break;
        case 401:
            $server["status"] = "unauthorized";
            break;
        default:
            break;
    }
    echo json_encode($server);
}

function getFromCurrentSession_UserConfig($session_id){
    $getUser = "SELECT session_log.user_id, session_log.session_id AS current_session, user.type AS user_type, user.folder AS user_folder
    FROM session_log INNER JOIN user ON session_log.user_id = user.id
    WHERE session_log.session_id = '$session_id'";
    $user = getQuery($getUser);
    // print_r($user);
    // exit();
    if($user > 0){
    //   print_r($user[0]);
    // exit();
        $user = $user[0];
        return $user;
        // sendStatus(200, $user);
    }
}

?>