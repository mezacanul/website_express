<?php

$prefix = "https://github.com/YanaEgorova/";
$tmplts = [
    "https://github.com/YanaEgorova/new-template-211",
];

// -- After downloading and unziping template
$tmpFolder = "files/tmp";
$newSite = [];
$newSiteFiles = [];

$ignore = [".", "..", ".DS_Store"];
$delete = [
    "products.js",
    "img0.png", "img1.png", "img2.png", "img3.png", "img4.png", "img5.png", "img6.png", "img7.png", "img8.png", "img9.png", "img10.png", "img11.png", "img12.png", "img13.png",
];

// -- Search for template name
// -- $newSite
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

// -- Search for files and folders on new template folder
// -- $newSiteFiles
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tmpFolder."/".$newSite)) as $filename) {
    // filter out "." and ".."
    if ($filename->isDir()) continue;
    $n = new SplFileInfo($filename);
    array_push($newSiteFiles, $n->getPathName());
}

// -- Perform actions
// -- . Delete files from $delete array
// -- . Add info.txt file
foreach ($newSiteFiles as $fof) {
    // .
    foreach ($delete as $d) {
        if(strpos($fof, $d)){
            unlink($fof);
        }
    }

    // .
    $infoFile = $tmpFolder."/".$newSite."/info.txt";
    $myfile = fopen($infoFile, "w") or die("Unable to open file!");
    $txt = "// -- Template Name: ".str_replace($prefix,"", $tmplts[0]);
    fwrite($myfile, $txt);
    fclose($myfile);

    // .
    switch (true) {
        case "" == "":
            break;
        // case 'products.js':
        //     unlink($fof);
        //     break;
        default:
            break;
    }
}

exit();

$suffix = "/zipball/master/";
$prefix = "https://github.com/YanaEgorova/";
$fileNames = [];
$linkToZips = [];
$tmplts = [
    "https://github.com/YanaEgorova/new-template-211",
];

// Files
$filesFolder = 'files/*';
$pathToTxtFile = "files/info.txt";

// For http requests
$arrContextOptions= [
    "ssl"=> [
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ],
];  

// -- Delete previous images on "files/tmp/img" folder
function cleanFilesFolder(){
    global $filesFolder;
    $files = glob($filesFolder, GLOB_BRACE);
    // $files = glob('files/tmp/img/*'); // get all file names
    foreach($files as $file){ // iterate files
        if(is_file($file)) {
            unlink($file); // delete file
        }
    }
}

function zipName($t){
    global $prefix;
    return str_replace($prefix,"", $t).".zip";
}

function linkToZip($t){
    global $suffix;
    return $t.$suffix;
}

function buildUserFiles(){
    global $tmplts, $arrContextOptions, $pathToTxtFile, $prefix, $linkToZips;
    
    foreach ($tmplts as $key => $t) {
        $pathToZip = "files/".zipName($t);
        // Create ZIP file
        file_put_contents($pathToZip, file_get_contents(linkToZip($t), false, stream_context_create($arrContextOptions)));

        // Create info.txt file
        $myfile = fopen($pathToTxtFile, "w") or die("Unable to open file!");
        $txt = "// -- Template Name: ".str_replace($prefix,"", $t);
        fwrite($myfile, $txt);
        fclose($myfile);


        $zip = new ZipArchive;
        if ($zip->open($pathToZip) === TRUE) {
        $zip->extractTo('files/tmp');
        $zip->close();
        echo 'ok';
        } else {
        echo 'failed';
        }

        // Add info.txt to ZIP file
        // $z = new ZipArchive();
        // $z->open($pathToZip);
        // for( $i = 0; $i < $z->numFiles; $i++ ){ 
        //     $stat = $z->statIndex( $i ); 
        //     print_r( basename( $stat['name'] ) . PHP_EOL ); 
        // }
        // // Notice the second argument which specifies the local path in the archive
        // // $z->addFile($pathToTxtFile, "info.txt");
        // $z->close();
        // array_push($linkToZips, $pathToZip);
    }
}

cleanFilesFolder();
buildUserFiles();
unlink($pathToTxtFile);

// echo json_encode($linkToZips);
echo "Files ready:<br><br>";
foreach ($linkToZips as $link) {
    echo "<a href='$link'>$link</a>";
    echo "<br>";
}

?>