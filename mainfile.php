<?php
  ob_start();  // Начался вывод страницы с кешированием
  ob_implicit_flush(0); 
  session_start(); // Для капчи (проверочный код-картинка от спамеров) // проверить вызов
  //$start_time = microtime(); // считываем текущее время // удалить
  //$start_array = explode(" ",$start_time); // разделяем секунды и миллисекунды
  //$start_time = $start_array[1] + $start_array[0]; // это и есть стартовое время

  while( list($key, $value) = @each($_REQUEST) ) {
    $_REQUEST[$key] = (!isset($value)) ? '' : $value;
  }
  while( list($key, $value) = @each($GLOBALS) ) {
    $GLOBALS[$key] = (!isset($value)) ? '' : $value;
  }

  unset($pagetitle); 
  if(!defined('END_TRANSACTION')) {
    define('END_TRANSACTION', 2);
  }
  $phpver = phpversion();
  $_SERVER['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER'])?addslashes(stripslashes($_SERVER['HTTP_REFERER'])):'';

  $HTTP_GET_VARS = $_GET;
  $HTTP_POST_VARS = $_POST;
  $HTTP_SERVER_VARS = $_SERVER;
  $HTTP_POST_FILES = $_FILES;
  $HTTP_ENV_VARS = $_ENV;
  $PHP_SELF = $_SERVER['PHP_SELF'];
  if(isset($_SESSION)) $HTTP_SESSION_VARS = $_SESSION;
  if(isset($_COOKIE)) $HTTP_COOKIE_VARS= $_COOKIE;

  if(isset($_REQUEST['id']))  $id = intval($_REQUEST['id']);
  if(isset($_REQUEST['cid'])) $cid = intval($_REQUEST['cid']);
  if(isset($_REQUEST['pid'])) $pid = intval($_REQUEST['pid']);
  if(isset($_REQUEST['blocks'])) $blocks = intval($_REQUEST['blocks']);

  if (stristr(htmlentities($_SERVER['PHP_SELF']), "mainfile.php")) {
    header("Location: index.php");
    exit();
  }

  @require_once("config.php"); // Настройки сайта
  global $zlib;
  if ($zlib == true) {
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && !empty($_SERVER['HTTP_ACCEPT_ENCODING'])) {
      if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
        if (extension_loaded('zlib')) {
          $do_gzip_compress = true;
          ob_start(array('ob_gzhandler',5));
          ob_implicit_flush(0);
          header('Content-Encoding: gzip');
        }
      }
    }
  }

  foreach ($_COOKIE AS $c_key => $c_val) {
      if (isset($_POST[$c_key]) OR isset($_GET[$c_key])) unset($_COOKIE[$c_key]); 
      $c_val = str_replace("select ","",$c_val);
      $c_val = str_replace("union ","",$c_val);
      $c_val = str_replace(".php","",$c_val);
      $_COOKIE[$c_key] = $c_val;
  }  
   if (!ini_get('register_globals')) { 
   extract($_POST, EXTR_SKIP); 
   extract($_GET, EXTR_SKIP); 
   extract($_COOKIE, EXTR_SKIP); 
  } 

  // Union Tap против UNION SQL Injections
  unset($matches);
  unset($loc);
  if(isset($_SERVER['QUERY_STRING'])) {
    if (preg_match("/([OdWo5NIbpuU4V2iJT0n]{5}) /", rawurldecode($loc=$_SERVER['QUERY_STRING']), $matches)) {
    die('Попытка взлома, тип 1');
    }
  }

  if((isset($admin) && $admin != $_COOKIE['admin'])) {
    die("Попытка взлома, тип 2");
  }

  // функция stripos (из ПХП5), клонированная для ПХП4
  if(!function_exists('stripos')) {
    function stripos_clone($haystack, $needle, $offset=0) {
    return strpos(strtoupper($haystack), strtoupper($needle), $offset);
    }
  } else { // Но если это ПХП5 - используем оригинал!
    function stripos_clone($haystack, $needle, $offset=0) {
    return stripos($haystack, $needle, $offset=0);
    }
  }

  // Дополнительная безопасность (Union, CLike, XSS)
  if(isset($_SERVER['QUERY_STRING'])) {
    $queryString = strtolower($_SERVER['QUERY_STRING']); // Если будут ошибки - убрать!
    if (stripos_clone($queryString,'0DUNION') OR stripos_clone($queryString,'%20union%20') OR stripos_clone($queryString,'/*') OR stripos_clone($queryString,'*/union/*') OR stripos_clone($queryString,'c2nyaxb0') OR stripos_clone($queryString,'+union+') OR stripos_clone($queryString,'http://') OR (stripos_clone($queryString,'cmd=') AND !stripos_clone($queryString,'&cmd')) OR (stripos_clone($queryString,'exec') AND !stripos_clone($queryString,'execu')) OR stripos_clone($queryString,'concat')) {
      die('Попытка взлома, тип 3');
    }
  }

  // Заплатка безопасности
  $postString = "";
  foreach ($_POST as $postkey => $postvalue) {
    if ($postString > "") {
     $postString .= "&".$postkey."=".$postvalue;
    } else {
     $postString .= $postkey."=".$postvalue;
    }
  }
  $postString = str_replace("%09", "%20", $postString);
  $postString = str_replace("%20union%20", "crazy", $postString); // баг.
  $postString = str_replace("%20Union%20", "crazy", $postString); // баг.
  $postString = str_replace(" union ", "crazy", $postString); // баг.
  $postString = str_replace(" Union ", "crazy", $postString); // баг.
  $postString_64 = base64_decode($postString);
  if (stripos_clone($postString,'%20union%20') OR stripos_clone($postString,'*/union/*') OR stripos_clone($postString,' union ') OR stripos_clone($postString_64,'%20union%20') OR stripos_clone($postString_64,'*/union/*') OR stripos_clone($postString_64,' union ') OR stripos_clone($postString_64,'+union+') OR stripos_clone($postString_64,'http://') OR (stripos_clone($postString_64,'cmd=') AND !stripos_clone($postString_64,'&cmd')) OR (stripos_clone($postString_64,'exec') AND !stripos_clone($postString_64,'execu')) OR stripos_clone($postString_64,'concat')) {
    die('Попытка взлома, тип 4');
  }

  if (isset($admin) && $admin == $_COOKIE['admin']) {
    $admin = base64_decode($admin);
    $admin = addslashes($admin);
    $admin = base64_encode($admin);
  }

  // Сабжы для использующих вредоносный HTML-код
  $htmltags = "<center><img src=\"/images/logo_admin.png\"><br><br><b>";
  $htmltags .= "Вы использовали запрещенные символы HTML-кода. Вероятно вы - взломщик.</b><br><br>";
  $htmltags .= "[ <a href=\"javascript:history.go(-1)\"><b>Вернитесь назад и больше не вводите HTML-теги.</b></a> ]";

  if (!defined('ADMIN_FILE')) {
    foreach ($_GET as $secvalue) {
      $secvalue = str_replace("(", "&#040;", str_replace(")", "&#041;", $secvalue));
      if ( (preg_match("/<[^>]*script*\"?[^>]*>/i", $secvalue)) ||
    	(preg_match("/<[^>]*object*\"?[^>]*>/i", $secvalue)) ||
    	(preg_match("/<[^>]*iframe*\"?[^>]*>/i", $secvalue)) ||
    	(preg_match("/<[^>]*applet*\"?[^>]*>/i", $secvalue)) ||
    	(preg_match("/<[^>]*meta*\"?[^>]*>/i", $secvalue)) ||
    	(preg_match("/<[^>]*style*\"?[^>]*>/i", $secvalue)) ||
    	(preg_match("/<[^>]*form*\"?[^>]*>/i", $secvalue)) ||
    	(preg_match("/<[^>]*img*\"?[^>]*>/i", $secvalue)) ||
    	(preg_match("/<[^>]*onmouseover*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onmouseout*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onmousemove*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onmouseup*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onload*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onreset*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onresize*\"?[^>]*>/i", $secvalue)) ||
    	(preg_match("/<[^>]*body*\"?[^>]*>/i", $secvalue)) ||
    	(preg_match("/\([^>]*\"?[^)]*\)/i", $secvalue)) ||
    	(preg_match("/\"/i", $secvalue)) ) {
      die ($htmltags);
      }
    }
    
    foreach ($_POST as $secvalue) {
      $secvalue = str_replace("(", "&#040;", str_replace(")", "&#041;", $secvalue));
      if ( (preg_match("/<[^>]*script*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*object*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*iframe*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*applet*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*meta*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*style*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*form*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*img*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*onmouseover*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onmouseout*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onmousemove*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onmouseup*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onload*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onreset*\"?[^>]*>/i", $secvalue)) ||
        (preg_match("/<[^>]*onresize*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/<[^>]*body*\"?[^>]*>/i", $secvalue)) ||
      (preg_match("/\([^>]*\"?[^)]*\)/i", $secvalue))
      ) {
      die ($htmltags);
      }
    }
  }

  // Если HTTP_REFERER будет пустым
  $posttags = "<b>Ошибка:</b> браузер не смог послать заголовок HTTP_REFERER для этого сайта.<br>
  Вся проблема - в браузере, использовании прокси-сервера или фаервола.<br>
  Смените браузер, отключите прокси и фаервол.<br><br>
  А может быть вы просто сохранили страницу сайта, открыли её и пытаетесь отправить письмо, комментарий или проголосовать.<br>
  Это возможно только со страниц сайта.";

  // Запрет использования других серверов, спасибо челам Quake и PeNdEjO
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_SERVER['HTTP_REFERER'])) {
    if (!stripos_clone($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])) {
     die('Запрещено размещение информации с другого сервера');
    }
    } else {
    die($posttags);
    }
  }
     
     @require_once("includes/db.php"); // База данных (функции для работы)
     @require_once("includes/sql_layer.php");
     global $ipban;
     if ($ipban == true) @require_once("includes/ipban.php"); // Бан
     $admin_file = "sys"; # Название файла административной панели

  // Отображение ошибок сайта, настраивается в файле config.php
  ini_set('safe_mode', 1); // проверить
  ini_set('expose_php', 0);

  if($display_errors) { 
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

  // Счетчик
  global $pid, $site_cash;
  $pid = intval($pid);

  if (!isset($admin)) $admin = "";
  else if (isset($cash)) if($cash == "del") { 
    $result = $db->sql_query("TRUNCATE TABLE `".$prefix."_cash`"); 
    die("Кеш удален. Можно закрыть эту вкладку."); 
  }
  if ($pid > 0 and !is_admin($admin)) {
    $db->sql_query("UPDATE ".$prefix."_pages SET counter=counter+1 WHERE pid='$pid'");
    recash($url0);
  }
 
// Отдаем страницу из кеша ==================================================
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
    $dni = dateresize($row['data']) + $cashe_day; // Сколько хранить кеш - 2 дня!
    $nowX = dateresize(date("Y.m.d "));
    if ($dni <= $nowX) { // Обновление
      // стираем страницу из кеша
      recash($url0, 0);
    }
    $txt = $row['text'];
    if ( is_admin($admin) ) $txt = page_admin($txt, $pid);
    echo $txt;
    if ($display_errors == true) print("<!-- запросов: $db->num_queries --> <!-- $db->num_q -->");
    die();
    // Закончили вывод страницы из кеша
  } else { // переходим к настройкам сайта, а далее - к генерации страницы без кеша


  // Основные настройки сайта
  $AllowableHTML = array("col"=>2,"td"=>2,"tr"=>2,"hr"=>2,"p"=>2,"font"=>2,"embed"=>2,"font color"=>2,"table"=>2,"b"=>2,"i"=>2,"strike"=>2,"div"=>2,"u"=>2,"a"=>2,"em"=>2,"br"=>2,"strong"=>2,"blockquote"=>2,"tt"=>2,"li"=>2,"ol"=>2,"ul"=>2,"img"=>2,"img src="=>2);
  $sitekey = "absolutno-luboy-soverschenno-bessmislenniy-nabor-simvolov"; # Секретный код
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
 


// Отдаем страницу без кеша ====================================================

    // Если не кешированная стр., то получаем инфу о голосованиях // проверить
    $pages_golos = array();
    $pages_golos_num = array();
    $resultX = $db->sql_query("SELECT num,golos FROM ".$prefix."_pages_golos");
    while ($rowX = $db->sql_fetchrow($resultX)) {
      $idX = $rowX['num'];
      if (!isset($pages_golos[$idX])) $pages_golos[$idX] = 0;
      if (!isset($pages_golos_num[$idX])) $pages_golos_num[$idX] = 0;
      $pages_golos[$idX] = $pages_golos[$idX] + $rowX['golos']; // сумма голосов
      $pages_golos_num[$idX] = $pages_golos_num[$idX] + 1; // кол-во голосовавших
    }
    // id	type	name	title	text	useit	shablon	counter	tables	color	description	keywords
    $text_mainpage = array();
    $useit_mainpage = array();
    $sqlX = "SELECT `id`,`text`,`useit` from ".$prefix."_mainpage where `type`='0' or `type`='1'"; // Дизайн и стиль
    $resultX = $db->sql_query($sqlX);
    while ($rowX = $db->sql_fetchrow($resultX)) {
      $idX = $rowX['id'];
      $text_mainpage[$idX] = $rowX['text'];
      $useit_mainpage[$idX] = $rowX['useit'];
      //if ($rowX['type'] == 2) $mods .= "/-".$rowX['name']." "; // отключение кеширования разделов
    }
    
    $id_mainpage2 = array();
    $title_mainpage2 = array();
    $text_mainpage2 = array();
    $useit_mainpage2 = array();
    $sqlY = "SELECT `id`,`name`,`title`,`text`,`useit` from `".$prefix."_mainpage` where `tables`='pages' and (`type`='2' or `type`='5')"; // список разделов
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
    
    $sql55="SELECT `cid`,`module`,`title` from ".$prefix."_pages_categories where `tables`='pages' and parent_id='0' order by `title`"; // список папок
    $result55 = $db->sql_query($sql55);
    $cid_title = array();
    $cid_module = array();
    while ($row55 = $db->sql_fetchrow($result55)) {
      $id55 = $row55['cid'];
      $cid_title[$id55] = $row55['title'];
      $cid_module[$id55] = $row55['module'];
    }
  // отключение кеширования для выборочных страниц
  //$nocash = explode(" ",$mods."/?name=-search /--search ".trim(str_replace("  "," ",str_replace("\n"," ",$row['nocashe']))));
  ///////////////////////////////////////////////////////////////////////////////////////////////
}
















