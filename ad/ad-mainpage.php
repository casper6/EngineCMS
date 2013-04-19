<?php
// Все «правила хорошего кода» написаны кровью, вытекшей из глаз программистов, читавших чужой код.
	if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
	$aid = trim($aid);
	global $prefix, $db, $red;
	$sql = "select realadmin from ".$prefix."_authors where aid='".$aid."'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$realadmin = $row['realadmin'];
	if ($realadmin==1) {
		$tip = "mainpage";
		$admintip = "mainpage";
##########################################################################################
function menu() {
	global $admintip, $siteurl, $type, $prefix, $db, $statlink;
	$stat_razdel = $stat_page = $stat_search = $mail_list = "";
	##############################################################################
	if ($type=="stat") {
		$sql = "select `name`, `title`, `counter` from ".$prefix."_mainpage where `tables`='pages' and `name`!='index' and `type`='2' order by counter desc";
		$result = $db->sql_query($sql) or die('Ошибка при попытке прочитать посещаемость разделов');
		while ($row = $db->sql_fetchrow($result)) {
			$stat_razdel .= "<tr valign=top><td class='polosa gray'><a target='_blank' href='/-".$row['name']."'>".strip_tags($row['title'], '<b><i>')."</a></td><td align=center class='polosa gray'>".$row['counter']."</td></tr>";
		}
		$stat_razdel = "<strong>Посещаемость разделов:</strong><table width=100% class='table_light'>".$stat_razdel."</table>Посещения страниц сайта администратором не учитываются. ";
	
		$sql = "SELECT pid, module, title, counter from ".$prefix."_pages where active='1' and `tables`='pages' order by counter desc limit 0,20";
		$result = $db->sql_query($sql) or die('Ошибка при попытке прочитать посещаемость страниц');
		while ($row = $db->sql_fetchrow($result)) {
		$stat_page .= "<tr valign=top><td class='polosa gray'><a target='_blank' href='/-".$row['module']."_page_".$row['pid']."'>".strip_tags($row['title'], '<b><i>')."</a></td><td align=center class='polosa gray'>".$row['counter']."</td></tr>";
		}
		$stat_page = "<strong>и страниц:</strong> <a href='sys.php?op=mainpage&amp;type=stat_page'>См. популярные &rarr;</a><table width=100% class='table_light'>".$stat_page."</table>";
		$user_name = array();
		$user_mail = array();
		$user_tel = array();
		$sql = "select `avtor`, `mail`, `ip`, `tel` from ".$prefix."_pages_comments where `mail`!='' or `tel`!='' order by ip";
		$result = $db->sql_query($sql) or die('Ошибка при попытке прочитать комментарии');
		while ($row = $db->sql_fetchrow($result)) {
			$user_ip = $row['ip'];
			$user_name[$user_ip] = $row['avtor'];
			$user_mail[$user_ip] = $row['mail'];
			$user_tel[$user_ip] = $row['tel'];
		}
		$sql = "SELECT ip, slovo, data, pages from ".$prefix."_search order by data desc limit 0,20";
		$result = $db->sql_query($sql) or die('Ошибка при попытке прочитать внутренние поисковые запросы сайта');
		while ($row = $db->sql_fetchrow($result)) {
			$user_ip = $row['ip'];
			$user_info = "";
			if (isset($user_name[$user_ip])) if ($user_name[$user_ip] != '') $user_info .= "Имя: ".$user_name[$user_ip];
			if (isset($user_mail[$user_ip])) if ($user_mail[$user_ip] != '') $user_info .= "<br>E-mail: ".$user_mail[$user_ip];
			if (isset($user_tel[$user_ip])) if ($user_tel[$user_ip] != '') $user_info .= "<br>Тел. ".$user_tel[$user_ip];
			$stat_search .= "<tr valign=top><td class='polosa gray'>".date2normal_view($row['data'], 2, 1)."</td><td class='polosa'>".$row['slovo']."<br><span class='gray'>".$user_info."</span></td><td class='polosa gray'>".$row['pages']."</td></tr>";
		}
		$stat_search = "<strong>Искали через поиск на сайте:</strong> <a href='sys.php?op=mainpage&amp;type=stat_search'>См. всё &rarr;</a><table width=100% class='table_light'>".$stat_search."</table>
		<strong>В средней колонке</strong> также выводится предполагаемое имя, e-mail и телефон человека, искавшего этот запрос.<br>
		<strong>В последней колонке:</strong> первое число - найдено в названии страниц, второе - в содержании.";
		
		if ($statlink != "") echo "<h2><img class='icon2 i41' src='/images/1.gif'><a href=".$statlink." target=_blank>Сторонняя статистика</a></h2>"; else echo "<div class='notice warning'>Сторонняя статистика не настроена. См. <a href='sys.php?op=Configure'>Настройки</a></div>";
		echo "<h2><img class='icon2 i41' src='/images/1.gif' style='margin-top:10px'>Встроенная статистика:</h2>
		<table cellpadding=2 width=100% class='block radius'><tr valign=top><td width=25%>".$stat_razdel."</td><td width=30%>".$stat_page."</td><td>".$stat_search."</td></tr></table></div>
		</body>
		</html>";
		exit;
	}
	##############################################################################
	if ($type=="stat_search") {
		echo "<h1>Статистика поисковых запросов (полная версия, макс. 10000 шт.)</h1>
		<a href=/sys.php?op=mainpage&amp;type=stat>Вернуться к общей статистике</a><br><br>";
		$user_name = array();
		$user_mail = array();
		$user_tel = array();
		$sql = "select `avtor`, `mail`, `ip`, `tel` from ".$prefix."_pages_comments where `mail`!='' or `tel`!='' order by ip";
		$result = $db->sql_query($sql); // or die('Ошибка при попытке прочитать названия разделов');
		while ($row = $db->sql_fetchrow($result)) {
			$user_ip = $row['ip'];
			$user_name[$user_ip] = $row['avtor'];
			$user_mail[$user_ip] = $row['mail'];
			$user_tel[$user_ip] = $row['tel'];
		}
		$sql = "SELECT id, ip, slovo, data, pages from ".$prefix."_search order by data desc limit 0,10000";
		$result = $db->sql_query($sql); // or die('Ошибка при попытке прочитать названия разделов');
		$stat_search1 = $stat_search2 = $stat_search3 = $stat_search4 = array();
		while ($row = $db->sql_fetchrow($result)) {
			$id = $row['id'];
			$del = " <div id='s_".$id."' style='display:inline;'><a onclick=delslovo('".$id."') class='punkt' title='Удалить слово'><img class='icon2 i21' src='/images/1.gif'></a></div>";
			$user_ip = $row['ip'];
			$user_info = "";
			if (isset($user_name[$user_ip])) if ($user_name[$user_ip] != '') $user_info .= "Имя: ".$user_name[$user_ip]." [".date2normal_view($row['data'], 2)."]";
			if (isset($user_mail[$user_ip])) if ($user_mail[$user_ip] != '') $user_info .= " E-mail: ".$user_mail[$user_ip];
			if (isset($user_tel[$user_ip])) if ($user_tel[$user_ip] != '') $user_info .= " Тел. ".$user_tel[$user_ip];
			//$str = "<tr valign=top><td class='polosa gray'>".date2normal_view($row['data'], 2)."</td><td class='polosa'>".$row['slovo'].$del."<br><span class='gray'>".$user_info."</span></td><td class='polosa gray'>".$row['pages']."</td></tr>";
			if (trim($row['slovo']) != "") {
				if ($row['pages'] == "0 | 0") $stat_search1[] = $row['slovo'].$del." <span class='gray'>".$user_info."</span>";
				elseif ($row['pages'] == "0 | 1") $stat_search2[] = $row['slovo'].$del." <span class='gray'>".$user_info."</span>";
				elseif (strpos(" ".$row['pages']," 0 | ") == "0 | 0") $stat_search3[] = $row['slovo']." — ".$row['pages']." ".$del." <span class='gray'>".$user_info."</span>";
				else $stat_search4[] = $row['slovo']." — ".$row['pages']." ".$del." <span class='gray'>".$user_info."</span>";
			}
		}
		natcasesort($stat_search1);
		natcasesort($stat_search2);
		natcasesort($stat_search3);
		natcasesort($stat_search4);
		$stat_search1 = "".implode("<br>",$stat_search1)."";
		$stat_search2 = "".implode("<br>",$stat_search2)."";
		$stat_search3 = "".implode("<br>",$stat_search3)."";
		$stat_search4 = "".implode("<br>",$stat_search4)."";
		if ($stat_search1 != "") echo "<h2>Ничего не найдено (можно создать искомые страницы)</h2>".$stat_search1."<hr>";
		if ($stat_search2 != "") echo "<h2>Найдено в содержании всего одной страницы (слишком мало информации по искомому слову)</h2>".$stat_search2."<hr>";
		if ($stat_search3 != "") echo "<h2>Найдено только в содержании (можно изменить названия страниц или создать новые страницы)</h2>".$stat_search3."<hr>";
		if ($stat_search4 != "") echo "<h2>Остальное (удовлетворяющее, найдено в названии и содержании)</h2>".$stat_search4."";
		exit;
	}
	##############################################################################
	if ($type=="stat_page") {
	global $now;
		$stat_page = "";
		$proc = 0;
		echo "<h1>Статистика посещений страниц</h1>
		<a href=/sys.php?op=mainpage&amp;type=stat>Вернуться к общей статистике</a><br><br>";
		$sql = "SELECT pid, module, title, `date`, counter from ".$prefix."_pages where active='1' and `tables`='pages' and `counter` > 15 order by counter desc limit 0,1000000";
		$result = $db->sql_query($sql); // or die('Ошибка при попытке прочитать названия разделов');
		$numrows = $db->sql_numrows($result);
		$nu = 0;
		while ($row = $db->sql_fetchrow($result)) {
			if ($proc == 0) {
				$proc = 100; $count = $row['counter'];
			} else {
				$proc = intval($row['counter'] * 100 / $count);
				if ($proc == 0) $proc = 1;
			}
			$time = dateresize($now) - dateresize($row['date']);
			if ($time == 0) $proc2 = 0;
			else $proc2 = intval( $row['counter'] / $time );
			// счетчик делим на сколько дней прошло с публикации
			if ($proc2 > 9) $proc2X = "<td class='red ffa4ac'>".$proc2."";
			elseif ($proc2 > 5) $proc2X = "<td class='green b4f3b4'>".$proc2."";
			else $proc2X = "<td class='f3f3a3'>".$proc2."";
			
			if ($proc2 > 2) {
				$nu++;
				$stat_page .= "<tr valign=top class='tr_hover'><td class='polosa gray'><a target=_blank href=/-".$row['module']."_page_".$row['pid'].">".$row['title']."</a><sup>".$nu."</sup></td><td width=100 class='polosa'><div class='h15 gradient' style='width:".$proc."%;'></div></td><td class='polosa'>".$row['counter']."</td>".$proc2X."</td><td class='polosa'>".$time."</td></tr>";
			}
		}
		if ($numrows > 0) $pro = intval($nu * 100 / $numrows);
		else $pro = 0;
		echo "<table width=100%><tr valign=bottom><td><nobr><strong>Страницы</strong> (всего: ".$numrows.", показано ниже: ".$nu.", эффективность: ".$pro."%)</nobr><br>
		Новые (до 15 посещений) и малопосещаемые (меньше 3 в день) не отображаются.</td><td width=80><strong><nobr>Процент</nobr></strong></td><td><nobr>Посещения</nobr></td><td><strong><nobr>Среднее</nobr></strong></td><td><nobr>Время, дней</nobr></td></tr>".$stat_page."</table>";
		exit;
	}
}
######################################################################################################
function mainpage($name="") {
	global $tip, $admintip, $prefix, $db, $name, $id, $display_delete, $display_addmenu;
	include("ad-header.php");
	echo "<a name=1></a>";
	menu();
	if ($name=="design" or $name=="css" or $name=="module" or $name=="block" or $name=="spisok" or $name=="base" or $name=="shablon") { create_main($name); }
	elseif (intval($id)>0) { edit_main($id); }
	elseif ($display_addmenu == false) echo "<center><br>Создание основных разделов сайта запрещено администратором.</center>";
	else {
		
		echo "<table class='block_back' cellspacing=0 cellpadding=0><tr valign=top><td id='razdel_td' class='radius' width=300>

			<div id='razdels' class='razdels'>
			<div class='black_grad h40'><button id=new_razdel_button title='Добавить оформление...' class='small black right3' onclick=\"show_animate('addmain');\"><span class='mr-2 icon darkgrey small' data-icon='+'></button>
			<span class='h1'>Оформление:</span>
			</div>";
	     ////////////////////// ДИЗАЙН 0
		 echo "<div id='mainrazdel0' class='dark_pole2'><a class='base_page' onclick=\"oformlenie_show('дизайн (html)','0','design','/sys.php?op=mainpage&amp;name=design&amp;type=0&amp;red=1')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='4'></span><span class='plus20'>Дизайн разделов и блоков (html)</span></div></a></div>";
		 ////////////////////// СТИЛЬ 1
		 echo "<div id='mainrazdel1' class='dark_pole2'><a class='base_page' onclick=\"oformlenie_show('стиль (css)','1','css','/sys.php?op=mainpage&amp;name=css&amp;type=1')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='#'></span><span class='plus20'>Стиль для дизайна (css)</span></div></a></div>";
		 ////////////////////// БЛОКИ 3
		 echo "<div id='mainrazdel3' class='dark_pole2'><a class='base_page' onclick=\"oformlenie_show('блок','3','block','/sys.php?op=mainpage&amp;name=block&amp;type=3')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='R'></span><span class='plus20'>Блоки (небольшие элементы)</span></div></a></div>";
		 ////////////////////// ШАБЛОНЫ 6
		 echo "<div id='mainrazdel6' class='dark_pole2'><a class='base_page' onclick=\"oformlenie_show('шаблон','6','shablon','/sys.php?op=mainpage&amp;name=shablon&amp;type=6&amp;red=1')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='v'></span><span class='plus20'>Шаблоны (внешний вид)</span></div></a></div>";
		 ////////////////////// ПОЛЯ 4
		 echo "<div id='mainrazdel4' class='dark_pole2'><a class='base_page' onclick=\"oformlenie_show('поле','4','pole','/sys.php?op=mainpage&amp;name=spisok&amp;type=4')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='S'></span><span class='plus20'>Поля (для страниц)</span></div></a></div>";
		 ////////////////////// БАЗЫ ДАННЫХ 5
		 echo "<div id='mainrazdel5' class='dark_pole2'><a class='base_page' onclick=\"oformlenie_show('БД','5','base','/sys.php?op=mainpage&amp;name=base&amp;type=5')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='D'></span><span class='plus20'>Базы данных (таблицы)</span></div></a></div>";
		 
		echo "</div></td><td>

			<div style='display:none;' id=addmain>
			<div class='block_white2 radius add_oformlenie'>
			<a title='Закрыть это окно' class=punkt onclick=\"show_animate('addmain')\"><div class='radius close_button'>&nbsp;x&nbsp;</div></a>

			<h1>Вы решили добавить:</h1>
			<a href='sys.php?op=mainpage&amp;name=design&amp;type=0&amp;red=1#1' class='bigicon'><img class='bigicon bi0' src='/images/1.gif'><b>Дизайн</b><br>
			Аналог темы, обрамление разделов или блоков</a>
			<a href='sys.php?op=mainpage&amp;name=css&amp;type=1#1' class='bigicon'><img class='bigicon bi1' src='/images/1.gif'><b>Стиль CSS</b><br>
			Редактируемый онлайн, для подключения к дизайнам</a>
			<a href='sys.php?op=mainpage&amp;name=block&amp;type=3#1' class='bigicon'><img class='bigicon bi3' src='/images/1.gif'><b>Блок</b><br>
			Настраиваемые элементы для вывода различной информации</a>
			<a href='sys.php?op=mainpage&amp;name=shablon&amp;type=6&amp;red=1#1' class='bigicon'><img class='bigicon bi6' src='/images/1.gif'><b>Шаблон</b><br>
			Замена настроек на ручной выбор выводимой информации</a>
			<a href='sys.php?op=mainpage&amp;name=spisok&amp;type=4#1' class='bigicon'><img class='bigicon bi4' src='/images/1.gif'><b>Поле</b><br>
			Поля для страниц, аналог таксономии CMS Drupal</a>
			<a href='sys.php?op=mainpage&amp;name=base&amp;type=5#1' class='bigicon'><img class='bigicon bi5' src='/images/1.gif'><b>Базу данных</b><br>
			Удобная таблица с поиском для внутреннего или открытого использования</a>
			<br><br>
			</div></div>

			<div class='m5'><button id='new_razdel_button' class='small' onclick='location.href=\"/sys.php?op=mainpage&amp;type=0&amp;id=1&amp;red=1\"' title='Редактировать главный дизайн'><span class='icon gray small' data-icon='7'></span>главный дизайн</button> 
			<button id='new_razdel_button' class='small' onclick='location.href=\"/sys.php?op=mainpage&amp;type=1&amp;id=20\"' title='Редактировать главный стиль'><span class='icon gray small' data-icon='7'></span>главный стиль</button>

			<div class='podrazdel radius' id='podrazdel'></div>
			</td></tr></table>";
		}
	echo "</div>
	</body>
	</html>";
}
###################################################################################################
function create_main($type) {
	global $tip, $admintip, $prefix, $db;
	echo "<a name=1></a>";
	$create = ""; 
	switch ($type) {
	case "design": $type_opis = "дизайна (HTML-разметка, внешний вид сайта)";
	$create.="<div id=about class=block style='display:none;'>Дизайн — это обрамление страниц сайта, всё, что окружает контент, содержание. В дизайне могут быть блоки, которые выводят необходимую информацию. Например созданный вами блок меню или автоматический блок [статистика], который выводит счетчик статистики из настроек сайта. В общем, дизайн — это весь html-код, от body до /body (невключительно), а сама страница заменена на служебное слово [содержание] - блок, который выводит содержание подключенных разделов и их страниц. После создания дизайна, откройте его редактирование и присвойте необходимые стили (css).<br></div>
	<table width=100%><tr><td>
	<span class=h2>Название:</span> По-русски, можно с пробелами
	<input type='text' name='title' value='' size=40 class='w100 h40 f16' autofocus><br>
	<br>".help_design()."<br>
	<span class=h2>Содержание дизайна (HTML):</span><br>
	<textarea name=text rows=15 cols=86 class='w100 h40 f16'></textarea>
	<div class='notice success black'><span class='icon large white' data-icon='C'></span>
	Здесь вы можете вставить готовый HTML-код (от тега &lt;body&gt; до &lt;/body&gt;, не включительно) или набрать его с нуля.
	<br><b>[содержание]</b> - автоматический блок для вывода страниц. Не использовать его можно лишь в случае присоединения дизайна к разделу, состоящему из одной страницы — в этом случае можно всю страницу поместить в дизайн или в раздел.</div>
	<input type=hidden name=id value=0>
	<input type=hidden name=namo value=''>
	<input type=hidden name=useit value=''>
	<input type=hidden name=shablon value=''>
	<input type=hidden name=type value='0'>
	<input type=hidden name=op value='".$admintip."_save'>
	</td></tr></table>";
	########################################################
	// [корзина] - общая корзина для всех разделов типа 'магазин'

	break;
	case "css": $type_opis = "стиля (CSS, настройка внешнего вида)";
	$create.="
	<div id=about class=block style='display:none;'>Стили - это CSS (каскадные таблицы стилей), описание оформления элементов сайта. Стили подключаются к дизайну (при его редактировании). В дизайне может быть использовано сразу несколько стилей. При подключении стилей они объединяются в один стиль и сжимаются.<br></div>
	<table width=100%><tr><td>
	<h2>Название:</h2>По-русски, можно с пробелами<br>
	<input type=text name=title value='' size=40 class='w100 h40 f16' autofocus>
	<h2>Содержание стиля:</h2>
	<textarea id=text name=text rows=30 cols=86 class='w100 h700 f12'></textarea><br>
	<input type=hidden name=id value=0>
	<input type=hidden name=type value='1'>
	<input type=hidden name=namo value=''>
	<input type=hidden name=useit value=''>
	<input type=hidden name=shablon value=''>
	<input type=hidden name=op value='".$admintip."_save'>
	</td></tr></table>";
	codemirror("css", "text");
	########################################################
	break;

	case "block": $type_opis = "блока (элемент содержания)";
		global $title_razdel_and_bd;
		$bd_show = false;
		$razdels = "";
		foreach ($title_razdel_and_bd as $key => $modul_title) {
			if (strpos(" ".$modul_title, "База данных")) {
				$razdels .=  "<option value='".$key."' class='baza'>".$modul_title."</option>";
				$bd_show = true;
			} else $razdels .= "<option value='".$key."' class='razdely'>".$modul_title."</option>";
		}
		if ($bd_show == false) $bd_show = " disabled"; else $bd_show = "";

	$typeX = array();
	$typeX[2]  = "Текст или HTML"; 
	$typeX[10] = "Меню сайта";
	$typeX[5]  = "Голосование (опрос)"; 
	$typeX[3]  = "Ротатор рекламы"; 

	$typeX[6]  = "Галерея из фото"; 
	$typeX[9]  = "Галерея из страниц";

	$typeX[4]  = "Папки раздела"; 
	$typeX[8]  = "Папки открытого раздела";
	$typeX[0]  = "Страницы раздела"; 
	$typeX[1]  = "Комментарии раздела"; 

	$typeX[12] = "Контактная форма";
	$typeX[31] = "JavaScript";
	$typeX[7]  = "PHP-код";
	$typeX[11] = "Календарь";
	$typeX[13] = "Облако тегов";
	$typeX[30] = "Посещаемость раздела";

	$typeX[20] = "БД (количество по 1 колонке верт.)";
	$typeX[21] = "БД (количество по 1 колонке гор.)";
	$typeX[22] = "БД (количество по 2 колонкам)";
	$typeX[23] = "БД (список колонок)";

			$styles = ""; // Выборка дизайнов
			$titles_design = titles_design();
			foreach ($titles_design as $id_design => $title_design) {
				if ($id_design != 1) $styles .= "<option value='".$id_design."'>".$title_design."</option>";
			}

	$create = "<div id=about class=block style='display:none;'>Блок — это дополнительный элемент сайта, который может быть вставлен в любом его месте. Блоки бывают автоматические (заранее заданные системой), полуавтоматические (создаваемые с последующей настройкой того, что будет в них отображаться) и ручные (текст, HTML, JavaScript или PHP-код).<br></div>
	<table width=100%><tr valign=bottom><td width=50%>
	<span class=h2>Название блока:</span><br><input type='text' name='title' value='' size=60 class='w100 h40 f16' autofocus></td><td>
	<span class=h3>Дизайн блока:</span><br><select name=design><option value=0>выбирать необязательно</option>".$styles."</select><br>Не стоит использовать дизайн для раздела!
	</td></tr><tr><td colspan=2>
	<span class=h2>Выберите тип блока:</span> (справа увидите его параметры (не у всех блоков), а снизу — описание)
	<table width=100% cellspacing=0 cellpadding=0><tr valign=top><td class='pr10'>
	<select id=name size=10 name='name' class='w100 f14' onchange=\"

	var arr = [ 'Блок выводит несколько страниц выбранного раздела или всех разделов', 'Блок выводит несколько комментариев со страниц выбранного раздела или всех разделов', 'В этом блоке можно написать любой текст, использовать HTML, а также другие созданные блоки', '«Ротатор» используется для показа блоков, текста или html.<br>При каждом обновлении страницы будет показан один из написанных элементов, разделение списка ротации — через символ «|» (вертикальная черта)', 'Блок выводит папки выбранного раздела или всех разделов', 'Блок выводит голосование или опрос, для чего нужно ввести сам вопрос в названии блока, а список вопросов — в Содержании блока, через «Enter»', 'Блок «Фотогалерея» выводит фотографии в нужном месте сайта, для его создания используется список адресов закаченный фотографий, через «Enter».<br>А их описание ставится сразу после адреса через символ «|» (вертикальная черта), пример: /img/1.jpg|Фото 1. <br>— Имена файлов могут содержать любые символы. <br>— Загрузка автоматическая сразу после выбора файлов. <br>— Фотографии будут автоматически переименованы, развернуты в нужную сторону, сжаты и уменьшены по ширине до 1000 пикселей.<br><div class=red>— Если впоследствии вы захотите стереть какую-либо фотографию — просто удалите ее строчку из блока галереи (не забудьте сохранить блок), затем перейдите во вкладку «Настройки», откройте «Удаление неиспользуемых фотографий» и удалите эту фотографию.</div>', 'PHP-код пишется в Содержании блока.<br>PHP можно писать сразу, без начальных и конечных обозначений ( &lt; ?php ... ? &gt; ).<br>Вывод информации в блок производится через переменную <b>$"."txt</b>', 'Блок выводит папки открытого в данный момент раздела или ничего не выводит, если это Главная страница или папок в разделе не создано', 'Эта фотогалерея собирает первые фотографии из страниц выбранного раздела (или всех разделов).<br>Если в предисловии страницы не обнаружена фотография, эта страница пропускается.', 'Меню сайта создается по простым правилам (для того, чтобы быть универсальным и легко переключать варианты отображения):<br>[элемент открыть][url=/]Главная[/url][элемент закрыть]<br>[элемент открыть][url=#]Пункт меню 1[/url][элемент закрыть]<br>[элемент открыть][url=#]Пункт меню 2[/url]<br>&nbsp;&nbsp;[уровень открыть]<br>&nbsp;&nbsp;[элемент открыть][url=#]Подпункт 1[/url][элемент закрыть]<br>&nbsp;&nbsp;[элемент открыть][url=#]Подпункт 2[/url][элемент закрыть]<br>&nbsp;&nbsp;[уровень закрыть]<br>[элемент закрыть]<br><i>где # - это ссылка на страницу.</i><br>В меню может быть до 3-х уровней вложенности', 'Календарь на текущем месяце показывает ссылками те даты, за которые созданы страницы в выбранном разделе (или всех разделах).<br>Также показывает текущую дату', 'В РАЗРАБОТКЕ!!!!!!!!! Контактная форма может применяться для создания страницы Контакты, а также для отправки разнообразных анкет, заявок, жалоб и т.д', 'Облако тегов — это вращающийся трехмерный шар, состоящий из ключевых слов, взятых из страниц выбранного раздела (или всех разделов). Под ним есть ссылка на альтернативный текстовый вариант облака', '14', '15', '16', '17', '18', '19', 'База данных (количество по 1 колонке верт.)', 'База данных (количество по 1 колонке гор.)', 'База данных (количество по 2 колонкам)', 'База данных (список колонок)', '24', '25', '26', '27', '28', '29', 'Статистика раздела, выводит кол-во посещений выбранного раздела', 'JavaScript-блок автоматически встанет в HEAD и его не нужно где-либо специально размещать.', '32' ];

	var add;
	if (document.getElementById('name').value!=31) add = '.<br>После создания блока, необходимо его настроить — настройка откроется автоматически.';
	else add = '';
	$('#opisanie_bloka').html (arr[document.getElementById('name').value] + add);

	if (document.getElementById('name').value==20 || document.getElementById('name').value==21 || document.getElementById('name').value==22 || document.getElementById('name').value==23) { $('.baza').removeAttr('disabled'); $('.razdely').attr('disabled', 'disabled'); } else { $('.baza').attr('disabled', 'disabled'); $('.razdely').removeAttr('disabled'); }

	if (document.getElementById('name').value!=6) { $('#photo_upload').hide(); } else { $('#photo_upload').show(); }

	if (document.getElementById('name').value==0 || document.getElementById('name').value==1 || document.getElementById('name').value==4 || document.getElementById('name').value==9 || document.getElementById('name').value==11 || document.getElementById('name').value==13 || document.getElementById('name').value==30 || document.getElementById('name').value==20 || document.getElementById('name').value==21 || document.getElementById('name').value==22 || document.getElementById('name').value==23) { $('#razdel_bloka').show('slow'); } else { $('#razdel_bloka').hide(); }

	if (document.getElementById('name').value==2 || document.getElementById('name').value==10 || document.getElementById('name').value==12 || document.getElementById('name').value==5 || document.getElementById('name').value==3 || document.getElementById('name').value==31 || document.getElementById('name').value==7) { $('#textarea_block').show('slow'); } else { $('#textarea_block').hide(); }

	if (document.getElementById('name').value==30 || document.getElementById('name').value==20 || document.getElementById('name').value==21 || document.getElementById('name').value==22 || document.getElementById('name').value==23) { $('.allrazdely').attr('disabled', 'disabled'); } else { $('.allrazdely').removeAttr('disabled');  }

	if (document.getElementById('name').value==12) { $('#form_block').show('slow'); } else { $('#form_block').hide(); }
	\">";
		foreach ($typeX as $key => $tit) {
			$sel = "";
			if ($key == 2) $sel = " selected";
			if ($key > 19 and $key < 24) $create .=  "<option value=".$key.$bd_show.">".$tit."</option>";
			else $create .=  "<option value='".$key."'".$sel.">".$tit."</option>";
		}
	// ЗАмена http://blueimp.github.com/cdn/css/bootstrap-responsive.min.css
	// Удалено 	<!--[if lt IE 7]><link rel='stylesheet' href='http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css'><![endif]-->
	$create .=  "</select>
	<input type='hidden' name='useit'>
	</td><td>

	<div id='razdel_bloka' style='display:none;'>
	<b>Выберите раздел для блока:</b><br>
	<select name='modul' size=9 class='mi50ma80'>
	<option value='' class='allrazdely'>< ВСЕ разделы ></option>".$razdels."</select> 
	</div>

	<div id='textarea_block'>
	<h2>Содержание блока:</h2>
	<textarea name=text rows=3 cols=86 class='w100 h155' id=textarea></textarea>
	</div>
	<input type=hidden name=op value='".$admintip."_create_block'>
	<input type=hidden name=id value=''>
	</form>

	<div id='photo_upload' style='display:none;'>".add_file_upload_form()."</div>

	</td>
	<td id='form_block' style='display:none;' width=500 class='block'>
	<a onclick=\"$('#form_block').hide();\" class=help class='close_button radius'>x</a>
	<b>Добавление формы:</b><br>
	<select id='form_element' onchange='

	if ( $(\"#form_element\").val() != 0 && $(\"#form_element\").val() != \"Отправить\" ) {
		$(\".form_id\").show(\"slow\");
		$(\".form_zvezda\").show(\"slow\");
	} else {
		$(\".form_id\").hide();
		$(\".form_zvezda\").hide();
	}

	if ( $(\"#form_element\").val() != 0 ) {
		$(\".form_name\").show(\"slow\");
	} else {
		$(\".form_name\").hide();
	}

	if ( $(\"#form_element\").val() == \"Список\" ) {
		$(\".form_var\").show(\"slow\");
	} else $(\".form_var\").hide();

	if ( $(\"#form_element\").val() == \"Строка\" || $(\"#form_element\").val() == \"Текст\" ) {
		$(\".form_placeholder\").show(\"slow\");
	} else $(\".form_placeholder\").hide();

	if ( $(\"#form_element\").val() == \"Строка\" || $(\"#form_element\").val() == \"Email\" || $(\"#form_element\").val() == \"Телефон\" ) {
		$(\".form_size_h\").show(\"slow\");
	} else $(\".form_size_h\").hide();

	if ( $(\"#form_element\").val() == \"Текст\" || $(\"#form_element\").val() == \"Список\" || $(\"#form_element\").val() == \"Адрес\" ) {
		$(\".form_size_v\").show(\"slow\");
	} else $(\".form_size_v\").hide();
	

	if ( $(\"#form_element\").val() != \"Email\" && $(\"#form_element\").val() != \"Телефон\" && $(\"#form_element\").val() != \"Адрес\" && $(\"#form_element\").val() != \"Отправить\" ) {
		$(\"#form_name\").val(\"\");
		$(\"#form_id\").val(\"\");
		$(\"#form_placeholder\").val(\"\");
	} else {
		if ( $(\"#form_element\").val() == \"Email\" ) {
			$(\"#form_name\").val(\"Email\");
			$(\"#form_id\").val(\"email\");
		}
		if ( $(\"#form_element\").val() == \"Телефон\" ) {
			$(\"#form_name\").val(\"Телефон\");
			$(\"#form_id\").val(\"tel\");
		}
		if ( $(\"#form_element\").val() == \"Адрес\" ) {
			$(\"#form_name\").val(\"Адрес\");
			$(\"#form_id\").val(\"address\");
		}
		if ( $(\"#form_element\").val() == \"Отправить\" ) {
			$(\"#form_name\").val(\"Отправить\");
		}
	}
	'>
		<option value='0'> >> Выберите поле << </option>
		<option value='Строка'>Строка</option>
		<option value='Текст'>Текст</option>
		<option value='Список'>Выбор из списка</option>
		<option value='Файл'>Файл</option>
		<option value='Email'>Email</option>
		<option value='Телефон'>Телефон</option>
		<option value='Адрес'>Адрес</option>
		<option value='Отправить'>Кнопка «Отправить»</option>
	</select> 
	<a class='button small green' class='ml20' onclick='

	if ( $(\"#form_name\").val() == \"\" ) {
		$(\"#form_name\").val(\"Заполните это поле\");
	} else {
		var add;
		var form_zvezda = 0;
		if ($(\"#form_zvezda\").is(\":checked\")) form_zvezda = 1;
		if ( $(\"#form_element\").val() == \"Строка\" ) add = $(\"#form_placeholder\").val() + \"|\" + $(\"#form_size_h\").val();
		if ( $(\"#form_element\").val() == \"Текст\" ) add = $(\"#form_placeholder\").val() + \"|\" + $(\"#form_size_v\").val();
		if ( $(\"#form_element\").val() == \"Список\" ) add = $(\"#form_var\").val().replace(new RegExp(\"\\n\",\"g\"),\"*\") + \"|\" + $(\"#form_size_v\").val();
		if ( $(\"#form_element\").val() == \"Email\" ) add = $(\"#form_size_h\").val();
		if ( $(\"#form_element\").val() == \"Телефон\" ) add = $(\"#form_size_h\").val();
		if ( $(\"#form_element\").val() == \"Адрес\" ) add = $(\"#form_size_v\").val();
		if ( $(\"#form_element\").val() != \"Отправить\" && $(\"#form_element\").val() != \"Файл\") add = \"|\" + $(\"#form_id\").val() + \"|\" + add + \"|\" + form_zvezda;
		if ( $(\"#form_element\").val() == \"Файл\") add = \"|\" + $(\"#form_id\").val() + \"|\" + form_zvezda;
		$(\"#textarea\").val($(\"#textarea\").val()+ $(\"#form_element\").val() + \"|\" + $(\"#form_name\").val() + add + \"\\n\" );
	}
	'><span class='icon white small' data-icon='+'></span>Добавить поле</a><br>
	<table width=100%><tr valign=top><td width=50%>
	<div class='form_name' style='display:none'>Название <span class=small>(по-русски)</span>*:<br>
		<input id='form_name' class='w100 h40 f16'></div>
	</td><td>
	<div class='form_id' style='display:none'>и по-английски <span class=small>(необязательно)</span><br>
		<input id='form_id' class='w100 h40 f16'></div>
	</td></tr><tr valign=top><td>
	<div class='form_var' style='display:none'>Выбор <span class=small>(вариант*(рус.)/значение)</span>:<br>
		<textarea rows=3 cols=86 class='w100 h70 f16' id='form_var'></textarea></div>
	<div class='form_placeholder' style='display:none'>Текст в поле <span class=small>(тип значения)</span>:<br>
		<input id='form_placeholder' class='w100 h40 f16'></div>
	</td><td>
	<div class='form_size_h' style='display:none'>Ширина <span class=small>(необязательно)</span>:<br>
	<select id='form_size_h'>
		<option value='10'>10%</option>
		<option value='20'>20%</option>
		<option value='30'>30%</option>
		<option value='40'>40%</option>
		<option value='50'>50%</option>
		<option value='60'>60%</option>
		<option value='70'>70%</option>
		<option value='80'>80%</option>
		<option value='90'>90%</option>
		<option value='100' selected>100%</option>
	</select></div>
	<div class='form_size_v' style='display:none'>Высота <span class=small>(необязательно)</span>:<br>
	<select id='form_size_v'>
		<option value='1'>Одна строка</option>
		<option value='2'>Две строки</option>
		<option value='3'>Три строки</option>
		<option value='4'>Четыре строки</option>
		<option value='5' selected>Пять строк</option>
		<option value='10'>10 строк</option>
		<option value='15'>15 строк</option>
		<option value='20'>20 строк</option>
		<option value='25'>25 строк</option>
		<option value='30'>30 строк</option>
	</select></div>
	</tr><tr><td colspan=2>
	<div class='form_zvezda' style='display:none'>
		<label><input type='checkbox' id='form_zvezda' value='1'> Обязательно к заполнению</label></div>
	</td></tr>
	</table>
	Добавляйте по очередности.<br>
	При отсутствии английского названия, оно создается автоматически, транслитом русского.<br>
	Не должно быть одинаковых английских названий!<br>
	Кнопка «Отправить» обычно добавляется последней. Её можно и не добавлять, тогда она будет добавлена автоматически.
	</td></tr></table>
	<div id='opisanie_bloka' class='notice success black'>
	<span class='icon large white' data-icon='C'></span>В этом блоке можно написать любой текст, использовать HTML, а также другие созданные блоки.<br>
После создания блока, необходимо его настроить — настройка откроется автоматически.
	</div>
	<br> <div class='notice warning black'>Если выбран дизайн, в нём обязательно должен быть блок [содержание], сам дизайн его обрамляет.</div></td></tr></table>";
	break;
	########################################################!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	case "spisok": $type_opis = "поля (дополнительное поле для страниц)";
		$create.="";
			$modules = ""; // Выборка разделов заменить!
			$sql2 = "select id, title from ".$prefix."_mainpage where `tables`='pages' and type='2' and name!='index'";
			$result2 = $db->sql_query($sql2);
			while ($row2 = $db->sql_fetchrow($result2)) {
			   $id_modul = $row2['id'];
			   $title_modul = $row2['title'];
			   $modules .= "<option value='".$id_modul."'>".$title_modul."</option>";
			}
			// Добавлены пользователи USERAD
			$sql3 = "select * from ".$prefix."_users_group where `group`!='rigion' and `group`!='config' and `group`!='obl'";
			$result3 = $db->sql_query($sql3);
			while ($row3 = $db->sql_fetchrow($result3)) {
			   $id = "1,".$row3['id'];
			   $title_group = $row3['group'];
			   $modules .= "<option value='".$id."'>Группа пользователей: ".$title_group."</option>";
			}
			// Добавлены пользователи USERAD
	$create.="<div id=about class=block style='display:none;'>В Разделах сайта есть папки, в папках лежат страницы (статьи, новости и т.д.). У каждой страницы несколько полей для хранения информации: название, дата, предисловие, содержание и т.д. Если вдруг для какого либо раздела (или для всех разделов) не хватает подобного поля - его можно добавить. Примеры использования полей: отдельное поле для ввода автора (для статей), поле для загрузки фотографии (для фотогалереи), выбор из раскрывающегося списка определенного населенного пункта (для каталога предприятий), период времени (для афиши) и т.д. А для того, чтобы поля начали отображаться на страницах - есть произвольные шаблоны, которые можно создать и подключить к любому разделу (См. в настройках раздела - Шаблон для всего раздела или Шаблон для страницы).<br><b>Поле может принадлежать или какому-то одному разделу или сразу всем разделам.</b><br></div>
	<table width=100%><tr><td width=50%>
	<input type=hidden name=type value='4'>
	<h2>Название поля</h2><input type=text name=title size=40 class='w100 h40 f16' autofocus><br>(рус.)</td><td>
	<h2>Обращение</h2><input type=text name=namo size=40 class='w100 h40 f16'><br>(англ., без пробелов) Используется для вывода в шаблонах как: [обращение]</td></tr>
	<tr><td width=50%>
	<input type=hidden name=shablon value=''>
	<h2>Выберите раздел:</h2><select name='useit' class='w100 h40 f16'><option value='0'>все разделы</option>".$modules."</select></td><td>
	<h2>Выберите тип поля:</h2><select name='s_tip' class='w100 h40 f16' id=s_tip onchange=\"
	if (document.getElementById('s_tip').value==0) { hide('spisok_5'); hide('spisok_2'); hide('spisok_3'); hide('spisok_4'); show('spisok_1'); }
	if (document.getElementById('s_tip').value==1) { hide('spisok_5'); hide('spisok_3'); hide('spisok_4'); hide('spisok_1'); show('spisok_2'); }
	if (document.getElementById('s_tip').value==2) { hide('spisok_5'); hide('spisok_4'); hide('spisok_1'); hide('spisok_2'); show('spisok_3'); }
	if (document.getElementById('s_tip').value==4) { hide('spisok_3'); hide('spisok_4'); hide('spisok_1'); hide('spisok_2'); show('spisok_5'); }
	if (document.getElementById('s_tip').value==3) { 
		hide('spisok_5'); hide('spisok_1'); hide('spisok_2'); hide('spisok_3'); show('spisok_4');
		$('#textarea').hide();
	} else { $('#textarea').show(); }
	\">
	<option value='0'>список слов (словосочетания разделяются Enter'ом)</option>
	<option value='1'>текст (можно написать шаблон)</option>
	<option value='4'>строка (можно написать шаблон)</option>
	<option value='2' disabled>файл (указать какой, куда и что с ним делать)</option>
	<option value='3'>период времени (две даты, актуально для Афиши)</option>
	</select></td></tr>
	<tr><td colspan=2>
	<h2>Содержание или Настройка поля:</h2>".spisok_help()."
	<div id=textarea>
	<textarea name=text rows=10 cols=86 class='w100'></textarea></div>
	<input type=hidden name=id value=''>
	<input type=hidden name=op value=".$admintip."_save>
	</td></tr></table>";
	break;
	########################################################
	case "base": $type_opis = "базы данных (таблица)";
	$create.="<div id=about class=block style='display:none;'>База данных — это таблица с удобным редактированием (по типу БД, а не электронных таблиц), поиском и фильтрами. Таблица может содержать любые поля и выводиться на страницы сайта или использоваться для внутреннего документооборота компании (доступно через администрирование).</b><br></div>
	<table width=100%><tr><td colspan=2>
	<input type='hidden' name='type' value='5'>
	<input type='hidden' name='shablon' value=''>
	</td></tr>
	<tr valign=top><td width=50%>
	<h2>Название:</h2><input type=text name=title size=30 class='w100 h40 f16' autofocus><br>(рус.)</td><td>
	<h2>Обращение:</h2><input type=text name=namo size=30 class='w100 h40 f16'><br>(англ., без пробелов, мал. буквами)<br>
	<b>Удалить</b> таблицу с таким же обращением (замена копии)? <select name='delete_table'><option value='1'>да</option><option value='0'>нет</option></select>
	</td></tr>
	<tr><td colspan=2>
	<h2>Тип базы данных:</h2><select name='s_tip' class='w100 h40 f16'>
	<option value='1'>открытая база данных (ограниченный доступ, полный доступ по паролю)</option>
	<option value='2'>закрытая база данных (доступ по паролю)</option>
	<option value='3'>интернет-магазин</option>
	</select><br><br>

	<a onclick=\"show_animate('excel')\" class='dark_pole'>Загрузить готовую таблицу</a><br>
	<div style='display:none;' id=excel><div class=block_white2>
	<b>Файл CSV</b> (в формат .CSV можно сохранить таблицу Excel или экспортировать из 1С, формат - utf-8) <br>
	Если файл не выбран - будет создана пустая структура базы данных на основании Названий полей<br>
	<input type=file name='useit' size=30><br>
	<b>Разделение строк:</b> <input type=text name=line_close size=5 value='\\r\\n'> возврат каретки: \\r, символ окончания линии: \\n<br>
	<b>Разделение полей:</b> <select name=line_razdel><option value='##'>##</option><option value=';'>;</option><option value='#'>#</option></select> для 1С - ##, для Excel - ;<br>
	<b>Экранирование полей:</b> <input type=text name=line_ekran size=5 value='\"'><br>
	<b>Удалить первую строку?</b> <select name=delete_stroka><option value='1'>да</option><option value='0'>нет</option></select> если первая строка в таблице содержит названия столбцов
	</div></div><br>

	<b>Поля базы данных (названия столбцов):</b><br>

	<a name=pole></a>
	<table id=table width=100% class=table_light>
	  <tr valign=bottom>
		<td width=18%>Имя поля <nobr>по-английски</nobr></td>
		<td width=20%>Имя поля <nobr>по-русски</nobr></td>
		<td width=10%>Тип данных</td>
		<td width=15%>Важно/Обязательно</td>
		<td width=15%>Видимость</td>
		<td width=20%>Замена информации</td>
		<td width=2% align=center>-</td>
	  </tr>

	<tr id=pole><td width=18%><input type=text name=pole_name[] size=15 class='w100' /></td><td width=20%><input type=text name=pole_rusname[] size=15 class='w100' /></td><td width=10%><label><select name=pole_tip[] class='w100'><option value='строка' selected=selected>Строка (до 250 букв)</option><option value='строкабезвариантов'>Строка без выбора вариантов</option><option value='число'>Число</option><option value='список'>Список</option><option value='текст'>Текст</option><option value='дата'>Дата</option><option value='датавремя'>Дата-Время</option><option value='фото'>Фото</option><option value='минифото'>МиниФото</option><option value='файл'>Файл</option><option value='ссылка'>Ссылка</option></select></label></td><td width=15%><label><select name=pole_main[] class='w100'><option value=0>не важно</option><option value=1>основная категория</option><option value=2>вторичная категория</option><option value=3>обязательно заполнять</option><option value=4>не важно и не печатать</option><option value=6>не важно, не печатать и не показывать</option><option value=7>обязательно, не печатать и не показывать</option><option value=5>пустая для печати</option></select></label></td><td width=15%><label><select name=pole_open[] class='w100'><option value=0 selected=selected>видно везде</option><option value=1>не видно нигде</option><option value=2>видно только на странице</option><option value=3>видно только по паролю</option></select></label></td><td width=20%><label><input type=text name=pole_rename[] size=15 class='w100' /></label></td><td><!-- <a href=# class=dark_pole id=del>X</a> --></td></tr>

	</table>

	  <div id=id0></div>
	  
	<input type=hidden id=nu size=3 value='1'>
	<div align=right><a href=#pole class=dark_pole id=add_pole>Добавить поле в таблицу</a> (например: название, описание, количество или стоимость...)</div>

	<script type=text/javascript>
	$(document).ready(
	function(){
		$('#add_pole').click(function(){
		  $('#pole').clone().appendTo('#table');
		  var newValue = parseInt($('#nu').val()) + 1;
		  $('#nu').val(newValue);
		});
		$('#pole').click(function(){
			// $('#pole').closest(this).empty();
			// $(this).empty();
		});
	});
	</script>

	<br><b>Добавить поле Голосование (golos, англ.)?</b> <select name=add_pole_golos><option value='1'>да</option><option value='0' selected>нет</option></select>
	<br><b>Добавить поле Комментарии (comm, англ.)?</b> <select name=add_pole_comm><option value='1'>да</option><option value='0' selected>нет</option></select>
	<br><b>Добавить поле Количество проданного товара (kol, англ.)?</b> <select name=add_pole_kol><option value='1'>да</option><option value='0' selected>нет</option></select>
	<br><br>Если вы выбрали тип поля «Список» - в ячейке «Замена информации» напишите слова этого поля через запятую!
	<br><br>Поля id (порядковый номер) и active (состояние активности) будут добавлены к любой таблице автоматически.<br><br>
	<b>После создания базы данных, создайте Раздел для нее и подключите базу в настройках раздела вместо его страниц и папок.</b>
	<br><br>Готовые фильтры для англ. названий: data (дата), data2 (дата 2), men (менеджер), company (фирма)
	<input type=hidden name=id value=''>
	<input type=hidden name=op value=".$admintip."_save>
	</td></tr></table>";
	break;
	########################################################
	case "shablon": $type_opis = "шаблона (оформление раздела или блока)";
		$create.="<div id=about class=block style='display:none;'>Шаблоны используются для изменения внешнего вида разделов, страниц и блоков. Используются либо стандартные поля страниц, либо дополнительно созданные Поля. Для страниц можно использовать любой дизайн, блоки и разделы используют табличную основу — начало < table >, соответственно сами шаблоны должны начинаться с < tr > и заканчиваться на < /tr >. Для того, чтобы в шаблоне раздела предусмотреть возможность именования столбцов таблицы, например: Дата, Название, Ссылка... после самого шаблона раздела нужна написать ключевое слово [следующий] и написать шаблон именования столбцов, т.е. по сути скопировать шаблон строк раздела, но вместо заготовок автоматических вставок поставить в него названия соответствующих полей-столбцов.<br></div>
		<table width=100%><tr><td width=50%>
		<h2>Название шаблона</h2>
		<input type=text name=title size=40 class='w100 h40 f16' autofocus> (рус.)</td><td>
		<h2>Обращение:</h2>
		<input type=text name=namo size=40 class='w100 h40 f16'> (англ., без пробелов)</td></tr>
		<tr><td colspan=2>
		".help_shablon()."
		<h2>Содержание шаблона (HTML-код и вставки шаблона):</h2>
		<textarea name=text rows=15 cols=80 class='w100' style='height:350px;'></textarea>
		</td></tr></table>
		<input type=hidden name=id value=''>
		<input type=hidden name=type value='6'>
		<input type=hidden name=op value=".$admintip."_save>";
	break;
	}
	echo "<form method='POST' action='sys.php' style='display:inline;' enctype='multipart/form-data'>
	<div class=fon>
	<div class='black_grad h40'>
	<button type='button' style='float:right;margin:3px;' class='medium orange' onClick=\"show_animate('about')\">?</button>
	<button type=submit id=new_razdel_button class='small green' style='float:left; margin:3px;'><span class='mr-2 icon white medium' data-icon='c'></span>Сохранить</button>
	<span class='h1'>Добавление ".$type_opis."</span></div>".$create."
	</div>
	</form>";
}

#####################################################################################################################
function edit_main($id) {
	global $tip, $admintip, $prefix, $db, $red, $nastroi;
	echo "<div id=podrazdel></div>";
     $sql = "select type,name,title,text,useit,shablon,description,keywords from ".$prefix."_mainpage where id='".$id."'";
     // здесь учитываем и возможность редактирования удаленных и старых версий, поэтому нет «`tables`='pages'»
     $result = $db->sql_query($sql);
     $row = $db->sql_fetchrow($result);
     $type = $row['type']; 
     $name = $row['name']; 
     $title = $row['title'];
     $text = $row['text'];
     $useit = str_replace("  "," ",$row['useit']);
     $shablon = $row['shablon'];
	 $descriptionX = $row['description'];
	 $keywordsX = $row['keywords'];
	// Если это раздел
	if ($type == "3") { 
		$useit_module = explode("|",$useit); 
		$useit_module = $useit_module[0]; 
			if ($useit_module != "") {
				$sql = "select title from ".$prefix."_mainpage where `tables`='pages' and name='".$useit_module."' and type='2'";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$useit_module = trim($row['title']);
			}
	}
	if (!isset($useit_module)) $useit_module = "";
	$color = "#ffffff"; // убрать
	$type_opis = "";

	echo "<form method='POST' action=sys.php>
	<input type=hidden name=type value='".$type."'><input type=hidden name=id value='".$id."'><input type=hidden name=op value='".$admintip."_save'>";

	// Определение дизайнов // проверить - возможно заменить на функции из mainfile
	$design_var = array();
	$design_names = array();
	$result2 = $db->sql_query("select id, title from ".$prefix."_mainpage where `tables`='pages' and type='0'");
	while ($row2 = $db->sql_fetchrow($result2)) { $design_var[] = $row2['id']; $design_names[] = trim($row2['title']); }
	$design_var = implode(",",$design_var);
	$design_names = implode(",",$design_names);

	// Определение разделов
	$razdel_var = array();
	$razdel_names = array();
	$razdel_name = array();
	$razdel_engname = array();
	$result2 = $db->sql_query("select id, name, title from ".$prefix."_mainpage where `tables`='pages' and name!='users' and type='2' order by title");
	while ($row2 = $db->sql_fetchrow($result2)) { 
		$raz_id = $row2['id']; 
		$razdel_engname[] = $row2['name']; 
		$razdel_var[] = $row2['id']; 
		$razdel_names[$raz_id] = str_replace(","," ",trim($row2['title']));
	}
	$razdel_var = implode(",",$razdel_var);
	$razdel_name = implode(",",$razdel_names);
	$razdel_engname = implode(",",$razdel_engname);
	if (trim($razdel_var) != "") $razdel_var .= ","; else $razdel_var .= "0,"; 
	if (trim($razdel_engname) != "") $razdel_engname .= ","; else $razdel_engname .= "все,"; 
	if (trim($razdel_name) != "") $razdel_name .= ",";  else $razdel_name .= "нет,"; 

	// Определение шаблонов
	$shablon_var = array();
	$shablon_names = array();
	$result2 = $db->sql_query("select id, title from ".$prefix."_mainpage where `tables`='pages' and type='6'");
	while ($row2 = $db->sql_fetchrow($result2)) { $shablon_var[] = $row2['id']; $shablon_names[] = trim($row2['title']); }
	$shablon_var = implode(",",$shablon_var);
	$shablon_names = implode(",",$shablon_names);
	if (trim($shablon_var) != "") $shablon_var .= ","; 
	if (trim($shablon_names) != "") $shablon_names .= ","; 

	// Определение баз данных
	$base_var = array("0");
	$base_names = array("нет");
	$result2 = $db->sql_query("select id, title from ".$prefix."_mainpage where `tables`='pages' and type='5'");
	while ($row2 = $db->sql_fetchrow($result2)) { $base_var[] = $row2['id']; $base_names[] = trim($row2['title']); }
	$base_var = implode(",",$base_var);
	$base_names = implode(",",$base_names);

	// Определение полей
	$spisok_var = array("");
	$spisok_names = array("нет");
	$result2 = $db->sql_query("select name, title, useit from ".$prefix."_mainpage where `tables`='pages' and type='4'");
	while ($row2 = $db->sql_fetchrow($result2)) { 
		//if ($row2['useit']==0) $use_it = "все поля"; 
		//else { $use_it = $row2['useit']; $use_it = $razdel_names[$use_it]; }
		if ($row2['useit'] != 0) { 
			$use_it = $row2['useit']; 
			if ( isset($razdel_names[$use_it]) ) $use_it = $razdel_names[$use_it];
			else $use_it = "поле всех разделов";
		} else $use_it = "";
		$spisok_var[] = $row2['name']; 
		$spisok_names[] = trim($row2['title']." (".$use_it.")"); 
	}
	$spisok_var = implode(",",$spisok_var);
	$spisok_names = implode(",",$spisok_names);

	echo "<div class=fon>
	<div class='black_grad h40'>
	<button type=submit id=new_razdel_button class='medium green' style='float:left; margin:3px;'><span class='mr-2 icon white small' data-icon='c'></span>Сохранить</button>
	<span class='h1' style='padding-top:10px;'>";

	if ($type == "0") { ############################### ОТКРЫТИЕ ДИЗАЙН
		$useit_all = explode(" ", $useit); // разделение стилей
		$n = count($useit_all);
		$stil = "";
		$styles = "";
		for ($x=0; $x < $n; $x++) { // Определение использованных стилей в дизайне
			$stil .= " $useit_all[$x]";
		}
			 $sql5 = "select id, title from ".$prefix."_mainpage where `tables`='pages' and type='1' order by title, id";
			 $result5 = $db->sql_query($sql5);
			 while ($row5 = $db->sql_fetchrow($result5)) {
				 $title_id = $row5['id'];
				 $title_style = trim($row5['title']);
				 $sel="";
				 for ($x=0; $x < $n; $x++) {
					if ($useit_all[$x] == $title_id) $sel = " selected='selected'";
				 }
				 $styles .= "<option value='".$title_id."'".$sel.">".$title_style."</option>";
			 }
		global $http_siteurl;
		$stil = str_replace(" ","-",trim($stil));

	echo "Редактирование дизайна (HTML)</span>";
	if (intval($nastroi) != 1) red_vybor();
	echo "</div>

	<span class=h2>Название дизайна</span> <span class=f12>Видит только администратор</span><br>
	<textarea class='big w100 h40 f16' name='title' rows='1' cols='10'>".$title."</textarea>
	".help_design()."
	<span class=h2>Содержание дизайна (HTML):</span><span class=f12>[содержание] - блок вывода страниц.</span>";
	// [корзина] - общая корзина для всех разделов типа \"магазин\"<br>
  	redactor($red, $text, 'text'); // редактор: типа редактора, редактируемое поле

	echo "<span class=h2>Использованные в дизайне стили CSS</span> <span class=f12>Зажмите Ctrl для выбора нескольких стилей</span><br>
	<select name='useit[]' size=2 class=f12 multiple='multiple'>".$styles."</select></div>";
	} ############################### ЗАКРЫТИЕ ДИЗАЙН

	if ($type == "1") { ############################### ОТКРЫТИЕ СТИЛЬ

	echo "Редактирование стиля (CSS)</span></div>

	<h2>Название стиля <span class=f12>Видит только администратор</span><br>
	<textarea class='big w100 h40 f16' name='title' rows='1' cols='10'>".$title."</textarea></h2>

	<span class=h2>Содержание стиля</span>
	<textarea class='f12 h700 w100' id=text name='text' rows='30' cols='50'>".$text."</textarea>
	<input type='hidden' name='namo' value='".$name."'><br>";
	codemirror("css", "text");
	} ############################### ЗАКРЫТИЕ СТИЛЬ

	if ($type == "2") { ############################### ОТКРЫТИЕ РАЗДЕЛА

	############################################################################################################################
	if ($nastroi == 1) { // начало редактирования настроек раздела

	// выделим имени модуля раздела и настройки
	$options = explode("|",$text);
	$module_name = $options[0];
	$options = str_replace($module_name."|","",$text);

	// обнулили все опции от греха подальше
	$media=$folder=$col=$view=$golos=$golosrazdel=$post=$comments=$datashow=$favorites=$socialnetwork=$search=$search_papka=$put_in_blog=$base=$vetki=$citata=$media_comment=$no_html_in_opentext=$no_html_in_text=$show_add_post_on_first_page=$show_add_post_fileform=$razdel_shablon=$page_shablon=$comments_all=$comments_num=$comments_mail=$comments_adres=$comments_tel=$comments_desc=$golostype=$pagenumbers=$comments_main=$tags_type=$tema_zapret_comm=$pagekol=$table_light=$designpages=$comments_add=$div_or_table=0;

	$menushow=$titleshow=$razdel_link=$peopleshow=$design=$tags=$podrobno=$podrazdel_active_show=$podrazdel_show=$tipograf=$limkol=$tags_show=$tema_zapret=1;

	$comment_shablon=2;

	$where=$order=$calendar=$reclama="";

	$sort="date desc";
	$tema = "Открыть новую тему";
	$tema_name = "Ваше имя";
	$tema_title = "Название темы";
	$tema_opis = "Подробнее (содержание темы)";
	$comments_1 = "Комментарии";
	$comments_2 = "Оставьте ваш вопрос или комментарий:";
	$comments_3 = "Ваше имя:";
	$comments_4 = "Ваш e-mail:";
	$comments_5 = "Ваш адрес:";
	$comments_6 = "Ваш телефон:";
	$comments_7 = "Ваш вопрос или комментарий:";
	$comments_8 = "Раскрыть все комментарии";
	$tag_text_show = "Ключевые слова";
	$lim=20;

	parse_str($options); // раскладка всех настроек раздела

	echo "
	".input("nastroi", "1", "1", "hidden")."
	".input("module_name", $module_name, "1", "hidden")."

	Настройка раздела «".trim($title)."»</span></div>
	<p>Для настройки раздела внимательно прочитайте опции и выберите соответствующие варианты.</p><p class='red'>В текстовых полях нельзя писать символ &</p>

	<a class='dark_pole align_center' onclick=\"show_animate('block1');\"><h2>Дизайн (обрамление страниц)</h2>
	</a><div id=block1 style='display: none;'>
	<table width=100% class='table_light'>
	<tr>
	<td><b>Дизайн раздела</b> (по умолчанию - Главный дизайн) [<a href=sys.php?op=mainpage&amp;name=design target=_blank>Добавить дизайн</a>]</td>
	<td>".select("options[design]", $design_var, $design_names, $design)."</td>
	</tr>
	<tr>
	<td>Дизайн страниц (по умолчанию - такой же, как и дизайн раздела)</td>
	<td>".select("options[designpages]", "0,".$design_var, "как у Дизайна раздела,".$design_names, $designpages)."</td>
	</tr>
	</table>
	</div>

	<a class='dark_pole align_center' onclick=\"show_animate('block3');\"><h2>Шаблоны (расположение и наличие элементов)</h2>
	</a><div id=block3 style='display: none;'>
	<table width=100% class='table_light'>
	<tr>
	<tr>
	<td><b>Шаблон для всего раздела</b> (вывод заголовков страниц и т.д.)</td>
	<td>".select("options[razdel_shablon]", $shablon_var."0", $shablon_names."без шаблона", $razdel_shablon)."</td>
	</tr>
	<tr>
	<td><b>Формирование списка страниц</b> в разделе и папках. Разделение отдельных страниц</td>
	<td>".select("options[div_or_table]", "1,0", "Безтабличное DIV,Табличное TABLE TR TD", $div_or_table)."</td>
	</tr>
	<tr>
	<td><b>Тип раздела:</b><br>
	<i class=red>Для типа раздела «Анкеты-рейтинги» необходимо выбрать в настройках ниже возможность комментировать и голосовать за страницы.</i></td>
	<td>".select("options[view]", "4,1,6,0", "анкеты-рейтинги,форум,статьи (на главной - разделы),статьи (на главной - страницы)", $view)."</td>
	</tr>
	<td><b>Шаблон для комментариев</b> на странице  [<a href=sys.php?op=mainpage&amp;name=shablon target=_blank>Добавить</a>] (пока нет полной поддержки)</td>
	<td>".select("options[comment_shablon]", $shablon_var."2,1,0", $shablon_names."ДвижОк: ArtistStyle,ДвижОк: диалоговый аля Joomla,ДвижОк: стандартный", $comment_shablon)."</td>
	</tr>
	<tr>
	<td><b>Шаблон для страницы</b></td>
	<td>".select("options[page_shablon]", $shablon_var."0", $shablon_names."без шаблона", $page_shablon)."</td>
	</tr>
	</table>
	</div>


	<a class='dark_pole align_center' onclick=\"show_animate('block5');\"><h2>Раздел и папки</h2>
	</a><div id=block5 style='display: none;'>
	<table width=100% class='table_light'>
	<tr>
	<td>Количество колонок (столбцов) в списке страниц, выводимом в папках и начале раздела, <nobr>по-умолчанию: 1.</nobr></td>
	<td>".select("options[limkol]", "1,2,3,4,5,6,7,8,9,10", "1,2,3,4,5,6,7,8,9,10", $limkol)."</td>
	</tr>
	<tr>
	<td><strong>Количество строк</strong> в списке страниц, выводимом в папках и начале раздела, <nobr>по-умолчанию: 20.</nobr></td>
	<td>".select("options[lim]", "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,20,25,30,50,75,100,200,500,1000", "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,20,25,30,50,75,100,200,500,1000", $lim)."</td>
	</tr>
	<tr>
	<td>Выводить количество страниц в папках, <nobr>по-умолчанию: нет.</nobr></td>
	<td>".select("options[pagekol]", "1,0", "ДА,НЕТ", $pagekol)."</td>
	</tr>
	<tr>
	<td><strong>Выводить нумерацию страниц</strong>, <nobr>по-умолчанию: снизу.</nobr></td>
	<td>".select("options[pagenumbers]", "0,1,2", "снизу,сверху,блок [нумерация] в дизайне или разделе", $pagenumbers)."</td>
	</tr>
	<tr>
	<td><strong>Сортировка страниц</strong> в списке</td>
	<td>".select("options[sort]", "sort[|]date desc,date desc,date,title,open_text,counter,golos desc,comm,cid[|]title,cid[|]date desc,price,prise desc,mainpage desc,search desc,pid,open_text[|] golos", "по очередности (настраивается),по дате (с последнего),по дате (с первого),по названию (по алфавиту),по предисловию (по алфавиту),по кол-ву посещений страницы,по среднему баллу голосования,по кол-ву комментариев,по № папки и названию,по № папки и дате страницы,магазин: по цене (с мин.), магазин: по цене (с макс.), по важности (Главная стр.),по наличию ключ. слов,по № страницы,по предисловию и голосованию", $sort)."</td>
	</tr>
	<tr>
	<td>Первый блок рекламы (после Названия раздела). Можно написать HTML-код рекламы, вставить любой [блок]. Не использовать символ &</td>
	<td>".input("options[reclama]", $reclama, 60, "txt")."</td>
	</tr>
	<tr>
	<td>Добавление строки поиска в раздел (поиск по этому разделу)</td>
	<td>".select("options[search]", "2,1,0", "снизу,сверху,нет", $search)."</td>
	</tr>
	<tr>
	<td>Дополнительно: Внутренний поиск раздела будет искать</td>
	<td>".select("options[search_papka]", "1,0", "только по открытой папке раздела,по всему разделу", $search_papka)."</td>
	</tr>
	<tr>
	<td>Очищать предисловие от HTML-кода (оставить обычный текст)</td>
	<td>".select("options[no_html_in_opentext]", "1,0", "ДА,НЕТ", $no_html_in_opentext)."</td>
	</tr>
	<tr>
	<td>Очищать содержание от HTML-кода (оставить обычный текст)</td>
	<td>".select("options[no_html_in_text]", "1,0", "ДА,НЕТ", $no_html_in_text)."</td>
	</tr>

	<tr>
	<td><b>Преобразовывать таблицы</b> в «красивые» (добавлять class «table_light»)</td>
	<td>".select("options[table_light]", "1,0", "ДА,НЕТ", $table_light)."</td>
	</tr>

	<tr>
	<td>Показывать название раздела, папки и выбор подпапок</td>
	<td>".select("options[menushow]", "1,0", "ДА,НЕТ", $menushow)."</td>
	</tr>
	<tr>
	<td>Название раздела — </td>
	<td>".select("options[razdel_link]", "2,1,0", "не показывать,это ссылка на раздел,без ссылки", $razdel_link)."</td>
	</tr>
	<tr>
	<td>Показывать список папок открытого раздела или папки</td>
	<td>".select("options[podrazdel_show]", "3,2,1,0", "сверху и снизу,снизу (не реализовано!),сверху,нет", $podrazdel_show)."</td>
	</tr>
	<tr>
	<td>Показывать название открытой папки на страницах</td>
	<td>".select("options[podrazdel_active_show]", "3,2,4,1,0", "раскрывающийся список папок,выделять из списка подразделов,после названия раздела (разделитель Enter),после названия раздела (разделитель | ),нет", $podrazdel_active_show)."</td>
	</tr>
	</table>
	</div>



	<a class='dark_pole align_center' onclick=\"show_animate('block6');\"><h2>Ключевые слова (тэги)</h2>
	</a><div id=block6 style='display: none;'>
	<table width=100% class='table_light'>
	<tr>
	<td><strong>Где показывать ключевые слова?</strong></td>
	<td>".select("options[tags]", "3,2,5,6,4,7,1,0", "ВЕЗДЕ,в разделе и папках,в разделе (в разработке),в разделе и на страницах (в разработке),в папках (в разработке),в папках и на страницах (в разработке),на страницах,НИГДЕ", $tags)."</td>
	</tr>
	<tr>
	<td>Текст перед тэгами, например: «<b>Тэги</b> (ключевые слова)». Не использовать символ &.</td>
	<td>".input("options[tag_text_show]",$tag_text_show)."</td>
	</tr>
	<tr>
	<td><strong>Облако ключевых слов в разделе</strong> (наверху, после названия)</td>
	<td>".select("options[tags_type]", "3,2,1,0", "ИЗ текущей папки раздела,ИЗ текущего раздела,ИЗ всех разделов портала,НЕ ПОКАЗЫВАТЬ", $tags_type)."</td>
	</tr>
	<tr>
	<td>Облако ключевых слов в разделе показывать раскрытым?</td>
	<td>".select("options[tags_show]", "1,0", "ДА,НЕТ", $tags_show)."</td>
	</tr>
	</table>
	</div>


	<a class='dark_pole align_center' onclick=\"show_animate('block7');\"><h2>Голосование</h2>
	</a><div id=block7 style='display: none;'>
	<table width=100% class='table_light'>
	<tr>
	<td><strong>Показывать голосование на страницах?</strong></td>
	<td>".select("options[golos]", "1,0", "ДА,НЕТ", $golos)."</td>
	</tr>
	<tr>
	<td><strong>Показывать голосование в разделе</strong> (в списке страниц)<strong>?</strong></td>
	<td>".select("options[golosrazdel]", "1,0", "ДА,НЕТ", $golosrazdel)."</td>
	</tr>
	<tr>
	<td><strong>Тип голосования</strong>, по умолчанию: оценка (макс. 5 баллов)</td>
	<td>".select("options[golostype]", "0,1,2,3", "Оценка (макс. 5 баллов),Кнопка «Проголосовать»,Рейтинг (кнопки + и -),Рейтинг («Мне понравилось»)", $golostype)."</td>
	</tr>
	</table>
	</div>


	<a class='dark_pole align_center' onclick=\"show_animate('block8');\"><h2>Страницы в разделе</h2>
	</a><div id=block8 style='display: none;'>
	<table width=100% class='table_light'>
	<tr>
	<td>Показывать Название страницы?</td>
	<td>".select("options[titleshow]", "1,0", "ДА,НЕТ", $titleshow)."</td>
	</tr>
	<tr>
	<td><strong>Показывать Дату создания?</strong></td>
	<td>".select("options[datashow]", "1,0", "ДА,НЕТ", $datashow)."</td>
	</tr>
	<tr>
	<td><strong>Показывать Количество посещений?</strong></td>
	<td>".select("options[peopleshow]", "0,1", "НЕТ,ДА", $peopleshow)."</td>
	</tr>
	<tr>
	<td><strong>Показывать Добавление страниц в Интернет-закладки?</strong> <img src=/images/favorit.gif align=bottom></td>
	<td>".select("options[favorites]", "1,0", "ДА,НЕТ", $favorites)."</td>
	</tr>
	<tr>
	<td><strong>Показывать Добавление страниц в Социальные сети?</strong> (Вконтакте, Гугл+, МойМир и т.д.)</td>
	<td>".select("options[socialnetwork]", "1,0", "ДА,НЕТ", $socialnetwork)."</td>
	</tr>
	<tr>
	<td>Показывать Код для вставки в блоги? (Название и Предисловие страницы со ссылкой на сайт). При выборе варианта «с логотипом» небольшой логотип (small_logo.jpg) нужно разместить в корне сайта. Проще всего это будет сделать администратору (если у вас нет доступа к FTP серверу и достаточных знаний).</td>
	<td>".select("options[put_in_blog]", "2,1,0", "с логотипом,без логотипа,НЕТ", $put_in_blog)."</td>
	</tr>
	<tr bgcolor=#ddffdd>
	<td>При сохранении использовать типограф для Содержания и Предисловия</td>
	<td>".select("options[tipograf]", "1,0", "ДА,НЕТ", $tipograf)."</td>
	</tr>
	</table>
	</div>


	<a class='dark_pole align_center' onclick=\"show_animate('block9');\"><h2>Комментарии</h2>
	</a><div id=block9 style='display: none;'>
	<table width=100% class='table_light'>
	<tr>
	<td>Показывать Комментарии на страницах</td>
	<td>".select("options[comments]", "0,1", "НЕТ,ДА", $comments)."</td>
	</tr>
	<tr>
	<td>Показывать Комментарии на Главной странице Раздела</td>
	<td>".select("options[comments_main]", "1,0", "ДА,НЕТ", $comments_main)."</td>
	</tr>
	<tr>
	<td>Разрешить пользователям Добавлять комментарии на страницы?</td>
	<td>".select("options[comments_add]", "0,2,1", "НЕТ,с проверкой администратора,без проверки", $comments_add)."</td>
	</tr>
	<tr>
	<td>Включить ветки (возможность отвечать на комментарии на страницах со смещением ответа на комментарий вправо)</td>
	<td>".select("options[vetki]", "2,1,0", "ветки раскрыты,ветки закрыты и есть кнопка «Раскрыть все»,НЕТ", $vetki)."</td>
	</tr>
	<tr>
	<td>Направление комментариев по дате:</td>
	<td>".select("options[comments_desc]", "1,0", "сначала последние (новые),сначала первые (старые)", $comments_desc)."</td>
	</tr>
	<tr>
	<td>Поле «E-mail» показывать:</td>
	<td>".select("options[comments_mail]", "3,2,1,0", "везде,на странице в комментариях,в форме добавления комментария,НЕТ", $comments_mail)."</td>
	</tr>
	<tr>
	<td>Поле «Адрес» показывать:</td>
	<td>".select("options[comments_adres]", "3,2,1,0", "везде,на странице в комментариях,в форме добавления комментария,НЕТ", $comments_adres)."</td>
	</tr>
	<tr>
	<td>Поле «Телефон» показывать:</td>
	<td>".select("options[comments_tel]", "3,2,1,0", "везде,на странице в комментариях,в форме добавления комментария,НЕТ", $comments_tel)."</td>
	</tr>
	<tr>
	<td>Количество выводимых комментариев, <strong>0 - выводить все</strong>.</td>
	<td>".select("options[comments_num]", "0,1,2,3,4,5,10,15,20,25,30,50,75,100,200,500,1000", "0,1,2,3,4,5,10,15,20,25,30,50,75,100,200,500,1000", $comments_num)."</td>
	</tr>
	<tr>
	<td>Показывать кнопку раскрытия всех комментариев (если кол-во выводимых комм. больше 0, см. выше)</td>
	<td>".select("options[comments_all]", "1,0", "ДА,НЕТ", $comments_all)."</td>
	</tr>
	<tr>
	<td>Включить информацию о возможности помещения графики, музыки и видео в комментарии на страницах.</td>
	<td>".select("options[media_comment]", "1,0", "ДА,НЕТ", $media_comment)."</td>
	</tr>
	<tr>
	<td>Можно заменить надпись «Комментарии» на:</td>
	<td>".input("options[comments_1]", $comments_1)."</td>
	</tr>
	<tr>
	<td>Можно заменить надпись «Оставьте ваш вопрос или комментарий:» на:</td>
	<td>".input("options[comments_2]", $comments_2)."</td>
	</tr>
	<tr>
	<td>Можно заменить надпись «Ваше имя:» на:</td>
	<td>".input("options[comments_3]", $comments_3)."</td>
	</tr>
	<tr>
	<td>Можно заменить надпись «Ваш e-mail:» на:</td>
	<td>".input("options[comments_4]", $comments_4)."</td>
	</tr>
	<tr>
	<td>Можно заменить надпись «Ваш адрес:» на:</td>
	<td>".input("options[comments_5]", $comments_5)."</td>
	</tr>
	<tr>
	<td>Можно заменить надпись «Ваш телефон:» на:</td>
	<td>".input("options[comments_6]", $comments_6)."</td>
	</tr>
	<tr>
	<td>Можно заменить надпись «Ваш вопрос или комментарий:» на:</td>
	<td>".input("options[comments_7]", $comments_7)."</td>
	</tr>
	<tr>
	<td>Можно заменить надпись «Раскрыть все комментарии» на:</td>
	<td>".input("options[comments_8]", $comments_8)."</td>
	</tr>
	<tr bgcolor=#f79779>
	<td>Запретить размещать в комментарии ссылки?</td>
	<td>".select("options[tema_zapret_comm]", "2,1,0", "ЗАПРЕТИТЬ,УДАЛЯТЬ http:// в начале ссылки,РАЗРЕШИТЬ", $tema_zapret_comm)."</td>
	</tr>
	</table>
	</div>


	<a class='dark_pole align_center' onclick=\"show_animate('block10');\"><h2>Добавление страниц пользователем</h2>
	</a><div id=block10 style='display: none;'>
	<table width=100% class='table_light'>
	<tr>
	<td><b>Разрешить пользователям добавлять страницы</b> в раздел.<br>Выделение серым цветом позволяет сразу писать комментарии к добавленной странице, что особенно актуально для рейтингов.</td>
	<td>".select("options[post]", "3,2,1,0", "с выделением серым цветом,с проверкой администратора,без проверки,ЗАПРЕТИТЬ", $post)."</td>
	</tr>
	<tr>
	<td><b>Показывать на главной</b> странице раздела Добавление страницы.</td>
	<td>".select("options[show_add_post_on_first_page]", "1,0", "ДА,НЕТ", $show_add_post_on_first_page)."</td>
	</tr>
	<tr>
	<td>Показывать форму загрузки фото или файла.</td>
	<td>".select("options[show_add_post_fileform]", "1,0", "ДА,НЕТ", $show_add_post_fileform)."</td>
	</tr>
	<tr>
	<td>Замена названия кнопки «Открыть новую темую». Например: «Добавить новость», «Разместить статью», «Добавить фото», «Добавить организацию».</td>
	<td>".input("options[tema]", $tema)."</td>
	</tr>
	<tr>
	<td>Замена названия текстового поля «Ваше имя». Например: «Ваш ник», «Ваши Ф.И.О.», «Автор сообщения», «Адрес». Если написать «no» - это поле не будет отображаться.</td>
	<td>".input("options[tema_name]", $tema_name)."</td>
	</tr>
	<tr>
	<td>Замена названия текстового поля «Название темы». Например: «Заголовок статьи», «Название фото», «Название и/или № организации».</td>
	<td>".input("options[tema_title]", $tema_title)."</td>
	</tr>
	<tr>
	<td>Замена названия текстового поля «Подробнее (содержание темы)». Например: «Описание фото», «Текст статьи», «Сообщение». Если написать «no» - это поле не будет отображаться.</td>
	<td>".input("options[tema_opis]", $tema_opis)."</td>
	</tr>
	<tr bgcolor=#f79779>
	<td>Запретить размещать в тексте ссылки?</td>
	<td>".select("options[tema_zapret]", "2,1,0", "ЗАПРЕТИТЬ,УДАЛЯТЬ http:// в начале ссылки,РАЗРЕШИТЬ", $tema_zapret)."</td>
	</tr>
	</table>
	</div>


	<a class='dark_pole align_center' onclick=\"show_animate('block4');\"><h2>Дополнительные блоки</h2>
	</a><div id=block4 style='display: none;'>
	<table width=100% class='table_light'>
	<tr>
	<td>Добавление блока Календарь. Выберите поле, подключенное к данному разделу и содержащее период времени (две даты).</td>
	<td>".select("options[calendar]", $spisok_var, $spisok_names, $calendar)."</td>
	</tr>
	</table>
	</div>


	<a class='dark_pole align_center' onclick=\"show_animate('block2');\"><h2>Подключение базы данных вместо страниц</h2>
	</a><div id=block2 style='display: none;'>
	<table width=100% class='table_light'>
	<tr>
	<td><b>Подключить базу данных</b> вместо страниц и папок [<a href=sys.php?op=mainpage&amp;name=base target=_blank>Добавить</a>]</td>
	<td>".select("options[base]", $base_var, $base_names, $base)."</td>
	</tr>
	<tr>
	<td>Выводить ссылку Подробнее... </td>
	<td>".select("options[podrobno]", "1,0", "ДА,НЕТ", $podrobno)."</td>
	</tr>
	</table>
	</div>";

	/*
	<a class='dark_pole align_center' onclick=\"show_animate('block11');\"><h2 style='color:red;'>В разработке...</h2>
	</a><div id=block11 style='display: none;'>
	<table width=90% cellpadding=2 cellspacing=0>
	<tr bgcolor=#FF6666>
	<td>Тип информации в разделе (по умолчанию - текст)</td>
	<td>".select("options[media]", "4,3,2,1,0", "музыка,видео,flash,фотогалерея,текст", $media)."</td>
	</tr>
	</table>
	</div>";
	*/
	############################################################################################################################
	} else { // конец редактирования настроек раздела
	// начало редактирования раздела
		
		echo "Редактирование раздела</span>";
		if (intval($nastroi) != 1) red_vybor();
		echo "</div>

		<table width='100%' border='0'><tr valign='top'><td width='50%'>
		<span class=h2>Название раздела</span><br>
		<textarea class='big w100 h40 f16' name='title' rows='2' cols='10'>".$title."</textarea>
		</td><td>
		<span class=h2>Адрес раздела на сайте</span><br>
		<textarea class='big w100 h40 f16' name='namo' rows='1' cols='10'>".$name."</textarea>
		<span class='red f12'>НЕ изменять!</span> <span class=f12>Ссылка: <a href=/-".$name." target=_blank>/-".$name."</a></span>
		</td></tr></table>
		<table width='100%' border='0'><tr valign='top'><td width='50%'>
		<span class=h2>Ключевые слова:</span> <span class=f12><a onclick=\"show('help5')\" class=help>?</a></span><br><textarea name='keywordsX' class='big w100 h40' rows='2' cols='10'>".$keywordsX."</textarea>
		<div id='help5' style='display:none;' class=f12>Это поле — для поисковых систем. Максимум 250 символов. Разделять запятой. Если пусто - используются ключевые словосочетания из <a href=/sys.php?op=Configure target=_blank>Настроек портала</a>).<br></div>
		</td><td>
		<span class=h2>Описание:</span> <span class=f12><a onclick=\"show('help6')\" class=help>?</a></span><br><textarea name='descriptionX' class='big w100 h40' rows='2' cols='10'>".$descriptionX."</textarea>
		<div id='help6' style='display:none;' class=f12>Это поле — для поисковых систем. Максимум 250 символов. Если пусто - используется основное описание из <a href=/sys.php?op=Configure target=_blank>Настроек портала</a>.</div>
		</td></tr></table>

		<span class=h2>Содержание раздела:</span><br>";
	  	redactor($red, $useit, 'useit', 'shablon'); // редактор: типа редактора, редактируемое поле

		echo "<br><input type=hidden name=text value='".$text."'>";
		if ($id != 24) {
			echo "<span class=h2>Шаблон Предисловия и Содержания страниц</span>
			<br>Если у большинства страниц раздела особенный дизайн или какое-то первоначальное содержание для всех страниц раздела одинаково — его можно прописать ниже как Шаблон для страниц. Сначала идет шаблон для Предисловия страниц, затем — для Содержания, разделяются они служебным словом [следующий]. Если нужен только шаблон для Предисловия - слово [следующий] можно не писать, а если нужен только шаблон для Содержания - слово [следующий] надо написать перед ним.<br>";
			redactor2($red, $shablon, 'shablon');
		} else {
			echo "<input type=hidden name=shablon value='".$shablon."'>";
		}
	} // конец редактирования раздела
		echo "<br><br><br>";
} ############################### ЗАКРЫТИЕ РАЗДЕЛА

	if ($type == "3") { ############################### ОТКРЫТИЕ БЛОК
	global $nastroi;
	if (intval($nastroi) == 1) { // начало редактирования настроек блока

	// выделим имени модуля раздела и настройки
	$options = explode("|",$useit);
	$module_name = $options[0];
	$options = str_replace($module_name."|","",$useit);

	// обнулили все опции от греха подальше
	$titleshow=$media=$folder=$datashow=$tagdelete=$ipdatauser=$design=$open_all=$catshow=$main=$daleeshow=$openshow=$number=$add=$size=$papki_numbers=$zagolovokin=$menu=$notitlelink=$noli=$html=$show_title=$random=$showlinks=$open_new_window=$shablon=$show_new_pages=$reload_link_show=$reload_link_time=0;
	$opros_type=$limkol=$pageshow=$only_question=$opros_result=$foto_gallery_type=1;
	$addtitle="Добавить статью";
	$dal="Далее...";
	$first = "src=";
	$second = ">";
	$third = " ";
	$col_bukv = 50;
	$img_width = 0;
	$img_height = 200;
	$size=10;
	$class=$alternative_title_link=$cid_open=$no_show_in_razdel=$watermark=$show_in_papka="";
	$sort = "date desc";
	$papka_sort = "sort, title";
	$razdel_open_name = "Открыть раздел"; //
	$razdel_open2_name = "Открыть раздел";
	$calendar = ""; // Календарь - перенаправление на дату из поля.
	$show_in_razdel = "все";
	$reload_link_text = "Показать еще...";

	// Для базы данных
	$base = ""; // Указываем название таблицы БД
	$first = ""; // первая колонка
	$second = ""; // вторая колонка
	$text = ""; // текст самой первой ячейки (для блока БД количество по 2 колонкам)
	$direct = "vert"; // направление, gor - горизонт., vert - вертикальное
	$all = 0; // Указывать сколько всего элементов, по умолчанию 0 - не указывать, 1 - указывать.
	$col = ""; // какие поля будут использоваться для вывода информации

	parse_str($options); // раскладка всех настроек блока

	echo "Настройка блока «".$title."»</span>";
	echo "</div>
	<span class=f12>Для настройки блока внимательно прочитайте опции и выберите соответствующие варианты.</span><br>
	<span class='red f12'>В текстовых полях нельзя писать символ &</span><br>
	".input("nastroi", "1", "1", "hidden")."
	<h2>Общие настройки блока:</h2>
	<table width=100% class=table_light>";
	if ($name == 4 or $name == 0 or $name == 1 or $name == 13 or $name == 11 or $name == 9 or $name == 30) {
	echo "<tr>
	<td><b>Блок использует содержание Раздела:</b><br>Всех разделов – оставьте поле пустым, определенного раздела — выберите этот раздел и нажмите «Добавить», нескольких разделов — добавьте несколько разделов через запятую.</td>
	<td>".input("options[module_name]", $module_name, "25","input"," id='add_razdel'")." 
<a class='button small' onclick='add_raz();'>&larr; Добавить</a><br>
".select("razdels", $razdel_engname."", $razdel_name."ко всем Разделам", "", " id='razdels'")."
<script>function add_raz() { if ($('#razdels').val() != '') $('#add_razdel').val( $('#add_razdel').val() + $('#razdels').val() + ',' ); }</script>
	</td></tr>";
	}
	if ($name == 22 or $name == 23) {
	echo "<tr>
	<td>Блок подключен к Базе данных</td>
	<td>".select("options[module_name]", $razdel_var."", $razdel_name."не подключен", $module_name)."</td>
	</tr>";
	}
	echo "<tr>
	<td>Дизайн блока (по умолчанию - нет). Окружает блок заранее созданным Дизайном (оформлением) для этого блока и подключает стиль CSS из дизайна</td>
	<td>".select("options[design]", $design_var.",0", $design_names.",нет", $design)."</td>
	</tr>";
	echo "<tr>
	<td>Показывать блок только в определенном разделе (по умолчанию - нет)</td>
	<td>".select("options[show_in_razdel]", $razdel_engname."все", $razdel_name."нет", $show_in_razdel)."</td>
	</tr>";
	echo "<tr>
	<td>Показывать блок только в определенной папке (указывается цифра — номер папки, по умолчанию - нет)</td>
	<td>".input("options[show_in_papka]", $show_in_papka)."</td>
	</tr>";
	echo "<tr>
	<td>Не показывать блок в определенном разделе (по умолчанию - нет)</td>
	<td>".select("options[no_show_in_razdel]", $razdel_engname."", $razdel_name."нет", $no_show_in_razdel)."</td>
	</tr>";
	echo "<tr>
	<td>Использовать только содержание блока, без внутреннего оформления и заголовка</td>
	<td>".select("options[html]", "1,0", "ДА,НЕТ", $html)."</td>
	</tr>";
	echo "<tr>
	<td><b>Заголовок блока</b></td>
	<td>".select("options[titleshow]", "2,1,0", "внутри предисловия как блок [заголовок],показывать,не показывать", $titleshow)."</td>
	</tr>";
	////////////////
	if ($name==0 or $name==1 or $name==3 or $name==4 or $name==6 or $name==8 or $name==9 or $name==10 or $name==13) echo "</table><br><br><h2>Настройка данного типа блока:</h2><table width=100% class='table_light'>";
	

	if ($name == 3) {
	echo "<tr>
	<td>Время автоматического обновления блока:</td>
	<td>".select("options[reload_link_time]", "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,30,50,60,120,180,240,300,600", "ВЫКЛЮЧЕНО,1 сек.,2,3,4,5 сек.,6,7,8,9,10 сек.,11,12,13,14,15 сек.,16,17,18,19,20 сек.,25,30,50 сек.,1 мин.,2 мин.,3 мин.,4 мин.,5 мин.,10 мин.", $reload_link_time)."</td>
	</tr>";

	echo "<tr>
	<td>Показывать ссылку для обновления блока:</td>
	<td>".select("options[reload_link_show]", "2,1,0", "после блока,до блока,НЕ показывать", $reload_link_show)."</td>
	</tr>";

	echo "<tr>
	<td>Текст ссылки обновления блока:</td>
	<td>".input("options[reload_link_text]", $reload_link_text)."</td>
	</tr>";
	}

	if ($name == 0 or $name == 1 or $name == 9) {
	echo "<tr>
	<td>Использовать <b>англ. название</b> Поля для назначения стиля всему блоку, например для того, чтобы цвет блока менялся в зависимости от выбора цвета из Поля страницы. Каждый выбранный стиль задается в Главном стиле CSS</td>
	<td>".input("options[class]", $class)."</td>
	</tr>";
	}

	if ($name == 0 or $name == 8) {
	echo "<tr>
	<td>Прикрепить шаблон к блоку (поиск по этому разделу)</td>
	<td>".select("options[shablon]", $shablon_var."0", $shablon_names."без шаблона", $shablon)."</td>
	</tr>";
	}

	if ($name == 5) {
	echo "<tr>
	<td>Тип ответов опроса:</td>
	<td>".select("options[opros_type]", "1,0", "одиночный выбор (кружки),множественный выбор (флажки) - НЕ работает", $opros_type)."</td>
	</tr>";

	echo "<tr>
	<td>Результаты опроса:</td>
	<td>".select("options[opros_result]", "2,1,0", "показывать после ответа,показывать сразу (ссылка),видит только Администратор", $opros_result)."</td>
	</tr>";
	}

	if ($name == 4 or $name == 8) {
	echo "<tr>
	<td>Показывать кол-во страниц в скобках после названия папки</td>
	<td>".select("options[papki_numbers]", "1,0", "ДА,НЕТ", $papki_numbers)."</td>
	</tr>";
	echo "<tr>
	<td>Сортировка папок по:</td>
	<td>".select("options[papka_sort]", "title,description,sort[|]title,counter[|]title,parent_id[|]title,cid,cid desc,module[|]title", "названию (по алфавиту),описанию,сортировке,кол-ву посещений,принадлежности к другим папкам,№ папки (с первой),№ папки (с последней),англ. названию раздела", $papka_sort)."</td>
	</tr>";
	}

	if ($name == 0 or $name == 1 or $name == 4 or $name == 6 or $name == 8 or $name == 9) {
	echo "<tr>
	<td>Убрать ссылку на раздел в заголовке блока</td>
	<td>".select("options[notitlelink]", "1,0", "ДА,НЕТ", $notitlelink)."</td>
	</tr>";
	echo "<tr>
	<td>Ссылка заголовка блока: заменить ссылку заголовка на любую другую</td>
	<td>".input("options[alternative_title_link]", $alternative_title_link)."</td>
	</tr>";
	}

	if ($name == 9) {
	echo "<tr>
	<td>Указание на первый параметр тега или элемент, после которого следует ссылка на изображение, по-умолчанию: <b>src=</b></td>
	<td>".input("options[first]", $first)."</td>
	</tr>";
	echo "<tr>
	<td>Указание на второй параметр тега или элемент, до которого была ссылка на изображение, по-умолчанию: <b>></b></td>
	<td>".input("options[second]", $second)."</td>
	</tr>";
	echo "<tr>
	<td>Указание на третий параметр тега или элемент, до которого была ссылка на изображение, по-умолчанию: <b>пробел</b></td>
	<td>".input("options[third]", $third)."</td>
	</tr>";
	echo "<tr>
	<td>Показывать заголовок страницы:</td>
	<td>".select("options[show_title]", "2,1,0", "перед фото,под фото,нет", $show_title)."</td>
	</tr>";
	}

	if ($name == 9 or $name == 6) {
	echo "<tr>
	<td><b>Размер картинки в пикселях:</b><br>
	<li>по горизонтали, по-умолчанию: 0. Если указать 0 — будет считаться по вертикали</td>
	<td><br>".input("options[img_width]", $img_width)."</td>
	</tr>";
	echo "<tr>
	<td><li>по вертикали, по-умолчанию: 100. Если указать 0 — будет считаться по горизонтали</td>
	<td>".input("options[img_height]", $img_height)."</td>
	</tr>";
	}

	if ($name == 6) {
	echo "<tr>
	<td>Водяной знак (наложение на фотографии, ссылка на изображение)</td>
	<td>".input("options[watermark]", $watermark)."</td>
	</tr>";
	echo "<tr>
	<td><b>Тип галереи фотографий:</b></td>
	<td>".select("options[foto_gallery_type]", "1,0", "миниатюры одна за другой,«карусель» — большая и миниатюры", $foto_gallery_type)."</td>
	</tr>";
	}

	if ($name == 22 or $name == 23) {
	echo "<tr>
	<td>Название таблицы Базы данных, обязательно</td>
	<td>".select("options[base]", $base_var, $base_names, $base)."</td>
	</tr>";
	echo "<tr>
	<td>Первая по важности колонка: </td>
	<td>".input("options[first]", $first)."</td>
	</tr>";
	echo "<tr>
	<td>Вторая по важности колонка: </td>
	<td>".input("options[second]", $second)."</td>
	</tr>";
	if ($name == 22) echo "<tr>
	<td>Можно поменять текст самой первой ячейки, по-умолчанию пустой</td>
	<td>".input("options[text]", $text)."</td>
	</tr>";
	echo "<tr>
	<td>Направление вывода информации</td>
	<td>".select("options[direct]", "gor,vert", "горизонтальное (в разработке),вертикальное", $direct)."</td>
	</tr>";
	echo "<tr>
	<td>Сколько сего элементов</td>
	<td>".select("options[all]", "0,1", "не указывать,указывать", $all)."</td>
	</tr>";
	echo "<tr>
	<td>Использованные поля, через запятую</td>
	<td>".input("options[col]", $col)."</td>
	</tr>";
	}

	if ($name == 23) {
	echo "<tr>
	<td>Сортировка страниц в списке (укажите англ. название поля и « desc» для обратной сортировки, можно указывать несколько значений через запятую): </td>
	<td>".input("options[sort]", $sort)."</td>
	</tr>";
	}

	if ($name == 0 or $name == 9) {
	echo "<tr>
	<td>Сортировка страниц в списке по: </td>
	<td>".select("options[sort]", "sort[|]date desc,date desc,date,title,open_text,counter,golos desc,comm,cid[|]title,cid[|]date desc,price,prise desc,mainpage desc,search desc,pid,open_text[|]golos", "по очередности (настраивается),дате (с последнего),дате (с первого),названию (по алфавиту),предисловию (по алфавиту),кол-ву посещений страницы,среднему баллу голосования,кол-ву комментариев,№ папки и названию,№ папки и дате страницы,цене (с мин.) магазин,цене (с макс.) магазин,важности (Главная стр.),наличию ключ. слов,№ страницы,предисловию и голосованию", $sort)."</td>
	</tr>";

	echo "<tr>
	<td>Показывать Предисловие страницы под ее названием-ссылкой</td>
	<td>".select("options[openshow]", "2,1,0", "да и без ссылки,да и со ссылкой,нет", $openshow)."</td>
	</tr>";
	}
	if ($name == 0) {
	echo "<tr>
	<td>Показывать Название страницы внутри предисловия страницы за счет использования автоматического блока [заголовок]. Если [заголовок] не будет упомянут в Предисловии страницы, ее Название не будет показано вообще.</td>
	<td>".select("options[zagolovokin]", "1,0", "ДА,НЕТ", $zagolovokin)."</td>
	</tr>";

	echo "<tr>
	<td>Показывать ссылку «Далее»</td>
	<td>".select("options[daleeshow]", "1,0", "ДА,НЕТ", $daleeshow)."</td>
	</tr>";
	echo "<tr>
	<td>Заменить надпись на ссылке «Далее». Можно писать HTML-код, например вставить таким образом картинку вместо текстовой ссылки.</td>
	<td>".input("options[dal]", $dal)."</td>
	</tr>";

	echo "<tr>
	<td>Показывать ссылку «Открыть все»</td>
	<td>".select("options[open_all]", "1,0", "ДА,НЕТ", $open_all)."</td>
	</tr>";

	echo "<tr>
	<td>Заменить надпись на ссылке «Открыть раздел» справа от названия блока. Можно убрать эту ссылку, полностью стерев ее название.</td>
	<td>".input("options[razdel_open_name]", $razdel_open_name)."</td>
	</tr>";
	echo "<tr>
	<td>Заменить надпись на ссылке «Открыть раздел» внизу блока. Можно убрать эту ссылку, полностью стерев ее название.</td>
	<td>".input("options[razdel_open2_name]", $razdel_open2_name)."</td>
	</tr>";

	echo "<tr>
	<td>Вырезать теги из названия и предисловия страницы, чтобы исключить изменение размеров и жирности текста.</td>
	<td>".select("options[tagdelete]", "1,0", "ДА,НЕТ", $tagdelete)."</td>
	</tr>";

	echo "<tr>
	<td>Учитывать дату последнего посещения страниц посетителем при сортировке по дате, т.е. показывать только те страницы, которые были созданы после последнего посещения</td>
	<td>".select("options[ipdatauser]", "1,0", "ДА,НЕТ", $ipdatauser)."</td>
	</tr>";

	echo "<tr>
	<td>Показывать дату перед названием страницы</td>
	<td>".select("options[datashow]", "1,0", "ДА,НЕТ", $datashow)."</td>
	</tr>";

	echo "<tr>
	<td>Показывать название папки перед названием страницы</td>
	<td>".select("options[catshow]", "1,0", "ДА,НЕТ", $catshow)."</td>
	</tr>";

	echo "<tr>
	<td>Открывать ссылки в новом окне?</td>
	<td>".select("options[open_new_window]", "1,0", "ДА,НЕТ", $open_new_window)."</td>
	</tr>";

	echo "<tr>
	<td>Показывать только свежие страницы (на основании COOKIE)</td>
	<td>".select("options[show_new_pages]", "1,0", "ДА,НЕТ", $show_new_pages)."</td>
	</tr>";
	}

	if ($name == 1) {
	echo "<tr>
	<td>Сортировка комментариев в списке</td>
	<td>".select("options[sort]", "data desc,data,avtor,text,golos,num[|]title,num[|]data desc,pid", "дате (с последнего),дате (с первого),имени автора (по алфавиту),тексту комментария (по алфавиту),кол-ву голосов,№ страницы и названию,№ страницы и дате комментария,№ комментария", $sort)."</td>
	</tr>";
	echo "<tr>
	<td>Кол-во выводимых в блок ссылок на комментарии</td>
	<td>".select("options[size]", "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,30,50,75,100,200,500,1000", "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,30,50,75,100,200,500,1000", $size)."</td>
	</tr>";
	echo "<tr>
	<td>C какого комментария начинать вывод? Укажите его номер в выводимой очередности. Используется для создания нескольких блоков, содержащих определенное кол-во выводимых ссылок на комментарии: например, первый начинается с 1 по 5, а второй с 6 по 10. Если разместить эти блоки рядом — получатся 2 колонки.</td>
	<td>".select("options[number]", "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,24,29,49,74,99,199,499,999", "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,30,50,75,100,200,500,1000", $number)."</td>
	</tr>";
	echo "<tr>
	<td>Какие комментарии отображать в блоке? Пояснение: ответы — это комментарии к другому комментарию, с отступом вправо по дереву.</td>
	<td>".select("options[only_question]", "2,1,0", "только ответы,только вопросы,ВСЕ", $only_question)."</td>
	</tr>";
	echo "<tr>
	<td>Кол-во выводимых символов в тексте комментария, до которых он урезается. Эта настройка нужна для того, чтобы не выводить в блок слишком длинные комментарии, урезать их, заканчивая троеточием.</td>
	<td>".select("options[col_bukv]", "10,15,20,25,30,50,75,100,200,500,1000", "10,15,20,25,30,50,75,100,200,500,1000", $col_bukv)."</td>
	</tr>";
	echo "<tr>
	<td>Показывать название страницы, к которой относится комментарий?</td>
	<td>".select("options[pageshow]", "0,1", "нет, да", $pageshow)."</td>
	</tr>";
	}

	if ($name == 0 or $name == 9) {
	echo "<tr>
	<td>Показывать страницы не из всего раздела, а только из одной определенной папки: указать номер папки. Чтобы узнать номер папки — откройте ее на сайте, в строке адреса страницы — это последний номер. По умолчанию параметр равен пустоте.</td>
	<td>".input("options[cid_open]", $cid_open)."</td>
	</tr>";

	echo "<tr>
	<td>Страницы, отмеченные галочкой «Ставить на главную страницу»:</td>
	<td>".select("options[main]", "2,1,0", "не показывать в блоке,показывать только их,показывать все страницы", $main)."</td>
	</tr>";
	}

	if ($name == 0 or $name == 9 or $name == 23) {
	echo "<tr>
	<td>C какой страницы начинать вывод? Укажите ее номер в выводимой очередности. Используется для создания нескольких блоков, содержащих определенное кол-во выводимых ссылок на страницы: например, первый начинается с 1 по 5, а второй с 6 по 10. Если разместить эти блоки рядом — получатся 2 колонки.</td>
	<td>".input("options[number]", $number)."</td>
	</tr>";
	}

	if ($name == 0 or $name == 1 or $name == 9) {
	echo "<tr>
	<td>Включить режим «Вразноброс», т.е. при каждом обновлении страницы будут показываться разные результаты:</td>
	<td>".select("options[random]", "1,0", "ДА,НЕТ", $random)."</td>
	</tr>";
	}

	if ($name == 0 or $name == 9 or $name == 13 or $name == 23) {
	echo "<tr>
	<td>Кол-во выводимых в блок ссылок на страницы (строк)</td>
	<td>".input("options[size]", $size)."</td>
	</tr>";
	}

	if ($name == 0 or $name == 9) {
	echo "<tr>
	<td>Количество колонок (столбцов) в списке страниц, <nobr>по-умолчанию: 1.</nobr></td>
	<td>".select("options[limkol]", "1,2,3,4,5,6,7,8,9,10", "1,2,3,4,5,6,7,8,9,10", $limkol)."</td>
	</tr>";

	echo "<tr>
	<td>Показывать ссылки-кнопки на следующие страницы?</td>
	<td>".select("options[showlinks]", "3,2,1,0", "сверху и снизу,снизу,сверху,не показывать", $showlinks)."</td>
	</tr>";
	}

	if ($name == 10) {
	echo "<tr>
	<td>Тип меню:</td>
	<td>".select("options[menu]", "5,2,6,3,1,0,4,7,8,9", "вертикальное 1 уровень,вертикальное 2 уровня (не желательно),вертикальное 3 уровня,горизонтальное 1 уровень (слева),горизонтальное 1 уровень (по ширине 100%),горизонтальное 3 уровня (слева),горизонтальное 3 уровня (слева[|] открывается вверх),KickStart вертикальное 3 уровня (слева),KickStart вертикальное 3 уровня (справа),KickStart горизонтальное 3 уровня (слева)", $menu)."</td>
	</tr>";
	}


	/////////////////
	if ($name == 0) {
		echo "<tr bgcolor=#FF6666>
		<td>Показывать ссылку на добавление страницы пользователем (в разработке)</td>
		<td>".select("options[add]", "1,0", "ДА,НЕТ", $add)."</td>
		</tr>
		<tr bgcolor=#FF6666>
		<td>Изменить надпись ссылки на добавление страницы пользователем (в разработке)</td>
		<td>".input("options[addtitle]", $addtitle)."</td>
		</tr>
		<tr bgcolor=#FF6666 class=hide>
		<td>Использование медиа (по умолчанию - нет)</td>
		<td>".select("options[media]", "4,3,2,1,0", "музыка,видео,flash,фото,нет", $media)."</td>
		</tr>
		<tr bgcolor=#FF6666 class=hide>
		<td>Дополнительно: папка с медиа-файлами (по умолчанию - нет)</td>
		<td>".input("options[folder]", $folder)."</td>
		</tr>";
	}
	echo "</table>";

	/////////////////////////////////////////////////////////
	} else { // редактирование содержания блока
		
	echo input("namo", $name, "1", "hidden")."
	Редактирование содержания блока</span>";
	if (intval($nastroi) != 1) red_vybor();
	echo "</div>

	<table width='100%' border='0'><tr valign='top'><td width='50%'>
	<span class=h2>Название блока</span><br>
	<textarea class='big w100 h40 f16' name='title' rows='1' cols='10'>".$title."</textarea>
	</td><td>
	<span class=h3>Название класса CSS</span><br>
	<textarea class='big w100 h40 f16' name='shablon' rows='1' cols='10'>".$shablon."</textarea>
	</td></tr>
	<tr><td colspan=2>";

	if ($name == 3) echo "<b>Разделение кусков рекламы или текста — с помощью символа | </b>";
	if ($name == 7) echo "<b>Вывод на экран из блока с PHP-кодом осуществляется через переменную $"."txt</b>";

	if ($name == 10 or $name == 5 or $name == 7 or $name == 0) $red = 1; // дополнить список при необходимости

	echo "<span class=h2>Содержание блока:</span><br>";
	redactor($red, $text, 'text'); // редактор: типа редактора, редактируемое поле

	echo "<div class='dark_pole' onclick=\"show('nastroi')\"><img class='icon2 i26' src='/images/1.gif'>Настройки (для импорта/экспорта)</div>
	<div id='nastroi' style='display: none;'>
	<br><span class=f12><a target='_blank' href=sys.php?op=mainpage&amp;type=3&amp;id=".$id."&nastroi=1>Перейти к визуальной настройке</a> &rarr;</span><br>
	<textarea class='f12 w100' name='useit' rows='2' cols='10'>".$useit."</textarea></div>

	</td></tr></table>";

	if ($name == 6) echo "</form>".add_file_upload_form();

	if ($name == 10) echo "<div class='dark_pole' style='float:right;' onclick=\"show('primer')\">
	    <img class='icon2 i26' src='/images/1.gif'>Пример построения меню сайта (справка)</div>
	    <div id='primer' style='display: none;'><pre>
	# - это ссылки на страницы.

	[элемент открыть][url=/]Главная[/url][элемент закрыть]
	[элемент открыть][url=#]Справочная[/url] 
	  [уровень открыть] 
	  [элемент открыть][url=#] 1[/url][элемент закрыть]
	  [элемент открыть][url=#] 2[/url][элемент закрыть]
	  [уровень закрыть] 
	[элемент закрыть]
	[элемент открыть][url=#]Афиша[/url][элемент закрыть]
	</pre></div>";
	}
	} ############################### ЗАКРЫТИЕ БЛОК



	if ($type == "4") { ############################### ОТКРЫТИЕ ПОЛЕ
	// определение названия использованного раздела
	$sql = "select title from ".$prefix."_mainpage where `tables`='pages' and id='".$useit."'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$main_design_title = trim($row['title']);
	if ($main_design_title!="") $main_design_title = $main_design_title." (выбрано)";
	else $main_design_title = "все разделы";
	// Определение списка названий разделов
	$modules = "";
	$sql = "select id, title from ".$prefix."_mainpage where `tables`='pages' and type='2' and name!='index'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
	$id_modul = trim($row['id']);
	$title_modul = trim($row['title']);
	$modules .= "<option value='".$id_modul."'>".$title_modul."</option>";
	}
	// Добавлены пользователи USERAD
	$sql3 = "select * from ".$prefix."_users_group where `group`!='rigion' and `group`!='config' and `group`!='obl'";
	$result3 = $db->sql_query($sql3);
	while ($row3 = $db->sql_fetchrow($result3)) {
		$id = "1,".$row3['id'];
		$title_group = $row3['group'];
		$modules .= "<option value='".$id."'>Группа пользователей: $title_group</option>";
	}
	// Добавлены пользователи USERAD
	
	echo "Редактирование поля</span></div>
	<table width=100%><tr><td width=50%>
	<h2>Название поля<br>
	<textarea class='big w100 h40 f16' name='title' rows='1' cols='10'>".$title."</textarea></h2>
	</td><td>
	<h2>Обращение (англ., без пробелов)<br>
	<textarea class='big w100 h40 f16' name='namo' rows='1' cols='10'>".$name."</textarea></h2>
	Используется в шаблонах для подключения вывода этого поля, пример: pole &rarr; [pole]
	</td></tr><tr><td>
	<h2>Принадлежит разделу:<br>
	<select name='useit' class='w100'><option value='0'>все разделы</option><option value='".$useit."' selected>".$main_design_title."</option>".$modules."</select></h2>
	</td><td>
	<h2>Параметры поля:<br>
	<textarea name='text' rows='1' cols='100' class='w100'>".$text."</textarea></h2>
	</td></tr></table>";

	} ############################### ЗАКРЫТИЕ ПОЛЕ

	if ($type == "5") { ############################### ОТКРЫТИЕ БАЗА ДАННЫХ
	echo "Редактирование базы данных (таблицы)</span>";
	echo "</div>
	<table width=100%><tr><td width=50%>
	<table width=90%><tr><td align=right><b>База данных:</b></td><td></td></tr>
	<tr><td align=right><b>Название:</b></td><td style='background-color: ".$color.";'><input type='text' name='title' value='".$title."' size=60></td></tr></table>
	</td></tr></table>
	<input type='hidden' name='namo' value='".$name."'>
	<h2>Содержание базы данных</h2>
	<textarea name=text rows=15 cols=40 style='width:100%; height:250px;'>".$text."</textarea><br>
	<b>Разделитель полей:</b> /!/ <br>
	<b>Формат полей:</b> Англ название#!#Рус название#!#Тип данных#!#Важно#!#Видимость#!#Замена <br>
	<b>Тип данных:</b> строка, строкабезвариантов, число, список, текст, дата, датавремя, фото, минифото, файл, ссылка<br>
	<b>Важно:</b> 0 (не важно), 1 (основная категория), 2 (вторичная категория), 3 (обязательно заполнять), 4 (не важно и не печатать), 5 (пустая для печати), 6 (не важно, не печатать и не показывать), 7 (обязательно, не печатать и не показывать)<br>
	<b>Видимость:</b> 0 (видно везде), 1 (не видно нигде), 2 (видно только на странице), 3 (видно только по паролю)<br>";
	} ############################### ЗАКРЫТИЕ БАЗА ДАННЫХ

	if ($type == "6") { ############################### ОТКРЫТИЕ ШАБЛОН
	echo "Редактирование шаблона</span>";
	if (intval($nastroi) != 1) red_vybor();
	echo "</div>
	<table width=100%><tr><td width=50%>
	<h2>Название:</h2>
	<textarea class='big w100 h40 f16' name='title' rows='1' cols='10'>".$title."</textarea>
	</td><td>
	<h2>Обращение (англ, без пробелов)</h2>
	<textarea class='big w100 h40 f16' name='namo' rows='1' cols='10'>".$name."</textarea>
	</td></tr><tr><td colspan=2>
	".help_shablon()."
	<h2>Содержание шаблона:</h2>";
	redactor($red, $text, 'text'); // редактор: типа редактора, редактируемое поле
	echo "</td></tr></table>";
	} ############################### ЗАКРЫТИЕ ШАБЛОН
	echo "</div></form>";
}
###################################################################################################
function mainpage_save($id=0, $type, $namo, $title, $text, $useit, $shablon, $descriptionX, $keywordsX, $s_tip) {
global $sgatie, $tip, $admintip, $prefix, $db, $nastroi;
$sql = "select name from ".$prefix."_mainpage where `id`='".$id."'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$mod_name = $row['name'];
recash("/-".$mod_name); // Удаление кеша раздела

if ($type == 2) {
	if (trim($namo) == "") $namo = strtolow(translit_name(trim($title)));
	else $namo = strtolow(translit_name(trim($namo)));
}

if ($nastroi == 1) { // Настройка раздела или блока
	global $options, $module_name;
	$text = array();
	foreach ($options as $key => $option) {
		if (($key=="base" and $option=="") or ($key=="base" and $option=="0") or $key=="module_name") {} else $text[] = $key."=".$option;
		if ($key=="module_name") $module_name = $option;
	}
	if ($type!=2) $text = $module_name."|".implode("&",$text);
	else $text = "pages|".implode("&",$text);
	// Обновление
	global $siteurl;
	if ($type!=2) {
		$db->sql_query("UPDATE ".$prefix."_mainpage SET `useit`='".$text."', `tables`='pages' WHERE `id`='".$id."';") or die('Не удалось обновить содержание.');
	} else {
		$db->sql_query("UPDATE ".$prefix."_mainpage SET `text`='".$text."', `tables`='pages' WHERE `id`='".$id."';") or die('Не удалось обновить содержание.');
	}
	if ($type == 3) Header("Location: sys.php?op=mainpage&type=element");
	else Header("Location: sys.php");
	die();
}

if (trim($title)=="") die('Вы не написали название! Вернитесь и заполните это поле.');
if ($type == 0) {
	$n = count($useit); // обработка полученных стилей дизайна (массив Select)
	$styles = "";
	for ($x=0; $x < $n; $x++) {
		$styles .= " $useit[$x]";
	}
	$useit = trim($styles);
	if ($sgatie==1) $text = str_replace("
			","",$text);
}

$text = str_replace("<P>&nbsp;</P>"," ", str_replace("  "," ", str_replace("   "," ", trim($text))));

$namo = mysql_real_escape_string($namo);
$text = mysql_real_escape_string($text);
$useit = mysql_real_escape_string($useit);
$descriptionX = mysql_real_escape_string($descriptionX);
$keywordsX = mysql_real_escape_string($keywordsX);

// Обратное преобразование textarea (замена на англ. букву e, костыль для текстового редактора)
$text = str_replace("tеxtarea","textarea",$text); // ireplace
$useit = str_replace("tеxtarea","textarea",$useit); // ireplace

$sql = "select text from ".$prefix."_mainpage where `tables`='pages' and id='".$id."'";
$result = $db->sql_query($sql);

if ($numrows = $db->sql_numrows($result) > 0) {
	// Обновление
	//if ($type==2) $useit = tipograf($useit, 2);
	$db->sql_query("UPDATE ".$prefix."_mainpage SET `name`='".$namo."', `title`='".$title."', `text`='".$text."', `useit`='".$useit."', `shablon`='".$shablon."', `tables`='pages', `description`='".$descriptionX."', `keywords`='".$keywordsX."' WHERE `id`='".$id."';") or die('Не удалось обновить содержание. Попробуйте нажать в Редакторе на кнопку "Чистка HTML"');

	if ($type == 2) Header("Location: sys.php");
	else Header("Location: sys.php?op=mainpage&type=element");
	die();
} else {

	// Создание
	if ($type==2) { 
		$text = "pages|".trim($text)."&design=".$useit; 
		$useit = "[содержание]"; 
	}

	if ($type==4) { //////////////////////////////////////////////////////////
		$elements = explode("\r\n",$text);
		// Создаем поле
		$n = count($elements);
		if ($n > 0 and $s_tip==0) {
			for ($x=0; $x < $n; $x++) {
				$element = str_replace("  "," ",trim($elements[$x]));
				if ($element != "") $db->sql_query("INSERT INTO ".$prefix."_spiski (`id`, `type`, `name`, `opis`, `sort`, `pages`, `parent`) VALUES (NULL, '".mysql_real_escape_string($namo)."', '".mysql_real_escape_string($element)."', '', '0', '', '0');") or die('Не удалось создать поле.');
			}
		}
		$and = ""; 
		if ($s_tip==1 or $s_tip==4) $and = "&shablon=".$text; // если тип - текст или строка
		if ($s_tip==2) $and = "&".$text; // если тип - файл
		$text = "spisok|type=".$s_tip.$and;
	} ////////////////////////////////////////////////////////////////////////

	if ($type==5) { //////////////////////////////////////////////////////////
		global $delete_table, $line_ekran, $line_close, $line_razdel, $delete_stroka, $add_pole_golos, $add_pole_comm, $add_pole_kol, $pole_rename, $pole_open, $pole_main, $pole_tip, $pole_rusname, $pole_name;

		$delete_table = intval($delete_table);
		$delete_stroka = intval($delete_stroka);
		$add_pole_golos = intval($add_pole_golos);
		$add_pole_comm = intval($add_pole_comm);
		$add_pole_kol = intval($add_pole_kol);

		// Верстаем данные, которые заносятся в таблицу
		//print_r($pole_name);
		$text = array();
		$n = count($pole_name);
		for ($x=0; $x < $n; $x++) {
			$text[] = $pole_name[$x]."#!#".$pole_rusname[$x]."#!#".$pole_tip[$x]."#!#".$pole_main[$x]."#!#".$pole_open[$x]."#!#".$pole_rename[$x];
		}
		$text2 = implode("/!/",$text);

		$all = array();

		if ($delete_table == 1) $db->sql_query("DROP TABLE IF EXISTS `".$prefix."_base_".$namo."`;") or die("Не удалось удалить старую таблицу. SQL: $sql");

		$sql = "CREATE TABLE `".$prefix."_base_".$namo."` (";
		for ($x=0; $x < $n+1; $x++) {
			$one = explode("#!#",$text[$x]);
			$zap = "";
			if ($x < $n-1) $zap = ", ";
			switch ($one[2]) {
				case "текст": $sql .= "`$one[0]` TEXT NOT NULL $zap"; break;
				case "строка": $sql .= "`$one[0]` VARCHAR( 255 ) NOT NULL $zap"; break;
				case "строкабезвариантов": $sql .= "`$one[0]` VARCHAR( 255 ) NOT NULL $zap"; break;
				case "список": $sql .= "`$one[0]` VARCHAR( 255 ) NOT NULL $zap"; break;
				case "ссылка": $sql .= "`$one[0]` VARCHAR( 255 ) NOT NULL $zap"; break;
				case "фото": $sql .= "`$one[0]` TEXT NOT NULL $zap"; break;
				case "минифото": $sql .= "`$one[0]` TEXT NOT NULL $zap"; break;
				case "файл": $sql .= "`$one[0]` TEXT NOT NULL $zap"; break;
				case "число": $sql .= "`$one[0]` INT( 10 ) NOT NULL $zap"; break;
				case "дата": $sql .= "`$one[0]` DATE NOT NULL $zap"; break;
				case "датавремя": $sql .= "`$one[0]` DATETIME NOT NULL $zap"; break;
			}
			$all[] = trim($one[0]);
		}
		$sql .= ");";
		$all = implode(",",$all);
		if ($delete_stroka == 1) $del_stroka = " IGNORE 1 LINES"; else $del_stroka = ""; //  ($all)

		if ($delete_table == 1) $vozmogno = ", возможно такая таблица уже существует"; else $vozmogno = "";
		$db->sql_query($sql) or die("Не удалось создать таблицу".$vozmogno.". SQL: $sql");

		$line_close = str_replace('\"','"',str_replace("\\\\","\\",$line_close)); // убрать
		$line_ekran = str_replace('\"','"',str_replace("\\\\","\\",$line_ekran));

		// Создаем таблицу с именем $prefix_base_$namo
		if (trim($_FILES["useit"]["tmp_name"] != "")) {
		$sql = "load data local infile '".mysql_real_escape_string($_FILES["useit"]["tmp_name"])."' INTO table ".$prefix."_base_".$namo." FIELDS TERMINATED BY '".$line_razdel."' ENCLOSED BY '". $line_ekran."' LINES TERMINATED BY '".$line_close."'".$del_stroka.";"; // local 
		$db->sql_query($sql) or die("Не удалось добавить информацию из файла CSV в таблицу базы данных. SQL: $sql");
		} else echo "Файл не доступен";

		$db->sql_query("ALTER TABLE `".$prefix."_base_".$namo."` ADD  `id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;");

		$add = ""; // Добавление дополнительных параметров
		if ($add_pole_golos == 1) {
			$db->sql_query("ALTER TABLE `".$prefix."_base_".$namo."` ADD  `golos` INT( 10 ) DEFAULT  '1' NOT NULL ;");
			$add .= "&golos=1";
		}

		if ($add_pole_comm == 1) {
			$db->sql_query("ALTER TABLE `".$prefix."_base_".$namo."` ADD  `comm` INT( 10 ) DEFAULT  '1' NOT NULL ;");
			$add .= "&comm=1";
		}

		if ($add_pole_kol == 1) {
			$db->sql_query("ALTER TABLE `".$prefix."_base_".$namo."` ADD  `kol` INT( 10 ) DEFAULT  '1' NOT NULL ;");
			$add .= "&kol=1";
		}

		$db->sql_query("ALTER TABLE `".$prefix."_base_".$namo."` ADD  `active` INT( 1 ) DEFAULT  '1' NOT NULL ;");

		$text = "base|type=".$s_tip.$add."&options=".$text2;
		$useit = "[содержание]";
	} 
	$namo = mysql_real_escape_string(stripcslashes($namo));
	$title = mysql_real_escape_string(stripcslashes($title));
	$text = mysql_real_escape_string(stripcslashes($text));
	$useit = mysql_real_escape_string(stripcslashes($useit));
	$shablon = mysql_real_escape_string(stripcslashes($shablon));
	$db->sql_query("INSERT INTO ".$prefix."_mainpage (`id`, `type`, `name`, `title`, `text`, `useit`, `shablon`, `counter`, `tables`, `color`, `description`, `keywords`) VALUES (NULL, '".$type."', '".$namo."', '".$title."', '".$text."', '".$useit."', '".$shablon."', '0', 'pages', '0', '', '');") or die('Не удалось создать. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
		}

	// узнаем id
	if ($id == 0) $row2 = $db->sql_fetchrow($db->sql_query("select `id` from ".$prefix."_mainpage where `tables`='pages' and `type`='".$type."' and `name`='".$namo."' and `title`='".$title."' and `text`='".$text."' and `useit`='".$useit."'")) or die("SQL: select `id` from ".$prefix."_mainpage where `tables`='pages' and `type`='".$type."' and `name`='".$namo."' and `title`='".$title."' and `text`='".$text."' and `useit`='".$useit."'");

	// после сохранения откроем настройку раздела или блока
	//if ( ($nastroi == 1 and $type == 2) or ($id != 0 and $type == 2) ) Header("Location: sys.php"); 
	if ($type == 2) Header("Location: sys.php?op=mainpage&id=".$row2['id']."&nastroi=1");
	elseif ($type == 3) Header("Location: sys.php?op=mainpage&id=".$row2['id']."&type=3&nastroi=1");
	else Header("Location: sys.php?op=mainpage&type=element");
	die;
}
#####################################################################################################################
function mainpage_razdel_color($id, $color) {
	global $tip, $admintip, $prefix, $db;
	$db->sql_query("UPDATE ".$prefix."_mainpage SET `color`='".$color."' WHERE `tables`='pages' and `id`='".$id."';");
	Header("Location: sys.php");
}
##################################################################################################
function mainpage_del($id, $type, $name="") {
	global $tip, $admintip, $prefix, $db;
	switch ( $type ) {
		case '0':
			# Дизайн - автомат. удалять из разделов и блоков -- ДОДЕЛАТЬ!!!
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
		break;
		case '1':
			# Стиль CSS - автоматически удалять из дизайнов -- ДОДЕЛАТЬ!!!
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
		break;
		case '3':
			# блоки
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
		break;
		case '2':
			# Раздел - удалять страницы и папки раздела
			$db->sql_query("DELETE FROM ".$prefix."_pages WHERE module='".$name."'");
			$db->sql_query("DELETE FROM ".$prefix."_pages_categories WHERE module='".$name."'");
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php");
		break;
		case '4':
			# Поле - удалять поля
			$db->sql_query("DELETE FROM ".$prefix."_spiski WHERE type='".$name."'");
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
		break;
		case '5':
			# База данных - удалять базу данных 
			$db->sql_query("DROP TABLE ".$prefix."_base_".$name);
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
			# и упоминание в разделах -- ДОДЕЛАТЬ!!!

		break;
		case '6':
			# Шаблонов - удалять из разделов и блоков -- ДОДЕЛАТЬ!!!
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
		break;
	}
}
##################################################################################################
function mainpage_recycle_spiski() {
	global $admintip, $prefix, $db;
	$sql = "select name from ".$prefix."_mainpage where type='4' and text like '%type=1%'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		$nam = $row['name'];
		$db->sql_query("DELETE FROM ".$prefix."_spiski WHERE type='".$nam."' and pages=''");
	}
	Header("Location: sys.php");
}
##################################################################################################
function mainpage_create_block($title, $name, $text, $modul, $useit, $design) {
	global $tip, $admintip, $prefix, $db, $name_razdels;
	# id type name title text useit shablon
	$title = trim($title); // Название блока
	$name = intval($name); // тип блока
	$useit = "|".trim($useit); // настройки блока
	if (trim($title) == "") $title = "Вы забыли ввести название этого блока! ОТРЕДАКТИРУЙТЕ!";
	if ($modul != 0) {
		$modul_name = $name_razdels[$modul];
		$useit = $modul_name.$useit;
		$shablon = "block-".$modul_name."-".$name; // css блока имеет вид: block-англ.имя раздела-тип блока
	} else $shablon = "block-".strtolow(translit_name(trim($title))); // транслитерация имени блока
	if ($design != 0) $useit .= "&design=$design";
	$title = mysql_real_escape_string(stripcslashes($title));
	$shablon = mysql_real_escape_string(stripcslashes($shablon));
	$text = mysql_real_escape_string(stripcslashes($text));
	$useit = mysql_real_escape_string(stripcslashes(str_replace("|&","|",$useit)));
	$db->sql_query("INSERT INTO ".$prefix."_mainpage VALUES (NULL, '3', '".$name."', '".$title."', '".$text."', '".$useit."', '".$shablon."', '0', 'pages', '0', '', '')") or die("Не удалось создать блок. INSERT INTO ".$prefix."_mainpage VALUES (NULL, '3', '".$name."', '".$title."', '".$text."', '', '".$useit."', '".$shablon."', '0', 'pages', '0', '', '') ");
	// узнаем id
	$row = $db->sql_fetchrow($db->sql_query("select `id` from ".$prefix."_mainpage where `tables`='pages' and `type`='3' and `name`='".$name."' and `title`='".$title."' and `text`='".$text."' and `useit`='".$useit."' limit 1"));
	Header("Location: sys.php?op=".$admintip."&type=3&id=".$row['id']."&nastroi=1");
}
#####################################################################################################################
function spisok_help() { // проверить вызов
	return "<div id=spisok_1><b>«список слов»:</b> просто указываем список возможных вариантов выбора, разделяем их через Enter.<br></div>

	<div id=spisok_2 style='display:none;'><b>«текст»:</b> можно написать заготовку-шаблон, которая будет использоваться по-умолчанию как значение данного поля.<br></div>

	<div id=spisok_3 style='display:none;'><b>«файл»:</b><br>
	прописывать параметры, разделяя их знаком &, указывать значения параметров через знак =, всё без пробелов!<br>
	например: <b>fil=pic&papka=/img=verh&align=left&resizepic=x&file=&picsize=600&minipic=1&resizeminipic=x&minipicsize=100</b><br>Очередность параметров значения не имеет.<br>
	<b>Список доступных параметров:</b><br> 
	<table width=100% cellpadding=5 cellspacing=0 border=1> 
	<tr><td><strong>fil=</strong></td><td>pic - картинка, doc - документ/архив, flash - flash-анимация, avi - видео-ролик</td></tr>
	<tr><td><strong>papka=</strong></td><td>путь сохранения файла, например /img/</td></tr>
	<tr><td><strong>align=</strong></td><td>left - слева, right - справа, center - по центру, no - без выравнивания</td></tr>
	<tr><td><strong>mesto=</strong></td><td>verh - сверху, niz - снизу текста страницы, block - в блоке [Обращение] - то, что указано по англ. в обращении к полю, только в квадратных скобках <font color=red>(экспериментальное значение)</font></td></tr>
	<tr><td><strong>resizepic=</strong></td><td>если file=pic, указываем как автоматически изменять размер изображения:<br>
	x - по-горизонтали, y - по-вертикали, big - по большей стороне</td></tr>
	<tr><td><strong>picsize=</strong></td><td>число в пикселях, указывается размер, к которому приводится изображение, если оно больше этого размера</td></tr>
	<tr><td><strong>minipic=</strong></td><td>1 - разрешить, 0 - запретить (по умолчанию) Создание миниатюры изображения</td></tr>
	<tr><td><strong>resizeminipic=</strong></td><td>если file=pic, указываем как автоматически изменять размер изображения до его миниатюры:<br>
	x - по-горизонтали, y - по-вертикали, big - по большей стороне</td></tr>
	<tr><td><strong>minipicsize=</strong></td><td>число в пикселях, указывается размер, к которому приводится миниатюра изображение, если оно больше этого размера</td></tr>
	</table></div>

	<div id=spisok_4 style='display:none;'><b>«период времени»:</b> для него пока что нет никаких настроек и шаблон ему не нужен.</div>

	<div id=spisok_5 style='display:none;'><b>«строка»:</b> можно написать заготовку-шаблон, которая будет использоваться по-умолчанию как значение данного поля.<br></div>";
}
#####################################################################################################################
function block_help() { // проверить вызов
	return "<hr>
	<p>Параметры прописываются как передача переменных в скриптах, например: <b>size=10&open_all=1</b><br>
	Начиная строку с первого параметра и разделяя параметры символом & без пробелов между параметрами.<br>
	Значение параметра пишется сразу через символ = . Очередность параметров значения не имеет.<br>
	<b>Список доступных параметров:</b><br> 
	noli shablon sort
	<table width=100% cellpadding=5 cellspacing=0 border=1> 

	<tr><td><strong>media=</strong></td>
	<td> использование медиа: <font color=green>0 - нет</font>, <font color=red>1 - фотогалерея, 2 - flash, 3 - видео, 4 - музыка</font></td></tr>
	<tr><td><strong>folder=</strong></td>
	<td> папка с файлами медиа: <font color=green>0 - нет</font>, <font color=red>указать папку с файлами</font></td></tr>

	<tr><td><strong>daleeshow=</strong></td>
	<td> показывать кнопку «Далее»: 1 - да, <font color=green>0 - нет</font> </td></tr>

	<tr><td><strong>shablon=</strong></td>
	<td> прикрепить шаблон к блоку. добавить № шаблона. полностью пока что не реализовано. </td></tr>

	<tr><td><strong>alternative_title_link=</strong></td>
	<td> заменить ссылку заголовка блока на любую другую, прописав ее после =</td></tr>

	<tr><td><strong>cid_open=</strong></td>
	<td> открывать страницы определенной папки, прописав ее номер после =</td></tr>


	<tr><td><strong>show_in_razdel=</strong></td>
	<td> показывать блок только в определенном разделе: англ. название раздела, <font color=green>все - во всех разделах</font> </td></tr>

	<tr><td><strong>no_show_in_razdel=</strong></td>
	<td> НЕ показывать блок в определенном разделе: англ. название раздела. Если блок показывается во всех разделах.</td></tr>

	<tr><td><strong>dal=</strong></td>
	<td> заменить надпись на кнопке «Далее...» на другую или код HTML, например изображение кнопки</td></tr>
	<tr><td><strong>zagolovokin=</strong></td>
	<td> показывать заголовок новости внутри блока за счет использования [заголовок], 1 - да, <font color=green>0 - нет</font> </td></tr>
	<tr><td><strong>notitlelink=</strong></td>
	<td> убрать ссылку на раздел в заголовке, 1 - да, <font color=green>0 - нет</font> </td></tr>
	<tr><td><strong>razdel_open_name=</strong></td>
	<td> можно изменить название с <font color=green>«Открыть раздел»</font> на любое другое или вообще не показывать, заменив на «no»</td></tr>
	<tr><td><strong>class=</strong></td>
	<td> использование англ. названия поля для выбора стиля всего блока (каждый выбранный стиль задается в Главном стиля), <font color=green>по умолчанию - нет</font> </td></tr>
	<tr><td><strong>design=</strong></td>
	<td> использование № дизайна для окружения блока (и подключения стиля), <font color=green>по умолчанию - нет</font> </td></tr>
	<tr><td><strong>add=</strong></td>
	<td> <font color=red>1 - показывать ссылку на добавление страницы пользователем</font>, <font color=green>0 - не показывать</font> </td></tr>
	<tr><td><strong>addtitle=</strong></td>
	<td> если показывается ссылка на добавление - можно изменить ее название с <font color=green>«Добавить статью»</font> на <font color=red>любое другое</font></td></tr>
	<tr><td><strong>number=</strong></td>
	<td> с какого номера начинать вывод, <font color=green>0 - самый первый</font> </td></tr>
	<tr><td><strong>size=</strong></td>
	<td> кол-во выводимых ссылок на страницу, <font color=green>10 - по-умолчанию</font> </td></tr>
	<tr><td><strong>open_all=</strong></td>
	<td> 1 - показывать ссылку «Открыть все», <font color=green>0 - не показывать</font> </td></tr>

	<tr><td><strong>html=</strong></td>
	<td> 1 - использовать только содержание блока (без оформления и заголовка), <font color=green>0 - по-умолчанию</font> </td></tr>

	<tr><td><strong>titleshow=</strong></td>
	<td> <font color=green>1 - показывать заголовок блока</font>, 0 - не показывать, 2 - показывать внутри сообщения по слову [заголовок] </td></tr>
	<tr><td><strong>openshow=</strong></td>
	<td> 1 - показывать предпросмотр, <font color=green>0 - не показывать</font> </td></tr>
	<tr><td><strong>datashow=</strong></td>
	<td> 1 - показывать дату, <font color=green>0 - не показывать</font> </td></tr>
	<tr><td><strong>main=</strong></td>
	<td> 1 - выводить в блок только отмеченные НА ГЛАВНУЮ СТРАНИЦУ, 2 - все без отмеченных, <font color=green>0 - выводить все</font> </td></tr>
	<tr><td><strong>catshow=</strong></td>
	<td> 1 - выводить папку -> перед названием статьи, <font color=green>0 - не выводить</font> </td></tr>
	<tr><td><strong>sort=</strong></td>
	<td> сортировка по title (по заголовку), counter (по просмотру), golos (по голосованию), comm (по кол-ву комментарий), <font color=green>date (по дате)</font> </td></tr>

	<tr><td colspan=2><br><strong>Для блока меню:</strong></td></tr>
	<tr><td><strong>menu=</strong></td>
	<td> тип меню для блока меню, <font color=green>0 - нет</font>, 1 - да</td></tr>

	<tr><td colspan=2><br><strong>Для блока папок модуля и открытых папок:</strong></td></tr>
	<tr><td><strong>papki_numbers=</strong></td>
	<td> ставить кол-во страниц, <font color=green>0 - нет</font>, 1 - да</td></tr>
	<tr><td><strong>papka_sort=</strong></td>
	<td> сортировка папок, <font color=green>sort, title - по-умолчанию</font></td></tr>



	<tr><td colspan=2><br><strong>Для блока опроса:</strong></td></tr>
	<tr><td><strong>opros_type=</strong></td>
	<td> тип опроса, <font color=green>1 - одиночный выбор (кружки)</font>, <font color=red>0 - множественный выбор (флажки)</font></td></tr>

	<tr><td colspan=2><br><strong>Для блока мини-картинок из предописания:</strong></td></tr>
	<tr><td><strong>first=</strong></td>
	<td> указание на первый элемент, после которого следует изображение, <font color=green>по умолчанию = «src=»</font> (без кавычек) </td></tr>
	<tr><td><strong>second=</strong></td>
	<td> указание на второй элемент, до которого следует изображение, <font color=green>по умолчанию = «>»</font> (без кавычек) </td></tr>
	<tr><td><strong>third=</strong></td>
	<td> указание на третий, последний элемент, до которого следует изображение, <font color=green>по умолчанию = пробел</font> </td></tr>
	<tr><td><strong>img_width=</strong></td>
	<td> размер картинки в пикселях по горизонтали, <font color=green>по умолчанию 60.</font> Указать 0 - будет по вертикали </td></tr>
	<tr><td><strong>img_height=</strong></td>
	<td> размер картинки в пикселях по вертикали, <font color=green>по умолчанию 60.</font> Указать 0 - будет по горизонтали </td></tr>

	<tr><td colspan=2><br><strong>Для блока PHP: всё, выводящееся на экран, ДОЛЖНО содержатся в переменной \$txt</strong></td></tr>

	<tr><td colspan=2><br><strong>При редактировании блока раздела: чтобы выводить все разделы вместо одного - убрать англ. название раздела в начале строки</strong></td></tr>

	<tr><td colspan=2><br><strong>Для блока базы данных:</strong></td></tr>
	<tr><td><strong>base=</strong></td>
	<td> указываем название таблицы БД, обязательно</td></tr>
	<tr><td><strong>first=</strong></td>
	<td> первая колонка, желательно</td></tr>
	<tr><td><strong>second=</strong></td>
	<td> вторая колонка, не обязательно</td></tr>
	<tr><td><strong>text=</strong></td>
	<td> текст самой первой ячейки (для блока БД «количество по 2 колонкам»), <font color=green>пустой по умолчанию</font></td></tr>
	<tr><td><strong>direct=</strong></td>
	<td> направление, gor - горизонтальное, <font color=green>vert - вертикальное</font></td></tr>
	<tr><td><strong>all=</strong></td>
	<td> сколько всего элементов, <font color=green>0 - не указывать</font>, 1 - указывать</td></tr>
	<tr><td><strong>col=</strong></td>
	<td> какие поля будут использоваться для вывода информации, через запятую</td></tr>

	</table>
	<font color=green><b>зеленым - по умолчанию</b></font>, <font color=red><b>красным - не работает</b></font>
	";
}
	#####################################################################################################################
	switch ($op) {
	    case "mainpage":
		    if (isset($name)) { // узнать id
		    	global $prefix, $db;
				$row = $db->sql_fetchrow( $db->sql_query("select `id` from ".$prefix."_mainpage where `tables`='pages' and `name`='".$name."' and `type`='2'") );
				$id = $row['id'];
		    }
		    if (isset($id)) mainpage($id); else mainpage();
	    break;
		
	    case "mainpage_save":
		    if (!isset($descriptionX)) $descriptionX = "";
		    if (!isset($keywordsX)) $keywordsX = "";
		    if (!isset($s_tip)) $s_tip = "";
		    if (!isset($namo)) $namo = "";
		    if (!isset($title)) $title = "";
		    if (!isset($text)) $text = "";
		    if (!isset($useit)) $useit = "";
		    if (!isset($shablon)) $shablon = "";
		    mainpage_save($id, $type, $namo, $title, $text, $useit, $shablon, $descriptionX, $keywordsX, $s_tip);
	    break;
		
	    case "mainpage_del":
	    	mainpage_del($id, $type, $name);
	    break;
	    
		case "mainpage_recycle_spiski":
			mainpage_recycle_spiski();
		break;
		
	    case "mainpage_create_block":
	    	mainpage_create_block($title, $name, $text, $modul, $useit, $design);
	    break;
		
		case "mainpage_razdel_color":
			mainpage_razdel_color($id, $color);
		break;
	}
}
?>
