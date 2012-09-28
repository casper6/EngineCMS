/* JS для админ-панели — нужно подписать все функции! */
function sho(pid,name,admintip,act) {
	if (act == 1) active = '<img class="icon2 i43" src=/images/1.gif> Выключить страницу</a>';
	else active = '<img class="icon2 i44" src=/images/1.gif> Включить страницу</a>';
	if (document.getElementById('pid'+pid).innerHTML=='') document.getElementById('pid'+pid).innerHTML = '<span class=sho_page> <a href=-'+name+'_page_'+pid+' target=_blank title="Открыть страницу на сайте" class="punkt"><img class="icon2 i36" src="/images/1.gif"> Открыть страницу на сайте</a> <a target=_blank href="sys.php?op='+admintip+'_edit_page&pid='+pid+'" title="Редактировать страницу в Редакторе" class="punkt"><img class="icon2 i35" src=/images/1.gif> Редактировать страницу</a> <a onclick=offpage("'+pid+'") class="punkt" title="Включение/Выключение страницы">'+active+' <a onclick=replace("'+pid+'") class="punkt" title="Копировать/Переместить/Создать ярлык"><img class="icon2 i32" src=/images/1.gif> Копировать/Переместить/Создать ярлык</a> <a onclick=delpage("'+pid+'") class="punkt" title="Удалить страницу"><img class="icon2 i33" src=/images/1.gif> Удалить страницу в Корзину</a></span>';
	else document.getElementById('pid'+pid).innerHTML = '';
}
function men10(pid,name,admintip) {
	if (document.getElementById('pid10'+pid).innerHTML=='') document.getElementById('pid10'+pid).innerHTML = '&nbsp; <a href=-'+name+'_page_'+pid+' target=_blank title="Посмотреть (открыть эту страницу на сайте)"><img src=images/admin/page_show.gif></a><a href="sys.php?op='+admintip+'_edit_page&pid='+pid+'" title="Изменить страницу в Редакторе"><img src=images/admin/page_editor.gif></a><a href="sys.php?op='+admintip+'_edit_page&pid='+pid+'&red=1" title="Изменить страницу в HTML"><img src=images/admin/page_noeditor.gif></a>&nbsp; &nbsp;<a href="sys.php?op='+admintip+'_status_page&pid='+pid+'&name='+name+'" title="Включение/Выключение страницы"><img src=images/admin/page_zamok.gif></a>&nbsp; &nbsp;<a href="sys.php?op='+admintip+'_delit_page&name='+name+'&pid='+pid+'" title="Удалить страницу c подтверждением"><img src=images/admin/page_delete.gif></a>&nbsp;<a href="sys.php?op='+admintip+'_delit_page&name='+name+'&pid='+pid+'&ok=1" title="Удалить страницу"><img src=images/admin/page_delete_moment.gif></a>';
	else document.getElementById('pid10'+pid).innerHTML = '';
}
function papka_show(cid, name, sort, id, xxx) {
	show('podpapka'+cid);
	if (document.getElementById('papka'+cid).innerHTML=='') { 
		document.getElementById('papka'+cid).innerHTML = '&nbsp; <a target=_blank href=-'+name+'_cat_'+cid+' title="Посмотреть (открыть эту папку на сайте)"><img class="icon2 i36" src="/images/1.gif"></a> <a target=_blank href="sys.php?op=edit_base_pages_category&cid='+cid+'" title="Изменить папку в Редакторе"><img class="icon2 i35" src=/images/1.gif></a> <a target=_blank href="sys.php?op=edit_base_pages_category&cid='+cid+'&red=1" title="Изменить папку в HTML"><img class="icon2 i34" src=/images/1.gif></a>&nbsp;&nbsp;&nbsp;<a onclick=delpapka("'+cid+'") style="cursor:pointer;" title="Удалить папку"><img class="icon2 i33" src=/images/1.gif></a>';
		papka(cid, sort, id,xxx);
	} else {
		document.getElementById('papka'+cid).innerHTML = '';
		document.getElementById('podpapka'+cid).innerHTML = '';
	}
}
function papka(pap, sort, id, xxx) {
	document.getElementById('podpapka'+pap).innerHTML = '<br>Загрузка страниц и подпапок...';
	JsHttpRequest.query('ad-ajax.php', {'raz': id, 'papka': pap, 'sort': sort}, function(result, errors) {if (result) {document.getElementById('podpapka'+pap).innerHTML = result['raz']; }},false);
}
function show_otvet_comm(cid, name, mail, mod) {
	if (document.getElementById('otvet_comm'+cid).innerHTML =='') {
		document.getElementById('otvet_comm'+cid).innerHTML = '<input type=text id="otvet_comm_sender'+cid+'" value="Администратор" size=15 style="width:100%;"><br><textarea style="width:100%;height:150px;" id="otvet_comm_txt'+cid+'"></textarea><dt><a onclick=\'new_otvet(0, '+cid+', document.getElementById("otvet_comm_sender'+cid+'").value, document.getElementById("otvet_comm_txt'+cid+'").value, "'+mail+'", "'+mod+'")\' class=punkt><img class="icon2 i11" src=/images/1.gif align=left><strong>Отправить ответ</strong></a>';
		if (mail != '') document.getElementById('otvet_comm'+cid).innerHTML += '<br><dt><a onclick=\'new_otvet(1, '+cid+', document.getElementById("otvet_comm_sender'+cid+'").value, document.getElementById("otvet_comm_txt'+cid+'").value, "'+mail+'", "'+mod+'")\' class=punkt><img class="icon2 i2" src=/images/1.gif align=left>Ответить по e-mail</a> (без комментария на сайте)<dt><a onclick=\'new_otvet(2, '+cid+', document.getElementById("otvet_comm_sender'+cid+'").value, document.getElementById("otvet_comm_txt'+cid+'").value, "'+mail+'", "'+mod+'")\' class=punkt><img class="icon2 i10" src=/images/1.gif align=left>Ответить через сайт</a> (без уведомления на e-mail)<br>';
		document.getElementById('otvet_comm_txt'+cid).focus(); 
		document.getElementById('otvet_comm_txt'+cid).value = name+', '; 
	} else document.getElementById('otvet_comm'+cid).innerHTML = '';
	
}
function new_otvet(type, cid, sender, info, mail, mod) {
	document.getElementById('otvet_comm'+cid).innerHTML = '<b>Отправляю ответ...</b>';
	var comm_otvet = 'comm_otvet';
	JsHttpRequest.query('ad-ajax.php', {'func': 'comm_otvet', 'comm_cid': cid, 'comm_type': type, 'comm_sender': sender, 'comm_otvet': info, 'comm_mail': mail, 'comm_mod': mod}, function(result, errors) {if (result) {document.getElementById('otvet_comm'+cid).innerHTML = result['func']; }},false);
}
function trash_pics() {
	document.getElementById('show_options2').innerHTML = '<br><b>Загружаю список фотографий...</b>';
	var comm_otvet = 'comm_otvet';
	JsHttpRequest.query('ad-ajax.php', {'func': 'trash_pics'}, function(result, errors) {if (result) {document.getElementById('show_options2').innerHTML = result['func']; }},false);
}
function replace(pid) {
	document.getElementById('pid'+pid).innerHTML = '<br>Загрузка функций копирования/перемещения и создания ярлыков...';
	JsHttpRequest.query('ad-ajax.php', {'replace': pid}, function(result, errors) {if (result) {document.getElementById('pid'+pid).innerHTML = result['replace']; }},false);
}
function men3(id, name, admintip) {
	if (document.getElementById('id'+id).innerHTML=='') document.getElementById('id'+id).innerHTML = '<a href="sys.php?op=edit_'+admintip+'&id='+id+'" title="Изменить элемент списка в Редакторе"><img src=images/admin/spisok_editor.gif></a> <a href="sys.php?op=edit_'+admintip+'&id='+id+'&red=1" title="Изменить элемент списка в HTML)"><img src=images/admin/spisok_noeditor.gif></a> &nbsp; <a href="sys.php?op='+admintip+'_del&name='+name+'&id='+id+'" title="Удалить элемент списка"><img src=images/admin/spisok_delete.gif></a>';
	else document.getElementById('id'+id).innerHTML = '';
}
function add_pole(minus, id) {
	if (minus==false) document.getElementById('id0').innerHTML += '<div id=div'+id+'><table width=100% border=0 cellspacing=0 cellpadding=5><tr><td width=18%><input type=text name=pole_name['+id+'] size=15 style="width:100%;" /></td><td width=20%><input type=text name=pole_rusname['+id+'] size=15 style="width:100%;" /></td><td width=10%><label><select name=pole_tip['+id+'] style="width:100%;"><option value="строка" selected=selected>Строка (до 250 букв)</option><option value="строкабезвариантов">Строка без выбора вариантов</option><option value="число">Число</option><option value="список">Список</option><option value="текст">Текст</option><option value="дата">Дата</option><option value="датавремя">Дата-Время</option><option value="фото">Фото</option><option value="минифото">МиниФото</option><option value="файл">Файл</option><option value="ссылка">Ссылка</option></select></label></td><td width=15%><label><select name=pole_main['+id+'] style="width:100%;"><option value=0>не важно</option><option value=1>основная категория</option><option value=2>вторичная категория</option><option value=3>обязательно заполнять</option><option value=4>не важно и не печатать</option><option value=6>не важно, не печатать и не показывать</option><option value=7>обязательно, не печатать и не показывать</option><option value=5>пустая для печати</option></select></label></td><td width=15%><label><select name=pole_open['+id+'] style="width:100%;"><option value=0 selected=selected>видно везде</option><option value=1>не видно нигде</option><option value=2>видно только на странице</option><option value=3>видно только по паролю</option></select></label></td><td width=20%><label><input type=text name=pole_rename['+id+'] size=15 style="width:100%;" /></label></td><td></td></tr></table></div>';
	else document.getElementById('id'+id).innerHTML = '';
}
function oformlenie_show(title,id,type,link) {
	var txt;
	$('.dark_pole2sel').attr('class', 'dark_pole2');
	$('#mainrazdel' + id).attr('class', 'dark_pole2sel');
	txt = '<a class="button" style="margin-top:4px;margin-bottom:10px;" title="Добавить '+title+'" target=_blank href='+link+'#1><img class="icon2 i39" src=/images/1.gif>Добавить '+title+'</a><br>';
	document.getElementById('podrazdel').innerHTML = txt+'<br>Загрузка...';
	JsHttpRequest.query('ad-ajax.php', {'func': 'oformlenie_show', 'papka': type, 'xxx': (Math.floor( Math.random() * (10000 - 9) ) + 10)}, function(result, errors) {if (result) {document.getElementById('podrazdel').innerHTML = txt + result['replace']; }},false);
}
function razdel_show(title,id,name,type,xxx,sort) {
	var txt;
	$('.dark_pole2sel').attr('class', 'dark_pole2');
	$('#mainrazdel' + id).attr('class', 'dark_pole2sel');
	xxx = Math.floor( Math.random() * (100000 - 9) ) + 10;
	if (id == 0) txt = '<h1>'+title+'</h1>';
	else {
		txt = '<div style="padding-top:10px;"><a class=button target=_blank href=-'+name+' title="Открыть этот раздел на сайте"><img class="icon2 i36" src="/images/1.gif">Открыть</a> <a class=button href="sys.php?op=mainpage&amp;type=2&id='+id+'" title="Редактировать Главную страницу этого раздела и Шаблон для заполнения страниц"><img class="icon2 i35" src=/images/1.gif>Редактировать</a> <a class=button href="sys.php?op=mainpage&id='+id+'&amp;type=2&nastroi=1" title="Настройки (опции) раздела"><img class="icon2 i38" src=/images/1.gif class=left>Настроить</a> <a class="button" onclick=show("del_raz'+id+'") style="margin-left:20px;" title="Удалить этот раздел"><img class="icon2 i33" src=/images/1.gif>Удалить</a><div id=del_raz'+id+' style="display:none"><div class=block><h1>Вы хотите удалить этот раздел и всё его содержимое?</h1><a onclick=\'delrazdel("'+id+'");show("del_raz'+id+'");razdel_show("Раздел удалён", 0)\' title="Удалить этот раздел" class="button red white">Удалить</a> <a onclick=show("del_raz'+id+'") style="margin-left:50px;" title="Не удалять" class="button green">НЕ удалять</a></div></div></div>'; 
		if (type == 'pages') {
			txt = txt+'<div style="margin-top: 5px; margin-bottom: 20px;"><a class="button" title="Добавить страницу (в редакторе)" target=_blank href="sys.php?op=base_pages_add_page&name='+name+'#1"><img class="icon2 i39" src=/images/1.gif>Добавить страницу</a> <a onclick=add_papka("'+id+'") class="button"><img class="icon2 i40" src=/images/1.gif>Создать папку</a> <nobr><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=0" title="Без цветовой маркировки"><div class="but_color radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=1" title="Раздел часто используется"><div class="but_color2 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=2" title="Раздел редко используется"><div class="but_color3 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=3" title="Раздел не используется"><div class="but_color4 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=4" title="Новый раздел, в разработке"><div class="but_color5 radius"><img class=icon3 src=/images/1.gif></div></a></nobr> <div id="add_papka" style="display:none;"></div></div>';
		} else txt = txt+'<div style="margin-top: 5px; margin-bottom: 20px;"><nobr><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=0" title="Без цветовой маркировки"><div class="but_color radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=1" title="Раздел часто используется"><div class="but_color2 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=2" title="Раздел редко используется"><div class="but_color3 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=3" title="Раздел не используется"><div class="but_color4 radius"><img class=icon3 src=/images/1.gif></div></a><a href="sys.php?op=mainpage_razdel_color&id='+id+'&color=4" title="Новый раздел, в разработке"><div class="but_color5 radius"><img class=icon3 src=/images/1.gif></div></a></nobr></div>';
	}
	document.getElementById('podrazdel').innerHTML = txt;
	if (type == 'pages') { razdel(id, sort, xxx, txt); }
}
function razdel(id, sort, xxx, txt) {
	document.getElementById('podrazdel').innerHTML += '<br>Загрузка страниц и папок раздела...';
	JsHttpRequest.query('ad-ajax.php', {'raz': id, 'sort': sort, 'xxx': xxx}, function(result, errors) {if (result) {document.getElementById('podrazdel').innerHTML = txt + result['raz'] + '<hr>'; }},false);
}
function add_papka(id) {
	show('add_papka');
	document.getElementById('add_papka').innerHTML = '<br>Загрузка списка папок раздела...';
	JsHttpRequest.query('ad-ajax.php', {'str': id}, function(result, errors) {if (result) {document.getElementById('add_papka').innerHTML = result['str']; }},false);
}
function save_papka(id,title,parent,name) {
	JsHttpRequest.query('ad-ajax.php', {'addpapka': id, 'papka': title, 'str': parent}, function(result, errors) {if (result) {
		addpapka = result['addpapka']; 
		}},false);
	razdel_show(addpapka,id,name,'pages',(Math.floor( Math.random() * (100000 - 9) ) + 10));
}
function offpage(pid) {
	JsHttpRequest.query('ad-ajax.php', {'offpage': pid, 'str': (Math.floor( Math.random() * (10000 - 9) ) + 10)}, function(result, errors) {if (result) {
		document.getElementById('page'+pid).innerHTML = result['offpage']; 
		}},false);
}
function offpage2(pid) {
	JsHttpRequest.query('ad-ajax.php', {'offpage': pid, 'str': (Math.floor( Math.random() * (10000 - 9) ) + 10)}, function(result, errors) {if (result) {
		document.getElementById('1page'+pid).innerHTML = 'Страница включена'; 
		}},false);
}
function openbox(num,name) {
	var add;
	add = '';
	if (name == 'Комментарии') add = '<span style="margin-left: 20px;"><img class="icon2 i21" src=/images/1.gif>Удалить: <a href="sys.php?op=delete_noactive_comm" class="gray nothing"> отключенные</a>, <a href="sys.php?op=delete_system_comm" class="gray nothing">системные</a> <a href="sys.php?op=base_comments" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i36" src=/images/1.gif class=left>Открыть все</a> <nobr><span style="margin-left: 20px;"><img class="icon2 i11" src="/images/1.gif" valign=bottom>Показать: <a class="nothing punkt" onclick="openbox(\'6\',\'Отключенные комментарии\');">отключенные</a>, <a class="nothing punkt" onclick="openbox(\'7\',\'Комментарии без ответов\');">без ответов</a></span></nobr><br><br>';

	if (name == 'Комментарии без ответов') add = '<a href="sys.php?op=delete_noactive_comm" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i21" src=/images/1.gif>Удалить отключенные</a> <a href="sys.php?op=base_comments" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i36" src=/images/1.gif class=left>Открыть все</a> <nobr><span style="margin-left: 20px;"><img class="icon2 i11" src="/images/1.gif" valign=bottom>Показать: <a class="nothing punkt" onclick="openbox(\'6\',\'Отключенные комментарии\');">отключенные</a>, <b>без ответов</b></span></nobr><br><br>';

	if (name == 'Отключенные комментарии') add = '<a href="sys.php?op=delete_noactive_comm" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i21" src=/images/1.gif>Удалить отключенные</a> <a href="sys.php?op=base_comments" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i36" src=/images/1.gif class=left>Открыть все</a> <nobr><span style="margin-left: 20px;"><img class="icon2 i11" src="/images/1.gif" valign=bottom>Показать: <b>отключенные</b>, <a class="nothing punkt" onclick="openbox(\'7\',\'Комментарии без ответов\');">без ответов</a></span></nobr><br><br>';

	if (name == 'Корзина') add = 'При удалении страниц, они попадают в Корзину для окончательного удаления или восстановления.<br><a href="sys.php?op=delete_all_pages&del=del" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i21" src=/images/1.gif>Очистить Корзину</a><br><br>';

	if (name == 'Резервные копии') add = 'При редактировании страниц создаются их копии для восстановления при необходимости. Внимание: восстанавливая предыдущий вариант страницы, вы заменяете новый!<br><a href="sys.php?op=delete_all_pages&del=backup" style="margin-left: 20px;" class="gray nothing"><img class="icon2 i21" src=/images/1.gif>Удалить все копии</a><br><br>';

	if (name == 'Добавленное посетителями') add = 'Эти страницы нуждаются в проверке и включении.<br><br>';

	if (name == 'Новое и отредактированное') add = 'История последних изменений<br><br>';
	document.getElementById('podrazdel').innerHTML = '<h1><img src=images/loading.gif> Загружаю...</h1>';
	JsHttpRequest.query('ad-ajax.php', {'opengarbage': num, 'str': (Math.floor( Math.random() * (10000 - 9) ) + 10)}, function(result, errors) {if (result) {document.getElementById('podrazdel').innerHTML = '<h1>'+name+'</h1>'+add+result['opengarbage']; }},false);
}
function delslovo(sid) {
	document.getElementById('s_'+sid).innerHTML = 'Удаляем...';
	JsHttpRequest.query('ad-ajax.php', {'func': 'delslovo', 'str': sid}, function(result, errors) {if (result) {document.getElementById('s_'+sid).innerHTML = result['func']; }},false);
}
function delpage(pid) {
	JsHttpRequest.query('ad-ajax.php', {'delpage': pid}, function(result, errors) {},false);
	if (document.getElementById('page'+pid)) $('#page'+pid).delay(200).fadeOut();
	if (document.getElementById('1page'+pid)) $('#1page'+pid).delay(200).fadeOut();
	if (document.getElementById('2page'+pid)) $('#2page'+pid).delay(200).fadeOut();
}
function deletepage(pid) {
	JsHttpRequest.query('ad-ajax.php', {'deletepage': pid}, function(result, errors) {},false);
	if (document.getElementById('delpage'+pid)) $('#delpage'+pid).delay(200).fadeOut();
	if (document.getElementById('backuppage'+pid)) $('#backuppage'+pid).delay(200).fadeOut();
}
function resetpage(pid) {
	JsHttpRequest.query('ad-ajax.php', {'resetpage': pid}, function(result, errors) {},false);
	if (document.getElementById('delpage'+pid)) $('#delpage'+pid).delay(200).fadeOut();
	if (document.getElementById('backuppage'+pid)) $('#backuppage'+pid).delay(200).fadeOut();
}
function delcomm(cid) {
	JsHttpRequest.query('ad-ajax.php', {'delcomm': cid}, function(result, errors) {},false);
	if (document.getElementById('1comm'+cid)) show('1comm'+cid); show('comm'+cid);
}
function del_file(file, id) {
	JsHttpRequest.query('ad-ajax.php', {'papka': file, 'func': 'delfile'}, function(result, errors) {},false);
	if (document.getElementById('file'+id)) $('#file'+id).hide();
}
function offcomm(cid) {
	JsHttpRequest.query('ad-ajax.php', {'offcomm': cid, 'str': (Math.floor( Math.random() * (10000 - 9) ) + 10)}, function(result, errors) {if (result) {document.getElementById('1comm'+cid).innerHTML = result['offcomm'];
	$('#comm'+cid).delay(1000).fadeOut();
	}},false);
}

function delrazdel(id) {
	JsHttpRequest.query('ad-ajax.php', {'delrazdel': id}, function(result, errors) {},false);
	if (document.getElementById('mainrazdel'+id)) show('mainrazdel'+id);
}
function rep(pid,what,razdel,papka) {
	JsHttpRequest.query('ad-ajax.php', {'replacepage': what, 'xxx': pid, 'papka': razdel, 'str': papka}, function(result, errors) {if (result) {document.getElementById('replace'+pid).innerHTML = result['replacepage']; }},false);
}
function clo(pid) {
	document.getElementById('pid'+pid).innerHTML = '';
}
function izmenapapka(select,papka,razdel,pid,type) {
	JsHttpRequest.query('ad-ajax.php', {'izmenapapka': select, 'xxx': papka, 'papka': razdel, 'str': pid, 'func': type, }, function(result, errors) {if (result) {document.getElementById('izmenapapka'+pid).innerHTML = result['izmenapapka']; }},false);
}

function delpapka(cid) {
	JsHttpRequest.query('ad-ajax.php', {'delpapka': cid}, function(result, errors) {},false);
	if (document.getElementById('cid'+cid)) show('cid'+cid);
}