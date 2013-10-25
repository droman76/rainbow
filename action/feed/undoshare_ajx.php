<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$babel = new BabelFish('feed');
$post_id = $_REQUEST['postid'];
$user_id = get_logged_in_user_id();
$scope = 2;

// delete the like/blessing
$qr = "delete from wall where post_id = $post_id and user_id = $user_id and scope = $scope";
get_query($qr);

// TODO: NOTIFICATION DELETE
/*$sql = "delete from notifications where target = $source_id and object = '$source_obj' and action = 'blessing'";
ilog($sql);
$r = get_query($sql);
if (get_errors()){
	echo "error";
}
*/
$q = "select username,name,user_id from wall,users where wall.scope = $scope and wall.user_id = users.id and post_id = $post_id";

	
$data = postaction($q,$user_id,'share',"sharePopup($post_id)");
echo $data->text;

?>