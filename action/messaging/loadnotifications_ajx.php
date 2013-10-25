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
$isnew = cronos_get('notifications');

$sql = "select * from notifications,users where users.id = sender and sender != $me and recipient = $me order by sent DESC";
//ilog(sql);
$messages = get_query($sql);
?>

<div id='inbox_container' class='inbox'>
	<span onclick='closeNotificationInbox()' style='z-index:1;height:10px;margin-right:2px;float:right;margin-top:2px;'>
	<img src="<?=$CONFIG->siteroot?>template/default/_graphics/closebox.png">
	</span>

	<span style='position:relative;top:3px;font-size:14px;left:5px'><b style='margin-top:2px'><?=$babel->say('p_notificationstitle')?></b></span><hr style='z-index:400' class='separator'>
	<ul>
<?php
$stack = array();
$reply = '';
$count = get_rows($messages);
if ($count == 0) echo '<div style="font-size:14px;color:gray;margin:10px">'.$babel->say('p_nonotifications',false).'</div>';


while($inbox = $messages->fetch_object()){
	$userid = $inbox->id;
	$name = $inbox->name;
	$highlight = '';
	$username = $inbox->username;
	$image = get_avatar_image($username,'small');
	$object_name = $inbox->object;
	$object_id = $inbox->target;
	$action = $inbox->action;
	$sent = $inbox->sent;
	$ago =  '<div style="color:gray"> '. ago($sent). " ". $babel->say('p_ago') . "</div>"; 

	$summary = '';
	if ($inbox->sent >= $isnew){
		$highlight = 'message-new-inbox';
	}

	switch ($action) {
		case 'bless':
			$actiontxt =  $babel->say('p_blessed');
			if ($object_name == 'post') {
				// retrieve post info
				$q = "select message from feed where post_id = $object_id";
				$r = get_query($q);
				if ($o = $r->fetch_object()){
					$msg = $o->message;
					//$summary = substr($msg, 0,50) . '...';
				}
			}
			$link = "/feed/$object_id";
			$linktxt = $babel->say('p_readpost');
			break;
		case 'friends':
			$actiontxt =  $babel->say('p_isnowfriend');
			//$summary = $babel->say('p_isnowfriendsummary');
			break;
		case 'commented':
			$actiontxt =  $babel->say('p_commentedonyourpost');
			$q = "select post_id, message from feed_comments where comment_id = $object_id";
			$r = get_query($q);
			if ($o = $r->fetch_object()){
				$summary = $o->message;
				if (strlen($summary) > 37) {
					$i = strpos($summary, ' ',37);
					$summary = substr($summary, 0, $i) . '...';
				}

				//$summary = substr($msg, 0,50) . '...';
				$post_id = $o->post_id;
				$link = "/feed/$post_id#$object_id";
				$linktxt = $babel->say('p_readcomment');


			}
			
			break;
		
		default:
			$actiontxt = $babel->say($action,false) . " $ago ". $babel->say('p_ago');
			$link = '';
			break;
	}
	?>

	<div id='boxitem' onclick='notification_goto("<?=$link?>")' class='notification-item <?=$highlight?>' style='height:50px;padding:5px' id='fr-<?=$userid?>'>
		<span style='float:left;margin-right:5px'><img src='<?=$image?>'></span>
		<span style='font-size:14px'><a href='/profile/<?=$username?>'><?=$name?></a></span> 
		<span><?=$actiontxt?> </span>
		<div style='color:gray;font-face:italic'>
		<?php if ($summary != '') { ?>
		<img src='/template/default/_graphics/reply.png'>
		<?php } ?>
		<?=$summary?></div><div style='font-size:10px'><?=$ago?></div>
	</div>
	
	<hr>
<?php } 

cronos_update('notifications');
?>
</ul>
</div>

