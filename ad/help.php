<?php
$pageslistdel .= "<p>Вы находитесь в Панели управления сайтом на Главной странице администрирования.<p><a class='h2 punkt' onclick=\"$('#about_cms').toggle('slow');\">Что такое «ДвижОк»?</a>.
      <div id=about_cms style='display:none;'>".close_button('about_cms')."
      <p>«ДвижОк» – это система редактирования сайтов с максимально продуманным, простым и удобным взаимодействием с человеком-редактором, т.е. с вами. Система идеально подходит для создания сайтов-визиток, блогов, коммерческих сайтов для компаний, каталогов товаров, а также статейных порталов. В отличии от многочисленных аналогов, ориентирована на людей, незнакомых с внутренним устройством сайтов. Администрирование сайта заключается в основном в создании и редактировании страниц и ответах на комментарии посетителей сайта, реже — в редактировании блоков (небольших текстовых элементах на страницах сайта).</div>

      <p><a class='h2 punkt' onclick=\"$('#osnova').toggle('slow');\">Знакомство с основными элементами</a>.
      <div id=osnova style='display:none;'>".close_button('osnova')."
    <p>Наведите мышкой на элементы нижеприведенного списка (красный круг покажет на этот элемент):</p>
    <img id=krug src='images/krug.gif' style='position: absolute; z-index:1000; left: 2000px; top: 2000px;' onmouseover=\"$(this).css({left: '2000px', top: '2000px'});\">
<ol class='li_light'>
<li onmouseover=\"$('#krug').css({left: '50px', top: '0px'});\"><b>Логотип панели администрирования</b> является ссылкой на эту страницу, также как и следующая за ним кнопка «Содержание». Вы всегда сможете вернуться на эту страницу, нажав по ним.</li>
<li onmouseover=\"$('#krug').css({left: '195px', top: '-25px'});\"><b>Кнопка «На сайт»</b> открывает главную страницу вашего сайта.</li>
<li onmouseover=\"$('#krug').css({left: '290px', top: '-25px'});\"><b>Поиск по сайту</b> — даже если на вашем сайте не установлен поиск (а сделать это можно очень быстро) — в Панели администрирования поиск работает аналогично — пишите поисковое слово или словосочетание, жмете Enter и поиск выдает результаты по найденному на страницах, а также в папках и разделах сайта.</li>
<li onmouseover=\"$('#krug').css({left: '250px', top: '15px'});\"><b>Меню администрирования</b> — содержит 4 основных раздела. Содержание — открыт в данный момент — позволяет управлять содержимым сайта — его разделами, папками, страницами, комментариями и блоками. Также содержит текстовое поле для заметок и напоминаний — «Записки администратора».</li>
<li onmouseover=\"$('#krug').css({left:'auto', right: '-20px', top: '15px'});\"><b>Выход из администрирования</b> — эту кнопку стоит нажимать после окончания всех работ по администрированию сайта только, если вы редактируете сайт за чужим компьютером или в интернет-кафе.</li>
<li onmouseover=\"$('#krug').css({left: '150px', top: '55px'});\"><b>Разделы сайта</b> — вся левая колонка посвящена созданным для сайта разделам, которые, в свою очередь, могут содержать папки и страницы или сами являться отдельными страницами. По сторонам от заголовка «Разделы:» находятся две кнопки — для сортировки (↓) и создания новых разделов (+). Колонка с разделами может скрываться при отображении не относящейся к ним информации на этом месте (белое поле). У созданного раздела после его названия может быть кнопка редактирования главной (и единственной) страницы раздела или кнопка добавления страниц (если раздел может содержать страницы).
</li>
<li onmouseover=\"$('#krug').css({left: '370px', top: '285px'});\"><b>Полоса сворачивания левой панели</b> служит для скрытия/раскрытия колонки разделов.
<li onmouseover=\"$('#krug').css({left: '530px', top: '60px'});\"><b>Панель быстрого доступа</b> к основным функциям редактора сайта. 
<a class='button small' onclick=\"$('#panel').toggle('slow');\">На ней могут быть...</a>
      <div id=panel style='display:none;'>".close_button('panel')." 
