<?php

session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/image_resize.php');
include($_SESSION['home'].'lib/cronos.php');

$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('group');


$groupname = db_escape(strip_tags($_REQUEST['groupname']));

$description = db_escape(strip_tags($_REQUEST['description']));
$visibility = $_REQUEST['visibility'];
$access = $_REQUEST['access'];
$postaccess = $_REQUEST['postaccess'];
$moderation = $_REQUEST['moderation'];
$creator = get_logged_in_user_id();
$imagefile = $_REQUEST['image'];
$image = '';
$valid = false;
$action = $_REQUEST['action'];

// validation...
if ($groupname == '') {
	system_msg($babel->say('group_cannot_be_null'));
}
else if ($description == '') {
	system_msg($babel->say('description_cannot_be_null'));
}
else {
	$valid = true;
}



if (!$valid) {
	header("Location: ".$CONFIG->site."/group?action=$action&groupname=$groupname&description=$description&visibility=$visibility&approval=$approval&moderation=$moderation&image=$imagefile");
	exit(1);
}

if ($action == 'new') {
	$groupid = strtolower($groupname);
	$groupid = str_replace(' ', '', $groupid);
}
else {
	$groupid = $_REQUEST['groupid'];
}


$errors = false;

// CHECK THAT group name is valid for new action
if ($action == 'new') {
	$q = "select * from groups where id = '$groupid'";
	$r = get_query($q);
	$c = get_rows($r);
	if ($c > 0) {
		system_msg($babel->say('p_group_name_already_exists'));
		header("Location: ".$CONFIG->site."/group?action=new&groupname=$groupname");
		exit(1);
	}

}

// **************** IMAGE HANDLING START ********************************
$upload_path = $_SESSION['data'].'groups/'.$groupid . '/'; 	
$userfile_name = $_FILES['image']['name'];
$userfile_tmp = $_FILES['image']['tmp_name'];
$userfile_size = $_FILES['image']['size'];
$userfile_type = $_FILES['image']['type'];
$filename = basename($_FILES['image']['name']);
$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));

if (!file_exists($upload_path)) {
	ilog("$upload_path does not exist.. creating it!");
	mkdir($upload_path,0755,true);
}

$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
$allowed_image_ext = array_unique($allowed_image_types); // do not change this
$error = '';	
ilog("Filename is: $filename");
if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
		
	foreach ($allowed_image_types as $mime_type => $ext) {
		//loop through the specified image types and if they match the extension then break out
		//everything is ok so go and check file size
		if($file_ext==$ext){
			$errors = false;
			break;

		}else{
			$errors = true;
		}
	}
	if ($errors) {
		$error = $babel->say('p_image_extension_not_valid');
		system_msg($error);
	}		
	//check if the file size is above the allowed limit
	
	
}else{
	$error= $babel->say('p_no_imagetoupload');
	//system_msg($error);
	//$errors = true;
	$file_ext = '';
	
}

if (!$errors){
		
		if (isset($_FILES['image']['name'])){
			//this file could now has an unknown file extension (we hope it's one of the ones set above!)
			$image_location = $upload_path.'master'.".".$file_ext;
			$cover_image_location = $upload_path.'medium'.".".$file_ext;
			
			
			ilog("Moving uploaded file to $image_location");
			move_uploaded_file($userfile_tmp, $image_location);


			chmod($image_location, 0775);
			
			// now resize to proper widths
			$image = new resize($image_location);
	    	$image->resizeImage(200, 200, 'crop');  
	    	$image->saveImage($cover_image_location, 50);  

			$result="success";
		}
		
	}




// **************** IMAGE HANDLING END **********************************

if ($action == 'new') {
	$sql = "insert into groups (id,name,description,visibility,postaccess,access,moderation,creator,image)";
	$sql .= " VALUES ('$groupid','$groupname','$description','$visibility','$postaccess','$access','$moderation',$creator,'$file_ext')";


	get_query($sql);
	if (get_errors())$errors = true;

	$sql = "insert into group_members (groupid,userid,role) values ('$groupid',$creator,'admin')";
	get_query($sql);
	if (get_errors())$errors = true;
	if (!$errors) {
		// insert post action here for the group
		post_action('action_groupcreate','','city',$groupid);
	}

}

if ($action == 'edit') {
	if (isset($file_ext) && $file_ext != '')
		$imagex = "image= '$file_ext',";
	else $imagex = '';

	$sql = "update groups set name = '$groupname', description = '$description', visibility = '$visibility',";
	$sql .= " postaccess = '$postaccess',access = '$access', $imagex moderation = '$moderation' where id = '$groupid'";
	// if editing lets put a flag for the cache to refresh the group image
	$_SESSION['groupavatarchanged'] = 'true';
	get_query($sql);

}


if (get_errors() || $errors){
	system_msg("There was an error. Contact web focalizer.");
	header("Location: ".$CONFIG->site."/group?action=new&groupname=$name");
}
else {
	if ($action == 'new') {
		system_msg($babel->say('p_group_create_success'));
		cronos_update($groupid);
	}
	else 
		system_msg($babel->say('p_group_update_success'));
		
	header("Location: ".$CONFIG->site."/group/$groupid");
}






?>