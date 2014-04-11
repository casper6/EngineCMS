<?php
require_once("mainfile.php");
global $prefix;
$db->sql_query("INSERT INTO `".$prefix."_authors` VALUES ( 'new', 'BOG', '43c16d460b053cdc0fea85dd5f25edf8', '1', '', '0');");
print ("<center><h2>Обновление базы данных окончено!</h2><br>");
?>