<ul>
  <li>Кнопка «Отзывы» выводит последние добавленные комментарии, одновременно скрывая левую колонку с разделами (за ненадобностью). В названии кнопки есть 3 цифры, разделенные чертой — 0/0/0 — это кол-во комментариев за сегодня, вчера и позавчера. Кнопку можно отключить в настройках.
  <li>Кнопка «Новое» отображает последние правки и добавление страниц.
  <li>Кнопку «Проверить:...» видно при наличии добавленных посетителями сайта страниц (если такая возможность включена). Также можно отключить в настройках. 
  <li>Кнопка «Удаленное» содержит удаленные страницы, которые можно отредактировать (".icon('black small','7').icon('orange small','7')."), удалить окончательно (".icon('red small','F').") или восстановить на прежнем месте (".icon('green small',';')."), если раздел, в котором находились страницы, не был удален. При отсутствии удаленных страниц Корзина не видна.
  <li>Кнопка «Старое» — это резервные копии страниц с возможностью замены текущей страницы на одну из её прошлых версий (".icon('green small',';').").
  <li>Кнопка «Блоки» показывает список небольших элементов сайта — блоков — с возможностью их редактирования (".icon('black small','7')."), настройки (".icon('yellow medium','V')."), отключения (".icon('red small','Q').") и удаления (".icon('red small','F').").
</ul></div>
</li>
</ol>
</div>

<p><a class='h2 punkt' onclick=\"$('#razdel').toggle('slow');\">Подробнее о разделах, папках и страницах</a>.
      <div id=razdel style='display:none;'>".close_button('razdel')."
      <p><b>Раздел</b> — это тематическая категория на сайте. Она может быть самодостаточной (представлять из себя всего одну страницу) или содержать несколько страниц и папок. <i>Пример: раздел «Контакты» может быть лишь одной страницей (т.е. выбрать раздел и нажать «Редактировать») и содержать контактную информацию компании, или содержать несколько несколько страниц с подробными контактами для нескольких городов.</i>
      <p><b>Главная страница</b> — это особый раздел. Он является единственной страницей и не может содержать в себе папок или страниц, поэтому при нажатии по нему сразу открывается редактирование.
      <p><b>Страницы</b> создаются, если информацию раздела следует разбить на несколько отдельных тем и не стоит делать одну гигантскую страницу. <i>Пример: раздел «О компании», в котором созданы страницы «Наши достижения», «Фотографии сотрудников», «История компании».</i>
      <p><b>Папки</b> (другие названия — подразделы, категории или каталоги) не являются обязательными и создаются в случае излишнего количества страниц (к примеру больше 50), когда нужно распределить эти страницы по тематикам. <i>Пример: раздел «Техника», в нём папка «Холодильники», в которой также находятся подпапки — «Однокамерные» и «Двухкамерные» (уже в них добавляются страницы, описывающие конкретные холодильники).</i>
      <p>Если раздел содержит только одну небольшую начальную страницу, можно не создавать в нем папок и страниц, а лишь редактировать сам раздел — после создания раздела, нажмите по его имени в списке разделов слева на этой странице и по кнопке <nobr>".icon('orange small','7')."«Редактировать»</nobr>. 
      <p>Создавая раздел, укажите его русское название, например, «Наши акции», а также английское имя, желательно в одно слово (без пробелов), например, akcyi (транслит) или stock (ссылка для автоматического перевода встроена в диалог создания раздела). Выберите дизайн (обычно используется «Главный дизайн», но на этом сайте, возможно, используются и дополнительные дизайны — система подскажет чаще используемый дизайн и он будет выбран по-умолчанию). Нажмите «Добавить раздел». Как только раздел будет создан, откроется его настройка — вы можете нажать по верхней левой кнопке «Сохранить» и при необходимости вернуться к настройке позже, нажав по его имени слева в списке разделов и по кнопке <nobr>".icon('yellow medium','V')."«Настроить»</nobr>. Настройка раздела разделена на небольшие группы и описана максимально подробно. Если описание какой-либо настройки вам не совсем понятно — обратитесь к разработчику.
      <p>Чтобы добавить в раздел папки или страницы (статьи/новости/посты) нажмите по его названию в списке разделов слева. Справа вы увидите кнопки <nobr>".icon('green small','+')."«Добавить страницу»</nobr> и <nobr>".icon('orange small',',')."«Создать папку»</nobr>. Страницы также можно добавлять, нажимая на кнопку ".icon('green small','+')." справа от названия раздела в списке разделов слева.
      </div>

