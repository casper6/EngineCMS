<?php
// при обращении к функции newkey вида newkey($texttags,2);
// получим полный список ключевиков с фразами в 2 и 3 слова - можно будет использовать для поиска позиций в поисковиках
mb_http_input('UTF-8'); 
mb_http_output('UTF-8'); 
mb_internal_encoding("UTF-8");
$metod = $_POST['metod'];
global $razmetka;
if ($metod == "newkey") {
  $kol = $_POST['kol'];
  $x = $_POST['x']; 
  echo newkey($x,$kol);
}
if ($metod == "des") {
  $kol = $_POST['kol'];
  $x = $_POST['x']; 
  $key = $_POST['key'];
  echo newdesc($x,$key,$kol);
}
if ($metod == "open_text") {
  $x = $_POST['x']; 
  $key = $_POST['key'];
  echo razmetka($x,$key);
}
if ($metod == "main_text") {
  $y = $_POST['y']; 
  $key = $_POST['key'];
  global $razmetka;
  $razmetka = razmetka($y,$key);
  echo $razmetka;
}
if ($metod == "procent") {
  $y = $_POST['x']; 
  $key = $_POST['key'];
  echo procent($y,$key);
}
if ($metod == "wordstat") {
  $text = $_POST['x']; 
  $key = stopslov($_POST['key']);
  $geo = $_POST['geo'];
  $keys = explode(",", trim($key));
  //$text = razmetka($text,$key);
  //preg_match_all('/<b>(.*?)<\/b>/i',$text, $zapros);
  $rezult = array();
  echo "<table width=100%><tr valign=top>";
  foreach ($keys as $slovo) {
  $slovo = trim(strip_tags($slovo));
    if (mb_substr_count($text, ' ') > 0) $arr = yapars($slovo, $geo);
    echo "<td><h3>".$slovo."</h3>";
    foreach ( $arr[1] as $k=>$v ) {
      // слово и кол-во запросов
      echo str_replace('+', '', html_entity_decode($v, ENT_NOQUOTES,'UTF-8')).' - '.$arr[2][$k].'<br>';
    }
    echo "</td>";
  }
  echo "</tr></table>";
}

function newkey($text,$kol) {
  # Чистим текст
  $text = normaltext($text,0); // полная функция для ключевиков
  # убираем стоп слова
  $text = stopslov($text);
  $text = sklonenie($text); // Удаление склонений
  # Получаем массив слов
  $text = explode(" ", trim($text));
  $text = delslov($text,4);  // удаляем слова в 3 и менее символа
  $data='';
    foreach($text as $key=>$word) $data.=' '.strtolower($word);
    $text=explode(' ', trim($data)); $size=count($text);
    $arr1=array(); $arr2=array(); $arr3=array();

    ### Строим массив слов отсортированный по частоте вложений в тексте
    for($i=0; $i<$size; $i++) {
        $word=$text[$i];
        if($arr1[$word])$arr1[$word]++; 
        else $arr1[$word]=1;
    }
    arsort($arr1);
    ### Строим массив фраз состоящих из двух слов отсортированный по частоте вложений в тексте
  if ($a == 2) {
    for($i=0; $i<$size-1; $i++) {
      $word=$text[$i].' '.$text[$i+1];
      if($arr2[$word])$arr2[$word]++; 
      else $arr2[$word]=1; 
    }
    arsort($arr2);
    ### Строим массив фраз состоящих из трех слов отсортированный по частоте вложений в тексте
    for($i=0; $i<$size-2; $i++) {
      $word=$text[$i].' '.$text[$i+1].' '.$text[$i+2];
      if($arr3[$word])$arr3[$word]++; 
      else $arr3[$word]=1;
    }
    arsort($arr3);
  }
    ### Выбираем $n первых слов с максимальной частотой вхожений
  $n = $kol-1;
  $data=array(); $i=0;
  foreach($arr1 as $word=>$count) {
    $data[$word]=$count;
    if($i++==$n)break;
  }
  arsort($data); $text='';

  ### Переводим массив фраз в текст, опять таки с учетом частот вложений
  foreach($data as $word=>$count) $text.=', '.$word; $text=substr($text, 1);
  ### Возвращаем полученный результат
  return trim($text);
}

