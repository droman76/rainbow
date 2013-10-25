<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$babel = new BabelFish('feed');
$me_id = get_logged_in_user_id();

$post_id = $_REQUEST['postid'];
$user_id = get_logged_in_user_id();

if (isset($_REQUEST['commentid']) && $_REQUEST['commentid'] != -1){
	$comment_id = $_REQUEST['commentid'];
	$source_id = $comment_id;
	$source_obj = 'comment';
	$note = 'p_comment_blessed';
}
else {
	$source_obj = 'post';
	$comment_id = -1;
	$source_id = $post_id;
}
$action = "blessing";

// delete the like/blessing
$qr = "delete from likes where post_id = $post_id and comment_id = $comment_id and user_id = $user_id";
$res = get_query($qr);

// Now delete notification for all users following this post/comment
$sql = "delete from notifications where target = $source_id and object = '$source_obj' and action = 'blessing'";
ilog($sql);
$r = get_query($sql);
if (get_errors()){
	echo "error";
}

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