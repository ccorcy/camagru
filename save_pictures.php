<?php
    include("config/database.php");
    session_start();
    header("Location: index.php?success=3");
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $save_pictures = $db->prepare('INSERT INTO `img` (`picture`, `user`) VALUES (:pic, :user);');
    $save_pictures->execute(array(
        ':pic' => $_POST['pic'],
        ':user' => $_SESSION['log_in']
    ));
?>
