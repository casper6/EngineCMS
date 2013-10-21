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
		die ('Ошибка безопасности GET');
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
		die ('Ошибка безопасности');
	}
}

if (!empty($_FILES['file']['name'])) {	
	$_FILES['file']['name'] = basename($_FILES['file']['name']);
	//$file_size = $_FILES['file']['size'];
	$file_type = info($_FILES['file']['name'], 'type');
	$file_name = str_replace('.'.$file_type, '', $_FILES['file']['name']);
	$file_id = translit_name($file_name);
	$file_name = get_filename('../files/', $_FILES['file']['name'], $file_type);
	 // md5(date('YmdHis'));
	$file_ico = get_ico($file_type);
	$file = '/files/'.$file_id.".".$file_type; //file_name;
	copy($_FILES['file']['tmp_name'], "..".$file);
	echo '<a href="'.$file.'" target="_blank" rel="'.$file_id.'" class="editor_file_link editor_file_ico_'.$file_ico.'">'.$file_name.'</a>';
}	

///////////////////////////////////////////////////////////

function get_ico($type) {
	$fileicons = array('other' => 0, 'avi' => 'avi', 'doc' => 'doc', 'docx' => 'doc', 'gif' => 'gif', 'jpg' => 'jpg', 'jpeg' => 'jpg', 'mov' => 'mov', 'csv' => 'csv', 'html' => 'html', 'pdf' => 'pdf', 'png' => 'png', 'ppt' => 'ppt', 'rar' => 'rar', 'rtf' => 'rtf', 'txt' => 'txt', 'xls' => 'xls', 'xlsx' => 'xls', 'zip' => 'zip');
	if (isset($fileicons[$type])) return $fileicons[$type];
	else return 'other';
}

function get_filename($path, $filename, $file_type) {
	if (!file_exists($path.$filename)) return $filename;
	$filename = str_replace('.'.$file_type, '', $filename);
	$new_filename = '';
	for ($i = 1; $i < 100; $i++) {			
		if (!file_exists($path.$filename.$i.'.'.$file_type)) {
			$new_filename = $filename.$i.'.'.$file_type;
			break;
		}
	}
	if ($new_filename == '') return false;
	else return $new_filename;		
}	

function info($file, $key = false) {
	$info = array();
	$array = explode(".", $file);
	$info['size'] = @filesize($file);
	//$info['time'] = filectime($file);
   	$info['type'] = end($array);
	$info['name'] = str_replace('.'.$info['type'], '', $file);
	$info['image'] = false;
	if ($info['type'] == 'JPG' ||
		$info['type'] == 'jpg' ||
		$info['type'] == 'gif' ||
		$info['type'] == 'png')	{
		$info['image'] = true;
	}
	if (!$key) return $info;
	else return $info[$key];
}

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
?>