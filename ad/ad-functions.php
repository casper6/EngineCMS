<?php
##########################################################################################
function page_admin($txt, $pid) { // добавление функций админа к странице сайта
  global $db, $prefix, $module_name, $url, $name;
  if ( $pid > 0 ) $red = "
<script>
function delpage(id) {
  $.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : 'html',
      data: {'func': 'delpage', 'id': id}
  });
  $('#redact').html('Страница удалена в Корзину.');
}
</script>
<a class=ad_button target=_blank href='/sys.php?op=base_pages_edit_page&amp;pid=".$pid."'><img class='ad_icon' src='/images/sys/edit.png'>Редактировать страницу <nobr>в редакторе</nobr></a><a class=ad_button target=_blank href='javascript:delpage(".$pid.");'><img class='ad_icon' src='/images/sys/trash_fill.png'>Удалить страницу <nobr>в Корзину</nobr></a>"; 
  elseif ( $pid == 0 and $module_name != "" ) {
    // выяснить id
    $sql55="SELECT `id` from ".$prefix."_mainpage where `tables`='pages' and `type`='2' and (`name` = '".$name."' or `name` like '".$name." %')";
    $result55 = $db->sql_query($sql55);
    $row55 = $db->sql_fetchrow($result55);
    $name_id = $row55['id'];
    $red = "<a class=ad_button target=_blank href='/sys.php?op=mainpage&amp;id=".$name_id."'><img class='ad_icon' src='/images/sys/edit.png'>Редактировать <nobr>главную стр.</nobr> раздела</a><a class=ad_button target=_blank href='sys.php?op=base_pages_add_page&amp;name=".$name."#1'><img class='ad_icon' src='/images/sys/plus.png'>Добавить <nobr>новую страницу</nobr> в раздел</a>";
  }
  elseif ( $module_name == "" ) $red = "<a class=ad_button target=_blank href='/sys.php?op=mainpage&amp;id=24'><img class='ad_icon' src='/images/sys/edit.png'>Редактировать Главную страницу</a><a class=ad_button target=_blank href='sys.php?op=base_pages_add_page#1'><img class='ad_icon' src='/images/sys/plus.png'>Добавить <nobr>новую страницу</nobr> на сайт</a>";
  else $red = "";
  $url = getenv("REQUEST_URI");
  $txt = str_replace("</body>","<div id='redact_show' style='position:absolute; top:10px; floaf:right; right:20px; z-index:3000; width:18px;'><a title='Показать настройки администратора' href=# style='cursor:pointer;' onclick=\"show('redact'); show('redact_show');\"><img src='/images/sys/edit.png' width=16></a></div><div id='redact' style='background: white; color: black; position:absolute; top:5px; display:none; floaf:right; right:20px; z-index:3000; width:560px;' class=show_block><div class=show_block_title><a title='Скрыть настройки администратора' href=# style='cursor:pointer;' onclick=\"show('redact_show'); show('redact');\"><img src='/images/sys/x_alt.png' width=16 align=right></a>Настройки администратора</div>".$red."<form method=post name=blocks_show action='".$url."' style='display:inline;'><input type='hidden' name=blocks value='1'><a class=ad_button href='javascript:document.blocks_show.submit();' title='Показать редактирование блоков на странице'><img class='ad_icon' src='/images/sys/new_window.png'>Показать блоки <nobr>на странице</nobr></a></form><a class=ad_button href='/sys.php?op=base_pages_re&amp;link=".$url."'><img class='ad_icon' src='/images/sys/reload_alt.png'>Обновить (перезагрузить) страницу</a><a class=ad_button target=_blank href='/red'><img class='ad_icon' src='/images/sys/cog.png'>Открыть редактирование сайта</a></div></body>",$txt);
  return $txt;
}
///////////////////////////////////////////////////////////////

function del_spiski($page_id, $type) { // Стираем упоминания страницы во всех элементах списка
  global $prefix, $db;
  $sql = "SELECT `id`, `pages` FROM ".$prefix."_spiski WHERE `type`='".$type."' and `pages` like '% ".$page_id." %'";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $del_id = $row['id'];
    $del_pages = " ".trim(str_replace("  "," ",str_replace(" ".$page_id." "," ",$row['pages'])))." ";
    if (trim($del_pages)=="") $del_pages=""; // Стираем пустые страницы
    $db->sql_query("UPDATE ".$prefix."_spiski SET pages='".$del_pages."' WHERE id='".$del_id."'") or die('Ошибка: Не удалось стереть всю ранее введенную в списки информацию.');
  }
}

