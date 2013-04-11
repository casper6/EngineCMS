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
  $kolslov = $_POST['kolslov'];
  $x = $_POST['x']; 
  echo newkey($x,$kol,$kolslov);
}
if ($metod == "des") {
  $kol = $_POST['kol'];
  $x = $_POST['x']; 
  $key = $_POST['key'];
  echo trim(newdesc($x,$key,$kol));
}
if ($metod == "procent") {
  $y = $_POST['x']; 
  $sin = $_POST['sin'];
  $key = $_POST['key'];
  echo procent($y,$key,$sin);
}
if ($metod == "wordstat") {
  $text = $_POST['x']; 
  $key = stopslov($_POST['key']);
  $geo = $_POST['geo'];
  $keys = explode(",", trim($key));
  $count=count($keys);
  $rezult = array();
  echo "<table width=100%><tr valign=top>";
  $arr = array();
  $arr = yapars($keys, $geo);
  for($i=0;$i<$count;$i++) {
  $slovo = trim($keys[$i]);
    echo "<td><b>".$slovo."</b>";
	$ch = 0;
	echo '<table class="table_light" width=100%>';
    foreach ( $arr[$i][1] as $k=>$v ) {
      // слово и кол-во запросов
	  if($ch++ % 2) { echo '<tr><td style="color:gray">'.str_replace('+', '', html_entity_decode($v, ENT_NOQUOTES,'UTF-8')).'</td><td style="color:gray">'.$arr[$i][2][$k].'</td></tr>';  } else
      echo '<tr><td>'.str_replace('+', '', html_entity_decode($v, ENT_NOQUOTES,'UTF-8')).'</td><td>'.$arr[$i][2][$k].'</td></tr>';
    }
	echo "</table>";
    echo "</td>";
  }
  echo "</tr></table>";
}

