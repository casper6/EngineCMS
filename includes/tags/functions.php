<?php
// при обращении к функции newkey вида newkey($texttags,2);
// получим полный список ключевиков с фразами в 2 и 3 слова - можно будет использовать для поиска позиций в поисковиках
function newkey($text) {
# Чистим текст
      $text = normaltext($text,0); // полная функция для ключевиков
# убираем стоп слова
      $text = stopslov($text);
	  $text = sklonenie($text); // Удаление склонений
      # Получаем массив слов
    $text = explode(" ", trim($text));
    $text = delslov($text);  // удаляем слова в 3 и менее символа
    
	$data='';
    foreach($text as $key=>$word) $data.=' '.strtolower($word);
    $text=split(' ', trim($data)); $size=count($text);
    $arr1=array(); $arr2=array(); $arr3=array();

    ### Строим массив слов отсортированный по частоте вложений в тексте
    for($i=0; $i<$size; $i++) {
        $word=$text[$i];
        if($arr1[$word])$arr1[$word]++; else $arr1[$word]=1;
    }
    arsort($arr1);
    ### Строим массив фраз состоящих из двух слов отсортированный по частоте вложений в тексте
	if ($a == 2) {
    for($i=0; $i<$size-1; $i++) {
        $word=$text[$i].' '.$text[$i+1];
        if($arr2[$word])$arr2[$word]++; else $arr2[$word]=1; 
    }
    arsort($arr2);
    ### Строим массив фраз состоящих из трех слов отсортированный по частоте вложений в тексте
    for($i=0; $i<$size-2; $i++) {
        $word=$text[$i].' '.$text[$i+1].' '.$text[$i+2];
        if($arr3[$word])$arr3[$word]++; else $arr3[$word]=1;
    }
    arsort($arr3);
}
    ### Выбираем 6 первых слов с максимальной частотой вложений
    $data=array(); $i=0;
    foreach($arr1 as $word=>$count) {
        $data[$word]=$count;
        if($i++==5)break;
    }
    arsort($data); $text='';

    ### Переводим массив фраз в текст, опять таки с учетом частот вложений
    foreach($data as $word=>$count) $text.=', '.$word; $text=substr($text, 1);

    ### Возвращаем полученный результат
    return trim($text);

}
function newdesc($text,$key) {
$text = normaltext($text,1);  // краткая функция для описания
$data2 = explode(".", trim($text)); // Предложения в массив
$datai = explode(",", trim($key)); // Ключевики в массив
$desc=array();
foreach($data2 as $arr1){ // Выполняем поиск предложения с максимумом ключевиков
$i = 0;
foreach($datai as $arr2){
$pos = strpos($arr1, $arr2);              
if($pos !== FALSE)
$i++;    
}
if (strlen(trim($arr1)) < 200) $desc[$i] =  trim($arr1); // новый массив предложений, предложение и сколько содержит ключевиков
}
krsort($desc); // сортируем
foreach($desc as $key => $val) 
{
$newdesk = $val; // берем только с максимальным вхождением ключевиков
break 1;
}
# убираем стоп слова
      $newdesk = stopslov($newdesk);
return  $newdesk;
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
		$text = str_replace(array("&nbsp;", "\r\n", "\r", "\n", "\t", "-", ",", ".", "&", "%", "?", "/", "(", ")", ";", ":", "`", "_", "'", "=", "+", "*", "^", "#", "!"), " ", $text);
		 }
	return trim($text);
	 }
function stopslov($text) { // Удаление стоп слов
    $name='includes/tags/common-words.txt';
    if(file_exists($name)) {
        if($file=fopen($name, 'r')) {
            $data='';
            while(!feof($file)){
                $word=trim(fgets($file));
                if($word[0]=='#')continue;
                $data.=$word;
            }
            fclose($file);
        }
        $text=preg_replace('/\b'.$data.'\b/ui', '', $text);
    } return  $text; }
	
	function delslov($array) { // Удаление слов до 4 символов в массиве
for($i = 0, $c = count($array); $i < $c; $i++)
{
    if(mb_strlen($array[$i]) < 4)
        unset($array[$i]);
}
 return  $array; }
 
 	function sklonenie($text) { // Поиск склонений слов
$arr2 = explode(" ", trim($text));
$arr = delslov($arr2);
foreach($arr as $s11){ // $s1 Наше слово по которому будем искать склонение для нормального слова (пример - стол)
 $s1 = trim($s11);
 if (utf8_substr($s1, -1) == "а" || utf8_substr($s1, -1) == "ь") {
 $s2 = preg_replace("/(.*).$/u", "\\1", $s1); // удаляем последний символ из слова если требуется для склонения (пример - макулатура , рубль)
 $zamena1 = array("/".$s2."ей/", "/".$s2."я/", "/".$s2."и/", "/".$s2."ем/", "/".$s2."ём/", "/".$s2."ы/", "/".$s2."у/", "/".$s2."ой/", "/".$s2."е/", "/".$s2."ю/" );
 $text = preg_replace($zamena1 , $s1, $text);
 } else {
 $zamena = array("/".$s1."ов/", "/".$s1."а/", "/".$s1."у/", "/".$s1."ом/", "/".$s1."е/", "/".$s1."ы/", "/".$s1."и/" );
 $text = preg_replace($zamena , $s1, $text);
 }
 }
return  $text; }
  function utf8_substr($str,$start) { // Функция substr для работы в utf-8
   preg_match_all("/./su", $str, $ar);
   if(func_num_args() >= 3) {
       $end = func_get_arg(2);
       return join("",array_slice($ar[0],$start,$end));
   } else {
       return join("",array_slice($ar[0],$start));
   }
}
function razmetka($text, $keys) { //Функция поика ключевых слов в тексте и бозначения их жирным шрифтом
$key = explode(",", trim($keys));
foreach($key as $s){
 $zamena = "/\b".trim($s)."\b/ui";
  $na = "<b>".$s."</b>";
 $text = preg_replace($zamena, $na, $text);
 }
return  $text; 
}
?>
