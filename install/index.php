<?php
$ver = '1.17'; // Версия CMS «ДвижОк»
header ("Content-Type: text/html; charset=utf-8");
// Получение списка БД
if (isset($_REQUEST['db'])) {
	$dbhost = $_REQUEST['db'];
	$dbuname = $_REQUEST['dbuname'];
	$dbpass = $_REQUEST['dbpass'];
	if (!mysql_connect($dbhost, $dbuname, $dbpass)) {
		echo "Ошибка доступа. Неправильно введены сервер, пользователь или пароль."; exit;
	} else {
		$q = mysql_query("SHOW DATABASES;");
		// Добавить проверку кол-ва таблиц в БД для вывода подходящей БД
		echo "<li><strong>Имя базы данных</strong><br><select name='dbname' style='width:100%;'>";
		while ($row = mysql_fetch_assoc($q)) {
		    if ($row['Database'] != 'information_schema' &&
		    	$row['Database'] != 'mysql' &&
		    	$row['Database'] != 'performance_schema' ) 
		    	echo "<option value='".$row['Database']."'>".$row['Database']."</option>";
		}
		echo "</select>";
		exit;
	}
}

// Проверка наличия файла config.php
if (file_exists("config.php")) die("<h3>Найдена установленная CMS «ДвижОк»</h3><li>Если вы только что её установили – удалите каталог install в корне сайта на сервере – после этого сайт заработает.<li>Если по какой-то другой причине в корне сайта оказался файл config.php, CMS еще не установлена и вы запустили её установку (или вы решили переустановить CMS) — удалите файл config.php в корне сайта и обновите эту страницу (нажав F5).");
	// <li>Если данный сайт создан ранее — вы можете <a href=#>обновить базу данных</a> до новой версии.

