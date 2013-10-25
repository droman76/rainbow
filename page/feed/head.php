<?php include($CONFIG->home . 'page/feed/feediterator.php');?>

<link rel="stylesheet" href="<?=$CONFIG->site?>/page/feed/feed.css" type="text/css">
	
<script type='text/javascript'>
//keeps track of last read times
var time = {};
<?=cronos()?> // initializes the time array

var throttle = 1000;
var get_lock = false;

var current_scope = '6';
var client_time = new Date().getTime();
var diferential = 0;
<?php if (isset($groupid)) {?>
var groupid = '<?=$groupid?>';
<?php } else {?>
var groupid = '';
<?php } 
$citystart=0;$regionstart=0;$countrystart=0;$worldstart=0;
if ($_SESSION['city'] != '') {
	$citystart = 15;
	$startview = 'city';
} else if ($_SESSION['region'] != '') {
	$regionstart = 15;
	$startview = 'region';
}else if ($_SESSION['country'] != ''){
	$countrystart = 15;
	$startview = 'country';
}
else {
	$startview = 'world';
	$worldstart = 15;
}
?>

var current_view = '<?=$startview?>';

var start_view = {};
start_view['continent'] = 0;
start_view['country'] = <?=$countrystart?>;
start_view['region'] = <?=$regionstart?>;
start_view['city'] = <?=$citystart?>;
start_view['world'] = <?=$worldstart?>;
start_view['admin'] = 0;
start_view['a'] = 0;
start_view['b'] = 0;
start_view['c'] = 0;
start_view['e'] = 0;
start_view['g'] = 0;
start_view['g'] = 0;
start_view['l'] = 0;
start_view['m'] = 0;
start_view['n'] = 0;
start_view['o'] = 0;
start_view['p'] = 0;
start_view['r'] = 0;
start_view['s'] = 0;

//if (<?=time()*1000?> > client_time) diferential = <?=time()*1000?> - client_time;// server is ahead
diferential = <?=time()*1000?> - client_time;
//alert(diferential); 

window.onscroll = function (e) {  
 activity=1;	
 loadFeed();
} 

window.setTimeout(function() {
	overSeer();
}, 1000);

window.setTimeout(function() {
	postMan();
}, 1000);
/* oversees throttle by monitoring user activity */
var sleep_level = 1; // first one hundred seconds are high throttle
var no_action = 0; // tracks the amount of no activity for every second
var activity = 0; // measures user activity by keypress or scroll
var countdown = throttle;
var master_view = '';


function overSeer() {

	sleep_level++;
	if (sleep_level > 960) throttle = 160000; // 2 min response after 8 min
	else if (sleep_level > 480) throttle = 80000; // 1 min response afer 4 min
	else if (sleep_level > 220) throttle = 40000; // 4 second response after 2min
	else if (sleep_level > 120) throttle = 20000; // 20 s after 1 min (unless comments or posting)
	else if (sleep_level > 30) throttle = 5000; //5 second response after 30 seconds
	else if (sleep_level > 0) throttle = 1000;
	
	c = (countdown/1000)-1
	
	_("feed_throttle").innerHTML = 'Speed at '+(1000/throttle)*3600+' requests/hr * '+throttle/1000 +'sec response time';
	
 	if (mailman) {
 		// Check for new mail
 		checkForNewMail();
 	}

	countdown = countdown - 1000;

	activity = 0; //reset to no activity
	setTimeout(function() {
		overSeer();
	}, 1000);

	


}
var postman;

function postMan() {
	if (get_lock == true) return; // user is currently fetching data through switch or scroll
	checkForNewPosts();
	postman = window.setTimeout(function() {
		postMan();
	}, throttle);

}
function resetPostman() {
	clearTimeout(postman);
	throttle = 1000;
	postMan();
	
}


