<?php
    include("config/database.php");
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $select_coms = $db->prepare('SELECT * FROM `img` WHERE `img`.`id` = :id');
    if ($_GET['id'] !== "") {
        $select_coms->execute(array(':id' => $_GET['id']));
        $result = $select_coms->fetch(PDO::FETCH_ASSOC);
        if ($result['commentaires'] == "") echo "Il n'y a aucun commentaire";
        else {
            $coms = unserialize($result['commentaires']);
            $tab = array();
            foreach($coms as $c) {
                $tab[] = array("user" => $c['user'], "commentaire" => $c['commentaire']);
            }
            echo json_encode($tab);
        }
    }
?>
