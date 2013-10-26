<?php

function no_access() {
	global $CONFIG;
	include($CONFIG->home.'/page/denied/access_denied.php');
	exit(1);
}


function is_user_logged_in() {
	return isset($_SESSION['userid']);
}
function get_logged_in_user_id() {
	return $_SESSION['userid'];

}
function get_logged_in_user_name() {
	return $_SESSION['username'];

}


function get_logged_in_user_fullname() {
	return $_SESSION['name'];

}
function get_user_trust_level() {
	if (isset($_SESSION['trustlevel']))
		return $_SESSION['trustlevel'];
	else return 0;
}
//TODO: load here proper admin and translation permissions
function is_admin() {
	if (isset($_SESSION['admin']) && $_SESSION['admin'] == 'on') return true;
	else return false;
}
function is_root() {
	if (isset($_SESSION['root']) && $_SESSION['root'] == 'on') return true;
	else return false;

}
function is_friend($id) {
	$me = get_logged_in_user_id();
	$q = "select * from friends where user1=$me and user2=$id and accepted = 1";
	$r = get_query($q);
	$c = get_rows($r);
	
	if ($c == 0) return false;
	if ($c > 0 ) return true;

}
function get_friend_connection($id) {
	$me = get_logged_in_user_id();
	$q = "select level from friends where user1=$me and user2=$id";
	$r = get_query($q);
	if ($u = $r->fetch_object()) {
		return $u->level;
	}
	else {
		return -1;
	}
	
}
function get_group_unread($groupid) {
	global $CONFIG;
	$t_file = $CONFIG->userdata. get_logged_in_user_name().'/timestamps';
	$cronos = json_decode(file_get_contents($t_file));
	if (isset($cronos->$groupid)){
		$time = $cronos->$groupid;
	}
	else {
		$time = time();
		cronos_update($groupid);
	}

	$q = "select * from feed where group_id = '$groupid' and date > $time";
	//ilog("GROUP UNREAD: $q");
	$r = get_query($q);
	$c = get_rows($r);

	return $c;
}

function set_roles($roles) {
	ilog('Setting roles: $roles');
	if ($roles != null && $roles != '') {
		$rolelist = explode(',', $roles);
		foreach($rolelist as $role) {
			switch ($role) {
				case 'translator':
					$_SESSION['translator'] = 'on';
					//ilog()
					break;
				case 'admin':
					$_SESSION['admin'] = 'on';
					break;
				case 'root':
					$_SESSION['admin'] = 'on';
					$_SESSION['root'] = 'on';

					break;
				default:
					
					break;
			}
		}
	}
		
}

function is_friend_request_pending($id) {
	$me = get_logged_in_user_id();
	$q = "select * from friends where user1=$me and user2=$id and accepted = 0";
	$r = get_query($q);
	$c = get_rows($r);
	
	if ($c == 0) return false;
	if ($c > 0 ) return true;
}

function is_real_friend($id) {
	$me = get_logged_in_user_id();
	$q = "select * from friends where user2=$me and user1=$id and level > 0";
	$r = get_query($q);
	$c = get_rows($r);
	
	if ($c == 0) return false;
	if ($c > 0 ) return true;

}
function is_group_member($group) {
	$me = get_logged_in_user_id();
 	$q = "select * from group_members where userid = $me and groupid = '$group'";
 	$r = get_query($q);
	$c = get_rows($r);
	
	if ($c == 0) return false;
	if ($c > 0 ) return true;

}
function is_group_admin($group) {
	$me = get_logged_in_user_id();
 	$q = "select * from group_members where userid = $me and groupid = '$group' and role = 'admin'";
 	ilog($q);
 	$r = get_query($q);
	$c = get_rows($r);
	
	if ($c == 0) return false;
	if ($c > 0 ) return true;

}

function is_translator() {
	if (isset($_SESSION['translator']) && $_SESSION['translator'] == 'on') return true;
	return false;
}

function isUser($user) {
	ilog("Checking for user existence $user");
	$q = "select id from users where username = '$user'";
	$r = get_query($q);
	$c = get_rows($r);
	return ($c > 0);
}
function is_new_user() {
	if ($_SESSION['isnew'] == 'yes') {
		$_SESSION['isnew'] = 'no';
		
		return true;
	} 
	else return false;
}


