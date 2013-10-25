<?php
//session_destroy();
//session_start()();
$facebook_loginbox = false;

if (isset($_SESSION['token']))
  $access_token = $_SESSION['token'];


if ($_SERVER["SERVER_NAME"] == 'dev.worldrainbowfamily.org') {
  $app_id = '166422403541555';
  $app_secret = '8534fd27acdbd26609c5c8da5a31cd90';
  $my_url = "http://dev.worldrainbowfamily.org/";

}
else {
  $app_id = '486260484767148';
  $app_secret = '1d409932ffc390dbc4d10bf89116a314';
  $my_url = "https://www.worldrainbowfamily.org/";

}

$permissions = "email,user_about_me,user_website";
if (isset($_REQUEST["code"]))
	$code = $_REQUEST["code"];
$imported = true; 
//ilog("processing facebook request"); 
  
if(empty($code)) {
  //ilog("setting dialog url");
  $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
  $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
   . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
   . $_SESSION['state'] . "&scope=$permissions";
   $imported = false;
}
else if(!isset($access_token)) {
  
  $token_url = "https://graph.facebook.com/oauth/access_token?"
     . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
     . "&client_secret=" . $app_secret . "&code=" . $code;
  ilog($token_url);
  $response = @file_get_contents($token_url);
  $params = null;
  parse_str($response, $params);
  $access_token = $params['access_token'];
  //ilog('Saved Access Token:'.$access_token);
  $_SESSION['token'] = $access_token;
}

if (!empty($access_token) && !(empty($code))){
 
  


  $graph_url = "https://graph.facebook.com/me?access_token=" 
   . $access_token;
  
  $user = json_decode(file_get_contents($graph_url));
  
  // check if there is a user already in the sytem with that login
  $sql = "SELECT * from users where extid = '". $user->id."'";
  ilog($sql);
  $result = get_query($sql);
  $check = mysqli_num_rows($result);
  if ($check > 0) {
    $dbuser = $result->fetch_object();

    // there exists already a user.. just forward to login
    force_login($dbuser->id,$dbuser->username,$dbuser->password,$dbuser->name,$dbuser->roles);
    header("Location: ".$CONFIG->site.'/');
    exit();
  }

  ilog($graph_url);
  $facebook_loginbox = true;

  system_msg($babel->say('p_facebook-connect-success'));      
}
else {
  //ilog("final else!!");
  if (!empty($code)) {
    system_msg($babel->say('p_facebook-connect-failed')); 
    ilog("facebook connection failed!!");      
  }
}

?>

<meta charset="UTF-8">
<title>Sign Up</title>

<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="<?=$CONFIG->site?>/page/userlogin/css/registration.css">

<script src="<?=$CONFIG->site?>/page/userlogin/js/registration_js.php"></script>