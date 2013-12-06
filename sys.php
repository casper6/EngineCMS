<?php
// Все «правила хорошего кода» написаны кровью, вытекшей из глаз программистов, читавших чужой код.
define('ADMIN_FILE', true);
if(isset($aid)) {
  if(!empty($aid) AND (!isset($admin) OR empty($admin)) AND $op!='login') {
  unset($aid);
  unset($admin);
  die(aa("Доступ закрыт! (sys)"));
  }
}
require_once("mainfile.php");
$checkurl = $_SERVER['REQUEST_URI'];
if((stripos($checkurl,'VXBkYXRlQXV0aG9y')) OR (stripos($checkurl,'QWRkQXV0aG9y')) OR (stripos($checkurl, "?admin")) OR (stripos($checkurl, "&admin")) OR (stripos($checkurl,'%20union%20') OR stripos($checkurl,'*%2f*') OR stripos($checkurl,'/*') OR stripos($checkurl,'*/union/*') OR stripos($checkurl,'c2nyaxb0') OR stripos($checkurl,'+union+') OR (stripos($checkurl,'cmd=') AND stripos($checkurl,'&cmd')===false) OR (stripos($checkurl,'exec') AND stripos($checkurl,'execu')===false) OR stripos($checkurl,'concat'))) die(aa("Попытка взлома")." №8");

global $admin_file, $siteurl, $show_reserv;

