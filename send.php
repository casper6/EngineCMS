<?php 
  require_once("mainfile.php");
  global $adminmail, $siteurl;
  if (trim($adminmail) == "") die("Email администратора не указан. Зайдите в администрирование &rarr; Настройки &rarr; Основные настройки Сайта &rarr; Почта администратора");
  $mail_to = $_POST['mail_to'];
  $tel = $_POST['mail_tel'];
  if(empty($mail_to) && empty($tel)) die("Введите свой почтовый адрес или телефон");
  $fio = filter($_POST['mail_subject'], "nohtml");
  $msg = filter($_POST['mail_msg'], "nohtml");
  $tel = filter($tel, "nohtml");
  if (strpos(" ".$msg, "[link") or strpos(" ".$msg, "[url") or strpos(" ".$msg, "[img")) die("Попытка отправки спама.");
  $content = str_replace("\r\n","<br>",$msg)."<br>Ф.И.О.: ".$fio."<br>Почта: ".$mail_to;
  $subject = "Письмо с сайта ".$siteurl;
  $from = "Администратор";
  if (!empty($tel)) $content .= "<br>Телефон: ".$tel;
  system_mes($content); // Отправляем системное сообщение админу
  if (preg_match('/^[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}](?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}-]*[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}])?\.)+[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}](?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}-]*[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}])?$/u', trim($mail_to))) 
    mail($adminmail, "=?utf-8?b?" . base64_encode($subject) . "?=", $content, "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: =?utf-8?b?" . base64_encode($from) . "?= <" . $adminmail . ">");
  echo "<h1>Письмо успешно отправлено. Спасибо! </h1><a href='http://".$siteurl."/'>Вернуться на Главную страницу</a>";  
?>