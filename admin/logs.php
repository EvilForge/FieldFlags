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
	<title> Flag General Admin Console</title>
	<link rel="stylesheet" type="text/css" href="/field/main.css">
	<meta name="robots" content="noarchive">
	<meta name="robots" content="noindex">
</head>
<body>
<p>Field Logs - All main game start and stops are logged here, along with summary flag information. Information can only be cleared by web admin at this time.</p>
<table class="FlagButtons">
<tr><td>Date-Time</td><td>Event</td><td>Source of Change</td></tr>
<?php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
if ($conn->connect_error) {
	//die ('FAIL:DBConnect: ' . $conn->connect_error);
	die ('FAIL:DBConnect:');
}
// Call the db for events.
$sql = "SELECT ev.event, ev.eventtime, ev.source FROM `gamelog` ev WHERE ev.eventtime BETWEEN NOW() - INTERVAL 30 DAY AND NOW() ORDER BY ev.eventtime DESC";
if(!$result = $conn->query($sql)) {
	echo "SQL:$sql<br/>";
	die ("FAIL:DBLOGQuery");
} else {
	// Got the current value, now set the new one.
	while($row = $result->fetch_assoc()) {
		echo "<tr><td>" . $row['eventtime'] . "</td><td>" . $row['event'] . "</td><td>" . $row['source'] . "</td></tr>";
	}
}
?>	
</table>
</body>
</html>
