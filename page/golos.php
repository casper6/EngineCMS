<?php
function golos_show($pid, $golostype, $gol) {
	global $prefix, $db, $DBName;
	$result2 = $db->sql_query("SELECT `golos` FROM ".$prefix."_pages_golos WHERE `num`='".$pid."'");
    //$golos_id = $prefix.'golos'.$pid;
    //if (isset($golos_id) and isset($GLOBALS[$golos_id])) $tmp = $GLOBALS[$golos_id]; else $tmp = "";
    $golo = array();
    while($row2 = $db->sql_fetchrow($result2)) { $golo[] = $row2['golos']; }
    $golos_user = count($golo);
    
	$echo = "<div id='golos".$pid."' class='golosa'>";
    switch ($golostype) {
		case 0:
		if ($golos_user == 0) $proc = 0;
	    else $proc = array_sum($golo)/$golos_user*10;
	    $stars = number_format( $proc/ 10, 2 );
	    $stars0 = number_format( $stars );
	    $golos_checked = array("","","","","");
	    $golos_checked[$stars0-1] = " checked";
	    $echo .= "<span class='star-rating star-rating".$pid."'>
	      <input type='radio' name='rating".$pid."' value='1'".$golos_checked[0]."><i></i>
	      <input type='radio' name='rating".$pid."' value='2'".$golos_checked[1]."><i></i>
	      <input type='radio' name='rating".$pid."' value='3'".$golos_checked[2]."><i></i>
	      <input type='radio' name='rating".$pid."' value='4'".$golos_checked[3]."><i></i>
	      <input type='radio' name='rating".$pid."' value='5'".$golos_checked[4]."><i></i>
	    </span>
	    <script>$('.star-rating".$pid." :radio').click( function(){ page_golos(".$pid.",'".$DBName."',this.value,".$golostype.") } )</script>";
	    if ($golos_user > 0) 
	    	$echo .= "<span class='golos' title='".ss("Оценка:")."' />".$stars." ".ss("из 5")."</span><span class='golos_user' title='".ss("Голосовали:")."' />".$golos_user."</span>";
		else 
			$echo .= "<span class='golos_user' title='".ss("Голосовали:")."' />".ss("Никто не голосовал")."</span>";
		break;

	    case 1:
	    $echo .= "<a onclick=\"page_golos(".$pid.",'".$DBName."',1,".$golostype.")\" title='".ss("Проголосовать")."' style='cursor:pointer;'><img src=\"/images/sys/102.png\" width=16 style='margin-right:3px;' /><u>".ss("Проголосовать")."</u></a> <img src=\"/images/sys/082.png\" width=16 style='margin-right:3px;' title='".ss("Голоса:")."' /><b>".$gol."</b></nobr>"; break;

	    case 2: 
	    $echo .= "<a onclick=\"page_golos(".$pid.",'".$DBName."',1,".$golostype.")\" title='+1' style='cursor:pointer;'><img src=\"/images/sys/add_2.png\" width=16 style='margin-right:5px;' /></a> <a onclick=\"page_golos(".$pid.",'".$DBName."',0,".$golostype.")\" title='-1' style='cursor:pointer;'><img src=\"/images/sys/minus.png\" width=16 style='margin-right:3px;' /></a> <img src=\"/images/sys/082.png\" width=16 style='margin-right:5px;' title='".ss("Рейтинг:")."' /><b>".$gol."</b> <img src=\"/images/sys/007.png\" width=16 style='margin-right:3px;' title='".ss("Голосовали:")."' /><b>".$golos_user."</b>"; break;

	    case 3: 
	    $echo .= "<a onclick=\"page_golos(".$pid.",'".$DBName."',1,".$golostype.")\" title='".ss("Мне понравилось")."' style='cursor:pointer; margin-right:10px;'><img src=\"/images/icon_yes.png\" width=16 style='margin-right:3px;' /></a> <a onclick=\"page_golos(".$pid.",'".$DBName."',0,".$golostype.")\" title='".ss("Мне не понравилось")."' style='cursor:pointer;'><img src=\"/images/icon_no.png\" width=16 style='margin-right:10px;' /></a> <img src=\"/images/sys/082.png\" width=16 style='margin-right:3px;' title='".ss("Рейтинг:")."' /><b>".$gol."</b> <img src=\"/images/sys/007.png\" width=16 style='margin-right:3px;' title='".ss("Голосовали:")."' /><b>".$golos_user."</b>"; break;
	}
	return $echo."</div>";
}
?>