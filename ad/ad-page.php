<?php
  if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
  $aid = trim($aid);
  global $prefix, $db, $red;
  $sql = "select realadmin from ".$prefix."_authors where aid='$aid'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $realadmin = $row['realadmin'];
  if ($realadmin==1) {
  $tip = "pages";
  $admintip = "base_pages";

function seo($edit=false){
  global $geo, $kolkey;
  echo "<script type='text/javascript'>
  function seo(){
    var x=document.getElementById('open_text').value;
    var y=document.getElementById('main_text').value;
    var kolkey= $('select.kolkey').val();
    var kolslov = $('select.kolslov').val();
    var kolslov = $('select.kolslov').val();
    var wordstatv = $('select.wordstatv').val();
    var poisksin = $('select.poisksin').val();
    var geo=document.getElementById('geo').value;
    var koldes=250;
    var a = (x+y);
    if ( (x + y).length >= 400) {
      xps=new XMLHttpRequest();
      xps.onreadystatechange=function() {
        if (xps.readyState==4 && xps.status==200)
          var key = document.getElementById('keywords2').innerHTML = xps.responseText;
        if( key.length >= 3 )  {
          $('#key').show('slow'); $('#key_hide').hide();
      document.getElementById('procent').innerHTML = '<h2>Загружаю вычисления...</h2>';
          if (wordstatv == 1) { document.getElementById('wordstat').innerHTML = '<h2>Загружаю популярные подходящие словосочетания...</h2>';}
          zapros('metod=des&x='+a+'&key='+key+'&kol='+koldes,document.getElementById('description2'),'des');
          zapros('metod=procent&x='+a+'&key='+key+'&sin='+poisksin,document.getElementById('procent'),'proc');
  		if (wordstatv == 1) { zapros('metod=wordstat&x='+a+'&geo='+geo+'&key='+key,document.getElementById('wordstat'),'word'); }
        } 
      }
      xps.open('POST','includes/seo.php',true);
      xps.setRequestHeader('Content-type','application/x-www-form-urlencoded');
      xps.send('metod=newkey&x='+a+'&kol='+kolkey+'&kolslov='+kolslov);
    } else {
      document.getElementById('ajax').innerHTML='Текст меньше 400 символов.';
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
  </script><span id='ajax'></span><input type='button' value='Заполнить ключевые слова' onclick='seo()'> <a class=punkt onclick='$(\"#opt_seo\").toggle(\"slow\")'>Настройки</a><div id=opt_seo style='display:none'><br><b>Регион:</b> <input id='geo' value='".$geo."' size=5> <a href='http://search.yaca.yandex.ru/geo.c2n' target='_blank'>Найти регион</a><br><b>Ключевых слов всего:</b> <select class='kolkey'><option value='30'>30</option><option value='20'>20</option><option value='15'>15</option><option value='10' selected='selected'>10</option><option value='9'>9</option><option value='8'>8</option><option value='7'>7</option><option value='6'>6</option><option value='5'>5</option><option value='4'>4</option><option value='3'>3</option><option value='2'>2</option><option value='1'>1</option></select><br><b>Из них словосочетаний:</b> <select class='kolslov'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option><option value='10'>10</option></select><br><b>Искать синонимы:</b> <select class='poisksin'><option value='0' selected='selected'>НЕТ</option><option value='1'>ДА</option></select><br><b>Выдача вордстат:</b> <select class='wordstatv'><option value='0' selected='selected'>НЕТ</option><option value='1'>ДА</option></select></div><p id='procent'></p><p id='wordstat'></p>";
}

function edit_base_pages_category($cid, $red=0) {
  global $module, $name, $tip, $admintip, $prefix, $db, $title_razdel_and_bd; //, $toolbars;
  include("ad-header.php");
  $cid = intval($cid);
  $red = intval($red);
  $sql = "SELECT * FROM ".$prefix."_".$tip."_categories WHERE cid='$cid'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $name = $row['module'];
  $title = $row['title'];
  $module = $name;
  $desc = $row['description'];
  $sortirovka = $row['sort'];
  $parent_id = $row['parent_id'];

  echo "<form action='sys.php' method='post'>
  <div class='fon w100 mw800'>
  <div class='black_grad' style='height:45px;'>
  <button type=submit id=new_razdel_button class='medium green' onclick=\"show('sortirovka');\" style='float:left; margin:3px;'><span style='margin-right: -2px;' class='icon white small' data-icon='c'></span> Сохранить</button>
  <span class='h1' style='padding-top:10px;'>
    ".$title_razdel_and_bd[$module]." &rarr; Редактирование папки</span>";
  if (intval($nastroi) != 1) red_vybor();
  echo "</div>";

  # cid module title description pic sort counter parent_id
  echo "<table width=100%><tr valign=top><td bgcolor=#eeeeee>
    <h2>Раздел:</h2>";
               $sql = "select name, title, color from ".$prefix."_mainpage where type='2' and `tables`='pages' and name != 'index' order by color desc, title";
               $result = $db->sql_query($sql);
               $numrows = $db->sql_numrows($result);
               echo "<select name=module id=to_razdel style='font-size:11px; width:100%;' size=1 onChange=\"izmenapapka(document.getElementById('to_razdel').value, '', '','','editdir');\">";
               while ($row = $db->sql_fetchrow($result)) {
               $name2 = $row['name'];
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
        if ($numrows > 10) $size = 20*16; else $size=($numrows+5)*16;
        echo "</select><br>
        <div style='display:inline; float:right;'><div id=showa style='display:inline; float:right;'><a style='cursor:pointer;' onclick=\"show('hidea'); show('showa'); $('#to_papka').width(500); $('#to_papka').height(400);\">развернуть &rarr;</a></div><div id=hidea style='display:none;'><a style='cursor:pointer;' onclick=\"show('showa'); show('hidea'); $('#to_papka').width(248); $('#to_papka').height(".$size.");\">&larr; свернуть</a></div></div><h2>Папка:</h2>";
               $sql = "select * from ".$prefix."_".$tip."_categories where module='$name' and `tables`='pages' and cid != '$cid' order by parent_id,cid";
               $result = $db->sql_query($sql);
               $numrows = $db->sql_numrows($result);
               if ($numrows > 10) $size = 10*16; else $size=($numrows+2)*16;
               echo "<div id='izmenapapka'><select name=parent_id id='to_papka' size=5 style='font-size:11px; width:248px; height:".$size."px;'><option value=0 selected>Основная папка («корень»)</option>";
               while ($row = $db->sql_fetchrow($result)) {
               $cid2 = $row['cid'];
               $title3 = $row['title'];
               $parentid = $row['parent_id'];
    	   $title3 = getparent($name,$parentid,$title3);
               if ($parent_id == $cid2) $sel = "selected"; else $sel = "";
    	   echo "<option value=".$cid2." ".$sel.">".$title3."</option>";
               }
    echo "</select></div><br><br>";
    //html_spravka();
    $sql3 = "select `text` from `".$prefix."_mainpage` where `name`='$name' and `type`='2'";
    $result3 = $db->sql_query($sql3);
    $row3 = $db->sql_fetchrow($result3);
    if (trim($row3['text'])!="") {
    $main_file = explode("|",$row3['text']);
    $main_options = $main_file[1];
    parse_str($main_options);
    }
    if ($view == 4) $blok = "<b>Шаблон для анкет рейтинга (только для этой папки!)</b><br>
    Пример написания шаблона:<br>
    Ваше Имя *: |строка<br>
    Договаривались ли Вы заранее с врачом: |выбор|да|нет<br>
    Отзыв о Вашем враче: |текст<br>";
    else $blok = "<h2>Содержание папки (текст над списком страниц папки):</h2>";

    echo "</select>
    </td><td>
    <h2>Название папки:</h2>
    <input type='text' name='title' value='$title' size='60'><br><br>
    ".$blok."";
    redactor($red, $desc, 'desc'); // редактор: типа редактора, редактируемое поле

    echo "<br><div style='float:left; '><h2>Сортировка:&nbsp;</h2></div> <input type='text' name='sortirovka' value='$sortirovka' size='3'><br><br><span class=small>Если вы решили отсортировать папки по-своему - лучше всего использовать десятичную разницу между числами сортировки для разных папок, например: 10, 20, 30, 40... Это нужно для того, чтобы в случае создания новой папки вы не изменяли сортировку для всех предыдущих, а легко присвоили ей следующий номер за сортировкой, стоящей перед ней папки, например: 11, 21, 31, 41... или 15, 25, 35 - чтобы можно было вклинить новые папки между ними.</span>
      <input type='hidden' name='cid' value='$cid'>
      <input type='hidden' name='op' value='".$admintip."_save_category'>
      </table></div></form>";
  admin_footer();
}

function base_pages_save_category($cid, $module, $title, $desc, $sortirovka, $parent_id) {
  global $tip, $admintip, $prefix, $db;
  $title = mysql_real_escape_string($title);
  $desc = mysql_real_escape_string($desc);
  $db->sql_query("UPDATE ".$prefix."_".$tip."_categories SET module='$module', title='$title', description='$desc', sort='$sortirovka', parent_id='$parent_id', `tables`='pages' WHERE cid='$cid'");
  Header("Location: sys.php");
}

function delete_razdel_base_pages($name) { 
  global $name, $tip, $admintip, $prefix, $db; 
  $db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE `tables`='del' and name='$name'"); 
  $db->sql_query("DELETE FROM ".$prefix."_".$tip."_categories WHERE module='$name' and `tables`='del'"); 
  $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE module='$name' and `tables`='del'"); 
  Header("Location: sys.php");
}

function delete_page_base_pages($pid) { 
  global $name, $tip, $admintip, $prefix, $db; 
  $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE `tables`='pages' and pid='$pid'"); 
  Header("Location: sys.php");
}

function delete_all($del="del") {
  global $tip, $prefix, $db; 
  if ($del == "design") {
    $db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE `tables`='del' and `type`!='2'") or die('Error: 0'); 
    Header("Location: sys.php?op=mainpage&type=element");
  } else {
    if ($del != "backup") $del = "del"; // в дальнейшем можно расширить
    $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE `tables`='".$del."'") or die('Error: 1');
    $db->sql_query("DELETE FROM ".$prefix."_".$tip."_categories WHERE `tables`='".$del."'") or die('Error: 2');
    $db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE `tables`='".$del."' and `type`='2'") or die('Error: 3');
    Header("Location: sys.php");
  }
}

# СТРАНИЦЫ =================
function base_pages_add_page($page_id=0, $red=0, $name=0, $razdel=0, $new=0, $pid=0) {
  global $tip, $module, $admintip, $prefix, $db, $red, $new, $pid, $redaktor, $toolbars, $geo, $kolkey, $title_razdel_and_bd, $siteurl;
  include("ad-header.php");
  $id = intval ($id);
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
    $search = $row['search'];
    $data = $row['date'];
    $counter = $row['counter'];
    $active = $row['active'];
    $comm = $row['comm'];
    $mainpage = $row['mainpage'];
    $rss = $row['rss'];
    $nocomm = $row['nocomm'];
    $price = $row['price'];
    $description = $row['description'];
    $keywords = $row['keywords'];
    $copy = $row['copy'];
    $sor = intval ($row['sort']); 

    // узнаем номер последней резервной копии
    $new_pid = 0;
    $sql = "SELECT `pid` from ".$prefix."_pages where copy='".$pid."' order by redate desc limit 1"; // список всех категорий
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $new_pid = $row['pid'];
    if ( $new > 0 ) echo "<div class='notice success mw700'><a target='_blank' class='green' href=/-".$module."_page_".$pid.">Страница</a> отредактирована. "; 
    else echo "<div class='notice warning mw700'>Открыть страницу <a target='_blank' class='green' href=/-".$module."_page_".$pid.">на сайте</a>. ";
    if ( $new_pid != 0 ) echo "Есть предыдущая версия: <button title='Заменить этой копией оригинал...' onclick='resetpage(".$new_pid."); setTimeout(\"location.reload()\", 2000);' class='small'><img class='icon2 i24' src='/images/1.gif'>Заменить на последнюю резервную копию</button>";
    else echo "Предыдущей версии нет.";
    echo "</div>";

    // Подстройка редактирования
    $main_title = $title_razdel_and_bd[$module]." &rarr; Редактирование страницы";
    $name = $module;
    if ($active!=1) $check= " unchecked"; else $check= " checked";
    if ($nocomm==1) $check2= " checked"; else $check2= " unchecked";
    if ($rss==1) $check3= " checked"; else $check3= " unchecked";
    if ($mainpage==1) $check4= " checked"; else $check4= " unchecked";
    $data = explode(" ",$data);
    $data1 = date2normal_view($data[0]);
    $data = explode(":",$data[1]);
    $data2 = $data[0];
    $data3 = $data[1];
    $data4 = $data[2];
    $data3_2 = date("i", time());
    $data3_2 = "<option value=".$data3_2."> ".$data3_2."!</option>";
    $saveme = "_edit_sv_page";

  } else { // Если это создание новой страницы
    if ( $pid > 0 ) { // Если только что добавили страницу
      // узнаем имя страницы
      $sql = "SELECT `title` from ".$prefix."_pages where pid='".$pid."'"; // список всех категорий
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $new_title = $row['title'];
      echo "<div class='notice success mw700'>Страница «<a target='_blank' class='green' href=/-".$name."_page_".$pid.">".$new_title."</a>» добавлена. <a href=/sys.php?op=base_pages_edit_page&name=".$name."&pid=".$pid."><img class='icon2 i35' src='/images/1.gif'>Редактировать</a>. <b>Добавим еще одну страницу?</b></div>";
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
      if ($red != 3 and $red != 4) { $shablon1 = "<p>"; $shablon2 = "<p>"; }
    } else { 
      $shablon = explode("[следующий]",$shablon);
      $shablon1 = $shablon[0];
      $shablon2 = $shablon[1];
    }
    if ($shablon2=="") $shablon2 = "<p>&nbsp;</p>";
    if (!isset($shablon1)) $shablon1="";
    if (!isset($shablon2)) $shablon2="";

    // Подстройка создания
    $main_title = "Добавление страницы";
    $description = $keywords = $search = "";
    $check= " checked"; // active
    $check2= " unchecked"; // nocomm
    $check3= " checked"; // rss
    $check4= " unchecked"; // mainpage
    $sor = "0";
    $data1 = date2normal_view(date("Y-m-d", time()));
    $data2 = date("H", time());
    $data3 = date("i", time());
    $data4 = date("s", time());
    $data3_2 = "";
    $titl = "";
    $saveme = "_save_page";
  }

  echo "<form action='sys.php' method='post' enctype='multipart/form-data'>
  <div class='fon w100 mw800'><div class='black_grad h40'>
  <button type=submit onClick=\" if (document.getElementById('to_razdel').value=='') { alert('Выберите раздел для страницы (слева сверху)'); return false; } else { submit(); } \" class='medium green left3'><span class='icon white small mr-2' data-icon='c'></span> Сохранить</button>
  <span class='h1 pt10'>".$main_title."</span>";
  if ($nastroi != 1) red_vybor();
  echo "</div>";

  echo "<table width=100%><tr valign=top><td width=250 id='razdels' style='background:#e7e9ec;'>";
  $sql = "select `name`, `title`, `color` from ".$prefix."_mainpage where `type`='2' and `tables`='pages' and `name` != 'index' order by `color` desc, `title`";
  $result = $db->sql_query($sql);

  echo "<label class='darkgreen b'><input type=checkbox name=active value=1".$check."> Включить страницу</label> <a onclick=\"show('help3')\" class=help>?</a><br><div id='help3' style='display:none;'><br>Если поставить эту галочку — ссылка на эту страницу будет видна в автоматическом списке страниц данного раздела, а также в блоках, которые выводят страницы данного раздела (если они созданы). Если галочку убрать — на эту страницу все равно можно поставить ссылку из любого места на сайте или с другого сайта и страница будет видна тем, кто перейдет по этой вручную созданной ссылке. Если вы хотите, чтобы в общем списке страниц данная страница не отображалась, а раскрывала более подробную информацию при переходе с другой страницы — отключите ее и сделайте на нее ссылку вручную.<br></div><br>

  <span class='h2 darkgreen'>Раздел:</span><br>
  <select name=module id=to_razdel class='f12 w100 mb20' size=10 onChange=\"izmenapapka(document.getElementById('to_razdel').value,'','','','addpage');\">";
    $colors = array("ffffff","b4f3b4","f3f3a3","ffa4ac","b8f4f2");
    while ($row = $db->sql_fetchrow($result)) {
      $name2 = $row['name'];
      $title2 = $row['title'];
      $color = $row['color'];
      $color = $colors[$color];
      if ($name == $name2) $sel = "selected"; else $sel = "";
      echo "<option style='background:".$color.";' value='".$name2."' ".$sel.">".$title2."</option>";
    }

    $sql = "select * from ".$prefix."_".$tip."_categories where `module`='".$name."' and `tables`='pages' order by `parent_id`, `title`";
    $result = $db->sql_query($sql);
    $numrows = $db->sql_numrows($result);
    if ($numrows > 10) $size = 10*16; else $size = ($numrows+2)*16;
    echo "</select>";

    if ($numrows > 0) {
      echo "<div class='in_r'><div id=showa class='in_r'><a style='cursor:pointer;' onclick=\"show('hidea'); show('showa'); $('#to_papka').width(500); $('#to_papka').height(400);\">развернуть &rarr;</a></div><div id=hidea style='display:none;'><a style='cursor:pointer;' onclick=\"show('showa'); show('hidea'); $('#to_papka').width(300); $('#to_papka').height(155);\">&larr; свернуть</a></div></div><span class=h2>Папка:</span><br><div id='izmenapapka'><select name=cid id='to_papka' size=4 class='f12 mb20 w100 h155'><option value=0 selected>Основная папка («корень»)</option>";
      while ($row = $db->sql_fetchrow($result)) {
        $cid2 = $row['cid'];
        $title = $row['title'];
        $parentid = $row['parent_id'];
        $sel = "";
        if (isset($cid)) if ($cid == $cid2) $sel = "selected";
        if ($parentid == 0) {
          // занести в переменную
          $first_opt[$cid2] = "<option value=".$cid2." ".$sel." style='background:#fdf;'>".$title."</option>"; 
        } else {
          $title = "&bull; ".getparent($name,$parentid,$title);
          // вывести и очистить переменную
          echo $first_opt[$parentid];
          $first_opt[$parentid] = "";
          echo "<option value='".$cid2."' ".$sel.">".$title."</option>";
        }
      }
      if (isset($first_opt)) if (count($first_opt) > 0) 
        foreach( $first_opt as $key => $value ) {
          if ($first_opt[$key] != "") echo $first_opt[$key];
        }
    echo "</select></div>";
    } else echo "<input type=hidden name=cid value='0'><i>В разделе нет папок.</i><br><br>";    
  
  echo "<div id='mainrazdel' class='dark_pole2'><a class='base_page' onclick=\"if ( $('#dop').is(':hidden') ) $('#mainrazdel').attr('class', 'dark_pole2sel'); else $('#mainrazdel').attr('class', 'dark_pole2'); $('#main').toggle(); $('#dop').toggle('slow'); \"><div id='mainrazdel'><div style=\"float:right\"><span class=\"f16 gray\">⟩</span></div><span class='icon gray large in_b' data-icon='z'><span aria-hidden='true'>z</span></span><span class='plus20'>Дополнительные настройки</span></div></a></div> ";

  echo "</td><td style='padding:0;'><a class='punkt' title='Свернуть/развернуть левую колонку' onclick='$(\"#razdels\").toggle(\"slow\");'><div class='polosa_razdelitel'>||</div></a></td><td>";

  echo "<div style='display:none' id='dop'><a title='Закрыть' class='punkt' onclick=\"if ( $('#dop').is(':hidden') ) $('#mainrazdel').attr('class', 'dark_pole2sel'); else $('#mainrazdel').attr('class', 'dark_pole2'); $('#main').toggle(); $('#dop').toggle('slow'); \"><div class='radius' style='font-size:12pt; width:20px; height: 20px; color: white; text-align:center; float:right; margin:5px; background: #bbbbbb;'>&nbsp;x&nbsp;</div></a>
  
  <h3>Ключевые слова для поисковых систем: <a onclick=\"show('help10')\" class='help'>?</a></h3><textarea id=keywords2 name=keywords2 class='big w100' rows=2 cols=10>".$keywords."</textarea>
  <br><div id='help10' style='display:none;'><span class=small>Максимум 1000 символов. Разделять словосочетания желательно запятой. Если пусто - используются <b>Теги</b> (если и они пустые - используются Ключевые словосочетания из <a href=/sys.php?op=Configure target=_blank>Настроек портала</a>).</span><br></div>

  <h3>Описание для поисковых систем: <a onclick=\"show('help11')\" class='help'>?</a></h3><textarea id=description2 name=description2 class='big w100' rows=4 cols=10>".$description."</textarea>
  <br><div id='help11' style='display:none;'><span class=small>Максимум 200 символов. Если пусто - используется <b>Название</b> страницы.</span><br></div>";

  if ($page_id > 0) {
    echo "<input type=hidden name=pid value='".$pid."'>";
    seo(true);
  } else seo();

  echo "<h3>Тэги (слова для похожих по тематике страниц): <a onclick=\"show('help12')\" class='help'>?</a></h3> <textarea name=search class='big w100' rows=1 cols=10>".$search."</textarea>
  <br><div id='help12' style='display:none;'><span class=small>Разделять пробелами, а слова в словосочетаниях символом + <br>
  Писать только существительные! НИКАКИХ ПРЕДЛОГОВ! Максимум неограничен. Разделять слова необходимо пробелом. Разделять слова в словосочетаниях символом +, например: игра+разума игротека game. Писать желательно в единственном числе и именительном падеже. Можно создать Блок «Облако тегов». Теги также могут выводиться на страницах (в настройках Раздела).</span><br></div>

  <table class='w100 f12'><tr><td>
  <label><input type=checkbox name=mainpage value=1".$check4."> На главную страницу</label> <a onclick=\"show('help_mainpage')\" class=help>?</a>
  </td><td>
  <label><input type=checkbox name=rss value=1".$check3."> Добавить в RSS</label> <a onclick=\"show('help_rss')\" class=help>?</a>
  </td><td>
  <label><input type=checkbox name=nocomm value=1".$check2."> Запретить комментарии</label> <a onclick=\"show('help_nocomm')\" class=help>?</a>
  </td><td>
  Очередность: <input type=text name=sor value='".$sor."' size=4 style='text-align:center;'> <a onclick=\"show('help_sor')\" class=help>?</a>
  </td></tr></table>

  <div id='help_nocomm' style='display:none;'>Если в данном разделе разрешены комментарии — вы можете отключить их выборочно на данной странице, поставив галочку.<br><br></div>
  <div id='help_rss' style='display:none;'>Технология RSS похожа на e-mail подписку на новости — в RSS-программу, сайт RSS-читалки или встроенную систему чтения RSS в браузере добавляется ссылка на данный сайт, после чего название и предисловие всех новых страниц, отмеченных данной галочкой, будут видны подписавшемуся человеку и он сможет быстро ознакомиться с их заголовками, не заходя на сайт. Если что-то ему понравится — он откроет сайт и прочитает подробности. RSS используется для постепенного увеличения количества посетителей сайта путем их возвращения на сайт за интересной информацией. <a href=http://yandex.ru/yandsearch?text=Что+такое+RSS%3F target=_blank>Подробнее о RSS?</a><br><br></div>
  <div id='help_mainpage' style='display:none;'>Если отметить эту галочку, данная страница будет отображаться в блоке, который настроен на отображение только помеченных этой галочкой страниц, или не будет отображаться в блоке, который настроен на показ всех неотмеченных галочкой страниц.<br><br></div>
  <div id='help_sor' style='display:none;'>Настраивается в настройках раздела. Может быть равна цифре. Применяется для ручной сортировки страниц. Лучше всего делать кратной 10, например 20, 30, 40 и т.д. для того, чтобы было удобно вставлять страницы между двумя другими. Если очередность у двух страниц совпадает, сортировка происходит по дате.<br><br></div>

  <span class=h2>Дата создания:</span> <script>$(function() { $.datepicker.setDefaults( $.datepicker.regional[ \"ru\" ] ); $( \"#f_date_c999\" ).datepicker({ changeMonth: true, changeYear: true, dateFormat: \"d MM yy\", showAnim: 'slide' }); });</script>
  <INPUT type=text name=data1 id=\"f_date_c999\" value=\"".$data1."\" onchange=\"document.getElementById('add999').value=document.getElementById('f_date_c999').value+'|'+document.getElementById('f_date_c2999').value\" readonly=1 size=18> <a onclick=\"show('help0')\" class=help>?</a> <nobr>Время: <select name=data2 class='f12'>";
  for ($x=0; $x < 24; $x++) {
    if ($x<10) $xx = "0".$x; else $xx = $x;
    $sel = ""; if ($xx == $data2) $sel = " selected";
    echo "<option value=".$xx.$sel."> ".$xx." </option>";
  }
  echo "</select>ч <select name=data3 class='f12'><option value=".$data3.$sel."> ".$data3." </option>".$data3_2."<option value='00'> 00 </option><option value='10'> 10 </option><option value='15'> 15 </option><option value='20'> 20 </option><option value='30'> 30 </option><option value='40'> 40 </option><option value='45'> 45 </option><option value='50'> 50 </option><option value='55'> 55 </option></select>м <input type=text name=data4 value='".$data4."' class='f12' size=3 onclick=\"this.value='00'\">с</nobr><div id='help0' style='display:none;'><br>Для выбора даты из календаря нажмите по дате. Для обнуления секунд кликните по ним. Минуты представлены текущим вариантом или выбором из основного интервала для ускорения работы.<br></div><br><br>";

  if ($page_id > 0) echo "<a onclick=\"show('slugebka')\" class=punkt>Скрытая информация</a><br><div id='slugebka' style='display:none;'><div class=radius><span class=small>Лучше не менять.</span><br><h3 style='display:inline'>Копия:</h3><INPUT type=text name=cop value='".$copy."' size=3><a onclick=\"show('help18')\" class=help>?</a><br><div id='help18' style='display:none;'>У страниц-копий указывается один и тот же номер — номер оригинальной страницы. Если это не копия, а единственный оригинал, цифра равна 0.<br></div><br><h3 style='display:inline'>Кол-во комментариев:</h3><INPUT type=text name=com value='".$comm."' size=3><br><br><h3 style='display:inline'>Кол-во посещений:</h3><INPUT type=text name=count value='".$counter."' size=3></div></div><br>";

  echo "</div>

  <div id='main'>
  <span class=h2>Название страницы (заголовок)</span><br>
  <textarea class='f16 w100 h40' name=title rows=1 cols=10>".$titl."</textarea>
  <br><span class=h2>Предисловие (начальный текст)</span><br>";
  redactor($red, $shablon1, 'open_text', 'main_text'); // редактор: тип редактора, редактируемое поле
  echo "<br><span class=h2>Содержание (основной текст)</span><br>";
  redactor2($red, $shablon2, 'main_text');

  echo "<br><input type=hidden name=foto value=''>";
  echo "<input type=hidden name=price value=''>";

  $sql = "select text from ".$prefix."_mainpage where name='$name' and type='2'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $tex = $row['text'];

  /*
  // это галерея?
  if (strpos($tex,"view=5")) echo "<b>Фото (для фотогалереи):</b> <input type=file name=foto size=40><br>
  <b>или ссылка:</b> <input type=text name=link_foto value='/img/' size=40><br>Ссылку на другие сайты начинать с http://<br>";
  else echo "<input type=hidden name=foto value=''>";

  // это магазин?
  if (strpos($tex,"view=3")) echo "<b>Стоимость:</b> <input type=text name=price size=3 value='0'> руб.<br>";
  else echo "<input type=hidden name=price value=''>";
  ======================================================
  // это галерея?
  $sql = "select text from ".$prefix."_mainpage where name='".$module."' and type='2'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $tex = $row['text'];
  if (strpos($tex,"view=5")) echo "<p><b>Фото (для фотогалереи):</b> <input type=file name=foto size=40> 
  <b>или ссылка:</b> <input type=text name=link_foto value='".$foto."' size=40></p>";
  else echo "<input type=hidden name=foto value='".$foto."'>";

  // это магазин?
  if (strpos($tex,"view=3")) echo "<p><b>Стоимость:</b> <input type=text name=price size=3 value='".$price."'> руб.</p>";
  else echo "<input type=hidden name=price value='".$price."'>";
  */

  // Подсоединие списков ////////////////////////////////
  if ($page_id > 0) {
    if ($copy != 0) $page_id = $copy;
    // определяем id раздела
    global $id_razdel_and_bd;
    $id = $id_razdel_and_bd[$module];
    //$sql2 = "select id from ".$prefix."_mainpage where name='".$module."' and `tables`='pages' and type='2'";
    //$result2 = $db->sql_query($sql2);
    //$row2 = $db->sql_fetchrow($result2);
    //$id = $row2['id'];
  }
  // Ищем все списки по разделу
  $sql = "select id, title, name, text from ".$prefix."_mainpage where (useit='".$id."' or useit='0') and type='4' order by title";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $s_id = $row['id'];
    $s_title = $row['title'];
    $s_name = $row['name'];
    $options = explode("|", $row['text']);
    $options = $options[1];
    $type=0;
    $shablon=""; 
    parse_str($options); // раскладка всех настроек списка
    switch($type) {
      ///////////////////
      case "0": // список слов
        if ($page_id > 0) $shablon = spisok_name($s_name,$page_id,1);
        echo "<br><br><b>".$s_title.":</b><br>";
        $sql2 = "select * from ".$prefix."_spiski where type='".$s_name."' order by parent,id";
        $result2 = $db->sql_query($sql2);
        echo "<select size=10 class='f12' multiple=multiple name='add[".$s_name."][]'><option value=0>ничего не выбрано</option>";
        while ($row2 = $db->sql_fetchrow($result2)) {
          $s_id2 = $row2['id'];
          $s_title2 = $row2['name'];
          $s_opis = $row2['opis'];
          $s_parent = $row2['parent'];
          $s_title2 = getparent_spiski($s_name,$s_parent,$s_title2);
          $sel = ""; 
          if ($page_id > 0) 
            if (in_array($s_title2,$sp_names)) $sel = " selected";
          else 
            if ($razdel == $s_id2) $sel = " selected";
          if ($s_opis != "") $s_opis = " (".$s_opis.")";
          echo "<option value=".$s_id2.$sel."> ".$s_title2.$s_opis."</option>";
        }
        echo "</select>";
      break;
      ///////////////////
      case "1": // текст
        if ($page_id > 0) $sp_names = spisok_name($s_name,$page_id);
        echo "<br><br><b>".$s_title.":</b><br><textarea name='add[".$s_name."]' rows='4' cols='60' class='w100'>".$shablon."</textarea>";
      break;
      ///////////////////
      case "2": // файл (НЕ_ГОТОВО!!!)
        if ($page_id > 0) $shablon = spisok_name($s_name,$page_id);
        // Пример настройки: file=pic&papka=/img=verh&resizepic=x&file=&picsize=600&minipic=1&resizeminipic=x&minipicsize=100
        switch($fil) {
          case "pic": $type_fil = "картинка"; break;
          case "doc": $type_fil = "документ/архив"; break;
          case "flash": $type_fil = "flash-анимация"; break;
          case "avi": $type_fil = "видео-ролик"; break;
        }
        $type_mini="";
        if ($minipic==1) $type_mini = "Также будет создана миниатюра.";

        echo "<br><br><b>$s_title:</b><br><input type=file name='add[$s_name]' size=30> 
        <b>или ссылка:</b> <input type=text name='add[$s_name]_link' value='$papka' size=30><br>
        Файл ($type_fil) сохранится в $papka, на странице будет $type_mesto. $type_mini";
      break;
      ///////////////////
      case "3": // период времени
        if ($page_id > 0) {
          $date1 = date2normal_view( spisok_name($s_name,$page_id," order by name") );
          $date2 = date2normal_view( spisok_name($s_name,$page_id," order by name desc") );
          $data3 = $date1."|".$date2;
        } else {
          $date1 = $date2 = "";
          $data3 = "дата";
        }
        echo "<br><br><b>".$s_title.":</b> (выберите даты из меню, кликнув по значкам)<br>
        <TABLE cellspacing=0 cellpadding=0 style='border-collapse: collapse'><TBODY><TR> 
        <TD><INPUT type=text name='text[".$s_name."]' id='f_date_c[".$s_name."]' value='".$date1."' onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD>
        <TD><IMG src=/images/calendar.gif id='f_trigger_c[".$s_name."]' title='Выбор даты'></TD>
        <TD width=20 align=center> - </TD>
        <TD><INPUT type=text name='text[".$s_name."]' id='f_date_c2[".$s_name."]' value='".$date2."' onchange=\"document.getElementById('add[".$s_name."]').value=document.getElementById('f_date_c[".$s_name."]').value+'|'+document.getElementById('f_date_c2[".$s_name."]').value\" readonly=1 size=15></TD> 
        <TD><IMG src=/images/calendar.gif id='f_trigger_c2[".$s_name."]' title='Выбор даты'></TD>
        </TR></TBODY></TABLE>
        <SCRIPT type='text/javascript'> 
            Calendar.setup({
                inputField     :    \"f_date_c[".$s_name."]\",     // id of the input field
                ifFormat       :    \"%e %B %Y\",      // format of the input field
                button         :    \"f_trigger_c[".$s_name."]\",  // trigger for the calendar (button ID)
                align          :    \"Tl\",           // alignment (defaults to \"Bl\")
                singleClick    :    true
            });
        </SCRIPT>
        <SCRIPT type='text/javascript'> 
            Calendar.setup({
                inputField     :    \"f_date_c2[".$s_name."]\",     // id of the input field
                ifFormat       :    \"%e %B %Y\",      // format of the input field
                button         :    \"f_trigger_c2[".$s_name."]\",  // trigger for the calendar (button ID)
                align          :    \"Tl\",           // alignment (defaults to \"Bl\")
                singleClick    :    true
            });
        </SCRIPT>
        <input type=hidden name='add[".$s_name."]' id='add[".$s_name."]' value='".$date3."'>"; //
      break;
      ///////////////////
      case "4": // строка
        if ($page_id > 0) $shablon = spisok_name($s_name,$page_id);
        echo "<br><br><b>".$s_title.":</b><br><INPUT name='add[".$s_name."]' type=text value='".$shablon."' class='w100'>";
      break;
      ///////////////////
      case "5": // число
        if ($page_id > 0) $shablon = spisok_name($s_name,$page_id);
        echo "<br><br><b>".$s_title.":</b><br><INPUT name='add[".$s_name."]' type=number value='".$shablon."' class='w45'>";
      break;
      ///////////////////
      case "6": // регион
        if ($page_id > 0) {
          $sp_name = spisok_name($s_name,$page_id);
          $namereg = $db->sql_fetchrow($db->sql_query("SELECT id FROM ".$prefix."_regions WHERE name='".$sp_name."'"));
          $namereg = $namereg['id'];
        } else {
          $namereg = "";
          $sp_name = "Выберите область...";
        }
        echo "<br><br><b>".$s_title.":</b><br><script type='text/javascript' src='includes/regions/jquery.livequery.js'></script>
        <script type='text/javascript'>
        $(document).ready(function() {
            //$('#loader').hide();
            $('.parent').livequery('change', function() {
              $(this).nextAll('.parent').remove();
              $(this).nextAll('label').remove();
              $('#show_sub_categories').append('<img src=\"includes/regions/loader.gif\" class=\"left3\" id=\"loader\" />');
              $.post(\"get_chid_categories.php\", {
                parent_id: $(this).val(),
              }, function(response){
                setTimeout(\"finishAjax('show_sub_categories', '\"+escape(response)+\"')\", 400);
              });
              return false;
            });
          });
          function finishAjax(id, response){
            $('#loader').remove();
            $('#'+id).append(unescape(response));
          }</script><br clear='all' /><br clear='all' />
          <div id='show_sub_categories'>
          <select name='add[".$s_name."]' class='parent'>
          <option value='".$namereg."' selected='selected'>".$sp_name."</option>";
        include("includes/regions/list.html");
        echo '</select></div><br clear="all" /><br clear="all" />';
      break;
    }
  }
  echo "<input type=hidden name=op value='".$admintip.$saveme."'>";
  echo "</div></td></tr></table></div></form>";
  admin_footer();
}

function spisok_name($s_name,$page_id,$arr=0,$add="") { // Получаем значение поля
  // Если arr=1 - передаем массив
  // add - условия сортировки sql-запроса
  global $db, $prefix;
  $sql = "SELECT name FROM ".$prefix."_spiski WHERE type='".$s_name."' and pages like '% ".$page_id." %'".$add;
  $result = $db->sql_query($sql);
  if ($arr==0) {
    $row = $db->sql_fetchrow($result);
    return $row['name'];
  } else {
    $s_names = array();
    while ($row = $db->sql_fetchrow($result)) {
      $s_names[] = $row['name'];
    }
    return $s_names;
  }    
}
####################################################################################
function base_pages_save_page($cid, $module, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $nocomm, $price, $add, $data1, $data2, $data3, $data4, $keywords2, $description2, $sor, $open_text_mysor, $main_text_mysor) {
  global $red, $tip, $admintip, $prefix, $db, $admin_file, $now;
  /*
  // это галерея?
  $sql = "select text from ".$prefix."_mainpage where name='".$module."' and type='2'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $tex = $row['text'];
  if (strpos($tex,"media=1")) {
  $ImgDir="img";
  if (trim($link_foto)=="/img/" or trim($link_foto)=="") {
  // Обработка имени файла: транслит и удаление пробелов
  $pic_name2 = date("Y-m-d_H-i-s_", time()).str_replace(" ","",translit($_FILES["foto"]["name"]));
  	if (Copy($_FILES["foto"]["tmp_name"], $ImgDir."/".basename($pic_name2))) {
  	unlink($_FILES["foto"]["tmp_name"]);
  	chmod($ImgDir."/".basename($pic_name2),0644);
  	$foto="/".$ImgDir."/".basename($pic_name2);
  	} else echo "ОШИБКА при копировании файла";
  } else $foto=trim($link_foto);
  } else 
  */
  $foto="";
  ##----------------------------------------------------##
  // это магазин?
    $price=intval($price);
  ##----------------------------------------------------##
      $search = str_replace(", "," ",$search);
      $search = str_replace(","," ",$search);
      $search = str_replace(". "," ",$search);
      $search = str_replace("."," ",$search);
      $search = str_replace("  "," ",$search);
      $search = " ".trim($search)." "; //strtolow(
  # pid module cid title open_text main_text date counter active golos comm foto search mainpage
  if ($open_text == " <br><br>") $open_text = "";
  if ($main_text == " <br><br>") $main_text = "";

  // mysql_escape_string
  $sor = intval($sor);
  $rss = intval($rss);
  $nocomm = intval($nocomm);

  $open_text = mysql_real_escape_string(form($module, $open_text, "open"));
  $main_text = mysql_real_escape_string(form($module, $main_text, "main"));
  $title = mysql_real_escape_string(form($module, trim($title), "title"));

  $keywords2 = trim(str_replace("  "," ",str_replace("   "," ",str_replace(" ,",", ",$keywords2))));
  $description2 = trim($description2);

  $data = date2normal_view($data1, 1)." $data2:$data3:$data4";
  $data2 = $now;
  $sql = "INSERT INTO ".$prefix."_".$tip." VALUES (NULL, '".$module."', '".$cid."', '".$title."', '".$open_text."', '".$main_text."', '".$data."', '".$data2."', '0', '".$active."', '0', '0', '".$foto."', '".$search."', '".$mainpage."', '".$rss."', '".$price."', '".$description2."', '".$keywords2."', 'pages', '0','".$sor."', '".$nocomm."');";
  $db->sql_query($sql) or die ("Не удалось сохранить страницу. Попробуйте нажать в Редакторе на кнопку Чистка HTML в Редакторе. Если всё равно появится эта ошибка - сообщите разработчику нижеследующее:".$sql);
  // Узнаем получившийся номер страницы ID
  $sql = "select pid from ".$prefix."_".$tip." where title='".$title."' and date='".$data."'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $page_id = $row['pid'];
  /////////////////////////////////////////////////////////////////////////
  // РАБОТА СО СПИСКАМИ
  if (!isset($add) or $add == "") $add = array();
  foreach ($add as $name => $elements) {
    // Получение информации о каждом списке
    if ($name != "") {
    $sql = "select * from ".$prefix."_mainpage where name='".$name."' and type='4'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $s_id = $row['id'];
    $options = explode("|", $row['text']); $options = $options[1];
    $type=0; $shablon=""; 
    parse_str($options); // раскладка всех настроек списка
  switch($type) {
  ////////////////////////////////////////////////////////////////////////////
  case "5": // число
  if( preg_match("/[^(0-9)|(\.)]/", $elements)) die ('Ошибка: Не удалось сохранить список. 5 - Возможен ввод только числа с плавающей точкой'); 
  else  $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 5'); 
  break;
  ////////////////////////////////////////////////////////////////////////////
  case "6": // регион
  $id_regions = $_POST['id_regions'];
  if (!isset($id_regions)) $id_regions = $elements;
  $region = $db->sql_fetchrow($db->sql_query("select name from ".$prefix."_regions where id='".$id_regions."'"));
  $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$region['name']."', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 6'); 
  break;
  ////////////////////////////////////////////////////////////////////////////
  case "4": // строка
          // Проверяем наличие подобного текста
          /*
          $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='$name' and name='$elements'";
          $result = $db->sql_query($sql);
          $numrows = $db->sql_numrows($result);
          if ($numrows == 1) { // если элемент найден
              $row = $db->sql_fetchrow($result);
              $s_pages = $row['pages'];
              $s_name = $row['name'];
                  if (strpos($agent," $page_id ") < 1 and $s_name == $elements) {
                      $s_pages .= " $page_id ";
                      $s_pages = str_replace("  "," ",$s_pages);
                      $db->sql_query("UPDATE ".$prefix."_spiski SET pages='$s_pages' WHERE type='$name' and name='$elements'") or die ('Ошибка: Не удалось обновить список. 1');
                      echo "up";
                  } else {
                      $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 2');
                      echo "in1";
                  }
          } else { // если элемент новый
          */ 
          // (id, type, name, opis, sort, pages, parent) 
              $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 3');
              //echo "in2";
          //}
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
  case "1": // текст
          // Проверяем наличие подобного текста
          /*
          $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='$name' and name='$elements'";
          $result = $db->sql_query($sql);
          $numrows = $db->sql_numrows($result);
          if ($numrows > 0) { // если элемент найден
              $row = $db->sql_fetchrow($result);
              $s_pages = $row['pages'];
              $s_name = $row['name'];
                  if (strpos($agent," $page_id ") < 1 and $s_name == $elements) {
                      $s_pages .= " $page_id ";
                      $s_pages = str_replace("  "," ",$s_pages);
                      $db->sql_query("UPDATE ".$prefix."_spiski SET pages='$s_pages' WHERE type='$name' and name='$elements'") or die ('Ошибка: Не удалось обновить список. 6');
                      echo "up";
                  } else {
                      $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 7');
                      echo "in1";
                  }
          } else { // если элемент новый
          */
          //
              $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 8');
              //echo "in2";
          //}
  break;
  ////////////////////////////////////////////////////////////////////////////
  case "0": // список
          // Проверяем сколько элементов в списке
          $num = count($elements);
          for ($x=0; $x < $num; $x++) { // посчитали сколько номеров списка
              if ($elements[$x] != 0) {
              // узнаем какие страницы уже есть у этого номера из списка
              $sql = "SELECT pages FROM ".$prefix."_spiski WHERE id='".$elements[$x]."'";
              $result = $db->sql_query($sql);
              $row = $db->sql_fetchrow($result);
              $s_pages = $row['pages'];
              if (strpos($agent," $page_id ") < 1) {
              $s_pages .= " $page_id ";
              $s_pages = str_replace("  "," ",$s_pages);
              // теперь присвоем каждому из элементов списка id страницы, которую редактируем.
              $db->sql_query("UPDATE ".$prefix."_spiski SET pages='".$s_pages."' WHERE id='".$elements[$x]."'") or die('Ошибка при добавлении страницы в элемент списка. 9. $name');
              }
      
              }
          }
  break;
  /////
  }
  }
  }
  $db->sql_query("DELETE FROM ".$prefix."_spiski WHERE name='-00-00'"); 
  // Удаление ошибок. Потом поправить, чтобы не было их!!!
  Header("Location: sys.php?op=base_pages_add_page&name=".$module."&razdel=".$cid."&red=".$red."&new=1&pid=".$page_id."#1");
}
###################################################################################
function base_pages_edit_sv_page($pid, $module, $cid, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $nocomm, $price, $add, $data1, $data2, $data3, $data4, $keywords2, $description2, $com, $cop, $count, $sor, $open_text_mysor, $main_text_mysor) {
  global $tip, $admintip, $prefix, $db, $now;
  // Делаем резервную копию!
  $sql = "SELECT `module`,`cid`,`title`,`open_text`,`main_text`,`date`,`counter`,`active`,`golos`,`comm`,`foto`,`search`,`mainpage`,`rss`,`price`,`description`,`keywords`,`copy`,`sort`,`nocomm` FROM ".$prefix."_".$tip." WHERE `pid`='".$pid."'";
  $result = $db->sql_query($sql);
  list($p_module,$p_cid,$p_title,$p_open_text,$p_main_text,$p_date,$p_counter,$p_active,$p_golos,$p_comm,$p_foto,$p_search,$p_mainpage,$p_rss,$p_price,$p_description,$p_keywords,$p_sort,$p_nocomm) = $db->sql_fetchrow($result);
  /*
  // узнать - это галерея или нет
  $sql = "select text from ".$prefix."_mainpage where name='".$module."' and type='2'";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrow($result);
  $tex = $row['text'];
  if (strpos($tex,"media=1")) {
  $ImgDir="img";
  if ($_FILES["foto"]["name"]!="") {
  // Обработка имени файла: транслит и удаление пробелов
  $pic_name2 = date("Y-m-d_H-i-s_", time()).str_replace(" ","",translit($_FILES["foto"]["name"]));
  	if (Copy($_FILES["foto"]["tmp_name"], $ImgDir."/".basename($pic_name2))) {
  	unlink($_FILES["foto"]["tmp_name"]);
  	chmod($ImgDir."/".basename($pic_name2),0644);
  	$foto="/".$ImgDir."/".basename($pic_name2);
  	} else echo "ОШИБКА при копировании файла";
  } else $foto=trim($link_foto);
  } else 
  */
  $foto = "";
  ##----------------------------------------------------##
  // это магазин?
  $price = intval($price);
  ##----------------------------------------------------##
  $search = str_replace(", "," ",$search);
  $search = str_replace(","," ",$search);
  $search = str_replace(". "," ",$search);
  $search = str_replace("."," ",$search);
  $search = str_replace("  "," ",$search);
  $search = " ".trim($search)." "; // strtolow(
  if ($mainpage=="") $mainpage=0;
  $sor = intval($sor);
  $rss = intval($rss);
  $nocomm = intval($nocomm);

  $keywords2 = trim(str_replace("  "," ",str_replace("   "," ",str_replace(" ,",", ",$keywords2))));
  $description2 = trim($description2);
  $open_text = mysql_real_escape_string(form($module, $open_text, "open"));
  $main_text = mysql_real_escape_string(form($module, $main_text, "main"));
  $title = mysql_real_escape_string(form($module, $title, "title"));

  // Обратное преобразование textarea (замена русской буквы е)
  $main_text = str_replace("tеxtarea","textarea",$main_text); // ireplace
  $open_text = str_replace("tеxtarea","textarea",$open_text); // ireplace

  $p_open_text = mysql_real_escape_string(form($module, $p_open_text, "open"));
  $p_main_text = mysql_real_escape_string(form($module, $p_main_text, "main"));
  $p_title = mysql_real_escape_string(form($module, $p_title, "title"));

  $data = date2normal_view($data1, 1)." $data2:$data3:$data4";
  $data2 = $now;

  $sql = "UPDATE ".$prefix."_".$tip." SET module='$module', cid='$cid', title='$title', open_text='$open_text', main_text='$main_text', date='$data', redate='$data2', counter='$count', active='$active', comm='$com', foto='$foto', search='$search', mainpage='$mainpage', rss='$rss', price='$price', description='$description2', keywords='$keywords2', copy='$cop', sort='$sor', nocomm='$nocomm' WHERE pid='".$pid."';";
  $db->sql_query($sql) or die('Не удалось сохранить изменения... Передайте нижеследующий текст разработчику:<br>'.$sql);

  // Делаем резервную копию
  if ($p_active != 3) // если это не добавленная пользователем страница
  $db->sql_query("INSERT INTO ".$prefix."_".$tip." VALUES (NULL, '$p_module', '$p_cid', '$p_title', '$p_open_text', '$p_main_text', '$p_date', '$now', '$p_counter', '$p_active', '$p_golos', '$p_comm', '$p_foto', '$p_search', '$p_mainpage', '$p_rss', '$p_price', '$p_description', '$p_keywords', 'backup', '$pid', '$p_sort', '$p_nocomm' );") or die("Резервная копия не создана...");

  // Ярлык?
  $and_copy = "";
  if ($cop != 0) { // Узнаем наличие других копий
    $sql = "select pid from ".$prefix."_".$tip." where copy='".$cop."' and pid!='".$pid."'";
    $result = $db->sql_query($sql);
    $and_copy = array();
    while ($row = $db->sql_fetchrow($result)) {
      $pidX = $row['pid'];
      $and_copy[] = "pid='".$pidX."'";
      if (function_exists('recash')) recash("/-".$module."_page_".$pidX, 0); // Обновление кеша ##
    }
    $and_copy = implode(" or ",$and_copy);
    $db->sql_query("UPDATE ".$prefix."_".$tip." SET title='$title', open_text='$open_text', main_text='$main_text', date='$data', redate='$data2', counter='$count', active='$active', comm='$com', foto='$foto', search='$search', mainpage='$mainpage', rss='$rss', price='$price', description='$description2', keywords='$keywords2', sort='$sor' WHERE ".$and_copy.";");
  }

  global $siteurl;
  if (function_exists('recash') and $active == 1) {
    recash("/-".$module."_page_".$pid); // Обновление кеша ##
    recash("/-".$module."_cat_".$cid, 0); ####################
    recash("/-".$module."_cat_".$cid."_page_0", 0); ##########
    recash("/-".$module."_cat_".$cid."_page_1", 0); ##########
    recash("/-".$module."",0); ###############################
  }

  // РАБОТА СО СПИСКАМИ
  $page_id = $pid;
  if (isset($copy)) if ($copy != 0) $page_id = $copy;
  del_spiski($page_id); // Стираем упоминания о списках для переназначения
  if (!isset($add) or $add == "") $add = array();

  // Получение информации о каждом списке
  foreach ($add as $name => $elements) { 
    $sql = "select * from ".$prefix."_mainpage where name='$name' and type='4'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $s_id = $row['id'];
    $options = explode("|", $row['text']); $options = $options[1];
    $type=0; $shablon=""; 
    parse_str($options); // раскладка всех настроек списка
    switch($type) {
	  ////////////////////////////////////////////////////////////////////////////
  case "6": // регион
  $sql = "SELECT id, name, pages FROM ".$prefix."_spiski WHERE type='$name' and pages like '% $page_id %'";
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $nums = $db->sql_numrows($result);
      $del_id = $row['id'];
      $del_name = $row['name'];
      $del_pages = $row['pages'];
      if ($nums==0 or ($elements != $del_name and $del_name!="")) {
	  $id_regions = $_POST['id_regions'];
  if (!isset($id_regions)) $id_regions = $elements;
   $region = $db->sql_fetchrow($db->sql_query("select name from ".$prefix."_regions where id='".$id_regions."'"));
  $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$region['name']."', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 6'); 
}
  break;
	      case "5": // число
      $sql = "SELECT id, name, pages FROM ".$prefix."_spiski WHERE type='$name' and pages like '% $page_id %'";
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $nums = $db->sql_numrows($result);
      $del_id = $row['id'];
      $del_name = $row['name'];
      $del_pages = $row['pages'];
      if ($nums==0 or ($elements != $del_name and $del_name!="")) { // Сравним число с вводимым
       if( preg_match("/[^(0-9)|(\.)]/", $elements)) die ('Ошибка: Не удалось сохранить список. 5 - Возможен ввод только числа с плавающей точкой'); 
  else  $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '".$name."', '".$elements."', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список. 5'); 
  } // END Сравним найденный текст с вводимым
      // Если текст похож - ничего не делаем, т.к. информация не изменилась.
      break;
      case "4": // строка
      // Найдем текст для данной страницы
      $sql = "SELECT id, name, pages FROM ".$prefix."_spiski WHERE type='$name' and pages like '% $page_id %'";
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $nums = $db->sql_numrows($result);

      $del_id = $row['id'];
      $del_name = $row['name'];
      $del_pages = $row['pages'];
      if ($nums==0 or ($elements != $del_name and $del_name!="")) { // Сравним найденный текст с вводимым
              $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список.');
      } // END Сравним найденный текст с вводимым
      // Если текст похож - ничего не делаем, т.к. информация не изменилась.

      break;
      ///////////////////////////////////////////////////////////////////////////////////////////
      case "3": // период времени
      // создаем диапазон дат и все их проверяем
      $elements = explode("|",$elements);
      $dat1 = date2normal_view($elements[0], 1);
      $dat2 = date2normal_view($elements[1], 1);
      $period = period($dat1, $dat2);

      // и все даты проверяем на наличие в БД
      $upd = array();
      $noupd = array();

      $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='$name' order by name";
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

      $update[] = "name='$up'";
      }
      foreach ($period as $per) {
      if (!in_array($per, $noupd)) $insert[] = "(NULL, '$name', '$per', '', '0', ' $page_id ', '0')";
      }

      $insert = implode(", ",$insert);
      $update = implode(" or ",$update);

      $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE type='$name' and (".$update.") order by name";
      $result = $db->sql_query($sql);
      while ($row = $db->sql_fetchrow($result)) {
      $na = $row['name']; // дата
      $pa = $row['pages']; // страницы
      	$db->sql_query("UPDATE ".$prefix."_spiski SET pages = ' $pa $page_id ' WHERE type='$name' and name='$na'") or die ("Ошибка: Не удалось обновить списки. $page_id $name");
      }

      	if (trim($insert) != "") {
      	$db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES ".$insert.";") or die ('Ошибка: Не удалось сохранить списки.');
      	}
      break;
      ///////////////////////////////////////////////////////////////////////////////////////////////
      case "2": // файл

      // Неокончено !!!!!!!!!
      break;
      ///////////////////////////////////////////////////////////////////////////////////////////////
      case "1": // текст
      // Найдем текст для данной страницы
      $sql = "SELECT id, name, pages FROM ".$prefix."_spiski WHERE type='$name' and pages like '% $page_id %'";
      $result = $db->sql_query($sql);
      $row = $db->sql_fetchrow($result);
      $nums = $db->sql_numrows($result);

      $del_id = $row['id'];
      $del_name = $row['name'];
      $del_pages = $row['pages'];
      if ($nums==0 or ($elements != $del_name and $del_name!="")) { // Сравним найденный текст с вводимым
          // записываем новый текст - Проверяем наличие подобного текста
          /*
          $sql = "SELECT name, pages FROM ".$prefix."_spiski WHERE name='$elements'";
          $result = $db->sql_query($sql);
          $numrows = $db->sql_numrows($result);
          if ($numrows > 0) { // если элемент найден
              $row = $db->sql_fetchrow($result);
              $s_pages = $row['pages'];
              $s_name = $row['name'];
                  if (strpos(" ".$s_pages," $page_id ") < 1 and $s_name==$elements) {
                      $s_pages .= " $page_id ";
                      $s_pages = " ".str_replace("  "," ",trim($s_pages))." ";
                      $db->sql_query("UPDATE ".$prefix."_spiski SET pages='$s_pages' WHERE type='$name' and name='$elements'") or die('Ошибка при добавлении страницы в элемент списка');;
                  } else {
                      $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список.');
                  }
          } else { // если элемент новый
          */
          // 
              $db->sql_query("INSERT INTO ".$prefix."_spiski (id, type, name, opis, sort, pages, parent) VALUES (NULL, '$name', '$elements', '', '0', ' $page_id ', '0');") or die ('Ошибка: Не удалось сохранить список.');
          //}
      } // END Сравним найденный текст с вводимым
      // Если текст похож - ничего не делаем, т.к. информация не изменилась.
      break;
      ////////////////////////////////////////////////////////////////////////////
      case "0": // список
      $num = count($elements); // сколько элементов в списке
      for ($x=0; $x < $num; $x++) { // посчитали сколько номеров списка
      	if ($elements[$x] != 0) { // Если это не "Не выбрано"
      	// узнаем какие страницы уже есть у этого номера из списка
      	$sql = "SELECT pages FROM ".$prefix."_spiski WHERE id='$elements[$x]'";
      	$result = $db->sql_query($sql);
      	$row = $db->sql_fetchrow($result);
      	$s_pages = $row['pages']." $page_id ";
      	$save_pages = str_replace("  "," ",$s_pages);
      	// теперь присвоем каждому из элементов списка id страницы, которую редактируем.
      	$db->sql_query("UPDATE `".$prefix."_spiski` SET `pages` =  '".$save_pages."' WHERE  `id` =".$elements[$x]." LIMIT 1 ;") or die('Ошибка при добавлении страницы в элемент списка');
      	}
      } 
      break;
    }
  }
  $db->sql_query("DELETE FROM ".$prefix."_spiski WHERE name='-00-00'"); // Удаление ошибок. Потом поправить, чтобы не было их!!!
  Header("Location: sys.php?op=base_pages_edit_page&name=".$name."&new=1&pid=".$pid);
}
#####################################################################################################################
function base_pages_delit_page($name,$pid, $ok) {
    global $tip, $admintip, $prefix, $db;
    $pid = intval($pid);
    $db->sql_query("DELETE FROM ".$prefix."_".$tip." WHERE pid='$pid'");
    Header("Location: sys.php");
}
#####################################################################################################################
function base_delit_comm() {
  global $tip, $prefix, $db;
  $db->sql_query("DELETE FROM ".$prefix."_".$tip."_comments WHERE cid>'0';") or die('Не удалось удалить комментарии');
  $db->sql_query("UPDATE ".$prefix."_".$tip." SET comm='0' WHERE comm>'0';") or die('Не удалось удалить записи о комментариях в страницах');
  $db->sql_query("UPDATE ".$prefix."_".$tip." SET counter='0' WHERE counter>'0';") or die('Не удалось удалить счетчики посещиний в страницах');
  $db->sql_query("UPDATE ".$prefix."_".$tip." SET golos='0' WHERE golos>'0';") or die('Не удалось удалить записи о голосованиях в страницах');
  Header("Location: sys.php");
}
#####################################################################################################################
function base_delit_noactive_comm($del="noactive") {
  global $tip, $prefix, $db;
  if ($del == "noactive") $db->sql_query("DELETE FROM ".$prefix."_".$tip."_comments WHERE cid>'0' and active!='1';") or die('Не удалось удалить отключенные комментарии');
  if ($del == "system") $db->sql_query("DELETE FROM ".$prefix."_".$tip."_comments WHERE num='0' and avtor='ДвижОк' and mail='';") or die('Не удалось удалить системные комментарии');
  Header("Location: sys.php");
}
#####################################################################################################################
function base_pages_delit_comm($cid, $ok, $pid) {
	mt_srand((double)microtime()*1000000);
	$num1 = mt_rand(100, 900);
  $url = getenv("HTTP_REFERER"); // REQUEST_URI
    global $tip, $prefix, $db;
    $cid = intval($cid);
    $pid = intval($pid);
    if($ok=="ok") {
    $db->sql_query("DELETE FROM ".$prefix."_".$tip."_comments WHERE cid='$cid'");
    $db->sql_query("UPDATE ".$prefix."_".$tip." SET comm=comm-1 WHERE pid = '$pid' and comm > '0'");
    $sql = "select module from ".$prefix."_".$tip." where pid = '$pid'";
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $mod = $row['module'];
    if (function_exists('recash')) recash("/-".$mod."_page_".$pid); // Обновление кеша ##
      $url = str_replace("#comm","",$url);
      Header("Location: $url");
    }
}
##################################################################################
function base_pages_re($link) {
    global $referer;
    recash($link);
    Header("Location: $referer");
}
###################################################################################
  switch ($op) {
      case "edit_base_pages_category":
      edit_base_pages_category($cid, $red);
      break;

      case "base_pages_save_category":
      base_pages_save_category($cid, $module, $title, $desc, $sortirovka, $parent_id);
      break;

      case "base_pages_add_page":
      if (!isset($razdel)) $razdel = "";
      if (!isset($name)) $name = "";
      if (!isset($red)) $red = "3";
      base_pages_add_page(0, $red, $name, $razdel);
      break;

      case "base_pages_save_page":
      if (!isset($foto)) $foto = "";
      if (!isset($link_foto)) $link_foto = "";
      if (!isset($mainpage)) $mainpage = "";
      if (!isset($add)) $add = "";
      if (!isset($open_text_mysor)) $open_text_mysor = "";
      if (!isset($main_text_mysor)) $main_text_mysor = "";
      base_pages_save_page($cid, $module, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $nocomm, $price, $add, $data1, $data2, $data3, $data4, $keywords2, $description2, $sor, $open_text_mysor, $main_text_mysor);
      break;

      case "base_pages_edit_page":
      if (!isset($red)) $red = 0;
      base_pages_add_page($pid, $red);
      break;

      case "base_pages_edit_sv_page":
      if (!isset($link_foto)) $link_foto = "";
      if (!isset($mainpage)) $mainpage = "";
      if (!isset($add)) $add = "";
      if (!isset($open_text_mysor)) $open_text_mysor = "";
      if (!isset($main_text_mysor)) $main_text_mysor = "";
          base_pages_edit_sv_page($pid, $module, $cid, $title, $open_text, $main_text, $foto, $link_foto, $search, $active, $mainpage, $rss, $nocomm, $price, $add, $data1, $data2, $data3, $data4, $keywords2, $description2, $com, $cop, $count, $sor, $open_text_mysor, $main_text_mysor);
      break;

      case "base_pages_delit_page":
      if (!isset($ok)) $ok = 0; else $ok = 1;
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
  }
}
?>
