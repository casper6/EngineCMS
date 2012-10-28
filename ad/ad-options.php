<?php
if (!defined('ADMIN_FILE')) {
	die ("Доступ закрыт!");
}
global $prefix, $db, $admin_file;
$aid = substr("$aid", 0,25);
$row = $db->sql_fetchrow($db->sql_query("SELECT realadmin FROM " . $prefix . "_authors WHERE aid='$aid'"));
if ($row['realadmin'] == 1) {


	function main_ban($ip=0) {
		global $prefix, $db, $bgcolor2, $admin_file;
		echo "<h2>Блокировка посетителей (запрет использования сайта)</h2>";
		$numrows = $db->sql_numrows($db->sql_query("SELECT * from ".$prefix."_banned_ip"));
		if ($numrows != 0) {
			echo "<a class='punkt dark_pole' onclick=\"show_animate('show_stop_users');\"><img class='icon2 i3' src='/images/1.gif' align='bottom'>Заблокированные по IP-адресу посетители: $numrows</a><div id='show_stop_users' style='display:none;'>
			"
			."<table class=table_light>"
			."<tr><td bgcolor=\"$bgcolor2\" align='left'><b>IP-адрес</b>&nbsp;</td>"
			."<td bgcolor=\"$bgcolor2\" align='left'><b>Причина блокировки</b>&nbsp;</td>"
			."<td bgcolor=\"$bgcolor2\" align='center'><b>Дата запрета</b>&nbsp;</td>"
			."<td bgcolor=\"$bgcolor2\" align='center'><b>Функции</b>&nbsp;</td></tr>";
			$result = $db->sql_query("SELECT * from ".$prefix."_banned_ip ORDER by date DESC");
			while ($row = $db->sql_fetchrow($result)) {
				$row['reason'] = filter($row['reason'], "nohtml");
				echo "<tr><td bgcolor=\"$bgcolor2\" align='left'>".$row['ip_address']."</td>"
				."<td bgcolor=\"$bgcolor2\">".$row['reason']."&nbsp;</td>"
				."<td bgcolor=\"$bgcolor2\" align='center' nowrap>".date2normal_view($row['date'])."&nbsp;</td>"
				."<td bgcolor=\"$bgcolor2\" align='center'><a href=\"".$admin_file.".php?op=ipban_edit&amp;id=".intval($row['id'])."\"><img class=\"icon2 i34\" src=/images/1.gif title=\"Редактировать\"></a>&nbsp;<a href=\"".$admin_file.".php?op=ipban_delete&amp;id=".intval($row['id'])."\"><img class=\"icon2 i21\" src=/images/1.gif title=\"Снять запрет\"></a>&nbsp;</td></tr>";
			}
			echo "</table></div>";
		}
		echo "<br><br><form action='".$admin_file.".php' method='post'>
		<table><tr><td>Введите IP-адрес пользователя:</td><td width=20></td><td>Причина блокировки:</td></tr><tr><td>";
		if ($ip != 0) {
			$ip = explode(".", $ip);
			echo "<input type='text' class=polosa name='ip1' size='4' maxlength='3' value='$ip[0]'> . <input type='text' class=polosa name='ip5' size='4' maxlength='3' value='$ip[1]'> . <input type='text' class=polosa name='ip3' size='4' maxlength='3' value='$ip[2]'> . <input type='text' class=polosa name='ip4' size='4' maxlength='3' value='$ip[3]'>";
		} else {
			echo "<input type='text' class=polosa name='ip1' size='3' maxlength='3'>.<input type='text' class=polosa name='ip5' size='3' maxlength='3'>.<input type='text' class=polosa name='ip3' size='3' maxlength='3'>.<input type='text' class=polosa name='ip4' size='3' maxlength='3'>";
		}
		echo "</td><td width=20></td><td><input type='text' class=polosa name='reason' size='20' maxlength='255'><input type='submit' value='ЗАПРЕТИТЬ'><input type='hidden' name='op' value='save_banned'>
		</td></tr></table></form>";
	}

	function ipban_delete($id) {
		global $prefix, $db, $admin_file;
		$id = intval($id);
		$db->sql_query("DELETE FROM ".$prefix."_banned_ip WHERE id=".$id);
		Header("Location: ".$admin_file.".php?op=Configure");
	}

	function ipban_edit($id) {
		global $prefix, $db, $bgcolor2, $admin_file;
		$id = intval($id);
		$row = $db->sql_fetchrow($db->sql_query("SELECT * from ".$prefix."_banned_ip WHERE id='$id'"));
		include ("ad-header.php");
		echo "<h3>Блокировка пользователей (запрет использования сайта)</h3><b>Измените IP-адрес пользователя:</b><br>";
		echo "<form action='".$admin_file.".php' method='post'>";
		$ip = explode(".", $row['ip_address']);
		$reason = filter($row['reason'], "nohtml");
		echo "<input type='text' class=polosa name='ip1' size='4' maxlength='3' value='".$ip[0]."'> . <input type='text' class=polosa name='ip5' size='4' maxlength='3' value='".$ip[1]."'> . <input type='text' class=polosa name='ip3' size='4' maxlength='3' value='".$ip[2]."'> . <input type='text' class=polosa name='ip4' size='4' maxlength='3' value='".$ip[3]."'>";
		echo "<br><br><b>...или причину запрета:</b><br><input type='text' class=polosa name='reason' size='50' maxlength='255' value='".$reason."'><br><br>";
		echo "<input type='hidden' name='id' value='".$id."'><input type='hidden' name='op' value='ipban_save'>";
		echo "<input type='submit' value='Сохранить изменения'><br></center>";
		echo "</form>";
		admin_footer();
	}

	function ipban_save($id=0, $ip1, $ip5, $ip3, $ip4, $reason) {
		global $prefix, $db, $admin_file;
		include ("ad-header.php");
		$id = intval($id);
		$ip = $ip1.'.'.$ip5.'.'.$ip3.'.'.$ip4;
		if (($ip1 == "" OR $ip5 == "" OR $ip3 == "" OR $ip4 == "") or ($ip1 > 255 OR $ip5 > 255 OR $ip3 > 255 OR $ip4 > 255 && $ip4 != "*") ) {
			echo "<center><b>Ошибка: </b> Введенный адрес неправильный: одна из цифр меньше 0 или больше 255! <b>$ip1.$ip5.$ip3.$ip4</b><br><br>Вернуться</center>";
			die();
		}
		if (!is_numeric($ip1) && !empty($ip1) OR !is_numeric($ip5) && !empty($ip5) OR !is_numeric($ip3) && !empty($ip3) OR !is_numeric($ip4) && !empty($ip4) && $ip4 != "*") {
			echo "<center><b>Ошибка: </b> Введенный адрес неправильный: нужно вводить только цифры от 0 до 255! <b>$ip1.$ip5.$ip3.$ip4</b><br><br>Вернуться</center>";
			die();
		}
		if (substr($ip1, 0, 1) == 0) {
			echo "<center><b>Ошибка: </b> Введенный адрес неправильный: ip-адрес не может начинаться с нуля! <b>$ip1.$ip5.$ip3.$ip4</b><br><br>Вернуться</center>";
			die();
		}
		if ($ip == "127.0.0.1") {
			echo "<center><b>Ошибка: </b> Введенный адрес неправильный: введен адрес вашего компьютера, а не сервера в Интернете! <b>127.0.0.1</b><br><br>Вернуться</center>";
			die();
		}
		$my_ip = $_SERVER["REMOTE_ADDR"];
		if ($ip == $my_ip) {
			echo "<center><b>Ошибка: </b> Введенный адрес неправильный: введен ваш сетевой адрес! <b>$ip</b><br><br>Вернуться</center>";
			die();
		}
		$reason = filter($reason, "nohtml");
		$date = date("Y-m-d");
		if ($id==0) $db->sql_query("INSERT INTO ".$prefix."_banned_ip VALUES (NULL, '$ip', '$reason', '$date')");
		else $db->sql_query("UPDATE ".$prefix."_banned_ip SET ip_address='$ip', reason='$reason' WHERE id='$id'");
		Header("Location: ".$admin_file.".php?op=Configure");
	}
	///////////////////////////////////////////////////////////////////////////////////////
	function updateadmin($chng_aid, $chng_name, $chng_pwd, $chng_pwd2, $adm_aid) {
		global $siteurl, $admin, $prefix, $db, $admin_file;
			$chng_aid = trim($chng_aid);
			if (!($chng_aid && $chng_name)) {
				Header("Location: ".$admin_file.".php?op=Configure");
			}
			if (!empty($chng_pwd2)) {
				if($chng_pwd != $chng_pwd2) {
					include("ad-header.php");
					echo "Первый пароль не соответствует второму. Вернитесь назад.<br>";
					admin_footer();
					exit;
				}
				$chng_pwd = md5($chng_pwd);
				$chng_aid = strtolower(substr("$chng_aid", 0,25));
				$db->sql_query("update ".$prefix."_authors set aid='$chng_aid', pwd='$chng_pwd' where name='$chng_name' AND aid='$adm_aid'");
				Header("Location: ".$admin_file.".php?op=Configure");
			} else {
				$db->sql_query("update ".$prefix."_authors set aid='$chng_aid' where name='$chng_name' AND aid='$adm_aid'");
				Header("Location: ".$admin_file.".php?op=Configure");
			}
	}
////////////////////////////////////////////////
	function Configure($ok=0) {
		global $prefix, $db, $admin_file, $siteurl, $admin, $ipban;
		include ("ad-header.php");
		$ok=intval($ok);
		// Получаем настройки из mainfile
		global $sitename, $startdate, $adminmail, $keywords, $description, $counter, $statlink, $postlink, $stopcopy, $registr, $pogoda, $flash, $sgatie, $ht_backup, $captcha_ok, $xnocashe, $jqueryui, $show_comments, $show_userposts, $show_page, $show_reserv, $uskorenie_blokov, $kickstart, $show_page_links, $ad_fon, $comment_send, $company_name, $company_fullname, $company_address, $company_time, $company_tel, $company_sot, $company_fax, $company_email, $company_map, $company_people, $search_design, $tag_design, $add_fonts, $site_cash, $normalize, $project_logotip, $project_name;
		
		$ad_fon_option = ""; // Выбор фоновок для админки
		for ($i=1; $i < 28; $i++) { // всего 27 фоновок + 1 по-умолчанию в папке images/ad-fon
			if ($ad_fon == $i) $sel = " selected"; else $sel = "";
			$ad_fon_option .= "<option value='".$i."'".$sel.">фон №".$i."</option>";
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

echo "<table style='width:100%; margin-top:5px; padding:0; background: #e2e5ea;' cellspacing=0 cellpadding=0><tr valign=top><td id='razdel_td' class='radius nothing' width=340>

			<div id='razdels' style='background:#e7e9ec;'>
			<div class='black_grad'><span class='h1'>Настройки".$opt_save."</span></div>";
	echo "<div id='mainrazdel8' class='dark_pole2'><a class='base_page' onclick=\"options_show('8','show_first')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\".\"></span><span class='plus20'>Перед началом разработки</span></div></a></div>";
	echo "<div id='mainrazdel2' class='dark_pole2'><a class='base_page' onclick=\"options_show('2','show_options_fonts')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"i\"></span><span class='plus20'>Подключение шрифтов</span></div></a></div>";
	echo "<div id='mainrazdel0' class='dark_pole2'><a class='base_page' onclick=\"options_show('0','show_options')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"Z\"></span><span class='plus20'>Основные настройки Сайта</span></div></a></div>";
	echo "<div id='mainrazdel1' class='dark_pole2'><a class='base_page' onclick=\"options_show('1','show_options_company')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"Y\"></span><span class='plus20'>Карточка компании (мини блоки)</span></div></a></div>";
	echo "<div id='mainrazdel3' class='dark_pole2'><a class='base_page' onclick=\"options_show('3','show_options_adspeed')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"z\"></span><span class='plus20'>Настройки Администрирования</span></div></a></div>";
	echo "<div id='mainrazdel4' class='dark_pole2'><a class='base_page' onclick=\"options_show('4','show_options_pass_block')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"O\"></span><span class='plus20'>Смена пароля и Блокировка по IP</span></div></a></div>";
	echo "<div id='mainrazdel7' class='dark_pole2'><a class='base_page' onclick=\"options_show('7','show_options_oldfotos'); trash_pics();\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"1\"></span><span class='plus20'>Удаление неиспользуемых фотографий</span></div></a></div>";
	echo "<div id='mainrazdel5' class='dark_pole2'><a class='base_page' href='sys.php?op=subscribe'><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"@\"></span><span class='plus20'>Рассылка (список адресатов)</span></div></a></div>";
	echo "<div id='mainrazdel6' class='dark_pole2'><a class='base_page' href='sys.php?op=users'><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"U\"></span><span class='plus20'>Пользователи (в разработке!)</span></div></a></div>";

	echo "</div></td><td><form action='".$admin_file.".php' method='post' name=\"form\">";

echo "<div id='show_first' class='show_pole' style='display:none;'>
<table class=table_light>


<tr valign=top><td style='min-width:250px;'>
<b>Включить <a href='http://necolas.github.com/normalize.css/' target='_blank'>normalize.css</a></b>:</td><td class=small>
".select("options[normalize]", "0,1", "НЕТ,ДА", $normalize)."<br>
Нормализация отличается от подхода reset.css тем, что выравнивает различия стандартных стилей разных браузеров и пытается нейтрализовать баги, не сбрасывая при этом самих стандартных стилей.
</td></tr>

<tr valign=top><td style='min-width:250px;'>
<b>Включить CSS-фреймворк</b>:</td><td class=small>
".select("options[kickstart]", "0,1,2,3,4,5,6,7,8,9,10,11", "-- НЕТ --,KickStart,CSSframework,Skeleton,Kube,Bootstrap,1140 Grid,Toast,Blueprint,YUI CSS Grids,960gs (12 и/или 16 колонок),960gs (24 колонки)", $kickstart, ' id=kickstart onchange="if ( $(\'#kickstart\').val() == 1) $(\'#frame1\').show(); else $(\'#frame1\').hide(); if ( $(\'#kickstart\').val() == 6) $(\'#frame6\').show(); else $(\'#frame6\').hide(); "')."
<br>Ссылка на сайт фреймворка (для просмотра правил оформления CSS и HTML) появится наверху администрирования.

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

}
</pre>
</td></tr>

<tr valign=top><td style='min-width:250px;'>
<b>Включить <a href='http://jqueryui.com' target='_blank'>jQuery UI</a></b>:</td><td class=small>
".select("options[jqueryui]", "0,1", "НЕТ,ДА", $jqueryui)."
</td></tr>

</table>
См. также «Подключение шрифтов».

<div style='text-align:center;'><input type='submit' value=' Сохранить настройки ' style='width:300px; height:40px;'></div>
<input type='hidden' name='op' value='ConfigSave'>
</div>



<div id='show_options' class='show_pole' style='display:none;'>
<table class=table_light>

<tr valign=top><td>
Название сайта (для заголовка title):</td><td class=small>
".input("options[sitename]", $sitename, 60)."
</td></tr><tr valign=top><td>
Логотип сайта/компании:</td><td class=small>
".input("options[project_logotip]", $project_logotip, 60)."
<br>Можно вывести в дизайн через блок [лого_проекта]
</td></tr><tr valign=top><td>
Название проекта/компании:</td><td class=small>
".input("options[project_name]", $project_name, 60)."
<br>Можно вывести в дизайн через блок [название_проекта]. Обобщенный вариант через замену картинкой тега H1 — через блок [название_лого_проекта]. А настраивается всё через CSS за счет стилей, которые можно посомтреть в коде страницы.

</td></tr><tr valign=top><td>
Описание сайта:</td><td class=small>
".input("options[description]", $description, 60, "txt")."
<br>Максимум 500 символов. Для поисковых систем (Description)
</td></tr><tr valign=top><td>
Ключевые словосочетания:</td><td class=small>
".input("options[keywords]", $keywords, 60, "txt")."
<br>Максимум 500 символов. Через запятую. Для поисковых систем (Keywords)
</td></tr><tr valign=top class=p2><td>
Ссылка на почтовый сайт:</td><td class=small>
".input("options[postlink]", $postlink, 60)."
<br>Если редактору удобно открывать свой почтовый сайт из системы администрирования сайта.
</td></tr><tr valign=top class=p2><td>
Ссылка на статистику:</td><td class=small>
".input("options[statlink]", $statlink, 60)."
<br>Ссылка на внешнюю статистику Сайта: LiveInternet, Google Analytics, Яндекс.Метрика и другие. Например, если использовать <a href=http://www.liveinternet.ru/add>liveinternet.ru</a> ссылка будет такой: http://pda.liveinternet.ru/stat/".$siteurl."/. Отображается сверху в Администрировании - Статистика.
</td></tr><tr valign=top class=p2><td>
Код счетчика статистики:</td><td class=small>
".input("options[counter]", $counter, 60, "txt")."
<br>Код счетчика внешней статистики Сайта: LiveInternet, Google Analytics, Яндекс.Метрика и другие. Счетчик можно поставить в дизайн как автоматический блок, написав [статистика]. Если вы хотите скрыть счетчик от посетителей - используйте счетчик без цифр (и с закрытой от просмотра статистикой) и/или поставьте счетчик в скрытый слой DIV (тогда ссылка на статистику будет доступна только из Администрирования — ссылка сверху). Если использовать <a href=http://www.liveinternet.ru/add>LiveInternet</a> для получения счетчика — выбрать при получении: «код счетчика в одну строчку» и «учитывать заголовки».
</td></tr>

<tr valign=top class=hide><td>
Регистрация:</td><td class=small>
".select("options[registr]", "0,1", "НЕТ,ЕСТЬ", $registr)."
<br>Вкл./Откл. Регистрацию и Вход пользователей на сайт.  Не работает</td></tr>

<tr valign=top class=p3><td>
Анимация:</td><td class=small>
".select("options[pogoda]", "0,1,2,3", "Отключена,* Снегопад,@ Осенние листья,• Воздушные шарики", $pogoda)."
<br>Фоновая анимация поверх содержания сайта (только на Главной странице).</td></tr>

<tr valign=top class=p3><td>
Полная поддержка Flash:</td><td class=small>
".select("options[flash]", "0,1", "НЕТ,ЕСТЬ", $flash)."
<br>Включать ТОЛЬКО при проблемах отображения SWF-файлов, FLA-видео и Flash-анимации на сайте.
</td></tr>

<tr valign=top class=p3><td>
Простая защита от копирования: </td><td class=small>
".select("options[stopcopy]", "0,1", "НЕТ,ЕСТЬ", $stopcopy)."
<br>Препятствует массовому копированию статей на чужие сайты глупыми злоумышленниками. Невозможность выделить/скопировать текст и нажать правую кнопку мыши. </td></tr>

<tr valign=top class=p3><td>
Защита комментариев: </td><td class=small>
".select("options[captcha_ok]", "1,0", "НЕТ,ЕСТЬ", $captcha_ok)."
<br>По умолчанию - вкл. При отключении можно не вводить проверочный код или вводить его неправильно — он не будет проверяться. </td></tr>

<tr valign=top class=p4><td>
Год основания сайта:</td><td class=small>
".input("options[startdate]", $startdate, 4)."
<br>Используется в автоматическом блоке [год], год основания - текущий год, для отображения внизу сайта, в строке копирайта, например © 2007-2009.
</td></tr>

<tr valign=top class=p4><td>
Почта администратора:</td><td class=small>
".input("options[adminmail]", $adminmail, 60)."
<br>Используется в автоматическом блоке [почта], для отправки почты администратору сайта.
</td></tr>

<tr valign=top class=p4><td>
Получать письма о новых комментариях:</td><td class=small>
".select("options[comment]", "0,1", "НЕТ,ДА", $comment_send)."
<br>Отправка уведомлений о новых комментариях на почту администратору.
</td></tr>

<tr valign=top><td>
Показывать в администрировании страницы, добавленные посетителями:</td><td class=small>
".select("options[show_userposts]", "0,1,2", "НЕТ,ДА,Точка вместо кнопки (скрытый вариант)", $show_userposts)."
</td></tr>

<tr valign=top><td>
Показывать комментарии в администрировании:</td><td class=small>
".select("options[show_comments]", "0,1,2", "НЕТ,ДА,Точка вместо кнопки (скрытый вариант)", $show_comments)."
</td></tr>

<tr valign=top><td>
Дизайн для страницы поиска:</td><td>
".select("options[search_design]", $id_designs, $title_designs, $search_design)." В дизайне должен быть блок [содержание]
</td></tr>

<tr valign=top><td>
Дизайн для страницы тэгов (ключевых слов):</td><td class=small>
".select("options[tag_design]", $id_designs, $title_designs, $tag_design)." В дизайне должен быть блок [содержание]
</td></tr>

<tr valign=top><td>
Преобразование {Название раздела} и {Название страницы} в ссылки на эти раздел и страницу, соответственно:</td><td class=small>
".select("options[show_page_links]", "0,1", "НЕТ,ДА", $show_page_links)." 
<br>По-умолчанию отключено. Если вы создаете сайт с большим количеством страниц (более 500) — желательно отключить. В случае отключения останется возможность преобразования названий разделов.
</td></tr>

</table>
<div style='text-align:center;'><input type='submit' value=' Сохранить настройки ' style='width:300px; height:40px;'></div>
<input type='hidden' name='op' value='ConfigSave'>
</div>

<div id='show_options_fonts' class='show_pole' style='display:none;'>
<p>Вы можете выбрать дополнительные шрифты (<i><a href='http://www.google.com/webfonts/' target='_blank'>от Google</a></i>) и эффекты для них.<br>Эффекты выбирать необязательно.</p>";

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
		$options3 .= "<option value=\"".$font."\">".$font."</option>";
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
	if (font != null) $('#add_fonts').append('<option value=\"' + add + '\">' + add + '</li>');
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
<div id='sgladit_shrift' style=\"display:none;\"><pre>
.title {
   text-shadow:-1px -1px 1px rgba(255,255,255,0.2), /* наверх и влево */
   1px 1px 1px rgba(255,255,255,0.2), /* вниз и вправо */
   1px 1px 1px rgba(0,0,0,0.7); /* тёмная тень */
}
</pre></div>
<p>
<div style='text-align:center;'><input type='submit' value=' Сохранить настройки ' style='width:300px; height:40px;'></div>
</div>

<div id='show_options_company' class='show_pole' style='display:none;'>
<p>Основные данные компании для быстрой замены. Могут быть вставлены в любом месте сайта как мини-блоки.
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
<div style='text-align:center;'><input type='submit' value=' Сохранить настройки ' style='width:300px; height:40px;'></div>
</div>


<div id='show_options_adspeed' class='show_pole' style='display:none;'>";

if ($site_cash == true) echo "<a class='button' target='_blank' href='?cash=del' style='margin-bottom:5px;'><span class=\"icon medium gray\" data-icon=\"T\"></span>Очистить кеш</a>";
else echo "<div class=\"notice warning\">Кеширование страниц отключено. Включить можно через config.php</div>";

echo "<table class=table_light>
<tr valign=top><td style='min-width:250px;'>
Фоновая картинка Администрирования:</td><td class=small>
<select name=options[ad_fon] onchange=\"$(body).css('backgroundImage', 'url(images/adfon/' + $(this).val() + '.png)')\"><option value='0'>по-умолчанию</option>".$ad_fon_option."</select>
</td></tr>
<tr valign=top><td>
Создание резервной копии:</td><td class=small>
".select("options[show_reserv]", "0,1", "НЕТ,ДА", $show_reserv)."
<br>Каждый день, при посещении администратором главной страницы администрирования, создается резервная копия всего содержания сайта, кроме файлов (документы, архивы, фотографии), закачанных на сервер. Если это большой портал на скромном хостинге, создание копии можно отключить для экономии файлового места.
</td></tr>
<tr valign=top><td>
Отображать количества страниц в разделах (на вкладке Содержание):</td><td class=small>
".select("options[show_page]", "0,1", "НЕТ,ДА", $show_page)."
<br>Отключать его имеет смысл, если создано очень много страниц (более 20 тысяч) и хочется ускорить загрузку вкладки Содержание на 1-2 секунды.
</td></tr>
<tr valign=top><td>
Включить ускорение вывода блоков (на вкладке Оформление):</td><td class=small>
".select("options[uskorenie_blokov]", "0,1", "НЕТ,ДА", $uskorenie_blokov)."
<br>Отключать его имеет смысл, если создано много блоков и  страница с ними долго загружается. Ускорение убирает информацию об использовании блоков. 
</td></tr>
<tr valign=top><td>
Файл с резервной копией .htaccess:</td><td class=small>
".input("options[ht_backup]", $ht_backup)."
<br>Для автовосстановления в случае поражения «вирусом».
</td></tr>


</table>
<div style='text-align:center;'><input type='submit' value=' Сохранить настройки ' style='width:300px; height:40px;'></div>
</div>
</form>";

///////////////////////////////////////////////////////////////////////////////////////////
$result = $db->sql_query("SELECT aid, name from " . $prefix . "_authors where name='BOG'");
$row = $db->sql_fetchrow($result);
$adm_aid = filter($row['aid'], "nohtml");
$adm_aid = trim(strtolower(substr("$adm_aid", 0,25)));
echo "<div id='show_options_pass_block' class='show_pole' style='display:none;'>";
		if (!isset($ip)) $ip="";
		if ($ipban != false) {
			echo "<a class='nothing punkt dark_pole' onclick=\"show_animate('show_options3');\"><img class='icon2 i43' src='/images/1.gif' align=bottom>Блокировка посетителей</a><div id='show_options3' style='display:none;'>";
			main_ban($ip);
			echo "<br><br></div>";
		} else echo "<div class=\"notice warning\">Блокировка посетителей отключена. Включить можно через config.php</div>";
		echo "

		<h2>Смена пароля администратора:</h2>";
		$row = $db->sql_fetchrow($db->sql_query("SELECT aid, name, pwd from " . $prefix . "_authors where name='BOG'"));
		$chng_aid = filter($row['aid'], "nohtml");
		$chng_name = filter($row['name'], "nohtml");
		$chng_pwd = filter($row['pwd'], "nohtml");
		$chng_aid = strtolower(substr("$chng_aid", 0,25));
		$aid = $chng_aid;
		echo "<form action=".$admin_file.".php method=post>
		<table class=tight>
		<tr><td align=right>Псевдоним:</td>
		<td><input type=text name=chng_aid value='".$chng_aid."' size=20 maxlength=25></td></tr>
		<tr><td align=right>Пароль:</td>
	    <td><input type=password name=chng_pwd size=20 maxlength=40></td></tr>
		<tr><td align=right>Пароль еще раз:</td>
		<td><input type=password name=chng_pwd2 size=20 maxlength=40></td></tr>
		<tr><td colspan=2><input type=submit value=' Сохранить '></td></tr></table>
		<input type=hidden name=chng_name value='".$chng_name."'>
		<input type=hidden name=adm_aid value='".$adm_aid."'>
		<input type=hidden name=op value='UpdateAuthor'>
		</form>


		<br><br><div class=\"notice warning hide\"><a href='sys.php?op=AdminsList'>Список администраторов</a></div>
		</div>";

 echo "<div class='show_pole' id='show_options_oldfotos' style='display:none;'>Загружаю...</div>

</div>

</td></tr></table>
</body>
</html>";
	}
///////////////////////////////////////////////////////////////////
	function AdminsList() {
		global $admin, $prefix, $db, $admin_file, $bgcolor2, $modules_info;
		include ("ad-header.php");

		echo "<center><font class=\"option\"><b>Редактирование админов</b></font></center><br>";
		echo "	<table border=\"1\" align=\"center\">
				<tr>
					<td>&nbsp;<b>Псевдоним</b>&nbsp;</td>
					<td>&nbsp;<b>Окружение</b>&nbsp;</td>
					<td>&nbsp;<b>Функции</b>&nbsp;</td>
				</tr>
		";
		$result = $db->sql_query("SELECT * from ".$prefix."_authors");
		while ($row = $db->sql_fetchrow($result)) {
			$a_aid = filter($row['aid'], "nohtml");
			$name = filter($row['name'], "nohtml");
			//$editor=filter($row['editor'],"nohtml");
			$a_aid = strtolower(substr($a_aid, 0,25));
			$name = substr($name, 0,50);
			echo "	<tr>";
			if ($name == "God") {
				echo "	<td>&nbsp;".$a_aid." <i>(главный аккаунт)</i>&nbsp;</td>";
			} 
			else {
				echo "	<td>&nbsp;".$a_aid."&nbsp;</td>";
			}
			if ($editor==='0') $editor="Да";
			else $editor="Нет";
			echo "<td align=\"center\">&nbsp;".$editor."</td>
					<td align=\"center\">
						<a href=\"".$admin_file.".php?op=AdminsEdit&amp;chng_aid=".$a_aid."\"><img src=\"images/edit.png\" alt=\"Редактировать\" title=\"Редактировать\" border=\"0\" width=\"17\" height=\"17\"></a>
			";
			if($name == "God") {
				echo "		<img src=\"images/delete_x.png\" alt=\"Главный аккаунт\" title=\"Главный аккаунт\" border=\"0\" width=\"17\" height=\"17\"></a></td>";
			} 
			else {
				echo "		<a href=\"".$admin_file.".php?op=AdminsDelete&amp;aid=".$a_aid."\"><img src=\"images/delete.png\" alt=\"Удалить\" title=\"Удалить\" border=\"0\" width=\"17\" height=\"17\"></a></td>";
			}
			echo "	</tr>";
		}
		echo "	</table><br><br>";

		echo "<center><font class=\"option\"><b>Добавить админа</b></font></center>
			<form action=\"".$admin_file.".php\" method=\"post\">
			<table border=\"0\">
				<tr>
					<td>Имя (необязательно):</td>
					<td colspan=\"3\"><input type=\"text\" name=\"add_name\" size=\"30\" maxlength=\"50\"> </td>
				</tr>
				<tr>
					<td>Псевдоним/ник, до 25 символов:</td>
					<td colspan=\"3\"><input type=\"text\" name=\"add_aid\" size=\"30\" maxlength=\"25\"></td>
				</tr>
				<tr>
					<td>Какую страницу открывать (ссылка, необязательно):</td>
					<td colspan=\"3\"><input type=\"text\" name=\"add_email\" size=\"30\"></td>
				</tr>
		";
		echo "	<tr>
					<td>Скрыть Настройки и Оформление (режим редактора)?</td>
					<td colspan=\"3\">
						<select name=\"add_editor\">
							<option name=\"add_editor\" value=\"0\">Да</option>
							<option name=\"add_editor\" value=\"1\">Нет</option>
						</select>
					</td>
				</tr>
		";
		echo "	<tr>
					<td>Пароль</td>
					<td colspan=\"3\"><input type=\"text\" name=\"add_pwd\" size=\"12\" maxlength=\"40\"></td>
				</tr>
				<tr>
					<td colspan=\"3\">
						<input type=\"hidden\" name=\"op\" value=\"AdminsAdd\">
						<input type=\"submit\" value=\"Добавить администратора\">
					</td>
				</tr>
			</table>
			</form>
		";
		admin_footer();
	}

	function AdminsAdd() { // доработать
		global $db, $prefix, $admin_file, $modules_info;
		$ok=intval($_POST['ok']);
		$add_aid=$_POST['add_aid'];
		$add_name=$_POST['add_name'];
		$add_email=$_POST['add_email'];
		$add_url='';
		$add_radminsuper=intval($_POST['add_radminsuper']);
		$add_pwd=$_POST['add_pwd'];
		$add_admlanguage=$_POST['add_admlanguage'];
		$add_editor=filter($_POST['add_editor']);
		$auth_modules=$_POST['auth_modules'];
		$add_aid = strtolower(substr($add_aid, 0,25));
		$add_name = substr($add_name, 0,25);
		if (!($add_aid && $add_name && $add_email && $add_pwd)) {
			include("ad-header.php");
			echo "<center><b>Ошибка создания</b><br>Заполните поля, вернувшись назад</center>";
		}
		if ($ok==1) {
			$add_pwd = md5($add_pwd);
			$result = $db->sql_query("INSERT INTO ".$prefix."_authors SET
				aid='".$add_aid."',
				name='".$add_name."',
				url='".$add_url."',
				email='".$add_email."',
				pwd='".$add_pwd."',
				counter='0',
				radminsuper='".$add_radminsuper."',
				admlanguage='".$add_admlanguage."',
				editor='".$add_editor."'"
			);
			$auth_modules=unserialize(base64_decode($auth_modules));
			$so=sizeof($auth_modules);
			if (is_array($auth_modules) AND $so>0) {
				$tmp=array();
				for ($i=0; $i < $so; $i++) {
					foreach ($modules_info as $k=>$v) {
						if (intval($auth_modules[$i])==intval($v['mid'])) {
							$tmp[]=intval($v['mid']);
						}
					}
				}
				$tmp=implode(",",$tmp);
				$sql="UPDATE ".$prefix."_modules SET admins=CONCAT(admins,'".$add_name."',',') WHERE mid IN (".$tmp.")";
				$db->sql_query($sql);
				require_once(INCLUDE_PATH."includes/core/modules2cache.php");
				Modules2Cache();			
			}
			Header("Location: ".$admin_file.".php?op=AdminsList");
		}
		else {
			include ("ad-header.php");
			echo "<center><b>"._AREYOUSURETOADDADMIN."</b><BR><BR>";
			$auth_modules=base64_encode(serialize($auth_modules));
			echo "	<form action=\"".$admin_file.".php\" method=\"post\">
					<input type=\"hidden\" name=\"op\" value=\"AdminsAdd\">
					<input type=\"hidden\" name=\"add_aid\" value=\"".$add_aid."\">
					<input type=\"hidden\" name=\"add_name\" value=\"".$add_name."\">
					<input type=\"hidden\" name=\"add_email\" value=\"".$add_email."\">
					<input type=\"hidden\" name=\"add_pwd\" value=\"".$add_pwd."\">
					<input type=\"hidden\" name=\"add_url\" value=\"".$add_url."\">
					<input type=\"hidden\" name=\"add_radminsuper\" value=\"".$add_radminsuper."\">
					<input type=\"hidden\" name=\"add_admlanguage\" value=\"".$add_admlanguage."\">
					<input type=\"hidden\" name=\"add_editor\" value=\"".$add_editor."\">
					<input type=\"hidden\" name=\"auth_modules\" value=\"".$auth_modules."\">
					<input type=\"hidden\" name=\"ok\" value=\"1\">
					<input type=\"submit\" value=\""._ADD."\"> | <a href=\"".$admin_file.".php?op=AdminsList\">"._NO."</a> 
				</form>";
			echo "</center>";
			admin_footer();
		}
	}


	function AdminsEdit() { // доработать
		global $admin, $prefix, $db, $admin_file, $modules_info;
		$chng_aid=$_GET['chng_aid'];
		include ("ad-header.php");
		echo "<center><font class=\"option\"><b>"._MODIFYINFO."</b></font></center><br><br>";
		$adm_aid = filter($chng_aid, "nohtml");
		$adm_aid = trim(substr($adm_aid,0,25));
		$row = $db->sql_fetchrow($db->sql_query("SELECT aid, name, url, email, pwd, radminsuper, admlanguage, editor FROM ".$prefix."_authors WHERE aid='".$adm_aid."'"));
		$chng_aid = filter($row['aid'], "nohtml");
		$chng_name = filter($row['name'], "nohtml");
		$chng_email = filter($row['email'], "nohtml");
		$chng_pwd = filter($row['pwd'], "nohtml");
		$chng_radminsuper = intval($row['radminsuper']);
		$chng_admlanguage = addslashes($row['admlanguage']);
		$chng_editor=filter($row['editor']);
		$chng_aid = strtolower(substr($chng_aid, 0,25));
		$aid = $chng_aid;
		echo "<form action=\"".$admin_file.".php\" method=\"post\">
			<table border=\"0\">
				<tr>
					<td>"._NAME.":</td>
					<td colspan=\"3\"><b>".$chng_name."</b><input type=\"hidden\" name=\"chng_name\" value=\"".$chng_name."\"></td>
				</tr>
				<tr>
					<td>"._NICKNAME.":</td>
					<td colspan=\"3\"><input type=\"text\" name=\"chng_aid\" value=\"".$chng_aid."\" size=\"30\" maxlength=\"25\"> <font class=\"tiny\">"._REQUIRED."</font></td>
				</tr>
				<tr>
					<td>"._EMAIL.":</td>
					<td colspan=\"3\"><input type=\"text\" name=\"chng_email\" value=\"".$chng_email."\" size=\"30\" maxlength=\"60\"> <font class=\"tiny\">"._REQUIRED."</font></td>
				</tr>
		";

		$s_edit1=$s_edit2=$s_edit3=$s_edit0="";
		$s_edit0="selected";

		echo "<input type=\"hidden\" name=\"chng_admlanguage\" value=\"\">";
		if ($row['name'] != "God") {
			echo "	<tr>
					<td>Права:</td>";

			if ($chng_radminsuper == 1) {
				$sel1 = "checked";
			}
			echo "	</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type=\"checkbox\" name=\"chng_radminsuper\" value=\"1\" ".$sel1."> <b>СуперПользователь</b></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan=\"3\"><font class=\"tiny\"><i>Предупреждение</i></font></td>
				</tr>
				
			";
		}
		echo "		<tr>
					<td>Пароль:</td>
					<td colspan=\"3\"><input type=\"password\" name=\"chng_pwd\" size=\"12\" maxlength=\"40\"></td>
				</tr>
				<tr>
					<td>Пароль еще раз:</td>
					<td colspan=\"3\"><input type=\"password\" name=\"chng_pwd2\" size=\"12\" maxlength=\"40\"> <font class=\"tiny\">(только при изменении)</font></td>
				</tr>
				<tr>
					<td colspan=\"2\">
						<input type=\"hidden\" name=\"adm_aid\" value=\"".$adm_aid."\">
						<input type=\"hidden\" name=\"op\" value=\"AdminsEditSave\">
						<input type=\"submit\" value=\"Сохранить\"> Назад
					</td>
				</tr>
			</table>
			</form>
		";
		admin_footer();
	}

	function AdminsEditSave() { // доработать
		global $admin, $prefix, $db, $admin_file, $modules_info;
		$chng_aid=filter($_POST['chng_aid'],"nohtml");
		$chng_name=filter($_POST['chng_name'],"nohtml");
		$chng_email=filter($_POST['chng_email'],"nohtml");
		$chng_url='';
		$chng_radminsuper=intval($_POST['chng_radminsuper']);
		$chng_pwd=filter($_POST['chng_pwd'],"nohtml");
		$chng_pwd2=filter($_POST['chng_pwd2'],"nohtml");
		$chng_admlanguage=filter($_POST['chng_admlanguage'],"nohtml");
		$chng_editor=filter($_POST['chng_editor'],"nohtml");
		$adm_aid=filter($_POST['adm_aid'],"nohtml");
		$auth_modules=$_POST['auth_modules'];
		$chng_aid = trim($chng_aid);
		if (!($chng_aid AND $chng_name AND $chng_email AND $chng_editor)) {
			Header("Location: ".$admin_file.".php?op=AdminsList");
		}
		$is_god=0;
		if ($chng_name=='God') {
			$chng_radminsuper=1;
			$is_god=1;
		}
		$chng_name = substr($chng_name, 0, 25);
		$sql="UPDATE ".$prefix."_modules SET admins=REPLACE(admins,'".$chng_name.",','') WHERE admins LIKE '%".$chng_name.",%'";
		$db->sql_query($sql);
		$sql="UPDATE ".$prefix."_authors SET aid='".$chng_aid."', email='".$chng_email."', url='".$chng_url."', radminsuper='".$chng_radminsuper."', admlanguage='".$chng_admlanguage."', editor='".$chng_editor."' WHERE name='".$chng_name."'";
		$db->sql_query($sql);
		if($chng_radminsuper==0) {
			$tmp=array();
			foreach ($modules_info as $k=>$v) {
				for ($i=0; $i < sizeof($auth_modules); $i++) {
					if (intval($auth_modules[$i])==intval($v['mid'])) {
						$tmp[]=intval($v['mid']);
					}
				}
			}
			$tmp=implode(",",$tmp);
			$db->sql_query("UPDATE ".$prefix."_modules SET admins=CONCAT(admins,'".$chng_name."',',') WHERE mid IN (".$tmp.")");
		}
		require_once(INCLUDE_PATH."includes/core/modules2cache.php");
		Modules2Cache();
		if (!empty($chng_pwd2)) {
			if($chng_pwd != $chng_pwd2) {
				include ("ad-header.php");
				echo _PASSWDNOMATCH . "<br><br>"
				."<center>" . _GOBACK . "</center>";
				admin_footer();
				exit;
			}
			$chng_pwd = md5($chng_pwd);
			$sql="UPDATE ".$prefix."_authors SET pwd='".$chng_pwd."' WHERE name='".$chng_name."'";
			$db->sql_query($sql);
		}
		Header("Location: ".$admin_file.".php?op=AdminsList");
	}

	function AdminsDelete() { // доработать
		global $db, $prefix, $admin_file, $modules_info;
		$ok=intval($_POST['ok']);
		if ($ok==1) {
			$aid = trim(filter($_POST['aid'],"nohtml"));
			$crow=$db->sql_fetchrow($db->sql_query("SELECT name from ".$prefix."_authors WHERE aid='".$aid."' LIMIT 1"));
			$name = substr($crow['name'], 0, 25);
			if ($name!='God') {
				$db->sql_query("UPDATE ".$prefix."_modules SET admins=REPLACE(admins,'".$name.",','') WHERE admins LIKE '%".$name.",%'");
				$db->sql_query("DELETE FROM ".$prefix."_authors WHERE aid='".$aid."'");
				require_once(INCLUDE_PATH."includes/core/modules2cache.php");
				Modules2Cache();
			}
			Header("Location: ".$admin_file.".php?op=AdminsList");
		}
		else {
			$aid = trim(filter($_GET['aid'],"nohtml"));
			include ("ad-header.php");
			echo "<center><font class=\"option\"><b>"._AUTHORDEL."</b></font><br><br>"
			._AUTHORDELSURE." <i>".$aid."</i>?<br><br>";
			echo "	<form action=\"".$admin_file.".php\" method=\"post\">
					<input type=\"hidden\" name=\"aid\" value=\"".$aid."\">
					<input type=\"hidden\" name=\"op\" value=\"AdminsDelete\">
					<input type=\"hidden\" name=\"ok\" value=\"1\">
					<input type=\"submit\" value=\""._DELETE."\"> | <a href=\"".$admin_file.".php?op=AdminsList\">"._NO."</a> 
				</form>";
			admin_footer();
		}
	}
/////////////////////////////////////////////////
	function subscribe() {
		global $admin, $prefix, $db;
		include ("ad-header.php");
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
		<div class='block radius'><h2>Адреса Email из комментариев: всего ".count($mails2).", разбито по 25 штук.</h2><a class='nothing punkt dark_pole' onclick=\"show_animate('show_about');\"><img class='icon2 i26' src='/images/1.gif' align=bottom>Нажмите для прочтения</a><div id='show_about' style='display:none;'>";
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
		case "subscribe":
		subscribe();
		break;

		case "Configure":
		if (!isset($save)) $save = "";
		Configure($save);
		break;

		case "ConfigSave":
		global $prefix, $db, $options;
		$mini_blocks = $options['company_name']."|||||".$options['company_fullname']."|||||".$options['company_address']."|||||".$options['company_time']."|||||".$options['company_tel']."|||||".$options['company_sot']."|||||".$options['company_fax']."|||||".$options['company_email']."|||||".$options['company_map']."|||||".$options['company_people'];

		$advanced = $options['jqueryui']."|".$options['show_comments']."|".$options['show_userposts']."|".$options['show_page']."|".$options['show_reserv']."|".$options['uskorenie_blokov']."|".$options['kickstart']."|".$options['show_page_links']."|".$options['ad_fon']."|".$options['search_design']."|".$options['tag_design']."|".$options['add_fonts']."|".$options['normalize']."|".$options['project_logotip']."|".$options['project_name'];
		// sitename	startdate	adminmail	keywords	description	counter	statlink	postlink	registr	pogoda	flash	sgatie	stopcopy	nocashe	adminmes	red	comment	captcha_ok	ht_backup
		$db->sql_query("UPDATE `".$prefix."_config` SET `sitename` = '".$options['sitename']."',`startdate` = '".$options['startdate']."',`adminmail` = '".$options['adminmail']."',`keywords` = '".$options['keywords']."',`description` = '".$options['description']."',`counter` = '".addslashes($options['counter'])."',`statlink` = '".$options['statlink']."',`postlink` = '".$options['postlink']."',`registr` = '".$options['registr']."',`pogoda` = '".$options['pogoda']."',`flash` = '".$options['flash']."',`sgatie` = '".$mini_blocks."',`stopcopy` = '".$options['stopcopy']."', `nocashe` = '".$advanced."', `comment` = '".$options['comment_send']."', `captcha_ok` = '".$options['captcha_ok']."', `ht_backup` = '".$options['ht_backup']."' LIMIT 1 ;") or die ('Настройки не сохранилось. Видимо забыли обновить базу данных или файл настройки администрирования.');
		Header("Location: sys.php?op=Configure&save=1");
		break;
		///////////////////////////////
		//case "modifyadmin":
		//if (!isset($chng_aid)) $chng_aid = "";
		//modifyadmin($chng_aid);
		//break;

		case "UpdateAuthor":
		if ($_POST['op'] != 'UpdateAuthor') exit;
		updateadmin($chng_aid, $chng_name, $chng_pwd, $chng_pwd2, $adm_aid);
		break;
		
		case "save_banned":
		ipban_save(0, $ip1, $ip5, $ip3, $ip4, $reason);
		break;

		case "ipban_delete":
		ipban_delete($id);
		break;

		case "ipban_edit":
		ipban_edit($id);
		break;

		case "ipban_save":
		ipban_save($id, $ip1, $ip5, $ip3, $ip4, $reason);
		break;
		/////////////////////////////
		case "AdminsList":
			AdminsList();
			break;
		case "AdminsEdit":
			AdminsEdit();
			break;
		case "AdminsEditSave":
			AdminsEditSave();
			break;
		case "AdminsAdd":
			AdminsAdd();
			break;
		case "AdminsDelete":
			AdminsDelete();
			break;
	}
} else die('Доступ закрыт!<br>Возможно, вы только что сменили имя и/или пароль администратора — тогда перейдите ко <a href="/sys.php?op=login">входу в администрирование</a>.');
?>