<?php
require_once("mainfile.php");
global $soderganie, $tip, $DBName, $prefix, $db, $module_name, $ModuleName, $admin, $opros_type;
if (isset($diag)) $diag = intval($diag); else $diag = 0;
	require_once "includes/JsHttpRequest/JsHttpRequest.php";
	$JsHttpRequest =& new JsHttpRequest("utf-8");
if (isset($_REQUEST['purse'])) $purse_type = $_REQUEST['purse']; else $purse_type = 0;
if (isset($_REQUEST['amount'])) $amount = intval($_REQUEST['amount']); else $amount = 0;
if (isset($_REQUEST['id'])) $id = intval($_REQUEST['id']); else $id = 0;
if (isset($_REQUEST['pass'])) $pass = $_REQUEST['pass']; else $pass = 0;
if (isset($_REQUEST['razdel'])) $razdel = $_REQUEST['razdel']; else $razdel = 0; // нужна защита!!!
if (isset($_REQUEST['base'])) $base = intval($_REQUEST['base']); else $base = 0;
if (isset($_REQUEST['savegolos'])) $savegolos = intval($_REQUEST['savegolos']); else $savegolos = 0; // pid
if (isset($_REQUEST['gol'])) $gol = $_REQUEST['gol']; else $gol = 0;
if (isset($_REQUEST['opros'])) $opros = $_REQUEST['str']; else $opros = 0;
if (isset($_REQUEST['num'])) $num = $_REQUEST['id']; else $num = 0;
if (isset($_REQUEST['symbol'])) $symbol = $_REQUEST['symbol']; else $symbol = 0;

if (isset($golos_id) and isset($GLOBALS[$golos_id])) $tmp = $GLOBALS[$golos_id]; else $tmp = ""; // поставлено временно от голосования...

