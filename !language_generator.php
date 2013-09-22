<?php
// Настройка
$lang = "po"; // Работа ведется с этим языком.

$php_ss_ok = $php_aa_ok = $js_ss = $js_aa = $php_ss = $php_aa = array();
function it($path){
	global $js_ss, $js_aa, $php_ss, $php_aa;
	$entries = scandir($path);
	foreach($entries as $entry) {
		if (is_dir($entry) && $entry != '.' && $entry != '..' && trim($entry)!="") 
		$filelist[] = it($entry);
		if ($path != '.') $path2 = $path."/"; else $path2 = "";
	    if ( (strpos($entry, '.php') || strpos($entry, '.js') ) && trim($entry)!="" && trim($entry)!="bd.php") {
	        $txt = file_get_contents($path2.$entry);
	        if (strpos($entry, '.php')) {
	        	if (strpos($txt, "ss(\"")) {
		        	preg_match_all('|ss\(("[^)]+?")\)|', $txt, $o);
		        	$php_ss = array_merge($php_ss, $o[0]);
		        	//foreach ($o[0] as $value) {
		        		//if (strpos(" ".$value, "button") ) echo $entry;
		        	//}
		        }
		        if (strpos($txt, "aa(\"")) {
		        	preg_match_all('|aa\(("[^)]+?")\)|', $txt, $o);
		        	$php_aa = array_merge($php_aa, $o[0]);
		        }
	        }
	        /*
	        if (strpos($entry, '.js')) {
	        	if (strpos($txt, "ss(\"")) {
		        	preg_match_all('|ss\(("[^)]+?")\)|', $txt, $o);
		        	$js_ss = array_merge($js_ss, $o[0]);
		        }
		        if (strpos($txt, "aa(\"")) {
		        	preg_match_all('|aa\(("[^)]+?")\)|', $txt, $o);
		        	$js_aa = array_merge($js_aa, $o[0]);
		        }
	        }
	        */
	    }
	}
	return $filelist;
}
// Составили список всех файлов
it('.');
// Получили список фраз
$php_ss = array_unique($php_ss);
$php_aa = array_unique($php_aa);

// Получили список ранее переведенных фраз
$php_ss_old = file('language/'.$lang.'.php');
$php_aa_old = file('language/adm_'.$lang.'.php');

$php_ss_old_count = count($php_ss_old) - 2;
$php_aa_old_count = count($php_aa_old) - 4;

$php_ss_old_ok = $php_aa_old_ok = array();

for ($i=2; $i < $php_aa_old_count; $i++) { 
	$x = str_replace("\",
11111", "", str_replace("\"
11111", "", str_replace("00000\"", "", "00000".$php_aa_old[$i]."11111")));
	$x = explode("\" => \"", $x);
	if ($x[1] != "") { // Если у найденной строки есть перевод
		$y = $x[0];
		$php_aa_old_ok[$y] = $x[1];
	}
}
for ($i=2; $i < $php_ss_old_count; $i++) { 
	$x = str_replace("\",
11111", "", str_replace("\"
11111", "", str_replace("00000\"", "", "00000".$php_ss_old[$i]."11111")));
	$x = explode("\" => \"", $x);
	if ($x[1] != "") { // Если у найденной строки есть перевод
		$y = $x[0];
		$php_ss_old_ok[$y] = $x[1];
	}
}

foreach ($php_aa as $key => $value) {
	$value = str_replace("aa(\"", "", str_replace("\")", "", $value));
	if (array_key_exists($value, $php_aa_old_ok)) 
		$php_aa_ok_2[] = "\"".$value."\" => \"".$php_aa_old_ok[$value]."\"";
	else 
		$php_aa_ok[] = "\"".$value."\" => \"\"";
}
$php_aa_ok = array_merge($php_aa_ok, $php_aa_ok_2);

foreach ($php_ss as $key => $value) {
	if ($value != "ss(\"button\")") {
		$value = str_replace("ss(\"", "", str_replace("\")", "", $value));
		if (array_key_exists($value, $php_ss_old_ok)) 
			$php_ss_ok_2[] = "\"".$value."\" => \"".$php_ss_old_ok[$value]."\"";
		else 
			$php_ss_ok[] = "\"".$value."\" => \"\"";
	}
}
$php_ss_ok = array_merge($php_ss_ok, $php_ss_ok_2);

// Добавить JS !!!

$php_aa_ok = "<?php\nreturn array(\n".implode(",\n",$php_aa_ok)."\n);\n?>";
$php_ss_ok = "<?php\nreturn array(\n".implode(",\n",$php_ss_ok)."\n);\n?>";

file_put_contents('language/shablon_language_admin_'.$lang.'.php',$php_aa_ok);
file_put_contents('language/shablon_language_'.$lang.'.php',$php_ss_ok);
echo "ГОТОВО!";
?>