<?php 
require_once('page/functions_users.php');
global $soderganie, $prefix, $db, $design, $data1;
// получаем список доспных разделов для добавления
$id1 = $_GET["group"];
$id = $_GET["id"];
$result = $db->sql_query("select text from ".$prefix."_mainpage where `id`='$id1'");
$row = $db->sql_fetchrow($result);
$catspisok = substr($row['text'],0,-1);
$soderganie .= "<div id='list_razdel'>".ss("Выберите раздел")."<br>";
$result2 = $db->sql_query("select title, name from ".$prefix."_mainpage where id IN (".$catspisok.")");
while ($row = $db->sql_fetchrow($result2)) {
  $soderganie .= '<a href="adduser_'.$_COOKIE['user_group'].'_'.$row['name'].'">'.$row['title'].'</a><br>';
}
$soderganie .= '</div>';
if ($_POST["submit"] == ss("Добавить")) {
  if(!$_POST["title"] || !$_POST["open_text"] || !$_POST["main_text"])
    $soderganie .= "<p class='errormes'>".ss("Вы ввели не все данные, попробуйте еще раз")."</p>";
  else {
    $open_text == strip_tags($_POST["open_text"]);
    $main_text == strip_tags($_POST["main_text"]);
    $title == strip_tags($_POST["title"]);
    /*
    // это галерея?
    $sql = "select text from ".$prefix."_mainpage where name='".$id."' and type='2'"; 
    $result = $db->sql_query($sql); 
    $row = $db->sql_fetchrow($result); 
    $tex = $row['text'];
    if (strpos($tex,"media=1")) {
      $ImgDir="img"; 
      if (trim($link_foto)=="/img/" or trim($link_foto)=="") {
        // Обработка имени файла: транслит и удаление пробелов
        $pic_name2 = date("Y-m-d_H-i-s_", time()).str_replace(" ","",translit($_FILES["foto"]["name"]));
      	if (Copy($_FILES["foto"]["tmp_name"], $ImgDir."/".basename($pic_name2))) {
        	unlink($_FILES["foto"]["tmp_name"]); 
          chmod($ImgDir."/".basename($pic_name2),0644); $foto="/".$ImgDir."/".basename($pic_name2);
      	} else echo "ОШИБКА при копировании файла"; 
      } else $foto=trim($link_foto); 
    } else $foto="";
    */
    // это магазин?
    $price = intval($_POST["price"]);	
    // прочее
    $sor = "0"; 
    $rss = "1"; 
    $active = "1";
    $cid = intval($_POST["categor"]); 
    $user = intval($_COOKIE['user_id']); 
    $data = $now; 
    $data2 = $now;
    $sql = "INSERT INTO ".$prefix."_pages VALUES (NULL, '".$id."', '".$cid."', '".$title."', '".$open_text."', '".$main_text."', '".$data."', '".$data2."', '0', '".$active."', '0', '0', '".$foto."', '', '', '".$rss."', '".$price."', '".$description2."', '".$keywords2."', 'pages', '0','".$sor."','".$user."');";
    $db->sql_query($sql) or die (ss("Не удалось сохранить страницу. Попробуйте нажать в Редакторе на кнопку Чистка HTML в Редакторе. Если всё равно появится эта ошибка - сообщите разработчику нижеследующее:").$sql);
    // Узнаем получившийся номер страницы ID
    $sql = "select `pid` from ".$prefix."_pages where `title`='".$title."' and `date`='".$data."'";
    $result = $db->sql_query($sql); 
    $row = $db->sql_fetchrow($result);
    $page_id = $row['pid'];
      // РАБОТА СО СПИСКАМИ
    if (!isset($_POST["add"]) or $_POST["add"] == "") $_POST["add"] = array();
    foreach ($_POST["add"] as $name => $elements) {
      // Получение информации о каждом списке
      if ($name != "") {
        $sql = "select * from ".$prefix."_mainpage where `name`='".$name."' and `type`='4'";
        $result = $db->sql_query($sql); 
        $row = $db->sql_fetchrow($result); 
        $s_id = $row['id'];
        $options = explode("|", $row['text']); 
        $options = $options[1];
        $type = 0;
        $shablon = ""; 
        parse_str($options); // раскладка всех настроек списка
        switch($type) {
          case "4": 
            $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', ' ".$page_id." ', '0');") or die (ss("Ошибка: Не удалось сохранить список.")." 3");
          break;
          case "3": // период времени создаем диапазон дат и все их проверяем
            $elements = explode("|",$elements);
            $dat1 = date2normal_view($elements[0], 1);
            $dat2 = date2normal_view($elements[1], 1);
            $period = period($dat1, $dat2);
            // и все даты проверяем на наличие в БД
            $upd = array();
            $noupd = array();
            $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' order by `name`";
            $result = $db->sql_query($sql);
            while ($row = $db->sql_fetchrow($result)) {
              $nam = $row['name']; // дата
              $pag = trim($row['pages']); // страницы
              if (in_array($nam, $period)!=FALSE) $noupd[] = $nam; // для INSERT
            }
            $insert = array(); 
            foreach ($period as $per) { 
              if (!in_array($per, $noupd)) $insert[] = "(NULL, '".$name.", '".$per."', '', '0', ' ".$page_id." ', '0')"; 
            }
            $insert = implode(", ",$insert);
            $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='".$name."' and (".$update.") order by name"; 
            $result = $db->sql_query($sql);
            while ($row = $db->sql_fetchrow($result)) {
              $na = $row['name']; // дата
              $pa = $row['pages']; // страницы
            }
            if (trim($insert) != "") { 
              $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES ".$insert.";") or die (ss("Ошибка: Не удалось сохранить списки.")." 5");
            }
          break;
          case "2": // файл НЕОКОНЧЕНО!
          break;
          case "1":
            $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', ' ".$page_id." ', '0');") or die (ss("Ошибка: Не удалось сохранить список.")." 8");
          break;
          case "0": // список
            // Проверяем сколько элементов в списке
            $num = count($elements);
            for ($x=0; $x < $num; $x++) { // посчитали сколько номеров списка
              if ($elements[$x] != 0) {
                // узнаем какие страницы уже есть у этого номера из списка
                $sql = "SELECT `pages` FROM ".$prefix."_spiski WHERE `id`='".$elements[$x]."'";
                $result = $db->sql_query($sql);
                $row = $db->sql_fetchrow($result);
                $s_pages = $row['pages'];
                if (strpos($agent, " ".$page_id." ") < 1) {
                  $s_pages .= " ".$page_id." ";
                  $s_pages = str_replace("  "," ",$s_pages);
                  // теперь присвоем каждому из элементов списка id страницы, которую редактируем.
                  $db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".$s_pages."' WHERE `id`='".$elements[$x]."'") or die(ss("Ошибка при добавлении страницы в элемент списка.")." 9. $name");
                }
              }
            }
          break;
        }
      }
    }
  }
  $soderganie .= "<p class='errormes'>".ss("Материал добавлен!")."</p><br>";
  recash('users_'.$_COOKIE['user_id']);
}

