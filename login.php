<?php
include("config/database.php");
session_start();

$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$select_users = $db->prepare('SELECT * FROM `user` WHERE `username` = :username');
if ($_POST['login'] === "Login")
{
    $select_users->execute(array('username' => $_POST['username']));
    $result = $select_users->fetch(PDO::FETCH_ASSOC);
    if ($_POST['username'] == $result['username'] && hash("whirlpool", $_POST['password']) == $result['password'])
    {
        if ($result['confirmed'] != 0){
            $_SESSION['log_in'] = $result['username'];
            header("Location: index.php");
        }
        else {
            header("Location: login.php?error=2");
        }
    }
    else {
        header("Location: login.php?error=1");
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login / Camagru </title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/form.css">
        <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    </head>
    <body>
        <form class="form-container" action="login.php" method="post">
        <div class="form-title"><h2>Login</h2></div>
            <label for="username">Username: </label><input class="form-field"type="text" name="username" value="" placeholder="Username"><br>
            <label for="password">Password: </label><input class="form-field" type="password" name="password" value="" placeholder="password"><br />
            <div class="submit-container">
                <input class="submit-button" type="submit" name="login" value="Login" />
            </div><br />
            <?php if ($_GET['error'] == 1) {
                echo "Invalid username or password<br /><a href='resetpwd.php'>Password lost ? Click on this link</a><br />";
            }
            else if ($_GET['error'] == 2) {
                echo "You must validate your account using the link in the mail that have been sent to you.<br />";
            } ?>
            <a href="register.php">No account ? Register now</a>
        </form>
    </body>
</html>
