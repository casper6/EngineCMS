<?php
if (!defined('MODULE_FILE')) {
  die (ss("У вас нет прав для доступа к этому файлу!"));
}
require_once("shablon.php");
$module_name = basename(dirname(__FILE__));
###########################################
// передается из header
global $prefix, $db, $soderganie, $soderganie2, $options, $ModuleName, $DBName; 
if ($DBName=="") $DBName="news";
if ($tip=="") $tip="mainpage";
if ($ModuleName=="") $ModuleName = ss("Содержание");

###########################################
// передается из любой страницы по ссылке
global $strelka, $go, $cid, $pid, $all, $avtor, $to, $info, $num, $ip, $golos, $getparent_cash;

########################################### проверить и возможно убрать кусок
// настройки раздела из БД
global $post, $comments, $datashow, $sort, $lim, $foto, $view, $search, $search_papka, $tema, $tema_name, $tema_title, $tema_opis, $menushow, $design; 

$media=$folder=$col=$view=$golos=$golosrazdel=$post=$comments=$datashow=$favorites=$socialnetwork=$search=$search_papka=$put_in_blog=$base=$vetki=$citata=$media_comment=$no_html_in_opentext=$no_html_in_text=$show_add_post_on_first_page=$media_post=$razdel_shablon=$page_shablon=$comments_all=$comments_num=$comments_mail=$comments_adres=$comments_tel=$comments_desc=$golostype=$pagenumbers=$comments_main=$tags_type=$tema_zapret_comm=$pagekol=$table_light=$designpages=$comments_add=$div_or_table=0;

$menushow=$titleshow=$razdel_link=$peopleshow=$design=$tags=$podrobno=$podrazdel_active_show=$podrazdel_show=$tipograf=$limkol=$tags_show=$tema_zapret=1;
$comment_shablon=2;

$sort="date desc";
$tema = ss("Открыть новую тему");
$tema_name = ss("Ваше имя");
$tema_title = ss("Название темы");
$tema_opis = ss("Подробнее (содержание темы)");
$comments_1 = ss("Комментарии");
$comments_2 = ss("Оставьте ваш вопрос или комментарий:");
$comments_3 = ss("Ваше имя:");
$comments_4 = ss("Ваш email:");
$comments_5 = ss("Ваш адрес:");
$comments_6 = ss("Ваш телефон:");
$comments_7 = ss("Ваш вопрос или комментарий:");
$comments_8 = ss("Раскрыть все комментарии");
$tag_text_show = ss("Ключевые слова");

$where=$order=$calendar=$reclama="";
$lim=20;