/////////////////////////////////////////////////////////


// ФУНКЦИИ


/////////////////////////////////////////////////////////

















/////////////////////////////////////////////////////////
function page_admin($txt, $pid) { // проверить вызов и/или перенести в админку
  global $db, $prefix, $module_name, $url, $name;
  if ( $pid > 0 ) $red = "
<script src=includes/JsHttpRequest/JsHttpRequest.js></script>
<script>
function delpage(pid) {
  JsHttpRequest.query('ad-ajax.php', {'delpage': pid}, function(result, errors) {},false);
  $('#redact').html('Страница удалена в Корзину.');
}
</script>
<a target=_blank href='/sys.php?op=base_pages_edit_page&amp;pid=".$pid."'>Редактировать страницу</a><br><a target=_blank style='cursor:pointer; color:red;' onclick='delpage(".$pid.");'>Удалить страницу</a><br>"; 
  elseif ( $pid == 0 and $module_name != "" ) {
    // выяснить id
    $sql55="SELECT `id` from ".$prefix."_mainpage where `tables`='pages' and `type`='2' and `name`='".$name."'";
    $result55 = $db->sql_query($sql55);
    $row55 = $db->sql_fetchrow($result55);
    $name_id = $row55['id'];
    $red = "<a target=_blank href='/sys.php?op=mainpage&amp;id=$name_id'>Редактировать главную страницу раздела</a><br><a target=_blank href='sys.php?op=base_pages_add_page&amp;name=$name#1'>Добавить новую страницу в раздел</a><br>";
  }
  elseif ( $module_name == "" ) $red = "<a target=_blank href='/sys.php?op=mainpage&amp;id=24'>Редактировать Главную страницу</a><br>";
  else $red = "";
  $url = getenv("REQUEST_URI");
  $txt = str_replace("</body>","<div id='redact_show' style='position:absolute; top:10px; floaf:right; right:20px; z-index:300; width:18px;'><a title='Показать настройки администратора' href=# style='cursor:pointer;' onclick=\"show('redact'); show('redact_show');\"><img class='icon2 i35' src='/images/1.gif'></a></div><div id='redact' style='background: white; color: black; position:absolute; top:5px; display:none; floaf:right; right:20px; z-index:300; width:290px;' class=show_block><div class=show_block_title><a title='Скрыть настройки администратора' href=# style='cursor:pointer;' onclick=\"show('redact_show'); show('redact');\"><img class='icon2 i33' src='/images/1.gif' align=right></a>Настройки администратора</div>".$red."<form method=post name=blocks_show action='".$url."' style='display:inline;'><input type='hidden' name=blocks value='1'><a href='javascript:document.blocks_show.submit();' title='Показать редактирование блоков на странице'>Показать редактирование блоков</a></form><br><a href='/sys.php?op=base_pages_re&amp;link=".$url."'>Обновить страницу</a></div></body>",$txt);
  return $txt;
}
/////////////////////////////////////////////////////////
function red_vybor() { // Выбор редактора (перенести в админку)
  global $url;
  $link = str_replace("&red=0","",str_replace("&red=1","",str_replace("&red=2","",str_replace("&red=3","",str_replace("&red=4","",$url)))));
  echo "
  <script>
  $(function() { $( \"#rerurn\" ) .button() .click(function() { show('red_vybor'); }) .next() .button( { text: false, icons: {primary: \"ui-icon-triangle-1-s\"} }) .click(function() { show('red_vybor_small'); }) .parent() .buttonset(); });
  </script>
  <div class='vybor_redaktora' style='float:right; margin-top:5px;'><div>
    <button id='rerurn'><img class='icon2 i35' src='/images/1.gif'>Выбрать редактор</button>
    <button id='sel'>Быстрый выбор редактора</button>
  </div></div>

  <div id='red_vybor_small' style='position:absolute; top:105px; right:12px; z-index:666; width:225px; background:white; display:none; border:solid 1px gray;' class=radius>
  <a href='".$link."&red=0' class=dark_pole3 style='width:162px'>Простой</a><br>
  <a href='".$link."&red=2' class=dark_pole3 style='width:162px'>Навороченный</a><br>
  <a href='".$link."&red=3' class=dark_pole3 style='width:162px'>Удобный</a><br>
  <a href='".$link."&red=4' class=dark_pole3 style='width:162px'><b>Лучший</b></a><br>
  <a href='".$link."&red=1' class=dark_pole3 style='width:162px'>HTML</a><br>
  <a onclick=show('red_vybor_small') style='cursor:pointer; float:right;' title='Закрыть'>Закрыть</a>
  </div>

  <div id='red_vybor' style='position: absolute; z-index:666; right:5px; top:5px; padding:5px; width:647px; background:white; display:none; border:solid 10px gray;' class=radius>
  <a onclick=show('red_vybor') style='cursor:pointer; float:right;' title='Закрыть'><img class='icon2 i33' src='/images/1.gif'></a>
  <h1>Выбор редактора</h1>
  <b>У всех редакторов есть:</b> Вставка ссылок, фотографий и таблиц, Жирность, Наклонность, Цвет текста, Верхний индекс, Списки, Заголовки, Центрирование и HTML-код

<a href='".$link."&red=0' class='dark_pole3'>
  <img src=/images/0.jpg><br>
  <h2 style='display:inline'>Простой</h2> Разделительная черта, flash-ролики, Чистка HTML при вставке из Word'а или с сайтов</a>

<a href='".$link."&red=2' class='dark_pole3'>
  <img src=/images/2.jpg><br>
  <h2 style='display:inline'>Навороченный</h2> Поиск/Замена, Размер текста, Цитата, Отступы списков, Подстрочный индекс, Зачеркнутый текст, flash-ролики, Смайлики, Спецсимволы, Разделительная черта, Цвет фона текста, Вставить из Word'а, Maximaze (во весь экран)</a>

<a href='".$link."&red=3' class='dark_pole3'>
  <img src=/images/3.jpg><br>
  <h2 style='display:inline'>Удобный</h2> Быстрая вставка фотографий, Удобная работа с таблицами, Вставка видео-роликов и файлов, Автоматическая чистка! при вставке из Word'а, Во весь экран, Зачеркнутый текст, Заливка текста (Цвет фона), Отступы списков.</a>

<a href='".$link."&red=4' class='dark_pole3'>
  <img src=/images/4.jpg><br>
  <h2 style='display:inline'>Лучший</h2> Возможности редактора «Удобный» + Загрузка файлов и фотографий путем переноса мышкой (Drag&Drop), Изменение размеров фотографий движением мышки после их удерживания, Автоматическое изменение размера окна редактора.</a>

<a href='".$link."&red=1' class='dark_pole3'>
  <img src=/images/1.jpg><br>
  <h2 style='display:inline'>HTML-код</h2> Невизуальный редактор для понимающих HTML-разметку.</a>

  </div>";
}
/////////////////////////////////////////////////////////
function makePass() { // Создать пароль
	$cons = "bcdfghjklmnpqrstvwxyz";
	$vocs = "aeiou";
	for ($x=0; $x < 6; $x++) {
		mt_srand ((double) microtime() * 1000000);
		$con[$x] = substr($cons, mt_rand(0, strlen($cons)-1), 1);
		$voc[$x] = substr($vocs, mt_rand(0, strlen($vocs)-1), 1);
	}
	mt_srand((double)microtime()*1000000);
	$num1 = mt_rand(0, 9);
	$num2 = mt_rand(0, 9);
	$makepass = $con[0] . $voc[0] .$con[2] . $num1 . $num2 . $con[3] . $voc[3] . $con[4];
	return($makepass);
}
/////////////////////////////////////////////////////////
global $adminSave; // Замена
/////////////////////////////////////////////////////////
function is_admin($admin) { // Проверка админа
  global $adminSave;
  if (!$admin) { return 0; }
  if (isset($adminSave)) return $adminSave;
  if (!is_array($admin)) {
    $admin = base64_decode($admin);
    $admin = addslashes($admin);
    $admin = explode(":", $admin);
  }
  $aid = $admin[0];
  $pwd = $admin[1];
  $aid = substr(addslashes($aid), 0, 25);
  if (!empty($aid) && !empty($pwd)) {
    global $prefix, $db;
    $sql = "SELECT pwd FROM ".$prefix."_authors WHERE aid='$aid'";
    $result = $db->sql_query($sql);
    $pass = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    if ($pass[0] == $pwd && !empty($pass[0])) {
  ////// static $adminSave;
      return $adminSave = 1;
    }
  }
  ////// static $adminSave;
  return $adminSave = 0;
}
/////////////////////////////////////////////////////////
function is_user($user) {
  if (!$user) { return 0; }
  if (isset($userSave)) return $userSave;
  if (!is_array($user)) {
  $user = base64_decode($user);
  $user = addslashes($user);
  $user = explode(":", $user);
  }
  $uid = $user[0];
  $pwd = $user[2];
  $uid = intval($uid);
  if (!empty($uid) AND !empty($pwd)) {
  global $db, $user_prefix;
  $sql = "SELECT user_password FROM ".$user_prefix."_users WHERE user_id='$uid'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $db->sql_freeresult($result);
  if ($row[0] == $pwd && !empty($row[0])) {
  static $userSave;
  	return $userSave = 1;
  }
  }
  static $userSave;
  return $userSave = 0;
}
/////////////////////////////////////////////////////////
function FixQuotes($what = "",$strip="") {
  //$what = str_replace("'","''",$what);
  while (stripos_clone($what, "\\\\'")) { // stristr
    $what = str_replace("\\\\'","'",$what);
  }
  return $what;
}
/////////////////////////////////////////////////////////
function delQuotes($string) { # Фильтры текста
	/* no recursive function to add quote to an HTML tag if needed */
	/* and delete duplicate spaces between attribs. */
	$tmp="";  # string buffer
	$result=""; # result string
	$i=0;
	$attrib=-1; # Are us in an HTML attrib ?   -1: no attrib   0: name of the attrib   1: value of the atrib
	$quote=0;   # Is a string quote delimited opened ? 0=no, 1=yes
	$len = strlen($string);
	while ($i<$len) {
		switch($string[$i]) { # What car is it in the buffer ?
		case "\"": #"   # a quote.
		if ($quote==0) {
			$quote=1;
		} else {
			$quote=0;
			if (($attrib>0) && (!empty($tmp))) { $result .= "=\"".$tmp."\""; }
			$tmp="";
			$attrib=-1;
		}
		break;
		case "=":   # an equal - attrib delimiter
		if ($quote==0) {  # Is it found in a string ?
		$attrib=1;
		if ($tmp!="") $result.=" $tmp";
		$tmp="";
		} else $tmp .= '=';
		break;
		case " ":   # a blank ?
		if ($attrib>0) {  # add it to the string, if one opened.
		$tmp .= $string[$i];
		}
		break;
		default:  # Other
		if ($attrib<0)  # If we weren't in an attrib, set attrib to 0
		$attrib=0;
		$tmp .= $string[$i];
		break;
		}
		$i++;
	}
	if (($quote!=0) && (!empty($tmp))) {
		if ($attrib==1) $result .= "=";
		/* If it is the value of an atrib, add the '=' */
		$result .= "\"".$tmp."\"";  /* Add quote if needed (the reason of the function ;-) */
	}
	return $result;
}
/////////////////////////////////////////////////////////
function validate_mail($email) { // проверить вызов
  if(strlen($email) < 7 || !preg_match("/^[_\.0-9a-z\-]+@([0-9a-z][0-9a-z\-]+\.)+[a-z]{2,6}$/i",$email)) {
    die("Ошибка в адресе Email. Вернитесь назад и исправьте.");
  } else {
    return $email;
  }
}
/////////////////////////////////////////////////////////
function check_html($str, $strip="") {
  global $AllowableHTML;
  // Этот код — из phpslash (GPL)
  if ($strip == "nohtml") $AllowableHTMLnow = array('');
  else $AllowableHTMLnow = $AllowableHTML;
  $str = preg_replace("/<\s*([^>]*?)\s*>/i",'<\\1>',$str); // Удаляем все пробелы из html тегов.
  $str = preg_replace("/<a[^>]*href\s*=\s*\"?\s*([^\" >]*)\s*\"?[^>]*>/i",'<a href="\\1">', $str); // Удаляем все атрибуты ссылки, кроме href
  $str = preg_replace("/<\s*img\s*([^>]*)\s*>/i", '', $str); // Удаляем img
  $str = preg_replace("/<a[^>]*?href\s*=\s*\"?javascript[[:punct:]]*\"?[^>]*>/i", '', $str); // Удаляем JS из «a href tags»
  $tmp = "";
  while (preg_match("/<(\/?[a-zA-Z0-9]*)\s*([^>]*)>/",$str,$reg)) {
    $i = strpos($str,$reg[0]);
    $l = strlen($reg[0]);
    if ($reg[1][0] == "/") $tag = strtolower(substr($reg[1],1));
    else $tag = strtolower($reg[1]);
    if ($a = (isset($AllowableHTMLnow[$tag])) ? $AllowableHTMLnow[$tag] : 0)
    if ($reg[1][0] == "/") $tag = "</".$tag.">";
    elseif (($a == 1) || (empty($reg[2]))) $tag = "<".$tag.">";
    else {
      # недостает функции поправки двойных кавычек
      $attrb_list=delQuotes($reg[2]);
      $tag = "<".$tag.$attrb_list.">";
    } # атрибуты в тегах разрешить
    else $tag = "";
    $tmp .= substr($str,0,$i) . $tag;
    $str = substr($str,$i+$l);
  }
  $str = $tmp . $str;
  return $str;
  exit;
  $str = str_replace("<?","",$str);
  return $str;
}
/////////////////////////////////////////////////////////
function filter($what, $strip="") {
	if ($strip == "nohtml") {
	 $what = trim( check_html( $what, $strip ) );
	}
	$what = stripslashes(FixQuotes($what));
	return($what);
}
/////////////////////////////////////////////////////////
if (isset($gf)){ // удалить
  switch($gf) {
  	case "gf":
  	$datekey = date("F j");
  	$rcode = hexdec(md5($_SERVER['HTTP_USER_AGENT'] . $sitekey . intval($random) . $datekey));
  	$code = substr($rcode, 2, 6);
  	$image = ImageCreateFromJPEG("images/code_bg.jpg");
  	$text_color = ImageColorAllocate($image, 80, 80, 80);
  	Header("Content-type: image/jpeg");
  	ImageString ($image, 5, 12, 2, $code, $text_color);
  	ImageJPEG($image, '', 75);
  	ImageDestroy($image);
  	die('main2');
  	break;
  }
}
/////////////////////////////////////////////////////////
function getuseragent() { // удалить
  return htmlspecialchars($_SERVER["HTTP_USER_AGENT"]);
}
/////////////////////////////////////////////////////////
function getip() { // Получаем IP-адрес
  if (getenv('REMOTE_ADDR'))  {
    $user_ip = getenv('REMOTE_ADDR');
  }
  elseif (getenv('HTTP_FORWARDED_FOR')) {
    $user_ip = getenv('HTTP_FORWARDED_FOR');
  }
  elseif (getenv('HTTP_X_FORWARDED_FOR')) {
    $user_ip = getenv('HTTP_X_FORWARDED_FOR');
  }
  elseif (getenv('HTTP_X_COMING_FROM'))  {
    $user_ip = getenv('HTTP_X_COMING_FROM');
  }
  elseif (getenv('HTTP_VIA')) {
    $user_ip = getenv('HTTP_VIA');
  }
  elseif (getenv('HTTP_XROXY_CONNECTION')) {
    $user_ip = getenv('HTTP_XROXY_CONNECTION');
  }
  elseif (getenv('HTTP_CLIENT_IP'))  {
    $user_ip = getenv('HTTP_CLIENT_IP');
  }
  else {
    $user_ip='unknown';
  }
  if (15<strlen($user_ip)) {
    $ar = explode (', ', $user_ip);
    $so=sizeof($ar)-1;
    for ($i=$so; $i>0; $i--) {
      if ($ar[$i]!='' and !preg_match ('/[a-zA-Zа-яА-Я]/', $ar[$i])) {
        $user_ip = $ar[$i];
        break;
      }
      if ($i==$so) {
        $user_ip = 'unknown';
      }
    }
  }
  if (preg_match ('/[^0-9\.]/', $user_ip)) {
    $user_ip = 'unknown';
  }
  return $user_ip;
}
/////////////////////////////////////////////////////////
function findMonthName($m) { # ИМЯ МЕСЯЦА 
  // Функция определения имени месяца по его числу
  $m=intval($m); $month=array(); $month["1"]="января"; $month["2"]="февраля"; $month["3"]="марта"; $month["4"]="апреля"; $month["5"]="мая"; $month["6"]="июня"; $month["7"]="июля"; $month["8"]="августа"; $month["9"]="сентября"; $month["10"]="октября"; $month["11"]="ноября"; $month["12"]="декабря"; return $month[$m];
  }
  function findMonthNameEng($m) {
  // Функция определения англ. имени месяца по его числу
  $m=intval($m); $month=array(); $month["1"]="Jan"; $month["2"]="Feb"; $month["3"]="Mar"; $month["4"]="Apr"; $month["5"]="May"; $month["6"]="Jun"; $month["7"]="Jul"; $month["8"]="Aug"; $month["9"]="Sen"; $month["10"]="Oct"; $month["11"]="Nov"; $month["12"]="Dec"; return $month[$m];
  }
///////////////////////////////////////////////////////////////
function tipograf($text, $p=0) { // типографика - все основные знаки препинания

  if ($p==0) $text = "<p>".trim($text)."</p>";
  // Смайлы (с легкостью можно добавить замену смайлов на картинки!)
  $text=str_replace(":)", "<img src=/images/smilies/04.gif>", $text);
  $text=str_replace(":(", "<img src=/images/smilies/11.gif>", $text);

  $zamena = array(
  "<div><br /> 
  </div>"=>"<br>",
  "spaw2/empty/"=>"",
  "\"/>"=>"\">",
  "MsoNormalTable"=>"table_light",
  " class=\"Apple-style-span\""=>"",
  "st1:metricconverter"=>"span",
  " w:st=\"on\""=>"",
  "&nbsp;"=>" ",
  "<B"=>"<b",
  "<I"=>"<i","<BR"=>"<br",
  "<P"=>"<p","</B>"=>"</b>",
  "</I>"=>"</i>","</P>"=>"</p>",
  "<HR"=>"<hr","SPAN"=>"span",
  " class=\"Apple-style-span\""=>"",
  " style=\"font-size: 11px; \""=>"",
  "<b> </b>"=>"","<i> </i>"=>"",
  "<br></b>"=>"</b><br>","<br></i>"=>"</i><br>",
  "<p>\t</p>"=>"","<p> </p>"=>"",
  "<p>\r\n</p>"=>"","\r\n"=>" ",
  "\t"=>" ","На "=>"На&nbsp;",
  "<p></p>"=>" ",
  "alt=\"\""=>"","class=\"\""=>"","style=\"\""=>"","height=\"\""=>"","width=\"\""=>"",
  "</p></p>"=>"</p>","<p> <p>"=>"<p>",
  "<p><p>"=>"<p>","То "=>"То&nbsp;",
  "</p> </p>"=>"</p>","</p>&nbsp;</p>"=>"</p>",
  "т. д."=>"т.д.","т. к."=>"т.к.",
  "- "=>"—&nbsp;","&mdash; "=>"—&nbsp;",
  "-&nbsp;"=>"—&nbsp;","&mdash;&nbsp;"=>"—&nbsp;",
  "( "=>"("," )"=>")",
  " %"=>"% "," ;"=>"; ",
  " !"=>"!"," ?"=>"?",
  " :"=>":"," ."=>".",
  " ,"=>", ",
  "..."=>"…","&hellip;"=>"…",
  "FONT-WEIGHT"=>"font-weight","FONT-STYLE"=>"font-style",
  "<p> <br><br> <p>"=>"<p>","<br><br></p>"=>"</p>",
  "<b>"=>"<strong>","</b>"=>"</strong>",
  "<i>"=>"<em>","</i>"=>"</em>",
  "<div><hr>"=>"<hr>", "<p>&nbsp;</p>"=>"<br><br>","<p>&nbsp;"=>"<p>",
  "<div><em>"=>"<em>","</em> 
  </div>"=>"</em>",
  "</em><br> <em>"=>"<br>","</strong><br> <strong>"=>"<br>",
  "<p>&nbsp;</p> \n<ul>"=>"<ul>","</ul> \n<p>&nbsp;</p>"=>"</ul>",
  "<br/></strong></em></p>"=>"</strong></em></p>",
  "<br/></strong></p>"=>"</strong></p>",
  "<br/></p>"=>"</p>",
  "    "=>" ","   "=>" ","  "=>" ","  "=>" "
  );
  $text = strtr( strtr($text, $zamena), $zamena);

  $pattern = "/http:\/\/www.onlinedisk.ru\/image\/"."(\d+)"."\/"."(\w+)".".jpg"."/i";
  $replacement = "http://www.onlinedisk.ru/get_image.php?id=$1";
  $text =  preg_replace($pattern, $replacement, $text);

  // Кавычки! («ёлочки» - &laquo; и &raquo или „лапки“ - &#132; и &#147;)
  //$text = preg_replace('/(^|\s)"(\S)/', '$1&laquo;$2', $text);
  //$text = preg_replace('/(\S)"([ .,?!])/', '$1&raquo;$2', $text);

  $zamena = array(
  " а "=>" а&nbsp;","А "=>"А&nbsp;",
  " в "=>" в&nbsp;","В "=>"В&nbsp;",
  " и "=>" и&nbsp;","И "=>"И&nbsp;",
  " к "=>" к&nbsp;","К "=>"К&nbsp;",
  " о "=>" о&nbsp;","О "=>"О&nbsp;",
  " с "=>" с&nbsp;","С "=>"С&nbsp;",
  " у "=>" у&nbsp;","У "=>"У&nbsp;",
  " я "=>" я&nbsp;","Я "=>"Я&nbsp;",
  " во "=>" во&nbsp;","Во "=>"Во&nbsp;",
  " до "=>" до&nbsp;","До "=>"До&nbsp;",
  " за "=>" за&nbsp;","За "=>"За&nbsp;",
  " из "=>" из&nbsp;","Из "=>"Из&nbsp;",
  " на "=>" на&nbsp;","На "=>"На&nbsp;",
  " не "=>" не&nbsp;","Не "=>"Не&nbsp;",
  " ни "=>" ни&nbsp;","Ни "=>"Ни&nbsp;",
  " но "=>" но&nbsp;","Но "=>"Но&nbsp;",
  " об "=>" об&nbsp;","Об "=>"Об&nbsp;",
  " то "=>" то&nbsp;","То "=>"То&nbsp;",
  " для "=>" для&nbsp;","Для "=>"Для&nbsp;",
  " или "=>" или&nbsp;","Или "=>"Или&nbsp;",
  " над "=>" над&nbsp;","Над "=>"Над&nbsp;",
  " обо "=>" обо&nbsp;","Обо "=>"Обо&nbsp;",
  " про "=>" про&nbsp;","Про "=>"Про&nbsp;",
  " около "=>" около&nbsp;","Около "=>"Около&nbsp;",
  " перед "=>" перед&nbsp;","Перед "=>"Перед&nbsp;",
  " после "=>" после&nbsp;","После "=>"После&nbsp;",
  " против "=>" против&nbsp;","Против "=>"Против&nbsp;",
  " напротив "=>" напротив&nbsp;","Напротив "=>"Напротив&nbsp;",
  " же "=>"&nbsp;же ","кое-как"=>"<nobr>кое-как</nobr>"
  );
  if ($p!=2) $text = strtr($text,$zamena);
  return $text;
}
///////////////////////////////////////////////////////////////
function zamena_predlog($text) { # Замена предлогов
  $zamena = array(" а "=>" ","А "=>""," в "=>" ","В "=>""," и "=>" ","И "=>""," к "=>" ","К "=>""," о "=>" ","О "=>""," с "=>" ","С "=>""," у "=>" ","У "=>""," я "=>" ","Я "=>""," во "=>" ","Во "=>""," до "=>" ","До "=>""," за "=>" ","За "=>""," из "=>" ","Из "=>""," на "=>" ","На "=>""," не "=>" ","Не "=>""," ни "=>" ","Ни "=>""," но "=>" ","Но "=>""," по "=>" ","По "=>""," об "=>" ","Об "=>""," то "=>" ","То "=>""," для "=>" ","Для "=>""," или "=>" ","Или "=>""," над "=>" ","Над "=>""," обо "=>" ","Обо "=>""," про "=>" ","Про "=>""," же "=>" ");
  $text = strtr($text,$zamena);
  return $text;
}
///////////////////////////////////////////////////////////////
function switch_type($type,$name,$useit,$useit_module=0) { // убрать !!!
  global $tip, $admintip, $prefix, $db, $spaw_show;
  $spaw_show = 1;
  $title_razdel="";
  switch ($type) {
  case "0": $type = "Дизайн"; 		$color = "#FFCC99";	$icon = "|design_editor|design_noeditor|4|5|design_delete"; break;
  case "1": $type = "Стиль&nbsp;CSS"; 	$color = "#FFFFCC";	$icon = "|style_noeditor|3|4|5|style_delete"; break;
  case "2": $type = "Раздел $name"; 	$color = "#90ee90";	$icon = "|pages_editor|pages_noeditor|pages_show|pages_allpages|pages_delete|pages_allpages_time|pages_allpages_base|pages_allpages_nastroi"; break;
  case "3": $type = "Блок"; 		$color = "#8ee5ee";	$icon = "|blocks_editor|blocks_noeditor|blocks_nastroi|5|blocks_delete";
  	switch ($name) {
  	case "0": #################################################
    if ($useit_module=="") $type = "Страницы всех разделов"; else 
  	$type = "Страницы&nbsp;раздела \"".$useit_module."\""; $spaw_show = 0; break;
  	case "1": #################################################
  	if ($useit_module=="") $type = "Комментарии всех разделов"; else 
    $type = "Комментарии&nbsp;раздела \"".$useit_module."\""; $spaw_show = 0; break;
  	case "2": #################################################
  	$type = "Текст&nbsp;или&nbsp;HTML"; break;
  	case "3": #################################################
  	$type = "Ротатор (при&nbsp;обновлении страницы)"; $spaw_show = 0; break;
  	case "4": #################################################
  	$type = "Папки&nbsp;раздела"; $spaw_show = 0; break;
  	case "5": #################################################
  	$type = "Голосование&nbsp;(Опрос)"; $spaw_show = 0; break;
  	case "6": #################################################
  	$type = "Фотогалерея"; $spaw_show = 0; break;
  	case "7": #################################################
  	$type = "PHP-код"; $spaw_show = 0; break;
  	case "8": #################################################
  	$type = "Папки&nbsp;открытого&nbsp;раздела"; $spaw_show = 0; break;
  	case "9": #################################################
  	$sql = "select title, useit from ".$prefix."_mainpage where id='$useit'";
  	$result = $db->sql_query($sql);
  	$row = $db->sql_fetchrow($result);
  	$title = $row['title'];
  	$type = "Мини-фото&nbsp;раздела \"".$title."\""; $spaw_show = 0; break;
  	case "10": #################################################
  	$type = "Меню&nbsp;сайта"; $spaw_show = 0; break;
  	case "11": #################################################
  	$type = "Календарь"; $spaw_show = 0; break;
  	case "13": #################################################
  	$type = "Облако&nbsp;тегов (ключевых&nbsp;слов)"; $spaw_show = 0; break;
  	case "20": #################################################
  	$type = "База&nbsp;данных (количество по&nbsp;1&nbsp;колонке верт.)"; $spaw_show = 0; break;
  	case "21": #################################################
  	$type = "База&nbsp;данных (количество по&nbsp;1&nbsp;колонке гор.)"; $spaw_show = 0; break;
  	case "22": #################################################
  	$type = "База&nbsp;данных (количество по&nbsp;2&nbsp;колонкам)"; $spaw_show = 0; break;
  	case "23": #################################################
  	$type = "База&nbsp;данных (список колонок)"; $spaw_show = 0; break;
    case "30": #################################################
    $type = "Кол-во&nbsp;посещений&nbsp;раздела"; $spaw_show = 0; break;
    case "31": #################################################
    $type = "JavaScript"; $spaw_show = 0; break;
  	} #########################################################
  	$name = ""; 
  	//$title = "[".$title."]";
  break;
  case "4": $type = "Список"; 		$color = "#99CCFF";	$icon = "|spisok_editor|3|4|spisok_allpages|spisok_delete"; break;
  case "5": $type = "База данных"; 	$color = "#f9a3f6";	$icon = "|base_editor|3|4|base_allpages|base_delete"; break;
  case "6": $type = "Шаблон для раздела или блока"; $color = "#dddddd";	$icon = "|spisok_editor|spisok_noeditor|4|spisok_allpages|spisok_delete"; break;
  }
  return $type."|".$color.$icon;
}
///////////////////////////////////////////////////////////////
function strtolow($txt, $t=1) { # Большие буквы в маленькие
  $from   = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЬЫЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $to     = 'абвгдеёжзийклмнопрстуфхцчшщъьыэюяabcdefghijklmnopqrstuvwxyz';
  if ($t==1) $txt = strtr($txt, $from, $to); elseif ($t==0) $txt = strtr($txt, $to, $from);
  return $txt;
}
///////////////////////////////////////////////////////////////
function translit($cyr_str) { # Транслит
  $tr = array(
   "Ґ"=>"G","Ё"=>"YO","Є"=>"E","Ї"=>"YI","І"=>"I","і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"#","є"=>"e",
   "ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I",
   "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
   "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH","Щ"=>"SCH","Ъ"=>"'","Ы"=>"YI","Ь"=>"",
   "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"'",
   "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya","«"=>"","»"=>""
  );
   return strtr($cyr_str,$tr);
}
///////////////////////////////////////////////////////////////
function translit_name($cyr_str) { # Транслит названий файлов
  $tr = array(
   "Ґ"=>"G","Ё"=>"YO","Є"=>"E","Ї"=>"YI","І"=>"I","і"=>"i","ґ"=>"g","ё"=>"yo","№"=>"","є"=>"e",
   "ї"=>"yi","А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I",
   "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
   "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH","Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
   "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"",
   "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
   "«"=>"","»"=>"","."=>"",","=>"","!"=>"",":"=>"",";"=>"","?"=>""," "=>"_"
  );
   return strtr($cyr_str,$tr);
  }
///////////////////////////////////////////////////////////////
function dateresize($dat) { // Функция для приведения даты в формат кол-ва дней (примерно)
  if ($dat=="") return 0;
  $dat = str_replace("-",".",$dat);
  $dat=explode(" ", $dat);
  $dat=explode(".", $dat[0]);
  $y = $dat[0] * 365;
  $m = $dat[1] * 30;
  $d = $dat[2];
  return $y+$m+$d;
}
///////////////////////////////////////////////////////////////
function date2normal_view($dat, $r=0, $t=0, $eng=0) { // Функция для приведения даты из формата 2012-02-20 в формат 20 Февраля 2012
  // $t = 1 - выводить также и время
  // $r = 2 - формат + Сегодня/Завтра/Послезавтра/Вчера/Позавчера
  if ($eng == 0) $months = array("?", "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
  else $months = array("?", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  $year = date("Y");
  if ($t != 0) {
    $dat = explode(" ", trim($dat));
    $time = explode(":",$dat[1]);
    $time = " в ".intval($time[0]).":".$time[1];
    $dat = $dat[0];
  }
  if ($r == 0 or $r == 2) { // если r=0 - стандартная функция
    // Функция для приведения даты из формата 2012-02-20 в формат 20 Февраля 2012
    $dat = explode("-", trim($dat));
    $y = $dat[0];
    $m = $dat[1];
    $d = $dat[2];
    $m2 = intval($m);
    $data = intval($d)." ".$months[$m2]." ".intval($y);
    //if ($y == 1) $data = str_replace(" ".$year, "", $data);
    if ($r == 2) { // Функция для приведения даты из формата 2012-02-20 в формат 20 Февраля 2012 + Сегодня/Вчера
      // доработать - настройка.
      /*
      $datax1_1 = $d." ".$m." ".$y;
      $date_now = date("d m Y");
      $date_now2 = date("d m Y",time()-86400);
      $date_now3 = date("d m Y",time()-172800);
      $date_now4 = date("d m Y",time()+86400);
      $date_now5 = date("d m Y",time()+172800);
      if ($date_now == $datax1_1) $data = "сегодня";
      if ($date_now2 == $datax1_1) $data = "вчера";
      if ($date_now3 == $datax1_1) $data = "позавчера";
      if ($date_now4 == $datax1_1) $data = "завтра";
      if ($date_now5 == $datax1_1) $data = "послезавтра";
      */
      $data = str_replace(" ".$year, "", $data);
    }
    if ($t != 0) $data .= $time;
    return $data;
  } elseif ($r == 1) { // Функция для приведения даты из формата 20 Февраля 2012 в формат 2012-02-20
    $dat = explode(" ", trim($dat));
    $y = $dat[2];
    $m = array_search(trim($dat[1]),$months); if ($m<10) $m="0".$m;
    $d = intval($dat[0]); if ($d<10) $d="0".$d;
    return $y."-".$m."-".$d;
  } 
}
///////////////////////////////////////////////////////////////
function period($dat1, $dat2) { // Функция вывода всех дат между двумя датами (период времени)
  // Формат входной даты: две даты типа 2009-12-31 (соответствует хранению в БД)
  // Формат выходной даты: массив дат типа 2009-12-31 (соответствует хранению в БД)
  // легко можно сделать и 31.12.2009 (соответствует адекватному восприятию)
  $date = explode('-', $dat1);
  $begin_year = $date[0];
  $begin_month = $date[1];
  $begin_day = $date[2];
  $date = explode('-', $dat2);
  $end_year = $date[0];
  $end_month = $date[1];
  $end_day = $date[2];
  $period = array();
  for ($cur_year = $begin_year; $cur_year <= $end_year; $cur_year++) {
      if ($cur_year == $end_year) $max_month = $end_month;
      else $max_month = 12;
      if ($cur_year == $begin_year) $cur_month = $begin_month;
      else $cur_month = 1;
      for ($cur_month; $cur_month <= $max_month; $cur_month++) {
      $cur_month = intval($cur_month);
   	if ($cur_month == $end_month) $max_day = $end_day;
   	else $max_day = cal_days_in_month(CAL_GREGORIAN, intval($cur_month), $cur_year);
   	if ($cur_month == $begin_month) $cur_day = $begin_day;
   	else $cur_day = 1;
      	for ($cur_day; $cur_day <= $max_day; $cur_day++) {
      	$cur_day = intval($cur_day);
          	$period[] = $cur_year.'-'.($cur_month < 10 ? '0'.$cur_month : $cur_month).'-'.($cur_day < 10 ? '0'.$cur_day : $cur_day);
          }
      }
  }
  return $period;
}
///////////////////////////////////////////////////////////////
function my_calendar($fill='', $modul, $showdate='') { // Функция вывода календаря
  $calendar = "";
  // Вычисляем число дней в текущем месяце
  $dayofmonth = date('t');
  $daynow = date('d');
  $monthandyear =date('Y-m-');
  // Счётчик для дней месяца
  $day_count = 1;
  // 1. Первая неделя
  $num = 0;
  for($i = 0; $i < 7; $i++) {
    // Вычисляем номер дня недели для числа
    $dayofweek = date('w', mktime(0, 0, 0, date('m'), $day_count, date('Y')));
    // Приводим к числа к формату 1 - понедельник, ..., 6 - суббота
    $dayofweek = $dayofweek - 1;
    if($dayofweek == -1) $dayofweek = 6;

    if($dayofweek == $i) {
      // Если дни недели совпадают, заполняем массив $week числами месяца
      $week[$num][$i] = $day_count;
      $day_count++;
    } else {
      $week[$num][$i] = "";
    }
  }
  // 2. Последующие недели месяца
  while(true) {
    $num++;
    for($i = 0; $i < 7; $i++) {
      $week[$num][$i] = $day_count;
      $day_count++;
      // Если достигли конца месяца - выходим из цикла
      if($day_count > $dayofmonth) break;
    }
    // Если достигли конца месяца - выходим из цикла
    if($day_count > $dayofmonth) break;
  }
  // Выводим содержимое массива $week в виде календаря. Выводим таблицу
  $calendar .= "<table cellspacing=0 cellpadding=0 border=0>";
  for($j = 0; $j < 7; $j++) {
    $calendar .= "<tr align=center>";
    for($i = 0; $i < count($week); $i++) {
      if(!empty($week[$i][$j])) {
        // Если имеем дело с субботой и воскресеньем — подсвечиваем их
        $class = "";
        if ($week[$i][$j]<10) $den = "0".$week[$i][$j]; else $den = $week[$i][$j];
        
        if ($week[$i][$j] == $daynow) $class = "bold";
        if ($monthandyear.(intval($week[$i][$j]) < 10 ? '0'.intval($week[$i][$j]) : $week[$i][$j]) == $showdate) $class = "select";
        if($j == 5 || $j == 6) $class .= " red"; else $class .= " black";
        if (in_array($monthandyear.$den,$fill)) $calendar .= "<td class='calendar ".trim($class)."'><a href=/-".$modul."_date_".$monthandyear.(intval($week[$i][$j]) < 10 ? '0'.intval($week[$i][$j]) : $week[$i][$j]).">".$week[$i][$j]."</a></td>";
        else $calendar .= "<td class='calendar ".trim($class)."'>".$week[$i][$j]."</td>";
      } else $calendar .= "<td>&nbsp;</td>";
    }
    $calendar .= "</tr>";
  } 
  $calendar .= "</table><font class='small black'>Сегодня: ".date2normal_view($monthandyear.$daynow)."</font>";
  if (trim($showdate) != "0-00-00" and trim($showdate) != "" and $showdate != $monthandyear.$daynow) $calendar .= "<br><font class='small red'>Выбрано: ".date2normal_view($showdate)."</font>";
  return $calendar;
}
////////////////////////////////////////////////////////////
function select($name,$vars,$vars_name,$znachenie) { // генерация SELECT элемента формы
  $return = "<select name=\"".$name."\">";
  $vars = explode(",",$vars);
  $vars_name = explode(",",$vars_name);
  $vybor = false; // если выбор не сделан - напишем этот невыбранный вариант!
  	foreach ($vars as $key => $var) {
  	$var = str_replace("[|]",",",$var);
  	if ($znachenie == $var) { $vybor = true; $sel=" selected style='background:#dddddd;'"; } else $sel="";
    $vars_name[$key] = str_replace("[|]",",",$vars_name[$key]);
  	$return .= "<option value='".$var."'".$sel.">".$vars_name[$key]."</option>";
  	}
  if ($vybor == false) {
  $return .= "<option value='".$znachenie."' selected style='background:#dddddd;'>".$znachenie."</option>";
  }
  $return .= "</select>";
  return $return;
}
/////////////////////////////////////////////////////////////
function input($name,$znachenie,$size="40",$type="text") { // генерация INPUT элемента формы
  if ($type=="txt") 
    return "<textarea name=\"".$name."\" rows=3 cols=80 style='width:100%; height:".$size."px;'>".$znachenie."</textarea>";
  else
    return "<input type=\"".$type."\" name=\"".$name."\" value=\"".$znachenie."\" size=\"".$size."\">";
}
///////////////////////////////////////////////////////////////
function smile_generate($smiles, $folder="") { // генерация полоски смайлов
  $smile = "";
  if ($folder != "") $folder = $folder."/";
  foreach ($smiles as $sm) { $smile .= "<img src=\"/images/smilies/".$folder.$sm.".gif\" onClick=\"clc_name(' **".$sm."');\"> "; }
  return $smile;
}
/////////////////////////////////////////////////////////////// доработать RSS
/*
function my_headlines($hid, $url=0) { // Заголовки других rss
    global $soderganie, $prefix, $db;
    $sql = "SELECT headlinesurl FROM ".$prefix."_headlines WHERE hid='$hid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $url = $row[headlinesurl];
    $rdf = parse_url($url);
    $fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
    if (!$fp) {
        $content = "<font class=\"content\">Problema!</font>";
        return;
    }
    if ($fp) {
        fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTTP/1.0\r\n");
        fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
        $string        = "";
        while(!feof($fp)) {
            $pagetext = fgets($fp,300);
            $string .= chop($pagetext);
        }
        fputs($fp,"Connection: close\r\n\r\n");
        fclose($fp);
        $items = explode("</item>",$string);
        $content = "<font class=\"content\">";
        for ($i=0;$i<10;$i++) {
            $link = ereg_replace(".*<link>","",$items[$i]);
            $link = ereg_replace("</link>.*","",$link);
            $title2 = ereg_replace(".*<title>","",$items[$i]);
            $title2 = ereg_replace("</title>.*","",$title2);
            if ($items[$i] == "") {
                $content = "";
                return;
            } else {
                if (strcmp($link,$title)) {
                        $cont = 1;
                    $content .= "<img src=\"images/arrow.gif\" border=\"0\" hspace=\"5\"><a href=\"".$link."\" target=\"new\">$title2</a><br>\n";
                }
            }
        }
    }
    $soderganie .= $content;
}
*/
///////////////////////////////////////////////////////////////
function del_spiski($page_id) {
  global $prefix, $db;
  // Стираем любые упоминания о странице во всех элементах списка
  $sql = "SELECT id, pages FROM ".$prefix."_spiski WHERE pages like '% $page_id %'";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $del_id = $row['id'];
    $del_pages = " ".trim(str_replace("  "," ",str_replace(" $page_id "," ",$row['pages'])))." ";
    if (trim($del_pages)=="") $del_pages=""; // Стираем пустые страницы
    $db->sql_query("UPDATE ".$prefix."_spiski SET pages='$del_pages' WHERE id='$del_id'") or die('Ошибка: Не удалось стереть всю ранее введенную в списки информацию.');
  }
}
///////////////////////////////////////////////////////////////
function WhatArrayElement($array, $value, $keys=0) { 
  // Редкая функция для поиска индекса массива по значению.
  if ($keys==0) {
    while (($key = array_search($value, $array)) !== FALSE)
      return $key;
  } else {
    $keys = array();
    while (($key = array_search($value, $array)) !== FALSE and !in_array($key,$keys)) { 
      $keys[] = $key; $key = false;
    }
    return $keys;
  }
}
///////////////////////////////////////////////////////////////
// Функция для форматирования текста страниц
function form($module, $text, $type="open") { 
  // type: open, main
  global $db, $prefix;
  $result8 = $db->sql_query("select text from ".$prefix."_mainpage where type='2' and name='".$module."'");
  $row8 = $db->sql_fetchrow($result8);
  $text2 = $row8['text'];

  if (strpos($text2,"tipograf=0") < 1) { // Типограф
    $text = tipograf($text, 1);
  }

  if (strpos($text2,"no_html_in_opentext=1") > 1 and $type=="open") strip_tags($text, '<a><img>'); // Удаление HTML из предисловия
  if (strpos($text2,"no_html_in_text=1") > 1 and $type=="main") strip_tags($text, '<a><img>'); // Удаление HTML из предисловия

  if (strpos($text2,"table_light=1") > 0 and $type=="main") // Добавление класса table_light
    $text = str_replace("<table", "<table class='table_light'", $text);

  if (get_magic_quotes_gpc($text)) $text = stripslashes($text);
  $text = filter($text);

  return $text;
}
///////////////////////////////////////////////////////////////
function block_names() {  // проверить дубликат
  // Функция для получения названий всех блоков (для дизайна и шаблонов)
  global $db, $prefix;
  $blocks = "";
  $sql3 = "select title from ".$prefix."_mainpage where type='3'";
  $result3 = $db->sql_query($sql3);
  while ($row3 = $db->sql_fetchrow($result3)) {
    $title_modul = trim($row3['title']);
    $blocks .= "[".$title_modul."] ";
  }
  return $blocks;
}
///////////////////////////////////////////////////////////////
function generate_password($number) { // Генерируем пароль
    $arr = array('1','2','3','4','5','6','7','8','9');
    $pass = ""; 
    for($i = 0; $i < $number; $i++) {
      $index = rand(0, count($arr) - 1);
      $pass .= $arr[$index];
    }
    return $pass;
  }
///////////////////////////////////////////////////////////////
function upload_foto_file($text){ // доработать
  return "<br><a onclick=\"show('upload_file'); hide('upload_foto');\" style='cursor:pointer;'><u>Загрузка фото или других файлов</u></a><br><div id='upload_file' style='display:none;'><div class=block2>
  <form action='http://www.onlinedisk.ru/upload/' method='POST' enctype='multipart/form-data' id='upload_file' target=_blank><b>Выберите файл:</b><br><input name=\"file\" type=\"file\" size=50><input type=\"submit\" value=\"Загрузить\"><input type=hidden name=MAX_FILE_SIZE value=30000> 
  </form><b>На открывшейся странице</b> скопируйте текст в поле под словом «Для форумов» и вставьте ниже ".$text.".</div></div><br><br>";
}
///////////////////////////////////////////////////////////////
function recash($url, $main=1) { // Обновление кеша
  global $db, $prefix;
  if ($main == 1) $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url`='/'");
  if (strpos($url,"_page_")) {
    $u = explode("_page_",$url);
    $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url`='".$u[0]."'");
    $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url` LIKE '".$u[0]."_cat_%'");
  }
  $db->sql_query("DELETE FROM ".$prefix."_cash WHERE `url`='".$url."'"); 
}
///////////////////////////////////////////////////////////////
function obrez($WORD) { // Функция обрезания окончаний
  $RESULT = ''; $MAKE = 0;
  $CLOSES = array('овая','овый','ная','ной','ный','ый','ий','ой','овое','ов','ах','ав','ях','ёвое','евое','ое','ям','ом','ем','ей','ёй','ай','ец'); //Окончания
  foreach ($CLOSES AS $PART)
    if (preg_match('/(.*)'.$PART.'/', $WORD)) $WORD = substr($WORD,0,strlen($WORD)-strlen($PART));
    $CHARS = array('а','е','ё','й','и','о','у','ь','ы','э','ю','я'); //Буквы
    for ($POSITION = strlen($WORD)-1; $POSITION >= 0; $POSITION--) {
      $CHAR = substr($WORD, $POSITION, 1);
      if (!in_array($CHAR,$CHARS)) $MAKE = 1;
      if ($POSITION==2) $MAKE = 1;
      if ($MAKE==1) $RESULT = $CHAR.$RESULT;
    }
  return $RESULT;
}
///////////////////////////////////////////////////////////////
function admin_footer() { // проверить вызов
  if ( stristr($_SERVER['HTTP_USER_AGENT'], 'Firefox') or stristr($_SERVER['HTTP_USER_AGENT'], 'Chrome') or stristr($_SERVER['HTTP_USER_AGENT'], 'Safari') ) {} else echo "<span class='gray noprint'>Для администрирования сайта желательно использовать браузер <a href='https://www.google.com/chrome?hl=ru' target='_blank'>Google Chrome</a>, <a href='http://www.apple.com/ru/safari/' target='_blank'>Apple Safari</a> или <a href='http://www.mozilla.org/ru/firefox/' target='_blank'>Mozilla Firefox</a>. <a href='http://www.whatbrowser.org/ru/' target='_blank'>Что такое браузер?</a></span></div></body>\n</html>";
  ob_end_flush();
  die();
}
///////////////////////////////////////////////////////////////
function num_ending($number, $endings) { // функция правильных окончаний слов
    $num100 = $number % 100;
    $num10 = $number % 10;
    if ($num100 >= 5 && $num100 <= 20) {
        return $endings[0];
    } else if ($num10 == 0) {
        return $endings[0];
    } else if ($num10 == 1) {
        return $endings[1];
    } else if ($num10 >= 2 && $num10 <= 4) {
        return $endings[2];
    } else if ($num10 >= 5 && $num10 <= 9) {
        return $endings[0];
    } else {
        return $endings[2];
    }
}
///////////////////////////////////////////////////////////////
function scandirectory($dir, $echoscan="", $images) {
  global $echoscan;
        $level = substr_count($dir,"/");
        for($line="", $counter=0; $counter<$level; ++$counter, $line.='--'){}
        $dir_res = opendir($dir);
        while($file = readdir($dir_res)) {
                if($file!=='.' && $file!=='..') {
                        if(is_dir($dir."/".$file)) {
                          scandirectory($dir."/".$file, $echoscan);
                        } else {
                          if (!strpos(" ".$dir."/".$file,"spaw2/uploads/images/icons/") and !strpos(" ".$dir."/".$file,"spaw2/uploads/_thumbs") and (strpos($file,"gif") or strpos($file,"png") or strpos($file,"jpg") or strpos($file,"jpeg") or strpos($file,"GIF") or strpos($file,"PNG") or strpos($file,"JPG") or strpos($file,"JPEG") )) $echoscan .= $dir."/".$file."@";
                        }
                }      
        }
  return $echoscan;
}
/////////////////////////////////////////////////////////////// Проверить вызов
function foto_find($content) { // Поиск фотографий на странице
  global $http_siteurl;
  preg_match_all("/(?<=url\()[a-zA-Z.\/]*(?=\))/i", $content, $matches);
  foreach($matches[0] as $item) {
    $item = trim($item);
    if ($item != "") $info[] = $item;
  }
  preg_match_all("#\s(?:href|src|url)=(?:[\"\'])?(.*?)(?:[\"\'])?(?:[\s\>])#i", $content, $matches);
  foreach($matches[0] as $item) { 
    if (strpos(" ".$item,"includes/phpThumb")) {
      $item = explode("&", str_replace("/includes/phpThumb/phpThumb.php?","",$item));
      $item = $item[0];
    }
    $item = str_replace($http_siteurl."/","",$item);
    if (!strpos(" ".$item,"http:") and ( strpos($item,"gif") or strpos($item,"png") or strpos($item,"jpg") or strpos($item,"jpeg") or strpos($item,"GIF") or strpos($item,"PNG") or strpos($item,"JPG") or strpos($item,"JPEG") )) {
      $item = trim(str_replace("uploadsimages","uploads/images",str_replace("/images/","images/",str_replace("/img/","img/",str_replace("/spaw2/","spaw2/",str_replace(".jpg/",".jpg",str_replace(".gif/",".gif",str_replace("\\","",str_replace("'","",str_replace("\"","",str_replace(">","",str_replace("href=","",str_replace("src=","",$item)))))))))))));

      if ($item != "") $info[] = $item;
    }
  }
  return $info;
}
/////////////////////////////////////////////////////////////// Проверить вызов
function show_cids($cid_papka, $cids=array()) { // получим cid вложенных папок
  global $prefix, $db;
  $sql = "SELECT cid from ".$prefix."_pages_categories where parent_id='".$cid_papka."'";
  $result = $db->sql_query($sql);
  if ($db->sql_numrows($result) > 0) {
    while ($row = $db->sql_fetchrow($result)) {
      $cid = $row['cid'];
      $cids[] = $cid;
      $cids += show_cids($cid,$cids);
    }
  }
  return $cids;
}
///////////////////////////////////////////////////////////////
function getparent($name, $parentid, $title) { // получение родительской папки
    global $prefix, $db;
    $sql = "select title, parent_id from ".$prefix."_pages_categories where module='$name' and `tables`='pages' and cid='$parentid' order by cid";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $ptitle = strip_tags($row['title'], '<b><i>');
    $pparentid = $row['parent_id'];
    if ($ptitle!="") 
      $title = $ptitle."/".$title;
    if ($pparentid!=0) {
        $title = getparent($name,$pparentid,$title);
    }
    return $title;
}
///////////////////////////////////////////////////////////////
function getparent_spiski($name, $parent, $title) { // получение родительского списка
    global $tip, $admintip, $prefix,$db;
    $sql = "select name, parent from ".$prefix."_spiski where type='$name' and id='$parent'";
    $result = $db->sql_query($sql) or die('Ошибка: Не далось подключиться к спискам');
    $row = $db->sql_fetchrow($result);
    $ptitle = $row['name'];
    $pparent = $row['parent'];
    if ($ptitle!="") $title=$ptitle."/".$title;
    if ($pparent!=0) {
        $title = getparent_spiski($name, $pparent, $title);
    }
    return $title;
}
/////////////////////////////////////////////////////////////// сделать настройку
function antivirus($x="0") { // антивирус для защиты от htaccess-вируса 
  // открываем .htaccess
  $htaccess = " ".implode(" ", file('.htaccess'));
  // ищем [NC], HTTP_USER_AGENT и (.*)
  if ( strpos($htaccess,"[NC]") or strpos($htaccess,"HTTP_USER_AGENT") or strpos($htaccess,"(.*)") ) {
    // меняем .htaccess на .ht_backup
    global $ht_backup;
    if ( $ht_backup != "" and file_exists($ht_backup) ) {
      unlink('.htaccess');
      copy($ht_backup, '.htaccess');
      // Оповестим админа
      if ($x == "0") $subg = "Найден и обезврежен «.htaccess»-вирус."; 
      elseif ($x == "1") $subg = "Вирус теперь поражает и резервную копию .htaccess — пора залезть в config.php и поменять название файла резервной копии.";
      system_mes($subg);
      if ($x == "0") antivirus("1");
    }
  }
}
///////////////////////////////////////////////////////////////
function system_mes($subg) { // Отправка системного сообщения администратору (в список «Комментарии»)
  global $prefix, $db, $now;
  $db->sql_query("INSERT INTO ".$prefix."_pages_comments ( `cid` , `num` , `avtor` , `mail` , `text` , `ip` , `data`, `drevo`, `adres`, `tel`, `active` ) VALUES ('', '0', 'ДвижОк', '', '".$subg."', '', '$now', '', '', '', '1')");
}
/////////////////////////////////////////////////////////////////
// Функция подсчета входящих в подкатегории страниц
function vhodyagie($id,$par,$num) {
  $num_all = 0;
  foreach ($par as $i1 => $nam1) { // nam - это родственник
    if ($id == $nam1) {
      $x = vhodyagie($i1,$par,$num);
      $num_all = $num_all + $num[$i1] + $x;
    }
  }
  return $num_all;
}
/////////////////////////////////////////////////////////////////
function process_php_code(&$text) { // Выполняем PHP )=
    $cur = 0;
    do{
      $b = strpos($text,'[?',$cur);
      $cur = $b+2;
      $e = strpos($text,'?]',$cur);
      $cur = $e+2;
      if($b===false || $e===false){
       break;
      } else {
       $this->err .= "+++ $b $e <br>";
       $code = substr($text,$b+2,$e-$b-2);
       $this->err .= "PHP code: ".htmlspecialchars($code)." <br>";
       $ret = eval($code);
       $text = substr($text,0,$b).$ret.substr($text,$e+2);
       $cur = 0;
      }
    }while(1);
}
/////////////////////////////////////////////////////////////////
function predlogi($text) {
  $zamena = array(
  " а "=>" а&nbsp;","А "=>"А&nbsp;",
  " в "=>" в&nbsp;","В "=>"В&nbsp;",
  " и "=>" и&nbsp;","И "=>"И&nbsp;",
  " к "=>" к&nbsp;","К "=>"К&nbsp;",
  " о "=>" о&nbsp;","О "=>"О&nbsp;",
  " у "=>" у&nbsp;","У "=>"У&nbsp;",
  " я "=>" я&nbsp;","Я "=>"Я&nbsp;",
  " во "=>" во&nbsp;","Во "=>"Во&nbsp;",
  " до "=>" до&nbsp;","До "=>"До&nbsp;",
  " за "=>" за&nbsp;","За "=>"За&nbsp;",
  " из "=>" из&nbsp;","Из "=>"Из&nbsp;",
  " на "=>" на&nbsp;","На "=>"На&nbsp;",
  " не "=>" не&nbsp;","Не "=>"Не&nbsp;",
  " ни "=>" ни&nbsp;","Ни "=>"Ни&nbsp;",
  " но "=>" но&nbsp;","Но "=>"Но&nbsp;",
  " об "=>" об&nbsp;","Об "=>"Об&nbsp;",
  " то "=>" то&nbsp;","То "=>"То&nbsp;",
  " для "=>" для&nbsp;","Для "=>"Для&nbsp;",
  " или "=>" или&nbsp;","Или "=>"Или&nbsp;",
  " над "=>" над&nbsp;","Над "=>"Над&nbsp;",
  " обо "=>" обо&nbsp;","Обо "=>"Обо&nbsp;",
  " про "=>" про&nbsp;","Про "=>"Про&nbsp;",
  " около "=>" около&nbsp;","Около "=>"Около&nbsp;",
  " перед "=>" перед&nbsp;","Перед "=>"Перед&nbsp;",
  " после "=>" после&nbsp;","После "=>"После&nbsp;",
  " против "=>" против&nbsp;","Против "=>"Против&nbsp;",
  " напротив "=>" напротив&nbsp;","Напротив "=>"Напротив&nbsp;",
  " же "=>"&nbsp;же "
  );
  $text = strtr($text,$zamena);
  $text = str_replace(" - "," &mdash; ",$text); // Тире и дефиз
  return $text;
}
/////////////////////////////////////////////////////////////////
function time_otschet($tim, $txt, $do) {
  return "<script language='javascript'> 
  function fulltime ()   { 
  var time=new Date(); 
  var newYear=new Date(\"".$tim."\"); 
  var totalRemains=(newYear.getTime()-time.getTime()); 

  var RemainsSec = (parseInt(totalRemains/1000));
  var RemainsFullDays=(parseInt(RemainsSec/(24*60*60)));
  var secInLastDay=RemainsSec-RemainsFullDays*24*3600; 
  var RemainsFullHours=(parseInt(secInLastDay/3600)); 
  if (RemainsFullHours<10){RemainsFullHours=\"0\"+RemainsFullHours}; 
  var secInLastHour=secInLastDay-RemainsFullHours*3600;
  var RemainsMinutes=(parseInt(secInLastHour/60));
  if (RemainsMinutes<10){RemainsMinutes=\"0\"+RemainsMinutes}; 
  var lastSec=secInLastHour-RemainsMinutes*60;
  if (lastSec<10){lastSec=\"0\"+lastSec}; 

  document.getElementById(\"RemainsFullDays\").innerHTML=\"<b style='font-size:1.3em;'>\"+RemainsFullDays+\"</b><span id='Rem'> дн</span>\"; 
  document.getElementById(\"RemainsFullHours\").innerHTML=\"<b>\"+RemainsFullHours+\"</b><span id='Rem'> ч</span>\"; 
  document.getElementById(\"RemainsMinutes\").innerHTML=\"<b>\"+RemainsMinutes+\"</b><span id='Rem'> м</span>\"; 
  document.getElementById(\"lastSec\").innerHTML=\"<b>\"+lastSec+\"</b><span id='Rem'> с</span>\"; <!-- highslide start  -->
  setTimeout('fulltime()',10)  

  } 
  </script> 
  <div id='clou_xs'>
  <div id='clock'>".$do."
  <span id='RemainsFullDays'></span>
    <span id='RemainsFullHours'></span>
    <span id='RemainsMinutes'></span>
    <span id='lastSec'></span> 
  </div>
  </div>
  <script language='javascript'>fulltime();</script>";
}
///////////////////////////////////////////////////////////////
?>