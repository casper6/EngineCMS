<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
require_once("mainfile.php");
if (isset($_REQUEST['action']))   $action = $_REQUEST['action']; else die(); // Выбор события
if ($_REQUEST['site'] !== '') {  $site = $_REQUEST['site']; } else { echo 'Введите домен'; exit; }// должно быть всегда
#####################
if ($action == 1) { // добавление сайта в сканер
if ($_REQUEST['login'] !== '')   $login = $_REQUEST['login'];
if ($_REQUEST['pass'] !== '')   $pass = $_REQUEST['pass'];
$site = str_replace("http://", "", $site);
$site = str_replace("www.", "", $site);
$v = $db->sql_numrows($db->sql_query("SELECT url FROM ".$prefix."_parser where class='s' and url='".$site."'"));
if ($v > 0) { echo 'Сайт '.$site.' уже есть в базе'; exit; }
$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','s','".$site."','".$login."','".$pass."','')") or die ('Ошибка: Не удалось сохранить. 1');
$filestr = fopen ('files/'.$site,"w+");
fwrite($filestr, $site."/\n");
fclose ($filestr);
$filestr2 = fopen ('files/go'.$site,"w+");
fwrite($filestr2, '');
fclose ($filestr2);
echo 'Сайт '.$site.' успешно добавили<meta http-equiv="Refresh" content="2"/> Перезагрузка'; exit;
}
#####################
if ($action == 2) { // сохраняем и изменяем авторизацию
if ($_REQUEST['login'] == '' || $_REQUEST['pass'] == '') 
echo 'Без авторизации'; else echo 'Есть авторизация';  
$pass = $_REQUEST['pass'];
$login = $_REQUEST['login'];
$db->sql_query("UPDATE ".$prefix."_parser SET title = '".$login."', text = '".$pass."'  WHERE id='".$site."'") or die ('Ошибка: Не удалось сохранить. 2');
exit;
}
#####################
if ($action == 3) { // форма редактирования
$result = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_parser where id='".$site."'"));
if ($result['config'] !== '') {
$arr = explode('|', $result['config']);
$sp = '';
$i = 0;
foreach ($arr as $u) {
$sp .= "<div id='delis".$site.$i."'>".$u."</div>";
$i++;
}
} else $sp = '';
echo "<h3>Аторизация</h3>
Логин: <input id='logins".$site."' value='".$result['title']."'><br>
Пароль: <input id='passs".$site."' value='".$result['text']."'><br>
<a href='#' onclick='parser(2,".$site.")'>Изменить</a> <hr>
<h3>Исключения</h3>
если url содержит исключение то он не будет просканирован<br>
Список:<br><div id='isres".$site."'>".$sp."</div>
<hr><a href='#' onclick='parser(7,".$site.")'>Удалить исключения</a>
<hr>Добавить исключение: <input id='is".$site."'><a href='#' onclick='parser(4,".$site.")'>Добавить</a>
<hr><center>
<button type='submit' onclick='close_site(".$site.")' class='medium green'><span class='mr-2 icon white medium' data-icon='c' style='display: inline-block; '></span>Закрыть</button><hr>
<a href='/sys.php?op=parser_del&id=".$site."'>
<button type='submit' class='medium black'><span class='mr-2 icon red medium' data-icon='c' style='display: inline-block; '></span>Удалить сайт</button>
</a></center>";
exit;
}
#####################
if ($action == 4) { // Добавляем исключения
if ($_REQUEST['login'] == '') { echo 'Введите исключение'; exit; }
$login = $_REQUEST['login'];
$result = $db->sql_fetchrow($db->sql_query("SELECT config FROM ".$prefix."_parser where id='".$site."'"));
if (mb_substr_count($result['config'],$login) > 0) { echo 'Такое исключение уже есть';  exit; }
if ($result['config'] == '') {
$db->sql_query("UPDATE ".$prefix."_parser SET config = '".trim($login)."' WHERE id='".$site."'") or die ('Ошибка: Не удалось сохранить. 4');
echo "<div id='delis".$site."1'>".$login."</div>";
exit;
} else {
$db->sql_query("UPDATE ".$prefix."_parser SET config = '".$result['config']."|".trim($login)."' WHERE id='".$site."'") or die ('Ошибка: Не удалось сохранить. 4');
$arr = explode('|', $result['config']."|".$login);
$i = 0;
foreach ($arr as $u) {
echo "<div id='delis".$site.$i."'>".$u."</div>";
$i++;
}
exit;
}
}
#####################
if ($action == 5) { // сканер сайта
$urls = array(); $vs = array();
$res = $db->sql_fetchrow($db->sql_query("SELECT * FROM ".$prefix."_parser where id='".$site."'"));
if ($res['title'] and $res['text']) $avto = true; else $avto = false; // Устанавливаем авторизацию
$vs = file('files/'.$res['url']); // всего нашли урл
$go = file('files/go'.$res['url']); // обработали
$vsego = count($vs); // сколько всего сканер нашел страниц
$gotov=  count($go); // сколько страниц обработал
if ($gotov == 0 ) 
 $urls = $vs;
else {
$ot = array_diff ($vs, $go); // Здесь то что еще не обработали
reset($ot);
$urls = array_slice($ot, 0, 10); // берем только 10 урл для обработки
}
unset($vs,$go);
if ($vsego !== $gotov) {
// Массив адресов получили - парсим
$res3 = mpars($urls,$res['title'],$res['text'],$avto,'http://'.$res['url']);
// Пишем в базу страницы
$count = count($res3);
$str = '';
for($i=0;$i<$count;$i++) {
$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','s".$res['id']."','".$urls[$i]."','".addslashes($res3[$i])."','0','0')") or die ('Ошибка: Не удалось сохранить. 5-2');
$file = fopen ('files/go'.$res['url'],"a+");
fwrite($file, $urls[$i]);
fclose ($file);
$str .= $res3[$i];// Ложим страницы в одну строку для поиска ссылок
}
preg_match_all('/(<a[^>]*)href=(\"?)([^\s\">]+?)(\"?)([^>]*>)/ismU',$str,$res4);
$a = $res4[0];
$c = count($a);
// список доменных зон
$domain = explode('|','.aero|.localhost|.arpa|.asia|.biz|.cat|.com|.coop|.edu|.gov|.info|.int|.jobs|.mil|.mobi|.museum|.name|.net|.org|.post|.pro|.tel|.travel|.xxx|.ac|.ad|.ae|.af|.ag|.ai|.al|.am|.an|.ao|.aq|.ar|.as|.at|.au|.aw|.ax|.az|.ba|.bb|.bd|.be|.bf|.bg|.bh|.bi|.bj|.bl|.bm|.bn|.bo|.bq|.br|.bs|.bt|.bv|.bw|.by|.bz|.ca|.cc|.cd|.cf|.cg|.ch|.ci|.ck|.cl|.cm|.cn|.co|.cr|.cu|.cv|.cw|.cx|.cy|.cz|.de|.dj|.dk|.dm|.do|.dz|.ec|.ee|.eg|.eh|.er|.es|.et|.eu|.fi|.fj|.fk|.fm|.fo|.fr|.ga|.gb|.gd|.ge|.gf|.gg|.gh|.gi|.gl|.gm|.gn|.gp|.gq|.gr|.gs|.gt|.gu|.gw|.gy|.hk|.hm|.hn|.hr|.ht|.hu|.id|.ie|.il|.im|.in|.io|.iq|.ir|.is|.it|.je|.jm|.jo|.jp|.ke|.kg|.kh|.ki|.km|.kn|.kp|.kr|.kw|.ky|.kz|.la|.lb|.lc|.li|.lk|.lr|.ls|.lt|.lu|.lv|.ly|.ma|.mc|.md|.me|.mf|.mg|.mh|.mk|.ml|.mm|.mn|.mo|.mp|.mq|.mr|.ms|.mt|.mu|.mv|.mw|.mx|.my|.mz|.na|.nc|.ne|.nf|.ng|.ni|.nl|.no|.np|.nr|.nu|.nz|.om|.pa|.pe|.pf|.pg|.ph|.pk|.pl|.pm|.pn|.pr|.ps|.pt|.pw|.py|.qa|.re|.ro|.rs|.ru|.rw|.sa|.sb|.sc|.sd|.se|.sg|.sh|.si|.sj|.sk|.sl|.sm|.sn|.so|.sr|.ss|.st|.su|.sv|.sx|.sy|.sz|.tc|.td|.tf|.tg|.th|.tj|.tk|.tl|.tm|.tn|.to|.tp|.tr|.tt|.tv|.tw|.tz|.ua|.ug|.uk|.um|.us|.uy|.uz|.va|.vc|.ve|.vg|.vi|.vn|.vu|.wf|.ws|.ye|.yt|.za|.zm|.zw|.рф|.испытание|.срб|.укр|.мон|.бг');
$s = file_get_contents('files/'.$res['url']);
 for($i=0;$i<$c ;$i++) {
       $ur = strstr($a[$i], "href=");
	   if(mb_strpos($ur, ' ') === true)$ur = mb_substr($ur, 0, mb_strpos($ur, ' '));
	   if(mb_strpos($ur, ' ') === false) $ur = str_replace('>', '', $ur);
	   if ( mb_substr_count($ur,"href=") == 1 || mb_substr_count($ur,"http") == 1) {
	   $ur = str_replace(array('href=','http://','www.',',','"',"'"), '', $ur);
	   // если урл правильный и это не файл
	if (mb_substr_count($ur,'#') == 0 and $ur !== '/' and $ur !=='' and $ur !==' ' and
	     mb_substr_count($ur,".zip") == 0 and 
	     mb_substr_count($ur,".rar") == 0 and 
		 mb_substr_count($ur,".doc") == 0 and 
		 mb_substr_count($ur,".docx") == 0 and 
		 mb_substr_count($ur,".txt") == 0 and 
		 mb_substr_count($ur,".mp3") == 0 and 
		 mb_substr_count($ur,".mp4") == 0) {
	if (mb_substr($ur, 0, 1) == '/' and mb_substr_count($s,$res['url'].$ur) == 0) { //    /index.htm нашли и пишем site.ru/index.htm
	$s .= $res['url'].$ur; 
	 $filestr = fopen ('files/'.$res['url'],"a+");
     fwrite($filestr, $res['url'].$ur."\n");
     fclose ($filestr);
	} 
    if (mb_substr($ur, 0, 1) !== '/')	{
	$nn = false;
	foreach ($domain as $d) {
	   if (mb_substr_count($ur,$d) == 1) $nn = true;
	   }
	   // нашли доменную зону и это ссылка на наш сайт
	   if ( $nn === true and mb_substr_count($ur,$res['url']) == 1 and mb_substr_count($s,$ur) == 0) {// - нашли .ru и пишем site.ru/index.htm
	$s .= $ur;
	 $filestr = fopen ('files/'.$res['url'],"a+");
     fwrite($filestr, $ur."\n");
     fclose ($filestr);
	 }
	 // не нашли доменную зону
	   if ( $nn === false and mb_substr_count($s,$res['url'].'/'.$ur) == 0 ) { // - добавляем слеш пишем site.ru/index.htm
	 $s .= $res['url'].'/'.$ur;
	 $filestr = fopen ('files/'.$res['url'],"a+");
     fwrite($filestr, $res['url'].'/'.$ur."\n");
     fclose ($filestr);
	 }}

	 }
	   } 
}
}
if($vsego !== $gotov) echo 'Страниц найденно - '.$vsego.' Страниц обработанно - '.$gotov; // еще не закончили - продолжаем
if($vsego == $gotov) echo 'Страниц готово - '.$vsego.' Конец работы';  //Все готово - остановка
}
#####################
if ($action == 6) { // Добавляем шаблон
$v = $db->sql_numrows($db->sql_query("SELECT url FROM ".$prefix."_parser where class='h' and url='".$site."'"));
if ($v > 0) { echo 'Имя '.$site.' уже есть в базе'; exit; }
// Добавляем сам шаблон
$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','h','".$site."','','','')") or die ('Ошибка: Не удалось сохранить. 6');
$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','h".$site."','Заголовок','false','title|plaintext','0')") or die ('Ошибка: Не удалось сохранить. 6-2');
$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','zh".$site."','[Заголовок]','','','')") or die ('Ошибка: Не удалось сохранить. 6-3');
$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','oh".$site."','[Ваше правило] и текст','','','')") or die ('Ошибка: Не удалось сохранить. 6-4');
$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','mh".$site."','[Ваше правило] и текст','','','')") or die ('Ошибка: Не удалось сохранить. 6-5');
$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','kh".$site."','true','','','')") or die ('Ошибка: Не удалось сохранить. 6-6');
$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','dh".$site."','true','','','')") or die ('Ошибка: Не удалось сохранить. 6-7');
$res = $db->sql_query("SELECT id, url FROM ".$prefix."_parser where class='h'");
while ($row = $db->sql_fetchrow($res)) {
echo '<b>'.$row['url'].'</b>   <a href="/sys.php?op=parser_temp&id='.$row['id'].'">Редактор</a>   <a href="/sys.php?op=parser_temp_del&id='.$row['id'].'">Удалить</a><hr>';
}}
#####################
if ($action == 7) { // Удаляем исключения
echo "";
$db->sql_query("UPDATE ".$prefix."_parser SET config = '' WHERE id='".$site."'") or die ('Ошибка: Не удалось сохранить. 7');
exit;
}
if ($action == 8) { // Добавляем правило
$nomer = $_REQUEST['nomer']; // Номер по счету
$ishod = $_REQUEST['ishod']; // Где ищем
$name = $_REQUEST['name']; // Название правила
$elem = $_REQUEST['elem']; // Элемент
$metod = $_REQUEST['metod']; // Что получить
$res = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where id='".$site."'"));
$v = $db->sql_numrows($db->sql_query("SELECT id FROM ".$prefix."_parser where url='".$name."' and class='h".$res['url']."'"));
if ($v > 0) { echo 'Такое правило уже есть';  exit; }
$db->sql_query("INSERT INTO ".$prefix."_parser (id, class, url, title, text, config) VALUES ('','h".$res['url']."','".$name."','".$nomer."','".$elem."|".$metod."','".$ishod."')") or die ('Ошибка: Не удалось сохранить. 8');
echo "<table width='100%'><tr><td>Правило</td><td>Использует</td><td>Номер эл-та</td><td>Элемент</td><td>Извлекаем</td><td></td><td></tr>";
$res2 = $db->sql_query("SELECT * FROM ".$prefix."_parser where class='h".$res['url']."'");
while ($row = $db->sql_fetchrow($res2)) {
$el = explode('|',$row['text']);
echo '<tr><td>'.$row['url'].'</td><td>'.$row['config'].'</td><td>'.$row['title'].'</td><td>'.$el[0].'</td><td>'.$el[1].'</td><td><a href="#" onclick="parser(9,'.$row['id'].')">Удалить</a></td><td></tr>';
}
echo "</table>";
exit;
}
if ($action == 9) { // Удаляем правило
$res = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where id='".$site."'"));
$db->sql_query("DELETE FROM ".$prefix."_parser WHERE id='".$site."'");
$db->sql_query("DELETE FROM ".$prefix."_parser WHERE config='".$site."' and class='h".$res['url']."'");
echo '<meta http-equiv="Refresh" content="1"/>';
exit;
}
if ($action == 10) { // оставшееся проект
require_once("includes/simple_html_dom.php");
$r = '';
$res = $db->sql_query("SELECT url, title FROM ".$prefix."_parser where class='s".$site."' limit 100");
while ($row = $db->sql_fetchrow($res)) {
$html = str_get_html(stripslashes($row['title']));
$title = $html->find('title',0)->plaintext;
$r .= "<a href='http://".$row['url']."'>".$title."</a><br>";
}
echo $r;
exit;
}
if ($action == 11) { // пример проект
$esli = $_REQUEST['esli']; // ищем в урл или странице
$sodr = $_REQUEST['sodr']; // что ищем
if($esli == 0 ) $es = "url LIKE '%".$sodr."%'";
if($esli == 1 ) $es = "title LIKE = %".$sodr."%";  
require_once("includes/simple_html_dom.php");

$res = $db->sql_query("SELECT id, title, url FROM ".$prefix."_parser where class='s".$site."' and ".$es." limit 100");
while ($row = $db->sql_fetchrow($res)) {
$html = str_get_html(stripslashes($row['title']));
$title = $html->find('title',0)->plaintext;
$r .= "<a href='http://".$row['url']."'>".$esli."|".$sodr."</a><br>";
}
echo $r;
exit;
}
if ($action == 12) { // запись из паука
$esli = $_REQUEST['esli']; // ищем в урл или странице
$sodr = $_REQUEST['sodr']; // что ищем
$imv = $_REQUEST['imv']; // куда пишем
$sh = $_REQUEST['sh']; // шаблон для разбора страницы
$plag = $_REQUEST['plag']; // использовать уникализатор
if($esli == 0 ) $es = 'url';
if($esli == 1 ) $es = 'title';  
require_once("includes/simple_html_dom.php");
$s = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where id='".$sh."'"));
$pravila = $db->sql_query("SELECT * FROM ".$prefix."_parser where class='h".$s['url']."'");
$sz = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where class='zh".$s['url']."'"));
$so = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where class='oh".$s['url']."'"));
$sm = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where class='mh".$s['url']."'"));
$sk = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where class='kh".$s['url']."'"));
$sd = $db->sql_fetchrow($db->sql_query("SELECT url FROM ".$prefix."_parser where class='dh".$s['url']."'"));
$res = $db->sql_query("SELECT url, title FROM ".$prefix."_parser where class='s".$site."' and ".$es." LIKE '%$sodr%' limit 30");
while ($row = $db->sql_fetchrow($res)) {
$html = str_get_html(stripslashes($row['title']));
$title = $html->find('title',0)->plaintext;
$sql = "INSERT INTO ".$prefix."_".$tip." VALUES (NULL, '".$module."', '".$cid."', '".$title."', '".$open_text."', '".$main_text."', '".$data."', '".$data2."', '0', '".$active."', '0', '0', '".$foto."', '".$search."', '".$mainpage."', '".$rss."', '".$price."', '".$description2."', '".$keywords2."', 'pages', '0','".$sor."', '".$nocomm."');";

}
exit;
}
function mpars($data,$log,$pass,$avto,$site) {
if ($avto === true) {
$post_data['login'] = $log;
$post_data['pas'] = $pass;
foreach ( $post_data as $key => $value) {
    $post_items[] = $key . '=' . $value;
}
$post_string = implode ('&', $post_items);
}
  $curls = array();
  $result = array();
  $mh = curl_multi_init();
  foreach ($data as $id => $d) {
    $curls[$id] = curl_init();
        $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
  curl_setopt($curls[$id], CURLOPT_URL, $url);
  curl_setopt($curls[$id], CURLOPT_HEADER,0);
  curl_setopt($curls[$id], CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curls[$id], CURLOPT_FOLLOWLOCATION, 1);
if ($avto === true) {
  curl_setopt($curls[$id], CURLOPT_POST,true);
  curl_setopt($curls[$id], CURLOPT_POSTFIELDS, $post_string);
}  
  curl_setopt($curls[$id], CURLOPT_REFERER, $site);
  curl_setopt($curls[$id], CURLOPT_CONNECTTIMEOUT, 30);
  curl_setopt($curls[$id], CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_multi_add_handle($mh, $curls[$id]);
  }
  $running = null;
  do { curl_multi_exec($mh, $running); } while($running > 0);
  foreach($curls as $id => $c) {
    $result[$id] = curl_multi_getcontent($c);
    curl_multi_remove_handle($mh, $c);
  }
  curl_multi_close($mh);
  return $result;
}
?>
