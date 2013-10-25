<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/cronos.php');

$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('messaging');

$view = $_REQUEST['view'];
$me = $_SESSION['userid'];
$userid = $_REQUEST['userid'];
$isnew = cronos_get('mail');

$sql = "select * from messaging where (sender=$me) or (recipient=$me) order by sent DESC";
//ilog(sql);
$messages = get_query($sql);
$count = get_rows($messages);

?>
<div id='inbox_container' class='inbox'>
	<span onclick='closeInbox()' style='z-index:1;height:10px;margin-right:2px;float:right;margin-top:2px;'><img src="<?=$CONFIG->siteroot?>template/default/_graphics/closebox.png"></span>

	<span style='position:relative;top:3px;font-size:14px;left:5px'><b style='margin-top:2px'>Message Inbox</b></span><hr style='z-index:400' class='separator'>
	<ul>

<?php
$stack = array();
$reply = '';
if ($count == 0) echo '<div style="font-size:14px;color:gray;margin:10px">'.$babel->say('p_nomessages').'</div>';
$start = 0;

while($inbox = $messages->fetch_object()){
	$loto = $inbox->sender;
	
	$messageid = $inbox->id;
	if ($inbox->sender == $me) {
		$loto = $inbox->recipient;
		$reply="<img src='".$CONFIG->siteroot."template/default/_graphics/reply.png'>";
	}
	if (in_array($loto, $stack)) continue;
	
	if ($loto == $userid || ($start == 0 and $userid == -1)) {
		$highlight = 'message-selected';
	}
	else {
		$highlight = '';
	}
	ilog($loto." <> ".$userid);
	array_push($stack, $loto);
	if ($inbox->sent >= $isnew){
		$highlight = 'message-new-inbox';
	}
	if ($reply != ''){
		$q = "select name,username from users where id = ".$inbox->recipient;
	}
	else {
		$q = "select name,username from users where id = ".$inbox->sender;	
	}
	$user = get_query($q)->fetch_object();
	$name = $user->name;
	
	$username = $user->username;
	$img = get_avatar_image($username,'small');
	$msg = $inbox->message;
	$ago = ago($inbox->sent);

	if ($view == 'main'){
		$width = '220px';
		$id =  "id='message_$loto'";
		$msg = strim($msg,37);
	
	}
	else {
		$width = '350px';
		$id = '';
		$msg = strim($msg,55);
	}


	include($CONFIG->home.'page/messaging/inbox_item.php');
	$start++;
?>

<?php 
$reply = '';
}
cronos_update('mail');


?>
</ul>
</div>
