<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 
include($_SESSION['home'].'page/feed/feediterator.php'); 


$size = $_REQUEST['size'];
$start = $_REQUEST['start'];
$view = $_REQUEST['view'];
$scope = $_REQUEST['scope'];
$master_view = $_REQUEST['master_view'];
$isgroup = false;

$CONFIG = $_SESSION['CONFIG'];

$babel = new BabelFish('feed');

if ($view != 'continent' && $view != 'country' && $view !='region' && $view != 'city' && $view != 'world' && strlen($view) > 2) {
	$isgroup = true;
}


$nscope = '';
$istag = false;
if (strlen($view) == 1) {// it is a tag view
	//$where = "$view = 'x'"; //add view filter
	//$nscope = getScope($master_view);
	$istag = true;

}else $where = '';

if (!$isgroup && !$istag) {
	if (!displayFeed($start,$size,$view,$where,false,$nscope)) {
		ilog("Read feed found NO items!");
		include($CONFIG->home.'page/feed/no_items.php');
	}
}
else if($istag && !$isgroup) {
	if (!displayTagFeed($start,$size,$view)) {
		ilog("Read tag feed found NO items!");
		include($CONFIG->home.'page/feed/no_items.php');
	}
}
else {
	ilog('calling group from feed!');
	if (!displayGroupFeed($start,$size,$view)) {
		ilog("Read group feed found NO items!");
		include($CONFIG->home.'page/feed/no_items.php');
	}
}
cronos_update($view);

?>