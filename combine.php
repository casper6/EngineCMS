<?php
  @require_once("config.php"); // Настройки сайта
  @require_once("includes/db.php"); // База данных (функции для работы)
  @require_once("includes/sql_layer.php");
  global $prefix, $db; 

if (isset($_GET['add'])) $add = $_GET['add']; else $add = "";
$name = explode("-", $_GET['files']);
$type = $_GET['type'];

// Добавочный шаблон стиля
if ($add != "") {
  $add = explode("_",$add); // ввести дополнительную проверку
  require_once("shablon_style.php");
  $sha = shablon_style_show ($add[0], $add[1]);
} else $sha = "";

// это будет добавлено к любому CSS
$contents = "
.editor_file_link {
	padding-left: 20px;
}
.editor_file_ico_avi 	{ background: url(ed/js/editor/i/fileicons/avi.png) no-repeat; }
.editor_file_ico_csv 	{ background: url(ed/js/editor/i/fileicons/csv.png) no-repeat; }
.editor_file_ico_doc 	{ background: url(ed/js/editor/i/fileicons/doc.png) no-repeat; }
.editor_file_ico_gif 	{ background: url(ed/js/editor/i/fileicons/gif.png) no-repeat; }
.editor_file_ico_html 	{ background: url(ed/js/editor/i/fileicons/html.png) no-repeat; }
.editor_file_ico_jpg 	{ background: url(ed/js/editor/i/fileicons/jpg.png) no-repeat; }
.editor_file_ico_mov	{ background: url(ed/js/editor/i/fileicons/mov.png) no-repeat; }
.editor_file_ico_other 	{ background: url(ed/js/editor/i/fileicons/other.png) no-repeat; }
.editor_file_ico_pdf 	{ background: url(ed/js/editor/i/fileicons/pdf.png) no-repeat; }
.editor_file_ico_png 	{ background: url(ed/js/editor/i/fileicons/png.png) no-repeat; }
.editor_file_ico_ppt 	{ background: url(ed/js/editor/i/fileicons/ppt.png) no-repeat; }
.editor_file_ico_rar 	{ background: url(ed/js/editor/i/fileicons/rar.png) no-repeat; }
.editor_file_ico_rtf 	{ background: url(ed/js/editor/i/fileicons/rtf.png) no-repeat; }
.editor_file_ico_txt 	{ background: url(ed/js/editor/i/fileicons/txt.png) no-repeat; }
.editor_file_ico_xls 	{ background: url(ed/js/editor/i/fileicons/xls.png) no-repeat; }
.editor_file_ico_zip 	{ background: url(ed/js/editor/i/fileicons/zip.png) no-repeat; }

.redactor_toolbar li {padding-left:5px !important;}

.comm_form #avtory, .comm_form #maily, .comm_form #adres, .comm_form #tel, .comm_form #area {width: 99%;}

