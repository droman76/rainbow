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

$sql = "select users.id,username,name from friends,users where accepted=0 and users.id = user1 and user2 = $me";
//ilog(sql);
$messages = get_query($sql);
?>

<div id='inbox_container' class='inbox'>
	<span onclick='closeFriendInbox()' style='z-index:1;height:10px;margin-right:2px;float:right;margin-top:2px;'><img src="<?=$CONFIG->siteroot?>template/default/_graphics/closebox.png"></span>

	<span style='position:relative;top:3px;font-size:14px;left:5px'><b style='margin-top:2px'>Friend Requests</b></span><hr style='z-index:400' class='separator'>
	<ul>
<?php
$stack = array();
$reply = '';
$count = get_rows($messages);
if ($count == 0) echo '<div style="font-size:14px;color:gray;margin:10px">'.$babel->say('p_norequests').'</div>';


while($inbox = $messages->fetch_object()){
	$userid = $inbox->id;
	$name = $inbox->name;
	$username = $inbox->username;
	$image = get_avatar_image($username,'small');
?>
	<div style='padding:5px' id='fr-<?=$userid?>'>
	<span style='float:left;margin-right:5px'><img src='<?=$image?>'></span>
	<span style='font-size:18px'><a href='/profile/<?=$username?>'><?=$name?></a></span><br>
	<div id='action-<?=$userid?>' style='margin-top:4px'>
		<button onclick='confirmFriend(<?=$userid?>)'><?=$babel->say('p_accept_friend')?></button>
		<button onclick='rejectFriend(<?=$userid?>)'><?=$babel->say('p_reject_friend')?></button>
	</div>
	<div id='confirm-<?=$userid?>' style='display:none;margin-top:4px'>
		<div id='friendbox'>
			<br><br><h3><?=$babel->say('p_addfriend')?></h3><br>
			<?=$babel->say('p_friendinfoadd')?><br><br>
			<b><?=$babel->say('p_connectionlevel')?>:</b>
			<select name='connectionlevel' id='friendlevel-<?=$userid?>'>
			<option value='0'>Virtual - Never met in person</option>
			<option value='1'>Superfical - Brief encounter</option>
			<option value='2'>Good - Vibed together</option>
			<option value='3'>Strong - Really vibe with each other</option>
			</select><br><br>
			<button onclick='acceptFriend(<?=$userid?>)' class='rainbow-button rainbow-button-submit'>+ <?=$babel->say('p_addfriend_yes')?></button>
			
		</div>
	</div>
	
	</div>
<?php } ?>
</ul>
</div>
