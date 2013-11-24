<?php
##############################################
## CMS «ДвижОк» (Content Management System) ##
## English name — EngineCMS                 ##
## Распространяется по лицензии.	        ##
## Since 2006 © Влад Мерк, г. Самара        ##
## 13i@list.ru | http://cms.ru.com          ##
##############################################

################### БАЗА ДАННЫХ
$dbhost 			= "localhost"; 	# Хост базы данных
$dbuname 			= "root"; 	# Имя пользователя базы данных
$dbpass 			= "root"; 	# Пароль пользователя базы данных
$dbname 			= "iron"; # Имя базы данных
$prefix 			= "dvizhok"; # Префикс базы данных
$dbtype 			= "MySQL"; 	# Тип базы данных - MySQL, остальные (mysql4, postgres, mssql, oracle, msaccess, db2, mssql-odbc) недоступны
################### НАСТРОЙКИ
$siteurl			= "xn--h1alffa9f.xn--p1ai"; # Пример: xn--h1alffa9f.xn--p1ai или it.ilost.ru
$realurl			= "россия.рф"; # Пример: россия.рф или it.ilost.ru
// Для русскоязычных адресов использован пуни-конвертер — http://r01.ru/domain/whois/instruments/converter.php
$lang_admin			= "ru"; // Язык администрирования
$lang 				= "ru"; // Язык сайта
$display_errors 	= false; # Отладочная опция - показ ошибок (и запросов к БД и их количества) = true
$ipban 				= false; # Админ-опция - включение блокировки по IP = true, отключение = false
$site_cash    		= false; # Система кеширования: false - отключена, file - кеширование в файлы, base - кеширование в БД
// date_default_timezone_set('Europe/Moscow'); # Может не работать на вашем сервере, позволяет настроить сайт на нужную временную зону
################### Проверка безопасности
if (stristr(htmlentities($_SERVER['PHP_SELF']), "config.php")) { Header("Location: index.php"); die(); }
?>