/* JS для админ-панели — подписать все функции! */

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
		if (val[1] == '') val[1] = 'без имени';
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
function sho(pid,name,admintip,act) {
	if (act == 1) active = icon('red small','Q')+' Выключить страницу</a>';
	else active = icon('green small','Q')+' Включить страницу</a>';
	if (document.getElementById('pid'+pid).innerHTML=='') document.getElementById('pid'+pid).innerHTML = '<span class=sho_page> <a href=-'+name+'_page_'+pid+' target=_blank title="Открыть страницу на сайте" class="punkt">'+icon('blue small','s')+' Открыть страницу на сайте</a> <a target=_blank href="sys.php?op='+admintip+'_edit_page&pid='+pid+'#1" title="Редактировать страницу в Редакторе" class="punkt">'+icon('orange small','7')+' Редактировать страницу</a> <a onclick=offpage('+pid+',0) class="punkt" title="Включение/Выключение страницы">'+active+' <a onclick=replace("'+pid+'") class="punkt" title="Копировать/Переместить/Создать ярлык">'+icon('blue small','^')+' Копировать/Переместить/Создать ярлык</a> <a onclick=delpage("'+pid+'") class="punkt" title="Удалить страницу">'+icon('red small','T')+' Удалить страницу в Удаленное</a></span>';
	else document.getElementById('pid'+pid).innerHTML = '';
}
function papka_show(cid, name, sort, id, xxx) {
	show('podpapka'+cid);
	if (document.getElementById('papka'+cid).innerHTML=='') { 
		document.getElementById('papka'+cid).innerHTML = '&nbsp; <a target=_blank href=-'+name+'_cat_'+cid+' title="Посмотреть (открыть эту папку на сайте)">'+icon('blue small','s')+'</a> <a target=_blank href="sys.php?op=edit_base_pages_category&cid='+cid+'#1" title="Изменить папку в Редакторе">'+icon('orange small','7')+'</a> <a target=_blank href="sys.php?op=edit_base_pages_category&cid='+cid+'&red=1#1" title="Изменить папку в HTML">'+icon('black small','7')+'</a>&nbsp;&nbsp;&nbsp;<a onclick=delpapka("'+cid+'") style="cursor:pointer;" title="Удалить папку">'+icon('red small','F')+'</a>';
		papka(cid, sort, id,xxx);
	} else {
		document.getElementById('papka'+cid).innerHTML = '';
		document.getElementById('podpapka'+cid).innerHTML = '';
	}
}
function papka(cid, sort, id, xxx) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'papka', 'id': id, 'string': cid+'*@%'+sort},
	    beforeSend: function(){ $('#podpapka'+cid).html('<br><img src=images/loading.gif> Загрузка страниц и подпапок...'); },
	    success: function(data){ $('#podpapka'+cid).html(data); }
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
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'comm_otvet', 'id': id, 'type': type, 'string': sender+'*@%'+info+'*@%'+mail+'*@%'+mod},
	    beforeSend: function(){ $('#otvet_send'+id).html('<img src=images/loading.gif> Отправляю ответ...'); },
	    success: function(data){ $('#otvet_send'+id).html('<span class=h1>'+icon('green medium','c')+data+'</span>'); }
	});
}
function trash_pics() {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'trash_pics'},
	    beforeSend: function(){ $('#show_options_oldfotos').html('<br><img src=images/loading.gif> Загружаю список фотографий...'); },
	    success: function(data){ $('#show_options_oldfotos').html(data); }
	});
}
function replace(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'replace', 'id': id},
	    beforeSend: function(){ $('#pid'+id).html('<br><img src=images/loading.gif> Загрузка функций копирования/перемещения и создания ярлыков...'); },
	    success: function(data){ $('#pid'+id).html(data); }
	});
}
function select_button(id) {
	$('.dark_pole2sel').attr('class', 'dark_pole2');
	$('#mainrazdel' + id).attr('class', 'dark_pole2sel');
}
function options_show(id,type) {
	select_button(id);
	$('.show_pole').hide();
	$('#'+type).show();
}
function oformlenie_show(title,id,type,link) {
	var txt;
	select_button(id);
	if (id!=2) txt = '<a class="button green" style="margin-top:4px;margin-bottom:10px;" target="_blank" href='+link+'#1> '+icon('white small','+')+' Добавить '+title+'</a> ';
	else txt = '<span class=h2>Удаленное оформление</span><p>При удалении объекта оформления, он попадает в «Удаленное оформление» для окончательного удаления или восстановления.<p>'+icon('red small','F')+' <a href=sys.php?op=delete_all&del=design>Очистить Удаленное</a>';
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'oformlenie_show', 'type': type },
	    beforeSend: function(){ $('#podrazdel').html( txt + '<br><img src=images/loading.gif> Загрузка...' ); },
	    success: function(data){ $('#podrazdel').html( txt + data ); }
	});
}
function razdel_show(title,id,name,type,xxx,sort) {
	var txt;
	select_button(id);
	xxx = Math.floor( Math.random() * (100000 - 9) ) + 10;
	colors = '<a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=0" title="Без цветовой маркировки">'+icon('gray small',',')+'</a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=1" title="Раздел часто используется">'+icon('lightgreen small',',')+'</a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=2" title="Раздел редко используется">'+icon('lightyellow small',',')+'</a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=3" title="Раздел не используется">'+icon('lightred small',',')+'</a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=4" title="Новый раздел, в разработке">'+icon('lightblue small',',')+'</a>';
	if (id == 0) txt = '<h1>'+title+'</h1>';
	else {
		txt = '<div class=pt10><nobr><ul class="button-bar"><li><a target=_blank class=blue href=-'+name+' title="Открыть этот раздел на сайте">'+icon('blue small','s')+' Открыть</a></li><li><a href="sys.php?op=mainpage&amp;type=2&id='+id+'#1" title="Редактировать Главную страницу этого раздела и Шаблон для заполнения страниц">'+icon('orange medium','7')+' Редактировать</a></li><li><a href="sys.php?op=mainpage&id='+id+'&amp;type=2&nastroi=1#1" title="Настройки (опции) раздела">'+icon('orange medium','V')+' Настроить</a></li><li><a class=pointer onclick=show("del_raz'+id+'") title="Удалить этот раздел">'+icon('red small','F')+'</a></li></ul></nobr><div id=del_raz'+id+' style="display:none"><br><br><div class=block><h1>Вы хотите удалить этот раздел и всё его содержимое?</h1><a onclick=\'delrazdel("'+id+'");show("del_raz'+id+'");show("mainrazdel'+id+'");razdel_show("Раздел удалён", 0)\' title="Удалить этот раздел" class="button red white">'+icon('white medium','F')+' Удалить</a> <a onclick=show("del_raz'+id+'") title="Не удалять" class="ml50 button green">НЕ удалять</a></div></div></div>'; 
		if (type == 'pages') {
			txt = txt+'<div class="mt5 mb20"><nobr><a class="button green" title="Добавить страницу (в редакторе)" target=_blank href="sys.php?op=base_pages_add_page&name='+name+'#1">'+icon('white small','+')+' Добавить страницу</a><a onclick="add_papka(\''+id+'\',1)" class="button small green" title="Добавить несколько страниц">цы</a> <a onclick="add_papka(\''+id+'\',0)" class="button small green">'+icon('yellow small',',')+' Создать папку</a> ' + colors + '</nobr> <div id="add_papka" style="display:none;"></div></div>';
		} else if (type == 'database') {
			txt = txt+'<div class="mt5 mb20"><nobr><a class="button green" href="sys.php?op=base_base_create_base&base='+name+'&name='+name+'&amp;red=1#1" title="Добавить строку в базу данных">'+icon('white small','+')+' Добавить строку</a> <a class="button blue" href="sys.php?op=base_base&name='+name+'" title="Открыть базу данных">'+icon('white small','s')+' Открыть таблицу</a> ' + colors + '</nobr></div>';
		} else txt = txt+'<div style="margin-top: 5px; margin-bottom: 20px;"><nobr>' + colors + '</nobr></div>';
		if (type == 'page') txt = txt+'Раздел не содержит блока отображения страниц — [содержание], поэтому сам является страницей.<br>Для изменения содержания раздела нажмите кнопку Редактировать.<br>Для размещения в разделе страниц и/или папок — добавьте в содержание раздела блок [содержание]';
	}
	document.getElementById('podrazdel').innerHTML = txt;
	if (type == 'pages') { razdel(id, sort, xxx, txt); }
}
function icon(classes,data) {
	return '<span class="icon '+classes+'" data-icon="'+data+'" style="display: inline-block; "><span aria-hidden="true">'+data+'</span></span>';
}
function razdel(id, sort, re, txt) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'razdel', 'id': id, 'string': re+'*@%'+sort},
	    beforeSend: function(){ $('#podrazdel').html('<br><img src=images/loading.gif> Загрузка страниц и папок раздела...'); },
	    success: function(data){ $('#podrazdel').html(txt + data + '<hr>'); }
	});
}
function add_papka(id,pages) {
	if (pages==0) pages = 'add_papka'; else pages = 'add_pages';
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': pages, 'id': id},
	    beforeSend: function(){ $('#add_papka').toggle(); $('#add_papka').html('<br><img src=images/loading.gif> Загрузка списка папок раздела...'); },
	    success: function(data){ $('#add_papka').html(data); }
	});
}
function save_papka(id,title,parent,name,pages) {
	if (pages==0) pages = 'addpapka'; else pages = 'addpages';
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': pages, 'id': id, 'string': title+'*@%'+parent },
	    success: function(data){ razdel_show(data,id,name,'pages',(Math.floor( Math.random() * (100000 - 9) ) + 10)); }
	});
}
function offpage(id,on) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'offpage', 'id': id },
	    success: function(data){
	    	if (on == 1) $('#1page'+id).html('<td colspan=4 class="notice success"><h2 class=center>Страница включена</h2></td>');
	    	else $('#page'+id).html(data);
	     }
	});
}
function openbox(id,name) {
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
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'opengarbage', 'id': id},
	    beforeSend: function(){ $('#podrazdel').html('<h1><img src=images/loading.gif> Загружаю...</h1>'); },
	    success: function(data){ $('#podrazdel').html('<h1>'+name+'</h1>'+add+data); $('#podrazdel').show(); }
	});
}
function delslovo(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delslovo', 'id': id},
	    beforeSend: function(){ $('#s_'+id).html(' <img src=images/loading.gif> Удаляю...'); },
	    success: function(data){ $('#s_'+id).html(' <b class=green>Удалено</b>'); }
	});
}
function delpage(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delpage', 'id': id}
	});
	$('#page'+id).hide();
	$('#1page'+id).hide();
}
function deletepage(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'deletepage', 'id': id}
	});
	$('#delpage'+id).hide();
	$('#backuppage'+id).hide();
}
function resetpage(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'resetpage', 'id': id }
	});
	$('#delpage'+id).html('<td colspan=3 class="notice success"><h2 class=center>Страница успешно восстановлена</h2></td>');
	$('#backuppage'+id).html('<td colspan=3 class="notice success"><h2 class=center>Оригинал страницы заменен на резервную копию</h2></td>');
}
function delcomm(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delcomm', 'id': id },
	    beforeSend: function(){ $('#1comm'+id).hide(); $('#comm'+id).hide(); },
	});
}
function del_file(file, id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delfile', 'type': file },
	    beforeSend: function(){ $('#file'+id).html('<img src=images/loading.gif> Удаляю...'); },
	    success: function(data){ $('#file'+id).hide(); }
	});
}
function delblock(id,type) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delblock', 'id': id, 'type': type },
	    beforeSend: function(){ $('#block_'+id).hide(); },
	});
}
function offblock(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'offblock', 'id': id },
	    success: function(data){ $('#block_'+id).html(data); }
	});
}
function offcomm(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'offcomm', 'id': id },
	    success: function(data){ $('#1comm'+id).html(data); $('#comm'+id).hide(); }
	});
}
function delrazdel(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delrazdel', 'id': id }
	});
}
function rep(id,type,razdel,papka) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'rep', 'type': type, 'id': id, 'string': papka+'*@%'+razdel },
	    beforeSend: function(){ $('#rep'+id).html('<img src=images/loading.gif> Секундочку...'); },
	    success: function(data){ $('#rep'+id).html(data); if (type == 3) $('#page'+id).hide('slow'); }
	});
}
function clo(pid) {
	document.getElementById('pid'+pid).innerHTML = '';
}
function izmenapapka(select,papka,razdel,id,type) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'izmenapapka', 'id': id, 'type': type, 'string': select+'*@%'+papka+'*@%'+razdel },
	    beforeSend: function(){ $('#izmenapapka'+id).html('<img src=images/loading.gif> Загружаю...'); },
	    success: function(data){ $('#izmenapapka'+id).html(data); }
	});
}
function delpapka(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
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
