<?php
session_start();
include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/simpleimage.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/image_resize.php'); 

$babel = new BabelFish('userlogin');

      		 	
$access_token = $_SESSION['token'];


ilog("Saving FACEBOOK profile image");

$graph_url = "https://graph.facebook.com/me?access_token=" 
   . $access_token;
  
$user = json_decode(file_get_contents($graph_url));

$graph_url = "https://graph.facebook.com/me?access_token=" 
   . $access_token. "&fields=cover,picture";
$picdata = json_decode(file_get_contents($graph_url));
 
$id = $user->id;
$link = $user->link;
$username = $user->username;
$email = $user->email;
$name = $user->name;
$gender = $user->gender;

$bio = $user->bio;
$cover_pic = $picdata->cover->source;
$user_pic = $picdata->picture->data->url;
  
$userdir = $CONFIG->userdata.$username;
if (!file_exists($userdir)) {
	ilog('creating user directory at '.$userdir);
	if (!mkdir($userdir, 0775,true)){
		elog("Could not create user directory for user $u. Ending program!");
		echo("There was an error creating user data directory. Contact focalizer");	
		exit(1);	
	}
}
	
$p = randStrGen(8);
$p_hash = md5($p);
$ua=getBrowser();
$browser = $ua['name'];
$browserversion = $ua['version'];
$platform = $ua['platform'];
$now = time();

// Check that the user does not already exist with that username
$q = "select * from users where username ='$username'";
$x = get_query($q);
if ($u = $x->fetch_object()) {
	// ok found a username with that name already

	//check that the email matches 
	if ($u->email == $email) {
		// ok it is the same user returning from migration activate and continue
		$sql = "update users set activated = 1, gender = '$gender', extid = '$id' where username = '$username'";

		get_query($sql);
	}
	else {
		system_msg("There is a user with the same username $username but different email already in the system. Registration failed!");
	}


}
else {


// Add user info into the database table for the main site table
$sql = "INSERT INTO users (username, name, email, password, gender,extid, continent_code,country, country_code,region,city,latitude,longitude,ip, signup, lastlogin, notescheck,language,logincount,postcount,trustlevel,browser,browser_version,platform)       
        VALUES('$username','$name','$email','$p_hash','$gender','$id','".$_SESSION['continent_code']."','".$_SESSION['country']."','".$_SESSION['country_code']."','".$_SESSION['region']."','".$_SESSION['city']."','".$_SESSION['latitude']."','".$_SESSION['longitude']."','".$_SESSION['ip']."',$now,$now,$now,'".$_SESSION['user_language']."',0,0,1,'$browser','$browserversion','$platform')";
ilog($sql);
$query = get_query($sql); 

}

if (get_errors()){
	system_msg("Registration Failed. Contact focalizer!");
	header("Location: ".$CONFIG->site.'/');
	exit(1);
}

$uid = get_insert_id();
// Establish their row in the userprofiletable
$sql = "INSERT INTO `userprofile` (`userid`, `bio`) VALUES ($uid, \"$bio\")";
$query = get_query($sql);		
ilog($sql);
		// Email the user their activation link
$to = "$email";							 
$from = "focalizer@worldrainbowfamily.org";
$subject = 'Welcome to the Rainbow Family Network!';
$message = "$name, welcome to the Rainbow Family Network, you have logged in using your facebook credentials. You can continue doing so or you can log in using your email and this password: <b> $p</b><br><br>Thank you for supporting this new alternative!!";
$headers = "From: $from\n";
$headers .= "MIME-Version: 1.0\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\n";
mail($to, $subject, $message, $headers);

// create the images if they do not exist
$image = new SimpleImage();
$image->load("https://graph.facebook.com/$id/picture?width=600");

$image->save($userdir."/full_".$username.'.jpg');

$resizer = new resize($userdir."/full_".$username.'.jpg');
$resizer->resizeImage(150, 150, 'crop');  
$resizer->saveImage($userdir."/medium_".$username.'.jpg', 70);  
$resizer->resizeImage(50, 50, 'crop');  
$resizer->saveImage($userdir."/small_".$username.'.jpg', 70);  
	    	
$image->load($cover_pic);
$image->save($userdir."/cover-master".'.jpg');
$resizer = new resize($userdir."/cover-master.jpg");
$resizer->resizeImage(345, 100, 'crop');  
$resizer->saveImage($userdir."/cover_small.jpg", 70);  
$resizer->resizeImage(900, 300, 'crop');  
$resizer->saveImage($userdir."/cover.jpg", 70);  

//$image->save($userdir."/cover_small_".$username.'.jpg');


$image_extension=".".$file_ext;
$extfile = $userdir.'/extinfo';
$h = fopen($extfile, 'w');
fwrite($h, ".jpg");
fclose($h);


force_login($uid,$username,$p_hash,$name,'');

header("Location: ".$CONFIG->site.'/');



function randStrGen($len){
	$result = "";
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789$$$$$$$1111111";
    $charArray = explode($chars);
    for($i = 0; $i < $len; $i++){
	    $randItem = array_rand($charArray);
	    $result .= "".$charArray[$randItem];
    }
    return $result;
}





?>