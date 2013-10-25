<?php


class PostProcessor {

	public $source_id;
	public $source_name;
	public $local_postid;
	public $post_id;
	public $post_url;
	public $user_id;
	public $user_name;
	public $user_url;
	public $pic_url;
	public $post_link;
	public $video_src;
	public $link_title;
	public $link_description;
	public $post_pic_url;
	public $post_type;
	public $message;
	public $post_date;
	public $source;
	public $postparent;
	public $replies;
	public $scope;
	public $filter;
	public $token;
	public $iscomment;
	
	public function __construct($source_id,$source_name = '',$scope='public') {
		$this->source_id = $source_id;
		$this->source_name = $source_name;
		$this->scope = $scope;
		$this->filter = 'on';
		$this->iscomment = false;
	}

	/*
	Public function to process a post recieved from google plus.
	Returns true if processing successfull, false if not.
	
	*/

	public function processGooglePlusPost($post,$parent='-1') {
		if ($post->verb != 'post')return false;
		
		$this->message = escape($post->object->content);
		$this->user_name = escape($post->actor->displayName);
		$this->post_id = $post->id;
		$this->post_url = $post->url;
		$this->user_url = $post->actor->url;		
		$this->source = 'google';
		$this->user_id= $post->actor->id;
		$this->pic_url = $post->actor->image->url;
		$this->postparent = $parent;
		// format 2013-05-11T23:22:21.572Z to convert to int...
		$this->post_date = strtotime($post->updated);
		 
		
		//error_log("POST PROCESSED parsed time is: ". gmdate("Y-m-d\TH:i:s\Z", $this->post_date) . " Original: $post->updated");
		
		if ($this->message == '' ) return false;
			
		
		if( $parent != '-1' || $this->filterMessage()){
			$reply_url = $post->object->replies->selfLink . "?key=AIzaSyDQfz6uWmbgNp8KnhccMMbhGGcQGXIuZkk";
			$replycount = $post->object->replies->totalItems;
			$this->replies = $replycount;
			if ($replycount > 0){
				// process replies…
				//error_log("POST $this->post_id has $replycount replies!! PROCESSING…");
				$feed = json_decode(file_get_contents($reply_url));
				foreach ($feed->items as $postcomment){
					$processor = new PostProcessor($this->source_id);	
					if ($processor->processGooglePlusPost($postcomment,$this->post_id)){
						$result =  $processor->save();	
					}
				}
				
			}
						
			return true;

		}
		else {
			return false;
		
		}
		
			
		
		
		return false;	
	
	}
	
	public function processFacebookPost($post,$parent='-1'){
		
		$this->post_id = $post->id;
		$this->user_name = escape($post->from->name);
		$this->user_id = $post->from->id;
		$this->user_url = "http://www.facebook.com/".$this->user_id;
		$this->message = escape($post->message);
		$this->post_url = $post->actions[0]->link; 
		$attachment_link = $post->link;
		$this->pic_url = "https://graph.facebook.com/$this->user_id/picture";
		$this->post_pic_url = $post->picture;
		$this->post_type = $post->type;

		if ($post->type == 'video' || $post->type == 'link'){
		
			$this->post_link = $post->link;
			$this->video_src = $post->source;
			$this->link_title = escape($post->name);
			$this->link_description = escape($post->description);

		}


		$this->postparent = $parent;
		$this->source = 'facebook';
		if ($parent == '-1')
			$this->post_date = strtotime($post->updated_time);
		else 
			$this->post_date = strtotime($post->created_time);
		//error_log("POST PROCESSED parsed time is: ". gmdate("Y-m-d\TH:i:s\Z", $this->post_date) . " Original: $post->updated_time");
		$filter = '';
		$comments = $post->comments;
		if ($this->filterMessage() == false && $parent == '-1'){
			
			return false;
		}
		// attach attachment link if any
		if (!empty($attachment_link)) $this->message .= escape("<br><a href='$attachment_link' target='_new'>$attachment_link</a>");
		$replies = 0;
		$this->save();

		if (!empty($comments->data)){
		foreach ($comments->data as $comment){
			$processor = new PostProcessor($this->source_id,$this->source_name,$this->scope);	
			$processor->filter = $this->filter;
			$processor->token = $this->token;
			$processor->iscomment = true;
			$processor->local_postid = $this->local_postid;	


			if($processor->processFacebookPost($comment,$this->post_id)){
				$processor->save();
			}
			$replies++;
			
		}
		}
		$this->replies = $replies;
		return true;	
	}
	
