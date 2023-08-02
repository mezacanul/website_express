<?php require_once("modules/init_config.php") ?>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/templates.css">

<body class="darkMode">
    <?php require_once("modules/header.php") ?>

    <!-- <h3>Templates</h3> -->
    <div class="templateActions">
        <span onclick="showAddTemplate()">Add</span>
        <span>/</span>
        <span onclick="showEditTemplate()">Edit</span>
    </div>

    <div class="site-options">
    
    <form action="#" id="addTemplate" class="templateAdd">
        <h3>Add Template</h3>
        <div class="fields">
            Preview <input type="text" name="previewAdd">
            URL <input type="text" name="urlAdd">
        </div>
        <button onclick="addTemplate()" type="button">Add to DB</button>
        <span>Template added!</span>
    </form>

    <form action="#" id="templateEdit" class="templateEdit" style="display: none">
    <h3>Edit Template</h3>
        <!-- <p>Current: </p> -->
        <select name="template"></select>
        <a class="templatePreviewLink darkMode" target="_blank"></a>
        <br>

        <button class="templateDemo">Demo Site</button>
        <select name="type" id="" style="margin-right: 16px"></select>
        <a href="">Demo Site</a>

        <div class="templateDetails">
            
            <div class="detailBlock bgs">
                <label for="backgrounds"><b>Backgrounds</b></label>
                &nbsp;
                <!-- <br> -->
                <button type="button" onclick="addBackground()">Add</button>
            </div>


            <div class="colors detailBlock">
                <label for="colors"><b>Colors</b></label>
                &nbsp;
                <!-- <br> -->
                <button type="button" onclick="addColor()">Add</button>
            </div>

            <div class="detailBlock">
                <p><b>Taglines</b></p>
                <input type="checkbox" name="main">
                <label for="main" class="tagline_check">Main Tagline</label>

                <input type="checkbox" name="sub">
                <label for="sub" class="tagline_check">Sub Tagline</label>

                <br>
                <div class="special_taglines">
                    <input type="checkbox" name="second_main">
                    <label for="second_main" class="tagline_check">Second Tagline</label>

                    <input type="checkbox" name="second_sub">
                    <label for="second_sub" class="tagline_check">Second Sub Tagline</label>
                </div>
            </div>

            <div class="additionals detailBlock">
                <div class="extra">
                    <label for="css"><b>CSS</b></label>
                    <textarea name="css"cols="30" rows="10"></textarea>
                </div>
                <div class="extra">
                    <label for="js"><b>JS</b></label>
                    <textarea name="js"cols="30" rows="10"></textarea>
                </div>
            </div>
        
        </div>
        
        <div class="detailBlock">
            <input type="text" name="templateId" disabled>
            <b>Preview</b> <input type="text" name="preview">
            <b>URL</b> <input type="text" name="url">
        </div>

        <hr>

        <div class="options">
            <p class="updateSuccess darkMode">Template updated!</p>
            <div class="buttons">
                <button onclick="updateTemplate()" type="button" class="">Save Changes</button>
                <button type="button" class="deleteBtn">Delete Template</button>
            </div>
            <div class="buttons">
                <!-- <button onclick="demoSite()" type="button" class="">Demo Site</button> -->
                <!-- <button onclick="deleteTemplate()" type="button" class="">Delete Template</button> -->
            </div>
            <div class="demoLinkContainer">
                <a href="" target="_blank"></a>
            </div>
            <!-- <button onclick="demo()" type="button" disabled>Demo Site</button> -->
        </div>
    </form>
    </div>
</body>

<script src="lib/jquery/jquery-3.6.0.min.js"></script>
<script src="js/main.js"></script>
<script src="js/on-load.js"></script>
<script src="js/gui.js"></script>