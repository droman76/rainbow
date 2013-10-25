<h1>Migration 2 in progress...</h1>
<br>
<?php


session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/image_resize.php');
include($_SESSION['home'].'lib/cronos.php');

$CONFIG = $_SESSION['CONFIG'];


$q = "select * from migration where email not in (select email from users)";
$r = get_query($q);

while ($o = $r->fetch_object()) {
	$n = $o->name;
	$u = $o->username;
	$e = $o->email;
	$g = 'x';
	$p = 'love';

	$url = "http://www.stopforumspam.com/api?email=$email&ip=$ip&f=json";
	$guardian = json_decode(file_get_contents($url));

	if ($guardian->email->appears > 0) {
		//echo "SPAMMER DETECTED! email $email of user $username ($name) appears ".$guardian->email->frequency. " times.<br>";		
		ilog("SPAMMER DETECTED! email $email of user $username ($name) appears ".$guardian->email->frequency. " times.<br>");		
		$spam = true;
		$s++;
		continue;
	}


	$p_hash = md5($p);
	$lang = 'en';
	// Check to see that the user does not exist already on the database
	$q = "select * from users where username = '$u'";
	$rx = get_query($q);
	$c = get_rows($rx);
	if ($c > 0 ) {
		//echo "User $n ($u) already notified.<br>";
		
	}
	else {

	// Add user info into the database table for the main site table
	$sql = "INSERT INTO users (username, name, gender, email, password, logincount,postcount,trustlevel,continent_code,country, country_code,region,city,latitude,longitude,ip, signup, lastlogin, notescheck,language)       
		        VALUES('$u','".db_escape($n)."','$g','$e','$p_hash',0,0,0,'','','','','','','','',now(),now(),now(),'$lang')";
	$query = get_query($sql); 
	if (!get_errors()) {
		$uid = get_insert_id();
		
		$to = "$e";							 
		$from = "focalizer@worldrainbowfamily.org";
		$subject = 'Oops, your account was accidentally deleted...';
		$message = '<body style="margin:5px; font-family:Tahoma, Geneva, sans-serif;">
		<h3 style="color:darkblue">Your account was deleted, but no problem!</h3>
		<div font-size:14px;">Hi '.$n.',<br /><br />
		Your account was deleted, it is late and i was tired coding and pressed the wrong button, but just click again on the link and everything will be restablished...:<br /><br />
		<a href="'. $CONFIG->site .'/activation?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">
		Click here to activate your account now and automatically login</a><br /><br />
		Once activated, to log in later please use the following information:<br />
		* E-mail Address: <b>'.$e.'</b><br>Your password has been changed to: <b>love</b><br><br>Please change your password as soon as posible by editing your profile!</div></body></html>';
		$headers = "From: Rainbow Family Network <$from>\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		//mail($to, $subject, $message, $headers);
        $mailed = mail($to, $subject, $message, $headers);
		if ($mailed) {
			echo "<span style='color:green'>Notified user $n ($u) -> $e</span><br>";	
		}
		else {
		echo "<br><span style='color:red'>$n ($u) send email to $e failed!</span><br>";

		}
	} else {
		echo "<br><span style='color:darkred'>$n ($u) with email $e db add failed!</span><br>";
	}

	}
	

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