$the_first = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_authors"));
if ($the_first == 0) {
	if (!$name) {
		include("ad/ad-header.php");
		echo "<center>".aa("Подождите, возникли проблемы с подключением к базе данных... Если через полчаса-час не наладится - свяжитесь с разработчиком или хостинг-провайдером.")."</center><br>";
		//admin_footer();
	}
	die();
}

	if (!isset($_POST['random_num'])) $_POST['random_num'] = "";
  	if (isset($aid) && (ereg("[^a-zA-Zа-яА-Я0-9_-]",trim($aid)))) {
   		die("...");
  	}
  	if (isset($aid)) { $aid = substr($aid, 0,25);}
  	if (isset($pwd)) { $pwd = substr($pwd, 0,40);}
  	if ((isset($aid)) && (isset($pwd)) && (isset($op)) && ($op == "login")) {
		$datekey = date("F j");
		$rcode = hexdec(md5($_SERVER['HTTP_USER_AGENT'] . $sitekey . $_POST['random_num'] . $datekey)); // $_GET['random']
		$code = substr($rcode, 2, 6);
		if(!empty($aid) AND !empty($pwd)) {
			$pwd = md5($pwd);
			$result = $db->sql_query("SELECT `pwd` FROM ".$prefix."_authors WHERE `aid`='".$aid."'");
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
	if (empty($aid) OR empty($pwd))
		die(aa("Если вы не взломщик, а администратор этого сайта, почистите, пожалуйста, куки (cookie). Например так: меню браузера Сервис -> Свойства обозревателя -> Удалить... -> Удалить \"Cookie\"."));
	$aid = substr($aid, 0,25);
	$result2 = $db->sql_query("SELECT `name`, `pwd` FROM ".$prefix."_authors WHERE `aid`='".$aid."'");
	if (!$result2)
		die(aa("Выбор из базы данных не удался!"));
	else {
		list($rname, $rpwd) = $db->sql_fetchrow($result2);

		if($rpwd == $pwd && !empty($rpwd))
			$admintest = 1;
	}
}
// Включение режима редактора (отключение функций админа)
global $editor_style;
$editor_style = false;
if (isset($rname)) if ($rname == "EDITOR") $editor_style = true;

if(!isset($op))
	$op = "adminMain"; 
elseif(($op=="mod_authors" || $op=="admins_list" || $op=="admins_delete" || $op=="admins_edit" || $op=="modifyadmin" || $op=="update_author" || $op=="admins_edit_save" || $op=="admins_add" || $op=="options") AND ($rname != "BOG" && $rname != "ADMIN"))
  	die(aa("$rname Запрещенная операция! Возможно, вы только что сменили имя и/или пароль администратора - тогда перейдите ко <a href='sys.php?op=login'>входу в администрирование</a>."));

$pagetitle = "- ".aa("Администрирование");

// Стирание кеша главной страницы
if (is_admin($admin)) recash("/");

// Вход в Админку
function login() {
	global $admin_file, $lang_admin;
	mt_srand ((double)microtime()*1000000);
	$random = mt_rand(0, 1000000);
	header ("Content-Type: text/html; charset=utf-8");
	echo "<!doctype html>
<!--[if lt IE 7 ]><html class='ie ie6 no-js lt-ie9 lt-ie8 lt-ie7' lang='".$lang_admin."'> <![endif]-->
<!--[if IE 7 ]><html class='ie ie7 no-js lt-ie9 lt-ie8' lang='".$lang_admin."'> <![endif]-->
<!--[if IE 8 ]><html class='ie ie8 no-js lt-ie9' lang='".$lang_admin."'> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang='".$lang_admin."' dir='ltr' class='no-js'> <!--<![endif]-->
<head>";
if (file_exists("favicon.png"))  echo "<link rel='shortcut icon' href='favicon.png' />";
else echo "<link rel='shortcut icon' href='favicon.ico' />";
echo "<title>".aa("Вход в Администрирование")."</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<meta http-equiv='Content-language' content='".$lang_admin."'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<!--[if lt IE 9]><script src='includes/html5.js'></script><![endif]-->
<meta http-equiv='pragma' content='no-cache' /><meta http-equiv='no-cache' /><meta http-equiv='cache-control' content='no-cache' /><link rel='stylesheet' href='ad/ad-style.css' type='text/css'>
<link REL='shortcut icon' href='images/favicon_cms.png' type='image/x-icon'><script src='includes/jquery183.min.js'></script>
<script type='text/javascript' src='includes/css-frameworks/kickstart/js/kickstart.js'></script><link rel='stylesheet' type='text/css' href='includes/css-frameworks/kickstart/css/kickstart.css' media='all' /><link rel='stylesheet' type='text/css' href='includes/css-frameworks/kickstart/style.css' media='all' />
</head>
<body style='background: rgb(236, 220, 252);' class='elements'>
<div class='grid'><form action='red' class=radius style='margin-top:20px; ' method='post' id=form>
<h5>".aa("Вход в администрирование")."</h5>
<input type=text name=aid size=20> <label>".aa("Псевдоним")."</label><br>
<input type=password name=pwd size=20> <label>".aa("Пароль")."</label><br><br>
<input class='large green mt5 w100' type=submit value=' ".aa("Войти")." '>
<input type=hidden name=password value='".$random."'>
<input type=hidden name=op value=login></form></div>
<script>$('#form').submit(function(e) {e.preventDefault();$('#form').animate({opacity: 0.1}, 1500,function(){ $('#form').unbind('submit').submit(); });});</script></body></html>";
}

function GraphicAdmin() {
	global $aid, $admin, $prefix, $db, $counter, $admin_file, $show_comments, $show_userposts, $razdel_sort, $registr, $show_page, $show_reserv;
	//$row = $db->sql_fetchrow($db->sql_query("SELECT `realadmin` FROM ".$prefix."_authors WHERE `aid`='".$aid."'"));
	//$realadmin = intval($row['realadmin']);
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
		}
	}
	// Показываем основные возможности - ред. разделов.
	// Комментарии
	$comments = "";
	$pageslist = "";
	$pages = "pages";
	$date_now1 = date("Y.m.d");
	$date_now2 = date("Y.m.d",time()-86400);
	$date_now3 = date("Y.m.d",time()-172800);
	$comm_segodnya = $db->sql_numrows($db->sql_query("SELECT `cid` FROM ".$prefix."_".$pages."_comments where `tables`='pages' and `data` like '".mysql_real_escape_string($date_now1)." %'"));
	$comm_vchera = $db->sql_numrows($db->sql_query("SELECT `cid` FROM ".$prefix."_".$pages."_comments where `tables`='pages' and `data` like '".mysql_real_escape_string($date_now2)." %'"));
	$comm_pozavchera = $db->sql_numrows($db->sql_query("SELECT `cid` FROM ".$prefix."_".$pages."_comments where `tables`='pages' and `data` like '".mysql_real_escape_string($date_now3)." %'"));

	if ($show_userposts != 0) {
		$num_add_pages = $db->sql_numrows($db->sql_query("SELECT `pid` FROM ".$prefix."_pages where (`active`='2' or `active`='3') and `tables`!='del'"));
	}

	//$soderganie_menu = "<button id='new_razdel_button' title='".aa("Добавить страницу...")."' class='small green nothing left3' onclick='location.href=\"/sys.php?op=base_pages_add_page#1\"'><span class=\"icon white small\" data-icon=\"+\"></span></button>";
 	
 	$soderganie_menu = "";
 	global $deviceType;
 	$buttons = explode(",", aa(" Новое, Проверить: , Удаленное, Старое, Блоки, Отзывы:, Вставить дату"));
	if ($deviceType != 'computer') $buttons = array('','','','','','','');

	if ($comm_segodnya > 0) $color_comm = " orange"; else $color_comm = "";
	if ($show_comments == 1) $soderganie_menu .= "<button id='openbox3' class='nothing dark_pole2".$color_comm."' onclick=\"openbox('3','".aa("Комментарии")."'); $('#razdels').hide('slow')\" title='".aa("Комментарии за сегодня/вчера/позавчера")."'><span class=\"icon gray small\" data-icon=\"'\"></span><nobr>".$buttons[5]." ".$comm_segodnya."/".$comm_vchera."/".$comm_pozavchera."</nobr></button>";

	$soderganie_menu .= " <button id='openbox5' class='nothing dark_pole2' onclick=\"openbox('5','".aa("Новое и отредактированное")."'); $('#razdels').hide('slow')\" title='".aa("Новые и отредактированные страницы, свежие изменения")."'><span class=\"icon gray small\" data-icon=\"M\"></span>".$buttons[0]."</button>";
	if (!isset($num_add_pages)) $num_add_pages = 0;

	if ($num_add_pages > 0 and $show_userposts != 0) $soderganie_menu .= "<button id='openbox4' class='nothing dark_pole2 orange' style='color: red;' onclick=\"openbox('4','".aa("Добавленное посетителями")."'); $('#razdels').hide('slow')\"><span class=\"icon gray small\" data-icon=\"u\"></span><nobr>".$buttons[1]."<strong>".$num_add_pages."</strong></nobr></button>";

	$del_page = $db->sql_numrows($db->sql_query("SELECT `pid` FROM ".$prefix."_".$pages." where `tables`='del' limit 0,1"));
	$del_razdel = $db->sql_numrows($db->sql_query("SELECT `id` FROM ".$prefix."_mainpage where `type`='2' and `tables`='del' limit 0,1"));

	if ($del_page > 0 || $del_razdel > 0) $deleted_class = ""; else $deleted_class = " hide";
	$soderganie_menu .= "<button id='openbox1' class='nothing dark_pole2".$deleted_class."' onclick=\"openbox('1','".aa("Удаленное")."'); $('#razdels').hide('slow')\" title='".aa("Удаленные страницы")."'><span class=\"icon gray small\" data-icon=\"T\"></span>".$buttons[2]."</button>";

	$backup_page = $db->sql_numrows($db->sql_query("SELECT `pid` FROM ".$prefix."_".$pages." where `tables`='backup' limit 0,1"));
	if ($backup_page > 0) $soderganie_menu .= "<button id='openbox2' class='nothing dark_pole2' onclick=\"openbox('2','".aa("Резервные копии")."'); $('#razdels').hide('slow')\" title='".aa("Резервные копии созданных ранее страниц")."'><span class=\"icon gray small\" data-icon=\"t\"></span>".$buttons[3]."</button>";

	$soderganie_menu .= " <button id='mainrazdel3' class='nothing dark_pole2' onclick=\"oformlenie_show('блок','3','block','/sys.php?op=mainpage&name=block&type=3'); $('#razdels').hide('slow')\" title='".aa("Резервные копии созданных ранее страниц")."'><span class=\"icon gray small\" data-icon=\"R\"></span>".$buttons[4]."</button> ";

	echo "<table cellspacing=0 cellpadding=0 class='light_fon w100 pm0 mw800'><tr valign=top><td id='razdel_td' class='pm0 nothing'><div id='razdels' style='min-width:400px;'>";

	$subg = "";
	$sql = "select * from ".$prefix."_mainpage where `tables`!='del' and type='2' order by ".mysql_real_escape_string($razdel_sort);
	$result = $db->sql_query($sql) or die(aa("Ошибка: не найдена таблица разделов."));
	$current_type = ""; 

	$num_razdel = $db->sql_numrows($db->sql_query("SELECT `id` FROM ".$prefix."_mainpage where `type`='2' and `name`!='index' and `tables`!='del'"));

	$razdel_txt = "";
	if ($num_razdel == 0) $razdel_txt = "<div style='padding-left:5px; color: red;'>".aa("Разделов пока нет. Добавьте, нажав на кнопку +. Если вам нужен одностраничный сайт — нажмите по кнопке «Главная страница» для её редактирования.")."</div>";

	if ($registr=='1') echo "&nbsp;&nbsp;&nbsp;<a href='".$admin_file.".php?op=MainUser'>".aa("Пользователи")."</a> <a href=".$admin_file.".php?op=sortuser>".aa("Список")."</a>";

	echo "<div class='curved-vt-2 hide' style='margin-left:-350px; width: 700px; top: 10px;' id='add'></div>
	<div class='black_grad'>
	<button id='new_razdel_button' title='".aa("Добавить раздел...")."' class='small right3' onclick=\"openbox('10','', 'add'); $('.dark_pole2sel').attr('class', 'dark_pole2');\"><span class=\"mr-2 icon darkgrey small\" data-icon=\"+\"></span></button>
	<span class='h1'>".aa("Разделы:")."</span>
		</div>".$razdel_txt."
		<div style='max-height:700px;'>";

	$icon_size = "large";
	if ($num_razdel > 10) $icon_size = "medium"; 
	//if ($num_razdel > 10) $icon_size = "small"; 

    while ($row = $db->sql_fetchrow($result)) {
	    $id = $row['id'];
	    $type = $row['type'];
	    $nam = $row['name'];
	    if (strpos($nam, "\n")) { // заменяем имя запароленного раздела
	        $nam = explode("\n", str_replace("\r", "", $nam));
	        $nam = trim($nam[0]);
	    }
	    $title = strip_tags($row['title'], '<b><i>');
	    if ($type == 3) $title = "[$title]";
		$color = $row['color'];
	    $text = $row['text'];
	    $useit = $row['useit'];
		$tables = $row['tables'];
		if ($show_page == 1) {
			$result3 = $db->sql_query("SELECT `pid` FROM ".$prefix."_pages where `active`='1' and `tables`='pages' and `module`='".$nam."'");
			$size = $db->sql_numrows($result3);
			$result4 = $db->sql_query("SELECT `pid` FROM ".$prefix."_pages where `active`!='1' and `tables`='pages' and `module`='".$nam."'");
			$size_off = $db->sql_numrows($result4);
		} else { 
			$size = 0; $size_off = 0; 
		}

		$type_opisX = "";
		if ($size < 1) $size = ""; 
		if ($size_off < 1) $size_off = ""; else $size_off = "&nbsp;<span class='red' title='".aa("Отключенные страницы")."'>-".$size_off."</span>";
		$type_opisX = "<span class='f14 pr10 pl10'><span class='green' title='".aa("Включенные страницы")."'>".$size."</span>".$size_off."</span>";
		if ($size < 1 and $size_off < 1) $type_opisX = "";
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
		// Для базы данных !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$link = "";
		$doping = "";
		if (strpos($options,"base=")) {
			$title = "БД «".trim($title)."»";
			$baza_name  = $nam; // Название таблицы БД 
			$type_opisX = "<nobr><span class='f14 radius bb1 pr10 pl10'><span class='green' title='".aa("Записи в базе данных")."'>".$db->sql_numrows($db->sql_query("SELECT `id` FROM ".$prefix."_base_".$nam))."</span></span> 
			<a class='button small ml10' href='sys.php?op=base_base&name=".$nam."' title='".aa("Открыть базу данных")."'><span class='icon small' data-icon='s'></span></a>
			<a class='button small ml5' href='sys.php?op=base_base_create_base&base=".$baza_name."&name=".$nam."&amp;red=1#1' title='".aa("Добавить строку в базу данных")."'><span class='icon small' data-icon='+'></span></a></nobr>";
		}
		if (!strpos($options,"base=")) {
			$type_opisX = "".$type_opisX."
			<a class='button small ml5' href='sys.php?op=base_pages_add_page&name=".$nam."#1' title='".aa("Добавить страницу в раздел")."'><span class='icon small' data-icon='+'></span></a>";
		}
		$ver = mt_rand(10000, 99999); // получили случайное число

		if (strpos($options,"base=")) { // не содержит страниц
			$right = $type_opisX;
			$ico = "D";
			$reaction = "razdel_show(\"\", ".$id.", \"".$nam."\", \"database\");";
		} elseif (strpos(" ".$useit, aa("[содержание]")) || strpos(" ".$useit, aa("[страницы]")) 
			|| strpos(" ".$useit, "[содержание]") || strpos(" ".$useit, "[страницы]")) { // эту строчку можно будет убрать
			$right = $type_opisX;
			$ico = ",";
			$reaction = "razdel_show(\"\", ".$id.", \"".$nam."\", \"".$text."\");";
		} else { // не содержит страниц
			$title = trim($title)." (".aa("стр.").")";
			$right = "<a class='button small ml5' href='sys.php?op=mainpage&type=2&id=".$id."#1' title='".aa("Редактировать страницу раздела")."'><span class='icon small' data-icon='7'></span></a>";
			$ico = ".";
			$reaction = "razdel_show(\"\", ".$id.", \"".$nam."\", \"page\");";
		}
		if ($nam=="index") $title = "<b>".$title."</b>";
		echo "<div id='mainrazdel".$id."' class='dark_pole2'><div style='float:right'>".$right."</div><a class='base_page' title='".aa("Нажмите для просмотра действий над этим разделом и его содержимым")."' href=#1 onclick='".$reaction."'><div id='mainrazdel".$id."'>
		<span class='icon ".$color." ".$icon_size."' data-icon='".$ico."'></span><span class='plus20'>".$title."</span>
		</div></a></div>";
    }
	echo "</div></div>
	</td>
	<td style='padding:0;'><a class='punkt' title='Свернуть/развернуть левую колонку' onmousemove='$(\"#razdels\").show();' onclick='$(\"#razdels\").toggle(\"slow\");'><div class='polosa_razdelitel'><div id='rotateText'><nobr>↑ Сворачивает Разделы ↑</nobr></div></div></a></td>
	<td style='width:100%;padding:0;'><div class='black_grad'><div class='pt5'>".$soderganie_menu."</div></div><div class='podrazdel radius nothing' id='podrazdel'>";

	// ЗАПИСКИ
	$row = $db->sql_fetchrow($db->sql_query("SELECT `adminmes` FROM ".$prefix."_config"));
	$adminmes = $row['adminmes'];
	global $op, $project_logotip, $project_name;
	if ($op == "mes") $mes_ok = "<span class='green'>".aa("Записки сохранены")."</span>"; 
	else $mes_ok = aa("Записки");

	echo "<div style='margin:10px;'>";
	if (!empty($project_logotip) && file_exists($project_logotip)) echo "<img src='".$project_logotip."' class=center>";
	if (!empty($project_name)) echo "<br><font style='font-size:44px; color:gray;'>".$project_name."</font>";
	echo "</div>
	<form action='".$admin_file.".php?op=mes' method='post' name=form class='nothing' class='w100'>
		<div class='center light_fon' style='height:242px; border: #ddd 1px solid;'>
		<a id='adminmes_date' style='float:right; margin:3px; display:none;' onclick=\"document.getElementById('adminmes').value+='\\r'+getDateNow()+'  '\" title='".aa("Вставить дату и время (в конце текста)")."' class='button small ml20'><span class='icon gray small' data-icon='6'></span>".$buttons[6]."</a>
		<button id='adminmes_save' class='hide small punkt' type='submit' style='float:left; margin:3px;'><span class=\"icon gray small\" data-icon=\"c\"></span> ".aa("Сохранить")."</button><div class='h3' style='height:40px;'>".$mes_ok."</div>
		
		<textarea id='adminmes' name='adminmes' rows='3' cols='40' style='width:99%' class='f14 yellow_grad h200' onclick='$(\"#adminmes_save\").show(); $(\"#adminmes_date\").show();'>".$adminmes."</textarea>
		</div>
	</form>
	</div>
	</td></tr></table>";

	global $siteurl, $display_errors;
	if ($display_errors == true) print("<!-- ".aa("запросов:")." $db->num_queries \n $db->num_q -->");

	// Генерация XML-карты сайта
	$map = false;
	if (file_exists("map.xml")) if (date("Y-m-d", filectime("map.xml")) == date("Y-m-d")) $map = true;
	if ($map == false) {
		$output = "";
				$sql = "SELECT `pid`, `module`, `date` FROM ".$prefix."_pages where `tables`='pages' and `active`='1' order by `date` desc limit 0,40000";
				$result = $db->sql_query($sql) or die(aa("Не могу добавить в карту сайта страницы. Обратитесь к разработчику."));
				while ($row = $db->sql_fetchrow($result)) {
					$pid = $row['pid'];
					$module = $row['module'];
					$dat = explode(" ",$row['date']);
					$dat = $dat[0];
					// Добавление страниц
					$output .= "<url><loc>http://".$siteurl."/-".$module."_page_".$pid."</loc>\n<lastmod>".$dat."</lastmod>\n</url>\n"; // <priority>0.7</priority>\n
					// Добавление страниц с комментариями дополнительно
					//if ($comm > 0) $output .= "<url>\n<loc>http://".$siteurl."/-".$module."_page_".$pid."_comm</loc>\n<lastmod>".$dat."</lastmod>\n<priority>0.6</priority>\n</url>\n";
				}
				// Добавление разделов
				$sql = "SELECT `name` FROM ".$prefix."_mainpage where `tables`='pages' and `name`!='index' and `type`='2'";
				$result = $db->sql_query($sql) or die(aa("Ошибка: Не получается добавить разделы в карту сайта. Обратитесь к разработчику."));
				while ($row = $db->sql_fetchrow($result)) {
					$module = $row['name'];
					if (strpos($module, "\n")) { // заменяем имя запароленного раздела
						$module = explode("\n", str_replace("\r", "", $module));
						$module = trim($module[0]);
					}
					$output .= "<url>\n<loc>http://".$siteurl."/-".$module."</loc>\n<changefreq>weekly</changefreq>\n<priority>0.5</priority>\n</url>\n";
				}
			// Добавление тегов
			$tags = array();
			$sql = "SELECT `search` FROM ".$prefix."_pages where `tables`='pages' and `active`='1' limit 0,500";
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
					$output .= "<url>\n<loc>http://".$siteurl."/slovo_".str_replace( "%","-", urlencode( $tag_name ) )."</loc>\n<changefreq>weekly</changefreq>\n<priority>0.4</priority>\n</url>\n";
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
	echo "<br></div>\n</body>\n</html>";
}

