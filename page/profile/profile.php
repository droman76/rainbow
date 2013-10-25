<?php
if (isset($_REQUEST['path'])) $path = $_REQUEST['path'];
if (isset($_REQUEST['action'])) $action = $_REQUEST['action'];

ilog("User Path: ".$path);
$name = $_SESSION['name'];

$location = getLocationString();

if (isset($_REQUEST['cover'])) {
	include('cover.php');
	exit(1);
}

$view = false;
$userid = 0;

if (strlen($path) > 4 && isUser($path)){
	$username = $path;
	$q ="select * from users where username = '".$username."'";
	ilog($q);
	$r = get_query($q);
	$u = $r->fetch_object();
	$fullname = $u->name;
	$userid = $u->id;
	$view = true;
	$browser = $u->browser;
	$version = $u->browser_version;
	$platform = $u->platform;
	$posts = $u->postcount;
	$trust = $u->trustlevel;
	$logins = $u->logincount;
	$lastlogin = '';
	$language = $u->language;

	if ($u->lastlogin > 0 )
		$lastlogin = ago($u->lastlogin). ' ago';


	
	
}
else {
	$username = $_SESSION['username'];
	$userid = $_SESSION['userid'];

}

$q = "select * from userprofile where userid = ".$userid;
$r = get_query($q);
if ($profile = $r->fetch_object()) {
	$bio = $profile->bio;
	
	$intro = $profile->intro;
	$birthday = $profile->birthdate;
	$skills = $profile->skills;
	$likes = $profile->interests;
	$email = $profile->contact;
	$website = $profile->website;
	

}


if ($view) {
	$bio = str_replace("\n", "<br>", $bio);

	include('profileview.php');
	
}
else {


?>

<div id='set-location' class='modal-bg' style='display:none'>

	<div style='left:25%;top:100px;width:500px;height:310px' class="modal">
		<div style='margin:20px'>
		<span style='float:right' ><a onclick='_("set-location").style.display="none"'><img  src='/template/default/_graphics/close.png'></a></span>
			
			<h2><?=$babel->say('p_changelocation')?></h2><hr>
			<p><?=$babel->say('p_changelocation_info')?>
			<br>
			<p><?=$babel->say('p_changelocation_info2')?>
			
			<input type="hidden" id='user-continent' value="<?=$_SESSION['continent_code']?>">
			<input type="hidden" id='user-country' value="<?=$_SESSION['country']?>">
			<input type="hidden" id="user-countrycode" value="<?=$_SESSION['country_code']?>">
			<input type="hidden" id="user-region" value="<?=$_SESSION['region']?>">
			<br><br>	
				
			<?=$babel->say('p_country')?>: <?=$babel->say($_SESSION['country'])?><br>	
			<?=$babel->say('p_city')?>: 
			<input type="text" id="user-city" value="<?=$_SESSION['city']?>"><br><br>
			<button onclick='updateLocation()' class='rainbow-button rainbow-button-submit'><?=$babel->say('p_updatelocation')?>			
			
		</div>
	</div>

</div>

<div style='margin:20px;width:700px'>

<h1><?=$babel->say('p_editprofile')?> </h1>
<br>
<div class="elgg-main elgg-body">
	<form method="post" action="<?=$CONFIG->siteroot?>action/profile/save.php"> 
		
			
	<div id='0' class=''>
		<fieldset>	
			<div>
				<label><?=$babel->say('p_displayname')?></label><br>
				<input type="text" style='width:90%' value="<?=$name?>" name="name" />	
			</div>
			<div><label><?=$babel->say('p_onesentenceabout')?>:</label><br>
				<input type="text" value="<?=$intro?>" name="intro" options="" style='width:90%'/>
			</div>
			<div><label><?=$babel->say('p_about')?>:</label><br>
				
				<textarea rows="10" cols="30" id="profile_description" name="bio" options="" style='width:600px' ><?=$bio?></textarea>
			
			</div>

			<div><label><?=$babel->say('p_birthday')?></label><br>
				<input type="text"  name="birthday" value="<?=$birthday?>" style="width:200px">
				</span>
			</div>
			
			<div><label><?=$babel->say('p_location')?></label><br>
				<span id='userlocation'><?=$location?></span> <a onclick='_("set-location").style.display="inline"'> - <?=$babel->say('p_changelocation')?> </a>
			</div>

			<div><label><?=$babel->say('p_likes')?></label><br>
				<input type="text" value="<?=$likes?>" name="likes" options="" style='width:90%'/>
			</div>

			<div><label><?=$babel->say('p_skills')?></label><br>
				<input type="text" style='width:90%' value="<?=$skills?>" name="skills" options="" class="elgg-input-tags" />
			</div>
			

			<div><label><?=$babel->say('p_website')?></label><br>
			<input style='width:40%' type="text" value="<?=$website?>" name="website" options="" class="elgg-input-url" />
			</div>
			<div><input type='submit' class='rainbow-button rainbow-button-submit' value='Save'>

		</fieldset>
	</div>
	</div>

	<?php } ?>