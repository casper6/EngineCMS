<?php
// Добавление табов
function show_tabs($bodytext) {
	global $jqueryui, $kickstart, $tab_obzor, $tab_show;
	if ($tab_show == "1") {
		$x = rand(0, 100000 - 1);
		$raz[0] = "{{"; $raz[1] = "}}";
		preg_match_all("/\\".$raz[0]."[^\\".$raz[1]."]+\\".$raz[1]."/s", $bodytext, $matches);
		$matches = $matches[0];
		$count_match = count($matches); // кол-во блоков
		if ($count_match > 0) {
		    $info_blocks = "";
		    $obzor = false;
		    $add = $add3 = $add4 = "";
		    if ($kickstart == 4) { 
		    	$names_block = "<nav class='nav-tabs' data-toggle='tabs' data-height='equal'><ul>"; 
		    	$add = "</nav>"; 
		    } elseif ($kickstart == 1) { 
		    	$names_block = "<ul class='tabs left'>"; 
		    	$add4 = " class='tab-content'";
		    } elseif ($jqueryui == true) {
		    	$names_block = "<script>$(function() { $('#tabs_".$x."').tabs(); });</script><div class=tabs id=tabs_".$x."><ul>"; 
		    	$add3 = "</div>";
		    }

		    for ( $i=0; $i < $count_match+1; $i++ ) { 
		      // Разделение блока
		      if (!isset($matches[$i])) $info_block = "";
		      else $info_block = explode($matches[$i],$bodytext);
		      $count_body = count($info_block);
		      // Первое разделение - поиск начального блока без названия
		      if ($i == 0) {
		      $ii = $i-1;
		            if (trim($info_block[0]) != "") {
		              $info_blocks .= "<div".$add4." id=\"tabs_".$x."-".$i."\">".$info_block[0]."</div>";
		              $names_block .= "<li><a class='active' href=\"#tabs_".$x."-".$i."\">".$tab_obzor."</a></li>";
		              $obzor = true;
		            }
		      } else {
		      if ($obzor == true) $ii = $i; else $ii = $i-1;
		        // Содержание блока
		        if ($count_match == $i) $info_blocks .= "<div".$add4." id=\"tabs_".$x."-".$ii."\">".$bodytext."</div>";
		        else $info_blocks .= "<div".$add4." id=\"tabs_".$x."-".$ii."\">".$info_block[0]."</div>";
		      }
		      $bodytext = "";
		      // Оставляем неисследованную оставшуюся часть текста
		      for ( $j=1; $j < $count_body; $j++ ) { 
		          $bodytext .= $info_block[$j];
		      }
		      // Название блока
		      if (!isset($matches[$i])) $name_block = "";
		      else $name_block = str_replace($raz[0],"",str_replace($raz[1],"",$matches[$i]));
		      if ($obzor == true) $iii = $i+1; else $iii = $i;
		      if ($iii == 0 && $kickstart == 4) $add2 = " class='active'"; else $add2 = "";
		      if (trim($name_block)!="") $names_block .= "<li><a".$add2." href=\"#tabs_".$x."-".$iii."\">".$name_block."</a></li>";
		    }
		    $names_block .= "</ul>".$add.$info_blocks.$add3."<br>";
		    $bodytext = "".$names_block;
		}
	}
	return $bodytext;
}
?>