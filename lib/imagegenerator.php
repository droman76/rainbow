<?php
	session_start();

	function endsWith($haystack,$needle)
	{
  		$expectedPosition = strlen($haystack) - strlen($needle);  	
  		return strripos($haystack, $needle, 0) === $expectedPosition;
	}
	$isgroup = false;

	$image = $_REQUEST['image'];
	$user = $_SESSION['username'];
	if (isset($_REQUEST['nocache'])) {
		$nocache = true;
	}
	else $nocache = true;

	if (isset($_REQUEST['group'])){
		$isgroup = true;
	}
	
	$i = stripos($image, '/');
	
	if ($i > 0) {
		$user = substr($image, 0,$i);
		$image = substr($image,$i+1);

	}
	//error_log("User is: $user and Image is: '$image' Groups; $isgroup");
	

	if ($isgroup) 
		$path_to_file = $_SESSION['data'] . 'groups/'.$user . '/' . $image;
	else 	
		$path_to_file = $_SESSION['userdata'] . $user . '/' . $image;
	

	if (!file_exists($path_to_file)){
		$path_to_file = $_SESSION['data'] . 'proxies/'. $user . '/' . $image;

	}
	// TODO: handle user and permission check and redirect to appropriate page when not allowed.
	//if ($image == '' || $user == '') 

	if (endsWith($image,'jpg')) $type = 'image/jpeg';
	else if (endsWith($image,'jpeg')) $type = 'image/jpeg';
	else if (endsWith($image,'gif')) $type = 'image/gif';
	else if (endsWith($image,'png')) $type = 'image/png';
	else $type = 'unknown';

	if ($nocache) {
		header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() ));
		header("Content-type: $type");
		readfile($path_to_file);
	}
	else {
		header('Pragma: public'); 
		header('Cache-Control: max-age=86400');
		header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
		header("Content-type: $type");
		readfile($path_to_file);
	}	


  	


?>