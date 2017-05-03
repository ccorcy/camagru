<?php
require("config/database.php");
session_start();
if ($_SESSION['log_in'] == "") header("Location: login.php");

$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
function display_pics(){
require("config/database.php");
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $get_pics = $db->prepare('SELECT * FROM `img` ORDER BY `date` DESC;');
    $get_pics->execute();
    $result = $get_pics->fetchAll();
    foreach ($result as $row)
    { ?>
        <div class="gallery">
            <img class="gal-pics" src=<?php echo '"'.$row['picture'].'"' ?> width="600" height="200">
                <div class="desc">User: <?php echo $row['user'];?><br />
                Notation: <span id=<?php echo '"'.$row['id'].'"' ?>><?php echo $row['vote']; ?></span>
                <input type="button" value="+" class="plus" id=<?php echo "'".$row['id']."'"; ?>>
                <input type="button" value="-" class="moins" id=<?php echo "'".$row['id']."'"; ?>><br />
                <a href=<?php echo '"commentaires.php?id='.$row['id'].'"'; ?>>Commentaires</a></div><br />
                date: <?php echo $row['date']; ?>
            </div>
    <?php }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Gallery / Camagru</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    </head>
    <header>
    <div class="header">
        <h1><a href="index.php">Camagru</a></h1>
        <a href="" style="margin-right: 15px;;">Gallery</a>
        <a href='logout.php'>Logout</a>
    </div>
    </header>
    <body>
        <center>
            <div class="container">
                <?php display_pics(); ?>
            </div>
        </center>
    <script type="text/javascript">
        const xhr = new XMLHttpRequest();
        const plus = document.getElementsByClassName("plus");
        const moins = document.getElementsByClassName("moins");

        function sendVotePositive(data){
            let obj = {"id":data.id,
                        "action": "add"};
            xhr.open('POST', 'vote.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
            xhr.onload = () => {
            if (xhr.status === 200 && xhr.readyState === 4) {
                }
            }
            xhr.send(JSON.stringify(obj));
            xhr.onload = () => {
                if (xhr.responseText != "") {
                    document.getElementById(data.id).innerHTML = xhr.responseText;
                }
            }
        };
        function sendVoteNegative(data){
            let obj = {"id":data.id,
                    "action": "remove"};
            xhr.open('POST', 'vote.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
            xhr.onload = () => {
                if (xhr.status === 200 && xhr.readyState === 4) {
                }
            }
            xhr.send(JSON.stringify(obj));
            xhr.onload = () => {
                if (xhr.status === 200 && xhr.readyState === 4) {
                    if (xhr.responseText != "")
                        document.getElementById(data.id).innerHTML = xhr.responseText;
                }
            }
        };

        for (let i = 0; i < plus.length; i++) {
            plus[i].addEventListener('click', () => { sendVotePositive(plus[i]) }, false);
        }
        for (let i = 0; i < moins.length; i++) {
            var truc = "bonjour";
            moins[i].addEventListener('click', () => { sendVoteNegative(moins[i]) } , false);
        }
    </script>
    </body>
</html>
