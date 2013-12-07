<?php
function golos_show($pid, $golostype, $gol) {
	global $prefix, $db, $DBName;
	$result2 = $db->sql_query("SELECT `golos` FROM ".$prefix."_pages_golos WHERE `num`='".$pid."'");
    //$golos_id = $prefix.'golos'.$pid;
    //if (isset($golos_id) and isset($GLOBALS[$golos_id])) $tmp = $GLOBALS[$golos_id]; else $tmp = "";
    $golo = array();
    while($row2 = $db->sql_fetchrow($result2)) { $golo[] = $row2['golos']; }
    $golos_user = count($golo);
    
	$echo = "<div id='golos".$pid."' class='page_rating'>";
    switch ($golostype) {
		case 0:
		if ($golos_user == 0) $proc = 0;
	    else $proc = array_sum($golo)/$golos_user*10;
	    $stars = number_format( $proc/ 10, 2 );
	    $stars0 = number_format( $stars );
	    $golos_checked = array("","","","","");
	    $golos_checked[$stars0-1] = " checked";
	    $echo .= "<div class='back_icon2'><span class='star-rating star-rating".$pid."'>
	      <input type='radio' name='rating".$pid."' title='".ss("Ужасно")."' value='1'".$golos_checked[0]."><i></i>
	      <input type='radio' name='rating".$pid."' title='".ss("Плохо")."' value='2'".$golos_checked[1]."><i></i>
	      <input type='radio' name='rating".$pid."' title='".ss("Удовлетворительно")."' value='3'".$golos_checked[2]."><i></i>
	      <input type='radio' name='rating".$pid."' title='".ss("Хорошо")."' value='4'".$golos_checked[3]."><i></i>
	      <input type='radio' name='rating".$pid."' title='".ss("Отлично")."' value='5'".$golos_checked[4]."><i></i>
	    </span></div>
	    <script>$('.star-rating".$pid." :radio').click( function(){ page_golos(".$pid.",'".$DBName."',this.value,".$golostype.") } )</script>";
	    if ($golos_user > 0) 
	    	$echo .= "<div class='ico_rating back_icon' title='".ss("Оценка")."'>".$stars." ".ss("из 5")."</div><div class='ico_user back_icon' title='".ss("Количество проголосовавших")."'>".$golos_user."</div>";
		else 
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