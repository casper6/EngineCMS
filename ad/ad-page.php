<?php
//if (!eregi("sys.php", $_SERVER['PHP_SELF']))
if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
$aid = trim($aid);
global $prefix, $db, $red;
$sql = "select realadmin from ".$prefix."_authors where aid='$aid'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$realadmin = $row['realadmin'];
if ($realadmin==1) {
$tip = "pages";
$admintip = "base_pages";

function menu() {
    global $module, $tip, $admintip, $prefix, $db, $op, $new;
    if (trim($module)=="") 
        $title = "";
    else {
        $sql = "SELECT title FROM ".$prefix."_mainpage where name='$module' and type='2'";
        $result = $db->sql_query($sql);
        $rows = $db->sql_fetchrow($result);
        $title = $rows['title'];
    }
    $redact=false;
    $h1 = "<h1><a href=sys.php>Разделы</a>  &rarr; ".$title."";
    if ($op == "base_pages_edit_page") { $h1 .= " &rarr; Редактирование страницы"; $redact=true; }
    if ($op == "base_pages_add_page" and $new != 1) { $h1 .= " Добавление страницы"; $redact=true; }
    if ($op == "base_pages_add_page" and $new == 1) { $h1 .= " Добавим еще одну страницу"; $redact=true; }
    if ($op == "base_pages_delit_page") $h1 .= " &rarr; Удаление страницы";
    if ($op == "base_pages_del_category") $h1 .= " &rarr; Удаление папки";
    if ($op == "edit_base_pages_category") { $h1 .= " &rarr; Редактирование папки"; $redact=true; }
    $h1 .= "</h1>";
    if ($redact==true) red_vybor();
    echo $h1;

}

// Страницы без папок и Страницы из нечаянно удаленных папок
function base_pages_derevo2($cids, $pages, $active, $title) {
global $name, $admintip, $prefix, $db, $tip;
$derevo = "";
$ver = mt_rand(10000, 99999); // получили случайное число
$no_pages = 0;
$yes_pages = 0;
$derevo .= "<li class='li_list li_mesto_0'><b>Страницы в начале раздела</b></li>";
foreach ($pages as $pid => $pag) {
$yes_pages++;
$cid = $cids[$pid];
if ($cid==0) {
$color=""; $nowork = "";
if ($active[$pid] == 0) { $color=" class=noact"; $nowork="отключена";}
$derevo .= "<li class='li_file'><a class=no title=\"".$title[$pid]."\" href=#".$ver.$pid." onclick='men($pid, \"$name\", \"$admintip\");'$color>".$pag."</a> $nowork <div id='pid".$pid."' style='display:inline;'></div></li>";
$no_pages++;
}
}

##############################################################
$derevo .= "<li class='li_list li_mesto_0'><hr size=1 color=darkgreen></li>
<li class='li_list li_mesto_0'><b>Страницы  из другого раздела или из удаленных папок</b></li>";

// Определяем все папки
$all_cid = array();
$sql = "SELECT cid FROM ".$prefix."_".$tip."_categories where module='$name' and `tables`='pages'";
$result = $db->sql_query($sql);
while ($rows = $db->sql_fetchrow($result)) {
$all_cid[] = $rows['cid'];
}
$yes_pages = 0;
foreach ($pages as $pid => $pag) {
$yes_pages++;
$cid = $cids[$pid];
if (!in_array($cid,$all_cid) and $cid!=0) {
$color=""; $nowork = "";
if ($active[$pid] == 0) { $color=" class=noact"; $nowork="отключена";}
$derevo .= "<li class='li_file li_mesto_$mesto'><a class=no title=\"".$title[$pid]."\" href=#".$ver.$pid." onclick='men($pid, \"$name\", \"$admintip\");'$color>$pag</a> $nowork <div id='pid".$pid."' style='display:inline;'></div></li>";
$no_pages++;
}
}

$derevo .= "<li class='li_list li_mesto_0'><hr size=1 color=darkgreen></li>
<li class='li_list li_mesto_0'><b>Всего страниц:</b> $yes_pages.<br><br></li>";
return $derevo."";
}
#####################################################################################################################
// Расширенное отображение страниц и папок
function base_pages_derevo3($names, $parents, $parent, $mesto, $cids, $pages, $active, $title) {
global $name, $admintip;
$derevo = "";
$granica = 5; // кол-во выводимых последних страниц
$no_pages = 0;
$ver = mt_rand(10000, 99999); // получили случайное число

// Пербираем названия папок текущего уровня
foreach ($names as $id => $nam) {

$derevo .= "";

$par = $parents[$id];
if ($par == $parent) {

// Показываем страницы этой папки
	$no_pages=0; // счетчик
	$derevo2 = "";
	foreach ($pages as $pid => $pag) {
	$cid = $cids[$pid];
		if ($cid==$id) {
$color=""; $nowork = "";
if ($active[$pid] == 0) { $color=" class=noact"; $nowork=" ОТКЛ. ";}
if ($active[$pid] == 2) { $color=" class=deact"; $nowork=" ПРОВЕРКА! ";}
if ($no_pages < $granica) $derevo2 .= "<li class='li_file li_mesto_$mesto'>$nowork<a class=no title=\"".$title[$pid]."\" href=#".$ver.$pid." onclick='men($pid, \"$name\", \"$admintip\");'$color>".$pag."</a><div id='pid".$pid."' style='display:inline; margin-left:5px;'></div></li>";
$no_pages++;
		}
	}

if ($no_pages == 0) $pusto = "<span class=red>пустая папка</span>"; else $pusto = "содержит $no_pages стр.";

$derevo .= "<script src=/includes/JsHttpRequest/JsHttpRequest.js></script><script language=JavaScript>\n
function open_pages_".$id."(a) {JsHttpRequest.query('ad-ajax.php', {'str': a}, function(result, errors) {if (result) {document.getElementById('cid_".$id."').innerHTML = result['str']; document.getElementById('del_".$id."').innerHTML = '';}},false);} \n</script>
<li class='li_papka li_mesto_$mesto'><a name=\"open_pages_".$id."\"></a><a class=\"no green\" href=#".$ver.$id." onclick='men2($id, \"$name\", \"$admintip\");'><span class=gray>папка</span> <strong>".$nam."</strong></a> ".$pusto." <div id='сid".$id."' style='display:inline; margin-left:5px;'></div></li>".$derevo2;

if ($granica < $no_pages) $derevo .= "<div id=cid_".$id." class='li_mesto_$mesto'><a id=del_".$id." href=\"#open_pages_".$id."\" onclick=\"open_pages_".$id."('".$id."'); return false;\"><span class=green>Раскрыть все страницы +".($no_pages - $granica)."</span></a></div>"; else $vsego = "";
//$derevo .= "<li class='li_list li_mesto_$mesto'> <font class=green>&nbsp;&nbsp;&nbsp;&nbsp; [всего  страниц]</font> $vsego</li>";

// Показываем подпапки
$de = base_pages_derevo3($names, $parents, $id, $mesto+1, $cids, $pages, $active);
if ($de=="") $derevo .= "<li class='li_list li_mesto_$mesto'></li>";
else $derevo .= "<li class='li_next li_mesto_$mesto'> $de</li>";

if ($mesto==0) $derevo .= "<hr size=1 color=darkgreen>";

}
}
return $derevo;
}
#####################################################################################################################
// Временное отображение страниц (последние 10)
function base_pages_derevo4($names, $parents, $parent, $mesto, $cids, $pages, $active, $title) {
global $name, $admintip;
$derevo = "";
$no_pages = 0;
$ver = mt_rand(10000, 99999); // получили случайное число


// Показываем страницы этой папки
	$no_pages=0; // счетчик
	foreach ($pages as $pid => $pag) {
	$cid = $cids[$pid];
$color=""; $nowork = "";
if ($active[$pid] == 0) { $color=" class=noact"; $nowork=" ОТКЛ. ";}
if ($active[$pid] == 2) { $color=" class=deact"; $nowork=" ПРОВЕРКА! ";}
if ($no_pages<10) $derevo .= "<li class='li_file li_mesto_$mesto'>$nowork<a class=no title=\"".$title[$pid]."\" href=#".$ver.$pid." onclick='men10($pid, \"$name\", \"$admintip\");'$color>".$pag."</a><div id='pid10".$pid."' style='display:inline; margin-left:5px;'></div></li>";
$no_pages++;
	}
//$derevo .= "<hr size=1 color=darkgreen>";
return $derevo;
}
#####################################################################################################################
function base_pages($name) {
    global $name, $tip, $admintip, $prefix, $db, $bgcolor1, $bgcolor2, $pagenum, $bgcolor3, $bgcolor4;
    include("ad-header.php");
    echo "<a name=1></a>";
    menu();
// Определяем все папки
$names = array();
$parents = array();
$sql = "SELECT cid, title, parent_id FROM ".$prefix."_".$tip."_categories where module='$name' and `tables`='pages' ORDER BY parent_id, title";
$result = $db->sql_query($sql);
$siz_papka = $db->sql_numrows($result);
while ($rows = $db->sql_fetchrow($result)) {
$с_cid = $rows['cid'];
$names[$с_cid] = $rows['title'];
$parents[$с_cid] = $rows['parent_id'];
}

// Определяем все страницы
$cids = array();
$pages = array();
$active = array();
$title = array();
$order = "date desc"; //else $order = "title, date desc";
$sql = "SELECT pid, cid, title, date, counter, active, comm, mainpage, description, keywords FROM ".$prefix."_".$tip." where module='$name' ORDER BY ".$order."";
$result = $db->sql_query($sql);
$siz = $db->sql_numrows($result);
while ($rows = $db->sql_fetchrow($result)) {
$pid = $rows['pid'];
$cids[$pid] = $rows['cid'];
//	if ($vid==0) {
  $dat = explode(" ",$rows['date']);
  $tim = explode(":",$dat[1]);
  $tim = $tim[0].":".$tim[1];
  $dat = explode("-",$dat[0]);
  $p_date = intval($dat[2])." ".findMonthName($dat[1]);
  $p_date_1 = $dat[2]." ".$dat[1]." ".$dat[0];
  
  $counter = intval($rows['counter']);
  $comm = intval($rows['comm']);
  $mainpage = intval($rows['mainpage']);
  $description = trim($rows['description']);
  $keywords = trim($rows['keywords']);
  
  $date_now = date("d m Y");
  $date_now2 = date("d m Y",time()-86400);
  $date_now3 = date("d m Y",time()-172800);
switch($p_date_1) {
case $date_now: $p_date = "Сегодня"; break;
case $date_now2: $p_date = "Вчера"; break;
case $date_now3: $p_date = "Позавчера"; break; }
	$p_date = $p_date." в ".$tim;
  	$title[$pid] = substr($rows['title'], 0, 40); // остается 40 символов
	if (strlen($title[$pid]) < strlen($rows['title'])) $title[$pid] .= "...";
	
	if ($keywords != "" and $description != "") $keydes = "<font color=darkblue>‡</font> "; 
	elseif ($keywords != "" or $description != "") $keydes = "<font color=blue>†</font> "; 
	else $keydes = "";
	
	if ($mainpage == 1) $keydes .= "<font color=green>•</font> "; 
	//€
	if (trim($title[$pid]) == "") $title[$pid] = "<span class=red>[НЕТ НАЗВАНИЯ СТРАНИЦЫ!]</span>";
	$pages[$pid] = "$keydes<span class=gray>".$p_date."</span> ".$title[$pid]." <span class=gray>$counter|$comm</span>";
	$title[$pid] = $rows['title'];
	//} else $pages[$pid] = $rows['title'];
$active[$pid] = $rows['active'];
}

global $add_cat;
// Генерируем дерево папок и страниц с помощью функции рекурсии
echo "<hr size=1 color=green>

<table width=100%><tr valign=top><td width=60%>

<div class=block_white2>
<div style='background: #b7ffba; padding: 5px; margin-bottom:10px;'>
<b>Справка:</b> Чтобы изменить страницу - нажмите по ней, чтобы создать - см. справа.<br>
Какую-то страницу уже не стоит показывать посетителям - просто выключите ее, нажав по <img src=images/admin/page_zamok.gif>
</div><b>Значения:</b> 
<nobr><img src=images/admin/page_show.gif> Открыть</nobr>
<nobr><img src=images/admin/page_editor.gif> Изменить</nobr>
<nobr><img src=images/admin/page_noeditor.gif> в HTML</nobr>
<nobr><img src=images/admin/page_zamok.gif> Вкл./Выкл.</nobr>
<nobr><img src=images/admin/page_delete.gif> Удалить</nobr>
<nobr><img src=images/admin/page_delete_moment.gif> быстро</nobr>
</div><br>";

if ($siz > 20) echo "<div class=block_white2><a href=/-".$name." target=_blank><img title='Открыть раздел на сайте' src=images/admin/time_papka.jpg align=left></a><br><b>10 последних страниц раздела</b><br><br>".base_pages_derevo4($names, $parents, 0, 0, $cids, $pages, $active, $title)."<br></div><br>";

$sql = "select id from ".$prefix."_mainpage where type='2' and name='".$name."'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$id_name = $row['id'];
     
echo "<table width=100% class=block_white2><tr valign=top><td>
<a href=/-".$name." target=_blank><img title='Открыть раздел на сайте' src=images/admin/main_papka.jpg align=left></a><br><b>Начало раздела</b> &nbsp; <a href=/sys.php?op=mainpage&id=".$id_name." title=\"Редактировать Главную страницу раздела в Редакторе\"><img src=images/admin/page_editor.gif></a>&nbsp; <a href=/sys.php?op=mainpage&id=".$id_name."&red=1 title=\"Редактировать Главную страницу раздела в HTML режиме\"><img src=images/admin/page_noeditor.gif></a>
<p>";

echo "<div style='background: #b7ffba; padding: 3px;'><span class=gray><b>Страница</b> Дата создания </span> Название страницы <span class=gray>посещения|комментарии</span></div>";

echo base_pages_derevo3($names, $parents, 0, 0, $cids, $pages, $active, $title);
//else echo base_pages_derevo($names, $parents, 0, 0, $cids, $pages, $active);

echo base_pages_derevo2($cids, $pages, $active, $title)."
</td></tr></table>
</td><td width=1%>
<div id=shows style=\"display:none;\"><a onclick=\"show('blocks'); show('shows');\" style='cursor:pointer;'><img align=right src=/spaw2/uploads/images/icons/cross.gif></a></div>
<div id=blocks>
<p align=right><a onclick=\"show('blocks'); show('shows')\" style='cursor:pointer;'><img align=bottom src=/spaw2/uploads/images/icons/minus.gif> <u>Скрыть всю колонку</u></a>
<br><br><p><img src=images/admin/add_page_editor.gif width=20 align=bottom> <b>СОЗДАТЬ СТРАНИЦУ</b> 
<div class=block>
<img src=images/admin/add_page_editor.gif width=20 align=bottom> <a href=/sys.php?op=".$admintip."_add_page&name=$name#1><b>в Редакторе</b></a> / 
<img src=images/admin/add_page_noeditor.gif width=20 align=bottom> <a href=/sys.php?op=".$admintip."_add_page&name=$name&red=1#1>без Редактора</a>
</div>
<br>
<p><img src=images/admin/add_papka.gif width=20 align=bottom> <b>СОЗДАТЬ ПАПКУ</b> 
<div class=block><form action=sys.php method=post style=\"display:inline;\">
Напишите название папки:<br>
<input type=text name=title size=30><input type=hidden name=op value=".$admintip."_add_category><input type=hidden name=name value=".$name."><br>Будет вложена в другую папку?<br>";
           $sql = "select cid, title, parent_id from ".$prefix."_".$tip."_categories where module='$name' and `tables`='pages' order by parent_id,cid";
           $result = $db->sql_query($sql);
           echo "<select name=parent_id><option value=0>нет (будет в Начале раздела)</option>";
           while ($row = $db->sql_fetchrow($result)) {
           $cid2 = $row['cid'];
           $title = $row['title'];
           $parentid = $row['parent_id'];
	   $title = getparent($name,$parentid,$title);
	   if ($add_cat==$cid2) $sel=" selected"; else $sel="";
	   echo "<option value=".$cid2.$sel.">".$title."</option>";
           }
echo "</select><br><br><input type=submit value=\"Создать\" style='width:200px; height:40px;'></form>
</div>
<br>

<p><img src=images/admin/info.gif width=20 align=bottom> <b>Дополнительная информация:</b>
<div class=block>
<b>Обозначения слева от названия страниц.</b> Заполнение полей \"Ключевые словосочетания и Описание для поисковых систем\": <br>
<font color=darkblue>‡</font> заполнены оба поля, <br>
<font color=blue>†</font> заполнено одно из полей.<br>
<font color=green>•</font> Вывод на Главную страницу<br>

<br>
<div style='background: #b7ffba; padding: 5px;'>
<div id=spravka_show>
<p><a onclick=\"show('spravka'); show('spravka_show');\" style='cursor:pointer;'><img src=/spaw2/uploads/images/icons/cross.gif align=bottom> <u><b>Раскрыть подробную справку</b></u></a>
</div>

<div id=spravka style='display:none;'>
<p><a onclick=\"show('spravka'); show('spravka_show');\" style='cursor:pointer;'><img src=/spaw2/uploads/images/icons/minus.gif align=bottom> <u><b>Скрыть справку</b></u></a>
<br><br>
<li><b>Нажатие по названию</b> папки или страници вызовет меню. При наведении на любой элемент меню вы увидите подсказку.
<br><br>
<li><b>В целях безопасности</b> доступно два типа удаления страниц и папок - с подтверждением (Вы уверены? Да/Нет) и без (быстрое).
<br><br>
<li><b>Если страница не дописана или устарела</b> (но может быть использована позже) - просто отключите ее, нажав по ее названию и выбрав из меню <img src=images/admin/page_zamok.gif align=top title=\"Включение/Выключение страницы\">.
<br><br>
<li><b>Длинные имена страниц урезаны...</b> Чтобы увидеть полное имя во всплывающей подсказке - просто наведите курсор мыши на название страницы.
<br><br>
<li><b>Страницы</b> можно создавать как в Начале раздела, так и в папках. Папки нужно создать предварительно (см. выше).
<br><br>
<li><b>Для вставки ссылки на страницу или раздел</b> достаточно написать в Редакторе (в Предисловии или Содержании) их название в фигурнах скобках, например: {Афиша 2010}. <b>Если же</b> ссылкой нужно сделать произвольный текст или изображение (баннер) - нужно либо открыть страницу через элемент меню <img align=top src=images/admin/page_show.gif title=\"Посмотреть (открыть эту страницу на сайте)\"> и скопировать адрес страницы (наверху в адресной строке вашего браузера). Затем выделить нужные слова или изображение и нажать на кнопку \"Создать ссылку\", далее в поле Адрес вставить адрес атрницы, нажав Ctrl+V, закончить создание ссылки, нажав ГОТОВО. 
</div>
</div>
</div>
<br>
<p><img src=images/admin/pages_teleport.gif width=20 align=bottom> <b>Телепортация страниц</b> 
<div class=block>";
if ($siz==0) {
echo "Страниц в папке не обнаружено. Телепортация невозможна.";
} else {
echo "<form action=sys.php method=post name=teleport style=\"display:inline;\">
<input type=hidden name=op value=".$admintip."_teleport><input type=hidden name=name value=".$name.">";
           $sql = "select cid, title, parent_id from ".$prefix."_".$tip."_categories where module='$name' and `tables`='pages' order by parent_id,cid";
           $result = $db->sql_query($sql);
           echo "<select name=from_razdel>";
           if ($siz_papka==0) { // Если папок еще нет!
           echo "<option value=copy_2>ВСЕ страницы раздела</option>";
           } else {
           echo "<option value=copy_0>ВСЕ страницы раздела (без папок)</option>
           <option value=copy_1>ВСЕ страницы раздела (с папками)</option>
           <option value=copy_2>ТОЛЬКО страницы из Начала раздела</option>
           <option value=copy_3>ВСЕ КРОМЕ Начала раздела (без папок)</option>
           <option value=copy_4>ВСЕ КРОМЕ Начала раздела (с папками)</option>
           <option value=copy_5>ТОЛЬКО ПАПКИ</option>";
           }
           while ($row = $db->sql_fetchrow($result)) {
           $cid2 = $row['cid'];
           $title = $row['title'];
           $parentid = $row['parent_id'];
       $title = getparent($name,$parentid,$title);
       echo "<option value=".$cid2.">из {".$title."}</option>";
           }
           
           //  onChange=\"if (this.value=='delete') show('papki'); else show('papki');\"
echo "</select><br>Действие: <select name=operation>
           <option value=teleport>перенести</option>
           <option value=copy>копировать</option>
           <option value=delete>удалить (осторожно!)</option>
           </select> <div id=papki><br> в раздел:";
           $sql = "select name, title from ".$prefix."_mainpage where type='2' and name != 'index' order by title";
           $result = $db->sql_query($sql);
           echo "<select name=to_razdel onChange=\"papkaValues(this.selectedIndex)\">
           <option value='$name'>Текущий</option>";
           $с_razdel = array();
           while ($row = $db->sql_fetchrow($result)) {
           $name2 = $row['name'];
           $title2 = $row['title'];
           $с_razdel[$name2] = $title2;
       echo "<option value=".$name2.">".$title2."</option>";
           }
echo "</select> <br> в папку:
<select name=papka>
<option value=\"0\">Начало раздела</option>
</select></div><br> ";

$с_cid = array();
$с_names = array();
$sql = "SELECT cid, module, title FROM ".$prefix."_".$tip."_categories where parent_id='0' and `tables`='pages' ORDER BY module, title";
$result = $db->sql_query($sql);
while ($rows = $db->sql_fetchrow($result)) {
$с_module = $rows['module'];
$с_names[$с_module] .= "     ".$rows['title'];
$с_cid[$с_module] .= "     ".$rows['cid'];
}
// перечисляем все разделы и их основные папки
foreach( $с_razdel as $key => $value ) {
    if (trim(str_replace("     ",",",trim($с_cid[$key])))!="") $text1 .= "\"0,".str_replace("     ",",",trim($с_cid[$key]))."\",\n";
    else $text1 .= "\"0\",\n";
    if (trim(str_replace("     ",",",trim($с_names[$key])))!="") $text2 .= "\"Начало раздела,".str_replace("     ",",",str_replace(",",".",str_replace("\"","'",trim($с_names[$key]))))."\",\n";
    else $text2 .= "\"Начало раздела\",\n";
}
echo "<script type=\"text/javascript\"><!--

var apapkaValues = new Array(
\"0\",
".$text1."\"0\"
);
var apapkaValues_name = new Array(
\"Начало раздела\",
".$text2."\"Начало раздела\"
);
function getpapkaValuesto_razdel(index){
    var spapkaValues = apapkaValues[index];
    return spapkaValues.split(\",\"); // преобразуем строку в массив
}
function getpapkaValuesto_razdel_name(index){
    var spapkaValues = apapkaValues_name[index];
    return spapkaValues.split(\",\"); // преобразуем строку в массив
}
function papkaValues(index){
    var aCurrpapkaValues = getpapkaValuesto_razdel(index);
    var aCurrpapkaValues_name = getpapkaValuesto_razdel_name(index);
    var nCurrpapkaValuesCnt = aCurrpapkaValues.length;
    var opapkaList = document.forms[\"teleport\"].elements[\"papka\"];
    var opapkaListOptionsCnt = opapkaList.options.length;
    opapkaList.length = 0; // удаляем все элементы из списка
    for (i = 0; i < nCurrpapkaValuesCnt; i++){
        // далее мы добавляем необходимые элементы в список
        if (document.createElement){
            var newpapkaListOption = document.createElement(\"OPTION\");
            newpapkaListOption.text = aCurrpapkaValues_name[i];
            newpapkaListOption.value = aCurrpapkaValues[i];
            // тут мы используем для добавления элемента либо метод IE, либо DOM, которые, alas, не совпадают по параметрам…
            (opapkaList.options.add) ? opapkaList.options.add(newpapkaListOption) : opapkaList.add(newpapkaListOption, null);
        }else{
            // для NN3.x-4.x
            opapkaList.options[i] = new Option(aCurrpapkaValues[i], aCurrpapkaValues[i], false, false);
        }
    }
}
papkaValues(document.forms[\"teleport\"].elements[\"to_razdel\"].selectedIndex);
//--></script>

<input type=submit value=\"Телепорт\" style='width:200px; height:40px;'><br>
Пока что при телепортации не сохраняются пути (вложенность страниц и папок).</form>";
}
echo "</div>

</div>
</td></tr></table>";

admin_footer(); //include("ad-footer.php");
}
#####################################################################################################################
#####################################################################################################################
#####################################################################################################################
function edit_base_pages_category($cid, $red=0) {
    global $module, $name, $tip, $admintip, $prefix, $db; //, $toolbars;
    include("ad-header.php");
    $cid = intval($cid);
    $red = intval($red);
    $sql = "SELECT * FROM ".$prefix."_".$tip."_categories WHERE cid='$cid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $name = $row['module'];
    $title = $row['title'];
    $module = $name;
    $desc = $row['description'];
    $sortirovka = $row['sort'];
    $parent_id = $row['parent_id'];

    menu();
echo "<br>";
    # cid module title description pic sort counter parent_id
echo "
<form action=\"sys.php\" method=\"post\">
<table width=100%><tr valign=top><td>
<input type=submit value=\"Сохранить изменения\" style='width:95%; height:45px; font-size: 20px;'>
<h2>Раздел:</h2>";
           $sql = "select name, title, color from ".$prefix."_mainpage where type='2' and `tables`='pages' and name != 'index' order by color desc, title";
           $result = $db->sql_query($sql);
           $numrows = $db->sql_numrows($result);
           echo "<select name=module id=to_razdel style='font-size:11px; width:100%;' size=1 onChange=\"izmenapapka(document.getElementById('to_razdel').value, '', '','','editdir');\">";
           while ($row = $db->sql_fetchrow($result)) {
           $name2 = $row['name'];
           $title2 = $row['title'];
           $color = $row['color'];
switch ($color) {
  case "1": // Частоупотребляемый зеленый
  $color = "b4f3b4"; break;
  case "2": // Редкоупотребляемый желтый
  $color = "f3f3a3";  break;
  case "3": // Закрытый или старый красный
  $color = "ffa4ac"; break;
  case "4": // Новый, в разработке
  $color = "b8f4f2"; break;
  default: 
  $color = "ffffff"; break;  // Стандартный белый
}
           if ($name == $name2) $sel = "selected"; else $sel = "";
	   echo "<option style='background:".$color.";' value=\"$name2\" ".$sel.">".$title2."</option>";
           }
           if ($numrows > 10) $size = 20*16; else $size=($numrows+5)*16;
echo "</select><br><div style='display:inline; float:right;'><div id=showa style='display:inline; float:right;'><a style='cursor:pointer;' onclick=\"show('hidea'); show('showa'); document.getElementById('to_papka').style.width=500; document.getElementById('to_papka').style.height=400;\">развернуть &rarr;</a></div><div id=hidea style='display:none;'><a style='cursor:pointer;' onclick=\"show('showa'); show('hidea'); document.getElementById('to_papka').style.width=248; document.getElementById('to_papka').style.height=".$size.";\">&larr; свернуть</a></div></div><h2>Папка:</h2>";
           $sql = "select * from ".$prefix."_".$tip."_categories where module='$name' and `tables`='pages' and cid != '$cid' order by parent_id,cid";
           $result = $db->sql_query($sql);
           $numrows = $db->sql_numrows($result);
           if ($numrows > 10) $size = 10*16; else $size=($numrows+2)*16;
           echo "<div id='izmenapapka'><select name=parent_id id='to_papka' size=4 style='font-size:11px; width:248px; height:".$size."px;'><option value=0 selected>... основная (корень)</option>";
           while ($row = $db->sql_fetchrow($result)) {
           $cid2 = $row['cid'];
           $title3 = $row['title'];
           $parentid = $row['parent_id'];
	   $title3 = getparent($name,$parentid,$title3);
           if ($parent_id == $cid2) $sel = "selected"; else $sel = "";
	   echo "<option value=".$cid2." ".$sel.">".$title3."</option>";
           }
echo "</select></div><br><br>";
//html_spravka();
$sql3 = "select `text` from `".$prefix."_mainpage` where `name`='$name' and `type`='2'";
$result3 = $db->sql_query($sql3);
$row3 = $db->sql_fetchrow($result3);
if (trim($row3['text'])!="") {
$main_file = explode("|",$row3['text']);
$main_options = $main_file[1];
parse_str($main_options);
}
if ($view == 4) $blok = "<b>Шаблон для анкет рейтинга (только для этой папки!)</b><br>
Пример написания шаблона:<br>
Ваше Имя *: |строка<br>
Договаривались ли Вы заранее с врачом: |выбор|да|нет<br>
Отзыв о Вашем враче: |текст<br>";
else $blok = "<h2>Содержание папки (текст над списком страниц папки):</h2>";

echo "</select>
</td><td>
<h2>Название папки:</h2>
<input type=\"text\" name=\"title\" value=\"$title\" size=\"60\"><br><br>
".$blok."";
if ($red==0) {
    $spaw = new SpawEditor("desc", $desc); 
    //$spaw->setStylesheet("/css_20"); // В дальнейшем подключить стили!!!
    $spaw->show();
} elseif ($red==2) {
    echo "<textarea cols=80 id=editor name=desc rows=10>".$desc."</textarea>
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
} elseif ($red==1) {
    echo "<textarea id=\"desc\" name=\"desc\" rows=\"15\" cols=\"80\">".$desc."</textarea>";
} elseif ($red==3) {
    echo "<script type=\"text/javascript\"> 
$(document).ready(function()
{  $('#desc').editor({ focus: true, toolbar: 'classic', css: ['/ed/js/editor/css/editor.css'] });  });
</script><textarea id=\"desc\" name=\"desc\" rows=\"15\" cols=\"100\">".$desc."</textarea>";
} elseif ($red==4) {
    global $red4_div_convert;
    echo "<script type=\"text/javascript\">
    function ButtonMore(obj, event, key){ obj.insertHtml('<!--more-->'); }
    function ButtonBlock(obj, event, key){ obj.insertHtml('[Название блока]'); }
    function ButtonLink(obj, event, key){ obj.insertHtml('{Название страницы или раздела}'); }
    $(document).ready(function() { 
      $('.redactor').redactor({ buttonsAdd: ['|', 'button_more', 'button_link', 'button_block'], buttonsCustom: {
        button_more: {title: 'Вставка ссылки на полное содержание (для предисловия)',callback: ButtonMore},
        button_link: {title: 'Вставка блока (например, галереи фотографий)',callback: ButtonBlock},
        button_block: {title: 'Вставка быстрой ссылки на страницу или раздел',callback: ButtonLink}
      }, mobile: false, ".$red4_div_convert." imageUpload: 'ed2/image_upload.php',fileUpload: 'ed2/file_upload.php' }); } );
    </script><textarea id=\"desc\" class='redactor' name=\"desc\" style='width: 100%; height: 300px;'>".$desc."</textarea>";
}

    echo "<br><div style='float:left; '><h2>Сортировка:&nbsp;</h2></div> <input type=\"text\" name=\"sortirovka\" value=\"$sortirovka\" size=\"3\"><br><br><span class=small>Если вы решили отсортировать папки по-своему - лучше всего использовать десятичную разницу между числами сортировки для разных папок, например: 10, 20, 30, 40... Это нужно для того, чтобы в случае создания новой папки вы не изменяли сортировку для всех предыдущих, а легко присвоили ей следующий номер за сортировкой, стоящей перед ней папки, например: 11, 21, 31, 41... или 15, 25, 35 - чтобы можно было вклинить новые папки между ними.</span>
<input type=\"hidden\" name=\"cid\" value=\"$cid\">
<input type=\"hidden\" name=\"op\" value=\"".$admintip."_save_category\">
</table></form>";
admin_footer(); //include("ad-footer.php");
}

function base_pages_save_category($cid, $module, $title, $desc, $sortirovka, $parent_id) {
  global $tip, $admintip, $prefix, $db;
  $title = mysql_real_escape_string($title);
  $desc = mysql_real_escape_string($desc);
  $db->sql_query("UPDATE ".$prefix."_".$tip."_categories SET module='$module', title='$title', description='$desc', sort='$sortirovka', parent_id='$parent_id', `tables`='pages' WHERE cid='$cid'");
  Header("Location: sys.php");
}

function delete_razdel_base_pages($name) { 
  global $name, $tip, $admintip, $prefix, $db; 
  $db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE `tables`='del' and name='$name'"); 
  $db->sql_query("DELETE FROM ".$prefix."_".$tip."_categories WHERE module='$name' and `tables`='del'"); 
  $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE module='$name' and `tables`='del'"); 
  Header("Location: sys.php");
}

function delete_category_base_pages($cid) { 
  global $name, $tip, $admintip, $prefix, $db; 
  $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE `tables`='del' and cid='$cid'"); 
  $db->sql_query("DELETE FROM ".$prefix."_".$tip."_categories WHERE `tables`='del' and cid='$cid'"); 
  Header("Location: sys.php");
}

function delete_page_base_pages($pid) { 
  global $name, $tip, $admintip, $prefix, $db; 
  $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE `tables`='pages' and pid='$pid'"); 
  Header("Location: sys.php");
}

function delete_all_pages($del="del") {
  global $tip, $prefix, $db; 
  if ($del != "backup") $del = "del"; // в дальнейшем можно расширить
  $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE `tables`='$del'") or die('1');
  $db->sql_query("DELETE FROM ".$prefix."_".$tip."_categories WHERE `tables`='$del'") or die('2');
  $db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE `tables`='$del'") or die('3'); 
  Header("Location: sys.php");
}

function base_pages_del_category($cid, $ok, $name) {
  global $name, $tip, $admintip, $prefix, $db;
  $cid = intval($cid);
  $n = delete_category_base_pages($cid);
  if ($n != 0){ 
    $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE cid='$cid'");
    $db->sql_query("DELETE FROM ".$prefix."_".$tip."_categories WHERE cid='$cid' and `tables`='pages'");
    // ДОБАВИТЬ УДАЛЕНИЕ ВСЕХ КОММЕНТАРИЕВ И ГОЛОСОВАНИЙ!!!
  } 
  Header("Location: sys.php");
}
# СТРАНИЦЫ
function base_pages_add_page($name, $razdel, $red=0, $new=0, $pid=0) {
  global $tip, $admintip, $prefix, $db, $red, $new, $pid, $redaktor, $toolbars;
  include("ad-header.php");
  echo "<a name=1></a>";
  $id = intval ($id);
  if ( $pid > 0 ) {
    // узнаем имя страницы
    $sql = "SELECT `title` from ".$prefix."_pages where pid='".$pid."'"; // список всех категорий
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $new_title = $row['title'];
    echo "<div style='text-align:center;'><span class='green'>Страница «<a target='_blank' class='green' href=/-".$name."_page_".$pid.">".$new_title."</a>» добавлена.</span> <a href=/sys.php?op=base_pages_edit_page&name=".$name."&pid=".$pid."><img class='icon2 i35' src='/images/1.gif'>Редактировать</a></div>";
  }
  menu();
  $sql = "select id, title, shablon from ".$prefix."_mainpage where name='$name' and `tables`='pages' and type='2'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $id = $row['id'];
  $title = $row['title'];
  $shablon = trim($row['shablon']);
  $shablon2 = "";
  //if ($red != 3 and $red != 4) {
      if ($shablon=="") { 
        if ($red != 3 and $red != 4) { $shablon1 = "<p>"; $shablon2 = "<p>"; }
      } else { 
        $shablon = explode("[следующий]",$shablon);
        $shablon1 = $shablon[0];
        $shablon2 = $shablon[1];
      }
      if ($shablon2=="") $shablon2 = "<p>&nbsp;</p>";
  //}
  if (!isset($shablon1)) $shablon1="";
  if (!isset($shablon2)) $shablon2="";
  //  onclick='this.disabled=true;'
  // <input type=hidden name=module value=".$name.">
  echo "<form method=\"POST\" action=\"sys.php\" enctype=\"multipart/form-data\">
  <table width=99%><tr valign=top><td width=250 bgcolor=#eeeeee>
  <input type=submit value=\"Добавить страницу\" class=radius style='width:250px; height:55px; font-size: 20px;' onClick=\" if (document.getElementById('to_razdel').value=='') { alert('Выберите раздел для страницы (слева сверху)!'); return false; } else { submit(); } \"><br><br>";
             $sql = "select name, title, color from ".$prefix."_mainpage where `tables`='pages' and type='2' and name != 'index' order by color desc, title";
             $result = $db->sql_query($sql);
             echo "<h1 style='color:darkgreen;'>Выберите раздел:</h1>
             <select name=module id=to_razdel style='font-size:11px; width:100%;' size=10 onChange=\"izmenapapka(document.getElementById('to_razdel').value,'','','','addpage');\">";
             while ($row = $db->sql_fetchrow($result)) {
             $name2 = $row['name'];
             $title2 = $row['title'];
             $color = $row['color'];
              switch ($color) {
                case "1": // Частоупотребляемый зеленый
                $color = "b4f3b4"; break;
                case "2": // Редкоупотребляемый желтый
                $color = "f3f3a3";  break;
                case "3": // Закрытый или старый красный
                $color = "ffa4ac"; break;
                case "4": // Новый, в разработке
                $color = "b8f4f2"; break;
                default: 
                $color = "ffffff"; break;  // Стандартный белый
              }
             if ($name == $name2) $sel = "selected"; else $sel = "";
         echo "<option style='background:".$color.";' value=\"$name2\" ".$sel.">".$title2."</option>";
             }
             $sql = "select * from ".$prefix."_".$tip."_categories where module='$name' and `tables`='pages' order by parent_id,title";
             $result = $db->sql_query($sql);
             $numrows = $db->sql_numrows($result);
             if ($numrows > 10) $size = 10*16; else $size=($numrows+2)*16;
  echo "</select><br>
  <div style='display:inline; float:right;'><div id=showa style='display:inline; float:right;'><a style='cursor:pointer;' onclick=\"show('hidea'); show('showa'); $('#to_papka').width(500); $('#to_papka').height(400);\">развернуть &rarr;</a></div><div id=hidea style='display:none;'><a style='cursor:pointer;' onclick=\"show('showa'); show('hidea'); $('#to_papka').width(248); $('#to_papka').height(".$size.");\">&larr; свернуть</a></div></div><h2>Папка:</h2>";

             echo "<div id='izmenapapka'><select name=cid id='to_papka' size=4 style='font-size:11px; width:248px; height:".$size."px;'><option value=0 selected>... основная (корень)</option>";
             while ($row = $db->sql_fetchrow($result)) {
             $cid2 = $row['cid'];
             $title = $row['title'];
             $parentid = $row['parent_id'];
         if ($parentid != 0) $title = "&bull; ".getparent($name,$parentid,$title);
         $sel = "";
         if (isset($cid)) if ($cid == $cid2) $sel = "selected";
         if ($parentid == 0) {
             // занести в переменную
             $first_opt[$cid2] = "<option value=".$cid2." ".$sel." style='background:#fdf;'>".$title."</option>"; 
         }
         if ($parentid != 0) {
             // вывести и очистить переменную
             echo $first_opt[$parentid];
             $first_opt[$parentid] = "";
             echo "<option value=$cid2 $sel>$title</option>";
         }
             }
        if (count($first_opt)>0) 
        foreach( $first_opt as $key => $value ) {
          if ($first_opt[$key] != "") echo $first_opt[$key];
        }
             
  echo "</select></div>";

  global $siteurl;
  //readfile("http://".$siteurl."/help_add_page.txt");

  echo "<br><br>
  <label><input type=checkbox name=active value=1 checked> Включить страницу</label> <a onclick=\"show('help3')\" class=help>?</a><br><div id='help3' style='display:none;'><br>Если поставить эту галочку — ссылка на эту страницу будет видна в автоматическом списке страниц данного раздела, а также в блоках, которые выводят страницы данного раздела (если они созданы). Если галочку убрать — на эту страницу все равно можно поставить ссылку из любого места на сайте или с другого сайта и страница будет видна тем, кто перейдет по этой вручную созданной ссылке. Если вы хотите, чтобы в общем списке страниц данная страница не отображалась, а раскрывала более подробную информацию при переходе с другой страницы — отключите ее и сделайте на нее ссылку вручную.<br></div>

  <br><a onclick=\"show('key'); show('key_hide')\" class=help id='key_hide'>Заполнить ключевые слова...</a>
  <div id='key' style='display:none;'>
    <h3>Ключевые слова для поисковых систем:</h3><textarea name=keywords2 class=big rows=2 cols=10 style='width:97%;'></textarea>
  <br><div class='help small'>?</div> <span class=small>Максимум 1000 символов. Разделять словосочетания желательно запятой. Если пусто - используются <b>Теги</b> (если и они пустые - используются Ключевые словосочетания из <a href=/sys.php?op=Configure target=_blank>Настроек портала</a>).</span><br><br>
    <h3>Описание для поисковых систем:</h3><textarea name=description2 class=big rows=2 cols=10 style='width:97%;'></textarea>
  <br><div class='help small'>?</div> <span class=small>Максимум 200 символов. Если пусто - используется <b>Название</b> страницы.</span><br><br>
    <h3>Тэги (слова для похожих по тематике страниц):</h3> <textarea name=search class=big rows=2 cols=10 style='width:97%;'></textarea>
  <br><div class='help small'>?</div> <span class=small>Разделять пробелами, а слова в словосочетаниях символом + <br>
  Писать только существительные! НИКАКИХ ПРЕДЛОГОВ! Максимум неограничен. Разделять слова необходимо пробелом. Разделять слова в словосочетаниях символом +, например: игра+разума игротека game. Писать желательно в единственном числе и именительном падеже. Можно создать Блок \"Облако тегов\". Теги также могут выводиться на страницах (в настройках Раздела).</span><br><br>
  </div><br>

  <br><div id='dop2'><a onclick=\"show('dop'); show('dop2');\" class=help>Дополнительно...</a><br></div><div id='dop' style='display:none;'>

  <br><label><input type=checkbox name=rss value=1 checked> Добавить в RSS</label>  <a onclick=\"show('help2')\" class=help>?</a><br><div id='help2' style='display:none;'><br>Технология RSS похожа на e-mail подписку на новости — в RSS-программу, сайт RSS-читалки или встроенную систему чтения RSS в браузере добавляется ссылка на данный сайт, после чего название и предисловие всех новых страниц, отмеченных данной галочкой, будут видны подписавшемуся человеку и он сможет быстро ознакомиться с их заголовками, не заходя на сайт. Если что-то ему понравится — он откроет сайт и прочитает подробности. RSS используется для постепенного увеличения количества посетителей сайта путем их возвращения на сайт за интересной информацией. <a href=http://yandex.ru/yandsearch?text=Что+такое+RSS%3F target=_blank>Подробнее о RSS?</a><br><br></div>
  <br><label><input type=checkbox name=mainpage value=1 unchecked> На главную страницу</label> <a onclick=\"show('help1')\" class=help>?</a><br><div id='help1' style='display:none;'><br>Если отметить эту галочку, данная страница будет отображаться в блоке, который настроен на отображение только помеченных этой галочкой страниц, или не будет отображаться в блоке, который настроен на показ всех неотмеченных галочкой страниц.<br></div><br>
  Очередность: <input type=text name=sor value='0' size=3 style='text-align:center;' onmouseover=\"this.style.background=&#39;lightgreen&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\"><a onclick=\"show('help8')\" class=help>?</a><br><div id='help8' style='display:none;'><br>Настраивается в настройках раздела. Может быть равна цифре. Применяется для ручной сортировки страниц. Лучше всего делать кратной 10, например 20, 30, 40 и т.д. для того, чтобы было удобно вставлять страницы между двумя другими. Если очередность у двух страниц совпадает, сортировка происходит по дате.<br></div><br>";

  $data1 = date2normal_view(date("Y-m-d", time()));
  $data2 = date("H", time());
  $data3 = date("i", time());
  $data4 = date("s", time());
  echo "<p>Дата:
  <script> 
  $(function() { $.datepicker.setDefaults( $.datepicker.regional[ \"ru\" ] ); $( \"#f_date_c999\" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: \"d MM yy\", showAnim: 'slide' }); });
  </script>
  <INPUT type=text name=data1 id=\"f_date_c999\" value=\"".$data1."\" onchange=\"document.getElementById('add999').value=document.getElementById('f_date_c999').value+'|'+document.getElementById('f_date_c2999').value\" readonly=1 size=18 onmouseover=\"this.style.background=&#39;lightgreen&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\"> <a onclick=\"show('help0')\" class=help>?</a><br>
  Время: ";
  echo "<select name=data2 style='font-size:12px;'>";
  for ($x=0; $x < 24; $x++) {
  if ($x<10) $xx = "0".$x; else $xx = $x;
             $sel = ""; if ($xx == $data2) $sel = " selected";
  	   echo "<option value=".$xx."$sel> $xx </option>";
             }
  echo "</select>ч";

  echo "<select name=data3 style='font-size:12px;'>

  <option value=".$data3."$sel> $data3 </option>
  <option value='00'> 00 </option>
  <option value='10'> 10 </option>
  <option value='15'> 15 </option>
  <option value='20'> 20 </option>
  <option value='30'> 30 </option>
  <option value='40'> 40 </option>
  <option value='45'> 45 </option>
  <option value='50'> 50 </option>
  <option value='55'> 55 </option>
  ";
  /* 
  for ($x=0; $x < 60; $x++) {
  if ($x<10) $xx = "0".$x; else $xx = $x;
             $sel = ""; if ($xx == $data3) $sel = " selected";
  	   echo "<option value=".$xx."$sel> $xx </option>";
             }
  */
  echo "</select>м";

  echo "<input type=text name=data4 value=\"".$data4."\" style='font-size:12px;' size=1 onclick=\"this.value='00'\" onmouseover=\"this.style.background=&#39;lightgreen&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\">с

  <div id='help0' style='display:none;'><br>Для выбора даты из календаря нажмите по дате. Для обнуления секунд кликните по ним. Минуты представлены текущим вариантом или выбором из основного интервала для ускорения работы.<br></div>
  <br><br>";

  //echo "<a onclick=\"show('vstavka')\" class=punkt>Проверка орфографии</a><div id='vstavka' style='display:none;'><iframe src=/orfo.php width=100% height=300 style='border:0;'></iframe></div><br><br>

  /*
  echo "Горячие клавиши:<br>
  Ctrl+C&nbsp;-&nbsp;Копировать<br>
  Ctrl+V&nbsp;-&nbsp;Вставить<br>
  Ctrl+X&nbsp;-&nbsp;Вырезать<br>
  Ctrl+A&nbsp;-&nbsp;Выделить&nbsp;всё<br>
  Ctrl+Z&nbsp;-&nbsp;Отменить
  <br><br>
  Как поставить в предисловии ссылку на содержание:<br>
  <b>написать:</b> -ссылка-<br>
  <b>итог:</b> <u>Читать дальше</u> &rarr;<br><br>
  <b>написать:</b><br>ссылка-Далее:-ссылка<br>
  <b>итог:</b> <u>Далее:</u><br>
  */
  echo "</div>";

  echo "</td><td>
  <h2 class=radius_top style='background:#eeeeee;'>Название страницы (заголовок)<textarea class=big name=title rows=1 cols=10 style='font-size:16pt; width:100%;'></textarea></h2>";

  // <div style='float:right; width:50%;'><label><input type=checkbox name=open_text_mysor value=1 unchecked>Удалить мусор</label></div>
  //if ($red!=3) 
  echo "<br><h2 class=radius_top style='background:#eeeeee;'>Предисловие (начальный текст)";
  if ($red==0) {
      $spaw = new SpawEditor("open_text", $shablon1); 
      //$spaw->setStylesheet("/css_20"); // В дальнейшем подключить стили!!!
      $spaw->setDimensions("100%", "200");
      $spaw->show();
  } elseif ($red==2) {
      echo "<textarea cols=80 id=editor name=open_text rows=10>".$shablon1."</textarea>
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
  // var ckeditor = CKEDITOR.replace('editor');
  // AjexFileManager.init({ returnTo: 'ckeditor', editor: ckeditor, skin: 'light' });
  } elseif ($red==1) {
      echo "<textarea id=\"open_text\" name=open_text rows=7 cols=40 style='width:100%;'>".$shablon1."</textarea>";
  } elseif ($red==3) {
  // (document).ready
      echo "<script type=\"text/javascript\"> 
  $(function()
  {  $('#open_text').editor({ focus: true, toolbar: 'classic', css: ['/ed/js/editor/css/editor.css'], upload: 'upload.php' }); 
  $('#main_text').editor({ css: ['/ed/js/editor/css/editor.css'], toolbar: 'classic', upload: 'upload.php' });  });
  </script>
  <textarea id=\"open_text\" name=open_text rows=7 cols=40 style='width:100%;'>".$shablon1."</textarea>";
  } elseif ($red==4) {
    global $red4_div_convert;
    echo "<script type=\"text/javascript\">
    function ButtonMore(obj, event, key){ obj.insertHtml('<!--more-->'); }
    function ButtonBlock(obj, event, key){ obj.insertHtml('[Название блока]'); }
    function ButtonLink(obj, event, key){ obj.insertHtml('{Название страницы или раздела}'); }
    $(document).ready(function() { 
      $('.redactor').redactor({ buttonsAdd: ['|', 'button_more', 'button_link', 'button_block'], buttonsCustom: {
        button_more: {title: 'Вставка ссылки на полное содержание (для предисловия)',callback: ButtonMore},
        button_link: {title: 'Вставка блока (например, галереи фотографий)',callback: ButtonBlock},
        button_block: {title: 'Вставка быстрой ссылки на страницу или раздел',callback: ButtonLink}
      }, mobile: false, ".$red4_div_convert." imageUpload: 'ed2/image_upload.php',fileUpload: 'ed2/file_upload.php' }); } );
    </script>
    <textarea id='open_text' class='redactor' name=open_text rows=7 cols=40 style='width:100%;'>".$shablon1."</textarea>";
  }

  // <div style='float:right; width:50%;'><label><input type=checkbox name=main_text_mysor value=1 unchecked>Удалить мусор</label></div>
  echo "</h2><br><h2 class=radius_top style='background:#eeeeee;'>Содержание (основной текст)";
  if ($red==0) {
      $spaw = new SpawEditor("main_text", $shablon2); 
      //$spaw->setStylesheet("/css_20"); // В дальнейшем подключить стили!!!
      $spaw->setDimensions("100%", "400"); 
      $spaw->show();
  } elseif ($red==2) {
      echo "<textarea cols=80 id=edit name=main_text rows=15>".$shablon2."</textarea>
  <script type=\"text/javascript\">
  CKEDITOR.replace( 'edit', {
   filebrowserBrowseUrl : '/ckfinder/ckfinder.html',
   filebrowserImageBrowseUrl : '/ckfinder/ckfinder.html?Type=Images',
   filebrowserFlashBrowseUrl : '/ckfinder/ckfinder.html?Type=Flash',
   filebrowserUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
   filebrowserImageUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
   filebrowserFlashUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
  });
  </script>";
  } else {
      echo "<textarea id='main_text' class='redactor' name=main_text rows=15 cols=40 style='width:100%;'>".$shablon2."</textarea>";
  }
  echo "</h2>";
  $sql = "select text from ".$prefix."_mainpage where name='$name' and type='2'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $tex = $row['text'];

  // это галерея?
  if (strpos($tex,"view=5")) echo "<b>Фото (для фотогалереи):</b> <input type=file name=foto size=40><br>
  <b>или ссылка:</b> <input type=text name=link_foto value=\"/spaw2/uploads/images/$tip/\" size=40><br>Ссылку на другие сайты начинать с http://<br>";
  else echo "<input type=hidden name=foto value=''>";


  // это магазин?
  if (strpos($tex,"view=3")) echo "<b>Стоимость:</b> <input type=text name=price size=3 value='0'> руб.<br>";
  else echo "<input type=hidden name=price value=''>";


  // Подсоединие списков ////////////////////////////////
  // Ищем все списки по разделу
  $sql = "select id, title, name, text from ".$prefix."_mainpage where (useit='$id' or useit='0') and type='4' order by title";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
  $s_id = $row['id'];
  $s_title = $row['title'];
  $s_name = $row['name'];
  $options = explode("|", $row['text']); $options = $options[1];
  $type=0; $shablon=""; 
  parse_str($options); // раскладка всех настроек списка
  //if ($type!=1 and $type!=2) { $type=0; $type_name="список"; } else { $type_name="текст"; }
  switch($type) {
  ///////////////////
  case "4": // строка
    echo "<br><br><b>$s_title:</b><br><INPUT name=\"add[$s_name]\" type=text value='".$shablon."' style=\"width:98%;\">";
  break;

  ///////////////////
  case "3": // период времени
    echo "<br><br><b>".$s_title.":</b> (выберите даты из меню, кликнув по значкам)<br>
    <TABLE cellspacing=0 cellpadding=0 style=\"border-collapse: collapse\"><TBODY><TR> 
    <TD><INPUT type=text name=\"text[".$s_name."]\" id=\"f_date_c[".$s_name."]\" value=\"\" onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD>
    <TD><IMG src=/images/calendar.gif id=\"f_trigger_c[".$s_name."]\" title=\"Выбор даты\" onmouseover=\"this.style.background=&#39;red&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\"></TD>
    <TD width=20 align=center> - </TD>
    <TD><INPUT type=text name=\"text[".$s_name."]\" id=\"f_date_c2[".$s_name."]\" value=\"\" onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD> 
    <TD><IMG src=/images/calendar.gif id=\"f_trigger_c2[".$s_name."]\" title=\"Выбор даты\" onmouseover=\"this.style.background=&#39;red&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\"></TD>
    </TR></TBODY></TABLE>
    <SCRIPT type=\"text/javascript\"> 
        Calendar.setup({
            inputField     :    \"f_date_c[".$s_name."]\",     // id of the input field
            ifFormat       :    \"%e %B %Y\",      // format of the input field
            button         :    \"f_trigger_c[".$s_name."]\",  // trigger for the calendar (button ID)
            align          :    \"Tl\",           // alignment (defaults to \"Bl\")
            singleClick    :    true
        });
    </SCRIPT>
    <SCRIPT type=\"text/javascript\"> 
        Calendar.setup({
            inputField     :    \"f_date_c2[".$s_name."]\",     // id of the input field
            ifFormat       :    \"%e %B %Y\",      // format of the input field
            button         :    \"f_trigger_c2[".$s_name."]\",  // trigger for the calendar (button ID)
            align          :    \"Tl\",           // alignment (defaults to \"Bl\")
            singleClick    :    true
        });
    </SCRIPT>
    <input type=hidden name=\"add[".$s_name."]\" id=\"add[".$s_name."]\" value=\"дата\">"; //
  break;
  ///////////////////

  case "2": // файл
    // file=pic&papka=/spaw/uploads/images/&mesto=verh&resizepic=x&file=&picsize=600&minipic=1&resizeminipic=x&minipicsize=100

    switch($fil) {
      case "pic": $type_fil = "картинка"; break;
      case "doc": $type_fil = "документ/архив"; break;
      case "flash": $type_fil = "flash-анимация"; break;
      case "avi": $type_fil = "видео-ролик"; break;
    }
    switch($mesto) {
      case "verh": $type_mesto = "сверху"; break;
      case "niz": $type_mesto = "снизу"; break;
    }
    $type_mini="";
    if ($minipic==1) $type_mini = "Также будет создана миниатюра.";

    echo "<br><br><b>$s_title:</b><br><input type=file name=\"add[$s_name]\" size=30> 
    <b>или ссылка:</b> <input type=text name=\"add[$s_name]_link\" value=\"$papka\" size=30><br>
    Файл ($type_fil) сохранится в $papka, на странице будет $type_mesto. $type_mini";
  break;
  ///////////////////

  case "1": // текст
    echo "<br><br><b>$s_title:</b><br><textarea name=\"add[$s_name]\" rows=\"4\" cols=\"60\" style=\"width:98%;\">".$shablon."</textarea>";
  break;
  ///////////////////
  case "0": // список слов
  echo "<br><br><b>$s_title:</b><br>";
             $sql2 = "select * from ".$prefix."_spiski where type='$s_name' order by parent,id";
             $result2 = $db->sql_query($sql2);
             echo "<select size=10 multiple=multiple name=\"add[$s_name][]\" style='font-size:11px;'><option value=0> не выбрано </option>";
             while ($row2 = $db->sql_fetchrow($result2)) {
               $s_id2 = $row2['id'];
               $s_title2 = $row2['name'];
               $s_opis = $row2['opis'];
               $s_parent = $row2['parent'];
    	         $s_title2 = getparent_spiski($s_name,$s_parent,$s_title2);
               $sel = ""; 
               if ($razdel == $s_id2) $sel = " selected";
    	         echo "<option value=".$s_id2.$sel."> $s_title2 ($s_opis)</option>";
             }
  echo "</select>";
  break;
  ///////////////////
  }
  }

  echo "<br>
  <input type=hidden name=op value=".$admintip."_save_page>
  </td></tr>
  <tr valign=bottom><td align=center bgcolor=#eeeeee>

  </form>
  </td></tr></table>";

  admin_footer(); //include("ad-footer.php");
}
#####################################################################################################################
function base_pages_save_page($cid, $module, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $price, $add, $data1, $data2, $data3, $data4, $keywords2, $description2, $sor, $open_text_mysor, $main_text_mysor) {
  global $red, $tip, $admintip, $prefix, $db, $admin_file, $now;
  //include("ad-header.php");
  /*
  $title = slash($title);
  $open_text = slash($open_text);
  $main_text = slash($main_text);
  $keywords2 = slash($keywords2);
  $description2 = slash($description2);
  $search = slash($search);
  */

  /*
  if ($red == 3) {
  $x = str_replace("\n","<br>",implode('<br>',$main_text));
  echo "$x";
  exit;
  }
  */

  ##----------------------------------------------------##
  // это галерея?
  $sql = "select text from ".$prefix."_mainpage where name='$module' and type='2'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $tex = $row['text'];
  if (strpos($tex,"media=1")) {
  $ImgDir="spaw2/uploads/images/$tip";
  if (trim($link_foto)=="/spaw2/uploads/images/$tip/" or trim($link_foto)=="") {
  // Обработка имени файла: транслит и удаление пробелов
  $pic_name2 = date("Y-m-d_H-i-s_", time()).str_replace(" ","",translit($_FILES["foto"]["name"]));
  	if (Copy($_FILES["foto"]["tmp_name"],"$ImgDir/".basename($pic_name2))) {
  	unlink($_FILES["foto"]["tmp_name"]);
  	chmod("$ImgDir/".basename($pic_name2),0644);
  	$foto="/$ImgDir/".basename($pic_name2);
  	} else echo "ОШИБКА при копировании файла";
  } else $foto=trim($link_foto);
  } else $foto="";
  ##----------------------------------------------------##
  // это магазин?
  //if (strpos($tex,"shop=")) 
    $price=intval($price);
  ##----------------------------------------------------##
      $search = str_replace(", "," ",$search);
      $search = str_replace(","," ",$search);
      $search = str_replace(". "," ",$search);
      $search = str_replace("."," ",$search);
      $search = str_replace("  "," ",$search);
      $search = " ".trim($search)." "; //strtolow(
  # pid module cid title open_text main_text date counter active golos comm foto search mainpage

  if ($open_text == " <br><br>") $open_text = "";
  if ($main_text == " <br><br>") $main_text = "";

  // mysql_escape_string
  /*
  if (get_magic_quotes_gpc($title)) $title = stripslashes($title);
  $title = filter($title, "nohtml");
  if (get_magic_quotes_gpc($open_text)) $open_text = stripslashes($open_text);
  $open_text = filter($open_text, "");
  if (get_magic_quotes_gpc($main_text)) $main_text = stripslashes($main_text);
  $main_text = filter($main_text, "");
  */
  $sor = intval($sor);
  $rss = intval($rss);

  $open_text = mysql_real_escape_string(form($module, $open_text, "open"));
  $main_text = mysql_real_escape_string(form($module, $main_text, "main"));
  $title = mysql_real_escape_string(form($module, $title, "title"));

  $keywords2 = trim(str_replace("  "," ",str_replace("   "," ",str_replace(" ,",", ",$keywords2))));
  $description2 = trim($description2);

  $data = date2normal_view($data1, 1)." $data2:$data3:$data4";
  $data2 = $now;
  //  (pid, module, cid, title, open_text, main_text, date, counter, active, golos, comm, foto, search, mainpage, rss, price, description, keywords, tables, copy)
  $sql = "INSERT INTO ".$prefix."_".$tip." VALUES (NULL, '$module', '$cid', '$title', '$open_text', '$main_text', '$data', '$data2', '0', '$active', '0', '0', '$foto', '$search', '$mainpage', '$rss', '$price', '$description2', '$keywords2', 'pages', '0','$sor');";
  $db->sql_query($sql) or die ("Не удалось сохранить страницу. Попробуйте нажать в Редакторе на кнопку Чистка HTML в Редакторе. Если всё равно появится эта ошибка - сообщите разработчику нижеследующее:".$sql);

  // Узнаем получившийся номер страницы ID
  $sql = "select pid from ".$prefix."_".$tip." where title='$title' and date='$data'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $page_id = $row['pid'];

  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // РАБОТА СО СПИСКАМИ
  if (!isset($add) or $add == "") $add = array();
  foreach ($add as $name => $elements) {
    // Получение информации о каждом списке
    if ($name != "") {
    $sql = "select * from ".$prefix."_mainpage where name='$name' and type='4'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $s_id = $row['id'];
    $options = explode("|", $row['text']); $options = $options[1];
    $type=0; $shablon=""; 
    parse_str($options); // раскладка всех настроек списка
    //if ($type!=1) { $type=0; $type_name="список"; } else { $type_name="текст"; }

  switch($type) {
  ////////////////////////////////////////////////////////////////////////////
  case "4": // строка
          // Проверяем наличие подобного текста
          /*
          $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='$name' and name='$elements'";
          $result = $db->sql_query($sql);
          $numrows = $db->sql_numrows($result);
          if ($numrows == 1) { // если элемент найден
              $row = $db->sql_fetchrow($result);
              $s_pages = $row['pages'];
              $s_name = $row['name'];
                  if (strpos($agent," $page_id ") < 1 and $s_name == $elements) {
                      $s_pages .= " $page_id ";
                      $s_pages = str_replace("  "," ",$s_pages);
                      $db->sql_query("UPDATE ".$prefix."_spiski SET pages='$s_pages' WHERE type='$name' and name='$elements'") or die ('Ошибка: Не удалось обновить список. 1');
                      echo "up";
                  } else {
                      $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 2');
                      echo "in1";
                  }
          } else { // если элемент новый
          */ 
          // (id, type, name, opis, sort, pages, parent) 
              $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 3');
              //echo "in2";
          //}
  break;
  ////////////////////////////////////////////////////////////////////////////
  case "3": // период времени
          // создаем диапазон дат и все их проверяем
          $elements = explode("|",$elements);
          $dat1 = date2normal_view($elements[0], 1);
          $dat2 = date2normal_view($elements[1], 1);
          $period = period($dat1, $dat2);
      
          // и все даты проверяем на наличие в БД
          $upd = array();
          $noupd = array();
      
          $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='$name' order by name";
          $result = $db->sql_query($sql);
      
          while ($row = $db->sql_fetchrow($result)) {
          $nam = $row['name']; // дата
          $pag = trim($row['pages']); // страницы
          if (in_array($nam, $period)!=FALSE) { 
          $noupd[] = $nam; // для INSERT
          if (strstr($pag,$page_id)==FALSE) $upd[] = $nam; // для UPDATE
          }
          }
      
          $insert = array();
          $update = array();
          foreach ($upd as $up) {
          //if (!in_array($up, $noupd)) 
          $update[] = "name='$up'";
          }
          foreach ($period as $per) {
          if (!in_array($per, $noupd)) $insert[] = "(NULL, '$name', '$per', '', '0', ' $page_id ', '0')";
          }
      
          $insert = implode(", ",$insert);
          $update = implode(" or ",$update);
      
          $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='$name' and (".$update.") order by name";
          $result = $db->sql_query($sql);
          while ($row = $db->sql_fetchrow($result)) {
          $na = $row['name']; // дата
          $pa = $row['pages']; // страницы
              if (trim($update) != "") {
              $db->sql_query("UPDATE ".$prefix."_spiski SET pages = ' $pa $page_id ' WHERE type='$name' and name='$na'") or die ("Ошибка: Не удалось обновить списки. 4 $page_id $name");
              //print ("UPDATE ".$prefix."_spiski SET pages = ' $pa $page_id ' WHERE type='$name' and name='$na'<br>");
              }
          }
      
              if (trim($insert) != "") {
              $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES ".$insert.";") or die ('Ошибка: Не удалось сохранить списки. 5');
              }

  break;
  ////////////////////////////////////////////////////////////////////////////
  case "2": // файл НЕОКОНЧЕНО!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
          // Смотрим настройки - тип файла и что с ним делать
      
          // Закачиваем файл
      
          // Транслит файла и смена имени на тип и дату
      
          // Изменение размеров
      
          // Записываем ссылку на него в определенное поле

  break;
  ////////////////////////////////////////////////////////////////////////////
  case "1": // текст
          // Проверяем наличие подобного текста
          /*
          $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='$name' and name='$elements'";
          $result = $db->sql_query($sql);
          $numrows = $db->sql_numrows($result);
          if ($numrows > 0) { // если элемент найден
              $row = $db->sql_fetchrow($result);
              $s_pages = $row['pages'];
              $s_name = $row['name'];
                  if (strpos($agent," $page_id ") < 1 and $s_name == $elements) {
                      $s_pages .= " $page_id ";
                      $s_pages = str_replace("  "," ",$s_pages);
                      $db->sql_query("UPDATE ".$prefix."_spiski SET pages='$s_pages' WHERE type='$name' and name='$elements'") or die ('Ошибка: Не удалось обновить список. 6');
                      echo "up";
                  } else {
                      $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 7');
                      echo "in1";
                  }
          } else { // если элемент новый
          */
          //
              $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 8');
              //echo "in2";
          //}
  break;
  ////////////////////////////////////////////////////////////////////////////
  case "0": // список
          // Проверяем сколько элементов в списке
          $num = count($elements);
          for ($x=0; $x < $num; $x++) { // посчитали сколько номеров списка
              if ($elements[$x] != 0) {
              // узнаем какие страницы уже есть у этого номера из списка
              $sql = "SELECT pages FROM ".$prefix."_spiski WHERE id='$elements[$x]'";
              $result = $db->sql_query($sql);
              $row = $db->sql_fetchrow($result);
              $s_pages = $row['pages'];
              if (strpos($agent," $page_id ") < 1) {
              $s_pages .= " $page_id ";
              $s_pages = str_replace("  "," ",$s_pages);
              // теперь присвоем каждому из элементов списка id страницы, которую редактируем.
              $db->sql_query("UPDATE ".$prefix."_spiski SET pages='$s_pages' WHERE id='$elements[$x]'") or die('Ошибка при добавлении страницы в элемент списка. 9. $name');
              }
      
              }
          }
  break;
  ///////////////////
  }
  }
  }
  $db->sql_query("DELETE FROM ".$prefix."_spiski WHERE name='-00-00'"); 
  // or die('Ошибка при удалении страницы из списка. 10'); 
  // Удаление ошибок. Потом поправить, чтобы не было их!!!!!!!!!!!!!!!!!!

  Header("Location: sys.php?op=base_pages_add_page&name=".$module."&razdel=".$cid."&red=".$red."&new=1&pid=".$page_id);
}

/* Бороться со стилями Гугл Хром? ини ну его нах...
<span style=\"font-weight: bold; \" class=\"Apple-style-span\">1</span>
*/

#####################################################################################################################
function base_pages_edit_page($pid, $red=0) {
$page_id = $pid;
global $tip, $module, $admintip, $red, $prefix, $db, $new; //, $redaktor, $toolbars;
    $sql = "SELECT * FROM ".$prefix."_pages WHERE pid='$pid' limit 1";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $cid = $row['cid'];
    $titl = filter($row['title']);
    $open_text = filter($row['open_text']);
    $main_text = filter($row['main_text']);
    $module = $row['module'];
    $foto = $row['foto'];
    // узнать - это галерея или нет
$sql2 = "select id, title, text from ".$prefix."_mainpage where name='$module' and `tables`='pages' and type='2'";
$result2 = $db->sql_query($sql2);
$row2 = $db->sql_fetchrow($result2);
$id = $row2['id'];
$tex = $row2['text'];
$title = $row2['title'];
if (!strpos($tex,"media=1")) $foto = "";
#######################################
    $search = $row['search'];
    $data = $row['date'];
    $counter = $row['counter'];
    $active = $row['active'];
    $comm = $row['comm'];
    //$this_module = $row['module'];
    $mainpage = $row['mainpage'];
    $rss = $row['rss'];
    $price = $row['price'];
    $description = $row['description'];
    $keywords = $row['keywords'];
    $copy = $row['copy'];
    $sor = intval ($row['sort']); 
    include("ad-header.php");

    // узнаем номер последней резервной копии
    $new_pid = 0;
    $sql = "SELECT `pid` from ".$prefix."_pages where copy='".$pid."' order by redate desc limit 1"; // список всех категорий
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $new_pid = $row['pid'];
    echo "<div id='backuppage".$new_pid."'><span class='green'>";
    if ( $new > 0 ) echo "<a target='_blank' class='green' href=/-".$module."_page_".$pid.">Страница</a> отредактирована. "; else echo "Открыть страницу <a target='_blank' class='green' href=/-".$module."_page_".$pid.">на сайте</a>. ";
    if ( $new_pid != 0 ) echo "Есть предыдущая версия. <a title='Заменить этой копией оригинал...' onclick='resetpage(".$new_pid."); setTimeout(\"location.reload()\", 2000);' class='punkt radius'><img class=\"icon2 i24\" src=\"/images/1.gif\"><nobr>Заменить на последнюю</nobr></a></span> </div>";
    else echo "Предыдущей версии нет.";
    
    menu();

echo "<form method=\"POST\" action=\"sys.php\" enctype=\"multipart/form-data\">
<table width=99%><tr valign=top><td width=250 bgcolor=#eeeeee>

<input type=submit value=\"Сохранить изменения\" class=radius style='width:250px; height:55px; font-size: 20px;'><br><br>

<h2>Раздел:</h2>";
           $sql = "select name, title, color from ".$prefix."_mainpage where type='2' and `tables`='pages' and name != 'index' order by color desc, title";
           $result = $db->sql_query($sql);
           echo "<select name=module id=to_razdel style='font-size:11px; width:100%;' size=1 onChange=\"izmenapapka(document.getElementById('to_razdel').value, '', '','','addpage');\">";
           while ($row = $db->sql_fetchrow($result)) {
               $name2 = $row['name'];
               $title2 = $row['title'];
               $color = $row['color'];
switch ($color) {
  case "1": // Частоупотребляемый зеленый
  $color = "b4f3b4"; break;
  case "2": // Редкоупотребляемый желтый
  $color = "f3f3a3";  break;
  case "3": // Закрытый или старый красный
  $color = "ffa4ac"; break;
  case "4": // Новый, в разработке
  $color = "b8f4f2"; break;
  default: 
  $color = "ffffff"; break;  // Стандартный белый
}
               if ($module == $name2) $sel = "selected"; else $sel = "";
               echo "<option style='background:".$color.";' value=\"$name2\" $sel>$title2</option>";
           }
           $sql = "select * from ".$prefix."_".$tip."_categories where module='$module' and `tables`='pages' order by parent_id, title";
           $result = $db->sql_query($sql);
           $numrows = $db->sql_numrows($result);
           if ($numrows > 10) $size = 10*16; else $size=($numrows+2)*16;
echo "</select><br>
<div style='display:inline; float:right;'><div id=showa style='display:inline; float:right;'><a style='cursor:pointer;' onclick=\"show('hidea'); show('showa'); $('#to_papka').width(500); $('#to_papka').height(400);\">развернуть &rarr;</a></div><div id=hidea style='display:none;'><a style='cursor:pointer;' onclick=\"show('showa'); show('hidea'); $('#to_papka').width(248); $('#to_papka').height(".$size.");\">&larr; свернуть</a></div></div>
<h2>Папка:</h2>";
         echo "<div id='izmenapapka'>
         <select name=cid id='to_papka' size=4 style='font-size:11px; width:248px; height:".$size."px;'><option value=0 selected>... основная (корень)</option>";
           while ($row = $db->sql_fetchrow($result)) {
           $cid2 = $row['cid'];
           $title = $row['title'];
           $parentid = $row['parent_id'];
       if ($parentid != 0) $title = "&bull; ".getparent($module,$parentid,$title);
	   if ($cid == $cid2) $sel = "selected"; else $sel = "";
	   if ($parentid == 0) {
           // занести в переменную
           $first_opt[$cid2] = "<option value=$cid2 $sel style='background:#fdf;'>$title</option>"; 
       }
       if ($parentid != 0) {
           // вывести и очистить переменную
           echo $first_opt[$parentid];
           $first_opt[$parentid] = "";
           echo "<option value=$cid2 $sel>$title</option>";
       }
           }
      if (count($first_opt)>0) 
      foreach( $first_opt as $key => $value ) {
        if ($first_opt[$key] != "") echo $first_opt[$key];
      }
echo "</select></div>";

global $siteurl;
//readfile("http://".$siteurl."/help_add_page.txt");

echo "<br><br>";

if ($active==1) $check= " checked"; else $check= " unchecked";
echo "<label><input type=checkbox name=active value=1".$check."> Включить страницу</label> <a onclick=\"show('help3')\" class=help>?</a><br><div id='help3' style='display:none;'><br>Если поставить эту галочку — ссылка на эту страницу будет видна в автоматическом списке страниц данного раздела, а также в блоках, которые выводят страницы данного раздела (если они созданы). Если галочку убрать — на эту страницу все равно можно поставить ссылку из любого места на сайте или с другого сайта и страница будет видна тем, кто перейдет по этой вручную созданной ссылке. Если вы хотите, чтобы в общем списке страниц данная страница не отображалась, а раскрывала более подробную информацию при переходе с другой страницы — отключите ее и сделайте на нее ссылку вручную.<br></div><br>";

echo "<div id='dop2'><a onclick=\"show('dop'); show('dop2');\" class=help>Дополнительно...</a><br></div><div id='dop' style='display:none;'><br>";

if ($rss==1) $check= " checked"; else $check= " unchecked";
echo "<label><input type=checkbox name=rss value=1".$check."> Добавить в RSS</label>  <a onclick=\"show('help2')\" class=help>?</a><br><div id='help2' style='display:none;'><br>Технология RSS похожа на e-mail подписку на новости — в RSS-программу, сайт RSS-читалки или встроенную систему чтения RSS в браузере добавляется ссылка на данный сайт, после чего название и предисловие всех новых страниц, отмеченных данной галочкой, будут видны подписавшемуся человеку и он сможет быстро ознакомиться с их заголовками, не заходя на сайт. Если что-то ему понравится — он откроет сайт и прочитает подробности. RSS используется для постепенного увеличения количества посетителей сайта путем их возвращения на сайт за интересной информацией. <a href=http://yandex.ru/yandsearch?text=Что+такое+RSS%3F target=_blank>Подробнее о RSS?</a><br></div><br>";

if ($mainpage==1) $check= " checked"; else $check= " unchecked";
echo "<label><input type=checkbox name=mainpage value=1".$check."> На главную страницу</label> <a onclick=\"show('help1')\" class=help>?</a><br><div id='help1' style='display:none;'><br>Если отметить эту галочку, данная страница будет отображаться в блоке, который настроен на отображение только помеченных этой галочкой страниц, или не будет отображаться в блоке, который настроен на показ всех неотмеченных галочкой страниц.<br></div><br>";
echo "Очередность: <INPUT type=text name=sor value=\"".$sor."\" style='text-align:center;' size=3 onmouseover=\"this.style.background=&#39;lightgreen&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\"><a onclick=\"show('help8')\" class=help>?</a><div id='help8' style='display:none;'><br>Настраивается в настройках раздела. Может быть равна цифре. Применяется для ручной сортировки страниц. Лучше всего делать кратной 10, например 20, 30, 40 и т.д. для того, чтобы было удобно вставлять страницы между двумя другими. Если очередность у двух страниц совпадает, сортировка происходит по дате.<br></div><br><br>";

$data = explode(" ",$data);
$data1 = date2normal_view($data[0]);
$data = explode(":",$data[1]);
$data2 = $data[0];
$data3 = $data[1];
$data4 = $data[2];
$data3_2 = date("i", time());
echo "<h2>Дата создания:</h2>
<script> 
$(function() { $.datepicker.setDefaults( $.datepicker.regional[ \"ru\" ] ); $( \"#f_date_c999\" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: \"d MM yy\", showAnim: 'slide' }); });
</script>
<INPUT type=text name=data1 id=\"f_date_c999\" value=\"".$data1."\" onchange=\"document.getElementById('add999').value=document.getElementById('f_date_c999').value+'|'+document.getElementById('f_date_c2999').value\" readonly=1 size=18 onmouseover=\"this.style.background=&#39;lightgreen&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\"> <a onclick=\"show('help0')\" class=help>?</a><br>
Время: ";
echo "<select name=data2 style='font-size:12px;'>";
for ($x=0; $x < 24; $x++) {
if ($x<10) $xx = "0".$x; else $xx = $x;
           $sel = ""; if ($xx == $data2) $sel = " selected";
	   echo "<option value=".$xx."$sel> $xx </option>";
           }
echo "</select>ч";

echo "<select name=data3 style='font-size:12px;'>

<option value=".$data3."$sel> $data3 </option>
<option value=".$data3_2."> $data3_2!</option>
<option value='00'> 00 </option>
<option value='10'> 10 </option>
<option value='15'> 15 </option>
<option value='20'> 20 </option>
<option value='30'> 30 </option>
<option value='40'> 40 </option>
<option value='45'> 45 </option>
<option value='50'> 50 </option>
<option value='55'> 55 </option>
";
/* 
for ($x=0; $x < 60; $x++) {
if ($x<10) $xx = "0".$x; else $xx = $x;
           $sel = ""; if ($xx == $data3) $sel = " selected";
       echo "<option value=".$xx."$sel> $xx </option>";
           }
*/
echo "</select>м";

echo "<input type=text name=data4 value=\"".$data4."\" style='font-size:12px;' size=1 onclick=\"this.value='00'\" onmouseover=\"this.style.background=&#39;lightgreen&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\">с
<div id='help0' style='display:none;'><br>Для выбора даты из календаря нажмите по дате. Для обнуления секунд кликните по ним. Минуты представлены текущим вариантом или выбором из основного интервала для ускорения работы.<br></div>
<br><br>";

//<a onclick=\"show('vstavka')\" class=punkt>Проверка орфографии</a><div id='vstavka' style='display:none;'><iframe src=/orfo.php width=100% height=300 style='border:0;'></iframe></div><br><br>

echo "<a onclick=\"show('slugebka')\" class=punkt>Скрытая информация</a>
<br><div id='slugebka' style='display:none;'><div class=radius><span class=small>Лучше не менять.</span><br>
<h3 style='display:inline'>Копия:</h3><INPUT type=text name=cop value=\"".$copy."\" size=3><a onclick=\"show('help18')\" class=help>?</a><br><div id='help18' style='display:none;'>У страниц-копий указывается один и тот же номер — номер оригинальной страницы. Если это не копия, а единственный оригинал, цифра равна 0.<br></div><br>
<h3 style='display:inline'>Кол-во комментариев:</h3><INPUT type=text name=com value=\"".$comm."\" size=3><br><br>
<h3 style='display:inline'>Кол-во посещений:</h3><INPUT type=text name=count value=\"".$counter."\" size=3>
</div></div><br>

Горячие клавиши:<br>
Ctrl+C&nbsp;-&nbsp;Копировать<br>
Ctrl+V&nbsp;-&nbsp;Вставить<br>
Ctrl+X&nbsp;-&nbsp;Вырезать<br>
Ctrl+A&nbsp;-&nbsp;Выделить&nbsp;всё<br>
Ctrl+Z&nbsp;-&nbsp;Отменить
</div>

</td><td>
<h2 class=radius_top style='background:#eeeeee;'>Название страницы (заголовок)<textarea class=big name=title rows=1 cols=10 style='font-size:16pt; width:100%;'>".$titl."</textarea></h2>
<br><h2 class=radius_top style='background:#eeeeee;'>Предисловие (начальный текст)";

// Исправление сломанных таблиц
$open_text = str_replace("td> nowrap","td nowrap",$open_text);
$open_text = str_replace("td> valign","td valign",$open_text);
$open_text = str_replace("td>>","td>",$open_text);
$main_text = str_replace("td> nowrap","td nowrap",$main_text);
$main_text = str_replace("td> valign","td valign",$main_text);
$main_text = str_replace("td>>","td>",$main_text);

if ($red==0) {
$spaw = new SpawEditor("open_text", $open_text); 
//$spaw->setStylesheet("/css_20"); // В дальнейшем подключить стили!!!
$spaw->setDimensions("100%", "200"); 
$spaw->show();
} elseif ($red==2) {
echo "
<textarea cols=80 id=editor name=open_text rows=10>".$open_text."</textarea>
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
} elseif ($red==1) {
  // Преобразование textarea (замена на русскую букву е, только для редактора)
  $open_text = str_replace("textarea","tеxtarea",$open_text); // ireplace
echo "<textarea id=\"open_text\" name=\"open_text\" rows=\"8\" cols=\"80\" style='width:100%;'>".$open_text."</textarea>";
} elseif ($red==3) {
echo "<script type=\"text/javascript\"> 
$(document).ready(function()
{  $('#open_text').editor({ focus: true, toolbar: 'classic', css: ['/ed/js/editor/css/editor.css'], upload: 'upload.php' }); 
$('#main_text').editor({ css: ['/ed/js/editor/css/editor.css'], toolbar: 'classic', upload: 'upload.php' });  });
</script>
<textarea id=\"open_text\" name=\"open_text\" rows=\"8\" cols=\"80\" style='width:100%;'>".$open_text."</textarea>";
} elseif ($red==4) {
    global $red4_div_convert;
    echo "<script type=\"text/javascript\">
    function ButtonMore(obj, event, key){ obj.insertHtml('<!--more-->'); }
    function ButtonBlock(obj, event, key){ obj.insertHtml('[Название блока]'); }
    function ButtonLink(obj, event, key){ obj.insertHtml('{Название страницы или раздела}'); }
    $(document).ready(function() { 
      $('.redactor').redactor({ buttonsAdd: ['|', 'button_more', 'button_link', 'button_block'], buttonsCustom: {
        button_more: {title: 'Вставка ссылки на полное содержание (для предисловия)',callback: ButtonMore},
        button_link: {title: 'Вставка блока (например, галереи фотографий)',callback: ButtonBlock},
        button_block: {title: 'Вставка быстрой ссылки на страницу или раздел',callback: ButtonLink}
      }, mobile: false, ".$red4_div_convert." imageUpload: 'ed2/image_upload.php',fileUpload: 'ed2/file_upload.php' }); } );
    </script>
<textarea id=\"open_text\" class='redactor' name=\"open_text\" rows=\"8\" cols=\"80\" style='width:100%;'>".$open_text."</textarea>";
}

echo "</h2><br><h2 class=radius_top style='background:#eeeeee;'>Содержание (основной текст)";

if ($red==0) {
  $spaw = new SpawEditor("main_text", $main_text); 
  //$spaw->setStylesheet("/css_20"); // В дальнейшем подключить стили!!!
  $spaw->setDimensions("100%", "450"); 
  $spaw->show();
} elseif ($red==2) {
  echo "<textarea cols=80 id=edit name=main_text rows=12>".$main_text."</textarea>
<script type=\"text/javascript\">
CKEDITOR.replace( 'edit', {
 filebrowserBrowseUrl : '/ckfinder/ckfinder.html',
 filebrowserImageBrowseUrl : '/ckfinder/ckfinder.html?Type=Images',
 filebrowserFlashBrowseUrl : '/ckfinder/ckfinder.html?Type=Flash',
 filebrowserUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
 filebrowserImageUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
 filebrowserFlashUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
</script>";
} elseif ($red==1) {
  // Преобразование textarea (замена на русскую букву е, только для редактора)
  $main_text = str_replace("textarea","tеxtarea",$main_text); // ireplace
  echo "<textarea id=\"main_text\" name=\"main_text\" rows=\"12\" cols=\"80\" style='width:100%;'>".$main_text."</textarea>";
} else {
  echo "<textarea id=\"main_text\" class='redactor' name=\"main_text\" rows=\"12\" cols=\"80\" style='width:100%;'>".$main_text."</textarea>";
}
echo "</h2><br>";

// это галерея?
$sql = "select text from ".$prefix."_mainpage where name='$module' and type='2'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$tex = $row['text'];
if (strpos($tex,"view=5")) echo "<p><b>Фото (для фотогалереи):</b> <input type=file name=foto size=40> 
<b>или ссылка:</b> <input type=text name=link_foto value='$foto' size=40></p>";
else echo "<input type=hidden name=foto value='$foto'>";

// это магазин?
if (strpos($tex,"view=3")) echo "<p><b>Стоимость:</b> <input type=text name=price size=3 value='$price'> руб.</p>";
else echo "<input type=hidden name=price value='$price'>";

// Подсоединие списков ////////////////////////////////
if ($copy != 0) $page_id = $copy;
// Ищем все списки
$sql = "select * from ".$prefix."_mainpage where (useit='".$id."' or useit='0') and type='4' order by id";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
$s_id = $row['id'];
$s_title = $row['title'];
$s_name = $row['name'];
$options = explode("|", $row['text']); $options = $options[1];
$type=0; $shablon=""; 
parse_str($options); // раскладка всех настроек списка
//if ($type!=1) { $type=0; $type_name="список"; } else { $type_name="текст"; }
switch($type) {
////////////////////////////////////////////////////////////////////////////

case "4": // строка
// Получаем значениЕ поля
$sql2 = "SELECT name FROM ".$prefix."_spiski WHERE type='".$s_name."' AND pages like '% ".$page_id." %'";
$result2 = $db->sql_query($sql2);
$row2 = $db->sql_fetchrow($result2);
$sp_name = $row2['name'];
echo "<br><br><b>$s_title:</b><br><INPUT type=text name=\"add[$s_name]\" value='".$sp_name."'>";
break;
////////////////////////////////////////////////////////////////////////////

case "3": // период времени
// Получаем значениЕ поля
$sql2 = "SELECT name FROM ".$prefix."_spiski WHERE type='".$s_name."' AND pages like '% ".$page_id." %' order by name";
$result2 = $db->sql_query($sql2); $row2 = $db->sql_fetchrow($result2); $date1 = date2normal_view($row2['name']);
$sql2 = "SELECT name FROM ".$prefix."_spiski WHERE type='".$s_name."' AND pages like '% ".$page_id." %' order by name desc";
$result2 = $db->sql_query($sql2); $row2 = $db->sql_fetchrow($result2); $date2 = date2normal_view($row2['name']);

echo "<br><br><b>".$s_title.":</b> (выберите даты из меню, кликнув по значкам)<br>
<TABLE cellspacing=0 cellpadding=0 style=\"border-collapse: collapse\"><TBODY><TR> 
<TD><INPUT type=text name=\"text[".$s_name."]\" id=\"f_date_c[".$s_name."]\" value=\"".$date1."\" onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD>
<TD><IMG src=/images/calendar.gif id=\"f_trigger_c[".$s_name."]\" title=\"Выбор даты\" onmouseover=\"this.style.background=&#39;red&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\"></TD>
<TD width=20 align=center> - </TD>
<TD><INPUT type=text name=\"text[".$s_name."]\" id=\"f_date_c2[".$s_name."]\" value=\"".$date2."\" onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD> 
<TD><IMG src=/images/calendar.gif id=\"f_trigger_c2[".$s_name."]\" title=\"Выбор даты\" onmouseover=\"this.style.background=&#39;red&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\"></TD>
</TR></TBODY></TABLE>
<SCRIPT type=\"text/javascript\"> 
    Calendar.setup({
        inputField     :    \"f_date_c[".$s_name."]\",     // id of the input field
        ifFormat       :    \"%e %B %Y\",      // format of the input field
        button         :    \"f_trigger_c[".$s_name."]\",  // trigger for the calendar (button ID)
        align          :    \"Tl\",           // alignment (defaults to \"Bl\")
        singleClick    :    true
    });
</SCRIPT>
<SCRIPT type=\"text/javascript\"> 
    Calendar.setup({
        inputField     :    \"f_date_c2[".$s_name."]\",     // id of the input field
        ifFormat       :    \"%e %B %Y\",      // format of the input field
        button         :    \"f_trigger_c2[".$s_name."]\",  // trigger for the calendar (button ID)
        align          :    \"Tl\",           // alignment (defaults to \"Bl\")
        singleClick    :    true
    });
</SCRIPT>
<input type=hidden name=\"add[".$s_name."]\" id=\"add[".$s_name."]\" value=\"".$date1."|".$date2."\">"; //
break;
////////////////////////////////////////////////////////////////////////////

case "2": // файл (НЕ_ГОТОВО!!!)
break;
////////////////////////////////////////////////////////////////////////////

case "1": // текст
// Получаем значениЕ поля
$sql2 = "SELECT name FROM ".$prefix."_spiski WHERE type='".$s_name."' AND pages like '% ".$page_id." %'";
$result2 = $db->sql_query($sql2);
$row2 = $db->sql_fetchrow($result2);
$sp_name = $row2['name'];
echo "<br><br><b>$s_title:</b><br><textarea name=\"add[$s_name]\" rows=\"1\" cols=\"60\">".$sp_name."</textarea>";
break;
////////////////////////////////////////////////////////////////////////////

case "0": // список
// Получаем значениЯ поля
$sql2 = "SELECT name FROM ".$prefix."_spiski WHERE type='".$s_name."' AND pages like '% ".$page_id." %'";
$result2 = $db->sql_query($sql2);
$sp_names = array();
while ($row2 = $db->sql_fetchrow($result2)) {
$sp_names[] = $row2['name'];
}
echo "<br><b>$s_title:</b><br>";
           $sql2 = "SELECT * FROM ".$prefix."_spiski WHERE type='$s_name' ORDER BY parent,id";
           $result2 = $db->sql_query($sql2);
           echo "<select size=10 multiple=multiple name=\"add[$s_name][]\" style='font-size:11px;'><option value=0> не выбрано </option>";
           while ($row2 = $db->sql_fetchrow($result2)) {
           $s_id2 = $row2['id'];
           $s_title2 = $row2['name'];
           $s_opis = $row2['opis'];
           $s_parent = $row2['parent'];
	   $s_title2 = getparent_spiski($s_name,$s_parent,$s_title2);
           $sel = ""; if (in_array($s_title2,$sp_names)) $sel = " selected";
	   echo "<option value=".$s_id2.$sel."> $s_title2 ($s_opis)</option>";
           }
echo "</select>";
break;
///////////////////
}
}



echo "<h3>Ключевые слова (для поисковых систем):</h3> <textarea name=keywords2 class=big rows=2 cols=10 style='width:100%;'>".$keywords."</textarea>
<br><div class='help small'>?</div> <span class=small>Максимум 1000 символов. Разделять словосочетания желательно запятой. Если пусто - используются <b>Теги</b> (если и они пустые - используются Ключевые словосочетания из <a href=/sys.php?op=Configure target=_blank>Настроек портала</a>).</span><br><br>

<h3>Описание для поисковых систем:</h3> <textarea name=description2 class=big rows=2 cols=10 style='width:100%;'>".$description."</textarea><br>
<div class='help small'>?</div> <span class=small>Максимум 200 символов. Если пусто - используется <b>Название</b> страницы.</span>";

echo "<h3>Тэги (слова для похожих по тематике страниц):</h3> 
<textarea name=search class=big rows=2 cols=10 style='width:100%;'>".$search."</textarea>
<br><div class='help small'>?</div> <span class=small>Разделять пробелами, а слова в словосочетаниях символом + 
<br>Писать только существительные! НИКАКИХ ПРЕДЛОГОВ! Максимум неограничен. Разделять слова необходимо пробелом. Разделять слова в словосочетаниях символом +, например: игра+разума игротека game. Писать желательно в единственном числе и именительном падеже. Можно создать Блок \"Облако тегов\". Теги также могут выводиться на страницах (в настройках Раздела).</span><br><br>";

echo "<input type=hidden name=op value=".$admintip."_edit_sv_page>
<input type=hidden name=pid value=$pid>
</td></tr>
</form></table>";
admin_footer(); //include("ad-footer.php");
}


#####################################################################################################################
#####################################################################################################################
/*
function slash($txt) {
    //$txt = tipograf($txt, 1);
    if (get_magic_quotes_gpc($txt)) $txt = stripslashes($txt);
    $txt = filter($txt);
    return $txt;
}
*/
#####################################################################################################################
function base_pages_edit_sv_page($pid, $module, $cid, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $price, $add, $data1, $data2, $data3, $data4, $keywords2, $description2, $com, $cop, $count, $sor, $open_text_mysor, $main_text_mysor) {
  global $tip, $admintip, $prefix, $db, $now;
  ##----------------------------------------------------##
  /*
  $title = slash($title);
  $open_text = slash($open_text);
  $main_text = slash($main_text);
  $keywords2 = slash($keywords2);
  $description2 = slash($description2);
  $search = slash($search);
  */

  // Делаем резервную копию!
  $sql = "SELECT module,cid,title,open_text,main_text,date,counter,active,golos,comm,foto,search,mainpage,rss,price,description,keywords,copy,sort FROM ".$prefix."_".$tip." WHERE pid='$pid'";
  $result = $db->sql_query($sql);
  list($p_module,$p_cid,$p_title,$p_open_text,$p_main_text,$p_date,$p_counter,$p_active,$p_golos,$p_comm,$p_foto,$p_search,$p_mainpage,$p_rss,$p_price,$p_description,$p_keywords,$p_sort) = $db->sql_fetchrow($result);
  /*
  $p_title = slash($p_title);
  $p_keywords = slash($p_keywords);
  $p_description = slash($p_description);
  $p_search = slash($p_search);
  $p_open_text = form($p_module, $p_open_text, "open");
  $p_main_text = form($p_module, $p_main_text, "main");
  */

  // узнать - это галерея или нет
  $sql = "select text from ".$prefix."_mainpage where name='$module' and type='2'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $tex = $row['text'];
  if (strpos($tex,"media=1")) {
  $ImgDir="spaw2/uploads/images/$tip";
  if ($_FILES["foto"]["name"]!="") {
  // Обработка имени файла: транслит и удаление пробелов
  $pic_name2 = date("Y-m-d_H-i-s_", time()).str_replace(" ","",translit($_FILES["foto"]["name"]));
  	if (Copy($_FILES["foto"]["tmp_name"],"$ImgDir/".basename($pic_name2))) {
  	unlink($_FILES["foto"]["tmp_name"]);
  	chmod("$ImgDir/".basename($pic_name2),0644);
  	$foto="/$ImgDir/".basename($pic_name2);
  	} else echo "ОШИБКА при копировании файла";
  } else $foto=trim($link_foto);
  } else $foto="";
  ##----------------------------------------------------##
  // это магазин?
  //if (strpos($tex,"shop=")) 
    $price=intval($price);
  ##----------------------------------------------------##
  $search = str_replace(", "," ",$search);
  $search = str_replace(","," ",$search);
  $search = str_replace(". "," ",$search);
  $search = str_replace("."," ",$search);
  $search = str_replace("  "," ",$search);
  $search = " ".trim($search)." "; // strtolow(
  if ($mainpage=="") $mainpage=0;
  $sor = intval($sor);
  $rss = intval($rss);

  $keywords2 = trim(str_replace("  "," ",str_replace("   "," ",str_replace(" ,",", ",$keywords2))));
  $description2 = trim($description2);
  $open_text = mysql_real_escape_string(form($module, $open_text, "open"));
  $main_text = mysql_real_escape_string(form($module, $main_text, "main"));
  $title = mysql_real_escape_string(form($module, $title, "title"));

  // Обратное преобразование textarea (замена русской буквы е)
  $main_text = str_replace("tеxtarea","textarea",$main_text); // ireplace
  $open_text = str_replace("tеxtarea","textarea",$open_text); // ireplace

  $p_open_text = mysql_real_escape_string(form($module, $p_open_text, "open"));
  $p_main_text = mysql_real_escape_string(form($module, $p_main_text, "main"));
  $p_title = mysql_real_escape_string(form($module, $p_title, "title"));

  //if (get_magic_quotes_gpc($title)) $title = stripslashes($title);  $title = filter($title, "nohtml");

  $data = date2normal_view($data1, 1)." $data2:$data3:$data4";
  # pid, module, cid, title, open_text, main_text, date, counter, active, golos, comm, foto, search, mainpage, rss, price, description, keywords, tables, copy
  $data2 = $now;

  $sql = "UPDATE ".$prefix."_".$tip." SET module='$module', cid='$cid', title='$title', open_text='$open_text', main_text='$main_text', date='$data', redate='$data2', counter='$count', active='$active', comm='$com', foto='$foto', search='$search', mainpage='$mainpage', rss='$rss', price='$price', description='$description2', keywords='$keywords2', copy='$cop', sort='$sor' WHERE pid='".$pid."';";
  $db->sql_query($sql) or die('Не удалось сохранить изменения... Передайте нижеследующий текст разработчику:<br>'.$sql);

  // Делаем резервную копию
  if ($p_active != 3) // если это не добавленная пользователем страница
  $db->sql_query("INSERT INTO ".$prefix."_".$tip." VALUES (NULL, '$p_module', '$p_cid', '$p_title', '$p_open_text', '$p_main_text', '$p_date', '$now', '$p_counter', '$p_active', '$p_golos', '$p_comm', '$p_foto', '$p_search', '$p_mainpage', '$p_rss', '$p_price', '$p_description', '$p_keywords', 'backup', '$pid', '$p_sort');") or die("Резервная копия не создана...");

  // Ярлык?
  $and_copy = "";
  if ($cop != 0) { // Узнаем наличие других копий
    $sql = "select pid from ".$prefix."_".$tip." where copy='$cop' and pid!='$pid'";
    $result = $db->sql_query($sql);
    $and_copy = array();
    while ($row = $db->sql_fetchrow($result)) {
      $pidX = $row['pid'];
      $and_copy[] = "pid='$pidX'";
      if (function_exists('recash')) recash("/-".$module."_page_".$pidX, 0); // Обновление кеша ##
    }
    $and_copy = implode(" or ",$and_copy);
    # pid cid title open_text main_text foto search date counter active comm
    $db->sql_query("UPDATE ".$prefix."_".$tip." SET title='$title', open_text='$open_text', main_text='$main_text', date='$data', redate='$data2', counter='$count', active='$active', comm='$com', foto='$foto', search='$search', mainpage='$mainpage', rss='$rss', price='$price', description='$description2', keywords='$keywords2', sort='$sor' WHERE ".$and_copy.";");
  }

  global $siteurl;
  if (function_exists('recash') and $active == 1) {
    recash("/-".$module."_page_".$pid); // Обновление кеша ##
    recash("/-".$module."_cat_".$cid, 0); ####################
    recash("/-".$module."_cat_".$cid."_page_0", 0); ##########
    recash("/-".$module."_cat_".$cid."_page_1", 0); ##########
    recash("/-".$module."",0); ###############################
  }

  // РАБОТА СО СПИСКАМИ
  $page_id = $pid;
  if (isset($copy)) if ($copy != 0) $page_id = $copy;
  del_spiski($page_id); // Стираем упоминания о списках для переназначения
  if (!isset($add) or $add == "") $add = array();

  // Получение информации о каждом списке
  foreach ($add as $name => $elements) { 
    $sql = "select * from ".$prefix."_mainpage where name='$name' and type='4'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $s_id = $row['id'];
    $options = explode("|", $row['text']); $options = $options[1];
    $type=0; $shablon=""; 
    parse_str($options); // раскладка всех настроек списка
    //if ($type!=1) { $type=0; $type_name="список"; } else { $type_name="текст"; }
    switch($type) {
      case "4": // строка
      // Найдем текст для данной страницы
      $sql = "SELECT id, name, pages FROM ".$prefix."_spiski WHERE type='$name' and pages like '% $page_id %'";
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $nums = $db->sql_numrows($result);

      $del_id = $row['id'];
      $del_name = $row['name'];
      $del_pages = $row['pages'];
      if ($nums==0 or ($elements != $del_name and $del_name!="")) { // Сравним найденный текст с вводимым

          // записываем новый текст - Проверяем наличие подобного текста
          /*
          $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='$name' and name='$elements'";
          $result = $db->sql_query($sql);
          $numrows = $db->sql_numrows($result);
          if ($numrows == 1) { // если элемент найден
              $row = $db->sql_fetchrow($result);
              $s_pages = $row['pages'];
              $s_name = $row['name'];
                  if (strpos(" ".$s_pages," $page_id ") < 1 and $s_name==$elements) {
                      $s_pages .= " $page_id ";
                      $s_pages = " ".str_replace("  "," ",trim($s_pages))." ";
                      $db->sql_query("UPDATE ".$prefix."_spiski SET pages='$s_pages' WHERE type='$name' and name='$elements'") or die('Ошибка при добавлении страницы в элемент списка');;
                  } else {
                      $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список.');
                  }
          } else { // если элемент новый
          */
          // 
              $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список.');
          //}

      } // END Сравним найденный текст с вводимым
      // Если текст похож - ничего не делаем, т.к. информация не изменилась.

      break;
      ///////////////////////////////////////////////////////////////////////////////////////////
      case "3": // период времени
      // создаем диапазон дат и все их проверяем
      $elements = explode("|",$elements);
      $dat1 = date2normal_view($elements[0], 1);
      $dat2 = date2normal_view($elements[1], 1);
      $period = period($dat1, $dat2);

      // и все даты проверяем на наличие в БД
      $upd = array();
      $noupd = array();

      $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='$name' order by name";
      $result = $db->sql_query($sql);

      while ($row = $db->sql_fetchrow($result)) {
      $nam = $row['name']; // дата
      $pag = trim($row['pages']); // страницы
      if (in_array($nam, $period)!=FALSE) { 
      $noupd[] = $nam; // для INSERT
      if (strstr($pag,$page_id)==FALSE) $upd[] = $nam; // для UPDATE
      }
      }

      $insert = array();
      $update = array();
      foreach ($upd as $up) {

      $update[] = "name='$up'";
      }
      foreach ($period as $per) {
      if (!in_array($per, $noupd)) $insert[] = "(NULL, '$name', '$per', '', '0', ' $page_id ', '0')";
      }

      $insert = implode(", ",$insert);
      $update = implode(" or ",$update);

      $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='$name' and (".$update.") order by name";
      $result = $db->sql_query($sql);
      while ($row = $db->sql_fetchrow($result)) {
      $na = $row['name']; // дата
      $pa = $row['pages']; // страницы
      	//if (trim($update) != "") {
      	$db->sql_query("UPDATE ".$prefix."_spiski SET pages = ' $pa $page_id ' WHERE type='$name' and name='$na'") or die ("Ошибка: Не удалось обновить списки. $page_id $name");
      	//print ("UPDATE ".$prefix."_spiski SET pages = ' $pa $page_id ' WHERE type='$name' and name='$na'<br>");
      	//}
      }

      	if (trim($insert) != "") {
      	$db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES ".$insert.";") or die ('Ошибка: Не удалось сохранить списки.');
      	//print ("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES ".$insert.";<br>");
      	}
      break;
      ///////////////////////////////////////////////////////////////////////////////////////////////
      case "2": // файл

      // Неокончено !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

      break;
      ///////////////////////////////////////////////////////////////////////////////////////////////
      case "1": // текст
      // Найдем текст для данной страницы
      $sql = "SELECT id, name, pages FROM ".$prefix."_spiski WHERE type='$name' and pages like '% $page_id %'";
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $nums = $db->sql_numrows($result);

      $del_id = $row['id'];
      $del_name = $row['name'];
      $del_pages = $row['pages'];
      if ($nums==0 or ($elements != $del_name and $del_name!="")) { // Сравним найденный текст с вводимым
          // записываем новый текст - Проверяем наличие подобного текста
          /*
          $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE name='$elements'";
          $result = $db->sql_query($sql);
          $numrows = $db->sql_numrows($result);
          if ($numrows > 0) { // если элемент найден
              $row = $db->sql_fetchrow($result);
              $s_pages = $row['pages'];
              $s_name = $row['name'];
                  if (strpos(" ".$s_pages," $page_id ") < 1 and $s_name==$elements) {
                      $s_pages .= " $page_id ";
                      $s_pages = " ".str_replace("  "," ",trim($s_pages))." ";
                      $db->sql_query("UPDATE ".$prefix."_spiski SET pages='$s_pages' WHERE type='$name' and name='$elements'") or die('Ошибка при добавлении страницы в элемент списка');;
                  } else {
                      $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список.');
                  }
          } else { // если элемент новый
          */
          // 
              $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список.');
          //}

      } // END Сравним найденный текст с вводимым
      // Если текст похож - ничего не делаем, т.к. информация не изменилась.
      break;
      ////////////////////////////////////////////////////////////////////////////
      case "0": // список
      $num = count($elements); // сколько элементов в списке
      for ($x=0; $x < $num; $x++) { // посчитали сколько номеров списка
      	if ($elements[$x] != 0) { // Если это не "Не выбрано"
      	// узнаем какие страницы уже есть у этого номера из списка
      	$sql = "SELECT pages FROM ".$prefix."_spiski WHERE id='$elements[$x]'";
      	$result = $db->sql_query($sql);
      	$row = $db->sql_fetchrow($result);
      	$s_pages = $row['pages']." $page_id ";
      	$save_pages = str_replace("  "," ",$s_pages);
      	// теперь присвоем каждому из элементов списка id страницы, которую редактируем.
      	$db->sql_query("UPDATE `".$prefix."_spiski` SET `pages` =  '".$save_pages."' WHERE  `id` =".$elements[$x]." LIMIT 1 ;") or die('Ошибка при добавлении страницы в элемент списка');
      	}
      } 
      break;
    }
  }

  $db->sql_query("DELETE FROM ".$prefix."_spiski WHERE name='-00-00'"); // Удаление ошибок. Потом поправить, чтобы не было их!!!

  //Header("Location: sys.php");
  Header("Location: sys.php?op=base_pages_edit_page&name=".$name."&new=1&pid=".$pid);
}
#####################################################################################################################
function base_pages_delit_page($name,$pid, $ok) {
    global $tip, $admintip, $prefix, $db;
    $pid = intval($pid);
    $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE pid='$pid'");
    //recash($url0);
    Header("Location: sys.php");
}
#####################################################################################################################
function base_delit_comm() {
  global $tip, $prefix, $db;
  $db->sql_query("DELETE FROM ".$prefix."_".$tip."_comments WHERE cid>'0';") or die('Не удалось удалить комментарии');
  $db->sql_query("UPDATE ".$prefix."_".$tip." SET comm='0' WHERE comm>'0';") or die('Не удалось удалить записи о комментариях в страницах');
  $db->sql_query("UPDATE ".$prefix."_".$tip." SET counter='0' WHERE counter>'0';") or die('Не удалось удалить счетчики посещиний в страницах');
  $db->sql_query("UPDATE ".$prefix."_".$tip." SET golos='0' WHERE golos>'0';") or die('Не удалось удалить записи о голосованиях в страницах');
  Header("Location: sys.php");
}
#####################################################################################################################
function base_delit_noactive_comm($del="noactive") {
  global $tip, $prefix, $db;
  if ($del == "noactive") $db->sql_query("DELETE FROM ".$prefix."_".$tip."_comments WHERE cid>'0' and active!='1';") or die('Не удалось удалить отключенные комментарии');
  if ($del == "system") $db->sql_query("DELETE FROM ".$prefix."_".$tip."_comments WHERE num='0' and avtor='ДвижОк' and mail='';") or die('Не удалось удалить системные комментарии');
  Header("Location: sys.php");
}
#####################################################################################################################
function base_pages_delit_comm($cid, $ok, $pid) {
	mt_srand((double)microtime()*1000000);
	$num1 = mt_rand(100, 900);
  $url = getenv("HTTP_REFERER"); // REQUEST_URI
    global $tip, $prefix, $db;
    $cid = intval($cid);
    $pid = intval($pid);
    if($ok=="ok") {
    $db->sql_query("DELETE FROM ".$prefix."_".$tip."_comments WHERE cid='$cid'");
    //$sql = "select comm from ".$prefix."_".$tip." where pid='$pid'";
    //$result = $db->sql_query($sql);
    //$row = $db->sql_fetchrow($result);
    //$comm = $row[comm];
    //if ($comm>0) $comm=$comm-1;
    $db->sql_query("UPDATE ".$prefix."_".$tip." SET comm=comm-1 WHERE pid = '$pid' and comm > '0'");
    $sql = "select module from ".$prefix."_".$tip." where pid = '$pid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $mod = $row['module'];
    if (function_exists('recash')) recash("/-".$mod."_page_".$pid); // Обновление кеша ##
      $url = str_replace("#comm","",$url);
      Header("Location: $url");
    }
}
#####################################################################################################################
function base_pages_re($link) {
    global $referer; //, $prefix, $db;
    //$sql = "select module from ".$prefix."_".$tip." where pid = '$pid'";
    //$result = $db->sql_query($sql);
    //$row = $db->sql_fetchrow($result);
    //$mod = $row['module'];
    recash($link);
    //$url = getenv("HTTP_REFERER");
    Header("Location: $referer");
}
#####################################################################################################################
function base_pages_teleport($name, $from_razdel, $operation, $to_razdel, $papka) {
    global $tip, $admintip, $prefix, $db;
    # cid module title description pic sort counter parent_id
    
    //echo "$name, $from_razdel, $operation, $to_razdel, $papka";
    // 4um, copy_0, copy, culinaria, 0 
    
    $and = "";
    $and2 = "";
    $and_papki = false;
    $and_pages = true;
    //if ( $operation == 'delete' ) $and_papki = true;
    
    switch ( $from_razdel ) {
        case 'copy_0':        # ВСЕ страницы (без папок)
        break;
        case 'copy_1':        # ВСЕ страницы (с папками)
        $and_papki = true;
        break;
        case 'copy_2':        # ТОЛЬКО страницы из Начала раздела
        $and = " and cid='0'";
        break;
        case 'copy_3':        # ВСЕ КРОМЕ Начала раздела (без папок)
        $and = " and cid!='0'";
        break;
        case 'copy_4':        # ВСЕ КРОМЕ Начала раздела (с папками)
        $and_papki = true;
        $and = " and cid!='0'";
        break;
        case 'copy_5':        # ТОЛЬКО ПАПКИ
        $and_papki = true;
        $and_pages = false;
        break;
        default:              # Определенная папка
        $and = " and cid='$from_razdel'";
        $and2 = " and parent_id='$from_razdel'";
        break;
    }
    //$and_papki = false;
    //$and_pages = false;
/////////////////////////////////////// СТРАНИЦЫ
    if ($and_pages == true) {
                switch ( $operation ) {
                    case 'copy':        # Копирование
                    $db->sql_query("INSERT INTO ".$prefix."_".$tip." (pid, module, cid, title, open_text, main_text, `date`, `redate`,counter, active, golos, comm, foto, search, mainpage, rss, price, description, keywords) SELECT NULL pid, '".$to_razdel."' module, '".$papka."' cid, title, open_text, main_text, `date`, `redate`, counter, active, golos, comm, foto, search, mainpage, rss, price, description, keywords FROM ".$prefix."_".$tip." WHERE (module='".$name."'".$and.")") or die("ошибка: не могу скопировать страницы");
                    break;
                    case 'teleport':        # Перемещение
                    $db->sql_query("UPDATE ".$prefix."_".$tip." SET module='$to_razdel', cid='$papka' WHERE module='".$name."'".$and) or die('ошибка: не могу переместить страницу № $pid');
                    break;
                    case 'delete':          # Удаление
                    $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE module='".$name."'".$and) or die('ошибка: не могу удалить страницу № $pid');
                    break;
                }
    }

////////////////////////////////////// ПАПКИ
    if ($and_papki == true) {
                switch ( $operation ) {
                    case 'copy':        # Копирование
                    $db->sql_query("INSERT INTO ".$prefix."_".$tip."_categories (cid, module, title, description, pic, `sort`, counter, parent_id, `tables`) SELECT NULL cid, '".$to_razdel."' module, title, description, pic, `sort`, counter, '".$papka."' parent_id, `tables` FROM ".$prefix."_".$tip."_categories WHERE (module='".$name."'".$and2.")") or die("ошибка: не могу скопировать папки");
                    break;
                    case 'teleport':        # Перемещение
                    // Защита от перемещения себя в себя!
                    if ($papka != 0) $and2 .= " and cid!='$papka'";
                    $db->sql_query("UPDATE ".$prefix."_".$tip."_categories SET module='$to_razdel', parent_id='$papka' WHERE module='$name'".$and2) or die('ошибка: не могу переместить папки');
                    break;
                    case 'delete':          # Удаление
                    $db->sql_query("DELETE FROM ".$prefix."_".$tip."_categories WHERE module='$name'".$and2) or die('ошибка: не могу удалить папки');
                    echo "DELETE FROM ".$prefix."_".$tip."_categories WHERE module='$name'".$and2;
                    break;
                }
    }
///////////////////////////////////////
if ($operation == 'delete') Header("Location: sys.php");
else Header("Location: sys.php");
}
#####################################################################################################################
switch ($op) {

    case "base_pages":
    base_pages($name);
    break;

    case "base_pages_cat":
    base_pages_cat($name);
    break;

    case "base_pages_teleport":
    base_pages_teleport($name, $from_razdel, $operation, $to_razdel, $papka);
    break;
    
    case "base_pages_add_category":
    base_pages_add_category($name, $title, $parent_id);
    break;

    case "edit_base_pages_category":
    edit_base_pages_category($cid, $red);
    break;

    case "base_pages_save_category":
    base_pages_save_category($cid, $module, $title, $desc, $sortirovka, $parent_id);
    break;

    case "base_pages_del_category":
    base_pages_del_category($cid, $ok, $name);
    break;

    case "base_pages_add_page":
    if (!isset($razdel)) $razdel = "";
    if (!isset($name)) $name = "";
    if (!isset($red)) $red = "3";
    base_pages_add_page($name, $razdel, $red);
    break;

    case "base_pages_save_page":
    if (!isset($foto)) $foto = "";
    if (!isset($link_foto)) $link_foto = "";
    if (!isset($mainpage)) $mainpage = "";
    $title = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $title));
    $open_text = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $open_text));
    $main_text = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $main_text));
    $keywords2 = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $keywords2));
    $description2 = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $description2));
    $search = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $search));
    if (!isset($add)) $add = "";
    if (!isset($open_text_mysor)) $open_text_mysor = "";
    if (!isset($main_text_mysor)) $main_text_mysor = "";
    base_pages_save_page($cid, $module, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $price, $add, $data1, $data2, $data3, $data4, $keywords2, $description2, $sor, $open_text_mysor, $main_text_mysor);
    break;

    case "base_pages_edit_page":
    if (!isset($red)) $red = 0;
    base_pages_edit_page($pid, $red);
    break;

    case "base_pages_edit_sv_page":
    if (!isset($link_foto)) $link_foto = "";
    if (!isset($mainpage)) $mainpage = "";
    $title = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $title));
    $open_text = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $open_text));
    $main_text = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $main_text));
    $keywords2 = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $keywords2));
    $description2 = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $description2));
    $search = str_replace("nion ", "nion&nbsp;", str_replace("NION ", "NION&nbsp;", $search));
    if (!isset($add)) $add = "";
    if (!isset($open_text_mysor)) $open_text_mysor = "";
    if (!isset($main_text_mysor)) $main_text_mysor = "";
        base_pages_edit_sv_page($pid, $module, $cid, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $price, $add, $data1, $data2, $data3, $data4, $keywords2, $description2, $com, $cop, $count, $sor, $open_text_mysor, $main_text_mysor);
    break;

    case "base_pages_delit_page":
    if (!isset($ok)) $ok = 0; else $ok = 1;
    base_pages_delit_page($name, $pid, $ok);
    break;

    case "base_pages_status_page":
    base_pages_status_page($name, $pid);
    break;

    case "base_pages_delit_comm":
    base_pages_delit_comm($cid, $ok, $pid);
    break;

    case "delete_noactive_comm":
    base_delit_noactive_comm("noactive");
    break;

    case "delete_system_comm":
    base_delit_noactive_comm("system");
    break;

    case "base_delit_comm":
    base_delit_comm();
    break;
    
    case "delete_all_pages":
    delete_all_pages($del);
    break;
    
    case "base_pages_re":
    base_pages_re($link);
    break;
}
}
?>