function newdesc($text,$key,$kol) {
  $text = normaltext($text,1);  // краткая функция для описания
  # убираем стоп слова
  $text = stopslov($text); 
  $data2 = explode(".", trim($text)); // Предложения в массив
  $datai = explode(",", trim($key)); // Ключевики в массив
  $desc=array();
  foreach($data2 as $arr1) { // Выполняем поиск предложения с максимумом ключевиков
    $i = 0;
    foreach($datai as $arr2) {
      $pos = strpos($arr1, $arr2);              
      if ($pos !== FALSE) $i++;    
    }
    if (strlen(trim($arr1)) < $kol) $desc[$i] = trim($arr1); // новый массив предложений, предложение и сколько содержит ключевиков
  }
  krsort($desc); // сортируем
  foreach($desc as $key => $val) {
    $newdesk = $val; // берем только с максимальным вхождением ключевиков
    break 1;
  }
  return $newdesk;
}

function normaltext($text, $a) { // Нормализация текста - убираем все лишнее
  $text= mb_strtolower($text); // все в нижний регистр
  $text = preg_replace( "'<script[^>]*>.*?</script>'usi", '', $text );  
  $text = preg_replace( "'<noindex[^>]*>.*?</noindex>'usi", '', $text ); // убераем noindex     
  $text = preg_replace( '/<!--.+?-->/u', '', $text );
  $text = preg_replace( '/{.+?}/u', '', $text );
  $text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/uis', '\2 (\1)', $text );
  $text = preg_replace('/<[^>]*>/u', ' ', $text);
  // email 
  $regex = '/(([_A-Za-z0-9-]+)(\\.[_A-Za-z0-9-]+)*@([A-Za-z0-9-]+)(\\.[A-Za-z0-9-]+)*)/iex';
  $text = preg_replace($regex, '', $text);
  $regex = "~<noscript[^>]*?>.*?</noscript>~usi";
  $text = preg_replace($regex, ' ', $text); // убираем ноуиндекс
  $regex = "~<table[^>]*?>.*?</table>~usi";
  $text = preg_replace($regex, ' ', $text);
  if ($a !== 1) { // для description не нужно
    // убираем ненужные знаки
    $text = str_replace(array("&nbsp;", "\r\n", "\r", "\n", "\t", "-", ",", ".", "&", "%", "?", "/", "(", ")", ";", ":", "`", "_", "'", "=", "+", "*", "^", "#", "!", "]", "["), " ", $text);
  }
  return trim($text);
}

function stopslov($text) { // Удаление стоп слов
  $name = 'common-words.txt';
  if (file_exists($name)) {
    $data = file($name);
    foreach ($data as $value) {
      $text = str_replace(trim($value), '', $text);
    }
  }
  return $text;
}
  
function delslov($array,$n) { // Удаление слов до 4 символов в массиве
  for($i = 0, $c = count($array); $i < $c; $i++) {
    if(mb_strlen($array[$i]) < $n) unset($array[$i]);
  }
  return $array;
}
 
