<?php
// Файл перенаправления ЧПУ
$url = explode("/",filter_var(urldecode($_SERVER['REQUEST_URI']), FILTER_SANITIZE_SPECIAL_CHARS));
require_once ('config.php');
require_once ('includes/db.php'); // Работа с базой данных
// print_r($url);
// раздел [0] => [1] => pro [2] =>
// каталог [0] => [1] => pro [2] => cat [3] =>
// страница [0] => [1] => pro [2] => xxx.html
$name = "index";
if (isset($url[3])) { // папки
  $row = $db->sql_fetchrow($db->sql_query("SELECT `cid`,`module` from `".$prefix."_pages_categories` where `clean_url`='".$url[2]."' and `tables`='pages'"));
  $name = $row['module'];
  $go = "showcat";
  $cid = $row['cid'];
} elseif ($url[2] != "") { // страницы
  $row = $db->sql_fetchrow($db->sql_query("SELECT `pid`,`module` from `".$prefix."_pages` where `clean_url`='".str_replace(".html", "", $url[2])."' and `tables`='pages' and active='1'"));
  $name = $row['module'];
  $go = "page";
  $pid = $row['pid'];
} elseif (isset($url[1])) { // разделы
  $name = $url[1];
}
include ("header.php");
?>