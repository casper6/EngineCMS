<?php
require_once("mainfile.php");
require_once("shablon.php");
global $prefix, $db, $admin;
if (is_admin($admin)) $admin_ok = 1; else $admin_ok = 0;
if (isset($_GET['p_id'])) $pid = intval($_GET['p_id']); 
else die(ss("Комментариев нет."));
$id_module = intval($_GET['id_module']);
$start = intval($_GET['start']);
$num = intval($_GET['num']);

// Получение настроек раздела
$sql = "SELECT `name`,`title`,`text`,`useit`,`shablon`,`description`,`keywords` FROM ".$prefix."_mainpage where `id`='".$id_module."' and `type`='2'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
//$options = explode("|",$row['text']);
//$module_name = $options[0];
$options = str_replace("pages|","",$row['text']);
parse_str($options);
$url = getenv("REQUEST_URI"); // запросить
if ($num != 0) $comments_num = $num;

//if ($comments_num > 0) $lim = " limit ".$start.",".$comments_num;
//else 
$lim = "";

if ($comments_desc == 1) $dat = " desc"; 
else $dat = "";

$sql_comm = "SELECT `cid` FROM ".$prefix."_pages_comments WHERE `num`='".$pid."' and `active`='1'";
$result = $db->sql_query($sql_comm);
$numrows = $db->sql_numrows($result);

$sql_comm = "SELECT `cid`,`avtor`,`ava`,`mail`,`text`,`ip`,`data`,`drevo`,`adres`,`tel` FROM ".$prefix."_pages_comments WHERE `num`='".$pid."' and `active`='1' order by drevo, data".$dat.$lim;
$result = $db->sql_query($sql_comm);