	public function filterMessage() {
		if ($this->filter == 'off')return true;
		if ($this->post_type == 'video') return true;
		if ($this->post_type == 'link') return true;
		
		// filter message to only key words
		$pos = 0;
		$pos += stripos($this->message,"rainbow");
		$pos += stripos($this->message,"gathering");
		$pos += stripos($this->message,"montana");
		$pos += stripos($this->message,"family");
		$pos += stripos($this->message,"kitchen");
		$pos += stripos($this->message,"ride");
		$pos += stripos($this->message,"caravan");
		$pos += stripos($this->message,"montana");
		$pos += stripos($this->message,"free");
		$pos += stripos($this->message,"help");
		$pos += stripos($this->message,"festival");
		$pos += stripos($this->message,"vision");
		$pos += stripos($this->message,"council");
		$pos += stripos($this->message,"scout");
		$pos += stripos($this->message,"events");
		$pos += stripos($this->message,"youtube");
		$pos += stripos($this->message,"going");
		$pos += stripos($this->message,"facebook");
		
		$pos += stripos($this->message,"garden");
		
		$pos += stripos($this->message,"map");
		$pos += stripos($this->message,"direction");
		
		$pos += stripos($this->message,"heading");
		$pos += stripos($this->message,"land");
		
		 	
		if ($pos == 0 ) return false;
		return true;
	
	}


