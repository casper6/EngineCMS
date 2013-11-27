<?php
  // Подписка на рассылку
  global $DBName, $prefix, $db, $now, $ip;
  $avtor = trim(str_replace("  "," ",filter($avtor, "nohtml")));
  $mail = trim(str_replace("  "," ",filter($mail, "nohtml")));
  if (!strpos($mail, "@")) {
    $soderganie .= "<h2>".ss("Вы указали неправильный email.")."</h2>
    ".ss("Попробуйте еще раз")."
    <form method='POST' action='--email' class='main_mail_form'><table><tr>
    <td align='right'>".ss("Email").": </td><td><input type='text' name='mail' class='main_mail_input' size='10'></td></tr><tr>
    <td align='right'>".ss("Имя").": </td><td><input type='text' name='avtor' value='".$avtor."' class='main_mail_input' size='10'></td></tr><tr><td colspan='2' align='right'><input type='submit' name='ok' value='".ss("Подписаться на рассылку")."'></td></tr></table></form>";
  } else {
    // проверка наличия такого email в БД 
    $numrows = $db->sql_numrows($db->sql_query("SELECT `cid` from ".$prefix."_pages_comments where `mail`='$mail' and `num`='0'"));
    if ($numrows == 0) {
      $db->sql_query("INSERT INTO ".$prefix."_pages_comments ( `cid` , `num` , `avtor` , `mail` , `text` , `ip` , `data`, `drevo`, `adres`, `tel`, `active` ) VALUES ('', '0', '".mysql_real_escape_string($avtor)."', '".mysql_real_escape_string($mail)."', '', '$ip', '$now', '', '', '', '1')");
      $soderganie .= "<h2>".ss("Вы подписались на рассылку.")."</h2><h2> Спасибо!</h2>";
    } else {
      $soderganie .= "<h2>".ss("Вы уже подписаны на рассылку.")."</h2>";
    }
  }
  $soderganie .= "<br>".ss("Вы можете вернуться назад (нажав на клавиатуре клавишу &larr;BackSpace) или перейти")." <a href='/'>".ss("на Главную страницу")."</a>.";

  // Получаем дизайн для подписки
  global $newsmail_design;
  // Определение дизайна и использованных стилей в дизайне
  list($design_for_newsmail, $stil) = design_and_style($newsmail_design);
  if ($design_for_newsmail == "0") die(ss("Ошибка: «Адрес раздела»")." (".$name.") ".ss("введен неправильно. Перейдите на")." <a href=/>".ss("Главную страницу")."</a>.");

  $block = str_replace(aa("[содержание]"), $soderganie, $design_for_newsmail);
  return array($block, $stil);
?>