if($_GET["id"] != "0") {
  // получаем список доспных разделов для добавления
  $soderganie .= "<form class='regforma' action='adduser_".$_COOKIE['user_group']."_".$id."' method='post' enctype='multipart/form-data'>".ss("Выберите категорию:")."<select name='categor'>";
  $result = $db->sql_query("select `title`, `cid` from ".$prefix."_pages_categories where `module`='".$id."'");
  while ($row = $db->sql_fetchrow($result)) {
    $soderganie .= '<option value="'.$row['cid'].'">'.$row['title'].'</option>';
  }
	$soderganie .= '</select><br>';
	$soderganie .= "<h2>".ss("Название (заголовок)")."</h2>
  <textarea class=big name=title rows=1 cols=10 style='font-size:16pt; width:100%;'></textarea><br>
	 <h2>".ss("Краткое содержание")."</h2><textarea cols=80 id=editor name=open_text rows=10></textarea><br>
	 <h2>".ss("Основной текст")."</h2><textarea cols=80 id=edit name=main_text rows=15></textarea><br>";
	   $sql = "select `text` from ".$prefix."_mainpage where `name`='".$id."' and `type`='2'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $tex = $row['text'];
  // это галерея?
  if (strpos($tex,"view=5")) $soderganie .= "<b>".ss("Фото (для фотогалереи)").":</b> 
    <input type='file' name='foto' size='40'><br>
  <b>".ss("или ссылка").":</b> <input type='text' name='link_foto' value='/img/' size='40'><br>
    ".ss("Ссылку на другие сайты начинать с http://")."<br>";
  else $soderganie .= "<input type='hidden' name='foto' value=''>";
  // это магазин?
  if (strpos($tex,"view=3")) $soderganie .= "<b>".ss("Стоимость:")."</b> 
    <input type='text' name='price' size='3' value='0'> ".ss("руб.")."<br>";
  else $soderganie .= "<input type='hidden' name='price' value=''>";
// Подсоединие списков
$result = $db->sql_query("select `id` from ".$prefix."_mainpage where `name`='".$id."'");
$row = $db->sql_fetchrow($result); $cat = $row['id'];
  $sql = "select `id`, `title`, `name`, `text` from ".$prefix."_mainpage where `useit` IN (".$cat.") and `type`='4' order by `title`";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $s_id = $row['id'];
    $s_title = $row['title'];
    $s_name = $row['name'];
    $options = explode("|", $row['text']); $options = $options[1];
    $type=0; $shablon=""; 
    parse_str($options); // раскладка всех настроек списка
    switch($type) {
      case "4": // строка
        $soderganie .="<br><br><b>".$s_title.":</b><br><INPUT name='add[".$s_name."]' type=text value='".$shablon."' style='width:98%;'>";
      break;
      ///////////////////
      case "3": // период времени
        $soderganie .="<br><br><b>".$s_title.":</b> (".ss("выберите даты из меню, кликнув по значкам").")<br>
        <TABLE cellspacing=0 cellpadding=0 style='border-collapse: collapse'><TBODY><TR> 
        <TD><INPUT type=text name='text[".$s_name."]' id='f_date_c[".$s_name."]' value='' onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD>
        <TD><IMG src=/images/calendar.png id='f_trigger_c[".$s_name."]' title='".ss("Выбор даты")."'></TD>
        <TD width=20 align=center> - </TD>
        <TD><INPUT type=text name='text[".$s_name."]' id='f_date_c2[".$s_name."]' value='' onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD> 
        <TD><IMG src=/images/calendar.png id='f_trigger_c2[".$s_name."]' title='".ss("Выбор даты")."'></TD>
        </TR></TBODY></TABLE>
        <SCRIPT type='text/javascript'> 
            Calendar.setup({
                inputField     :    \"f_date_c[".$s_name."]\",     // id of the input field
                ifFormat       :    \"%e %B %Y\",      // format of the input field
                button         :    \"f_trigger_c[".$s_name."]\",  // trigger for the calendar (button ID)
                align          :    \"Tl\",           // alignment (defaults to \"Bl\")
                singleClick    :    true
            });
        </SCRIPT>
        <SCRIPT type='text/javascript'> 
            Calendar.setup({
                inputField     :    \"f_date_c2[".$s_name."]\",     // id of the input field
                ifFormat       :    \"%e %B %Y\",      // format of the input field
                button         :    \"f_trigger_c2[".$s_name."]\",  // trigger for the calendar (button ID)
                align          :    \"Tl\",           // alignment (defaults to \"Bl\")
                singleClick    :    true
            });
        </SCRIPT>
        <input type=hidden name='add[".$s_name."]' id='add[".$s_name."]' value='".ss("дата")."'>"; //
      break;
      ///////////////////
      case "2": // файл file=pic&papka=/img=verh&resizepic=x&file=&picsize=600&minipic=1&resizeminipic=x&minipicsize=100
        switch($fil) {
          case "pic": 
            $type_fil = ss("картинка"); 
          break; 
          case "doc": 
            $type_fil = ss("документ/архив"); 
          break; 
          case "flash": 
            $type_fil = ss("flash-анимация"); 
          break; 
          case "avi": 
            $type_fil = ss("видео-ролик"); 
          break;
        }
        switch($mesto) {
          case "verh": 
            $type_mesto = ss("сверху"); 
          break; 
          case "niz": 
            $type_mesto = ss("снизу"); 
          break;
        }
        $type_mini="";
        if ($minipic==1) $type_mini = ss("Также будет создана миниатюра.");
        $soderganie .="<br><br><b>".$s_title.":</b><br><input type='file' name='add[".$s_name."]' size='30'> 
        <b>".ss("или ссылка").":</b> <input type='text' name='add[".$s_name."]_link' value='".$papka."' size='30'><br>
        ".ss("Файл")." (".$type_fil.") ".ss("сохранится в")." ".$papka.", ".ss("на странице будет")." ".$type_mesto.". ".$type_mini;
      break;
      case "1": // текст
        $soderganie .="<br><br><b>".$s_title.":</b><br><textarea name='add[".$s_name."]' rows='4' cols='60' style='width:98%;'>".$shablon."</textarea>";
      break;
      case "0": // список слов
        $soderganie .="<br><br><b>".$s_title.":</b><br>";
        $sql2 = "select * from ".$prefix."_spiski where `type`='".$s_name."' order by `parent`, `id`";
        $result2 = $db->sql_query($sql2);
        $soderganie .="<select size='10' multiple='multiple' name='add[".$s_name."][]' style='font-size:11px;'><option value=0> ".ss("не выбрано")." </option>";
        while ($row2 = $db->sql_fetchrow($result2)) {
         $s_id2 = $row2['id'];
         $s_title2 = $row2['name'];
         $s_opis = $row2['opis'];
         $s_parent = $row2['parent'];
         $s_title2 = getparent_spiski($s_name,$s_parent,$s_title2);
         $sel = ""; 
         if ($razdel == $s_id2) $sel = " selected";
         $soderganie .= "<option value=".$s_id2.$sel."> ".$s_title2." (".$s_opis.")</option>";
        }
        $soderganie .= "</select>";
      break;
    }
  }
  $soderganie .= '<br><input type="submit" name="submit" value="'.ss("Добавить").'"></form>';
}
list($design_for_reg, $stil) = design_and_style($design);
if ($design_for_reg == "0") die(ss("Ошибка: «Адрес раздела»")." (".$name.") ".ss("введен неправильно. Перейдите на")." <a href=/>".ss("Главную страницу")."</a>.");
$block = str_replace(ss("[содержание]"), $soderganie, $design_for_reg);
return array($block, $stil);
?>