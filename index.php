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
        <div class="main_frame">
            <center>
                <video autoplay></video>
            </center>
        </div>
        <img src="">
        <canvas style="display:none; width:350px; height:250px;"></canvas>
    </body>
    <footer>

    </footer>
    <script type="text/javascript">

    function hasGetUserMedia() {
        return !!(navigator.getUserMedia || navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia || navigator.msGetUserMedia);
    }

    if (hasGetUserMedia()) {
        var errorCallback = function(e) {
          console.log('Reeeejected!', e);
        };
        navigator.getUserMedia({video: true, audio: true}, function(localMediaStream) {
          var video = document.querySelector('video');
          video.src = window.URL.createObjectURL(localMediaStream);
          video.onloadedmetadata = function(e) {
            // Ready to go. Do some stuff.
          };
        }, errorCallback);

        var video = document.querySelector('video');
        var canvas = document.querySelector('canvas');
        var ctx = canvas.getContext('2d');
        var localMediaStream = null;

        function snapshot() {
            if (localMediaStream) {
            ctx.drawImage(video, 0, 0);
            document.querySelector('img').src = canvas.toDataURL('image/png');
            }
        }

        video.addEventListener('click', snapshot, false);
        navigator.getUserMedia({video: true}, function(stream) {
        video.src = window.URL.createObjectURL(stream);
        localMediaStream = stream;
        }, errorCallback);

        var idx = 0;
        var filters = ['grayscale', 'sepia', 'blur', 'saturate', 'invert', ''];

        function changeFilter(e) {
          var el = e.target;
          el.className = '';
          var effect = filters[idx++ % filters.length]; // loop through filters.
          if (effect) {
            el.classList.add(effect);
          }
        }

        document.querySelector('img').addEventListener(
            'click', changeFilter, false);
            }
    </script>
</html>
