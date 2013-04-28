<?php
/////////////////////////////////////////////////////////
function is_admin($admin) { // Проверка админа
  global $adminSave;
  if (!$admin) { return 0; }
  if (isset($adminSave)) return $adminSave;
  if (!is_array($admin)) {
    $admin = base64_decode($admin);
    $admin = addslashes($admin);
    $admin = explode(":", $admin);
  }
  $aid = $admin[0];
  $pwd = $admin[1];
  $aid = substr(addslashes($aid), 0, 25);
  if (!empty($aid) && !empty($pwd)) {
    global $prefix, $db;
    $sql = "SELECT pwd FROM ".$prefix."_authors WHERE aid='$aid' limit 1";
    $result = $db->sql_query($sql);
    $pass = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    if ($pass[0] == $pwd && !empty($pass[0])) {
      return $adminSave = 1;
    }
  }
  return $adminSave = 0;
}
/////////////////////////////////////////////////////////
function FixQuotes($what = "",$strip="") {
  while (stripos($what, "\\\\'")) { // stristr
    $what = str_replace("\\\\'","'",$what);
  }
  return $what;
}
/////////////////////////////////////////////////////////
function delQuotes($string) { # Фильтры текста
  $tmp="";    # string buffer
  $result=""; # result string
  $i=0;
  $attrib=-1; # Are us in an HTML attrib ?   -1: no attrib   0: name of the attrib   1: value of the atrib
  $quote=0;   # Is a string quote delimited opened ? 0=no, 1=yes
  $len = strlen($string);
  while ($i<$len) {
    switch($string[$i]) { # What car is it in the buffer ?
    case "\"": #"       # a quote.
    if ($quote==0) {
      $quote=1;
    } else {
      $quote=0;
      if (($attrib>0) && ($tmp != "")) { $result .= "=\"$tmp\""; }
      $tmp="";
      $attrib=-1;
    }
    break;
    case "=":           # an equal - attrib delimiter
    if ($quote==0) {  # Is it found in a string ?
    $attrib=1;
    if ($tmp!="") $result.=" $tmp";
    $tmp="";
    } else $tmp .= '=';
    break;
    case " ":           # a blank ?
    if ($attrib>0) {  # add it to the string, if one opened.
    $tmp .= $string[$i];
    }
    break;
    default:            # Other
    if ($attrib<0)    # If we weren't in an attrib, set attrib to 0
    $attrib=0;
    $tmp .= $string[$i];
    break;
    }
    $i++;
  }
  if (($quote!=0) && ($tmp != "")) {
    if ($attrib==1) $result .= "=";
    /* If it is the value of an atrib, add the '=' */
    $result .= "\"$tmp\"";  /* Add quote if needed (the reason of the function ;-) */
  }
  return $result;
}
/////////////////////////////////////////////////////////
function check_html ($str, $strip="") {
  /* The core of this code has been lifted from phpslash */
  /* which is licenced under the GPL. */
  if ($strip == "nohtml")
  $str = stripslashes($str);
  $str = eregi_replace("<[[:space:]]*([^>]*)[[:space:]]*>",'<\\1>', $str);
  $str = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?[[:space:]]*([^\" >]*)[[:space:]]*\"?[^>]*>",'<a href="\\1">', $str);
  $str = eregi_replace("<[[:space:]]* img[[:space:]]*([^>]*)[[:space:]]*>", '', $str);
  $str = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?javascript[[:punct:]]*\"?[^>]*>", '', $str);
  /*
  $tmp = "";
  while (ereg("<(/?[[:alpha:]]*)[[:space:]]*([^>]*)>",$str,$reg)) {
    $i = strpos($str,$reg[0]);
    $l = strlen($reg[0]);
    if ($reg[1][0] == "/") $tag = strtolower(substr($reg[1],1));
    else $tag = strtolower($reg[1]);
    if ($reg[1][0] == "/") $tag = "</$tag>";
    elseif ($reg[2] == "") $tag = "<$tag>";
    else {
      $attrb_list=delQuotes($reg[2]);
      $tag = "<$tag" . $attrb_list . ">";
    }
    $tmp .= substr($str,0,$i) . $tag;
    $str = substr($str,$i+$l);
  }
  $str = $tmp . $str;
  */
  return $str;
  exit;
  /* Squash PHP tags unconditionally */
  $str = ereg_replace("<\?","",$str);
  return $str;
}
/////////////////////////////////////////////////////////
/*
function filter($what, $strip="") {
  if ($strip == "nohtml") {
    $what = trim( strip_tags ( $what ) ); // check_html
  }
  $what = stripslashes(FixQuotes($what));
  return($what);
}
*/
function filter($what, $strip="", $save="") {
  if ($strip == "nohtml") {
    $what = check_html($what, $strip);
    $what = htmlentities(trim($what), ENT_QUOTES, "UTF-8");
  }
  if ($save == 1) {
    $what = check_html($what, $strip);
    $what = addslashes($what);
  } else {
    $what = stripslashes(FixQuotes($what));
    $what = check_html($what, $strip);
  }
  return($what);
}
/////////////////////////////////////////////////////////
function getuseragent() { // удалить
  return htmlspecialchars($_SERVER["HTTP_USER_AGENT"]);
}
/////////////////////////////////////////////////////////
function getip() { // Получаем IP-адрес
  if (getenv('REMOTE_ADDR'))  {
    $user_ip = getenv('REMOTE_ADDR');
  }
  elseif (getenv('HTTP_FORWARDED_FOR')) {
    $user_ip = getenv('HTTP_FORWARDED_FOR');
  }
  elseif (getenv('HTTP_X_FORWARDED_FOR')) {
    $user_ip = getenv('HTTP_X_FORWARDED_FOR');
  }
  elseif (getenv('HTTP_X_COMING_FROM'))  {
    $user_ip = getenv('HTTP_X_COMING_FROM');
  }
  elseif (getenv('HTTP_VIA')) {
    $user_ip = getenv('HTTP_VIA');
  }
  elseif (getenv('HTTP_XROXY_CONNECTION')) {
    $user_ip = getenv('HTTP_XROXY_CONNECTION');
  }
  elseif (getenv('HTTP_CLIENT_IP'))  {
    $user_ip = getenv('HTTP_CLIENT_IP');
  }
  else {
    $user_ip='unknown';
  }
  if (15<strlen($user_ip)) {
    $ar = explode (', ', $user_ip);
    $so=sizeof($ar)-1;
    for ($i=$so; $i>0; $i--) {
      if ($ar[$i]!='' and !preg_match ('/[a-zA-Zа-яА-Я]/', $ar[$i])) {
        $user_ip = $ar[$i];
        break;
      }
      if ($i==$so) {
        $user_ip = 'unknown';
      }
    }
  }
  if (preg_match ('/[^0-9\.]/', $user_ip)) {
    $user_ip = 'unknown';
  }
  return $user_ip;
}
/////////////////////////////////////////////////////////
function findMonthName($m, $language="") { // Функция определения имени месяца по его числу
  $m = intval($m);
  if ($language == "english short") $month = array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sen","Oct","Nov","Dec");
  else $month = array("", "января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
  return $month[$m];
}
////////////////////////////////////////////////////////
function tipograf($text, $p=0) { // Типографика - все основные знаки препинания
  if ($p==0) $text = "<p>".trim($text)."</p>";
  // Смайлы (можно добавить замену смайлов)
  $text=str_replace(" :) ", "<img src=/images/smilies/04.gif>", $text);
  $text=str_replace(" :( ", "<img src=/images/smilies/11.gif>", $text);
  $zamena = array(
  "<div><br /> 
  </div>"=>"<br>",
  "\"/>"=>"\">",
  "MsoNormalTable"=>"table_light",
  " class=\"Apple-style-span\""=>"",
  "st1:metricconverter"=>"span",
  " w:st=\"on\""=>"",
  "&nbsp;"=>" ",
  "<B"=>"<b",
  "<I"=>"<i",
  "<BR"=>"<br",
  "<P"=>"<p",
  "</B>"=>"</b>",
  "</I>"=>"</i>",
  "</P>"=>"</p>",
  "<HR"=>"<hr",
  "SPAN"=>"span",
  " class=\"Apple-style-span\""=>"",
  " style=\"font-size: 11px; \""=>"",
  "<b> </b>"=>"",
  "<i> </i>"=>"",
  "<br></b>"=>"</b><br>",
  "<br></i>"=>"</i><br>",
  "<p>\t</p>"=>"",
  "<p> </p>"=>"",
  "<p>\r\n</p>"=>"",
  "\r\n"=>" ",
  "\t"=>" ",
  "На "=>"На&nbsp;",
  "<p></p>"=>" ",
  "class=\"\""=>"",
  "style=\"\""=>"",
  "height=\"\""=>"",
  "width=\"\""=>"",
  "</p></p>"=>"</p>",
  "<p> <p>"=>"<p>",
  "<p><p>"=>"<p>",
  "То "=>"То&nbsp;",
  "</p> </p>"=>"</p>",
  "</p>&nbsp;</p>"=>"</p>",
  "т. д."=>"т.д.",
  "т. к."=>"т.к.",
  "- "=>"—&nbsp;",
  "&mdash; "=>"—&nbsp;",
  "-&nbsp;"=>"—&nbsp;",
  "&mdash;&nbsp;"=>"—&nbsp;",
  "( "=>"(",
  "("=>" (",
  " )"=>")",
  ")"=>") ",
  ") ."=>").",
  ") ,"=>"),",
  ") ;"=>");",
  ") :"=>"):",
  ") )"=>"))",
  "– "=>" — ",
  "- "=>" — ",
  " %"=>"% ",
  " ;"=>"; ",
  " !"=>"!",
  " ?"=>"?",
  " :"=>":",
  " ."=>".",
  " ,"=>",",
  ","=>", ",
  "...."=>"… ",
  "..."=>"… ",
  ".."=>"… ",
  "…."=>"…",
  "… ."=>"…",
  "…"=>"… ",
  "&hellip;"=>"…",
  "FONT-WEIGHT"=>"font-weight",
  "FONT-STYLE"=>"font-style",
  "<p> <br><br> <p>"=>"<p>",
  "<br><br></p>"=>"</p>",
  "<b>"=>"<strong>",
  "</b>"=>"</strong>",
  "<i>"=>"<em>",
  "</i>"=>"</em>",
  "<div><hr>"=>"<hr>", 
  "<p>&nbsp;</p>"=>"<br><br>",
  "<p>&nbsp;"=>"<p>",
  "<div><em>"=>"<em>",
  "</em> 
  </div>"=>"</em>",
  "</em><br> <em>"=>"<br>",
  "</strong><br> <strong>"=>"<br>",
  "<p>&nbsp;</p> \n<ul>"=>"<ul>",
  "</ul> \n<p>&nbsp;</p>"=>"</ul>",
  "<br/></strong></em></p>"=>"</strong></em></p>",
  "<br/></strong></p>"=>"</strong></p>",
  "<br/></p>"=>"</p>",
  "    "=>" ",
  "   "=>" ",
  "  "=>" ",
  "  "=>" "
  );
  $text = strtr( strtr($text, $zamena), $zamena);
  //$text = preg_replace('B"b([^"x84x93x94rn]+)b"B', '?1?', $text); // Замена кавычек
  //$text = preg_replace("/.+/i", ".", $text);
  //$text = preg_replace('#(\.|\?|!|\(|\)){3,}#', '\1\1\1', $text); // замена повторяющихся знаков препинания, например двойные запятые

  //$pattern = "/http:\/\/www.onlinedisk.ru\/image\/"."(\d+)"."\/"."(\w+)".".jpg"."/i";
  //$replacement = "http://www.onlinedisk.ru/get_image.php?id=$1";
  //$text =  preg_replace($pattern, $replacement, $text);

  // Кавычки! («ёлочки» - &laquo; и &raquo или „лапки“ - &#132; и &#147;)
  //$text = preg_replace('/(^|\s)"(\S)/', '$1&laquo;$2', $text);
  //$text = preg_replace('/(\S)"([ .,?!])/', '$1&raquo;$2', $text);
  if ($p!=2) $text = predlogi($text);
  return $text;
}
///////////////////////////////////////////////////////////////
function strtolow($txt, $t=1) { # Большие буквы в маленькие (и наоборот, при t=0)
  $from   = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЬЫЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $to     = 'абвгдеёжзийклмнопрстуфхцчшщъьыэюяabcdefghijklmnopqrstuvwxyz';
  if ($t==1) $txt = strtr($txt, $from, $to); elseif ($t==0) $txt = strtr($txt, $to, $from);
  return $txt;
}
///////////////////////////////////////////////////////////////
function translit($cyr_str) { # Транслит
  $tr = array(
   "Ґ"=>"G","Ё"=>"YO","Є"=>"E","Ї"=>"YI","І"=>"I","і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"#","є"=>"e",
   "ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I",
   "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
   "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH","Щ"=>"SCH","Ъ"=>"'","Ы"=>"YI","Ь"=>"",
   "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"'",
   "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya","«"=>"","»"=>""
  );
   return $str = iconv ( "UTF-8", "UTF-8//IGNORE", strtr ( $cyr_str, $tr ) );
}
///////////////////////////////////////////////////////////////
function translit_name($cyr_str) { # Транслит названий файлов
  $tr = array(
   "Ґ"=>"G","Ё"=>"YO","Є"=>"E","Ї"=>"YI","І"=>"I","і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"","є"=>"e",
   "ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I",
   "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
   "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
   "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"",
   "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
   "«"=>"","»"=>"","."=>"",","=>"","!"=>"",":"=>"",";"=>"","?"=>""," "=>"_"
  );
   return $str = iconv ( "UTF-8", "UTF-8//IGNORE", strtr ( $cyr_str, $tr ) );
}
///////////////////////////////////////////////////////////////
function dateresize($dat) { // Функция для приведения даты в формат кол-ва дней (примерно)
  if ($dat=="") return 0;
  $dat = str_replace("-",".",$dat);
  $dat=explode(" ", $dat);
  $dat=explode(".", $dat[0]);
  $y = $dat[0] * 365;
  $m = $dat[1] * 30;
  $d = $dat[2];
  return $y+$m+$d;
}
///////////////////////////////////////////////////////////////
function date2normal_view($dat, $r=0, $t=0, $eng=0) { // Функция для приведения даты из формата 2012-02-20 в формат 20 Февраля 2012
  // $t = 1 - выводить также и время
  // $r = 2 - формат + Сегодня/Завтра/Послезавтра/Вчера/Позавчера
  global $lang, $data_days;
  if ($lang != "ru-RU") $eng = 1;
  if ($data_days == true) $r = 2;

  if ($eng == 0) $months = array("?", "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
  else $months = array("?", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  $year = date("Y");
  if ($t != 0) {
    $dat = explode(" ", trim($dat));
    $time = explode(":",$dat[1]);
    $time = " в ".intval($time[0]).":".$time[1];
    $dat = $dat[0];
  }
  if ($r == 0 or $r == 2) { // если r=0 - стандартная функция
    // Функция для приведения даты из формата 2012-02-20 в формат 20 Февраля 2012
    $dat = explode("-", trim($dat));
    $y = $dat[0];
    $m = $dat[1];
    $d = $dat[2];
    $m2 = intval($m);
    $data = intval($d)." ".$months[$m2]." ".intval($y);
    //if ($y == 1) $data = str_replace(" ".$year, "", $data);
    if ($r == 2) { // Функция для приведения даты из формата 2012-02-20 в формат 20 Февраля 2012 + Сегодня/Вчера
      // доработать - настройка.
      $datax1_1 = $d." ".$m." ".$y;
      $date_now = date("d m Y");
      $date_now2 = date("d m Y",time()-86400);
      $date_now3 = date("d m Y",time()-172800);
      $date_now4 = date("d m Y",time()+86400);
      $date_now5 = date("d m Y",time()+172800);
      if ($date_now == $datax1_1) $data = "сегодня";
      if ($date_now2 == $datax1_1) $data = "вчера";
      if ($date_now3 == $datax1_1) $data = "позавчера";
      if ($date_now4 == $datax1_1) $data = "завтра";
      if ($date_now5 == $datax1_1) $data = "послезавтра";
      $data = str_replace(" ".$year, "", $data);
    }
    if ($t != 0) $data .= $time;
    return $data;
  } elseif ($r == 1) { // Функция для приведения даты из формата 20 Февраля 2012 в формат 2012-02-20
    $dat = explode(" ", trim($dat));
    $y = $dat[2];
    $m = array_search(trim($dat[1]),$months); if ($m<10) $m="0".$m;
    $d = intval($dat[0]); if ($d<10) $d="0".$d;
    return $y."-".$m."-".$d;
  } 
}
///////////////////////////////////////////////////////////////
function period($dat1, $dat2) { // Функция вывода всех дат между двумя датами (период времени)
  // Формат входной даты: две даты типа 2009-12-31 (соответствует хранению в БД)
  // Формат выходной даты: массив дат типа 2009-12-31 (соответствует хранению в БД)
  // легко можно сделать и 31.12.2009 (соответствует адекватному восприятию)
  $date = explode('-', $dat1);
  $begin_year = $date[0];
  $begin_month = $date[1];
  $begin_day = $date[2];
  $date = explode('-', $dat2);
  $end_year = $date[0];
  $end_month = $date[1];
  $end_day = $date[2];
  $period = array();
  for ($cur_year = $begin_year; $cur_year <= $end_year; $cur_year++) {
      if ($cur_year == $end_year) $max_month = $end_month;
      else $max_month = 12;
      if ($cur_year == $begin_year) $cur_month = $begin_month;
      else $cur_month = 1;
      for ($cur_month; $cur_month <= $max_month; $cur_month++) {
      $cur_month = intval($cur_month);
    if ($cur_month == $end_month) $max_day = $end_day;
    else $max_day = cal_days_in_month(CAL_GREGORIAN, intval($cur_month), $cur_year);
    if ($cur_month == $begin_month) $cur_day = $begin_day;
    else $cur_day = 1;
        for ($cur_day; $cur_day <= $max_day; $cur_day++) {
        $cur_day = intval($cur_day);
            $period[] = $cur_year.'-'.($cur_month < 10 ? '0'.$cur_month : $cur_month).'-'.($cur_day < 10 ? '0'.$cur_day : $cur_day);
          }
      }
  }
  return $period;
}
///////////////////////////////////////////////////////////////
function my_calendar($fill='', $modul, $showdate='') { // Функция вывода календаря
  $calendar = "";
  // Вычисляем число дней в текущем месяце
  $dayofmonth = date('t');
  $daynow = date('d');
  $monthandyear =date('Y-m-');
  // Счётчик для дней месяца
  $day_count = 1;
  // 1. Первая неделя
  $num = 0;
  for($i = 0; $i < 7; $i++) {
    // Вычисляем номер дня недели для числа
    $dayofweek = date('w', mktime(0, 0, 0, date('m'), $day_count, date('Y')));
    // Приводим к числа к формату 1 - понедельник, ..., 6 - суббота
    $dayofweek = $dayofweek - 1;
    if($dayofweek == -1) $dayofweek = 6;
    if($dayofweek == $i) {
      // Если дни недели совпадают, заполняем массив $week числами месяца
      $week[$num][$i] = $day_count;
      $day_count++;
    } else {
      $week[$num][$i] = "";
    }
  }
  // 2. Последующие недели месяца
  while(true) {
    $num++;
    for($i = 0; $i < 7; $i++) {
      $week[$num][$i] = $day_count;
      $day_count++;
      // Если достигли конца месяца - выходим из цикла
      if($day_count > $dayofmonth) break;
    }
    // Если достигли конца месяца - выходим из цикла
    if($day_count > $dayofmonth) break;
  }
  // Выводим содержимое массива $week в виде календаря. Выводим таблицу
  $calendar .= "<table cellspacing=0 cellpadding=0 border=0>";
  for($j = 0; $j < 7; $j++) {
    $calendar .= "<tr align=center>";
    for($i = 0; $i < count($week); $i++) {
      if(!empty($week[$i][$j])) {
        // Если имеем дело с субботой и воскресеньем — подсвечиваем их
        $class = "";
        if ($week[$i][$j]<10) $den = "0".$week[$i][$j]; else $den = $week[$i][$j];
        
        if ($week[$i][$j] == $daynow) $class = "bold";
        if ($monthandyear.(intval($week[$i][$j]) < 10 ? '0'.intval($week[$i][$j]) : $week[$i][$j]) == $showdate) $class = "select";
        if($j == 5 || $j == 6) $class .= " red"; else $class .= " black";
        if (in_array($monthandyear.$den,$fill)) $calendar .= "<td class='calendar ".trim($class)."'><a href=/-".$modul."_date_".$monthandyear.(intval($week[$i][$j]) < 10 ? '0'.intval($week[$i][$j]) : $week[$i][$j]).">".$week[$i][$j]."</a></td>";
        else $calendar .= "<td class='calendar ".trim($class)."'>".$week[$i][$j]."</td>";
      } else $calendar .= "<td>&nbsp;</td>";
    }
    $calendar .= "</tr>";
  } 
  $calendar .= "</table><font class='small black'>Сегодня: ".date2normal_view($monthandyear.$daynow)."</font>";
  if (trim($showdate) != "0-00-00" and trim($showdate) != "" and $showdate != $monthandyear.$daynow) $calendar .= "<br><font class='small red'>Выбрано: ".date2normal_view($showdate)."</font>";
  return $calendar;
}
////////////////////////////////////////////////////////////
function select($name,$vars,$vars_name,$znachenie,$add='') { // генерация SELECT элемента формы
  // $add - добавление, например id или onchange...
  if ( ($vars == "0,1" || $vars == "1,0") && ($vars_name == "ДА,НЕТ" || $vars_name == "НЕТ,ДА" || $vars_name == "НЕТ,ЕСТЬ")) {
    $add .= " class='hide'";
    $style1 = $style2 = "";
    if ($znachenie == "1") $style1 = "style='display:none;'"; 
    else $style2 = "style='display:none;'";
    $id = md5($name);
    $button = "<a class='button red white' id=on_".$id." href='javascript: $(\"#".$id." [value=1]\").attr(\"selected\", \"selected\");  $(\"#on_".$id."\").hide().next().show();'".$style1.">Выключено</a><a class='button green' id=off_".$id." href='javascript: $(\"#".$id." [value=0]\").attr(\"selected\", \"selected\"); $(\"#off_".$id."\").hide().prev().show();'".$style2.">Включено</a>";
  } else { $button = ""; $id=""; }
  $return = "<select id='".$id."' name='".$name."'".$add.">";
  $vars = explode(",",$vars);
  $vars_name = explode(",",$vars_name);
  $vybor = false; // если выбор не сделан - напишем этот невыбранный вариант!
    foreach ($vars as $key => $var) {
    $var = str_replace("[|]",",",$var);
    if ($znachenie == $var) { $vybor = true; $sel=" selected style='background:#dddddd;'"; } else $sel="";
    $vars_name[$key] = str_replace("[|]",",",$vars_name[$key]);
    $return .= "<option value='".$var."'".$sel.">".$vars_name[$key]."</option>";
    }
  if ($vybor == false) {
  $return .= "<option value='".$znachenie."' selected style='background:#dddddd;'>".$znachenie."</option>";
  }
  $return .= "</select>".$button;
  return $return;
}
/////////////////////////////////////////////////////////////
function input($name,$znachenie,$size="40",$type="text",$add='') { // генерация INPUT элемента формы
  if ($type=="txt") 
    return "<textarea name='".$name."'".$add." rows=3 cols=80 style='width:100%; height:".$size."px;'>".$znachenie."</textarea>";
  else
    return "<input type='".$type."' name='".$name."'".$add." value='".$znachenie."' size='".$size."'>";
}
////////////////////////////////////////////////////////////
function smile_generate($smiles, $folder="") { // генерация полоски смайлов
  $smile = "";
  if ($folder != "") $folder = $folder."/";
  foreach ($smiles as $sm) { $smile .= "<img src=\"/images/smilies/".$folder.$sm.".gif\" onClick=\"clc_name(' **".$sm."');\"> "; }
  return $smile;
}
///////////////////////////////////////////////////////////////
function WhatArrayElement($array, $value, $keys=0) { // функция для поиска индекса массива по значению.
  if ($keys==0) {
    while (($key = array_search($value, $array)) !== FALSE)
      return $key;
  } else {
    $keys = array();
    while (($key = array_search($value, $array)) !== FALSE and !in_array($key,$keys)) { 
      $keys[] = $key; $key = false;
    }
    return $keys;
  }
}
///////////////////////////////////////////////////////////////
function form($module, $text, $type="open") { // Функция для форматирования текста страниц
  // type: open, main
  global $db, $prefix;
  $result8 = $db->sql_query("select text from ".$prefix."_mainpage where type='2' and name='".$module."'");
  $row8 = $db->sql_fetchrow($result8);
  $text2 = $row8['text'];
  if (strpos($text2,"tipograf=0") < 1) { // Типограф
    $text = tipograf($text, 1);
  }
  if (strpos($text2,"no_html_in_opentext=1") > 1 and $type=="open") strip_tags($text, '<a><img>'); // Удаление HTML из предисловия
  if (strpos($text2,"no_html_in_text=1") > 1 and $type=="main") strip_tags($text, '<a><img>'); // Удаление HTML из предисловия
  if (strpos($text2,"table_light=1") > 0 and $type=="main") // Добавление класса table_light
    $text = str_replace("<table", "<table class='table_light'", $text);
  //if (get_magic_quotes_gpc($text)) $text = stripslashes($text);
  //$text = filter($text);
  return $text;
}
///////////////////////////////////////////////////////////////// проверить дубликат
function block_names() { // Функция для получения названий всех блоков (для дизайна и шаблонов) 
  global $db, $prefix;
  $blocks = "";
  $sql3 = "select title from ".$prefix."_mainpage where type='3'";
  $result3 = $db->sql_query($sql3);
  while ($row3 = $db->sql_fetchrow($result3)) {
    $title_modul = trim($row3['title']);
    $blocks .= "[".$title_modul."] ";
  }
  return $blocks;
}
///////////////////////////////////////////////////////////////
function upload_foto_file($text){ // доработать
  return "<br><a onclick=\"show('upload_file'); hide('upload_foto');\" style='cursor:pointer;'><u>Загрузка фото или других файлов</u></a><br><div id='upload_file' style='display:none;'><div class=block2>
  <form action='http://www.onlinedisk.ru/upload/' method='POST' enctype='multipart/form-data' id='upload_file' target=_blank><b>Выберите файл:</b><br><input name=\"file\" type=\"file\" size=50><input type=\"submit\" value=\"Загрузить\"><input type=hidden name=MAX_FILE_SIZE value=30000> 
  </form><b>На открывшейся странице</b> скопируйте текст в поле под словом «Для форумов» и вставьте ниже ".$text.".</div></div><br><br>";
}
///////////////////////////////////////////////////////////////
function recash($url, $main=1) { // Обновление кеша
  global $db, $prefix, $site_cash;
  $u = explode("_page_",$url);
  if ($site_cash == "base") { // если кеш хранится в БД
    if ($main == 1) $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url`='/'");
    if (mb_strpos($url,"_page_")) {
      $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url`='".$u[0]."'");
      $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url` LIKE '".$u[0]."_cat_%'");
    }
    $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url`='".$url."'"); 
  } else { // если кеш файловый
    if ($main == 1) if (file_exists("cashe/-index")) unlink("cashe/-index");
	if (mb_strpos($url,"_page_")) {
      if (file_exists("cashe/".$u[0])) unlink("cashe/".$u[0]);
	  	$files = glob("cashe/".$u[0]."_cat_*");
    $c = count($files);
    if (count($files) > 0) {
        foreach ($files as $file) {
            if (file_exists($file)) unlink($file);
        }
    } 
    }
    if (file_exists("cashe/".$url)) unlink("cashe/".$url);
  }
}
///////////////////////////////////////////////////////////////
function obrez($word) { // Функция обрезания окончаний
  $result = ''; $make = 0;
  //Окончания
  $closes = array('овая','овый','овое','ёвое','евое','ная','ной','ный','ные','ый','ые','ий','ой','ая','ов','ах','ав','ях','ое','ям','ом','ем','ей','ёй','ай','ец','а','е','и','о','у','ь','ы','ю','я');
  $word_count = mb_strlen($word);
  if ($word_count >= 4) foreach ($closes AS $part) 
    if (preg_match('/(.*)'.$part.' /', $word)) {
      $wordX = mb_substr($word, 0, $word_count - mb_strlen($part));
      if ($wordX != $word) { $word = $wordX; break; }
    }

    $chars = array('а','е','ё','й','и','о','у','ь','ы','э','ю','я'); //Буквы
    for ($position = $word_count-1; $position >= 0; $position--) {
      $char = mb_substr($word, $position, 1);
      if (!in_array($char,$chars)) $make = 1;
      if ($position==2) $make = 1;
      if ($make==1) $result = $char.$result;
    }

  return $result;
}
///////////////////////////////////////////////////////////////
function getparent($name, $parentid, $title) { // получение родительской папки
    global $prefix, $db;
    $sql = "select title, parent_id from ".$prefix."_pages_categories where module='$name' and `tables`='pages' and cid='$parentid' order by cid";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $ptitle = strip_tags($row['title'], '<b><i>');
    $pparentid = $row['parent_id'];
    if ($ptitle!="") $title = $ptitle."/".$title;
    if ($pparentid!=0) {
      $title = getparent($name,$pparentid,$title);
    }
    return $title;
}
///////////////////////////////////////////////////////////////
function getparent_spiski($name, $parent, $title) { // получение родительского списка
    global $tip, $admintip, $prefix,$db;
    $sql = "select name, parent from ".$prefix."_spiski where type='$name' and id='$parent'";
    $result = $db->sql_query($sql) or die('Ошибка: Не далось подключиться к спискам');
    $row = $db->sql_fetchrow($result);
    $ptitle = $row['name'];
    $pparent = $row['parent'];
    if ($ptitle!="") $title = $ptitle."/".$title;
    if ($pparent!=0) {
      $title = getparent_spiski($name, $pparent, $title);
    }
    return $title;
}
/////////////////////////////////////////////////////////////// сделать настройку
function antivirus($x=0) { // антивирус для защиты от htaccess-вируса (временные костыли)
  // открываем .htaccess
  $htaccess = " ".implode(" ", file('.htaccess'));
  // ищем [NC], HTTP_USER_AGENT и (.*)  or strpos($htaccess,"HTTP_USER_AGENT") or strpos($htaccess,"(.*)")
  if ( strpos($htaccess,"[NC]") ) {
    // меняем .htaccess на .ht_backup
    global $ht_backup;
    if ( $ht_backup != "" and file_exists($ht_backup) ) {
      if (file_exists('.htaccess')) unlink('.htaccess');
      copy($ht_backup, '.htaccess');
      // Оповестим админа
      if ($x == 0) $subg = "Найден и обезврежен «.htaccess»-вирус."; 
      elseif ($x == 1) $subg = "Вирус теперь поражает и резервную копию .htaccess — пора залезть в Настройки Администрирования и поменять название файла резервной копии.";
      system_mes($subg);
      if ($x == 0) antivirus("1");
    }
  }
}
///////////////////////////////////////////////////////////////
function system_mes($subg) { // Отправка системного сообщения администратору (в список «Комментарии»)
  global $prefix, $db, $now;
  $db->sql_query("INSERT INTO ".$prefix."_pages_comments ( `cid` , `num` , `avtor` , `mail` , `text` , `ip` , `data`, `drevo`, `adres`, `tel`, `active` ) VALUES ('', '0', 'ДвижОк', '', '".$subg."', '', '$now', '', '', '', '1')");
}
/////////////////////////////////////////////////////////////////
function vhodyagie($id,$par,$num) { // Функция подсчета входящих в подкатегории страниц
  $num_all = 0;
  foreach ($par as $i1 => $nam1) { // nam - это родственник
    if ($id == $nam1) {
      $x = vhodyagie($i1,$par,$num);
      $num_all = $num_all + $num[$i1] + $x;
    }
  }
  return $num_all;
}
/////////////////////////////////////////////////////////////////
function predlogi($text) { // Добавление к предлогам неразрывного пробела, для типографики страниц
  $predlogi = array(" а","А"," в","В"," и","И"," к","К"," о","О"," у","У"," я","Я"," во","Во"," до","До"," за","За"," из","Из"," на","На"," не","Не"," ни","Ни"," но","Но"," об","Об"," то","То"," для","Для"," или","Или"," над","Над"," обо","Обо"," про","Про"," около","Около"," перед","Перед"," после","После"," против","Против"," напротив","Напротив");
  foreach ($predlogi as $value) {
    $text = str_replace($value." ", $value."&nbsp;",$text);
  }
  $text = str_replace("кое-как","<nobr>кое-как</nobr>",$text);
  $text = str_replace(" же ","&nbsp;же ",$text);
  $text = str_replace(" - "," &mdash; ",$text); // Тире и дефиз
  return $text;
}
/////////////////////////////////////////////////////////////////
function time_otschet($tim, $txt, $do) { // JavaScript обратного отсчета времени
  return "<script language='javascript'> 
  function fulltime () { 
    var time=new Date(); 
    var newYear=new Date(\"".$tim."\"); 
    var totalRemains=(newYear.getTime()-time.getTime()); 
    var RemainsSec = (parseInt(totalRemains/1000));
    var RemainsFullDays=(parseInt(RemainsSec/(24*60*60)));
    var secInLastDay=RemainsSec-RemainsFullDays*24*3600; 
    var RemainsFullHours=(parseInt(secInLastDay/3600)); 
    if (RemainsFullHours<10){RemainsFullHours=\"0\"+RemainsFullHours}; 
    var secInLastHour=secInLastDay-RemainsFullHours*3600;
    var RemainsMinutes=(parseInt(secInLastHour/60));
    if (RemainsMinutes<10){RemainsMinutes=\"0\"+RemainsMinutes}; 
    var lastSec=secInLastHour-RemainsMinutes*60;
    if (lastSec<10){lastSec=\"0\"+lastSec}; 
    document.getElementById(\"RemainsFullDays\").innerHTML=\"<b style='font-size:1.3em;'>\"+RemainsFullDays+\"</b><span id='Rem'> дн</span>\"; 
    document.getElementById(\"RemainsFullHours\").innerHTML=\"<b>\"+RemainsFullHours+\"</b><span id='Rem'> ч</span>\"; 
    document.getElementById(\"RemainsMinutes\").innerHTML=\"<b>\"+RemainsMinutes+\"</b><span id='Rem'> м</span>\"; 
    document.getElementById(\"lastSec\").innerHTML=\"<b>\"+lastSec+\"</b><span id='Rem'> с</span>\"; <!-- highslide start  -->
    setTimeout('fulltime()',10)
  } 
  </script> 
  <div id='clou_xs'>
  <div id='clock'>".$do."
  <span id='RemainsFullDays'></span>
    <span id='RemainsFullHours'></span>
    <span id='RemainsMinutes'></span>
    <span id='lastSec'></span> 
  </div>
  </div>
  <script language='javascript'>fulltime();</script>";
}
/////////////////////////////////////////////////////////////// 
function text_shablon() { // список шаблонов
    global $prefix, $db;
    $text_shablon = array(); 
    $sqlZ = "SELECT `id`,`text` from ".$prefix."_mainpage where `tables`='pages' and type='6'";
    $resultZ = $db->sql_query($sqlZ);
    while ($rowZ = $db->sql_fetchrow($resultZ)) {
      $idZ = $rowZ['id'];
      $text_shablon[$idZ] = $rowZ['text'];
    }
    return $text_shablon;
}
/////////////////////////////////////////////////////////////// 
function titles_papka($cid=0,$all=0) { // список названий папок
    global $prefix, $db;
    $cid = intval($cid);
    if ($cid == 0) {
      $titles_papka = array(); 
      if ($all==0) $and = " and parent_id='0'"; else $and = "";
      //$cid_module = array(); // список принадлежности папок к разделам
      $result = $db->sql_query("SELECT `cid`,`title` from ".$prefix."_pages_categories where `tables`='pages'".$and." order by `title`");
      while ($row = $db->sql_fetchrow($result)) {
        $id = $row['cid'];
        $titles_papka[$id] = $row['title'];
        //$cid_module[$id] = $row['module'];
      }
      return $titles_papka;
    } else {
      $result = $db->sql_query("SELECT `title` from ".$prefix."_pages_categories where `cid`='".$cid."'");
      $row = $db->sql_fetchrow($result);
      $title_papka = $row['title'];
      return $title_papka;
    }
}
/////////////////////////////////////////////////////////////// 
function design_and_style($design) { // Определение дизайна
  global $prefix, $db;
  if (isset($design)) {
    $sql4 = "select `text`, `useit` from ".$prefix."_mainpage where `tables`='pages' and `id`='$design' and type='0'";
    $result4 = $db->sql_query($sql4);
    $numrows = $db->sql_numrows($result4);
  } else $numrows = 0;
  if ($numrows > 0) {
    $row4 = $db->sql_fetchrow($result4);
    $block = $row4['text'];
    $style_useit = trim($row4['useit']);
    // ОТКРЫТО Определение использованных стилей в дизайне
    $useit = explode(" ", $style_useit);
    $n = count($useit);
    $stil = "";
       for ($x=0; $x < $n; $x++) {
         $stil .= " ".$useit[$x];
         //$sql = "select title from ".$prefix."_mainpage where `tables`='pages' and `id`='$useit[$x]'";
         //$result = $db->sql_query($sql);
         //$row = $db->sql_fetchrow($result);
         //$title = trim($row['title']);
         //echo $title;
       }
    $stil = str_replace(" ","-",trim($stil));
    $stil = "/css_".$stil;
  } else $block = $stil = "0";
    return array($block, $stil);
}
///////////////////////////////////////////////////////////////
function validate_email($email) { // Проверка мыла
  return preg_match('/^[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}](?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}-]*[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}])?\.)+[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}](?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}-]*[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}])?$/u', trim($email));
}
/////////////////////////////////////////////////////////////// in_array без учета регистра
function in_arrayi($needle, $haystack) {
  return in_array(strtolower($needle), array_map('strtolower', $haystack));
}
##########################################################################################
?>