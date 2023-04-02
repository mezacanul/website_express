<?php 
require_once("credentials.php");
require_once("db-routines.php");
// $servername, $dbname, $username, $password

function getAll(){

  $getAll = "SELECT * FROM products";
  $all = getQuery($getAll);

  return $all;
}

function search($clue, $type = "", $offset){

  if($type != ""){
    if($type == "sku"){
      $stmt = "SELECT * FROM products WHERE sku LIKE '%".$clue."%' LIMIT 10";
    } else {
      $stmt = "SELECT * FROM products WHERE name LIKE '%".$clue."%' AND type = '".$type."' LIMIT 20 OFFSET ".($offset * 20);
    }
  } else {
    $stmt = "SELECT * FROM products WHERE name LIKE '%".$clue."%' LIMIT 20 OFFSET ".($offset * 20);
  }

  $all = getQuery($stmt);
  return $all;
}

function countResults($clue, $type = ""){

  if($type != ""){
    if($type == "sku"){
      $stmt = "SELECT count(*) as total FROM products WHERE sku LIKE '%".$clue."%' LIMIT 10";
    } else {
      $stmt = "SELECT count(*) as total FROM products WHERE name LIKE '%".$clue."%' AND type = '".$type."' LIMIT 20";
    }
  } else {
    $stmt = "SELECT count(*) as total FROM products WHERE name LIKE '%".$clue."%' LIMIT 20";
  }
  $all = getQuery($stmt);
  return $all;
}

function getTypes(){

  $getProductTypes = "SELECT * FROM productTypes";
  $all = getQuery($getProductTypes);
  
  return $all;
}

function getPriceRanges(){

  $stmt = "SELECT * FROM priceRange";
  $all = getQuery($stmt);

  return $all;
}

function getItemDetails($sku) {

  $stmt = "SELECT * FROM products WHERE sku = '".$sku."'";
  $all = getQuery($stmt);

  return $all;
}

function getPriceList($bank){

  $getPrices = "SELECT price FROM prices WHERE bank = '$bank'";
  $all = getQuery($getPrices);

  return $all;
}

function getPriceBanks(){

  $getBankPrices = "SELECT DISTINCT bank FROM prices";
  $all = getQuery($getBankPrices);
  
  return $all;
}

function getBatch($type, $priceParams){
  
  // DEV USE: FOR POSTMAN TESTING -- 
  // $priceParams = json_decode($priceParams);
  // DEV END 
  
  $products = [];

  foreach ($priceParams as $prd) {
    $pId = $prd["priceId"];
    $getProduct = "SELECT * FROM products WHERE type = '$type' AND priceId = $pId ORDER BY rand() LIMIT 1"; 
    $product = getQuery($getProduct);
    array_push($products, $product);
  }

  return $products;
}

function getTemplates(){

  $getTemplates = "SELECT * FROM templates ORDER BY url";
  $all = getQuery($getTemplates);
  
  return $all;
}

function getTemplateInfo($id){

  $getTemplateInfo = "SELECT * FROM templates WHERE id = '$id' LIMIT 1";
  $all = getQuery($getTemplateInfo);
  
  return $all;
}

function switchProduct($params){

  $priceId = $_POST["priceId"];
  $type = $_POST["type"];
  $getRandomProduct = "SELECT * FROM products WHERE priceId = '$priceId' AND type = '$type' ORDER BY rand() LIMIT 1";
  $product = getQuery($getRandomProduct);

  return $product[0];
}

function getReturnAddressAll(){
  $getReturnAddressAll = "SELECT * FROM returnAddress";
  $all = getQuery($getReturnAddressAll);
  return $all;
}

switch ($_POST["action"]) {
  case "search":
    if($_POST["type"] != "default"){
      echo json_encode(search($_POST["clue"], $_POST["type"], $_POST["offset"]));
    } else {
      echo json_encode(search($_POST["clue"], "", $_POST["offset"]));
    } 
    break;
  case "countResults":
    if($_POST["type"] != "default"){
      echo json_encode(countResults($_POST["clue"], $_POST["type"]));
    } else {
      echo json_encode(countResults($_POST["clue"]));
    } 
    break;
  case "getAll":
    echo json_encode(getAll());
    break;
  case "getTypes":
    echo json_encode(getTypes());
    break;
  case "getPriceRanges":
    echo json_encode(getPriceRanges());
    break;
  case "getItemDetails":
    echo json_encode(getItemDetails($_POST["sku"]));
    break;
  case "getPriceBanks":
    echo json_encode(getPriceBanks());
    break;
  case "getBatch":
    echo json_encode(getBatch($_POST["type"], $_POST["priceParams"]));
    break;
  case "getTemplates":
    echo json_encode(getTemplates());
    break;
  case "getTemplateInfo":
    echo json_encode(getTemplateInfo($_POST["templateId"]));
    break;
  case "getPriceList":
    echo json_encode(getPriceList($_POST["bank"]));
    break;
  case "switchProduct":
    echo json_encode(switchProduct($_POST));
  case "getReturnAddressAll":
    echo json_encode(getReturnAddressAll());
    break;
  default:
    exit();
}

?>