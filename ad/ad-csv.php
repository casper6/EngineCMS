<?php
if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
$aid = trim($aid);
global $prefix, $db, $red;
$sql = "select realadmin from ".$prefix."_authors where aid='$aid'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$realadmin = $row['realadmin'];
if ($realadmin==1) {
######################################################################################################

function txt_and_csv_main() {
	global $prefix, $db;
	include("ad/ad-header.php");
	$result = $db->sql_query("SELECT * FROM ".$prefix."_txt_and_csv where file_id = '0'") or die('<center>База импорта не установлена. <a href="sys.php?op=txt_and_csv_install">Установить</a>.</center>');
	echo "<script>
	$(document).ready(function(){ 
		$('.hider').click(function(){ 
			$('#hidden').slideToggle('slow'); return false; 
		}); 
	});
	</script>
	<div style='background: #e2e5ea;'><div class='black_grad' style='height:45px;'>
	<span class='h1'>Импорт содержимого из txt и csv файлов</span></div>
	<p class='hider'>Справка</p>
	<div id='hidden' style='display: none;'>ВЫ можете добавить ваш файл для импорта содержимого файла в разделы сайта.<br>
	Также ВЫ можете добавить zip архивы с вашими файлами и изображениями ссылки на которые используются в тексте.<br>
	<span class='red'>ПОМНИТЕ</span> - ссылка для ваших изображений будет иметь следующий вид img/images.jpg (jpg, jpeg, png, gif)<br>
	для прочих файлов files/file.doc.<br>
	Используйте данное правило при написании своих текстов.<br>
	<span class='red'>ПОМНИТЕ</span> - если папка не указанна в строке файла импорта то содержимо будет импортированно в корневой раздел раздела сайта<br>
	если папка уазана но её нет в разделе сайта то она будет добавлена автоматически.<br>
	<span class='red'>ВАЖНО!!!</span> не используйте точку с запятой в своих текстах - по умолчанию данный знак является разделителем полей, но вы всегда сможете при импорте указать свой знак разделителя полей.<br>
	<span class='red'>ВАЖНО!!!</span> не используйте в названиях файлов и изображений пробелов и русских символов.<br>
	<span class='red'>ВАЖНО!!!</span> zip архивы и файлы txt, csv не должены превышать 50 МБ, но их может быть несколько.<br>
	<span class='red'>ВАЖНО!!!</span> zip архив не должен содержать в себе папок, просто файлы или изображения.<br>
	<h3>Пример строки из файла импорта</h3>
	<b>Наша папка;Краткое содержание материала;полное содержание;Цена 100руб.;Цвет красный;price1.doc,price2.doc;katalog.pdf;images1.png,images2.phg,images3.png</b><br></div>
	<center>Добавить файл импорта txt или csv (макс. 50Мб).
	        <form action='sys.php?op=upload_txt_and_csv' method='post' enctype='multipart/form-data'>
			<input type='file' name='file'>
			<input type='submit' name='submit' value='Добавить'></form><hr>
			<br>Добавленные файлы для импорта</center><hr>
	<table width='100%'>";
	while ($row = $db->sql_fetchrow($result)) {
	    $id = $row['id'];
		echo '<tr><td>';
		echo $row['file'];
		echo "</td><td>Добавить zip архив (макс. 50МБ).
	        <form action='sys.php?op=upload_txt_and_csv&id=".$id."' method='post' enctype='multipart/form-data'>
			<br><input type='file' name='zip'>
			<br><input type='submit' name='submit' value='Добавить архив'></form>
			<br>Добавленные архивы<br>";
		$result2 = $db->sql_query("SELECT * FROM ".$prefix."_txt_and_csv where file_id = '$id'") or die('Ошибка поиска архивов');
		while ($row2 = $db->sql_fetchrow($result2)) {
			echo $row2['file'].'<br>';
		}
		echo '</td><td>Выберите раздел в который импортировать содержимое <form action="sys.php?op=start_txt_and_csv&amp;id='.$id.'"" method="post"><select name="modul">';
		$resl = $db->sql_query("select `id`, `title` from ".$prefix."_mainpage where `type`='2' and `id`!='24'");
		while ($row = $db->sql_fetchrow($resl)) {
			echo "<option value=\"".$row['id']."\">".$row['title']."</option>";
		}
		echo '</select><br>Укажите разделительный знак<br>
		<input type="text" name="znak" value=";"><br><input type="submit" name="submit" value="Начать импорт"></form></td></tr>';
	}
	echo "</table></div>";
}

function txt_and_csv_install() {
	global $prefix, $db;
	$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_txt_and_csv`;");
	$db->sql_query("CREATE TABLE `".$prefix."_txt_and_csv` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`file` text,
	`file_id` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=201898 ;");
	Header("Location: sys.php?op=txt_and_csv_main");
}

function upload_txt_and_csv() {
	global $prefix, $db;
	if ($_POST["submit"] == "Добавить") {
		if ($_FILES["file"]["size"] > 52428800) echo "<p>Размер файла превышает 50 Мб.</p>"; 
		else {
			// Проверяем загружен ли файл
			if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
				// проверяем расширение файла
				if ($_FILES["file"]['type'] == "text/plain" || $_FILES["file"]['type'] == "text/comma-separated-values" ) {
					$files = $_FILES['file']['name'];
					move_uploaded_file($_FILES["file"]["tmp_name"], "includes/txt_and_csv/".$files);
					$db->sql_query("INSERT INTO ".$prefix."_txt_and_csv (id, file, file_id) VALUES ('', '$files', '0')");
					Header("Location: sys.php?op=txt_and_csv_main");
				} else echo "<p>Можно загружать только txt csv файлы.".$_FILES["file"]['type'];
			} else echo "<p>Ошибка загрузки файла. ".$_FILES['file']['error']."</p>";
		}
	}
	if ($_POST["submit"] == "Добавить архив") {
		if ($_FILES["zip"]["size"] > 52428800) echo "<p>Размер файла превышает 50 Мб.</p>"; 
		else {
			// Проверяем загружен ли файл
			if (is_uploaded_file($_FILES["zip"]["tmp_name"])) {
				// проверяем расширение файла
				if ($_FILES["zip"]['type'] == "application/zip" ) {
					$id= $_GET["id"]; // К какому файлу импорта принадлежит
					$files = $_FILES['zip']['name'];
					move_uploaded_file($_FILES["zip"]["tmp_name"], "includes/txt_and_csv/".$files);
					$db->sql_query("INSERT INTO ".$prefix."_txt_and_csv (id, file, file_id) VALUES ('', '$files', '$id')");
					Header("Location: sys.php?op=txt_and_csv_main");
				} else echo "<p>Можно загружать только zip файлы.";
			} else echo "<p>Ошибка загрузки файла. ".$_FILES['file']['error']."</p>";
		}
	}
}

function start_txt_and_csv() {
	global $prefix, $db;
	$id= $_GET["id"];
	$znak = $_POST["znak"];
	$cat = $_POST["modul"];
	include("ad/ad-header.php");
	echo tools_menu().'<br>';
	$result = $db->sql_query("SELECT file FROM ".$prefix."_txt_and_csv where id = '$id'") or die('<center>Ошибка</center>');
	$row = $db->sql_fetchrow($result);
	$arr = file("includes/txt_and_csv/".$row['file']);
	echo "<div style='background: #e2e5ea;'><div class='black_grad' style='height:45px;'>
	<span class='h1'>Выберите необходимые поля для ваших данных</span></div>";
	for ($i = 0; $i < 1; $i++) {
	    $a = iconv("cp1251", "UTF-8",$arr[$i]);
		$a = str_replace("&quot", "", $a);
		$a = str_replace("&amp", "", $a);
		$a = explode($znak, $a);
		$count = count($a);
		echo '<form action="sys.php?op=step2_txt_and_csv&id='.$id.'&znak='.$znak.'&cat='.$cat.'" method="post"><table><tr><td><b>Данные из файла импорта</b></td><td><b>Поле в которое записываем</b></td></tr>';
		for ($y = 0; $y < $count; $y++) {
			echo '<tr>';
			if($y%2 == 0) echo '<td style="background-color:#9c5959;">'; else echo '<td>';
			echo utf8_substr($a[$y],0,100).'</td>';
			if($y%2 == 0) echo '<td style="background-color:#9c5959;">'; else echo '<td>';
			echo '<select name="stroka['.$y.']">
			<option value="0">Папка</option>
			<option value="1">Название страницы (заголовок)</option>
			<option value="2">Предисловие (начальный текст)</option>
			<option value="3">Содержание (основной текст)</option>
			<option value="4">Ключевые слова(мета-тег)</option>
			<option value="5">Описание (мета-тег)</option>
			<option value="6">Ключевые слова (облако тегов)</option>';
			// подключаем списки для выбора
			$res = $db->sql_query("SELECT name, text FROM ".$prefix."_mainpage where (`useit` = '$cat' or `useit` = '0') and `type`='4'") or die('Ошибка подключения полей');
			while ($row = $db->sql_fetchrow($res)) {
				$type = $row['text'];
				$pole = $row['name'];
				if ($type{12} == 1 || $type{12} == 4 || $type{12} == 5 ) echo '<option value="'.$pole.'">'.$pole.'</option>'; // Нужные нам поля, текст и строка
			}
		}
		echo '</td></tr></table><input type="submit" name="submit" value="Продолжить"></form>';
	}
}

function step2_txt_and_csv() {
	global $prefix, $db, $now;
	$id= $_GET["id"];
	$znak = $_GET["znak"];
	$cat = $_GET["cat"];
	$stroka = $_POST["stroka"];
	include("ad/ad-header.php");
	echo tools_menu().'<br>';
	$active = '1'; // все страницы будут включенны
	$rss = '1'; // доступно по RSS
	$res = $db->sql_query("SELECT `name` FROM ".$prefix."_mainpage where `tables`='pages' and `id`='".$cat."' ") or die('Ошибка');
	while ($row = $db->sql_fetchrow($res)) {
		$module = $row['name']; // Определили наш раздел
		if (strpos($module, "\n")) { // заменяем имя запароленного раздела
			$module = explode("\n", str_replace("\r", "", $module));
			$module = trim($module[0]);
		}
	}
	// Начинаем проверку, если одно поле установленно нескольким значениям
	$count = count($stroka);
	$result = array_unique($stroka);
	$count2 = count($result);
	if ($count2 !== $count) echo 'Ошибка - выбранно несколько значений данных для одного поля';
	else {
		$revers = array_flip ($stroka); // Меняем местами ключи и значения массива
		$result2 = $db->sql_query("SELECT `file` FROM ".$prefix."_txt_and_csv where `id`='".$id."'") or die('Ошибка');
		$row = $db->sql_fetchrow($result2);
		$arr = file("includes/txt_and_csv/".$row['file']);
		$count = count($arr);
		for ($i = 0; $i < $count; $i++) {
		    $a = iconv("cp1251", "UTF-8",$arr[$i]); // проверить
			$a = str_replace("&quot", "", $a);
			$a = str_replace("&amp", "", $a);
			$a = explode($znak, $a);
			if (array_key_exists("0", $revers)) { // Если выбрали поле папка $revers[0] Папка
				$y = $revers[0]; $title_name = $a[$y]; // имя папки
				$result = $db->sql_query("SELECT count(1) `counter` FROM ".$prefix."_pages_categories where `title`='".$title_name."' and `module`='".$module."'");
				while ($row2 = $db->sql_fetchrow($result)) {
					$counter = $row2['counter'];
			        if ( $counter > 0 ) { // если нашли папку то получаем ее id
						$result = $db->sql_query("SELECT `cid` FROM ".$prefix."_pages_categories where `title`='".$title_name."' and `module`='".$module."'");
						$row3 = $db->sql_fetchrow($result);
						$cid = $row3['cid'];        
                	} else { // если не нашли папку то создаем ее
						$db->sql_query("INSERT INTO ".$prefix."_pages_categories VALUES (NULL, '".$module."', '".$title_name."', '', '', '0', '0', '0', 'pages')");
						$result = $db->sql_query("SELECT `cid` FROM ".$prefix."_pages_categories where `title`='".$title_name."' and `module`='".$module."'"); // и получаем ее id
						$row4 = $db->sql_fetchrow($result);
						$cid = $row4['cid']; 
					}
				}
			} else $cid = '0'; // Если поле папок не было заданно пишем данные в корень раздела
			if (array_key_exists("1", $revers)) { $q = $revers[1]; $title = $a[$q]; } else $title = ''; // $revers[1] Название страницы (заголовок)
			if (array_key_exists("2", $revers)) { $w = $revers[2]; $open_text = $a[$w]; } else $open_text = ''; // $revers[2] Предисловие (начальный текст)
			if (array_key_exists("3", $revers)) { $e = $revers[3]; $main_text = $a[$e]; } else $main_text = ''; // $revers[3] Содержание (основной текст)
			if (array_key_exists("4", $revers)) { $r = $revers[4]; $keywords2 = $a[$r]; } else $keywords2 = ''; // $revers[4] Ключевые слова(мета-тег)
			if (array_key_exists("5", $revers)) { $t = $revers[5]; $description2 = $a[$t]; } else $description2 = ''; // $revers[5] Описание (мета-тег)
			if (array_key_exists("6", $revers)) { $u = $revers[6]; $search = $a[$u]; } else $search = ''; // $revers[6] Ключевые слова (облако тегов)
			
			// НАЧИНАЕМ SEO-ОПТИМИЗАЦИЮ
			$texttags = $open_text." ".$main_text;
			if ($keywords2 == "") $keywords2 = newkey($texttags);
			if ($description2 == "") { 
				$description2 = newdesc($texttags,$keywords2); 
				$open_text = razmetka($open_text, $keywords2);
				$main_text = razmetka($main_text, $keywords2);
			}
			// SEO-ОПТИМИЗАЦИЯ ЗАКОНЧЕНА

			$sql = "INSERT INTO ".$prefix."_pages VALUES (NULL, '".$module."', '".$cid."', '".$title."', '".$open_text."', '".$main_text."', '".$now."', '".$now."', '0', '".$active."', '0', '0', '', '".$search."', '0', '".$rss."', '0.00', '".$description2."', '".$keywords2."', 'pages', '0','0');";
			$db->sql_query($sql) or die ("Не удалось сохранить страницу. Попробуйте нажать в Редакторе на кнопку Чистка HTML в Редакторе. Если всё равно появится эта ошибка - сообщите разработчику нижеследующее:".$sql);
			// Узнаем получившийся номер страницы ID
			$sql = "select pid from ".$prefix."_pages where title='".$title."' and date='".$now."'";
			$result = $db->sql_query($sql);
			$row5 = $db->sql_fetchrow($result);
			$page_id = $row5['pid'];
			$res = $db->sql_query("SELECT name, text FROM ".$prefix."_mainpage where (`useit` = '$cat' or `useit` = '0') and `type`='4'") or die('Ошибка подключения полей');
			while ($row6 = $db->sql_fetchrow($res)) {
				$type = $row6['text']; $pole = $row6['name'];
				if ($type{12} == 1 || $type{12} == 4 ) { // Нужные нам поля, текст и строка
					if (array_key_exists($pole, $revers)) { $o = $revers[$pole]; $elements = $a[$o]; } else $elements = ''; // $revers[$pole] Список
					$db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$pole."', '".$elements."', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список.'); 
				}
			}
		} echo "<div style='background: #e2e5ea;'><div class='black_grad' style='height:45px;'><span class='h1'>Импорт данных завершен</span></div>";
		$result3 = $db->sql_query("SELECT count(1) 'counter' FROM ".$prefix."_txt_and_csv where file_id = '$id'");
		$row7 = $db->sql_fetchrow($result3);
		$counter = $row7['counter'];
        if ( $counter > 0 ) echo '<a href="sys.php?op=txt_and_csv_zip&id='.$id.'">Импортировать архив</a><br>'; // если eсть архивы
		else echo '<a href="sys.php?op=txt_and_csv_main">Вернуться на начальную страницу импорта</a>.<br>'; // если нет архивов
	}
}
 
function txt_and_csv_zip() { // Функция импорта архивов
	global $prefix, $db;
	$id= $_GET["id"];
	$zip = new ZipArchive;
	$result2 = $db->sql_query("SELECT * FROM ".$prefix."_txt_and_csv where file_id = '$id'") or die('Ошибка поиска архивов');
	while ($row2 = $db->sql_fetchrow($result2)) {
		if ($zip->open("includes/txt_and_csv/".$row2['file']) === true) {
			$zip->extractTo('includes/txt_and_csv/zip/'); // распаковываем архив
			$zip->close();
		}
	}
	$files = glob("includes/txt_and_csv/zip/*"); // находим все распакованные файлы
    $c = count($files);
    if (count($files) > 0) {
        foreach ($files as $file) {    
            if (file_exists($file)) {
				$file_extension = end(explode(".", $file)); // узнаем расширение файла
				if ($file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "png" || $file_extension == "gif" ) {
					if (!copy($file, 'img/'.basename($file))) echo "Не удалось скопировать ".$filenames."."; // если изображение
		  		} else {
		  			if (!copy($file, 'files/'.basename($file))) echo "Не удалось скопировать ".$filenames."."; // все другие файлы
				}
            	unlink($file); // скопировали и сразу удалили
            }
        }
    }
}
switch ($op) {
	case "txt_and_csv_main":
		txt_and_csv_main(); break;
	case "txt_and_csv_install":
		txt_and_csv_install(); break;
	case "start_txt_and_csv":
		start_txt_and_csv(); break;
	case "upload_txt_and_csv":
		upload_txt_and_csv(); break;
	case "step2_txt_and_csv":
		step2_txt_and_csv(); break;
	case "txt_and_csv_zip":
		txt_and_csv_zip(); break;
}}
?>