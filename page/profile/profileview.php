
<?php

if (isset($_SESSION['avatarchanged']))$nocache = '?nocache=true';
else $nocache = '';

$img_src = get_avatar_image($username) . $nocache;
//$userid = get_logged_in_user_id();



?>
<div id="avatar_helper" style="display:none"></div>
<div id='cover-popup' class='modal-bg' style='display:none'>

	<div style='left:25%;top:100px;width:500px;height:300px' class="modal">
		<div style='margin:20px'>
		<span style='float:right' ><a onclick='_("cover-popup").style.display="none"'><img  src='/template/default/_graphics/close.png'></a></span>
			
			<h2><?=$babel->say('p_cover')?></h2><hr>
			<p><?=$babel->say('p_choose_cover_info')?>
			<br>
			<form action='<?=$CONFIG->siteroot?>action/profile/upload_cover.php' method='post' enctype="multipart/form-data">
			<input type="hidden" name="cover" value="edit">
			<input type="file" onchange='submit(this)' class="rainbow-input-file" name="image" value="upload file">
			</form>

		</div>
	</div>

</div>
<div id='send-message' class='modal-bg' style='display:none'>

	<div style='left:25%;top:100px;width:500px;height:300px' class="modal">
	<div id='messagebox' style='margin:20px'>
 		<span style='float:right' ><a onclick='_("send-message").style.display="none"'><img  src='/template/default/_graphics/close.png'></a></span>
			
			<h2><?=$babel->say('p_sendmessagetitle')?></h2><hr>
			<p><?=$babel->say('p_sendmessage_info')?>
			<br>
		
		 <textarea id="textmessage" style='height:100px' name='message' class='feed-textarea feed-message' onkeyup='resize_textarea(this,40)'></textarea>
		<button onclick='sendMessage(<?=$userid?>);_("send-message").style.display="none"'><?=$babel->say('p_sendmessage')?></button>
		
		</div>	


	</div>

</div>
<?php $connection = get_friend_connection($userid); ?>
			
<div id='friend-request' class='modal-bg' style='display:none'>

	<div style='font-size:16px;padding:15px;left:25%;top:100px;width:500px;height:300px' class="modal">
		<div id='friendbox' >
			<span style='float:right' ><a onclick='_("friend-request").style.display="none"'><img  src='/template/default/_graphics/close.png'></a></span>
			<h2><?=$babel->say('p_addfriend')?></h2>
			<hr>
			<?=$babel->say('p_friendinfoadd')?>
			<br><br><b><?=$babel->say('p_connectionlevel')?>:</b>
			<select name='connectionlevel' id='friendlevel'>
			<option value='0' <?php if ($connection == 0) echo 'selected' ?>>Virtual - Never met in person</option>
			<option value='1' <?php if ($connection == 1) echo 'selected' ?>>Superfical - Brief encounter</option>
			<option value='2' <?php if ($connection == 2) echo 'selected' ?>>Good - Vibed together</option>
			<option value='3' <?php if ($connection == 3) echo 'selected' ?>>Strong - Really vibe with each other</option>
			</select><br><br>
			<button onclick='addFriend(<?=$userid?>,true);' class='rainbow-button rainbow-button-submit'>+ <?=$babel->say('p_addfriend')?></button>
	
		</div>
	</div>

</div>

<div id='friend-info' class='modal-bg' style='display:none'>
<?php 
if (is_friend($userid)) {
	$title = $babel->say('p_youarefriends');
	$updatebutton = $babel->say('p_updatefriend');
	$withdrawbutton = $babel->say('p_unfriend');

}
else {
	$title = $babel->say('p_pendingfriends');
	$updatebutton = $babel->say('p_updatefriendrequest');
	$withdrawbutton = $babel->say('p_withdrawrequest');
}
?>
	<div style='font-size:16px;padding:15px;left:25%;top:100px;width:500px;height:300px' class="modal">
		<div id='friendbox' >
			<span style='float:right' ><a onclick='_("friend-info").style.display="none"'><img  src='/template/default/_graphics/close.png'></a></span>
			<h2><?=$title?></h2>
			<hr>
			<?=$babel->say('p_friendinfo')?>
			<br><br><b><?=$babel->say('p_connectionlevel')?>:</b>
			<select name='connectionlevel' id='friendlevel2'>
			<option value='0' <?php if ($connection == 0) echo 'selected' ?>>Virtual - Never met in person</option>
			<option value='1' <?php if ($connection == 1) echo 'selected' ?>>Superfical - Brief encounter</option>
			<option value='2' <?php if ($connection == 2) echo 'selected' ?>>Good - Vibed together</option>
			<option value='3' <?php if ($connection == 3) echo 'selected' ?>>Strong - Really vibe with each other</option>
			</select><br><br>
			<button onclick='updateFriend(<?=$userid?>);_("friend-info").style.display="none"' class='rainbow-button rainbow-button-submit'>+ <?=$updatebutton?></button>
			<span style='float:right'><button onclick='removeFriend(<?=$userid?>);' class='rainbow-button '>- <?=$withdrawbutton?></button>
			</span>
		</div>
	</div>

</div>

