function _(x){
	return document.getElementById(x);
}

function s(x){
	document.getElementById(x).style.display = 'block';
}
function h(x){
	document.getElementById(x).style.display = 'none';
}
function t(x){
	e = document.getElementById(x);
	if (e.style.display == 'none') e.style.display = 'block';
	else e.style.display = 'none';
}
function system_message(msg) {
	var code = "<ul class=\"rainbow-system-messages\"><li style=\"opacity: 0.9;\" class='rainbow-message rainbow-state-success'>"+msg+"</li></ul>";
	_("system_messages").style.display = "block";
	_("system_messages").innerHTML = code;
	setTimeout(function() {
		if (ctrl_key == 'off')
    	_("system_messages").style.display = 'none';
	}, 5000);
}
function error_message(msg) {
	var code = "<ul class=\"rainbow-system-messages\"><li style=\"opacity: 0.9;\" class='rainbow-message rainbow-state-error'>"+msg+"</li></ul>";
	_("system_messages").style.display = "block";
	_("system_messages").innerHTML = code;
	setTimeout(function() {
		
		if (ctrl_key == 'off')
    		_("system_messages").style.display = 'none';
	}, 5000);
}

function ttranslate(e) {
	if (ctrl_key == 'off') return;
	e.style.display = 'none';
	n = _(e.id + "-h");
	i = _(e.id + "-n");

	n.style.display = "block";
	i.focus();
	i.select();
}
function form_translate() {
	alert('translation from form');
	return false;

}
function getPageHeight() {
var w = window,
    d = document,
    e = d.documentElement,
    g = d.getElementsByTagName('body')[0],
    x = w.innerWidth || e.clientWidth || g.clientWidth,
    y = w.innerHeight|| e.clientHeight|| g.clientHeight;
	return y;
}


var alt_key = 'off';

function ttranslate_post(e){
	var data = e.id.split("-");
	var rpage = data[0];
	var rkey = data[1];
	var ttranslation = e.value;
	
	var ajax = ajaxObj("POST", "/action/translator/translator.php");
    
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			
	    	if(ajax.responseText == "translation_error"){
	        	
	        	error_message(ajax.responseText);
			
			} else {
				_(rpage+"-"+rkey+"-h").style.display = 'none';
				_(rpage+"-"+rkey).innerHTML = ajax.responseText;
				_(rpage+"-"+rkey).style.display = 'block';
			}
			
	    }   
    }
    ajax.send("page="+rpage+"&key="+rkey+"&translation="+ttranslation);
    

}
var ctrl_key = 'off';
var enter_key = 'off';

function keyPress(e) {
	//alert(e);
	if (e == 17)ctrl_key='on';
	if (e == 18)alt_key='on';
	if (e == 13)enter_key='on';
	activity =1;
}
function keyRelease(e) {
	if (e == 17)ctrl_key='off';
	if (e == 18)alt_key='off';
	if (e == 13)enter_key='off';


}
function translate_enter_key() {
	if (enter_key == 'on')
	alert("Translator, click outside of input box to save translation. Do not press ENTER key to save changes!");
	enter_key = 'off';


}

setTimeout(function() {
	if (ctrl_key == 'off')
    	_("system_messages").style.display = 'none';
}, 4000);


function resize_textarea(t,cols) {
	//ert('resizing');
a = t.value.split('\n');
b=1;
for (x=0;x < a.length; x++) {

 	if (a[x].length >= cols) b+= Math.floor(a[x].length/cols);
 	//system_message('a[x] length: '+a[x].length + " t.cols: "+cols + " b:"+b);

 }
b+= a.length;
if (b > t.rows) t.rows = b;
}

//TODO: key combo triggers
/* for combination.. todo
function KeyPress(e){
 for (i=0;i<KPAry.length;i++){
  if (e==KPAry[i]){
   return;
  }
 }
 if (e!=17&&e!=18&&e!=82){
  KPAry=new Array();
 }
 KPAry[KPAry.length]=e;
 if (KPAry.length==3){
  if (KPAry[0]==17&&KPAry[1]==18&&KPAry[2]==82){
   alert('Keys Pressed\nctrl+alt+R ');
  }
  KPAry=new Array();

 }
}
*/
//************* POP UP BOX HANDLING ****************/
function findPos(obj) {
	var rect = obj.getBoundingClientRect();
	//console.log(rect.top, rect.right, rect.bottom, rect.left);
	//alert('Top: '+rect.top+ "Bottom: "+rect.bottom+" Left: "+rect.left);
	
	var curleft = curtop = 0;
	/*
	if (obj.offsetParent) {
		do {
			curleft += obj.offsetLeft;
			curtop += obj.offsetTop;
			} while (obj = obj.offsetParent);
			alert("LEFT: "+curleft+" TOP: "+curtop);
			//return [curleft,curtop];
			//return curtop;
		}
	*/
	return [rect.left,rect.bottom];
	

}

function showBox(e,target,left,top,msg) {
	//alert(e);
	//e = _(e);
	if (boxtimer!=null)
		clearTimeout(boxtimer);
	pos = findPos(e);
	
	pos[0] += left;
	pos[1] += top;

	tp = _(target);
	tp.style.position = 'fixed';
	tp.style.top = pos[1] + 'px';
	tp.style.left = pos[0] + 'px';
	if (msg == ''){
		_(target+'_content').innerHTML = "";
		_(target+'_loading').style.display = 'block';
	}else{
		_(target+'_loading').style.display = 'none';
    	_(target+'_content').innerHTML = msg;
    }     
	messaging = false;	
	tp.style.display = 'block';

}


var mousein = false;
var boxtimer;
// This is called when moving out of the trigger that called the
// pop up box. This closes the box only and only if the user has
// not moved the cursor into the box..
function onTriggerMouseOut(event,target) {
	mousein=false;
	
	boxtimer = setTimeout(function() {
		if (mousein == true) return;
		onBoxMouseOut(event,target);
	}, 300);

}


function onBoxMouseIn() {
	mousein = true;
	clearTimeout(boxtimer);
}

var messaging = false;
// Function to close the pop up box called from the trigger
function onBoxMouseOut(event,target) {
	if (messaging == true) return; // user is typing a message do not close the box
	// gets the box defined by target
	k = document.getElementById(target);
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

//*************** END OF POPUP BOX CODE ********************/


document.onkeyup=function(evt){ keyRelease(evt?evt.keyCode:event.keyCode); }

document.onkeydown=function(evt){ keyPress(evt?evt.keyCode:event.keyCode); }
