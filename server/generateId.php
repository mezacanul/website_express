<?php 

function generateId($length){
    $abc = str_split("abcdefghijklmnopqrstuvwxyz1234567890");
    $id = "";

    for ($i=0; $i < $length; $i++) { 
        $l = $abc[rand(0, (count($abc) - 1))];
        $id .= $l;
    }
    return $id;
}

?>