<?php
require_once("mainfile.php");
###################################################### Страница ошибок
$search_form = "<form method='POST' action='search' style='display:inline;' class='main_search_form'><input type=search name='slovo' class='main_search_input'><input type='submit' name='ok' value='".ss("Найти")."' class='main_search_button'></form><br>";
$error404 = "<img src='/images/404.jpg'>";
$code = intval($_REQUEST['code']);
if ($code == "404") {
  header("HTTP/1.0 404 Not Found");
  die("<h1 style='color:red;'>".ss("Ошибка")." ".$code."</h1>
  <center style='margin-top:40px;'><b>".ss("Запрашиваемая страница не существует.</b><br>Она была удалена, отключена или никогда не создавалась.<br><br>Вы можете перейти на <a href='/''>Главную страницу</a> <br>или попробовать найти нужную информацию<br>через поиск по сайту:").$search_form.$error404."</center>");
}
if ($code == "403") {
  header("HTTP/1.0 403 Forbidden");
  die("<h1 style='color:red;'>".ss("Ошибка")." ".$code."</h1>
  <center style='margin-top:40px;'>".ss("Вы можете перейти на <a href='/''>Главную страницу</a> <br>или попробовать найти нужную информацию<br>через поиск по сайту:").$search_form.$error404."</center>");
}
if ($code == "500") {
  header("HTTP/1.0 500 Server Error");
  die("<h1 style='color:red;'>".ss("Ошибка")." ".$code."</h1>
  <center style='margin-top:40px;'><img src=/images/icon_no.png>".ss("Сейчас мы работаем над устранением проблемы сервера или проводим профилактику, просим вернуться на сайт немного позже.")."<br>".$error404."</center>");
}
if ($code == "401") {
  header("HTTP/1.0 401 Unauthorized");
  die("<h1 style='color:red;'>".ss("Ошибка")." ".$code."</h1>
  <center style='margin-top:40px;'><img src=/images/icon_no.png>".ss("Вы не авторизованы на сайте.")."<br>".$error404."</center>");
}
if ($code == "666") {
  header("HTTP/1.0 404 Not Found");
  die("<h1 style='color:red;'>".ss("Ошибка")."</h1>
  <center style='margin-top:40px;'><b>".ss("Запрашиваемая страница не существует.</b><br>Вероятно, что раздел сайта удален или адрес раздела был введен неправильно (к примеру, с использованием русских букв или пробелов).<br><br>Вы можете перейти на <a href='/''>Главную страницу</a> <br>или попробовать найти нужную информацию<br>через поиск по сайту:").$search_form.$error404."</center>");
}
if ($code == "123") {
  header("HTTP/1.0 404 Not Found");
  die("<h1 style='color:red;'>".ss("Ошибка")." ".$code."</h1>
  <center style='margin-top:40px;'><b>".ss("Доступ запрещен.</b><br>Вероятно, что адрес раздела был введен неправильно (к примеру, с использованием русских букв).<br><br>Вы можете перейти на <a href='/''>Главную страницу</a> <br>или попробовать найти нужную информацию<br>через поиск по сайту:").$search_form.$error404."</center>");
}
?>