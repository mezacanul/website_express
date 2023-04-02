<?php 

if($_SERVER["REQUEST_METHOD"] == "POST"){
    switch ($_POST["action"]) {
        case 'downloadDemo':
            $demoPath = $_POST["demoPath"];
            $siteName = $_POST["siteName"];
            $demoUrl = zipAndGetDemo($demoPath, $siteName);
            echo json_encode($demoUrl);
            break;
        default:
            break;
    }
}

function zipAndGetDemo($demoPath, $siteName){
    $pathToZip = realpath($demoPath);
    
    $zip = new ZipArchive();
    $zip->open("../files/zip/$siteName.zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);

    // Create recursive directory iterator
    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($pathToZip),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files as $name => $file)
    {
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

    return $siteName;
}

?>