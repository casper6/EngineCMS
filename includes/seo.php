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
  $text2 = normaltext($text,0); // полная функция для ключевиков
$text = stopslov($text2);
$text = sklonenie($text); // Удаление склонений
  # Получаем массив слов
 $text = explode(" ", trim($text));
 $size1=count($text);
    $text = delslov($text,4);  // удаляем слова в 3 и менее символа
	$data='';
    foreach($text as $key=>$word) $data.=' '.strtolower($word);
    $text=explode(' ', trim($data)); $size=count($text);
    $arr1=array(); $arr2=array(); $arr3=array();
    ### Строим массив слов отсортированный по частоте вложений в тексте
    for($i=0; $i<$size; $i++) {
        $word=$text[$i];
        if($arr1[$word])$arr1[$word]++; else $arr1[$word]=1;
    }
    arsort($arr1);
    $data=array(); 
    foreach($arr1 as $word=>$count) {
        $data[$word]=$count;
    }
    arsort($data); reset($data); $text='';
    ### Возвращаем полученный результат
foreach($data as $word=>$count) {
$raz = mb_substr_count($text2, trim($word), "UTF-8");
$rf += $raz*100/$size1;
	$text.=', '.$word;
    if($rf > $kol + 1) break;
	++$rf;
	}
	return trim(substr($text, 1));
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
  $text= mb_convert_case($text, MB_CASE_LOWER, "UTF-8"); // все в нижний регистр
  $text = preg_replace( "'<script[^>]*>.*?</script>'usi", ' ', $text );  
  $text = preg_replace( "'<noindex[^>]*>.*?</noindex>'usi", ' ', $text ); // убераем noindex     
  $text = preg_replace( '/<!--.+?-->/u', '', $text );
  $text = preg_replace( '/{.+?}/u', '', $text );
  $text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/uis', '\2 (\1)', $text );
  $text = preg_replace('/<[^>]*>/u', ' ', $text);
  // email 
  $regex = '/(([_A-Za-z0-9-]+)(\\.[_A-Za-z0-9-]+)*@([A-Za-z0-9-]+)(\\.[A-Za-z0-9-]+)*)/iex';
  $text = preg_replace($regex, ' ', $text);
  $regex = "~<noscript[^>]*?>.*?</noscript>~usi";
  $text = preg_replace($regex, ' ', $text); // убираем ноуиндекс
  $regex = "~<table[^>]*?>.*?</table>~usi";
  $text = preg_replace($regex, ' ', $text);
  if ($a !== 1) { // для description не нужно
    // убираем ненужные знаки
    $text = str_replace(array("&nbsp;", "\r\n", "\r", "\n", "\t", "-", ",", ".", "&", "%", "?", "/", "(", ")", ";", ":", "`", "_", "'", "=", "+", "*", "^", "#", "!", "]", "[", "\""), " ", $text);
  }
  return trim($text);
}

