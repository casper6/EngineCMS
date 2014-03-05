<?php
require_once("mainfile.php");
global $prefix, $db, $admin, $now;
if (is_admin($admin)) {
	if (isset($_REQUEST['action'])) $action = $_REQUEST['action']; else die(); // Выбор события
	if (isset($_REQUEST['data1'])) $data1 = $_REQUEST['data1']; else die();
	#####################
	if ($action == 1) { // добавляем url-ы
		$urls = explode(PHP_EOL, $data1);	
		foreach($urls as $url)
			$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','1','".$url."','','','')") or die ('Ошибка: Не удалось сохранить список url.');
		echo "<h1 style='color: green'>Добавили ".count($urls)." URL-ов в промежуточные записи</h1>"; exit;
	}
	#####################
	if ($action == 2) { // сканируем сайт
		//	$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','2','".$data1."','','','')") or die ('Ошибка: Не удалось сохранить url.');
		echo "{'result_par':1}"; exit;
	}
	#####################
	if ($action == 3) { // ищем через яшу
		$res = $db->sql_fetchrow($db->sql_query("SELECT config FROM ".$prefix."_parser where `class`='config'"));
		$PCon = explode('][',$res['config']);
		// 0 - Логин на Яндексе
		// 1 - Ключ АПИ Яндекса
		// 7 - Глубина поиска через Яндекс
		// 10 - метод поиска
		global $geo, $prefix, $db;
		// для регионального добавить &lr='.$geo.'
		if ($PCon[10] == 1) $text = $data1; else $text = 'статьи && '.$data1;
		$page = get_cpage('http://xmlsearch.yandex.ru/xmlsearch?user='.$PCon[0].'&key='.$PCon[1].'&query='.urlencode(trim($text)).'&page=0&sortby=rlv&groupby=attr%3Dd.mode%3Ddeep.groups-on-page%3D'.$PCon[7]);
		// Массив с возможными вариантами ошибок. На примере 32го показано присвоение пояснений к ошибке
		$errors = array('1', '2', '15', '18', '19', '20', '31', '32' => 'Закончились лимиты', '33', '34', '37', '42', '43', '44', '48', '100');
		foreach ($errors as $key => $value)
			if (strpos($page, '<error code="'.$key.'">')) { echo 'Ошибка '.$key.': '.$value; exit; }
		$xml = simplexml_load_string($page);
		foreach ($xml->response->results->grouping->group as $urldata) {
			$name = $urldata->doc->url;
			$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','1','".$name."','','','')") or die ('Ошибка: Не удалось сохранить список url.');
		}
		echo "<h1 style='color: green'>Результаты поиска сохранили в промежуточные записи</h1>"; exit;
	}
	#####################
	if ($action == 4) { // настройка
		$PCon = explode('][',$data1);
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
		$result = $db->sql_fetchrow($db->sql_query("SELECT title FROM ".$prefix."_mainpage where id = '".$PCon[2]."'"));	
		$config = $PCon[0].']['.$PCon[1].']['.$PCon[2].'}{'.$result["title"].']['.$PCon[3].']['.$PCon[4].']['.$PCon[5].']['.$PCon[6].']['.$PCon[7].']['.$PCon[8].']['.$PCon[9].']['.$PCon[10];
		$db->sql_query("UPDATE ".$prefix."_parser SET config = '".$config."' WHERE class='config'") or die ('Ошибка: Не удалось сохранить настройку.');
		echo "<h1 style='color: green'>Сохранил</h1>"; exit;
	}
	if ($action == 5) { // Удаление промежуточных записей
		$db->sql_query("DELETE from ".$prefix."_parser where `class`='1'");
		echo "<h1 style='color: red'>Записи удалили!</h1>"; exit;
	}
	#####################
	if ($action == 6) { // добавление ссылки
		$urls = explode(PHP_EOL,$data1);
		foreach($urls as $url) {
			$link = explode('|',$url);
			$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','zamena','".$link[1]."','".$link[0]."','','0')") or die ('Ошибка: Не удалось сохранить ссылку.');
		}
		echo "<table class='table_light'><tr><td></td><td>Фраза</td><td>Ссылка</td><td>Раз поставили</td><td></td></tr>";
		$res = $db->sql_query("SELECT * FROM ".$prefix."_parser where `class`='zamena'");
		$i = 1;
		while ($row = $db->sql_fetchrow($res)) {
			echo "<tr><td>".$i."</td><td>".$row['title']."</td><td>".$row['url']."</td><td>".$row['config']."</td>
			<td><a title='удалить' class='button red white punkt' onClick='pardellink(".$row['id'].",1)'>Удалить</a></td></tr>";
			$i++;
		}
		echo "</table>"; 
		exit;
	}
	#####################
	if ($action == 7) { // удаление ссылки
		$db->sql_query("DELETE from ".$prefix."_parser where `id`='".$data1."'");
		echo "<table class='table_light'><tr><td></td><td>Фраза</td><td>Ссылка</td><td>Раз поставили</td><td></td></tr>";
		$res = $db->sql_query("SELECT * FROM ".$prefix."_parser where `class`='zamena'");
		$i = 1;
		while ($row = $db->sql_fetchrow($res)) {
			echo "<tr><td>".$i."</td><td>".$row['title']."</td><td>".$row['url']."</td><td>".$row['config']."</td>
			<td><a title='удалить' class='button red white punkt' onClick='pardellink(".$row['id'].",1)'>Удалить</a></td></tr>";
			$i++;
		}
		echo "</table>"; 
		exit;
	}
	#####################
	if ($action == 8) { // Грабим статьи
		$v = $db->sql_numrows($db->sql_query("SELECT url FROM ".$prefix."_parser where class='1'"));
		if ($v > 0) {
			$row = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_parser where `class`='1' limit 1"));
			$urls = $row['url']; 
			$db->sql_query("DELETE FROM ".$prefix."_parser WHERE id='".$row['id']."'");
			$str = $str2 = get_cpage($urls);
			$kod = get_codepage($str);
			if ($kod != "UTF-8") {
				$str = iconv($kod, 'UTF-8', $str);
				$str2 = iconv($kod, 'UTF-8', $str2);
			}
			echo dos_parser($str,$str2,$urls,$row['title'],$row['text']);
		} else echo "2"; 
		exit;
	}
	#####################
	if ($action == 9) { // Добавляем RSS
		$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','rss_chanel','".$data1."','','','')") or die ('Ошибка: Не удалось сохранить rss.');
		$doc  = new DOMDocument();
		$doc->load($data1);
		$items = $doc->getElementsByTagName("item");
		$i = 0;
		foreach($items as $item) {
			$tnl = $item->getElementsByTagName("title");
			$tnl = $tnl->item(0);
			$title = $tnl->firstChild->textContent;
			$tnl = $item->getElementsByTagName("link");
			$tnl = $tnl->item(0);
			$link = $tnl->firstChild->textContent;		
			$tnl = $item->getElementsByTagName("description");
			$tnl = $tnl->item(0);
			$description = $tnl->firstChild->textContent;
			$v = $db->sql_numrows($db->sql_query("SELECT `url` FROM ".$prefix."_parser where class='rss_link' and url ='".$link."'"));
			if ($v == 0) {
				$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','rss_link','".$link."','','','')") or die ('Ошибка: Не удалось сохранить rss2.');
				$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','1','".$link."','".$title."','".$description."','')") or die ('Ошибка: Не удалось сохранить rss2.');
				$i++;
			}
		}
		if ($i == 0) echo "2"; 
		else {
			$str = "<h1 style='color: green'>RSS добавили, новых записей ".$i."</h1>";
			$str .= "<table class='table_light'>";
			$res2 = $db->sql_query("SELECT * FROM ".$prefix."_parser where `class`='rss_chanel'");
			$i = 1;
			while ($row = $db->sql_fetchrow($res2)) {
				$str .= "<tr><td>".$i."</td><td>".$row['url']."</td>
				<td><a title='удалить' class='button red white punkt' onClick='pardellink(".$row['id'].",2)'>Удалить</a></td></tr>";
				$i++;
			}
			$str .= "</table>";
			echo $str;
		}
		exit;
	}
	#####################
	if ($action == 10) { // Проверяем RSS
		$res2 = $db->sql_query("SELECT * FROM ".$prefix."_parser  where `class`='rss_chanel'");
		$str = ''; $kol = 0;
		while ($row = $db->sql_fetchrow($res2)) {
			$doc  = new DOMDocument();
			$doc->load($row['url']);
			$items = $doc->getElementsByTagName("item");
			$i = 0;
			foreach($items as $item) {
				$tnl = $item->getElementsByTagName("title");
				$tnl = $tnl->item(0);
				$title = $tnl->firstChild->textContent;
				$tnl = $item->getElementsByTagName("link");
				$tnl = $tnl->item(0);
				$link = $tnl->firstChild->textContent;		
				$tnl = $item->getElementsByTagName("description");
				$tnl = $tnl->item(0);
				$description = $tnl->firstChild->textContent;
				$v = $db->sql_numrows($db->sql_query("SELECT `url` FROM ".$prefix."_parser where class='rss_link' and url ='".$link."'"));
				if ($v == 0) {
					$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','rss_link','".$link."','','','')") or die ('Ошибка: Не удалось сохранить rss2.');
					$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','1','".$link."','".$title."','".$description."','')") or die ('Ошибка: Не удалось сохранить rss2.');
					$i++;
				}
			}   
			$str .= "<h1 style='color: green'>RSS - ".$row['url'].", новых записей ".$i."</h1>";
			$kol += $i;
		}
		if ($kol ==0) echo "2"; else echo $str;
		exit;
	}
	if ($action == 9) { // Удаляем RSS
		$db->sql_query("DELETE from ".$prefix."_parser where `id`='".$data1."'");
		$str = "<table class='table_light'>";
		$res2 = $db->sql_query("SELECT * FROM ".$prefix."_parser where `class`='rss_chanel'");
		$i = 1;
		while ($row = $db->sql_fetchrow($res2)) {
			$str .= "<tr><td>".$i."</td><td>".$row['url']."</td>
			<td><a title='удалить' class='button red white punkt' onClick='pardellink(".$row['id'].",2)'>Удалить</a></td></tr>";
			$i++;
		}
		$str .= "</table>";
		echo $str;
		exit;
	}
}