//if ($gol > 5 or $gol < 1) $gol = 1;
//////////////////////////////////////////////////////////////////////////////////////////
$info = "";
	if ($savegolos > 0) {
		//$bangolosdays = 30;
		$purse_type = intval($purse_type);
		$ip = getenv("REMOTE_ADDR"); // IP
		$num = intval($savegolos);
		//$gol = intval($gol);
		if ($gol == 6 and is_admin($admin)) {
			$db->sql_query("UPDATE ".$prefix."_pages SET golos='0' WHERE pid='$num';");
			$db->sql_query("DELETE from ".$prefix."_pages_golos WHERE num='$num';");
			$info = "Голоса удалены.";
			$GLOBALS['_RESULT'] = array("savegolos" => $info,);
			exit;
		}
		//} else {
			if ($gol>5) $gol=1; 
			if ($gol<0) $gol=1;
			if ($purse_type != 2 and $purse_type != 3) $gol = intval($gol);
			$dat = date("Y.m.d H:i:s");
			$golos_id = $prefix.'golos'.$num;

//
			
			if ($purse_type == 0) { if ($gol != 1 and $gol != 2 and $gol != 3 and $gol != 4 and $gol != 5 ) $gol = 5; }
			if ($purse_type == 1) { $gol = 1; }
			if ($purse_type == 2 or $purse_type == 3) { if ($gol != 1) $gol = -1; }

				if ($purse_type != 0) { 
				$sql = "SELECT golos FROM ".$prefix."_pages where pid='$num'";
				$row2 = $db->sql_fetchrow($db->sql_query($sql));
				$resnum = $db->sql_query($sql);
				$numrows = $db->sql_numrows($resnum);
					if (($numrows > 0 and $tmp == $golos_id) or $num==0) {
						$info = "<b class=red>Вы уже голосовали!</b>";
					} else {
						$golos = $row2['golos'] + $gol;
						$db->sql_query("UPDATE ".$prefix."_pages SET golos='".$golos."' WHERE pid='$num';");
						$db->sql_query("INSERT INTO ".$prefix."_pages_golos (`gid`, `ip`, `golos`, `num`, `data`) VALUES ('', '$ip', '$gol', '$num', '$dat')");
						setcookie ("$golos_id", "$golos_id",time()+2678400,"/");
					}
				} else {
					$sql = "SELECT data FROM ".$prefix."_pages_golos WHERE ip='$ip' AND num='$num'";
					$resnum = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$date = $row['data'];
					$date2 = dateresize($date);
					$date = dateresize($dat);
					//$raznost = $date - $date2;
					$numrows = $db->sql_numrows($resnum);
					if ($numrows > 0 or $tmp==$golos_id or $num==0) {
						$info = "<b class=red>Вы уже голосовали.</b>";
						//if ($raznost<$bangolosdays) {
						//	$raznost = $bangolosdays-$raznost;
						//} else { // глюк!
							//$db->sql_query("INSERT INTO ".$prefix."_".$tip."_golos ( `gid` , `ip` , `golos`, `num`, `data`) VALUES ('', '$ip', '$gol', '$num', '$dat')");
						//}
					} else {
						//$info = "<b class=red>$purse_type - $gol</b>";
						$db->sql_query("INSERT INTO ".$prefix."_pages_golos (`gid`, `ip`, `golos`, `num`, `data`) VALUES ('', '$ip', '$gol', '$num', '$dat')");
						setcookie ("$golos_id", "$golos_id",time()+2678400,"/");
					}
				}
			// }
	$sqlX = "SELECT module from ".$prefix."_pages where pid = '$num'";
	$resultX = $db->sql_query($sqlX);
	$rowX = $db->sql_fetchrow($resultX);
	$mod = $rowX['module'];
	recash("/-".$mod."_page_".$num); // Обновление кеша ##
			
		if ($purse_type != 0) {
			$sql2 = "SELECT golos FROM ".$prefix."_pages where pid='$num'";
			$result2 = $db->sql_query($sql2);
			$row2 = $db->sql_fetchrow($result2);
			$golos = $row2['golos'];
		}
		if ($purse_type == 1) 
			$info .= " <b class=green>Спасибо за ваше неравнодушие!</b>";
			//<img src=\"/images/sys/102.png\" width=16 style='margin-right:3px;' title='Спасибо за ваш голос!' /> <img src=\"/images/sys/082.png\" width=16 style='margin-right:3px;' title='Голоса:' /><b>".$golos."</b>";
		if ($purse_type == 2 or $purse_type == 3) {
			$sql23 = "SELECT golos FROM ".$prefix."_pages_golos WHERE num='$num'";
			$result23 = $db->sql_query($sql23);
			$numrows23 = $db->sql_numrows($result23);
			$info .= " <b class=green>Спасибо за ваше неравнодушие!</b>";
			//<img src=\"/images/sys/102.png\" width=16 style='margin-right:3px;' title='Спасибо за ваш голос!' /> <img src=\"/images/sys/082.png\" width=16 style='margin-right:3px;' title='Рейтинг:' /><b>".$golos."</b> <img src=\"/images/sys/007.png\" width=16 style='margin-right:3px;' title='Голосовали:' /><b>".$numrows23."</b>";
		}
		if ($purse_type == 0) {
			$sql2 = "SELECT golos FROM ".$prefix."_pages_golos WHERE num='$num'";
			$result2 = $db->sql_query($sql2);
			$numrows2 = $db->sql_numrows($result2);
//
			$golo=array();
			while($row2 = $db->sql_fetchrow($result2)) {
				$golo[] = $row2['golos'];
			}
			$proc = array_sum($golo)/$numrows2*10;
			//$ser = (int) (intval(array_sum($golo))/$numrows2);
			$sersv = $proc*2;
			$sersv2 = number_format($proc,2)/10;
			$sersv1 = number_format($sersv2,2);
			$sersv = 90*$sersv/100;
			$info .= " <b class=green>Спасибо за ваше неравнодушие!</b>";
			//<br><table border=0 width=100% cellspacing=0 cellpadding=0><tr><td width=100><table border=0 width=90 cellspacing=0 cellpadding=0><tr><td style=\"background:url(/images/zvezda_hide.gif) #FF6600 ".$sersv."px repeat-y; width:90px; height:16px;\"><img src=\"/images/zvezda_golos.gif\" width=18 /><img src=\"/images/zvezda_golos.gif\" width=18 /><img src=\"/images/zvezda_golos.gif\" width=18 /><img src=\"/images/zvezda_golos.gif\" width=18 /><img src=\"/images/zvezda_golos.gif\" width=18 /></td></tr></table></td><td><img src=\"/images/sys/102.png\" width=16 style='margin-right:3px;' title='Спасибо за ваш голос!' /> <img src=\"/images/sys/082.png\" width=16 style='margin-right:3px;' title='Оценка:' /><b>".$sersv1." из 5</b> <img src=\"/images/sys/007.png\" width=16 style='margin-right:3px;' title='Голосовали:' /><b>".$numrows2."</b></td></tr></table>";
		}
	
	$GLOBALS['_RESULT'] = array("savegolos" => $info,);
	exit();
	}