function red_vybor() { // Выбор редактора
  global $url;
  $link = str_replace("&red=0","",str_replace("&red=1","",str_replace("&red=2","",str_replace("&red=3","",str_replace("&red=4","",$url)))));
  echo "
  <button id='rerurn' class='small orange' type=button onclick=\"show('red_vybor');\" style='float:right;margin:3px;'><span style='margin-right: -2px;' class=\"icon white medium\" data-icon=\"7\"></span> ".aa("Редактор")."</button>

  <div id='red_vybor' style='position: absolute; z-index:666; right:5px; top:5px; padding:5px; width:647px; background:white; display:none; border:solid 10px gray;' class=radius>
  <a onclick=show('red_vybor') style='cursor:pointer; float:right;' title='Закрыть'><img class='icon2 i33' src='/images/1.gif'></a>
  <h1>".aa("Выбор редактора")."</h1>

<a href='".$link."&red=3' class='dark_pole3'>
  <img src=/images/3.jpg><br>
  <h2 style='display:inline'>".aa("Визуальный №1")."</h2> ".aa("Быстрая вставка фотографий, Удобная работа с таблицами, Вставка видео-роликов и файлов, Автоматическая чистка! при вставке из Word'а, Во весь экран, Зачеркнутый текст, Заливка текста (Цвет фона), Отступы списков.")."</a>

<a href='".$link."&red=4' class='dark_pole3'>
  <img src=/images/4.jpg><br>
  <h2 style='display:inline'>".aa("Визуальный №2")."</h2> ".aa("Все возможности редактора «Визуальный №1», а также: Загрузка файлов и фотографий путем переноса мышкой, Изменение размеров фотографий движением мышки после их удерживания, Возможность назначения фотографиям ссылок (создание баннеров), Автоматическое изменение размера окна редактора, Поддержка заготовок текста (добавить заготовки можно в Настройках).")."</a>

<a href='".$link."&red=1' class='dark_pole3'>
  <img src=/images/1.jpg><br>
  <h2 style='display:inline'>".aa("HTML-код №1")."</h2> ".aa("Невизуальный редактор (простой текст и код).")."</a>

<a href='".$link."&red=2' class='dark_pole3'>
  <img src=/images/2.png><br>
  <h2 style='display:inline'>".aa("HTML-код №2")."</h2> ".aa("Невизуальный редактор с цветной подсветкой кода.")."</a>

  </div>";
}

function adminMain() {
	include("ad/ad-header.php");
	GraphicAdmin();
}

if($admintest) {
	switch($op) {
		case "mes";
			if (isset($adminmes)) $db->sql_query("UPDATE `".$prefix."_config` SET `adminmes` = '".mysql_real_escape_string($adminmes)."' LIMIT 1 ;") or die (aa("Не сохранилось..."));
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
			Header("Location: sys.php");
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