function sklonenie($text) { // Поиск склонений слов
  $arr2 = explode(" ", trim($text));
  $arr = delslov($arr2,4);
  foreach($arr as $s11) { 
  // $s1 Наше слово по которому будем искать склонение для нормального слова (пример - стол)
   $s1 = trim($s11);
   if (utf8_substr($s1, -1) == "а" || utf8_substr($s1, -1) == "ь") {
   $s2 = preg_replace("/(.*).$/ui", "\\1", $s1); // удаляем последний символ из слова если требуется для склонения (пример - макулатура , рубль)
   $zamena1 = array("/\b".$s2."ей\b/ui", "/\b".$s2."я\b/ui", "/\b".$s2."и\b/ui", "/\b".$s2."ем\b/ui", "/\b".$s2."ём\b/ui", "/\b".$s2."ы\b/ui", "/\b".$s2."у\b/ui", "/\b".$s2."ой\b/ui", "/\b".$s2."е\b/ui", "/\b".$s2."ю/u" );
   $text = preg_replace($zamena1 , $s1, $text);
   }
  elseif (utf8_substr($s1, -2) == "ая" || utf8_substr($s1, -2) == "ые"){
   $s3 = preg_replace("/(.*).$\b/ui", "\\2", $s1); // удаляем последних 2 символа из слова (пример - земляная , земляной, земляные)
   $zamena2 = array("/\b".$s3."ые\b/ui","/\b".$s3."ая\b/ui","/\b".$s3."ую\b/ui", "/\b".$s3."ой\b/ui", "/\b".$s3."ий\b/ui", "/\b".$s3."ым\b/ui", "/\b".$s3."ых\b/ui" );
   $text = preg_replace($zamena2 , $s1, $text);
   } else {
   $zamena = array("/\b".$s1."ов\b/ui", "/\b".$s1."а\b/ui", "/\b".$s1."у\b/ui", "/\b".$s1."ом\b/ui", "/\b".$s1."е\b/ui", "/\b".$s1."ы\b/ui", "/\b".$s1."и\b/ui" ); // Замена для простого как СТОЛ и СТУЛ 
   $text = preg_replace($zamena , $s1, $text);
   }
  }
  return $text;
}

function razmetka($text, $keys) { //Функция поика ключевых слов в тексте и бозначения их жирным шрифтом
  $text = str_replace(array("<b>", "</b>"), "", $text);
  $key = explode(",", trim($keys));
  foreach($key as $s1) {
   $zamena = "/\b".trim($s1)."\b/ui";
    $na = "<b>".$s1."</b>";
   $text = preg_replace($zamena, $na, $text);
   // также найдем склонения ключевых слов и тоже обозначим их жирным
   if (utf8_substr($s1, -1) == "а" || utf8_substr($s1, -1) == "ь") {
   $s2 = preg_replace("/(.*).$/ui", "\\1", $s1); // удаляем последний символ из слова если требуется для склонения (пример - макулатура , рубль)
   $zamena1 = array("/\b".$s2."ей\b/ui", "/\b".$s2."я\b/ui", "/\b".$s2."и\b/ui", "/\b".$s2."ем\b/ui", "/\b".$s2."ём\b/ui", "/\b".$s2."ы\b/ui", "/\b".$s2."у\b/ui", "/\b".$s2."ой\b/ui", "/\b".$s2."е\b/ui", "/\b".$s2."ю\b/ui" );
   $na = array("<b>".$s2."ей</b>", "<b>".$s2."я</b>", "<b>".$s2."и</b>", "<b>".$s2."ем</b>", "<b>".$s2."ём</b>", "<b>".$s2."ы</b>", "<b>".$s2."у</b>", "<b>".$s2."ой</b>", "<b>".$s2."е</b>", "<b>".$s2."ю</b>" );
   $text = preg_replace($zamena1, $na, $text);
   }
  elseif (utf8_substr($s1, -2) == "ая" || utf8_substr($s1, -2) == "ые"){
   $s3 = preg_replace("/(.*).$/ui", "\\2", $s1); // удаляем последних 2 символа из слова (пример - земляная , земляной, земляные)
   $zamena2 = array("/\b".$s3."ая\b/ui","/\b".$s3."ую\b/ui", "/\b".$s3."ой\b/ui", "/\b".$s3."ий\b/ui", "/\b".$s3."ым\b/ui", "/\b".$s3."ых\b/ui" );
   $na = array("<b>".$s3."ая</b>","<b>".$s3."ую</b>", "<b>".$s3."ой</b>", "<b>".$s3."ий</b>", "<b>".$s3."ым</b>", "<b>".$s3."ых</b>" );
   $text = preg_replace($zamena2 , $na, $text);
   } else {
   $zamena1 = array("/\b".$s1."ов\b/ui", "/\b".$s1."а\b/ui", "/\b".$s1."у\b/ui", "/\b".$s1."ом\b/ui", "/\b".$s1."е\b/ui", "/\b".$s1."ы\b/ui", "/\b".$s1."и\b/ui" );
   $na = array("<b>".$s1."ов</b>", "<b>".$s1."а</b>", "<b>".$s1."у</b>", "<b>".$s1."ом</b>", "<b>".$s1."е</b>", "<b>".$s1."ы</b>", "<b>".$s1."и</b>" ); // Замена для простого как СТОЛ и СТУЛ 
   $text = preg_replace($zamena1, $na, $text);
   }
   }
   $text = str_replace("</b> <b>", " ", $text);
  return  $text; 
}