function dos_parser($str,$str2,$urls,$titles,$texts) {
	global $prefix, $db, $admin, $now;
	$str = preg_replace( "'<script[^>]*>.*?</script>'usi", ' ', $str);
	$str = preg_replace( "'<style[^>]*>.*?</style>'usi", ' ', $str);
	$str = preg_replace("#(</?\w+)(?:\s(?:[^<>/]|/[^<>])*)?(/?>)#ui", '$1$2', $str); // удаляем атрибуты тегов
	$arr = explode("</div>", $str);
	$ar = array();
	foreach($arr as $str) {
		$str = preg_replace('/ {2,}/', ' ', $str); // убераем повторы пробелов
		$str = str_ireplace(array("\n","\r","\r\n","  ", "<p>&nbsp;</p>", "<p></p>"), "", $str);
		$str3 = preg_replace('#<a href=(?:.*)(?=</a>)#Usi', '', $str);
		$ar[strlen(trim(strip_tags($str3)))] = trim(strip_tags($str,'<p>, <h1>'));
	}
	krsort($ar);
	$content = current($ar); // наша статья
	if (strlen(trim($content)) > 1000) {
		$res = $db->sql_fetchrow($db->sql_query("SELECT config FROM ".$prefix."_parser where `class`='config'"));
		$PCon = explode('][',$res['config']);
		$PRazdel = explode('}{',$PCon[2]);
		// 2 - Раздел для сохранения
		// 3 - Включение статей
		// 4 - Авто key и desc
		// 5 - Ключи в облако
		// 6 - Предложений в предисловии
		// 8 - Если есть ключи берем их
		// 9 - Тоже с описанием
		require_once("includes/seo.php");
		if  (!empty($titles)) $title = $titles;
		else {
			eregi("<title>(.*)</title>",$str2,$regs2);
			$title = $regs2[1]; 
		}
		// ключи и описание
		$key = $desc = '';
		if ($PCon[4] == 1) {
			$meta = get_meta_tags ($urls[$i]);
			if ($PCon[8] == 1 && !empty($meta['keywords'])) 
				$key = iconv($kod, 'UTF-8',$meta['keywords']);
			else
				$key = newkey(mb_substr($content, 0, 2000),5,3);
			if ($PCon[9] == 1 && !empty($meta['description']))
				$desc = iconv($kod, 'UTF-8',$meta['description']);
			else
				$desc = trim(newdesc($content,$key,250));
		}
		// раздел куда сохраняем
		$result2 = $db->sql_fetchrow($db->sql_query("SELECT name FROM ".$prefix."_mainpage where id = '".$PRazdel[0]."'"));
		// облако мета
		if ( $PCon[5] == 1 ) $obl = $key; else $obl = '';
		// предложения в описание
		if  (!empty($texts)) { 
			$dubl = $opis = $texts; 
			$poln = preg_replace("@".$texts."@", '', $content, 1);  
		} else {
			$arr = explode(".", $content);
			$opis = ''; $poln = ''; $dubl = '';
			for($i=0;$i<count($arr);$i++) {
				if ($i < $PCon[6]) $opis .= $arr[$i].".";
				else $poln .= $arr[$i].".";
				if ($i > 5 && $i < 8) $dubl .= $arr[$i].".";
			}
		}
		$vs = $db->sql_numrows($db->sql_query("SELECT `pid` FROM ".$prefix."_pages where (`open_text` LIKE '%".mysql_real_escape_string($dubl)."%' or `main_text` LIKE '%".mysql_real_escape_string($dubl)."%')"));
		if ($vs == 0) {
			// подстановка ссылок
			$zamen = true;
			$link_arr = $link_arr2 = array();
			$res = $db->sql_query("SELECT * FROM ".$prefix."_parser where `class`='zamena'");
			if ($db->sql_numrows($res) > 0) {
			while ($row = $db->sql_fetchrow($res)) {
				if (strpos($poln, $row['title'])) {
					$poln = preg_replace("@".$row['title']."@", '<a href="'.$row['url'].'">'.$row['title'].'</a>', $poln, 1);
					$zamen = false;
					$ch = $row['config'] + 1;
					$db->sql_query("UPDATE ".$prefix."_parser SET config = '".$ch."' WHERE id='".$row['id']."'") or die ('Ошибка: Не удалось сохранить1.');
					break;
				}
				$link_arr[$row['config']] = $row['title']; 
				$link_arr2[$row['config']] = $row['url'];
				$link_arr3[$row['config']] = $row['id']; 
				$link_arr4[$row['config']] = $row['config']; }
				if ($zamen == true) {
					ksort($link_arr); 
					ksort($link_arr2); 
					ksort($link_arr3); 
					ksort($link_arr4);
					$link_title = current($link_arr);
					$link_url = current($link_arr2);
					$link_id = current($link_arr3);
					$link_config = current($link_arr4);
					$poln = '<a href="'.$link_url.'">'.$link_title.'</a>. '.$poln;
					$ch = $link_config+1;
					$db->sql_query("UPDATE ".$prefix."_parser SET config = '".$ch."' WHERE id='".$link_id."'") or die ('Ошибка: Не удалось сохранить2.');
				}
			}
			$sql = "INSERT INTO ".$prefix."_pages (`pid`,`module`,`cid`,`title`,`open_text`,`main_text`,`date`,`redate`,`counter`,`active`,`golos`,`comm`,`foto`,`search`,`mainpage`,`rss`,`price`,`description`,`keywords`,`tables`,`copy`,`sort`,`nocomm`,`meta_title`,`clean_url`) VALUES 
			(NULL, '".$result2['name']."', '0', '".mysql_real_escape_string(trim($title))."', '".mysql_real_escape_string(trim($opis))."', '".mysql_real_escape_string(trim($poln))."', '".$now."', '".$now."', '0', '".$PCon[3]."', '0', '0', '', '".$obl."', '0', '1', '0.00', '".mysql_real_escape_string($desc)."', '".mysql_real_escape_string($key)."', 'pages', '0','', '', '', '');";
			$db->sql_query($sql) or die ("Не удалось сохранить страницу. Попробуйте нажать в Редакторе на кнопку Чистка HTML в Редакторе. Если всё равно появится эта ошибка - сообщите разработчику нижеследующее:".$sql);
			// Узнаем получившийся номер страницы ID
			$sqld = "select pid from ".$prefix."_pages where title='".$title."' and date='".$now."'";
			$resultd = $db->sql_query($sqld);
			$rowd = $db->sql_fetchrow($resultd);
			return "Страница <a href='/-".$rowd['module']."_page_".$rowd['pid']."' target='_blank'>".$title."</a> ДОБАВЛЕНА - <a target='_blank' href='/sys.php?op=base_pages_edit_page&name=".$rowd['module']."&pid=".$rowd['pid']."'>Редактировать</a><hr>";
		} else return "Возможно статья уже существует ".$urls."<hr>";
	} else return "текст не нашли по адресу ".$urls."<hr>";
}

