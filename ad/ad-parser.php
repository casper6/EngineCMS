<?php
	if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die (aa("Доступ закрыт!")); }
	$aid = trim($aid);
	global $prefix, $db, $red;
	$row = $db->sql_fetchrow($db->sql_query("SELECT `realadmin` FROM ".$prefix."_authors where `aid`='".$aid."'"));
	$realadmin = $row['realadmin'];
	if ($realadmin == 1) {
////////////////////////////////////////////////////////
function parser_main() {
	global $prefix, $db;
	include("ad/ad-header.php");
	echo "<div id='razdels' class='light_fon'>
	<div class='black_grad'><span class='h1'>Парсер</span></div>";
	require_once("includes/seo.php");
	parser_js();
	$result = $db->sql_query("SELECT * FROM ".$prefix."_parser where `id`='0'") or die("<div class='notice warning'><a href='sys.php?op=parser_install'>Установить парсер</a></div>");
	$res = $db->sql_fetchrow($db->sql_query("SELECT `config` FROM ".$prefix."_parser where `class`='config'"));
	$PCon = explode('][',$res['config']);
	$PRazdel = explode('}{',$PCon[2]);
	// 0 - Логин на Яндексе
	// 1 - Ключ АПИ Яндекса
	// 2 - Раздел для сохранения
	// 3 - Включение статей
	// 4 - Авто key и desc
	// 5 - Ключи в облако
	// 6 - Предложений в предисловии
	// 7 - Глубина поиска через Яндекс
	// 8 - Если есть ключи берем их
	// 9 - Тоже с описанием
	// 10 - метод поиска
	echo "
	<input type='button' value='Парсинг' onClick='showpars(1)'>
	<input type='button' value='RSS' onClick='showpars(2)'>
	<input type='button' value='Настройки' onClick='showpars(3)'>
	<input type='button' value='Крон' onClick='showpars(4)'>

	<div id='pardiv1' style='display: none;'>
		<table width='100%'><tr valign=top><td width='33%'>
		<h1>Забрать статьи с URL-ов</h1>
		<textarea id='par1' style='width: 100%; height: 220px;'></textarea><br>
		<input type='submit' onClick='parser(1)' value='Начать'>
		</td><td width='33%'>
		<h1>Сканировать сайт</h1>
		<p style='color: red'>НЕ РАБОТАЕТ</p>
		<input id='par2' type='text' value='url с http'>
		<br><input type='submit' onClick='parser(2)' value='Сканировать'>
		</td><td>
		<h1>Сканировать по Яндексу</h1>
		<input id='par3' type='text' value='запрос'>
		<br><input type='submit' onClick='parser(3)' value='Сканировать'>
		</td></tr></table>
	</div>

	<div id='pardiv2' style='display: none;'>
		<table width='100%'><tr valign=top><td width='33%'>
		<h1>Добавление RSS каналов</h1>
		<input id='par21' type='text' value=''><br>
		<input type='submit' onClick='parser(7)' value='Добавить'></td><td>
		<h1>Список RSS каналов</h1>
		<input type='submit' onClick='parser(9)' value='Проверить RSS'>
		<div id='rsslist'><table class='table_light'>";
		$res2 = $db->sql_query("SELECT * FROM ".$prefix."_parser  where `class`='rss_chanel'");
		$i = 1;
		while ($row = $db->sql_fetchrow($res2)) {
			echo "<tr><td>".$i."</td><td>".$row['url']."</td>
			<td><a title='удалить' class='button red white punkt' onClick='pardellink(".$row['id'].",2)'>Удалить</a></td></tr>";
			$i++;
		}
		echo "</table></div>
		</td></tr></table>
	</div>

	<div id='pardiv3'>
		<table width='100%'><tr valign=top><td width='33%'>
		<h1>Настройка для Яндекса</h1>
		<table class='table_light'>
		<tr><td>Брать первые</td><td>
		".select("par17", "10,20,30,40,50,60,70,80,90,100", "10,20,30,40,50,60,70,80,90,100", $PCon[7], " style='width:70px;'")." сайтов
		</td></tr><tr><td>
		Искать</td><td>";
		$vs = $db->sql_numrows($db->sql_query("SELECT id FROM ".$prefix."_parser where `class`='1'"));
		
		echo "".select("par110", "1,2", "Любые тексты,Только статьи", $PCon[10], " style='width:160px;'")."</td></tr>
		<tr><td></td><td><input type='submit' onClick='parser(4)' value='Сохранить Настройки'></td></tr>
		<tr><td>Осталось промежуточных записей</td><td>".$vs."</td></tr>
		<tr><td>
		<a class='button green white punkt' onClick='parser(8)'>Продолжить проверку</a></td><td>
		<a class='button red white punkt' onClick='parser(5)'>Удалить записи</a></td></tr></table>
		</td><td width='33%'>

		<h1>Общие настройки</h1>
		<p>Сохранять в раздел<br><select id='par12'><option value='".$PRazdel[0]."'>".$PRazdel[1]."</option>";
		$result2 = $db->sql_query("SELECT * FROM ".$prefix."_mainpage where `type` = '2' and `id` != '".$PRazdel[0]."' and (`useit` LIKE '%[содержание]%' or `useit` LIKE '%[страницы]%')");
		while ($row = $db->sql_fetchrow($result2)) {
			echo "<option value='".$row['id']."'>".$row['title']."</option>";
		}
		echo "</select>
		<p>".select("par13", "0,1", "НЕТ,ДА", $PCon[3])."Включить статьи<br><br>
		<p>".select("par6", "0,1", "НЕТ,ДА", $PCon[4])."Авто определение мета описания и ключевиков<br><br>
		<p>".select("par18", "0,1", "НЕТ,ДА", $PCon[8])."Копировать ключевики<br><br>
		<p>".select("par19", "0,1", "НЕТ,ДА", $PCon[9])."Копировать мета описание<br><br>
		<p>".select("par7", "0,1", "НЕТ,ДА", $PCon[5])."Ключевики -> в мета облако<br><br>
		<p>Предложений, отправляемых в предисловие: ".select("par9", "1,2,3,4,5", "1,2,3,4,5", $PCon[6], "style='width:60px'")."

		</td><td>
		<h1>Установка ссылок</h1>
		<table class='table_light'><tr><td>
		Найти фразу</td><td>и поставить ссылку</td></tr></table>
		<textarea id='par10' style='width: 100%; height: 220px;'>Наша фраза|http://site.ru</textarea>
		<input type='submit' onClick='parser(6)' value='Добавить'>
		<div id='parlisturl'><table class='table_light'><tr><td></td><td>Фраза</td><td>Ссылка</td><td>Сколько поставили</td><td></td></tr>";
		$res2 = $db->sql_query("SELECT * FROM ".$prefix."_parser  where `class`='zamena'");
		$i = 1;
		while ($row = $db->sql_fetchrow($res2)) {
			echo "<tr><td>".$i."</td><td>".$row['title']."</td><td>".$row['url']."</td><td>".$row['config']."</td>
			<td><a title='удалить' class='button red white punkt' onClick='pardellink(".$row['id'].",1)'>Удалить</a></td></tr>";
			$i++;
		}
	echo "</table></div></td></tr></table>
	</div>

	<div id='pardiv4' style='display: none;'><p style='color: red'>НЕ РАБОТАЕТ, будет после сканера сайтов</p></div>

	<div id='parres'></div>";
}

// input[name=myname] - селектор для name
function parser_js() {
	global $yandex_user, $yandex_key;
	// function parser
	// 1 - добавляем url-ы
	// 2 - сканируем сайт
	// 3 - ищем через яшу
	// 4 - настройка
	// 5 - Удаление временных записей
	// 6 - добавление ссылки
	// 7 - Добавление RSS канала
	// 8 - Работа с промежуточными записями  - непосредственно их добавление на сайт
	// 9 - проверка RSS каналов
	// pardellink - удаление ссылки
	echo "<script>
	function parser_site(id){
		xps=new XMLHttpRequest();
		xps.onreadystatechange=function() {
			if (xps.readyState==4 && xps.status==200) {
				var otvet = xps.responseText;
				if ( parseInt(otvet,10) !== 2 && otvet != '') { parser(id); $('#parres').prepend(otvet); }
				if ( parseInt(otvet,10) == 2) { $('#parres').prepend('<h1 style=\"color: green\">Готово!!!</h1>'); }
			}
		}
		xps.open('POST','parser.php',true); 
		xps.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		xps.send('action='+id);
	}
	function pardellink(id,metod){
		xps=new XMLHttpRequest();
		xps.onreadystatechange=function() {
		    if (xps.readyState==4 && xps.status==200)
			if (metod == 1 ) { document.getElementById('parlisturl').innerHTML = xps.responseText; }
			if (metod == 2 ) { document.getElementById('rsslist').innerHTML = xps.responseText; }
		}
		xps.open('POST','parser.php',true); 
		xps.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		if (metod == 1 ) { xps.send('action=7&data1='+id); } 
		if (metod == 2 ) { xps.send('action=11&data1='+id); } 
	}
	function parser(action){
		if ( action == 1) { var data1 =document.getElementById('par1').value; }
		if ( action == 2) { var data1 =document.getElementById('par2').value; }
		if ( action == 3) { var data1 =document.getElementById('par3').value; }
		if ( action == 4) { var configpar = ".$yandex_user."+']['+".$yandex_key."+']['+parseInt($('select#par12').val(),10)+']['+parseInt($('select[name=par13]').val(),10)+']['+parseInt($('select[name=par6]').val(),10)+']['+parseInt($('select[name=par7]').val(),10)+']['+parseInt($('select[name=par9]').val(),10)+']['+parseInt($('select#par17').val(),10)+']['+parseInt($('select[name=par18]').val(),10)+']['+parseInt($('select[name=par19]').val(),10)+']['+parseInt($('select#par110').val(),10); }
		if ( action == 6) { var data1 =document.getElementById('par10').value;  }
		if ( action == 7) { var data1 =document.getElementById('par21').value;  }
		xps = new XMLHttpRequest();
		xps.onreadystatechange=function() {
			if (xps.readyState==4 && xps.status==200) {
				var otvet = xps.responseText;
				if ( action == 1) { parser_site(8); $('#parres').prepend(otvet); }
				if ( action == 2) { $('#parres').prepend(otvet); }
				if ( action == 3) { parser_site(8); $('#parres').prepend(otvet);	}
				if ( action == 4) {  $('#parres').prepend(otvet); }
				if ( action == 5) {  $('#parres').prepend(otvet); }
				if ( action == 6) {  document.getElementById('parlisturl').innerHTML = otvet; }
				if ( action == 7 && parseInt(otvet,10) !== 2) { document.getElementById('rsslist').innerHTML = otvet; parser_site(8); }
				if ( action == 7 && parseInt(otvet,10) == 2) { $('#parres').prepend('<h1 style=\"color: red\">Новых записей нет!!! или неудача</h1>'); }
				if ( action == 8 && parseInt(otvet,10) !== 2) { parser_site(8); $('#parres').prepend(otvet); }
				if ( action == 8 && parseInt(otvet,10) == 2) { $('#parres').prepend('<h1 style=\"color: green\">Готово!!!</h1>'); }
				if ( action == 9 && parseInt(otvet,10) !== 2) { parser_site(8); $('#parres').prepend(otvet); }
				if ( action == 9 && parseInt(otvet,10) == 2) { $('#parres').prepend('<h1 style=\"color: red\">Новых записей нет!!!</h1>'); }
			}
		}
		xps.open('POST','parser.php',true); 
		xps.setRequestHeader('Content-type','application/x-www-form-urlencoded');
		if ( action == 1) { xps.send('action=1&data1='+data1); }
		if ( action == 2) { xps.send('action=2&data1='+data1);}
		if ( action == 3) { xps.send('action=3&data1='+data1); }
		if ( action == 4) { xps.send('action=4&data1='+configpar); }
		if ( action == 5) { xps.send('action=5'); }
		if ( action == 6) { xps.send('action=6&data1='+data1); }
		if ( action == 7) { xps.send('action=9&data1='+data1); }
		if ( action == 8) { xps.send('action=8'); }
		if ( action == 9) { xps.send('action=10'); }
	}
	function showpars(id){
		$('#pardiv1').hide(); $('#pardiv2').hide(); $('#pardiv3').hide(); $('#pardiv4').hide();
		setTimeout( function() { $('#pardiv'+id).show() } , 50);
	}
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
	)  DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;");
	// 0 - Логин на Яндексе
	// 1 - Ключ АПИ Яндекса
	// 2 - Раздел для сохранения
	// 3 - Включение статей
	// 4 - Авто key и desc
	// 5 - Ключи в облако
	// 6 - Предложений в предисловии
	// 7 - Глубина поиска через Яндекс
	// 8 - Если есть ключи берем их
	// 9 - Тоже с описанием
	// 10 - метод поиска
	$config = 'Логин][Key][0}{Укажите][0][1][1][3][100][1][0][1';
	$db->sql_query("INSERT INTO ".$prefix."_parser (`id`, `class`, `url`, `title`, `text`, `config`) VALUES ('','config','','','','".$config."')") or die ('Ошибка: Не удалось сохранить настройку.');
	Header("Location: sys.php?op=parser_main");
}

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
	}
}
?>
