<?php
// Всё, что вы накодите, может быть использовано против вас в багтрекере.
define('MODULE_FILE', true);
define ('ROOT_DIR', dirname ( __FILE__ ) ); // доработать!

session_start(); // Для капчи (проверочный код-картинка от спама) // проверить вызов
require_once("mainfile.php");
global $strelka, $siteurl, $prefix, $name, $db, $admin, $sitename, $pagetitle, $pagetitle2, $registr, $pogoda, $flash, $keywords, $description, $counter, $startdate, $adminmail, $keywords2, $description2, $stopcopy, $nocash, $blocks, $http_siteurl, $display_errors, $gallery_css3, $gallery_lightbox, $gallery_carusel, $gallery_sly, $deviceType, $scrollyeah, $lightload, $spin;
$nocash = $gallery_css3 = $gallery_lightbox = $gallery_carusel = $gallery_sly = $gallery_sly_full = $mp3_player = false;
if ($name == "") $name = "index";
$index_ok = true; // индексирование поисковиками
// Сборка дизайна с разделом и Блоки
	
	###################################################### БЛОКИ
	$block = ""; // Определение раздела

$pagetitles = array(
	"-email" => ss("Подписка на рассылку новостей сайта")." — ",
	"-search" => ss("Поиск")." — ",
	"-tags" => ss("Тэги")." — ",
	"-register" => ss("Регистрация")." — ",
	"-login" => ss("Вход")." — ",
	"-user" => ss("Страница пользователя"),
	"-users" => ss("Личная анкета")." — ",
	"-adduser" => ss("Добавление публикации")." — ",
	"-edituser" => ss("Редактирование личной анкеты")." — ",
	"-logout" => ss("Вы вышли")." — ");
if (isset($pagetitles[$name])) {
    list($block, $stil) = include('page/'.substr($name, 1).'.php'); 
    $pagetitle = $pagetitles[$name];
} else {
	global $title_razdels, $txt_razdels, $useit_razdels, $pid;

	// Настройки раздела по-умолчанию
	$designpages = 0; // т.е. дизайн для страниц = дизайну разделов

	if (!isset($title_razdels[$name])) {
		$title_razdels[$name] = "";
		// Проверяем на наличие среди паролей
		foreach ($pass_razdels as $key => $pass_razdel) {
			if (in_array($name, $pass_razdel)) {
				$pass_name = $name;
				$pass_rename = $name = $key;
			}
		}
	}
	
	if ($title_razdels[$name] == "") {
		$main_title = ""; // ИЗМЕНА на mainfile
		$main_file = "";
		$main_options = "";
	} else {
		if (isset($pass_razdels[$name])) $index_ok = false;
		$main_title = $title_razdels[$name];
		$main_file = array();
		if (trim($txt_razdels[$name])!="") {
			$main_file = explode("|",  $txt_razdels[$name]);
			$main_options = $main_file[1];
			$main_file = $main_file[0];
		} else {
			$main_options = "";
			$main_file = "";
		}
	}
	// Содержание главной страницы раздела
	if (isset ($useit_razdels[$name]) ) $soda = $useit_razdels[$name]; else $soda = "";

	parse_str($main_options); // Включили все настройки раздела

	// Выбор дизайна:
	if ($pid == 0) { // для разделов
		if (isset($design_tablet)) if ($design_tablet != 0 && $deviceType == "tablet") $design = $design_tablet;
		if (isset($design_phone)) if ($design_phone != 0 && $deviceType == "phone") $design = $design_phone;
	} else { // для страниц
		if (isset($designpages)) if ($designpages != 0) $design = $designpages;
		if (isset($designpages_tablet)) if ($designpages_tablet != 0 && $deviceType == "tablet") $design = $designpages_tablet;
		if (isset($designpages_phone)) if ($designpages_phone != 0 && $deviceType == "phone") $design = $designpages_phone;
	}
	// Разберемся со стилями id, type, name, opis, sort, pages, parent
	$style_type = array();
	$style_name = array();
	$style_pages = array();
	$sql7 = "SELECT id, type, name, pages FROM ".$prefix."_spiski";
	$result7 = $db->sql_query($sql7);
	while ($row7 = $db->sql_fetchrow($result7)) {
		$style_id = $row7['id'];
		$style_type[$style_id] = $row7['type'];
		$style_name[$style_id] = $row7['name'];
		$style_pages[$style_id] = $row7['pages'];
	}

	// Определение дизайна и использованных стилей в дизайне
	if (isset($design)) list($block, $stil) = design_and_style($design); else $block = "0";
	if ($block == "0") { Header("Location: error.php?code=666"); die; }
	//die("Ошибка: «Адрес раздела» (".$name.") введен неправильно. Перейдите на <a href=/>Главную страницу</a>.");

	// Получаем список всех папок
	$titles_papka = titles_papka(0,1);

	// Определяем Главный раздел
	/*
	if ($name == "index") {
		// Смотрим чему равно значение Главной страницы
		global $useit_razdels; // ЗАМЕНА mainpage2
		$name13 = $useit_razdels[$name];

		// Ставим содержание главной страницы
		$main_file = $name13;
		$main_options = "no";
	}
	*/
	global $soderganie, $soderganie2, $options, $ModuleName, $tip, $DBName, $page_cat, $http_siteur, $cid, $pid, $include_tabs;
	$options = $main_options;
	$ModuleName = $main_title;
	$DBName = $name; // важно не менять!
	$tip = $main_file;

	if (file_exists("page/main.php") and $main_options != "no") {
		require_once("page/main.php");

		$soda = explode(aa("[следующий]"),$soda);
		// $soda[0] - для всех
		// $soda[1] - только для главной страницы
		// $soda[2] - только для папок
		// $soda[3] - только для страниц
		$soda_col = count($soda);
		if (strpos(" ".$soda[0], aa("[содержание]"))) $soderganie = str_replace(aa("[содержание]"), $soderganie, $soda[0]);
		else {
			$soderganie = str_replace(aa("[название]"), "<div class='cat_title'><h1 class='cat_categorii_link'>".$ModuleName."</h1></div>", $soda[0]);
			$soderganie = str_replace(aa("[страницы]"), $soderganie2, $soderganie);
			// Добавление табов
		    if (strpos(" ".$soderganie, "{{")) {
		      if ($include_tabs == false) { include ('page/tabs.php'); $include_tabs = true; }
		      if (strpos(" ".$soderganie, "{{")) $soderganie = show_tabs($soderganie);
		    }
		}

		if ($cid=="" and ($pid=="" or $pid==0) and $soda_col > 1) {
			if (strpos(" ".$soda[1],aa("[содержание]"))) $soderganie = str_replace(aa("[содержание]"), $soderganie, $soda[1]);
			else {
				$soderganie = str_replace(aa("[название]"), "<div class='cat_title'><A class='cat_categorii_link' href=-".$DBName.">".$ModuleName."</a></div><div class='polosa'></div>", $soda[1]);
				$soderganie = str_replace(aa("[страницы]"), $soderganie2, $soderganie);
			}
		}
		// Содержание главной страницы раздела
		if ($cid!="" and ($pid=="" or $pid==0) and $soda_col > 2) $soderganie = str_replace(aa("[содержание]"), $soderganie, $soda[2]);
		if ($cid=="" and $pid!="" and $pid!=0 and $soda_col > 3) $soderganie = str_replace(aa("[содержание]"), $soderganie, $soda[3]); 

		$block = str_replace(aa("[содержание]"), $soderganie, $block); 
		// Тело раздела ставится в дизайн

		// Нумерация страниц ставится в дизайн
		if (strpos(" ".$block, aa("[нумерация]"))) {
			global $topic_links_global;
			$block = str_replace(aa("[нумерация]"), $topic_links_global, $block);
		}
	// Ставим содержание модуля
	} else {
		$block = str_replace(aa("[содержание]"), $main_file, $block);
	}
} // крышка определения поиска и тегов

	# НАЧАЛО Определение блоков и их заполнение
	$sql2 = "select id,name,title,text,useit,shablon,color from ".$prefix."_mainpage where `type`='3' and `tables`='pages'"; 
	// `name` != '$name' and 
	// ИЗМЕНА: вынесено за пределы массива и вместо * замена
	$result2 = $db->sql_query($sql2); // заменить имена переменных :)
	$nameYYY = array();
	$titleYYY = array();
	$textYYY = array();
	$useitYYY = array();
	$shablonYYY = array();
	$block_colorYYY = array();
	while ($row2 = $db->sql_fetchrow($result2)) {
		$idYYY = $row2['id'];
		$nameYYY[$idYYY] = $row2['name'];
		$titleYYY[$idYYY] = trim($row2['title']);
		$textYYY[$idYYY] = trim($row2['text']);
		$useitYYY[$idYYY] = trim($row2['useit']);
		$shablonYYY[$idYYY] = trim($row2['shablon']);
		$block_colorYYY[$idYYY] = $row2['color'];
	}
	// Вставить оптимизацию блоков
