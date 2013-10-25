<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/notifications.php');


$babel = new BabelFish('user');

$level = $_REQUEST['level'];
$friend = $_REQUEST['to'];
$me = get_logged_in_user_id();
$accepted = 0;

// check to see if friend request is already there
$q = "SELECT * FROM friends WHERE user1 = $me and user2 = $friend";
$r = get_query($q);
$c = get_rows($r);

if ($c > 0 ){
	echo $babel->say('p_friend_relation_exists');
	exit(1);
}

// check to see if counterpart request already exists
$q = "SELECT * FROM friends WHERE user1 = $friend and user2 = $me";
$r = get_query($q);
$c = get_rows($r);
ilog($q);
if ($c > 0 ){
	// Ok a friend is already there.. set accepted to 1
	$accepted = 1;
	// Update the friend request counterpart
	$q = "UPDATE friends set accepted = 1 where user1 = $friend and user2 = $me";
	ilog($q);
	get_query($q);

	// notify of the accept request
	notify($friend, 'user',$me,'friends');

	// Start following this user


}
else {
	// this is a new friend request send mail notification only
	$name = $_SESSION['name'];
	$content = $name .' '.$babel->say('p_friendrequest',false).'<br><br>';
	$action = 'friendrequest';
	send_mail($friend,'subject_newfriendrequest','youhave_friendrequest',$content,$action);
			
}

// Start following this user too
// check to see if following person is already there
$q = "SELECT * FROM following WHERE source_id = $friend and source_obj = 'user' and user_id_following = $me";
$r = get_query($q);
$c = get_rows($r);

if ($c == 0 ){
	// add follow request
	$q = "INSERT INTO following (source_id,source_obj,user_id_following) VALUES ($friend,'user',$me)";
	get_query($q);
}



$q = "INSERT INTO friends (user1,user2,level,accepted) VALUES ($me,$friend,$level,$accepted)";
get_query($q);

if (get_errors()){
	echo "error!";
}
else {
	echo $babel->say('p_add_friend_success');
}
?>