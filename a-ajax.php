<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
require_once("mainfile.php");
global $prefix, $db, $module_name, $admin, $now, $adminmail, $ip, $siteurl;
if (is_admin($admin)) {
  if (isset($_REQUEST['func']))   $func = $_REQUEST['func']; else die(); // Выбор функции
  if (isset($_REQUEST['type']))   $type = $_REQUEST['type'];
  if (isset($_REQUEST['id']))     $id = intval($_REQUEST['id']);
  if (isset($_REQUEST['string'])) $string = $_REQUEST['string'];
/* ===================================================================================================== */
if ($func == "oformlenie_show") { // Выводим содержание различных оформлений
  // Получаем названия -- заменить?
  $sql = "select id, name, title, useit from ".$prefix."_mainpage where `tables`='pages' and type='2' or type='1' order by title";
  $result = $db->sql_query($sql);
  $module_title = array();
  $module_name = array();
  $module_name = array();
  while ($row = $db->sql_fetchrow($result)) {
       $id = $row['id'];
       $name = $row['name'];
       $module_title[$id] = $row['title'];
       $module_name[$name] = $row['title'];
       $module_name2[$name] = $row['id'];
  }
  $info = "";
  $admintip = "mainpage";
  switch($type) {


  case "design":
    $sql = "select id,title,useit from ".$prefix."_mainpage where `tables`='pages' and type='0' order by title, name";
    $result = $db->sql_query($sql);
    $info .= "<table width=100% class=table_light>";
    if (!isset($nam)) $nam = "";
    while ($row = $db->sql_fetchrow($result)) {
       $useit = explode(" ", trim($row['useit']));
       $css = "";
       foreach( $useit as $value ) {
        if ($value) $css .= " <a href='/sys.php?op=mainpage&id=".$value."' title='Редактировать' class='gray'><i>".$module_title[$value]."</i><img class='icon2 i34' src='/images/1.gif'></a> ";
       }
       if ($css == "") $css = "<i class='red'>стиль не выбран</i><br>"; else $css = "Подключенные стили (css): ".$css."<br>";
       if ($row['title'] != "Главный дизайн") $delx = "<a class='padleft30' href=/sys.php?op=mainpage_del&id=".$row['id']."&type=0&name=".$nam." title='Удалить дизайн ".$row['title']."'><img class='icon2 i21' src='/images/1.gif'></a>";
        else $delx = "<span class='padleft30'><img title='Основной дизайн нельзя удалить' class='icon2 i44' src='/images/1.gif'>";
        
       $info .= "<tr valign='top'><td><h2>".$row['title']." &darr;</h2>
       <div style='float:right; display: inline;'>
       <a href='sys.php?op=mainpage&id=".$row['id']."&red=1' title='Редактировать в HTML'><img class='icon2 i34' src='/images/1.gif'></a> 
       ".$delx."
       </div>";
      ///////
       $sql2 = "select id,name,title from ".$prefix."_mainpage where `tables`='pages' and type='2' and text like '%design=".$row['id']."&%' order by title, name";
       $result2 = $db->sql_query($sql2);
       $numrows2 = $db->sql_numrows($result2);
       if ($numrows2 == 0) $razr = "";
       else {
        $razr = "<div style='overflow:auto; width:90%; height:70px;'>Используется в разделах: <br>";
        while ($row2 = $db->sql_fetchrow($result2)) {
          $id = $row2['id'];
          $razr .= "<a href='/-".$row2['name']."' target='_blank' class='gray'>".$row2['title']."</a> ";
        }
        $razr .= "</div>";
       }
      ///////
       $sql2 = "select id,name,title from ".$prefix."_mainpage where `tables`='pages' and type='3' and useit like '%design=".$row['id']."&%' order by title, name";
       $result2 = $db->sql_query($sql2);
       $numrows2 = $db->sql_numrows($result2);
       if ($numrows2 == 0) $bloc = "";
       else {
        if ($razr != "") $razr .= "<br>";
        $bloc = "Используется в блоках: ";
        while ($row2 = $db->sql_fetchrow($result2)) {
          $id = $row2['id'];
          $bloc .= "[".$row2['title']."] ";
        }
       }
       $info .= "".$css."".$razr.$bloc."</td></tr>";
    }
    $info .= "</table>";
  break;


  case "css":
    $sql = "select * from ".$prefix."_mainpage where `tables`='pages' and type='1' order by title, name";
    $result = $db->sql_query($sql);
    $info = "<table class=table_light width=50% style='min-width:500px;'>";
    while ($row = $db->sql_fetchrow($result)) {
     $useit = explode(" ", trim($row['useit']));
     $css = "";
     foreach( $useit as $value ) {
      if ($value) $css .= $module_title[$value]." <a href='/sys.php?op=mainpage&type=1&id=".$value."' title='Редактировать'><img class='icon2 i34' src='/images/1.gif'></a> ";
     }
     if ($css == "") $css = "<i class='gray'>стиль не выбран</i><br>"; 
     else $css = "Подключенные стили (css): ".$css."<br>";
     if ($row['title'] != "Главный стиль") $delx = "<a class='padleft30' href='/sys.php?op=mainpage_del&id=".$row['id']."&type=1' title='Удалить стиль «".$row['title']."»'><img class='icon2 i21' src='/images/1.gif'></a>";
     else $delx = "<span class='padleft30'><img title='Основной стиль нельзя удалить' class='icon2 i44' src='/images/1.gif'>";
     $info .= "<tr><td><h2>".$row['title']."<div style='float:right; display: inline;'><a href='sys.php?op=mainpage&type=1&id=".$row['id']."' title='Редактировать'><img class='icon2 i34' src='/images/1.gif'></a> ".$delx."</h2></div></td></tr>";
    }
    $info .= "</table>";
  break;


  case "block":
    $block_names = array("-"=>"",
      "2"=>"Текст или HTML (<i>в том числе [другие блоки]</i>)", 
      "10"=>"Меню сайта", 
      "5"=>"Голосование (<i>опрос, несколько ответов на вопрос</i>)", 
      "3"=>"Ротатор – для блоков, текста или HTML", 
      "6"=>"Фотогалерея (<i>список фото</i>)", 
      "9"=>"Фотогалерея (<i>фото, взятые из страниц</i>)", 
      "4"=>"Папки раздела", 
      "8"=>"Папки открытого раздела", 
      "0"=>"Страницы раздела", 
      "1"=>"Комментарии раздела", 
      "11"=>"Календарь", 
      "12"=>"Форма для заполнения (анкеты, опросы и т.д.)", 
      "13"=>"Облако тегов (<i>ключевых слов</i>)", 
      "30"=>"Статистика раздела (<i>количество посещений</i>)", 
      "31"=>"JavaScript-код (<i>ставится на место автоматически</i>)", 
      "7"=>"PHP-код (<i>вывод через переменную \$txt</i>)", 
      "20"=>"База данных (<i>количество по 1 колонке, вертикально</i>)", 
      "21"=>"База данных (<i>количество по 1 колонке, горизонтально</i>)", 
      "22"=>"База данных (<i>количество по 2 колонкам</i>)", 
      "23"=>"База данных (<i>список колонок</i>)");
    global $uskorenie_blokov;
    $sql = "select id,name,title from ".$prefix."_mainpage where `tables`='pages' and type='3' order by name, title";
    $result = $db->sql_query($sql);
    $n = $blocks_no = $blocks_yes = "";
    while ($row = $db->sql_fetchrow($result)) {
      if ($uskorenie_blokov == 0) {
        $sql2 = "select id,name,title,useit from ".$prefix."_mainpage where `tables`='pages' and type='0' and text like '%[".$row['title']."]%' order by title, name";
        $result2 = $db->sql_query($sql2);
        $numrows2 = $db->sql_numrows($result2);
        if ($numrows2 == 0) $diz = "";
        else {
          $diz = "<span class='green'> &rarr; используется в дизайнах: |";
          while ($row2 = $db->sql_fetchrow($result2)) {
            $diz .= " ".$row2['title']." |";
          }
          $diz .= "</span>";
        }
        ///////
        $sql2 = "select id,name,title from ".$prefix."_mainpage where `tables`='pages' and type='2' and useit like '%[".$row['title']."]%' order by title, name";
        $result2 = $db->sql_query($sql2);
        $numrows2 = $db->sql_numrows($result2);
        if ($numrows2 == 0) $razr = "";
        else {
          if ($diz != "") $diz .= "<br>";
          $razr = "<span class='green'> &rarr; используется в разделах: ";
          while ($row2 = $db->sql_fetchrow($result2)) {
            $id = $row2['id'];
            $razr .= "<a href='/-".$row2['name']."' target='_blank' class='gray'>".$row2['title']."</a> ";
          }
          $razr .= "</span>";
        }
        ///////
        $sql2 = "select id,name,title from ".$prefix."_mainpage where `tables`='pages' and type='3' and text like '%[".$row['title']."]%' order by title, name";
        $result2 = $db->sql_query($sql2);
        $numrows2 = $db->sql_numrows($result2);
        if ($numrows2 == 0) $bloc = "";
        else {
          if ($razr != "") $razr .= "<br>";
          $bloc = "<span class='green'> &rarr; используется в блоках: ";
          while ($row2 = $db->sql_fetchrow($result2)) {
            $id = $row2['id'];
            $bloc .= "[".$row2['title']."] ";
          }
          $bloc .= "</span>";
        }
        ///////
        $sql2 = "select pid, module, title from ".$prefix."_pages where `tables`='pages' and active='1' and (open_text like '%[".$row['title']."]%' or main_text like '%[".$row['title']."]%') order by title";
        $result2 = $db->sql_query($sql2);
        $numrows2 = $db->sql_numrows($result2);
        if ($numrows2 == 0) $stri = "";
        else {
          if ($bloc != "") $bloc .= "<br>";
          $stri = "<span class='green'> &rarr; используется в страницах: ";
          if ($numrows2 < 6) {
            while ($row2 = $db->sql_fetchrow($result2)) {
              $stri .= "<a href='/-".$row2['module']."_page_".$row2['pid']."' target='_blank' class='gray'>".$row2['title']."</a> ";
            }
          } else $stri .= $numrows2." страниц...";
          $stri .= "</span>";
        }
        if ($diz=="" and $razr=="" and $bloc=="" and $stri=="") 
          $title = $row['title'].'<span class="gray"> &rarr; не используется</span>';
        else 
          $title = $row['title'].$diz.$razr.$bloc.$stri;
      } else $title = $row['title'];

      if ($n == $row['name']) {$nu = "-";}
      else {$n = $row['name']; $nu = $row['name'];}
      $bgcolor = "#FFeecc"; //FFddaa
       if ($nu == "-") $block = "<td class='padleft30'>"; else $block = "<td style='background:white;'><br><h2>".$block_names[$nu]." &darr;</h2></td></tr><tr><td class='padleft30'>";
      $title = $block.$title;
      $blocks_ok = "<tr valign=top>".$title."<div style='margin-left:20px; display: inline; float:right;'>
       <a href='sys.php?op=mainpage&type=3&id=".$row['id']."&red=1' title='Редактировать в HTML'><img class='icon2 i34' src='/images/1.gif'></a> 
       <a href='sys.php?op=mainpage&type=3&id=".$row['id']."&nastroi=1' title='Настроить блок'><img class='icon2 i38' src='/images/1.gif'></a> 
       <a class='padleft30' href='sys.php?op=mainpage_del&id=".$row['id']."&type=3' title='Удалить ".$row['title']."'><img class='icon2 i21' src='/images/1.gif'></a>
       </div>
       </td></tr>";
      $blocks_no .= $blocks_ok;
    }
    $info .= "<span class=green>Названия блоков в [квадратных скобках] можно использовать для вставки в дизайн, разделы, папки, страницы или другие блоки (т.е. в любом месте сайта).</span><table width=100% class='table_light'>".$blocks_no."</table>";
  break;


  case "shablon": // 6
    $sql = "select * from ".$prefix."_mainpage where `tables`='pages' and type='6' order by type, title, name";
    $result = $db->sql_query($sql);
    $current_type = "";
    $info .= "<table width=60% class=table_light>";
    while ($row = $db->sql_fetchrow($result)) {
      $id = $row['id'];
      $type = $row['type'];
      $nam = $row['name']; 
      $title = $row['title'];
      $useit = $row['useit'];
      $useit_module = "";
      $text = $row['text'];
      global $admin_file;
      $redactor = "<div style='float:right;'><a href='sys.php?op=mainpage&id=".$id."&red=1&type=6' title='Редактировать'><img class='icon2 i34' src='/images/1.gif'></a> ";
      $redactor .= "<a class='padleft30' href='sys.php?op=mainpage_del&id=".$id."&type=6&name=$nam' title='Удалить'><img class='icon2 i33' src='/images/1.gif'></a></div>"; // удаление базы данных
      $info .= "<tr><td>".$redactor."<h2>".$title."</h2></td></tr>";
    }
    $info .= "</table>";
  break;


  case "pole": // 4
    $sql = "select * from ".$prefix."_mainpage where `tables`='pages' and type = '4' order by type, title, name";
    $result = $db->sql_query($sql);
    $current_type = "";
    $info .= "<table width=100% class=table_light>";
    while ($row = $db->sql_fetchrow($result)) {
      $id = $row['id'];
      $type = $row['type']; 
      $nam = $row['name']; 
      $title = $row['title'];
      $useit = $row['useit'];
      $text = $row['text'];
      $and = "";
      global $admin_file;
      $s_tip = explode("|",$text); $s_tip = explode("&",$s_tip[1]); $s_tip = explode("=",$s_tip[0]); 
      if ($s_tip[1]==0) $and = "список фраз на выбор";
      if ($s_tip[1]==1) $and = "текст";
      if ($s_tip[1]==2) $and = "файл";
      if ($s_tip[1]==3) $and = "период времени";
      if ($s_tip[1]==4) $and = "строка";
      $m_title = "<a href=/-".$useit.">".$module_title[$useit]."</a>";
      if ($useit==0) $m_title = "все разделы";
      $type_opisX = "Раздел: ".$m_title.".<br>Тип: ".$and.".</sup>";
      $adres = "$title";
      $redactor = "<div style='float:right;'><a href='sys.php?op=mainpage&id=".$id."&red=1&type=4' title='Редактировать'><img class='icon2 i34' src='/images/1.gif'></a> 
      <a class='padleft30' href='sys.php?op=mainpage_del&id=".$id."&type=4&name=$nam' title='Удалить поле'><img class='icon2 i33' src='/images/1.gif'></a></div>";
      $info .= "<tr><td>".$redactor."<h2>".$title." &darr;</h2><sup style=\"color:#999999;\">Используется в шаблонах: [".$nam."]</sup><br>
      ".$type_opisX."</td></tr>";
    }
    $info .= "</table><br><a class=\"dark_pole\" title=\"Очистить пустые поля\" href='sys.php?op=mainpage_recycle_spiski'><img class=\"icon2 i33\" src=/images/1.gif class=left>Очистить пустые поля</a>";
  break;


  case "base": // 5
    $sql = "select * from ".$prefix."_mainpage where `tables`='pages' and type = '5' order by type, title, name";
    $result = $db->sql_query($sql);
    $current_type = "";
    $info .= "<table width=60% class=table_light>";
    while ($row = $db->sql_fetchrow($result)) {
      $id = $row['id'];
      $type = $row['type']; 
      $nam = $row['name']; 
      $title = $row['title'];
      $useit = $row['useit'];
      $useit_module = "";
      $text = $row['text'];
      $and = "";
      global $admin_file;
      $ti = "";
      $text = explode("|",$text);
      $options = $text[1];
      $text = $text[0];
      if (strpos(" ".$options,"base=")) $text = "base";
      $adres = "$title";
      if ($nam != "index") {
        if (!strpos(" ".$options,"base=")) $link = ""; 
        else $link = "<a href=/sys.php?op=base_base&name=$nam title='Раскрыть содержание раздела базы данных'><img src='images/admin/".$type_opis[8].".gif' style='background: green; width: 20px; height: 20px;'></a>&nbsp;"; 
      } else $link = "";
      $adres = "$title";
      $link = "";
      $view2 = "";
      $adres = "$title";
      $view = "&nbsp;&nbsp;"; 
      $redactor = "";
      $and = "";
      $redactor .= "<div style='float:right;'><a class='padleft30' href='sys.php?op=mainpage_del&id=".$id."&type=5&name=$nam' title='Удалить базу данных'><img class='icon2 i33' src='/images/1.gif'></a></div>"; // удаление базы данных
      $info .= "<tr><td>".$adres." <sup style=\"color:#999999;\">".$and."</sup>&nbsp;&nbsp;<font color=#dddddd>".$view2."</font> ".$redactor."&nbsp;".$and2."".$view."&nbsp;&nbsp;".$link."</td></tr>";
    }
    $info .= "</table><div class=green>Для редактирования таблицы подключите её к разделу через его настройки.</div>";
  break;

  default:
    $info = '?';
  break;
  }

  echo $info; exit; 
}
######################################################################################
if ($func == "delfile") { // Удаляем фотографию с сервера
  unlink ($type); exit; 
}
######################################################################################
if ($func == "trash_pics") { // Создаем список неиспользуемых фотографий
  $info = $inf2 = $fotos = "";
  $inf = array();
  // собираем адреса фотографий со всех страниц
  $sql = "select text from ".$prefix."_pages_comments where `tables`='pages' and active='1'";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $f = foto_find($row['text']);
    if (is_array($f)) $fotos .= " ".implode(" ",$f);
  }
  $sql = "select description from ".$prefix."_pages_categories where `tables`='pages'";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $f = foto_find($row['description']);
    if (is_array($f)) $fotos .= " ".implode(" ",$f);
  }
  $sql = "select open_text, main_text from ".$prefix."_pages where `tables`='pages'";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $f = foto_find($row['open_text'].$row['main_text']);
    if (is_array($f)) $fotos .= " ".implode(" ",$f);
  }
  $sql = "select text, useit from ".$prefix."_mainpage where `tables`='pages' and `name`!='6'";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $f = foto_find($row['text'].$row['useit']);
    if (is_array($f)) $fotos .= " ".implode(" ",$f);
  }
  $sql = "select text from ".$prefix."_mainpage where `tables`='pages' and `type`='3' and `name`='6'";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $txt = explode("\n",$row['text']);
    for ( $i=0; $i < count($txt); $i++ ) { 
      $link = explode("|",$txt[$i]);
      $link = str_replace("/img/","img/",trim($link[0]));
      if ($link != "") $fotos .= " ".$link;
    }
  }
  $inf = array();
  $f = trim(str_replace("  "," ",$fotos));
  //if (strlen($f)>0) 
    $inf = explode(" ",$f);
  $inf = array_unique($inf);
  $inf_count = count($inf);
  if ($inf_count != 0) {
    if (is_dir("img")) $inf2 .= scandirectory("img", "", "");
    if (is_dir("spaw2/uploads")) $inf2 .= scandirectory("spaw2/uploads", "", "");
    $inf2 = explode("@",trim($inf2));
    $inf2 = array_unique($inf2);
    $inf2_count = count($inf2)-1;

    $info .= "<h1>Загруженных на сервер фотографий: ".$inf2_count.".<h1>";
    $diff = array_diff($inf2, $inf);
    $diff = array_unique($diff);
    $diff_count = count($diff);
    $info .= "<h2>Неиспользованных на сайте фотографий (из числа загруженных): <b>".$diff_count."</b>.</h2><b>Вы можете удалить</b> те фотографии, которые не понадобятся в дальнейшем.<br>";
    $num = 1;
    foreach ($diff as $a) { 
      $info .= "<div id='file".$num."' class='delfoto'><a href='".$a."' target='_blank'><img src='includes/phpThumb/phpThumb.php?src=/".$a."&w=0&h=100&q=0'></a><br><a class='punkt' onclick=\"del_file('".$a."', '".$num."');\">Удалить фото</a></div>"; // <br>".$a."
      $num++;
    }
  } else $info .= "<br>Фотографий на сайте не найдено<br>";
  echo $info; exit; 
}
###############################################################################################
if ($func == "delslovo") { // Удаляем слово из статистики поиска по сайту
  $db->sql_query("DELETE from ".$prefix."_search WHERE `id`='$id'"); exit;
}
#################################################################################################
if ($func == "comm_otvet") { // Ответ на комментарий из администрирования
  $comm_cid     = $id;
  $comm_type    = $type;
  list($comm_sender, $comm_otvet, $comm_mail, $comm_mod) = explode("*@%", $string);
  $info = "<b>Ошибка при отправке ответа...</b>";
  if ($comm_otvet == "") $info = "<b>Ответ оказался пустым... ничего не отправлено.</b>";
  else {
    // Получение всей информации
    $row = $db->sql_fetchrow($db->sql_query("SELECT `num`, `avtor`, `text` FROM ".$prefix."_pages_comments WHERE cid='$comm_cid'")) or exit;
    // cid  num avtor mail  text  ip  data  golos tables  drevo adres tel active
    $comm_pid = $row['num'];
    $comm_avtor = $row['avtor'];
    $comm_txt = $row['text'];
    if ($comm_type == 0 or $comm_type == 2) {
      # отправка ответа на сайт
      // Проверка наличия подобного комментария.
      if ($numrows = $db->sql_numrows($db->sql_query("SELECT cid FROM ".$prefix."_pages_comments WHERE text='$comm_otvet' and num='$comm_pid'")) == 0) { 
         $db->sql_query("INSERT INTO ".$prefix."_pages_comments ( `cid` , `num` , `avtor` , `mail` , `text` , `ip` , `data`, `drevo`, `adres`, `tel`, `active` ) VALUES ('', '$comm_pid', '$comm_sender', '$adminmail', '$comm_otvet', '$ip', '$now', '$comm_cid', '', '', '1')");
         $db->sql_query("UPDATE ".$prefix."_pages SET comm=comm+1 WHERE pid='$comm_pid'");
      }
    }
    if (($comm_type == 0 or $comm_type == 1) and $comm_mail != "") {
      # отправка ответа на e-mail
      mail($comm_mail, '=?koi8-r?B?'.base64_encode(convert_cyr_string($comm_avtor.", получен ответ на ваш комментарий...", "w","k")).'?=', "<h3>Здравствуйте, ".$comm_avtor."!</h3><b>Вы писали:</b><br><br>".str_replace("\r\n","<br>",$comm_txt)."<br><br><b>Вам ответил(а) ".$comm_sender.", e-mail не сообщил(а):</b><br><br>".str_replace("\r\n","<br>",$comm_otvet)."<br><br>Чтобы ответить на комментарий, перейдите на сайт по <a href=http://".$siteurl."/-".$comm_mod."_page_".$comm_pid."#comm_".$comm_cid.">этой ссылке</a>.<br><br><br><br>Отвечать на это письмо не нужно - оно было создано сайтом автоматически!", "Content-Type: text/html; charset=utf-8\r\nFrom: ".$adminmail."\r\n");
    }
    $info = "<b>Ответ отправлен.</b>";
  }
  echo $info; exit;
}
######################################################################################
if ($func == "izmenapapka") { // Отображение списка папок для раздела
  list($select, $papka, $razdel) = explode("*@%", $string);
  $info = "";
  $sql = "select cid, module, title, parent_id from ".$prefix."_pages_categories where module='".$select."' and `tables`='pages' order by parent_id, title";
  $result = $db->sql_query($sql) or $info = "Ошибка. Попробуйте обновить страницу. Не поможет — обращайтесь к разработчику.";

  if ($type == "addpage") $info .= "<select name=cid id='to_papka' size=2 style='font-size:11px; width:248px; height:200px;'>";
  elseif ($type == "editdir") $info .= "<select name=parent_id id='to_papka' size=2 style='font-size:11px; width:248px; height:200px;'>";
  elseif ($type == "izmenapage") $info .= "<select style='width:100%' name=to_papka id='to_papka".$id."' size=10>";

  $info .= "<option value=0 selected>Основная папка («корень»)</option>";
      while ($row = $db->sql_fetchrow($result)) {
               $cid3 = $row['cid'];
               $title3 = strip_tags($row['title'], '<b><i>');
               $module3 = $row['module'];
               $parentid = $row['parent_id'];
               if ($parentid != 0) $title3 = "&bull; ".getparent($razdel,$parentid,$title3);
               if ($papka == $cid3 and $razdel == $module3) $sel = "selected"; else $sel = "";
               if ($parentid == 0) { // занести в переменную
                   $first_opt[$cid3] = "<option value=".$cid3." ".$sel." style='background:#fdf;'>".$title3."</option>";
               }
               if ($parentid != 0) { // вывести и очистить переменную
                   $info .= $first_opt[$parentid];
                   $first_opt[$parentid] = "";
                   $info .= "<option value=".$cid3." ".$sel.">".$title3."</option>";
               }
      }
      if (isset($first_opt)) if (count($first_opt) > 0) 
        foreach( $first_opt as $key => $value ) {
          if ($first_opt[$key] != "") $info .= $first_opt[$key];
        }
  $info .= "</select>";
  echo $info; exit;
}
######################################################################################
if ($func == "addpapka") { // Добавляем папку(и)
  list($title, $parent) = explode("*@%", $string);
  global $name_razdels, $title_razdel_and_bd;
  $name_raz = $name_razdels[$id];
  $title = explode("+|+",$title);
  if (trim($title[1]) != "") {
      $title = explode("\n",$title[1]);
      foreach( $title as $title_value ) {
          $pap = mysql_real_escape_string(trim($title_value));
          $title_name = explode("|",$title_value);
          $title_opis = $title_name[1];
          $title_name = $title_name[0];
          if ($pap!="") $db->sql_query("INSERT INTO ".$prefix."_pages_categories VALUES (NULL, '$name_raz', '$title_name', '$title_opis', '', '0', '0', '$parent', 'pages')");
      }
  } else {
      $title_name = explode("|", trim($title[0]) );
      $title_opis = $title_name[1];
      $title_name = $title_name[0];
      if ($title!="") $db->sql_query("INSERT INTO ".$prefix."_pages_categories VALUES (NULL, '$name_raz', '$title_name', '$title_opis', '', '0', '0', '$parent', 'pages')");
  }
  echo $title_razdel_and_bd[$name_raz]; exit;
}
######################################################################################
if ($func == "offpage") { // вкл./выкл. страницы
  $color=""; $nowork = "";
  $active = $db->sql_fetchrow($db->sql_query("SELECT pid, module, title, active FROM ".$prefix."_pages where pid='$id'"));
  if ($active['active'] == 1) { $act = 0; $color=" class=noact"; $nowork="<img class=\"icon2 i43\" src=/images/1.gif class=left title='Страница отключена.\nНажав по ней и выбрав такой же значок, вы можете ее включить.'>"; } 
  else $act = 1;
  $db->sql_query("UPDATE ".$prefix."_pages SET `active`='$act' WHERE pid='$id'"); 
  echo "<div id=\"page".$active['pid']."\"><a href=#".mt_rand(10000, 99999).$active['pid']." onclick='sho(".$active['pid'].", \"".$active['module']."\", \"base_pages\",".$act.");'".$color." class='punkt no'>".$nowork."".$active['title']."</a><div id='pid".$active['pid']."' class=pid></div></div>"; exit;
}
######################################################################################
if ($func == "delrazdel") { // Удаление раздела
  // ДОПИСАТЬ! не хватает рекурсии для удаления комментариев и голосований!
  global $name_razdels;
  $name_raz = $name_razdels[$id];
  $db->sql_query("UPDATE ".$prefix."_pages SET `tables`='del' WHERE module='$name_raz'"); 
  $db->sql_query("UPDATE ".$prefix."_pages_categories SET `tables`='del' WHERE module='$name_raz'"); 
  $db->sql_query("UPDATE ".$prefix."_mainpage SET `tables`='del' WHERE id='$id'"); 
  //$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='$id'"); // окончательное удаление
  exit;
}
######################################################################################
if ($func == "delpapka") { // Удаление папки
  $cids = show_cids($id);
  $cids[] = $id;
  foreach ($cids as $value) {
    $db->sql_query("UPDATE ".$prefix."_pages SET `tables`='del' WHERE `tables`='pages' and cid='".$value."'"); 
    $db->sql_query("UPDATE ".$prefix."_pages_categories SET `tables`='del' WHERE `tables`='pages' and cid='".$value."'");
  }
  exit;
}
######################################################################################
if ($func == "delcomm") { // Удаление комментария
  // узнаем номер страницы
  $raz = $db->sql_fetchrow($db->sql_query("SELECT num FROM ".$prefix."_pages_comments where cid='$id'"));
  $num = $raz['num'];
  $db->sql_query("DELETE from ".$prefix."_pages_comments WHERE cid='$id'");
  $db->sql_query("UPDATE ".$prefix."_pages SET comm=comm-1 WHERE pid='$num'");
  exit;
}
######################################################################################
if ($func == "delpage") { // Псевдо удаление страницы
  global $now;
  $db->sql_query("UPDATE ".$prefix."_pages SET `tables`='del', `redate`='$now' WHERE `tables`='pages' and pid='$id'");
  exit;
}
######################################################################################
if ($func == "deletepage") { // Полное удаление страницы
  $db->sql_query("DELETE from ".$prefix."_pages WHERE (`tables`='del' or `tables`='backup') and pid='$id'"); 
  exit;
}
######################################################################################
if ($func == "resetpage") { // Восстановление страницы
  // Узнаем, это удаленная стр. или резерв. копия
  $raz = $db->sql_fetchrow($db->sql_query("SELECT tables, copy FROM ".$prefix."_pages where pid='$id'"));
  $pi = $raz['copy'];
  if ( $raz['tables'] == "backup" ) {
      $db->sql_query("DELETE from ".$prefix."_pages WHERE pid='$pi'"); 
      $db->sql_query("UPDATE ".$prefix."_pages SET `pid`='$pi', `tables`='pages', `copy`='0' WHERE pid='$id'");
  } elseif ( $raz['tables'] == "del" ) {
      $db->sql_query("UPDATE ".$prefix."_pages SET `tables`='pages', `redate`='$now' WHERE `tables`='del' and pid='$id'");
  }
  exit;
}
######################################################################################
if ($func == "offcomm") { // Вкл./Выкл. комментария
  $active = $db->sql_fetchrow($db->sql_query("SELECT cid, num, avtor, text, data, active FROM ".$prefix."_pages_comments where cid='$id'"));
  if ($active['active'] == 1) { $act = 0; $comm = "Комментарий выключен"; $color="error"; } else { $act = 1; $comm = "Комментарий включен"; $color="success"; }
  $db->sql_query("UPDATE ".$prefix."_pages_comments SET `active`='$act' WHERE cid='$id'");
  echo "<td colspan=2 class='notice ".$color."'>".$comm."</td>"; exit;
}
######################################################################################
if ($func == "add_papka") { // Создание папки
  // Узнаем название раздела
  global $name_razdels;
  $name_raz = $name_razdels[$id];
  $list = "<form method=post style=\"display:inline;\" onsubmit='return false'>
  <h1>Создаем папку в этом разделе</h1>
  <table width=100%><tr valign=top><td width=60%>
  <div id=txt>Имя папки:</div><div id=txt2 style='display:none;'>Имена папок:</div>
  <div id=text><input type=text name=title id='title".$id."' size=30 style='width:100%;'></div>
  <div id=text2 style='display:none;'><br>
  <textarea name=title2 id='title".$id."text' rows=5 cols=3 style='width:100%; height: 200px;'></textarea><br></div>
  </td><td width=5></td><td>Основная или вложенная папка?<br>";
             $sql = "select cid, title, parent_id from ".$prefix."_pages_categories where module='$name_raz' and `tables`='pages' order by parent_id,cid";
             $result = $db->sql_query($sql);
             $list .= "<select id='select".$id."' name=parent_id style='width: 100%;'><option value=0>Это основная папка</option>";
             while ($row = $db->sql_fetchrow($result)) {
               $cid = $row['cid'];
               $title = strip_tags($row['title'], '<b><i>');
               $parentid = $row['parent_id'];
               $title = getparent($name_raz,$parentid,$title);
               //if ($add_cat==$cid) $sel=" selected"; else $sel="";
               $list .= "<option value=".$cid.">Вложена в «".$title."»</option>";
             }
  $list .= "</select><br><br>
  <a onclick=\"show('text'); show('text2'); show('text22');  show('txt'); show('txt2');\" class=punkt>Создать несколько папок...</a>
  <div id=text22 style='display:none;'><br><br><font class=green>&larr; Пишите имена папок в столбик, разделяя их нажатием Enter</font><br><br><br></div>
  <input type=submit value=\" Создать \" onclick=\"save_papka('".$id."',document.getElementById('title".$id."').value+'+|+'+document.getElementById('title".$id."text').value,document.getElementById('select".$id."').value,'$name_raz');\" style='width:100%; height:55px; font-size: 22px; margin-top:20px;'>
  </td></tr></table>
  <div class='help small'>?</div> <span class=small>Добавить описание к папке можно сразу после названия через символ «|».<br>
  Если вы не видите созданной папки — обновите страницу (например, нажав F5).</span>
  </form>";
  $list = "<div class='block_white2 radius' style='margin-top:20px; padding-bottom:30px; padding-left:10px; background: #dddddd;'><a title='Закрыть это окно' class=punkt onclick=\"$('#add_papka').hide();\"><div class='radius' style='font-size:12pt; width:20px; height: 20px; color: white; text-align:center; float:right; margin:5px; margin-bottom:0; background: #bbbbbb;'>&nbsp;x&nbsp;</div></a>".$list."
  </div>";
  echo $list; exit;
}
######################################################################################
if ($func == "opengarbage") { // Открытие вкладок Содержания
  global $title_razdel_and_bd;
  $color=$pageslistdel=$nowork = "";

  if ($id == 5) { // НОВОЕ
    $pageslistdel .= "<table width=100% class=table_light><thead><tr><th>Дата последнего изменения</th><th>Раздел </th><th>Страница</th></tr></thead><tbody>";
    $result6 = $db->sql_query("SELECT `pid`, `module`, `title`, `active`, `date`, `redate` from ".$prefix."_pages where `tables`='pages' order by `redate` desc limit 0,1000");
    while ($row6 = $db->sql_fetchrow($result6)) {
        $pid = $row6['pid'];
        $title = strip_tags($row6['title'], '<b><strong><em><i>');
        if (trim($title) == "") $title = "< страница без названия >";
        //if (strlen($row6['module']) >= 22) $module = substr($row6['module'],0,20)."...";
        //else 
          $module = $row6['module'];
        $date = date2normal_view(str_replace(".","-",$row6['redate']), 2, 1);
        $gray_date = " gray";
        if (date2normal_view(str_replace(".","-",$row6['redate'])) == date2normal_view(str_replace(".","-",$row6['date']))) $gray_date = "";
        if (!isset($module)) $title_razdel_and_bd[$module] = "РАЗДЕЛ УДАЛЁН! &rarr; $module";
        $m_title = $title_razdel_and_bd[$module];
        if ($row6['active'] == 1) $p_active_color = "white";
        else {
          $p_active_color = "#dddddd";
          $m_title = "<a onclick=offpage(".$pid.",1) class=\"punkt\" title=\"Включение страницы\"><img class=\"icon2 i44\" src=/images/1.gif>Включить</a>";
        }
        $pageslistdel .= "<tr id=1page".$pid." bgcolor=".$p_active_color." class='tr_hover'><td class='".$gray_date."'><nobr>".$date."</nobr></td><td>".$m_title."</td><td><a title='Удалить страницу в Корзину' onclick=delpage(".$pid.") style=\"cursor:pointer;\"><img class=\"icon2 i33\" src=/images/1.gif align=right></a><a title='Изменить страницу' href='sys.php?op=base_pages_edit_page&name=".$module."&pid=".$pid."'><img class=\"icon2 i35\" src=/images/1.gif></a> <a title='Открыть страницу на сайте' target=_blank href=-".$module."_page_".$pid.">".$title."</a>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
    }
    $pageslistdel .= "</tbody></table>";

  } elseif ($id == 4) { // ДОБАВЛЕННОЕ ПОСЕТИТЕЛЯМИ
    $pageslistdel .= "<table width=100% class=table_light><thead><tr><th class='gray'>Дата создания</th><th class='gray'>Раздел </th><th class='gray'>Страница</th></tr></thead><tbody>";
    $result7 = $db->sql_query("SELECT `pid`, `module`, `title`, `date` from ".$prefix."_pages where (`active`='2' or `active`='3') and `tables`!='del' order by `date` desc limit 0,10000");
    while ($row7 = $db->sql_fetchrow($result7)) {
      $pid = $row7['pid'];
      $title = strip_tags($row7['title'], '<b><strong><em><i>');
        if (trim($title) == "") $title = "< страница без названия >";
      //if (strlen($row7['module']) >= 22) $module = substr($row7['module'],0,20)."...";
      //else 
        $module = $row7['module'];
      if (!isset($module)) $title_razdel_and_bd[$module] = "РАЗДЕЛ УДАЛЁН! &rarr; $module";
      $date = date2normal_view(str_replace(".","-",$row7['date']), 2, 1);
      $pageslistdel .= "<tr id=2page".$pid." bgcolor=#ffffff class='tr_hover'><td class='gray'><nobr>".$date."</nobr></td><td class='gray'>".$title_razdel_and_bd[$module]."</td><td><a title='Удалить страницу в Корзину' onclick=delpage(".$pid.") style=\"cursor:pointer;\"><img class=\"icon2 i33\" src=/images/1.gif align=right></a><a title='Изменить страницу в Редакторе' href='sys.php?op=base_pages_edit_page&name=".$module."&pid=".$pid."'><img class=\"icon2 i35\" src=/images/1.gif></a> <a title='Открыть страницу на сайте' target=_blank href=-".$module."_page_".$pid.">".$title."</a>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
    }
  $pageslistdel .= "</tbody></table><i>Максимум отображения: 10.000 страниц.</i>";

  } elseif ($id == 3 or $id == 6 or $id == 7) { // КОММЕНТАРИИ
    $limit = 100;
    $and = "";
    $del = false;
    if ($id == 6) { // отключенные
      $limit = 100;
      $and = " and `active`='0'";
      $del = true;
    }
    if ($id == 7) { // без ответов
      $limit = 100;
      $sql = "SELECT `drevo` from ".$prefix."_pages_comments where `tables`='pages'".$line_id." and `active`!='0' and `num`!='0' and `drevo`!='0' order by `data` desc limit 0,1000000";
      $result = $db->sql_query($sql);
      $drevos = array();
      while ($row = $db->sql_fetchrow($result)) {
        $drevos[] = $row['drevo'];
      }
      array_unique($drevos);
      sort($drevos);
      $drevos = implode("' and `cid`!='",$drevos);
      $and = " and `num`!='0' and `active`!='0' and `drevo`='0' and (`cid`!='".$drevos."')";
    }
    $pageslistdel .= "<table width=100% class=table_light><thead><tr><th class='gray'><nobr>Дата и время</nobr></th><th class='gray'>Имя, раздел и комментарий (нажмите, чтобы развернуть)</th></tr></thead><tbody>";
    $line_id = "";
    $sql5 = "SELECT `cid`, `num`, `avtor`, `mail`, `text`, `data`, `drevo`, `tel`, `active` from ".$prefix."_pages_comments where `tables`='pages'".$line_id.$and." order by `data` desc limit 0,".$limit;
    $result5 = $db->sql_query($sql5);

    while ($row5 = $db->sql_fetchrow($result5)) {
      $cid = $row5['cid'];
      $num = $row5['num'];
      $txt = $row5['text'];
      $otvet = $row5['drevo'];
      $avtor = strip_tags($row5['avtor']);
      $mails = trim(str_replace(" ","",strip_tags($row5['mail']))); 
      $tel = trim(strip_tags($row5['tel'])); 
      $data = date2normal_view(str_replace(".","-",$row5['data']), 2, 1);
      if (strpos($mails, "@")) { $mail = $mails; $mails = "<br>Email: <a href='mailto:".$mails."'>".$mails."</a>"; } else { $mail = ""; $mails = ""; }
      if ($tel != "") $tel = "<br>Телефон: ".$tel;
       
      $sql4 = "SELECT `title`, `module` from ".$prefix."_pages where `pid` = '$num'";
      $result4 = $db->sql_query($sql4);
      $row4 = $db->sql_fetchrow($result4);
      $module = $row4['module'];
      $titles = $row4['title'];

      $pishet = "пишет в";
      if ($otvet != 0) {
        $otvet = "<br>Является ответом на <a target='_blank' href='/sys.php?op=base_comments&name=".$module."&pid=".$num."#".$otvet."'>комм. №".$otvet."</a>.";
        $pishet = "отвечает в";
      }
      else $otvet = "";
      if ($row5['active'] == 0) {
        $bgcolor = " bgcolor='#dddddd' class='ffa4ac'"; 
        $vkl = "<img class=\"icon2 i44\" src=/images/1.gif align=right title='Включить комментарий'>";
      } else {
        $bgcolor = " bgcolor='white'";
        $vkl = "<img class=\"icon2 i43\" src=/images/1.gif align=right title='Отключить комментарий'>";
      }
      $textline = mb_substr(strip_tags($txt), 0, 45, 'UTF-8');
      if (strlen($textline)<strlen($txt)) $textline .= "...";
      if ($avtor == "Администратор") $avtor2 = "<span class=red>".$avtor."</span>";
      else $avtor2 = $avtor;
      if ($num != 0) {
        if (!isset($module)) $titl_mainpage = "РАЗДЕЛ УДАЛЁН! &rarr; $module";
        else $titl_mainpage = trim($title_razdel_and_bd[$module]);
        if ($del == true) $del = "<a title='Удалить отключенный комментарий' onclick=delcomm(".$cid.") class=punkt><img class='icon2 i21' src='/images/1.gif' align=right></a>"; else $del = "";
        $pageslistdel .= "<tr onclick=show('comm".$cid."') title='Показать комментарий...' valign=top style='cursor:pointer;' class='tr_hover' id=1comm".$cid.$bgcolor."><td class='gray'><nobr>".$data."</nobr></td><td>".$del."<a onclick=offcomm(".$cid.") class=punkt>".$vkl."</a>

        <a title='Изменить комментарий' href='sys.php?op=base_comments_edit_comments&cid=".$cid."'><img class='icon2 i35' src=/images/1.gif align=right></a>

        <i>".$avtor2."</i><span class='gray'> ".$pishet." разделе «".$titl_mainpage."» на странице </span><a title='Открыть на сайте...' target=_blank href='-".$module."_page_".$num."#comm_".$cid."'>".$titles."</a>: <span class='gray'>".$textline."</span></td></tr>
        <tr><td colspan=2".$bgcolor." style='padding:0; margin:0;'>
        <div style='display:none;' id=comm".$cid.">
        <br>".$otvet.$mails.$tel."<br>
        <div class=bggray>".$txt."</div><br>
        <a onclick=\"show_otvet_comm($cid,'".$avtor."','".$mail."','".$module."')\" class=punkt><img class='icon2 i11' src='/images/1.gif' align=left>Ответить на комментарий</a>
        <div id=otvet_comm".$cid."></div><br><br>
        </div>
        </td></tr>";
      } else {
        if ($mail != "") $pageslistdel .= "<tr valign=top id=1comm".$cid.$bgcolor."><td class='gray'><nobr>".$data."</nobr></td><td><a title='Удалить подписку' onclick=delcomm(".$cid.") class=punkt><img class='icon2 i21' src='/images/1.gif' align=right></a><a title='Изменить подписку' href='sys.php?op=base_comments_edit_comments&cid=".$cid."'><img class='icon2 i35' src='/images/1.gif' align=right></a> <span class=green>Подписка на рассылку</span>, ".$avtor." &rarr; ".$mail."</td></tr>";
        else $pageslistdel .= "<tr valign=top id=1comm".$cid.$bgcolor."><td class='gray'><nobr>".$data."</nobr></td><td><a title='Удалить сообщение' onclick=delcomm(".$cid.") class=punkt><img class='icon2 i21' src='/images/1.gif' align=right></a> <span class=green>".$avtor."</span> &rarr; ".$txt."</td></tr>";
      }
    }
  } else {
    if ($id == 1) { $deistvo = "del"; $slovo = "Дата удаления"; }
    if ($id == 2) { $deistvo = "backup"; $slovo = "Дата изменения оригинала"; }

    $sql = "SELECT count(`pid`) from ".$prefix."_pages where `tables`='".$deistvo."'";
    $iid = $deistvo."page";
    $numrows = $db->sql_fetchrow( $db->sql_query($sql) );
    $numrows = $numrows[0];

    $sql6 = "SELECT `pid`, `module`, `title`, `redate` from ".$prefix."_pages where `tables`='".$deistvo."' order by `redate` desc limit 500";
    $iid = $deistvo."page";
        $result6 = $db->sql_query($sql6);
        $pageslistdel .= "Всего: ".$numrows.". Показаны только последние 500 штук. 
        <table width=100% class=table_light><thead><tr><th>".$slovo."</th><th>Раздел </th><th>Страница</th></tr></thead><tbody>";
            while ($row6 = $db->sql_fetchrow($result6)) {
                $pid = $row6['pid'];
                $title = strip_tags($row6['title'], '<b><strong><em><i>');
                if (trim($title) == "") $title = "< страница без названия >";
                $module = $row6['module'];
                if (!isset($title_razdel_and_bd[$module])) $title_razdel_and_bd[$module] = "РАЗДЕЛ УДАЛЁН! &rarr; $module";
                
                $date = date2normal_view(str_replace(".","-",$row6['redate']), 2, 1);
                if ($id == 1) $recreate = "<a title='Восстановить страницу...\nЕсли её раздел или папка удалены, сначала отредактируйте и восстановите из резервных копий!' onclick=resetpage(".$pid.") style=\"cursor:pointer;\"><img class=\"icon2 i37\" src=/images/1.gif></a>";
                if ($id == 2) $recreate = "<a title='Заменить этой копией оригинал...\nПодумайте, прежде чем нажимать!' onclick=resetpage(".$pid.") style=\"cursor:pointer;\"><img class=\"icon2 i24\" src=/images/1.gif></a>";
                $pageslistdel .= "<tr valign=top id=".$iid.$pid." bgcolor=#ffffff class='tr_hover'><td><nobr>".$date."</nobr></td><td>".$title_razdel_and_bd[$module]."</td><td><a title='Удалить страницу (без возможности восстановления)' onclick=deletepage(".$pid.") style=\"cursor:pointer;\"><img class=\"icon2 i21\" src=/images/1.gif align=right></a>     
                <a target=_blank title='Изменить страницу' href='sys.php?op=base_pages_edit_page&name=".$module."&pid=".$pid."'><img class=\"icon2 i35\" src=/images/1.gif class=left></a><a target=_blank title='Изменить страницу в HTML' href='sys.php?op=base_pages_edit_page&name=".$module."&pid=".$pid."&red=1'><img class=\"icon2 i34\" src=/images/1.gif class=left></a>
               ".$title."&nbsp;&nbsp;".$recreate."
                </td></tr>";
          }
  }
  $pageslistdel .= "</tbody></table>";
  echo $pageslistdel;
  exit;
}
######################################################################################
if ($func == "rep") { // Копия/Перемещения/Ярлык страницы
  list($papka, $razdel) = explode("*@%", $string);
  $info = "Не получилось...";
  if ($type == 1) { // создать ярлык (ссылку)
  $info = "<b>Страница успешно продублирована.</b> При изменении любой из страниц-ярлыков, информация будет меняться во всех остальных страницах. Удаление одной из страниц-ярлыков (в том числе оригинала) не затронет других страниц.";
  // получим все данные об этой странице
  $sql = "SELECT * FROM ".$prefix."_pages WHERE pid='$id'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
    $keys = $row['keywords'];
    $desc = $row['description'];
    $title = strip_tags($row['title'], '<b><i>');
    $opentext = $row['open_text'];
    $bodytext = $row['main_text'];
    $data = $row['date'];
    $data2 = $now;
    $active = $row['active'];
    $foto = $row['foto'];
    $search = $row['search'];
    $mainpage = $row['mainpage'];
    $rss = $row['rss'];
    $price = $row['price'];
    $copy = $row['copy'];
    $sort = $row['sort'];
    $re2 = $id;
    if ($copy == 0) $copy = $id;
    else $re2 = $copy;
  // создадим такую же
  $db->sql_query("INSERT INTO ".$prefix."_pages VALUES (NULL, '$razdel', '$papka', '$title', '$opentext', '$bodytext', '$data', '$data2', '0', '$active', '0', '0', '$foto', '$search', '$mainpage', '$rss', '$price', '$desc', '$keys', 'pages', '$copy', '$sort');") or $info = "Создать копию не удалось."; 
  $db->sql_query("UPDATE ".$prefix."_pages SET `copy`='$re2' WHERE pid='$id'") or $info .= "Изменить номер в копируемой странице не удалось.";
  }
  //////////////////
  if ($type == 2) { // копировать
  $info = "<b>Страница успешно скопирована.</b>";
  // получим все данные об этой странице
  $sql = "SELECT * FROM ".$prefix."_pages WHERE pid='$id'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
    $keys = $row['keywords'];
    $desc = $row['description'];
    $title = strip_tags($row['title'], '<b><i>');
    $opentext = $row['open_text'];
    $bodytext = $row['main_text'];
    $data = $row['date'];
    $data2 = $now;
    $active = $row['active'];
    $foto = $row['foto'];
    $search = $row['search'];
    $mainpage = $row['mainpage'];
    $rss = $row['rss'];
    $price = $row['price'];
    $sort = $row['sort'];
  // создадим такую же
    if (isset($copy)) {
      if ($copy == 0) $copy = $id;
    } else $copy = $id;
  $db->sql_query("INSERT INTO ".$prefix."_pages VALUES (NULL, '".mysql_real_escape_string($razdel)."', '$papka', '".mysql_real_escape_string($title)."', '".mysql_real_escape_string($opentext)."', '".mysql_real_escape_string($bodytext)."', '$data', '$data2', '0', '$active', '0', '0', '$foto', '".mysql_real_escape_string($search)."', '$mainpage', '$rss', '".mysql_real_escape_string($price)."', '".mysql_real_escape_string($desc)."', '".mysql_real_escape_string($keys)."', 'pages', '0', '$sort');") or $info = "Скопировать не удалось."; 
  }
  //////////////////
  if ($type == 3) { // переместить
  $info = "<b>Страница успешно перемещена.</b>";
  $db->sql_query("UPDATE ".$prefix."_pages SET `module`='$razdel', `cid`='$papka' WHERE pid='$id'") or $info = "Перемещение не удалось.";
  }
  echo "<div class='notice success center'><h2>".$info."</h2></div>"; exit;
}
######################################################################################
if ($func == "replace") { // Перемещение страницы
  // Узнаем название раздела и id папки
  $name_raz = $db->sql_fetchrow($db->sql_query("SELECT module, cid FROM ".$prefix."_pages where `tables`='pages' and pid='$id'"));
  $name_pap = $name_raz['cid'];
  $name_raz = $name_raz['module'];
  $list = "<form method=post style=\"display:inline;\" name=teleport onsubmit='return false'>
  <a title='Закрыть это окно' class=punkt onclick=\"clo($id);\"><div class='radius' style='font-size:12pt; width:20px; height: 20px; color: white; text-align:center; float:right; margin:5px; margin-bottom:0; background: #bbbbbb;'>&nbsp;x&nbsp;</div></a>
  <p><b>Что будем делать?</b> <select name=what id='what".$id."'>
  <option value=1>создадим ярлык</option>
  <option value=2 selected>скопируем</option>
  <option value=3>переместим</option>
  </select> <div id='rep".$id."'></div>
  <p><b>В какой раздел?</b> ";
  $sql = "select name, title, color from ".$prefix."_mainpage where type='2' and name != 'index' and `tables`='pages' order by color desc, title";
  $result = $db->sql_query($sql);
  $list .= "<select name=to_razdel id='to_razdel".$id."' style='width:100%;' onChange=\"izmenapapka(document.getElementById('to_razdel".$id."').value, $name_pap, '$name_raz',$id,'izmenapage');\">";
             while ($row = $db->sql_fetchrow($result)) {
                 $name2 = $row['name'];
                 $title2 = strip_tags($row['title'], '<b><i>');
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
                 if ($name_raz == $name2) $sel = " selected"; else $sel = "";
                 $list .= "<option style='background:".$color.";' value=".$name2."$sel>".$title2."</option>";
             }
        $list .= "</select><p><b>В какую папку?</b> (у раздела может и не быть папок — значит в «корень»)";
        $sql = "select cid, title, parent_id from ".$prefix."_pages_categories where module='$name_raz' and `tables`='pages' order by parent_id, title";
        $result = $db->sql_query($sql);
        $list .= "<div id='izmenapapka".$id."'>
        <select style='width:100%;' name=to_papka id='to_papka".$id."' size=10>
        <option value=0 selected>Основная папка («корень»)</option>";
        while ($row = $db->sql_fetchrow($result)) {
            $cid3 = $row['cid'];
            $title3 = strip_tags($row['title'], '<b><i>');
            $parentid = $row['parent_id'];
            if ($parentid != 0) $title3 = "&bull; ".getparent($name_raz,$parentid,$title3);
            if ($name_pap == $cid3) $sel = "selected"; else $sel = "";
            if ($parentid == 0) {
                $first_opt[$cid3] = "<option value=".$cid3." ".$sel." style='background:#fdf;'>".$title3."</option>"; 
            }
            // вывести и очистить переменную
            if ($parentid != 0) $list .= $first_opt[$parentid];
            $first_opt[$parentid] = "";
            $list .= "<option value=".$cid3." ".$sel.">".$title3."</option>";
        }
  $list .= "</select></div><input type=button value=\"OK\" style='width:55%; height:35px;' onclick=\"rep($id,document.getElementById('what".$id."').value,document.getElementById('to_razdel".$id."').value,document.getElementById('to_papka".$id."').value); if ($('#what".$id."').val()==3) clo($id);\"><br>Жмём 1 раз, т.к. копирование и ярлыки при каждом нажатии создают новую страницу.
  </form>";
  $list = "<div class='block radius' style='width:95%;'>".$list."
  <p><strong>Справка:</strong> <a class=punkt onclick=\"show('yarlyk_help');\">Что такое Ярлык?</a> 
  <div id='yarlyk_help' style='display:none;'>Когда нужна страница, которая должна находиться в нескольких разделах или папках (к примеру компания относится к разным видам деятельности, для которых созданы папки) — нужно создать на нее ярлык. При изменении ярлыка, информация изменится и на основной странице тоже, т.е. получаются зависимые друг от друга страницы. При удалении одной из них (даже источника) данные во всех остальных не теряются.<br>
  Если в разделе или папке 2 ярлыка одной страницы, отобразится только один.<br>
  Если в разделе или папке оригинал и его ярлык, отобразится только оригинал.</div></div>";
  echo $list; exit;
}
######################################################################################
if ($func == "papka") { // Папка
  list($cid, $sort) = explode("*@%", $string);
  $list = "";
  if ($sort==1) $order = "title, date desc";
  elseif ($sort==0) $order = "date desc";
  elseif ($sort==2) $order = "redate desc";
  elseif ($sort==3) $order = "comm desc";
  elseif ($sort==4) $order = "counter desc";
  elseif ($sort==5) $order = "active";
  global $name_razdels;
  $name_raz = $name_razdels[$id];
    // Подпапки этой папки
    $sql = "SELECT cid, title, parent_id FROM ".$prefix."_pages_categories where module='$name_raz' and `tables`='pages' and parent_id='$cid' order by title";
    $result = $db->sql_query($sql);
    $siz_papka = $db->sql_numrows($result);
    if ($siz_papka > 0) {
      while ($rows = $db->sql_fetchrow($result)) {
        $с_cid = $rows['cid'];
        $name_cid = strip_tags($rows['title'], '<b><i>');
        $cid_pages = $db->sql_numrows($db->sql_query("select pid from ".$prefix."_pages where `tables`='pages' and module='$name_raz' and cid='$с_cid'"));
        if (trim($name_cid) == "") $name_cid = "<span class=red>Эта страница без Названия. Отредактируйте!</span>";
        $cid_papki = $db->sql_numrows($db->sql_query("select cid from ".$prefix."_pages_categories where `tables`='pages' and module='$name_raz' and parent_id='$с_cid'"));
        if ($cid_pages == 0 and $cid_papki == 0) $pusto = "<span class='small red'>пустая папка</span>";
        if ($cid_pages > 0) $pusto = "<span class='small'>содержит $cid_pages ".num_ending($cid_pages, Array('страниц','страницу','страницы'))."</span>";
        if ($cid_papki > 0) $pusto = "<span class='small'>содержит $cid_papki ".num_ending($cid_papki, Array('папок','папку','папки'))."</span>";
        if ($cid_pages > 0 and $cid_papki > 0) $pusto = "<span class='small'>содержит $cid_papki ".num_ending($cid_papki, Array('папок','папку','папки'))." и $cid_pages ".num_ending($cid_pages, Array('страниц','страницу','страницы'))."</span>";
        
        $list .= "<div id=\"cid".$с_cid."\"><a name=\"open_pages_".$с_cid."\"></a><a class=\"no green punkt\" onclick='papka_show($с_cid, \"$name_raz\", \"$sort\", \"$id\",(Math.floor( Math.random() * (10000 - 10 + 1) ) + 10));'><img class=\"icon2 i40\" src=/images/1.gif class=left>".$name_cid."</a> ".$pusto." <div id=\"papka".$с_cid."\" style='display:inline; margin-left:5px;'></div><div id=\"podpapka".$с_cid."\" style='display:none;'></div><br></div>";
      }
    }
    // Страницы папки
    $dop_list = "";
    $granica = 10;
    $no_pages = 1;
    $sql = "SELECT pid, module, title, `date`, redate, counter, active, comm, mainpage, rss, description, keywords, copy FROM ".$prefix."_pages where `tables`='pages' and cid='$cid' and module='$name_raz' ORDER BY ".$order;
    $result = $db->sql_query($sql);
    $siz_page = $db->sql_numrows($result);
    if ($siz_page > 0) {
      while ($rows = $db->sql_fetchrow($result)) { 
        $pid = $rows['pid'];
        if ($sort==2) $date = date2normal_view(str_replace(".","-",$rows['redate']));
        else $date = date2normal_view(str_replace(".","-",$rows['date']));
        $date = str_replace(" ".date("Y"),"",$date);
        $name = $rows['module'];
        $title = strip_tags($rows['title'], '<b><i>');
        $active = $rows['active'];
        if (trim($title) == "") $title = "<span class=red>Страница без Названия. Отредактируйте или удалите!</span>";
        $counter = intval($rows['counter']);
        $comm = intval($rows['comm']);
        $mainpage = intval($rows['mainpage']);
        $rss = intval($rows['rss']);
        $description = trim($rows['description']);
        $keywords = trim($rows['keywords']);
        $copy = trim($rows['copy']);
        if ($copy == $pid) $copy = " <span class=green>(оригинал)</span>"; else $copy = "";
        $keydes = "";
        if ($keywords == "") $keydes = "<span class=red title='Нет ключевых слов'>*</span>"; 
        if ($description == "") $keydes = "<span class=red title='Нет описания'>*</span>"; 
        if ($keywords == "" and $description == "") $keydes = "<span class=red title='Нет описания и ключевых слов'>**</span>"; 
        if ($comm != 0) $keydes .= " <img class=\"icon2 i10\" src=/images/1.gif title='Комментарии:'>".$comm." "; 
        if ($counter != 0) $keydes .= " <img class=\"icon2 i42\" src=/images/1.gif title='Посещения:'>".$counter." "; 
        if ($mainpage == 1) $keydes .= "<span class=green title='Страница отмечена для Главной страницы'>*</span> "; 
        if ($rss == 0) $keydes .= " <span class=rss title='Отключен RSS'>rss</span> "; 
        $ver = mt_rand(10000, 99999); // получили случайное число
        $color=""; $nowork = "";
        if ($active == 0) { $color=" class=noact"; $nowork="<img class=\"icon2 i43\" src=/images/1.gif class=left title='Страница отключена.\nНажав по ней и выбрав такой же значок, вы можете ее включить.'>";}
        if ($active == 2) { $color=" class=deact"; $nowork="<img class=\"icon2 i44\" src=/images/1.gif class=left title='Страница требует проверки.\nНажмите по ней и выберите один из редакторов. Проверьте страницу и поставьте галочку Включить.'>";}
        if ($active == 3) { $color=" class=deact"; $nowork="<img class=\"icon2 i44\" src=/images/1.gif class=left title='Страница требует проверки!!!\nНажмите по ней и выберите один из редакторов. Проверьте страницу и поставьте галочку Включить.'>";}
        $pg = "<div id=\"page".$pid."\" class='gray openpage'><a href=#".$ver.$pid." onclick='sho($pid, \"$name\", \"base_pages\",".$active.");'".$color." class=punkt>".$nowork."".$title."</a>".$copy." ".$date." ".$keydes." <div id='pid".$pid."' class=pid></div></div>";
        if ($no_pages < $granica+1) $list .= $pg; 
        if ($no_pages > $granica) $dop_list .= $pg;
        $no_pages++;
      }
      $siz_page = $siz_page - $granica;
      if ($siz_page > 0) $list .= "<a id='doplistshow".$cid."' onmouseover='show(\"doplistshow".$cid."\"); show(\"doplist".$cid."\");' onclick='show(\"doplistshow".$cid."\"); show(\"doplist".$cid."\");' style='cursor:pointer;'><img class=\"icon2 i37\" src=/images/1.gif class=left><u>Раскрыть ещё $siz_page ".num_ending($siz_page, Array('страниц','страницу','страницы'))."</u></a><div style='display:none;' id=\"doplist".$cid."\">".$dop_list."</div>";
    } else $list .= "<img class=\"icon2 i39\" src=/images/1.gif class=left><span class=gray>В этой папке нет страниц.</span>";
    $list = "<div style='margin-left:15px; border-left: 1px dotted #999999;'>".$list."</div>";
    echo "<div class=block_white>".$list."</div>"; exit;
}
######################################################################################
if ($func == "razdel") { // Папка
  list($re, $sort) = explode("*@%", $string);
  if ($sort==1) $order = "title, date desc";
  elseif ($sort==0) $order = "date desc";
  elseif ($sort==2) $order = "redate desc";
  elseif ($sort==3) $order = "comm desc";
  elseif ($sort==4) $order = "counter desc";
  elseif ($sort==5) $order = "active";
  global $name_razdels;
  $list = "";
  $name_raz = $name_razdels[$id];
  // Если раздел
  if ($re > 0) $list .= " ";
  // Папки раздела
  $nopapka = 0;
  $nopage = 0;
  $sql = "SELECT cid, title, parent_id FROM ".$prefix."_pages_categories where module='$name_raz' and `tables`='pages' and parent_id='0' order by title";
  $result = $db->sql_query($sql);
  $siz_papka = $db->sql_numrows($result);
  if ($siz_papka > 0) {
    while ($rows = $db->sql_fetchrow($result)) {
      $с_cid = $rows['cid'];
      $name_cid = strip_tags($rows['title'], '<b><i>');
      $cid_pages = $db->sql_numrows($db->sql_query("select pid from ".$prefix."_pages where `tables`='pages' and module='$name_raz' and cid='$с_cid'"));
      if (trim($name_cid) == "") $name_cid = "<span class=red>Папка без Названия. Отредактируйте или удалите!</span>";
      
      $cid_papki = $db->sql_numrows($db->sql_query("select cid from ".$prefix."_pages_categories where `tables`='pages' and module='$name_raz' and parent_id='$с_cid'"));
      if ($cid_pages == 0 and $cid_papki == 0) $pusto = "<span class='small red'>пустая папка</span>";
      if ($cid_pages > 0) $pusto = "<span class='small'>содержит $cid_pages ".num_ending($cid_pages, Array('страниц','страницу','страницы'))."</span>";
      if ($cid_papki > 0) $pusto = "<span class='small'>содержит $cid_papki ".num_ending($cid_papki, Array('папок','папку','папки'))."</span>";
      if ($cid_pages > 0 and $cid_papki > 0) $pusto = "<span class='small'>содержит ".$cid_papki." ".num_ending($cid_papki, Array('папок','папку','папки'))." и ".$cid_pages." ".num_ending($cid_pages, Array('страниц','страницу','страницы'))."</span>";
      
      $list .= "<div id=\"cid".$с_cid."\"><a name=\"open_pages_".$с_cid."\"></a><a class=\"no green punkt\" onclick='papka_show(".$с_cid.", \"".$name_raz."\", \"".$sort."\", \"".$id."\",(Math.floor( Math.random() * (10000 - 10 + 1) ) + 10));'><img class=\"icon2 i40\" src=/images/1.gif class=left>".$name_cid."</a> ".$pusto." <div id=\"papka".$с_cid."\" style='display:inline; margin-left:5px;'></div><div id=\"podpapka".$с_cid."\" style='display:none;'></div><br></div>";
    }
  } else {
      $nopapka = 1;
      $list .= "<img class=\"icon2 i40\" src=/images/1.gif class=left><span class=gray>В этом разделе нет папок.</span>";
  }
  // Страницы раздела
  $dop_list = "";
  $granica = 10;
  $no_pages = 1;
  $list .= "<br>";
  $sql = "SELECT `pid`, `module`, `title`, `date`, `redate`, `counter`, `active`, `comm`, `mainpage`, `rss`, `description`, `keywords`, `copy` FROM ".$prefix."_pages where `tables`='pages' and `cid`='0' and `module`='$name_raz' ORDER BY ".$order;
  $result = $db->sql_query($sql);
  $siz_page = $db->sql_numrows($result);
  if ($siz_page > 0) {
    while ($rows = $db->sql_fetchrow($result)) {
      $pid = $rows['pid'];
      if ($sort==2) $date = date2normal_view(str_replace(".","-",$rows['redate']));
      else $date = date2normal_view(str_replace(".","-",$rows['date']));
      $date = str_replace(" ".date("Y"),"",$date);
      $name = $rows['module'];
      $title = strip_tags($rows['title'], '<b><i>');
      $active = $rows['active'];
      if (trim($title) == "") $title = "<span class=red>Страница без Названия. Отредактируйте или удалите!</span>";
      $counter = intval($rows['counter']);
      $comm = intval($rows['comm']);
      $mainpage = intval($rows['mainpage']);
      $rss = intval($rows['rss']);
      $description = trim($rows['description']);
      $keywords = trim($rows['keywords']);
      $copy = trim($rows['copy']);
      if ($copy == $pid) $copy = " <span class=green>(оригинал)</span>"; else $copy = "";
      $keydes = "";
      if ($keywords == "") $keydes = "<span class=red title='Нет ключевых слов'>*</span>"; 
      if ($description == "") $keydes = "<span class=red title='Нет описания'>*</span>"; 
      if ($keywords == "" and $description == "") $keydes = "<span class=red title='Нет описания и ключевых слов'>**</span>";
      if ($comm != 0) $keydes .= " <img class=\"icon2 i10\" src=/images/1.gif title='Комментарии:'>".$comm." "; 
      if ($counter != 0) $keydes .= " <img class=\"icon2 i42\" src=/images/1.gif title='Посещения:'>".$counter." "; 
      if ($mainpage == 1) $keydes .= "<span class=green title='Страница отмечена для Главной страницы'>*</span> "; 
      if ($rss == 0) $keydes .= " <span class=rss title='Отключен RSS'>rss</span> ";  
      $ver = mt_rand(10000, 99999); // получили случайное число
      $color=""; $nowork = "";
      if ($active == 0) { $color=" class=noact"; $nowork="<img class=\"icon2 i43\" src=/images/1.gif class=left title='Страница отключена.\nНажав по ней и выбрав такой же значок, вы можете ее включить.'>";}
      if ($active == 2) { $color=" class=deact"; $nowork="<img class=\"icon2 i44\" src=/images/1.gif class=left title='Страница требует проверки.\nНажмите по ней и выберите один из редакторов. Проверьте страницу и поставьте галочку Включить.'>";}
      if ($active == 3) { $color=" class=deact"; $nowork="<img class=\"icon2 i44\" src=/images/1.gif class=left title='Страница требует проверки!!!\nНажмите по ней и выберите один из редакторов. Проверьте страницу и поставьте галочку Включить.'>";}
      $pg = "<div id=\"page".$pid."\" class='gray openpage'><a href=#".$ver.$pid." onclick='sho($pid, \"$name\", \"base_pages\",".$active.");'".$color." class=punkt>".$nowork."".$title."</a>".$copy." ".$date." ".$keydes." <div id='pid".$pid."' class=pid></div></div>";
      if ($no_pages < $granica+1) $list .= $pg; 
      if ($no_pages > $granica) $dop_list .= $pg;
      $no_pages++;
    }
  $siz_page = $siz_page - $granica;
  if ($siz_page > 0) $list .= "<a id='doplistshow".$name_raz."' onmouseover='show(\"doplistshow".$name_raz."\"); show(\"doplist".$name_raz."\");' onclick='show(\"doplistshow".$name_raz."\"); show(\"doplist".$name_raz."\");' style='cursor:pointer;'><img class=\"icon2 i37\" src=/images/1.gif class=left><u>Раскрыть ещё $siz_page ".num_ending($siz_page, Array('страниц','страницу','страницы'))."</u></a><div style='display:none;' id=\"doplist".$name_raz."\">".$dop_list."</div>";
  } else {
      $nopage = 1;
      $list .= "<img class=\"icon2 i39\" src=/images/1.gif class=left><span class=gray>В корневой папке этого раздела нет страниц.</span>";
  }
  $list .= "<br><br></div>";
  if ($nopage == 1 and $nopapka == 1) {
      $list2 = "<div style=\"margin-left:20px;\">";
  } else {
      $list2 = "<div style=\"margin-left:20px; float:right;\">
      <button class='small' onclick=show('sortir_page')>Сортировка страниц <img class=\"icon2 i25\" src=/images/1.gif></button>
      <div id=sortir_page style='display:none;'><br>";
      // Сортировка страниц - выбор
      $sql = "SELECT `title` FROM ".$prefix."_mainpage where `tables`='pages' and `name`='$name_raz'";
      $result = $db->sql_query($sql);
      $rows = $db->sql_fetchrow($result);
      $title = strip_tags($rows['title'], '<b><i>');
      $pages_list = array("по дате создания","по алфавиту","по дате изменения","по кол-ву комментариев","по кол-ву просмотров","сначала — отключенные");
      for ( $i=0; $i < count($pages_list); $i++ ) {
          if ($sort==$i) {
              $list2 .= "&rarr;<strong>".$pages_list[$i]."</strong><br>";
          } else {
              $list2 .= "<a onclick=\"razdel_show('".$title."', '".$id."', '".$name_raz."', 'pages', '', '".$i."');\" class='punkt'>".$pages_list[$i]."</a><br>";
          }
      }
      $list2 .= "</div>"; 
  }
  echo $list2."</div>".$list; exit;
}
######################################################################################


}
?>