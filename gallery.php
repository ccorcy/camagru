<?php
require("config/database.php");

$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
function display_pics(){
require("config/database.php");
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $get_pics = $db->prepare('SELECT * FROM `img`;');
    $get_pics->execute();
    $result = $get_pics->fetchAll();
    foreach ($result as $row)
    {
        echo "<img class='gal-pics' style='height:200px; width: 300px;' src='".$row['picture']."'/>";
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
        <a href="mypics.php" style="margin-left: 15px;;">My pictures</a>
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
