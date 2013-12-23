<?php
  // Поиск по дате

  // проверка даты
  $showdate = explode("-",$showdate);
  $showdate = intval($showdate[0])."-".(intval($showdate[1]) < 10 ? '0'.intval($showdate[1]) : $showdate[1])."-".(intval($showdate[2]) < 10 ? '0'.intval($showdate[2]) : $showdate[2]);
    
  global $strelka, $soderganie, $soderganie2, $db, $prefix, $admin, $name, $pagetitle, $show_comments;
  global $post, $comments, $datashow, $folder, $media, $view, $col, $search, $search_papka, $tema, $tema_name, $tema_title, $tema_opis, $menushow, $where, $order, $peopleshow, $calendar, $div_or_table; // настройки из БД
  
  $ANDDATA = "";

  $p_pid_last = 1; // последняя категория (для форума)

  $soderganie = ss("Найдено на")." ".date2normal_view($showdate);

  // Список всех папок (массив)
  $c_name = array();
  $sql = "SELECT `cid`, `title` FROM ".$prefix."_pages_categories where `tables`='pages'";
  $result = $db->sql_query($sql) or die(ss("Не удалось собрать список всех папок"));
  while ($row = $db->sql_fetchrow($result)) {
    $x_cid = $row['cid'];
    $c_name[$x_cid] = $row['title'];
  }

  // списки
  if (trim($calendar) != "") {
    $sql2 = "SELECT pages FROM ".$prefix."_spiski where `name`='".mysql_real_escape_string($showdate)."' AND `type`='".mysql_real_escape_string($calendar)."'";
    $result2 = $db->sql_query($sql2) or die(ss("Не удалось собрать списки"));
    $row = $db->sql_fetchrow($result2);
    $datavybor = $row['pages'];
    $datavybor = trim(str_replace("   "," ",str_replace("  "," ",$datavybor)));
    $datavybor = " and (`pid`='".str_replace(" ","' or `pid`='",$datavybor)."')";
  } else $datavybor = " and `date` like '".mysql_real_escape_string($showdate)." %'";

  $sql2 = "SELECT `pid` FROM ".$prefix."_pages where `tables`='pages' and (`copy`='0' or `copy`=pid) and `active`='1'".$datavybor;

  $result2 = $db->sql_query($sql2) or die(ss("Не удалось определить кол-во страниц"));
  $nu = $db->sql_numrows($result2);

  $soderganie .= ", всего: ".$nu;
  if ($nu > 0) {
     # Если не выбран ни один каталог
    $soderganie .= "<table cellspacing=0 cellpadding=3 width=100%>";
    $sql2 = "SELECT * FROM ".$prefix."_pages where `tables`='pages' and (`copy`='0' or `copy`=pid) and `active`='1'".$datavybor." order by `date`";
    $result2 = $db->sql_query($sql2);
    $soderganie .= "";

    while ($row2 = $db->sql_fetchrow($result2)) {
      $p_pid = $row2['pid'];
      $pсid = $row2['cid'];
      $module = $row2['module'];
      if ($pсid != 0) $p_name = "<div class='cat_page_cattitle'><a href='-".$module."_cat_".$pсid."' class='cat_page_cattitle'>".$c_name[$pсid]."</a></div>"; 
      else $p_name = "";
      $title = $row2['title'];
      $text = $row2['open_text'];
      ///////////////////////////
      $text = str_replace(aa("[заголовок]"),"",$text); // Убираем Заголовок, использованный в блоке!
      ///////////////////////////
      $p_comm = $row2['comm']; 
      $p_counter = $row2['counter'];
      $dat = explode(" ",$row2['date']); // заменить
      $dat = explode("-",$dat[0]);
      $p_date = intval($dat[2])." ".findMonthName($dat[1])." ".$dat[0];
      $p_date_1 = $dat[2]." ".$dat[1]." ".$dat[0];
      $date_now = date("d m Y");
      $date_now2 = date("d m Y",time()-86400);
      $date_now3 = date("d m Y",time()-172800);
      if ($date_now == $p_date_1) $p_date = ss("Сегодня");
      if ($date_now2 == $p_date_1) $p_date = ss("Вчера");
      if ($date_now3 == $p_date_1) $p_date = ss("Позавчера");

      if ($row2['copy']==0) {
        $a_open = "<a href='-".$module."_page_".$p_pid."'>";
        $a_open_comm = "<a href='-".$module."_page_".$p_pid."_comm#comm'>";
        $a_close = "</a>";
      } else {
        $a_open = "<noindex><a rel='nofollow' href='-".$module."_page_".$p_pid."'>";
        $a_open_comm = "<noindex><a rel='nofollow' href='-".$module."_page_".$p_pid."_comm#comm'>";
        $a_close = "</a></noindex>";
      }

        $soderganie .= "<tr valign='top'><td>
        <div class='page_link_title'><span class='page_title'>".$a_open.$title.$a_close."</span>";
        if (is_admin($admin)) $soderganie .= "&nbsp; (<a href='sys.php?op=base_pages_edit_page&name=".$module."&pid=".$p_pid."' title=\"".aa("Редактировать страницу")."\">".aa("редактировать")."</a>)";
        $soderganie .= "</div>";
        if (trim($text)!="") $soderganie .= "<div class='cat_page_text'>".$text."</div>";
        if ($pсid>0 and $c_name[$pсid]!="") $soderganie .= "<div class='cat_page_folder ico_folder back_icon' title='".ss("Папка")."'><A href='-".$module."_cat_".$pсid."' class='page_razdel_link'>".$c_name[$pсid]."</a></div>";

        $soderganie .= "</td></tr>";

    }
    $soderganie .= "</table>";
  }
  if (is_admin($admin)) $soderganie .= "<b class='red'>".aa("Редактирование страниц доступно только администратору.")."</b>";
  // Получаем дизайн для поиска
  global $search_design;
  // Определение дизайна и использованных стилей в дизайне
  list($design_for_search, $stil) = design_and_style($search_design);
  if ($design_for_search == "0") die(ss("Ошибка: «Адрес раздела»")." (".$name.") ".ss("введен неправильно. Перейдите на")." <a href=/>".ss("Главную страницу")."</a>.");
  $block = str_replace(aa("[содержание]"), $soderganie, $design_for_search);
  return array($block, $stil);

?>