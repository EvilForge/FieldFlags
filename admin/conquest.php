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
	<title> Conquest Game Page</title>
	<link rel="stylesheet" type="text/css" href="/field/main.css">
	<meta name="robots" content="noarchive">
	<meta name="robots" content="noindex">
</head>
<body>
<p>CONQUEST Game Page:</p>
<body onload="myPageLoad()">
	<div id="container" style="position: relative; left: 0; top: 0;">
	  <img id="map" src="/field/images/clearmap.png" style="position: relative; top: 0; left: 0;" />
	  <img id="btnMapMode" title="Click to change map MODE" src="/field/images/btnMode.png" style="position: absolute; left: 0px; top: 0px;" alt="Mode" onclick="changeView()" />
	  <img id="flag1" title="Flag 1, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 25px; top: 235px;" alt="Flag 1" onclick="flagClick(1)"/>
	  <img id="flag2" title="Flag 2, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 277px; top: 326px;" alt="Flag 2" onclick="flagClick(2)"/>
	  <img id="flag3" title="Flag 3, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 248px; top: 395px;" alt="Flag 3" onclick="flagClick(3)"/>
	  <img id="flag4" title="Flag 4, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 390px; top: 526px;" alt="Flag 4" onclick="flagClick(4)"/>
	  <img id="flag5" title="Flag 5, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 333px; top: 205px;" alt="Flag 5" onclick="flagClick(5)"/>
	  <img id="flag6" title="Flag 6, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 395px; top: 370px;" alt="Flag 6" onclick="flagClick(6)"/>
	  <img id="flag7" title="Flag 7, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 554px; top: 318px;" alt="Flag 7" onclick="flagClick(7)"/>
	  <img id="flag8" title="Flag 8, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 568px; top: 470px;" alt="Flag 8" onclick="flagClick(8)"/>
	  <img id="flag9" title="Flag 9, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 845px; top: 588px;" alt="Flag 9" onclick="flagClick(9)"/>
	  <img id="flag10" title="Flag 10, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 36px; top: 392px;" alt="Flag 10" onclick="flagClick(10)"/>
	  <img id="flag11" title="Flag 11, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 696px; top: 518px;" alt="Flag 11" onclick="flagClick(11)"/>
	  <img id="flag12" title="Flag 12, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 684px; top: 330px;" alt="Flag 12" onclick="flagClick(12)"/>
	  <img id="flag13" title="Flag 13, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 724px; top: 450px;" alt="Flag 13" onclick="flagClick(13)"/>
	  <img id="flag14" title="Flag 14, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 203px; top: 11px;" alt="Flag 14" onclick="flagClick(14)"/>
	  <img id="flag15" title="Flag 15, Offline, No Owner" src="/field/images/off.png" style="position: absolute; left: 205px; top: 185px;" alt="Flag 15" onclick="flagClick(15)"/>
	</div>
	<table class="FlagButtons">
	<tr><td>Game Actions: </td><td><input type="button" onclick="fieldMode(2)" value="Game On"/></td><td>Enabled Flags show green. Button press changes owner.</td></tr>
	<tr><td></td><td><input type="button" onclick="fieldMode(5)" value="2 Min Warn"/></td><td>Enabled Flags flash white/green twice as a 2-min warning.</td></tr>
	<tr><td></td><td><input type="button" onclick="fieldMode(6)" value="Game End"/></td><td>ALL Flags show red. Flag times are summarized below. Stay in Game End for at least 30 seconds to allow flags to report times.
	</td></tr>
	<tr><td>Pre-game Flag Actions: </td><td>
	<input type="button" onclick="fieldMode(20)" value="Step 1"/></td><td>Bring all flags online to sleep mode. Disable flags you are not using by clicking those flags and setting them to disabled. Give the field 5 minutes to stabilize.</td></tr>
	<tr><td></td><td><input type="button" onclick="fieldMode(21)" value="Step 2"/></td><td>Once step 1 is done, Use this to, set all active flags to  standby (yellow/blue flash) and track owners.</td></tr>
	<tr><td>After-game Flag Actions: </td><td><input type="button" onclick="fieldMode(10)" value="All - Sleep"/></td><td>Reset flags but leave ready to start next game quicker, ~2m.</td></tr>
	<tr><td></td><td><input type="button" onclick="fieldMode(-1)" value="All - Disable"/></td><td>Reset and Power Down flags to save battery but takes ~5m to restart.
	</td></tr>
	<tr><td>Blind Man (all field): </td><td>
	<input type="button" onclick="fieldMode(17)" value="All - Blind Man"/></td><td>ALL FLAGS HALT and show RED/Yellow. Use GAME ON to restart.</td></tr>
	</table>
	<p>Flag times (secs held for each color, disabled flags are skipped):</p>
	<table class="FlagButtons">
	<tr><td>Flag</td><td>Green</td><td>Tan</td><td>Blue</td></tr>
