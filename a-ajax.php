<?php
// Все «правила хорошего кода» написаны кровью, вытекшей из глаз программистов, читавших чужой код.
header("Expires: " . date("r", time() + 3600));
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
require_once("mainfile.php");
global $prefix, $db, $admin, $now, $adminmail, $ip, $siteurl, $title_razdels_by_id, $deviceType;
if (is_admin($admin)) {
  if (isset($_REQUEST['func']))   $func = $_REQUEST['func']; else die(); // Выбор функции
  if (isset($_REQUEST['type']))   $type = $_REQUEST['type']; else $type = 0;
  if (isset($_REQUEST['id']))     $id = intval($_REQUEST['id']); else $id = 0;
  if (isset($_REQUEST['string'])) $string = $_REQUEST['string']; else $string = "";
/* ============================================================================= */
if ($func == "oformlenie_show") { // Выводим содержание различных оформлений
  $info = "";
  $admintip = "mainpage";
  switch($type) {

  case "trash":
    $n = "";
    $oformlenie_names = array("-"=>"",
      "0"=>"Дизайн", 
      "1"=>"Стиль", 
      "3"=>"Блоки", 
      "4"=>"Поля", 
      "5"=>"Базы данных", 
      "6"=>"Шаблоны");
    $sql = "select `id`,`type`,`title`,`useit` from ".$prefix."_mainpage where `tables`='del' and `type` != '2' order by `type`, `title`, `name`";
    $result = $db->sql_query($sql);
    $info = "<table width=100% class='table_light'>";
    if (!isset($nam)) $nam = "";
    while ($row = $db->sql_fetchrow($result)) {
      if ($n == $row['type']) $nu = "-";  
      else { $n = $nu = $row['type']; }
      if ($nu == "-") $block = "<tr valign=top id='block_".$row['id']."'><td class='padleft30'>"; 
      else $block = "<tr valign=top><td style='background:white;'><br><h2>".$oformlenie_names[$nu]." &darr;</h2></td></tr><tr id='block_".$row['id']."'><td class='padleft30'>";
      $title = $block.$row['title'];
      $info .= $title."<div style='float:right; display: inline;'>
       <a target='_blank' href='sys.php?op=mainpage&type=".$row['type']."&id=".$row['id']."&red=1' title='Редактировать'>".icon('black small','7')."</a> 
       <a class='padleft30 punkt' onclick='delblock(".$row['id'].",2)' title='Восстановить'>".icon('green small',';')."</a> 
       <a class='padleft30 punkt' onclick='delblock(".$row['id'].",1)' title='Удалить'>".icon('red small','F')."</a>
       </div></td></tr>";
    }
    $info .= "</table>";
  break;

  case "design":
    $sql = "select `id`,`title`,`useit` from ".$prefix."_mainpage where `tables`='pages' and `type`='0' order by `title`, `name`";
    $result = $db->sql_query($sql);
    $info .= "<table width=100% class='table_light'>";
    if (!isset($nam)) $nam = "";

    while ($row = $db->sql_fetchrow($result)) {
       $useit = explode(" ", trim($row['useit']));
       $css = "";
       foreach( $useit as $value ) {
        if ($value) $css .= " <a href='/sys.php?op=mainpage&id=".$value."' title='Редактировать' class='gray'><i>".$title_razdels_by_id[$value]."</i>".icon('black small','7')."</a> ";
       }
       if ($css == "") $css = "<i class='red'>стиль не выбран</i><br>";
       else $css = "Подключенные стили (css): ".$css."<br>";
       if ($row['title'] != "Главный дизайн") $delx = "<a class='padleft30 punkt' onclick='delblock(".$row['id'].",0)' title='Удалить'>".icon('red small','F')."</a>";
        else $delx = "<span class='padleft30'>".icon('gray small','X');
        
       $info .= "<tr valign='top' id='block_".$row['id']."'><td><h2>".$row['title']." &darr;</h2>
       <div style='float:right; display: inline;'>
       <a href='sys.php?op=mainpage&id=".$row['id']."&red=1' title='Редактировать в HTML'>".icon('orange small','7')."</a> ".$delx."</div>";
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
    $sql = "select `id`, `title` from ".$prefix."_mainpage where `tables`='pages' and `type`='1' order by `title`, `name`";
    $result = $db->sql_query($sql);
    $info = "<table width=100% class=table_light>";
    while ($row = $db->sql_fetchrow($result)) {
     if ($row['title'] != "Главный стиль") $delx = "<a class='padleft30 punkt' onclick='delblock(".$row['id'].",0)' title='Удалить'>".icon('red small','F')."</a>";
     else $delx = "<span class='padleft30'>".icon('gray small','X');
     $info .= "<tr id='block_".$row['id']."'><td><h2>".$row['title']."<div style='float:right; display: inline;'><a href='sys.php?op=mainpage&type=1&id=".$row['id']."' title='Редактировать'>".icon('black small','7')."</a> ".$delx."</h2></div></td></tr>";
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
      "9"=>"Экстрактор страниц", 
      "4"=>"Папки раздела", 
      "8"=>"Папки открытого раздела", 
      "0"=>"Страницы раздела", 
      "1"=>"Комментарии раздела", 
      "11"=>"Календарь", 
      "12"=>"Форма для заполнения (анкеты, опросы и т.д.)", 
      "13"=>"Облако тегов (<i>ключевых слов</i>)", 
      "30"=>"Посещаемость раздела", 
      "31"=>"JavaScript (<i>ставится на место автоматически</i>)", 
      "7"=>"PHP-код (<i>вывод через переменную \$txt</i>)", 
      "20"=>"База данных (<i>количество по 1 колонке, вертикально</i>)", 
      "21"=>"База данных (<i>количество по 1 колонке, горизонтально</i>)", 
      "22"=>"База данных (<i>количество по 2 колонкам</i>)", 
      "23"=>"База данных (<i>список колонок</i>)");
    global $uskorenie_blokov;
    $sql = "select `id`,`name`,`title`,`color` from ".$prefix."_mainpage where `tables`='pages' and `type`='3' order by `name`, `title`";
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
          $title = '<a href="sys.php?op=mainpage&type=3&id='.$row['id'].'&red=1">'.$row['title'].'</a>'.$diz.$razr.$bloc.$stri;
      } else $title = '<a href="sys.php?op=mainpage&type=3&id='.$row['id'].'&red=1">'.$row['title'].'</a>';

      if ($row['color'] != "1") {
        $icon_disable = "red"; $text_disable = "Отключить блок"; $class_disable = "";
      } else {
        $icon_disable = "green"; $text_disable = "Включить блок"; $class_disable = " bggray";
      }
      if ($n == $row['name']) $nu = "-";  else { $n = $row['name']; $nu = $row['name']; }
      $bgcolor = "#FFeecc"; //FFddaa

       if ($nu == "-") $block = "<tr valign=top id='block_".$row['id']."'><td class='padleft30".$class_disable."'>"; 
       else $block = "<tr valign=top><td style='background:white;'><br><h2>".$block_names[$nu]." &darr;</h2></td></tr><tr id='block_".$row['id']."'><td class='padleft30".$class_disable."'>";
      $title = $block.$title;
      $blocks_ok = $title."<div style='margin-left:20px; display: inline; float:right;'>
       <a href='sys.php?op=mainpage&type=3&id=".$row['id']."&red=1' title='Редактировать в HTML'>".icon('black small','7')."</a> 
       <a href='sys.php?op=mainpage&type=3&id=".$row['id']."&nastroi=1' title='Настроить блок'>".icon('yellow medium','V')."</a> 
       <a class='padleft30 punkt' onclick='offblock(".$row['id'].")' title='".$text_disable."'>".icon($icon_disable.' small','Q')."</a>  
       <a class='padleft30 punkt' onclick='delblock(".$row['id'].",0)' title='Удалить блок'>".icon('red small','F')."</a>
       </div>
       </td></tr>";
      $blocks_no .= $blocks_ok;
    }
    $info .= "<br><span class=green>Названия блоков в [квадратных скобках] можно использовать для вставки в дизайн, разделы, папки, страницы, шаблоны или другие блоки (т.е. в любом месте сайта).</span><table class='table_light w100'>".$blocks_no."</table>";
  break;


  case "shablon": // 6
    $sql = "select `id`, `title` from ".$prefix."_mainpage where `tables`='pages' and `type`='6' order by `type`, `title`, `name`";
    $result = $db->sql_query($sql);
    $info .= "<table width=60% class=table_light>";
    while ($row = $db->sql_fetchrow($result)) {
      //$id = $row['id'];
      //$type = $row['type'];
      //$nam = $row['name']; 
      //$title = $row['title'];
      //$useit = $row['useit'];
      //$useit_module = "";
      //$text = $row['text'];
      $redactor = "<div style='float:right;'><a href='sys.php?op=mainpage&id=".$row['id']."&red=1&type=6' title='Редактировать'>".icon('black small','7')."</a> ";
      $redactor .= "<a class='padleft30 punkt' onclick='delblock(".$row['id'].",0)' title='Удалить'>".icon('red small','F')."</a></div>";
      $info .= "<tr id='block_".$row['id']."'><td>".$redactor."<h2>".$row['title']."</h2></td></tr>";
    }
    $info .= "</table>";
  break;


  case "pole": // 4
    $sql = "select `id`,`name`,`title`,`text`,`useit`,`shablon` from ".$prefix."_mainpage where `tables`='pages' and `type`='4' order by `title`, `name`";
    $result = $db->sql_query($sql);
    if ($db->sql_numrows($result) > 0) {
      //$info .= "<a class='button small red white' href='sys.php?op=mainpage_recycle_spiski'>".icon('white small','x')." Очистить пустые поля</a>
      $info .= "<table width=100% class=table_light>";
      while ($row = $db->sql_fetchrow($result)) {
        $useit = $row['useit'];
        $shablon = trim($row['shablon']);
        $and = "";
        $s_tip = explode("|",$row['text']); 
        $s_tip = explode("&",$s_tip[1]); 
        $s_tip = explode("=",$s_tip[0]);
        if ($s_tip[1]==0) $and = "список слов (выбор одного значения)";
        if ($s_tip[1]==1) $and = "текст";
        //if ($s_tip[1]==2) $and = "файл";
        if ($s_tip[1]==3) $and = "период времени (две даты, актуально для Афиши)";
        if ($s_tip[1]==4) $and = "строка";
        if ($s_tip[1]==5) $and = "число";
        if ($s_tip[1]==6) $and = "регион (регионы можно выбрать в настройках)";
        if ($s_tip[1]==7) $and = "список слов (выбор нескольких значений)";
        if ($useit=="0") { $razdel_title = "все разделы"; $papka_title = ""; }
        else {
          if ($shablon=="0" or $shablon==" 0 ") $papka_title = ", все папки";
          else { // находим кол-во папок
            $shablon = explode(" ",$shablon);
            $shablon = count($shablon);
            $papka_title = ", ".$shablon." ".num_ending($shablon, Array(aa("папок"),aa("папка"),aa("папки")));
          }
          $razdel_title = "<a href=/-".$useit.">".$title_razdels_by_id[$useit]."</a>";
        }
        $redactor = "<div style='float:right;'><a href='sys.php?op=mainpage&id=".$row['id']."&red=1&type=4' title='Редактировать'>".icon('black small','7')."</a> 
        <a class='padleft30 punkt' onclick='delblock(".$row['id'].",0)' title='Удалить'>".icon('red small','F')."</a></div>";
        $info .= "<tr id='block_".$row['id']."' onmouseover='$(\"#hide_".$row['id']."\").show();' onmouseout='$(\"#hide_".$row['id']."\").hide();'><td>".$redactor."<h2>".$row['title']."</h2>
        <span id='hide_".$row['id']."' class='hide'><sup style=\"color:#999999;\">Блок для использования в шаблоне: [".$row['name']."]</sup><br>Раздел: ".$razdel_title.$papka_title.".<br>Тип: ".$and.".</span></td></tr>";
      }
      $info .= "</table>";
    }
  break;


  case "base": // 5
    $sql = "select `id`,`name`,`title`,`text` from ".$prefix."_mainpage where `tables`='pages' and `type` = '5' order by `type`, `title`, `name`";
    $result = $db->sql_query($sql);
    $info .= "<table width=60% class=table_light>";
    while ($row = $db->sql_fetchrow($result)) {
      $ti = "";
      $text = explode("|",$row['text']);
      $options = $text[1];
      $text = $text[0];
      if (strpos(" ".$options,"base=")) $text = "base";
      if ($row['name'] != "index") {
        if (!strpos(" ".$options,"base=")) $link = ""; 
        else $link = "<a class='button medium' href=/sys.php?op=base_base&name=".$row['name'].">".icon('blue small','s')." открыть базу данных</a>"; 
      } else $link = "";
      $info .= "<tr id='block_".$row['id']."'><td><div style='float:right;'><a class='padleft30 punkt' onclick='delblock(".$row['id'].",0)' title='Удалить'>".icon('red small','F')."</a></div>".$row['title']." ".$link."</td></tr>";
    }
    $info .= "</table><p>Для редактирования таблица должна быть подключена к разделу (подключение возможно через настройки раздела).<br>При создании таблицы она автоматически создает одноименный раздел и подключается к нему.<br>Редактируется таблица через раздел — на вкладке Содержание.</p>";
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
if ($func == "show_pole") { // Ответ на комментарий из администрирования

  function spisok_name($s_name,$page_id,$arr=0,$add="") { // Получаем значение поля
    // Если arr=1 - передаем массив
    // add - условия сортировки sql-запроса
    global $db, $prefix;
    $result = $db->sql_query("SELECT `name` FROM ".$prefix."_spiski WHERE `type`='".$s_name."' and `pages` like '% ".$page_id." %'".$add);
    if ($arr==0) {
      $row = $db->sql_fetchrow($result);
      return $row['name'];
    } else {
      $s_names = array();
      while ($row = $db->sql_fetchrow($result)) {
        $s_names[] = $row['name'];
      }
      return $s_names;
    }    
  }

  list($razdel, $page_id, $cid) = explode("*@%", $string);
  $info = ""; //$id, $razdel, $page_id, $cid";

  // Ищем все списки по разделу
  $sql = "select `id`, `title`, `name`, `text` from ".$prefix."_mainpage 
  where (`useit`='".$id."' or `useit`='0') and (`shablon` like '% ".$cid." %' or `shablon` = '' or `shablon` = '0' or `shablon` = ' 0 ') 
  and `type`='4' order by `title`";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $s_id = $row['id'];
    $s_title = $row['title'];
    $s_name = $row['name'];
    $options = explode("|", $row['text']);
    $options = $options[1];
    $type=0;
    $shablon=""; 
    parse_str($options); // раскладка всех настроек списка
    switch($type) {
      ///////////////////
      case "7": // список слов (множественный выбор)
        if ($page_id > 0) $sp_names = spisok_name($s_name,$page_id,1);
        $info .= "<p><b>".$s_title.":</b><br>";
        $sql2 = "select * from ".$prefix."_spiski where type='".$s_name."' order by parent,id";
        $result2 = $db->sql_query($sql2);
        $info .= "<select size=10 class='f12' multiple=multiple name='add[".$s_name."][]'>";
        $opt = $sel_ok = " selected";
        while ($row2 = $db->sql_fetchrow($result2)) {
          $s_id2 = $row2['id'];
          $s_title2 = $row2['name'];
          $s_opis = $row2['opis'];
          $s_parent = $row2['parent'];
          $s_title2 = getparent_spiski($s_name,$s_parent,$s_title2);
          $sel = ""; 
          if ( ($page_id > 0 && in_array($s_title2,$sp_names)) || $razdel == $s_id2 ) { $sel = " selected"; $sel_ok = ""; }
          if ($s_opis != "") $s_opis = " (".$s_opis.")";
          $opt .= "<option value=".$s_id2.$sel."> ".$s_title2.$s_opis."</option>";
        }
        $info .= "<option value=0".$sel_ok.">ничего не выбрано</option>".$opt."</select><br>".aa("Для выбора нескольких зажмите <nobr>клавишу <code>Ctrl</code></nobr> <nobr>или <code>⌘Cmd</code> (на МакОС).</nobr>");
      break;

      case "0": // список слов (единичный выбор)
        if ($page_id > 0) $sp_names = spisok_name($s_name,$page_id,1);
        $info .= "<p><b>".$s_title.":</b><br>";
        $sql2 = "select * from ".$prefix."_spiski where type='".$s_name."' order by parent,id";
        $result2 = $db->sql_query($sql2);
        $info .= "<select class='f12' name='add[".$s_name."]'><option value=0>ничего не выбрано</option>";
        while ($row2 = $db->sql_fetchrow($result2)) {
          $s_id2 = $row2['id'];
          $s_title2 = $row2['name'];
          $s_opis = $row2['opis'];
          $s_parent = $row2['parent'];
          $s_title2 = getparent_spiski($s_name,$s_parent,$s_title2);
          $sel = ""; 
          if ( ($page_id > 0 && in_array($s_title2,$sp_names)) || $razdel == $s_id2 ) { $sel = " selected"; }
          if ($s_opis != "") $s_opis = " (".$s_opis.")";
          $info .= "<option value=".$s_id2.$sel."> ".$s_title2.$s_opis."</option>";
        }
        $info .= "</select>";
      break;
      ///////////////////
      case "1": // текст
        if ($page_id > 0) $shablon = spisok_name($s_name,$page_id);
        $info .= "<p><b>".$s_title.":</b><br><textarea name='add[".$s_name."]' rows='4' cols='60' class='w100'>".$shablon."</textarea>";
      break;
      ///////////////////
      case "2": // файл (НЕ_ГОТОВО!!!)
      /*
        if ($page_id > 0) $sp_names = spisok_name($s_name,$page_id);
        // Пример настройки: file=pic&papka=/img=verh&resizepic=x&file=&picsize=600&minipic=1&resizeminipic=x&minipicsize=100
        switch($fil) {
          case "pic": $type_fil = "картинка"; break;
          case "doc": $type_fil = "документ/архив"; break;
          case "flash": $type_fil = "flash-анимация"; break;
          case "avi": $type_fil = "видео-ролик"; break;
        }
        $type_mini="";
        if ($minipic==1) $type_mini = "Также будет создана миниатюра.";

        $info .= "<p><b>".$s_title.":</b><br><input type=file name='add[".$s_name."]' size=30> 
        <b>или ссылка:</b> <input type=text name='add[".$s_name."]_link' value='".$papka."' size=30><br>
        Файл (".$type_fil.") сохранится в ".$papka.", на странице будет ".$type_mesto.". ".$type_mini;
        */
      break;
      ///////////////////
      case "3": // период времени
        if ($page_id > 0) {
          $date1 = date2normal_view( spisok_name($s_name,$page_id," order by name") );
          $date2 = date2normal_view( spisok_name($s_name,$page_id," order by name desc") );
          $data3 = $date1."|".$date2;
        } else {
          $date1 = $date2 = "";
          $data3 = "дата";
        }
        $info .= "<p><b>".$s_title.":</b> (выберите даты из меню, кликнув по значкам)<br>
        <TABLE cellspacing=0 cellpadding=0 style='border-collapse: collapse'><TBODY><TR> 
        <TD><INPUT type=text name='text[".$s_name."]' id='f_date_c[".$s_name."]' value='".$date1."' onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD>
        <TD><IMG src=/images/calendar.gif id='f_trigger_c[".$s_name."]' title='Выбор даты'></TD>
        <TD width=20 align=center> - </TD>
        <TD><INPUT type=text name='text[".$s_name."]' id='f_date_c2[".$s_name."]' value='".$date2."' onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD> 
        <TD><IMG src=/images/calendar.gif id='f_trigger_c2[".$s_name."]' title='Выбор даты'></TD>
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
        <input type=hidden name='add[".$s_name."]' id='add[".$s_name."]' value='".$date3."'>"; //
      break;
      ///////////////////
      case "4": // строка
        if ($page_id > 0) $shablon = spisok_name($s_name,$page_id);
        $info .= "<p><b>".$s_title.":</b><br><INPUT name='add[".$s_name."]' id='".$s_name."' type=text value='".$shablon."' class='w45'>";
        
        $result1 = $db->sql_query("select `name` from ".$prefix."_spiski where type='".$s_name."' order by id desc limit 100");
        $opt = "";
        while ($row1 = $db->sql_fetchrow($result1)) {
          if ($row1['name'] != $shablon) $opt .= "<option value=\"".$row1['name']."\">".$row1['name']."</option>";
        }
        if ($opt != "") $info .= "<select onchange=\"$('#".$s_name."').val(this.value);\" class='w45'><option value=\"\" style=\"background:#dddddd;\">".aa("варианты...")."</option>".$opt."</select>";
      break;
      ///////////////////
      case "5": // число
        if ($page_id > 0) $shablon = spisok_name($s_name,$page_id);
        $info .= "<p><b>".$s_title.":</b><br><INPUT name='add[".$s_name."]' type='number' value='".$shablon."' class='w45'>";
      break;
      ///////////////////
      case "6": // регион
        if ($page_id > 0) {
          $sp_name = spisok_name($s_name,$page_id);
          $namereg = $db->sql_fetchrow($db->sql_query("SELECT `id` FROM ".$prefix."_regions WHERE `name`='".$sp_name."'"));
          $namereg = $namereg['id'];
        } else {
          $namereg = "";
          $sp_name = "Выберите область...";
        }
        $info .= "<p><b>".$s_title.":</b><br><script type='text/javascript' src='includes/regions/jquery.livequery.js'></script>
        <script type='text/javascript'>
        $(document).ready(function() {
            //$('#loader').hide();
            $('.parent').livequery('change', function() {
              $(this).nextAll('.parent').remove();
              $(this).nextAll('label').remove();
              $('#show_sub_categories').append('<img src=\"includes/regions/loader.gif\" class=\"left3\" id=\"loader\" />');
              $.post(\"get_chid_categories.php\", {
                parent_id: $(this).val(),
              }, function(response){
                setTimeout(\"finishAjax('show_sub_categories', '\"+escape(response)+\"')\", 400);
              });
              return false;
            });
          });
          function finishAjax(id, response){
            $('#loader').remove();
            $('#'+id).append(unescape(response));
          }</script><br clear='all' /><br clear='all' />
          <div id='show_sub_categories'>
          <select name='add[".$s_name."]' class='parent'>
          <option value='".$namereg."' selected='selected'>".$sp_name."</option>";
        include("includes/regions/list.html");
        $info .= '</select></div><br clear="all" /><br clear="all" />';
      break;
    }
  }
  echo $info; exit;
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
  list($select, $papka, $this_cid) = explode("*@%", $string);
  $info = "";
  $main_papka = "Основная папка («корень»)";
  switch ($type) {
    case "addpage": 
      $info .= "<select name='cid' onchange='
    ra_val = $(\"#to_razdel\").val();
    show_pole(ra[ra_val],page_id,ra_val,this.value);
    ' id='to_papka' size='2' class='w100' style='height:200px;'>"; break;
    case "editdir": 
      $info .= "<select name='parent_id' id='to_papka' size='2' onchange='if(this.value==\"".$this_cid."\") this.value=\"".$papka."\"' style='width:248px; height:400px;'>"; break;
    case "izmenapage": 
      $info .= "<select class='w100' name='to_papka' id='to_papka".$id."' size='10'>"; break;
    case "papka_in_pole": 
      global $name_razdels;
      if (isset($name_razdels[$select])) $select = $name_razdels[$select];
      $main_papka = "Все папки (по-умолчанию)";
      $info .= "<select class='w100' multiple='multiple' name='shablon[]' id='papki' size='20'>"; break;
  }
  $sql = "select cid, module, title, parent_id from ".$prefix."_pages_categories where module='".$select."' and `tables`='pages' order by parent_id, title";
  $result = $db->sql_query($sql) or $info = "Ошибка. Попробуйте обновить страницу. Не поможет — обращайтесь к разработчику.";

  $info .= "<option value=0 selected>".$main_papka."</option>";
  $last_cid = 0;
  $title = $par = $module = array();
  while ($row = $db->sql_fetchrow($result)) {
    $id = $row['cid'];
    $title[$id] = strip_tags($row['title'], '<b><i>');
    $par[$id] = $row['parent_id'];
    //$module[$id] = $row['module'];
  }
  if (count($title)>0) {
    foreach ($title as $id => $nam) {
      if ($par[$id]==0) { // папка, содержащая подпапки
        if ($papka == $id) $sel = "selected"; else $sel = "";
        $info .= "<option value=".$id." ".$sel." style='background:#fdf;'>".$nam."</option>";
        if (in_array($id, $par)) {
          foreach ($title as $id2 => $nam2) {
            if ($par[$id2]==$id) { // папка, содержащая подпапки
              if ($papka == $id2) $sel = "selected"; else $sel = "";
              $info .= "<option value=".$id2." ".$sel.">&bull; ".$nam2."</option>";
              if (in_array($id2, $par)) {
                foreach ($title as $id3 => $nam3) {
                  if ($par[$id3]==$id2) { // подпапка
                    if ($papka == $id3) $sel = "selected"; else $sel = "";
                    $info .= "<option value=".$id3." ".$sel.">&bull;&bull; ".$nam3."</option>";
                  }
                }
              }
            }
          }
        }
      }
    }
  }
  $info .= "</select>";
  echo $info; exit;
}
######################################################################################
if ($func == "addpapka") { // Добавляем папку(и)
  list($title, $parent) = explode("*@%", $string);
  global $name_razdels, $title_razdel_and_bd;
  $name_raz = $name_razdels[$id];
  if (strpos($title, "&&&")) $title = explode("&&&",$title);
  else $title = explode("\n",$title);
  foreach( $title as $title_value ) {
    $pap = mysql_real_escape_string(trim($title_value));
    $title_name = explode("|",$title_value);
    $title_opis = $title_name[1];
    $title_name = $title_name[0];
    if ($pap!="" && $title_name!="") $db->sql_query("INSERT INTO ".$prefix."_pages_categories VALUES (NULL, '$name_raz', '$title_name', '$title_opis', '', '0', '0', '$parent', 'pages')");
  }
  echo $title_razdel_and_bd[$name_raz]; exit;
}
######################################################################################
if ($func == "addpages") { // Добавляем страницы
  list($title, $cid) = explode("*@%", $string);
  global $name_razdels, $title_razdel_and_bd, $now;
  $name_raz = $name_razdels[$id];
  if (strpos($title, "&&&")) $title = explode("&&&",$title);
  else $title = explode("\n",$title);
  foreach( $title as $value ) {
    $pap = mysql_real_escape_string(trim($value));
    $value = explode("|",$value);
    $tit = $value[0];
    $open_text = $value[1];
    $main_text = $value[2];
    $active = $value[3]; if ($active=="") $active = "1";
    if ($pap!="" && $tit!="") $db->sql_query("INSERT INTO ".$prefix."_pages VALUES (NULL, '".$name_raz."', '".$cid."', '".$tit."', '".$open_text."', '".$main_text."', '".$now."', '".$now."', '0', '".$active."', '0', '0', '', '', '', '".$active."', '', '', '', 'pages', '0','0', '0');");
  }
  echo $title_razdel_and_bd[$name_raz]; exit;
}
######################################################################################
if ($func == "offpage") { // вкл./выкл. страницы
  $color=""; $nowork = "";
  $active = $db->sql_fetchrow($db->sql_query("SELECT pid, module, title, active FROM ".$prefix."_pages where pid='$id'"));
  if ($active['active'] == 1) { $act = 0; $color=" class=noact"; $nowork = icon('red small','Q'); } 
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
  exit;
}
######################################################################################
if ($func == "delblock") { 
  // type=0 Удаление оформления в Удаленные
  // type=1 Полное удаление оформления
  // type=2 Восстановление оформления
  if ($type==0) $db->sql_query("UPDATE ".$prefix."_mainpage SET `tables`='del' WHERE `id`='".$id."'"); 
  if ($type==1) $db->sql_query("DELETE from ".$prefix."_mainpage WHERE `id`='".$id."'");
  if ($type==2) $db->sql_query("UPDATE ".$prefix."_mainpage SET `tables`='pages' WHERE `id`='".$id."'");
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
  $active = $db->sql_fetchrow($db->sql_query("SELECT `active` FROM ".$prefix."_pages_comments where cid='$id'"));
  if ($active['active'] == 1) { 
    $act = 0; $comm = "Комментарий выключен"; $color="error"; 
  } else { 
    $act = 1; $comm = "Комментарий включен"; $color="success";
  }
  $db->sql_query("UPDATE ".$prefix."_pages_comments SET `active`='$act' WHERE cid='$id'");
  echo "<td colspan=2 class='notice ".$color."'>".$comm."</td>"; exit;
}
######################################################################################
if ($func == "offblock") { // Вкл./Выкл. блока
  $active = $db->sql_fetchrow($db->sql_query("SELECT `color` FROM ".$prefix."_mainpage where id='$id'"));
  if ($active['color'] != "1") { 
    $act = "1"; $comm = "Блок выключен"; $color="error"; 
  } else { 
    $act = "0"; $comm = "Блок включен"; $color="success"; 
  }
  $db->sql_query("UPDATE ".$prefix."_mainpage SET `color`='$act' WHERE id='$id'");
  echo "<td class='padleft30 notice ".$color."'>".$comm."</td>"; exit;
}
######################################################################################
if ($func == "add_pages") { // Создание страниц
  // Узнаем название раздела
  global $name_razdels;
  $name_raz = $name_razdels[$id];
  $list = "<form method=post style=\"display:inline;\" onsubmit='return false'>
  <h1>Добавим сразу несколько страниц</h1>
  <table width=100% class='table_light'><tr valign=top><td>
  <span class=h2>Заголовки страниц:</span><br>
  <textarea name=title2 id='title".$id."' rows=5 cols=3 style='width:100%; height: 200px;' autofocus></textarea>
  </td></tr><tr><td><span class=h2>В начале раздела или вложены в папку?</span><br>";
  $sql = "select cid, title, parent_id from ".$prefix."_pages_categories where module='".$name_raz."' and `tables`='pages' order by parent_id,cid";
  $result = $db->sql_query($sql);
  $list .= "<select id='select".$id."' name=parent_id style='width: 100%;'><option value=0>в начале раздела</option>";
  while ($row = $db->sql_fetchrow($result)) {
    $cid = $row['cid'];
    $title = strip_tags($row['title'], '<b><i>');
    $parentid = $row['parent_id'];
    $title = getparent($name_raz,$parentid,$title);
    $list .= "<option value=".$cid.">вложены в «".$title."»</option>";
  }
  $list .= "</select><br>
  <input type=submit value=\" Добавить \" onclick=\"save_papka('".$id."',document.getElementById('title".$id."').value,document.getElementById('select".$id."').value,'".$name_raz."',1);\" style='width:100%; height:55px; font-size: 22px; margin-top:20px;'>
  </td></tr></table>
  <blockquote class=small>Вы можете создать сразу несколько страниц — пишите их заголовки в столбик, разделяя Enter.<br>Добавить предисловие, содержание и активность (вкл./выкл = 1/0) к страницам можно сразу после заголовка через символ «|». Пример: Заголовок|Предисловие|Содержание|1<br>
  В качестве разделителя страниц по-умолчанию используется Enter, но если вам нужно сразу добавить страницы с большим количеством текста, в котором присутствуют переносы строк — используйте разделитель &&&<br>
  Если вы не видите созданных страниц — обновите страницу (нажав F5) и вновь откройте этот раздел.</blockquote>
  </form>";
  $list = close_button('add_papka').$list."<hr>";
  echo $list; exit;
}
######################################################################################
if ($func == "add_papka") { // Создание папки
  // Узнаем название раздела
  global $name_razdels;
  $name_raz = $name_razdels[$id];
  $list = "<form method=post style=\"display:inline;\" onsubmit='return false'>
  <h1>Создадим папку (или папки) в этом разделе</h1>
  <table width=100% class='table_light'><tr valign=top><td>
  <span class=h2>Имя папки:</span><br>
  <textarea name=title2 id='title".$id."' rows=5 cols=3 style='width:100%; height: 200px;' autofocus></textarea>
  </td></tr><tr><td><span class=h2>В начале раздела или вложена в другую папку?</span><br>";
   $sql = "select cid, title, parent_id from ".$prefix."_pages_categories where module='".$name_raz."' and `tables`='pages' order by parent_id,cid";
   $result = $db->sql_query($sql);
   $list .= "<select id='select".$id."' name=parent_id style='width: 100%;'><option value=0>в начале раздела</option>";
   while ($row = $db->sql_fetchrow($result)) {
     $cid = $row['cid'];
     $title = strip_tags($row['title'], '<b><i>');
     $parentid = $row['parent_id'];
     $title = getparent($name_raz,$parentid,$title);
     $list .= "<option value=".$cid.">вложена в «".$title."»</option>";
   }
  $list .= "</select><br>
  <input type=submit value=\" Создать \" onclick=\"save_papka('".$id."',document.getElementById('title".$id."').value,document.getElementById('select".$id."').value,'".$name_raz."',0);\" style='width:100%; height:55px; font-size: 22px; margin-top:20px;'>
  </td></tr></table>
  <blockquote class=small>Вы можете создать сразу несколько папок — пишите их имена в столбик, разделяя Enter.<br>Добавить описание к папке можно сразу после названия через символ «|». Пример: Название | Описание.<br>
  В качестве разделителя страниц по-умолчанию используется Enter, но если вам нужно сразу добавить папки с большим количеством текста в описании, в котором присутствуют переносы строк — используйте разделитель &&&<br>
  Если вы не видите созданной папки — обновите страницу (нажав F5) и вновь откройте этот раздел.</blockquote>
  </form>";
  $list = close_button('add_papka').$list."<hr>";
  echo $list; exit;
}
######################################################################################
if ($func == "opengarbage") { // Открытие вкладок Содержания
  global $title_razdel_and_bd, $lang;
  $color=$pageslistdel=$nowork = "";

  if ($id == 10) {
    $options = ""; // Выборка настроек
    $styles2 = ""; // Выяснить основной дизайн у всех разделов
    $sql3 = "select `title`, `text` from ".$prefix."_mainpage where `type`='2' and `name`!='index' and `tables`!='del'";
    $result3 = $db->sql_query($sql3);
    $num_razdel = $db->sql_numrows($result3);
    if ($num_razdel > 0) {
        while ($row3 = $db->sql_fetchrow($result3)) {
            $design = 1;
            $parse = explode("|",$row3['text']);
            parse_str($parse[1]); // Разложим на переменные и узнаем дизайн раздела
            $styles2[] = $design;
            $options .= "<option value='".$parse[1]."'>раздела «".$row3['title']."»</option>";
        }
        $styles2 = array_count_values($styles2);
        reset($styles2);
        arsort($styles2);
        $styles2 = key($styles2);
    }
        
      $styles = ""; // Выборка дизайнов
      $sql2 = "select `id`, `title` from ".$prefix."_mainpage where `type`='0'";
      $result2 = $db->sql_query($sql2);
      while ($row2 = $db->sql_fetchrow($result2)) {
        $id_design = $row2['id'];
        $title_design = trim($row2['title']);
        $sel = "";
        if ($styles2 == $row2['id']) {
          $title_design .= " (используется чаще всего)";
          $sel = " selected";
        }
        $styles .= "<option value='".$row2['id']."'".$sel.">".$title_design."</option>";
      }
    $pageslistdel .= "<form method='post' action=sys.php>
      <TABLE width=100% class='table_light'><tr><td>
      <span class=h2>Название раздела:</span><br>
      <input id=name_razdel type=text name=title size=30 class=w100 autofocus><br>
      <i>Примеры: «О нас», «Наша продукция», «Каталог», «Контакты» и т.д.</i> <a class=punkt onclick='$(\"#psevdonim\").toggle(\"slow\");'>Псевдоним</a> будет создан транслитом.<br>
      </td></tr>
      <tr id=psevdonim style='display:none;'><td>
      <span class=h3>Псевдоним:</span><br>
      <input type=text name=namo size=30 class=w100><br>
      <a href=# onclick=\"window.open('http://translate.google.ru/#".$lang."/en/' + $('#name_razdel').val(),'Перевод',' width=800,height=400'); return false;\"><b>Перевести название</b></a>. <i>Используются англ. буквы и знак «_», без пробелов. Примеры: «about_me», «product», «catalog», «contact» и т.д.</i>
      </td></tr><tr><td>
      <span class=h2>Использовать настройки:</span><br><select name=text size=10 class=w100>
      <option value='[название]'>• раздел будет отдельной страницей без вложенных папок и страниц</option>
      <option value='lim=15&amp;comments=0' selected>• вариант «Статьи» (15 страниц на листе, комментарии выключены)</option>
      <option value='lim=10&amp;comments=1&amp;comments_add=1&amp;vetki=2&amp;comments_mail=1&amp;comments_adres=1'>• вариант «Блог» (10 страниц на листе, комментарии включены)</option>
      <option value='lim=500&amp;comments=0'>• вариант «Каталог» (500 страниц на листе, комментарии выключены)</option>
      ".$options."</select><br><i>Можно моментально настроить новый раздел, выбрав один из вариантов или ранее созданных разделов.</i>
      </td></tr><tr><td>
      <span class=h2>Выберите дизайн:</span><br>
      <select name=useit class=w100>".$styles."</select><br><i>Дизайн раздела окружает содержимое раздела оформлением, он обязательно должен содержать в себе блок [содержание] (который выводит содержание раздела), а также у дизайна должен быть выбран стиль (css).</i>
      </td></tr></table>
      <input type=\"submit\" value=\"Добавить раздел\" class='w100 h40 f16'>
      <input type=hidden name=type value='2'>
      <input type=hidden name=shablon>
      <input type=hidden name=id value=''>
      <input type=hidden name=op value=mainpage_save>
      </form>";
  } elseif ($id == 8) { // ПОМОЩЬ на Главной
    require_once ('ad/help.php');

  } elseif ($id == 5) { // НОВОЕ
    $pageslistdel .= "<table width=100% class=table_light><thead><tr><th>Дата последнего изменения</th><th>Раздел </th><th class='gray'>Включение</th><th>Страница</th></tr></thead><tbody>";
    $result6 = $db->sql_query("SELECT `pid`, `module`, `title`, `active`, `date`, `redate` from ".$prefix."_pages where `tables`='pages' order by `redate` desc limit 0,1000");
    while ($row6 = $db->sql_fetchrow($result6)) {
        $pid = $row6['pid'];
        $title = strip_tags($row6['title'], '<b><strong><em><i>');
        if (trim($title) == "") $title = "< страница без названия >";
        $module = $row6['module'];
        $date = date2normal_view(str_replace(".","-",$row6['redate']), 2, 1);
        $gray_date = " gray";
        if (date2normal_view(str_replace(".","-",$row6['redate'])) == date2normal_view(str_replace(".","-",$row6['date']))) $gray_date = "";
        if (!isset($module)) $title_razdel_and_bd[$module] = "РАЗДЕЛ УДАЛЁН! &rarr; $module";
        $m_title = $title_razdel_and_bd[$module];

        if ($row6['active'] == 1) { $p_active_color = ""; $vkl_title = ""; }
        else {
          $p_active_color = " bgcolor=#dddddd";
          $vkl_title = "<a onclick=offpage(".$pid.",1) class=\"punkt\" title=\"Включение страницы\">".icon('white small','`')."Включить</a>";
        }
        $pageslistdel .= "<tr id=1page".$pid."".$p_active_color." class='tr_hover'><td class='".$gray_date."'><nobr>".$date."</nobr></td><td>".$m_title."</td><td>".$vkl_title."</td><td><a title='Удалить страницу в Удаленные' onclick=delpage(".$pid.") class='pointer' style='float:right;'>".icon('red small','T')."</a><a title='Изменить страницу' href='sys.php?op=base_pages_edit_page&name=".$module."&pid=".$pid."'>".icon('orange small','7')."<a title='Открыть страницу на сайте' target=_blank href=-".$module."_page_".$pid.">".$title."</a>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
    }
    $pageslistdel .= "</tbody></table>";

  } elseif ($id == 4) { // ДОБАВЛЕННОЕ ПОСЕТИТЕЛЯМИ
    $pageslistdel .= "<table width=100% class=table_light><thead><tr><th class='gray'>Дата создания</th><th class='gray'>Раздел </th><th class='gray'>Проверка</th><th class='gray'>Страница</th></tr></thead><tbody>";
    $result7 = $db->sql_query("SELECT `pid`, `module`, `title`, `date` from ".$prefix."_pages where (`active`='2' or `active`='3') and `tables`!='del' order by `date` desc limit 0,10000");
    while ($row7 = $db->sql_fetchrow($result7)) {
      $pid = $row7['pid'];
      $title = strip_tags($row7['title'], '<b><strong><em><i>');
      if (trim($title) == "") $title = "< страница без названия >";
      $module = $row7['module'];
      if (!isset($module)) $title_razdel_and_bd[$module] = "РАЗДЕЛ УДАЛЁН! &rarr; $module";
      $date = date2normal_view(str_replace(".","-",$row7['date']), 2, 1);
      $pageslistdel .= "<tr id=1page".$pid." class='tr_hover'><td class='gray'><nobr>".$date."</nobr></td><td class='gray'>".$title_razdel_and_bd[$module]."</td><td><a onclick=offpage(".$pid.",1) class=\"punkt\" title=\"Включение страницы\">".icon('white small','`')."Включить</a></td><td><a title='Удалить страницу в Удаленные' onclick=delpage(".$pid.") class='pointer' style='float:right;'>".icon('red small','T')."</a><a title='Изменить страницу в Редакторе' href='sys.php?op=base_pages_edit_page&name=".$module."&pid=".$pid."'>".icon('orange small','7')."</a><a title='Открыть страницу на сайте' target=_blank href=-".$module."_page_".$pid.">".$title."</a>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
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
      $sql = "SELECT `drevo` from ".$prefix."_pages_comments where `tables`='pages' and `active`!='0' and `num`!='0' and `drevo`!='0' order by `data` desc limit 0,1000000";
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
        $vkl = "<div style='float:right; margin-left:10px;' title='Включить комментарий'>".icon('green small','Q')."</div>";
      } else {
        $bgcolor = " bgcolor='white'";
        $vkl = "<div style='float:right; margin-left:10px;' title='Отключить комментарий'>".icon('red small','Q')."</div>";
      }
      $textline = mb_substr(strip_tags($txt), 0, 45, 'UTF-8');
      if (strlen($textline)<strlen($txt)) $textline .= "...";

      if ($avtor == "Администратор") $avtor2 = "<span class=red>".$avtor."</span>";
      else $avtor2 = $avtor;
      if ($num != 0) {
        if (!isset($module)) $titl_mainpage = "РАЗДЕЛ УДАЛЁН! &rarr; $module";
        else $titl_mainpage = trim($title_razdel_and_bd[$module]);
        //if ($del == true) $del = "<a title='Удалить отключенный комментарий' onclick=delcomm(".$cid.") class=punkt><div style='float:right; margin-left:20px;' title='Отключить комментарий'>".icon('red small','F')."</div></a>"; 
        //else 
        $del = "";
        $pageslistdel .= "<tr onclick=show('comm".$cid."') title='Показать комментарий...' valign=top style='cursor:pointer;' class='tr_hover' id=1comm".$cid.$bgcolor."><td class='gray'><nobr>".$data."</nobr></td><td>".$del."<a onclick=offcomm(".$cid.") class=punkt>".$vkl."</a>

        <a style='float:right;' title='Изменить комментарий' href='sys.php?op=base_comments_edit_comments&cid=".$cid."'>".icon('orange small','7')."</a>

        <i>".$avtor2."</i><span class='gray'> ".$pishet." разделе «".$titl_mainpage."» на странице </span><a title='Открыть на сайте...' target=_blank href='-".$module."_page_".$num."#comm_".$cid."'>".$titles."</a>: <span class='gray'>".$textline."</span></td></tr>
        <tr><td colspan=2".$bgcolor." style='padding:0; margin:0;'>
        <div style='display:none;' id=comm".$cid.">
        ".$otvet.$mails.$tel."<br><br>
        <div class=bggray>".$txt."</div><br>
        <a id='show_otvet_link".$cid."' onclick=\"show_otvet_comm($cid,'".$avtor."','".$mail."','".$module."')\" class='button medium'>".icon('orange small','\'')." Ответить на комментарий</a>
        <div id=otvet_comm".$cid."></div><br><br>
        </div>
        </td></tr>";
      } else {
        if ($mail != "") $pageslistdel .= "<tr valign=top id=1comm".$cid.$bgcolor."><td class='gray'><nobr>".$data."</nobr></td><td><a title='Удалить подписку' onclick=delcomm(".$cid.") class=punkt>".icon('red small','F')."</a><a style='float:right;' title='Изменить подписку' href='sys.php?op=base_comments_edit_comments&cid=".$cid."'>".icon('orange small','7')."</a> <span class=green>Подписка на рассылку</span>, ".$avtor." &rarr; ".$mail."</td></tr>";
        else {
          // Преобразование адреса URL в ссылку (с учетом тире)
          $txt = preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" terget="_blank">$1</a>', $txt);
          $pageslistdel .= "<tr valign=top id=1comm".$cid.$bgcolor."><td class='gray'><nobr>".$data."</nobr></td><td><a style='float:right;' title='Удалить сообщение' onclick=delcomm(".$cid.") class=punkt>".icon('red small','F')."</a> <span class=green>".$avtor."</span> &rarr; ".$txt."</td></tr>";
        }
      }
    }
  } else {
    if ($id == 1) { $deistvo = "del"; $slovo = "Дата удаления"; }
    if ($id == 2) { $deistvo = "backup"; $slovo = "Дата изменения оригинала"; }

    if ($id == 1) {
      $sql3 = "select `id`, `title` from ".$prefix."_mainpage where `type`='2' and `tables`='del'";
      $result3 = $db->sql_query($sql3);
      $pageslistdel .= "<h2>Разделы:</h2><table width=100% class=table_light>";
      while ($row = $db->sql_fetchrow($result3)) {
        $pageslistdel .= "<tr id='block_".$row['id']."'><td><div style='float:right; display: inline;'><a target='_blank' href='sys.php?op=mainpage&type=2&id=".$row['id']."' title='Редактировать'>".icon('black small','7')."</a> 
       <a class='padleft30 punkt' onclick='delblock(".$row['id'].",2)' title='Восстановить'>".icon('green small',';')."</a> 
       <a class='padleft30 punkt' onclick='delblock(".$row['id'].",1)' title='Удалить'>".icon('red small','F')."</a></div>
       <h2>".$row['title']."</h2></td></tr>";
      }
      $pageslistdel .= "</table><br>";
    }
    $sql = "SELECT count(`pid`) from ".$prefix."_pages where `tables`='".$deistvo."'";
    $iid = $deistvo."page";
    $numrows = $db->sql_fetchrow( $db->sql_query($sql) );
    $numrows = $numrows[0];

    $sql6 = "SELECT `pid`, `module`, `title`, `redate` from ".$prefix."_pages where `tables`='".$deistvo."' order by `redate` desc limit 500";
    $iid = $deistvo."page";
    $result6 = $db->sql_query($sql6);
    $pageslistdel .= "<h2>Страницы (".$numrows."):</h2>
        <table width=100% class=table_light><thead><tr><th>".$slovo."</th><th>Раздел </th><th>Страница</th></tr></thead><tbody>";
    while ($row6 = $db->sql_fetchrow($result6)) {
      $pid = $row6['pid'];
      $title = strip_tags($row6['title'], '<b><strong><em><i>');
      if (trim($title) == "") $title = "< страница без названия >";
      $module = $row6['module'];
      if (!isset($title_razdel_and_bd[$module])) $title_razdel_and_bd[$module] = "РАЗДЕЛ УДАЛЁН &rarr; $module";
      $date = date2normal_view(str_replace(".","-",$row6['redate']), 2, 1);
      if ($id == 1) $recreate = "<a title='Восстановить страницу...\nЕсли её раздел или папка удалены, сначала отредактируйте и восстановите из резервных копий!' onclick=resetpage(".$pid.") style=\"cursor:pointer;\">".icon('green small',';')."</a>";
      if ($id == 2) $recreate = "<a title='Заменить этой копией оригинал...\nПодумайте, прежде чем нажимать!' onclick=resetpage(".$pid.") style=\"cursor:pointer;\">".icon('green small',';')."</a>";
      $pageslistdel .= "<tr valign=top id=".$iid.$pid."><td><nobr>".$date."</nobr></td><td>".$title_razdel_and_bd[$module]."</td><td><a title='Удалить страницу (без возможности восстановления)' onclick=deletepage(".$pid.") class='pointer' style='float:right;'>".icon('red small','F')."</a>     
      <a target='_blank' title='Изменить страницу' href='sys.php?op=base_pages_edit_page&name=".$module."&pid=".$pid."'>".icon('orange small','7')."</a><a target=_blank title='Изменить страницу в HTML' href='sys.php?op=base_pages_edit_page&name=".$module."&pid=".$pid."&red=1'>".icon('black small','7')."</a>
     ".$title."&nbsp;&nbsp;".$recreate."</td></tr>";
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
    $nocomm = $row['nocomm'];
    $re2 = $id;
    if ($copy == 0) $copy = $id;
    else $re2 = $copy;
  // создадим такую же
  $db->sql_query("INSERT INTO ".$prefix."_pages VALUES (NULL, '$razdel', '$papka', '$title', '$opentext', '$bodytext', '$data', '$data2', '0', '$active', '0', '0', '$foto', '$search', '$mainpage', '$rss', '$price', '$desc', '$keys', 'pages', '$copy', '$sort', '$nocomm');") or $info = "Создать копию не удалось."; 
  $db->sql_query("UPDATE ".$prefix."_pages SET `copy`='$re2' WHERE pid='$id'") or $info .= "Изменить номер в копируемой странице не удалось.";
  }

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
    $nocomm = $row['nocomm'];
  // создадим такую же
    if (isset($copy)) {
      if ($copy == 0) $copy = $id;
    } else $copy = $id;

  // `pid`,`module`,`cid`,`title`,`open_text`,`main_text`,`date`,`redate`,`counter`,`active`,`golos`,`comm`,`foto`,`search`,`mainpage`,`rss`,`price`,`description`,`keywords`,`tables`,`copy`,`sort`,`nocomm`
    $sql = "INSERT INTO ".$prefix."_pages VALUES (NULL, '".mysql_real_escape_string($razdel)."', '$papka', '".mysql_real_escape_string($title)."', '".mysql_real_escape_string($opentext)."', '".mysql_real_escape_string($bodytext)."', '$data', '$data2', '0', '$active', '0', '0', '$foto', '".mysql_real_escape_string($search)."', '$mainpage', '$rss', '".mysql_real_escape_string($price)."', '".mysql_real_escape_string($desc)."', '".mysql_real_escape_string($keys)."', 'pages', '0', '$sort', '$nocomm');";
  $db->sql_query($sql) or $info = "Скопировать не удалось."; 
  }

  if ($type == 3) { // переместить
  $info = "<b>Страница успешно перемещена.</b>";
  $db->sql_query("UPDATE ".$prefix."_pages SET `module`='$razdel', `cid`='$papka' WHERE pid='$id'") or $info = "Перемещение не удалось.";
  }
  echo "<div class='notice success center mw700'><h2>".$info."</h2></div>"; exit;
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
  
  //$sql = "select cid, title, parent_id from ".$prefix."_pages_categories where module='$name_raz' and `tables`='pages' order by parent_id, title";
  //$result = $db->sql_query($sql);
  $list .= "<div id='izmenapapka".$id."'>
<script>
izmenapapka(document.getElementById('to_razdel".$id."').value, $name_pap, '$name_raz',$id,'izmenapage');
</script>";
/*
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
    if (isset($first_opt[$parentid]) && $parentid != 0) $list .= $first_opt[$parentid];
    $first_opt[$parentid] = "";
    $list .= "<option value=".$cid3." ".$sel.">".$title3."</option>";
  }</select>
  */
  $list .= "</div><input type=button value=\"OK\" style='width:55%; height:35px;' onclick=\"rep($id,document.getElementById('what".$id."').value,document.getElementById('to_razdel".$id."').value,document.getElementById('to_papka".$id."').value); if ($('#what".$id."').val()==3) clo($id);\"><br>Жмём 1 раз, т.к. копирование и ярлыки при каждом нажатии создают новую страницу.
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
  switch ($sort) {
    case "0": $order = "date desc"; break;
    case "2": $order = "redate desc";  break;
    case "3": $order = "comm desc"; break;
    case "4": $order = "counter desc"; break;
    case "5": $order = "active"; break;
    case "1":
    default: $order = "title, date desc"; break;
  }
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
        if ($cid_pages > 0) $pusto = "<span class='small'>содержит ".$cid_pages." ".num_ending($cid_pages, Array(aa("страниц"),aa("страницу"),aa("страницы")))."</span>";
        if ($cid_papki > 0) $pusto = "<span class='small'>содержит ".$cid_papki." ".num_ending($cid_papki, Array(aa("папок"),aa("папку"),aa("папки")))."</span>";
        if ($cid_pages > 0 and $cid_papki > 0) $pusto = "<span class='small'>содержит ".$cid_papki." ".num_ending($cid_papki, Array('папок','папку','папки'))." и ".$cid_pages." ".num_ending($cid_pages, Array(aa("страниц"),aa("страницу"),aa("страницы")))."</span>";
        $list .= "<div id=\"cid".$с_cid."\"><a name=\"open_pages_".$с_cid."\"></a><a class=\"no green punkt\" onclick='papka_show($с_cid, \"$name_raz\", \"$sort\", \"$id\",(Math.floor( Math.random() * (10000 - 10 + 1) ) + 10));'>".icon('orange small',',')." ".$name_cid."</a> ".$pusto." <div id=\"papka".$с_cid."\" style='display:inline; margin-left:5px;'></div><div id=\"podpapka".$с_cid."\" style='display:none;'></div><br></div>";
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
        $copy = $rows['copy'];
        if ($copy == $pid) $copy = " <span class='green'>(оригинал)</span>"; 
          elseif ($copy != '0') $copy = " <span class='red'>(копия)</span>";
          else $copy = "";
        $keydes = "";
        if ($keywords == "") $keydes = "<span class=red title='Нет ключевых слов'>*</span>"; 
        if ($description == "") $keydes = "<span class=red title='Нет описания'>*</span>"; 
        if ($keywords == "" and $description == "") $keydes = "<span class=red title='Нет описания и ключевых слов'>**</span>"; 
        if ($comm != 0) $keydes .= " ".icon('gray small','\'').$comm." "; 
        if ($counter != 0) $keydes .= " ".icon('gray small','s').$counter." "; 
        if ($mainpage == 1) $keydes .= "<span class=green title='Страница отмечена для Главной страницы'>*</span> "; 
        if ($rss == 0) $keydes .= " <span class=rss title='Отключен RSS'>rss</span> "; 
        global $deviceType;
        if ($deviceType != 'computer') {$copy=""; $date=""; $keydes=""; }
        $ver = mt_rand(10000, 99999); // получили случайное число
        $color=""; $nowork = icon('white small','.')." ";
        if ($active == 0) { $color=" class=noact"; $nowork = icon('red small','Q');}
        if ($active == 2 || $active == 3) { $color=" class=deact"; $nowork = icon('red small','!');;}

        $pg = "<div id=\"page".$pid."\" class='gray openpage'><a href=#".$ver.$pid." onclick='sho($pid, \"$name\", \"base_pages\",".$active.");'".$color." class=punkt>".$nowork."".$title."</a>".$copy." ".$date." ".$keydes." <div id='pid".$pid."' class=pid></div></div>";
        if ($no_pages < $granica+1) $list .= $pg; 
        if ($no_pages > $granica) $dop_list .= $pg;
        $no_pages++;
      }
      $siz_page = $siz_page - $granica;
      if ($siz_page > 0) $list .= "<a id='doplistshow".$cid."' onmouseover='show(\"doplistshow".$cid."\"); show(\"doplist".$cid."\");' onclick='show(\"doplistshow".$cid."\"); show(\"doplist".$cid."\");' class='punkt'>".icon('black small','|')." Раскрыть ещё ".$siz_page." ".num_ending($siz_page, Array('страниц','страницу','страницы'))."</u></a><div style='display:none;' id=\"doplist".$cid."\">".$dop_list."</div>";
    } else $list .= icon('black small','.')." <span class=gray>В этой папке нет страниц.</span>";
    $list = "<div style='margin-left:15px; border-left: 1px dotted #999999;'>".$list."</div>";
    echo "<div class=block_white>".$list."</div>"; exit;
}
######################################################################################
if ($func == "razdel") { // Папка
  list($re, $sort) = explode("*@%", $string);
  switch ($sort) {
    case "0": $order = "date desc"; break;
    case "2": $order = "redate desc";  break;
    case "3": $order = "comm desc"; break;
    case "4": $order = "counter desc"; break;
    case "5": $order = "active"; break;
    case "1":
    default: $order = "title, date desc"; break;
  }
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
      if (trim($name_cid) == "") $name_cid = "<span class=red>Папка без Названия. Отредактируйте!</span>";
      $cid_papki = $db->sql_numrows($db->sql_query("select cid from ".$prefix."_pages_categories where `tables`='pages' and module='$name_raz' and parent_id='$с_cid'"));
      if ($cid_pages == 0 and $cid_papki == 0) $pusto = "<span class='small red'>пустая папка</span>";
      if ($cid_pages > 0) $pusto = "<span class='small'>содержит ".$cid_pages." ".num_ending($cid_pages, Array(aa("страниц"),aa("страницу"),aa("страницы")))."</span>";
      if ($cid_papki > 0) $pusto = "<span class='small'>содержит ".$cid_papki." ".num_ending($cid_papki, Array(aa("папок"),aa("папку"),aa("папки")))."</span>";
      if ($cid_pages > 0 and $cid_papki > 0) $pusto = "<span class='small'>содержит ".$cid_papki." ".num_ending($cid_papki, Array('папок','папку','папки'))." и ".$cid_pages." ".num_ending($cid_pages, Array(aa("страниц"),aa("страницу"),aa("страницы")))."</span>";
      $list .= "<div id=\"cid".$с_cid."\"><a name=\"open_pages_".$с_cid."\"></a><a class=\"no green punkt\" onclick='papka_show(".$с_cid.", \"".$name_raz."\", \"".$sort."\", \"".$id."\",(Math.floor( Math.random() * (10000 - 10 + 1) ) + 10));'>".icon('orange small',',')." ".$name_cid."</a> ".$pusto." <div id=\"papka".$с_cid."\" style='display:inline; margin-left:5px;'></div><div id=\"podpapka".$с_cid."\" style='display:none;'></div><br></div>";
    }
  } else {
      $nopapka = 1;
      $list .= icon('yellow small',',')." <span class=gray>В этом разделе нет папок.</span>";
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
      if (trim($title) == "") $title = "<span class=red>Страница без Названия. Отредактируйте!</span>";
      $counter = intval($rows['counter']);
      $comm = intval($rows['comm']);
      $mainpage = intval($rows['mainpage']);
      $rss = intval($rows['rss']);
      $description = trim($rows['description']);
      $keywords = trim($rows['keywords']);
      $copy = $rows['copy'];
      if ($copy == $pid) $copy = " <span class='green'>(оригинал)</span>"; 
        elseif ($copy != '0') $copy = " <span class='red'>(копия)</span>";
        else $copy = "";
      $keydes = "";
      if ($keywords == "") $keydes = "<span class=red title='Нет ключевых слов'>*</span>"; 
      if ($description == "") $keydes = "<span class=red title='Нет описания'>*</span>"; 
      if ($keywords == "" and $description == "") $keydes = "<span class=red title='Нет описания и ключевых слов'>**</span>";
      if ($comm != 0) $keydes .= " ".icon('gray small','\'').$comm." "; 
      if ($counter != 0) $keydes .= " ".icon('gray small','s').$counter." "; 
      if ($mainpage == 1) $keydes .= "<span class=green title='Страница отмечена для Главной страницы'>*</span> "; 
      if ($rss == 0) $keydes .= " <span class=rss title='Отключен RSS'>rss</span> ";  
      $ver = mt_rand(10000, 99999); // получили случайное число
      $color=""; $nowork = icon('white small','.')." ";

      global $deviceType;
        if ($deviceType != 'computer') {$copy=""; $date=""; $keydes=""; }

      if ($active == 0) { $color=" class=noact"; $nowork=icon('red small','Q');}
      if ($active == 2 || $active == 3) { $color=" class=deact"; $nowork=icon('red small','!');}
      $pg = "<div id=\"page".$pid."\" class='gray openpage'><a href=#".$ver.$pid." onclick='sho($pid, \"$name\", \"base_pages\",".$active.");'".$color." class=punkt>".$nowork."".$title."</a>".$copy." ".$date." ".$keydes." <div id='pid".$pid."' class=pid></div></div>";
      if ($no_pages < $granica+1) $list .= $pg; 
      if ($no_pages > $granica) $dop_list .= $pg;
      $no_pages++;
    }
  $siz_page = $siz_page - $granica;
  if ($siz_page > 0) $list .= "<a id='doplistshow".$name_raz."' onmouseover='show(\"doplistshow".$name_raz."\"); show(\"doplist".$name_raz."\");' onclick='show(\"doplistshow".$name_raz."\"); show(\"doplist".$name_raz."\");' class='punkt'>".icon('black small','|')." Раскрыть ещё ".$siz_page." ".num_ending($siz_page, Array('страниц','страницу','страницы'))."</a><div style='display:none;' id=\"doplist".$name_raz."\">".$dop_list."</div>";
  } else {
      $nopage = 1;
      $list .= icon('black small','.')." <span class=gray>В корневой папке этого раздела нет страниц.</span>";
  }
  $list .= "<br><br></div>";
  if ($nopage == 1 and $nopapka == 1) {
    $list2 = "";
  } else {
      $list2 = "<div style=\"margin-left:20px; float:right;\">
      <button class='small' onclick=show('sortir_page')><img src='/images/sortirovka.png'></button>
      <div id=sortir_page style='display:none;'><br>Сортировать страницы:<br>";
      // Сортировка страниц
      $pages_list = array("по дате создания","по алфавиту","по дате изменения","по кол-ву комментариев","по кол-ву просмотров","сначала — отключенные");
      for ( $i=0; $i < count($pages_list); $i++ ) {
          if ($sort==$i) {
              $list2 .= "&rarr;<strong>".$pages_list[$i]."</strong><br>";
          } else { 
              $list2 .= "<a onclick=\"razdel_show('','".$id."', '".$name_raz."', 'pages', '', '".$i."');\" class='punkt'>".$pages_list[$i]."</a><br>";
          }
      }
      $list2 .= "</div></div>"; 
  }
  echo $list2.$list; exit;
}
}
function icon($classes,$data) {
  return '<span class="icon '.$classes.'" data-icon="'.$data.'" style="display: inline-block; "><span aria-hidden="true">'.$data.'</span></span>';
}
?>