<?php

if(!eregi('image/', $_FILES['file']['type'])) {
  echo 'Это не фотография!';
  exit(0);
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
    (preg_match("/<[^>]*onmouseover*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmouseout*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmousemove*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmouseup*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onload*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onreset*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onresize*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/<[^>]*body*\"?[^>]*>/i", $secvalue)) ||
    (preg_match("/\"/i", $secvalue)) ) {
        echo 'Ошибка безопасности';
        die;
    }
}
$_FILES['file']['type'] = strtolower($_FILES['file']['type']);
if ($_FILES['file']['type'] == 'image/png' 
|| $_FILES['file']['type'] == 'image/jpg' 
|| $_FILES['file']['type'] == 'image/gif' 
|| $_FILES['file']['type'] == 'image/jpeg'
|| $_FILES['file']['type'] == 'image/pjpeg') {	
    $filename = md5(date('YmdHis')).'.jpg';
    $file = '../img/'.$filename;
    copy($_FILES['file']['tmp_name'], $file);
	$array = array(
		'filelink' => '/img/'.$filename
	);
	echo stripslashes(json_encode($array));   
}

function is_image($image_path) { 
    if (!$f = fopen($image_path, 'rb')) return false;
    $data = fread($f, 8);
    fclose($f);
    // проверка сигнатуры
    if (array_pop(unpack('H12', $data)) == '474946383961' || array_pop(unpack('H12', $data)) == '474946383761') return 'gif';
    else if (array_pop(unpack('H4', $data)) == 'ffd8') return 'jpg';
    else if (array_pop(unpack('H16', $data)) == '89504e470d0a1a0a') return 'png';
    return false;
}
?>