<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/image_resize.php');


$CONFIG = $_SESSION['CONFIG'];
$babel = new BabelFish('feed');

$city = $_REQUEST['city'];
$region = $_REQUEST['region'];
$countrycode = $_REQUEST['countrycode'];

$_SESSION['city'] = $city;

if ($countrycode == 'CA' || $countrycode == 'US')
 $_SESSION['region'] = $region;
/*
Only city location update is allowed...
$_SESSION['region'] = $_REQUEST['region'];
$_SESSION['country'] = $_REQUEST['country'];
$_SESSION['country_code'] = $_REQUEST['countrycode'];
$_SESSION['continent_code'] = $_REQUEST['continent'];
*/
ilog("********* LOCATION UPDATE ***********");
if (isset($_SESSION['city']) && isset($_SESSION['region']) && isset($_SESSION['country'])) 
	$location = $babel->say($_SESSION['city']) . ' '.$babel->say($_SESSION['region']).', '.$babel->say($_SESSION['country']);
else if (isset($_SESSION['region']) && isset($_SESSION['country'])) 
	$location = $_SESSION['region'].', '.$_SESSION['country'];
else if (isset($_SESSION['country'])) 
	$location = $_SESSION['country'];

$me = get_logged_in_user_id();
// update the user location in database
if ($countrycode == 'CA' || $countrycode == 'US') {
	$q = "update users set city = '$city', region = '$region' where id = $me";
	ilog("Updating user location! $q");
}
else {
	ilog("Updating user location! $q");
	$q = "update users set city = '$city' where id = $me";
}
get_query($q);

echo $location;


?>