<?php require_once("modules/init_config.php") ?>
<?php // print_r($_COOKIE); ?>

<link href="lib/bootstrap/bootstrap.min.css" rel="stylesheet">
<script src="lib/bootstrap/bootstrap.bundle.min.js"></script>
<link href="css/index2.css" rel="stylesheet">

<body id="home">
<header class="p-3 bg-primary text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-4 align-items-center d-flex">
                <h3>Website Express</h3>
            </div>

            <div class="col-md-8 d-flex justify-content-md-end">
                <ul class="nav">
                <!-- <li><a href="./" class="nav-link px-2 text-white">Home</a></li> -->

                <li><a href="#" class="nav-link px-3 text-white">Products</a></li>
                <!-- <li><a href="#" class="nav-link px-3 text-white">App Configuration</a></li> -->
                <li><a href="#" class="nav-link px-3 text-white">Admin</a></li>
                
                <li class="nav-item dropdown">
                    <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        App Configuration
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Templates</a></li>
                        <li><a class="dropdown-item" href="#">Backgrounds</a></li>
                        <li><a class="dropdown-item" href="#">Colors</a></li>
                        <li><a class="dropdown-item" href="#">Taglines</a></li>
                        <li><a class="dropdown-item" href="#">Types</a></li>
                        <li><a class="dropdown-item" href="#">Return Address</a></li>
                    </ul>
                </li>
                </ul>
            </div>
        </div>
    </div>
</header>
    
<div class="container">
    <form action="#" id="mainForm">
    <div class="row p-3">
        <div class="col  my-4">
            <h3 class="mb-4">Client Configuration</h3>
            <input name="url" class="form-control w-75" type="text" placeholder="URL" aria-label="default input example">
            <input name="phone" class="form-control w-75" type="text" placeholder="Phone Number" aria-label="default input example">
            <input name="corp" class="form-control  w-75" type="text" placeholder="Corporation" aria-label="default input example">
            <input name="address" class="form-control  w-75" type="text" placeholder="Client Address" aria-label="default input example">

            <!-- <select class="form-select">
                <option selected>Open this select menu</option>
                <option value="1">One</option>
            </select> -->

            <!-- <p>Descriptor:</p> -->
            <div class="d-flex align-items-center">
                <select name="descriptorType" class="form-select w-50 border border-primary" id="descriptor-select">
                    <!-- <option selected>Descriptor</option> -->
                    <option value="alphanumeric">Alphanumeric descriptor</option>
                    <option value="dba">DBA descriptor</option>
                    <option value="url">URL descriptor</option>
                    <option value="dashed">Dashed descriptor</option>
                    <option value="spaced">Spaced descriptor</option>
                </select>
                <p class="ms-3 mb-0" id="descriptor-target"></p>
            </div>
            <br>

            <!-- <p>Return address:</p> -->
            <div class="d-flex align-items-center">
                <select name="returnAddress" class="form-select w-50 border border-primary" id="return-address-select">
                    <!-- <option selected>Return Address</option> -->
                </select>
                <p class="ms-3 mb-0 text-truncate w-50" id="return-address-target"></p>
            </div>

        </div>

        <div class="col my-4">
            <h3 class="mb-4">Website Configuration</h3>
            
            <!-- <p>Niche:</p> -->
            <select name="type" class="form-select w-50 border border-primary" id="niche-select">
                <!-- <option selected>Niche</option> -->
            </select>
            <br>

            <!-- <p>Products:</p> -->
            <div class="d-flex align-items-center">
            <select name="products" class="form-select w-50 border border-primary" id="products-select">
                <!-- <option selected>Products</option> -->
                <option value="dont" selected>Don't include products</option>
                <option value="upload">Upload products</option>
            </select>
            <input class="form-control mb-0 w-50 ms-3" type="file" id="formFileDirectory" webkitdirectory>
            </div>
            <br>

            <!-- <p>Template:</p> -->
            <div class="d-flex align-items-center">
                <select name="template" class="form-select w-50  border border-primary" id="template-select">
                    <!-- <option selected>Template</option> -->
                </select>
                <button type="button" class="btn btn-white ms-3 border border-primary" onclick="selectRandomTemplate()">Random</button>
                <a class="ms-3" href="#" id="target-template-url">
                    Preview
                </a>
            </div>    
            <br>

            
        </div>
    </div>

    <hr>

    <div class="row p-3">
        <div class="col">
            <button type="button" class="btn btn-primary" onclick="create()">Create Website</button>
            <button type="button" class="btn btn-primary" disabled>Download</button>
            <br>
            <!-- <a href="" id="target-url" class="d-block mt-3">MyWebsite.com</a> -->
        </div>
        <div class="col"></div>
    </div>
    </form>
</div>
</body>


<script src="lib/jquery/jquery-3.6.0.min.js"></script>
<script src="js/generateId.js"></script>
<script src="js/index2.js"></script>
<script src="https://kit.fontawesome.com/1f6c105efb.js" crossorigin="anonymous"></script>