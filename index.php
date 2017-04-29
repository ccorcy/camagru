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
            echo "Bonjour " . $_SESSION['log_in']. " !<br />";
            echo "<a href='manage_account.php'>Manage account</a><br />";
            echo "<a href='logout.php'>Logout</a>";
        }
        else {
            echo '<form class="login" action="index.php" method="post"> <label for="username">Username: </label><input type="text" name="username" value="" placeholder="Username"><br>
                <label for="password">Password: </label><input type="password" name="password" value="" placeholder="password">
                <input type="submit" name="login" value="Login">
            </form>
            <a href="register.php">No account ? Register now</a>';
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
        <h1>Camagru</h1>
    </header>
    <body>
        <div class="left-panel">
            <div class="block">
                <?php is_log() ?>
            </div>
        </div>
            <center>
                <video autoplay></video>
            </center>
        <hr>
        <center><img src=""></center>
        <canvas style="display:none"></canvas>
        <input type="button" name="send" value="Send">
    </body>
    <footer>

    </footer>
    <script type="text/javascript">
        var streaming = false,
     video        = document.querySelector('video'),
     canvas       = document.querySelector('canvas'),
     photo        = document.querySelector('img'),
     button       = document.querySelector('button'),
     width = 640,
     height = 0;

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
    }

    video.addEventListener('click', function(ev){
     takepicture();
    ev.preventDefault();
    }, false);
    button.addEventListener('click', function(ev){
     takepicture();
    ev.preventDefault();
    }, false);

    </script>
</html>
