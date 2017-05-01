<?php
require("config/database.php");

$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
function display_pics(){
require("config/database.php");
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $get_pics = $db->prepare('SELECT * FROM `img` ORDER BY `date` DESC;');
    $get_pics->execute();
    $result = $get_pics->fetchAll();
    foreach ($result as $row)
    {
        echo '<div class="gallery">
            <img class="gal-pics" src="'.$row['picture'].'" width="600" height="200">
                <div class="desc">User: '.$row['user'].'<br />
                Notation: '.$row['vote'].'
                <a href="vote.php?id='.$row['id'].'&action=add">+</a>
                <a href="vote.php?id='.$row['id'].'&action=remove">-</a><br />
                <a href="commentaires.php?id='.$row['id'].'">Commentaires</a></div><br />
                date: '.$row['date'].'
            </div>';
    }
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
    </div>
    </header>
    <body>
        <center>
            <div class="container">
                <?php display_pics(); ?>
            </div>
        </center>
    </body>
</html>
