<?php
/* Движок: Content Management System */
define('ADMIN_FILE', true);
if(isset($aid)) {
  if(!empty($aid) AND (!isset($admin) OR empty($admin)) AND $op!='login') {
  unset($aid);
  unset($admin);
  die("Доступ закрыт! (sys)");
  }
}
require_once("mainfile.php");

$checkurl = $_SERVER['REQUEST_URI'];
if (preg_match("/AddAuthor/", $checkurl)) die ('Попытка взлома №6');
if (preg_match("/UpdateAuthor/", $checkurl)) die ('Попытка взлома №7');

if((stripos($checkurl,'AddAuthor')) OR (stripos($checkurl,'VXBkYXRlQXV0aG9y')) OR (stripos($checkurl,'QWRkQXV0aG9y')) OR (stripos($checkurl,'UpdateAuthor')) OR (stripos($checkurl, "?admin")) OR (stripos($checkurl, "&admin")) OR (stripos($checkurl,'%20union%20') OR stripos($checkurl,'*%2f*') OR stripos($checkurl,'/*') OR stripos($checkurl,'*/union/*') OR stripos($checkurl,'c2nyaxb0') OR stripos($checkurl,'+union+') OR (stripos($checkurl,'cmd=') AND stripos($checkurl,'&cmd')===false) OR (stripos($checkurl,'exec') AND stripos($checkurl,'execu')===false) OR stripos($checkurl,'concat'))) die("Попытка взлома!");

global $admin_file;

$the_first = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_authors"));
if ($the_first == 0) {
	if (!$name) {
		include("ad-header.php");
		echo "<center>Подождите, возникли проблемы с подключением к базе данных... Если через полчаса-час не наладится - свяжитесь с разработчиком или хостинг-провайдером.</center><br>";
		admin_footer();
	}
	die();
}

	if (!isset($_POST['random_num'])) $_POST['random_num'] = "";
  	if (isset($aid) && (ereg("[^a-zA-Zа-яА-Я0-9_-]",trim($aid)))) {
   		die("Begone");
  	}
  	if (isset($aid)) { $aid = substr($aid, 0,25);}
  	if (isset($pwd)) { $pwd = substr($pwd, 0,40);}
  	if ((isset($aid)) && (isset($pwd)) && (isset($op)) && ($op == "login")) {
		$datekey = date("F j");
		$rcode = hexdec(md5($_SERVER['HTTP_USER_AGENT'] . $sitekey . $_POST['random_num'] . $datekey)); // $_GET['random']
		$code = substr($rcode, 2, 6);
		if(!empty($aid) AND !empty($pwd)) {
			$pwd = md5($pwd);
			$result = $db->sql_query("SELECT pwd FROM ".$prefix."_authors WHERE aid='".$aid."'");
			list($rpwd) = $db->sql_fetchrow($result);
			if($rpwd == $pwd) {
				$admin = base64_encode($aid.":".$pwd);
				setcookie("admin",$admin,time()+2592000);
				unset($op);
			}
		}
	}

$admintest = 0;

if(isset($admin) && !empty($admin)) {
	$admin = addslashes(base64_decode($admin));
	$admin = explode(":", $admin);
	$aid = addslashes($admin[0]);
	$pwd = $admin[1];
	if (empty($aid) OR empty($pwd)) {
		$admintest = 0;
		die("<html>\n<title>Да вы взломщик! (Get out!)</title>\n<body bgcolor=#FFFFFF text=#000000>\n\n<br><br><br>\n\n<center><br><br>\n<font size=\"+4\"><b>Да вы, вероятно, взломщик! (Get out!)</b><br>Если вы не взломщик, а администратор этого сайта, почистите, пожалуйста, куки (cookie). Например так: меню браузера Сервис -> Свойства обозревателя -> Удалить... -> Удалить \"Cookie\".</font></center>\n</body>\n</html>");
	}
	$aid = substr($aid, 0,25);
	$result2 = $db->sql_query("SELECT name, pwd FROM ".$prefix."_authors WHERE aid='".$aid."'");
	if (!$result2) {
		die("Выбор из базы данных не удался!");
	} else {
		list($rname, $rpwd) = $db->sql_fetchrow($result2);
		if($rpwd == $pwd && !empty($rpwd)) {
			$admintest = 1;
		}
	}
}

