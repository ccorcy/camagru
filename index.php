<?php
    include("config/database.php");
    session_start();

    if ($_SESSION['log_in'] === ""){
        header("Location: login.php");
    }



    function is_log() {
        if ($_SESSION['log_in'] !== "")
        {
            echo "<h2>Bonjour " . $_SESSION['log_in']. " !</h2><br />";
            echo "<a href='manage_account.php'>Manage account</a><br />";
            echo "<a href='logout.php'>Logout</a>";
        }
        else {
            echo '<form class="login" action="index.php" method="post"> <label for="username">Username: </label><input class="form-field"type="text" name="username" value="" placeholder="Username"><br>
                <label for="password">Password: </label><input class="form-field" type="password" name="password" value="" placeholder="password"><br />
                <div class="submit-container">
                    <input class="submit-button" type="submit" name="login" value="Login" />
                </div><br />
                <a href="register.php">No account ? Register now</a>
            </form>'
            ;
        }
    }

    function display_save() {
        if ($_SESSION['log_in'] !== "")
        {
            echo 'display="inline"';
        }
        else {
            echo 'display="none"';
        }
    }

    function display_pics(){
        require("config/database.php");
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $get_pics = $db->prepare('SELECT * FROM `img` WHERE `user` = :user ORDER BY `date` DESC LIMIT 5;');
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
        <a id="gal" href="gallery.php" style="margin-right: 15px;">Gallery</a>
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
            <div class="right-panel">
                <?php display_pics() ?>
            </div>
                <center>
                    <video style="width: 40%" autoplay></video>
                </center>

        </div><br /><br />
        </center>
        <div class="">
            <div>
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
            <center><img id="picture" src="" style="display:none"></center>
            <canvas style="display:none"></canvas><br>
            <div id="send-container">
                    <form id="myform" action="save_pictures.php" method="post">
                        <input id="pic" type="text" name="pic" style="display:none" value=""/>
                        <a href="javascript:{}" id="login" <?php display_save() ?> class="myButton" onclick="document.getElementById(`myform`).submit(); return false;">SAVE</a>
                    </form>
            </div>
        </div>

    </body>
    <footer>

    </footer>
    <script type="text/javascript">
    var     streaming = false,
            video        = document.querySelector('video'),
            canvas       = document.querySelector('canvas'),
            photo        = document.querySelector('#picture'),
            button       = document.querySelector('button'),
            pic_input    = document.querySelector('#pic'),
            width = 640,
            height = 0;

    var     ghost = document.querySelector('#ghost'),
            willface = document.querySelector('#willface'),
            glasses = document.querySelector('#glasses');

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
     streaming = true;
    }
    }, false);

    function takepicture() {
        canvas.width = width;
        canvas.height = height;
        canvas.getContext('2d').drawImage(video, 0, 0, width, height);
        var data = canvas.toDataURL('image/jpg');
        photo.setAttribute('src', data);
        photo.style.display = "inline";
        document.querySelector('#login').style.display = "inline";
        document.querySelector('#send-container').style.display = "inline";
        document.querySelector('#login').style.display = "inline";
        pic_input.value = data;
    }

    willface.addEventListener('click', (ev) => {
        canvas.getContext('2d').drawImage(willface, 235, 0);
        var data = canvas.toDataURL('image/jpg');
        photo.setAttribute('src', data);
        pic_input.value = data;
    ev.preventDefault();
    }, false);

    ghost.addEventListener('click', (ev) => {
        canvas.getContext('2d').drawImage(ghost, 95, 26);
        var data = canvas.toDataURL('image/jpg');
        photo.setAttribute('src', data);
        pic_input.value = data;
    ev.preventDefault();
    }, false);

    glasses.addEventListener('click', (ev) => {
        canvas.getContext('2d').drawImage(glasses, 235, 25);
        var data = canvas.toDataURL('image/jpg');
        photo.setAttribute('src', data);
    ev.preventDefault();
    }, false);

    video.addEventListener('click', function(ev){
     takepicture();
    ev.preventDefault();
    }, false);

    </script>
</html>
