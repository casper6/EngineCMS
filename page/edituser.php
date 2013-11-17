<?php
require_once('page/functions_users.php');
global $soderganie, $prefix, $db, $design;
$soderganie .= "<form class=regforma' action='--edituser_".$_COOKIE['user_id']."' method='post'>
	    <br><input class='regname' type='name' name='user_name' value='' placeholder='".$_COOKIE['user_name']."'>
		<br><input type='submit' name='submit' value='".ss("Обновить имя")."'></form>";
if ($_POST["submit"] == ss("Обновить имя")) {
  if (!$_POST["user_name"]) {
    $soderganie .= "<p class='errormes'>".ss("Вы не ввели своё имя")."</p>";
  } else {
    $user_name = $_POST["user_name"];
    $db->sql_query("UPDATE ".$prefix."_users SET `name`='$user_name' WHERE `user_id`='".$_COOKIE['user_id']."' ;"); 
    user_set_tokens($_COOKIE['email']);
    $soderganie .= '<p class="errormes">'.ss("Имя обновленно, дождитесь обновления страницы").' <meta http-equiv="Refresh" content="6"/>';
  }
}
$soderganie .= "<form class='regforma' action='--edituser_".$_COOKIE['user_id']."' method='post' enctype='multipart/form-data'>
<br><input class='regfile' type='file' name='file' placeholder='".ss("Выберите фотографию")."'>
<br><input type='submit' name='submit2' value='".ss("Обновить фото")."'></form>";
if ($_POST["submit2"] == ss("Обновить фото")) { 
  if (!$_FILES["file"]["size"]) $soderganie .= "<p class='errormes'>".ss("Вы не выбрали фотографию")."</p>";
  else {
    // удаляем старое фото
    unlink($_COOKIE['user_pfoto']);
    // новое имя файла
    $cn_foto = md5(date("Y-M-D-h-m-s"));
    // проверяем размер файла
    if($_FILES["file"]["size"] > 5242880) { // 1024*5*1024
      $soderganie .= "<p class='errormes'>".ss("Размер файла превышает пять мегабайт")."</p>";
    } else {
      // Проверяем загружен ли файл
      if(is_uploaded_file($_FILES["file"]["tmp_name"])) {
        // проверяем расширение файла
        if($_FILES["file"]['type'] == "image/gif" || 
          $_FILES["file"]['type'] == "image/png" || 
          $_FILES["file"]['type'] == "image/jpg" || 
          $_FILES["file"]['type'] == "image/jpeg") {
          $imgname = str_replace("image/", ".", $_FILES["file"]['type']);
          move_uploaded_file($_FILES["file"]["tmp_name"], "img/user/".$cn_foto.$imgname);
          $photo = "img/user/".$cn_foto.$imgname;
          $db->sql_query("UPDATE ".$prefix."_users SET `photo`='$photo' WHERE `user_id`='".$_COOKIE['user_id']."' ;"); 
          user_set_tokens($_COOKIE['email']);
          $soderganie .= ss("Фото обновлено, дождитесь обновления страницы");
          $soderganie .= '<meta http-equiv="Refresh" content="6"/>';
        } else {
            $soderganie .= "<p class='errormes'>".ss("Можно загружать только изображения в форматах jpg, jpeg, gif и png.");
        }
      } else {
        $soderganie .= "<p class='errormes'>".ss("Ошибка загрузки файла")."</p>";
      }
    }
  }
}
// доп инфа о пользователе через списки
$numrows = $db->sql_numrows($db->sql_query("SELECT id FROM ".$prefix."_mainpage where `useit`= '1,".$_COOKIE['user_group']."' and type='4'"));
if ($numrows > 0) {
  if($_POST["submit2"] == ss("Обновить")) {
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
        $type=0; 
        $shablon=""; 
        parse_str($options); // раскладка всех настроек списка
        switch($type) {
          ////////////////////////////////////////////////////////////////////////////
          case "4":
            $numrows = $db->sql_numrows($db->sql_query("SELECT id FROM ".$prefix."_spiski where where `type`='$name' and pages='1,".$_COOKIE['user_id']."'"));
            if ($numrows2 == 1) {
            $db->sql_query("UPDATE ".$prefix."_spiski SET `name`='$elements' WHERE `type`='$name' and pages='1,".$_COOKIE['user_id']."';") or die (ss("Ошибка: Не удалось сохранить список.")." 8");
            } else  {
            $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', '1,".$_COOKIE['user_id']."', '0');") or die (ss("Ошибка: Не удалось сохранить список.")." 3");
            } 
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
                  $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' order by `name`";
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
                    $update[] = "name='".$up."'";
                  }
                  foreach ($period as $per) {
                    if (!in_array($per, $noupd)) $insert[] = "(NULL, '".$name.", '".$per."', '', '0', ' ".$page_id." ', '0')";
                  }
                  $insert = implode(", ",$insert);
                  $update = implode(" or ",$update);
                  $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and (".$update.") order by `name`";
                  $result = $db->sql_query($sql);
                  while ($row = $db->sql_fetchrow($result)) {
                  $na = $row['name']; // дата
                  $pa = $row['pages']; // страницы
                      if (trim($update) != "") {
                      $db->sql_query("UPDATE ".$prefix."_spiski SET `pages` = ' $pa $page_id ' WHERE `type`='".$name."' and `name`='".$na."'") or die (ss("Ошибка: Не удалось обновить списки.")." 4 $page_id $name");
                      }
                  }
                  if (trim($insert) != "") {
                    $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES ".$insert.";") or die (ss("Ошибка: Не удалось сохранить списки.")." 5");
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
          case "1":
            $numrows2 = $db->sql_numrows($db->sql_query("SELECT id FROM ".$prefix."_spiski where `type`='$name' and pages='1,".$_COOKIE['user_id']."'"));
            if ($numrows2 == 1) $db->sql_query("UPDATE ".$prefix."_spiski SET `name`='$elements' WHERE `type`='$name' and pages='1,".$_COOKIE['user_id']."';") or die (ss("Ошибка: Не удалось сохранить списки.")." 8");
            else $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', '1,".$_COOKIE['user_id']."', '0');") or die (ss("Ошибка: Не удалось сохранить списки.")." 3");
          break;
          ////////////////////////////////////////////////////////////////////////////
          case "0": // список
                  // Проверяем сколько элементов в списке
                  $num = count($elements);
                  for ($x=0; $x < $num; $x++) { // посчитали сколько номеров списка
                      if ($elements[$x] != 0) {
                      // узнаем какие страницы уже есть у этого номера из списка
                      $sql = "SELECT pages FROM ".$prefix."_spiski WHERE id='".$elements[$x]."'";
                      $result = $db->sql_query($sql);
                      $row = $db->sql_fetchrow($result);
                      $s_pages = $row['pages'];
                      if (strpos($agent," $page_id ") < 1) {
                      $s_pages .= " $page_id ";
                      $s_pages = str_replace("  "," ",$s_pages);
                      // теперь присвоем каждому из элементов списка id страницы, которую редактируем.
                      $db->sql_query("UPDATE ".$prefix."_spiski SET pages='".$s_pages."' WHERE id='".$elements[$x]."'") or die(ss("Ошибка при добавлении страницы в элемент списка.")." 9. $name");
                      }
              
                      }
                  }
          break;  
        } // end switch
      } // end if
    } // end foreach
  } // end if
  $soderganie .= "<form class='regforma' action='--edituser_".$_COOKIE['user_id']."' method='post' enctype='multipart/form-data'>";
  $sql = "select `id`, `title`, `name`, `text` from ".$prefix."_mainpage where `useit`= '1,".$_COOKIE['user_group']."' and `type`='4' order by `title`";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $s_id = $row['id'];
    $s_title = $row['title'];
    $s_name = $row['name'];
    $options = explode("|", $row['text']); $options = $options[1];
    $type=0; 
    parse_str($options); // раскладка всех настроек списка
    switch($type) {
      ///////////////////
      case "4": // строка
        $result = $db->sql_query("select `name` from ".$prefix."_spiski where `type`='".$s_name."' and `pages`='1,".$_COOKIE['user_id']."'"); $row = $db->sql_fetchrow($result);
        $soderganie .="<br><br><b>".$s_title.":</b><br><INPUT name='add[".$s_name."]' type='text' value='".$row['name']."' style='width:98%;'>";
      break;
      ///////////////////
      case "3": // период времени
        $soderganie .="<br><br><b>".$s_title.":</b> (".ss("выберите даты из меню, кликнув по значкам")."<br>
        <TABLE cellspacing=0 cellpadding=0 style='border-collapse: collapse'><TBODY><TR> 
        <TD><INPUT type='text' name='text[".$s_name."]' id='f_date_c[".$s_name."]' value='' onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD>
        <TD><IMG src='/images/calendar.png' id='f_trigger_c[".$s_name."]' title='".ss("Выбор даты")."'></TD>
        <TD width='20' align='center'> - </TD>
        <TD><INPUT type=text name='text[".$s_name."]' id='f_date_c2[".$s_name."]' value='' onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD> 
        <TD><IMG src='/images/calendar.png' id='f_trigger_c2[".$s_name."]' title='".ss("Выбор даты")."'></TD>
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
        $type_mini="";
        if ($minipic==1) $type_mini = ss("Также будет создана миниатюра.");
        $soderganie .="<br><br><b>".$s_title.":</b><br><input type=file name='add[".$s_name."]' size=30> 
        <b>".ss("или ссылка").":</b> <input type=text name='add[".$s_name."]_link' value='".$papka."' size=30><br>
        ".ss("Файл")." (".$type_fil.") ".ss("сохранится в")." ".$papka.", ".ss("на странице будет")." ".$type_mesto.". ".$type_mini."";
      break;
      case "1": // текст
        $result = $db->sql_query("select name from ".$prefix."_spiski where `type`='".$s_name."' and pages='1,".$_COOKIE['user_id']."'");
        $row = $db->sql_fetchrow($result);
        $soderganie .="<br><br><b>".$s_title.":</b><br><textarea name='add[".$s_name."]' rows='4' cols='60' style='width:98%;'>".$row['name']."</textarea>";
      break;
      case "0": // список слов
        $soderganie .="<br><br><b>".$s_title.":</b><br>";
        $sql2 = "select * from ".$prefix."_spiski where type='".$s_name."' order by parent,id";
        $result2 = $db->sql_query($sql2);
        $soderganie .="<select size=10 multiple=multiple name='add[".$s_name."][]' style='font-size:11px;'><option value=0> ".ss("не выбрано")." </option>";
        while ($row2 = $db->sql_fetchrow($result2)) {
          $s_id2 = $row2['id'];
          $s_title2 = $row2['name'];
          $s_opis = $row2['opis'];
          $s_parent = $row2['parent'];
        	$s_title2 = getparent_spiski($s_name,$s_parent,$s_title2);
          $sel = ""; 
          if ($razdel == $s_id2) $sel = " selected";
        	$soderganie .= "<option value='".$s_id2.$sel."'> ".$s_title2." (".$s_opis.")</option>";
        }
      $soderganie .="</select>";
      break;
    } // end switch
  } // end while
  $soderganie .= '<br><input type="submit" name="submit2" value="'.ss("Обновить").'"></form>';
} // end if

list($design_for_reg, $stil) = design_and_style($design);
if ($design_for_reg == "0") die(ss("Ошибка: «Адрес раздела»")." (".$name.") ".ss("введен неправильно. Перейдите на")." <a href=/>".ss("Главную страницу")."</a>."); 
$block = str_replace(ss("[содержание]"),$soderganie,$design_for_reg);
return array($block, $stil);
?>