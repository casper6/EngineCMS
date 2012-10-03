<?php
require_once("mainfile.php");
global $prefix;



/*
$db->sql_query("select keywords from `".$prefix."_pages`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages` ADD `description` VARCHAR( 200 ) NOT NULL , ADD `keywords` text NOT NULL ;");
$db->sql_query("select rss from `".$prefix."_pages`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages` ADD `rss` CHAR( 1 ) DEFAULT '1' NOT NULL AFTER `mainpage` ;");
$db->sql_query("select tables from `".$prefix."_pages_categories`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages_categories` ADD `tables` VARCHAR( 255 ) DEFAULT 'pages' NOT NULL ;");
$db->sql_query("select counter from `".$prefix."_mainpage`;") or $db->sql_query("ALTER TABLE `".$prefix."_mainpage` ADD `counter` INT( 10 ) DEFAULT '0' NOT NULL ;");
$db->sql_query("select tables from `".$prefix."_pages_golos`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages_golos` ADD `tables` VARCHAR( 255 ) DEFAULT 'pages' NOT NULL ;");
$db->sql_query("select postlink from `".$prefix."_config`;") or $db->sql_query("ALTER TABLE `".$prefix."_config` ADD `postlink` VARCHAR( 255 ) NOT NULL AFTER `statlink` ;");
$db->sql_query("select stopcopy from `".$prefix."_config`;") or $db->sql_query("ALTER TABLE `".$prefix."_config` ADD `stopcopy` INT( 1 ) DEFAULT '0' NOT NULL ;");
$db->sql_query("select mail from `".$prefix."_pages_comments`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages_comments` ADD `mail` VARCHAR( 255 ) NOT NULL AFTER `avtor` ;");
$db->sql_query("select tables from `".$prefix."_pages_comments`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages_comments` ADD `tables` VARCHAR( 255 ) DEFAULT 'pages' NOT NULL ;");
$db->sql_query("select tables from `".$prefix."_mainpage`;") or $db->sql_query("ALTER TABLE `".$prefix."_mainpage` ADD `tables` VARCHAR( 255 ) DEFAULT 'pages' NOT NULL ;");
$db->sql_query("select golos from `".$prefix."_pages_comments`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages_comments` ADD `golos` INT( 2 ) DEFAULT '0' NOT NULL AFTER `data` ;");
$db->sql_query("select drevo from `".$prefix."_pages_comments`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages_comments` ADD `drevo` INT( 10 ) DEFAULT '0' NOT NULL ;");
$db->sql_query("select adres from `".$prefix."_pages_comments`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages_comments` ADD `adres` TEXT NOT NULL , ADD `tel` VARCHAR( 255 ) NOT NULL , ADD `active` INT( 1 ) DEFAULT '1' NOT NULL ;");
$db->sql_query("ALTER TABLE `".$prefix."_spiski` CHANGE `name` `name` TEXT");
$db->sql_query("select tables from `".$prefix."_pages`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages` ADD `tables` VARCHAR( 255 ) DEFAULT 'pages' NOT NULL ;");
$db->sql_query("select copy from `".$prefix."_pages`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages` ADD `copy` INT( 10 ) DEFAULT '0' NOT NULL ;");
$db->sql_query("select color from `".$prefix."_mainpage`;") or $db->sql_query("ALTER TABLE `".$prefix."_mainpage` ADD `color` VARCHAR( 255 ) DEFAULT '0' NOT NULL ;");
$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_optimize_gain`;");
$db->sql_query("CREATE TABLE `".$prefix."_bases` ( `id` INT( 10 ) NOT NULL AUTO_INCREMENT , `base` VARCHAR( 255 ) NOT NULL , `data` DATETIME NOT NULL , `user` VARCHAR( 255 ) DEFAULT 'Администратор' NOT NULL , `pass` VARCHAR( 255 ) NOT NULL , `pause` VARCHAR( 1 ) DEFAULT '0' NOT NULL ,PRIMARY KEY ( `id` ));");
$db->sql_query("CREATE TABLE `".$prefix."_cash` (`id` INT( 10 ) NOT NULL AUTO_INCREMENT, `url` TEXT NOT NULL, `data` DATETIME NOT NULL, `text` TEXT NOT NULL, PRIMARY KEY ( `id` ));");
$db->sql_query("select info from `".$prefix."_bases`;") or $db->sql_query("ALTER TABLE `".$prefix."_bases` ADD `info` TEXT NOT NULL ;");
$db->sql_query("select nocashe from `".$prefix."_config`;") or $db->sql_query("ALTER TABLE `".$prefix."_config` ADD `nocashe` text NOT NULL;");
$db->sql_query("ALTER TABLE `".$prefix."_cash` CHANGE `text` `text` MEDIUMTEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci");
$db->sql_query("select `sort` from `".$prefix."_pages`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages` ADD `sort` INT( 10 ) DEFAULT '0' NOT NULL ;");
$db->sql_query("select `redate` from `".$prefix."_pages`;") or x();
$db->sql_query("select `adminmes` from `".$prefix."_config`;") or $db->sql_query("ALTER TABLE `".$prefix."_config` ADD `adminmes` TEXT NOT NULL ;");
$db->sql_query("CREATE TABLE `".$prefix."_search` (`id` INT(10) NOT NULL AUTO_INCREMENT ,`ip` VARCHAR(20) NOT NULL ,`slovo` VARCHAR(255) NOT NULL ,`data` datetime NOT NULL ,`pages` VARCHAR(20) NOT NULL ,PRIMARY KEY (`id`));");
$db->sql_query("CREATE TABLE `".$prefix."_banner` (`id` INT( 10 ) NOT NULL AUTO_INCREMENT,`link` VARCHAR( 255 ) NOT NULL ,`ru` VARCHAR( 255 ) NOT NULL ,`en` VARCHAR( 255 ) NOT NULL ,`ip` TEXT NOT NULL ,`kol` INT( 10 ) NOT NULL ,`data` datetime NOT NULL, PRIMARY KEY ( `id` ));");

$db->sql_query("select `description` from `".$prefix."_mainpage`;") or $db->sql_query("ALTER TABLE `".$prefix."_mainpage` ADD `description` VARCHAR( 255 ) NOT NULL, ADD `keywords` VARCHAR( 255 ) NOT NULL");

$db->sql_query("select `red` from `".$prefix."_config`;") or $db->sql_query("ALTER TABLE `".$prefix."_config` ADD `red` ENUM('0', '1', '2', '3') NOT NULL");
$db->sql_query("UPDATE `".$prefix."_config` SET `red` = '3' WHERE `red` = '0' LIMIT 1 ;");

$db->sql_query("select `comment` from `".$prefix."_config`;") or $db->sql_query("ALTER TABLE `".$prefix."_config` ADD `comment` ENUM('0', '1') NOT NULL");
$db->sql_query("select `captcha_ok` from `".$prefix."_config`;") or $db->sql_query("ALTER TABLE `".$prefix."_config` ADD `captcha_ok` ENUM('0', '1') NOT NULL");

$db->sql_query("ALTER TABLE `".$prefix."_mainpage` MODIFY COLUMN `color` integer AFTER `tables`;");

$db->sql_query("ALTER TABLE `".$prefix."_config` 
CHANGE `registr` `registr` TINYINT( 1 ) NOT NULL ,
CHANGE `pogoda` `pogoda` TINYINT( 1 ) NOT NULL ,
CHANGE `flash` `flash` TINYINT( 1 ) NOT NULL ,
CHANGE `sgatie` `sgatie` TINYINT( 1 ) NOT NULL ,
CHANGE `comment` `comment` TINYINT( 1 ) NOT NULL ,
CHANGE `captcha_ok` `captcha_ok` TINYINT( 1 ) NOT NULL ,
CHANGE `startdate` `startdate` SMALLINT( 1 ) NOT NULL ,
CHANGE `keywords` `keywords` VARCHAR( 500 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL ,
CHANGE `description` `description` VARCHAR( 500 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL ,
CHANGE `counter` `counter` VARCHAR( 5000 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL ,
CHANGE `adminmes` `adminmes` VARCHAR( 30000 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL ,
CHANGE `red` `red` TINYINT( 1 ) NOT NULL ,
CHANGE `stopcopy` `stopcopy` TINYINT( 1 ) NOT NULL");

$db->sql_query("ALTER TABLE `".$prefix."_mainpage` 
CHANGE `tables` `tables` ENUM( 'pages', 'del', 'backup' ) NOT NULL DEFAULT 'pages',
CHANGE `color` `color` TINYINT( 1 ) NULL,
CHANGE `type` `type` TINYINT( 1 ) NOT NULL");

// sort - SMALLINT макс. число 32767
$db->sql_query("ALTER TABLE `".$prefix."_pages` 
CHANGE `cid` `cid` MEDIUMINT( 6 ) NOT NULL,
CHANGE `active` `active` TINYINT( 1 ) NOT NULL,
CHANGE `mainpage` `mainpage` TINYINT( 1 ) NOT NULL,
CHANGE `rss` `rss` TINYINT( 1 ) NOT NULL,
CHANGE `price` `price` DECIMAL( 10, 2 ) NOT NULL,
CHANGE `sort` `sort` SMALLINT( 5 ) NOT NULL,
CHANGE `counter` `counter` MEDIUMINT( 6 ) NOT NULL,
CHANGE `golos` `golos` MEDIUMINT( 6 ) NOT NULL,
CHANGE `comm` `comm` MEDIUMINT( 6 ) NOT NULL,
CHANGE `title` `title` VARCHAR( 2000 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL,
CHANGE `date` `date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `redate` `redate` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
CHANGE `tables` `tables` ENUM( 'pages', 'del', 'backup' ) NOT NULL DEFAULT 'pages'");

$db->sql_query("ALTER TABLE `".$prefix."_pages_categories` 
CHANGE `cid` `cid` MEDIUMINT( 6 ) NOT NULL AUTO_INCREMENT ,
CHANGE `sort` `sort` SMALLINT( 5 ) NOT NULL,
CHANGE `counter` `counter` MEDIUMINT( 6 ) NOT NULL,
CHANGE `parent_id` `parent_id` MEDIUMINT( 6 ) NOT NULL,
CHANGE `tables` `tables` ENUM( 'pages', 'del', 'backup' ) NOT NULL DEFAULT 'pages'");

$db->sql_query("ALTER TABLE `".$prefix."_pages_comments` 
CHANGE `avtor` `avtor` VARCHAR( 255 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL ,
CHANGE `ip` `ip` VARCHAR( 15 ) NOT NULL ,
CHANGE `data` `data` VARCHAR( 20 ) NOT NULL ,
CHANGE `golos` `golos` SMALLINT( 2 ) NULL DEFAULT '0',
CHANGE `tables` `tables` ENUM( 'pages', 'del', 'backup' ) NOT NULL DEFAULT 'pages',
CHANGE `adres` `adres` VARCHAR( 2000 ) CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL ,
CHANGE `active` `active` TINYINT( 1 ) NOT NULL DEFAULT '1'");

$db->sql_query("ALTER TABLE `".$prefix."_pages_golos` 
CHANGE `ip` `ip` VARCHAR( 15 ) NOT NULL ,
CHANGE `golos` `golos` SMALLINT( 2 ) NOT NULL DEFAULT '0',
CHANGE `tables` `tables` ENUM( 'pages', 'del', 'backup' ) NOT NULL DEFAULT 'pages'");

$db->sql_query("ALTER TABLE `".$prefix."_cash` CHANGE `url` `url` VARCHAR( 300 ) NOT NULL");

$db->sql_query("select `link` from `".$prefix."_authors`;") or $db->sql_query("ALTER TABLE `".$prefix."_authors` ADD `link` VARCHAR( 250 ) NOT NULL, ADD  `all` ENUM( '0', '1') NOT NULL");

*/
# ////////////////////////////////////////////////////////////////

