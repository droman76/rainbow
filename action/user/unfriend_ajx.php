<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$babel = new BabelFish('user');

$level = $_REQUEST['level'];
$friend = $_REQUEST['to'];
$me = get_logged_in_user_id();
$accepted = 0;


$q = "delete FROM friends WHERE user1 = $me and user2 = $friend";
$r = get_query($q);

$q = "delete FROM friends WHERE user1 = $friend and user2 = $me";
$r = get_query($q);


if (get_errors()){
	echo "error!";
}
else {
	system_msg($babel->say('p_un_friend_success'));
}
?>