function checkForNewPosts() {
	countdown = throttle;
	var ajax = ajaxObj("POST", "/action/feed/post-get-new_ajx.php");
	var start_time = Math.floor(time[current_view] / 1000); // set it in seconds
	
	//_("feed_timer").innerHTML = out;
	//start_time = start_time+5; // add latency
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText.substring(0,7) == "error::"){
	            	error_message(ajax.responseText.substring(7,ajax.responseText.length));
				} 
				 	
				else {
					//alert(ajax.responseText);
					newdata = ajax.responseText;

					/** TAB COUNTER **/
					// PARSE out tabcounter information
					n = newdata.indexOf("||");
					
					var countinfo = ajax.responseText.substring(0,n);
					var tabcount = countinfo.split("*");
					//alert(ajax.responseText);
					//alert(countinfo);
					for (var i = 0; i < tabcount.length; i++) {
  						var tab = tabcount[i].split(':');
  						var view = tab[0]; var count = tab[1];
  						var cv = _("counter_"+view);
  						if (tab != null && view != null && view != '' && view != current_view && cv != null){
  							if (count >0 && view != 'mail' && view != 'friends' && view != 'notifications')
  								cv.innerHTML = '('+count+')';
  							else if (count > 0 && view == 'mail')
  								cv.innerHTML = "<span class='messages-new'>"+count+"</span>";
  							else if (count > 0 && view == 'friends')
  								cv.innerHTML = "<span class='messages-new'>"+count+"</span>";
  							else if (count > 0 && view == 'notifications')
  								cv.innerHTML = "<span class='messages-new'>"+count+"</span>";
  							else 
  								cv.innerHTML = '';
  						
  						}
  						
  					}
					/** NEW POSTS **/
					
					c = newdata.indexOf("::*::"); // the marker denoting end of post data
					newdata = ajax.responseText.substring(n+2,c);
					
					if(newdata == "nodata" || newdata == ''){
						//alert('nothing');
	            		// do nothing
	            	}
					else {
						//alert(newdata);
						_("feedcontainer_"+current_view).innerHTML = newdata + _("feedcontainer_"+current_view).innerHTML;
						//system_message("<?=$babel->say('p_post_success')?>")
						//_("feed-textarea_"+current_view).value = '';
						time[current_view] = new Date().getTime()+diferential; //update view time to present
					}
					

					/** NEW COMMENTS **/

					
					cdata = ajax.responseText.substring(c+5,ajax.responseText.length);
					//alert('Comment data : '+cdata);
					if(cdata == "nocomments"){
	            		// do nothing
	            	}
					else {
						//alert('Comment found! '+cdata);
						// parse the DOM
						parser = new DOMParser()
   						doc = parser.parseFromString(cdata, "text/xml");
						//alert(cdata);
     							
						for (var i = 0; i < doc.childNodes.length; i++) {
     							var child = doc.childNodes[i];
     							post_id = child.getAttribute('data-postid');
     							q = cdata.indexOf('</li>')
     							html = cdata.substring(0,q+5);
     							if (html=='')continue;	
     							quid = "comment-container_"+post_id+'_'+current_view;
     							//alert(quid);
     							_(quid).innerHTML = _(quid).innerHTML + html;
     							// make sure the new comment box is visible:
     							_("comment-"+post_id+"_"+current_view).style.display = 'inline';

     							//alert(child.tagName);
     						
  						}

						

					}
					



				}
	        }
        }
        //alert("sending ajax with view: "+current_view+" and groupid: "+groupid);

        ajax.send("scope="+current_scope+"&view="+current_view+"&stime="+start_time+"&master_view="+master_view+"&groupid="+groupid);

	// traverse de list of actual posts and update time
	
	var items = _("feedcontainer_"+current_view).children;
	for(var i = 0; i < items.length; i++) {
    	if(items[i].tagName == 'LI') {
    		timestamp = items[i].getAttribute('data-timestamp');
    		st = timestamp * 1000;
    		var sago = ago(st);
    		if (sago){
    			time_id = items[i].id + '_' + timestamp;
    			//alert("'"+time_id+"'");
    			_(time_id).innerHTML = sago +' '+ "<?=$babel->say('p_ago')?>";
    		}
    		//now traverse this list item to find the comments list item
    		var post_id = items[i].id.split("-")[1];
    		tagcid = "comment-container_"+post_id;
    		//alert(tagcid);
    		var comments = _(tagcid);
    		if (comments != null) {
    			comments = comments.children;

	    		for (var j=0; j < comments.length;j++){
	    			timestamp = comments[j].getAttribute('data-timestamp');
	    			commentid = comments[j].getAttribute('data-commentid');
	    			//alert(timestamp);
	    			stt = timestamp * 1000;
	    			var sago = ago(stt);
	    			if (sago) {
	    				cig = 'ago_' + commentid+'_'+current_view;
	    				if (_(cig)==null)alert(cig);
	    				_(cig).innerHTML = sago +' '+ "<?=$babel->say('p_ago')?>";
	    			}
	    		}
    		}

    	}    	
    }
    
	
	

}

