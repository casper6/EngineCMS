<?php
if ($_REQUEST['op'] == "page_save_spiski") {
  require_once("../mainfile.php");
  global $prefix, $db, $red, $admin;
  if (is_admin($admin)) $realadmin = 1; else $realadmin = 0;
} else {
  if (mb_strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
  $aid = trim($aid);
  global $prefix, $db, $red;
  $sql = "SELECT realadmin FROM ".$prefix."_authors where aid='".$aid."'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $realadmin = $row['realadmin'];
}
if ($realadmin==1) {

function seo($edit=false){
  global $kolkey;
  echo "<script>
  function seo(){
    new Spinner().spin( document.getElementById('procent') );
    var x = $('#open_text').val();
    var y = $('#main_text').val();
    var kolkey = $('select.kolkey').val();
    var kolslov = $('select.kolslov').val();
    var kolslov = $('select.kolslov').val();
    var koldes=250;
    var a = (x+y);
    if ( (x + y).length >= 400) {
      xps=new XMLHttpRequest();
      xps.onreadystatechange=function() {
        if (xps.readyState==4 && xps.status==200)
          var key = document.getElementById('keywords2').innerHTML = xps.responseText;
        if( key.length >= 3 )  {
          $('#key').show('slow'); $('#key_hide').hide();
      document.getElementById('procent').innerHTML = '<h2>".aa("Загружаю вычисления...")."</h2>';
          zapros('metod=des&x='+a+'&key='+key+'&kol='+koldes,document.getElementById('description2'),'des');
          zapros('metod=procent&x='+a+'&key='+key,document.getElementById('procent'),'proc');
        } 
      }
      xps.open('POST','includes/seo.php',true);
      xps.setRequestHeader('Content-type','application/x-www-form-urlencoded');
      xps.send('metod=newkey&x='+a+'&kol='+kolkey+'&kolslov='+kolslov);
    } else {
      document.getElementById('ajax').innerHTML='".aa("Текст меньше 400 символов.")."';
    }
  }
  function zapros(url,mesto,metod) {
    metod=new XMLHttpRequest();
    metod.onreadystatechange=function() {
      if (metod.readyState==4 && metod.status==200) mesto.innerHTML= metod.responseText;
    }
    metod.open('POST','includes/seo.php',true);
    metod.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    metod.send(url);
  }
  </script><span id='ajax'></span><input type='button' class='blue' value='".aa("Заполнить ключевые слова")."' onclick='seo()'> ".aa("Ключевых слов:")." <select class='kolkey'><option value='30'>30</option><option value='20'>20</option><option value='15' selected='selected'>15</option><option value='10'>10</option><option value='9'>9</option><option value='8'>8</option><option value='7'>7</option><option value='6'>6</option><option value='5'>5</option><option value='4'>4</option><option value='3'>3</option><option value='2'>2</option><option value='1'>1</option></select>, ".aa("из них словосочетаний:")." <select class='kolslov'><option value='1'>1</option><option value='2'>2</option><option value='3' selected='selected'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option><option value='10'>10</option></select>
  <p id='procent'></p>";
}

function edit_base_pages_category($cid, $red=0) {
  global $clean_urls, $module, $name, $prefix, $db, $title_razdel_and_bd, $new; //, $toolbars;
  include("ad/ad-header.php");
  $cid = intval($cid);
  $red = intval($red);
  $sql = "SELECT * FROM ".$prefix."_pages_categories WHERE `cid`='".$cid."'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $name = $row['module'];
  $title = $row['title'];
  $module = $name;
  $desc = $row['description'];
  $sortirovka = $row['sort'];
  $pic = $row['pic'];
  $parent_id = $row['parent_id'];
  $clean_url = $row['clean_url'];
  $meta_title = $row['meta_title'];
  $keywords = $row['keywords'];
  $description = $row['meta_description'];

  if ( $new > 0 ) echo "<div class='notice success mw700'><a target='_blank' class='green' href='".re_link("/-".$module."_cat_".$cid)."'>Папка</a> отредактирована.</div>"; 
    else echo "<div class='notice warning mw700'>Открыть папку <a target='_blank' class='green' href='".re_link("/-".$module."_cat_".$cid)."'>на сайте</a>.</div>";

  echo "<form action='sys.php' method='post'>
  <div class='fon w100 mw800'>
  <div class='black_grad' style='height:45px;'>
  <button type=submit id=new_razdel_button class='medium green' onclick=\"show('sortirovka');\" style='float:left; margin:3px;'><span style='margin-right: -2px;' class='icon white small' data-icon='c'></span> Сохранить</button>
  <span class='h1' style='padding-top:10px;'>
    ".$title_razdel_and_bd[$module]." &rarr; Редактирование папки</span>";
  if (intval($nastroi) != 1) red_vybor();
  echo "</div>";
  # cid module title description pic sort counter parent_id
  echo "<table class='w100'><tr valign='top'><td bgcolor='#eeeeee' width='250' id='razdels'>
  <h2>Раздел:</h2>";
  $sql = "select `name`, `title`, `color` from ".$prefix."_mainpage where `type`='2' and (`useit` like '%".aa("[страницы]")."%' or `useit` like '%".aa("[содержание]")."%') and `tables`='pages' order by `color` desc, `title`";
  $result = $db->sql_query($sql);
  $numrows = $db->sql_numrows($result);
  echo "<select name='module' id='to_razdel' class='w100' size='1' onChange=\"izmenapapka(document.getElementById('to_razdel').value, '', '','','editdir');\">";
  while ($row = $db->sql_fetchrow($result)) {
    $name2 = $row['name'];
    if (mb_strpos($name2, "\n")) { // заменяем имя запароленного раздела
      $name2 = explode("\n", str_replace("\r", "", $name2));
      $name2 = trim($name2[0]);
    }
    $title2 = $row['title'];
    $color = $row['color'];
    switch ($color) {
      case "1": // Частоупотребляемый зеленый
      $color = "b4f3b4"; break;
      case "2": // Редкоупотребляемый желтый
      $color = "f3f3a3";  break;
      case "3": // Закрытый или старый красный
      $color = "ffa4ac"; break;
      case "4": // Новый, в разработке
      $color = "b8f4f2"; break;
      default: 
      $color = "ffffff"; break;  // Стандартный белый
    }
    if ($name == $name2) $sel = "selected"; else $sel = "";
	   echo "<option style='background:".$color.";' value='$name2' ".$sel.">".$title2."</option>";
  }
  echo "</select><br>
  <div style='display:inline; float:right;'><div id=showa style='display:inline; float:right;'><a style='cursor:pointer;' onclick=\"show('hidea'); show('showa'); $('#to_papka').width(500);\">развернуть &rarr;</a></div><div id=hidea style='display:none;'><a style='cursor:pointer;' onclick=\"show('showa'); show('hidea'); $('#to_papka').width(300);\">&larr; свернуть</a></div></div><h2>Папка:</h2>";
  $sql = "select * from ".$prefix."_pages_categories where module='$name' and `tables`='pages' and cid != '$cid' order by parent_id,cid";
  $result = $db->sql_query($sql);
  echo "<div id='izmenapapka'><script>izmenapapka(document.getElementById('to_razdel').value, '$parent_id', '$cid','','editdir')</script></div><br><br>";
  $sql3 = "select `text` from `".$prefix."_mainpage` where (`name` = '".$name."' or `name` like '".$name." %') and `type`='2'";
  $result3 = $db->sql_query($sql3);
  $row3 = $db->sql_fetchrow($result3);
  if (trim($row3['text'])!="") {
    $main_file = explode("|",$row3['text']);
    $main_options = $main_file[1];
    parse_str($main_options);
  }
  if ($view == 4) $blok = "<p><b>Шаблон для анкет рейтинга (только для этой папки!)</b><br>
  Пример написания шаблона:<br>
  Ваше Имя *: |строка<br>
  Договаривались ли Вы заранее с врачом: |выбор|да|нет<br>
  Отзыв о Вашем враче: |текст<br>";
  else $blok = "<div class='right3'>Проверка текста: 
      <a href='http://orthography.morphology.ru' target='_blank' class='button small blue'>орфография</a> 
      <a href='http://speller.yandex.net/speller/1.0/index.html' target='_blank' class='button small blue'>орфография 2</a> 
      <a href='http://test-the-text.ru' target='_blank' class='button small blue'>информационный стиль</a></div><p>
      <h1>Содержание папки:</h1>";

  echo "<div id='mainrazdel' class='dark_pole2'><a class='base_page' onclick=\"if ( $('#dop').is(':hidden') ) $('#mainrazdel').attr('class', 'dark_pole2sel'); else { $('#mainrazdel').attr('class', 'dark_pole2');} $('#main').toggle(); $('#dop').toggle('slow'); \"><div id='mainrazdel'><span class='icon gray large in_b' data-icon='z'><span aria-hidden='true'>z</span></span><span class='plus20'>Дополнительные настройки</span></div></a></div> ";

  echo "</td><td style='padding:0;'><a class='punkt' title='Свернуть/развернуть левую колонку' onclick='$(\"#razdels\").toggle(\"slow\");'><div class='polosa_razdelitel'><div id='rotateText'><nobr>↑ Сворачивает левую колонку ↑</nobr></div></div></a></td><td>";

  echo "<div style='display:none' id='dop'><a title='Закрыть' class='punkt' onclick=\"if ( $('#dop').is(':hidden') ) $('#mainrazdel').attr('class', 'dark_pole2sel'); else $('#mainrazdel').attr('class', 'dark_pole2'); $('#main').toggle(); $('#dop').toggle('slow'); \"><div class='radius' style='font-size:12pt; width:20px; height: 20px; color: white; text-align:center; float:right; margin:5px; background: #bbbbbb;'>&nbsp;x&nbsp;</div></a>";

  if ($clean_urls != 0) echo "<h2>Замена адреса папки (ссылки):</h2><textarea name='clean_url' id='clean_url' class='big w100' rows='2' cols='10'>".$clean_url."</textarea>";
  else echo "<input name='clean_url' type='hidden' value='".$clean_url."'>";

  echo "<div class='radius w100 p5' style='border: 1px solid #1D6DC1;'><h2 style='color: #1D6DC1;'>SEO настройки</h2>
  <h2>Замена TITLE: <a onclick=\"show('help17')\" class='help'>?</a></h2><textarea name='meta_title' class='big w95' rows='2' cols='10'>".$meta_title."</textarea>
  <br><div id='help17' style='display:none;'><span class=small>По-умолчанию — пустое поле и TITLE будет создан автоматически: «название папки — название раздела».</span><p></div>

  <h2>Ключевые слова для поисковых систем: <a onclick=\"show('help10')\" class='help'>?</a></h2><textarea name='keywords2' class='big w95' rows='2' cols='10'>".$keywords."</textarea>
  <br><div id='help10' style='display:none;'><span class=small>Максимум 500 символов. Разделять словосочетания желательно запятой. Если пусто - используются Ключевые словосочетания из <a href='/sys.php?op=options' target='_blank'>Настроек портала</a>).</span><p></div>

  <h2>Описание для поисковых систем: <a onclick=\"show('help11')\" class='help'>?</a></h2><textarea name='description2' class='big w95' rows='4' cols='10'>".$description."</textarea>
  <br><div id='help11' style='display:none;'><span class=small>Максимум 200 символов. Если пусто - используется <b>Название</b> папки.</span><p></div>
  </div>

  <h2>Фотография для папки, адрес изображения:</h2>
  <input type='text' name='pic' value='".$pic."' class='w100'><br>
  Можно настроить раздел на использование шаблона папки, содержащего фотографию. Загрузить картинку можно с помощью редактора, затем переключиться в HTML-режим первой кнопкой редактора, скопировать её адрес и вставить в это поле.
  ";

  // Замена ссылок (адрес страницы)
  $trans_title = "";
  if ($clean_urls == 1) // транслит
    $trans_title = " onchange='$(\"textarea#clean_url\").val( translite( del_space( $(\"input#title\").val() ) ) );'";
  if ($clean_urls == 2) // русские
    $trans_title = " onchange='$(\"textarea#clean_url\").val( del_space( $(\"input#title\").val() ) );'";

  echo "</div><div id='main'>
  <h2>Название папки:</h2>
  <input type='text' name='title' id='title' value='".$title."' size='60' class='w100'".$trans_title.">
  ".$blok."";
  echo redactor($red, $desc, 'desc'); // редактор: типа редактора, редактируемое поле

  echo "<h2>Сортировка:</h2>
  <input type='text' name='sortirovka' value='".$sortirovka."' size='3' style='float:left; margin-right:10px;'> Если нужно отсортировать папки в определенной последовательности - лучше указывать цифры в этом поле, использовать десятичную разницу между числами сортировки для разных папок, например: 10, 20, 30, 40... Это нужно для того, чтобы в случае создания новой папки вы не изменяли сортировку для всех предыдущих, а легко присвоили ей следующий номер за сортировкой, стоящей перед ней папки, например: 11, 21, 31, 41... или 15, 25, 35 - чтобы можно было вклинить новые папки между ними. <b>Пример:</b> для первой папки в поле Сортировка указать 10, для второй — 20 и так далее.
  </div>
    <input type='hidden' name='cid' value='$cid'>
    <input type='hidden' name='op' value='base_pages_save_category'>
    </table></div></form>";
  admin_footer();
}

function base_pages_save_category($cid, $module, $title, $desc, $sortirovka, $parent_id, $description2, $keywords2, $meta_title, $clean_url, $pic) {
  global $prefix, $db;
  $db->sql_query("UPDATE ".$prefix."_pages_categories SET `module`='".mysql_real_escape_string($module)."', `title`='".mysql_real_escape_string($title)."', `description`='".mysql_real_escape_string($desc)."', `pic`='".mysql_real_escape_string($pic)."', `sort`='".mysql_real_escape_string($sortirovka)."', `parent_id`='".mysql_real_escape_string($parent_id)."', `tables`='pages', `meta_description`='".mysql_real_escape_string($description2)."', `keywords`='".mysql_real_escape_string($keywords2)."', `meta_title`='".mysql_real_escape_string($meta_title)."', `clean_url`='".mysql_real_escape_string($clean_url)."' WHERE cid='$cid'") or die("не удалось обновить папку");
  Header("Location: sys.php?op=edit_base_pages_category&cid=".$cid."&new=1#1");
}

function delete_razdel_base_pages($name) { 
  global $name, $prefix, $db; 
  $db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE `tables`='del' and (`name` = '".$name."' or `name` like '".$name." %')"); 
  $db->sql_query("DELETE FROM ".$prefix."_pages_categories WHERE `module`='".$name."' and `tables`='del'"); 
  $db->sql_query("DELETE FROM ".$prefix."_pages WHERE `module`='".$name."' and `tables`='del'"); 
  Header("Location: sys.php");
}

function delete_page_base_pages($pid) { 
  global $name, $prefix, $db; 
  $db->sql_query("DELETE FROM ".$prefix."_pages WHERE `tables`='pages' and `pid`='".$pid."'"); 
  Header("Location: sys.php");
}

function delete_all($del="del") {
  global $prefix, $db; 
  if ($del == "design") {
    $db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE `tables`='del' and `type`!='2'") or die('Error: 0'); 
    Header("Location: sys.php?op=mainpage&type=element");
  } else {
    if ($del != "backup") $del = "del"; // в дальнейшем можно расширить
    $db->sql_query("DELETE FROM ".$prefix."_pages WHERE `tables`='".$del."'") or die('Error: 1');
    $db->sql_query("DELETE FROM ".$prefix."_pages_categories WHERE `tables`='".$del."'") or die('Error: 2');
    $db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE `tables`='".$del."' and `type`='2'") or die('Error: 3');
    Header("Location: sys.php");
  }
}

# СТРАНИЦЫ =================
function base_pages_add_page($page_id=0, $red=0, $name=0, $cid=0, $new=0, $pid=0) {
  global $module, $prefix, $db, $red, $new, $pid, $redaktor, $toolbars, $kolkey, $title_razdel_and_bd, $siteurl, $txt_razdels, $clean_urls, $golos_admin, $future_date, $close_date;
  include("ad/ad-header.php");
  $id = intval ($id);
  $golos = 0;
  $search_tags = '';
  if ( $page_id > 0 ) { // Если это редактирование
    $sql = "SELECT * FROM ".$prefix."_pages WHERE pid='".$pid."' limit 1";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $cid = $row['cid'];
    $titl = stripcslashes($row['title']);
    $shablon1 = $open_text = stripcslashes($row['open_text']);
    $shablon2 = $main_text = stripcslashes($row['main_text']);
    $module = $row['module'];
    //$foto = $row['foto'];
    $search_tags = $row['search'];
    $data = $row['date'];
    $close_data = $row['close_date'];
    $counter = $row['counter'];
    $active = $row['active'];
    $comm = $row['comm'];
    $golos_reiting = $row['golos'];
    $mainpage = $row['mainpage'];
    $rss = $row['rss'];
    $nocomm = $row['nocomm'];
    $price = $row['price'];
    $description = $row['description'];
    $keywords = $row['keywords'];
    $meta_title = $row['meta_title'];
    $clean_url = $row['clean_url'];
    $copy = $row['copy'];
    $sor = intval ($row['sort']);
    global $clean_urls;
    switch($clean_urls) {
      case 1: $chpu = "<b>ЧПУ: транслит названия страницы.</b> Ссылка на раздел: <a href='/".$name."/' target='_blank'>".$name."/</a>"; 
      if ($clean_url == "") $clean_url = clean_url( translit_name($titl) );
        break;
      case 2: $chpu = "<b>ЧПУ: название страницы.</b> Ссылка на раздел: <a href='/".$name."/' target='_blank'>".$name."/</a>"; 
      if ($clean_url == "") $clean_url = clean_url($titl);
        break;
      default: $chpu = "<b>ЧПУ выключено.</b> Ссылка на раздел: <a href='/-".$name."' target='_blank'>-".$name."</a>"; break;
    }
    // узнаем номер последней резервной копии
    $new_pid = 0;
    $sql = "SELECT `pid` from ".$prefix."_pages where copy='".$pid."' order by redate desc limit 1"; // список всех категорий
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $new_pid = $row['pid'];
    if ( $new > 0 ) echo "<div class='notice success mw700'><a target='_blank' class='green' href='".re_link("/-".$module."_page_".$pid)."'>Страница</a> отредактирована. "; 
    else echo "<div class='notice warning mw700'>Открыть страницу <a target='_blank' class='green' href='".re_link("/-".$module."_page_".$pid)."'>на сайте</a>. ";
    if ( $new_pid != 0 ) echo "Есть предыдущая версия: <button title='Заменить этой копией оригинал...' onclick='resetpage(".$new_pid."); setTimeout(\"location.reload()\", 2000);' class='small'>Заменить на последнюю резервную копию</button>";
    else echo "Предыдущей версии нет.";
    echo "</div>";

    // Подстройка редактирования
    $main_title = "Редактирование страницы";
    $name = $module;

    $data = explode(" ",$data);
    $data1 = date2normal_view($data[0]);
    $data = explode(":",$data[1]);
    $data2 = $data[0];
    $data3 = $data[1];
    $data4 = $data[2];
    $close_data = date2normal_view($close_data);
    $saveme = "_edit_sv_page";
  } else { // Если это создание новой страницы
    $golos_reiting = 0;
    if ( $pid > 0 ) { // Если только что добавили страницу
      // узнаем имя страницы
      $sql = "SELECT `title` from ".$prefix."_pages where pid='".$pid."'"; // список всех категорий
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $new_title = $row['title'];
      echo "<div class='notice success mw700'>Страница «<a target='_blank' class='green' href='".re_link("/-".$name."_page_".$pid)."'>".$new_title."</a>» добавлена. <a href='/sys.php?op=base_pages_edit_page&name=".$name."&pid=".$pid."'><span class=\"icon orange small\" data-icon=\"7\"></span>Редактировать</a>. <b>Добавим еще одну страницу?</b></div>";
    }
    // Получаем шаблон
    $sql = "select id, title, shablon from ".$prefix."_mainpage where name='".$name."' and `tables`='pages' and type='2'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $id = $row['id'];
    $title = $row['title'];
    $shablon = trim($row['shablon']);
    $shablon2 = "";
    if ($shablon=="") { 
      if ($red != 3 and $red != 4) { $shablon1 = ""; $shablon2 = ""; }
    } else { 
      $shablon = explode("[следующий]",$shablon);
      $shablon1 = $shablon[0];
      $shablon2 = $shablon[1];
    }
    if ($shablon2=="") $shablon2 = "";
    if (!isset($shablon1)) $shablon1="";
    if (!isset($shablon2)) $shablon2="";

    // Подстройка создания
    $main_title = "Добавление страницы";
    $description = $keywords = $search = $meta_title = $clean_url = "";
    $active = 1; // active
    $nocomm = 0; // nocomm
    $rss = 1; // rss
    $mainpage = 0; // mainpage
    $sor = "0";
    $close_data = $data1 = date2normal_view(date("Y-m-d", time()));
    $data2 = date("H", time());
    $data3 = date("i", time());
    $data4 = date("s", time());
    $titl = "";
    $saveme = "_save_page";
  }
  // Замена ссылок (адрес страницы)
  $trans_title = "";
  if ($clean_urls == 1) // транслит
    $trans_title = " onchange='$(\"textarea#clean_url2\").val( translite( del_space( $(\"textarea#title\").val() ) ) );'";
  if ($clean_urls == 2) // русские
    $trans_title = " onchange='$(\"textarea#clean_url2\").val( del_space( $(\"textarea#title\").val() ) );'";

  echo "<form action='sys.php' method='post' enctype='multipart/form-data'>
  <div class='fon w100 mw800'><div class='black_grad h40'>
  <button type=submit onClick=\" if (document.getElementById('to_razdel').value=='') { alert('Выберите раздел для страницы (слева сверху)'); return false; } else { submit(); } \" class='small green left3'><span class='icon white small mr-2' data-icon='c'></span> Сохранить</button>
  <span class='h1 pt10'>".$main_title."</span>";
  if ($nastroi != 1) red_vybor();
  echo "</div>
  <table width=100%><tr valign=top><td width=250 id='razdels' style='background:#e7e9ec;'>
  <p>".select("active", "0,1", "НЕТ,ДА", $active)." <b>Включить</b></p>";

  parse_str(str_replace("pages|", "", $txt_razdels[$name]));
  // голосование
  if ($golos_admin == 1) echo "<p><b>Рейтинг:</b> <input name='golos_reiting' size='3' value='".$golos_reiting."'></p>";
  else echo "<input name='golos_reiting' value='".$golos_reiting."' type='hidden'>";
  // 0,1,2,3", "Оценка (5 звезд),Кнопка «Проголосовать»,Рейтинг (кнопки + и -),Рейтинг (понравилось/не понравилось)

  echo "<br><p><span class='h2 darkgreen'>Раздел:</span><br>";
  global $id_razdel_and_bd;
  $ra = array();
  foreach ($id_razdel_and_bd as $key => $value) {
    $ra[] = "'$key': $value";
  }
  echo "<script>var ra = {".implode(",", $ra)."}; var page_id = ".$page_id.";</script>
  <select name='module' id='to_razdel' class='w100 mb20' size='10' onChange=\"
  ra_val = $('#to_razdel').val();
  izmenapapka(ra_val,'','','','addpage'); 
  show_pole(ra[ra_val],".$page_id.",ra_val,0);\">";

  $colors = array("ffffff","b4f3b4","f3f3a3","ffa4ac","b8f4f2");

  $sql = "select `name`, `title`, `color` from ".$prefix."_mainpage where `type`='2' and (`useit` like '%".aa("[страницы]")."%' or `useit` like '%".aa("[содержание]")."%') and `tables`='pages' order by `color` desc, `title`";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $name2 = $row['name'];
    if (mb_strpos($name2, "\n")) { // заменяем имя запароленного раздела
      $name2 = explode("\n", str_replace("\r", "", $name2));
      $name2 = trim($name2[0]);
    }
    $title2 = $row['title'];
    $color = $row['color'];
    $color = $colors[$color];
    if ($name == $name2) $sel = "selected"; else $sel = "";
    
    echo "<option style='background:".$color.";' value='".$name2."' ".$sel.">".$title2."</option>";
  }

  $sql = "select * from ".$prefix."_pages_categories where `module`='".$name."' and `tables`='pages' order by `parent_id`, `title`";
  $result = $db->sql_query($sql);
  $numrows = $db->sql_numrows($result);
  if ($numrows > 10) $size = 10*16; 
  else $size = ($numrows+2)*16;
  echo "</select>";

  //if ($numrows > 0) {
    echo "<div class='in_r'><div id=showa class='in_r'><a style='cursor:pointer;' onclick=\"show('hidea'); show('showa'); $('#to_papka').width(500); $('#to_papka').height(400);\">развернуть &rarr;</a></div><div id=hidea style='display:none;'><a style='cursor:pointer;' onclick=\"show('showa'); show('hidea'); $('#to_papka').width(300); $('#to_papka').height(155);\">&larr; свернуть</a></div></div><span class=h2>Папка:</span><br>

    <div id='izmenapapka'><script>izmenapapka($('#to_razdel').val(),'".$cid."','','','addpage');</script></div>";
  //} else echo "<input type='hidden' name='cid' value='0'><p><i>В разделе нет папок.</i></p>";    
  
  echo "<div id='mainrazdel' class='dark_pole2'><a class='base_page' onclick=\"if ( $('#dop').is(':hidden') ) $('#mainrazdel').attr('class', 'dark_pole2sel'); else { $('#mainrazdel').attr('class', 'dark_pole2');} $('#main').toggle(); $('#dop').toggle('slow'); \"><div id='mainrazdel'><span class='icon gray large in_b' data-icon='z'><span aria-hidden='true'>z</span></span><span class='plus20'>Дополнительные настройки</span></div></a></div> ";

  if ( $pid > 0 & $new==0) echo "<br><b>Искать по названию в:</b><ul>
<li><a target='_blank' href='http://yandex.ru/yandsearch?text=".$titl."'>Яндексе</a>
<li><a target='_blank' href='http://images.yandex.ru/yandsearch?text=".$titl."'>Яндекс.Картинках</a>
<li><a target='_blank' href='http://market.yandex.ru/search.xml?text=".$titl."'>Яндекс.Маркете</a>
<li><a target='_blank' href='http://www.google.ru/search?q=".$titl."'>Гугле</a>
<li><a target='_blank' href='http://www.google.ru/search?tbm=isch&q=".$titl."'>Гугл.Картинках</a></ul>";

  echo "</td><td style='padding:0;'><a class='punkt' title='Свернуть/развернуть левую колонку' onclick='$(\"#razdels\").toggle(\"slow\");'><div class='polosa_razdelitel'><div id='rotateText'><nobr>↑ Сворачивает левую колонку ↑</nobr></div></div></a></td><td>";

  echo "<div style='display:none' id='dop'><a title='Закрыть' class='punkt' onclick=\"if ( $('#dop').is(':hidden') ) $('#mainrazdel').attr('class', 'dark_pole2sel'); else $('#mainrazdel').attr('class', 'dark_pole2'); $('#main').toggle(); $('#dop').toggle('slow'); \"><div class='radius' style='font-size:12pt; width:20px; height: 20px; color: white; text-align:center; float:right; margin:5px; background: #bbbbbb;'>&nbsp;x&nbsp;</div></a>";

  if ($clean_urls != 0) echo "<h3>Замена адреса страницы (ссылки):</h3><textarea id='clean_url2' name='clean_url2' class='big w100' rows='2' cols='10'>".$clean_url."</textarea>";
  else echo "<input name='clean_url2' type='hidden' value='".$clean_url."'>";

  echo "<div class='radius w100 p5' style='border: 1px solid #1D6DC1;'><h2 style='color: #1D6DC1;'>SEO настройки</h2>
  <h3>Замена TITLE: <a onclick=\"show('help17')\" class='help'>?</a></h3><textarea id='meta_title2' name='meta_title2' class='big w95' rows='2' cols='10'>".$meta_title."</textarea>
  <br><div id='help17' style='display:none;'><span class=small>По-умолчанию — пустое поле и TITLE будет создан автоматически: «название страницы — название папки (если есть) — название раздела».</span><p></div>

  <h3>Ключевые слова для поисковых систем: <a onclick=\"show('help10')\" class='help'>?</a></h3><textarea id='keywords2' name='keywords2' class='big w95' rows='2' cols='10'>".$keywords."</textarea>
  <br><div id='help10' style='display:none;'><span class=small>Максимум 1000 символов. Разделять словосочетания желательно запятой. Если пусто - используются <b>Теги</b> (если и они пустые - используются Ключевые словосочетания из <a href='/sys.php?op=options' target='_blank'>Настроек портала</a>).</span><p></div>

  <h3>Описание для поисковых систем: <a onclick=\"show('help11')\" class='help'>?</a></h3><textarea id='description2' name='description2' class='big w95' rows='4' cols='10'>".$description."</textarea>
  <br><div id='help11' style='display:none;'><span class=small>Максимум 200 символов. Если пусто - используется <b>Название</b> страницы.</span><p></div>";

  if ($page_id > 0) {
    echo "<input type='hidden' name='pid' value='".$pid."'>";
    seo(true);
  } else seo();


  $tags = array();
  $sql = "select `search` from ".$prefix."_pages where `tables`='pages' and `active`='1' and `copy` ='0'";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    if (trim($row['search']) != "") {
      $tag = array();
      $tag = explode(",",$row['search']);
      foreach ($tag as $tag1) {
        $tag1 = trim($tag1);
        if ($tag1 != "") $tags[] = $tag1;
      }
    }
  }
  $tags = implode("','", array_unique($tags));
  //if ($tags == "0") $search_tags = $tags ="";
  echo "</div>

  <h3>Тэги (слова для похожих по тематике страниц): $search_tags $tags<a onclick=\"show('help12')\" class='help'>?</a></h3> 
  <input name='search' id='question_tags' class='big w100' value='".$search_tags."'>
  <script>
  $(function() {
  $('#question_tags').tagit({
      removeConfirmation: true,
      allowSpaces: true,
      tabIndex: 3,
      autocomplete: {delay: 0, minLength: 1},
      availableTags: ['".$tags."']
      });
  });
  </script>