if(!isset($op)) { 
	$op = "adminMain"; 
} elseif(($op=="mod_authors" OR $op=="modifyadmin" OR $op=="UpdateAuthor" OR $op=="AddAuthor" OR $op=="deladmin2" OR $op=="deladmin" OR $op=="deladminconf") AND ($rname != "BOG")) {
  die("Запрещенная операция! <br>Возможно, вы только что сменили имя и/или пароль администратора - тогда перейдите ко <a href=sys.php?op=login>входу в администрирование</a>.");
}
$pagetitle = "- Администрирование";

// Стирание кеша главной страницы
if (is_admin($admin)) recash("/");

global $razdel_sort; // Сортировка разделов в Содержании
$razdel_sort = intval($razdel_sort);
if ( $razdel_sort == 1 or $razdel_sort == 2 or $razdel_sort == 0 ) setcookie("razdel_sort", $razdel_sort, time()+60*60*24*360);

/******************/
/* Вход в Админку */
/******************/

function login() {
	global $admin_file;
	mt_srand ((double)microtime()*1000000);
	$random = mt_rand(0, 1000000);
	header ("Content-Type: text/html; charset=utf-8");
	echo "<!doctype html>\n<html lang=\"ru-RU\" dir=\"ltr\">\n<head><title>Вход в Администрирование</title><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><meta http-equiv='pragma' content='no-cache' /><meta http-equiv='no-cache' /><meta http-equiv='cache-control' content='no-cache' /><link rel=\"stylesheet\" href=\"ad-style.css\" type=\"text/css\"><link REL=\"shortcut icon\" href=\"favicon_cms.ico\" type=\"image/x-icon\"></head>\n<body>\n<center style='margin-top:200px;'><h1>Вход в администрирование</h1><br><br><form action='red' style=\"background: red url(images/adfon/default.jpg);\" method='post'><table><tr><td align=right>Псевдоним: </td><td><input type=text name=aid size=20></td></tr><tr><td align=right>Пароль: </td><td><input type=password name=pwd size=20></td></tr><tr><td><input type=hidden name=password value=$random><input type=hidden name=op value=login></td><td><input type=submit value=\" Войти \"></td></tr></table></form></center>\n</body></html>";
}