for ($iii=1; $iii <= 2; $iii++) { // 2 прохода по обработке блоков и вложенных блоков
	foreach( $nameYYY as $idX => $nameX ) {
		$titleX = trim($titleYYY[$idX]);
		// поиск блока в тексте
		if ( !strpos(" ".$block, "[".$titleX."]") ) continue; // Переход к следующему!
		$textX = $textYYY[$idX];
		$useitX = $useitYYY[$idX];
		$shablonX = $shablonYYY[$idX];
		$block_color = $block_colorYYY[$idX];

	// обнулили все опции блоков от греха подальше
	$titleshow = $reload_one_by_one = $folder = $datashow = $tagdelete = $ipdatauser = $design = $open_all = $catshow = $main = $daleeshow = $openshow = $number = $add = $size = $papki_numbers = $zagolovokin = $menu = $noli = $html = $show_title = $random = $showlinks = $open_new_window = $shablon = $show_new_pages = $reload_link_show = $reload_link_time = $re_menu = 0;
	$opros_type = $limkol = $pageshow = $only_question = $opros_result = $foto_gallery_type = $notitlelink = $foto_num = 1;
	$shablon = $class = $alternative_title_link = $cid_open = $no_show_in_razdel = $watermark = $show_in_papka = "";
	$addtitle = ss("Добавить статью");
	$dal = ss("Далее...");
	$first = "src=";
	$second = ">";
	$third = " ";
	$col_bukv = 50;
	$img_width = 60;
	$img_height = 60;
	$sort = "date desc";
	$papka_sort = "sort, title";
	$razdel_open_name = ss("Открыть раздел"); //
	$razdel_open2_name = ss("Открыть раздел");
	$calendar = ""; // Календарь - перенаправление на дату из списка.
	$show_in_razdel = ss("все");
	$reload_link_text = ss("Показать еще...");

	// для блока расписания
	$specialist = "Специалист";
	$work_time = "Время приема";
	$record = "Записаться на ";
	$all_days = "1";
	$tomorrow_record = "На сегодня запись окончена. Вы можете записаться на другой день.";
	$numberOfMonths = 2;
	$calendar_maxDate_days = 30;
	$calendar_maxDate = 1;
	$next_day = 0;
	$end_hour = 16; // Час начала записи на следующий день и окончания на текущий.
	$show_end_hour = 1;
	$current_day = 0;
	$zapis_na_priem = "Запись на прием";
	$zapis_obrashenie = "Укажите, пожалуйста, телефон, по которому в ближайшее время с вами могут связаться администраторы нашего медицинского центра для подтверждения записи на прием.";
	$zapis_your_name = "Ваше имя:";
	$zapis_your_tel = "Ваш телефон:";
	$zapis_spec = "Врач:";
	$zapis_data = "Время приема:";
	$zapis_send = "Записаться";
	$zapis_zayavka_send = "Ваша заявка успешно отправлена.<br>В ближайшее время мы вам позвоним.";
	$deleted_days = "воскресенье";
	$deleted_dates = ""; //,18.11.2013";

	// для блока карты
	$map_house_address = $map_house_name = $map_house_description = "";
	$map_shablon = '<div style="color:red">$[name]</div><div style="color:#0A0">$[description]</div><div style="color:black">$[dom]</div>';
	$map_yandex_key = 'AIBlZ1IBAAAA8D6sLAIADXX8cFuUyDpQ68hvl-ErRjT9vu0AAAAAAAAAAADCAvqqQC08r3m17iVBNDnpFXnXLw==';
	$map_center = "Москва";
	$map_zoom = 9;

	// Для базы данных
	$base = ""; // Указываем название таблицы БД
	$first = ""; // первая колонка
	$second = ""; // вторая колонка
	$text1 = ""; // текст самой первой ячейки (для блока БД количество по 2 колонкам)
	$direct = "vert"; // направление, gor - горизонт., vert - вертикальное
	$all = 0; // Указывать сколько всего элементов, по умолчанию 0 - не указывать, 1 - указывать.
	$col = ""; // какие поля будут использоваться для вывода информации

	// выделим имени модуля раздела и настройки
	if (mb_substr($useitX, 0, 1) != "|") {
		$useitY = explode("|",$useitX);
		$useitX0 = $useitY[0];
		$useitY = str_replace($useitX0."|","",$useitX);
		$useitX = $useitX0;
		$alternative_title_link = "/-".$useitX."";
	} else {
		$useitY = mb_substr($useitX, 1, mb_strlen($useitX)-1);
		$useitX = $alternative_title_link = "";
	}
	parse_str($useitY);
	
	$class = stripslashes($class);

	$show_in_razdel_array = explode(",", $show_in_razdel);
	$no_show_in_razdel_array = explode(",", $no_show_in_razdel);

	if ($random == 1) $sort = "RAND()";

	if ($alternative_title_link == "" and $useitX != aa("все")) $alternative_title_link = "/-".$useitX."";
	if ($cid_open==aa("все") or $cid_open=="") {} else {$alternative_title_link = "/-".$useitX."_cat_".$cid_open;}

	// Определение дизайна блоков
	$design_open=""; $design_close="";

	$block_title = $titleX;
	$block_title2 = "";
	/////////////////////////////////////
	if ($design != 0) {
		$row7 = $db->sql_fetchrow($db->sql_query("select `text`, `useit` from ".$prefix."_mainpage where `id`='".$design."' and type='0' and `tables`='pages'"));
		$design = explode(aa("[содержание]"), $row7['text']);
		$stile = $row7['useit'];
		// Добавляем стиль дизайна
		if (trim($stile)!="" and $stile!=0) $stil .= "-".str_replace(" ","-",trim($stile));
		/////////////////////////////////////
		if ($nameX == 0) {
		if ($useitX != aa("все") and $nameX != 2 and $useitX != "" and $razdel_open_name != "" and $razdel_open_name != "no") $block_title2 .= "<span class='open_all_small'> &nbsp; &#124; &nbsp; </span> <a href=-".$useitX." title=\"".$razdel_open_name."\" class='open_all_small'><u>".$razdel_open_name."</u></a>";
		}
	/////////////////////////////////////
		$design_open = "<div class='".$shablonX." ".$class."'>".$design[0]; 
		if ($titleshow != 0 and $titleshow != 3) {
			if (($nameX==0 or $nameX==1 or $nameX==4 or $nameX==6 or $nameX==8 or $nameX==9) and $notitlelink==0) {
				$design_open .= "<h3 class=\"h3_block_title class_".$class."\"><a href=".$alternative_title_link." title=\"".$block_title."\" class=\"h3_block_title class_".$class."\">".$block_title."</a>".$block_title2."</h3><div class=polosa></div>";
			} else {
				if ($titleshow != 2) $design_open .= "<h3 class=\"h3_block_title class_".$class."\">".$block_title."</h3><div class='polosa'></div>";
			}
		} 
		if (!isset($design[1])) $design[1] = "";
		$design_close = $design[1]."</div>";
	/////////////////////////////////////
	} else {
		$design_open = "<div class='".$shablonX." ".$class."'>"; 
		if ($titleshow != 0 and $titleshow != 3) {
			if (($nameX==0 or $nameX==1 or $nameX==4 or $nameX==6 or $nameX==8 or $nameX==9) and $notitlelink==0) {
				$design_open .= "<h3 class=\"".$shablonX." h3_block_title class_".$class."\"><a href=".$alternative_title_link." title=\"".$block_title."\" class=\"h3_block_title class_".$class."\">".$block_title."</a>".$block_title2."</h3><div class=polosa></div>";
			} else {
				if ($titleshow != 2) $design_open .= "<h3 class=\"".$shablonX." h3_block_title class_".$class."\">".$block_title."</h3><div class='polosa'></div>";
			}
		}
	$design_close = "</div>";
	}

	if ($html == 1) { $design_close = ""; $design_open = ""; $block_title = ""; $block_title2 = ""; }
	if ($blocks == 1) { $design_close .= "</div>"; $design_open = "<div class='show_block'><div class='show_block_title'><a href='sys.php?op=mainpage&id=".$idX."&red=1' title='".ss("Редактировать")."'>".$titleX."</a> (<a href='sys.php?op=mainpage&id=".$idX."&nastroi=1' title='".ss("Настроить блок")."'>настроить</a>)</div>".$design_open; }

	// Определяем наличие шаблонов
	if (trim($shablon) != "") {
		// Доступ к шаблону
		if (intval($shablon) == trim($shablon)) {
			$text_shablon = text_shablon();
			if ($shablon == 0) $shablon = "";
			else $shablon = $text_shablon[$shablon];
		}
	// Выясняем наличие элементов шаблона
	}

	// Работа с разными типами блоков
	if (!isset($cid)) $cid = 0;
	if ( ( !in_array($name, $show_in_razdel_array) && $show_in_razdel != aa("все") )
		|| in_array($name, $no_show_in_razdel_array)
		|| ( $show_in_papka != $cid && $show_in_papka != "" && in_array($name, $show_in_razdel_array) ) ) {
		$block = str_replace("[".$titleX."]", "", $block);
		$nameX = "-1";
	}

$js = '';
if (strpos(" ".$block, "[".$titleX."]")) { // определение наличия блока на странице
if ($block_color != "1") {
switch ($nameX) {
				//////////////////////////////////////////////////////////////////////////////////////////////////////
case "0": # Блок страниц раздела
	if ($open_new_window == 1) $blank = " target='_blank'"; 
	else $blank = "";

	$text_old = $textX;
	$textX = ""; // В эту переменную входит содержание блока

	$and = "";
	if ($main==1) $and = " and mainpage='1'";
	if ($main==2) $and = " and mainpage!='1'";
	if ($text_old != "") $and .= " and ".stripcslashes($text_old)."";


	if ($useitX=="open_razdel") { // Показывать ВСЕ разделы или выбранный
		//if ($name == "index") {
			//$block = str_replace("[".$titleX."]", "", $block);
			//break 1;
		//} else {
			$and2 = " and module='".$name."'";
		//}
	} elseif ($useitX==aa("все") or $useitX=="") { // Показывать ВСЕ разделы или выбранный
		$and2 = ""; 
	} elseif ( strpos($useitX, ",") ) { // Показывать определенные разделы, через «,»
		$a = array();
		$use = explode(",",$useitX);
		foreach ($use as $value) {
			if ($value != "") $a[] = "module='".$value."'";
		}
		$and2 = " and (".implode(" or ",$a).")";
	} else $and2 = " and module='".$useitX."'";

	if ($cid_open==aa("все") or $cid_open=="") { // Показывать ВСЕ папки или выбранную
		$and3 = "";
		$cid_open2 = "";
	} else {
		$and2 = " and cid='".$cid_open."'";
		$cid_open2 = "_cat_".$cid_open;
	}
	
	if (isset($size)) $limit = " limit ".$number.",".$size; // $lim2 = size - убрано
	
	if ($shablon != "") {
	$sel = "*"; 
		// Ищем списки (доп. поля), относящиеся к нашим страницам по разделу
		$s_names = array();
		$s_opts = array();
		// Определим № раздела
		global $id_razdel_and_bd;
		$r_id = $id_razdel_and_bd[$useitX];
		
		$result5 = $db->sql_query("SELECT `id`, `name`, `text` FROM ".$prefix."_mainpage WHERE `tables`='pages' and (`useit` = '".$r_id."' or `useit` = '0') and `type`='4'");
		while ($row5 = $db->sql_fetchrow($result5)) {
			$s_id = $row5['id'];
			$n = $row5['name'];
			$s_names[$s_id] = $n;
			// Найдем значение всех полей для данных страниц
			$result6 = $db->sql_query("SELECT name, pages FROM ".$prefix."_spiski WHERE type='".$n."'");
			while ($row6 = $db->sql_fetchrow($result6)) {
			$n1 = $row6['name'];
			$n2 = explode(" ", str_replace("  ", " ", trim($row6['pages'])));
				foreach ($n2 as $n2_1 => $n2_2) {
					$s_opts[$n][$n2_2] = $n1;
				}
			}
		}

	} else $sel = "`pid`, `module`, `cid`, `title`, `open_text`, `main_text`, `date`, `mainpage`"; 
	
	$and4 = '';
	// Если в настройках блока указано отображать только последние страницы
	if ($show_new_pages == 1) {
		global $_COOKIE; // Получаем дату куки
		$dat = date("Y-m-d");
		if (isset($_COOKIE['lastdate'])) {
			$tmp = $_COOKIE['lastdate']; 
			if (!preg_match("|^[\d-\|]+$|", $tmp)) die(ss("Попытка взлома не удалась."));
			$tmp = explode("|",$tmp);
			$nowdate = $tmp[0];
			$olddate = $tmp[1];
		} else { 
			$nowdate = "0"; 
			$olddate = "0";
		}
		if ($nowdate == "0") { // Посетил первый раз - показываем всё
			setcookie ('lastdate', $dat."|0",time()+2678400,"/");
		} elseif ($nowdate == $dat && $olddate == "0") { // Посетил сегодня (первый раз) - показываем всё
		} elseif ($nowdate == $dat && $olddate != "0") { // Посетил сегодня (ранее посещал) - показываем только свежее
			if (!empty($useitX)) $textX .= ss("Показаны последние. Показать")." <a href='/-".$useitX."'>".ss("все")."</a>.";
			$and4 = " and DATE(`date`) >= '".$nowdate."'";
		} else { // Посетил не сегодня - показываем только свежее
			setcookie ('lastdate', $dat."|".$nowdate,time()+2678400,"/");
			$and4 = " and DATE(`date`) >= '".$nowdate."'";
		}
	}

	$sql = "SELECT ".$sel." from ".$prefix."_pages where `tables`='pages' and `active`='1'".$and.$and2.$and3.$and4." order by ".$sort."".$limit."";
	$result = $db->sql_query($sql);

	if ($shablon == "")
		if ($openshow==0) $textX .= "<ul class=\"block_li_title\">"; 
	
	$numlock = 0; // Счетчик кол-ва выведенных строк у блоков
	$number = "1";
	while ($row = $db->sql_fetchrow($result)) {
		$numlock++;
		$p_id = $row['pid'];
		$module = $row['module'];
		$p_cid = $row['cid'];
		$title = filter($row['title']);
		$titl = str_replace("\"","",$title);
		$page_on_mainpage = "";
    	if ($row['mainpage'] == "1") $page_on_mainpage = " page_favourite";

		if ($openshow > 0 or $shablon != "") {
			$open_text = filter(strip_tags(str_replace("<img ", "<img title='".$titl."' ", $row['open_text']), '<b><br><i><ul><li><ol><dl><dt><img><table><tr><td><a><strong><em><embed><param><object><p><iframe><div>'), "", 0);
			$main_text = filter(str_replace("<img ","<img title='".$titl."' ",$row['main_text']), "", 0);
			// Вырезание авто-ссылок
		  $open_text = preg_replace('/'.ss("ссылка").'-.*-'.ss("ссылка").'/Uis', '', $open_text);
		  $open_text = str_replace('-'.ss("ссылка").'-', '', $open_text);
		  $open_text = str_replace('<hr class="editor_cut">', '', $open_text);
		  $main_text = str_replace('<!--more-->', '<hr>', $main_text);
	  	}
		if ($shablon != "") {
			$active = $row['active'];
			switch ($active) {
				case "1": $active = ss("Открытая информация");	break;
				case "2": $active = ss("Информация ожидает проверки");	break;
				case "0": $active = ss("Доступ к странице ограничен");	break;
			}
			$counterX = $row['counter'];
			$golos = $row['golos'];
			$comm = $row['comm'];
			$no_foto_open_text = preg_replace('/<img .*?>/is', '', $open_text);
			if (preg_match_all('/<img(?:\\s[^<>]*?)?\\bsrc\\s*=\\s*(?|"([^"]*)"|\'([^\']*)\'|([^<>\'"\\s]*))[^<>]*>/i', $open_text, $m)) $foto_adres = $m[1][0];
			else $foto_adres = "";
			$no_html_open_text = strip_tags($open_text,"<ul><li><ol>");
			$foto = "<img src='".$foto_adres."'>";
			$search = $row['search'];
			$price = ""; //$row['price']." ".ss("руб."); // или другая валюта! Добавить смену валюты
			$rss = ""; //$row['rss'];
			/* switch ($rss) {
				case "1": $rss = "<a name=rss title='Информация доступна через RSS-подписку' class=green_link>RSS</a>"; break;
				case "0": $rss = "<a name=rss title='Информация не доступна через RSS-подписку' class=red_link>RSS</a>"; break;
			} */
		}
						//////////
		if (trim($class) != "") {
			$class = trim($class);
			// Смотрим класс стиля, выбранный из списка для блока показываемой страницы.
			$sql7 = "SELECT name from ".$prefix."_spiski where type='".$class."' and pages like '% ".$p_id." %'";
			$result7 = $db->sql_query($sql7);
			$row7 = $db->sql_fetchrow($result7); 
			$class = " ".$row7['name'];
		} else $class = " "; //no_class

		if ($datashow == 1 or $shablon != "") { // Если показывать дату // доработать - функция из mainfile
			$data = date2normal_view($row['date'])." ".$strelka." ";
		} else $data = "";

		$page_comments_word = ss("Пока без комментариев");

		if (isset($comm))
			if ($comm > 0)
				$page_comments_word = $comm." ".num_ending($comm, Array(ss("комментариев"),ss("комментарий"),ss("комментария")));

		if ($tagdelete == "1") {
			$title = strip_tags($title);
			$open_text = strip_tags($open_text);
		}
		// Начало замены
		if ($shablon != "") {
			if (!isset($titles_papka[$p_cid])) $titles_papka[$p_cid] = "";
		  $tr = array(
		  	"[number]"=>$number, // порядковый номер
		  	"[page_id]"=>$p_id,
		  	"[page_razdel]"=>$module,
			"[cat_id]"=>$p_cid,
			"[cat_name]"=>$titles_papka[$p_cid],
			"[page_title]"=>$title,
			"[page_link]"=>"-".$module."_page_".$p_id,
			"[page_link_title]"=>"<a href=-".$module."_page_".$p_id.$blank." class='block_title ".$class."'>".$title."</a>",
			"[page_opentext]"=>"<span id=block_open_text class='block_open_text ".$class."'>".$open_text."</span>",
			"[page_text]"=>$main_text,
			"[page_data]"=>$data,
			"[page_counter]"=>$counterX,
			"[page_active]"=>$active,
			"[page_reiting]"=>$golos,
			"[page_comments]"=>$comm,
			"[page_comments_word]"=>$page_comments_word,
			"[photo_address]"=>$foto_adres,
			"[photo]"=>$foto,
			"[opentext_no_photo]"=>$no_foto_open_text,
			"[opentext_no_html]"=>$no_html_open_text,
			"[page_tags]"=>$search,
			"[page_rss]"=>$rss,
			"[page_on_mainpage]"=>$page_on_mainpage,
			// старый вариант с русскими названиями (впоследствии будет удален)
			"[№]"=>$p_id,
			"[модуль]"=>$module,
			"[№ папки]"=>$p_cid,
			"[название папки]"=>$titles_papka[$p_cid],
			"[название]"=>$title,
			"[ссылка]"=>"<a href=-".$module."_page_".$p_id.$blank." class='block_title ".$class."'>".$title."</a>",
			"[предисловие]"=>"<span id=block_open_text class='block_open_text ".$class."'>".$open_text."</span>",
			"[содержание]"=>$main_text,
			"[дата]"=>$data,
			"[число посещения]"=>$counterX,
			"[открытость]"=>$active,
			"[число голосование]"=>$golos,
			"[число комментарии]"=>$comm,
			"[адрес фото]"=>$foto_adres,
			"[фото]"=>$foto,
			"[предисловие_без_фото]"=>$no_foto_open_text,
			"[предисловие_без_html]"=>$no_html_open_text,
			"[теги]"=>$search,
			"[цена]"=>$price,
			"[rss доступность]"=>$rss
		  );
			$shablonX = $shablon;
			foreach ($s_names as $id2 => $nam2) {
			// Найдем значение каждого поля для данной страницы
				if (!isset($s_opts[$nam2][$p_id])) $s_opts[$nam2][$p_id] = "";
				$nam3 = $s_opts[$nam2][$p_id];
				$shablonX = str_replace("[".$nam2."]", $nam3, $shablonX);
			}
			$textX .= strtr($shablonX,$tr);
			$number++;
			
						////////////////////////////
		} else { // если без шаблона
			 // Если показывать название папки
			if (($catshow == 1 or $shablon != "") and $p_cid != 0) $cat = "<span class=\"block_li_cat ".$class."\">".$titles_papka[$p_cid]."</span> ".$strelka." "; else $cat = "";
				if ($openshow > 0) { // Если показывать предописание
					if (trim($main_text)!="" and $daleeshow == 1) {
						if ($openshow == 1) {
							$dalee = " <div id=dalee class=\"dalee ".$class."\"><a href=-".$module."_page_".$p_id.$blank.">".$dal."</a></div>";
						} else {
							$dalee = " <div id=dalee class=\"dalee ".$class."\">".$dal."</div>";
						}
					} else $dalee = "";
					$open_text = "<span id='block_open_text' class='block_open_text ".$class."'>".$open_text.$dalee."</span>";
					if ($zagolovokin == 0) {
						$zagolovok = "<span class='block_title ".$class."'><span class='block_li_data ".$class."'>".$data."</span>".$cat."<a class='block_title ".$class."' href='-".$module."_page_".$p_id.$blank."'>".$title."</a></span>";
						$open_text = str_replace(aa("[заголовок]"), "", $open_text); 
					} else {
						$zagolovok = "";
						if ($openshow == 1) { 
							$open_text = "<span class=a_block_title><a class=\"a_block_title ".$class."\" href=-".$module."_page_".$p_id.$blank.">".str_replace(aa("[заголовок]"),"</span><span class=\"block_title ".$class."\"><span class=\"block_li_data ".$class."\">".$data."</span>".$cat."".$title."</span>",$open_text)."</a>"; // Вставляем Заголовок в блок!
						} else {
							$open_text = "<span class=a_block_title>".str_replace(aa("[заголовок]"),"</span><span class=\"block_title ".$class."\">".$data."".$cat."".$title."</span>",$open_text).""; // Вставляем Заголовок в блок!
						}
					}
					$textX .= "<div>".$zagolovok."".$open_text."</div>\n";
				} else { // Если НЕ показывать предописание
					$textX .= "<li class=\"block_li_title ".$class."\"><span class=\"block_li_data ".$class."\">".$data."</span>".$cat."<a href=-".$module."_page_".$p_id.$blank.">".$title."</a></li>";
				}
		}			
	}

	if ($numlock == 0 && $and4 != "" && !empty($useitX)) $textX .= ss("Пока ничего нового. См.")." <a href='/-".$useitX."'>".ss("Архив")."</a>.";

	if ($shablon == "") {
		if ($openshow==0) $textX .= "</ul>";
	}	

	if ($openshow==0) $textX .= "";

	if ($add==1) $textX .= "<div id=add class=\"add".$class."\"><a href=-".$module."&add=true".$blank." id=add_link class=\"add_link".$class."\">".$addtitle."</a></div>";
	
	if ($open_all==1 and $razdel_open2_name != "" and $razdel_open2_name != "no") $textX .= "<br><div id=open_all class=\"open_all".$class."\"><a href=-".$module.$cid_open2.$blank." id='open_all_link' class=\"open_all_link".$class."\">".$razdel_open2_name."</a></div><br>";

	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "1": # Блок комментариев раздела
	// Получим список № страниц
	if ($useitX == "") $and = "";
	else $and = " where `tables`='pages' and module='".$useitX."'"; 
	// Показывать ВСЕ разделы или выбранный
	if ($only_question==0) $and2 = "";
	elseif ($only_question==1) $and2 = " and drevo='0'"; 
	elseif ($only_question==2) $and2 = " and drevo!='0'"; 
	if (isset($size)) $limit = " limit ".$number.",".$size;
	if ($shablon != "") $sel = "*";
	else $sel = "cid, num, avtor, text, data"; 
	
	$sql = "SELECT `pid`, `title`, `module` from ".$prefix."_pages".$and." order by `date` desc";

	$result = $db->sql_query($sql);
	$line_id = array();
	$titles = array();
	$modules = array();
		while ($row = $db->sql_fetchrow($result)) {
			$p_id = $row['pid'];
			$line_id[] = $p_id;
			$modules[$p_id] = $row['module'];
			$titles[$p_id] = $row['title'];
		}
	$line_id = " and (num='".implode("' or num='", $line_id)."')";
	$sql = "SELECT ".$sel." from ".$prefix."_pages_comments where `tables`='pages'".$line_id." and active='1'".$and2." order by ".$sort.$limit."";

	$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$cid = $row['cid'];
			$num = $row['num'];
			$avtor = $row['avtor'];
			$text = strip_tags(mb_substr($row['text'], 0, $col_bukv), '<b><i><img><a><strong><em>');
			if (mb_strlen($row['text']) > $col_bukv) $text .= "...";
			$data = $row['data'];
			$data = date2normal_view(str_replace(".","-",$data), 2, 1);
			// Если показывать название страницы
			if ($pageshow == 1) $cat = "".$titles[$num]." $strelka "; else $cat = "";
			$textX .= "<li class=\"block_li_title ".$class."\"><span class=\"block_li_data ".$class."\">".$data."</span> ".$strelka." <span class=\"block_li_cat ".$class."\">".$cat."</span> <a class=\"block_comment_text ".$class."\" href=-".$modules[$num]."_page_".$num."#comm_".$cid.">".$text."</a></li>";
		}
		$textX = "<ul class=\"block_li_title\">".$textX."</ul>";
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "2": # Блок текста
	// Добавление табов
	global $include_tabs;
	if (strpos(" ".$textX, "{{")) {
	  if ($include_tabs == false) { include ('page/tabs.php'); $include_tabs = true; }
	  if (strpos(" ".$textX, "{{")) $textX = show_tabs($textX);
	}
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	if ($titleshow != 0) $block = str_replace(aa("[заголовок]"), $titleX, $block);
	break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "3": # Блок ротатор рекламы
	$reload_link = "<a style='cursor:pointer;' onclick='$(showrotator".$idX."(0))'>".$reload_link_text."</a>";
	$textX = "<script>
	function showrotator".$idX."(timer){
		$.get('rotator.php',{num:'".$idX."', timer: timer},
			function(data){ $('#show_rotator".$idX."').html(data);});}
	  $(showrotator".$idX."(".$reload_one_by_one."));";
	  if ($reload_link_on_start != 0) $textX .= "setTimeout(function() { $(showrotator".$idX."(".$reload_one_by_one.")); }, 1000);";
	  if ($reload_link_time != 0) {
	  	$row = $db->sql_fetchrow($db->sql_query("select `text` from ".$prefix."_mainpage where `type`='3' and `name`='3' and `id`='".$idX."'"));
		$showrotator_max = count(explode("|", $row['text']));
	  	$reload_link_time = $reload_link_time * 1000;
	  	// включение последовательного показа блоков
		if ($reload_one_by_one == 1) $and = "\nshowrotator_timer=showrotator_timer+1; 
		if (showrotator_timer > showrotator_max) showrotator_timer = 1;"; 
		else $and = "";
	  	$textX .= "\nvar showrotator_timer=".$reload_one_by_one.";\nvar showrotator_max=".$showrotator_max.";
	  	setInterval( function(){ $( showrotator".$idX."(showrotator_timer) ); ".$and." }, ".$reload_link_time.");";
	  }
	  $textX .= "</script>";
	  if ($reload_link_show == "1") $textX .= $reload_link;
	  $textX .= "<div id='show_rotator".$idX."'>".ss("Загружается...")."</div>";
	  if ($reload_link_show == "2") $textX .= $reload_link;
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////	
case "4": # Блок папок раздела 
	//if ($useitX == 'index') $block = str_replace("[".$titleX."]", "", $block); // если главная - ничего не выводим
	//else {
		if ($noli == 0) $textX = "<ul id='block_ul_title_".$useitX."' class='block_ul_title'>"; 
		// В эту переменную входит содержание блока

		global $txt_razdels; // ЗАМЕНА mainpage2 №3
		$textXX = explode("|", $txt_razdels[$useitX] );
		// Определяем отношение страниц к папкам
		if ($papki_numbers==1) {
			$num = array();
			$sql = "SELECT `pid`, `cid` from ".$prefix."_pages where `tables`='pages' and `module`='".$useitX."' and `active`='1'";
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result)) {
				$cid_id = $row['cid'];		//
				if (!isset($num[$cid_id])) $num[$cid_id] = 1; else $num[$cid_id]++;
			}
		}
		////// Определяем отношение подпапок к папкам
		if ($papki_numbers==0) $and_par = " and parent_id='0'"; else $and_par = "";
			$sql="SELECT `cid`, `title`, `parent_id` from ".$prefix."_pages_categories where `module`='".$useitX."' and `tables`='pages'".$and_par." order by ".$papka_sort;
			$result = $db->sql_query($sql);
			$title = array();
			while ($row = $db->sql_fetchrow($result)) {
				$id = $row['cid'];
				$title[$id] = $row['title'];
				$par[$id] = $row['parent_id'];
			}
		// Выводим результаты
		foreach ($title as $id => $nam) {
		if ($papki_numbers==1) {
			$and2=""; 
			if (vhodyagie($id,$par,$num)>0) $and2 = "<div class='add'>+".vhodyagie($id,$par,$num)."</div>";
			$and="";
			if ( $num[$id]>0 ) $and = " (".$num[$id].$and2.")";
		} else $and="";
			if ($par[$id]==0) {
				if ($noli == 0) {
					$textX .= "<li id='block_li_title_".$useitX."' class='block_li_title'><a class='papki_".$useitX."' href=-".$useitX."_cat_".$id.">".$nam."</a>".$and."";
				} else {
					$textX .= "<a class='papki_".$useitX."' href=-".$useitX."_cat_".$id.">".$nam."</a>".$and." | ";
				}
			}
		}
		if ($noli == 0) $textX .= "</ul>";
		// Вставим шаблон из блока!!!
		$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	//}
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "5": # Блок голосования
	$textX = "<script>$(showopros(".$idX.", 1, 0));</script>
	<div id='show_opros".$idX."'><span class='ico_loading i16'></span>";
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break; 
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "6": # Фотогалерея
	global $siteurl;
	$links = explode("\n", str_replace("\n\n", "\n", $textX) );
	$textX = $textX0 = "";
	if ($foto_gallery_type == 8) $textX0 = array();
	if ($foto_gallery_type >= 2 && $foto_gallery_type <= 7) {
		if ($img_width == "0") $img_width = $img_height;
		if ($img_height == "0") $img_height = $img_width;
	}
	for ( $i=0; $i < count($links); $i++ ) { 
		# Обработка ссылок на фото
		$link = $title = $alt = "";
		$link = explode("|",$links[$i]);
		if (isset($link[1])) $title = $link[1]; else $title = "";
		if (isset($link[2])) $alt = $link[2]; else $alt = "";
		if (isset($link[3])) $alt2 = $link[3]; else $alt2 = "";
		if (isset($link[0])) $link = $link[0]; else $link = "";
		if ($watermark != "") $water = "includes/php_thumb/php_thumb.php?src=".$link."&fltr[]=wmi|".$watermark."|BR|100";	else $water = $link;
		// foto_gallery_type: 1 - миниатюры, 0 - «карусель», 2 - описание в 3 строки, 3 - аля макос горизонтальная полоса прокрутки
		if ($foto_gallery_type == 1) $textX .= "<span class='div_a_img_gallery'><a title='".$title."' href='".$water."' class='lightbox' rel='group'><img alt='".$title."' src='includes/php_thumb/php_thumb.php?src=".$link."&amp;w=".$img_width."&amp;h=".$img_height."&amp;q=0' class='img_gallery'></a></span> ";
		if ($foto_gallery_type == 0) $textX0 .= "<li><a href='".$water."'><img src='includes/php_thumb/php_thumb.php?src=".$link."&amp;w=".$img_width."&amp;h=".$img_height."&amp;q=0' title='".$title."' alt='".$alt."' class='image".$i."'></a></li>";
		if ($foto_gallery_type == 2) $textX0 .= "<li><a href='#image-".$i."'><img src='includes/php_thumb/php_thumb.php?src=".$link."&amp;fltr[]=crop|0|0|0|0.05&amp;w=".$img_width."&amp;h=".$img_height."&amp;q=0' alt='".$title."'><span>".$title."</span></a><div class='lb-overlay' id='image-".$i."'><img src='".$water."' alt='".$title." / ".$alt." / ".$alt2."' /><div><h3>".$title."<span>".$alt."</h3><p>".$alt2."</p></div><a href='#page' class='lb-close'>x Закрыть</a></div></li>";
		if ($foto_gallery_type >= 3 && $foto_gallery_type <= 6) $textX0 .= "<li><a title='".$title."' href='".$water."' class='lightbox' rel='group'><img src='includes/php_thumb/php_thumb.php?src=".$link."&amp;w=".$img_width."&amp;h=".$img_height."&amp;q=0' alt='".$alt."' class='img_gallery'></a></li>";
		if ($foto_gallery_type == 7) $textX0 .= "<a title='".$title."' href='".$water."' class='lightbox' rel='group'><figure style=\"background-image: url('includes/php_thumb/php_thumb.php?src=".$link."&amp;w=".$img_width."&amp;h=".$img_height."&amp;q=0')\"><figcaption><h4>".$title."</h4><p>".$alt."</p></figcaption></figure></a>";
		if ($foto_gallery_type == 8) $textX0[] = "<a title='".$title."' href='".$water."' class='lightbox' rel='group'><img src='includes/php_thumb/php_thumb.php?src=".$link."&amp;w=".$img_width."&amp;h=".$img_height."&amp;q=0' alt='".$alt."' class='img_gallery'></a>";
	}
	if ($foto_gallery_type == 8) {
		$rand = array_rand($textX0, $foto_num);
		foreach ($rand as $key) {
			$textX .= $textX0[$key];
		}
	}
	if ($foto_gallery_type >= 3 && $foto_gallery_type <= 7) {
		// effects 3, basic 4, cycleitems 5, oneperframe 6, fullscreen 7
		$type_sly = array('','','','effects', 'basic', 'cycleitems', 'oneperframe', 'fullscreen');
		if ($foto_gallery_type == 7) {
			$gallery_sly_full = true;
			$image_block = "<div id='images' class='images clearfix'>".$textX0."</div>";
		} else {
			$gallery_sly = true;
			$image_block = "<ul class='slidee'>".$textX0."</ul>";
		}
		$type_sly = $type_sly[$foto_gallery_type];
		$gallery_lightbox = true;
		if ($foto_gallery_type != 7) $textX .= "<div class='scrollbar'><div class='handle'><div class='mousearea'></div></div></div>";
		$textX .= "<div class='frame ".$type_sly."' id='".$type_sly."'>".$image_block."</div>";
		if ($foto_gallery_type != 7) $textX .= "<style>.frame ul li {width: ".$img_width."px;}</style><div class='controls center'><button class='prev'> ← ".ss("предыдущая")." </button> <button class='next'> ".ss("следующая")." → </button></div>";
		else $textX .= "<style>.images figure { padding-top: ".$img_height."px; width: ".$img_width."px; }</style>";
	}
	if ($foto_gallery_type == 2) { $gallery_css3 = true; $textX .= "<style>.lb-album li > a{width: ".$img_width."px;height: ".$img_height."px;line-height: ".$img_height."px;}.lb-album li > a span{width: ".$img_width."px;height: ".$img_height."px;line-height: ".$img_height."px;}</style><ul class='lb-album'>".$textX0."</ul>"; }	
	if ($foto_gallery_type == 1 || $foto_gallery_type == 8) { $gallery_lightbox = true; }
	if ($foto_gallery_type == 0) { $gallery_carusel = true; $textX .= "<div id='carusel-gallery' class='ad-gallery'><div class='ad-image-wrapper'></div><div class='ad-controls'></div><div class='ad-nav'><div class='ad-thumbs'><ul class='ad-thumb-list'>".$textX0."</ul></div></div></div>"; }
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "7": # Блок PHP // проверить eval
	$textX = str_replace("<? ", "", str_replace(" ?>", "", $textX));
	global $txt;
	eval($textX); // Все содержится в переменной $txt, а eval() - для выполнения кода
	if (!isset($txt)) $txt = "";
	$block = str_replace("[".$titleX."]", $design_open.$txt.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "8": # Блок папок ОТКРЫТОГО раздела
	global $cid, $pid;
	if ($shablon != "") {
		$shablon = explode("#!#", $shablon);
		$shablon_category = $shablon[0];
		$shablon_razdelitel = $shablon[1];
		$shablon_main = $shablon[2];
		$shablon_active = $shablon[3];
	} else { // если без шаблона
		$shablon_category = "<li class=\"[css] block_li_title\">".aa("[активность]")."<a href=".aa("[ссылка]").">".aa("[название]")."</a>".aa("[число страниц]")."</li>";
		$shablon_razdelitel = "";
		$shablon_main = "<ul class='block_li_title'>".aa("[содержание]")."</ul>";
		$shablon_active = $strelka." ";
		$shablon_active2 = "<div style='float:left; width:10px; height:10px;'></div>".$strelka." ";
	}
	$block_title = "";
	if ($titleshow != 2 and $titleshow != 3) $block_title .= "<h3 class='h3_block_title'>".$ModuleName."</h3><div class='polosa'></div>";
	$textX = "";
		global $txt_razdels; // ЗАМЕНА mainpage2 №4
		$textXX = explode("|", $txt_razdels[$DBName] );
	//$pages = $textXX[0]; // получили название файла модуля, например, pages
	$num = array();
	// Определяем отношение страниц к папкам
	$sql = "SELECT cid from ".$prefix."_pages where `tables`='pages' and module='".$DBName."' and active='1'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		$cid_id = $row['cid'];
		if (!isset($num[$cid_id])) $num[$cid_id] = 0;
		if ($cid_id>0) $num[$cid_id]++;
	}
	// Определяем cid от pid, т.е. если открыта не папка, а страница
	if ($pid>0) {
		$sql = "SELECT cid from ".$prefix."_pages where `tables`='pages' and pid='".$pid."'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$cid = $row['cid'];
	}
	// Определяем отношение подпапок к папкам
	$sql = "SELECT cid, title, parent_id from ".$prefix."_pages_categories where module='".$DBName."' and `tables`='pages' order by ".$papka_sort;
	$result = $db->sql_query($sql);
	$title = array();
	while ($row = $db->sql_fetchrow($result)) {
		$id = $row['cid'];
		$title[$id] = $row['title'];
		$par[$id] = $row['parent_id'];
	}
	// Выводим результаты
	$papki = array();
	foreach ($title as $id => $nam) {
		if ($papki_numbers==1 or $shablon != "") {
			$and2=""; 
			if (vhodyagie($id,$par,$num)>0) $and2 = "<div class='add'>+".vhodyagie($id,$par,$num)."</div>";
			$and="";
			if ($num[$id]>0) $and = " (".$num[$id].$and2.")";
		} else $and="";
		// Определение и выделение выбранной (текущей) папки
		if (!isset($par[$cid])) $par[$cid] = 0;
		if ($cid == $id or $id == $par[$cid]) {
			$thisis = $shablon_active;
			$podpapki = "";
			// Вывод подпапок выбранной категории
			$sql="SELECT cid, title from ".$prefix."_pages_categories where module='".$DBName."' and `tables`='pages' and parent_id='".$id."' order by ".$papka_sort;
			$result = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($result)) {
					$p_id = $row['cid'];
					$p_title = $row['title'];
					if ($cid==$p_id) $podthisis = "".$shablon_active2.""; 
					else $podthisis = "<div style='float:left; width:30px; height:10px;'></div>";
					//$podpapki .= "<li class=\"podpapki block_li_title\">[стрелка]<a href=[ссылка]>[название]</a></li>";
					$tr = array(
					"[cat_id]"=>$p_id,
					"[cat_name]"=>$p_title,
					"[cat_link]"=>"/-".$DBName."_cat_".$p_id."",
					"[cat_link_title]"=>"<a href=-".$DBName."_cat_".$p_id.">".$p_title."</a>",
					"[cat_page_num]"=>"",
					"[css]"=>"podpapki",
					"[cat_parent]"=>$podthisis,
					// старый вариант с русскими названиями (впоследствии будет удален)
					"[№]"=>$p_id,
					"[название]"=>$p_title,
					"[ссылка]"=>"/-".$DBName."_cat_".$p_id."",
					"[полная ссылка]"=>"<a href=-".$DBName."_cat_".$p_id.">".$p_title."</a>",
					"[число страниц]"=>"",
					"[активность]"=>$podthisis
					);
					$podpapki .= strtr($shablon_category,$tr);
				}
		} else { 
			$thisis=""; 
			$podpapki=""; 
		}
		if ($par[$id]==0) {
			$tr = array(
			"[cat_id]"=>$id,
			"[cat_name]"=>$nam,
			"[cat_link]"=>"/-".$DBName."_cat_".$id."",
			"[cat_link_title]"=>"<a href=-".$DBName."_cat_".$id.">".$nam."</a>",
			"[cat_page_num]"=>$and,
			"[css]"=>"papki",
			"[cat_parent]"=>$thisis,
			// старый вариант с русскими названиями (впоследствии будет удален)
			"[№]"=>$id,
			"[название]"=>$nam,
			"[ссылка]"=>"/-".$DBName."_cat_".$id."",
			"[полная ссылка]"=>"<a href=-".$DBName."_cat_".$id.">".$nam."</a>",
			"[число страниц]"=>$and,
			"[активность]"=>$thisis
			);
			$papki[] = strtr($shablon_category,$tr).$podpapki;
		}
	}
	$textX .= implode($shablon_razdelitel,$papki);
	$shablon_main = str_replace(aa("[содержание]"),$textX,$shablon_main);
	if ($textX!="") $block = str_replace("[".$titleX."]", $design_open.$block_title.$shablon_main.$design_close, $block);
	else $block = str_replace("[".$titleX."]", "", $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "9": # Блок мини-фото - экстрактор предописания страниц
	$textX = ""; // В эту переменную входит содержание блока
	$and = "";
	$and2 = "";
	if ($cid_open=="все" or $cid_open=="") { // Показывать ВСЕ папки или выбранную
	} else $and2 = " and cid='".$cid_open."'";
	$lim2 = $size * $limkol;
	if ($showlinks > 0) $lim2 = 100000;
	$proc = intval(100 / $limkol);
	if ($main==1) $and = " and `mainpage` = '1'";
	if ($main==2) $and = " and `mainpage` != '1'";
	if ($useitX == "") $andmodule = "";
	else $andmodule = " and `module`='".$useitX."'";
	$sql = "SELECT `pid`, `module`, `title`, `open_text`, `main_text` from ".$prefix."_pages where `tables`='pages'".$andmodule.$and.$and2." and `active`='1' and ( (`open_text` like '%".$first."%' or `main_text` like '%".$first."%') and (`open_text` like '%.jpg%' or `open_text` like '%.gif%' or `main_text` like '%.jpg%' or `main_text` like '%.gif%') and `open_text` not like '%smilies%' and `open_text` not like '%addVariable%' and `open_text` not like '%embed%' and `main_text` not like '%smilies%' and `main_text` not like '%addVariable%' and `main_text` not like '%embed%') order by ".$sort." limit ".$number.",".$lim2;
	$result = $db->sql_query($sql) or die(ss("Ошибка"));
	$numrows = $db->sql_numrows($result);
	$opentable=0;
	$limkol_num = 0;
	$kol_num = 0;
	while ($row = $db->sql_fetchrow($result)) {
		$id = $row['pid'];
		$title = $row['title'];
		$modul = $row['module'];
		$open_text = $row['open_text'];
		$main_text = $row['main_text'];

		// Если картинки нет в main - смотрим в open
		if ( !strpos($open_text, $first)) $open_text = $main_text;
		$src = explode($first,$open_text);
		$src = explode($second,$src[1]);
		$src = explode($third,$src[0]);
		$src = str_replace("&","",str_replace("\\","",str_replace("\"","",str_replace("\"/","",str_replace("alt=\"\" ","",$src[0])))));
		if ($img_width == 0) $width=""; else $width=" width=".$img_width."";
		if ($img_height == 0) $height=""; else $height=" height=".$img_height."";
		$open = "";

		if ($limkol > 1 and $opentable==0) { $textX .= "<table cellspacing=0 cellpadding=0 width=100%><tr valign=top><td width=".$proc."%>"; $opentable=1; }
		if ($openshow > 0) { // 2,1,0 - да и без ссылки,да и со ссылкой,нет
			$open2 = explode("<br>",$open_text);
			$open2 = str_replace($open2[0]."", "", $open_text);
			if ($openshow == 1) $open = "<a href=-".$modul."_page_".$id.">".$open2."</a>";
			if ($openshow == 2) $open = $open2;
		}
		if ($show_title == 2) $textX .= "<div class=\"".$class."\"><a href=-".$modul."_page_".$id." class='ex_pic ".$class."'>".$title."</a></div>";
		$textX .= "<a href=-".$modul."_page_".$id." title=\"".$title."\" class='ex_pic ".$class."'><img".$width.$height." src='".$src."' alt=\"".$title."\" class=\"extract ".$useitX."_pic ".$class."\"></a>\n";
		if ($show_title == 2) $textX .= $open;
		if ($show_title == 1) $textX .= "<div class=\"".$class."\"><a href=-".$modul."_page_".$id." class='ex_pic ".$class."'>".$title."</a></div>";
		if ($show_title == 1) $textX .= $open;
		$limkol_num++;
		$kol_num++;
		if ($limkol > 1) {
			if ($limkol_num == $limkol) {
				if ($kol_num == $size * $limkol) {
					if ($showlinks > 0) $textX .= "</table>|+|+|<table cellspacing=0 cellpadding=0 width=100%><tr valign=top><td width=".$proc."%>";
					else {
						$textX .= "</table>";
						$close_table = true;
					}
					$limkol_num = 0;
					$kol_num = 0;
				} else {
					$limkol_num = 0;
					if ($kol_num != $size * $limkol) $textX .= "</td></tr><tr valign=top><td width=".$proc."%>";
					else { 
						$textX .= "</td></tr></table>";
						$close_table = true;
					}
				}
			} else {
			  $textX .= "</td><td width=".$proc."%>"; $close_table = false;
			}
		}
	}
	if ($showlinks > 0) $textX .= "</table>";
	if ($showlinks > 0) {
		$sql = "SELECT `pid` from ".$prefix."_pages where `tables`='pages' and `module`='".$useitX."'".$and.$and2." and `active`='1' and (`open_text` like '%".$first."%' or `open_text` like '%".$first2."%')";
		$result = $db->sql_query($sql) or die('Ошибка');
		$numrows2 = $db->sql_numrows($result);
		$count = intval( $numrows2 / ($size * $limkol) );
		$ostatok = $numrows2 - $count * $size * $limkol;
		if ($ostatok > 0) $count++;
		if ($count > 1) {
			$matches = explode("|+|+|",$textX);
			$textX = "";
			$info_blocks = "";
			$obzor = false;
			$names_block = "";
			for ( $i=0; $i < $count; $i++ ) { 
				$info_blocks .= "<div id='fragment-".$i."'>".$matches[$i]."</div>";
				$names_block .= "<li><a href='#fragment-".$i."'>".( $i + 1 )."</a></li>";
			}
			// 3,2,1,0 - сверху и снизу,снизу,сверху,не показывать
			if ($showlinks == 1) $names_block = "<div id='rotate'><ul>".$names_block."</ul>".$info_blocks."</div>";
			if ($showlinks == 2) $names_block = $info_blocks."<div id='rotate'><ul>".$names_block."</ul></div>";
			if ($showlinks == 3) $names_block = "<div id='rotate'><ul>".$names_block."</ul>".$info_blocks."<ul>".$names_block."</ul><hr ></div>";
			$textX .= "<script>$(function() { $('#rotate > ul').tabs({ fx: { opacity: 'toggle' } }); }); </script>".$names_block."";
		}
	}
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "10": # Блок меню
	global $siteurl, $url, $re_menu, $class;

		$url1 = $url;
		if ($url1 == "") $url1 = "/";
		$url1 = str_replace("http://".$siteurl,"",$url1);
		$url1 = str_replace("http%3A%2F%2F".$siteurl."%2F","","/".$url1);
		$url1 = str_replace("//","/",$url1);
		$url3 = str_replace("/","",$url1);
		$url2 = explode("_",$url1);
		$url2 = $url2[0];
		$url4 = str_replace("/","",$url2);

		$lvl_open = "<ul>";
		$lvl_close = "</ul>";
		$el_open = "<li>";
		$el_open2 = "<li class='li_mainmenu_open'>";
		$el_close = "</li>";
		$url_open = "<a class='li1menu_link' href='";
		$url_open2 = "li1menu_link";
		$url_close1 = "'>";
		$url_close2 = "</a>";
		if ($menu == "1") {
			$el_open = "<td class='align_center'>";
			$el_open2 = "<td class='align_center td_mainmenu_open'>";
			$el_close = "</td>";
			$url_open = "<a class='table1menu_link' href='";
			$url_open2 = "table1menu_link";
			$url_close1 = "'><div class='li2menu_div'>";
			$url_close2 = "</div></a>";
		} elseif ($menu == "2") {
			$el_open2 = "<li class='li_mainmenu_open'>";
			$lvl_open = "<ul class='ul_tree'>";
			$url_open = "<a class='li2menu_link' href='";
			$url_open2 = "li2menu_link";
		}

	if ($re_menu != "1" && $re_menu != "0") {
		// Создаем меню из папок выбранного в настройках меню раздела
		// Определяем отношение подпапок к папкам
		$sql = "SELECT cid, title, parent_id from ".$prefix."_pages_categories where module='".$re_menu."' and `tables`='pages' order by cid";
		$result = $db->sql_query($sql);
		$papki = $title = $par = array();
		while ($row = $db->sql_fetchrow($result)) {
			$id = $row['cid'];
			$title[$id] = $row['title'];
			$par[$id] = $row['parent_id'];
		}
		$textXX = "";
		foreach ($title as $id => $nam) {
			if ($par[$id]==0) { // папка, содержащая подпапки
				$textXX .= $el_open.$url_open.'-'.$re_menu.'_cat_'.$id.$url_close1.$nam.$url_close2;
				if (in_array($id, $par) && $menu!="1" && $menu!="3" && $menu!="5") {
					$textXX .= $lvl_open;
					foreach ($title as $id2 => $nam2) {
						if ($par[$id2]==$id) { // папка, содержащая подпапки
							$textXX .= $el_open.$url_open.'-'.$re_menu.'_cat_'.$id2.$url_close1.$nam2.$url_close2;
							if (in_array($id2, $par) && $menu!="2") {
								$textXX .= $lvl_open;
								foreach ($title as $id3 => $nam3) {
									if ($par[$id3]==$id2) { // подпапка
										$textXX .= $el_open.$url_open.'-'.$re_menu.'_cat_'.$id3.$url_close1.$nam3.$url_close2.$el_close;
									}
								}
								$textXX .= $lvl_close;
							}
							$textXX .= $el_close;
						}
					}
					$textXX .= $lvl_close;
				}
				$textXX .= $el_close;
			}
		}
	} else {
		// поиск и замену блоков в меню
		
		
		$tr = array("http://".$siteurl=>"");
		$textX = strtr($textX,$tr);
		$tr = array(aa("[уровень открыть]")=>$lvl_open,aa("[уровень закрыть]")=>$lvl_close,aa("[элемент открыть]")=>$el_open,aa("[элемент закрыть]")=>$el_close,"[url="=>$url_open,"[/url]"=>$url_close2);
		$textXX = strtr($textX,$tr);

		$textXX = str_replace("]".$el_close,"$$$".$el_close,$textXX);
		$textXX = str_replace("]",$url_close1,$textXX);
		//if ($url != "/") {
			$textXX = str_replace("' href='".$url1."'>", " mainmenu_open' href='".$url1."'>", $textXX);
			$textXX = str_replace($el_open."<a class='".$url_open2." mainmenu_open' href='".$url1."'>", $el_open2."<a class='".$url_open2." mainmenu_open' href='".$url1."'>", $textXX);
			if ($url1 != $url2) {
				$textXX = str_replace("' href='".$url2."'>", " mainmenu_open' href='".$url2."'>", $textXX);
				$textXX = str_replace($el_open."<a class='".$url_open2." mainmenu_open' href='".$url2."'>", $el_open2."<a class='".$url_open2." mainmenu_open' href='".$url2."'>", $textXX);
			}
			$textXX = str_replace("' href='".$url4."'>", " mainmenu_open' href='".$url4."'>", $textXX);
			$textXX = str_replace($el_open."<a class='".$url_open2." mainmenu_open' href='".$url4."'>", $el_open2."<a class='".$url_open2." mainmenu_open' href='".$url4."'>", $textXX);
			if ($url3 != $url4) {
				// основной вариант -имя_cat_№
				$textXX = str_replace("' href='".$url3."'>", " mainmenu_open' href='".$url3."'>", $textXX);
				$textXX = str_replace($el_open."<a class='".$url_open2." mainmenu_open' href='".$url3."'>", $el_open2."<a class='".$url_open2." mainmenu_open' href='".$url3."'>", $textXX);
			}

			if (strpos($url3, "_page_")) // если открыта страница
				if (strpos($textXX, "' href='".$url3."'>")) { // если в меню есть на нее ссылка
					// узнаем номер страницы
					$url3_pid = explode("_page_", $url3);
					$url3_pid = $url3_pid[1];
					// узнаем номер папки этой страницы
					$sql = "SELECT `cid` from ".$prefix."_pages where `pid`='".$url3_pid."' limit 1";
					$result = $db->sql_query($sql);
					$papki = $title = $par = array();
					$row = $db->sql_fetchrow($result);
					$num_cat = $row['cid'];
					// выделим пункт меню папки этой страницы
					$textXX = str_replace("' href='".$url2."_cat_".$num_cat."'>", " mainmenu_open' href='".$url2."_cat_".$num_cat."'>", $textXX);
					$textXX = str_replace("<li><a class='li1menu_link mainmenu_open' href='".$url2."_cat_".$num_cat."'>", "<li class='li_mainmenu_open'><a class='li1menu_link mainmenu_open' href='".$url2."_cat_".$num_cat."'>", $textXX);
					$textXX = str_replace("' href='".$url4."_cat_".$num_cat."'>", " mainmenu_open' href='".$url4."_cat_".$num_cat."'>", $textXX);
					$textXX = str_replace("<li><a class='li1menu_link mainmenu_open' href='".$url4."_cat_".$num_cat."'>", "<li class='li_mainmenu_open'><a class='li1menu_link mainmenu_open' href='".$url4."_cat_".$num_cat."'>", $textXX);
				}

			$textXX = str_replace("mainmenu_open mainmenu_open", "mainmenu_open", $textXX);
	}
			if ($menu == 7 or $menu == 8 or $menu == 9) $textXX = str_replace("<li class='li_mainmenu_open'><a class='li1menu_link mainmenu_open' href='/'>", "<li class='current'><a href='/'>", $textXX);
			elseif ($url == "-index") $textXX = str_replace("<li><a class='li1menu_link' href='/'>", "<li class='li_mainmenu_open'><a class='li1menu_link mainmenu_open' href='/'>", $textXX);
	switch ($menu) {
		case "0": // гор влево 3 уровня
			$class_menu = "menu-h-d"; break;
		case "6": // верт 3 уровня
			$class_menu = "menu-v-d"; break;
		case "5": // верт 1 уровня
			$class_menu = "menu-v"; break;
		case "4": // гор влево 3 уровня вверх
			$class_menu = "menu-h-d.d-up"; break;
		case "3": // гор влево 1 уровень
			$class_menu = "menu-h"; break;
		case "1": // Таблица гор выравнивание по всей таблице 1 уровень
			if ($re_menu == "1" || $re_menu == "0") {
				$tr = array(aa("[элемент открыть]")=>$el_open,aa("[элемент закрыть]")=>$el_close,"[/url]"=>$url_close2,"[url="=>$url_open,"]"=>$url_close1); // без 3 уровней!
				$textXX = strtr($textX,$tr);
			}
			//$textXX = str_replace("' href='".$url1."'>", " mainmenu_open' href='".$url1."'>", $textXX);
			//$textXX = str_replace("' href='".$url2."'>", " mainmenu_open' href='".$url2."'>", $textXX);
			//$textXX = str_replace("' href='".$url3."'>", " mainmenu_open' href='".$url3."'>", $textXX);
			$textXX = str_replace($el_open."<a class='".$url_open2."' href='".$url1."'>", $el_open2."<a class='".$url_open2." mainmenu_open' href='".$url1."'>", $textXX);
			$textXX = str_replace($el_open."<a class='".$url_open2."' href='".$url2."'>", $el_open2."<a class='".$url_open2." mainmenu_open' href='".$url2."'>", $textXX);
			$textXX = str_replace($el_open."<a class='".$url_open2."' href='".$url3."'>", $el_open2."<a class='".$url_open2." mainmenu_open' href='".$url3."'>", $textXX);

			if ($class != "") $class_menu = $class; else $class_menu = "table1menu";
			$textXX = "<table class='".$class_menu."' width=100% cellspacing=0 cellpadding=0><tr valign=bottom>".$textXX."</tr></table>";
		break;
		case "2": // вертикальное 2 уровня
			if ($re_menu == "1" || $re_menu == "0") {
				$tr = array(aa("[уровень открыть]")=>$lvl_open,aa("[уровень закрыть]")=>$lvl_close,aa("[элемент открыть]")=>$el_open,aa("[элемент закрыть]")=>$el_close, "[/url]"=>$url_close2,"[url="=>$url_open,"]"=>$url_close1); // без 3 уровней!
				$textXX = strtr($textX,$tr);
			}
			$textXX = str_replace("<li><a class='li2menu_link' href='".$url1."'>", "<li class='li_openlink'><a class='li2menu_openlink' href='".$url1."'>", $textXX);
			$textXX = str_replace("<li><a class='li2menu_link' href='".$url2."'>", "<li class='li_openlink'><a class='li2menu_openlink' href='".$url2."'>", $textXX);
			$textXX = str_replace("<li><a class='li2menu_link' href='".$url3."'>", "<li class='li_openlink'><a class='li2menu_openlink' href='".$url3."'>", $textXX);
			if ($class != "") $class_menu = $class; else $class_menu = "suckerdiv";
			$textXX = "<div class='".$class_menu."'><ul id='suckertree1'>".$textXX."</ul></div>";
		break;
	 	case "7": // KickStart вертикальное 3 уровня (слева)
			$class_menu = "menu vertical"; break;
		case "8": // KickStart вертикальное 3 уровня (справа)
			$class_menu = "menu vertical right"; break;
		case "9": // KickStart горизонтальное 3 уровня (слева)
			$class_menu = "menu"; break;
	}
	if ($class != "") $class_menu = $class; 
	if ($menu != "1" and $menu != "2") $textXX = "<ul id='menu' class='".$class_menu."'>".$textXX."</ul>";
	$textXX = str_replace("$$$","]",$textXX);
	$textXX = str_replace("<a class='li2menu_link' href=''>","",$textXX);
	$textXX = str_replace("<a class='li1menu_link' href=''>","",$textXX);
	$textXX = str_replace("<a class='li2menu_openlink' href=''>","",$textXX);
	$textXX = str_replace("</div></a>","",$textXX);
	$textXX = str_replace("</ul></a>","",$textXX);
	$block = str_replace("[".$titleX."]", $design_open.$textXX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "11": # КАЛЕНДАРЬ
	global $showdate; // проверить
	if (trim($showdate) != "0-00-00" and trim($showdate) != "") {
		$showdate = explode("-",$showdate);
		$showdate = intval($showdate[0])."-".(intval($showdate[1]) < 10 ? '0'.intval($showdate[1]) : $showdate[1])."-".(intval($showdate[2]) < 10 ? '0'.intval($showdate[2]) : $showdate[2]);
	}
	$calendar_dates = array();
	if ($calendar == "") {
		$sql = "select date from ".$prefix."_pages where `tables`='pages' and module='".$useitX."' and active!='0' order by date";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$dates = explode(" ",$row['date']);
			$calendar_dates[] = $dates[0];
		}
	} else {
		$sql = "select name from ".$prefix."_spiski where type='".$calendar."' and pages!='' order by name";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$calendar_dates[] = $row['name'];
		}	
	}
	$textX .= "".my_calendar($calendar_dates, $useitX, $showdate); 
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "12": # Форма (для анкет, опросов и т.д.) // Доделать!!!
	$text = explode("\n",$textX);
	$textX = '';
	$x = 0;
	foreach ($text as $value) {
		$value = explode("|",$value);
		// автофокус в первый элемент формы
		if ($x == 0) { $x=1; $autofocus = " autofocus"; } else $autofocus = "";
		if ($value[ count($value)-1 ] == 1) { $autofocus .= " required"; }

		$opis = $value[1];
		if (!empty($value[2])) $name = $value[2];
		switch ($value[0]) {
			case aa("Строка"):
				$textX .= "<p>".$opis."<input class='form_stroka' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Текст"):
				# code...
				break;
			case aa("Список"):
				# code...
				break;
			case aa("Число"):
				$textX .= "<p>".$opis."<input type='number' min='0' max='10' step='2' value='6' class='form_stroka' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Файл"):
				$textX .= "<p>".$opis."<input type='file' class='form_stroka' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Email"):
				$textX .= "<p>".$opis."<input type='email' class='form_stroka' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Телефон"):
				$textX .= "<p>".$opis."<input class='form_stroka' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Адрес"):
				$textX .= "<p>".$opis."<input class='form_stroka' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Ссылка"):
				$textX .= "<p>".$opis."<input class='form_stroka' type='url' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Дата"):
				$textX .= "<p>".$opis."<input class='form_stroka' type='date' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("ВремяДата"):
				$textX .= "<p>".$opis."<input class='form_stroka' type='datetime' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Месяц и год"):
				$textX .= "<p>".$opis."<input class='form_stroka' type='month' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Неделя"):
				$textX .= "<p>".$opis."<input class='form_stroka' type='week' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Время"):
				$textX .= "<p>".$opis."<input class='form_stroka' type='time' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Пароль"):
				$textX .= "<p>".$opis."<input type='password' class='form_stroka' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Поиск"):
				$textX .= "<p>".$opis."<input class='form_stroka' type='search' name=".$name." placeholder='".$value[3]."'".$autofocus.">";
				break;
			case aa("Отправить"):
				$textX .= "<input type='submit' class='form_submit' value='".$value[1]."'>";
				break;
			default:
				break;
		}
	}
	$textX = "<form name='' action='page/form.php' method='post' enctype='multipart/form-data'>".$textX."</form>";
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break;

