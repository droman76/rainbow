<?php include($CONFIG->home . 'lib/cronos.php');?>
<?php


function getCountFeed($view,$where,$master_view='') {
	$city = $_SESSION['city'];
	$region = $_SESSION['region'];
	$country = $_SESSION['country'];
	$continent = $_SESSION['continent_code'];
	if ($master_view != '')
		$scope = getScope($master_view);
	else $scope = getScope($view);

	$tagclause = '';
	
	if ($where != '') $where = $where .' AND ';
	$space = "and (continent_code = '$continent' OR country = '$country' OR region = '$region' OR city = '$city')";

	if (strlen($view)==1) {
		$scope = '';
		$tagclause = "$view = 'x'";
		$space = '';
	}


	$query = "select count(post_id) as postcount from feed where parent = '-1' and $where $tagclause $scope $space";
	//if (strlen($view)==1) ilog ($query);
	$result = get_query($query);
	$i = $result->fetch_array();
	return $i[0];

	
	

}

function getPostView($id) {
	$query = "select view from feed where post_id = $id";
	$r = get_query($query);
	return $r->fetch_object()->view;

}

function displayPost($post_id,$view) {

	$query = "select * from feed where post_id = $post_id";
	$result = get_query($query);

	return feedIterator($result,$view,0,1);
	
}


function displayUserFeed($start,$size,$userid){

	global $CONFIG;
	//$query = "select * from feed where parent = '-1' and user_name='$username' order by date DESC limit $start,$size";
	$query = "select * from feed, wall where parent = '-1' and feed.post_id = wall.post_id and wall.user_id = $userid  order by date DESC limit $start,$size";
	$result = get_query($query);
	$check = mysqli_num_rows($result);
	if ($check === 0 ){// no items return empty template	
		return false;
	}

	return feedIterator($result,'userview',$start,$size,$CONFIG->home."page/profile/profile_feed_item.php");

}

function displayGroupFeed($start,$size,$group,$from='') {
	global $CONFIG;
	$query = "select * from feed where group_id = '$group' and parent = '-1' $from order by date DESC limit $start,$size";
	$result = get_query($query);
	return feedIterator($result,$group,$start,$size);


}


function displayFeed($start,$size,$view,$where='',$onlycount=false,$scope=''){
	if ($scope == '')
		$scope = getScope($view);

	if ($where != '') $where = $where .' AND ';
	
	if ($view == 'world') $space = '';

	$query = "select * from feed where $where parent = '-1' and $scope order by date DESC limit $start,$size";
	$result = get_query($query);
	//if ($where =='' && strlen($view) > 1)
	//	ilog("view: $view query: $query");

	$check = mysqli_num_rows($result);
	if ($onlycount === true) {
		//elog("returning $check for view $view");
		return $check;
	}
	if ($check === 0 ){// no items return empty template
		
		return false;
	}

	return feedIterator($result,$view,$start,$size);
}

function displayTagFeed($start,$size,$tag) {
	$city = $_SESSION['city'];
	$region = $_SESSION['region'];
	$country = $_SESSION['country'];
	$continent = $_SESSION['continent_code'];
	global $babel;

	$in = $babel->say('p_in',false);
	$inthe = $babel->say('p_inthe',false);

	$space = "(continent_code = '$continent' OR country = '$country' OR region = '$region' OR city = '$city')";

	// Get by city first
	$query = "select * from feed where parent = '-1' and $tag = 'x' and city = '$city' order by date DESC limit $start,$size";
	$result = get_query($query);
	if (get_rows($result) > 0) {
		echo "<h3>In ".$babel->say($city).":</h3>";
		feedIterator($result,$tag,$start,$size);
	}
	// Then region
	$query = "select * from feed where parent = '-1' and $tag = 'x' and city != '$city' and region = '$region' order by date DESC limit $start,$size";
	$result = get_query($query);
	if (get_rows($result) > 0) {
		echo "<h3>$in ".$babel->say($region).":</h3>";
		feedIterator($result,$tag,$start,$size);
	}
	// Then country
	$query = "select * from feed where parent = '-1' and $tag = 'x' and city != '$city' and region != '$region' and country='$country' order by date DESC limit $start,$size";
	$result = get_query($query);
	if (get_rows($result) > 0) {
	echo "<h3>$in ".$babel->say($country).":</h3>";
		feedIterator($result,$tag,$start,$size);
	}
	// Then continent
	$query = "select * from feed where parent = '-1' and $tag = 'x' and city != '$city' and region != '$region' and country!='$country' and continent_code = '$continent' order by date DESC limit $start,$size";
	$result = get_query($query);
	
	if (get_rows($result) > 0) {
		echo "<h3>$in ".$babel->say($continent).":</h3>";
		feedIterator($result,$tag,$start,$size);
	}
	// then World
	$query = "select * from feed where parent = '-1' and $tag = 'x' and city != '$city' and region != '$region' and country!='$country' and continent_code != '$continent' order by date DESC limit $start,$size";
	$result = get_query($query);
	if (get_rows($result) > 0) {
		echo "<h3>$inthe ".$babel->say("p_world").":</h3>";
		feedIterator($result,$tag,$start,$size);
	}

	

}

