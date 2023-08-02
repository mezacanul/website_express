<?php
require_once("credentials.php");
require_once("db-routines.php");
require_once("main-tools.php");
require_once("js-text.php");
// require_once("get.php");

date_default_timezone_set("America/Mexico_City");

$prefix = "https://github.com/YanaEgorova/";
$suffix = "/zipball/master/";

// -- For http requests
$arrContextOptions= [
    "ssl"=> [
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ],
];  

// -- Global vars
$tmpFolder = "../files/tmp/";
$zipFolder = "../files/zip/";
$ignore = [".", "..", ".DS_Store"];
$delete = [
    "products.js",
    "img0.png", "img1.png", "img2.png", "img3.png", "img4.png", "img5.png", "img6.png", "img7.png", "img8.png", "img9.png", "img10.png", "img11.png", "img12.png", "img13.png",
];

function getTemplateOptions($id){
    $getTemplate = "SELECT * FROM templates WHERE id = '$id'";
    $templateOptions = getQuery($getTemplate);
    return $templateOptions[0];
}

// -- APP
if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Get POST parameters
    $templateUrl = $_POST["template"];
    $websiteData = $_POST["websiteData"];
    $templateId = $_POST["templateId"];
    $type = $_POST["type"];
    $nicheType = $_POST["nicheType"];
    $prices = $_POST["prices"];
    $returnAddressId = $_POST["returnAddressId"];
    $userConfig = getFromCurrentSession_UserConfig($_POST["current_session"]);
    $tmpFolder = $tmpFolder.$userConfig["user_folder"]."/";
    $zipFolder = $zipFolder.$userConfig["user_folder"]."/";
    
    // echo($type.$nicheType);
    // exit();

    // Organize template options
    $templateOptions = getTemplateOptions($templateId);
    $replaceColors = json_decode($templateOptions["colors"]);
    $replaceBgs = json_decode($templateOptions["bgs"]);
    $replaceTaglines = json_decode($templateOptions["taglines"]);

    $addToCode = [
        "css"=>$templateOptions["css"],
        "js"=>$templateOptions["js"]
    ];

    // -- MAIN FUNCTION: TESTING AREA
    // print_r(1);
    // exit();


    // Get new values from DB for template options
    // -- TO DO: 
    // - Get colors (if gem -> get gem colors)
    // - Get bgs per type
    // - Get taglines per type
    if($replaceColors){
        $replaceColors = array_map(function($color) { 
            global $type;
    
            $colorType = ($type != "jewelry" && $type != "sewing") ? $color->colorType : ($color->colorType."Fem");
            $getNewColor = "SELECT * FROM colors WHERE type = '$colorType' ORDER BY rand() LIMIT 1";
            $nColor = getQuery($getNewColor)[0];
            $color->replace = $nColor["color"];
            $color->replaceType = $nColor["type"];
            
            return $color; 
        }, $replaceColors);
    }

    if($replaceTaglines){
        $replaceTaglines = array_map(function($tgln) {
            global $type;
            
            switch ($tgln) {
                case 'sub':
                    $getNewTgln = "SELECT * FROM taglines WHERE type = 'sub' ORDER BY rand() LIMIT 1";
                    break;
                case 'main':
                    $getNewTgln = "SELECT * FROM taglines WHERE type = '$type' AND sub = 'short' ORDER BY rand() LIMIT 1";
                    break;
                case 'second_main':
                    $getNewTgln = "SELECT * FROM taglines WHERE type = '$type' AND sub = 'short' ORDER BY rand() LIMIT 1";
                    break;
                case 'second_sub':
                    $getNewTgln = "SELECT * FROM taglines WHERE type = '$type' AND sub = 'long' ORDER BY rand() LIMIT 1";
                    break;
                default:
                    # code...
                    break;
            }
            $nTgln = getQuery($getNewTgln)[0];
            
            $tglnOpt = [
                "type"=>$tgln,
                "tgln"=>$nTgln["tagline"]
            ];
            return $tglnOpt;
        }, $replaceTaglines);
    }

    if($replaceBgs){
        $replaceBgs = array_map(function($bg) {
            global $type;
    
            if($type == "preworkout"){
                $getNewBgs = "SELECT * FROM backgrounds WHERE type = 'sport' ORDER BY rand() LIMIT 1";
            } elseif($type == "keto") {
                $getNewBgs = "SELECT * FROM backgrounds WHERE type = 'nutra' ORDER BY rand() LIMIT 1";
            } else {
                $getNewBgs = "SELECT * FROM backgrounds WHERE type = '$type' ORDER BY rand() LIMIT 1";
            }
            $nBg = getQuery($getNewBgs)[0];
    
            $bgOpt = [
                "bg"=>$bg,
                "replace"=>$nBg["fileId"]
            ];
            return $bgOpt;
        }, $replaceBgs);
    }

    $replaceOptns = [
        "replaceColors"=>$replaceColors,
        "replaceBgs"=>$replaceBgs,
        "replaceTaglines"=>$replaceTaglines
    ];


    cleanFolder($tmpFolder);
    downloadAndExtractZip($templateUrl, $zipFolder, $tmpFolder);
    
    $newSite = getTemplatePath();
    $newSiteFilePaths = getNewSiteFilePaths($newSite);
    performActions($newSite, $newSiteFilePaths, $templateUrl, $websiteData, $replaceOptns, $addToCode, $prices, $returnAddressId, $type, $nicheType);

    cleanFolder($zipFolder);

    $response = [
        "serverPath" => $tmpFolder.$newSite,
        "sitePath" => $newSite
    ];

    echo json_encode($response);
} else {
    // -- TESTING
    // -- POST VARS
    $templateUrl = "https://github.com/YanaEgorova/new-template-202";
    $websiteData = 'const WEBSITE_NAME = "The Gem District";
    const WEBSITE_URL = "TheGemDistrict.com";
    const WEBSITE_EMAIL = "support@TheGemDistrict.com";
    const WEBSITE_DESCRIPTOR = "8446051598TheGemDistri";
    const WEBSITE_PHONE = "844 605 1598";
    const WEBSITE_CORP = "Ca Ro Gadgets Inc.";
    const WEBSITE_ADDRESS = "353 West 48th St 4th Flr PBM 370, New York, NY 10036, USA";
    const WEBSITE_RETURN_ADDRESS = "12913 Harbor Blvd Ste Q3 #433, Garden Grove, CA 92840, USA";';;

    // --------------------------------------------------------------------
    // -- TO DO: Delete all files from "tmp" folder before starting program
    // --------------------------------------------------------------------
    cleanFolder($tmpFolder);
    downloadAndExtractZip($templateUrl, $zipFolder, $tmpFolder);
    
    $newSite = getTemplatePath();
    $newSiteFilePaths = getNewSiteFilePaths($newSite);
    performActions($newSite, $newSiteFilePaths, $templateUrl, $websiteData);
    cleanFolder($zipFolder);

    $response = [
        "demo" => $tmpFolder.$newSite
    ];

    echo json_encode($response);
}

