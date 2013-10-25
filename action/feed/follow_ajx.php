<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$babel = new BabelFish('feed');
$user_id = get_logged_in_user_id();
$post_id = $_REQUEST['postid'];
$source_obj = 'post';


// check that user is not already following
$sql = "insert into following (source_id,source_obj,user_id_following) values ($post_id,'$source_obj',$user_id)";
get_query($sql);

if (get_errors()){
	echo "error";
}
else {
	$q = "select username,name,users.id as user_id from following,users where user_id_following = users.id and source_id = $post_id and source_obj = 'post'";

	$data = postaction($q,$user_id,'follow',"followingPopup($post_id)");
	echo $data->text;

}


?>