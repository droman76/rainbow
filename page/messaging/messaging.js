
var profileloader;
var mailman = false; // mail man is the ajax process that checks for new messages
var loadtime; // the time the conversation loaded (for the ajax process to know)


function notification_goto(link) {
	_("notification-inbox").style.display='none';	
	location.href = link;

}

function loadProfile(e,target,left,top,userid,pview) {
		mousein = true;
		clearTimeout(profileloader);

		profileloader = setTimeout(function() {
				loadProfileX(e,target,left,top,userid,pview);
		}, 500);	
}

function loadProfileX(e,target,left,top,userid,pview) {
		if (mousein == false) return;

		var ajax = ajaxObj("POST", "/action/profile/profile_popup.php");
		showBox(e,target,left,top,'');
			
		ajax.onreadystatechange = function() {
		    if(ajaxReturn(ajax) == true) {
		        if(ajax.responseText == "error"){        	
				} else {
					newdata = ajax.responseText;
					showBox(e,target,left,top,newdata);
					//usercache[userid+pview] = newdata;
				}
		    }
		}
		ajax.send("userid="+userid+"&view="+pview);
	//}
}

function checkForNewMail() {
	// This loads new mail into the current active conversation thread
	//alert("checking for new mail");
	var ajax = ajaxObj("POST", "/action/messaging/getnewmessages_ajx.php");
	 ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} else {
					if (ajax.responseText == 'nodata'){
						// dont do anything, there are no new messages
					}
					else {
						newdata = ajax.responseText;
						newdata = newdata.split('*::*');
				
						// new data is found!! insert it!
						//alert(newdata[0]);
						_("conversation_container").innerHTML += newdata[0];
						loadtime = newdata[1];
						_("conversation-box").scrollTop = _("conversation-box").scrollHeight - _("conversation-box").clientHeight;



					}
					
				}
	        }
        }
        ajax.send("userid="+selectedconversation+"&loadtime="+loadtime);
}



function replyConversation() {
	var ajax = ajaxObj("POST", "/action/messaging/send_ajx.php");
	var message = _("conversation-textarea").value;
	if (message == '') {
		return;
	}
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	        if(ajax.responseText.substring(0,5) == "error"){        	
				_("conversation_container").innerHTML = ajax.responseText;
				
			} else {
				_("conversation_container").innerHTML = _("conversation_container").innerHTML+ ajax.responseText;
				_("conversation-box").scrollTop = _("conversation-box").scrollHeight - _("conversation-box").clientHeight;

				_("conversation-textarea").value = '';	
				_("conversation-textarea").focus();	
			
			}
	    }
	}
	ajax.send("to="+selectedconversation+"&message="+message+"&view=main");	
	resetPostman();
	

}

function sendMessage(toUser) {

	var message = _("textmessage").value;
	//alert("Sending "+message+" to "+toUser);
	var ajax = ajaxObj("POST", "/action/messaging/send_ajx.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	        if(ajax.responseText.substring(0,5) == "error"){        	
				_("messagebox").innerHTML = ajax.responseText;
				
			} else {
				_("messagebox").innerHTML = ajax.responseText;
				messaging=false;
				boxtimer = setTimeout(function() {
				_("avatar_helper").style.display="none";
				if (_("send-message") != null)
					_("send-message").style.display="none"
				//usercache[toUser+'link'] = null;
	}, 1500);
	
			}
	    }
	}
	ajax.send("to="+toUser+"&message="+message);	
	resetPostman();
	
}

function confirmFriend(userid) {
	_("action-"+userid).style.display = "none";
	_("confirm-"+userid).style.display = "inline";

}

function acceptFriend(userid) {
	//alert("Rejecting "+userid);
	var ajax = ajaxObj("POST", "/action/user/acceptfriend_ajx.php");
	var level = _("friendlevel-"+userid).value;
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			system_message(ajax.responseText);
			_("fr-"+userid).style.display = 'none';		
	    }
	}
	ajax.send("userid="+userid+"&level="+level);	
}