function ago(stime) {
	var now = new Date().getTime()+diferential;
	var dif = now - stime;
	var out = '';
	var seconds = dif / 1000;
	//alert('now: '+now+" stime: "+stime+'dif: '+dif+" SECONDS: "+seconds)
	//seconds += 15; // to account for latency

	var y = Math.floor(seconds / 31570560);
	var mo = Math.floor(seconds / 2630880) ;
	var w = Math.floor(seconds / 604800);
	var d = Math.floor(seconds / 86400);
	var h = Math.floor(seconds / 3600);
	var m = Math.floor(seconds / 60);
	var s = Math.floor(seconds);
	
	if (s < 60) {
		if (s%10 != 0)
		return false;
	}
	if (s >=1) out = (s>1) ? s + " <?=$babel->say('seconds')?>" : s + " <?=$babel->say('second')?>";
	if (m >=1) out = (m>1) ? m + ' minutes' : m + ' minute';
	if (h >=1) out = (h>1) ? h + ' hours' : h + ' hour';
	if (d >=1) out = (d>1) ? d + ' days' : d + ' day';
	if (w >=1) out = (w>1) ? w + ' weeks' : w + ' week';
	if (mo >=1) out = (mo>1) ? m + ' months' : m + ' month';
	if (y >=1) out = (y>1) ? y + ' years' : y + ' year';
	
	//_("feed_timer").innerHTML = out;
	return out;
	//alert ('Time since start: '+out);
	
    //var pds = array('second','minute','hour','day','week','month','year','decade');
    //var lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);

}

function switchTag(tag) {
	//tag = e.value;
	if (current_view == 'continent') scope = 9;
	if (current_view == 'country') scope = 12;
	if (current_view == 'region') scope = 14;
	if (current_view == 'city') scope = 15;
	if (current_view == 'world') scope = 5;

	
	switchView(tag,scope);

	toggleTag(tag);
}


function switchView(view,scope) {
	activity=1;// user is active
	sleep_level = 1; // throtle up!
	countdown=throttle;
	// mark all items as read
	_("activity-title_"+current_view).style.display = 'none';
    _("activity-title_"+view).style.display = 'block';
    
	_("feedcontainer_"+current_view).style.display = 'none';
  	_("feed-input_"+current_view).style.display = 'none';
  	
  	
  	// this is for the tabs to change
  	if (view.length > 1){
  		_(view).className="rainbow-state-selected";
  		if (current_view.length > 1 && view != current_view)
  			_(current_view).className="";
  		else if (master_view != view && view != current_view) {
  			_(master_view).className ="";
  		}
  		//_('tag-selector').value = '*';
  		
  	}
  	else if (current_view.length > 1 && view.length != 1){
  		_(current_view).className="";
  	}



	var items = _("feedcontainer_"+current_view).children;
	for(var i = 0; i < items.length; i++) {
    	if(items[i].tagName == 'LI') {
    		items[i].className = 'rainbow-item';
    	}    	
    }
    if (current_view.length > 1) master_view = current_view;
    resetPostman();
	
    resetInput(); // resets input box before switching view
    
  	
  	current_view = view;
	current_scope = scope;

	_("feedcontainer_"+view).style.display = 'block';
  	_("feed-input_"+view).style.display = 'block';
  		
  				
  	// reset for tag view so we load a new data fresh	
  	if (view.length == 1) {
  		_("feedcontainer_"+view).innerHTML = '';
  		start_view[view] = 0;
  	}
  	if (start_view[view] == 0) {
  		get_lock = true;
  		//alert('getting feed for '+view+' scope: '+scope);
  		getFeedData(view,scope);
  		
  		get_lock = false; 
  	}
  	else {
  		get_lock = true;
  		checkForNewPosts();
  		get_lock = false; 
  	}
  	
  	time[view] = new Date().getTime()+diferential;
  	// reset current tab to none (number beside tag)
  	var cv = _("counter_"+view);
  	if (cv != null) cv.innerHTML = '';	

}

function resetInput() {
	_("feed-textarea").value = '';
	_("feed-textarea").rows = 1;
	_("imagedropzone").style.display="none";
	_("drop_zone").innerHTML = "";
	_("fileinput").style.display='none';
	_("files").value = '';
	_("addimages_text").innerHTML = "<?=$babel->say('p_addimages')?>";
	_("feed-textarea").setAttribute('placeholder',"What would you like to share?");
	_("tag-a").style.display='none';
	_("tag-b").style.display='none';
	_("tag-c").style.display='none';
	_("tag-e").style.display='none';
	_("tag-f").style.display='none';
	_("tag-g").style.display='none';
	_("tag-l").style.display='none';
	_("tag-m").style.display='none';
	_("tag-n").style.display='none';
	_("tag-o").style.display='none';
	_("tag-p").style.display='none';
	_("tag-r").style.display='none';
	_("tag-s").style.display='none';
	_('a_menu-tag').className='tag-deselected';
	_('b_menu-tag').className='tag-deselected';
	_('c_menu-tag').className='tag-deselected';
	_('e_menu-tag').className='tag-deselected';
	_('f_menu-tag').className='tag-deselected';
	_('g_menu-tag').className='tag-deselected';
	_('l_menu-tag').className='tag-deselected';
	_('m_menu-tag').className='tag-deselected';
	_('n_menu-tag').className='tag-deselected';
	_('o_menu-tag').className='tag-deselected';
	_('p_menu-tag').className='tag-deselected';
	_('r_menu-tag').className='tag-deselected';
	_('s_menu-tag').className='tag-deselected';
	barter_offer = ''; barter_ask = '';
	barter = false; barter_offer_mark = -1; barter_ask_mark = -1;
	char_stack = new Array();
	tags = ""; tagcount = 0;
	_('barter-offer').innerHTML = '';
	_('barter-ask').innerHTML = '';
	_('avatar_helper').style.display='none';
	messaging = false;

	
	
      


}


