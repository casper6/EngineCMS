<?php
require_once('page/functions_users.php');
global $hash_padding, $soderganie, $prefix, $db, $design, $sitename, $adminmail, $siteurl;
$email = $_POST['em'];
if (validate_email($email)) { 
	$query = "SELECT `user_id` FROM ".$prefix."_users WHERE `email` = '".$email."'";
	$result2 = $db->sql_query($query); 
	if ($result2 && $db->sql_fetchrow($result2) > 0)
		$soderganie .= ss("ОШИБКА - адрес электронной почты уже существует.");
	else {
		$user_ip = $_SERVER['REMOTE_ADDR'];
		$hash = md5($email.$hash_padding);
		$result = $db->sql_query("SELECT `useit`, `text` FROM ".$prefix."_mainpage where `name`='config' and `type`='10'");
		while ($row = $db->sql_fetchrow($result)) { 
			if ($row['useit'] == 1) $regions = $_POST['id_regions'];
			$simvol_pass = $row['text'];
		}
		$result = $db->sql_query("SELECT `id` FROM ".$prefix."_mainpage where `name`!='config' and `type`='10'");
		$cnt = $db->sql_numrows($result);
		if ($cnt == 1) {
			$row = $db->sql_fetchrow($result); 
			$group = $row['id'];
		} else $group = filter($_POST['groups']);
		$password = generate_simvols($simvol_pass);
		$crypt_pwd = md5($password);
		$result = $db->sql_query("INSERT INTO ".$prefix."_users (`user_id`, `user_group`, `name`, `photo`, `password`, `email`, `remote_addr`, `confirm_hash`, `date_created`, `regions`) VALUES ('', '".$group."', '', '',  '".$crypt_pwd."', '".$email."', '".$user_ip."', '".$hash."', NOW(), '".$regions."')"); 
		if (!$result) $soderganie .= ss("ОШИБКА - Ошибка базы данных."); 
		else {
			$encoded_email = urlencode($_POST['em']);
			$from = ss("Администратор");
			$mail_body = ss("Спасибо за регистрацию на")." ".$sitename.".<br>".ss("Запомните и никому не передавайте ваш пароль")."<br>".ss("Пароль:")." ".$password."<br>".ss("Теперь вы можете войти на сайт")."<br>".$siteurl;
			mail($email, "=?utf-8?b?" . base64_encode($sitename) . "?=", $mail_body, "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: =?utf-8?b?" . base64_encode($from) . "?= <" . $adminmail . ">");
			$soderganie .= ss("Вы успешно зарегистрировались. Вы вскоре получите подтверждение по электронной почте."); 
		}
	}
} else {
	$soderganie .= ss("ОШИБКА: email введен неправильно.");
}
list($design_for_reg, $stil) = design_and_style($design);
if ($design_for_reg == "0") die(ss("Ошибка: «Адрес раздела»")." (".$name.") ".ss("введен неправильно. Перейдите на")." <a href=/>".ss("Главную страницу")."</a>.");
$block = str_replace(ss("[содержание]"), $soderganie, $design_for_reg);
return array($block, $stil);
?>