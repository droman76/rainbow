
<h1>Running spam detection... </h1>
<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/notifications.php'); 




$q = "select * from users";
$r = get_query($q);
$s = 0;
while ($o = $r->fetch_object() ) {
	$spam = false;
	$username = $o->username;
	$userid = $o->id;
	$name = $o->name;
	$email = $o->email;
	$ip = $o->ip;
	
	$url = "http://www.stopforumspam.com/api?email=$email&ip=$ip&f=json";
	$guardian = json_decode(file_get_contents($url));

	if ($guardian->email->appears > 0) {
		echo "SPAMMER DETECTED! email $email of user $username ($name) appears ".$guardian->email->frequency. " times.<br>";		
		ilog("SPAMMER DETECTED! email $email of user $username ($name) appears ".$guardian->email->frequency. " times.<br>");		
		$spam = true;
		$s++;
	}
	if ($guardian->ip->appears > 0) {
		echo "SPAMMER DETECTED! ip $ip of user $username ($name) appears ".$guardian->email->frequency. " times.<br>";		
		ilog("SPAMMER DETECTED! ip $ip of user $username ($name) appears ".$guardian->email->frequency. " times.<br>");		
		
		$s++;
		$spam = $true;
	}
	if ($spam) {
		// delete offending user and its activity
		$q = "delete from feed where user_id = $userid";
		get_query($q);
		$q = "delete from feed_comments where user_id = $userid";
		get_query($q);
		$q = "delete from users where id = $userid";
		get_query($q);
		


	}


}

echo "<br><br><h3>Spam sweep completed. Found $s spammers.</h3>";