function post(group) {
	activity = 1;
	sleep_level = 1;
	var message = _("feed-textarea").value;
	view = current_view;
	//alert(view);

	//alert('posting in '+location+ " message: "+message);
	//cacaUploadedImages55();
        

	var ajax = null;
	if (group == null || group == '' )
		ajax = ajaxObj("POST", "/action/feed/post_ajx.php");
	else 
		ajax = ajaxObj("POST", "/action/group/post_ajx.php");
		
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText.substring(0,7) == "error::"){
	            	error_message(ajax.responseText.substring(7,ajax.responseText.length));
				} else {
					//alert(ajax.responseText);
					newdata = ajax.responseText;
					if (view.length == 1) {
						//also add in master view when in tag view
						ditems = newdata.split("*****");
						_("feedcontainer_"+view).innerHTML = ditems[0] + _("feedcontainer_"+view).innerHTML;
						_("feedcontainer_"+master_view).innerHTML = ditems[1] + _("feedcontainer_"+master_view).innerHTML;
					}
					else {
						_("feedcontainer_"+view).innerHTML = newdata + _("feedcontainer_"+view).innerHTML;
					
					}

					system_message("<?=$babel->say('p_post_success')?>")
					resetInput();
					// currently in tag view mode.. so retoggle tag after reset
					if (current_view.length == 1)
						toggleTag(current_view);
					imagelist = ''; // reset the image list
     
		
				}
	        }
	        else {
	        	if (ajax.status == 500) {
	        		error_message("<?=$babel->say('e_posting_server_error')?>");
	        	}
	        }
        }
        // check to see if there are images and upload them
        //images = handleUpload();
        //alert(imagelist);
        if (imagelist == 'error'){
        	error_message("<?=$babel->say('e_imageuploadfailed')?>");
        }
        else {
        	ajax.send("scope="+current_scope+"&view="+view+"&message="+message+"&images="+imagelist+"&tags="+tags+"&master_view="+master_view+"&group="+group);
        }
       


}

function loadFeed()
{
		
    var docViewTop = f_scrollTop();
    var docViewBottom = f_clientHeight();

    var feeder = document.getElementById("feeder_"+current_view);
	
    if (feeder == null) return;
    //alert("Top: "+docViewTop+" Height: "+docViewBottom);
    var elemTop = findTop(feeder);
    //alert("Feeder at: "+elemTop);
    var elemBottom = elemTop + '10';
    result = (elemTop >= docViewTop && elemTop <= (docViewTop+docViewBottom));
    if (result){
    	//alert ("Feeder is now visible!!! Feeder at "+elemTop+" document btw "+docViewTop+" and "+docViewBottom);
    	remove("feeder_"+current_view);
    	get_lock = true;
    	newdata = getFeedData(current_view);
    	get_lock = false;
    }
}

function remove(id)
{
    return (elem=document.getElementById(id)).parentNode.removeChild(elem);
}

function findTop(obj) {
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		do {
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop;
			} while (obj = obj.offsetParent);
			return curtop;
	}
}

function f_filterResults(n_win, n_docel, n_body) {
	var n_result = n_win ? n_win : 0;
	if (n_docel && (!n_result || (n_result > n_docel)))
		n_result = n_docel;
	return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
}
function f_scrollTop() {
	return f_filterResults (
		window.pageYOffset ? window.pageYOffset : 0,
		document.documentElement ? document.documentElement.scrollTop : 0,
		document.body ? document.body.scrollTop : 0
	);
}

function f_clientHeight() {
	return f_filterResults (
		window.innerHeight ? window.innerHeight : 0,
		document.documentElement ? document.documentElement.clientHeight : 0,
		document.body ? document.body.clientHeight : 0
	);
}


var size = 20;

