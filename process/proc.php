<?PHP
include("config.php");
$action = "";
$validPost = false;

// Validate and determine action to be taken. ft=flag times, fb=flag battery, fa=flag array
if (isset($_POST['a'])) { 
	$action = filter_var($_POST['a'], FILTER_SANITIZE_STRING);
	if ( (($action=="u") && (isset($_POST['fa']))) || (($action=="ft") && (isset($_POST['id']))) || (($action=="u") && (isset($_POST['fb']))) || ($action=="gsd")) {
		// Update from field sent. Unpack, then respond with Server state array.
		if (isset($_POST['fa'])) {
			// Flag Array data is sent. Unpack it and validate
			$b64Array = filter_var($_POST['fa'], FILTER_SANITIZE_STRING);
			if (strlen($b64Array)!=28) {
				// Invalid length for a 20 flag byte array. Toss it.
				$b64Array = "";
				$validPost = false;
				http_response_code(500);
			} else {
				$binArray = unpack("C*",base64_decode($b64Array));
//				echo ("Decoded Array length :".sizeof($binArray)."<br/>");
				$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
				if ($conn->connect_error) {
					//die ('FAIL:DBConnect: ' . $conn->connect_error);
					http_response_code(500);
					die ('FAIL:DBConnect:');
				}
				$cnt = 0;
				foreach ($binArray as $item) {
					$flagmode = ($item >> 1)&7;
					$owner = ($item >> 4)&7;
					$enabled = ($item & 1);
					$sql = "UPDATE `flagstatus` SET `mode`=".$flagmode.",`owner`=".$owner.",`enabled`=".$enabled." WHERE `flagid`=".($cnt+1);
					$sql2 = "SELECT `owner` from `desiredstatus` WHERE `flagid`=".($cnt+1)." LIMIT 1";
					//echo("Arr[".$cnt."]:".ord($item)."\r\n");
					$DOwn = 0;
					if($result = $conn->query($sql2)){
						$row = $result->fetch_assoc();
						$DOwn = $row['owner']+0;
						mysqli_free_result($result);
					}
					if ($conn->query($sql) === TRUE) {
						//echo $conn->affected_rows;
						if (($owner==0) && ($DOwn==4)) {
							$sql = "UPDATE desiredstatus SET owner=5 WHERE owner=4 AND flagid=".($cnt+1);
							if ($conn->query($sql) === TRUE) {
								// clear the reset bit.
								//echo $sql;
							} else {
								echo("FAIL SQL:$sql\r\n");
								die ("FAIL:RDUPDATE");
							}
						}
					} else {
						//die("Query to update flag failed with this error: " . $conn->error); 
						http_response_code(500);
						echo("FAIL SQL:$sql\r\n");
						die ("FAIL:FAUPDATE");
					}
					$cnt++;
				}
				$validPost = true;
			}
		}
		if (($action=="ft") && (isset($_POST['id']))) {
			// Parse Flag Times tg,tt,tb must be 1 to 86400 or 1 day
			$validPost = true;
			if(isset($_POST['tg'])) {
				$timegreen = filter_var($_POST['tg'], FILTER_SANITIZE_NUMBER_INT);
				if (($timegreen>86400) || ($timegreen<0)) {
					$validPost = false;
					echo("TG $timegreen INVALID\r\n");
				}
			}
			if(isset($_POST['tt'])) {
				$timetan = filter_var($_POST['tt'], FILTER_SANITIZE_NUMBER_INT);
				if (($timetan>86400) || ($timetan<0)) {
					$validPost = false;
					echo("TT $timetan INVALID\r\n");
				}
			}
			if(isset($_POST['tb'])) {
				$timeblue = filter_var($_POST['tb'], FILTER_SANITIZE_NUMBER_INT);
				if (($timeblue>86400) || ($timeblue<0)) {
					$validPost = false;
					echo("TB $timeblue INVALID\r\n");
				}
			}
			if(isset($_POST['id'])) {
				$flagid = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
				if (($flagid>20) || ($flagid<1)) {
					$validPost = false;
					echo("ID $flagid INVALID\r\n");
				}
			}
			if ($validPost) {
				$sql = "UPDATE flagstatus SET greentime=$timegreen,tantime=$timetan,bluetime=$timeblue WHERE flagid = $flagid";
				$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
				if ($conn->connect_error) {
					//die ('FAIL:DBConnect: ' . $conn->connect_error);
					http_response_code(500);
					die ('FAIL:DBConnect:');
				}
				if ($conn->query($sql) === TRUE) {
				} else {
					http_response_code(500);
					echo("FAIL SQL:$sql\r\n");
					die ("FAIL:FTUPDATE");
				}
			}
		}
		if (isset($_POST['fb'])) {
			// Flag Battery Array data is sent. Unpack it and validate
			$b64Array = filter_var($_POST['fb'], FILTER_SANITIZE_STRING);
			if (strlen($b64Array)!=28) {
				// Invalid length for a 20 flag battery byte array. Toss it.
				$b64Array = "";
				$validPost = false;
				http_response_code(500);
			} else {
				$binArray = unpack("C*",base64_decode($b64Array));
				$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
				if ($conn->connect_error) {
					http_response_code(500);
					die ('FAIL:DBConnect:');
				}
				$cnt = 0;
				foreach ($binArray as $item) {
					$sql = "UPDATE `flagstatus` SET `battery`=".$item." WHERE `flagid`=".($cnt+1);
					if ($conn->query($sql) === TRUE) {
					} else {
						http_response_code(500);
						echo("FAIL SQL:$sql\r\n");
						die ("FAIL:FBUPDATE");
					}
					$cnt++;
				}
				$validPost = true;
			}
		}
		if ($action=="gsd") {
			$validPost = true;
		}
	}
}
if ($validPost) { // Valid post sent and processed, send back the desired flag states.
	if ($action=="gsd") {
		$sql = "SELECT spawndelay FROM `desiredstatus` ORDER BY flagid";
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME);
		if(!$result = $conn->query($sql)){
			http_response_code(500);
			echo("FAIL SQL:$sql\r\n");
			die ("FAIL:FBUPDATE");
		} else {
			$firstloop = true;
			while($row = $result->fetch_assoc()) {
				if (!$firstloop) {
					echo(",");
				}
				echo($row["spawndelay"]);
				$firstloop = false;
			}
		}
	} else {
		$sql = "SELECT enabled+(mode<<1)+(owner<<4) AS flagval FROM `desiredstatus` ORDER BY flagid";
		if(!$result = $conn->query($sql)){
			http_response_code(500);
			echo("FAIL SQL:$sql\r\n");
			die ("FAIL:FBUPDATE");
		} else {
			$firstloop = true;
			while($row = $result->fetch_assoc()) {
				if (!$firstloop) {
					echo(",");
				}
				echo($row["flagval"]);
				$firstloop = false;
			}
		}
	}
} else {
	http_response_code(500);
	echo("BAD POST\r\n");
}
//var_dump($_REQUEST);
//$greentime = filter_var($_POST['g'], FILTER_SANITIZE_NUMBER_INT);
//$hash = filter_var($_POST['hash'], FILTER_SANITIZE_STRING);
?>
