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
  $rep1 = array("\n","  ","!","?","#","№","!","&",":",";","-","—","\"","«","»","(",")",",,","..","...");
  $rep2 = array(" "," ", ".",".","", "", "", "", ",",",",",",",","",  "", "", "", "", ",", ".", ".");
  $ys = $_POST['x']; 
  $key = $_POST['key'];
  $str = explode(".", trim($ys));

  $go = array(); 
  $i = 0;
  foreach ($str as $word) {
    $k = explode(",", $word);
    foreach ($k as $fraza) {
      $fraza = trim($fraza);
      if (mb_substr_count($fraza, ' ') > 2) {

        $i++;
        $go[$i] = '!('.$fraza.')'; 
      }
    }
  }
  echo procent($ys,$key);
}

function newkey($text,$kol,$kolslov) {
  # Чистим текст
  $text = normaltext($text,0); // полная функция для ключевиков

  $textorig = stopslov($text);
  $texts = explode(' ', $textorig);
  $text2 = $texts;
  $count = count($texts);
  $text_koren = $arr5 = '';
  $arr = $arr2 = $arr3 = $arr4 = $arrse = array();
  for ($i=0; $i < $count; $i++) {
    if (mb_strlen(trim($texts[$i])) > 4) {
      $koren = sklon(trim($texts[$i])); // находим корень слова
      if (summpristav($textorig, trim($koren)) > 1) { // берем слова имеющие более 1-го вхождения
        $arr[$koren] = summpristav($textorig, trim($koren)); // корень и сколько вхождений в тексте он имеет
        $arr2[$koren]= $texts[$i]; // корень и найденное слово по нему
        $arr3[$i]= $koren; // Массив с корнями
      }
      $text_koren .= $koren." "; // строим текст из корней
    } else $text_koren .= trim($texts[$i])." ";
  }
  for ($i=0; $i < $count; $i++) { // строим фразы
    if (!empty($texts[$i]) && !empty($texts[$i+1])) {
      if (isset($arr3[$i+1]) && isset($arr3[$i]))
        if (array_key_exists($arr3[$i],$arr) 
        && array_key_exists($arr3[$i+1],$arr) 
        && mb_substr_count($arr5,$arr3[$i].' '.$arr3[$i+1]) == 0 
        && mb_substr_count($text_koren, $arr3[$i].' '.$arr3[$i+1]) > 1) {
          $arr4[trim($texts[$i]).' '.trim($texts[$i+1])] = mb_substr_count($text_koren, $arr3[$i].' '.$arr3[$i+1]); // Фраза в 2 слова и ее вес
          $arr5 .= $arr3[$i].' '.$arr3[$i+1].'|';
          $arr5 .= $arr3[$i+1].' '.$arr3[$i].'|';
        }
      if (isset($arr3[$i+2]) && isset($arr3[$i+1]) && isset($arr3[$i]))
        if ($i < $count - 1 
        && array_key_exists($arr3[$i],$arr) 
        && array_key_exists($arr3[$i+2],$arr) 
        && mb_substr_count($arr5,$arr3[$i].' '.trim($texts[$i+1]).' '.$arr3[$i+2]) == 0
        && mb_substr_count($arr5,$arr3[$i].' '.$arr3[$i+1]) == 0 
        && mb_substr_count($text_koren, $arr3[$i].' '.trim($texts[$i+1]).' '.$arr3[$i+2]) > 1) {
          $arr4[trim($texts[$i]).' '.trim($texts[$i+1]).' '.trim($texts[$i+2])] = mb_substr_count($text_koren, $arr3[$i].' '.trim($texts[$i+1]).' '.$arr3[$i+2]); // Фраза в 3 слова с предлогом в середине и ее вес
          $arr5 .= $arr3[$i].' '.trim($texts[$i+1]).' '.$arr3[$i+2].'|';
        }
    }
  }
  arsort($arr4); // сортируем словосочетания по вхождениям
  $arrres2 = array_keys($arr4); // переводим в массив словосочетаний (номер - словосочетание)
  $countres2 = count($arrres2);
  $y =1; // этот счетчик будет работать в двух циклах
  $str = '';  // начинаем строить строку с ключами (берем словосочетания)
  for ($i=0; $i < $countres2; $i++) {
    if ($y > $kolslov) break;
    $str .= " ".$arrres2[$i].","; // обязательно пробел в начале или strpos не сработает потом
    ++$y;
  }
  $count2=count($arr3);
  $vhod1 = array();
  $arr6 = array_slice($arr4, 0, $kolslov);
  // в итоге получаем
  for ($i=0; $i < $count2; $i++)
    if (!empty($arr3[$i]))
      if (mb_substr_count($str,$arr3[$i]) < 1) 
        $arr6[$arr2[$arr3[$i]]] = $arr[$arr3[$i]]; // слово и сколько вхождений в тексте
  $str2 = ''; // продолжаем строить строку с ключами
  arsort($arr6); // сортируем словосочетания по вхождениям
  $arrres2 = array_keys($arr6); // переводим в массив словосочетаний (номер - словосочетание)
  $countres2 = count($arrres2)-1;
  $y =1; // этот счетчик будет работать в двух циклах
  $str = '';  // начинаем строить строку с ключами (берем словосочетания)
  for ($i=0;$i<$countres2;$i++) {
    if ($y > $kol) break;
    $str2 .= " ".$arrres2[$i].","; // обязательно пробел в начале или strpos не сработает потом
    ++$y;
  }
  $keys = trim(mb_substr($str2, 0, -1 , 'UTF-8')); // обьединяем словосочетания и слова
  return $keys;
}

