<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'page/feed/feediterator.php'); 
$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('feed');

$commentid = $_REQUEST['commentid'];
$postid = $_REQUEST['postid'];
$me = get_logged_in_user_id();

// TODO: create an entry on the deleted table for tracking and restoring
$q = "delete from feed_comments where comment_id = $commentid";

if (get_query($q)){
	

}
else {
	echo "error: ".$babel->say('p_postdeletefailed');
}

// Delete notifications
$q = "delete from notifications where sender = $me and target = $commentid and action = 'commented'";
if (get_query($q)){
	echo $babel->say('p_commentdeleted');

}
else {
	echo "error: ".$babel->say('p_deletenotificationfailed');
}

?>