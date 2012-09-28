<?php
################################################
## Движок CMS (Content Management System)     ##
## 2006-2012 (c) Владимир Меркушев, г. Самара ##
## 13i@list.ru | http://karta63.ru            ##
################################################

################### НАСТРОЙКА ВРЕМЕННОЙ ЗОНЫ
date_default_timezone_set('Europe/Moscow');
################### АДРЕС САЙТА
$siteurl 		= "localhost";
################### БАЗА ДАННЫХ
$dbhost 		= "localhost"; 	# Хост базы данных
$dbuname 		= "root"; 	# Имя пользователя базы данных
$dbpass 		= "root"; 		# Пароль пользователя базы данных
$dbname 		= "samrod"; 	# Имя базы данных
$prefix 		= "samrod"; 	# Префикс базы данных
$user_prefix 		= "samrod";# Префикс таблицы с пользователями
$dbtype 		= "MySQL"; 	# Тип базы данных (MySQL, mysql4, postgres, mssql, oracle, msaccess, db2, mssql-odbc)
################### ОБРАБОТКА ОШИБОК
// При ошибке Ошибка 330 (net::ERR_CONTENT_DECODING_FAILED) поставить false
$zlib = true; // Подключение библиотеки zlib для сжатия данных
################### НАСТРОЙКИ ОТОБРАЖЕНИЯ
$display_errors 	= true; # Отладочная опция - для показа ошибок (и запросов к БД и их количества) написать = true, для отмены = false
$display_delete 	= true; # Админ-опция - для показа кнопок удаления основного содержания = true, для скрытия = false
$display_addmenu 	= true; # Админ-опция - для показа кнопок создания основного содержания = true, для скрытия = false
$ipban 				= false; # Админ-опция - для включения блокировки по IP = true, для отключения = false
$site_cash 			= true; # Для всключения кеширования = true, для отключения = false
################### НАСТРОЙКИ CSS И JS
$lastmodified 		= date("Ymds"); // Сохранность кеша CSS, "Ym" - 1 месяц, "Ymd" - 1 день, "Ymds" - 1 секунда
################### НАСТРОЙКИ САЙТА
$lang 				= "ru-RU"; // Язык
$red4_div_convert 	= "convertDivs: true,"; // Поставьте false для отмены конвертации <DIV> в <P> в 4м виз. редакторе

$horizont_menu		= 0; // гор. меню
$otstup_table_menu 	= ""; // <br> - для высоких меню гор. меню
$vertical_menu		= 0; // верт. меню
$more_smile		= 0; // Дополнительные смайлики в комментариях
$search_pic		= "/img/search.gif"; // Кнопка Найти
$mail_pic		= "/img/mailme.gif"; // Кнопка Подписки на новости
$otpravka_pic		= "/img/ok.gif"; // Кнопка Отправить
$strelka		= "&rarr;";
$opros			= 1; // Тип опроса: 1 - круговая диаграмма, 0 - Столбцы
$pic_ramka		= 0; // графическая рамка вокруг изображения (СамРод)
$slovo_poisk		= "Поиск:";
$slovo_mail		= "Подписка на новости по e-mail:";
$razdel_design		= false; // использование дизайна в разделах (для их отличия)
$razdel_open_name	= "Открыть раздел";
$reiting_data 		= "Дата написания отзыва"; // Дата написания отзыва (для отзывов о роддомах - дата родов)*
###################
if (stristr(htmlentities($_SERVER['PHP_SELF']), "config.php")) { Header("Location: index.php"); die(); }
?>