<?php
include('config.php');
?>
<!DOCTYPE html>
<html lang='en'>
<head><meta charset='UTF-8'>
<title>Field Status</title>
<link rel="stylesheet" type="text/css" href="/field/main.css">
<meta name="robots" content="noarchive">
<meta name="robots" content="noindex">
</head>
<body>
<span class='wsite-logo'><img src='/field/images/D14Banner.jpg' alt='Banner' /></span><br/>
<h1>Field Status:</h1>
<?php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
if ($conn->connect_error) {
	die ('DB Connection error: ' . $conn->connect_error);
}
$sql = "SELECT `name`,`enabled`,`mode`,`owner` FROM flagstatus WHERE `enabled`=1 ORDER BY flagid";
if(!$result = $conn->query($sql)){
    die('There was an error running the query [' . $conn->error . ']');
}
if($result->num_rows == 0) {?>
	<p>The field is not currently active. Please try later, during an active game.</p>
	<iframe src="https://www.google.com/maps/d/embed?mid=1Hj1SeawgTB36p2VSdhi1LU" width="600" height="560"></iframe>
	<?php
} else { ?>
	<table><tr><th>Flag Name</th><th>Status</th><th>Owner</th><th rowspan='21'><iframe src="https://www.google.com/maps/d/embed?mid=1SLm80awgTB36p2VSdhi1LU" width="600" height="560"></iframe></th></tr>
<?php
}
while($row = $result->fetch_assoc()){
?>	<tr><td style='text-align:left;'><?=$row["name"]?></td>
	<td><?php
	if ($row["enabled"] == 0) {
	echo("Disabled</td><td></td><td></td><td></td>");
	} else {
	if ($row["mode"]==0) echo("<span class='Grey'>Sleeping</span>");
	if ($row["mode"]==1) echo("<span class='Yellow'>Standby</span>");
	if ($row["mode"]==2) echo("<span class='Green'>Game ON</span>");
	if ($row["mode"]==3) echo("Two Min Warn");
	if ($row["mode"]==4) echo("<span class='Red'>Game END</span>");
	if ($row["mode"]==5) echo("<span class='Red'>BLIND MAN</span>");
	?></td>
	<td><span class="<?php getOwner($row["owner"]) ?>"><?php getOwner($row["owner"])?></span></td><?php
	}
} 
mysqli_free_result($result);
mysqli_close($conn);				
?>
    </tr>
</table>

</body>
</html>