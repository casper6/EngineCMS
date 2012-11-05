<?php 
require_once('page/functions_users.php');
global $design;
$soderganie = 'Доступ закрыт';

if ( isset($_GET['hash']) && isset($_COOKIE['id_hash']) ) 
	if ( $_GET['hash'] == $_COOKIE['id_hash'] ) {
		$soderganie = 'Страница пользователя '.$_COOKIE['email'].' с идентификатором '.$_COOKIE['id_hash']; 
	}
//////////////////////////////////////
list($design_for_reg, $stil) = design_and_style($design);
if ($design_for_reg == "0") die("Ошибка: «Адрес раздела» (".$name.") введен неправильно. Перейдите на <a href=/>Главную страницу</a>.");
$block = str_replace("[содержание]",$soderganie,$design_for_reg); 
return array($block, $stil);
?>