// -- Delete all files from target folder 
// -- return TRUE
function cleanFolder($dir){
    // global $tmpFolder;

    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        if ($file->isDir()){
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
}

// -- Download and extract template on tmp folder
// -- return TRUE
function downloadAndExtractZip($templateUrl, $zipFolder, $tmpFolder){
    global $arrContextOptions, $pathToTxtFile, $prefix, $linkToZips;
    $linkToZip = zipLink($templateUrl);
    $pathToZip = $zipFolder."/".zipName($templateUrl);

    file_put_contents($pathToZip, file_get_contents($linkToZip, false, stream_context_create($arrContextOptions)));

    $zip = new ZipArchive;
    if ($zip->open($pathToZip) === TRUE) {
        $zip->extractTo($tmpFolder);
        $zip->close();
        return true;
    } 
}

// -- Search for template folder
// -- return STRING
function getTemplatePath(){
    global $tmpFolder, $ignore;

    $newSite = [];
    $scan = scandir($tmpFolder);
    foreach($scan as $file) {
        if (!is_dir("myFolder/$file")) {
            if(!in_array($file, $ignore)){
                array_push($newSite, $file);
            }
        }
    }
    if(count($newSite) == 1) {
        $newSite = $newSite[0];
    }
    return $newSite;
}

// -- Search for files and folders on new template folder
// -- return ARRAY
function getNewSiteFilePaths($newSite){
    global $tmpFolder;

    $newSiteFilePaths = [];
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tmpFolder.$newSite)) as $filename) {
        // filter out "." and ".."
        if ($filename->isDir()) continue;
        $n = new SplFileInfo($filename);
        array_push($newSiteFilePaths, $n->getPathName());
    }

    return $newSiteFilePaths;
}

// -- Get name of template zip folder
// -- return STRING
function zipName($t){
    global $prefix;
    return str_replace($prefix,"", $t).".zip";
}

// -- Create link to github master zip
// -- return STRING
function zipLink($t){
    global $suffix;
    return $t.$suffix;
}