<div id="container" style="width:900px">
	<span style='position:absolute;left:550px;top:305px'>

	

	<?php if ($fullname == $_SESSION['name']) { ?>	
	<button onclick='location.href="<?=$CONFIG->siteroot?>profile"' style='font-size:15px'><a href='<?=$CONFIG->siteroot?>profile'><?=$babel->say('p_editprofile')?></a></button> 

	<button onclick='_("cover-popup").style.display="block"' style='font-size:15px'><?=$babel->say('p_editcoverpic')?></button> 

	<?php } else { ?>
	<button onclick='_("send-message").style.display="inline";_("textmessage").focus()' style='margin-right:10px;font-size:15px'><?=$babel->say('p_message')?></button>
	<?php if (is_friend_request_pending($userid)) { ?>
	<span style='font-size:16px'><a onclick='_("friend-info").style.display="inline"'><?=$babel->say('p_friendrequestpending')?></a></span>
	
	<?php } else if (is_friend($userid)) {?>
	<span style='font-size:16px'><a onclick='_("friend-info").style.display="inline"'><?=$babel->say('p_youarefriends')?></a></span>
	<?php } else { ?>
	<button onclick='_("friend-request").style.display="inline"' class='rainbow-button rainbow-button-submit' style='font-size:15px'>+ <?=$babel->say('p_add_friend')?></button>
	<?php } ?>
	<?php } ?>
	</span>
	<div id="coverheader" style='width:100%;height:300px' >
	 	<?php if (isUserImage($username,"cover.jpg")) {?>
		<div style='height:300px;width:849px;background-image: url(<?=$CONFIG->siteroot?>myimages/<?=$username?>/cover.jpg)'> 
		</div>
		<? } else { ?>
		<div style='height:300px;width:849px;background-image: url(<?=$CONFIG->siteroot?>template/default/_graphics/rainbow.png)'> 
		</div>
		
		<?php }  ?>

	</div>
	<div id="coverheader" style='-webkit-border-bottom-right-radius: 2px;-webkit-border-bottom-left-radius: 2px;-moz-border-radius-bottomright: 2px;-moz-border-radius-bottomleft: 2px;border-bottom-right-radius: 2px;border-bottom-left-radius: 2px;width:850px;height:75px;background-color:white' >
	<div style='margin-left:200px;padding-top:10px'><h1 style='margin-bottom:4px'><?=$fullname?></h1> 
	
	<span style='line-height:21px;font-size:15px'><?=$intro?></span>
	<?php if (is_root()) { ?>
	<div><i><?=$browser?> <?=$version?> <?=$platform?></i> ** <b>Logins:</b> <?=$logins?> <b>Posts:</b> <?=$posts?> <b>Trust:</b> <?=$trust?> <b>Last:</b> <?=$lastlogin?> ** Lang: <?=$language?>
	<a href="<?=$CONFIG->siteroot?>action/user/proxyuser.php?userid=<?=$userid?>&username=<?=$username?>"><?=$babel->say('p_proxyuser')?></a></div>
	<?php } ?>
	</div>
	</div>
	
	<div style='-webkit-border-radius: 2px;position:absolute;left:20px;top:200px;border-style:solid;border-color:black;border-width:1px'>
	<?php if (get_logged_in_user_name() == $username){?>
	<a href='/avatar'>
	<?php } else {?>
	<a >
	
	<?php } ?>
	
	<div style='-webkit-border-radius: 2px;border: 4px solid #fff;height:150px;width:150px;background-image:url(<?=$img_src?>)'>
	</div>
	</a>
	</div>
	
	</div>

	<div id="mainbody" style='width:900px'>

		<span style='display:inline-block;width:240px;margin-right:5px;margin-top:10px'>
			<div class='profile_box'>
			<img style='float:left;margin-right:5px' src="/template/default/_graphics/om.jpg">
			
			<h2><?=$babel->say('p_personalinfo')?></h2><hr>
			<h4><?=$babel->say('p_life')?></h4><?=$bio?>
			<br><br>
			<h4><?=$babel->say('p_skillsshare')?></h4>
			<?=$skills?>
			<br><br>
			<h4><?=$babel->say('p_likestodo')?></h4>
			<?=$likes?>
			</div>

			<div  class='profile_box'>
			<img style='float:left;margin-right:5px' src="/template/default/_graphics/karma.png">
			
			<h2><?=$babel->say('p_goodkarma')?>
			</h2>

			<hr><br>
			
			
			<?php
				$q = "select good_karma from karma where user_id = $userid";
				$r = get_query($q);
				if ($o = $r->fetch_object()) {
					$good_karma = $o->good_karma;
				}
				else {
					$good_karma = 0;
				}
			?>
			<center>
				<span style='color:gray;font-size:54px'><?=$good_karma?></span>
				</center>
			<?php if ($userid == get_logged_in_user_id()) {?>
				<br><?=$babel->say('p_howtogetmoregoodkarma')?>
			<?php } ?>
			
			</div>
		</span>


		
		
	
		<span id="middle" style="border-style:solid;display:inline-block;vertical-align:top;margin-left:0px;margin-top:10px;width:600px">
			
			
			<div id="feedcontainer_continent" style='width:600px;'>
			
			<?php if (is_friend($userid)) {?>
			<div class='profile_box'>
			<h2><?=$babel->say('p_postmessage')?></h2><hr>
			<form id='wallform' action='/action/profile/postwall.php'>
			<input type="hidden" name="userprofile" value="<?=$username?>">
			<input type="hidden" name="userprofileid" value="<?=$userid?>">
			
			<textarea name='message' style='height:50px'></textarea>
			<button onclick='_("wallform").submit()'><?=$babel->say('p_posttowall')?></button>
			</form>
			</div>	
			<? } ?>
		      <?php if (!displayUserFeed(0,15,$userid)){
		         include('no_items.php');
		      }?>
		   
			</div>
		</span><br>

		
	</div>
	
</div>
<div id='feed_throttle' style='display:none'></div>
<div id="ajax_loader"></div>
<br><br><br><br><br><br><br><br>