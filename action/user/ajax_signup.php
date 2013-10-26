<?php 
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$rpage = page_controller();
$babel = new BabelFish($rpage);


// Ajax calls this NAME CHECK code to execute
if(isset($_POST["usernamecheck"])){
	$username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
	$username = str_replace(' ', '', $username);
	$username = strtolower($username);
	$sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
    $query = get_query($sql); 
    $uname_check = mysqli_num_rows($query);
    if (strlen($username) < 3 || strlen($username) > 16) {
	    echo '<strong style="color:#F00;">'.$babel->say('registration_size_error').'</strong>';
	    exit();
    }
	if (is_numeric($username[0])) {
	    echo '<strong style="color:#F00;">'.$babel->say('registration_username_error').'</strong>';
	    exit();
    }
    if ($uname_check < 1) {
	    echo '';
	    exit();
    } else {
	    echo '<strong style="color:#F00;">' .' '.  $babel->say('user_taken') . '</strong>';
	    exit();
    }
}
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["n"])){
	// CONNECT TO THE DATABASE
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$e = db_escape($_POST['e']);
	$n = db_escape($_POST['n']);
	$u = preg_replace('#[^a-z0-9]#i', '', $n);
	$u = str_replace(' ', '', $u);
	$u = strtolower($u);

	$g = $_REQUEST['g'];

	
	$p = $_POST['p'];
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));

    // check the captcha first
    if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']) {
        echo $captcha_message = $babel->say('p_invalid_captcha');
        exit(1);
    } 


    // check to see that the user is not a spammer!!
    $url = "http://www.stopforumspam.com/api?email=$e&ip=$ip&f=json";
	$guardian = json_decode(file_get_contents($url));

	if ($guardian->email->appears > 0) {
		$msg = "SPAMMER DETECTED! email $email of user $username ($name) appears ".$guardian->email->frequency. " times.<br>";		
		$headers = "From: Rainbow Family Network <$from>\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail('daniel@lacasacolibri.org', 'Spammer tried to access the network!', $msg, $headers);
		echo "Sorry. I can't let you in.";
		exit(1);
	}
	else if ($guardian->ip->appears > 0) {
		$msg = "BAD IP DETECTED! ip $ip of user $username ($name) with email $email appears ".$guardian->ip->frequency. " times.<br>";		
		$headers = "From: Rainbow Family Network <$from>\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail('daniel@lacasacolibri.org', 'BAD IP detected on the network!', $msg, $headers);
		
		
	}

    //get the user language:


    if (isset($_SESSION['user_language'])){
    	$lang = $_SESSION['user_language'];
    }
    else {
 	    $lang = 'en';
	}
	// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
	$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
    $query = get_query($sql); 
	$u_check = mysqli_num_rows($query);
	// -------------------------------------------
	$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
    $query = get_query($sql); 
	$e_check = mysqli_num_rows($query);
	// FORM DATA ERROR HANDLING
	if($u == "" || $e == "" || $p == "" ){
		echo $babel->say('error_fill_all');
        exit();
	} else if ($u_check > 0){ 
        echo $babel->say('error_user_taken');
        exit();
	} else if ($e_check > 0){ 
        echo $babel->say('error_email_taken');
        exit();
	} else if (strlen($u) < 3 || strlen($u) > 16) {
        echo $babel->say('registration_size_error');
        exit(); 
    } else if (is_numeric($u[0])) {
        echo $babel->say('registration_username_error');
        exit();
    } else {

    	$userdir = $CONFIG->userdata."$u";
    	if (!file_exists($userdir)) {
			ilog('creating user directory at '.$userdir);
			if (!mkdir($userdir, 0775,true)){
				elog("Could not create user directory for user $u. Ending program!");
				echo("There was an error creating user data directory. Contact focalizer");	
				exit(1);	
			}
		}
	// END FORM DATA ERROR HANDLING
	    // Begin Insertion of data into the database
		// Hash the password and apply your own mysterious unique salt
		$p_hash = md5($p);

		// get browser info
		$ua=getBrowser();
		$browser = $ua['name'];
		$browserversion = $ua['version'];
		$platform = $ua['platform'];
		$now = time();

		// Add user info into the database table for the main site table
		$sql = "INSERT INTO users (username, name, gender, email, password, logincount,postcount,trustlevel,continent_code,country, country_code,region,city,latitude,longitude,ip, signup, lastlogin, notescheck,language,browser,browser_version,platform)       
		        VALUES('$u','$n','$g','$e','$p_hash',0,0,0,'".$_SESSION['continent_code']."','".$_SESSION['country']."','".$_SESSION['country_code']."','".$_SESSION['region']."','".$_SESSION['city']."','".$_SESSION['latitude']."','".$_SESSION['longitude']."','$ip',$now,$now,$now,'$lang','$browser','$browser_version','$platform')";
		ilog("Registering new user $n ($u) @ $e from ".$_SESSION['city'].', '.$_SESSION['country']);
		
		$query = get_query($sql); 
		$uid = get_insert_id();
		/*
		// Establish their row in the useroptions table
		$sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$u','original')";
		
		$query = get_query($sql);
		// Create directory(folder) to hold each user's files(pics, MP3s, etc.)
		*/

	
		
		// Email the user their activation link
		$to = "$e";							 
		$from = "focalizer@worldrainbowfamily.org";
		$subject = 'World Rainbow Family Account Activation';
		$message = '<body style="margin:5px; font-family:Tahoma, Geneva, sans-serif;">
		<h3 style="color:darkblue">Welcome to the new Rainbow Family Network!</h3>
		<div font-size:14px;">Aloha '.$n.',<br /><br />
		Please click the link below to activate your account when ready:<br /><br />
		<a href="'. $CONFIG->site .'/activation?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">
		Click here to activate your account now</a><br /><br />
		Login after successful activation using your:<br />
		* E-mail Address: <b>'.$e.'</b></div></body></html>';
		$headers = "From: Rainbow Family Network <$from>\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail($to, $subject, $message, $headers);
		echo "signup_success";
		exit();
	}
	exit();
}


function randStrGen($len){
	$result = "";
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789$$$$$$$1111111";
    $charArray = str_explode($chars);
    for($i = 0; $i < $len; $i++){
	    $randItem = array_rand($charArray);
	    $result .= "".$charArray[$randItem];
    }
    return $result;
}
?>