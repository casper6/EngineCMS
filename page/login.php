<?php 
require_once('page/functions_users.php'); 
global $hash_padding, $soderganie, $prefix, $db, $design, $sitename, $adminmail, $siteurl;
if ($_POST["submit"] == "Вспомнить пароль") {
if (!$_POST['em']) {
  $soderganie .= '<p class="errormes">ОШИБКА - Отсутствует email.</p>'; 
} else {
$em = $_POST['em'];
if (validate_email($em)) {
$a2 = $db->sql_numrows($db->sql_query("SELECT `email` FROM ".$prefix."_users WHERE email = '".$em."'"));
  if ($a2 != 1) { 
    $soderganie .= '<p class="errormes">ОШИБКА - Пользователь не найден, мы не можем выслать вам новый пароль</p>'; 
  } else {
$result = $db->sql_query("SELECT `text` FROM ".$prefix."_mainpage where `name`='config' and `type`='10'");
$row = $db->sql_fetchrow($result);
$simvol_pass = $row['text'];
$newpass = generate_simvols($simvol_pass);
$crypt_pwd2 = md5($newpass);
$db->sql_query("UPDATE ".$prefix."_users SET `password`='".$crypt_pwd2."' WHERE `email`='".$em."' ;"); 
$from = "Восстановление пароля";
$mail_body = "Восстановление пароля для ".$sitename.".<br>Запомните и никому не передавайте ваш пароль<br>Ваш новый пароль: ".$newpass;
			mail($em, "=?utf-8?b?" . base64_encode($sitename) . "?=", $mail_body, "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: =?utf-8?b?" . base64_encode($from) . "?= <" . $adminmail . ">");
			$soderganie .= '<p class="errormes">Проверьте почту, вам выслали новый пароль.</p>';
}} else {
	$soderganie .= '<p class="errormes">ОШИБКА: email введен неправильно.</p>';
}}} else {
if (!$_POST['em'] || !$_POST['pa']) {
  $soderganie .= '<p class="errormes">ОШИБКА - Отсутствует email или пароль.</p>'; 
} else {
  $em = $_POST['em']; 
  $password = $_POST['pa'];
  $crypt_pwd = md5($password); 
  $a = $db->sql_numrows($db->sql_query("SELECT `email` FROM ".$prefix."_users WHERE email = '".$em."' AND password = '".$crypt_pwd."'"));
  if ($a != 1) { 
    $soderganie .= '<p class="errormes">ОШИБКА - Пользователь не найден или пароль неверный</p>
	<br><p class="errormes">Если забыли пароль введите свой email для получения нового пароля</p>'; 
	$soderganie .= "<form class='regforma' action='--login' method='post'>
	<br><input class='regmail' type='email' name='em' value='".$em."'>
	<br><input type='submit' name='submit' value='Вспомнить пароль'></form>"; 
  } else {
    user_set_tokens($em);
    header("Location: --users_".$_COOKIE['user_id']); 
  } 
} }
list($design_for_reg, $stil) = design_and_style($design);
if ($design_for_reg == "0") die("Ошибка: «Адрес раздела» (".$name.") введен неправильно. Перейдите на <a href=/>Главную страницу</a>.");
$block = str_replace("[содержание]",$soderganie,$design_for_reg);
return array($block, $stil);
?>