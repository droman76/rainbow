<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/functions.php');
include($_SESSION['home'].'lib/babelfish.php');
include($_SESSION['home'].'page/avatar/config.php');
include ($_SESSION['home'] . 'lib/imagelib.php');
include ($_SESSION['home']. 'page/avatar/config.php');
include($_SESSION['home'].'lib/database.php');


if (file_exists($upload_path . 'extinfo')){
	$hr = fopen($upload_path . 'extinfo','r');
	$image_extension = fread($hr, filesize($upload_path . 'extinfo'));
	fclose($hr);
}
// Only one of these image types should be allowed for upload
$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
$allowed_image_ext = array_unique($allowed_image_types); // do not change this
$image_ext = "";	// initialise variable, do not change this.
foreach ($allowed_image_ext as $mime_type => $ext) {
    $image_ext.= strtoupper($ext)." ";
}

//Image Locations
$large_image_location = $upload_path.$large_image_name.$image_extension;
$thumb_image_location = $upload_path.$thumb_image_name.$image_extension;
$avatar_image_location = $upload_path.$avatar_image_name.$image_extension;
$big_image_location = $upload_path.$big_image_name.$image_extension;


if (isset($_POST["upload_thumbnail"]) ) {
	

	//Get the new coordinates to crop the image.
	$x1 = $_POST["x1"];
	$y1 = $_POST["y1"];
	$x2 = $_POST["x2"];
	$y2 = $_POST["y2"];
	$w = $_POST["w"];
	$h = $_POST["h"];
	//Scale the image to the thumb_width set above
	$scale1 = $thumb_width/$w;
	$scale2 = $avatar_width/$w;
	$scale3 = $big_width/$w;
	resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale1);
	resizeThumbnailImage($avatar_image_location, $large_image_location,$w,$h,$x1,$y1,$scale2);
	resizeThumbnailImage($big_image_location, $large_image_location,$w,$h,$x1,$y1,$scale3);
	

	// delete all previous avatar change actions
	$me = get_logged_in_user_id();
	$q = "select post_id from feed where action='action_avatarchange' and user_id=$me";
	$r = get_query($q);
	if ($o = $r->fetch_object()) {
		$post_id = $o->post_id;
		$q = "delete from feed where post_id = $post_id";
		get_query($q);
		$q = "delete from wall where post_id = $post_id";
		get_query($q);
	}
	// Post message on the feed showing this change
	post_action('action_avatarchange','','city');
	$_SESSION['avatarchanged'] = 'true'; // this is for caching issues for image generator to check and refresh cache

	//Reload the page again to view the thumbnail
	header("location: /avatar");
	exit();
}

else if (isset($_POST["upload"])) { 

	//Create the upload directory with the right permissions if it doesn't exist
	if(!is_dir($upload_dir)){
		mkdir($upload_dir, 0775);
		chmod($upload_dir, 0775);
	}

	//Get the file information
	$userfile_name = $_FILES['image']['name'];
	$userfile_tmp = $_FILES['image']['tmp_name'];
	$userfile_size = $_FILES['image']['size'];
	$userfile_type = $_FILES['image']['type'];
	$filename = basename($_FILES['image']['name']);
	$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
	
	//Only process if the file is a JPG, PNG or GIF and below the allowed limit
	if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
		
		foreach ($allowed_image_types as $mime_type => $ext) {
			//loop through the specified image types and if they match the extension then break out
			//everything is ok so go and check file size
			if($file_ext==$ext && $userfile_type==$mime_type){
				$error = "";
				break;
			}else{
				$error = "Only <strong>".$image_ext."</strong> images accepted for upload<br />";
			}
		}
		//check if the file size is above the allowed limit
		if ($userfile_size > ($max_file*1048576)) {
			$error.= "Images must be under ".$max_file."MB in size";
		}
		
	}else{
		$error= "Select an image for upload";
	}
	//Everything is ok, so we can upload the image.
	if (strlen($error)==0){
		
		if (isset($_FILES['image']['name'])){
			//this file could now has an unknown file extension (we hope it's one of the ones set above!)
			$large_image_location = $upload_path.$large_image_name.".".$file_ext;
			$thumb_image_location = $upload_path.$thumb_image_name.".".$file_ext;
			
			//put the file ext in the session so we know what file to look for once its uploaded
			$image_extension=".".$file_ext;
			$extfile = $upload_path.'extinfo';
			$h = fopen($extfile, 'w');
			fwrite($h, ".".$file_ext);
			fclose($h);

			move_uploaded_file($userfile_tmp, $large_image_location);
			chmod($large_image_location, 0777);
			
			$width = getWidth($large_image_location);
			$height = getHeight($large_image_location);
			//Scale the image if it is greater than the width set above
			if ($width > $max_width){
				$scale = $max_width/$width;
				$uploaded = resizeImage($large_image_location,$width,$height,$scale);
			}else{
				$scale = 1;
				$uploaded = resizeImage($large_image_location,$width,$height,$scale);
			}
			//Delete the thumbnail file so the user can create a new one
			if (file_exists($thumb_image_location)) {
				unlink($thumb_image_location);
			}
		}
		//Refresh the page to show the new uploaded image
		header("location: /avatar");
		exit();
	}
}
else {
	system_msg("Error.. could not process request. Please Contact Focalizer!");
	header("location: /avatar");
	exit();
}

?>