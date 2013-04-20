<?php
if (stristr(htmlentities($_SERVER['PHP_SELF']), "ad-header.php")) {
	Header("Location: index.php");
	die();
}

// Определяем устройство - компьютер, планшет или телефон
require_once 'includes/Mobile_Detect.php';
$detect = new Mobile_Detect;
global $deviceType;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

global $postlink, $name, $sitename, $op, $type, $red, $prefix, $db, $id, $nastroi, $kickstart;
$color1=$color2=$color3=$color4="gray"; // Цвета 4х кнопок основных категорий админки (Содержание...)

if ($postlink != "") $post = "<button class='small' onclick='location.href=\"".$postlink."\"' title='Открыть почтовый сайт...'><span class=\"icon small black\" data-icon=\"@\"></span> Почта</button> "; else $post="";

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
	case 9:
	$kick_link = "http://yuilibrary.com/yui/docs/cssgrids/";
	$kick_name = "YUI"; break;
	case 10:
	$kick_link = "http://960.gs";
	$kick_name = "960gs"; break;
	case 11:
	$kick_link = "http://960.gs";
	$kick_name = "960gs(24)"; break;

	default:
	$kick_link = "";
	$kick_name = ""; break;
}
if ($kickstart != 0) $kick = " <button class='small' onclick='location.href=\"".$kick_link."\"' title='Открыть сайт CSS-фреймворка «".$kick_name."»'><span class=\"icon small black\" data-icon=\"S\"></span> ".$kick_name."</button>";
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
<!--[if IE]><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\"><![endif]-->
<!--[if lt IE 9]><script src=\"http://html5shim.googlecode.com/svn/trunk/html5.js\"></script><![endif]-->
<link rel=\"stylesheet\" href=\"ad-style.css\" type=\"text/css\">
<link rel=\"shortcut icon\" href=\"favicon_cms.ico\" type=\"image/x-icon\">
<script src='includes/jad.js'></script>
<script src='includes/j.js'></script>
<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>
<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js'></script>
<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/i18n/jquery-ui-i18n.min.js'></script> 
<script src='includes/jquery.innerfade.js'></script>";
/*
if (!$op) {
	echo "\n<script type=\"text/javascript\" src=\"includes/jeditable.js\"></script>
	<script type=\"text/javascript\" src=\"includes/form.js\"></script>
	<script type=\"text/javascript\" src=\"includes/select.js\"></script>
	<script type=\"text/javascript\" src=\"includes/general.js\"></script>";
}
*/
echo "<link rel=\"stylesheet\" href=\"http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/jquery-ui.css\" media=\"all\" />
<script type=\"text/javascript\" src=\"includes/css-frameworks/kickstart/js/ad-kickstart.js\"></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kickstart/css/ad-kickstart.css\" media=\"all\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kickstart/css/kickstart-forms.css\" media=\"all\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kickstart/css/kickstart-icons.css\" media=\"all\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"includes/css-frameworks/kickstart/css/kickstart-buttons.css\" media=\"all\" />";

if ($red==3) echo "<script type=\"text/javascript\" src=\"ed/js/editor/editor.js\"></script> 
<link rel=\"stylesheet\" href=\"ed/js/editor/css/editor.css\" type=\"text/css\" media=\"screen, projection\" /> ";
if ($red==4) echo "<script src=\"ed2/redactor.js\"></script>
<link rel=\"stylesheet\" href=\"ed2/redactor.css\" />
<script src=\"ed2/ru.js\"></script>
<script src=\"ed2/clips.js\"></script>
<link rel=\"stylesheet\" href=\"ed2/clips.css\" />
<script src=\"ed2/fullscreen.js\"></script>";

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
</td><td>
	<div class='nothing noprint'>

<div style='margin: 0 5px 5px 0;'>";

//if($detect->isiOS())
//if($detect->isAndroidOS())
global $buttons;
$buttons = array(' Содержание',' Оформление',' Настройки',' Статистика',' ПОМОЩЬ','');
if ($deviceType != 'computer') $buttons = array('','','','','','');

if ($op == "adminMain") red_help(8);
if ($op == "mainpage" && $type==2) red_help(9);

echo "<button class='small' target=_blank onclick='window.open(\"/\")' title='Перейти на сайт (откроется в новом окне)'><span class=\"icon small black\" data-icon=\"4\"></span> На сайт</button> ".$post.$kick." <form method=post name=search action='/--search' style='display:inline;' class='nothing'><input type='search' placeholder='Поиск по сайту' name=slovo class=w45></form>
</div>

<ul class=\"button-bar\">
<li class='first ".$color1."'><a title='Содержание сайта: разделы, папки, страницы и комментарии' href='sys.php'><span class=\"icon gray small\" data-icon=\",\"></span>".$buttons[0]."</a></li>
<li class='".$color2."'><a title='Дизайн, стиль, блоки и прочие элементы оформления сайта' href='sys.php?op=mainpage&amp;type=element'><span class=\"icon gray small\" data-icon=\"Y\"></span>".$buttons[1]."</a></li>
<li class='".$color3."'><a title='Настройки сайта' href='sys.php?op=Configure'><span class=\"icon gray small\" data-icon=\"=\"></span>".$buttons[2]."</a></li>
<li class='".$color4."'><a title='Открыть статистику сайта' href='sys.php?op=mainpage&amp;type=stat'><span class=\"icon gray small\" data-icon=\"j\"></span>".$buttons[3]."</a></li>
<li class='last'><a title='Выход из администрирования\n(мера безопасности)' href='sys.php?op=logout'><span class=\"icon red small\" data-icon=\"Q\"></span></a></li>
</ul>

	</div>
</td></tr></table><a name=1></a>";

function red_help($id){
	global $buttons;
	echo "<button class='small red' onclick=\"openbox('".$id."','Помощь'); $('#show_razdel').click();\" style='color:white !important;' title='Открыть справочную информацию'><span class=\"icon small white\" data-icon=\"n\"></span>".$buttons[4]."</button> ";
}
?>