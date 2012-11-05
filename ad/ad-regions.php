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
	global $prefix, $db;
	include("ad-header.php");
			$result = $db->sql_query("SELECT count(*) as cnt FROM ".$prefix."_regions") or die('<center>База регионов не установленна, <a href="sys.php?op=regions_install">приступить к установке</a></center>');
while ($row = mysql_fetch_assoc($result)) { 
if ($row['cnt'] == 0) echo '<center>cуществует мнение что база регионов не установленна, <a href="sys.php?op=regions_install">приступить к установке</a></center>';
}
echo '<center>установленная база использует структуру:<h5>';
include("includes/regions/config.php");
if ($stryktyra ==1){echo 'область-район-город';}
if ($stryktyra ==2){echo 'область-город';}
if ($stryktyra ==3){echo 'область-район';}
if ($stryktyra ==4){echo 'район-город';}
if ($stryktyra ==5){echo 'только области';}
if ($stryktyra ==6){echo 'только районы';}
if ($stryktyra ==7){echo 'только города';}
echo '</h5>Форма выбора регионов:';
include("includes/regions/meny.html");
echo 'Вы можете переустанивить базу регионов,<br> при переустановке базы регионов не сокращайте структуру и обязательно используйте в новой базе регионы из прошлой,<br>
Несоблюдение правил переустановки может дать ошибки в модулях системы которые используют данную базу<br> <a href="sys.php?op=regions_install">переустановить</a></center>';
}
function regions_install() {
	global $prefix, $db;
	include("ad-header.php");
	echo '<center><h5>Шаг 1</h5>Выберите необходимую структуру для установки регионов<br>
	значения:
    <h5>район-город</h5>
    <h5>только районы</h5>
    <h5>только города</h5>
    будут действительны только для одной области,<br> все остальные структуры можно использовать в нескольких необходимых вам регионов или во всех регионах, а также в одном отдельном регионе<br>
	Если использовать только Москва, Санкт-Петербург с поселками а также Ненецкий автономный округ то следует выбирать структуры во избежания дублирования:
	<h5>область-город</h5>
	<h5>область-район</h5>
	<h5>только области</h5>
    <h5>только районы</h5>
    <h5>только города</h5>
	Выбрать структуру:
	<form action="sys.php?op=regions_vibor"  method="post">
	<select name="stryktyra">
	<option value="1">область-район-город</option>
	<option value="2">область-город</option>
	<option value="3">область-район</option>
	<option value="4">район-город</option>
	<option value="5">только области</option>
	<option value="6">только районы</option>
	<option value="7">только города</option>
	</select>
	<input type="submit" value="Продолжить установку"></form>
  </center>';
}
function regions_vibor() {
	global $prefix, $db;
	$stryktyra=$_POST['stryktyra'];
	include("ad-header.php");
	
	// ложим выбранную структуру в конфиг
	$text = "<?php \$stryktyra = ".$stryktyra.";?>";
	$fp = fopen("includes/regions/config.php", "w"); // Открываем файл в режиме записи 
    fwrite($fp, $text); // Запись в файл
    fclose($fp); //Закрытие файла
	// выбираем регионы
?>	<script type="text/javascript">$(document).ready(function() {
	
	/* see if anything is previously checked and reflect that in the view*/
	$(".checklist input:checked").parent().addClass("selected");
	
	/* handle the user selections */
	$(".checklist .checkbox-select").click(
		function(event) {
			event.preventDefault();
			$(this).parent().addClass("selected");
			$(this).parent().find(":checkbox").attr("checked","checked");
		}
	);
	
	$(".checklist .checkbox-deselect").click(
		function(event) {
			event.preventDefault();
			$(this).parent().removeClass("selected");
			$(this).parent().find(":checkbox").removeAttr("checked");
		}
	);
	
});</script>
<style type="text/css">
form {
	margin: 0 0 30px 0;
}

legend {
	font-size: 17px;
}

fieldset {
	border: 0;
}

.checklist {
	list-style: none;
	margin: 0;
	padding: 0;
}

.checklist li {
	float: left;
	margin-right: 10px;
	background: url(includes/regions/checkboxbg.gif) no-repeat 0 0;
	width: 105px;
	height: 150px;
	position: relative;
	font: normal 11px/1.3 "Lucida Grande","Lucida","Arial",Sans-serif;
}

.checklist li.selected {
	background-position: -105px 0;
}

.checklist li.selected .checkbox-select {
	display: none;
}

.checkbox-select {
	display: block;
	float: left;
	position: absolute;
	top: 118px;
	left: 10px;
	width: 85px;
	height: 23px;
	background: url(includes/regions/select.gif) no-repeat 0 0;
	text-indent: -9999px;
}

.checklist li input {
	display: none;	
}

a.checkbox-deselect {
	display: none;
	color: white;
	font-weight: bold;
	text-decoration: none;
	position: absolute;
	top: 120px;
	right: 10px;
}

.checklist li.selected a.checkbox-deselect {
	display: block;
}

.checklist li label {
	display: block;
	text-align: center;
	padding: 8px;
}

</style>

<?php
	echo '<center><h5>Шаг 2</h5><h5>Выбор регионов</h5><form action="sys.php?op=regions_addbase" method="post">
		<fieldset>
			<ul class="checklist">
				<li>
					<input name="adigea" value="1" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Адыгея</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="bashk" value="9" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Башкортостан</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="byryat" value="64" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Бурятия</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="altai" value="88" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Алтай</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="dagestan" value="100" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Дагестан</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="ing" value="143" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Ингушетия</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="kabbal" value="148" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Кабардино-Балкария</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="Kalmic" value="159" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Калмыкия</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="karchek" value="174" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Карачаево-Черкессия</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="karelia" value="185" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Карелия</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="xmao" value="1970" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Ханты-Мансийский автономный округ</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="chao" value="1981" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Чукотский автономный округ</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="yanao" value="1990" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Ямало-Ненецкий автономный округ</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="komi" value="201" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Коми</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="marij" value="221" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Марий Эл</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="mordovia" value="236" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Мордовия</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="yakutia" value="259" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Якутия</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="so" value="294" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Северная Осетия</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="tatarstan" value="304" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Татарстан</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="tiva" value="349" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Тыва</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="ydmyrtia" value="367" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Удмуртия</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="xakas" value="393" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Хакасия</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="chr" value="402" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Чеченская Республика</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="chyvas" value="419" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Чувашия</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="altaik" value="441" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Алтайский край</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="krdk" value="502" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Краснодарский край</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="pmk" value="592" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Приморский край</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="stavropol" value="618" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Ставропольский край</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="xabk" value="645" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Хабаровский край</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="kryak" value="542" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Красноярский край</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="amyro" value="663" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Амурская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="arxangel" value="684" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Архангельская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="astraxan" value="705" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Астраханская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="belgorod" value="717" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Белгородская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="bryansk" value="739" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Брянская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="vladimir" value="767" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Владимирская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="volgograd" value="784" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Волгоградская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="vologod" value="818" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Вологодская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="voronej" value="845" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Воронежская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="ivan" value="878" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Ивановская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="irkytsk" value="900" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Иркутская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="kalinin" value="928" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Калининградская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="kaluga" value="942" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Калужская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="kamchat" value="967" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Камчатский край</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="kemerovo" value="980" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Кемеровская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="kirov" value="999" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Кировская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="kostroma" value="1040" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Костромская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="kyrgan" value="1065" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Курганская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="kyrsk" value="1090" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Курская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="leningrad" value="1119" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Ленинградская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="lipet" value="1137" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Липецкая область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="magadan" value="1156" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Магаданская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="mosko" value="1165" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Московская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="myrm" value="1205" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Мурманская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="nijgorod" value="1211" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Нижегородская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="nowgorod" value="1261" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Новгородская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="novosib" value="1284" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Новосибирская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="omsk" value="1315" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Омская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="orenbyrg" value="1348" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Оренбургская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="orlov" value="1384" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Орловская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="penza" value="1409" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Пензенская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="permk" value="1439" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Пермский край</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="pskov" value="1474" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Псковская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="rostov" value="1500" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Ростовская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="ryazan" value="1544" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Рязанская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="samara" value="1570" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Самарская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="saratov" value="1598" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Саратовская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="sverdlovsk" value="1655" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Свердловская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="smolen" value="1688" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Смоленская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="tambov" value="1714" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Тамбовская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="tver" value="1738" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Тверская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="tomsk" value="1775" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Томская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="tyla" value="1792" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Тульская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="tumen" value="1816" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Тюменская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="ylyanov" value="1839" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Ульяновская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="chelyabinsk" value="1861" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Челябинская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="zabaikal" value="1889" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Забайкальский край</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="yaroslav" value="1919" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Ярославская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="moskovg" value="1937" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Москва, г</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="sp" value="1939" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Санкт-Петербург</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="eao" value="1942" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Еврейская автономная область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
				<li>
					<input name="abao" value="1948" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Агинский Бурятский автономный округ</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="komipao" value="1953" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Коми-Пермяцкий автономный округ</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="neneao" value="1961" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Ненецкий автономный округ</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="yorbao" value="1962" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Усть-Ордынский Бурятский автономный окру</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li><li>
					<input name="caxalin" value="1637" type="checkbox" id="choice_a"/>
					<label for="choice_a"><h3>Сахалинская область</h3></label>
					<a class="checkbox-select" href="#">Выбрать</a>
					<a class="checkbox-deselect" href="#">Отменить</a>
				</li>
			</ul>
			<div style="clear: both;"></div>
			<button type="submit">Продолжить установку</button>
		</fieldset>
	</form>
	</center>';
}
function regions_addbase() {
	global $prefix, $db;
	// из структуры определяем что необходимо
	include("includes/regions/config.php");
	if ($stryktyra == 1 || $stryktyra == 2 || $stryktyra == 3 || $stryktyra == 5) { // необходимы области
	$obl = 1;
	}
	if ($stryktyra == 1 || $stryktyra == 3 || $stryktyra == 4 || $stryktyra == 6) { // необходимы районы
	$raion = 1;
	}
	if ($stryktyra == 1 || $stryktyra == 2 || $stryktyra == 4 || $stryktyra == 7) { // необходимы города
	$gorod = 1;
	}
	$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_regions`;");
	$db->sql_query("CREATE TABLE `".$prefix."_regions` (
`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `region_id` smallint(7) unsigned NOT NULL,
  `raion_id` smallint(6) unsigned NOT NULL,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=201898 ;");
    if ($_POST['adigea'] ==1 ) { include("includes/regions/".$_POST['adigea'].".php");
	} if ($_POST['bashk'] ==9) { include("includes/regions/".$_POST['bashk'].".php");
	} if ($_POST['byryat'] ==64) { include("includes/regions/".$_POST['byryat'].".php");
	} if ($_POST['altai'] ==88) { include("includes/regions/".$_POST['altai'].".php");
	} if ($_POST['dagestan'] ==100) { include("includes/regions/".$_POST['dagestan'].".php");
	} if ($_POST['ing'] ==143) { include("includes/regions/".$_POST['ing'].".php");
	} if ($_POST['kabbal'] ==148) { include("includes/regions/".$_POST['kabbal'].".php");
	} if ($_POST['Kalmic'] ==159) { include("includes/regions/".$_POST['Kalmic'].".php");
	} if ($_POST['karchek'] ==174) { include("includes/regions/".$_POST['karchek'].".php");
	} if ($_POST['karelia'] ==185) { include("includes/regions/".$_POST['karelia'].".php");
	} if ($_POST['xmao'] ==1970) { include("includes/regions/".$_POST['xmao'].".php");
	} if ($_POST['chao'] ==1981) { include("includes/regions/".$_POST['chao'].".php");
	} if ($_POST['yanao'] ==1990) { include("includes/regions/".$_POST['yanao'].".php");
	} if ($_POST['komi'] ==201) { include("includes/regions/".$_POST['komi'].".php");
	} if ($_POST['marij'] ==221) { include("includes/regions/".$_POST['marij'].".php");
	} if ($_POST['mordovia'] ==236) { include("includes/regions/".$_POST['mordovia'].".php");
	} if ($_POST['yakutia'] ==259) { include("includes/regions/".$_POST['yakutia'].".php");
	} if ($_POST['so'] ==294) { include("includes/regions/".$_POST['so'].".php");
	} if ($_POST['tatarstan'] ==304) { include("includes/regions/".$_POST['tatarstan'].".php");
	} if ($_POST['tiva'] ==349) { include("includes/regions/".$_POST['tiva'].".php");
	} if ($_POST['ydmyrtia'] ==367) { include("includes/regions/".$_POST['ydmyrtia'].".php");
	} if ($_POST['xakas'] ==393) { include("includes/regions/".$_POST['xakas'].".php");
	} if ($_POST['chr'] ==402) { include("includes/regions/".$_POST['chr'].".php");
	} if ($_POST['chyvas'] ==419) { include("includes/regions/".$_POST['chyvas'].".php");
	} if ($_POST['altaik'] ==441) { include("includes/regions/".$_POST['altaik'].".php");
	} if ($_POST['krdk'] ==502) { include("includes/regions/".$_POST['krdk'].".php");
	} if ($_POST['pmk'] ==592) { include("includes/regions/".$_POST['pmk'].".php");
	} if ($_POST['stavropol'] ==618) { include("includes/regions/".$_POST['stavropol'].".php");
	} if ($_POST['xabk'] ==645) { include("includes/regions/".$_POST['xabk'].".php");
	} if ($_POST['kryak'] ==542) { include("includes/regions/".$_POST['kryak'].".php");
	} if ($_POST['amyro'] ==663) { include("includes/regions/".$_POST['amyro'].".php");
	} if ($_POST['arxangel'] ==684) { include("includes/regions/".$_POST['arxangel'].".php");
	} if ($_POST['astraxan'] ==705) { include("includes/regions/".$_POST['astraxan'].".php");
	} if ($_POST['belgorod'] ==717) { include("includes/regions/".$_POST['belgorod'].".php");
	} if ($_POST['bryansk'] ==739) { include("includes/regions/".$_POST['bryansk'].".php");
	} if ($_POST['vladimir'] ==767) { include("includes/regions/".$_POST['vladimir'].".php");
	} if ($_POST['volgograd'] ==784) { include("includes/regions/".$_POST['volgograd'].".php");
	} if ($_POST['vologod'] ==818) { include("includes/regions/".$_POST['vologod'].".php");
	} if ($_POST['voronej'] ==845) { include("includes/regions/".$_POST['voronej'].".php");
	} if ($_POST['ivan'] ==878) { include("includes/regions/".$_POST['ivan'].".php");
	} if ($_POST['irkytsk'] ==900) { include("includes/regions/".$_POST['irkytsk'].".php");
	} if ($_POST['kalinin'] ==928) { include("includes/regions/".$_POST['kalinin'].".php");
	} if ($_POST['kaluga'] ==942) { include("includes/regions/".$_POST['kaluga'].".php");
	} if ($_POST['kamchat'] ==967) { include("includes/regions/".$_POST['kamchat'].".php");
	} if ($_POST['kemerovo'] ==980) { include("includes/regions/".$_POST['kemerovo'].".php");
	} if ($_POST['kirov'] ==999) { include("includes/regions/".$_POST['kirov'].".php");
	} if ($_POST['kostroma'] ==1040) { include("includes/regions/".$_POST['kostroma'].".php");
	} if ($_POST['kyrgan'] ==1065) { include("includes/regions/".$_POST['kyrgan'].".php");
	} if ($_POST['kyrsk'] ==1090) { include("includes/regions/".$_POST['kyrsk'].".php");
	} if ($_POST['leningrad'] ==1119) { include("includes/regions/".$_POST['adigea'].".php");
	} if ($_POST['lipet'] ==1137) { include("includes/regions/".$_POST['leningrad'].".php");
	 } if ($_POST['magadan'] ==1156) { include("includes/regions/".$_POST['magadan'].".php");
	} if ($_POST['mosko'] ==1165) { include("includes/regions/".$_POST['mosko'].".php");
	} if ($_POST['myrm'] ==1205) { include("includes/regions/".$_POST['myrm'].".php");
	} if ($_POST['nijgorod'] ==1211) { include("includes/regions/".$_POST['nijgorod'].".php");
	} if ($_POST['nowgorod'] ==1261) { include("includes/regions/".$_POST['nowgorod'].".php");
	} if ($_POST['novosib'] ==1284) { include("includes/regions/".$_POST['novosib'].".php");
	} if ($_POST['omsk'] ==1315) { include("includes/regions/".$_POST['omsk'].".php");
	} if ($_POST['orenbyrg'] ==1348) { include("includes/regions/".$_POST['orenbyrg'].".php");
	} if ($_POST['orlov'] ==1384) { include("includes/regions/".$_POST['orlov'].".php");
	} if ($_POST['penza'] ==1409) { include("includes/regions/".$_POST['penza'].".php");
	} if ($_POST['permk'] ==1439) { include("includes/regions/".$_POST['permk'].".php");
	} if ($_POST['pskov'] ==1474) { include("includes/regions/".$_POST['pskov'].".php");
	} if ($_POST['rostov'] ==1500) { include("includes/regions/".$_POST['rostov'].".php");
	} if ($_POST['ryazan'] ==1544) { include("includes/regions/".$_POST['ryazan'].".php");
	} if ($_POST['samara'] ==1570) { include("includes/regions/".$_POST['samara'].".php");
	} if ($_POST['saratov'] ==1598) { include("includes/regions/".$_POST['saratov'].".php");
	} if ($_POST['sverdlovsk'] ==1655) { include("includes/regions/".$_POST['sverdlovsk'].".php");
	} if ($_POST['smolen'] ==1688) { include("includes/regions/".$_POST['smolen'].".php");
	} if ($_POST['tambov'] ==1714) { include("includes/regions/".$_POST['tambov'].".php");
	} if ($_POST['tver'] ==1738) { include("includes/regions/".$_POST['tver'].".php");
	} if ($_POST['tomsk'] ==1775) { include("includes/regions/".$_POST['tomsk'].".php");
	} if ($_POST['tyla'] ==1792) { include("includes/regions/".$_POST['tyla'].".php");
	} if ($_POST['tumen'] ==1816) { include("includes/regions/".$_POST['tumen'].".php");
	} if ($_POST['ylyanov'] ==1839) { include("includes/regions/".$_POST['ylyanov'].".php");
	} if ($_POST['chelyabinsk'] ==1861) { include("includes/regions/".$_POST['chelyabinsk'].".php");
	} if ($_POST['zabaikal'] ==1889) { include("includes/regions/".$_POST['zabaikal'].".php");
	} if ($_POST['yaroslav'] ==1919) { include("includes/regions/".$_POST['yaroslav'].".php");
	} if ($_POST['moskovg'] ==1937) { include("includes/regions/".$_POST['moskovg'].".php");
	} if ($_POST['sp'] ==1939) { include("includes/regions/".$_POST['sp'].".php");
	} if ($_POST['eao'] ==1942) { include("includes/regions/".$_POST['eao'].".php");
	 } if ($_POST['abao'] ==1948) { include("includes/regions/".$_POST['abao'].".php");
	} if ($_POST['komipao'] ==1953) { include("includes/regions/".$_POST['komipao'].".php");
	} if ($_POST['neneao'] ==1961) { include("includes/regions/".$_POST['neneao'].".php");
	} if ($_POST['yorbao'] ==1962) { include("includes/regions/".$_POST['yorbao'].".php");
	} if ($_POST['caxalin'] ==1637) { include("includes/regions/".$_POST['caxalin'].".php");}
	include("ad-header.php");
	   echo '<center><h5>Шаг 3</h5>создание меню выбора<br> <a href="sys.php?op=regions_menu">Создать меню и закончить установку</a></center>';
	
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
