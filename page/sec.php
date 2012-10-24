<?php
##########################################################################################
  while( list($key, $value) = @each($_REQUEST) ) {
    $_REQUEST[$key] = (!isset($value)) ? '' : $value;
  }
  while( list($key, $value) = @each($GLOBALS) ) {
    $GLOBALS[$key] = (!isset($value)) ? '' : $value;
  }

  $agent=" ".getenv("HTTP_USER_AGENT"); # Защита от скачивания
    if (stripos($agent,"DISCo Pump") || stripos($agent,"Offline Explorer") || stripos($agent,"Teleport") || stripos($agent,"WebZIP") || stripos($agent,"WebCopier") || stripos($agent,"Wget") || stripos($agent,"FlashGet") || stripos($agent,"CIS TE") || stripos($agent,"DTS Agent") || stripos($agent,"WebReaper") || stripos($agent,"HTTrack") || stripos($agent,"Web Downloader")) { die("Хорош уже скачивать!"); } 
  
  unset($pagetitle);
  if(!defined('END_TRANSACTION')) {
    define('END_TRANSACTION', 2);
  }

  $_SERVER['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER'])?addslashes(stripslashes($_SERVER['HTTP_REFERER'])):'';
  // Запрет использования других серверов
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_SERVER['HTTP_REFERER'])) {
      if (stripos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
       die('Запрещено размещение информации с другого сервера');
      }
    } else die("<b>Ошибка:</b> браузер не смог послать заголовок HTTP_REFERER для этого сайта.<br>
  Вся проблема - в браузере, использовании прокси-сервера или фаервола.<br>
  Смените браузер, отключите прокси и фаервол.<br><br>
  А может быть вы просто сохранили страницу сайта, открыли её и пытаетесь отправить письмо, комментарий или проголосовать.<br>
  Это возможно только со страниц сайта.");
  }

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

  if (stristr(htmlentities($_SERVER['PHP_SELF']), "mainfile.php")) { // проверить
    header("Location: index.php");
    exit();
  }

  foreach ($_COOKIE AS $c_key => $c_val) {
      if (isset($_POST[$c_key]) OR isset($_GET[$c_key])) unset($_COOKIE[$c_key]); 
      $c_val = str_replace("select ","",$c_val);
      $c_val = str_replace("union ","",$c_val);
      $c_val = str_replace("from ","",$c_val);
      $c_val = str_replace(".php","",$c_val);
      $c_val = str_replace(base64_encode("select "),"",$c_val);
      $c_val = str_replace(base64_encode("union "),"",$c_val);
      $c_val = str_replace(base64_encode("from "),"",$c_val);
      $c_val = str_replace(base64_encode(".php"),"",$c_val);
      $_COOKIE[$c_key] = $c_val;
  }  
  
  if (!ini_get('register_globals')) { 
   @import_request_variables("GPC", "");
   //extract($_POST, EXTR_SKIP); 
   //extract($_GET, EXTR_SKIP); 
   //extract($_COOKIE, EXTR_SKIP); 
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

  // Дополнительная безопасность (Union, CLike, XSS)
  if ( isset($_SERVER['QUERY_STRING']) ) {
    $queryString = strtolower($_SERVER['QUERY_STRING']); // Если будут ошибки - убрать!
    if (stripos($queryString,'0dunion') OR stripos($queryString,'%20union%20') OR stripos($queryString,'/*') OR stripos($queryString,'*/union/*') OR stripos($queryString,'c2nyaxb0') OR stripos($queryString,'+union+') OR stripos($queryString,'http://') OR (stripos($queryString,'cmd=') AND stripos($queryString,'&cmd') === false) OR (stripos($queryString,'exec') AND stripos($queryString,'execu')===false) OR stripos($queryString,'concat') OR stripos($queryString,'*%2f*') OR stripos($queryString,'/*') OR stripos($queryString,'2%2f%2a') OR stripos($queryString,'--+')) {
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
  $postString = strtolower(str_replace("%09", "%20", $postString));
  $postString_64 = strtolower(base64_decode($postString));
  if (stripos($postString,'%20union%20') OR stripos($postString,'*/union/*') OR stripos($postString,' union ') OR stripos($postString_64,'%20union%20') OR stripos($postString_64,'*/union/*') OR stripos($postString_64,' union ') OR stripos($postString_64,'+union+') OR stripos($postString_64,'http://') OR (stripos($postString_64,'cmd=') AND stripos($postString_64,'&cmd')===false) OR (stripos($postString_64,'exec') AND stripos($postString_64,'execu')===false) OR stripos($postString_64,'concat') OR (stripos($postString,'http-equiv')) OR (stripos($postString_64,'http-equiv')) OR (stripos($postString,'alert(')) OR (stripos($postString_64,'alert(')) OR (stripos($postString,'javascript:')) OR (stripos($postString_64,'javascript:')) OR (stripos($postString,'document.cookie')) OR (stripos($postString_64,'document.cookie')) OR (stripos($postString,'onmouseover=')) OR (stripos($postString_64,'onmouseover=')) OR (stripos($postString,'document.location')) OR (stripos($postString_64,'document.location'))) {
    die('Попытка взлома, тип 4<br>Возможно, что вы ввели «javascript:» перед JS-запросом в ссылке. Делать этого не нужно, т.к. и без этого JS-код будет работать.');
  }

  if (isset($admin) && $admin == $_COOKIE['admin']) {
    $admin = base64_decode($admin);
    $admin = addslashes($admin);
    $admin = base64_encode($admin);
  }

  // Сабж для использующих вредоносный HTML-код
  $htmltags = "<center><img src=\"/images/logo_admin.png\"><br><br><b>Вы использовали запрещенные символы HTML-кода. Вероятно вы - взломщик.</b><br><br>[ <a href=\"javascript:history.go(-1)\"><b>Вернитесь назад и больше не вводите HTML-теги.</b></a> ]";

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
      (preg_match("/\([^>]*\"?[^)]*\)/i", $secvalue)) ) {
      die ($htmltags);
      }
    }
  }
##########################################################################################
?>