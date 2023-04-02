<!-- <script src="./lib/jquery/jquery-3.6.0.min.js"></script>
<script>
    $.post("testing.php").then((data)=>{
        console.log(data);
    })
</script> -->
<?php 
    // -- Inital Configuration
    // -----------------------
    // -- For script execution (in seconds)
    $start = microtime(true);
    // -----------------------
    // Server utilities available on application
    require_once("server/credentials.php"); require_once("server/db-routines.php"); require_once("server/generateId.php");
?>

<?php
// if($_SERVER["REQUEST_METHOD"] == "POST"){
    $str = getQuery("SELECT * FROM taglines where id = '018f17f2-737b-11ed-9e5f-38c98642bc92'");
    // echo $str[0]["tagline"];
    echo mb_convert_encoding($str[0]["tagline"], 'UTF-8', 'Windows-1252');
// }
exit();
?>

<?php

// STATUS: Disabled
// See "POST_"
if($_SERVER["REQUEST_METHOD"] == "POST_"){
    // -----------------------
    // --------- Get Amazon product details testing

    // -- Product URL
    $url = "https://www.amazon.com.mx/dp/B09G987MYR/";

    $arrContextOptions= [
        "ssl"=> [
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ],
    ];  

    $page = file_get_contents($url, false, stream_context_create($arrContextOptions));
    $dom = new DOMDocument;
    @$dom->loadHTML($page);
    // -> Document loaded
    $xpath = new DomXPath($dom);
    // -> For searching with classes

    $elements = $xpath->query("//*[contains(@class, 'po-')]");

    // $target = $dom->getElementsByClassName("a-spacing-small");
    // print_r($target->getAttribute("data-old-hires"));
    foreach ($elements as $detail) {
        foreach ($detail->childNodes as $node) {
            if(isset($node->tagName) && $node->tagName == "td"){
                print_r($node->nodeValue);
                echo "; ";
            }
        }
        echo "<br>";
    }

    // print_r($elements[0]->childNodes[2]);
    // -----------------------
}

// -----------------------
// --------- AWS testing
// $url = 'https://www.amazon.com/Ravinte-Kitchen-Hardware-Cabinets-Cupboard/dp/B07WXNZMXJ/ref=sr_1_4?c=ts&keywords=Hardware&qid=1670887494&s=hardware&sr=1-4&ts_id=511228';
// // $url = "https://www.facebook.com";

// // Initialize a new curl resource
// $ch = curl_init($url);
// // Set the options for the curl request
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return the response as a string
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Follow redirects
// // Execute the curl request
// $response = curl_exec($ch);
// // Close the curl resource to free up system resources
// curl_close($ch);
// echo $response;
// -----------------------

// -----------------------
// --------- List and make background names for Website Express app
// $type = "hardware";
// $paths = getFilesInfo("files/img/backgrounds/$type", "path");
// $fileNames = getFilesInfo("files/img/backgrounds/$type", "fn"); 

// echo renameFiles($paths);
// echo buildComparativeTable($fileNames);

// foreach ($fileNames as $fn) {
//     echo $fn;
//     echo "<br>";
// }
// -----------------------

// -- FUNCTIONS:
// -------------

// $folder : STRING - Folder where to scan for files
// $path_or_fn : STRING - ("path", "fn") Defines if path or filename is gonna be returned
function getFilesInfo($folder, $path_or_fn){
    global $tmpFolder;
    $filePaths = [];

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder)) as $filename) {
        // filter out "." and ".."
        if ($filename->isDir()) continue;
        $n = new SplFileInfo($filename);
        
        if($n->getFileName() != ".DS_Store"){
            switch ($path_or_fn) {
                case 'path':
                    array_push($filePaths, $n->getPathName());
                    break;
                case 'fn':
                    array_push($filePaths, $n->getFileName());
                    break;
                default: break;
            }
        }
    }

    return $filePaths;
}

function buildComparativeTable($fileNames){
    global $type;
    $table = "<table>";
    
    $table .= "<tr>";
    $table .= "<th>Old Name</th>";
    $table .= "<th>New Name</th>";
    $table .= "</tr>";

    foreach ($fileNames as $fn) {
        $id = generateId(4);
        $table .= "<tr>";
        $table .= "<td>$fn</td>";
        $table .= "<td>$type"."_$id.jpg</td>";
        $table .= "</tr>";
    }

    $table .= "</table>";
    return $table;
}

function renameFiles($paths){
    global $type;
    foreach ($paths as $pth) {
        $bgPath = "files/img/backgrounds/$type/";
        $newFile = $type."_". generateId(4) .".jpg";
        rename($pth, $bgPath.$newFile);
    }
    return "ok";
}

// -----------------------
// -- For script execution (in seconds)
echo "<br>";
echo "<br>";
$time_elapsed_secs = ( microtime(true) - $start );
echo "<b>Time:</b> " . $time_elapsed_secs . " seconds";
// -----------------------

exit();
?>

<!-- FIX tagline encoding -->
<?php

// $getTaglines = "SELECT * from taglines where id = '3239460e-4f52-11ed-aab8-38c98642bc92'";
// $tglns = getQuery($getTaglines);
// // // print_r(utf8_decode($tglns[0]["tagline"]));
// echo "<pre>";
// $decodedString = mb_convert_encoding($tglns[0]["tagline"], 'UTF-8', 'Windows-1252');
// echo str_replace("'","\'",$decodedString);
// echo "</pre>";

