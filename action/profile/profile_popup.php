<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$CONFIG = $_SESSION['CONFIG'];
$babel = new BabelFish('profile');

$userid = $_REQUEST['userid'];
$me = $_SESSION['userid'];

$sql = "SELECT * from users where id = $userid";
$result = get_query($sql);
$user = $result->fetch_object();
$view = $_REQUEST['view'];
$sql = "SELECT * from userprofile where userid = $userid";
$result = get_query($sql);
$profile = $result->fetch_object();
$username = $user->username;
$bio = $profile->bio;
$intro = $profile->intro;
$name = $user->name;
$avatar_pic = get_avatar_image($username);

$q = "select accepted from friends where user1=$me and user2=$userid";
$r = get_query($q);
$friender = $r->fetch_object();

$q = "select * from following where user_id_following = $me and source_obj = 'user' and source_id=$userid";
$r = get_query($q);
$follower = $r->fetch_object();

$sql = "select * from messaging where (sender=$me and recipient=$userid) or (recipient=$me and sender=$userid) order by sent DESC";
//ilog(sql);
$messages = get_query($sql);



//echo "Profile loading $user->username userid: $userid";

$avatar_cover = $CONFIG->siteroot. "myimages/$username/cover_small".".jpg";
?>
<style>
.popup-cover {
   background: #6cab26;
   background-image: url('<?=$avatar_cover?>'); /* fallback */
   background-image: url('<?=$avatar_cover?>'), -webkit-gradient(linear, left top, left bottom, from(#444444), to(#999999)); /* Saf4+, Chrome */
   background-image: url('<?=$avatar_cover?>'), -webkit-linear-gradient(top, #444444, #999999); /* Chrome 10+, Saf5.1+ */
   
}
</style>


<a onclick='_("friendbox").style.display="none";_("followbox").style.display="none";_("messagebox").style.display="block";_("cover").style.display="none";messaging=true;_("textmessage").focus()'>Message</a>  &#183; 

<?php if ($me != $userid) {?> 


<?php if ($friender != null) {?>
 	<?php if ($friender->accepted == 1){ ?>
	<span style='color:gray'><?=$babel->say('p_youarefriends')?></span> &#183;
	<?php } else { ?>
	<span style='color:gray'><?=$babel->say('p_friend_request_sent')?></span> &#183; 
	<?php } ?>
<?php } else { ?>


<a onclick='_("messagebox").style.display="none";_("followbox").style.display="none";_("friendbox").style.display="block";_("cover").style.display="none";addfriend=true;'><?=$babel->say('p_addfriend')?></a>  &#183; 

<?php } ?>

<?php if ($follower != null ) {?>
<span style='color:gray'><?=$babel->say('p_following_person')?></span>

<?php } else { ?>
<a onclick='_("messagebox").style.display="none";_("friendbox").style.display="none";_("followbox").style.display="block";_("cover").style.display="none"'><?=$babel->say('p_follow')?></a>

 <?php } ?>


<?php } ?>

<?php if ($view == 'image') {?>
 <div id='cover'>
	<div class='popup-cover' style="border-color:#9599d5;margin-top:5px;position:relative;left:-10px;border-top-width:1px;border-style:solid;width:345px;height:100px;">
	</div>
	<div>
		<span style='float:left;width:100px;position:relative;top:-30px;margin-right:20px;left:10px`'>
		<img style="width:100px;border-width:7px;border-color:#407df9;border-style:solid" width=100 src='<?=$avatar_pic?>'>
		</span>
		<div style='margin-top:3px;height:100px'><h3><?=$name?></h3><?=$intro?></div>
	</div>
 </div>
<?php } else {?>
<div id='cover'></div>
<?php } ?>

<div id='messagebox' style='display:none'>
 
 <textarea id="textmessage" name='message' class='feed-textarea feed-message' onkeyup='resize_textarea(this,40)'></textarea>
<button onclick='sendMessage(<?=$userid?>)'><?=$babel->say('p_sendmessage')?></button>
<div>
<h4><?=$babel->say('p_previous_messages')?></h4>
<?php while($inbox = $messages->fetch_object()){
	$q = "select name from users where id = ".$inbox->sender;
	$name = get_query($q)->fetch_object()->name;

	echo '<b>'.$name . ":</b> ";
	echo $inbox->message;
	echo " <i><font color='gray'> ".ago($inbox->sent);
	echo " ".$babel->say('p_ago')."</font></i>";
	echo '<br/>';
}
?>
</div>
 </div>

 <div id='friendbox' style='display:none'>
	<hr><h3><?=$babel->say('p_addfriend')?></h3><br>
	<?=$babel->say('p_friendinfoadd')?>
	<br><br><b><?=$babel->say('p_connectionlevel')?>:</b>
	<select name='connectionlevel' id='friendlevel'>
	<option value='0'>Virtual - Never met in person</option>
	<option value='1'>Superfical - Brief encounter</option>
	<option value='2'>Good - Vibed together</option>
	<option value='3'>Strong - Really vibe with each other</option>
	</select><br><br>
	<button onclick='addFriend(<?=$userid?>,false)' class='rainbow-button rainbow-button-submit'>+ <?=$babel->say('p_addfriend')?></button>
	
</div>

<div id='followbox' style='display:none'>
	<hr><h3><?=$babel->say('p_follow')?></h3><br>
	<?=$babel->say('p_followinfoadd')?>
	<br><br>
	<button onclick='followPerson(<?=$userid?>)' class='rainbow-button rainbow-button-submit'>+ <?=$babel->say('p_addfollow')?></button>
	
</div>


