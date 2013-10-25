<br>
<div style="margin:auto; width:700px">
<?php

if (!isset($babel)) {
	$babel = new BabelFish('denied');
}

	if (!isset($_SESSION['userid']) || $_SESSION['userid'] == ''){
		echo "<h1>".$babel->say('p_sessionexpired_title')."</h1>";
		echo "<p><br>";
		echo "<h3>".$babel->say('p_sessionexpired_body')."</h3>";
		echo "<br>".$babel->say('p_not_logged_in');
		echo "<br><br><a href=\"$CONFIG->site\">".$babel->say('p_back_to_login')."</a>";

	}
	else {
		echo "<h1>".$babel->say('p_denied_title')."</h1>";
		echo "<p><br>";
		echo "<h3>".$babel->say('p_denied_body')."</h3>";
	
		echo "<br>".$babel->say('p_access_denied');
		echo "<br><br><a href=\"$CONFIG->site\">".$babel->say('p_back_to_login')."</a>";

	}





?>
</div>
</br>