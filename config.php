<?php
##############################################
## ДвижОк CMS (Content Management System)   ##
## Распространяется по GNU GPL 3 версии.	##
## 2006-2012 © Владимир Меркушев, г. Самара ##
## 13i@list.ru | http://karta63.ru          ##
##############################################

// date_default_timezone_set('Europe/Moscow');
################### АДРЕС САЙТА
//_SERVER["HTTP_HOST"]
$siteurl 			= "localhost"; # Пример: ilost.ru или it.ilost.ru (домены третьего уровня использовать можно, папки — нельзя)
################### БАЗА ДАННЫХ
$dbhost 			= "localhost"; 	# Хост базы данных
$dbuname 			= "root"; 	# Имя пользователя базы данных
$dbpass 			= "root"; 	# Пароль пользователя базы данных
$dbname 			= "samrod"; # Имя базы данных
$prefix 			= "samrod"; # Префикс базы данных
$user_prefix 		= "samrod"; # Префикс таблицы с пользователями
$dbtype 			= "MySQL"; 	# Тип базы данных - MySQL, остальные (mysql4, postgres, mssql, oracle, msaccess, db2, mssql-odbc) недоступны
################### НАСТРОЙКИ ОТОБРАЖЕНИЯ
$display_errors 	= false; # Отладочная опция - для показа ошибок (и запросов к БД и их количества) написать = true, для отмены = false
$display_delete 	= true; # Админ-опция - для показа кнопок удаления основного содержания = true, для скрытия = false
$display_addmenu 	= true; # Админ-опция - для показа кнопок создания основного содержания = true, для скрытия = false
$ipban 				= false; # Админ-опция - для включения блокировки по IP = true, для отключения = false
$site_cash 			= false; # Для отключения кеширования = true, для отключения = false
################### НАСТРОЙКИ CSS
$lastmodified 		= date("Ymds"); // Сохранность кеша CSS, "Ym" - 1 месяц, "Ymd" - 1 день, "Ymds" - 1 секунда
################### НАСТРОЙКИ САЙТА
$lang 				= "ru-RU"; // Язык
$red4_div_convert 	= "convertDivs: true,"; // Поставьте false для отмены конвертации <DIV> в <P> в 2-м визуал. редакторе
$more_smile			= false; // Дополнительные смайлики в комментариях, если true - включится
$strelka			= "&rarr;";
$razdel_open_name	= "Открыть раздел";
$reiting_data 		= "Дата написания отзыва"; // Дата написания отзыва (пример: для отзывов о роддомах - дата родов, Дата посещения и т.д.)*
###################
if (stristr(htmlentities($_SERVER['PHP_SELF']), "config.php")) { Header("Location: index.php"); die(); } // Проверка безопасности
?>