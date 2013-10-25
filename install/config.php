<?php
/**
 * Defines global configuration
 *
 */

$CONFIG = NULL;

if (!isset($CONFIG)) {
	$CONFIG = new stdClass;
}

/**
 * The database parameters configuration, fill in blank spaces with correct values
 */

$CONFIG->dbuser = '';
$CONFIG->dbpass = '';
$CONFIG->dbname = '';
$CONFIG->dbhost = '';
$CONFIG->dbprefix = '';

/**
 * The site parameters configuration will in <..> with correct values
 */
$CONFIG->site_name = 'World Rainbow Family';
$CONFIG->site_mail = '<email@blablabla>';
$CONFIG->root = '<root>/';
$CONFIG->home = $CONFIG->root . '/';
$CONFIG->data = $CONFIG->root . 'data/';
$CONFIG->userdata = $CONFIG->data . 'users/';

$CONFIG->site = 'http://www..org';
$CONFIG->language = 'en';	
$CONFIG->siteroot = '/';

/**
 * Logging parameters
 */
$CONFIG->ilog = $CONFIG->root .'info.log';
$CONFIG->elog = $CONFIG->root .'fatal.log';

$_SESSION['CONFIG'] = $CONFIG;


?>