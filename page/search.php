<?php
  // Настройка поиска (будет вынесена в админку)
  $col_razdel = 5; // ограничение кол-ва найденных результатов
  $col_papka = 5;
  $col_page = 100;

  // Поиск по всем разделам, папкам и страницам
  // Выставлен лимит на кол-во: 100 папок и 100 разделов, 500 страниц.
  global $soderganie, $tip, $DBName, $prefix, $db, $module_name, $ModuleName, $slovo, $design, $now, $ip, $papka, $title_razdels;
  $admintip = "base_pages";

  $slov = filter($slovo);
  $soderganiemain = "<h1>Вы искали: ".$slov."</h1>";

  $slov = str_replace("—","-",str_replace("."," ",str_replace(","," ",str_replace(":"," ",str_replace(";"," ",str_replace("!"," ",str_replace("?"," ",str_replace("\""," ",$slov)))))))); // меняя знаки пепинания на пробелы, чтобы потом слова не слились
  $slov = preg_replace("/[^(\w)|(\x7F-\xFF)|(\s)(\-)]/","",$slov); // чистим поисковое слово
  $slov = trim(mb_strtolower(preg_replace("/  +/"," ",$slov))); // убираем лишние пробелы и приводим к нижнему регистру

  $soderganiemain .= "<div class='main_search_line'><form method=POST action='--search' class=main_search_form><input type='search' placeholder='Поиск по сайту' style='width:98%' name=slovo class='main_search_input' value='".$slov."' autofocus><input type='submit' name='ok' value='Найти' class='main_search_button'></form></div>";