function isUserImage($user,$image) {
	global $CONFIG;
	ilog("Checking ". $CONFIG->userdata . 'proxies/'.$user . '/'.$image);
	if (file_exists($CONFIG->userdata . $user . '/'.$image)) return true;
	else if (file_exists($CONFIG->data . 'proxies/'.$user . '/'.$image)) return true;
	return false;

}


function strim($message, $size){
	if (strlen($message) <= $size) return $message; // no trim needed
	$message = strip_tags($message);			
    		// truncate string
    $message = substr($message, 0, $size);

    // make sure it ends in a word so assassinate doesn't become ass...
    $message = substr($message, 0, strrpos($message, ' ')).'…';
    return $message;
    		
}

class PostAction {

}

function postaction($q,$me_id,$action,$link) {
	global $babel;
	$pa = new PostAction();
	$lankey1 = "p_".$action ."_this";
	$lankey2 = "p_".$action . "_this_only";


	$r = get_query($q);
	$items = 0;
	$you = false;
	$namestring = '';
	
	while($or = $r->fetch_object()){
		$un = $or->username;
		$n = $or->name;
		$i = $or->user_id;
		if ($i == $me_id) $you = true;
		if ($items < 3 && $i != $me_id)
			$namestring .= "<a href='/profile/$un'>$n</a> ";
		$items++;
	}
	$btext = '';

	if ($you && $items == 1) $btext = $babel->say('p_you') .' '. $babel->say($lankey1);
	else if (!$you && $items == 1) $btext = $namestring .' '. $babel->say($lankey2);
	else if ($you && $items > 1 && $items < 3) $btext = $babel->say('p_you') .' '.$babel->say('p_and') .' '. $namestring . ' '. $babel->say($lankey1);
	else if ($you && $items >= 3) $btext = $babel->say('p_you') .' '. $babel->say('p_and') .' '. ($items-1) . ' '. $babel->say('p_people') . ' '. $babel->say($lankey1);
	else if (!$you && $items > 1 && $items < 3) $btext = $namestring . ' '. $babel->say($lankey1);
	else if (!$you && $items >= 3) $btext = $items . ' '. $babel->say('p_people') . ' '. $babel->say($lankey1);

	$pa->items = $items;
	$pa->you = $you;
	$pa->text = $btext;

	return $pa;

}

function add_user_to_group($userid,$groupid) {
	$q = "INSERT into group_members (groupid,userid,role) VALUES ('$groupid',$userid,'member')";
	get_query($q);
}

function force_login($userid,$username,$password,$name,$roles) {
	$_SESSION['userid'] = $userid;
	$_SESSION['username'] = $username;
	$_SESSION['name'] = $name;
	$_SESSION['password'] = $password;

	set_roles($roles);

	setcookie( "id", $userid, strtotime( '+30 days' ), "/", "", "", TRUE );
	setcookie( "user", $username, strtotime( '+30 days' ), "/", "", "", TRUE );
	setcookie( "pass", $password, strtotime( '+30 days' ), "/", "", "", TRUE );

	update_login_info($userid);

}

function update_login_info($userid) {
	$q = "select * from users where id = $userid";
	$r = get_query($q);
	$babel = new BabelFish('user');

	ilog('Updating user login info for userid $userid');
	if ($o = $r->fetch_object()){
		$country = $o->country_code;
		$region = $o->region;
		$city = $o->city;
		$count = $o->logincount;
		if ($count == 0) {
			ilog("New User Detected - setting up flags...");
			$_SESSION['isnew'] = 'yes';
			post_action('action_join','','city');
			add_user_to_group($userid,'sitefeedback');

			// Now add the intro message here
			$intro = $babel->say('message_intro',false);
			// Find out root user
			$q = "select * from users where roles like '%root%' LIMIT 1";
			ilog("Finding root $q");
			$r = get_query($q);
			if ($o = $r->fetch_object()) {
				$root = $o->id;
				// send message
				$q = "insert into messaging (recipient,sender,message,sent) values ($userid,$root,'$intro',".(time()+5).")";
				ilog("Adding automatic message $q");
				get_query($q);
			}

			

				 
		}
		if ($city != $_SESSION['city'] && $country == $_SESSION['country_code']) {
			// the user city is different but country is same, so this means user specified a new city
			// so load this city in the session instead
			$_SESSION['city'] = $city;
			$_SESSION['region'] = $region;
		}
				
		$count++;
		if ($region != $_SESSION['region'] || $country != $_SESSION['country_code']) {
			// user has moved location.. update...
			$locationupdate = "country = '".$_SESSION["country"]."', region = '".$_SESSION["region"]."', city = '".$_SESSION["city"]."', latitude = '".$_SESSION["latitude"]."',longitude = '".$_SESSION["longitude"]."',country_code = '".$_SESSION["country_code"]."',";
		}
		else $locationupdate = '';
		// update browser info..
		$ua=getBrowser();
		$browser = $ua['name'];
		$browserversion = $ua['version'];
		$platform = $ua['platform'];
		$now = time();
		$language = $_SESSION['user_language'];
		$sql = "UPDATE users SET language='$language',browser='$browser', browser_version='$browserversion', platform='$platform', logincount=$count, ip='".$_SESSION['ip']."', $locationupdate lastlogin=$now WHERE id=$userid LIMIT 1";
		ilog("Updating USER $sql");
		get_query( $sql );
				
	}

}

