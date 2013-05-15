/* JS для админ-панели — нужно подписать все функции! */
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
	if (act == 1) active = '<img class="icon2 i43" src=/images/1.gif> Выключить страницу</a>';
	else active = '<img class="icon2 i44" src=/images/1.gif> Включить страницу</a>';
	if (document.getElementById('pid'+pid).innerHTML=='') document.getElementById('pid'+pid).innerHTML = '<span class=sho_page> <a href=-'+name+'_page_'+pid+' target=_blank title="Открыть страницу на сайте" class="punkt"><img class="icon2 i36" src="/images/1.gif"> Открыть страницу на сайте</a> <a target=_blank href="sys.php?op='+admintip+'_edit_page&pid='+pid+'#1" title="Редактировать страницу в Редакторе" class="punkt"><img class="icon2 i35" src=/images/1.gif> Редактировать страницу</a> <a onclick=offpage('+pid+',0) class="punkt" title="Включение/Выключение страницы">'+active+' <a onclick=replace("'+pid+'") class="punkt" title="Копировать/Переместить/Создать ярлык"><img class="icon2 i32" src=/images/1.gif> Копировать/Переместить/Создать ярлык</a> <a onclick=delpage("'+pid+'") class="punkt" title="Удалить страницу"><img class="icon2 i33" src=/images/1.gif> Удалить страницу в Корзину</a></span>';
	else document.getElementById('pid'+pid).innerHTML = '';
}
function papka_show(cid, name, sort, id, xxx) {
	show('podpapka'+cid);
	if (document.getElementById('papka'+cid).innerHTML=='') { 
		document.getElementById('papka'+cid).innerHTML = '&nbsp; <a target=_blank href=-'+name+'_cat_'+cid+' title="Посмотреть (открыть эту папку на сайте)"><img class="icon2 i36" src="/images/1.gif"></a> <a target=_blank href="sys.php?op=edit_base_pages_category&cid='+cid+'#1" title="Изменить папку в Редакторе"><img class="icon2 i35" src=/images/1.gif></a> <a target=_blank href="sys.php?op=edit_base_pages_category&cid='+cid+'&red=1#1" title="Изменить папку в HTML"><img class="icon2 i34" src=/images/1.gif></a>&nbsp;&nbsp;&nbsp;<a onclick=delpapka("'+cid+'") style="cursor:pointer;" title="Удалить папку"><img class="icon2 i33" src=/images/1.gif></a>';
		papka(cid, sort, id,xxx);
	} else {
		document.getElementById('papka'+cid).innerHTML = '';
		document.getElementById('podpapka'+cid).innerHTML = '';
	}
}
function papka(cid, sort, id, xxx) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'papka', 'id': id, 'string': cid+'*@%'+sort},
	    beforeSend: function(){ $('#podpapka'+cid).html('<br>Загрузка страниц и подпапок...'); },
	    success: function(data){ $('#podpapka'+cid).html(data); }
	});
}
function show_otvet_comm(cid, name, mail, mod) {
	if (document.getElementById('otvet_comm'+cid).innerHTML =='') {
		document.getElementById('otvet_comm'+cid).innerHTML = '<input type=text id="otvet_comm_sender'+cid+'" value="Администратор" size=15 style="width:100%;"><br><textarea style="width:100%;height:150px;" id="otvet_comm_txt'+cid+'"></textarea><dt><a onclick=\'new_otvet(0, '+cid+', document.getElementById("otvet_comm_sender'+cid+'").value, document.getElementById("otvet_comm_txt'+cid+'").value, "'+mail+'", "'+mod+'")\' class=punkt><img class="icon2 i11" src=/images/1.gif align=left><strong>Отправить ответ</strong></a> (по email и через сайт)';
		if (mail != '') document.getElementById('otvet_comm'+cid).innerHTML += '<br><a onclick=\'new_otvet(1, '+cid+', document.getElementById("otvet_comm_sender'+cid+'").value, document.getElementById("otvet_comm_txt'+cid+'").value, "'+mail+'", "'+mod+'")\' class=punkt><img class="icon2 i2" src=/images/1.gif align=left>Ответить по email</a> (без комментария на сайте)<br><br><a onclick=\'new_otvet(2, '+cid+', document.getElementById("otvet_comm_sender'+cid+'").value, document.getElementById("otvet_comm_txt'+cid+'").value, "'+mail+'", "'+mod+'")\' class=punkt><img class="icon2 i10" src=/images/1.gif align=left>Ответить через сайт</a> (без уведомления на email)<br>';
		document.getElementById('otvet_comm_txt'+cid).focus(); 
		document.getElementById('otvet_comm_txt'+cid).value = name+', '; 
	} else document.getElementById('otvet_comm'+cid).innerHTML = '';
}
function new_otvet(type, id, sender, info, mail, mod) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'comm_otvet', 'id': id, 'type': type, 'string': sender+'*@%'+info+'*@%'+mail+'*@%'+mod},
	    beforeSend: function(){ $('#otvet_comm'+id).html('<b>Отправляю ответ...</b>'); },
	    success: function(data){ $('#otvet_comm'+id).html(data); }
	});
}
function trash_pics() {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'trash_pics'},
	    beforeSend: function(){ $('#show_options_oldfotos').html('<br>Загружаю список фотографий...'); },
	    success: function(data){ $('#show_options_oldfotos').html(data); }
	});
}
function replace(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'replace', 'id': id},
	    beforeSend: function(){ $('#pid'+id).html('<br>Загрузка функций копирования/перемещения и создания ярлыков...'); },
	    success: function(data){ $('#pid'+id).html(data); }
	});
}
function men3(id, name, admintip) {
	if (document.getElementById('id'+id).innerHTML=='') document.getElementById('id'+id).innerHTML = '<a href="sys.php?op=edit_'+admintip+'&id='+id+'" title="Изменить элемент списка в Редакторе"><img src=images/admin/spisok_editor.gif></a> <a href="sys.php?op=edit_'+admintip+'&id='+id+'&red=1" title="Изменить элемент списка в HTML)"><img src=images/admin/spisok_noeditor.gif></a> &nbsp; <a href="sys.php?op='+admintip+'_del&name='+name+'&id='+id+'" title="Удалить элемент списка"><img src=images/admin/spisok_delete.gif></a>';
	else document.getElementById('id'+id).innerHTML = '';
}
function add_pole(minus, id) {
	if (minus==false) document.getElementById('id0').innerHTML += '<div id=div'+id+'><table width=100% border=0 cellspacing=0 cellpadding=5><tr><td width=18%><input type=text name=pole_name['+id+'] size=15 style="width:100%;" /></td><td width=20%><input type=text name=pole_rusname['+id+'] size=15 style="width:100%;" /></td><td width=10%><label><select name=pole_tip['+id+'] style="width:100%;"><option value="строка" selected=selected>Строка (до 250 букв)</option><option value="строкабезвариантов">Строка без выбора вариантов</option><option value="число">Число</option><option value="список">Список</option><option value="текст">Текст</option><option value="дата">Дата</option><option value="датавремя">Дата-Время</option><option value="фото">Фото</option><option value="минифото">МиниФото</option><option value="файл">Файл</option><option value="ссылка">Ссылка</option></select></label></td><td width=15%><label><select name=pole_main['+id+'] style="width:100%;"><option value=0>не важно</option><option value=1>основная категория</option><option value=2>вторичная категория</option><option value=3>обязательно заполнять</option><option value=4>не важно и не печатать</option><option value=6>не важно, не печатать и не показывать</option><option value=7>обязательно, не печатать и не показывать</option><option value=5>пустая для печати</option></select></label></td><td width=15%><label><select name=pole_open['+id+'] style="width:100%;"><option value=0 selected=selected>видно везде</option><option value=1>не видно нигде</option><option value=2>видно только на странице</option><option value=3>видно только по паролю</option></select></label></td><td width=20%><label><input type=text name=pole_rename['+id+'] size=15 style="width:100%;" /></label></td><td></td></tr></table></div>';
	else document.getElementById('id'+id).innerHTML = '';
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
	txt = '<a class="button" style="margin-top:4px;margin-bottom:10px;" title="Добавить '+title+'" target=_blank href='+link+'#1><img class="icon2 i39" src=/images/1.gif>Добавить '+title+'</a><br>';
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'oformlenie_show', 'type': type },
	    beforeSend: function(){ $('#podrazdel').html( txt + '<br>Загрузка...' ); },
	    success: function(data){ $('#podrazdel').html( txt + data ); }
	});
}
function razdel_show(title,id,name,type,xxx,sort) {
	var txt;
	select_button(id);
	xxx = Math.floor( Math.random() * (100000 - 9) ) + 10;
	if (id == 0) txt = '<h1>'+title+'</h1>';
	else {
		txt = '<div class=pt10><nobr><ul class="button-bar"><li><a target=_blank class=blue href=-'+name+' title="Открыть этот раздел на сайте"><img class="icon2 i36" src="/images/1.gif">Открыть</a></li><li><a href="sys.php?op=mainpage&amp;type=2&id='+id+'#1" title="Редактировать Главную страницу этого раздела и Шаблон для заполнения страниц"><img class="icon2 i35" src=/images/1.gif>Редактировать</a></li><li><a href="sys.php?op=mainpage&id='+id+'&amp;type=2&nastroi=1#1" title="Настройки (опции) раздела"><img class="icon2 i38" src=/images/1.gif class=left>Настроить</a></li><li><a onclick=show("del_raz'+id+'") title="Удалить этот раздел"><img class="icon2 i33" src=/images/1.gif></a></li></ul></nobr><div id=del_raz'+id+' style="display:none"><br><br><div class=block><h1>Вы хотите удалить этот раздел и всё его содержимое?</h1><a onclick=\'delrazdel("'+id+'");show("del_raz'+id+'");show("mainrazdel'+id+'");razdel_show("Раздел удалён", 0)\' title="Удалить этот раздел" class="button red white">Удалить</a> <a onclick=show("del_raz'+id+'") title="Не удалять" class="ml50 button green">НЕ удалять</a></div></div></div>'; 
		if (type == 'pages') {
			txt = txt+'<div class="mt5 mb20"><nobr><a class="button green" title="Добавить страницу (в редакторе)" target=_blank href="sys.php?op=base_pages_add_page&name='+name+'#1"><img class="icon2 i39" src=/images/1.gif>Добавить страницу</a><a onclick="add_papka(\''+id+'\',1)" class="button small green" title="Добавить несколько страниц">цы</a> <a onclick="add_papka(\''+id+'\',0)" class="button small green"><img class="icon2 i40" src=/images/1.gif>Создать папку</a> <a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=0" title="Без цветовой маркировки"><div class="but_color radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=1" title="Раздел часто используется"><div class="but_color2 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=2" title="Раздел редко используется"><div class="but_color3 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=3" title="Раздел не используется"><div class="but_color4 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=4" title="Новый раздел, в разработке"><div class="but_color5 radius"><img class=icon3 src=/images/1.gif></div></a></nobr> <div id="add_papka" style="display:none;"></div></div>';
		} else txt = txt+'<div style="margin-top: 5px; margin-bottom: 20px;"><nobr><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=0" title="Без цветовой маркировки"><div class="but_color radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=1" title="Раздел часто используется"><div class="but_color2 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=2" title="Раздел редко используется"><div class="but_color3 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=3" title="Раздел не используется"><div class="but_color4 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=4" title="Новый раздел, в разработке"><div class="but_color5 radius"><img class=icon3 src=/images/1.gif></div></a></nobr></div>';
		if (type == 'page') txt = txt+'Раздел не содержит блока отображения страниц — [содержание], поэтому сам является страницей.<br>Для изменения содержания раздела нажмите кнопку Редактировать.<br>Для размещения в разделе страниц и/или папок — добавьте в содержание раздела блок [содержание]';
	}
	document.getElementById('podrazdel').innerHTML = txt;
	if (type == 'pages') { razdel(id, sort, xxx, txt); }
}
function razdel(id, sort, re, txt) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'razdel', 'id': id, 'string': re+'*@%'+sort},
	    beforeSend: function(){ $('#podrazdel').html('<br>Загрузка страниц и папок раздела...'); },
	    success: function(data){ $('#podrazdel').html(txt + data + '<hr>'); }
	});
}
function add_papka(id,pages) {
	if (pages==0) pages = 'add_papka'; else pages = 'add_pages';
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': pages, 'id': id},
	    beforeSend: function(){ $('#add_papka').toggle(); $('#add_papka').html('<br>Загрузка списка папок раздела...'); },
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
	if (name == 'Комментарии') add = '<span style="margin-left: 20px;"><img class="icon2 i21" src=/images/1.gif>Удалить: <a href="sys.php?op=delete_noactive_comm" class="gray nothing"> отключенные</a>, <a href="sys.php?op=delete_system_comm" class="gray nothing">системные</a> <a href="sys.php?op=base_comments" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i36" src=/images/1.gif class=left>Открыть все</a> <nobr><span style="margin-left: 20px;"><img class="icon2 i11" src="/images/1.gif" valign=bottom>Показать: <a class="nothing punkt" onclick="openbox(\'6\',\'Отключенные комментарии\');">отключенные</a>, <a class="nothing punkt" onclick="openbox(\'7\',\'Комментарии без ответов\');">без ответов</a></span></nobr><br><br>';
	if (name == 'Комментарии без ответов') add = '<a href="sys.php?op=delete_noactive_comm" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i21" src=/images/1.gif>Удалить отключенные</a> <a href="sys.php?op=base_comments" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i36" src=/images/1.gif class=left>Открыть все</a> <nobr><span style="margin-left: 20px;"><img class="icon2 i11" src="/images/1.gif" valign=bottom>Показать: <a class="nothing punkt" onclick="openbox(\'6\',\'Отключенные комментарии\');">отключенные</a>, <b>без ответов</b></span></nobr><br><br>';
	if (name == 'Отключенные комментарии') add = '<a href="sys.php?op=delete_noactive_comm" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i21" src=/images/1.gif>Удалить отключенные</a> <a href="sys.php?op=base_comments" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i36" src=/images/1.gif class=left>Открыть все</a> <nobr><span style="margin-left: 20px;"><img class="icon2 i11" src="/images/1.gif" valign=bottom>Показать: <b>отключенные</b>, <a class="nothing punkt" onclick="openbox(\'7\',\'Комментарии без ответов\');">без ответов</a></span></nobr><br><br>';
	if (name == 'Корзина') add = 'При удалении страниц, они попадают в Корзину для окончательного удаления или восстановления.<br><a href="sys.php?op=delete_all_pages&del=del" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i21" src=/images/1.gif>Очистить Корзину</a><br><br>';
	if (name == 'Резервные копии') add = 'При редактировании страниц создаются их копии для восстановления при необходимости. Внимание: восстанавливая предыдущий вариант страницы, вы заменяете новый!<br><a href="sys.php?op=delete_all_pages&del=backup" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i21" src=/images/1.gif>Удалить все копии</a><br><br>';
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
	    beforeSend: function(){ $('#s_'+id).html('Удаляем...'); },
	    success: function(data){ $('#s_'+id).html('<b class=green>удалено</b>'); }
	});
}
function delpage(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delpage', 'id': id}
	});
	$('#page'+id).hide();
	$('#1page'+id).hide();
	//$('#2page'+id).hide();
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
	    beforeSend: function(){ $('#file'+id).html('Удаляю...'); },
	    success: function(data){ $('#file'+id).hide(); }
	});
}
function delblock(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delblock', 'id': id },
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
	    beforeSend: function(){ $('#rep'+id).html('Секундочку...'); },
	    success: function(data){ $('#rep'+id).html(data); }
	});
}
function clo(pid) {
	document.getElementById('pid'+pid).innerHTML = '';
}
function izmenapapka(select,papka,razdel,id,type) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'izmenapapka', 'id': id, 'type': type, 'string': select+'*@%'+papka+'*@%'+razdel },
	    beforeSend: function(){ $('#izmenapapka'+id).html('Загружаю...'); },
	    success: function(data){ $('#izmenapapka'+id).html(data); }
	});
}

function delpapka(id) {
	$.ajax({ url: 'a-ajax.php', cache: false, dataType : "html",
	    data: {'func': 'delpapka', 'id': id },
	    beforeSend: function(){ $('#cid'+id).hide(); }
	});
}