function get_charset($url) {
    $data = get_headers($url);
    foreach ($data as $key => $value) {
        $s = explode(':', $value);
        if(strcmp($s[0], 'Content-Type') == 0) return substr($s[1], strpos($s[1],'charset=') + 8);
    }
}

function get_codepage($text = '') {
    if (!empty($text)) {
        $utflower  = 7;
        $utfupper  = 5;
        $lowercase = 3;
        $uppercase = 1;
        $last_simb = 0;
        $charsets = array(
            'UTF-8'       => 0,
            'CP1251'      => 0,
            'KOI8-R'      => 0,
            'IBM866'      => 0,
            'ISO-8859-5'  => 0,
            'MAC'         => 0,
        );
        for ($a = 0; $a < strlen($text); $a++) {
            $char = ord($text[$a]);
            // non-russian characters
            if ($char<128 || $char>256) continue;
            // UTF-8
            if (($last_simb==208) && (($char>143 && $char<176) || $char==129)) $charsets['UTF-8'] += ($utfupper * 2);
            if ((($last_simb==208) && (($char>175 && $char<192) || $char==145)) || ($last_simb==209 && $char>127 && $char<144)) $charsets['UTF-8'] += ($utflower * 2);
            // CP1251
            if (($char>223 && $char<256) || $char==184) $charsets['CP1251'] += $lowercase;
            if (($char>191 && $char<224) || $char==168) $charsets['CP1251'] += $uppercase;
            // KOI8-R
            if (($char>191 && $char<224) || $char==163) $charsets['KOI8-R'] += $lowercase;
            if (($char>222 && $char<256) || $char==179) $charsets['KOI8-R'] += $uppercase;
            // IBM866
            if (($char>159 && $char<176) || ($char>223 && $char<241)) $charsets['IBM866'] += $lowercase;
            if (($char>127 && $char<160) || $char==241) $charsets['IBM866'] += $uppercase;
            // ISO-8859-5
            if (($char>207 && $char<240) || $char==161) $charsets['ISO-8859-5'] += $lowercase;
            if (($char>175 && $char<208) || $char==241) $charsets['ISO-8859-5'] += $uppercase;
            // MAC
            if ($char>221 && $char<255) $charsets['MAC'] += $lowercase;
            if ($char>127 && $char<160) $charsets['MAC'] += $uppercase;
            $last_simb = $char;
        }
        arsort($charsets);
        return key($charsets);
    }
}

function get_cpage($url) { 
	ob_start();
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_exec($ch);
	$result = ob_get_contents();
	curl_close ($ch);
	ob_end_clean();
	return $result;
}
?>
