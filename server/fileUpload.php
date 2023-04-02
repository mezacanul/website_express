<?php 
require_once("credentials.php");
require_once("db-routines.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    switch ($_POST["action"]) {
        case 'uploadProducts':
            $templatePath = $_POST["newSitePath"];
            $demo = addProductsFromUpload($_FILES, $templatePath);
            echo json_encode($demo);
            break;
        case "includeProducts":
            echo json_encode( includeProducts($_POST["items"], $_POST["demoPath"]) );
        case "previewBgUpload":
            echo json_encode( previewBgUpload($_FILES) );
        default:
            break;
    }
}

function previewBgUpload($files){
    $tmpPath = "../files/img/preview/bg.jpg";
    // move_uploaded_file($files[0]["tmp_name"], $tmpPath);
    return $files;
}

function buildJsText($productsDetails){
    $text = "export const products = [\n";
    foreach ($productsDetails as $k => $pd) {
        $i = $k+1;
        // $text .= "--------";
        $text .= "\t{\n";
        $text .= "\t\tid: \"". $pd["sku"] ."\",\n";
        $text .= "\t\tname: \"". str_replace('"', '\"', $pd["name"]) ."\",\n";
        $text .= "\t\tprice: priceMessages[$i].price,\n";
        $text .= "\t\timage: \"img/". $pd["fileId"] ."\",\n";
        $text .= "\t\tisItRing: ". $pd["isRing"] .",\n";
        $text .= "\t\tringSizes: ". '["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13"]' .",\n";
        
        // Description JSON decoding
        $text .= "\t\tdescription: [\n";
        $jsonDesc = substr($pd["description"], 1);
        $jsonDesc = substr($jsonDesc, 0, -1);
        $descripton = json_decode($jsonDesc);
        foreach ($descripton as $desc) {
            $text .= "\t\t\t{ ";
            $text .= isset($desc->title) ? " title: \"". str_replace('"', '\"', $desc->title) ."\"," : "";
            $text .= " text: \"". str_replace('"', '\"', $desc->text) ."\" ";
            $text .= " },\n";
        }

        $text .= "\t\t]\n";
        $text .= "\t},\n";
        // $text .= "--------";
    }
    $text .= "]";
    return $text;
}

function getProductsDetails($items){
    $productsDetails = [];
    foreach ($items as $item) {
        $stmt = "SELECT * FROM products WHERE sku = '$item'";
        // echo getQuery($stmt);
        $getProduct = getQuery($stmt);
        array_push($productsDetails, $getProduct[0]);
    }

    return $productsDetails;
}

function includeProducts($items, $demoPath){
    $productsDetails = getProductsDetails($items);
    $productsText = buildJsText($productsDetails);
    $jsFilePath = $demoPath ."/js/data/products.js";

    $newFile = fopen($jsFilePath, "w") or die("Unable to open file!");
    fwrite($newFile, $productsText);
    fclose($newFile);

    foreach ($productsDetails as $prd) {
        if(file_exists("../files/img/products/".$prd["fileId"])){
            copy(("../files/img/products/".$prd["fileId"]), ($demoPath ."/img/". $prd["fileId"]));
        }
    }

    $res = "ok";
    return $res;
}

// FIX: Dennis double quote on price VAR
function fixDoubleQuote($newPath){
    $oldFile = fopen($newPath, "r") or die("Unable to open file!");
    $oldText = fread($oldFile,filesize($newPath));
    fclose($oldFile);

    $newText = str_replace('"priceMessages', "priceMessages", $oldText);
    $newText = str_replace('.price"', ".price", $newText);

    $newFile = fopen($newPath, "w") or die("Unable to open file!");
    fwrite($newFile, $newText);
    fclose($newFile);
}
// END FIX

function addProductsFromUpload($files, $targetFolder){
    foreach ($_FILES as $f) {
        switch ($f["name"]) {
            case 'products.js':
                $newPath = $targetFolder."/js/data/".$f["name"];
                move_uploaded_file($f["tmp_name"], $newPath);
                fixDoubleQuote($newPath);
                break;
            default:
                break;
        }
    
        switch (true) {
            case (strpos($f["name"], "img") >= 0);
                move_uploaded_file($f["tmp_name"], $targetFolder."/img/".$f["name"]);
                // do {
                //     $t = 2;
                // } while (!file_exists($targetFolder."/img/".$f["name"]));
                break;
            default:
                # code...
                break;
        }
    }

    return $targetFolder;
}
?>