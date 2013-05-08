<?php
//if (!eregi("sys.php", $_SERVER['PHP_SELF']))
if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
$aid = trim($aid);
global $prefix, $db, $red;
$row = $db->sql_fetchrow($db->sql_query("select realadmin from ".$prefix."_authors where aid='$aid'"));
$realadmin = $row['realadmin'];
if ($realadmin==1) {
$tip = "spiski";
$admintip = "base_spisok";

function getparent($name, $parent, $title) {
    global $tip, $admintip, $prefix,$db;
    $sql = "select name, parent from ".$prefix."_".$tip." where type='$name' and id='$parent'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    //$cid = $row[cid];
    $ptitle = $row['name'];
    $pparent = $row['parent'];
    if ($ptitle!="") $title=$ptitle."/".$title;
    if ($pparent!=0) {
        $title=getparent($pparent,$title);
    }
    return $title;
}
##################################################################################################
function menu() {
    global $name, $tip, $admintip, $prefix, $db;
    echo "<center><a href=/sys.php?op=mainpage><b>Вернуться к содержанию</b></a> | <a href=sys.php?op=".$admintip."&name=$name#1><b>Список $name</b></a> (перечень или шаблон)</center>";
    }
##################################################################################################
function base_spisok_derevo($names, $parents, $parent, $mesto) {
global $name, $admintip;
$derevo = "";
$ver = mt_rand(10000, 99999); // получили случайное число
// Пербираем названия папок текущего уровня
foreach ($names as $id => $nam) {
$par = $parents[$id];
if ($par == $parent) {
$derevo .= "<li class='li_mesto_".$mesto."'> <a class=no href=#".$ver.$id." onclick='men3(".$id.", \"".$name."\", \"".$admintip."\");'>".$nam."</a> ".$nowork." <div id='id".$id."' style='display:inline;'></div></li>";
// Показываем подпапки
$de = base_spisok_derevo($names, $parents, $id, $mesto+1);
if ($de=="") $derevo .= "";
else $derevo .= "<li class='li_next li_mesto_".$mesto."'> ".$de."</li>";
}
}
return $derevo;
}

##################################################################################################
function base_spisok($name) {
    global $name, $tip, $admintip, $prefix, $db, $bgcolor1, $bgcolor2, $spisoknum, $bgcolor3, $bgcolor4;
    include("ad-header.php");
    echo "<a name=1></a>";
    menu();
// Определяем все папки
$names = array();
$parents = array();
$sql = "SELECT * FROM ".$prefix."_".$tip." where type='$name' ORDER BY parent, name";
$result = $db->sql_query($sql);
while ($rows = $db->sql_fetchrow($result)) {
$id = $rows['id'];
$names[$id] = $rows['name'];
$parents[$id] = $rows['parent'];
}

// Генерируем дерево папок и страниц с помощью функции рекурсии
echo "<hr>
<table width=90% align=center><tr valign=top><td>".base_spisok_derevo($names, $parents, 0, 0)."</td><td>


<form action=sys.php method=post><b>Добавить элемент:</b><br>
<input type=text name=title size=30><input type=hidden name=op value=".$admintip."_add_category><input type=hidden name=name value=".$name."><br>вложенный в:<br>";
           $sql = "select id,name,parent from ".$prefix."_".$tip." where type='$name' order by parent, id";
           $result = $db->sql_query($sql) or die ('Ошибка: Не могу показать категории');
           echo "<select name=parent_id><option value=0>корень списка (основной)</option>";
           while ($row = $db->sql_fetchrow($result)) {
           $id2 = $row['id'];
           $title = $row['name'];
           $parent = $row['parent'];
	   $title = getparent($name,$parent,$title);
	   echo "<option value=$id2>$title</option>";
           }
echo "</select><br><input type=submit value=\"Создать\" style='width:200px; height:40px;'></form><br>
</td></tr></table>";

admin_footer(); //include("ad-footer.php");
}
#####################################################################################################################
function base_spisok_add_spisok($name, $razdel, $red=0) {
    global $tip, $admintip, $prefix, $db, $redaktor, $toolbars;
    include("ad-header.php");
    echo "<a name=1></a>";
    menu();
    $red = intval($red);
$sql = "select shablon from ".$prefix."_mainspisok where name='$name' and type='2'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$shablon = $row['shablon'];
           
echo "<center><h3>Добавление страницы модуля $name</h3></center>";
echo "<form method=\"POST\" action=\"sys.php?red=$red\" enctype=\"multipart/form-data\">
<table width=100%><tr valign=top><td align=center>
<b>Выберите раздел:</b><br>";
           $sql = "select * from ".$prefix."_".$tip."_categories where module='$name' order by parent_id,cid";
           $result = $db->sql_query($sql);
           $numrows = $db->sql_numrows($result);
           if ($numrows > 35) $size = 35; else $size=$numrows+1;
           echo "<select name=cid size=$size style='font-size:11px;'><option value=0 selected>не выбран</option>";
           while ($row = $db->sql_fetchrow($result)) {
           $cid2 = $row['cid'];
           $title = $row['title'];
           $parentid = $row['parent_id'];
	   $title = getparent($name,$parentid,$title);
           if ($razdel == $cid2) $sel = "selected"; else $sel = "";
	   echo "<option value=$cid2 $sel>$title</option>";
           }
echo "</select><br><br><input type=submit value=\" Сохранить \" style='width:95%; height:35px;'>
</td><td>
<b>Название страницы:</b><br><textarea name=\"title\" rows=\"2\" cols=\"60\"></textarea><br>
<b>Вступительный текст:</b><br><textarea name=\"open_text\" rows=\"5\" cols=\"60\"></textarea><br>
<b>Основной текст:</b><br>";

if ($red==0) {

} elseif ($red==2) {
echo "<textarea cols=80 id=editor name=main_text rows=10>".$shablon."</textarea>
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
echo "<textarea name=\"main_text\" rows=\"15\" cols=\"80\">".$shablon."</textarea><br>";
html_spravka();
}


// узнать - это галерея или нет
$sql = "select text from ".$prefix."_mainspisok where name='$name' and type='2'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$tex = $row['text'];
if (strpos($tex,"media")) echo "<b>Фото (для фотогалереи):</b> <input type=file name=foto size=40> 
<b>или ссылка:</b> <input type=text name=link_foto value=\"/img/\" size=40><br>";

else echo "<input type=hidden name=foto value=\"\">";
echo "<b>Схожие слова (теги):</b> <textarea name=\"search\" rows=\"2\" cols=\"60\"></textarea><p>
<input type=checkbox name=mainspisok value=1 unchecked> <b>Ставить на Главную страницу </b><p>
<input type=checkbox name=active value=1 checked> <b>Включить страницу (ссылка на страницу будет видна всем)</b><p>
<input type=hidden name=op value=".$admintip."_save_spisok>
<input type=hidden name=module value=".$name.">
</td></tr></table>
</form>";
admin_footer(); //include("ad-footer.php");
}
#####################################################################################################################
function base_spisok_save_spisok($cid, $module, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainspisok) {
global $red, $tip, $admintip, $prefix, $db, $admin_file;
##----------------------------------------------------##
// узнать - это галерея или нет
$sql = "select text from ".$prefix."_mainspisok where name='$module' and type='2'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$tex = $row[text];
if (strpos($tex,"media")) {
$ImgDir="img";
if (trim($link_foto)=="/img/" or trim($link_foto)=="") {
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
    $title = filter(trim($title), "nohtml");
    $open_text = stripslashes(FixQuotes($open_text)); // filter($open_text, "", 1);
    $main_text = stripslashes(FixQuotes($main_text)); // filter($main_text, "", 1);
    $search = str_replace(", "," ",$search);
    $search = str_replace(","," ",$search);
    $search = str_replace(". "," ",$search);
    $search = str_replace("."," ",$search);
    $search = str_replace("  "," ",$search);
    $search = " ".trim(strtolow($search))." ";
# pid module cid title open_text main_text date counter active golos comm foto search mainspisok
 $db->sql_query("INSERT INTO ".$prefix."_".$tip." (pid, module, cid, title, open_text, main_text, date, counter, active, golos, comm, foto, search, mainspisok) VALUES (NULL, '$module', '$cid', '$title', '$open_text', '$main_text', now(), '0', '$active', '0', '0', '$foto', '$search', '$mainspisok')");
Header("Location: sys.php?op=base_spisok_add_spisok&name=$module&razdel=$cid&red=$red");
}

#####################################################################################################################
function base_spisok_edit_spisok($name, $pid, $red=0) {
global $tip, $admintip, $prefix, $db; //, $redaktor, $toolbars;
    $sql = "SELECT * FROM ".$prefix."_".$tip." WHERE pid='$pid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $cid = $row['cid'];
    $titl = $row['title'];
    $open_text = $row['open_text'];
    $main_text = $row['main_text'];
    $foto = $row['foto'];
    // узнать - это галерея или нет
$sql2 = "select text from ".$prefix."_mainspisok where name='$module' and type='2'";
$result2 = $db->sql_query($sql2);
$row2 = $db->sql_fetchrow($result2);
$tex = $row2['text'];
if (!strpos($tex,"media")) $foto = "";
#######################################
    $search = $row['search'];
    $date = $row['date'];
    $counter = $row['counter'];
    $active = $row['active'];
    $comm = $row['comm'];
    $mainspisok = $row['mainspisok'];
    include("ad-header.php");
    menu();
echo "<center><h3>Редактирование страницы модуля $name</h3></center>";
echo "<form method=\"POST\" action=\"sys.php\" enctype=\"multipart/form-data\">
<table width=100%><tr valign=top><td align=center>
<b>Выберите папку:</b><br>";
           $sql = "select * from ".$prefix."_".$tip."_categories where module='$name' order by parent_id,cid";
           $result = $db->sql_query($sql);
           $numrows = $db->sql_numrows($result);
           if ($numrows > 35) $size = 35; else $size=$numrows+1;
           echo "<select name=cid size=$size style='font-size:11px;'><option value=0>не выбран</option>";
           while ($row = $db->sql_fetchrow($result)) {
           $cid2 = $row['cid'];
           $title = $row['title'];
           $parentid = $row['parent_id'];
	   $title = getparent($name,$parentid,$title);
	   if ($cid == $cid2) $sel = "selected"; else $sel = "";
	   echo "<option value=$cid2 $sel>$title</option>";
           }
echo "</select><br><br><input type=submit value=\" Сохранить\nизменения \" style='width:95%; height:55px;'>
</td><td>
<b>Название страницы:</b><br><textarea name=\"title\" rows=\"2\" cols=\"60\">$titl</textarea><br>
<b>Вступительный текст:</b><br><textarea name=\"open_text\" rows=\"5\" cols=\"60\">$open_text</textarea><br>
<b>Основной текст:</b><br>";
if ($red==0) {
} elseif ($red==2) {
echo "<textarea cols=80 id=editor name=main_text rows=10>".$main_text."</textarea>
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
echo "<textarea name=\"main_text\" rows=\"15\" cols=\"80\">".$main_text."</textarea><br>";
html_spravka();
}
// узнать - это галерея или нет
$sql = "select text from ".$prefix."_mainspisok where name='$name' and type='2'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$tex = $row['text'];
if (strpos($tex,"media")) echo "<b>Фото (для фотогалереи):</b> <input type=file name=foto size=40> 
<b>или ссылка:</b> <input type=text name=link_foto value=\"$foto\" size=40><br>";
else echo "<input type=hidden name=foto value=\"\">";
// Сделать выбор закачанных изображений!
echo "<b>Схожие слова (теги):</b> <textarea name=\"search\" rows=\"2\" cols=\"60\">$search</textarea><p>";
if ($mainspisok==1) $check= " checked"; else $check= " unchecked";
echo "<input type=checkbox name=mainspisok value=1$check> <b>Ставить на главную страницу</b><p>";
if ($active==1) $check= " checked"; else $check= " unchecked";
echo "<input type=checkbox name=active value=1$check> <b>Включить страницу (ссылка на страницу будет видна всем)</b><p>
<input type=hidden name=op value=".$admintip."_edit_sv_spisok>
<input type=hidden name=module value=".$name.">
<input type=hidden name=pid value=$pid>
</td></tr></table>
</form>";
admin_footer(); //include("ad-footer.php");
}
#####################################################################################################################
function base_spisok_edit_sv_spisok($pid, $module, $cid, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainspisok) {
global $tip, $admintip, $prefix, $db;
##----------------------------------------------------##
// узнать - это галерея или нет
$sql = "select text from ".$prefix."_mainspisok where name='$module' and type='2'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$tex = $row[text];
if (strpos($tex,"media")) {
$ImgDir="img";
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
    $title = filter(trim($title), "nohtml");
    $open_text = stripslashes(FixQuotes($open_text)); // filter($open_text, "", 1);
    $main_text = stripslashes(FixQuotes($main_text)); // filter($main_text, "", 1);
    $search = str_replace(", "," ",$search);
    $search = str_replace(","," ",$search);
    $search = str_replace(". "," ",$search);
    $search = str_replace("."," ",$search);
    $search = str_replace("  "," ",$search);
    $search = " ".trim(strtolow($search))." ";

# pid cid title open_text main_text foto search date counter active comm
$db->sql_query("UPDATE ".$prefix."_".$tip." SET cid='$cid', module='$module', title='$title', open_text='$open_text', main_text='$main_text', foto='$foto', search='$search', active='$active', mainspisok='$mainspisok' WHERE pid='$pid'");
Header("Location: sys.php?op=".$admintip."&name=$module");
}
#####################################################################################################################
function base_spisok_delit_spisok($name,$pid, $ok) {
    global $tip, $admintip, $prefix, $db;
    $cid = intval($cid);
    if($ok) {
    $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE pid='$pid'");
    Header("Location: sys.php?op=".$admintip."&name=$name#1");
    }
    else {
    $sql = "select title from ".$prefix."_".$tip." where pid='$pid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $title = $row['title'];

    include("ad-header.php");
    menu();
    echo "<br><center><b>Несанкционированное удаление страницы</b><br><br>";
    echo "Внимание! Вы хотите удалить страницу <b>$title</b>.<br><br>";
    echo "Это правда?<br><br>[ <a href=\"sys.php?op=".$admintip."&name=$name#1\"><b>НЕТ</b></a> | <a href=\"sys.php?op=".$admintip."_delit_spisok&name=$name&pid=$pid&ok=1\"><b>ДА</b></a> ]</center><br><br>";
    admin_footer(); //include("ad-footer.php");
    }
}
#####################################################################################################################
switch ($op) {
    case "base_spisok":
    base_spisok($name);
    break;

    case "base_spisok_add_spisok":
    base_spisok_add_spisok($name, $razdel, $red);
    break;

    case "base_spisok_save_spisok":
base_spisok_save_spisok($cid, $module, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainspisok);
    break;

    case "base_spisok_edit_spisok":
    base_spisok_edit_spisok($name, $pid, $red);
    break;

    case "base_spisok_edit_sv_spisok":
    base_spisok_edit_sv_spisok($pid, $module, $cid, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainspisok);
    break;

    case "base_spisok_delit_spisok":
    base_spisok_delit_spisok($name, $pid, $ok);
    break;
}
}
?>
