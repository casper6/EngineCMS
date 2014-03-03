<?php
/////////////////////////////////////////////////////////
function tags_generate($tags) { // Тэги
  $searches = array();
  $search2 = explode(",",$tags);
  $tags_num = count($search2);
  for ($x=0; $x < $tags_num; $x++) {
    $search2[$x] = trim($search2[$x]);
    $searches[] = "<a class='slovo' href='slovo_".$search2[$x]."'>".str_replace("+","&nbsp;",$search2[$x])."</a>";
  }
  return implode(", ", $searches);
}
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
    $sql = "SELECT `pwd` FROM ".$prefix."_authors WHERE `aid`='".$aid."' limit 1";
    $result = $db->sql_query($sql);
    $pass = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    if ($pass[0] == $pwd && !empty($pass[0])) {
      return $adminSave = 1;
    }
  }
  return $adminSave = 0;
}
///////////////////////////////////////////////////////////////
function num_ending($number, $endings) { // функция правильных окончаний слов
    $num100 = $number % 100;
    $num10 = $number % 10;
    if ($num100 >= 5 && $num100 <= 20) { return $endings[0];
    } else if ($num10 == 0) { return $endings[0];
    } else if ($num10 == 1) { return $endings[1];
    } else if ($num10 >= 2 && $num10 <= 4) { return $endings[2];
    } else if ($num10 >= 5 && $num10 <= 9) { return $endings[0];
    } else { return $endings[2]; }
}
/////////////////////////////////////////////////////////
function FixQuotes($what = "",$strip="") {
  while (stripos($what, "\\\\'")) { // stristr
    $what = str_replace("\\\\'","'",$what);
  }
  return $what;
}
/////////////////////////////////////////////////////////
function delQuotes($string) { # Фильтр текста
  $tmp="";    
  $result=""; 
  $i=0;
  $attrib=-1; 
  $quote=0;   
  $len = strlen($string);
  while ($i<$len) {
    switch($string[$i]) {
      case "\"": 
        if ($quote==0) $quote=1;
        else {
      $quote=0;
          if (($attrib>0) && ($tmp != "")) $result .= "=\"".$tmp."\"";
      $tmp="";
      $attrib=-1;
    }
    break;
      case "=":           
        if ($quote==0) {  
    $attrib=1;
          if ($tmp!="") $result.=" ".$tmp;
    $tmp="";
    } else $tmp .= '=';
    break;
      case " ":           
        if ($attrib>0) $tmp .= $string[$i];
    break;
      default:            
        if ($attrib<0) $attrib=0;
    $tmp .= $string[$i];
    break;
    }
    $i++;
  }
  if (($quote!=0) && ($tmp != "")) {
    if ($attrib==1) $result .= "=";
    $result .= "\"".$tmp."\"";
  }
  return $result;
}
/////////////////////////////////////////////////////////
function check_html ($str, $strip="") {
  if ($strip == "nohtml") $str = stripslashes($str);
  $str = eregi_replace("<[[:space:]]*([^>]*)[[:space:]]*>",'<\\1>', $str);
  $str = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?[[:space:]]*([^\" >]*)[[:space:]]*\"?[^>]*>",'<a href="\\1">', $str);
  $str = eregi_replace("<[[:space:]]* img[[:space:]]*([^>]*)[[:space:]]*>", '', $str);
  $str = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?javascript[[:punct:]]*\"?[^>]*>", '', $str);
  return $str;
  exit;
  //$str = ereg_replace("<\?","",$str);
  //return $str;
}
/////////////////////////////////////////////////////////
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
function getip() { // Получаем IP-адрес
  if (getenv('REMOTE_ADDR')) $user_ip = getenv('REMOTE_ADDR');
  elseif (getenv('HTTP_FORWARDED_FOR')) $user_ip = getenv('HTTP_FORWARDED_FOR');
  elseif (getenv('HTTP_X_FORWARDED_FOR')) $user_ip = getenv('HTTP_X_FORWARDED_FOR');
  elseif (getenv('HTTP_X_COMING_FROM')) $user_ip = getenv('HTTP_X_COMING_FROM');
  elseif (getenv('HTTP_VIA')) $user_ip = getenv('HTTP_VIA');
  elseif (getenv('HTTP_XROXY_CONNECTION')) $user_ip = getenv('HTTP_XROXY_CONNECTION');
  elseif (getenv('HTTP_CLIENT_IP')) $user_ip = getenv('HTTP_CLIENT_IP');
  else $user_ip='unknown';
  if (15<strlen($user_ip)) {
    $ar = explode (', ', $user_ip);
    $so=sizeof($ar)-1;
    for ($i=$so; $i>0; $i--) {
      if ($ar[$i]!='' and !preg_match ('/[a-zA-Zа-яА-Я]/', $ar[$i])) {
        $user_ip = $ar[$i];
        break;
      }
      if ($i==$so) $user_ip = 'unknown';
    }
  }
  if (preg_match ('/[^0-9\.]/', $user_ip)) $user_ip = 'unknown';
  return $user_ip;
}
/////////////////////////////////////////////////////////
function findMonthName($m) { // Функция определения имени месяца по его числу
  $m = intval($m);
  // english "Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sen,Oct,Nov,Dec");
  $month = explode(",", "?,".ss("января,февраля,марта,апреля,мая,июня,июля,августа,сентября,октября,ноября,декабря"));
  return $month[$m];
}
////////////////////////////////////////////////////////
function tipograf($text, $p=0) { // Типографика - все основные знаки препинания
  global $lang;
  if ($p==0) $text = "<p>".trim($text)."</p>";
  $zamena = array(
  " class=\"MsoNormalCxSpFirst\""=>"",
  " class=\"MsoNormalCxSpMiddle\""=>"",
  "MsoNormalTable"=>"table_light",
  " class=\"Apple-style-span\""=>"",
  "st1:metricconverter"=>"span",
  " w:st=\"on\""=>"",
  "&nbsp;"=>" ",
  " class=\"Apple-style-span\""=>"",
  " style=\"font-size: 11px; \""=>"",
  "<b> </b>"=>" ",
  "<i> </i>"=>" ",
  "<br></b>"=>"</b><br>",
  "<br></i>"=>"</i><br>",
  "class=\"\""=>"",
  "style=\"\""=>"",
  "height=\"\""=>"",
  "width=\"\""=>"",
  "</p></p>"=>"</p>",
  "<p> <p>"=>"<p>",
  "<p><p>"=>"<p>",
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
  "url ("=>"url(",
  "rgb ("=>"rgb(",
  " )"=>")",
  ")"=>") ",
  ") )"=>"))",
  "– "=>" — ",
  "- "=>" — ",
  " -
"=>" — ",
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
  "<p> <br><br> <p>"=>"<p>",
  "</em><br> <em>"=>"<br>",
  "</strong><br> <strong>"=>"<br>",
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
  if ($p!=2 && $lang=="ru") $text = predlogi($text);
  return $text;
}
///////////////////////////////////////////////////////////////
function strtolow($txt, $t=1) { # Конвертация регистра букв
  // t = 1 – все буквы маленькие
  // t = 0 – ВСЕ БУКВЫ БОЛЬШИЕ
  // t = 2 – Первые Буквы Большие
  //$from   = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЬЫЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
  //$to     = 'абвгдеёжзийклмнопрстуфхцчшщъьыэюяabcdefghijklmnopqrstuvwxyz';
  //if ($t==1) $txt = strtr($txt, $from, $to); 
  //elseif ($t==0) $txt = strtr($txt, $to, $from);
  $case = array(MB_CASE_UPPER,MB_CASE_LOWER,MB_CASE_TITLE);
  $txt = mb_convert_case($txt, $case[$t]);
  return $txt;
}
///////////////////////////////////////////////////////////////
function translit($cyr_str) { # Транслит
  $tr = array("Ґ"=>"G","Ё"=>"YO","Є"=>"E","Ї"=>"YI","І"=>"I","і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"#","є"=>"e",
   "ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I",
   "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
   "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH","Щ"=>"SCH","Ъ"=>"'","Ы"=>"Y","Ь"=>"",
   "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"'",
   "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya","«"=>"","»"=>"");
   return $str = iconv ( "UTF-8", "UTF-8//IGNORE", strtr ( $cyr_str, $tr ) );
}
///////////////////////////////////////////////////////////////
function translit_name($cyr_str) { # Транслит названий файлов
  $tr = array("Ґ"=>"G","Ё"=>"YO","Є"=>"E","Ї"=>"YI","І"=>"I","і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"","є"=>"e",
   "ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I",
   "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
   "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"Y","Ь"=>"",
   "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"",
   "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
   "«"=>"","»"=>"","."=>"",","=>"","!"=>"",":"=>"","("=>"",")"=>"","["=>"","]"=>"","{"=>"","}"=>"",";"=>"","?"=>""," "=>"_");
   return iconv ( "UTF-8", "UTF-8//IGNORE", strtr ( $cyr_str, $tr ) );
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
  //if ($lang != "ru-RU" && $lang != "ru") $eng = 1;
  if ($data_days == true) $r = 2;
  // "январь,февраль,март,апрель,май,июнь,июль,август,сентябрь,октябрь,ноябрь,декабрь"
  //if ($lang == 'ru') 
  $months = explode(",", "?,".ss("января,февраля,марта,апреля,мая,июня,июля,августа,сентября,октября,ноября,декабря"));
  //else $months = array("?", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
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
      if ($date_now == $datax1_1) $data = ss("сегодня");
      if ($date_now2 == $datax1_1) $data = ss("вчера");
      if ($date_now3 == $datax1_1) $data = ss("позавчера");
      if ($date_now4 == $datax1_1) $data = ss("завтра");
      if ($date_now5 == $datax1_1) $data = ss("послезавтра");
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
function my_calendar($fill='', $modul, $showdate='', $month='', $year='') { // Функция вывода календаря
  $calendar = "";
  if ($month=='' && $year=='') {
    $month = date('n'); // месяц
    $year = date('Y');
    $dayofmonth = date('t'); // Вычисляем число дней в текущем месяце
    $daynow = date('j'); 
  } else {
    if ($month=='') $month = date('n');
    if ($year=='') $year = date('Y');
    $dayofmonth = date('t', mktime(0, 0, 0, $month, 13, $year)); 
    $daynow = 0;
    if ($month == date('n') && $year == date('Y')) $daynow = date('j');
  }
  $monthandyear = $year."-".$month."-";
  // Счётчик для дней месяца
  $day_count = 1;
  // 1. Первая неделя
  $num = 0;
  for($i = 0; $i < 7; $i++) {
    // Вычисляем номер дня недели для числа
    $dayofweek = date('w', mktime(0, 0, 0, $month, $day_count, $year));
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
  
  $days = explode(",", ss("Пн,Вт,Ср,Чт,Пт,Сб,Вс"));
  // Выводим содержимое массива $week в виде календаря. Выводим таблицу
  $calendar .= "<div class='calendar'>";
  for($j = 0; $j < 7; $j++) {
    //$calendar .= "<tr align=center>";
    if($j == 5 || $j == 6) $class = " red"; else $class = " black";
    $calendar .= "<div class='calendar_cell".$class."'>".$days[$j]."</div>";
    for($i = 0; $i < count($week); $i++) {
      if(!empty($week[$i][$j])) {
        // Если имеем дело с субботой и воскресеньем — подсвечиваем их
        if($j == 5 || $j == 6) $class = " red"; else $class = " black";
        //if ($week[$i][$j]<10) $den = "0".$week[$i][$j]; else 
        $den = $week[$i][$j];
        if ($week[$i][$j] == $daynow) $class .= " bold";
        if ($monthandyear.$week[$i][$j] == $showdate) $class .= " select";
        // (intval($week[$i][$j]) < 10 ? '0'.intval($week[$i][$j]) : $week[$i][$j])
        if (in_array($monthandyear.$den,$fill)) $calendar .= "<div class='calendar_cell".$class."'><a href='date_".$monthandyear.$week[$i][$j]."'>".$week[$i][$j]."</a></div>";
        // (intval($week[$i][$j]) < 10 ? '0'.intval($week[$i][$j]) : $week[$i][$j])
        else $calendar .= "<div class='calendar_cell".$class."'>".$week[$i][$j]."</div>";
      } else $calendar .= "<div class='calendar_cell'></div>";
    }
    $calendar .= "<div class='clear'></div>";
  } 
  $calendar .= "</div>";
  $calendar .= "<p class='small red'>".ss("Сегодня: ").date2normal_view(date('Y-m-d'))."</p>";
  if (trim($showdate) != "" and $showdate != $monthandyear.$daynow) $calendar .= "<p class='small black'>".ss("Выбрано: ").date2normal_view($showdate)."</p>"; // trim($showdate) != "0-00-00" and 
  return $calendar;
}
////////////////////////////////////////////////////////////
function select($name,$vars,$vars_name,$znachenie,$add='',$class='left3') { // генерация SELECT элемента формы
  // $add - добавление, например id или onchange...
  if ( ($vars == "0,1" || $vars == "1,0") && ($vars_name == "ДА,НЕТ" || $vars_name == "НЕТ,ДА" || $vars_name == "НЕТ,ЕСТЬ")) {
    $add .= " class='hide'";
    $style1 = $style2 = "";
    if ($znachenie == "1") $style1 = "style='display:none;'"; 
    else $style2 = "style='display:none;'";
    $id = md5($name);
    $button = "<a title='".ss("Выключено")."' class='".$class." button red white small punkt' id=on_".$id." onclick='$(\"#".$id." [value=1]\").attr(\"selected\", \"selected\"); $(\"#on_".$id."\").hide().next().show();'".$style1."><span class=\"icon white small\" data-icon=\"Q\"></span></a><a title='".ss("Включено")."' class='".$class." button green small punkt' id=off_".$id." onclick='$(\"#".$id." [value=0]\").attr(\"selected\", \"selected\"); $(\"#off_".$id."\").hide().prev().show();'".$style2."><span class=\"icon white small\" data-icon=\"`\"></span></a>";
  } else { 
    $button = "";
    $id = $name;
    $add .= " class='w100'";
  }
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
    return "<textarea name='".$name."'".$add." rows=3 cols=80 class=w100 style='height:".$size."px;'>".$znachenie."</textarea>";
  else {
    if ($size == "100%") $size = " class=w100"; else $size = " size='".$size."'";
    return "<input type='".$type."' name='".$name."'".$add." value='".$znachenie."'".$size.">";
  }
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
  // type: open, main - text
  global $db, $prefix;
  $result8 = $db->sql_query("select `text` from ".$prefix."_mainpage where `type`='2' and (`name` = '".mysql_real_escape_string($module)."' or `name` like '".mysql_real_escape_string($module)." %')");
  $row8 = $db->sql_fetchrow($result8);
  $text2 = $row8['text'];
  if (strpos($text2,"tipograf=0") < 1) { // Типограф
    $text = tipograf($text, 1);
  }
  if (strpos($text2,"no_html_in_opentext=1") > 1 and $type=="open") strip_tags($text, '<a><img>'); // Удаление HTML из предисловия
  if (strpos($text2,"no_html_in_text=1") > 1 and $type=="main") strip_tags($text, '<a><img>'); // Удаление HTML из предисловия
  if (strpos($text2,"table_light=1") > 0 and $type=="main") // Добавление класса table_light
    $text = str_replace("<table", "<table class='table_light'", $text);
  return $text;
}
///////////////////////////////////////////////////////////////// проверить дубликат
function block_names() { // Функция для получения названий всех блоков (для дизайна и шаблонов) 
  global $db, $prefix;
  $blocks = "";
  $sql3 = "select `title` from ".$prefix."_mainpage where `type`='3'";
  $result3 = $db->sql_query($sql3);
  while ($row3 = $db->sql_fetchrow($result3)) {
    $title_modul = trim($row3['title']);
    $blocks .= "[".$title_modul."] ";
  }
  return $blocks;
}
///////////////////////////////////////////////////////////////
function recash($url, $main=1) { // Обновление кеша
  global $db, $prefix, $site_cash;
  $u = explode("page_",$url);
  if ($site_cash == "base") { // если кеш хранится в БД
    if ($main == 1) $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url`='/'");
    if (mb_strpos($url,"page_")) {
      $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url`='".mysql_real_escape_string($u[0])."'");
      $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url` LIKE 'cat_%'");
    }
    $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url`='".mysql_real_escape_string($url)."'"); 
  } elseif ($site_cash == "file") { // если кеш файловый
    if ($main == 1) 
      if (file_exists("cashe/index")) unlink("cashe/index");
  	if (mb_strpos($url,"page_")) {
      if (file_exists("cashe/".$u[0])) unlink("cashe/".$u[0]);
  	  $files = glob("cashe/cat_*");
      $c = count($files);
      if (count($files) > 0) {
        foreach ($files as $file) {
          if (file_exists($file)) unlink($file);
        }
      }
    }
    if ($url != "" && file_exists("cashe/".$url) && $url != "/") unlink("cashe/".$url);
  }
}
///////////////////////////////////////////////////////////////
function obrez($word) { // Функция обрезания окончаний
  global $lang;
  if ($lang != "ru") {
    return $word;
  } else {
    $result = ''; $make = 0;
      $closes = array('овая','овый','овое','ёвое','евое','ная','ной','ный','ные','ый','ые','ий','ой','ая','ов','ах','ав','ях','ое','ям','ом','ем','ей','ёй','ай','ец','а','е','и','о','у','ь','ы','ю','я'); //Окончания
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
}
///////////////////////////////////////////////////////////////
function getparent($name, $parentid, $title) { // получение родительской папки
  global $prefix, $db;
  $sql = "select `title`, `parent_id` from ".$prefix."_pages_categories where `module`='".mysql_real_escape_string($name)."' and `tables`='pages' and `cid`='".mysql_real_escape_string($parentid)."'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $ptitle = strip_tags($row['title'], '<b><i>');
  $pparentid = $row['parent_id'];
  if ($ptitle!="") $title = $ptitle."/".$title;
  if ($pparentid!=0) $title = getparent($name,$pparentid,$title);
  return $title;
}
///////////////////////////////////////////////////////////////
function getparent_spiski($name, $parent, $title) { // получение родительского списка
  global $tip, $admintip, $prefix,$db;
  if ($parent != 0) {
    $sql = "select `name`, `parent` from ".$prefix."_spiski where `type`='".mysql_real_escape_string($name)."' and `id`='".mysql_real_escape_string($parent)."'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $ptitle = $row['name'];
    $pparent = $row['parent'];
    if ($ptitle!="") $title = $ptitle."/".$title;
    if ($pparent!=0) $title = getparent_spiski($name, $pparent, $title);
    return $title;
  } else return $title;
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
      if ($x == 0) $subg = aa("Найден и обезврежен «.htaccess»-вирус."); 
      elseif ($x == 1) $subg = aa("Вирус теперь поражает и резервную копию .htaccess — пора обновить оба файла, взяв их из дистрибутива, а также в [Настройки -> Настройки Администрирования] поменять название файла с резервной копией .htaccess");
      system_mes($subg);
      if ($x == 0) antivirus("1");
    }
  }
}
///////////////////////////////////////////////////////////////
function system_mes($subg) { // Отправка системного сообщения администратору (в список «Комментарии»)
  global $prefix, $db, $now;
  $db->sql_query("INSERT INTO ".$prefix."_pages_comments ( `cid` , `num` , `avtor` , `mail` , `text` , `ip` , `data`, `drevo`, `adres`, `tel`, `active` ) VALUES ('', '0', '".aa("Системное сообщение")."', '', '".mysql_real_escape_string($subg)."', '', '".mysql_real_escape_string($now)."', '', '', '', '1')");
}
/////////////////////////////////////////////////////////////////
function vhodyagie($id,$par,$num) { // Функция подсчета входящих в подкатегории страниц
  $num_all = 0;
  foreach ($par as $i1 => $nam1) { // nam - это родственник
    if ($id == $nam1) {
      $x = vhodyagie($i1,$par,$num);
      if (!isset($num[$i1])) $num[$i1] = 0;
      $num_all = $num_all + $num[$i1] + $x;
    }
  }
  return $num_all;
}
/////////////////////////////////////////////////////////////////
function predlogi($text) { // Добавление к предлогам неразрывного пробела, для типографики страниц
  global $lang;
  if ($lang != "ru") {
    return $text;
  } else {
    /*
    $predlogi = array(" а","А"," в","В"," и","И"," к","К"," о","О"," у","У"," я","Я"," во","Во"," до","До"," за","За"," из","Из"," на","На"," не","Не"," ни","Ни"," но","Но"," об","Об"," то","То"," для","Для"," или","Или"," над","Над"," обо","Обо"," про","Про"," около","Около"," перед","Перед"," после","После"," против","Против"," напротив","Напротив");
    foreach ($predlogi as $value) {
      $text = str_replace($value." ", $value."&nbsp;",$text);
    }
    */
    $text = str_replace("кое-как","<nobr>кое-как</nobr>",$text);
    //$text = str_replace(" же ","&nbsp;же ",$text);
    $text = str_replace(" - "," &mdash; ",$text); // Тире и дефиз
    return $text;
  }
}
/////////////////////////////////////////////////////////////////
function time_otschet($tim, $txt, $do) { // JavaScript обратного отсчета времени
  return "<script> 
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
    document.getElementById(\"RemainsFullDays\").innerHTML=\"<b style='font-size:1.3em;'>\"+RemainsFullDays+\"</b><span id='Rem'> ".ss("дней")."</span>\"; 
    document.getElementById(\"RemainsFullHours\").innerHTML=\"<b>\"+RemainsFullHours+\"</b><span id='Rem'> ".ss("ч")."</span>\"; 
    document.getElementById(\"RemainsMinutes\").innerHTML=\"<b>\"+RemainsMinutes+\"</b><span id='Rem'> ".ss("м")."</span>\"; 
    document.getElementById(\"lastSec\").innerHTML=\"<b>\"+lastSec+\"</b><span id='Rem'> ".ss("с")."</span>\"; <!-- highslide start  -->
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
  <script>fulltime();</script>";
}
/////////////////////////////////////////////////////////////// 
function text_shablon() { // список шаблонов
    global $prefix, $db;
    $text_shablon = array(); 
    $sqlZ = "SELECT `id`,`text` from ".$prefix."_mainpage where `tables`='pages' and `type`='6'";
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
      if ($all==0) $and = " and `parent_id`='0'"; else $and = "";
      //$cid_module = array(); // список принадлежности папок к разделам
      $result = $db->sql_query("SELECT `cid`,`title` from ".$prefix."_pages_categories where `tables`='pages'".$and." order by `title`");
      while ($row = $db->sql_fetchrow($result)) {
        $id = $row['cid'];
        $titles_papka[$id] = $row['title'];
        //$cid_module[$id] = $row['module'];
      }
      return $titles_papka;
    } else {
      $result = $db->sql_query("SELECT `title` from ".$prefix."_pages_categories where `cid`='".mysql_real_escape_string($cid)."'");
      $row = $db->sql_fetchrow($result);
      $title_papka = $row['title'];
      return $title_papka;
    }
}
/////////////////////////////////////////////////////////////// 
function design_and_style($design) { // Определение дизайна
  global $prefix, $db;
  if (isset($design)) {
    $sql4 = "select `text`, `useit` from ".$prefix."_mainpage where `tables`='pages' and `id`='".mysql_real_escape_string($design)."' and type='0'";
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
  //return preg_match('/^[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}](?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}-]*[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}])?\.)+[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}](?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}-]*[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}])?$/u', trim($email));
  return (bool) preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\.\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\.\x21\x23-\x27x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $email);
}
/////////////////////////////////////////////////////////////// in_array без учета регистра
function in_arrayi($needle, $haystack) {
  return in_array(strtolower($needle), array_map('strtolower', $haystack));
}
///////////////////////////////////////////////////////////////
function site_redactor($nolink=false) {
  global $lang;
  if ($nolink == false) $add = ", 'video', 'link'"; else $add = "";
  return "<script src='ed2/redactor.js'></script>
<link rel='stylesheet' href='ed2/redactor.css' />
<script src='ed2/".$lang.".js'></script>
<script>$(function() { 
      $('.redactor').redactor({
          mobile: true, 
          observeImages: false, 
          convertDivs: true,
          imageUpload: 'ed2/image_upload.php',
          fileUpload: 'ed2/file_upload.php', 
          lang: '".$lang."', 
          buttons: ['bold', 'italic', 'deleted', '|', 'image', 'file'".$add."],
          autoresize: true,
          allowedTags: ['a', 'p', 'b', 'i', 'img', 'iframe', 'object', 'param'],
          minHeight: 300 }); } );
    </script>";
}
///////////////////////////////////////////////////////////////
function list_papka($title, $par, $num, $noli, $noli_razdelitel, $cid_module, $all_papka, $all_pages, $papki_numbers, $cid_level, $sort, $punkt_open, $punkt_check, $ccid, $ppid, $page_cid) {
  global $prefix;
  $links = array();
  foreach ($title as $id => $nam) {
    $list_papka = $list_pages = $and5 = $and4 = $and3 = $and2 = $and = "";
    if ($par[$id] == $cid_level) { // первый уровень
      if ($papki_numbers == 1) {
        $vhod = vhodyagie($id, $par, $num);
        if ($vhod > 0) $and2 = "<div class='add'>+".$vhod."</div>";
        if ( isset($num[$id]) ) $and = " (".$num[$id].$and2.")";
        elseif ($vhod > 0) $and = " (".$and2.")";
      }
      if ($noli == 0) {
        $and3 = "<li id='block_li_title_".$id."' class='block_li_title block_li_title_papka'>";
        $and4 = "</li>";
        $and5 = "ul";
      } else { $and4 = $and3 = ""; $and5 = "div"; }
      if ($all_papka == true) {
        $list_papka = list_papka($title, $par, $num, $noli, $noli_razdelitel, $cid_module, $all_papka, $all_pages, $papki_numbers, $id, $sort, $punkt_open, $punkt_check, $ccid, $ppid, $page_cid);
      }
      if ($all_pages == true && $id != '0') {
        $sql = "SELECT `pid`, `module`, `title` from ".$prefix."_pages where `tables`='pages' and `cid`='".$id."' and `active`='1' order by ".$sort;
        $list_pages = list_pages($sql, $noli, $noli_razdelitel, $punkt_check, $ppid);
      }
      if ($list_papka != "" || $list_pages!= "") {
        $hide = "";
        if ($punkt_open == 1) $hide = " class='hide'";
        if ($punkt_open == 2 && $cid_level != '0') $hide = " class='hide'";
        if ($punkt_open == 3) {
          if (isset($par[$ccid])) {
            if ($id != $par[$ccid]) $hide = " class='hide'";
            if ($id == $ccid) $hide = "";
          } elseif ($id != $page_cid) $hide = " class='hide'";
          if (isset($par[ ''.$par[$ccid].'' ]))
            if ($id == $par[ ''.$par[$ccid].'' ]) $hide = "";
        }
        $and4 = "<".$and5.$hide." id='papki_".$id."'>".$list_papka.$list_pages."</".$and5.">".$and4;
      }
      // выделяем папку
      $active = "";
      if (isset($par[$ccid])) {
        if ($punkt_check == 1 && ( $id == $par[$ccid] || $id == $ccid) ) $active = " punkt_active";
      } elseif ($punkt_check == 1 && $id == $page_cid) $active = " punkt_active";
      if (isset($par[ ''.$par[$ccid].'' ]))
        if ($punkt_check == 1 && $id == $par[ ''.$par[$ccid].'' ]) $active = " punkt_active";

      if ( ($noli == 0 && $and4 != "</li>") || ($noli == 1 && $and4 != "") ) {
        $link1 = "<span onclick='$(\"#papki_".$id."\").toggle();' class='spoiler_link".$active."'>";
        $link2 = "</span>";
      } elseif ( $punkt_check == 1 && $id == $ccid ) {
        $link1 = "<span class='punkt_active_main".$active."'>";
        $link2 = "</span>";
      } else {
        $link1 = "<a id='papki_".$id."' href='".re_link("-".$cid_module[$id]."_cat_".$id)."' class='punkt_link".$active."'>";
        $link2 = "</a>";
      }
      
      $links[] = $and3.$link1.$nam.$link2.$and.$and4."";
    }
  }
  if ($noli != 0) $razdelitel = $noli_razdelitel; 
  else $razdelitel = "";
  $links = implode($razdelitel, $links);
  return $links;
}
/////////////////////////////////////////////////////////////// вывод страниц
function list_pages($sql, $noli, $noli_razdelitel, $punkt_check, $ppid) {
  $pagesshow = array();
  global $db;
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $pid_id = $row['pid'];
    $pid_module = $row['module'];
    $pid_title = $row['title'];
    if ($noli == 0) $and3 = "<li id='block_li_title_".$pid_id."' class='block_li_title block_li_title_pages'>";
    if ($punkt_check == 1 && $ppid == $pid_id) {
      $pagesshow[] = $and3."<span class='punkt_link punkt_active' href='".re_link("-".$pid_module."_page_".$pid_id)."'>".$pid_title."</span>";
    } else {
      $pagesshow[] = $and3."<a id='pages_".$pid_id."' class='punkt_link' href='".re_link("-".$pid_module."_page_".$pid_id)."'>".$pid_title."</a>";
    }
  }
  if ($noli != 0) $razdelitel = $noli_razdelitel; 
  else $razdelitel = "";
  $pagesshow = implode($razdelitel, $pagesshow);
  return $pagesshow;
}
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
?>