//////////////////////////////////////////////////////////////////////////////////////////
	if ($id > 0 and $opros == "") {
		// Проверка наличия пароля
		if ($db->sql_numrows($db->sql_query("SELECT id FROM ".$prefix."_bases where base='$base' and pause='0' and pass='$pass'")) > 0) {
		// Вывод информации
		$sql3 = "SELECT name, text FROM ".$prefix."_mainpage where id='$base' and type='5'";
		$result3 = $db->sql_query($sql3); $row3 = $db->sql_fetchrow($result3);
		$base_name = $row3['name'];
		$options = $row3['text'];
		parse_str($options);
		$options = explode("/!/",$options); // ранее *
		$options_num = count($options);
		$names = array();
		$names_spisok = "";
		$rus_names = array();
		for ($x=0; $x < $options_num; $x++) {
			$option = explode("#!#",$options[$x]); // ранее !
			if ($option[4] == 3) {
				$names[] = $option[0]; // что берем в базе
				$rus_names[] = $option[1]; // по-русски
			}
		}
		$names_spisok = implode(", ", $names);
		$sql3 = "SELECT $names_spisok FROM ".$prefix."_base_".$base_name." where id='$id'";
		$result3 = $db->sql_query($sql3); $row3 = $db->sql_fetchrow($result3);
		//$base_name = $row3['name'];
		//$options = $row3['text'];
	
		session_start(); 
		$_SESSION['passpay'] = $pass; // store session data
		//echo "Pageviews = ". $_SESSION['views']; //retrieve data
		/*
		if(isset($_REQUEST['passpay'])){
		session_start();
		}
		if($_REQUEST['passpay']){
		$_SESSION['passpay'] = $pass;
		}
		*/
		$info = "Пароль введен верно.<br>";
		print_r($row3);
		foreach ($row3 as $key => $ro) {
		if ($rus_names[$key] != "") $info .= "<b>$rus_names[$key]:</b> $ro<br>";
		}

		//$info .= "<br>Для удобства ввода пароля при открытии нескольких позиций в базе данных - выделив, скопируйте (Ctrl + C) введенный пароль и вставляйте (Ctrl + V) его в поле ввода пароля.";
		
		} else $info = "Пароль введен неверно. Обновите страницу (нажав F5) и попробуйте еще раз.";
		$GLOBALS['_RESULT'] = array("id" => $info,);
		exit();
	}


	if ($diag > 0) { // Если это диаграмма
	$diag = intval($diag);
		// Получить из базы данных значения и прибавить к ним голоса
		$sql2 = "select text from ".$prefix."_mainpage where type='3' and name='5' and id='$diag'"; // `name` != '$name' and 
		$result2 = $db->sql_query($sql2);
		$row2 = $db->sql_fetchrow($result2);
		$textX = trim($row2['text']);
		$lines = explode("\r\n",str_replace("<i>","",str_replace("</i>","",str_replace("</b>","",str_replace("<br>","                     ",str_replace("<b>","",$textX))))));
	////////////////////////////// Показываем результаты
		$lines2 = array();
		$cols2 = array();
		foreach ($lines as $line_id => $line) {
			$line = explode("|",$line);
			$col = $line[1]; if ($col=="") $col=0;
			$line = $line[0];
			$lines2[] = $line;
			$cols2[] = $col;
		} // for закончился
		$sto = array_sum($cols2);
		
		foreach ($lines2 as $line_id => $line) {
			$proc = intval($cols2[$line_id] * 100 / $sto);
			// обрезание
			$pag_line = substr($line, 0, 16); // остается x символов
			if (strlen($pag_line)<strlen($line)) $pag_line .= "...";
			$lines2[$line_id] = $proc."% ".$pag_line;
			if ($cols2[$line_id]>0.9) $cols3[$line_id] = $cols2[$line_id];
		}
	arsort($cols3);
	natsort($lines2);
	$lines2 = array_reverse($lines2, TRUE);
	$cols3 = array_values($cols3);
	$lines2 = array_values($lines2);
	// Зададим значение и подписи
	$VALUES=$cols3;
	$LEGEND=$lines2;
	$size = 175;
	Diagramm($size,$VALUES,$LEGEND);
	}
