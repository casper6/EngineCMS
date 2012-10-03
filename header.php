<?php
	define('MODULE_FILE', true);
	require_once("mainfile.php");
	global $strelka, $siteurl, $cookie, $prefix, $module_name, $name, $db, $sitekey, $admin, $sitename, $pagetitle, $pagetitle2, $registr, $pogoda, $flash, $keywords, $description, $counter, $startdate, $adminmail, $keywords2, $description2, $stopcopy, $nocash, $blocks, $http_siteurl, $display_errors;
	$nocash = false;
	if ($name == "") $name = "index";

    $username = "Аноним"; // удалить

if ($name=="-email") { // занесение мыла как скрытого комментария
	global $DBName, $prefix, $db, $now, $ip;
	$avtor = trim(str_replace("  "," ",filter($avtor, "", 1)));
	$mail = trim(str_replace("  "," ",filter($mail, "", 1)));
	if (!strpos($mail, "@")) {
		echo "<h2>Вы указали неправильный Email.</h2>Попробуйте еще раз
		<form method=POST action=\"/--email\" class=main_mail_form><table><tr><td align=right>Email: </td><td><input type=text name=mail class=main_mail_input size=10></td></tr><tr><td align=right>Имя: </td><td><input type=text name=avtor value='".$avtor."' class=main_mail_input size=10></td></tr><tr><td colspan=2 align=right><input type='submit' name='ok' value='Подписаться на рассылку'></td></tr></table></form>";
	} else {
		// проверка наличия такого email в БД 
		$numrows = $db->sql_numrows($db->sql_query("SELECT `cid` from ".$prefix."_pages_comments where `mail`='$mail' and `num`='0'"));
		if ($numrows == 0) {
			$db->sql_query("INSERT INTO ".$prefix."_pages_comments ( `cid` , `num` , `avtor` , `mail` , `text` , `ip` , `data`, `drevo`, `adres`, `tel`, `active` ) VALUES ('', '0', '$avtor', '$mail', '', '$ip', '$now', '', '', '', '1')");
			echo "<h2>Вы подписались на рассылку.</h2><h2> Спасибо!</h2>";
		} else {
			echo "<h2>Вы уже подписаны на рассылку.</h2>";
		}
	}
	echo "<br>Вы можете вернуться назад (нажав на клавиатуре клавишу &larr;BackSpace) или перейти <a href='/'>на Главную</a>.";
	exit;
} elseif ($name=="golos") { // Голосование (опросы)
	###################################################### ГОЛОСОВАНИЕ ГОЛОСОВАНИЕ
	global $soderganie, $tip, $DBName, $prefix, $db, $module_name, $ModuleName, $opros, $num, $admin, $ip, $now, $referer;
	$num = intval($num);
	$dat = $now;
	$n_opros = count($opros);
	if ($n_opros==1) $opros=intval($opros[0]);
	else {
	     for ($x=0; $x < $n_opros; $x++) {
	     $opros_list .= intval($opros[$x])." ";
	     }
	     $opros = trim($opros_list);
	}
	$sql = "SELECT data FROM ".$prefix."_golos WHERE ip='$ip' AND num='$num'";
	$resnum = $db->sql_query($sql);
	$numrows = $db->sql_numrows($resnum);
	if ($numrows > 0 or $num==0) {
		Header("Location: $referer");
		// если уже есть - на страницу переброс
	} else {
		$db->sql_query("INSERT INTO ".$prefix."_golos ( `gid` , `ip` , `golos`, `num`, `data`) VALUES ('', '$ip', '$opros', '$num', '$now')");
		// Получить из базы данных значения и прибавить к ним голоса
		$sql2 = "select text from ".$prefix."_mainpage where `tables`='pages' and type='3' and id='$num'"; // `name` != '$name' and 
		$result2 = $db->sql_query($sql2);
		$row2 = $db->sql_fetchrow($result2);
		$textX = trim($row2['text']);
		$lines = explode("\r\n",$textX);
		$txt = "";
		foreach ($lines as $line_id => $line) {
			$line = explode("|",$line);
			$col = $line[1];
			if ($col=="") $col=0;
			$line = $line[0];
			if ($line_id == $opros) $col = $col + 1;
			$txt .= $line."|".$col."\r\n";
		} // foreach закончился
		$db->sql_query("UPDATE ".$prefix."_mainpage SET text='$txt' WHERE `tables`='pages' and id='$num' and type='3'");
		Header("Location: $referer");
	}

} elseif ($name=="-search") { // Поиск по всем разделам
	###################################################### ПОИСК ПОИСК 
	global $soderganie, $tip, $DBName, $prefix, $db, $module_name, $ModuleName, $slovo, $design, $now, $ip, $papka;
	echo $slovo;
	$slov = str_replace("  "," ",str_replace(";"," ",str_replace("—"," ",str_replace("`"," ",str_replace("№ ","№",str_replace("№"," №",str_replace(",",", ",str_replace("ё","е",trim(strip_tags($slovo))))))))));

	if (strpos($slov,"@")) {
		echo "E-mail адреса не стоит искать на этом сайте, лучше использовать их для написания писем в почтовых программах или на почтовых сайтах, на которых вы зарегистрированы.";
		exit;
	}
	if (strpos(" ".$slov,"www.") or strpos($slov,".ru")) {
		echo "Адреса сайтов нужно писать не в поиске по сайту, а в адресной строке вашего браузера (той программы, через которую вы смотрите этот сайт)!";
		exit;
	}
	if (strpos(" ".$slov,"dir")) {
		echo "Рискну предположить, что ты — начинающий хакер. Если просто хочется взломать какой-то сайт — найди себе другой полигон. А если заинтересовал именно этот, пиши мне — 13i@list.ru";
		exit;
	}
	if (strpos(" ".$slov,"ИНН") or strpos(" ".$slov,"инн")) {
		echo "Не стоит искать ИНН на этом сайте.";
		exit;
	}
	if (strpos(" ".$slov,"голые") or strpos(" ".$slov,"порн") or strpos(" ".$slov,"эротика")) {
		echo "Не стоит искать голых, эротику и порно.";
		exit;
	}

	if ($slov == "мыло" and is_admin($admin)) {
		$sql5 = "SELECT num, avtor, mail from ".$prefix."_pages_comments where `mail`!='' order by num";
		$result5 = $db->sql_query($sql5);
		$numrows = $db->sql_numrows($result5);
		$nu = 0; // счетчик email для разбиения по 25 штук
		$nu2 = 0; // счетчик подписанных на рассылку
		$echo = ""; 
		$mails2 = array();
		while ($row5 = $db->sql_fetchrow($result5)) {
			$avtor = $row5['avtor'];
			
			$mails = trim(strip_tags($row5['mail']));
			if ( !in_array($mails,$mails2) and strpos($mails, "@") and strpos($mails, ".") ) {
				$nu++;
				$mails2[] = $mails;
				if ($row5['num'] == 0) { $nu2++; $echo .= "\"<b>".$avtor."</b>\" &lt;".$mails."&gt;, "; }
				else $echo .= "\"".$avtor."\" &lt;".$mails."&gt;, ";
				if ($nu == 25) { $echo .= "<hr>"; $nu = 0; }
			}
		}
		echo "<h1>Адреса Email из комментариев, всего ".count($mails2).", разбито по 25 штук.</h1>";
		if ($nu2 > 0) echo "<h2>Подписавшиеся на рассылку выделены жирным, всего: ".$nu2.".</h2>";
		echo "<p>Можно вставлять для отправки сразу после исправления имен, если они набраны неправильно.</p>
		<p><b>Внимание!</b> Рассылку лучше всего делать со специально зарегистрированного для этого email адреса. Не желательно писать в письме рассылки адрес сайта со ссылкой только на одну страницу. Ни в коем случае не делать рекламные рассылки! Это может быть расценено как спам, а сайт могут просто закрыть!</p><p><b>Разрешается делать:</b>
		<li>Обзорные рассылки — много ссылок на разные материалы
		<li>Извещение о начале какого-то конкурса или массового события
		<li>Поздравительные праздничные рассылки
		<hr>".$echo;
		exit;
	}
	
	$papka = intval($papka);
	if ($papka == 0) $papka = ""; // search_papka
	else $papka = " and cid = '".$papka."'";
	$slov = str_replace("  "," ",trim($slov));
	
	// Определение названий всех разделов
	$sql3 = "select `name`, `title` from `".$prefix."_mainpage` where `tables`='pages' and `type`='2'";
	$result3 = $db->sql_query($sql3);
	while ($row3 = $db->sql_fetchrow($result3)) {
		$m_name = $row3['name'];
		$m_title[$m_name] = $row3['title'];
	}
	
	$soderganie .= "<div class='main_search_line'><form method=POST action=\"--search\" class=main_search_form><input type='search' placeholder='Поиск по сайту' name=slovo class='main_search_input' value=\"".$slov."\"><input type='submit' name='ok' value='Найти' class='main_search_button'> <a href='/'>Вернуться на Главную страницу</a></form></div>";
	
	// Заголовок
	$pagetitle = $slovo." — Поиск — ";
	$slovo = zamena_predlog($slov);
	$slovo = explode(" ",$slovo);
	for ( $i=0; $i < count($slovo); $i++ ) { 
	$sl = strlen($slovo[$i]);
		if ($sl > 4) $slovo[$i] = obrez($slovo[$i]);
	}
	$slovo = trim(implode("%",$slovo));

	$numrows = $db->sql_numrows( $db->sql_query("SELECT `pid` FROM ".$prefix."_pages where `tables`='pages'".$papka." and active='1' and (copy='0' or copy=pid) and (main_text LIKE '%".$slovo."%' or title LIKE '%".$slovo."%' or open_text LIKE '%".$slovo."%' or description LIKE '%".$slovo."%') order by date desc") );
	$nu = "";
	if ($numrows==0 or strlen($slovo) == 0) {
		$numrows = "ничего не найдено...";
		$nu = explode(" ",$slov);
		if ($nu>1) {
			$nu = "<br><br><h3>Данное сочетание не обнаружено. Попробуйте поискать по другим словам.<br>В слове должно быть как минимум 3 буквы!</h3>"; 
			$numrows1 = 0;
			$numrows2 = 0;
		}
	}
	
	$soderganie .= "&nbsp; Найдено: <b>$numrows</b> <br>".$nu."<div class=main_search>";
		if ($numrows!=0) {
			// Список всех папок (массив)
			$c_name = array();
			$sql = "SELECT cid,title FROM ".$prefix."_pages_categories where `tables`='pages'";
			$result = $db->sql_query($sql) or die('Не удалось собрать список всех папок');
			while ($row = $db->sql_fetchrow($result)) {
				$x_cid = $row['cid'];
				$c_name[$x_cid] = strip_tags($row['title']);
			}

			$pids = array(); // Список похожих
			$res2 = $db->sql_query("SELECT `pid`,`module`,`cid`,`title` FROM ".$prefix."_pages where `tables`='pages'".$papka." and active='1' and (copy='0' or copy=pid) and title LIKE '%".$slovo."%'");
			$numrows1 = $db->sql_numrows($res2);
			if ($numrows1 == 0) $nu = "не найдены"; else $nu = $numrows1;
			$soderganie .= "<p><b>Совпадения в названии страницы: ".$nu."</b><ol>";
			$admintip = "base_pages";
			while ($row = $db->sql_fetchrow($res2)) {
				$p_pid = $row['pid'];
				$p_title = $row['title'];
				$p_module = $row['module'];
				$p_cid = $row['cid'];
				if ($p_cid != 0) $cat = "<a class='search_cat_link' href='/-".$p_module."_cat_".$p_cid."'>".$c_name[$p_cid]."</a> ".$strelka." "; else $cat = "";
				$soderganie .= "<li><a href='/-".$p_module."'>".$m_title[$p_module]."</a> ".$strelka." ".$cat."<a class='search_page_link' href=-".$p_module."_page_".$p_pid.">".$p_title.".</a>";
	
				if (is_admin($admin)) $soderganie .= "&nbsp; <a href=sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid." title=\"Изменить страницу в Редакторе\"><img src=images/sys/edit_1.png title=\"Изменить страницу в Редакторе\"></a><a href=sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid."&red=1 title=\"Изменить страницу (быстрый HTML режим)\"><img src=images/sys/edit_0.png title=\"Изменить страницу (быстрый HTML режим)\"></a>";
				// Заносим в список
				$pids[] = $p_pid;
			}

			$res3 = $db->sql_query("SELECT `pid`,`module`,`cid`,`title` FROM ".$prefix."_pages where `tables`='pages'".$papka." and active='1' and (copy='0' or copy=pid) and (main_text LIKE '%".$slovo."%' or open_text LIKE '%".$slovo."%')");
			$numrows2 = $db->sql_numrows($res3);
			if ($numrows2 == 0) $nu = "не найдены"; else $nu = $numrows2;
			$soderganie .= "</ol><hr noshade=noshade><p><b>Совпадения в содержании (или описании) страницы: $nu</b><ol>";
			while ($row = $db->sql_fetchrow($res3)) {
				$p_pid = $row['pid'];
				$p_title = $row['title'];
				$p_module = $row['module'];
				$p_cid = $row['cid'];
				if ($p_cid != 0) $cat = "<a class='search_cat_link' href=-".$p_module."_cat_".$p_cid.">".$c_name[$p_cid]."</a> $strelka "; else $cat = "";
					if (!in_array($p_pid,$pids)) {
						$soderganie .= "<li><a href=-".$p_module.">".$m_title[$p_module]."</a> $strelka ".$cat."<a class='search_page_link' href=-".$p_module."_page_".$p_pid.">$p_title.</a>";
						if (is_admin($admin)) $soderganie .= "&nbsp; <a href=sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid." title=\"Изменить страницу в Редакторе\"><img src=images/sys/edit_1.png title=\"Изменить страницу в Редакторе\"></a><a href=sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid."&red=1 title=\"Изменить страницу (быстрый HTML режим)\"><img src=images/sys/edit_0.png title=\"Изменить страницу (быстрый HTML режим)\"></a>";
					}
			}
			$soderganie .= "</ol><p>Одинаковые с совпадениями в названии результаты не показываются.<hr noshade=noshade>";
		}
		if (is_admin($admin)) $soderganie .= "<h3>Редактирование страниц и эта надпись видны только вам — Администратору.</h3>";
	$soderganie .= "</div>";
	$block = $soderganie;

	// Занесение слова в БД
	if ($db->sql_numrows($db->sql_query("SELECT `id` FROM ".$prefix."_search where `slovo`='$slov' and ip='$ip'")) == 0 and trim($slov) != '' and !is_admin($admin)) $db->sql_query("INSERT INTO `".$prefix."_search` (`id`,`ip`,`slovo`,`data`,`pages`) VALUES (NULL, '$ip', '$slov', '$now', '$numrows1 | $numrows2');");

	// Стили (основной)
	$sql = "select id from ".$prefix."_mainpage where `tables`='pages' and `type`='1' and `name`='index'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$style_id = trim($row['id']);
	$stil = "/css_$style_id";

} elseif ($name=="-slovo") { // Поиск по ключ. словам
	###################################################### ТЕГИ
	global $soderganie, $tip, $DBName, $prefix, $db, $slovo, $design;
	$slov = trim(strip_tags(urldecode(str_replace( "-","%", $slovo))));
	$slov = str_replace("  "," ",trim($slov));
	$slovo = str_replace(" ","%",$slov);
	
	// Определение названий всех разделов
	$sql3 = "select `name`, `title` from `".$prefix."_mainpage` where `tables`='pages' and `type`='2'";
	$result3 = $db->sql_query($sql3);
	while ($row3 = $db->sql_fetchrow($result3)) {
	$m_name = $row3['name'];
	$m_title[$m_name] = $row3['title'];
	}
	
	$res1 = $db->sql_query("SELECT `pid` FROM ".$prefix."_pages where `tables`='pages' and active='1' and (copy='0' or copy=pid) and (search LIKE '% ".$slovo." %') order by date desc");
	$numrows = $db->sql_numrows($res1);
	if ($numrows==0) {
	$numrows = "ничего не найдено...";
	$nu = explode(" ",$slov);
	if ($nu>1) $numrows .= "<br>Данный тег не обнаружен.";
	}
	$soderganie .= "<center><div class=main_search_line align=left><table border=0 cellspacing=1 cellpadding=0><tr><td><b>Вы выбрали тег</b> (ключевое слово): <b>$slov.</b> Найдено: $numrows</td></tr></table></div><br><div class=main_search align=left><ol>
	";
		if ($numrows!=0) {
		$pids = array(); // Список похожих
		$res2 = $db->sql_query("SELECT `pid`,`module`,`cid`,`title` FROM ".$prefix."_pages where `tables`='pages' and active='1' and (copy='0' or copy=pid) and search LIKE '% ".$slovo." %'");
		$admintip = "base_pages";
		while ($row = $db->sql_fetchrow($res2)) {
			$p_pid = $row['pid'];
			$p_title = $row['title'];
			$p_module = $row['module'];
			$p_cid = $row['cid'];
			$soderganie .= "<li><a href=-".$p_module.">".$m_title[$p_module]."</a> $strelka <a href=-".$p_module."_page_".$p_pid.">$p_title</a>";
		
			if (is_admin($admin)) $soderganie .= "&nbsp; <a href=sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid." title=\"Изменить страницу в Редакторе\"><img src=images/sys/edit_1.png title=\"Изменить страницу в Редакторе\"></a>&nbsp; <a href=sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid."&red=1 title=\"Изменить страницу (быстрый HTML режим)\"><img src=images/sys/edit_0.png title=\"Изменить страницу (быстрый HTML режим)\"></a>";
			// Заносим в список
			$pids[] = $p_pid;
		}
	$soderganie .= "</ol><hr noshade=noshade>";
		}
		if (is_admin($admin)) $soderganie .= "<h2>Редактирование страниц доступно только вам — администратору.</h2>";
	$soderganie .= "</div></center>";
	$block = $soderganie;
	
	// Заголовок
	$pagetitle = $slovo." — Поиск — ";
	
	// Стили (основной)
	$sql = "select id from ".$prefix."_mainpage where `tables`='pages' and `type`='1' and `name`='index'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$style_id = trim($row['id']);
	$stil = "/css_$style_id";

} else { // Сборка дизайна с разделом и Блоки
	###################################################### БЛОКИ
	$block = ""; // Определение раздела
	global $title_mainpage2, $text_mainpage2, $useit_mainpage2, $pid;

	// Настройки раздела по-умолчанию
	$designpages = 0; // т.е. дизайн для страниц = дизайну разделов

	if (!isset($title_mainpage2[$name])) $title_mainpage2[$name] = "";

	if ($title_mainpage2[$name] == "") {
		$main_title = ""; // ИЗМЕНА на mainfile
		$main_file = "";
		$main_options = "";
	} else {
		$main_title = $title_mainpage2[$name];
		$main_file = array();
		if (trim($text_mainpage2[$name])!="") {
			$main_file = explode("|",  $text_mainpage2[$name]);
			$main_options = $main_file[1];
			$main_file = $main_file[0];
		} else {
			$main_options = "";
			$main_file = "";
		}
	}
	// Содержание главной страницы раздела
	if (isset ($useit_mainpage2[$name]) ) $soda = $useit_mainpage2[$name]; else $soda = "";

	parse_str($main_options); // Включили все настройки раздела

	// Выбор дизайна: для страниц или раздела
	if ($designpages != 0 and $pid != 0) $design = $designpages;

	// Разберемся со стилями id, type, name, opis, sort, pages, parent
	$style_type = array();
	$style_name = array();
	$style_pages = array();
	$sql7 = "SELECT id, type, name, pages from ".$prefix."_spiski";
	$result7 = $db->sql_query($sql7);
	while ($row7 = $db->sql_fetchrow($result7)) {
		$style_id = $row7['id'];
		$style_type[$style_id] = $row7['type'];
		$style_name[$style_id] = $row7['name'];
		$style_pages[$style_id] = $row7['pages'];
	}

	// Определение дизайна
	if (isset($design)) {
		$sql4 = "select `text`, `useit` from ".$prefix."_mainpage where `tables`='pages' and `id`='$design' and type='0'";
		$result4 = $db->sql_query($sql4);
		$numrows = $db->sql_numrows($result4);
	} else $numrows = 0;
	if ($numrows > 0) {
		$row4 = $db->sql_fetchrow($result4);
		$block = $row4['text'];
		$style_useit = trim($row4['useit']);

		// ОТКРЫТО Определение использованных стилей в дизайне
		$useit = explode(" ", $style_useit);
		$n = count($useit);
		$stil = "";
			 for ($x=0; $x < $n; $x++) {
				 $stil .= " $useit[$x]";
				 $sql = "select title from ".$prefix."_mainpage where `tables`='pages' and `id`='$useit[$x]'";
				 $result = $db->sql_query($sql);
				 $row = $db->sql_fetchrow($result);
				 $title = trim($row['title']);
			 }
		$stil = str_replace(" ","-",trim($stil));
		$stil = "/css_$stil";
	// ЗАКРЫТО Определение использованных стилей в дизайне

	} else {
		//global $nocash;
		//$nocash = true;
		die("Ошибка: «Адрес раздела» (".$name.") введен неправильно. Перейдите на <a href=/>Главную страницу</a>.");
	}

	// Получаем список всех категорий
	global $cid_title, $cid_module;

	// Определяем Главный модуль
	if ($name == "index") {
		// Смотрим чему равно значение Главной страницы
		global $useit_mainpage2; // ЗАМЕНА mainpage2
		$name13 = $useit_mainpage2[$name];

		// Ставим содержание главной страницы
		$main_file = $name13;
		$main_options = "no";
	}

	global $soderganie, $soderganie2, $options, $ModuleName, $tip, $DBName, $page_cat, $http_siteur, $cid, $pid, $pic_ramka;
	$options = $main_options;
	$ModuleName = $main_title;
	$DBName = $name; // важно не менять!
	$tip = $main_file;

	if (file_exists("page/main.php") and $main_options != "no") {
		require_once("page/main.php");

		$soda = explode("[следующий]",$soda);
		// $soda[0] - для всех
		// $soda[1] - только для главной страницы
		// $soda[2] - только для папок
		// $soda[3] - только для страниц

		$soda_col = count($soda);
		if (strpos(" ".$soda[0],"[содержание]")) $soderganie = str_replace("[содержание]", $soderganie, $soda[0]);
		else {
			$soderganie = str_replace("[название]", "<div class=cat_title><font class=cat_categorii_link>".$ModuleName."</font></div><div class=polosa></div>", $soda[0]);
			$soderganie = str_replace("[страницы]", $soderganie2, $soderganie);
		}

		if ($cid=="" and $pid=="" and $soda_col > 1) {
			if (strpos(" ".$soda[1],"[содержание]")) $soderganie = str_replace("[содержание]", $soderganie, $soda[1]);
			else {
				$soderganie = str_replace("[название]", "<div class=cat_title><A class=cat_categorii_link href=-".$DBName.">".$ModuleName."</a></div><div class=polosa></div>", $soda[1]);
				$soderganie = str_replace("[страницы]", $soderganie2, $soderganie);
			}
		}

		//if ($cid=="" and $pid=="" and $soda_col > 1) $soderganie = str_replace("[содержание]", $soderganie, $soda[1]); 
		// Содержание главной страницы раздела

		if ($cid!="" and $pid=="" and $soda_col > 2) $soderganie = str_replace("[содержание]", $soderganie, $soda[2]); 
		// Содержание главной страницы раздела

		if ($cid=="" and $pid!="" and $soda_col > 3) $soderganie = str_replace("[содержание]", $soderganie, $soda[3]); 
		// Содержание главной страницы раздела

		$block = str_replace("[содержание]", $soderganie, $block); 
		// Тело раздела ставится в дизайн

		// Нумерация страниц ставится в дизайн
		if (strpos(" ".$block, "[нумерация]")) {
			global $topic_links_global;
			$block = str_replace("[нумерация]", $topic_links_global, $block);
		}

	// Ставим содержание модуля
	} else {
		$block = str_replace("[содержание]", $main_file, $block);
	}
/////////////////////////////////////////////////////////////////////////////////////////

	# НАЧАЛО Определение блоков и их заполнение
	$sql2 = "select id,name,title,text,useit,shablon from ".$prefix."_mainpage where type='3'"; 
	// `name` != '$name' and 
	// ИЗМЕНА: вынесено за пределы массива и вместо * замена
	$result2 = $db->sql_query($sql2);
	$nameYYY = array();
	$titleYYY = array();
	$textYYY = array();
	$useitYYY = array();
	$shablonYYY = array();
	while ($row2 = $db->sql_fetchrow($result2)) {
		$idYYY = $row2['id'];
		$nameYYY[$idYYY] = $row2['name'];
		$titleYYY[$idYYY] = trim($row2['title']);
		$textYYY[$idYYY] = trim($row2['text']);
		$useitYYY[$idYYY] = trim($row2['useit']);
		$shablonYYY[$idYYY] = trim($row2['shablon']);
	}
	// Вставить оптимизацию блоков
for ($iii=0; $iii < 2; $iii++) {
	foreach( $nameYYY as $idX => $nameX ) {
		$titleX = trim($titleYYY[$idX]);
		// поиск блока в тексте
		if ( $block == str_replace("[".$titleX."]", "", $block) ) continue; // Переход к следующему!
		
		$textX = trim($textYYY[$idX]);
		$useitX = trim($useitYYY[$idX]);
		$shablonX = trim($shablonYYY[$idX]);

	// обнулили все опции блоков от греха подальше
	$media=$folder=$datashow=$design=$open_all=$catshow=$main=$daleeshow=$openshow=$number=$add=$size=$papki_numbers=$zagolovokin=$menu=$notitlelink=$noli=$html=$show_title=$random=$showlinks=$open_new_window=0;
	$titleshow=$opros_type=$limkol=$pageshow=$only_question=$opros_result=$foto_gallery_type=1;
	$addtitle="Добавить статью";
	$dal="Далее...";
	$first = "src=";
	$second = ">";
	$third = " ";
	$col_bukv = 50;
	$img_width = 60;
	$img_height = 60;
	$shablon=$class=$alternative_title_link=$cid_open=$no_show_in_razdel=$watermark=$show_in_papka="";
	$sort = "date desc";
	$papka_sort = "sort, title";
	$razdel_open_name = "Открыть раздел"; //
	$razdel_open2_name = "Открыть раздел";
	$calendar = ""; // Календарь - перенаправление на дату из списка.
	$show_in_razdel = "все";

	// Для базы данных
	$base = ""; // Указываем название таблицы БД
	$first = ""; // первая колонка
	$second = ""; // вторая колонка
	$text = ""; // текст самой первой ячейки (для блока БД количество по 2 колонкам)
	$direct = "vert"; // направление, gor - горизонт., vert - вертикальное
	$all = 0; // Указывать сколько всего элементов, по умолчанию 0 - не указывать, 1 - указывать.
	$col = ""; // какие поля будут использоваться для вывода информации

	if (trim($useitX)!="") {
		$useit = explode("|",$useitX); 
		$useitX = $useit[0];
		$useitY = $useit[1]; // опции
		$alternative_title_link = "/-".$useitX."";
		parse_str($useitY);
	} else {
		$useitX = "";
		$useitY = "";
	}
	
	if ($random == 1) $sort = "RAND()";
	########################################################################################

	if ($alternative_title_link == "" and $useitX != "все") $alternative_title_link = "/-".$useitX."";
	if ($cid_open=="все" or $cid_open=="") {} else {$alternative_title_link = "/-".$useitX."_cat_".$cid_open;}

	// Определение дизайна блоков #############
	$design_open=""; $design_close="";

	$block_title = $titleX;
	$block_title2 = "";
	/////////////////////////////////////
	if ($design != 0) {
		$row7 = $db->sql_fetchrow($db->sql_query("select `text`, `useit` from ".$prefix."_mainpage where `id`='$design' and type='0'"));
		$design = explode("[содержание]", $row7['text']);
		$stile = $row7['useit'];
		// Добавляем стиль дизайна
		if (trim($stile)!="" and $stile!=0) $stil .= "-".str_replace(" ","-",trim($stile));
		/////////////////////////////////////
		if ($nameX == 0) {
		if ($useitX != "все" and $nameX != 2 and $useitX != "" and $razdel_open_name != "" and $razdel_open_name != "no") $block_title2 .= "<font class=open_all_small> &nbsp; &#124; &nbsp; </font> <a href=-".$useitX." title=\"".$razdel_open_name."\" class=open_all_small><u>".$razdel_open_name."</u></a>";
		}
	/////////////////////////////////////
		$design_open = "<div class=".$shablonX.">".$design[0]; 
		if ($titleshow != 0 and $titleshow != 3) {
			if (($nameX==0 or $nameX==1 or $nameX==4 or $nameX==6 or $nameX==8 or $nameX==9) and $notitlelink==0) {
				$design_open .= "<h3 class=\"h3_block_title class_".$class."\"><a href=".$alternative_title_link." title=\"".$block_title."\" class=\"h3_block_title class_".$class."\">".$block_title."</a>".$block_title2."</h3><div class=polosa></div>"; 
			} else {
				$design_open .= "<h3 class=\"h3_block_title class_".$class."\">".$block_title."</h3><div class=polosa></div>";
			}
		} 
		if (!isset($design[1])) $design[1] = "";
		$design_close = $design[1]."</div>";
	/////////////////////////////////////
	} else {
		$design_open = "<div class=".$shablonX.">"; 
		if ($titleshow != 0 and $titleshow != 3) {
			if (($nameX==0 or $nameX==1 or $nameX==4 or $nameX==6 or $nameX==8 or $nameX==9) and $notitlelink==0) {
				$design_open .= "<h3 class=\"".$shablonX." h3_block_title class_".$class."\"><a href=".$alternative_title_link." title=\"".$block_title."\" class=\"h3_block_title class_".$class."\">".$block_title."</a>".$block_title2."</h3><div class=polosa></div>";
			} else {
				$design_open .= "<h3 class=\"".$shablonX." h3_block_title class_".$class."\">".$block_title."</h3><div class=polosa></div>";
			}
		}
	$design_close = "</div>";
	}

	if ($html == 1) { $design_close = ""; $design_open = ""; $block_title = ""; $block_title2 = ""; }
	if ($blocks == 1) { $design_close .= "</div>"; $design_open = "<div class=show_block><div class=show_block_title>".$titleX." 
	<a href='sys.php?op=mainpage&id=".$idX."&red=1' title='Редактировать в HTML'><img align=right class='icon2 i34' src='/images/1.gif'></a><a href='sys.php?op=mainpage&id=".$idX."&nastroi=1' title='Настроить блок'><img align=right class='icon2 i38' src='/images/1.gif'></a>
	</div>".$design_open; }

	###########################################
	// Определяем наличие шаблонов
	if (trim($shablon) != "") {
		// Доступ к шаблону
		if (intval($shablon) == trim($shablon)) {
			global $text_shablon;
			if ($shablon == 0) $shablon = "";
			else $shablon = $text_shablon[$shablon];
		}
	// Выясняем наличие элементов шаблона
	}
	###########################################

	// Работа с разными типами блоков
	if (($show_in_razdel != $name and $show_in_razdel != "все") or $no_show_in_razdel == $name or ($show_in_papka != $cid and $show_in_papka != "")) {
		$block = str_replace("[$titleX]", "", $block);
		$nameX = "-1";
	}

switch ($nameX) { ###############################

case "0": # Блок модуля
	if ($open_new_window == 1) $blank = " target='_blank'"; 
	else $blank = "";

$text_old = $textX;
$textX = ""; // В эту переменную входит содержание блока
if ($media==2) { // удалить
	##################################################################################################
	// ФЛЕШКА: в папке должно лежать три файла: файл флешки, текста и картинки 
	$offset = 0;
	$max2 = 6;
	if (is_dir($folder)) {
		$dir = dir($folder);
		$list = array();
		while($func=$dir->read()) {
			if(strpos($func,".jpg")) $list[] = str_replace(".jpg","",$func);
		}
		closedir($dir->handle);
		sort($list);
		$nu=0; // счетчик кол-ва флешек для перехода к слудующей строке в таблице
		$textX .= "<center><table class=flash_table><tr valign=top>";
		for ($i=$offset; $i < $max2; $i++) { // выгружаем циклом $lim флешек
			$pic_path = $dir->path."/$list[$i].jpg";
			$txt_path = $dir->path."/$list[$i].txt";
			$link = "/-".$useitX."_page_".$i."";
			$OpenData=FOpen($txt_path,"r"); // читаем описания флешек из txt файлов
			$txt=@FRead($OpenData,FileSize($txt_path)); 
			$textX .= "<td align=center>
			<a href=".$link.$blank."><img src=$pic_path title=\"".$txt."\" alt=\"".$txt."\"><div class=flash_text>".$txt."</div></a></td>";
			$nu++;
			if ($nu==3) { $nu=0; $textX .= "</tr><tr valign=top>"; }
		}
		$textX .= "</tr></table></center>";
	}
	##################################################################################################
} else {
	$and = "";
	if ($main==1) $and = " and mainpage='1'";
	if ($main==2) $and = " and mainpage!='1'";
	if ($text_old != "") $and .= " and ".stripcslashes($text_old)."";
	if ($useitX=="все" or $useitX=="") { // Показывать ВСЕ разделы или выбранный
		$and2 = ""; 
		$pages="pages";
	} else $and2 = " and module='$useitX'"; 
	if ($cid_open=="все" or $cid_open=="") { // Показывать ВСЕ папки или выбранную
		$and3 = "";
		$cid_open2 = "";
	} else {
		$and2 = " and cid='$cid_open'";
		$cid_open2 = "_cat_".$cid_open;
	}
	
	if (isset($size)) $limit = " limit $number,$size"; // $lim2 = size - убрано
	
	if ($shablon != "") {
	$sel = "*"; 
		// Ищем списки (доп. поля), относящиеся к нашим страницам по разделу
		$s_names = array();
		$s_opts = array();
		// Определим № раздела
		global $id_mainpage2; // ЗАМЕНА mainpage2 №2
		$r_id = $id_mainpage2[$useitX];
		
		$result5 = $db->sql_query("SELECT id, name, text FROM ".$prefix."_mainpage WHERE `tables`='pages' and (useit = '$r_id' or useit = '0') and type='4'");
		while ($row5 = $db->sql_fetchrow($result5)) {
			$s_id = $row5['id'];
			$n = $row5['name'];
			$s_names[$s_id] = $n;
			// Найдем значение всех полей для данных страниц
			$result6 = $db->sql_query("SELECT name, pages FROM ".$prefix."_spiski WHERE type='$n'");
			while ($row6 = $db->sql_fetchrow($result6)) {
			$n1 = $row6['name'];
			$n2 = explode(" ", str_replace("  ", " ", trim($row6['pages'])));
				foreach ($n2 as $n2_1 => $n2_2) {
					$s_opts[$n][$n2_2] = $n1;
				}
			}
		}

	} else $sel = "pid, module, cid, title, open_text, main_text, `date`"; 
	
	$sql = "SELECT ".$sel." from ".$prefix."_pages where `tables`='pages' and active='1'".$and.$and2.$and3." order by ".$sort."".$limit."";
	$result = $db->sql_query($sql);


	if ($shablon == "") {
		if ($openshow==0) $textX .= "<ul id=block_li_title class=\"block_li_title\">"; 
	}
	
	$numlock = 0; // Счетчик кол-ва выведенных строк у блоков
	while ($row = $db->sql_fetchrow($result)) {
	
		$numlock++;
		$p_id = $row['pid'];
		$module = $row['module'];
		$p_cid = $row['cid'];
		$title = filter($row['title'], "", 0);
		$titl = str_replace("\"","",$title);
		$open_text = filter(strip_tags(str_replace("<img ","<img title='$titl' ",$row['open_text']), '<b><br><i><img><table><tr><td><a><strong><em><embed><param><object><p><iframe><div>'), "", 0);
		$main_text = filter(str_replace("<img ","<img title='$titl' ",$row['main_text']), "", 0);
		
		// Вырезание авто-ссылок
	  $open_text = preg_replace('/ссылка-.*-ссылка/Uis', '', $open_text);
	  $open_text = str_replace('-ссылка-', '', $open_text);
	  $open_text = str_replace('<hr class="editor_cut">', '', $open_text);
	  $main_text = str_replace('<!--more-->', '<hr>', $main_text);
  
		if ($shablon != "") {
			$active = $row['active'];
			switch ($active) {
				case "1": $active = "Открытая информация";	break;
				case "2": $active = "Информация ожидает проверки";	break;
				case "0": $active = "Доступ к странице ограничен";	break;
			}
			$counterX = $row['counter'];
			$golos = $row['golos'];
			$comm = $row['comm'];
			$foto_adres = $row['foto'];
			$foto = "<img src='".$foto_adres."'>";
			$search = $row['search'];
			$price = $row['price']." руб."; // или другая валюта!
			$rss = $row['rss'];
			switch ($rss) {
				case "1": $rss = "<a name=rss title='Информация доступна через RSS-подписку' class=green_link>RSS</a>"; break;
				case "0": $rss = "<a name=rss title='Информация не доступна через RSS-подписку' class=red_link>RSS</a>"; break;
			}
		}
		////////////////////////////////////////////////////////////////////////////////////!!!!!!!!!!!!!!!!!!!!!!!!!
		if (trim($class) != "") {
			$class = trim($class);
			// Смотрим класс стиля, выбранный из списка для блока показываемой страницы.
			$sql7 = "SELECT name from ".$prefix."_spiski where type='".$class."' and pages like '% ".$p_id." %'";
			$result7 = $db->sql_query($sql7);
			$row7 = $db->sql_fetchrow($result7); 
			$class = " ".$row7['name'];
		} else $class = " "; //no_class

		if ($datashow == 1 or $shablon != "") { // Если показывать дату // доработать - функция из mainfile
			$dat = explode(" ",$row['date']);
			$dat = explode("-",$dat[0]);
			$p_date = intval($dat[2])." ".findMonthName($dat[1])." ".$dat[0];
			$p_date_1 = $dat[2]." ".$dat[1]." ".$dat[0];
			$date_now = date("d m Y");
			$date_now2 = date("d m Y",time()-86400);
			$date_now3 = date("d m Y",time()-172800);
			if ($date_now == $p_date_1) $p_date = "Сегодня";
			if ($date_now2 == $p_date_1) $p_date = "Вчера";
			if ($date_now3 == $p_date_1) $p_date = "Позавчера";
			$data = "".$p_date." $strelka ";
		} else $data = "";

		if ($shablon != "") {
		  $tr = array(
			"[№]"=>$p_id,
			"[модуль]"=>$module,
			"[№ папки]"=>$p_cid,
			"[название]"=>$title,
			"[ссылка]"=>"<a href=-".$module."_page_".$p_id.$blank.">".$title."</a>",
			"[предисловие]"=>$open_text,
			"[содержание]"=>$main_text,
			"[дата]"=>$data,
			"[число посещения]"=>$counterX,
			"[открытость]"=>$active,
			"[число голосование]"=>$golos,
			"[число комментарии]"=>$comm,
			"[адрес фото]"=>$foto_adres,
			"[фото]"=>$foto,
			"[теги]"=>$search,
			"[цена]"=>$price,
			"[rss доступность]"=>$rss
		  );
			$shablonX = $shablon;
			foreach ($s_names as $id2 => $nam2) {
			// Найдем значение каждого поля для данной страницы
					if (!isset($s_opts[$nam2][$p_id])) $s_opts[$nam2][$p_id] = "";
					$nam3 = $s_opts[$nam2][$p_id];
					$shablonX = str_replace("[$nam2]", $nam3, $shablonX);
			}
			$textX .= strtr($shablonX,$tr);
			
		///////////////////////////////////////////////////////////////////////////////////
		} else { // если без шаблона
			 // Если показывать название папки
			if (($catshow == 1 or $shablon != "") and $p_cid != 0) $cat = "<span class=\"block_li_cat ".$class."\">".$cid_title[$p_cid]."</span> $strelka "; else $cat = "";
			global $class;
					if ($openshow > 0) { // Если показывать предописание
						$textX .= "<div id=venzel class=\"venzel ".$class."\"></div>";
						if ($numlock > 1) $textX .= "<br>";
						if (trim($main_text)!="" and $daleeshow == 1) {
							if ($openshow == 1) {
								$dalee = " <div id=dalee class=\"dalee ".$class."\"><a href=-".$module."_page_".$p_id.$blank.">".$dal."</a></div>";
							} else {
								$dalee = " <div id=dalee class=\"dalee ".$class."\">$dal</div>";
							}
						} else $dalee = "";
						$open_text = "<div id=block_open_text class=\"block_open_text ".$class."\">".$open_text.$dalee."</div>";
						
						if ($pic_ramka == 1) { // настройка используется для рамок изображений на сайте Самарских Родителей)
							$open_text = str_replace("<img","<div id=for_pic class=\"for_pic".$class."\"><img", str_replace("<IMG","<img",$open_text));
						}
							if ($zagolovokin == 0) {
								$zagolovok = "<div class=\"block_title ".$class."\"><span class=\"block_li_data ".$class."\">".$data."</span>".$cat."<a class=\"block_title ".$class."\" href=-".$module."_page_".$p_id.$blank.">".$title."</a></div>";
								$open_text = str_replace("[заголовок]", "", $open_text); 
								//$zagolovok = str_replace("[заголовок]", "", $zagolovok); 
							} else {
								$zagolovok = "";
								if ($openshow == 1) { 
									$open_text = "<div class=a_block_title><a class=\"a_block_title ".$class."\" href=-".$module."_page_".$p_id.$blank.">".str_replace("[заголовок]","</div><div class=\"block_title ".$class."\"><span class=\"block_li_data ".$class."\">".$data."</span>".$cat."".$title."</div>",$open_text)."</a>"; // Вставляем Заголовок в блок!
								} else {
									$open_text = "<div class=a_block_title>".str_replace("[заголовок]","</div><div class=\"block_title ".$class."\">".$data."".$cat."".$title."</div>",$open_text).""; // Вставляем Заголовок в блок!
								}
							}
						$textX .= "".$zagolovok."".$open_text."";
					} else { // Если НЕ показывать предописание
						$textX .= "<li class=\"block_li_title ".$class."\"><span class=\"block_li_data ".$class."\">".$data."</span>".$cat."<a href=-".$module."_page_".$p_id.$blank.">".$title."</a></li>";
					}
		}			
	}
	if ($shablon == "") {
		if ($openshow==0) $textX .= "</ul>";
	}	

	if ($openshow==0) $textX .= "";

	if ($add==1) $textX .= "<div id=add class=\"add".$class."\"><a href=-".$module."&add=true".$blank." id=add_link class=\"add_link".$class."\">$addtitle</a></div>";
	
	if ($open_all==1 and $razdel_open2_name != "" and $razdel_open2_name != "no") $textX .= "<br><div id=open_all class=\"open_all".$class."\"><a href=-".$module.$cid_open2.$blank." id='open_all_link' class=\"open_all_link".$class."\">".$razdel_open2_name."</a></div><br>";
}
$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "1": # Блок комментариев модуля
	$pages="pages";
	// Получим список № страниц
	if ($useitX=="") $and = ""; else $and = " where `tables`='pages' and module='$useitX'"; 
	// Показывать ВСЕ разделы или выбранный

	if ($only_question==0) $and2 = "";
	elseif ($only_question==1) $and2 = " and drevo='0'"; 
	elseif ($only_question==2) $and2 = " and drevo!='0'"; 
	if (isset($size)) $limit = " limit $number,$size";
	if ($shablon != "") $sel = "*"; else $sel = "cid, num, avtor, text, data"; 
	
	$sql = "SELECT pid, title, module from ".$prefix."_".$pages.$and." order by date desc";

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
	$sql = "SELECT ".$sel." from ".$prefix."_".$pages."_comments where `tables`='pages'".$line_id." and active='1'".$and2." order by ".$sort."".$limit."";

	$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$cid = $row['cid'];
			$num = $row['num'];
			$avtor = $row['avtor'];
			$text = strip_tags(substr($row['text'], 0, $col_bukv), '<b><i><img><a><strong><em>');
			if (strlen($row['text']) > $col_bukv) $text .= "...";
			$data = $row['data'];
			$data = date2normal_view(str_replace(".","-",$data), 2, 1);
			
	// Если показывать название страницы
	if ($pageshow == 1) $cat = "".$titles[$num]." $strelka "; else $cat = "";
			
			$textX .= "<li class=\"block_li_title ".$class."\"><span class=\"block_li_data ".$class."\">".$data."</span> ".$strelka." <span class=\"block_li_cat ".$class."\">".$cat."</span> <a class=\"block_comment_text ".$class."\" href=-".$modules[$num]."_page_".$num."#comm_".$cid.">".$text."</a></li>";
		}
		$textX = "<ul id=block_li_title class=\"block_li_title\">".$textX."</ul>";
	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "2": # Блок текста 
	$block=str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "3": # Блок ротатор рекламы
	$lines = trim($textX);
	$lines = explode("|", $lines); // ЗАМЕНИТЬ!
	$itogo = count($lines)-1;
	srand((double) microtime()*1000000);
	$i=rand(0,$itogo); // выбираем случайное число (0...MAX)
	$textX = $lines[$i];
	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////	
case "4": # Блок папок раздела 
	if ($noli == 0) $textX = "<ul id=block_ul_title_$useitX class=\"block_ul_title\">"; 
	// В эту переменную входит содержание блока

	global $text_mainpage2; // ЗАМЕНА mainpage2 №3
	$textXX = explode("|", $text_mainpage2[$useitX] );
	$pages = $textXX[0]; // получили название файла модуля, например, pages
	// Определяем отношение страниц к папкам
	if ($papki_numbers==1) {
		$num = array();
		$sql = "SELECT pid, cid from ".$prefix."_".$pages." where `tables`='pages' and module='$useitX' and active='1'";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$cid_id = $row['cid'];		//
			if (!isset($num[$cid_id])) $num[$cid_id] = 1; else $num[$cid_id]++;
		}
	}
	////// Определяем отношение подпапок к папкам
	if ($papki_numbers==0) $and_par = " and parent_id='0'"; else $and_par = "";
		$sql="SELECT cid, title, parent_id from ".$prefix."_".$pages."_categories where module='$useitX' and `tables`='pages'".$and_par." order by $papka_sort";
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
				$textX .= "<a class='papki_$useitX' href=-".$useitX."_cat_".$id.">".$nam."</a>".$and." | ";
			}
		}
	}
	if ($noli == 0) $textX .= "</ul>";
	// Вставим шаблон из блока!!!
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "5": # Блок голосования
	$textX = "<script language=\"javascript\">
	  function showopros".$idX."(res, golos) { 
		  $.get('opros.php', { num: '".$idX."', res: res, golos: golos }, function(data) { 
			  $('#show_opros".$idX."').html( data ); 
		  }); 
	  }
	  $(showopros".$idX."(1, 0));
	  </script><div id='show_opros".$idX."'>Загружается опрос...</div>";
	$block = str_replace("[".$titleX."]", $design_open.$textX.$design_close, $block);
	$type = ""; break; 
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "6": # Фотогалерея
	$links = explode("\n",$textX);
	$textX = $textX0 = "";
	global $siteurl;
	for ( $i=0; $i < count($links); $i++ ) { 
		# Обработка ссылок на фото
		$link = $title = $alt = "";
		$link = explode("|",$links[$i]);
		if (isset($link[1])) $title = $link[1]; else $title = "";
		if (isset($link[2])) $alt = $link[2]; else $alt = "";
		if (isset($link[0])) $link = $link[0]; else $link = "";
		if ($watermark != "") $water = "http://".$siteurl."/includes/phpThumb/phpThumb.php?src=".$link."&fltr[]=wmi|".$watermark."|BR|100";	else $water = $link;
		// foto_gallery_type: 1 - миниатюры, 0 - «карусель»
		if ($foto_gallery_type == 1) $textX .= "<span class='div_a_img_gallery'><a title='".$title."' href='".$water."' class='lightbox' rel='group'><img alt='".$title."' src='http://".$siteurl."/includes/phpThumb/phpThumb.php?src=".$link."&amp;w=".$img_width."&amp;h=".$img_height."&amp;q=0' class='img_gallery'></a></span> ";
		elseif ($foto_gallery_type == 0) $textX0 .= "<li><a href='".$water."'><img src='http://".$siteurl."/includes/phpThumb/phpThumb.php?src=".$link."&amp;w=".$img_width."&amp;h=".$img_height."&amp;q=0' title='".$title."' alt='".$alt."' class='image".$i."'></a></li>";
	}
	if ($foto_gallery_type == 0) $textX .= "
    <div id='carusel-gallery' class='ad-gallery'>
      <div class='ad-image-wrapper'></div>
      <div class='ad-controls'></div>
      <div class='ad-nav'>
        <div class='ad-thumbs'>
          <ul class='ad-thumb-list'>
            ".$textX0."
          </ul>
        </div>
      </div>
    </div>";

	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "7": # Блок PHP // проверить eval
	eval($textX); // Все содержится в переменной $txt, а eval() - для выполнения кода
	if (!isset($txt)) $txt = "";
	$block = str_replace("[$titleX]", $design_open.$txt.$design_close, $block);
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
		$shablon_category = "<li class=\"[css] block_li_title\">[активность]<a href=[ссылка]>[название]</a>[число страниц]</li>";
		$shablon_razdelitel = "";
		$shablon_main = "<ul class=block_li_title>[содержание]</ul>";
		$shablon_active = $strelka." ";
		$shablon_active2 = "<img align=left width=10 height=10 src=images/pixel.gif>".$strelka." ";
	}
	$block_title = "";
	if ($titleshow != 2 and $titleshow != 3) $block_title .= "<h3 class=h3_block_title>".$ModuleName."</h3><div class=polosa></div>";
	$textX = "";
		global $text_mainpage2; // ЗАМЕНА mainpage2 №4
		$textXX = explode("|", $text_mainpage2[$DBName] );
	$pages = $textXX[0]; // получили название файла модуля, например, pages
	$num = array();
	// Определяем отношение страниц к папкам
	$sql = "SELECT cid from ".$prefix."_".$pages." where `tables`='pages' and module='$DBName' and active='1'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		$cid_id = $row['cid'];
		if (!isset($num[$cid_id])) $num[$cid_id] = 0;
		if ($cid_id>0) $num[$cid_id]++;
	}
	// Определяем cid от pid, т.е. если открыта не папка, а страница
	if ($pid>0) {
		$sql = "SELECT cid from ".$prefix."_".$pages." where `tables`='pages' and pid='$pid'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$cid = $row['cid'];
	}
	// Определяем отношение подпапок к папкам
	$sql="SELECT cid, title, parent_id from ".$prefix."_".$pages."_categories where module='$DBName' and `tables`='pages' order by $papka_sort";
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
		if ($num[$id]>0) $and = " ($num[$id]".$and2.")";
		} else $and="";
		// Определение и выделение выбранной (текущей) папки
		if (!isset($par[$cid])) $par[$cid] = 0;
		if ($cid == $id or $id == $par[$cid]) {
			$thisis = $shablon_active;
			$podpapki = "";
			// Вывод подпапок выбранной категории
			$sql="SELECT cid, title from ".$prefix."_".$pages."_categories where module='$DBName' and `tables`='pages' and parent_id='$id' order by $papka_sort";
			$result = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($result)) {
					$p_id = $row['cid'];
					$p_title = $row['title'];
					if ($cid==$p_id) $podthisis = "".$shablon_active2.""; 
					else $podthisis = "<img align=left width=30 height=10 src=images/pixel.gif>";
					//$podpapki .= "<li class=\"podpapki block_li_title\">[стрелка]<a href=[ссылка]>[название]</a></li>";
					$tr = array(
					"[№]"=>$p_id,
					"[название]"=>$p_title,
					"[ссылка]"=>"/-".$DBName."_cat_".$p_id."",
					"[полная ссылка]"=>"<a href=-".$DBName."_cat_".$p_id.">".$p_title."</a>",
					"[число страниц]"=>"",
					"[css]"=>"podpapki",
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
			"[№]"=>$id,
			"[название]"=>$nam,
			"[ссылка]"=>"/-".$DBName."_cat_".$id."",
			"[полная ссылка]"=>"<a href=-".$DBName."_cat_".$id.">".$nam."</a>",
			"[число страниц]"=>$and,
			"[css]"=>"papki",
			"[активность]"=>$thisis
			);
			$papki[] = strtr($shablon_category,$tr).$podpapki;
		}
	}
	$textX .= implode($shablon_razdelitel,$papki);
	$shablon_main = str_replace("[содержание]",$textX,$shablon_main);
	if ($textX!="") $block = str_replace("[$titleX]", $design_open.$block_title.$shablon_main.$design_close, $block);
	else $block = str_replace("[$titleX]", "", $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "9": # Блок мини-фото - экстрактор предописания страниц
	$textX = ""; // В эту переменную входит содержание блока
	$and = "";
	$and2 = "";
	if ($cid_open=="все" or $cid_open=="") { // Показывать ВСЕ папки или выбранную
	} else $and2 = " and cid='$cid_open'";
	$lim2 = $size * $limkol;
	if ($showlinks > 0) $lim2 = 100000;
	$proc = intval(100 / $limkol);
	if ($main==1) $and = " and `mainpage` = '1'";
	if ($main==2) $and = " and `mainpage` != '1'";
	if ($useitX == "") $andmodule = "";
	else $andmodule = " and `module`='$useitX'";
	$sql = "SELECT `pid`, `module`, `title`, `open_text`, `main_text` from ".$prefix."_pages where `tables`='pages'".$andmodule.$and.$and2." and `active`='1' and ( (`open_text` like '%".$first."%' or `main_text` like '%".$first."%') and (`open_text` like '%.jpg%' or `open_text` like '%.gif%' or `main_text` like '%.jpg%' or `main_text` like '%.gif%') and `open_text` not like '%smilies%' and `open_text` not like '%addVariable%' and `open_text` not like '%embed%' and `main_text` not like '%smilies%' and `main_text` not like '%addVariable%' and `main_text` not like '%embed%') order by ".$sort." limit $number,$lim2";
	$result = $db->sql_query($sql) or die('Ошибка');
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

		if ($limkol > 1 and $opentable==0) { $textX .= "<table cellspacing=0 cellpadding=0 width=100%><tr valign=top><td width=$proc%>"; $opentable=1; }
		if ($openshow > 0) { // 2,1,0 - да и без ссылки,да и со ссылкой,нет
			$open2 = explode("<br>",$open_text);
			$open2 = str_replace($open2[0]."", "", $open_text);
			if ($openshow == 1) $open = "<a href=-".$modul."_page_".$id.">$open2</a>";
			if ($openshow == 2) $open = $open2;
		}
		if ($show_title == 2) $textX .= "<div class=\"".$class."\"><a href=-".$modul."_page_".$id." class='ex_pic ".$class."'>".$title."</a></div>";
		$textX .= "<a href=-".$modul."_page_".$id." class='ex_pic ".$class."'><img".$width.$height." src='".$src."' title=\"".$title."\" class=\"extract ".$useitX."_pic ".$class."\"></a>";
		if ($show_title == 2) $textX .= $open;
		if ($show_title == 1) $textX .= "<div class=\"".$class."\"><a href=-".$modul."_page_".$id." class='ex_pic ".$class."'>".$title."</a></div>";
		if ($show_title == 1) $textX .= $open;
		$limkol_num++;
		$kol_num++;
		if ($limkol > 1) {
			if ($limkol_num == $limkol) {
				if ($kol_num == $size * $limkol) {
				if ($showlinks > 0) $textX .= "</table>|+|+|<table cellspacing=0 cellpadding=0 width=100%><tr valign=top><td width=".$proc."%>";
				else { $textX .= "</table>"; $close_table = true; }
				$limkol_num = 0;
				$kol_num = 0;
				} else {
					$limkol_num = 0;
					if ($kol_num != $size * $limkol) $textX .= "</td></tr><tr valign=top><td width=".$proc."%>";
					else { $textX .= "</td></tr></table>";  $close_table = true; }
				}
			} else {
			  $textX .= "</td><td width=".$proc."%>"; $close_table = false;
			}
		}
	}
	if ($showlinks > 0) $textX .= "</table>";
		
	if ($showlinks > 0) {
		$sql = "SELECT `pid` from ".$prefix."_pages where `tables`='pages' and `module`='$useitX'".$and.$and2." and `active`='1' and (`open_text` like '%".$first."%' or `open_text` like '%".$first2."%')";
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
	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "10": # Блок меню
	global $siteurl, $url;
	$url1 = $url;
	if ($url1 == "") $url1 = "/";
	$url1 = str_replace("http://".$siteurl,"",$url1);
	$url1 = str_replace("http%3A%2F%2F".$siteurl."%2F","","/".$url1);
	$url1 = str_replace("//","/",$url1);
	$url2 = explode("_",$url1);
	$url2 = $url2[0];
	$tr = array("[b]"=>"<b>","[B]"=>"<b>","[/b]"=>"</b>","[/B]"=>"</b>","[i]"=>"<i>","[I]"=>"<i>","[/i]"=>"</i>","[/I]"=>"</i>","http://".$siteurl=>"");
	$textX = strtr($textX,$tr);
	$tr = array("[уровень открыть]"=>"<ul>","[уровень закрыть]"=>"</ul>","[элемент открыть]"=>"<li>","[элемент закрыть]"=>"</li>","[/url]"=>"</a>","[url="=>"<a class='li1menu_link' href=","[/URL]"=>"</a>","[URL="=>"<a class='li1menu_link' href=","]"=>">"
	);
	$textXX = strtr($textX,$tr);
	if ($url != "/") {
		$textXX = str_replace("' href=".$url1.">", " mainmenu_open' href=".$url1.">", $textXX);
		$textXX = str_replace("<li><a class='li1menu_link mainmenu_open' href=".$url1.">", "<li class='li_mainmenu_open'><a class='li1menu_link mainmenu_open' href=".$url1.">", $textXX);

		$textXX = str_replace("' href=".$url2.">", " mainmenu_open' href=".$url2.">", $textXX);
		$textXX = str_replace("<li><a class='li1menu_link mainmenu_open' href=".$url2.">", "<li class='li_mainmenu_open'><a class='li1menu_link mainmenu_open' href=".$url2.">", $textXX);

		$textXX = str_replace("mainmenu_open mainmenu_open", "mainmenu_open", $textXX);

		if ($menu == 7 or $menu == 8 or $menu == 9) $textXX = str_replace("<li class='li_mainmenu_open'><a class='li1menu_link mainmenu_open' href", "<li class='current'><a href", $textXX);
	} else {
		if ($menu == 7 or $menu == 8 or $menu == 9) $textXX = str_replace("<li class='li_mainmenu_open'><a class='li1menu_link mainmenu_open' href='/'>", "<li class='current'><a href='/'>", $textXX);
		else $textXX = str_replace("<li><a class='li1menu_link' href='/'>", "<li class='li_mainmenu_open'><a class='li1menu_link mainmenu_open' href='/'>", $textXX);
	}

	switch ($menu) {
		case "0": // гор влево 3 уровня
		$class_menu = "menu-h-d"; break;
		############################################
		case "6": // верт 3 уровня
		$class_menu = "menu-v-d"; break;
		############################################
		case "5": // верт 1 уровня
		$class_menu = "menu-v"; break;
		############################################
		case "4": // гор влево 3 уровня вверх
		$class_menu = "menu-h-d.d-up"; break;
		 ############################################
		case "3": // гор влево 1 уровень
		$class_menu = "menu-h"; break;
		 ############################################
		case "1": // Таблица гор выравнивание по всей таблице 1 уровень
		GLOBAL $otstup_table_menu;
		$tr = array( // без 3 уровней!
		"[элемент открыть]"=>"<td align=center>","[элемент закрыть]"=>"</td>",
		"[/url]"=>"</div></a>","[url="=>"<a class='table1menu_link' href=\"",
		"[/URL]"=>"</div></a>","[URL="=>"<a class='table1menu_link' href=\"","]"=>"\"><div class=li2menu_div>".$otstup_table_menu.""
		);
		$textXX = strtr($textX,$tr);
		$textXX = "<table class='table1menu' width=100% cellspacing=0 cellpadding=0><tr valign=bottom>".$textXX."</tr></table>";
		$textXX = str_replace("' href=\"".$url1."\">", " mainmenu_open' href=\"".$url1."\" >", $textXX);
		$textXX = str_replace("' href=\"".$url2."\">", " mainmenu_open' href=\"".$url2."\" >", $textXX);
		break;
		 ############################################
		case "2": // вертикальное 2 уровня
		$tr = array( // без 3 уровней!
		"[уровень открыть]"=>"<ul class=ul_tree>","[уровень закрыть]"=>"</ul>","[элемент открыть]"=>"<li>","[элемент закрыть]"=>"</li>", "[/url]"=>"</a>","[url="=>"<a class=li2menu_link href=\"","[/URL]"=>"</a>","[URL="=>"<a class=li2menu_link href=\"","]"=>"\">"
		);
		$textXX = strtr($textX,$tr);
		$textXX = "<div class=suckerdiv><ul id=suckertree1>".$textXX."</ul></div>";
		$textXX = str_replace("<li><a class=li2menu_link href=\"".$url1."\">", "<li class=li_openlink><a class=li2menu_openlink href=\"".$url1."\">", $textXX);
		$textXX = str_replace("<li><a class=li2menu_link href=\"".$url2."\">", "<li class=li_openlink><a class=li2menu_openlink href=\"".$url2."\">", $textXX);
		break;
	 	############################################
	 	case "7": // KickStart вертикальное 3 уровня (слева)
		$class_menu = "menu vertical"; break;
		case "8": // KickStart вертикальное 3 уровня (справа)
		$class_menu = "menu vertical right"; break;
		case "9": // KickStart горизонтальное 3 уровня (слева)
		$class_menu = "menu"; break;
	}
	if ($menu != "1" and $menu != "2") $textXX = "<ul id=\"menu\" class=\"".$class_menu."\">".$textXX."</ul>";
	
	$block = str_replace("[$titleX]", $design_open.$textXX.$design_close, $block);
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
		$sql = "select date from ".$prefix."_pages where `tables`='pages' and module='$useitX' and active!='0' order by date";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
		$dates = explode(" ",$row['date']);
		$calendar_dates[] = $dates[0];
		}
	} else {
		$sql = "select name from ".$prefix."_spiski where type='$calendar' and pages!='' order by name";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
		$calendar_dates[] = $row['name'];
		}	
	}
	$textX .= "".my_calendar($calendar_dates, $useitX, $showdate); 
	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "13": # ОБЛАКО ТЕГОВ
	$tags = array();
	$sql = "select search from ".$prefix."_pages where `tables`='pages' and active='1'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		if (trim($row['search']) != "") {
		$tag = array();
		$tag = explode(" ",trim(str_replace("  "," ",$row['search'])));
			foreach ($tag as $tag1) {
				if (trim($tag1) != "" and strlen($tag1)>2 ) $tags[] = trim($tag1);
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
		if ($razmer == 0) { $tags3[$tag1] = "20"; $tags4[$tag1] = "red"; }
		if ($razmer == 1) { $tags3[$tag1] = "17.75"; $tags4[$tag1] = "orange"; }
		if ($razmer == 2) { $tags3[$tag1] = "15.5"; $tags4[$tag1] = "blue"; }
		if ($razmer == 3) { $tags3[$tag1] = "13.25"; $tags4[$tag1] = "green"; }
		if ($razmer > 3) { $tags3[$tag1] = "11"; $tags4[$tag1] = "gray"; }
		if ($razmer > 10) $tags3[$tag1] = "1";
		$razmer++;
	}
	$textX .= "<div id=text_tags style=\"display:none\"><br>"; 
	$tagcloud = "";
	foreach ($tags as $tag_name => $tag_col) {
		if ($tags3[$tag_col] != "1") {
			$tagcloud .= "<a href='--slovo_".str_replace( "%","-",  $tag_name ) ."' style='font-size: ".$tags3[$tag_col]."px;'>".$tag_name."</a> "; //  class='slovo' title='$tag_col темы' rel=\"tag nofollow\"
			$tagcloud2 .= "<noindex><a class='slovo' href='--slovo_".str_replace( "%","-",  $tag_name ) ."' style='color:".$tags4[$tag_col]."; font-size: ".$tags3[$tag_col]."pt;' rel='nofollow'>".$tag_name."</a></noindex> ";
		}
	}
	$tagcloud = str_replace( "&nbsp;", " ", str_replace( "+", " ", $tagcloud ) ) ; 
	$tagcloud2 = str_replace( "&nbsp;", " ", str_replace( "+", " ", $tagcloud2 ) ) ; 
	$textX .= str_replace( "pt","px", $tagcloud2);
	$textX .= "<br><br><center><div id=show_oblako style='font-size: 11px; cursor:pointer;' OnClick=\"show('text_tags'); show('flash_tags'); show('show_tags'); show('all_tags');\"><font style='border-bottom:1px dotted;'>Вернуть облако тегов</font></div></center> 
	</div>
	<div id=\"all_tags\"></div>
	<div id=flash_tags><script>
	var rnumber = Math.floor(Math.random()*9999999);
	var widget_so = new SWFObject(\"/includes/tagcloud.swf?r=\"+rnumber, \"tagcloudflash\", \"100%\", \"250\", \"9\", \"#ffffff\");
	widget_so.addParam(\"wmode\", \"transparent\");
	widget_so.addParam(\"allowScriptAccess\", \"always\");
	widget_so.addVariable(\"tcolor\", \"0x000000\");
	widget_so.addVariable(\"tspeed\", \"113\");
	widget_so.addVariable(\"distr\", \"true\");
	widget_so.addVariable(\"mode\", \"tags\");
	widget_so.addVariable(\"tagcloud\", \"<span>". $tagcloud ."<\/span>\");
	widget_so.write(\"all_tags\");
	</script></div> 
	<center><div id=show_tags style='font-size: 11px; cursor:pointer;' OnClick=\"show('text_tags'); show('flash_tags'); show('show_tags'); show('all_tags');\"><font style='border-bottom:1px dotted;'>Смотреть список слов</font></div></center> 
	";
	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "22": # База данных (количество по 2 колонкам)
	if ($direct=="gor") { $direct = $first; $first = $second; $second = $direct; }
	$and = "";
	if (trim($textX) != "") $and = " and ".$textX;
	$firsts = array();
	$result = $db->sql_query("SELECT ".$first." FROM ".$prefix."_base_".$base." where (active='1' or active='3')$and");
	while ($row = $db->sql_fetchrow($result)) {
	$firsts[] = $row[$first];
	}
	$firsts = array_unique($firsts);
	
	$seconds = array();
	$result = $db->sql_query("SELECT ".$second." FROM ".$prefix."_base_".$base." where (active='1' or active='3')$and");
	while ($row = $db->sql_fetchrow($result)) {
	$seconds[] = $row[$second];
	}
	$seconds = array_unique($seconds);

	$textX = "<table class=base_table width=100%><tr><td class=base_first>$text</td>";
	foreach ($firsts as $first1) {
	$textX .= "<td class=base_first>".$first1."</td>";
	}
	if ($all==1) {
	$textX .= "<td class=base_first>Всего:</td>";
	}
	$textX .= "</tr>";
	foreach ($seconds as $second1) {
	$textX .= "<tr><td class=base_second>".$second1."</td>";
		foreach ($firsts as $first1) {
		$numrows = $db->sql_numrows($db->sql_query("SELECT ".$first." FROM ".$prefix."_base_".$base." 
		WHERE ".$first."='".$first1."' and ".$second."='".$second1."' and (active='1' or active='3')$and"));
		$textX .= "<td class=base_second_first><a href=-".$useitX."_first_".str_replace("%","|",urlencode(str_replace("-","-_-",str_replace("+","+++",$second1))))."_second_".str_replace("%","|",urlencode(str_replace("-","---",str_replace("+","+_+",$first1))))."_opt_".$idX.">".$numrows."</a></td>";
		}
		if ($all==1) {
		$numrows = $db->sql_numrows($db->sql_query("SELECT ".$first." FROM ".$prefix."_base_".$base." 
		WHERE ".$second."='".$second1."' and (active='1' or active='3')$and"));
		$textX .= "<td class=base_second_first><a href=-".$useitX."_first_".str_replace("%","|",urlencode(str_replace("-","---",str_replace("+","+++",$second1))))."_opt_".$idX.">".$numrows."</a></td>";
		}
	$textX .= "</tr>";
	}
	$textX .= "</table>";

	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "23": # База данных (список по нескольким колонкам)
	$and = "";
	if (trim($textX) != "") $and = " and ".$textX;
	if ($direct=="vert") {
		$where = "where active='1' or active='3'$and ";
		$text = str_replace(",","</td><td>",$text);
		if ($textX != "") $where = "where ".stripcslashes($textX)." and (active='1' or active='3')$and ";
		$textX = "<table class='base_table table_light' width=100%>";
		if ($text != "") $textX .= "<tr class=base_first><td>".$text."</td></tr>";

		// узнать имя БД по номеру
		global $id_mainpage2;
		$base_name = WhatArrayElement($id_mainpage2, $base);
		$razdel_name = WhatArrayElement($id_mainpage2, $useitX);

		$sql = "SELECT id, ".$col." FROM ".$prefix."_base_".$base_name." ".$where." order by ".$sort." limit ".$number.",".$size."";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$pass_num = count($row)/2;
			$textX .= "<tr class=base_second>";
			for ($x=1; $x < $pass_num; $x++) {
				if ($x==1) $textX .= "<td><a href=-".$razdel_name."_page_".$row['id'].">".$row[$x]."</a></td>";
				else $textX .= "<td>".$row[$x]."</td>";
			}
			$textX .= "</tr>";
		}
		$textX .= "</table>";
	} else {
		$textX .= "Горизонтальный вывод данного блока еще не готов.";
	}
	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
//////////////////////////////////////////////////////////////////////////////////////////////////////
case "30": # Статистика раздела, выводит кол-во посещений
	$textX = "ошибка";
	$sql8 = "select counter from ".$prefix."_mainpage where `tables`='pages' and name='$useitX' and type='2'";
	$result8 = $db->sql_query($sql8);
	$row8 = $db->sql_fetchrow($result8);
	$textX = $row8['counter'];
	$block = str_replace("[$titleX]", $design_open.$textX.$design_close, $block);
	$type = ""; break;
	
//case "8": 
//$type = ""; break;

	} # конец определения типа блока
} # ЗАКОНЧЕНО Определение блоков и их заполнение
} # ЗАКОНЧЕНО ПОВТОРНОЕ Определение блоков и их заполнение
//////////////////////////////////////////////////////////////////////////////////////////////////////

	// Заголовок для модуля ставится в дизайн
	if (strpos($block, "[заголовок")) {
		$block = str_replace("[заголовок]", "<div class=cat_title>".$main_title."</div>", $block); 
		$block = str_replace("[заголовок-ссылка]", "<div class=cat_title><A class=cat_categorii_link href=-".$DBName.">".$main_title."</a></div>", $block); 
	}

	// Ставим годовой промежуток существования сайта
	$god = $startdate;
	$year = date("Y");
	$nextyear = $year+1;
	if ($year != $startdate) $god .= "—$year";
	$block=str_replace("[год]", "© ".$god, $block); // Промежуток лет ставится в дизайн

	// Ставим статистику
	$block=str_replace("[статистика]", $counter, $block); 

	// Ставим почту
	$mailer = "<a href=\"/mail.php\">Отправить письмо</a>"; // ".str_replace("@","<nobr>@</nobr>",$adminmail)." ";
	$soderganie=str_replace("[почта]", $mailer, $soderganie); 
	$block=str_replace("[почта]", $mailer, $block); 

	// Ставим Новый год
	if (strpos($block, "[новый год]")) { //February 12, 2001
		$newyaer = time_otschet("January 01, ".$nextyear, "C Новым годом!!!", "До Нового года осталось: ");
		$soderganie=str_replace("[новый год]", $newyaer, $soderganie); 
		$block=str_replace("[новый год]", $newyaer, $block); 
	}
	if (strpos($block, "[1 сентября]")) {
		// Ставим 1 сентября 2011,1,1
		if ( date("m") > 9 or ( date("m") == 9 and date("d") > 1 ) ) $year = $nextyear;
		$sent = time_otschet("September 01, ".$year, "Время пришло!", "До нового учебного года осталось: ");
		$soderganie=str_replace("[1 сентября]", $sent, $soderganie); 
		$block=str_replace("[1 сентября]", $sent, $block); 
	}

	// Ставим почту
	if (strpos($block, "письмо]")) {
		// Заявка
		$mailer = "
		<table width=100% border=0> 
		<form action=send.php enctype='multipart/form-data' method=post name=formsend> 
		<tr><td><nobr>Ф.И.О., компания:</nobr><br><input type=text name=mail_subject maxlength=32 size=40 class=all_width></td></tr> 
		<tr><td><nobr>E-mail:</nobr><br><input type=text name=mail_to maxlength=64 size=40 class=all_width></td></tr> 
		<tr><td><nobr>Телефон:</nobr><br><input type=text name=mail_tel maxlength=30 size=40 class=all_width></td></tr> 
		<tr><td>Заявка:<br><textarea cols=50 rows=4 name=mail_msg class=all_width></textarea><br>
		<input type=hidden name=mail_file></td> 
		</tr><tr><td>
		<span class=small>* Все поля обязательны к заполнению</span>
		<p align=right><input class='standart_send_button' value=\"Отправить\" type=\"button\" onClick=\" al=''; if (document.formsend.mail_subject.value=='') al = al + 'Вы не заполнили поле &quot;Ф.И.О.&quot;. '; if (document.formsend.mail_to.value=='') al = al + 'Вы не заполнили поле &quot;E-mail&quot;. '; if (document.formsend.mail_tel.value=='') al = al + 'Вы не заполнили поле &quot;Телефон&quot;. '; if (document.formsend.mail_msg.value=='') al = al + 'Вы не заполнили поле &quot;Заявка&quot;. '; if (al) alert(al); else submit();\"></p>
		</td></tr> 
		</form> 
		</table>"; 
		$soderganie=str_replace("[заявка-письмо]", $mailer, $soderganie); 
		$block=str_replace("[заявка-письмо]", $mailer, $block); 

		// Обычная почта
		$mailer = "
		<table width=100% border=0> 
		<form action=send.php enctype='multipart/form-data' method=post name=formsend> 
		<tr><td><nobr><b>Фамилия, имя*:</b></nobr></td><td width=60%><input type=text name=mail_subject maxlength=32 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>E-mail*:</b></nobr></td><td width=60%><input type=text name=mail_to maxlength=64 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>Телефон*:</b></nobr></td><td width=60%><input type=text name=mail_tel maxlength=30 size=40 class=all_width></td></tr> 
		<tr><td colspan=2><b>Текст письма*:</b><br><textarea cols=50 rows=8 name=mail_msg class=all_width></textarea><br>
		<input type=hidden name=mail_file></td> 
		</tr><tr><td colspan=2>
		<span class=small>* Все поля обязательны к заполнению</span>
		<p align=right><input class='standart_send_button' value=\"Отправить\" type=\"button\" onClick=\" al=''; if (document.formsend.mail_subject.value=='') al = al + 'Вы не заполнили поле &quot;Фамилия и имя&quot;. '; if (document.formsend.mail_to.value=='') al = al + 'Вы не заполнили поле &quot;E-mail&quot;. '; if (document.formsend.mail_tel.value=='') al = al + 'Вы не заполнили поле &quot;Телефон&quot;. '; if (document.formsend.mail_msg.value=='') al = al + 'Вы не заполнили поле &quot;Текст письма&quot;. '; if (al) alert(al); else submit();\"></p>
		</td></tr> 
		</form> 
		</table>"; 
		$soderganie=str_replace("[письмо]", $mailer, $soderganie); 
		$block=str_replace("[письмо]", $mailer, $block); 

		// Ставим почту+имя
		$mailer = "
		<table width=400 border=0 align=center> 
		<form action=send.php enctype='multipart/form-data' method=post name=formsend> 
		<tr><td><nobr><b>Фамилия, имя*:</b></nobr></td><td width=60%><input type=text name=mail_subject maxlength=32 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>E-mail:</b></nobr></td><td width=60%><input type=text name=mail_to maxlength=64 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>Телефон*:</b></nobr></td><td width=60%><input type=text name=mail_tel maxlength=30 size=40 class=all_width></td></tr> 
		<tr><td colspan=2>* - поля обязательны к заполнению.
		<p align=right><input class='micro_send_button' value=\"Отправить\" type=\"button\" onClick=\" al=''; if (document.formsend.mail_subject.value=='') al = al + 'Вы не заполнили поле &quot;Ваше имя&quot;. '; if (al) alert(al); else submit();\"></p>
		</td></tr> 
		</form> 
		</table>"; 
		$soderganie=str_replace("[микрописьмо]", $mailer, $soderganie); 
		$block=str_replace("[микрописьмо]", $mailer, $block); 

		// Ставим почту короткую
		$mailer2 = "
		<table width=100% border=0> 
		<form action=send.php enctype='multipart/form-data' method=post name=formsend> 
		<tr><td><nobr><b>Фамилия, имя*:</b></nobr></td><td width=60%><input type=text name=mail_subject maxlength=32 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>E-mail*:</b></nobr></td><td width=60%><input type=text name=mail_to maxlength=64 size=40 class=all_width></td></tr> 
		<tr><td><nobr><b>Телефон*:</b></nobr></td><td width=60%><input type=text name=mail_tel maxlength=30 size=40 class=all_width></td></tr> 
		<tr><td colspan=2><b>Комментарий*:</b><br>
		<textarea cols=50 rows=2 name=mail_msg class=all_width></textarea>
		<br><input type=hidden name=mail_file></td> 
		</tr><tr><td colspan=2>
		<span class=small>* Все поля обязательны к заполнению</span>
		<p align=right><input class='small_send_button' value=\"Отправить\" type=\"button\" onClick=\" al=''; if (document.formsend.mail_subject.value=='') al = al + 'Вы не заполнили поле &quot;Фамилия и имя&quot;. '; if (document.formsend.mail_to.value=='') al = al + 'Вы не заполнили поле &quot;E-mail&quot;. '; if (document.formsend.mail_tel.value=='') al = al + 'Вы не заполнили поле &quot;Телефон&quot;. '; if (document.formsend.mail_msg.value=='') al = al + 'Вы не заполнили поле &quot;Комментарий&quot;. '; if (al) alert(al); else submit();\"></p>
		</td></tr> 
		</form> 
		</table>
		"; 
		$soderganie=str_replace("[миниписьмо]", $mailer2, $soderganie); 
		$block=str_replace("[миниписьмо]", $mailer2, $block); 
	}

	// Ставим поиск
	$search = "<form method=POST action=\"/--search\" class='main_search_form'><input type='search' name=slovo placeholder='Поиск по сайту' class='main_search_input'><input type='submit' name='ok' value='Найти' class='main_search_button'></form>";
	$block=str_replace("[поиск]", $search, $block);

	// Ставим подписку
	$search = "<form method=POST action=\"/--email\" class=main_mail_form><table width=100%><tr><td align=right>Email: </td><td><input type=text name=mail class=main_mail_input size=10 class=all_width></td></tr><tr><td align=right>Имя: </td><td><input type=text name=avtor class=main_mail_input size=10 class=all_width></td></tr><tr><td colspan=2 align=right><input type='submit' name='ok' value='Подписаться'></td></tr></table></form>";
	$block=str_replace("[подписка]", $search, $block);

// Ставим подписку в линию
	$search = "<form method=POST action=\"/--email\" class=main_mail_form><table><tr><td><b>Рассылка: </b></td><td>&nbsp;Email:</td><td><input type=text name=mail class=main_mail_input size=10 class=all_width></td><td>&nbsp;Имя:</td><td><input type=text name=avtor class=main_mail_input size=10 class=all_width></td><td colspan=2 align=right><input type='submit' name='ok' value='Подписаться'></td></tr></table></form>";
	$block=str_replace("[подписка_горизонт]", $search, $block);

	// Ставим день и время
	if (strpos($block, "[день]") or strpos($block, "[время]")) {
		$den = date("d m Y");
		$den = explode(" ",$den);
		$den = intval($den[0])." ".findMonthName($den[1])." ".$den[2];
		$vremya = date("H:i", time() + 3600); // + 1 час - Самарское время.
		$block=str_replace("[день]", $den, $block);
		$block=str_replace("[время]", $vremya, $block);
	}

	// Ставим кнопку Твиттера
	$block=str_replace("[твиттер]", "<div><a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-lang=\"ru\" data-size=\"large\">Твитнуть</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script></div>", $block);

	// Ставим символ |
	$block=str_replace("[-]", "|", $block); //  УБРАТЬ!!!!!!!!!!!!!!!!!!!!!!!

	//Ставим RSS
	if (strpos($block, "[rss")) {
		$block=str_replace("[rss]", "<a href=rss.php title=\"RSS-подписка позволит вам быстро узнать о всех новых статьях на этом сайте\" target=_blank class=rss><img src=images/rss_16.png></a>", $block);
		$block=str_replace("[rss32]", "<a href=rss.php target=_blank title='RSS-подписка. Нажми!'><img src=images/rss_32.gif></a>", $block);
		$block=str_replace("[rss50]", "<a href=rss.php target=_blank title='RSS-подписка. Нажми!'><img src=images/rss_50.gif></a>", $block);
		$block=str_replace("[rss100]", "<a href=rss.php target=_blank title='RSS-подписка. Нажми!'><img src=images/rss_100.gif></a>", $block);
		$block=str_replace("[rss150]", "<a href=rss.php target=_blank title='RSS-подписка. Нажми!'><img src=images/rss_150.gif></a>", $block);
		$block=str_replace("[rss200]", "<a href=rss.php target=_blank title='RSS-подписка. Нажми!'><img src=images/rss_200.gif></a>", $block);
	}

	// Обработка мини-блоков (карточка компании)
	function company_blocks($company, $name, $block) {
		$company = explode("|||", $company);
		foreach ($company as $key => $value) {
			$key_plus = $key + 1;
			$block = str_replace("[".$name.$key_plus."]", str_replace("\r\n", "<br>", $value), $block);
		}
		return $block;
	}
	$block = company_blocks($company_name, "компания", $block);
	$block = company_blocks($company_fullname, "КОМПАНИЯ", $block);
	$block = company_blocks($company_address, "адрес компании", $block);
	$block = company_blocks($company_time, "время работы компании", $block);
	$block = company_blocks($company_tel, "телефон компании", $block);
	$block = company_blocks($company_sot, "сотовый компании", $block);
	$block = company_blocks($company_fax, "факс компании", $block);
	$block = company_blocks($company_email, "почта компании", $block);
	$block = company_blocks($company_map, "карта компании", $block);
	$block = company_blocks($company_people, "лицо компании", $block);

	// Ставим валютный информер
	if (strpos($block, "[валюта]")) { // INFORMER VALUTA by 13i
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
		$valuta .= "<A HREF=\"javascript:\" onMouseDown=\"win2 = window.open('http://www.cbr.ru/currency_base/D_print.aspx?date_req=".$dat."','',
		'width=400,height=260'); return true;\">Валюта</a>: USD: ".$usd." EUR: ".$eur."";
		$block = str_replace("[валюта]", $valuta, $block);
	}
	
	// Получить заголовки всех страниц и разделов
	global $title_mainpage2, $show_page_links;
	// НАЙТИ ЗАМЕНУ, к примеру функцию поиска между скобками.
	
		// Определяем все разделы
		foreach( $title_mainpage2 as $key_name => $row_title ) {
			$row_title = str_replace( "»","&raquo;", str_replace( "«","&laquo;", $row_title ) );
			$row_title2 = predlogi($row_title);
			$block = str_replace("{".$row_title."}", "<a class=auto_link href=-".$key_name.">".$row_title."</a>", $block);
			$block = str_replace("{".$row_title2."}", "<a class=auto_link href=-".$key_name.">".$row_title."</a>", $block);
		}
	if ($show_page_links == 1) {
		// Определяем все страницы
		$sql = "SELECT pid, module, title FROM ".$prefix."_pages where `tables`='pages' and active='1'";
		$result = $db->sql_query($sql);
		while ($rows = $db->sql_fetchrow($result)) {
			$row_title = str_replace( "«","&laquo;", str_replace( "»","&raquo;", $rows['title'] ) );
			$row_title2 = predlogi($row_title);
			$block=str_replace("{".$row_title."}", "<a class=auto_link href=-".$rows['module']."_page_".$rows['pid'].">".$row_title."</a>", $block);
			$block=str_replace("{".$row_title2."}", "<a class=auto_link href=-".$rows['module']."_page_".$rows['pid'].">".$row_title."</a>", $block);
		}
	}

/*

Регулярное выражения для поиска и выбора значений между фигурным скобками {}. // проверить и внедрить
$raz[0] = "{";
$raz[1] = "}";
$text='111 {222 222} {33 33} {f}{ \=-01 ?!%} { 44 }444}';
preg_match_all("/\\".$raz[0]."[^\\"
.$raz[1]."]+\\".$raz[1]."/s", $text, $matches);
var_dump($matches);

# будет выведено
# array
# 0 => 
# array
# 0 => string '{222 222}' (length=9)
# 1 => string '{33 33}' (length=7)
# 2 => string '{f}' (length=3)
# 3 => string '{ \=-01 ?!%}' (length=12)
# 4 => string '{ 44 }' (length=6)

*/
}

############################################################################################################
######################### ФОРМИРОВАНИЕ ДОКУМЕНТА ###########################################################
############################################################################################################
$pagetit = str_replace("<br>","",$pagetitle);

  if ($keywords2 == "") $keywords2 = $keywords;
  if ($description2 == "") $description2 = $description;
// При открытии модуля можно убирать определенные блоки через CSS

global $add_css;
if (trim($add_css) != "") $stil .= "_add_".str_replace (" ","-", str_replace ("  "," ", trim($add_css))); 

header("Cache-Control: public");
header("Expires: " . date("r", time() + 3600));
global $data_page, $lang; // 0, 24 Oct 2009 07:55:07
if ($data_page != "") header ("Last-Modified: 0, $data_page GMT");
else header ("Last-Modified: ".gmdate("L, d M Y H:i:s")." GMT");
header ("Content-Type: text/html; charset=utf-8");
// <!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">
echo "<!doctype html>\n<html lang=\"".$lang."\" dir=\"ltr\">\n<head>";
if (file_exists("favicon.ico"))  echo "<link rel='shortcut icon' href='favicon.ico' />";
elseif (file_exists("favicon.png")) echo "<link rel='shortcut icon' href='favicon.png' />";
//if (file_exists("favicon_apple.png")) echo "<link rel='apple-touch-icon' href='favicon_apple.png' />";

// Основной JavaScript
echo "<script src='includes/j.js'></script>\n
<script src='includes/iepngfix_tilebg.js'></script>\n";

echo "<title>".$pagetit.$sitename."</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<meta name=\"keywords\" content=\"".$keywords2."\">
<meta name=\"description\" content=\"".$description2."\">
<meta name=\"robots\" content=\"index, follow\">
<meta http-equiv=\"Content-language\" content=\"".$lang."\"> 
<meta name=\"copyright\" content=\"".$sitename."\">

<script src=\"http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js\"></script>
<script src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js\"></script>
<script src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js\"></script> 
<script src=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/i18n/jquery-ui-i18n.min.js\"></script> 
<script src=\"includes/jquery.lightbox.js\"></script>
<script src=\"includes/jquery.innerfade.js\"></script>
<script src=\"includes/ui.core.js\"></script>
<script src=\"includes/ui.tabs.js\"></script>
<script src=\"includes/jquery.ad-gallery.js\"></script>";

global $kickstart;
// 1,2,3,4,5,6,7,8 — KickStart,CSSframework,Skeleton,Kube,Bootstrap,1140 Grid,Toast,Blueprint
if ($kickstart == 4) echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kube100/kube.min.css\" /> 	
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kube100/master.css\" />";
if ($kickstart == 1) echo "<!--[if lt IE 9]><script src=\"http://html5shiv.googlecode.com/svn/trunk/html5.js\"></script><![endif]-->
<script type=\"text/javascript\" src=\"includes/css-frameworks/kickstart/js/prettify.js\"></script>
<script type=\"text/javascript\" src=\"includes/css-frameworks/kickstart/js/kickstart.js\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kickstart/css/kickstart.css\" media=\"all\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kickstart/style.css\" media=\"all\" />";
else echo "<script>
$(document).ready(function(){ 
	$('.lightbox').lightbox({ fitToScreen: true, imageClickClose: false }); 
	var galleries = $('.ad-gallery').adGallery(); $('#switch-effect').change( function() { galleries[0].settings.effect = $(this).val(); return false; } ); 
});
</script>
<link rel='stylesheet' href='includes/lightbox.css' media='screen' />"; // при включенном kickstart, lightbox не нужен

// http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/themes/base/jquery-ui.css — проверить
echo "
<link rel=\"stylesheet\" href=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/themes/base/jquery-ui.css\" media=\"all\" />
<link rel='stylesheet' href='includes/ui.tabs.css' media='print, projection, screen' /> 
<link rel='alternate' href='/rss/' title='".$siteurl." RSS' />
<link rel='stylesheet' href='includes/carusel.css' media='screen' />
<link rel='stylesheet' href='".$stil.".css' />";

	################ НАЧАЛО ТЕЛА
	$notmenu = "";
	if ($stopcopy == 1) echo $notmenu = " oncontextmenu='notmenu();'";
	echo "</head>\n<body".$notmenu.">";

	if ($kickstart == 1) echo "<a id=\"top-of-page\"></a><div id=\"wrap\" class=\"clearfix\">";
	if ($kickstart == 4) echo "<div id=\"page\">";

	// Исправляем старые ошибки: # проверить и удалить
	$block = str_replace("»»»","\"",$block);
	$block = str_replace("\"\"\"","\"",$block);
	$block = str_replace("\"»","\"",$block);
	$block = str_replace("%C2%BB%C2%BB%C2%BB","",$block);
	$block = str_replace("%22%22%22","",$block);

	//Подставляем получившееся в HTML + поправка для IE. // удалить
	echo str_replace("</table></table>","</table>",str_replace("</tr></div>","</tr>",$block)); 

		// Если включена погодная анимация
		global $url;
		//echo $url;
		if ($url=="/") {
			if ($pogoda==1) echo "<script src='includes/sneg.js'></script>\n";
			if ($pogoda==2) echo "<script src='includes/list.js'></script>\n";
			if ($pogoda==3) {
				echo "<script src='includes/shar.js'></script>\n";
				include("includes/ballon.htm");
			}
		}
		// Если включена SWF Flash поддержка
		if ($flash==1) echo "<script src='includes/swffix_modified.js'></script>\n";

	// Если включена защита от копирования (для школьников)
	if ($stopcopy==1) echo "<script language='Javascript1.1'><!-- 
	function notmenu() { window.event.returnValue=false;} 
	document.ondragstart = test; 
	document.onselectstart = test; 
	document.ontextmenu = test; 
	function test() { return false } 
	// --></script> ";
	
	global $admin, $pid, $now, $nocash;

	if ($kickstart == 1 or $kickstart == 4) echo "</div>";

	echo "\n</body>\n</html>"; // Кончаем! )

	$txt = ob_get_contents(); // собираем файл для вывода на экран и сохранения в кеше
	ob_end_clean();
	$txt2 = $txt;
	if (is_admin($admin)) $txt2 = page_admin($txt2,$pid); // добавили функции админа к страничке
	echo $txt2;

	// если в config.php выбрано «показывать ошибки», помимо этого покажет запросы к БД и их количество
	if ($display_errors == true) print("<!-- запросов: $db->num_queries \n $db->num_q -->");

	// Проверка добавляемой информации
	if ( !strpos(" ".$txt, "Ошибка: «Адрес раздела»" ) ) {
		$txt = addslashes($txt);
		// Запрет кеширования
		//$nocash = explode(" ","/?name=-search /--search ".trim(str_replace("  "," ",str_replace("\n"," ",$nocash))));
		$url0 = str_replace("http://".$siteurl,"",$url);
		$url0 = str_replace("http%3A%2F%2F".$siteurl."%2F","/",$url0);
		$sql = "SELECT `text`, `data` FROM ".$prefix."_cash where `url`='$url0' limit 1";
	    $result = $db->sql_query($sql);
	    $numrows = $db->sql_numrows($result);
	    if ($numrows == 0) {
			// Добавление в кеш
			if ( $nocash == false and !strpos($url0,"-search") and !strpos($url0,"_cat_") and !strpos($url0,"savecomm") and !strpos($url0,"savepost") ) $db->sql_query("INSERT INTO `".$prefix."_cash` (`id`, `url`, `data`, `text`) VALUES (NULL, '$url0', '$data', '$txt');") or die ("Обновите страницу, нажав F5 !!!");
		}
	}
	// Запуск антивируса
	antivirus(); 
?>
