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
	<title> Flag Mobile Admin Console</title>
	<link rel="stylesheet" type="text/css" href="/field/main.css">
	<meta name="robots" content="noarchive">
	<meta name="robots" content="noindex">
	<meta id="meta" name="viewport" content="width=device-width; initial-scale=1.0" />
</head>
<body onload="myPageLoad()">
<?php

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
if ($conn->connect_error) {
	die ('DB Connection error: ' . $conn->connect_error);
}
$flagid = 0;
$editid = 0;
$editmode = 0;
$editen = 0;
$editowner = 0;
$editdelay = 0;
if(isset($_POST['id'])) {
	$flagid = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
	if (($flagid<1) || ($flagid>20)) {
		$flagid = 0;
	}
}
if(isset($_POST['editid'])) {
	$editid = filter_var($_POST['editid'], FILTER_SANITIZE_NUMBER_INT);
	if (($editid<1) || ($editid>20)) {
		$editid = 0;
	}
}
if(isset($_POST['editmode'])) {
	$editmode = filter_var($_POST['editmode'], FILTER_SANITIZE_NUMBER_INT);
	if (($editmode<0) || ($editmode>7)) {
		$editmode = 0;
	}
}
if(isset($_POST['editen'])) {
	$editen = filter_var($_POST['editen'], FILTER_SANITIZE_NUMBER_INT);
	if (($editen!=1) && ($editen!=0)) {
		$editen = 0;
	}
}
if(isset($_POST['editowner'])) {
	$editowner = filter_var($_POST['editowner'], FILTER_SANITIZE_NUMBER_INT);
	if (($editowner<0) || ($editowner>5)) {
		$editowner = 0;
	}
}
if(isset($_POST['editdelay'])) {
	$editdelay = filter_var($_POST['editdelay'], FILTER_SANITIZE_NUMBER_INT);
	if (($editdelay<5) || ($editdelay>180)) {
		$editdelay = 5;
	}
}