/* Магазин */
.shop_card_minifoto {border-radius: 5px; -moz-border-radius: 5; -webkit-border-radius: 5; border:1px solid #bbbbbb; float:left; margin-right: 5px; width: 30px; height: 30px;}
.shop_card_price {float:right; margin-left: 5px; text-align:right; max-height: 40px;}
.shop_card { padding-top:10px; padding-bottom:10px; min-height: 55px !important;}
.shop_card_oformlenie {margin-top:10px;}
.shop_card_oformlenie a { width:100%; background: #d0e087; color: #4b6028; border-radius: 5px; -moz-border-radius: 5; -webkit-border-radius: 5; border-bottom:2px solid #bdc9a2; padding:7px; cursor:pointer; text-decoration:none;}
.shop_card_oformlenie a:hover {background: #bdc9a2; color: #333; border-bottom:2px solid #666;}
.shop_card {border-bottom: 1px dotted #333;}
.shop_card:hover {background:#eee;}
.shop_card_price b {display:block; margin-bottom:5px;}
.shop_card_del {cursor:pointer; background:darkred; border-radius: 5px; -moz-border-radius: 5; -webkit-border-radius: 5; padding:1px 5px 1px; cursor:pointer; text-decoration:none; color: white;}
.shop_card_del:hover {background:red; color: white;}
.shop_card_itogo, .shop_card_itogo_price {margin-top:10px; margin-bottom:20px; height: 25px;}
.shop_form_input {width:98%;}

input {
  transition: all 0.30s ease-in-out;
  -webkit-transition: all 0.30s ease-in-out;
  -moz-transition: all 0.30s ease-in-out;
  outline:none;
}
input:focus {
  border:#35a5e5 1px solid;
  box-shadow: 0 0 5px rgba(81, 203, 238, 1);
  -webkit-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
  -moz-box-shadow: 0 0 5px rgba(81, 203, 238, 1);
}

.radius {
	border: 1px solid #cccccc;
	border-radius: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
}

.cat_page_comments {margin-right: 10px;}

a img {border:0;}

.opros_line, .opros_line2 {height:2px; margin:0px; padding:0px;}

.editorbutton {PADDING-LEFT: 3px; FLOAT: left; CURSOR: pointer;}

.text_link {color: #00f;}
.text_link A:visited {color: #666;}
.text_link A:hover {text-decoration: none; border-bottom: 1px dashed blue;}

.green_link {color: #0f0;}
.red_link {color: #f00;}

.block {border: 1px solid gray; background: #deffde; padding: 5px; }

.small {font-size: 0.9em;}
.red, .red a {color:red;}
.black, .black a {color:black;}
.bold, .bold a {color:white; background:red;}
.select, .select a {color:black; font-weight:bold; background:#dddddd;}
.calendar {padding:5px;}

.pl20 {padding-left: 20px}

.hidden, .hide {
  display: none;
}

.error{
  display: inline;
  color: black;
  background-color: pink;  
}
.pages_links {color: black; display:inline; margin:0;padding:0;}
.pages_links a {color: black; border:1px solid #dddddd; padding:5px;}
.pages_links a:hover {color: black; background: #dddddd; padding:5px;}

/* Горизонт. меню (без подменю 3 уровня) */
.menu-h { overflow: hidden; }
.menu-h li { float: left; list-style: none; padding: 0 .6em; }

/* Горизонт. меню с подменю - 3 уровня */
.menu-h-d { min-height: 24px; padding:0; margin:0;}
.menu-h-d li { float: left; display: block; position: relative; list-style: none; border: 0;}
.menu-h-d a { text-decoration: none; padding: 1px 8px; display: block; border: 0;}

.menu-h-d ul { display: none; position: absolute; top: 30px; left: -1px; width: 300px; background: #fff; border: 0; padding:0; margin:0;}
.menu-h-d ul ul { left: 100%; top: -1px;  }

.menu-h-d li li { float: none; margin:0; width: 300px;}
.menu-h-d li:hover { background: #ccc; }

.menu-h-d li li a { text-decoration: none;}
.menu-h-d li a { text-decoration: none;}
.menu-h-d li li a:hover { text-decoration: underline;}
.menu-h-d li a:hover { text-decoration: underline;}

.menu-h-d li:hover ul,
.menu-h-d li:hover ul li:hover ul,
.menu-h-d li:hover ul li:hover ul li:hover ul { display: block; }

.menu-h-d li:hover ul ul,
.menu-h-d li:hover ul li:hover ul ul { display: none; }

/* Горизонт. меню, выпадающее вверх (без подменю 3 уровня) */
.menu-h-d.d-up ul { bottom: 22px; }
.menu-h-d.d-up ul ul { bottom: -1px; }

/* Вертикальное меню (без подменю 3 уровня) */
.menu-v { }
.menu-v li { padding: 2px 0; list-style: none; }
.menu-v li ul { padding-left: 1em; margin-top: 2px; }
.menu-v li li { border: none; }

/* Вертикальное меню с подменю - 3 уровня */
.menu-v-d { }
.menu-v-d li { padding: 2px 0; display: block; position: relative; list-style: none; }
.menu-v-d li a { display: block; position: relative; text-decoration: none;}

.menu-v-d li:hover { background: #ccc; }
.menu-v-d a:hover { color: #fff; }

.menu-v-d li ul { display: none; position: absolute; top: -1px; left: 100%; width: 100%; background: #fff; }
.menu-v-d ul ul { left: 100%; }
.menu-v-d li ul li { background: #fff; }

.menu-v-d li:hover ul ul,
.menu-v-d li:hover ul li:hover ul ul { display: none; }

.menu-v-d li:hover ul,
.menu-v-d li:hover ul li:hover ul,
.menu-v-d li:hover ul li:hover ul li:hover ul { display: block; }

.li1menu_link, .table1menu_link { text-decoration:none; }

/* Красивая таблица - стиль table_light */
table.table_light { border-collapse: collapse; border: 1px solid white; }
table.table_light td { border: 1px dashed #66bbdd; padding: .5em; color: black; }
table.table_light thead th, table.table_light tfoot th { border: 1px solid #A85070; text-align: left; background: #e9f2fc; color: black; padding-top:6px; }
table.table_light tbody td a { background: transparent; color: #0e5db6; }
table.table_light tbody td a:hover { background: transparent; color: red; }
table.table_light tbody th a { background: transparent; color: #0e5db6; }
table.table_light tbody th a:hover { background: transparent; color: #0e5db6; }
table.table_light tbody th, table.table_light tbody td { vertical-align: top; text-align: left; }
table.table_light tfoot td { border: 1px solid #38160C; padding-top:6px; }
table.table_light tbody tr:hover { background: #e9f2fc; }
table.table_light tbody tr:hover th, table.table_light tbody tr.odd:hover th { background: #e9f2fc; }

#menu { -webkit-padding-start: 0px; }

.img_left {float: left; margin-right: 10px; margin-bottom: 10px;}
.img_right {float: right; margin-left: 10px; margin-bottom: 10px;}

.table_left {float: left; margin-right: 10px; width: 10%; border:0;}
.table_right {float: right; margin-left: 10px; width: 20%; border:0;}
table[align=left] {margin-right: 10px;}
table[align=right] {margin-left: 10px;}

/* =========================================== */
/* Предустановки - перенос стилей из php */

.all_width, .main_mail_input {width:100%;}
.main_mail_form, .main_search_form, .add, .main_search_form {display:inline;}

.show_block { border: 2px dotted green; padding:1px;  border-radius: 5px;  -moz-border-radius: 5px;  -webkit-border-radius: 5px;}
.show_block_title {background: #efefef; color: black; padding:2px; padding-left:5px; border: 0;}

input[type=search] {
  -webkit-appearance: textfield;
  -webkit-box-sizing: content-box;
  font-family: inherit;
  font-size: 100%;
}
input::-webkit-search-decoration,
input::-webkit-search-cancel-button {
  display: none;
}

input[type=search] {
  background: #ededed url(images/view.gif) no-repeat 3px center;
  border: solid 1px #ccc;
  padding: 2px 3px 2px 20px;
  width: 50px; /* Ширина по умолчанию */
  -webkit-border-radius: 10em;
  -moz-border-radius: 10em;
  border-radius: 10em;
  -webkit-transition: all .5s;
  -moz-transition: all .5s;
  transition: all .5s;
}
input[type=search]:focus {
  width: 130px; /* Ширина при наличии фокуса ввода */
  background-color: #fff;
  border-color: #6dcff6;
  -webkit-box-shadow: 0 0 5px rgba(109,207,246,.5);
  -moz-box-shadow: 0 0 5px rgba(109,207,246,.5);
  box-shadow: 0 0 5px rgba(109,207,246,.5); /* Эффект свечения */
}
.main_search_button {display:none !important;}

.comm_label_textarea {padding-top:10px;}

.button {cursor:pointer; text-decoration: underline;}

a.search_razdel_link, a.search_papka_link {color:gray !important;}
a.search_page_link {font-size:20px !important;}

.ad_button {width:100px; display:block; text-align:center; float:left; margin:5px; line-height:1em !important; font-size:12px !important;}
.ad_icon {height:32px; display:block; margin: 0 auto;}

.nav-tabs ul li, .tabs ul li {background:none !important; padding: 0 !important;}
ul.tabs {margin:10px 0 -1px 0 !important; padding: 0 !important;}
ul.tabs li {background:none !important; padding: 0 !important; margin: 0 !important; }
.tabs ul li.ui-tabs-active {background:white !important; }
.nav-tabs, .nav-tabs ul {height:32px !important; }
.nav-tabs {width:100% !important;}
.ui-widget-header, ul.tabs li.current {border-bottom:0 !important;}
".$sha;

$n = count($name);
if ($n > 0) {
	for ($x=0; $x < $n; $x++) {
	$i = intval($name[$x]);
     $sql = "select `text` from ".$prefix."_mainpage where `id`='".$i."' and (`type`='1' or (`type`='3' and `name`='31')) and `tables`='pages' and `color`='0'";
     $result = $db->sql_query($sql);
     $row = $db->sql_fetchrow($result);
     $contents .= "\n".$row['text']; 
	}
}

if ($type == 'js') {
  for ($i = 1; $i < 10; $i++) {
    $contents = str_replace("\n\n", "\n", $contents); //Удаляем переносы строк
    $contents = str_replace("\r\r", "\r", $contents); //Удаляем переносы строк
    $contents = str_replace("\r\n\r\n", "\r\n", $contents); //Удаляем переносы строк
  }
  header ("Content-Type: text/javascript");
} elseif ($type == 'css') {
$contents = str_replace("color:white","color:#ffffff",$contents); // Заменим основные цвета...
$contents = str_replace("color:black","color:#000000",$contents);
$contents = str_replace("color:red","color:#ff0000",$contents);
$contents = str_replace("color:green","color:#00ff00",$contents);
$contents = str_replace("color:blue","color:#0000ff",$contents);
$contents = preg_replace('/\/\*.*?\*\//s', ' ', $contents); // Удаляем все комментарии
$contents = str_replace("\r", " ", str_replace("\n", " ", $contents)); //Удаляем переносы строк
$contents = str_replace(chr(9), "", $contents); //Удаляем табуляцию
$contents = str_replace(" }", "}", $contents); //Удаляем пробелы...
$contents = str_replace(" {", "{", $contents);
$contents = str_replace("{ ", "{", $contents);
$contents = str_replace("} ", "}", $contents);
$contents = str_replace("; ", ";", $contents);
$contents = str_replace(" ;", ";", $contents);
$contents = str_replace(" :", ":", $contents);
$contents = str_replace(": ", ":", $contents);
$contents = str_replace("+ ", "+", $contents);
$contents = str_replace(" +", "+", $contents);
$contents = str_replace("= ", "=", $contents);
$contents = str_replace(" =", "=", $contents);
$contents = str_replace("- ", "-", $contents);
$contents = str_replace("/ ", "/", $contents);
$contents = str_replace(" /", "/", $contents);
$contents = str_replace(", ", ",", $contents);
$contents = str_replace(" ,", ",", $contents);
$contents = str_replace("  ", " ", $contents);
header ("Content-Type: text/css");
}
echo $contents;
?>	
