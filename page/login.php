<?php 
require_once('page/functions_users.php'); 
global $hash_padding, $soderganie, $prefix, $db, $design;
if (!$_POST['em'] || !$_POST['pa']) {
  $soderganie .= 'ОШИБКА - Отсутствует email или пароль.'; 
} else {
  $em = $_POST['em']; 
  $password = $_POST['pa'];
  $crypt_pwd = md5($password); 
  $a = $db->sql_numrows($db->sql_query("SELECT `email` FROM ".$prefix."_users WHERE email = '".$em."' AND password = '".$crypt_pwd."'"));
  if ($a != 1) { 
    $soderganie .= $em.'ОШИБКА - Пользователь не найден или пароль неверный'. $crypt_pwd; 
  } else {
    user_set_tokens($em);
    header("Location: --users_".$_COOKIE['user_id']); 
  } 
}
list($design_for_reg, $stil) = design_and_style($design);
if ($design_for_reg == "0") die("Ошибка: «Адрес раздела» (".$name.") введен неправильно. Перейдите на <a href=/>Главную страницу</a>.");
$block = str_replace("[содержание]",$soderganie,$design_for_reg);
return array($block, $stil);
?>