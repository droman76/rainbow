<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$CONFIG = $_SESSION['CONFIG'];
$babel = new BabelFish('messaging');

$userid = $_REQUEST['userid'];
$loadtime = $_REQUEST['loadtime'];

if (!isset($loadtime) || $loadtime == 0) $loadtime = time();

$me = get_logged_in_user_id();

$sql = "select * from messaging where ((recipient=$me and sender=$userid)) and sent > $loadtime order by sent ASC";
$messages = get_query($sql);

$q = "select name,username,id from users where id = ".$userid;	
$conversationuser = get_query($q)->fetch_object()->name;

$c = get_rows($messages);
if ($c== 0) {
	echo "nodata";
	exit(1);
} 
$start = 0;
?>
<?php

while($inbox = $messages->fetch_object()){
	
	$messageid = $inbox->id;
	$q = "select id,name,username from users where id = ".$inbox->sender;	
	$user = get_query($q)->fetch_object();
	$name = $user->name;
	$xuserid = $user->id;
	$name = explode(" ", $name);
	$name = $name[0];	
	$username = $user->username;
	$img = get_avatar_image($username,'small');
	$msg = $inbox->message;
	$ago = ago($inbox->sent);

	if ($start == 0 && $userid == -1) {
			
		$conversationuser = $user->name;
		$userid = $xuserid;
		
	}

	include($CONFIG->home.'page/messaging/conversation_item.php');
	$start++;

} 

?>*::*<?=time()?>