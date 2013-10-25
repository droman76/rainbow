<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$babel = new BabelFish('user');

$follow = $_REQUEST['person'];
$me = get_logged_in_user_id();
$accepted = 0;

// check to see if following person is already there
$q = "SELECT * FROM following WHERE source_id = $follow and source_obj = 'user' and user_id_following = $me";
$r = get_query($q);
$c = get_rows($r);

if ($c > 0 ){
	echo $babel->say('p_following_relation_exists');
	exit(1);
}
// add follow request
$q = "INSERT INTO following (source_id,source_obj,user_id_following) VALUES ($follow,'user',$me)";
get_query($q);

if (get_errors()){
	echo "error!";
}
else {
	echo $babel->say('p_follow_user_success');
}
?>