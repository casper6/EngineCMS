<?php
  ###################################################### Страница ошибок
  $code = intval($_REQUEST['code']);
  if ($code == "404") {
    header("HTTP/1.0 404 Not Found");
    die("<h1 style='color:red;'>Ошибка ".$code."</h1>
    <center style='margin-top:40px;'><img src=/images/icon_no.png> <b>Запрашиваемая страница не существует.</b><br>Она была удалена, отключена или никогда и не создавалась.<br><br>Вы можете перейти на <a href='/''>Главную страницу</a> <br>или попробовать найти нужную информацию на сайте<br>с помощью быстрого поиска: <form method=POST action=\"/--search\" style='display:inline;' class=main_search_form>
    <input type=search name=slovo class=main_search_input><input type='submit' name='ok' value='Найти' class='main_search_button'>
    </form><br><img src='/images/404.jpg'></center>");
  }
  if ($code == "403") {
    header("HTTP/1.0 403 Forbidden");
    die("<h1 style='color:red;'>Ошибка 403</h1>
    <center style='margin-top:40px;'>Вы можете перейти на <a href='/''>Главную страницу</a> <br>или попробовать найти нужную информацию на сайте<br>с помощью быстрого поиска: <form method=POST action=\"/--search\" style='display:inline;' class=main_search_form>
    <input type=search name=slovo class=main_search_input><input type='submit' name='ok' value='Найти' class='main_search_button'>
    </form><br><img src='/images/404.jpg'></center>");
  }
  if ($code == "500") {
    header("HTTP/1.0 500 Server Error");
    die("<h1 style='color:red;'>Ошибка ".$code."</h1>
    <center style='margin-top:40px;'><img src=/images/icon_no.png>Сейчас мы работаем над устранением проблемы сервера или проводим профилактику, просим вернуться на сайт немного позже.<br><img src='/images/404.jpg'></center>");
  }
  if ($code == "401") {
    header("HTTP/1.0 401 Unauthorized");
    die("<h1 style='color:red;'>Ошибка ".$code."</h1>
    <center style='margin-top:40px;'><img src=/images/icon_no.png>Вы не авторизованы на сайте.<br><img src='/images/404.jpg'></center>");
  }
  if ($code == "666") {
    header("HTTP/1.0 404 Not Found");
    die("<h1 style='color:red;'>Ошибка</h1>
    <center style='margin-top:40px;'><img src=/images/icon_no.png> <b>Запрашиваемая страница не существует.</b><br>Вероятно, что раздел сайта удален или адрес раздела был введен неправильно (к примеру, с использованием русских букв или пробелов).<br><br>Вы можете перейти на <a href='/''>Главную страницу</a> <br>или попробовать найти нужную информацию на сайте<br>с помощью быстрого поиска: <form method=POST action=\"/--search\" style='display:inline;' class=main_search_form>
    <input type=search name=slovo class=main_search_input><input type='submit' name='ok' value='Найти' class='main_search_button'>
    </form><br><img src='/images/404.jpg'></center>");
  }
    if ($code == "123") {
    header("HTTP/1.0 404 Not Found");
    die("<h1 style='color:red;'>Ошибка ".$code."</h1>
    <center style='margin-top:40px;'><img src=/images/icon_no.png> <b>Доступ запрещенн.</b><br>Вероятно, что адрес раздела был введен неправильно (к примеру, с использованием русских букв).<br><br>Вы можете перейти на <a href='/''>Главную страницу</a> <br>или попробовать найти нужную информацию на сайте<br>с помощью быстрого поиска: <form method=POST action=\"/--search\" style='display:inline;' class=main_search_form>
    <input type=search name=slovo class=main_search_input><input type='submit' name='ok' value='Найти' class='main_search_button'>
    </form><br><img src='/images/404.jpg'></center>");
  }
?>