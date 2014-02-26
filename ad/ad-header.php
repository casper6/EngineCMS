<?php
if (stristr(htmlentities($_SERVER['PHP_SELF']), "ad-header.php")) {
	Header("Location: index.php"); die();
}
global $clean_urls, $deviceType, $postlink, $name, $sitename, $op, $type, $red, $prefix, $db, $id, $nastroi, $lang_admin, $siteurl, $op, $ad_fon, $show_admin_top;
if ($postlink != "") $post = "<button class='small' onclick='location.href=\"".$postlink."\"' title='".aa("Открыть почтовый сайт...")."'><span class='icon small' data-icon='@'></span> ".aa("Почта")."</button> "; else $post="";

// Определяем заголовок страницы (title) и цвета кнопок главного меню (какой раздел администрирования выбран)
$color1 = $color2 = $color3 = $color4 = "gray"; // Цвета 4х кнопок основных категорий админки (Содержание...)
$color1 = "black";
$title = aa("Управление сайтом");
$titles_text = array("base_pages_add_page" => aa("Добавление страницы в раздел"),"base_pages_edit_page"  => aa("Редактирование страницы"),"edit_base_pages_category"  => aa("Редактирование папки"),"subscribe" => aa("Рассылка (список адресатов и их email'ов)"),"options" => aa("Настройка сайта"),"users" => aa("Настройка пользователей"),"mainpage" => aa("Редактирование оформления"),"mainpage_0" => aa("Редактирование дизайна (HTML)"),"mainpage_1" => aa("Редактирование стиля (CSS)"),"mainpage_2" => aa("Редактирование раздела"),"mainpage_3" => aa("Редактирование блока"),"mainpage_4" => aa("Редактирование поля"),"mainpage_5" => aa("Редактирование базы данных"),"mainpage_6" => aa("Редактирование шаблона"),"mainpage_design" => aa("Добавление дизайна (HTML)"),"mainpage_css" => aa("Добавление стиля (CSS)"),"mainpage_block" => aa("Добавление блока"),"mainpage_spisok" => aa("Добавление поля"),"mainpage_base" => aa("Добавление базы данных"),"mainpage_shablon" => aa("Добавление шаблона"),"mainpage_nastroi" => aa("Настройка элемента сайта"),"mainpage_nastroi2" => aa("Настройка раздела"),"mainpage_nastroi3" => aa("Настройка блока"),"mainpage_element" => aa("Оформление сайта"),"stat_main" => aa("Общая статистика"),"stat_page" => aa("Статистика посещений страниц"),"stat_search" => aa("Статистика поисковых запросов") );
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
}
if (strripos($op, "stat") !== false) { $color1 = "gray"; $color4 = "black"; }

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
<link rel='shortcut icon' href='images/favicon_cms.png' type='image/x-icon'>
<script src='includes/jquery183.min.js'></script>
<script src='includes/css-frameworks/kickstart/js/ad-kickstart.js'></script>
<script>var clean_urls=".$clean_urls.";</script>
<script src='includes/jad.js'></script>
<script src='includes/j.js'></script>
<script src='includes/jquery-ui.min.js'></script>
<script src='includes/jquery-ui-i18n.min.js'></script> 
<script src='includes/jquery.innerfade.js'></script>

<script src='includes/jquery.tag-it.min.js'></script>
<link rel='stylesheet' href='includes/jquery.tagit.css'>

<link href='http://fonts.googleapis.com/css?family=Poiret+One&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

<link rel='stylesheet' href='includes/jquery-ui.css'>
<!--[if lt IE 9]><script src='includes/html5.js'></script><![endif]-->
<link rel='stylesheet' href='engine.css'>
<link rel='stylesheet' href='includes/css-frameworks/kickstart/css/ad-kickstart.css'>
<link rel='stylesheet' href='includes/css-frameworks/kickstart/css/kickstart-forms.css'>
<link rel='stylesheet' href='includes/css-frameworks/kickstart/css/kickstart-icons.css'>
<link rel='stylesheet' href='includes/css-frameworks/kickstart/css/kickstart-buttons.css'>
";

if ($lang_admin != 'ru') echo "<script src='language/adm_".$lang_admin.".js'></script>";

if ($red==3) echo "<script src='ed/js/editor/editor.js'></script> 
<link rel='stylesheet' href='ed/js/editor/css/editor.css' media='screen, projection' /> ";

