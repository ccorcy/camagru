<?php
    include("config/database.php");
    session_start();

    if ($_POST['comment'] !== ""
         && $_SESSION['log_in'] !== "") {
        $n_com = array('user' => $_SESSION['log_in'], 'commentaire' => $_POST['comment']);
        $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $select_coms = $db->prepare('SELECT `commentaires` FROM `img` WHERE `img`.`id` = :id');
        $update_coms = $db->prepare('UPDATE `img` SET `commentaires` = :commentaires WHERE `img`.`id` = :id');
        $select_coms->execute(array(':id' => $_GET['id']));
        $result = $select_coms->fetch(PDO::FETCH_ASSOC);
        $result = unserialize($result['commentaires']);
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
        <a id="gal" href="gallery.php" style="margin-right: 15px;">Gallery</a>
    </div>
    </header>
    <body>
        <div>
            <div id="comment" class="commentaires" style="float:left;">

            </div>
            <div class="add_com">
                <form id="com" class="form-container" style="float:left;" action=<?php echo '"commentaires.php?id='.$_GET['id'].'";' ?> method="post">
                    <div class="form-title"><h2>Comment picture</h2></div>
                        <div class="form-title">Your comments</div>
                        <textarea class="form-field" name="comment" rows="8" cols="80" required></textarea><br />
                        <input style="display:none;" type="text" name="id" value=<?php echo '"'.$_GET['id'].'"' ?>>
                        <div class="submit-container">
                            <input class="submit-button" type="submit" name="submit" value="Submit comment" />
                        </div>
                </form>
            </div>
        </div>
    <script type="text/javascript">
        const com = document.querySelector('#com');
        const xhr = new XMLHttpRequest();
        const comments = document.querySelector('#comment');


        com.addEventListener('submit', (e) => {
            e.preventDefault();
            let form = new FormData(com);
            xhr.open('POST', com.action, true);
            xhr.onload = () => {
                if (xhr.status === 200 && xhr.readyState === 4) {
                    comments.innerHTML = "";
                    xhr.open('GET', 'get_comms.php?id=' + <?php echo $_GET['id'] ?>, true);
                    xhr.onload = () => {
                        if (xhr.status === 200 && xhr.readyState === 4) {
                            let com = JSON.parse(xhr.responseText);
                            for (let i = 0; i < com.length; i++) {
                                let e = document.createElement('div');
                                let h2 = document.createElement('h2');
                                let p = document.createElement('p');
                                e.setAttribute('class', 'comments');
                                h2.setAttribute('class', 'user-comments');
                                h2.innerHTML = com[i].user;
                                p.setAttribute('class', 'comm');
                                p.innerHTML = com[i].commentaire;
                                e.appendChild(h2);
                                e.appendChild(p);
                                comments.appendChild(e);
                            }
                        }
                    }
                    xhr.send();
                }
            }
            xhr.send(form);
        }, false);

        xhr.open('GET', 'get_comms.php?id=' + <?php echo $_GET['id'] ?>, true);
        xhr.onload = () => {
            if (xhr.status === 200 && xhr.readyState === 4) {
                let com = JSON.parse(xhr.responseText);
                for (let i = 0; i < com.length; i++) {
                    let e = document.createElement('div');
                    let h2 = document.createElement('h2');
                    let p = document.createElement('p');
                    e.setAttribute('class', 'comments');
                    h2.setAttribute('class', 'user-comments');
                    h2.innerHTML = com[i].user;
                    p.setAttribute('class', 'comm');
                    p.innerHTML = com[i].commentaire;
                    e.appendChild(h2);
                    e.appendChild(p);
                    comments.appendChild(e);
                }
            }
        }
        xhr.send();
    </script>
    </body>
</html>
