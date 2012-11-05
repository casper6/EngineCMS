<?php
if (!defined('MODULE_FILE')) {
  die ("У вас нет прав для доступа к этому файлу!");
}
require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
###########################################
// передается из header
global $prefix, $db; 

$value = preg_replace('/[^\d]+/', '', $value); // только целое число

$value = preg_replace('/[^\d,]+/', '', $value); // только число с запятой

preg_replace("/[^\p{L}0-9\+\-\_:\.@ ]/u", "", $_string));
?>