echo "<script src='includes/spin.js'></script>
<script src='ed2/redactor.js'></script>
<link rel='stylesheet' href='ed2/redactor.css' />
<script src='ed2/".$lang_admin.".js'></script>
<script src='ed2/clips.js'></script>
<link rel='stylesheet' href='ed2/clips.css' />
<script src='ed2/fullscreen.js'></script>
<script src='ed2/fontcolor.js'></script>
<script src='ed2/fontsize.js'></script>
<script src='ed2/fontfamily.js'></script>";

echo "\n</head>\n<body style=\"background-color: ".$ad_fon.";\"><div class=' radius'>";
$url = getenv("REQUEST_URI");
$url = str_replace("http://".$siteurl,"",$url);
$url2 = explode("_",$url);
$url2 = explode("?",$url2[0]);
$url2 = $url2[0];

$lang_logo = "";
if ($lang_admin != "ru" && $lang_admin != "ua") $lang_logo = "_en";

if ($show_admin_top == 0) echo "<div class='mw800 w100 m0 h15 center' id='admin_top_line' onmousemove=' $(\"#admin_top_line\").hide(); $(\"#admin_top\").show();'><img height=15 align=left src='images/logo_admin".$lang_logo.".png'>".aa("Главное меню")."</div><table class='mw800 hide w100 m0 fixed z1000 l0 t0 shadow' id='admin_top' style='background-color: ".$ad_fon.";'>";
else echo "<table class='mw800 w100 m0'>";
echo "<tr><td class=center width=170><a title='".aa("Перейти в Содержание")."' href='sys.php' class='nothing'><img src='images/logo_admin".$lang_logo.".png'></a></td><td class=mp0><div class='nothing noprint'><div style='margin: 5px 5px 5px 0;'>";
//if($detect->isiOS())
//if($detect->isAndroidOS())
global $buttons;
$buttons = explode(",", aa(" Содержание, Оформление, Настройки, Статистика, ПОМОЩЬ, "));
if ($deviceType != 'computer') $buttons = array('','','','','','');
if ($url == "/red" || $url == "/sys.php" || $url == "/sys.php?op=mes") echo "<a class='button small in_r' onclick=\"openbox('8','".aa("Помощь")."'); $('#show_razdel').click();\" title='".aa("Открыть справочную информацию")."'><span class='icon small' data-icon='n'></span>".$buttons[4]."</a>";
echo "<nobr>
<button class='small' target=_blank onclick='window.open(\"/\")' title='".aa("Перейти на сайт (откроется в новом окне)")."'><span class='icon small' data-icon='4'></span> ".aa("На сайт")."</button>
<form method=post name=search action='search' style='display:inline;' class='nothing'><input type='search' placeholder='".aa("Поиск по сайту")."' name='slovo' class='w25'></form>
".$post."
</nobr></div>
<a class='in_r button small' style='margin: 0 5px 0 0;' title='".aa("Выход из администрирования\n(мера безопасности)")."' href='sys.php?op=logout'><span class='icon small' data-icon='Q'></span></a>

<ul class='button-bar'>
<li class='first ".$color1."'><a title='".aa("Содержание сайта: разделы, папки, страницы и комментарии")."' href='sys.php'><span class='icon gray small' data-icon=','></span>".$buttons[0]."</a></li>";
global $editor_style;
if ($editor_style == false) {
	echo "<li class='".$color2."'><a title='".aa("Дизайн, стиль, блоки и прочие элементы оформления сайта")."' href='sys.php?op=mainpage&amp;type=element'><span class='icon gray small' data-icon='Y'></span>".$buttons[1]."</a></li>
	<li class='".$color3."'><a title='".aa("Настройки сайта")."' href='sys.php?op=options'><span class='icon gray small' data-icon='='></span>".$buttons[2]."</a></li>";
}
echo "<li class='last ".$color4."'><a title='".aa("Открыть статистику сайта")."' href='sys.php?op=stat_main'><span class='icon gray small' data-icon='j'></span>".$buttons[3]."</a></li>
</ul>
</div></td></tr></table>
<a name='top'></a><div onmousemove='$(\"#admin_top\").hide(\"fast\"); $(\"#admin_top_line\").show();'>";
?>