$nu = 0;
header ("Content-Type: text/html; charset=utf-8");
if ($numrows == 0) {
  echo ss("В ожидании вашего комментария...");
} else {
  if ($comment_shablon < 20) // Получаем шаблон
    $sha = shablon_show("comments", $comment_shablon);
  else {
    // Доступ к шаблону
    $sql2 = "select `text` from ".$prefix."_mainpage where `tables`='pages' and `id`='".mysql_real_escape_string($comment_shablon)."' and `type`='6'";
    $result2 = $db->sql_query($sql2);
    $row2 = $db->sql_fetchrow($result2);
    $sha = $row2['text'];
  }
  $p_id = $avtor = $text = $date1 = $date2 = $ip = $drevo = $mail = $adres = $tel = $ava = array();
  $date_now = date("d m Y");
  $date_now2 = date("d m Y",time()-86400);
  $date_now3 = date("d m Y",time()-172800);
  while ($row = $db->sql_fetchrow($result)) { // заменить на функцию даты !!!
    $c_id = $row['cid'];
    $p_id[$c_id] = $pid;
    $avtor[$c_id] = $row['avtor'];
    $text[$c_id] = $row['text'];
    $date = str_replace(".","-",str_replace(" ","-",str_replace(":","-",$row['data'])));
    $datax = explode("-",$date);
    $datax1 = intval($datax[2])." ".findMonthName($datax[1])." ".$datax[0];
    $datax1_1 = $datax[2]." ".$datax[1]." ".$datax[0];
    $datax2 = $datax[3].":".$datax[4]."";
    if ($date_now == $datax1_1) $datax1 = ss("Сегодня");
    elseif ($date_now2 == $datax1_1) $datax1 = ss("Вчера");
    elseif ($date_now3 == $datax1_1) $datax1 = ss("Позавчера");
    $date1[$c_id] = $datax1;
    $date2[$c_id] = $datax2;
    $ip[$c_id] = $row['ip'];
    $drevo[$c_id] = $row['drevo'];
    $mail[$c_id] = $row['mail'];
    $ava[$c_id] = $row['ava'];
    $adres[$c_id] = $row['adres'];
    $tel[$c_id] = $row['tel'];
  }
  echo generate_comm($admin_ok, $p_id, $avtor, $text, $mail, $adres, $tel, $date1, $date2, $ip, $drevo, $sha, "0", "", $vetki, $comments_num, $comments_all, $comments_mail, $comments_adres, $comments_tel, $ava)."<div id='comments_refresh' title='".ss("Обновить комментарии")."'><a onclick='showcomm(".$start.",".$num.")' class='refresh'></a><a href='#new' class='new'>0</a></div>";
  // Ссылка «Раскрыть все» // $all_numrows > $comments_num and  and $comments_num > 0
  if ($comments_all == 1 && $numrows > $comments_num && $comments_num != 0) echo "<div class='comm_all_show'><a href='#comm' id='allcomm_show' onclick=\"showcomm(0,1000000);\">".$comments_8."</a></div>";
}
/////////////////////////////////////////////////////
function generate_comm($admin_ok, $p_id, $avtor, $text, $mail, $adres, $tel, $date1, $date2, $ip, $drevo, $sha, $position, $numb="", $vetki, $comments_num, $comments_all, $comments_mail, $comments_adres, $comments_tel, $ava) {
  $sha3 = "";
  global $db, $prefix, $gravatar;
  $all_show = ""; $all_hide = ""; // список для "раскрыть все ответы"
  $comments_nu = $comments_num + 1;
  $nu = 0;
  foreach ( $p_id as $comm_cid => $pid ) {
    $sha2 = "";
    $ok = false;
    if (isset($drevo[$comm_cid]) && $drevo[$comm_cid] == $position) {
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
      $text2 = "<div id='comm_".$comm_cid."' class='comm_text'>".$text2."</div>";

      $ver = mt_rand(10000, 99999); // получили случайное число
      $nus = $numb.$nu;
      if ($vetki > 0) $comm_otvet = " <a class='no comm_write' href='#comm_otvet_show' onclick='otvet(".$comm_cid.", \"".$nus."\", \" ".$avtor[$comm_cid].", \");'>".ss("Ответить")."</a>";
      else $comm_otvet = "";

      $otvets = generate_comm_num($p_id, $drevo, $comm_cid);
      if ($position==0 and $otvets > 0 and $vetki == 1) {
        $comment_otvet_show = " <div style=\"display:inline;\" id=\"show_otvet".$nus."\"><a class=\"no pointer bb1gray\" title=\"".ss("Показать ответы на это сообщение")."\" onclick='show(\"big_otvet".$nus."\"); show(\"show_otvet".$nus."\"); show(\"hide_otvet".$nus."\");'>+ ".ss("Раскрыть ответ")."</a></div><div style=\"display:none;\" id=\"hide_otvet".$nus."\"><a class=\"no showotvet pointer bb1gray\" title=\"".ss("Убрать ответы на это сообщение")."\" onclick='show(\"big_otvet".$nus."\"); show(\"show_otvet".$nus."\"); show(\"hide_otvet".$nus."\");'>- ".ss("Скрыть ответ")."</a></div>"; 
        $all_show .= "show(\"big_otvet".$nus."\"); show(\"show_otvet".$nus."\"); show(\"hide_otvet".$nus."\"); ";
        $all_hide .= "show(\"big_otvet".$nus."\"); show(\"show_otvet".$nus."\"); show(\"hide_otvet".$nus."\"); ";
      } else $comment_otvet_show = "";

      //$comm_citata = " <a href=\"#addcomm\" title='Вставить как цитату в комментарий №$comm_cid' onclick=\"var s=document.getElementById('comm_".$comm_cid."').innerHTML; clc_name(' [quote] ".$avtor[$comm_cid]." писал(а): ' + citata_shock(s) + '[/quote] ')\" class='no citata'>Цитата</a>";

      $avtor_type = ss("Гость");

      if ($admin_ok==1) $comment_admin = "<a href='sys.php?op=base_comments_edit_comments&cid=".$comm_cid."&red=1' class='edit_link'>".aa("Редактировать")."</a> <a href='sys.php?op=base_pages_delit_comm&cid=".$comm_cid."&ok=ok&pid=".$pid."' class='delete_link'>".aa("Удалить")."</a> "; else $comment_admin = "";

      if (strpos($sha,"[comment_ipbox]")) {
        if ($avtor[$comm_cid] == aa("Администратор") or $avtor[$comm_cid] == aa("Админ") or $avtor[$comm_cid] == aa("админ")) { 
          $ipbox[0]=255; $ipbox[1]=0; $ipbox[2]=0; 
        } elseif ($avtor[$comm_cid] == aa("Редактор") or $avtor[$comm_cid] == aa("редактор") or $avtor[$comm_cid] == aa("Директор") or $avtor[$comm_cid] == aa("директор")) { 
          $ipbox[0]=205; $ipbox[1]=35; $ipbox[2]=189; 
        } else { 
          if ($ip[$comm_cid] != "unknown") $ipbox = explode(".",$ip[$comm_cid]);
          else { $ipbox[0]=0; $ipbox[1]=0; $ipbox[2]=0; }
        }
        
        if ($gravatar == "1") {
          if ($mail[$comm_cid] != "") {
                $avatar = $ava[$comm_cid];
                if ($avatar == "") $avatar = validate_gravatar($mail[$comm_cid], $comm_cid);
                if ($avatar == "no") $avatar = "";
          } else $avatar = "";
          if ($avatar != "") $comm_ipbox = "<a title='".ss("Вы всегда можете поменять свой аватар")."' href='http://ru.gravatar.com/site/login/' target='_blank' rel='nofollow' class='avatar'><img src='".$avatar."?s=35' style='margin-right:10px; float:left; border:0; width:35px; height:35px;'></a>";
          else $comm_ipbox = "<div style='margin-right:10px; float:left; border:0; background: rgb(".$ipbox[0].", ".$ipbox[1].", ".$ipbox[2]."); width:35px; height:35px;'><a title='".ss("Чтобы изменить аватар, нажмите здесь и зарегистрируйтесь, введя адрес email")."' href='http://ru.gravatar.com/site/signup/' target='_blank' rel='nofollow' class='avatar'><img title='#".$comm_cid." ip:".$ip[$comm_cid]."' src='images/avatar_new.png'></a></div>";
        } else $comm_ipbox = "<div style='margin-right:10px; float:left; border:0; background: rgb(".$ipbox[0].", ".$ipbox[1].", ".$ipbox[2]."); width:35px; height:35px;' class='avatar'><img title='#".$comm_cid." ip:".$ip[$comm_cid]."' src='images/avatar_new.png'></div>";

      } else $comm_ipbox = "";

      if ($comments_mail > 1 and trim($mail[$comm_cid]) != "") $comm_mail = "<b>".ss("Email:")."</b> ".$mail[$comm_cid]; else $comm_mail = "";
      if ($comments_tel > 1 and trim($tel[$comm_cid]) != "") $comm_tel = "<b>".ss("Телефон:")."</b> ".$tel[$comm_cid]; else $comm_tel = "";
      if ($comments_adres > 1 and trim($adres[$comm_cid]) != "") $comm_adres = "<b>".ss("Адрес:")."</b> ".$adres[$comm_cid]; else $comm_adres = "";

      // "[comment_citata]"=>$comm_citata,
      $sha_zamena = array(
      "[comment_otvet]"=>$comm_otvet,
      "[comment_otvet_show]"=>$comment_otvet_show,
      "[comment_id]"=>$comm_cid,
      "[comment_num]"=>$numb.$nu,
      "[comment_text]"=>$text2,
      "[comment_avtor_type]"=>$avtor_type,
      "[comment_avtor]"=>"".$avtor2,
      "[comment_data]"=>$date1[$comm_cid],
      "[comment_time]"=>$date2[$comm_cid],
      "[comment_admin]"=>$comment_admin,
      "[comment_mail]"=>$comm_mail,
      "[comment_tel]"=>$comm_tel,
      "[comment_ipbox]"=>$comm_ipbox,
      "[comment_adres]"=>$comm_adres
      );
      $width = $display = "";
      if ($position==0 && $vetki != 2) $display = " style='display: none;'"; 
      elseif ($position!=0) $width = " style='padding-left: 20px'";
      $sha2 = "";
      if ($comments_num>0 && $comments_all==1 && $numb.$nu == $comments_nu) 
        $sha2 .= "<div id='allcomm' style='display:none;'>";
      $sha2 .= "<div class='comm_razdel'".$width.">".strtr($sha,$sha_zamena);
      $number = $numb.$nu."_";
      if ($otvets > 0) // $otvets = generate_comm_num($p_id, $drevo, $comm_cid)
        $sha2 .= "<div class='comm_otvet'".$display." id='big_otvet".$nus."'>".generate_comm($admin_ok, $p_id, $avtor, $text, $mail, $adres, $tel, $date1, $date2, $ip, $drevo, $sha, $comm_cid, $number, $vetki, $comments_num, $comments_all, $comments_mail, $comments_adres, $comments_tel, $ava)."</div>";
      $sha2 .= "</div>";
      if ($comments_num>0 && $comments_all==1 && $numb.$nu == $comments_nu) $sha2 .= "</div>";
      $sha3 .= $sha2;
      $sha2 = "";
    } // end if
    //$sha2 .= "$position  $nu $comments_num<br>";
    if ($position == 0 && $nu == $comments_num && $comments_num != 0) break;
  } // end foreach
  $and = "";
  if ( $position==0 && $vetki == 1) $and .= "<br><a class=\"no pointer bb1gray\" id=\"all_show\" title=\"".ss("Показать все ответы")."\" onclick='".$all_show." show(\"all_hide\"); show(\"all_show\");'>+ ".ss("Показать все ответы")."</a><a class=\"no pointer bb1gray\" id=\"all_hide\" style='display:none;' title=\"".ss("Скрыть все ответы")."\" onclick='".$all_hide." show(\"all_hide\"); show(\"all_show\");'>- ".ss("Скрыть все ответы")."</a>";

  return $and.$sha3;
}

function generate_comm_num($p_id, $drevo, $position) {
  $ok = false;
  $nu = 0;
  foreach ($p_id as $comm_cid => $pid)
    if ($drevo[$comm_cid] == $position) $nu++;
  return $nu;
}

function validate_gravatar($email, $cid) { // Проверка аватара
  global $db, $prefix;
  $uri = 'http://www.gravatar.com/avatar/' . md5($email) . '?d=404';
  $headers = @get_headers($uri);
  if (preg_match("|200|", $headers[0]) == 1) $result = $uri;
  else $result = "no";
  // Сохраним полученный результат, чтобы потом не получать его повторно (ДОПИСАТЬ функцию ежемесячной перепроверки)
  $db->sql_query("UPDATE ".$prefix."_pages_comments SET `ava`='".$result."' WHERE `cid`='".$cid."'");
  return $result;
}
?>