/*
Строка|ФИО||Ваша фамилия|100|0
Текст|Описание|opis|напишите что-нибудь|5|1
Список|Вы русский?||конечно!/yes*неа/no*вы о чем?/what|3|1
Email|Email|email|100|1
Телефон|Телефон|tel|100|1
Адрес|Адрес|address|2|1
Отправить|Отправить анкету
*/
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "13": # ОБЛАКО ТЕГОВ
	$tags = array();
	$sql = "select `search` from ".$prefix."_pages where `tables`='pages' and `active`='1' and `copy` ='0'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		if (trim($row['search']) != "") {
			$tag = array();
			$tag = explode(",",$row['search']);
			foreach ($tag as $tag1) {
				$tag1 = trim($tag1);
				if ($tag1!="" && mb_strlen($tag1)>2) $tags[] = $tag1;
			}
		}
	}
	$tags = array_count_values($tags);
	$tags2 = array_unique($tags);
	rsort($tags2);
	$razmer = 0;
	$tags3 = array();
	$tags4 = array();
	foreach ($tags2 as $tag1) {
          if ($razmer == 0) { $tags3[$tag1] = "14"; $tags4[$tag1] = "#000000"; }
          if ($razmer == 1) { $tags3[$tag1] = "13"; $tags4[$tag1] = "#363636"; }
          if ($razmer == 2) { $tags3[$tag1] = "12"; $tags4[$tag1] = "#555555"; }
          if ($razmer == 3) { $tags3[$tag1] = "11"; $tags4[$tag1] = "#707070"; }
          if ($razmer > 3) { $tags3[$tag1] = "10"; $tags4[$tag1] = "#898989"; }
          if ($razmer > 4) { $tags3[$tag1] = "9"; $tags4[$tag1] = "#a1a1a1"; }
		$razmer++;
	}
	$tagcloud = "";
	foreach ($tags as $tag_name => $tag_col) {
		if ($tags3[$tag_col] != "9") {
			$tagcloud .= "<noindex><a class='slovo' href='slovo_".$tag_name."' style='color:".$tags4[$tag_col]."; font-size: ".$tags3[$tag_col]."pt;' rel='nofollow'>".$tag_name."</a></noindex> ";
		}
	}
	$textX .= $tagcloud;
	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "14": # Расписание
		
	$tomorrow_record = "<h3 class='tomorrow_record'>".$tomorrow_record."</h3>"; 
	if (date("G",time()) > $end_hour && $show_end_hour == "1") {
		$next_day = 1;
	} else $tomorrow_record = "";
	// maxdate +15D Y M настройка

	$now_calendar = date("Y/m/d",time() + (86400*$next_day));

	$all_options = $specialist."*@%".$work_time."*@%".$record."*@%".$all_days."*@%".$next_day;

	if ($calendar_maxDate == 1) $calendar_maxDate = "maxDate: '+".$calendar_maxDate_days."D',";

	$day_name = array("воскресенье"=>0,"понедельник"=>1,"вторник"=>2,"среда"=>3,"четверг"=>4,"пятница"=>5,"суббота"=>6);
	$deleted_days = explode(",", $deleted_days);
	$deleted_day = array();
	foreach ($deleted_days as $value) {
		$deleted_day[] = $day_name[ trim($value) ];
	}
	$deleted_day = implode(",", $deleted_day);

	$deleted_dates = explode(",", $deleted_dates);
	$deleted_date = array();
	foreach ($deleted_dates as $value) {
		$deleted_date[] = "[".str_replace(".",", ",$value)."]";
	}
	$deleted_date = implode(", ", $deleted_date);
	$textX = "<script>$(function() {
	    $( '#datepicker' ).datepicker(  {
	      beforeShowDay: function(date) {
			var day = date.getDay();
			var closedDates = [".$deleted_date."];
			var closedDays = [".$deleted_day."];
			var x = [true];
			for (var i = 0; i < closedDays.length; i++) {
				if (day == closedDays[i]) {
					x = [false];
				}
			}
			for (var i = 0; i < closedDates.length; i++) {
				/* 11=30=2013=30=11=2013--
				alert(date.getMonth() + '=' + date.getDate() + '=' + date.getFullYear() + '=' + closedDates[i][0] + '=' + closedDates[i][1] + '=' + closedDates[i][2] + '--');
				*/
				if (closedDates[i][2] === undefined) {
					if (date.getMonth() == closedDates[i][1] - 1 && date.getDate() == closedDates[i][0]) {
						x = [false];
					}
				} else {
					if (date.getMonth() == closedDates[i][1] - 1 && date.getDate() == closedDates[i][0] && date.getFullYear() == closedDates[i][2]) {
						x = [false];
					}
				}
			}
					
			return x;
	      },
	      numberOfMonths: ".$numberOfMonths.",";
	      if (!is_admin($admin)) $textX .= "minDate: new Date('".$now_calendar."'), ".$calendar_maxDate;
	      $textX .= "onSelect: function(date) {
	            $(show_raspisanie(".$idX.", '".$all_options."*@%' + date));
	      },
	    });
	});
	function show_zapis(num, data, spec) {
		$('#zapis_dialog_spec').html(spec);
		$('#zapis_dialog_data').html(data);
		$('#zapis_specialist').val(spec);
		$('#zapis_dat').val(data);
		$('#zapis_num').val(num);
		$('#zapis_dialog').show().dialog();
		$('#zapis_name').val('');
		$('#zapis_tel').val('');
		$('#zapis_del').hide();
		$('#zapis_send').val('".$zapis_send."');";

		if (is_admin($admin)) $textX .= "num2 = num.split(',');
		$('#zapis_name').val(num2[3]);
		num2 = num2[4].split(';');
		$('#zapis_tel').val(num2[0]);
		$('#zapis_del').show();
		if (isNaN(num)) $('#zapis_send').val('".aa("Сохранить изменения")."');";

	$textX .= "}
	</script>

	<div id='zapis_dialog' class='hide' title='".$zapis_na_priem."'>
	<form id='zapis'>
	<p>".$zapis_obrashenie."</p>
	<p><label for='your_name'>".$zapis_your_name."</label> <input id='zapis_name' name='your_name' type='text' size='30' style='width:100%' required>
	<p><label for='your_tel'>".$zapis_your_tel."</label> <input id='zapis_tel' name='your_tel' type='tel' size='30' style='width:100%' required>
	<p><span for='zapis_specialist'>".$zapis_spec."</span> <span id='zapis_dialog_spec'>---</span><input id='zapis_specialist' value='' name='zapis_specialist' type='hidden'>
	<p><span for='zapis_dat'>".$zapis_data."</span> <span id='zapis_dialog_data'>---</span><input id='zapis_dat' value='' name='zapis_dat' type='hidden'>
	<p><input value='".$zapis_send."' type='button' id='zapis_send' onclick='save_raspisanie()'> <input value='".aa("Удалить")."' type='button' class='hide' id='zapis_del' onclick='$(\"#zapis_name\").val(\"\"); save_raspisanie()'></p>
	<input value='".$zapis_zayavka_send."' name='zapis_zayavka_send' type='hidden'>

	<input id='zapis_num' value='' name='zapis_num' type='hidden'>
	<input value='".$idX."' name='id_block' type='hidden'>
	</form>
	</div>";

	$textX .= $tomorrow_record.'<div id="datepicker"></div>
	<script>$(show_raspisanie('.$idX.', "'.$all_options.'*@%'.$current_day.'"));</script>
	<div id="show_raspisanie'.$idX.'"></div>';

	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "15": # КАРТА
	// Если обображается некорректно, скорее всего вы применили стили оформления (css) к table, tr, th или td напрямую, без указания каких-либо классов.
	$map_house_address = str_replace('|||', '","', addslashes($map_house_address));
    $map_house_name = str_replace('|||', '","', addslashes($map_house_name));
    $map_house_description = str_replace('|||', '","', addslashes($map_house_description));
    $map_shablon = addslashes($map_shablon);
    $map_center = addslashes($map_center);
    $map_zoom = intval($map_zoom);
    
	$textX = '<script src="http://api-maps.yandex.ru/1.1/index.xml?key='.$map_yandex_key.'" type="text/javascript"></script>
    <script type="text/javascript">
        // Создает обработчик события window.onLoad
        YMaps.jQuery(function () {
            // Создает экземпляр карты и привязывает его к созданному контейнеру
            var map = new YMaps.Map(YMaps.jQuery("#YMapsID")[0]);
            map.addControl(new YMaps.TypeControl());
            map.addControl(new YMaps.ToolBar());
            map.addControl(new YMaps.Zoom());
            map.addControl(new YMaps.MiniMap());
            map.addControl(new YMaps.ScaleLine());
            // Устанавливает начальные параметры отображения карты: центр карты и коэффициент масштабирования
            var city = new YMaps.Geocoder("'.$map_center.'");
            YMaps.Events.observe(city, city.Events.Load, function () {
                if (this.length()) {
                    map.setCenter(this.get(0).getGeoPoint(), '.$map_zoom.');
                    //alert("Найдено :" + this.length());
                    //map.addOverlay(this.get(0));
                    //map.panTo(this.get(0).getGeoPoint())
                }
            });
            
            //map.setCenter(new YMaps.GeoPoint(37.64, 55.76), 10);

            var s = new YMaps.Style();
            s.balloonContentStyle = new YMaps.BalloonContentStyle(
                new YMaps.Template("'.$map_shablon.'")
            );

            // место
            var house_address = ["'.$map_house_address.'"];
            var house_name = ["'.$map_house_name.'"];
            var house_description = ["'.$map_house_description.'"];
            //var dom = house_address[0];
            //var name = house_name[0];
            //var description = house_description[0];
            for (var i = house_address.length - 1; i >= 0; i--) {

                var geocoder = new YMaps.Geocoder(house_address[i]);
                geocoder.dom = house_address[i];
                geocoder.name = house_name[i];
                geocoder.description = house_description[i];

                YMaps.Events.observe(geocoder, geocoder.Events.Load, function (geocoder) { //, house_address, house_name, house_description
                    //alert (i);
                    var geoCoords = geocoder.get(0).getGeoPoint();
                    // Создает метку в центре города
                    var placemark = new YMaps.Placemark(geoCoords, {style: s});
                    // Устанавливает содержимое балуна
                    placemark.dom = geocoder.dom;
                    placemark.name = geocoder.name;
                    placemark.description = geocoder.description;
                    //placemark.setIconContent(house_name[i]);
                    // Добавляет метку на карту
                    map.addOverlay(placemark);
                    //map.addOverlay(geocoder.get(0));
                });

            };
			
        })
    </script>
    <div id="YMapsID" style="width:600px;height:600px"></div>';

	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "22": # База данных (количество по 2 колонкам)
	if ($direct=="gor") { $direct = $first; $first = $second; $second = $direct; }
	$and = "";
	if (trim($textX) != "") $and = " and ".$textX;
	$firsts = array();
	$result = $db->sql_query("SELECT ".$first." FROM ".$prefix."_base_".$base." where (active='1' or active='3')".$and);
	while ($row = $db->sql_fetchrow($result)) {
		$firsts[] = $row[$first];
	}
	$firsts = array_unique($firsts);
	
	$seconds = array();
	$result = $db->sql_query("SELECT ".$second." FROM ".$prefix."_base_".$base." where (active='1' or active='3')".$and);
	while ($row = $db->sql_fetchrow($result)) {
		$seconds[] = $row[$second];
	}
	$seconds = array_unique($seconds);

	$textX = "<table class='base_table' width='100%'><tr><td class='base_first'>".$text1."</td>";
	foreach ($firsts as $first1) {
		$textX .= "<td class='base_first'>".$first1."</td>";
	}
	if ($all==1) {
	$textX .= "<td class='base_first'>".ss("Всего").":</td>";
	}
	$textX .= "</tr>";
	foreach ($seconds as $second1) {
		$textX .= "<tr><td class='base_second'>".$second1."</td>";
		foreach ($firsts as $first1) {
			$numrows = $db->sql_numrows($db->sql_query("SELECT ".$first." FROM ".$prefix."_base_".$base." 
			WHERE ".$first."='".$first1."' and ".$second."='".$second1."' and (active='1' or active='3')$and"));
			$textX .= "<td class='base_second_first'><a href=-".$useitX."_first_".str_replace("%","|",urlencode(str_replace("-","-_-",str_replace("+","+++",$second1))))."_second_".str_replace("%","|",urlencode(str_replace("-","---",str_replace("+","+_+",$first1))))."_opt_".$idX.">".$numrows."</a></td>";
		}
		if ($all==1) {
			$numrows = $db->sql_numrows($db->sql_query("SELECT ".$first." FROM ".$prefix."_base_".$base." 
			WHERE ".$second."='".$second1."' and (active='1' or active='3')".$and));
			$textX .= "<td class='base_second_first'><a href=-".$useitX."_first_".str_replace("%","|",urlencode(str_replace("-","---",str_replace("+","+++",$second1))))."_opt_".$idX.">".$numrows."</a></td>";
		}
		$textX .= "</tr>";
	}
	$textX .= "</table>";

	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "23": # База данных (список по нескольким колонкам)
	$and = "";
	if (trim($textX) != "") $and = " and ".$textX;
	if ($direct=="vert") {
		$where = "where active='1' or active='3'".$and." ";
		$text1 = str_replace(",","</td><td>",$text1);
		if ($textX != "") $where = "where ".stripcslashes($textX)." and (active='1' or active='3')".$and." ";
		$textX = "<table class='base_table table_light' width='100%'>";
		if ($text1 != "") $textX .= "<tr class='base_first'><td>".$text1."</td></tr>";
		// узнать имя БД по номеру
		global $id_razdel_and_bd;
		$base_name = WhatArrayElement($id_razdel_and_bd, $base);
		$razdel_name = WhatArrayElement($id_razdel_and_bd, $useitX);
		$sql = "SELECT id, ".$col." FROM ".$prefix."_base_".$base_name." ".$where." order by ".$sort." limit ".$number.",".$size."";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$pass_num = count($row)/2;
			$textX .= "<tr class='base_second'>";
			for ($x=1; $x < $pass_num; $x++) {
				if ($x==1) $textX .= "<td><a href=-".$razdel_name."_page_".$row['id'].">".$row[$x]."</a></td>";
				else $textX .= "<td>".$row[$x]."</td>";
			}
			$textX .= "</tr>";
		}
		$textX .= "</table>";
	} else {
		$textX .= ss("Горизонтальный вывод данного блока еще не готов.");
	}
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "30": # Статистика раздела, выводит кол-во посещений
	$textX = ss("Ошибка");
	$sql8 = "SELECT `counter` FROM ".$prefix."_mainpage WHERE `tables`='pages' and (`name` = '".$useitX."' or `name` like '".$useitX." %') and type='2'";
	$result8 = $db->sql_query($sql8);
	$row8 = $db->sql_fetchrow($result8);
	$textX = $row8['counter'];
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "31": # Блок JS
	if ($js == "") {
		$contents = array();
		$sql = "SELECT `id` FROM ".$prefix."_mainpage WHERE `type`='3' and `name`='31' and `tables`='pages' and `color`='0'";
		$result = $db->sql_query($sql);
		while($row = $db->sql_fetchrow($result)) {
			$contents[] = $row['id'];
		}
		if (count($contents) > 0) $js = implode("-",$contents);
		else $js = "no";
	}
	$type = ""; break;
//case "8":
	} # конец switch - определения типа блока
} else $block = str_replace("[".$titleX."]", "", $block); // убираем отключенный блок
} // закрытие if наличие блока на странице
} # ЗАКОНЧЕНО Определение блоков и их заполнение
} # ЗАКОНЧЕНО ПОВТОРНОЕ Определение блоков и их заполнение
//////////////////////////////////////////////////////////////////////////////////////////////////////

	// Заголовок для раздела ставится в дизайн
	if (strpos(" ".$block, aa("[заголовок"))) {
		$block = str_replace(aa("[заголовок]"), "<div class='cat_title'>".$main_title."</div>", $block); 
		$block = str_replace(aa("[заголовок-ссылка]"), "<div class='cat_title'><A class='cat_categorii_link' href=-".$DBName.">".$main_title."</a></div>", $block); 
	}
	if (strpos(" ".$block, aa("[название папки]"))) {
		if (!isset($cid)) $cid = 0;
		if ($cid > 0) $block = str_replace(aa("[название папки]"), "<div class='papka_title'> &rarr; ".titles_papka($cid)."</div>", $block);
		else $block = str_replace(aa("[название папки]"), "", $block);
	}

	// Регистрация / Вход
	if (strpos(" ".$block, aa("[регистрация]"))) {
		$registr = "<div class='registration'>
		<a class='button' onclick=\" 
		$.ajax({ url: 'ajax.php', cache: false, dataType : 'html',
		    data: {'func': 'registration_form'},
		    beforeSend: function(){ $('.registr').show(); $('.registration').hide(); },
		    success: function(data){ $('.registr').html(data); }
		});\">".ss("Регистрация")."</a> 
		<a class='button' onclick=\"$('.vhod').show(); $('.registration').hide();\">".ss("Вход")."</a>
		</div>
		<div class='registr hide'>
		".ss("Загрузка...")."
		</div>
		<div class='vhod hide'>
		<form class='reg_forma' action='login' method='post'> 
		<input class='reg_mail' type='text' name='em' value='' placeholder='".ss("Email")."'>
		<br><input class='reg_pass' type='password' name='pa' value='' placeholder='".ss("Пароль")."'>
		<br><input type='submit' name='submit' value='".ss("Войти")."'></form>
		</div>";
		$block = str_replace(aa("[регистрация]"), $registr, $block);
	}

	// Ставим годовой промежуток существования сайта
	$god = $startdate;
	$year = date("Y");
	$nextyear = $year+1;
	if ($year != $startdate) $god .= "—".$year;
	$block = str_replace(aa("[год]"), "© ".$god, $block); // Промежуток лет ставится в дизайн

	// Символ рубля
	$block=str_replace(aa("[руб]"), "<span class='rur'>p<span>уб.</span></span>", $block); 

	// Ставим статистику
	$block=str_replace(aa("[статистика]"), $counter, $block); 

	// Ставим почту
	if (strpos(" ".$block, aa("[почта]"))) {
		$mailer = "<a href=\"/mail.php\">".ss("Отправить письмо")."</a>"; // ".str_replace("@","<nobr>@</nobr>",$adminmail)." ";
		$soderganie = str_replace(aa("[почта]"), $mailer, $soderganie); 
		$block = str_replace(aa("[почта]"), $mailer, $block); 
	}

	// Ставим Новый год
	if (strpos(" ".$block, aa("[новый год]"))) { //February 12, 2001
		$newyaer = time_otschet(aa("January 01").", ".$nextyear, ss("C Новым годом!!!"), ss("До Нового года осталось: "));
		$soderganie = str_replace(aa("[новый год]"), $newyaer, $soderganie); 
		$block = str_replace(aa("[новый год]"), $newyaer, $block); 
	}
	if (strpos(" ".$block, aa("[1 сентября]"))) {
		// Ставим 1 сентября 2011,1,1
		if ( date("m") > 9 or ( date("m") == 9 and date("d") > 1 ) ) $year = $nextyear;
		$sent = time_otschet(aa("September 01").", ".$year, ss("Время пришло!"), ss("До нового учебного года осталось: "));
		$soderganie = str_replace(aa("[1 сентября]"), $sent, $soderganie); 
		$block = str_replace(aa("[1 сентября]"), $sent, $block); 
	}
	if (strpos(" ".$block, aa("[список "))) {
		// Ставим автогенерацию раскрывающегося списка из заголовков и последующих элементов
		if ( date("m") > 9 or ( date("m") == 9 and date("d") > 1 ) ) $year = $nextyear;
		$zagolovok = array("h2","h3");
		$spisok = array("p","table", "div", "blockquote");
		foreach ($zagolovok as $z) {
			foreach ($spisok as $s) {
				$sent = '<script>$(function() {
						$("'.$z.'").toggleClass("button").next("'.$s.'").hide(); $("'.$z.'").click(function() { $(this).next("'.$s.'").slideToggle(); return false; });
				});</script>';
				$soderganie = str_replace(aa("[список ").$z." ".$s."]", $sent, $soderganie); 
				$block = str_replace(aa("[список ").$z." ".$s."]", $sent, $block);
			}
		}
	}
	if (strpos(" ".$block, aa("[корзина]"))) {
		// Ставим ajax-блок Корзины
		$sent = "<script>$(function() {	shop_show_card(); });</script><div id='shop_card'></div>";
		$soderganie = str_replace(aa("[корзина]"), $sent, $soderganie);  // проверить soderganie и block
		$block = str_replace(aa("[корзина]"), $sent, $block);
	}

	// Ставим почту
	if (strpos(" ".$block, aa("письмо]"))) {
		// Заявка
		$mailer = "<table width='100%' border='' class='mail_form mail_form_1'> 
		<form action=send.php enctype='multipart/form-data' method=post name=formsend> 
		<tr><td><nobr>".ss("Ф.И.О., компания:")."</nobr><br><input type=text name=mail_subject maxlength=32 size=40 class=all_width></td></tr> 
		<tr><td><nobr>".ss("Электропочта:")."</nobr><br><input type=text name=mail_to maxlength=64 size=40 class=all_width></td></tr> 
		<tr><td><nobr>".ss("Телефон:")."</nobr><br><input type=text name=mail_tel maxlength=30 size=40 class=all_width></td></tr> 
		<tr><td>".ss("Заявка:")."<br><textarea cols=50 rows=4 name=mail_msg class=all_width></textarea><br>
		<input type=hidden name=mail_file></td> 
		</tr><tr><td>
		<span class=small>".ss("* Все поля обязательны к заполнению")."</span>
		<p align=right><input class='standart_send_button' value=\"".ss("Отправить")."\" type=\"button\" onClick=\" al=''; if (document.formsend.mail_subject.value=='') al = al + '".ss("Вы не заполнили поле &quot;Ф.И.О.&quot;.")." '; if (document.formsend.mail_to.value=='') al = al + '".ss("Вы не заполнили поле &quot;Электропочта&quot;.")." '; if (document.formsend.mail_msg.value=='') al = al + '".ss("Вы не заполнили поле &quot;Заявка&quot;.")." '; if (al) alert(al); else submit();\"></p>
		</td></tr> 
		</form> 
		</table>"; 
		$soderganie=str_replace(aa("[заявка-письмо]"), $mailer, $soderganie); 
		$block=str_replace(aa("[заявка-письмо]"), $mailer, $block); 

		// Обычная почта
		$mailer = "<table width='100%' border='' class='mail_form mail_form_2'> 
		<form action=send.php enctype='multipart/form-data' method=post name=formsend> 
		<tr><td><nobr><b>".ss("Фамилия, имя*:")."</b></nobr></td><td width=60%><input type=text name=mail_subject maxlength=32 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>".ss("Электропочта*:")."</b></nobr></td><td width=60%><input type=text name=mail_to maxlength=64 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>".ss("Телефон*:")."</b></nobr></td><td width=60%><input type=text name=mail_tel maxlength=30 size=40 class=all_width></td></tr> 
		<tr><td colspan=2><b>".ss("Текст письма*:")."</b><br><textarea cols=50 rows=8 name=mail_msg class=all_width></textarea><br>
		<input type=hidden name=mail_file></td> 
		</tr><tr><td colspan=2>
		<span class=small>".ss("* Все поля обязательны к заполнению")."</span>
		<p align=right><input class='standart_send_button' value=\"".ss("Отправить")."\" type=\"button\" onClick=\" al=''; if (document.formsend.mail_subject.value=='') al = al + '".ss("Вы не заполнили поле &quot;Фамилия, имя&quot;.")." '; if (document.formsend.mail_to.value=='') al = al + '".ss("Вы не заполнили поле &quot;Электропочта&quot;.")." '; if (document.formsend.mail_msg.value=='') al = al + '".ss("Вы не заполнили поле &quot;Текст письма&quot;.")." '; if (al) alert(al); else submit();\"></p>
		</td></tr> 
		</form> 
		</table>"; 
		$soderganie=str_replace(aa("[письмо]"), $mailer, $soderganie); 
		$block=str_replace(aa("[письмо]"), $mailer, $block); 

		// Ставим почту+имя
		$mailer = "<table width='400' border='0' align='center' class='mail_form mail_form_3'>
		<form action=send.php enctype='multipart/form-data' method=post name=formsend> 
		<tr><td><nobr><b>".ss("Ваше имя*:")."</b></nobr></td><td width=60%><input type=text name=mail_subject maxlength=32 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>".ss("Электропочта:")."</b></nobr></td><td width=60%><input type=text name=mail_to maxlength=64 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>".ss("Телефон*:")."</b></nobr></td><td width=60%><input type=text name=mail_tel maxlength=30 size=40 class=all_width></td></tr> 
		<tr><td colspan=2>".ss("* - поля обязательны к заполнению.")."
		<p align=right><input class='micro_send_button' value=\"".ss("Отправить")."\" type=\"button\" onClick=\" al=''; if (document.formsend.mail_subject.value=='') al = al + '".ss("Вы не заполнили поле &quot;Ваше имя&quot;.")." '; if (al) alert(al); else submit();\"></p>
		</td></tr> 
		</form> 
		</table>"; 
		$soderganie = str_replace(aa("[микрописьмо]"), $mailer, $soderganie); 
		$block = str_replace(aa("[микрописьмо]"), $mailer, $block); 

		// Ставим почту короткую
		$mailer2 = "<table width='100%' border='' class='mail_form mail_form_4'> 
		<form action=send.php enctype='multipart/form-data' method=post name=formsend> 
		<tr><td><nobr><b>".ss("Фамилия, имя*:")."</b></nobr></td><td width=60%><input type=text name=mail_subject maxlength=32 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>".ss("Электропочта*:")."</b></nobr></td><td width=60%><input type=text name=mail_to maxlength=64 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>".ss("Телефон*:")."</b></nobr></td><td width=60%><input type=text name=mail_tel maxlength=30 size=40 class=all_width></td></tr> 
		<tr><td colspan=2><b>".ss("Комментарий*:")."</b><br>
		<textarea cols=50 rows=2 name=mail_msg class='all_width'></textarea>
		<br><input type=hidden name=mail_file></td> 
		</tr><tr><td colspan=2>
		<span class=small>".ss("* Все поля обязательны к заполнению")."</span>
		<p align=right><input class='small_send_button' value=\"".ss("Отправить")."\" type=\"button\" onClick=\" al=''; if (document.formsend.mail_subject.value=='') al = al + '".ss("Вы не заполнили поле &quot;Фамилия, имя&quot;.")." '; if (document.formsend.mail_to.value=='') al = al + '".ss("Вы не заполнили поле &quot;Электропочта&quot;.")." '; if (document.formsend.mail_msg.value=='') al = al + '".ss("Вы не заполнили поле &quot;Комментарий&quot;.")." '; if (al) alert(al); else submit();\"></p>
		</td></tr> 
		</form> 
		</table>
		"; 
		$soderganie=str_replace(aa("[миниписьмо]"), $mailer2, $soderganie); 
		$block=str_replace(aa("[миниписьмо]"), $mailer2, $block); 
	}

	// Ставим поиск
	$search = "<form method='POST' action='search' class='main_search_form'><input type='search' name='slovo' placeholder='Поиск'><input type='submit' name='ok' value='".ss("Найти")."' class='main_search_button'></form>";
	$block=str_replace(aa("[поиск]"), $search, $block);

	// Ставим подписку
	if (strpos(" ".$block, aa("[подписка"))) {
		$search = "<form method='POST' action='email' class='main_mail_form'><table width='100%'><tr><td align='right'>".ss("Электропочта:")." </td><td><input type='text' name='mail' class='main_mail_input' size='10' class='all_width'></td></tr><tr><td align='right'>".ss("Имя:")." </td><td><input type='text' name='avtor' class='main_mail_input' size='10' class='all_width'></td></tr><tr><td colspan='2' align='right'><input type='submit' name='ok' value='".ss("Подписаться")."'></td></tr></table></form>";
		$block=str_replace(aa("[подписка]"), $search, $block);

		// Ставим подписку в линию
		$search = "<form method='POST' action='email' class='main_mail_form'><table><tr><td><b>".ss("Рассылка:")." </b></td><td>&nbsp;".ss("Электропочта:")."</td><td><input type='text' name='mail' class='main_mail_input' size='10' class='all_width'></td><td>&nbsp;Имя:</td><td><input type='text' name='avtor' class='main_mail_input' size='10' class='all_width'></td><td colspan='2' align='right'><input type='submit' name='ok' value='".ss("Подписаться")."'></td></tr></table></form>";
		$block=str_replace(aa("[подписка_горизонт]"), $search, $block);
	}

	// Ставим день и время
	if (strpos(" ".$block, aa("[день]")) or strpos($block, aa("[время]"))) {
		$den = date("d m Y");
		$den = explode(" ",$den);
		$den = intval($den[0])." ".findMonthName($den[1])." ".$den[2];
		$vremya = date("H:i", time() + 3600); // + 1 час - Самарское время.
		$block=str_replace(aa("[день]"), $den, $block);
		$block=str_replace(aa("[время]"), $vremya, $block);
	}

	global $project_logotip, $project_name, $sitename, $description;
	if (strpos(" ".$block, aa("_проекта]"))) {
		$block=str_replace(aa("[лого_проекта]"), "<img src='".$project_logotip."' class='project_logotip'>", $block);
		$block=str_replace(aa("[заголовок_проекта]"), $sitename, $block);
		$block=str_replace(aa("[описание_проекта]"), $description, $block);
		$block=str_replace(aa("[название_проекта]"), $project_name, $block);
		$block=str_replace(aa("[название_лого_проекта]"), "<h1 class='project_logotip_name'><a href='/'' title='".ss("Главная страница")."'><span>".$project_name."</span><img src='".$project_logotip."' alt=''></a></h1>", $block);
	}

	// вход в закрытый раздел
	if (strpos(" ".$block, aa("[закрытая зона]"))) {
		$block=str_replace(aa("[закрытая зона]"), "Введите пароль:<br><input class='closed_zona' id='closed_zona'><input class='closed_zona' type='button' value='Войти' onclick='location.href = \"-\" + $(\"#closed_zona\").val();'>", $block);
	}
	// Ставим кнопку Твиттера
	$block=str_replace(aa("[твиттер]"), "<div><a href='https://twitter.com/share' class='twitter-share-button' data-lang='ru' data-size='large'>Твитнуть</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script></div>", $block);

	// Ставим символ |
	$block=str_replace("[-]", "|", $block); //  УБРАТЬ!!!!!!!!!!!!!!!!!!!!!!!

	// Ставим фильтр для магазина
	if (strpos(" ".$block, aa("[фильтр]"))) {
		global $filter, $filter_show_all, $filter_name, $id_razdel_and_bd;
		$filtr = "";
		if ($pid == 0) {
			$id = $id_razdel_and_bd[$DBName];
			// получаем список id страниц выбранного раздела и папки
			$sql2 = "select `pid` from ".$prefix."_pages where `cid`='".$cid."' and `module`='".$DBName."' and `tables`='pages' and `active`='1' order by `pid`";
			$result2 = $db->sql_query($sql2);
			$numrows = $db->sql_numrows($result2);
			if ($numrows > 5) {
				$and = array();
				while ($row2 = $db->sql_fetchrow($result2)) {
					$and[] = "`pages` like '% ".$row2['pid']." %'";
				}
				$and = " and (".implode(" or ", $and).")";
				// получаем все поля
				$sql = "select `id`, `title`, `name`, `text` from ".$prefix."_mainpage 
				where (`useit`='".$id."' or `useit`='0') and (`shablon` like '% ".$cid." %' or `shablon` = '' or `shablon` = '0' or `shablon` = ' 0 ') 
				and `type`='4' and `tables`='pages' order by `title`";
				$result = $db->sql_query($sql);
				$filtr .= "<form method='post'>";
				while ($row = $db->sql_fetchrow($result)) {
					$s_id = $row['id'];
					$s_title = $row['title'];
					$s_name = $row['name'];
					$options = explode("|", $row['text']);
					$options = $options[1];
					$type=0;
					$shablon=""; 
					parse_str($options);
					switch($type) {
						case "5": // число
						// получаем максимум и минимум для панели
						$sql2 = "select max(`name` + 0) max, min(`name` + 0) min from ".$prefix."_spiski where `type`='".$s_name."'".$and;
						$result2 = $db->sql_query($sql2);
						$row2 = $db->sql_fetchrow($result2);
						$max = $max2 = $row2['max'];
						$min = $min2 = $row2['min'];
						if ($max != 0 && $min != 0 && $max != $min) {
							if (isset($filter)) {
						    	$min2 = explode(" - ", $filter[$s_name]);
						    	$max2 = intval($min2[1]);
						    	$min2 = intval($min2[0]);
						    }
						    $filtr .= "<script>
						    $(function() {
							    $( '#slider-range".$s_name."' ).slider({
								    range: true,
								    min: ".$min.",
								    max: ".$max.",
								    values: [ ".$min2.", ".$max2." ],
								    slide: function( event, ui ) {
									    $('#amount".$s_name."').val( ui.values[ 0 ] + ' - ' + ui.values[ 1 ] );
									    $('#text_amount".$s_name."').html( ' от ' + ui.values[ 0 ] + ' до ' + ui.values[ 1 ] );
									    $('.change_filter').hide();
									    $('#change".$s_name."').show();
								    }
							    });
							    $( '#amount".$s_name."' ).val( $( '#slider-range".$s_name."' ).slider( 'values', 0 ) +
							    ' - ' + $( '#slider-range".$s_name."' ).slider( 'values', 1 ) );
							    $( '#text_amount".$s_name."' ).html( ' от ' +$( '#slider-range".$s_name."' ).slider( 'values', 0 ) +
							    ' до ' + $( '#slider-range".$s_name."' ).slider( 'values', 1 ) );
						    });
						    </script>
						    <p><span class='filter_title filter_title_".$s_name."'>".$s_title."</span> 
						    <nobr><span class='filter_interval filter_interval_".$s_name."' id='text_amount".$s_name."'></span></nobr>
						    <input type='hidden' name='filter[".$s_name."]' id='amount".$s_name."'></p>
						    <div id='slider-range".$s_name."'></div>
						    <div id='change".$s_name."' class='hide center change_filter'><button class='small' style='margin-top:10px;'>".ss("Показать")."</button></div>";
				    	}
				    	break;

				    	case "0": // список слов (выбор одного значения)
				    	case "4": // строка (можно написать шаблон)
						case "7": // список слов (выбор нескольких значений)
						// получаем максимум и минимум для панели
						$sql2 = "select `name` from ".$prefix."_spiski where `type`='".$s_name."'".$and." order by `name`";
						$result2 = $db->sql_query($sql2);
						$names = array();
						while ($row2 = $db->sql_fetchrow($result2)) {
							$names[] = $row2['name'];
						}
						$names = array_unique($names);
						if (count($names) > 0) {
							$filtr .= "<script>
						    $(function() {
							    $( '.text".$s_name."' ).click(function(){
									    $('.change_filter').hide();
									    $('#change".$s_name."').show();
							    });
						    });
						    </script>
						    <p><span class='filter_title filter_title_".$s_name."'>".$s_title.":</span><br>";
						    $filtr_values = "";
						    $smaller_then_4 = false;
						    $smaller_then_7 = false;
							foreach ($names as $key => $value) {
								$checked = "";
								if (isset($filter))
							    	if ($filter[$s_name."_".$value] == "on") $checked = " checked";
							    $filtr_values .= "<label for='text".$s_name."_".$key."'><input class='text".$s_name."' type='checkbox' name='filter[".$s_name."_".$value."]' id='text".$s_name."_".$key."'".$checked."> ".$value."</label><br>";

							    $strlen = mb_strlen($value);
							    if ($strlen > 4) $smaller_then_4 = true;
							    if ($strlen > 7) $smaller_then_7 = true;
							} 
							// Обработка внешнего вида
							// Если меньше трех символом в каждом слове - скоращенный вывод без перехода на следующую строку

							if ($smaller_then_4 == false || (count($names) < 4 && $smaller_then_7 == false)) 
								$filtr_values = str_replace("<br>", " ", $filtr_values);

							$filtr .= $filtr_values."<div id='change".$s_name."' class='hide center change_filter'><button class='small'>".ss("Показать")."</button></div>";
						}
				    	break;
			   		}
				} // end while
				$filtr .= "</form>";
				// Настройки фильтра
				if ($filter_name == "") $filter_name = ss("Фильтр товаров");
				if ($filter_show_all == "") $filter_show_all = ss("Показать все");
				if (isset($filter)) $filtr .= "<div class='center'><p><a href='-".$DBName."_cat_".$cid."' class='button small'>".$filter_show_all."</a></div>";
				if (trim($filter_name) != "" && $filtr != "<form method='post'></form>") 
					$filtr = "<div class='filter_form'><div class='filter_name'>".$filter_name."</div>".$filtr."</div>";
				else $filtr = "";
			}
		}
		$block = str_replace(aa("[фильтр]"), $filtr, $block);
	}

	// Ставим RSS
	if (strpos(" ".$block, "[rss"))
		$block=str_replace("[rss]", "<a href='rss.php' title='".ss("Подпишись на наши новости по RSS!")."' class='rss'><span class='ico_rss i16'></span></a>", $block);

	// MP3-плеер
	if (strpos(" ".$block, ".mp3\"")) {
		$mp3_player = true;
		$block=str_replace(".mp3\"", ".mp3\" class=\"jouele\"", $block);
	}

	// Обработка мини-блоков (карточка компании)
	function company_blocks($company, $name, $block) {
		if (strpos(" ".$block, "[".$name)) { // проверить
			$company = explode("|||", $company);
			foreach ($company as $key => $value) {
				$key_plus = $key + 1;
				$block = str_replace("[".$name.$key_plus."]", str_replace("\r\n", "<br>", $value), $block);
			}
		}
		return $block;
	}
	$block = company_blocks($company_name, aa("компания"), $block);
	$block = company_blocks($company_fullname, aa("КОМПАНИЯ"), $block);
	$block = company_blocks($company_address, aa("адрес компании"), $block);
	$block = company_blocks($company_time, aa("время работы компании"), $block);
	$block = company_blocks($company_tel, aa("телефон компании"), $block);
	$block = company_blocks($company_sot, aa("сотовый компании"), $block);
	$block = company_blocks($company_fax, aa("факс компании"), $block);
	$block = company_blocks($company_email, aa("почта компании"), $block);
	$block = company_blocks($company_map, aa("карта компании"), $block);
	$block = company_blocks($company_people, aa("лицо компании"), $block);

	// Ставим валютный информер (только для России)
	if (strpos(" ".$block, "[валюта]")) { // INFORMER VALUTA by 13i
		$eur = 0;
		$usd = 0;
		$dat = date("d.m.Y",time()); // за вчера: date("d.m.Y",time()-86400);
		$lines = http_request('http://www.cbr.ru/currency_base/D_print.aspx?date_req='.$dat); // file_get_contents
		$lines1 = explode("Доллар США</td>",$lines);
		$lines1 = explode("</td>",$lines1[1]);
		$usd = str_replace("<td align=\"right\">","",$lines1[0]);
		$lines1 = explode("Евро</td>",$lines);
		$lines1 = explode("</td>",$lines1[1]);
		$eur = str_replace("<td align=\"right\">","",$lines1[0]);
		$valuta .= "<A HREF=\"#\" onMouseDown=\"win2 = window.open('http://www.cbr.ru/currency_base/D_print.aspx?date_req=".$dat."','',
		'width=400,height=260'); return true;\">Валюта</a>: USD: ".$usd." EUR: ".$eur."";
		$block = str_replace("[валюта]", $valuta, $block);
	}
		
	if ($show_page_links == 1) {
		// Добавить if {} наличие

		// Получить заголовки всех страниц и разделов
		global $title_razdels, $show_page_links;
		// НАЙТИ ЗАМЕНУ, к примеру функцию поиска между скобками.
		foreach( $title_razdels as $key_name => $row_title ) { // Определяем все разделы
			$row_title = str_replace( "»","&raquo;", str_replace( "«","&laquo;", $row_title ) );
			//$row_title2 = predlogi($row_title);
			$block = str_replace("{".$row_title."}", "<a class='auto_link' href=-".$key_name.">".$row_title."</a>", $block);
			//$block = str_replace("{".$row_title2."}", "<a class='auto_link' href=-".$key_name.">".$row_title."</a>", $block);
		}
		// Определяем все страницы
		$sql = "SELECT `pid`, `module`, `title` FROM ".$prefix."_pages where `tables`='pages' and `active`='1'";
		$result = $db->sql_query($sql);
		while ($rows = $db->sql_fetchrow($result)) {
			$row_title = str_replace( "«","&laquo;", str_replace( "»","&raquo;", $rows['title'] ) );
			//$row_title2 = predlogi($row_title);
			//$row_title3 = str_replace("&nbsp;", " ", $row_title);
			$block=str_replace("{".$row_title."}", "<a class='auto_link' href=-".$rows['module']."_page_".$rows['pid'].">".$row_title."</a>", $block);
			//$block=str_replace("{".$row_title2."}", "<a class='auto_link' href=-".$rows['module']."_page_".$rows['pid'].">".$row_title."</a>", $block);
			//$block=str_replace("{".$row_title3."}", "<a class='auto_link' href=-".$rows['module']."_page_".$rows['pid'].">".$row_title."</a>", $block);
		}
	}


