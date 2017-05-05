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
        $db->query('USE `camagru`;');
        $get_pics = $db->prepare('SELECT * FROM `img` WHERE `user` = :user ORDER BY `date` DESC;');
        $get_pics->execute(array('user' => $_SESSION['log_in']));
        $result = $get_pics->fetchAll();
        foreach ($result as $row)
        {
            echo "<img id='".$row['id']."' class='gal-pics' style='width:30%; margin: 5px' src='".$row['picture']."'/>";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Camagru</title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
    </head>
    <header>

    <body>
        <div class="header">
            <h1><a href="index.php">Camagru</a></h1>
            <a href="gallery.php" style="margin-right: 15px;">Gallery</a>
            <a href='logout.php'>Logout</a>
        </div>
        </header><hr>
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
                    <input type="file" id="cust_pic" accept="image/*">
                </div>
            </div>
            <div class="right-panel" style="overflow:auto;">
                <p>Click on a picture to delete it</p>
                <?php display_pics() ?>
            </div>
            <video style="width: 30%" autoplay></video>
        </div><br /><br />
        </center>
    <hr>
        <center>
            <div class="main-frame" style="width:100%">
                <div class="left-panel">
                    <div class="block">
                        <h2>Filtre</h2>
                        <p>Click on the camera to take a picture !</p>
                        <div class="filter">
                            <img id="glasses" src="filtre/glasses.png" style="width:30%" alt="">
                            <img id="willface" src="filtre/willface.png"style="width:30%" alt="" />
                            <img id="ghost" src="filtre/ghost.png" style="width:30%" alt="">
                        </div>
                    </div>
                </div>
                <div class="right-panel" style="text-align:left">
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
    let     streaming = false,
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

    const     ghost = document.querySelector('#ghost'),
            willface = document.querySelector('#willface'),
            glasses = document.querySelector('#glasses');

    let     filtre = [glasses, willface, ghost];
    let     selected = -1;

    let cust_pic = document.getElementById('cust_pic');
    let gal_pics = document.getElementsByClassName("gal-pics");
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

    }
    );

    cust_pic.addEventListener('change', (e) => {
        let listFiles = this.files;
    }, false);

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
        if (streaming === true) {
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
    }

    willface.addEventListener('click', (ev) => {
        selected = 1;
        canvas.getContext('2d').drawImage(save, 0, 0, width, height);
        canvas.getContext('2d').drawImage(willface, getValue(x), getValue(y));
        var data = canvas.toDataURL('image/jpg');
        filter.value = "filtre/willface.png";
        document.querySelector('#login').style.display = "inline";
        document.querySelector('#send-container').style.display = "inline";
        if (photo.src != "")
            document.querySelector('#send-container').style.display = "inline";
        photo.setAttribute('src', data);
        willface.style = "box-shadow: 2px 2px 15px black; width: 30%";
        ghost.style = "box-shadow: 0px black; width: 30%";
        glasses.style = "box-shadow: 0px black; width: 30%";
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
        if (photo.src != "")
            document.querySelector('#send-container').style.display = "inline";
        photo.setAttribute('src', data);
        ghost.style = "box-shadow: 2px 2px 15px black; width: 30%";
        glasses.style = "box-shadow: 0px black; width: 30%";
        willface.style = "box-shadow: 0px black; width: 30%";
        ev.preventDefault();
    }, false);

    x.addEventListener('input', (ev) => { ev.preventDefault(); filtre[selected].click();},false);
    y.addEventListener('input', (ev) => { ev.preventDefault(); filtre[selected].click();},false);

    glasses.addEventListener('click', (ev) => {
        selected = 0;
        canvas.getContext('2d').drawImage(save, 0, 0, width, height);
        canvas.getContext('2d').drawImage(glasses, getValue(x), getValue(y));
        var data = canvas.toDataURL('image/jpg');
        filter.value = "filtre/glasses.png";
        document.querySelector('#login').style.display = "inline";
        if (photo.src != "")
            document.querySelector('#send-container').style.display = "inline";
        photo.setAttribute('src', data);
        glasses.style = "box-shadow: 2px 2px 15px black; width: 30%";
        ghost.style = "box-shadow: 0px black; width: 30%";
        willface.style = "box-shadow: 0px black; width: 30%";
        ev.preventDefault();
    }, false);

    video.addEventListener('click', (ev) => {
     takepicture();
    ev.preventDefault();
    }, false);

    function deletePics(data) {
        if (window.confirm("Are you sure that you want to delete this picture ?") === true) {
        xhr.open('GET', 'delete_img.php?id=' + data.id, true);
        xhr.send();
        xhr.onload = () => {
            if (xhr.status === 200 && xhr.readyState === 4) {
                if (xhr.responseText === "OK") {
                    data.style.display = "none";
                }
            }
        }
        }
    }

    for (let i = 0; i < gal_pics.length; i++) {
        gal_pics[i].addEventListener('click', (ev) => { ev.preventDefault(); deletePics(gal_pics[i]); }, false);
    }

    </script>
</html>
