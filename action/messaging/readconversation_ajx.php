<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$CONFIG = $_SESSION['CONFIG'];
$babel = new BabelFish('messaging');

$userid = $_REQUEST['userid'];
$me = $_SESSION['userid'];

if ($userid == -1) {
	// figure out the user id for the first message based on the last message sent
	$sql = "select * from messaging where (sender=$me) or (recipient=$me) order by sent DESC LIMIT 1";
	$r = get_query($sql);
	if ($o = $r->fetch_object()) {
		$sx = $o->sender;
		$rx = $o->recipient;

		if ($sx == $me) $userid = $rx;
		else $userid = $sx;
	}

}

$sql = "select * from messaging where (sender=$me and recipient=$userid) or (recipient=$me and sender=$userid) order by sent ASC";
$messages = get_query($sql);

if ($userid > 0) {
	$q = "select name,username,id from users where id = ".$userid;
	$o = get_query($q)->fetch_object();	
	$conversationuser = $o->name;
	$cusername = $o->username;
}

?>


<?php 
$start = 0;

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
		$cusername = $user->username;


		$userid = $xuserid;
		if ($inbox->sender == $inbox->recipient) $conversationuser = $babel->say('p_talkingtomyself',false);
	}
	
	if ($start == 0) { ?>
	<span style='position:relative;top:3px;font-size:14px;left:5px'>
		<span style='float:left;margin-left:5px'>
			<img src='/template/default/_graphics/yantrasmall.png'>
		</span>
		<h3 style='margin-bottom:12px;margin:7px;font-size:18px;'>Conversation with <a href='/profile/<?=$cusername?>'><?=$conversationuser?></a></h3>
	</span>
	<hr style='margin-top:7px' class='separator'>
	*::*  
	<div id='conversation_container' style='' class='inbox'>
  	
  	
	<?php } 

	include($CONFIG->home.'page/messaging/conversation_item.php');
	$start++;

} 

?>
</div>*::*<?=$userid?>*::*<?=time()?>

