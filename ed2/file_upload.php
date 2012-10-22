<?php

  foreach ($_GET as $secvalue) {
    $secvalue = str_replace("(", "&#040;", str_replace(")", "&#041;", $secvalue));
    if ( (preg_match("/<[^>]*script*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*object*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*iframe*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*applet*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*meta*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*style*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*form*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*img*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*onmouseover*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmouseout*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmousemove*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmouseup*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onload*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onreset*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onresize*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*body*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/\([^>]*\"?[^)]*\)/i", $secvalue)) ||
    (preg_match("/\"/i", $secvalue)) ) {
    die ('Попытка взлома через GET');
    }
  }
  foreach ($_FILES['file'] as $secvalue) {
    $secvalue = str_replace("(", "&#040;", str_replace(")", "&#041;", $secvalue));
    if ( (preg_match("/<[^>]*script*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*object*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*iframe*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*applet*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*meta*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*style*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*form*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*img*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*onmouseover*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmouseout*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmousemove*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmouseup*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onload*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onreset*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onresize*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*body*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/\"/i", $secvalue)) ) {
    die ('Попытка взлома через файл');
    }
  }

function translit_name($cyr_str) { # Транслит названий файлов
  //$cyr_str = iconv ( "UTF-8", "windows-1251", $cyr_str );
  $tr = array(
   "Ґ"=>"G","Ё"=>"YO","Є"=>"E","Ї"=>"YI","І"=>"I","і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"","є"=>"e",
   "ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I",
   "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
   "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
   "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"",
   "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
   "«"=>"","»"=>"",","=>"","!"=>"",":"=>"",";"=>"","?"=>""," "=>"_"
  );
   return iconv ( "UTF-8", "UTF-8//IGNORE", strtr ( $cyr_str, $tr ) );
}

if (!empty($_FILES['file']['name'])) {    
  //$file_size = $_FILES['file']['size']; // Преобразовать для показа размера
   //$array = explode(".", $_FILES['file']['name']);
   //$file_type = end($array);
  $file_id = translit_name($_FILES['file']['name']); // md5(date('YmdHis'));
  copy($_FILES['file']['tmp_name'], '../files/'.$file_id); //.".".$file_type
  $array = array(
    'filelink' => '/files/'.$file_id, //.".".$file_type,
    'filename' => $file_id //$_FILES['file']['name']
  );
echo stripslashes(json_encode($array));
}
?>