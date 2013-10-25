<?php


function notify($from, $object_name,$object_id,$action,$note='') {

	$time = time();
	// Determine who is following this post...
	$sql = "select * from following where source_id = $object_id and source_obj = '$object_name'";
	ilog($sql);
	$r = get_query($sql);
	if (!get_errors()){
		while ($follow = $r->fetch_object()){
			$follower = $follow->user_id_following;
			
			// now add an entry on notifications for this follower
			if ($follower != $from) {
				$q = "insert into notifications (recipient,sender,target,object,action,note,sent) values ";
				$q .= "($follower,$from,$object_id,'$object_name','$action','$note',$time)";
				get_query($q);
			}
			if (get_errors()) {
				echo "DB Error while adding notification!. Contact focalizer!";
				exit(1);
			}
		}
	}

	// figure out the object owner
	switch ($object_name) {
		case 'post':
			$q = "select user_id from feed where post_id = $object_id";
			$to = get_query($q)->fetch_object()->user_id;
			// If blessing add good karma to the post owner
			if ($action == 'bless') add_good_karma($to);
			break;
		case 'comment':

			// figure out the post id and then the user id
			
			$q = "select post_id,user_id, message from feed_comments where comment_id = $object_id";
			$c = get_query($q)->fetch_object();
			$post_id = $c->post_id;
			$commentactor = $c->user_id;
			$commenttxt = $c->message;

			$q = "select user_id,message from feed where post_id = $post_id";
			ilog ($q);
			$r = get_query($q)->fetch_object();

			$to = $r->user_id;
			$posttxt = $r->message;

			$content = comment_preparecontent($posttxt,$commenttxt,$to,$commentactor);

			/*
			// Now from this post id find out all the other people commenting and notify them!
			$q = "select user_id from feed_comments where post_id = $post_id and (user_id != $to or user_id != $from)";
			$r = get_query($q);
			while ($o = $r->fetch_object()){
				$xcommentactor = $c->user_id;
				$q = "insert into notifications (recipient,sender,target,object,action,note,sent) values ";
				$q .= "($xcommentactor,$from,$object_id,'$object_name','commentthread','$note',$time)";
				get_query($q);
			}
			*/


			// send the user an email
			//send_mail($to,'subject_newcomment','message_newcomment',$content,$action);
			break;

		case 'user':
			$to = $from;
			$from = $object_id;
			if ($action = 'friends') {
				// Send mail notification
				$content = friendaccept_preparecontent($to,$from);
				send_mail($to,'subject_frindshipaccepted','frindship_hasbeenaccepted',$content,$action);

			}
			
			
			break;	
		
		default:
			# code...
			break;
	}

	// Ok now notify the object owner of the action unless it is me to me
	if ($to != $from) {
		$q = "insert into notifications (recipient,sender,target,object,action,note,sent) values ";
		$q .= "($to,$from,$object_id,'$object_name','$action','$note',$time)";
		get_query($q);
	}
			

	
}
function friendaccept_preparecontent($to,$from) {
	$babel = new BabelFish('mailer');
	
	$q = "select name from users where id = $from";
	ilog($q);
	$name2 = get_query($q)->fetch_object()->name;
	$html = "<div style='background-color:#e6f3fa;margin:20px;padding:20px;border-width:5px;border-style:groove;border-color:#60beef'>";
	$html = "$name2 ". $babel->say('p_hasacceptedyourfriendrequest',false);
	//$html .= substr($commenttxt, 0,100);
	$html .= "</div>";
	return $html;	

}

function comment_preparecontent($posttxt,$commenttxt,$postactor,$commentactor) {

	// Get the name of the post actor
	/*
	$q = "select name from users where id = $postactor";
	ilog($q);
	$name = get_query($q)->fetch_object()->name;
	*/
	// Get the name of the comment actor
	$babel = new BabelFish('mailer');
	
	$q = "select name from users where id = $commentactor";
	ilog($q);
	$name2 = get_query($q)->fetch_object()->name;

	$html = "<div style='background-color:#e6f3fa;margin:20px;padding:20px;border-width:5px;border-style:groove;border-color:#60beef'>";
	$html .= "<span style='font-size:14px;color:darkblue'>".$babel->say('p_yourpost',false)."</span><br>";
	//$html .= substr($posttxt, 0,100);
	$html .= $posttxt;
	$html .= "<br><br>";
	$html .= "<span style='font-size:14px;color:darkblue'>".$name2."'s ".$babel->say('p_ucomment',false)."</span><br>";
	$html .= $commenttxt;
	//$html .= substr($commenttxt, 0,100);
	$html .= "</div>";

	return $html;

}

function send_mail($to,$subject_code,$message_code,$message_content,$action) {

	global $CONFIG;

	$babel = new BabelFish('mailer');
	$subject = $babel->say($subject_code,false);
	$message = $babel->say($message_code,false);

	// Get the email and name for this user
	$q = "select name,email from users where id = $to";
	$r = get_query($q);
	if ($o = $r->fetch_object()) {

		$name = $o->name;
		$names = explode(' ', $name);
		$firstname = $names[0];
		$to_email = $o->email;
	
		// Email the user their activation link
		$from = $CONFIG->site_mail;

		ilog("Sending MAIL notification to $name @ $to_email on action $action");
	

		$html = '<body style="margin:5px; font-family:Tahoma, Geneva, sans-serif;">';
		$html .= $babel->say('p_greet',false) . " $firstname! <br><br>";
		$html .= $message;
		$html .= '<br>';
		$html .= $message_content;
		$html .= $babel->say('p_cometosite',false) . ' <a href="'.$CONFIG->site.'">'.$CONFIG->site_name.'</a> ';
		$html .= $babel->say('p_afterlink',false) . "<br><br>";
		$html .= $babel->say('p_signature',false); 
		$html .= '</body>';


		$headers = "From: ".$CONFIG->site_name." <$from>\n";
	    $headers .= "MIME-Version: 1.0\n";
	    $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		mail($to_email, $subject, $html, $headers);

	}

}





?>