<?php
if (stristr(htmlentities($_SERVER['PHP_SELF']), "ad-header.php")) {
	Header("Location: index.php"); die();
}
global $deviceType, $postlink, $name, $sitename, $op, $type, $red, $prefix, $db, $id, $nastroi, $lang_admin;
if ($postlink != "") $post = "<button class='small' onclick='location.href=\"".$postlink."\"' title='".aa("Открыть почтовый сайт...")."'><span class='icon small black' data-icon='@'></span> ".aa("Почта")."</button> "; else $post="";

// Определяем заголовок страницы (title) и цвета кнопок главного меню (какой раздел администрирования выбран)
$color1 = $color2 = $color3 = $color4 = "gray"; // Цвета 4х кнопок основных категорий админки (Содержание...)
$color1 = "black";
$title = aa("Управление сайтом");
$titles_text = array("base_pages_add_page" => aa("Добавление страницы в раздел"),"base_pages_edit_page"  => aa("Редактирование страницы"),"edit_base_pages_category"  => aa("Редактирование папки"),"subscribe" => aa("Рассылка (список адресатов и их email'ов)"),"options" => aa("Настройка сайта и администратора"),"users" => aa("Настройка пользователей"),"mainpage" => aa("Редактирование оформления"),"mainpage_0" => aa("Редактирование дизайна (HTML)"),"mainpage_1" => aa("Редактирование стиля (CSS)"),"mainpage_2" => aa("Редактирование раздела"),"mainpage_3" => aa("Редактирование блока"),"mainpage_4" => aa("Редактирование поля"),"mainpage_5" => aa("Редактирование базы данных"),"mainpage_6" => aa("Редактирование шаблона"),"mainpage_design" => aa("Добавление дизайна (HTML)"),"mainpage_css" => aa("Добавление стиля (CSS)"),"mainpage_block" => aa("Добавление блока"),"mainpage_spisok" => aa("Добавление поля"),"mainpage_base" => aa("Добавление базы данных"),"mainpage_shablon" => aa("Добавление шаблона"),"mainpage_nastroi" => aa("Настройка элемента сайта"),"mainpage_nastroi2" => aa("Настройка раздела"),"mainpage_nastroi3" => aa("Настройка блока"),"mainpage_element" => aa("Оформление сайта"),"mainpage_stat" => aa("Общая статистика"),"mainpage_stat_page" => aa("Статистика посещений страниц"),"mainpage_stat_search" => aa("Статистика поисковых запросов") );
if (isset($titles_text[$op])) $title = $titles_text[$op];
if ($op == "subscribe" || $op == "options" || $op == "users") { $color1 = "gray"; $color3 = "black"; }
if ($op == "mainpage") {
	$color1 = "gray"; $color2 = "black";
	if (isset($type)) $title = $titles_text[$op."_".$type];
	if ($type == 2) { $color2 = "gray"; $color1 = "black"; }
	if (isset($name)) $title = $titles_text[$op."_".$name];
	if ($nastroi == 1) { 
		$title = $titles_text[$op."_nastroi"]; 
		$color2 = "gray"; $color3 = "black";
		if ($type == 3 || $type == 2) $titles_text[$op."_nastroi".$type];
	}
	if (strripos($type, "stat") !== false) { $color2 = "gray"; $color4 = "black"; }
}

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
<link rel='stylesheet' href='ad/ad-style.css' type='text/css'>
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
<script src='ed2/".$lang_admin.".js'></script>
<script src='ed2/clips.js'></script>
<link rel='stylesheet' href='ed2/clips.css' />
<script src='ed2/fullscreen.js'></script>
<script src='ed2/fontcolor.js'></script>
<script src='ed2/fontsize.js'></script>
<script src='ed2/fontfamily.js'></script>";

global $ad_fon;
if ($ad_fon == 0) $fon = "default.jpg"; else $fon = $ad_fon.".png";
echo "\n</head>\n<body style=\"background: white url('/images/adfon/".$fon."');\">";
$url = getenv("REQUEST_URI");
global $siteurl, $op;
$url = str_replace("http://".$siteurl,"",$url);
$url2 = explode("_",$url);
$url2 = explode("?",$url2[0]);
$url2 = $url2[0];
echo "<table class='mw800 w100 m0'><tr><td align=center width=170 class=mp0><a title='".aa("Перейти в Содержание")."' href='sys.php' class='nothing'><img src='images/logo_admin.png'></a></td><td class=mp0><div class='nothing noprint'><div style='margin: 0 5px 5px 0;'>";
//if($detect->isiOS())
//if($detect->isAndroidOS())
global $buttons;
$buttons = explode(",", aa(" Содержание, Оформление, Настройки, Статистика, ПОМОЩЬ, "));
if ($deviceType != 'computer') $buttons = array('','','','','','');
echo "<nobr>";
echo "<button class='small' onclick=\"openbox('8','".aa("Помощь")."'); $('#show_razdel').click();\" title='".aa("Открыть справочную информацию")."'><span class='icon small' data-icon='n'></span>".$buttons[4]."</button> 
	<button class='small' target=_blank onclick='window.open(\"/\")' title='".aa("Перейти на сайт (откроется в новом окне)")."'><span class='icon small' data-icon='4'></span> ".aa("На сайт")."</button> ".$post." <form method=post name=search action='/--search' style='display:inline;' class='nothing'><input type='search' placeholder='".aa("Поиск по сайту")."' name=slovo class=w45></form></nobr></div>
<ul class='button-bar'>
<li class='first ".$color1."'><a title='".aa("Содержание сайта: разделы, папки, страницы и комментарии")."' href='sys.php'><span class='icon gray small' data-icon=','></span>".$buttons[0]."</a></li>
<li class='".$color2."'><a title='".aa("Дизайн, стиль, блоки и прочие элементы оформления сайта")."' href='sys.php?op=mainpage&amp;type=element'><span class='icon gray small' data-icon='Y'></span>".$buttons[1]."</a></li>
<li class='".$color3."'><a title='".aa("Настройки сайта")."' href='sys.php?op=options'><span class='icon gray small' data-icon='='></span>".$buttons[2]."</a></li>
<li class='last ".$color4."'><a title='".aa("Открыть статистику сайта")."' href='sys.php?op=mainpage&amp;type=stat'><span class='icon gray small' data-icon='j'></span>".$buttons[3]."</a></li>
<li class='first last'><a title='".aa("Выход из администрирования\n(мера безопасности)")."' href='sys.php?op=logout' class='button small'><span class='icon red small' data-icon='Q'></span></a></li>
</ul>
</div></td></tr>
<tr><td colspan=2 class='black_grad2 h5 mp0'><a name='1'></a>
</td></tr></table>";
?>