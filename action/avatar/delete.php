<?php
session_start();
include($_SESSION['home'].'config.php');
include ($CONFIG->home . 'page/avatar/config.php');



if ($_GET['a']=="delete" && strlen($_GET['t'])>0){
//get the file locations 
	$large_image_location = $upload_path.$large_image_prefix.$_GET['t'].$image_extension;
	$thumb_image_location = $upload_path.$thumb_image_prefix.$_GET['t'].$image_extension;
	if (file_exists($large_image_location)) {
		unlink($large_image_location);
	}
	if (file_exists($thumb_image_location)) {
		unlink($thumb_image_location);
	}
	header("location: /avatar");
	exit(); 
}
?>