<?php
// Все «правила хорошего кода» написаны кровью, вытекшей из глаз программистов, читавших чужой код.
$display_delete = true; # показ кнопок удаления основного содержания = true, для скрытия = false
$display_addmenu = true; # показ кнопок создания основного содержания = true, для скрытия = false

if ($_REQUEST['op'] == "mainpage_save_ayax") {
	require_once("../mainfile.php");
	global $prefix, $db, $red, $admin;
	if (is_admin($admin)) $realadmin = 1; else $realadmin = 0;
} else {
	if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
	$aid = trim($aid);
	global $prefix, $db, $red;
	$sql = "SELECT realadmin FROM ".$prefix."_authors where aid='".$aid."'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$realadmin = $row['realadmin'];
}
if ($realadmin==1) {
	$tip = "mainpage";
	$admintip = "mainpage";
######################################################################################################
function mainpage($name="") {
	global $tip, $admintip, $prefix, $db, $name, $id, $display_delete, $display_addmenu;
	include("ad/ad-header.php");
	if ($name=="design" or $name=="css" or $name=="module" or $name=="block" or $name=="spisok" or $name=="base" or $name=="shablon") { create_main($name); }
	elseif (intval($id)>0) { edit_main($id); }
	elseif ($display_addmenu == false) echo "<center><br>Создание основных разделов сайта запрещено администратором.</center>";
	else { 
		echo "<div class='curved-vt-2 hide' style='margin-left:-265px; width: 530px; top: 80px;' id='add'>
		<a title='Закрыть это окно' class=punkt onclick=\"$('#add').hide();\"><div class='radius close_button'>&nbsp;x&nbsp;</div></a>
			<h1>Вы решили добавить:</h1>
			<a href='sys.php?op=mainpage&amp;name=design&amp;type=0&amp;red=1#1' class='bigicon'><span class='bigicon bi0'></span><b>Дизайн</b><br>
			Аналог темы, обрамление разделов или блоков</a>
			<a href='sys.php?op=mainpage&amp;name=css&amp;type=1#1' class='bigicon'><span class='bigicon bi1'></span><b>Стиль CSS</b><br>
			Редактируемый онлайн, для подключения к дизайнам</a>
			<a href='sys.php?op=mainpage&amp;name=block&amp;type=3#1' class='bigicon'><span class='bigicon bi3'></span><b>Блок</b><br>
			Настраиваемые элементы для вывода различной информации</a>
			<a href='sys.php?op=mainpage&amp;name=shablon&amp;type=6&amp;red=1#1' class='bigicon'><span class='bigicon bi6'></span><b>Шаблон</b><br>
			Замена настроек на ручной выбор выводимой информации</a>
			<a href='sys.php?op=mainpage&amp;name=spisok&amp;type=4#1' class='bigicon'><span class='bigicon bi4'></span><b>Поле</b><br>
			для страниц (аналог таксономии, для фильтров и доп. информации)</a>
			<a href='sys.php?op=mainpage&amp;name=base&amp;type=5#1' class='bigicon'><span class='bigicon bi5'></span><b>Базу данных</b><br>
			Таблица с поиском и фильтрами для внутреннего или открытого использования</a>
			</div>

		<table class='block_back w100 mw800 pm0' cellspacing=0 cellpadding=0><tr valign=top><td id='razdel_td' class='radius'>

			<div id='razdels' style='width:340px;'>
			<div class='black_grad h40'><button id='addmain' title='Добавить оформление...' class='small right3' onclick=\"
if ($('#mainrazdel0').attr('class') == 'dark_pole2sel') window.location = 'sys.php?op=mainpage&amp;name=design&amp;type=0&amp;red=1#1';
else if ($('#mainrazdel1').attr('class') == 'dark_pole2sel') window.location = 'sys.php?op=mainpage&amp;name=css&amp;type=1#1';
else if ($('#mainrazdel3').attr('class') == 'dark_pole2sel') window.location = 'sys.php?op=mainpage&amp;name=block&amp;type=3#1';
else if ($('#mainrazdel6').attr('class') == 'dark_pole2sel') window.location = 'sys.php?op=mainpage&amp;name=shablon&amp;type=6&amp;red=1#1';
else if ($('#mainrazdel4').attr('class') == 'dark_pole2sel') window.location = 'sys.php?op=mainpage&amp;name=spisok&amp;type=4#1';
else if ($('#mainrazdel5').attr('class') == 'dark_pole2sel') window.location = 'sys.php?op=mainpage&amp;name=base&amp;type=5#1';
			else $('#add').toggle();\"><span class='mr-2 icon small' data-icon='+'></button>
			<span class='h1'>Оформление</span>
			</div>";
	     ////////////////////// ДИЗАЙН 0
		 if ($show_admin_top != "2") echo "<div id='mainrazdel0' class='dark_pole2'><a class='base_page' onclick=\"$('#addmain').attr('class', 'small right3 green'); oformlenie_show('дизайн (html)','0','design','/sys.php?op=mainpage&amp;name=design&amp;type=0&amp;red=1')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='4'></span><span class='plus20'>Дизайн разделов и блоков (html)</span></div></a></div>";
		 ////////////////////// СТИЛЬ 1
		 if ($show_admin_top != "2") echo "<div id='mainrazdel1' class='dark_pole2'><a class='base_page' onclick=\"$('#addmain').attr('class', 'small right3 green'); oformlenie_show('стиль (css)','1','css','/sys.php?op=mainpage&amp;name=css&amp;type=1')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='#'></span><span class='plus20'>Стиль для дизайна (css)</span></div></a></div>";
		 ////////////////////// БЛОКИ 3
		 echo "<div id='mainrazdel3' class='dark_pole2'><a class='base_page' onclick=\"$('#addmain').attr('class', 'small right3 green'); oformlenie_show('блок','3','block','/sys.php?op=mainpage&amp;name=block&amp;type=3')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='R'></span><span class='plus20'>Блоки (небольшие элементы)</span></div></a></div>";
		 ////////////////////// ШАБЛОНЫ 6
		 if ($show_admin_top != "2") echo "<div id='mainrazdel6' class='dark_pole2'><a class='base_page' onclick=\"$('#addmain').attr('class', 'small right3 green'); oformlenie_show('шаблон','6','shablon','/sys.php?op=mainpage&amp;name=shablon&amp;type=6&amp;red=1')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='v'></span><span class='plus20'>Шаблоны (внешний вид)</span></div></a></div>";
		 ////////////////////// ПОЛЯ 4
		 if ($show_admin_top != "2") echo "<div id='mainrazdel4' class='dark_pole2'><a class='base_page' onclick=\"$('#addmain').attr('class', 'small right3 green'); oformlenie_show('поле','4','pole','/sys.php?op=mainpage&amp;name=spisok&amp;type=4')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='S'></span><span class='plus20'>Поля (для страниц)</span></div></a></div>";
		 ////////////////////// БАЗЫ ДАННЫХ 5
		 echo "<div id='mainrazdel5' class='dark_pole2'><a class='base_page' onclick=\"$('#addmain').attr('class', 'small right3 green'); oformlenie_show('БД','5','base','/sys.php?op=mainpage&amp;name=base&amp;type=5')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='D'></span><span class='plus20'>Базы данных (таблицы)</span></div></a></div>";

		 echo "<br>";
		 ////////////////////// УДАЛЕННОЕ
		 echo "<div id='mainrazdel2' class='dark_pole2'><a class='base_page' onclick=\"$('#addmain').attr('class', 'small right3'); oformlenie_show('','2','trash','')\"><div id='mainrazdel".$id."'><span class='icon gray large' data-icon='T'></span><span class='plus20'>Удаленное оформление</span></div></a></div>";
		 
		echo "</div></td>
	<td style='padding:0;'><a class='punkt' title='Свернуть/развернуть левую колонку' onmousemove='$(\"#razdels\").show();' onclick='$(\"#razdels\").toggle(\"slow\");'><div class='polosa_razdelitel'><div id='rotateText'><nobr>↑ Сворачивает Оформление ↑</nobr></div></div></a></td>
	<td class='w100 pm0'>";
			
			if ($show_admin_top != "2") echo "<div class='black_grad'><div class='pt5'>
			<button class='small' onclick='location.href=\"/sys.php?op=mainpage&amp;type=0&amp;id=1&amp;red=2\"' title='Редактировать главный дизайн'><span class='icon small' data-icon='7'></span> Главный дизайн</button> 
			<button class='small' onclick='location.href=\"/sys.php?op=mainpage&amp;type=1&amp;id=20\"' title='Редактировать главный стиль'><span class='icon small' data-icon='7'></span> Главный стиль</button></div></div>";

		echo "<div class='podrazdel radius' id='podrazdel'></div>
			</td></tr></table>";
		}
	echo "

	<br></div></body></html>";
}
###################################################################################################
function create_main($type) {
	global $tip, $admintip, $prefix, $db;
	$create = ""; 
	switch ($type) {
	case "design": $type_opis = "дизайна (HTML)";
	$create.="<div id=about class=block style='display:none;'>Дизайн — это обрамление страниц сайта, всё, что окружает контент, содержание. В дизайне могут быть блоки, которые выводят необходимую информацию. Например созданный вами блок меню или автоматический блок [статистика], который выводит счетчик статистики из настроек сайта. В общем, дизайн — это весь html-код, от body до /body (невключительно), а сама страница заменена на служебное слово [содержание] - блок, который выводит содержание подключенных разделов и их страниц. После создания дизайна, откройте его редактирование и присвойте необходимые стили (css).<br></div>
	<table class='w100 mw800'><tr><td>
	<span class=h2>Название:</span> По-русски, можно с пробелами
	<input type='text' name='title' value='' size=40 class='w100 h40 f16' autofocus><br>
	".help_design()."
	<h1>Содержание дизайна (HTML): ".button_resize_red(2, false)."
	<a class='button small blue' onclick='if ($(\"#color_scheme\").html() == \"\") $(\"#color_scheme\").html(\"<iframe src=http://colorscheme.ru width=985 height=660 scrolling=no frameborder=0></iframe>\"); $(\"#color_scheme\").toggle();'>Подбор цвета</a>
	</h1>
	<div class='hide' style='position:absolute; margin: 0 auto; width: 985px; height:660px; z-index:1000;' id='color_scheme'></div>
	".redactor('2', '', 'text', '', 'html')."
	<div class='notice success black'><span class='icon large white' data-icon='C'></span>
	Здесь вы можете вставить готовый HTML-код (от тега &lt;body&gt; до &lt;/body&gt;, не включительно) или набрать его с нуля.
	<br><b>[содержание]</b> - автоматический блок для вывода страниц. Не использовать его можно лишь в случае присоединения дизайна к разделу, состоящему из одной страницы — в этом случае можно всю страницу поместить в дизайн или в раздел.</div>
	<input type=hidden name=id value=0>
	<input type=hidden name=namo value=''>
	<input type=hidden name=useit value=''>
	<input type=hidden name=shablon value=''>
	<input type=hidden name=type value='0'>
	<input type=hidden name=op value='".$admintip."_save'>
	</td></tr></table>";
	########################################################
	// [корзина] - общая корзина для всех разделов типа 'магазин'

	break;
	case "css": $type_opis = "стиля (CSS)";
	$create.="<div id=about class=block style='display:none;'>Стили - это CSS (каскадные таблицы стилей), описание оформления элементов сайта. Стили подключаются к дизайну (при его редактировании). В дизайне может быть использовано сразу несколько стилей. При подключении стилей они объединяются в один стиль и сжимаются.<br></div>
	<div class='w95 mw800'>
	<h2>Название: <span class='f12'>По-русски, можно с пробелами</span><br><input type='text' name='title' value='' size='40' class='w100 h40 f16' autofocus></h2>

	<h1>Содержание стиля: ".button_resize_red(2, false)."
	<a class='button small blue' onclick='if ($(\"#color_scheme\").html() == \"\") $(\"#color_scheme\").html(\"<iframe src=http://colorscheme.ru width=985 height=660 scrolling=no frameborder=0></iframe>\"); $(\"#color_scheme\").toggle();'>Подбор цвета</a>
	 <a class='button small blue' href='http://sassmeister.com' target='_blank'>SASS > CSS</a>
	 <a class='button small blue' href='http://less2css.org' target='_blank'>LESS > CSS</a>
	</h1>
	<div class='hide' style='position:absolute; margin: 0 auto; width: 985px; height:660px; z-index:1000;' id='color_scheme'></div>
	".redactor('2', '', 'text', '', 'css')."
	<input type='hidden' name='id' value=0>
	<input type='hidden' name='type' value='1'>
	<input type='hidden' name='namo' value=''>
	<input type='hidden' name='useit' value=''>
	<input type='hidden' name='shablon' value=''>
	<input type='hidden' name='op' value='".$admintip."_save'>
	</div>";
	########################################################
	break;

	case "block": $type_opis = "блока (элемент содержания)";
		global $title_razdel_and_bd;
		$bd_show = false;
		$razdels = "";
		foreach ($title_razdel_and_bd as $key => $modul_title) {
			$key_class = 'razdely';
			if (strpos(" ".$modul_title, aa("база данных"))) { $key_class='baza'; $bd_show = true; }
			$razdels .= "<option value='".$key."' class='".$key_class."'>".$modul_title."</option>";
		}
		if ($bd_show == false) $bd_show = " disabled"; else $bd_show = "";

	$typeX = array();
	$typeX[2]  = "Текст или HTML"; 
	$typeX[10] = "Меню сайта";
	$typeX[5]  = "Голосование (опрос)"; 
	$typeX[3]  = "Ротатор – для блоков, текста или HTML"; 

	$typeX[6]  = "Фотогалерея"; 
	$typeX[9]  = "Экстрактор страниц (по-умолчанию: фото)";

	$typeX[4]  = "Папки раздела"; 
	$typeX[8]  = "Папки открытого раздела";
	$typeX[0]  = "Страницы раздела"; 
	$typeX[1]  = "Комментарии раздела"; 

	$typeX[14] = "Расписание с записью на прием";
	$typeX[15] = "Карта";
	$typeX[12] = "Форма для заполнения (в разработке)"; // анкеты, опросы и т.д.
	$typeX[31] = "JavaScript";
	$typeX[7]  = "PHP-код";
	$typeX[11] = "Календарь";
	$typeX[13] = "Облако тегов";
	$typeX[30] = "Посещаемость раздела";

	$typeX[20] = "БД (количество по 1 колонке верт.)";
	$typeX[21] = "БД (количество по 1 колонке гор.)";
	$typeX[22] = "БД (количество по 2 колонкам)";
	$typeX[23] = "БД (список колонок)";

			$styles = ""; // Выборка дизайнов
			$titles_design = titles_design();
			foreach ($titles_design as $id_design => $title_design) {
				if ($id_design != 1) $styles .= "<option value='".$id_design."'>".$title_design."</option>";
			}

	// Получим список названий блоков
	$sql2 = "select `id`,`name`,`title` from ".$prefix."_mainpage where `type`='3' and `tables`='pages' order by `title`"; 
	$result2 = $db->sql_query($sql2);
	$titles_block = array();
	$options_block2 = $options_block3 = "";
	while ($row2 = $db->sql_fetchrow($result2)) {
		$titles_block[] = trim($row2['title']);
		if ($row2['name']=='2') $options_block2 .= "<option value='".$row2['id']."'>".$row2['title']."</option>"; // блоки текста
		if ($row2['name']=='3') $options_block3 .= "<option value='".$row2['id']."'>".$row2['title']."</option>";// блоки-ротаторы
	}
	$titles_block = implode("','", $titles_block);
	$create = "<div id=about class=block style='display:none;'>Блок — это дополнительный элемент сайта, который может быть вставлен в любом его месте. Блоки бывают автоматические (заранее заданные системой), полуавтоматические (создаваемые с последующей настройкой того, что будет в них отображаться) и ручные (текст, HTML, JavaScript или PHP-код).<br></div>

	<p><span class=h2>Название:</span> <input id='block_name' type='text' name='title' value='' size=60 class='w60 f16'>
	<div id='block_name_correction'></div>
	<script>
	function in_array(needle, haystack, strict) {
		var found = false, key, strict = !!strict;
		for (key in haystack) {
			if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
				found = true;
				break;
			}
		}
		return found;
	}
	$('#block_name').blur(function () {
	  if ($('#block_name').val() != '') {
	  	if (in_array($('#block_name').val(), ['".$titles_block."'])) alert('Такое название блока уже есть! Используйте более подробное название.');
	  } else {
	  	alert('Название блока не должно быть пустым!');
	  }
	});
	</script>

	<p><span class='h3'>Добавить в другой блок:</span> 
	<select name='another_block'><option value='0'>не добавлять</option><option disabled>= текстовые блоки =</option>".$options_block2."<option disabled>= блоки-ротаторы =</option>".$options_block3."</select>, <nobr>позиция: 
	<select name='another_block_position'><option value='0'>сверху</option><option value='1'>снизу</option><option value='2'>заменить!</option></select></nobr>

	<p><span class='h3'>Дизайн для блока:</span> <select name='design'><option value='0'>выбирать необязательно</option>".$styles."</select>

	<p><span class='h2'>Выберите тип блока:</span> (справа — его параметры, а зеленым цветом снизу — описание)
	<table class='w100' cellspacing=0 cellpadding=0><tr valign=top><td class=''>
	<select id=name size=18 name='name' class='w100 f14' onchange=\"
	var arr = [ 'Блок выводит несколько страниц выбранного раздела или всех разделов. Вывод может настраиваться, возможно создание шаблонов. За счет блоков [фото] и [адрес фото] в шаблоне возможно создание блока, выводящего только по одной фотографии из предисловия страницы. А благодаря блоку [предисловие_без_фото] в шаблоне можно выводить предисловие страницы с вырезанными из него фотографиями, к примеру в узкую колонку новостей.', 'Блок выводит несколько комментариев со страниц выбранного раздела или всех разделов', 'В этом блоке можно написать любой текст, использовать HTML, а также другие созданные блоки', '«Ротатор» используется для показа блоков, текста или html.<br>При каждом обновлении страницы будет показан один из написанных элементов, разделение списка ротации — через символ «|» (вертикальная черта). Также возможна смена контента в блоке через определенное время и/или в определенной последовательности.<br>Примеры:<br>1 вариант - каждый раз при загрузке страницы грузит новую картинку или анекдот<br>2 вариант - то же самое с кнопкой «Хочу еще» (ее можно и по-другому обозвать) и обновлением<br>3 вариант - показывает что-то с периодичностью по времени (настраивается)<br>4 вариант - показывает что-то с определенной последовательностью, одно за другим', 'Блок выводит папки выбранного раздела или всех разделов', 'Блок выводит голосование (опрос). Название блока — это вопрос опроса, а список ответов — в Содержании блока, через «Enter».<br>После того, как на опрос кто-то ответит, после каждого ответа появится символ «|» и число проголосовавших.<br>Если нужно закрыть какой-то ответ для голосования (например, это больше не нужно или уже реализовано) — нужно после числа проголосовавших поставить еще один символ «|» и написать причину зыкрытия, например «готово» или «удалено», желательно кратко.', 'Блок «Фотогалерея» выводит фотографии в нужном месте сайта, для его создания используется список адресов закаченный фотографий, через «Enter».<br>А их описание ставится сразу после адреса через символ «|» (вертикальная черта), пример: /img/1.jpg|Фото 1. <br>— Имена файлов могут содержать любые символы. <br>— Загрузка автоматическая сразу после выбора файлов. <br>— Фотографии будут автоматически переименованы, развернуты в нужную сторону, сжаты и уменьшены по ширине до 1000 пикселей.<br><div class=red>— Если впоследствии вы захотите стереть какую-либо фотографию — просто удалите ее строчку из блока галереи (не забудьте сохранить блок), затем перейдите во вкладку «Настройки», откройте «Удаление неиспользуемых фотографий» и удалите эту фотографию.</div>', 'PHP-код пишется в Содержании блока.<br>PHP можно писать сразу, без начальных и конечных обозначений ( &lt; ?php ... ? &gt; ).<br>Вывод информации в блок производится через переменную <b>$"."txt</b>', 'Блок выводит папки открытого в данный момент раздела или ничего не выводит, если это Главная страница или папок в разделе не создано', 'Блок по-умолчанию может выводить первые фотографии из страниц выбранных разделов, но его можно настроить на вывод любой другой информации, помещенной в предисловие или содержание страницы (если информация не найдена в предисловии - будет найдена в содержании). Если в предисловии и содержании страницы не обнаружен обусловленная настройками информация, эта страница пропускается.', 'Меню сайта может настраиваться автоматически или вручную. Если выбран авто-режим — при редактировании блока можно будет выбрать разделы, папки и страницы сайта, а также их очередность. <br>В ручном режиме меню создается по простым правилам (для того, чтобы быть универсальным и легко переключать варианты отображения):<br>[элемент открыть][url=/]Главная[/url][элемент закрыть]<br>[элемент открыть][url=#]Пункт меню 1[/url][элемент закрыть]<br>[элемент открыть][url=#]Пункт меню 2[/url]<br>&nbsp;&nbsp;[уровень открыть]<br>&nbsp;&nbsp;[элемент открыть][url=#]Подпункт 1[/url][элемент закрыть]<br>&nbsp;&nbsp;[элемент открыть][url=#]Подпункт 2[/url][элемент закрыть]<br>&nbsp;&nbsp;[уровень закрыть]<br>[элемент закрыть]<br><i>где # - это ссылка на страницу.</i><br>В меню может быть до 3-х уровней вложенности', 'Календарь на текущем месяце показывает ссылками те даты, за которые созданы страницы в выбранном разделе (или всех разделах).<br>Также показывает текущую дату', 'В РАЗРАБОТКЕ!!!!!!!!! Контактная форма может применяться для создания страницы Контакты, а также для отправки разнообразных анкет, заявок, жалоб и т.д', 'Облако тегов — это вращающийся трехмерный шар, состоящий из ключевых слов, взятых из страниц выбранного раздела (или всех разделов). Под ним есть ссылка на альтернативный текстовый вариант облака', 'Блок расписания состоит из календаря для выбора даты записи и самого расписания работы отдельных специалистов, кабинетов или услуг. Структура содержания блока<ul><li>процедура, описание, сеанс процедуры XX минут, день-дни и время работы от-до. <li>или — специалист, специальность, время приема XX минут, день-дни и время работы от-до.</ul>Пример содержания блока:<br>Вардунас Валерий Аркадьевич, стоматолог, 60 минут, понедельник-вторник-среда-пятница 10:00-21:45, четверг-воскресенье 15:00-20:30, суббота 11:00-13:30<br>Васильченко Георгий Александрович, массажист, 30 минут, вторник 12:00-20:00, среда-четверг-пятница 15:00-20:15, суббота-воскресенье 15:00-18:30<br>Важно перечислять все дни, если время работы для них одно и то же. Т.е., если работаем с понедельника по пятницу — надо писать не понедельник-пятница, а понедельник-вторник-среда-четверг-пятница.', 'Блок карты основан на Яндекс.Картах, позволяет выводить один или несколько объектов с описанием, производить поиск по карте', '16', '17', '18', '19', 'База данных (количество по 1 колонке верт.)', 'База данных (количество по 1 колонке гор.)', 'База данных (количество по 2 колонкам)', 'База данных (список колонок)', '24', '25', '26', '27', '28', '29', 'Статистика раздела, выводит кол-во посещений выбранного раздела', 'JavaScript-блок автоматически встанет в HEAD и его не нужно где-либо специально размещать.', '32' ];

	var add;
	if (document.getElementById('name').value!=31) add = '.<br>После создания блока, необходимо его настроить — настройка откроется автоматически.';
	else add = '';
	$('#opisanie_bloka').html (arr[document.getElementById('name').value] + add);

	if (document.getElementById('name').value==20 || document.getElementById('name').value==21 || document.getElementById('name').value==22 || document.getElementById('name').value==23) { $('.baza').removeAttr('disabled'); $('.razdely').attr('disabled', 'disabled'); } else { $('.baza').attr('disabled', 'disabled'); $('.razdely').removeAttr('disabled'); }

	if (document.getElementById('name').value!=6) { $('#photo_upload').hide(); } else { $('#photo_upload').show(); }

	if (document.getElementById('name').value==0 || document.getElementById('name').value==1 || document.getElementById('name').value==4 || document.getElementById('name').value==9 || document.getElementById('name').value==11 || document.getElementById('name').value==13 || document.getElementById('name').value==30 || document.getElementById('name').value==20 || document.getElementById('name').value==21 || document.getElementById('name').value==22 || document.getElementById('name').value==23) { $('#razdel_bloka').show('slow'); } else { $('#razdel_bloka').hide(); }

	if (document.getElementById('name').value==2 || document.getElementById('name').value==10 || document.getElementById('name').value==12 || document.getElementById('name').value==5 || document.getElementById('name').value==14 || document.getElementById('name').value==3 || document.getElementById('name').value==31 || document.getElementById('name').value==7) { $('#textarea_block').show('slow'); } else { $('#textarea_block').hide(); }

	if (document.getElementById('name').value==30 || document.getElementById('name').value==20 || document.getElementById('name').value==21 || document.getElementById('name').value==22 || document.getElementById('name').value==23) { $('.allrazdely').attr('disabled', 'disabled'); } else { $('.allrazdely').removeAttr('disabled');  }

	if (document.getElementById('name').value==12) { $('#form_block').show('slow'); } else { $('#form_block').hide(); }
	\">";
		foreach ($typeX as $key => $tit) {
			$sel = "";
			if ($key == 2) $sel = " selected";
			if ($key > 19 and $key < 24) $create .=  "<option value=".$key.$bd_show.">".$tit."</option>";
			else $create .=  "<option value='".$key."'".$sel.">".$tit."</option>";
		}
	$create .=  "</select>
	<input type='hidden' name='useit'>
	</td><td>

	<div id='razdel_bloka' style='display:none;'>
	<b>Выберите раздел для блока:</b><br>
	<select name='modul' size=9 class='mi50ma80'>
	<option value='' class='allrazdely'>< ВСЕ разделы ></option>".$razdels."</select> 
	</div>

	<div id='textarea_block'>

	<h2>Содержание блока:</h2>
	<div class='pics w100'></div>
	<textarea name=text rows=3 cols=86 class='w100 h155' id=textarea onchange=\"if ($('#name').val() == '6') pics_refresh('#textarea');\"></textarea>
	</div>
	<input type=hidden name=op value='".$admintip."_create_block'>
	<input type=hidden name=id value=''>
	</form>

	<div id='photo_upload' class='hide'>".add_file_upload_form("textarea", "textarea_block")."</div>

	</td>
	<td id='form_block' style='display:none;' width=500 class='block'>
	<a onclick=\"$('#form_block').hide();\" class=help class='close_button radius'>x</a>
	<b>Добавление формы:</b><br>
	<select id='form_element' onchange='

	if ( $(\"#form_element\").val() != 0 && $(\"#form_element\").val() != \"Отправить\" ) {
		$(\".form_id\").show(\"slow\");
		$(\".form_zvezda\").show(\"slow\");
	} else {
		$(\".form_id\").hide();
		$(\".form_zvezda\").hide();
	}

	if ( $(\"#form_element\").val() != 0 ) {
		$(\".form_name\").show(\"slow\");
	} else {
		$(\".form_name\").hide();
	}

	if ( $(\"#form_element\").val() == \"Список\" ) {
		$(\".form_var\").show(\"slow\");
	} else $(\".form_var\").hide();

	if ( $(\"#form_element\").val() == \"Строка\" || $(\"#form_element\").val() == \"Текст\" ) {
		$(\".form_placeholder\").show(\"slow\");
	} else $(\".form_placeholder\").hide();

	if ( $(\"#form_element\").val() == \"Строка\" || $(\"#form_element\").val() == \"Email\" || $(\"#form_element\").val() == \"Телефон\" ) {
		$(\".form_size_h\").show(\"slow\");
	} else $(\".form_size_h\").hide();

	if ( $(\"#form_element\").val() == \"Текст\" || $(\"#form_element\").val() == \"Список\" || $(\"#form_element\").val() == \"Адрес\" ) {
		$(\".form_size_v\").show(\"slow\");
	} else $(\".form_size_v\").hide();
	

	if ( $(\"#form_element\").val() != \"Email\" && $(\"#form_element\").val() != \"Телефон\" && $(\"#form_element\").val() != \"Адрес\" && $(\"#form_element\").val() != \"Отправить\" ) {
		$(\"#form_name\").val(\"\");
		$(\"#form_id\").val(\"\");
		$(\"#form_placeholder\").val(\"\");
	} else {
		if ( $(\"#form_element\").val() == \"Email\" ) {
			$(\"#form_name\").val(\"Email\");
			$(\"#form_id\").val(\"email\");
		}
		if ( $(\"#form_element\").val() == \"Телефон\" ) {
			$(\"#form_name\").val(\"Телефон\");
			$(\"#form_id\").val(\"tel\");
		}
		if ( $(\"#form_element\").val() == \"Адрес\" ) {
			$(\"#form_name\").val(\"Адрес\");
			$(\"#form_id\").val(\"address\");
		}
		if ( $(\"#form_element\").val() == \"Отправить\" ) {
			$(\"#form_name\").val(\"Отправить\");
		}
	}
	'>
		<option value='0'> >> Выберите поле << </option>
		<option value='Строка'>Строка</option>
		<option value='Текст'>Текст</option>
		<option value='Список'>Выбор из списка</option>
		<option value='Файл'>Файл</option>
		<option value='Email'>Email</option>
		<option value='Телефон'>Телефон</option>
		<option value='Адрес'>Адрес</option>
		<option value='Отправить'>Кнопка «Отправить»</option>
	</select> 
	<a class='button small green' class='ml20' onclick='

	if ( $(\"#form_name\").val() == \"\" ) {
		$(\"#form_name\").val(\"Заполните это поле\");
	} else {
		var add;
		var form_zvezda = 0;
		if ($(\"#form_zvezda\").is(\":checked\")) form_zvezda = 1;
		if ( $(\"#form_element\").val() == \"Строка\" ) add = $(\"#form_placeholder\").val() + \"|\" + $(\"#form_size_h\").val();
		if ( $(\"#form_element\").val() == \"Текст\" ) add = $(\"#form_placeholder\").val() + \"|\" + $(\"#form_size_v\").val();
		if ( $(\"#form_element\").val() == \"Список\" ) add = $(\"#form_var\").val().replace(new RegExp(\"\\n\",\"g\"),\"*\") + \"|\" + $(\"#form_size_v\").val();
		if ( $(\"#form_element\").val() == \"Email\" ) add = $(\"#form_size_h\").val();
		if ( $(\"#form_element\").val() == \"Телефон\" ) add = $(\"#form_size_h\").val();
		if ( $(\"#form_element\").val() == \"Адрес\" ) add = $(\"#form_size_v\").val();
		if ( $(\"#form_element\").val() != \"Отправить\" && $(\"#form_element\").val() != \"Файл\") add = \"|\" + $(\"#form_id\").val() + \"|\" + add + \"|\" + form_zvezda;
		if ( $(\"#form_element\").val() == \"Файл\") add = \"|\" + $(\"#form_id\").val() + \"|\" + form_zvezda;
		$(\"#textarea\").val($(\"#textarea\").val()+ $(\"#form_element\").val() + \"|\" + $(\"#form_name\").val() + add + \"\\n\" );
	}
	'><span class='icon white small' data-icon='+'></span>Добавить поле</a><br>
	<table class='w100 mw800'><tr valign=top><td width=50%>
	<div class='form_name' style='display:none'>Название <span class=small>(по-русски)</span>*:<br>
		<input id='form_name' class='w100 h40 f16'></div>
	</td><td>
	<div class='form_id' style='display:none'>и по-английски <span class=small>(необязательно)</span><br>
		<input id='form_id' class='w100 h40 f16'></div>
	</td></tr><tr valign=top><td>
	<div class='form_var' style='display:none'>Выбор <span class=small>(вариант*(рус.)/значение)</span>:<br>
		<textarea rows=3 cols=86 class='w100 h70 f16' id='form_var'></textarea></div>
	<div class='form_placeholder' style='display:none'>Текст в поле <span class=small>(тип значения)</span>:<br>
		<input id='form_placeholder' class='w100 h40 f16'></div>
	</td><td>
	<div class='form_size_h' style='display:none'>Ширина <span class=small>(необязательно)</span>:<br>
	<select id='form_size_h'>
		<option value='10'>10%</option>
		<option value='20'>20%</option>
		<option value='30'>30%</option>
		<option value='40'>40%</option>
		<option value='50'>50%</option>
		<option value='60'>60%</option>
		<option value='70'>70%</option>
		<option value='80'>80%</option>
		<option value='90'>90%</option>
		<option value='100' selected>100%</option>
	</select></div>
	<div class='form_size_v' style='display:none'>Высота <span class=small>(необязательно)</span>:<br>
	<select id='form_size_v'>
		<option value='1'>Одна строка</option>
		<option value='2'>Две строки</option>
		<option value='3'>Три строки</option>
		<option value='4'>Четыре строки</option>
		<option value='5' selected>Пять строк</option>
		<option value='10'>10 строк</option>
		<option value='15'>15 строк</option>
		<option value='20'>20 строк</option>
		<option value='25'>25 строк</option>
		<option value='30'>30 строк</option>
	</select></div>
	</tr><tr><td colspan=2>
	<div class='form_zvezda' style='display:none'>
		<label><input type='checkbox' id='form_zvezda' value='1'> Обязательно к заполнению</label></div>
	</td></tr>
	</table>
	Добавляйте по очередности.<br>
	При отсутствии английского названия, оно создается автоматически, транслитом русского.<br>
	Не должно быть одинаковых английских названий!<br>
	Кнопка «Отправить» обычно добавляется последней. Её можно и не добавлять, тогда она будет добавлена автоматически.
		</td></tr>
	</table>
	<div id='opisanie_bloka' class='notice success black'>
	В этом блоке можно написать любой текст, использовать HTML, а также другие созданные блоки.<br>После создания блока, необходимо его настроить — настройка откроется автоматически.
	</div>
	<div class='notice warning black'>Отдельно созданный дизайн для блока может использоваться для его обрамления, к примеру: в красивую рамку. Если выбран дизайн, в содержании дизайна обязательно должен быть блок [содержание] для вывода самого блока.</div>";
	break;
	########################################################
	case "spisok": $type_opis = "поля (для страниц)";
		$create.="";
			$modules = ""; // Выборка разделов заменить!
			$sql2 = "SELECT `id`, `title` FROM ".$prefix."_mainpage WHERE `tables`='pages' and `type`='2' and `name`!='index'";
			$result2 = $db->sql_query($sql2);
			while ($row2 = $db->sql_fetchrow($result2)) {
			   $id_modul = $row2['id'];
			   $title_modul = $row2['title'];
			   $modules .= "<option value='".$id_modul."'>".$title_modul."</option>";
			}
			// Добавлены пользователи USERAD
			$sql3 = "SELECT * FROM ".$prefix."_users_group WHERE `group`!='rigion' and `group`!='config' and `group`!='obl'";
			$result3 = $db->sql_query($sql3);
			while ($row3 = $db->sql_fetchrow($result3)) {
			   $id = "1,".$row3['id'];
			   $title_group = $row3['group'];
			   $modules .= "<option value='".$id."'>Группа пользователей: ".$title_group."</option>";
			}
			// Добавлены пользователи USERAD
	$create.="<div id=about class=block style='display:none;'>В Разделах сайта есть папки, в папках лежат страницы (статьи, новости и т.д.). У каждой страницы несколько полей для хранения информации: название, дата, предисловие, содержание и т.д. Если вдруг для какого либо раздела (или для всех разделов) не хватает подобного поля - его можно добавить. Примеры использования полей: отдельное поле для ввода автора (для статей), поле для загрузки фотографии (для фотогалереи), выбор из раскрывающегося списка определенного населенного пункта (для каталога предприятий), период времени (для афиши) и т.д. А для того, чтобы поля начали отображаться на страницах - есть произвольные шаблоны, которые можно создать и подключить к любому разделу (См. в настройках раздела - Шаблон для списка страниц или Шаблон для страницы).<br><b>Поле может принадлежать или какому-то одному разделу или сразу всем разделам.</b><br></div>
	<table class='w100 mw800'><tr valign=top><td width=50%>
	<input type=hidden name=type value='4'>
	<h2>Название поля</h2><input type=text name=title size=40 class='w100 h40 f16' autofocus><br>(рус.)</td><td>
	<h2>Обращение</h2><input type=text name=namo size=40 class='w100 h40 f16'><br>(англ., без пробелов) Используется для вывода в шаблонах как: [обращение]</td></tr>
	<tr valign=top><td width=50%>
	<input type=hidden name=shablon value=''>
	<h2>Выберите раздел:</h2><select name='useit' id='razdels' class='w100 h40 f16' onchange='check_papka()'><option value='0'>все разделы</option>".$modules."</select><div class='show_papka hide'>
	<a class='button' onclick=\"$('#izmenapapka0').toggle(); $('#papka1').toggle();\">Выбор папок</a>

	<div id='izmenapapka0' class='hide w100'><select id='papki' name='shablon[]'><option value='0'></option></select></div>
	<span id='papka1' class='hide'>".aa("Для выбора нескольких зажмите <nobr>клавишу <code>Ctrl</code></nobr> <nobr>или <code>⌘Cmd</code> (на МакОС).</nobr>")."</div>
	
	<script>function check_papka() { 
	if ($('#razdels').val() != '0') { $('.show_papka').show('slow'); 
	izmenapapka($('#razdels').val(), 0, '',0,'papka_in_pole'); }
	else { $('.show_papka').hide('fast'); $('#papki').hide('fast'); }
	}</script>


	</td><td>
	<h2>Выберите тип поля:</h2><select name='s_tip' class='w100 h40 f16' id=s_tip onchange=\"
	if ($('#s_tip').val()==0 || $('#s_tip').val()==7) { $('#spisok_0').html( $('#spisok_1').html() ); }
	if ($('#s_tip').val()==1) { $('#spisok_0').html( $('#spisok_2').html() ); }
	if ($('#s_tip').val()==2) { $('#spisok_0').html( $('#spisok_3').html() ); }
	if ($('#s_tip').val()==4) { $('#spisok_0').html( $('#spisok_5').html() ); }
	if ($('#s_tip').val()==5) { $('#spisok_0').html( $('#spisok_6').html() ); }
	if ($('#s_tip').val()==6) { $('#spisok_0').html( $('#spisok_7').html() ); $('#textarea').hide(); }
	if ($('#s_tip').val()==3) { $('#spisok_0').html( $('#spisok_4').html() ); $('#textarea').hide(); }
	if ($('#s_tip').val()!=3 && $('#s_tip').val()!=6) { $('#textarea').show(); }
	\">
	<option value='0'>список слов (выбор одного значения)</option>
	<option value='7'>список слов (выбор нескольких значений)</option>
	<option value='1'>текст (можно написать шаблон)</option>
	<option value='4'>строка (можно написать шаблон)</option>
	<option value='5'>число (можно написать шаблон)</option>
	<option value='6'>регион (регионы можно включить в настройках)</option>
	<option value='3'>период времени (две даты, актуально для Афиши)</option>
	</select></td></tr>
	<tr><td colspan=2>
	<h2>Содержание или Настройка поля:</h2>".spisok_help()."
	<div id=textarea>
	<textarea name=text rows=10 cols=86 class='w100'></textarea></div>
	<input type=hidden name=id value=''>
	<input type=hidden name=op value=".$admintip."_save>
	</td></tr></table>";
	// <option value='2' disabled>файл (указать какой, куда и что с ним делать)</option>
	break;
	########################################################
	case "base": $type_opis = "базы данных (таблицы)";
	global $lang;
	$another_table_list = "";
	$sql2 = "select `name`,`title`,`text` from ".$prefix."_mainpage where `type`='5' and `tables`='pages'";
	$result2 = $db->sql_query($sql2);
	while ($row2 = $db->sql_fetchrow($result2)) {
		$title = $row2['title'];
		$name = $row2['name'];
		$text = explode("|",$row2['text']); 
	    $text = str_replace($text[0]."|", "", $row2['text']); 
	    parse_str($text);
	    $options = explode("/!/",$options);
	    $n = count($options);
	    $another_table_list .= "<option value='table|".$name."|id'>".$title." &rarr; № (".aa('номер').")</option>";
	    for ($x=0; $x < $n; $x++) {
    		$one = explode("#!#",$options[$x]);
    		$another_table_list .= "<option value='table|".$name."|".$one[0]."'>".$title." &rarr; ".$one[1]."</option>";
    	}
    	$another_table_list .= "<option value='' disabled>———</option>";
		// men#!#фамилии#!#строкабезвариантов#!#1#!#1#!#
		// imena#!#имена#!#строкабезвариантов#!#0#!#1#!#
	}
	$create.="<div id=about class=block style='display:none;'>База данных — это таблица с удобным редактированием (по типу БД, а не электронных таблиц), поиском и фильтрами. Таблица может содержать любые поля и выводиться на страницы сайта или использоваться для внутреннего документооборота компании (доступно через администрирование).</b><br></div>
	
	<input type='hidden' name='type' value='5'>
	<input type='hidden' name='shablon' value=''>
	
	<p><span class=h3>Название:</span><br>
	<input type=text name=title size=30 class='w100 f16' id=name_base autofocus><br>
	<a class=punkt onclick='$(\"#eng_name\").toggle(\"slow\");'>Псевдоним</a> будет создан транслитом.
      <div id=eng_name style='display:none;'>
      <p><span class=h3>Псевдоним:</span><br>
      <input type=text name=namo size=30 class='w100 f16'><br>
      <a href=# onclick=\"window.open('http://translate.google.ru/#".$lang."/en/' + $('#name_base').val(),'Перевод',' width=800,height=400'); return false;\"><b>Перевести название</b></a>. <i>Используются англ. буквы и знак «_», без пробелов. Примеры: «products», «catalog», «shop» и т.д.</i>
      </div>

	<select name='delete_table' class='hide'><option value='1' selected>да</option><option value='0'>нет</option></select>

	<p><span class=h3>База данных будет подключена к одноименному разделу, ...</span><br>
	<select name='s_tip' class='w100 f16' onchange='if (this.value==1) { $(\"#table\").find(\".pole_open\").removeClass(\"hide\"); } else { $(\"#table\").find(\".pole_open\").addClass(\"hide\"); }'><option value='1'>доступному на сайте</option><option value='2' selected>доступному только администратору</option></select>

	<div style='float:right;'><a class='button green small' id=add_pole><span class=\"icon white medium\" data-icon=\"+\"></span> Добавить колонку</a></div>
	
	<p><span class=h2>Поля базы данных (названия колонок):</span><br>
	<a name=pole></a>
	<table id='table' class='w100 mw800 table_light'>
	  <tr valign=bottom>
		<td width=40%>Название поля</td>
		<td width=10%>Тип данных</td>
		<td width=10%>Важность<sup>1</sup></td>
		<td width=15% class='pole_open hide'>Видимость <nobr>на сайте</nobr></td>
		<td width=15%>Замена информации<sup>2</sup></td>
		<td width=5%>Фильтр</td>
		<td width=5%>Убрать</td>
	  </tr>
	<tr id=pole_0><td>
	<input type=text name=pole_rusname[] size=15 class='w100' /><a class=punkt onclick='show_eng(this)'>Псевдоним</a> (англ., будет создан автоматически)<span class='eng hide'>:<br><input type=text name=pole_name[] size=15 class='w100' /></span>
	</td><td>
	<select name=pole_tip[] class='w100'><option value='строка'>Строка с выбором предыдущих значений (до 1000 символов)</option><option value='строкабезвариантов' selected>Строка (до 1000 символов)</option><option value='число'>Число</option><option value='список'>Список (в поле «Замена информации» — слова через запятую)</option><option value='текст'>Текст</option><option value='дата'>Дата</option><option value='датавремя'>Дата/Время</option><option value='фото'>Фото</option><option value='минифото'>МиниФото</option><option value='файл' disabled>Файл</option><option value='ссылка' disabled>Ссылка</option><option value='' disabled>——— Списки из колонок других таблиц ———</option>".$another_table_list."</select></td><td>
	<select name=pole_main[] class='w100'><option value=0>не важно</option><option value=1>1. основная категория</option><option value=2>2. вторичная категория</option><option value=3>обязательно заполнять</option><option value=4>не важно и не печатать</option><option value=6>не важно, не печатать и не показывать</option><option value=7>обязательно, не печатать и не показывать</option><option value=5>пустая для печати</option></select></td>
	<td class='pole_open hide'>
	<select name=pole_open[] class='w100'><option value=1 selected>не видно нигде</option><option value=0>видно в таблице и на странице «Подробнее»</option><option value=2>видно после нажатия «Подробнее»</option></select></td><td>
	<input type=text name='pole_rename[]' size=15 class='w100' /></td><td>

	<select class='pole_filter hide' name='pole_filter[]'><option value='0'>нет</option><option value='1'>да</option></select>
	<a class='button small red white pole_filter_button' onclick='pole_filter(this)'><span class=\"icon  small\" data-icon=\"Q\"></span></a></td><td>
	<a class='button small red white' onclick='del_pole(this)'><span class=\"icon  small\" data-icon=\"F\"></span></a></td></tr>
	</table>
	<div id=id0></div>

	<script>
	var id = 1;
	$(function(){
		$('#add_pole').click(function(){
		  $('#pole_0').clone().attr('id', 'pole_'+id).appendTo('#table');
		  id = id + 1;
		});
	});
	function pole_filter(x) {
		if ($(x).parents('tr').find('.pole_filter').val() == '0') {
			$(x).parents('tr').find('.pole_filter').val('1');
			$(x).parents('tr').find('.pole_filter_button').removeClass('red').addClass('green');
		} else {
			$(x).parents('tr').find('.pole_filter').val('0');
			$(x).parents('tr').find('.pole_filter_button').removeClass('green').addClass('red');
		}
	}
	function show_eng(x) {
		$(x).parents('tr').find('.eng').toggle(); 
	}
	function del_pole(x) {
		if ($(x).parents('tr').attr('id') != 'pole_0') $(x).parents('tr').empty(); 
		else alert('Первая строка не удаляется.');
	}
	</script>

	<table class='hide'><tr valign=top><td><span class=h3>Добавить поля:</span></td><td>
	</td><td>«Голосование» ".select("add_pole_golos", "0,1", "НЕТ,ДА", "0")."
	</td><td>«Комментарии» ".select("add_pole_comm", "0,1", "НЕТ,ДА", "0")."
	</td><td>«Количество проданного товара» ".select("add_pole_kol", "0,1", "НЕТ,ДА", "0")."
	</td></tr></table>

	<p><a onclick=\"$('#excel').toggle();\" class='button small'>Загрузить готовую таблицу (необязательно)</a></p>
	<div style='display:none;' id=excel><div class=block_white2>
	<b>Файл CSV</b> (в формат .CSV можно сохранить таблицу Excel или экспортировать из 1С, формат - utf-8) <br>
	Если файл не выбран - будет создана пустая структура базы данных на основании Названий полей<br>
	<input type=file name='useit' size=30><br>
	<b>Разделение строк:</b> <input type=text name=line_close size=5 value='\\r\\n'> возврат каретки: \\r, символ окончания линии: \\n<br>
	<b>Разделение полей:</b> <select name=line_razdel>
	<option value='##'>##</option>
	<option value=';'>;</option>
	<option value='#'>#</option>
	<option value=':'>:</option>
	<option value=','>,</option>
	</select> для 1С CSV - ##, для Excel CSV - ;<br>
	<b>Экранирование полей:</b> <input type=text name=line_ekran size=5 value='\"'><br>
	<b>Удалить первую строку?</b> ".select("delete_stroka", "0,1", "НЕТ,ДА", "1")." — если первая строка в таблице содержит названия колонок
	</div></div>

	<p><sup>1</sup> <b>«Важность»</b>: основная и вторичная категории выводятся в блоках БД и обязательны к заполнению. <i>Пример: таблица аренды недвижимости. Основная категория — список типов недвижимости (квартира,дом,участок), вторичная — список районов города (Центральный,Кировский,Советский). Блоки могут выводить количество строк в данных категориям.</i>
	<p><sup>2</sup> <b>«Замена информации»</b>: при выборе типа данных «Строка», «Число», «Текст», «Дата», «Дата/Время» или «Ссылка» можно написать текст по-умолчанию.
	<p>Поля № и Активность (включено/выключено) будут добавлены автоматически.
	<p>Для вывода информации по кол-ву записей за сегодня, завтра и послезавтра — назовите англ. поле с датой — data.
	
	<input type=hidden name=id value=''>
	<input type=hidden name=op value=".$admintip."_save>";
	break;
	########################################################
	case "shablon": $type_opis = "шаблона (оформление)";
		$create.="<div id=about class=block style='display:none;'>Шаблоны используются для изменения внешнего вида разделов, страниц и блоков. Используются либо стандартные поля страниц, либо дополнительно созданные Поля. Для страниц можно использовать любой дизайн, блоки и разделы используют табличную основу — начало < table >, соответственно сами шаблоны должны начинаться с < tr > и заканчиваться на < /tr >. Для того, чтобы в шаблоне раздела предусмотреть возможность именования колонок таблицы, например: Дата, Название, Ссылка... после самого шаблона раздела нужна написать ключевое слово [следующий] и написать шаблон именования колонок, т.е. по сути скопировать шаблон строк раздела, но вместо заготовок автоматических вставок поставить в него названия соответствующих полей-колонок.<br></div>
		<table class='w100 mw800'><tr><td width=50%>
		<h2>Название шаблона</h2>
		<input type=text name=title size=40 class='w100 h40 f16' autofocus> (рус.)</td><td>
		<h2>Обращение:</h2>
		<input type=text name=namo size=40 class='w100 h40 f16'> (англ., без пробелов)</td></tr>
		<tr><td colspan=2>
		".help_shablon()."
		<h2>Содержание шаблона (HTML-код и вставки шаблона):</h2>
		<textarea name=text rows=15 cols=80 class='w100' style='height:350px;'></textarea>
		</td></tr></table>
		<input type=hidden name=id value=''>
		<input type=hidden name=type value='6'>
		<input type=hidden name=op value=".$admintip."_save>";
	break;
	}
	echo "<form method='POST' action='sys.php' id='main_save' style='display:inline;' enctype='multipart/form-data'>
	<div class='fon w100 mw800'>
	<div class='black_grad h40'>
	<button type='button' style='float:right;margin:3px;' class='medium orange' onClick=\"show_animate('about')\">?</button>
	<button type='submit' class='small green' style='float:left; margin:3px;'><span class='mr-2 icon white small' data-icon='c'></span>Сохранить</button>
	<span class='h1'>Добавление ".$type_opis."</span></div>".$create."
	</div>
	</form>";
}
############################################################################################
function edit_main($id) {
	global $tip, $admintip, $prefix, $db, $red, $nastroi;
	echo "<div id=podrazdel></div>";
    $sql = "SELECT `type`,`name`,`title`,`text`,`useit`,`shablon`,`description`,`keywords`,`meta_title` FROM ".$prefix."_mainpage where `id`='".$id."'";
    // здесь учитываем и возможность редактирования удаленных и старых версий, поэтому нет «`tables`='pages'»
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$type = $row['type']; 
	$name_all = $name = $row['name']; 
	if (strpos($name, "\n")) { // заменяем имя запароленного раздела
		$name = explode("\n", str_replace("\r", "", $name));
		$name = trim($name[0]);
	}
	$title = $row['title'];
	$text = $row['text'];
	$useit = str_replace("  "," ",$row['useit']);
	$sha = $row['shablon'];
	$descriptionX = $row['description'];
	$keywordsX = $row['keywords'];
	$meta_titleX = $row['meta_title'];
	// Если это раздел
	if ($type == "3") { 
		$useit_module = explode("|",$useit); 
		$useit_module = $useit_module[0]; 
			if ($useit_module != "") {
				$sql = "SELECT `title` FROM ".$prefix."_mainpage where `tables`='pages' and (`name` = '".$useit_module."' or `name` like '".$useit_module." %') and `type`='2'";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$useit_module = trim($row['title']);
			}
	}
	if (!isset($useit_module)) $useit_module = "";
	$color = "#ffffff"; // убрать
	$type_opis = "";

	if (!isset($name)) $name = 0;
	if ($type == "1") $css_true = "true"; else $css_true = "";
	if ($red == 2 || $type == "1") 
		if (($name == "0" || $name == "5" || $name == "6" || $name == "10" || $name == "15") && $type == "3") {
		} else {
			if ($type == 2) $type_pole = "useit"; else $type_pole = "text";
			echo "<div id='photo_upload' class='hide'><h2>Можно не нажимать кнопку «Вставить фото», а сразу переносить файл фотографии в редактор.</h2>".add_file_upload_form("textarea", "", false, $type_pole, $css_true)."</div>";
		}

	echo "<form method='POST' action='sys.php'>
	<input type='hidden' name='type' value='".$type."'><input type='hidden' name='id' value='".$id."'><input type='hidden' name='op' value='".$admintip."_save'>";

	// Определение дизайнов // проверить - возможно заменить на функции из mainfile
	$design_var = array();
	$design_names = array();
	$result2 = $db->sql_query("SELECT `id`, `title` FROM ".$prefix."_mainpage where `tables`='pages' and `type`='0'");
	while ($row2 = $db->sql_fetchrow($result2)) { $design_var[] = $row2['id']; $design_names[] = trim($row2['title']); }
	$design_var = implode(",",$design_var);
	$design_names = implode(",",$design_names);

	// Определение разделов
	$razdel_var = array();
	$razdel_names = array();
	$razdel_name = array();
	$razdel_engname = array();
	$result2 = $db->sql_query("SELECT `id`, `name`, `title` FROM ".$prefix."_mainpage where `tables`='pages' and `name`!='users' and `type`='2' and `name`!='' order by `title`");
	while ($row2 = $db->sql_fetchrow($result2)) { 
		$raz_id = $row2['id'];
		$name2 = $row2['name'];
		if (strpos($name2, "\n")) { // заменяем имя запароленного раздела
			$name2 = explode("\n", str_replace("\r", "", $name2));
			$name2 = trim($name2[0]);
		}
		$razdel_engname[] = $name2; 
		$razdel_var[] = $row2['id']; 
		$razdel_names[$raz_id] = str_replace(","," ",trim($row2['title']));
	}
	$razdel_var = implode(",",$razdel_var);
	$razdel_name = implode(",",$razdel_names);
	$razdel_engname = implode(",",$razdel_engname);
	if (trim($razdel_var) != "") $razdel_var .= ","; else $razdel_var .= "0,"; 
	if (trim($razdel_engname) != "") $razdel_engname .= ","; else $razdel_engname .= "все,"; 
	if (trim($razdel_name) != "") $razdel_name .= ",";  else $razdel_name .= "нет,"; 

	// Определение шаблонов
	$shablon_var = array();
	$shablon_names = array();
	$result2 = $db->sql_query("SELECT id, title FROM ".$prefix."_mainpage where `tables`='pages' and type='6'");
	while ($row2 = $db->sql_fetchrow($result2)) { $shablon_var[] = $row2['id']; $shablon_names[] = trim($row2['title']); }
	$shablon_var = implode(",",$shablon_var);
	$shablon_names = implode(",",$shablon_names);
	if (trim($shablon_var) != "") $shablon_var .= ","; 
	if (trim($shablon_names) != "") $shablon_names .= ","; 

	// Определение баз данных
	$base_var = array("0");
	$base_names = array("нет");
	$result2 = $db->sql_query("SELECT id, title FROM ".$prefix."_mainpage where `tables`='pages' and type='5'");
	while ($row2 = $db->sql_fetchrow($result2)) { $base_var[] = $row2['id']; $base_names[] = trim($row2['title']); }
	$base_var = implode(",",$base_var);
	$base_names = implode(",",$base_names);

	// Определение полей
	$spisok_var = array("");
	$spisok_names = array("нет");
	$result2 = $db->sql_query("SELECT name, title, useit FROM ".$prefix."_mainpage where `tables`='pages' and type='4'");
	while ($row2 = $db->sql_fetchrow($result2)) { 
		if ($row2['useit'] != 0) { 
			$use_it = $row2['useit']; 
			if ( isset($razdel_names[$use_it]) ) $use_it = $razdel_names[$use_it];
			else $use_it = "поле всех разделов";
		} else $use_it = "";
		$spisok_var[] = $row2['name']; 
		$spisok_names[] = trim($row2['title']." (".$use_it.")"); 
	}
	$spisok_var = implode(",",$spisok_var);
	$spisok_names = implode(",",$spisok_names);
	echo "<div class='fon w100 mw800'>
	<div class='black_grad h40'>
	<a class='button small green' onclick='save_main(\"ad/ad-mainpage.php\", \"mainpage_save_ayax\", \"\", \"\")' style='float:left; margin:3px;'><span class='mr-2 icon white small' data-icon='c'></span> Сохранить</a>

	<script type='text/javascript'>
	jQuery(function(){
	 $(\".scroll_on_top\").hide();
	 if ($(window).scrollTop()>=\"250\") $(\".scroll_on_top\").fadeIn(\"slow\")
	 $(window).scroll(function(){
	  if ($(window).scrollTop()<=\"250\") $(\".scroll_on_top\").fadeOut(\"slow\")
	  else $(\".scroll_on_top\").fadeIn(\"slow\")
	 });
	});
	</script>
	<a class='scroll_on_top button medium green' onclick='save_main(\"ad/ad-mainpage.php\", \"mainpage_save_ayax\", \"\", \"\")' style='position:fixed; top:86%; right:0%; left:90%; z-index:1000;'>Сохранить</a>
	<a class='scroll_on_top button medium blue' href='#top' style='position:fixed; top:93%; right:0%; left:90%; z-index:1000;'>&uarr;&nbsp;Наверх</a>

	<div id='save_main' style='float:left; margin:3px;'></div>
	<span class='h1' style='padding-top:10px;'>";

	if ($type == "0") { ############################### ОТКРЫТИЕ ДИЗАЙН
		$useit_all = explode(" ", $useit); // разделение стилей
		$n = count($useit_all);
		$stil = "";
		$styles = "";
		for ($x=0; $x < $n; $x++) { // Определение использованных стилей в дизайне
			$stil .= " ".$useit_all[$x];
		}
			 $sql5 = "SELECT `id`, `title` FROM ".$prefix."_mainpage where `tables`='pages' and `type`='1' order by `title`, `id`";
			 $result5 = $db->sql_query($sql5);
			 while ($row5 = $db->sql_fetchrow($result5)) {
				 $title_id = $row5['id'];
				 $title_style = trim($row5['title']);
				 $sel="";
				 for ($x=0; $x < $n; $x++) {
					if ($useit_all[$x] == $title_id) $sel = " selected='selected'";
				 }
				 $styles .= "<option value='".$title_id."'".$sel.">".$title_style."</option>";
			 }
		global $http_siteurl;
		$stil = str_replace(" ","-",trim($stil));

	echo "Редактирование дизайна (HTML)</span>";
	if (intval($nastroi) != 1) red_vybor();
	echo "</div>

	<h2>Название дизайна</h2>
	<textarea class='big w100 h40 f16' name='title' rows='1' cols='10'>".$title."</textarea>
	".help_design()."
	<h1>Содержание дизайна (HTML):".button_resize_red($red, true)."
	<a class='button small blue' onclick='if ($(\"#color_scheme\").html() == \"\") $(\"#color_scheme\").html(\"<iframe src=http://colorscheme.ru width=985 height=660 scrolling=no frameborder=0></iframe>\"); $(\"#color_scheme\").toggle();'>Подбор цвета</a>
	</h1>
	<div class='hide' style='position:absolute; margin: 0 auto; width: 985px; height:660px; z-index:1000;' id='color_scheme'></div>"; 
  	echo redactor($red, $text, 'text'); // редактор: типа редактора, редактируемое поле

	echo "<span class=h2>Использованные в дизайне стили CSS</span>
	<select name='useit[]' size=6 class='w100' multiple='multiple'>".$styles."</select>
	".aa("Для выбора нескольких зажмите <nobr>клавишу <code>Ctrl</code></nobr> <nobr>или <code>⌘Cmd</code> (на МакОС).</nobr>")."</div>";
	} ############################### ЗАКРЫТИЕ ДИЗАЙН

	if ($type == "1") { ############################### ОТКРЫТИЕ СТИЛЬ
		echo "Редактирование стиля (CSS)</span></div>
		<div class='w95 mw800'>
		<h2>Название стиля <span class=f12>Видит только администратор</span><br>
		<textarea class='big w100 h40 f16' name='title' rows='1' cols='10'>".$title."</textarea></h2>
		<h2>
		Содержание стиля: ".button_resize_red(2, true)."
		<a class='button small blue' onclick='if ($(\"#color_scheme\").html() == \"\") $(\"#color_scheme\").html(\"<iframe src=http://colorscheme.ru width=985 height=660 scrolling=no frameborder=0></iframe>\"); $(\"#color_scheme\").toggle();'>Подбор цвета</a>
		 <a class='button small blue' href='http://sassmeister.com' target='_blank'>SASS > CSS</a>
		 <a class='button small blue' href='http://less2css.org' target='_blank'>LESS > CSS</a>
		</h2>
	<div class='hide' style='position:absolute; margin: 0 auto; width: 985px; height:660px; z-index:1000;' id='color_scheme'></div>";
		echo redactor('2', $text, 'text', '', 'css');
		echo "<input type='hidden' name='namo' value='".$name."'></div>";
	} ############################### ЗАКРЫТИЕ СТИЛЬ

	if ($type == "2") { ############################### ОТКРЫТИЕ РАЗДЕЛА

	####################################################################
	if ($nastroi == 1) { // начало редактирования настроек раздела
		// выделим имени модуля раздела и настройки
		$options = explode("|",$text);
		$module_name = $options[0];
		$options = str_replace($module_name."|","",$text);
		// обнулили все опции
		$media = $folder = $col = $view = $golos = $golosrazdel = $post = $comments = $datashow = $favorites = $socialnetwork = $search = $search_papka = $put_in_blog = $base = $vetki = $citata = $media_comment = $no_html_in_opentext = $no_html_in_text = $show_add_post_on_first_page = $media_post = $razdel_shablon = $page_shablon = $comments_all = $comments_num = $comments_mail = $comments_adres = $comments_tel = $comments_desc = $golostype = $pagenumbers = $comments_main = $tags_type = $pagekol = $table_light = $designpages = $comments_add = $papka_show = $add_post_to_mainpage = $design_tablet = $designpages_tablet = $design_phone = $designpages_phone = $edit_pole = $show_tags_pages = $golos_admin = $comment_all_link = $show_read_all = 0;
		$menushow = $titleshow = $razdeltitleshow = $razdel_link = $peopleshow = $design = $tags = $podrobno = $podrazdel_active_show = $podrazdel_show = $tipograf = $limkol = $tags_show = $tema_zapret = $tema_zapret_comm = $opentextshow = $maintextshow = $papka_tags_pages = $razdel_tags_pages = $div_or_table = 1;
		$comment_all_link_text = "читать полностью";
		$comm_col_letters = "1000";
		$comment_shablon = 2;
		$col_tags_pages = 5;
		$lim = 20;
		$where = $order = $calendar = $reclama = "";
		$comm_show_one_more = 3;
	    $comm_show_one_more_text = "Показать еще";
		$sort = "date desc";
		$tema = "Открыть новую тему";
		$tema_name = "Ваше имя";
		$tema_title = "Название темы";
		$tema_opis = "Подробнее (содержание темы)";
		$comments_1 = "Комментарии";
		$comments_2 = "Оставьте ваш вопрос или комментарий:";
		$comments_3 = "Ваше имя:";
		$comments_4 = "Ваш email:";
		$comments_5 = "Ваш адрес:";
		$comments_6 = "Ваш телефон:";
		$comments_7 = "Ваш вопрос или комментарий:";
		$comments_8 = "Показать все";
		$tag_text_show = "Ключевые слова";
		$read_all = "Читать далее...";
		$reiting_data = "Дата написания отзыва";
		$text_tags_pages = "См. также:";

		parse_str($options); // раскладка всех настроек раздела

		echo "
		".input("nastroi", "1", "1", "hidden")."
		".input("module_name", $module_name, "1", "hidden")."

		Настройка раздела «".trim($title)."»</span></div>
		<p>Для настройки раздела внимательно прочитайте опции и выберите соответствующие варианты.</p>

		<a class='dark_pole align_center' onclick=\"show_animate('block1');\"><h2>Дизайн (обрамление страниц)</h2> 
		</a><div id=block1 style='display: none;'>
		<table  class='w100 mw800 table_light'>
		<tr class='v_bottom'>
		<td><b>Общий дизайн:</b> <a class='button small' href='sys.php?op=mainpage&amp;name=design' target='_blank'>Добавить</a>
		<li>для раздела (по умолчанию - Главный дизайн)
		<li>для страниц (по умолчанию - дизайн раздела)</td>
		<td>
		<p>".select("options[design]", $design_var, $design_names, $design)."
		<p>".select("options[designpages]", "0,".$design_var, "— как у Общего дизайна раздела,".$design_names, $designpages)."</td></tr>
		<tr class='v_bottom'>
		<td><b>Дизайн для планшетов:</b>
		<li>для раздела
		<li>для страниц</td>
		<td>
		<p>".select("options[design_tablet]", "0,".$design_var, "— как у Общего дизайна раздела,".$design_names, $design_tablet)."
		<p>".select("options[designpages_tablet]", "0,".$design_var, "— как у Общего дизайна раздела,".$design_names, $designpages_tablet)."</td></tr>
		<tr class='v_bottom'>
		<td><b>Дизайн для смартфонов:</b>
		<li>для раздела
		<li>для страниц</td>
		<td>
		<p>".select("options[design_phone]", "0,".$design_var, "— как у Общего дизайна раздела,".$design_names, $design_phone)."
		<p>".select("options[designpages_phone]", "0,".$design_var, "— как у Общего дизайна раздела,".$design_names, $designpages_phone)."</td></tr>

		</table>
		</div>

		<a class='dark_pole align_center' onclick=\"show_animate('block3');\"><h2>Шаблоны (расположение и наличие элементов)</h2>
		</a><div id=block3 style='display: none;'>
		<table  class='w100 mw800 table_light'>
		<tr>
		<tr><td><b>Шаблон для списка страниц</b> (вывод заголовков страниц и т.д.)</td>
		<td>".select("options[razdel_shablon]", $shablon_var."0", $shablon_names."без шаблона", $razdel_shablon)."</td></tr>
		<tr><td><b>Формирование списка страниц</b> в разделе и папках. Разделение отдельных страниц</td>
		<td>".select("options[div_or_table]", "1,0", "Безтабличное DIV,Табличное TABLE TR TD", $div_or_table)."</td></tr>
		<tr><td><b>Тип раздела:</b><br>
		<i class=red>Для типа раздела «Анкеты-рейтинги» необходимо выбрать в настройках ниже возможность комментировать и голосовать за страницы.<br>
		Для типа раздела «Форум» необходимо выбрать в настройках «Раздел и папки» сортировку страниц «для Форума»</i></td>
		<td>".select("options[view]", "4,1,6,0", "Анкеты-рейтинги,Форум,Папки на Главной,Страницы на Главной", $view)."</td></tr>
		<td><b>Шаблон для комментариев</b> на странице  [<a href='sys.php?op=mainpage&amp;name=shablon' target='_blank'>Добавить</a>]</td>
		<td>".select("options[comment_shablon]", $shablon_var."3,2,1,0", $shablon_names."ДвижОк: аля ХабраХабр,ДвижОк: ArtistStyle,ДвижОк: диалоговый аля Joomla,ДвижОк: стандартный", $comment_shablon)."</td></tr>
		<tr><td><b>Шаблон для страницы</b></td>
		<td>".select("options[page_shablon]", $shablon_var."0", $shablon_names."без шаблона", $page_shablon)."</td></tr>
		</table>
		</div>


		<a class='dark_pole align_center' onclick=\"show_animate('block5');\"><h2>Раздел и папки</h2>
		</a><div id=block5 style='display: none;'>
		<table  class='w100 mw800 table_light'>
		<tr><td>Количество колонок (столбцов) в списке страниц, выводимом в папках и начале раздела, <nobr>по-умолчанию: 1.</nobr></td>
		<td>".select("options[limkol]", "1,2,3,4,5,6,7,8,9,10", "1,2,3,4,5,6,7,8,9,10", $limkol)."</td></tr>
		<tr><td><strong>Количество строк</strong> в списке страниц, выводимом в папках и начале раздела, <nobr>по-умолчанию: 20.</nobr></td>
		<td>".select("options[lim]", "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,20,25,30,50,75,100,200,500,1000", "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,20,25,30,50,75,100,200,500,1000", $lim)."</td></tr>
		<tr><td>Выводить количество страниц в папках, <nobr>по-умолчанию: нет.</nobr></td>
		<td>".select("options[pagekol]", "1,0", "ДА,НЕТ", $pagekol)."</td></tr>
		<tr><td><strong>Выводить нумерацию страниц</strong>, <nobr>по-умолчанию: снизу.</nobr></td>
		<td>".select("options[pagenumbers]", "0,1,2", "снизу,сверху,блок [нумерация] в дизайне или разделе", $pagenumbers)."</td></tr>
		<tr><td><strong>Сортировка страниц</strong> в списке</td>
		<td>".select("options[sort]", "sort[|]date desc,date desc,date,title,open_text,counter,golos desc,comm,cid[|]title,cid[|]date desc,mainpage desc,search desc,pid,open_text[|] golos,price,prise desc", "по очередности (настраивается),по дате (с последнего),по дате (с первого),по названию (по алфавиту),по предисловию (по алфавиту),по кол-ву посещений страницы,по среднему баллу голосования,по кол-ву комментариев,по № папки и названию,для типа «Форум» (по № папки и дате), по важности (Главная стр.),по наличию ключ. слов,по № страницы,по предисловию и голосованию,магазин: по цене (с мин.), магазин: по цене (с макс.)", $sort)."</td></tr>
		<tr><td>Вставка между Названием раздела и списком страниц. Можно написать HTML-код или вставить любой [блок]. Не использовать символ &</td>
		<td>".input("options[reclama]", $reclama, 60, "txt")."</td></tr>
		<tr><td>Выводить описание папки:</td>
		<td>".select("options[papka_show]", "1,0", "снизу,сверху", $papka_show)."</td></tr>
		<tr><td>Добавление строки поиска в раздел (поиск по этому разделу)</td>
		<td>".select("options[search]", "2,1,0", "снизу,сверху,нет", $search)."</td></tr>
		<tr><td>Дополнительно: Внутренний поиск раздела будет искать</td>
		<td>".select("options[search_papka]", "1,0", "только по открытой папке раздела,по всему разделу", $search_papka)."</td></tr>
		<tr><td>Показывать название раздела, папки и выбор подпапок</td>
		<td>".select("options[menushow]", "1,0", "ДА,НЕТ", $menushow)."</td></tr>
		<tr><td>Название раздела — </td>
		<td>".select("options[razdel_link]", "2,1,0", "не показывать,это ссылка на раздел,без ссылки", $razdel_link)."</td></tr>
		<tr><td>Показывать список папок открытого раздела или папки</td>
		<td>".select("options[podrazdel_show]", "3,2,1,0", "сверху и снизу,снизу (не реализовано!),сверху,нет", $podrazdel_show)."</td></tr>
		<tr><td>Показывать название открытой папки на страницах</td>
		<td>".select("options[podrazdel_active_show]", "3,2,4,1,0", "раскрывающийся список папок,выделять из списка подразделов,после названия раздела (разделитель Enter),после названия раздела (разделитель | ),нет", $podrazdel_active_show)."</td></tr>
		</table>
		</div>



		<a class='dark_pole align_center' onclick=\"show_animate('block6');\"><h2>Ключевые слова (тэги)</h2>
		</a><div id=block6 style='display: none;'>
		<table  class='w100 mw800 table_light'>
		<tr><td><strong>Показывать ".input("options[tag_text_show]",$tag_text_show)." </strong></td>
		<td>".select("options[tags]", "3,2,5,6,4,7,1,0", "ВЕЗДЕ,в разделе и папках,в разделе (в разработке),в разделе и на страницах (в разработке),в папках (в разработке),в папках и на страницах (в разработке),на страницах,НИГДЕ", $tags)."</td></tr>
		<tr><td><strong>Облако ключевых слов в разделе</strong> (наверху, после названия)</td>
		<td>".select("options[tags_type]", "3,2,1,0", "ИЗ текущей папки раздела,ИЗ текущего раздела,ИЗ всех разделов портала,НЕ ПОКАЗЫВАТЬ", $tags_type)."</td></tr>
		<tr><td>Облако ключевых слов в разделе показывать раскрытым</td>
		<td>".select("options[tags_show]", "1,0", "ДА,НЕТ", $tags_show)."</td></tr>
		</table>
		</div>


		<a class='dark_pole align_center' onclick=\"show_animate('block7');\"><h2>Рейтинг</h2>
		</a><div id=block7 style='display: none;'>
		<table  class='w100 mw800 table_light'>
		<tr><td>Показывать рейтинг на страницах</td>
		<td>".select("options[golos]", "1,0", "ДА,НЕТ", $golos)."</td></tr>
		<tr><td>Показывать рейтинг в разделе (в списке страниц)</td>
		<td>".select("options[golosrazdel]", "1,0", "ДА,НЕТ", $golosrazdel)."</td></tr>
		<tr><td><strong>Тип рейтинга</strong></td>
		<td>".select("options[golostype]", "4,5,6,0,1,2,3", "Оценка (3 балла),Оценка (5 баллов),Оценка (10 баллов),Оценка (5 звезд),Кнопка «Проголосовать»,Рейтинг (кнопки + и -),Рейтинг (понравилось/не понравилось)", $golostype)."</td></tr>
		<tr><td>Рейтинг редактирует Администратор, т.е. посетители сайта не могут влиять на рейтинг. Если ранее рейтинг был включен без этой опции — все набранные посетителями значения будут обнулены и всем страницам нужно будет заново проставить рейтинг, поэтому обычно эту опцию следует включать для только что созданных разделов.</td>
		<td>".select("options[golos_admin]", "1,0", "ДА,НЕТ", $golos_admin)."</td></tr>
		</table>
		</div>


		<a class='dark_pole align_center' onclick=\"show_animate('block8');\"><h2>Страницы в разделе</h2>
		</a><div id=block8 style='display: none;'>
		<table class='w100 mw800 table_light'>
		<tr><td>Показывать Название раздела</td>
		<td>".select("options[razdeltitleshow]", "1,0", "ДА,НЕТ", $razdeltitleshow)."</td></tr>
		<tr><td>Показывать Название страницы</td>
		<td>".select("options[titleshow]", "1,0", "ДА,НЕТ", $titleshow)."</td></tr>
		<tr><td>Показывать Предисловие страницы</td>
		<td>".select("options[opentextshow]", "1,0", "ДА,НЕТ", $opentextshow)."</td></tr>
		<tr><td>Показывать Содержание страницы</td>
		<td>".select("options[maintextshow]", "1,0", "ДА,НЕТ", $maintextshow)."</td></tr>
		<tr>
		<tr><td>Показывать в разделе/папке в предисловии ссылку ".input("options[read_all]", $read_all)." на страницу.<br>
		Если отключить автоматическую вставку ссылок — можно поставить их вручную с помощью блока [ссылка].<br>
		Также можно использовать другие обозначения: &lt;cut&gt;, &lt;!--more--&gt; и &lt;hr class=\"editor_cut\"&gt;<br>
		В случае последующего включения автоматических ссылок, все поставленные вручную не будут показаны.</td>
		<td>".select("options[show_read_all]", "1,0", "ДА,НЕТ", $show_read_all)."</td></tr>
		<tr><td><strong>Показывать Дату создания</strong></td>
		<td>".select("options[datashow]", "1,0", "ДА,НЕТ", $datashow)."</td></tr>
		<tr><td><strong>Показывать Количество посещений</strong></td>
		<td>".select("options[peopleshow]", "0,1", "НЕТ,ДА", $peopleshow)."</td></tr>
		<tr><td>Показывать похожие страницы ".input("options[text_tags_pages]", $text_tags_pages, 40)." <p>
		Количество страниц ".input("options[col_tags_pages]", $col_tags_pages, 3).".<p>
		<b>Вывод страниц основан на одинаковых тегах</b> (ключевых словах). Включать их отображение на странице необязательно, но заполнять данные поля в дополнительных настройках при редактировании страницы обязательно.<p>
		Показывать похожие страницы только из этой же папки (если нет — со всего раздела) ".select("options[papka_tags_pages]", "0,1", "НЕТ,ДА", $papka_tags_pages)."<br><p><br>
		Показывать похожие страницы только из этого раздела (если нет — со всех разделов) ".select("options[razdel_tags_pages]", "0,1", "НЕТ,ДА", $razdel_tags_pages)."</td>
		<td>".select("options[show_tags_pages]", "0,1", "НЕТ,ДА", $show_tags_pages)."</td></tr>
		<tr><td><strong>Показывать Добавление страниц в Интернет-закладки</strong></td>
		<td>".select("options[favorites]", "1,0", "ДА,НЕТ", $favorites)."</td></tr>
		<tr><td><strong>Показывать Добавление страниц в Социальные сети</strong> (Вконтакте, Гугл+, МойМир и т.д.)</td>
		<td>".select("options[socialnetwork]", "1,0", "ДА,НЕТ", $socialnetwork)."</td></tr>
		<tr><td>Показывать Код для вставки в блоги (Название и Предисловие страницы со ссылкой на сайт). При выборе варианта «с логотипом» небольшой логотип (small_logo.jpg) нужно разместить в корне сайта. Проще всего это будет сделать администратору (если у вас нет доступа к FTP серверу и достаточных знаний).</td>
		<td>".select("options[put_in_blog]", "2,1,0", "с логотипом,без логотипа,НЕТ", $put_in_blog)."</td></tr>
		<tr><td>Очищать предисловие от HTML-кода (оставить обычный текст)</td>
		<td>".select("options[no_html_in_opentext]", "1,0", "ДА,НЕТ", $no_html_in_opentext)."</td></tr>
		<tr><td>Очищать содержание от HTML-кода (оставить обычный текст)</td>
		<td>".select("options[no_html_in_text]", "1,0", "ДА,НЕТ", $no_html_in_text)."</td></tr>
		<tr><td><b>Преобразовывать таблицы</b> в «красивые» (добавлять class «table_light»)</td>
		<td>".select("options[table_light]", "1,0", "ДА,НЕТ", $table_light)."</td></tr>
		<tr><td>При сохранении использовать типограф для Содержания и Предисловия</td>
		<td>".select("options[tipograf]", "1,0", "ДА,НЕТ", $tipograf)."</td></tr>
		</table>
		</div>


		<a class='dark_pole align_center' onclick=\"show_animate('block9');\"><h2>Комментарии</h2>
		</a><div id=block9 style='display: none;'>
		<table  class='w100 mw800 table_light'>
		<tr><td><b>Показывать</b> ".input("options[comments_1]", $comments_1)." на страницах</td>
		<td>".select("options[comments]", "0,1", "НЕТ,ДА", $comments)."</td></tr>
		<tr><td>Показывать на Главной странице Раздела</td>
		<td>".select("options[comments_main]", "1,0", "ДА,НЕТ", $comments_main)."</td></tr>
		<tr><td><b>Разрешить пользователям Добавлять комментарии на страницы</b></td>
		<td>".select("options[comments_add]", "0,2,1", "НЕТ,с проверкой администратора,без проверки", $comments_add)."</td></tr>
		<tr><td><b>Система комментариев:</b><br>
		<li>Линейная система — на первый взгляд помогает комментаторам держаться темы, мешает вести одновременно несколько бесед, хорошо воспринимается зрительно, но при попытке людей создать дискуссию - сильно усложняет её понимание.
		<li>Древовидная система — помогает комментаторам развивать в ветках разные темы, провоцирует комментаторов на ведение споров, затрудняет последовательное чтение комментариев, но облегчает понимание дискуссий, повышает количество комментариев и время на сайте.
		<li>Гибридная система — Линейная система, раскрывающаяся в Древовидную за счет кнопки «Раскрыть все». Также под каждым комментарием с ответом есть кнопка «Показать ответ».
		</td>
		<td>".select("options[vetki]", "2,1,0", "Древовидная система,Гибриная система,Линейная система", $vetki)."</td></tr>
		<tr><td>Направление комментариев по дате:</td>
		<td>".select("options[comments_desc]", "1,0", "сначала последние (новые),сначала первые (старые)", $comments_desc)."</td></tr>
		<tr><td>Показывать поле ".input("options[comments_4]", $comments_4)." </td>
		<td>".select("options[comments_mail]", "3,2,1,0", "везде,на странице в комментариях,в форме добавления комментария,НЕТ", $comments_mail)."</td></tr>
		<tr><td>Показывать ссылку, разворачивающую полный текст комментария ".input("options[comment_all_link_text]", $comment_all_link_text)."<br>
		Количество букв, до которых будет сокращен комментарий ".input("options[comm_col_letters]", $comm_col_letters, "7", "number")."</td>
		<td>".select("options[comment_all_link]", "1,0", "ДА,НЕТ", $comment_all_link)."</td></tr>

		<tr><td>Показывать поле ".input("options[comments_5]", $comments_5)." </td>
		<td>".select("options[comments_adres]", "3,2,1,0", "везде,на странице в комментариях,в форме добавления комментария,НЕТ", $comments_adres)."</td></tr>
		<tr><td>Показывать поле ".input("options[comments_6]", $comments_6)." </td>
		<td>".select("options[comments_tel]", "3,2,1,0", "везде,на странице в комментариях,в форме добавления комментария,НЕТ", $comments_tel)."</td></tr>
		<tr><td><b>Включить визуальный редактор</b> в форме добавления комментария.
		<br>Кнопки редактора: жирность, наклон, зачеркнуто, добавить картинку, видео, файл и ссылку.
		<br>Запрешенные форматы файлов: exe, php, js, html и xml</td>
		<td>".select("options[media_comment]", "1,0", "ДА,НЕТ", $media_comment)."</td></tr>
		<tr><td>Можно заменить надпись «Оставьте ваш вопрос или комментарий:» на:</td>
		<td>".input("options[comments_2]", $comments_2)."</td></tr>
		<tr><td>Можно заменить надпись «Ваше имя:» на:</td>
		<td>".input("options[comments_3]", $comments_3)."</td></tr>
		<tr><td>Можно заменить надпись «Ваш вопрос или комментарий:» на:</td>
		<td>".input("options[comments_7]", $comments_7)."</td></tr>
		<tr><td><b>Запретить добавление ссылок</b> в комментариях</td>
		<td>".select("options[tema_zapret_comm]", "1,0", "НЕТ,ЕСТЬ", $tema_zapret_comm)."</td></tr>
		<tr><td>Если выбран тип раздела «Анкеты-рейтинги» Дата написания отзыва (пример: для отзывов о роддомах - дата родов, дата посещения и т.д.):</td>
		<td>".input("options[reiting_data]", $reiting_data)."</td></tr>
		<tr><td>Количество выводимых комментариев</td>
		<td>".select("options[comments_num]", "0,1,2,3,4,5,10,15,20,25,30,50,75,100,200,500,1000", "выводить все,1,2,3,4,5,10,15,20,25,30,50,75,100,200,500,1000", $comments_num)."</td></tr>
		<tr>
		<td colspan='2'><h3>Если выводятся не все комментарии:</h3></td></tr>
		<tr><td>Показывать кнопку ".input("options[comments_8]", $comments_8)."</td>
		<td>".select("options[comments_all]", "1,0", "ДА,НЕТ", $comments_all)."</td></tr>
		<tr><td>Показывать кнопку ".input("options[comm_show_one_more_text]", $comm_show_one_more_text)." или ссылки на страницы с комментариями</td>
		<td>".select("options[comm_show_one_more]", "0,1,2,3", "ничего не показывать,«Показать еще» — откроются под предыдущими комментариями,«Показать еще» — откроются вместо предыдущих комментариев,ссылки на страницы с комментариями 1 2 3 4 >", $comm_show_one_more)."</td></tr>
		</table>
		</div>


		<a class='dark_pole align_center' onclick=\"show_animate('block10');\"><h2>Добавление страниц пользователем</h2>
		</a><div id=block10 style='display: none;'>
		<table  class='w100 mw800 table_light'>
		<tr><td><b>Разрешить пользователям добавлять страницы</b> в раздел.<br>Выделение серым цветом позволяет сразу писать комментарии к добавленной странице, что особенно актуально, если выбран тип раздела «Анкеты-рейтинги».</td>
		<td>".select("options[post]", "3,2,1,0", "с выделением серым цветом,с проверкой администратора,без проверки,ЗАПРЕТИТЬ", $post)."</td></tr>
		<tr><td><b>Показывать на главной</b> странице раздела Добавление страницы.</td>
		<td>".select("options[show_add_post_on_first_page]", "1,0", "ДА,НЕТ", $show_add_post_on_first_page)."</td></tr>
		<tr><td><b>Показывать добавление страницы в корень раздела.</td>
		<td>".select("options[add_post_to_mainpage]", "1,0", "ДА,НЕТ", $add_post_to_mainpage)."</td></tr>
		<tr><td><b>Включить визуальный редактор</b> в форме добавления страницы.
		<br>Кнопки редактора: жирность, наклон, зачеркнуто, добавить картинку, видео, файл и ссылку.
		<br>Запрешенные форматы файлов: exe, php, js, html и xml</td>
		<td>".select("options[media_post]", "1,0", "ДА,НЕТ", $media_post)."</td></tr>
		<tr><td>Замена названия кнопки «Открыть новую тему». Например: «Добавить новость», «Разместить статью», «Добавить фото», «Добавить организацию».</td>
		<td>".input("options[tema]", $tema)."</td></tr>
		<tr><td>Замена названия текстового поля «Ваше имя». Например: «Ваш ник», «Ваши Ф.И.О.», «Автор сообщения», «Адрес». Если написать «no» - это поле не будет отображаться.</td>
		<td>".input("options[tema_name]", $tema_name)."</td></tr>
		<tr><td>Замена названия текстового поля «Название темы». Например: «Заголовок статьи», «Название фото», «Название и/или № организации».</td>
		<td>".input("options[tema_title]", $tema_title)."</td></tr>
		<tr><td>Замена названия текстового поля «Подробнее (содержание темы)». Например: «Описание фото», «Текст статьи», «Сообщение». Если написать «no» - это поле не будет отображаться.</td>
		<td>".input("options[tema_opis]", $tema_opis)."</td></tr>
		<tr><td><b>Запретить добавление ссылок</b> в тексте добавляемой страницы</td>
		<td>".select("options[tema_zapret]", "1,0", "НЕТ,ЕСТЬ", $tema_zapret)."</td></tr>
		</table>
		</div>


		<a class='dark_pole align_center' onclick=\"show_animate('block4');\"><h2>Дополнительные блоки</h2>
		</a><div id=block4 style='display: none;'>
		<table  class='w100 mw800 table_light'>
		<tr><td>Добавление блока Календарь. Выберите поле, подключенное к данному разделу и содержащее период времени (две даты).</td>
		<td>".select("options[calendar]", $spisok_var, $spisok_names, $calendar)."</td></tr>
		</table>
		</div>


		<a class='dark_pole align_center' onclick=\"show_animate('block2');\"><h2>Подключение базы данных вместо страниц</h2>
		</a><div id=block2 style='display: none;'>
		<table  class='w100 mw800 table_light'>
		<tr><td><b>Подключить базу данных</b> вместо страниц и папок [<a href='sys.php?op=mainpage&amp;name=base' target='_blank'>Добавить</a>]</td>
		<td>".select("options[base]", $base_var, $base_names, $base)."</td></tr>
		<tr><td>Выводить ссылку Подробнее... </td>
		<td>".select("options[podrobno]", "1,0", "ДА,НЕТ", $podrobno)."</td></tr>
		</table>
		</div>


		<a class='dark_pole align_center' onclick=\"show_animate('block12');\"><h2>Администрирование раздела</h2>
		</a><div id=block12 style='display: none;'>
		<table  class='w100 mw800 table_light'>
		<tr><td>Выводить редактирование полей при раскрытии функций страницы на Главной администрирования</td>
		<td>".select("options[edit_pole]", "1,0", "ДА,НЕТ", $edit_pole)."</td></tr>
		</table>
		</div>

		<p class='red'>В текстовых полях нельзя писать символ &</p>";
	######################################################################
	} else { // конец редактирования настроек раздела
	// начало редактирования раздела
		echo "Редактирование раздела</span>";
		if (intval($nastroi) != 1) red_vybor();
		global $clean_urls;
		switch($clean_urls) {
			case 1: $chpu = "<b>ЧПУ: транслит названия страницы.</b> Ссылка на раздел: <a href='/".$name."/' target='_blank'>".$name."/</a>"; break;
			case 2: $chpu = "<b>ЧПУ: название страницы.</b> Ссылка на раздел: <a href='/".$name."/' target='_blank'>".$name."/</a>"; break;
			default: $chpu = "<b>ЧПУ выключено.</b> Ссылка на раздел: <a href='/-".$name."' target='_blank'>-".$name."</a>"; break;
		}
		echo "</div>
		<table width='100%' border='0'><tr valign='top'><td width='50%'>
		<h2>Название раздела</h2>
		<textarea class='big w100 h40 f16' name='title' rows='2' cols='10'>".$title."</textarea><br>
		<a class='button blue' onclick='$(\".meta\").toggle();'>SEO настройки</a>
		</td><td>
		<h2>Адрес раздела на сайте</h2>
		<textarea class='big w100 h40 f16' name='namo' rows='1' cols='10'>".$name_all."</textarea>
		<span class=f12>".$chpu."<br>Не изменять, если созданы папки/страницы!</span> 
		</td></tr>
		</table>
		<table width='100%' border='0'><tr valign='top' class='hide meta'><td width='40%'>
		<h3>Замена TITLE: <a onclick=\"show('help17')\" class='help'>?</a></h3><textarea name='meta_titleX' class='big w100 h40' rows='2' cols='10'>".$meta_titleX."</textarea>
		<div id='help17' style='display:none;'><br><span class=small>По-умолчанию — пустое поле и TITLE будет создан автоматически: «название раздела». Иногда бывает нужно сделать TITLE страницы отличным от её названия.</span></div>
		</td><td width='30%'>
		<h3>Ключевые слова: <span class=f12><a onclick=\"show('help5')\" class=help>?</a></span></h3><textarea name='keywordsX' class='big w100 h40' rows='2' cols='10'>".$keywordsX."</textarea>
		<div id='help5' style='display:none;' class=f12>Это поле keywords для поисковых систем. Максимум 250 символов. Разделять запятой. Если пусто - используются ключевые словосочетания из <a href='/sys.php?op=options' target='_blank'>Настроек портала</a>).<br></div>
		</td><td>
		<h3>Описание: <span class=f12><a onclick=\"show('help6')\" class=help>?</a></span></h3><textarea name='descriptionX' class='big w100 h40' rows='2' cols='10'>".$descriptionX."</textarea>
		<div id='help6' style='display:none;' class=f12>Это поле description для поисковых систем. Максимум 250 символов. Если пусто - используется основное описание из <a href='/sys.php?op=options' target='_blank'>Настроек портала</a>.</div>
		</td></tr>
		</table>

		<div class='right3'>Проверка текста: 
		  <a href='http://orthography.morphology.ru' target='_blank' class='button small blue'>орфография</a> 
		  <a href='http://speller.yandex.net/speller/1.0/index.html' target='_blank' class='button small blue'>орфография 2</a> 
		  <a href='http://test-the-text.ru' target='_blank' class='button small blue'>информационный стиль</a></div>
		<h1>Содержание раздела: ".button_resize_red($red, true)."</h1>";
	  	echo redactor($red, $useit, 'useit', 'shablon'); // редактор: типа редактора, редактируемое поле

		echo "<input type='hidden' name='text' value='".$text."'>";
		if ($id != 24) {
			echo "<tr><td colspan='2'>
			<a class='dark_pole' onClick='$(\"#shablon_show\").toggle(\"slow\")'><span class=\"icon gray small\" data-icon=\"p\"></span>
			Предисловие и Содержание страниц по-умолчанию. Заготовка, предлагаемая при создании страницы</a>

			<div id='shablon_show' class='hide'>".close_button("shablon_show")."Если у большинства страниц раздела особенный дизайн или какое-то первоначальное содержание для всех страниц раздела одинаково — его можно прописать ниже как заготовку для страниц. Сначала идет заготовка для Предисловия страниц, затем — для Содержания, разделяются они служебным словом [следующий]. Если нужна только заготовка для Предисловия - слово [следующий] можно вообще не писать, а если нужна только заготовка для Содержания - слово [следующий] надо написать перед ней.<br>Пример: 111[следующий]222 — при создании страницы вы увидите в её Предисловии текст 111, а в Содержании — 222.";
			echo redactor2($red, $sha, 'shablon');
			echo "</div>";
		} else {
			echo "<input type='hidden' name='shablon' value='".$sha."'>";
		}
	} // конец редактирования раздела
} ############################### ЗАКРЫТИЕ РАЗДЕЛА

	if ($type == "3") { ############################### ОТКРЫТИЕ БЛОК
	global $nastroi;

	// выделим имени модуля раздела и настройки
	if (mb_substr($useit, 0, 1) != "|") {
		$options = explode("|",$useit);
		$module_name = $options[0];
		$options = str_replace($module_name."|","",$useit);
	} else {
		$options = mb_substr($useit, 1, mb_strlen($useit)-1);
		$module_name = "";
	}

	// обнулили все опции
	$titleshow = $reload_one_by_one = $folder = $datashow = $tagdelete = $ipdatauser = $design = $open_all = $catshow = $main = $daleeshow = $openshow = $number = $add = $size = $papki_numbers = $zagolovokin = $menu = $noli = $show_title = $random = $showlinks = $open_new_window = $shablon = $show_new_pages = $reload_link_show = $reload_link_time = $reload_link_on_start = $show_pages_from = $calendar_future = $calendar_years = $re_menu_type = $must_have_foto_adres = $papki_in_razdel_show = $papki_in_razdel_punkt = $papki_in_papki_show = $papki_in_papki_punkt = $papki_in_pages_show = $papki_in_pages_punkt = 0;
	$opros_type = $limkol = $pageshow = $only_question = $opros_result = $foto_gallery_type = $re_menu = $notitlelink = $foto_num = $papki_in_papki_check = $papki_in_pages_check = 1;
	$noli_razdelitel = "<br>";
	$col_bukv = 50;
	$img_width = 0;
	$img_height = 200;
	$size = 10;
	$class = $alternative_title_link = $cid_open = $no_show_in_razdel = $watermark = $show_in_papka = "";
	$calendar = ""; // Календарь - перенаправление на дату из поля.
	$addtitle = "Добавить статью";
	$dal = "Далее...";
	$first = "src=";
	$second = ">";
	$third = " ";
	$sort = "date desc";
	if ($name == 5) $titleshow = 1; // заголовок для блока голосования - показывать
	if ($name == 4) $sort = "sort, title"; // сортировка по алфавиту для блока папок
	$papka_sort = "sort, title";
	$razdel_open_name = $razdel_open2_name = "Открыть раздел";
	$show_in_razdel = "все";
	$reload_link_text = "Показать еще...";
	// Для базы данных
	$base = ""; // Указываем название таблицы БД
	$first = ""; // первая колонка
	$second = ""; // вторая колонка
	$text1 = ""; // текст самой первой ячейки (для блока БД количество по 2 колонкам)
	$direct = "vert"; // направление, gor - горизонт., vert - вертикальное
	$all = 0; // Указывать сколько всего элементов, по умолчанию 0 - не указывать, 1 - указывать.
	$col = ""; // какие поля будут использоваться для вывода информации

	// для блока расписания
	$specialist = "Специалист";
	$work_time = "Время приема";
	$record = "Записаться на ";
	$all_days = "1";
	$tomorrow_record = "На сегодня запись окончена. Вы можете записаться на другой день.";
	$numberOfMonths = 2;
	$calendar_maxDate_days = 30;
	$calendar_maxDate = 1;
	$next_day = 0;
	$end_hour = 16; // Час начала записи на следующий день и окончания на текущий.
	$show_end_hour = 1;
	$current_day = 0;
	$zapis = "";
	$zapis_na_priem = "Запись на прием";
	$zapis_obrashenie = "Укажите, пожалуйста, телефон, по которому в ближайшее время с вами могут связаться администраторы нашего медицинского центра для подтверждения записи на прием.";
	$zapis_your_name = "Ваше имя:";
	$zapis_your_tel = "Ваш телефон:";
	$zapis_spec = "Врач:";
	$zapis_data = "Время приема:";
	$zapis_send = "Записаться";
	$zapis_zayavka_send = "Ваша заявка успешно отправлена.<br>В ближайшее время мы вам позвоним.";
	$deleted_days = "воскресенье,суббота";
	$deleted_dates = "1.1,31.12"; //,18.11.2013";

	// для блока карты
	$map_house_address = $map_house_name = $map_house_description = "";
	$map_shablon = '<div style="color:red">$[name]</div><div style="color:#0A0">$[description]</div><div style="color:black">$[dom]</div>';
	$map_yandex_key = 'AIBlZ1IBAAAA8D6sLAIADXX8cFuUyDpQ68hvl-ErRjT9vu0AAAAAAAAAAADCAvqqQC08r3m17iVBNDnpFXnXLw==';
	$map_center = "Москва";
	$map_zoom = 9;

	parse_str($options); // раскладка всех настроек блока
	$class = htmlspecialchars($class,ENT_QUOTES);
	$map_shablon = htmlspecialchars($map_shablon,ENT_QUOTES);

	// убираем лишние запятые в конце
	if (mb_substr($module_name, -1, 1) == ",") $module_name = mb_substr($module_name, 0, mb_strlen($module_name)-1);
	if (mb_substr($show_in_razdel, -1, 1) == ",") $show_in_razdel = mb_substr($show_in_razdel, 0, mb_strlen($show_in_razdel)-1);

	if (strpos($show_in_razdel, ",") or $show_in_razdel == "") $razdels2 = "все"; else $razdels2 = $show_in_razdel;
	if (strpos($module_name, ",")) $razdels1 = ""; else $razdels1 = $module_name;
	if (strpos($no_show_in_razdel, ",")) $razdels3 = ""; else $razdels3 = $no_show_in_razdel;

	if (intval($nastroi) == 1) { // начало редактирования настроек блока
	echo "Настройка блока «".$title."»</span>";
	echo "</div>
	".input("nastroi", "1", "1", "hidden")."
	<h2 class='black_polosa'>Общие настройки блока:</h2>
	<table class='w100 table_light'>";
	
	if ($name == 22 or $name == 23) {
	echo "<tr><td>Блок подключен к Базе данных</td>
	<td>".select("options[module_name]", $razdel_var."", $razdel_name."не подключен", $module_name)."</td></tr>";
	}

	echo "<tr><td><b>Дизайн блока</b> (по умолчанию - нет). Окружает блок заранее созданным Дизайном (оформлением) для этого блока и подключает стиль CSS из дизайна</td>
	<td>".select("options[design]", $design_var.",0", $design_names.",= нет =", $design)."</td></tr>";

	echo "<tr><td><b>CSS-класс блока</b>. Название класса (прописать же настройки класса можно в Главном (или другом) стиле CSS).</td>
	<td>".input("options[class]", $class)."</td></tr>";

	echo "<tr><td><h2>Блок БУДЕТ показан:</h2><ul><li><b>во всех разделах</b> – все, <li><b>в определенном разделе</b> — выберите этот раздел и нажмите «Добавить», <li><b>в нескольких разделах</b> — добавьте несколько разделов через запятую.</ul></td>
	<td>".input("options[show_in_razdel]", $show_in_razdel, "25","input"," id='show_in_raz'")."<br>
	<a class='button medium' id='show_in_razdel_button' onclick='show_raz();'>&uarr; Добавить &uarr;</a><br>
	".select("razdels2", $razdel_engname.aa("все"), $razdel_name."= ко всем Разделам =",$razdels2," onchange='$(\"#show_in_razdel_button\").addClass(\"green\");'")."
	<script>function show_raz() { 
		if ($('#razdels2').val() != '".aa("все")."') {
			if ($('#show_in_raz').val() == '".aa("все")."') $('#show_in_raz').val( '' ); 
			$('#show_in_raz').val( $('#show_in_raz').val() + $('#razdels2').val() + ',' );
		} else $('#show_in_raz').val('".aa("все")."');
	}</script>
	</td></tr>";

	echo "<tr><td>Показывать блок только в определенной папке, если «Блок будет показан» — «в определенном разделе» (указывается цифра — номер папки, по умолчанию - пустое поле)</td>
	<td>".input("options[show_in_papka]", $show_in_papka, 5)."</td></tr>";

	echo "<tr><td><h2>Блок НЕ БУДЕТ показан:</h2><ul><li><b>в определенном разделе</b> — выберите этот раздел и нажмите «Добавить», <li><b>в нескольких разделах</b> — добавьте несколько разделов через запятую.</ul>По-умолчанию, пустое поле.</td>
	<td>".input("options[no_show_in_razdel]", $no_show_in_razdel, 25,"input"," id='no_show_in_raz'")."<br>
	<a class='button medium' id='no_show_in_razdel_button' onclick='no_show_raz();'>&uarr; Добавить &uarr;</a><br>
	".select("razdels3", $razdel_engname, $razdel_name."= не выбран раздел =",$razdels3, " onchange='$(\"#no_show_in_razdel_button\").addClass(\"green\");'")."
	<script>function no_show_raz() { 
		if ($('#razdels3').val() != '".aa("все")."') {
			if ($('#no_show_in_raz').val() == '".aa("все")."') $('#no_show_in_raz').val( '' ); 
			$('#no_show_in_raz').val( $('#no_show_in_raz').val() + $('#razdels3').val() + ',' );
		} else $('#no_show_in_raz').val('".aa("все")."');
	}</script>
	</td></tr>";

	echo "<tr><td><b>Заголовок блока</b></td>
	<td>".select("options[titleshow]", "3,2,1,0,4", "спойлер (блок свернут до заголовка),внутри предисловия как блок [заголовок],показывать,не показывать,не показывать и без DIV-обрамления", $titleshow)."</td></tr>";
	if ($name==0 || $name==1 || $name==3 || $name==4 || $name==5 || $name==6 || $name==8 || $name==9 || $name==10 || $name==11 || $name==13 || $name==14 || $name==15 || $name==30) 
		echo "</table><h2 class='black_polosa'>Настройки данного типа блока:</h2><table class='w100 mw800 table_light'>";
	
	if ($name==0 || $name==1 || $name==4 || $name==9 || $name==11 || $name==13 || $name==30) { // дополнить остальные блоки
		echo "<tr><td><h2>Блок использует содержание Раздела:</h2><ul><li><b>всех разделов</b> – оставьте поле пустым, <li><b>определенного раздела</b> — выберите этот раздел и нажмите «Добавить», <li><b>нескольких разделов</b> — добавьте несколько разделов через запятую, <li><b>открытого раздела</b> — выберите из меню разделов пункт «открытого раздела» и добавьте его или напишите в поле «open_razdel» (без кавычек). При выводе страниц открытого раздела на Главной странице блок исчезнет совсем, при выводе в разделе без страниц будет выведен заголовок, если он разрешен в настройках блока.</ul></td>
		<td>".input("options[module_name]", $module_name, "25","input"," id='add_razdel'")."<br>
	<a class='button medium' id='module_name_button' onclick='add_raz();'>&uarr; Добавить &uarr;</a><br>
	".select("razdels1", $razdel_engname.",open_razdel", $razdel_name."= ко всем Разделам =,= открытого раздела =",$razdels1, " onchange='$(\"#module_name_button\").addClass(\"green\");'")."
	<script>function add_raz() { 
		if ($('#razdels1').val() != '') {
			if ($('#razdels1').val() == 'open_razdel') $('#add_razdel').val( $('#razdels1').val() ); 
			else {
				if ($('#add_razdel').val() == 'open_razdel') $('#add_razdel').val( '' ); 
				$('#add_razdel').val( $('#add_razdel').val() + $('#razdels1').val() + ',' );
			}
		} else $('#add_razdel').val('');
	}</script>
		</td></tr>";
	}

	if ($name == 11) {
	echo "<tr><td>Какое время показывать</td>
	<td>".select("options[calendar_future]", "0,1,2", "будущее и прошлое,только прошлое,только будущее", $calendar_future)."</td></tr>";
	echo "<tr><td>Глубина времени</td>
	<td>".select("options[calendar_years]", "0,1,2,3,4,5,6,7,8,9,10", "текущий год,+1 год,+2 года,+3 года,+4 года,+5 лет,+6 лет,+7 лет,+8 лет,+9 лет,+10 лет,", $calendar_years)."</td></tr>";
	}

	if ($name == 3) {
	echo "<tr><td>Дополнительное обновление блока сразу после загрузки (нужно для исправления загрузки некоторых JS-скриптов, к примеру опросов Вконтакте):</td>
	<td>".select("options[reload_link_on_start]", "1,0", "ДА,НЕТ", $reload_link_on_start)."</td></tr>";

	echo "<tr><td>Время автоматического обновления блока:</td>
	<td>".select("options[reload_link_time]", "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,30,50,60,120,180,240,300,600", "ВЫКЛЮЧЕНО,1 сек.,2,3,4,5 сек.,6,7,8,9,10 сек.,11,12,13,14,15 сек.,16,17,18,19,20 сек.,25,30,50 сек.,1 мин.,2 мин.,3 мин.,4 мин.,5 мин.,10 мин.", $reload_link_time)."</td></tr>";

	echo "<tr><td>Последовательный показ блоков. Обычно ротатор используется для показа блоков вразноброс — это могут быть рекламные или информационные блоки (например, высказывания или юмор...). Но иногда нужно показать блоки последовательно, один за другим, например, если вы хотите показать несколько взаимосвязанных рекламных объявлений, но вместо GIF хотите использовать несколько JPG-изображений, идущих в определенной последовательности, каждая со своей ссылкой.</td>
	<td>".select("options[reload_one_by_one]", "1,0", "ДА,НЕТ", $reload_one_by_one)."</td></tr>";

	echo "<tr><td>Показывать ссылку для обновления блока (для показа вразноброс, т.е. если последовательный показ отключен)</td>
	<td>".select("options[reload_link_show]", "2,1,0", "после блока,до блока,НЕ показывать", $reload_link_show)."</td></tr>";

	echo "<tr><td>Текст ссылки обновления блока:</td>
	<td>".input("options[reload_link_text]", $reload_link_text)."</td></tr>";
	}

	if ($name == 0 or $name == 8) {
	echo "<tr><td>Прикрепить шаблон к блоку (поиск по этому разделу)</td>
	<td>".select("options[shablon]", $shablon_var."0", $shablon_names."без шаблона", $shablon)."</td></tr>";
	}

	if ($name == 5) {
	echo "<tr><td>Тип ответов опроса:</td>
	<td>".select("options[opros_type]", "1,0", "одиночный выбор (кружки),множественный выбор (флажки)", $opros_type)."</td></tr>";

	echo "<tr><td>Результаты опроса:</td>
	<td>".select("options[opros_result]", "2,1,0", "показывать после ответа,показывать сразу (ссылка),видит только Администратор", $opros_result)."</td></tr>";
	}

	if ($name == 4 or $name == 8) {
	echo "<tr><td>Показывать кол-во страниц в скобках после названия папки</td>
	<td>".select("options[papki_numbers]", "1,0", "ДА,НЕТ", $papki_numbers)."</td></tr>";
	echo "<tr><td>Сортировка папок по:</td>
	<td>".select("options[papka_sort]", "title,description,sort[|]title,counter[|]title,parent_id[|]title,cid,cid desc,module[|]title", "названию (по алфавиту),описанию,сортировке,кол-ву посещений,принадлежности к другим папкам,№ папки (с первой),№ папки (с последней),англ. названию раздела", $papka_sort)."</td></tr>";
	}

	if ($name == 0 || $name == 1 || $name == 4 || $name == 6 || $name == 8 || $name == 9) {
	echo "<tr><td>Убрать ссылку на раздел в заголовке блока</td>
	<td>".select("options[notitlelink]", "1,0", "ДА,НЕТ", $notitlelink)."</td></tr>";
	echo "<tr><td>Ссылка заголовка блока: заменить ссылку заголовка на любую другую</td>
	<td>".input("options[alternative_title_link]", $alternative_title_link)."</td></tr>";
	}

	if ($name == 0 || $name == 9 || $name == 4) {
	echo "<tr><td>Сортировка страниц в списке по: </td>
	<td>".select("options[sort]", "sort[|]date desc,date desc,date,sort[|]title,title,open_text,counter,golos desc,comm,cid[|]title,cid[|]date desc,price,prise desc,mainpage desc,search desc,pid,open_text[|]golos", "по очередности (настраивается),дате (с последнего),дате (с первого),названию (по алфавиту),названию (без поля сортировки),предисловию (по алфавиту),кол-ву посещений страницы,среднему баллу голосования,кол-ву комментариев,№ папки и названию,№ папки и дате страницы,цене (с мин.) магазин,цене (с макс.) магазин,важности (Главная стр.),наличию ключ. слов,№ страницы,предисловию и голосованию", $sort)."</td></tr>";
	}

	if ($name == 4) { // папки
	echo "<tr><td>Разделять пункты ".input("options[noli_razdelitel]", $noli_razdelitel, "size=3").". Если отключено, показывать пункты UL > LI</td> 
	<td>".select("options[noli]", "1,0", "ДА,НЕТ", $noli)."</td></tr>";
		
	echo "<tr><td><h2>Если открыт раздел</h2>
	<li>показывать:</td> 
	<td><br><br>".select("options[papki_in_razdel_show]", "0,1,2,3,4,5", "папки первого уровня,папки первого уровня + страницы в корне,все папки,все папки + все страницы,все страницы,страницы в корне", $papki_in_razdel_show)."</td></tr>";
	echo "<tr><td><li>вложенные пункты:</td>
	<td>".select("options[papki_in_razdel_punkt]", "0,1,2", "открыты,свёрнуты,открыт только первый уровень", $papki_in_razdel_punkt)."</td></tr>

	<tr><td><h2>Если открыта папка</h2>
	<li>показывать:</td>
	<td><br><br>".select("options[papki_in_papki_show]", "0,1,2,3,4,5", "как при открытом разделе,все папки,все папки + страницы,только вложенные папки,только вложенные папки + страницы,только вложенные страницы", $papki_in_papki_show)."</td></tr>";
	echo "<tr><td><li>вложенные пункты:</td>
	<td>".select("options[papki_in_papki_punkt]", "0,1,2,3", "открыты,свёрнуты,открыт только первый уровень,открыта только ветка текущей папки", $papki_in_papki_punkt)."</td></tr>
	<tr><td><li>выделять открытую папку</td>
	<td>".select("options[papki_in_papki_check]", "1,0", "ДА,НЕТ", $papki_in_papki_check)."</td></tr>

	<tr><td><h2>Если открыта страница</h2>
	<li>показывать:</td>
	<td><br><br>".select("options[papki_in_pages_show]", "0,1,2,3,4,5,6", "как при открытом разделе,как при открытой папке,только вложенные папки текущей папки,только вложенные папки + страницы,только вложенные страницы,только папки предыдущего уровня,только папки предыдущего уровня + страницы", $papki_in_pages_show)."</td></tr>";
	echo "<tr><td><li>вложенные пункты:</td>
	<td>".select("options[papki_in_pages_punkt]", "0,1,2,3", "открыты,свёрнуты,открыт только первый уровень,открыта только ветка текущей страницы", $papki_in_pages_punkt)."</td></tr>
	<tr><td><li>выделять открытую страницу</td>
	<td>".select("options[papki_in_pages_check]", "1,0", "ДА,НЕТ", $papki_in_pages_check)."</td></tr>";
	}

	if ($name == 9) {
	echo "<tr><td>Указание на первый параметр тега или элемент, после которого следует ссылка на изображение, по-умолчанию: <b>src=</b></td>
	<td>".input("options[first]", $first)."</td></tr>";
	echo "<tr><td>Указание на второй параметр тега или элемент, до которого была ссылка на изображение, по-умолчанию: <b>></b></td>
	<td>".input("options[second]", $second)."</td></tr>";
	echo "<tr><td>Указание на третий параметр тега или элемент, до которого была ссылка на изображение, по-умолчанию: <b>пробел</b></td>
	<td>".input("options[third]", $third)."</td></tr>";
	echo "<tr><td>Показывать заголовок страницы</td>
	<td>".select("options[show_title]", "2,1,0", "перед фото,под фото,нет", $show_title)."</td></tr>";
	}

	if ($name == 9 or $name == 6) {
	echo "<tr><td><b>Размер картинки в пикселях</b><br>
	<li>по горизонтали, по-умолчанию: 0. Если указать 0 — будет считаться по вертикали</td>
	<td><br>".input("options[img_width]", $img_width)."</td></tr>";
	echo "<tr><td><li>по вертикали, по-умолчанию: 100. Если указать 0 — будет считаться по горизонтали</td>
	<td>".input("options[img_height]", $img_height)."</td></tr>";
	}

	if ($name == 6) {
	echo "<tr><td>Водяной знак (наложение на фотографии, ссылка на изображение)</td>
	<td>".input("options[watermark]", $watermark)."</td></tr>";
	echo "<tr><td><b>Тип галереи фотографий</b></td>
	<td>".select("options[foto_gallery_type]", "8,7,6,5,4,3,2,1,0", "вразноброс несколько фото (количество настраивается),полноэкранные миниатюры с описанием в 2 строки (разделение | ) и увеличением,горизонтальная плавная дорожка с показом по одному фото и с увеличением,горизонтальная плавная дорожка с автопоказом (2 сек.) и увеличением,горизонтальная плавная дорожка с увеличением,горизонтальная плавная дорожка аля MacOs с увеличением,миниатюры одного размера с описанием в 3 строки (разделение | ),миниатюры с описанием в одну строку,«карусель» — большая картинка и миниатюры", $foto_gallery_type)."</td></tr>"; // effects 3, basic 4, cycleitems 5, oneperframe 6
	echo "<tr><td>Количество фото при типе галереи «вразноброс»</td>
	<td>".input("options[foto_num]", $foto_num)."</td></tr>";
	}

	if ($name == 22 or $name == 23) {
	echo "<tr><td>Название таблицы Базы данных, обязательно</td>
	<td>".select("options[base]", $base_var, $base_names, $base)."</td></tr>";
	echo "<tr><td>Первая по важности колонка </td>
	<td>".input("options[first]", $first)."</td></tr>";
	echo "<tr><td>Вторая по важности колонка </td>
	<td>".input("options[second]", $second)."</td></tr>";
	if ($name == 22) echo "<tr><td>Можно поменять текст самой первой ячейки, по-умолчанию пустой</td>
	<td>".input("options[text1]", $text1)."</td></tr>";
	echo "<tr><td>Направление вывода информации</td>
	<td>".select("options[direct]", "gor,vert", "горизонтальное (в разработке),вертикальное", $direct)."</td></tr>";
	echo "<tr><td>Сколько сего элементов</td>
	<td>".select("options[all]", "0,1", "не указывать,указывать", $all)."</td></tr>";
	echo "<tr><td>Использованные поля, через запятую</td>
	<td>".input("options[col]", $col)."</td></tr>";
	}

	if ($name == 23) {
	echo "<tr><td>Сортировка страниц в списке (укажите англ. название поля и « desc» для обратной сортировки, можно указывать несколько значений через запятую): </td>
	<td>".input("options[sort]", $sort)."</td></tr>";
	}

	if ($name == 0 or $name == 9) {
	echo "<tr><td>Показывать Предисловие страницы под ее названием-ссылкой</td>
	<td>".select("options[openshow]", "2,1,0", "да и без ссылки,да и со ссылкой,нет", $openshow)."</td></tr>";
	}
	if ($name == 0) {
	echo "<tr><td>Показывать Название страницы внутри предисловия страницы за счет использования автоматического блока [заголовок]. Если [заголовок] не будет упомянут в Предисловии страницы, ее Название не будет показано вообще.</td>
	<td>".select("options[zagolovokin]", "1,0", "ДА,НЕТ", $zagolovokin)."</td></tr>";

	echo "<tr><td>Показывать ссылку «Далее»</td>
	<td>".select("options[daleeshow]", "1,0", "ДА,НЕТ", $daleeshow)."</td></tr>";
	echo "<tr><td>Заменить надпись на ссылке «Далее». Можно писать HTML-код, например вставить таким образом картинку вместо текстовой ссылки.</td>
	<td>".input("options[dal]", $dal)."</td></tr>";

	echo "<tr><td>Показывать ссылку «Открыть все»</td>
	<td>".select("options[open_all]", "1,0", "ДА,НЕТ", $open_all)."</td></tr>";

	echo "<tr><td>Заменить надпись на ссылке «Открыть раздел» справа от названия блока. Можно убрать эту ссылку, полностью стерев ее название.</td>
	<td>".input("options[razdel_open_name]", $razdel_open_name)."</td></tr>";
	echo "<tr><td>Заменить надпись на ссылке «Открыть раздел» внизу блока. Можно убрать эту ссылку, полностью стерев ее название.</td>
	<td>".input("options[razdel_open2_name]", $razdel_open2_name)."</td></tr>";

	echo "<tr><td>Вырезать теги из названия и предисловия страницы, чтобы исключить изменение размеров и жирности текста.</td>
	<td>".select("options[tagdelete]", "1,0", "ДА,НЕТ", $tagdelete)."</td></tr>";

	echo "<tr><td>Учитывать дату последнего посещения страниц посетителем при сортировке по дате, т.е. показывать только те страницы, которые были созданы после последнего посещения</td>
	<td>".select("options[ipdatauser]", "1,0", "ДА,НЕТ", $ipdatauser)."</td></tr>";

	echo "<tr><td>Показывать дату перед названием страницы</td>
	<td>".select("options[datashow]", "1,0", "ДА,НЕТ", $datashow)."</td></tr>";

	echo "<tr><td>Показывать название папки перед названием страницы</td>
	<td>".select("options[catshow]", "1,0", "ДА,НЕТ", $catshow)."</td></tr>";

	echo "<tr><td>Открывать ссылки в новом окне</td>
	<td>".select("options[open_new_window]", "1,0", "ДА,НЕТ", $open_new_window)."</td></tr>";

	echo "<tr><td>Показывать только свежие страницы (на основании COOKIE)</td>
	<td>".select("options[show_new_pages]", "1,0", "ДА,НЕТ", $show_new_pages)."</td></tr>";
	}

	if ($name == 1) {
	echo "<tr><td>Сортировка комментариев в списке</td>
	<td>".select("options[sort]", "data desc,data,avtor,text,golos,num[|]title,num[|]data desc,pid", "дате (с последнего),дате (с первого),имени автора (по алфавиту),тексту комментария (по алфавиту),кол-ву голосов,№ страницы и названию,№ страницы и дате комментария,№ комментария", $sort)."</td></tr>";
	echo "<tr><td>Кол-во выводимых в блок ссылок на комментарии</td>
	<td>".select("options[size]", "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,30,50,75,100,200,500,1000", "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,30,50,75,100,200,500,1000", $size)."</td></tr>";
	echo "<tr><td>C какого комментария начинать вывод? Укажите его номер в выводимой очередности. Используется для создания нескольких блоков, содержащих определенное кол-во выводимых ссылок на комментарии: например, первый начинается с 1 по 5, а второй с 6 по 10. Если разместить эти блоки рядом — получатся 2 колонки.</td>
	<td>".select("options[number]", "0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,24,29,49,74,99,199,499,999", "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,30,50,75,100,200,500,1000", $number)."</td></tr>";
	echo "<tr><td>Какие комментарии отображать в блоке? Пояснение: ответы — это комментарии к другому комментарию, с отступом вправо по дереву.</td>
	<td>".select("options[only_question]", "2,1,0", "только ответы,только вопросы,ВСЕ", $only_question)."</td></tr>";
	echo "<tr><td>Кол-во выводимых символов в тексте комментария, до которых он урезается. Эта настройка нужна для того, чтобы не выводить в блок слишком длинные комментарии, урезать их, заканчивая троеточием.</td>
	<td>".select("options[col_bukv]", "10,15,20,25,30,50,75,100,200,500,1000", "10,15,20,25,30,50,75,100,200,500,1000", $col_bukv)."</td></tr>";
	echo "<tr><td>Показывать название страницы, к которой относится комментарий</td>
	<td>".select("options[pageshow]", "0,1", "нет, да", $pageshow)."</td></tr>";
	}

	if ($name == 0) { // доработать блок мини-фото на работу с этими настройками
		echo "<tr><td>Показывать страницы... (из «корня» раздела или только из его папок)</td>
		<td>".select("options[show_pages_from]", "0,1,2", "все страницы,только страницы из «корня» раздела,только страницы из папок", $show_pages_from)."</td></tr>";

		echo "<tr><td>Показывать страницы с фото...</td>
		<td>".select("options[must_have_foto_adres]", "0,1", "все страницы,только страницы с фото", $must_have_foto_adres)."</td></tr>";
	}

	if ($name == 0 or $name == 9) { // доработать блок мини-фото на работу с этими настройками
	echo "<tr><td>Показывать страницы не из всего раздела, а только из одной определенной папки: указать номер папки. Чтобы узнать номер папки — откройте ее на сайте, в строке адреса страницы — это последний номер. По умолчанию параметр равен пустоте.</td>
	<td>".input("options[cid_open]", $cid_open)."</td></tr>";

	echo "<tr><td>Страницы, отмеченные галочкой «Ставить на главную страницу»:</td>
	<td>".select("options[main]", "2,1,0", "не показывать в блоке,показывать только их,показывать все страницы", $main)."</td></tr>";
	}

	if ($name == 0 or $name == 9 or $name == 23) {
	echo "<tr><td>C какой страницы начинать вывод? Укажите ее номер в выводимой очередности. Используется для создания нескольких блоков, содержащих определенное кол-во выводимых ссылок на страницы: например, первый начинается с 1 по 5, а второй с 6 по 10. Если разместить эти блоки рядом — получатся 2 колонки.</td>
	<td>".input("options[number]", $number)."</td></tr>";
	}

	if ($name == 0 or $name == 1 or $name == 9) {
	echo "<tr><td>Включить режим «Вразноброс», т.е. при каждом обновлении страницы будут показываться разные результаты:</td>
	<td>".select("options[random]", "1,0", "ДА,НЕТ", $random)."</td></tr>";
	}

	if ($name == 0 or $name == 9 or $name == 13 or $name == 23) {
	echo "<tr><td>Кол-во выводимых в блок ссылок на страницы (строк)</td>
	<td>".input("options[size]", $size)."</td></tr>";
	}

	if ($name == 0 or $name == 9) {
	echo "<tr><td>Количество колонок (столбцов) в списке страниц, <nobr>по-умолчанию: 1.</nobr></td>
	<td>".select("options[limkol]", "1,2,3,4,5,6,7,8,9,10", "1,2,3,4,5,6,7,8,9,10", $limkol)."</td></tr>";

	echo "<tr><td>Показывать ссылки-кнопки на следующие страницы</td>
	<td>".select("options[showlinks]", "3,2,1,0", "сверху и снизу,снизу,сверху,не показывать", $showlinks)."</td></tr>";
	}

	if ($name == 10) {
	echo "<tr><td>Раздел сайта с папками или Режим меню: <a class='punkt' onclick=\"$('#re_menu').toggle('slow');\">Справка</a>.
      <div id=re_menu style='display:none;'>".close_button('re_menu')."<p>Меню сайта может настраиваться автоматически или вручную, также можно преобразовать в меню папки выбранного раздела. Если выбран автоматический режим — при редактировании блока можно будет выбрать разделы, папки и страницы сайта, а также их очередность — в удобном редакторе меню. <p>В ручном режиме меню описывается текстом по правилам (для того, чтобы быть универсальным и легко переключать варианты отображения):<br>[элемент открыть][url=/]Главная[/url][элемент закрыть]<br>[элемент открыть][url=#]Пункт меню 1[/url][элемент закрыть]<br>[элемент открыть][url=#]Пункт меню 2[/url]<br>&nbsp;&nbsp;[уровень открыть]<br>&nbsp;&nbsp;[элемент открыть][url=#]Подпункт 1[/url][элемент закрыть]<br>&nbsp;&nbsp;[элемент открыть][url=#]Подпункт 2[/url][элемент закрыть]<br>&nbsp;&nbsp;[уровень закрыть]<br>[элемент закрыть]<br><i>где # - это ссылка на страницу.</i><br>В меню может быть до 3-х уровней вложенности</div>
	</td>
	<td>".select("options[re_menu]", $razdel_engname."1,0", $razdel_name."режим: автоматический,режим: ручной", $re_menu)."</td></tr>";

	echo "<tr><td>Если выбран «раздел сайта», показывать в автоматическом меню:</td>
	<td>".select("options[re_menu_type]", "0,1,2", "папки,страницы,папки и страницы", $re_menu_type)."</td></tr>";

	echo "<tr><td>Тип меню и уровни вложенности подменю:</td>
	<td>".select("options[menu]", "5,2,6,3,1,0,4,7,8,9", "вертикальное 1 уровень,вертикальное 2 уровня (простой список),вертикальное 3 уровня,горизонтальное 1 уровень (слева),горизонтальное 1 уровень (по ширине 100%),горизонтальное 3 уровня (слева),горизонтальное 3 уровня (слева[|] открывается вверх),KickStart вертикальное 3 уровня (слева),KickStart вертикальное 3 уровня (справа),KickStart горизонтальное 3 уровня (слева)", $menu)."</td></tr>";
	}

	if ($name == 15) { // для блока карты
    	echo "<tr>
		<td width=30%>Центрирование карты по определенному адресу, например названию города.</td>
		<td>".input("options[map_center]", $map_center, 100)."</td></tr>
		<tr><td>Масштаб увеличения карты</td>
		<td>".select("options[map_zoom]", "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18", "1 (очень мелко),2 (карта мира),3,4,5 (страна),6,7,8,9 (город),10,11,12,13 (улица),14,15,16 (дом),17,18 (очень крупно)", $map_zoom, 100)."</td></tr>
    	<tr><td>Список адресов, разделенных ||| (например: <i>Самара, ул. Победы, 4 б|||Самара, ул. Стара-Загора, 134</i>). В шаблоне выводится как house_address</td>
		<td>".input("options[map_house_address]", $map_house_address, 150, "txt")."</td></tr>
		<tr><td>Список названий организаций, мест или строений, разделенных ||| (например: <i>Ресторан «У Палыча»|||Кафе «Морошка»|||Школа №77</i>). В шаблоне выводится как house_name</td>
		<td>".input("options[map_house_name]", $map_house_name, 150, "txt")."</td></tr>
		<tr><td>Список описаний организаций, мест или строений, разделенных ||| (например: <i>отличная еда|||дешевая выпечка|||хорошие учителя</i>). Можно вписать видео и фотографии, через html. В шаблоне выводится как house_description</td>
		<td>".input("options[map_house_description]", $map_house_description, 150, "txt")."</td></tr>
		<tr><td>Шаблон для вывода подписей для точек на карте</td>
		<td>".input("options[map_shablon]", $map_shablon, 150, "txt")."</td></tr>
		<tr><td>Идентификационный ключ для <a href='http://api-maps.yandex.ru' target='_blank'>Яндекс-карт</a></td>
		<td>".input("options[map_yandex_key]", $map_yandex_key, 100)."</td></tr>";
	}

	if ($name == 14) { // для блока расписания
		echo "<tr><td colspan='2'><h2>Календарь</h2></td></tr>
		<tr><td>Количество блоков месяцев</td>
		<td>".select("options[numberOfMonths]", "1,2,3,4,5", "1,2,3,4,5", $numberOfMonths)."</td></tr>
		<tr><td>Количество дней для записи. Например: 30 (1 месяц), последующие дни будут недоступны для записи. Если отключить - отграничения не будет, можно будет записаться на любую будущую дату.</td>
		<td>".select("options[calendar_maxDate]", "1,0", "ДА,НЕТ", $calendar_maxDate)." ".input("options[calendar_maxDate_days]", $calendar_maxDate_days, 5)."</td></tr>
		<tr><td>Час дня, после которого начнется запись на следующий день. Например: 16. После этого времени также будет показана надпись: ".input("options[tomorrow_record]", $tomorrow_record, 100)."</td>
		<td>".select("options[show_end_hour]", "1,0", "ДА,НЕТ", $show_end_hour, 5)." ".input("options[end_hour]", $end_hour, 5)."</td></tr>
		<tr><td>Отключенные для записи дни недели, через запятую. Например: суббота,воскресенье</td>
		<td>".input("options[deleted_days]", $deleted_days)."</td></tr>
		<tr><td>Отключенные для записи даты, через запятую. Например, праздники: «1.1.2013, 31.12.2013» или «1.1, 31.12» (т.е. можно и без указания года)</td>
		<td>".input("options[deleted_dates]", $deleted_dates, 50, "txt")."</td></tr>
		<tr><td>Сколько дней сразу выводить в расписании</td>
		<td>".select("options[all_days]", "0,1,2,3,4,5,6,7", "без расписания (в разработке),1 день,2 дня,3 дня,4 дня,5 дней,6 дней,неделя", $all_days)."</td></tr>
		<tr><td>Показать сразу не текущий, а какой-то другой день, например: «7.10.2013». По-умолчанию, для текущего дня ставим «0»</td>
		<td>".input("options[current_day]", $current_day, 5)."</td></tr>

		<tr><td colspan='2'><h2>Расписание</h2></td></tr>
		<tr><td>Какой день показывать в расписании</td>
		<td>".select("options[next_day]", "-2,-1,0,1,2,3,4,5,6,7,8,15,31", "позавчера, вчера, сегодня (лучше не менять), завтра, послезавтра, через 2 дня, через 3 дня, через 4 дня, через 5 дней, через 6 дней, через неделю, через 2 недели, через месяц", $next_day)."</td></tr>
		<tr><td>Надпись «Специалист». Можно заменить на: «Услуга», «Мероприятие», «Занятия» и т.д.</td>
		<td>".input("options[specialist]", $specialist)."</td></tr>
		<tr><td>Надпись «Время приема». Можно заменить на: «Время проведения», «Время посещения» и т.д.</td>
		<td>".input("options[work_time]", $work_time)."</td></tr>
		<tr><td>Надпись «Записаться на » (далее следует время).</td>
		<td>".input("options[record]", $record)."</td></tr>

		<tr><td colspan='2'><h2>Запись</h2></td></tr>
		<tr><td>Список записанных людей</td>
		<td>".input("options[zapis]", $zapis, 150, "txt")."</td></tr>
		<tr><td>Надпись «Запись на прием» (заголовок окна записи). Можно заменить на: «Запись на семинар», «Зарегистрируйтесь» и т.д.</td>
		<td>".input("options[zapis_na_priem]", $zapis_na_priem)."</td></tr>
		</tr>
		<tr><td>Надпись в окне записи «Укажите, пожалуйста, телефон, по которому в ближайшее время с вами могут связаться администраторы нашего медицинского центра для подтверждения записи на прием.»</td>
		<td>".input("options[zapis_obrashenie]", $zapis_obrashenie, 50, "txt")."</td></tr>
		</tr>
		<tr><td>Поле для заполнения «Ваше имя:». Можно заменить на: «Ф.И.О.», «Как вас зовут?», «Как к вам обращаться?»</td>
		<td>".input("options[zapis_your_name]", $zapis_your_name)."</td></tr>
		</tr>
		<tr><td>Поле для заполнения «Ваш телефон:». Можно заменить на: «Ваша электронная почта», «Ссылка на ваш проект», «Контактная информация» и т.д.</td>
		<td>".input("options[zapis_your_tel]", $zapis_your_tel)."</td></tr>
		</tr>
		<tr><td>Заполненное поле «Врач:». Можно заменить на: «Специалист:», «Услуга:», «Мероприятие:», «Занятие:», «Семинар:» и т.д.</td>
		<td>".input("options[zapis_spec]", $zapis_spec)."</td></tr>
		</tr>
		<tr><td>Заполненное поле «Время приема:». Можно заменить на: «Время:», «Время записи:», «Вас будут ждать:» и т.д.</td>
		<td>".input("options[zapis_data]", $zapis_data)."</td></tr>
		</tr>
		<tr><td>Надпись на кнопке отправки записи «Записаться». Можно заменить на: «Отправить», «Отправить заявку» и т.д.</td>
		<td>".input("options[zapis_send]", $zapis_send)."</td></tr>
		</tr>
		<tr><td>Сообщение «Ваша заявка успешно отправлена.&lt;br&gt;В ближайшее время мы вам позвоним.». Можно заменить на: «Спасибо! Заявка принята.» и т.д.</td>
		<td>".input("options[zapis_zayavka_send]", $zapis_zayavka_send, 50, "txt")."</td></tr>
		";
	}
	/////////////////
	if ($name == 0) {
		echo "<tr bgcolor=#FF6666>
		<td>Показывать ссылку на добавление страницы пользователем (в разработке)</td>
		<td>".select("options[add]", "1,0", "ДА,НЕТ", $add)."</td></tr>
		<tr bgcolor=#FF6666>
		<td>Изменить надпись ссылки на добавление страницы пользователем (в разработке)</td>
		<td>".input("options[addtitle]", $addtitle)."</td></tr>
		<tr bgcolor=#FF6666 class=hide>
		<td>Дополнительно: папка с медиа-файлами (по умолчанию - нет)</td>
		<td>".input("options[folder]", $folder)."</td></tr>";
	}
	echo "</table>

	<p class='red'>В текстовых полях нельзя писать символ &</p> <br><br><br>";

	/////////////////////////////////////////////////////////
	} else { // Редактирование содержания блока
		echo input("namo", $name, "1", "hidden")."
		Редактирование содержания блока</span>";
		if (intval($nastroi) != 1 && $name != 10 && $name != 6) red_vybor();
		echo "</div>
		<table class='w100 mw800'><tr valign='top'><td width='50%'>
		<h2>Название блока</h2>
		<textarea class='big w100 h40 f16' name='title' rows='1' cols='10'>".$title."</textarea>
		</td><td>
		<h2>Название класса CSS</h2>
		<textarea class='big w100 h40 f16' name='shablon' rows='1' cols='10'>".$sha."</textarea>
		</td></tr>
		<tr><td colspan=2>";
		if ($name == 6) $text .= "\n";

		if ($name == 3) echo "<b>Разделение кусков рекламы или текста — с помощью символа | </b>";
		if ($name == 7) echo "<b>Вывод на экран из блока с PHP-кодом осуществляется через переменную $"."txt</b><br>
			&lt;? и ?&gt; ставятся <b>только</b> в начале и конце кода, лишь для визуального редактора и наглядности.<br>";

		if ($name == 10 or $name == 5 or $name == 0) $red = 1; // дополнить список при необходимости

		if ($name != 6) echo "<h2>Содержание блока:".button_resize_red($red, true)."</h2>";

		if ($name != 31 && $name != 7 && $name != 10 && $name != 6) echo redactor($red, $text, 'text'); // редактор: тип редактора, редактируемое поле
		if ($name == 6) echo "<div class='pics w100'></div>
		<textarea name=text rows=3 cols=86 class='w100 h155' id=textarea onchange=\"pics_refresh('#textarea');\">".str_replace("\n\n", "\n", $text)."</textarea>
		<script>$(function(){pics_refresh('#textarea');$('#textarea').hide();})</script>";
		if ($name == 31) echo redactor($red, $text, 'text', '', 'javascript');
		if ($name == 7) {
			$text = str_replace("<? ", "", str_replace(" ?>", "", $text));
			echo redactor('2', "<? \n".$text."\n ?>", 'text', '', 'php');
		}
		if ($name == 10 && $re_menu == 1) echo "<div style='display:none'>";
		if ($name == 10 && $re_menu == 1) echo redactor($red, '', 'text', '');
		elseif ($name == 10 && $re_menu != 1) echo redactor($red, $text, 'text', '');
		if ($name == 10 && $re_menu == 1) echo "</div>";
		echo "</td></tr>";
		
		if ($name == 10 && $re_menu == 1) {
			$menu_element = explode("\n", $text);
			$menu_elements = "";
			$uroven = 0; // Уровень смещения меню
			foreach ($menu_element as $value) {
				$value = trim($value);
				if ($value == "[уровень открыть]") $uroven++;
				elseif ($value == "[уровень закрыть]") $uroven--;
				elseif ($value == '[элемент закрыть]') {} 
				else {
					$value = str_replace("[элемент открыть][url=", "", str_replace("[/url]", "", str_replace("[элемент закрыть]", "", $value)));
					$value = explode("]", $value);
					$ur = "";
					for ($i=0; $i < $uroven ; $i++) $ur .= "→";
					$menu_elements .= "<option value='".$value[0]."'>".$ur.$value[1]."</option>";
				}
			}
			echo '<script>
			function bottom_menu(top) {
				x = $("#menu_element :selected");
				if (top == 1) x.insertBefore(x.prev());
				else x.insertAfter(x.next());
				save_menu();
			}
			function min_menu() {
				$("#menu_element :selected").text( $("#menu_element :selected").text().replace("→","") );
				save_menu();
				select_menu();
			}
			function max_menu() {
				x = $("#menu_element :selected");
				z = x.text().replace("→→","");
				if (x.text() == z) { x.text( "→" + x.text() ); save_menu(); }
				select_menu();
			}
			function del_menu() {
				$("#menu_element :selected").remove();
				save_menu();
				$("#izmena").hide();
			}
			function delete_menu() {
				$("#menu_element").empty();
				save_menu();
				$("#izmena").hide();
			}
			function add_menu(top) {
				var link = $("#link").val();
				var link_title = $("#link_title").val();
				x = "<option value=\'" + link + "\'>" + link_title + "</option>";
				if (top == 2) { 
					$("#menu_element option:selected").after( x ); 
					//$("#menu_element :first").attr("selected", "selected");
				} else if (top == 1) { 
					$("#menu_element").prepend( x ); 
					$("#menu_element :first").attr("selected", "selected");
				} else {
					$("#menu_element").append( x );
					$("#menu_element :last").attr("selected", "selected");
				}
				save_menu();
			}
			function save_menu() {
				var all=\'\';
				var pod = pod2 = podrazdel = podrazdel2 = false;
				$.each($(\'#menu_element option\'), function(i,val) {
					x = this.text;
					y = x.replace("→","");
					z = x.replace("→→","");
					if (podrazdel == false && y != x) { all = all + \'\n[уровень открыть]\n\'; pod = true; }
					if (podrazdel == false && y == x && i != 0 && z == x) all = all + \'[элемент закрыть]\n\';
					if (podrazdel == true && y != x && z == x) all = all + \'[элемент закрыть]\n\';
					if (podrazdel == true && y == x) { all = all + \'[элемент закрыть]\n[уровень закрыть]\n[элемент закрыть]\n\'; pod = false; }
					if (podrazdel2 == false && z != x) { all = all + \'\n[уровень открыть]\n\'; pod2 = true; pod = true; }
					if (podrazdel2 == true && z != x) all = all + \'[элемент закрыть]\n\';
					if (podrazdel2 == true && z == x) { all = all + \'[уровень закрыть]\n[элемент закрыть]\n\'; pod2 = false; }
					x = x.replace("→","").replace("→","");
					all = all + \'[элемент открыть][url=\' + this.value + \']\' + x + \'[/url]\';
					if (i == $(\'#menu_element option\').length-1) all = all + \'[элемент закрыть]\n\';
					podrazdel = pod;
					podrazdel2 = pod2;
				});
				$(\'#text\').val(all);
			}
			function select_razdels() {
				$("#razdel").hide();
				$("#link").val( $("#razdels :selected").val() ); 
				$("#link_title").val( $("#razdels :selected").text() );
			}
			function select_menu() {
				$("#izmena").show();
				$("#link").val( $("#menu_element :selected").val() ); 
				$("#link_title").val( $("#menu_element :selected").text() );
			}
			function red_menu() {
				$("#izmena").hide();
				$("#menu_element :selected").val( $("#link").val() ); 
				$("#menu_element :selected").text( $("#link_title").val() );
				save_menu();
			}
			$(document).ready( function() { save_menu(); $("#menu_element").children().draggable(); } );
			</script>';
			// multiple пока не работает.
			global $title_razdels;
			$title_razdelsX = "";
			foreach ($title_razdels as $key => $value) {
				if ($key != "index") $title_razdelsX .= "<option value='-".$key."'>".$value."</option>";
			}
			echo "<tr valign=top><td width=50%>
			<a class='button small punkt' onclick='min_menu()'>← Вложенность -</a> 
			<a class='button small punkt' onclick='max_menu()'>→ Вложенность +</a> 
			<a class='button small punkt' onclick='bottom_menu(1)'>↑ Поднять</a> 
			<a class='button small punkt' onclick='bottom_menu(0)'>↓ Опустить</a> 
			<select size=13 class='w100' id='menu_element' onclick='select_menu()'>".$menu_elements."</select><br>
			<a class='button small punkt red white' onclick='del_menu()'>× Удалить</a>
			<a class='button small punkt red white ml20' onclick='delete_menu()'>Очистить всё</a>
			</td><td>
			<span class=h3>Адрес ссылки (URL):</span><br>
			<input id=link class=w100><br>
			<span class=h3>Название ссылки:</span><br>
			<input id=link_title class='w100 mb10'>
			<div id='izmena' style='display:none' class='mb20'><a class='button blue' onclick='red_menu()'>Изменить</a><br></div>
			<a class='button green' onclick='add_menu(0)'>+ Добавить в конец меню</a>
			<a class='button green small punkt' onclick='add_menu(1)'>в начало</a>
			<a class='button green small punkt' onclick='add_menu(2)'>после выбранного</a><br><br>

			Выбрать для добавления в меню:<br><a class='button' onclick='$(\"#link\").val(\"/\");$(\"#link_title\").val(\"Главная\");'>Главная</a> 
			<a class='button black' onclick='$(\"#razdel\").toggle();'>Раздел</a> 
			<!-- <a class='button small punkt black' onclick='void(0)'>Папку</a> 
			<a class='button small punkt black' onclick='void(0)'>Страницу</a> -->
			<div id='razdel' style='display:none'>
			<select class='w100' id='razdels' onchange='select_razdels()'><option value=''>Выберите раздел</option>".$title_razdelsX."</select>
			</div>
			</td></tr>";
		}

		echo "</table>
		<div class='dark_pole' onclick=\"show('nastroi')\"><span class=\"icon gray small\" data-icon=\"p\"></span> Настройки (для импорта/экспорта)</div>
		<div id='nastroi' style='display: none;'>
		<br><span class=f12><a target='_blank' href='sys.php?op=mainpage&amp;type=3&amp;id=".$id."&nastroi=1'>Перейти к визуальной настройке</a> &rarr;</span><br>
		<textarea class='f12 w100' name='useit' rows='2' cols='10'>".str_replace("&","&amp;",$useit)."</textarea></div>";


		if ($name == 10 && $re_menu == 0) echo "<div class='dark_pole' style='float:right;' onclick=\"show('primer')\">
		    <span class=\"icon gray small\" data-icon=\"?\"></span> Пример построения меню сайта</div>
		    <div id='primer' style='display: none;'><pre>
		# - это ссылки на страницы.

		[элемент открыть][url=/]Главная[/url][элемент закрыть]
		[элемент открыть][url=#]Справочная[/url] 
		  [уровень открыть] 
		  [элемент открыть][url=#] 1[/url][элемент закрыть]
		  [элемент открыть][url=#] 2[/url][элемент закрыть]
		  [уровень закрыть] 
		[элемент закрыть]
		[элемент открыть][url=#]Афиша[/url][элемент закрыть]
		</pre></div>";

		echo "</form>";
		if ($name == 6) echo "<hr>".add_file_upload_form("textarea");
		}
	} ############################### ЗАКРЫТИЕ БЛОК


	if ($type == "4") { ############################### ОТКРЫТИЕ ПОЛЕ
		// определение названия использованного раздела
		$sql = "SELECT `title` FROM ".$prefix."_mainpage where `tables`='pages' and `id`='".$useit."'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$main_design_title = trim($row['title']);
		if ($main_design_title!="") $main_design_title = $main_design_title." (выбрано)";
		else $main_design_title = "все разделы";
		// Определение списка названий разделов
		$modules = "";
		$sql = "SELECT `id`, `title` FROM ".$prefix."_mainpage where `tables`='pages' and `type`='2' and `name`!='index'";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$id_modul = trim($row['id']);
			$title_modul = trim($row['title']);
			$modules .= "<option value='".$id_modul."'>".$title_modul."</option>";
		}
		// Добавлены пользователи USERAD
		$sql3 = "SELECT * FROM ".$prefix."_users_group where `group`!='rigion' and `group`!='config' and `group`!='obl'";
		$result3 = $db->sql_query($sql3);
		while ($row3 = $db->sql_fetchrow($result3)) {
			$id = "1,".$row3['id'];
			$title_group = $row3['group'];
			$modules .= "<option value='".$id."'>Группа пользователей: ".$title_group."</option>";
		}
		// Добавлены пользователи USERAD
		
		echo "Редактирование поля</span></div>
		<table class='w100 mw800'><tr valign='top'><td width='50%'>
		<h2>Название поля<br>
		<textarea class='big w100 h40 f16' name='title' rows='1' cols='10'>".$title."</textarea></h2>
		</td><td>
		<h2>Обращение (англ., без пробелов)<br>
		<textarea class='big w100 h40 f16' name='namo' rows='1' cols='10'>".$name."</textarea></h2>
		Используется в шаблонах для подключения вывода этого поля, пример: pole &rarr; [pole]
		</td></tr><tr><td>
		<h2>Принадлежит разделу:<br>
		<select name='useit' class='w100'><option value='0'>все разделы</option><option value='".$useit."' selected>".$main_design_title."</option>".$modules."</select></h2>
		<h2>№ папок:<br><input type='text' name='shablon' value='".$sha."'></h2>
		</td><td>
		<h2>Параметры поля:<br>
		<textarea name='text' rows='1' cols='100' class='w100'>".$text."</textarea></h2>
		</td></tr></table>";

	} ############################### ЗАКРЫТИЕ ПОЛЕ


	if ($type == "5") { ############################### ОТКРЫТИЕ БАЗА ДАННЫХ
		echo "Редактирование базы данных (таблицы)</span>";
		echo "</div>
		<h2>Название:</h2><input type='text' name='title' value='".$title."' class='w100'>
		<input type='hidden' name='namo' value='".$name."'>
		<h2>Содержание базы данных</h2>
		<textarea name=text rows=15 cols=40 class='w45 h200'>".$text."</textarea><br>
		<p>Параметры разделяются знаком &, который нельзя использовать в параметрах.

		<h2>Параметр type=</h2> Доступность на сайте (1 — доступен для вывода на сайте, 2 — доступен только администратору).

		<h2>Параметр message=</h2> Сообщение справа при добавлении информации в базу данных, можно использовать HTML.

		<h2>Параметр del_stroka=</h2> Показывать кнопку удаления строки: 1 - показывать, 0 - не показывать. 
		<h2>Параметр edit_stroka=</h2> Показывать кнопку редактирования строки: 0 - показывать, 1 - не показывать.
		<h2>Параметр num_day_stroka=</h2> Максимальное количество строк, добавляемых за 1 день, 0 - без ограничений.

		<h2>Параметр options=</h2> Содержит поля и их настройки<br>
		Разделитель полей: /!/ <br>
		Разделитель настроек полей: #!# <br>
		Формат полей: Англ название#!#Рус название#!#Тип данных#!#Важно#!#Видимость#!#Замена <br>
		Тип данных: строка, строкабезвариантов, число, список, текст, дата, датавремя, фото, минифото, файл, ссылка<br>
		Важно: 0 (не важно), 1 (основная категория), 2 (вторичная категория), 3 (обязательно заполнять), 4 (не важно и не печатать), 5 (пустая для печати), 6 (не важно, не печатать и не показывать), 7 (обязательно, не печатать и не показывать)<br>
		Видимость: 0 (видно везде), 1 (не видно нигде), 2 (видно только на странице), 3 (видно только по паролю)<br>";
	} ############################### ЗАКРЫТИЕ БАЗА ДАННЫХ

	if ($type == "6") { ############################### ОТКРЫТИЕ ШАБЛОН
		echo "Редактирование шаблона</span>";
		if (intval($nastroi) != 1) red_vybor();
		echo "</div>
		<table class='w100 mw800'><tr><td width=50%>
		<h2>Название:</h2>
		<textarea class='big w100 h40 f16' name='title' rows='1' cols='10'>".$title."</textarea>
		</td><td>
		<h2>Обращение (англ, без пробелов)</h2>
		<textarea class='big w100 h40 f16' name='namo' rows='1' cols='10'>".$name."</textarea>
		</td></tr><tr><td colspan=2>
		".help_shablon()."
		<h2>Содержание шаблона:".button_resize_red($red, true)."</h2>";
		echo redactor($red, $text, 'text'); // редактор: типа редактора, редактируемое поле
		echo "</td></tr></table>";
	} ############################### ЗАКРЫТИЕ ШАБЛОН
	echo "</div></form>";
}
###################################################################################################
function mainpage_save($id=0, $type, $namo, $title, $text, $useit, $shablon, $descriptionX, $keywordsX, $meta_titleX, $s_tip) {
	global $sgatie, $tip, $admintip, $prefix, $db, $nastroi, $clean_urls;
	$err = 0;
	$op = $_REQUEST['op'];
	if ($type == 2) {
		$sql = "SELECT `name` FROM ".$prefix."_mainpage where `id`='".$id."'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$mod_name = $row['name'];
		if (strpos($mod_name, "\n")) { // заменяем имя запароленного раздела
			$mod_name = explode("\n", str_replace("\r", "", $mod_name));
			$mod_name = trim($mod_name[0]);
		}
		recash($mod_name); // Удаление кеша раздела
	}
	// Обратное преобразование textarea (замена русской буквы е)
	$text = str_replace("tеxtarea","textarea",$text); // ireplace
	$useit = str_replace("tеxtarea","textarea",$useit); // ireplace
	$shablon = str_replace("tеxtarea","textarea",$shablon); // ireplace

	if ($type == 2 || $type == 5) {
		if (trim($namo) == "") $namo = $title;
		if ($clean_urls == 2) $namo = clean_url($namo);
		else $namo = clean_url(strtolow(translit_name($namo)));
	}
	if ($nastroi == 1) { // Настройка раздела или блока
		global $options, $module_name;
		$text = array();
		foreach ($options as $key => $option) {
			if (($key=="base" and $option=="") or ($key=="base" and $option=="0") or $key=="module_name") {} 
				else $text[] = $key."=".$option;
			if ($key=="module_name") $module_name = $option;
		}
		if ($type != 2) $text = $module_name."|".implode("&",$text);
		else $text = "pages|".implode("&",$text);
		$text = stripslashes($text);
		// Обновление настроек
		global $siteurl;
		if ($type != 2) { // блока
			$db->sql_query("UPDATE ".$prefix."_mainpage SET `useit`='".mysql_real_escape_string($text)."', `tables`='pages' WHERE `id`='".$id."';") or $err = 1;
		} else { // раздела
			$db->sql_query("UPDATE ".$prefix."_mainpage SET `text`='".mysql_real_escape_string($text)."', `tables`='pages' WHERE `id`='".$id."';") or $err = 1;
		}
		if ($err == 1) echo "Не удалось обновить содержание.";
		if ($op == "mainpage_save_ayax") { 
			if ($err == 0) echo "Сохранил";
			exit; 
		}
		elseif ($err != 0) die();
		elseif ($type == 3) Header("Location: sys.php?op=mainpage&type=element");
		else Header("Location: sys.php");
		die();
	}

	if (trim($title)=="") $err = 2;
	if ($type == 0) {
		$n = count($useit); // обработка полученных стилей дизайна (массив Select)
		$styles = "";
		for ($x=0; $x < $n; $x++) {
			$styles .= " $useit[$x]";
		}
		$useit = trim($styles);
		if ($sgatie==1) $text = str_replace("
				","",$text);
	}

	// в php 5.4 это уже не нужно :)
	$text = stripslashes(str_replace("  "," ", str_replace("   "," ", trim($text))));
	$namo = stripslashes(trim($namo));
	$title = stripslashes(trim($title));
	$useit = stripslashes(trim($useit));
	$shablon = stripslashes(trim($shablon));
	$descriptionX = stripslashes(trim($descriptionX));
	$keywordsX = stripslashes(trim($keywordsX));
	$meta_titleX = stripslashes(trim($meta_titleX));

	// Обратное преобразование textarea (замена на англ. букву e, костыль для текстового редактора)
	$text = str_replace("tеxtarea","textarea",$text); // ireplace
	$useit = str_replace("tеxtarea","textarea",$useit); // ireplace

	$sql = "SELECT `text` FROM ".$prefix."_mainpage where `tables`='pages' and id='".$id."'";
	$result = $db->sql_query($sql);

	if ($numrows = $db->sql_numrows($result) > 0) {
		// Обновление
		if ($type==3 && $namo == 7) $text = str_replace("<? ", "", str_replace(" ?>", "", $text));
		$db->sql_query("UPDATE ".$prefix."_mainpage SET `name`='".mysql_real_escape_string($namo)."', `title`='".mysql_real_escape_string($title)."', `text`='".mysql_real_escape_string($text)."', `useit`='".mysql_real_escape_string($useit)."', `shablon`='".mysql_real_escape_string($shablon)."', `tables`='pages', `description`='".mysql_real_escape_string($descriptionX)."', `keywords`='".mysql_real_escape_string($keywordsX)."', `meta_title`='".mysql_real_escape_string($meta_titleX)."' WHERE `id`='".$id."';") or $err = 3;

		if ($err == 2) echo "Вы не написали название! Вернитесь и заполните это поле.";
		if ($err == 3) echo "Не удалось обновить содержание. Попробуйте нажать в Редакторе на кнопку «Чистка HTML»";
		if ($op == "mainpage_save_ayax") {
			if ($err == 0) echo "Сохранил"; 
			exit; 
		}
		elseif ($err != 0) die();
		elseif ($type == 2) Header("Location: sys.php");
		else Header("Location: sys.php?op=mainpage&type=element");
		die();
	} else {
		// Создание
		if ($type==2) {
			if ($text == "[название]") {
				$text = "pages|design=".$useit;
				$useit = "[название]Текст раздела «".$title."». Для редактирования откройте Администрирование — слева выберите этот раздел, затем справа нажмите по кнопке Редактировать.<br>Блок &#91;название&#93; в данном случае выводит название раздела.<br>Если вы хотите вывести (вместо названия и последующего произвольного текста) статьи, добавленные в этот раздел — напишите блок &#91;содержание&#93; вместо блока &#91;название&#93;.<br>Более подробная справка доступна при редактировании раздела.";
			} else { 
				$text = "pages|".trim($text)."&design=".$useit; 
				$useit = "[содержание]";
			}
		}
		if ($type==4) { //////////////////////////////////////////////////////////
			$elements = explode('\\r\\n',$text);
			// Создаем списки для поля
			$n = count($elements);
			if ($n > 0 and ($s_tip==0 || $s_tip==7)) {
				for ($x=0; $x < $n; $x++) {
					$element = str_replace("  "," ",trim($elements[$x]));
					if ($element != "") $db->sql_query("INSERT INTO ".$prefix."_spiski (`id`, `type`, `name`, `opis`, `sort`, `pages`, `parent`) VALUES (NULL, '".mysql_real_escape_string($namo)."', '".mysql_real_escape_string($element)."', '', '0', '', '0');") or die('Не удалось создать поле.');
				}
			}
			$and = ""; 
			if ($s_tip==1 or $s_tip==4) $and = "&shablon=".$text; // если тип - текст или строка
			if ($s_tip==2) $and = "&".$text; // если тип - файл
			$text = "spisok|type=".$s_tip.$and;
			$shablon = " ".implode(" ", $shablon)." "; // получаем папки поля
		}

		if ($type==5) { //////////////////////////////////////////////////////////
			global $delete_table, $line_ekran, $line_close, $line_razdel, $delete_stroka, $add_pole_golos, $add_pole_comm, $add_pole_kol, $pole_rename, $pole_open, $pole_filter, $pole_main, $pole_tip, $pole_rusname, $pole_name;

			$delete_table = intval($delete_table);
			$delete_stroka = intval($delete_stroka);
			$add_pole_golos = intval($add_pole_golos);
			$add_pole_comm = intval($add_pole_comm);
			$add_pole_kol = intval($add_pole_kol);

			// Верстаем данные, которые заносятся в таблицу
			$text = array();
			print_r($pole_rusname);
			$n = count($pole_name);
			for ($x=0; $x < $n; $x++) {
				if (trim($pole_name[$x]) == "") $pole_name[$x] = strtolow(translit_name(trim($pole_rusname[$x])));
				else $pole_name[$x] = strtolow(translit_name(trim($pole_name[$x])));
				$text[] = $pole_name[$x]."#!#".$pole_rusname[$x]."#!#".$pole_tip[$x]."#!#".$pole_main[$x]."#!#".$pole_open[$x]."#!#".$pole_rename[$x]."#!#".$pole_filter[$x];
			}
			$text2 = implode("/!/",$text);

			$all = array();

			if ($delete_table == 1) $db->sql_query("DROP TABLE IF EXISTS `".$prefix."_base_".$namo."`;") or die("Не удалось удалить старую таблицу. SQL: $sql");

			$sql = "CREATE TABLE `".$prefix."_base_".$namo."` (";
			$sql2 = array();
			for ($x=0; $x < $n; $x++) {
				$one = explode("#!#",$text[$x]);
				switch ($one[2]) {
					case "текст": $sql2[] = "`$one[0]` TEXT NOT NULL"; break;
					case "строка": $sql2[] = "`$one[0]` VARCHAR( 1000 ) NOT NULL"; break;
					case "строкабезвариантов": $sql2[] = "`$one[0]` VARCHAR( 1000 ) NOT NULL"; break;
					case "список": $sql2[] = "`$one[0]` VARCHAR( 1000 ) NOT NULL"; break;
					case "ссылка": $sql2[] = "`$one[0]` VARCHAR( 1000 ) NOT NULL"; break;
					case "фото": $sql2[] = "`$one[0]` TEXT NOT NULL"; break;
					case "минифото": $sql2[] = "`$one[0]` TEXT NOT NULL"; break;
					case "файл": $sql2[] = "`$one[0]` TEXT NOT NULL"; break;
					case "число": $sql2[] = "`$one[0]` INT( 10 ) NOT NULL"; break;
					case "дата": $sql2[] = "`$one[0]` DATE NOT NULL"; break;
					case "датавремя": $sql2[] = "`$one[0]` DATETIME NOT NULL"; break;
					default: 
						if (strpos(" ".$one[2], "table|")) $sql2[] = "`$one[0]` INT( 10 ) NOT NULL";
						else $sql2[] = "`$one[0]` VARCHAR( 1000 ) NOT NULL"; break;
				}
				$all[] = trim($one[0]);
			}
			$sql .= implode(",",$sql2).") DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;";
			$all = implode(",",$all);
			if ($delete_stroka == 1) $del_stroka = " IGNORE 1 LINES"; else $del_stroka = ""; //  ($all)

			if ($delete_table == 1) $vozmogno = ", возможно такая таблица уже существует"; else $vozmogno = "";
			$db->sql_query($sql) or die("Не удалось создать таблицу".$vozmogno.". SQL: $sql");

			$line_close = str_replace('\"','"',str_replace("\\\\","\\",$line_close)); // убрать
			$line_ekran = str_replace('\"','"',str_replace("\\\\","\\",$line_ekran));

			// Вставляем данные в таблицу с именем $prefix_base_$namo
			if (trim($_FILES["useit"]["tmp_name"] != "")) {
				$sql = "LOAD DATA LOCAL INFILE '".mysql_real_escape_string($_FILES["useit"]["tmp_name"])."' INTO table ".$prefix."_base_".$namo." FIELDS TERMINATED BY '".$line_razdel."' ENCLOSED BY '". $line_ekran."' LINES TERMINATED BY '".$line_close."'".$del_stroka.";"; // local 
				$db->sql_query($sql) or die("Не удалось добавить информацию из файла CSV в таблицу базы данных. SQL: $sql");
			} else echo "Файл не доступен";

			$db->sql_query("ALTER TABLE `".$prefix."_base_".$namo."` ADD  `id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;");
			$add = ""; // Добавление дополнительных параметров
			if ($add_pole_golos == 1) {
				$db->sql_query("ALTER TABLE `".$prefix."_base_".$namo."` ADD  `golos` INT( 10 ) DEFAULT  '1' NOT NULL ;");
				$add .= "&golos=1";
			}
			if ($add_pole_comm == 1) {
				$db->sql_query("ALTER TABLE `".$prefix."_base_".$namo."` ADD  `comm` INT( 10 ) DEFAULT  '1' NOT NULL ;");
				$add .= "&comm=1";
			}
			if ($add_pole_kol == 1) {
				$db->sql_query("ALTER TABLE `".$prefix."_base_".$namo."` ADD  `kol` INT( 10 ) DEFAULT  '1' NOT NULL ;");
				$add .= "&kol=1";
			}
			$db->sql_query("ALTER TABLE `".$prefix."_base_".$namo."` ADD  `active` INT( 1 ) DEFAULT  '1' NOT NULL ;");
			$text = "base|type=".$s_tip.$add."&options=".$text2;
			$useit = "[содержание]";
		}
		// Обратное преобразование textarea (замена русской буквы е)
  		$text = str_replace("tеxtarea","textarea",$text); // ireplace
  		$useit = str_replace("tеxtarea","textarea",$useit); // ireplace
  		$shablon = str_replace("tеxtarea","textarea",$shablon); // ireplace
  		$sql = "INSERT INTO ".$prefix."_mainpage (`id`, `type`, `name`, `title`, `text`, `useit`, `shablon`, `tables`) VALUES (NULL, '".$type."', '".mysql_real_escape_string($namo)."', '".mysql_real_escape_string($title)."', '".mysql_real_escape_string($text)."', '".mysql_real_escape_string($useit)."', '".mysql_real_escape_string($shablon)."', 'pages');";
		$db->sql_query($sql) or die('Не удалось создать. Попробуйте еще раз и в случае неудачи обратитесь к разработчику. '.$sql);
	}

	// узнаем id
	if ($id == 0 && ($type == 2 or $type==5)) {
		$row2 = $db->sql_fetchrow($db->sql_query("SELECT `id` FROM ".$prefix."_mainpage where `tables`='pages' and `type`='".$type."' and `name`='".$namo."' and `title`='".$title."' and `text`='".$text."' and `useit`='".$useit."'")) or die("SQL: SELECT `id` FROM ".$prefix."_mainpage where `tables`='pages' and `type`='".$type."' and `name`='".$namo."' and `title`='".$title."' and `text`='".$text."' and `useit`='".$useit."'");
		if ($type==5) { // Создаем раздел для БД
			// База данных будет подключена к одноименному разделу, ... 
			if ($s_tip == "1") 	$useit2 = "[содержание]"; // доступному на сайте
				else 			$useit2 = ""; 			// доступному только администратору
			$text2 = "pages|design=1&designpages=0&comments=0&lim=100&base=".$row2['id']; 
			// Обратное преобразование textarea (замена русской буквы е)
	  		$text2 = str_replace("tеxtarea","textarea",$text2); // ireplace
	  		$useit2 = str_replace("tеxtarea","textarea",$useit2); // ireplace
	  		$shablon = str_replace("tеxtarea","textarea",$shablon); // ireplace
			$db->sql_query("INSERT INTO ".$prefix."_mainpage (`id`, `type`, `name`, `title`, `text`, `useit`, `shablon`, `counter`, `tables`, `color`, `description`, `keywords`) VALUES (NULL, '2', '".mysql_real_escape_string($namo)."', '".mysql_real_escape_string($title)."', '".mysql_real_escape_string($text2)."', '".mysql_real_escape_string($useit2)."', '".mysql_real_escape_string($shablon)."', '0', 'pages', '0', '', '');") or die('Не удалось создать раздел для БД. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
			// узнаем id папки для БД, чтобы перейти к ее настройке
			$row2 = $db->sql_fetchrow($db->sql_query("SELECT `id` FROM ".$prefix."_mainpage where `tables`='pages' and `type`='2' and `name`='".mysql_real_escape_string($namo)."' and `title`='".mysql_real_escape_string($title)."' and `text`='".mysql_real_escape_string($text2)."' and `useit`='".mysql_real_escape_string($useit2)."'")) or die("SQL: select `id` from ".$prefix."_mainpage where `tables`='pages' and `type`='2' and `name`='".$namo."' and `title`='".$title."' and `text`='".$text2."' and `useit`='".$useit2."'");
		}
		// после сохранения откроем настройку раздела или блока 
		Header("Location: sys.php?op=mainpage&id=".$row2['id']."&nastroi=1");
		die;
	}

	Header("Location: sys.php?op=mainpage&type=element");
	die;
}
##################################################################################################
function mainpage_razdel_color($id, $color) { // заменить на аякс?
	global $tip, $admintip, $prefix, $db;
	$db->sql_query("UPDATE ".$prefix."_mainpage SET `color`='".$color."' WHERE `tables`='pages' and `id`='".$id."';");
	Header("Location: sys.php");
}
##################################################################################################
function mainpage_del($id, $type, $name="") {
	global $tip, $admintip, $prefix, $db;
	switch ( $type ) {
		case '0':
			# Дизайн - автомат. удалять из разделов и блоков -- ДОДЕЛАТЬ!!!
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
		break;
		case '1':
			# Стиль CSS - автоматически удалять из дизайнов -- ДОДЕЛАТЬ!!!
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
		break;
		case '3':
			# блоки
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
		break;
		case '2':
			# Раздел - удалять страницы и папки раздела
			$db->sql_query("DELETE FROM ".$prefix."_pages WHERE module='".$name."'");
			$db->sql_query("DELETE FROM ".$prefix."_pages_categories WHERE module='".$name."'");
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php");
		break;
		case '4':
			# Поле - удалять поля
			$db->sql_query("DELETE FROM ".$prefix."_spiski WHERE type='".$name."'");
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
		break;
		case '5':
			# База данных - удалять базу данных 
			$db->sql_query("DROP TABLE ".$prefix."_base_".$name);
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
			# и упоминание в разделах -- ДОДЕЛАТЬ!!!

		break;
		case '6':
			# Шаблонов - удалять из разделов и блоков -- ДОДЕЛАТЬ!!!
			$db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
			Header("Location: sys.php?op=".$admintip."&type=element");
		break;
	}
}
##################################################################################################
function mainpage_create_block($title, $name, $text, $modul, $useit, $design, $another_block, $another_block_position) {
	global $tip, $admintip, $prefix, $db; //, $name_razdels;
	# id type name title text useit shablon
	$title = trim($title); // Название блока
	$name = intval($name); // тип блока
	$useit = "|".trim($useit); // настройки блока
	if (trim($title) == "") die("Вы забыли ввести название блока!");
	if ($modul != "0" && $modul != "allrazdely") {
		$modul_name = translit_name($title); 
		$useit = $modul.",".$useit;
		$shablon = "block-".$modul_name."-".$name; // css блока имеет вид: block-англ.имя раздела-тип блока
	} else $shablon = "block-".strtolow(translit_name(trim($title))); // транслитерация имени блока
	if ($design != 0) $useit .= "&design=$design";
	$title = mysql_real_escape_string(stripcslashes($title));
	$shablon = mysql_real_escape_string(stripcslashes($shablon));
	$text = mysql_real_escape_string(stripcslashes($text));
	$useit = mysql_real_escape_string(stripcslashes(str_replace("|&","|",$useit)));
	// Обратное преобразование textarea (замена русской буквы е)
	$text = str_replace("tеxtarea","textarea",$text); // ireplace
	$useit = str_replace("tеxtarea","textarea",$useit); // ireplace
	$shablon = str_replace("tеxtarea","textarea",$shablon); // ireplace
	// id,type,name,title,text,useit,shablon,counter,tables,color,description,keywords,meta_title

	// Прописываем вызов нового блока в другом блоке (текстовом или ротаторе)
	if ($another_block != 0) {
		if ($another_block_position == 0) $db->sql_query("UPDATE ".$prefix."_mainpage SET `text`=CONCAT('[".mysql_real_escape_string($title)."]', `text`) WHERE `id`='".$another_block."';"); // сверху
		if ($another_block_position == 1) $db->sql_query("UPDATE ".$prefix."_mainpage SET `text`=CONCAT(`text`,'[".mysql_real_escape_string($title)."]') WHERE `id`='".$another_block."';"); // снизу
		if ($another_block_position == 2) $db->sql_query("UPDATE ".$prefix."_mainpage SET `text`='[".mysql_real_escape_string($title)."]' WHERE `id`='".$another_block."';"); // вместо
	}

	$db->sql_query("INSERT INTO ".$prefix."_mainpage (`id`, `type`, `name`, `title`, `text`, `useit`, `shablon`, `tables`) VALUES (NULL, '3', '".mysql_real_escape_string($name)."', '".$title."', '".$text."', '".$useit."', '".$shablon."', 'pages')") or die("Не удалось создать блок. INSERT INTO ".$prefix."_mainpage VALUES (NULL, '3', '".$name."', '".$title."', '".$text."', '".$useit."', '".$shablon."', '0', 'pages', '0', '', '', '') ");
	// узнаем id
	$row = $db->sql_fetchrow($db->sql_query("SELECT `id` FROM ".$prefix."_mainpage where `tables`='pages' and `type`='3' and `name`='".$name."' and `title`='".$title."' and `text`='".$text."' and `useit`='".$useit."' limit 1"));
	if ($name != 31) Header("Location: sys.php?op=".$admintip."&type=3&id=".$row['id']."&nastroi=1");
	else Header("Location: sys.php?op=mainpage&type=element");
}
#################################################################################################
function spisok_help() { // проверить вызов
	return "<div id=spisok_0><b>«список слов»:</b> просто указываем список возможных вариантов выбора, разделяем их через Enter.<br></div>
	<div id=spisok_1 style='display:none;'><b>«список слов»:</b> просто указываем список возможных вариантов выбора, разделяем их через Enter.<br></div>
	<div id=spisok_2 style='display:none;'><b>«текст»:</b> можно написать заготовку-шаблон, которая будет использоваться по-умолчанию как значение данного поля.<br></div>
	<div id=spisok_3 style='display:none;'><b>«файл»:</b><br>
	прописывать параметры, разделяя их знаком &, указывать значения параметров через знак =, всё без пробелов!<br>
	например: <b>fil=pic&papka=/img=verh&align=left&resizepic=x&file=&picsize=600&minipic=1&resizeminipic=x&minipicsize=100</b><br>Очередность параметров значения не имеет.<br>
	<b>Список доступных параметров:</b><br> 
	<table class='w100 mw800' cellpadding=5 cellspacing=0 border=1> 
	<tr><td><strong>fil=</strong></td><td>pic - картинка, doc - документ/архив, flash - flash-анимация, avi - видео-ролик</td></tr>
	<tr><td><strong>papka=</strong></td><td>путь сохранения файла, например /img/</td></tr>
	<tr><td><strong>align=</strong></td><td>left - слева, right - справа, center - по центру, no - без выравнивания</td></tr>
	<tr><td><strong>mesto=</strong></td><td>verh - сверху, niz - снизу текста страницы, block - в блоке [Обращение] - то, что указано по англ. в обращении к полю, только в квадратных скобках <font color=red>(экспериментальное значение)</font></td></tr>
	<tr><td><strong>resizepic=</strong></td><td>если file=pic, указываем как автоматически изменять размер изображения:<br>
	x - по-горизонтали, y - по-вертикали, big - по большей стороне</td></tr>
	<tr><td><strong>picsize=</strong></td><td>число в пикселях, указывается размер, к которому приводится изображение, если оно больше этого размера</td></tr>
	<tr><td><strong>minipic=</strong></td><td>1 - разрешить, 0 - запретить (по умолчанию) Создание миниатюры изображения</td></tr>
	<tr><td><strong>resizeminipic=</strong></td><td>если file=pic, указываем как автоматически изменять размер изображения до его миниатюры:<br>
	x - по-горизонтали, y - по-вертикали, big - по большей стороне</td></tr>
	<tr><td><strong>minipicsize=</strong></td><td>число в пикселях, указывается размер, к которому приводится миниатюра изображение, если оно больше этого размера</td></tr>
	</table></div>
	<div id=spisok_4 style='display:none;'><b>«период времени»:</b> для него пока что нет никаких настроек и шаблон ему не нужен.</div>
	<div id=spisok_5 style='display:none;'><b>«строка»:</b> можно написать заготовку-шаблон, которая будет использоваться по-умолчанию как значение данного поля.<br></div>
	<div id=spisok_6 style='display:none;'><b>«число»:</b> можно написать заготовку-шаблон, которая будет использоваться по-умолчанию как значение данного поля.<br></div>
	<div id=spisok_7 style='display:none;'><b>«регион»:</b> для него пока что нет никаких настроек и шаблон ему не нужен.</div>";
}
#########################################################################################
	switch ($op) {
	    case "mainpage":
		    if (isset($name)) { // узнать id
		    	global $prefix, $db;
				$row = $db->sql_fetchrow( $db->sql_query("SELECT `id` FROM ".$prefix."_mainpage where `tables`='pages' and (`name` = '".$name."' or `name` like '".$name." %') and `type`='2'") );
				$id = $row['id'];
		    }
		    if (isset($id)) mainpage($id); else mainpage();
	    	break;
	    case "mainpage_save":
	    case "mainpage_save_ayax":
	    	if ($op == "mainpage_save_ayax") parse_str($_REQUEST['string']);
		    if (!isset($descriptionX)) $descriptionX = "";
		    if (!isset($keywordsX)) $keywordsX = "";
		    if (!isset($meta_titleX)) $meta_titleX = "";
		    if (!isset($s_tip)) $s_tip = "";
		    if (!isset($namo)) $namo = "";
		    if (!isset($title)) $title = "";
		    if (!isset($text)) $text = "";
		    if (!isset($useit)) $useit = "";
		    if (!isset($shablon)) $shablon = "";
		    mainpage_save($id, $type, $namo, $title, $text, $useit, $shablon, $descriptionX, $keywordsX, $meta_titleX, $s_tip);
	    	break;
	    case "mainpage_del":
	    	mainpage_del($id, $type, $name);
	    	break;
		case "mainpage_recycle_spiski":
			mainpage_recycle_spiski();
			break;
	    case "mainpage_create_block":
	    	mainpage_create_block($title, $name, $text, $modul, $useit, $design, $another_block, $another_block_position);
	    	break;
		case "mainpage_razdel_color":
			mainpage_razdel_color($id, $color);
			break;
	}
} else echo "Доступ закрыт!";
?>