	public function save() {

		global $CONFIG;

		$external_userid = $this->user_id;
		$external_post_id = $this->post_id;

		// first thing, check this post doesn't already exist!
		$q = "select user_id from feed where external_post_id = '".$external_post_id."'";
		$r = get_query($q);
		$c = get_rows($r);
		if ($c > 0) {
			ilog("Post $external_post_id already exists! Skipping...");
			echo("Post $external_post_id already exists! Skipping...<br>");
			return true;
				
		}

		
		//okay lets find out if this user exists in our user table (external id)
		$q = "select id,username,name from users where extid = '".$external_userid."'";
		$r = get_query($q);
		$count = get_rows($r);
		if ($u = $r->fetch_object()){
			$user_id = $u->id;
			$user_name = $u->username;
			$name = $u->name;
			//ilog("User $name found in database.. Loading it up!");
			
		}
		else {
			// retrieve user information from facebook
			$graph_url = "https://graph.facebook.com/".$external_userid."?access_token=" 
   . $this->token;
  
			$user = json_decode(file_get_contents($graph_url));

			$graph_url = "https://graph.facebook.com/".$external_userid."?access_token=" 
			   . $this->token. "&fields=cover,picture";
			$picdata = json_decode(file_get_contents($graph_url));
			 
			$username = $user->username;
			$name = $user->name;
			$gender = $user->gender;

			$cover_pic = $picdata->cover->source;
			$user_pic = $picdata->picture->data->url;

			$userdir = $CONFIG->data.'proxies/'.$username;

			// create user entry
			$q = "insert into users (username,name,gender,activated,email,password,extid,proxy) values";
			$q .= "('$username','".db_escape($name)."','$gender','0','','','$external_userid',1)";
			
			ilog($q);
			if(!get_query($q)) {
				elog("Error!! User $username not Created from facebook cron process. Exit!");
				return;
			}
			$user_id = get_insert_id();



			if (!file_exists($userdir)) {
				ilog('creating user directory at '.$userdir);
				if (!mkdir($userdir, 0775,true)){
					elog("Could not create user directory for user $u. Ending program!");
					echo("There was an error creating user data directory. Contact focalizer");	
					exit(1);	
				}
			}
			// create the images
			$image = new SimpleImage();
			ilog("Saving Image url: https://graph.facebook.com/".$external_userid."/picture?width=600");

			$image->load("https://graph.facebook.com/".$external_userid."/picture?width=600");

			$image->save($userdir."/full_".$username.'.jpg');

			$resizer = new resize($userdir."/full_".$username.'.jpg');
			$resizer->resizeImage(150, 150, 'crop');  
			$resizer->saveImage($userdir."/medium_".$username.'.jpg', 70);  
			$resizer->resizeImage(50, 50, 'crop');  
			$resizer->saveImage($userdir."/small_".$username.'.jpg', 70);  
				    	
			$image->load($cover_pic);
			
			$image->save($userdir."/cover-master_".'.jpg');
			$resizer = new resize($userdir."/cover-master_".'.jpg');
			$resizer->resizeImage(900, 300,'crop');  
			$resizer->saveImage($userdir."/cover_".'.jpg', 70);  

			$image_extension=".".$file_ext;
			$extfile = $userdir.'/extinfo';
			$h = fopen($extfile, 'w');
			fwrite($h, ".jpg");
			fclose($h);


			
		}


		$external_source_id = $this->source_id;
		$external_source_name = $this->source_name;

		$now = time();
		$source = $this->source;
		
		$tags = '';
		$post_url = '';
		// integrate pic here..
		$post_pic_url = '';
		$post_type = $this->post_type;
		$link_src = $this->post_link;
		$link_title = $this->link_title;
		$link_desc = $this->link_desc;
		$link_video = $this->video_src;
		$source = $this->source;
		$parent = $this->postparent;
		$scope = '1'; // insert for world view
		$continent = 'na';
		$countrycode = 'na';
		$country = 'na';
		$region = 'na';
		$city = 'na';
		$longitude = 'na';
		$latitude = 'na';
		$message = $this->message;
		$local_postid = $this->local_postid;
		
		//echo "<h3>$username </h3><br>";
		
		
		//error_log("EXECUTING SQL: <$sql>");
		if ($this->iscomment) {
			$sql = "INSERT INTO `feed_comments` (`post_id`,`user_id`, `user_name`,`user_full_name`, `user_profile_url`,"; 
			$sql.= "`user_pic_url`, `message`, `link_src`,"; 
			$sql.= "`link_title`, `link_description`, `link_video_src`, `source`, `date`, `scope`,"; 
			$sql.= "`continent_code`, `country_code`, `country`, `region`, `city`, `longitude`, `latitude`,`last_update`)"; 
			$sql.= "VALUES ('$local_postid','$user_id','$user_name','".$name."', '', '', '".$message."',"; 
			$sql.= "'$link_src', '$link_title', '".$link_desc."', '$link_video', '$source',";
			$sql.= "'$now',"; 
			$sql.= "'$scope', '$continent', '$countrycode', '$country', '$region', '$city',"; 
			$sql.= "'$longitude', '$latitude','$now')";
			
		}
		else {
			$sql = "INSERT INTO `feed` (`external_post_id`,`external_userid`,`external_source_id`,`external_source_name`,`user_id`, `user_name`,`user_full_name`, `user_profile_url`,"; 
			$sql.= "`user_pic_url`, `message`, `tags`, `post_url`, `post_pic_url`, `post_type`, `link_src`,"; 
			$sql.= "`link_title`, `link_description`, `link_video_src`, `source`, `date`, `parent`, `scope`,"; 
			$sql.= "`continent_code`, `country_code`, `country`, `region`, `city`, `longitude`, `latitude`)"; 
			$sql.= "VALUES ('$external_post_id','$external_userid','$external_source_id','$external_source_name','$user_id', '$user_name','".$name."', '', '', '".$message."', '$tags', '$post_url',"; 
			$sql.= "'$post_pic_url', '$post_type', '$link_src', '$link_title', '".$link_desc."', '$link_video', '$source',";
			$sql.= "'$now', '-1',"; 
			$sql.= "'$scope', '$continent', '$countrycode', '$country', '$region', '$city',"; 
			$sql.= "'$longitude', '$latitude')";
	

		
		}

		

		echo "<br><br>";
		get_query($sql);
		$this->local_postid = get_insert_id();
		$post_id = $this->local_postid;
		$sql2 = "INSERT INTO wall (post_id,user_id,scope) VALUES ($post_id,$user_id,1)";
		get_query($sql2);

		

	}
}

function escape ($input) {
		return str_replace(array("\\","\'", '\"', '"', "'"), array('/',"\'\'", '\\"', '\"', "\'"), $input);
		
	
}


?>
