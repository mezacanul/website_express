<?php 
require_once("credentials.php");
require_once("db-routines.php");
require_once("main-tools.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    switch ($_POST["action"]) {
        case 'downloadDemo':
            $demoUrl = zipAndGetDemo($_POST["demoPath"], $_POST["siteName"], $_POST["session_id"]);
            echo json_encode($demoUrl);
            break;
        default:
            break;
    }
}

function zipAndGetDemo($demoPath, $siteName, $session_id){
    $userConfig = getFromCurrentSession_UserConfig($session_id);
    $zipFolder = "../files/zip/".$userConfig["user_folder"]."/";
    $pathToZip = realpath("../".$demoPath);
    $zipFile = $zipFolder.$siteName.".zip";
    // $pathToZip = realpath($demoPath);
    // $pathToZip = realpath($pathToZip);
    // print_r($zipFolder); exit();
    
    $zip = new ZipArchive();
    $zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    
    // $pathToZipFile = str_replace("../", "", $zipFile);
    // return $pathToZipFile;
    // $zip->close();
    // exit();

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($pathToZip),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files as $name => $file) {
        // Skip directories (they would be added automatically)
        if (!$file->isDir())
        {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($pathToZip) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
        }
    }
    // Zip archive will be created only after closing object
    $zip->close();

    $pathToZipFile = str_replace("../", "", $zipFile);
    return $pathToZipFile;
}

?>