# ФОРМИРОВАНИЕ СТРАНИЦЫ
$pagetit = str_replace("<br>","",$pagetitle);

if ($keywords2 == "") $keywords2 = $keywords;
if ($description2 == "") $description2 = $description;

// При открытии раздела можно убирать определенные блоки и через CSS
global $add_css, $data_page, $lang, $kickstart, $jqueryui, $normalize, $sortable, $add_fonts, $url, $admin, $pid, $now, $nocash, $head_insert; //, $slider;
if (trim($add_css) != "") $stil .= "_add_".str_replace (" ","-", str_replace ("  "," ", trim($add_css))); 

// Кеширование: откл.
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Expires: " . date("r", time() + 3600));
if ($data_page != "") header ("Last-Modified: 0, ".$data_page." GMT");
else header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//header("Cache-Control: public");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0


header ("Content-Type: text/html; charset=utf-8");
echo "<!doctype html>\n
<!--[if lt IE 7 ]><html class='ie ie6 no-js lt-ie9 lt-ie8 lt-ie7' lang='".$lang."'> <![endif]-->
<!--[if IE 7 ]><html class='ie ie7 no-js lt-ie9 lt-ie8' lang='".$lang."'> <![endif]-->
<!--[if IE 8 ]><html class='ie ie8 no-js lt-ie9' lang='".$lang."'> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang='".$lang."' dir='ltr' class='no-js'> <!--<![endif]-->
\n<head>";
if (file_exists("favicon.png"))  echo "<link rel='shortcut icon' href='favicon.png' />";
else echo "<link rel='shortcut icon' href='favicon.ico' />";
// <meta http-equiv='Content-language' content='".$lang."'> 
// <meta name='copyright' content='".str_replace("'","",$sitename)."'>
if (isset($pass_name) || $index_ok == false) echo "<meta content='noindex, nofollow' name='robots' />";
else echo "<meta content='index, follow' name='robots' />";
echo "<title>".$pagetit.$sitename."</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<meta name='keywords' content='".str_replace("'","",$keywords2)."'>
<meta name='description' content='".str_replace("'","",$description2)."'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<!--[if lt IE 9]><script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script><![endif]-->
<!--[if IE]><script src='includes/iepngfix_tilebg.js'></script><![endif]-->
<script src='includes/j.js'></script>
<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js'></script>
<script src='includes/modernizr-1.5.min.js'></script>";