// Запуск установки ====================
if (isset($_REQUEST['ipban'])) {
	$lang = $_REQUEST['lang'];
	$lang_admin = $_REQUEST['lang_admin'];
	$ipban = $_REQUEST['ipban'];
	$site_cash = $_REQUEST['site_cash'];
	$dbhost = $_REQUEST['dbhost'];
	$dbuname = $_REQUEST['dbuname'];
	$dbpass = $_REQUEST['dbpass'];
	$dbname = $_REQUEST['dbname'];
	$prefix = $_REQUEST['prefix'];
	$razdel = $_REQUEST['razdel'];
	$design = intval($_REQUEST['design']);
	$type = $_REQUEST['type'];
	$a = $_REQUEST['a'];
	$pass = md5($_REQUEST['pass']);
	$email = $_REQUEST['email'];
	$table_delete = $_REQUEST['table_delete'];

	$siteurl = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
	if ($siteurl == "") $siteurl = "localhost";

	// пуниконвертер
	require_once('idna_convert.class.php');
	$idn = new idna_convert(array('idn_version'=>2008));
	$punycode = $idn->decode(stripslashes($siteurl));
	//$punycode = (stripos($punycode, 'xn--')!==false) ? $idn->decode($punycode) : $idn->encode($punycode);

	// Доп. настройки для разных типов сайтов
	if ($type == 'company') {}
	if ($type == 'shop') {}
	if ($type == 'blog') {}
	if ($type == 'group') {}
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Установка CMS «ДвижОк»</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="../includes/css-frameworks/skeleton/base.css">
	<link rel="stylesheet" href="../includes/css-frameworks/skeleton/skeleton.css">
	<link rel="stylesheet" href="../includes/css-frameworks/skeleton/layout.css">
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="shortcut icon" href="../images/favicon_cms.png">
	<style>
	input {width:100%;}
	</style>
</head>
<body style="background:url('images/adfon/22.png')">
	<div class="del">
<?
	echo "Началась установка.<br>";
	// Проверка БД
	$db = mysql_connect ($dbhost, $dbuname, $dbpass) or die("Не выбрана база данных! ".mysql_error());
	echo "Подключение к базе данных успешно.<br>";

	// Создание config.php
	$conf = '<?php
##############################################
## CMS «ДвижОк» (Content Management System) ##
## English name — EngineCMS                 ##
## Распространяется по лицензии.	        ##
## Since 2006 © Влад Мерк, г. Самара        ##
## 13i@list.ru | http://cms.ru.com          ##
##############################################

################### БАЗА ДАННЫХ
$dbhost 			= "'.$dbhost.'"; 	# Хост базы данных
$dbuname 			= "'.$dbuname.'"; 	# Имя пользователя базы данных
$dbpass 			= "'.$dbpass.'"; 	# Пароль пользователя базы данных
$dbname 			= "'.$dbname.'"; # Имя базы данных
$prefix 			= "'.$prefix.'"; # Префикс базы данных
$dbtype 			= "MySQL"; 	# Тип базы данных - MySQL, остальные (mysql4, postgres, mssql, oracle, msaccess, db2, mssql-odbc) недоступны
################### НАСТРОЙКИ
$siteurl			= "'.$punycode.'"; # Пример: xn--h1alffa9f.xn--p1ai или it.ilost.ru
$realurl			= "'.$siteurl.'"; # Пример: россия.рф или it.ilost.ru
// Для русскоязычных адресов использован пуни-конвертер — http://r01.ru/domain/whois/instruments/converter.php
$lang_admin			= "'.$lang_admin.'"; // Язык администрирования
$lang 				= "'.$lang.'"; // Язык сайта
$display_errors 	= false; # Отладочная опция - показ ошибок (и запросов к БД и их количества) = true
$ipban 				= '.$ipban.'; # Админ-опция - включение блокировки по IP = true, отключение = false
$site_cash    		= '.$site_cash.'; # Система кеширования: false - отключена, file - кеширование в файлы, base - кеширование в БД
// date_default_timezone_set(\'Europe/Moscow\'); # Может не работать на вашем сервере, позволяет настроить сайт на нужную временную зону
################### Проверка безопасности
if (stristr(htmlentities($_SERVER[\'PHP_SELF\']), "config.php")) { Header("Location: index.php"); die(); }
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

	function translit_n($cyr_str) { # Транслит названий файлов
	  $tr = array(
	   "Ґ"=>"G","Ё"=>"YO","Є"=>"E","Ї"=>"YI","І"=>"I","і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"","є"=>"e",
	   "ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I",
	   "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
	   "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
	   "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
	   "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
	   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"",
	   "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
	   "«"=>"","»"=>"","."=>"",","=>"","!"=>"",":"=>"",";"=>"","?"=>""," "=>"_"
	  );
	   return $str = iconv ( "UTF-8", "UTF-8//IGNORE", strtr ( $cyr_str, $tr ) );
	}

	function strtolowX($txt, $t=1) { # Большие буквы в маленькие (и наоборот, при t=0)
	  $from   = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЬЫЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
	  $to     = 'абвгдеёжзийклмнопрстуфхцчшщъьыэюяabcdefghijklmnopqrstuvwxyz';
	  if ($t==1) $txt = strtr($txt, $from, $to); elseif ($t==0) $txt = strtr($txt, $to, $from);
	  return $txt;
	}

	function copy_folder($d1, $d2, $upd = true, $force = true) { // копирование папки с файлами
	    if ( is_dir( $d1 ) ) { 
	        $d2 = mkdir_safe( $d2, $force ); 
	        if (!$d2) {fs_log("!!fail $d2"); return;} 
	        $d = dir( $d1 ); 
	        while ( false !== ( $entry = $d->read() ) ) { 
	            if ( $entry != '.' && $entry != '..' )  
	                copy_folder( "$d1/$entry", "$d2/$entry", $upd, $force ); 
	        } 
	        $d->close(); 
	    } 
	    else { 
	        $ok = copy_safe( $d1, $d2, $upd ); 
	        $ok = ($ok) ? "ok-- " : " -- ";
	    } 
	} //function copy_folder 

	function mkdir_safe( $dir, $force ) { 
	    if (file_exists($dir)) { 
	        if (is_dir($dir)) return $dir; 
	        else if (!$force) return false; 
	        unlink($dir); 
	    } 
	    return (mkdir($dir, 0777, true)) ? $dir : false; 
	} //function mkdir_safe 

	function copy_safe ($f1, $f2, $upd) { 
	    $time1 = filemtime($f1); 
	    if (file_exists($f2)) { 
	        $time2 = filemtime($f2); 
	        if ($time2 >= $time1 && $upd) return false; 
	    } 
	    $ok = copy($f1, $f2); 
	    if ($ok) touch($f2, $time1); 
	    return $ok; 
	} //function copy_safe

	if (file_exists('config.php')) unlink('config.php');
	if (!file_put_contents('config.php', $conf, LOCK_EX)) die('<li>Файл config.php в корне сайта не перезаписан! Попробуйте его удалить и перезапустить установку CMS.');
	echo "<li>Файл config.php настроен";

	// Запуск install.php
	if (file_exists("install/install.php")) include ("install/install.php");
	else die('<li>Файл install.php в папке install не найден!');
	echo "<li>Установка БД окончена";
	// Запуск обновления
	//include ("update_old_version.php");

	// Добавление дизайна

	// Добавляем настройку конфигурации сайта
	if ($design != 0) {
		// Копируем файлы темы
		copy_folder("install/themes/".$design, "theme");
		echo "<li>Картинки дизайна скопированы в папку theme";
		if (file_exists("install/themes/".$design."_install.php")) include ("install/themes/".$design."_install.php");
		else die('<li>Файл '.$design.'_install.php в папке install/themes не найден!');
		echo "<li>Установка дизайна в БД окончена";
	} else {
		$db->sql_query("INSERT INTO `".$prefix."_config` VALUES ( 'Название сайта', '2013', '".$email."', '', '', '', '', '', '0', '0', '0', '|||||||||||||||||||||||||||||||||||||||||||||', '0', '1|1|1|1|1|0|0|1|26', '', '4', '0', '0', '.ht_backup');") or die('Не удалось записать настройку конфигурации сайта');
		$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '1', '0', '', 'Главный дизайн', 'что-то в шапке<br>[содержание]<br>футер сайта', '20', '', '0', 'pages', '0', '', '');");
		$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '24', '2', 'index', 'Главная страница', 'pages|design=1', 'Текст главной страницы', '', '0', 'pages', '0', '', '');");
		$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '20', '1', 'index', 'Главный стиль', '

		.img_left {float: left; padding-right: 10px;}
		.img_right {float: right; padding-left: 10px;}

		a img {	border:none;}
		img[align=left] {
		 margin-right: 15px;
		}
		img[align=right] {
		 margin-left: 15px;
		}
		img[align=center] {
		 display: block;
		 margin: 0 auto !important;
		}

		.venzel { display:none; }
		.razdel { display:none; }

		/* Открыть все */
		.open_all {display: block; float: right;}
		.open_all_small, a:link .open_all_small, a:visited .open_all_small, a:hover .open_all_small {}
		a.open_all_link {}
		', '', '', '0', 'pages', '0', '', '');");
	}
	echo "<li>Настройка конфигурации сайта закончена";
	
	// Создаем разделы
	$useit = 1; // Если верстка использует разделы с общим дизайном (т.е. для главной и остальных страниц используется один Главный дизайн), иначе используется другой дизайн, назначенный в верстке.

	if ($design != 2) { // во втором дизайне не используется создание разделов
		$menu_block = array();
		
		$razdel = explode("\n",$razdel);
		foreach ($razdel as $raz) {
			$raz = explode("|",$raz);
			if (isset($raz[1])) $r = trim($raz[1]); else $r = "";
			$r2 = trim($raz[0]);
			$engname = array("Новости"=>"news","Наши новости"=>"news","Статьи"=>"article","Наши статьи"=>"article","Советы"=>"tips","Наши советы"=>"tips","Я читаю"=>"read","Я пишу"=>"write","Я смотрю"=>"see","Я слушаю"=>"listen","Я играю"=>"play","Услуги"=>"services","Наши услуги"=>"services","О компании"=>"about","Производство"=>"production","Наше производство"=>"production","Продукты"=>"product","Наши продукты"=>"product","Акции"=>"promo","Наши акции"=>"promo","Скидки"=>"sale","Наши скидки"=>"sale","Магазины"=>"shops","Наши магазины"=>"shops","Прайс-лист"=>"price","Прайс"=>"price","Цены"=>"price","Расценки"=>"price","Стоимость"=>"price","Каталог"=>"catalog","Наш каталог"=>"catalog","Франшиза"=>"franchise","Дилерство"=>"dealership","Дилеры"=>"dealers","Галерея"=>"gallery","Фотогалерея"=>"photo","Наше фото"=>"photos","Наши фото"=>"photos","Видеогалерея"=>"video","Сотрудничество"=>"partnership","Партнерство"=>"partnership","Партнеры"=>"partners","Наши партнеры"=>"partners","Вакансии"=>"vacancy","Наши вакансии"=>"vacancy","Работа"=>"job","Оставить заявку"=>"request","Заявка"=>"request","Напишите нам"=>"email","Пишите нам"=>"email","Отзывы"=>"reviews","Отзывы о нас"=>"reviews","Контакты"=>"contacts","Наши контакты"=>"contacts","Обратная связь"=>"feedback","Связь с нами"=>"feedback","Связаться с нами"=>"feedback","Связаться со мной"=>"feedback","О магазине"=>"about_shop","Товары"=>"products","Наши товары"=>"products","Бренды"=>"brands","Наши бренды"=>"brands","Спецпредложения"=>"special","Наши спецпредложения"=>"special","Спецпредложение"=>"special","Спец. предложения"=>"special","Специальное предложение"=>"special","Специальные предложения"=>"special","Гарантия"=>"warranty","Оплата"=>"payment","Как оплатить"=>"payment","Доставка"=>"delivery","Отзывы клиентов"=>"client_reviews","Клиенты"=>"clients","Наши клиенты"=>"clients","О сайте"=>"about_site","О нас"=>"about_us","Обо мне"=>"about_me","Интересно"=>"interesting","Это интересно"=>"interesting","Интересное"=>"interesting","Полезно"=>"useful","Полезное"=>"useful","Интересные статьи"=>"interesting","Полезные статьи"=>"useful","Информация"=>"info","Игра"=>"game","Игры"=>"games","Блог"=>"blog","Форум"=>"forum","Гостевая"=>"guestbook","Гостевая книга"=>"guestbook","Заметки"=>"notes","Портфолио"=>"portfolio","Мои работы"=>"my_work","Работы"=>"portfolio","Наша работа"=>"portfolio","Работы участников"=>"works","Друзья"=>"friends","Мои друзья"=>"friends","Фото"=>"photo","Видео"=>"video","Музыка"=>"music","Библиотека"=>"books","От автора"=>"author","Вступление"=>"first","Сообщество"=>"community","Секта"=>"cult","Курсы"=>"courses","События"=>"events","Уроки"=>"lessons","Общение"=>"communion","Творчество"=>"creation","Наш блог"=>"blog","Сотрудники"=>"workers","Работники"=>"workers");
			if (isset($engname[$r2])) {
				$r = $engname[$r2];
			} else {
				if ($r=="") $r = translit_n($r2);
				else $r = translit_n($r);
				$r = strtolowX($r);
			}
			if ($r2 != "Главная" && $r2 != "Главная страница" && $r2 != "главная страница" && $r2 != "главная") {
				//Настройки для разделов, основываясь на их названии
				$text = "lim=15&comments=0"; // 15 страниц, комментарии выключены
				if ($r2 == "Новости" || $r2 == "Статьи" || $r2 == "Советы" || $r2 == "Я читаю" || $r2 == "Я пишу" || $r2 == "Я смотрю" || $r2 == "Я слушаю" || $r2 == "Блог" || $r2 == "Заметки" || $r2 == "Музыка" || $r2 == "Библиотека" || $r2 == "Работы участников" || $r2 == "Курсы" || $r2 == "События" || $r2 == "Творчество" || $r2 == "Наш блог" || $r2 == "Общение") $text = "lim=10&comments=1&comments_add=1&vetki=2&comments_mail=1&comments_adres=1"; // 10 страниц на листе, комментарии включены
				if ($r2 == "Каталог" || $r2 == "Прайс" || $r2 == "Прайс-лист" || $r2 == "Галерея" || $r2 == "Товары" || $r2 == "Услуги" || $r2 == "Производство" || $r2 == "Продукты" || $r2 == "Акции" || $r2 == "Скидки" || $r2 == "Спецпредложения" || $r2 == "Вакансии") $text = "lim=100&comments=0"; // 500 страниц на листе, комментарии выключены

				// Настройка типа разделов - одна страница или несколько
				$soderganie = "[содержание]";
				if ($r2 == "Услуги" || $r2 == "Наши услуги" || $r2 == "О компании" || $r2 == "Производство" || $r2 == "Наше производство" || $r2 == "Акции" || $r2 == "Наши акции" || $r2 == "Скидки" || $r2 == "Наши скидки" || $r2 == "Магазины" || $r2 == "Наши магазины" || $r2 == "Прайс-лист" || $r2 == "Прайс" || $r2 == "Цены" || $r2 == "Расценки" || $r2 == "Стоимость" || $r2 == "Франшиза" || $r2 == "Дилерство" || $r2 == "Дилеры" || $r2 == "Сотрудничество" || $r2 == "Партнерство" || $r2 == "Партнеры" || $r2 == "Наши партнеры" || $r2 == "Вакансии" || $r2 == "Наши вакансии" || $r2 == "Оставить заявку" || $r2 == "Заявка" || $r2 == "Напишите нам" || $r2 == "Пишите нам" || $r2 == "Отзывы" || $r2 == "Отзывы о нас" || $r2 == "Контакты" || $r2 == "Наши контакты" || $r2 == "Обратная связь" || $r2 == "Связь с нами" || $r2 == "Связаться с нами" || $r2 == "Связаться со мной" || $r2 == "О магазине" || $r2 == "Бренды" || $r2 == "Наши бренды" || $r2 == "Специальное предложение" || $r2 == "Спецпредложение" || $r2 == "Гарантия" || $r2 == "Оплата" || $r2 == "Как оплатить" || $r2 == "Доставка" || $r2 == "Отзывы клиентов" || $r2 == "От автора" || $r2 == "Наши клиенты" || $r2 == "О сайте" || $r2 == "О нас" || $r2 == "Обо мне" || $r2 == "Игра" || $r2 == "Друзья" || $r2 == "Секта" || $r2 == "Сообщество" || $r2 == "Общение" || $r2 == "Сотрудники" || $r2 == "Работники") 
					$soderganie = "[название]<br>Текст раздела «".$r2."». Для редактирования откройте Администрирование — слева выберите этот раздел, затем справа нажмите по кнопке Редактировать.<br>Блок &#91;название&#93; в данном случае выводит название раздела.<br>Если вы хотите вывести (вместо названия и последующего произвольного текста) статьи, добавленные в этот раздел — напишите блок &#91;содержание&#93; вместо блока &#91;название&#93;.<br>Более подробная справка доступна при редактировании раздела.";
				$namo = mysql_real_escape_string(stripcslashes($r));
				$title = mysql_real_escape_string(stripcslashes($r2));
				$text = mysql_real_escape_string($text);
				if ($title != "") $db->sql_query("INSERT INTO ".$prefix."_mainpage (`id`, `type`, `name`, `title`, `text`, `useit`, `shablon`, `counter`, `tables`, `color`, `description`, `keywords`) VALUES (NULL, '2', '".$namo."', '".$title."', 'pages|design=".$useit."&designpages=0&".$text."', '".$soderganie."', '', '0', 'pages', '0', '', '".$title."');") or die('Не удалось создать. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');

				$r = "-".$r;
			} else $r = "/";
			// Создаем блок меню из разделов
			$menu_block[] = "[элемент открыть][url=".$r."]".$r2."[/url][элемент закрыть]";
		}
		$menu_block = implode("\n",$menu_block);
		/* Записываем меню
			5 – вертикальное 1 уровень
			2 – вертикальное 2 уровня (не желательно)
			6 – вертикальное 3 уровня
			3 – горизонтальное 1 уровень (слева)
			1 – горизонтальное 1 уровень (по ширине 100%)
			0 – горизонтальное 3 уровня (слева)
			4 – горизонтальное 3 уровня (слева, открывается вверх)
			7 – KickStart вертикальное 3 уровня (слева)
			8 – KickStart вертикальное 3 уровня (справа)
			9 – KickStart горизонтальное 3 уровня (слева) */
		$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES (NULL, '3', '10', 'Главное меню', '".$menu_block."', '|design=0&show_in_razdel=все&no_show_in_razdel=&html=0&titleshow=0&menu=0', 'block-main_menu', '0', 'pages', '0', '', '');");
	}
	// Добавляем страницы в некоторые разделы

	// Добавляем админа
	$db->sql_query("INSERT INTO `".$prefix."_authors` VALUES ( '".$a."', 'BOG', '".$pass."', '1', '', '0');") or die ('<li>Администратор не был добавлен в базу данных');
	echo "<li>Права администратора установлены";

	// Отправка email админу
	$siteurl = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
	if ($siteurl == "") $siteurl = "localhost";

	if ($email != "") mail($email, '=?koi8-r?B?'.base64_encode(convert_cyr_string($a.", пароль для сайта ".$siteurl, "w","k")).'?=', "<h3>Здравствуйте, ".$a."!</h3><b>Вы создали сайт ".$siteurl."</b><br>Ваш псевдоним для входа в администрирование: ".$a."<br>Пароль: ".$pass."<br>Для входа в администрирование, перейдите на сайт по <a href='http://".$siteurl."/red'>ссылке</a>, введите псевдоним и пароль и нажмите «Войти».<br><br><br><br>Отвечать на это письмо не нужно - оно было создано сайтом автоматически.", "Content-Type: text/html; charset=utf-8\r\nFrom: ".$email."\r\n");

	// Переход в админку
	removeDirectory('install');
	rmdir('install');
	echo "</div>
	<style>.del {display:none;}</style>
	<div class=\"container\" style=\"background:url('images/fon.png');\">
	<h1>Установка успешно завершилась.</h1>
	<p>Папка «install» удалена.</p>
	<h4><ul>
	<li><a target='_blank' href='/'>Перейти на сайт</a><br>
	<li>Перед началом работ над сайтом <strong>обязательно</strong> откройте и сохраните <a target='_blank' href='sys.php?op=options'>Настройки</a>!
	<li><a target='_blank' href='red'>Перейти в Администрирование сайта</a> — http://".$_SERVER["HTTP_HOST"]."/red
	</ul></h4>
	<p>Минимальная документация встроена в CMS (кнопка «Помощь» в Администрировании и подсказки).<br>Если её недостаточно — пишите на <a href='mailto:13i@list.ru'><strong>13i@list.ru</strong></a> или стучитесь в skype <a href='skype:angel13i?add'><strong>angel13i</strong></a> — вы получите ответы на все вопросы, после чего встроенная помощь будет расширена и дополнена. Также принимаются предложения и пожелания.
	</div>
	</body>
	</html>";
	die;
}

// НАЧАЛО УСТАНОВКИ =====================================
// Проверка версии PHP
$phpversion = preg_replace('/[a-z-]/', '', phpversion());
if ($phpversion{0}<4) die ('Версия PHP ниже плинтуса. Где же ты нарыл такое старьё?! 0_о');
if ($phpversion{0}==4) die ('Версия PHP — 4. Попросите хостинг-компанию установить PHP как минимум версии 5.2.1');

function removeDirectory($dir) { // Удаляем папку с файлами (install)
    if ($objs = glob($dir."/*")) {
       foreach($objs as $obj) {
         is_dir($obj) ? removeDirectory($obj) : unlink($obj);
       }
    }
    rmdir($dir);
  }

function generate_password($number) {  // Генерируем пароль
    $arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','r','s','t','u','v','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','R','S','T','U','V','X','Y','Z','1','2','3','4','5','6','7','8','9','0');
    $pass = "";
    for($i = 0; $i < $number; $i++) { // Вычисляем случайный индекс массива
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
}
$pass = generate_password(20);
$pass_bd = generate_password(15);

// Azərbaycan (inkişaf)
$languages = array("Albanian"=>"sq",
"Arabic"=>"ar",
"Argentinian Spanish"=>"es_ar",
"Azərbaycan"=>"az",
"Belorussian"=>"by",
"Bosnian"=>"ba",
"Brazilian Portuguese"=>"pt_br",
"Bulgarian"=>"bg",
"Catalan"=>"ca",
"Chinese Simplified"=>"zh_cn",
"Chinese Traditional"=>"zh_tw",
"Croatian"=>"hr",
"Czech"=>"cs",
"Danish"=>"da",
"Dutch"=>"nl",
"English"=>"en",
"Esperanto"=>"eo",
"Finnish"=>"fi",
"French"=>"fr",
"German"=>"de",
"Greek"=>"el",
"Hungarian"=>"hu",
"Hebrew"=>"he",
"Indonesian"=>"id",
"Italian"=>"it",
"Japanese"=>"ja",
"Korean"=>"ko",
"Latvijas"=>"lv",
"Lithuanian"=>"lt",
"Macedonian"=>"mk",
"Norwegian (Bokmål)"=>"no_NB",
"Persian"=>"fa",
"Polski"=>"pl",
"Portuguese"=>"pt_pt",
"Romanian"=>"ro",
"Русский"=>"ru",
"Serbian (Cyrillic)"=>"sr-cir",
"Serbian (Latin)"=>"sr-lat",
"Slovak"=>"sk",
"Slovenian"=>"sl",
"Spanish"=>"es",
"Swedish"=>"sv",
"Thai"=>"th",
"Turkish"=>"tr",
"Український"=>"ua",
"Vietnamese"=>"vi");
?>
<!DOCTYPE html>
<? $lang_install = "en";
if (isset($_REQUEST['lang_admin'])) $lang_install = $_REQUEST['lang_admin']; ?>
<!--[if lt IE 7 ]><html class="ie ie6" lang="<? echo $lang_install; ?>"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="<? echo $lang_install; ?>"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="<? echo $lang_install; ?>"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="<? echo $lang_install; ?>"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Установка CMS «ДвижОк»</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="../includes/css-frameworks/skeleton/base.css">
	<link rel="stylesheet" href="../includes/css-frameworks/skeleton/skeleton.css">
	<link rel="stylesheet" href="../includes/css-frameworks/skeleton/layout.css">
	<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="shortcut icon" href="../images/favicon_cms.png">
	<style>input {width:100%;}</style>
	<script src='includes/jquery.lightbox.js'></script>
	<script src='includes/jquery.ad-gallery.js'></script>
	<script>$(document).ready(function(){ $('.lightbox').lightbox({ fitToScreen: true, imageClickClose: false }); var galleries = $('.ad-gallery').adGallery(); $('#switch-effect').change( function() { galleries[0].settings.effect = $(this).val(); return false; } ); });</script>
	<link rel='stylesheet' href='includes/lightbox.css' media='screen' />
</head>
<body style="background:url('images/adfon/21.png')">
<? 
if (!isset($_REQUEST['lang'])) {
	$siteurl = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
	if ($siteurl == "") $siteurl = "localhost";
	if ($_SERVER["REQUEST_URI"] == "/install/") { echo '<meta http-equiv="refresh" content="0; url=http://'.$siteurl.'">'; exit; }
	if ($_SERVER["REQUEST_URI"] != "/") { 
		echo '<div class="container" style="background:url(\'images/fon.png\'); z-index:10; margin: auto; top:0;"><h1>Устанавливать в некорневую папку нельзя.</h1><h3>Вы можете установить CMS «ДвижОк» в корневую папку домена второго/третьего уровня на хостинге или локальном сервере.</h3></div>'; 
		die(); 
	}
?>
<form>
	<div class="container" style="background:url('images/fon.png'); z-index:10; margin: auto; top:0;">
		<div class="sixteen columns" style="background:url('install/world.png') no-repeat 360px 50px; min-height:155px;">
			<h1 class="remove-bottom" style="margin-top: 40px">EngineCMS Install</h1>
			<h5 style="margin-bottom:30px;"><? echo $ver; ?></h5>

			<img src="install/users.png" style="float:left; margin-right:10px;"> 
			<strong>Select site language</strong>:<br><select name="lang">
				<option value="ru" selected>Русский</option>
				<option value="en">English (in development)</option>
				<option value="pl">Polski (stanie developerskim)</option>
				<option value="ua">Український (в розробці)</option>
				<option value="lv">Latvijas (ir izstrādes procesā)</option>
			</select>
			<img src="install/admin.png" style="float:left; margin-right:10px;"> 
			<strong>Select administration language</strong>:<br><select name="lang_admin">
				<option value="ru" selected>Русский</option>
				<option value="en">English (in development)</option>
				<option value="pl">Polski (stanie developerskim)</option>
				<option value="ua">Український (в розробці)</option>
				<option value="lv">Latvijas (ir izstrādes procesā)</option>
			</select>
			<button type="submit" id="submit" style="margin-left: 30px"><h3>Next →</h3></button>
		</div>
		
	</div>
</form>
<? } else { 
	$lang = $_REQUEST['lang'];
	$lang_admin = $_REQUEST['lang_admin'];
	// Подключение перевода

	
	?>
<form>
	<input type="hidden" name="lang" value="<? echo $lang; ?>">
	<input type="hidden" name="lang_admin" value="<? echo $lang_admin; ?>">
	<div class="container" style="background:url('images/fon.png');">
		<div class="sixteen columns" style="background:url('install/logo.png') no-repeat right 5px; min-height:155px;">
			<h1 class="remove-bottom" style="margin-top: 40px">Установка CMS «ДвижОк»</h1>
			<h5>Версия <? echo $ver; ?></h5>
		</div>
		<div class="one-third column">
			<h3>1. База данных</h3>
			<ul class="square">
				<li><strong>Сервер MySQL</strong> (host)<br><input id="dbhost" name="dbhost" value="localhost"></li>
				<li><strong>Имя пользователя</strong><br><input id="dbuname" name="dbuname" value="root"></li>
				<li><strong>Пароль пользователя</strong><br><input id="dbpass" name="dbpass" value="<? echo $pass_bd; ?>"></li>
				<a id='dbname_show' onclick='x=$("#dbhost").val(); b=$("#dbuname").val(); c=$("#dbpass").val(); $.ajax({ url: "index.php?db=" + x + "&dbuname=" + b + "&dbpass=" + c, cache: false, dataType: "html", beforeSend: function(){ $("#db").html("Загрузка..."); }, success: function(data) { $("#db").html(data); if (data != "Ошибка доступа. Неправильно введены сервер, пользователь или пароль.") { $("#column2").show(); $("#column3").show(); $("#li1").show(); $("#li2").show(); $("#dbname_show").hide(); } } });' class='button'><h4>Определить имя базы данных</h4></a><div id='db'></div></li>
				
				<li id="li1" style="display:none;"><a onclick='$("#prefix_show").toggle();' style='color:darkgreen; cursor:pointer; text-decoration:none; border-bottom:1px dashed green;'>Префикс таблиц</a> (необязательно)<div id='prefix_show' style='display:none'><input name="prefix" value="dvizhok"><br>Если на хостинге один сайт или на каждый сайт есть своя база данных, префикс менять необязательно.</div></li>
				<li id="li2" style="display:none;"><select name="table_delete" style="width:100%;"><option value="true">Удалить существующие таблицы</option><option value="false" selected>Не удалять, если таблицы созданы</option></select></li>
			</ul>
		</div>
		<div class="one-third column" id="column2" style="display:none;">
			<h3>2. Администратор</h3>
			<ul class="square">
		<script>
			$(document).ready(function(){
				var pattern = /^[a-zа-я0-9_-]+@[a-zа-я0-9-]+\.([a-zа-я]{1,6}\.)?[a-zа-я]{2,6}$/i; //name-_09@mail09-.ru
				var mail = $('#mail');
				mail.blur(function(){
					if(mail.val() != ''){
						if(mail.val().search(pattern) == 0){
							$('#valid').text('Отлично.');
							$('#submit').attr('disabled', false);
							mail.removeClass('error').addClass('ok');
						}else{
							$('#valid').text('Email не подходит');
							$('#submit').attr('disabled', true);
							mail.addClass('error');
						}
					}else{
						$('#valid').text('Поле email не должно быть пустым!');
						mail.addClass('error');
						$('#submit').attr('disabled', true);
					}
				});
			});	
		</script>
				<li><strong>Электронная почта</strong><br><input name="email" id="mail" value=""><span id="valid"></span></li>
				<li><strong>Псевдоним (логин)</strong><br><input name="a" value="admin"><br><b>Желательно поменять!</b></li>
				<li><strong>Пароль</strong><br><input name="pass" value="<? echo $pass; ?>"><br>
					Будет отправлен на указанный email</li>
				<li id='all_show'><a onclick='$("#blo_show").show();$("#cash_show").show();$("#all_show").hide();' style='color:darkgreen; cursor:pointer; text-decoration:none; border-bottom:1px dashed green;'>IP-блокировка и кеш отключены</a></li>
				<li id='blo_show' style='display:none'><strong>Блокировка по IP-адресу</strong>:<br><select style="width:100%;" name="ipban"><option value="true">Включить</option><option value="false" selected>Отключить</option></select></li>
				<li id='cash_show' style='display:none'><strong>Кеширование страниц сайта</strong>:<br><select style="width:100%;" name="site_cash"><option value="false">Отключено</option><option value="file" disabled>в файлы (доработка в процессе)</option><option value="base" disabled>в базу данных (доработка в процессе)</option></select></li>
			</ul>
			
		</div>
		<div class="one-third column" id="column3" style="display:none;">
			<h3>3. Дизайн</h3>
			<p>Готовый дизайн служит для освоения «Движка» или быстрой разработки сайтов.
			<select size="3" id="design_skin" name="design" style="width:100%;" onchange="x='1';
			if ( $('#design_skin :selected').val() == 2 ) { $('#type').hide(); $('#razdel_show').hide(); $('#razdel').html(''); $('#submit').show(); $('#type').val(''); $('#razdels').hide(); }
			else { $('#type').show(); if ($('#type :selected').val() == '') $('#submit').hide(); $('#razdels').show(); }
			if ( $('#design_skin :selected').val() != 0 ) 
				$('#design').html('<a target=\'_blank\' class=\'lightbox\' title=\'Увеличить\' href=\'install/themes/' + $('#design_skin :selected').val() + '.jpg\'><img src=\'install/themes/' + $('#design_skin :selected').val() + '.jpg\' width=\'100%\' alt=\'\'></a>');
			else 
				$('#design').html('');">
				<option value="0">без дизайна (для вставки верстки)</option>
				<option value="1" selected>«Тестовый»</option>
				<option value="2">Блог аля «Эгея» (E2 от Ильи Бирмана)</option>
			</select>
			<!-- стрелки выбора дизайна < > <a target='_blank' href='design_1.jpg'><img src='design_1.jpg' height=190></a> -->
			<div id='design'><a target='_blank' class='lightbox' title='Увеличить' href='install/themes/1.jpg'><img src='install/themes/1.jpg' width='100%' alt=''></a></div>

			<h3 id='razdels'>4. Разделы</h3>
			<select id='type' name="type" style="width:100%;" onchange="x='';
			if ( $('#type :selected').val() == 'company') x='Главная\nУслуги\nО компании\nПроизводство\nПродукты\nНовости\nАкции\nСкидки\nМагазины\nПрайс-лист\nКаталог\nФраншиза\nДилерство\nГалерея\nСотрудничество\nВакансии\nОставить заявку\nНапишите нам\nСтатьи\nСоветы\nОтзывы\nКонтакты';
			if ($('#type :selected').val() == 'shop') x='О магазине\nТовары\nПрайс-лист\nБренды\nНовости\nАкции\nСкидки\nСпецпредложения\nГарантия\nОплата\nДоставка\nПартнеры\nСотрудничество\nВакансии\nОтзывы клиентов\nКонтакты';
			if ($('#type :selected').val() == 'blog') x='О сайте\nОбо мне\nИнтересно\nЯ читаю\nЯ пишу\nЯ смотрю\nЯ слушаю\nБлог\nСтатьи\nЗаметки\nПортфолио\nДрузья\nФото\nВидео\nМузыка\nБиблиотека\nСвязаться со мной\nОт автора';
			if ($('#type :selected').val() == 'group') x='О нас\nСообщество\nРаботы участников\nКурсы\nСобытия\nОбщение\nТворчество\nСотрудничество\nНаш блог\nКонтакты';
			$('#razdel').val(x);
			if ( $('#type :selected').val() == '') $('#razdel_show').hide();
			else { $('#razdel_show').show(); $('#submit').show(); $('#type option:first').attr('disabled', 'disabled'); } ">
				<option value="">Выберите тип сайта ↓</option>
				<option value="company">Компания / Организация</option>
				<option value="shop">Магазин / Каталог</option>
				<option value="blog">Личный сайт / Блог</option>
				<option value="group">Сообщество / Группа</option>
			</select>
			<div id='razdel_show' style='display:none'>
				<strong style='color:darkred'>Удалите/дополните разделы</strong> ниже.
				<br>Их адреса будут созданы <a onclick='$("#auto_show").toggle();' style='color:darkgreen; cursor:pointer; text-decoration:none; border-bottom:1px dashed green;'>автоматически</a><br>
				<span id='auto_show' style='display:none'>
				или их можно написать сразу после названия раздела, отделив символом «|», например: О нас|about</span>
				<textarea id='razdel' name='razdel' rows='9' style='width:100%;'></textarea>
			</div>
		</div>

		<div class="column">
			<button type="submit" id="submit" style="float:right; margin-left: 30px; display:none;"><h3>Установить →</h3></button>
			<hr>
			<?
			if ($phpversion{0}==5 && $phpversion{2}<2) echo "<p><b style='color:red;'>Версия PHP — 5.".$phpversion{2}.". Рекомендуется использовать PHP как минимум версии 5.2.1";
			if ( ( $phpversion{0}==5 && $phpversion{2}>3 ) || $phpversion{0}>5) echo "<p style='color:red;'>На версии PHP 5.4 (и выше) CMS не тестировалась — вы можете сообщить разработчику обо всех ошибках на почту 13i@list.ru";
			if (!function_exists('curl_init')) echo "<p style='color:red;'>Желательно включить поддержку cURL на вашем хостинге.";
			if (!extension_loaded('imagick') || !class_exists("Imagick")) echo "<p style='color:red;'>Библиотека Imagick не установлена – придется  уменьшать размер больших фотографий (более 1000 пикселей по ширине) перед вставкой в редактор. Советуем перейти на другой хостинг с поддержкой этой библиотеки или договориться с текущим хостингом о её подключении. На виртуальном локальном сервере чаще всего эта библиотека не работает.";
			?>
		</div>
	</div>
</form>
<? } ?>
</body>
</html>