?>

<?php

// -- List files in bgs folder
// $location = "files/img/backgrounds/".$type;
// $it = new RecursiveDirectoryIterator($location, RecursiveDirectoryIterator::SKIP_DOTS);
// $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
// $i = 0;

?>

<!-- <div id="create">
    <p>Source</p>
    <input type="text" placeholder="Type">
    <br>
    <textarea name="" id="" cols="30" rows="10"></textarea>
    <br>
    <br>
    <button onclick="create()">Create</button>
    <br>
    <p>Result</p>
    <textarea name="" id="" style="width: 50%" rows="10"></textarea>
</div> -->

<!-- <div id="bgs">
    <p>Source</p>
    <input type="text" placeholder="Type">
    <br><br>
    <input type="text" placeholder="Qty">
    <br>
    <br>
    <br>
    <button onclick="bgs()">Create</button>
    <br>
    <p>Result</p>
    <textarea name="" id="" style="width: 50%" rows="10"></textarea>
</div> -->

<script src="lib/jquery/jquery-3.6.0.min.js"></script>
<script src="generateId.js"></script>
<script>
    function create() {
        userInput = $("textarea")[0].value
        type = $("input[type='text']")[0].value
        taglines = userInput.split("\n")
        result = ""

        taglines.forEach(tln => {
            result += `(UUID(), '${(tln).replace("'", "\'")}', '${type}'),\n`
        });
        $("textarea")[1].value = result
        // console.log(result);
    }

    function bgs() {
        type = $("input[type='text']")[0].value
        qty = $("input[type='text']")[1].value
        txt = ""

        for (i = 0; i < qty; i++) {
            txt += `(UUID(), '${ type+"_"+generateId(4)+".jpg" }', '${type}')${i == 49? "" : ",\n"}`
        }

        $("textarea")[0].value = txt
    }
</script>

<?php 

// $wDataPathFile = "files/tmp/YanaEgorova-new-template-101-9d338b0/js/website-data.js";
// $websiteData = 'export const WEBSITE_NAME = "The Gem District";
// export const WEBSITE_URL = "TheGemDistrict.com";
// export const WEBSITE_EMAIL = "support@TheGemDistrict.com";
// export const WEBSITE_DESCRIPTOR = "8446051598TheGemDistri";
// export const WEBSITE_PHONE = "844 605 1598";
// export const WEBSITE_CORP = "Ca Ro Gadgets Inc.";
// export const WEBSITE_ADDRESS = "353 West 48th St 4th Flr PBM 370, New York, NY 10036, USA";
// export const WEBSITE_RETURN_ADDRESS = "12913 Harbor Blvd Ste Q3 #433, Garden Grove, CA 92840, USA";';

// function addWebsiteData($wdPath, $wd){
//     $export = "";

//     // Read "website-data.js" file
//     $myfile = fopen($wdPath, "r") or die("Unable to open file!");
//     $websiteDataTxt = fread($myfile,filesize($wdPath));
//     fclose($myfile);

//     // Splice text by line jump
//     $arrayFromString = explode("\n",$websiteDataTxt);
//     // echo strpos($arrayFromString[0], "export");
//     if(strpos($arrayFromString[0], "export") == 0){
//         $export = "export ";
//     }

//     // Delete first 8 lines
//     for ($i=0; $i < 8; $i++) { 
//         unset($arrayFromString[$i]);
//     }

//     // Add "export" keyword to website data where it applies 
//     $websiteDataArray = explode("\n",$wd);
//     foreach ($websiteDataArray as $i => $w) {
//         $websiteDataArray[$i] = $export.$w;
//     }

//     // Add website data to "finalTxt" variable
//     $merge = array_merge($websiteDataArray, $arrayFromString); 
//     $finalTxt = "";
//     foreach ($merge as $ln) {
//         $finalTxt .= $ln."\n";
//     }

//     // $finalFile = $wDataPathFile;
//     unlink($wdPath);
//     $wdFile = fopen($wdPath, "w") or die("Unable to open file!");
//     fwrite($wdFile, $finalTxt);
//     fclose($wdFile);

//     return true;
// }

// function sendFiles($files){
//     print_r($files);
// }

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $templatePath = "/Applications/XAMPP/xamppfiles/htdocs/TEST/express/files/tmp/YanaEgorova-new-template-226-f5fa1ff";

    // print_r( $_FILES["f1"]["name"] );
    foreach ($_FILES as $f) {
        switch ($f["name"]) {
            case 'products.js':
                move_uploaded_file($f["tmp_name"], $templatePath."/js/data/".$f["name"]);
                echo "ok JS / ";
                break;
            default:
                break;
        }

        switch (true) {
            case (strpos($f["name"], "img") >= 0);
                move_uploaded_file($f["tmp_name"], $templatePath."/img/".$f["name"]);
                echo "ok IMGS / ";
                break;
            default:
                # code...
                break;
        }
    }

    // echo $_POST["action"]."---";
    // print_r( $_FILES );
}

// $finalTxt = addWebsiteData($wDataPathFile, $websiteData);
// echo "<pre>".$finalTxt."</pre>";
?>