function utf8_substr($str,$start) { // Функция substr для работы в utf-8
   preg_match_all("/./su", $str, $ar);
   if(func_num_args() >= 3) {
       $end = func_get_arg(2);
       return join("",array_slice($ar[0],$start,$end));
   } else {
       return join("",array_slice($ar[0],$start));
   }
}

function procent($text,$keys) {
  # Чистим текст
  $text = stopslov($text);
  $text = normaltext($text,0); // полная функция для ключевиков
  $text = sklonenie($text); // Удаление склонений
  # Получаем массив слов
  $textm = explode(" ", trim($text));
  $size=count($textm);
  $keys = explode(",", trim($keys));
  $result = 'Слово с наибольшим весом является самым главным в тексте, 
  чтобы повысить вес слова – употребите его чаще или поставьте ближе к началу текста.<br>';
  $result .= '<table class="table_light" width=600>';
  $result .= '<tr><td>Словосочетание</td><td align=center>Упоминания</td><td align=center>% в тексте</td><td align=center>Вес</td>';
    krsort($textm);
    $i= 1;
    $ves = array();
      foreach($textm as $key=>$word) { 
     $ves[$word]+= $i;  
      ++$i;
      }
      foreach($keys as $slovo) {
    $raz = mb_substr_count($text, trim($slovo));
    $result .= '<tr><td>'.trim($slovo).'</td><td align=center>'.$raz.'</td><td align=center>'.round($raz*100/$size,2).'</td><td align=center>'.$ves[trim($slovo)].'</td></tr>';
    $summ+=$raz;
      }
  $proc = round($summ*100/$size,2);
  $result .= '</table><br>Всего в тексте ключевых слов - '.$summ.'<br>Их процент - '.$proc;
  if ( $proc < 5 ) { $result .= '<br>Недостаточно ключевых слов в тексте.'; }
  elseif ( $proc > 8 ) { $result .= '<br>Много ключевых слов в тексте.'; }
  else { $result .= '<br>Оптимальное количество ключевых слов.'; }
  return  $result;
}

function yapars($word, $geo) {
  /* Параметры */
  $fuid01 = '5092466b0cbab62d.jJgD6rQ2fWFcESmYT9oT82-ExFT4NaO7vw8H86HaqzHrhrHEdXNSr8DA2RI2rDYEP4N120pWif5GgR3hKu1WywHo_7plw1WyTzAYGkDRzDILyuWXBgIVE8KIWS3Cp9eO';
  $params = array(
    'cmd' => 'words',
    'page' => 1,
    't' => $word,
    'geo' => $geo, // 39 - Москва и область
  );
//'http://wordstat.yandex.ru/?cmd=words&page=1&t=%D0%BF%D0%BE%D0%BC%D0%B8%D0%B4%D0%BE%D1%80%D1%8B&geo=&text_geo='
  /* Запрос к wordstat Яндекс */
  $uri = 'http://wordstat.yandex.ru/?'.http_build_query($params);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $uri);
  curl_setopt($ch, CURLOPT_HEADER,0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_REFERER, 'http://wordstat.yandex.ru/');
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)");
  curl_setopt($ch, CURLOPT_COOKIE, 'fuid01='.$fuid01);
  $contents = curl_exec($ch);
  curl_close($ch);

  /* Парсинг данных и их вывод на экран */
  if ( preg_match('/<table border="0" cellpadding="5" cellspacing="0" width="100%">(.*)<\/table>/isU', $contents, $table) ) {
    if ( preg_match_all('/<tr class="tlist" bgcolor=".*">\s*?<td>\s*?<a href=".*">(.*)<\/a>\s*?<\/td>\s*?<td><div style="width: 10px"><\/div> <\/td>\s*?<td class="align-right-td">(.*)<\/td>\s*?<\/tr>/isU', $table[1], $m) ) {
      return $m;
      }
    }
  }
?>
