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
	<table class="FlagButtons"><tr><td>All Active Flag Actions: </td><td>
	<input type="button" onclick="fieldMode(0)" value="Sleep"/>
	<input type="button" onclick="fieldMode(1)" value="Standby"/>
	<input type="button" onclick="fieldMode(2)" value="Game ON"/>
	<input type="button" onclick="fieldMode(3)" value="Game ON Spawn Limit"/>
	<input type="button" onclick="fieldMode(4)" value="Game ON Spawn Trap"/>
	<input type="button" onclick="fieldMode(5)" value="Game ON 2 Min Warn"/>
	<input type="button" onclick="fieldMode(6)" value="Game END"/>
	<input type="button" onclick="fieldMode(-1)" value="Turn Off"/></td></tr>
	<tr><td>All Flag Actions: </td><td>
	<input type="button" onclick="fieldMode(10)" value="All - Sleep"/>
	<input type="button" onclick="fieldMode(11)" value="All - Standby"/>
	<input type="button" onclick="fieldMode(12)" value="All - Game On"/>
	<input type="button" onclick="fieldMode(16)" value="All - Game End"/></td></tr>
	<tr><td>Blind Man (all field): </td><td>
	<input type="button" onclick="fieldMode(17)" value="All - Blind Man"/></td></tr>
	<tr><td>Custom Game Scenarios: </td>
	<td><input type="button" onclick="gameMode(1)" id="btnGame1" value="Game 1"/> <input type="text" id="txtGame1" value="" /> &nbsp;&nbsp;<input type="button" onclick="setGame(1)" id="btnSetGame1" value="Set"/></td></tr>
	<tr><td></td><td><input type="button" onclick="gameMode(2)" id="btnGame2" value="Game 2"/> <input type="text" id="txtGame2" value="" />  &nbsp;&nbsp;<input type="button" onclick="setGame(2)" id="btnSetGame2" value="Set"/></td></tr>
	<tr><td></td><td><input type="button" onclick="gameMode(3)" id="btnGame3" value="Game 3"/> <input type="text" id="txtGame3" value="" /> &nbsp;&nbsp;<input type="button" onclick="setGame(3)" id="btnSetGame3" value="Set"/></td></tr>
	<tr><td></td><td><input type="button" onclick="gameMode(4)" id="btnGame4" value="Game 4"/> <input type="text" id="txtGame4" value="" /> &nbsp;&nbsp;<input type="button" onclick="setGame(4)" id="btnSetGame4" value="Set"/></td></tr>
	<tr><td></td><td><input type="button" onclick="gameMode(5)" id="btnGame5" value="Game 5"/> <input type="text" id="txtGame5" value="" /> &nbsp;&nbsp;<input type="button" onclick="setGame(5)" id="btnSetGame5" value="Set"/></td></tr>
	<tr><td></td><td><input type="button" onclick="gameMode(6)" id="btnGame6" value="Game 6"/> <input type="text" id="txtGame6" value="" /> &nbsp;&nbsp;<input type="button" onclick="setGame(6)" id="btnSetGame6" value="Set"/></td></tr>
	<tr><td></td><td><input type="button" onclick="gameMode(7)" id="btnGame7" value="Game 7"/> <input type="text" id="txtGame7" value="" /> &nbsp;&nbsp;<input type="button" onclick="setGame(7)" id="btnSetGame7" value="Set"/></td></tr>
	<tr><td></td><td><input type="button" onclick="gameMode(8)" id="btnGame8" value="Game 8"/> <input type="text" id="txtGame8" value="" /> &nbsp;&nbsp;<input type="button" onclick="setGame(8)" id="btnSetGame8" value="Set"/></td></tr>
	<tr><td></td><td><input type="button" onclick="gameMode(9)" id="btnGame9" value="Game 9"/> <input type="text" id="txtGame9" value="" /> &nbsp;&nbsp;<input type="button" onclick="setGame(9)" id="btnSetGame9" value="Set"/></td></tr>
	<tr><td></td><td><input type="button" onclick="gameMode(10)" id="btnGame10" value="Game 10"/> <input type="text" id="txtGame10" value="" /> &nbsp;&nbsp;<input type="button" onclick="setGame(10)" id="btnSetGame10" value="Set"/></td></tr>
	</table>
	<p>This web application is restricted. Do not share the password for this website with players or staff.</p>
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
		// httpxml call to game mode in flag.php, flag.php matches desired to game mode table value
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
