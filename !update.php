<?php
require_once("mainfile.php");
global $prefix;

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