// Подключение других языков, если это не русский
if ($lang != 'ru') echo "<script src=\"language/".$lang.".js\"></script>";

if ($normalize != 0) echo "<link rel='stylesheet' href='includes/css-frameworks/normalize.css' />";

if ($sortable != 0) echo "<script src='includes/jquery.tinysort.min.js'></script>";

if ($jqueryui != 0) echo "<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js'></script><script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/i18n/jquery-ui-i18n.min.js'></script><link rel='stylesheet' href='http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css' media='all' /><script src='includes/jquery-ui-datepicker-ru.js'></script>";

switch($kickstart) { // Выбор CSS-фреймворка
	case 1: // KickStart
	echo "<script src='includes/css-frameworks/kickstart/js/kickstart.js'></script><link rel='stylesheet' href='includes/css-frameworks/kickstart/css/kickstart.css' media='all' /><link rel='stylesheet' href='includes/css-frameworks/kickstart/style.css' media='all' />"; break;
	case 2: // CSSframework
	echo "<link rel='stylesheet' href='includes/css-frameworks/css-framework.css' />"; break;
	case 3: // Skeleton
	echo "<link rel='stylesheet' href='includes/css-frameworks/skeleton/base.css'><link rel='stylesheet' href='includes/css-frameworks/skeleton/skeleton.css'><link rel='stylesheet' href='includes/css-frameworks/skeleton/layout.css'>"; break;
	case 4: // Kube
	echo "<link rel='stylesheet' href='includes/css-frameworks/kube/kube.min.css' /><link rel='stylesheet' href='includes/css-frameworks/kube/master.css' /><script src='includes/css-frameworks/kube/kube.buttons.js'></script><script src='includes/css-frameworks/kube/kube.tabs.js'></script>"; break;
	case 5: // Bootstrap
	echo "<link href='includes/css-frameworks/bootstrap/css/bootstrap.min.css' rel='stylesheet'><link href='includes/css-frameworks/bootstrap/css/bootstrap-responsive.min.css' rel='stylesheet'><script src='includes/css-frameworks/bootstrap/js/bootstrap.min.js'></script>"; break;
	case 6: // 1140 Grid
	echo "<!--[if lte IE 9]><link rel='stylesheet' href='includes/css-frameworks/1140_cssgrid/ie.css' media='screen' /><![endif]--><link rel='stylesheet' href='includes/css-frameworks/1140_cssgrid/1140.css' media='screen' /><script src='includes/css-frameworks/1140_cssgrid/css3-mediaqueries.js'></script>"; break;
	case 7: // Toast
	echo "<link rel='stylesheet' href='includes/css-frameworks/toast/toast.css' />"; break;
	case 8: // Blueprint
	echo "<link rel='stylesheet' href='includes/css-frameworks/blueprint/screen.css' media='screen, projection'><link rel='stylesheet' href='includes/css-frameworks/blueprint/print.css' media='print'><!--[if lt IE 8]><link rel='stylesheet' href='includes/css-frameworks/blueprint/ie.css' media='screen, projection'><![endif]-->"; break;
	case 9: // YUI CSS Grids
	echo "<link rel='stylesheet' href='http://yui.yahooapis.com/3.7.3/build/cssreset/reset-min.css' type='text/css'><link rel='stylesheet' href='http://yui.yahooapis.com/3.7.3/build/cssfonts/fonts-min.css' type='text/css'><link rel='stylesheet' href='http://yui.yahooapis.com/3.7.3/build/cssgrids/grids-min.css' type='text/css'><script src='http://yui.yahooapis.com/3.7.3/build/yui/yui-min.js'></script>"; break;
	case 10: // 960gs (12 и/или 16 колонок)
	echo "<link rel='stylesheet' href='includes/css-frameworks/960gs/reset.css' /><link rel='stylesheet' href='includes/css-frameworks/960gs/text.css' /><link rel='stylesheet' href='includes/css-frameworks/960gs/960.css' />"; break;
	case 11: // 960gs (24 колонки)
	echo "<link rel='stylesheet' href='includes/css-frameworks/960gs/reset.css' /><link rel='stylesheet' href='includes/css-frameworks/960gs/text.css' /><link rel='stylesheet' href='includes/css-frameworks/960gs/960_24_col.css' />"; break;
	default:
	break;
}