<p><a class='h2 punkt' onclick=\"$('#add_red_razdel').toggle('slow');\">Подробнее о редактировании Раздела</a>.
      <div id=add_red_razdel style='display:none;'>".close_button('add_red_razdel')."
      <p><a title='Нажмите для увеличения' class=lightbox href='images/structura_razdela.png'><img src='images/structura_razdela.png' width=40% class=right3></a>На рисунке (который можно увеличить, нажав по нему) вы видите внешний вид раздела и его страниц. 
      <p>Раздел может не содержать страниц и папок, а сам являться <b>одиночной страницей</b> (Рис. 1, в этом случае при редактировании раздела в его содержании необязательно прописывать блок [содержание] и [название] — можно сразу писать текст страницы).
      <p>Если раздел <b>состоит из страниц и/или папок</b> — в его содержании обязательно должен присутствовать блок [содержание] (Рис. 2) или комплект блоков [название] и [страницы] (Рис. 2.2) (между которыми можно разместить какую-либо информацию).
      <p>При создании раздела перечисленные блоки добавляются в содержание раздела автоматически. 
      <p>Если нужно чтобы какая-либо информация выводилась в разделе сверху или снизу от содержания (названия и списка страниц) — напишите эту информацию в «Содержании раздела» соответственно сверху или снизу от блока [содержание].
      <p>Если нужно чтобы какая-либо информация выводилась в нескольких разделах, подключенных к одному дизайну — её необязательно добавлять в каждый раздел, а лучше один раз разместить в Дизайне сверху или снизу от блока [содержание] (который выводит в дизайне содержание раздела).
      <p>Иногда нужно вставить информацию посередине, между заголовком и списком страниц — для этого нужно использовать вместо блока [содержание] два других блока — [название] и [страницы], между которыми и написать всё, что вы хотите поставить между Заголовком и Списком страниц (Рис. 2.2) или Заголовком и Текстом страницы раздела (Рис. 3.2).
      <p><b>Напомним:</b> если вы хотите, чтобы вместо перечня страниц в разделе отображалась только одна основная страница — начальная страница раздела, да еще и с произвольным содержанием — просто сотрите всё «Содержание раздела» и напишите то, что вам нужно.
      <br>
      <p>Иногда возникают ситуации, когда нужно сделать что-то нестандартное, например, чтобы:<ul>
      <li>Главная страница раздела имела произвольное содержание, но в разделе также были страницы и папки
      <li>при открытии папки на сайте перед названием раздела выводилась определенная информация, которая не будет видна на главной раздела и на страницах
      <li>а под текстом всех страниц должна выводится другая информация, но ее не должно быть видно под текстом главной страницы раздела и страниц папок</ul>
      И такие сложные задачи можно легко решить с помощью расширенного варианта содержания раздела.
    <h4><a class='button small' onclick=\"$('#high_razdel').toggle('slow');\">Пример сложного содержания раздела</a></h4>
      <div id=high_razdel style='display:none;'>".close_button('high_razdel')."<div class=block2>
    <strong>Наверху раздела [содержание] Внизу раздела</strong> [следующий]<br>
    Наверху главной страницы раздела [содержание] Внизу главной страницы раздела [следующий] <br>
    Наверху папок раздела [содержание] Внизу папок раздела [следующий] <br>
    Наверху страницы раздела [содержание] Внизу страницы раздела
    </div>
    Не обязательно указывать всё это. Были приведены наиболее полные возможности системы. Чаще всего достаточно выделенной фразы (естественно с измененными неслужебными словами) или даже одного служебного слова [содержание].
    <br><br>
    Если открыть первую страницу раздела (с вышеприведенным содержанием), можно увидеть:<br><div class=block2>
    Наверху главной страницы раздела <br>
    Наверху раздела<br>
    <b>Название раздела</b><br>
    <li>Список страниц</li>
    Внизу раздела <br>
    Внизу главной страницы раздела</div><br>
    Если открыть одну из папок раздела, можно увидеть:<br><div class=block2>
    Наверху папок раздела<br>
    Наверху раздела<br>
    <b>Название раздела и открытой папки</b><br>
    <li>Список страниц открытой папки</li>
    Внизу раздела <br>
    Внизу папок раздела</div><br>
    Если открыть одну из страниц раздела, можно увидеть:<br><div class=block2>
    Наверху страницы раздела<br>
    Наверху раздела<br>
    <b>Название раздела</b><br>
    <b>Название открытой страницы</b><br>
    Предисловие и Содержание страницы<br>
    Комментарии страницы<br>
    Внизу раздела <br>
    Внизу страницы раздела</div></div></div>

