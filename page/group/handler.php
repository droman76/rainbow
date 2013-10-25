<?php 

// Handler, to manage the specific actions to different template views

function pre_processor($page) {
	
	$action = $_REQUEST['action'];
	if ($action == 'new')
		$page->layout = 'nosidebar';
	else if ($action == 'list' || $action == 'edit'){
		$page->layout = 'nosidebar';
		//$page->sidebar_left = "master/sidebar_left.php";
		//$page->header = "master/header.php";
	}
	return $page;



}

function post_processor($page) {

	return $page;
	
}