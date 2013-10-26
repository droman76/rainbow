<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'page/feed/feediterator.php'); 

$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('user');

$password = $_REQUEST['password'];

$p_hash = md5($password);

$me = get_logged_in_user_id();

$q= "update users set password = '$p_hash' where id = $me";
get_query($q);

if (get_errors()) {
	echo "Error updating password!";
}
else {
 echo $babel->say('p_password_update_success!');
}
?>