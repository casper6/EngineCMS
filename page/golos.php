<?php
function golos_show($pid, $golostype, $gol, $golos_admin=0) {
	global $prefix, $db, $DBName;
	
	if ($golostype != 1) {
		$result2 = $db->sql_query("SELECT `golos` FROM ".$prefix."_pages_golos WHERE `num`='".$pid."'");
	    $golo = array();
	    while($row2 = $db->sql_fetchrow($result2)) $golo[] = $row2['golos'];
	    $golos_user = count($golo);
	}
    if ($golos_admin == 1 && ($golostype == 1 || $golostype == 2 || $golostype == 3)) $golostype = 0;
	$echo = "<div id='golos".$pid."' class='page_rating'>";

	$golos_id = $prefix.'golos'.$pid;
	if (isset($GLOBALS[$golos_id])) $tmp = $GLOBALS[$golos_id]; else $tmp = "";

    switch ($golostype) {
    	case 4: // Оценка(3 варианта)
    	case 5: // Оценка(5 вариантов)
    	case 6: // Оценка(10 вариантов)
    	if ($golos_user == 0) $proc = 0;
	    else $proc = array_sum($golo)/$golos_user*10;
	    $stars = number_format( $proc/ 10, 2 );
	    if ($golostype == 4) { $golos_title = explode(",",ss("Плохо,Удовлетворительно,Хорошо")); $from = 3; }
	    if ($golostype == 5) { $golos_title = explode(",",ss("Ужасно,Плохо,Удовлетворительно,Хорошо,Отлично")); $from = 5; }
	    if ($golostype == 6) { $golos_title = explode(",",ss("1 - Ужасно,2,3,4 - Плохо,5,6 - Удовлетворительно,7,8 - Хорошо,9,10 - Отлично")); $from = 10; }
		$ip = getenv("REMOTE_ADDR");
		$num = $db->sql_query("SELECT `golos` FROM ".$prefix."_pages where `pid`='".$pid."' and `ip`='".$ip."'");
		if ($golos_admin == 0) {
			if ($db->sql_numrows($num) == 0 && $tmp!=$golos_id && $pid>0) {
	    	$echo .= "<div class='back_icon2'><select name='rating".$pid."' class='select-rating".$pid."'><option value='0'>".ss("выберите вариант")."</option><option value='1'>".$golos_title[0]."</option><option value='2'>".$golos_title[1]."</option><option value='3'>".$golos_title[2]."</option>";
	      	if ($golostype == 5 || $golostype == 6) $echo .= "<option value='4'>".$golos_title[3]."</option><option value='5'>".$golos_title[4]."</option>";
	      	if ($golostype == 6) $echo .= "<option value='6'>".$golos_title[5]."</option><option value='7'>".$golos_title[6]."</option><option value='8'>".$golos_title[7]."</option><option value='9'>".$golos_title[8]."</option><option value='10'>".$golos_title[9]."</option>";
		    $echo .= "</select></div><script>$('.select-rating".$pid."').change( function(){ if (this.value != '0') page_golos(".$pid.",'".$DBName."',this.value,".$golostype.") } )</script>";
			}
		} else $stars = $gol;
	    if ($golos_user > 0 || $golos_admin == 1) {
	    	$echo .= "<div class='ico_rating back_icon' title='".ss("Оценка")."'>".$stars." ".ss("из")." ".$from."</div>";
	    	if ($golos_admin == 0) $echo .= "<div class='ico_user back_icon' title='".ss("Количество проголосовавших")."'>".$golos_user."</div>";
		} else 
			$echo .= "<div class='ico_rating back_icon' title='".ss("Количество проголосовавших")."'>".ss("Никто не голосовал")."</div>";
		break;

		case 0: // 5 вариантов
		if ($golos_user == 0) $proc = 0;
	    else $proc = array_sum($golo)/$golos_user*10;
	    $stars = number_format( $proc/ 10, 2 );
	    $stars0 = number_format( $stars );
	    $golos_checked = array("","","","","");
	    $golos_checked[$stars0-1] = " checked";
	    if ($golos_admin == 0) $echo .= "<div class='back_icon2'><span class='star-rating star-rating".$pid."'>
	      <input type='radio' name='rating".$pid."' title='".ss("Ужасно")."' value='1'".$golos_checked[0]."><i></i>
	      <input type='radio' name='rating".$pid."' title='".ss("Плохо")."' value='2'".$golos_checked[1]."><i></i>
	      <input type='radio' name='rating".$pid."' title='".ss("Удовлетворительно")."' value='3'".$golos_checked[2]."><i></i>
	      <input type='radio' name='rating".$pid."' title='".ss("Хорошо")."' value='4'".$golos_checked[3]."><i></i>
	      <input type='radio' name='rating".$pid."' title='".ss("Отлично")."' value='5'".$golos_checked[4]."><i></i>
	    </span></div>
	    <script>$('.star-rating".$pid." :radio').click( function(){ page_golos(".$pid.",'".$DBName."',this.value,".$golostype.") } )</script>";
	    else $stars = $gol;
	    if ($golos_user > 0 || $golos_admin == 1) {
	    	$echo .= "<div class='ico_rating back_icon' title='".ss("Оценка")."'>".$stars." ".ss("из")." 5</div>";
	    	if ($golos_admin == 0) $echo .= "<div class='ico_user back_icon' title='".ss("Количество проголосовавших")."'>".$golos_user."</div>";
		} else 
			$echo .= "<div class='ico_rating back_icon' title='".ss("Количество проголосовавших")."'>".ss("Никто не голосовал")."</div>";
		break;

	    case 1:
	    $echo .= "<div class='ico_like back_icon' title='".ss("Проголосовать")."'><a onclick=\"page_golos(".$pid.",'".$DBName."',1,".$golostype.")\" title='".ss("Проголосовать")."' class='pointer'>".ss("Проголосовать")."</a></div><div class='ico_rating back_icon' title='".ss("Количество проголосовавших")."'>".$gol."</div>"; break;

	    case 2: 
	    $echo .= "<div class='back_icon2'><a onclick=\"page_golos(".$pid.",'".$DBName."',1,".$golostype.")\" title='".ss("Проголосовать")." ".ss("ЗА")."' class='pointer'><span class='i16 ico_plus'></span></a> <a onclick=\"page_golos(".$pid.",'".$DBName."',0,".$golostype.")\" title='".ss("Проголосовать")." ".ss("ПРОТИВ")."' class='pointer'><span class='i16 ico_minus'></span></a></div> <div class='ico_rating back_icon' title='".ss("Рейтинг")."'>".$gol."</div> <div class='ico_user back_icon' title='".ss("Количество проголосовавших")."'>".$golos_user."</div>"; break;

	    case 3: 
	    $echo .= "<div class='back_icon2'><a onclick=\"page_golos(".$pid.",'".$DBName."',1,".$golostype.")\" title='".ss("Мне понравилось")."' class='pointer'><span class='i16 ico_like'></span></a> <a onclick=\"page_golos(".$pid.",'".$DBName."',0,".$golostype.")\" title='".ss("Мне не понравилось")."' class='pointer'><span class='i16 ico_like rotate180'></span></a></div> <div class='ico_rating back_icon' title='".ss("Рейтинг")."'>".$gol."</div> <div class='ico_user back_icon' title='".ss("Количество проголосовавших")."'>".$golos_user."</div>"; break;
	}
	return $echo."</div>";
}
?>