// Подключение mp3-плеера
if ($mp3_player == true)
	echo "<script src='includes/jquery.jplayer.min.js'></script><script src='includes/jouele/jouele.js'></script><link rel='stylesheet' href='includes/jouele/jouele.css' />";

// Подключение фото-галерей
if ($gallery_css3 == true) echo "<link rel='stylesheet' href='includes/lightbox-css3.css' media='screen' />";
if ($gallery_carusel == false && $kickstart != 1) echo "<script src='includes/jquery.lightbox.js'></script><script src='includes/jquery.ad-gallery.js'></script><script>$(document).ready(function(){ $('.lightbox').lightbox({ fitToScreen: true, imageClickClose: false }); var galleries = $('.ad-gallery').adGallery(); $('#switch-effect').change( function() { galleries[0].settings.effect = $(this).val(); return false; } ); });</script>
<link rel='stylesheet' href='includes/lightbox.css' media='screen' />"; // при включенном kickstart, lightbox не нужен, включается fancybox
if ($gallery_carusel == true) echo "<script src='includes/jquery.lightbox.js'></script><script src='includes/jquery.ad-gallery.js'></script><script>function light_box(){ $('.lightbox').lightbox({ fitToScreen: true, imageClickClose: false } $(document).ready(light_box()); var galleries = $('.ad-gallery').adGallery(); $('#switch-effect').change( function() { galleries[0].settings.effect = $(this).val(); return false; } ); });</script><link rel='stylesheet' href='includes/carusel.css' media='screen' />";
if ($gallery_sly == true) echo "<script src='includes/sly.min.js'></script><link rel='stylesheet' href='includes/sly.css' media='screen' />";
if ($gallery_sly_full == true) echo "<script src='includes/sly.min.js'></script><link rel='stylesheet' href='includes/sly_full.css' media='screen' />";

