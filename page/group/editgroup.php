<?php 

$groupid = $_REQUEST['groupid'];

$q = "select * from groups where id = '$groupid'";
$r = get_query($q);
$g = $r->fetch_object();



$groupname = $g->name;

$description = $g->description;
$visibility = $g->visibility;
$access = $g->access;

$postaccess = $g->postaccess;
$moderation = $g->moderation;
$button_action = 'p_save_changes';
$action = 'edit';
$me = get_logged_in_user_id();

//Only Admins can access this page
if (!is_group_admin($groupid)){
	no_access();
}


?>


<div style='margin:30px'>
<h1>Edit Group <?=$groupname?></h1>


<?php include('group_form.php'); ?>



</div>
