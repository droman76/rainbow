<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$babel = new BabelFish('feed');
$post_id = $_REQUEST['postid'];
$user_id = get_logged_in_user_id();

// delete the following
$qr = "delete from following where source_id = $post_id and user_id_following = $user_id and source_obj = 'post'";
get_query($qr);


$q = "select username,name,users.id as user_id from following,users where user_id_following = users.id and source_id = $post_id and source_obj = 'post'";
$data = postaction($q,$user_id,'follow',"followingPopup($post_id)");
echo $data->text;

?>