<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'page/feed/feediterator.php'); 
$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('feed');
$scope = $_REQUEST['scope'];
$view = $_REQUEST['view'];
$user_id = $_SESSION['userid'];
$groupid = $_REQUEST['groupid'];
$master_view = $_REQUEST['master_view'];
$t_file = $CONFIG->userdata.$_SESSION['username'].'/timestamps';
$cronos = json_decode(file_get_contents($t_file));

$viewcount = '';
$time = $cronos->continent;

// This code is used to get the little numbers beside the locality tabs on the feed
// to determine if there has been number published on the other tabs
// the displayFeed is only returning a number because it is passed with the true parameter
// true => return only count of items, not the whole data

if ($view != 'continent')
	$viewcount .= 'continent:'.getCountFeed('continent',"date > $time and user_id !=$user_id").'*';
$time = $cronos->country;
if ($view != 'country')
	$viewcount .= 'country:'.getCountFeed('country',"date > $time and user_id !=$user_id").'*';
$time = $cronos->region;
if ($view != 'region')
	$viewcount .= 'region:'.getCountFeed('region',"date > $time and user_id !=$user_id").'*';
$time = $cronos->city;
if ($view != 'city')
	$viewcount .= 'city:'. getCountFeed('city',"date > $time and user_id !=$user_id").'*';
$time = $cronos->world;
if ($view != 'world')
	$viewcount .= 'world:'.getCountFeed('world',"date > $time and user_id !=$user_id").'*';

if (strlen($view)==1)$tview = $master_view;
else $tview = $view;

$time = $cronos->a;
if ($view != 'a')
	$viewcount .= 'a:'.getCountFeed('a',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->b;
if ($view != 'b')
	$viewcount .= 'b:'.getCountFeed('b',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->c;
if ($view != 'c')
	$viewcount .= 'c:'.getCountFeed('c',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->e;
if ($view != 'e')
	$viewcount .= 'e:'.getCountFeed('e',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->f;
if ($view != 'f')
	$viewcount .= 'f:'.getCountFeed('f',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->g;
if ($view != 'g')
	$viewcount .= 'g:'.getCountFeed('g',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->l;
if ($view != 'l')
	$viewcount .= 'l:'.getCountFeed('l',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->m;
if ($view != 'm')
	$viewcount .= 'm:'.getCountFeed('f',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->n;
if ($view != 'n')
	$viewcount .= 'n:'.getCountFeed('n',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->o;
if ($view != 'o')
	$viewcount .= 'o:'.getCountFeed('o',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->p;
if ($view != 'p')
	$viewcount .= 'p:'.getCountFeed('p',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->r;
if ($view != 'r')
	$viewcount .= 'r:'.getCountFeed('r',"date > $time and user_id !=$user_id",$tview).'*';
$time = $cronos->s;
if ($view != 's')
	$viewcount .= 's:'.getCountFeed('s',"date > $time and user_id !=$user_id",$tview).'*';



// get the new messages 
$time = $cronos->mail;
$viewcount .= 'mail:'.getNewMailCount($time).'*';

// get friend requests
$viewcount .= 'friends:'.getFriendRequestCount() . '*';
$time = $cronos->notifications;
$viewcount .= 'notifications:'.getNotificationCount($time).'*';

//ilog($viewcount);


$time = $cronos->$view;

// It is then encoded in a string viewcount, that later the javascript on the browser will
// split and process
echo $viewcount . "||";

// Now do the actual data retrieval for the current view 
$tag_where = '';
$nscope = '';
if (strlen($view) == 1) {// we are dealing with a tag view here
	$tag_where = "$view = 'x' and";	
	$nscope = getScope($master_view);
}
if ($groupid != '') {
	if (!displayGroupFeed(0,1000,$groupid,"AND date > $time and user_id !=$user_id")) echo "nodata";
	else {
		cronos_update($view);
	}
	//cronos_update($view);

}
else {
	if (!displayFeed(0,1000,$view,"$tag_where date > $time and user_id !=$user_id",false,$nscope)) echo "nodata";
	else {
		cronos_update($view);
	}
}


//divider to identify comments are now starting (for javascript)
//in /pages/feed/head.php

echo "::*::";


// Now retrieve any new comments for the current view
// automatically echoes on output
$comments_view = $view . 'comments';
if (!isset($cronos->$comments_view)){
	cronos_update($comments_view);
	$time = time();
}
else $time = $cronos->$comments_view;
$comments = getNewComments($view,$time,$master_view);
if ($comments == '') {	
	echo "nocomments";
}
else {
	cronos_update($comments_view);
	

}
//echo "*-*-*"; // to denote end of file to javascript
//cronos_update($view);

function getNewMailCount($time) {
	$me = $_SESSION['userid'];
	$sql = "select sender from messaging where (sender=$me or recipient=$me) and sent > $time";
	$messages = get_query($sql);
	$stack = array();
	$count = 0;

	while($inbox = $messages->fetch_object()){
		$loto = $inbox->sender;
		if (in_array($loto, $stack)) continue;
		array_push($stack, $loto);
		$count++;

	}
	return $count;
	
}

function getFriendRequestCount() {
	$me = $_SESSION['userid'];
	$sql = "select count(user1) as requests from friends where user2 = $me and accepted = 0";
	$r = get_query($sql);
	return $r->fetch_object()->requests;
	
}

function getNotificationCount($time) {
	$me = $_SESSION['userid'];
	$sql = "select count(id) as nfx from notifications where recipient = $me and sent > $time";
	$r = get_query($sql);
	//ilog($sql);
	return $r->fetch_object()->nfx;
	
}



?>