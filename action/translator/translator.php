<?php
session_start();
include('language.php');
include($_SESSION['home']. "lib/functions.php");
include($_SESSION['home']. "lib/babelfish.php");





$page = $_REQUEST['page'];
$key = $_REQUEST['key'];
$translation = $_REQUEST['translation'];



$language = $_SESSION['user_language'];
//$locale = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);

$babel = new BabelFish($page);

if (strlen(str_replace(' ', '', $translation)) == 0) {
	echo $babel->say($key);
	return;
}
// first read the json file:
$babel->set($key,$translation);
$babel->save();

echo $translation;






?>