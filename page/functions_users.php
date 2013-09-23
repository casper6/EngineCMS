<?php
$hash_padding = "dhfgyshdgGHGDFgdtg";

// Дизайн модуля регистрации
global $prefix, $db;
$row = $db->sql_fetchrow($db->sql_query("select `title` from ".$prefix."_mainpage where `type`='10' and `name`='config'"));
$design = intval($row['title']);

// Генератор случайной строки (пароль и прочие случайности)
function generate_simvols($kolichestvo) {
	$arr = array(':',';','.',',','-','!','(',')','*','%','+','=','[',']','~','a','b','c','d','e','f','g','h','i','j','k','l','m','n','p','r','s','t','u','v','x','y','z','Q','W','E','R','T','Y','U','I','O','P','A','S','D','F','G','H','J','K','L','Z','X','C','V','B','N','M','1','2','3','4','5','6','7','8','9','0');
	// Генерируем случайную строку
	$pass = "";
	for ($i = 0; $i < $kolichestvo; $i++) { // Вычисляем случайный индекс массива
		$index = rand(0, count($arr) - 1);
		$pass .= $arr[$index];
	} 
	return $pass;
}

// Запоминаем пользователя
function user_set_tokens($em_in) {
	global $hash_padding, $prefix, $db; 
	if (!$em_in) { 
		$feedback = ss("ОШИБКА - Не указано имя пользователя."); 
		return false;
	} 
	$id_hash = md5($em_in.$hash_padding); 
	setcookie('email', $em_in, (time()+2592000), '/', '', 0);
	setcookie('id_hash', $id_hash, (time()+2592000), '/', '', 0);  
	$row = $db->sql_fetchrow($db->sql_query("SELECT `user_id`, `name`, `photo`, `user_group`, `regions` FROM ".$prefix."_users WHERE `email` = '".$em_in."'"));
	setcookie('user_id', $row['user_id'], (time()+2592000), '/', '', 0);
	setcookie('user_name', $row['name'], (time()+2592000), '/', '', 0);
	setcookie('user_pfoto', $row['photo'], (time()+2592000), '/', '', 0);
	setcookie('user_group', $row['user_group'], (time()+2592000), '/', '', 0);
	setcookie('user_regions', $row['regions'], (time()+2592000), '/', '', 0); 
}

// выходим
function user_logout() {
	setcookie('email', '', (time()+2592000), '/', '', 0); 
	setcookie('id_hash', '', (time()+2592000), '/', '', 0); 
	setcookie('user_id', '', (time()+2592000), '/', '', 0); 
	setcookie('user_name', '', (time()+2592000), '/', '', 0);
	setcookie('user_pfoto', '', (time()+2592000), '/', '', 0); 
	setcookie('user_group', '', (time()+2592000), '/', '', 0); 
	setcookie('user_regions', '', (time()+2592000), '/', '', 0);
} 
?>