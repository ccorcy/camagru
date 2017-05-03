<?php
    include("config/database.php");

    session_start();
    if ($_SESSION['log_in'] == "") {
        header("Location: login.php");
    }else if ($_POST['action'] == "" || $_POST['id'] == "") { header("Location: index.php"); } else {
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $select_users = $db->prepare('SELECT `user_vote` FROM `img` WHERE `img`.`id` = :id');
    $modify_user_vote = $db->prepare('UPDATE `img` SET `user_vote` = :usr WHERE `img`.`id` = :id');
    $add_points = $db->prepare('UPDATE `img` SET `vote` = `vote` + 1  WHERE `img`.`id` = :id');
    $remove_points = $db->prepare('UPDATE `img` SET `vote` = `vote` - 1 WHERE `img`.`id` = :id');
    $get_note = $db->prepare('SELECT `vote` FROM `img` WHERE `img`.`id` = :id');

    $str_json = file_get_contents('php://input');
    $JSON = json_decode($str_json, true);
    $select_users->execute(array(':id' => $JSON['id']));
    $res = $select_users->fetch(PDO::FETCH_ASSOC);
    if ($res['user_vote'] != "")
        $res = explode("," , $res['user_vote']);
    if (!in_array($_SESSION['log_in'], $res)) {
        if ($JSON['action'] === "add") {
            $add_points->execute(array(':id' => $JSON['id']));
            $n_res = $res['user_vote'].",".$_SESSION['log_in'];
            $modify_user_vote->execute(array(':usr' => $n_res, ':id' => $JSON['id']));
            $get_note->execute(array(":id" => $JSON['id']));
            $note = $get_note->fetch(PDO::FETCH_ASSOC);
            echo $note['vote'];
        }
        else if ($JSON['action'] === 'remove') {
            $remove_points->execute(array(':id' => $JSON['id']));
            $n_res = $res['user_vote'].",".$_SESSION['log_in'];
            $modify_user_vote->execute(array(':usr' => $n_res, ':id' => $JSON['id']));
            $get_note->execute(array(":id" => $JSON['id']));
            $note = $get_note->fetch(PDO::FETCH_ASSOC);
            echo $note['vote'];
        }
    }
    else {
    }
    }
?>