function feedIterator($result,$view,$start,$size,$processor='') {

global $CONFIG;
global $t_home;
$babel = new BabelFish('feed');
$city = $_SESSION['city'];
$region = $_SESSION['region'];
$country = $_SESSION['country'];
$continent = $_SESSION['continent_code'];

$last_viewed = cronos_get($view);
$post_count = $start;
$trigger = false;
$me_id = get_logged_in_user_id();


$my_pic_url = '';
$extinfo = $CONFIG->userdata .$_SESSION['username'].'/extinfo';

if (file_exists($extinfo)){
	$hr = fopen($extinfo,'r');
	$image_extension = fread($hr, filesize($extinfo));
	fclose($hr);
	$my_pic_url = $CONFIG->site . '/myimages/'.$_SESSION['username'] .'/small_'.$_SESSION['username'].$image_extension;

}
else $my_pic_url = $CONFIG->site.'/template/default/images/defaultsmall.gif';

$count = get_rows($result);

while($row = $result->fetch_object()){
		$data = "";
		$attachment_html = '';
		$post_count++;
		$post_id = $row->post_id;
		$user_name = $row->user_full_name;
		$user_login_id = $row->user_name;
		$user_id = $row->user_id;
		$message = $row->message;
		// replace new lines with breakpoints
		$message = str_replace("\n", "<br>", $message);
		$action = $row->action;
		$action_object = $row->action_object;
		$pic_url = get_avatar_image($user_login_id,$size='small');
		//$post_date = date("F j, Y, g:i a",$row->date);
		$timestamp = $row->date;
		$post_date = ago($row->date). ' '.$babel->say('p_ago');
		$post_url = $row->post_url;
		$post_pic = $row->post_pic_url;
		$link_src = $row->link_src;
		$link_video = $row->link_video_src;
		$link_title = $row->link_title;
		$link_desc = $row->link_description;

		/*if (!empty($post_link)){
			error_log("EMBEDDING LINK: $post_link");
			$call = "https://api.embed.ly/1/oembed?key=2471bedfd0b54a9782969db008e8c8f7&maxwidth=300&url=".urlencode($post_link);
			$info = json_decode(file_get_contents($call));
			$attachment_html = $info->html;
		}
		*/

		$message = utf8_encode(nl2br($message));
		$source = $row->source;
		$source_id = $row->external_source_id;
		$source_name = $row->external_source_name;
		$country = $babel->say($row->country);
		$country_code = $row->country_code;
		$region = $babel->say($row->region);
		$city = $babel->say($row->city);

		$tags = $row->tags;
		$address = $row->address;
		$longitude = $row->longitude;
		$latitude = $row->latitude;
		$post_type = $row->post_type;
		$slug = '';
		$group = $row->group_id;
		$images = '';
		// replace the view with the right view if view is 'item' meaning we are looking at a single post only
		if ($view == 'item') {
			$view = $row->view;
		}


		// process pic url 
		if ($source == 'local' && $post_pic != ''){
			$images = explode("::", $post_pic);
			$post_pic = '';
		}
		// retrieve sharings
		$q = "select username,name,user_id from wall,users where wall.scope = 2 and wall.user_id = users.id and post_id = $post_id";
		$share = postaction($q,$me_id,'share',"sharePopup($post_id)");

		// retrieve followers
		$q = "select username,name,users.id as user_id from following,users where user_id_following = users.id and source_id = $post_id and source_obj = 'post'";
		$follow = postaction($q,$me_id,'follow',"followingPopup($post_id)");


		// retrieve the blessings number for the post
		$q = "select post_id, user_id, username, users.name from likes, users where post_id = $post_id and comment_id = -1 and users.id = user_id";
		$r = get_query($q);
		// TODO: optimize this.. perhaps move out of loop into batch?
		$blessings = 0;
		$you = false;
		$namestring = '';
		while($or = $r->fetch_object()){
			$un = $or->username;
			$n = $or->name;
			$i = $or->user_id;
			if ($i == $me_id) $you = true;
			if ($blessings < 3 && $i != $me_id)
				$namestring .= "<a href='/profile/$un'>$n</a> ";
			$blessings++;
		}
		$btext = '';

		if ($you && $blessings == 1) $btext = $babel->say('p_you') .' '. $babel->say('p_blessed_this');
		else if (!$you && $blessings == 1) $btext = $namestring .' '. $babel->say('p_blessed_this_only');
		else if ($you && $blessings > 1 && $blessings < 3) $btext = $babel->say('p_you') .' '.$babel->say('p_and') .' '. $namestring . ' '. $babel->say('p_blessed_this');
		else if ($you && $blessings >= 3) $btext = $babel->say('p_you') .' '. $babel->say('p_and') .' '. ($blessings-1) . ' '. $babel->say('p_people') . ' '. $babel->say('p_blessed_this');
		else if (!$you && $blessings > 1 && $blessings < 3) $btext = $namestring . ' '. $babel->say('p_blessed_this');
		else if (!$you && $blessings >= 3) $btext = $blessings . ' '. $babel->say('p_people') . ' '. $babel->say('p_blessed_this');

//		ilog("Got $blessings blessings! for post $post_id");
		// This is to check if the current logged in user blessed this post
		
		// create slug of 1000 characters
		if (strlen($message) > 1000){
			$slug = strip_tags($message);			
    		// truncate string
    		$slug = substr($slug, 0, 1000);

    		// make sure it ends in a word so assassinate doesn't become ass...
    		$slug = substr($slug, 0, strrpos($slug, ' ')).'…';
    			
			// add readmore link				
			$slug = "<div id='read_$post_id'>".nl2br($slug)."<br><div id='readmore_$post_id'><a onclick=\"document.getElementById('readmore_$post_id').style.display='none';document.getElementById('read_$post_id').style.display='none';;document.getElementById('full_$post_id').style.display='inline'\">Read More...</a></div></div>";
			// append full message but hidden
			//$slug .= "<div style='display:none' id='full_$post_id'>$total_message</div>";
			
		}

		if (strlen($post_pic) > 0 && $post_type != 'video' && source != 'local'){
			$message = "<div style='float:left;margin:5px'><img src='$post_pic' ></div>".nl2br($message);
				// add attachments
			$message .= $attachment_html;
		}

		$location = $babel->say('p_from');
		$ago = ago($row->date) . ' '.$babel->say('p_ago');
		if ((isset($row->country) && isset($row->region) && isset($row->city) && $row->region != '') && ($country_code == 'US' || $country_code =='CA')) $location .= " $city, $region $country";
		else if (isset($row->country) && isset($row->city) && $row->city != '') $location .= " $city, $country";
		else if (isset($row->country) && isset($row->region) && $row->region != '') $location .= " $region $country";
		else if (isset($row->country)) $location .= " $country";

		// prints the feed item
		if ($view == 'userview')
			include($CONFIG->home.'page/profile/profile_feed_item.php');
		else if ($processor == '')include('feed_item.php');
		else include($processor);
	 // if already displayerd over 2/3 of posts add the ajax trigger
	if ($post_count > (($size*2)/3) && $trigger == false) {
		echo "<span id='feeder_$view' style='height:0px'></span>";
			$trigger = true;
	}


	} // end while loop	
	return true;
}

