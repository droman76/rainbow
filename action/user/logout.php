<?php


function logout() {
	$babel = new BabelFish('userlogin');
	// Expire their cookie files
	if ( isset( $_COOKIE["id"] ) && isset( $_COOKIE["user"] ) && isset( $_COOKIE["pass"] ) ) {
		setcookie( "id", '', strtotime( '-5 days' ), '/' );
		setcookie( "user", '', strtotime( '-5 days' ), '/' );
		setcookie( "pass", '', strtotime( '-5 days' ), '/' );
	}
	// Destroy the session variables
	unset( $_SESSION['userid'] );
	unset( $_SESSION['username'] );
	unset( $_SESSION['country'] );
	unset( $_SESSION['country_code'] );
	unset( $_SESSION['continent_code'] );
	
	unset( $_SESSION['region'] );
	unset( $_SESSION['city'] );
	unset( $_SESSION['avatarchanged'] );
	unset( $_SESSION['groupavatarchanged'] );
	unset( $_SESSION['geolocated'] );
	unset ( $_SESSION['root']);
	unset ($_SESSION['admin']);




	// Double check to see if their sessions exists
	if ( isset( $_SESSION['username'] ) ) {
		system_msg( $babel->say("e_logout_failed"));
		return false;
	} else {
		system_msg($babel->say('i_logout_success'));
		return true;
	}

}
?>
