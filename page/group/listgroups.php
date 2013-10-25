
<div style='margin:15px'>

<h1><?=$babel->say('p_grouplist')?> </h1>

<br>
<?php

$q = "select * from groups";
$r = get_query($q);
$trust = get_user_trust_level();
$me = get_logged_in_user_id();


while ($g = $r->fetch_object()) {

	$id = $g->id;
	$name = $g->name;
	$description = $g->description;
	$visibility = $g->visibility;
	$creator = $g->creator;

	if ($visibility == 'trust' && $trust < 1 && $creator != $me && !is_real_friend($creator))
		continue;
	else if ($visibility == 'friends' && $creator != $me && !is_friend($creator)) {
		continue;
	}
	else if ($visibility == 'close' && $creator != $me && !is_real_friend($creator)) {
		continue;
	}
	else if ($visibility == 'private' && $creator != $me) {
		continue;
	}

?>
<div>
<a style='font-size:14px' href='<?=$CONFIG->siteroot?>group/<?=$id?>'><?=$name?></a><br>
<?=$description?>


</div>
<br>


<?php } ?>
</div>