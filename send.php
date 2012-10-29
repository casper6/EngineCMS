<?php 
  require_once("mainfile.php");
  global $adminmail, $siteurl;
  $mail_to = $_POST['mail_to'];
  if(empty($mail_to)) die("Введите свой почтовый адрес");
  $fio = filter($_POST['mail_subject'], "nohtml");
  $msg = filter($_POST['mail_msg'], "nohtml");
  $tel = filter($_POST['mail_tel'], "nohtml");
  if (strpos(" ".$msg, "[link") or strpos(" ".$msg, "[url") or strpos(" ".$msg, "[img")) die("Попытка отправки спама.");
  if (preg_match('/^[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}](?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}-]*[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}])?\.)+[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}](?:[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}-]*[a-zA-Z0-9а-яА-ЯёЁ\x{0600}-\x{06FF}])?$/u', $mail_to)) {
    //$headers   = "Content-Type: text/html; charset=utf-8\r\nFrom: ".$mail_to."\r\n";  
    $content = str_replace("\r\n","<br>",$msg)."<br>Ф.И.О.: ".$fio."<br>Почта: ".$mail_to;
    $subject = "Письмо с сайта ".$siteurl;
    $from = "Администратор";
    if (!empty($tel)) $multipart .= "<br>Телефон: ".$tel;
    if(!mail($adminmail, "=?utf-8?b?" . base64_encode($subject) . "?=", $content, "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: =?utf-8?b?" . base64_encode($from) . "?= <" . $adminmail . ">") echo "Не удалось отправить письмо. Попробуйте еще раз, нажав в браузере Назад.";
    else echo "<h1>Письмо успешно отправлено. Спасибо!</h1><a href='http://".$siteurl."/'>Вернуться на Главную страницу</a>";  
      //mail($adminmail, '=?koi8-r?B?'.base64_encode(convert_cyr_string("Письмо с сайта $siteurl: ".$thema, "w","k")).'?=', $multipart, $headers)) 
  } else die('Неправильный email');
?>