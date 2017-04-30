<?php
    include("config/database.php");
    session_start();

    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

    $select_users = $db->prepare('SELECT * FROM `user` WHERE `username` = :username');
    if ($_POST['login'] === "Login")
    {
        $select_users->execute(array('username' => $_POST['username']));
        $result = $select_users->fetch(PDO::FETCH_ASSOC);
        if ($_POST['username'] == $result['username'] && hash("whirlpool", $_POST['password']) == $result['password'])
        {
            if ($result['confirmed'] != 0)
                $_SESSION['log_in'] = $result['username'];
            else {
                echo "You must validate your account using the link in the mail that have been sent to you.";
            }
        }
        else {
            echo "Invalid Username or Password";
        }
    }

    function is_log() {
        if ($_SESSION['log_in'] !== "")
        {
            echo "<h2>Bonjour " . $_SESSION['log_in']. " !</h2><br />";
            echo "<a href='manage_account.php'>Manage account</a><br />";
            echo "<a href='logout.php'>Logout</a>";
        }
        else {
            echo '<form class="login" action="index.php" method="post"> <label for="username">Username: </label><input type="text" name="username" value="" placeholder="Username"><br>
                <label for="password">Password: </label><input type="password" name="password" value="" placeholder="password"><br />
                <input id="login" type="submit" name="login" value="Login"><br />
                <a href="register.php">No account ? Register now</a>
            </form>'
            ;
        }
    }

    function display_save() {
        if ($_SESSION['log_in'] !== "")
        {
            echo '<div id="send-container">
                    <form id="myform" action="save_pictures.php" method="post">
                        <input id="pic" type="text" name="pic" style="display:none" value=""/>
                        <a href="javascript:{}" id="login" style="display:none" class="myButton" onclick="document.getElementById(`myform`).submit(); return false;">SAVE</a>
                    </form>
                </div>';
        }
        else {
            echo "You must be logged to save your pictures.";
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
    </head>
    <header>
    <div class="header">
        <h1><a href="index.php">Camagru</a></h1>
        <a id="mypic" href="mypics.php" style="margin-left: 15px;;">My pictures</a>
        <a id="gal" href="gallery.php" style="margin-right: 15px;;">Gallery</a>
    </div>
    </header>
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
            </p>";
            }
        ?>
        <center>
        <div style="width: 1100px;">
            <div class="left-panel">
                <div class="block">
                    <?php is_log() ?>
                </div>
            </div>
            <div class="right-panel">
                <div class="block">
                    <h2>Instructions</h2>
                    <p>Click on the camera to take a picture !</p>
                    <p>You must be logged to save your picture</p>
                </div>
            </div>
                <center>
                    <video autoplay></video>
                </center>

        </div>
        </center>
        <hr>
        <center><img src=""></center>
        <canvas style="display:none"></canvas><br>
        <?php display_save() ?>
    </body>
    <footer>

    </footer>
    <script type="text/javascript">
    var     streaming = false,
            video        = document.querySelector('video'),
            canvas       = document.querySelector('canvas'),
            photo        = document.querySelector('img'),
            button       = document.querySelector('button'),
            pic_input    = document.querySelector('#pic');
            width = 640,
            height = 0;


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
        document.querySelector('#login').style.display = "inline";
        document.querySelector('#send-container').style.display = "inline";
        pic_input.value = data;
    }

    video.addEventListener('click', function(ev){
     takepicture();
    ev.preventDefault();
    }, false);

    </script>
</html>