parse_str($options); // раскладка всех настроек модуля
###########################################
###########################################
###########################################
$getparent_cash = array();
###########################################
function top_menu($cid, $page) {
  $ret = "";
  global $strelka, $soderganie, $soderganie2, $DBName, $prefix, $db, $module_name, $ModuleName, $go, $pagetitle;
  global $golos, $post, $comments, $datashow, $sort, $lim, $folder, $media, $view, $search, $razdel_link, $podrazdel_active_show, $podrazdel_show, $reclama, $tema, $pagekol; // настройки модуля из БД
  $cid = mysql_real_escape_string($cid);
  $DBName = mysql_real_escape_string($DBName);
  $searchline1 = "";
  // Поиск
  if ($search == 1) $searchline1 .= search_line($DBName, $cid)."<br>";
  // На главную
  $c_title = array();
  $c_description = array();
  $c_parent = array();
  $c_title2 = array();
    $sql = "SELECT `parent_id` FROM ".$prefix."_pages_categories where module='$DBName' and `tables`='pages' and `cid` = '$cid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $c_cid_parent = $row['parent_id'];
    
    $orr = "";
    if ( $c_cid_parent != 0 and $c_cid_parent != "" ) $orr .= " or `parent_id` = '".mysql_real_escape_string($c_cid_parent)."'";
    if ( $cid != 0 and $cid != "" ) $orr .= " or `parent_id` = '".$cid."'";

    $sql = "SELECT `cid`, `title`, `description`, `parent_id` FROM ".$prefix."_pages_categories where `module`='".$DBName."' and `tables`='pages' and (`parent_id`='0'".$orr.") order by `sort`, `title`";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
      $c_cid = $row['cid'];
      $c_title[$c_cid] = $row['title'];
      $c_description[$c_cid] = $row['description'];
      $c_parent[$c_cid] = $row['parent_id'];
    }
    
    $sql = "SELECT `cid`, `title` FROM ".$prefix."_pages_categories where `module`='".$DBName."' and `tables`='pages' and `parent_id`='0' order by `sort`, `title`";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
      $c_cid2 = $row['cid'];
      $c_title2[$c_cid2] = $row['title'];
    }
  // Кол-во страниц в папках
  if ($pagekol == 1) {
    $num = array();
    $sql = "SELECT `cid` from ".$prefix."_pages where `tables`='pages' and `module`='".$DBName."' and `active`='1'";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
      $num[] = $row['cid'];
    }
    $num = array_count_values ($num);
  } else $num = "";
  ##########################

  if (($view==2 or $view==4 or $view==6) and $cid==0) { // Если отображение - каталог

    if ($cid > 0) $title = trim($c_title[$cid]); else $title = "";
    $ret .= "<div class='cat_title'>";
    if ($razdel_link==1) $ret .= "<h1 class='cat_categorii_link'><A href='/-".$DBName."'>".$ModuleName."</a></h1>";
    else $ret .= "<h1 class='cat_categorii_link'>".$ModuleName."</h1>";
    
  if (trim($reclama) == "" and ($cid > 0)) $reclama = $c_description[$cid];
  if (trim($reclama) != "" and $view!=4) $ret .= "<div class='cat_description'>".$reclama."</div>";

    $ret .= "<br>".$searchline1." ".$title." </div><div class='venzel'></div>";
    if ($razdel_link==2) $ret = "";

  $links="";
  /////////////////////////////////////////////
    foreach ($c_title as $key => $cid_title) {
      if ($c_parent[$key]==0) {
        $cid_title = trim($cid_title);
        
        if (isset($num[$key])) { if ($num[$key] != "") $num[$key] = " (".$num[$key].")"; }
        else $num[$key] = '';
    
        $links .= "<div class='cat_maincategorii_link'><a href=/-".$DBName."_cat_".$key.">".$cid_title."</a>".$num[$key]."</div>";

        if (count($c_title) > 0 and ($podrazdel_show==3 or $podrazdel_show==1)) {
          $links2 = "";
          foreach ($c_title as $key2 => $cid_title2) {
            if ($c_parent[$key2]==$key) {
              $cid_title2 = str_replace(" ","&nbsp;",trim($cid_title2));
              $links2 .= "<a href=/-".$DBName."_cat_".$key2.">".$cid_title2."</a>   ";
            }
          }
          if ($links2 != "") {
            $links .= "<div class='cat_mainpodcategorii_all'><div class='cat_mainpodcategorii'>".str_replace("   ","</div> <div class='cat_mainpodcategorii'>",trim($links2))."</div></div>";
          }
        }
      }
    }
    $ret .= $links." &nbsp;";
    // Поиск
    //if ($search == 2) $soderganie .= search_line($DBName, $cid);
    $pagetitle = $ModuleName." — "; // $cat_title." — 
  #####################################################################
  } else { // Если отображение - не каталог

  if (!isset($c_description[$cid])) $c_description[$cid] = "";

  if ($page == 2) $c_description[$cid] = "";
  ################################################################

  if (!isset($c_title[$cid])) $c_title[$cid] = "";
  if (!isset($c_parent[$cid])) $c_parent[$cid] = 0;

  if (trim($reclama) == "") $reclama = $c_description[$cid];
  $title = trim($c_title[$cid]);
  $parent_id = $c_parent[$cid];
  $and = "";

  // Добавить подкатегории
  $title = "<span class='cat_podcategorii_link'>".$title."</span>";
  $title = getparent_page($parent_id,$title,$cid,$page);

  // <A class=cat_podcategorii_link href=\"\-".$DBName."_cat_".$cid."\">$title</a>
  if ($page == 1) $and = " ".$strelka." ".$title."";
  if ($page == 2) $and = " ".$strelka." <A href=/-".$DBName."_cat_".$cid.">".$title."</a>";


  $ret .= "<div class='cat_title'>";
  if ($razdel_link==1) $ret .= "<h1 class='cat_categorii_link'><A href='/-".$DBName."'>".$ModuleName."</a></h1>";
  else $ret .= "<h1 class='cat_categorii_link'>".$ModuleName."</h1>";
  if ($podrazdel_active_show > 0) $ret .= $and;
  $ret .= "</div> ";

  if ($razdel_link==2) $ret = "";
  // Добавление табов
    global $include_tabs;
    if (strpos(" ".$reclama, "{{")) {
      if ($include_tabs == false) { include ('page/tabs.php'); $include_tabs = true; }
      if (strpos(" ".$reclama, "{{")) $reclama = show_tabs($reclama);
    }
  if (trim($reclama) != "" and $view!=4) $ret .= "<div class='cat_description'>".$reclama."</div>";

  if ( (count($c_title) > 0) and ($podrazdel_show==3 or $podrazdel_show==1) and ( $page == 1 or $podrazdel_active_show > 0) ) {
  $links = $links2 = "";

  if ($cid == 0) $c_title = $c_title2;
  $no_links_perehod = false;

  $links2 .= "<div class='main_cat_links'>";

  	foreach ($c_title as $key => $cid_title) {
    $cid_title = str_replace(" ","&nbsp;",trim($cid_title));
    
    if ($cid != 0 and $c_parent[$key]!=0 and $no_links_perehod == false) { 
      $links2 .= "   </div><div class='podcategorii_cat_links'>"; 
      $no_links_perehod = true;
    }
    
      if ($podrazdel_active_show == 2) {
          if ($cid != $key and $c_parent[$cid] != $key) 
            $links2 .= "   <a href='/-".$DBName."_cat_".$key."' class='no_active_podcategorii_link'>".$cid_title."</a>";
          else 
            $links2 .= "   <b><a href='/-".$DBName."_cat_".$key."' class='active_podcategorii_link'>".$cid_title."</a></b>";
          
      } elseif ($podrazdel_active_show == 3) {
          if ($cid == $key) $sel = " selected"; else $sel = "";
          if ($key != 0) $links2 .= "<option value='".$key."'".$sel.">".$cid_title."</option>";
      } else {
        if ($key == $cid and $cid_title != "") {
        // Показываем подразделы, если они есть
          $sql = "SELECT `cid`, `title` FROM ".$prefix."_pages_categories where `module`='".$DBName."' and `parent_id`='".$cid."' and `tables`='pages' order by `sort`, `cid`";
          $result = $db->sql_query($sql);
          while ($row = $db->sql_fetchrow($result)) {
            $c_cid4 = $row['cid'];
            $c_title4 = $row['title'];
            //$c_description[$c_cid] = $row['description'];

            // Кол-во страниц в подразделах
            if (isset($num[$c_cid4])) {if ($num[$c_cid4] != "") $num[$c_cid4] = " (".$num[$c_cid4].")";}
            else $num[$c_cid4] = "";

            $links2 .= "   <a href=/-".$DBName."_cat_".$c_cid4.">".$c_title4."</a>".$num[$c_cid4]."";
          }
        }
      }
  	}
    
    if ($no_links_perehod == false) $links2 .= "   </div>";
    
    
  /////////////////////////////////////
    if ($podrazdel_active_show == 3) {
      if ($cid == "0") $sel = " selected"; else $sel = "";
      $links2 = "<select id='c_id' onchange=\"if (document.getElementById('c_id').value != '') location.href = '/-".$DBName."_cat_' + document.getElementById('c_id').value;\"><option value=''>".ss("Выберите раздел")."</option>".$links2."</select><p>";
    }
    
  	if (trim($links2) != "" and trim($links2) != "<div class='main_cat_links'>   </div>") {
      if ($podrazdel_show==3 or $podrazdel_show==1) {
        if ($podrazdel_active_show != 3) $links .= "<div class='cat_podcategorii_link'>";
        //if (trim($links2)!="") $links .= "| "; // Разделение черточками
        if ($podrazdel_active_show == 4) $razdelitel = "<br>"; else $razdelitel = "&nbsp;| ";
        $links = str_replace("   ",$razdelitel,trim($links2));
        if ($podrazdel_active_show != 3) $links .= "</div>";
      }
  	  //if ($cat==2) $links .= "<div class=cat_maincategorii_link> ".str_replace("   ","</div><div class=cat_maincategorii_link>",trim($links2))."</div>";
  	  $ret .= $links." <br>";
  	}
  }

  $pagetitle = $ModuleName." — ";
  }

  if ($post!=0 and $page==0 and $cid!=0) $ret .= addpost($cid);
  $ret .= $searchline1;
  return $ret;
}
###########################################################
function showdate($showdate) {
  // проверка даты
  $showdate = explode("-",$showdate);
  	$showdate = intval($showdate[0])."-".(intval($showdate[1]) < 10 ? '0'.intval($showdate[1]) : $showdate[1])."-".(intval($showdate[2]) < 10 ? '0'.intval($showdate[2]) : $showdate[2]);
  	
  global $strelka, $soderganie, $soderganie2, $DBName, $db, $prefix, $module_name, $admin, $name, $pagetitle;
  global $golos, $post, $comments, $datashow, $sort, $folder, $media, $view, $col, $search, $search_papka, $tema, $tema_name, $tema_title, $tema_opis, $menushow, $where, $order, $peopleshow, $calendar, $comments_1; // настройки из БД
  $ANDDATA="";

  $p_pid_last = 1; // последняя категория (для форума)

  $soderganieOPEN = "";
  //$soderganieOPEN = "<table class='all_page' width='100%'><tr valign='top'><td>";
  $soderganieMENU = top_menu(0,0);
  $soderganieALL = "<center><div class='polosa'></div></center>";
  $soderganieALL .= "на ".date2normal_view($showdate);

  // Список всех папок (массив)
  $c_name = array();
  $sql = "SELECT `cid`, `title` FROM ".$prefix."_pages_categories where `module`='".$DBName."' and `tables`='pages'";
  $result = $db->sql_query($sql) or die(ss("Не удалось собрать список всех папок"));
  while ($row = $db->sql_fetchrow($result)) {
    $x_cid = $row['cid'];
    $c_name[$x_cid] = $row['title'];
  }

  $showdate = mysql_real_escape_string($showdate);
  $calendar = mysql_real_escape_string($calendar);
  // списки
  if (trim($calendar) != "") {
    $sql2 = "SELECT pages FROM ".$prefix."_spiski where `name`='".$showdate."' AND `type`='".$calendar."'";
    $result2 = $db->sql_query($sql2) or die(ss("Не удалось собрать списки"));
    $row = $db->sql_fetchrow($result2);
    $datavybor = $row['pages'];
    $datavybor = trim(str_replace("   "," ",str_replace("  "," ",$datavybor)));
    $datavybor = " and (`pid`='".str_replace(" ","' or `pid`='",$datavybor)."')";
  } else $datavybor = " and `date` like '".$showdate." %'";

  $sql2 = "SELECT `pid` FROM ".$prefix."_pages where `tables`='pages' and (`copy`='0' or `copy`=pid) and `module`='".$DBName."' AND `active`='1'".$datavybor;
  $result2 = $db->sql_query($sql2) or die(ss("Не удалось определить кол-во страниц"));
  $nu = $db->sql_numrows($result2);
  $soderganieALL .= ", всего: ".$nu;
  if ($nu > 0 and $view!=4) {
     # Если не выбран ни один каталог
    if ($div_or_table == 0) {
      if ($view==1) $soderganieALL .= "<table cellspacing=0 cellpadding=3 width=100%>";
      else $soderganieALL .= "<table cellspacing=0 cellpadding=3 width=100%>";
    }
    $sql2 = "SELECT * FROM ".$prefix."_pages where `tables`='pages' and (`copy`='0' or `copy`=pid) and `module`='".$DBName."' AND `active`='1'".$datavybor." order by ".mysql_real_escape_string($sort);
    $result2 = $db->sql_query($sql2);
    $soderganieALL .= "";

    if ($comments==1) $colspan=4;
    else $colspan=3;
    while ($row2 = $db->sql_fetchrow($result2)) {
      $p_pid = $row2['pid'];
      $pсid = $row2['cid'];
      if ($pсid != 0) $p_name = "<div class='cat_page_cattitle'><a href='/-".$DBName."_cat_".$pсid."' class='cat_page_cattitle'>".$c_name[$pсid]."</a></div>"; else $p_name = "";
      $title = $row2['title'];
      $text = $row2['open_text'];
      ///////////////////////////
      $text = str_replace(aa("[заголовок]"),"",$text); // Убираем Заголовок, использованный в блоке!
      ///////////////////////////
      $p_comm = $row2['comm'];
      $p_counter = $row2['counter'];
      $dat = explode(" ",$row2['date']);
      $dat = explode("-",$dat[0]);
      $p_date = intval($dat[2])." ".findMonthName($dat[1])." ".$dat[0];
      $p_date_1 = $dat[2]." ".$dat[1]." ".$dat[0];
      $date_now = date("d m Y");
      $date_now2 = date("d m Y",time()-86400);
      $date_now3 = date("d m Y",time()-172800);
      if ($date_now == $p_date_1) $p_date = ss("Сегодня");
      if ($date_now2 == $p_date_1) $p_date = ss("Вчера");
      if ($date_now3 == $p_date_1) $p_date = ss("Позавчера");

    	if ($view == 1) { // ФОРУМ //////////////////////////////////////////////////////
        $soderganieALL .= "<tr valign=top>";
        if ($p_pid_last != $pсid) { 
          $p_pid_last = $pсid;
          $soderganieALL .= "<td colspan=".$colspan." class='cat_page_forum'>".$p_name."</td></tr><tr valign='top'>"; 
        }
        $soderganieALL .= "<td class='cat_page_title'><a href=/-".$DBName."_page_".$p_pid.">".$title."</a></td>";
        $soderganieALL .= "<td class='cat_page_date'><nobr>".$p_date."</nobr></td>";
        $soderganieALL .= "<td class='cat_page_date'>".ss("читали:")."&nbsp;".$p_counter."</td>";
        if ($comments==1) {
          if ($p_comm>0) $soderganieALL .= "<td class='cat_page_commnum'>".$p_comm." ".ss("комм.")."</td>";
          else $soderganieALL .= "<td class='cat_page_commnum'><a href='/-".$DBName."_page_".$p_pid."'>".ss("Добавить комментарий")."</a></td>";
        }
        $soderganieALL .= "</tr>";
    	} else { ////////////////////////////////////////////////////////	<div class=cat_page></div>
        $soderganieALL .= "<tr valign='top'><td>

        <div class='page_link_title'><A href='/-".$DBName."_page_".$p_pid."'><h1 class='cat_page_title'>".$title."</h1></A></div>";
        if (trim($text)!="") $soderganieALL .= "<div class='cat_page_text'>".$text."</div>";
        $soderganieALL .= "<div class='cat_page_counter'>";
        if ($pсid>0 and $c_name[$pсid]!="") $soderganieALL .= "<img width='16' src='/images/sys/papka.png' align='bottom' title='".ss("Раздел:")."' style='padding-right:5px;'><A href='/-".$DBName."_cat_".$pсid."'><b>".$c_name[$pсid]."</b></a> &nbsp; ";
        if ($peopleshow==1) $soderganieALL .= "<img width='16' src='/images/sys/magnify.png' align='bottom' title='".ss("Просмотры:")."' style='padding-right:5px; padding-left:15px;'><b>".$p_counter."</b>";
        $soderganieALL .= "</div>";
        if ($datashow==1) $soderganieALL .= "<div class='cat_page_date'><img width='16' src='/images/sys/026.png' align='bottom' title='".ss("Дата:")."' style='padding-right:5px;'>".$p_date."</div>"; // Отображение даты
        if ($p_comm>0) $soderganieALL .= "<div class='cat_page_comments'><a title='".ss("раскрыть")." ".$comments_1."' href='/-".$DBName."_page_".$p_pid."_comm#comm'>".$comments_1.": <b>".$p_comm."</b></a></div>"; // Отображение комментариев
        $soderganieALL .= "</td></tr>";
    	} ////////////////////////////////////////////////////////
    }
    $soderganieALL .= "</table>";
  }
  //////////////////////////////////////////////////////////////
  $soderganie .= $soderganieOPEN.$soderganieMENU.$soderganieALL;
  $soderganie2 .= $soderganieOPEN.$soderganieALL;
}
######################################################################################
function showcat($cid=0, $pag=0, $slovo="") {
  global $strelka, $soderganie, $soderganie2, $DBName, $db, $prefix, $module_name, $admin, $name, $pagetitle, $keywords2, $description2;
  global $golos, $golosrazdel, $golostype, $post, $comments, $datashow, $sort, $lim, $folder, $media, $view, $col, $search, $search_papka, $tema, $tema_name, $tema_title, $tema_opis, $menushow, $base, $where, $order, $peopleshow, $show_add_post_on_first_page, $razdel_shablon, $comments_1, $comments_main, $limkol, $tags, $tag_text_show, $tags_type, $tags_show, $pagenumbers, $div_or_table, $show_read_all, $read_all; // настройки из БД
  $DBName = mysql_real_escape_string($DBName);
  $sort = mysql_real_escape_string($sort);
  $cid = intval($cid);
  $pag = intval($pag);
  $add_css = $rus_names_ok = "";
  $sql = "select `description`, `keywords` from ".$prefix."_mainpage where `tables`='pages' and `name`='".$DBName."' and `type`='2'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $keywords2 = $row['keywords'];
  $description2 = $row['description'];

  $slovo = str_replace(" ","%",str_replace("  "," ",trim(trim(strip_tags(urldecode(str_replace( "-","%", $slovo)))))));
  $ANDDATA="";

  if (!is_admin($admin)) // Счетчик
    $db->sql_query("UPDATE ".$prefix."_mainpage SET `counter`=`counter`+1 WHERE `tables`='pages' and `name`='".$DBName."' and `type`='2'");

  if ($base=="") { // Если это не база данных
    $offset = $pag * $lim * $limkol;
    $lim2 = $lim * $limkol;
    ################### Главная страница НЕМЕДИЙНОГО содержания
    $cid = mysql_real_escape_string($cid);
    // Генерация облака тегов
    $tagcloud = "";
    if ($tags_type != 0) {
    // Откуда будем добывать теги...
      if ($tags_type == 1) { $and = ""; $link_tag = "/--slovo_"; } // ИЗ всех разделов портала
      if ($tags_type == 2) { $and = " and `module`='".$DBName."'"; $link_tag = "/-".$DBName."_slovo_"; } // ИЗ текущего раздела
      if ($tags_type == 3) { $and = " and `cid`='".$cid."' and `module`='".$DBName."'"; $link_tag = "/-".$DBName."_cat_".$cid."_slovo_"; } // ИЗ текущей папки раздела
        $tagss = array();
        $sql = "select `search` from ".$prefix."_pages where `tables`='pages' and (`copy`='0' or `copy`=pid) and `active`='1'".$and;
        $result = $db->sql_query($sql);
        while ($row = $db->sql_fetchrow($result)) {
          if (trim($row['search']) != "") {
          $tag = array();
          $tag = explode(" ",trim(str_replace("  "," ",$row['search'])));
            foreach ($tag as $tag1) {
              if (trim($tag1) != "" and strlen($tag1)>2 ) {
                $tagss[] = trim($tag1);
              }
            }
          }
        }
      $tagss = array_count_values($tagss);
      $tags2 = array_unique($tagss);
      rsort($tags2);
      $razmer = 0;
      $tags3 = array();
      $tags4 = array();
        foreach ($tags2 as $tag1) {
          if ($razmer == 0) { $tags3[$tag1] = "14"; $tags4[$tag1] = "#000000"; }
          if ($razmer == 1) { $tags3[$tag1] = "13"; $tags4[$tag1] = "#363636"; }
          if ($razmer == 2) { $tags3[$tag1] = "12"; $tags4[$tag1] = "#555555"; }
          if ($razmer == 3) { $tags3[$tag1] = "11"; $tags4[$tag1] = "#707070"; }
          if ($razmer > 3) { $tags3[$tag1] = "10"; $tags4[$tag1] = "#898989"; }
          if ($razmer > 4) { $tags3[$tag1] = "9"; $tags4[$tag1] = "#a1a1a1"; }
          $razmer++;
        }
        
            if ($tags_show == 1) $tagcloud = $tag_text_show."<br>";
            else $tagcloud = "<a onclick=\"show('tags')\" style=\"cursor:pointer;\"><u>".$tag_text_show."</u></a><br><div id='tags' style='display:none;'>";
            $tag_kol = 0;
            foreach ($tagss as $tag_name => $tag_col) {
              if ($tags3[$tag_col] != "1") {
                $tagcloud .= "<a class='slovo' href='".$link_tag.str_replace( "%","-", urlencode( $tag_name ) )."' style='color:".$tags4[$tag_col]."; font-size: ".$tags3[$tag_col]."pt;'>".str_replace("+","&nbsp;",$tag_name)."</a> "; //  class='slovo' title='$tag_col темы' rel=\"tag nofollow\"
                $tag_kol++;
              }
            }
            if ($tags_show == 1) $tagcloud .= "<br>";
            else $tagcloud .= "<br></div>";
        if ($tag_kol == 0) $tagcloud = "";
    }

    $slovo = strtolow($slovo);
    if (trim($slovo) != "") {
      $tag_slovo = ss("<b>Выбрано</b> ключевое слово:")." <b>".str_replace("+","&nbsp;",$slovo)."</b>.<br>".ss("Показаны только страницы, содержащие это слово.")."<br>"; 
      $and_1_3 = " and `search` LIKE '% ".mysql_real_escape_string($slovo)." %'";
      $offset = 0;
      $lim2 = 1000;
    } else {
      $tag_slovo = "";
      $and_1_3 = "";
    }
    $soderganieALL = "";
    //////////////////////////////////////////////////////
    if ($cid == 0 and $view!=2) {
      if ($menushow != 0) $soderganieMENU = top_menu($cid, 0);
      if (!isset($soderganieALL)) $soderganieALL = "";
      $soderganieALL .= $tagcloud.$tag_slovo;
      # Если не выбран ни один каталог

      if ($div_or_table == 0) {
        if ($view==1) $soderganieALL .= "<table cellspacing=0 cellpadding=3 width=100%>";
        else $soderganieALL .= "<table cellspacing=0 cellpadding=3 width=100%>";
      }
      $and_1_1 = "";
      $and_1_2 = "";
    } else {
      $and_1_1 = " and `cid`='".$cid."'";
      $and_1_2 = $ANDDATA;
      if ($menushow != 0) $soderganieMENU = top_menu($cid, 1);
      $soderganieALL .= $tagcloud;
    }

    // Список всех каталогов (массив)
    $c_name = array();
    //$c_pic = array();
    $sql = "SELECT `cid`, `title` FROM ".$prefix."_pages_categories where `module`='".$DBName."' and `tables`='pages' and `cid`!='0' order by `sort`, `title`";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
      $x_cid = $row['cid'];
      $c_name[$x_cid] = $row['title'];
      if ($cid == $x_cid) { // ? может убрать?
        $cid_title = $row['title'];
      }
    }

    $and_1_4 = "";
    if ($view==6 and $cid == 0) $and_1_4 = " and `cid`='0'";

    $sql2 = "SELECT `pid` FROM ".$prefix."_pages where `tables`='pages' and `module`='".$DBName."'".$and_1_1." AND (`active`='1' or `active`='2')".$and_1_2.$and_1_3.$and_1_4;
    $result2 = $db->sql_query($sql2);
    $nu = $db->sql_numrows($result2);

    $sql2 = "SELECT * FROM ".$prefix."_pages where `tables`='pages' and `module`='".$DBName."'".$and_1_1." AND (`active`='1' or `active`='2')".$and_1_2.$and_1_3.$and_1_4." ORDER BY ".$sort." limit ".mysql_real_escape_string($offset).",".mysql_real_escape_string($lim2);
    $result2 = $db->sql_query($sql2);

    $proc = intval(100 / $limkol);
    $soderganieALL2 = "";
    if ($limkol > 1) $soderganieALL2 = "<tr valign='top'><td width='".$proc."%'>";
    $limkol_num = 0;
    $kol_num = 0;

    global $tema_name, $tema_title;
    $p_pid_last = 0; // последняя категория (для форума)
    if ($comments == 1) $colspan = 4; else $colspan = 3; // количество ячеек таблицы (для форума)

    if ($nu >0 and $view==4 and $cid != 0) {
      $soderganieALL = "<div class='venzel'></div>
      <table cellspacing='0' cellpadding='0' width='100%'><tr valign='top'><td>".$tema_title."</td>";
      if ( $tema_name != "no" ) $soderganieALL .= "<td class='reiting_text'>".$tema_name."</td>"; else $soderganieALL .= "<td></td>";
      $soderganieALL .= "<td align='center'>".ss("Средний балл")."</td><td align='center'>".ss("Всего голосов")."</td><td align='center'>+</td><td align='center'>?</td><td align='center'>-</td></tr>";
    }
    $numm = 0;

  if ($nu > 0) { //  and $view != 4
    if ($razdel_shablon == 0) { // Если используются внутренние шаблоны
      // Получаем шаблон
      $sha = shablon_show("razdel", $view);
      $add_css = " razdel_".$view;
    } else {
      // Доступ к шаблону
      $sql = "select `text` from ".$prefix."_mainpage where `tables`='pages' and `id`='".mysql_real_escape_string($razdel_shablon)."' and `type`='6'";
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $sha_first = "";
      $sha = explode(aa("[следующий]"),$row['text']);
      if (isset($sha[1])) $sha_first = $sha[1];
      else $sha_first = "";
      $sha = $sha[0];
      //$add_css .= " razdel_".$row['text'];
      
        // Ищем списки (доп. поля), относящиеся к нашим страницам по модулю
        $s_names = array();
        $s_opts = array();
        // Определим № раздела
        $sql = "select `id` from ".$prefix."_mainpage where `tables`='pages' and name='".$DBName."' and type='2'";
        $result7 = $db->sql_query($sql);
        $row7 = $db->sql_fetchrow($result7);
        $r_id = mysql_real_escape_string($row7['id']);
        $result5 = $db->sql_query("SELECT `id`, `name`, `text` FROM ".$prefix."_mainpage WHERE `tables`='pages' and (`useit` = '".$r_id."' or `useit` = '0') and `type`='4'");
        while ($row5 = $db->sql_fetchrow($result5)) {
          $s_id = $row5['id'];
          $n = $row5['name'];
          $s_names[$s_id] = $n;
          // Найдем значение всех полей для данных страниц // переделать
          $result6 = $db->sql_query("SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE type='".mysql_real_escape_string($n)."'");
          while ($row6 = $db->sql_fetchrow($result6)) {
            $n1 = $row6['name'];
            $n2 = explode(" ", trim($row6['pages']));
            foreach ($n2 as $n2_1 => $n2_2) {
              $s_opts[$n][$n2_2] = $n1; // раздел страница поле
            }
          }
        }
    }
    $color = 1;
    
    // Получаем список всех индексов страниц для исключения копий
    $pid_s = array();
    while ($row3 = $db->sql_fetchrow($result2)) {
      // Добавляем только оригиналы, без копий
      if ( $row3['copy'] == $row3['pid'] or $row3['copy'] == 0 ) $pid_s[] = $row3['pid'];
    }
    $result2 = $db->sql_query($sql2);

      $copy_s = array();
      while ($row2 = $db->sql_fetchrow($result2)) {
        if ( ( $row2['copy'] == 0 or $row2['copy'] == $row2['pid'] or !in_array($row2['copy'], $pid_s) ) and !in_array($row2['copy'], $copy_s) ) {

          if ($row2['copy'] != 0) $copy_s[] = $row2['copy'];
          $numm++;
          $p_pid = $row2['pid'];
          $pсid = $row2['cid'];
          $p_name = "";
          if ($pсid!=0 and $cid == 0) 
            $p_name="<div class='cat_page_cattitle'><a href='/-".$DBName."_cat_".$pсid."' class='cat_page_cattitle'>".$c_name[$pсid]."</a></div>";
          $title = stripcslashes($row2['title']);
          $open_text = stripcslashes($row2['open_text']);
          if (trim($open_text) == "<br><br>") $open_text = "";
          $text = $row2['main_text'];
          if (trim($text) == "<br><br>") $text = "";
          $open_text = str_replace(aa("[заголовок]"),"",$open_text); // Убираем Заголовок, использованный в блоке!
          // Добавление табов
          if (strpos(" ".$open_text, "{{")) {
            global $include_tabs;
            if ($include_tabs == false) { 
              include ('page/tabs.php'); 
              $include_tabs = true;
            }
            if (strpos(" ".$open_text, "{{")) $open_text = show_tabs($open_text);
          }
          $p_comm = $row2['comm'];
          $p_active = $row2['active'];
          $p_counter = $row2['counter'];
          $dat = explode(" ",$row2['date']);
          $dat = explode("-",$dat[0]);
          $p_date = intval($dat[2])." ".findMonthName($dat[1])." ".$dat[0];
          $p_date_1 = $dat[2]." ".$dat[1]." ".$dat[0];
          $date_now = date("d m Y");
          $date_now2 = date("d m Y",time()-86400);
          $date_now3 = date("d m Y",time()-172800);
          if ($date_now == $p_date_1) $p_date = ss("Сегодня");
          if ($date_now2 == $p_date_1) $p_date = ss("Вчера");
          if ($date_now3 == $p_date_1) $p_date = ss("Позавчера");
          $active = ss("Открытая информация");
          $search = $row2['search'];
            
          $golos = $foto_adres = $foto = $price = $rss = ""; // обнуление
          if ($golostype != 0) $golos = $row2['golos'];
          if ($razdel_shablon > 0) {
            switch ($p_active) {
              case "1": $active = ss("Открытая информация");	break;
              case "2": $active = ss("Информация ожидает проверки");	break;
              case "3": $active = ss("Информация ожидает проверки администратора");	break;
              case "0": $active = ss("Доступ к странице ограничен");	break;
            }
            //$foto_adres = $row2['foto'];
            //$foto = "<img src='".$foto_adres."'>";
            //$price = $row2['price']." ".ss("руб."); // или другая валюта!
            $rss = $row2['rss'];
            switch ($rss) {
              case "1": $rss = "<a name='rss' title='".ss("Информация доступна через RSS-подписку")."' class='green_link'>RSS</a>"; break;
              case "0": $rss = "<a name='rss' title='".ss("Информация не доступна через RSS-подписку")."' class='red_link'>RSS</a>"; break;
            }
          }
          $sred_golos = $all_golos = $sred_golos = $plus_golos = $neo_golos = $minus_golos = $active_color = "";
          
          if ($nu > 0 and $view==4 and $cid != 0) {
            if ($color == 1) {
              $active_color = " style='background-color: #f1f1f1;"; $color = 0; 
            } else {
              $active_color = " style='background-color: white;'"; $color = 1;
            }
            if ($p_active == 2) $active_color .= " color:gray;";
            $active_color .= "'";
            $p_pid = mysql_real_escape_string($p_pid);
            $row3 = $db->sql_fetchrow($db->sql_query("select SUM(`golos`) i from ".$prefix."_pages_comments where `num`='".$p_pid."' and `active`='1'"));
            $sred_golos = $row3['i'];
            $all_golos = $db->sql_numrows($db->sql_query("SELECT `cid` FROM ".$prefix."_pages_comments WHERE `num`='".$p_pid."' and `active`='1'"));
            $sred_golos = round($sred_golos/$all_golos, 2);
            $plus_golos = $db->sql_numrows($db->sql_query("SELECT `cid` FROM ".$prefix."_pages_comments WHERE `num`='".$p_pid."' and `golos`='5' and `active`='1'"));
            $neo_golos = $db->sql_numrows($db->sql_query("SELECT `cid` FROM ".$prefix."_pages_comments WHERE `num`='".$p_pid."' and `golos`='3' and `active`='1'"));
            $minus_golos = $db->sql_numrows($db->sql_query("SELECT `cid` FROM ".$prefix."_pages_comments WHERE `num`='".$p_pid."' and `golos`='1' and `active`='1'"));
          }

          if ($view==1) { // ФОРУМ //////////////
            if ($p_pid_last != $pсid and $pсid != 0) { 
              $sha_first = "<tr valign='top'><td colspan='".$colspan."' class='cat_page_forum'>".$p_name."</td></tr><tr valign='top'>";
              $p_pid_last = $pсid; 
            }
            if ($comments==1) {
              if ($p_comm>0) $p_comm = "".$p_comm." ".ss("комм.");
              else $p_comm = "<a href='/-".$DBName."_page_".$p_pid."#comm'>".ss("Добавить комментарий")."</a>";
            } else $p_comm = "";
          } elseif ($view!=4) { /////////////////////////////////	<div class=cat_page></div>
            if (trim($open_text)!="" and $tema_title != "no") $open_text = "<div class='cat_page_text'>".$open_text."</div>"; else $open_text = "";
            if ($pсid>0 and $c_name[$pсid]!="") $all_cat_link = "<nobr><img width='16' src='/images/sys/papka.png' align='bottom' title='".ss("Раздел:")."' style='padding-right:5px;'><A href='/-".$DBName."_cat_".$pсid."'><b>".$c_name[$pсid]."</b></a></nobr> "; else $all_cat_link = "";
            if ($peopleshow==1) $all_page_counter = " <nobr><img width='16' src='/images/sys/magnify.png' align='bottom' title='".ss("Просмотры:")."' style='padding-right:5px; padding-left:15px;'><b>".$p_counter."</b></nobr>";
            else $all_page_counter = "";
            if ($datashow==1) $all_page_data = " <div class='cat_page_date'><nobr><img width='16' src='/images/sys/026.png' align='bottom' title='".ss("Дата:")."' style='padding-right:5px;'>".$p_date."</nobr></div>";
            else $all_page_data = "";
            if ($p_comm>0) $all_page_comments = " <div class='cat_page_comments'><nobr><a title='".ss("раскрыть")." ".$comments_1."' href='/-".$DBName."_page_".$p_pid."_comm#comm'><img width='16' src='/images/sys/028.png' align='bottom' title='".$comments_1.":' style='padding-right:5px;'><b>".$p_comm."</b></a></nobr></div>";
            else $all_page_comments = "";
          }

          $pagelink = "/-".$DBName."_page_".$p_pid;
          $pagelinktitle = "<A href='".$pagelink."'><h1 class='cat_page_title'>".$title."</h1></A>";
          if (strlen($text) < 10 and $comments != 1) {
            $pagelink = "#";
            $pagelinktitle = "<h1 class='cat_page_title'>".$title."</h1>";
          }

          $golosraz = "";
          if ($golosrazdel != 0) {
            require_once ("golos.php");
            $golosraz = golos_show($p_pid, $golostype, $golos); // (страница, тип рейтинга, кол-во рейтинга для типов 1, 2 и 3)
          }
      
          $page_tags = "";
          
          // ВЕЗДЕ - 3, в разделе и папках - 2, в разделе - 5, в разделе и на страницах - 6, 
          // в папках - 4, в папках и на страницах - 7, на страницах - 1,НИГДЕ - 0
          
          if ((($tags == 2 or $tags == 3) or $razdel_shablon != 0) and trim($search) != "") {
            $searches = array();
            $search2 = explode(" ",trim(strtolow($search)));
            $search_num = count($search2);
            for ($x=0; $x < $search_num; $x++) {
              $searches[] = "<a class='slovo' href='/--slovo_".str_replace( "%","-", urlencode( $search2[$x] ) )."'>".str_replace("+","&nbsp;",$search2[$x])."</a>";
            }
            $page_tags .= "<br>".$tag_text_show." ".implode(" | ", $searches)."";
          }

          $all_page_link = "";
          if ($show_read_all == "1") $all_page_link = " <A href='/-".$DBName."_page_".$p_pid."'>".$read_all."</a>";

          // Дополнение - преобразователь ссылок. //////////////////////
          /*
          $links_zamena = array(
            aa("[ссылка]")=>"<a href=/-".$DBName."_page_".$p_pid." class=open_page_link>".ss("Читать дальше")."</a> &rarr;",
            "<hr class=\"editor_cut\">"=>"<a href=/-".$DBName."_page_".$p_pid." class=open_page_link>".ss("Читать дальше")."</a> &rarr;",
            aa("-ссылка]")=>"</a>",
            aa("[ссылка-")=>"<a href=/-".$DBName."_page_".$p_pid." class=open_page_link>",
            );
          $open_text = strtr($open_text, $links_zamena);
          */
          //////////////////////////////////////////////////////////////

          if ($pсid==0) $c_name[$pсid] = "";

          $sha_zamena = array(
          "[page_id]"=>$p_pid,
          "[page_num]"=>$numm,
          "[page_razdel]"=>$DBName,
          "[page_link_title]"=>$pagelinktitle,
          "[page_link]"=>"/-".$DBName."_page_".$p_pid,
          "[all_page_link]"=>$all_page_link,
          "[page_open_text]"=>$open_text,
          "[page_title]"=>$title,
          "[page_text]"=>$text,
          "[page_data]"=>$p_date,
          "[all_page_data]"=>$all_page_data,
          "[page_counter]"=>$p_counter,
          "[all_page_counter]"=>$all_page_counter,
          "[page_comments]"=>$p_comm,
          "[all_page_comments]"=>$all_page_comments,
          "[cat_id]"=>$pсid,
          "[cat_name]"=>$c_name[$pсid],
          "[cat_link]"=>"/-".$DBName."_cat_".$pсid,
          "[all_cat_link]"=>$all_cat_link,
          "[sred_golos]"=>$sred_golos,
          "[all_golos]"=>$all_golos,
          "[plus_golos]"=>$plus_golos,
          "[neo_golos]"=>$neo_golos,
          "[minus_golos]"=>$minus_golos,
          "[active_color]"=>$active_color,
          "[page_active]"=>$active,
          "[page_golos]"=>$golos,
          "[cat_golos]"=>$golosraz,
          "[page_foto_adres]"=>$foto_adres,
          "[page_foto]"=>$foto,
          "[page_search]"=>$search,
          "[page_price]"=>$price,
          "[page_rss]"=>$rss,
          "[page_tags]"=>$page_tags,
          );
          if (($nu >0 and $view==4 and $cid != 0) or $view!=4) {
            $limkol_num++;
            $kol_num++;
            if ($limkol > 1) {
              if ($limkol_num == $limkol) {
                $limkol_num = 0;
                if ($kol_num != $lim*$limkol) $shaX = $sha."</td></tr><tr valign=top><td width=".$proc."%>";
                else $shaX = $sha."</td></tr></table>";
              } else $shaX = $sha."</td><td width=".$proc."%>";
            } elseif ($div_or_table == 0) {
              if ($view != 1) $shaX = "<tr valign=top><td>".$sha."</td></tr>";
            } else $shaX = $sha;

            if ($view==1) $shaX = $sha;
            $sha2 = strtr($shaX, $sha_zamena);
            if (!isset($s_names)) $s_names = array();
            foreach ($s_names as $id2 => $nam2) {
              // Найдем значение каждого поля для данной страницы
              if (!isset($s_opts[$nam2][$p_pid])) $s_opts[$nam2][$p_pid] = "";
              $nam3 = $s_opts[$nam2][$p_pid]; // WhatArrayElement();
              $sha2 = str_replace("[".$nam2."]", $nam3, $sha2);
            }
          }
          if (!isset($sha_first)) $sha_first = "";
          $soderganieALL2 .= $sha_first.$sha2;
        } // end if
    } // end if main
  } // end while

  if ($div_or_table == 0) {
    if ($cid == 0) $soderganieALL .= $soderganieALL2."</table>"; // </table>
    elseif ($view != 4) $soderganieALL .= "<table cellspacing=0 cellpadding=3 width=100%>".$soderganieALL2."</table>";
  } else $soderganieALL .= $soderganieALL2."<div style='clear:both;'></div>";

  if ($view!=4) {
    // Нумерация страниц
    if ($pagenumbers == 0) $soderganieALL .= "<div class=venzel></div><center>".topic_links($nu, $pag, "/-".$DBName."_cat_".$cid."_page_", $lim*$limkol)."</center>";

    if ($pagenumbers == 1) $soderganieALL = "<center>".topic_links($nu, $pag, "/-".$DBName."_cat_".$cid."_page_", $lim*$limkol)."</center><div class=venzel></div>".$soderganieALL;

    if ($pagenumbers == 2) {
      global $topic_links_global;
      $topic_links_global = "<center>".topic_links($nu, $pag, "/-".$DBName."_cat_".$cid."_page_", $lim*$limkol)."</center>";
    }
  }
  ###############################################################################################
  if (($comments_main == 1 and $cid == 0) or ($comments_main == 2 and $cid != 0) or ($comments_main == 3)) {
    // Комментарии
    
    // Форма комментариев
    $soderganieALL .= "".addcomm("0");
  }
  if (($post!=0 and $cid!=0) or ($cid == 0 and $show_add_post_on_first_page==1)) $soderganieALL .= addpost($cid); // Форма добавления страницы
  //////////////////////////////////////////////////////////////////////
  } else { // Если это база данных
    $soderganieMENU = top_menu($cid, 0);

    if ($razdel_shablon == 0) { // Если используются внутренние шаблоны
      // Получаем шаблон
      $sha = shablon_show("razdel", $view);
      $add_css .= " razdel_".$view;
    } else {
      // Доступ к шаблону
      $sql = "select `text` from ".$prefix."_mainpage where `tables`='pages' and `id`='".mysql_real_escape_string($razdel_shablon)."' and `type`='6'";
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $sha = $row['text'];
      //$add_css .= " razdel_".$row['text'];
    }

    $offset = $pag * $lim;

    // Определяем имя и настройки раздела - заменить?
    $sql = "SELECT `id`, `title`, `text` FROM ".$prefix."_mainpage where `tables`='pages' and `type`='2' and `name`='".mysql_real_escape_string($name)."'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $module_options = explode("|",$row['text']); 
    $module_options = $module_options[1]; 
    $base = 0;
    parse_str($module_options); // Настройки раздела
    $and = "";
    $first1 = "";
    $second1 = "";

    global $first, $second, $option;
    $option = intval($option);
    $first1 = urldecode(str_replace("|","%",str_replace("-_-","-",str_replace("+_+","+",$first))));
    $second1 = urldecode(str_replace("|","%",str_replace("-_-","-",str_replace("+_+","+",$second))));
    $first = "";
    $second = "";

    $sql = "SELECT `name`, `title`, `text` FROM ".$prefix."_mainpage where `tables`='pages' and `id`='".mysql_real_escape_string($base)."'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    //$baza_title  = $row['title']; // Название БД
    $baza_options  = str_replace("base|","",$row['text']); 
    $baza_name  = $row['name']; // Название таблицы БД  - заменить ?
    $type = 0;
    parse_str($baza_options);
  //print $baza_options;

    if ($first1!="") {
      $soderganieALL = ss("Вы выбрали:")." ".$first1;
      $and .= $first."='".$first1."'";
        if ($second1!="") {
          $soderganieALL .= ", ".$second1;
          $and .= " and ".$second."='".$second1."'";
        }
        if ($option!=0) {
          $sql = "SELECT `text` FROM ".$prefix."_mainpage where `tables`='pages' and `type`='3' and `id`='".mysql_real_escape_string($option)."'";
          $result = $db->sql_query($sql);
          $row = $db->sql_fetchrow($result);
          $option  = trim($row['text']); 
          if ($option != "") {
            //if ($option == "active='3'") $soderganieALL .= ", открытая информация";
            //if ($option == "active='1'") $soderganieALL .= ", закрытая информация";
            $and .= " and ".$option;
          } // else $soderganieALL .= ", открытая и закрытая информация";
        }
    } elseif(!isset($soderganieALL)) $soderganieALL = "";

    //echo $baza_options;
    $options = explode("/!/",$options); // $!$  ранее *
    $options_num = count($options);
    $names = array();
    $rus_names = array();
    $vagnost_names = array();
    $otkrytost_names = array();
    $zamena_names = array();
    $zapros_names = array();
    $type_names = array();
    for ($x=0; $x < $options_num; $x++) { /////////////////////////////////////////////////////
      $option = explode("#!#",$options[$x]); // #!#  ранее !
      $names[] = $option[0];
      if ($option[4] == 0) $zapros_names[] = $option[0];
      if ($option[4] == 0) $rus_names[] = $option[1];
      $vagnost_names[] = $option[3];
      $otkrytost_names[] = $option[4];
      $zamena_names[] = $option[5];
      $type_names[] = $option[2];
    }

    if ($podrobno == 1) $pod = "<td><b>".ss("Подробности")."</b></td>"; else $pod = "";

    $zapros_names2 = implode(", ",$zapros_names);

    if (empty($GLOBALS["sort_data_base"])) { 
      $s = 0;
      $desc = ""; 
    } else {
      $s = explode("_",$GLOBALS["sort_data_base"]);
      $desc = $s[1];
      $s = $s[0];
      SetCookie($s,"");
    }

    foreach( $rus_names as $key => $value ) {
      if ($s == $key and $desc == "") $link = "<a href='page/set.php?name=sort_data_base&fill=".$key."_desc' title='".ss("Нажмите для сортировки")."' style='color:red;'>&darr;</a>";
      elseif ($s == $key and $desc == "desc") $link = "<a href='page/set.php?name=sort_data_base&fill=".$key."_' title='".ss("Нажмите для сортировки")."' style='color:green;'>&uarr;</a>";
     else $link = "<a href='page/set.php?name=sort_data_base&fill=".$key."_' title='".ss("Нажмите для сортировки")."'>&darr;</a>";
     $rus_names_ok .= "<td><b>".$value."</b> ".$link."</td>";
    }

    if (trim($and)=="") $and .= "(`active`='1' or `active`='3')"; else $and .= " and (`active`='1' or `active`='3')";
    if ($where != "") $where = "WHERE ".stripcslashes($where)." and ".$and;
    else $where = "WHERE ".$and;
    if ($order != "") $order = " ORDER ".stripcslashes($order).""; // сортировка
    else $order = " ORDER BY `".$zapros_names[$s]."` ".$desc.", `active` desc";

    if ($razdel_shablon == 0) $soderganieALL .= "<div class=venzel></div>
    <table width=100% cellspacing=1 cellpadding=1 class=main_base_table><tr valign=top>".$rus_names_ok.$pod."</tr>";

    $where = mysql_real_escape_string($where);
    $order = mysql_real_escape_string($order);

    $sql = "SELECT ".mysql_real_escape_string($zapros_names2)." FROM ".$prefix."_base_".$baza_name." ".$where;
    $result = $db->sql_query($sql) or die(ss("Запрос к базе данных неудачен: ").$sql);
    $nu = $db->sql_numrows($result);

  	$sql = "SELECT `id`, ".$zapros_names2." FROM ".$prefix."_base_".$baza_name." ".$where.$order." limit ".mysql_real_escape_string($offset).",".mysql_real_escape_string($lim);
  	$result = $db->sql_query($sql);
  	while ($row = $db->sql_fetchrow($result)) {
      $pass_num = count($row)/2;
      $sha2 = $sha;
      if ($razdel_shablon == 0) $soderganieALL .= "<tr valign=top>"; // Если используются внутренние шаблоны
      for ($x=1; $x < $pass_num; $x++) {
        if ($razdel_shablon == 0) $soderganieALL .= "<td>";
          if ($type_names[$x-1]=="дата") $row[$x] = date2normal_view($row[$x]);
          if ($razdel_shablon == 0) {
            if ($zapros_names[$x-1] != 'id') $soderganieALL .= "".$row[$x]."";
          } else {
            $sha2 = str_replace("[".$baza_name."_".$zapros_names[$x-1]."]", $row[$x], $sha2);
          }
        if ($razdel_shablon == 0) $soderganieALL .= "</td>";
      }
        if ($razdel_shablon == 0) {
          if ($podrobno == 1) $soderganieALL .= "<td><a href=/-".$DBName."_page_".$row['id'].">".ss("Подробнее...")."</a></td>";
          $soderganieALL .= "</tr>";
        } else {
          $sha2 = str_replace(aa("[подробнее]"), "<a href=/-".$DBName."_page_".$row['id'].">".ss("Подробнее...")."</a>", $sha2);
          $soderganieALL .= $sha2;
        }
  	}
    
  	if ($razdel_shablon == 0) $soderganieALL .= "</table>";

    // Нумерация страниц
    if ($pagenumbers == 0) $soderganieALL .= "<div class=venzel></div><center>".topic_links($nu, $pag, "/-".$DBName."_cat_".$cid."_page_", $lim)."</center>";
    if ($pagenumbers == 1) $soderganieALL = "<center>".topic_links($nu, $pag, "/-".$DBName."_cat_".$cid."_page_", $lim)."</center><div class=venzel></div>".$soderganieALL;
    if ($pagenumbers == 2) {
      global $topic_links_global;
      $topic_links_global = "<center>".topic_links($nu, $pag, "/-".$DBName."_cat_".$cid."_page_", $lim)."</center>";
    }


    if ($post!=0) { add_base($baza_name,$name); } // Форма добавления страницы
  }
  ///////////////////////////////////////////////////////////////////
  // Поиск
  if ($search == 2) $soderganieALL .= "<br><br>".search_line($DBName, $cid);

  //$soderganieALL .= "</td></tr></table>"; // all_page

  //////////////////////////////////////////
  if (!isset($soderganieOPEN)) $soderganieOPEN = "";
  $soderganie .= $soderganieOPEN.$soderganieMENU.$soderganieALL;
  $soderganie2 .= $soderganieOPEN.$soderganieALL;
}
##############################    страницы    ###############################
function page($pid, $all) {
  global $strelka, $soderganie, $soderganie2, $DBName, $db, $prefix, $module_name, $admin, $pagetitle, $pagetitle2, $ModuleName, $print, $siteurl, $keywords2, $description2, $data_page;
  // настройки модуля из БД
  global $golos, $golostype, $post, $comments, $datashow, $sort, $tags, $lim, $folder, $view, $col, $menushow, $favorites, $socialnetwork, $name, $put_in_blog, $base, $titleshow, $comments_add, $add_css, $comment_shablon, $page_shablon, $comments_all, $comments_num, $comments_mail, $comments_adres, $comments_tel, $vetki, $comments_all, $comments_num, $comments_desc, $comments_1, $comments_2, $comments_3, $comments_4, $comments_5, $comments_6, $comments_7, $comments_8, $tag_text_show;
  $pid = mysql_real_escape_string(intval($pid));

  if ($base=="") { // Если это не база данных
  ////////////////////////////////////////////////////////////// Стандартные страницы

    #  pid, module, cid, title, open_text, main_text, `date`, counter, active, golos, comm, foto, search, mainpage
    $sql = "SELECT * FROM ".$prefix."_pages WHERE `pid`='".$pid."'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $cid = $row['cid'];
    $active = $row['active'];

    if ($cid=="") { // or ($active != 1 and !is_admin($admin))
      header("HTTP/1.0 404 Not Found");
      echo "<center style='margin-top:40px;'>".ss("<img src=/images/icon_no.png> <b>Запрашиваемая страница не существует.</b><br>Она была удалена, отключена или никогда и не создавалась.<br><br>Вы имеете право сохранять молчание и перейти на <a href=/>Главную страницу</a> <br>или попробовать найти нужную информацию на сайте с помощью быстрого поиска:")." <form method=POST action=\"/--search\" style='display:inline;' class=main_search_form>
      <input type=search name=slovo class=main_search_input><input type='submit' name='ok' value='".ss("Найти")."' class='main_search_button'>
      </form></center>";
      exit;
    }
    
    global $titl; // для передачи заголовка в php-скрипт
    $p_pid = $pid;
    $search = trim(str_replace("  "," ",$row['search']));
    $keys = $row['keywords'];
    $desc = $row['description'];
    $title = str_replace("<p>","",str_replace("</p>","",filter($row['title'])));
    $titl = str_replace("\"","",$title); 

    $opentext = str_replace("jpg\"><img ","jpg\" class=\"lightbox\" rel=\"page\"><img ", str_replace("<img ","<img title='$titl' ", filter($row['open_text'])));
    $bodytext = str_replace("jpg\"><img ","jpg\" class=\"lightbox\" rel=\"page\"><img ", str_replace("<img ","<img title='$titl' ", filter($row['main_text'])));
    $nocomm = $row['nocomm'];
    // Вырезание авто-ссылок
    //$opentext = preg_replace('/[ссылка-.*-ссылка]/Uis', '', $opentext);
    //$opentext = preg_replace("/[".aa("ссылка")."-.*-".aa("ссылка")."]/Uis", "", $opentext);
    //$opentext = str_replace("[".aa("ссылка")."]", "", $opentext);
    $opentext = str_replace('<hr class="editor_cut">', '', $opentext);
    
    global $table_light;
    if ($table_light == "1") {
      $opentext = str_ireplace("<table","<table class=\"table_light\"", $opentext);
      $bodytext = str_ireplace("<table","<table class=\"table_light\"", $bodytext);
    }

    // ========================================================================================
    // Добавление табов
    global $include_tabs;
    if (strpos(" ".$opentext, "{{")) {
      if ($include_tabs == false) { include ('page/tabs.php'); $include_tabs = true; }
      if (strpos(" ".$opentext, "{{")) $opentext = show_tabs($opentext);
    }
    if (strpos(" ".$bodytext, "{{")) {
      if ($include_tabs == false) { include ('page/tabs.php'); $include_tabs = true; }
      if (strpos(" ".$bodytext, "{{")) $bodytext = show_tabs($bodytext);
    }
    // ========================================================================================

    $module = $row['module'];
    $dat = explode(" ",$row['date']);
    $gol = $row['golos'];
    $comm = $row['comm'];
    $opentext = str_replace(aa("[заголовок]"),"",$opentext);
    // Убираем Заголовок, использованный в блоке!
    if ($keys == "") $keys = $search; $keywords2 = $keys;
    if ($desc == "") $description2 = $title; else $description2 = $desc;
    $page_tim = $dat[1];
    $dat = explode("-",$dat[0]);
    $p_date = intval($dat[2])." ".findMonthName($dat[1])." ".$dat[0];
    ///////////////////////////
    if ($put_in_blog>0) {
      $title_blog = htmlspecialchars($title);
      $link = "http://".$siteurl."/-".$DBName."_page_".$pid."";
      $logo_link = "http://".$siteurl."/small_logo.jpg";
      $opentext_blog = str_replace("/img/","http://".$siteurl."/img/",$opentext_blog);
      $opentext_blog = str_replace("/IMG/","http://".$siteurl."/img/",$opentext_blog);
      $opentext_blog = str_replace("http://".$siteurl."http://".$siteurl,"http://".$siteurl,$opentext_blog);
      $opentext_blog = str_replace("<img","<img style='margin:5px;'",$opentext_blog);
      $opentext_blog = str_replace("<IMG","<IMG style='margin:5px;'",$opentext_blog);
      $opentext_blog = htmlspecialchars($opentext_blog);
    }

    $main_title = "[menushow]";
    if ($menushow != 0) {
      if ($cid != 0) $main_titleX = top_menu($cid, 2); 
      else $main_titleX = "<div class=cat_title><h2 class=cat_categorii_link><a href=/-".$DBName.">".$ModuleName."</a></h2></div>";
      if (!isset($venzel)) $venzel = "";
      $venzel .= "<div class=venzel></div>";
    } else $main_titleX = "";

    ############################3
    $cat_title = "";
    if ($cid != 0) {
      $sqlZ = "SELECT `title` FROM ".$prefix."_pages_categories WHERE `cid`='".mysql_real_escape_string($cid)."' and `tables`='pages'";
      $resultZ = $db->sql_query($sqlZ);
      $rowZ = $db->sql_fetchrow($resultZ);
      if ($rowZ['title'] != "") $cat_title = $rowZ['title']." /";
    }
    // Для TITLE
    $pagetitle = $title." — ".$cat_title." ".$ModuleName." — ";
    $pagetitle = str_replace("  "," ",$pagetitle);
    $pagetitle = str_replace("— —","—",$pagetitle);
    $pagetitle2 = $title." — ".$cat_title." ";

    ############################3
      
    $search_num = count(explode(" ",$search));
    $search_keys = ""; # and
    if ($search_num==1) $search_keys = " and search LIKE '%".$search."%'";
    if ($search_num>1) { ###########################################
      $or = 1;
      $slovoC = strtok(trim($search)," ");
      $slovoX = $q = "";
      while ($slovoC) {
        $first = $slovoC;
        $slovoX .= $first." ";
        //if (!isset($proverka)) $proverka = $v.$name[$kt];
        //elseif ($proverka=="") $proverka = $v.$name[$kt];
        if ($or==1) $q .= " ";
        //else if ($proverka <> $v.$name[$kt]) {
        //    $proverka = $v.$name[$kt];
        //    $q .= " ";
        //} 
        else $q .= "@";
        $q .= "search|LIKE|'%".$first."%'";
        $slovoC = strtok(" ");
      } // Разбиение запроса на подзапросы ЗАКОНЧЕНО !
      $slovoX = trim($slovoX);
      $slovoX = str_replace(" ","|",$slovoX); // Использование в результате поиска.
      $search_where = $q;  
      $search_where = trim($search_where);
      if ($or==1) $search_where=str_replace(" ", " OR ", $search_where);
      else {
          $search_where=str_replace(" ", ")|OR|(", $search_where);
          $search_where=str_replace("@", " ", $search_where);
          $search_where=trim($search_where);
          $search_where=str_replace(" ", " AND ", $search_where);
          $search_where=str_replace("|OR|", " OR ", $search_where);
      }
      $search_keys=" and (".str_replace("|", " ", $search_where).")"; // Запрос для поиска - ОК!
    } ###########################################

  $page_title = "";
  if ($titleshow == 1) {
  if ($page_shablon == 0) $page_title .= "<h1 class=page_title>".$title."</h1>";
  else $page_title .= $title;
  }

  $page_opentext = "";
  if ($page_shablon == 0) {
    if ($view == 1) $page_opentext = "<div class=page_forum_avtor>".$opentext."</div>";
    else $page_opentext = "<div class=page_opentext>".$opentext."</div>";
  } else $page_opentext = $opentext;

  if (!isset($no_opentext)) $no_opentext = "";
  if ($no_opentext == 1) $page_opentaxt = "";

  if (!isset($page_text)) $page_text = "";
  if ($page_shablon == 0) $page_text .= "<div class=page_text>".$bodytext."</div>";
  else $page_text .= $bodytext;

  if (!isset($page_data)) $page_data = "";
  $page_date = "";
  if ($datashow == 1) $page_date .= "<address>".ss("Дата:")." <b>".$p_date."</b></address>";
  $page_data .= $p_date;

  $page_tags = "";
  if (($tags == 1 or $tags == 3) and trim($search) != "") {
    $searches = array();
    $search2 = explode(" ",$search);
    for ($x=0; $x < $search_num; $x++) {
      $searches[] = "<a class='slovo' href='/--slovo_".str_replace( "%","-", urlencode( $search2[$x] ) )."'>".str_replace("+","&nbsp;",$search2[$x])."</a>";
    }
    $page_tags .= $tag_text_show." ".implode(" | ", $searches)."";
  }

  $page_socialnetwork = "";
  if ($socialnetwork == 1) {
    $page_socialnetwork .= "<div id=\"socialnetwork\" class=\"socialnetwork\"><script src=\"//yandex.st/share/share.js\" charset=\"utf-8\"></script>".ss("Добавьте в социальные сети:")." <div class=\"yashare-auto-init\" data-yashareL10n=\"ru\" data-yashareType=\"none\" data-yashareQuickServices=\"yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,lj,moikrug,gplus\"></div></div>";
  }

  if ($favorites == 1) {
    global $http_siteurl, $sitename, $url, $lang;
    $url = urlencode($http_siteurl.$url);
    $tit3 = urlencode($pagetitle.$sitename); 
    $page_favorites = "<div id=\"favorites\" class=\"favorites\">".ss("Cохраните в закладках:")." <br>
    <a target='_blank' href='http://twitter.com/home?status=".$tit3.",%20".$url."'><img width='16' height='16' title=\"Twitter\" style=\"background-image: url(/images/favorit.gif); background-position: -80px 0px;\" src='/images/pixel.gif'></a> 
    <a target='_blank' href='http://www.google.com/bookmarks/mark?op=add&amp;bkmk=".$url."&amp;title=".$tit3."'><img width='16' height='16' title=\"Google\" style=\"background-image: url(/images/favorit.gif); background-position: -40px 0px; \" src='/images/pixel.gif'></a> ";
    // Добавить закладки для других языков
    if ($lang == "ru") $page_favorites .= "<a target='_blank' href='http://zakladki.yandex.ru/userarea/links/addfromfav.asp?bAddLink_x=1&amp;lurl=".$url."&amp;lname=".$tit3."'><img width='16' height='16' title=\"Яндекс.Закладки\" style=\"background-image: url(/images/favorit.gif); background-position: -60px 0px; \" src='/images/pixel.gif'></a> 
    <a target='_blank' href='http://news2.ru/add_story.php?url=".$url."'><img width='16' height='16' title=\"News2\" style=\"background-image: url(/images/favorit.gif); background-position: -140px 0px; \" src='/images/pixel.gif'></a> 
    <a target='_blank' href='http://memori.ru/link/?sm=1&amp;u_data[url]=".$url."&amp;u_data[name]=".$tit3."'><img width='16' height='16' title=\"Memori\" style=\"background-image: url(/images/favorit.gif); background-position: 0px 0px; \" src='/images/pixel.gif'></a> 
    <a target='_blank' href='http://bobrdobr.ru/addext.html?url=".$url."&amp;title=".$tit3."'><img width='16' height='16' title=\"БобрДобр\" style=\"background-image: url(/images/favorit.gif); background-position: -20px 0px; \" src='/images/pixel.gif'></a> 
    <a target='_blank' href='http://moemesto.ru/post.php?url=".$url."&amp;title=".$tit3."'><img width='15' height='16' title=\"МоёМесто\" style=\"background-image: url(/images/favorit.gif); background-position: -180px 0px; \" src='/images/pixel.gif'></a> ";
    $page_favorites .= "</div>";
  } else $page_favorites = "";

  if ($put_in_blog>0) {
  $page_blog = "<div id=\"put_in_blog\" class=\"put_in_blog\" OnClick=\"$('#onoffputinblog').toggle();\" style=\"cursor: pointer;\"><b style=\"border-bottom:1px dotted #999999;\">".ss("Разместите в своем блоге")."</b></div><div id=\"onoffputinblog\" class=\"editor\" style=\"display:none;\"><br>".ss("Код для вставки в блог:")."<br><div style=\"height:150px;overflow:scroll;background-color:#F5F5F5; padding-top:10px;\">";
  if ($put_in_blog==2) {
  $page_blog .= "&lt;table width=75% border=0 cellspacing=0 cellpadding=0 style=&quot;border: 1px solid #dfdfdf; background-color:#f1edd3; color: #333333;&quot;&gt;&lt;tr&gt; &lt;td valign=bottom&gt; &lt;div style=&quot;width: 95%;font-size:16px; font-weight:bold;padding: 5px 10px 5px 5px;&quot;&gt;&lt;img src=&quot;".$logo_link."&quot; width=&quot;102&quot; height=&quot;41&quot; align=&quot;left&quot; /&gt; &lt;a href=".$link." target=_blank style=&quot;text-decoration:none; color: #2e64c8;&quot;&gt; &nbsp;".$title_blog." &lt;/a&gt; &lt;/div&gt;&lt;/td&gt;&lt;/tr&gt; &lt;tr&gt; &lt;td align=left valign=top style=&quot;padding: 0 10px 0 10px; font-size:11px;&quot;&gt; ".$opentext_blog." &lt;/td&gt; &lt;/tr&gt; &lt;tr align=right&gt; &lt;td style=&quot;padding:5px 10px 0 10px&quot;&gt;&lt;div style=&quot;border-top: 1px solid #dfdfdf; padding-top: 10px; padding:5px 0 5px 0; font-size: 11px;&quot;&gt;&lt;a href=".$link." target=_blank style=&quot;color: #2e64c8;&quot;&gt;".ss("читать полностью")."&lt;/a&gt;&lt;/div&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;";
  } elseif ($put_in_blog==1) {
  $page_blog .= "&lt;table width=100% border=0 cellspacing=0 cellpadding=0 style=&quot;border: 1px solid #dfdfdf; background-color:#FFFFFF; color: #333333;&quot;&gt;&lt;tr&gt; &lt;td valign=bottom&gt; &lt;div style=&quot;width: 95%;font-size:16px; font-weight:bold;padding: 5px 10px 5px 5px;&quot;&gt; &lt;a href=".$link." target=_blank style=&quot;text-decoration:none; color: #2e64c8;&quot;&gt; ".$title_blog." &lt;/a&gt; &lt;/div&gt;&lt;/td&gt;&lt;/tr&gt; &lt;tr&gt; &lt;td align=left valign=top style=&quot;padding: 0 10px 0 10px; font-size:11px;&quot;&gt; ".$opentext_blog." &lt;/td&gt; &lt;/tr&gt; &lt;tr align=right&gt; &lt;td style=&quot;padding:5px 10px 0 10px&quot;&gt;&lt;div style=&quot;border-top: 1px solid #dfdfdf; padding-top: 10px; padding:5px 0 5px 0; font-size: 11px;&quot;&gt;&lt;a href=".$link." target=_blank style=&quot;color: #2e64c8;&quot;&gt;".ss("читать полностью")."&lt;/a&gt;&lt;/div&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;";
  }
  $page_blog .= "</div></div><br>";
  } else $page_blog = "";

  if ($search != "") { // Похожие новости // добавить настройку вкл/выкл
    $sql = "SELECT `pid`, `title`, `date` FROM ".$prefix."_pages WHERE `tables`='pages' and `module`='".$module."' and (`active`='1' or `active`='2') and `pid`!='".$pid."' and `cid`='".$cid."'".mysql_real_escape_string($search_keys)." order by `date` desc limit 0,5";
    $result = $db->sql_query($sql);
    $numrows = $db->sql_numrows($result);
    if ($numrows > 0 and $search_num>0) {
      $page_search_news = "<br><div class=page_another>".ss("В тему:")."</div> <div class=another_links>";
      while($row = $db->sql_fetchrow($result)) {
        $p_pid = $row['pid'];
        $p_title = $row['title'];
        $dat2 = explode(" ",$row['date']);
        $dat2 = explode("-",$dat2[0]);
        $p_p_date = intval($dat2[2])." ".findMonthName($dat2[1])." ".$dat2[0];
        $page_search_news .= "<div class=another_link><a href=/-".$DBName."_page_".$p_pid.">".$p_title."</a>";
        if ($datashow==1) $page_search_news .= "<br>".$p_p_date;
        $page_search_news .= "</div> ";
      }
      $page_search_news .= "</div><br>";
    }
  } else $page_search_news = "";

  // Рейтинг #######################################
  if ($golos==1 and $view!=4) {
    require_once ("golos.php");
    $page_reiting = golos_show($pid, $golostype, $gol); // (страница, тип рейтинга, кол-во рейтинга для типов 1, 2 и 3)
  } else $page_reiting = ""; // END OF Рейтинг #######################################

  global $comments;

  // Комментарии #######################################
  if ($comments>0 and $view!=4 and $nocomm == 0) {
    if (is_admin($admin)) { $adm = 1; } else { $adm = 0; }
    $add_css .= " comments_".$comment_shablon;
    $page_comments = "<div class=page_comm><a name=comm id=comm></a>".$comments_1."</div>
    <script>function showcomm(){
      $.get('comments.php', { p_id: '".$pid."', desc: '".$comments_desc."', sha: '".$comment_shablon."', vetki: '".$vetki."', num: '".$comments_num."', all: '".$comments_all."', mail: '".$comments_mail."', adres: '".$comments_adres."', tel: '".$comments_tel."' }, function(data) { 
      $('#page_comments').html( data );
      });
    }
    $(showcomm());</script><div id='page_comments'></div>";

    // Ссылка «Раскрыть все» // $all_numrows > $comments_num and  and $comments_num > 0
    if ($comments_all == 1) $page_comments .= "</div><br><a href=#comm id='allcomm_show' onclick=\"show('allcomm'); show('allcomm_show');\">".$comments_8."</a>";
    // END OF Комментарии ################################

    $page_add_comments = "";
    if ( $comments_add > 0) {
      $page_add_comments = addcomm($pid); // Форма добавления комментариев
      if ( $comments_add == 2 ) $page_add_comments .= "<br>".ss("<b>Внимание!</b> Информация будет добавлена на сайт после проверки администратором.");
    }
  } else $page_comments = "";

  /////////////////////////////////////// рейтинги
  if ($view==4) {
    $page_comments = "";
    global $com;
    $com = mysql_real_escape_string(intval($com));
    if ($com > 0) { ///////////////////////////////////// Вывод коммента
      $sql = "SELECT * FROM ".$prefix."_pages_comments WHERE `cid`='".$com."' and `active`='1'";
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $avtor = $row['avtor'];
      $text = explode("|&|",$row['text']);
      $page_comments .= "<table width=100% border=0 cellpadding=5>
      <tr width=50% valign=top><td align=right><u><b>".ss("Автор:")."</b></u></td><td width=50%><u>".$avtor."</u></td></tr></table>".$text[1];
    } else { //////////////////////////////////// Вывод всех комментов
       // (".$comm.")
      $page_comments .= "<div class=page_comm><a name=comm></a>".ss("Отзывы")."</div>";
      if ($comm > 0) {
        $sql = "SELECT * FROM ".$prefix."_pages_comments WHERE `num`='".$pid."' and `active`='1' order by `data` desc";
        $result = $db->sql_query($sql);
        $numrows = $db->sql_numrows($result);
        if ($numrows > 0) {
          $page_comments .= "<table width=100% border=0 cellpadding=5><tr valign=top><td align=center><b>".ss("Оценка")."</b></td><td align=center><b>".ss("Дата")."</b></td><td><b>".ss("Комментарий")."</b></td></tr>";
          $color = 1;
          while($row = $db->sql_fetchrow($result)) {
            $comm_cid = $row['cid'];
            $avtor = $row['avtor'];
            $text = str_replace("|&|","<br>",$row['text']);
            //$text = $text[0];
            $gol = $row['golos'];
            switch($gol) {
              case "1": $gol = "<font color=red>".ss("плохо")."</font>"; break;
              case "3": $gol = "<font color=darkblue>".ss("средне")."</font>"; break;
              case "5": $gol = "<font color=darkgreen>".ss("отлично")."</font>"; break;
              default: $gol = "<font color=darkblue>".ss("средне")."</font>"; break;
            }
            $ip = $row['ip']; //ip
            $date = str_replace(".","-",str_replace(" ","-",str_replace(":","-",$row['data'])));
            $datax = explode("-",$date);
            $datax1 = intval($datax[2])." ".findMonthName($datax[1])." ".$datax[0];
            //$datax2 = $datax[3].":".$datax[4]."";
            if ($color == 1) {
              $active_color = " style='background-color: #f1f1f1;'"; $color = 0; 
            } else {
              $active_color = " style='background-color: white;'"; $color = 1;
            }
            // № оценка дата автор комментарий
            $page_comments .= "<tr valign=top".$active_color."><td class=page_comm_golos' align=center><b>".$gol."</b></td>
            <td align=center>".$datax1."</td><td>".$text."";
            $page_comments .= "</td></tr>";
          }
          $page_comments .= "</table>";
          $page_comments .= "<div class=venzel></div>";
        }
      // END OF Комментарии ################################
      }
      $page_add_comments = addcomm_reiting($pid, $cid); // Форма добавления комментариев
    } // ELSE of Вывод коммента )
  } // Кончаем выводить рейтинги.

  //break; // Конец стандартных страниц
  //}
  //////////////////////////////////////////////// БАЗА ДАННЫХ
  } else { // Если это база данных
    global $strelka;

    $main_title = ""; //<br><a href=\"/-".$name."\">Вернуться назад</a><br>";
    if (isset($cid)) {
      $main_title .= top_menu($cid, 0);
    } else {
      $cid = 0;
    }
    $page_text = "";
    $page_opentext = "";
    // Определяем имя и настройки раздела
    $sql = "SELECT `title`, `text` FROM ".$prefix."_mainpage where `tables`='pages' and `type`='2' and `name`='".mysql_real_escape_string($name)."'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    //$module_id = $row['id']; // номер раздела
    $module_title = $row['title']; // Название раздела
    $module_options = explode("|",$row['text']); $module_options = $module_options[1]; 

    $page_title = "<div class='cat_title'><h1 class='cat_categorii_link'><a href='/-".$name."'>".$module_title."</a> ".$strelka." ".ss("Запись №").$pid."</h1></div>"; // Заголовок страницы

    $base = 0;
    parse_str($module_options); // Настройки раздела
    // Определяем имя и настройки БД
    $sql = "SELECT `name`, `title`, `text` FROM ".$prefix."_mainpage where `tables`='pages' and `id`='".mysql_real_escape_string($base)."'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    // $row['title']; // Название БД
    $baza_options  = $row['text']; 
    $baza_name  = $row['name']; // Название таблицы БД 
    parse_str($baza_options);
    $options = explode("/!/",$options); // $!$  ранее *
    $options_num = count($options);
    $names = array();
    $rus_names = array();
    $vagnost_names = array();
    $otkrytost_names = array();
    $zamena_names = array();
    $zapros_names = array();
    $type_names = array();
    for ($x=0; $x < $options_num; $x++) {
      $option = explode("#!#",$options[$x]); // #!#  ранее !
      $names[] = $option[0];
      if ($option[4] == 0 or $option[4] == 2 or $option[4] == 3) $zapros_names[] = $option[0];
      if ($option[4] == 0 or $option[4] == 2 or $option[4] == 3) $rus_names[] = $option[1];
      $type_names[] = $option[2];
      $vagnost_names[] = $option[3];
      $otkrytost_names[] = $option[4];
      $zamena_names[] = $option[5];
    }

    $zapros_names = implode(", ",$zapros_names).", `active`";
    $and = "`id`='".$pid."' and (`active`='1' or `active`='3')";

    if (isset($where)) {
      if ($where != "") $where = "where ".stripcslashes($where)." and ".$and;
      else $where = "where ".$and;
    } else $where = "where ".$and;

    if (!isset($order)) $order = "";

    if ($order != "") $order = " order ".stripcslashes($order).""; // сортировка
    $page_opentext .= "<div class=venzel></div>
    <table width=100% cellspacing=0 cellpadding=5 class=main_base_table>";

    $zapros_names = mysql_real_escape_string($zapros_names);
    $baza_name = mysql_real_escape_string($baza_name);
    $where = mysql_real_escape_string($where);

    $sql = "SELECT ".$zapros_names." FROM ".$prefix."_base_".$baza_name." ".$where."";
    $result = $db->sql_query($sql);
    $nu = $db->sql_numrows($result);

    global $siteurl;
  	$sql = "SELECT ".$zapros_names." FROM ".$prefix."_base_".$baza_name." ".$where."";
  	$result = $db->sql_query($sql);
  	$row = $db->sql_fetchrow($result);
  	$pass_num = count($row) / 2;
  	$active = $row['active'];
  	for ($x = 0; $x < $pass_num; $x++) {
  		if ($x != $pass_num - 1) { // Если это не последний элемент - active, то покажем
      	$page_opentext .= "<tr valign=top><td align=right><b>".$rus_names[$x]."</b></td><td>";
      	if ($type_names[$x] == aa("дата")) $row[$x] = date2normal_view($row[$x]);
      	if ($otkrytost_names[$x] == "3" and $active != "3") {
          $page_opentext .= "".$zamena_names[$x]."";
      	} else $page_opentext .= str_replace("\r\n","<br>",$row[$x]);
      	$page_opentext .= "</td></tr>";
  	  }
  	}
  	$page_opentext .= "</table>";
    if (!isset($pag)) $pag = 1;
    $page_opentext .= "<center>".topic_links($nu, $pag, "/-".$DBName."_cat_".$cid."_page_", $lim)."</center>";


    $p_pid = $page_comments = $page_search_news = $page_reiting = $page_date = $page_data = $page_tags = $page_favorites = $page_socialnetwork = $page_blog = $main_titleX = "";
  }

  ////////////////////////////////////////////////////////////////// ВЫВОД
  if ($page_shablon == 0) { // Если используются внутренние шаблоны
    // Получаем шаблон
    $sha = shablon_show("page", $view);
  } else { // Если используются внешние шаблоны
    // Доступ к шаблону
    $sql = "select `text` from ".$prefix."_mainpage where `tables`='pages' and `id`='".mysql_real_escape_string($page_shablon)."' and `type`='6'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $sha = $row['text'];

    // Ищем списки (доп. поля), относящиеся к нашим страницам по модулю
    $s_names = array();
    $s_opts = array();
    // Определим № раздела
    global $id_razdel_and_bd;
    $r_id = $id_razdel_and_bd[$DBName];

    $result5 = $db->sql_query("SELECT `id`, `name`, `text` FROM ".$prefix."_mainpage WHERE `tables`='pages' and (`useit` = '".mysql_real_escape_string($r_id)."' or `useit` = '0') and `type`='4'");
    while ($row5 = $db->sql_fetchrow($result5)) {
      $s_id = $row5['id'];
      $n = $row5['name'];
      $s_names[$s_id] = $n;
      // Найдем значение всех полей для данных страниц
      $result6 = $db->sql_query("SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".mysql_real_escape_string($n)."'");
      while ($row6 = $db->sql_fetchrow($result6)) {
        $n1 = $row6['name'];
        $n2 = explode(" ", str_replace("  ", " ", trim($row6['pages'])));
        foreach ($n2 as $n2_1 => $n2_2) {
          $s_opts[$n][$n2_2] = $n1;
        }
      }
    }
  }
  // Если в тексте обозначены комментарии - заменять их, а в самой странице - убрать.
  if (!isset($page_add_comments)) $page_add_comments = "";
  if (strpos($page_text,aa("[комментарии]"))) {
    $page_text = str_replace(aa("[комментарии]"), $page_comments."<br>".$page_add_comments, $page_text);
    $page_add_comments = "";
    $page_comments = "";
  }
  if (!isset($venzel)) $venzel = "";
  $sha_zamena = array(
  "[page_id]"=>$p_pid,
  "[page_title]"=>$page_title,
  "[page_opentext]"=>$page_opentext,
  "[page_text]"=>$page_text,
  "[page_comments]"=>$page_comments,
  "[page_add_comments]"=>$page_add_comments,
  "[page_search_news]"=>$page_search_news,
  "[page_reiting]"=>$page_reiting,
  "[venzel]"=>$venzel,
  "[main_title]"=>$main_title,
  "[page_date]"=>$page_date,
  "[page_data]"=>$page_data,
  "[page_tags]"=>$page_tags,
  "[page_favorites]"=>$page_favorites,
  "[page_socialnetwork]"=>$page_socialnetwork,
  "[page_blog]"=>$page_blog
  );
  $sha2 = strtr($sha,$sha_zamena);
  
  if (!isset($s_names)) $s_names = array();
  foreach ($s_names as $id2 => $nam2) {
    // Найдем значение каждого поля для данной страницы
    if (!isset($s_opts[$nam2][$p_pid])) $s_opts[$nam2][$p_pid] = "";
    $nam3 = $s_opts[$nam2][$p_pid];
    if (trim($nam3)=="") { // Дополнение для скрытия заголовков в случае наличия пустых строк
      // Заголовки в шаблоне должны обрамляться знаками ! (первый слева, последний справа)
      preg_match('|!'.$nam2.'(.*)'.$nam2.'!|Uis', $sha2, $matches);
      if (isset($matches[1])) $sha2 = str_replace($matches[1], "", $sha2) ;
    }
    $sha2 = str_replace("!".$nam2, "", $sha2);
    $sha2 = str_replace($nam2."!", "", $sha2);
    $sha2 = str_replace("[".$nam2."]", $nam3, $sha2);
  }
      
  $soderganie = str_replace("[menushow]",$main_titleX,$sha2); // что это? :)
  $soderganie2 = str_replace("[menushow]","",$sha2);
}
###################################################################
// Добавление комментария
function addcomm($pid) {
  $ret = "";
  # Настройка-----------------
  $usercomm=1; # писать неюзерам нельзя РЕАЛИЗОВАТЬ!!!
  $commentagain=0;

  global $soderganie, $DBName, $db, $prefix, $cookie, $module_name, $media_comment, $comments_mail, $comments_adres, $comments_tel, $comments_2, $comments_3, $comments_4, $comments_5, $comments_6, $comments_7, $tema_zapret_comm;
  $pid = intval($pid);
  // Понадобится если будут спамить в обход капче! Тогда использовать проверку времени!!!
  //$ip = getenv("REMOTE_ADDR"); // IP
  //$sql = "SELECT `gid` FROM ".$prefix."_pages_golos WHERE ip='".$ip."'";
  //$resnum = $db->sql_query($sql);
  //$numgolos = $db->sql_numrows($resnum);
  $ret .= "<br>";

  if (isset($_COOKIE["comment"])) {
    $comment = $_COOKIE["comment"];
    $comment = explode("|",$comment);
    $avtor = $comment[0];
    $mail = $comment[1];
    $adres = $comment[2];
    $tel = $comment[3];
  } else {
    $avtor = "";
    $mail = "";
    $adres = "";
    $tel = "";
  }

  $avt_key="<input type=text name='avtory' id='avtory' value=\"".$avtor."\" size=17> ";

  if ($commentagain==1) $ret .= ss("Вы можете оставить только один комментарий")."<br>";

  //$ver = mt_rand(10000, 99999); // получили случайное число

  $ret .= "<div class=send_comment>".$comments_2."</div><br>";

  $ret .= "<form method='post' action='/-".$DBName."?go=savecomm' name='add' class='addcomm'>
  <input name='num' value='".$pid."' type='hidden'>
  <input name='go' value='savecomm' type='hidden'>
  <input id='comm_otvet' name='comm_otvet' value='0' type='hidden'>
  <div id='comm_otvet_show' class='comm_otvet_show'></div>

  <div class='comm_form' id='comm_form'>
  <a name='addcomm'></a>

  <input type='hidden' name='avtor' id='avtor'>
  <input type='hidden' name='mail' id='mail'>

  ".$comments_3." ".$avt_key;

  if ($comments_mail == 1 or $comments_mail == 3) $ret .= "".$comments_4." <input type=text name='maily' id='maily' value=\"".$mail."\" size=17>"; else $ret .= "<input type=hidden name='maily' id='maily' value=\"".$mail."\">";

  if ($comments_adres == 1 or $comments_adres == 3) $ret .= "<br>".$comments_5." <input type=text name='adres' id='adres' value=\"".$adres."\" size=17>"; else $ret .= "<input type=hidden name='adres' id='adres' value=\"".$adres."\">";

  if ($comments_tel == 1 or $comments_tel == 3) $ret .= "".$comments_6." <input type=text name='tel' id='tel' value=\"".$tel."\" size=17>"; else $ret .= "<input type=hidden name='tel' id='tel' value=\"".$tel."\">";

  $kcaptcha = "<table width=100% style='margin:0;'><tr valign=top><td width=50%><img src='kcaptcha/index.php?".session_name()."=".session_id()."' style='border-radius: 5px; float:left; margin-right:10px;'>".ss("Введите код:")."<br><input type='text' name='keystring' size='5' maxlength='3'></td><td><input class='comm_submit' type='submit' name='ok' value='".ss("Отправить")."'></td></tr></table>";

  if ($tema_zapret_comm == 1 || $tema_zapret_comm == 2) $nolink = true; else $nolink = false;
  if ($media_comment==1) $ret .= site_redactor($nolink)."<div class='comm_label_textarea'>".$comments_7."</div><textarea id='area' class='redactor comm_textarea' name='info' style='width: 100%; height: 220px;'></textarea>".$kcaptcha;

  if ($media_comment==0) {
    $ret .= "<table width=100% cellspacing=0 cellpadding=0><tr valign=bottom><td width=350>".$comments_7."</td><td>
    <DIV class='editor' style='margin-top:10px; width:100%;'> 
    <DIV class='editorbutton' onclick=\"clc_bbcode('".ss("жирный',1)")."\"><IMG title='".ss("Жирный текст")."' src='images/bold.gif'></DIV>
    <DIV class='editorbutton' onclick=\"clc_bbcode('".ss("цитата',1)")."\"><IMG title='".ss("Вставить цитату")."' src='images/quote.gif'></DIV>";
    global $more_smile;
    if ($more_smile == true or $more_smile == false) $ret .= "<div id=\"cont\" class=\"editorbutton\" OnClick=\"show('onoffsmilies0');\" style=\"cursor: pointer;\"><img title=\"".ss("Показать смайлы: эмоции")."\" src=\"images/smilies/07.gif\"></div>";
    if ($more_smile == true) $ret .= "<div id=\"cont\" class=\"editorbutton\" OnClick=\"show('onoffsmilies1');\" style=\"cursor: pointer;\"><img title=\"".ss("Смайлы: альтернативная коллекция :)")."\" src=\"images/smilies/75.gif\"></div>
    <div id=\"cont\" class=\"editorbutton\" OnClick=\"show('onoffsmilies2');\" style=\"cursor: pointer;\"><img title=\"".ss("Смайлы: если эмоций маловато :)")."\" src=\"images/smilies/17.gif\"></div>
    <div id=\"cont\" class=\"editorbutton\" OnClick=\"show('onoffsmilies3');\" style=\"cursor: pointer;\"><img title=\"".ss("Смайлы: аниме-эмоции o_O")."\" src=\"images/smilies/18.gif\"></div>";
    $ret .= "</td></tr></table>
    <TEXTAREA id=area rows=7 style='font-size:18px;' name=info></TEXTAREA>
    ".$kcaptcha."
    <div id=\"onoffsmilies0\" class=\"editor\" style=\"display:none;\"><br><div class=\"editorbutton\">
    ".smile_generate(array("01","02","03","04","05","06","07","08","09",10,11,12,13,14,15,16,17,18))." <div OnClick=\"show('onoffsmilies0');\" style=\"cursor: pointer;\">".ss("Закрыть")."</div></div><br></div>"; // убран лишний div
    if ($more_smile == true) $ret .= "
    <div id=\"onoffsmilies1\" style=\"display:none;\"><br><br><div class=\"editorbutton\">".smile_generate(array(75,76,77,78,79,80,81,82,83,84,85,86,87,88))."</div></div>
    <div id=\"onoffsmilies2\" style=\"display:none;\"><br><br><div class=\"editorbutton\">".smile_generate(array(20,21,22,23,24,25,26,27,28,29,30,31,33,34,36,37,38,39,40,42,43,44,45,46,47,48,49,50,51,53,54,55,56,57,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74))."</div></div>
    <div id=\"onoffsmilies3\" style=\"display:none;\"><br><br><div class=\"editorbutton\">".smile_generate(array(90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,159,160,161,162))."</div></div>"; // </DIV>
  }
  $ret .= "</div></form>";
  // Для добавления аватара (мини-фото) к комментарию, введите свой email в комментарий и зарегистрируйтесь на <a href='http://ru.gravatar.com/site/signup/' target='_blank' rel='nofollow'>сайте «Gravatar»</a>, если вы не сделали этого ранее.
  return $ret;
}

