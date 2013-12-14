<?php
// Все «правила хорошего кода» написаны кровью, вытекшей из глаз программистов, читавших чужой код.
  ob_start();  // Начался вывод страницы с кешированием
  ob_implicit_flush(0);
  mb_internal_encoding('UTF-8');
  require_once ('page/functions.php'); // Функции
  require_once ('page/sec.php'); // Функции безопасности
  //if (file_exists('config.php')) 
    require_once ('config.php');
  //else die(ss("Файл")." config.php ".ss("не найден")); // Настройки сайта
  require_once ('includes/db.php'); // Работа с базой данных
  require_once ('includes/sql_layer.php'); // Функции для работы с БД MySQL
  require_once ('includes/mobiledetect.php'); // Определяем устройство - компьютер, планшет или телефон
  $detect = new Mobile_Detect;
  global $deviceType, $ipban, $display_errors, $pid, $site_cash;
  $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
  //global $lang;

  // Переходная рудиментарная опция - убрано после переделки установщика
  //if (!isset($lang)) $lang = "ru";
  //if ($lang == "ru-RU") $lang = "ru";
  //if (!isset($lang_admin)) $lang_admin = "ru";

  if ( isset($admin) ) {
    if (is_admin($admin)) {
      require_once ('ad/ad-functions.php'); // Функции для администрирования
      if (isset($cash)) if ($cash == "del") { // Удаление всего кеша админом
    	  if ( $site_cash == "base"){
          $db->sql_query("TRUNCATE TABLE `".$prefix."_cash`") or die(aa("Не удалось стереть кеш...")); 
    		}
    		if ($site_cash == "file") {
      		$files = glob("cashe/*");
          $c = count($files);
          if (count($files) > 0)
            foreach ($files as $file)  
              if (file_exists($file)) unlink($file);
        }
        die(aa("Кеш удален. Можно <a href=\"javascript:self.close()\">закрыть</a> эту вкладку.")); 
      }
    } else $admin = "";
  } else $admin = "";
  
  if ($ipban == true) require_once("includes/ipban.php"); // Бан
  $admin_file = "sys"; # Название файла панели администрирования
  if (isset($_POST)) $num_post = count($_POST);
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
  $url = getenv("REQUEST_URI"); //REQUEST_URI substr(getenv("REQUEST_URI"), 1);
  $data = $now;
  
  $url = str_replace("/", "", $url); 
  if ($url == '') $url = "-index";

  global $url_link;
  // Определение страницы для кеша без лишних параметров
  $url_link = "";
  if (!isset($go)) {
    if (isset($name) && !isset($op)) $url_link = "-".$name;
    //$go = "";
    $url_link = "-index";
  } else {
    if ($go == 'addbase') $url_link = "-".$name."_addbase_".$spa;
    if ($go == 'showdate') $url_link = "-".$name."_date_".$showdate;
    if ($go == 'showcat') {
      if (isset($first) && isset($second)) $url_link = "-".$name."_first_".$first."_second_".$second;
      if (isset($first) && isset($option)) $url_link = "-".$name."_first_".$first."_opt_".$option;
      if (isset($cid) && isset($slovo)) $url_link = "-".$name."_cat_".$cid."_slovo_".$slovo;
      if (!isset($cid) && isset($slovo)) $url_link = "-".$name."_slovo_".$slovo;
      if (isset($cid) && isset($pag)) $url_link = "-".$name."_cat_".$cid."_page_".$pag;
      if (isset($cid) && !isset($pag) && !isset($slovo) && !isset($first)) $url_link = "-".$name."_cat_".$cid;
    }
    if ($go == 'page') {
      if (isset($pid)) $url_link = "-".$name."_page_".$pid;
      if (isset($pid) && isset($com)) $url_link = "-".$name."_page_".$pid."_com_".$com;
      //if (isset($pid) && isset($comm)) $url_link = "-".$name."_page_".$pid."_comm";
    }
  }

  $pid = mysql_real_escape_string(intval($pid));
  $numrows = 0;
  if (!is_admin($admin)) { 
##########################################################################################
    // Отдаем страницу из кеша
    // Сколько дней хранить кеш
    if ($pid > 0) {
      $cashe_day = 30;  // если это страница
      $db->sql_query("UPDATE ".$prefix."_pages SET `counter`=counter+1 WHERE `pid`='".$pid."'"); // Простой счетчик посещаемости страниц
      recash($url_link);
    } else $cashe_day = 1; // если это главная страница, разделы и папки

    // если кеш на базе
  	if ($site_cash == "base" and $num_post == 0) {
      $sql = "SELECT `text`, `data` FROM ".$prefix."_cash where `url`='".mysql_real_escape_string($url_link)."' limit 1";
      $result = $db->sql_query($sql);
      $numrows = $db->sql_numrows($result);
    }
  	// если кеш на файлах
  	if ($site_cash == "file" and $num_post == 0) {
    	if ($url_link == '/' or $url_link == '') $url_link = "-index";
      if (file_exists("cashe/".$url_link) && $url_link != "-index") $numrows = 1;
    }
  }
  if ($numrows > 0 && $url_link != "" && !is_admin($admin)) {
    // Ставим страницу из кеша
	  // если кеш на базе
		if ( $site_cash == "base") {
      $row = $db->sql_fetchrow($result);
      $dni = dateresize($row['data']) + $cashe_day; // Сколько хранить кеш
    	$txt = $row['text'];
  	}
  	// если кеш на файлах
  	if ( $site_cash == "file") {
    	$dni = dateresize(date("Y.m.d H:i:s", fileatime("cashe/".$url_link))) + $cashe_day;
    	$txt = stripcslashes(file_get_contents("cashe/".$url_link));
  	}
    $nowX = dateresize(date("Y.m.d "));
    if ($dni <= $nowX) { // Обновление
      recash($url_link, 0); // стираем страницу из кеша
    }
    
    if ( isset($admin) ) { // Если это администратор...
      if (is_admin($admin)) $txt = page_admin($txt, $pid); // добавляем на стр. кнопку её редактирования
    }
    echo $txt; // Выводим страницу
    if ($display_errors == true) print("\n<!-- \n".aa("запросов к БД").": ".$db->num_queries." \n".$db->num_q." -->"); // Запросы к БД
    die(); // Закончили вывод страницы из кеша
##########################################################################################
  } else { // переходим к настройкам сайта и генерации страницы без кеша

  // Основные настройки сайта
  $include_tabs = false; // подключение табов
  $sitekey = "absolutno-luboy-soverschenno-bessmislenniy-nabor-simvolov"; # Секретный код :)
  global $red, $http_siteurl;
  $http_siteurl = "http://".$_SERVER['HTTP_HOST']; # Имя сайта
  $result = $db->sql_query("SELECT * FROM ".$prefix."_config");
  $row = $db->sql_fetchrow($result);
  $sitename = filter($row['sitename']); // Имя сайта (title)
  $startdate = $row['startdate'];
  $adminmail = $row['adminmail'];
  $keywords = filter($row['keywords']);
  $description = filter($row['description']);
  $counter = $row['counter'];
  $statlink = filter($row['statlink']);
  $postlink = $row['postlink'];
  $stopcopy = intval($row['stopcopy']);
  $registr = intval($row['registr']);
  $pogoda = intval($row['pogoda']);
  $flash = intval($row['flash']);
  $ht_backup = $row['ht_backup']; // Файл, в котором лежит резервная копия .htaccess
  $captcha_ok = intval($row['captcha_ok']); // отключение проверки комментариев
  $jqueryui = $show_comments = $show_userposts = $normalize = "";
  // Получаем настройки
  list($jqueryui, $show_comments, $show_userposts, $show_page, $show_reserv, $uskorenie_blokov, $kickstart, $show_page_links, $ad_fon, $search_design, $tag_design, $add_fonts, $normalize, $project_logotip, $project_name, $geo, $kolkey, $add_clips, $sortable, $color_tema_html, $color_tema_css, $color_tema_js, $color_tema_php, $tab_obzor, $tab_show, $shop_text_val1, $shop_text_val2, $shop_text_itogo, $shop_text_oformit, $shop_text_korzina, $shop_text_delete, $shop_pole, $shop_admin_mail, $shop_text_after_mail,$shop_spisok_pole, $shop_shablon_form_order, $shop_shablon_mail_client, $shop_shablon_mail_admin,$ed2_button_html,$ed2_button_formatting, $ed2_button_bold, $ed2_button_italic, $ed2_button_deleted, $ed2_button_underline, $ed2_button_unorderedlist, $ed2_button_orderedlist, $ed2_button_outdent, $ed2_button_indent, $ed2_button_image, $ed2_button_video, $ed2_button_file, $ed2_button_table, $ed2_button_link, $ed2_button_alignment, $ed2_button_horizontalrule, $ed2_button_more, $ed2_button_link2, $ed2_button_block, $ed2_button_pre, $ed2_button_fullscreen, $ed2_button_clips, $ed2_button_fontcolor, $ed2_button_fontsize, $ed2_button_fontfamily, $ed2_minHeight, $ed2_direction, $ed2_paragraphy, $ed2_button_superscript, $head_insert, $filter_name, $filter_show_all, $gravatar, $ed2_div_convert, $strelka, $smile_icons, $add_mail_shablons, $avtor_comments, $search_in_pages, $search_in_papka, $search_in_razdel, $newsmail_design, $search_col_razdel, $search_col_papka, $search_col_page, $search_col_showall, $scrollyeah, $lightload, $spin, $razdel_sort, $show_admin_top) = explode("|",trim($row['nocashe']));
  //if ($add_fonts != "") $add_fonts = explode(".",$add_fonts);
  $project_name = filter($project_name);
  // Основные настройки
  if ($show_admin_top == "") $show_admin_top = "1";
  if ($razdel_sort == "") $razdel_sort = "color desc, title";
  if ($smile_icons == "") // получаем список файлов смайлов
    $smile_icons = str_replace("images/smilies/","",implode(",", glob("images/smilies/{*.gif,*.png,*.jpg,*.jpeg}",GLOB_BRACE)));
  if ($avtor_comments == "") $avtor_comments = "Администратор, Редактор, Модератор, Менеджер";
  if ($gravatar == "") $gravatar = "0";
  if ($project_logotip == "") $project_logotip = "img/logotip.png";  
  if ($tab_obzor == "") $tab_obzor = ss("Обзор");
  if ($tab_show == "") $tab_show = "1";
  if ($jqueryui == "") $jqueryui = "1";
  if ($normalize == "") $normalize = "0";
  if ($sortable == "") $sortable = "0";
  if ($show_page == "") $show_page = "1";
  if ($show_reserv == "") $show_reserv = "0";
  if ($uskorenie_blokov == "") $uskorenie_blokov = "0";
  if ($kickstart == "") $kickstart = "0";
  if ($show_page_links == "") $show_page_links = "0";
  if ($ad_fon == "") $ad_fon = "1";
  if ($tag_design == "") $tag_design = "1";
  if ($newsmail_design == "") $newsmail_design = "1";
  if ($geo == "") $geo = "51";
  if ($kolkey == "") $kolkey = "8";
  // поиск
  if ($search_in_pages == "") $search_in_pages = "1";
  if ($search_in_papka == "") $search_in_papka = "0";
  if ($search_in_razdel == "") $search_in_razdel = "0";
  if ($search_design == "") $search_design = "1";
  if ($search_col_razdel == "") $search_col_razdel = "10"; // ограничение кол-ва найденных разделов
  if ($search_col_papka == "") $search_col_papka = "10"; // кол-во папок
  if ($search_col_page == "") $search_col_page = "50"; // кол-во страниц
  if ($search_col_showall == "") $search_col_showall = "500"; // кол-во страниц при нажатии Показать все
  // Настройки фильтра
  if ($filter_name == "") $filter_name = ss("Фильтр товаров");
  if ($filter_show_all == "") $filter_show_all = ss("Показать все");
  // Настройки магазина
  if ($shop_text_val2 == "") $shop_text_val2 = ss(" руб.");
  if ($shop_text_itogo == "") $shop_text_itogo = ss("Итого:");
  if ($shop_text_oformit == "") $shop_text_oformit = ss("Оформить покупку");
  if ($shop_text_korzina == "") $shop_text_korzina = ss("Ваша Корзина пуста.");
  if ($shop_text_delete == "") $shop_text_delete = "x";
  if ($shop_pole == "") $shop_pole = "";
  if ($shop_admin_mail == "") $shop_admin_mail = $adminmail;
  if ($shop_text_after_mail == "") $shop_text_after_mail = "<h1>".ss("Спасибо!")."</h1><h3>".ss("Ваш заказ успешно отправлен. В ближайшее время мы вам позвоним.")."</h3>";
  if ($shop_spisok_pole == "") $shop_spisok_pole = ss("Ф.И.О.:*\nТелефон:*\nEmail:\nАдрес:\nДополнительная информация:");
  //if ($shop_shablon_form_order == "") $shop_shablon_form_order = "";
  //if ($shop_shablon_mail_client == "") $shop_shablon_mail_client = "";
  list($company_name, $company_fullname, $company_address, $company_time, $company_tel, $company_sot, $company_fax, $company_email, $company_map, $company_people) = explode("|||||",trim($row['sgatie']));
  $red_type = intval($row['red']); // редактор
  if (!isset($red) or $red=="") $red = $red_type;
  else {
    if ($red != "1" && $red != "2") $db->sql_query("UPDATE ".$prefix."_config SET `red`='".mysql_real_escape_string($red)."' WHERE `red`='".mysql_real_escape_string($red_type)."'");
  }
  $comment_send = intval($row['comment']); // отправка комментариев админу
  $ip = getip(); //getenv("REMOTE_ADDR"); // IP
##########################################################################################
  // Получение настроек дизайна, стиля, разделов, папок и шаблонов

    /*
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
    */
    /*
    $text_mainpage = array(); // Дизайн и стиль: список содержания
    $useit_mainpage = array(); // Дизайн и стиль: список настроек
    $sqlX = "SELECT `id`,`text`,`useit` from ".$prefix."_mainpage where `type`='0' or `type`='1'";
    $resultX = $db->sql_query($sqlX);
    while ($rowX = $db->sql_fetchrow($resultX)) {
      $idX = $rowX['id'];
      $text_mainpage[$idX] = $rowX['text'];
      $useit_mainpage[$idX] = $rowX['useit'];
    }
    */

    $id_razdel_and_bd = array(); // список ID разделов
    $title_razdel_and_bd = array(); // список рус. названий разделов и БД
    $title_razdels = array(); // список рус. названий разделов
    $title_razdels_by_id = array(); // список рус. названий разделов по ID
    $txt_razdels = array(); // список содержания разделов
    $useit_razdels = array(); // список настроек разделов
    $name_razdels = array(); // список англ. названий разделов
    $pass_razdels = array(); // список паролей для раздела
    $nopass_razdels = array(); // список без паролей для раздела
    //$sqlY = "SELECT `id`,`type`,`name`,`title`,`text`,`useit` from `".$prefix."_mainpage` where `tables`='pages' and (`type`='2' or `type`='5')";
    $sqlY = "SELECT `id`,`type`,`name`,`title`,`text`,`useit` from `".$prefix."_mainpage` where `tables`='pages' and (`type`='1' or `type`='2' or `type`='5')";//`type`='1' or 
    $resultY = $db->sql_query($sqlY);
    while ($rowY = $db->sql_fetchrow($resultY)) {
      $nameX = $rowY['name'];
      $idX = $rowY['id'];
      if (strpos($rowY['name'], "\n")) {
        $names = explode("\n", str_replace("\r", "", $rowY['name']));
        $nameX = trim($names[0]);
        $nopass_razdels[$idX] = $nameX;
        $pass_razdels[$nameX] = $names;
      }
      $id_razdel_and_bd[$nameX] = $rowY['id']; 
      $title_razdel_and_bd[$nameX] = $rowY['title']; 

      if ($rowY['type'] == 5) $title_razdel_and_bd[$nameX] = aa("База данных")." «".$title_razdel_and_bd[$nameX]."»";
      else {
        if ($rowY['type'] != 1) {
          $name_razdels[$idX] = $rowY['name'];
          $title_razdels[$nameX] = $rowY['title']; 
          $txt_razdels[$nameX] = $rowY['text'];
          $useit_razdels[$nameX] = $rowY['useit'];
          $title_razdels_by_id[$idX] = $rowY['title'];
        } else {
          $title_razdels_by_id[$idX] = $rowY['title'];
        }
      }
    }
    
  // отключение кеширования для выборочных страниц // доработать
  //$nocash = explode(" ",$mods."/?name=-search search ".trim(str_replace("  "," ",str_replace("\n"," ",$row['nocashe']))));
  ///////////////////////////////////////////////////////////////////////////////////////////////
}
#############################
function close_button($txt) { // Кнопка для закрытия, обращение по id объекта
  return "<a class='punkt' onclick=\"$('#".$txt."').hide('slow');\"><div class='radius' style='font-size:12pt; width:20px; height: 20px; color: white; text-align:center; float:right; margin:5px; background: #bbbbbb;'>&nbsp;x&nbsp;</div></a>";
}

// локализация
global $lang, $lang_admin;
if ($lang_admin != 'ru')  $lang_text_admin = include ('language/adm_'.$lang_admin.'.php');
if ($lang != 'ru')        $lang_text = include ('language/'.$lang.'.php');
#############################
function aa($t) { // Функция перевода админки / Translate administration function
  global $lang_admin, $lang_text_admin;
  if ($lang_admin == 'ru') return $t; // Русский — по-умолчанию.
  else 
    if (isset($lang_text_admin[$t])) return $lang_text_admin[$t];
    else return " [ No translate: ".$t." ] ";
}
#############################
function ss($t) { // Функция перевода сайта / Translate function
  global $lang, $lang_text;
  if ($lang == 'ru') return $t; // Русский — по-умолчанию.
  else
    if (isset($lang_text[$t])) return $lang_text[$t];
    else return " [ No translate: ".$t." ] ";
}
#############################
function getFromPOST($name, $value) { // перевести все запросы на функцию!
  return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $value;
}
#############################
?>