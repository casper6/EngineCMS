<?php
  // Поиск по всем разделам
  ###################################################### ПОИСК ПОИСК 
  global $soderganie, $tip, $DBName, $prefix, $db, $module_name, $ModuleName, $slovo, $design, $now, $ip, $papka;

  $slov = str_replace("  "," ",str_replace(";"," ",str_replace("—"," ",str_replace("`"," ",str_replace("№ ","№",str_replace("№"," №",str_replace(",",", ",str_replace("ё","е",trim(strip_tags($slovo))))))))));

  if (strpos($slov,"@")) {
    echo "E-mail адреса не стоит искать на этом сайте, лучше использовать их для написания писем в почтовых программах или на почтовых сайтах, на которых вы зарегистрированы. Если вам нужны Контакты — посмотрите, возможно они есть в меню сайта.";
    exit;
  }
  if (strpos(" ".$slov,"www.") or strpos($slov,".ru") or strpos($slov,".com") or strpos($slov,".http")) {
    echo "Адреса сайтов нужно писать не в поиске по сайту, а в адресной строке вашего браузера (той программы, через которую вы смотрите этот сайт)!";
    exit;
  }

  if ($slov == "мыло" and is_admin($admin)) { // переместить!
    $sql5 = "SELECT num, avtor, mail from ".$prefix."_pages_comments where `mail`!='' order by num";
    $result5 = $db->sql_query($sql5);
    $numrows = $db->sql_numrows($result5);
    $nu = 0; // счетчик email для разбиения по 25 штук
    $nu2 = 0; // счетчик подписанных на рассылку
    $echo = ""; 
    $mails2 = array();
    while ($row5 = $db->sql_fetchrow($result5)) {
      $avtor = $row5['avtor'];
      
      $mails = trim(strip_tags($row5['mail']));
      if ( !in_array($mails,$mails2) and strpos($mails, "@") and strpos($mails, ".") ) {
        $nu++;
        $mails2[] = $mails;
        if ($row5['num'] == 0) { $nu2++; $echo .= "\"<b>".$avtor."</b>\" &lt;".$mails."&gt;, "; }
        else $echo .= "\"".$avtor."\" &lt;".$mails."&gt;, ";
        if ($nu == 25) { $echo .= "<hr>"; $nu = 0; }
      }
    }
    echo "<h1>Адреса Email из комментариев, всего ".count($mails2).", разбито по 25 штук.</h1>";
    if ($nu2 > 0) echo "<h2>Подписавшиеся на рассылку выделены жирным, всего: ".$nu2.".</h2>";
    echo "<p>Можно вставлять для отправки сразу после исправления имен, если они набраны неправильно.</p>
    <p><b>Внимание!</b> Рассылку лучше всего делать со специально зарегистрированного для этого email адреса. Не желательно писать в письме рассылки адрес сайта со ссылкой только на одну страницу. Ни в коем случае не делать рекламные рассылки! Это может быть расценено как спам, а сайт могут просто закрыть!</p><p><b>Разрешается делать:</b>
    <li>Обзорные рассылки — много ссылок на разные материалы
    <li>Извещение о начале какого-то конкурса или массового события
    <li>Поздравительные праздничные рассылки
    <hr>".$echo;
    exit;
  }
  
  $papka = intval($papka);
  if ($papka == 0) $papka = ""; // search_papka
  else $papka = " and cid = '".$papka."'";
  $slov = str_replace("  "," ",trim($slov));
  
  // Определение названий всех разделов
  $sql3 = "select `name`, `title` from `".$prefix."_mainpage` where `tables`='pages' and `type`='2'";
  $result3 = $db->sql_query($sql3);
  while ($row3 = $db->sql_fetchrow($result3)) {
    $m_name = $row3['name'];
    $m_title[$m_name] = $row3['title'];
  }
  
  $soderganie .= "<div class='main_search_line'><form method=POST action=\"--search\" class=main_search_form><input type='search' placeholder='Поиск по сайту' name=slovo class='main_search_input' value=\"".$slov."\"><input type='submit' name='ok' value='Найти' class='main_search_button'></form></div>";
  
  // Заголовок
  $slovo = zamena_predlog($slov);
  $slovo = explode(" ",$slovo);
  for ( $i=0; $i < count($slovo); $i++ ) { 
  $sl = strlen($slovo[$i]);
    if ($sl >= 6) $slovo[$i] = obrez($slovo[$i]);
  }
  $slovo = trim(implode("%",$slovo));

  $numrows = $db->sql_numrows( $db->sql_query("SELECT `pid` FROM ".$prefix."_pages where `tables`='pages'".$papka." and active='1' and (copy='0' or copy=pid) and (main_text LIKE '%".$slovo."%' or title LIKE '%".$slovo."%' or open_text LIKE '%".$slovo."%' or description LIKE '%".$slovo."%') order by date desc") );
  $nu = "";
  if ($numrows==0 or strlen($slovo) == 0) {
    $numrows = "ничего не найдено...";
    $nu = explode(" ",$slov);
    if ($nu>1) {
      $nu = "<br><br><h3>Данное сочетание не обнаружено. Попробуйте поискать по другим словам.<br>В слове должно быть как минимум три буквы.</h3>"; 
      $numrows1 = 0;
      $numrows2 = 0;
    }
  }
  
  $soderganie .= "<h2>Найдено: ".$numrows."</h2><p>".$nu."<div class=main_search>";
    if ($numrows!=0) {
      // Список всех папок (массив)
      $c_name = array();
      $sql = "SELECT cid,title FROM ".$prefix."_pages_categories where `tables`='pages'";
      $result = $db->sql_query($sql) or die('Не удалось собрать список всех папок');
      while ($row = $db->sql_fetchrow($result)) {
        $x_cid = $row['cid'];
        $c_name[$x_cid] = strip_tags($row['title']);
      }

      $pids = array(); // Список похожих
      $res2 = $db->sql_query("SELECT `pid`,`module`,`cid`,`title` FROM ".$prefix."_pages where `tables`='pages'".$papka." and active='1' and (copy='0' or copy=pid) and title LIKE '%".$slovo."%'");
      $numrows1 = $db->sql_numrows($res2);
      if ($numrows1 == 0) $nu = "не найдены"; else $nu = $numrows1;
      $soderganie .= "<p>Совпадения в названии страниц: ".$nu."<ol>";
      $admintip = "base_pages";
      while ($row = $db->sql_fetchrow($res2)) {
        $p_pid = $row['pid'];
        $p_title = $row['title'];
        $p_module = $row['module'];
        $p_cid = $row['cid'];
        if ($p_cid != 0) $cat = "<a class='search_cat_link' href='/-".$p_module."_cat_".$p_cid."'>".$c_name[$p_cid]."</a> ".$strelka." "; else $cat = "";
        $soderganie .= "<li><a href='/-".$p_module."'>".$m_title[$p_module]."</a> ".$strelka." ".$cat."<a class='search_page_link' href=-".$p_module."_page_".$p_pid.">".$p_title.".</a>";
  
        if (is_admin($admin)) $soderganie .= "&nbsp; <a href=sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid." title=\"Изменить страницу в Редакторе\"><img src=images/sys/edit_1.png title=\"Изменить страницу в Редакторе\"></a><a href=sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid."&red=1 title=\"Изменить страницу (быстрый HTML режим)\"><img src=images/sys/edit_0.png title=\"Изменить страницу (быстрый HTML режим)\"></a>";
        // Заносим в список
        $pids[] = $p_pid;
      }

      $res3 = $db->sql_query("SELECT `pid`,`module`,`cid`,`title` FROM ".$prefix."_pages where `tables`='pages'".$papka." and active='1' and (copy='0' or copy=pid) and (main_text LIKE '%".$slovo."%' or open_text LIKE '%".$slovo."%')");
      $numrows2 = $db->sql_numrows($res3);
      if ($numrows2 == 0) $nu = "не найдены"; else $nu = $numrows2;
      $soderganie .= "</ol><p>Совпадения в содержании (или описании) страниц: $nu<ol>";
      while ($row = $db->sql_fetchrow($res3)) {
        $p_pid = $row['pid'];
        $p_title = $row['title'];
        $p_module = $row['module'];
        $p_cid = $row['cid'];
        if ($p_cid != 0) $cat = "<a class='search_cat_link' href=-".$p_module."_cat_".$p_cid.">".$c_name[$p_cid]."</a> $strelka "; else $cat = "";
          if (!in_array($p_pid,$pids)) {
            $soderganie .= "<li><a href=-".$p_module.">".$m_title[$p_module]."</a> ".$strelka." ".$cat."<a class='search_page_link' href=-".$p_module."_page_".$p_pid.">$p_title.</a>";
            if (is_admin($admin)) $soderganie .= "&nbsp; <a href=sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid." title=\"Изменить страницу в Редакторе\"><img src=images/sys/edit_1.png title=\"Изменить страницу в Редакторе\"></a><a href=sys.php?op=".$admintip."_edit_page&name=".$p_module."&pid=".$p_pid."&red=1 title=\"Изменить страницу (быстрый HTML режим)\"><img src=images/sys/edit_0.png title=\"Изменить страницу (быстрый HTML режим)\"></a>";
          }
      }
      $soderganie .= "</ol>";
    }
    if (is_admin($admin)) $soderganie .= "<h3>Редактирование страниц доступно только Администратору.</h3>";
  $soderganie .= "</div>";

  // Получаем дизайн для поиска
  global $search_design;
  // Определение дизайна и использованных стилей в дизайне
  list($design_for_search, $stil) = design_and_style($search_design);
  if ($design_for_search == "0") die("Ошибка: «Адрес раздела» (".$name.") введен неправильно. Перейдите на <a href=/>Главную страницу</a>.");

  $block = str_replace("[содержание]",$soderganie,$design_for_search);

  // Занесение слова в БД
  if ($db->sql_numrows($db->sql_query("SELECT `id` FROM ".$prefix."_search where `slovo`='$slov' and ip='$ip'")) == 0 and trim($slov) != '' and !is_admin($admin)) $db->sql_query("INSERT INTO `".$prefix."_search` (`id`,`ip`,`slovo`,`data`,`pages`) VALUES (NULL, '$ip', '$slov', '$now', '$numrows1 | $numrows2');");

  return array($block, $stil);
?>