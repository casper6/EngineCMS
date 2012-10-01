<?php
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
	$_FILES['file']['tmp_name'] = str_replace("(", "", str_replace("<", "", str_replace("^", "", str_replace("?", "", $_FILES['file']['tmp_name']))));
	copy($_FILES['file']['tmp_name'], '../img/'.md5(date('YmdHis')).'.jpg');
	echo '/img/'.md5(date('YmdHis')).'.jpg';
}
?>