function newdesc($text,$key,$kol) {
  $text = normaltext($text,1);  // краткая функция для описания
  # убираем стоп слова
  $text = stopslov($text); 
  $data2 = explode(".", trim($text)); // Предложения в массив
  $key = str_replace(",", "", $key);
  $datai = explode(" ", trim($key)); // Ключевики в массив
  $desc=array();
  foreach ($data2 as $arr1) { // Выполняем поиск предложения с максимумом ключевиков
  if (strlen(trim($arr1)) < $kol) {
    $i = 0;
    foreach ($datai as $arr2) {
      $i += summpristav($arr1, sklon(trim($arr2)));
    }
     $desc[$i] = trim($arr1); // новый массив предложений, предложение и сколько содержит ключевиков
	 }
  }
  krsort($desc); // сортируем
  foreach ($desc as $key => $val) {
    $newdesk = $val; // берем только с максимальным вхождением ключевиков
    break 1;
  }
  return $newdesk;
}

function normaltext($text, $a) { // Нормализация текста - убираем все лишнее
  $text= mb_convert_case($text, MB_CASE_LOWER); // все в нижний регистр
  $text = preg_replace( "'<script[^>]*>.*?</script>'usi", ' ', $text );  
  $text = preg_replace( "'<noindex[^>]*>.*?</noindex>'usi", ' ', $text ); // убераем noindex     
  $text = preg_replace( '/<!--.+?-->/u', '', $text );
  $text = preg_replace( '/{.+?}/u', '', $text );
  $text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/uis', '\1', $text );
  $text = preg_replace('/<[^>]*>/u', ' ', $text);
  // email 
  $regex = '/(([_A-Za-z0-9-]+)(\\.[_A-Za-z0-9-]+)*@([A-Za-z0-9-]+)(\\.[A-Za-z0-9-]+)*)/iex';
  $text = preg_replace($regex, ' ', $text);
  $regex = "~<noscript[^>]*?>.*?</noscript>~usi";
  $text = preg_replace($regex, ' ', $text); // убираем ноуиндекс
  $regex = "~<table[^>]*?>.*?</table>~usi";
  $text = preg_replace($regex, ' ', $text);
  if ($a !== 1) // для description не нужно
    $text = str_ireplace(array("&nbsp;", "\r\n", "\r", "\n", "\t", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "&", "%", "/", "`", "_", "'", "=", "+", "*", "^", "#", "]", "[", "\"", "-", "—", ",", ".", "?", "(", ")", ";", ":", "!"), " ", $text); // убираем ненужные знаки
  return trim($text);
}

