<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
@require_once("config.php"); // Настройки сайта
@require_once("includes/db.php"); // База данных (функции для работы)
@require_once("includes/sql_layer.php");
require_once("shablon.php");
global $prefix, $db; // , $ad, $id, $desc, $sha, $vetki, $comments_num, $comments_all, $comments_mail, $comments_adres, $comments_tel;

if (isset($_COOKIE['admin'])) {
  if ($_COOKIE['admin'] != "") $admin = 1; else $admin = 0;
} else $admin = 0;

if (isset($_GET['p_id'])) $pid = intval($_GET['p_id']);
else die('Комментариев нет.');

$comments_desc = intval($_GET['desc']);
$comment_shablon = intval($_GET['sha']);
$vetki = intval($_GET['vetki']);
$comments_num = intval($_GET['num']);
$comments_all = intval($_GET['all']);
$comments_mail = intval($_GET['mail']);
$comments_adres = intval($_GET['adres']);
$comments_tel = intval($_GET['tel']);

$url = getenv("REQUEST_URI");

$lim = ""; // доделать на аяксе
if ($comments_desc == 1) $dat = " desc"; else $dat = "";

  $sql_comm = "SELECT `cid`,`avtor`,`ava`,`mail`,`text`,`ip`,`data`,`drevo`,`adres`,`tel` FROM ".$prefix."_pages_comments WHERE `num`='$pid' and `active`='1' order by drevo, data".$dat.$lim;
  $result = $db->sql_query($sql_comm);
  $numrows = $db->sql_numrows($result);
  $nu = 0;
  // Получаем шаблон
  $sha = shablon_show("comments", $comment_shablon);
  $p_id = $avtor = $text = $date1 = $date2 = $ip = $drevo = $mail = $adres = $tel = $ava = array();

  $date_now = date("d m Y");
  $date_now2 = date("d m Y",time()-86400);
  $date_now3 = date("d m Y",time()-172800);
  $c_id = 0;
  $comm = "В ожидании вашего комментария...";
  while ($row = $db->sql_fetchrow($result)) {
    $c_id = $row['cid'];
    $p_id[$c_id] = $pid;
    $avtor[$c_id] = $row['avtor'];
    $text[$c_id] = $row['text'];
    $date = str_replace(".","-",str_replace(" ","-",str_replace(":","-",$row['data'])));
    $datax = explode("-",$date);
    $datax1 = intval($datax[2])." ".findMonthName($datax[1])." ".$datax[0];
    $datax1_1 = $datax[2]." ".$datax[1]." ".$datax[0];
    $datax2 = $datax[3].":".$datax[4]."";
    if ($date_now == $datax1_1) $datax1 = "Сегодня";
    elseif ($date_now2 == $datax1_1) $datax1 = "Вчера";
    elseif ($date_now3 == $datax1_1) $datax1 = "Позавчера";
    $date1[$c_id] = $datax1;
    $date2[$c_id] = $datax2;
    $ip[$c_id] = $row['ip'];
    $drevo[$c_id] = $row['drevo'];
    $mail[$c_id] = $row['mail'];
    $ava[$c_id] = $row['ava'];
    $adres[$c_id] = $row['adres'];
    $tel[$c_id] = $row['tel'];
  }
  if (count($p_id) > 0 and $c_id != 0) $comm = generate_comm($admin, $p_id, $avtor, $text, $mail, $adres, $tel, $date1, $date2, $ip, $drevo, $sha, "0", "", $vetki, $comments_num, $comments_all, $comments_mail, $comments_adres, $comments_tel, $ava);
  header ("Content-Type: text/html; charset=utf-8");
  echo $comm;
//}

