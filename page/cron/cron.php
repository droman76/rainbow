<h1>Cron Process</h1>
<br>
<?php

include('PostProcessor.php');
include($_SESSION['home'].'lib/simpleimage.php'); 
include($_SESSION['home'].'lib/image_resize.php'); 

cron_import_external_posts();




function cron_import_external_posts(){

	global $CONFIG;

	$lock_file = $CONFIG->data."newcronlock.lck";
   	ilog("CRON PROCESS IN PROGRESS…");
   	
   	// locking
   	if (file_exists($lock_file)){
   		unlink($lock_file); //remove lock
   		echo "Lock Removed. Refresh to start Cron Process...";
   		return true;
   	}
   	else {
   		$data = "Lock created at ".time();
   		// create lock file and proceed
   		$handle = fopen($lock_file, 'w') or die('Cannot open file:  '.$progress_file); 
		fwrite($handle, $lockdata);
		fclose($handle);
   	}
    
	//filter file clearing
	$filter_file = $CONFIG->data."filtered.txt";	
	$handle = fopen($filter_file, 'w') or die('Cannot open file:  '.$progress_file);
	fwrite($handle,'Filter Messages From Import. Time: '.date("F j, Y, g:i a").'\n\n');
	fclose($handle);
	
	//get admin token
	$progress_file = $CONFIG->data.'admintoken.txt';
	$handle = fopen($progress_file, 'r') or $error=1;
	//if ($error==1)return; 
	$admin_token = fread($handle, filesize($progress_file));
	fclose($handle);

	if (empty($admin_token) || $error ==1){
		echo ("ADMIN TOKEN NOT RETRIEVED. FAILED TO OPEN FILE");
		elog("Admin token could not be retrived from file. Exiting!");
		return;
	}

	// rainbow family google + group
	//$result = import_google_group_data("106862416135373438613");
	// rainbow family to canada
	//$result = import_facebook_group_data("369212259844415",'canada caravan',$admin_token);
	// rainbow family of facebook
	
	$result = import_facebook_group_data("2230889765",'RAINBOW FAMILY OF FACEBOOK',$admin_token);
	
	//$result = import_facebook_group_data("161762407215252",'Rainbow Family Alt',$admin_token);
	//$result = import_facebook_group_data("2572870730",'Rainbow Family Gathering',$admin_token);
	//$result = import_facebook_group_data("7546830131",'Next Rainbow Gatherings',$admin_token);
	//$result = import_facebook_group_data("314732201952201",'bc-rainbow-family',$admin_token);
	

	//$result = import_facebook_group_data("600808499935011",'canada-gathering',$admin_token);
	//$result = import_facebook_group_data("274475285976469",'community-page',$admin_token);
	//$result = import_facebook_group_data("184801984893721",'usa-rainbow-family',$admin_token);
	//rideshare board
	//$result = import_facebook_group_data("55972693514",'gmo-page',$admin_token,'private','off');  
	//$result =	 import_facebook_group_data("120017848059935",'rideshare',$admin_token,'private','off');
	//$result =	 import_facebook_group_data("152360988275434",'sacred-heart tribe',$admin_token);
	error_log("CRON PROCESS COMPLETE!!");
	
   	
}	

function log_filter($data) {
//filter file clearing
	$filter_file = elgg_get_data_path ().'/'."filtered.txt";	
	$handle = fopen($filter_file, 'a') or die('Cannot open file:  '.$progress_file);
	fwrite($handle,$data);
	fclose($handle);

}
/*
function import_google_group_data($groupid){
	$group_url = "https://www.googleapis.com/plus/v1/people/$groupid/activities/public?key=AIzaSyDQfz6uWmbgNp8KnhccMMbhGGcQGXIuZkk";
	//error_log($group_url);
	$result = false;
	$feed = json_decode(file_get_contents($group_url));
	$postcount = 0;
	$replycount = 0;
	$filtered = '';
	foreach ($feed->items as $post){
			
			$processor = new PostProcessor($groupid);
				
			if ($processor->processGooglePlusPost($post)){
				$result =  $processor->save();	
				//error_log("SAVE RETURNED VALUE $result");
				if ($result == 0) $postcount++;
				
			}
			else {
				//message was filtered write to filter file
				
				$filtered .= "SOURCE GOOGLE $groupid: ".$processor->message."\n";
				
			
			}
			$replycount += $processor->replies;
						
	}
	//error_log("CRON: PROCESSED $postcount POSTS AND $replycount COMMENTS FROM GOOGLE GROUP $groupid AT ".gmdate("Y-m-d\TH:i:s\Z", time()));
	log_filter($filtered);
	return $result;
	
}
*/


function import_facebook_group_data($groupid,$groupname,$admin_token,$scope='public',$filter='on'){
	
//now read the group!!
	$group_url = "https://graph.facebook.com/$groupid/feed?access_token=" 
           . $admin_token;
    echo "<a href='$group_url'>Processing group...</a><br>";       
	$feed = json_decode(file_get_contents($group_url));
	ilog('REQUESTING: '.$group_url);
	$postcount = 0;
	$filtered;
	foreach ($feed->data as $post) {
		$processor = new PostProcessor($groupid,$groupname,$scope);	
		$processor->token = $admin_token;
		$processor->filter = $filter;
		if ($processor->processFacebookPost($post)){
			//$result =  $processor->save();
			$postcount++;			
		}
		else {
			$filtered .= "SOURCE FACEBOOK $groupid: ".$processor->message."\n";
			
		}		
		$replycount += $processor->replies;
	}
	ilog("CRON: PROCESSED $postcount POSTS AND $replycount COMMENTS FROM FACEBOOK GROUP $groupname ($groupid) AT ".gmdate("Y-m-d\TH:i:s\Z", time()));
	echo("CRON: PROCESSED $postcount POSTS AND $replycount COMMENTS FROM FACEBOOK GROUP $groupname ($groupid) AT ".gmdate("Y-m-d\TH:i:s\Z", time()));
	echo "<br>";
	echo "Filtered: <pre>".$filtered."</pre>";
	//log_filter($filtered);
	
	return $result;
}

function cron_log ($data){
  $filter_file = '/home/rainbow/'."cron.log";  
  $handle = fopen($filter_file, 'a') or die('Cannot open file:  '.$progress_file);
  fwrite($handle,$data.'\n');
  fclose($handle);

}

?>

