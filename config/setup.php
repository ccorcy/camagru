<?php
    include("database.php");

    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $create_user = $db->prepare('CREATE TABLE `user` (
    	`id` int AUTO_INCREMENT NOT NULL,
    	`username` varchar(256) NOT NULL,
    	`password` varchar(256) NOT NULL,
    	`mail` varchar(256) NOT NULL,
        `confirmed` int,
    	PRIMARY KEY (`id`)
    );');
    $create_img = $db->prepare('CREATE TABLE `img` (
        `id` int AUTO_INCREMENT NOT NULL,
        `picture` LONGTEXT NOT NULL,
        `vote` int,
        `user` varchar(256) NOT NULL,
        `commentaires` MEDIUMTEXT,
        PRIMARY KEY (`id`)
    );');
    $create_user->execute();
    $create_img->execute();
?>
