<?php
    include("config/database.php");

    header("Location: gallery.php");
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $add_points = $db->prepare('UPDATE `img` SET `vote` = `vote` + 1 WHERE `img`.`id` = :id');
    $remove_points = $db->prepare('UPDATE `img` SET `vote` = `vote` - 1 WHERE `img`.`id` = :id');
    if ($_GET['action'] == "add") {
        $add_points->execute(array(':id' => $_GET['id']));
    }
    else if ($_GET['action'] == 'remove') {
        $remove_points->execute(array(':id' => $_GET['id']));
    }
?>
