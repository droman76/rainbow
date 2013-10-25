# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.13)
# Database: rainbow
# Generation Time: 2013-10-25 17:11:35 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table blockedusers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blockedusers`;

CREATE TABLE `blockedusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blocker` varchar(16) NOT NULL,
  `blockee` varchar(16) NOT NULL,
  `blockdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table feed
# ------------------------------------------------------------

DROP TABLE IF EXISTS `feed`;

CREATE TABLE `feed` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `group_id` varchar(100) DEFAULT NULL,
  `user_name` varchar(200) NOT NULL,
  `user_full_name` varchar(128) NOT NULL,
  `user_profile_url` varchar(256) NOT NULL,
  `action` varchar(50) DEFAULT NULL,
  `action_object` varchar(100) DEFAULT NULL,
  `user_pic_url` varchar(256) NOT NULL,
  `message` text NOT NULL,
  `tags` varchar(20) DEFAULT NULL,
  `post_url` varchar(256) NOT NULL,
  `post_pic_url` varchar(256) NOT NULL,
  `post_type` varchar(50) DEFAULT NULL,
  `link_src` varchar(256) NOT NULL,
  `link_title` varchar(250) DEFAULT NULL,
  `link_description` text,
  `link_video_src` text,
  `source` varchar(11) NOT NULL,
  `external_source_id` varchar(50) DEFAULT '',
  `external_userid` varchar(100) DEFAULT NULL,
  `external_post_id` varchar(50) DEFAULT '',
  `external_source_name` varchar(100) DEFAULT '',
  `date` int(11) NOT NULL,
  `last_cron` int(11) DEFAULT NULL,
  `parent` varchar(50) NOT NULL,
  `view` varchar(50) NOT NULL DEFAULT '',
  `continent_code` varchar(11) NOT NULL,
  `country_code` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `a` char(1) DEFAULT '',
  `b` char(1) DEFAULT '',
  `c` char(1) DEFAULT '',
  `e` char(1) DEFAULT '',
  `f` char(1) DEFAULT '',
  `g` char(1) DEFAULT '',
  `l` char(1) DEFAULT '',
  `m` char(1) DEFAULT '',
  `n` char(1) DEFAULT '',
  `o` char(1) DEFAULT '',
  `p` char(1) DEFAULT '',
  `r` char(1) DEFAULT '',
  `s` char(1) DEFAULT '',
  PRIMARY KEY (`post_id`),
  UNIQUE KEY `post_id` (`post_id`),
  KEY `external_post_id` (`external_post_id`),
  KEY `group_id` (`group_id`,`post_id`),
  FULLTEXT KEY `message` (`message`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table feed_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `feed_comments`;

CREATE TABLE `feed_comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_full_name` varchar(128) NOT NULL,
  `user_profile_url` varchar(256) NOT NULL,
  `user_pic_url` varchar(256) NOT NULL,
  `message` text NOT NULL,
  `scope` varchar(11) NOT NULL,
  `link_src` varchar(256) NOT NULL,
  `link_title` varchar(250) DEFAULT NULL,
  `link_description` text,
  `link_video_src` text,
  `source` varchar(11) NOT NULL,
  `date` int(11) NOT NULL,
  `last_update` int(11) NOT NULL,
  `continent_code` varchar(11) DEFAULT '',
  `country_code` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`comment_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table following
# ------------------------------------------------------------

DROP TABLE IF EXISTS `following`;

