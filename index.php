
<?php
	session_start();

	include_once('config.php');
	include_once('lib/database.php');
	include_once('lib/functions.php');
	include_once('lib/pagehandler.php');
	include_once('lib/babelfish.php');
	include_once('lib/geolocation.php');


	

	// Load the user laguage if it is not yet set in session...
	if (!isset($_SESSION['user_language'])) {
		if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) && strlen($_SERVER["HTTP_ACCEPT_LANGUAGE"]) > 2){
    		$lang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0,2);
    		$_SESSION['user_language'] = $lang;
    	}
    	else $_SESSION['user_language'] = 'en';
    }
    if (!isset($_SESSION['browser'])) {
    	$ua=getBrowser();
		$_SESSION['browser'] = $ua['name'];
		$_SESSION['browser_v'] = $ua['version'];
		$_SESSION['platform'] = $ua['platform'];
    }

    ilog("******************* Browser: ".$_SESSION['browser']." V:".$_SESSION['browser_v']." on ".$_SESSION['platform']." || Lang: ".$_SESSION['user_language'].' ***********************');
	
	header('Content-Type: text/html; charset=UTF-8'); 
	
	$_SESSION['home'] = $CONFIG->home;
	$_SESSION['data'] = $CONFIG->data;
	$_SESSION['userdata'] = $CONFIG->userdata;


	/*
	 * ADD REQUESTER LOCATION TO SESSION
	 */
	geolocate();

	/*
	 * PAGE HANDLING & MAPPING LOADING
	 * determines if a page is overriden by parameters such as userlogin log in
	 * @location lib/functions.php
	 */
	$rpage = page_controller();
	
  	$_SESSION['rpage'] = $rpage;


	$handler = new PageHandler($rpage);
	
	// Log information

	 ilog("Routing Page: $rpage");
	/*
	 ilog("Header Handler: ". $handler->getHeader());
	 ilog("Content Handler: ".$handler->getContent());
	*/

	/*
	 * LOAD LANGUAGE FILE
	 */
	$babel = new BabelFish($rpage);


	/*
	 * LOAD THE TEMPLATE
	 */

	$template = $handler->getTemplate();
	$layout = $handler->getLayout(); 
	

	include('template/'.$template.'/'.$template.'.php');

	/*
	 * FINAL CLEANUP
	 */

	cleanup();





?>