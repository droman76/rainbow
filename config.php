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
 * The database parameters configuration
 */

$CONFIG->dbuser = 'root';
$CONFIG->dbpass = '';
$CONFIG->dbname = 'rainbow';
$CONFIG->dbhost = 'localhost';
$CONFIG->dbprefix = '';

/**
 * The site parameters configuration
 */
$CONFIG->site_name = 'World Rainbow Family';
$CONFIG->site_mail = 'focalizer@worldrainbowfamily.org';
$CONFIG->root = '/Users/daniel/Sites/rainbow/';
$CONFIG->home = $CONFIG->root . 'dev/';
$CONFIG->data = $CONFIG->root . 'data/';
$CONFIG->userdata = $CONFIG->data . 'users/';

$CONFIG->site = 'http://local.worldrainbowfamily.org';
$CONFIG->language = 'en';	
$CONFIG->siteroot = '/';

/**
 * Logging parameters
 */
$CONFIG->ilog = $CONFIG->root .'info.log';
$CONFIG->elog = $CONFIG->root .'fatal.log';

$_SESSION['CONFIG'] = $CONFIG;


?>