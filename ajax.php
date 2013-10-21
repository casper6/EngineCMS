<?php
// Все «правила хорошего кода» написаны кровью, вытекшей из глаз программистов, читавших чужой код.
require_once("mainfile.php");
global $soderganie, $tip, $DBName, $prefix, $db, $module_name, $ModuleName, $admin;
if (isset($_REQUEST['func']))   $func = $_REQUEST['func']; else die(); // Выбор функции
if (isset($_REQUEST['type']))   $type = $_REQUEST['type']; else $type = 0;
if (isset($_REQUEST['id']))     $id = intval($_REQUEST['id']); else $id = 0;
if (isset($_REQUEST['string'])) $string = $_REQUEST['string']; else $string = "";
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
					$count--;
					if ($count == 1) $count = 0;
					$info[] = $t[0]."$".$t[1]."$".$t[2]."$".$t[3]."$".$t[4]."$".$count;
				}
			}
		}
		if (count($info) > 0) $tovars = implode("|",$info);
		else $tovars = "";
	setcookie ('shop_tovar', $tovars, time()+60*60*24*60); // список товаров|id страницы товара$стоимость товара
}
/////////////////////////////////////
if ($func == "shop_add_tovar") {
	$tovars = "";
	$count = 0;
	$update = false;
	$info = array();
	if (isset($_COOKIE['shop_tovar'])) if ($_COOKIE['shop_tovar'] != "") $tovars = $_COOKIE['shop_tovar']."|";
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
				$count = intval($t[5]);
				if ($count == 0) $count++;
				$count++;
				$info[] = $t[0]."$".$t[1]."$".$t[2]."$".$t[3]."$".$t[4]."$".$count;
			} elseif (intval($id) != intval($t[0])) $info[] = $tovar;
		}
		if ($update == false) {
			$razdel = $row['module'];
			$open_text = $row['open_text'];
			if (preg_match_all('/<img(?:\\s[^<>]*?)?\\bsrc\\s*=\\s*(?|"([^"]*)"|\'([^\']*)\'|([^<>\'"\\s]*))[^<>]*>/i', $open_text, $m)) $pic = $m[1][0]; else $pic = "";
			setcookie ('shop_tovar', $tovars.$id."$".$string."$".$title."$".$pic."$".$razdel."$".$count);
		} else {
			$tovars = implode("|",$info);
			setcookie ('shop_tovar', $tovars, time()+60*60*24*60); // хранится 2 месяца
		}
		// список товаров|id страницы товара$стоимость товара
	}
}
///////////////////////////////////////////////////////////
if ($func == "shop_send_order") {
	global $now, $shop_pole, $shop_text_mail, $shop_text_after_mail, $shop_admin_mail, $shop_spisok_pole, $shop_text_val1, $shop_text_val2, $siteurl; // ;
	// Настройки магазина
	if ($shop_text_val2 == "") $shop_text_val2 = ss(" руб.");
	if ($shop_text_itogo == "") $shop_text_itogo = ss("Итого:");
	if ($shop_text_oformit == "") $shop_text_oformit = ss("Оформить покупку");
	if ($shop_text_korzina == "") $shop_text_korzina = ss("Ваша Корзина пуста.");
	if ($shop_text_delete == "") $shop_text_delete = "×";
	if ($shop_pole == "") $shop_pole = "";
	if ($shop_admin_mail == "") $shop_admin_mail = $adminmail;
	if ($shop_text_after_mail == "") $shop_text_after_mail = ss("<h1>Спасибо!</h1><h3>Ваш заказ успешно отправлен. В ближайшее время мы вам позвоним.</h3>");
	if ($shop_spisok_pole == "") $shop_spisok_pole = ss("Ф.И.О.:*\nТелефон:*\nEmail:\nАдрес:\nДополнительная информация:");
	//if ($shop_shablon_form_order == "") $shop_shablon_form_order = "";
	//if ($shop_shablon_mail_client == "") $shop_shablon_mail_client = "";
	//if ($shop_shablon_mail_admin == "") $shop_shablon_mail_admin = "";

	$subject = aa("Новый заказ");
	$order = aa("Время приема заказа:")." ".$now."<br>";
	// Список контактных данных
	foreach ($string as $key => $value) {
		$order .= str_replace("mail_", "", $key).": ".$value."<br>";
	}
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
					$itogo += intval($tovar[1]);
				} else {
					$itogo += intval($tovar[1]) * $count;
					$count = " x ".$count;
				}
				$order .= "<br>".aa("Товар")." ".$tovar[2]."<br>
				".aa("Ссылка на товар:")." http://".$siteurl."/-".$name_razdel."_page_".$id_page."<br>
				".aa("Стоимость:")." ".$shop_text_val1.$tovar[1].$shop_text_val2.$count."<br>";
			}
		}
		$order .= "<br>".aa("ИТОГО:")." ".$shop_text_val1.$itogo.$shop_text_val2."<br>";
		// Отправка письма
		mail($shop_admin_mail, "=?utf-8?b?" . base64_encode($subject) . "?=", $order, "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: =?utf-8?b?" . base64_encode(aa("Администратор")) . "?= <" . $shop_admin_mail . ">");
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
	$info .= "<div class='shop_card_oformlenie'><a onclick='shop_show_card()'>".$shop_text_return."</a></div>
	<input name='func' value='shop_send_order' type='hidden'>
	</form>";
	echo $info;
	exit();
}
///////////////////////////////////////////////////////////
if ($func == "shop_show_card") {
	// global $title_razdels;
	global $shop_text_val1, $shop_text_val2, $shop_text_itogo, $shop_text_oformit, $shop_text_korzina, $shop_text_delete;
	$info = "";
	$itogo = 0;
	$img_width = $img_height = 30;
	if (!isset($_COOKIE['shop_tovar'])) $info = $shop_text_korzina;
	else {
		$tovars = explode("|", $_COOKIE['shop_tovar']);
		foreach ($tovars as $tovar) {
			$tovar = explode("$", $tovar);
			$id_page = intval($tovar[0]);
			if ($id_page != 0) {
				$name_razdel = $tovar[4];
				$pic = $tovar[3];
				$count = intval($tovar[5]);
				if ($count == 0) {
					$count = "";
					$itogo += intval($tovar[1]);
				} else {
					$itogo += intval($tovar[1]) * $count;
					$count = " x ".$count;
				}
				if ($pic != "") $pic = "<a href='".$tovar[3]."' title='".$tovar[2]."' class='lightbox' rel='group'><div class='shop_card_minifoto' style='background:url(\"includes/phpThumb/phpThumb.php?src=".$tovar[3]."&amp;w=".$img_width."&amp;h=".$img_height."&amp;q=0\") center center no-repeat;'></div></a>";
				$info .= "<div class='shop_card'>
				<div class='shop_card_price'><b>".$shop_text_val1.$tovar[1].$shop_text_val2.$count."</b>
				<a class='shop_card_del' onclick='shop_del_tovar(".$id_page.")'>".$shop_text_delete."</a></div>
				".$pic."<a target='_blank' href='-".$name_razdel."_page_".$id_page."'>".$tovar[2]."</a>
				</div>"; 
			}
		}
		$info .= "
		<div class='shop_card_price shop_card_itogo_price'><b>".$shop_text_val1.$itogo.$shop_text_val2."</b></div>
		<div class='shop_card_itogo'>".$shop_text_itogo."</div>
		<div class='shop_card_oformlenie'><a onclick='shop_show_order()'>".$shop_text_oformit."</a></div>";
	}
	echo "<div class='shop_cards'>".$info."</div>";
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
	    $info .= "<form class='regforma' action='--register' method='post'>
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
	$spasibo = " <b class=green>".ss("Спасибо за ваше неравнодушие!")."</b>";
	$golosovali = "<b class=red>".ss("Вы уже голосовали!")."</b>";
	$udaleny = ss("Голоса удалены.");
	$info = "";
	if (isset($golos_id) and isset($GLOBALS[$golos_id])) $tmp = $GLOBALS[$golos_id]; else $tmp = ""; // поставлено от голосования
	list($name, $gol) = explode("*@%", $string);
		$type = intval($type);
		$ip = getenv("REMOTE_ADDR"); // IP
		if ($gol == 6 and is_admin($admin)) { // Доделать
			$db->sql_query("UPDATE ".$prefix."_pages SET golos='0' WHERE pid='".$id."';");
			$db->sql_query("DELETE from ".$prefix."_pages_golos WHERE num='".$id."';");
			$info = $udaleny;
			echo $info;	exit;
		}
		if ($gol > 5) $gol=1; 
		if ($gol < 0) $gol=1;
		if ($type != 2 and $type != 3) $gol = intval($gol);
		$dat = date("Y.m.d H:i:s");
		$golos_id = $prefix.'golos'.$id;
		if ($type == 0) if ($gol != 1 and $gol != 2 and $gol != 3 and $gol != 4 and $gol != 5 ) $gol = 5;
		if ($type == 1) $gol = 1;
		if ($type == 2 or $type == 3) if ($gol != 1) $gol = -1;
		if ($type != 0) {
			$sql = "SELECT `golos` FROM ".$prefix."_pages where `pid`='".$id."'";
			$row2 = $db->sql_fetchrow($db->sql_query($sql));
			$resnum = $db->sql_query($sql);
			$numrows = $db->sql_numrows($resnum);
			if (($numrows > 0 and $tmp == $golos_id) or $id==0) {
				$info = $golosovali;
			} else {
				$golos = $row2['golos'] + $gol;
				$db->sql_query("UPDATE ".$prefix."_pages SET `golos`='".$golos."' WHERE `pid`='".$id."';");
				$db->sql_query("INSERT INTO ".$prefix."_pages_golos (`gid`, `ip`, `golos`, `num`, `data`) VALUES ('', '$ip', '$gol', '$id', '$dat')");
				setcookie ($golos_id, $golos_id,time()+2678400,"/");
			}
		} else {
			$sql = "SELECT `data` FROM ".$prefix."_pages_golos WHERE `ip`='$ip' AND `num`='".$id."'";
			$resnum = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$date = $row['data'];
			$date2 = dateresize($date);
			$date = dateresize($dat);
			$numrows = $db->sql_numrows($resnum);
			if ($numrows > 0 or $tmp==$golos_id or $id==0) {
				$info = $golosovali;
			} else {
				$db->sql_query("INSERT INTO ".$prefix."_pages_golos (`gid`, `ip`, `golos`, `num`, `data`) VALUES ('', '$ip', '$gol', '$id', '$dat')");
				setcookie ($golos_id, $golos_id,time()+2678400,"/");
			}
		}
		$sqlX = "SELECT `module` from ".$prefix."_pages where `pid`='".$id."'";
		$resultX = $db->sql_query($sqlX);
		$rowX = $db->sql_fetchrow($resultX);
		$mod = $rowX['module'];
		recash("/-".$mod."_page_".$id); // Обновление кеша
		if ($info != $golosovali) $info .= $spasibo;
	echo $info; exit();
}
?>