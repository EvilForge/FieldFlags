<?php
include('config.php');
session_start();
if (isset($_GET['login'])) {
    if ($_POST['username'] == USERNAME && $_POST['password'] == PASSWORD) {
        $_SESSION['loggedin'] = 1;
        header("Location: index.php");
        exit;
    } else echo "Incorrect Login.";
}
?>
<html>
<head></head>
<body>
Please log in:
<form action="?login=1" method="post">
    Username: <input type="text" name="username"/> Password: <input
    type="password" name="password"/> <input type="submit" value="Login"/>
</form>
</body>
</html>
