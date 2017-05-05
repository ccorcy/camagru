<?php
    include("config/database.php");
    session_start();
    header("Location:index.php");
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $db->query('USE `camagru`;');
    $delete_usr = $db->prepare('DELETE FROM `user` WHERE `user`.`username` = :usr;');
    $delete_pics = $db->prepare('DELETE FROM `img` WHERE `user` = :usr;');
    $delete_usr->execute(array(':usr' => $_SESSION['log_in']));
    $delete_pics->execute(array(':usr' => $_SESSION['log_in']));
    $_SESSION['log_in'] = "";
?>