function get_avatar_image($username,$size='medium'){
	global $CONFIG;
	$extinfo = $CONFIG->userdata .$username.'/extinfo';
	$extinfo2 = $CONFIG->data .'proxies/'.$username.'/extinfo';
    if (file_exists($extinfo)){
		$hr = fopen($extinfo,'r');
		$image_extension = fread($hr, filesize($extinfo));
		fclose($hr);
		$avatar_image = $CONFIG->site . '/myimages/'.$username.'/' . $size.'_'.$username.$image_extension;
		
	}
	else if (file_exists($extinfo2)) {
		$hr = fopen($extinfo,'r');
		$image_extension = fread($hr, filesize($extinfo2));
		$image_extension = '.jpg';
		fclose($hr);
		$avatar_image = $CONFIG->site . '/myimages/'.$username.'/' . $size.'_'.$username.$image_extension;		
	}
	else {
		$avatar_image = $CONFIG->site . '/template/default/images/default'.$size.'.gif';
	}
	return $avatar_image;

}


function page_controller() {
	global $CONFIG;

	if (!isset($_REQUEST['page']))$rpage = '';
	else $rpage = trim($_REQUEST['page'],'/');
	$action = '';
	if (isset($_REQUEST['action']))
		$action = $_REQUEST['action'];
	
	if ($rpage == 'userlogin') return $rpage;

	if ($rpage == 'denied') {// to mesigify
		echo "You have no access to this page.<br><br>";
		$rpage = 'userlogin';
	}	


	// this block handles when a user is activating for first time
	if ($rpage == 'activation'){
		include($CONFIG->home . 'action/user/activation.php');
		global $babel;
		$babel = new BabelFish('activation');
		if(!sign_in_user_from_request()) $rpage = 'userlogin';
		else if(activate_user()) $rpage = 'feed';
		else $rpage = 'feed';
		
	}

	


	// this first block is to redirect if user is logged in or not
	if (!gatekeeper() && $rpage=='' ) $rpage = 'userlogin';
	else if (gatekeeper() && $rpage=='') $rpage = 'feed';
	else if (gatekeeper()) {}
	else if($rpage == 'userlogin') return $rpage;
	else if($rpage == 'cron') return $rpage; // cron allowed to execute outside checks
	else $rpage = 'denied';
	

	// this second block checks for the case a user is logging in
	// therefore changing the redirection
	if ($action == 'login') {
		include($CONFIG->home . 'action/user/login.php');
		$result = login();
		if ($result) $rpage = 'feed';
		else $rpage = 'userlogin';
	}

	// this third block checks for verifying if the page actually is valid
	// no check to see if this is a valid page
	$page_loc = $CONFIG->home ."page/".$rpage."/".$rpage.".json";
		
	if (!file_exists($page_loc)) {
			// check to see if this is a child case
			$p = strpos($rpage, '/');
			if ($p > 0) {
				$parent = substr($rpage, 0,$p);
				// check to see if the parent exists
				$page_loc =  $CONFIG->home ."page/".$parent."/".$parent.".json";
				if (!file_exists($page_loc))
					$rpage = "denied"; // no parent with that name.. deny
				else {
					$_REQUEST['path'] = substr($rpage, $p+1,strlen($rpage));
					$rpage = $parent;
				}
			}
			else $rpage = "denied";
			
	}	



	if ($action == 'logout'){
	  	
      include($CONFIG->home.'action/user/logout.php');
      if (logout())
      	$rpage = 'userlogin';
 	 	else $rpage = 'feed';
 	 }



	return $rpage;


}

