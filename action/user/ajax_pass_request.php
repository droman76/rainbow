<?php

session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$babel = new BabelFish('user');
$email = $_REQUEST['e'];
$sql = "select id from users where email='$email'";
ilog("Sending new password request to email $email");

$result = get_query($sql);
$row = $result->fetch_row();

if (isset($row[0])){
	// Update the user record with the new password
	$id = $row[0];
	$newpass = "love".randStrGen(4);
	$hash = md5($newpass);
	$q = "update users set password = '$hash' where id=$id";
	get_query($q);
	if (get_errors()){
		echo $babel->say('p_failed_update_password');
		exit(1);
	}
	// Email the user their activation link
	$to = "$email";							 
	$from = "focalizer@worldrainbowfamily.org";
	$subject = 'Your new account password';
	$message = '<body style="margin:5px; font-family:Tahoma, Geneva, sans-serif;">
	<h3 style="color:darkblue">Here is your account Password:</h3>
	<div font-size:16px;">'.$newpass.'</div><div><br><br>This is a temporary password. Please change it as soon as possible by editing your profile and changing the password there. For now Login using your email and this password combination. ';
	$headers = "From: Rainbow Family Network <$from>\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\n";
	$sent = mail($to, $subject, $message, $headers);
	if ($sent)
		echo $babel->say('p_password_sent');
	else 
		echo $babel->say('p_failedtosend_email');

}
else {
	echo $babel->say("p_no_user_found");

}

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