<?php
if (stristr(htmlentities($_SERVER['PHP_SELF']), "ad-header.php")) {
	Header("Location: in.php");
	die();
}
require_once("mainfile.php");
global $postlink, $name, $sitename, $op, $type, $red, $prefix, $db, $id, $nastroi, $kickstart;
$color1=$color2=$color3=$color4="gray"; // Цвета 4х кнопок основных категорий админки (Содержание...)

if ($postlink != "") $post = "<button class='small' onclick='location.href=\"".$postlink."\"' title='Открыть почтовый сайт...'><span class=\"icon small black\" data-icon=\"@\"></span>Почта</button> "; else $post="";

switch($kickstart) {
	case 1:
	$kick_link = "http://www.99lime.com/elements/";
	$kick_name = "KickStart"; break;
	case 2:
	$kick_link = "http://css-framework.ru/doc/utilites/";
	$kick_name = "CSSframework"; break;
	case 3:
	$kick_link = "http://www.getskeleton.com/#examples";
	$kick_name = "Skeleton"; break;
	case 4:
	$kick_link = "http://kubeframework.com";
	$kick_name = "Kube"; break;
	case 5:
	$kick_link = "http://twitter.github.com/bootstrap/base-css.html";
	$kick_name = "Bootstrap"; break;
	case 6:
	$kick_link = "http://cssgrid.net";
	$kick_name = "1140 Grid"; break;
	case 7:
	$kick_link = "http://daneden.me/toast/";
	$kick_name = "Toast"; break;
	case 8:
	$kick_link = "http://www.blueprintcss.org/tests/";
	$kick_name = "Blueprint"; break;
	default:
	$kick_link = "";
	$kick_name = ""; break;
}
if ($kickstart != 0) $kick = " <button class='small' onclick='location.href=\"".$kick_link."\"' title='Открыть сайт с примерами разметки CSS-фреймворка «".$kick_name."»'><span class=\"icon small black\" data-icon=\"S\"></span>".$kick_name."</button>";
else $kick = "";


$title = "Управление сайтом";
$color1="blue";
if ($op == "base_pages_add_page") $title = "Добавление страницы в раздел";
if ($op == "edit_base_pages_category") $title = "Редактирование папки";
if ($op == "base_pages_edit_page") $title = "Редактирование страницы";
if ($op == "subscribe") {$title = "Рассылка (список адресатов и их email'ов)"; $color1="gray"; $color3="blue";}
if ($op == "Configure") {$title = "Настройка сайта и администратора"; $color1="gray"; $color3="blue";}
if ($op == "mainpage" and $id > 0) {$title = "Редактирование оформления"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 0) {$title = "Редактирование дизайна (HTML)"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 1) {$title = "Редактирование стиля (CSS)"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 2) $title = "Редактирование раздела";
if ($op == "mainpage" and $type == 3) {$title = "Редактирование блока"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 4) {$title = "Редактирование поля"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 5) {$title = "Редактирование базы данных"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 6) {$title = "Редактирование шаблона"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "design") {$title = "Добавление дизайна (HTML)"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "css") {$title = "Добавление стиля (CSS)"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "block") {$title = "Добавление блока"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "spisok") {$title = "Добавление поля"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "base") {$title = "Добавление базы данных"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "shablon") {$title = "Добавление шаблона"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $nastroi == 1) {$title = "Настройка элемента сайта"; $color1="gray"; $color3="blue";}
if ($op == "mainpage" and $nastroi == 1 and $type == 3) {$title = "Настройка блока"; $color1="gray"; $color3="blue";}
if ($op == "users") {$title = "Настройка пользователей"; $color1="gray"; $color3="blue";}
if ($op == "mainpage" and $nastroi == 1 and $type == 2) {$title = "Настройка раздела"; $color1="gray"; $color3="blue";}
if ($op == "mainpage" and $type == "element") {$title = "Оформление сайта"; $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == "stat") {$title = "Общая статистика"; $color1="gray"; $color2="gray"; $color4="blue";}
if ($op == "mainpage" and $type == "stat_page") {$title = "Статистика посещений страниц"; $color1="gray"; $color2="gray"; $color4="blue";}
if ($op == "mainpage" and $type == "stat_search") {$title = "Статистика поисковых запросов"; $color1="gray"; $color2="gray"; $color4="blue";}
header ("Content-Type: text/html; charset=utf-8");
echo "<!doctype html>
<html lang=\"ru-RU\" dir=\"ltr\">
<head><title>".$title."</title>\n
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<meta http-equiv='pragma' content='no-cache' />
<meta http-equiv='no-cache' />
<meta http-equiv='cache-control' content='no-cache' />
<meta name='viewport' content='width=device-width'>
<link rel=\"stylesheet\" href=\"ad-style.css\" type=\"text/css\">
<link rel=\"shortcut icon\" href=\"favicon_cms.ico\" type=\"image/x-icon\">
<script src='includes/JsHttpRequest/JsHttpRequest.js'></script>
<script src='includes/jad.js'></script>
<script src='includes/j.js'></script>
<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js'></script>
<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js'></script> 
<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/i18n/jquery-ui-i18n.min.js'></script> 
<script src='includes/jquery.innerfade.js'></script>";

