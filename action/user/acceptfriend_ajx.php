<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/notifications.php');


$babel = new BabelFish('user');

$me = get_logged_in_user_id();
$friend = $_REQUEST['userid'];
$level = $_REQUEST['level'];

// check to see if friend request is already there
$q = "insert into friends (user1,user2,level,accepted) values ($me,$friend,$level,1)";
$r = get_query($q);

$q = "update friends set accepted=1 where user2=$me and user1=$friend";
$r = get_query($q);

// Start following this user, for notifications etc
// check to see if following person is already there
$q = "SELECT * FROM following WHERE source_id = $friend and source_obj = 'user' and user_id_following = $me";
$r = get_query($q);
$c = get_rows($r);

if ($c == 0 ){
	// add follow request
	$q = "INSERT INTO following (source_id,source_obj,user_id_following) VALUES ($follow,'user',$me)";
	get_query($q);
}


notify($friend, 'user',$me,'friends');

echo $babel->say('p_accepted_friend_request');


?>