function ago($tm,$rcs = 0) {
    $cur_tm = time(); 
    $dif = $cur_tm-$tm;
    $pds = array('second','minute','hour','day','week','month','year','decade');
    $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);

    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
        $no = floor($no);
        if($no <> 1)
            $pds[$v] .='s';
        $x = sprintf("%d %s ",$no,$pds[$v]);
        if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0))
            $x .= time_ago($_tm);
        return $x;
 }

function get_my_location() {
	$babel = new BabelFish('feed');
	if ((isset($_SESSION['region']) && isset($_SESSION['country']) && $_SESSION['region'] != '') && ($_SESSION['country_code'] == 'US' || $_SESSION['country_code'] == 'CA')) 
		$location = $babel->say($_SESSION['city']) . ' '. $_SESSION['region'].', '.$_SESSION['country'];
	if ((isset($_SESSION['city']) && $_SESSION['city'] != '')&& isset($_SESSION['country'])) 
		$location = $babel->say($_SESSION['city']) . ', '.$babel->say($_SESSION['country']);
	else if (isset($_SESSION['country'])) 
		$location = $_SESSION['country'];

	return $location;
}


function proxy_user($userid) {
	global $babel;
				// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
	$sql = "SELECT * FROM users WHERE id = $userid LIMIT 1";
	ilog($sql);
	$query = get_query( $sql );
	if($u = $query->fetch_object()){
		
		$db_id = $u->id;
		$db_username = $u->username;
		$db_name = $u->name;
		$city = $u->city;
		$region = $u->region;
		$country = $u->country;
		$country_code = $u->country_code;
		$continent_code = $u->continent_code;
		ilog("PROXY: setting userid $db_id and username $db_username");
		
		// CREATE THEIR SESSIONS AND COOKIES
		$_SESSION['userid'] = $db_id;
		$_SESSION['username'] = $db_username;
		$_SESSION['name'] = $db_name;
		$_SESSION['password'] = $db_pass_str;
		$_SESSION['city'] = utf8_encode($city);
		$_SESSION['region'] = utf8_encode($region);
		$_SESSION['country'] = utf8_encode($country);
		$_SESSION['country_code'] = $country_code;
		$_SESSION['continent_code'] = $continent_code;
		$_SESSION['longitude'] = $long;
		$_SESSION['latitude'] = $lat;
		$_SESSION['geolocated'] = 'yes';
	}
	/*
	setcookie( "id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE );
	setcookie( "user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE );
	setcookie( "pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE );
	*/			
			

}