function getNewComments($view,$time,$master_view) {
	
	if (strlen($view)==1) $xview = $master_view;
	else $xview=$view;

	$scopein = getScope($xview);
	$city = $_SESSION['city'];
	$region = $_SESSION['region'];
	$country = $_SESSION['country'];
	$continent = $_SESSION['continent_code'];
	$user_id = $_SESSION['userid'];

	$space = "(continent_code = '$continent' OR country = '$country' OR region = '$region' OR city = 'city')";
	$query = "select * from feed_comments where date > $time and user_id != '$user_id'  order by date ASC";

	//ilog("Retrieving COMMENTS for $view: $query");

	$comment_data = get_query($query);
	return renderCommentList($comment_data,$view);
}




function displayComments($post_id,$view) {	
		$replies = 0;

		$comment_data = get_query("select * from feed_comments where post_id = '$post_id' order by date ASC");
		return renderCommentList($comment_data,$view);

}
function renderCommentList($data,$view){
		global $babel,$CONFIG;
		$replies = 0;
		$me_id = get_logged_in_user_id();

		while($crow = $data->fetch_object()){
			$pic_url = $crow->user_pic_url;
			$comment_id =$crow->comment_id; 
			$postid =$crow->post_id; 
			
			$user_name = $crow->user_name;
			$user_full_name = $crow->user_full_name;
			$cuser_id = $crow->user_id;
			$comment = $crow->message;
			$source = $crow->source;
			$timestamp = $crow->date;
			$ago = ago($crow->date) .' '.$babel->say('p_ago',false);
			$location = '';
			if ($source == 'local') {
				$pic_url = $CONFIG->site . '/myimages/'.$user_name .'/small_'.$user_name.$pic_url;
			}
			else {
				$pic_url = $CONFIG->site . '/myimages/'.$user_name .'/small_'.$user_name.'.jpg';
				
			}
			$country = $babel->say($crow->country);
			$country_code = $crow->country_code;
			$region = $babel->say($crow->region);
			$city = $babel->say($crow->city);
	
			// retrieve the number of likes
			$q = "select count(comment_id) as items from likes where post_id = $postid and comment_id = $comment_id";
			$r = get_query($q);
			$o = $r->fetch_object();
			$likes = $o->items;

			// determine if i liked this comment
			$you = false;
			$q = "select user_id from likes where post_id= $postid and comment_id = $comment_id and user_id = $me_id";
			$r = get_query($q);
			$c = get_rows($r);
			if ($c > 0) $you = true;


			$location = $babel->say('p_from',false);
			if (isset($crow->country) && isset($crow->region) && isset($crow->city) && ($country_code == 'US' || $country_code =='CA')) $location .= " $city, $region $country";
			else if (isset($crow->country) && isset($crow->region) && isset($crow->city)) $location .= " $city, $country";
			else if (isset($crow->country) && isset($crow->region) && ($country_code == 'CA' || $country_code = "US")) $location .= " $region $country";
			else if (isset($crow->country)) $location .= " $country";
			// prints the feed comment
			include('feed_comment.php');
			$replies++;
		}
		return $replies;


}


