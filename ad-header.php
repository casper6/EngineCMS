<?php
if (stristr(htmlentities($_SERVER['PHP_SELF']), "ad-header.php")) {
	Header("Location: index.php"); die();
}
global $deviceType, $postlink, $name, $sitename, $op, $type, $red, $prefix, $db, $id, $nastroi, $lang_admin;
$color1=$color2=$color3=$color4="gray"; // Цвета 4х кнопок основных категорий админки (Содержание...)
if ($postlink != "") $post = "<button class='small' onclick='location.href=\"".$postlink."\"' title='".aa("Открыть почтовый сайт...")."'><span class='icon small black' data-icon='@'></span> ".aa("Почта")."</button> "; else $post="";
$title = aa("Управление сайтом");
$color1="blue";
if ($op == "base_pages_add_page") $title = aa("Добавление страницы в раздел");
if ($op == "edit_base_pages_category") $title = aa("Редактирование папки");
if ($op == "base_pages_edit_page") $title = aa("Редактирование страницы");
if ($op == "subscribe") {$title = aa("Рассылка (список адресатов и их email'ов)"); $color1="gray"; $color3="blue";}
if ($op == "Configure") {$title = aa("Настройка сайта и администратора"); $color1="gray"; $color3="blue";}
if ($op == "mainpage" and $id > 0) {$title = aa("Редактирование оформления"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 0) {$title = aa("Редактирование дизайна (HTML)"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 1) {$title = aa("Редактирование стиля (CSS)"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 2) $title = aa("Редактирование раздела");
if ($op == "mainpage" and $type == 3) {$title = aa("Редактирование блока"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 4) {$title = aa("Редактирование поля"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 5) {$title = aa("Редактирование базы данных"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == 6) {$title = aa("Редактирование шаблона"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "design") {$title = aa("Добавление дизайна (HTML)"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "css") {$title = aa("Добавление стиля (CSS)"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "block") {$title = aa("Добавление блока"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "spisok") {$title = aa("Добавление поля"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "base") {$title = aa("Добавление базы данных"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $name == "shablon") {$title = aa("Добавление шаблона"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $nastroi == 1) {$title = aa("Настройка элемента сайта"); $color1="gray"; $color3="blue";}
if ($op == "mainpage" and $nastroi == 1 and $type == 3) {$title = aa("Настройка блока"); $color1="gray"; $color3="blue";}
if ($op == "users") {$title = aa("Настройка пользователей"); $color1="gray"; $color3="blue";}
if ($op == "mainpage" and $nastroi == 1 and $type == 2) {$title = aa("Настройка раздела"); $color1="gray"; $color3="blue";}
if ($op == "mainpage" and $type == "element") {$title = aa("Оформление сайта"); $color1="gray"; $color2="blue";}
if ($op == "mainpage" and $type == "stat") {$title = aa("Общая статистика"); $color1="gray"; $color2="gray"; $color4="blue";}
if ($op == "mainpage" and $type == "stat_page") {$title = aa("Статистика посещений страниц"); $color1="gray"; $color2="gray"; $color4="blue";}
if ($op == "mainpage" and $type == "stat_search") {$title = aa("Статистика поисковых запросов"); $color1="gray"; $color2="gray"; $color4="blue";}
// http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js
header ("Content-Type: text/html; charset=utf-8");
echo "<!doctype html>
<html lang='".$lang_admin."' dir='ltr'>
<head><title>".$title."</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
<meta http-equiv='pragma' content='no-cache' />
<meta http-equiv='no-cache' />
<meta http-equiv='cache-control' content='no-cache' />
<meta name='viewport' content='width=device-width'>
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<!--[if lt IE 9]><script src='includes/html5.js'></script><![endif]-->
<link rel='stylesheet' href='ad-style.css' type='text/css'>
<link rel='shortcut icon' href='images/favicon_cms.png' type='image/x-icon'>
<script src='includes/jad.js'></script>
<script src='includes/j.js'></script>
<script src='includes/jquery183.min.js'></script>
<script src='includes/jquery-ui.min.js'></script>
<script src='includes/jquery-ui-i18n.min.js'></script> 
<script src='includes/jquery.innerfade.js'></script>
<link rel='stylesheet' href='includes/jquery-ui.css' media='all' />
<script src='includes/css-frameworks/kickstart/js/ad-kickstart.js'></script>
<link rel='stylesheet' type='text/css' href='includes/css-frameworks/kickstart/css/ad-kickstart.css' media='all' />
<link rel='stylesheet' type='text/css' href='includes/css-frameworks/kickstart/css/kickstart-forms.css' media='all' />
<link rel='stylesheet' type='text/css' href='includes/css-frameworks/kickstart/css/kickstart-icons.css' media='all' />
<link rel='stylesheet' type='text/css' href='includes/css-frameworks/kickstart/css/kickstart-buttons.css' media='all' />";

if ($lang_admin != 'ru') echo "<script src='language/adm_".$lang_admin.".js'></script>";

if ($red==3) echo "<script src='ed/js/editor/editor.js'></script> 
<link rel='stylesheet' href='ed/js/editor/css/editor.css' type='text/css' media='screen, projection' /> ";
if ($red==4) echo "<script src='ed2/redactor.js'></script>
<link rel='stylesheet' href='ed2/redactor.css' />
<script src='ed2/ru.js'></script>
<script src='ed2/clips.js'></script>
<link rel='stylesheet' href='ed2/clips.css' />
<script src='ed2/fullscreen.js'></script>";

global $ad_fon;
if ($ad_fon == 0) $fon = "default.jpg"; else $fon = $ad_fon.".png";
echo "\n</head>\n<body style=\"background: white url('/images/adfon/".$fon."');\">";
$url = getenv("REQUEST_URI");
global $siteurl, $op;
$url = str_replace("http://".$siteurl,"",$url);
$url2 = explode("_",$url);
$url2 = explode("?",$url2[0]);
$url2 = $url2[0];
echo "<table class='mw800 w100'><tr><td align=center width=170><a title='".aa("Перейти в Содержание")."' href='sys.php' class='nothing'><img src='images/logo_admin.png'></a></td><td><div class='nothing noprint'><div style='margin: 0 5px 5px 0;'>";
//if($detect->isiOS())
//if($detect->isAndroidOS())
global $buttons;
$buttons = explode(",", aa(" Содержание, Оформление, Настройки, Статистика, ПОМОЩЬ, "));
if ($deviceType != 'computer') $buttons = array('','','','','','');
echo "<nobr>";
if ($op == "adminMain") echo "<button class='small red' onclick=\"openbox('8','".aa("Помощь")."'); $('#show_razdel').click();\" style='color:white !important;' title='".aa("Открыть справочную информацию")."'><span class='icon small white' data-icon='n'></span>".$buttons[4]."</button> ";
echo "<button class='small' target=_blank onclick='window.open(\"/\")' title='".aa("Перейти на сайт (откроется в новом окне)")."'><span class='icon small black' data-icon='4'></span> ".aa("На сайт")."</button> ".$post." <form method=post name=search action='/--search' style='display:inline;' class='nothing'><input type='search' placeholder='".aa("Поиск по сайту")."' name=slovo class=w45></form></nobr></div>
<nobr><ul class='button-bar'>
<li class='first ".$color1."'><a title='".aa("Содержание сайта: разделы, папки, страницы и комментарии")."' href='sys.php'><span class='icon gray small' data-icon=','></span>".$buttons[0]."</a></li>
<li class='".$color2."'><a title='".aa("Дизайн, стиль, блоки и прочие элементы оформления сайта")."' href='sys.php?op=mainpage&amp;type=element'><span class='icon gray small' data-icon='Y'></span>".$buttons[1]."</a></li>
<li class='".$color3."'><a title='".aa("Настройки сайта")."' href='sys.php?op=Configure'><span class='icon gray small' data-icon='='></span>".$buttons[2]."</a></li>
<li class='".$color4."'><a title='".aa("Открыть статистику сайта")."' href='sys.php?op=mainpage&amp;type=stat'><span class='icon gray small' data-icon='j'></span>".$buttons[3]."</a></li>
<li class='last'><a title='".aa("Выход из администрирования\n(мера безопасности)")."' href='sys.php?op=logout'><span class='icon red small' data-icon='Q'></span></a></li>
</ul></nobr></div></td></tr></table><a name=1></a>";
?>