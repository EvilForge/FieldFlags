<?php
include('config.php');
session_start();
if ($_SESSION['loggedin'] != 1) {
    header("Location: login.php");
    exit;
} ?>
<!DOCTYPE html>
<html lang='en'>
<head><meta charset='UTF-8'>
	<title> Flag Admin Main Menu</title>
	<link rel="stylesheet" type="text/css" href="/field/main.css">
	<meta name="robots" content="noarchive">
	<meta name="robots" content="noindex">
</head>
<body>
<p>Select a game mode or the general Admin page using the links below:</p>
<a href="admin.php" alt="Click to go to the general admin page.">General Admin Page</a><br/>
<a href="conquest.php" alt="Click to go to the general admin page.">Conquest Game Page</a><br/>
<a href="m-general.php" alt="Click to go to the mobile friendly general admin page.">Mobile Admin</a><br/>
<a href="game1.php" alt="Click to go to the general admin page.">Game 1 Page</a><br/>
<a href="logs.php" alt="Click to go to the field game logs.">Game Logs Page</a><br/>
<p>Online training documents are available here:<br/>
<a href="lessons.php"> Flag Lessons</a></p>
</body>
</html>