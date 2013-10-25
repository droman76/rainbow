<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 
include($_SESSION['home'].'lib/database.php'); 

$CONFIG = $_SESSION['CONFIG'];
$babel = new BabelFish('search');

$search = $_REQUEST['searchterm'];

/*
SELECT post_id, message, MATCH(feed.message) AGAINST ('crystal land') as Relevance FROM feed WHERE MATCH
(feed.message) AGAINST('+crystal +land' IN 
BOOLEAN MODE) HAVING Relevance > 0.2 ORDER 
BY Relevance DESC
*/

// User Search has first priority

$q = "select id, username, name,email from users where name like '%$search%' or users.`username` like '%$search%' or email like '%$search%'";
$r = get_query($q);

if (get_errors()) return "error";

echo "<div class='search-results'>";

if (get_rows($r) == 0) {
 	echo $babel->say('p_noresults_found');
}

while ($o = $r->fetch_object()) {
	$name = $o->name;
	$username = $o->username;
	$image = get_avatar_image($username,'small');
?>


	<div class='search-item'>
		<span style='float:left'><img src='<?=$image?>'></span>
		<span style='margin-left:5px'><a href='/profile/<?=$username?>'><?=$name?></a></span><br>
		<span style='margin-left:5px'>Vancouver BC Canada,</span><br>
		<span style='margin-left:5px'>0 Friends in common</span>
		

	</div>

	<div style='height:3px' class='clearfloat'></div>

<?php

}

?>
</div>