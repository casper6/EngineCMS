<?php
  ob_start();  // Начался вывод страницы с кешированием
  ob_implicit_flush(0); 
  session_start(); // Для капчи (проверочный код-картинка от спама) // проверить вызов
##########################################################################################
  require_once ('page/functions.php'); // Функции
  require_once ('page/sec.php'); // Функции безопасности
  require_once ('config.php'); // Настройки сайта
  require_once ('includes/db.php'); // Работа с базой данных
  require_once ('includes/sql_layer.php'); // Функции для работы с БД: MySQL, mSQL, postgres и postgres_local
    if ( isset($admin) ) {
    if (is_admin($admin)) {
      require_once ('ad/ad-functions.php'); // Функции для администрирования
      if (isset($cash)) if ($cash == "del") { // Удаление всего кеша админом
        $db->sql_query("TRUNCATE TABLE `".$prefix."_cash`") or die('Не удалось стереть кеш...'); 
        die("Кеш удален. Можно <a href=\"javascript:self.close()\">закрыть</a> эту вкладку."); 
      }
    }
  } else $admin = "";
  global $zlib, $ipban, $display_errors, $pid, $site_cash;
  if ($ipban == true) require_once("includes/ipban.php"); // Бан
  $admin_file = "sys"; # Название файла панели администрирования
##########################################################################################
  // Отображение ошибок сайта, настраивается в файле config.php
  if ($display_errors) { 
    ini_set('display_errors', 1);
    error_reporting(15); // Отображает основные ошибки
  } else { // Не показывать ошибки
    ini_set('display_errors', 0);
    error_reporting(8191); // было 0, но 8191 - запрещает вывод всех ошибок PHP 4, 5 и 6
  }

  $now = date("Y.m.d H:i:s");
  $referer = getenv("HTTP_REFERER"); // REQUEST_URI
  $url = getenv("REQUEST_URI"); //substr(getenv("REQUEST_URI"), 1);
  $data = $now;
  $url0 = str_replace("http://".$siteurl,"",$url);
  $url0 = str_replace("http%3A%2F%2F".$siteurl."%2F","/",$url0);

  $pid = intval($pid);
  if ($pid > 0) if (!is_admin($admin)) { // Простой счетчик посещаемости страниц
    $db->sql_query("UPDATE ".$prefix."_pages SET counter=counter+1 WHERE pid='$pid'");
    recash($url0);
  }
