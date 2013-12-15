<?php
if (!defined('ADMIN_FILE')) {
	die ("Доступ закрыт!");
}
global $prefix, $db, $admin_file;
$aid = substr($aid, 0,25);
$row = $db->sql_fetchrow($db->sql_query("SELECT `realadmin` FROM ".$prefix."_authors WHERE `aid`='".$aid."'"));
if ($row['realadmin'] == 1) {

	function main_ban($ip_address=0) {
		global $prefix, $db, $bgcolor2, $admin_file;
		echo "<h2>Блокировка посетителей (запрет использования сайта)</h2>";
		$numrows = $db->sql_numrows($db->sql_query("SELECT * from ".$prefix."_banned_ip"));
		if ($numrows != 0) {
			echo "<a class='button medium' onclick=\"$('#show_stop_users').toggle();\"><span class=\"icon gray medium\" data-icon=\"U\"></span> Заблокированные по IP-адресу посетители: ".$numrows."</a><div id='show_stop_users' class='hide'>"
			."<table class='table_light w100'>"
			."<tr><td bgcolor='".$bgcolor2."' align='left'>IP-адрес</td>"
			."<td bgcolor='".$bgcolor2."' align='left'>Причина блокировки</td>"
			."<td bgcolor='".$bgcolor2."' align='center'>Дата запрета</td>"
			."<td bgcolor='".$bgcolor2."' align='center'>Функции</td></tr>";
			$result = $db->sql_query("SELECT * from ".$prefix."_banned_ip ORDER by date DESC");
			while ($row = $db->sql_fetchrow($result)) {
				$row['reason'] = filter($row['reason'], "nohtml");
				echo "<tr><td bgcolor='".$bgcolor2."' align='left'>".$row['ip_address']."</td>"
				."<td bgcolor='".$bgcolor2."'>".$row['reason']."&nbsp;</td>"
				."<td bgcolor='".$bgcolor2."' align='center' nowrap>".date2normal_view($row['date'])."&nbsp;</td>"
				."<td bgcolor='".$bgcolor2."' align='center'><a href='".$admin_file.".php?op=ipban_edit&amp;id=".intval($row['id'])."' title='Редактировать'><span class=\"icon black small\" data-icon=\"7\"></span></a>&nbsp;<a href='".$admin_file.".php?op=ipban_delete&amp;id=".intval($row['id'])."' title='Снять запрет'><span class=\"icon red small\" data-icon=\"F\"></span></a>&nbsp;</td></tr>";
			}
			echo "</table><br><br></div>";
		}
		echo "<form action='".$admin_file.".php' method='post'>
		<table><tr><td>IP-адрес пользователя:</td><td>Причина блокировки:</td></tr><tr><td>";
		if ($ip_address == 0) $ip_address = "";
		echo "<input type='text' class='polosa' name='ip_address' value='".$ip_address."'>";
		echo "</td><td><input type='text' class='polosa' name='reason' size='20' maxlength='255'> <input type='submit' value='Запретить'><input type='hidden' name='op' value='ipban_save'><input type='hidden' name='id' value='0'>
		</td></tr></table></form>";
	}

	function ipban_delete($id) {
		global $prefix, $db, $admin_file;
		$id = intval($id);
		$db->sql_query("DELETE FROM ".$prefix."_banned_ip WHERE id=".$id);
		Header("Location: ".$admin_file.".php?op=options");
	}

	function ipban_edit($id) {
		global $prefix, $db, $bgcolor2, $admin_file;
		$id = intval($id);
		$row = $db->sql_fetchrow($db->sql_query("SELECT * from ".$prefix."_banned_ip WHERE id='".$id."'"));
		include ("ad/ad-header.php");
		echo "<h3>Редактирование блокировки пользователей</h3><b>IP-адрес:</b><br>";
		echo "<form action='".$admin_file.".php' method='post'>";
		$ip_address = $row['ip_address'];
		$reason = filter($row['reason'], "nohtml");
		echo "<input type='text' class='polosa' name='ip_address' value='".$ip_address."'>";
		echo "<br><br><b>Причина запрета:</b><br><input type='text' class=polosa name='reason' size='50' maxlength='255' value='".$reason."'><br><br>";
		echo "<input type='hidden' name='id' value='".$id."'><input type='hidden' name='op' value='ipban_save'>";
		echo "<input type='submit' value='Сохранить изменения'><br></center>";
		echo "</form>";
		admin_footer();
	}

	function ipban_save($id=0, $ip_address, $reason) {
		global $prefix, $db, $admin_file;
		include ("ad/ad-header.php");
		$id = intval($id);
		if ($ip_address == "127.0.0.1") die("<p class='center'><b>Ошибка: </b> Введенный адрес неправильный: введен адрес вашего компьютера, а не сервера в Интернете! <b>127.0.0.1</b><br><br>Вернуться</p>");
		if ($ip_address == $_SERVER["REMOTE_ADDR"]) die("<p class='center'><b>Ошибка: </b> Введенный адрес неправильный: введен ваш сетевой адрес! <b>".$ip_address."</b><br><br>Вернуться</p>");
		$reason = filter($reason, "nohtml");
		$date = date("Y-m-d");
		if ($id==0) $db->sql_query("INSERT INTO ".$prefix."_banned_ip VALUES (NULL, '".$ip_address."', '".mysql_real_escape_string($reason)."', '".$date."')");
		else $db->sql_query("UPDATE ".$prefix."_banned_ip SET `ip_address`='".$ip_address."', `reason`='".mysql_real_escape_string($reason)."' WHERE `id`='".$id."'");
		Header("Location: ".$admin_file.".php?op=options");
	}
	///////////////////////////////////////////////////////////////////////////////////////
	function updateadmin($chng_aid, $chng_name, $chng_pwd, $chng_pwd2, $adm_aid) {
		global $siteurl, $admin, $prefix, $db, $admin_file;
			$chng_aid = trim($chng_aid);
			if (!($chng_aid && $chng_name)) {
				Header("Location: ".$admin_file.".php?op=options");
			}
			if (!empty($chng_pwd2)) {
				if($chng_pwd != $chng_pwd2) {
					include("ad/ad-header.php");
					echo "Первый пароль не соответствует второму. Вернитесь назад.<br>";
					admin_footer();
					exit;
				}
				$chng_pwd = md5($chng_pwd);
				$chng_aid = strtolower(substr($chng_aid, 0,25));
				$db->sql_query("update ".$prefix."_authors set aid='".$chng_aid."', pwd='".$chng_pwd."' where name='".$chng_name."' AND aid='".$adm_aid."'");
				Header("Location: ".$admin_file.".php?op=options");
			} else {
				$db->sql_query("update ".$prefix."_authors set aid='".$chng_aid."' where name='".$chng_name."' AND aid='".$adm_aid."'");
				Header("Location: ".$admin_file.".php?op=options");
			}
	}
////////////////////////////////////////////////
	function options($ok=0) {
		global $prefix, $db, $admin_file, $siteurl, $admin, $ipban;
		include ("ad/ad-header.php");
		$ok = intval($ok);
		// Получаем настройки из mainfile
		global $sitename, $startdate, $adminmail, $keywords, $description, $counter, $statlink, $postlink, $stopcopy, $registr, $pogoda, $flash, $sgatie, $ht_backup, $captcha_ok, $xnocashe, $jqueryui, $show_comments, $show_userposts, $show_page, $show_reserv, $uskorenie_blokov, $kickstart, $show_page_links, $ad_fon, $comment_send, $company_name, $company_fullname, $company_address, $company_time, $company_tel, $company_sot, $company_fax, $company_email, $company_map, $company_people, $search_design, $tag_design, $add_fonts, $site_cash, $normalize, $project_logotip, $project_name, $geo, $kolkey, $add_clips, $add_mail_shablons, $sortable, $color_tema_html, $color_tema_css, $color_tema_js, $color_tema_php, $tab_obzor, $tab_show, $shop_text_val1, $shop_text_val2, $shop_text_itogo, $shop_text_oformit, $shop_text_korzina, $shop_text_delete, $shop_pole, $shop_admin_mail, $shop_text_after_mail,$shop_spisok_pole, $shop_shablon_form_order, $shop_shablon_mail_client, $shop_shablon_mail_admin, $head_insert, $filter_name, $filter_show_all, $gravatar, $strelka, $smile_icons, $avtor_comments, $search_in_pages, $search_in_papka, $search_in_razdel, $newsmail_design, $search_col_razdel, $search_col_papka, $search_col_page, $search_col_showall, $scrollyeah, $lightload, $spin, $razdel_sort, $show_admin_top;
		
		$fon_colors = array('#ffffff','#e1e1e1','#cccccc','#b3b3b3','#9a9a9a','#666666','#333333','#000000','#d8f8c4','#ccf4b4','#c4fccc','#c4fccc','#fcfcac','#fcfca4','#fcf9c7','#fce4ac','#fce4d4','#fcccbc','#fcd4dc','#fcccd4','#ddf3fe','#cdeeff','#defff8','#cafff3','#dcdcfc','#ccccfc','#ecdcfc','#ecccfc','#82317a');
		$ad_fon_option = ""; // Выбор фоновок для админки
		for ($i=0; $i < count($fon_colors); $i++) {
			if ($ad_fon == $fon_colors[$i]) $sel = " selected> → ←"; else $sel = ">";
			$ad_fon_option .= "<option style='background: ".$fon_colors[$i].";' value='".$fon_colors[$i]."'".$sel." </option>";
		}

		$titles_design = titles_design(); // Выборка дизайнов
		$id_designs = array();
		$title_designs = array();
		foreach ($titles_design as $id_design => $title_design) {
			$id_designs[] = $id_design;
			$title_designs[] = str_replace(",","[|]",$title_design);
		}
		$id_designs = implode(",",$id_designs);
		$title_designs = implode(",",$title_designs);

$opt_save = ":";
if ($ok==1) $opt_save = " сохранены";
// mainrazdel0
echo "<table class='w100 mw800 pm0 block_back'><tr valign=top><td id='razdel_td' class='radius nothing'>
<form action='".$admin_file.".php' method='post' name='form'>
	<div id='razdels' style='width:340px;'>";
	echo "<div id='mainrazdel8' class='dark_pole2sel'><a class='base_page' onclick=\"options_show('8','show_first')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='.'></span><span class='plus20'>Начальные настройки</span></div></a></div>";
	echo "<div id='mainrazdel1' class='dark_pole2'><a class='base_page' onclick=\"options_show('1','show_options_company')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='Y'></span><span class='plus20'>Карточка компании (мини блоки)</span></div></a></div>";
	echo "<div id='mainrazdel3' class='dark_pole2'><a class='base_page' onclick=\"options_show('3','show_options_adspeed')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='z'></span><span class='plus20'>Администрирование</span></div></a></div>";
	echo "<div id='mainrazdel5' class='dark_pole2'><a class='base_page' onclick=\"options_show('5','show_options_zagotovka')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='7'></span><span class='plus20'>Редактор</span></div></a></div>";
	echo "<div id='mainrazdel15' class='dark_pole2'>".select("options[show_comments]", "0,1", "НЕТ,ДА", $show_comments,'','right3')."<a class='base_page' onclick=\"options_show('15','show_options_comments')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon=\"'\"></span><span class='plus20'>Комментарии</span> </div></a></div>";
	echo "<div id='mainrazdel12' class='dark_pole2'><a class='base_page' onclick=\"options_show('12','show_options_fonts')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='i'></span><span class='plus20'>Шрифты</span></div></a></div>";
	echo "<div id='mainrazdel13' class='dark_pole2'><a class='base_page' onclick=\"options_show('13','show_shop')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='$'></span><span class='plus20'>Магазин</span></div></a></div>";
	echo "<br>";
	echo "<div id='mainrazdel4' class='dark_pole2'><a class='base_page' onclick=\"options_show('4','show_options_pass_block')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='O'></span><span class='plus20'>Смена пароля и Блокировка по IP</span></div></a></div>";
	echo "<div id='mainrazdel7' class='dark_pole2'><a class='base_page' onclick=\"options_show('7','show_options_oldfotos'); trash_pics();\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='1'></span><span class='plus20'>Удаление старых фото</span></div></a></div>";
	echo "<div id='mainrazdel5' class='dark_pole2'><a class='base_page' href='sys.php?op=subscribe'><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='@'></span><span class='plus20'>Cписок адресатов для рассылки</span></div></a></div>";
	echo "<div id='mainrazdel6' class='dark_pole2'><a class='base_page' href='sys.php?op=users'><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='U'></span><span class='plus20'>Пользователи (в разработке)</span></div></a></div>";
    echo "<div id='mainrazdel11' class='dark_pole2'><a class='base_page' href='sys.php?op=txt_and_csv_main'><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='('></span><span class='plus20'>Импорт из Excel (в формате .csv)</span></div></a></div>";
	echo "<div id='mainrazdel10' class='dark_pole2'><a class='base_page' onclick=\"options_show('10','show_options_razrab');\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='b'></span><span class='plus20'>Информация для разработчиков</span></div></a></div>";
	if (is_dir("includes/regions")) echo "<div id='mainrazdel14' class='dark_pole2'><a class='base_page' href='sys.php?op=regions_main'><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='&'></span><span class='plus20'>Регионы</span></div></a></div>";
	echo "</div></td>
	<td style='padding:0;'><a class='punkt' title='Свернуть/развернуть левую колонку' onmousemove='$(\"#razdels\").show();' onclick='$(\"#razdels\").toggle(\"slow\");'><div class='polosa_razdelitel'><div id='rotateText'><nobr>↑ Сворачивает Настройки ↑</nobr></div></div></a></td>
	<td class='w100 p0'>";

echo "<div class='black_grad p0'>
<button id='save_options' type='submit' class='small green' style='float:left; margin:3px;'><span class='mr-2 icon white medium' data-icon='c'></span>Сохранить</button>
<span class='h1'>Настройки".$opt_save."</span>
</div>

<div id='show_options_razrab' class='show_pole pl10' style='display:none;'>";

$phpversion = preg_replace('/[a-z-]/', '', phpversion());
if ($phpversion{0}<4) die ('Версия PHP ниже плинтуса. Где же ты нарыл такое старьё?! 0_о');
if ($phpversion{0}==4) die ('Версия PHP — 4. Попросите хостинг-компанию установить PHP как минимум версии 5.2.1, желательно 5.4.10');
if ($phpversion{0}==5 && $phpversion{2}<4) 
	echo "<p style='color:red;'>Версия PHP — 5.".$phpversion{2}.". Рекомендуется использовать PHP версии 5.4.10, минимум — 5.2.1";
if ( ( $phpversion{0}==5 && $phpversion{2}>4 ) || $phpversion{0}>5) 
	echo "<p style='color:red;'>Версия PHP — 5.".$phpversion{2}.".<br>На 5.5 (и выше) CMS полноценно не тестировалась — вы можете попробовать и передать разработчику все возникшие ошибки или замечания.";
if (!function_exists('curl_init')) 
	echo "<p style='color:red;'>Желательно включить поддержку cURL на вашем хостинге.";
if (!extension_loaded('imagick') || !class_exists("Imagick")) 
	echo "<p style='color:red;'>Библиотека Imagick не установлена – вам придется самостоятельно уменьшать размер больших фотографий (полученных фотоаппаратом) перед вставкой в редактор. Советуем перейти на другой хостинг с поддержкой этой библиотеки или договориться с текущим хостингом о её подключении.";

echo "<li><a href='http://hotel-s.ru' target='_blank'>Официальный сайт CMS «ДвижОк»</a>
<li><a href='http://translate.google.com/manager/website/add' target='_blank'>Переводчик сайтов</a>
<li><a href='http://uptolike.ru' target='_blank'>Большие удобные настраиваемые социальные кнопки</a>


<h2>Внутреннее устройство CMS «ДвижОк»</h2>
<img src='images/shema.png' style='width:100%; max-width:860px;'>
</div>	

<div id='show_first' class='show_pole pl10'>";
	
echo "<table class='table_light'>
<tr valign='top'><td style='min-width:250px;'>
Включить <a href='http://necolas.github.com/normalize.css/' target='_blank'>normalize.css</a>:</td><td class='small'>
".select("options[normalize]", "0,1", "НЕТ,ДА", $normalize)."
Нормализация отличается от подхода reset.css тем, что выравнивает различия стандартных стилей разных браузеров и пытается нейтрализовать баги, не сбрасывая при этом самих стандартных стилей.
</td></tr>

<tr valign=top><td style='min-width:250px;'>
<b>Включить CSS-фреймворк</b>:<br>";

global $kickstart;
switch($kickstart) {
	case 1:
	$kick_link = "http://www.99lime.com/elements/";
	$kick_name = "KickStart"; break;
	case 2:
	$kick_link = "http://css-framework.ru/doc/utilites/";
	$kick_name = "CSSframework"; break;
	case 3:
	$kick_link = "http://www.getskeleton.com/#examples";
	$kick_name = "Skeleton"; break;
	case 4:
	$kick_link = "http://kubeframework.com";
	$kick_name = "Kube"; break;
	case 5:
	$kick_link = "http://twitter.github.com/bootstrap/base-css.html";
	$kick_name = "Bootstrap"; break;
	case 6:
	$kick_link = "http://cssgrid.net";
	$kick_name = "1140 Grid"; break;
	case 7:
	$kick_link = "http://daneden.me/toast/";
	$kick_name = "Toast"; break;
	case 8:
	$kick_link = "http://www.blueprintcss.org/tests/";
	$kick_name = "Blueprint"; break;
	case 9:
	$kick_link = "http://yuilibrary.com/yui/docs/cssgrids/";
	$kick_name = "YUI"; break;
	case 10:
	$kick_link = "http://960.gs";
	$kick_name = "960gs"; break;
	case 11:
	$kick_link = "http://960.gs";
	$kick_name = "960gs(24)"; break;
}
if ($kickstart != 0) echo " <a target='_blank' class='button small' href='".$kick_link."' title='Открыть сайт CSS-фреймворка «".$kick_name."»'><span class='icon small black' data-icon='S'></span> ".$kick_name."</a>";

echo "</td><td class=small>
".select("options[kickstart]", "0,1,2,3,4,5,6,7,8,9,10,11", "- НЕТ -,KickStart,CSSframework,Skeleton,Kube,Bootstrap,1140 Grid,Toast,Blueprint,YUI CSS Grids,960gs (12 и/или 16 колонок),960gs (24 колонки)", $kickstart, ' id=kickstart onchange="if ( $(\'#kickstart\').val() == 1) $(\'#frame1\').show(); else $(\'#frame1\').hide(); if ( $(\'#kickstart\').val() == 6) $(\'#frame6\').show(); else $(\'#frame6\').hide(); "')."
<br>Ссылка на сайт фреймворка (для просмотра правил оформления CSS и HTML) появится слева после сохранения.
<pre id='frame1' style='display:none;'>
LightBox отключен, включен FancyBox, входящий в состав KickStart
</pre>
<pre id='frame6' style='display:none;'>
Вставьте в Главный стиль:
/* Для обычной версии сайта */
body {}
/* Для мобильной версии */
@media handheld, only screen and (max-width: 767px) {
}
/* Для более высокого разрешения iPhone 4 */
@media only screen and (-webkit-min-device-pixel-ratio: 2) {
}</pre>
<br>См. также <a href='http://csstemplater.com' target='_blank'>Генератор HTML+CSS шаблонов</a>
</td></tr>

<tr valign=top><td style='min-width:250px;'>
<b>Включить <a href='http://jqueryui.com' target='_blank'>jQuery UI</a></b>:</td><td class=small>
".select("options[jqueryui]", "0,1", "НЕТ,ДА", $jqueryui)."Используется для вкладок (табов). При использовании css-фреймворка Cube или KickStart вкладки будут работать и при отключенном jQuery UI.
</td></tr>

<tr valign=top><td style='min-width:250px;'>
Включить <a href='https://github.com/artpolikarpov/scrollyeah' target='_blank'>ScrollYeah</a>:</td><td class=small>
".select("options[scrollyeah]", "0,1", "НЕТ,ДА", $scrollyeah)."Используется для создания полосы из DIV-элементов с прокруткой перетаскиванием мышкой или пальцем на сенсорных экранах. <a class='punkt' onclick=\"$('#scrollyeah').toggle('slow');\">Пример</a>.
<div id=scrollyeah style='display:none;'>".close_button('scrollyeah')."
<pre>
&lt;div class='scrollyeah'&gt; ... &lt;/div&gt;
Опции можно добавить через атрибуты типа data-optionName, где optionName — это:
shadows — включает тени
maxWidth - стоит увеличить, если ваш контент больше 999999px.
disableIfFit — отключает перетаскивание, если контент не выходит за границы основного DIV
centerIfFit — центрирует основной DIV, если ширина контента меньше ширины основного DIV
triggerScrollyeah — включает триггер событий на элементе

Настройки по-умолчанию:
  maxWidth: 999999
  shadows: true
  disableIfFit: true
  centerIfFit: false
  triggerScrollyeah: false

Параллакс-эффект (см. <a href='http://artpolikarpov.github.io/scrollyeah/examples/parallax.html' target='_blank'>пример</a>):
&lt;div class='scrollyeah'&gt;
  &lt;div class='scrollyeah__parallax' data-parallaxRate='-.3'&gt;
    &lt;!-- Контент для параллакса, например какой-то фон --&gt;
  &lt;/div&gt;
  &lt;!-- DIV'ы --&gt;
&lt;/div&gt;
</pre>
</div>
</td></tr>

<tr valign=top><td style='min-width:250px;'>
Включить LightLoad:</td><td class=small>
".select("options[lightload]", "0,1", "НЕТ,ДА", $lightload)."Используется для управляемой загрузки элементов страницы. Для каждого элемента прописывается желаемое поведение. <a class='punkt' onclick=\"$('#lightload').toggle('slow');\">Подробная инструкция</a>.
<div id=lightload style='display:none;'>".close_button('lightload')."
<pre>
<h2>&lt;div class='lightload'&gt; ... &lt;/div&gt;</h2>
— появится только после того, как всё содержимое будет загружено

Атрибуты для добавления эффектов:
<b>data-spin-parent='true'</b> 
— покажет Spin в родительском элементе, пока не загрузится

<b>data-effect='slide'</b> 
— выбор эффекта появления: relax, slide, zoom или screw.
См. пример:
data-effect='slide' data-up='100px' 
— появится, пролетев снизу вверх 100 пикселей
data-effect='relax' data-scale='.5' data-origin='bottom'
— будет вертикально расти, появившись в половину своей высоты

<b>data-duration='500'</b> 
— продолжительность анимации в миллисекундах, по-умолчанию = 500.

<b>data-up='20px'</b> или <b>data-down='5em'</b> 
— применяется для slide-эффекта, элемент пролетит снизу 
или сверху на указанное расстояние. 
Одновременное использование data-up и data-down не имеет смысла.

<b>data-left='10%'</b> или <b>data-right='5cm'</b>
— применяется для slide-эффекта, элемент пролетит справа или слева 
на указанное расстояние. 
По-умолчанию = 0, т.е. без горизонтального смещения. 
Одновременное использование data-up и data-down не имеет смысла.

<b>data-scale='0.5'</b> 
Увеличение для эффектов relax, zoom и screw. 
По-умолчанию: для relax — 0.92, для zoom и screw — 0.5.

<b>data-angle='180'</b> 
Для screw-эффекта, наклон в градусах. По-умолчанию: 90˚. 
Для изменения направления используйте минусовые значения.

<b>data-origin='top'</b> 
Для relax, zoom и screw эффектов, начальная точка трансформации. 
По-умолчанию: «top» для relax и «center center» для zoom и screw.

<b>data-opaque='true'</b> 
— Включает плавный переход от полной прозрачности к видимости.

<b>data-style-1</b> и <b>data-style-2</b>
Создание эффектов вручную с помощью CSS-анимации. 
Эти атрибуты будут игнорированы, если будут использованы data-эффекты. 
См. пример:
data-opaque='true'
data-style-1='-webkit-transform: rotate3d(1,1,0,90deg)'
data-style-2='-webkit-transform: rotate3d(0,0,0,0);
    transition: opacity .5s ease-out,
    -webkit-transform 2s cubic-bezier(0.0, 0.0, 0.001, 1.0)'
Невероятный эффект!


Порядок загрузки и задержка по времени
Иной раз нужно подождать, прежде чем загрузиться 
один элемент до того, как показывать следующий.

<b>data-await='element-id'</b>
Ожидание элемента с id='element-id' (но не окончание анимации). 
Ожидаемый элемент также должен иметь class='lightload', 
иначе он будет проигнорирован. Здесь можно указать только один элемент, 
зато он может ожидать какой-то другой элемент 
— получается последовательная цепочка ожидания.

<b>data-continue='true'</b> 
Ожидание предыдущего обозначенного классом элемента.
Если указано data-await — этот атрибут не сработает.

<b>data-hold='500'</b>
Ожидание в миллисекундах

Примеры:

&lt;div class='lightload'&gt;
  ... Эй...
&lt;/div&gt;
&lt;div class='lightload' data-continue='true'&gt;
  ... Ожидаем загрузки предыдущего элемента с lightload ...
&lt;/div&gt;

&lt;div class='lightload' data-await='thing' data-hold='500'&gt;
  ... Ожидаем элемент с id=thing, затем ждем 500 ms и показываем ...
&lt;/div&gt;
&lt;div class='lightload' id='thing'&gt;
  ... Этот элемент грузится первым ...
&lt;/div&gt;

Для одновременной загрузки элементов не важно, 
какой будет грузиться первым — настройте их ожидать друг друга:
&lt;div class='lightload' id='one' data-await='two'&gt;
  ...
&lt;/div&gt;
&lt;div class='lightload' id='two' data-await='one'&gt;
  ...
&lt;/div&gt;


Добавление индикатора загрузки
<b>data-spin='true'</b>
— Добавляет индикатор, если включен Spin в Настройках


Вставка анимации загрузки в блоки
осуществляется за счет поля Класс CSS в их Настройках.
Туда можно прописать просто «lightload»
Или использовать эффекты и настройки, не забыв в конце
lightload поставить «'», а в конце всех настроек «'» не ставить.
Пример: lightload' data-spin='true

</pre></div>
<p>".select("options[spin]", "0,1", "НЕТ,ДА", $spin)."<a href='http://fgnass.github.io/spin.js/' target='_blank'>Spin</a>, используется для создания вращающегося индикатора активности, применяется для LightLoad.
</td></tr>

<tr valign=top><td style='min-width:250px;'>
Включить вкладки («табы»):</td><td class=small>
".select("options[tab_show]", "0,1", "НЕТ,ДА", $tab_show)."Вкладки будут работать только при включенном jQuery UI или использовании css-фреймворка Cube или KickStart.<br>Название первой вкладки по-умолчанию (если вкладки начинаются с текста):<br>".input("options[tab_obzor]", $tab_obzor, "100%")."
</td></tr>

<tr valign=top><td style='min-width:250px;'>
Включить <a href='http://tinysort.sjeiti.com' target='_blank'>сортировку TinySort</a>:</td><td class=small>
".select("options[sortable]", "0,1", "НЕТ,ДА", $sortable)."Позволяет сортировать любые элементы, обращаясь к ним через jQuery. <a class='punkt' onclick=\"$('#sortable').toggle('slow');\">Пример</a>.
<div id=sortable style='display:none;'>".close_button('sortable')."
<pre>
&lt;div id=sorter style='display:none;'&gt;Сортировка:
&lt;a style='cursor:pointer;' onclick=\"$('div#tovars>div#tovar').tsort('h3',{attr:'title'});\"&gt;по цене&lt;/a&gt;, 
&lt;a style='cursor:pointer;' onclick=\"$('div#tovars>div#tovar').tsort('h1',{charOrder:'абвгдђежзијклљмнњопрстћуфхцчџш'});\"&gt;по названию&lt;/a&gt;
&lt;/div&gt;
Можно показывать сортировку только при наличии сортируемых элементов:
&lt;script&gt;$(document).ready(function() { if ( document.getElementById('tovar') != null ) $('#sorter').show('slow'); });&lt;/script&gt;
</pre>
</div>
</td></tr>


	<tr valign=top><td style='min-width:250px;'>
	Название сайта (для title):</td><td class=small>
	".input("options[sitename]", $sitename, "100%")."
	<br>Можно вывести в дизайн через блок [заголовок_проекта]
	</td></tr><tr valign=top><td>
	Логотип сайта/компании:</td><td class=small>
	".input("options[project_logotip]", $project_logotip, "100%")."
	<br>Можно вывести в дизайн через блок [лого_проекта]
	</td></tr><tr valign=top><td>
	Название проекта/компании:</td><td class=small>
	".input("options[project_name]", $project_name, "100%")."
	<br>Можно вывести в дизайн через блок [название_проекта]. Обобщенный вариант через замену картинкой тега H1 — через блок [название_лого_проекта]. А настраивается всё через CSS за счет стилей, которые можно посмотреть в коде страницы.
	</td></tr><tr valign=top><td>
	Вставка в HEAD:</td><td class=small>
	".input("options[head_insert]", $head_insert, 30, "txt")."
	<br>Например, код верификации сайта (meta) в поисковых системах или в других интернет-сервисах.
	</td></tr><tr valign=top><td>
	Описание сайта:</td><td class=small>
	".input("options[description]", $description, 60, "txt")."
	<br>Максимум 500 символов. Для поисковых систем (Description). Можно вывести в дизайн через блок [описание_проекта]
	</td></tr><tr valign=top><td>
	Ключевые словосочетания:</td><td class=small>
	".input("options[keywords]", $keywords, 60, "txt")."
	<br>Максимум 500 символов. Через запятую. Для поисковых систем (Keywords)
	</td></tr><tr valign=top class=p2><td>
	Ссылка на почтовый сайт:</td><td class=small>
	".input("options[postlink]", $postlink, "100%")."
	<br>Если редактору удобно открывать свой почтовый сайт из системы администрирования сайта.
	</td></tr><tr valign=top class=p2><td>
	Ссылка на статистику:</td><td class=small>
	".input("options[statlink]", $statlink, "100%")."
	<br>Ссылка на внешнюю статистику Сайта: LiveInternet, Google Analytics, Яндекс.Метрика и другие. Например, если использовать <a href=http://www.liveinternet.ru/add>liveinternet.ru</a> ссылка будет такой: http://pda.liveinternet.ru/stat/".$siteurl."/. Отображается сверху в Администрировании - Статистика.
	</td></tr><tr valign=top class=p2><td>
	Код счетчика статистики:</td><td class=small>
	".input("options[counter]", $counter, 60, "txt")."
	<br>Код счетчика внешней статистики Сайта: LiveInternet, Google Analytics, Яндекс.Метрика и другие. Счетчик можно поставить в дизайн как автоматический блок, написав [статистика]. Если вы хотите скрыть счетчик от посетителей - используйте счетчик без цифр (и с закрытой от просмотра статистикой) и/или поставьте счетчик в скрытый слой DIV (тогда ссылка на статистику будет доступна только из Администрирования — ссылка сверху). Если использовать <a href=http://www.liveinternet.ru/add>LiveInternet</a> для получения счетчика — выбрать при получении: «код счетчика в одну строчку» и «учитывать заголовки».
	</td></tr>

	<tr valign=top><td>
	Список файлов смайлов из папки /images/smilies/</td><td class=small>
	".input("options[smile_icons]", $smile_icons, 30, "txt")."Для переопределения смайлов (если вы стерли какие-то смайлы или добавили свои в папку /images/smilies/) очистите эту строку, сохраните настройки, затем сохраните еще раз.<br>В имени файлов смайлов нельзя использовать пробелы, подойдут цифры, эмоции на английском или скобочки (типа «)» или «(((»). Форматы файлов: png, gif, jpg.</td></tr>
	
	<tr valign=top class=hide><td>
	Регистрация:</td><td class=small>
	".select("options[registr]", "0,1", "НЕТ,ЕСТЬ", $registr)."Вкл./Откл. Регистрацию и Вход пользователей на сайт.  Не работает</td></tr>

	<tr valign=top class=p3><td>
	Анимация:</td><td class=small>
	".select("options[pogoda]", "0,1,2,3", "Отключена,* Снегопад,@ Осенние листья,• Воздушные шарики", $pogoda)."
	<br>Фоновая анимация поверх содержания сайта (только на Главной странице).</td></tr>

	<tr valign=top class=p3><td>
	Полная поддержка Flash:</td><td class=small>
	".select("options[flash]", "0,1", "НЕТ,ЕСТЬ", $flash)."Включать ТОЛЬКО при проблемах отображения SWF-файлов, FLA-видео и Flash-анимации на сайте.
	</td></tr>

	<tr valign=top class=p3><td>
	Простая защита от копирования: </td><td class=small>
	".select("options[stopcopy]", "0,1", "НЕТ,ЕСТЬ", $stopcopy)."Препятствует массовому копированию статей на чужие сайты глупыми злоумышленниками. Невозможность выделить/скопировать текст и нажать правую кнопку мыши. </td></tr>

	<tr valign=top class=p4><td>
	Год основания сайта:</td><td class=small>
	".input("options[startdate]", $startdate, 4)."
	<br>Используется в автоматическом блоке [год], год основания - текущий год, для отображения внизу сайта, в строке копирайта, например © 2007-2009.
	</td></tr>

	<tr valign=top class=p4><td>
	Почта администратора:</td><td class=small>
	".input("options[adminmail]", $adminmail, "100%")."
	<br>Используется в автоматическом блоке [почта], для отправки почты администратору сайта.
	</td></tr>

	<tr valign=top><td>
	Дизайн для страницы тэгов (ключевых слов):</td><td class=small>
	".select("options[tag_design]", $id_designs, $title_designs, $tag_design)." В дизайне должен быть блок [содержание]
	</td></tr>

	<tr valign=top><td>
	Дизайн для страницы подписки на новости:</td><td class=small>
	".select("options[newsmail_design]", $id_designs, $title_designs, $newsmail_design)." В дизайне должен быть блок [содержание]
	</td></tr>

	<tr valign=top class=p4><td>
	Разделитель между датой и названием страницы, а также между названием раздела и названием папки:</td><td class=small>
	".input("options[strelka]", $strelka, 30)."
	<br>По-умолчанию, стрелка &rarr;
	</td></tr>

	<tr valign=top><td>
	Преобразование {Название раздела} и {Название страницы} в ссылки на эти раздел и страницу, соответственно:</td><td class=small>
	".select("options[show_page_links]", "0,1", "НЕТ,ДА", $show_page_links)."По-умолчанию отключено. Если вы создаете сайт с большим количеством страниц (более 500) — желательно отключить. В случае отключения останется возможность преобразования названий разделов.
	</td></tr>

	<tr valign=top><td colspan='2' class='black_grad'>
	<h2>Поиск по сайту поставить на сайт можно автоматическим блоком [поиск]</h2>
	</td></tr>

	<tr valign=top><td>
	Включить поиск по:</td><td>".select("options[search_in_pages]", "0,1", "НЕТ,ДА", $search_in_pages)." страницам, 
	".select("options[search_in_papka]", "0,1", "НЕТ,ДА", $search_in_papka)." папкам и 
	".select("options[search_in_razdel]", "0,1", "НЕТ,ДА", $search_in_razdel)." разделам.
	</td></tr>

	<tr valign=top class=p4><td>
	Ограничение количества найденных:</td><td class=small>
	".input("options[search_col_page]", $search_col_page, 4)." страниц, 
	".input("options[search_col_papka]", $search_col_papka, 3)." папок и 
	".input("options[search_col_razdel]", $search_col_razdel, 3)." разделов
	</td></tr>

	<tr valign=top class=p4><td>
	Ограничение количества найденных страниц при расширенном поиске</td><td class=small>
	".input("options[search_col_showall]", $search_col_showall, 7)."
	</td></tr>

	<tr valign=top><td>
	Дизайн для страницы поиска:</td><td>
	".select("options[search_design]", $id_designs, $title_designs, $search_design)." В дизайне должен быть блок [содержание]
	</td></tr>

</table>
<div style='text-align:center;'><input type='submit' value=' Сохранить ' style='width:300px; height:40px;'></div>
<input type='hidden' name='op' value='options_save'>
</div>


<div id='show_shop' class='show_pole pl10' style='display:none;'>
<p><a class='button' onclick='$(\"#show_shop_on\").toggle();'>Подключение магазина</a>
<div id='show_shop_on' style='display:none;'>
<p><b>Блок [корзина]</b> можно поставить в любом месте сайта. Он поддерживает добавление/удаление товаров и подсчет итоговой суммы. Работает через куки (cookie).
<p>Также есть <b>блоки для создания мини-корзины</b> в любом месте сайта — [корзина_итого] и [корзина_количество] — они выводят числа. Если Корзина пуста, блок [корзина_итого] будет пустым, а блок [корзина_количество] выдаст информацию о том, что Корзина пуста (настраивается ниже).
<p><b>Cсылку «Купить»</b> можно создать в шаблоне блока, раздела или страниц раздела — для взаимодействия с блоком [корзина]. Помимо использования в шаблонах, ссылку «Купить» можно использовать в любом произвольном месте. Ссылка содержит id страницы раздела и стоимость товара, описанного на этой странице. При массовом наполнении реализуется за счет поля «Стоимость» (или «Цена») и шаблона.<br>
	
<p>Код ссылки «Купить»:<br>
<pre>&lt;a onclick=\"shop_add_tovar([page_id],'[price]',1,'price')\"&gt;Купить&lt;/a&gt;</pre>
<p>Код кнопки «Купить»:<br>
<pre>&lt;button onclick=\"shop_add_tovar([page_id],'[price]',1,'price')\"&gt;Купить&lt;/button&gt;</pre>
	
<p><b>Вместо блоков можно использовать и цифры,</b> например (2 - номер страницы товара, '590' — стоимость в рублях, 1 - количество, 'price' — id поля, которое содержит стоимость товара):<br>
<pre>&lt;a onclick=\"shop_add_tovar(2, '590', 1, 'price')\"&gt;Купить&lt;/a&gt;</pre>
<p><b>Если поля со стоимостью товара нет</b> — используйте shop_add_tovar(2, '590', 1, ''), т.е пустое место. В этом случае магазин не будет проверять изменение цены.
	
<p><b>Если в магазине больше десяти товаров,</b> имеет смысл прописывать кнопки для покупки товаров не вручную для каждого товара, а применять для этого дополнительные поля (для ввода стоимости) и шаблоны (для настройки отображаемой информации, в частности для отображения на страницах раздела магазина добавленного поля с ценой). При использовании в шаблонах необходимо обозначать необходимую информацию блоками.

<p>Пример шаблона для раздела (вывод списка страниц):<br>
<pre>&lt;div id='tovar'&gt;
&lt;h1&gt;[page_link_title]&lt;/h1&gt;
[page_open_text]
&lt;div style='clear: both;'&gt;
&lt;div class='page_price'&gt;[price] руб.
&lt;a title=\"Купить\" style=\"cursor:pointer;\" 
onclick=\"shop_add_tovar([page_id], '[price]', 1, 'price');\"&gt;В Корзину&lt;/a&gt;&lt;/div&gt;
&lt;/div&gt;</pre>

<p>Пример шаблона для страницы (вывод информации о товаре):<br>
<pre>&lt;div class='page_cat_title'&gt;[main_title]&lt;/div&gt;
&lt;div class='page_title'&gt;[page_title]&lt;/div&gt;
&lt;div class='page_opentext'&gt;[page_opentext]&lt;/div&gt; 
&lt;div class='page_price'&gt;[price] руб. 
&lt;a title=\"Купить\" style=\"cursor:pointer;\" 
onclick=\"shop_add_tovar([page_id], '[price]', 1, 'price');\"&gt;В Корзину&lt;/a&gt;&lt;/div&gt;
&lt;div class='page_text'&gt;[page_text]&lt;/div&gt;
&lt;hr&gt;[page_favorites]</pre>

<h2>Подключение выбора количества покупаемого товара:</h2>
<p>Осуществляется с помощью поля ввода или выбора цифрового значения. Если у вас маловероятно закажут больше одного товара (к примеру, крупногабаритная или дорогая розница) — вряд ли понадобится делать выбор количества. Если обычно не заказывают больше определенного небольшого количества, например 10 — можно сделать поле для выбора из заранее подготовленных значений, от 1 до 10. Если количество может быть гораздо больше и ни от чего не зависит — рациональнее всего сделать поле для текстового ввода количества. Подстановка цифровых значений из этих полей происходит за счет JavaScript или jQuery. Например так: shop_add_tovar([page_id], '[price]', $('#count').val(), 'price'); и добавим перед кнопкой «Купить» &lt;input type='number' size='5' id='count' value='1' class='page_shop_count'&gt; Естественно такой вариант подойдет только, если на странице только один товар, например для страницы самого товара. Если так сделать в разделе, в списке страниц — получится несколько элементов с одинаковым id, поэтому используем: 
<pre>&lt;input type='number' min='0' id='count_[page_id]' value='1' class='page_shop_count'&gt;</pre> и 
<pre>shop_add_tovar([page_id], '[price]', $('#count_[page_id]').val(), 'price');</pre><br>

Помимо INPUT type='number' можно использовать:
<pre>&lt;input type='range' min='0' max='100' id='range_[page_id]' value='1' 
class='page_shop_count onchange=\"$('#count_[page_id]').val( $('#range_[page_id]').val() )\"&gt;
&lt;input id='count_[page_id]' type='text' value='1' size='5'&gt;</pre>
или 
<pre>&lt;select id='count_[page_id]' class='page_shop_count'&gt;
&lt;option value='1'&gt;1&lt;/option&gt;
&lt;option value='2'&gt;2&lt;/option&gt;
&lt;option value='3'&gt;3&lt;/option&gt;
&lt;option value='4'&gt;4&lt;/option&gt;
&lt;option value='5'&gt;5&lt;/option&gt;
&lt;/select&gt;</pre>

<p class=hide>Использование в шаблоне блоков [Купить_ссылка] и [Купить_кнопка] возможно только при подключении поля, которое содержит цену товара. В РАЗРАБОТКЕ!</p>
	<hr>
</div>

	<table class='table_light'>
	<tr valign=top><td colspan=2>

	<h1>Настройка Фильтра (блок [фильтр])</h1>
	</td></tr><tr valign=top><td width=40%>
	Заголовок «Фильтр товаров» (чтобы убрать, замените на пробел)</td><td class=small>
	".input("options[filter_name]", $filter_name, "100%")."
	</td></tr><tr valign=top><td>
	Кнопка «Показать все» (или «убрать все фильтры»):</td><td class=small>
	".input("options[filter_show_all]", $filter_show_all, "100%")."
	</td></tr><tr valign=top><td colspan=2>

	<h1>Настройка Корзины</h1>
	</td></tr><tr valign=top><td width=40%>
	Денежный знак слева или справа от суммы:</td><td class=small>
	".input("options[shop_text_val1]", $shop_text_val1, 4)." ЧИСЛО ".input("options[shop_text_val2]", $shop_text_val2, 4)."<br>
	Пример: $ ЧИСЛО или ЧИСЛО руб.
	</td></tr><tr valign=top><td>
	Поле для автоматических блоков [Купить_ссылка] и [Купить_кнопка]</td><td class=small>
	".input("options[shop_pole]", $shop_pole, "100%")."<br>Блоки можно использовать в шаблонах.
	</td></tr><tr valign=top><td>
	Замена надписи на ссылке «Оформить покупку»:</td><td class=small>
	".input("options[shop_text_oformit]", $shop_text_oformit, "100%")."
	</td></tr><tr valign=top><td>
	Замена надписи «Итого:»:</td><td class=small>
	".input("options[shop_text_itogo]", $shop_text_itogo, "100%")."
	</td></tr><tr valign=top><td>
	Замена надписи «Ваша Корзина пуста.»:</td><td class=small>
	".input("options[shop_text_korzina]", $shop_text_korzina, "100%")."
	</td></tr><tr valign=top><td>
	Замена надписи на кнопке удаления товара «×»:</td><td class=small>
	".input("options[shop_text_delete]", $shop_text_delete, "100%")."
	</td></tr><tr valign=top><td colspan=2>

	<h1>Настройка Оформления заказа</h1>
	</td></tr><tr valign=top><td>
	Список полей для отправки (разделяется через Enter):</td><td class=small>
	".input("options[shop_spisok_pole]", $shop_spisok_pole, 100, "txt")."<br>
	Если после названия поля поставить «звездочку» — поле будет обязательным для заполнения. Пример: Ф.И.О.:*
	</td></tr><tr valign=top><td>
	Шаблон формы отправки заказа:</td><td class=small>
	".input("options[shop_shablon_form_order]", $shop_shablon_form_order, 100, "txt")."
	</td></tr><tr valign=top><td colspan=2>

	<h1>Настройка Отправки заказа</h1>
	</td></tr><tr valign=top><td>
	Email для приёма заказов:</td><td class=small>
	".input("options[shop_admin_mail]", $shop_admin_mail, "100%")."
	</td></tr><tr valign=top><td>
	Текст после отправки формы заказа:</td><td class=small>
	".input("options[shop_text_after_mail]", $shop_text_after_mail, 60, "txt")."
	</td></tr><tr valign=top><td>
	Шаблон письма отправки заказа клиенту:</td><td class=small>
	".input("options[shop_shablon_mail_client]", $shop_shablon_mail_client, 100, "txt")."
	</td></tr><tr valign=top><td>
	Шаблон письма отправки заказа администратору:</td><td class=small>
	".input("options[shop_shablon_mail_admin]", $shop_shablon_mail_admin, 100, "txt")."
	</td></tr>

	</table>
	<div style='text-align:center;'><input type='submit' value=' Сохранить ' style='width:300px; height:40px;'></div>
	<input type='hidden' name='op' value='options_save'>
	</div>



<div id='show_options_fonts' class='show_pole pl10' style='display:none;'>
	<p>Вы можете выбрать и подключить дополнительные шрифты (<i><a href='http://www.google.com/webfonts/' target='_blank'>от Google</a></i>) и эффекты для них.<br>Эффекты выбирать необязательно.</p>";

	$fonts_rus = explode(",","Andika,Anonymous Pro,Bad Script,Comfortaa,Cuprum,Didact Gothic,EB Garamond,Forum,Istok Web,Jura,Kelly Slab,Ledger,Lobster,Marck Script,Marmelad,Neucha,Open Sans,Open Sans Condensed,Oranienbaum,PT Mono,PT Sans,PT Sans Caption,PT Sans Narrow,PT Serif,PT Serif Caption,Philosopher,Play,Poiret One,Press Start 2P,Prosto One,Ruslan Display,Russo One,Scada,Stalinist One,Tenor Sans,Ubuntu,Ubuntu Condensed,Ubuntu Mono,Underdog,Yeseva One");
	$fonts_eng = explode(",","ABeeZee,Abel,Abril Fatface,Aclonica,Acme,Actor,Adamina,Advent Pro,Aguafina Script,Akronim,Aladin,Aldrich,Alegreya,Alegreya SC,Alex Brush,Alfa Slab One,Alice,Alike,Alike Angular,Allan,Allerta,Allerta Stencil,Allura,Almendra,Almendra SC,Amarante,Amaranth,Amatic SC,Amethysta,Andada,Andika,Annie Use Your Telescope,Anonymous Pro,Antic,Antic Didone,Antic Slab,Anton,Arapey,Arbutus,Arbutus Slab,Architects Daughter,Archivo Black,Archivo Narrow,Arimo,Arizonia,Armata,Artifika,Arvo,Asap,Asset,Astloch,Asul,Atomic Age,Aubrey,Audiowide,Autour One,Average,Averia Gruesa Libre,Averia Libre,Averia Sans Libre,Averia Serif Libre,Bad Script,Balthazar,Bangers,Basic,Baumans,Belgrano,Belleza,BenchNine,Bentham,Berkshire Swash,Bevan,Bigshot One,Bilbo,Bilbo Swash Caps,Bitter,Black Ops One,Bonbon,Boogaloo,Bowlby One,Bowlby One SC,Brawler,Bree Serif,Bubblegum Sans,Bubbler One,Buda,Buenard,Butcherman,Butterfly Kids,Cabin,Cabin Condensed,Cabin Sketch,Caesar Dressing,Cagliostro,Calligraffitti,Cambo,Candal,Cantarell,Cantata One,Cantora One,Capriola,Cardo,Carme,Carrois Gothic,Carrois Gothic SC,Carter One,Caudex,Cedarville Cursive,Ceviche One,Changa One,Chango,Chau Philomene One,Chelsea Market,Cherry Cream Soda,Chewy,Chicle,Chivo,Coda,Coda Caption,Codystar,Combo,Comfortaa,Coming Soon,Concert One,Condiment,Contrail One,Convergence,Cookie,Copse,Corben,Courgette,Cousine,Coustard,Covered By Your Grace,Crafty Girls,Creepster,Crete Round,Crimson Text,Crushed,Cuprum,Cutive,Damion,Dancing Script,Dawning of a New Day,Days One,Delius,Delius Swash Caps,Delius Unicase,Della Respira,Devonshire,Didact Gothic,Diplomata,Diplomata SC,Doppio One,Dorsa,Dosis,Dr Sugiyama,Droid Sans,Droid Sans Mono,Droid Serif,Duru Sans,Dynalight,EB Garamond,Eagle Lake,Eater,Economica,Electrolize,Emblema One,Emilys Candy,Engagement,Enriqueta,Erica One,Esteban,Euphoria Script,Ewert,Exo,Expletus Sans,Fanwood Text,Fascinate,Fascinate Inline,Federant,Federo,Felipa,Fenix,Finger Paint,Fjord One,Flamenco,Flavors,Fondamento,Fontdiner Swanky,Forum,Francois One,Fredericka the Great,Fredoka One,Fresca,Frijole,Fugaz One,Galdeano,Galindo,Gentium Basic,Gentium Book Basic,Geo,Geostar,Geostar Fill,Germania One,Give You Glory,Glass Antiqua,Glegoo,Gloria Hallelujah,Goblin One,Gochi Hand,Gorditas,Goudy Bookletter 1911,Graduate,Gravitas One,Great Vibes,Griffy,Gruppo,Gudea,Habibi,Hammersmith One,Handlee,Happy Monkey,Headland One,Henny Penny,Herr Von Muellerhoff,Holtwood One SC,Homemade Apple,Homenaje,IM Fell DW Pica,IM Fell DW Pica SC,IM Fell Double Pica,IM Fell Double Pica SC,IM Fell English,IM Fell English SC,IM Fell French Canon,IM Fell French Canon SC,IM Fell Great Primer,IM Fell Great Primer SC,Iceberg,Iceland,Imprima,Inconsolata,Inder,Indie Flower,Inika,Irish Grover,Istok Web,Italiana,Italianno,Jacques Francois,Jacques Francois Shadow,Jim Nightshade,Jockey One,Jolly Lodger,Josefin Sans,Josefin Slab,Judson,Julee,Junge,Jura,Just Another Hand,Just Me Again Down Here,Kameron,Karla,Kaushan Script,Kelly Slab,Kenia,Knewave,Kotta One,Kranky,Kreon,Kristi,Krona One,La Belle Aurore,Lancelot,Lato,League Script,Leckerli One,Ledger,Lekton,Lemon,Life Savers,Lilita One,Limelight,Linden Hill,Lobster,Lobster Two,Londrina Outline,Londrina Shadow,Londrina Sketch,Londrina Solid,Lora,Love Ya Like A Sister,Loved by the King,Lovers Quarrel,Luckiest Guy,Lusitana,Lustria,Macondo,Macondo Swash Caps,Magra,Maiden Orange,Mako,Marcellus,Marcellus SC,Marck Script,Marko One,Marmelad,Marvel,Mate,Mate SC,Maven Pro,McLaren,Meddon,MedievalSharp,Medula One,Megrim,Meie Script,Merienda One,Merriweather,Metal Mania,Metamorphous,Metrophobic,Michroma,Miltonian,Miltonian Tattoo,Miniver,Miss Fajardose,Modern Antiqua,Molengo,Molle,Monofett,Monoton,Monsieur La Doulaise,Montaga,Montez,Montserrat,Montserrat Alternates,Montserrat Subrayada,Mountains of Christmas,Mr Bedfort,Mr Dafoe,Mr De Haviland,Mrs Saint Delafield,Mrs Sheppards,Muli,Mystery Quest,Neucha,Neuton,News Cycle,Niconne,Nixie One,Nobile,Norican,Nosifer,Nothing You Could Do,Noticia Text,Nova Cut,Nova Flat,Nova Mono,Nova Oval,Nova Round,Nova Script,Nova Slim,Nova Square,Numans,Nunito,Old Standard TT,Oldenburg,Oleo Script,Open Sans,Open Sans Condensed,Oranienbaum,Orbitron,Oregano,Orienta,Original Surfer,Oswald,Over the Rainbow,Overlock,Overlock SC,Ovo,Oxygen,Oxygen Mono,PT Mono,PT Sans,PT Sans Caption,PT Sans Narrow,PT Serif,PT Serif Caption,Pacifico,Parisienne,Passero One,Passion One,Patrick Hand,Patua One,Paytone One,Peralta,Permanent Marker,Petit Formal Script,Petrona,Philosopher,Piedra,Pinyon Script,Plaster,Play,Playball,Playfair Display,Podkova,Poiret One,Poller One,Poly,Pompiere,Pontano Sans,Port Lligat Sans,Port Lligat Slab,Prata,Press Start 2P,Princess Sofia,Prociono,Prosto One,Puritan,Quando,Quantico,Quattrocento,Quattrocento Sans,Questrial,Quicksand,Qwigley,Racing Sans One,Radley,Raleway,Raleway Dots,Rammetto One,Ranchers,Rancho,Rationale,Redressed,Reenie Beanie,Revalia,Ribeye,Ribeye Marrow,Righteous,Rochester,Rock Salt,Rokkitt,Romanesco,Ropa Sans,Rosario,Rosarivo,Rouge Script,Ruda,Ruge Boogie,Ruluko,Ruslan Display,Russo One,Ruthie,Rye,Sail,Salsa,Sancreek,Sansita One,Sarina,Satisfy,Scada,Schoolbell,Seaweed Script,Sevillana,Shadows Into Light,Shadows Into Light Two,Shanti,Share,Shojumaru,Short Stack,Sigmar One,Signika,Signika Negative,Simonetta,Sirin Stencil,Six Caps,Skranji,Slackey,Smokum,Smythe,Sniglet,Snippet,Sofadi One,Sofia,Sonsie One,Sorts Mill Goudy,Source Code Pro,Source Sans Pro,Special Elite,Spicy Rice,Spinnaker,Spirax,Squada One,Stalinist One,Stardos Stencil,Stint Ultra Condensed,Stint Ultra Expanded,Stoke,Sue Ellen Francisco,Sunshiney,Supermercado One,Swanky and Moo Moo,Syncopate,Tangerine,Telex,Tenor Sans,The Girl Next Door,Tienne,Tinos,Titan One,Titillium Web,Trade Winds,Trocchi,Trochut,Trykker,Tulpen One,Ubuntu,Ubuntu Condensed,Ubuntu Mono,Ultra,Uncial Antiqua,Underdog,UnifrakturCook,UnifrakturMaguntia,Unkempt,Unlock,Unna,VT323,Varela,Varela Round,Vast Shadow,Vibur,Vidaloka,Viga,Voces,Volkhov,Vollkorn,Voltaire,Waiting for the Sunrise,Wallpoet,Walter Turncoat,Warnes,Wellfleet,Wire One,Yanone Kaffeesatz,Yellowtail,Yeseva One,Yesteryear,Zeyada");
	$options = "";
	foreach ($fonts_rus as $font) {
		$options .= "<option value='".$font."'>".$font."</option>";
	}
	$options .= "<option value='' disabled>- Английские шрифты -</option>";
	foreach ($fonts_eng as $font) {
		$options .= "<option value='".$font."'>".$font."</option>";
	}

	// Chrome/Firefox/Opera/Safari
	$effects = explode(",","anaglyph,emboss,fire,fire-animation,neon,outline,shadow-multiple,3d,3d-float");

	// Chrome/Safari
	$effects2 = explode(",","brick-sign,canvas-print,crackle,decaying,destruction,distressed,distressed-wood,fragile,grass,ice,mitosis,putting-green,scuffed-steel,splintered,static,stonewash,vintage,wallpaper");

	$options2 = "";
	foreach ($effects as $effect) {
		$options2 .= "<option value='".$effect."'>".$effect."</option>";
	}
	$options2 .= "<option value='' disabled>- Эффекты (Chrome/Safari) -</option>";
	foreach ($effects2 as $effect) {
		$options2 .= "<option value='".$effect."'>".$effect."</option>";
	}
	$options3 = "";
	if (strlen($add_fonts) > 1) {
		$add_fonts2 = explode(".",$add_fonts);
		foreach ($add_fonts2 as $font) {
			$options3 .= "<option value='".$font."'>".$font."</option>";
		}
	}
	echo "<script>
	function del_font() {
		$(\"#add_fonts :selected\").remove();
	}
	function add_font() {
		var font = $(\"#fonts\").val();
		var effect = $(\"#effects\").val();
		var add = font;
		if (effect != '') add = font + ',' + effect;
		if (font != null) $('#add_fonts').append('<option value=\"' + add + '\">' + add + '</option>');
	}
	function font(show) {
		var font = $(\"#fonts\").val();
		var effect = $(\"#effects\").val();
		var effect_show = '';
		var about_effect = '';
		if (show == 'show') { 
			font = $(\"#add_fonts\").val();
			font = font.split(/[,]/);
			if (font['1']) effect = font['1']; else effect = '';
			font = font['0'];
		}
		if (effect != null && effect != '') {
			effect_show = '&effect=' + effect;
			about_effect = '<br>Подключение эффекта: &lt;div class=\"font-effect-' + effect + '\"&gt;Пример текста&lt;/div&gt;<br>Подключенный эффект можно использовать в любых шрифтах';
		}
		$('#font_preview').html('<link href=\"http://fonts.googleapis.com/css?family=' + font.replace(\" \", \"+\") + '&subset=latin,cyrillic' + effect_show + '\" rel=\"stylesheet\" type=\"text/css\"><h1 style=\"font-family: ' + '\'' + font + '\'' + ';\">' + $('#text_primer').val() + ' <br>Использование в CSS: font-family: \'' + font + '\' ' + about_effect + '</h1>');
		$('#font_preview').toggleClass('font-effect-' + effect);
	}
	function save_fonts() {
		var all='';
		$.each($('#add_fonts option'), function(i,val) { 
			all = all + this.text;
			if ( i != $('#add_fonts option').length-1) all = all + '.'; 
		});
		$('#fonts_spisok').val(all);
	}
	</script>
	<div style='float:left; margin-right:5px; margin-bottom:5px;'><h3>Шрифты:</h3>
	<select id='fonts' size=16 onchange='font()'><option value='' disabled>- Русские шрифты -</option>".$options."</select>
	</div>
	<h3>Эффекты:</h3>
	<select style='margin-bottom:5px;' id='effects' onchange='font()'><option value='' disabled>- Эффекты (Chrome/Firefox/Opera/Safari) -</option><option value='' selected>Эффект не выбран</option>".$options2."</select>

	<br><a class='button' onclick='add_font(); save_fonts();'>Добавить &darr;</a> <a class='button' onclick='del_font(); save_fonts();'>Удалить</a><br>
	Список используемых шрифтов:<br>
	<select style='margin-top:5px;' id=\"add_fonts\" onchange=\"font('show')\" size=\"11\">".$options3."</select>
	<input id='fonts_spisok' type=hidden name='options[add_fonts]' value='".$add_fonts."'>

	<div id='font_preview' onclick=\"show('text_primer');\" style='padding:10px; clear: both; background:white;'>Здесь будет показан пример шрифта и его применения. Нажмите, чтобы изменить текст.</div>
	<input id='text_primer' style='width:80%; display:none;' type='text' value='Съешь ещё этих мягких французских булок, да выпей чаю. Нажмите, чтобы изменить текст.<br>Grumpy wizards make toxic brew for the evil Queen and Jack.'><br>
	Не забудьте добавить шрифт в список используемых и сохранить.<br>
	Если при выборе эффекта (или шрифта с эффектом из списка выбранных шрифтов), вы не увидели эффекта в поле предпросмотра — выберите другой эффект и еще раз выберите первый.


	<p><b>Справка:</b> <a style='cursor:pointer;' class=punkt onclick=\"show('sgladit_shrift')\">Как сгладить шрифт через CSS</a> (подходит для больших заголовков)<br>
	<div id='sgladit_shrift' style='display:none;'><pre>
	.title {
	   text-shadow:-1px -1px 1px rgba(255,255,255,0.2), /* наверх и влево */
	   1px 1px 1px rgba(255,255,255,0.2), /* вниз и вправо */
	   1px 1px 1px rgba(0,0,0,0.7); /* тёмная тень */
	}
	</pre></div>
	<p>
	<div style='text-align:center;'><input type='submit' value=' Сохранить ' style='width:300px; height:40px;'></div>
	</div>

<div id='show_options_company' class='show_pole pl10' style='display:none;'>
	<p>Основные данные компании могут быть вставлены в любом месте сайта как мини-блоки и используются для быстрой замены, т.е. мы меняем информацию только на странице настроек, а она меняется на всем сайте.
	<p>Информация разделяется тремя символами «вертикальная черта», т.е. ||| Каждому последующему мини-блоку присваивается аналогичное имя с порядковым номером.
	<p>Пример: пишем в поле «Краткое название компании:» текст «АгроХолдинг|||Промышленная палата|||РусТранс»
	<br>Получаем 3 мини-блока: [компания1] (АгроХолдинг), [компания2] (Промышленная палата) и [компания3] (РусТранс).
	<p><i>Переход на новую строку обрабатывается как BR-тег</i>.
	<table class=table_light>
	<tr valign=top><td style='min-width:350px;'>
	Краткое название компании: [компания1]
	".input("options[company_name]", $company_name, 40, "txt")."</td><td>
	Полное название компании: [КОМПАНИЯ1]
	".input("options[company_fullname]", $company_fullname, 40, "txt")."</td></tr>
	<tr valign=top><td>
	Адрес(а) компании: [адрес компании1]
	".input("options[company_address]", $company_address, 50, "txt")."</td><td>
	Время работы: [время работы компании1]
	".input("options[company_time]", $company_time, 50, "txt")."</td></tr>
	<tr valign=top><td>
	Телефон(ы) компании: [телефон компании1]
	".input("options[company_tel]", $company_tel, 30, "txt")."</td><td>
	Сотовый(ые) компании: [сотовый компании1]
	".input("options[company_sot]", $company_sot, 30, "txt")."</td></tr>
	<tr valign=top><td>
	Факс(ы) компании: [факс компании1]
	".input("options[company_fax]", $company_fax, 30, "txt")."</td><td>
	Email(ы) компании: [почта компании1]
	".input("options[company_email]", $company_email, 30, "txt")."</td></tr>
	<tr valign=top><td>
	Код(ы) карты: [карта компании1]
	".input("options[company_map]", $company_map, 80, "txt")."</td><td>
	Контактное(ые) лицо(а): [лицо компании1]
	".input("options[company_people]", $company_people, 80, "txt")."</td></tr>
	</table>
	<div style='text-align:center;'><input type='submit' value=' Сохранить ' style='width:300px; height:40px;'></div>
	</div>


<div id='show_options_adspeed' class='show_pole pl10' style='display:none;'>";
	
	// Получаем количество добавленных в кеш страниц, а также занимаемый ими размер
	if ($site_cash == "file" || $site_cash == "base") {
		if ($site_cash == "base") {
			$row = $db->sql_fetchrow($db->sql_query("SELECT COUNT(`id`) i FROM ".$prefix."_cash"));
			$num = $row['i'];
			$rows = $db->sql_fetchrow($db->sql_query("SHOW TABLE STATUS LIKE '".$prefix."_cash'"));
			$cash_size = "страниц в базе данных: ".$num.", размер: ".round( $rows['Data_length'] / 1048576, 3)." Мбайт";
		}
		if ($site_cash == "file") {
			//Применение glob("cashe/"."*") здесь невозможно — если файлов будет больше 100.000 — функция выдаст ошибку.
			$num = 0;
			$file_size = 0;
			if ($handle = opendir("cashe/")) {
			    while (false !== ($file = readdir($handle))) {
			        if ($file != "." && $file != ".." && $file != ".htaccess") {
			        	$num ++;
			        	$size = filesize("cashe/".$file);
				        $file_size = $file_size + $size;
				        // Можно сделать вывод файлов в кеше, не факт, что это нужно
				        //echo "$file - ".filesize("cashe/".$file)."<br>"; 
				    }
			    }
			    closedir($handle);
			}
			$cash_size = "страниц в файлах: ".$num.", размер: ".round( $file_size / 1048576, 3)." Мбайт";
		}
		echo "<p><a class='button' target='_blank' href='?cash=del' style='margin-bottom:5px;'><span class='icon medium gray' data-icon='T'></span>Очистить кеш (".$cash_size.")</a>";
	} else echo "<div class='notice warning'>Кеширование страниц отключено. Включить можно в файле config.php — \$site_cash</div>";

	// Цветовые схемы невизуального редактора
	$color_themes = "chrome,clouds,crimson_editor,dawn,dreamweaver,eclipse,github,solarized_light,textmate,tomorrow,xcode,ambiance,chaos,clouds_midnight,cobalt,idle_fingers,kr_theme,merbivore,merbivore_soft,mono_industrial,monokai,pastel_on_dark,solarized_dark,terminal,tomorrow_night,tomorrow_night_blue,tomorrow_night_bright,tomorrow_night_eighties,twilight,vibrant_ink";
	$color_themes_names = "Chrome (светлая),Clouds (светлая),Crimson Editor (светлая),Dawn (светлая),Dreamweaver (светлая),Eclipse (светлая),GitHub (светлая),Solarized Light (светлая),TextMate (светлая),Tomorrow (светлая),XCode (светлая),Ambiance (темная),Chaos (темная),Clouds Midnight (темная),Cobalt (темная),idleFingers (темная),krTheme (темная),Merbivore (темная),Merbivore Soft (темная),Mono Industrial (темная),Monokai (темная),Pastel on dark (темная),Solarized Dark (темная),Terminal (темная),Tomorrow Night (темная),Tomorrow Night Blue (темная),Tomorrow Night Bright (темная),Tomorrow Night 80s (темная),Twilight (темная),Vibrant Ink (темная)";

	echo "<table class='w100'><tr valign=top><td width='60'>
	Цвет<br>фона:<select size='29' style='width:60px; text-align:center;' name=options[ad_fon] onchange=\"$(body).css('background-color', '' + $(this).val() + ' !important' )\">".$ad_fon_option."</select>
	</td><td>


	<table class='table_light'>
	<tr valign=top><td>
	<b>Создание резервной копии:</b><br>
	<a class='button' href='sys.php?op=backup' target='_blank'>Создать сейчас</a></td><td class=small>
	".select("options[show_reserv]", "0,1", "НЕТ,ДА", $show_reserv)."Каждый день, при посещении администратором главной страницы администрирования, создается резервная копия всего содержания сайта, кроме файлов (документы, архивы, фотографии), закачанных на сервер. Если это большой портал на скромном хостинге, создание копии можно отключить для экономии файлового места.
	</td></tr>


	<tr valign=top><td>
	Показывать «шапку»:</td><td class=small>
	".select("options[show_admin_top]", "0,1", "НЕТ,ДА", $show_admin_top)."При отключении «шапка» (верхняя часть администрирования с главным меню) будет автоматически скрыта и снова показана при наведении.
	</td></tr>

	<tr valign=top><td>
	Отображать количество страниц в разделах (на вкладке Содержание):</td><td class=small>
	".select("options[show_page]", "0,1", "НЕТ,ДА", $show_page)."Отключать его имеет смысл, если создано очень много страниц (более 20 тысяч) и хочется ускорить загрузку вкладки Содержание на 1-2 секунды.
	</td></tr>

	<tr valign=top><td>
	Показывать добавленные посетителями страницы:</td><td class=small>
	".select("options[show_userposts]", "0,1", "НЕТ,ДА", $show_userposts)."
	</td></tr>
	
	<tr valign=top><td>
	Включить ускорение вывода блоков (на вкладке Оформление):</td><td class=small>
	".select("options[uskorenie_blokov]", "0,1", "НЕТ,ДА", $uskorenie_blokov)."Отключать его имеет смысл, если создано много блоков и  страница с ними долго загружается. Ускорение убирает информацию об использовании блоков. 
	</td></tr>

	<tr valign=top><td>
	Файл с резервной копией .htaccess:</td><td class=small>
	".input("options[ht_backup]", $ht_backup, "100%")."
	<br>Для автовосстановления в случае поражения «вирусом».
	</td></tr>

	<tr valign=top><td>
	Сортировка разделов во вкладке Содержание:</td><td class=small>
	".select("options[razdel_sort]", "color desc[|] title,title,counter desc[|] title", "по цвету и алфавиту,по алфавиту,по посещаемости", $razdel_sort)."
	</td></tr>

	<tr valign=top class='hide'><td>
	Регион:</td><td>
	".input("options[geo]", $geo, "5", "number")."
	</td></tr>

	<tr valign=top><td>
	Ключевых слов:</td><td class=small>
	".input("options[kolkey]", $kolkey, "3", "number")."
	</td></tr>

	</table>


	</td></tr></table>
	
	<div style='text-align:center;'><input type='submit' value=' Сохранить ' style='width:300px; height:40px;'></div>
	</div>";


echo "<div id='show_options_comments' class='show_pole pl10' style='display:none;'>
	<p>Комментарии можно вывести и в тексте страницы (если она сделана по сложному шаблону), для этого нужно использовать блок [комментарии] — в этом случае комментарии не будут отображаться на своем месте по-умолчанию.
	<table class='table_light'>

	<tr valign=top><td>
	Использование аватаров <a href='http://ru.gravatar.com' target='_blank'>Gravatar</a> в комментариях:</td><td class=small>
	".select("options[gravatar]", "0,1", "НЕТ,ЕСТЬ", $gravatar)."</td></tr>

	<tr valign=top><td>
	Отключить защиту комментариев: </td><td class=small>
	".select("options[captcha_ok]", "0,1", "НЕТ,ЕСТЬ", $captcha_ok)."По умолчанию - выкл. При отключении можно не вводить проверочный код или вводить его неправильно — он не будет проверяться. </td></tr>

	<tr valign=top><td>
	Получать письма о новых комментариях:</td><td class=small>
	".select("options[comment_send]", "0,1", "НЕТ,ДА", $comment_send)."Отправка уведомлений о новых комментариях на почту администратора.
	</td></tr>

	<tr valign=top><td>
	Варианты отправителей (должности, ники или имена), через запятую:</td><td class=small>
	".input("options[avtor_comments]", $avtor_comments, 100)."
	При ответе на комментарий можно выбрать отправителя или написать что-то другое.<br>
	По-умолчанию будет предложен первый вариант.
	</td></tr>

	</table>

	<h2>Шаблоны ответов на комментарии</h2>";
	$add_mail_shablons1 = "";
	if (strlen($add_mail_shablons) > 1) {
		$add_mail_shablons2 = explode("?%?",$add_mail_shablons);
		foreach ($add_mail_shablons2 as $cli) {
			$cli2 = explode("*?*",$cli);
			$add_mail_shablons1 .= "<option value='".$cli."'>".$cli2[0]."</option>";
		}
	}
	echo "<script>
	function del_mail_shablon() {
		$(\"#add_mail_shablons :selected\").remove();
	}
	function add_mail_shablon() {
		var mail_shablon = $(\"#mail_shablons\").val();
		var text = $(\"#mail_shablon_text\").val();
		if (mail_shablon != '') $('#add_mail_shablons').append('<option value=\"' + mail_shablon + '*?*' + text + '\">' + mail_shablon + '</option>');
	}
	function mail_shablon(show) {
		var mail_shablon = $(\"#add_mail_shablons\").val();
		mail_shablon = mail_shablon.split('*?*');
		if (mail_shablon['1']) { text = mail_shablon['1']; mail_shablon = mail_shablon['0']; } else { text = mail_shablon = ''; }
		$('#mail_shablon_preview').html(text + '<p><a class=\"button small\" onclick=\"$(\'#mail_shablons_html\').toggle();\">Показать HTML</p><textarea id=\"mail_shablons_html\" class=\"w100 hide\">' + text + '</textarea>');
	}
	function save_mail_shablons() {
		var all='';
		$.each($('#add_mail_shablons option'), function(i,val) { 
			all = all + this.value;
			if ( i != $('#add_mail_shablons option').length-1) all = all + '?%?'; 
		});
		$('#mail_shablons_spisok').val(all);
	}
	</script>

	<div style='display:none;' id='add_mail_shablon' class=block>
	
	<h3>Краткое название ответа:</h3>
	<input id='mail_shablons' class=w100>
	<h3>Текст ответа (можно использовать HTML):</h3>
	<textarea id='mail_shablon_text' class=w100></textarea><br>
	<a class='button' onclick='add_mail_shablon(); save_mail_shablons();'>Добавить &darr;</a>
	</div>

	<h2><a id='button_add_mail_shablon' title='Добавить шаблон ответа...' class='mr10 button small green' onclick=\"$('#add_mail_shablon').show('slow'); $('#button_add_mail_shablon').hide('slow');\"><span class='mr-2 icon darkgrey small' data-icon='+'></span><span class='plus20'>Добавить</span></a>
	Используемые шаблоны:</h2>
	<select style='margin-top:5px;' name='options[add_mail_shablons]' id='add_mail_shablons' onchange='mail_shablon()' size=10 class='w100'>".$add_mail_shablons1."</select>
	<a class='button white red' onclick='del_mail_shablon(); save_mail_shablons();'>Удалить</a>
	<input id='mail_shablons_spisok' type=hidden name='options[add_mail_shablons]' value='".$add_mail_shablons."'>

	<div id='mail_shablon_preview' style='margin:10px; padding:10px; clear: both; background:white;'>Здесь будет показан выбранный вариант ответа.</div>

	<div style='text-align:center;'><input type='submit' value=' Сохранить ' style='width:300px; height:40px;'></div>
	<input type='hidden' name='op' value='options_save'>
	</div>";



  // Настройки невизуального редактора с подсветкой кода
  if ($color_tema_html == "") $color_tema_html = "monokai";
  if ($color_tema_css == "") $color_tema_css = "monokai";
  if ($color_tema_js == "") $color_tema_js = "monokai";
  if ($color_tema_php == "") $color_tema_php = "monokai";
echo "<div id='show_options_zagotovka' class='show_pole pl10' style='display:none;'>
	<table class='table_light' width='100%'>

	<tr><td colspan=3><h1>Невизуальный редактор с подсветкой кода</h1><img src='images/2.png'></td></tr>
	<tr valign=top><td style='max-width:150px;'>
	Цветовая тема:</td><td class=small>
	HTML:".select("options[color_tema_html]", $color_themes, $color_themes_names, $color_tema_html)."<br>
	CSS:".select("options[color_tema_css]", $color_themes, $color_themes_names, $color_tema_css)."</td><td class=small>
	JS:".select("options[color_tema_js]", $color_themes, $color_themes_names, $color_tema_js)."<br>
	PHP:".select("options[color_tema_php]", $color_themes, $color_themes_names, $color_tema_php)."
	</td></tr>

	<tr><td colspan=3><h1>2й визуальный редактор (серые кнопки)</h1><img src='images/4.jpg'></td></tr>
	<tr valign=top><td style='max-width:150px;'>
	Выберите кнопки редактора:</td><td class=small>";

global $ed2_button_html, $ed2_button_formatting, $ed2_button_bold, $ed2_button_italic, $ed2_button_deleted, $ed2_button_underline, $ed2_button_unorderedlist, $ed2_button_orderedlist, $ed2_button_outdent, $ed2_button_indent, $ed2_button_image, $ed2_button_video, $ed2_button_file, $ed2_button_table, $ed2_button_link, $ed2_button_alignment, $ed2_button_horizontalrule, $ed2_button_more, $ed2_button_link2, $ed2_button_block, $ed2_button_pre, $ed2_button_fullscreen, $ed2_button_clips, $ed2_button_fontcolor, $ed2_button_fontsize, $ed2_button_fontfamily, $ed2_minHeight, $ed2_direction, $ed2_div_convert, $ed2_paragraphy, $ed2_button_superscript;
if ($ed2_button_html == "" && $ed2_button_bold == "" && $ed2_button_link == "") $ed2_button_html = $ed2_button_formatting = $ed2_button_bold = $ed2_button_italic = $ed2_button_deleted = $ed2_button_unorderedlist = $ed2_button_orderedlist = $ed2_button_image = $ed2_button_video = $ed2_button_file = $ed2_button_table = $ed2_button_link = $ed2_button_alignment = $ed2_button_horizontalrule = $ed2_button_fullscreen = $ed2_button_clips = " checked";
if ($ed2_direction == "") $ed2_direction = "ltl";
if ($ed2_div_convert != "1") $ed2_div_convert = "0";
if ($ed2_minHeight == "") $ed2_minHeight = "300";
if ($ed2_button_html == "1") $ed2_button_html = " checked";
if ($ed2_button_formatting == "1") $ed2_button_formatting = " checked";
if ($ed2_button_bold == "1") $ed2_button_bold = " checked";
if ($ed2_button_italic == "1") $ed2_button_italic = " checked";
if ($ed2_button_deleted == "1") $ed2_button_deleted = " checked";
if ($ed2_button_underline == "1") $ed2_button_underline = " checked";
if ($ed2_button_unorderedlist == "1") $ed2_button_unorderedlist = " checked";
if ($ed2_button_orderedlist == "1") $ed2_button_orderedlist = " checked";
if ($ed2_button_outdent == "1") $ed2_button_outdent = " checked";
if ($ed2_button_indent == "1") $ed2_button_indent = " checked";
if ($ed2_button_image == "1") $ed2_button_image = " checked";
if ($ed2_button_video == "1") $ed2_button_video = " checked";
if ($ed2_button_file == "1") $ed2_button_file = " checked";
if ($ed2_button_table == "1") $ed2_button_table = " checked";
if ($ed2_button_link == "1") $ed2_button_link = " checked";
if ($ed2_button_alignment == "1") $ed2_button_alignment = " checked";
if ($ed2_button_horizontalrule == "1") $ed2_button_horizontalrule = " checked";
if ($ed2_button_more == "1") $ed2_button_more = " checked";
if ($ed2_button_link2 == "1") $ed2_button_link2 = " checked";
if ($ed2_button_block == "1") $ed2_button_block = " checked";
if ($ed2_button_pre == "1") $ed2_button_pre = " checked";
if ($ed2_button_fullscreen == "1") $ed2_button_fullscreen = " checked";
if ($ed2_button_clips == "1") $ed2_button_clips = " checked";
if ($ed2_button_fontcolor == "1") $ed2_button_fontcolor = " checked";
if ($ed2_button_fontsize == "1") $ed2_button_fontsize = " checked";
if ($ed2_button_fontfamily == "1") $ed2_button_fontfamily = " checked";
if ($ed2_button_superscript == "1") $ed2_button_superscript = " checked";

echo "
".input('options[ed2_button_html]','1','','checkbox',$ed2_button_html)." Код (режим просмотра HTML)<br>
".input('options[ed2_button_formatting]','1','','checkbox',$ed2_button_formatting)." Форматирование текста<br>
".input('options[ed2_button_bold]','1','','checkbox',$ed2_button_bold)." Полужирный<br>
".input('options[ed2_button_italic]','1','','checkbox',$ed2_button_italic)." Наклонный (курсив)<br>
".input('options[ed2_button_deleted]','1','','checkbox',$ed2_button_deleted)." Зачеркнутый<br>
".input('options[ed2_button_underline]','1','','checkbox',$ed2_button_underline)." Подчеркнутый<br>
".input('options[ed2_button_unorderedlist]','1','','checkbox',$ed2_button_unorderedlist)." • Обычный список<br>
".input('options[ed2_button_orderedlist]','1','','checkbox',$ed2_button_orderedlist)." 1. Нумерованный список<br>
".input('options[ed2_button_outdent]','1','','checkbox',$ed2_button_outdent)." < Уменьшить отступ<br>
".input('options[ed2_button_indent]','1','','checkbox',$ed2_button_indent)." > Увеличить отступ<br>
".input('options[ed2_button_image]','1','','checkbox',$ed2_button_image)." Изображение<br>
".input('options[ed2_button_video]','1','','checkbox',$ed2_button_video)." Видео<br>
".input('options[ed2_button_file]','1','','checkbox',$ed2_button_file)." Файл<br>
".input('options[ed2_button_table]','1','','checkbox',$ed2_button_table)." Таблица<br>
".input('options[ed2_button_link]','1','','checkbox',$ed2_button_link)." Ссылка<br>
".input('options[ed2_button_alignment]','1','','checkbox',$ed2_button_alignment)." Выравнивание текста<br>
</td><td class=small>
".input('options[ed2_button_horizontalrule]','1','','checkbox',$ed2_button_horizontalrule)." Горизонтальная линия<br>
".input('options[ed2_button_more]','1','','checkbox',$ed2_button_more)." Ссылка на полное содержание (для предисловия)<br>
".input('options[ed2_button_link2]','1','','checkbox',$ed2_button_link2)." [] Вставка блока<br>
".input('options[ed2_button_block]','1','','checkbox',$ed2_button_block)." {} Вставка ссылки на страницу или раздел*<br>
".input('options[ed2_button_pre]','1','','checkbox',$ed2_button_pre)." Предварительно форматированный текст (PRE)<br>
".input('options[ed2_button_fullscreen]','1','','checkbox',$ed2_button_fullscreen)." Во весь экран (кнопка справа)<br>
".input('options[ed2_button_clips]','1','','checkbox',$ed2_button_clips)." <b>Заготовки</b> (шаблоны, можно добавить ниже ↓)<br>
".input('options[ed2_button_fontcolor]','1','','checkbox',$ed2_button_fontcolor)." Цвет текста и заливки (фона) текста<br>
".input('options[ed2_button_fontsize]','1','','checkbox',$ed2_button_fontsize)." Размер текста (нежелательно!)<br>
".input('options[ed2_button_fontfamily]','1','','checkbox',$ed2_button_fontfamily)." Шрифт текста (нежелательно!)<br>
".input('options[ed2_button_superscript]','1','','checkbox',$ed2_button_superscript)." Нижний и верхний индекс<br>
Высота поля редактора: ".input('options[ed2_minHeight]',$ed2_minHeight, "10")."<br>
Направление текста: ".select('options[ed2_direction]','ltl,rtl','слева направо,справа налево (по-арабски)',$ed2_direction)."<p>
Конвертировать тэги DIV в P".select('options[ed2_div_convert]','0,1','НЕТ,ДА',$ed2_div_convert)."<p>
Запретить автоматическую вставку тэга P (будет использован BR)".select('options[ed2_paragraphy]','0,1','НЕТ,ДА',$ed2_paragraphy)."
	</td></tr>
	</table>

	<p>Вы можете добавить шаблонные <b>заготовки</b> для использования во втором редакторе. Это позволит быстро вставлять в предисловие или содержание страницы заранее заготовленные куски текста или HTML-кода, и, в отличии от обычной вставки блоков, также сразу их редактировать. Если на всех страницах раздела используется один и тот же шаблон - его можно задать при редактировании самого раздела (внизу). Заготовки же для редактора используются в случае вставки многочисленных (чаще всего небольших) элементов на странице, т.е. позволяют решить немного другие задачи. Пример: красивая рамочка, DIV с css-классом для цветного выделения текста, заготовка определенной таблицы и т.д.</p>";

	$add_clips1 = "";
	if (strlen($add_clips) > 1) {
		$add_clips2 = explode("?%?",$add_clips);
		foreach ($add_clips2 as $cli) {
			$cli2 = explode("*?*",$cli);
			$add_clips1 .= "<option value='".$cli."'>".$cli2[0]."</option>";
		}
	}
	echo "<script>
	function del_clip() {
		$(\"#add_clips :selected\").remove();
	}
	function add_clip() {
		var clip = $(\"#clips\").val();
		var text = $(\"#clips_text\").val();
		if (clip != '') $('#add_clips').append('<option value=\"' + clip + '*?*' + text + '\">' + clip + '</option>');
	}
	function clip(show) {
		var clip = $(\"#add_clips\").val();
		clip = clip.split('*?*');
		if (clip['1']) { text = clip['1']; clip = clip['0']; } else { text = clip = ''; }
		$('#clip_preview').html(text + '<p><a class=\"button small\" onclick=\"$(\'#clips_html\').toggle();\">Показать HTML</p><textarea id=\"clips_html\" class=\"w100 hide\">' + text + '</textarea>');
	}
	function save_clips() {
		var all='';
		$.each($('#add_clips option'), function(i,val) { 
			all = all + this.value;
			if ( i != $('#add_clips option').length-1) all = all + '?%?'; 
		});
		$('#clips_spisok').val(all);
	}
	</script>

	<div style='display:none;' id='add_clip' class=block>
	
	<h3>Название заготовки:</h3>
	<input id='clips' class=w100>
	<h3>Текст заготовки (можно использовать HTML и [блоки]):</h3>
	<textarea id='clips_text' class=w100></textarea><br>
	<a class='button' onclick='add_clip(); save_clips();'>Добавить &darr;</a>
	</div>

	<h2><a id='button_add_clip' title='Добавить заготовку...' class='mr10 button small green' onclick=\"$('#add_clip').show('slow'); $('#button_add_clip').hide('slow');\"><span class='mr-2 icon darkgrey small' data-icon='+'></span><span class='plus20'>Добавить</span></a>
	Используемые заготовки:</h2>
	<select style='margin-top:5px;' name='options[add_clips]' id='add_clips' onchange='clip()' size=10 class='w100'>".$add_clips1."</select>
	<a class='button white red' onclick='del_clip(); save_clips();'>Удалить</a>
	<input id='clips_spisok' type=hidden name='options[add_clips]' value='".$add_clips."'>

	<div id='clip_preview' style='margin:10px; padding:10px; clear: both; background:white;'>Здесь будет показана выбранная заготовка.</div>

	<div style='text-align:center;'><input type='submit' value=' Сохранить ' style='width:300px; height:40px;'></div>
	<input type='hidden' name='op' value='options_save'>
	</div>
	</form>";

	///////////////////////////////////////////////////////////////////////////////////////////
	
	$row = $db->sql_fetchrow($db->sql_query("SELECT `name` from " . $prefix . "_authors where `aid`='".$admin[0]."'"));
	//$chng_aid = $admin[0];
	$chng_name = filter($row['name'], "nohtml");
	//$chng_pwd = filter($row['pwd'], "nohtml");
	//$chng_aid = strtolower(substr($chng_aid, 0,25));
	//$aid = $chng_aid;
	
	echo "<div id='show_options_pass_block' class='show_pole pl10' style='display:none;'>";
	if (!isset($ip_address)) $ip_address="";
	if ($ipban != false) {
		main_ban($ip_address);
		echo "<hr>";
	} else echo "<div class='notice warning'>Блокировка посетителей отключена. Включить можно через config.php</div>";
	echo "<h2>Смена пароля администратора:</h2>
	<form action=".$admin_file.".php method=post>
	<table class='table_light'>
	<tr><td align='right'>Псевдоним:</td>
	<td><input type=text name='chng_aid' value='".$admin[0]."' size='20' maxlength='25'></td></tr>
	<tr><td align='right'>Старый пароль:</td>
	<td><input type='password' name='chng_pwd' size='20' maxlength='40'></td></tr>
	<tr><td align='right'>Новый пароль:</td>
	<td><input type='password' name='chng_pwd2' size='20' maxlength='40'></td></tr>
	<tr><td colspan='2'><input type='submit' value=' Сохранить '></td></tr></table>
	<input type='hidden' name='adm_aid' value='".$admin[0]."'>
	<input type='hidden' name='op' value='update_author'>
	</form>";
	if ($chng_name == "BOG") {
		echo "<hr><a class='button medium' onclick=\"$('#show_admins').toggle();\"><span class=\"icon gray medium\" data-icon=\"u\"></span> Редактирование администраторов</a>
		<div id='show_admins' class='hide'>";
		echo "	<table class='w100 table_light'>
				<tr>
					<td>Псевдоним</td>
					<td>Окружение</td>
					<td>Функции</td>
				</tr>";
		$result = $db->sql_query("SELECT * from ".$prefix."_authors");
		while ($row = $db->sql_fetchrow($result)) {
			$a_aid = filter($row['aid'], "nohtml");
			$name = filter($row['name'], "nohtml");
			//$editor=filter($row['editor'],"nohtml");
			$a_aid = strtolower(substr($a_aid, 0,25));
			$name = substr($name, 0,50);
			if ($editor==='1') $editor = "Да"; else $editor = "Нет";
			echo "<tr><td>".$a_aid."</td>
			<td align='center'>".$editor."</td>
			<td><a href='sys.php?op=admins_edit&amp;chng_aid=".$a_aid."' title='Редактировать'><span class=\"icon black small\" data-icon=\"7\"></span></a>
			<a href='sys.php?op=admins_delete&amp;aid_del=".$a_aid."' title='Удалить'><span class=\"icon red small\" data-icon=\"F\"></span></a></td></tr>";
		}
		echo "</table>";
		echo "<h1>Добавить администратора</h1>
			<form action='sys.php' method='post'>
			<table class='table_light'><tr><td>Псевдоним:</td>
			<td colspan='3'><input type='text' name='add_aid' size='30' maxlength='25'></td></tr>
			<tr><td>Пароль</td>
			<td colspan='3'><input type='text' name='add_pwd' size='30' maxlength='40'></td></tr>
			<tr><td>Режим редактора</td>
			<td colspan='3'>".select('options[add_editor]','1,0','ДА,НЕТ','1')." Скрыть Настройки и Оформление</td></tr>
			<tr><td colspan='3'><input type='hidden' name='op' value='admins_add'><input type='submit' value='Добавить администратора'></td></tr></table>
			</form></div>";
	}
	echo "</div>

	<div class='show_pole pl10' id='show_options_oldfotos' style='display:none;'>Загружаю...</div>
	</div>

	</td></tr></table>
	<br></div>
	</body>
	</html>";
	}
	//////////////////////////////////////////
	function admins_edit_save($add=0, $add_aid, $add_pwd, $add_editor, $old_name=0) { // доработать
		global $admin, $prefix, $db, $admin_file;
		// aid name pwd realadmin link all
		// admin BOG c4ca4... 1 _ 0
		if ($add_editor == "1") $add_name = "EDITOR"; else $add_name = "ADMIN";
		$add_pwd = md5($add_pwd);
		$add_aid = substr($add_aid, 0, 25);
		if ($add == 1) { // Создадим админа
			$result = $db->sql_query("INSERT INTO ".$prefix."_authors SET `aid`='".$add_aid."', `name`='".$add_name."', `pwd`='".$add_pwd."', `realadmin`='1', `link`='', `all`='0'");
		} else { // Отредактируем админа
			if (!($add_aid && $add_pwd && $add_editor))
				Header("Location: ".$admin_file.".php?op=options");
			else
				$db->sql_query("UPDATE ".$prefix."_authors SET `aid`='".$add_aid."', name='".$add_name."', pwd='".$add_pwd."' WHERE aid='".$old_name."'");
		}
		Header("Location: ".$admin_file.".php?op=options");
	}

	function admins_delete($aid_del) { // доработать
		global $db, $prefix, $admin_file;
		$aid_del = trim(filter($aid_del,"nohtml"));
		$crow = $db->sql_fetchrow($db->sql_query("SELECT `name` from ".$prefix."_authors WHERE `aid`='".$aid_del."' LIMIT 1"));
		if ($name != 'BOG')
			$db->sql_query("DELETE FROM ".$prefix."_authors WHERE `aid`='".$aid_del."'");
		Header("Location: ".$admin_file.".php?op=options");
	}
/////////////////////////////////////////////////
	function subscribe() {
		global $admin, $prefix, $db;
		include ("ad/ad-header.php");
		$sql5 = "SELECT num, avtor, mail from ".$prefix."_pages_comments where `mail`!='' order by num";
		$result5 = $db->sql_query($sql5);
		$numrows = $db->sql_numrows($result5);
		$nu = 0; // счетчик email для разбиения по 25 штук
		$nu2 = 0; // счетчик подписанных на рассылку
		$echo = ""; 
		$mails2 = array();
		while ($row5 = $db->sql_fetchrow($result5)) {
			$avtor = $row5['avtor'];
			
			$mails = trim(strip_tags($row5['mail']));
			if ( !in_array($mails,$mails2) and strpos($mails, "@") and strpos($mails, ".") ) {
				$nu++;
				$mails2[] = $mails;
				if ($row5['num'] == 0) { $nu2++; $echo .= "\"<b>".$avtor."</b>\" &lt;".str_replace(" ", "", $mails)."&gt;, "; }
				else $echo .= "\"".$avtor."\" &lt;".str_replace(" ", "", $mails)."&gt;, ";
				if ($nu == 25) { $echo .= "<hr>"; $nu = 0; }
			}
		}
		echo "<span class=h1 style='font-size:20pt;'>Рассылка</span>
		<div class='block radius'><h2>Адреса Email из комментариев: всего ".count($mails2).", разбито по 25 штук.</h2><a class='nothing punkt dark_pole' onclick=\"show_animate('show_about');\">Справочная информация</a><div id='show_about' style='display:none;'>";
		if ($nu2 > 0) echo "<p><b>Подписавшиеся на рассылку выделены жирным, всего: ".$nu2.".</b></p>";
		echo "<p>Можно вставлять для отправки сразу после исправления имен, если они набраны неправильно.</p>
		<p><b>Внимание!</b> Рассылку лучше всего делать со специально зарегистрированного для этого email адреса. Не желательно писать в письме рассылки адрес сайта со ссылкой только на одну страницу. Ни в коем случае не делать рекламные рассылки! Это может быть расценено как спам, а сайт могут просто закрыть!</p><p><b>Разрешается делать:</b>
		<li>Обзорные рассылки — много ссылок на разные материалы
		<li>Извещение о начале какого-то конкурса или массового события
		<li>Поздравительные праздничные рассылки
		<hr><br></div><br><br>".$echo."</div>
		</body>
		</html>";
		
	}
/////////////////////////////////////////////////////////////////////////////////////////////
	switch($op) {
		case "options":
		if (!isset($save)) $save = "";
		options($save);
		break;

		case "options_save":
			global $prefix, $db, $options;
			$mini_blocks = $options['company_name']."|||||".$options['company_fullname']."|||||".$options['company_address']."|||||".$options['company_time']."|||||".$options['company_tel']."|||||".$options['company_sot']."|||||".$options['company_fax']."|||||".$options['company_email']."|||||".$options['company_map']."|||||".$options['company_people'];
			$advanced = $options['jqueryui']."|".$options['show_comments']."|".$options['show_userposts']."|".$options['show_page']."|".$options['show_reserv']."|".$options['uskorenie_blokov']."|".$options['kickstart']."|".$options['show_page_links']."|".$options['ad_fon']."|".$options['search_design']."|".$options['tag_design']."|".$options['add_fonts']."|".$options['normalize']."|".$options['project_logotip']."|".$options['project_name']."|".$options['geo']."|".$options['kolkey']."|".$options['add_clips']."|".$options['sortable']."|".$options['color_tema_html']."|".$options['color_tema_css']."|".$options['color_tema_js']."|".$options['color_tema_php']."|".$options['tab_obzor']."|".$options['tab_show']."|".$options['shop_text_val1']."|".$options['shop_text_val2']."|".$options['shop_text_itogo']."|".$options['shop_text_oformit']."|".$options['shop_text_korzina']."|".$options['shop_text_delete']."|".$options['shop_pole']."|".$options['shop_admin_mail']."|".$options['shop_text_after_mail']."|".$options['shop_spisok_pole']."|".$options['shop_shablon_form_order']."|".$options['shop_shablon_mail_client']."|".$options['shop_shablon_mail_admin']."|".$options['ed2_button_html']."|".$options['ed2_button_formatting']."|".$options['ed2_button_bold']."|".$options['ed2_button_italic']."|".$options['ed2_button_deleted']."|".$options['ed2_button_underline']."|".$options['ed2_button_unorderedlist']."|".$options['ed2_button_orderedlist']."|".$options['ed2_button_outdent']."|".$options['ed2_button_indent']."|".$options['ed2_button_image']."|".$options['ed2_button_video']."|".$options['ed2_button_file']."|".$options['ed2_button_table']."|".$options['ed2_button_link']."|".$options['ed2_button_alignment']."|".$options['ed2_button_horizontalrule']."|".$options['ed2_button_more']."|".$options['ed2_button_link2']."|".$options['ed2_button_block']."|".$options['ed2_button_pre']."|".$options['ed2_button_fullscreen']."|".$options['ed2_button_clips']."|".$options['ed2_button_fontcolor']."|".$options['ed2_button_fontsize']."|".$options['ed2_button_fontfamily']."|".$options['ed2_minHeight']."|".$options['ed2_direction']."|".$options['head_insert']."|".$options['filter_name']."|".$options['filter_show_all']."|".$options['gravatar']."|".$options['ed2_div_convert']."|".$options['strelka']."|".$options['smile_icons']."|".$options['add_mail_shablons']."|".$options['avtor_comments']."|".$options['search_in_pages']."|".$options['search_in_papka']."|".$options['search_in_razdel']."|".$options['newsmail_design']."|".$options['search_col_razdel']."|".$options['search_col_papka']."|".$options['search_col_page']."|".$options['search_col_showall']."|".$options['scrollyeah']."|".$options['lightload']."|".$options['spin']."|".$options['razdel_sort']."|".$options['show_admin_top']."|".$options['ed2_paragraphy']."|".$options['ed2_button_superscript'];
			// sitename	startdate	adminmail	keywords	description	counter	statlink	postlink	registr	pogoda	flash	sgatie	stopcopy	nocashe	adminmes	red	comment	captcha_ok	ht_backup
			$db->sql_query("UPDATE `".$prefix."_config` SET 
				`sitename` = '".mysql_real_escape_string($options['sitename'])."',
				`startdate` = '".mysql_real_escape_string($options['startdate'])."',
				`adminmail` = '".mysql_real_escape_string($options['adminmail'])."',
				`keywords` = '".mysql_real_escape_string($options['keywords'])."',
				`description` = '".mysql_real_escape_string($options['description'])."',
				`counter` = '".mysql_real_escape_string($options['counter'])."',
				`statlink` = '".mysql_real_escape_string($options['statlink'])."',
				`postlink` = '".mysql_real_escape_string($options['postlink'])."',
				`registr` = '".mysql_real_escape_string($options['registr'])."',
				`pogoda` = '".mysql_real_escape_string($options['pogoda'])."',
				`flash` = '".mysql_real_escape_string($options['flash'])."',
				`sgatie` = '".mysql_real_escape_string($mini_blocks)."',
				`stopcopy` = '".mysql_real_escape_string($options['stopcopy'])."',
				`nocashe` = '".mysql_real_escape_string($advanced)."',
				`comment` = '".mysql_real_escape_string($options['comment_send'])."',
				`captcha_ok` = '".mysql_real_escape_string($options['captcha_ok'])."',
				`ht_backup` = '".mysql_real_escape_string($options['ht_backup'])."' LIMIT 1 ;") or die ('Настройки не сохранилось. Видимо забыли обновить базу данных или файл настройки администрирования.');
			Header("Location: sys.php?op=options&save=1");
			break;
		case "subscribe":
			subscribe();
			break;
		case "update_author":
			if ($_POST['op'] != 'update_author') exit;
			updateadmin($chng_aid, $chng_name, $chng_pwd, $chng_pwd2, $adm_aid);
			break;
		case "ipban_delete":
			ipban_delete($id);
			break;
		case "ipban_edit":
			ipban_edit($id);
			break;
		case "ipban_save":
			ipban_save($id, $ip_address, $reason);
			break;
		case "admins_edit":
			admins_edit();
			break;
		case "admins_edit_save":
			admins_edit_save(0, $add_aid, $add_pwd, $add_editor);
			break;
		case "admins_add":
			admins_edit_save(1, $add_aid, $add_pwd, $add_editor);
			break;
		case "admins_delete":
			admins_delete($aid_del);
			break;
	}
} else die('Доступ закрыт!<br>Возможно, вы только что сменили имя и/или пароль администратора — тогда перейдите ко <a href="/sys.php?op=login">входу в администрирование</a>.');
?>