function newkey($text,$kol,$kolslov) {
  # Чистим текст
  $text = normaltext($text,0); // полная функция для ключевиков
$textorig = stopslov($text);
$textorig =  preg_replace('/ {2,}/',' ',$textorig);
$text = explode(' ', $textorig);
$text = delslov($text,4);
$text2 =$text;
sort($text2);
$count=count($text);
$arr = array(); $arr2 = array(); $arr3 = array(); $arr4 = array(); $arr5 = array(); $text_koren = '';
for($i=0;$i<$count;$i++) {
$koren = sklon(trim($text2[$i])); // находим корень слова
$koren1 = sklon(trim($text[$i]));
$koren2 = sklon(trim($text[$i+1]));
if (mb_substr_count($textorig, trim($koren)) > 1) { // берем слова имеющие более 1-го вхождения
$arr[$koren]= mb_substr_count($textorig, trim($koren)); // корень и сколько вхождений в тексте он имеет
$arr2[$koren]= $text2[$i]; // корень и найденное слово по нему
$arr3[$koren]= $i; // Массив с корнями
}
if ($koren2 == true){
$arr4[1][$i] = $text[$i]." ".$text[$i+1]; // массив с фразами в 2 слова
$arr4[2][$i] = $koren1." ".$koren2; // и их корни
}
$text_koren .= $koren1." "; // и строим тот-же текст но вместо слов их корни
}
$arrhy = array_flip($arr4[2]);
$arr4[2] = array_flip($arrhy);
$arrres = array();
for($i=0;$i<$count;$i++) {
if (mb_substr_count($text_koren, $arr4[2][$i]) > 1) { 
if (strpos(trim($arr4[1][$i]), ' ') > 0) 
$arrres[$arr4[1][$i]] = mb_substr_count($text_koren, $arr4[2][$i]); // наши словосочетаниея имеющие больше 1-го вхождения (словосочетание - вхождений)
}
}
arsort($arrres); // сортируем словосочетания по вхождениям
$arrres2 = array_keys($arrres); // переводим в массив словосочетаний (номер - словосочетание)
$countres2 = count($arrres2);
$y =1; // этот счетчик будет работать в двух циклах
$str = '';  // начинаем строить строку с ключами (берем словосочетания)
for($i=0;$i<$countres2;$i++) {
if($y > $kolslov)break;
$str .= " ".$arrres2[$i].","; // обязательно пробел в начале или strpos не сработает потом
++$y;
}
$arr3 = array_flip($arr3); sort($arr3); reset($arr3);$count2=count($arr3);
$vhod1 = array();
// в итоге получаем
for($i=0;$i<$count2;$i++) { if ( $arr[$arr3[$i]] > 1) $vhod1[$arr2[$arr3[$i]]] = $arr[$arr3[$i]]; } // слово и сколько вхождений в тексте
arsort($vhod1); // сортируем по вхождениям
$str2 = ''; // продолжаем строить строку с ключами
      foreach(array_keys ($vhod1) as $word=>$key) { 
	  $a = sklon($key);
	  if (strpos($str,$a) < 1) { // удаляем слово если оно есть в словосочетании
	  if($y > $kol)break;
      $str2 .= " ".$key.","; // берем слова которые не вошли в словосочетания
	  ++$y;
	  }
      }
	  if (mb_strlen($str2,"UTF-8") > 1) $keys = trim($str.mb_substr($str2, 0, -1, "UTF-8")); // обьединяем словосочетания и слова
	  else $keys = trim(mb_substr($str, 0, -1, "UTF-8")); // только словосочетания
	  return $keys;
}
function newdesc($text,$key,$kol) {
  $text = normaltext($text,1);  // краткая функция для описания
  # убираем стоп слова
  //$text = stopslov($text); 
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
    $text = str_replace(array("&nbsp;", "\r\n", "\r", "\n", "\t", "-", "—", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", ",", ".", "&", "%", "?", "/", "(", ")", ";", ":", "`", "_", "'", "=", "+", "*", "^", "#", "!", "]", "[", "\""), " ", $text);
  }
  return trim($text);
}
function stopslov($text) { // Удаление стоп слов
  $name = 'common-words.txt';
  if (file_exists($name)) {
    $data = file($name);
    foreach ($data as $value) {
      $text = str_replace(' '.trim($value).' ', ' ', $text);
    }
  }
  return $text;
}
function delslov($array,$n) { // Удаление слов до 4 символов в массиве
  for($i = 0, $c = count($array); $i < $c; $i++) {
    if(mb_strlen(trim($array[$i]),"UTF-8") < $n) unset($array[$i]);
  }
  return $array;
}
function sklon($text) {
// удаляем
$s3 = mb_substr($text, mb_strlen($text,"UTF-8")-3, 3, "UTF-8"); // окончание слова в 3 символа
if ($s3== "ями"||$s3== "ами"||$s3== "ией"||$s3== "иям"||$s3== "ием"||$s3== "иях"||$s3== "мва"||$s3== "яна"||$s3== "сть"||$s3== "ной"||$s3== "ная"||$s3== "ные"||$s3== "ных"||$s3== "ную") {
$text = mb_substr($text, 0, -3, "UTF-8");
}
if (mb_strlen($text,"UTF-8") > 3) {
$s2 = mb_substr($text, mb_strlen($text,"UTF-8")-2, 2, "UTF-8"); // окончание слова в 2 символа
if ($s2=="ев"||$s2=="ов"||$s2=="ье"||$s2=="еи"||$s2=="ии"||$s2=="ей"||$s2=="ой"||$s2=="ий"||$s2=="ям"||$s2=="ем"||$s2=="ам"||$s2=="ом"||$s2=="ах"||$s2=="ях"||$s2=="ию"||$s2=="ью"||$s2=="ия"||$s2=="ья"||$s2=="ок") {
$text = mb_substr($text, 0, -2, "UTF-8");
}
$s1 = mb_substr($text, mb_strlen($text,"UTF-8")-1, 1, "UTF-8"); // окончание слова в 1 символ
if ($s1=="а"||$s1=="у"||$s1=="е"||$s1=="ы"||$s1=="и"||$s1=="й"||$s1=="о"||$s1=="ю"||$s1=="я"||$s1=="ь") {
$text = mb_substr($text, 0, -1, "UTF-8");
}
}
return $text;
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

function procent($text,$keys,$sinoff) {
  # Чистим текст
  $text = normaltext($text,0); // полная функция для ключевиков
  $textd = explode(" ", trim($text));
  $sized=count($textd);
$textorig =  preg_replace('/ {2,}/',' ',$text);

  # Получаем массив слов
  $textm = explode(" ", trim($textorig));
  $size3=count($textm);
  $keys0 = explode(" ", trim($keys));
  $keys2 = explode(",", trim($keys));
  $size2=count($keys0);
  $keyssin = explode(",", trim($keyssi));
  $text_koren = '';
for($i=0;$i<$size3;$i++) {
$text_koren .= sklon(trim($textm[$i]))." "; // строим текст из корней
}
$key_koren = '';
for($i=0;$i<$size2;$i++) {
$s1 = mb_substr($keys0[$i], mb_strlen($keys0[$i],"UTF-8")-1, 1, "UTF-8");
if ($s1==",") {
$key = mb_substr($keys0[$i], 0, -1, "UTF-8");
$koren = sklon(trim($key)); // находим корень слова ключевика
$key_koren .= $koren.", "; // строим ключевики из корней
} else { $key = $keys0[$i];
$koren = sklon(trim($key)); // находим корень слова ключевика
$key_koren .= $koren." "; // строим ключевики из корней
}
}
$key_koren = explode(",", trim($key_koren));
$size4=count($key_koren);
  $result = 'Слово с наибольшим весом является самым главным в тексте, 
  чтобы повысить вес слова – употребите его чаще или поставьте ближе к началу текста.<br>';
  $result .= '<table class="table_light" width=100%>';
  $result .= "<tr><td>Словосочетание</td><td align=center>Упоминания</td><td align=center>% в тексте</td><td width=65% align=center>Синонимы</td></tr>";
    	if ($sinoff == 1) { $str = sinonim(trim($keys).'|');  // получаем синонимы
$arr1 = explode("|", trim($str));
$sin1 = array();
foreach ($arr1 as $gruppa) {
$arr2 = explode(",", trim($gruppa));
for($i=0;$i<$size4;$i++) {
$sin1[$i] .= trim($arr2[$i]).', ';
}}
	}
      for($i=0;$i<$size4;$i++) {
	  if ($sinoff == 1) {
	  $sin = explode(",",$sin1[$i]);
$arr = array_flip($sin);
$sin = array_flip($arr);
$sinonim = implode(",", $sin);
$sinonim = str_replace(',', ', ', $sinonim);
}
    $raz = mb_substr_count($text_koren, trim($key_koren[$i]));
	if ( mb_substr_count(trim($keys2[$i]), ' ') > 0 )
	$procent_slovo = round($raz*100/$sized,2)*2;
	 else 
	$procent_slovo = round($raz*100/$sized,2);
$result .= "<tr><td>".trim($keys2[$i])."</td><td align=center>".$raz."</td><td align=center>".$procent_slovo."</td>";
if ($sinoff == 1) $result .= "<td align=center>".$sinonim."</td>";
if ($sinoff == 0) $result .= "<td align=center>Поиск синонимов отключен.</td>";
$result .= "</tr>";
	$summ+=$raz;
	$proc+=$procent_slovo;
      }
  $result .= '</table><br>Всего в тексте ключевых слов - '.$summ.'<br>Их процент - '.$proc;
  if ( $proc < 5 ) { $result .= '<br>Недостаточно ключевых слов в тексте.'; }
  elseif ( $proc > 8 ) { $result .= '<br>Много ключевых слов в тексте.'; }
  else { $result .= '<br>Оптимальное количество ключевых слов.'; }
  return  $result;
}

function yapars($word, $geo='0') {
  $count = count($word);
  $uri = array();
  for($i=0;$i<$count;$i++) {
  $params = array(
    'cmd' => 'words',
    'page' => 1,
    't' => $word[$i],
    'geo' => $geo,
  );
//'http://wordstat.yandex.ru/?cmd=words&page=1&t=%D0%BF%D0%BE%D0%BC%D0%B8%D0%B4%D0%BE%D1%80%D1%8B&geo=&text_geo='
  /* Запрос к wordstat Яндекс */
  $uri[$i] = 'http://wordstat.yandex.ru/?'.http_build_query($params);
  }
  $contents = array();
  $contents = multiyapars($uri);
  $m = array();
  for($i=0;$i<$count;$i++) {
  /* Парсинг данных и их вывод на экран */
  if ( preg_match('/<table border="0" cellpadding="5" cellspacing="0" width="100%">(.*)<\/table>/isU', $contents[$i], $table) ) {
    if ( preg_match_all('/<tr class="tlist" bgcolor=".*">\s*?<td>\s*?<a href=".*">(.*)<\/a>\s*?<\/td>\s*?<td><div style="width: 10px"><\/div> <\/td>\s*?<td class="align-right-td">(.*)<\/td>\s*?<\/tr>/isU', $table[1], $m) ) {
	$s[$i] = $m;
      }}}
	  return $s;
}
function multiyapars($data) {
  $fuid01 = '5092466b0cbab62d.jJgD6rQ2fWFcESmYT9oT82-ExFT4NaO7vw8H86HaqzHrhrHEdXNSr8DA2RI2rDYEP4N120pWif5GgR3hKu1WywHo_7plw1WyTzAYGkDRzDILyuWXBgIVE8KIWS3Cp9eO';
  $curls = array();
  $result = array();
  $mh = curl_multi_init();
  // Дескриптор мульти потока. Тоесть эта штука отвечает за то, чтобы много
  // запросов шли параллельно.
  foreach ($data as $id => $d) {
    $curls[$id] = curl_init();
        // Для каждого url создаем отдельный curl механизм чтоб посылал запрос)
        $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
        // Если $d это массив (как в случае с пост), то достаем из массива url
        // если это не массив, а уже ссылка - то берем сразу ссылку
  curl_setopt($curls[$id], CURLOPT_URL,$url);
  curl_setopt($curls[$id], CURLOPT_HEADER,0);
  curl_setopt($curls[$id], CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curls[$id], CURLOPT_REFERER, 'http://wordstat.yandex.ru/');
  curl_setopt($curls[$id], CURLOPT_CONNECTTIMEOUT, 30);
  curl_setopt($curls[$id], CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
  curl_setopt($curls[$id], CURLOPT_COOKIE, 'fuid01='.$fuid01);
        // добавляем текущий механизм к числу работающих параллельно
    curl_multi_add_handle($mh, $curls[$id]);
  }
  // число работающих процессов.
  $running = null;
  // curl_mult_exec запишет в переменную running количество еще не завершившихся
  // процессов. Пока они есть - продолжаем выполнять запросы.
  do { curl_multi_exec($mh, $running); } while($running > 0);
  // Собираем из всех созданных механизмов результаты, а сами механизмы удаляем
  foreach($curls as $id => $c) {
    $result[$id] = curl_multi_getcontent($c);
    curl_multi_remove_handle($mh, $c);
  }
  // Освобождаем память от механизма мультипотоков
  curl_multi_close($mh);
  // возвращаем данные собранные из всех потоков.
  return $result;
}
function sinonim($sinonim) { 
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'http://seogenerator.ru/api/synonym/');
  curl_setopt($ch, CURLOPT_HEADER,0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_REFERER, 'http://seogenerator.ru/');
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, 'text='.$sinonim.'&base=big1&type=random&count=10&format=text');
  $contents = curl_exec($ch);
  curl_close($ch);
  if ($contents == 'Exceeded the limit queries from this IP address') $contents = 'Перегрузка сервера.';
  return $contents;
}
?>
