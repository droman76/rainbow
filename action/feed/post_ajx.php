<?php
/* SCOPE DEFENITIONS:

world only - 1
world & continent = 2
world & continent & country = 3
world & continent & country & region = 4
world & continent & country & region & city = 5

continent only - 6
continent & country = 7
continent & country & region = 8
continent & country & region & city = 9

country only - 10
country & region - 11
country & region & city - 12

region only - 12
region & city - 14

city only - 15
*/

session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'lib/embedder.php'); 
include($_SESSION['home'].'page/feed/feediterator.php');
include($_SESSION['home'].'lib/notifications.php');

$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('feed');
$scope = $_REQUEST['scope'];
$message = $_REQUEST['message'];
$view = $_REQUEST['view'];
$master_view = $_REQUEST['master_view'];
$tags = trim($_REQUEST['tags'],"::");
$tag_head_clause='';$tag_value_clause='';

if (strlen($tags)>0) {
	$taglist = explode("::", $tags);
	foreach ($taglist as $tag) {
		$tag_head_clause .= ",`$tag`";
		$tag_value_clause .= ",'x'";
	}
}

$t_site = $CONFIG->site;
$t_home = $CONFIG->site . '/template/default';
ilog("View is $view Master View is: $master_view");
if ($message == '' || str_replace(' ', '', $message) == '') {
	echo "error:: ".$babel->say('e_post_cannot_be_empty');
	return;
}

// Strip Tags but allow certain ones
$allow = '<table><thead><th><tr><td><tbody><tfoot><h4><b><i><u>';
$message = strip_tags($message, $allow);


$video = getVideo($message,'460','365');

if (isset($video) && isset($video->frame) ) {
	$message = preg_replace('@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@', "<a href=\"http$2://$4\">$video->title</a>", $message);	
	$link_video = $video->frame;
	$link_desc = $video->description;
	$link_title = $video->title;
	$link_src = $video->url;
}
else {
	$message = preg_replace('@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@', '<a href="http$2://$4">$1$2$3$4</a>', $message);
	$link_src='';$link_title='';$link_desc='';$link_video='';
}

$user_id = $_SESSION['userid'];
$user_name = $_SESSION['name'];
$user_login_id = $_SESSION['username'];

$profile_url = $CONFIG->site . '/profile/'.$user_id;
//post_url = $CONFIG->site . '/post/'.$post_id;
$extinfo = $CONFIG->userdata .$_SESSION['username'].'/extinfo';
$images = trim($_REQUEST['images'],"::");
$post_pic_url = $images;

if ($images != '')
	$images = explode("::", $images);

$pic_url = '';
//TODO: create a smaller version for comment images for mypicurl here
if (file_exists($extinfo)){
	$hr = fopen($extinfo,'r');
	$image_extension = fread($hr, filesize($extinfo));
	fclose($hr);
	$pic_url = $CONFIG->site . '/myimages/'.$_SESSION['username'] .'/small_'.$_SESSION['username'].$image_extension;
	$my_pic_url = $CONFIG->site . '/myimages/'.$_SESSION['username'] .'/small_'.$_SESSION['username'].$image_extension;

}
else {
	$pic_url = $CONFIG->site.'/template/default/images/defaultsmall.gif';
	$my_pic_url = $CONFIG->site.'/template/default/images/defaultsmall.gif';

}

$city = $_SESSION['city'];
$region = $_SESSION['region'];
$country = $_SESSION['country'];
$countrycode = $_SESSION['country_code'];
$continent = $_SESSION['continent_code'];
$longitude = $_SESSION['longitude'];
$latitude = $_SESSION['latitude'];

// not set yet
$post_url = '';
$post_type = '';


$source = 'local';


$now = time();

// Use this to override view in case of select from sidebar right topic
if (strlen($view) == 1) {
	$view = $master_view;
}


ilog("Inserting new Post from $user_name with VIEW $view. MSG: $message");

$sql = "INSERT INTO `feed` (`user_id`, `user_name`,`user_full_name`, `user_profile_url`,"; 
$sql.= "`user_pic_url`, `message`, `tags`, `post_url`, `post_pic_url`, `post_type`, `link_src`,"; 
$sql.= "`link_title`, `link_description`, `link_video_src`, `source`, `date`, `parent`, `view`,"; 
$sql.= "`continent_code`, `country_code`, `country`, `region`, `city`, `longitude`, `latitude`$tag_head_clause)"; 
$sql.= "VALUES ('$user_id', '$user_login_id','$user_name', '$profile_url', '$pic_url', '".db_escape($message)."', '$tags', '$post_url',"; 
$sql.= "'$post_pic_url', '$post_type', '$link_src', '$link_title', '".db_escape($link_desc)."', '$link_video', '$source',";
$sql.= "'$now', '-1',"; 
$sql.= "'$view', '$continent', '$countrycode', '$country', '$region', '$city',"; 
$sql.= "'$longitude', '$latitude'$tag_value_clause)";

get_query($sql);
ilog($sql);

$post_id = get_insert_id();
$location = 'here and now';

// insert now into wall
$sql = "INSERT INTO wall (post_id,user_id,scope) VALUES ($post_id,$user_id,1)";
get_query($sql);

// notify followers of current user of this post
notify($user_id,'post',$post_id,'posted');



$location = get_my_location();

$ago = ago(time()) .' '. $babel->say("p_ago");
$location = $babel->say('p_from'). ' '.$location;
$timestamp = time();
$last_viewed = time();
/*** FEED ITEM INPUTS
   * $post_id => Post id
   * $user_id => The feed items user id 
   * $user_name => The user name 
   * $pic_url => the picture to display of the user
   * $images => the array of images (or single string for one image) of the post
   * $location => the location the message is sent from
   * $ago => how long ago it was posted
   * $message => the body of the message
   * $timestamp => the time the message is created

**/
$message = str_replace("\n", "<br>", $message);

include($CONFIG->home.'page/feed/feed_item.php');
// if in tag view also append the post for the super view
if (strlen($view)==1) {
	echo "*****";
	$view = $master_view;
	include($CONFIG->home.'page/feed/feed_item.php');
}


?>