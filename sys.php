<?php
/* Движок: Content Management System */
define('ADMIN_FILE', true);
if(isset($aid)) {
  if($aid AND (!isset($admin) OR empty($admin)) AND $op!='login') {
  unset($aid);
  unset($admin);
  die("Доступ закрыт! (sys)");
  }
}
require_once("mainfile.php");

// Закрытие доступа для других сайтов ##############################################
/*
global $siteurl;
if (strtoupper($_SERVER['HTTP_HOST']) != strtoupper($siteurl)) {
	echo "Попытка взлома!";
	die();
}
*/
##################################################################################

// NEW 28.09.2011
//Security XSS Prevention by Zhen-Xjell 22 Mar 2004 NukeCops.com Copyright All Rights Reserved
/*
$refer = $_SERVER['HTTP_REFERER'];
if (!preg_match("/ad.php/", "$refer")) {
	echo "<BR>$refer<BR>die";
	exit;
}
*/

$checkurl = $_SERVER['REQUEST_URI'];

// NEW 28.09.2011
// Addition by Zhen-Xjell NukeCops.com Mar 22 2004 Copyright All Rights Reserved
if (preg_match("/AddAuthor/", "$checkurl")) {
echo "die";
exit;
}
if (preg_match("/UpdateAuthor/", "$checkurl")) {
echo "die";
exit;
}

if((stripos_clone($checkurl,'AddAuthor')) OR (stripos_clone($checkurl,'VXBkYXRlQXV0aG9y')) OR (stripos_clone($checkurl,'QWRkQXV0aG9y')) OR (stripos_clone($checkurl,'UpdateAuthor')) OR (stripos_clone($checkurl, "?admin")) OR (stripos_clone($checkurl, "&admin")) OR (stripos_clone($checkurl,'%20union%20') OR stripos_clone($checkurl,'*%2f*') OR stripos_clone($checkurl,'/*') OR stripos_clone($checkurl,'*/union/*') OR stripos_clone($checkurl,'c2nyaxb0') OR stripos_clone($checkurl,'+union+') OR (stripos_clone($checkurl,'cmd=') AND !stripos_clone($checkurl,'&cmd')) OR (stripos_clone($checkurl,'exec') AND !stripos_clone($checkurl,'execu')) OR stripos_clone($checkurl,'concat'))) {
die("Попытка взлома!");
}

global $admin_file;

