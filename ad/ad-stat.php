<?php
  if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
  $aid = trim($aid);
  global $prefix, $db, $red;
  $sql = "SELECT realadmin FROM ".$prefix."_authors where aid='".$aid."'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $realadmin = $row['realadmin'];
if ($realadmin==1) {
  include("ad/ad-header.php");

  function stat_main() {
    global $prefix, $db, $statlink;
    $stat_razdel = $stat_page = "";
    $sql = "SELECT `name`, `title`, `counter` FROM ".$prefix."_mainpage where `tables`='pages' and `type`='2' order by `counter` desc";
    $result = $db->sql_query($sql) or die('Ошибка при попытке прочитать посещаемость разделов');
    while ($row = $db->sql_fetchrow($result)) {
      $name2 = $row['name'];
      if (strpos($name2, "\n")) { // заменяем имя запароленного раздела
        $name2 = explode("\n", str_replace("\r", "", $name2));
        $name2 = trim($name2[0]);
      }
      $stat_razdel .= "<tr valign=top><td class='polosa gray'><a target='_blank' href='/-".$name2."'>".strip_tags($row['title'], '<b><i>')."</a></td><td align=center class='polosa gray'>".$row['counter']."</td></tr>";
    }
    $stat_razdel = "<h2>Посещаемость разделов:</h2><table class='w100 table_light'>".$stat_razdel."</table>
    <p>Посещения страниц сайта администратором не учитываются. 
    <p><a class='button small' href='sys.php?op=stat_main_delete'><span class='icon red small' data-icon='F'></span> Очистить статистику разделов и страниц</a>";
  
    $sql = "SELECT `pid`,`module`,`title`,`counter` FROM ".$prefix."_pages where `active`='1' and `tables`='pages' order by `counter` desc limit 0,50";
    $result = $db->sql_query($sql) or die('Ошибка при попытке прочитать посещаемость страниц');
    while ($row = $db->sql_fetchrow($result)) {
    $stat_page .= "<tr valign=top><td class='polosa gray'><a target='_blank' href='/-".$row['module']."_page_".$row['pid']."'>".strip_tags($row['title'], '<b><i>')."</a></td><td align=center class='polosa gray'>".$row['counter']."</td></tr>";
    }
    $stat_page = "<a href='sys.php?op=stat_page' class='right3 button'>См. популярные &rarr;</a>
    <h2>и страниц:</h2>
    <table class='w100 table_light'>".$stat_page."</table>";
    $user_name = array();
    $user_mail = array();
    $user_tel = array();
    $sql = "SELECT `avtor`, `mail`, `ip`, `tel` FROM ".$prefix."_pages_comments where `mail`!='' or `tel`!='' order by `ip`";
    $result = $db->sql_query($sql) or die('Ошибка при попытке прочитать комментарии');
    while ($row = $db->sql_fetchrow($result)) {
      $user_ip = $row['ip'];
      $user_name[$user_ip] = $row['avtor'];
      $user_mail[$user_ip] = $row['mail'];
      $user_tel[$user_ip] = $row['tel'];
    }
    $sql = "SELECT ip, slovo, data, pages FROM ".$prefix."_search order by data desc limit 0,20";
    $result = $db->sql_query($sql) or die('Ошибка при попытке прочитать внутренние поисковые запросы сайта');
    $numrows = $db->sql_numrows($result);
    $stat_search = "<h2>Статистика поиска на сайте:</h2>";
    if ($numrows > 0) {
      while ($row = $db->sql_fetchrow($result)) {
        $user_ip = $row['ip'];
        $user_info = "";
        if (isset($user_name[$user_ip])) if ($user_name[$user_ip] != '') $user_info .= "Имя: ".$user_name[$user_ip];
        if (isset($user_mail[$user_ip])) if ($user_mail[$user_ip] != '') $user_info .= "<br>Email: ".$user_mail[$user_ip];
        if (isset($user_tel[$user_ip])) if ($user_tel[$user_ip] != '') $user_info .= "<br>Тел. ".$user_tel[$user_ip];
        $stat_search .= "<tr valign=top><td class='polosa gray'>".date2normal_view($row['data'], 2, 1)."</td><td class='polosa'>".$row['slovo']."<br><span class='gray'>".$user_info."</span></td><td class='polosa gray'>".$row['pages']."</td></tr>";
      }
      $stat_search = "<a href='sys.php?op=stat_search' class='right3 button'>См. всё &rarr;</a>
      <table class='w100 table_light'>".$stat_search."</table>
      <p>В средней колонке могут выводиться имя, email и телефон человека, искавшего этот запрос.<br>
      <p>В последней колонке — количество найденных страниц.
      <p><a class='button small' href='sys.php?op=stat_delete'><span class='icon red small' data-icon='F'></span> Очистить статистику поиска</a>";
    } else $stat_search .= "<p>На сайте еще никто не пользовался поиском.
    <p>Поисковые запросы администратора не учитываются статистикой.";

    if ($statlink != "") echo "<h2><span class=\"icon black medium\" data-icon=\"j\"></span> <a href=".$statlink." target=_blank>Сторонняя статистика</a></h2>"; 
    else echo "<div class='notice warning mw800'>Сторонняя статистика не настроена. См. <a href='sys.php?op=options'>Настройки</a></div>";
    echo "<h2><span class=\"icon gray medium\" data-icon=\"j\"></span> Встроенная статистика:</h2>
    <table cellpadding=2 class='w100 mw800 light_fon radius'><tr valign=top><td width=25%>".$stat_razdel."</td><td width=30%>".$stat_page."</td><td>".$stat_search."</td></tr></table></div>
    <br></div></body>
    </html>";
    echo "<br></div></body></html>";
  }

  function stat_search() {
    global $prefix, $db, $statlink;
    echo "<h1>Статистика поисковых запросов (полная версия)</h1>
    <a href='sys.php?op=stat_main'>Вернуться к общей статистике</a><br><br>";
    $user_name = array();
    $user_mail = array();
    $user_tel = array();
    $sql = "SELECT `avtor`, `mail`, `ip`, `tel` FROM ".$prefix."_pages_comments where `mail`!='' or `tel`!='' order by ip";
    $result = $db->sql_query($sql); // or die('Ошибка при попытке прочитать названия разделов');
    while ($row = $db->sql_fetchrow($result)) {
      $user_ip = $row['ip'];
      $user_name[$user_ip] = $row['avtor'];
      $user_mail[$user_ip] = $row['mail'];
      $user_tel[$user_ip] = $row['tel'];
    }
    $sql = "SELECT id, ip, slovo, data, pages FROM ".$prefix."_search order by data desc limit 0,50000";
    $result = $db->sql_query($sql); // or die('Ошибка при попытке прочитать названия разделов');
    $stat_search1 = $stat_search2 = $stat_search3 = $stat_search4 = array();
    while ($row = $db->sql_fetchrow($result)) {
      $id = $row['id'];
      $del = " <div id='s_".$id."' style='display:inline;'><a onclick=delslovo('".$id."') class='punkt' title='Удалить слово'><span class=\"icon red small\" data-icon=\"F\"></span></a></div>";
      $user_ip = $row['ip'];
      $user_info = "";
      if (isset($user_name[$user_ip])) if ($user_name[$user_ip] != '') $user_info .= "Имя: ".$user_name[$user_ip]." [".date2normal_view($row['data'], 2)."]";
      if (isset($user_mail[$user_ip])) if ($user_mail[$user_ip] != '') $user_info .= " Email: ".$user_mail[$user_ip];
      if (isset($user_tel[$user_ip])) if ($user_tel[$user_ip] != '') $user_info .= " Тел. ".$user_tel[$user_ip];
      //$str = "<tr valign=top><td class='polosa gray'>".date2normal_view($row['data'], 2)."</td><td class='polosa'>".$row['slovo'].$del."<br><span class='gray'>".$user_info."</span></td><td class='polosa gray'>".$row['pages']."</td></tr>";
      if (trim($row['slovo']) != "") {
        if ($row['pages'] == "0 | 0") $stat_search1[] = $row['slovo'].$del." <span class='gray'>".$user_info."</span>";
        elseif ($row['pages'] == "0 | 1") $stat_search2[] = $row['slovo'].$del." <span class='gray'>".$user_info."</span>";
        elseif (strpos(" ".$row['pages']," 0 | ") == "0 | 0") $stat_search3[] = $row['slovo']." — ".$row['pages']." ".$del." <span class='gray'>".$user_info."</span>";
        else $stat_search4[] = $row['slovo']." — ".$row['pages']." ".$del." <span class='gray'>".$user_info."</span>";
      }
    }
    natcasesort($stat_search1);
    natcasesort($stat_search2);
    natcasesort($stat_search3);
    natcasesort($stat_search4);
    $stat_search1 = "".implode("<br>",$stat_search1)."";
    $stat_search2 = "".implode("<br>",$stat_search2)."";
    $stat_search3 = "".implode("<br>",$stat_search3)."";
    $stat_search4 = "".implode("<br>",$stat_search4)."";
    if ($stat_search1 != "") echo "<h2>Ничего не найдено (можно создать искомые страницы)</h2>".$stat_search1."<hr>";
    if ($stat_search2 != "") echo "<h2>Найдено в содержании всего одной страницы (слишком мало информации по искомому слову)</h2>".$stat_search2."<hr>";
    if ($stat_search3 != "") echo "<h2>Найдено только в содержании (можно изменить названия страниц или создать новые страницы)</h2>".$stat_search3."<hr>";
    if ($stat_search4 != "") echo "<h2>Остальное (удовлетворяющее, найдено в названии и содержании)</h2>".$stat_search4."";
    echo "<br></div></body></html>";
  }

  function stat_page() {
    global $now, $prefix, $db, $statlink;
    $stat_page = "";
    $proc = 0;
    echo "<h1>Статистика посещений страниц</h1>
    <a href='sys.php?op=stat_main'>Вернуться к общей статистике</a><br><br>";
    $sql = "SELECT `pid`, `module`, `title`, `date`, counter FROM ".$prefix."_pages where active='1' and `tables`='pages' and `counter` > 15 order by `counter` desc limit 0,1000000";
    $result = $db->sql_query($sql); // or die('Ошибка при попытке прочитать названия разделов');
    $numrows = $db->sql_numrows($result);
    $nu = 0;
    while ($row = $db->sql_fetchrow($result)) {
      if ($proc == 0) {
        $proc = 100; $count = $row['counter'];
      } else {
        $proc = intval($row['counter'] * 100 / $count);
        if ($proc == 0) $proc = 1;
      }
      $time = dateresize($now) - dateresize($row['date']);
      if ($time == 0) $proc2 = 0;
      else $proc2 = intval( $row['counter'] / $time );
      // счетчик делим на сколько дней прошло с публикации
      if ($proc2 > 9) $proc2X = "<td class='red ffa4ac'>".$proc2."";
      elseif ($proc2 > 5) $proc2X = "<td class='green b4f3b4'>".$proc2."";
      else $proc2X = "<td class='f3f3a3'>".$proc2."";
      if ($proc2 > 2) {
        $nu++;
        $stat_page .= "<tr valign=top class='tr_hover'><td class='polosa gray'><a target=_blank href=/-".$row['module']."_page_".$row['pid'].">".$row['title']."</a><sup>".$nu."</sup></td><td width=100 class='polosa'><div class='h15 gradient' style='width:".$proc."%;'></div></td><td class='polosa'>".$row['counter']."</td>".$proc2X."</td><td class='polosa'>".$time."</td></tr>";
      }
    }
    if ($numrows > 0) $pro = intval($nu * 100 / $numrows);
    else $pro = 0;
    echo "<table class='w100'><tr valign=bottom><td><nobr><strong>Страницы</strong> (всего: ".$numrows.", показано ниже: ".$nu.", эффективность: ".$pro."%)</nobr><br>
    Новые (до 15 посещений) и малопосещаемые (меньше 3 в день) не отображаются.</td><td width=80><strong><nobr>Процент</nobr></strong></td><td><nobr>Посещения</nobr></td><td><strong><nobr>Среднее</nobr></strong></td><td><nobr>Время, дней</nobr></td></tr>".$stat_page."</table>";
    echo "<br></div></body></html>";
  }

  function stat_main_delete() {
    global $prefix, $db;
    $db->sql_query("UPDATE ".$prefix."_mainpage SET `counter`='".$namo."';");
    $db->sql_query("UPDATE ".$prefix."_pages SET `counter`='".$namo."';");
    Header("Location: sys.php?op=stat_main");
  }

  function stat_delete() {
    global $prefix, $db;
    $db->sql_query("DELETE FROM ".$prefix."_search");
    Header("Location: sys.php?op=stat_main");
  }

  
###################################################################################
  switch ($op) {
      case "stat_main":
      stat_main();
      break;

      case "stat_page":
      stat_page();
      break;

      case "stat_search":
      stat_search();
      break;

      case "stat_delete":
      stat_delete();
      break;

      case "stat_main_delete":
      stat_main_delete();
      break;
  }
}
?>
