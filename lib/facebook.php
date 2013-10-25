<?php


function makeRequest($url, $params, $ch=null) {
/*
    curl -F 'access_token=...' \
     -F 'message=Hello. I like this new API.' \
     https://graph.facebook.com/[USER_ID]/feed
     */
     
     $curl = curl_init();
// Set some options - we are passing in a useragent too here
	curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_URL => $url,
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => $params
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	error_log("CURL RESPONSE: $resp");
	
	
	
    if ($resp === false) {
    	error_log("Error: ".curl_error($curl));
    	return false;
    }
    else {
    	error_log("success!!! returning good.");
    	return $resp;
    }
    // Close request to clear up some resources
	curl_close($curl);
  }


         
?>

