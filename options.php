<?php
if (!defined('ADMIN_FILE')) {
	die ("Доступ закрыт!");
}
global $prefix, $db, $admin_file;
$aid = substr("$aid", 0,25);
$row = $db->sql_fetchrow($db->sql_query("SELECT realadmin FROM " . $prefix . "_authors WHERE aid='$aid'"));
if ($row['realadmin'] == 1) {

	function displayadmins() {
		global $siteurl, $admin, $prefix, $db, $admin_file, $bgcolor2;
		if (is_admin($admin)) {
			$result = $db->sql_query("SELECT aid, name from " . $prefix . "_authors where name='BOG'");
			$row = $db->sql_fetchrow($result);
			$a_aid = filter($row['aid'], "nohtml");
			$a_aid = strtolower(substr("$a_aid", 0,25));
			modifyadmin($a_aid);
		} else {
			die('Доступ закрыт!');
		}
	}

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
		admin_footer(); //include("ad-footer.php");
	}

	function ipban_save($id=0, $ip1, $ip5, $ip3, $ip4, $reason) {
		global $prefix, $db, $admin_file;
		include ("ad-header.php");
		$id = intval($id);
		//if (substr($ip2, 0, 2) == 00) { $ip2 = ereg_replace("00", "", $ip2); }
		//if (substr($ip3, 0, 2) == 00) { $ip3 = ereg_replace("00", "", $ip3); }
		//if (substr($ip4, 0, 2) == 00) { $ip4 = ereg_replace("00", "", $ip4); }
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
		//echo "<br><br>IP-адрес <b>$ip</b> запрещен. <a href=/sys.php?op=Configure>Вернуться к Настройкам администратора</a>";
	}

	///////////////////////////////////////////////////////////////////////////////////////
	function modifyadmin($chng_aid) {
	global $siteurl, $admin, $prefix, $db, $admin_file;
		//include("ad-header.php");
		echo "<a class='nothing punkt dark_pole' onclick=\"show_animate('show_options3');\"><img class='icon2 i38' src='/images/1.gif' align=bottom>Настройки администратора</a><div id='show_options3' style='display:none;'>";

		if (!isset($ip)) $ip="";
		main_ban($ip);

		echo "<br><br><h2>Смена пароля администратора</h2>";
		$adm_aid = filter($chng_aid, "nohtml");
		$adm_aid = trim($adm_aid);
		$row = $db->sql_fetchrow($db->sql_query("SELECT aid, name, pwd from " . $prefix . "_authors where name='BOG'"));
		$chng_aid = filter($row['aid'], "nohtml");
		$chng_name = filter($row['name'], "nohtml");
		$chng_pwd = filter($row['pwd'], "nohtml");
		$chng_aid = strtolower(substr("$chng_aid", 0,25));
		$aid = $chng_aid;
		echo "<form action=".$admin_file.".php method=post>"
		."<table border=0><tr><td><input type=hidden name=chng_name value='".$chng_name."'></td></tr>"
		."<tr><td align=right>Имя:</td>"
		."<td colspan=3><input type=text name=chng_aid value='".$chng_aid."' size=12 maxlength=25> [текущий логин: $chng_aid]</td></tr>
		<tr><td align=right>Пароль:</td>"
	    ."<td colspan=3><input type=password name=chng_pwd size=12 maxlength=40></td></tr>"
		."<tr><td align=right>Еще раз:</td>"
		."<td colspan=3><input type=password name=chng_pwd2 size=12 maxlength=40></td></tr>"
		."<input type=hidden name=adm_aid value='".$adm_aid."'>"
		."<input type=hidden name=op value='UpdateAuthor'>"
		."<tr><td></td><td><input type=submit value=' Сохранить '>"
		."</td></tr></table></form>

		<br><br><a href='sys.php?op=AdminsList'>Список администраторов</a> (в разработке)
		</div>"; // Список администраторов
/*
 echo "<a style='cursor:pointer;' onclick=\"show('prazd');\"><u>Праздники и именины</u></a> <span class=small>(сегодня и завтра)</span><div id='prazd' style='display:none;'><divlock><script>var calendru_c='cal_prazdnik';var calendru_mc='cal_date';var calendru_dc='cal_day';var calendru_c_all='cal_links';var calendru_n_l=0;var calendru_n_s=0;var calendru_n_d=1;var calendru_i_f=1;var calendru_show_names=1;var calendru_t_names='cal_imenin';var calendru_c_names='cal_name';</script><script src=http://www.calend.ru/img/export/informer_today_and_tommorow.js?></script></div></div><br><br>";
*/
 echo "<br><br><a class='nothing punkt dark_pole' onclick=\"show_animate('show_options2'); trash_pics();\"><img class='icon2 i6' src='/images/1.gif' align=bottom>Удаление неиспользуемых фотографий</a><div id='show_options2' style='display:none;'>Загружаю...</div>

 <br><br><a class='nothing dark_pole' href='sys.php?op=subscribe'><img class='icon2 i4' src='/images/1.gif' valign=bottom>Рассылка (список адресатов и их email'ов)</a>

 <br><br><a class='nothing dark_pole' href='images/shema.jpg' target='_blank'><img class='icon2 i18' src='/images/1.gif' valign=bottom>Схема внутреннего устройства CMS «ДвижОк»</a>
</div>
</body>
</html>";

	}

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
		global $prefix, $db, $admin_file, $siteurl;
		include ("ad-header.php");
		$ok=intval($ok);
		$row = $db->sql_fetchrow($db->sql_query("SELECT * from ".$prefix."_config"));
		$xsitename = filter($row['sitename'], "nohtml");
		$xstartdate = filter($row['startdate'], "nohtml");
		$xadminmail = filter($row['adminmail'], "nohtml");
		$xkeywords = filter($row['keywords'], "nohtml");
		$count_xkeywords = strlen($xkeywords);
		$xdescription = filter($row['description'], "nohtml");
		$count_xdescription = strlen($xdescription);
		$xcounter = trim(stripslashes($row['counter']));
		$count_xcounter = strlen($xcounter);
		$xstatlink = filter($row['statlink'], "nohtml");
		$xpostlink = filter($row['postlink'], "nohtml");
		$sel1=$sel2=$sel2_2=$sel2_3=$sel3=$sel4=$sel5=$sel6=$sel7=$sel8=$sel9=$sel8_2=$sel9_2=$sel10=$sel10_2=$sel11=$sel12=$sel13="";

		// Переход к одному полю со всеми настройками
		$show_comments = $show_userposts = $xpage = $xreserv = 1; // показывать на главной админки новые комменты и страницы, добавленные посетителями
		$xnocashe='';
		$ca = ""; // кеш страниц
		list($xnocashe, $show_comments, $show_userposts, $xpage, $xreserv, $uskorenie_blokov) = explode("|",$row['nocashe']);
		if ($show_comments == "") $show_comments = 1;
		if ($show_userposts == "") $show_userposts = 1;
		if ($xpage == "") $xpage = 1;
		if ($xreserv == "") $xreserv = 1;
		if ($uskorenie_blokov == "") $uskorenie_blokov = 0;

		$xregistr = intval($row['registr']); 	if ($xregistr==1) $sel1=" selected";
		$xpogoda = intval($row['pogoda']); 		
		if ($xpogoda==1) $sel2=" selected";
		if ($xpogoda==2) $sel2_2=" selected";
		if ($xpogoda==3) $sel2_3=" selected";
		$xflash = intval($row['flash']); 		if ($xflash==1) $sel3=" selected";
		$xsgatie = intval($row['sgatie']); 		if ($xsgatie==1) $sel4=" selected";
		$xstopcopy = intval($row['stopcopy']); 	if ($xstopcopy==1) $sel5=" selected";
		$xcomment = $row['comment'];			if ($xcomment==1) $sel6=" selected";
		$xcaptcha_ok = intval($row['captcha_ok']); 	if ($xcaptcha_ok==1) $sel7=" selected";
		if ($show_comments==1) $sel8=" selected";
		if ($show_comments==2) $sel8_2=" selected";
		if ($show_userposts==1) $sel9=" selected";
		if ($show_userposts==2) $sel9_2=" selected";
		if ($xnocashe=='*') $sel10=" selected";
		if ($xnocashe=='') $sel10_2=" selected";
		if ($xpage == 1) $sel12 = " selected";
		if ($xreserv == 1) $sel11 = " selected";
		if ($uskorenie_blokov == 1) $sel13 = " selected";

echo "<form action='".$admin_file.".php' method='post' name=\"form\">
<h1>Настройки</h1>";

if ($ok==1) echo "<span class=green>успешно сохранены</span><br>";

echo "<a class='nothing punkt dark_pole' onclick=\"show_animate('show_options');\"><img class='icon2 i38' src='/images/1.gif' align=bottom>Общие настройки сайта</a><div id='show_options' style='display:none;'>";

echo "<table class=table_light><tr valign=top><td>
Название сайта:</td><td class=small><textarea name=xsitename rows=2 cols=80 style='width: 100%;'>".$xsitename."</textarea><br>
Выводится в заголовке окна (Title)
</td></tr><tr valign=top><td>
Описание сайта:</td><td class=small>
<textarea onkeypress=\"count1()\" onkeyup=\"count1()\" name=xdescription rows=3 cols=80 style='width: 100%;'>".$xdescription."</textarea><br>
Набрано <input name=\"result1\" value=\"".$count_xdescription."\" size=3> из 500 допустимых символов. Для поисковых систем (Description)
</td></tr><tr valign=top><td>
Ключевые словосочетания:</td><td class=small>
<textarea onkeypress=\"count2()\" onkeyup=\"count2()\" name=xkeywords rows=3 cols=80 style='width: 100%;'>".$xkeywords."</textarea><br>
Набрано <input name=\"result2\" value=\"".$count_xkeywords."\" size=3> из 500 допустимых символов. 

<script>
function isSpace(s){return (s==\" \" || s==\"n\" || s==\"13\" || s==\"t\" || s==\"r\")?true:false;}
function count1(){ var c=0; var i; var prevspace=true; var str=document.forms.form.xdescription.value; for(i=str.length-1;i>=0;i--){if(isSpace(str.charAt(i)) && prevspace){}else{c++;} prevspace=isSpace(str.charAt(i)); } document.forms.form.result1.value=c; return false; }
function count2(){ var c=0; var i; var prevspace=true; var str=document.forms.form.xkeywords.value; for(i=str.length-1;i>=0;i--){if(isSpace(str.charAt(i)) && prevspace){}else{c++;} prevspace=isSpace(str.charAt(i)); } document.forms.form.result2.value=c; return false; }
function count3(){ var c=0; var i; var prevspace=true; var str=document.forms.form.xcounter.value; for(i=str.length-1;i>=0;i--){if(isSpace(str.charAt(i)) && prevspace){}else{c++;} prevspace=isSpace(str.charAt(i)); } document.forms.form.result3.value=c; return false; }
</script>

Через запятую. Для поисковых систем (Keywords)
</td></tr><tr valign=top class=p2><td>
Ссылка на почтовый сайт:</td><td class=small><input type='text' name='xpostlink' value='".$xpostlink."' size='60' maxlength='255'>
</td></tr><tr valign=top class=p2><td>
Ссылка на статистику:</td><td class=small><input type='text' name='xstatlink' value='".$xstatlink."' size='60' maxlength='255'><br>
Если использовать <a href=http://www.liveinternet.ru/add>liveinternet.ru</a> ссылка будет такой: http://pda.liveinternet.ru/stat/".$siteurl."/. Отображается сверху в Администрировании - Статистика.
</td></tr><tr valign=top class=p2><td>
Код счетчика статистики:</td><td class=small>
<textarea onkeypress=\"count2()\" onkeyup=\"count2()\" name=xcounter rows=5 cols=70 style='width: 100%;'>".$xcounter."</textarea><br>
Набрано <input name=\"result2\" value=\"".$count_xcounter."\" size=5> из 5000 допустимых символов.<br>
Желательно использовать <a href=http://www.liveinternet.ru/add>liveinternet.ru</a> для получения счетчика (выбрать при получении: «код счетчика в одну строчку» и «учитывать заголовки»). Счетчик можно поставить в дизайн как автоматический блок, написав [статистика]. Если вы хотите скрыть счетчик от посетителей - используйте счетчик без цифр (и с закрытой от просмотра статистикой) и/или поставьте счетчик в скрытый слой DIV (тогда ссылка на статистику будет доступна только из Администрирования — ссылка сверху).
</td></tr>

<tr valign=top class=hide><td>
Регистрация:</td><td class=small><select name=xregistr><option value=0>НЕТ</option><option value=1".$sel1.">ЕСТЬ</option></select> Вкл./Откл. Регистрацию и Вход пользователей на сайт.</td></tr>

<tr valign=top class=p3><td>
Анимация:</td><td class=small><select name=xpogoda>
<option value=0>Отключена</option>
<option value=1".$sel2.">* Снег</option>
<option value=2".$sel2_2.">@ Листья</option>
<option value=3".$sel2_3.">• Шарики</option>
</select> Фоновая анимация поверх содержания сайта (только на Главной странице).</td></tr>

<tr valign=top class=p3><td>
Полная поддержка Flash:</td><td class=small><select name=xflash><option value=0>Откл.</option><option value=1".$sel3.">Вкл.</option></select> Включать ТОЛЬКО при проблемах отображения SWF-файлов, FLA-видео и Flash-анимации на сайте.
</td></tr>

<tr valign=top class=hide><td>
Денежный счёт пользователей:</td><td class=small><select name=xsgatie><option value=0>НЕТ</option><option value=1".$sel4.">ЕСТЬ</option></select> Вкл./Откл. внутреннего счёта для пользователей и возможности назначения администратором Даты окончания оплаченного периода для пользования платными функциями сайта. Используется для полузакрытой и закрытой Базы данных.</td></tr>

<tr valign=top class=p3><td>
Простая защита от копирования: </td><td class=small><select name=xstopcopy><option value=0>Откл.</option><option value=1".$sel5.">Вкл.</option></select> Препятствует массовому копированию статей на чужие сайты глупыми злоумышленниками. Невозможность выделить/скопировать текст и нажать правую кнопку мыши. </td></tr>

<tr valign=top class=p3><td>
Защита комментариев: </td><td class=small><select name=xcaptcha_ok><option value=0>Вкл.</option><option value=1".$sel7.">Откл.</option></select> По умолчанию - вкл. При отключении можно не вводить проверочный код или вводить его неправильно — он не будет проверяться. </td></tr>

<tr valign=top class=p4><td>
Год основания сайта:</td><td class=small><input type='text' name='xstartdate' value='".$xstartdate."' size='4' maxlength='4'><br>
Используется в автоматическом блоке [год], год основания - текущий год, для отображения внизу сайта, в строке копирайта, например 2007-2009.
</td></tr>

<tr valign=top class=p4><td>
Почта администратора:</td><td class=small><input type='text' name='xadminmail' value='".$xadminmail."' size='40' maxlength='255'><br>
Используется в автоматическом блоке [почта], для отправки почты администратору сайта.
</td></tr>

<tr valign=top class=p4><td>
Письма о комментариях:</td><td class=small><select name=xcomment><option value=0>НЕ получать</option><option value=1".$sel6.">получать</option></select><br>
Отправка уведомлений о новых комментариях на почту администратору.
</td></tr>

<tr valign=top><td>
Страницы, добавленные посетителями в администрировании:</td><td class=small><select name=xshow_userposts><option value=0>НЕ показывать</option><option value=1".$sel9.">показывать</option><option value=2".$sel9_2.">показывать точку (скрытый вариант)</option></select>
</td></tr>

<tr valign=top><td>
Комментарии в администрировании:</td><td class=small><select name=xshow_comments><option value='0'>НЕ показывать</option><option value='1'".$sel8.">показывать</option><option value=2".$sel8_2.">показывать точку (скрытый вариант)</option></select>
</td></tr>

</table>
<div style='text-align:center;'><input type='submit' value=' Сохранить настройки ' style='width:300px; height:40px;'></div>
<input type='hidden' name='op' value='ConfigSave'>

</div>

<br>

<a class='nothing punkt dark_pole' onclick=\"show_animate('show_options_speed');\"><img class='icon2 i38' src='/images/1.gif' align=bottom>Ускорение сайта</a><div id='show_options_speed' style='display:none;'>

<table class=table_light>
<tr valign=top><td style='min-width:250px;'>
Кеширование страниц:</td><td class=small><select name=xnocashe><option value='!'>очистить кеш</option><option value='*'".$sel10.">ОТКЛЮЧЕНО</option><option value=''".$sel10_2.">ВКЛЮЧЕНО</option></select><br>
Кеширование – это временное сохранение собранной страницы для последующего более быстрого её открытия. Включать кеширование нужно для сайтов с высокой нагрузкой, а также в случае медленного открытия страниц сайта.
</td></tr>
<tr valign=top><td>
Создание резервной копии:</td><td class=small><select name=xreserv><option value='0'>ОТКЛЮЧЕНО</option><option value='1'".$sel11.">ВКЛЮЧЕНО</option></select><br>
Каждый день, при посещении администратором главной страницы администрирования, создается резервная копия всего содержания сайта, кроме файлов (документы, архивы, фотографии), закачанных на сервер. Если это большой портал на скромном хостинге, создание копии можно отключить для экономии файлового места.
</td></tr>
<tr valign=top><td>
Отображение количества страниц в разделах (на вкладке Содержание):</td><td class=small><select name=xpage><option value='0'>ОТКЛЮЧЕНО</option><option value='1'".$sel12.">ВКЛЮЧЕНО</option></select><br>
Отключать его имеет смысл, если создано очень много страниц (более 20 тысяч) и хочется ускорить загрузку вкладки Содержание на 1-2 секунды.
</td></tr>
<tr valign=top><td>
Ускорение вывода блоков (на вкладке Оформление):</td><td class=small><select name=xuskorenie_blokov><option value='0'>ОТКЛЮЧЕНО</option><option value='1'".$sel13.">ВКЛЮЧЕНО</option></select><br>
Отключать его имеет смысл, если создано много блоков и  страница с ними долго загружается. Ускорение убирает информацию об использовании блоков. 
</td></tr>
</table>
<div style='text-align:center;'><input type='submit' value=' Сохранить настройки ' style='width:300px; height:40px;'></div>
<input type='hidden' name='op' value='ConfigSave'>
</div>
</form>";
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

	function AdminsAdd() {
		global $db, $prefix, $admin_file, $modules_info;
		$ok=intval($_POST['ok']);
		$add_aid=$_POST['add_aid'];
		$add_name=$_POST['add_name'];
		$add_email=$_POST['add_email'];
//		$add_url=$_POST['add_url'];
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


	function AdminsEdit() {
		global $admin, $prefix, $db, $admin_file, $modules_info;
		$chng_aid=$_GET['chng_aid'];
		include ("ad-header.php");
		echo "<center><font class=\"option\"><b>"._MODIFYINFO."</b></font></center><br><br>";
		$adm_aid = filter($chng_aid, "nohtml");
		$adm_aid = trim(substr($adm_aid,0,25));
		$row = $db->sql_fetchrow($db->sql_query("SELECT aid, name, url, email, pwd, radminsuper, admlanguage, editor FROM ".$prefix."_authors WHERE aid='".$adm_aid."'"));
		$chng_aid = filter($row['aid'], "nohtml");
		$chng_name = filter($row['name'], "nohtml");
//		$chng_url = filter($row['url'], "nohtml");
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
/*
		echo "		<tr>
					<td>"._URL.":</td>
					<td colspan=\"3\"><input type=\"text\" name=\"chng_url\" value=\"".$chng_url."\" size=\"30\" maxlength=\"60\"></td>
				</tr>
		";
*/
		$s_edit1=$s_edit2=$s_edit3=$s_edit0="";
		if ($chng_editor=="Tiny_MCE") {
			$s_edit1="selected";
		}
		elseif ($chng_editor=="CKEditor") {
			$s_edit2="selected";
		}
		elseif ($chng_editor=="FCKEditor") {
			$s_edit3="selected";
		}
		elseif ($chng_editor=="Spaw2") {
			$s_edit4="selected";
		}
		else {
			$s_edit0="selected";
		}
		echo "		<tr>
					<td>"._DEFAULTEDITOR.":</td>
					<td colspan=\"3\">
						<select name=\"chng_editor\">
							<option name=\"add_editor\" value=\"0\" ".$s_edit0.">"._ADMIN_WITHOUTEDITOR."</option>
							<option name=\"chng_editor\" value=\"Tiny_MCE\" ".$s_edit1.">Tiny MCE</option>
							<option name=\"chng_editor\" value=\"CKEditor\" ".$s_edit2.">CKEditor</option>
							<option name=\"chng_editor\" value=\"FCKEditor\" ".$s_edit3.">FCKEditor</option>
							<option name=\"chng_editor\" value=\"Spaw2\" ".$s_edit4.">Spaw2</option>
						</select>
					</td>
				</tr>
		";
		if ($multilingual == 1) {
			echo "	<tr>
					<td>"._LANGUAGE.":</td><td colspan=\"3\">
						<select name=\"chng_admlanguage\">";
			$handle=opendir('language');
			while ($file = readdir($handle)) {
				if (preg_match("/^lang\-(.+)\.php/", $file, $matches)) {
					$langFound = $matches[1];
					$languageslist .= $langFound." ";
				}
			}
			closedir($handle);
			$languageslist = explode(" ", $languageslist);
			sort($languageslist);
			for ($i=0; $i < sizeof($languageslist); $i++) {
				if(!empty($languageslist[$i])) {
					echo "		<option value=\"".$languageslist[$i]."\" ";
					if($languageslist[$i]==$chng_admlanguage) {
						echo "selected";
					}
					echo ">".ucfirst($languageslist[$i])."</option>\n";
				}
			}
			if (empty($chng_admlanguage)) {
				$allsel = "selected";
			} 
			else {
				$allsel = "";
			}
			echo "				<option value=\"\" ".$allsel.">"._ALL."</option>
						</select>
					</td>
				</tr>
			";
		}
		else {
			echo "		<input type=\"hidden\" name=\"chng_admlanguage\" value=\"\">";
		}
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

	function AdminsEditSave() {
		global $admin, $prefix, $db, $admin_file, $modules_info;
		$chng_aid=filter($_POST['chng_aid'],"nohtml");
		$chng_name=filter($_POST['chng_name'],"nohtml");
		$chng_email=filter($_POST['chng_email'],"nohtml");
//		$chng_url=$_POST['chng_url'];
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

	function AdminsDelete() {
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
		echo "<h1>Рассылка</h1><h2>Адреса Email из комментариев: всего ".count($mails2).", разбито по 25 штук.</h2><a class='nothing punkt dark_pole' onclick=\"show_animate('show_about');\"><img class='icon2 i26' src='/images/1.gif' align=bottom>Нажмите для прочтения</a><div id='show_about' style='display:none;'>";
		if ($nu2 > 0) echo "<p><b>Подписавшиеся на рассылку выделены жирным, всего: ".$nu2.".</b></p>";
		echo "<p>Можно вставлять для отправки сразу после исправления имен, если они набраны неправильно.</p>
		<p><b>Внимание!</b> Рассылку лучше всего делать со специально зарегистрированного для этого email адреса. Не желательно писать в письме рассылки адрес сайта со ссылкой только на одну страницу. Ни в коем случае не делать рекламные рассылки! Это может быть расценено как спам, а сайт могут просто закрыть!</p><p><b>Разрешается делать:</b>
		<li>Обзорные рассылки — много ссылок на разные материалы
		<li>Извещение о начале какого-то конкурса или массового события
		<li>Поздравительные праздничные рассылки
		<hr><br></div><br><br>".$echo."
		</div>
		</body>
		</html>";
		
	}
/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
	switch($op) {
		case "subscribe":
		subscribe();
		break;

		case "Configure":
		if (!isset($save)) $save = "";
		Configure($save);
		displayadmins();
		break;

		case "ConfigSave":
		global $prefix, $db;
		$xcounter = addslashes($xcounter);
		$xnocashe = trim(str_replace("  "," ",$xnocashe))."|".$xshow_comments."|".$xshow_userposts."|".$xpage."|".$xreserv."|".$xuskorenie_blokov;
		$db->sql_query("UPDATE `".$prefix."_config` SET `sitename` = '$xsitename',`startdate` = '$xstartdate',`adminmail` = '$xadminmail',`keywords` = '$xkeywords',`description` = '$xdescription',`counter` = '$xcounter',`statlink` = '$xstatlink',`postlink` = '$xpostlink',`registr` = '$xregistr',`pogoda` = '$xpogoda',`flash` = '$xflash',`sgatie` = '$xsgatie',`stopcopy` = '$xstopcopy', `nocashe` = '$xnocashe', `comment` = '$xcomment', `captcha_ok` = '$xcaptcha_ok' LIMIT 1 ;") or die ('Не сохранилось...');
		Header("Location: sys.php?op=Configure&save=1");
		break;
		///////////////////////////////
		case "modifyadmin":
		if (!isset($chng_aid)) $chng_aid = "";
		modifyadmin($chng_aid);
		break;

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