/////////////////////////////////////////////////////
function generate_comm($admin, $p_id, $avtor, $text, $mail, $adres, $tel, $date1, $date2, $ip, $drevo, $sha, $position, $numb="", $vetki, $comments_num, $comments_all, $comments_mail, $comments_adres, $comments_tel, $ava) {
  $sha3 = "";
  global $db, $prefix;
  $ok = false;
  $all_show = ""; $all_hide = ""; // список для "раскрыть все ответы"
  $comments_nu = $comments_num + 1;
  $nu = 0;
  foreach ( $p_id as $comm_cid => $pid ) {
    $sha2 = "";
    if (isset($drevo[$comm_cid])) {
      if ($drevo[$comm_cid] == $position) { 
        $ok = true; //$sha2 .= "1 $position - $drevo[$comm_cid]<br>"; 
      } else { 
        $ok = false; //$sha2 .= "2 $position - $drevo[$comm_cid]<br>"; 
      }
      } else $ok = false;
    if ($ok == true) {
    $nu++;

// Кавычки в имени (исправление ошибки в IE)
//$avtor[$comm_cid] = preg_replace('/(^|\s)"(\S)/', '$1&laquo;$2', $avtor[$comm_cid]);
//$avtor[$comm_cid] = preg_replace('/(\S)"([ .,?!])/', '$1&raquo;$2', $avtor[$comm_cid]);
$avtor[$comm_cid] = str_replace('"', ' ', str_replace('\'', ' ', $avtor[$comm_cid])); // &raquo;
$avtor2 = $avtor[$comm_cid];
//$avtor2 = "<a href=\"#addcomm\" title='Вставить имя в ваш комментарий' onclick=\"clc_name(' ".$avtor[$comm_cid].", ')\">".$avtor[$comm_cid]."</a>";
$text2 = str_replace(',', ', ', $text[$comm_cid]);
//$text2 = str_replace(' ,', ',', $text2);
//$text2 = str_replace(' ...', '...', $text2);
//$text2 = str_replace(' руб ', ' руб. ', $text2); $text2 = str_replace(' руб,', ' руб.,', $text2); $text2 = str_replace(' руб!', ' руб.!', $text2);
  $text2 = "<div id='comm_".$comm_cid."' class='overcomm'>".$text2."</div>";

    $ver = mt_rand(10000, 99999); // получили случайное число
    $nus = $numb.$nu;
    if ($vetki > 0) $comm_otvet = " <a class='no comm_write' href=#comm_otvet_show onclick='otvet(".$comm_cid.", \"".$nus."\", \" ".$avtor[$comm_cid].", \");'>Ответить</a>";
    else $comm_otvet = "";

    $otvets = generate_comm_num($p_id, $drevo, $comm_cid);
    if ($position==0 and $otvets > 0 and $vetki == 1) {
      $comment_otvet_show = " <div style=\"display:inline;\" id=\"show_otvet".$nus."\"><a class=\"no\" title=\"Показать ответы на это сообщение\" href=\"#big_otvet".$ver."\" onclick='show(\"big_otvet".$nus."\"); show(\"show_otvet".$nus."\"); show(\"hide_otvet".$nus."\");'>+</a> <a class=\"no showotvet\" title=\"Показать ответы на это сообщение\" href=\"#big_otvet".$ver."\" onclick='show(\"big_otvet".$nus."\"); show(\"show_otvet".$nus."\"); show(\"hide_otvet".$nus."\");'>Раскрыть ответ</a></div><div style=\"display:none;\" id=\"hide_otvet".$nus."\"><a class=\"no showotvet\" title=\"Убрать ответы на это сообщение\" href=\"#big_otvet".$ver."\" onclick='show(\"big_otvet".$nus."\"); show(\"show_otvet".$nus."\"); show(\"hide_otvet".$nus."\");'>-</a> <a class=\"no\" title=\"Убрать ответы на это сообщение\" href=\"#big_otvet".$ver."\" onclick='show(\"big_otvet".$nus."\"); show(\"show_otvet".$nus."\"); show(\"hide_otvet".$nus."\");'>Скрыть ответ</a></div>"; 
      $all_show .= "show(\"big_otvet".$nus."\"); show(\"show_otvet".$nus."\"); show(\"hide_otvet".$nus."\"); ";
      $all_hide .= "show(\"big_otvet".$nus."\"); show(\"show_otvet".$nus."\"); show(\"hide_otvet".$nus."\"); ";
    } else $comment_otvet_show = "";

    //$comm_citata = " <a href=\"#addcomm\" title='Вставить как цитату в комментарий №$comm_cid' onclick=\"var s=document.getElementById('comm_".$comm_cid."').innerHTML; clc_name(' [quote] ".$avtor[$comm_cid]." писал(а): ' + citata_shock(s) + '[/quote] ')\" class='no citata'>Цитата</a>";

    $avtor_type = "Гость";

    if ($admin==1) $comment_admin = "<a href=/sys.php?op=base_comments_edit_comments&cid=".$comm_cid."&red=1 title='Изменить в HTML'><img src='/images/sys/edit_0.png' align=bottom width=16></a> <a href=/sys.php?op=base_pages_delit_comm&cid=".$comm_cid."&ok=ok&pid=".$pid." title='Удалить'><img align=bottom src=/images/sys/del.png width=16></a> "; else $comment_admin = "";

if (strpos($sha,"[comment_ipbox]")) {
  $ipbox = explode(".",$ip[$comm_cid]);
  if ($avtor[$comm_cid] == "Администратор" or $avtor[$comm_cid] == "Админ" or $avtor[$comm_cid] == "админ") { $ipbox[0]=255; $ipbox[1]=0; $ipbox[2]=0; }
  if ($avtor[$comm_cid] == "Редактор" or $avtor[$comm_cid] == "редактор" or $avtor[$comm_cid] == "Директор" or $avtor[$comm_cid] == "директор") { $ipbox[0]=205; $ipbox[1]=35; $ipbox[2]=189; }
  /*
  if ($mail[$comm_cid] != "") {
        $avatar = $ava[$comm_cid];
        if ($avatar == "") $avatar = validate_gravatar($mail[$comm_cid], $comm_cid);
        if ($avatar == "no") $avatar = "";
  } else 
  */
  $avatar = "";

  /*
  if ($avatar != "") $comm_ipbox = "<a title='Вы всегда можете поменять свой аватар' href='http://ru.gravatar.com/site/login/' target='_blank' rel='nofollow'><img src='".$avatar."?s=35' style='margin-right:10px; float:left; border:0; width:35px; height:35px;'></a>";
  else 
  */

  $comm_ipbox = "<div style='margin-right:10px; float:left; border:0; background: rgb(".$ipbox[0].", ".$ipbox[1].", ".$ipbox[2]."); width:35px; height:35px;'><a title='Чтобы изменить аватар, нажмите здесь и зарегистрируйтесь, введя адрес email' href='http://ru.gravatar.com/site/signup/' target='_blank' rel='nofollow'><img title='#".$comm_cid." ip:".$ip[$comm_cid]."' src=/images/avatar_new.png></a></div>";
} else $comm_ipbox = "";

if ($comments_mail > 1 and trim($mail[$comm_cid]) != "") $comm_mail = "<b>E-mail:</b> ".$mail[$comm_cid]; else $comm_mail = "";
if ($comments_tel > 1 and trim($tel[$comm_cid]) != "") $comm_tel = "<b>Телефон:</b> ".$tel[$comm_cid]; else $comm_tel = "";
if ($comments_adres > 1 and trim($adres[$comm_cid]) != "") $comm_adres = "<b>Адрес:</b> ".$adres[$comm_cid]; else $comm_adres = "";

// "[comment_citata]"=>$comm_citata,

      $sha_zamena = array(
      "[comment_otvet]"=>$comm_otvet,
      "[comment_otvet_show]"=>$comment_otvet_show,
      "[comment_id]"=>$comm_cid,
      "[comment_num]"=>$numb.$nu,
      "[comment_text]"=>$text2,
      "[comment_avtor_type]"=>$avtor_type,
      "[comment_avtor]"=>"".$avtor2, //<a name=\"big_otvet".$nus."\"></a>
      "[comment_data]"=>$date1[$comm_cid],
      "[comment_time]"=>$date2[$comm_cid],
      "[comment_admin]"=>$comment_admin,
      "[comment_mail]"=>$comm_mail,
      "[comment_tel]"=>$comm_tel,
      "[comment_ipbox]"=>$comm_ipbox,
      "[comment_adres]"=>$comm_adres
      );

      if ($position==0 and $vetki != 2) {
      $width = ""; $display = " style=\"display: none;\""; 
      } else {
          if ($position==0) {
          $width = ""; $display = ""; 
          } else {
          $width = " style=\"padding-left: 20px\""; $display = "";
          }
      }

$sha2 = "";
if ($comments_num>0 and $comments_all==1 and $numb.$nu == $comments_nu) $sha2 .= "<div id=allcomm style='display:none;'>";

$sha2 .= "<div class='comm_razdel'".$width.">".strtr($sha,$sha_zamena);

$number = $numb.$nu."_";
//if (count($p_id) > 0 and count($drevo) > 0)
if (generate_comm_num($p_id, $drevo, $comm_cid) > 0) 
$sha2 .= "<div class='comm_otvet'".$display." id=\"big_otvet".$nus."\">".generate_comm($admin, $p_id, $avtor, $text, $mail, $adres, $tel, $date1, $date2, $ip, $drevo, $sha, $comm_cid, $number, $vetki, $comments_num, $comments_all, $comments_mail, $comments_adres, $comments_tel, $ava)."</div>";

$sha2 .= "</div>";
if ($comments_num>0 and $comments_all==1 and $numb.$nu == $comments_nu) $sha2 .= "</div>";
$sha3 .= $sha2;
$sha2 = "";
    }
}
$and = "";
 if ( $position==0 and $vetki == 1) $and .= "<br><a class=\"no\" id=\"all_show\" title=\"Показать все ответы\" href=\"#comm\" onclick='".$all_show." show(\"all_hide\"); show(\"all_show\");'>+ Показать все ответы</a><a class=\"no\" id=\"all_hide\" style='display:none;' title=\"Показать все ответы\" href=#comm onclick='".$all_hide." show(\"all_hide\"); show(\"all_show\");'>- Скрыть все ответы</a>";
return $and.$sha3;

}

function generate_comm_num($p_id, $drevo, $position) {
  $ok = false;  $nu = 0;
  foreach ( $p_id as $comm_cid => $pid ) {
    $sha2 = "";
    if ($drevo[$comm_cid] == $position) { 
      $ok = true; 
    } else { 
      $ok = false; 
    }
    if ($ok == true) {
      $nu++;
    }
  }
  return $nu;
}

function findMonthName($m) { // Функция определения имени месяца по его числу
  $m = intval($m);
  $month = array("", "января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
  return $month[$m];
}

function validate_gravatar($email, $cid) { // Проверка аватара
  global $db, $prefix;
  $uri = 'http://www.gravatar.com/avatar/' . md5($email) . '?d=404';
  $headers = @get_headers($uri);
  if (preg_match("|200|", $headers[0]) == 1) $result = $uri;
  else $result = "no";
  // Сохраним полученный результат, чтобы потом не получать его повторно (ДОПИСАТЬ функцию ежемесячной перепроверки)
  $db->sql_query("UPDATE ".$prefix."_pages_comments SET ava='".$result."' WHERE `cid`='".$cid."'");
  return $result;
}
?>
