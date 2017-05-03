<?php
    include("config/database.php");
    session_start();

    if ($_SESSION['log_in'] == ""){
        header("Location: login.php");
    }



    function is_log() {
        if ($_SESSION['log_in'] != "")
        {
            echo "<h2>Bonjour " . $_SESSION['log_in']. " !</h2><br />";
            echo "<a href='manage_account.php'>Manage account</a><br />";
        }
    }

    function display_pics(){
        require("config/database.php");
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $get_pics = $db->prepare('SELECT * FROM `img` WHERE `user` = :user ORDER BY `date` DESC;');
        $get_pics->execute(array('user' => $_SESSION['log_in']));
        $result = $get_pics->fetchAll();
        foreach ($result as $row)
        {
            echo "<img class='gal-pics' style='height:100px; width: 150px; margin: 5px' src='".$row['picture']."'/>";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Camagru</title>
        <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
    </head>
    <header>
    <div class="header">
        <h1><a href="index.php">Camagru</a></h1>
        <a href="gallery.php" style="margin-right: 15px;">Gallery</a>
        <a href='logout.php'>Logout</a>
    </div>
    </header><hr>
    <body>
        <?php if ($_GET['success'] == 1) {
        echo "<p>
            Username correctly changed !
            </p>";}
            else if ($_GET['success'] == 2) {
            echo "<p>
                Password correctly changed !
            </p>";}
            else if ($_GET['success'] == 3) {
            echo "<p>
                Picture saved !
            </p>";}
        ?>
        <center>
        <div class="main-frame" style="width: 100%;">
            <div class="left-panel">
                <div class="block">
                    <?php is_log() ?>
                </div>
            </div>
            <div class="right-panel" style="overflow:auto;">
                <?php display_pics() ?>
            </div>
            <video style="width: 30%" autoplay></video>
        </div><br /><br />
        </center>
        <center>
            <div class="main-frame">
                <div class="left-panel">
                    <div class="block">
                        <h2>Filtre</h2>
                        <p>Click on the camera to take a picture !</p>
                        <div class="filter">
                            <img id="glasses" src="filtre/glasses.png" style="width:200px;height:128px;" alt="">
                            <img id="willface" src="filtre/willface.png"style="width:200px;height:128px;" alt="" />
                            <img id="ghost" src="filtre/ghost.png" style="width:200px;height:128px;" alt="">
                        </div>
                    </div>
                </div>
                <div class="right-panel">
                    <div id="send-container">
                            <form id="myform" action="save_pictures.php" method="post">
                                <div class="block">
                                    <label for="x">X position: </label><input id="x" type="range" name="x" value="150" min="-150" max="500">
                                </div>
                                <div class="block">
                                    <label for="y">Y position: </label><input id="y" type="range" name="y" value="150" min="-150" max="500">
                                </div>
                                <input id="pic" type="text" name="pic" style="display:none" value=""/>
                                <input id="filter" type="text" name="filter" style="display:none;" value="">
                                <a href="javascript:{}" id="login" class="myButton" onclick="document.getElementById(`myform`).submit(); return false;">SAVE</a>
                            </form>
                    </div>
                </div>
                <center><img id="picture" src="" style="display:none;width:30%;"></center>
                <canvas style="display:none"></canvas><br>
                <canvas id="save" style="display:none"></canvas><br>
            </div>
        </center>


    </body>
    <footer>

    </footer>
    <script type="text/javascript">
    var     streaming = false,
            video        = document.querySelector('video'),
            canvas       = document.querySelector('canvas'),
            save         = document.querySelector('#save'),
            photo        = document.querySelector('#picture'),
            button       = document.querySelector('button'),
            pic_input    = document.querySelector('#pic'),
            filter_input = document.querySelector('#filter'),
            x = document.querySelector('#x'),
            y = document.querySelector('#y'),
            width = 640,
            height = 0;

    var     ghost = document.querySelector('#ghost'),
            willface = document.querySelector('#willface'),
            glasses = document.querySelector('#glasses');

    var     filtre = [glasses, willface, ghost];
    var     selected = -1;

    document.querySelector('#login').style.display = "none";
    var xhr = new XMLHttpRequest();

    navigator.getMedia = ( navigator.getUserMedia ||
                        navigator.webkitGetUserMedia ||
                        navigator.mozGetUserMedia ||
                        navigator.msGetUserMedia);

    navigator.getMedia(
    {
     video: true,
     audio: false
    },
    function(stream) {
     if (navigator.mozGetUserMedia) {
       video.mozSrcObject = stream;
     } else {
       var vendorURL = window.URL || window.webkitURL;
       video.src = vendorURL.createObjectURL(stream);
     }
     video.play();
    },
    function(err) {
     console.log("An error occured! " + err);
    }
    );

    video.addEventListener('canplay', function(ev){
    if (!streaming) {
     height = video.videoHeight / (video.videoWidth/width);
     video.setAttribute('width', width);
     video.setAttribute('height', height);
     canvas.setAttribute('width', width);
     canvas.setAttribute('height', height);
     save.setAttribute('width', width);
     save.setAttribute('height', height);
     streaming = true;
    }
    }, false);

    function getValue(v) {
        return v.value;
    }

    function takepicture() {
        canvas.width = width;
        canvas.height = height;
        canvas.getContext('2d').drawImage(video, 0, 0, width, height);
        save.getContext('2d').drawImage(video, 0, 0, width, height);
        var data = canvas.toDataURL('image/jpg');
        photo.setAttribute('src', data);
        photo.style.display = "inline";

        pic_input.value = data;
        canvas.getContext('2d').save();
    }

    willface.addEventListener('click', (ev) => {
        selected = 1;
        canvas.getContext('2d').drawImage(save, 0, 0, width, height);
        canvas.getContext('2d').drawImage(willface, getValue(x), getValue(y));
        var data = canvas.toDataURL('image/jpg');
        filter.value = "filtre/willface.png";
        document.querySelector('#login').style.display = "inline";
        document.querySelector('#send-container').style.display = "inline";
        document.querySelector('#login').style.display = "inline";
        photo.setAttribute('src', data);
        ev.preventDefault();
    }, false);

    ghost.addEventListener('click', (ev) => {
        selected = 2;
        canvas.getContext('2d').drawImage(save, 0, 0, width, height);
        canvas.getContext('2d').drawImage(ghost, getValue(x), getValue(y));
        var data = canvas.toDataURL('image/jpg');
        filter.value = "filtre/ghost.png";
        document.querySelector('#login').style.display = "inline";
        document.querySelector('#send-container').style.display = "inline";
        document.querySelector('#login').style.display = "inline";
        photo.setAttribute('src', data);
        ev.preventDefault();
    }, false);

    x.addEventListener('change', (ev) => { ev.preventDefault(); filtre[selected].click();},false);
    y.addEventListener('change', (ev) => { ev.preventDefault(); filtre[selected].click();},false);

    glasses.addEventListener('click', (ev) => {
        selected = 0;
        canvas.getContext('2d').drawImage(save, 0, 0, width, height);
        canvas.getContext('2d').drawImage(glasses, getValue(x), getValue(y));
        var data = canvas.toDataURL('image/jpg');
        filter.value = "filtre/glasses.png";
        document.querySelector('#login').style.display = "inline";
        document.querySelector('#send-container').style.display = "inline";
        document.querySelector('#login').style.display = "inline";
        photo.setAttribute('src', data);
        ev.preventDefault();
    }, false);

    video.addEventListener('click', (ev) => {
     takepicture();
    ev.preventDefault();
    }, false);
    </script>
</html>
