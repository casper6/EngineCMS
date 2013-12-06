<?php
require_once("mainfile.php");
global $prefix, $db, $otpravka_pic, $_COOKIE; 

if (is_admin($admin)) $admin_ok = 1;
else $admin_ok = 0;

$ip = getenv("REMOTE_ADDR"); // IP
$opros_res = intval($_GET['res']); // Вывод: 1 - опрос, 2 - отправка, 3 - результаты
$opros_num = intval($_GET['num']); // Номер опроса
$opros_id = $prefix.'opros'.$opros_num;
$dat = date("Y.m.d H:i:s");
if (isset($_COOKIE[$opros_id])) $tmp = $_COOKIE[$opros_id];
else $tmp = "";
$otvet = "";

if ($opros_res != 1 && $opros_res != 3 && $_GET['golos'] != '') {
  //$otvet = $_GET['golos'];
  //if (!is_integer($_GET['golos'])) 
  $opros_golosa = explode(" ", trim($_GET['golos']));
  //else $opros_golosa = array( intval($_GET['golos']) ); // Если вывод = 2, получаем информации о голосовании
  //if ($opros_golos > -1) {
    if ($tmp == $opros_id) {
        $otvet .= "<span class='red'>".ss("Вы уже голосовали.")."</span>";
      } else {
        // Получить из базы данных значения и прибавить к ним голоса
        $sql2 = "select `text` from ".$prefix."_mainpage where `type`='3' and `name`='5' and `id`='".$opros_num."'";
        $result2 = $db->sql_query($sql2);
        $row2 = $db->sql_fetchrow($result2);
        $textX = trim($row2['text']);
        $lines = explode("\r\n",$textX);
        $txt = "";
        foreach ($opros_golosa as $opros_golos) {
          $db->sql_query("INSERT INTO ".$prefix."_golos ( `gid` , `ip` , `golos`, `num`, `data`) VALUES ('', '".$ip."', '".$opros_golos."', '".$opros_num."', '".$dat."')");
          $lines2 = array();
          foreach ($lines as $line_id => $line) {
            $line = explode("|",$line);
            if (isset($line[1])) $col = $line[1]; 
            else $col=0;
            if (isset($line[2])) $line_ok = "|".$line[2]; 
            else $line_ok = "";
            $line = $line[0];
            if ($line_id == $opros_golos) $col = $col + 1;
            $lines2[] = $line."|".$col.$line_ok;
          }
          $lines = $lines2;
        }
        $txt = implode("\r\n", $lines);
        $db->sql_query("UPDATE ".$prefix."_mainpage SET `text`='".$txt."' WHERE `id`='".$opros_num."' and `name`='5' and `type`='3'");
        $otvet .= "<span class='green'>".ss("Ваш голос принят. Спасибо!")."</span>";
        setcookie ($opros_id, $opros_id,time()+2678400,"/");
      }
}
// Загрузка информации об опросе
$sql2 = "select `text`, `useit` from ".$prefix."_mainpage where `type`='3' and `name`='5' and `id`='".$opros_num."'"; 
$result2 = $db->sql_query($sql2);
$row2 = $db->sql_fetchrow($result2);
// выделим имя модуля раздела и настройки
$lines = explode("\r\n", trim($row2['text']));
$useit = explode("|", $row2['useit']); 
$useit = $useit[1]; // опции
parse_str($useit);
mt_srand ((double)microtime()*1000000);
$re = mt_rand(0, 1000000);
$textX = "<div id='a".$re."'><a name='golos".$re."'></a>";
$textX2 = "";
$lines2 = array();
$cols2 = array();
foreach ($lines as $line_id => $line) {
  $line = explode("|",$line);
  if (isset($line[1])) $col = $line[1];
  else $col=0;
  if (isset($line[2])) {
    $ok_line = true; 
    $text_line = $line[2];
  } else $ok_line = false;

  $line = $line[0];
  if ($tmp == $opros_id || $opros_res != 1) { // Если голосовали - готовим результат
    if ($ok_line == true) $line = "<b>".$text_line."</b> <s>".$line."</s>";
    $lines2[] = $line;
    $cols2[] = $col;
  } else { // Если еще не голосовали
    if ($ok_line == true) {
      $line = "<b>".$text_line."</b> <s>".$line."</s>";
      $line_disabled = " disabled";
    } else $line_disabled = "";
    if ($opros_type==1) { // Выбор кружки
      $textX2 .= "<label class='radio-opros' onclick='valueOpros = ".$line_id.";'><input name='opros[]' type='radio' value='".$line_id."'".$line_disabled."> ".$line."</label><br>"; 
    } else { // Выбор флажки
      $textX2 .= "<label class='checkbox-opros' onclick='if (valueOpros == \"-1\") valueOpros = \"\"; valueOpros = valueOpros+\" \"+".$line_id.";'><input name='opros[]' type='checkbox' value='".$line_id."'".$line_disabled."> ".$line."</label><br>";
    }
  }
}
if ($tmp==$opros_id || $opros_res != 1) { // Если голосовали - показываем результат
  if ($opros_result == 1 || $opros_result == 2 || $admin_ok == 1) { // Если результат можно видеть всем
    if ($opros_result == 0 && $admin_ok == 1) $textX .= aa("Результаты опроса видите только вы — администратор.");
    $sql2 = "select `ip` from ".$prefix."_golos where `num`='".$opros_num."'"; 
    $result2 = $db->sql_query($sql2);
    $sto = array();
    while ($row = $db->sql_fetchrow($result2))
      $sto[] = $row['ip'];
    $sto = count( array_unique($sto) );
    foreach ($lines2 as $line_id => $line) {
      if ($sto > 0) $proc = intval($cols2[$line_id] * 100 / $sto); 
      else $proc = 0;
      if ($proc > 50) { 
        if ($proc > 100) $proc = 100;
        $line2 = "";
        $line1 = $proc." %";
      } else {
        $line1 = "";
        $line2 = $proc." %";
      }
      $proc2 = 100 - $proc;
      $textX .= "<div class='w100' class='opros_otvet'>".$line."</div>
      <div class='w100 opros_line'>".$line1."<div style='width:".$proc2."%;' class='opros_line2'>".$line2."</div></div>";
    }
    $textX .= "<br><span class='opros_all'>".ss("Всего проголосовало:")." ".$sto.".</span>";
  } else {
    $textX .= ss("Вы уже проголосовали. Администратор запретил просмотр результатов голосования.");
  }
} else { // Если еще не голосовали - ссылка на результаты
  $textX .= "<form method=post enctype=\"multipart/form-data\" onsubmit=\"return false\">".$textX2."
  <p><input type='submit' id=\"go\" name='go' value='Отправить' class=\"ok opros\" onclick=\"CheckForm(".$opros_num.");\"></form>";
  if ($opros_result == 1 || $admin_ok == 1) $textX .= "<p><a href=\"#golos".$re."\" onclick=\"$(showopros(".$opros_num.",3, 0)); return false;\" class=opros_result_show>".ss("Посмотреть результаты")."</a>";
}
$textX .= "</div>";
if ($otvet != "") $textX = $otvet; // если нужно сообщить о голосовании
header ("Content-Type: text/html; charset=utf-8");
echo $textX;
?>
