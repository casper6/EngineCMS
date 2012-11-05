<?php 
require_once('page/functions_users.php'); 
global $hash_padding, $soderganie, $prefix, $db, $design;
 if (!$_POST['em'] || !$_POST['pa'] ) { 
    $soderganie .= 'ОШИБКА - Отсутствует email или пароль'; 
  } else {
    $em = $_POST['em']; 
    $password = $_POST['pa'];
    $crypt_pwd = md5($password); 
    $query = "SELECT count(*) as cnt FROM ".$prefix."_users WHERE email = '$em' AND password = '$crypt_pwd'"; 
    $result = $db->sql_query($query); 
	while ($row = $db->sql_fetchrow($result)) { 
    $a = $row['cnt'];
	}
    if ($a == 0){ 
      $soderganie .= $em.'ОШИБКА - Пользователь не найден или пароль неверный'. $crypt_pwd; 
    } elseif ($a == 1) { 
           user_set_tokens($em);
		   $hash = md5($em.$hash_padding);
           header("Location: --uzvers_".$hash); 
    } 
  }
list($design_for_reg, $stil) = design_and_style($design); if ($design_for_reg == "0") die("Ошибка: «Адрес раздела» (".$name.") введен неправильно. Перейдите на <a href=/>Главную страницу</a>.");
$block = str_replace("[содержание]",$soderganie,$design_for_reg); return array($block, $stil);
?>