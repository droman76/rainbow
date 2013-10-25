<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'page/feed/feediterator.php'); 
$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('feed');

$postid = $_REQUEST['postid'];
// TODO: create an entry on the deleted table for tracking and restoring
$q = "delete from feed where post_id = $postid";
if (get_query($q)){
	echo $babel->say('p_postdeleted');

}
else {
	echo "error: ".$babel->say('p_postdeletefailed');
}



?>