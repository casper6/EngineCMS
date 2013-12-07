<?php
  // Поиск по всем разделам, папкам и страницам
  global $lang, $soderganie, $tip, $DBName, $prefix, $db, $module_name, $ModuleName, $slovo, $design, $now, $ip, $papka, $title_razdels, $showall, $admin, $search_in_pages, $search_in_papka, $search_in_razdel, $search_col_razdel, $search_col_papka, $search_col_page, $search_col_showall;

  $admintip = "base_pages";
  $showall = intval($showall);
  if ($showall != 0) $search_col_page = $search_col_showall; // показать все

  $slov = filter($slovo);
  $soderganiemain = "<h1>".ss("Вы искали:")." ".$slov."</h1>";
// 43-excel56 39-x
  $slov = str_replace("-0"," 0",str_replace("-1"," 1",str_replace("-2"," 2",str_replace("-3"," 3",str_replace("-4"," 4",str_replace("-5"," 5",str_replace("-6"," 6",str_replace("-7"," 7",str_replace("-8"," 8",str_replace("-9"," 9", str_replace("—","-",str_replace("."," ",str_replace(","," ",str_replace(":"," ",str_replace(";"," ",str_replace("!"," ",str_replace("?"," ",str_replace("\""," ",$slov)))))))) )))))))))); // меняя знаки пепинания на пробелы, чтобы потом слова не слились
  $slov = preg_replace("/[^(\w)|(\x7F-\xFF)|(\s)]/","",$slov); // чистим поисковое слово // (\-)

  $last_number = false;
  $slov2 = "";
  for ($i=0; $i < strlen($slov); $i++) {
    if (is_numeric($slov[$i])) {
      if ($last_number == false) $slov2 .= " ".$slov[$i];  
      else $slov2 .= $slov[$i];
      $last_number = true; 
    } else { 
      if ($last_number == true) $slov2 .= " ".$slov[$i]; 
      else $slov2 .= $slov[$i];
      $last_number = false; 
    }
  }
  $slov = trim(mb_strtolower(preg_replace("/  +/"," ", $slov2))); // убираем лишние пробелы и приводим к нижнему регистру

  $soderganiemain .= "<div class='main_search_line'><form method='POST' action='search' class='main_search_form'><input type='search' placeholder='".ss("Поиск по сайту")."' style='width:98%' name='slovo' class='main_search_input' value='".$slov."' autofocus><input type='submit' name='ok' value='".ss("Найти")."' class='main_search_button'></form></div>";