if ($slov == "") $soderganie .= "<p>Вы задали пустой поиск. Напишите что-нибудь в строке поиска выше.";
else {

  if (strpos($slov,"@")) $soderganie .= "<p>E-mail адреса не стоит искать на этом сайте, лучше использовать их для написания писем в почтовых программах или на почтовых сайтах, на которых вы зарегистрированы. Если вам нужны Контакты — посмотрите в меню сайта.";
  if (strpos(" ".$slov,"www.") or strpos($slov,"http://")) $soderganie .= "<p>Адреса сайтов нужно писать не в поиске по сайту, а в адресной строке вашего браузера (той программы, через которую вы смотрите этот сайт), в самом верху окна.";

  $papka = intval($papka);
  if ($papka == 0) $papka = "";
  else $papka = " and cid = '".$papka."'";

  
  if (is_admin($admin)) $soderganie .= "<h3>Справка: Редактирование доступно только Администратору.</h3>";
  // Заголовок
  $slova = zamena_predlog($slov); // убираем предлоги
  $slovo = preg_split("/\s+/s",$slova);

  //echo $slova;
  $stemmer = new Lingua_Stem_Ru();
  $ss = explode(" ", $slova);
  $co = count($ss);
  $slova = array();
  for ($i=0; $i < $co; $i++) {
    $slova[] = $stemmer->stem_word($ss[$i]);
  }
  $slova = implode(" ",$slova);
  //echo " - ".$slova;
  

  $s = implode("%", $slovo);

  $count_slovo = count($slovo);
  $always = array(); // обязательные для поиска слова
  $notbad = array(); // необязательные для поиска слова
  for ( $i=0; $i < $count_slovo; $i++ ) {
    $slovo[$i] = obrez($slovo[$i]);
    if (preg_match("|^[\d]+$|", $slovo[$i])) $always[] = $slovo[$i]; // число - заносим в список обязательных
    else $notbad[] = $slovo[$i];
  }

  // Формируем список словосочетаний, по которым будем искать
  if ($slova != $slov) { $search_line[] = $slov; $search_line[] = $slova; } // Значит есть предлоги, будем искать по ним и без них
  else $search_line[] = $slov;

  if (count($always)>3) {
    $search_line[] = $always[0]."%".$always[1]."%".$always[2];
    $search_line[] = $always[1]."%".$always[2]."%".$always[3];
    $search_line[] = $always[3]."%".$always[0]."%".$always[1];
    $search_line[] = $always[0]."%".$always[1];
    $search_line[] = $always[1]."%".$always[0];
    $search_line[] = $always[1]."%".$always[2];
    $search_line[] = $always[0]."%".$always[2];
    $search_line[] = $always[2]."%".$always[0];
    $search_line[] = $always[2]."%".$always[1];
    $search_line[] = $always[3]."%".$always[0];
    $search_line[] = $always[0]."%".$always[3];
    $search_line[] = $always[1]."%".$always[3];
    $search_line[] = $always[2]."%".$always[3];
    $search_line[] = $always[3]."%".$always[1];
  }
  if (count($always)==3) {
    $search_line[] = $always[0]."%".$always[1];
    $search_line[] = $always[1]."%".$always[0];
    $search_line[] = $always[1]."%".$always[2];
    $search_line[] = $always[0]."%".$always[2];
    $search_line[] = $always[2]."%".$always[0];
    $search_line[] = $always[2]."%".$always[1];
  }
  if (count($always)==2 and count($notbad)==0) {
    $search_line[] = $always[0]."%".$always[1];
    $search_line[] = $always[1]."%".$always[0];
  }
  if (count($always)==2 and count($notbad)==1) {
    $search_line[] = $always[0]."%".$always[1];
    $search_line[] = $always[1]."%".$always[0];
    $search_line[] = $always[0]."%".$notbad[0];
    $search_line[] = $notbad[0]."%".$always[0];
    $search_line[] = $always[1]."%".$notbad[0];
    $search_line[] = $notbad[0]."%".$always[1];
  }
  if (count($always)==2 and count($notbad)==2) {
    $search_line[] = $always[0]."%".$always[1];
    $search_line[] = $always[1]."%".$always[0];
    $search_line[] = $always[0]."%".$notbad[0];
    $search_line[] = $notbad[0]."%".$always[0];
    $search_line[] = $always[1]."%".$notbad[0];
    $search_line[] = $notbad[0]."%".$always[1];
    $search_line[] = $always[0]."%".$notbad[1];
    $search_line[] = $notbad[1]."%".$always[0];
    $search_line[] = $always[1]."%".$notbad[1];
    $search_line[] = $notbad[1]."%".$always[1];
  }
  if (count($always)==1 and count($notbad)==0) {
    $search_line[] = $always[0];
  }
  if (count($always)==1 and count($notbad)==1) {
    $search_line[] = $always[0]."%".$notbad[0];
    $search_line[] = $notbad[0]."%".$always[0];
  }
  if (count($always)==1 and count($notbad)>1) {
    $search_line[] = $always[0]."%".$notbad[0];
    $search_line[] = $notbad[0]."%".$always[0];
    $search_line[] = $always[0]."%".$notbad[1];
    $search_line[] = $notbad[1]."%".$always[0];
    $search_line[] = $notbad[0]."%".$notbad[1];
    $search_line[] = $notbad[1]."%".$notbad[0];
  }
  if (count($always)==0 and count($notbad)==1) {
    $search_line[] = $notbad[0];
  }
  if (count($always)==0 and count($notbad)==2) {
    $search_line[] = $notbad[1]."%".$notbad[0];
    $search_line[] = $notbad[0]."%".$notbad[1];
  }
  if (count($always)==0 and count($notbad)>2) {
    $search_line[] = $notbad[1]."%".$notbad[0];
    $search_line[] = $notbad[0]."%".$notbad[1];
    $search_line[] = $notbad[1]."%".$notbad[2];
    $search_line[] = $notbad[2]."%".$notbad[1];
    $search_line[] = $notbad[2]."%".$notbad[0];
    $search_line[] = $notbad[0]."%".$notbad[2];
  }

  $add_pages = $add_razdel = $add_papka = array();

  foreach ($search_line as $s_line) {
    $add_pages[] = " or (LOWER(`title`) LIKE '%".$s_line."%' or LOWER(`main_text`) LIKE '%".$s_line."%' or LOWER(`open_text`) LIKE '%".$s_line."%')";
  }
  $add_pages = implode("",$add_pages);

  foreach ($search_line as $s_line) {
    $add_papka[] = " or (LOWER(`title`) LIKE '%".$s_line."%' or LOWER(`description`) LIKE '%".$s_line."%')";
  }
  $add_papka = implode("",$add_papka);

  foreach ($search_line as $s_line) {
    $add_razdel[] = " or (LOWER(`title`) LIKE '%".$s_line."%' or LOWER(`useit`) LIKE '%".$s_line."%')";
  }
  $add_razdel = implode("",$add_razdel);

  $search_line = array_merge($search_line,$slovo);

  $soderganie .= "<div class=main_search><ol>";
  $c_name = titles_papka(0,1); // Список всех папок
  $allnum = 0; // сколько всего найдено

  if ($papka == "") { // если не выбрана определенная папка - ищем и по разделам
  ////////////////////////////////////////////////////////////////////////////////////////
      $res2 = $db->sql_query("SELECT `id`,`name`,`title`,`useit` FROM ".$prefix."_mainpage where `tables`='pages' and type='2' and ( (LOWER(`title`) LIKE '%".$s."%' or LOWER(`useit`) LIKE '%".$s."%')".$add_razdel." ) limit ".$col_razdel);
      $allpids = $pids1 = $pids2 = $pids3 = $rr_title = $rr_useit = $rr_name = array(); //  = $rr_name
      while ($row = $db->sql_fetchrow($res2)) {
        $id = $row['id'];
        $rr_name[$id] = $row['name'];
        $rr_title[$id] = $row['title'];
        $rr_useit[$id] = $row['useit'];
      }
      foreach ($rr_title as $id => $title) {
        foreach ($search_line as $s_line) {
          # поиск в заголовке разделов
          if(preg_match('/['.str_replace("%", "|", $s_line).']/i',$title)) 
            if (!in_array($id,$allpids)) { $pids2[] = $id; $allpids[] = $id; } // если нет в списке, заносим в список страниц
          # поиск в содержании разделов
          if(preg_match('/['.str_replace("%", "|", $s_line).']/i',$rr_useit[$id])) 
            if (!in_array($id,$allpids)) { $pids2[] = $id; $allpids[] = $id; }
        }
      }
      $pids = array_merge($pids1,$pids2,$pids3);
      $numrows1 = count($pids);
      if ($numrows1 == 0) $nu = "не найдено"; else { $nu = $numrows1; $allnum += $nu; }
      $soderganie .= "<h2>В разделах: ".$nu."</h2>";
      foreach ($pids as $p_pid) {
        $p_title = $rr_title[$p_pid];
        $soderganie .= "<li>раздел <a class='search_page_link' href='/-".$rr_name[$p_pid]."'>".$p_title."</a>";
        if (is_admin($admin)) $soderganie .= "&nbsp; <a href='sys.php?op=mainpage&type=2&id=".$p_pid."' title='Изменить раздел в Редакторе'><img src=images/sys/edit_1.png></a><a href='sys.php?op=mainpage&type=2&id=".$p_pid."&red=1' title='Изменить раздел (быстрый HTML режим)'><img src='images/sys/edit_0.png'></a>";
        foreach ($slovo as $s_line) {
          $txt = strchop(strip_tags(str_replace("&nbsp;"," ",str_replace("[содержание]"," ",str_replace("[следующий]"," ",str_replace("[название]"," ",str_replace("[страницы]"," ",str_replace("<br>"," ",str_replace("<p>"," ",$rr_useit[$p_pid])))))))),$s_line,100);
          if ($txt != "......" and $txt != false) $soderganie .= "<blockquote>".$txt."</blockquote>";
        }
      }
  ////////////////////////////////////////////////////////////////////////////////////////
      $res2 = $db->sql_query("SELECT `cid`,`module`,`title`,`description` FROM ".$prefix."_pages_categories where `tables`='pages' and ( (LOWER(`title`) LIKE '%".$s."%' or LOWER(`description`) LIKE '%".$s."%')".$add_papka." ) limit ".$col_papka);
      $allpids = $pids1 = $pids2 = $pids3 = $rr_title = $rr_description = $rr_module = array(); //  = $rr_name
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
            if (!in_array($id,$allpids)) { $pids2[] = $id; $allpids[] = $id; }
          # поиск в содержании папок
          if(preg_match('/['.str_replace("%", "|", $s_line).']/i',$rr_description[$id])) 
            if (!in_array($id,$allpids)) { $pids2[] = $id; $allpids[] = $id; }
        }
      }
      $pids = array_merge($pids1,$pids2,$pids3);
      $numrows1 = count($pids);
      if ($numrows1 != 0) { $soderganie .= "<h2>В папках: ".$numrows1."</h2>"; $allnum += $nu; }
      
      foreach ($pids as $p_cid) {
        $soderganie .= "<li>папка <a class='search_page_link' href='/-".$rr_module[$p_cid]."_cat_".$p_cid."'>".$rr_title[$p_cid]."</a>";
        if (is_admin($admin)) $soderganie .= "&nbsp; <a href='sys.php?op=edit_base_pages_category&cid=".$p_cid."' title='Изменить папку в Редакторе'><img src=images/sys/edit_1.png></a><a href='sys.php?op=edit_base_pages_category&cid=".$p_cid."&red=1' title='Изменить папку (быстрый HTML режим)'><img src='images/sys/edit_0.png'></a>";
        foreach ($slovo as $s_line) {
          $txt = strchop(strip_tags(str_replace("&nbsp;"," ",str_replace("<br>"," ",str_replace("<p>"," ",$rr_description[$p_cid])))),$s_line,100);
          if ($txt != "......" and $txt != false) $soderganie .= "<blockquote>".$txt."</blockquote>";
        }
      }
  }
  ////////////////////////////////////////////////////////////////////////////////////////
      $res2 = $db->sql_query("SELECT `pid`,`module`,`cid`,`title`,`open_text`,`main_text` FROM ".$prefix."_pages where `tables`='pages'".$papka." and `active`='1' and (`copy`='0' or `copy`=pid) and ( (LOWER(`title`) LIKE '%".$s."%' or LOWER(`main_text`) LIKE '%".$s."%' or LOWER(`open_text`) LIKE '%".$s."%')".$add_pages." ) limit ".$col_page);
      $allpids = $pids1 = $pids2 = $pids3 = $pp_title = $pp_module = $pp_cid = $pp_open_text = array();
      while ($row = $db->sql_fetchrow($res2)) {
        $id = $row['pid'];
        $pp_title[$id] = $row['title'];
        $pp_module[$id] = $row['module'];
        $pp_cid[$id] = $row['cid'];
        $pp_open_text[$id] = $row['open_text'].$row['main_text'];
      }
      foreach ($pp_title as $id => $title) {
        foreach ($search_line as $s_line) {
          # поиск в заголовке страниц
          if (stripos(" ".$title, $s_line)) 
            if (!in_array($id,$allpids)) { $pids1[] = $id; $allpids[] = $id; } // если нет в списке, заносим в список страниц
          # поиск в предисловии страниц
          if (stripos(" ".$pp_open_text[$id], $s_line)) 
            if (!in_array($id,$allpids)) { $pids2[] = $id; $allpids[] = $id; }
        }
      }
      $pids = array_merge($pids1,$pids2,$pids3);
      $numrows1 = count($pids);
      if ($numrows1 == 0) $nu = "не найдено"; else { $nu = $numrows1; $allnum += $nu; }
      $soderganie .= "<h2>В страницах: ".$nu."</h2>";
      foreach ($pids as $p_pid) {
        $p_title = $pp_title[$p_pid];
        $p_module = $pp_module[$p_pid];
        $p_cid = $pp_cid[$p_pid];
        if ($p_cid != 0) $cat = " ".$strelka." <a class='search_papka_link' href='/-".$p_module."_cat_".$p_cid."'>".$c_name[$p_cid]."</a>"; else $cat = "";
        $soderganie .= "<li>стр. <a class='search_page_link' href='/-".$p_module."_page_".$p_pid."'>".$p_title."</a>";
        if (is_admin($admin)) $soderganie .= "&nbsp; <a href='sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid."' title='Изменить страницу в Редакторе'><img src=images/sys/edit_1.png></a><a href='sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid."&red=1' title='Изменить страницу (быстрый HTML режим)'><img src='images/sys/edit_0.png'></a>";
        $soderganie .= "<br><a class='search_razdel_link' href='/-".$p_module."'>".$title_razdels[$p_module]."</a> ".$cat;
        foreach ($slovo as $s_line) {
          $txt = strchop(strip_tags(str_replace("&nbsp;"," ",str_replace("<br>"," ",str_replace("<p>"," ",$pp_open_text[$p_pid])))),$s_line,100);
          if ($txt != "......") $soderganie .= "<blockquote>".$txt."</blockquote>";
        }
      }
  $soderganie .= "</ol></div>";

  if ($allnum == 0) $soderganie = $soderganiemain."<h3>Данное словосочетание не обнаружено. Попробуйте поискать по другим словам.</h3>";
  else $soderganie = $soderganiemain.$soderganie;
}
  // Получаем дизайн для поиска
  global $search_design;
  // Определение дизайна и использованных стилей в дизайне
  list($design_for_search, $stil) = design_and_style($search_design);
  if ($design_for_search == "0") die("Ошибка: «Адрес раздела» (".$name.") введен неправильно. Перейдите на <a href=/>Главную страницу</a>.");
  $block = str_replace("[содержание]",$soderganie,$design_for_search);

  // Занесение слова в БД
  if ($db->sql_numrows($db->sql_query("SELECT `id` FROM ".$prefix."_search where `slovo`='$slov' and ip='$ip'")) == 0 and trim($slov) != '' and !is_admin($admin)) $db->sql_query("INSERT INTO `".$prefix."_search` (`id`,`ip`,`slovo`,`data`,`pages`) VALUES (NULL, '$ip', '$slov', '$now', '$allnum');");

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
  $zamena = array(" а "=>" "," в "=>" "," и "=>" "," к "=>" "," о "=>" "," с "=>" "," у "=>" "," я "=>" "," во "=>" "," до "=>" "," за "=>" "," из "=>" "," на "=>" "," не "=>" "," ни "=>" "," но "=>" "," по "=>" "," об "=>" "," то "=>" "," для "=>" "," или "=>" "," над "=>" "," обо "=>" "," про "=>" ","Про "=>""," же "=>" ", " около "=>" "," перед "=>" "," после "=>" "," против "=>" "," напротив "=>" "," кто такой "=>" "," что такое "=>" "," кто "=>" "," что "=>" "," какой "=>" "," зачем "=>" "," почему "=>" "," когда же "=>" "," когда будет "=>" "," когда "=>" "," разрешается ли "=>" "," можно ли "=>" "," как бы "=>" "," как "=>" "," с какими "=>" "," какими "=>" "," с какой "=>" "," какой "=>" "," с каким "=>" "," каким "=>" "," о ком "=>" "," о чем "=>" "," чем "=>" ");
  $text = trim(strtr(" ".$text." ",$zamena));

  return $text;
}


setlocale(LC_ALL, 'ru_RU.UTF-8', 'rus');
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
    function s(&$s, $re, $to)
    {
        $orig = $s;
        $s = preg_replace($re, $to, $s);
        return $orig !== $s;
    }
    function m($s, $re)
    {
        return preg_match($re, $s);
    }
    function stem_word($word)
    {
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
    function stem_caching($parm_ref)
    {
        $caching_level = @$parm_ref['-level'];
        if ($caching_level) {
            if (!$this->m($caching_level, '/^[012]$/u')) {
                die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
            }
            $this->Stem_Caching = $caching_level;
        }
        return $this->Stem_Caching;
    }
    function clear_stem_cache()
    {
        $this->Stem_Cache = array();
    }
}

?>