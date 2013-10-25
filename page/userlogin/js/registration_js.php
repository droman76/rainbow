<?php
session_start();

include($_SESSION['home'].'config.php');
include($_SESSION['home'].'lib/babelfish.php'); 
include($_SESSION['home'].'lib/functions.php'); 

$rpage = page_controller();
$babel = new BabelFish($rpage);

header('Content-type: text/javascript'); ?>

function restrict(elem){
	var tf = _(elem);
	var rx = new RegExp;
	if(elem == "email"){
		rx = /[' "]/gi;
	} else if(elem == "username"){
		rx = /[^a-z0-9]/gi;
	}
	tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
	_(x).innerHTML = "";
}
function checkusername(){
	var u = _("name").value;
	if(u != ""){
		_("unamestatus").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "action/user/ajax_signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {

	            _("unamestatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("usernamecheck="+u);
	}
}
function forgot_password(){
	_("pagebody").style.display = 'none';
	_("requestpassword").style.display = 'block'; 

}
function request_password(){
	var ajax = ajaxObj("POST", "action/user/ajax_pass_request.php");
    var e = _("passwordrequest").value;
	
    ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText != "request_success"){
	        	system_message(ajax.responseText);
			
			} else {
				window.scrollTo(0,0);
				_("requestpassword").style.display = 'none'; 
	        	_("pagebody").style.display = 'block';
	        	_("system_messages").style.display = 'block';
				_("system_messages").innerHTML = "<?=$babel->say('p_request_success',false)?><u>"+e+"</u> <?=$babel->say('p_passwordrequest_success2',false)?>";
			}
			
	    }   
    }
    ajax.send("e="+e);
    
		
}


function signup(){
	// get the captcha
	var e = _("email").value;
	var n = _("name").value;
	
	var p1 = _("pass1").value;
	var g = _("gender").value;

	var status = _("status");
	if(e == "" || p1 == "" || g == "" ){
		error_message("<?=$babel->say('registration_fill_all_data')?>");
	} 
	else {
		_("captcha").style.display = "inline";
		_("captchatxt").selected;
		_("captchatxt").focus();
	
	}

}


function signup_cont() {
	_("captcha").style.display = 'none';	
	var e = _("email").value;
	var n = _("name").value;
	
	var p1 = _("pass1").value;
	var g = _("gender").value;

	var captcha = _("captchatxt").value;

	var status = _("status");
	if(e == "" || p1 == "" || g == "" ){
		error_message("<?=$babel->say('registration_fill_all_data')?>");
	} 
	else {
		_("signupbtn").style.display = "none";
		status.innerHTML = 	"<?=$babel->say('registration_wait')?>";
		var ajax = ajaxObj("POST", "action/user/ajax_signup.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText != "signup_success"){
	            	
	            	error_message(ajax.responseText);
					_("signupbtn").style.display = "block";
				} else {
					window.scrollTo(0,0);
					_("pagebody").innerHTML = "<h1><?=$babel->say('registration_success')?></h1><br><br>"+n+",	<?=$babel->say('registration_success1')?><u>"+e+"</u> <?=$babel->say('registration_success2')?><br><br><br><a href='<?=$CONFIG->site?>'><?=$babel->say('registration_return')?></a>";
				}
	        }
        }
        ajax.send("e="+e+"&p="+p1+"&n="+n+"&g="+g+"&captcha="+captcha);
        
	}
}
function openTerms(){
	_("terms").style.display = "block";
	emptyElement("status");
}


function emptyElement(x){
	_(x).innerHTML = "";
}
function login(){
	var e = _("lemail").value;
	var p = _("lpassword").value;
	if(e == "" || p == ""){
		_("status").innerHTML = "Fill out all of the form data";
		return false;
	} else {
		_("loginform").submit();
		
	}
}