<p><a class='h2 punkt' onclick=\"$('#add_block').toggle('slow');\">Добавление блока</a>.
      <div id=add_block style='display:none;'>".close_button('add_block')."
      <p>Переходим на вкладку «Оформление», нажимаем кнопку «+» в заголовке левой колонке, жмем «Добавить блок».
<p>В открывшемся окне сразу пишем название блока (можно по-русски), желательно без предлогов. Выбираем его тип, например «Текст или HTML» и в появившемся поле «Содержание блока:» прописываем его содержание, т.е. тот текст, который будет показывать наш блок.
<br>Жмем кнопку «Сохранить» в левом верхнем углу.
<br>Появилось окно настройки блока.
<br>Если мы поставили ему адекватное название, которое можно вывести как заголовок над его содержанием — выбираем в поле «Заголовок блока» — «показывать».
<br>Также может понадобиться поле «Показывать блок только в определенном разделе». Если блок нужно показывать в каком-то определенном разделе, у которого нет своего отдельного дизайна, можно выбрать в этом поле нужный раздел.
<p>Остается поставить блок. Обычно блоки ставятся в Дизайн. Главный дизайн сайта или дизайн, созданный для определенного раздела. Открываем дизайн на редактирование: Оформление — Дизайн разделов и блоков — нужный нам дизайн — белый карандаш (редактирование). Ищем место, в которое нужно поставить. Если вы не разбираетесь в HTML – ищите по другим уже поставленным блокам или обратитесь к разработчику. Ставим название блока в квадратных скобках. <i>Например: [блок]</i>
<p>Отключить блок можно двумя способами — убрать его название в квадратных скобках из дизайна или просто временно отключить на странице блоков (Оформление — Блоки).
<p>Если в тексте нужно поставить ссылку на одну из статей сайта, это можно сделать из редактора и без знания HTML – открываем созданный блок на редактирование (белый карандаш), жмем справа сверху кнопку «Редактор» и выбираем один из редакторов. Пишем текст ссылки в тектовом поле редактора, выделяем его, жмем кнопку с изображением цепи «Ссылка», выбираем в меню «Вставить ссылку» — в поле URL вставляем скопированный адрес страницы, на которую нужно перейти после нажатия на ссылку — Вставить. Сохраняем блок (слева сверху кнопка «Сохранить»).</div>

<p><a class='h2 punkt' onclick=\"$('#add_link').toggle('slow');\">Добавление ссылки</a>.
<div id=add_link style='display:none;'>".close_button('add_link')."
<p>— Переходим на страницу, на которую нужно переходить по будущей ссылке и копируем ее адрес в адресной строке наверху.
<p>— Открываем на редактирование то место (страницу, папку, раздел или блок), где должна находиться ссылка.
<p>— Пишем ее название, выделяем его и жмем кнопку «Ссылка» в редакторе (в виде звена цепочки). В появившемся окне, в поле URL вставляем скопированный адрес страницы. Жмем «Вставить». 
<p>— Сохраняем (кнопка Сохранить в левом верхнем углу).
</div>

<p><a class='h2 punkt' onclick=\"$('#add_banner').toggle('slow');\">Добавление баннера</a>.
<div id=add_banner style='display:none;'>".close_button('add_banner')."
<p>— Изменяем размер картинки баннера по ширине до нужного (размеры можно спросить у разработчика, измеряются в пикселях, «px»).
<p>— Если баннер нужно разметить <b>на странице</b> — ищем в разделах эту страницу, если же его нужно разместить <b>на всех страницах</b> или в определенном разделе — жмем кнопку «Блоки» на главной странице администрирования и ищем в списке блоков нужный блок, название которого также уточняем у разработчика (к примеру: «Баннер_на_Главной», «верхний баннер», «баннер справа», «Баннер раздела Новости» и т.д.)
<p>— Нажимаем белый карандаш (редактировать), жмем кнопку Редактор справа сверху, жмем по второму редактору.
<p>— Добавляем картинку баннера — жмем в редакторе кнопку «Изображение» (иконка - картина с горами). Перетаскиваем файл картинки или жмем кнопку «Выбрать файл» и находим наш файл.
<p>— Баннеры бывают и без ссылок, но если на картинку баннера нужно поставить ссылку (на другой сайт или страницу нашего сайта) — жмем по картинке и вписываем ссылку на сайт в поле Ссылка.
<p>— Сохраняем (кнопка Сохранить в левом верхнем углу).</div>

