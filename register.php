<?php
    include("config/database.php");
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $insert_user = $db->prepare('INSERT INTO `user` (username, password, mail, confirmed) VALUES (:username, :password, :mail, :confirmed);');
    if ($_POST['Register'] == "Register")
    {
        if ($_POST['username'] !== "" && $_POST['password'] !== "" && $_POST['password'] === $_POST['vpassword'] && $_POST['mail'] !== ""
            && preg_match("/^([A-Za-z0-9]){4,15}$/", $_POST['username']) && preg_match("/^([A-Za-z0-9]){4,15}$/", $_POST['password']))
        {
            $password = hash("whirlpool", $_POST['password']);
            $insert_user->execute(array(':username' => $_POST['username'],
                                        ':password' => $password,
                                        ':mail' => $_POST['mail'],
                                        ':confirmed' => 0));
            $to = $_POST['mail'];
            $subject = 'Camagru Signup | Verification ';
            $message = '
                Thanks for signing up!
            Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.

            ------------------------
            Username: '.$_POST['username'].'
            Password: '.$_POST['password'].'
            ------------------------

            Please click this link to activate your account:
            http://localhost:8080/camagru/verify.php?mail='.$_POST['mail'].'&password='.$password.'
            ';
            $headers = 'From:noreply@camagru.com' . "\r\n";
            mail($to, $subject, $message, $headers);
            header("Location: login.php?success=1");
        }
        else {
            echo "<h3>Invalid username or password</h3>";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
        <meta charset="utf-8">
        <title>Register / Camagru</title>
    </head>
    <header>
    <div class="header">
        <h1><a href="index.php">Camagru</a></h1>
        <a id="gal" href="gallery.php" style="margin-right: 15px;;">Gallery</a>
    </div>
    </header>
    <body>
        <div class="cont">
            <form class="form-container" action="register.php" method="post">
                <div class="form-title"><h2>Register</h2></div>
                    <div class="form-title">Username</div>
                    <input class="form-field" type="text" name="username" required/><br />
                    <div class="form-title">Email</div>
                    <input class="form-field" type="text" name="mail" required/><br />
                    <div class="form-title">Password</div>
                    <input class="form-field" type="password" name="password" required/><br />
                    <div class="form-title">Verify password</div>
                    <input class="form-field" type="password" name="vpassword" required/><br />
                    <div class="submit-container">
                        <input class="submit-button" type="submit" name="Register" value="Register" />
                    </div>
            </form>
        </div>
    </body>
</html>