##############################################################################
function add_base($baza_name,$name) {
  # Настройка-----------------
  $usercomm=1; # писать неюзерам нельзя РЕАЛИЗОВАТЬ!!!
  global $soderganie, $DBName, $db, $prefix, $module_name, $admin, $tema, $tema_name, $tema_title, $tema_opis, $post;
  $soderganie .= "<br><a href=/-".$name."_addbase_".$baza_name."><b>".ss("Добавить в базу данных")."</b></a>";
}
###########################################################
// Добавление строки в базу данных
function addbase($base,$name,$spa=0) {
  global $soderganie, $DBName, $db, $prefix, $cookie, $module_name, $admin, $tema, $tema_name, $tema_title, $tema_opis, $post;
  if ($spa == 1) $soderganie .= ss("<b>Спасибо.</b><br>В ближайшее время ваша информация будет проверена и размещена на сайте.");
  else {
  # Настройка-----------------
  $usercomm = 1; # писать неюзерам нельзя РЕАЛИЗОВАТЬ!!!
  # Настройка-----------------
  $soderganie .= ss("<h3>Добавление информации в базу данных</h3>");
  // Заменить календарь!!!
  $soderganie .= "<!-- calendar -->
  <LINK rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=/includes/calendar/calendar-win2k-cold-1.css title=\"win2k-cold-1\"> 
  <SCRIPT src=/includes/calendar/calendar.js></SCRIPT> 
  <SCRIPT src=/includes/calendar/calendar-en.js></SCRIPT> 
  <SCRIPT src=/includes/calendar/calendar-setup.js></SCRIPT>
  <!-- / calendar -->";
  $soderganie .= "<form method=\"POST\" action=\"/-".$DBName."\" enctype=\"multipart/form-data\">
  <table width=100%><tr valign=top><td>";

  $sql2 = "SELECT `name`, `text` FROM ".$prefix."_mainpage WHERE `tables`='pages' and `id`='".mysql_real_escape_string($base)."' and `type`='5'";
  $result2 = $db->sql_query($sql2);
  $row2 = $db->sql_fetchrow($result2);
  // Верстаем данные, которые заносятся в таблицу
  $text = explode("|",$row2['text']); 
  $base_name = $row2['name'];
  $text = $text[1];
  $noadd="";
  parse_str($text);
  $noadd = explode(" ",$noadd);
  $options = explode("/!/",$options); // $!$ - удалять это из строки!!!
  $n = count($options);
  for ($x=0; $x < $n; $x++) {
  	$one = explode("#!#",$options[$x]); // #!# - удалять это из строки!!!
  	if (!in_array($one[0],$noadd)) {
  		switch ($one[2]) {
        case aa("текст"): 
          $soderganie .= "<p><b>".$one[1].":</b><br>
          <textarea name=\"text[".$one[0]."]\" rows=\"5\" cols=\"80\"></textarea>
          <input type=hidden name=\"type[".$one[0]."]\" value=\"".aa("текст")."\"><br></p>";
          break;

        case aa("строка"): 
          $soderganie .= "<p><b>".$one[1].":</b><br><input type=text id=\"".$one[0]."\" name=\"text[".$one[0]."]\" value=\"\" size=40>";
          // Добавляем выбор вариантов
          $stroka = $one[0]; 
          $opt = "";
          $rows = array();
          $sql1 = "SELECT ".mysql_real_escape_string($stroka)." FROM ".$prefix."_base_".mysql_real_escape_string($base_name)." where `active`='1' or `active`='3'";
          $result1 = $db->sql_query($sql1);
          while ($row1 = $db->sql_fetchrow($result1)) {
            $rows[] = $row1[$stroka];
          }
          $rows = array_unique($rows);
          foreach ($rows as $r) {
            $opt .= "<option value=\"".$r."\">".$r."</option>";
          }
          if ($one[4] == 0 or $one[4] == 2) $soderganie .= " <select name=vybor[".$one[0]."] onchange=\"document.getElementById('".$one[0]."').value = this.value;\"><option value=\"\">".ss("напишите или выберите вариант")."</option>".$opt."</select>
          <input type=hidden name=\"type[".$one[0]."]\" value=\"".aa("строка")."\"></p>"; 
          break;

        case aa("число"): 
          $soderganie .= "<p><b>".$one[1].":</b><br><input type=text name=\"text[".$one[0]."]\" value=\"\" size=5>".ss(" (только число)")."<input type=hidden name=\"type[".$one[0]."]\" value=\"".aa("число")."\"></p>"; 
          break;

        case aa("дата"): 
          $soderganie .= "<p><b>".$one[1].":</b><br>
          <TABLE cellspacing=0 cellpadding=0 style=\"border-collapse: collapse\"><TBODY><TR valign=top> 
           <TD><INPUT type=text name=\"text[".$one[0]."]\" id=\"f_date_c[".$one[0]."]\" readonly=1 size=15></TD> 
           <TD><IMG src=/images/calendar.gif id=\"f_trigger_c[".$one[0]."]\" title=\"Выбор даты\" onmouseover=\"this.style.background=&#39;red&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\">".ss(" (выберите дату из меню)")."</TD> 
          </TR></TBODY></TABLE>
          <SCRIPT> 
              Calendar.setup({
                  inputField     :    \"f_date_c[".$one[0]."]\",     // id of the input field
                  ifFormat       :    \"%e %B %Y\",      // format of the input field
                  button         :    \"f_trigger_c[".$one[0]."]\",  // trigger for the calendar (button ID)
                  align          :    \"Tl\",           // alignment (defaults to \"Bl\")
                  singleClick    :    true
              });
          </SCRIPT><input type=hidden name=\"type[".$one[0]."]\" value=\"".aa("дата")."\"></p>"; 
          break;

        case aa("датавремя"): 
          $soderganie .= "<p><b>".$one[1].":</b><br><input type=text name=\"text[".$one[0]."]\" value=\"\" size=17>
          <input type=hidden name=\"type[".$one[0]."]\" value=\"".aa("датавремя")."\"></p>";
          break;
  		}
  	}
  }
  $soderganie .= "<br><br><TABLE cellspacing=0 cellpadding=0><TBODY><TR valign=top> 
  <TD width=200>".ss("Введите код:")." <input type=text name=keystring size=3 maxlength=3></TD><TD width=100><img src='kcaptcha/index.php?".session_name()."=".session_id()."'></TD>
  <TD><input type=submit value=\"".ss("Добавить информацию")."\"></TD>
  </TR></TBODY></TABLE>
  <input type='hidden' name='id' value='0'>
  <input type='hidden' name='basename' value='".$base_name."'>
  <input type='hidden' name='name' value='".$name."'>
  <input type='hidden' name='go' value='savebase'>
  </td></tr></table></form>";
  }
}
###########################################
function savebase ($name, $basename, $type, $text) { // Сохранение добавления строки в базу данных
  $link = getenv("HTTP_REFERER");
  global $_SESSION, $_POST, $soderganie, $DBName, $db, $prefix, $module_name, $post, $captcha_ok;
  // Ввести проверку на активность - проверка постов администратором
  if ($post==1) $active = 1;
  if ($post==2) $active = 0;
  if ($post==3) $active = 2;
  if( (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring']) or $captcha_ok == 1) {
    $texts = mysql_real_escape_string(implode("', '",$text));
    $types = array_keys($text);
    $types = mysql_real_escape_string(implode("`, `",$types));
    $db->sql_query("INSERT INTO ".$prefix."_base_".mysql_real_escape_string($basename)." (`id`, `".$types."`, `active`) VALUES (NULL, '".$texts."', '2')") or die(ss("Ошибка базы данных: Не удается добавить вашу информацию."));
    $location = "/-".$DBName.""; // _addbase_1
  } else die(ss("Ошибка: Вы неправильно ввели код проверки. Нажмите в браузере «Назад»."));
  //} else die("Ошибка: вероятно попытка взлома или добавление в базу данных из сохраненной страницы. Вернитесь на сайт! name - $name, basename - $basename");
  unset($_SESSION['captcha_keystring']);
  global $siteurl;
  recash(str_replace("http://".$siteurl,"",$link)); // Обновление кеша
  recash(str_replace("http://".$siteurl,"",getenv("REQUEST_URI")),0);
  Header("Location: $location");
}
########################################################################################
function addpost($cid) {
  $ret = "";
  # Настройка-----------------
  $usercomm = 1; # писать неюзерам нельзя РЕАЛИЗОВАТЬ!!!
  # Настройка-----------------
  global $soderganie, $media_post, $DBName, $db, $prefix, $cookie, $module_name, $admin, $tema, $tema_name, $tema_title, $tema_opis, $post, $tema_zapret;
  $cid = intval($cid);
  $DBName = mysql_real_escape_string($DBName);
  $sql = "select `id`, `shablon` from ".$prefix."_mainpage where `tables`='pages' and `name`='".$DBName."' and `type`='2'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $id = mysql_real_escape_string($row['id']);
  $shablon = explode(aa("[следующий]"),$row['shablon']);
  $shablon1 = $shablon[0];
  if (isset($shablon[1])) $shablon2 = $shablon[1]; else $shablon2 = "";
  $ret .= "<br>";
  $anonymous="";
  $avt = $anonymous;

  $chars = array('&nbsp;', '&brvbar;', '&sect;', '&raquo;', '&para;', '&copy;', '&reg;', '&micro;', '&laquo;', '&not;', '&shy;', '&plusmn;', '&middot;'); 
 
  if (function_exists('mb_internal_encoding')) mb_internal_encoding('utf-8');

  $ret .= "<a name='send_post'></a><div style='display:none;' id='minus2' class='send_post'><p><b>".$tema.":</b></div>
  <div class='send_post' id='cross2'><p><a href='#send_post' onclick=\"show('minus2'); show('panel2'); show('cross2')\" class='send_post'> ".$tema." </a></div>
  <div style='display:none' id='panel2' class='addpost'>";

  $ret .= "<form method='post' action='/-".$DBName."?go=savepost' name='addpost' class='addpost'><input name='go' value='savepost' type='hidden'>";

  if ($cid==0) $main="<option value='0'>".ss("Главная страница раздела")."</option>"; else $main="";

  $result = $db->sql_query("select `cid`, `title`, `parent_id` from ".$prefix."_pages_categories where `module`='".$DBName."' and `tables`='pages' order by `parent_id`, `title`");
  $num = $db->sql_numrows($result);
  if ($num > 0) {
    $ret .= "<label><b>".ss("Выберите раздел:")."</b></label> <select name='num'>".$main."";
    while ($row = $db->sql_fetchrow($result)) {
      $cid2 = $row['cid'];
      $title = $row['title'];
      $parentid = $row['parent_id'];
      $title = getparent_for_addpost($DBName,$parentid,$title);
      if ($parentid != 0) $title = "&bull; ".$title;
      if ($cid == $cid2) $sel = "selected"; else $sel = "";
      if ($parentid == 0) {
        // занести в переменную, если есть подпапки
        $first_opt[$cid2] = "<option value='".$cid2."' ".$sel." style='background:#fdf;'>".$title."</option>"; 
      }
      if ($parentid != 0) {
        // вывести и очистить переменную
        $ret .= $first_opt[$parentid];
        $first_opt[$parentid] = "";
        $ret .= "<option value='".$cid2."' ".$sel.">".$title."</option>";
      }
      $last_cid = $cid2;
    }
    if (count($first_opt)>0) 
    foreach( $first_opt as $key => $value ) {
     if ($first_opt[$key] != "") $ret .= $first_opt[$key];
    }
    $ret .= "</select><br><br>";
  } else $ret .= "<input type='hidden' name='num' value='0'>";

  $ret .= "<label for=title><b>".$tema_title.":</b></label> <input type=text name=post_title size=40 maxlength=255 style='width:98%;'><br>";

  $kcaptcha = "<table width=100% style='margin:0;'><tr valign=top><td width=50%><img src='kcaptcha/index.php?".session_name()."=".session_id()."' style='border-radius: 5px; float:left; margin-right:10px;'>".ss("Введите код:")."<br><input type='text' name='keystring' size='5' maxlength='3'></td><td><input class='comm_submit' type='submit' name='ok' value='".ss("Отправить")."'></td></tr></table>";

  if ($tema_zapret == 1 || $tema_zapret == 2) $nolink = true; else $nolink = false;
  if ($media_post==1) $ret .= site_redactor($nolink);
  $ret .= "<input type='hidden' name='avtor' id='avtor'>
  <input type='hidden' name='mail' id='mail'>";

  if (trim($tema_name)!="no") $ret .= "<br><label for=avtory><b>".$tema_name.":</b></label> <br><textarea rows=3 cols=20 id=avtor style='width:100%;' name=avtory class='post_textarea post_name'>".$shablon1."</textarea><br>";
  else $ret .= "<input type=hidden name='avtory' value=\"".$avt."\">";

  if (trim($tema_opis)!="no") $ret .= "<br><b>".$tema_opis.":</b><br><textarea rows=10 cols=20 id=area style='width: 100%; height: 220px;' name=info class='redactor post_textarea post_opis'>".$shablon2."</textarea>";
  else $ret .= "<input type=hidden name=info>";

  global $view;
  if ($view != 4) {
    // Подсоединие списков ////////////////////////////////
    // Ищем все списки по разделу
    $sql = "select `id`, `title`, `name`, `text` from ".$prefix."_mainpage where `tables`='pages' and (`useit`='".$id."' or `useit`='0') and `type`='4' order by `id`";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result)) {
      $s_id = $row['id'];
      $s_title = $row['title'];
      $s_name = $row['name'];
      $options = explode("|", $row['text']); $options = $options[1];
      $type=0; $shablon=""; 
      parse_str($options); // раскладка всех настроек списка

      switch($type) {
        case "4": // строка
          $ret .=  "<br><br><b>".$s_title.":</b><br><input name=\"add[".$s_name."]\" type=text style='width:98%;' value='".$shablon."'>";
          break;

        case "3": // период времени
          $ret .=  "<br><br><b>".$s_title.":</b> ".ss("(выберите даты из меню, кликнув по значкам)")."<br>
          <table cellspacing=0 cellpadding=0 style=\"border-collapse: collapse\"><tbody><tr valign=top> 
          <td><input type=text name=\"text[".$s_name."]\" id=\"f_date_c[".$s_name."]\" value=\"\" onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD>
          <td><img src=/images/calendar.gif id=\"f_trigger_c[".$s_name."]\" title=\"".ss("Выбор даты")."\" onmouseover=\"this.style.background=&#39;red&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\"></td>
          <td width=20 align=center> - </td>
          <td><input type=text name=\"text[".$s_name."]\" id=\"f_date_c2[".$s_name."]\" value=\"\" onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></td> 
          <td><img src=/images/calendar.gif id=\"f_trigger_c2[".$s_name."]\" title=\"".ss("Выбор даты")."\" onmouseover=\"this.style.background=&#39;red&#39;;\" onmouseout=\"this.style.background=&#39;&#39;\"></td>
          </tr></tbody></table>
          <script> 
              Calendar.setup({
                  inputField     :    \"f_date_c[".$s_name."]\",     // id of the input field
                  ifFormat       :    \"%e %B %Y\",      // format of the input field
                  button         :    \"f_trigger_c[".$s_name."]\",  // trigger for the calendar (button ID)
                  align          :    \"Tl\",           // alignment (defaults to \"Bl\")
                  singleClick    :    true
              });
          </script>
          <script> 
              Calendar.setup({
                  inputField     :    \"f_date_c2[".$s_name."]\",     // id of the input field
                  ifFormat       :    \"%e %B %Y\",      // format of the input field
                  button         :    \"f_trigger_c2[".$s_name."]\",  // trigger for the calendar (button ID)
                  align          :    \"Tl\",           // alignment (defaults to \"Bl\")
                  singleClick    :    true
              });
          </script>
          <input type=hidden name=\"add[".$s_name."]\" id=\"add[".$s_name."]\" value=\"".aa("дата")."\">"; //
          break;

        case "2": // файл
          // file=pic&papka=/img/=verh&resizepic=x&file=&picsize=600&minipic=1&resizeminipic=x&minipicsize=100
          /*
          switch($fil) {
          case "pic": $type_fil = "картинка"; break;
          case "doc": $type_fil = "документ/архив"; break;
          case "flash": $type_fil = "flash-анимация"; break;
          case "avi": $type_fil = "видео-ролик"; break;
          }
          switch($mesto) {
          case "verh": $type_mesto = "сверху"; break;
          case "niz": $type_mesto = "снизу"; break;
          }
          */
          //$type_mini="";
          //if ($minipic==1) $type_mini = "Также будет создана миниатюра.";
          $ret .=  "<br><br><b>".$s_title.":</b><br><input type=file name=\"add[".$s_name."]\" size=30> 
          <b>".ss("или ссылка:")."</b> <input type=text name=\"add[".$s_name."]_link\" value=\"".$papka."\" size=30>";
          break;

        case "1": // текст
          $ret .=  "<br><br><b>".$s_title.":</b><br><textarea name=\"add[".$s_name."]\" rows=\"3\" cols=\"60\" style='width:98%;'>".$shablon."</textarea>";
          break;

        case "0": // список слов
          $ret .=  "<br><br><b>".$s_title.":</b><br>";
          $sql2 = "select * from ".$prefix."_spiski where `type`='".mysql_real_escape_string($s_name)."' order by `parent`,`id`";
          $result2 = $db->sql_query($sql2);
          //  size=10 multiple=multiple
          $ret .=  "<select name=\"add[".$s_name."][]\" style='font-size:11px; width:98%;'>";
          while ($row2 = $db->sql_fetchrow($result2)) {
            $s_id2 = $row2['id'];
            $s_title2 = $row2['name'];
            $s_opis = $row2['opis'];
            $s_parent = $row2['parent'];
            $s_title2 = getparent_spiski($s_name,$s_parent,$s_title2);
            $sel = ""; //if ($razdel == $s_id2) $sel = " selected";
            $ret .=  "<option value=".$s_id2.$sel."> ".$s_title2."</option>";
          }
          $ret .=  "</select>";
          break;
      }
    }
  } // end if ($view==4) {
  $ret .= "<input type='hidden' name='keystring' value='".$chars[array_rand($chars)]."'>".$kcaptcha;

  if ($post==2) $ret .= "<br>".ss("Информация будет добавлена на сайт сразу после проверки администратором.");
  //if ($post==3) $ret .= "<br>Информация будет добавлена на сайт, но появится в RSS только после проверки администратором.";
  $ret .= "</form></div>";
  return $ret;
}
###########################################
// Сохранение страницы, добавленной посетителем
function savepost ($avtory, $avtor, $mail, $post_title, $info, $num, $cid, $add){
  global $ip;
  $link = getenv("HTTP_REFERER");
  global $_SESSION, $_POST, $soderganie, $lang, $media_post, $DBName, $now, $db, $prefix, $module_name, $post, $admin, $tema_zapret, $tema_zapret, $captcha_ok;
  // Ввести проверку на активность - проверка постов администратором

  $active = 3;
  if ($post==1) { $inform = ss("Информация добавлена на сайт."); $active = 1; }
  if ($post==2) { $active = 3; $inform = ss("Информация будет добавлена на сайт после проверки администратором."); }
  if ($post==3) { $active = 2; $inform = ss("Информация добавлена на сайт, но в ближайшее время пройдет модерацию."); }
  if (is_admin($admin)) $active = 1;

  //$date = date("Y-m-d H:i:s");
  //$ip = getenv("REMOTE_ADDR"); // IP
  $num = intval($num);
  $avtory = trim(str_replace("  "," ",filter($avtory, "nohtml")));
  $post_title = trim(str_replace("document.cookie","", str_replace("  "," ",filter($post_title, "nohtml"))));

  if ($media_post == 0) $info = bbcode(trim(str_replace("document.cookie","",filter($info, "nohtml"))));
  if ($media_post == 1) $info = filter($info);

  //$pattern = "/onlinedisk.ru\/image\/"."(\d+)"."\/IMG"."(\d+)".".jpg"."/i";
  //$replacement = "onlinedisk.ru/get_image.php?id=$1";
  //$avtor =  preg_replace($pattern, $replacement, $avtor);
  //$info =  preg_replace($pattern, $replacement, $info);

  // Узнаем настройку раздела
  /*
  $sql3 = "select `text` from `".$prefix."_mainpage` where `name`='$DBName' and `type`='2'";
  $result3 = $db->sql_query($sql3);
  $row3 = $db->sql_fetchrow($result3);
  if (trim($row3['text'])!="") {
    $main_file = explode("|",$row3['text']);
    $main_options = $main_file[1];
    parse_str($main_options);
  }
  */
  if (($tema_zapret == 1 || $tema_zapret == 2) and ( strpos(" ".$avtory.$info.$post_title, "://") or strpos(" ".$avtory.$info.$post_title, "www.") ) ) die(ss("Запрещено размещать информацию, содержащую ссылки. Это защита от спама. <br><b>Если ссылку разместить необходимо - пишите ее без http:// и www"));

  if ($lang == "ru") 
      if ($avtor != "" || $mail != "" || 
        (!preg_match("#[а-яА-Я]#i",$info) && $media_post == 0) || 
        (!preg_match("#[а-яА-Я]#i",$info) && $media_post == 1 && !strpos(" ".$info, 'a') && !strpos(" ".$info, 'img') && !strpos(" ".$info, 'iframe') && !strpos(" ".$info, 'object') ) ) die(ss("Запрещено размещать спам."));

  $addpost = false;
  if ($avtory != "" AND $info != "") {
      if( ( (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring']) or $captcha_ok == 1) ) {
        $addpost = true;
        // возможно стоит вставить проверку промежутка времени между двумя постами...
      } else {
        if (is_admin($admin)) {
          $addpost = true;
        } else die(ss("Ошибка: Вы неправильно ввели код проверки. Нажмите в браузере «Назад»."));
      }
    } else die(ss("Ошибка: Вы не ввели основную информацию. Нажмите в браузере «Назад»."));

  if ($addpost == true) {
    // добавим страницу
    $db->sql_query("INSERT INTO ".$prefix."_pages (pid, module, cid, title, open_text, main_text, date, redate, counter, active, golos, comm, foto, search, mainpage, rss) VALUES (NULL, '".mysql_real_escape_string($DBName)."', '".mysql_real_escape_string($num)."', '".mysql_real_escape_string($post_title)."', '".mysql_real_escape_string($avtory)."', '".mysql_real_escape_string($info)."', '".mysql_real_escape_string($now)."', '".mysql_real_escape_string($now)."', '0', '".$active."', '0', '0', '', '', '', '0')") or die(ss("Ошибка базы данных: Не удается добавить вашу информацию."));

    // получим id добавленной страницы
    $row = $db->sql_fetchrow($db->sql_query("select `pid` from ".$prefix."_pages where `tables`='pages' and `title`='".mysql_real_escape_string($post_title)."' and `date`='".mysql_real_escape_string($now)."'"));
    $page_id = $row['pid'];

    // РАБОТА СО СПИСКАМИ
    if (is_array($add)) {
      foreach ($add as $name => $elements) {
        // Получение информации о каждом списке
        $sql = "select * from ".$prefix."_mainpage where `tables`='pages' and `name`='".mysql_real_escape_string($name)."' and `type`='4'";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $s_id = $row['id'];
        $options = explode("|", $row['text']); $options = $options[1];
        $type=0; $shablon=""; 
        parse_str($options); // раскладка всех настроек списка
        //if ($type!=1) { $type=0; $type_name="список"; } else { $type_name="текст"; }

        switch($type) {
          ////////////////////////////////////////////////////////////////////////////
          case "4": // строка
                  // Проверяем наличие подобного текста
                  $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".mysql_real_escape_string($name)."' and `name`='".mysql_real_escape_string($elements)."'";
                  $result = $db->sql_query($sql);
                  $numrows = $db->sql_numrows($result);
                  if ($numrows > 0) { // если элемент найден
                      $row = $db->sql_fetchrow($result);
                      $s_pages = $row['pages'];
                      $s_name = $row['name'];
                          if (strpos($agent," ".$page_id." ") < 1 and $s_name == $elements) {
                              $s_pages .= " ".$page_id." ";
                              $s_pages = str_replace("  "," ",$s_pages);
                              $db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".$s_pages."' WHERE `type`='".mysql_real_escape_string($name)."' and `name`='".mysql_real_escape_string($elements)."'") or die (ss("Ошибка: Не удалось обновить список."));
                          } else {
                              $db->sql_query("INSERT INTO ".$prefix."_spiski (`id`, `type`, `name`, `opis`, `sort`, `pages`, `parent`) VALUES (NULL, '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($elements)."', '', '0', ' ".mysql_real_escape_string($page_id)." ', '0');") or die (ss("Ошибка: Не удалось сохранить список."));
                          }
                  } else { // если элемент новый
                      $db->sql_query("INSERT INTO ".$prefix."_spiski (`id`, `type`, `name`, `opis`, `sort`, `pages`, `parent`) VALUES (NULL, '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($elements)."', '', '0', ' ".mysql_real_escape_string($page_id)." ', '0');") or die (ss("Ошибка: Не удалось сохранить список."));
                  }
          break;
          ////////////////////////////////////////////////////////////////////////////
          case "3": // период времени
                  // создаем диапазон дат и все их проверяем
                  $elements = explode("|",$elements);
                  $dat1 = date2normal_view($elements[0], 1);
                  $dat2 = date2normal_view($elements[1], 1);
                  $period = period($dat1, $dat2);
                  // и все даты проверяем на наличие в БД
                  $upd = array();
                  $noupd = array();
                  $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".mysql_real_escape_string($name)."' order by `name`";
                  $result = $db->sql_query($sql);
                  while ($row = $db->sql_fetchrow($result)) {
                    $nam = $row['name']; // дата
                    $pag = trim($row['pages']); // страницы
                    if (in_array($nam, $period)!=FALSE) { 
                      $noupd[] = $nam; // для INSERT
                      if (strstr($pag,$page_id)==FALSE) $upd[] = $nam; // для UPDATE
                    }
                  }
                  $insert = array();
                  $update = array();
                  foreach ($upd as $up) {
                    //if (!in_array($up, $noupd)) 
                    $update[] = "name='".$up."'";
                  }
                  foreach ($period as $per) {
                    if (!in_array($per, $noupd)) $insert[] = "(NULL, '".$name."', '".$per."', '', '0', ' ".$page_id." ', '0')";
                  }

                  $insert = mysql_real_escape_string(implode(", ",$insert));
                  $update = mysql_real_escape_string(implode(" or ",$update));

                  $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".mysql_real_escape_string($name)."' and (".$update.") order by `name`";
                  $result = $db->sql_query($sql);
                  while ($row = $db->sql_fetchrow($result)) {
                    $na = $row['name']; // дата
                    $pa = $row['pages']; // страницы
                    if (trim($update) != "")
                      $db->sql_query("UPDATE ".$prefix."_spiski SET `pages` = ' ".mysql_real_escape_string($pa)." ".mysql_real_escape_string($page_id)." ' WHERE `type`='".mysql_real_escape_string($name)."' and `name`='".mysql_real_escape_string($na)."'") or die (ss("Ошибка: Не удалось обновить списки."));
                  }
                  if (trim($insert) != "") $db->sql_query("INSERT INTO ".$prefix."_spiski (`id`, `type`, `name`, `opis`, `sort`, `pages`, `parent`) VALUES ".$insert.";") or die (ss("Ошибка: Не удалось сохранить списки."));

          break;
          ////////////////////////////////////////////////////////////////////////////
          case "2": // файл НЕОКОНЧЕНО!
                  // Смотрим настройки - тип файла и что с ним делать
                  // Закачиваем файл
                  // Транслит файла и смена имени на тип и дату
                  // Изменение размеров
                  // Записываем ссылку на него в определенное поле
          break;
          ////////////////////////////////////////////////////////////////////////////
          case "1": // текст
                  // Проверяем наличие подобного текста
                  $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".mysql_real_escape_string($name)."' and `name`='".mysql_real_escape_string($elements)."'";
                  $result = $db->sql_query($sql);
                  $numrows = $db->sql_numrows($result);
                  if ($numrows > 0) { // если элемент найден
                      $row = $db->sql_fetchrow($result);
                      $s_pages = $row['pages'];
                      $s_name = $row['name'];
                          if (strpos($s_pages," $page_id ") < 1 and $s_name == $elements) {
                              $s_pages .= " $page_id ";
                              $s_pages = str_replace("  "," ",$s_pages);
                              $db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".mysql_real_escape_string($s_pages)."' WHERE `type`='".mysql_real_escape_string($name)."' and `name`='".mysql_real_escape_string($elements)."'") or die ('Ошибка: Не удалось обновить списки.');
                              //echo "up";
                          } else {
                              $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($elements)."', '', '0', ' ".mysql_real_escape_string($page_id)." ', '0');") or die (ss("Ошибка: Не удалось сохранить списки."));
                              //echo "in1";
                          }
                  } else { // если элемент новый
                      $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".mysql_real_escape_string($name)."', '".mysql_real_escape_string($elements)."', '', '0', ' ".mysql_real_escape_string($page_id)." ', '0');") or die (ss("Ошибка: Не удалось сохранить списки."));
                      //echo "in2";
                  }
          break;
          ////////////////////////////////////////////////////////////////////////////
          case "0": // список
                  // Проверяем сколько элементов в списке
                  $num = count($elements);
                  for ($x=0; $x < $num; $x++) { // посчитали сколько номеров списка
                    if ($elements[$x] != 0) {
                      // узнаем какие страницы уже есть у этого номера из списка
                      $sql = "SELECT `pages` FROM ".$prefix."_spiski WHERE `id`='".mysql_real_escape_string($elements[$x])."'";
                      $result = $db->sql_query($sql);
                      $row = $db->sql_fetchrow($result);
                      $s_pages = $row['pages'];
                      if (strpos($s_pages," ".$page_id." ") < 1) {
                        $s_pages .= " ".$page_id." ";
                        $s_pages = str_replace("  "," ",$s_pages);
                        // теперь присвоем каждому из элементов списка id страницы, которую редактируем.
                        $db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".$s_pages."' WHERE `id`='".mysql_real_escape_string($elements[$x])."'") or die(ss("Ошибка: Не удалось добавить страницы в список."));
                      }
                    }
                  }
          break;
        } // switch
      } // for
    } // if
    $location = "/-".$DBName.""; // _cat_".$num."#comm
  } else die(ss("Ошибка: Возможно вы используете программу для отправки сообщений."));

  unset($_SESSION['captcha_keystring']);

  header ("Content-Type: text/html; charset=utf-8");
  echo "<html><head><meta http-equiv='Refresh' content='6; URL=".$location."'></head><body>
  <h2>".ss("Спасибо!")."</h2>".$inform."<br>".ss("Через 6 секунд откроется предыдущая страница.<br>Также вы можете перейти на <a href='/'>Главную</a>.")."</body></html>";
  global $siteurl; #######################################################################
  recash(str_replace("http://".$siteurl,"",getenv("HTTP_REFERER"))); // Обновление кеша ##
  recash(str_replace("http://".$siteurl,"",getenv("REQUEST_URI")),0); ####################
  ########################################################################################
  exit;
}
###########################################
// Добаление рейтинга-комментария
function addcomm_reiting($pid, $cid) {
  $ret = "";
  # Настройка-----------------
  //$usercomm=1; # писать неюзерам нельзя РЕАЛИЗОВАТЬ!!!
  //$commentagain=1; // У рейтингов только один раз можно!!!
  # Настройка-----------------
    global $soderganie, $DBName, $db, $prefix, $cookie, $module_name, $admin, $reiting_data;
    $pid = mysql_real_escape_string(intval($pid));
    $cid = mysql_real_escape_string(intval($cid));
  $avt = $anonymous;
  $reiting = "";
  if ($cid != 0) {
    $sql4 = "SELECT `description` FROM ".$prefix."_pages_categories where `cid`='".$cid."' and `tables`='pages'";
    $result4 = $db->sql_query($sql4);
    $row4 = $db->sql_fetchrow($result4);
    $reiting = trim($row4['description']);
  }
  // cid num avtor text ip data golos 
  $ip = getenv("REMOTE_ADDR"); // IP - заменить!
  $sql2 = "SELECT `cid` FROM ".$prefix."_pages_comments WHERE `num`='".$pid."' and `ip`='".$ip."'";
  $result2 = $db->sql_query($sql2);
  $numrows2 = $db->sql_numrows($result2);

  $colvo_otzyv = 1000000; // Кол-во отзывов вывести в настройки!!!

  $colvo_otzyvX = $colvo_otzyv - 1;
  if ($numrows2 > $colvo_otzyvX and !is_admin($admin)) {
    $ret .= ss("Вы уже оставили отзыв!<br>");
  } else {
    //$ret .= "<b>Вы можете оставить не более ".$colvo_otzyv." отзывов.</b><br>";
    $date1 = intval(date("Y"));
    $date_now = $date1+1;
    // Определение значений даты
    if ($reiting_data=="") $reiting_data = ss("Дата последнего посещения:")."<sup>*</sup>";
    // ПРЕОБРАЗОВАТЕЛЬ начало
    $reitings = explode("\n",trim(str_replace("  "," ",$reiting)));
    // Товар всё еще работает? <select name='avtor'><option value=\"все еще работает\">да, работает</option><option value=\"уже сломался\">нет, сломался</option></select>
    $ret .= "<form method=post action=/-".$DBName." name=addpost class=addpost enctype=\"multipart/form-data\">
    <table><tr valign=top><td align=right></td><td></td></tr>
    <tr valign=top><td align=right><b>".ss("Ваше имя:")."<sup>*</sup></b></td><td>
    <input type=text name='avtor' style='width:400px;'>
    </td></tr>
    <tr valign=top><td align=right><b>".$reiting_data."</b></td><td>
    <select name='date1'>
    <option value=\"1\">".ss("около месяца")."</option>
    <option value=\"2\">".ss("2 месяца")."</option>
    <option value=\"3\">".ss("3 месяца")."</option>
    <option value=\"4\">".ss("4 месяца")."</option>
    <option value=\"5\">".ss("5 месяцев")."</option>
    <option value=\"6\">".ss("полгода")."</option>
    <option value=\"7\">".ss("7 месяцев")."</option>
    <option value=\"8\">".ss("8 месяцев")."</option>
    <option value=\"9\">".ss("9 месяцев")."</option>
    <option value=\"10\">".ss("10 месяцев")."</option>
    <option value=\"11\">".ss("11 месяцев")."</option>
    <option value=\"12\">".ss("1 год")."</option>
    <option value=\"24\">".ss("2 года")."</option>
    <option value=\"36\">".ss("3 года")."</option>
    <option value=\"48\">".ss("4 года")."</option>
    <option value=\"60\">".ss("5 лет")."</option>
    <option value=\"72\">".ss("6 лет")."</option>
    <option value=\"84\">".ss("7 лет")."</option>
    <option value=\"96\">".ss("8 лет")."</option>
    <option value=\"108\">".ss("9 лет")."</option>
    <option value=\"120\">".ss("10 и более лет")."</option>
    </select> 
    </td></tr>
    <tr valign=top><td align=right><b>".ss("Комментарий:")."<sup>*</sup></b></td><td><textarea rows=2 name='info[]' style='width:400px;'></textarea></td></tr>
    <tr valign=top><td align=right><b>".ss("Недостатки:")."<sup>*</sup></b></td><td><textarea rows=3 name='minus' style='width:400px;'></textarea></td></tr>
    <tr valign=top><td align=right><b>".ss("Преимущества:")."<sup>*</sup></b></td><td><textarea rows=3 name='plus' style='width:400px;'></textarea></td></tr>";
    foreach ($reitings as $key => $reitingi) {
      $reit = explode("|",$reitingi);
      $num_reit = count($reit);
      switch(trim($reit[1])) {
        case aa("строка"): 
          $otvet = "<input type=text name='info[]' size=65>"; 
          break;
        case aa("текст"): 
          $otvet = "<textarea rows=3 name='info[]' style='width:400px;'></textarea>"; 
          break;
        case aa("выбор"): 
          $options="";
          for ($i=2; $i < $num_reit; $i++) {
            $options .= "<option value=\"".$reit[$i]."\">".$reit[$i]."</option>";
          }
          $otvet = "<select style='width:99%;' name='info[]'>".$options."</select>"; 
          break; 
        case aa("флажки"): 
          $otvet="";
          $keys=$key+1;
          for ($i=2; $i < $num_reit; $i++) {
          $otvet .= "<label class=\"checkbox-reiting\"><input name='info[".$keys."][]' type=checkbox value='".$reit[$i]."'>".$reit[$i]."</label><br>";
          }
          break; 
      }
      $ret .= "<tr valign=top><td align=right><b>".trim($reit[0])."</b></td><td>".$otvet."</td></tr>";
    }
    $ret .= "<tr valign=top><td align=right><b>".ss("Оценка:")."<sup>*</sup></b></td><td>
    <select name=gol>
    <option value=1>".ss("плохо")."</option>
    <option value=3 selected>".ss("средне")."</option>
    <option value=5>".ss("отлично")."</option>
    </select>
    </td></tr>
    <tr valign=top><td align=right><b>".ss("Введите код")."<sup>*</sup></b></td><td><input type=text name=keystring size='3' maxlength='3'><br><img src='kcaptcha/index.php?".session_name()."=".session_id()."' style='border-radius: 10px;'></td></tr>
    </table>
    <center><input class='comm_submit' type='submit' name='ok' value='".ss("Отправить")."'></center>
    <input name='num' value='".$pid."' type='hidden'>
    <input name='cid' value='".$cid."' type='hidden'>
    <input name='go' value='savereiting' type='hidden'></form>";
    // ПРЕОБРАЗОВАТЕЛЬ КОНЕЦ
  }
  return $ret;
}
###########################################
// Сохранение рейтинга
function savereiting ($avtor, $info, $num, $cid, $gol, $date1, $minus, $plus){
  # Запрет комментариев повторно - перевести в функции!!!
  $link = getenv("HTTP_REFERER");
  global $now, $_SESSION, $_POST, $soderganie, $DBName, $db, $prefix, $module_name, $admin, $captcha_ok, $reiting_data;
  //$date_time = date(" H:i:s");
  //$date = $date1.".".$date2.".".$date3.$date_time;
  $ip = getenv("REMOTE_ADDR"); // IP заменить!
  $num = mysql_real_escape_string(intval($num));
  $cid = mysql_real_escape_string(intval($cid));
  $avtor = trim(str_replace("  "," ",filter($avtor, "nohtml")));
  $minus = trim(str_replace("  "," ",filter($minus, "nohtml")));
  $plus = trim(str_replace("  "," ",filter($plus, "nohtml")));

  if ($avtor != "" AND $info != "") {
    if( (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring']) or $captcha_ok == 1) {
      // Проверка существования комментария данного юзера
      $sql = "SELECT `cid` FROM ".$prefix."_pages_comments WHERE `ip`='".$ip."' and `active`='1' and `num`='".$num."'";
      $resnum = $db->sql_query($sql);
      if ($numrows = $db->sql_numrows($resnum) > 0 and !is_admin($admin)) {
        $location = "/-".$DBName."_page_".$num."#comm";
        // т.е. если уже есть - на страницу переброс
      } else {
        $reiting = "";
        if ($cid != 0) {
          $sql4 = "SELECT `description` FROM ".$prefix."_pages_categories where `cid`='".$cid."' and `tables`='pages'";
          $result4 = $db->sql_query($sql4);
          $row4 = $db->sql_fetchrow($result4);
          $reiting = trim($row4['description']);
        }
        $text = "";

        // ПРЕОБРАЗОВАТЕЛЬ начало
        $reitings = explode("\n",trim(str_replace("  "," ",$reiting)));
        $text .= "<table cellpadding=0 cellspacing=0>";
        foreach ($reitings as $key => $reitingi) {
          if ($key==0) $main_info = $info[$key]."|&|";
          $keys = $key+1;
          $reit = explode("|",$reitingi);
          $massiv = $info[$keys];

          // Если это флажки
          if (trim($reit[1])==aa("флажки")) {
            $keys2 = $key+1;
            $massivs = $info[$keys2];
            $massiv = "";
            foreach ($massivs as $mas) {
              $massiv .= str_replace(" ","?&$",trim($mas))." ";
            }
            $massiv = str_replace(" ",", ",trim($massiv));
            $massiv = str_replace("?&$"," ",trim($massiv));
          }
          if (trim($info[$keys]) != "") $text .= "<tr valign=top><td>".trim($reit[0])."</td><td>".$massiv."</td></tr>";
        }

        switch(intval($gol)) {
          case "1": $otvet = ss("плохо"); break;
          case "3": $otvet = ss("средне"); break;
          case "5": $otvet = ss("отлично"); break;
        }
        switch(intval($date1)) {
          case "1": $time_otvet = ss("около месяца"); break;
          case "2": $time_otvet = ss("2 месяца"); break;
          case "3": $time_otvet = ss("3 месяца"); break;
          case "4": $time_otvet = ss("4 месяца"); break;
          case "5": $time_otvet = ss("5 месяцев"); break;
          case "6": $time_otvet = ss("полгода"); break;
          case "7": $time_otvet = ss("7 месяцев"); break;
          case "8": $time_otvet = ss("8 месяцев"); break;
          case "9": $time_otvet = ss("9 месяцев"); break;
          case "10": $time_otvet = ss("10 месяцев"); break;
          case "11": $time_otvet = ss("11 месяцев"); break;
          case "12": $time_otvet = ss("1 год"); break;
          case "24": $time_otvet = ss("2 года"); break;
          case "36": $time_otvet = ss("3 года"); break;
          case "48": $time_otvet = ss("4 года"); break;
          case "60": $time_otvet = ss("5 лет"); break;
          case "72": $time_otvet = ss("6 лет"); break;
          case "84": $time_otvet = ss("7 лет"); break;
          case "96": $time_otvet = ss("8 лет"); break;
          case "108": $time_otvet = ss("9 лет"); break;
          case "120": $time_otvet = ss("10 и более лет"); break;
        }
        if ($reiting_data=="") $reiting_data = ss("Дата последнего посещения:");
        $text .= "
        <tr valign=top><td colspan=2><b>".$avtor."</b></td></tr>
        <tr valign=top><td><b>".$reiting_data."&nbsp;</b></td><td>".$time_otvet."</td></tr>
        <tr valign=top><td><b>".ss("Оценка:")."</b></td><td>".$otvet."</td></tr>
        <tr valign=top><td><b>".ss("Недостатки:")."</b></td><td>".$minus."</td></tr>
        <tr valign=top><td><b>".ss("Преимущества:")."</b></td><td>".$plus."</td></tr>
        </table>";
        // ПРЕОБРАЗОВАТЕЛЬ конец

        $text = $main_info.$text;
        $db->sql_query("INSERT INTO ".$prefix."_pages_comments (`cid`,`num`,`avtor`,`text`,`ip`,`data`,`golos`) VALUES ('','".$num."','','".mysql_real_escape_string($text)."','".$ip."', '".mysql_real_escape_string($now)."','".mysql_real_escape_string($gol)."')") or die(ss("Ошибка: Не получается сохранить рейтинг"));
        $db->sql_query("UPDATE ".$prefix."_pages SET `comm`=comm+1 WHERE `tables`='pages' and `pid`='".$num."'") or die(ss("Ошибка: Не получается обновить количество рейтингов на странице"));
        $location = "/-".$DBName."_page_".$num."#comm"; 
      }

    } else die("Ошибка: Вы неправильно ввели код проверки. Нажмите в браузере «Назад».");
  } else die("Ошибка: Вы не ввели основную информацию. Нажмите в браузере «Назад».");

  global $siteurl; #######################################################################
  recash(str_replace("http://".$siteurl,"",getenv("HTTP_REFERER"))); // Обновление кеша ##
  recash(str_replace("http://".$siteurl,"",getenv("REQUEST_URI")),0); ####################
  ########################################################################################
  unset($_SESSION['captcha_keystring']);
  Header("Location: $location");
}
###########################################
// Сохранение комментария
function savecomm($avtor, $avtory, $info, $num, $comm_otvet, $maily, $mail, $adres, $tel){
  $link = getenv("HTTP_REFERER");
  $active = 1;
  $commentagain = 0; # 1 = Запрет комментариев повторно - перевести в функции!!!
  //echo $avtory;
  global $admin, $_SESSION, $_POST, $soderganie, $lang, $media_comment, $DBName, $db, $prefix, $module_name, $comments_add, $captcha_ok, $tema_zapret_comm, $now, $ip, $adminmail, $comment_send, $siteurl;

  $num = mysql_real_escape_string(intval($num));
  // переопределение ip
  if (isset($_COOKIE["comment"])) {
    $comment = $_COOKIE["comment"];
    $comment = explode("|",$comment);
    $ip2 = $comment[4];
    if ($ip2 != "") $ip = $ip2;
  }

  if ($comments_add == 2) $active = 0;
  if ($comments_add != 0) {
    $num = intval($num);
    $comm_otvet = intval($comm_otvet);
    $avtory = str_replace("  "," ",filter($avtory));
    $maily = str_replace("  "," ",filter($maily));
    $adres = str_replace("  "," ",filter($adres));
    $tel = str_replace("  "," ",filter($tel));
    if ($media_comment == 0) $info = bbcode(str_replace("document.cookie","",filter($info)));
    if ($media_comment == 1) $info = filter(str_replace("<p><p>","<p>",str_replace("</p></p>","</p>",str_replace("</p><br></p>","</p>",$info))));
    //$info = str_replace("http://".$siteurl, $siteurl, $info);
    //$info = str_replace("http://www.onlinedisk.ru", "www.onlinedisk.ru", $info);

    //$info = str_replace(":)", "<img src=/images/smilies/04.gif>", $info);
    //$info = str_replace(":(", "<img src=/images/smilies/11.gif>", $info);

    $info = str_replace(" ,", ",", $info);
    $info = str_replace("!", "! ", str_replace(" !", "!", $info));
    $info = str_replace("! !", "!", str_replace("! ! !", "!!!", $info));

    if (($tema_zapret_comm == 1 || $tema_zapret_comm == 2) and ( strpos(" ".$avtory.$info.$maily.$adres.$tel, "://") or strpos(" ".$avtory.$info.$maily.$adres.$tel, "www.") ) ) die(ss("Запрещено размещать информацию, содержащую ссылки. Это защита от спама. <br><b>Если ссылку разместить необходимо - пишите ее без http:// и www"));

    if ($lang == "ru") 
      if ($avtor != "" or $mail != "" or (!preg_match("#[а-яА-Я]#i",$info) && !strpos(" ".$info, 'a') && !strpos(" ".$info, 'img') && !strpos(" ".$info, 'iframe') && !strpos(" ".$info, 'object') ) ) die(ss("Запрещено размещать спам."));

    //$open_mails = array(".hu","quantumwise.com",".hu","insproiws.com",".br",".nl",".fr",".cn","usa.net","nasimke.com","ymail.com","mail.com","yahoo.com","hotmail.com","msn.com","yandex.com","gmx.com","i.ua","meta.ua","yandex.ua","ukr.net","bigmir.net");
    //$open_mails2 = array("hu","br","nl","fr","cn","com","net","ua","uk","nl","de","fd");

    // Разрешаем активацию входящих email для .ru и gmail.com
    if (trim($maily) != "") {
      $mail_server = explode("@",$maily);
      $mail_server = $mail_server[1];
      $mail_server2 = explode(".",$mail_server);
      if ($mail_server2[2] != "") $mail_server2[2] = ".".$mail_server2[2];
      $mail_server2 = $mail_server2[1].$mail_server2[2];
      //if (in_array($mail_server,$open_mails)) $active = 0; 
      //if (in_array($mail_server2,$open_mails2)) $active = 0;
      if ($mail_server == "gmail.com") $active = 1;
      if ($lang == "ru") if ($mail_server2 == "ru") $active = 1;
      if ($lang == "az") if ($mail_server2 == "az") $active = 1;
      if ($lang == "en") if ($mail_server2 == "com" || $mail_server2 == "net" || $mail_server2 == "org" || $mail_server2 == "gov") $active = 1;
    } else $active = 1;
    //$pattern = "/www.onlinedisk.ru\/image\/"."(\d+)"."\/"."([а-яА-ЯA-Za-z0-9 _-]+)".".jpg"."/i";
    //$replacement = "<a href=http://www.onlinedisk.ru/get_image.php?id=$1 target=_blank><img src=/includes/phpThumb/phpThumb.php?src=http://www.onlinedisk.ru/get_image.php?id=$1&w=200&h=200&q=45 width=200 /></a> ";
    //$info =  preg_replace($pattern, $replacement, $info);

    $location = "/-".$DBName."_page_".$num."#comm";
    $addcomm = false;

    $ok_mail = true; // Запрет отправки комментариев с некоторых e-mail
    if ( strpos(" ".$maily, ".tv") ) $ok_mail = false;

    if ($avtory != "" AND $info != "") {
      if( ( (isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring']) or $captcha_ok == 1) and ($ok_mail == true) ){
        if ($commentagain==1) {
          // Проверка существования комментария данного юзера
          $sql = "SELECT `cid` FROM ".$prefix."_pages_comments WHERE `ip`='".$ip."' and `num`='".$num."'";
          $resnum = $db->sql_query($sql);
            if ($numrows = $db->sql_numrows($resnum) > 0) {
            // т.е. если уже есть - на страницу переброс
            } else $addcomm = true;
        } else $addcomm = true;
      } else {
        if (is_admin($admin)) { 
          $addcomm = true;
          $maily = $adminmail; // под вопросом!
        } else die(ss("Ошибка: Вы неправильно ввели код проверки. Нажмите в браузере «Назад»."));
      }
    } else die(ss("Ошибка: Вы не ввели основную информацию. Нажмите в браузере «Назад»."));

    // Проверка наличия подобного комментария.
    $sql = "SELECT `cid` FROM ".$prefix."_pages_comments WHERE `text`='".mysql_real_escape_string($info)."' and `num`='".$num."'";
    $resnum = $db->sql_query($sql);
    if ($numrows = $db->sql_numrows($resnum) > 0) $addcomm = false;
      if ($addcomm != false) { 
            // добавим в куки имя, телефон, адрес и мыло
            setcookie("comment", $avtory."|".$maily."|".$adres."|".$tel."|".$ip, time()+60*60*24*360);
            // запишем комментарий в БД
            $db->sql_query("INSERT INTO ".$prefix."_pages_comments ( `cid` , `num` , `avtor` , `mail` , `text` , `ip` , `data`, `drevo`, `adres`, `tel`, `active` ) VALUES ('', '".mysql_real_escape_string($num)."', '".mysql_real_escape_string($avtory)."', '".mysql_real_escape_string($maily)."', '".mysql_real_escape_string($info)."', '".$ip."', '".mysql_real_escape_string($now)."', '".mysql_real_escape_string($comm_otvet)."', '".mysql_real_escape_string($adres)."', '".mysql_real_escape_string($tel)."', '".$active."')");
            // Обновим счетчик комментариев страницы
            $db->sql_query("UPDATE ".$prefix."_pages SET `comm`=comm+1 WHERE `pid`='".$num."'");
      }
  
    // Отправка извещения на mail в случае ответа на коммент

    // получим название раздела
      $sql4 = "SELECT `module` from ".$prefix."_pages where `pid` = '".$num."'";
      $result4 = $db->sql_query($sql4);
      $row4 = $db->sql_fetchrow($result4);
      $mod = $row4['module'];

    if ( $comm_otvet != 0 and $active != 0 ) { // отправка уведомления о комментарии автору предыдущего коммента - ответ
      # берем mail из коммента $comm_otvet
      $sql = "SELECT `avtor`, `mail`, `text` FROM ".$prefix."_pages_comments WHERE `cid`='".mysql_real_escape_string($comm_otvet)."'";
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $avtor2 = $row['avtor'];
      $mail2 = $row['mail'];
      $text2 = $row['text'];
        if (trim($maily)=="") $maily = "e-mail не сообщил(а)";
        if (trim($mail2)!="") mail($mail2, '=?utf-8?b?'.base64_encode($avtor2.ss(", получен ответ на ваш комментарий на сайте ").$siteurl).'?=', "<h3>".ss("Здравствуйте, ").$avtor2."!</h3><b>".ss("Вы писали:")."</b><br><br>".str_replace("\r\n","<br>",$text2)."<br><br><b>".ss("Вам ответил(а) ").$avtory.", ".$maily.":</b><br><br>".str_replace("\r\n","<br>",$info)."<br><br>".ss("Чтобы ответить на комментарий, перейдите на сайт по ")."<a href=http://".$siteurl."/-".$mod."_page_".$num."#comm_".$comm_otvet.">".ss("этой ссылке")."</a>.<br><br><br><br>".ss("Отвечать на это письмо не нужно - оно было создано сайтом автоматически!"), "Content-Type: text/html; charset=utf-8\r\nFrom: ".$maily."\r\n");
    }

    if ( $comment_send == 1 and $active != 0 ) { // отправка уведомления о комментарии администратору
      $sql = "SELECT `cid` FROM ".$prefix."_pages_comments WHERE `avtor`='".mysql_real_escape_string($avtory)."' and `text`='".mysql_real_escape_string($info)."'";
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $cid_num = $row['cid'];
      mail($adminmail, '=?utf-8?b?'.base64_encode(aa("Комментарий на ").$siteurl." [".$now."]").'?=', "<b>".aa("Написал(а) ").$avtory." <".$maily.">:</b><br><br>".str_replace("\r\n","<br>",$info)."<br><br>".aa("Чтобы ответить на комментарий, перейдите ")."<a href='http://".$siteurl."/-".$mod."_page_".$num."#comm_".$cid_num."'>".aa("на сайт")."</a>".aa(" или в его ")."<a href='http://".$siteurl."/red'>".aa("администрирование")."</a>.<br><br><br><br>".aa("Письмо создано сайтом автоматически."), "Content-Type: text/html; charset=utf-8\r\nFrom: ".$adminmail."\r\n");
    }
  } else die(ss("Размещать комментарии в этом разделе запрещено"));

  unset($_SESSION['captcha_keystring']);
  Header("Location: ".$location);
}
###########################################
// Сохранение голосования
function savegolos ($gol, $num){
  global $soderganie, $DBName, $db, $prefix, $module_name, $ip, $commentagain, $admin, $bangolosdays;
  global $siteurl;
  recash(str_replace("http://".$siteurl,"",getenv("HTTP_REFERER"))); // Обновление кеша ##
  recash(str_replace("http://".$siteurl,"",getenv("REQUEST_URI")),0);
  $num = mysql_real_escape_string(intval($num));
  if ($gol>10) $gol=10;
  if ($gol<1) $gol=1;
  $gol = mysql_real_escape_string(intval($gol));
  $dat = mysql_real_escape_string(date("Y.m.d H:i:s"));
  $golos_id = $prefix.'golos'.$num;

  if (isset($golos_id) and isset($GLOBALS[$golos_id])) $tmp = $GLOBALS[$golos_id]; else $tmp = "";

  if (is_admin($admin)) {
    $db->sql_query("INSERT INTO ".$prefix."_pages_golos ( `gid` , `ip` , `golos`, `num`, `data`) VALUES ('', '".$ip."', '".$gol."', '".$num."', '".$dat."')");
    Header("Location: /-".$DBName."_page_".$num."");
  } else {
    $sql = "SELECT `data` FROM ".$prefix."_pages_golos WHERE `ip`='".$ip."' AND `num`='".$num."'";
    $resnum = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $date = $row['data'];
    $date2 = dateresize($date);
    $date = dateresize($dat);
    $raznost = $date - $date2;
    $numrows = $db->sql_numrows($resnum);
    if (($numrows > 0 and $tmp==$golos_id) or $num==0) {
      if ($raznost<$bangolosdays) {
        $raznost=$bangolosdays-$raznost;
        Header("Location: /-".$DBName."_page_".$num."&day=".$raznost);
        // т.е. если уже есть - на страницу переброс
      } else {
        // глюк!
        //$db->sql_query("INSERT INTO ".$prefix."_pages_golos ( `gid` , `ip` , `golos`, `num`, `data`) VALUES ('', '$ip', '$gol', '$num', '$dat')");
        Header("Location: /-".$DBName."_page_".$num); 
      }
    } else {
      $db->sql_query("INSERT INTO ".$prefix."_pages_golos ( `gid` , `ip` , `golos`, `num`, `data`) VALUES ('', '".$ip."', '".$gol."', '".$num."', '".$dat."')");
      Header("Location: /-".$DBName."_page_".$num);
      setcookie ($golos_id, $golos_id,time()+2678400,"/");
    }
  }
}
###########################################
// BBCODE - преобразование --- ТИПОГРАФИКОЙ_ДОПОЛНИТЬ!!
function bbcode($text, $nolink=1) {
  $text = str_replace(ss("жирный]"),"b]", str_replace("QUOTE","quote", str_replace(ss("цитата]"),"quote]", str_replace("IMG","img", str_replace(">",")", str_replace("<","(", trim($text)))))));
  $quote1 = "<table border=0 align=center width=98% cellpadding=3 cellspacing=1><tr valign=top><td><b><span style=\"font-size: 10px\">".ss("Цитата")."</span></b></td></tr><tr valign=top><td bgcolor=#F5F5F5 style=\"border:1px solid #c0c0c0; padding:5px; margin:5px;\">";
  $quote2 = "</td></tr></table>";
  $q1 = substr_count($text, '[quote]');
  $q2 = substr_count($text, '[/quote]');
  if ($q2 != $q1) {
    $quote1 = "<br><b><span style=\"font-size: 10px\">".ss("Цитата").":</span></b><br>";
    $quote2 = "";
  }
  $tr = array(
   "[b]"=>"<b>","[B]"=>"<b>","[/b]"=>"</b>","[/B]"=>"</b>",
   "[i]"=>"<i>","[I]"=>"<i>","[/i]"=>"</i>","[/I]"=>"</i>",
   "[/img]"=>"]<br>","[img]"=>"[img=",
   "[quote]"=>$quote1,"[/quote]"=>$quote2,
   "[hr]"=>"<hr>","[HR]"=>"<hr>",
   "(c)"=>" &#169; ","[li]"=>"<li>"
  );
  // "(r)"=>" &#174; ","(tm)"=>" &#153; ","[*]"=>"<li>","[u]"=>"<u>","[U]"=>"<u>","[/u]"=>"</u>","[/U]"=>"</u>",
  $text = strtr($text,$tr);

    ## замена ссылок и e-mail
    //$text = ereg_replace("^[URL=([^]]+)][^[]+[/URL^]", "\\0 [\\1]", $text);
    
  if ($nolink == 1) $text = preg_replace('/\[(mail)=("?)(.{9,}?)\2\](.+?)\[\/\1\]/', '<noindex><a rel="nofollow" href="mailto:\3">\4</a></noindex>', $text);
  else $text = preg_replace('/\[(mail)=("?)(.{9,}?)\2\](.+?)\[\/\1\]/', '<a href="mailto:\3">\4</a>', $text);

  if ($nolink == 1) $text = preg_replace('/\[(url)=("?)(.{9,}?)\2\](.+?)\[\/\1\]/', '<noindex><a rel="nofollow" target=_blank href="\3">\4</a></noindex>', $text);
  else $text = preg_replace('/\[(url)=("?)(.{9,}?)\2\](.+?)\[\/\1\]/', '<a target=_blank href="\3">\4</a>', $text);

  $text = preg_replace('/\[(img)=("?)(.{9,}?)\2\]/', '<img style="margin:5px;" src="\3" />', $text);

  //$pattern = "/onlinedisk.ru\/image\/"."(\d+)"."\/IMG"."(\d+)".".jpg"."/i";
  //$replacement = "onlinedisk.ru/get_image.php?id=$1";
  //$text =  preg_replace($pattern, $replacement, $text);

  // Замена смайлов
  for ($i=162; $i > 1; $i--) {
    if ($i < 10) $ii = "0".$i; else $ii = $i;
    $text = str_replace("**".$ii, "<img src=/images/smilies/".$ii.".gif>",$text);
  }
  //$text = preg_replace("/\*(\d{3})/","<img src=/images/smilies/\\1.gif>",$text);
  //$text = preg_replace("/\*(\d{2})/","<img src=/images/smilies/\\1.gif>",$text);
  
  //$text = str_replace("viewer.php?file=","images/",$text);
  
  //$text = str_replace("'","\"",$text);
  //$text = str_replace("]",">",$text);
  $text = str_replace("\n","<br>",$text);
  return $text;
}
##############################################################
// Строчка выбора страниц < 1 2 3 >
function topic_links($records,$r_start=0,$URL,$inpage=20,$type=0,$names=0) {
//$records - всего записей 
//$r_start - текущая страница 
//$URL - адрес, заканчивающийся на "=" 
//$inpage - записей на страницу
//$type - вид строки выбора
//$names - вариант именований
    $str="";
    if ($records<=$inpage) return;

        $str.="<div class=pages_links>";
    if ($r_start!=0 and $r_start > 5) {
        $str.="<a class=pages_links href=".$URL.($r_start-1)." title=\"".ss("предыдущая страница")."\"><</a>";
        $str.=" <a href=".$URL."0 title=\"".ss("первая страница")."\"><B>1</B></a>";
        }
    if ($r_start==0) {$sstart=$r_start-0; $send=$r_start+10;}   if ($r_start==1) {$sstart=$r_start-1; $send=$r_start+9;}
    if ($r_start==2) {$sstart=$r_start-2; $send=$r_start+8;}    if ($r_start==3) {$sstart=$r_start-3; $send=$r_start+7;}
    if ($r_start==4) {$sstart=$r_start-4; $send=$r_start+6;}    if ($r_start>=5) {$sstart=$r_start-5; $send=$r_start+5;}
    if ($send*$inpage>$records) $send=$records/$inpage;
    if ($sstart<0) $sstart=0;
    if ($records%$inpage==0) $add=0; else $add=1;
    for ($i=$sstart; $i<$send; $i++) {
        if ($i==$r_start) $str.=" <B>".($i+1)."</B>";
        else $str.=" <a href=".$URL.$i." title=\"".ss("Перейти к странице")." ".($i+1)."\"><B>".($i+1)."</B></a>";
        }
        $send=$records/$inpage;
        $send2 = intval($records/$inpage);
        if ($send2 != $send) $send2++;
    if ($r_start+(1-$add)<intval($records/$inpage) and $r_start < $send2-5) {
        $str.=" <a class=pages_links href=".$URL.($r_start+1)." title=\"".ss("следующая страница")."\">></a>";
        if ( ($r_start==0 and $send2<10) or ($i-1 == intval($records/$inpage)-(1-$add)) ) {} else $str.=" <a href=".$URL.(intval($records/$inpage)-(1-$add))." title=\"".ss("последняя страница")."\"><B>".$send2."</B></a>";
        }
        $str.="</div>";
    return($str);
}
###########################################
// Поисковая строка (выводится сверху или снизу модуля - зависит от настроек)
function search_line($modul, $papka, $slovo="") {
  return "<div class=\"search_line_".$modul."\"><form method=POST action=/--search style='display:inline;'>".ss("Поиск по разделу:")." <input type=text name=slovo size=10 value=\"".$slovo."\"><input type=submit value=\"".ss("Найти")."\"><input type=hidden name=modul value=\"".$modul."\"><input type=hidden name=papka value=\"".$papka."\"><input type=hidden name=go value=search></form></div>";
}
###########################################
// Поисковый ответ
function search($slovo="", $modul, $papka=0) {
  global $soderganie, $DBName, $prefix, $db, $module_name, $ModuleName, $go;
  global $golos, $post, $comments, $datashow, $sort, $lim, $folder, $media, $view, $search, $search_papka; // настройки модуля из БД
  $slov = str_replace(">",")",str_replace("<","(",trim(filter($slovo, "nohtml"))));
  $papka = mysql_real_escape_string(intval($papka));
  if ($search_papka==0) $papka = 0;
  $modul = mysql_real_escape_string(str_replace(">",")",str_replace("<","(",trim(filter($modul, "nohtml")))));
  $slov = str_replace("  "," ",trim($slov));
  $slovo = mysql_real_escape_string(str_replace(" ","%",$slov));
  $soderganie .= search_line($modul, $papka, $slov);
  if ($papka != 0) $and=" and cid=".$papka; else $and="";

  $result = $db->sql_query("SELECT `pid` FROM ".$prefix."_pages where `tables`='pages' and `module`='".$modul."'".$and." and (`main_text` LIKE '%".$slovo."%' or `title` LIKE '%".$slovo."%' or `open_text` LIKE '%".$slovo."%')");
  $numrows = $db->sql_numrows($result);
  if ($numrows==0) {
    $numrows = ss("ничего не найдено...");
    $nu = explode(" ",$slov);
    if ($nu>1) $numrows .= "<br>".ss("Данное сочетание не обнаружено. Попробуйте поискать по одному из этих слов.");
  }
  $soderganie .= "<p><b>".ss("Вы искали:")." ".$slov.".</b> ".ss("Найдено:")." ".$numrows.".";
  if ($numrows!=0) {
  	$pids = array(); // Список похожих
  	$result = $db->sql_query("SELECT `pid`,`title` FROM ".$prefix."_pages where `tables`='pages' and `module`='".$modul."'".$and." and `title` LIKE '%".$slovo."%'");
  	$numrows = $db->sql_numrows($result);
  	if ($numrows==0) $numrows = ss("не найдены");
  	$soderganie .= "<hr noshade=noshade><p><b>".ss("Совпадения в названии:")." ".$numrows."</b><ol>";
  	while ($row = $db->sql_fetchrow($resultX)) {
    	$p_pid = $row['pid'];
    	$p_title = $row['title'];
    	$soderganie .= "<li><a href=/-".$modul."_page_".$p_pid.">".$p_title."</a>";
    	// Заносим в список
    	$pids[] = $p_pid;
  	}
  	/////////////////
  	$result = $db->sql_query("SELECT `pid`,`title` FROM ".$prefix."_pages where `tables`='pages' and `module`='".$modul."'".$and." and (`main_text` LIKE '%".$slovo."%' or `open_text` LIKE '%".$slovo."%')");
  	$numrows = $db->sql_numrows($result);
  	if ($numrows==0) $numrows = ss("не найдены");
  	$soderganie .= "</ol><hr noshade=noshade><p><b>".ss("Совпадения в содержании:")." ".$numrows."</b><ol>";
  	while ($row = $db->sql_fetchrow($resultX)) {
    	$p_pid = $row['pid'];
    	$p_title = $row['title'];
    	if (!in_array($p_pid,$pids)) $soderganie .= "<li><a href=/-".$modul."_page_".$p_pid.">".$p_title."</a>";
  	}
  	$soderganie .= "</ol><p>".ss("Одинаковые с совпадениями в названии не показываются.")."<hr noshade=noshade>";
  }
}
###########################################
function getparent_page($parent_id,$title,$cid=0,$page=0) {
  global $strelka, $soderganie, $soderganie2, $DBName, $prefix, $db, $getparent_cash;
  $cash = $parent_id.$DBName.$cid;
  if (!isset($getparent_cash[$cash])) {
    $pparent_id = "";
    $ptitle = "";
    if ($parent_id != 0) {
      $sql = "select `title`, `parent_id` from ".$prefix."_pages_categories where `module`='".mysql_real_escape_string($DBName)."' and `tables`='pages' and `cid`='".mysql_real_escape_string($parent_id)."'";
      $res = $db->sql_query($sql);
      $row = $db->sql_fetchrow($res);
      $ptitle = strip_tags($row['title']);
      $pparent_id = $row['parent_id'];
    }
    if ($page==2) $tit = "<A href=/-".$DBName."_cat_".$cid." class='cat_podcategorii_link'>".$title."</a>"; 
    else $tit = $title;
    if ($ptitle!="") $title = "<A href=/-".$DBName."_cat_".$parent_id." class='cat_podcategorii_link'>".$ptitle."</a> ".$strelka." ".$tit;
    if (intval($pparent_id)!=0) { $title=getparent_page($pparent_id,$title); }
    $getparent_cash[$cash] = $title;
    return $title;
  } else {
    return $getparent_cash[$cash];
  }
}
###########################################
function getparent_for_addpost($name, $parentid, $title) {
    global $admin, $admintip, $prefix, $db, $getparent_cash;
    $cash = $name.$parentid.$title;
  if (!isset($getparent_cash[$cash])) {
    $pparent_id = "";
    $ptitle = "";
    if ($pparent_id != 0) {
      $sql = "select `title`, `parent_id` from ".$prefix."_pages_categories where `module`='".mysql_real_escape_string($name)."' and `tables`='pages' and `cid`='".mysql_real_escape_string($parentid)."'";
      $res = $db->sql_query($sql);
      $row = $db->sql_fetchrow($res);
      $ptitle = strip_tags($row['title']);
      $pparent_id = $row['parent_id'];
    }
    if ($ptitle!="") $title=$ptitle."/".$title;
    if ($pparent_id != 0) { $title=getparent_for_addpost($name,$pparent_id,$title); }
    $getparent_cash[$cash] = $title;
    return $title;
  } else {
    return $getparent_cash[$cash];
  }
}
##############################################################
// Определяем, что будем показывать...
switch($go) {
  case "showcat":
    global $cid, $pag, $slovo;
    showcat($cid, $pag, $slovo);
    break;
  case "page":
    page($pid, $all);
    break;
  case "savecomm":
    if (!isset($adres)) $adres = "";
    if (!isset($tel)) $tel = "";
    if (!isset($avtor)) $avtor = "";
    if (!isset($avtory)) $avtory = "";
    if (!isset($mail)) $mail = "";
    if (!isset($maily)) $maily = "";
    savecomm ($avtor, $avtory, $info, $num, $comm_otvet, $maily, $mail, $adres, $tel);
    break;
  case "savereiting":
    savereiting ($avtor, $info, $num, $cid, $gol, $date1, $minus, $plus);
    break;
  case "savegolos":
    savegolos ($gol, $num);
    break;
  case "savepost":
    if (!isset($post_title)) $post_title = "";
    if (!isset($add)) $add = "";
    savepost ($avtory, $avtor, $mail, $post_title, $info, $num, $cid, $add);
    break;
  case "search":
    search($slovo, $modul, $papka);
    break;
  case "addbase":
    addbase($base,$name,$spa);
    break;
  case "savebase":
    savebase ($name, $basename, $type, $text);
    break;
  case "showdate":
    showdate ($showdate);
    break;
  default:
    showcat("0");
    break; 
}
?>