<?php
$greenSum = 0;
$tanSum= 0;
$blueSum= 0;
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
if ($conn->connect_error) {
	//die ('FAIL:DBConnect: ' . $conn->connect_error);
	die ('FAIL:DBConnect:');
}
for ($cnt=1;$cnt<21;$cnt++) {
	// Call the db for flag time.
	$sql = "SELECT fs.enabled, fs.greentime, fs.tantime, fs.bluetime FROM `flagstatus` fs WHERE fs.flagid=$cnt";
	if(!$result = $conn->query($sql)) {
		echo "SQL:$sql<br/>";
		die ("FAIL:DBFTQuery");
	} else {
		// Got the current value, now set the new one.
		$row = $result->fetch_assoc();
		$Enabled = $row['enabled']+0;
		$greentime = $row['greentime']+0;
		$tantime = $row['tantime']+0;
		$bluetime = $row['bluetime']+0;
		if ($Enabled) {
			echo("<tr><td>$cnt</td><td>$greentime</td><td>$tantime</td><td>$bluetime</td></tr>");
			$greenSum = $greenSum + $greentime;
			$tanSum = $tanSum + $tantime;
			$blueSum = $blueSum + $bluetime;
		} else {
			// Echo nothing, this flag is disabled.
		}
	}
}
echo("<tr><td>ALL (Summary)</td><td>$greenSum</td><td>$tanSum</td><td>$blueSum</td></tr>");
$winner = "None, or Tie";
if (($greenSum > $tanSum) && ($greenSum > $blueSum)) {
	$winner = "Green";
}
if (($tanSum > $greenSum) && ($tanSum > $blueSum)) {
	$winner = "Tan";
}
if (($blueSum > $greenSum) && ($blueSum > $tanSum)) {
	$winner = "Blue";
}
echo("<tr><td>WINNER</td><td>$winner</td><td></td><td></td></tr>");
?>	
	
	</table>
	<p>This web application is restricted. Do not share the password for this website with players or  staff.</p>
	<p>Instructions for this site are available <a href="/field/admin/lessons.php">HERE</a>.</p>

	<script type="text/javascript">
	function flagClick(flagID) {
		var xhttp;
		if (window.XMLHttpRequest) {
			xhttp = new XMLHttpRequest();
			} else {
			// code for IE6, IE5
			xhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	    xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				flag_State = new Array();
				flag_State = this.responseText.split(",");
				strStatus = "";
				strIcon = "";
				sentFlag = flag_State[0];
				if (flag_State[2]==1) {
					switch(flag_State[1]) {
						case "-1":
							strStatus = "Flag"+sentFlag+",Off";
							strIcon = "/field/images/off.png";
						break;
						case "0":
							strStatus = "Flag"+sentFlag+",Sleeping";
							strIcon = "/field/images/sleeping.png";
						break;
						case "1":
							strStatus = "Flag"+sentFlag+",Standby";
							strIcon = "/field/images/standby.png";
						break;
						case "2":
							strStatus = "Flag"+sentFlag+",Game ON";
							strIcon = "/field/images/gameon.png";
						break;
						case "3":
							strStatus = "Flag"+sentFlag+",Game ON Limit Spawn";
							strIcon = "/field/images/spawnlimit.png";
						break;
						case "4":
							strStatus = "Flag"+sentFlag+",Game ON Trap ON";
							strIcon = "/field/images/spawntrap.png";
						break;
						case "5":
							strStatus = "Flag"+sentFlag+",2Min Warn";
							strIcon = "/field/images/twomin.png";
						break;
						case "6":
							strStatus = "Flag"+sentFlag+",Game OVER";
							strIcon = "/field/images/gameover.png";
						break;
						case "7":
							strStatus = "Flag"+sentFlag+",BLIND MAN";
							strIcon = "/field/images/deadman.png";
						break;
					}
				} else {
					strStatus =  "Flag"+sentFlag+",Offline";
					strIcon = "/field/images/off.png";
				}
				if (strMapMode=="Owner") {
					switch(flag_State[3]) {
						case "0":
							strIcon = "/field/images/noowner.png";
						break;
						case "1":
							strIcon = "/field/images/green.png";
						break;
						case "2":
							strIcon = "/field/images/tan.png";
						break;
						case "3":
							strIcon = "/field/images/blue.png";
						break;
						case "4":
							strIcon = "/field/images/reset.png";
						break;
						case "5":
							strIcon = "/field/images/dontcare.png";
						break;
					}
				}
				switch(flag_State[3]) {
					case "0":
						strStatus = strStatus+",No Owner";
					break;
					case "1":
						strStatus = strStatus+",Green";
					break;
					case "2":
						strStatus = strStatus+",Tan";
					break;
					case "3":
						strStatus = strStatus+",Blue";
					break;
					case "4":
						strStatus = strStatus+",Resetting";
					break;
					case "5":
						strStatus = strStatus+",Dont care who owns it.";
					break;
				}
				if (!!(document.getElementById("flag"+sentFlag))) {
					document.getElementById("flag"+sentFlag).src = strIcon;
					document.getElementById("flag"+sentFlag).title = strStatus;
				}
		   }
		}
		var strMapMode = document.getElementById("btnMapMode").alt;
		var strURL = "id="+flagID+"&a=nm";
		if (strMapMode=="Owner") {
			strURL = "id="+flagID+"&a=no";
		}
		if (strMapMode!="Battery") {
			xhttp.open("POST","/field/admin/flag.php",true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send(strURL); // flag id and next mode request
		}
	}
	
	function changeView() {
 		switch (document.getElementById("btnMapMode").alt) {
			case "Mode":
				document.getElementById("btnMapMode").src = "/field/images/btnOwner.png";
				document.getElementById("btnMapMode").alt = "Owner";
			break;
			case "Owner":
				document.getElementById("btnMapMode").src = "/field/images/btnBattery.png";
				document.getElementById("btnMapMode").alt = "Battery";
			break;
			case "Battery":
				document.getElementById("btnMapMode").src = "/field/images/btnMode.png";
				document.getElementById("btnMapMode").alt = "Mode";
			break;
		}
		for(var x=1;x<21;x++) {
			updateStatus(x);
		}

	}
	
	function updateStatus(flagid) {
		// update all the flatg statuses 1-20
		var xuhttp;
		if (window.XMLHttpRequest) {
			xuhttp = new XMLHttpRequest();
			} else {
			// code for IE6, IE5
			xuhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	    xuhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200) {
				var strMapMode = document.getElementById("btnMapMode").alt;
				flag_State = new Array();
				flag_State = this.responseText.split(",");
				strStatus = "";
				strIcon = "";
				sentFlag = flag_State[0];
				if (flag_State[2]==1) {
					switch(flag_State[1]) {
						case "0":
							strStatus = "Flag"+sentFlag+",Sleeping";
							strIcon = "/field/images/sleeping.png";
						break;
						case "1":
							strStatus = "Flag"+sentFlag+",Standby";
							strIcon = "/field/images/standby.png";
						break;
						case "2":
							strStatus = "Flag"+sentFlag+",Game ON";
							strIcon = "/field/images/gameon.png";
						break;
						case "3":
							strStatus = "Flag"+sentFlag+",Game ON Limit Spawn";
							strIcon = "/field/images/spawnlimit.png";
						break;
						case "4":
							strStatus = "Flag"+sentFlag+",Game ON Trap ON";
							strIcon = "/field/images/spawntrap.png";
						break;
						case "5":
							strStatus = "Flag"+sentFlag+",2Min Warn";
							strIcon = "/field/images/twomin.png";
						break;
						case "6":
							strStatus = "Flag"+sentFlag+",Game OVER";
							strIcon = "/field/images/gameover.png";
						break;
						case "7":
							strStatus = "Flag"+sentFlag+",BLIND MAN";
							strIcon = "/field/images/deadman.png";
						break;
					}
				} else {
					strStatus =  "Flag"+sentFlag+",Offline";
					strIcon = "/field/images/off.png";
				}
				if (strMapMode=="Owner") {
					switch(flag_State[3]) {
						case "0":
							strIcon = "/field/images/noowner.png";
						break;
						case "1":
							strIcon = "/field/images/green.png";
						break;
						case "2":
							strIcon = "/field/images/tan.png";
						break;
						case "3":
							strIcon = "/field/images/blue.png";
						break;
						case "4":
							strIcon = "/field/images/reset.png";
						break;
						case "5":
							strIcon = "/field/images/dontcare.png";
						break;
					}
				}
				if (strMapMode=="Battery") {
					if (flag_State[4]<10){
						strIcon = "/field/images/dead.png";
					}
					if (flag_State[4]>20){
						strIcon = "/field/images/low.png";
					}
					if (flag_State[4]>50){
						strIcon = "/field/images/half.png";
					}
					if (flag_State[4]>80){
						strIcon = "/field/images/full.png";
					}
				}
				switch(flag_State[3]) {
					case "0":
						strStatus = strStatus+",No Owner";
					break;
					case "1":
						strStatus = strStatus+",Green";
					break;
					case "2":
						strStatus = strStatus+",Tan";
					break;
					case "3":
						strStatus = strStatus+",Blue";
					break;
					case "4":
						strStatus = strStatus+",Resetting";
					break;
					case "5":
						strStatus = strStatus+",Dont Care who owns it.";
					break;
				}
				strStatus = strStatus + ","+flag_State[4]+"%";
				if (!!(document.getElementById("flag"+sentFlag))) {
					document.getElementById("flag"+sentFlag).src = strIcon;
					document.getElementById("flag"+sentFlag).title = strStatus;
				}
			}
		}
		xuhttp.open("POST","/field/admin/flag.php",true);
		xuhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xuhttp.send("id="+flagid+"&a=gf"); // field mode request using action AF and mode = newMode
	}
	
	function fieldMode(newMode) {
		var fieldmodehttp;
		if (window.XMLHttpRequest) {
			fieldmodehttp = new XMLHttpRequest();
			} else {
			// code for IE6, IE5
			fieldmodehttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	    fieldmodehttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200) {
				// Refresh the field icons.
				for(var x=1;x<21;x++) {
					updateStatus(x);
				}
			}
		}
		// field modes dont correspond to flag modes anymore.
		fieldmodehttp.open("POST","/field/admin/flag.php",true);
		fieldmodehttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		fieldmodehttp.send("fm="+newMode+"&a=af"); // field mode request using action AF and mode = newMode
	}
	
	function myPageLoad() {
		for(var x=1;x<21;x++) {
			updateStatus(x);
			updateCustomGames(x);
		}
		window.setInterval(refreshMap,30000);
	}
	
	function refreshMap() {
		for(var x=1;x<21;x++) {
			updateStatus(x);
		}
	}
	
	function gameMode(newGame) {
		// game modes set up a set of flags or owners.
		// httpxml call to game mode in conquest.php, conquest.php matches desired to game mode table value
		// return function refreshes page.
		var gamemodehttp;
		if (window.XMLHttpRequest) {
			gamemodehttp = new XMLHttpRequest();
			} else {
			// code for IE6, IE5
			gamemodehttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	    gamemodehttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200) {
				// Refresh the field icons.
				for(var x=1;x<21;x++) {
					updateStatus(x);
				}
			}
		}
		// field modes dont correspond to flag modes anymore.
		gamemodehttp.open("POST","/field/admin/flag.php",true);
		gamemodehttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		gamemodehttp.send("gm="+newGame+"&a=fg"); // field mode request using action AF and mode = newMode
	}
	
	function updateCustomGames(gameid){
		// update the button & label - if found - with custom game name.
		var xghttp;
		if (window.XMLHttpRequest) {
			xghttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xghttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	    xghttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200) {
				gameName = new Array();
				gameName = this.responseText.split("|");
				if (!!(document.getElementById("txtGame"+gameid))) {
					document.getElementById("txtGame"+gameid).value = gameName[1];
				}
			}
		}
		xghttp.open("POST","/field/admin/flag.php",true);
		xghttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xghttp.send("gm="+gameid+"&a=gd"); // field mode request using action AF and mode = newMode
	}
	
	function setGame(gameid) {
		// Take the number given, pull txt and id and send it to flag page SQL for updating existing game with desired status.
		var sghttp;
		if (window.XMLHttpRequest) {
			sghttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			sghttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
	    sghttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200) {
				response = new Array();
				response = this.responseText.split("|");

				if (response[0]!= "-1") {
					updateCustomGames(response[0]);
					alert(response[1]);
				} else {
					alert("ERROR - Custom Game was not set.");
				}
			}
		}
		if (!!(document.getElementById("txtGame"+gameid))) {
			var strnewname = document.getElementById("txtGame"+gameid).value;
			sghttp.open("POST","/field/admin/flag.php",true);
			sghttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			sghttp.send("gm="+gameid+"&a=sg&gn="+strnewname); // field mode request using action AF and mode = newMode
		}
	}

	</script>
</body>
</html>