function getFeedData(view,scope) {
	
var ajax = ajaxObj("POST", "/action/feed/readfeed_ajx.php");
_("ajax_loader").style.display = "block";
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText == "error"){
	            	system_message(ajax.responseText);
					_("ajax_loader").style.display = "none";
				} 
				
				else {
					//alert(ajax.responseText);
					newdata = ajax.responseText;
					//alert(newdata);
					_("ajax_loader").style.display = "none";
					_("feedcontainer_"+view).innerHTML = _("feedcontainer_"+view).innerHTML + newdata;
					//+"<div id='feeder'></div>";

		
				}
	        }
	        else if (ajax.status == 500){
					_("ajax_loader").style.display = "none";
					_("feedcontainer_"+view).innerHTML = "<div class='no-items'><?=$babel->say('p_server_error')?></div>";
					

			}
        }
        //alert("sending ajax with start: "+start+" and size: "+size);
        if (start_view[view] == null) start_view[view] = 20;

        ajax.send("start="+start_view[view]+"&size="+size+"&view="+view+"&scope="+scope+"&master_view="+master_view);
     
        start_view[view] = start_view[view]+size;
 }

 function commenting(event,e,postid) {
 	sleep_level = 1;
 	var keyCode = ('which' in event) ? event.which : event.keyCode;
 	if(keyCode == '13'){
 		//submit
 		var comment = e.value;
 		// Disable comment box while submitting
 		e.placeholder = '<?=$babel->say('p_submittingc_pleasewait',false)?>';
 		e.disabled = true;

 		//system_message("Submitting comment "+comment);
 		var ajax = ajaxObj("POST", "/action/feed/comment_ajx.php");
		//_("ajax_loader").style.display = "block";
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText == "error"){
	            	
				} else {
					//alert(ajax.responseText);
					newdata = ajax.responseText;
					_("ajax_loader").style.display = "none";
					_("comment-container_"+postid+'_'+current_view).innerHTML = _("comment-container_"+postid+'_'+current_view).innerHTML + newdata;
					e.disabled = false;
					e.placeholder = "<?=$babel->say('p_write_comment',false)?>";
					e.focus();
					e.selected();
		
				}
	        }
        }
        ajax.send("postid="+postid+"&comment="+comment+'&scope='+current_scope+'&view='+current_view);
     
 		e.value = '';
 	}

 }


if (window.File && window.FileReader && window.FileList && window.Blob) {
  //alert('You have file support');
} else {
  alert('Image attachments/upload are not fully supported in this browser. Please consider updating your browser version');
}

function viewbox(postid){
	_('comment-'+postid+'_'+current_view).style.display = 'block';
	_('commentarea_'+postid+'_'+current_view).focus();

}

function resize_feed_input(e){
	if (e.value.length > 0 || hasimages) return;
	else e.rows = 1;

}
var imagelist = '';
function handleUpload(){
      //event.preventDefault();
      //event.stopPropagation();
  
  
  var fileInput = document.getElementById('files');
  var data = new FormData();
  //var imagelist = '';
  _('upload_progress').style.display = 'block';
  // this is for files of selector choose files
if (fileInput){
  for(var i = 0; i < fileInput.files.length; ++i){
     data.append('file[]',fileInput.files[i]);
     imagelist += fileInput.files[i].name + "::";
     isimages = 1;
  }
}
if (fileList){
  // this is for files of drag an drop
  for (var i = 0, f; f = fileList[i]; i++) {
      //alert(f);
      data.append('file[]',f);
      imagelist += f.name + "::";
  
  }
}
  if (imagelist == '') return '';

  var request = new XMLHttpRequest();
  
  request.upload.addEventListener('progress',function(event){
    if(event.lengthComputable){
    	_('upload_progress').style.display = 'block';
  
      var percent = event.loaded / event.total;
      var progress = document.getElementById('upload_percent_progress');
      progress.style.display = 'block';
      per = Math.round(percent * 100) + '%';    
      progress.innerHTML = 'Loading '+per;
      //system_message('loading');
    }
  });
  request.onreadystatechange = function() {
  	_('upload_progress').style.display = 'block';
  
 			if(ajaxReturn(request) == true) {
	            if(request.responseText == "error"){
	            	imagelist = 'error';
				} 
				else {
					_('upload_progress').style.display = 'none';
  
				}
	        }
 	}
	     
  
  request.open('POST','<?=$CONFIG->site?>/action/feed/upload_images.php');
  request.setRequestHeader('Cache-Control','no-cache');
  
  request.send(data);
  _('upload_progress').style.display = 'none';
  //alert('response: '+request.responseText);
  
  //return imagelist;
	
}


var tags = "";
var tagcount = 0;

