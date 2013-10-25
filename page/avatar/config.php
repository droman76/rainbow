<?php
$upload_dir = $_SESSION['userdata'].$_SESSION['username']; 				// The directory for the images to be saved in
$upload_path = $upload_dir."/";				// The path to where the image will be saved
$large_image_prefix = "full_"; 			// The prefix name to large image
$big_image_prefix = "big_"; 			// The prefix name to large image
$avatar_image_prefix = "medium_";			// The prefix name to the thumb image
$thumb_image_prefix = "small_";			// The prefix name to the thumb image
$large_image_name = $large_image_prefix.$_SESSION['username'];     // New name of the large image (append the timestamp to the filename)
$big_image_name = $big_image_prefix.$_SESSION['username'];     // New name of the large image (append the timestamp to the filename)
$avatar_image_name = $avatar_image_prefix.$_SESSION['username'];     // New name of the large image (append the timestamp to the filename)
$thumb_image_name = $thumb_image_prefix.$_SESSION['username'];     // New name of the thumbnail image (append the timestamp to the filename)

$max_file = "5"; 							// Maximum file size in MB
$max_width = "700";							// Max width allowed for the large image
$thumb_width = "45";						// Width of thumbnail image
$thumb_height = "45";						// Height of thumbnail image
$avatar_width = "150";						// Width of thumbnail image
$avatar_height = "150";						// Width of thumbnail image
$big_width = "350";						// Width of thumbnail image
$big_height = "350";						// Width of thumbnail image

if (file_exists($upload_path . 'extinfo')){
	$hr = fopen($upload_path . 'extinfo','r');
	$image_extension = fread($hr, filesize($upload_path . 'extinfo'));
	fclose($hr);
}
else $image_extension = '';

//Image Locations
$large_image_location = $upload_path.$large_image_name.$image_extension;
$thumb_image_location = $upload_path.$thumb_image_name.$image_extension;
$avatar_image_location = $upload_path.$avatar_image_name.$image_extension;
$big_image_location = $upload_path.$big_image_name.$image_extension;

ilog("HERE NOW 3*****************!!!");


?>