// -- Reads original wd file, add wd to new string, delete original file and create new "website-data.js" file
// -- return TRUE
function addWebsiteData($wdPath, $wd){
    $export = "";

    // Read "website-data.js" file
    $myfile = fopen($wdPath, "r") or die("Unable to open file!");
    $websiteDataTxt = fread($myfile,filesize($wdPath));
    fclose($myfile);

    // Splice text by line jump
    $arrayFromString = explode("\n",$websiteDataTxt);
    if(strpos($arrayFromString[0], "export") == 0){
        $export = "export ";
    }

    // Delete first 8 lines
    for ($i=0; $i < 9; $i++) { 
        unset($arrayFromString[$i]);
    }

    // Add "export" keyword to website data where it applies 
    $websiteDataArray = explode("\n",$wd);
    foreach ($websiteDataArray as $i => $w) {
        $websiteDataArray[$i] = $export.$w;
    }

    // Add website data to "finalTxt" variable
    $merge = array_merge($websiteDataArray, $arrayFromString); 
    $finalTxt = "";
    foreach ($merge as $ln) {
        $finalTxt .= $ln."\n";
    }

    // Delete original file and create new one with website data
    unlink($wdPath);
    $wdFile = fopen($wdPath, "w") or die("Unable to open file!");
    fwrite($wdFile, $finalTxt);
    fclose($wdFile);

    return true;
}

// -- Perform actions
// -- . Add info.txt file
// -- . Delete files from $delete array
// -- . Add website data to website-data.js file
function performActions($newSite, $newSiteFilePaths, $url, $wd, $replaceOptns, $addToCode, $prices, $returnAddressId, $type, $nicheType){
    global $delete, $tmpFolder, $prefix, $js_texts;
    // print_r($js_texts);
    // exit();
    // $userConfig = getFromCurrentSession_UserConfig($_COOKIE["current_session"]);

    // . Add "info.txt"
    $infoFile = $tmpFolder.$newSite."/info.txt";
    $myfile = fopen($infoFile, "w") or die("Unable to open file!");
    $txt = "// -- Template Name: ".str_replace($prefix,"", zipName($url));
    // $txt = $txt."\n// -- User ID: ".$userConfig["user_id"];
    $txt = $txt."\n// -- Created: ".date("Y-m-d H:i:s", substr(time(), 0, 10));
    fwrite($myfile, $txt);
    fclose($myfile);

    // . Replace backgrounds
    if($replaceOptns["replaceBgs"]){
        foreach ($replaceOptns["replaceBgs"] as $rBg) {
            $from = "../files/img/backgrounds/".$rBg["replace"];
            $to = $tmpFolder.$newSite.'/img/'.$rBg["bg"];
            copy($from, $to);
        }
    }

    // -- $fof : File or Folder
    foreach ($newSiteFilePaths as $fof) {
        // .
        foreach ($delete as $d) {
            if(strpos($fof, $d)){
                if(is_file($fof)){
                    unlink($fof);
                }
            }
        }

        if($prices == "bpx"){
            switch (true) {
                case strpos($fof, ".html"):
                    updatePriceScript($fof);
                    break;
                case strpos($fof, ".php"):
                    updatePriceScript($fof);
                    break;
                default:
                    break;
            }
        }

        # -- (TEMPORARY) DELETE "Affiliate program" from all html, php files
        switch (true) {
            case strpos($fof, ".html"):
                removeAffiliateProgram($fof);
                break;
            case strpos($fof, ".php"):
                removeAffiliateProgram($fof);
                break;
            default: break;
        }
    
        // .
        switch (true) {
            case strpos($fof, "website-data.js"):
                addWebsiteData($fof, $wd);
                if($replaceOptns["replaceTaglines"]){
                    addTaglines($fof, $replaceOptns["replaceTaglines"]);
                }
                addJsToWDFile($fof, $addToCode["js"]);
                break;
            case strpos($fof, "vars.css"):
                if($replaceOptns["replaceColors"]){
                    replaceColors($fof, $replaceOptns["replaceColors"]);
                }
                addCssToVarsFile($fof, $addToCode["css"]);
                break;
            // case strpos($fof, "shipping"):
            //     if($replaceOptns["replaceColors"]){
            //         replaceColors($fof, $replaceOptns["replaceColors"]);
            //     }
            //     addCssToVarsFile($fof, $addToCode["css"]);
            //     break;
            default: break;
        }
    }

    // . Add default "products.js" if niche type is special
    if($nicheType == "special"){
        $products_js = $tmpFolder.$newSite.'/js/data/products.js';
        $myfile = fopen($products_js, "w") or die("Unable to open file!");
        
        switch ($type) {
            case 'keto': fwrite($myfile, $js_texts["keto_js"]); break;
            case 'preworkout': fwrite($myfile, $js_texts["preworkout_js"]); break;
            case 'skincare': fwrite($myfile, $js_texts["skincare_js"]); break;
            default: break;
        }
        fclose($myfile);
    }
}

