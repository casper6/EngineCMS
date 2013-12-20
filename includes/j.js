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
  else $(show_opros(opros_num,2,valueOpros));
}
/* Отображение блока голосования */
function show_opros(id, res, golos) { 
	$.get('opros.php', { num: id, res: res, golos: golos }, function(data) { $('#show_opros'+id).html( data ); }); 
}
/* Отображение блока календаря */
function show_calendar(id, string) {
	$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
		data: {'func': 'show_calendar', 'string': string},
	    success: function(data){ $('#show_calendar'+id).html(data); }
	});
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

function page_golos(id,name,gol,type) {
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

function shop_show_card(basket) {
	$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
	    data: {'func': 'shop_show_card', 'string': basket },
	    beforeSend: function(){ $('.shop_' + basket).html('<img src="images/loading.gif">'); },
	    success: function(data){ $('.shop_' + basket).html(data); }
	});
}

function shop_add_tovar(id,price,count,price_pole,price_replace,replace_type) {
	if (count == null) count = 1;
	if (price_pole == null) price_pole = '';
	if (price_replace == null) price_replace = '';
	if (replace_type == null) replace_type = 1;
	if (price == 0 || price == null) alert ('Error: NO Price!');
	else if (id == 0 || id == null) alert ('Error: NO product ID!');
		else {
			$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
		    data: {'func': 'shop_add_tovar', 'id': id, 'string': price+'*@%'+count+'*@%'+price_pole+'*@%'+price_replace+'*@%'+replace_type},
		    success: function(data){ shop_show_card('price'); shop_show_card('count'); shop_show_card('card'); }
			});
			$('#shop_card_window').show();
		}
}

function shop_delete() {
	$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
	    data: {'func': 'shop_delete'},
	    success: function(data){ shop_show_card('price'); shop_show_card('count'); shop_show_card('card'); }
	});
}

function shop_del_tovar(id,all) {
	$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
	    data: {'func': 'shop_del_tovar', 'id': id, 'string': all},
	    success: function(data){ shop_show_card('price'); shop_show_card('count'); shop_show_card('card'); }
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