///////////////////////////////////////////////////////////////
function admin_footer() { // проверить вызов
  if ( stristr($_SERVER['HTTP_USER_AGENT'], 'Firefox') or stristr($_SERVER['HTTP_USER_AGENT'], 'Chrome') or stristr($_SERVER['HTTP_USER_AGENT'], 'Safari') ) {} else echo "<span class='gray noprint'>Для администрирования сайта желательно использовать браузер <a href='https://www.google.com/chrome?hl=ru' target='_blank'>Google Chrome</a>, <a href='http://www.apple.com/ru/safari/' target='_blank'>Apple Safari</a> или <a href='http://www.mozilla.org/ru/firefox/' target='_blank'>Mozilla Firefox</a>. <a href='http://www.whatbrowser.org/ru/' target='_blank'>Что такое браузер?</a></span></div></body>\n</html>";
  //ob_end_flush();
  die();
}
///////////////////////////////////////////////////////////////
function scandirectory($dir, $echoscan="", $images) { // переделать
  global $echoscan;
        $level = substr_count($dir,"/");
        for($line="", $counter=0; $counter<$level; ++$counter, $line.='--'){}
        $dir_res = opendir($dir);
        while($file = readdir($dir_res)) {
                if($file!=='.' && $file!=='..') {
                        if(is_dir($dir."/".$file)) {
                          scandirectory($dir."/".$file, $echoscan);
                        } else {
                          if (!strpos(" ".$dir."/".$file,"spaw2/uploads/images/icons/") and !strpos(" ".$dir."/".$file,"spaw2/uploads/_thumbs") and (strpos($file,"gif") or strpos($file,"png") or strpos($file,"jpg") or strpos($file,"jpeg") or strpos($file,"GIF") or strpos($file,"PNG") or strpos($file,"JPG") or strpos($file,"JPEG") )) $echoscan .= $dir."/".$file."@";
                        }
                }      
        }
  return $echoscan;
}
/////////////////////////////////////////////////////////////// Проверить вызов
function foto_find($content) { // Поиск фотографий на странице
  global $http_siteurl;
  $info = '';
  preg_match_all("/(?<=url\()[a-zA-Z.\/]*(?=\))/i", $content, $matches);
  foreach($matches[0] as $item) {
    $item = trim($item);
    if ($item != "") $info[] = $item;
  }
  preg_match_all("#\s(?:href|src|url)=(?:[\"\'])?(.*?)(?:[\"\'])?(?:[\s\>])#i", $content, $matches);
  foreach($matches[0] as $item) { 
    if (strpos(" ".$item,"includes/php_thumb")) {
      $item = explode("&", str_replace("/includes/php_thumb/php_thumb.php?","",$item));
      $item = $item[0];
    }
    $item = str_replace($http_siteurl."/","",$item);
    if (!strpos(" ".$item,"http:") and ( strpos($item,"gif") or strpos($item,"png") or strpos($item,"jpg") or strpos($item,"jpeg") or strpos($item,"GIF") or strpos($item,"PNG") or strpos($item,"JPG") or strpos($item,"JPEG") )) {
      $item = trim(str_replace("uploadsimages","uploads/images",str_replace("/images/","images/",str_replace("/img/","img/",str_replace("/spaw2/","spaw2/",str_replace(".jpg/",".jpg",str_replace(".gif/",".gif",str_replace("\\","",str_replace("'","",str_replace("\"","",str_replace(">","",str_replace("href=","",str_replace("src=","",$item)))))))))))));

      if ($item != "") $info[] = $item;
    }
  }
  return $info;
}
/////////////////////////////////////////////////////////////// Проверить вызов
function show_cids($cid_papka, $cids=array()) { // получим cid вложенных папок
  global $prefix, $db;
  $sql = "SELECT cid from ".$prefix."_pages_categories where parent_id='".$cid_papka."'";
  $result = $db->sql_query($sql);
  if ($db->sql_numrows($result) > 0) {
    while ($row = $db->sql_fetchrow($result)) {
      $cid = $row['cid'];
      $cids[] = $cid;
      $cids += show_cids($cid,$cids);
    }
  }
  return $cids;
}
/////////////////////////////////////////////////////////////// 
function titles_design() { // Дизайн: список
    global $prefix, $db;
    $titles_design = array();
    $sqlX = "SELECT `id`,`title` from ".$prefix."_mainpage where `type`='0'";
    $resultX = $db->sql_query($sqlX);
    while ($rowX = $db->sql_fetchrow($resultX)) {
      $idX = $rowX['id'];
      $titles_design[$idX] = $rowX['title'];
    }
    return $titles_design;
}
/////////////////////////////////////////////////////////////// 
function help_autoblocks() {
  return "[заголовок] - заголовок открытого раздела можно выводить в любом месте дизайна<br>
    [заголовок-ссылка] - стандартный заголовок раздела<br>
    [название папки] - имя открытой папки<br>
    [твиттер] - кнопка «Твитнуть»<br>
    [лого_проекта] - логотип проекта, настраивается в Настройках сайта<br>
    [название_проекта] - название проекта, настраивается в Настройках сайта<br>
    [название_лого_проекта] - логотип + название — через замену картинкой тега H1<br>
    [поиск] - поиск по всему сайту, по всем страницам всех разделов<br>
    [год] - выводит промежуток между начальным и текущим годами существования сайта, например: © 2007-2012 или © 2012<br>
    [почта] - выводит ссылку на почтовую форму \"Написать нам\".<br>
    [день] - выводит сегодняшний день в формате \"3 Января 2009\".<br>
    [время] - выводит текущее время.<br>
    [rss] - выводит ссылку на RSS с картинкой 16х16 пикселей (другие варианты: [rss32] [rss50], [rss100], [rss128], [rss150]).<br>
    Прямая ссылка на RSS: /rss<br>
    [список h2 div], [список h3 div], [список h2 table], [список h3 table], [список h2 p], [список h3 p], [список h2 blockquote], [список h3 blockquote] – автоматически создают раскрывающиеся списки из заголовков (h2, h3) и следующей за ними информации, содержащейся в тегах «p», «table», «div» и «blockquote», соответственно.
    <hr>";
}
///////////////////////////////////////////////////////////////
function help_design() {
  return "<a class='button small' onclick='$(\"#show_blocks\").toggle();'>Справка по блокам для вставки в дизайн</a><div id='show_blocks' class='hide'><b>Созданные блоки</b> (посмотреть их принадлежность вы можете во вкладке Оформление->Блоки):<br>".block_names()."<hr><b>Автоматические блоки</b>:<br>[содержание] - содержание раздела<br>[нумерация] - вывод нумерации раздела (если в настройках раздела выбрано такое отображение нумерации)<br>".help_autoblocks()."</div>";
}
/////////////////////////////////////////////////////////////// 
function help_shablon() {
  global $db, $prefix;
  // Перечислим все базы данных
  $bases = ""; // Выборка разделов
  $sql2 = "select name, title, text from ".$prefix."_mainpage where `tables`='pages' and type='5'";
  $result2 = $db->sql_query($sql2);
  while ($row2 = $db->sql_fetchrow($result2)) {
      $base_name = $row2['name'];
      $base_title = $row2['title'];
      $base_text = $row2['text']; // Необходимо разобрать и получить имена!
      // Перечислим все их поля
      $sql3 = "SHOW COLUMNS FROM ".$prefix."_base_".$base_name."";
      $result3 = $db->sql_query($sql3);
      $rowsX = "";
      while ($row3 = mysql_fetch_assoc($result3)) {
        $rowsX .= "[".$base_name."_".$row3['Field']."] — <br>"; // [Field] [Type] [Null] [Key] [Default] [Extra]
      }
      $add = "[подробнее]"; //if (strpos($base_text, "type=3")) $add .= " [добавить в корзину]";
      $bases .= "<option value='".$rowsX.$add."'>Вставки для базы данных \"".$base_title."\"</option>";
  }
  return "<h2>Справка:</h2> <select class='w100' name=shablon_var2 onchange=\"$('#shablon_var').html(this.value); $('#shablon_var').show(); $('#show_shablon_var').show();\">
    <option value='Выберите другой объект из того же списка'>Выберите объект для шаблона</option>

    <option value='<b>Созданные блоки</b> (посмотреть их принадлежность вы можете во вкладке Оформление->Блоки):<br>".block_names()."<hr><b>Автоматические блоки</b> (не предназначены для шаблонов, но могут быть использованы):<br>
    ".help_autoblocks()."'>Все блоки</option>

    <option value='<b>Вставки для шаблона раздела</b> (не подходят для других шаблонов):<br>
    [page_id] — Идентификационный уникальный номер (ID) страницы<br>
    [page_num] — Количество страниц<br>
    [page_razdel] — Англ. наименование раздела страницы<br>
    [page_link_title_h1] — Ссылка на страницу с H1, при отсутствии содержания — просто её название в H1, без ссылки<br>
    [page_link_title] — Ссылка на страницу<br>
    [page_link] — Адрес страницы, вида: /-РАЗДЕЛ_page_НОМЕР-СТРАНИЦЫ<br>
    [page_title] — Название страницы (заголовок)<br>
    [page_opentext] — Предисловие (начальный текст)<br>
    [page_text] — Содержание (основной текст)<br>
    [page_data] — Дата создания страницы<br>
    [all_page_data] — Дата создания в DIV-обрамлении с иконкой<br>
    [page_counter] — Количество посещений страницы<br>
    [all_page_counter] — Количество посещений в DIV-обрамлении с иконкой<br>
    [page_comments] — Количество комментариев страницы<br>
    [page_comments_word] — слово «комментария» после числа комментариев, с правильным окончанием<br>
    [all_page_comments] — Количество комментариев в DIV-обрамлении с иконкой<br>
    [cat_id] — Идентификационный уникальный номер (ID) папки, 0 — если корень раздела<br>
    [cat_name] — Название папки, в которой лежит страница, НИЧЕГО — если страница в корне<br>
    [cat_link] — Адрес папки, вида: /-РАЗДЕЛ_cat_НОМЕР-ПАПКИ<br>
    [all_cat_link] — Ссылка на папку в DIV-обрамлении с иконкой<br>
    [page_active] — Открытость страницы, варианты значений: «Открытая информация», «Информация ожидает проверки», «Информация ожидает проверки администратора» или «Доступ к странице ограничен»<br>
    [page_golos] — Если голосование за страницу не «5 звезд» — выводит лишь оценку<br>
    [cat_golos] — Вывод любого выбранного голосования<br>
    [page_search] — Перечень тегов страницы (без ссылок)<br>
    [page_tags]— Перечень тегов-ссылок<br>
    [page_on_mainpage] — Если страница отмечена для Главной - выводит слово page_favourite, которое можно использовать для класса, изменяющего внешний вид выбранной страницы<br>
    [page_rss] — Красный или Зеленый значок RSS — доступность этой страницы через RSS<br>

    <br>Если в настройках Раздела выбран <b>«Тип раздела» — «анкеты-рейтинги»</b>:<br>
    [sred_golos] — Средний балл голосования<br>
    [all_golos] — Всего человек голосовало<br>
    [plus_golos] — Сколько положительных голосов<br>
    [neo_golos] — Сколько нейтральных голосов<br>
    [minus_golos] — Сколько негативных голосов<br>
    [active_color] — Цвет ячейки таблицы, вида: « style=\"background-color: #f1f1f1;\"»<br>

    <br><b class=red>В разработке</b>:<br>
    [page_foto_adres] — <br>
    [page_foto] — <br>
    [page_price] — <hr>
    '>Вставки для шаблона раздела</option>

    <option value='Вставки для шаблона <b>страниц</b>:<br>
    [page_id] — Идентификационный уникальный номер (ID) страницы<br>
    [main_title] — Заголовок раздела, список папок<br>
    [page_title] — Название страницы (заголовок)<br>
    [page_opentext] — Предисловие (начальный текст)<br>
    [page_text] — Содержание (основной текст)<br>
    [page_data] — Дата создания страницы<br>
    [page_tags] — Перечень тегов-ссылок<br>
    [page_on_mainpage] — Если страница отмечена для Главной - выводит слово page_favourite, которое можно использовать для класса, изменяющего внешний вид выбранной страницы<br>
    [page_favorites] — Ссылки на добавление страницы в социальные закладки<br>
    [page_socialnetwork] — Ссылки на добавление страницы в социальные сети<br>
    [page_blog] — Раскрывающийся текстовый блок с текстом названия и предисловия — для вставки на блоги<br>
    [venzel] — DIV-Разделитель с css-классом venzel<br>
    [page_search_news] — Схожие по тематике страницы<br>
    [page_reiting] — Вывод любого выбранного голосования<br>
    [page_comments] — Вывод комментариев страницы<br>
    [page_add_comments] — Форма добавления комментариев<br>
    Возможны дополнительные вставки — это англ. названия полей, относящихся к этому же разделу.'>Вставки для шаблона страниц</option>

    <option value='Вставки для шаблона <b>комментариев на странице</b>:<br>
    [comment_otvet] — Ссылка «Ответить»<br>
    [comment_otvet_show] — Ссылка «Раскрыть ответ» для свернутых веток комментариев (настраивается в разделе)<br>
    [comment_id] — Идентификационный уникальный номер (ID) комментария<br>
    [comment_num] — Порядковый последовательный номер комментария<br>
    [comment_text] — Содержание комментария<br>
    [comment_avtor_type] — Тип автора. На данный момент = Гость<br>
    [comment_avtor] — Автор комментария<br>
    [comment_data] — Дата написания комментария<br>
    [comment_time] — Время написания комментария<br>
    [comment_admin] — Кнопки редактирования и удаления комментария (видны только администратору)<br>
    [comment_mail] — Email автора комментария<br>
    [comment_tel] — Телефон автора комментария<br>
    [comment_adres]— Адрес автора комментария<br>
    '>Вставки для шаблона комментариев на странице</option>

    <option value='Вставки для шаблона <b>блока страниц</b>:<br>
    [number] - порядковый номер с 1 до ...<br>
    [page_id] — Идентификационный уникальный номер (ID) страницы<br>
    [page_razdel] — Англ. наименование раздела страницы<br>
    [cat_id] — Идентификационный уникальный номер (ID) папки, 0 — если корень раздела<br>
    [cat_name] — Название папки, в которой лежит страница, НИЧЕГО — если страница в корне<br>
    [page_title] — Название страницы (заголовок)<br>
    [page_link] — Адрес страницы, вида: /-РАЗДЕЛ_page_НОМЕР-СТРАНИЦЫ<br>
    [page_link_title] — Ссылка на страницу<br>
    [page_opentext] — Предисловие (начальный текст)<br>
    [page_text] — Содержание (основной текст)<br>
    [page_data] — Дата создания страницы<br>
    [page_counter] — Количество посещений страницы<br>
    [page_active] — Открытость страницы, варианты значений: «Открытая информация», «Информация ожидает проверки», «Информация ожидает проверки администратора»<br>
    [page_reiting] — Выводит оценку голосования<br>
    [page_comments] — Количество комментариев страницы<br>
    [page_tags] — Перечень тегов страницы (без ссылок)<br>
    [page_on_mainpage] — Если страница отмечена для Главной - выводит слово page_favourite, которое можно использовать для класса, изменяющего внешний вид выбранной страницы<br>
    [page_rss] — Красный или Зеленый значок RSS — доступность этой страницы через RSS<br>
    [opentext_no_photo] – Из Предисловия страницы вырезаны все фотографии<br>
    [opentext_no_html] – Из Предисловия страницы вырезаны все теги HTML, кроме списков (ul, ol, li)<br>
    [photo_address] — Адрес первого фото в предисловии<br>
    [photo] — Первое фото в предисловии<br>
    <hr>
    '>Вставки для шаблона блока страниц</option>

    <option value='Вставки для шаблона <b>блока папок открытого раздела</b>:<br>
    [cat_id] — Идентификационный уникальный номер (ID) папки<br>
    [cat_name] — Название папки<br>
    [cat_link] — Адрес папки, вида: /-РАЗДЕЛ_cat_НОМЕР-ПАПКИ<br>
    [cat_link_title] — Ссылка на папку<br>
    [cat_page_num] — Количество страниц в папке (в скобках), если 0 — не выводится<br>
    [css] — Класс css, варианты: «podpapki» или «papki» для вложенных и основных папок<br>
    [cat_parent] — Отступ для подпапок<hr>
    '>Вставки для шаблона блока открытого раздела</option>
    ".$bases."
    </select>
    <a title='Закрыть/Открыть справочное окно' id='show_shablon_var' class=punkt onclick=\"show_animate('shablon_var');\" style='float:right; display:none;'><div class='radius' style='font-size:12pt; width:20px; height: 20px; color: white; text-align:center; float:right; margin:5px; margin-bottom:0; background: #bbbbbb;'>&nbsp;&uarr;&nbsp;</div></a><div id=shablon_var style='display:none; width:95%; height:300px; scroll:auto;' class=block></div>";
}
##########################################################################################
function add_file_upload_form($textarea="textarea", $textarea_show="textarea", $spisok_show = true, $redactor_id = "", $css = false) {
  $txt = "<form id='fileupload' action='includes/upload/server/php/' method='POST' enctype='multipart/form-data'>
  <label for='show_oldnames'><input type='checkbox' id='show_oldnames' checked> <b>Добавлять имя файла</b> фотографии как её описание (<i>подходит для осмысленных названий</i>)</label><br><div class='notice warning green'><span class='icon green medium' data-icon='('></span>Фотографии можно перенести из любой папки вашего компьютера, даже не нажимая кнопку «Добавить файлы...»</div>
  <div style='padding:10px; padding-left:30px; margin-bottom:30px;'>
                  <span class='btn btn-success fileinput-button' style='position: relative;  overflow: hidden;  float: left;  margin-right: 5px;'>
                      <a class='button'>Добавить файлы...</a>
                      <input id='fileupload' type='file' name='files[]' style='position: absolute;  top: 0;  right: 0;  margin: 0;  opacity: 0;  filter: alpha(opacity=0);  transform: translate(-300px, 0) scale(4);  font-size: 23px;  direction: ltr;  cursor: pointer;' data-url='server/php/' multiple>";
                      if ($spisok_show == true) $txt .= "<a class='button small' onclick='$(\"#".$textarea_show."\").toggle();'>Показать список</a>";
                  $txt .= "</span>
              </div>
<div id='progress'><div class='bar' style='width: 0%;height: 18px;background: green;'></div></div>
<script src='includes/upload/js/jquery.ui.widget.js'></script>
<script src='includes/upload/js/jquery.iframe-transport.js'></script>
<script src='includes/upload/js/jquery.fileupload.js'></script>
<script>
$(function () {
    $('#fileupload').fileupload({
      dataType: 'json',
      autoUpload: true,
      sequentialUploads: true,
      done: function (e, data) {";
        if ($redactor_id == "") $txt .= "
          $('#textarea').hide();
        /* $('#".$textarea_show."').show(); */
        ";
        $txt .= "$.each(data.result.files, function (index, file) {";
          if ($redactor_id == "") $txt .= "
          id = file.name;
          id = id.replace('.', '');
          if (document.getElementById('show_oldnames').checked == false) file.oldname = '';
          value = '/img/' + file.name + '|' + file.oldname;
          $('#".$textarea."').append(value + '\\n');
          if (file.oldname == null || file.oldname == '' || typeof file.oldname == 'undefined') file.oldname = 'без имени';
          $('.pics').append('<div id=\"' + id + '\" class=\"pic\" style=\"background:url(\'includes/php_thumb/php_thumb.php?src=/img/' + file.name + '&amp;w=160&amp;h=100&amp;q=0\') no-repeat bottom white;\"><a title=\"Удалить фото\" class=\"button small red white\" onclick=\"pics_replace(\'#' + id + '\',\'#".$textarea."\', \'' + value + '\');\">×</a><span>' + file.oldname + '</span></div>');
          ";
          else {
            $txt .= "\nvalue = '/img/' + file.name;\n";
            if ($css == true) $txt .= $redactor_id.".insert(value);\n";
            else $txt .= "alt = ''; if (document.getElementById('show_oldnames').checked == true) alt = file.oldname;
              ".$redactor_id.".insert('<img alt=\"'+alt+'\" src=\"'+value+'\">');\n";
          }
          $txt .= "
        });
      },
      progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .bar').css(
            'width',
            progress + '%'
        );
      }
    });
});
</script>
</form>";
return $txt;
}
##########################################################################################
function redactor($type, $txt, $name, $name2="", $style="html") {
  global $add_clips, $lang_admin;
  // индексы клавиш - https://code.google.com/p/jquerykeyboard/wiki/Indexes
  $echo = "<script>
  function keying() {
    document.body.onkeydown = function() {
      if ( event.ctrlKey || event.keyCode == 91) {
        document.body.onkeydown = function() {
          if ( event.keyCode == 83) {
            save_main(\"ad/ad-mainpage.php\", \"mainpage_save_ayax\", \"\", \"\");
            keying();
            return false;
          } else keying();
        }
      }
      if (event.keyCode == 27) {
          if ( $(\"#button_resize_red\").is(\":visible\") ) $(\"#button_resize_red\").click();
      }
    }
  }
  keying();
  </script>";
  if ($type=="0") {
  } elseif ($type=="2") {
    // editor.insert("I know how to insert text"); вставка текста


    // Преобразование textarea (замена на русскую букву е, только для редактора)
    $txt = str_replace("textarea","tеxtarea",$txt); // ireplace
    //$txt = str_replace("&","&amp;",$txt);
    global $color_tema_html, $color_tema_css, $color_tema_js, $color_tema_php; // цветовые стили для редактора кода
    // Настройки невизуального редактора с подсветкой кода
    if ($color_tema_html == "") $color_tema_html = "monokai";
    if ($color_tema_css == "") $color_tema_css = "monokai";
    if ($color_tema_js == "") $color_tema_js = "monokai";
    if ($color_tema_php == "") $color_tema_php = "monokai";
    if ($style == "html") $theme=$color_tema_html;
    if ($style == "css") $theme=$color_tema_css;
    if ($style == "php") $theme=$color_tema_php;
    if ($style == "javascript") $theme=$color_tema_js;
    // section>h2+ul.nav>li.nav-item$*5>a
    $echo .= "<textarea id='".$name."X' class='hide' name='".$name."'>".$txt."</textarea>
    <pre id='".$name."' class='w100 h1200 l0'></pre><br>
    <script src='/includes/ace-redactor/ace.js'></script><script>
      var config = ace.require(\"ace/config\");
      var ".$name." = ace.edit('".$name."');
      ".$name.".setTheme('ace/theme/".$theme."');
      ".$name.".getSession().setValue( $('#".$name."X').val() );
      ".$name.".getSession().setMode('ace/mode/".$style."');
      ".$name.".getSession().setUseWrapMode(true);

      ".$name.".getSession().setWrapLimitRange(null, null);
      ".$name.".setBehavioursEnabled(true); //auto pairing of quotes & brackets
      ".$name.".setShowPrintMargin(false);
      ".$name.".session.setUseSoftTabs(true); //use soft tabs (likely the default)
      ".$name.".getSession().on('change', function (e) {
          EditorChanged();
      });
      //called when editor changes
      function EditorChanged() {
        //custom stuff
      }
      // Include auto complete- Only for Template Editor page
      ace.config.loadModule('ace/ext/language_tools', function () {
        ".$name.".setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true
        })
        ace.config.loadModule(\"ace/snippets/".$style."\");
      });
      ".$name.".getSession().on('change', function(e) {
          $('#".$name."X').val( ".$name.".getSession().getValue() );
      });
      document.getElementById('".$name."').style.fontSize='16px';
    </script>";
    /*
    горячие клавиши для редактора
      ".$name.".commands.addCommand({
        name: 'SaveMe',
        bindKey: {win: 'Ctrl-S',  mac: 'Command-S'}, // вызов на PC и Mac
        exec: function(".$name.") {
          // editor.*
        },
        readOnly: false
      });
    */
  } elseif ($type=="1") {
    // Преобразование textarea (замена на русскую букву е, только для редактора)
    $txt = str_replace("textarea","tеxtarea",$txt); // ireplace

    $txt = str_replace("&","&amp;",$txt);
    $echo .= "<textarea id='".$name."' class='w100 h400' name='".$name."'>".$txt."</textarea><br>";
  } elseif ($type=="3") {
    $echo .= "<script> 
    $(document).ready(function()
    {  $('#".$name."').editor({ focus: true, toolbar: 'classic', css: ['/ed/js/editor/css/editor.css'] });";
  if ($name2 != "") $echo .= "\n$('#".$name2."').editor({ css: ['/ed/js/editor/css/editor.css'], toolbar: 'classic', upload: 'upload.php' });\n";
  $echo .= "});
    </script><textarea id='".$name."' name='".$name."' style='width: 100%; height: 220px;'>".$txt."</textarea>";
  } elseif ($type=="4") {
    // Настройки второго редактора
    global $ed2_button_html, $ed2_button_formatting, $ed2_button_bold, $ed2_button_italic, $ed2_button_deleted, $ed2_button_underline, $ed2_button_unorderedlist, $ed2_button_orderedlist, $ed2_button_outdent, $ed2_button_indent, $ed2_button_image, $ed2_button_video, $ed2_button_file, $ed2_button_table, $ed2_button_link, $ed2_button_alignment, $ed2_button_horizontalrule, $ed2_button_more, $ed2_button_link2, $ed2_button_block, $ed2_button_pre, $ed2_button_fullscreen, $ed2_button_clips, $ed2_button_fontcolor, $ed2_button_fontsize, $ed2_button_fontfamily, $ed2_minHeight, $ed2_direction, $ed2_div_convert;
    if ($ed2_button_html == "" && $ed2_button_bold == "" && $ed2_button_link == "") $ed2_button_html = $ed2_button_formatting = $ed2_button_bold = $ed2_button_italic = $ed2_button_deleted = $ed2_button_unorderedlist = $ed2_button_orderedlist = $ed2_button_image = $ed2_button_video = $ed2_button_file = $ed2_button_table = $ed2_button_link = $ed2_button_alignment = $ed2_button_horizontalrule = $ed2_button_fullscreen = $ed2_button_clips = " checked";
    if ($ed2_direction == "") $ed2_direction = "ltl";
    if ($ed2_div_convert == "1") $ed2_div_convert = "true"; else $ed2_div_convert = "false";
    if ($ed2_minHeight == "") $ed2_minHeight = "300";
    $ed2_html=$ed2_formatting=$ed2_bold=$ed2_italic=$ed2_deleted=$ed2_underline=$ed2_unorderedlist=$ed2_orderedlist=$ed2_outdent=$ed2_indent=$ed2_image=$ed2_video=$ed2_file=$ed2_table=$ed2_link=$ed2_alignment=$ed2_horizontalrule=$ed2_more=$ed2_link2=$ed2_block=$ed2_pre=$ed2_fullscreen=$ed2_clips=$ed2_fontcolor=$ed2_fontsize=$ed2_fontfamily="";
    if ($ed2_button_html == "1") $ed2_html = "'html', ";
    if ($ed2_button_formatting == "1") $ed2_formatting = "'|', 'formatting', ";
    if ($ed2_button_bold == "1") $ed2_bold = "'|', 'bold',";
    if ($ed2_button_italic == "1") $ed2_italic = "'italic',";
    if ($ed2_button_deleted == "1") $ed2_deleted = "'deleted',";
    if ($ed2_button_underline == "1") $ed2_underline = "'underline',";
    if ($ed2_button_unorderedlist == "1") $ed2_unorderedlist = "'|', 'unorderedlist',";
    if ($ed2_button_orderedlist == "1") $ed2_orderedlist = "'orderedlist',";
    if ($ed2_button_outdent == "1") $ed2_outdent = "'outdent',";
    if ($ed2_button_indent == "1") $ed2_indent = "'indent',";
    if ($ed2_button_image == "1") $ed2_image = "'|', 'image',";
    if ($ed2_button_video == "1") $ed2_video = "'video',";
    if ($ed2_button_file == "1") $ed2_file = "'file', ";
    if ($ed2_button_table == "1") $ed2_table = "'table',";
    if ($ed2_button_link == "1") $ed2_link = "'link',";
    if ($ed2_button_alignment == "1") $ed2_alignment = "'|', 'alignment',";
    if ($ed2_button_horizontalrule == "1") $ed2_horizontalrule = "'|', 'horizontalrule',";
    if ($ed2_button_more == "1") $ed2_more = "'|', 'button_more',";
    if ($ed2_button_link2 == "1") $ed2_link2 = "'button_link',";
    if ($ed2_button_block == "1") $ed2_block = "'button_block',";
    if ($ed2_button_pre == "1") $ed2_pre = "'pre'";
    if ($ed2_button_fullscreen == "1") $ed2_fullscreen = "'fullscreen',";
    if ($ed2_button_clips == "1") $ed2_clips = "'clips',";
    if ($ed2_button_fontcolor == "1") $ed2_fontcolor = "'fontcolor',";
    if ($ed2_button_fontsize == "1") $ed2_fontsize = "'fontsize',";
    if ($ed2_button_fontfamily == "1") $ed2_fontfamily = "'fontfamily'";
    // iframe: true, css: 'css_20.css',
    $echo .= "<script>
    $(document).ready(function() { 
      $('.redactor').redactor({ 
        buttons: [".$ed2_html.$ed2_formatting.$ed2_bold.$ed2_italic.$ed2_deleted.$ed2_underline.$ed2_unorderedlist.$ed2_orderedlist.$ed2_outdent.$ed2_indent.$ed2_image.$ed2_video.$ed2_file.$ed2_table.$ed2_link.$ed2_alignment.$ed2_horizontalrule.$ed2_more.$ed2_link2.$ed2_block.$ed2_pre."], 
        buttonsCustom: {
            button_more: {title: 'Вставка ссылки на полное содержание (для предисловия)',callback: function (){ this.insertHtml('<!--more-->'); }},
            button_link: {title: '[] — Вставка блока (например, галереи фотографий)',callback: function (){ this.insertHtml('[Название блока]'); }},
            button_block: {title: '{} — Вставка быстрой ссылки на страницу или раздел',callback: function (){ this.insertHtml('{Название страницы или раздела}'); }},
            pre: {title: '<PRE>', callback: function(){ this.formatBlocks('pre'); }}
          }, 
          mobile: true, 
          observeImages: true, 
          observeLinks: true, 
          convertVideoLinks: true, 
          convertImageLinks: true,
          tabSpaces: 4, 
          boldTag: 'b',
          italicTag: 'i',
          deniedTags: ['html', 'head', 'body', 'meta', 'applet'],
          convertDivs: ".$ed2_div_convert.",
          imageUpload: 'ed2/image_upload.php', 
          fileUpload: 'ed2/file_upload.php', 
          clipboardUploadUrl: 'ed2/clipboard_upload.php', 
          lang: '".$lang_admin."', 
          minHeight: ".$ed2_minHeight.",
          direction: '".$ed2_direction."',
          plugins: [".$ed2_fullscreen.$ed2_clips.$ed2_fontcolor.$ed2_fontsize.$ed2_fontfamily."] }); } );
    </script><textarea id='".$name."' class='redactor' name='".$name."' style='width: 100%; height: 220px;'>".$txt."</textarea>";
    $clip_title = array('«Рыба» для заполнения тестовых страниц');
    $clip_text = array('<p>Социальная парадигма, на первый взгляд, определяет антропологический феномен толпы, говорится в докладе ОБСЕ. Политическая психология ограничивает эмпирический доиндустриальный тип политической культуры, указывает в своем исследовании К.Поппер. Демократия участия, как бы это ни казалось парадоксальным, ограничивает социализм, последнее особенно ярко выражено в ранних работах В.И.Ленина. Правовое государство, согласно традиционным представлениям, постоянно. Политическое лидерство отражает феномен толпы, впрочем, это несколько расходится с концепцией Истона.<p>Понятие политического конфликта, особенно в условиях политической нестабильности, означает культ личности (отметим, что это особенно важно для гармонизации политических интересов и интеграции общества). Политическое учение Руссо сохраняет феномен толпы, исчерпывающее исследование чего дал М.Кастельс в труде "Информационная эпоха". Политическое учение Августина, в первом приближении, доказывает механизм власти (приводится по работе Д.Белла "Грядущее постиндустриальное общество"). Либеральная теория, в первом приближении, сохраняет онтологический тоталитарный тип политической культуры, если взять за основу только формально-юридический аспект. Постиндустриализм существенно формирует культ личности, что было отмечено П.Лазарсфельдом. Демократия участия, с другой стороны, вызывает плюралистический доиндустриальный тип политической культуры, впрочем, это несколько расходится с концепцией Истона.');
    $echo .= "<div id=\"clipsmodal\" style=\"display: none;\"><div id=\"redactor_modal_content\"><div class=\"redactor_modal_box\"><ul class=\"redactor_clips_box\">";
    if (strlen($add_clips) > 1) {
      $add_clips2 = explode("?%?",$add_clips);
      foreach ($add_clips2 as $cli) {
        $cli = explode("*?*",$cli);
        $clip_title[] = $cli[0];
        $clip_text[] = $cli[1];
      }
    }
    foreach ($clip_title as $key => $value) {
      $echo .= "<li><a href=\"#\" class=\"redactor_clip_link\">".$clip_title[$key]."</a><div class=\"redactor_clip\" style=\"display: none;\">".$clip_text[$key]."</div></li>";
    }
    $echo .= "</ul></div></div><div id=\"redactor_modal_footer\">Добавить заготовку можно в Настройках 〉Заготовки для редактора.<br><a href=\"#\" class=\"redactor_modal_btn redactor_btn_modal_close\">Закрыть</a></div></div> ";
  }
  return $echo;
}
##########################################################################################
function redactor2($type, $txt, $name, $style="html") {
  $echo = "";
  if ($type=="1") {
    // Преобразование textarea (замена на русскую букву е, только для редактора)
    $txt = str_replace("textarea","tеxtarea",$txt); // ireplace
    $txt = str_replace("&","&amp;",$txt);
    $echo .= "<textarea id='".$name."' class='w100 h155' name='".$name."'>".$txt."</textarea><br>";
  } elseif ($type=="2") {
    global $color_tema_html, $color_tema_css, $color_tema_js, $color_tema_php; // цветовые стили для редактора кода
    // Настройки невизуального редактора с подсветкой кода
    if ($color_tema_html == "") $color_tema_html = "monokai";
    if ($color_tema_css == "") $color_tema_css = "monokai";
    if ($color_tema_js == "") $color_tema_js = "monokai";
    if ($color_tema_php == "") $color_tema_php = "monokai";
    if ($style == "html") $theme=$color_tema_html;
    if ($style == "css") $theme=$color_tema_css;
    if ($style == "php") $theme=$color_tema_php;
    if ($style == "javascript") $theme=$color_tema_js;
    // Преобразование textarea (замена на русскую букву е, только для редактора)
    $txt = str_replace("textarea","tеxtarea",$txt); // ireplace
    //$txt = str_replace("&","&amp;",$txt);
    $echo .= "<textarea id='".$name."X' class='hide' name='".$name."'>".$txt."</textarea>
    <pre id='".$name."' class='w100 h700'></pre><br>
    <script src='/includes/ace-redactor/ace.js'></script><script>var ".$name." = ace.edit('".$name."');
          ".$name.".getSession().setValue( $('#".$name."X').val() );
          ".$name.".setTheme('ace/theme/".$theme."');
          ".$name.".getSession().setMode('ace/mode/".$style."');
          ".$name.".getSession().setUseWrapMode(true);
          ".$name.".getSession().on('change', function(e) {
              $('#".$name."X').val( ".$name.".getSession().getValue() );
          });
          document.getElementById('".$name."').style.fontSize='16px';</script>";
  } elseif ($type=="4") {
    $echo .= "<textarea id='".$name."' class='redactor' name='".$name."' rows=15 cols=40 style='width: 100%; height: 220px;'>".$txt."</textarea>";
  } elseif ($type=="3") {
    $echo .= "<textarea id='".$name."' name='".$name."' rows=15 cols=40 style='width: 100%; height: 220px;'>".$txt."</textarea>";
  }
  return $echo;
}
##########################################################################################
function button_resize_red($redactor, $savebutton=false) { // redactor_id
  if ($redactor == 2) {
    if ($savebutton == true) { 
      $add = " $(\"#button_save\").show();"; 
      $add2 = " $(\"#button_save\").hide();"; 
    } else $add = $add2 = "";
    $txt = "<a id='editor_large_button' class='right3 button small orange' onclick='$(\".ace_editor\").css(\"position\", \"fixed\").css(\"top\", \"0\").css(\"height\", \"100%\").css(\"margin\", \"0\"); $(\"#button_resize_red\").show();".$add."'>↑ Развернуть</a>
    <a class='z10000 small orange button' id='button_resize_red' onclick='$(\".ace_editor\").css(\"position\", \"relative\").css(\"height\", \"1200px\"); $(\"#button_resize_red\").hide();".$add2."' style='position:absolute; margin:-5px 70%; display:none;'>↓Esc</a>

    <a class='right3 button small orange' onclick='$(\"#photo_upload\").toggle();'>Вставить фото</a>";
    if ($savebutton == true) $txt .= "<a class='z10000 small green button' id='button_save' onclick='save_main(\"ad/ad-mainpage.php\", \"mainpage_save_ayax\", \"\", \"\")' style='position:absolute; margin:-5px 80%; display:none;'>".aa("Сохранить")."</a>";
  } else $txt = "";
  return $txt;
}
##########################################################################################
?>
