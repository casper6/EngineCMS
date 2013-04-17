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
function regions_main() {
	global $prefix, $db, $soderganie;
	include("ad-header.php");
	$result = $db->sql_query("SELECT count(*) as cnt FROM ".$prefix."_regions") or die('<center>База регионов не установленна, <a href="sys.php?op=regions_install">приступить к установке</a></center>');
	while ($row = mysql_fetch_assoc($result)) { 
		if ($row['cnt'] == 0) echo '<center>cуществует мнение что база регионов не установленна, <a href="sys.php?op=regions_install">приступить к установке</a></center>';
	}
	include("includes/regions/config.php");
	$stru = array('','область-район-город','область-город','область-район','район-город','только области','только районы','только города');
	echo '<div style="background: #e2e5ea;"><div class="black_grad" style="height:45px;">
	<span class="h1">Регионы использует структуру <b>'.$stru[$stryktyra].'</b></span></div>
	<center><div class=block style="width:800px; text-align:left;">
	<p>Выбор регионов:';
	include("includes/regions/meny.html");
	echo $soderganie.'<p>Вы можете переустановить регионы. При этом не сокращайте структуру и обязательно используйте в новой базе регионы из прошлой.<br>Несоблюдение правил переустановки может дать ошибки в модулях системы, которые используют данную базу.
	<p><a href="sys.php?op=regions_install"><b>Переустановить</b> регионы</a></div></center></div>';
}

function regions_install() {
	global $prefix, $db;
	include("ad-header.php");
	echo '<div style="background: #e2e5ea;"><div class="black_grad"><span class="h1">Шаг Первый</span></div>
	<center><div class=block style="width:800px; text-align:left;">
	<p><span class=h3>Выберите структуру для установки регионов.</span>
	<p>Значения <b>район-город</b>, <b>только районы</b>, <b>только города</b> будут действительны только для одной области, все остальные структуры можно использовать в нескольких необходимых вам регионов или во всех регионах, а также в одном отдельном регионе.
	<p>Если использовать только «Москва, Санкт-Петербург с поселками, а также Ненецкий автономный округ», то следует выбирать структуры во избежание дублирования: <b>область-город</b>, <b>область-район</b>, <b>только области</b>, <b>только районы</b>, <b>только города</b>.
	<form action="sys.php?op=regions_vibor" method="post">
	<p>Выберите структуру: <select name="stryktyra">
	<option value="1">область-район-город</option>
	<option value="2">область-город</option>
	<option value="3">область-район</option>
	<option value="4">район-город</option>
	<option value="5">только области</option>
	<option value="6">только районы</option>
	<option value="7">только города</option>
	</select>
	<p><input type="submit" value="Продолжить установку"></form>
	</div></center></div>';
}

