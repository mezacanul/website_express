
<?php require_once("modules/init_config.php") ?>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/backgrounds.css">

<body class="darkMode">
    <?php require_once("modules/header.php") ?>

    <!-- <h3>Templates</h3> -->
    <div class="actions">
        <span onclick="showAddBg()">Add</span>
        <span>/</span>
        <span onclick="showEditBg()">List</span>
    </div>

    <div class="site-options">
    
        <form action="#" class="bgAdd">
            <h3>Add Background</h3>
            <div class="fields">
                <select name="bgType" id=""></select>
                <input type="file" name="bgAdd">
            </div>

            <button onclick="addBg()" type="button">Add to DB</button>
            <span>Background added!</span>
        </form>

        <div class="bgPreview">
            <img src="files/img/backgrounds/gadget_wj56.jpg" alt="">
            <!-- <img src="" alt=""> -->
        </div>

    </div>
</body>

<script src="lib/jquery/jquery-3.6.0.min.js"></script>
<script src="js/main.js"></script>
<script src="js/on-load.js"></script>
<script src="js/backgrounds.js"></script>
<script src="js/gui.js"></script>