##########################################################################################
// Отдаем страницу из кеша
  // Сколько дней хранить кеш
  if ($pid > 0) $cashe_day = 30;  // если это страница
  else $cashe_day = 1; // если это главная страница, разделы и папки

  if ($site_cash == true) { // проверка включения кеширования
    $sql = "SELECT `text`, `data` FROM ".$prefix."_cash where `url`='$url0' limit 1";
    $result = $db->sql_query($sql);
    $numrows = $db->sql_numrows($result);
  } else $numrows = 0;

  if ($numrows > 0) {
    // Ставим страницу из кеша
    $row = $db->sql_fetchrow($result);
    $dni = dateresize($row['data']) + $cashe_day; // Сколько хранить кеш
    $nowX = dateresize(date("Y.m.d "));
    if ($dni <= $nowX) { // Обновление
      recash($url0, 0); // стираем страницу из кеша
    }
    $txt = $row['text'];
    if ( isset($admin) ) { // Если это администратор...
      if (is_admin($admin)) $txt = page_admin($txt, $pid); // добавляем на стр. кнопку её редактирования
    }
    echo $txt; // Выводим страницу
    if ($display_errors == true) print("\n<!-- \nзапросов к БД: $db->num_queries \n$db->num_q -->"); // Запросы к БД
    die(); // Закончили вывод страницы из кеша
##########################################################################################
  } else { // переходим к настройкам сайта и генерации страницы без кеша

  // Основные настройки сайта
  $sitekey = "absolutno-luboy-soverschenno-bessmislenniy-nabor-simvolov"; # Секретный код :)
  global $red, $http_siteurl;
  $http_siteurl = "http://".$_SERVER['HTTP_HOST']; # Имя сайта
  $result = $db->sql_query("SELECT * FROM ".$prefix."_config");
  $row = $db->sql_fetchrow($result);
  $sitename = filter($row['sitename'], "nohtml"); // Имя сайта (title)
  $startdate = filter($row['startdate'], "nohtml");
  $adminmail = filter($row['adminmail'], "nohtml");
  $keywords = filter($row['keywords'], "nohtml");
  $description = filter($row['description'], "nohtml");
  $counter = stripslashes($row['counter']);
  $statlink = filter($row['statlink'], "nohtml");
  $postlink = filter($row['postlink'], "nohtml");
  $stopcopy = $row['stopcopy'];
  $registr = $row['registr'];
  $pogoda = $row['pogoda'];
  $flash = $row['flash'];
  $ht_backup = $row['ht_backup']; // Файл, в котором лежит резервная копия .htaccess
  $captcha_ok = $row['captcha_ok']; // отключение проверки комментариев
  $ca = $show_comments = $show_userposts = "";
  list($ca, $show_comments, $show_userposts, $show_page, $show_reserv, $uskorenie_blokov, $kickstart, $show_page_links, $ad_fon) = explode("|",trim($row['nocashe']));
  if ($show_page == "") $show_page = "1";
  if ($show_reserv == "") $show_reserv = "0";
  if ($uskorenie_blokov == "") $uskorenie_blokov = "0";
  if ($kickstart == "") $kickstart = "0";
  if ($show_page_links == "") $show_page_links = "1";
  if ($ad_fon == "") $ad_fon = "0";
  list($company_name, $company_fullname, $company_address, $company_time, $company_tel, $company_sot, $company_fax, $company_email, $company_map, $company_people) = explode("|||||",trim($row['sgatie']));
  $red_type = intval($row['red']); // редактор
  if (!isset($red) or $red=="") $red = $red_type;
  else {
      $db->sql_query("UPDATE ".$prefix."_config SET red='$red' WHERE red='$red_type'");
  }
  $comment_send = intval($row['comment']); // отправка комментариев админу
  $ip = getip(); //getenv("REMOTE_ADDR"); // IP
##########################################################################################
  // Получение настроек дизайна, стиля, разделов, папок и шаблонов

    // проверить
    $pages_golos = array(); // сумма голосов
    $pages_golos_num = array(); // кол-во голосовавших
    $resultX = $db->sql_query("SELECT num,golos FROM ".$prefix."_pages_golos");
    while ($rowX = $db->sql_fetchrow($resultX)) {
      $idX = $rowX['num'];
      if (!isset($pages_golos[$idX])) $pages_golos[$idX] = 0;
      if (!isset($pages_golos_num[$idX])) $pages_golos_num[$idX] = 0;
      $pages_golos[$idX] = $pages_golos[$idX] + $rowX['golos'];
      $pages_golos_num[$idX] = $pages_golos_num[$idX] + 1;
    }

    $text_mainpage = array(); // Дизайн и стиль: список содержания
    $useit_mainpage = array(); // Дизайн и стиль: список настроек
    $sqlX = "SELECT `id`,`text`,`useit` from ".$prefix."_mainpage where `type`='0' or `type`='1'";
    $resultX = $db->sql_query($sqlX);
    while ($rowX = $db->sql_fetchrow($resultX)) {
      $idX = $rowX['id'];
      $text_mainpage[$idX] = $rowX['text'];
      $useit_mainpage[$idX] = $rowX['useit'];
    }
    
    $id_mainpage2 = array(); // список ID разделов
    $title_mainpage2 = array(); // список названий разделов
    $text_mainpage2 = array(); // список содержания разделов
    $useit_mainpage2 = array(); // список настроек разделов
    $sqlY = "SELECT `id`,`name`,`title`,`text`,`useit` from `".$prefix."_mainpage` where `tables`='pages' and (`type`='2' or `type`='5')";
    $resultY = $db->sql_query($sqlY);
    while ($rowY = $db->sql_fetchrow($resultY)) {
      $nameX = $rowY['name'];
      $id_mainpage2[$nameX] = $rowY['id']; 
      $title_mainpage2[$nameX] = $rowY['title']; 
      $text_mainpage2[$nameX] = $rowY['text'];
      $useit_mainpage2[$nameX] = $rowY['useit'];
    }
    
    $text_shablon = array(); // список шаблонов
    $sqlZ = "SELECT `id`,`text` from ".$prefix."_mainpage where `tables`='pages' and type='6'";
    $resultZ = $db->sql_query($sqlZ);
    while ($rowZ = $db->sql_fetchrow($resultZ)) {
      $idZ = $rowZ['id'];
      $text_shablon[$idZ] = $rowZ['text'];
    }

    $cid_title = array(); // список названий папок
    $cid_module = array(); // список принадлежности папок к разделам
    $sql55="SELECT `cid`,`module`,`title` from ".$prefix."_pages_categories where `tables`='pages' and parent_id='0' order by `title`";
    $result55 = $db->sql_query($sql55);
    while ($row55 = $db->sql_fetchrow($result55)) {
      $id55 = $row55['cid'];
      $cid_title[$id55] = $row55['title'];
      $cid_module[$id55] = $row55['module'];
    }
  // отключение кеширования для выборочных страниц // доработать
  //$nocash = explode(" ",$mods."/?name=-search /--search ".trim(str_replace("  "," ",str_replace("\n"," ",$row['nocashe']))));
  ///////////////////////////////////////////////////////////////////////////////////////////////
}
?>