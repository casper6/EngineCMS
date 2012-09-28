<?php
require_once("mainfile.php");
global $prefix;


$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_authors`;");
$db->sql_query("CREATE TABLE `".$prefix."_authors` (
 `aid` varchar(25) NOT NULL,
 `name` varchar(50),
 `pwd` varchar(40),
 `realadmin` tinyint(1) DEFAULT '1',
 PRIMARY KEY (`aid`),
 KEY aid (`aid`)
);");


$db->sql_query("INSERT INTO `".$prefix."_authors` VALUES ( 'new', 'BOG', '43c16d460b053cdc0fea85dd5f25edf8', '1');");

$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_banned_ip`;");
$db->sql_query("CREATE TABLE `".$prefix."_banned_ip` (
 `id` int(11) NOT NULL auto_increment,
 `ip_address` varchar(15) NOT NULL,
 `reason` varchar(255) NOT NULL,
 `date` date DEFAULT '0000-00-00' NOT NULL,
 PRIMARY KEY (`id`),
 KEY id (`id`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_banner`;");
$db->sql_query("CREATE TABLE `".$prefix."_banner` (
 `id` int(10) NOT NULL auto_increment,
 `link` varchar(255) NOT NULL,
 `ru` varchar(255) NOT NULL,
 `en` varchar(255) NOT NULL,
 `ip` text NOT NULL,
 `kol` int(10) NOT NULL,
 `data` datetime NOT NULL,
 PRIMARY KEY (`id`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_bases`;");
$db->sql_query("CREATE TABLE `".$prefix."_bases` (
 `id` int(10) NOT NULL auto_increment,
 `base` varchar(255) NOT NULL,
 `data` datetime NOT NULL,
 `user` varchar(255) DEFAULT 'Администратор' NOT NULL,
 `pass` varchar(255) NOT NULL,
 `pause` varchar(1) DEFAULT '0' NOT NULL,
 `info` text NOT NULL,
 PRIMARY KEY (`id`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_cash`;");
$db->sql_query("CREATE TABLE `".$prefix."_cash` (
 `id` int(10) NOT NULL auto_increment,
 `url` text NOT NULL,
 `data` datetime NOT NULL,
 `text` mediumtext,
 PRIMARY KEY (`id`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_config`;");
$db->sql_query("CREATE TABLE `".$prefix."_config` (
 `sitename` varchar(255) NOT NULL,
 `startdate` varchar(50) NOT NULL,
 `adminmail` varchar(255) NOT NULL,
 `keywords` varchar(255) NOT NULL,
 `description` varchar(255) NOT NULL,
 `counter` text NOT NULL,
 `statlink` varchar(255) NOT NULL,
 `postlink` varchar(255) NOT NULL,
 `registr` varchar(1) DEFAULT '0' NOT NULL,
 `pogoda` varchar(1) DEFAULT '0' NOT NULL,
 `flash` varchar(1) DEFAULT '0' NOT NULL,
 `sgatie` varchar(1) DEFAULT '1' NOT NULL,
 `stopcopy` int(1) DEFAULT '0' NOT NULL,
 `nocashe` text NOT NULL,
 `adminmes` text NOT NULL,
 `red` enum('0','1','2','3') NOT NULL,
 `comment` enum('0','1') NOT NULL,
 `captcha_ok` enum('0','1') NOT NULL,
 PRIMARY KEY (`sitename`)
);");


$db->sql_query("INSERT INTO `".$prefix."_config` VALUES ( 'Название сайта', '2012', 'mail@mail.ru', 'ключевые, слова', 'Описание сайта', 'Код счетчика вставить сюда — вывести его в дизайне можно через блок [статистика]', 'http://www.liveinternet.ru/stat/', '', '0', '0', '0', '0', '0', '', '', '3', '0', '0');");

$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_golos`;");
$db->sql_query("CREATE TABLE `".$prefix."_golos` (
 `gid` int(10) NOT NULL auto_increment,
 `ip` varchar(15) NOT NULL,
 `golos` text NOT NULL,
 `num` int(10) NOT NULL,
 `data` varchar(19) NOT NULL,
 PRIMARY KEY (`gid`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_mainpage`;");
$db->sql_query("CREATE TABLE `".$prefix."_mainpage` (
 `id` int(10) NOT NULL auto_increment,
 `type` varchar(255) NOT NULL,
 `name` varchar(255) NOT NULL,
 `title` varchar(255) NOT NULL,
 `text` text NOT NULL,
 `useit` text,
 `shablon` text NOT NULL,
 `counter` int(10) DEFAULT '0' NOT NULL,
 `tables` varchar(255) DEFAULT 'pages' NOT NULL,
 `color` int(11),
 `description` varchar(255) NOT NULL,
 `keywords` varchar(255) NOT NULL,
 PRIMARY KEY (`id`)
);");


$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '1', '0', '', 'Главный дизайн', 'шапка[содержание]футер', '20', '', '0', 'pages', '0', '', '');");

$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '24', '2', 'index', 'Главная страница', 'pages|design=1', 'Скоро открытие... ', '', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '20', '1', 'index', 'Главный стиль', '', '', '', '0', 'pages', '0', '', '');");


$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_pages`;");
$db->sql_query("CREATE TABLE `".$prefix."_pages` (
 `pid` int(10) NOT NULL auto_increment,
 `module` varchar(255) NOT NULL,
 `cid` int(10) DEFAULT '0' NOT NULL,
 `title` text NOT NULL,
 `open_text` text NOT NULL,
 `main_text` text NOT NULL,
 `date` datetime DEFAULT '0000-00-00 00:00:00',
 `redate` datetime NOT NULL,
 `counter` int(10) DEFAULT '0' NOT NULL,
 `active` int(1) DEFAULT '1' NOT NULL,
 `golos` int(10) DEFAULT '0' NOT NULL,
 `comm` int(10) DEFAULT '0' NOT NULL,
 `foto` varchar(255) NOT NULL,
 `search` text NOT NULL,
 `mainpage` char(1) DEFAULT '0' NOT NULL,
 `rss` char(1) DEFAULT '1' NOT NULL,
 `price` int(10) DEFAULT '0' NOT NULL,
 `description` varchar(200) NOT NULL,
 `keywords` text NOT NULL,
 `tables` varchar(255) DEFAULT 'pages' NOT NULL,
 `copy` int(10) DEFAULT '0' NOT NULL,
 `sort` int(10) DEFAULT '0' NOT NULL,
 PRIMARY KEY (`pid`),
 KEY pid (`pid`),
 KEY cid (`cid`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_pages_categories`;");
$db->sql_query("CREATE TABLE `".$prefix."_pages_categories` (
 `cid` int(10) NOT NULL auto_increment,
 `module` varchar(255) NOT NULL,
 `title` varchar(255) NOT NULL,
 `description` text NOT NULL,
 `pic` varchar(255) NOT NULL,
 `sort` int(1) DEFAULT '0' NOT NULL,
 `counter` int(10) DEFAULT '0' NOT NULL,
 `parent_id` int(10) DEFAULT '0' NOT NULL,
 `tables` varchar(255) DEFAULT 'pages' NOT NULL,
 PRIMARY KEY (`cid`),
 KEY cid (`cid`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_pages_comments`;");
$db->sql_query("CREATE TABLE `".$prefix."_pages_comments` (
 `cid` int(10) NOT NULL auto_increment,
 `num` int(10) DEFAULT '0' NOT NULL,
 `avtor` tinytext NOT NULL,
 `mail` varchar(255) NOT NULL,
 `text` text NOT NULL,
 `ip` tinytext NOT NULL,
 `data` tinytext NOT NULL,
 `golos` int(2) DEFAULT '0',
 `tables` varchar(255) DEFAULT 'pages' NOT NULL,
 `drevo` int(10) DEFAULT '0' NOT NULL,
 `adres` text NOT NULL,
 `tel` varchar(255) NOT NULL,
 `active` int(1) DEFAULT '1' NOT NULL,
 PRIMARY KEY (`cid`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_pages_golos`;");
$db->sql_query("CREATE TABLE `".$prefix."_pages_golos` (
 `gid` int(10) NOT NULL auto_increment,
 `ip` tinytext NOT NULL,
 `golos` int(2) DEFAULT '0' NOT NULL,
 `num` int(10) DEFAULT '0' NOT NULL,
 `data` varchar(19) NOT NULL,
 `tables` varchar(255) DEFAULT 'pages' NOT NULL,
 PRIMARY KEY (`gid`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_search`;");
$db->sql_query("CREATE TABLE `".$prefix."_search` (
 `id` int(10) NOT NULL auto_increment,
 `ip` varchar(20) NOT NULL,
 `slovo` varchar(255) NOT NULL,
 `data` datetime NOT NULL,
 `pages` varchar(20) NOT NULL,
 PRIMARY KEY (`id`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_session`;");
$db->sql_query("CREATE TABLE `".$prefix."_session` (
 `uname` varchar(25) NOT NULL,
 `time` varchar(14) NOT NULL,
 `host_addr` varchar(48) NOT NULL,
 `guest` int(1) DEFAULT '0' NOT NULL,
 KEY time (`time`),
 KEY guest (`guest`)
);");


$db->sql_query("INSERT INTO `".$prefix."_session` VALUES ( '109.229.100.78', '1294640362', '109.229.100.78', '1');");

$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_shop`;");
$db->sql_query("CREATE TABLE `".$prefix."_shop` (
 `id` int(10) NOT NULL auto_increment,
 `ip` varchar(15) NOT NULL,
 `user` varchar(255) NOT NULL,
 `module` varchar(255) NOT NULL,
 `page` int(10) DEFAULT '0' NOT NULL,
 `num` int(10) DEFAULT '0' NOT NULL,
 `type` char(1) DEFAULT '0' NOT NULL,
 `data` datetime NOT NULL,
 PRIMARY KEY (`id`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_spiski`;");
$db->sql_query("CREATE TABLE `".$prefix."_spiski` (
 `id` int(10) NOT NULL auto_increment,
 `type` varchar(255) NOT NULL,
 `name` text,
 `opis` text NOT NULL,
 `sort` int(10) DEFAULT '0',
 `pages` text NOT NULL,
 `parent` int(10) DEFAULT '0' NOT NULL,
 PRIMARY KEY (`id`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_users`;");
$db->sql_query("CREATE TABLE `".$prefix."_users` (
 `user_id` int(11) NOT NULL auto_increment,
 `name` varchar(60) NOT NULL,
 `username` varchar(25) NOT NULL,
 `user_email` varchar(255) NOT NULL,
 `femail` varchar(255) NOT NULL,
 `user_website` varchar(255) NOT NULL,
 `user_avatar` varchar(255) NOT NULL,
 `user_regdate` varchar(20) NOT NULL,
 `user_icq` varchar(15),
 `user_occ` varchar(100),
 `user_from` varchar(100),
 `user_interests` varchar(150) NOT NULL,
 `user_sig` varchar(255),
 `user_viewemail` tinyint(2),
 `user_theme` int(3),
 `user_aim` varchar(18),
 `user_yim` varchar(25),
 `user_msnm` varchar(25),
 `user_password` varchar(40) NOT NULL,
 `storynum` tinyint(4) DEFAULT '10' NOT NULL,
 `umode` varchar(10) NOT NULL,
 `uorder` tinyint(1) DEFAULT '0' NOT NULL,
 `thold` tinyint(1) DEFAULT '0' NOT NULL,
 `noscore` tinyint(1) DEFAULT '0' NOT NULL,
 `bio` tinytext NOT NULL,
 `ublockon` tinyint(1) DEFAULT '0' NOT NULL,
 `ublock` tinytext NOT NULL,
 `theme` varchar(255) NOT NULL,
 `commentmax` int(11) DEFAULT '4096' NOT NULL,
 `counter` int(11) DEFAULT '0' NOT NULL,
 `newsletter` int(1) DEFAULT '0' NOT NULL,
 `user_posts` int(10) DEFAULT '0' NOT NULL,
 `user_attachsig` int(2) DEFAULT '0' NOT NULL,
 `user_rank` int(10) DEFAULT '0' NOT NULL,
 `user_level` int(10) DEFAULT '1' NOT NULL,
 `broadcast` tinyint(1) DEFAULT '1' NOT NULL,
 `popmeson` tinyint(1) DEFAULT '0' NOT NULL,
 `user_active` tinyint(1) DEFAULT '1',
 `user_session_time` int(11) DEFAULT '0' NOT NULL,
 `user_session_page` smallint(5) DEFAULT '0' NOT NULL,
 `user_lastvisit` int(11) DEFAULT '0' NOT NULL,
 `user_timezone` tinyint(4) DEFAULT '10' NOT NULL,
 `user_style` tinyint(4),
 `user_lang` varchar(255) DEFAULT 'english' NOT NULL,
 `user_dateformat` varchar(14) DEFAULT 'D M d, Y g:i a' NOT NULL,
 `user_new_privmsg` smallint(5) unsigned DEFAULT '0' NOT NULL,
 `user_unread_privmsg` smallint(5) unsigned DEFAULT '0' NOT NULL,
 `user_last_privmsg` int(11) DEFAULT '0' NOT NULL,
 `user_emailtime` int(11),
 `user_allowhtml` tinyint(1) DEFAULT '1',
 `user_allowbbcode` tinyint(1) DEFAULT '1',
 `user_allowsmile` tinyint(1) DEFAULT '1',
 `user_allowavatar` tinyint(1) DEFAULT '1' NOT NULL,
 `user_allow_pm` tinyint(1) DEFAULT '1' NOT NULL,
 `user_allow_viewonline` tinyint(1) DEFAULT '1' NOT NULL,
 `user_notify` tinyint(1) DEFAULT '0' NOT NULL,
 `user_notify_pm` tinyint(1) DEFAULT '0' NOT NULL,
 `user_popup_pm` tinyint(1) DEFAULT '0' NOT NULL,
 `user_avatar_type` tinyint(4) DEFAULT '3' NOT NULL,
 `user_sig_bbcode_uid` varchar(10),
 `user_actkey` varchar(32),
 `user_newpasswd` varchar(32),
 `points` int(10) DEFAULT '0',
 `last_ip` varchar(15) DEFAULT '0' NOT NULL,
 `karma` tinyint(1) DEFAULT '0',
 PRIMARY KEY (`user_id`),
 KEY uid (`user_id`),
 KEY uname (`username`),
 KEY user_session_time (`user_session_time`),
 KEY karma (`karma`)
);");


$db->sql_query("INSERT INTO `".$prefix."_users` VALUES ( '1', '', 'Anonymous', '', '', '', 'blank.gif', 'Mar 18, 2006', '', '', '', '', '', '0', '0', '', '', '', '', '10', '', '0', '0', '0', '', '0', '', '', '4096', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '10', NULL, 'russian', 'D M d, Y g:i a', '0', '0', '0', NULL, '1', '1', '1', '1', '1', '1', '1', '1', '0', '3', NULL, NULL, NULL, '0', '0', '0');");
$db->sql_query("INSERT INTO `".$prefix."_users` VALUES ( '2', '13i', '13i', '13i@list.ru', '', '', 'gallery/134.png', 'Apr 23, 2007', '', '', '', '', '', '1', NULL, '', '', '', '68934a3e9455fa72420237eb05902327', '10', 'nested', '0', '0', '0', '', '0', '', '', '4096', '0', '0', '2', '0', '0', '1', '1', '0', '1', '1177970005', '-4', '1177952338', '4', '3', 'russian', 'Y-m-d, H:i:s', '0', '0', '1177952338', NULL, '1', '1', '1', '1', '1', '0', '0', '0', '0', '3', '', '', NULL, '0', '0', '0');");

$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_users_temp`;");
$db->sql_query("CREATE TABLE `".$prefix."_users_temp` (
 `user_id` int(10) NOT NULL auto_increment,
 `username` varchar(25) NOT NULL,
 `user_email` varchar(255) NOT NULL,
 `user_password` varchar(40) NOT NULL,
 `user_regdate` varchar(20) NOT NULL,
 `check_num` varchar(50) NOT NULL,
 `time` varchar(14) NOT NULL,
 PRIMARY KEY (`user_id`)
);");

$db->sql_query("select `ht_backup` from `".$prefix."_config`;") or $db->sql_query("ALTER TABLE `".$prefix."_config` ADD `ht_backup` VARCHAR( 255 ) NOT NULL");
$db->sql_query("UPDATE `".$prefix."_config` SET `ht_backup` = '.ht_backup' LIMIT 1 ;");

print ("<center><h2>Обновление базы данных окончено!</h2><br>");
?>