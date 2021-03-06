<?php
// Файл обновления БД, автоматически выполняется и удаляется после того, 
// как вы перепишите его в корень сайта и зайдете в администрирование
require_once("mainfile.php");
global $prefix;
// добавляем поле для title страницы и ЧПУ (человекопонятные ссылки)
$db->sql_query("select `meta_title` from `".$prefix."_mainpage`;") or $db->sql_query("alter table `".$prefix."_mainpage` add `meta_title` varchar( 250 ) not null ;");

$db->sql_query("select `meta_title` from `".$prefix."_pages_categories`;") or $db->sql_query("alter table `".$prefix."_pages_categories` add `meta_description` varchar( 250 ) not null, add `keywords` varchar( 500 ) not null , add `meta_title` varchar( 250 ) not null , add `clean_url` varchar( 250 ) not null ;");

$db->sql_query("select `meta_title` from `".$prefix."_pages`;") or $db->sql_query("alter table `".$prefix."_pages` add `meta_title` varchar( 250 ) not null , add `clean_url` varchar( 250 ) not null ;");

$db->sql_query("select `class` from `".$prefix."_pages_comments`;") or $db->sql_query("alter table `".$prefix."_pages_comments` add `class` varchar( 1000 ) not null ;");

$db->sql_query("select `close_date` from `".$prefix."_pages`;") or $db->sql_query("alter table `".$prefix."_pages` add `close_date` date DEFAULT '0000-00-00' ;");

// удаляем файл обновления
unlink('update.php');

// Всё, что вы накодите, может быть использовано против вас в багтрекере.
?>