<div id='help12' style='display:none;'><span class=small>Разделять запятыми, можно писать словосочетания.<br>
  Можно создать Блок «Облако тегов». Теги также могут выводиться на страницах и облаком тегов в начале раздела (см. в настройках Раздела).</span><br></div>

  <table class='w100'><tr><td>
  <p>".select("mainpage", "0,1", "НЕТ,ДА", $mainpage)." На главную страницу <a onclick=\"show('help_mainpage')\" class='help'>?</a>
  </td><td>
  <p>".select("rss", "0,1", "НЕТ,ДА", $rss)." Добавить в RSS <a onclick=\"show('help_rss')\" class='help'>?</a>
  </td><td>
  <p>".select("nocomm", "0,1", "НЕТ,ДА", $nocomm)." Запретить комментарии</label> <a onclick=\"show('help_nocomm')\" class='help'>?</a>
  </td><td>
  Очередность: <input type='text' name='sor' value='".$sor."' size='5' style='text-align:center;'> <a onclick=\"show('help_sor')\" class='help'>?</a>
  </td></tr></table>

  <div id='help_nocomm' style='display:none;'>Если в данном разделе разрешены комментарии — вы можете отключить их выборочно на данной странице, поставив галочку.<br><br></div>
  <div id='help_rss' style='display:none;'>Технология RSS похожа на e-mail подписку на новости — в RSS-программу, сайт RSS-читалки или встроенную систему чтения RSS в браузере добавляется ссылка на данный сайт, после чего название и предисловие всех новых страниц, отмеченных данной галочкой, будут видны подписавшемуся человеку и он сможет быстро ознакомиться с их заголовками, не заходя на сайт. Если что-то ему понравится — он откроет сайт и прочитает подробности. RSS используется для постепенного увеличения количества посетителей сайта путем их возвращения на сайт за интересной информацией. <a href='http://yandex.ru/yandsearch?text=Что+такое+RSS%3F' target=_blank>Подробнее о RSS?</a><br><br></div>
  <div id='help_mainpage' style='display:none;'>Если отметить эту галочку, данная страница будет отображаться в блоке, который настроен на отображение только помеченных этой галочкой страниц, или не будет отображаться в блоке, который настроен на показ всех неотмеченных галочкой страниц.<br><br></div>
  <div id='help_sor' style='display:none;'>Настраивается в настройках раздела. Может быть равна цифре. Применяется для ручной сортировки страниц. Лучше всего делать кратной 10, например 20, 30, 40 и т.д. для того, чтобы было удобно вставлять страницы между двумя другими. Если очередность у двух страниц совпадает, сортировка происходит по дате.<br><br></div>";


  if ($future_date == "1") {
    $create_date = "Дата отложенной публикации";
    $data2 = $data3 = $data4 = "00"; $notime = " class='hide'";
  } else { $create_date = "Дата публикации"; $notime = ""; }
  echo "<h2>".$create_date.":</h2> <script>$(function() { $.datepicker.setDefaults( $.datepicker.regional[ \"ru\" ] ); $( \"#f_date_c999\" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: \"d MM yy\", showAnim: 'slide' }); });</script>
  <INPUT type=text name='data1' id=\"f_date_c999\" value=\"".$data1."\" readonly='1' size='18'> 

  <nobr".$notime."> Время: ".select("data2", "00,01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,16,17,18,19,20,21,22,23", "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23", $data2, " style='width:60px;'")." ч 
  ".select("data3", "00,10,15,20,25,30,35,40,45,50,55", "00,10,15,20,25,30,35,40,45,50,55", $data3, " style='width:60px;'")." м <input type=text name='data4' value='".$data4."' class='f12' size=3 onclick=\"this.value='00'\"> с <a onclick=\"show('help0')\" class='help'>?</a></nobr>

  <div id='help0' style='display:none;'><br>Для выбора даты из календаря нажмите по дате. Для обнуления секунд кликните по ним. Минуты представлены текущим вариантом или выбором из основного интервала для ускорения работы.<br></div>";

  if ($close_date == "1") echo "<h2>Дата отключения:</h2> <script>$(function() { $.datepicker.setDefaults( $.datepicker.regional[ \"ru\" ] ); $( \"#f_date_999\" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: \"d MM yy\", showAnim: 'slide' }); });</script>
  <INPUT type=text name='close_data' id=\"f_date_999\" value=\"".$close_data."\" readonly='1' size='18'> <br>Если выбранная дата отключения позже даты публикации, страница не будет показана на сайте после этой даты.";
  else echo input("close_data", $close_data, 40, "hidden");

  if ($page_id > 0) echo "<a onclick=\"show('slugebka')\" class=punkt>Скрытая информация</a><br><div id='slugebka' style='display:none;'><div class=radius><span class=small>Лучше не менять.</span><br><h3 style='display:inline'>Копия:</h3><INPUT type=text name=cop value='".$copy."' size=3><a onclick=\"show('help18')\" class=help>?</a><br><div id='help18' style='display:none;'>У страниц-копий указывается один и тот же номер — номер оригинальной страницы. Если это не копия, а единственный оригинал, цифра равна 0.<br></div><br><h3 style='display:inline'>Кол-во комментариев:</h3><INPUT type=text name=com value='".$comm."' size=3><br><br><h3 style='display:inline'>Кол-во посещений:</h3><INPUT type=text name=count value='".$counter."' size=3></div></div><br>";
  echo "</div>
  <div id='main'>
  <h1>Название страницы (заголовок)</h1>
  <textarea class='f16 w100 h40' name=title id=title rows=1 cols=10".$trans_title.">".$titl."</textarea>
  <div class='right3'>Проверка текста: 
    <a href='#plagiat' onclick='plagiat()' class='button small blue'>уникальность</a> 
    <a href='http://orthography.morphology.ru' target='_blank' class='button small blue'>орфография</a> 
    <a href='http://speller.yandex.net/speller/1.0/index.html' target='_blank' class='button small blue'>орфография 2</a> 
    <a href='http://test-the-text.ru' target='_blank' class='button small blue'>информационный стиль</a>
  </div><p>
  <h1>Предисловие</h1>";
  echo redactor($red, $shablon1, 'open_text', 'main_text'); // редактор: тип редактора, редактируемое поле
  echo "<h1>Содержание (основной текст)</h1>";
  echo redactor2($red, $shablon2, 'main_text');
  echo "<a name='plagiat'></a><div id='plagiat' class=hide style='min-height:700px;'></div>
  <input type='hidden' name='foto' value=''><input type='hidden' name='price' value=''>";
  //$sql = "select `text` from ".$prefix."_mainpage where (`name` = '".$name."' or `name` like '".$name." %') and `type`='2'";
  //$result = $db->sql_query($sql);
  //$row = $db->sql_fetchrow($result);
  //$tex = $row['text'];

  // Подсоединие списков ////////////////////////////////
  if ($page_id > 0) {
    if ($copy != 0) $page_id = $copy;
    // определяем id раздела
    global $id_razdel_and_bd;
    $id = $id_razdel_and_bd[$module];
  }

  echo "<div id='pole".$page_id."'><script>show_pole(".$id.",".$page_id.",$('#to_razdel').val(),".$cid.");</script></div>";

  echo "<input type=hidden name=op value='base_pages".$saveme."'>";
  echo "</div></td></tr></table></div></form>";
  admin_footer();
}