$db->sql_query("ALTER TABLE `".$prefix."_pages` DROP INDEX `pid`");
$db->sql_query("ALTER TABLE `".$prefix."_banned_ip` DROP INDEX `id`");
$db->sql_query("ALTER TABLE `".$prefix."_authors` DROP INDEX `aid`");
$db->sql_query("ALTER TABLE `".$prefix."_pages_categories` DROP INDEX `cid`");

$db->sql_query("select ava from `".$prefix."_pages_comments`;") or $db->sql_query("ALTER TABLE `".$prefix."_pages_comments` ADD `ava` VARCHAR( 255 ) NOT NULL AFTER `avtor` ;");

$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_shop`;");
$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_banner`;");
$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_session`;");
$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_users`;");
$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_users_temp`;");

$db->sql_query("select `ht_backup` from `".$prefix."_config`;") or $db->sql_query("ALTER TABLE `".$prefix."_config` ADD `ht_backup` VARCHAR( 255 ) NOT NULL");
$db->sql_query("UPDATE `".$prefix."_config` SET `ht_backup` = '.ht_backup' LIMIT 1 ;");
$db->sql_query("ALTER TABLE `".$prefix."_config` CHANGE `sgatie` `sgatie` MEDIUMTEXT NOT NULL;");

print ("<center><h2>Обновление базы данных окончено!</h2><br>");
?>