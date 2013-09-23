<?php
	if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
	$aid = trim($aid);
	global $prefix, $db, $red;
	$sql = "select realadmin from ".$prefix."_authors where aid='$aid'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$realadmin = $row['realadmin'];
	if ($realadmin==1) {
////////////////////////////////////////////////////////
function parser_main() {
	global $prefix, $db;
	include("ad/ad-header.php");
	parser_js();
	echo "<table style='width:100%; margin-top:5px; padding:0; background: #e2e5ea;' cellspacing=0 cellpadding=0><tr valign=top><td id='razdel_td' class='radius nothing' width=340><div id='razdels' style='background:#e7e9ec;'><div class='black_grad'><button id=new_razdel_button title='Назад в настройки сайта' class='small black' onclick='location.href=\"/sys.php?op=Configure#1\"' style='float:left; margin:3px;'><span style='margin-right: -2px;' class=\"icon darkgrey small\" data-icon=\"{\"></span></button><span class='h1'>Парсер</span></div>";
	echo "<div class='right3 '><button id=mainrazdel3 style='float:right;' title='Новый поток...' class='dark_pole2' onclick=\"options_show('3','add_rss');\"><span class='mr-2 icon darkgrey small' data-icon='+'></span><span class='plus20'>Добавить RSS</span></button></div>
<div id='mainrazdel1' class='dark_pole2'><a class='base_page' onclick=\"options_show('1','rss')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"A\"></span><span class='plus20'>RSS</span></div></a></div>
<div class='right3 '><button id=mainrazdel4 style='float:right;' title='Новый сайт' class='dark_pole2' onclick=\"options_show('4','add_site');\"><span class='mr-2 icon darkgrey small' data-icon='+'></span><span class='plus20'>Добавить сайт</span></button></div>
<div id='mainrazdel0' class='dark_pole2'><a class='base_page' onclick=\"options_show('0','pauk')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"E\"></span><span class='plus20'>Сканер</span></div></a></div>
<div class='right3 '><button id=mainrazdel5 style='float:right;' title='Справка' class='dark_pole2' onclick=\"options_show('5','add_shablon');\"><span class='mr-2 icon darkgrey small' data-icon='+'></span><span class='plus20'>Справка</span></button></div>
<div id='mainrazdel2' class='dark_pole2'><a class='base_page' onclick=\"options_show('2','shablon')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"S\"></span><span class='plus20'>Шаблоны</span></div></a></div>
</div></td><td>";
echo "<div id='rss' class='show_pole'>";
$result = $db->sql_query("SELECT * FROM ".$prefix."_parser where id = '0'") or die("<div class='notice warning'><a href='sys.php?op=parser_install'>Установить парсер</a></div>");
echo 'страница rss';
echo "</div><div id='add_rss' class='show_pole' style='display:none;'>";
echo 'страница добавления rss';
echo "</div><div id='add_site' class='show_pole' style='display:none;'>
<br><br><center><h1>Добавить новый сайт для сканера и последующего создания копии сайта или части его.</h1>
<div id='result'></div><br><br>Домен: <input id='sites'> без http:// и www
<h2>если необходима авторизация введите</h2>
Логин: <input id='logins'><br>Пароль: <input id='passs'><br>
<input type='button' value='Добавить' onclick='parser(1)'> <hr></center>";
echo "</div></div><div id='add_shablon' class='show_pole' style='display:none;'>";
echo '<h1>Справка</h1>Номер  по счету - если элементов соответсвующих поиску несколько укажите номер элемента который необходимо найти. Иначе будут найдены все элементы соответсвующие поиску.<br><hr>
Поиск элементов:<br>
div - найдет div или все div<br>
#nid - найдет элемент id которого равен nid<br>
.myclass - найдет элемент class которого равен myclass<br><hr>
Комбинированный:<br>
.myclass #nid div - сначала найдутся все классы равные .myclass, затем в найденном id = nid, и в конце выберутся все div<br><hr>
img [width] - найдет нам все изображения у которых задан атрибут ширина<br>
img[width=400px] - найдет все изображения, у которых задана ширина равная 400px<br>
text - найдет все текстовые блоки в html<br><hr>
Получить путь изображения:<br>
найти элемент - img<br>
вывести содержание - src<br><hr>
Получить ссылку:<br>
найти элемент - a<br>
вывести содержание - href<br><hr>
Получить только текст (&quot;&lt;div&gt;foo &lt;b&gt;bar&lt;/b&gt;&lt;/div&gt;&quot;):<br>
найти элемент - div<br>
вывести содержание - plaintext (foo bar)<br><hr>
Получить внутренний HTML (&quot;&lt;div&gt;foo &lt;b&gt;bar&lt;/b&gt;&lt;/div&gt;&quot;):<br>
найти элемент - div<br>
вывести содержание - innertext (foo &lt;b&gt;bar&lt;/b&gt;)<br><hr>
Получить весь HTML (&quot;&lt;div&gt;foo &lt;b&gt;bar&lt;/b&gt;&lt;/div&gt;&quot;):<br>
найти элемент - div<br>
вывести содержание - outertext (&lt;div&gt;foo &lt;b&gt;bar&lt;/b&gt;&lt;/div&gt;)<br>';
echo "</div></div><div id='pauk' class='show_pole' style='display:none;'><h1>Список сайтов</h1><table width='100%' border='1'>";
$results = $db->sql_query("SELECT * FROM ".$prefix."_parser where `class`='s'");
while ($ress = $db->sql_fetchrow($results)) {
if (!$ress['config']) $sk = 0; else
$sk = count(explode('|',$ress['config'])); // Сколько исключений
if ($ress['title'] and $ress['text']) $avto = 'Есть авторизация'; else $avto = 'Без авторизации';
$vs = file('files/'.$ress['url']); // всего нашли урл
$go = file('files/go'.$ress['url']); // обработали
$vsego = count($vs); // сколько всего сканер нашел страниц
$gotov = count($go); // сколько страниц обработал
if($vsego !== $gotov) {$skol = 'Страниц найденно - '.$vsego.' Страниц обработанно - '.$gotov; $smotr = true;}
if($vsego == $gotov) { unset($skol); $skol = 'Страниц готово - '.$vsego.' Конец работы';  $smotr = false;}

echo "<tr><td><h2>".$ress['url']."</h2></td><td><span id='log".$ress['id']."'>".$avto."</span><br><div id='a".$ress['id']."'><a onclick='parser(3,".$ress['id'].")' href='#'>Редактировать</a></div>
<div id='config".$ress['id']."'></div></td><td><div id='results".$ress['id']."'>".$skol."</div></td><td>";
if ($smotr !== true) echo"<a href='/sys.php?op=parser_prev&id=".$ress['id']."'><button type='submit' class='medium black'><span class='mr-2 icon red medium' data-icon='c' style='display: inline-block; '></span>Начать импорт</button></a>";
if ($smotr == true) echo "<button id='but2".$ress['id']."' type='submit' onclick='parser_site(".$ress['id'].")' class='medium black'><span class='mr-2 icon red medium' data-icon='c' style='display: inline-block; '></span>Искать</button>";
echo "</td></tr>";
}
echo "</table></div></div><div id='shablon' class='show_pole' style='display:none;'>";
echo "<h1>Шаблоны для парсера</h1>
ИМЯ: <input id='shab'><input type='button' value='Добавить' onclick='parser(6)'>
<div id='sh'>";
$res = $db->sql_query("SELECT id, url FROM ".$prefix."_parser where class='h'");
while ($row = $db->sql_fetchrow($res)) {
echo '<b>'.$row['url'].'</b>   <a href="/sys.php?op=parser_temp&id='.$row['id'].'">Редактор</a>   <a href="/sys.php?op=parser_temp_del&id='.$row['id'].'">Удалить</a><hr>';
}
echo '</div></div></td></tr></table>';

}
function parser_temp() {
global $prefix, $db;
	include("ad/ad-header.php");
	parser_js();
	$id = $_GET['id'];
	$res = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where id='".$id."'"));
	echo "<table style='width:100%; margin-top:5px; padding:0; background: #e2e5ea;' cellspacing=0 cellpadding=0>
	<tr valign=top><td id='razdel_td' class='radius nothing' width=340><div id='razdels' style='background:#e7e9ec;'>
	<div class='black_grad'><button id=new_razdel_button title='Назад' class='small black' onclick='location.href=\"/sys.php?op=parser_main\"' style='float:left; margin:3px;'>
	<span style='margin-right: -2px;' class=\"icon darkgrey small\" data-icon=\"{\"></span></button><span class='h1'>Настройка шаблона</span></div>
<div class='right3 '>
</div>
<div id='mainrazdel1' class='dark_pole2'>
<a class='base_page' onclick=\"options_show('1','shablon')\"><div id='mainrazdel".$id."'>
<span class=\"icon gray large\" data-icon=\"S\"></span><span class='plus20'>Шаблон</span></div></a></div>
<div class='right3 '>
</div>
<div id='mainrazdel0' class='dark_pole2'><a class='base_page' onclick=\"options_show('0','pravila')\">
<div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"!\"></span><span class='plus20'>Правила</span></div></a></div>
</div>
</td><td>
<div id='shablon' class='show_pole' style='display:none;'>";
$resz = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where class='zh".$res['url']."'"));
$reso = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where class='oh".$res['url']."'"));
$resm = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where class='mh".$res['url']."'"));
$resk = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where class='kh".$res['url']."'"));
$resd = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where class='dh".$res['url']."'"));
echo "<form action='sys.php?op=parser_temp_edit&id=".$id."'  method='post'><table><tr><td>
Заголовок: </td><td><input type='text' size='100' name='z' value='".$resz['url']."'></td></tr><tr><td>
Краткая часть: </td><td><input type='text' size='100' name='o' value='".$reso['url']."'></td></tr><tr><td>
Полная часть: </td><td><input type='text' size='100' name='m' value='".$resm['url']."'></td></tr><tr><td>
Keywords: </td><td><input type='text' size='100' name='k' value='".$resk['url']."'></td></tr><tr><td>
Descriptions: </td><td><input type='text' size='100' name='d' value='".$resd['url']."'></td></tr></table>
<button type='submit' class='medium green'><span class='mr-2 icon white medium' data-icon='c' style='display: inline-block; '></span>Изменить</button>
</form>
</div>
<div id='pravila' class='show_pole' style='display:none;'>
<b>Новое правило -</b><br><table><tr><td> 
имя:</td><td><input id='name' value='правило".$id."'></td></tr><tr><td>
использует:</td><td><select class='ishod'>
<option value='0'>Страница</option>";
$res2 = $db->sql_query("SELECT * FROM ".$prefix."_parser where class='h".$res['url']."'");
$rty = '';
while ($row = $db->sql_fetchrow($res2)) {
if ($row['config'] == 0)
echo "<option value='".$row['url']."'>".$row['url']."</option>";
$el = explode('|',$row['text']);
$rty .= '<tr><td>'.$row['url'].'</td><td>'.$row['config'].'</td><td>'.$row['title'].'</td><td>'.$el[0].'</td><td>'.$el[1].'</td><td><a href="#" onclick="parser(9,'.$row['id'].')">Удалить</a></td><td></tr>';
}
echo "</select></td></tr><tr><td>
номер элемента:</td><td><input id='nomer' value='0'></td></tr><tr><td>
элемент:</td><td><input id='elem' value='div .myclass'></td></tr><tr><td>
извлекаем:</td><td><input id='metod' value='plaintext'></td></tr></table>
<button type='submit' onclick='parser(8,".$id.")' class='medium green'><span class='mr-2 icon white medium' data-icon='c' style='display: inline-block; '></span>Добавить правило</button>
<hr>
<h1>Список правил</h1><div id='temp'>
<table width='100%'><tr><td>Правило</td><td>Использует</td><td>Номер эл-та</td><td>Элемент</td><td>Извлекаем</td><td></td><td></tr>";
echo $rty;
echo "</table></div></div>
</td></tr></table>";
}
function parser_temp_edit() {
global $prefix, $db;
$id = $_GET['id'];
$res = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where id='".$id."'"));
$db->sql_query("UPDATE ".$prefix."_parser SET `url`='".$_POST['z']."' where class='zh".$res['url']."'");
$db->sql_query("UPDATE ".$prefix."_parser SET `url`='".$_POST['o']."' where class='oh".$res['url']."'");
$db->sql_query("UPDATE ".$prefix."_parser SET `url`='".$_POST['m']."' where class='mh".$res['url']."'");
$db->sql_query("UPDATE ".$prefix."_parser SET `url`='".$_POST['k']."' where class='kh".$res['url']."'");
$db->sql_query("UPDATE ".$prefix."_parser SET `url`='".$_POST['d']."' where class='dh".$res['url']."'");
Header("Location: sys.php?op=parser_temp&id=".$id);
}
function parser_temp_del() {
global $prefix, $db;
$id = $_GET['id'];
$res = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where id='".$id."'"));
$db->sql_query("DELETE FROM ".$prefix."_parser WHERE id='".$id."'");
$db->sql_query("DELETE FROM ".$prefix."_parser WHERE class='h".$res['url']."'");
$db->sql_query("DELETE FROM ".$prefix."_parser where class='zh".$res['url']."'");
$db->sql_query("DELETE FROM ".$prefix."_parser where class='oh".$res['url']."'");
$db->sql_query("DELETE FROM ".$prefix."_parser where class='mh".$res['url']."'");
$db->sql_query("DELETE FROM ".$prefix."_parser where class='kh".$res['url']."'");
$db->sql_query("DELETE FROM ".$prefix."_parser where class='dh".$res['url']."'");
Header("Location: sys.php?op=parser_main");
}
function parser_prev() {
global $prefix, $db;
$id = $_GET['id'];
include("ad/ad-header.php");
parser_js();
$site = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where id='".$id."'"));
echo "<div style='background: #e2e5ea;'><div class='black_grad' style='height:45px;'>
	<span class='h1'>Настройка импорта - ".$site['url']."</span></div>
	<table width='100%'><tr><td>
	<b>Если:</b> <select class='esli'>
	<option value='0'>URL</option>
	<option value='1'>Страница</option></select></td><td>
	<b>Содержит:</b> <input id='sodr'></td><td>
	<b>Записать в:</b> <select class='imv'>";
		$res = $db->sql_query("SELECT id, title, name FROM ".$prefix."_mainpage where type='2' and id !='24'");
while ($row = $db->sql_fetchrow($res)) {
echo "<option value='".$row['name']."|0'>".$row['title']."</option>";
		$res3 = $db->sql_query("SELECT cid, title FROM ".$prefix."_pages_categories where module='".$row['name']."'");
while ($row3 = $db->sql_fetchrow($res3)) {
echo "<option value='".$row['name']."|".$row3['cid']."'>Папка - ".$row3['title']."</option>";
}
}
	echo "</select></td><td>
	<b>Используя шаблон:</b> <select class='sh'>";
	$res2 = $db->sql_query("SELECT id, url, config FROM ".$prefix."_parser where class='h'");
while ($row = $db->sql_fetchrow($res2)) {
echo "<option value='".$row['id']."'>".$row['url']."</option>";
}
	echo "</select></td><td>
	<b>Уникализация:</b> <select class='plag'>
	<option value='0'>НЕТ</option>
	<option value='1'>ДА</option></select></td></tr></table><hr>
	<table width='100%' align='center'><tr><td>
	<button type='submit' onclick='parser(12,".$id.")' class='medium green'><span class='mr-2 icon white medium' data-icon='c' style='display: inline-block; '></span>Записать</button>
	</td><td><button type='submit' onclick='parser(11,".$id.")' class='medium blue'><span class='mr-2 icon white medium' data-icon='c' style='display: inline-block; '></span>Пример</button>
	</td><td><button type='submit' onclick='parser(10,".$id.")' class='medium blue'><span class='mr-2 icon white medium' data-icon='c' style='display: inline-block; '></span>Осталось</button>
	</td><td><a href='/sys.php?op=parser_clear&id=".$id."'>
	<button type='submit' class='medium black'><span class='mr-2 icon red medium' data-icon='c' style='display: inline-block; '></span>Очистить проект</button></a>
	</td></tr></table><hr><div id='progect_info'></div>";
}
function parser_clear() {
global $prefix, $db;
$id = $_GET['id'];
$res = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where id='".$id."'"));
$filestr = fopen ('files/'.$res['url'],"w+");
fwrite($filestr, $res['url']."/\n");
fclose ($filestr);
$filestr2 = fopen ('files/go'.$res['url'],"w+");
fwrite($filestr2, '');
fclose ($filestr2);
$db->sql_query("DELETE FROM ".$prefix."_parser where class='s".$id."'");
Header("Location: sys.php?op=parser_main");
}
function parser_del() {
global $prefix, $db;
$id = $_GET['id'];
$res = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where id='".$id."'"));
unlink('files/'.$res['url']);
unlink('files/go'.$res['url']);
$db->sql_query("DELETE FROM ".$prefix."_parser where class='s".$id."'");
$db->sql_query("DELETE FROM ".$prefix."_parser where id='".$id."'");
Header("Location: sys.php?op=parser_main");
}
function parser_js() {
// function parser
// 1 - добавляем сайт
// 2 - сохранение авторизация
// 3 - форма редактирования
// 4 - сохранение исключений
// 6 - добавление шаблона
// 7 - удаление исключений
// 8 - добавляем правило
// 9 - удаляем правило
// 10 - оставшееся проект
// 11 - пример проект
// 12 - запись из паука
// parser_site - сканер сайта
//
echo "<script type='text/javascript'>
function parser(action,id){
     var xps = id;
	 if ( action == 1) { var site =document.getElementById('sites').value; login =document.getElementById('logins').value; pass =document.getElementById('passs').value; }
	 if ( action == 2) { login =document.getElementById('logins'+id).value; pass =document.getElementById('passs'+id).value; }
	 if ( action == 4) { is =document.getElementById('is'+id).value; }
	 if ( action == 6) { var si =document.getElementById('shab').value;  }
	 if ( action == 8) { var name =document.getElementById('name').value;
	                     var ishod = $('select.ishod').val();
                         var nomer =document.getElementById('nomer').value;
						 var elem =document.getElementById('elem').value;
                         var metod =document.getElementById('metod').value;}
	 if ( action == 11) { var esli = $('select.esli').val();
	 var sodr =document.getElementById('sodr').value;  }
if ( action == 12) { var esli = $('select.esli').val();
	 var sodr =document.getElementById('sodr').value;
var imv = $('select.imv').val();
var sh = $('select.sh').val();
var plag = $('select.plag').val();	 }	 
	  xps=new XMLHttpRequest(); xps.onreadystatechange=function() {
    if (xps.readyState==4 && xps.status==200)
	if ( action == 1) { document.getElementById('result').innerHTML = xps.responseText; }
	if ( action == 2) { document.getElementById('log'+id).innerHTML = xps.responseText; document.getElementById('lp').innerHTML = 'Сохранили'}
	if ( action == 3) {  document.getElementById('config'+id).innerHTML = xps.responseText;  }
	if ( action == 4) {  document.getElementById('isres'+id).innerHTML = xps.responseText; 	}
	if ( action == 6) {  document.getElementById('sh').innerHTML = xps.responseText; 	}
	if ( action == 7) {  document.getElementById('isres'+id).innerHTML = xps.responseText; 	}
	if ( action == 8) {  document.getElementById('temp').innerHTML = xps.responseText; 	}
	if ( action == 9) {  document.getElementById('temp').innerHTML = xps.responseText; 	}
	if ( action == 10) {  document.getElementById('progect_info').innerHTML = xps.responseText; }
	if ( action == 11) {  document.getElementById('progect_info').innerHTML = xps.responseText; }
	if ( action == 12) {  document.getElementById('progect_info').innerHTML = xps.responseText; }
	}
	 xps.open('POST','parser.php',true); 
	xps.setRequestHeader('Content-type','application/x-www-form-urlencoded');
if ( action == 1) { xps.send('action=1&site='+site+'&login='+login+'&pass='+pass); }
if ( action == 2) { xps.send('action=2&site='+id+'&login='+login+'&pass='+pass);}
if ( action == 3) { xps.send('action=3&site='+id); $('#a'+id).hide(); }
if ( action == 4) { xps.send('action=4&site='+id+'&login='+is); }
if ( action == 6) { xps.send('action=6&site='+si); }
if ( action == 7) { xps.send('action=7&site='+id); }
if ( action == 8) { xps.send('action=8&site='+id+'&nomer='+nomer+'&ishod='+ishod+'&name='+name+'&elem='+elem+'&metod='+metod); }
if ( action == 9) { xps.send('action=9&site='+id); }
if ( action == 10) { xps.send('action=10&site='+id); }
if ( action == 11) { xps.send('action=11&site='+id+'&esli='+esli+'&sodr='+sodr); }
if ( action == 12) { xps.send('action=12&site='+id+'&esli='+esli+'&sodr='+sodr+'&imv='+imv+'&sh='+sh+'&plag='+plag); }
  }
    function parser_site(id){
	var xps = id; $('#but2'+id).hide(); $('#a'+id).hide(); document.getElementById('config'+id).innerHTML = '';
 xps=new XMLHttpRequest(); xps.onreadystatechange=function() {
 if (xps.readyState==4 && xps.status==200) {
 document.getElementById('results'+id).innerHTML = xps.responseText;
 startsite(xps,id);
 }
 }
 startsite(xps,id);
  }
  function startsite(xps,id){
   xps.open('POST','parser.php',true); 
 xps.setRequestHeader('Content-type','application/x-www-form-urlencoded');
 xps.send('action=5&site='+id);
  }
 function close_site(id){ document.getElementById('config'+id).innerHTML = ''; $('#a'+id).show();} 
</script>";
}
function parser_install() {
	global $prefix, $db;
	$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_parser`;");
	$db->sql_query("CREATE TABLE `".$prefix."_parser` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`class` text,
	`url` text,
	`title` text,
	`text` text,
	`config` text,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM ;");
	Header("Location: sys.php?op=parser_main");
}
////////////////////////////////////////////////////////
	switch ($op) {
		case "parser_ajax":
		parser_ajax();
	    break;
	    case "parser_main":
		parser_main();
	    break;
		case "parser_install":
		parser_install();
	    break;
		case "parser_temp":
		parser_temp();
	    break;
		case "parser_temp_del":
		parser_temp_del();
	    break;
		case "parser_temp_edit":
		parser_temp_edit();
	    break;
		case "parser_prev":
		parser_prev();
	    break;
		case "parser_clear":
		parser_clear();
	    break;
		case "parser_del":
		parser_temp_del();
	    break;
	}
}
?>
