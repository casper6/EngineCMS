<?php
if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
$aid = trim($aid);
global $prefix, $db, $red;
$sql = "select realadmin from ".$prefix."_authors where aid='$aid'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$realadmin = $row['realadmin'];
if ($realadmin==1) {

	function txt_and_csv_main() {
		global $prefix, $db;
		include("ad/ad-header.php");
		echo "<div class='light_fon'><div class='black_grad'>
		<a class='button orange right3' onclick='$(\"#hidden\").toggle();'>?</a>
		<span class='h1'>Импорт содержимого из txt и csv файлов</span></div>
		<div id='hidden' class='hide'><p>Вы можете добавить ваш файл для импорта содержимого файла в разделы сайта.<br>
		Также можно добавить фотографии, ссылки на которые используются в тексте.<br>
		— ссылка для ваших изображений будет иметь следующий вид img/images.jpg (jpg, jpeg, png, gif)<br>
		для прочих файлов files/file.doc.<br>
		Используйте данное правило при написании своих текстов.<br>
		— если папка не указанна в строке файла импорта то содержимо будет импортированно в корневой раздел раздела сайта<br>
		если папка уазана но её нет в разделе сайта то она будет добавлена автоматически.<br>
		— не используйте точку с запятой в своих текстах - по умолчанию данный знак является разделителем полей, но вы всегда сможете при импорте указать свой знак разделителя полей.<br>
		— не используйте в названиях файлов и изображений пробелов и русских символов.<br>
		— файлы txt, csv не должены превышать 50 МБ, но их может быть несколько.<br>
		<p>Пример строки из файла импорта:<br>
		№ папки;Название страницы;Краткое содержание материала;полное содержание;100;красный;price1.doc;images1.png<br></div>
		<h2>Добавить файл импорта txt или csv (макс. 50Мб).</h2>
        <form action='sys.php?op=upload_txt_and_csv' method='post' enctype='multipart/form-data'>
		<input type='file' name='file'>
		<input type='submit' name='submit' value='Добавить'></form>";
		$result = $db->sql_query("SELECT * FROM ".$prefix."_txt_and_csv") or die('<h2 class="center">База импорта не установлена. <a href="sys.php?op=txt_and_csv_install">Установить</a>.</h2>');
		$numrows = $db->sql_numrows($result);
		if ($numrows > 0) {
			echo "<h2>Импортируем файл...</h2>
			<table class='w100 table_light'>";
			while ($row = $db->sql_fetchrow($result)) {
			    // Проверим наличие файлов
			    if (file_exists("files/".$row['file_id'])) {
					echo '<tr id="id_'.$row['id'].'"><td class="vert_center"><b>'.$row['file'].'</b>';
					//echo "</td><td>Добавить изображения (в разработке)";
					echo '</td><td class="vert_center">
					<form action="sys.php?op=start_txt_and_csv&amp;id='.$row['id'].'"" method="post">
					<nobr>в раздел</nobr> <select name="modul">';
					$resl = $db->sql_query("select `id`, `title` from ".$prefix."_mainpage where `type`='2' and `id`!='24' and `tables`='pages'");
					while ($row2 = $db->sql_fetchrow($resl))
						echo "<option value=\"".$row2['id']."\">".$row2['title']."</option>";
					echo '</select>, </td><td class="vert_center">
					символ <input type="text" name="znak" size="2" value=";"> <nobr>разделяет поля</nobr></td><td class="vert_center">
					<input type="submit" name="submit" value="Начать импорт" class="small"></form></td><td class="vert_center">
					<a onclick="del_csv(\''.$row['id'].'\')" class="pointer" title="Удалить файл"><span class="icon red small" data-icon="F"></span></a></td></tr>';
				} else {
					// Если файла нет - удаляем запись о нем
					$db->sql_query("DELETE from ".$prefix."_txt_and_csv WHERE `id`='".$row['id']."'");
				}
			}
			echo "</table>";
		}
		echo "</div>";
	}

	function txt_and_csv_install() {
		global $prefix, $db;
		$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_txt_and_csv`;");
		$db->sql_query("CREATE TABLE `".$prefix."_txt_and_csv` (
		`id` int(10) NOT NULL AUTO_INCREMENT,
		`file` text,
		`file_id` varchar(255) NOT NULL,
		PRIMARY KEY (`id`)
		) DEFAULT CHARSET=utf8;");
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
					//if ($_FILES["file"]['type'] == "text/plain" || $_FILES["file"]['type'] == "text/comma-separated-values" ) {
						$files = $_FILES['file']['name'];
						$filename = date('d-m-Y_H-i-s').'_'.translit($_FILES['file']['name']);
						move_uploaded_file($_FILES["file"]["tmp_name"], "files/".$filename);
						$db->sql_query("INSERT INTO ".$prefix."_txt_and_csv (id, file, file_id) VALUES ('', '$files', '$filename')");
						Header("Location: sys.php?op=txt_and_csv_main");
					//} else echo "<p>Можно загружать только txt csv файлы.".$_FILES["file"]['type'];
				} elseif ($_FILES['file']['error'] == "4") echo "<p>Вы не выбрали файл. Вернитесь назад и выберите.</p>";
				else echo "<p>Ошибка загрузки файла. ".$_FILES['file']['error']."</p>";
			}
		}
		/*
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
		*/
	}

	function start_txt_and_csv() {
		global $prefix, $db;
		$id= $_GET["id"];
		$znak = $_POST["znak"];
		$cat = $_POST["modul"];
		include("ad/ad-header.php");
		//echo tools_menu().'<br>';
		$result = $db->sql_query("SELECT `file_id` FROM ".$prefix."_txt_and_csv where `id` = '".$id."'") or die('<center>Ошибка</center>');
		$row = $db->sql_fetchrow($result);
		$arr = file("files/".$row['file_id']);
		echo "<div class='light_fon'><div class='black_grad'>
		<span class='h1'>Выберите поля для импорта данных</span></div>";
		for ($i = 0; $i < 1; $i++) {
		    $a = iconv("cp1251", "UTF-8",$arr[$i]);
			$a = str_replace("&quot", "", $a);
			$a = str_replace("&amp", "", $a);
			$a = explode($znak, $a);
			$count = count($a);
			echo '<form action="sys.php?op=step2_txt_and_csv&id='.$id.'&znak='.$znak.'&cat='.$cat.'" method="post">
			<table class="table_light w100"><tr><td width="50%" class="right">Данные...</td><td width="2%" class="center"></td><td>... добавляем в поле</td></tr>';
			for ($y = 0; $y < $count; $y++) {
				echo '<tr><td class="right">'.mb_substr($a[$y],0,100).'</td><td> >> </td><td>';
				echo '<select name="stroka['.$y.']" class="w100">
				<option value="---">- не добавляем -</option>
				<option value="7">Номер существующей папки</option>
				<option value="0">Название папки (если нет - будет создана)</option>
				<option value="1">Название страницы (заголовок) — обязательно!</option>
				<option value="2">Предисловие (начальный текст)</option>
				<option value="3">Содержание (основной текст)</option>
				<option value="4">Ключевые слова для поисковых машин (через запятую)</option>
				<option value="5">Описание для поисковых машин</option>
				<option value="6">Тэги (ключевые слова для поиска страниц схожей тематики)</option>';
				// подключаем списки для выбора
				$res = $db->sql_query("SELECT `name`, `title` FROM ".$prefix."_mainpage where (`useit` = '".$cat."' or `useit` = '0') and `type`='4'") or die('Ошибка подключения полей');
				while ($row = $db->sql_fetchrow($res)) {
					//$type = $row['text'];
					$pole = $row['name'];
					//if ($type{12} == 1 || $type{12} == 4 || $type{12} == 5 ) 
					echo '<option value="'.$row['name'].'">'.$row['title'].' ('.$row['name'].')</option>'; // Нужные нам поля, текст и строка
				}
			}
			echo '</td></tr></table><p class="center"><input type="submit" name="submit" value="Завершить импорт"></form>';
		}
	}

	function step2_txt_and_csv() {
		global $prefix, $db, $now;
		$id = $_GET["id"];
		$znak = $_GET["znak"];
		$cat = $_GET["cat"];
		$stroka = $_POST["stroka"];
		include("ad/ad-header.php");
		//echo tools_menu().'<br>';
		$active = '0'; // все страницы будут выключены
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
		//$count = count($stroka);
		//$result = array_unique($stroka);
		//$count2 = count($result);
		//if ($count2 !== $count) echo '<b>Ошибка:</b>'.$count2.' !== '.$count.' выбрано несколько значений данных для одного поля<br>или в тексте таблицы есть символы-разделители.';
		//else {
			$revers = $revers_spiski = array_flip ($stroka); // Меняем местами ключи и значения массива

			// в массиве $revers_spiski остаются только названия списков
			unset($revers_spiski["---"]);
			unset($revers_spiski["0"]);
			unset($revers_spiski["1"]);
			unset($revers_spiski["2"]);
			unset($revers_spiski["3"]);
			unset($revers_spiski["4"]);
			unset($revers_spiski["5"]);
			unset($revers_spiski["6"]);
			unset($revers_spiski["7"]);

			$result2 = $db->sql_query("SELECT `file_id` FROM ".$prefix."_txt_and_csv where `id`='".$id."'") or die('Ошибка обращения к базе данных');
			$row = $db->sql_fetchrow($result2);
			$arr = file("files/".$row['file_id']);
			$count = count($arr);
			for ($i = 0; $i < $count; $i++) {
			    $a = iconv("cp1251", "UTF-8",$arr[$i]); // проверить
				$a = str_replace("&quot", "", $a);
				$a = str_replace("&amp", "", $a);
				$a = explode($znak, $a);
				if (array_key_exists("0", $revers)) { // Если выбрали поле папка $revers[0] Папка
					$y = $revers[0]; 
					$title_name = $a[$y]; // имя папки
					$result = $db->sql_query("SELECT `cid` FROM ".$prefix."_pages_categories where `title`='".$title_name."' and `module`='".$module."'");
					$counter = $db->sql_numrows($result);
					//while ($row2 = $db->sql_fetchrow($result)) {
					//$counter = $row2['counter'];
			        if ($counter > 0) { // если нашли папку - получаем ее id
						$row3 = $db->sql_fetchrow($result);
						$cid = $row3['cid'];        
                	} else { // если не нашли папку - создаем ее
						$db->sql_query("INSERT INTO ".$prefix."_pages_categories VALUES (NULL, '".$module."', '".$title_name."', '', '', '0', '0', '0', 'pages')");
						$row4 = $db->sql_fetchrow($db->sql_query("SELECT `cid` FROM ".$prefix."_pages_categories where `title`='".$title_name."' and `module`='".$module."'")); // и получаем ее id
						$cid = $row4['cid']; 
					}
					//}
				} else $cid = '0'; // Если поле папок не было заданно пишем данные в корень раздела

				if (array_key_exists("1", $revers)) { 
					$q = $revers[1]; 
					$title = trim($a[$q]); 
				} else $title = ''; // $revers[1] Название страницы (заголовок)
				if (array_key_exists("2", $revers)) { 
					$w = $revers[2]; 
					$open_text = trim($a[$w]); 
				} else $open_text = ''; // $revers[2] Предисловие (начальный текст)
				if (array_key_exists("3", $revers)) { 
					$e = $revers[3]; 
					$main_text = trim($a[$e]); 
				} else $main_text = ''; // $revers[3] Содержание (основной текст)
				if (array_key_exists("4", $revers)) { 
					$r = $revers[4]; 
					$keywords2 = trim($a[$r]); 
				} else $keywords2 = ''; // $revers[4] Ключевые слова(мета-тег)
				if (array_key_exists("5", $revers)) { 
					$t = $revers[5]; 
					$description2 = trim($a[$t]); 
				} else $description2 = ''; // $revers[5] Описание (мета-тег)
				if (array_key_exists("6", $revers)) { 
					$u = $revers[6]; 
					$search = trim($a[$u]); 
				} else $search = ''; // $revers[6] Ключевые слова (облако тегов)
				if (array_key_exists("7", $revers)) { 
					$s = $revers[7]; 
					$cid = $a[$s]; 
					if (trim($cid)=='') continue; 
					else $cid = intval($cid); 
				} else $cid = '0'; // № папки
				
				// НАЧИНАЕМ SEO-ОПТИМИЗАЦИЮ
				/*
				$texttags = $open_text." ".$main_text;
				if ($keywords2 == "") $keywords2 = newkey($texttags);
				if ($description2 == "") { 
					$description2 = newdesc($texttags,$keywords2); 
					$open_text = razmetka($open_text, $keywords2);
					$main_text = razmetka($main_text, $keywords2);
				}
				*/
				// SEO-ОПТИМИЗАЦИЯ ЗАКОНЧЕНА

				if (trim($title) != "") {
					
					$db->sql_query("INSERT INTO ".$prefix."_pages VALUES (NULL, '".$module."', '".$cid."', '".$title."', '".$open_text."', '".$main_text."', '".$now."', '".$now."', '0', '".$active."', '0', '0', '', '".$search."', '0', '".$rss."', '0.00', '".$description2."', '".$keywords2."', 'pages', '0','0','0');") or die ("Не удалось сохранить страницу - сообщите разработчику следующее: ".$sql);
					
					if (count($revers_spiski) > 0) { // если есть поля для списков
						//print_r($revers_spiski);
						
						// Узнаем получившийся номер страницы ID
						$row5 = $db->sql_fetchrow($db->sql_query("select `pid` from ".$prefix."_pages where `title`='".$title."' and `date`='".$now."'"));
						$page_id = $row5['pid'];

						$res = $db->sql_query("SELECT `name`, `text` FROM ".$prefix."_mainpage where (`useit` = '".$cat."' or `useit` = '0') and `type`='4' and `tables` = 'pages'") or die('Ошибка подключения списков');
						while ($row = $db->sql_fetchrow($res)) {

						    // Получение информации о каждом списке
						    //$sql = "select * from ".$prefix."_mainpage where `name`='".$name."' and `type`='4'";
						    //$result = $db->sql_query($sql);
						    //$row = $db->sql_fetchrow($result);
						    $name = $row['name'];
						    if (array_key_exists($name, $revers)) { 
								$elements = $revers[$name]; 
								$elements = trim($a[$elements]);
							    $options = explode("|", $row['text']); 
							    $options = $options[1];
							    $type = 0; 
							    $shablon = ""; 
							    parse_str($options); // раскладка всех настроек списка
							    switch($type) {

							      case "6": // регион
							        del_spiski($page_id, $name); // Стираем упоминания о списках для переназначения
							        $sql = "SELECT `id`, `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='$name' and `pages` like '% ".$page_id." %'";
							        $result = $db->sql_query($sql);
							        $row = $db->sql_fetchrow($result);
							        $nums = $db->sql_numrows($result);
							        $del_id = $row['id'];
							        $del_name = $row['name'];
							        $del_pages = $row['pages'];
							        if ($nums==0 or ($elements != $del_name and $del_name!="")) {
							          $id_regions = $_POST['id_regions'];
							          if (!isset($id_regions)) $id_regions = $elements;
							           $region = $db->sql_fetchrow($db->sql_query("select `name` from ".$prefix."_regions where `id`='".$id_regions."'"));
							          $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$region['name']."', '', '0', ' ".$page_id." ', '0');") or die ('Ошибка: Не удалось сохранить список. 6'); 
							        }
							      break;

							      case "1": // текст
							      case "4": // строка
							      case "5": // число
							        if ($type == "5") $elements = filter_var($elements, FILTER_SANITIZE_NUMBER_FLOAT);
							        // Проверяем наличие подобного элемента
							        $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and `pages` like '% ".$page_id." %'";
							        $result = $db->sql_query($sql);
							        $numrows = $db->sql_numrows($result);
							        if ($numrows > 0) { // если элемент найден
							          $row = $db->sql_fetchrow($result);
							          $s_pages = $row['pages'];
							          $s_name = $row['name'];
							          if ($elements != $s_name) { // Если значение изменилось
							            if ($s_pages == " ".$page_id." ") {// Удалим весь список, если других страниц в нем нет
							              $db->sql_query("DELETE FROM ".$prefix."_spiski WHERE `type`='".$name."' and `name`='".$s_name."'");
							            } else { // Или удалим лишь номер страницы из списка, если в нем есть другие страницы
							              $s_pages = str_replace(" ".$page_id." "," ", $s_pages);
							              $db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".$s_pages."' WHERE `type`='".$name."' and `name`='".$s_name."'") or die ('Ошибка: Не удалось обновить список. 1');
							            }
							            // Если name != 0 - создадим новый список или добавим в существующий
							            if ($elements != "") {
							              // Проверим, нет ли такого списка
							              $sql = "SELECT `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and `name`='".$elements."'";
							              $result = $db->sql_query($sql);
							              $numrows = $db->sql_numrows($result);
							              if ($numrows > 0) { // Список есть - добавляем к нему
							                $row = $db->sql_fetchrow($result);
							                $s_pages2 = $row['pages'];
							                $s_pages2 = str_replace("  "," ", $s_pages2." ".$page_id." ");
							                $db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".$s_pages2."' WHERE `type`='".$name."' and `name`='".$elements."'") or die ('Ошибка: Не удалось обновить список. 1');
							              } else { // Списка нет - создаем новый
							                $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', ' ".$page_id." ', '0');") or die ('Ошибка: Не удалось сохранить список. 2');
							              }
							            }
							          } // если значение не изменилось - ничего не трогаем
							        } elseif ($elements != "") { // Если элемент не найден - проверим, нет ли такого списка
							          $sql = "SELECT `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and `name`='".$elements."'";
							          $result = $db->sql_query($sql);
							          $numrows = $db->sql_numrows($result);
							          $row = $db->sql_fetchrow($result);
							          if ($numrows > 0) { // Список есть - добавляем к нему
							            $s_pages2 = $row['pages'];
							            $s_pages2 = str_replace("  "," ", $s_pages2." ".$page_id." ");
							            $db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".$s_pages2."' WHERE `type`='".$name."' and `name`='".$elements."'") or die ('Ошибка: Не удалось обновить список. 1');
							          } else { // Списка нет - создаем новый
							            $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', ' ".$page_id." ', '0');") or die ('Ошибка: Не удалось сохранить список. 2');
							          }
							        }
							      break;

							      case "3": // период времени
							        del_spiski($page_id, $name); // Стираем упоминания о списках для переназначения
							        // создаем диапазон дат и все их проверяем
							        $elements = explode("|",$elements);
							        $dat1 = date2normal_view($elements[0], 1);
							        $dat2 = date2normal_view($elements[1], 1);
							        $period = period($dat1, $dat2);
							        // и все даты проверяем на наличие в БД
							        $upd = array();
							        $noupd = array();
							        $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' order by `name`";
							        $result = $db->sql_query($sql);
							        while ($row = $db->sql_fetchrow($result)) {
							          $nam = $row['name']; // дата
							          $pag = trim($row['pages']); // страницы
							          if (in_array($nam, $period)!=FALSE) { 
							            $noupd[] = $nam; // для INSERT
							            if (strstr($pag,$page_id)==FALSE) $upd[] = $nam; // для UPDATE
							          }
							        }
							        $insert = array();
							        $update = array();
							        foreach ($upd as $up) {
							          $update[] = "`name`='".$up."'";
							        }
							        foreach ($period as $per) {
							          if (!in_array($per, $noupd)) $insert[] = "(NULL, '".$name."', '".$per."', '', '0', ' ".$page_id." ', '0')";
							        }
							        $insert = implode(", ",$insert);
							        $update = implode(" or ",$update);
							        $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and (".$update.") order by `name`";
							        $result = $db->sql_query($sql);
							        while ($row = $db->sql_fetchrow($result)) {
							          $na = $row['name']; // дата
							          $pa = $row['pages']; // страницы
							          $db->sql_query("UPDATE ".$prefix."_spiski SET `pages` = ' ".$pa." ".$page_id." ' WHERE `type`='".$name."' and `name`='".$na."'") or die ("Ошибка: Не удалось обновить списки. $page_id $name");
							        }
							        if (trim($insert) != "") {
							          $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES ".$insert.";") or die ('Ошибка: Не удалось сохранить списки.');
							        }
							      break;

							      case "2": // файл
							      // Неокончено !!!
							      break;

							      case "7": // список (несколько значений)
							        $num = count($elements); // сколько элементов в списке
							        del_spiski($page_id, $name); // Стираем упоминания о списках для переназначения
							        for ($x=0; $x < $num; $x++) { // посчитали сколько номеров списка
							          if ($elements[$x] != 0) { // Если это не "Не выбрано"
							            // узнаем какие страницы уже есть у этого номера из списка
							            $sql = "SELECT `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and `id`='".$elements[$x]."'";
							            $result = $db->sql_query($sql);
							            $row = $db->sql_fetchrow($result);
							              $save_pages = str_replace("  "," ", $row['pages']." ".$page_id." ");
							              // теперь присвоем каждому из элементов списка id страницы, которую редактируем.
							              $db->sql_query("UPDATE ".$prefix."_spiski SET `pages` =  '".$save_pages."' WHERE `type`='".$name."' and  `id` = '".$elements[$x]."' LIMIT 1 ;") or die('Ошибка при добавлении страницы в элемент списка');
							          }
							        } 
							      break;
							      
							      case "0": // список (одно значение)
							        del_spiski($page_id, $name); // Стираем упоминания о списках для переназначения
							        if ($elements != 0) { // Если это не "Не выбрано"
							          // узнаем какие страницы уже есть у этого номера из списка
							          $sql = "SELECT `pages` FROM ".$prefix."_spiski WHERE `id`='".$elements."'";
							          $result = $db->sql_query($sql);
							          $row = $db->sql_fetchrow($result);
							          $save_pages = str_replace("  "," ", $row['pages']." ".$page_id." ");
							          // теперь присвоем каждому из элементов списка id страницы, которую редактируем.
							          $db->sql_query("UPDATE `".$prefix."_spiski` SET `pages` =  '".$save_pages."' WHERE `id` =".$elements." LIMIT 1 ;") or die('Ошибка при добавлении страницы в элемент списка');
							        }
							      break;
							    }
  							}

							/*
							$type = $row6['text']; 
							$pole = $row6['name'];
							if (array_key_exists($pole, $revers)) { 
								$o = $revers[$pole]; 
								$elements = $a[$o];
								if ($type{12} == 5) // цифра
									$elements = filter_var($elements, FILTER_SANITIZE_NUMBER_FLOAT);
								// Проверим, нет ли такого списка
								$result = $db->sql_query("SELECT `pages` FROM ".$prefix."_spiski WHERE `type`='".$pole."' and `name`='".$elements."'");
								$numrows = $db->sql_numrows($result);
								if ($numrows > 0) { // Список есть - добавляем к нему
									$row = $db->sql_fetchrow($result);
									$s_pages2 = $row['pages'];
									$s_pages2 = str_replace("  "," ", $s_pages2." ".$page_id." ");
									$db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".$s_pages2."' WHERE `type`='".$pole."' and `name`=' ".$elements." '") or die ('Ошибка: Не удалось обновить список.');
								} else { // Списка нет - создаем новый
									if (trim($elements) != "" && trim($pole) != "" && $page_id != 0) $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$pole."', ' ".$elements." ', '', '0', ' ".$page_id." ', '0');") or die ('Ошибка: Не удалось сохранить список.');
								}
							} */
						} // end while
					}
				}
			} echo "<div class='light_fon'><div class='black_grad'><h1>Импорт данных завершен</h1></div>";
			/*
			$result3 = $db->sql_query("SELECT `id` FROM ".$prefix."_txt_and_csv where `file_id` = '".$id."'");
			$counter = $db->sql_numrows($result2);
	        if ($counter > 0) echo '<a href="sys.php?op=txt_and_csv_zip&id='.$id.'">Импортировать архив</a><br>'; // если eсть архивы
			else 
			*/
			echo 'Вернуться <a href="sys.php?op=txt_and_csv_main">на начальную страницу импорта</a> или <a href="sys.php">в Содержание</a>.<br>'; // если нет архивов
		//}
	}
	/*
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
	*/
	switch ($op) {
		case "txt_and_csv_main":
			txt_and_csv_main(); 
			break;
		case "txt_and_csv_install":
			txt_and_csv_install(); 
			break;
		case "start_txt_and_csv":
			start_txt_and_csv(); 
			break;
		case "upload_txt_and_csv":
			upload_txt_and_csv(); 
			break;
		case "step2_txt_and_csv":
			step2_txt_and_csv(); 
			break;
		//case "txt_and_csv_zip":
			//txt_and_csv_zip(); break;
	}
}
?>