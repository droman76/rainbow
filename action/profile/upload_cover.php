<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/image_resize.php');


$CONFIG = $_SESSION['CONFIG'];
$babel = new BabelFish('profile');
$result='na';

$upload_path = $_SESSION['userdata'].$_SESSION['username'] . '/'; 	
$userfile_name = $_FILES['image']['name'];
$userfile_tmp = $_FILES['image']['tmp_name'];
$userfile_size = $_FILES['image']['size'];
$userfile_type = $_FILES['image']['type'];
$filename = basename($_FILES['image']['name']);
$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));


$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
$allowed_image_ext = array_unique($allowed_image_types); // do not change this
$error = '';	
ilog("Filename is: $filename");
if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
		
	foreach ($allowed_image_types as $mime_type => $ext) {
		//loop through the specified image types and if they match the extension then break out
		//everything is ok so go and check file size
		if($file_ext==$ext){
			$error = "";
			break;
		}else{
			$error = $babel->say('p_image_extension_not_valid');
			system_msg($error);
		}
	}
	//check if the file size is above the allowed limit
	
	
}else{
	$error= $babel->say('p_no_imagetoupload');
	system_msg($error);
	
}
ilog("Error $error");
if (strlen($error)==0){
		
		if (isset($_FILES['image']['name'])){
			//this file could now has an unknown file extension (we hope it's one of the ones set above!)
			$image_location = $upload_path.'cover-master'.".".$file_ext;
			$cover_image_location = $upload_path.'cover'.".".$file_ext;
			$cover_small_image_location = $upload_path.'cover_small'.".".$file_ext;
			
			//put the file ext in the session so we know what file to look for once its uploaded
			/*
			$image_extension=".".$file_ext;
			$extfile = $upload_path.'coverextinfo';
			$h = fopen($extfile, 'w');
			fwrite($h, ".".$file_ext);
			fclose($h);
			*/
			ilog("Moving uploaded file to $image_location");
			move_uploaded_file($userfile_tmp, $image_location);


			chmod($image_location, 0775);
			
			// now resize to proper widths
			$image = new resize($image_location);
	    	$image->resizeImage(900, 300, 'crop');  
	    	$image->saveImage($cover_image_location, 80);  
	    	$image->resizeImage(345, 100, 'crop');  
	    	$image->saveImage($cover_small_image_location, 80);  

			system_msg($babel->say('p_upload_success_cover'));
			$result="success";
		}
		
	}


header('location: '.$CONFIG->site."/profile/".$_SESSION['username']);


?>