<?php

  $id = $user->id;
  $link = $user->link;
  $username = $user->username;
  $email = $user->email;
  $name = $user->name;
  $gender = $user->gender;
  
  $bio = $user->bio;
  
?>
<div class="modal-bg">

<div id='facebook_confirm' class="modal" style='z-index:500000'>

<div style='margin:40px'>
	<img style='float:left;margin-right:10px;margin-bottom:40px' src='https://graph.facebook.com/<?=$id?>/picture?width=150&height=150' border='1'>  
<h3>Welcome <?=$name?>!</h3><br>
<?=$babel->say('p_facebookwelcomemsg')?>
<br><br>

<b>Username:<b> <?=$username?><br>
<b>Email: </b><?=$email?><br><br>
<form id='fb_login' method='POST' action='<?=$CONFIG->siteroot?>action/user/facebook_login.php'>
<button id="signupbtn" onclick='_("fb_login").submit()'>
<?=$babel->say('p_facebookloginbutton')?> </button>

<form>
</div>
</div>

</div>