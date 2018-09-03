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
	<title> Flag Admin Training</title>
	<link rel="stylesheet" type="text/css" href="/field/main.css">
	<meta name="robots" content="noarchive">
	<meta name="robots" content="noindex">
</head>
<body>
<h1>Web Enabled Field Instructions</h1>	
	<p>Remember, once the field is online, you dont have to be on the field, or even in the state to control it. Any internet browser against this website can control the page. That also means you should NEVER give out the password to players or  staff!</p>
	<h2>Powering on the field</h2>
	<p>The field consists of flag nodes, a gateway, an Internet hostspot, the Internet web server, and the clients (your phone). The order to power up is hotspot, gateway, then flags. Flags will sleep for 30-60 seconds before attempting to reconnect when offline, so initial field startup may take several minutes.</p>
	<ol>
	<li>Power on the Internet hot-spot, located in the comms building, and allow it a minute to connect.</li>
	<li>Power on the gateway (pushbutton on bottom, with a green LED next to the button). It will take 30 seconds to come up.</li>
	<li>Power on each flag you wish to use. You can power on all flags and just enable the ones you need, or save battery and just power on the ones you are using immediately.</li>
	<li>Flags will "relay" for each other, so you must have a path back to comms, for any flag more than 300 feet away. For example, the Mosque flag must relay thru chop-shop. Or, Flag 13 must relay thru flag 7 or 8 and then possibly flag 6. SO even if you are not using flag 6, if you ARE using flag 13, you need the flags in the relay path back turned on.</li>
	<li>Flags will report status to the website. Use the website to check to see when the last time a flag checked in to verify it is online and reporting.</li>
	</ol>

	<h2>Field operation</h2>
	<ol>
	<li>All flags start as off. This isnt actually powered off, its a 'disabled' sleeping state, where they do not respond to "all field" settings, and check in occasionally.</li>
	<li>Setting a flag to "sleeping" or "off" will clear any data it saves for ownership.</li>
	<li>A sleeping flag will only check in once every 25-60 seconds but will relay for other flags.</li>
	<li>An online flag will check in once every 10 seconds when alive. They will return status to the server within 10 seconds of receiving a command. So your round-trip time to set any one flag can be up to 20-30 seconds.</li>
	<li>Click each flag icon to change its state.</li>
	<li>Click and "all field" button to change ALL active flags to that mode quickly.</li>
	<li>CAUTION. "BLIND MAN" WILL ENABLE AND SET ALL FLAGS TO BLIND MAN MODE.  Blind man will set the entire field to active and blind man alert mode. You will need to set all flags back to Standby or game on and set ones you do not need to sleeping to restart the game.</li>
	<li>MAP MODE - click the top left button to change the map so it displays the flag modes (and allows you to change mode), or displays owner data (and allows you to change ownership), or displays battery info (read only).</li>
	<li>Custom Games - Custom games set owner and mode for all flags on the field to a pre-set state. You can also change the name and set the field up as you want then click the SET button for that custom mode to record the current field state as a new custom mode.</li>
	</ol>
	<h2>Powering down the field</h2>
	<ol>
	<li>Make note of any flags that need batteries changed or service.</li>
	<li>Set the field to sleeping or offline so the web status page is reset and no flags are stil showing colors/lights.</li>
	<li>Give the field a minute to send the offline/sleep command to all flags.</li>
	<li>Once everything is asleep, it doesent matter really what is powered down first. The Hotspot should always be powered down when not in use to save battery and data (limited to 500mb/month, which isnt much!).</li>
	</ol>
	<h2>Maintenance</h2>
	<ol>
	<li>All flags use internal batteries (and eventually, solar panels to recharge them). At this time, we use 18650 Lipo (protected!) cells in the flags and gateway.</li>
	<li>If a flag uses more than one battery, replace BOTH at the same time and ensure BOTH batteries are fully charged.</li>
	<li>Charge any LiPo with an approved smart charger!</li>
	<li>Batteries will charge is a solar panel is attached and the sun is out. The flags do NOT need to be turned on to charge.</li>
	<li>If a flag cannot connect, ensure the antenna is snug (be gentle). Ensure the power light comes on when powered up. Open the case and look for signs of water damage or infestation.</li>
	<li>Worst case bring the flag in and swap it with a spare. The spare must be programmed with the flag number to show correctly on the field. You can take a flag from a remote location and swap it but the location on the website will still show the original spot.</li>
	</ol>
	<p>This web application is restricted. Do not share the password for this website with players or  staff.</p>

	<!-- Start Footer -->
	<footer id="contentinfo" class="body">
		<p>&copy; 2016&ndash;2018  Airsoft, &copy; 2016 Reid Bush</p>
	</footer>
	<!-- End Footer -->
	<?php //var_dump($_POST) ?>
</body>
</html>