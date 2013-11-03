/* JS для админ-панели — подписать все функции! */
var global_spisok_name = global_spisok_title = "";
function aa(t,lang_admin,lang_admin_text) { // Функция перевода сайта / Translate function
  if (lang_admin == 'ru') return t; // Русский — по-умолчанию.
  else {
      if (lang_admin_text[t]) return lang_admin_text[t];
      else return " [ Error: no translate for: "+t+" ] ";
  } 
}
function pics_refresh(txt){
	$('.pics').html('');
	text = $(txt).val();
	text = text.split('\n');
	$.each(text, function(index, value) {
		val = value.split('|');
		if (val[1] == ''  || typeof val[1] == 'undefined') val[1] = 'без имени';
		id = val[0].replace(".", "").replace("/", "").replace("/", "");
		if (val[0] != '') $('.pics').append('<div id="' + id + '" class="pic" style="background:url(\'includes/phpThumb/phpThumb.php?src=' + val[0] + '&amp;w=160&amp;h=100&amp;q=0\') no-repeat bottom white;"><a title="Удалить фото" class="button small red white" onclick="pics_replace(\'#' + id + '\',\'' + txt + '\',\'' + value + '\');">×</a><span>' + val[1] + '</span></div>');
	});
}
function pics_replace(id,txt,search){
	text = $(txt).val();
	text = text.replace(search + '\n', '');
	$(txt).val(text);
	$(id).hide('slow');
}

