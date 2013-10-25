<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('messaging');

$to = $_REQUEST['to'];
$from = $_SESSION['userid'];
$message = strip_tags($_REQUEST['message']);
$view = $_REQUEST['view'];

// sanitize the message for saving
$msg = strip_tags($message);
db_escape($message);
$time = time();

if ($to == -1) {
	// find out who we are really sending to
	$sql = "select * from messaging where (sender=$from) or (recipient=$from) order by sent DESC";
	$messages = get_query($sql);
	if ($m = $messages->fetch_object()) {
		$r = $m->recipient;
		$s = $m->sender;
		if ($r != $from) $to = $r;
		else $to = $s;
	}
}

$sql = "INSERT INTO `messaging` (`recipient`, `sender`, `message`, `sent`) VALUES ($to, $from, '".db_escape($msg)."', $time)";

if(get_query($sql)){ 
	if ($view == 'main') {
	$id = get_insert_id();
	$name = $_SESSION['name'];
	$name = explode(" ", $name);
	$name = $name[0];
	$username = $_SESSION['username'];
	$img = get_avatar_image($username,'small');
	$ago = ago(time());
	$reply="<img src='".$CONFIG->siteroot."template/default/_graphics/reply.png'>";
	
	include($CONFIG->home.'page/messaging/conversation_item.php');
 	}
 	else
 	{
 		echo $babel->say('p_message_success');
 	}
}
else {
	echo 'error: '.get_errors();
}


?>