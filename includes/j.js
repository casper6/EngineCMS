/* Проверка формы голосования */
var valueOpros = -1;
function CheckForm(opros_num){
  if(valueOpros == -1) alert('Вы не выбрали ни одного ответа!');
  else $(showopros(opros_num,2,valueOpros));
}

/* Отображение голосования */
function showopros(id, res, golos) { 
	$.get('opros.php', { num: id, res: res, golos: golos }, function(data) { $('#show_opros'+id).html( data ); }); 
}

/* Поиск объекта (используется ниже) */
function getObject(obj) {  
	var theObj
	if (document.layers) {
		if (typeof obj == "string") return document.layers[obj]
		else return obj
	}
	if (document.all) {
		if (typeof obj == "string") {
			if (document.all(obj)!=null) return document.all(obj).style;
			else return null;
		} else return obj.style
	}
	if (document.getElementById) {
		if (typeof obj == "string") return document.getElementById(obj).style
		else return obj.style
	}
	return null;
}

/* Отображение скрытого DIV объекта  УДАЛИТЬ ДУБЛИКАТ!*/
function show(obj) { 
	with (document.getElementById(obj).style) { 
		if (display == "none") display = "inline"; 
		else display = "none"; 
	}
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

/* Изменение размеров */
function resize(obj, width1, width2, height1, height2) { 
	with (document.getElementById(obj).style) {
	   if (width != width2) width = width2;
	   else width = width1;
	   if (height != height2) height = height2;
	   else height = height1;
	}
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
/* ################################################################ */
/* Скрытие DIV объекта */
function hide(obj) {  
	with (document.getElementById(obj).style) {
	   display = "none";
	}
}
/* ################################################################ */
/* Отображение меню - DIV объекта (используется для смайлов в комментариях) */
function SwitchMenu(obj) {
	if (document.getElementById) {
		var el = document.getElementById(obj);
		var ar = document.getElementById("cont").getElementsByTagName("div");
		if (el.style.display == "none") {
			for (var i=0; i<ar.length; i++) {
			ar[i].style.display = "none";
			}
		el.style.display = "block";
		} else {
		el.style.display = "none";
		}
	}
}
/* ################################################################ */
/* функции для работы текст. редактора комментариев */
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
	document.getElementById('comm_otvet_show').innerHTML='<p><b>Вы отвечаете.</b> <a class=\'no comm_write\' href=#addcomm onclick=\'otmena_otvet();\'>Отменить ответ</a><br><br>'; document.getElementById('comm_otvet').value = сid;
	var el=document.getElementById('area');
	el.focus();
	if (el.selectionStart==null) {
		var rng=document.selection.createRange();
		rng.text=rng.text+t;
	} else {
		el.value=el.value.substring(0,el.selectionStart)+el.value.substring(el.selectionStart,el.selectionEnd)+t+el.value.substring(el.selectionEnd);
	}
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
/* ################################################################ */
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

function page_golos(id,name,gol,type) {
	if (type == 0) { if (gol != 1 & gol != 2 & gol != 3 & gol != 4 & gol != 5 & gol != 6 ) gol = 1; }
	if (type == 1) { if (gol != 6 ) gol = 1; }
	if (type == 2) { if (gol != 1 & gol != 6) gol = 0;}
	if (type == 3) { if (gol != 1 & gol != 6) gol = 0;}
	$.ajax({ url: 'ajax.php', cache: false, dataType : "html",
	    data: {'func': 'savegolos', 'type': type, 'id': id, 'string': name+'*@%'+gol},
	    beforeSend: function(){ $('#golos'+id).html('Секундочку...'); },
	    success: function(data){ $('#golos'+id).html(data); }
	});
}
