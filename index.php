<?php require_once("modules/init_config.php") ?>
<?php // print_r($_COOKIE); ?>

<!-- <link rel="stylesheet" href="css/products.css"> -->
<link rel="stylesheet" href="css/style.css">

<body class="darkMode">
    <?php require_once("modules/header.php") ?>

    <form action="#" id="mainForm" class="mainForm">
        <h3>
            <!-- Sites info  -->
            <!-- <button type="button" onclick="addSite()" class="addBtn">+</button> -->
        </h3>
        <div class="main_form_container site-options" style="display: block">
            
            <div class="theOptions" style="display: flex">

            <div class="top_form">
                <h3>Site details</h3>
                <div class="sites">
                    <div class="site_details">
                        <input type="text" name="url" placeholder="URL" onkeyup="setDescriptor()">
                        <br>
                        <input type="text" name="phone" placeholder="Phone" onkeyup="setDescriptor()">
                        <br>
                        <label for="descriptor"><b>Descriptor:</b> </label>
                        <select id="descriptorType" name="descriptorType" onchange="setDescriptor()">
                            <option value="alphanumeric">Alfanumeric</option>
                            <option value="dba">DBA</option>
                            <option value="url">URL</option>
                            <option value="dashed">Dashed</option>
                            <option value="spaced">Spaced</option>
                        </select>
                        <input type="hidden" name="descriptor">
                        <!-- <p><b>Example:</b> descriptor</p> -->
                        <!-- <br>
                        <input type="text" name="descriptor" id="" disabled placeholder="Descriptor"> -->
                        <!-- <br> -->
                    </div>
                </div>
            </div>
    
            <div class="top_form">
                <h3>Corp Details</h3>
                <input type="text" name="corp" placeholder="Corp">
                <br>
                <input type="text" name="address" placeholder="Address">
                <br>
                <label for="return_address"><b>Return Address:</b></label>
                <!-- <br> -->
                <select name="returnAddressSelect" onchange="setReturnAddress()">
                </select>
                <input type="hidden" name="returnAddress">
                <!-- <br><br> -->
                <!-- <p><b>Address:</b> <span class="raTarget"></span></p> -->
            </div>

            </div>

            <div class="optionDetails" style=" margin-top: 3rem">
                <p><b style="text-decoration: underline;">Descriptor Example:</b> <span class="descTarget"></span></p>
                <p><b style="text-decoration: underline;">Return Address:</b> <span class="raTarget"></span></p>
            </div>
        </div>
        
        <!-- <hr> -->

        <h3>
            <!-- Signer Info  -->
            <!-- <button type="button" onclick="addSigner()" class="addBtn">+</button> -->
        </h3>


        <!-- <hr> -->

        <div class="template_configuration site-options">
            <div class="configuration">
                <h3>Template Configuration</h3>
                <label for="type">Type: </label>
                <select name="type"></select>
                <!-- <label for="prices">Prices: </label>
                <select name="prices"></select> -->
                <br style="margin-bottom: 2rem">

                <label for="template">Template: </label>
                <select name="template" id="templateSelect"></select>
                <!-- <input type="text" name="template" placeholder="Template URL"> -->
                <a class="templatePreviewLink" target="_blank"></a>
                <button onclick="selectRandTemplate()" type="button" class="randBtn">&#128256;</button>
                <br style="margin-bottom: 2rem">
    
                <div class="productOptions">
                    <div class="productIncludeOptions">
                        <label for="products">Products: </label>
                        <select name="products" id="productsSelect">
                            <option value="dont">Dont include</option>
                            <!-- <option value="include">Include</option> -->
                            <option value="upload">Upload</option>
                        </select>
                    </div>  
                    <br>
    
                    <div class="productsDetails">
                        <!-- <label for="prices">Prices: </label>
                        <select name="prices"></select> -->
    
                        <button onclick="getBatch()" type="button">New Batch</button>
                    </div>
            
                    <div class="uploadFiles">
                        <label for="productsFiles">Select products folder: </label>
                        <br>
                        <input name="productsFiles" type="file" webkitdirectory directory multiple/>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="options">
            <div class="buttons">
                <button onclick="create()" type="button" class="createDemoBtn">Create Demo</button>
                <button onclick="download()" type="button" disabled class="downloadDemoBtn">Download</button>
            </div>
            <p class="statusAnim">
                Creating site...
                <i class="fa-solid fa-cube fa-beat-fade"></i>
            </p>
            <div class="demoLinkContainer">
                <a href="" target="_blank"></a>
            </div>
            <!-- <button onclick="demo()" type="button" disabled>Demo Site</button> -->
        </div>

        <!-- Buttons -->
        <br>
        <!-- <hr> -->
        <div class="products_preview">
            <div class="console">
                <h2>Console: </h2>
                <p></p>
            </div>
            <div class="results"></div>
            <div class="container"></div>
        </div>
        <br>
        <br>
        <!-- <hr> -->
        <!-- <br> -->
    </form>
    <!-- <input type="checkbox" name="export" id="export">
    <label for="export">"export"</label>
    <br>
    <textarea name="" id="target" cols="100" rows="10"></textarea> -->
</body>

<script src="lib/jquery/jquery-3.6.0.min.js"></script>
<script src="js/generateId.js"></script>
<script src="js/products.js"></script>
<script src="js/main.js"></script>
<script src="js/on-load.js"></script>
<script src="js/home.js"></script>
<script src="js/gui.js"></script>
<script src="https://kit.fontawesome.com/1f6c105efb.js" crossorigin="anonymous"></script>