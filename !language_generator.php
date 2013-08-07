<?php
//	""=>""
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
	    }
	}
	return $filelist;
}
// Составили список всех файлов
it('.');
$php_ss = array_unique($php_ss);
$php_aa = array_unique($php_aa);
foreach ($php_ss as $key => $value) if ($value != "ss(\"button\")") $php_ss_ok[] = "\"".str_replace("ss(\"", "", str_replace("\")", "", $value))."\" => \"\"";
foreach ($php_aa as $key => $value) $php_aa_ok[] = "\"".str_replace("aa(\"", "", str_replace("\")", "", $value))."\" => \"\"";

$php_aa_ok = "<?php\nreturn array(\n".implode(",\n",$php_aa_ok)."\n);\n?>";
$php_ss_ok = "<?php\nreturn array(\n".implode(",\n",$php_ss_ok)."\n);\n?>";

file_put_contents('language/shablon_language.php',$php_ss_ok);
file_put_contents('language/shablon_language_admin.php',$php_aa_ok);
echo "ГОТОВО!";
?>