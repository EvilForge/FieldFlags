<?php
define('DB_NAME', 'xxxxx');
define('DB_USER', 'xxxxx');
define('DB_PASSWORD', 'xxxxx');
define('DB_HOST', 'localhost');
define('HTTP_SERVER', 'http://localhost/field/');
define('HTTP_PATH', '/field/');
define('IMAGES', '/field/images/');

function getMode($sentMode) {
  switch($sentMode) {
    case 0:
    echo "Sleeping";
    break;
    case 1:
    echo "Standby";
    break;
    case 2:
    echo "Game On";
    break;
    case 3:
    echo "Two Minutes";
    break;
    case 4:
    echo "Game End";
    break;
    case 5:
    echo "Blind Man";
    break;
  }	
}

function getOwner($sentOwner) {
  switch ($sentOwner) {
    case 0:
    echo "No Owner";
    break;
    case 1:
    echo "Green";
    break;
    case 2:
    echo "Tan";
    break;
    case 3:
    echo "Blue";
    break;
  }
}

?>