function toggleTag(tag){
	var p = tags.indexOf(tag);
	if (p < 0){
		if (tagcount == 3) {
			error_message("<?=$babel->say('e_nomorethanthreetags')?>");
			return false;
		}
		tags += tag + '::';
		tagcount++;
		_(tag+'_menu-tag').className = 'tag-selected';

		_("tag-"+tag).style.display='block';
		_("tag-messenger").style.display = 'none';
		_("tag-menu").style.display = 'none';
		// handle specific mode cases
		if (tag == 'b') barter = true;
	
	}
	else {
		tagcount--;
		n =tags.substring(p,tags.length).indexOf('::');
		tags = tags.substring(0,p) + tags.substring(p+n+2,tags.length);
		_(tag+'_menu-tag').className = 'tag-deselected';
		_("tag-"+tag).style.display='none';
	}
	return true;
}
function tagOut(tag){
	if (tags.indexOf(tag)>=0) return; // tag is selected dont do anything
	_(tag+'_menu-tag').className = 'tag-hoverout';
}
function tagIn(tag){
	if (tags.indexOf(tag)>=0) return; // tag is selected dont do anything
	_(tag+'_menu-tag').className = 'tag-hover';

}



function toggleLayer(layer){
	var myLayer = document.getElementById(layer);
	if(myLayer.style.display=="none" || myLayer.style.display==""){
	myLayer.style.display="block";
	_("cat_desc").style.display='block';

	} else { 
	myLayer.style.display="none";
	_("cat_desc_"+current).style.display='none';
	}
}

var mark1 = false;
var mark2 = false;
var barter = false;
var barter_offer_mark = -1;
var barter_ask_mark = -1;
var p_char ='';
var c_char = '';
var barter_offer = '';
var barter_ask = '';
var char_stack = new Array();
//TODO: modify this code to handle internationalization triggers
// something in babelfish like babel.tagCode('barter');
function smartTagger(e) {

	var unicode=e.keyCode? e.keyCode : e.charCode;
	//system_message(unicode);
	var tchar=String.fromCharCode(unicode).toLowerCase();
	c_char = unicode;
	char_stack.push(c_char);
	var tm = _("tag-messenger");
	var t= _("feed-textarea");
		
	if (mark1 == false && unicode == '188'){
		mark1 = true;
	}
	else if (mark1 == true && unicode == '188'){
		// activate tooltip menu.. listening 
		mark2 = true;
		_("tag-messenger").style.display = 'block'
		_("tag-messenger").innerHTML = 'Now type one of the letters in red to automatically tag your post';
		//s(tm)
		s("tag-menu");
	}
	else if (mark1 && mark2 == true) {
		// handle appropriate key
		switch(unicode){
			case 66: _("tag-b").style.display='block';
			_("tag-menu").style.display = 'none';
			_("tag-messenger").style.display = 'none';
			toggleTag('b');
			barter = true;

			break;
			default: e = _("tag-"+tchar);
			if ( e != null) {
				toggleTag(tchar); 
			}
			else {
				error_message("<?=$babel->say('p_unsupported_tag')?>");
				
			}
		}
		mark1=false;
		mark2=false;
		//remove tag from text
		t.value = t.value.substring(0,t.value.length-3);
		
	}
	else {
		mark1 = false;
		mark2 = false;
	}
	l = char_stack.length;
	ba =_("barter-ask");
	bo =_("barter-offer");

	var offerTxt = "&nbsp;&nbsp;&nbsp;<font color=#2a6131>Offering:</font> ";
	var askTxt = "&nbsp;&nbsp;&nbsp;<font color=#bc5400>Asking:</font> ";
	
	/*************  BARTER OFFER - SAVE ***********************************/
	if (barter_offer_mark >=0 && char_stack[l-2]==32 && char_stack[l-1] == 32){
		//alert('saved');
		barter_offer_mark = -1;
		t.value = t.value.substring(0,t.value.length-1); // retreat one space back
		bo.innerHTML = offerTxt + '<b>'+barter_offer+'</b>';
		if (barter_ask.length <= 0)
		_("barter-msg").innerHTML = 'You can now type b,a to type what you want to ask for in exchange';
		else _("barter-msg").innerHTML = '';
	}
	/*************  BARTER ASK - SAVE ***********************************/
	if (barter_ask_mark >=0 && char_stack[l-2]==32 && char_stack[l-1] == 32){
		//alert('saved');
		barter_ask_mark = -1;
		t.value = t.value.substring(0,t.value.length-1); // retreat one space back
		ba.innerHTML = askTxt + '<b>'+barter_ask+'</b>';
		if (barter_offer.length <= 0)
		_("barter-msg").innerHTML = 'You can now type b,o to specify what you would like to offer';
		else _("barter-msg").innerHTML = '';
	}
	/*************  BARTER OFFER - START ***********************************/
	if (barter && char_stack[l-3]==66 && char_stack[l-2]==188 && char_stack[l-1] == 79){
		_("barter-msg").innerHTML = 'Type in what you are offering. End by pressing space bar stwice';
		//s(tm); // show this element
		bo.innerHTML = offerTxt;
		t.value = t.value.substring(0,t.value.length-3);
		barter_offer = '';
		barter_offer_mark = t.value.length;
	}
	/*************  BARTER ASK - START ***********************************/
	else if (barter && char_stack[l-3]==66 && char_stack[l-2]==188 && char_stack[l-1] == 65){
		_("barter-msg").innerHTML = 'Type in what you are asking for. End by pressing space bar stwice';
		ba.innerHTML = askTxt;
		t.value = t.value.substring(0,t.value.length-3);
		barter_ask = '';
		barter_ask_mark = t.value.length;
		
	}
	/*************  BARTER ASK - IN PROCESS *********************************/
	else if (barter_ask_mark >= 0) {
		barter_ask = t.value.substring(barter_ask_mark,t.value.length);
		ba.innerHTML = askTxt+barter_ask;

	}
	/*************  BARTER OFFER - IN PROCESS ******************************/
	else if (barter_offer_mark >= 0) {
		barter_offer = t.value.substring(barter_offer_mark,t.value.length);
		bo.innerHTML = offerTxt+barter_offer;

	}

	
}
//var usercache = new Array();