$the_first = $db->sql_numrows($db->sql_query("SELECT * FROM ".$prefix."_authors"));
if ($the_first == 0) {
	if (!$name) {
		include("ad-header.php");

		echo "<center>Подождите, какие-то проблемы с подключением к базе данных... Если через полчаса-час не наладится - свяжитесь с разработчиком или хостингом.</center><br>";
		// 
		admin_footer(); //include("ad-footer.php");
	}
	/*
	switch($fop) {
		case "create_first":
		create_first($name, $url, $email, $pwd, $user_new);
		break;
	}
	*/
	die();
}

  if (isset($aid) && (ereg("[^a-zA-Zа-яА-Я0-9_-]",trim($aid)))) {
   die("Begone");
  }
  if (isset($aid)) { $aid = substr($aid, 0,25);}
  if (isset($pwd)) { $pwd = substr($pwd, 0,40);}
  if ((isset($aid)) && (isset($pwd)) && (isset($op)) && ($op == "login")) {
	$datekey = date("F j");
	$rcode = hexdec(md5($_SERVER['HTTP_USER_AGENT'] . $sitekey . $_GET['random'] . $datekey));
	$code = substr($rcode, 2, 6);
	
	//if (extension_loaded("gd") AND $code != $_GET['che']) {
		//Header("Location: ".$admin_file.".php");
		//die('Ошибка: Код введен не верно');
	//}
	if(!empty($aid) AND !empty($pwd)) {
		$pwd = md5($pwd);
		$result = $db->sql_query("SELECT pwd FROM ".$prefix."_authors WHERE aid='$aid'");
		list($rpwd) = $db->sql_fetchrow($result);
		if($rpwd == $pwd) {
			$admin = base64_encode("$aid:$pwd");
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
		$admintest=0;
		$alert = "<html>\n<title>Да вы взломщик! (Go out!)</title>\n<body bgcolor=#FFFFFF text=#000000>\n\n<br><br><br>\n\n<center><br><br>\n<font size=\"+4\"><b>Да вы, вероятно, взломщик! (Go out!)</b><br>Если вы не взломщик, а администратор этого сайта, почистите, пожалуйста, куки (cookie). Например так: меню браузера Сервис -> Свойства обозревателя -> Удалить... -> Удалить \"Cookie\".</font></center>\n</body>\n</html>\n";
		die($alert);
	}
	$aid = substr($aid, 0,25);
	$result2 = $db->sql_query("SELECT name, pwd FROM ".$prefix."_authors WHERE aid='$aid'");
	if (!$result2) {
		die("Выбор из базы данных не удался! На лицо проблемы!");
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



global $razdel_sort;
$razdel_sort = intval($razdel_sort);
if ( $razdel_sort == 1 or $razdel_sort == 2 or $razdel_sort == 0 ) setcookie("razdel_sort", $razdel_sort, time()+60*60*24*360);


/*********************************************************/
/* Login Function   */
/*********************************************************/

function login() {
	global $admin_file;
	mt_srand ((double)microtime()*1000000);
	$random = mt_rand(0, 1000000);
	header ("Content-Type: text/html; charset=utf-8");
	//echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd\">";
	echo "<!doctype html>
<html lang=\"ru-RU\" dir=\"ltr\">
<head><title>Вход в Администрирование</title><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><meta http-equiv='pragma' content='no-cache' /><meta http-equiv='no-cache' /><meta http-equiv='cache-control' content='no-cache' /><link rel=\"stylesheet\" href=\"ad-style.css\" type=\"text/css\"><link REL=\"shortcut icon\" href=\"favicon_cms.ico\" type=\"image/x-icon\"></head>
	<body>
	<center style='margin-top:200px;'><h1>Вход в администрирование</h1>";
	echo "<br><br>";
	echo "<form action='red' style=\"background: red url(images/adfon/default.jpg);\" method='post'>"
	."<table>"
	."<tr><td align=right>Псевдоним: </td>"
	."<td><input type=text name=aid size=20></td></tr>"
	."<tr><td align=right>Пароль: </td>"
	."<td><input type=password name=pwd size=20></td></tr>";
	//if (extension_loaded("gd")) echo "<tr><td align=right>Код:</td><td><img src='?gf=gf&amp;random=$random' border='1' alt='Проверочный код' title='Код'></td></tr><tr><td align=right>Напишите код:</td><td><input type=text name=che size=7 maxlenght=6><input class=hide type=text name=gfx_check size=7 maxlenght=6></td></tr>";
	//else echo "<tr><td colspan='2'>Ошибка: Не могу отобразить проверочный код!</td></tr>";
	echo "<tr><td><input type=hidden name=password value=$random><input type=hidden name=op value=login>"
	."</td><td><input type=submit value=\" Войти \"></td></tr></table></form></center>
	</body></html>";
}

function deleteNotice($id) {
	global $prefix, $db, $admin_file;
	$id = intval($id);
	$db->sql_query("DELETE FROM ".$prefix."_reviews_add WHERE id = '$id'");
	Header("Location: ".$admin_file.".php?op=reviews");
}

function GraphicAdmin() {
	global $aid, $admin, $prefix, $db, $counter, $admin_file;
	$row = $db->sql_fetchrow($db->sql_query("SELECT realadmin FROM ".$prefix."_authors WHERE aid='$aid'"));
	$realadmin = intval($row['realadmin']);
global $admin, $prefix, $db, $admin_file;
$inf_base = "";
if (file_exists("map.xml")) {
if (date("Y-m-d", filectime("map.xml")) != date("Y-m-d")) {
	// Починка БД
	$result = $db->sql_query('SHOW TABLE STATUS');
	if ($db->sql_numrows($result)) {
	$local_query = array();
		while ($row = $db->sql_fetchrow($result)) {
			if (strpos(" ".$row[0]." ",$prefix)) $local_query[] = $row[0];
		}
		$db->sql_query('REPAIR TABLE '.implode(", ",$local_query));
	}
	//$inf_base = "3. База данных сайта сжата до ".(intval($total_all)/1000)." Мбайт";
}
}
///////////////////////////////////////////////////////////////
// Показываем основные возможности - ред. разделов.
///////////////////////////////////////////////////////////////

//echo "<table width=100% cellpadding=0 cellspacing=0 border=0><tr valign=top><td>";

////////// Комментарии

	$comments = "";
	$pageslist = "";
	
	$pages = "pages";
	
	//$sql4 = "SELECT pid, title, module from ".$prefix."_pages order by date desc limit 0,1000";
	//$result4 = $db->sql_query($sql4);
	//$line_id = array();
	//$titles = array();
	//$modules = array();
	//	while ($row4 = $db->sql_fetchrow($result4)) {
	//		$pid = $row4['pid'];
			//$line_id[] = $pid;
			//$modules[$pid] = $row4['module'];
			//$titles[$pid] = strip_tags($row4['title'], '<b><strong><em><i>');
	//	}

	global $show_comments, $show_userposts;
	$date_now1 = date("Y.m.d");
	$date_now2 = date("Y.m.d",time()-86400);
	$date_now3 = date("Y.m.d",time()-172800);
	
	$comm_segodnya = $db->sql_numrows($db->sql_query("select `cid` from ".$prefix."_".$pages."_comments where `tables`='pages' and data like '$date_now1 %'"));
	$comm_vchera = $db->sql_numrows($db->sql_query("select `cid` from ".$prefix."_".$pages."_comments where `tables`='pages' and data like '$date_now2 %'"));
	$comm_pozavchera = $db->sql_numrows($db->sql_query("select `cid` from ".$prefix."_".$pages."_comments where `tables`='pages' and data like '$date_now3 %'"));
		
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

/*
if (date("Y-m-d", filectime("map.xml")) != date("Y-m-d")) {
global $siteurl;
$url = str_replace("http://","",$siteurl);
$url = str_replace("www.","",$url);
$url = str_replace(".ru","",$url);
$url = str_replace("/","",$url);
$url = str_replace(".","_",$url);
$strFileName = "BackUp";
$date_jour = str_replace(" ", "_", date2normal_view(date ("Y-m-d"),0,0,1));
$link = $strFileName."_".$url."_".$date_jour.".zip";
echo "<br><img src=spaw2/uploads/images/icons/zip.gif align=bottom> <a href='backup/$link' class='gray'>Скачать архив сайта</a><br>";
// Очистка кеша
/////////////////////////////////////////////
}
*/
echo "<table style='width:100%; background: #e2e5ea; margin-top:5px; padding:0;' cellspacing=0 cellpadding=5><tr valign=top><td id='razdel_td' class='radius nothing'>
<div id='razdels' style='background:#e7e9ec;'>";

/*
<nobr style='margin-left:20px'><a href=sys.php?op=mainpage&amp;id=1&amp;red=1 title='Редактировать в HTML'><img class=\"icon2 i34\" src=/images/1.gif><strong>HTML дизайн</strong></a></nobr>
<nobr style='margin-left:20px'><a href=sys.php?op=mainpage&amp;id=20 title='Редактировать'><img class=\"icon2 i34\" src=/images/1.gif><strong>CSS стиль</strong></a></nobr>
*/

global $razdel_sort;
// Сортировка разделов: 0 - цвет, 1 - алфавит, 2 - посещаемость
$razdel_sort_name = array("<a href=red?razdel_sort=0>цвету раздела</a>", "<a href=red?razdel_sort=1>названию</a>", "<a href=red?razdel_sort=2>посещаемости</a>");
if (!isset($razdel_sort)) if (!isset($_COOKIE["razdel_sort"])) { setcookie("razdel_sort", "0", time()+60*60*24*360); $razdel_sort = 0;}
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
	 //$c = 0;
	
///////////////// Разделы портала

	// Подсчет комментов и посещений страниц
	//$mod_counter = array();
	//$mod_comm = array();
	//$mod_pid = array();
	//$mod_pid_pass = array();
	//$result2 = $db->sql_query("select module, comm from ".$prefix."_pages where `active`='1' and `tables`='pages'");
	//while ($row2 = $db->sql_fetchrow($result2)) {
	//	$mod = $row2['module'];
	//	$mod_pid[$mod] = $mod_pid[$mod] + 1;
		//if ($row2['active']==0) $mod_pid_pass[$mod] = $mod_pid_pass[$mod] + 1;
	//	$mod_comm[$mod] = $mod_comm[$mod] + $row2['comm']; 
		//$mod_counter[$mod] = $mod_counter[$mod] + $row2['counter']; 
	//}

	//$raz_counter = array();
	//$result2 = $db->sql_query("select name from ".$prefix."_mainpage where type='2'");
	//while ($row2 = $db->sql_fetchrow($result2)) {
		//$raz = $row2['name'];
	//$raz_counter[$raz] = $row2['counter']; // Подсчет посещений разделов
	//}


global $registr, $admin_file, $show_page;

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

				echo "<div class='black_grad'><button id=new_razdel_button title='Сортировка...' class='small inset' onclick=\"show('sortirovka');\" style='float:left; margin:3px;'><span style='margin-right: -2px;' class=\"icon darkgrey small\" data-icon=\"|\"></span></button><button id=new_razdel_button title='Добавить раздел...' class='small inset' onclick=\"show_animate('add');\" style='float:right; margin:3px;'><span style='margin-right: -2px;' class=\"icon darkgrey small\" data-icon=\"+\"></span></button><span class='h1'>Разделы:</span>
				</div>".$razdel_txt."
				<div id='sortirovka' style='color: green; display:none;'>";

				//if ($num_razdel > 10) 
					echo "<p style=' margin-left:20px;'>Сортировка по: <br>".$razdel_sort_name[0].",<br>".$razdel_sort_name[1].",<br>".$razdel_sort_name[2]."</p>";  
				
				//if ($num_razdel > 20) echo " <nobr>Колонки: <a href=# onclick='$(\"#razdels\").attr(\"class\",\"colonki1\"); $(\"#razdel_td\").attr(\"width\",\"30%\")'>одна</a>, <a href=# onclick='$(\"#razdels\").attr(\"class\",\"colonki1\"); $(\"#razdel_td\").attr(\"width\",\"99%\")'>одна широкая</a>, <a href=# onclick='$(\"#razdels\").attr(\"class\",\"colonki2\"); $(\"#razdel_td\").attr(\"width\",\"99%\")'>две</a></nobr>";

				echo "</div>"; 
			
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
			<b>Название раздела:</b></td><td><input type=text name=title size=30 style='width:100%;'><br>
			Например: «О нас», «Наша продукция», «Каталог», «Контакты» и т.д.
			</td></tr><tr><TD align=right valign=top>
			Англ. название:</td><td><input type=text name=namo size=30 style='width:100%;'><br>
			Например: «about», «product», «catalog», «contact» и т.д. <br>
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
		<a class='base_page' href='/sys.php?op=mainpage&amp;id=24&amp;red=1' title='Редактировать главную страницу'><div class='dark_pole2'><span class='icon black ".$icon_size."' data-icon='4'></span><span class='plus20'><b>Главная страница</b></span> <span class='small' style='color:#e7e9ec'>(редактировать)</span></div></a>";

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
			$result3 = $db->sql_query("select pid from ".$prefix."_pages where `active`='1' and `tables`='pages' and module='$nam'");
			$size = $db->sql_numrows($result3);
			$result4 = $db->sql_query("select pid from ".$prefix."_pages where `active`!='1' and `tables`='pages' and module='$nam'");
			$size_off = $db->sql_numrows($result4);
			} else { $size = 0; $size_off = 0; }
		//$result4 = $db->sql_query("select sum(comm) as x from ".$prefix."_pages where `active`='1' and `tables`='pages' and module='$nam'");
		//$row4 = $db->sql_fetchrow($result4);
		//$mod_comm = $row4['x']; 
	 
		#####
		$type_opis = explode("|",switch_type($type,$nam,$id));
		//$color = $type_opis[1];
		$type_opisX = $type_opis[0];
		if ($nam!="index") {
			//$size = $mod_pid; //$db->sql_numrows($db->sql_query("select pid from ".$prefix."_pages where module='$nam'"));
			//$sizeX = $mod_pid_pass[$nam]; //$db->sql_numrows($db->sql_query("select pid from ".$prefix."_pages where module='$nam' and active='0'"));
			//$row2 = $db->sql_fetchrow($db->sql_query("select SUM(`comm`) i from ".$prefix."_pages where module='$nam'"));
			//$size2 = $mod_comm; //$row2['i'];
			//$row2 = $db->sql_fetchrow($db->sql_query("select SUM(`counter`) i from ".$prefix."_pages where module='$nam'"));
			//$size3 = $mod_counter[$nam]; //$row2['i'];
			//$row2 = $db->sql_fetchrow($db->sql_query("select counter from ".$prefix."_mainpage where name='$nam' and type='2'"));
			//$size4 = "\nПосещения раздела и папок: ".$raz_counter[$nam]; //$row2['counter'];
			//if (trim($size3) != "") $size4 .= ", страниц: ".$size3."\n"; else $size4 .= "\n";
			if ($size < 1) $size = ""; 
			if ($size_off < 1) $size_off = ""; else $size_off = "-".$size_off;
			
			//if ($size2 < 1) $size2 = ""; 
			//if ($size2 != 0) $size2 = "<a href=sys.php?op=base_comments&name=$nam title='Посмотреть комментарии этого раздела'>".$size2."</a>";
			//if ($sizeX < 1) $sizeX = ""; else $sizeX = " <font color=red>-$sizeX</font>";
			$type_opisX = "<span class='green' title='Включенные страницы'>".$size."</span>&nbsp;<span class='red' title='Отключенные страницы'>".$size_off."</span>"; //.$sizeX;
			if ($size < 1 and $size_off < 1) $type_opisX = "";
		} else if ($nam=="index") $type_opisX = "";

		if ($current_type != $type) {
			$current_type = $type; 

			
		}

		$text = explode("|",$text); 
		$options = $text[1];
		$text = $text[0];
		if (strpos($options,"base=")) $text = "base";


		switch ($color) {
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
			$sql2 = "SELECT `text` FROM ".$prefix."_mainpage where type='2' and name='$nam'";
			$result2 = $db->sql_query($sql2);
			$row2 = $db->sql_fetchrow($result2);
			$module_options = explode("|",$row2['text']); $module_options = $module_options[1]; 
			parse_str($module_options);
			$sql2 = "SELECT name FROM ".$prefix."_mainpage where id='$base'";
			$result2 = $db->sql_query($sql2);
			$row2 = $db->sql_fetchrow($result2);
			$baza_name  = $row2['name']; // Название таблицы БД 
			// $db->sql_numrows($db->sql_query("select id from ".$prefix."_base_".$baza_name."")).
			$type_opisX = " <nobr><a href=sys.php?op=base_base&name=".$nam." title='Открыть базу данных' style='margin-left:10px;'><img class=\"icon2 i34\" src=/images/1.gif></a>
			<a href=sys.php?op=base_base_create_base&base=".$baza_name."&name=".$nam."&amp;red=1 title='Добавить строку в базу данных' style='margin-left:5px;'><img class=\"icon2 i29\" src=/images/1.gif></a></nobr>
			";
		}

		//if (($type_opisX == "" or $type_opisX == "0") and !strpos($options,"base=")) $iconpage = "<span class='small'><a href='sys.php?op=mainpage&amp;id=".$id."' title='Редактировать главную страницу этого раздела'><img class=\"icon2 i35\" src=/images/1.gif></a></span>"; 
		//else
		if (!strpos($options,"base=")) {
			// num_ending($type_opisX, Array('страниц','страница','страницы'))
			$type_opisX = "<nobr><span class='small radius' style='border-bottom: #aaaaaa 1px solid;'><b>&nbsp;".$type_opisX."&nbsp;</b></span></nobr>";
		}
		$ver = mt_rand(10000, 99999); // получили случайное число
//<a class='base_page' href='/sys.php?op=mainpage&amp;id=24&amp;red=1' title='Редактировать главную страницу'><div class='dark_pole2'><span class='icon black medium' data-icon='4'></span><span class='plus20'><b>Главная страница</b></span> <span class='small' style='color:#dddddd'>(редактировать)</span></div></a>

		echo "<div id='mainrazdel".$id."' class='dark_pole2'><div style='float:right'>".$iconpage.$type_opisX."</div><a class='base_page' title='Нажмите для просмотра действий над этим разделом и его содержимым' onclick='razdel_show(\"".$title."\", ".$id.", \"".$nam."\", \"".$text."\");'><div id='mainrazdel".$id."'>
		<span class='icon ".$color." ".$icon_size."' data-icon=','></span><span class='plus20'>".$title."</span>
		
		</div></a></div>";
    }
echo "</div>
</td><td width=70%>

".$soderganie_menu.$new_razdel."<div class='podrazdel radius nothing' id='podrazdel'>";

// ЗАПИСКИ
$row = $db->sql_fetchrow($db->sql_query("SELECT adminmes from ".$prefix."_config"));
$adminmes = $row['adminmes'];
global $op;
if ($op == "mes") $mes_ok = "<span style='color:green;'>Записки сохранены</span>"; else $mes_ok = "";

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
// <span class='small nothing'>Для просмотра страниц (и папок) нажмите по Названию раздела. У большинства элементов при наведении выводятся подсказки.</span><br><br>
// onmouseover=\"resize('adminmes', '360', '600', '300', '500');\" 

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

/////////////////////////////////////
echo "
</div>
</body>
</html>";
// Было сохранено $total_gain Кбайт
//	$sql_query = "UPDATE ".$prefix."_optimize_gain SET gain='$total_gain', data='$dat' WHERE id='1'";
//	$result = $db->sql_query($sql_query);
//	}
}

function adminMain() {
include("ad-header.php");
GraphicAdmin();
admin_footer(); //include("ad-footer.php");
}

if($admintest) {

	switch($op) {

		case "gf":
		gf();
		break;

		case "mes";
		$db->sql_query("UPDATE `".$prefix."_config` SET `adminmes` = '$adminmes' LIMIT 1 ;") or die ('Не сохранилось...');
		adminMain();
		break;

		case "deleteNotice":
		deleteNotice($id);
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
		include("ad-header.php");
		// 
		echo "<center><font class=title><b>Вы вышли из администрирования!</b></font></center>";
		// 
		Header("Refresh: 3; url=".$admin_file.".php");
		admin_footer(); //include("ad-footer.php");
		break;

		case "login";
		unset($op);
		login();

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