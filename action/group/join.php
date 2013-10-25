<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/image_resize.php');
include($_SESSION['home'].'lib/cronos.php');

$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('group');


$groupid = $_REQUEST['groupid'];
$me = get_logged_in_user_id();

$q = "insert into group_members (groupid,userid,role) VALUES ('$groupid',$me,'member')";
get_query($q);

if (!get_errors()) {
	system_msg($babel->say('p_joined_group'));
}
else {
	system_msg($babel->say('p_failed_tojoin_group'));
}

header("Location: ".$CONFIG->site."/group/$groupid");

?>