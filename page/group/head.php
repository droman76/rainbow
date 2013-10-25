<?php

$groupid = $_REQUEST['path'];
if (!isset($_REQUEST['action']))
	include ($CONFIG->home . 'page/feed/head.php');


if (($groupid == '' || $groupid == null) && !isset($_REQUEST['action'])) {
	no_access();

}
// find out if the group exists
if (!isset($_REQUEST['action'])){
	$q = "select * from groups where id = '$groupid'";
	$r = get_query($q);
	$c = get_rows($r);
	if ($c == 0 ) no_access();
}
?>



<script type='text/javascript'>

var current_view = '<?=$groupid?>';

function createGroup() {


	_("groupcreate").submit();
}



</script>
