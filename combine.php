<?php
  @require_once("config.php"); // Настройки сайта
  @require_once("includes/db.php"); // База данных (функции для работы)
  @require_once("includes/sql_layer.php");
  global $prefix, $db; 

if (isset($_GET['add'])) $add = $_GET['add']; else $add = "";
$name = explode("-", $_GET['files']);

// Добавочный шаблон стиля
if ($add != "") {
  $add = explode("_",$add); // ввести дополнительную проверку
  require_once("shablon_style.php");
  $sha = shablon_style_show ($add[0], $add[1]);
} else $sha = "";


// ДОБАВИТЬ — word-wrap: break-word; - перенос длинных слов в Дивах
/*
div {
    white-space: pre; 
    white-space: pre-wrap;
    white-space: pre-line;
    white-space: -moz-pre-wrap !important;
    white-space: -hp-pre-wrap;
    white-space: -o-pre-wrap;
    white-space: -pre-wrap;
    word-wrap: break-word;
}
*/

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

/*
.cat_page_text IMG, .page_opentext IMG, .page_text IMG {
	background:url('images/loading.gif') no-repeat center; 
}
*/
.comm_form #avtory, .comm_form #maily, .comm_form #adres, .comm_form #tel, .comm_form #area {width: 99%;}

del { color:#666; }

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

.overcomm {max-height:150px; overflow:auto;}

.radius {
	border: 1px solid #cccccc;
	border-radius: 10px;
	/* Gecko (Firefox 3.6+) */
	-moz-border-radius: 10px;
	/* WebKit (Safari/Chrome) */
	-webkit-border-radius: 10px;
}

/* Выравнивание DIV */
.align_center {	position: relative;	width: 100%;}
.align_center:after {content: '';display: block;clear: both;}
.align_center_to_left {position: relative;right: 50%;float: right;}
.align_center_to_right {position: relative;z-index: 1;right: -50%;}

.cat_page_comments {margin-right: 10px;}

a img {border:0;}

/* 
A[target=\"_blank\"] { 
	background: url(images/sys/028.png) 0 6px no-repeat;
	padding-left: 15px;
}
*/

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

#menu {
-webkit-padding-start: 0px;
}

.img_left, img.justifyleft {float: left; margin-right: 10px; margin-bottom: 10px;}
.img_right, img.justifyright {float: right; margin-left: 10px; margin-bottom: 10px;}

.table_left {float: left; margin-right: 10px; width: 10%; border:0;}
.table_right {float: right; margin-left: 10px; width: 20%; border:0;}
table[align=left] {margin-right: 10px;}
table[align=right] {margin-left: 10px;}


.icon {margin-right: 5px; margin-left: 5px; width:16px; height:16px; border:0;}
.icon2 {margin:0; padding:0; margin-right: 2px; width:16px; height:16px; border:0; cursor:pointer;}
.i1 {background: url('/images/icons.png'); }
.i2 {background: url('/images/icons.png') -16px; }
.i3 {background: url('/images/icons.png') -32px; }
.i4 {background: url('/images/icons.png') -48px; }
.i5 {background: url('/images/icons.png') -64px; }
.i6 {background: url('/images/icons.png') -80px; }
.i7 {background: url('/images/icons.png') -96px; }
.i8 {background: url('/images/icons.png') -112px; }
.i9 {background: url('/images/icons.png') -128px; }
.i10 {background: url('/images/icons.png') -144px; }
.i11 {background: url('/images/icons.png') -160px; }
.i12 {background: url('/images/icons.png') -176px; }
.i13 {background: url('/images/icons.png') -192px; }
.i14 {background: url('/images/icons.png') -208px; }
.i15 {background: url('/images/icons.png') -224px; }
.i16 {background: url('/images/icons.png') -240px; }
.i17 {background: url('/images/icons.png') -256px; }
.i18 {background: url('/images/icons.png') -272px; }
.i19 {background: url('/images/icons.png') -288px; }
.i20 {background: url('/images/icons.png') -304px; }
.i21 {background: url('/images/icons.png') -320px; }
.i22 {background: url('/images/icons.png') -336px; }
.i23 {background: url('/images/icons.png') -352px; }
.i24 {background: url('/images/icons.png') -368px; }
.i25 {background: url('/images/icons.png') -384px; }
.i26 {background: url('/images/icons.png') -400px; }
.i27 {background: url('/images/icons.png') -416px; }
.i28 {background: url('/images/icons.png') -432px; }
.i29 {background: url('/images/icons.png') -448px; }
.i30 {background: url('/images/icons.png') -464px; }
.i31 {background: url('/images/icons.png') -480px; }
.i32 {background: url('/images/icons.png') -496px; }
.i33 {background: url('/images/icons.png') -512px; }
.i34 {background: url('/images/icons.png') -528px; }
.i35 {background: url('/images/icons.png') -544px; }
.i36 {background: url('/images/icons.png') -560px; }
.i37 {background: url('/images/icons.png') -576px; }
.i38 {background: url('/images/icons.png') -592px; }
.i39 {background: url('/images/icons.png') -608px; }
.i40 {background: url('/images/icons.png') -624px; }
.i41 {background: url('/images/icons.png') -640px; }
.i42 {background: url('/images/icons.png') -656px; }
.i43 {background: url('/images/icons.png') -672px; }
.i44 {background: url('/images/icons.png') -688px; }
.i45 {background: url('/images/icons.png') -704px; }
.i46 {background: url('/images/icons.png') -720px; }

/* =========================================== */
/* Предустановки - перенос стилей из php */

.all_width, .main_mail_input {width:100%;}
.main_mail_form, .main_search_form, .add, .main_search_form {display:inline;}

.show_block { border: 2px solid black; padding:2px;  border-radius: 12px;  -moz-border-radius: 12px;  -webkit-border-radius: 12px;}
.show_block_title {background: #7f7f7f; color: white; padding:2px; padding-left:5px; border: 0;  border-radius: 10px;  -moz-border-radius: 10px;  -webkit-border-radius: 10px;}

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

a.button {cursor:pointer; text-decoration: underline;}

a.search_razdel_link, a.search_papka_link {color:gray !important;}
a.search_page_link {font-size:20px !important;}
".$sha;

$n = count($name);
if ($n > 0) {
	for ($x=0; $x < $n; $x++) {
	$i = intval($name[$x]);
     $sql = "select text from ".$prefix."_mainpage where id='".$i."'";
     $result = $db->sql_query($sql);
     $row = $db->sql_fetchrow($result);
     $contents .= "\n".$row['text']; 
	}
}
             //   if ($type == 'javascript') {
             //    for ($i = 1; $i < 10; $i++) {
             //    $contents = str_replace("\n\n", "\n", $contents); //Удаляем переносы строк
             //    $contents = str_replace("\r\r", "\r", $contents); //Удаляем переносы строк
             //    $contents = str_replace("\r\n\r\n", "\r\n", $contents); //Удаляем переносы строк
             //    }
             //   }
//                if ($type == 'css') {
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
echo $contents;
?>	
