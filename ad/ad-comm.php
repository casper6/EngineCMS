<?php
if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die (aa("Доступ закрыт!")); }
$aid = trim($aid);
global $prefix, $db, $red;
$row = $db->sql_fetchrow($db->sql_query("select `realadmin` from ".$prefix."_authors where `aid`='".$aid."'"));
$realadmin = $row['realadmin'];
if ($realadmin==1) {
    ##################################################################################################
    function base_comments($name=0, $cid=0, $pid=0) {
        global $prefix, $db, $bgcolor1, $bgcolor2, $spisoknum, $bgcolor3, $bgcolor4, $p;
        include("ad/ad-header.php");
        $lim = 50; // Настройка кол-ва выводимых комментариев.
        $offset = $p * $lim;
        // Определяем все разделы // --- Доработать - взять всё из mainfile !!!
        $module_title = array();
        $module_names = array();
        $sql = "SELECT name, title FROM ".$prefix."_mainpage where `tables`='pages' and type='2' and name!='index'";
        $result = $db->sql_query($sql) or die(aa("Не удалось собрать разделы... "));
        while ($rows = $db->sql_fetchrow($result)) {
            $module_name = $rows['name'];            
            if (strpos($module_name, "\n")) { // заменяем имя запароленного раздела
                $module_name = explode("\n", str_replace("\r", "", $module_name));
                $module_name = trim($module_name[0]);
            }
            $module_names[] = $module_name;
            $module_title[$module_name] = $rows['title'];
        }
        // Определяем все папки
        $papka_module = array();
        $papka_title = array();
        $sql = "SELECT cid, module, title FROM ".$prefix."_pages_categories where `tables`='pages'";
        $result = $db->sql_query($sql) or die(aa("Не удалось собрать папки... "));
        while ($rows = $db->sql_fetchrow($result)) {
            $papka_cid = $rows['cid'];
            $papka_module[$papka_cid] = $rows['module'];
            $papka_title[$papka_cid] = $rows['title'];
        }
        // Определяем все страницы
        $page_cid = array();
        $page_module = array();
        $page_title = array();
        $sql = "SELECT pid, cid, module, title FROM ".$prefix."_pages where `tables`='pages'";
        $result = $db->sql_query($sql) or die(aa("Не удалось собрать страницы... "));
        while ($rows = $db->sql_fetchrow($result)) {
            $page_pid = $rows['pid'];
            $page_cid[$page_pid] = $rows['cid'];
            $page_module[$page_pid] = $rows['module'];
            $page_title[$page_pid] = $rows['title'];
        }
        $page = "все";
        $papka = "все";
        $razdel = "все";
        // Формируем конечный запрос
        if (!in_array($name, $module_names) and $cid == 0 and $pid == 0) $sql = "SELECT p.cid pccid, pcom.cid cid, pcom.num num, pcom.avtor avtor, pcom.mail mail, pcom.text text, pcom.ip ip, pcom.data data, pcom.golos golos, pcom.adres adres, pcom.tel tel, pcom.active active FROM ".$prefix."_pages_comments pcom, ".$prefix."_pages p WHERE pcom.num=p.pid order by data desc";

        if (in_array($name, $module_names)) $sql = "SELECT p.cid pccid, pcom.cid cid, pcom.num num, pcom.avtor avtor, pcom.mail mail, pcom.text text, pcom.ip ip, pcom.data data, pcom.golos golos, pcom.adres adres, pcom.tel tel, pcom.active active FROM ".$prefix."_pages_comments pcom, ".$prefix."_pages p WHERE pcom.num=p.pid and p.module='".$name."' order by data desc";

        if (!in_array($name, $module_names) and $cid==0 and $pid==0) { } else $razdel = "<a href=/sys.php?op=base_comments&name=".$name.">".$module_title[$name]."</a> | <a href=/sys.php?op=base_comments>Открыть комментарии всех разделов</a>";

        if ($cid != 0) {
        $sql = "SELECT p.cid pccid, pcom.cid cid, pcom.num num, pcom.avtor avtor, pcom.mail mail, pcom.text text, pcom.ip ip, pcom.data data, pcom.golos golos, pcom.adres adres, pcom.tel tel, pcom.active active FROM ".$prefix."_pages_comments pcom, ".$prefix."_pages p WHERE p.cid=".$cid." and pcom.num=p.pid order by data desc";
        $papka = "<a href=/sys.php?op=base_comments&name=$name&cid=".$cid.">".$papka_title[$cid]."</a>";
        }

        if ($pid != 0) {
        $cid = $page_cid[$pid];
        $sql = "SELECT * FROM ".$prefix."_pages_comments where num='$pid' order by data desc";
        $page = "<a href=/sys.php?op=base_comments&name=$name&pid=".$pid.">".$page_title[$pid]."</a>";
        if (isset ($papka_title[$cid])) $papka = "<a href=/sys.php?op=base_comments&name=$name&cid=".$cid.">".$papka_title[$cid]."</a>"; else $papka = "";
        }

        $comm_text = array();
        $result = $db->sql_query($sql) or die("Не удалось собрать комментарии... ");
        $numrows = $db->sql_numrows($result);
        $sql .= " limit $offset,$lim";
        $result = $db->sql_query($sql);

        echo "<h1>Комментарии</h1>
        <h2>Раздел: ".$razdel."<br>
        Папка: ".$papka."<br>
        Страница: ".$page."<br>
        Всего комментариев: ".$numrows.".</h2>
        <div class=block2><b>Справка:</b> 
        <font color=red>Ссылки на страницы и папки открывают не сами страницы, а список их комментариев.</font>
        <br>Розовым цветом отображаются выключенные или непроверенные администратором комментарии.</div>
        <table width=100% border=0 cellspacing=1 cellpadding=5 bgcolor=lightblue>
        <tr bgcolor=lightblue><td><b>Дата<br><nobr>и Время</nobr></b></td><td><b>Автор</b></td><td><b>Раздел &rarr; Папка</b></td><td><b>Страница <br>и Комментарий</b></td><td width=80><b>Функции</b></td></tr>";
        $year = date("Y");
        $col = 0;
        while ($rows = $db->sql_fetchrow($result)) {
            if ($col == 0) { $col = 1; $color = "#ffffff"; } else { $col = 0; $color = "#ddffff"; }
            $comm_cid = $rows['cid'];
            if (isset($rows['pccid'])) $pc_cid = $rows['pccid']; else $pc_cid = 0;
            $comm_text = $rows['text'];
            $comm_golos = $rows['golos'];
            $comm_page = $rows['num'];
            $comm_avtor = $rows['avtor'];
            $comm_ip = $rows['ip'];
                $comm_mail = $rows['mail'];     
                if (trim($comm_mail) != "") $comm_mail = ",<br><b>Email:</b> ".$comm_mail;
                $comm_adres = $rows['adres'];   
                if (trim($comm_adres) != "") $comm_adres = ",<br><b>Адрес:</b> ".$comm_adres;
                $comm_tel = $rows['tel'];       
                if (trim($comm_tel) != "") $comm_tel = ",<br><b>Телефон:</b> ".$comm_tel;
            $act = $rows['active'];
            if ($act == 0) $color = "#ffdddd";
            $comm_data = str_replace(".","-",str_replace(" ","-",str_replace(":","-",$rows['data'])));
            $datax = explode("-",$comm_data);
            if ($year == $datax[0]) $year2 = ""; 
            else $year2 = " ".$datax[0];
            $comm_data = "<nobr>".intval($datax[2])." ".findMonthName($datax[1]).$year2."<nobr><br>в ".$datax[3].":".$datax[4];
            $comm_avtor .= $comm_mail.$comm_adres.$comm_tel;
            if ($pid != 0) $pc_cid = $cid;
            if ($comm_golos > 0) {
                if ($comm_golos == 1) $comm_golos = "плохо"; 
                if ($comm_golos == 3) $comm_golos = "средне"; 
                if ($comm_golos == 5) $comm_golos = "отлично";
                $comm_text = explode("|&|",$comm_text); $comm_text = "<b>Рейтинг: ".$comm_golos."</b>. ".$comm_text[0];
            }
            if ($comm_page == 0) $raz = 0; 
            else $raz = $page_module[$comm_page];

            if ($pc_cid == 0) $papka_title[$pc_cid] = "";
            echo "<tr valign=top bgcolor=".$color."><td class=gray><a name=".$comm_cid."></a>".$comm_data."</td><td class=gray>".$comm_avtor."<br>IP-адрес: ".$comm_ip."</td><td>
            <a href=/sys.php?op=base_comments&name=".$raz." title='Открыть комментарии этого раздела'><b>".$module_title[$raz]."</b></a> &rarr;&nbsp;<a href=/sys.php?op=base_comments&name=".$raz."&cid=".$pc_cid." title='Открыть комментарии этой папки'>".$papka_title[$pc_cid]."</a></td><td><b>Название стр.:</b> <a href=/sys.php?op=base_comments&name=".$raz."&pid=".$comm_page." title='Открыть комментарии этой страницы'>".$page_title[$comm_page]."</a> <a target=_blank href=/-".$raz."_page_".$comm_page."#comment_".$comm_cid." title='Открыть комментарий на сайте'><span class=\"icon blue small\" data-icon=\"s\"></span><b style='color: #1191bb;'>Открыть на сайте</b></a><br><b>Комментарий №".$comm_cid.":</b> ".$comm_text."</td><td><nobr><a title=\"Редактировать\" href=/sys.php?op=base_comments_edit_comments&cid=".$comm_cid."&red=1><span class=\"icon black small\" data-icon=\"7\"></span></a> <a href=/sys.php?op=base_comments_status&name=".$raz."&pid=".$comm_page."&cid=".$comm_cid." title='Отключить/Включить комментарий'><span class=\"icon red small\" data-icon=\"Q\"></span></a> &nbsp; &nbsp; <a href=/sys.php?op=base_pages_delit_comm&cid=".$comm_cid."&ok=ok&pid=".$comm_page." title=\"Удалить\"><span class=\"icon red small\" data-icon=\"F\"></span></a></nobr>
            </td></tr>";

        }
        echo "</table>";
        $url = getenv("REQUEST_URI");
        echo "<center>".comm_links($numrows,$p,$url."&p=",$lim)."</center>";
        admin_footer();
    }
    #####################################################################################################################
    function base_comments_edit_comments($cid) {
    global $prefix, $db, $red;
        $sql = "SELECT * FROM ".$prefix."_pages_comments WHERE `cid`='".$cid."'";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $comm_cid = $row['cid'];
        $comm_text = $row['text'];
        $comm_golos = $row['golos'];
        $comm_page = $row['num'];
        $comm_data = $row['data'];
        $comm_avtor = $row['avtor'];
        $comm_mail = $row['mail'];
        $comm_tel = $row['tel'];
        $comm_adres = $row['adres'];
        $comm_act = $row['active'];
        $comm_drevo = $row['drevo'];
        $comm_ip = $row['ip'];
        include("ad/ad-header.php");
        $sql = "SELECT `module`, `title` FROM ".$prefix."_pages WHERE `pid`='".$comm_page."'";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $page_module = $row['module'];
        $page_title = $row['title'];
        echo "<form method=\"POST\" action=\"sys.php\" enctype=\"multipart/form-data\">

        <div class='fon w100 mw800'>
        <div class='black_grad' style='height:45px;'>
        <button type=submit id=new_razdel_button class='medium green' onclick=\"show('sortirovka');\" style='float:left; margin:3px;'><span style='margin-right: -2px;' class='icon white small' data-icon='c'></span> Сохранить</button>
        <span class='h1' style='padding-top:10px;'>Редактирование комментария (№".$cid.")</span>";
        red_vybor();
        echo "</div>
        <table class='w100'><tr valign='top'><td bgcolor='#eeeeee' width='250' id='razdels'>";
        echo "<p><b>Относится к странице</b> № <input type=text size=4 name=num value=\"".$comm_page."\"> «<a target='_blank' href='".re_link("-".$page_module."_page_".$comm_page)."'>".$page_title."</a>»
        <p>".select("act", "0,1", "НЕТ,ДА", $comm_act)." <b>Включить</b>
        <hr><p><b>Дата:</b> <input type=text name='dataX' class='w60' value=\"".$comm_data."\">
        <p><b>Голосование/рейтинги:</b><br>".select("golos", "0,1,2,3,4,5,6,7,8,9,10", "НЕТ,1,2,3,4,5,6,7,8,9,10", $comm_golos)."
        <p><b>Ответ на другой комментарий<br>№:</b> <input type=text size=3 name=drevo value=\"".$comm_drevo."\"> 
        0 - это начальный комментарий
        <br>
        </td><td>
        <table class='w100'><tr valign='top'><td width='50%'>
        <p><b>Автор:</b> ( IP-адрес: ".$comm_ip." )<br><input type=text size=60 class='w100' name=avtor2 value=\"".$comm_avtor."\">
        <p><b>Email:</b><br><input type=text size=60 class='w100' name=mail value=\"".$comm_mail."\">
        </td><td>
        <p><b>Адрес:</b><br><input type=text size=60 class='w100' name=adres value=\"".$comm_adres."\">
        <p><b>Телефон:</b><br><input type=text size=60 class='w100' name=tel value=\"".$comm_tel."\">
        </td></tr></table>

        <p><b>Текст комментария:</b><br>".redactor($red, $comm_text, 'text');
        if ($comm_golos > 0) echo "<font color=red>|&| - это символы-разделители в тексте комментария анкет-рейтингов, делят предпросмотр анкеты и саму анкету, НЕ ТРОГАТЬ!!!.</font><br>";

        echo "<input type=hidden name=op value=base_comments_edit_sv_comments>
        <input type=hidden name=cid value=$cid>
        </td></tr></table>
        </form>";
        admin_footer(); //include("ad-footer.php");
    }
    ###########################################################
    function base_comments_edit_sv_comments($cid, $text, $golos, $num, $dataX, $avtor2, $mail, $adres, $tel, $act, $drevo) {
        global $prefix, $db;
        if ($cid == $drevo) die ('Комментарий не может быть ответом сам на себя!');
        $text = str_replace("&amp;","&",$text);
        // cid num avtor mail text ip data golos --- comments
        $db->sql_query("UPDATE ".$prefix."_pages_comments SET num='".mysql_real_escape_string($num)."', avtor='".mysql_real_escape_string($avtor2)."', mail='".mysql_real_escape_string($mail)."', text='".mysql_real_escape_string($text)."', data='".mysql_real_escape_string($dataX)."', golos='".mysql_real_escape_string($golos)."', drevo='".mysql_real_escape_string($drevo)."', adres='".mysql_real_escape_string($adres)."', tel='".mysql_real_escape_string($tel)."', active='".mysql_real_escape_string($act)."' WHERE cid='".mysql_real_escape_string($cid)."'") or die('Не могу сохранить изменения в комментарии...');
        $sql = "select module from ".$prefix."_pages where `pid` = '".mysql_real_escape_string($num)."'";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $mod = $row['module'];
        recash("page_".$num); // Обновление кеша
        Header("Location: sys.php?op=base_comments&pid=".$num."&name=".$mod);
    }
    ##########################################################
    function comm_links($records,$r_start=0,$URL,$inpage=20) { // Строчка выбора страниц < 1 2 3 >
        //$records - всего записей 
        //$r_start - текущая страница 
        //$URL - адрес, заканчивающийся на "=" 
        //$inpage - записей на страницу
        $str="";
        if ($records<=$inpage) return;
        if ($r_start!=0) {
            $str.="<a href=".$URL."0 title=\"первая страница\"><<</a> ";
            $str.="<a href=$URL".($r_start-1)." title=\"предыдущая страница\"><</a> ";
            }
        else $str.="";
        if ($r_start==0) {$sstart=$r_start-0;$send=$r_start+10;}
        if ($r_start==1) {$sstart=$r_start-1;$send=$r_start+9;}
        if ($r_start==2) {$sstart=$r_start-2;$send=$r_start+8;}
        if ($r_start==3) {$sstart=$r_start-3;$send=$r_start+7;}
        if ($r_start==4) {$sstart=$r_start-4;$send=$r_start+6;}
        if ($r_start>=5) {$sstart=$r_start-5;$send=$r_start+5;}
        if ($send*$inpage>$records) $send=$records/$inpage;
        if ($sstart<0) $sstart=0;
        if ($records%$inpage==0) $add=0; else $add=1;
        for ($i=$sstart;$i<$send;$i++) {
            if ($i==$r_start) $str.=" <B>".($i+1)."</B> | ";
            else $str.="<a href=$URL".($i)." title=\"".($i+1)." страница\"><B>".($i+1)."</B></a> |  ";
            }
        if ($r_start+(1-$add)<intval($records/$inpage)) {
            $str.=" <a href=$URL".($r_start+1)." title=\"следующая страница\">></a>";
            $str.=" <a href=$URL".(intval($records/$inpage)-(1-$add))." title=\"последняя страница\">>></a>";
            }
        else $str.="";
        return($str);
    }
    ##################################################
    function base_comments_status($name, $pid, $cid) {
        global $prefix, $db;
        $sql = "select active from ".$prefix."_pages_comments where cid='$cid'";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $act = $row['active'];
        if ($act == 0 or $act == 2) $db->sql_query("UPDATE ".$prefix."_pages_comments SET active='1' WHERE cid='$cid'") or die('Не удалось включить комментарий. Вероятно какие-то проблемы с базой данных. Администратор поможет решить проблему.');
        if ($act == 1) $db->sql_query("UPDATE ".$prefix."_pages_comments SET active='0' WHERE cid='$cid'") or die('Не удалось отключить комментарий. Вероятно какие-то проблемы с базой данных. Администратор поможет решить проблему.');
        Header("Location: sys.php?op=base_comments&name=$name&pid=$pid");
    }
    switch ($op) {
        case "base_comments":
            if (!isset($name)) $name="";
            if (!isset($cid)) $cid="";
            if (!isset($pid)) $pid="";
            base_comments($name, $cid, $pid);
            break;
        case "base_comments_edit_comments":
            base_comments_edit_comments($cid);
            break;
        case "base_comments_edit_sv_comments":
            base_comments_edit_sv_comments($cid, $text, $golos, $num, $dataX, $avtor2, $mail, $adres, $tel, $act, $drevo);
            break;
        case "base_comments_status":
            base_comments_status($name, $pid, $cid);
            break;
    }
}
?>
