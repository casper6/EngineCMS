<?php
header("Content-Type: text/html; charset=utf-8");
require_once("mainfile.php");
global $prefix, $db;
// Загрузка информации о ротаторе по номеру блока
$row = $db->sql_fetchrow($db->sql_query("select `text` from ".$prefix."_mainpage where `type`='3' and `name`='3' and `id`='".filter_var($_GET['num'], FILTER_VALIDATE_INT)."'"));
$lines = explode("|", $row['text']);
$i = array_rand($lines, 1);
echo $lines[$i];

//$lines = str_replace("\"","'",$row['text']);
//echo $lines;
?>
