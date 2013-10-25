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
$CONFIG->dbpass = 'admin';
$CONFIG->dbname = 'rainbow';
$CONFIG->dbhost = 'localhost';
$CONFIG->dbprefix = '';

/**
 * The site parameters configuration
 */
$CONFIG->site_name = 'World Rainbow Family';
$CONFIG->root = '/home/rainbow/';
$CONFIG->home = $CONFIG->root . 'world/dev/';
$CONFIG->data = $CONFIG->root . 'data/';
$CONFIG->site = 'https://www.worldrainbowfamily.org/dev';
$CONFIG->language = 'en';

/**
 * Logging parameters
 */
$CONFIG->ilog = $CONFIG->root .'info.log';
$CONFIG->elog = $CONFIG->root .'fatal.log';




?>