<?php 

session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 

$city = $_REQUEST['city'];
$country = $_REQUEST['country'];

$address = "$city, $country";
$address = str_replace(' ', '+', $address);


$url = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=$address";
ilog($url);
ilog (file_get_contents($url));
$r = json_decode(file_get_contents($url));

$components = $r->results[0]->address_components;


foreach($components as $item) {
	if ($item->types[0] == 'country'){
		$country_code = $item->short_name;
		$country = $item->long_name;
	}
	if ($item->types[0] == 'administrative_area_level_1') {
		$region = $item->short_name;
	}
	if ($item->types[0] == 'locality') {
		$city = $item->long_name;


	}

}
ilog("Parsed city $city -- region $region -- country $country -- countrycode $country_code");

echo "$city**$region**$country";





?>