if ($js != "" && $js != "no") echo "<script src='js_".$js.".js'></script>";

if ($lightload != 0) echo "<script src='includes/lightload.js'></script>";
if ($spin != 0) echo "<script src='includes/spin.js'></script>";
if ($scrollyeah != 0) echo "<script src='includes/scrollyeah.js'></script><link rel='stylesheet' href='includes/scrollyeah.css' />";

echo "<link rel='alternate' href='/rss/' title='".$project_name." RSS' />
<link rel='stylesheet' href='".$stil.".css' />";

if (mb_strlen($add_fonts)>1) {
	$add_fonts = explode(".",$add_fonts);
	if (count($add_fonts) > 0) {

		foreach ($add_fonts as $font) {
			$font = explode(",",$font);
			$effect_show = "";
			if (isset($font[1])) $effect_show = '&effect='.$font[1]; // эффект шрифта
			$font = str_replace(" ", "+", $font[0]);
			echo '<link href="http://fonts.googleapis.com/css?family='.$font.'&subset=latin,cyrillic'.$effect_show.'" rel="stylesheet" type="text/css">';
		}
	}
}
	$add_body = "";
	if ($stopcopy == 1) $add_body .= " oncontextmenu='notmenu();'"; // «защита» от глупых копипастеров
	if ($kickstart == 10) $add_body .= " class='yui3-skin-sam'";
	if ($kickstart == 1) $add_body .= ' class="elements"';
	# НАЧАЛО ТЕЛА
	echo $head_insert."</head>\n<body".$add_body." id='page'>";
	if ($kickstart == 1) echo "<div class='grid'>"; 
	//<a id='top-of-page'></a><div id='wrap' class='clearfix'>"; //<div class='grid'>
	if ($kickstart == 3 or $kickstart == 8) echo "<div class='container'>";
	//if ($kickstart == 4) echo "<div id='page'>";
	echo $block; // Вывод страницы

	if ($url=="-index") { // снег, листья и шарики на Главной
		if ($pogoda==1) echo "<script src='includes/sneg.js'></script>\n";
		if ($pogoda==2) echo "<script src='includes/list.js'></script>\n";
		if ($pogoda==3) { echo "<script src='includes/shar.js'></script>\n"; include("includes/ballon.htm"); }
	}
	
	// Если включена SWF Flash поддержка
	if ($flash==1) echo "<script src='includes/swfobject.js'></script><script src='includes/swffix_modified.js'></script>";

	// Если включена защита от копирования (для школьников)
	if ($stopcopy==1) echo "<script><!-- 
	function notmenu() { window.event.returnValue=false; } document.ondragstart = test; document.onselectstart = test; document.ontextmenu = test; function test() { return false } 
	// --></script> ";

	if ($kickstart == 1 or $kickstart == 3 or $kickstart == 4 or $kickstart == 8) echo "</div>";

	echo "</body></html>";

	$txt = ob_get_contents(); // собираем файл для вывода на экран и сохранения в кеше

	if (isset($pass_name)) $txt = str_replace("-".$pass_rename, "-".$pass_name, $txt);

	ob_end_clean();
	if (is_admin($admin)) echo page_admin($txt,$pid); // добавили функции админа к страничке
	else echo $txt;
	// если в config.php выбрано «показывать ошибки», помимо этого покажет запросы к БД и их количество
	if ($display_errors == true) print("<!-- DataBase queries: $db->num_queries \n $db->num_q -->");

	// Проверка добавляемой информации
	if ( $site_cash != false ) {
		$numrows = 0;
		$txt = addslashes($txt);
		global $url_link;
		if ($url_link != "") {
			if ($site_cash == "base") {
			    $numrows = $db->sql_fetchrow($db->sql_query("SELECT count(`id`) i FROM ".$prefix."_cash where `url`='".$url_link."' limit 1"));
			    $numrows = $numrows['i'];
			}
			// если кеш на файлах
			if ($site_cash == "file") {
				if ($url_link == '/' || $url_link == '') $url_link = "-index";
					if (file_exists("cashe/".$url_link)) $numrows = 1;
			}
		    if ($numrows == 0 && $url_link != "-search" && $url_link != "savecomm" && $url_link != "savepost") {
				// Добавление в кеш
				if ($site_cash == "base") // если кеш в БД
					$db->sql_query("INSERT INTO `".$prefix."_cash` (`id`, `url`, `data`, `text`) VALUES (NULL, '".$url_link."', '".$now."', '".$txt."');") or die (ss("Обновите страницу, (нажав F5)."));
				elseif ($site_cash == "file") { // если кеш в файлах
					$filestr = fopen ("cashe/".$url_link,"w+");
					fwrite($filestr, $txt);
					fclose ($filestr);
				}
			}
		}
	}
	antivirus(); // Запуск антивируса :)
?>