<?php
include("config/database.php");
header("Location: login.php");
$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$db->query('USE `camagru`;');
$activate = $db->prepare("UPDATE `user` SET `confirmed` = 1 WHERE `mail` = '" . $_GET['mail']."' AND `password` = '".$_GET['password']."';");
$activate->execute();
?>
