<?php
    include("config/database.php");
    header("Location: index.php");
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $save_pictures = $db->prepare('INSERT INTO `img` (`picture`, `user`) VALUES (:pic, :user);');
    $save_pictures->execute(array(
        ':pic' => $_POST['pic'],
        ':user' => $_POST['user']
    ));
?>