CREATE TABLE `following` (
  `source_id` int(11) NOT NULL DEFAULT '0',
  `source_obj` char(11) NOT NULL DEFAULT '0',
  `user_id_following` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`source_id`,`source_obj`,`user_id_following`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table friends
# ------------------------------------------------------------

DROP TABLE IF EXISTS `friends`;

CREATE TABLE `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1` varchar(16) NOT NULL,
  `user2` varchar(16) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `datemade` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `accepted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table group_members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `group_members`;

CREATE TABLE `group_members` (
  `groupid` varchar(100) NOT NULL DEFAULT '',
  `userid` int(11) NOT NULL DEFAULT '0',
  `role` varchar(11) NOT NULL DEFAULT '',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`groupid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(200) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `visibility` varchar(11) NOT NULL DEFAULT '',
  `access` varchar(11) NOT NULL DEFAULT '',
  `postaccess` varchar(11) NOT NULL DEFAULT '',
  `moderation` varchar(11) NOT NULL DEFAULT '',
  `creator` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(100) DEFAULT '',
  `topic` char(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table karma
# ------------------------------------------------------------

DROP TABLE IF EXISTS `karma`;

CREATE TABLE `karma` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `good_karma` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table likes
# ------------------------------------------------------------

DROP TABLE IF EXISTS `likes`;

CREATE TABLE `likes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) DEFAULT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table messaging
# ------------------------------------------------------------

DROP TABLE IF EXISTS `messaging`;

CREATE TABLE `messaging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent` int(11) NOT NULL,
  `seen` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipient` (`recipient`),
  KEY `sender` (`sender`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table migration
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migration`;

CREATE TABLE `migration` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `username` varchar(128) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `salt` varchar(8) NOT NULL DEFAULT '',
  `email` text NOT NULL,
  `language` varchar(6) NOT NULL DEFAULT '',
  `code` varchar(32) NOT NULL DEFAULT '',
  `banned` enum('yes','no') NOT NULL DEFAULT 'no',
  `admin` enum('yes','no') NOT NULL DEFAULT 'no',
  `last_action` int(11) NOT NULL DEFAULT '0',
  `prev_last_action` int(11) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL DEFAULT '0',
  `prev_last_login` int(11) NOT NULL DEFAULT '0',
  `country` varchar(100) DEFAULT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`guid`),
  UNIQUE KEY `username` (`username`),
  KEY `password` (`password`),
  KEY `email` (`email`(50)),
  KEY `code` (`code`),
  KEY `last_action` (`last_action`),
  KEY `last_login` (`last_login`),
  KEY `admin` (`admin`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `name_2` (`name`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table notifications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `target` varchar(100) NOT NULL DEFAULT '',
  `object` varchar(50) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `note` varchar(255) NOT NULL,
  `sent` int(11) DEFAULT NULL,
  `seen` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipient` (`recipient`),
  KEY `sender` (`sender`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table photos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `photos`;

CREATE TABLE `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(16) NOT NULL,
  `gallery` varchar(16) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `uploaddate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `status`;

CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osid` int(11) NOT NULL,
  `account_name` varchar(16) NOT NULL,
  `author` varchar(16) NOT NULL,
  `type` enum('a','b','c') NOT NULL,
  `data` text NOT NULL,
  `postdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table useroptions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `useroptions`;

CREATE TABLE `useroptions` (
  `id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `background` varchar(255) NOT NULL,
  `question` varchar(255) DEFAULT NULL,
  `answer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table userprofile
# ------------------------------------------------------------

DROP TABLE IF EXISTS `userprofile`;

CREATE TABLE `userprofile` (
  `userid` int(11) NOT NULL,
  `bio` text NOT NULL,
  `profile_pic` varchar(250) NOT NULL,
  `skills` text NOT NULL,
  `interests` text NOT NULL,
  `contact` varchar(200) NOT NULL,
  `website` varchar(200) NOT NULL,
  `intro` varchar(250) NOT NULL DEFAULT '',
  `birthdate` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL DEFAULT '',
  `roles` varchar(100) DEFAULT NULL,
  `proxy` int(11) DEFAULT '0',
  `extid` varchar(150) DEFAULT '',
  `website` varchar(255) DEFAULT NULL,
  `continent_code` varchar(10) DEFAULT '',
  `country` varchar(255) DEFAULT NULL,
  `region` varchar(100) DEFAULT '',
  `city` varchar(100) DEFAULT '',
  `country_code` varchar(4) DEFAULT '',
  `longitude` varchar(100) DEFAULT '',
  `latitude` varchar(100) DEFAULT '',
  `language` varchar(10) DEFAULT 'en',
  `userlevel` enum('a','b','c','d') DEFAULT 'a',
  `avatar` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT '',
  `activated` enum('0','1') NOT NULL DEFAULT '0',
  `logincount` int(11) DEFAULT NULL,
  `postcount` int(11) DEFAULT NULL,
  `trustlevel` int(11) DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `browser_version` varchar(100) DEFAULT NULL,
  `platform` varchar(100) DEFAULT NULL,
  `lastlogin` int(11) DEFAULT NULL,
  `signup` int(11) DEFAULT NULL,
  `notescheck` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table wall
# ------------------------------------------------------------

DROP TABLE IF EXISTS `wall`;

CREATE TABLE `wall` (
  `post_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `scope` int(11) DEFAULT NULL,
  `postdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
