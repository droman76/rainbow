<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$userid = $_REQUEST['userid'];
$username = $_REQUEST['username'];



proxy_user($userid);

header("Location: ".$CONFIG->site.'/profile/'.$username);


?>