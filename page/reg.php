<?php
  global $soderganie, $prefix, $db, $design;
  if (!empty($_POST['submit'])) {
    require_once('page/functions_users.php');
    $feedback = user_register(); 
    $soderganie .= "<div class='errormess'>".$feedback."</div>"; // Обратная связь 
  }
  // Определение дизайна и использованных стилей в дизайне
  list($design_for_reg, $stil) = design_and_style($design);
  if ($design_for_reg == "0") die("Ошибка: «Адрес раздела» (".$name.") введен неправильно. Перейдите на <a href=/>Главную страницу</a>.");
  $block = str_replace("[содержание]",$soderganie,$design_for_reg);
  return array($block, $stil);
?>