function stopslov($text) { // Удаление стоп слов
  $data = array('ближе','близко','более','будет','была','были','было','быстро','быть','вам','вами','вас','верх','внутри','http','www','вопрос','восемнадцать','восемь','восемьдесят','восемьсот','воскресенье',
  'время','всё','всего','всех','вторник','вчера','года','градус','грамм','даже','далеко','два','двадцать','двенадцать','двести','девяносто','девятнадцать','девять','девятьсот','день','десять','длинный','для',
  'его','если','есть','завтра','здесь','знать','ибо','идет','извините','или','иногда','иной','кажется','казаться','каких','килограмм','километр','килотонна','когда','конечно','короткий','которая','которой','которые',
  'какое','какой','какие','который','которым','которых','лево','либо','литр','лишь','мало','медленно','между','меня','метр','миллиграмм','миллилитр','миллиметр','миллисекунда','минута','млрд','мне','многие','много','могут',
  'мое','моё','моей','может','можно','надо','найти','нам','нами','наоборот','например','нас','нах','нашего','нашей','него','нее','ней','некоторые','некто','необходимо','несколько','нет','нибудь','низ','никто','ним',
  'ними','них','нужно','ночь','обо','один','одиннадцать','однако','окуеть','она','они','оно','очень','паре','парой','пару','перед','писали','под','подле','пожалуйста','поздно','понедельник','порой','после','потом',
  'поэтому','одной','нужна','состоит','второй','право','своей','простите','просто','пуд','назад','вперед','опять','снова','вновь','классно','впредь','такую','я','сайт','интернет','порно','вспять','но','по',
  'пятнадцать','пятница','пять','пятьдесят','пятьсот','раз','мной','разу','рано','самый','сантиметр','свой','себя','сегодня','сейчас','секунда','семнадцать','семь','семьдесят','семьсот','сколько','снаружи','сорок',
  'спасибо','спустя','сразу','среда','стоит','суббота','сутки','также','такой','там','тебе','тебя','тобой','собой','типа','тогда','того','тоже','толще','только','тонна','три','тридцать','тринадцать','триста','хотя',
  'час','через','четверг','четыре','четыреста','четырнадцать','шестнадцать','шесть','шестьдесят','шестьсот','эта','эти','этим','это','этого','этой','этому','этот','является','чтобы','больше','сам','сама','сами',
  'самой','самих','самого','само','самими','самим','самому','самые','чем','зачем','почему','чему','чего','кто','что','когда','куда','туда','там','тут','здесь','вон','многими','многих','многим','своим','своими',
  'вашу','моем','лучше','ваше','любом','можете','раза','обычно','раз','этом');
  foreach ($data as $val) {
    //$text = str_ireplace(chr(194).chr(160).$val.chr(194).chr(160), " ", $text);

    $text = str_replace(chr(194).chr(160), " ", $text);
    $text = str_ireplace(" ".$val." ", " ", $text);
    //$text = str_ireplace(" ".$val." ", " ", $text);
  }
  $text = str_replace("  ", " ", $text);
  //print($text);
  return $text;
}
function sklon($text) {
// удаляем
$s4 = mb_substr($text, mb_strlen($text)-6, 6); //альные альных
if ($s4== "альные"||$s4== "альных"||$s4== "альную"||$s4== "альной"||$s4== "альная") {
$text = mb_substr($text, 0, -6);
} else {
$s5 = mb_substr($text, mb_strlen($text)-4, 4); // окончание слова в 4 символа
if ($s5== "овые"||$s5== "овых"||$s5== "овым"||$s5== "овую"||$s5== "овая")
$text = mb_substr($text, 0, -4);

$s3 = mb_substr($text, mb_strlen($text)-3, 3); // окончание слова в 3 символа
if ($s3== "ями"||$s3== "ами"||$s3== "ией"||$s3== "иям"||$s3== "ием"||$s3== "иях"||$s3== "мва"||$s3== "яна"||$s3== "сть"||$s3== "сти"||$s3== "ной"||$s3== "ная"||$s3== "ные"||$s3== "ных"||$s3== "ную"||$s3== "ный")
$text = mb_substr($text, 0, -3);

if (mb_strlen($text) > 3) {
$s2 = mb_substr($text, mb_strlen($text)-2, 2); // окончание слова в 2 символа
if ($s2=="ев"||$s2=="ов"||$s2=="ье"||$s2=="еи"||$s2=="ые"||$s2=="ие"||$s2=="ии"||$s2=="ей"||$s2=="ой"||$s2=="ий"||$s2=="ям"||$s2=="ем"||$s2=="ам"||
$s2=="ых"||$s2=="ом"||$s2=="ах"||$s2=="ях"||$s2=="ию"||$s2=="ью"||$s2=="ия"||$s2=="ья"||$s2=="ок")
$text = mb_substr($text, 0, -2);
$s1 = mb_substr($text, mb_strlen($text)-1, 1); // окончание слова в 1 символ
if ($s1=="а"||$s1=="у"||$s1=="е"||$s1=="ы"||$s1=="и"||$s1=="й"||$s1=="о"||$s1=="ю"||$s1=="я"||$s1=="ь")
$text = mb_substr($text, 0, -1);
}
}
return $text;
}

