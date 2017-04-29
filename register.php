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
            http://www.localhost:8080/camagru/verify.php?mail='.$_POST['mail'].'&password='.$password.'
            ';
            $headers = 'From:noreply@camagru.com' . "\r\n";
            mail($to, $subject, $message, $headers);
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
        <meta charset="utf-8">
        <title>Register / Camagru</title>
    </head>
    <body>
        <div class="block register">
            <form action="register.php" method="post">
                <label for="username">Username: </label><input type="text" name="username" value="" placeholder="Username"><br>
                <label for="mail">E-Mail address: </label><input type="<strong>email</strong>" name="mail" value="" placeholder="yourmail@xxx.xxx"><br>
                <label for="password">Password: </label><input type="password" name="password" value=""><br>
                <label for="vpassword">Verify Password: </label><input type="password" name="vpassword" value=""><br>
                <input type="submit" name="Register" value="Register">
            </form>
        </div>
    </body>
    <footer>
        <a href="index.php">Back to main page</a>
    </footer>
</html>