function rejectFriend(userid) {
	//alert("Rejecting "+userid);
	var ajax = ajaxObj("POST", "/action/user/rejectfriend_ajx.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			system_message(ajax.responseText);
			_("fr-"+userid).style.display = 'none';		
	    }
	}
	ajax.send("userid="+userid);	
}
function removeFriend(toUser) {
	var level = _("friendlevel2").value;
	//alert("Sending "+message+" to "+toUser);
	var ajax = ajaxObj("POST", "/action/user/unfriend_ajx.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	        if(ajax.responseText.substring(0,5) == "error"){        	
				_("friendbox").innerHTML = ajax.responseText;
				
			} else {
				location.reload(true);
	
			}
	    }
	}
	ajax.send("to="+toUser+"&level="+level);	
	//resetPostman();

}
function updateFriend(toUser) {
	var level = _("friendlevel2").value;
	//alert("Sending "+message+" to "+toUser);
	var ajax = ajaxObj("POST", "/action/user/updatefriendlevel_ajx.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	        if(ajax.responseText.substring(0,5) == "error"){        	
				_("friendbox").innerHTML = ajax.responseText;
				
			} else {
				location.reload(true);
	
			}
	    }
	}
	ajax.send("to="+toUser+"&level="+level);	
	//resetPostman();

}

function addFriend(toUser,reload) {

	var level = _("friendlevel").value;
	//alert("Sending "+message+" to "+toUser);
	var ajax = ajaxObj("POST", "/action/user/addfriend_ajx.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	        if(ajax.responseText.substring(0,5) == "error"){        	
				_("friendbox").innerHTML = ajax.responseText;
				
			} else {
				if (reload) {
					location.reload();
				}
				else {
					_("friendbox").innerHTML = ajax.responseText;
					messaging=false;
					boxtimer = setTimeout(function() {
					_("avatar_helper").style.display="none";
					//usercache[toUser+'link'] = null;
					}, 1500);
				}
			}
	    }
	}
	ajax.send("to="+toUser+"&level="+level);	
	resetPostman();
	
}

function followPerson(person_id){
	var ajax = ajaxObj("POST", "/action/user/followperson_ajx.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	        if(ajax.responseText.substring(0,5) == "error"){        	
				_("followbox").innerHTML = ajax.responseText;
				
			} else {
				_("followbox").innerHTML = ajax.responseText;
				messaging=false;
				boxtimer = setTimeout(function() {
				_("avatar_helper").style.display="none";
				
	}, 1500);
	
			}
	    }
	}
	ajax.send("person="+person_id);	
	resetPostman();
}

function hideInbox() {
	_("message-inbox").style.display='none';
}

function showNotificationInbox(e,target,left,top) {
	if (_(target).style.display != 'none') {
		_(target).style.display = 'none';
		return;
	}
	_("friend-inbox").style.display='none';	
	
	_("inbox-main").style.display="none";
	_("message-inbox").style.display='none';	
	showBox(e,target,left,top,'');
	var ajax = ajaxObj("POST", "/action/messaging/loadnotifications_ajx.php");
	
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	        if(ajax.responseText.substring(0,5) == "error"){        	
				newdata = ajax.responseText;
				system_message(newdata);
			} else {
				newdata = ajax.responseText;
				showBox(e,target,left,top,newdata);
				//boxtimer = setTimeout(function() {
				//_(target).style.display="none";
				//}, 1000);
	
			}
	    }
	}
	ajax.send("userid=&view=normal");	
		


	//alert('showing inbox '+target);
	
}



function showFriendInbox(e,target,left,top) {
	if (_(target).style.display != 'none') {
		_(target).style.display = 'none';
		return;
	}
	_("inbox-main").style.display="none";
	_("message-inbox").style.display='none';	
	_("notification-inbox").style.display='none';	
	
	showBox(e,target,left,top,'');
	var ajax = ajaxObj("POST", "/action/messaging/readfriendinbox_ajx.php");
	
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	        if(ajax.responseText.substring(0,5) == "error"){        	
				newdata = ajax.responseText;
				system_message(newdata);
			} else {
				newdata = ajax.responseText;
				showBox(e,target,left,top,newdata);
				//boxtimer = setTimeout(function() {
				//_(target).style.display="none";
				//}, 1000);
	
			}
	    }
	}
	ajax.send("userid=&view=normal");	
		


	//alert('showing inbox '+target);
	
}



