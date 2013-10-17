<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
require_once("mainfile.php");
global $prefix, $db;
$rotator_num = intval($_GET['num']); // Номер блока ротатора
// Загрузка информации о ротаторе
$sql2 = "select `text` from ".$prefix."_mainpage where `type`='3' and `name`='3' and `id`='".$rotator_num."'"; 
$result2 = $db->sql_query($sql2);
$row2 = $db->sql_fetchrow($result2);
$lines = explode("|", trim($row2['text'])); // ЗАМЕНИТЬ!
$itogo = count($lines)-1;
srand((double) microtime()*1000000);
$i = rand(0, $itogo);
$textX = $lines[$i];
header ("Content-Type: text/html; charset=utf-8");
echo $textX;
?>