function stopslov($text) { // Удаление стоп слов
  $name = 'common-words.txt'; // СВОЛОТА в ASCII а в общем хз
  // поэтому пишем жестко
     $data = file_get_contents($name);;
	$data2 = explode("|", trim($data));
    foreach ($data2 as $value) {
      $text = str_replace(" ".trim($value)." ", ' ', $text);
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
  $arr2 = explode(" ", trim($text)); $arr2 = array_unique($arr2);
  foreach($arr2 as $s11) { 
  // $s1 Наше слово по которому будем искать склонение для нормального слова (пример - стол)
   $s1 = trim($s11);
   if(mb_strlen($s1, "UTF-8") > 3) {
   $s4 = " ".mb_substr($s1, 0, -2, "UTF-8"); // удаляем последний символ из слова если требуется для склонения (пример - макулатура , рубль)
   $zamena2 = array($s4."ев",$s4."ов",$s4."ье",$s4."иями",$s4."ями",$s4."ами",$s4."еи",$s4."ии",$s4."ией",$s4."ей",$s4."ой",$s4."ий",$s4."иям",$s4."ям",$s4."ием",$s4."ем",
$s4."ам",$s4."ом",$s4."ах",$s4."иях",$s4."ях",$s4."ию",$s4."ью",$s4."ия",$s4."ья",$s4."ок", $s4."мва", $s4."яна", $s4."ровать",$s4."ег",$s4."ги",$s4."га",$s4."сть",$s4."сти");
  $text = str_replace($zamena2 , " ".$s1." ", $text);
     $s2 = " ".$s1." ";
   $zamena = array($s2."ов ", $s2."а", $s2."у", $s2."ом", $s2."е", $s2."ы", $s2."и" ); // Замена для простого как СТОЛ и СТУЛ 
   $text = str_replace($zamena , " ".$s1." ", $text);
      $s3 = " ".mb_substr($s1, 0, -1, "UTF-8");  // удаляем последний символ из слова если требуется для склонения (пример - макулатура , рубль)
   $zamena1 = array($s3."ев",$s3."ов",$s3."ье",$s3."иями",$s3."ями",$s3."ами",$s3."еи",$s3."ии",$s3."и",$s3."ией",$s3."ей",$s3."ой",$s3."ий",$s3."й",$s3."иям",$s3."ям",$s3."ием",$s3."ем",
$s3."ам",$s3."ом",$s3."о",$s3."у",$s3."ах",$s3."иях",$s3."ях",$s3."ы",$s3."ию",$s3."ью",$s3."ю",$s3."ия",$s3."ья",$s3."я",$s3."ок", $s3."мва", $s3."яна", $s3."ровать",$s3."ег",$s3."ги",$s3."га",$s3."сть",$s3."сти");
$text = str_replace($zamena1 , " ".$s1." ", $text);
}}
  return $text;
}
function razmetka($text, $keys) { //Функция поика ключевых слов в тексте и бозначения их жирным шрифтом
  $text = str_replace(array("<b>", "</b>"), "", $text);
  $key = explode(",", trim($keys));
  foreach($key as $s1) {
   $zamena = trim($s1);
    $na1 = "<b>".$s1."</b>";
   $text = str_replace($zamena, $na1, $text);
   // также найдем склонения ключевых слов и тоже обозначим их жирным
    $s3 = " ".$s1;
   $zamena = array($s3."ов", $s3."а", $s3."у", $s3."ом", $s3."е", $s3."ы", $s3."и" ); // Замена для простого как СТОЛ и СТУЛ
   $na2 = array("<b>".$s3."ов</b>", "<b>".$s3."а</b>", "<b>".$s3."у</b>", "<b>".$s3."ом</b>", "<b>".$s3."е</b>", "<b>".$s3."ы</b>", "<b>".$s3."и</b>" );   
   $text = str_replace($zamena , $na2, $text);
   $s2 = " ".mb_substr($s1, 0, -1, "UTF-8"); // удаляем последний символ из слова если требуется для склонения (пример - макулатура , рубль)
   $zamena1 = array($s2."ей", $s2."я", $s2."и", $s2."ем", $s2."ём", $s2."ы", $s2."у", $s2."ой", $s2."е", $s2."ю" );
   $na3 = array("<b>".$s2."ей</b>", "<b>".$s2."я</b>", "<b>".$s2."и</b>", "<b>".$s2."ем</b>", "<b>".$s2."ём</b>", "<b>".$s2."ы</b>", "<b>".$s2."у</b>", "<b>".$s2."ой</b>", "<b>".$s2."е</b>", "<b>".$s2."ю</b>" );
   $text = str_replace($zamena1 , $na3, $text);
      $s4 = " ".mb_substr($s1, 0, -2, "UTF-8"); // ищем земляной, земляные заменяем на земляная
   $zamena2 = array($s4."ие",$s4."ые",$s4."ое",$s4."ими",$s4."ыми",$s4."ей",$s4."ий",$s4."ый",$s4."ой",$s4."ем",$s4."им",$s4."ым",$s4."ом",
$s4."его",$s4."ого",$s4."ему",$s4."ому",$s4."их",$s4."ых",$s4."ую",$s4."юю",$s4."ая",$s4."яя",$s4."ою",$s4."ею");
$na4 = array("<b>".$s4."ие</b>", "<b>".$s4."ые</b>", "<b>".$s4."ое</b>", "<b>".$s4."ими</b>", "<b>".$s4."ыми</b>", "<b>".$s4."ей</b>", "<b>".$s4."ий</b>", "<b>".$s4."ый</b>", "<b>".$s4."ой</b>", "<b>".$s4."ем</b>", "<b>".$s4."им</b>", "<b>".$s4."ым</b>", "<b>".$s4."ом</b>", "<b>".$s4."его</b>", "<b>".$s4."ого</b>", "<b>".$s4."ему</b>", "<b>".$s4."ому</b>", "<b>".$s4."их</b>", "<b>".$s4."ых</b>", "<b>".$s4."ую</b>", "<b>".$s4."юю</b>", "<b>".$s4."ая</b>", "<b>".$s4."яя</b>", "<b>".$s4."ою</b>", "<b>".$s4."ею</b>");
   $text = str_replace($zamena2 , $na4, $text);
   }
   $zamen = array("<b></b>", "<b> </b>", "</b></b>", "<b><b>");
   $text = str_replace($zamen, " ", $text);
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
  $text2 = normaltext($text,0); // полная функция для ключевиков
$text = stopslov($text2);
$text = sklonenie($text); // Удаление склонений

  # Получаем массив слов
  $textm = explode(" ", trim($text));
  $size=count($textm);
  $keys = explode(",", trim($keys));
  $textm = delslov($textm,4);
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
  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
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