function showPostOptions(postid) {
	_("post-options-"+postid).style.display= "block";
}

function onPostOptionsOut(event,target) {
	if (messaging == true) return; // user is typing a message do not close the box
	// gets the box defined by target
	k = target;
	//k = document.getElementById(target);
	//this is the original element the event handler was assigned to
   	var e = event.toElement || event.relatedTarget;

	//check for all children levels (checking from bottom up)
	while(e && e.parentNode && e.parentNode != window) {
	    if (e.parentNode == k||  e == k) {
	        if(e.preventDefault) e.preventDefault();
	        return false;
	    }
	    e = e.parentNode;
	}
	k.style.display = 'none';

}
function editPost(postid) {
	var content = _("itemcontent-"+postid+"_"+current_view).innerHTML;
	content = content.trim();
	content = content.replace(/<br>/g, "\n");
	content = content.replace(/(<([^>]+)>)/ig,"");
	
	var html = '<textarea onclick="resize_textarea(this,62)" rows=5 id="xpost" onkeyup="resize_textarea(this,62)">'+content+'</textarea>';
	html += '<div><button style="font-size:12px" class="rainbow-button rainbow-button-submit" onclick="updatePost('+postid+')">'+'<?=$babel->say('p_updatepost',false)?>'+ '</button></div>';
	
	_("itemcontent-"+postid+"_"+current_view).innerHTML= html;
	_("itemcontent-"+postid+"_"+current_view).selected;
}
function updatePost(postid) {
	var content = _("xpost").value;
	xcontent = content.replace(/\n/g, "<br>");
	var ajax = ajaxObj("POST", "/action/feed/updatepost_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} else {
					_("itemcontent-"+postid+"_"+current_view).innerHTML = ajax.responseText;
					
				}
	        }
        }
     ajax.send("content="+content+"&postid="+postid);
     



}


function deleteComment(commentid,postid) {
	var ajax = ajaxObj("POST", "/action/feed/deletecomment_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} else {
					_("comment-"+commentid+'_'+current_view).style.display = "none";
					system_message(ajax.responseText);
				}
	        }
        }
        ajax.send("commentid="+commentid+"&postid="+postid);
     

}
function deletePost(postid) {
	var ajax = ajaxObj("POST", "/action/feed/delete_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} else {
					_("item-"+postid+'_'+current_view).style.display = "none";
					system_message(ajax.responseText);
				}
	        }
        }
        ajax.send("postid="+postid);
     

}


