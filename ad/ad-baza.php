<?php
    if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die (aa("Доступ закрыт!")); }
    $aid = trim($aid);
    global $prefix, $db, $red;
    $row = $db->sql_fetchrow($db->sql_query("SELECT `realadmin` FROM ".$prefix."_authors where `aid`='".$aid."'"));
    $realadmin = $row['realadmin'];
    if ($realadmin == 1) {
##################################################################################################
function base_base($name) {
    global $url, $prefix, $db, $p, $sortir, $show, $active_sort, $interval_sort, $search_sort, $itogo_show, $doc;
    if ($p == "") $p=0;
    if ($doc == "") {
        include("ad/ad-header.php");
    } else {
        ob_start();
        header("Content-Disposition: attachment; Content-type: application/msword; filename=База_данных.htm"); 
    }
    // Определяем имя и настройки раздела
    $sql = "SELECT `id`, `title`, `text` FROM ".$prefix."_mainpage where `type`='2' and (`name` = '".$name."' or `name` like '".$name." %')";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $module_id = $row['id']; // номер раздела
    $module_title = $row['title']; // Название раздела
    $module_options = explode("|",$row['text']); 
    $module_options = str_replace($module_options[0]."|", "", $row['text']);
    $base = 0;
    $lim = 100; // Настройка кол-ва выводимых элементов по-умолчанию.
    parse_str($module_options); // Настройки раздела
    
    $sql = "SELECT `name`, `title`, `text` FROM ".$prefix."_mainpage where `id`='".$base."' and `type`='5'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $baza_title  = $row['title']; // Название БД
    $baza_options = explode("|",$row['text']); 
    $baza_options = str_replace($baza_options[0]."|", "", $row['text']);
    $baza_name  = $row['name']; // Название таблицы БД 
    $type = 2; // Тип базы данных
    $status = "Удалено|#fcccd7#!#Утверждено|#dddddd#!#Проверить|#eeeeee#!#В_работе|#ffffff";
    // Значение статуса: Название|цвет
    $del_stroka = $edit_stroka = 1; // Удаление и Редактирование строки
    $num_day_stroka = $num_day_sum_stroka = 0; // Ограничение кол-ва и суммы значений вносимых каждый день строк
    $message = ""; // Дополнительные сообщения
    parse_str($baza_options);
    $offset = $p * $lim;

    $status_color = $status_int = $status_name = array();
    $i = 0;
    $status = explode("#!#", $status);
    foreach ($status as $value) {
        $s = explode("|", $value);
        $status_color[] = $s[1];
        $status_name[] = $s[0];
        $status_int[] = $i;
        $i++;
    }

    $where = array();

    if (isset($active_sort) && $active_sort != "") {
        $active_sort2 = intval($active_sort); 
        $where[] = "active = '".$active_sort."'";
    } else $active_sort2 = "";

    if (isset($itogo_show) && $itogo_show != "") {
        $itogo_show = intval($itogo_show);
        if ($itogo_show > 0) {
            $lim = 1000000;
        }
    } else $itogo_show = 0;

    if (isset($interval_sort) && $interval_sort != "") {
        $interval_sort2 = urldecode($interval_sort); 
        if (!strpos($interval_sort,"-")) {
            $where[] = "id >= '".$interval_sort."'"; 
        } else {
            $interval_sort_ex = explode("-",trim($interval_sort));
            $where[] = "id >= '".$interval_sort_ex[0]."' and id <= '".$interval_sort_ex[1]."'";
        }
    } else $interval_sort2 = "";
    $interval_sort3 = "1-100";

    if (isset($search_sort)) {
        $search_sort2 = explode(" ",str_replace("  "," ",trim(urldecode($search_sort)))); 
        // Перечислим все столбцы
        $sql3 = "SHOW columns from ".$prefix."_base_".$baza_name."";
            $result3 = $db->sql_query($sql3);
            $where_search = array();
            while ($row3 = mysql_fetch_assoc($result3)) {
                foreach ($search_sort2 as $value) {
                    if ($value != "" && $row3['Field'] != 'id' && $row3['Field'] != 'active' && $row3['Type'] != 'date') 
                        $where_search[] = "LOWER(`".$row3['Field']."`) like '%".str_replace(" ","%",mb_strtolower($value))."%'";
                }
            }
        if (count($where_search) > 0) $where[] = "(".implode(" or ",$where_search).")";
        $search_sort2 = implode(" ",$search_sort2);
    } else
        $search_sort2 = "";
    $search_sort3 = "слово (или слова через пробел)";

    if (isset($sortir)) {
        $sortir = explode("_",$sortir);
        $sort_way = intval($sortir[1]);
        if ($sort_way==1) {
            $sort_way = " desc";
            $sortir = intval($sortir[0]);
        } else {
            $sort_way = "";
            $sortir = intval($sortir[0]);
        }
    } else {
        $sort_way = " desc";
        $sortir = "-1";
    }

    $main_url = urldecode($url);

    if ($doc=="") echo "<div class='noprint radius' style='float:right; background: #dddddd; padding:5px;'>
    <a href=# onclick='print()' title='Распечатать (выберайте альбомный формат)' class='punkt noprint'><span class=\"icon gray small\" data-icon=\"P\"></span> Распечатать</a><br><br>

    <a href='".$main_url."&doc=true' title='Сохранить как документ Word' class='punkt noprint'><span class=\"icon gray small\" data-icon=\"w\"></span> Сохранить .doc</a><br><br>

    <a href='/sys.php?op=mainpage&id=".$base."' target='_blank' title='Настроить базу данных (только для администратора!)' class='punkt noprint'><span class=\"icon gray small\" data-icon=\"=\"></span> Настройки</a>
    </div>
    <h1 class='noprint'><a href=/sys.php?op=base_base&name=".$name.">".$module_title."</a>, база данных.
    <a href=/sys.php?op=base_base_create_base&base=$baza_name&name=$name&red=1 class='button'><span class='icon small green' data-icon='+'></span> Добавить строку</a>
    <a onclick='$(\"#base_search\").toggle();' class='button'><span class='icon small blue' data-icon='s'></span> Поиск</a>

    </h1>";

    if ($doc=="") {
        // Пароли
        if ($type==1) { // Если это база данных, а не магазин
            // id` ,  `base` ,  `data` ,  `user` ,  `pass` ,  `pause
            $sql3 = "SELECT * FROM ".$prefix."_bases where base='$base' order by data desc";
            $result3 = $db->sql_query($sql3);
            while ($row = $db->sql_fetchrow($result3)) {
                $id = $row['id'];
                $dat = explode(" ",$row['data']);
                $tim = $dat[1];
                $dat = explode("-",$dat[0]);
                $data = intval($dat[2])." ".findMonthName($dat[1])." ".$dat[0]." ".$tim;
                $user = $row['user'];
                $pass = $row['pass'];
                $info = $row['info'];
                $pause = $row['pause'];
                $color = "white";
                if ($pause == 1) { $color="pink"; $pause = "<a href='sys.php?op=base_base_pause_pass&id=".$id."&pause=0&cat=$name' title='Возобновить'><span class=\"icon gray small\" data-icon=\"2\"></span></a>"; 
                } else $pause = "<a href='sys.php?op=base_base_pause_pass&id=".$id."&pause=1&cat=$name' title='Отключить'><span class=\"icon red small\" data-icon=\"Q\"></span></a>";
                $passwords .= "<tr bgcolor='".$color."'><td>".$data."</td><td>".$user."</td><td>".$pass."</td><td>".$info."</td><td>
                $pause <a href='sys.php?op=base_base_pause_pass&id=$id&pause=3&cat=$name' title='Удалить'><span class=\"icon red small\" data-icon=\"T\"></span></a> 
                </td></tr>";
            }
            echo "<a class=punkt onclick=\"show('passwords');\"><strong>Список паролей</strong></a>
            <div id='passwords' style='display:none;'>
            <table width=100%><tr bgcolor=#dddddd><td>Дата создания</td><td>Кем создан</td><td>Пароль</td><td>Информация</td><td></td></tr>
            ".$passwords."</table>
            <br>
            <form method=\"POST\" action=\"sys.php\" enctype=\"multipart/form-data\"><strong>Создать новый пароль</strong><br>
            Информация: <input type=text name=info value=\"\" style='width:400px;'> <input type=submit value=\"Создать\">
            <input type=hidden name=op value=base_base_new_pass>
            <input type=hidden name=use value=\"Администратор\"><input type=hidden name=base value=\"$base\"><input type=hidden name=cat value=\"$name\">
            </form>
            </div><br>";
        }
    }
    #####################################################################################
    $opisanie = "СОРТИРОВКА:\n Первое нажатие - сортировка по этой колонке. \n Второе нажатие - сортировка в обратную сторону.";
    //echo $baza_options;
    $options = explode("/!/",$options); // ранее *
    $options_num = count($options);
    $names = array();
    $rus_names = array();
    $type_names = array();
    $noprint_pole = array(); // Те рус. имена, которые не надо печатать
    $noprint_pole_index = array(); // Те позиции, которые не надо печатать
    $zero_pole = array(); // Те рус. имена, которые надо печатать пустыми
    $zero_pole_index = array(); // Те позиции, которые надо печатать пустыми
    $hide_pole = array(); // Те рус. имена, которые не надо показывать и печатать
    $hide_pole_index = array(); // Те позиции, которые не надо показывать и печатать
    $filters = $filters_name = array(); // Фильтры
    for ($x=0; $x < $options_num; $x++) {
        $option = explode("#!#",$options[$x]); // ранее !
        $xx = $x;
        $names[] = $option[0];

        if ($doc=="") {
            $link = " <a href='".$main_url."&sortir=".$xx."_0' title=\"".$opisanie."\" class='noprint punkt'>↓</a>";
            if ($sortir == $xx) {
                if ($sort_way == " desc") {
                    $link = " <a href='".$main_url."&sortir=".$xx."_0' title=\"".$opisanie."\" class='noprint punkt'>↑↑</a>";
                } else {
                    $link = " <a href='".$main_url."&sortir=".$xx."_1' title=\"".$opisanie."\" class='noprint punkt'>↓↓</a>";
                }
            }
        } else $link = "";

        if (isset($option[6]))
            if ($option[6] == 1) { // включить фильтр для этого поля
                $filters[ ] = $option[0]; // добавили англ. имя поля
                $filters_name[ ] = $option[1]; // добавили рус. имя поля
            } else {
                $filters[ ] = ""; // добавили англ. имя поля
                $filters_name[ ] = ""; // добавили рус. имя поля
            }
        $rus_names[] = $option[1].$link;
        $type_names[ ] = $option[2];
        if ($option[3] == 4) { // не важно и не печатать
            $noprint_pole[] = $option[1];
            $noprint_pole_index[] = $x+1;
        }
        if ($option[3] == 5) { // пустая для печати
            $zero_pole[] = $option[1];
            $zero_pole_index[] = $x+1;
        }
        if ($option[3] == 6 or $option[3] == 7) { // не печатать и не показывать, 6 - не важно к заполнению, 7 - важно
            $hide_pole[] = $option[1];
            $hide_pole_index[] = $x+1;
        }
    }

    // WHERE

    foreach ($filters as $key => $value) {
        if (isset($show[$value]) && $show[$value] != "") {
            if ( $type_names[$key] == 'дата' ) {
                if (mb_strpos($show[$value], "-")) { // диапазон дат
                    $datas = explode("-", urldecode($show[$value]));
                    $where[] = "((`".$value."` >= '".date2normal_view($datas[0], 1)."' and `".$value."` <= '".date2normal_view($datas[1], 1)."') or `".$value."` = '0000-00-00')";
                } else // одиночная дата
                    $where[] = "`".$value."`='".date2normal_view(urldecode($show[$value]), 1)."'";
            } elseif ($type_names[$key] == 'число') { 
                $where[] = "`".$value."` = '".trim($show[$value])."'";
            } elseif ($type_names[$key] == 'строка' || $type_names[$key] == 'список' || mb_strpos(" ".$type_names[$key], "table|")) { 
                $where[] = "`".$value."` = '".$show[$value]."'";
            } else { // строка без вариантов, текст
                $where[] = "LOWER(`".$value."`) like '%".mb_strtolower(str_replace(" ","%",urldecode($show[$value])))."%'";
            }
        }
    }

    $where = implode(" and ", $where);

        $names["-1"] = "id";
        $names["-2"] = "active";

        if ($doc=="") {
            $link_id = " <a href='".$main_url."&sortir=-1_0' title=\"".$opisanie."\" class='noprint punkt'>↓</a>";
            if ($sortir == "-1") {
                if ($sort_way == " desc") {
                $link_id = " <a href='".$main_url."&sortir=-1_0' title=\"".$opisanie."\" class='noprint punkt'>↑↑</a>";
                } else {
                $link_id = " <a href='".$main_url."&sortir=-1_1' title=\"".$opisanie."\" class='noprint punkt'>↓↓</a>";
                }
            }
            $link_active = " <a href='".$main_url."&sortir=-2_0' title=\"".$opisanie."\" class='noprint punkt'>↓</a>";
            if ($sortir == "-2") {
                if ($sort_way == " desc") {
                $link_active = " <a href='".$main_url."&sortir=-2_0' title=\"".$opisanie."\" class='noprint punkt'>↑↑</a>";
                } else {
                $link_active = " <a href='".$main_url."&sortir=-2_1' title=\"".$opisanie."\" class='noprint punkt'>↓↓</a>";
                }
            }
        } else { $link_id = ""; $link_active = ""; }

        $pass_num = count($rus_names);
        $rus_names = implode("</td><td>",$rus_names);
        $hide_pole_num = count($hide_pole);
        for ($i=0; $i < $hide_pole_num; $i++) { 
            $rus_names = str_replace("<td>".$hide_pole[$i], "<td class='noprint print'>".$hide_pole[$i], $rus_names); // скрываем имя при печати
        }
        $noprint_pole_num = count($noprint_pole);
        for ($i=0; $i < $noprint_pole_num; $i++) { 
            $rus_names = str_replace("<td>".$noprint_pole[$i], "<td class='noprint'>".$noprint_pole[$i], $rus_names); // скрываем имя при печати
        }
        $zero_pole_num = count($zero_pole);
        for ($i=0; $i < $zero_pole_num; $i++) { 
            $rus_names = str_replace("<td>".$zero_pole[$i], "<td class='print'>".$zero_pole[$i], $rus_names); // скрываем имя для вывода на экран
        }
        if ($where != "") {
            $where = "where ".stripcslashes($where); 
            $where2 = $where." and active != '3'"; 
        } else $where2 = "where active != '3'";
        $order = " order by ".$names[$sortir].$sort_way.", id desc";

        $result = $db->sql_query("SELECT * FROM ".$prefix."_base_".$baza_name." ".$where);
        $numrows = $db->sql_numrows($result);

        if ($doc=="") {
            if ($main_url != "/sys.php?op=base_base&name=".$name) {
                $hide_search = ""; 
                $show_all_button = "<a class='button medium' href='#' onClick=\"location='sys.php?op=base_base&name=".$name."'\"><span class='icon small' data-icon='v'></span> Показать все</a>";
            } else {
                $hide_search = " hide";
                $show_all_button = "";
            }
            echo "<div class='block noprint".$hide_search."' id='base_search'>
            <p class='noprint'><span class=h2>Поиск:</span> <input type=text id=\"f_search\" name=\"search_sort\" value=\"".$search_sort2."\" placeholder='".$search_sort3."' size=30>";
        $another_tables = array();

        foreach ($type_names as $type_name) {
            if (strpos(" ".$type_name, "table|")) {
                // Получаем все поля колонки другой таблицы
                $baza_name2 = explode("|",str_replace("table|","",$type_name));
                $sql = "SELECT `id`,`".$baza_name2[1]."` FROM ".$prefix."_base_".$baza_name2[0]." where `active`!='0'";
                $result = $db->sql_query($sql);
                while ($row = $db->sql_fetchrow($result)) {
                    $id = $row['id'];
                    $another_tables[$type_name][$id] = $row[ $baza_name2[1] ];
                }
            }
        }
        $filter_urls = "";
        $date_now0 = date2normal_view(date("Y-m-d", time()-86400)); // вчера
        $date_now1 = date2normal_view(date("Y-m-d")); // сегодня
        $date_now2 = date2normal_view(date("Y-m-d", time()+86400)); // завтра
        $date_now3 = date2normal_view(date("Y-m-d", time()+172800)); // послезавтра
        $date_now4 = date2normal_view(date("Y-m-d", mktime(0, 0, 0, date("m")-1, 1,   date("Y")))); // прошлый месяц
        $date_now4_2 = date2normal_view(date("Y-m-d", mktime(0, 0, 0, date("m")-1, 31,   date("Y")))); // прошлый месяц
        $date_now5 = date2normal_view(date("Y-m-")."1"); // месяц
        $date_now5_2 = date2normal_view(date("Y-m-")."31"); // месяц
        $date_now6 = date2normal_view(date("Y-m-d", mktime(0, 0, 0, date("m")+1, 1,   date("Y")))); // следующий месяц
        $date_now6_2 = date2normal_view(date("Y-m-d", mktime(0, 0, 0, date("m")+1, 31,   date("Y")))); // следующий месяц
        $date_now7 = date2normal_view(date("Y-m-d", mktime(0, 0, 0, 1, 1, 2000))); // вечность
        $date_now7_2 = date2normal_view(date("Y-m-d", mktime(0, 0, 0, 1, 1, 3000))); // вечность

        foreach ($filters as $key => $value) {
            if ($value != "") {
                if ( $type_names[$key] == 'дата' ) { // Выбор по дате, если есть такое поле
                    if (!isset($show[$value])) {
                        $show[$value] = $date_now7;
                        $show2[$value] = $date_now7_2; //date2normal_view(date("Y-m-d"));
                    } elseif (mb_strpos($show[$value], "-")) { // диапазон дат
                        $datas = explode("-", urldecode($show[$value]));
                        $show[$value] = $datas[0];
                        $show2[$value] = $datas[1];
                    } else $show2[$value] = $show[$value];
                    echo "<script>$(function() { $.datepicker.setDefaults( $.datepicker.regional[ \"ru\" ] ); 
                        $( \"#f_".$value."\" ).datepicker({ numberOfMonths: 1, changeMonth: true, changeYear: true, dateFormat: \"d MM yy\", showAnim: 'slide' }); 
                        $( \"#ff_".$value."\" ).datepicker({ numberOfMonths: 1, changeMonth: true, changeYear: true, dateFormat: \"d MM yy\", showAnim: 'slide' }); 
                    });</script>
                    <p class='noprint'>".$filters_name[$key]." с <INPUT type=text name=\"text\" id=\"f_".$value."\" value='".$show[$value]."' readonly=1 size=15 onchange=\"$('#ff_".$value."').val( $('#f_".$value."').val() )\" onmouseover=\"this.style.background=&#39;#dddddd&#39;\" onmouseout=\"this.style.background=&#39;&#39;\" style='cursor:pointer; width:140px;'> по <INPUT type=text name=\"text\" id=\"ff_".$value."\" value='".$show2[$value]."' readonly=1 size=15 onmouseover=\"this.style.background=&#39;#dddddd&#39;\" onmouseout=\"this.style.background=&#39;&#39;\" style='cursor:pointer; width:140px;'>
                    <nobr><a class='small button' onclick=\"$('#ff_".$value."').val('".$date_now0."'); $('#f_".$value."').val('".$date_now0."')\"><</a><a class='small button' onclick=\"$('#ff_".$value."').val('".$date_now1."'); $('#f_".$value."').val('".$date_now1."')\">сегодня</a><a class='small button' onclick=\"$('#ff_".$value."').val('".$date_now2."'); $('#f_".$value."').val('".$date_now2."')\">></a><a class='small button' onclick=\"$('#ff_".$value."').val('".$date_now3."'); $('#f_".$value."').val('".$date_now3."')\">>></a> <a class='small button' onclick=\"$('#ff_".$value."').val('".$date_now4_2."'); $('#f_".$value."').val('".$date_now4."')\"><</a><a class='small button' onclick=\"$('#ff_".$value."').val('".$date_now5_2."'); $('#f_".$value."').val('".$date_now5."')\">месяц</a><a class='small button' onclick=\"$('#ff_".$value."').val('".$date_now6_2."'); $('#f_".$value."').val('".$date_now6."')\">></a> <a class='small button' onclick=\"$('#ff_".$value."').val('".$date_now7_2."'); $('#f_".$value."').val('".$date_now7."')\">всё</a></nobr>";
                    $filter_urls .= "&show[".$value."]=' + $('#f_".$value."').val() + '-' + $('#ff_".$value."').val() + '";
                } elseif ( $type_names[$key] == 'строка' || $type_names[$key] == 'список' || mb_stripos(" ".$type_names[$key], 'table|')) {
                    if (!isset($show[$value]) || $value == "") $show[$value] = $show_value = "";
                    else $show_value = $show[$value];
                    if (mb_stripos(" ".$type_names[$key], 'table|') 
                        && isset( $another_tables[ $type_names[$key] ][ $show[$value] ] ) ) 
                        $show[$value] = $another_tables[ $type_names[$key] ][ $show[$value] ];
                    echo "<p class='noprint'>".$filters_name[$key]." ".vybor_stroka($baza_name, "f_".$value."", $value, $show_value."|||".$show[$value], 250, $another_tables, $type_names[$key]); // 
                    $filter_urls .= "&show[".$value."]=' + document.getElementById('f_".$value."').value + '";
                } elseif ( $type_names[$key] == 'число') {
                    if (!isset($show[$value])) $show[$value] = "";
                    echo "<p class='noprint'>".$filters_name[$key]." ".input("f_".$value."", $show[$value], 10, 'input', " id=\"f_".$value."\"")." (точное значение)";
                    $filter_urls .= "&show[".$value."]=' + document.getElementById('f_".$value."').value + '";
                } else {
                    if (!isset($show[$value])) $show[$value] = "";
                    echo "<p class='noprint'>".$filters_name[$key]." ".input("f_".$value."", $show[$value], 25, 'input', " id=\"f_".$value."\"")." (неточно)";
                    $filter_urls .= "&show[".$value."]=' + document.getElementById('f_".$value."').value + '";
                }
            }
        }
        echo "<p class='noprint'>№ <input type=text id=\"f_interval\" name=\"interval_sort\" value=\"".$interval_sort2."\" placeholder='".$interval_sort3."' size=6> 23-45 или 34 (если от цифры до конца).

        <p class='noprint'>Статус ".select("f_active",','.implode(',',$status_int),','.implode(',',$status_name), $active_sort2, " class='w45'")."

        <p class='noprint'>Дополнительные возможности ".select("f_itogo","0,2,1","- выберите из списка -,Показать все строки сразу,С числами: Сумма[|] среднее[|] макс./мин. + все строки", $itogo_show)."

        <p class='noprint'><a class='button big' href='#' onClick=\"location='".$main_url.$filter_urls."&active_sort=' + document.getElementById('f_active').value + '&search_sort=' + document.getElementById('f_search').value + '&interval_sort=' + document.getElementById('f_interval').value + '&itogo_show=' + document.getElementById('f_itogo').value + '&show[".$value."]=' + $('#f_".$value."').val()\"><span class='icon medium green' data-icon='s'></span> Найти</a> ".$show_all_button."
        <br><br><br><br>
        </div>";

        $date_now1 = date("Y-m-d");
        $date_now2 = date("Y-m-d",time()+86400); // завтра
        $date_now3 = date("Y-m-d",time()+172800); // послезавтра
        $date_now4 = date("Y-m-d",time()-86400); // вчера

        $all = $db->sql_numrows($db->sql_query("select id from ".$prefix."_base_".$baza_name.""));

        if ( in_array("data",$names) ) {
            $segodnya = $db->sql_numrows($db->sql_query("select id from ".$prefix."_base_".$baza_name." where data = '$date_now1' and active = '3'"));

            $zavtra = $db->sql_numrows($db->sql_query("select id from ".$prefix."_base_".$baza_name." where data = '$date_now2' and active = '3'"));

            $poslezavtra = $db->sql_numrows($db->sql_query("select id from ".$prefix."_base_".$baza_name." where data = '$date_now3' and active = '3'"));

            $daty = "".date2normal_view($date_now1).": <b>".$segodnya."</b>. Завтра: <b>".$zavtra."</b>. Послезавтра: <b>".$poslezavtra."</b>."; // &nbsp; Вчера: ". $vchera;
        } else $daty = "";
        echo "<h1><b>".$numrows."</b> из ".$all.". ".$daty."</span></h1>";
    }

    $url = "sys.php?op=base_base&name=".$name; // getenv("REQUEST_URI");
    if ($sort_way == " desc") $sort_way = 1; else $sort_way = 0;

    if ($doc=="") echo "<div class='radius_top noprint' style='float:left;min-width:500;background:#eeeeee;text-align:center; height:17px; margin-left:50px; overflow:hidden;'>".base_links($numrows,$p,$main_url."&p=",$lim,"top")."</div>";

    echo "<table class='table_light_bd'><tr valign='top'><td title='Статус (Активность)' width='120'>Статус".$link_active."</td><td>№".$link_id."</td><td>".$rus_names."</td>";
    foreach ($names as $row3) {
        if (!in_array($row3, $names)) {
            switch ($row3) {
                //case "golos": echo "<td>Голосование</td>"; break;
                case "id":  break;
                case "active":  break;
                //case "comm": echo "<td>Кол-во коммент.:</td>"; break;
                //case "kol": echo "<td>Количество проданного:</td>"; break;
                default: echo "<td>Поле ".$row3."</td>"; break;
            }
        }
    }
    if ($doc=="") echo "<td class='noprint'></td>";
    echo "</tr>";

	$sql = "SELECT * FROM ".$prefix."_base_".$baza_name." ".$where."".$order." limit ".$offset.",".$lim;
    //echo $sql;
	$result = $db->sql_query($sql) or die($sql);
    $itogo = array();
    $all = 0;
	while ($row = $db->sql_fetchrow($result)) {
    	$base_id = $row['id'];
        $base_active = $status_name[ $row['active'] ]; 
        $color = $status_color[ $row['active'] ];
    	echo "<tr bgcolor=".$color." class='tr_hover tr".$base_id."'><td><a style='cursor:pointer; float:right;' onclick=\"$('.tr".$base_id."').toggleClass('tr_hide noprint');\" title=\"При нажатии строка не будет выведена при печати\"><div class='tr_icon tr_toggle_icon noprint'></div></a>".$base_active."</td>";
    	for ($x=0; $x < $pass_num+1; $x++) {
            if ($x !=0 and $type_names[$x-1]=="дата") {
                $tr = array(" Январь"=>" Янв"," Февраль"=>" Фев"," Март"=>" Март"," Апрель"=>" Апр"," Май"=>" Май"," Июнь"=>" Июнь"," Июль"=>" Июль"," Август"=>" Авг"," Сентябрь"=>" Сен"," Октябрь"=>" Окт"," Ноябрь"=>" Ноя"," Декабрь"=>" Дек");
                $row[$x] = strtr(date2normal_view($row[$x],0),$tr);
                if ($row[$x] == "0 ? 0") $row[$x] = "—";
            }
            $row[$x] = trim(str_replace("&nbsp;"," ",str_replace(",",", ",str_replace(".",". ",str_replace("/","/ ",strip_tags($row[$x]))))));
            if ($x !=0 && $type_names[$x-1]=="число" && $row[$x] != 0 && $itogo_show == 1) {
                preg_match_all('|\d+|', $row[$x], $matches); 
                $ro = array_sum($matches[0]);
                if (count($matches[0]) > 1) $row[$x] .= " <b>= ".$ro."</b>";
                $all++;
                $itogo[ $names[$x-1] ][] = $ro;
            }
            $pag_line = $row[$x];
            if ($x !=0 and strpos(" ".$type_names[$x-1], "table|")) {
                $pole_name = $type_names[$x-1];
                $pag_line = $another_tables[$pole_name][$pag_line];
            }

            if ( in_array(intval($x), $hide_pole_index) ) $nop = "f12 noprint print";
            elseif ( in_array(intval($x), $noprint_pole_index) ) $nop = "f12 noprint";
            else $nop = "f12"; 
            if ( in_array(intval($x), $zero_pole_index) ) echo "<td class='f12 print'></td>";
            else echo "<td class='".$nop."'>".$pag_line."</td>";
    	}
      
            echo "<td class='noprint'>";
            if ($edit_stroka == 1) echo "<a href='/sys.php?op=base_base_edit_base&name=".$name."&base=".$baza_name."&red=1&id=".$base_id."' title=\"Редактировать\"><div class='tr_icon tr_edit_icon noprint'></div></a> ";
            if ($del_stroka == 1) echo "<a href='/sys.php?op=base_base_delit_base&name=".$name."&base=".$baza_name."&id=".$base_id."' title=\"Удалить с подтверждением\"><div class='tr_icon tr_delete_icon noprint'></div></a>";
            echo "</td>";
        echo "</tr>";
        
    }

    echo "<tr valign=top class='block'><td class='noprint' width=120></td><td></td>";
    foreach ($names as $row3) {
        switch ($row3) {
            case "id":  break;
            case "active":  break;
            default:
                if (isset($itogo[ $row3 ])) echo "<td>С&nbsp;числами: ".$all."<br><b>Сумма: ".floatNumber(array_sum($itogo[ $row3 ]), 2)."</b><br>Среднее: ".floatNumber( array_sum($itogo[ $row3 ]) / $all, 2)."<br>Максимум: ".floatNumber(max($itogo[ $row3 ]), 2)."<br>Минимум: ".floatNumber(min($itogo[ $row3 ]), 2)."</td>"; 
                else echo "<td></td>"; 
            break;
        }
    }
    if ($doc=="") echo "<td class='noprint'></td>";
    echo "</tr>";

    echo "</table><p class='noprint'><span class=\"icon black small left3\" data-icon=\"x\"></span> — при нажатии строка не будет выведена при печати.";


    if ($doc=="") echo "<div class='radius_bottom noprint' style='min-width:500px;margin-right:50px;background:#eeeeee;float:right;text-align:center;'>".base_links($numrows,$p,$main_url."&p=",$lim,"bottom")."</div><br>";


    if ($doc=="") admin_footer();
    else {
        $txt = ob_get_contents(); 
        ob_end_clean();
        echo $txt;
    }
}
#############################################################################################
function base_base_edit_base($id, $base, $name, $red=0) {
    global $prefix, $db;
    include("ad/ad-header.php");
    echo "<br><a href='/sys.php?op=base_base&name=".$name."' style='padding-left:5px;padding-right:5px;' class='punkt radius'> &larr; Вернуться к базе данных </a></b><br>";
    echo "<h1>Редактирование строки №$id в базе данных</h1>";

    $sql = "SELECT * FROM ".$prefix."_base_".$base." WHERE `id`='".$id."'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);

    $sql2 = "SELECT `text` FROM ".$prefix."_mainpage WHERE `name`='".$base."' and `type`='5'";
    $result2 = $db->sql_query($sql2);
    $row2 = $db->sql_fetchrow($result2);

    // Верстаем данные, которые заносятся в таблицу
    $text = explode("|",$row2['text']); 
    $text = str_replace($text[0]."|", "", $row2['text']);
    $status = "Удалено|#fcccd7#!#Утверждено|#dddddd#!#Проверить|#eeeeee#!#В_работе|#ffffff";
    // Значение статуса: Название|цвет
    $message = ""; // Дополнительные сообщения
    parse_str($text);
    $status_name = $status_int = array();
    $status = explode("#!#", $status);
    $i = 0;
    foreach ($status as $value) {
        $s = explode("|", $value);
        $status_name[] = $s[0];
        $status_int[] = $i;
        $i++;
    }

    $options = explode("/!/",$options); // $!$  ранее *
    $n = count($options);

    if ($message != "") echo "<div style='float:right; width:300px; position:absolute; z-index:-1; top: 280px; right:10px;'>".$message."</div>";

    echo "<form method=\"POST\" action=\"sys.php\" enctype=\"multipart/form-data\">
    <table><tr valign=top><td class='radius' style='background: #dddddd; opacity:0.9; filter:alpha(opacity=90); -moz-opacity:0.9;'>";

    $rowY = array();
    for ($x=0; $x < $n; $x++) {
    	$one = explode("#!#",$options[$x]); // #!#  ранее !
        $rowY[] = $one[0];
        if ($one[3] != 5) { // Если это не пустая ячейка, т.е. Важность не равна «пустая для печати»
            switch ($one[2]) {


            case "текст": 
                echo "<p>".$one[1].":<br>";
                if ($red==0) {
                } elseif ($red==2) {
                echo "<textarea cols=80 id=editor name=\"text[".$one[0]."]\" rows=10>".$row[$x+1]."</textarea>
                <script type=\"text/javascript\">
                CKEDITOR.replace( 'editor', {
                 filebrowserBrowseUrl : '/ckfinder/ckfinder.html',
                 filebrowserImageBrowseUrl : '/ckfinder/ckfinder.html?Type=Images',
                 filebrowserFlashBrowseUrl : '/ckfinder/ckfinder.html?Type=Flash',
                 filebrowserUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                 filebrowserImageUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                 filebrowserFlashUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                });
                </script>";
                } else {
                    echo "<textarea name=\"text[".$one[0]."]\" rows=\"3\" cols=\"60\">".$row[$x+1]."</textarea>
                    <input type=hidden name=\"type[".$one[0]."]\" value=\"текст\"><br>";
                }
                echo "</p>"; 
            break;


            case "строка": 
                echo "<p>".$one[1].": <input placeholder='написать или выбрать из вариантов' type=text id=\"".$one[0]."\" name=\"text[".$one[0]."]\" value=\"".$row[$x+1]."\" size=40>"; 
                // Добавляем выбор вариантов
                $stroka = $one[0]; 
                $opt = "";
                $rows = array();
                $sql1 = "SELECT ".$stroka." FROM ".$prefix."_base_".$base." order by ".$stroka."";
                $result1 = $db->sql_query($sql1);
                while ($row1 = $db->sql_fetchrow($result1)) {
                    $rows[] = $row1[$stroka];
                }
                $rows = array_unique($rows);
                $nu = 0;
                foreach ($rows as $r) { // Действует ограничение в 100 элементов раскрывающегося списка.
                    if ($nu < 100) $opt .= "<option value=\"".$r."\">".$r."</option>";
                    $nu++;
                }
                //if ($one[4] == 0 or $one[4] == 2) 
                echo "<select name=vybor[".$one[0]."] onchange=\"$('#".$one[0]."').val(this.value);\" style='width:120px;'><option value=\"\" style=\"background:#dddddd;\">".aa("варианты...")."</option>".$opt."</select><input type=hidden name=\"type[".$one[0]."]\" value=\"строка\"></p>";
            break;


            case "строкабезвариантов": 
                echo "<p>".$one[1].": <input type=text id=\"".$one[0]."\" name=\"text[".$one[0]."]\" value=\"".$row[$x+1]."\" size=50>"; 
                if ($one[4] == 0 or $one[4] == 2) echo "<input type=hidden name=\"type[".$one[0]."]\" value=\"строка\"></p>"; 
            break;


            case "список": 
                echo "<p>".$one[1].": "; 
                // Добавляем выбор вариантов
                $stroka = explode(",",$one[5]); 
                $opt = "";
                foreach ($stroka as $r) {
                    //$r = trim($r);
                    $opt .= "<option value=\"".$r."\">".$r."</option>";
                }
                if ($one[4] == 0 or $one[4] == 2) echo " <select id=\"".$one[0]."\" name=\"text[".$one[0]."]\"><option value=\"".$row[$x+1]."\" style=\"background:#dddddd;\" selected>".$row[$x+1]."</option>".$opt."</select>
                <input type=hidden name=\"type[".$one[0]."]\" value=\"список\"></p>"; 
            break;


            case "число": 
                echo "<p>".$one[1].": <input placeholder='0 (число или сумма + чисел)' type=text name=\"text[".$one[0]."]\" value=\"".$row[$x+1]."\" size=30>
                <input type=hidden name=\"type[".$one[0]."]\" value=\"число\"></p>"; 
            break;


            case "дата": 
                $dat = date2normal_view($row[$x+1]);
                echo "<script>$(function() { $.datepicker.setDefaults( $.datepicker.regional[ \"ru\" ] ); $( \"#f_date_c_".$one[0]."\" ).datepicker({ numberOfMonths: 2, changeMonth: true, changeYear: true, dateFormat: \"d MM yy\", showAnim: 'slide' }); });</script>
                <p>".$one[1].": <INPUT placeholder='&rarr; выберите дату &larr;' type='text' name=\"text[".$one[0]."]\" id=\"f_date_c_".$one[0]."\" value=\"".$dat."\" readonly='1' size='20' onmouseover=\"this.style.background=&#39;#dddddd&#39;\" onmouseout=\"this.style.background=&#39;&#39;\" style='cursor:pointer;' onChange=\"document.getElementById('f_date_c[".$one[0]."]').value = this.value\"></p>
                <INPUT type='hidden' name=\"text[".$one[0]."]\" id=\"f_date_c[".$one[0]."]\" value=\"".$dat."\">
                <input type='hidden' name=\"type[".$one[0]."]\" value=\"дата\"></p>"; 
            break;


            case "датавремя": 
                echo "<p>".$one[1].":<br><input type=text name=\"text[".$one[0]."]\" value=\"".$row[$x+1]."\" size=17>
                <input type=hidden name=\"type[".$one[0]."]\" value=\"датавремя\"></p>"; 
            break;


            default:
                echo "<p>".$one[1].": "; // Добавляем выбор вариантов
                $opt = "";
                $all = explode("|",$one[2]);
                $sql2 = "select `id`, `".$all[2]."` from ".$prefix."_base_".$all[1]." where `active`!='0' order by `".$all[2]."`";
                $result2 = $db->sql_query($sql2);
                while ($row2 = $db->sql_fetchrow($result2)) {
                    $title = $row2[ $all[2] ];
                    $sel = "";
                    if ($row2['id'] == $row[$x+1]) $sel = " selected";
                    $opt .= "<option value='".$row2['id']."'".$sel.">".$title."</option>";
                }
                echo "<select id='".$one[0]."' name='text[".$one[0]."]'>".$opt."</select>
                <input type=hidden name='type[".$one[0]."]' value='список'></p>";
            break;
            }
        }

    }

    $x = 0;
    $xx = $n; // счетчик позиции ячейки
            $sql3 = "SHOW COLUMNS FROM ".$prefix."_base_".$base."";
            $result3 = $db->sql_query($sql3);
            while ($row3 = mysql_fetch_assoc($result3)) {
            
                if (!in_array($row3['Field'], $rowY)) {

                    switch ($row3['Field']) {
                        case "golos": 
                        echo "<p><b>Голосование:</b><br><input type=text name=\"text[golos]\" value=\"".$row[$xx]."\" size=5> (число с точкой)
    <input type=hidden name=\"type[golos]\" value=\"строка\"></p>";
                        break;

                        case "id": 
                        break;

                        case "active": 
                        break;

                        case "comm": 
                        echo "<p><b>Кол-во комментариев:</b><br><input type=text name=\"text[comm]\" value=\"".$row[$xx]."\" size=5> (целое число)
    <input type=hidden name=\"type[comm]\" value=\"число\"></p>";
                        break;

                        case "kol": 
                        echo "<p><b>Количество проданного товара:</b><br><input type=text name=\"text[kol]\" value=\"".$row[$xx]."\" size=5> (целое число)
    <input type=hidden name=\"type[kol]\" value=\"число\"></p>";
                        break;

                        default: // на всякий случай
                        echo "<p><b>Поле ".$row3['Field'].":</b><br><input type=text name=\"text[".$row3['Field']."]\" value=\"".$row[$xx]."\" size=5>
    <input type=hidden name=\"type[".$row3['Field']."]\" value=\"текст\"></p>";
                        break;
                    }
                $xx++;
                }
            }


    if (!isset($alerts)) $alerts = "";

    echo "<br><br>
    </td><td width=150>
    <input type=submit value='Сохранить\nизменения' style='width:100%; height:65px; font-size: 20px;' onClick=\" al=''; ".$alerts." if (al) { alert(al); return false; } else { submit(); } \"><br><br><b>Статус:</b><br>
    ".select("active",implode(',',$status_int),implode(',',$status_name), $row['active'], " size=10")."
    </td></tr></table>
    <input type='hidden' name='op' value='base_base_edit_sv_base'>
    <input type='hidden' name='id' value='".$id."'>
    <input type='hidden' name='base' value='".$base."'>
    <input type='hidden' name='name' value='".$name."'>
    </form>";
    admin_footer();
    // Ограничение: не более 100 элементов в автоматических списках <i>напишите или выберите вариант</i>.<br>Ограничение используется для ускорения загрузки страницы.
}
#####################################################################################################################
function base_base_create_base($base, $name, $red=0) {
    global $prefix, $db, $title_razdel_and_bd, $ok;
    $alerts  = "";
    include("ad/ad-header.php");
    echo "<br><a href=/sys.php?op=base_base&name=".$name." style='padding-left:5px;padding-right:5px;' class='punkt radius'> &larr; Вернуться к базе данных </a></b><br>";
    if (intval($ok) == 1) echo "<h2 class=green>Строка успешно добавлена. Добавим еще одну?</h2>";
    echo "<h1>Добавление строки к базе данных «".trim($title_razdel_and_bd[$name])."»</h1>";
    $sql2 = "SELECT `text` FROM ".$prefix."_mainpage WHERE `name`='".$base."' and `type`='5'";
    $result2 = $db->sql_query($sql2);
    $row2 = $db->sql_fetchrow($result2);
    
    // Верстаем данные, которые заносятся в таблицу
    $text = explode("|",$row2['text']); 
    $text = str_replace($text[0]."|", "", $row2['text']); 
    $status = "Удалено|#fcccd7#!#Утверждено|#dddddd#!#Проверить|#eeeeee#!#В_работе|#ffffff";
    // Значение статуса: Название|цвет
    $message = ""; // Дополнительные сообщения
    $num_day_stroka = $num_day_sum_stroka = 0;
    parse_str($text);
    $status_name = $status_int = array();
    $status = explode("#!#", $status);
    $i = 0;
    foreach ($status as $value) {
        $s = explode("|", $value);
        $status_name[] = $s[0];
        $status_int[] = $i;
        $i++;
    }

    $options = explode("/!/",$options); // $!$  ранее *
    $n = count($options);

    if ($message != "") echo "<div style='float:right; width:300px; position:absolute; z-index:-1; top: 280px; right:10px;'>".$message."</div>";

    echo "<form method='POST' name='send' action='sys.php' enctype='multipart/form-data'>
    <table><tr valign=top><td class='radius' style='background: #dddddd;'>";

    $rowY = array();
            
    for ($x=0; $x < $n; $x++) {
    	$one = explode("#!#",$options[$x]); // #!#  ранее !
        $rowY[] = $one[0];
        if ($one[3] != 5) { // Если это не пустая ячейка, т.е. Важность не равна «пустая для печати»
            switch ($one[2]) {

                case "текст": 
                    echo "<p>".$one[1].":<br>";
                    if ($red==0) {
                    } elseif ($red==2) {
                        echo "<textarea cols=80 id=\"".$one[0]."\" name=\"text[".$one[0]."]\" rows=10></textarea>
                        <script type=\"text/javascript\">
                        CKEDITOR.replace( 'editor', {
                         filebrowserBrowseUrl : '/ckfinder/ckfinder.html',
                         filebrowserImageBrowseUrl : '/ckfinder/ckfinder.html?Type=Images',
                         filebrowserFlashBrowseUrl : '/ckfinder/ckfinder.html?Type=Flash',
                         filebrowserUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                         filebrowserImageUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                         filebrowserFlashUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
                        });
                        </script>";
                    } else {
                        echo "<textarea name=\"text[".$one[0]."]\" id=\"".$one[0]."\" rows=\"3\" cols=\"60\"></textarea>
                        <input type=hidden name=\"type[".$one[0]."]\" value=\"текст\"><br>";
                        // html_spravka(); // ?может убрать
                    }
                    echo "</p>"; 
                    if ($one[3] == 3 or $one[3] == 2 or $one[3] == 1 or $one[3] == 7) $alerts .= "if (document.getElementById('".$one[0]."').value=='') al = al + 'Не заполнено поле «".$one[1]."». \\r\\n';";
                break;


                case "строка": 
                    echo "<p>".$one[1].": <input placeholder='написать или выбрать из вариантов' type=text id=\"".$one[0]."\" name=\"text[".$one[0]."]\" value=\"\" size=40>"; 
                    // Добавляем выбор вариантов
                    $stroka = $one[0]; 
                    $opt = "";
                    $rows = array();
                    $sql1 = "SELECT ".$stroka." FROM ".$prefix."_base_".$base." order by ".$stroka."";
                    $result1 = $db->sql_query($sql1);
                    while ($row1 = $db->sql_fetchrow($result1)) {
                        $rows[] = $row1[$stroka];
                    }
                    $rows = array_unique($rows);
                    foreach ($rows as $r) {
                        $opt .= "<option value=\"".$r."\">".$r."</option>";
                    }
                    echo "<select name=vybor[".$one[0]."] onchange=\"document.getElementById('".$one[0]."').value = this.value;\" style='width:120px;'><option value=\"\">варианты...</option>".$opt."</select>
                    <input type=\"hidden\" name=\"type[".$one[0]."]\" value=\"строка\"></p>"; 
                    if ($one[3] == 3 or $one[3] == 2 or $one[3] == 1 or $one[3] == 7) $alerts .= "if (document.getElementById('".$one[0]."').value=='') al = al + 'Не заполнено поле «".$one[1]."». \\r\\n';";
                break;


                case "строкабезвариантов": 
                    echo "<p>".$one[1].": <input type=text id=\"".$one[0]."\" name=\"text[".$one[0]."]\" value=\"\" size=40>"; 
                    if ($one[4] == 0 or $one[4] == 2) echo "<input type=hidden name=\"type[".$one[0]."]\" value=\"строка\"></p>"; 
                    if ($one[3] == 3 or $one[3] == 2 or $one[3] == 1 or $one[3] == 7) $alerts .= "if (document.getElementById('".$one[0]."').value=='') al = al + 'Не заполнено поле «".$one[1]."». \\r\\n';";
                break;


                case "список": 
                    echo "<p>".$one[1].": "; // Добавляем выбор вариантов
                    $stroka = explode(",",$one[5]); 
                    $opt = "";
                    foreach ($stroka as $r) { 
                        $r = trim($r);
                        $opt .= "<option value=\"".$r."\">".$r."</option>";
                    }
                    if ($one[4] == 0 or $one[4] == 2) echo " <select id=\"".$one[0]."\" name=\"text[".$one[0]."]\"><option value=\"\">&rarr; выберите вариант &larr;</option>".$opt."</select>
                    <input type=\"hidden\" name=\"type[".$one[0]."]\" value=\"список\"></p>"; 
                    if ($one[3] == 3 or $one[3] == 2 or $one[3] == 1 or $one[3] == 7) $alerts .= "if (document.getElementById('".$one[0]."').value=='') al = al + 'Не заполнено поле «".$one[1]."». \\r\\n';";
                break;


                case "число": 
                    echo "<p>".$one[1].": <input placeholder='0 (число или сумма + чисел)' type=text id=\"".$one[0]."\" name=\"text[".$one[0]."]\" value=\"\" size=30>
                    <input type=\"hidden\" name=\"type[".$one[0]."]\" value=\"число\"></p>"; 
                    if ($one[3] == 3 or $one[3] == 2 or $one[3] == 1 or $one[3] == 7) $alerts .= "if (document.getElementById('".$one[0]."').value=='') al = al + 'Не заполнено поле «".$one[1]."». \\r\\n';";
                break;

                 
                case "дата": 
                    $add1 = "var num_day".$one[0]." = new Array();";
                    $add2 = "var num_day_sum".$one[0]." = new Array();";
                    $no_add1 = $no_add2 = "true";
                    if ($num_day_stroka != 0) { // получаем даты, в которые есть превышение кол-ва строк
                        $num_day_stroka_el = explode("|", $num_day_stroka);
                        if ($one[0] == $num_day_stroka_el[1]) {
                            $rows = array();
                            $sql = "SELECT `".$num_day_stroka_el[1]."` FROM ".$prefix."_base_".$base." GROUP BY `".$num_day_stroka_el[1]."` HAVING count(`id`)>=".$num_day_stroka_el[0];
                            
                            $result2 = $db->sql_query($sql);
                            while ($row2 = $db->sql_fetchrow($result2)) {
                                $rows[] = $row2[ $num_day_stroka_el[1] ];
                            }
                            if (count($rows)>0) $add1 = "var num_day".$one[0]." = new Array('".implode("','", $rows)."');";
                            $no_add1 = $num_day_stroka_el[2];
                        }
                    }

                    if ($num_day_sum_stroka != 0) { // получаем даты, в которые есть превышение суммы элементов в числовом поле
                        $num_day_sum_stroka_el = explode("|", $num_day_sum_stroka);
                        if ($one[0] == $num_day_sum_stroka_el[1]) {
                            $rows = array();
                            $result2 = $db->sql_query("SELECT `".$num_day_sum_stroka_el[1]."` FROM ".$prefix."_base_".$base." GROUP BY `".$num_day_sum_stroka_el[1]."` HAVING sum(`".$num_day_sum_stroka_el[2]."`)>=".$num_day_sum_stroka_el[0]);
                            while ($row2 = $db->sql_fetchrow($result2)) {
                                $rows[] = $row2[ $num_day_sum_stroka_el[1] ];
                            }
                            if (count($rows)>0) $add2 = "var num_day_sum".$one[0]." = new Array('".implode("','", $rows)."');";
                            $no_add2 = $num_day_sum_stroka_el[3];
                        }
                    }
                    if ($no_add1 == "") { $no_add1 = "true"; $add1 = "var num_day".$one[0]." = new Array();"; }
                    if ($no_add2 == "") { $no_add2 = "true"; $add2 = "var num_day_sum".$one[0]." = new Array();"; }
                    echo "<script>
                    $(function() { 
                        $.datepicker.setDefaults( $.datepicker.regional[ \"ru\" ] ); 
                        $( \"#f_date_c_".$one[0]."\" ).datepicker({ numberOfMonths: 2, beforeShowDay: highlight_noactive".$one[0].", changeMonth: true, changeYear: true, dateFormat: \"d MM yy\", showAnim: 'slide' }); });
                    ".$add1.$add2."
                    function highlight_noactive".$one[0]."(date) {
                      var Month = (date.getMonth() > 9) ? date.getMonth() + 1 : '0' + (date.getMonth() + 1);
                      var Day = (date.getDate() > 9) ? date.getDate() : '0' + date.getDate();
                      var date = date.getFullYear() + '-' + Month + '-' + Day;
                      var clas = '';
                      var show = true;
                      if ($.inArray(date, num_day".$one[0].") != -1 || $.inArray(date, num_day_sum".$one[0].") != -1) clas = 'red_date';
                      if ($.inArray(date, num_day".$one[0].") != -1) show = ".$no_add1.";
                      if ($.inArray(date, num_day_sum".$one[0].") != -1) show = ".$no_add2.";
                      return [show, clas];
                    }
                    </script>
                    <p>".$one[1].": <INPUT placeholder='&rarr; выберите дату &larr;' type='text' name=\"text[".$one[0]."]\" id=\"f_date_c_".$one[0]."\" readonly='1' size='20' onmouseover=\"this.style.background=&#39;#dddddd&#39;\" onmouseout=\"this.style.background=&#39;&#39;\" style='cursor:pointer;' onChange=\"document.getElementById('f_date_c[".$one[0]."]').value = this.value\">
                    <INPUT type='hidden' name=\"text[".$one[0]."]\" id=\"f_date_c[".$one[0]."]\"
                    <input type=hidden name=\"type[".$one[0]."]\" value=\"дата\"></p>"; 
                    if ($one[3] == 3 or $one[3] == 2 or $one[3] == 1 or $one[3] == 7) $alerts .= "if (document.getElementById('f_date_c_".$one[0]."').value=='') al = al + 'Не заполнено поле «".$one[1]."». \\r\\n';";
                break;


                case "датавремя": 
                    echo "<p>".$one[1].":<br><input type=text id=\"".$one[0]."\" name=\"text[".$one[0]."]\" value=\"\" size=17>
                    <input type=hidden name=\"type[".$one[0]."]\" value=\"датавремя\"></p>"; 
                    if ($one[3] == 3 or $one[3] == 2 or $one[3] == 1 or $one[3] == 7) $alerts .= "if (document.getElementById('".$one[0]."').value=='') al = al + 'Не заполнено поле «".$one[1]."». \\r\\n';";
                break;


                default:
                    echo "<p>".$one[1].": "; // Добавляем выбор вариантов
                    $opt = "";
                    $all = explode("|",$one[2]);
                    $sql2 = "select `id`, `".$all[2]."` from ".$prefix."_base_".$all[1]." where `active`!='0' order by `".$all[2]."`";
                    $result2 = $db->sql_query($sql2);
                    while ($row2 = $db->sql_fetchrow($result2)) {
                        $title = $row2[ $all[2] ];
                        $opt .= "<option value='".$row2['id']."'>".$title."</option>";
                    }
                    echo "<select id='".$one[0]."' name='text[".$one[0]."]'><option value=''>&rarr; выберите вариант &larr;</option>".$opt."</select>
                    <input type=hidden name='type[".$one[0]."]' value='список'></p>"; 
                    if ($one[3] == 3 or $one[3] == 2 or $one[3] == 1 or $one[3] == 7) $alerts .= "if (document.getElementById('".$one[0]."').value=='выберите вариант') al = al + 'Не заполнено поле «".$one[1]."». \\r\\n';";
                break;
            }
        }
    }

    $sql3 = "SHOW COLUMNS FROM ".$prefix."_base_".$base."";
    $result3 = $db->sql_query($sql3);
    while ($row3 = mysql_fetch_assoc($result3)) {
        if (!in_array($row3['Field'], $rowY)) {
            switch ($row3['Field']) {
                case "golos": 
                echo "<p><b>Голосование:</b><br><input type=text name=\"text[golos]\" value=\"0\" size=5> (число с точкой)
<input type=hidden name=\"type[golos]\" value=\"строка\"></p>";
                break;
                
                case "id": 
                break;

                case "active": 
                break;
                
                case "comm": 
                echo "<p><b>Кол-во комментариев:</b><br><input type=text name=\"text[comm]\" value=\"0\" size=5> (целое число)
<input type=hidden name=\"type[comm]\" value=\"число\"></p>";
                break;
                
                case "kol": 
                echo "<p><b>Количество проданного товара:</b><br><input type=text name=\"text[kol]\" value=\"0\" size=5> (целое число)
<input type=hidden name=\"type[kol]\" value=\"число\"></p>";
                break;
                
                default: // на всякий случай
                echo "<p><b>Поле ".$row3['Field'].":</b><br><input type=text name=\"text[".$row3['Field']."]\" value=\"\" size=5>
<input type=hidden name=\"type[".$row3['Field']."]\" value=\"текст\"></p>";
                //$x++;
                break;
            }
        }
    }
    echo "<br><br>
    </td><td width=150>
    <input type='submit' value='Сохранить\nстроку' style='width:100%; height:65px; font-size: 20px;' onClick=\" al=''; ".$alerts." if (al) { alert(al); return false; } else { submit(); } \"><br><br><b>Статус:</b><br>
    ".select("active",implode(',',$status_int),implode(',',$status_name), '3', " size=10")."
    </td></tr></table>
    <input type='hidden' name='op' value='base_base_edit_sv_base'>
    <input type='hidden' name='id' value='0'>
    <input type='hidden' name='base' value='".$base."'>
    <input type='hidden' name='name' value='".$name."'>
    </form>";
    admin_footer();
    // <br>Ограничение: не более 100 элементов в списках на выбор (для строк)
}
#####################################################################################################################
function base_base_edit_sv_base($id=0, $text, $type, $base, $name, $active) {
    global $prefix, $db;
    $set = array();
    foreach ($text as $key => $txt) {
        switch ($type[$key]) {
            case "текст": $txt = str_replace("!!!","!",str_replace("!!!","!",$txt)); break;
            case "строка": $txt = str_replace("!!!","!",str_replace("!!!","!",$txt)); break;
            case "строкабезвариантов": $txt = str_replace("!!!","!",str_replace("!!!","!",$txt)); break;
            case "список": break;
            case "число": $txt = intval($txt); break;
            case "дата": $txt = date2normal_view($txt, 1); break;
            case "датавремя":  break;
        }
        $txt = stripslashes(FixQuotes(str_replace("&amp;","&",$txt))); 
        if ($id != 0) $set[] = "`".$key."` ='".$txt."'";
        else $set[] = "'".$txt."'";
    }
    $set = implode(", ",$set);

    if ($id != 0) {
    $db->sql_query("UPDATE ".$prefix."_base_".$base." SET ".$set.", `active`='".$active."' WHERE `id`='".$id."'") or die("Не удалось обновить информацию в базу данных. UPDATE ".$prefix."_base_".$base." SET ".$set.", active='".$active."' WHERE id='".$id."'");
    } else {
        global $referer;
        $db->sql_query("INSERT INTO ".$prefix."_base_".$base." VALUES (NULL, ".$set.", '".$active."');") or die("Не удалось добавить информацию в базу данных. INSERT INTO ".$prefix."_base_".$base." VALUES (NULL, ".$set.", '".$active."');");
        Header("Location: ".$referer."&ok=1"); // отправка на повтор
        exit;
    }
    Header("Location: sys.php?op=base_base&name=".$name."");
}
#####################################################################################################################
function base_links($records,$r_start=0,$URL,$inpage=20,$top="top") { // Строчка выбора страниц < 1 2 3 >
    //$records - всего записей 
    //$r_start - текущая страница 
    //$URL - адрес, заканчивающийся на "=" 
    //$inpage - записей на страницу
    $str="";
    if ($records<=$inpage) return;
    if ($r_start!=0) {
        $str.="<a class='base_page radius_".$top."' href='".$URL."0' title='первая страница'> &nbsp; << &nbsp; </a> ";
        $str.="<a class='base_page radius_".$top."' href='".$URL.($r_start-1)."' title='предыдущая страница'> &nbsp; < &nbsp; </a> ";
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
        if ($i==$r_start) $str.=" <B class='base_open_page radius_".$top."'> &nbsp; ".($i+1)." &nbsp;</B> &nbsp; ";
        else $str.="<a class='base_page radius_".$top."' href='".$URL.($i)."' title='".($i+1)." страница'>&nbsp; <B>".($i+1)."</B> &nbsp;</a> ";
        }
    if ($r_start+(1-$add)<intval($records/$inpage)) {
        $str.=" <a class='base_page radius_".$top."' href='".$URL.($r_start+1)."' title='следующая страница'>&nbsp; > &nbsp;</a> ";
        $str.=" <a class='base_page radius_".$top."' href='".$URL.(intval($records/$inpage)-(1-$add))."' title='последняя страница'>&nbsp; >> &nbsp;</a> ";
        }
    else $str.="";
    return($str);
}
###################################################################################################
function base_base_delit_base($base, $name, $id, $ok) {
    global $prefix, $db;
    $id = intval($id);
    if($ok) {
        $db->sql_query("DELETE FROM ".$prefix."_base_".$base." WHERE id='$id'");
        Header("Location: sys.php?op=base_base&name=$name#1");
    } else {
        include("ad/ad-header.php");
        echo "<br><center><b>Удаление строки из таблицы</b><br><br>";
        echo "Внимание! Вы хотите удалить строку <b>№ $id</b>.<br><br>";
        echo "Это правда?<br><br>[ <a href=\"sys.php?op=base_base&name=$name#1\"><b>НЕТ</b></a> | <a href=\"sys.php?op=base_base_delit_base&name=$name&base=$base&id=$id&ok=1\"><b>ДА</b></a> ]</center><br><br>";
        admin_footer();
    }
}
#######################################################################################################
function base_pause_pass($id, $pause, $cat) {
    global $prefix, $db;
    if ($pause==1 or $pause==0) $db->sql_query("UPDATE ".$prefix."_bases SET pause='".$pause."' WHERE id='".$id."'");
    if ($pause==3) $db->sql_query("DELETE FROM ".$prefix."_bases WHERE id='$id'");
    Header("Location: sys.php?op=base_base&name=$cat#1");
}
#######################################################################################################
function base_new_pass($base, $use, $cat, $info) {
    global $prefix, $db;
    // Генерация пароля
    $pass = generate_password(15); // 15 символов в пароле
    // Проверка наличия такого пароля ???
    $date = date("Y.m.d H:i:s");
    if ($use != "Администратор") $use = "Пользователь";
    if (trim($base) != "") $db->sql_query("INSERT INTO `".$prefix."_bases` (`id`, `base`, `data`, `user`, `pass`, `pause`, `info`) VALUES (NULL, '$base', '$date', '$use', '$pass', '0', '$info');");
    Header("Location: sys.php?op=base_base&name=$cat#1");
}
##############################################################
function vybor_stroka($base, $name, $stroka, $now="выберите", $width=200, $table="", $type="") {
    global $prefix, $db;
    $opt = "";
    $rows = array();
    $sql1 = "SELECT ".$stroka." FROM ".$prefix."_base_".$base." order by ".$stroka;
    $result1 = $db->sql_query($sql1);
    while ($row1 = $db->sql_fetchrow($result1)) {
        $rows[] = trim($row1[$stroka]);
    }
    $rows = array_unique($rows);
    $nu = 0;
    if (mb_stripos(" ".$type, 'table|'))
        foreach ($rows as $r) { // Действует ограничение в 500 элементов раскрывающегося списка.
            if ($nu < 500) $opt .= "<option value=\"".$r."\">".$table[$type][$r]."</option>";
            $nu++;
        }
    else 
        foreach ($rows as $r) { // Действует ограничение в 500 элементов раскрывающегося списка.
            if ($nu < 500) $opt .= "<option value=\"".$r."\">".$r."</option>";
            $nu++;
        }
    $no = explode("|||", $now);
    if (isset($no[1])) { $now2 = $no[1]; $now = $no[0];}
    else $now2 = $now;
    return "<select id='".$name."' style='width:".$width."px;'>
    <option value='".$now."' style='background:#dddddd;'>".$now2."</option>
    <option value='' style='background:#dddddd;'>= пусто =</option>".$opt."
    </select>";
}
##############################################################
    switch ($op) {
        case "base_base":
            if (!isset($id)) $id = "";
            base_base($name, $id, $pid);
            break;
        case "base_base_edit_base":
            base_base_edit_base($id, $base, $name, $red);
            break;
        case "base_base_edit_sv_base":
            base_base_edit_sv_base($id, $text, $type, $base, $name, $active);
            break;
        case "base_base_delit_base":
            if (!isset($ok)) $ok = 0;
            base_base_delit_base($base, $name, $id, $ok);
            break;
        case "base_base_create_base":
            base_base_create_base($base, $name, $red);
            break;
        case "base_base_pause_pass":
            base_pause_pass($id, $pause, $cat);
            break;
        case "base_base_new_pass":
            base_new_pass($base, $use, $cat, $info);
            break;
    }
}
?>