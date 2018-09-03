<?php
include('config.php');
session_start();
if ($_SESSION['loggedin'] != 1) {
    header("Location: login.php");
    exit;
}
$flagid = 0;
$action = "";
$fieldmode = 0;
$gamemode = 0;
$timegreen = 0;
$timetan = 0;
$timeblue = 0;
$gamename = "";
$validPost = true;
if(isset($_POST['id'])) {
	$flagid = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
	if (($flagid<1) || ($flagid>20)) {
		$validPost = false;
	}
}
if(isset($_POST['fm'])) {
	$fieldmode = filter_var($_POST['fm'], FILTER_SANITIZE_NUMBER_INT);
	if (($fieldmode<-1) || ($fieldmode>25)) {
		$validPost = false;
	}
}
if(isset($_POST['gn'])) {
	$gamename = filter_var($_POST['gn'], FILTER_SANITIZE_STRING);
	if (($gamename == "") || (strlen($gamename)>200)) {
		$validPost = false;
	}
}
if(isset($_POST['gm'])) {
	$gamemode = filter_var($_POST['gm'], FILTER_SANITIZE_NUMBER_INT);
	if (($gamemode<1) || ($gamemode>10)) {
		$validPost = false;
	}
}
if(isset($_POST['a'])) {
	$action = filter_var($_POST['a'], FILTER_SANITIZE_STRING);
	if (($action!="nm") && ($action!="no") && ($action!="af") && ($action!="gf") && ($action!="fg") && ($action!="gd") && ($action!="sg")) {
		$validPost = false;
	}
} else {
	$validPost = false;
}
if ($validPost) {
	$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
	if ($conn->connect_error) {
		//die ('FAIL:DBConnect: ' . $conn->connect_error);
		die ('FAIL:DBConnect:');
	}
	// Get flag details
	if (($flagid != 0) && ($action=="gf")) {
		// Get Flag Mode and Owner and Enabled state.
		$sql = "SELECT ds.mode, ds.enabled, ds.owner as dsown, fs.battery, fs.owner as fsown FROM `desiredstatus` ds INNER JOIN `flagstatus` fs ON ds.flagid=fs.flagid WHERE ds.flagid=$flagid";
		if(!$result = $conn->query($sql)) {
			echo "SQL:$sql<br/>";
			die ("FAIL:DBGQuery");
		} else {
			// Got the current value, now set the new one.
			$row = $result->fetch_assoc();
			$Mode = $row['mode']+0;
			$Enabled = $row['enabled']+0;
			$DOwner = $row['dsown']+0;
			$FOwner = $row['fsown']+0;
			$Battery = $row['battery']+0;
			if ($DOwner>3) {
				// we dont care or are resetting, so show the real current flag owner
				echo "$flagid,$Mode,$Enabled,$FOwner,$Battery";
			} else {
				echo "$flagid,$Mode,$Enabled,$DOwner,$Battery";
			}
		}
	}
	// Increment the current mode and send us back details on the flag.
	if (($flagid != 0) && ($action=="nm")) {
		// nm means get & set next flag mode.
		$sql = "SELECT `mode`, `enabled`, `owner` FROM `desiredstatus` WHERE `flagid`=$flagid";
		if(!$result = $conn->query($sql)) {
			echo "SQL:$sql<br/>";
			die ("FAIL:DBSQuery");
		} else {
			// Got the current value, now set the new one.
			$row = $result->fetch_assoc();
			$NewMode = $row['mode'] + 1;
			$Enabled = $row['enabled'];
			$Owner = $row['owner'];
			if ($Enabled==0) {
				// New Mode is sleep. reenable.
				$NewMode = 0;
				$Enabled = 1;
			}
			if ($NewMode>6) { 
				// Disable instead of sleep. jump over 7 (blind man) for flag click.
				$Enabled = 0;
				$NewMode = 0;
				$Owner = 0;
			}
			$sql = "UPDATE `desiredstatus` SET `mode`=$NewMode, `enabled`=$Enabled, `owner`=$Owner WHERE `flagid`=$flagid";
			if(!$result2 = $conn->query($sql)) {
				echo "SQL:$sql<br/>";
				die ("FAIL:DBUQuery");
			} else {
				// Got the current value, now set the new one.
				if ($Enabled == 0) {
					echo "$flagid,-1,$Enabled,$Owner";
				} else {
					echo "$flagid,$NewMode,$Enabled,$Owner";
				}
				mysqli_free_result($result);
				mysqli_close($conn);
			}
		}
	}
	// Increment the current owner and send us back details on the flag.
	if (($flagid != 0) && ($action=="no")) {
		// nm means get & set next flag owner.
		$sql = "SELECT `mode`, `enabled`, `owner` FROM `desiredstatus` WHERE `flagid`=$flagid";
		if(!$result = $conn->query($sql)) {
			echo "SQL:$sql<br/>";
			die ("FAIL:DBSQuery");
		} else {
			// Got the current value, now set the new one.
			$row = $result->fetch_assoc();
			$Mode = $row['mode'];
			$Enabled = $row['enabled'];
			$NewOwner = $row['owner'] + 1;
			if ($Enabled==0) {
				$NewOwner = 0;
			}
			if ($NewOwner>5) {
				$NewOwner = 0;
			}
			$sql = "UPDATE `desiredstatus` SET `owner`=$NewOwner WHERE `flagid`=$flagid";
			if(!$result2 = $conn->query($sql)) {
				echo "SQL:$sql<br/>";
				die ("FAIL:DBUQuery");
			} else {
				// Got the current value, now set the new one.
				echo "$flagid,$Mode,$Enabled,$NewOwner";
				mysqli_free_result($result);
				mysqli_close($conn);
			}
		}
	}
	// Set all flags to some specific mode or just active flags depending on mode sent.
	if (($fieldmode >= -1) && ($fieldmode < 25) && ($action=="af")) {
		// af means set active flags to mode in ID.
		$sql = "";
		$sql2 = "";
		$sql3 = "";
		switch ($fieldmode){ // -1 disable active, 0-7 set corresponding mode for enabled. 10-17 do same for all field regardless.
			case -1: // disable any turned on.
				$sql = "UPDATE `desiredstatus` SET `mode`=0, `enabled`=0 WHERE `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field DISABLED all active flags.', CURRENT_TIMESTAMP, 'Web server flag.php af=-1')";
				break;
			case 0: // active to sleep.
				$sql = "UPDATE `desiredstatus` SET `mode`=0 WHERE `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field active flags to SLEEP.', CURRENT_TIMESTAMP, 'Web server flag.php af=0')";
				break;
			case 1: // active to standby.
				$sql = "UPDATE `desiredstatus` SET `mode`=1 WHERE `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field active flags to STANDBY.', CURRENT_TIMESTAMP, 'Web server flag.php af=1')";
				break;
			case 2: // active to game on.
				$sql = "UPDATE `desiredstatus` SET `mode`=2 WHERE `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field active flags to GAMEON.', CURRENT_TIMESTAMP, 'Web server flag.php af=2')";
				break;
			case 3: // active to spawn limit.
				$sql = "UPDATE `desiredstatus` SET `mode`=3 WHERE `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field active flags to GAMEON Spawn Limited.', CURRENT_TIMESTAMP, 'Web server flag.php af=3')";
				break;
			case 4: // active to spawn trap.
				$sql = "UPDATE `desiredstatus` SET `mode`=4 WHERE `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field active flags to GAMEON Spawn Trap.', CURRENT_TIMESTAMP, 'Web server flag.php af=4')";
				break;
			case 5: // active to 2min.
				$sql = "UPDATE `desiredstatus` SET `mode`=5 WHERE `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field active flags to GAMEON 2Min warning.', CURRENT_TIMESTAMP, 'Web server flag.php af=5')";
				break;
			case 6: // active to game end.
				$sql = "UPDATE `desiredstatus` SET `mode`=6 WHERE `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field active flags to GAME END.', CURRENT_TIMESTAMP, 'Web server flag.php af=6')";
				break;
			case 7: // active to blind man.
				$sql = "UPDATE `desiredstatus` SET `mode`=6 WHERE `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field active flags to BLIND MAN.', CURRENT_TIMESTAMP, 'Web server flag.php af=7')";
				break;
			case 10: // all to sleep.
				$sql = "UPDATE `desiredstatus` SET `mode`=0, `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field all flags SLEEP.', CURRENT_TIMESTAMP, 'Web server flag.php af=10')";
				break;
			case 11: // all to standby.
				$sql = "UPDATE `desiredstatus` SET `mode`=1, `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field all flags STANDBY.', CURRENT_TIMESTAMP, 'Web server flag.php af=11')";
				break;
			case 12: // all to game on.
				$sql = "UPDATE `desiredstatus` SET `mode`=2, `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field all flags GAMEON.', CURRENT_TIMESTAMP, 'Web server flag.php af=12')";
				break;
			case 13: // all to spawn limit.
				$sql = "UPDATE `desiredstatus` SET `mode`=3, `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field all flags GAMEON Spawn Limited.', CURRENT_TIMESTAMP, 'Web server flag.php af=13')";
				break;
			case 14: // all to spawn trap.
				$sql = "UPDATE `desiredstatus` SET `mode`=4, `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field all flags GAMEON Spawn Trap.', CURRENT_TIMESTAMP, 'Web server flag.php af=14')";
				break;
			case 15: // all to 2min.
				$sql = "UPDATE `desiredstatus` SET `mode`=5, `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field all flags GAMEON 2Min warning.', CURRENT_TIMESTAMP, 'Web server flag.php af=15')";
				break;
			case 16: // all to game end.
				$sql = "UPDATE `desiredstatus` SET `mode`=6, `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field all flags GAME END.', CURRENT_TIMESTAMP, 'Web server flag.php af=16')";
				break;
			case 17: // all to blind man.
				$sql = "UPDATE `desiredstatus` SET `mode`=7, `enabled`=1";
				$sql2 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field all flags BLIND MAN.', CURRENT_TIMESTAMP, 'Web server flag.php af=17')";
				break;
			case 20: // Generic Field wake from disabled.
				$sql = "UPDATE `desiredstatus` SET `owner`=0,`mode`=0,`enabled`=1";
				$sql2 = "UPDATE `flagstatus` SET `lastseen`=0,`greentime`=0,`tantime`=0,`bluetime`=0";
				$sql3 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field Wakeup ALL to SLEEP and clear times, owner.', CURRENT_TIMESTAMP, 'Web server flag.php af=11')";
				break;
			case 21: // Conquest Setup.
				$sql = "UPDATE `desiredstatus` SET `owner`=5,`mode`=1 WHERE `enabled`=1";
				$sql2 = "UPDATE `flagstatus` SET `lastseen`=0,`greentime`=0,`tantime`=0,`bluetime`=0";
				$sql3 = "INSERT INTO `gamelog` (`event`, `eventtime`, `source`) VALUES ('Field active flags to STANDBY, clear times, track owner.', CURRENT_TIMESTAMP, 'Web server flag.php af=12')";
				break;
			default:
				break;
		}
		if(!$result = $conn->query($sql)) {
			echo "SQL:$sql<br/>";
			die ("FAIL:DBFU1Query");
		} else {
			if ($sql2 != "") {
				if(!$result = $conn->query($sql2)) {
					echo "SQL:$sql<br/>";
					die ("FAIL:DBFU2Query");
				} else {
					if ($sql3 != "") {
						if(!$result = $conn->query($sql3)) {
							echo "SQL:$sql<br/>";
							die ("FAIL:DBFU3Query");
						} else {
							echo "SUCCESS";
						}
					} else {
							echo "SUCCESS";
					}
				}
			} else {
					echo "SUCCESS";
			}
		}
	}
	// Set entire field to specific custom game scenario
	if (($gamemode >= 1) && ($gamemode < 20) && ($action=="fg")) {
		// af means set active flags to mode in ID.
		$sql = "UPDATE `desiredstatus` ds JOIN `gamemode` gm ON ds.flagid=gm.flagid SET ds.mode=gm.flagmode, ds.owner=gm.flagowner, ds.enabled=gm.flagenabled WHERE gm.gameid=$gamemode";
		if(!$result = $conn->query($sql)) {
			echo "SQL:$sql<br/>";
			die ("FAIL:DBGMQuery");
		} else {
			echo "SUCCESS";
		}
	}
	// Get specific game name
	if (($gamemode >= 1) && ($gamemode < 20) && ($action=="gd")) {
		// Get game details - Mode number and title.
		$sql = "SELECT gamename FROM `gamemode` WHERE gameid=$gamemode LIMIT 1";
		if(!$result = $conn->query($sql)) {
			echo "SQL:$sql<br/>";
			die ("FAIL:DBGDQuery");
		} else {
			// Got the current value, now set the new one.
			$row = $result->fetch_assoc();
			$Name = $row['gamename'];
			echo "$gamemode|$Name";
		}
	}
	// Copy current field state and set it to sent custom game number
	if (($gamemode >= 1) && ($gamemode < 11) && ($action=="sg")) {
		// Set game details - run sql to copy current field over gamemode records.
		if ($gamename=="") {
			$gamename = "Custom Game $gamemode.";
		}
		$sql = "UPDATE gamemode gm INNER JOIN desiredstatus ds ON gm.flagid=ds.flagid SET flagmode=mode,flagowner=owner,flagenabled=enabled,gamename='$gamename' WHERE gameid = $gamemode";
		if(!$result = $conn->query($sql)) {
			echo "-1";
			die ();
		} else {
			// echo success.
			echo "$gamemode|Updated $gamename";
		}
	}
}
?>