#############################################################
function win2uni($s) {
$s = convert_cyr_string($s,'w','i');
// преобразование iso8859-5 -> unicode:
for ($result='', $i=0; $i<strlen($s); $i++) {
$charcode = ord($s[$i]);
$result .= ($charcode>175)?"&#".(1040+($charcode-176)).";":$s[$i];
}
return ($result);
}
#############################################################
function Diagramm($size,$VALUES,$LEGEND) {
// $im - идентификатор изображения
// $VALUES - массив со значениями
// $LEGEND - массив с подписями
// Создадим изображения
header("Content-Type: image/png");

// Посчитаем количество пунктов, от этого зависит высота легенды
$legend_count=count($LEGEND);

	// Получим размеры изображения
	$W=$size;                 
	$H=137+$legend_count*15;
	
$W = $W*2;
$H = $H*2;

$im=ImageCreate($W,$H);

// Зададим цвет фона. Немного желтоватый, для того, чтобы было
// видно границы изображения на белом фоне.
$bgcolor=ImageColorAllocate($im,255,255,255); // $im,255,255,220

// Зададим цвета элементов
$COLORS[14] = imagecolorallocate($im, 255, 18, 0);
$COLORS[13] = imagecolorallocate($im, 255, 120, 0);
$COLORS[12] = imagecolorallocate($im, 255, 244, 0);
$COLORS[11] = imagecolorallocate($im, 205, 252, 7);
$COLORS[10] = imagecolorallocate($im, 115, 223, 18);
$COLORS[9] = imagecolorallocate($im, 12, 182, 49);//
$COLORS[8] = imagecolorallocate($im, 0, 190, 100);
$COLORS[7] = imagecolorallocate($im, 0, 219, 171);//
$COLORS[6] = imagecolorallocate($im, 0, 234, 251);//
$COLORS[5] = imagecolorallocate($im, 0, 139, 218);//
$COLORS[4] = imagecolorallocate($im, 0, 54, 169);//
$COLORS[3] = imagecolorallocate($im, 0, 5, 144);//
$COLORS[2] = imagecolorallocate($im, 68, 0, 150);//
$COLORS[1] = imagecolorallocate($im, 192, 0, 181);//
$COLORS[0] = imagecolorallocate($im, 248, 3, 198);//
$COLORS[15] = imagecolorallocate($im, 211, 211, 211);//
$COLORS[16] = imagecolorallocate($im, 117, 117, 117);//
$COLORS[17] = imagecolorallocate($im, 33, 33, 33);

// Зададим цвета теней элементов
$SHADOWS[14] = imagecolorallocate($im, 215, 0, 0);
$SHADOWS[13] = imagecolorallocate($im, 255, 67, 0);
$SHADOWS[12] = imagecolorallocate($im, 255, 171, 0);
$SHADOWS[11] = imagecolorallocate($im, 127, 224, 4);
$SHADOWS[10] = imagecolorallocate($im, 66, 142, 12);
$SHADOWS[9] = imagecolorallocate($im, 8, 107, 29);//
$SHADOWS[8] = imagecolorallocate($im, 0, 112, 57);
$SHADOWS[7] = imagecolorallocate($im, 0, 138, 97);//
$SHADOWS[6] = imagecolorallocate($im, 0, 160, 224);//
$SHADOWS[5] = imagecolorallocate($im, 0, 82, 140);//
$SHADOWS[4] = imagecolorallocate($im, 0, 32, 97);//
$SHADOWS[3] = imagecolorallocate($im, 0, 4, 83);//
$SHADOWS[2] = imagecolorallocate($im, 42, 0, 87);//
$SHADOWS[1] = imagecolorallocate($im, 114, 0, 106);//
$SHADOWS[0] = imagecolorallocate($im, 191, 2, 119);//
$SHADOWS[15] = imagecolorallocate($im, 130, 130, 130);//
$SHADOWS[16] = imagecolorallocate($im, 68, 68, 68);//
$SHADOWS[17] = imagecolorallocate($im, 20, 20, 20);
$black=ImageColorAllocate($im,0,0,0);

	// Вывод легенды #####################################
	// Посчитаем максимальную длину пункта, от этого зависит ширина легенды
	$max_length = 0;
	foreach($LEGEND as $v) if ($max_length<strlen($v)) $max_length=strlen($v);

	// Номер шрифта, котором мы будем выводить легенду
	$FONT = 18;
	$font_w = 30;
	$font_h = 30;

	// Вывод прямоугольника - границы легенды ----------------------------
	$l_width = $W-20;
	$l_height = $font_h*$legend_count+40;

	// Получим координаты верхнего левого угла прямоугольника - границы легенды
	$l_x1 = 10;
	$l_y1 = 220;

	// Выводя прямоугольника - границы легенды
	ImageRectangle($im, $l_x1, $l_y1, $l_x1+$l_width, $l_y1+$l_height, $black);

	// Вывод текст легенды и цветных квадратиков
	$text_x = $l_x1+30+$font_h;
	$square_x = $l_x1+20;
	$y = $l_y1+20;

	$i=0;
	foreach($LEGEND as $v) {
		$dy = $y+($i*$font_h)+24;
		$v = win2uni($v);
// imagettftext($new_img, $fsize, 0, $xcord, $ycord, $pri_color, $font, $text);
   imagettftext($im, $FONT, 0, $text_x-5, $dy, $black, "./includes/arial.ttf", $v);
// imagettftext($im, $fs, 0, $x+1, $y-1, $black, "./calligraph.ttf", $text);
		//ImageString($im, $FONT, $text_x, $dy, $v, $black);
		$dy = $y+($i*$font_h);
		ImageFilledRectangle($im,
                             $square_x+1-5,$dy+2,$square_x+$font_h-2-5,$dy+$font_h-2,
                             $COLORS[$i]);
		ImageRectangle($im,
                       $square_x+1-5,$dy+2,$square_x+$font_h-2-5,$dy+$font_h-2,
                       $black);
		$i++;
		}

	// Вывод круговой диаграммы ----------------------------------------
	$total = array_sum($VALUES);
	$anglesum = $angle=Array(0);
	$i = 1;

	// Расчет углов
	while ($i<count($VALUES)) {
		$part=$VALUES[$i-1]/$total;
		$angle[$i]=floor($part*360);
		$anglesum[$i]=array_sum($angle);
		$i++;
		}
	$anglesum[] = $anglesum[0];

	// Расчет диаметра
	$diametr = $W-20;

	// Расчет координат центра эллипса
	$circle_x = ($diametr/2)+10;
	$circle_y = 90;

	// Поправка диаметра, если эллипс не помещается по высоте
	//if ($diametr>($H*2)-10-10) $diametr=($H*2)-20-20-40;

	// Вывод тени
	for ($j=20;$j>0;$j--)
		for ($i=0;$i<count($anglesum)-1;$i++)
			ImageFilledArc($im,$circle_x,$circle_y+$j,
                               $diametr,$diametr/2,
                               $anglesum[$i],$anglesum[$i+1],
                               $SHADOWS[$i],IMG_ARC_PIE);

	// Вывод круговой диаграммы
	for ($i=0;$i<count($anglesum)-1;$i++)
		ImageFilledArc($im,$circle_x,$circle_y,
                           $diametr,$diametr/2,
                           $anglesum[$i],$anglesum[$i+1],
                           $COLORS[$i],IMG_ARC_PIE);
$imd = imagecreatetruecolor($W/2,$H/2);
imagecopyresampled($imd,$im,0,0,0,0,$W/2,$H/2,$W,$H);
imagedestroy($im);
imagepng($imd);
//imagedestroy($im);
//ImagePNG($im);
}
?>


