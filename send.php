<?php 
  require_once("mainfile.php");
  global $adminmail, $siteurl;
  if(empty($_POST['mail_to'])) die("Введите свой почтовый адрес");
  // Если поле выбора вложения не пустое - закачиваем его на сервер 
  $thm = filter($_POST['mail_subject'], "nohtml");
  $msg = filter($_POST['mail_msg'], "nohtml");
  $tel = filter($_POST['mail_tel'], "nohtml");
  if (strpos(" ".$msg, "[link") or strpos(" ".$msg, "[url") or strpos(" ".$msg, "[img")) {
    print "Попытка отправки спама.";   
    exit();   
  }
  if (filter_var($_POST['mail_to'], FILTER_VALIDATE_EMAIL)) {
    $mail_to = $_POST['mail_to'];
    send_mail($mail_to, $thm, $msg, '', $tel);
  } else die('Неправильный email');
  function send_mail($mail_to, $thema, $html, $path, $telefon) { 
  global $adminmail, $siteurl;

  if ($path != "") {
    $fp = fopen($path,"rb");   
    if (!$fp) { 
    print "Невозможно открыть фото... Возможные причины: ошибка на сервере, неправильный формат файла или слишком большой размер фотографии.";   
    exit();   
    }
    $file = fread($fp, filesize($path));   
    fclose($fp);   
    $name = $path;
    }
    //echo $path;
    //$path = explode("\\",$path);
    //$num = count($path) - 1;
    //$name = $path;
    //$name = "file.ext"; // в этой переменной надо сформировать имя файла (без всякого пути)  
    $EOL = "\r\n"; // ограничитель строк, некоторые почтовые сервера требуют \n - подобрать опытным путём
    $boundary     = "--".md5(uniqid(time()));  // любая строка, которой не будет ниже в потоке данных.  
    //if ($path != "") $headers    = "MIME-Version: 1.0;$EOL";   
    //if ($path != "") $headers   .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL
    //Content-Type: text/html; charset=utf-8$EOL";  
    //else 
    $headers   = "Content-Type: text/html; charset=utf-8$EOL";  
    $headers   .= "From: $mail_to$EOL";  
    //$headers .= "Bcc: $mail_to$EOL";
    /*
    if ($path != "") {
    $multipart  = "--$boundary$EOL";   
    $multipart .= "Content-Type: text/html; charset=utf-8$EOL";   
    $multipart .= "Content-Transfer-Encoding: base64$EOL";   
    $multipart .= $EOL; // раздел между заголовками и телом html-части 
    $multipart .= chunk_split(base64_encode($html));   
    $multipart .=  "$EOL--$boundary$EOL";   
    $multipart .= "Content-Type: application/octet-stream; name=\"$name\"$EOL";   
    $multipart .= "Content-Transfer-Encoding: base64$EOL";   
    $multipart .= "Content-Disposition: attachment; filename=\"$name\"$EOL";   
    $multipart .= $EOL; // раздел между заголовками и телом прикрепленного файла 
    $multipart .= chunk_split(base64_encode($file));   
    $multipart .= "$EOL--$boundary--$EOL";   
    } else {
    */
        //$multipart  = "--$boundary$EOL";   
    //$multipart .= "Content-Type: text/html; charset=utf-8$EOL";   
    //$multipart .= "Content-Transfer-Encoding: base64$EOL";   
    //$multipart .= $EOL; // раздел между заголовками и телом html-части 
    $multipart = str_replace($EOL,"<br>",$html);
    $multipart .= "<br>Ф.И.О.: ".$thema;
    $multipart .= "<br>Почта: ".$mail_to;
    if ($telefon) $multipart .= "<br>Телефон: ".$telefon;
    //'=?koi8-r?B?'.base64_encode(chunk_split(convert_cyr_string(      , "w","k"))).'?='
    //$multipart .= "$EOL--$boundary--$EOL"; 
    //}
    
if(!mail($adminmail, '=?koi8-r?B?'.base64_encode(convert_cyr_string("Письмо с сайта $siteurl: ".$thema, "w","k")).'?=', $multipart, $headers)) {
      echo "Не удалось отправить письмо. Попробуйте еще раз, нажав в браузере Назад.";            //если письмо не отправлено
      } else { //// если письмо отправлено
    echo "Письмо успешно отправлено. Спасибо!<br><a href=http://".$siteurl."/>Вернуться на Главную страницу</a>";  
    }
  exit;
  }
?>