if (!$op) {
	echo "\n<script type=\"text/javascript\" src=\"includes/jeditable.js\"></script>
	<script type=\"text/javascript\" src=\"includes/form.js\"></script>
	<script type=\"text/javascript\" src=\"includes/select.js\"></script>
	<script type=\"text/javascript\" src=\"includes/general.js\"></script>";
}
echo "<link rel=\"stylesheet\" href=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/themes/base/jquery-ui.css\" media=\"all\" />
<script type=\"text/javascript\" src=\"includes/css-frameworks/kickstart/js/ad-kickstart.js\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kickstart/css/ad-kickstart.css\" media=\"all\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kickstart/css/kickstart-forms.css\" media=\"all\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kickstart/css/kickstart-icons.css\" media=\"all\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kickstart/css/kickstart-buttons.css\" media=\"all\" />";


if ($red==3) echo "<script type=\"text/javascript\" src=\"ed/js/editor/editor.js\"></script> 
<link rel=\"stylesheet\" href=\"ed/js/editor/css/editor.css\" type=\"text/css\" media=\"screen, projection\" /> ";
if ($red==4) echo "<script src=\"ed2/redactor.js\"></script><link rel=\"stylesheet\" href=\"ed2/redactor.css\" /> ";

// УДАЛЕНО
/* 
if ($red==0) include("spaw2/spaw.inc.php"); //spaw2 !!!
if ($red==2) echo "<script type=\"text/javascript\" src=\"ckeditor/ckeditor.js\"></script>";
<script src='includes/ui.core.js'></script>
<script src='includes/ui.tabs.js'></script>

<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js'></script>
<script src='includes/jquery-ui.min.js'></script> 
<script src='includes/jquery-ui-i18n.min.js'></script> 

<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css/kickstart-buttons.css\" media=\"all\" />
<link rel='StyleSheet' href='includes/jquery-ui.css' type='text/css' />

<script type=\"text/javascript\" src=\"includes/js/prettify.js\"></script>

echo "<!-- calendar -->
<LINK rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=includes/calendar/calendar-win2k-cold-1.css title=\"win2k-cold-1\"> 
<SCRIPT type=\"text/javascript\" src=includes/calendar/calendar.js></SCRIPT> 
<SCRIPT type=\"text/javascript\" src=includes/calendar/calendar-en.js></SCRIPT> 
<SCRIPT type=\"text/javascript\" src=includes/calendar/calendar-setup.js></SCRIPT>
<!-- / calendar -->";
*/

global $ad_fon;
if ($ad_fon == 0) $fon = "default.jpg"; else $fon = $ad_fon.".png";
echo "\n</head>\n<body style=\"background: white url('/images/adfon/".$fon."');\">";
$url = getenv("REQUEST_URI");
global $siteurl, $op;
$url = str_replace("http://".$siteurl,"",$url);
$url2 = explode("_",$url);
$url2 = explode("?",$url2[0]);
$url2 = $url2[0];
echo "<table width=100%><tr><td align=center width=170>
	<a title=\"Перейти в Содержание\" href=\"sys.php\" class='nothing'><img src=images/logo_admin.png></a>
</td><td width=570>
	<div class='nothing noprint'>

<div style='margin: 0 5px 5px 0;'>
<button class='small' onclick='location.href=\"/\"' title='Перейти на сайт (откроется в новом окне)'><span class=\"icon small black\" data-icon=\"4\"></span>На сайт</button> ".$post.$kick."
</div>

<ul class=\"button-bar\">
<li class='first ".$color1."'><a title='Содержание сайта: разделы, папки, страницы и комментарии' href='sys.php'><span class=\"icon gray small\" data-icon=\",\"></span> Содержание</a></li>
<li class='".$color2."'><a title='Дизайн, стиль, блоки и прочие элементы оформления сайта' href='sys.php?op=mainpage&amp;type=element'><span class=\"icon gray small\" data-icon=\"Y\"></span> Оформление</a></li>
<li class='".$color3."'><a title='Настройки сайта' href='sys.php?op=Configure'><span class=\"icon gray small\" data-icon=\"=\"></span> Настройки</a></li>
<li class='".$color4."'><a title='Открыть статистику сайта' href='sys.php?op=mainpage&amp;type=stat'><span class=\"icon gray small\" data-icon=\"j\"></span> Статистика</a></li>
<li class='last'><a title='Выход из администрирования\n(мера безопасности)' href='sys.php?op=logout'><span class=\"icon red small\" data-icon=\"Q\"></span></a></li>
</ul>

	</div>
</td><td>
	<form method=post name=search action='/--search' style='display:inline;' class='nothing'><input type='search' placeholder='Поиск по сайту' name=slovo style='width:100%;'></form>
</td></tr></table>";
?>