####################################################################################
function base_pages_save_page($cid, $module, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $golos_reiting, $nocomm, $price, $add, $data1, $data2, $data3, $data4, $meta_title2, $clean_url2, $keywords2, $description2, $sor, $open_text_mysor, $main_text_mysor, $close_data) {
  global $red, $prefix, $db, $admin_file;
  $now = date("Y-m-d H:i:s");
  $foto = "";
  $price = intval($price); // это магазин?

  $search = trim(str_replace(",",", ",$search));
  $search = str_replace(".",", ",$search);
  $search = str_replace("   "," ",$search);
  $search = str_replace("  "," ",$search);
  # pid module cid title open_text main_text date counter active golos comm foto search mainpage
  //if ($open_text == " <br><br>") $open_text = "";
  //if ($main_text == " <br><br>") $main_text = "";

  $sor = intval($sor);
  $rss = intval($rss);
  $nocomm = intval($nocomm);
  $golos_reiting = intval($golos_reiting);
  $open_text = str_replace("http://http://", "http://", str_replace("http//", "http://", $open_text));
  $main_text = str_replace("http://http://", "http://", str_replace("http//", "http://", $main_text));
  $open_text = mysql_real_escape_string(form($module, $open_text, "open"));
  $main_text = mysql_real_escape_string(form($module, $main_text, "main"));
  $title = mysql_real_escape_string(form($module, trim($title), "title"));
  $keywords2 = mysql_real_escape_string(trim(str_replace("  "," ",str_replace("   "," ",str_replace(" ,",", ",$keywords2)))));
  $description2 = mysql_real_escape_string(trim($description2));
  $meta_title2 = mysql_real_escape_string($meta_title2);
  $search = mysql_real_escape_string($search);

  $clean_url2 = strtr($clean_url2, array('____'=>'_', '___'=>'_', '__'=>'_', '*'=>'',':'=>'','('=>'',')'=>'','  '=>'',' '=>'', ' '=>'_', '.'=>'', ','=>'', '!'=>'', '?'=>'', '=>'=>'', ';'=>'', '&'=>'_and_', '%'=>'', '$'=>'', '#'=>'', '№'=>'', '@'=>'', '^'=>'', '='=>'', '\''=>'','"'=>'','«'=>'', '»'=>'', '____'=>'_', '___'=>'_', '__'=>'_'));

  $data = date2normal_view($data1, 1)." $data2:$data3:$data4";
  $close_data = date2normal_view($close_data, 1);

  $sql = "INSERT INTO ".$prefix."_pages (`pid`,`module`,`cid`,`title`,`open_text`,`main_text`,`date`,`redate`,`counter`,`active`,`golos`,`comm`,`foto`,`search`,`mainpage`,`rss`,`price`,`description`,`keywords`,`tables`,`copy`,`sort`,`nocomm`,`meta_title`,`clean_url`,`close_date`) VALUES (NULL, '".$module."', '".$cid."', '".$title."', '".$open_text."', '".$main_text."', '".$data."', '".$now."', '0', '".$active."', '".$golos_reiting."', '0', '".$foto."', '".$search."', '".$mainpage."', '".$rss."', '".$price."', '".$description2."', '".$keywords2."', 'pages', '0','".$sor."', '".$nocomm."', '".$meta_title2."', '".$clean_url2."', '".$close_data."');";
  $db->sql_query($sql) or die ("Не удалось сохранить страницу. Сообщите разработчику нижеследующее:".$sql);
  // Узнаем получившийся номер страницы ID
  $sql = "select pid from ".$prefix."_pages where title='".$title."' and date='".$data."'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $page_id = $row['pid'];
  /////////////////////////////////////////////////////////////////////////
  // РАБОТА СО СПИСКАМИ
  if (!isset($add) or $add == "") $add = array();

  foreach ($add as $name => $elements) {
    // Получение информации о каждом списке
    if ($name != "") {
    $sql = "select * from ".$prefix."_mainpage where `name`='".$name."' and `type`='4'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $options = explode("|", $row['text']); 
    $options = $options[1];
    $type = 0; 
    $shablon = ""; 
    parse_str($options); // раскладка всех настроек списка
    switch($type) {
      case "6": // регион
        $id_regions = $_POST['id_regions'];
        if (!isset($id_regions)) $id_regions = $elements;
        $region = $db->sql_fetchrow($db->sql_query("select `name` from ".$prefix."_regions where `id`='".$id_regions."'"));
        $db->sql_query("INSERT INTO ".$prefix."_spiski (`id`, `type`, `name`, `opis`, `sort`, `pages`, `parent`) VALUES (NULL, '".$name."', '".$region['name']."', '', '0', ' ".$page_id." ', '0');") or die ('Ошибка: Не удалось сохранить список. 6'); 
      break;
      ////////////////////////////////////////////////////////////////////////////
      case "1": // текст
      case "4": // строка
      case "5": // число
          // Проверяем наличие подобного элемента
              $sql = "SELECT `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and `name`='".$elements."' limit 1";
              $result = $db->sql_query($sql);
              $numrows = $db->sql_numrows($result);
              if ($numrows == 1) { // если элемент найден
                $row = $db->sql_fetchrow($result);
                $s_pages = $row['pages'];
                //$s_name = $row['name'];
                $s_pages .= " ".$page_id." ";
                $s_pages = str_replace("  "," ",$s_pages);
                // если переносим в существующий элемент
                $db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".$s_pages."' WHERE `type`='".$name."' and `name`='".$elements."'") or die ('Ошибка: Не удалось обновить список. 1');
              } else { // если элемент новый
                $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', ' ".$page_id." ', '0');") or die ('Ошибка: Не удалось сохранить список. 4');
              }
      break;
      ////////////////////////////////////////////////////////////////////////////
      case "3": // период времени
              // создаем диапазон дат и все их проверяем
              $elements = explode("|",$elements);
              $dat1 = date2normal_view($elements[0], 1);
              $dat2 = date2normal_view($elements[1], 1);
              $period = period($dat1, $dat2);
              // и все даты проверяем на наличие в БД
              $upd = array();
              $noupd = array();
              $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='".$name."' order by name";
              $result = $db->sql_query($sql);
              while ($row = $db->sql_fetchrow($result)) {
                $nam = $row['name']; // дата
                $pag = trim($row['pages']); // страницы
                if (in_array($nam, $period)!=FALSE) { 
                $noupd[] = $nam; // для INSERT
                if (strstr($pag,$page_id)==FALSE) $upd[] = $nam; // для UPDATE
                }
              }
              $insert = array();
              $update = array();
              foreach ($upd as $up) {
                $update[] = "name='".$up."'";
              }
              foreach ($period as $per) {
                if (!in_array($per, $noupd)) $insert[] = "(NULL, '".$name.", '".$per."', '', '0', ' $page_id ', '0')";
              }
              $insert = implode(", ",$insert);
              $update = implode(" or ",$update);
              $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='".$name."' and (".$update.") order by name";
              $result = $db->sql_query($sql);
              while ($row = $db->sql_fetchrow($result)) {
              $na = $row['name']; // дата
              $pa = $row['pages']; // страницы
                  if (trim($update) != "") {
                  $db->sql_query("UPDATE ".$prefix."_spiski SET pages = ' $pa $page_id ' WHERE type='".$name."' and name='".$na."'") or die ("Ошибка: Не удалось обновить списки. 4 $page_id $name");
                  }
              }
              if (trim($insert) != "") {
                $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES ".$insert.";") or die ('Ошибка: Не удалось сохранить списки. 5');
              }
      break;
      ////////////////////////////////////////////////////////////////////////////
      case "2": // файл НЕОКОНЧЕНО!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
              // Смотрим настройки - тип файла и что с ним делать
              // Закачиваем файл
              // Транслит файла и смена имени на тип и дату
              // Изменение размеров
              // Записываем ссылку на него в определенное поле
      break;
      ////////////////////////////////////////////////////////////////////////////
      case "0": // список (одно значение)
              // Проверяем сколько элементов в списке
              //$num = count($elements);
              //for ($x=0; $x < $num; $x++) { // посчитали сколько номеров списка
              if ($elements != 0) {
                // узнаем какие страницы уже есть у этого номера из списка
                $sql = "SELECT pages FROM ".$prefix."_spiski WHERE id='".$elements."'";
                $result = $db->sql_query($sql);
                $row = $db->sql_fetchrow($result);
                $s_pages = $row['pages'];
                if (mb_strpos($s_pages," $page_id ") < 1) {
                  $s_pages .= " $page_id ";
                  $s_pages = str_replace("  "," ",$s_pages);
                  // теперь присвоем каждому из элементов списка id страницы, которую редактируем.
                  $db->sql_query("UPDATE ".$prefix."_spiski SET pages='".$s_pages."' WHERE id='".$elements."'") or die('Ошибка при добавлении страницы в элемент списка. 9. $name');
                }
              }
              //}
      break;
      ////////////////////////////////////////////////////////////////////////////
      case "7": // список (несколько значений)
              // Проверяем сколько элементов в списке
              $num = count($elements);
              for ($x=0; $x < $num; $x++) { // посчитали сколько номеров списка
                  if ($elements[$x] != 0) {
                    // узнаем какие страницы уже есть у этого номера из списка
                    $sql = "SELECT pages FROM ".$prefix."_spiski WHERE id='".$elements[$x]."'";
                    $result = $db->sql_query($sql);
                    $row = $db->sql_fetchrow($result);
                    $s_pages = $row['pages'];
                    if (mb_strpos($s_pages," $page_id ") < 1) {
                      $s_pages .= " $page_id ";
                      $s_pages = str_replace("  "," ",$s_pages);
                      // теперь присвоем каждому из элементов списка id страницы, которую редактируем.
                      $db->sql_query("UPDATE ".$prefix."_spiski SET pages='".$s_pages."' WHERE id='".$elements[$x]."'") or die('Ошибка при добавлении страницы в элемент списка. 9. $name');
                    }
                  }
              }
      break;
    }
    }
  }
  $db->sql_query("DELETE FROM ".$prefix."_spiski WHERE name='-00-00'"); 
  // Удаление ошибок. Потом поправить, чтобы не было их!!!
  Header("Location: sys.php?op=base_pages_add_page&name=".$module."&cid=".$cid."&red=".$red."&new=1&pid=".$page_id."#1");
}
###################################################################################
function base_pages_edit_sv_page($pid, $module, $cid, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $golos_reiting, $nocomm, $price, $add, $data1, $data2, $data3, $data4, $meta_title2, $clean_url2, $keywords2, $description2, $com, $cop, $count, $sor, $open_text_mysor, $main_text_mysor, $close_data) {
  global $prefix, $db;
  $now = date("Y-m-d H:i:s");
  // Делаем резервную копию!
  $sql = "SELECT `module`,`cid`,`title`,`open_text`,`main_text`,`date`,`counter`,`active`,`golos`,`comm`,`foto`,`search`,`mainpage`,`rss`,`price`,`description`,`keywords`,`sort`,`nocomm`,`meta_title`,`clean_url`,`close_data` FROM ".$prefix."_pages WHERE `pid`='".$pid."'";
  $result = $db->sql_query($sql);
  list($p_module, $p_cid, $p_title, $p_open_text, $p_main_text, $p_date, $p_counter, $p_active, $p_golos, $p_comm, $p_foto, $p_search, $p_mainpage, $p_rss, $p_price, $p_description, $p_keywords, $p_sort, $p_nocomm, $p_meta_title, $p_clean_url, $p_close_data) = $db->sql_fetchrow($result);
  $foto = "";
  $price = intval($price); // это магазин? убрать/доработать
  $search = trim(str_replace(",",", ",$search));
  $search = str_replace(".",", ",$search);
  $search = str_replace("   "," ",$search);
  $search2 = str_replace("  "," ",$search);
  if ($mainpage == "") $mainpage = 0;
  $sor = intval($sor);
  $rss = intval($rss);
  $nocomm = intval($nocomm);
  $golos_reiting = intval($golos_reiting);
  $data1 = date2normal_view($data1, 1);
  $close_data = date2normal_view($close_data, 1);
  // получаем настройки раздела по отложенной и отключенной публикации
  $future_date = $close_date = 0;
  $result = $db->sql_query("SELECT `text` FROM ".$prefix."_mainpage where `name`='".$module."'");
  $row = $db->sql_fetchrow($result);
  $options = explode("|",$row['text']);
  parse_str( str_replace($options[0]."|","",$row['text']) );
  if ( ( $future_date == 1 && $data1 > $now ) || 
    ( $close_date == 1 && $close_data < $now ) ) $active = "0";

  // Обратное преобразование textarea (замена русской буквы е)
  $open_text = str_replace("http://http://", "http://", str_replace("http//", "http://", $open_text));
  $main_text = str_replace("http://http://", "http://", str_replace("http//", "http://", $main_text));
  $main_text = str_replace("tеxtarea","textarea",$main_text); // ireplace
  $open_text = str_replace("tеxtarea","textarea",$open_text); // ireplace
  $open_text = mysql_real_escape_string(form($module, $open_text, "open"));
  $main_text = mysql_real_escape_string(form($module, $main_text, "main"));
  $title = mysql_real_escape_string(form($module, $title, "title"));
  $keywords2 = mysql_real_escape_string(trim(str_replace("  "," ",str_replace("   "," ",str_replace(" ,",", ",$keywords2)))));
  $description2 = mysql_real_escape_string(trim($description2));
  $meta_title2 = mysql_real_escape_string($meta_title2);
  $search2 = mysql_real_escape_string($search2);

  $p_open_text = mysql_real_escape_string(form($module, $p_open_text, "open"));
  $p_main_text = mysql_real_escape_string(form($module, $p_main_text, "main"));
  $p_title = mysql_real_escape_string(form($module, $p_title, "title"));
  $p_keywords = mysql_real_escape_string(trim(str_replace("  "," ",str_replace("   "," ",str_replace(" ,",", ",$p_keywords)))));
  $p_description = mysql_real_escape_string(trim($p_description));
  $p_meta_title = mysql_real_escape_string($p_meta_title);

  $data = $data1." $data2:$data3:$data4";
  $clean_url2 = strtr($clean_url2, array('____'=>'_', '___'=>'_', '__'=>'_', '*'=>'',':'=>'','('=>'',')'=>'','  '=>'',' '=>'', ' '=>'_', '.'=>'', ','=>'', '!'=>'', '?'=>'', '=>'=>'', ';'=>'', '&'=>'_and_', '%'=>'', '$'=>'', '#'=>'', '№'=>'', '@'=>'', '^'=>'', '='=>'', '\''=>'','"'=>'','«'=>'', '»'=>'', '____'=>'_', '___'=>'_', '__'=>'_'));

  $sql = "UPDATE ".$prefix."_pages SET `module`='".$module."', `cid`='".$cid."', `title`='".$title."', `open_text`='".$open_text."', `main_text`='".$main_text."', `date`='".$data."', `redate`='".$now."', `counter`='".$count."', `active`='".$active."', `golos`='".$golos_reiting."', `comm`='".$com."', `foto`='".$foto."', `search`='".$search2."', `mainpage`='".$mainpage."', `rss`='".$rss."', `price`='".$price."', `description`='".$description2."', `keywords`='".$keywords2."', `copy`='".$cop."', `sort`='".$sor."', `nocomm`='".$nocomm."', `meta_title`='".$meta_title2."', `clean_url`='".$clean_url2."', `close_date`='".$close_data."' WHERE `pid`='".$pid."';";
  $db->sql_query($sql) or die('Не удалось сохранить изменения... Передайте нижеследующий текст разработчику:<br>'.$sql);

  // Делаем резервную копию
  if ($p_active != 3) // если это не добавленная пользователем страница
  $db->sql_query("INSERT INTO ".$prefix."_pages (`pid`,`module`,`cid`,`title`,`open_text`,`main_text`,`date`,`redate`,`counter`,`active`,`golos`,`comm`,`foto`,`search`,`mainpage`,`rss`,`price`,`description`,`keywords`,`tables`,`copy`,`sort`,`nocomm`,`meta_title`,`clean_url`,`close_date`) VALUES (NULL, '$p_module', '$p_cid', '$p_title', '$p_open_text', '$p_main_text', '$p_date', '$now', '$p_counter', '$p_active', '$p_golos', '$p_comm', '$p_foto', '$p_search', '$p_mainpage', '$p_rss', '$p_price', '$p_description', '$p_keywords', 'backup', '$pid', '$p_sort', '$p_nocomm', '$p_meta_title', '$p_clean_url', '$p_close_data' );") or die("Резервная копия не создана...");

  // Ярлык?
  $and_copy = "";
  if ($cop != 0) { // Узнаем наличие других копий
    $sql = "select pid from ".$prefix."_pages where copy='".$cop."' and pid!='".$pid."'";
    $result = $db->sql_query($sql);
    $and_copy = array();
    while ($row = $db->sql_fetchrow($result)) {
      $pidX = $row['pid'];
      $and_copy[] = "pid='".$pidX."'";
      //if (function_exists('recash')) 
      recash("page_".$pidX, 0); // Обновление кеша ##
    }
    $and_copy = implode(" or ",$and_copy);
    $db->sql_query("UPDATE ".$prefix."_pages SET `title`='".$title."', `open_text`='".$open_text."', `main_text`='".$main_text."', `date`='".$data."', `redate`='".$data2."', `counter`='".$count."', `active`='".$active."', `golos`='".$golos_reiting."', `comm`='".$com."', `foto`='".$foto."', `search`='".$search2."', `mainpage`='".$mainpage."', `rss`='".$rss."', `price`='".$price."', `description`='".$description2."', `keywords`='".$keywords2."', `sort`='".$sor."', `meta_title`='".$meta_title2."', `close_date`='".$close_data."' WHERE ".$and_copy.";");
  }

  global $siteurl;
  if ($active == 1) { // function_exists('recash') and 
    recash("page_".$pid); // Обновление кеша ##
    recash("cat_".$cid, 0); ####################
    recash("cat_".$cid."_page_0", 0); ##########
    recash("cat_".$cid."_page_1", 0); ##########
    recash($module,0); ###############################
  }

  // РАБОТА СО СПИСКАМИ
  $page_id = $pid;
  if (isset($cop)) if ($cop != 0) $page_id = $cop;
  if (!isset($add) or $add == "") $add = array();

  save_spiski($add, $page_id);

  $db->sql_query("DELETE FROM ".$prefix."_spiski WHERE name='-00-00'"); // Удаление ошибок. Исправить!!!
  Header("Location: sys.php?op=base_pages_edit_page&name=".$module."&new=1&pid=".$pid);
}
###################################################################################
function save_spiski ($add, $page_id) {
  global $db, $prefix;
    // Получение информации о каждом списке
  foreach ($add as $name => $elements) { 
    $sql = "select * from ".$prefix."_mainpage where `name`='".$name."' and `type`='4' and `tables` = 'pages'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $options = explode("|", $row['text']); 
    $options = $options[1];
    $type = 0; 
    $shablon = ""; 
    parse_str($options); // раскладка всех настроек списка
    switch($type) {

      case "6": // регион
        del_spiski($page_id, $name); // Стираем упоминания о списках для переназначения
        $sql = "SELECT `id`, `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='$name' and `pages` like '% ".$page_id." %'";
        $result = $db->sql_query($sql);
        $row = $db->sql_fetchrow($result);
        $nums = $db->sql_numrows($result);
        $del_id = $row['id'];
        $del_name = $row['name'];
        $del_pages = $row['pages'];
        if ($nums==0 or ($elements != $del_name and $del_name!="")) {
          $id_regions = $_POST['id_regions'];
          if (!isset($id_regions)) $id_regions = $elements;
           $region = $db->sql_fetchrow($db->sql_query("select `name` from ".$prefix."_regions where `id`='".$id_regions."'"));
          $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$region['name']."', '', '0', ' ".$page_id." ', '0');") or die ('Ошибка: Не удалось сохранить список. 6'); 
        }
      break;

      case "1": // текст
      case "4": // строка
      case "5": // число
        if ($type == "5") $elements = filter_var(str_replace(",", ".", $elements), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        // Проверяем наличие подобного элемента
        $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and `pages` like '% ".$page_id." %'";
        $result = $db->sql_query($sql);
        $numrows = $db->sql_numrows($result);
        if ($numrows > 0) { // если элемент найден
          $row = $db->sql_fetchrow($result);
          $s_pages = $row['pages'];
          $s_name = $row['name'];
          if ($elements != $s_name) { // Если значение изменилось
            if ($s_pages == " ".$page_id." ") {// Удалим весь список, если других страниц в нем нет
              $db->sql_query("DELETE FROM ".$prefix."_spiski WHERE `type`='".$name."' and `name`='".$s_name."'");
            } else { // Или удалим лишь номер страницы из списка, если в нем есть другие страницы
              $s_pages = str_replace(" ".$page_id." "," ", $s_pages);
              $db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".$s_pages."' WHERE `type`='".$name."' and `name`='".$s_name."'") or die ('Ошибка: Не удалось обновить список. 1');
            }
            // Если name != 0 - создадим новый список или добавим в существующий
            if ($elements != "") {
              // Проверим, нет ли такого списка
              $sql = "SELECT `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and `name`='".$elements."'";
              $result = $db->sql_query($sql);
              $numrows = $db->sql_numrows($result);
              if ($numrows > 0) { // Список есть - добавляем к нему
                $row = $db->sql_fetchrow($result);
                $s_pages2 = $row['pages'];
                $s_pages2 = str_replace("  "," ", $s_pages2." ".$page_id." ");
                $db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".$s_pages2."' WHERE `type`='".$name."' and `name`='".$elements."'") or die ('Ошибка: Не удалось обновить список. 1');
              } else { // Списка нет - создаем новый
                $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', ' ".$page_id." ', '0');") or die ('Ошибка: Не удалось сохранить список. 2');
              }
            }
          } // если значение не изменилось - ничего не трогаем
        } elseif ($elements != "") { // Если элемент не найден - проверим, нет ли такого списка
          $sql = "SELECT `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and `name`='".$elements."'";
          $result = $db->sql_query($sql);
          $numrows = $db->sql_numrows($result);
          $row = $db->sql_fetchrow($result);
          if ($numrows > 0) { // Список есть - добавляем к нему
            $s_pages2 = $row['pages'];
            $s_pages2 = str_replace("  "," ", $s_pages2." ".$page_id." ");
            $db->sql_query("UPDATE ".$prefix."_spiski SET `pages`='".$s_pages2."' WHERE `type`='".$name."' and `name`='".$elements."'") or die ('Ошибка: Не удалось обновить список. 1');
          } else { // Списка нет - создаем новый
            $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', ' ".$page_id." ', '0');") or die ('Ошибка: Не удалось сохранить список. 2');
          }
        }
      break;

      case "3": // период времени
        del_spiski($page_id, $name); // Стираем упоминания о списках для переназначения
        // создаем диапазон дат и все их проверяем
        $elements = explode("|",$elements);
        $dat1 = date2normal_view($elements[0], 1);
        $dat2 = date2normal_view($elements[1], 1);
        $period = period($dat1, $dat2);
        // и все даты проверяем на наличие в БД
        $upd = array();
        $noupd = array();
        $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' order by `name`";
        $result = $db->sql_query($sql);
        while ($row = $db->sql_fetchrow($result)) {
          $nam = $row['name']; // дата
          $pag = trim($row['pages']); // страницы
          if (in_array($nam, $period)!=FALSE) { 
            $noupd[] = $nam; // для INSERT
            if (strstr($pag,$page_id)==FALSE) $upd[] = $nam; // для UPDATE
          }
        }
        $insert = array();
        $update = array();
        foreach ($upd as $up) {
          $update[] = "`name`='".$up."'";
        }
        foreach ($period as $per) {
          if (!in_array($per, $noupd)) $insert[] = "(NULL, '".$name."', '".$per."', '', '0', ' ".$page_id." ', '0')";
        }
        $insert = implode(", ",$insert);
        $update = implode(" or ",$update);
        $sql = "SELECT `name`, `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and (".$update.") order by `name`";
        $result = $db->sql_query($sql);
        while ($row = $db->sql_fetchrow($result)) {
          $na = $row['name']; // дата
          $pa = $row['pages']; // страницы
          $db->sql_query("UPDATE ".$prefix."_spiski SET `pages` = ' ".$pa." ".$page_id." ' WHERE `type`='".$name."' and `name`='".$na."'") or die ("Ошибка: Не удалось обновить списки. $page_id $name");
        }
        if (trim($insert) != "") {
          $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES ".$insert.";") or die ('Ошибка: Не удалось сохранить списки.');
        }
      break;

      case "2": // файл
      // Неокончено !!!
      break;

      case "7": // список (несколько значений)
        $num = count($elements); // сколько элементов в списке
        del_spiski($page_id, $name); // Стираем упоминания о списках для переназначения
        for ($x=0; $x < $num; $x++) { // посчитали сколько номеров списка
          if ($elements[$x] != 0) { // Если это не "Не выбрано"
            // узнаем какие страницы уже есть у этого номера из списка
            $sql = "SELECT `pages` FROM ".$prefix."_spiski WHERE `type`='".$name."' and `id`='".$elements[$x]."'";
            $result = $db->sql_query($sql);
            $row = $db->sql_fetchrow($result);
              $save_pages = str_replace("  "," ", $row['pages']." ".$page_id." ");
              // теперь присвоем каждому из элементов списка id страницы, которую редактируем.
              $db->sql_query("UPDATE ".$prefix."_spiski SET `pages` =  '".$save_pages."' WHERE `type`='".$name."' and  `id` = '".$elements[$x]."' LIMIT 1 ;") or die('Ошибка при добавлении страницы в элемент списка');
          }
        } 
      break;
      
      case "0": // список (одно значение)
        del_spiski($page_id, $name); // Стираем упоминания о списках для переназначения
        if ($elements != 0) { // Если это не "Не выбрано"
          // узнаем какие страницы уже есть у этого номера из списка
          $sql = "SELECT `pages` FROM ".$prefix."_spiski WHERE `id`='".$elements."'";
          $result = $db->sql_query($sql);
          $row = $db->sql_fetchrow($result);
          $save_pages = str_replace("  "," ", $row['pages']." ".$page_id." ");
          // теперь присвоем каждому из элементов списка id страницы, которую редактируем.
          $db->sql_query("UPDATE `".$prefix."_spiski` SET `pages` =  '".$save_pages."' WHERE `id` =".$elements." LIMIT 1 ;") or die('Ошибка при добавлении страницы в элемент списка');
        }
      break;
    }
  }
}
#####################################################################################################################
function base_pages_delit_page($name,$pid, $ok) {
    global $prefix, $db;
    $pid = intval($pid);
    $db->sql_query("DELETE FROM ".$prefix."_pages WHERE pid='$pid'");
    Header("Location: sys.php");
}
#####################################################################################################################
function base_delit_comm() {
  global $prefix, $db;
  $db->sql_query("DELETE FROM ".$prefix."_pages_comments WHERE cid>'0';") or die('Не удалось удалить комментарии');
  $db->sql_query("UPDATE ".$prefix."_pages SET comm='0' WHERE comm>'0';") or die('Не удалось удалить записи о комментариях в страницах');
  $db->sql_query("UPDATE ".$prefix."_pages SET counter='0' WHERE counter>'0';") or die('Не удалось удалить счетчики посещиний в страницах');
  $db->sql_query("UPDATE ".$prefix."_pages SET golos='0' WHERE golos>'0';") or die('Не удалось удалить записи о голосованиях в страницах');
  Header("Location: sys.php");
}
#####################################################################################################################
function base_delit_noactive_comm($del="noactive") {
  global $prefix, $db;
  if ($del == "noactive") $db->sql_query("DELETE FROM ".$prefix."_pages_comments WHERE cid>'0' and active!='1';") or die('Не удалось удалить отключенные комментарии');
  if ($del == "system") $db->sql_query("DELETE FROM ".$prefix."_pages_comments WHERE num='0' and avtor='ДвижОк' and mail='';") or die('Не удалось удалить системные комментарии');
  Header("Location: sys.php");
}
#####################################################################################################################
function base_pages_delit_comm($cid, $ok, $pid) {
	mt_srand((double)microtime()*1000000);
	$num1 = mt_rand(100, 900);
  $url = getenv("HTTP_REFERER"); // REQUEST_URI
    global $prefix, $db;
    $cid = intval($cid);
    $pid = intval($pid);
    if($ok=="ok") {
    $db->sql_query("DELETE FROM ".$prefix."_pages_comments WHERE `cid`='".$cid."'");
    $db->sql_query("UPDATE ".$prefix."_pages SET `comm` = `comm`-1 WHERE `pid` = '".$pid."' and `comm` > '0'");
    $sql = "select `module` from ".$prefix."_pages where `pid` = '".$pid."'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $mod = $row['module'];
    //if (function_exists('recash')) 
    recash("page_".$pid); // Обновление кеша ##
      $url = str_replace("#comm","",$url);
      Header("Location: ".$url);
    }
}
##################################################################################
function base_pages_re($link) {
    global $referer;
    recash($link);
    Header("Location: ".$referer);
}
###################################################################################
  switch ($op) {
    case "edit_base_pages_category":
      edit_base_pages_category($cid, $red);
      break;
    case "base_pages_save_category":
      if (file_exists($_SERVER["DOCUMENT_ROOT"].'/cashe/clean_url_categories'.$cid)) unlink ($_SERVER["DOCUMENT_ROOT"].'/cashe/clean_url_categories'.$cid);
      base_pages_save_category($cid, $module, $title, $desc, $sortirovka, $parent_id, $description2, $keywords2, $meta_title, $clean_url, $pic);
      break;
    case "base_pages_add_page":
      if (!isset($cid)) $cid = 0;
      if (!isset($name)) $name = "";
      if (!isset($red)) $red = "3";
      base_pages_add_page(0, $red, $name, $cid);
      break;
    case "base_pages_save_page":
      if (!isset($foto)) $foto = "";
      if (!isset($link_foto)) $link_foto = "";
      if (!isset($mainpage)) $mainpage = "";
      if (!isset($add)) $add = "";
      if (!isset($open_text_mysor)) $open_text_mysor = "";
      if (!isset($main_text_mysor)) $main_text_mysor = "";
      if (file_exists($_SERVER["DOCUMENT_ROOT"].'/cashe/clean_url_pages'.$pid)) unlink ($_SERVER["DOCUMENT_ROOT"].'/cashe/clean_url_pages'.$pid);
      base_pages_save_page($cid, $module, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $golos_reiting, $nocomm, $price, $add, $data1, $data2, $data3, $data4, $meta_title2, $clean_url2, $keywords2, $description2, $sor, $open_text_mysor, $main_text_mysor, $close_data);
      break;
    case "base_pages_edit_page":
      if (!isset($red)) $red = 0;
      base_pages_add_page($pid, $red);
      break;
    case "base_pages_edit_sv_page":
      if (!isset($link_foto)) $link_foto = "";
      if (!isset($mainpage)) $mainpage = "";
      if (!isset($add)) $add = "";
      if (!isset($nocomm)) $nocomm = "";
      if (!isset($open_text_mysor)) $open_text_mysor = "";
      if (!isset($main_text_mysor)) $main_text_mysor = "";
      if (file_exists($_SERVER["DOCUMENT_ROOT"].'/cashe/clean_url_pages'.$pid)) unlink ($_SERVER["DOCUMENT_ROOT"].'/cashe/clean_url_pages'.$pid);
          base_pages_edit_sv_page($pid, $module, $cid, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $golos_reiting, $nocomm, $price, $add, $data1, $data2, $data3, $data4, $meta_title2, $clean_url2, $keywords2, $description2, $com, $cop, $count, $sor, $open_text_mysor, $main_text_mysor, $close_data);
      break;
    case "base_pages_delit_page":
      if (!isset($ok)) $ok = 0; 
      else $ok = 1;
      base_pages_delit_page($name, $pid, $ok);
      break;
    case "base_pages_delit_comm":
      base_pages_delit_comm($cid, $ok, $pid);
      break;
    case "delete_noactive_comm":
      base_delit_noactive_comm("noactive");
      break;
    case "delete_system_comm":
      base_delit_noactive_comm("system");
      break;
    case "base_delit_comm":
      base_delit_comm();
      break;
    case "delete_all":
      delete_all($del);
      break;
    case "base_pages_re":
      base_pages_re($link);
      break;
    case "page_save_spiski":
      parse_str($_REQUEST['string']);
      save_spiski($add, $page_id);
      break;
  }
}
?>
