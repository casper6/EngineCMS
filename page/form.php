<?php // недоделано.
if (!defined('MODULE_FILE')) { die (ss("У вас нет прав для доступа к этому файлу!")); }
$module_name = basename(dirname(__FILE__));
global $prefix, $db; 

$value = preg_replace('/[^\d]+/', '', $value); // только целое число

$value = preg_replace('/[^\d,]+/', '', $value); // только число с запятой

preg_replace("/[^\p{L}0-9\+\-\_:\.@ ]/u", "", $_string));
?>