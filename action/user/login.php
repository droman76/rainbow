<?php


function login() {
	global $babel;
	$babel = new BabelFish('userlogin');
	ilog("Login in... email: ".$_POST["lemail"]);
	// AJAX CALLS THIS LOGIN CODE TO EXECUTE
	if ( isset( $_POST["lemail"] ) ) {
		// CONNECT TO THE DATABASE
		// GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
		$e = db_escape( $_POST['lemail'] );
		$p = md5( $_POST['lpassword'] );
		// GET USER IP ADDRESS
		$ip = preg_replace( '#[^0-9.]#', '', getenv( 'REMOTE_ADDR' ) );
		// FORM DATA ERROR HANDLING
		if ( $e == "" || $p == "" ) {
			ilog('$e User denid login.. adding system msg');
			system_msg($babel->say('error_loginfailed'));
			//echo "login_failed";
			return false;
		} else {
			// END FORM DATA ERROR HANDLING
			$sql = "SELECT id, username, name,password,logincount,city,country_code,region,roles FROM users WHERE (email='$e' or username='$e') AND activated='1' LIMIT 1";
			$query = get_query( $sql );
			$row = $query->fetch_row();
			$db_id = $row[0];
			$db_username = $row[1];
			$db_name = $row[2];
			$db_pass_str = $row[3];
			$logincount = $row[4];
			$city = $row[5];
			$country = $row[6];
			$region = $row[7];
			$roles = $row[8];

			ilog( "LOGIN: retrieving $db_username , $db_pass_str" );
			if ( $p != $db_pass_str ) {
				system_msg($babel->say('error_loginfailed'));
				ilog('$e User denid login.. adding system msg');
				return false;
			} else {
				// CREATE THEIR SESSIONS AND COOKIES
				$_SESSION['userid'] = $db_id;
				$_SESSION['username'] = $db_username;
				$_SESSION['name'] = $db_name;
				$_SESSION['password'] = $db_pass_str;

				set_roles($roles);
				setcookie( "id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE );
				setcookie( "user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE );
				setcookie( "pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE );
				// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
				update_login_info($db_id);
				return true;
			}
		}
		
	}
	system_msg($babel->say("registration_fill_all_data"));
	return false;
}

?>
