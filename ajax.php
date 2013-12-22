<?php
// Все «правила хорошего кода» написаны кровью, вытекшей из глаз программистов, читавших чужой код.
require_once("mainfile.php");
global $soderganie, $tip, $DBName, $prefix, $db, $module_name, $ModuleName, $admin;
if (isset($_REQUEST['func']))   $func = $_REQUEST['func']; else die(); // Выбор функции
if (isset($_REQUEST['type']))   $type = $_REQUEST['type']; else $type = 0;
if (isset($_REQUEST['id']))     $id = intval($_REQUEST['id']); else $id = 0;
if (isset($_REQUEST['string'])) $string = $_REQUEST['string']; else $string = "";
///////////////////////////////////////////////////////////
if ($func == "show_calendar") {
	parse_str($string);
	$and = "";
	//$and = " and module='".$useitX."'";
	$calendar_dates = array();
	if ($calendar == "") {
		$sql = "select date from ".$prefix."_pages where `tables`='pages'".$and." and active!='0' order by date";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$dates = explode(" ",$row['date']);
			$calendar_dates[] = $dates[0];
		}
	} else {
		$sql = "select name from ".$prefix."_spiski where type='".$calendar."' and pages!='' order by name";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$calendar_dates[] = $row['name'];
		}	
	}
	require_once("page/functions.php");
	echo my_calendar($calendar_dates, $useitX, $showdate);
	exit();
}
///////////////////////////////////
if ($func == "save_raspisanie") {
	global $adminmail;
	parse_str($string);
	if (($your_name != "" && $your_tel != "") || is_admin($admin)) {
		// Запись в базу
		$result = $db->sql_query("SELECT `useit` FROM ".$prefix."_mainpage where `id`='".$id_block."' and `useit` like '%".$zapis.$zapis_num.",".$zapis_dat.";%'");
		if ($db->sql_numrows($result) == 0) {
			
			$result = $db->sql_query("SELECT `useit` FROM ".$prefix."_mainpage where `id`='".$id_block."'");
			$row = $db->sql_fetchrow($result);
			$useit = substr($row['useit'], 1);
			parse_str($useit);
			// 1,15.11.2013, 14:00,777,666;,15.11.2013, 14:00,77700,00;
			if (is_admin($admin) && !is_numeric($zapis_num)) {
				if (trim($your_name) == "" or trim($your_tel) == "") { // Удаление
					$useit = str_replace($zapis_num, "", $row['useit']);
					echo aa("Запись удалена.");
				} else { // Редактирование
					$z_num = explode(",", $zapis_num);
					$useit = str_replace($zapis_num, $z_num[0].",".$zapis_dat.",".str_replace(",", ".", $your_name).",".str_replace(",", ".", $your_tel).";", $row['useit']);
					echo aa("Запись отредактирована.");
				}
			}
			if (is_numeric($zapis_num)) $useit = str_replace("zapis=".$zapis, "zapis=".$zapis.$zapis_num.",".$zapis_dat.",".str_replace(",", ".", $your_name).",".str_replace(",", ".", $your_tel).";", $row['useit']);
			$db->sql_query("UPDATE ".$prefix."_mainpage SET `useit`='".$useit."' WHERE `id`='".$id_block."';");

			// Отправка письма
			if (!is_admin($admin)) {
				$order = "<b>".aa("Заявка")."</b><br>".aa("Имя").": ".$your_name."<br>".aa("Телефон").": ".$your_tel."<br>".$zapis_specialist."<br>".aa("Дата и время").": ".$zapis_dat;
				if ($adminmail != "") mail($adminmail, "=?utf-8?b?" . base64_encode(aa("Новая заявка")) . "?=", str_replace("<br>","\r\n",$order), "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: =?utf-8?b?" . base64_encode(aa("Администратор")) . "?= <" . $adminmail . ">");
				// Отправляем системное сообщение админу
				system_mes($order);
			}
			if (is_numeric($zapis_num)) echo $zapis_zayavka_send;
		} else echo ss("Запись на это время уже есть. Обновите страницу (по F5).");
	} else echo ss("Вы не ввели имя или телефон. Обновите страницу (по F5) и повторите.");
	exit();
}
///////////////////////////////////
if ($func == "show_raspisanie") {
	list($specialist, $work_time, $record, $all_days, $next_day, $current_dayX) = explode("*@%", $string); // убрать за счет options
	// Получаем текст блока по id
	$sql2 = "select `text`, `useit` from ".$prefix."_mainpage where `id`='".$id."' and `type`='3' and `tables`='pages'";
	$result2 = $db->sql_query($sql2);
	$row2 = $db->sql_fetchrow($result2);
	$raspisaniya = explode("\n", trim($row2['text']));
	$options = substr($row2['useit'], 1);
	parse_str($options);
	// 2,11.11.2013, 14:00;
	$info = "";
	$zapisi = explode(";", $zapis);
	$all_zapisi = $all_zapisi_person = array();
	foreach ($zapisi as $zapis) {
		if ($zapis != "") {
			$zapis = explode(",", $zapis);
			$zapis_person = $zapis[0];
			$zapis_day = $zapis[1];
			$zapis_time = trim($zapis[2]);
			$zapis_name = trim($zapis[3]);
			$zapis_tel = trim($zapis[4]);
			$all_zapisi[$zapis_day." ".$zapis_time] = $zapis_person;
			if (is_admin($admin)) $all_zapisi_person[$zapis_day." ".$zapis_time] = $zapis_name."\n".$zapis_tel;
		}
	}

	for ($nextday = $next_day; $nextday < $next_day + $all_days; $nextday++) {
		if ($current_dayX == 0) $tim = time()+86400*$nextday;
		else {
			$current_dayX = explode(".", $current_dayX);
			$tim = mktime(0,0,0,$current_dayX[1], $current_dayX[0], $current_dayX[2]);
		}
		$now_day = date("j", $tim);
		$now_day_0 = date("d", $tim); // с нулем число
		$now_year = date("Y", $tim);
		$day_num = date("w", $tim); 
		$mes_num = date("m", $tim);
		$day_name = array("воскресенье","понедельник","вторник","среда","четверг","пятница","суббота");
		$day_name_big = array("Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота");
		$mes_name = array("","января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
		$day_name = $day_name[$day_num];
		$info .= "<table style='width:100%; margin:0; padding:0; margin-bottom:40px;'>
		<tr><th width=15%>".$specialist."</th><th>".$day_name_big[$day_num].", ".$now_day." ".$mes_name[$mes_num]." ".$now_year.". ".$work_time."</th></tr>";
		foreach ($raspisaniya as $raspisanie) {
			$raspisanie = explode(";", $raspisanie);
			$person_num = intval($raspisanie[0]); // номер для заполнения записи
			$person_name = trim($raspisanie[1]);
			$person_profession = trim($raspisanie[2]);
			$person_work_time = intval(trim(str_replace("минут", "replace", $raspisanie[3])));
			$count = count($raspisanie);
			$person_day_time = array("воскресенье"=>"","понедельник"=>"","вторник"=>"","среда"=>"","четверг"=>"","пятница"=>"","суббота"=>"","нечетные"=>"","четные"=>"","будни"=>"","выходные"=>"","ежедневно"=>"");
			//$chet = 0;
			for ($i=4; $i < $count; $i++) { 
				$times = explode(" ", trim($raspisanie[$i]));
				$days = $times[0];
				$time = $times[1];
				$monthX = "";
				if (isset($times[2])) { // числа месяца
					$time = $times[2];
					$monthX = $times[1];
				}
				$times = explode("-", $days);
				foreach ($times as $t) {
					$t = trim($t);
					if ($monthX != "") $person_day_time[$t.$monthX] = $time;
					else $person_day_time[$t] = $time;
				}
			}
			// Выводим информацию по каждому врачу за выбранный день
			$work_hours = $work_order = array();
			// высчитываем процент - кол-во часов текущего дня
			
			$mes_name2 = $mes_name[$mes_num];
			if ($person_day_time[$now_day.$mes_name2] != "") { // числа
				$person_day_time = $person_day_time[$now_day.$mes_name2];
			} elseif ($person_day_time["нечетные"] != "" || $person_day_time["четные"] != "") { // чет/не чет
				if ($now_day & 1) {
			       if ($person_day_time["нечетные"] != "") $person_day_time = $person_day_time["нечетные"];
			    } else { 
			       if ($person_day_time["четные"] != "") $person_day_time = $person_day_time["четные"];
			    }
			} elseif ($person_day_time["будни"] != "" && $day_num != 6 && $day_num != 0) $person_day_time = $person_day_time["будни"];
			elseif ($person_day_time["выходные"] != "" && ($day_num == 6 || $day_num == 0)) $person_day_time = $person_day_time["выходные"];
			elseif ($person_day_time["ежедневно"] != "") $person_day_time = $person_day_time["ежедневно"];
			else { // дни недели
				$person_day_time = $person_day_time[$day_name];
			}

			if ($person_day_time != "" && !is_array($person_day_time)) {
				$time_interval = explode("-", $person_day_time);
				$time_interval_1 = $time_interval[0]; // 15:00
				$time_interval_2 = $time_interval[1]; // 20:30
				$hour_1 = explode(":", $time_interval_1);
					$hour_minutes_1 = 60;
					if (intval($hour_1[1]) > 0) $hour_minutes_1 = intval($hour_1[1]);
					$minutes_1 = intval($hour_1[0] * 60 + $hour_1[1]);
				$hour_1 = intval($hour_1[0]);
				$hour_2 = explode(":", $time_interval_2);
					$hour_minutes_2 = 60;
					if (intval($hour_2[1]) > 0) $hour_minutes_2 = intval($hour_2[1]);
					$minutes_2 = intval($hour_2[0] * 60 + $hour_2[1]);
				$hour_2_2 = intval($hour_2[0]);
				if ($hour_2[1] != "00") $hour_2_2++; //  && $hour_2_2 != 23
				$hour_2 = $hour_2_2;

				$raznica_hour = ($minutes_2 - $minutes_1) / 60;
				$procent_hour = intval( 100 / $raznica_hour );

				$raznica_minutes = intval($minutes_2 - $minutes_1);
				$raznica_minutes2 = intval($raznica_minutes / $person_work_time);
				
				$ostatok_minutes_2 = intval($raznica_minutes - ($raznica_minutes2 * $person_work_time));
				if ($ostatok_minutes_2 != 0) $raznica_minutes2++;

				$procent_minutes = intval( 100 / $raznica_minutes2 );

				$procent_minutes_1 = 100 / $raznica_minutes; /// процент на одну минуту времени

				//$info .= $hour_minutes_1."|".$hour_minutes_2." - $ostatok_minutes_2<br>";
				if ($person_day_time != "" && !is_array($person_day_time)) {
					for ($y=$hour_1; $y < $raznica_hour + $hour_1; $y++) {
						$procent_hour = 60 * $procent_minutes_1;
						$minutes_for_hour = "00";
						if ($y == $hour_1) {
							$procent_hour = $hour_minutes_1 * $procent_minutes_1;
							if ($hour_minutes_1 != 60) $minutes_for_hour = $hour_minutes_1;
						}
						if ($y > $raznica_hour + $hour_1 - 1) $procent_hour = $hour_minutes_2 * $procent_minutes_1;
						$work_hours[] = "<td width='".$procent_hour."%'>".$y."<sup>".$minutes_for_hour."</sup></td>";
					}
					for ($y=0; $y < $raznica_minutes2; $y++) { 
						$procent_minutes2 = $person_work_time * $procent_minutes_1;
						if ($ostatok_minutes_2 != 0 && $y > $raznica_minutes2-2) $procent_minutes2 = $ostatok_minutes_2 * $procent_minutes_1;

						$totalMinutes = $minutes_1 + $y * $person_work_time;
						$totalHours = floor($totalMinutes/60); // Получаем количество полных часов
						$totalMinutes = $totalMinutes - ($totalHours*60); // Получаем оставшиеся минуты
						if ($totalMinutes == 0) $totalMinutes = "00";

						if ($all_zapisi[$now_day.".".$mes_num.".".$now_year." ".$totalHours.":".$totalMinutes] != $person_num) {
							if ($current_dayX == 0 && $next_day == 0 && intval($totalHours) <= intval(date("G",time())))
								$work_order_ok = "<td width='".$procent_minutes2."%' style='background: lightyellow' title='".$totalHours.":".$totalMinutes."'></td>";
							else $work_order_ok = "<td width='".$procent_minutes2."%' class='raspisanie'><a title='".$record.$totalHours.":".$totalMinutes."' onclick='show_zapis(\"".$person_num."\", \"".$now_day.".".$mes_num.".".$now_year.", ".$totalHours.":".$totalMinutes."\", \"".$person_name.", ".$person_profession."\")'><div class='raspisanie'></div></a></td>";
						} else {
							if (is_admin($admin)) $add_button = "<a onclick='show_zapis(\"".$person_num.",".$now_day.".".$mes_num.".".$now_year.", ".$totalHours.":".$totalMinutes.",".str_replace("\n", ",", $all_zapisi_person[$now_day.".".$mes_num.".".$now_year." ".$totalHours.":".$totalMinutes]).";\", \"".$now_day.".".$mes_num.".".$now_year.", ".$totalHours.":".$totalMinutes."\", \"".$person_name.", ".$person_profession."\")'><div class='raspisanie'></div></a>";
							$work_order_ok = "<td width='".$procent_minutes2."%' class='raspisanie_add' title='".$totalHours.":".$totalMinutes."\n".$all_zapisi_person[$now_day.".".$mes_num.".".$now_year." ".$totalHours.":".$totalMinutes]."'>".$add_button."</td>";
						}

						$work_order[] = $work_order_ok;
					}
					//if ($ostatok_minutes != 0) $work_order[] = "<td bgcolor=green></td>";
					$work_time_table = "<table style='width:100%; margin:0; padding:0;'><tr>".implode("",$work_hours)."</tr></table>
					<table style='width:100%; margin:0; padding:0; height:15px;'><tr>".implode("",$work_order)."</tr></table>";
					$info .= "<tr><td width='25%'><b>".$person_name.",</b><br>".$person_profession."</td><td>".$work_time_table."</td></tr>";
				}
			}
		}
		$info .= "</table>";
	}
	echo $info;
	exit();
}
///////////////////////////////////
if ($func == "shop_delete") {
	setcookie ('shop_tovar', '');
}
///////////////////////////////////
if ($func == "shop_del_tovar") {
	$tovars = "";
	$info = array();
	if (isset($_COOKIE['shop_tovar'])) if ($_COOKIE['shop_tovar'] != "") $tovars = $_COOKIE['shop_tovar']."|";
	$tovars = explode("|", $_COOKIE['shop_tovar']);
		foreach ($tovars as $tovar) {
			$t = explode("$", $tovar);
			if (intval($t[0]) != 0) {
				if (intval($id) != intval($t[0])) $info[] = $tovar;
				elseif (intval($t[5]) > 1) {
					$count = intval($t[5]);
					if ($string == 0) $count--;
					else $count = 0;
					//if ($count == 1) $count = 0;
					if ($count != 0) $info[] = $t[0]."$".$t[1]."$".$t[2]."$".$t[3]."$".$t[4]."$".$count."$".$t[6]."$".$t[7]."$".$t[8];
				}
			}
		}
		if (count($info) > 0) $tovars = implode("|",$info);
		else $tovars = "";
	setcookie ('shop_tovar', $tovars, time()+5184000); // 60*60*24*60 - 2 месяца, список товаров|id страницы товара$стоимость товара
}
/////////////////////////////////////
if ($func == "shop_add_tovar") {
	list($price, $count, $pole, $price_replace, $replace_type) = explode("*@%", $string);
	$price = floatval(str_replace(",", ".", $price));
	$tovars = "";
	$count = intval($count);
	$update = false;
	$info = array();
	if (isset($_COOKIE['shop_tovar'])) if ($_COOKIE['shop_tovar'] != "") $tovars = trim($_COOKIE['shop_tovar']);
	// Узнаем раздел, название и картинку по id
	$row = $db->sql_fetchrow($db->sql_query("SELECT `module`,`title`,`open_text` from ".$prefix."_pages where `pid`='".$id."'"));
	$title = $row['title'];
	if ($title != "") {
		// находим кол-во товара
		$ts = explode("|", $tovars);
		foreach ($ts as $tovar) {
			$t = explode("$", $tovar);
			if ($id == intval($t[0])) {
				$update = true;
				$count = $count + intval($t[5]);
				//if ($count == 0) $count++;
				//$count++;
				if ($count != 0) $info[] = $t[0]."$".$t[1]."$".$t[2]."$".$t[3]."$".$t[4]."$".$count."$".$pole."$".$price_replace."$".$replace_type;
			} elseif (intval($id) != intval($t[0])) $info[] = $tovar;
		}
		if ($update == false) { // первый товар в Корзине
			$razdel = $row['module'];
			$open_text = $row['open_text'];
			if (preg_match_all('/<img(?:\\s[^<>]*?)?\\bsrc\\s*=\\s*(?|"([^"]*)"|\'([^\']*)\'|([^<>\'"\\s]*))[^<>]*>/i', $open_text, $m)) 
				$pic = $m[1][0]; else $pic = "";
			if ($tovars != "") $tovars .= "|";
			setcookie ('shop_tovar', $tovars.$id."$".$price."$".$title."$".$pic."$".$razdel."$".$count."$".$pole."$".$price_replace."$".$replace_type);
		} else { // последующие товары в Корзине
			$tovars = implode("|",$info);
			setcookie ('shop_tovar', $tovars, time()+5184000); // хранится 60*60*24*60 - 2 месяца
		}
		// список товаров|id страницы товара$стоимость товара
	}
}
///////////////////////////////////////////////////////////
if ($func == "shop_send_order") {
	global $now, $shop_pole, $shop_text_mail, $shop_text_after_mail, $shop_admin_mail, $shop_spisok_pole, $shop_text_val1, $shop_text_val2, $siteurl; // ;
	//if ($shop_shablon_form_order == "") $shop_shablon_form_order = "";
	//if ($shop_shablon_mail_client == "") $shop_shablon_mail_client = "";
	//if ($shop_shablon_mail_admin == "") $shop_shablon_mail_admin = "";

	$subject = aa("Новый заказ");
	$order = aa("Время приема заказа:")." ".$now."<br>";
	// Список контактных данных
	foreach ($string as $key => $value) {
		$order .= str_replace("mail_", "", $key).": ".$value."<br>";
	}
	$itogo = 0;
	// Список товаров
	if (isset($_COOKIE['shop_tovar'])) {
		$tovars = explode("|", $_COOKIE['shop_tovar']);
		foreach ($tovars as $tovar) {
			$tovar = explode("$", $tovar);
			$id_page = intval($tovar[0]);
			if ($id_page != 0) {
				$name_razdel = $tovar[4];
				$count = intval($tovar[5]);
				if ($count == 0) {
					$count = "";
					$itogo += floatval($tovar[1]);
				} else {
					$itogo += floatval($tovar[1]) * intval($count);
					$count = " x ".$count;
				}
				$order .= "<br>".aa("Товар")." ".$tovar[2]."<br>
				".aa("Ссылка на товар:")." http://".$siteurl."/-".$name_razdel."_page_".$id_page."<br>
				".aa("Стоимость:")." ".$shop_text_val1.$tovar[1].$shop_text_val2.$count."<br>";
			}
		}
		$order .= "<br>".aa("ИТОГО:")." ".$shop_text_val1.$itogo.$shop_text_val2."<br>";
		// Отправка письма
		mail($shop_admin_mail, "=?utf-8?b?" . base64_encode($subject) . "?=", str_replace("<br>","\r\n",$order), "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: =?utf-8?b?" . base64_encode(aa("Администратор")) . "?= <" . $shop_admin_mail . ">");
    	echo $shop_text_after_mail; 
    	system_mes($order); // Отправляем системное сообщение админу
    	setcookie ('shop_tovar', ''); // очищаем куки
	} 
	exit();
}
///////////////////////////////////////////////////////////
if ($func == "shop_show_order") {
	global $shop_pole, $shop_text_mail, $shop_text_return, $shop_spisok_pole; // $shop_text_after_mail, $shop_admin_mail;
	// types - text, email, tel
	$shop_text_return = ss("Вернуться");
	$pole = $onclick = "";
	// Получаем поля
	$shop_spisok_pole = explode("\n",$shop_spisok_pole);
	foreach ($shop_spisok_pole as $key => $value) {
		// Определение важности *
		if (strpos($value, "*")) {
			$value = str_replace("*", "", $value);
			$add = "<sup class='red'>*</sup>";
			$onclick .= "if ($('#mail_".translit_name($value)."').val() == '') al = al + '".ss("Заполните поле")." «".$value."».\\n';";
		} else $add = "";
		// Определение типа текстового поля
		$type = "text";
		$v = mb_convert_case($value, MB_CASE_LOWER);
		$type_texts = array(
			"tel"=>ss("тел.;телефон;сот.;сотовый;факс;тел/"),
			"email"=>ss("mail;электропочта;электронная почта;мэйл"),
			"date"=>ss("дата"),
			"time"=>ss("время"),
			"datetime"=>ss("дата;время"),
			"password"=>ss("пароль"),
			"month"=>ss("месяц"),
			"color"=>ss("цвет"),
			"week"=>ss("неделя"),
			"url"=>ss("ссылка;url;link;линк;адрес сайта;адрес страницы;адрес в интернете;интернет адрес"),
			"number"=>ss("сколько;как много;как часто")
			);
		foreach ($type_texts as $ke => $valu) {
			$valu2 = explode(";",$valu);
			foreach ($valu2 as $val)
				if (stripos(" ".$v, $val)!==false) $type = $ke;
		}

		$pole .= "<p><label for='string[mail_".translit_name($value)."]'>".$value.$add."</label><br>
		<input type='".$type."' name='string[mail_".translit_name($value)."]' id='mail_".translit_name($value)."' class='shop_form_input' /> ";
	}
	$info = "
	<p>".$shop_text_mail."
	<form action='' class='shop_order_form' method=post id='order_form'>
		".$pole."
		<p><input type='button' value='".ss("Отправить")."' onClick=\"AjaxFormRequest('shop_card', 'order_form', 'ajax.php');\" />";
	if ($onclick != "") $info .= "<p>".ss("Поля, помеченные")." <sup class='red'>*</sup> ".ss("обязательны к заполнению.");
	$info .= "<div class='shop_card_oformlenie'><a onclick='shop_show_card(\"card\")'>".$shop_text_return."</a></div>
	<input name='func' value='shop_send_order' type='hidden'>
	</form>";
	echo $info;
	exit();
}
///////////////////////////////////////////////////////////
if ($func == "shop_show_card") {
	// global $title_razdels;
	$shop_tovarov = "товаров,товар,товара";
	$shop_for_summa = "на сумму";
	global $shop_text_val1, $shop_text_val2, $shop_text_itogo, $shop_text_oformit, $shop_text_korzina, $shop_text_delete, $shop_text_ochistit, $shop_text_delete_all, $shop_minimal_itogo, $shop_minimal_itogo_text;
	$info = "";
	$itogo = 0;
	$all_count = 0;
	// $string - тип корзины
	$img_width = $img_height = 30;
	if (!isset($_COOKIE['shop_tovar'])) { if ($string != "price") $info = $shop_text_korzina; }
	else {
		$tovars = explode("|", $rest = $_COOKIE['shop_tovar']);
		foreach ($tovars as $tovar) {
			$tovar = explode("$", $tovar);
			$price = $tovar[1];
			if (isset($tovar[7])) $price_replace = $tovar[7]; else $price_replace = "";
			if (isset($tovar[8])) $replace_type = $tovar[8]; else $replace_type = 1;
			// Проверка изменившейся цены
			if (isset($tovar[6])) if ($tovar[6] != "") {
				$row = $db->sql_fetchrow($db->sql_query("SELECT `name` from ".$prefix."_spiski where `type`='".htmlspecialchars($tovar[6],ENT_QUOTES)."' and `pages` like '% ".$tovar[0]." %'"));
				if ($row['name'] != $price) $price = floatval($row['name']);
			}
			$id_page = intval($tovar[0]);
			if ($id_page != 0) {
				$name_razdel = $tovar[4];
				$pic = $tovar[3];
				$count = intval($tovar[5]);
				$del_all = "";
				if ($count == 1) {
					$count = "";
					$all_count++;
					$itogo += floatval($price);
				} else {
					// Подключаем обработку цены
					if ($price_replace != "") {
						$price_replace = explode(",", $price_replace);
						foreach ($price_replace as $value) {
							$price_key = explode("=", $value);
							if ($count > $price_key[0]) {
								if ($replace_type == 1) $price = $price_key[1]; // 1 тип замены цены: цены просто меняется
								if ($replace_type == 2) $price = floatval($price / 100 * (100 - $price_key[1])); // 2 тип замены цены: от цены отнимается процент скидки
							}
						}
					}

					$itogo += floatval($price) * $count;
					$all_count += $count;
					if ($count > 1) $del_all = " <a class='shop_card_del' onclick='shop_del_tovar(".$id_page.",1)'>".$shop_text_delete_all."</a>";
					$count = " x ".$count;
				}
				if ($string == "card") {
					if ($pic != "") $pic = "<a href='".$tovar[3]."' title='".$tovar[2]."' class='lightbox' rel='group'><div class='shop_card_minifoto' style='background:url(\"includes/php_thumb/php_thumb.php?src=".$tovar[3]."&amp;w=".$img_width."&amp;h=".$img_height."&amp;q=0\") center center no-repeat;'></div></a>";
					$info .= "<div class='shop_card'>
					<div class='shop_card_price'><b>".$shop_text_val1.$price.$shop_text_val2.$count."</b>
					<a class='shop_card_del' onclick='shop_del_tovar(".$id_page.",0)'>".$shop_text_delete."</a>".$del_all."</div>
					".$pic."<a target='_blank' href='-".$name_razdel."_page_".$id_page."'>".$tovar[2]."</a>
					</div>";
				}
			}
		}
		if ($string == "price") $info = $itogo;
		if ($string == "count") $info = $all_count;
        if ($itogo <= $shop_minimal_itogo && $shop_minimal_itogo != 0) {
        	$shop_oformit_link = "class='disable' title='".$shop_minimal_itogo_text."'";
        	$shop_minimal_text = "<div class='shop_minimal_itogo_text'>".$shop_minimal_itogo_text."</div>";
        } else { 
        	$shop_oformit_link ="onclick='shop_show_order()'";
        	$shop_minimal_text = "";
        }

		if ($string == "card") {
			$info .= "<div class='shop_card_price shop_card_itogo_price'><b>".$shop_text_val1.$itogo.$shop_text_val2."</b></div>
			<div class='shop_card_itogo'>".$shop_text_itogo." ".$all_count." ".num_ending($all_count, explode(",", $shop_tovarov))."</div>
			<div class='shop_card_oformlenie'><a ".$shop_oformit_link.">".$shop_text_oformit."</a> <a class='shop_card_delete' onclick='shop_delete()'>".$shop_text_ochistit."</a></div>";
			$info = "<div class='shop_cards'>".$info.$shop_minimal_text."</div>";
		}
	}
	echo $info;
	exit();
}
///////////////////////////////////////////////////////////
if ($func == "registration_form") {
	$info = "";
	$result = $db->sql_query("SELECT `id`, `name` FROM ".$prefix."_mainpage where `name`!='config' and `type`='10'");
	$cnt = $db->sql_numrows($result);
	if ($cnt == 0) $info .= ss("Для регистрации необходимо создать группу.");
	else {
	    // Вывести форму на экран 
	    $info .= "<form class='regforma' action='register' method='post'>
	    <br><input class='regmail' type='email' name='em' value='' placeholder='".ss("Email")."'>";
	    if ($cnt > 1) {
	        $info .= "<br><select name='groups' class='groups'><option value='0'>".ss("Выберите группу")."</option>";
	        while ($row = $db->sql_fetchrow($result)) {
	          $info .= "<option value='".$row['id']."'>".$row['name']."</option>";
	        }
	        $info .= "</select>";
	    }
	    $row = $db->sql_fetchrow($db->sql_query("SELECT `useit` FROM ".$prefix."_mainpage where `name`='config' and `type`='10'"));
	    if ($row['useit'] == 1) {
	        $info .= "<br>".ss("Выберите местоположение");
	        $soderganie .= include("includes/regions/meny.html");
	    }
	    $info .= "<br><input type='submit' name='submit' value='".ss("Зарегистрироваться")."'></form>"; 
	}
	echo $info; exit();
}
///////////////////////////////////////////////////////////
if ($func == "savegolos") { // Сохраняем голосование
	if ($type == 0 || $type == 1 || $type == 2 || $type == 3 || $type == 4 || $type == 5 || $type == 6) {
		$spasibo = " <b class='green'>".ss("Спасибо за ваше неравнодушие!")."</b>";
		$golosovali = "<b class='red'>".ss("Вы уже голосовали!")."</b>";
		$udaleny = ss("Голоса удалены.");
		$info = "";
		$golos_id = $prefix.'golos'.$id;
		if (isset($GLOBALS[$golos_id])) $tmp = $GLOBALS[$golos_id]; else $tmp = "";
		list($name, $gol) = explode("*@%", $string);
		//echo $gol;
		$gol = intval($gol);
		if ($type == 0 || $type == 5) if ($gol < 1 || $gol > 5) $gol = 1;
		if ($type == 1) $gol = 1;
		if ($type == 2 || $type == 3) if ($gol != 1) $gol = -1; // 0
		if ($type == 4) if ($gol < 1 || $gol > 3) $gol = 1;
		if ($type == 6) if ($gol < 1 || $gol > 10) $gol = 1;
		$ip = getenv("REMOTE_ADDR"); // IP
		/*
		if ($gol == 6 and is_admin($admin)) { // Доделать
			$db->sql_query("UPDATE ".$prefix."_pages SET golos='0' WHERE pid='".$id."';");
			$db->sql_query("DELETE from ".$prefix."_pages_golos WHERE num='".$id."';");
			$info = $udaleny;
			echo $info;	exit;
		}
		*/
		$dat = date("Y.m.d H:i:s");
		if ($type != 0 && $type != 4 && $type != 5 && $type != 6) {
			$sql = "SELECT `golos` FROM ".$prefix."_pages where `pid`='".$id."' and `ip`='".$ip."'";
			$num = $db->sql_query($sql);
			if (($db->sql_numrows($num) > 0 || $tmp==$golos_id) || $id<1) {
				$info = $golosovali;
			} else {
				$row2 = $db->sql_fetchrow($db->sql_query($sql));
				$golos = $row2['golos'] + $gol;
				$db->sql_query("UPDATE ".$prefix."_pages SET `golos`='".$golos."' WHERE `pid`='".$id."';");
				$db->sql_query("INSERT INTO ".$prefix."_pages_golos (`gid`, `ip`, `golos`, `num`, `data`) VALUES ('', '$ip', '$gol', '$id', '$dat')");
				setcookie ($golos_id, $golos_id,time()+2678400,"/");
			}
		} else { // тип - оценка (5 звезд)
			global $now;
			$num = $db->sql_query("SELECT `data` FROM ".$prefix."_pages_golos WHERE `ip`='".$ip."' AND `num`='".$id."'");
			if ($db->sql_numrows($num) > 0 || $tmp==$golos_id || $id<1) {
				$info = $golosovali;
			} else {
				$db->sql_query("INSERT INTO ".$prefix."_pages_golos (`gid`, `ip`, `golos`, `num`, `data`) VALUES ('', '$ip', '$gol', '$id', '".$now."')");
				setcookie ($golos_id, $golos_id,time()+2678400,"/");
			}
		}
		if ($info != $golosovali) $info .= $spasibo;
		echo $info; 
	}
	exit();
}
?>