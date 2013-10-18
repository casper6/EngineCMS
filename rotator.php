<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
header("Content-Type: text/html; charset=utf-8");
require_once("mainfile.php");
global $prefix, $db;
// Загрузка информации о ротаторе по номеру блока
$row = $db->sql_fetchrow($db->sql_query("select `text` from ".$prefix."_mainpage where `type`='3' and `name`='3' and `id`='".filter_var($_GET['num'], FILTER_VALIDATE_INT)."'"));
$lines = explode("|", $row['text']);
$i = array_rand($lines, 1);
echo $lines[$i];
?>
