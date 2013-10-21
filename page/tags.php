<?php
  // Поиск по всем разделам
  ###################################################### ПОИСК ПОИСК 
  global $soderganie, $tip, $DBName, $prefix, $db, $module_name, $ModuleName, $design, $now, $ip, $papka;

  // Поиск по ключ. словам
  ###################################################### ТЕГИ
  global $soderganie, $tip, $DBName, $prefix, $db, $design;
  $slovo = explode("_", urldecode(getenv("REQUEST_URI")));
  $slov = trim(strip_tags(str_replace("-", "%", $slovo[1])));
  $slov = str_replace("  ", " ", $slov);
  $slovo = str_replace(" ", "%", $slov);
  
  // Определение названий всех разделов
  $sql3 = "select `name`, `title` from `".$prefix."_mainpage` where `tables`='pages' and `type`='2'";
  $result3 = $db->sql_query($sql3);
  while ($row3 = $db->sql_fetchrow($result3)) {
    $m_name = $row3['name'];
    if (strpos($m_name, "\n")) { // заменяем имя запароленного раздела
      $m_name = explode("\n", str_replace("\r", "", $m_name));
      $m_name = trim($m_name[0]);
    }
    $m_title[$m_name] = $row3['title'];
  }
  
  $res1 = $db->sql_query("SELECT `pid` FROM ".$prefix."_pages where `tables`='pages' and `active`='1' and (`copy`='0' or `copy`=`pid`) and (`search` LIKE '% ".$slovo." %') order by `date` desc");
  $numrows = $db->sql_numrows($res1);
  if ($numrows==0) {
    $numrows = ss("ничего не найдено...");
    $nu = explode(" ",$slov);
    if ($nu>1) $numrows .= "<br>".ss("Данный тег не обнаружен.");
  }
  $soderganie .= "<center><div class=main_search_line align=left><table border=0 cellspacing=1 cellpadding=0><tr><td><b>".ss("Вы выбрали тег")." ".ss("«").$slov.ss("»")."</b> ".ss("Найдено:")." ".$numrows."</td></tr></table></div><br><div class=main_search align=left><ol>";
  if ($numrows!=0) {
    $pids = array(); // Список похожих
    $res2 = $db->sql_query("SELECT `pid`,`module`,`cid`,`title` FROM ".$prefix."_pages where `tables`='pages' and `active`='1' and (`copy`='0' or `copy`=`pid`) and `search` LIKE '% ".$slovo." %'");
    $admintip = "base_pages";
    while ($row = $db->sql_fetchrow($res2)) {
      $p_pid = $row['pid'];
      $p_title = $row['title'];
      $p_module = $row['module'];
      $p_cid = $row['cid'];
      $soderganie .= "<li><a href=-".$p_module.">".$m_title[$p_module]."</a> ".$strelka." <a href=-".$p_module."_page_".$p_pid.">".$p_title."</a>";
    
      if (is_admin($admin)) $soderganie .= "&nbsp; <a href='sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid."' title=\"".aa("Изменить страницу в Редакторе")."\"><img src='images/sys/edit_1.png' title=\"".aa("Изменить страницу в Редакторе")."\"></a>&nbsp; <a href='sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid."&red=1' title=\"".aa("Изменить страницу (быстрый HTML режим)")."\"><img src='images/sys/edit_0.png' title=\"".aa("Изменить страницу (быстрый HTML режим)")."\"></a>";
      // Заносим в список
      $pids[] = $p_pid;
    }
    $soderganie .= "</ol><hr>";
  }
  if (is_admin($admin)) $soderganie .= "<h2>".aa("Редактирование страниц доступно только вам — администратору.")."</h2>";
  $soderganie .= "</div></center>";

  // Получаем дизайн для поиска
  global $tag_design;
  // Определение дизайна и использованных стилей в дизайне
  list($design_for_tag, $stil) = design_and_style($tag_design);
  if ($design_for_tag == "0") die(ss("Ошибка: «Адрес раздела»")." (".$name.") ".ss("введен неправильно. Перейдите на")." <a href=/>".ss("Главную страницу")."</a>.");

  $block = str_replace(aa("[содержание]"), $soderganie, $design_for_tag);
  return array($block, $stil);
?>