<?php
// Всё, что вы накодите, может быть использовано против вас в багтрекере.
require_once("mainfile.php");
global $prefix;
// добавляем поле для title страницы и ЧПУ (человекопонятные ссылки)
$db->sql_query("select `meta_title` from `".$prefix."_mainpage`;") or $db->sql_query("alter table `".$prefix."_mainpage` add `meta_title` varchar( 250 ) not null ;");

$db->sql_query("select `meta_title` from `".$prefix."_pages_categories`;") or $db->sql_query("alter table `".$prefix."_pages_categories` add `meta_description` varchar( 250 ) not null, add `keywords` varchar( 500 ) not null , add `meta_title` varchar( 250 ) not null , add `clean_url` varchar( 250 ) not null ;");

$db->sql_query("select `meta_title` from `".$prefix."_pages`;") or $db->sql_query("alter table `".$prefix."_pages` add `meta_title` varchar( 250 ) not null , add `clean_url` varchar( 250 ) not null ;");
// удаляем файл обновления
unlink('update.php');
?>
