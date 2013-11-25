function ss(t,lang,lang_text) { // Функция перевода сайта / Translate function
  if (lang == 'ru') return t; // Русский — по-умолчанию.
  else {
      if (lang_text[t]) return lang_text[t];
      else return " [ Error: no translate for: "+t+" ] ";
  } 
}

/* Проверка формы голосования */
var valueOpros = '-1';
function CheckForm(opros_num){
  if(valueOpros == '-1') alert('?');
  else $(showopros(opros_num,2,valueOpros));
}
/* Отображение голосования */
function showopros(id, res, golos) { 
	$.get('opros.php', { num: id, res: res, golos: golos }, function(data) { $('#show_opros'+id).html( data ); }); 
}

/* Изменение размеров ПРОВЕРИТЬ! */
function resize(obj, width1, width2, height1, height2) { 
	with (document.getElementById(obj).style) {
	   if (width != width2) width = width2;
	   else width = width1;
	   if (height != height2) height = height2;
	   else height = height1;
	}
}

/* Функции для работы текст. редактора комментариев */
function clc_bbcode(t,nu){
	var el = document.getElementById("area"),
	teg1 = "["+t+"]",
	teg2 = "[/"+t+"]";
	el.focus();
	if (nu==0){ 
		teg1 = ""; 
		teg2 = "["+t+"]"; 
	}
	if (el.selectionStart == null) {
	var rng = document.selection.createRange();
	rng.text = teg1+rng.text+teg2;
	} else {
	el.value = el.value.substring(0,el.selectionStart)+teg1+el.value.substring(el.selectionStart,el.selectionEnd)+teg2+el.value.substring(el.selectionEnd);}
}
 
function otvet(сid, number, t) {
	document.getElementById('comm_otvet_show').innerHTML = '<p><b>Вы отвечаете.</b> <a class=\'no comm_write\' href=#addcomm onclick=\'otmena_otvet();\'>Отменить ответ</a><br><br>'; 
	document.getElementById('comm_otvet').value = сid;
	var el = document.getElementById('area');
	el.focus();
	if (el.selectionStart == null) {
		var rng = document.selection.createRange();
		rng.text = rng.text + t;
	} else el.value = el.value.substring(0,el.selectionStart) + el.value.substring(el.selectionStart,el.selectionEnd) + t + el.value.substring(el.selectionEnd);
}
function otmena_otvet() {
	document.getElementById('comm_otvet_show').innerHTML = ''; 
	document.getElementById('comm_otvet').value = 0;
}

function clc_name(t){
	var el = document.getElementById("area");
	el.focus();
	if (el.selectionStart == null) {
		var rng = document.selection.createRange();
		rng.text = rng.text+t;
	} else {
		el.value = el.value.substring(0,el.selectionStart)+el.value.substring(el.selectionStart,el.selectionEnd)+t+el.value.substring(el.selectionEnd);
	}
}

/* Функция для обработки цитат при их вставке в текст. редактор комментариев */
function citata_shock(t){
	t = t.replace('<(/?)([i|b|u|hr|li]+)>','[$1$2]');
	t = t.replace('[/li]',''); 
	t = t.replace('<br>',' ');
	t = t.replace('</a>','[/url]'); 
	t = t.replace('<a href=','[url=');
	t = t.replace(/\<div.*\>/gi,'');
	t = t.replace(/\<table.*\>/gi,'');
	t = t.replace(/\<span.*\>/gi,'');
	t = t.replace(/\<img.*\>/gi,'');
	t = t.replace('<(/?)([table|tr|td|tbody|div|span]+)>','');
	t = t.replace('">',']'); 
	t = t.replace('>',''); 
	t = t.replace('"',''); 
	t = t.replace(/\<a.*\>/gi,'');
	t = t.replace(/\<.*\>/gi,'');
	return t;
}

/* Функция для отправки формы средствами Ajax */
function AjaxFormRequest(result_id,form_id,url) { 
    jQuery.ajax({
        url:     url, //Адрес подгружаемой страницы 
        type:     "POST", //Тип запроса 
        dataType: "html", //Тип данных 
        data: jQuery("#"+form_id).serialize(),  
        success: function(response) { //Если все нормально 
        	document.getElementById(result_id).innerHTML = response; 
	    }, 
	    error: function(response) { //Если ошибка 
	    	document.getElementById(result_id).innerHTML = "Error: ajax form"; 
	    } 
 	}); 
}

function page_golos(id,name,gol,type) {
	if (type == 0) { if (gol != 1 & gol != 2 & gol != 3 & gol != 4 & gol != 5 & gol != 6 ) gol = 1; }
	if (type == 1) { if (gol != 6 ) gol = 1; }
	if (type == 2) { if (gol != 1 & gol != 6) gol = 0;}
	if (type == 3) { if (gol != 1 & gol != 6) gol = 0;}
	$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
	    data: {'func': 'savegolos', 'type': type, 'id': id, 'string': name+'*@%'+gol},
	    beforeSend: function(){ $('#golos'+id).html('<img src="images/loading.gif">'); },
	    success: function(data){ $('#golos'+id).html(data); }
	});
}

function shop_show_order() {
	$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
	    data: {'func': 'shop_show_order' },
	    beforeSend: function(){ $('#shop_card').html('<img src="images/loading.gif">'); },
	    success: function(data){ $('#shop_card').html(data); }
	});
}

function shop_show_card() {
	$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
	    data: {'func': 'shop_show_card' },
	    beforeSend: function(){ $('#shop_card').html('<img src="images/loading.gif">'); },
	    success: function(data){ $('#shop_card').html(data); }
	});
}

function show_raspisanie(id, string) {
	$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
data: {'func': 'show_raspisanie', 'id': id, 'string': string},
beforeSend: function(){ $('#show_raspisanie'+id).html('<img src="images/loading.gif">'); },
	    success: function(data){ $('#show_raspisanie'+id).html(data); }
	});
}

function save_raspisanie() {
	var msg = $('form#zapis').serialize();
    $.ajax({
      type: 'POST',
      url: 'ajax.php',
      data: {'func': 'save_raspisanie', 'string': msg },
	  beforeSend: function(){ $('#zapis_dialog').html('<img src=images/loading.gif> Отправляю...'); },
      success: function(data) { $('#zapis_dialog').html(data); }
    });
}

function shop_add_tovar(id,price) {
	$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
	    data: {'func': 'shop_add_tovar', 'id': id, 'string': price},
	    success: function(data){ shop_show_card(); }
	});
}

function shop_del_tovar(id) {
	$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
	    data: {'func': 'shop_del_tovar', 'id': id},
	    success: function(data){ shop_show_card(); }
	});
}

/* Отображение скрытого DIV объекта  УДАЛИТЬ */
function show(obj) { 
	with (document.getElementById(obj).style) { 
		if (display == "none") display = "inline"; 
		else display = "none"; 
	}
}

/* Скрытие DIV объекта  УДАЛИТЬ */
function hide(obj) {  
	with (document.getElementById(obj).style) {
	   display = "none";
	}
}