if ($slov == "") $soderganie .= "<p>".ss("Вы задали пустой поиск. Напишите что-нибудь в строке поиска.");
else {

  $papka = intval($papka);
  if ($papka == 0) $papka = "";
  else $papka = " and `cid` = '".$papka."'";

  if (is_admin($admin)) $soderganie .= "<h3>".ss("Редактирование страниц доступно только Администратору.")."</h3>";

  //$search_line[] = str_replace(" ", "%", $slov); 
  if ($lang == 'ru') {
    $slova = zamena_predlog($slov); // убираем предлоги
    $stemmer = new Lingua_Stem_Ru();
    $ss = explode(" ", $slova);
    $co = count($ss);
    $slova = array();
    for ($i=0; $i < $co; $i++)
      if (mb_strlen($ss[$i]) > 1) $slova[] = $stemmer->stem_word($ss[$i]);
    $slova = implode(" ",$slova);
    //if ($slova != $slov) $search_line[] = $slova; // Значит есть предлоги, будем искать по ним и без них
  } else $slova = $slov;

  // разбиваем строку на слова
  $slovo = explode(" ",$slova);
  $s = implode("%", $slovo);

  if (count($slovo)>=4) {
    $search_line[] = $slovo[0]."%".$slovo[1]."%".$slovo[3]."%".$slovo[2];
    $search_line[] = $slovo[0]."%".$slovo[2]."%".$slovo[1]."%".$slovo[3];
    $search_line[] = $slovo[0]."%".$slovo[2]."%".$slovo[3]."%".$slovo[1];
    $search_line[] = $slovo[0]."%".$slovo[3]."%".$slovo[1]."%".$slovo[2];
    $search_line[] = $slovo[0]."%".$slovo[3]."%".$slovo[2]."%".$slovo[1];
    $search_line[] = $slovo[1]."%".$slovo[0]."%".$slovo[2]."%".$slovo[3];
    $search_line[] = $slovo[1]."%".$slovo[0]."%".$slovo[3]."%".$slovo[2];
    $search_line[] = $slovo[1]."%".$slovo[2]."%".$slovo[0]."%".$slovo[3];
    $search_line[] = $slovo[1]."%".$slovo[2]."%".$slovo[3]."%".$slovo[0];
    $search_line[] = $slovo[1]."%".$slovo[3]."%".$slovo[0]."%".$slovo[2];
    $search_line[] = $slovo[1]."%".$slovo[3]."%".$slovo[2]."%".$slovo[0];
    $search_line[] = $slovo[2]."%".$slovo[1]."%".$slovo[0]."%".$slovo[3];
    $search_line[] = $slovo[2]."%".$slovo[1]."%".$slovo[3]."%".$slovo[0];
    $search_line[] = $slovo[2]."%".$slovo[0]."%".$slovo[1]."%".$slovo[3];
    $search_line[] = $slovo[2]."%".$slovo[0]."%".$slovo[3]."%".$slovo[1];
    $search_line[] = $slovo[2]."%".$slovo[3]."%".$slovo[1]."%".$slovo[0];
    $search_line[] = $slovo[2]."%".$slovo[3]."%".$slovo[0]."%".$slovo[1];
    $search_line[] = $slovo[3]."%".$slovo[1]."%".$slovo[2]."%".$slovo[0];
    $search_line[] = $slovo[3]."%".$slovo[1]."%".$slovo[0]."%".$slovo[2];
    $search_line[] = $slovo[3]."%".$slovo[2]."%".$slovo[1]."%".$slovo[0];
    $search_line[] = $slovo[3]."%".$slovo[2]."%".$slovo[0]."%".$slovo[1];
    $search_line[] = $slovo[3]."%".$slovo[0]."%".$slovo[1]."%".$slovo[2];
    $search_line[] = $slovo[3]."%".$slovo[0]."%".$slovo[2]."%".$slovo[1];
  }
  if (count($slovo)==3) {
    $search_line[] = $slovo[0]."%".$slovo[2]."%".$slovo[1];
    $search_line[] = $slovo[1]."%".$slovo[2]."%".$slovo[0];
    $search_line[] = $slovo[1]."%".$slovo[0]."%".$slovo[2];
    $search_line[] = $slovo[2]."%".$slovo[0]."%".$slovo[1];
    $search_line[] = $slovo[2]."%".$slovo[1]."%".$slovo[0];
  }
  if (count($slovo)==2) {
    $search_line[] = $slovo[1]."%".$slovo[0];
  }

  $add_pages = $add_razdel = $add_papka = array();

  $search_line = array_unique($search_line);

  foreach ($search_line as $s_line) {
    $s_line = mysql_real_escape_string($s_line);
    if ($s_line != $s) {
      $add_pages[] = " or (LOWER(CONCAT(`title`,' ',`open_text`,' ',`main_text`)) LIKE '%".$s_line."%')";
      $add_papka[] = " or (LOWER(CONCAT(`title`,' ',`description`)) LIKE '%".$s_line."%')";
      $add_razdel[] = " or (LOWER(CONCAT(`title`,' ',`useit`)) LIKE '%".$s_line."%')";
    }
  }
  $add_pages = implode("",$add_pages);
  $add_papka = implode("",$add_papka);
  $add_razdel = implode("",$add_razdel);

  $search_line = array_merge($search_line,$slovo);

  $soderganie .= "<div class=main_search><ol>";
  $c_name = titles_papka(0,1); // Список всех папок
  $allnum = 0; // сколько всего найдено

  $s = mysql_real_escape_string($s);

  if ( $papka == "" && $showall == 0 && $search_in_razdel == 1) { // если не выбрана определенная папка и показываются не все страницы - ищем и по разделам
    ////////////////////////////////////////////////////////////////////////////////////////
    $sql = "SELECT `id`,`name`,`title`,`useit` FROM ".$prefix."_mainpage where `tables`='pages' and type='2' and ( (LOWER(CONCAT(`title`,' ',`useit`) ) LIKE '%".$s."%')".$add_razdel." ) limit ".$search_col_razdel;
    $res2 = $db->sql_query($sql);
    //if (is_admin($admin)) echo $sql." <b>".$db->sql_numrows($res2)."</b>";
    $allpids = $pids = $pids1 = $pids2 = $rr_title = $rr_useit = $rr_name = array(); //  = $rr_name
    while ($row = $db->sql_fetchrow($res2)) {
      $id = $row['id'];
      $name2 = $row['name'];
      if (strpos($name2, "\n")) { // заменяем имя запароленного раздела
        $name2 = explode("\n", str_replace("\r", "", $name2));
        $name2 = trim($name2[0]);
      }
      $rr_name[$id] = $name2;
      $rr_title[$id] = $row['title'];
      $rr_useit[$id] = $row['useit'];
    }
    foreach ($rr_title as $id => $title) {
      foreach ($search_line as $s_line) {
        # поиск в заголовке разделов
        if(preg_match('/['.str_replace("%", "|", $s_line).']/i',$title)) 
          if (!in_array($id,$allpids)) { $pids1[] = $id; $allpids[] = $id; } // если нет в списке, заносим в список страниц
        # поиск в содержании разделов
        if(preg_match('/['.str_replace("%", "|", $s_line).']/i',$rr_useit[$id])) 
          if (!in_array($id,$allpids)) { $pids2[] = $id; $allpids[] = $id; }
      }
    }
    $pids = array_merge($pids1,$pids2);
    $nu = count($pids);
    if ($nu != 0) { $soderganie .= "<h2>".ss("В разделах:")." ".$nu."</h2>"; $allnum += $nu; }

    foreach ($pids as $p_pid) {
      $p_title = $rr_title[$p_pid];
      $soderganie .= "<li>".ss("раздел")." <a class='search_page_link' href='/-".$rr_name[$p_pid]."'>".$p_title."</a>";
      if (is_admin($admin)) $soderganie .= "&nbsp; <a href='sys.php?op=mainpage&type=2&id=".$p_pid."' title='".ss("Редактировать раздел")."'>".ss("Редактировать раздел")."</a>";
      foreach ($slovo as $s_line) {
        $txt = strchop(strip_tags(str_replace("&nbsp;"," ",str_replace("".aa("[содержание]").""," ",str_replace("".aa("[следующий]").""," ",str_replace("".aa("[название]").""," ",str_replace("".aa("[страницы]").""," ",str_replace("<br>"," ",str_replace("<p>"," ",$rr_useit[$p_pid])))))))),$s_line,100);
        if ($txt != "......" and $txt != false) $soderganie .= "<blockquote>".$txt."</blockquote>";
      }
    }
  }
    ////////////////////////////////////////////////////////////////////////////////////////
  if ( $papka == "" && $showall == 0 && $search_in_papka == 1) {
    $sql = "SELECT `cid`,`module`,`title`,`description` FROM ".$prefix."_pages_categories where `tables`='pages' and ( (LOWER(CONCAT(`title`,' ',`description`)) LIKE '%".$s."%')".$add_papka." ) limit ".$search_col_papka;
    $res2 = $db->sql_query($sql);
    //if (is_admin($admin)) echo "<br>".$sql." <b>".$db->sql_numrows($res2)."</b>";
    $allpids = $pids = $pids1 = $pids2 = $rr_title = $rr_description = $rr_module = array(); //  = $rr_name
    while ($row = $db->sql_fetchrow($res2)) {
      $id = $row['cid'];
      $rr_title[$id] = $row['title'];
      $rr_module[$id] = $row['module'];
      $rr_description[$id] = $row['description'];
    }
    foreach ($rr_title as $id => $title) {
      foreach ($search_line as $s_line) {
        # поиск в заголовке папок
        if(preg_match('/['.str_replace("%", "|", $s_line).']/i',$title)) 
          if (!in_array($id,$allpids)) { $pids1[] = $id; $allpids[] = $id; }
        # поиск в содержании папок
        if(preg_match('/['.str_replace("%", "|", $s_line).']/i',$rr_description[$id])) 
          if (!in_array($id,$allpids)) { $pids2[] = $id; $allpids[] = $id; }
      }
    }
    $pids = array_merge($pids1,$pids2);
    $nu = count($pids);
    if ($nu != 0) { $soderganie .= "<h2>".ss("В подразделах:")." ".$nu."</h2>"; $allnum += $nu; }

    foreach ($pids as $p_cid) {
      $soderganie .= "<li>".ss("папка")." <a class='search_page_link' href='/-".$rr_module[$p_cid]."_cat_".$p_cid."'>".$rr_title[$p_cid]."</a>";
      if (is_admin($admin)) $soderganie .= "&nbsp; <a href='sys.php?op=edit_base_pages_category&cid=".$p_cid."' title='".ss("Редактировать папку")."'>".ss("Редактировать папку")."</a>";
      foreach ($slovo as $s_line) {
        $txt = strchop(strip_tags(str_replace("&nbsp;"," ",str_replace("<br>"," ",str_replace("<p>"," ",$rr_description[$p_cid])))),$s_line,100);
        if ($txt != "......" and $txt != false) $soderganie .= "<blockquote>".$txt."</blockquote>";
      }
    }
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  if ( $search_in_pages == 1 ) {
    if ($showall == 0) $show_text = ",`open_text`,`main_text`"; else $show_text = "";
    $sql = "SELECT `pid`,`module`,`cid`,`title`".$show_text.",`date` FROM ".$prefix."_pages where `tables`='pages'".$papka." and `active`='1' and (`copy`='0' or `copy`=`pid`) and ( (LOWER(CONCAT(`title`,' ',`open_text`,' ',`main_text`)) LIKE '%".$s."%')".$add_pages." ) order by `date` desc limit ".$search_col_page;
    $res2 = $db->sql_query($sql);
    //if (is_admin($admin)) echo "<br>".$sql." <b>".$db->sql_numrows($res2)."</b>";
    
    $allpids = $pids = $pids1 = $pids2 = $p_pid = $pp_title = $pp_module = $pp_cid = $pp_open_text = $p_date = array();
    while ($row = $db->sql_fetchrow($res2)) {
      $id = $row['pid'];
      if ($showall != 0) $p_pid[$id] = $row['pid'];
      $pp_title[$id] = stripslashes($row['title']);
      //echo $row['title'];
      $pp_module[$id] = $row['module'];
      $pp_cid[$id] = $row['cid'];
      $p_date[$id] = date2normal_view($row['date'], 2); 
      if ($showall == 0) $pp_open_text[$id] = stripslashes($row['open_text'].$row['main_text']);
    }
    if ($showall == 0) {
      foreach ($pp_title as $id => $title) {
        foreach ($search_line as $s_line) {
          # поиск в заголовке страниц
          if ( mb_stripos(" ".$title, $s_line)) 
            if (!in_array($id,$allpids)) { // если нет в списке, заносим в список страниц
              $pids1[] = $id; 
              $allpids[] = $id; 
            } 
          # поиск в предисловии страниц
          if ( mb_stripos(" ".$pp_open_text[$id], $s_line)) //  || mb_stripos(" ".str_replace(" ","%",$pp_open_text[$id]), $s_line) 
            if (!in_array($id,$allpids)) { // если нет в списке, заносим в список страниц
              $pids2[] = $id; 
              $allpids[] = $id; 
            }
        }
      } 
      $pids = array_merge($pids1,$pids2);
      $nu = count($pids);
    } else { 
      $nu = count($pp_title); 
      $pids = $p_pid; 
    }

    if ($nu == $search_col_page && $showall == 0) { 
      $bolee = " ".ss("более")." "; $vse = ". <a class='search_showall' href='header.php?name=-search&slovo=".$slov."&showall=1'>".ss("Показать все")."</a>"; 
    } else $vse = $bolee = " "; 
    if ($nu != 0) { $soderganie .= "<h2>".ss("В страницах:").$bolee.$nu.$vse."</h2>"; $allnum += $nu; }

    foreach ($pids as $p_pid) {
      $p_title = $pp_title[$p_pid];
      $p_module = $pp_module[$p_pid];
      $p_cid = $pp_cid[$p_pid];
      $cat = "";
      if ($showall == 0) 
        if ($p_cid != 0) 
          $cat = " ".$strelka." <a class='search_folder_link' href='/-".$p_module."_cat_".$p_cid."'>".$c_name[$p_cid]."</a>"; 
      
      $soderganie .= "<li>".ss("стр.")." <a class='search_page_link' href='/-".$p_module."_page_".$p_pid."'>".$p_title."</a> <span class='search_page_date'>".$p_date[$p_pid]."</span>";
      
      if (is_admin($admin)) 
        $soderganie .= "&nbsp; <a href='sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid."' title='".aa("Редактировать страницу")."'>".aa("Редактировать страницу")."</a>";
      
      if ($showall == 0) { // если показать ограниченное кол-во
        $soderganie .= "<br><a class='search_razdel_link' href='/-".$p_module."'>".$title_razdels[$p_module]."</a> ".$cat;
        foreach ($slovo as $s_line) {
          $txt = strchop(strip_tags(str_replace("&nbsp;"," ",str_replace("<br>"," ",str_replace("<p>"," ",$pp_open_text[$p_pid])))),$s_line,100);
          if ($txt != "......") $soderganie .= "<blockquote>".$txt."</blockquote>";
        }
      }
    }
  }
  $soderganie .= "</ol></div>";

  if ($allnum == 0) $soderganie = $soderganiemain."<h3>".ss("Данное словосочетание не обнаружено. Попробуйте поискать по другим словам.")."</h3>";
  else $soderganie = $soderganiemain.$soderganie;
}
  // Получаем дизайн для поиска
  global $search_design;
  // Определение дизайна и использованных стилей в дизайне
  list($design_for_search, $stil) = design_and_style($search_design);
  if ($design_for_search == "0") die(ss("Ошибка: «Адрес раздела»")." (".$name.") ".ss("введен неправильно. Перейдите на <a href=/>Главную страницу</a>."));
  $block = str_replace(aa("[содержание]"),$soderganie,$design_for_search);

  // Занесение слова в БД
  $slov = mysql_real_escape_string($slov);
  $ip = mysql_real_escape_string($ip);
  $now = mysql_real_escape_string($now);
  if ($db->sql_numrows($db->sql_query("SELECT `id` FROM ".$prefix."_search where `slovo`='".$slov."' and ip='".$ip."'")) == 0 and trim($slov) != '' and !is_admin($admin)) $db->sql_query("INSERT INTO `".$prefix."_search` (`id`,`ip`,`slovo`,`data`,`pages`) VALUES (NULL, '".$ip."', '".$slov."', '".$now."', '".$allnum."');");

  return array($block, $stil);

////////////////////////////////////////////////////////
function strchop($data,$word,$interval,$ci=true) {
 /*
 Выделение строк с поисковыми словами
 $data строка в которой ищем
 $word что ищем
 $interval интервал символов до и символов после
 $ci нечувствителен к регистру по умолчанию true
 @return string|false результат, если false - нет вхождения
 */
  $data = preg_replace('#\[(.*?)\]#ui', '', $data);
  // вырезаем Script, Style и блоки

    //if($ci){$position=stripos($data,$word);}else{$position = strpos($data,$word);};
    $position = $ci?mb_stripos($data,$word):mb_strpos($data,$word);
    //ничего нет - вернули false
    if(!$position) return false;
    //Определяем стартовую позицию новой строки
    $start_position = $position - $interval;
    //От конца слова определили конечный интервал
    $end_position = $position + mb_strlen($word) + $interval;
    //Если стартовая позиция отрицательная делаем в 0
    if($start_position < 0) $start_position = 0;
    //определяем длину новой строки 
    $len = $end_position - $start_position;
    $length = (mb_strlen($data) > $len) ? mb_strripos(mb_substr($data, 0, $len), ' ') : $len;
    //вернули результат #([А-я]*струя[а-я]*)#i
    return preg_replace('#([А-я]*'.mb_convert_case($word, MB_CASE_UPPER).'[а-я]*)#uis', '<b class=red>$1</b>', preg_replace('#([А-я]*'.mb_convert_case($word, MB_CASE_TITLE).'[а-я]*)#uis', '<b class=red>$1</b>', preg_replace('#([А-я]*'.mb_convert_case($word, MB_CASE_LOWER).'[а-я]*)#uis', '<b class=red>$1</b>', '...'.mb_substr($data,$start_position,$length).'...')));
}
///////////////////////////////////////////////////////////////
function zamena_predlog($text) { # Замена предлогов
  $zamena = array(" а "=>" "," в "=>" "," и "=>" "," к "=>" "," о "=>" "," с "=>" "," у "=>" "," я "=>" "," во "=>" "," до "=>" "," за "=>" "," из "=>" "," на "=>" "," не "=>" "," ни "=>" "," но "=>" "," по "=>" "," об "=>" "," то "=>" "," для "=>" "," или "=>" "," над "=>" "," обо "=>" "," про "=>" ","Про "=>""," же "=>" ", " около "=>" "," перед "=>" "," после "=>" "," против "=>" "," напротив "=>" ");
  $text = trim(strtr(" ".$text." ",$zamena));
  return $text;
}

//setlocale(LC_ALL, 'ru_RU.UTF-8', 'rus');
class Lingua_Stem_Ru {
    var $Stem_Caching = 0;
    var $Stem_Cache = array();
    var $VOWEL = '/аеиоуыэюя/u';
    var $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/u';
    var $REFLEXIVE = '/(с[яь])$/u';
    var $ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|ему|ому|их|ых|ую|юю|ая|яя|ою|ею)$/u';
    var $PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/u';
    var $VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ен|ило|ыло|ено|ят|ует|уют|ит|ыт|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/u';
    var $NOUN = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я)$/u';
    var $RVRE = '/^(.*?[аеиоуыэюя])(.*)$/u';
    var $DERIVATIONAL = '/[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$/u';
    function s(&$s, $re, $to) {
        $orig = $s;
        $s = preg_replace($re, $to, $s);
        return $orig !== $s;
    }
    function m($s, $re) {
        return preg_match($re, $s);
    }
    function stem_word($word) {
       $word = mb_strtolower($word, 'UTF-8');
       /*$word = strtr($word, 'ё', 'е');*/
       $word = preg_replace('/ё/u', 'е', $word);
        if ($this->Stem_Caching && isset($this->Stem_Cache[$word])) {
            return $this->Stem_Cache[$word];
        }
        $stem = $word;
        do {
          if (!preg_match($this->RVRE, $word, $p)) break;
          $start = $p[1];
          $RV = $p[2];
          if (!$RV) break;
          if (!$this->s($RV, $this->PERFECTIVEGROUND, '')) {
              $this->s($RV, $this->REFLEXIVE, '');
              if ($this->s($RV, $this->ADJECTIVE, '')) {
                  $this->s($RV, $this->PARTICIPLE, '');
              } else {
                  if (!$this->s($RV, $this->VERB, ''))
                      $this->s($RV, $this->NOUN, '');
              }
          }
          $this->s($RV, '/и$/u', '');
          if ($this->m($RV, $this->DERIVATIONAL))
              $this->s($RV, '/ость?$/u', '');
          if (!$this->s($RV, '/ь$/u', '')) {
              $this->s($RV, '/ейше?/u', '');
              $this->s($RV, '/нн$/u', 'н');
          }
          $stem = $start.$RV;
        } while(false);
        if ($this->Stem_Caching) $this->Stem_Cache[$word] = $stem;
        return $stem;
    }
    function stem_caching($parm_ref) {
        $caching_level = @$parm_ref['-level'];
        if ($caching_level) {
            if (!$this->m($caching_level, '/^[012]$/u')) {
                die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
            }
            $this->Stem_Caching = $caching_level;
        }
        return $this->Stem_Caching;
    }
    function clear_stem_cache() {
        $this->Stem_Cache = array();
    }
}
?>