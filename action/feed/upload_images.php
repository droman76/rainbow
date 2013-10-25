<?php
session_start();

$user_name = $_SESSION['username'];
$CONFIG = $_SESSION['CONFIG'];

// from http://net.tutsplus.com/tutorials/php/image-resizing-made-easy-with-php/
include($CONFIG->home . 'lib/image_resize.php');

$result = 'success';
if (!file_exists($CONFIG->userdata.$user_name."/feed"))
	mkdir($CONFIG->userdata.$user_name."/feed");
if (!file_exists($CONFIG->userdata.$user_name."/thumbnails"))
	mkdir($CONFIG->userdata.$user_name."/thumbnails");
if (!file_exists($CONFIG->userdata.$user_name."/gallery"))
	mkdir($CONFIG->userdata.$user_name."/gallery");
if (!file_exists($CONFIG->userdata.$user_name."/gallery/feed"))
	mkdir($CONFIG->userdata.$user_name."/gallery/feed");


if(!empty($_FILES['file'])){
    foreach  ($_FILES['file']['name'] as $key => $name) {
    	$path = $CONFIG->userdata.$user_name."/feed/$name";
    	$feed_path = $CONFIG->userdata.$user_name."/feed/$name";
    	$thumb_path = $CONFIG->userdata.$user_name."/thumbnails/thumb_$name";
    	$gallery_path = $CONFIG->userdata.$user_name."/gallery/feed/$name";
    	
	    if (!move_uploaded_file($_FILES['file']['tmp_name'][$key],$path)){
	        $result = "error";
	    }
	    else {
	    	// resize images for appropriate outputs
	    	$image = new resize($path);
	    	$image->resizeImage(50, 50, 'crop');  
	    	$image->saveImage($thumb_path, 100);  
	    	
	    	if ($image->width > 400) {
	    		$image->resizeImage(400, 400, 'landscape');  
	    		$image->saveImage($feed_path, 80);  

	    	}
	    	if ($image->width > 800) {
				$image->resizeImage(900, 900, 'landscape');  
	    		$image->saveImage($gallery_path, 80);  	
	    	}
	    	else {
	    		$image->resizeImage($image->width,$image->height,'exact');
	    		$image->saveImage($gallery_path, 80);  	
	    		
	    	}



	    }
  }
}
echo $result;
?>