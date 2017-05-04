<?php
    session_start();
    include("config/database.php");

    $db = new PDO($DB_DSN, $DB_USERNAME, $DB_PASSWORD);

    $select_img = $db->prepare('SELECT * FROM `img` WHERE `img`.`id` = :id;');
    $delete_pics = $db->prepare('DELETE FROM `img` WHERE `img`.`id` = :id');
    $select_img->execute(array(":id" => $_GET['id']));
    $result = $select_img->fetch(PDO::FETCH_ASSOC);
    if ($result['user'] == $_SESSION['log_in']) {
        $delete_pics->execute(array(":id" => $_GET['id']));
        echo "OK";
    } else { echo "ERROR"; }
?>