function sign_in_user_from_request($activated='0') {
	$u = $_REQUEST['id'];
	$p = $_REQUEST['p'];
	global $babel;
				// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
	$sql = "SELECT id, username, name, password FROM users WHERE id = $u AND activated='$activated' LIMIT 1";
	$query = get_query( $sql );
			if(!$row = $query->fetch_row()){
				error_msg($babel->say('e_userisalreadyactive'));
				return false;
			}
			$db_id = $row[0];
			$db_username = $row[1];
			$db_name = $row[2];
			$db_pass_str = $row[3];


			ilog( "LOGIN: retrieving $db_username , $db_pass_str" );
			if ( $p != $db_pass_str ) {
				error_msg($babel->say('error_loginfailed'));
				ilog('$e User denied login.. adding system msg');
				return false;
			} else {
				// CREATE THEIR SESSIONS AND COOKIES
				$_SESSION['userid'] = $db_id;
				$_SESSION['username'] = $db_username;
				$_SESSION['name'] = $db_name;
				$_SESSION['password'] = $db_pass_str;
				setcookie( "id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE );
				setcookie( "user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE );
				setcookie( "pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE );
				
				update_login_info($db_id);
				return true;
			}
			error_msg($babel->say('error_loginfailed'));
			return false;
	

}

function getLocationString() {

$city = $_SESSION['city'];
$region = $_SESSION['region'];
$country = $_SESSION['country'];
$location = '';
if (isset($country) && isset($region) && isset($city)) $location .= " $city, $region $country";
else if (isset($country) && isset($region)) $location .= " $region $country";
else if (isset($country)) $location .= " $country";

return $location;
}

/**
*	Log information message
*	This function logs all messages at INFO level 
* 	@param string $msg The message to log
*	@return void
*/
function ilog($msg) {
	global $CONFIG;
	global $ihandle;
	if (!isset($ihandle)){
		$ihandle = fopen($CONFIG->ilog, 'a');
	}
	
	$user = $_SESSION['ip'];
	if (isset($_SESSION['username'])) $user = $_SESSION['username'];

	$msg = '[INFO] '.$user. ' || '.$_SESSION['rpage'].' => ' . $msg;
	fwrite($ihandle,$msg."\n");
}
/**
*	Log information message
*	This function logs all messages at ERROR level 
* 	@param string $msg The message to log
*	@return void
*/

function elog($msg){
	global $CONFIG;
	global $ehandle;

	if (!isset($ehandle)){
		$ehandle = fopen($CONFIG->elog, 'a');
	}
	$user = $_SESSION['ip'];
	if (isset($_SESSION['username'])) $user = $_SESSION['username'];

	$msg = '[ERROR] '.$user. ' || '.$_SESSION['rpage'].' => ' . $msg;

	fwrite($ehandle,$msg."\n");
	
	//fclose($ehandle);
}

function system_msg($key){
	$_SESSION['system'] = $key . '::'. $_SESSION['system']; 

}
function error_msg($key){
	$_SESSION['system'] = $key . '::'. $_SESSION['system']; 

}

function get_all_system_messages() {
	$messages = $_SESSION['system'];
	$_SESSION['system'] = '';
	return $messages;

}

function cleanup() {
	global $ehandle;
	global $ihandle;
	
	if ($ehandle != null) fclose($ehandle);
	if ($ihandle != null)fclose($ihandle);


}

function post_action($action,$message,$view,$object_id='') {
	$city = $_SESSION['city'];
	$region = $_SESSION['region'];
	$country = $_SESSION['country'];
	$countrycode = $_SESSION['country_code'];
	$continent = $_SESSION['continent_code'];
	$longitude = $_SESSION['longitude'];
	$latitude = $_SESSION['latitude'];

	if ($city == '') $view = 'region';
	if ($region == '') $view = 'country';

	$user_id = get_logged_in_user_id();
	$user_name = get_logged_in_user_name();
	$user_full_name = get_logged_in_user_fullname();


	// not set yet
	$post_url = '';
	$post_type = '';


	$source = 'local';


	$now = time();		
	
	$sql = "INSERT INTO `feed` (`user_id`, `user_name`,`user_full_name`, `user_profile_url`,"; 
	$sql.= "`user_pic_url`, `message`, `tags`, `post_url`, `post_pic_url`, `post_type`, `link_src`,"; 
	$sql.= "`link_title`, `link_description`, `link_video_src`, `source`, `date`, `parent`, `view`,"; 
	$sql.= "`continent_code`, `country_code`, `country`, `region`, `city`, `longitude`, `latitude`,action,action_object)"; 
	$sql.= "VALUES ('$user_id', '$user_name','$user_full_name', 'local', '.jpg', '".db_escape($message)."', '', '',"; 
	$sql.= "'', '', '', '', '', '', 'local',";
	$sql.= "'$now', '-1',"; 
	$sql.= "'$view', '$continent', '$countrycode', '$country', '$region', '$city',"; 
	$sql.= "'$longitude', '$latitude','$action','$object_id')";
	ilog("POSTING ACTION $action: $sql");
	get_query($sql);

	$post_id = get_insert_id();

	// insert now into wall
	$sql = "INSERT INTO wall (post_id,user_id,scope) VALUES ($post_id,$user_id,1)";
	get_query($sql);

}

function gatekeeper() {
	if(isset($_SESSION["userid"]) && isset($_SESSION["username"])) {
		global $user_id, $user_name, $user_fullname;
		$user_id = preg_replace('#[^0-9]#', '', $_SESSION['userid']);
		$user_name = preg_replace('#[^a-z0-9]#i', '', $_SESSION['username']);
		$user_fullname = preg_replace('#[^a-z0-9]#i', '', $_SESSION['name']);
	
	// Verify the user
		return true;
	}
	return false;

}

function add_good_karma($user_id) {
	// find out if a user already has a karmic entry
	$q = "select * from karma where user_id = $user_id";
	$r = get_query($q);


	if ($o = $r->fetch_object()) {
		$good = $o->good_karma;
		$good++;
		
		// an entry exists update
		$q = "update karma set good_karma = $good";
		get_query($q);
	}
	else {
		// create a new entry
		$q = "insert into karma (user_id,good_karma) values ($user_id,1)";
		get_query($q);

	}
}

function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 




?>