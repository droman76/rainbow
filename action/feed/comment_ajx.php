<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'page/feed/feediterator.php'); 
include($_SESSION['home'].'lib/notifications.php');

$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('feed');

$comment = strip_tags($_REQUEST['comment']);
$postid = $_REQUEST['postid'];
$scope = $_REQUEST['scope'];
$view = $_REQUEST['view'];

$user_id = $_SESSION['userid'];
$user_name = $_SESSION['username'];
$user_full_name = $_SESSION['name'];

$profile_url = $CONFIG->site . '/profile/'.$user_id;
//post_url = $CONFIG->site . '/post/'.$post_id;
$extinfo = $CONFIG->userdata .$_SESSION['username'].'/extinfo';

$pic_url = '';
$image_extension = '';
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
$link_src = '';
$link_title = '';
$link_desc = '';
$link_video = '';

$source = 'local';
$date = time();
$timestamp = $date;

$sql = "INSERT INTO `feed_comments` (`comment_id`, `post_id`, `user_id`, `user_name`,`user_full_name`, `user_profile_url`, `user_pic_url`, `message`,`scope`, `link_src`, `link_title`, `link_description`, `link_video_src`, `source`, `date`, `last_update`, `continent_code`, `country_code`, `country`, `region`, `city`, `longitude`, `latitude`) VALUES (NULL, '$postid', '$user_id', '$user_name','$user_full_name', 'local', '$image_extension', '".db_escape($comment)."','$scope', '$link_src', '$link_title', '$link_desc', '$link_video', '$source', $date, $date, '$continent', '$countrycode', '$country', '$region', '$city', '$longitude', '$latitude');";

get_query($sql);
$comment_id = get_insert_id();

$location = get_my_location();

$ago = ago(time()) .' '. $babel->say("p_ago");
$location = $babel->say('p_from'). ' '.$location;

// notify
notify($user_id,'comment',$comment_id,'commented');


include($CONFIG->home.'page/feed/feed_comment.php');


?>