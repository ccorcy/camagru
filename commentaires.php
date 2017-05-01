<?php
    include("config/database.php");
    session_start();

    function display_coms(){
        include("config/database.php");
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $select_coms = $db->prepare('SELECT `commentaires` FROM `img` WHERE `img`.`id` = :id');
        if ($_GET['id'] !== "") {
            $select_coms->execute(array(':id' => $_GET['id']));
            $result = $select_coms->fetch(PDO::FETCH_ASSOC);
            if ($result['commentaires'] !== "") echo "Il n'y a aucun commentaire";
            else {
                    $coms = unserialize($result['commentaires']);
                    foreach($coms as $c) {
                        echo "user: ".$c['user']."  com: ".$c['commentaire'];
                    }
                }
            }
        }

    if ($_POST['submit'] === "Submit comment" && $_POST['comment'] !== ""
        && preg_match("/^([A-Za-z0-9]){0,150}$/", $_POST['comment']) && $_POST['id'] !== ""
         && $_SESSION['log_in'] !== "") {
        $n_com = array('user' => $_SESSION['log_in'], 'commentaire' => $_POST['comment']);
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $select_coms = $db->prepare('SELECT `commentaires` FROM `img` WHERE `img`.`id` = :id');
        $update_coms = $db->prepare('UPDATE `img` SET `commentaires` = :commentaires WHERE `img`.`id` = :id');
        $select_coms->execute(array(':id' => $_GET['id']));
        $result = $select_coms->fetch(PDO::FETCH_ASSOC);
        $result = unserialize($result);
        $result[] = $n_com;
        $update_coms->execute(array(':id' => $_POST['id'], ':commentaires' => serialize($result)));
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Commentaires / Camagru</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
        <link rel="stylesheet" href="css/form.css">
    </head>
    <header>
    <div class="header">
        <h1><a href="index.php">Camagru</a></h1>
        <a id="mypic" href="mypics.php" style="margin-left: 15px;;">My pictures</a>
        <a id="gal" href="gallery.php" style="margin-right: 15px;;">Gallery</a>
    </div>
    </header>
    <body>
        <div class="commentaires">
            <?php display_coms(); ?>
        </div>
        <div class="add_com">
            <form class="form-container" action="commentaires.php" method="post">
                <div class="form-title"><h2>Comment</h2></div>
                    <div class="form-title">Your comments</div>
                    <input class="form-field" type="text" name="comment" maxlength="150" required/><br />
                    <input style="display:none;" type="text" name="id" value=<?php echo '"'.$_GET['id'].'"' ?>>
                    <div class="submit-container">
                        <input class="submit-button" type="submit" name="submit" value="Submit comment" />
                    </div>
            </form>
        </div>
    </body>
</html>
