<link rel="stylesheet" href="<?=$CONFIG->site?>/page/profile/profile.css" type="text/css">
<?php
include ($CONFIG->home . 'page/feed/head.php');

?>
<script type='text/javascript'>

function checkLocation() {
	var country_code = _("user-countrycode").value;
	var country = _("user-country").value;
	var region = _("user-region").value;
	var city = _("user-city").value;
	var ok = false;
	
	var ajax = new XMLHttpRequest();
	ajax.open( "POST", "/action/profile/checklocation_ajx.php", false );
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    			alert("invalid location");
	    			
				} else {
					var s = ajax.responseText;
					var location = s.split('**');
					var region = location[1];
					var city = location[0];

					_("user-region").value = region;
					_("user-city").value = city;
				
					//alert("Found "+location[0]+ ' '+location[1]+ ' '+location[2]);
					ok = true;
				}
	        }
        }
       ajax.send("country="+country+"&countrycode="+country_code+"&region="+region+"&city="+city);
       return ok;
}

function updateLocation() {

	if (!checkLocation()) {
		return false;
	}

	var continent = _("user-continent").value;
	var country = _("user-country").value;
	var country_code = _("user-countrycode").value;
	var region = _("user-region").value;
	var city = _("user-city").value;


	var ajax = ajaxObj("POST", "/action/profile/updatelocation_ajx.php");
	//_("ajax_loader").style.display = "block";
     ajax.onreadystatechange = function() {
	 	if(ajaxReturn(ajax) == true) {
	    	if(ajax.responseText == "error"){
	    		system_message("Error!");	
				} else {
					_("userlocation").innerHTML = ajax.responseText;
					_("set-location").style.display = "none";
				}
	        }
        }
       ajax.send("continent="+continent+"&country="+country+"&countrycode="+country_code+"&region="+region+"&city="+city);
}


</script>
