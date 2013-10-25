<?php

session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 


$emailoruser = $_REQUEST['e'];
$sql = "select email from users where email='$emailoruser' or username='$emailoruser'";
ilog("executing sql: $sql");

$result = get_query($sql);

$row = $result->fetch_row();
if (isset($row[0])){
	echo "request_success";
}
else {
	echo "There is no user with that email. Please try again.";

}






?>