function procent($text,$keys) {
  # Чистим текст
  $text = normaltext($text,0); // полная функция для ключевиков
  $text = stopslov($text); 
  $textd = explode(" ", trim($text));
  $sized = count($textd);
  $textorig =  preg_replace('/ {2,}/',' ',$text);
  # Получаем массив слов
  $textm = explode(" ", trim($textorig));
  $size3 = count($textm);
  $keys0 = explode(" ", trim($keys));
  $keys2 = explode(",", trim($keys));
  $size2 = count($keys0);
  $text_koren = '';
  for ($i=0; $i<$size3; $i++) {
    $text_koren .= sklon(trim($textm[$i]))." "; // строим текст из корней
  }
  $key_koren = '';
  for ($i=0; $i<$size2; $i++) {
    $s1 = mb_substr($keys0[$i], mb_strlen($keys0[$i])-1, 1);
    if ($s1 == ",") {
      $key = mb_substr($keys0[$i], 0, -1);
      $koren = sklon(trim($key)); // находим корень слова ключевика
      $key_koren .= $koren.", "; // строим ключевики из корней
    } else { 
      $key = $keys0[$i];
      $koren = sklon(trim($key)); // находим корень слова ключевика
      $key_koren .= $koren." "; // строим ключевики из корней
    }
  }
  $key_koren = explode(",", trim($key_koren));
  $size4 = count($key_koren);
  $result = '<p>Слово с наибольшим весом является самым главным в тексте, 
    чтобы повысить вес слова – употребите его чаще или поставьте ближе к началу текста.<br>';
  $result .= '<table class="table_light w60">';
  $result .= "<tr><td>Словосочетание</td><td align=center>Упоминания</td><td align=center>% в тексте</td></tr>";
  $re = array();
  for ($i=0; $i < $size4; $i++) {
    $raz = summpristav($text_koren, trim($key_koren[$i]));
  	if ( mb_substr_count(trim($keys2[$i]), ' ') > 0 )
  	  $procent_slovo = round($raz * 100 / $sized, 2) * 2;
  	else 
  	  $procent_slovo = round($raz * 100 / $sized, 2);
    $re[$raz.mt_rand(1000000, 9999999)] = "<tr><td>".trim($keys2[$i])."</td><td align=center>".$raz."</td><td align=center>".$procent_slovo."</td></tr>";
  }
  krsort($re);
  $result .= implode("",$re).'</table>';
  return  $result;
}

function summpristav($text,$slovo) { // Подсчет слова с приставками
  $summ = 0;
  $summ += mb_substr_count($text,' '.$slovo);
  $pr = 'во взо вы до за изо ко на над надо не недо о об обо от ото па пере по под подо пра пред предо про разо с со су у без бес вз вс воз вос из ис низ нис раз рас роз рос пре при';
  $p = explode(' ', $pr);
  foreach($p as $arr) {
    $summ += mb_substr_count($text,' '.$arr.$slovo);
  }
  return $summ;
}
?>
