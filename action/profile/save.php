<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$CONFIG = $_SESSION['CONFIG'];
$babel = new BabelFish('profile');

$userid = $_SESSION['userid'];
$name =  db_escape(strip_tags($_REQUEST['name']));
$bio = db_escape(strip_tags($_REQUEST['bio']));
$likes =  db_escape(strip_tags($_REQUEST['likes']));
$skills =  db_escape(strip_tags($_REQUEST['skills']));
$birthday = strip_tags($_REQUEST['birthday']);
$email =  db_escape(strip_tags($_REQUEST['email']));
$website =  db_escape(strip_tags($_REQUEST['website']));
$intro =  db_escape(strip_tags($_REQUEST['intro']));

if (strlen($name) == '') {
	system_msg($babel->say('p_name_cannotbempty'));
	header("Location: ".$CONFIG->site.'/profile/');
	exit(1);
}

$sc = "SELECT userid from userprofile where userid = $userid";
$r = get_query($sc);
$count = db_get_rowcount($r);

if ($count <= 0) {
$sql = "INSERT INTO `userprofile` (`userid`, `bio`, `profile_pic`, `birthdate`, `skills`, `interests`, `contact`, `website`,intro) VALUES ('$userid', '$bio', '', CURRENT_TIMESTAMP, '$skills', '$likes', '$email', '$website','$intro')";
}
else {
	$sql = "UPDATE  `userprofile` SET  `bio` =  '$bio',`skills` =  '$skills',interests = '$likes',birthdate = '$birthday', `contact` =  '$email',`website` =  '$website', intro = '$intro' WHERE  `userid` = $userid";
}
get_query($sql);

if ($_SESSION['name'] != $name) {
	$q = "UPDATE users set name = '$name' where id = $userid";

	get_query($q);
	// also update all of the feeds
	$q = "UPDATE feed set user_full_name = '$name' where user_id = $userid";
	get_query($q);
	$q = "UPDATE feed_comments set user_full_name = '$name' where user_id = $userid";
	get_query($q);
		

	$_SESSION['name'] = $name;

}

system_msg($babel->say('p_profilesaved'));
header("Location: ".$CONFIG->site.'/profile/'.$_SESSION['username']);

?>