<p><a class='h2 punkt' onclick=\"$('#add_tab').toggle('slow');\">Добавление вкладок («табов»)</a>.
<div id=add_tab style='display:none;'>".close_button('add_tab')."
<p>Предисловие и содержание страницы, содержание папки или раздела, а также блока типа «Текст или HTML» можно превратить в разделенные вкладками «страницы».<br>См. пример:
<br><img src='images/tabs.png'>
<p>Для реализации примера в содержании текстового блока (страницы, папки или раздела) достаточно написать следующее:<br>
<pre>{{Вкладка 1}}
текст вкладки 1
{{Вкладка 2}}
текст вкладки 2
{{Вкладка 3}}
текст вкладки 3</pre>
<p>Естественно, название и текст вкладок, их количество — могут быть любыми. Главное — обозначить название вкладок, включив их в символы {{ }}.
<p>Если начать текст вкладок не с названия первой вкладки, например с любого текста, первая вкладка будет называться «Обзор» (можно изменить в настройках)
<p>Если вы не хотите превращать страницу (ее предисловие или содержание) или раздел во вкладки, а просто хотите добавить вкладки в текст страницы — используйте для этого блок типа «Текст или HTML».
<p>Если вкладки не работают (т.е. описанная выше конструкция не превращается во вкладки) — проверьте <a href='sys.php?op=options'>Настройки</a> — Начальные настройки — Включить вкладки.
<p>Если вам необходимо одновременно использовать в тексте два символа фигурных скобок ( {{ или }} ) — вкладки можно отключить в Настройках.
</div>

<p><a class='h2 punkt' onclick=\"$('#add_pole').toggle('slow');\">Создание дополнительных полей для страниц</a>.
<div id=add_pole style='display:none;'>".close_button('add_pole')."
<p>Открыть вкладку «Оформление», нажать кнопку «+», нажать «Поля».
<p>Написать название и англ. название поля (оно будет использовано в шаблоне), выбрать раздел (и если нужно - папки раздела) для добавления поля. Если это поле необходимо для всех разделов, раздел можно не выбирать. После того, как поле создано, оно появится при создании и редактировании страниц, после Содержания.
<p>Для того, чтобы созданное поле отображалось на сайте необходимо создать шаблон для вывода страниц этого раздела (а также шаблон для вывода списка страниц в разделе, если это поле будет выводиться не только на странице, но и в списке страниц). 
<p>При создании шаблона для вывода в определенных местах нужных элементов используются специальные блоки, справку по которым можно найти при создании шаблона (Справка — «Выберите объект для шаблона»).
<p>После того, как шаблон(ы) создан, его обязательно нужно подключить к разделу — для этого открыть настройки раздела (вкладка «Содержание», нажать по нужному разделу, нажать «Настроить» справа, нажать по вкладке «Шаблоны (расположение и наличие элементов)») и выбрать на строчках «Шаблон для списка страниц» и/или «Шаблон для страницы» созданные шаблоны, после чего сохранить.
</div>

<hr><i>За более подробной информацией обратитесь к разработчику (это поможет улучшить справочную систему).</i>
<script src='includes/jquery.lightbox.js'></script><script src='includes/jquery.ad-gallery.js'></script><script>$(document).ready(function(){ $('.lightbox').lightbox({ fitToScreen: true, imageClickClose: false }); var galleries = $('.ad-gallery').adGallery(); $('#switch-effect').change( function() { galleries[0].settings.effect = $(this).val(); return false; } ); });</script><link rel='stylesheet' href='includes/lightbox.css' media='screen' />";
?>
