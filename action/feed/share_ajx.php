<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/notifications.php');

$babel = new BabelFish('feed');
$user_id = get_logged_in_user_id();
$post_id = $_REQUEST['postid'];
// Todo: set the scope in the front end by querying user about the level of sharing
$scope = 2;

//TODO: add notification for all friends when scope is 2



$sql = "insert into wall (post_id,user_id,scope) values ($post_id,$user_id,$scope) on duplicate key update wall.scope = $scope";
get_query($sql);

if (get_errors()){
	echo "error";
}
else {
	$q = "select username,name,user_id from wall,users where wall.scope = 2 and wall.user_id = users.id and post_id = $post_id";

	
	$data = postaction($q,$user_id,'share',"sharePopup($post_id)");
	
	// add notification
	notify($user_id,'post',$post_id,'share');


	echo $data->text;

}


?>