function GraphicAdmin() {
	global $aid, $admin, $prefix, $db, $counter, $admin_file, $show_comments, $show_userposts, $razdel_sort, $registr, $show_page;
	$row = $db->sql_fetchrow($db->sql_query("SELECT realadmin FROM ".$prefix."_authors WHERE aid='".$aid."'"));
	$realadmin = intval($row['realadmin']);
	$inf_base = "";
	if (file_exists("map.xml")) {
		if (date("Y-m-d", filectime("map.xml")) != date("Y-m-d")) {
			// Починка БД (раз в день)
			$result = $db->sql_query('SHOW TABLE STATUS');
			if ($db->sql_numrows($result)) {
				$local_query = array();
				while ($row = $db->sql_fetchrow($result)) {
					if (strpos(" ".$row[0]." ",$prefix)) $local_query[] = $row[0];
				}
				$db->sql_query('REPAIR TABLE '.implode(", ",$local_query));
			}
			//echo "База данных сжата до ".(intval($total_all)/1000)." Мбайт";
		}
	}
	///////////////////////////////////////////////////////////////
	// Показываем основные возможности - ред. разделов.
	///////////////////////////////////////////////////////////////

	// Комментарии
	$comments = "";
	$pageslist = "";
	$pages = "pages";

	$date_now1 = date("Y.m.d");
	$date_now2 = date("Y.m.d",time()-86400);
	$date_now3 = date("Y.m.d",time()-172800);
	
	$comm_segodnya = $db->sql_numrows($db->sql_query("select `cid` from ".$prefix."_".$pages."_comments where `tables`='pages' and data like '".$date_now1." %'"));
	$comm_vchera = $db->sql_numrows($db->sql_query("select `cid` from ".$prefix."_".$pages."_comments where `tables`='pages' and data like '".$date_now2." %'"));
	$comm_pozavchera = $db->sql_numrows($db->sql_query("select `cid` from ".$prefix."_".$pages."_comments where `tables`='pages' and data like '".$date_now3." %'"));
		
	if ($show_userposts != 0) {
		$num_add_pages = $db->sql_numrows($db->sql_query("SELECT `pid` from ".$prefix."_pages where (`active`='2' or `active`='3') and `tables`!='del'"));
	}
	$soderganie_menu = "<div style='margin-bottom:5px;'>
	<button class='nothing small' id='hide_razdel' onclick=\"$('#show_razdel').show(); $('#razdels').hide(); $('#hide_razdel').hide(); $('#razdel_td').hide(); $('.dark_pole2sel').attr('class', 'dark_pole2');\" style='' title='Скрыть Разделы в кнопку'>&rarr;</button>

	<button class='small' id='show_razdel' style='display:none;' href=# onclick=\" $('#razdel_td').show(); $('#show_razdel').hide(); $('#razdels').show(); $('#hide_razdel').show();\"><span class=\"icon gray small\" data-icon=\",\"></span>Разделы</button> <button class='nothing small' onclick=\"openbox('5','Новое и отредактированное'); $('#hide_razdel').click();\"><span class=\"icon gray small\" data-icon=\"M\"></span>Новое</button>";
	if (!isset($num_add_pages)) $num_add_pages = 0;

	if ($num_add_pages > 0 and $show_userposts != 0) {
		if ($show_userposts == 2) $soderganie_menu .= "<a style='color: gray;' onclick=\"openbox('4','Добавленное посетителями');\">.</a>";
		elseif ($show_userposts == 1) $soderganie_menu .= "<button class='nothing small' style='color: red;' onclick=\"openbox('4','Добавленное посетителями'); $('#hide_razdel').click();\"><span class=\"icon gray small\" data-icon=\"u\"></span><nobr>Проверить: <strong>".$num_add_pages."</strong></nobr></button>";
	}
	if ($show_comments != 0) {
		if ($show_comments == 2) $soderganie_menu .= "<a style='color: gray;' onclick=\"openbox('3','Комментарии');\">.</a>";
		elseif ($show_comments == 1) $soderganie_menu .= "<button class='nothing small' onclick=\"openbox('3','Комментарии'); $('#hide_razdel').click();\" title='Комментарии за сегодня/вчера/позавчера'><span class=\"icon gray small\" data-icon=\"'\"></span><nobr>Комментарии: ".$comm_segodnya."/".$comm_vchera."/".$comm_pozavchera."</nobr></button>";
	}
	$del_page = $db->sql_numrows($db->sql_query("SELECT pid from ".$prefix."_".$pages." where `tables`='del' limit 0,1"));
	if ($del_page > 0) $soderganie_menu .= "<button class='nothing small' onclick=\"openbox('1','Корзина'); $('#hide_razdel').click();\" title='Удаленные страницы'><span class=\"icon gray small\" data-icon=\"T\"></span>Корзина</button>";

	$backup_page = $db->sql_numrows($db->sql_query("SELECT pid from ".$prefix."_".$pages." where `tables`='backup' limit 0,1"));
	if ($backup_page > 0) $soderganie_menu .= "<button class='nothing small' onclick=\"openbox('2','Резервные копии'); $('#hide_razdel').click();\" title='Резервные копии созданных ранее страниц'><span class=\"icon gray small\" data-icon=\"t\"></span>Старое</button>";

	$soderganie_menu .= " <button class='nothing small' onclick=\"oformlenie_show('блок','3','block','/sys.php?op=mainpage&name=block&type=3'); $('#hide_razdel').click();\" title='Резервные копии созданных ранее страниц'><span class=\"icon gray small\" data-icon=\"R\"></span>Блоки</button> ";

	$soderganie_menu .= "<button id='new_razdel_button' title='Добавить страницу...' class='small green nothing' onclick='location.href=\"/sys.php?op=base_pages_add_page#1\"'><span class=\"icon white small\" data-icon=\"+\"></span>страницу</button>
	</div>";

	echo "<table style='width:100%; background: #e2e5ea; margin-top:5px; padding:0;' cellspacing=0 cellpadding=5><tr valign=top><td id='razdel_td' class='radius nothing'><div id='razdels' style='background:#e7e9ec;'>";

	// Сортировка разделов: 0 - цвет, 1 - алфавит, 2 - посещаемость
	$razdel_sort_name = array("<a href=red?razdel_sort=0>цвету раздела</a>", "<a href=red?razdel_sort=1>названию</a>", "<a href=red?razdel_sort=2>посещаемости</a>");
	if (!isset($razdel_sort)) if (!isset($_COOKIE["razdel_sort"])) { 
		setcookie("razdel_sort", "0", time()+60*60*24*360); 
		$razdel_sort = 0;
	}
	else $razdel_sort = intval($_COOKIE["razdel_sort"]);
	$razdel_sort_name[$razdel_sort] = ">>> ".$razdel_sort_name[$razdel_sort]."";
	if ($razdel_sort == 0) $razdel_sort = "color desc, title";
	elseif ($razdel_sort == 1) $razdel_sort = "title";
	elseif ($razdel_sort == 2) $razdel_sort = "counter desc";
	else $razdel_sort = "color, title";

	$subg = "";
	$sql = "select * from ".$prefix."_mainpage where `tables`!='del' and type='2' and name!='index' order by ".$razdel_sort;
	$result = $db->sql_query($sql);
	$current_type = ""; 

			$styles2 = ""; // Выяснить основной дизайн у всех разделов
			$sql3 = "select text from ".$prefix."_mainpage where type='2' and name!='index' and `tables`!='del'";
			$result3 = $db->sql_query($sql3);
			$num_razdel = $db->sql_numrows($result3);
			while ($row3 = $db->sql_fetchrow($result3)) {
				$design = 1;
				$parse = explode("|",$row3['text']);
				parse_str($parse[1]); // Разложим на переменные и узнаем дизайн раздела
				$styles2[] = $design;
			}
			if ($num_razdel > 0) {
				$styles2 = array_count_values($styles2);
				reset($styles2);
				arsort($styles2);
				$styles2 = key($styles2);
				$razdel_txt = "";
			} else $razdel_txt = "<div style='padding-left:5px; color: red;'>Разделов пока нет. Добавьте.</div>";
				
			$styles = ""; // Выборка дизайнов
			$sql2 = "select id, title from ".$prefix."_mainpage where type='0'";
			$result2 = $db->sql_query($sql2);
			while ($row2 = $db->sql_fetchrow($result2)) {
				$id_design = $row2['id'];
				$title_design = trim($row2['title']);
				$sel = "";
				if ($styles2 == $row2['id']) {
					$title_design .= " (используется чаще всего)";
					$sel = " selected";
				}
				$styles .= "<option value='".$row2['id']."'".$sel.">".$title_design."</option>";
			}

			if ($registr=='1') echo "&nbsp;&nbsp;&nbsp;<a href=".$admin_file.".php?op=MainUser>Пользователи</a> <a href=".$admin_file.".php?op=sortuser>Список</a>";

			echo "<div class='black_grad'><button id=new_razdel_button title='Сортировка...' class='small black' onclick=\"show('sortirovka');\" style='float:left; margin:3px;'><span style='margin-right: -2px;' class=\"icon darkgrey small\" data-icon=\"|\"></span></button><button id=new_razdel_button title='Добавить раздел...' class='small black right3' onclick=\"show_animate('add');\"><span class=\"mr-2 icon darkgrey small\" data-icon=\"+\"></span></button><span class='h1'>Разделы:</span>
				</div>".$razdel_txt."
				<div id='sortirovka' style='color: green; display:none;'>
				<p style=' margin-left:20px;'>Сортировка по: <br>".$razdel_sort_name[0].",<br>".$razdel_sort_name[1].",<br>".$razdel_sort_name[2]."</p></div>"; 
			
			$new_razdel = "<div style='display:none;' id='add'>
			<form method='post' action=sys.php class='block_white2 radius align_center' style='min-width: 450px; max-width:700px; margin-bottom:20px; background: #dddddd;'>
			<a title='Закрыть это окно' class=punkt onclick=\"show_animate('add')\"><div class='radius' style='font-size:12pt; width:20px; height: 20px; color: white; text-align:center; float:right; margin:5px; margin-bottom:0; background: #bbbbbb;'>&nbsp;x&nbsp;</div></a>
			<h1>Вы решили добавить раздел:</h1>
			<div class='help small'>?</div><a name=metka class=punkt href=#metka onClick=\"show('about')\">Узнать, что такое раздел</a>
			<div id=about style='display:none;'><div style='background: white;'>
			<p><b>Раздел</b> — это определенная тематическая категория на сайте. Она может быть самодостаточной (представлять из себя одну страницу) или содержать несколько страниц и папок. 
			<p>Страницы создаются, если информацию раздела следует разбить на несколько отдельных тем и не стоит делать одну гигантскую страницу.
			<p>Папки не являются обязательными и создаются как подкатегории (подразделы) в случае излишнего количества страниц (к примеру больше 50).
			<p>Если раздел содержит только одну небольшую начальную страницу, можно не создавать в нем папок и страниц, а лишь редактировать сам раздел — после создания раздела, нажмите по его имени в списке разделов на этой странице и по ссылке <nobr><img class=\"icon2 i35\" src=/images/1.gif><u>глав. страница раздела</u></nobr>. 
			<p>Создавая раздел, укажите его русское название, например, «Наши акции», а также английское имя, желательно в одно слово (без пробелов), например, akcyi (транслит) или stock (перевод). После этого выберите дизайн (обычно используется «Главный дизайн», но на этом сайте, возможно, используются и дополнительные дизайны — в таком случае часто используемый дизайн будет выбран по-умолчанию). Нажмите «Добавить раздел». 
			<p>Как только раздел будет создан, его можно настроить, нажав по его имени и по ссылке <nobr><img class=\"icon2 i38\" src=/images/1.gif><u>настроить</u></nobr>. 
			<p>Чтобы добавить в раздел папки (категории/каталоги) или страницы (статьи/новости/посты) нажмите по его названию в списке разделов слева. Справа вы увидите ссылку Добавить <nobr><img class=\"icon2 i39\" src=/images/1.gif><u>страницу</u></nobr>, а следом за ней — <nobr><img class=\"icon2 i40\" src=/images/1.gif><u>папку</u></nobr>. 
			<p><i>Если что-то не ясно — обратитесь к разработчику.</i>
			</div></div>
			<input type=hidden name=type value='2'>
			<TABLE cellspacing=0 cellpadding=5 width=100%><TR>
			<TD align=right valign=top width=40>
			<b>Название раздела:</b></td><td><input id=rus_name type=text name=title size=30 style='width:100%;'><br>
			Например: «О нас», «Наша продукция», «Каталог», «Контакты» и т.д.
			</td></tr><tr><TD align=right valign=top>
			Англ. название:</td><td><input type=text name=namo size=30 style='width:100%;'><br>
			<a href=# onclick=\"window.open('http://translate.google.ru/#ru/en/' + $('#rus_name').val(),'Перевод',' width=800,height=400'); return false;\"><b>Перевести русское название</b></a><br>
			Например: «about», «product», «catalog», «contact» и т.д.<br>
			Используются англ. буквы и знак «_», без пробелов. 
			<input type=hidden name=text value='lim=10&amp;comments=1'>
			</td></tr><tr valign=top><TD align=right valign=top>
			<b>Выберите дизайн:</b></td><td><select name=useit style='width:100%;'>".$styles."</select>
			<br><input type=\"submit\" value=\"Добавить раздел\" style='width:100%; height:55px; font-size: 20px; margin-top:20px;'>
			</td></tr></table>
			<input type=hidden name=shablon>
			<input type=hidden name=id value=''>
			<input type=hidden name=op value=mainpage_save>
			</form>
			</div>";

		$icon_size = "large";
		if ($num_razdel > 5) $icon_size = "medium"; 
		if ($num_razdel > 10) $icon_size = "small"; 

		echo "<div id='mainrazdel_index'>
		<a class='base_page' href='/sys.php?op=mainpage&amp;id=24&amp;red=1' title='Редактировать главную страницу'><div class='dark_pole2'><span class='icon black ".$icon_size."' data-icon='4'></span><span class='plus20'>Главная страница</span> <span class='small' style='color:#e7e9ec'>(редактировать)</span></div></a>";

    while ($row = $db->sql_fetchrow($result)) {
	    $id = $row['id'];
	    $type = $row['type']; 
	    $nam = $row['name']; 
	    $title = strip_tags($row['title'], '<b><i>');
	    if ($type == 3) $title = "[$title]";
		$color = $row['color'];
	    $text = $row['text'];
	    $useit = $row['useit'];
		$tables = $row['tables'];

		if ($show_page == 1) {
			$result3 = $db->sql_query("select pid from ".$prefix."_pages where `active`='1' and `tables`='pages' and module='".$nam."'");
			$size = $db->sql_numrows($result3);
			$result4 = $db->sql_query("select pid from ".$prefix."_pages where `active`!='1' and `tables`='pages' and module='".$nam."'");
			$size_off = $db->sql_numrows($result4);
			} else { $size = 0; $size_off = 0; }

		$type_opisX = "";
		if ($nam!="index") {
			if ($size < 1) $size = ""; 
			if ($size_off < 1) $size_off = ""; else $size_off = "-".$size_off;
			$type_opisX = "<span class='green' title='Включенные страницы'>".$size."</span>&nbsp;<span class='red' title='Отключенные страницы'>".$size_off."</span>"; //.$sizeX;
			if ($size < 1 and $size_off < 1) $type_opisX = "";
		} elseif ($nam=="index") $type_opisX = "";
		if ($current_type != $type) $current_type = $type;
		$text = explode("|",$text); 
		$options = $text[1];
		$text = $text[0];
		if (strpos($options,"base=")) $text = "base";

		switch ($color) { // Цвета разделов
			case "1": // Частоупотребляемый зеленый
			$color = "lightgreen"; break;
			case "2": // Редкоупотребляемый желтый
			$color = "lightyellow";	break;
			case "3": // Закрытый или старый красный
			$color = "lightred"; break;
			case "4": // Новый, в разработке
			$color = "lightblue"; break;
			default: 
			$color = "gray";
			break;  // Стандартный белый
		}
		///////////// Для базы данных !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$link = "";
		$doping = "";
		$iconpage = "";
		if (strpos($options,"base=")) {
			$title = "БД «".trim($title)."»";
			// Узнаем название БД
			$sql2 = "SELECT `text` FROM ".$prefix."_mainpage where type='2' and name='".$nam."'";
			$result2 = $db->sql_query($sql2);
			$row2 = $db->sql_fetchrow($result2);
			$module_options = explode("|",$row2['text']); $module_options = $module_options[1]; 
			parse_str($module_options);
			$sql2 = "SELECT name FROM ".$prefix."_mainpage where id='".$base."'";
			$result2 = $db->sql_query($sql2);
			$row2 = $db->sql_fetchrow($result2);
			$baza_name  = $row2['name']; // Название таблицы БД 
			$type_opisX = " <nobr><a href=sys.php?op=base_base&name=".$nam." title='Открыть базу данных' style='margin-left:10px;'><img class=\"icon2 i34\" src=/images/1.gif></a>
			<a href=sys.php?op=base_base_create_base&base=".$baza_name."&name=".$nam."&amp;red=1 title='Добавить строку в базу данных' style='margin-left:5px;'><img class=\"icon2 i29\" src=/images/1.gif></a></nobr>
			";
		}
		if (!strpos($options,"base=")) {
			$type_opisX = "<nobr><span class='small radius' style='border-bottom: #aaaaaa 1px solid;'><b>&nbsp;".$type_opisX."&nbsp;</b></span></nobr>";
		}
		$ver = mt_rand(10000, 99999); // получили случайное число

		echo "<div id='mainrazdel".$id."' class='dark_pole2'><div style='float:right'>".$iconpage.$type_opisX."</div><a class='base_page' title='Нажмите для просмотра действий над этим разделом и его содержимым' onclick='razdel_show(\"".$title."\", ".$id.", \"".$nam."\", \"".$text."\");'><div id='mainrazdel".$id."'>
		<span class='icon ".$color." ".$icon_size."' data-icon=','></span><span class='plus20'>".$title."</span>
		
		</div></a></div>";
    }
	echo "</div>
	</td><td width=70%>".$soderganie_menu.$new_razdel."<div class='podrazdel radius nothing' id='podrazdel'>";

	// ЗАПИСКИ
	$row = $db->sql_fetchrow($db->sql_query("SELECT adminmes from ".$prefix."_config"));
	$adminmes = $row['adminmes'];
	global $op, $project_logotip, $project_name;
	if ($op == "mes") $mes_ok = "<span style='color:green;'>Записки сохранены</span>"; else $mes_ok = "";

	echo "<div style='margin:50px;'>";
	if (!empty($project_logotip)) echo "<img src='".$project_logotip."' width=300 class=center>";
	if (!empty($project_name)) echo "<br><font style='font-size:44px; color:gray;'>".$project_name."</font>";
	echo "</div>";

	echo "<table align=center width=100%><tr><td>
		<h2>Записки администратора</h2></td><td align=center>
		<button onclick=\"document.getElementById('adminmes').value+='\\r'+getDateNow()+'  '\" title='Вставить дату и время (в конце текста)' class='pill small punkt'><span class=\"icon gray small\" data-icon=\"6\"></span>Вставить дату</button>
		</td><td><form action='".$admin_file.".php?op=mes' method='post' name=form class=nothing>
		<button type='submit' class='small'><span class=\"icon gray small\" data-icon=\"C\"></span>Сохранить</button></td><td>".$mes_ok."
		</td></tr><tr><td colspan=3>
		<textarea id=adminmes name=adminmes rows=3 cols=80 style='border:0; height:300px; width: 98%;' class=yellow_grad>".$adminmes."</textarea>
	</td></tr></table>
	</form>
	</div>
	</td></tr></table>";

	global $display_errors;
	if ($display_errors == true) print("<!-- запросов: $db->num_queries \n $db->num_q -->");

	// Генерация XML-карты сайта
	//////////////////////////////////////
	$map = false;
	if (file_exists("map.xml")) if (date("Y-m-d", filectime("map.xml")) == date("Y-m-d")) $map = true;
	if ($map == false) {
		//$db->sql_query("TRUNCATE TABLE `".$prefix."_cash`");

		global $siteurl, $show_reserv;
		$output = "";
				$sql = "SELECT `pid`, `module`, `date` from ".$prefix."_".$pages." where `tables`='pages' and `active`='1' order by `date` desc limit 0,40000";
				$result = $db->sql_query($sql) or die("Не могу добавить в карту сайта страницы. Обратитесь к разработчику.");
				
				while ($row = $db->sql_fetchrow($result)) {
					$pid = $row['pid'];
					$module = $row['module'];
					//$comm = $row['comm'];
					$dat = explode(" ",$row['date']);
					$dat = $dat[0];
					// Добавление страниц
					$output .= "<url><loc>http://".$siteurl."/-".$module."_page_".$pid."</loc>\n<lastmod>".$dat."</lastmod>\n</url>\n"; // <priority>0.7</priority>\n
					// Добавление страниц с комментариями дополнительно
					//if ($comm > 0) $output .= "<url>\n<loc>http://".$siteurl."/-".$module."_page_".$pid."_comm</loc>\n<lastmod>".$dat."</lastmod>\n<priority>0.6</priority>\n</url>\n";
				}

				//$output .= "\n";
				// Добавление разделов
				$sql = "SELECT `name` from ".$prefix."_mainpage where `tables`='pages' and `name`!='index' and `type`='2'";
				$result = $db->sql_query($sql) or die("Не могу добавить разделы в карту сайта. Обратитесь к разработчику.");

				while ($row = $db->sql_fetchrow($result)) {
					$module = $row['name'];
					$output .= "<url>\n<loc>http://".$siteurl."/-".$module."</loc>\n<changefreq>weekly</changefreq>\n<priority>0.5</priority>\n</url>\n";
				}

			// Добавление тегов
			$tags = array();
			$sql = "select search from ".$prefix."_pages where `tables`='pages' and active='1' limit 0,1000";
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
			$tags = array_unique($tags);
			if (count($tags) > 0) {
				foreach( $tags as $tag_key => $tag_name ) {
					$output .= "<url>\n<loc>http://".$siteurl."/--slovo_".str_replace( "%","-", urlencode( $tag_name ) )."</loc>\n<changefreq>weekly</changefreq>\n<priority>0.4</priority>\n</url>\n";
				}
			}
		// Сборка карты сайта
		$date_now = date("Y-m-d");
		$output = '<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
		<url>
		<loc>http://'.$siteurl.'/</loc>
		<lastmod>'.$date_now.'</lastmod>
		<changefreq>daily</changefreq>
		<priority>0.9</priority>
		</url>'.$output.'</urlset>';

		$file = fopen("map.xml","wt");
		fputs($file, $output);
		fclose($file);

		if ($show_reserv == 1) echo "<iframe src=sys.php?op=backup style='display:none;'></iframe>";
	}
	echo "</div>\n</body>\n</html>";
}
/////////////////////////////////////////////////////////
function red_vybor() { // Выбор редактора (перенести в админку)
  global $url;
  $link = str_replace("&red=0","",str_replace("&red=1","",str_replace("&red=2","",str_replace("&red=3","",str_replace("&red=4","",$url)))));
  echo "
  <button id='rerurn' class='medium orange' type=button onclick=\"show('red_vybor');\" style='float:right;margin:3px;'><span style='margin-right: -2px;' class=\"icon white medium\" data-icon=\"7\"></span></button>

  <div id='red_vybor' style='position: absolute; z-index:666; right:5px; top:5px; padding:5px; width:647px; background:white; display:none; border:solid 10px gray;' class=radius>
  <a onclick=show('red_vybor') style='cursor:pointer; float:right;' title='Закрыть'><img class='icon2 i33' src='/images/1.gif'></a>
  <h1>Выбор редактора</h1>
  <!-- 
  <b>У всех редакторов есть:</b> Вставка ссылок, фотографий и таблиц, Жирность, Наклонность, Цвет текста, Верхний индекс, Списки, Заголовки, Центрирование и HTML-код 

<a href='".$link."&red=0' class='dark_pole3'>
  <img src=/images/0.jpg><br>
  <h2 style='display:inline'>Простой</h2> Разделительная черта, flash-ролики, Чистка HTML при вставке из Word'а или с сайтов</a>

<a href='".$link."&red=2' class='dark_pole3'>
  <img src=/images/2.jpg><br>
  <h2 style='display:inline'>Навороченный</h2> Поиск/Замена, Размер текста, Цитата, Отступы списков, Подстрочный индекс, Зачеркнутый текст, flash-ролики, Смайлики, Спецсимволы, Разделительная черта, Цвет фона текста, Вставить из Word'а, Maximaze (во весь экран)</a>
-->
<a href='".$link."&red=3' class='dark_pole3'>
  <img src=/images/3.jpg><br>
  <h2 style='display:inline'>Визуальный №1</h2> Быстрая вставка фотографий, Удобная работа с таблицами, Вставка видео-роликов и файлов, Автоматическая чистка! при вставке из Word'а, Во весь экран, Зачеркнутый текст, Заливка текста (Цвет фона), Отступы списков.</a>

<a href='".$link."&red=4' class='dark_pole3'>
  <img src=/images/4.jpg><br>
  <h2 style='display:inline'>Визуальный №2</h2> Возможности редактора: возможности редактора «Визуальный №1», Загрузка файлов и фотографий путем переноса мышкой (Drag&Drop), Изменение размеров фотографий движением мышки после их удерживания, Автоматическое изменение размера окна редактора.</a>

<a href='".$link."&red=1' class='dark_pole3'>
  <img src=/images/1.jpg><br>
  <h2 style='display:inline'>HTML-код</h2> Невизуальный редактор для понимающих HTML-разметку.</a>

  </div>";
}
function adminMain() {
	include("ad-header.php");
	GraphicAdmin();
	admin_footer(); //include("ad-footer.php");
}

if($admintest) {
	switch($op) {
		case "mes";
			$db->sql_query("UPDATE `".$prefix."_config` SET `adminmes` = '".$adminmes."' LIMIT 1 ;") or die ('Не сохранилось...');
			adminMain();
			break;
		case "GraphicAdmin":
			GraphicAdmin();
			break;
		case "adminMain":
			adminMain();
			break;
		case "logout":
			setcookie("admin", false);
			$admin = "";
			die("Вы вышли из администрирования!");
			//Header("Refresh: 2; url=".$admin_file.".php");
			//admin_footer();
			break;
		case "login";
			unset($op);
			login();
			break;
		default:
			if (!is_admin($admin)) login();
			else include("ad/!link_cases.php");
			break;
	}
} else {
	switch($op) {
		default:
			login();
			break;
	}
}
?>