function getScope($view) {

$city = $_SESSION['city'];
$region = $_SESSION['region'];
$country_code = $_SESSION['country_code'];
$country = $_SESSION['country'];
$continent = $_SESSION['continent_code'];
	

switch ($view) {
	// Show all world posts and continent post except for my continent
	case 'world':
		$scope = "((view = 'world') OR ((continent_code !='$continent' AND region !='$region' AND country_code != '$country_code' AND city !='$city') and (view = 'world' or view = 'continent' or view = 'country' or view = 'region' or view = 'city')))";
		break;
	case 'continent':
		// Show all posts for my continent and for the countries within my continent except for my country
		$scope = "continent_code = '$continent' AND (region !='$region' AND country_code != '$country_code' AND city !='$city') and (view = 'continent' or view = 'country' or view = 'region' or view = 'city')";
		break;
	case 'country':
		// If it is USA or CANADA then add regions otherwise add cities
		if ($country_code == "US" || $country_code == "CA") {
			// Show all posts for my country and all posts for regions within my country (except the ones i am in)
			$scope = "country_code = '$country_code' AND (region !='$region' AND city !='$city') and (view = 'country' or view = 'region' or view = 'city')";
		} else { 
			// Show all posts for my country and all posts for cities within my country (except the ones i am in)
			$scope = "country_code = '$country_code' AND (city !='$city') and (view = 'country' or view = 'city')";
		}
		break;
		// Show all post for my region and all cities in the region except my city	
	case 'region':
		$scope = "region = '$region' AND (city !='$city') and (view = 'country' or view = 'region' or view = 'city')";
		break;
		// Show all posts for my city
	case 'city':
		$scope = "(city = '$city' and country = '$country') ";
		break;
	default:
		$scope = "(view = '$view')";
		break;
	}

return $scope;

}
?>