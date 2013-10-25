<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/notifications.php'); 


$babel = new BabelFish('feed');

$post_id = $_REQUEST['postid'];
if (isset($_REQUEST['commentid']) && $_REQUEST['commentid'] != -1){
	$comment_id = $_REQUEST['commentid'];
	$source_id = $comment_id;
	$source_obj = 'comment';
	$note = 'p_comment_blessed';
} else {
	$comment_id = -1;
	$source_obj = 'post';
	$source_id = $post_id;
	$note = 'p_post_blessed';
}
$action = 'blessing';
$user_id = get_logged_in_user_id();
$me_id = $user_id;


// check that there is not already a like/blessing for this item
$qr = "select * from likes where post_id = $post_id and comment_id = $comment_id and user_id = $user_id";
$res = get_query($qr);
ilog($qr);
if (get_rows($res) > 0){

	echo "You have already blessed this item.";
	exit(1);
}

$sql = 'insert into likes (post_id,comment_id,user_id) VALUES ';
$sql .= "($post_id,$comment_id,$user_id)";

$r = get_query($sql);

if (get_errors()) {
	echo "There was a database error.. Contact focalizer.";
	exit(1);
}


// Add notification for this blesing
notify($me_id,$source_obj,$source_id,'bless');


// Retrieve the number of blessings
if ($comment_id != -1) {
	$q = "select count(comment_id) as items from likes where post_id = $post_id and comment_id = $comment_id";
	$r = get_query($q);
	$o = $r->fetch_object();
	$likes = $o->items;
	echo $likes;
	exit(1);
}


// retrieve the blessings number for the post
$q = "select post_id, user_id, username, users.name from likes, users where post_id = $post_id and comment_id = $comment_id and users.id = user_id";
$r = get_query($q);
// TODO: optimize this.. perhaps move out of loop into batch?
$blessings = 0;
$you = false;
$namestring = '';
while($or = $r->fetch_object()){
	$un = $or->username;
	$n = $or->name;
	$i = $or->user_id;
	if ($i == $me_id) $you = true;
	if ($blessings < 3 && $i != $me_id)
		$namestring .= "<a href='/profile/$un'>$n</a> ";
	$blessings++;
}
$btext = '';
$btext = '';



if ($you && $blessings == 1) $btext = $babel->say('p_you') .' '. $babel->say('p_blessed_this');
else if (!$you && $blessings == 1) $btext = $namestring .' '. $babel->say('p_blessed_this_only');
else if ($you && $blessings > 1 && $blessings < 3) $btext = $babel->say('p_you') .' '.$babel->say('p_and') .' '. $namestring . ' '. $babel->say('p_blessed_this');
else if ($you && $blessings >= 3) $btext = $babel->say('p_you') .' '. $babel->say('p_and') .' '. ($blessings-1) . ' '. $babel->say('p_people') . ' '. $babel->say('p_blessed_this');
else if (!$you && $blessings > 1 && $blessings < 3) $btext = $namestring . ' '. $babel->say('p_blessed_this');
else if (!$you && $blessings >= 3) $btext = $blessings . ' '. $babel->say('p_people') . ' '. $babel->say('p_blessed_this');

echo $btext;


?>