function blessPost(postid,commentid) {

	var ajax = ajaxObj("POST", "/action/feed/bless_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} else {
					_("blessbox1-"+postid+'_'+current_view).style.display = 'none';
					_("blessbox2-"+postid+'_'+current_view).style.display = 'inline';
					
					//system_message(ajax.responseText);

					_("blessplaceholder_"+postid +'_'+ current_view).style.display = 'inline';
					if (_("blessplaceholdertext_"+postid +'_'+ current_view) != null)
						_("blessplaceholdertext_"+postid +'_'+ current_view).innerHTML = ajax.responseText;
					
				}
	        }
        }
        ajax.send("postid="+postid+"&commentid="+commentid);
     

}
function likeComment(postid,commentid) {

	var ajax = ajaxObj("POST", "/action/feed/bless_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} else {
					_("commentlikes-"+postid+'-'+commentid+'_'+current_view).style.display = 'inline';
					_("commentlikes-"+postid+'-'+commentid+'_'+current_view).innerHTML = ajax.responseText;
					_("commentlikestxt1-"+postid+'-'+commentid+'_'+current_view).style.display = 'none';
					_("commentlikestxt2-"+postid+'-'+commentid+'_'+current_view).style.display = 'inline';


				}
	        }
        }
        ajax.send("postid="+postid+"&commentid="+commentid);
     

}
function unlikeComment(postid,commentid) {

	var ajax = ajaxObj("POST", "/action/feed/undobless_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} else {
					if (ajax.responseText == '0') {
						_("commentlikes-"+postid+'-'+commentid+'_'+current_view).style.display = 'none';
					
					}
					_("commentlikes-"+postid+'-'+commentid+'_'+current_view).innerHTML = ajax.responseText;
					_("commentlikestxt2-"+postid+'-'+commentid+'_'+current_view).style.display = 'none';
					_("commentlikestxt1-"+postid+'-'+commentid+'_'+current_view).style.display = 'inline';


				}
	        }
        }
        ajax.send("postid="+postid+"&commentid="+commentid);
     

}
function undoblessPost(postid,commentid) {
	var ajax = ajaxObj("POST", "/action/feed/undobless_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} else {
					if (ajax.responseText == '') {
						_("blessplaceholder_"+postid +'_'+ current_view).style.display = 'none';
				
					}
					else {
						_("blessplaceholdertext_"+postid +'_'+ current_view).innerHTML = ajax.responseText;
					}
					_("blessbox2-"+postid+'_'+current_view).style.display = 'none';
					_("blessbox1-"+postid+'_'+current_view).style.display = 'inline';
					
				}
	        }
        }
        ajax.send("postid="+postid+"&commentid="+commentid);
     

}
function sharePost(postid) {
	var ajax = ajaxObj("POST", "/action/feed/share_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} else {
					_("sharebox1-"+postid+'_'+current_view).style.display = 'none';
					_("sharebox2-"+postid+'_'+current_view).style.display = 'inline';
					
					//system_message(ajax.responseText);

					_("shareplaceholder_"+postid +'_'+ current_view).style.display = 'inline';
					if (_("shareplaceholdertext_"+postid +'_'+ current_view) != null)
						_("shareplaceholdertext_"+postid +'_'+ current_view).innerHTML = ajax.responseText;
					
				}
	        }
        }
        ajax.send("postid="+postid);
     

}
function unsharePost(postid) {
	var ajax = ajaxObj("POST", "/action/feed/undoshare_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} 
			else {
				if (ajax.responseText == '') {
					_("shareplaceholder_"+postid +'_'+ current_view).style.display = 'none';
				}
				else {
					_("shareplaceholdertext_"+postid +'_'+ current_view).innerHTML = ajax.responseText;
				}
				_("sharebox2-"+postid+'_'+current_view).style.display = 'none';
				_("sharebox1-"+postid+'_'+current_view).style.display = 'inline';
	       	}
        }
    }
    ajax.send("postid="+postid);
     

}
function followPost(postid) {
	var ajax = ajaxObj("POST", "/action/feed/follow_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} else {
					_("followbox1-"+postid+'_'+current_view).style.display = 'none';
					_("followbox2-"+postid+'_'+current_view).style.display = 'inline';
					
					//system_message(ajax.responseText);

					_("followplaceholder_"+postid +'_'+ current_view).style.display = 'inline';
					if (_("followplaceholdertext_"+postid +'_'+ current_view) != null)
						_("followplaceholdertext_"+postid +'_'+ current_view).innerHTML = ajax.responseText;
					
				}
	        }
        }
        ajax.send("postid="+postid);
     

}
function unfollowPost(postid) {
	var ajax = ajaxObj("POST", "/action/feed/undofollow_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} 
			else {
				if (ajax.responseText == '') {
					_("followplaceholder_"+postid +'_'+ current_view).style.display = 'none';
				}
				else {
					_("followplaceholdertext_"+postid +'_'+ current_view).innerHTML = ajax.responseText;
				}
				_("followbox2-"+postid+'_'+current_view).style.display = 'none';
				_("followbox1-"+postid+'_'+current_view).style.display = 'inline';
	       	}
        }
    }
    ajax.send("postid="+postid);
     

}

var keyCount = 0;

function resetKeyCount() {
	//alert('reseting count');
	keyCount = 0;
}
function searchData(event) {
	if (keyCount < 3) {
		keyCount++;
		return;
	}
	var ajax = ajaxObj("POST", "/action/search/search_ajx.php");
	term = _("search-input").value;
	
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} 
			else {
				_("search-results").style.display = 'inline';
				_("search_content").innerHTML = ajax.responseText;
	       	}
        }
    }
    ajax.send("searchterm="+term);

}


</script>