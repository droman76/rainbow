<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$babel = new BabelFish('user');

$me = get_logged_in_user_id();
$friend = $_REQUEST['userid'];

// check to see if friend request is already there
$q = "update friends set accepted=-1 where user2=$me and user1= $friend";
$r = get_query($q);


echo $babel->say('p_rejected_friend_request');


?>