function removeAffiliateProgram($file){
    $myfile = fopen($file, "r") or die("Unable to open file!");
    $fileContent = fread($myfile,filesize($file));
    fclose($myfile);

    $newContent = str_replace("Affiliate Program", "", $fileContent);
    $newContent = str_replace("affiliate-program", "", $newContent);

    $myNewFile = fopen($file, "w") or die("Unable to open file!");
    fwrite($myNewFile, $newContent);
    fclose($myNewFile);
}

function updatePriceScript($file){
    $myfile = fopen($file, "r") or die("Unable to open file!");
    $fileContent = fread($myfile,filesize($file));
    fclose($myfile);

    $newContent = str_replace("-nxg-", "-bpx-", $fileContent);

    $myNewFile = fopen($file, "w") or die("Unable to open file!");
    fwrite($myNewFile, $newContent);
    fclose($myNewFile);
}

// Adds custom js to website-data.js file
// return TRUE
function addJsToWDFile($wdPath, $newJs){
    $wdFile = fopen($wdPath, "a") or die("Unable to open file!");
    $js = "\n".$newJs;
    fwrite($wdFile, $js);
    fclose($wdFile);
    return true;
}

// Adds custom css to vars.css file
// return TRUE
function addCssToVarsFile($varsPath, $newCss){
    $varsFile = fopen($varsPath, "a") or die("Unable to open file!");
    $css = "\n".$newCss;
    fwrite($varsFile, $css);
    fclose($varsFile);
    return true;
}

// -- Replaces color variables on vars.css
// -- return TRUE
function replaceColors($varsFile, $replaceColors){
    // -- TO DO: make function replaceColors()
    // $varsFile = $fof;
    $myfile = fopen($varsFile, "r") or die("Unable to open file!");
    $varsCss = fread($myfile,filesize($varsFile));
    fclose($myfile);

    foreach ($replaceColors as $colorReplace) {
        $varsCss = str_replace($colorReplace->color,$colorReplace->replace,$varsCss);
    }
    $myfile = fopen($varsFile, "w") or die("Unable to open file!");
    fwrite($myfile, $varsCss);
    fclose($myfile);
    
    return true;
}

// -- Adds taglines on website-data.js
// -- return TRUE
function addTaglines($wdFile, $replaceTaglines){
    global $type;
    $mainTgKey = 0;
    $subTgKey = 0;
    $sec_mainTgKey = 0;
    $sec_subTgKey = 0;

    // Read "website-data.js" file
    $myfile = fopen($wdFile, "r") or die("Unable to open file!");
    $websiteDataTxt = fread($myfile,filesize($wdFile));
    fclose($myfile);   

    $arrayFromString = explode("\n",$websiteDataTxt);
    // print_r($arrayFromString);
    foreach ($arrayFromString as $i => $ln) {
        switch (true) {
            case strpos($ln, "MAIN_TAGLINE ="): 
                $mainTgKey = $i;
                break;
            case strpos($ln, "SECONDARY_TAGLINE ="): 
                $subTgKey = $i;
                break;
            case strpos($ln, "MAIN_SECOND_TAGLINE ="): 
                $sec_mainTgKey = $i;
                break;
            case strpos($ln, "SECONDARY_SECOND_TAGLINE ="): 
                $sec_subTgKey = $i;
                break;
            default:
                break;
        }
    }

    foreach ($replaceTaglines as $tgln) {
        // $decodedString = mb_convert_encoding($tgln['tgln'], 'UTF-8', 'Windows-1252');
        // $tagline = str_replace("'","\'",$decodedString);
        // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        $tagline = str_replace("'","\'",$tgln['tgln']);

        switch ($tgln["type"]) {
            case 'main':
                $arrayFromString[$mainTgKey] = "const MAIN_TAGLINE = '$tagline';";
                break;
            case 'sub':
                $nTgln = str_replace("[TYPE]", $type, $tagline);
                $arrayFromString[$subTgKey] = "const SECONDARY_TAGLINE = '$nTgln';";
                break;
            case 'second_main':
                $arrayFromString[$sec_mainTgKey] = "const MAIN_SECOND_TAGLINE = '$tagline';";
                break;
            case 'second_sub':
                $arrayFromString[$sec_subTgKey] = "const SECONDARY_SECOND_TAGLINE = '$tagline';";
                break;
            default:
                break;
        }
    }

    $finalTxt = "";
    foreach ($arrayFromString as $ln) {
        $finalTxt .= $ln."\n";
    }

    $wdFile = fopen($wdFile, "w") or die("Unable to open file!");
    fwrite($wdFile, $finalTxt);
    fclose($wdFile);
    
    return true;
}

// function copyFile($from, $to){
//     copy($from, $to);
//     return true;
// }
?>