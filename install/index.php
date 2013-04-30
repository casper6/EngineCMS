<?php
// Проверка наличия файла config.php
if (file_exists("config.php")) die("<h3>Найдена установленная CMS «ДвижОк»</h3><li>Если вы только что её установили – удалите каталог install в корне вашего сайта на сервере – после этого сайт заработает.<li>Если по какой-то другой причине в корне сайта оказался файл config.php, CMS еще не установлена и вы запустили её установку — просто сотрите файл config.php и обновите эту страницу.");
	// <li>Если данный сайт создан ранее — вы можете <a href=#>обновить базу данных</a> до новой версии.

// Запуск установки ====================
if (isset($_REQUEST['lang'])) {
	$lang = $_REQUEST['lang'];
	$ipban = $_REQUEST['ipban'];
	$site_cash = $_REQUEST['site_cash'];
	$dbhost = $_REQUEST['dbhost'];
	$dbuname = $_REQUEST['dbuname'];
	$dbpass = $_REQUEST['dbpass'];
	$dbname = $_REQUEST['dbname'];
	$prefix = $_REQUEST['prefix'];
	$a = $_REQUEST['a'];
	$pass = md5($_REQUEST['pass']);
	// Проверка БД
	$db = mysql_connect ($dbhost, $dbuname, $dbpass) or die("не выбрана база! ".mysql_error());
	// Создание config.php
	$conf = '<?php
##############################################
## ДвижОк CMS (Content Management System)   ##
## Распространяется по GNU GPL 3 версии.	##
## 2006-2013 © Влад Мерк, г. Самара         ##
## 13i@list.ru | http://hotel-s.ru          ##
##############################################

// date_default_timezone_set(\'Europe/Moscow\'); # Может не работать на вашем сервере
################### АДРЕС САЙТА
$siteurl 			= "'.$prefix.'"; # Пример: ilost.ru или it.ilost.ru (домены третьего уровня использовать можно, папки — нельзя)
################### БАЗА ДАННЫХ
$dbhost 			= "'.$dbhost.'"; 	# Хост базы данных
$dbuname 			= "'.$dbuname.'"; 	# Имя пользователя базы данных
$dbpass 			= "'.$dbpass.'"; 	# Пароль пользователя базы данных
$dbname 			= "'.$dbname.'"; # Имя базы данных
$prefix 			= "'.$prefix.'"; # Префикс базы данных
$dbtype 			= "MySQL"; 	# Тип базы данных - MySQL, остальные (mysql4, postgres, mssql, oracle, msaccess, db2, mssql-odbc) недоступны
################### НАСТРОЙКИ ОТОБРАЖЕНИЯ
$display_errors 	= false; # Отладочная опция - для показа ошибок (и запросов к БД и их количества) написать = true, для отмены = false
$ipban 				= '.$ipban.'; # Админ-опция - для включения блокировки по IP = true, для отключения = false
$site_cash    		= '.$site_cash.'; # Система кеширования: false - отключена, file - кеширование в файлы, base - кеширование в БД
################### НАСТРОЙКИ САЙТА
$lang 				= "'.$lang.'"; // Язык
$red4_div_convert 	= "convertDivs: true,"; // Поставьте false для отмены конвертации <DIV> в <P> в 2-м визуал. редакторе
$more_smile			= false; // Дополнительные смайлики в комментариях, если true - включится
$strelka			= "&rarr;";
$razdel_open_name	= "Открыть раздел";
$reiting_data 		= "Дата написания отзыва"; // Дата написания отзыва (пример: для отзывов о роддомах - дата родов, Дата посещения и т.д.)*
###################
if (stristr(htmlentities($_SERVER[\'PHP_SELF\']), "config.php")) { Header("Location: index.php"); die(); } // Проверка безопасности
?>';
	if (!function_exists('file_put_contents')) {
	    function file_put_contents($filename, $data) {
	        $f = @fopen($filename, 'w');
	        if (!$f) {
	            return false;
	        } else {
	            $bytes = fwrite($f, $data);
	            fclose($f);
	            return $bytes;
	        }
	    }
	}
	if (!file_put_contents('config.php', $conf, LOCK_EX)) die('<li>Файл config.php в корне сайта не перезаписан! Попробуйте его удалить и перезапустить установку CMS.');
	echo "<li>Файл config.php настроен";

	// Запуск install.php
	if (file_exists("install/install.php")) include ("install/install.php");
	else die('<li>Файл install.php в папке install не найден!');
	echo "<li>Установка БД окончена";
	// Запуск обновления
	//include ("update_old_version.php");

	// Добавление дизайна

	// Добавляем админа
	$db->sql_query("INSERT INTO `".$prefix."_authors` VALUES ( '".$a."', 'BOG', '".$pass."', '1', '', '0');") or die ('<li>Администратор не был добавлен в базу данных');
	echo "<li>Права администратора установлены";
	// Переход в админку
	echo "<h1>Установка успешно завершилась. Удалите папку install</h1>
	<a href=sys.php>Перейти в Администрирование сайта</a>";
	die;
}
// НАЧАЛО УСТАНОВКИ =====================================
// Проверка версии PHP
$phpversion = preg_replace('/[a-z-]/', '', phpversion());
if ($phpversion{0}<4) die ('Версия PHP ниже плинтуса. Где же ты нарыл такое старьё?! 0_о');
if ($phpversion{0}==4) die ('Версия PHP — 4. Попросите хостинг-компанию установить PHP как минимум версии 5.2.1');
$siteurl = $_SERVER["HTTP_HOST"];
if ($siteurl == "") $siteurl = "localhost";

function generate_password($number) {  // Генерируем пароль
    $arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6','7','8','9','0','.','(',')','!','-',);
    $pass = "";
    for($i = 0; $i < $number; $i++) { // Вычисляем случайный индекс массива
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
}
$pass = generate_password(10);
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="ru"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Установка CMS «ДвижОк»</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="../includes/css-frameworks/skeleton/base.css">
	<link rel="stylesheet" href="../includes/css-frameworks/skeleton/skeleton.css">
	<link rel="stylesheet" href="../includes/css-frameworks/skeleton/layout.css">
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="shortcut icon" href="../images/favicon_cms.png">
	<style>
	input {width:100%;}
	</style>
</head>
<body>
<form>
<div class="container">
	<div class="sixteen columns">
			<h1 class="remove-bottom" style="margin-top: 40px">Установка CMS «ДвижОк»</h1>
			<h5>Версия 1.9</h5>
			<hr />
		</div>
		<div class="one-third column">
			<h3>С чего начать?</h3>
			<p>На хостинге должны быть установлены: PHP от 5.2.1 до 5.3 (выше пока не поддерживается) и MySQL 4.1 (или выше).<br>В MySQL необходимо создать базу данных.</p>
			<ul class="square">
				<li><strong>Хост базы данных</strong>:<br><input name="dbhost" value="localhost"></li>
				<li><strong>Имя базы данных</strong>:<br><input name="dbname" value=""></li>
				<li><strong>Имя пользователя базы данных</strong>:<br><input name="dbuname" value=""></li>
				<li><strong>Пароль пользователя базы данных</strong>:<br><input name="dbpass" value=""></li>
				<li><strong>Префикс таблиц</strong>:<br><input name="prefix" value="dvizhok"><br>Если у вас один сайт или на каждый сайт своя база данных - префикс можно не менять.</li>
			</ul>
		</div>
		<div class="one-third column">
			<h3>Основные настройки</h3>
			<ul class="square">
				<li><strong>Адрес сайта</strong>:<br><input name="siteurl" value="<? echo $siteurl; ?>"></li>
				<li><strong>Язык</strong>:<br><select name="lang"><option value="ru-RU">Русский</option></select></li>
				<li><strong>Псевдоним администратора</strong>:<br><input name="a" value="admin"></li>
				<li><strong>Пароль администратора</strong>:<br><input name="pass" value="<? echo $pass; ?>"><br>Скопируйте или перепишите пароль!</li>
				<li><strong>Блокировка по IP-адресу</strong>:<br><select name="ipban"><option value="true">Включить</option><option value="false" selected>Отключить</option></select></li>
				<li><strong>Кеширование страниц сайта</strong>:<br><select name="site_cash"><option value="false">Отключено</option><option value="file">в файлы</option><option value="base">в базу данных</option></select></li>
			</ul>
		</div>
		<div class="one-third column">
			<h3>Документация и поддержка</h3>
			<p>Большая часть документации содержится в самой CMS, как в Помощи, так и в необходимых местах. Если встроенной помощи недостаточно — пишите на <a href="mailto:13i@list.ru">13i@list.ru</a> или стучитесь в skype <b>angel13i</b> — вы получите ответы на все вопросы, после чего встроенная помощь будет расширена и дополнена.</p>
			<button type="submit"><h3>Установить →</h3></button>
		</div>
</div>
<?
if ($phpversion{0}==5 && $phpversion{2}<2) echo "<b style='color:red;'>Версия PHP — 5.".$phpversion{2}.". Рекомендуется использовать PHP как минимум версии 5.2.1</b>";
if ($phpversion{0}>=5 && $phpversion{2}>3) echo "<b style='color:red;'>Версия PHP — 5.".$phpversion{2}.". Рекомендуется использовать PHP как максимум версии 5.3.<br>На 5.4 полноценно не тестировалось — вы можете попробовать и передать разработчику все возникшие ошибки.</b>";
if (!function_exists('curl_init')) echo "<b style='color:red;'>Желательно включить поддержку cURL на вашем хостинге.</b>";
?>
</form>
</body>
</html>