if (($editid != 0) && (!empty($_POST['editsubmit']))) {
	$sql = "UPDATE `desiredstatus` SET `mode`=$editmode, `enabled`=$editen, `owner`=$editowner, `spawndelay`=$editdelay WHERE `flagid`=$editid";
	echo "SQL:$sql<br/>";
	if(!$sqlupdate = $conn->query($sql)) {
		echo "SQL:$sql<br/>";
		die ("FAIL:DBUQuery");
	} else {
		// TODO now echo what we set the flag to.. for confirmation as the page only shows current status.
		echo"SQL Update Success! Please wait for the flag to report in a few seconds...";
		//mysqli_free_result($sqlupdate);
	}
}
?>
<form id='filter' name='filter' method='post' action=''>
<p style='color: #FAF8C4;'>Select flag or <a style='font-size:smaller;' href='m-general.php'>Return to Mobile Admin Page</a></p>
<select class='pickflag' name='id' id='id' onchange='this.form.submit()'><?php
$sql = "SELECT `flagid`, `name`,`flagdesc` FROM flagstatus ORDER BY flagid";	
if(!$result = $conn->query($sql)){
    die('There was an error running the query [' . $conn->error . ']');
}
if($result->num_rows == 0) {
	?><p>Error querying DB for list of flags!</p><?php
} else { 
	?><option selected='selected' value='<?php echo"$flagid'>Flag $flagid"; ?></option><?php
	while($row = $result->fetch_assoc()){
	?><option style='width: 50%;' value='<?=$row["flagid"]?>'><?=$row["name"]?> - <?=$row["flagdesc"]?></option><?php
	} 
}
mysqli_free_result($result);
?></select><?php
if ($flagid==0) {
	$flagid=1;
}
$sql = "SELECT `flagid`, `name`,`flagdesc`,`mode`,`owner`,`battery`,`enabled`,`lastseen`,`greentime`,`tantime`,`bluetime` FROM flagstatus WHERE `flagid`=$flagid";	
$sql2 = "SELECT `spawndelay` FROM desiredstatus WHERE `flagid`=$flagid";	
if(!$result = $conn->query($sql)){
    die('There was an error running the current status query [' . $conn->error . ']');
}
if(!$result2 = $conn->query($sql2)){
    die('There was an error running the desired status query [' . $conn->error . ']');
}
if($result->num_rows != 1) {
	?><p>Error querying DB flag current details!</p><?php
} else {
	$row = $result->fetch_assoc();
	$row2 = $result2->fetch_assoc();
	?>
	<p style='color: #FAF8C4;'>Flag State:</p>
	<form id='flagset' name='flagset' method='post' action='m-setflag.php'>
	<input type='hidden' id='editid' name='editid' value='<?=$row["flagid"]?>'></input>
	<label>Name</label> <?=$row["name"]?><br />
	<label>Mode</label> <select class="flagedit" name='editmode' id='editmode'><option value='<?php
	switch ($row["mode"]){
		case 0: echo"0'>Sleeping"; break;
		case 1: echo"1'>StandBy"; break;
		case 2: echo"2'>Game On"; break;
		case 3: echo"3'>Game On Spawn Limited"; break;
		case 4: echo"4'>Game On Spawn Trap"; break;
		case 5: echo"5'>Game On 2min Warning"; break;
		case 6: echo"6'>Game Over"; break;
		case 7: echo"7'>Blind Man"; break;
		default: break;
	}
	?></option><option value='0'>Sleeping</option><option value='1'>StandBy</option><option value='2'>Game On</option><option value='3'>Game On Spawn Limited</option><option value='4'>Game On Spawn Trap</option><option value='5'>Game On 2Min Warning</option><option value='6'>Game Over</option><option value='7'>Blind Man</option></select><br />
	<label>Enabled</label> <input class="flagedit" type='checkbox' name='editen' id='editen' value='<?php
	if ($row["enabled"]) {
		echo"Yes' checked='checked' ></input>";
	} else {
		echo"No' ></input>";
	}
	?><br />
	<label>Owner</label> <select class="flagedit" name='editowner' id='editowner'><option selected='selected' value='<?php
	switch ($row["owner"]){
		case 0: echo"0'>No Owner"; break;
		case 1: echo"1'>Green"; break;
		case 2: echo"2'>Tan"; break;
		case 3: echo"3'>Blue"; break;
		case 4: echo"4'>Reset"; break;
		case 5: echo"5'>Flag Controlled"; break;
		default: break;
	}
	?></option><option value='0'>No Owner</option><option value='1'>Green</option><option value='2'>Tan</option><option value='3'>Blue</option><option value='4'>Reset</option><option value='5'>Flag Controlled</option></select><br />
	<label>Spawn Delay</label> <input class="flagedit" type='range' name='editdelay' id='editdelay' min='5' max='180' onchange='updateTextInput(this.value);' value='<?=$row2["spawndelay"]?>'></input> <input class='slider' type='number' id='sliderdelay' value='<?=$row2["spawndelay"]?>' onchange='updateSlider(this.value);'> s<br />
	<label> &nbsp;</label> <input class='flagedit' type='submit' id='editsubmit' name='editsubmit' value='Update'></form>
	<hr>
	<div style='font-size:small;'>(other information)</div>
	<label>Battery</label> <?=$row["battery"]?>%<br />
	<label>Last report</label> <?=$row["lastseen"]?>s ago.<br />
	<label>Green Time</label> <?=$row["greentime"]?>s held.<br />
	<label>Tan Time</label> <?=$row["tantime"]?>s held.<br />
	<label>Blue Time</label> <?=$row["bluetime"]?>s held.<br />
	<label>Description</label> <?=$row["flagdesc"]?><br />
	<hr><p>Note that flags usually update within 15 seconds. If you havent refreshed in a while, select your flag and hit 'Go' to get the most current data.</p>
	<?php
}
mysqli_free_result($result);
mysqli_free_result($result2);
// Do the close as last step of page.
mysqli_close($conn);				
//var_dump(get_defined_vars());
?>
	<script type="text/javascript">
	function myPageLoad() {
		window.setInterval(refreshMap,30000);
	}
	function updateTextInput(val) {
          document.getElementById('sliderdelay').value=val; 
    }
	function updateSlider(val) {
          document.getElementById('flagdelay').value=val; 
    }
	</script>
</body>
</html>