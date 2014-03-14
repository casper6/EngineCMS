<?php
if (stristr(htmlentities($_SERVER['PHP_SELF']), "ad-header.php")) {
	Header("Location: index.php"); die();
}
global $clean_urls, $deviceType, $postlink, $name, $sitename, $op, $type, $red, $prefix, $db, $id, $nastroi, $lang_admin, $siteurl, $op, $ad_fon, $show_admin_top;
if ($postlink != "") $post = "<button class='small' onclick='location.href=\"".$postlink."\"' title='".aa("Открыть почтовый сайт...")."'><span class='icon small' data-icon='@'></span></button> "; else $post="";

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
	if (isset($titles_text[$op."_".$name])) $title = $titles_text[$op."_".$name];
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
<script>var clean_urls=".$clean_urls.";</script>
<script src='/includes/jquery.compress.js'></script>
<script src='includes/css-frameworks/kickstart/js/ad-kickstart.compress.js'></script>
<!--[if lt IE 9]><script src='includes/html5.js'></script><![endif]-->
<link rel='stylesheet' href='includes/jquery.compress.css'>
<link rel='stylesheet' href='engine.css'>
<link rel='stylesheet' href='includes/css-frameworks/kickstart/css/ad-kickstart.compress.css'>";
/* 
jquery-2.0.0.js
jquery-migrate-1.1.1.js
jquery-ui-datepicker-ru.js
jad.js
j.js
jquery-ui.min.js
jquery-ui-i18n-min.js - не используется, оставлен русский язык
jquery.innerfade.js - слайдер, не используется
jquery.tag-it.min.js
spin.js

jquery.compress.css
includes/jquery.tagit.css
includes/jquery-ui.css

ad-kickstart.compress.css = 
	ad-kickstart.css
	kickstart-icons.css
	kickstart-forms.css
	kickstart-menus.css
	kickstart-buttons.css
*/
if ($lang_admin != 'ru') echo "<script src='language/adm_".$lang_admin.".js'></script>";
if ($red==3) echo "<script src='ed/js/editor/editor.js'></script><link rel='stylesheet' href='ed/js/editor/css/editor.css' media='screen, projection' /> ";

echo "\n</head>\n<body style=\"background-color: ".$ad_fon.";\">";
$url = getenv("REQUEST_URI");
$url = str_replace("http://".$siteurl,"",$url);
$url2 = explode("_",$url);
$url2 = explode("?",$url2[0]);
$url2 = $url2[0];

if ($show_admin_top == 0) {
	echo "<div class='mw800 w100 m0 h15 center noprint' id='admin_top_line' onmousemove=' $(\"#admin_top_line\").hide(); $(\"#admin_top\").show();'><img height=15 align=left src='images/logotip.png'>".aa("Главное меню")."</div><table class='mw800 hide w100 m0 fixed z1000 l0 t0 shadow' id='admin_top' style='background-color: ".$ad_fon.";'>";
} else echo "<table class='mw800 w100 m0'>";
echo "<tr class='noprint'>";
echo "<td class='center mp0'><a title='".aa("Перейти в Содержание")."' href='sys.php' class='nothing'><img src='images/logotip.png' align=left></a> 


</td>";
echo "<td class='mp0'><div class='nothing p5'>";
//if($detect->isiOS())
//if($detect->isAndroidOS())
global $buttons;
$buttons = explode(",", aa(" Содержание, Оформление, Настройки"));
if ($deviceType != 'computer') $buttons = array('','','');

$exit_admin_button = " <a id='logout_button' class='button small right3' title='".aa("Выход из администрирования\n(мера безопасности)")."' href='sys.php?op=logout'><span class='icon small red' data-icon='Q'></span></a>";



echo "<ul class='button-bar' style='margin-right: 5px; margin-bottom: 5px'>
<li class='first ".$color1."'><a title='".aa("Перейти в Содержание")."' href='sys.php'><span class='icon gray small' data-icon=','></span>".$buttons[0]."</a></li>";
global $editor_style;
if ($editor_style == false) {
	echo "<li class='".$color2."'><a title='".aa("Дизайн, стиль, блоки и прочие элементы оформления сайта")."' href='sys.php?op=mainpage&amp;type=element'><span class='icon gray small' data-icon='Y'></span>".$buttons[1]."</a></li>
	<li class='".$color3."'><a title='".aa("Настройки сайта")."' href='sys.php?op=options'><span class='icon gray small' data-icon='='></span>".$buttons[2]."</a></li>";
}
if ($show_admin_top != "2") echo "<li class='last ".$color4."'><a title='".aa("Открыть статистику сайта")."' href='sys.php?op=stat_main'><span class='icon gray small' data-icon='j'></span></a></li>";
else echo $exit_admin_button."";
echo "</ul>";

if ($show_admin_top != "2") {
	echo "<script>
	var search_form = \"<a class=punkt onclick=$('#add').hide('slow');><div class=radius style='font-size:12pt; width:20px; height: 20px; color: white; text-align:center; float:right; margin:5px; background: #bbbbbb;'>&nbsp;x&nbsp;</div></a><h1>Поиск по сайту</h1><form method='post' name='search' action='search' style='display:inline;' class='nothing'><input class='w100' type='search' name='slovo'></form>\";
	</script>
	<nobr><button id='site_button' class='small' target='_blank' onclick='window.open(\"/\")' title='".aa("Перейти на сайт (откроется в новом окне)")."'><span class='icon small' data-icon='4'></span></button> 
	<button class='small' title='".aa("Поиск по сайту")."' onclick='$(\"#add\").toggle().html(search_form); $(\"#add form input\").focus();'><span class='icon small red' data-icon='s'></span></button>
	".$post;
	if ($url == "/red" || $url == "/sys.php" || $url == "/sys.php?op=mes") echo "<a class='button small' onclick=\"openbox('8','".aa("Помощь")."'); $('#show_razdel').click();\" title='".aa("Открыть справочную информацию")."'><span class='icon small' data-icon='n'></span></a>";
	echo "".$exit_admin_button."</nobr>";
}

echo "</div></td></tr></table>
<a name='top'></a>";
if ($show_admin_top == 0) echo "<div onmousemove='$(\"#admin_top\").hide(\"fast\"); $(\"#admin_top_line\").show();'>";
?>