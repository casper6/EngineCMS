<?php
global $soderganie, $prefix, $db;
require_once('page/functions_users.php');
 
 if (!empty($_POST['submit'])) { 
  $feedback = user_register(); 
  // Обратная связь 
  $soderganie .= "<P class=\"errormess\">$feedback</P>"; 
} else { 
$result = $db->sql_query("SELECT count(*) as cnt FROM ".$prefix."_mainpage where `name`!='config' and `type`='10'");
while ($row = $db->sql_fetchrow($result)) {
$cnt = $row['cnt'];
  if ($cnt == 0) { $soderganie .= "<BR>Для регистрации необходима группа<BR>"; } else {
// Вывести форму на экран 
  $soderganie .= "<P CLASS=\"reg1\"><B>РЕГИСТРАЦИЯ</B><BR> 
Заполните эту форму и подтверждение о регистрации будет направлено вам на указанный email.  
Как только вы нажмете на ссылку в письме, ваша учетная запись будет подтверждена  
и вы cможете зайти на сайт.</P> 
<FORM CLASS=\"regforma\" ACTION=\"-register\" METHOD=\"POST\"> 
<P CLASS=\"regname\">Ваше имя<BR> <INPUT TYPE=\"TEXT\" NAME=\"name\" VALUE=\"\"></P> 
<P CLASS=\"regpass\">Пароль<BR> <INPUT TYPE=\"password\" NAME=\"password1\" VALUE=\"\"></P> 
<P CLASS=\"regpass\"><B>Пароль</B> (повторить)<BR> <INPUT TYPE=\"password\" NAME=\"password2\" VALUE=\"\"></P> 
<P CLASS=\"regmail\"><B>Email</B> (требуется для подтверждения регистрации)<BR><INPUT TYPE=\"TEXT\" NAME=\"email\" VALUE=\"\">";

if ($cnt > 1){
$soderganie .= "<BR>Выберите группу<BR><select name=\"groups\" class=\"groups\">";
$result = $db->sql_query("SELECT id, name  FROM ".$prefix."_mainpage where `name`!='config' and `type`='10'");
while ($row = $db->sql_fetchrow($result)) {
$soderganie .= "<option value=\"".$row['id']."\">".$row['name']."</option>";
}
$soderganie .= "</select>";
}
$result = $db->sql_query("SELECT useit FROM ".$prefix."_mainpage where `name`='config' and `type`='10'");
while ($row = $db->sql_fetchrow($result)) { 
if ($row['useit'] == 1){
$soderganie .= "<BR>Выберите ваше местоположение<BR>";
$soderganie .= regions();
}
}  
$soderganie .= "</P><P><INPUT TYPE=\"SUBMIT\" NAME=\"submit\" VALUE=\"Зарегистрироваться\"></P> 
</FORM>"; 
}  
}
}
    // Получаем дизайн для поиска
  global $design;
  // Определение дизайна и использованных стилей в дизайне
  list($design_for_reg, $stil) = design_and_style($design);
  if ($design_for_reg == "0") die("Ошибка: «Адрес раздела» (".$name.") введен неправильно. Перейдите на <a href=/>Главную страницу</a>.");
  $block = str_replace("[содержание]",$soderganie,$design_for_reg);
  return array($block, $stil);
?>