function showInbox(e,target,left,top) {
	_("friend-inbox").style.display='none';
	_("notification-inbox").style.display='none';	
	
	if (_(target).style.display != 'none') {
		_(target).style.display = 'none';
		return;
	}	
	showBox(e,target,left,top,'');
	var ajax = ajaxObj("POST", "/action/messaging/readinbox_ajx.php");
	
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	        if(ajax.responseText.substring(0,5) == "error"){        	
				newdata = ajax.responseText;
				system_message(newdata);
			} else {
				newdata = ajax.responseText;
				showBox(e,target,left,top,newdata);
				//boxtimer = setTimeout(function() {
				//_(target).style.display="none";
				//}, 1000);
	
			}
	    }
	}
	ajax.send("userid=&view=normal");	
		


	//alert('showing inbox '+target);
	
}
function showFeedPage() {
	_("secondary-page").style.display = 'none';
	_("main-page").style.display = 'block';
	
}
maininboxloaded=false;
selectedconversation = -1;

function showMainInbox(userid) {
	_("friend-inbox").style.display='none';
	_("notification-inbox").style.display='none';	
	
	if (!maininboxloaded)
		loadMainInbox(userid);
	else {
		me = _("message_"+userid);
		if (me.className != 'message-selected'){
			me.className = 'message-selected';
			if (selectedconversation > 0)
				_("message_"+selectedconversation).className = '';
		}
	}
	userid = loadConversation(userid);
	if (userid > 0 )
		selectedconversation = userid;	
	_("message-inbox").style.display='none';
	_("inbox-main").style.display='block';
	
}
function closeInbox(){
	_("inbox-main").style.display="none";
	_("message-inbox").style.display='none';
	maininboxloaded=false;
	mailman = false;


}
function closeFriendInbox(){
	
	_("friend-inbox").style.display='none';


}
function closeNotificationInbox(){
	
	_("notification-inbox").style.display='none';


}

function loadConversation(userid) {
	var ajax = ajaxObj("POST", "/action/messaging/readconversation_ajx.php");
	mailman = true;
	
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	        if(ajax.responseText.substring(0,5) == "error"){        	
				newdata = ajax.responseText;
				system_message(newdata);
			} else {
				newdata = ajax.responseText;
				newdata = newdata.split('*::*');
				_("conversation-thread").style.overflowY = 'auto';
				
				_("conversation-header").innerHTML = newdata[0];
				//alert(getPageHeight());
				_("inbox-conversation").style.height = getPageHeight() + 'px'; 
				_("inbox-userlist").style.height = getPageHeight() + 'px';
				//_("conversation-thread").innerHTML = ;
				_("conversation-thread").innerHTML = ""+newdata[1];
				//alert('scrolling');
				//_("conversation-thread").style.overflowY = 'hidden';
				_("conversation-thread").style.display = 'block';
				_("conversation-textarea").focus();
				//_("conversation-thread").scrollTop = _("conversation-thread").scrollHeight - _("conversation-thread").clientHeight+ 20;
				_("conversation-box").scrollTop = _("conversation-box").scrollHeight;
				//_("conversation-thread").scrollTop = 90000
				//alert('scrolling');
				selectedconversation = newdata[2];
				loadtime = newdata[3];
				mailman = true;

	
    
				
			}
	    }
	}
	ajax.send("userid="+userid);
					
}

function loadMainInbox(userid) {
	var ajax = ajaxObj("POST", "/action/messaging/readinbox_ajx.php");
	
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	        if(ajax.responseText.substring(0,5) == "error"){        	
				newdata = ajax.responseText;
				system_message(newdata);
			} else {
				newdata = ajax.responseText;
				_("inbox-userlist").innerHTML = newdata;
				maininboxloaded=true;
			}
	    }
	}
	ajax.send("view=main&userid="+userid);	
}
