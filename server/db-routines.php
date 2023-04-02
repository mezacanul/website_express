<?php

function getQuery($query){
    global $servername, $dbname, $username, $password;
  
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
      $stmt = $conn->prepare($query);
      $stmt->execute();
  
      // set the resulting array to associative
      $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $all = $stmt->fetchAll();
      $conn = null;
    } catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
  
    return $all;  
}

function updateQuery($query){
  global $servername, $dbname, $username, $password;

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // set the resulting array to associative
    $success = $stmt->rowCount() . " records UPDATED";
  } catch(PDOException $e) {
    echo $query . "<br>" . $e->getMessage();
  }
  
  $conn = null;
  return $success;  
}

function deleteQuery($query){
  global $servername, $dbname, $username, $password;

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // use exec() because no results are returned
    $conn->exec($query);
    $success = "ok";
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
  
  $conn = null;
  return $success;  
}

function addQuery($query){
  global $servername, $dbname, $username, $password;

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // use exec() because no results are returned
    $conn->exec($query);
    $success = "ok";
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
  
  $conn = null;
  return $success;  
}



?>