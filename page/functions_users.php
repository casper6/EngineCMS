<?php
$supersecret_hash_padding = 'hvgjkfdhjfdhuhbutbj';
 // Дизайн модуля регистрации
$result = $db->sql_query("select title from ".$prefix."_mainpage where `type`='10' and `name`='config'");
while ($row = $db->sql_fetchrow($result)) {
  $design = intval($row['title']);
}

function user_register() {
  global $supersecret_hash_padding, $db, $prefix;
  $result = $db->sql_query("SELECT useit, text FROM ".$prefix."_mainpage where `name`='config' and `type`='10'");
  while ($row = $db->sql_fetchrow($result)) {
    // Проверяем данные от пользователя на соответствие заданным параметрам, условиям. 
    if (strlen($_POST['name']) <= 25 && strlen($_POST['password1']) <= $row['text'] && 
    ($_POST['password1'] == $_POST['password2']) && 
    strlen($_POST['email']) <= 150 && validate_email($_POST['email'])) { 
      // Проверка имени пользователя и пароля
  	  $result = $db->sql_query("select * from ".$prefix."_mainpage where `type`='10' and `name`='config'");
  		while ($row = $db->sql_fetchrow($result)) {
    		$cat = $row['text'];
        if (account_namevalid($_POST['name']) || strlen($_POST['password1'] >= $cat)) {
          $user_name = strtolower($_POST['name']); 
          $user_name = trim($user_name); 
          $email= $_POST['email']; 
          // Сопоставление логина и email, заявленных новым пользователем, с уже имеющимися в БД 
          // В БД не должно найтись совпадений, ни логину не email. 
          $query = "SELECT user_id FROM ".$prefix."_users WHERE email = '$email'"; 
          $result2 = $db->sql_query($query); 
          if ($result2 && $db->sql_fetchrow($result2) > 0) { 
            $feedback = 'ОШИБКА - адрес электронной почты уже существует'; 
            return $feedback; 
          } else {
            $password = md5($_POST['password1']); 
            $user_ip = $_SERVER['REMOTE_ADDR']; 
            // Создаём новый хэш для вставки в БД и подтверждение по электронной почте 
            $hash = md5($email.$supersecret_hash_padding); 
            if ($row['useit'] == 1) $regions = $_POST['id_regions'];
            $result = $db->sql_query("SELECT `id` FROM ".$prefix."_mainpage where `name`!='config' and `type`='10'");
            $cnt = $db->sql_numrows($result);
            if ($cnt == 1) {
              $row = $db->sql_fetchrow($result);
              $group = $row['id'];
            } else $group = filter($_POST['groups'], "nohtml");
            $result = $db->sql_query("INSERT INTO user (user_id, user_group, name, photo,  password, email, remote_addr, confirm_hash, is_confirmed, date_created, regions) VALUES ('', '$group', '$user_name', '$photo',  '$password', '$email', '$user_ip', '$hash', 0, NOW(), '$regions')"); 
            if (!$result) { 
              $feedback = 'ОШИБКА - Ошибка базы данных'; 
              return $feedback;
            } else {
              // Отправить подтверждение по электронной почте 
              $encoded_email = urlencode($_POST['email']); 
              $mail_body = "Спасибо за регистрацию на LPHP.RU. Щелкните по этой ссылке для подтверждения регистрации: 
              http://localhost/confirm.php?hash=$hash&email=$encoded_email
              Как только вы увидите подтверждающее сообщение, вы будете зарегистрированы в LPHP.RU"; 
              mail ($email, 'LPHP.RU registration Confirmation', $mail_body, 'From: noreply@example.com');
              $feedback = 'Вы успешно зарегистрировались. Вы вскоре получите подтверждение по электронной почте'; 
              return $feedback; 
            } 
          } 
        } else {
          $feedback =  'ОШИБКА: Имя пользователя или пароль являются недопустимыми'; 
          return $feedback; 
        } 
  	  }
    } else {
      $feedback = 'ОШИБКА: Пожалуйста, заполните все поля правильно'; 
      return $feedback; 
    }
  }
} 
function account_namevalid() {
  // должны иметь по крайней мере один символ 
  if (strspn($_POST['name'], "йцукенгшщзхъфывапролджэячсмитьбюёЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮЁ") == 0) return false;
  // должна содержать все допустимые символы 
  if (strspn($_POST['name'], "йцукенгшщзхъфывапролджэячсмитьбюёЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮЁ") != strlen($_POST['name'])) return false;
  // минимальная и максимальная длина 
  if (strlen($_POST['name']) < 3) return false;
  if (strlen($_POST['name']) > 50) return false;
  // Запрещённый логины для регистрации // доделать
  if (eregi("^((root)|(bin)|(daemon)|(adm)|(lp)|(sync)|(shutdown)|(halt)|(mail)|(news)|(uucp)|(operator)|(games)|(mysql)|(httpd)|(nobody)|(dummy)|(www)|(cvs)|(shell)|(ftp)|(irc)|(debian)|(ns)|(download)|(new)|(ad)|(admin)|(administrator)|(админ)|(администратор))$", $_POST['name'])) return false;
  if (eregi("^(anoncvs_)", $_POST['name'])) return false;
  return true; 
}


?>