function sho(pid,name,act,id_razdel,cid,edit_pole) {
	if (edit_pole == "1") add_pole = '<p><form class="light_fon" id="spiski_'+pid+'"><a onclick="save_main(\'ad/ad-page.php\', \'page_save_spiski\', \'#spiski_'+pid+'\', \'#pid'+pid+'\');" class="button small green">'+icon('medium white','c')+' Сохранить</a><input type="hidden" name="page_id" value="'+pid+'"><div id="pole' + pid + '"></div></form>';
	else add_pole = '';
	if (act == 1) active = 'Выключить страницу">' + icon('red small','Q')+'</a>';
	else active = 'Включить страницу">' + icon('green small','Q')+'</a>';
	if (document.getElementById('pid'+pid).innerHTML=='') document.getElementById('pid'+pid).innerHTML = '<br><ul class="button-bar"><li class="first"><a href=-'+name+'_page_'+pid+' target=_blank title="Открыть страницу на сайте">'+icon('blue small','s')+' Открыть</a></li><li><a target=_blank href="sys.php?op=base_pages_edit_page&pid='+pid+'#1" title="Редактировать страницу">'+icon('orange small','7')+' Редактировать</a></li><li class="punkt"><a onclick=replace("'+pid+'") title="Копировать/Переместить/Создать ярлык">'+icon('blue small','^')+'</a></li><li class="punkt"><a onclick=offpage('+pid+',0) title="'+active+'</li><li class="last punkt"><a onclick=delpage("'+pid+'") title="Удалить страницу">'+icon('red small','F')+'</a></li></ul>'+add_pole;
	else document.getElementById('pid'+pid).innerHTML = '';
	if (edit_pole == "1") {
		var x = '$(function() { show_pole(' + id_razdel + ',' + pid + ',\'' + name + '\',' + cid + '); });';
		var y = document.createElement ('script'); //Создаём новый тег <SCRIPT> 
		y.defer = true; // Даём разрешение на исполнение скрипта после его "приживления" на странице 
		y.text = x; //Записываем полученный от сервера "набор символов" как JS-код 
		document.body.appendChild (y); //Приживляем тег <SCRIPT> // это делается вместо document.getElementById(id).innerHTML = ...
	}
}
function papka_show(cid, name, sort, id, xxx) {
	show('podpapka'+cid);
	if (document.getElementById('papka'+cid).innerHTML=='') { 
		document.getElementById('papka'+cid).innerHTML = '<br><ul class="button-bar"><li class="first"><a target=_blank href=-'+name+'_cat_'+cid+' title="Открыть эту папку на сайте">'+icon('blue small','s')+' Открыть</a></li><li><a target=_blank href="sys.php?op=edit_base_pages_category&cid='+cid+'#1" title="Редактировать папку">'+icon('orange small','7')+' Редактировать</a></li><li class="last"><a onclick=delpapka("'+cid+'") style="cursor:pointer;" title="Удалить папку">'+icon('red small','F')+'</a></li></ul>';
		papka(cid, sort, id,xxx);
	} else {
		document.getElementById('papka'+cid).innerHTML = '';
		document.getElementById('podpapka'+cid).innerHTML = '';
	}
}
function papka(cid, sort, id, xxx) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'papka', 'id': id, 'string': cid+'*@%'+sort},
	    beforeSend: function(){ $('#podpapka'+cid).html('<br><img src=images/loading.gif> Загрузка страниц и подпапок...'); },
	    success: function(data){ $('#podpapka'+cid).html(data); }
	});
}
function show_pole(id, page_id, razdel, cid) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'show_pole', 'id': id, 'string': razdel+'*@%'+page_id+'*@%'+cid},
	    beforeSend: function(){ $('#pole'+page_id).html('<br><img src=images/loading.gif> Загрузка...'); },
	    success: function(data){ $('#pole'+page_id).html(data); }
	});
}
function save_main(url, op, id, id2) {
	if (id == "") id = 'form';
	if (id2 == "") id2 = '#save_main';
	var msg = $(id).serialize();
    $.ajax({
      type: 'POST',
      url: url,
      data: {'op': op, 'string': msg },
	  beforeSend: function(){ $(id2).html('<img src=images/loading.gif> Сохраняю...'); },
      success: function(data) { $(id2).html(data); if (id2 != '#save_main') $(id2).html(icon('green small','*')); },
      /* error: function(xhr, str) { alert('Возникла ошибка: ' + xhr.responseCode ); } */
    });
}
function show_otvet_comm(cid, name, mail, mod) {
	if ($('#otvet_comm'+cid).html() == '') {
		$('#show_otvet_link'+cid).hide();
		$('#otvet_comm'+cid).html('<div style="float:left;">'+icon('gray medium','u')+'</div><input type=text id="otvet_comm_sender'+cid+'" value="Администратор" size=15 style="width:90%; max-width:800px;"><br>'+icon('gray medium','@')+' Текст ответа:<br><textarea style="width:95%; max-width:800px;height:100px;" id="otvet_comm_txt'+cid+'"></textarea><dt><a onclick=\'new_otvet(0, '+cid+', document.getElementById("otvet_comm_sender'+cid+'").value, document.getElementById("otvet_comm_txt'+cid+'").value, "'+mail+'", "'+mod+'")\' class="button">'+icon('orange small','\'')+' Отправить ответ</a>');
		if (mail != '') $('#otvet_comm'+cid).append('Отправить только: <a onclick=\'new_otvet(1, '+cid+', document.getElementById("otvet_comm_sender'+cid+'").value, document.getElementById("otvet_comm_txt'+cid+'").value, "'+mail+'", "'+mod+'")\' class=punkt>по email</a> или <a onclick=\'new_otvet(2, '+cid+', document.getElementById("otvet_comm_sender'+cid+'").value, document.getElementById("otvet_comm_txt'+cid+'").value, "'+mail+'", "'+mod+'")\' class=punkt>через сайт</a>');
		$('#otvet_comm'+cid).append('<br><div id="otvet_send'+cid+'"></div>');
		$('#otvet_comm_txt'+cid).focus(); 
		$('#otvet_comm_txt'+cid).val(name+', '); 
	}
}
function new_otvet(type, id, sender, info, mail, mod) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'comm_otvet', 'id': id, 'type': type, 'string': sender+'*@%'+info+'*@%'+mail+'*@%'+mod},
	    beforeSend: function(){ $('#otvet_send'+id).html('<img src=images/loading.gif> Отправляю ответ...'); },
	    success: function(data){ $('#otvet_send'+id).html('<span class=h1>'+icon('green medium','c')+data+'</span>'); }
	});
}
function trash_pics() {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'trash_pics'},
	    beforeSend: function(){ $('#show_options_oldfotos').html('<br><img src=images/loading.gif> Загружаю список фотографий...'); },
	    success: function(data){ $('#show_options_oldfotos').html(data); }
	});
}
function replace(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'replace', 'id': id},
	    beforeSend: function(){ $('#pid'+id).html('<br><img src=images/loading.gif> Загрузка функций копирования/перемещения и создания ярлыков...'); },
	    success: function(data){ $('#pid'+id).html(data); }
	});
}
function select_button(id) {
	$('.dark_pole2sel').attr('class', 'dark_pole2');
	$('#'+id).attr('class', 'dark_pole2sel');
}
function options_show(id,type) {
	select_button('mainrazdel'+id);
	if (id == 4 || id == 7 || id == 10) $('#save_options').hide();
	else $('#save_options').show();
	$('.show_pole').hide();
	$('#'+type).show();
}
function save_spisok() {
	var msg = $('form#save_spisok').serialize();
    $.ajax({
      type: 'POST',
      url: 'ad/ad-ajax.php',
      data: {'func': 'save_spisok', 'string': msg },
	  beforeSend: function(){ $('#add_spisok').html('<img src=images/loading.gif> Сохраняю...'); },
      success: function(data) { $('#add_spisok').hide('slow'); 
      /*spiski_show(global_spisok_name, global_spisok_title); */
  }
    });
}
function add_spisok(id, type, name, pages, opis, sort, parent) {
	if (id == 0) { title = 'Добавление значения поля'; name_text = 'Вы можете ввести несколько названий (разделять Enter)'; }
	else { title = 'Редактирование значения поля'; name_text = 'Название'; }
	var data = '<span class=h2>' + title + '</span><form id="save_spisok"><input name="id" type="hidden" value="'+id+'"><input name="type" type="hidden" value="'+type+'"><p><b>'+name_text+':</b><textarea class="w100 h40" name="name" autofocus>'+unescape(name)+'</textarea><div class="" id="another_options"><p>Страницы (№ страниц через пробел):<textarea class="w100 h40" name="pages">'+pages+'</textarea><p>Описание (пояснительная информация):<textarea class="w100 h40" name="opis">'+opis+'</textarea><p>Сортировка: <input class="w10" name="sort" type="text" value="'+sort+'"> Вложенность: <input class="w10" name="parent" type="text" value="'+parent+'"></div><p class="center"><a class="button middle green white" onclick="save_spisok()"> '+icon('white small','c')+' Сохранить </a> <a class="button middle" onclick="$(\'#add_spisok\').hide();"> Отмена </a></form>';
	$('#add_spisok').html( data ).show();
}
function spiski_show(name, title) {
	global_spisok_name = name;
	global_spisok_title = title;
	var txt;
	txt = '<span class=h2>Редактирование значений поля «'+title+'»</span><p>При удалении значения также удаляется информация из связанных с ним страниц.<br>Пример: Создав поле «Цена» (prise, число) и выставив для страницы его значение, например 1000, мы создали значение prise = 1000. Здесь мы можем его удалить также, как если бы отредактировали страницу и стерли 1000 в поле Цена.</p><a class="button green" style="margin-top:4px;margin-bottom:10px;" target="_blank" onclick="add_spisok(\'0\', \''+name+'\', \'\', \'\', \'\', \'0\', \'0\')"> '+icon('white small','+')+' Добавить значение поля</a>';
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'spiski_show', 'type': name },
	    beforeSend: function(){ $('#podrazdel').html( txt + '<p><img src=images/loading.gif> Загрузка...' ); },
	    success: function(data){ $('#podrazdel').html( txt + data ); }
	});
}
function oformlenie_show(title,id,type,link) {
	var txt = '';
	select_button('mainrazdel'+id);
	if (id==2) txt = '<p>При удалении объекта оформления, он попадает в «Удаленное оформление» для окончательного удаления или восстановления.<p>'+icon('red small','F')+' <a href=sys.php?op=delete_all&del=design>Очистить Удаленное</a>';
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'oformlenie_show', 'type': type },
	    beforeSend: function(){ $('#podrazdel').html( txt + '<p><img src=images/loading.gif> Загрузка...' ); },
	    success: function(data){ $('#podrazdel').html( txt + data ); }
	});
}
function razdel_show(title,id,name,type,xxx,sort) {
	var txt;
	select_button('mainrazdel'+id);
	xxx = Math.floor( Math.random() * (100000 - 9) ) + 10;
	colors = '<span id="colors" class="hide"><a class="punkt" onclick="$(\'#add\').hide(\'slow\');"><div class="radius" style="font-size:12pt; width:20px; height: 20px; color: white; text-align:center; float:right; margin:5px; background: #bbbbbb;">&nbsp;x&nbsp;</div></a><h1>Выберите цвет раздела по частоте его использования:</h1><ul class="button-bar small"><li class="first"><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=0" title="Без цветовой маркировки">'+icon('gray small',',')+' не выбрано</a></li><li><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=1" title="Раздел часто используется">'+icon('lightgreen small',',')+' часто</a></li><li><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=2" title="Раздел редко используется">'+icon('lightyellow small',',')+' редко</a></li><li><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=3" title="Раздел не используется">'+icon('lightred small',',')+' не используется</a></li><li class="last"><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=4" title="Новый раздел, в разработке">'+icon('lightblue small',',')+' новый</a></li></ul><br>Цвет может использоваться для сортировки разделов при нажатии <img src="images/sortirovka.png"> кнопки сортировки слева от заголовка «Разделы:».</span>';
	del_razdel = '<li><a href="sys.php?op=mainpage&id='+id+'&amp;type=2&nastroi=1#1" title="Настройки (опции) раздела">'+icon('orange small','V')+' Настроить</a></li><li class="last"><a class=pointer onclick="$(\'#add\').show().html( $(\'#del_raz'+id+'\').html() )" title="Удалить этот раздел">'+icon('red small','F')+'</a></li></ul></nobr><div id="del_raz'+id+'" class="hide"><h1>Вы хотите удалить этот раздел и всё его содержимое?</h1><a onclick=\'delrazdel("'+id+'"); $("#add").hide("slow"); show("mainrazdel'+id+'"); razdel_show("Раздел удалён", 0)\' title="Удалить этот раздел" class="button red white">'+icon('white medium','F')+' Удалить</a> <a onclick="$(\'#add\').hide(\'slow\')" title="Не удалять" class="ml50 button">НЕ удалять</a></div>';
	if (name == 'index') del_razdel = '<li class="last"><a href="sys.php?op=mainpage&id='+id+'&amp;type=2&nastroi=1#1" title="Настройки (опции) раздела">'+icon('orange small','V')+' Настроить</a></li></ul></nobr>';
	if (id == 0) txt = '<h1>'+title+'</h1>';
	else {
		txt = '<nobr><ul class="button-bar"><li class="first"><a target="_blank" class="blue" href=-'+name+' title="Открыть этот раздел на сайте">'+icon('blue small','s')+' Открыть</a></li><li><a href="sys.php?op=mainpage&amp;type=2&id='+id+'#1" title="Редактировать Главную страницу этого раздела и Шаблон для заполнения страниц">'+icon('orange small','7')+' Редактировать</a></li>'+del_razdel; 
		if (type == 'pages') {
			txt = txt+'<div class="mt5 mb20"><ul class="button-bar"><li class="first"><a title="Добавить страницу (в редакторе)" target=_blank href="sys.php?op=base_pages_add_page&name='+name+'#1">'+icon('small','+')+' Добавить страницу</a></li><li class="last"><a class="pointer" onclick="add_papka(\''+id+'\',0)">'+icon('orange small',',')+' Добавить папку</a></li></ul> <a class="button small" onclick=\'$("#add").show().html( $("#colors").html() );\' title="Цвет раздела (видит только администратор)">'+icon('gray small',',')+' Цвет</a><br><a onclick="add_papka(\''+id+'\',1)" class="button small" title="Добавить несколько страниц">или несколько страниц</a> ' + colors + '<div id="add_papka" style="display:none;"></div></div>';
		} else if (type == 'database') {
			txt = txt+'<div class="mt5 mb20"><nobr><a class="button green" href="sys.php?op=base_base_create_base&base='+name+'&name='+name+'&amp;red=1#1" title="Добавить строку в базу данных">'+icon('white small','+')+' Добавить строку</a> <a class="button blue" href="sys.php?op=base_base&name='+name+'" title="Открыть базу данных">'+icon('white small','s')+' Открыть таблицу</a> <a class="button small" onclick=\'$("#add").show().html( $("#colors").html() );\' title="Цвет раздела (видит только администратор)">'+icon('gray small',',')+' Цвет</a>' + colors + '</nobr></div>';
		} else txt = txt+' <a class="button small" onclick=\'$("#add").show().html( $("#colors").html() );\' title="Цвет раздела (видит только администратор)">'+icon('gray small',',')+' Цвет</a>' + colors;
		if (type == 'page') txt = txt+'<p>Раздел является одиночной страницей, т.к. не содержит блока отображения страниц — [содержание] или [страницы].<br>Для изменения содержания раздела нажмите кнопку Редактировать.<br>Для размещения в разделе страниц и/или папок — добавьте в содержание раздела блок [содержание] или блоки [название] и [страницы].';
	}
	document.getElementById('podrazdel').innerHTML = txt;
	if (type == 'pages') { razdel(id, sort, xxx, txt); }
}
function icon(classes,data) {
	return '<span class="icon '+classes+'" data-icon="'+data+'" style="display: inline-block; "><span aria-hidden="true">'+data+'</span></span>';
}
function razdel(id, sort, re, txt) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'razdel', 'id': id, 'string': re+'*@%'+sort},
	    beforeSend: function(){ $('#podrazdel').html('<p><img src=images/loading.gif> Загрузка страниц и папок раздела...'); },
	    success: function(data){ $('#podrazdel').html(txt + data + '<hr>'); }
	});
}
function add_papka(id,pages) {
	if (pages==0) pages = 'add_papka'; else pages = 'add_pages';
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': pages, 'id': id},
	    beforeSend: function(){ $('#add').show(); $('#add').html('<p><img src=images/loading.gif> Загрузка списка папок раздела...'); },
	    success: function(data){ $('#add').html(data); }
	});
}
function save_papka(id,title,parent,name,pages) {
	if (pages==0) pages = 'addpapka'; else pages = 'addpages';
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': pages, 'id': id, 'string': title+'*@%'+parent },
	    success: function(data){ $('#add').hide(); razdel_show(data,id,name,'pages',(Math.floor( Math.random() * (100000 - 9) ) + 10)); }
	});
}
function offpage(id,on) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'offpage', 'id': id },
	    success: function(data){
	    	if (on == 1) $('#1page'+id).html('<td colspan="4" class="notice success"><h2 class="center">Страница включена</h2></td>');
	    	else $('#page'+id).html(data);
	     }
	});
}
function openbox(id,name,type) {
	select_button('openbox'+id);
	var add = '';
	var no_answer_comm = '<a class="nothing punkt" onclick="openbox(\'7\',\'Комментарии без ответов\');">без ответов</a>';
	var no_active_comm = '<a class="nothing punkt" onclick="openbox(\'6\',\'Отключенные комментарии\');">отключенные</a>';
	if (name == 'Комментарии без ответов') no_answer_comm = '<b>без ответов</b>';
	if (name == 'Отключенные комментарии') no_active_comm = '<b>отключенные</b>';
	if (name == 'Комментарии' || name == 'Комментарии без ответов' || name == 'Отключенные комментарии') add = '<span style="margin-left: 20px;">'+icon('red small','F')+'Удалить: <a href="sys.php?op=delete_noactive_comm" class="gray nothing"> отключенные</a>, <a href="sys.php?op=delete_system_comm" class="gray nothing">системные</a> <a href="sys.php?op=base_comments" style="margin-left: 20px;" class="gray nothing">'+icon('blue small','s')+'Открыть все</a> <nobr><span style="margin-left: 20px;">'+icon('orange small','\'')+'Показать: '+no_active_comm+', '+no_answer_comm+'</span></nobr><br><br>';
	if (name == 'Удаленное') add = 'При удалении страниц, они попадают в Удаленное для окончательного удаления или восстановления.<br>'+icon('red small','F')+' <a href="sys.php?op=delete_all&del=del" class="gray nothing">Очистить Удаленное</a><br><br>';
	if (name == 'Резервные копии') add = 'При редактировании страниц создаются их копии для восстановления при необходимости. Внимание: восстанавливая предыдущий вариант страницы, вы заменяете новый!<br><a href="sys.php?op=delete_all&del=backup" style="margin-left: 20px;" class="gray nothing">'+icon('red small','F')+'Удалить все копии</a><br><br>';
	if (name == 'Добавленное посетителями') add = 'Эти страницы нуждаются в проверке и включении.<br><br>';
	if (name == 'Новое и отредактированное') add = 'История последних изменений<br><br>';
	if (type != 'add') type = 'podrazdel';
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'opengarbage', 'id': id},
	    beforeSend: function(){ $('#'+type).html('<h1><img src=images/loading.gif> Загружаю...</h1>'); },
	    success: function(data){ $('#'+type).html('<h1>'+name+'</h1>'+add+data); $('#'+type).show(); }
	});
}
function delslovo(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delslovo', 'id': id},
	    beforeSend: function(){ $('#s_'+id).html(' <img src="images/loading.gif"> Удаляю...'); },
	    success: function(data){ $('#s_'+id).html(' <b class="green">Удалено</b>'); }
	});
}
function del_csv(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'del_csv', 'id': id},
	    beforeSend: function(){ $('#id_'+id).html('<td colspan="4"></td><td><img src="images/loading.gif"> Удаляю...</td>'); },
	    success: function(data){ $('#id_'+id).html('<td colspan="5"><div class="notice error">Файл удален</div></td>'); }
	});
}
function delpage(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delpage', 'id': id}
	});
	$('#page'+id).hide();
	$('#1page'+id).hide();
}
function deletepage(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'deletepage', 'id': id}
	});
	$('#delpage'+id).hide();
	$('#backuppage'+id).hide();
}
function resetpage(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'resetpage', 'id': id }
	});
	$('#delpage'+id).html('<td colspan="3" class="notice success"><h2 class="center">Страница успешно восстановлена</h2></td>');
	$('#backuppage'+id).html('<td colspan="3" class="notice success"><h2 class="center">Оригинал страницы заменен на резервную копию</h2></td>');
}
function delcomm(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delcomm', 'id': id },
	    beforeSend: function(){ $('#1comm'+id).hide(); $('#comm'+id).hide(); },
	});
}
function del_file(file, id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delfile', 'type': file },
	    beforeSend: function(){ $('#file'+id).html(' <img src=images/loading.gif> Удаляю...'); },
	    success: function(data){ $('#file'+id).hide(); }
	});
}
function delblock(id,type) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delblock', 'id': id, 'type': type },
	    beforeSend: function(){ $('#block_'+id).hide(); },
	});
}
function delspisok(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delspisok', 'id': id },
	    beforeSend: function(){ $('#block_'+id).hide(); },
	});
}
function offblock(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'offblock', 'id': id },
	    success: function(data){ $('#block_'+id).html(data); }
	});
}
function offcomm(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'offcomm', 'id': id },
	    success: function(data){ $('#1comm'+id).html(data); $('#comm'+id).hide(); }
	});
}
function delrazdel(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delrazdel', 'id': id }
	});
}
function rep(id,type,razdel,papka) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'rep', 'type': type, 'id': id, 'string': papka+'*@%'+razdel },
	    beforeSend: function(){ $('#rep'+id).html(' <img src=images/loading.gif> Секундочку...'); },
	    success: function(data){ $('#rep'+id).html(data); if (type == 3) $('#page'+id).hide('slow'); }
	});
}
function clo(pid) {
	document.getElementById('pid'+pid).innerHTML = '';
}
function izmenapapka(select,papka,this_cid,id,type) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'izmenapapka', 'id': id, 'type': type, 'string': select+'*@%'+papka+'*@%'+this_cid },
	    beforeSend: function(){ $('#izmenapapka'+id).html(' <img src=images/loading.gif> Загружаю...'); },
	    success: function(data){ $('#izmenapapka'+id).html(data); }
	});
}
function delpapka(id) {
	$.ajax({ url: 'ad/ad-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delpapka', 'id': id },
	    beforeSend: function(){ $('#cid'+id).hide(); }
	});
}
/* Текущая дата и время */
function getDateNow(){
	var now = new Date(),
	tim = ((now.getHours()<10)?"0":"")+now.getHours()+":"+((now.getMinutes()<10)?"0":"")+now.getMinutes(),
	e = now.getDate(),
	month = ['', 'января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'],
	d = tim + ' - ' + e + ' ',
	nmonths = month[ (now.getMonth() + 1)];
	d += nmonths;
	return d;
}

function show_animate(obj) {
	with (document.getElementById(obj).style) {
	   if (display == "none") { 
		   $(document.getElementById(obj)).show();  
		   $(".nothing").fadeTo('slow', 0.4); 
		} else {
		   $(document.getElementById(obj)).hide();  
		   $(".nothing").fadeTo('slow', 1); 
		}
	}
}
