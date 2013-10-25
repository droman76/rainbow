<?php 

$groupname = $_REQUEST['groupname'];
$description = $_REQUEST['description'];
$visibility = $_REQUEST['visibility'];
$access = $_REQUEST['access'];
$approval = $_REQUEST['approval'];
$moderation = $_REQUEST['moderation'];
$button_action = 'p_create';
$action = 'new';
$groupid = '';

?>


<div style='margin:30px'>
<h1>Create a new group</h1>


<?php include('group_form.php'); ?>



</div>