function regions_vibor() {
	global $prefix, $db;
	$stryktyra=$_POST['stryktyra'];
	include("ad-header.php");
	// кладем выбранную структуру в конфиг
	$text = "<?php \$stryktyra = ".$stryktyra.";?>";
	$fp = fopen("includes/regions/config.php", "w"); // Открываем файл в режиме записи 
    fwrite($fp, $text); // Запись в файл
    fclose($fp); //Закрытие файла
	// выбираем регионы
	echo '<div style="background: #e2e5ea;"><div class="black_grad" style="height:45px;">
	<span class="h1">Шаг Второй</span></div>
	<center><div class=block style="width:800px; text-align:left;">
	<span class=h3>Выбор регионов</span><form action="sys.php?op=regions_addbase" method="post">
	<select name="ALLREGION" class="h1" onchange="if (this.value == 1) $(\'#reg_table\').hide(\'slow\'); else $(\'#reg_table\').show(\'slow\');">
	<option value="0">Выбирать по отдельности</option>
	<option value="1">Выбрать все</option></select><hr>
	<table width="100%" id=reg_table><tr valign=top><td>';
	$regions = array('Адыгея' => 'adigea','Алтай' => 'altai','Алтайский край' => 'altaik','Амурская область' => 'amyro','Архангельская область' => 'arxangel','Астраханская область' => 'astraxan','Агинский Бурятский авт. округ' => 'abao','Башкортостан' => 'bashk','Бурятия' => 'byryat','Белгородская область' => 'belgorod','Брянская область' => 'bryansk','Владимирская область' => 'vladimir','Волгоградская область' => 'volgograd','Вологодская область' => 'vologod','Воронежская область' => 'voronej','Дагестан' => 'dagestan','Еврейская автономная область' => 'eao','Забайкальский край' => 'zabaikal','Ивановская область' => 'ivan','Иркутская область' => 'irkytsk','Ингушетия' => 'ing','Кабардино-Балкария' => 'kabbal','Калмыкия' => 'Kalmic','Карачаево-Черкессия' => 'karchek','Карелия' => 'karelia','Коми' => 'komi','Коми-Пермяцкий авт. округ' => 'komipao','Красноярский край' => 'kryak','Краснодарский край' => 'krdk','Калининградская область' => 'kalinin','Калужская область' => 'kaluga','Камчатский край' => 'kamchat','Кемеровская область' => 'kemerovo','Кировская область' => 'kirov','Костромская область' => 'kostroma','Курганская область' => 'kyrgan','Курская область' => 'kyrsk','Ленинградская область' => 'leningrad','Липецкая область' => 'lipet','Марий Эл' => 'marij','Мордовия' => 'mordovia','Магаданская область' => 'magadan','Московская область' => 'mosko','<b>Москва</b>' => 'moskovg','Мурманская область' => 'myrm','Нижегородская область' => 'nijgorod','Новгородская область' => 'nowgorod','Новосибирская область' => 'novosib','Ненецкий авт. округ' => 'neneao','Омская область' => 'omsk','Оренбургская область' => 'orenbyrg','Орловская область' => 'orlov','Пензенская область' => 'penza','Пермский край' => 'permk','Псковская область' => 'pskov','Приморский край' => 'pmk','Ростовская область' => 'rostov','Рязанская область' => 'ryazan','<b>Санкт-Петербург</b>' => 'sp','Сахалинская область' => 'caxalin','Самарская область' => 'samara','Саратовская область' => 'saratov','Свердловская область' => 'sverdlovsk','Смоленская область' => 'smolen','Северная Осетия' => 'so','Ставропольский край' => 'stavropol','Тамбовская область' => 'tambov','Тверская область' => 'tver','Томская область' => 'tomsk','Тульская область' => 'tyla','Тюменская область' => 'tumen','Татарстан' => 'tatarstan','Тыва' => 'tiva','Ульяновская область' => 'ylyanov','Удмуртия' => 'ydmyrtia','Усть-Ордынский Бурятский авт. окру' => 'yorbao','Хакасия' => 'xakas','Хабаровский край' => 'xabk','Ханты-Мансийский авт. округ' => 'xmao','Чеченская Республика' => 'chr','Чувашия' => 'chyvas','Челябинская область' => 'chelyabinsk','Чукотский авт. округ' => 'chao','Ярославская область' => 'yaroslav','Ямало-Ненецкий авт. округ' => 'yanao','Якутия' => 'yakutia');
	$i=0;
	foreach ($regions as $name => $re) {
		$i++;
		echo '<select name="'.$re.'"><option value="0">Отключить</option><option value="1">Включить</option></select> '.$name.'<br>';
		if ($i==43) { echo '</td><td>'; $i=0; }
	}
	echo '</td></tr></table><button type="submit">Продолжить установку</button></form></div></center></div>';
}
function regions_addbase() {
	global $prefix, $db;
	include("includes/regions/config.php");	// из структуры определяем что необходимо
	if ($stryktyra == 1 || $stryktyra == 2 || $stryktyra == 3 || $stryktyra == 5) $obl = 1; // необходимы области
	if ($stryktyra == 1 || $stryktyra == 3 || $stryktyra == 4 || $stryktyra == 6) $raion = 1; // необходимы районы
	if ($stryktyra == 1 || $stryktyra == 2 || $stryktyra == 4 || $stryktyra == 7) $gorod = 1; // необходимы города
	$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_regions`;");
	$db->sql_query("CREATE TABLE `".$prefix."_regions` (`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT, `region_id` smallint(7) unsigned NOT NULL, `raion_id` smallint(6) unsigned NOT NULL, `name` varchar(40) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;");
	$regions = array('adigea','bashk','byryat','altai','dagestan','ing','kabbal','Kalmic','karchek','karelia','xmao','chao','yanao','komi','marij','mordovia','yakutia','so','tatarstan','tiva','ydmyrtia','xakas','chr','chyvas','altaik','krdk','pmk','stavropol','xabk','kryak','amyro','arxangel','astraxan','belgorod','bryansk','vladimir','volgograd','vologod','voronej','ivan','irkytsk','kalinin','kaluga','kamchat','kemerovo','kirov','kostroma','kyrgan','kyrsk','leningrad','lipet','magadan','mosko','myrm','nijgorod','nowgorod','novosib','omsk','orenbyrg','orlov','penza','permk','pskov','rostov','ryazan','samara','saratov','sverdlovsk','smolen','tambov','tver','tomsk','tyla','tumen','ylyanov','chelyabinsk','zabaikal','yaroslav','moskovg','sp','eao','abao','komipao','neneao','yorbao','caxalin');
	foreach ($regions as $re) {
		if ($_POST[$re] == '1') include("includes/regions/".$_POST[$re].".php");
	}
	include("ad-header.php");
	echo '<div style="background: #e2e5ea;"><div class="black_grad" style="height:45px;"><span class="h1">Шаг Третий</span></div><center><a href="sys.php?op=regions_menu" class=h1>Закончить установку</a></center>';
}

function regions_menu() {
    global $prefix, $db;
	include("includes/regions/config.php");
	$text = "";
	if ($stryktyra == 1 || $stryktyra == 2 || $stryktyra == 3 || $stryktyra == 5) { // необходимы области
		$result = $db->sql_query("select id, name from ".$prefix."_regions where region_id = '0' and raion_id = '0'");
		while ($row = $db->sql_fetchrow($result)) {
			$text .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
		}			
	}
	if ($stryktyra == 4 || $stryktyra == 6) { // необходимы районы
		$result = $db->sql_query("select id, name from ".$prefix."_regions where region_id != '0' and raion_id = '0'");
		while ($row = $db->sql_fetchrow($result)) {
			$text .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
		}
	}
	if ($stryktyra == 7) { // необходимы города
		$result = $db->sql_query("select id, name from ".$prefix."_regions where raion_id != '0'");
		while ($row = $db->sql_fetchrow($result)) {
			$text .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
		}
	}
	$fp = fopen("includes/regions/list.html", "w"); // Открываем файл в режиме записи 
    fwrite($fp, $text); // Запись в файл
    fclose($fp); //Закрытие файла
	Header("Location: sys.php?op=regions_main");

}
	switch ($op) {
	    case "regions_main":
		regions_main();
	    break;
		
		case "regions_install":
		regions_install();
	    break;
		
		case "regions_vibor":
		regions_vibor();
	    break;
		
		case "regions_addbase":
		regions_addbase();
	    break;
		
		case "regions_menu":
		regions_menu();
	    break;
	}
}
?>
