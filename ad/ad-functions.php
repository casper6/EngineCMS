<?php
##########################################################################################
function page_admin($txt, $pid) { // добавление функций админа к странице сайта
  global $db, $prefix, $module_name, $url, $name;
  if ( $pid > 0 ) $red = "
<script>
function delpage(id) {
  $.ajax({ url: 'a-ajax.php', cache: false, dataType : 'html',
      data: {'func': 'delpage', 'id': id}
  });
  $('#redact').html('Страница удалена в Корзину.');
}
</script>
<a target=_blank href='/sys.php?op=base_pages_edit_page&amp;pid=".$pid."'>Редактировать страницу</a><br><a target=_blank style='cursor:pointer; color:red;' onclick='delpage(".$pid.");'>Удалить страницу</a><br>"; 
  elseif ( $pid == 0 and $module_name != "" ) {
    // выяснить id
    $sql55="SELECT `id` from ".$prefix."_mainpage where `tables`='pages' and `type`='2' and `name`='".$name."'";
    $result55 = $db->sql_query($sql55);
    $row55 = $db->sql_fetchrow($result55);
    $name_id = $row55['id'];
    $red = "<a target=_blank href='/sys.php?op=mainpage&amp;id=$name_id'>Редактировать главную страницу раздела</a><br><a target=_blank href='sys.php?op=base_pages_add_page&amp;name=$name#1'>Добавить новую страницу в раздел</a><br>";
  }
  elseif ( $module_name == "" ) $red = "<a target=_blank href='/sys.php?op=mainpage&amp;id=24'>Редактировать Главную страницу</a><br>";
  else $red = "";
  $url = getenv("REQUEST_URI");
  $txt = str_replace("</body>","<div id='redact_show' style='position:absolute; top:10px; floaf:right; right:20px; z-index:300; width:18px;'><a title='Показать настройки администратора' href=# style='cursor:pointer;' onclick=\"show('redact'); show('redact_show');\"><img class='icon2 i35' src='/images/1.gif'></a></div><div id='redact' style='background: white; color: black; position:absolute; top:5px; display:none; floaf:right; right:20px; z-index:300; width:290px;' class=show_block><div class=show_block_title><a title='Скрыть настройки администратора' href=# style='cursor:pointer;' onclick=\"show('redact_show'); show('redact');\"><img class='icon2 i33' src='/images/1.gif' align=right></a>Настройки администратора</div>".$red."<form method=post name=blocks_show action='".$url."' style='display:inline;'><input type='hidden' name=blocks value='1'><a href='javascript:document.blocks_show.submit();' title='Показать редактирование блоков на странице'>Показать редактирование блоков</a></form><br><a href='/sys.php?op=base_pages_re&amp;link=".$url."'>Обновить страницу</a></div></body>",$txt);
  return $txt;
}
///////////////////////////////////////////////////////////////
function del_spiski($page_id) { // Стираем упоминания страницы во всех элементах списка
  global $prefix, $db;
  $sql = "SELECT id, pages FROM ".$prefix."_spiski WHERE pages like '% $page_id %'";
  $result = $db->sql_query($sql);
  while ($row = $db->sql_fetchrow($result)) {
    $del_id = $row['id'];
    $del_pages = " ".trim(str_replace("  "," ",str_replace(" $page_id "," ",$row['pages'])))." ";
    if (trim($del_pages)=="") $del_pages=""; // Стираем пустые страницы
    $db->sql_query("UPDATE ".$prefix."_spiski SET pages='$del_pages' WHERE id='$del_id'") or die('Ошибка: Не удалось стереть всю ранее введенную в списки информацию.');
  }
}
///////////////////////////////////////////////////////////////
function admin_footer() { // проверить вызов
  if ( stristr($_SERVER['HTTP_USER_AGENT'], 'Firefox') or stristr($_SERVER['HTTP_USER_AGENT'], 'Chrome') or stristr($_SERVER['HTTP_USER_AGENT'], 'Safari') ) {} else echo "<span class='gray noprint'>Для администрирования сайта желательно использовать браузер <a href='https://www.google.com/chrome?hl=ru' target='_blank'>Google Chrome</a>, <a href='http://www.apple.com/ru/safari/' target='_blank'>Apple Safari</a> или <a href='http://www.mozilla.org/ru/firefox/' target='_blank'>Mozilla Firefox</a>. <a href='http://www.whatbrowser.org/ru/' target='_blank'>Что такое браузер?</a></span></div></body>\n</html>";
  ob_end_flush();
  die();
}
///////////////////////////////////////////////////////////////
function scandirectory($dir, $echoscan="", $images) {
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
    if (strpos(" ".$item,"includes/phpThumb")) {
      $item = explode("&", str_replace("/includes/phpThumb/phpThumb.php?","",$item));
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
///////////////////////////////////////////////////////////////
function num_ending($number, $endings) { // функция правильных окончаний слов
    $num100 = $number % 100;
    $num10 = $number % 10;
    if ($num100 >= 5 && $num100 <= 20) { return $endings[0];
    } else if ($num10 == 0) { return $endings[0];
    } else if ($num10 == 1) { return $endings[1];
    } else if ($num10 >= 2 && $num10 <= 4) { return $endings[2];
    } else if ($num10 >= 5 && $num10 <= 9) { return $endings[0];
    } else { return $endings[2]; }
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

##########################################################################################

?>
