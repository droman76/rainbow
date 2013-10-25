<!-- ********************************** -->
<!-- *********** activation.php ******* -->
<!-- ********************************** -->
<?php

function activate_user() {
	global $babel;

	if ( isset( $_GET['id'] ) && isset( $_GET['u'] ) && isset( $_GET['e'] ) && isset( $_GET['p'] ) ) {
		// Connect to database and sanitize incoming $_GET variables
		$id = preg_replace( '#[^0-9]#i', '', $_GET['id'] );
		$u = preg_replace( '#[^a-z0-9]#i', '', $_GET['u'] );
		$e = db_escape( $_GET['e'] );
		$p = db_escape( $_GET['p'] );
		// Evaluate the lengths of the incoming $_GET variable
		//echo "Reciving: id:$id u:$u e:$e p:$p";

		// Check their credentials against the database
		$sql = "SELECT * FROM users WHERE id='$id' AND username='$u' AND email='$e' AND password='$p' LIMIT 1";
		elog( $sql );
		$query = get_query( $sql );
		$numrows = mysqli_num_rows( $query );
		// Evaluate for a match in the system (0 = no match, 1 = match)
		if ( $numrows == 0 ) {
			// Log this potential hack attempt to text file and email details to yourself
			//echo("location: message.php?msg=Your credentials are not matching anything in our system");
			//exit();
			error_msg($babel->say('e_credentails_dont_match') );
			return false;
		}
		// Match was found, you can activate them
		$sql = "UPDATE users SET activated='1' WHERE id='$id' LIMIT 1";
		$query = get_query( $sql );
		// Optional double check to see if activated in fact now = 1
		$sql = "SELECT * FROM users WHERE id='$id' AND activated='1' LIMIT 1";
		$query = get_query( $sql );
		$numrows = mysqli_num_rows( $query );
		// Evaluate the double check
		if ( $numrows == 0 ) {
			// Log this issue of no switch of activation field to 1
			error_msg($babel->say('activation_failure') );
			return false;
		} else if ( $numrows == 1 ) {
				// Great everything went fine with activation!
				system_msg($babel->say("activation_success"));
				return true;
			}
	} else {
		// Log this issue of missing initial $_GET variables
		error_msg($babel->say('e_missing_parameters'));
		return false;
	}
}
?>
