<style>
    body {
        font-family: sans-serif;
    }
    .mainApp {
        display: flex;
        margin-bottom: 3rem;
        /* flex-direction: column;
        align-items: flex-start; */
    }

    textarea {
        display: block;
        margin-right: 3rem;
        margin-bottom: 1rem;
        width: 30rem;
        height: 15rem;
    }

    input[type="text"] {
        width: 30rem;
    }

    .message {
        display: none;
    }
</style>
<!-- 
<div class="mainApp">
    <div class="cont userInput">
        <h2>User text</h2>
        <textarea name="userCode" id="" cols="30" rows="10"></textarea>
        <button onclick="replaceText()">Replace</button>
        <p class="message">Text copied!</p>
    </div>
    <div class="cont">
        <h2>Result</h2>
        <textarea name="result" id="" cols="30" rows="10"></textarea>  
    </div>
</div>

<div class="options" style="display: none">
    <input type="text" placeholder="Replace this">
    <br><br>
    <input type="text" placeholder="With this">
</div> -->

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script>
    // var replaceVars = [ "5.95", "6.25", "6.95", "7.95", "19.99", "24.99", "39.99", "49.95", "69.99", "89.99", "99.99", "119.88", ["132.00", "132"], ["139.00", "139"], ["141.00", "141"], ["149.00", "149"], "159.87", "199.85" ]
    
    // function replaceText() {
    //     var newText = $("textarea[name='userCode']").val()
    //     replaceVars.forEach((txt, i) => {
    //         if(typeof(txt) == "object"){
    //             txt.forEach(t => {
    //                 newText = newText.replace(t, `priceMessages[${(i+1)}].price`)
    //             });
    //         } else {
    //             newText = newText.replace(txt, `priceMessages[${(i+1)}].price`)
    //         }
    //     })
    //     $("textarea[name='result']").val(newText)
    //     navigator.clipboard.writeText(newText)
    //     $(".message").css("display", "block")
    //     setTimeout(() => {
    //         $(".message").css("display", "none")
    //     }, 3000);
    // }
</script>

<?php 

$beforeScript = '<script type="module" src="./js/on-load.js"></script>';
$insertScript = "<script type='text/javascript' src='https://thebestproductmanager.com/products/prices-nxg-object'></script>\n\t";
$replaceNxg = [ "0.99", "5.95", "6.25", "6.95", "7.95", "19.99", "24.99", "39.99", "49.95", "69.99", "89.99", "99.99", "119.88", ["132.00", "132"], ["139.00", "139"], ["141.00", "141"], ["149.00", "149"], "159.87", "199.85" ];

$files = [];
foreach(glob('tmp/*.*') as $file) {
    array_push($files, $file);
}

foreach ($files as $f) {
    $myfile = fopen($f, "r") or die("Unable to open file!");
    $fileContent = fread($myfile,filesize($f));
    fclose($myfile);

    switch (true) {
        case strpos($f, "products.js"):
            $newText = $fileContent;
            foreach ($replaceNxg as $i => $r) {
                if(gettype($r) == "array"){
                    foreach ($r as $p) {
                        $newText = str_replace($r, "priceMessages[".($i)."].price", $newText);
                    }
                } else {
                    $newText = str_replace($r, "priceMessages[".($i)."].price", $newText);
                }
            }
            break;
        default:
            $newText = str_replace($beforeScript, ($insertScript . $beforeScript), $fileContent);
            break;
    }

    $newFilePath = str_replace("tmp/", "export/", $f);
    $newFile = fopen($newFilePath, "w") or die("Unable to open file!");
    fwrite($newFile, $newText);
    fclose($newFile);
}
  

echo "<h2>DONE<h2>";

?>