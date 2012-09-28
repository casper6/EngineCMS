<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
//require_once("mainfile.php");
@require_once("config.php"); // Настройки сайта
@require_once("includes/db.php"); // База данных (функции для работы)
@require_once("includes/sql_layer.php");
//require_once("shablon.php");
global $prefix, $db, $opros, $otpravka_pic; 

if (isset($_COOKIE['admin'])) {
  if ($_COOKIE['admin'] != "") $admin = 1; else $admin = 0;
} else $admin = 0;

$ip = getenv("REMOTE_ADDR"); // IP
$opros_res = intval($_GET['res']); // Вывод: 1 - опрос, 2 - отправка, 3 - результаты
$opros_num = intval($_GET['num']); // Номер опроса
$opros_id = $prefix.'opros'.$opros_num;
$dat = date("Y.m.d H:i:s");
if (isset($_COOKIE[$opros_id])) $tmp = $_COOKIE[$opros_id]; else $tmp = "";
$otvet = "";

if ($opros_res != 1 and $opros_res != 3) {
  $opros_golos = intval($_GET['golos']); // Если вывод = 2, получаем информации о голосовании
  //if ($opros_golos > -1) {
    if ($tmp == $opros_id) {
        $otvet = "<b><font color=red>Вы уже голосовали.</font></b>";
      } else {
        $db->sql_query("INSERT INTO ".$prefix."_golos ( `gid` , `ip` , `golos`, `num`, `data`) VALUES ('', '$ip', '$opros_golos', '$opros_num', '$dat')");
        $otvet = "<b><font color=green>Ваш голос принят. Спасибо!</font></b>";
        setcookie ("$opros_id", "$opros_id",time()+2678400,"/");
  
        // Получить из базы данных значения и прибавить к ним голоса
        $sql2 = "select text from ".$prefix."_mainpage where type='3' and name='5' and id='$opros_num'"; // `name` != '$name' and 
        $result2 = $db->sql_query($sql2);
        $row2 = $db->sql_fetchrow($result2);
        $textX = trim($row2['text']);
        $lines = explode("\r\n",$textX);
        $txt = "";
        foreach ($lines as $line_id => $line) {
          $line = explode("|",$line);
          $col = $line[1]; if ($col=="") $col=0;
          $line = $line[0];
          if ($line_id == $opros_golos) $col = $col + 1;
          $txt .= $line."|".$col."\r\n";
        } // for закончился
        $db->sql_query("UPDATE ".$prefix."_mainpage SET text='$txt' WHERE id='$opros_num' and name='5' and type='3'");
      }
  //} else $otvet = "<b><font color=red>Ошибка. Ваш голос не добавлен.</font></b>";
}

// Загрузка информации об опросе
$sql2 = "select text, useit from ".$prefix."_mainpage where type='3' and name='5' and id='$opros_num'"; 
$result2 = $db->sql_query($sql2);
$row2 = $db->sql_fetchrow($result2);
$lines = explode("\r\n", trim($row2['text']));
$useit = explode("|", $row2['useit']); 
$useit = $useit[1]; // опции
parse_str($useit);

  mt_srand ((double)microtime()*1000000);
  $re = mt_rand(0, 1000000);
  $textX = "<div id=a".$re."><a name=\"golos$re\"></a>";
  $textX2 = "";
  $lines2 = array();
  $cols2 = array();
  
  foreach ($lines as $line_id => $line) {
    $line = explode("|",$line);
    $col = $line[1]; if ($col=="") $col=0;
    $line = $line[0];

    if ($tmp == $opros_id or $opros_res != 1) { // Если голосовали - готовим результат
      $lines2[] = $line;
      $cols2[] = $col;
    } else { // Если еще не голосовали
      if ($opros_type==1) { // Выбор кружки
        $textX2 .= "<label class=\"radio-opros\" onclick=\"valueOpros = ".$line_id.";\"><input name=opros[] type=radio value='".$line_id."'>".$line."</label><br>"; 
      } else { // Выбор флажки
        $textX2 .= "<label class=\"checkbox-opros\" onclick=\"valueOpros = ".$line_id.";\"><input name=opros[] type=checkbox value='".$line_id."'>".$line."</label><br>"; 
      }
    }
  } // for закончился

  if ($tmp==$opros_id or $opros_res != 1) { // Если голосовали - показываем результат
    if ($opros_result == 1 or $opros_result == 2 or $admin == 1) { // Если результат можно видеть всем
      if ($opros_result == 0 and $admin == 1) $textX .= "<B>Результаты опроса видите только вы — администратор.</B>";
      $sto = array_sum($cols2);
      if ($opros == 0) {
        $textX .= "";
        foreach ($lines2 as $line_id => $line) {
          $proc = intval($cols2[$line_id] * 100 / $sto);
          if ($proc>50) { 
            $line1=$proc."%"; $line2=""; 
          } else {
            $line1=""; $line2=$proc."%"; 
          }
          $proc2 = 100 - $proc;
          if ($proc==0) $proc==1;
          $textX .= "<table width=100%><tr><td colspan=2 class=opros_otvet>".$line."</td></tr><tr><td bgcolor=red style='text-align:right; width:".$proc."%; height:16px;' class=opros_line><b>".$line1."</b></td><td style='text-align:left; width:".$proc2."%; height:16px;' class=opros_line2>".$line2."</td></tr></table>";
        }
        $textX .= "";
      } else { // Если выбран графический вид результатов опроса
        $ver = mt_rand(10000, 99999); // получили случайное число
        $textX .= "<br><img src=ajax.php?diag=$opros_num&nu=$ver>";
      }
      $textX .= "<br><span class=opros_all>Всего проголосовало: $sto.</span>";
    } else {
      $textX .= "Вы уже проголосовали. Администратор запретил просмотр результатов голосования.";
    }
  } else { // Если еще не голосовали - ссылка на результаты

    $textX .= "<script language=JavaScript>\n
var valueOpros = -1;
function CheckForm".$re."(){
  if(valueOpros == -1) { alert('Вы не выбрали ни одного ответа!'); }
  else { 
    $(showopros".$opros_num."(2,valueOpros));
  }
}
</script>
<form method=post enctype=\"multipart/form-data\" onsubmit=\"return false\">".$textX2."<br><center>
<input type='image' id=\"go\" name='go' src=\"".$otpravka_pic."\" title='Отправить' style='border:0;' class=\"ok opros\" onclick=\"CheckForm".$re."();\"></center></form>";

if ($opros_result == 1 or $admin == 1)  $textX .= "<br><a href=\"#golos$re\" onclick=\"$(showopros".$opros_num."(3, 0)); return false;\" class=opros_result_show>Посмотреть результаты</a>";
  }
  $textX .= "</div>";

if ($otvet != "") $textX = $otvet; // если нужно сообщить о голосовании

header ("Content-Type: text/html; charset=utf-8");
echo $textX;
?>
