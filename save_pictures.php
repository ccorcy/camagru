<?php
    include("config/database.php");
    session_start();

    if ($_SESSION['log_in'] === "" && $_POST['pic'] === "")
        header("Location: index.php?error=3");
    else {
    header("Location: index.php?success=3");
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $save_pictures = $db->prepare('INSERT INTO `img` (`picture`, `user`, `vote`) VALUES (:pic, :user, :vote);');
    $save_pictures->execute(array(
        ':pic' => $_POST['pic'],
        ':user' => $_SESSION['log_in'],
        ':vote' => 0
    ));}
?>
