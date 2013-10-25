<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'page/feed/feediterator.php');
include($_SESSION['home'].'lib/embedder.php'); 


$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('feed');

$postid = $_REQUEST['postid'];
$allow = '<table><thead><th><tr><td><tbody><tfoot><h4><b><i><u>';

$message = strip_tags($_REQUEST['content'],$allow);
$video = getVideo($message,'460','365');

if (isset($video) && isset($video->frame) ) {
	$message = preg_replace('@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@', "<a href=\"http$2://$4\">$video->title</a>", $message);	
	$link_video = $video->frame;
	$link_desc = $video->description;
	$link_title = $video->title;
	$link_src = $video->url;
}
else {
	$message = preg_replace('@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@', '<a href="http$2://$4">$1$2$3$4</a>', $message);
	$link_src='';$link_title='';$link_desc='';$link_video='';
}


// TODO: create an entry on the deleted table for tracking and restoring
$q = "update feed set message='".db_escape($message)."' where post_id = $postid";
if (get_query($q)){
	$message = str_replace("\n", "<br>", $message);
	echo $message;
}
else {
	echo "error";
}



?>