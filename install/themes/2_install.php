<?php

$db->sql_query("INSERT INTO `".$prefix."_config` VALUES ( 'Блог Ивана Иванова', '2013', '', '', 'Я фрилансер. Прочный и нужный. Пишу про работу, жизнь и прочие дела. Интересуюсь хорошим времяпровождением.', '', '', '', '0', '0', '0', '|||||||||||||||||||||||||||||||||||||||||||||', '0', '0|1|1|1|1|0|0|0|21|1|1||0|img/logotip.png|Иван Иванов|51|8||0|monokai|monokai|monokai|monokai|Обзор|0|| руб.|Итого:|Оформить покупку|Ваша Корзина пуста.|×|||<h1>Спасибо!</h1><h3>Ваш заказ успешно отправлен. В ближайшее время мы вам позвоним.</h3>|Ф.И.О.:*
Телефон:*
Email:
Адрес:
Дополнительная информация:||||1|1|1|1|1||1|1|||1|1|1||1|1|1||||1|1|||||300|ltl||Фильтр товаров|Показать все|1|0|→', 'Привет!
Чтобы добавить страницу в Блог — нажми на плюсик рядом с названием раздела «Блог» в левой колонке.
Блоки «Избранное» и «Обсуждаемое» - выводят информацию автоматически.
Блоки «Кстати, сайты» и «Интересные блоги» — текстовые, можно отредактировать, нажав по кнопке «Блоки» → нажав по названию нужного блока.
Чтобы добавить страницу в Избранное - при создании или редактировании страницы нажмите на вкладку «Дополнительные настройки» и поставьте галочку «На главную страницу». Там же можно и теги ввести (через пробел!).
В Настройках можно поменять основные надписи.
Раздел «Портфолио», страницы «Контакты» и «Обо мне» в данный момент не используются на сайте. Вы сами можете прописать ссылки на них в Главном дизайне, в блоках или Главной странице.
Приятного использования!', '4', '0', '0', '.ht_backup');");

$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '1', '0', '', 'Главный дизайн', '<div class=\"blog_header\">
 <div class=\"top-spacer\"></div>
<div class=\"user-picture\">
 <div class=\"e2-user-picture e2-external-drop-target\">
 <div class=\"e2-external-drop-target-frame\">
 <a href=\"/\"><img src=\"theme/userpic.png\" alt=\"[название_проекта]\" /></a>
 </div>
 </div>
</div>
<div class=\"form-search\">[поиск]</div>
<div class=\"header\">
 <h1><span id=\"e2-blog-title\">[заголовок_проекта]</span> <a class=\"rss-link\" style=\"\" href=\"/rss\">РСС</a></h1>
 <p class=\"blog-description\"><span id=\"e2-blog-description\">[описание_проекта]</span></p>
 <p>[почта]</p>
</div>
<div class=\"clear\"></div>
</div>
<div class=\"sidebar ontop\">
 <div class=\"sidebar-element\"><p><a href=\"#\" onclick=\"$(\'#tags\').toggle();\">Теги</a></p><div id=\"tags\" class=\"hide\">[Теги]</div></div>
 <div class=\"sidebar-element\"><h3>Избранное ★</h3>[Избранное]</div>
 <div class=\"sidebar-element\">[Обсуждаемое]</div>
 <div class=\"sidebar-element\">[Интересные блоги]</div>
 <div class=\"sidebar-element\">[Кстати, сайты]</div>
</div>
<div class=\"main-content\">[содержание]</div>
<div class=\"clear\"></div>
<div class=\"bottom-line\"></div>
<div class=\"visual-login\"></div>
<div class=\"copyrights icons\">[год] <span id=\"e2-blog-author\">[название_проекта]</span></div>
<div class=\"engine-info\"><span title=\"EngineCMS\">Работает на CMS «<a href=\"http://hotel-s.ru/\">ДвижОк</a>»</span></div>
<div class=\"bottom-spacer\"></div>', '20', '', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '20', '1', 'index', 'Главный стиль', '* {
 line-height: 1.5em;
 -webkit-box-sizing: border-box;
 -moz-box-sizing: border-box;
 box-sizing: border-box;
}

html, body {
 background-color: #fbfaf6;
}

body {
 color: #000;
 margin: 0;
 padding: 0;
 font-family: \"Helvetica\", \"Arial\", sans-serif;
 font-size: 13px;
}

a {
 color: #000099;
}

a:visited {
 color: #660099;
}

a:hover,
a:visited:hover,
a.hover,
a:visited.hover,
.clickable-keyboard-shortcut:hover {
 color: #d04000;
 border-color: #d04000;
}

h1 {
 font-size: 24px;
 font-weight: bold;
 margin: 0;
 color: #000;
}
h1 a {
 color: #000;
}
h1 a:visited {
 color: #000;
}
h1 a:hover {
 color: #d04000;
}

h2 {
 font-size: 24px;
 font-weight: bold;
 margin: 0 0 0.375em 0;
}

h3 {
 font-size: 16px;
 font-weight: bold;
 margin: 0 0 0.375em 0;
}

p {
 margin: 0 0 0.75em 0;
}

p + ul, p + ol {
 margin-top: -0.75em;
}

ul, ol {
 margin: 0 0 0.75em 0;
 padding: 0 0 0 40px;
}

li {
 margin: 0 0 0.375em 0;
}

table {
 font-size: 100%;
 empty-cells: show;
 border-spacing: 0;
 border-collapse: collapse;
 margin: 0 0 0.75em 0;
}

td {
 padding: 0 1em 0.375em 0;
 vertical-align: top;
}

th {
 font-weight: bold;
 padding-bottom: 0.375em;
}

blockquote {
 margin: 0 0 0.75em 0;
 padding: 0 0 0 40px;
}

img {
 border: 0;
}

sup, sub {
 position: relative;
 vertical-align: middle;
 font-size: 75%;
 font-weight: normal;
}

sup {
 bottom: 0.5em;
}

sub {
 top: 0.5em;
}

small {
 font-size: 85%;
}

pre, tt, code {
 font-family: \"Consolas\", \"Courier New\", monospace;
 font-size: 100%;
}

form {
 display: inline;
 margin: 0;
}

input.text,
select,
textarea {
 font-family: \"Helvetica\", \"Arial\", sans-serif;
 font-size: 15px;
 margin: 0;
 border: 1px #ccc solid;
 border-radius: 4px;
 border-color: #bbb #ccc #ddd;
 box-shadow: 0 1px 1px #ddd inset;
}

button {
 font-family: \"Helvetica\", \"Arial\", sans-serif;
 font-size: 15px;
 margin: 0;
 border: 0;
 box-shadow: 0;
}

input.text,
textarea {
 padding: 4px;
}

input.text:focus,
textarea:focus {
 border-color: #777 #999 #bbb;
 box-shadow: 0 1px 3px #bbb inset;
}

.clear {
 clear: both;
}

* html .clear {
 height: 0;
 overflow: hidden;
}

.tsp {
 margin-right: -0.167em;
}

.hsp {
 margin-right: -0.25em;
}

.small {
 font-size: 85%;
}

.help {
 font-size: 11px;
}

.help a {
 color: #999;
}

.help a:hover {
 color: #d04000;
}

.dashed {
 text-decoration: none;
 border-bottom-width: 1px;
 border-bottom-style: dashed;
}

.dashed, .dashed:visited {
 color: #000099;
 border-color: #000099;
}

.nu {
 text-decoration: none;
}

.ellipsis {
 margin-left: 1px;
 letter-spacing: 1px;
}

.count,
.count-new {
 font-size: 85%;
 position: relative;
}

.count {
 top: .5em;
 color: #000;
}

.rss-link {
 position: relative;
 color: #999999;
 font-size: 9px;
 font-style: normal;
 font-weight: normal;
 letter-spacing: 1px;
 bottom: 0.9em;
 margin-left: .17em;
}

.hidden,
.hidden a {
 color: #98a098;
}

.icons {
 white-space: nowrap;
}

.relative {
 position: relative;
}

.baseline * {
 vertical-align: baseline;
}

.super-h {
 font-size: 11px;
 margin: 0 0 -0.5em 0;
 max-width: 800px;
}

.tag-description {
 font-size: 13px;
 margin: -0.75em 0 3em 0;
 max-width: 800px;
}

.wrong {
 color: #900;
}

.unexistent {
 color: #ccc;
}

.sunglass {
 position: fixed;
 left: 0;
 top: 0;
 width: 100%;
 height: 100%;
 background: #000608;
 z-index: 997;
 opacity: .8;
 -moz-opacity: .8;
 -webkit-opacity: .8;
}

.sunglass-text {
 position: fixed;
 left: 0;
 top: 200px;
 width: 100%;
 z-index: 998;
}

.sunglass-text h1 {
 font-weight: normal;
 margin: 0 0 .75em;
}

.sunglass-text *,
.sunglass-text a {
 text-align: center;
 color: #fff;
}

.sunglass-text a:hover {
 color: #f66;
}

.top-spacer, .bottom-spacer {
 clear: both;
 height: 1em;
}

.top-line, .bottom-line {
 margin: 0 1% 0 3%;
 width: 96%;
 border-bottom: 1px #d0d0d0 solid;
}

.top-line {
 margin-top: 20px;
}

.bottom-line {
 margin-top: 40px;
}

* html .top-line {
 height: 0;
 overflow: hidden;
}

.header {
 margin: 0 25% 0 19%;
 -next-by-homm86-for-ie7: true;
 margin-right: 0;
 width: 56%;
}

.blog-description {
 font-size: 13px;
 max-width: 800px;
}

.message-bar {
 background: #fd9;
 margin: 1em 1% 1em 3%;
 width: 96%;
 padding: 0.5em 0 1em 16%;
 -webkit-border-radius: 12px;
 -moz-border-radius: 12px;
 border-radius: 12px;
}
.message-bar ul {
 padding: 0;
}

.sidebar {
 float: left;
 margin: 0 2% 0 3%;
 padding: 1.5em 0;
 width: 14%;
 font-size: 13px;
}

.sidebar h2 {
 font-size: 13px;
 margin: 1em 0 .33em 0;
}

.sidebar h2 a {
 color: #000;
}

.sidebar h2 a:hover {
 color: #f33;
}

.sidebar h2:first-child {
 margin-top: 0;
}

.sidebar .links-list, .sidebar .block_li_title {
 padding: 0 !important;
 margin: 0 !important;
 list-style: none !important;
 font-size: 11px !important;
}

.block_li_data {display: none !important;}

.sidebar .links-list li {
 margin: 0 0 .5em 0;
 text-indent: 0;
}

.sidebar .links-list li:before {
 content: \'\';
 margin: 0;
}

.sidebar-element {
 margin-bottom: 1.5em;
}

* html .sidebar {
 display: inline;
}

.main-content {
 margin: 1.5em 1% 0 19%;
 width: 80%;
 margin-right: 0;
 min-height: 320px;
}

.main-content h3 {
 font-size: 15px;
 font-weight: bold;
 margin-bottom: 0;
 color: #000;
}

.main-content .text {
 max-width: 800px;
}

.main-content .center {
 text-align: center;
 width: 80%;
}

.external-html {
 font-size: 13px;
 width: 80%;
 padding: .75em 2em 1.5em 1em;
 margin: 0.75em -2em 1.5em -1em;
 background: #e8ece8;
 border-radius: 12px;
 -moz-border-radius: 12px;
 -webkit-border-radius: 12px;
}

.year-months,
.month-days {
 font-size: 11px;
 margin-bottom: 2em;
}

.year-month,
.month-day {
 padding: 0 .33em;
}

.pages {
 margin: 0 0 2.25em;
 font-size: 13px;
}

.pages td {
 padding: 0;
}

.visual-login,
.copyrights,
.engine-info {
 float: left;
 font-size: 13px;
}

.visual-login {
 margin: 1.5em 0 0 3%;
 padding: 0 1em 0 0;
 width: 16%;
}

* html .visual-login {
 display: inline;
}

.copyrights {
 margin: 1.5em 0 0 0;
 padding: 0 1em 0 0;
 width: 60%;
}

.engine-info {
 margin: 1.5em 0 0 0;
 padding: 0 1em 0 0;
 width: 20%;
}

.engine-info,
.engine-info a {
 color: #999999;
}

.button {
 display: inline;
 display: inline-block;
 outline: none;
 padding: 4px 10px;
 background: #fff;
 background: -moz-linear-gradient(top, white 0%, #e8e8e8 85%, rgba(255, 255, 255, 0.33) 100%);
 /* FF3.6+ */
 background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(85%, #e8e8e8), color-stop(100%, rgba(255, 255, 255, 0.33)));
 /* Chrome,Safari4+ */
 background: -webkit-linear-gradient(top, white 0%, #e8e8e8 85%, rgba(255, 255, 255, 0.33) 100%);
 /* Chrome10+,Safari5.1+ */
 background: -o-linear-gradient(top, white 0%, #e8e8e8 85%, rgba(255, 255, 255, 0.33) 100%);
 /* Opera11.10+ */
 background: -ms-linear-gradient(top, white 0%, #e8e8e8 85%, rgba(255, 255, 255, 0.33) 100%);
 /* IE10+ */
 filter: progid:DXImageTransformImageTransform.Microsoft.gradient(startColorstr=\'#ffffff\', endColorstr=\'#e8e8e8\',GradientType=0 );
 /* IE6-9 */
 background: linear-gradient(top, #ffffff 0%, #e8e8e8 85%, rgba(255, 255, 255, 0.33) 100%);
 /* W3C */
 border: 1px #fff solid;
 border-radius: 4px;
 box-shadow: 0 1px 2px #777;
 text-shadow: 0 1px 0 #fff;
 font-size: 13px;
 font-weight: normal;
 font-family: \"Arial\";
 cursor: hand;
 cursor: pointer;
}

.submit-box .button {
 background: -moz-linear-gradient(top, white 0%, #f0f0c0 85%, rgba(255, 255, 255, 0.33) 100%);
 /* FF3.6+ */
 background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(85%, #f0f0c0), color-stop(100%, rgba(255, 255, 255, 0.33)));
 /* Chrome,Safari4+ */
 background: -webkit-linear-gradient(top, white 0%, #f0f0c0 85%, rgba(255, 255, 255, 0.33) 100%);
 /* Chrome10+,Safari5.1+ */
 background: -o-linear-gradient(top, white 0%, #f0f0c0 85%, rgba(255, 255, 255, 0.33) 100%);
 /* Opera11.10+ */
 background: -ms-linear-gradient(top, white 0%, #f0f0c0 85%, rgba(255, 255, 255, 0.33) 100%);
 /* IE10+ */
 filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#ffffff\', endColorstr=\'#f0f0c0\',GradientType=0 );
 /* IE6-9 */
 background: linear-gradient(top, #ffffff 0%, #f0f0c0 85%, rgba(255, 255, 255, 0.33) 100%);
 /* W3C */
 box-shadow: 0 1px 2px #876;
}

.delete-box .button {
 background: -moz-linear-gradient(top, white 0%, #f0d0c0 85%, rgba(255, 255, 255, 0.67) 100%);
 /* FF3.6+ */
 background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(85%, #f0d0c0), color-stop(100%, rgba(255, 255, 255, 0.67)));
 /* Chrome,Safari4+ */
 background: -webkit-linear-gradient(top, white 0%, #f0d0c0 85%, rgba(255, 255, 255, 0.67) 100%);
 /* Chrome10+,Safari5.1+ */
 background: -o-linear-gradient(top, white 0%, #f0d0c0 85%, rgba(255, 255, 255, 0.67) 100%);
 /* Opera11.10+ */
 background: -ms-linear-gradient(top, white 0%, #f0d0c0 85%, rgba(255, 255, 255, 0.67) 100%);
 /* IE10+ */
 filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#ffffff\', endColorstr=\'#f0d0c0\',GradientType=0 );
 /* IE6-9 */
 background: linear-gradient(top, #ffffff 0%, #f0d0c0 85%, rgba(255, 255, 255, 0.67) 100%);
 /* W3C */
 box-shadow: 0 1px 2px #433;
}

.button:disabled {
 color: #a0a0a0;
 cursor: default;
}

.button:hover {
 box-shadow: 0 2px 4px #777;
}

.button:focus {
 border: 1px #ccc dashed;
}

.button:active:hover,
.button:disabled:hover {
 border-color: #fff;
 box-shadow: 0 1px 2px #777;
}

.submit-box .button:hover {
 box-shadow: 0 2px 4px #876;
}

.submit-box .button:focus {
 border-color: #d0d060;
}

.submit-box .button:disabled {
 color: #c0c0a0;
}

.submit-box .button:active:hover,
.submit-box .button:disabled:hover {
 border-color: #fff;
 box-shadow: 0 1px 2px #876;
}

.delete-box .button:hover {
 box-shadow: 0 2px 4px #433;
}

.delete-box .button:focus {
 border-color: #e87c6c;
}

.delete-box .button:disabled {
 color: #a0c0c0;
}

.delete-box .button:active:hover,
.delete-box .button:disabled:hover {
 border-color: #fff;
 box-shadow: 0 1px 2px #433;
}

.button * {
 vertical-align: absmiddle;
}

.submit-button {
 font-size: 16px;
 padding: 10px 1.33em;
 font-weight: bold;
}

.sign-in-button {
 font-size: 13px;
 padding: 4px 10px;
}

.keyboard-shortcut {
 background: #f0f0c0;
 color: #a64;
 padding: 0 .33em;
 border-radius: .33em;
 -moz-border-radius: .33em;
 -webkit-border-radius: .33em;
 text-decoration: none;
 font-size: 85%;
}

.clickable-keyboard-shortcut {
 cursor: pointer;
 cursor: hand;
}

.submit-box .keyboard-shortcut {
 background: transparent;
 border: none;
 margin-left: .67em;
 color: #a64;
 font-weight: normal;
 font-size: 85%;
}

.input-remark {
 margin-left: 1em;
}

.toolbar a {
 float: left;
 display: block;
 text-decoration: none;
 margin-right: 1em;
}

.toolbar * {
 cursor: inherit;
 vertical-align: middle;
}

.toolbar-end {
 clear: left;
}

.note {
 margin-bottom: 48px;
}
.note h1 {
 margin: 0;
 color: #000;
 font-size: 21px;
 -moz-transition-property: font-size;
 -webkit-transition-property: font-size;
 -o-transition-property: font-size;
 transition-property: font-size;
 -moz-transition-duration: 0.33s;
 -webkit-transition-duration: 0.33s;
 -o-transition-duration: 0.33s;
 transition-duration: 0.33s;
 -moz-transition-timing-function: ease-out;
 -webkit-transition-timing-function: ease-out;
 -o-transition-timing-function: ease-out;
 transition-timing-function: ease-out;
 font-weight: bold;
 position: relative;
}
.note h1.draft {
 font-style: italic;
}
.note h1 .icons {
 white-space: nowrap;
}
.note h2 {
 color: #000;
 font-size: 100%;
 font-weight: bold;
 margin: 1.5em 0 0.375em 0;
}
.note h2:first-child {
 margin-top: 0;
}
.note .date {
 font-size: 11px;
 color: #333;
 letter-spacing: .12em;
 text-transform: uppercase;
}
.note .text {
 font-size: 15px;
 margin: 0 0 0.375em 0;
}
.note .tags {
 font-size: 13px;
 margin: 0 0 0.375em 0;
}
.note .comments-link {
 margin: 0.375em 0 0.75em 40px;
 font-size: 15px;
 position: relative;
}
.note .comments-link .comments-link-icon {
 background-image: url(\'theme/comments.gif\');
 background-repeat: no-repeat;
 width: 16px;
 height: 16px;
 padding-top: 0;
 padding-left: 0;
 padding-right: 0;
 display: block;
 background-position: 0px 0;
 font-size: 0;
 line-height: 0;
 _background-image: url(\'theme/comments.gif\');
 display: -moz-inline-box;
 -moz-box-orient: vertical;
 display: inline-block;
 vertical-align: middle;
 *vertical-align: auto;
 position: relative;
 position: absolute;
 left: -22px;
 top: 4px;
}
.note .comments-link .comments-link-icon {
 *display: inline;
}

.page_favourite h1 {
 font-size: 30px;
}

* + html .note {
 zoom: 1;
}


.e2-user-picture {
 float: left;
 margin: 0 0 0 3%;
 width: 16%;
}

* html .e2-user-picture {
 display: inline;
}

.e2-external-drop-target-dragover {
 background: #f8f8e0;
 position: relative;
 border: #963 1px dashed;
 -webkit-border-radius: .75em;
 -moz-border-radius: .75em;
 border-radius: .75em;
}

.e2-external-drop-target-dragover .e2-external-drop-target-frame {
 margin: -1px;
}

.e2-external-drop-target-dragover img,
.e2-user-picture-uploading img {
 opacity: .2;
 -webkit-opacity: .2;
 -moz-opacity: .2;
}

span.page_favourite {
 background-image: url(\'theme/star.gif\');
 background-repeat: no-repeat;
 width: 17px;
 height: 16px;
 padding-top: 0;
 padding-left: 0;
 padding-right: 0;
 display: block;
 background-position: 0px 0;
 font-size: 0;
 line-height: 0;
 _background-image: url(\'theme/star.gif\');
 display: -moz-inline-box;
 -moz-box-orient: vertical;
 display: inline-block;
 vertical-align: middle;
 *vertical-align: auto;
 position: relative;
 top: -2px;
}
.i-favourite {
 *display: inline;
}

a { color: #009 }
a:visited { color: #009 }

.form-search {
 float: right;
 text-align: right;
 margin: 0.417em 1% 0 0;
 margin-left: -1%;
 white-space: nowrap;
}

.blog-description {
 font-size: 1.1em;
}

.blog_header {
 padding-bottom: 20px;
}

.user-picture img {
 position: absolute; 
 margin-left: -25px;
 margin-top: -7px;
}

.pages_links {font-size: 18px; color: #d04000;}
.pages_links a {border:none;}
 
.ontop {padding-top:30px;}


		.img_left {float: left; padding-right: 10px;}
		.img_right {float: right; padding-left: 10px;}

		a img {	border:none;}
		img[align=left] {
		 margin-right: 15px;
		}
		img[align=right] {
		 margin-left: 15px;
		}
		img[align=center] {
		 display: block;
		 margin: 0 auto !important;
		}

		.venzel { display:none; }
		.razdel { display:none; }

		/* Открыть все */
		.open_all {display: block; float: right;}
		.open_all_small, a:link .open_all_small, a:visited .open_all_small, a:hover .open_all_small {}
		a.open_all_link {}', '', '', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '24', '2', 'index', 'Главная страница', 'pages|design=1', '[Вывод статей на Главной]
<div class=\"pages\"><a href=\"-blog_cat_0_page_1\">Ранее</a><br /></div>', '', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '25', '2', 'about_me', 'Обо мне', 'pages|design=1&designpages=0&lim=15&comments=0', '[название]<br>Текст раздела «Обо мне». Для редактирования откройте Администрирование — слева выберите этот раздел, затем справа нажмите по кнопке Редактировать.<br>Блок &#91;название&#93; в данном случае выводит название раздела.<br>Если вы хотите вывести (вместо названия и последующего произвольного текста) статьи, добавленные в этот раздел — напишите блок &#91;содержание&#93; вместо блока &#91;название&#93;.<br>Более подробная справка доступна при редактировании раздела.', '', '0', 'pages', '0', '', 'Обо мне');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '26', '2', 'blog', 'Блог', 'pages|design=1&designpages=0&razdel_shablon=36&div_or_table=1&view=0&comment_shablon=2&page_shablon=0&limkol=1&lim=1&pagekol=0&pagenumbers=0&sort=date desc&reclama=&papka_show=0&search=0&search_papka=0&menushow=0&razdel_link=2&podrazdel_show=1&podrazdel_active_show=1&tag_text_show=Ключевые слова&tags=1&tags_type=0&tags_show=1&golos=0&golosrazdel=0&golostype=0&titleshow=1&opentextshow=1&maintextshow=1&read_all=Читать далее...&show_read_all=1&datashow=0&peopleshow=1&favorites=0&socialnetwork=0&put_in_blog=0&no_html_in_opentext=0&no_html_in_text=0&table_light=0&tipograf=1&comments_1=Комментарии&comments=1&comments_main=0&comments_add=1&vetki=2&comments_desc=0&comments_4=Ваш email:&comments_mail=1&comments_5=Ваш адрес:&comments_adres=0&comments_6=Ваш телефон:&comments_tel=0&comments_num=0&comments_8=Раскрыть все комментарии&comments_all=0&media_comment=1&comments_2=Оставьте ваш вопрос или комментарий:&comments_3=Ваше имя:&comments_7=Ваш вопрос или комментарий:&tema_zapret_comm=1&reiting_data=Дата написания отзыва&post=0&show_add_post_on_first_page=0&add_post_to_mainpage=0&media_post=0&tema=Открыть новую тему&tema_name=Ваше имя&tema_title=Название темы&tema_opis=Подробнее (содержание темы)&tema_zapret=1&calendar=&podrobno=1', '[содержание]', '', '0', 'pages', '0', '', 'Блог');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '27', '2', 'portfolio', 'Портфолио', 'pages|design=1&designpages=0&lim=15&comments=0', '[содержание]', '', '0', 'pages', '0', '', 'Портфолио');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '28', '2', 'contacts', 'Контакты', 'pages|design=1&designpages=0&lim=15&comments=0', '[название]<br>Текст раздела «Контакты». Для редактирования откройте Администрирование — слева выберите этот раздел, затем справа нажмите по кнопке Редактировать.<br>Блок &#91;название&#93; в данном случае выводит название раздела.<br>Если вы хотите вывести (вместо названия и последующего произвольного текста) статьи, добавленные в этот раздел — напишите блок &#91;содержание&#93; вместо блока &#91;название&#93;.<br>Более подробная справка доступна при редактировании раздела.', '', '0', 'pages', '0', '', 'Контакты');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '29', '3', '10', 'Главное меню', '[элемент открыть][url=-about_me]Обо мне[/url][элемент закрыть]
[элемент открыть][url=-blog]Блог[/url][элемент закрыть]
[элемент открыть][url=-portfolio]Портфолио[/url][элемент закрыть]
[элемент открыть][url=-contacts]Контакты[/url][элемент закрыть]', '|design=0&show_in_razdel=все&no_show_in_razdel=&html=0&titleshow=0&menu=0', 'block-main_menu', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '30', '3', '13', 'Теги', '', 'blog,|design=0&show_in_razdel=все&show_in_papka=&no_show_in_razdel=&html=1&titleshow=0&size=30', 'block--13', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '31', '3', '2', 'Кстати, сайты', '<ul class=\"links-list\">
<li><a href=\"http://design.artgorbunov.ru/\">Дизайн-собака</a></li>
<li><a href=\"http://www.artlebedev.ru/kovodstvo/business-lynch/\">Бизнес-линч</a></li>
<li><a href=\"http://1hub.ru/\">ПервоХаб</a></li>
<li><a href=\"http://turbofilm.tv\">Турбик</a></li>
<li><a href=\"http://znatnado.ru\">ЗнатьНадо</a></li>
<li><a href=\"http://dirty.ru/\">Дёти</a></li>
<li><a href=\"http://www.browsercover.me/\">Оборачивалка картинок в браузер</a></li>
<li><a href=\"http://www.dejavu.org/emulator.htm\">Эмулятор старинных браузеров</a></li>
</ul>', '|design=0&show_in_razdel=все&show_in_papka=&no_show_in_razdel=&html=0&titleshow=1', 'block--2', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '32', '3', '0', 'Обсуждаемое', '', 'blog,|design=0&show_in_razdel=все&show_in_papka=&no_show_in_razdel=&html=0&titleshow=1&class=&shablon=0&notitlelink=1&alternative_title_link=&sort=comm&openshow=0&zagolovokin=0&daleeshow=0&dal=Далее...&open_all=0&razdel_open_name=Открыть раздел&razdel_open2_name=Открыть раздел&tagdelete=0&ipdatauser=0&datashow=0&catshow=0&open_new_window=0&show_new_pages=0&cid_open=&main=0&number=0&random=0&size=5&limkol=1&showlinks=0&add=0&addtitle=Добавить статью&media=0&folder=0', 'block--0', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '33', '3', '2', 'Интересные блоги', '<ul class=\"links-list\">
<li><a href=\"http://ilyabirman.ru/meanwhile/\">Илья Бирман про дизайн</a></li>
<li><a href=\"http://albov.net/\">Сергей Альбов о том, что узнал</a></li>
<li><a href=\"http://ksoftware.livejournal.com/\">Коля Товеровский об интерфейсах</a></li>
<li><a href=\"http://ilyapetrov.com/blog/\">Илья Петров о рекламе</a></li>
<li><a href=\"http://tema.livejournal.com/\">Тёма Лебедев обо всём</a></li>
<li><a href=\"http://zyalt.livejournal.com/\">Илья Варламов про Россию</a></li>
<li><a href=\"http://blog.vandergrav.ru/\">Интересно о всяком у Антона Григорьева</a></li>
</ul>', '|design=0&show_in_razdel=все&show_in_papka=&no_show_in_razdel=&html=0&titleshow=1', 'block--2', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '34', '3', '0', 'Избранное', '', 'blog,|design=0&show_in_razdel=все&show_in_papka=&no_show_in_razdel=&html=1&titleshow=0&class=&shablon=0&notitlelink=1&alternative_title_link=&sort=date desc&openshow=0&zagolovokin=0&daleeshow=0&dal=Далее...&open_all=0&razdel_open_name=Открыть раздел&razdel_open2_name=Открыть раздел&tagdelete=0&ipdatauser=0&datashow=0&catshow=0&open_new_window=0&show_new_pages=0&cid_open=&main=1&number=0&random=0&size=50&limkol=1&showlinks=0&add=0&addtitle=Добавить статью&media=0&folder=0', 'block--0', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '35', '6', 'main_sha', 'для блока страниц Блога на Главной', '<div class=\"note[page_on_mainpage]\">
 <h1 class=\"published visible e2-smart-title\">[page_link_title]<span class=\"[page_on_mainpage]\"></span></h1>
 <p class=\"date\" title=\"[page_data]\">[page_data]</p>
 <div class=\"text published visible\">[page_opentext]</div>
 <div class=\"comments-link\"><span class=\"comments-link-icon\"></span><a href=\"[page_link]\"><b>[page_comments_word]</b></a></div>
</div>', '', '', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '36', '6', 'sha_blog', 'для раздела Блог', '<div class=\"note[page_on_mainpage]\">
 <h1 class=\"published visible e2-smart-title\">[page_link_title]<span class=\"[page_on_mainpage]\"></span></h1>
 <p class=\"date\" title=\"[page_data]\">[page_data]</p>
 <div class=\"text published visible\">[page_opentext]</div>
 <div class=\"comments-link\"><span class=\"comments-link-icon\"></span><a href=\"[page_link]\"><b>[page_comments_word]</b></a></div>
</div>', '', '', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '37', '3', '0', 'Вывод статей на Главной', '', 'blog,|design=0&show_in_razdel=все&show_in_papka=&no_show_in_razdel=&html=1&titleshow=0&class=&shablon=35&notitlelink=1&alternative_title_link=&sort=date desc&openshow=0&zagolovokin=0&daleeshow=0&dal=Далее...&open_all=0&razdel_open_name=Открыть раздел&razdel_open2_name=Открыть раздел&tagdelete=0&ipdatauser=0&datashow=0&catshow=0&open_new_window=0&show_new_pages=0&cid_open=&main=0&number=0&random=0&size=5&limkol=1&showlinks=0&add=0&addtitle=Добавить статью&media=0&folder=0', 'block--0', '0', 'pages', '0', '', '');");


$db->sql_query("INSERT INTO `".$prefix."_pages` VALUES ( '1', 'blog', '0', 'Не&nbsp;буду — не&nbsp;хочу', '<p><span style=\"background-color: white; \">Часто мы,  выражая недовольство,  говорим «я не&nbsp;буду этого делать». Это признак </span><a href=\"http://us2.campaign-archive1.com/?u=833bf5395122c8de57f99f863&id=f0f0b91120\" style=\"background-color: white; \">пассивной агрессии</a><span style=\"background-color: white; \"> и&nbsp;полная херня как часть диалога. Это не&nbsp;реплика даже,  а&nbsp;просто посыл в&nbsp;жопу.</span></p><p>Может показаться,  что нифига подобного,  потому что я&nbsp;же могу решать за&nbsp;себя,  что я&nbsp;буду делать,  а&nbsp;чего не&nbsp;буду. Ну вот решил,  что не&nbsp;буду,  смиритесь.</p><p>А&nbsp;вот и&nbsp;нет. Потому что ты понятия не&nbsp;имеешь,  чего ты там будешь делать через пять минут. Может,  я&nbsp;тебе щас руку сломаю и&nbsp;ты,  сука,  будешь делать. Или&nbsp;пойду директору настучу,  и&nbsp;ты будешь делать,  причём ускоренно. Или,  может,  я&nbsp;заплачу тебе сто тыщ,  и&nbsp;ты будешь делать два раза подряд.</p><p>Не&nbsp;ври себе и&nbsp;окружающим. Не&nbsp;говори «не буду»,  скажи честно «не хочу».</p>', '', '2013-10-16 19:48:26', '2013-10-16 19:48:46', '0', '1', '0', '0', '', '  ', '1', '1', '0.00', '', '', 'pages', '0', '0', '0');");
$db->sql_query("INSERT INTO `".$prefix."_pages` VALUES ( '2', 'blog', '0', 'Кошек можно,  собак — нельзя', '<p><span style=\"background-color: white; \">Илья Бирман писал заметку про&nbsp;</span><a href=\"http://ilyabirman.ru/meanwhile/all/dog-owners/\" style=\"background-color: white; \">собачников</a><span style=\"background-color: white; \">. Я&nbsp;с ним полностью согласен. Но&nbsp;я&nbsp;более радикально настроен и&nbsp;чё-то наболело в&nbsp;последнее время. Почему-то люди воспринимают как должное возможность держать в&nbsp;городе животное. Типа,  ну можно же,  все держат,  чё. А&nbsp;я вот считаю,  что нельзя. Кто сказал,  что можно-то? Просто ещё не&nbsp;запретили. Животные — прекрасные твари. Но&nbsp;это&nbsp;же не&nbsp;значит,  что можно с лосём в&nbsp;метро ехать. </span><span style=\"background-color: white; \">…  </span></p>', '<p><span style=\"background-color: white; \">Так вот,  к&nbsp;собакам. Собака — нифига не&nbsp;домашнее животное. Домашнее — это кошка. Она всю жизнь может в&nbsp;квартире прожить и&nbsp;ни разу на&nbsp;улицу не&nbsp;выйти. А&nbsp;собака — она огромная,  вонючая,  регулярно выгуливается хозяевами на&nbsp;улице,  где обильно ссыт и&nbsp;срёт  (sic!), громко лает и&nbsp;может загрызть человека насмерть,  если захочет. Охуели что ли такое в&nbsp;городе держать? Нет,  нельзя. Вы бы ещё динозавра завели и&nbsp;по улицам с ними гуляли.</span></p><p>Кошку дома можно. Собаку на&nbsp;улице нельзя. Хочешь — держи дома,  спи с ней в&nbsp;одной кровати,  выгуливай в&nbsp;коридоре. А&nbsp;ещё есть карманные шавки. Их тоже можно,  но&nbsp;в&nbsp;квартире. На&nbsp;улице — нельзя. И&nbsp;кошку на&nbsp;улице тоже нельзя. В&nbsp;специальной клетке можно. Без клетки нельзя. Город — он для&nbsp;людей. Нутыпонел.</p>', '2013-10-16 19:48:46', '2013-10-16 20:15:29', '0', '1', '0', '1', '', ' кошка собака ', '0', '1', '0.00', '', '', 'pages', '0', '0', '0');");
$db->sql_query("INSERT INTO `".$prefix."_pages` VALUES ( '3', 'blog', '0', 'Кошек можно,  собак — нельзя', '<p><span style=\"background-color: white; \">Илья Бирман писал заметку про&nbsp;</span><a href=\"http://ilyabirman.ru/meanwhile/all/dog-owners/\" style=\"background-color: white; \">собачников</a><span style=\"background-color: white; \">. Я&nbsp;с ним полностью согласен. Но&nbsp;я&nbsp;более радикально настроен и&nbsp;чё-то наболело в&nbsp;последнее время. Почему-то люди воспринимают как должное возможность держать в&nbsp;городе животное. Типа,  ну можно же,  все держат,  чё. А&nbsp;я вот считаю,  что нельзя. Кто сказал,  что можно-то? Просто ещё не&nbsp;запретили. Животные — прекрасные твари. Но&nbsp;это&nbsp;же не&nbsp;значит,  что можно с лосём в&nbsp;метро ехать. </span><span style=\"background-color: white; \">…  </span></p>', '<p><span style=\"background-color: white; \">Так вот,  к&nbsp;собакам. Собака — нифига не&nbsp;домашнее животное. Домашнее — это кошка. Она всю жизнь может в&nbsp;квартире прожить и&nbsp;ни разу на&nbsp;улицу не&nbsp;выйти. А&nbsp;собака — она огромная,  вонючая,  регулярно выгуливается хозяевами на&nbsp;улице,  где обильно ссыт и&nbsp;срёт  (sic!), громко лает и&nbsp;может загрызть человека насмерть,  если захочет. Охуели что ли такое в&nbsp;городе держать? Нет,  нельзя. Вы бы ещё динозавра завели и&nbsp;по улицам с ними гуляли.</span></p><p>Кошку дома можно. Собаку на&nbsp;улице нельзя. Хочешь — держи дома,  спи с ней в&nbsp;одной кровати,  выгуливай в&nbsp;коридоре. А&nbsp;ещё есть карманные шавки. Их тоже можно,  но&nbsp;в&nbsp;квартире. На&nbsp;улице — нельзя. И&nbsp;кошку на&nbsp;улице тоже нельзя. В&nbsp;специальной клетке можно. Без клетки нельзя. Город — он для&nbsp;людей. Нутыпонел.</p>', '2013-10-16 19:48:46', '2013-10-16 20:15:29', '0', '1', '0', '1', '', '  ', '0', '1', '0.00', '', '', 'backup', '2', '0', '0');");
$db->sql_query("INSERT INTO `".$prefix."_pages` VALUES ( '4', 'blog', '0', 'Голосование за схему метро', '<p><span style=\"background-color: white; \">Я&nbsp;считаю,  что голосование на&nbsp;</span><a href=\"http://dt.mos.ru/metro/\" style=\"background-color: white; \">конкурсе</a><span style=\"background-color: white; \"> устроено очень плохо и&nbsp;от этого получается предвзятым.</span></p><p>Во-первых,  нельзя было прямо на&nbsp;странице голосования писать авторов схем. Да,  три тысячи дизайнеров всё равно узнали бы,  где чьё. Но&nbsp;остальные выбирают не&nbsp;схему,  а&nbsp;автора: «О,  это схему делал один дизайнер,  а&nbsp;эту целая студия,  а&nbsp;вот у&nbsp;этой даже картограф есть,  цо-цо-цо»…  </p>', '<p><span style=\"background-color: white; \">Во-вторых,  нельзя было вывешивать на&nbsp;голосование по-разному нашпигованные схемы. У&nbsp;Бирмана,  например нет тысячи электричковых линий,  а&nbsp;у схемы студии есть. В&nbsp;результате ребята выбирают не&nbsp;лучшую схему,  пытаясь вникнуть в&nbsp;суть,  а&nbsp;смотрят,  где побольше нарисовано. Если Бирмана допустили в&nbsp;конкурс без электричек,  то&nbsp;и&nbsp;с остальных схем надо было электрички снять на&nbsp;время голосования.</span></p><p>Ну и&nbsp;сам интерфейс голосования адский. Я,  например,  не&nbsp;смог проголосовать сначала. Не&nbsp;потому что сайт лежал. Нет,  он работал. Я&nbsp;не мог проголосовать,  потому что не&nbsp;дождался смски с кодом. Потому что,  оказывается,  надо было капчу ввести. Схуя ли? Мне написано «На&nbsp;ваш номер будет отправлено 1 СМС с кодом подтверждения. Введите номер,  нажмите кнопку». Ну дак я&nbsp;ввёл,  нажал и&nbsp;стал ждать смс. Если я&nbsp;уже нажал кнопку «ОК»,  то&nbsp;я&nbsp;не смотрю,  появилась ли там где-то капча. Если я&nbsp;с первого раза не&nbsp;смог,  могу себе представить,  сколько нормальный людей сыпется мимо голосования.</p>', '2013-10-16 20:46:41', '2013-10-16 21:14:57', '0', '1', '0', '0', '', ' метро ', '0', '1', '0.00', '', '', 'pages', '0', '0', '0');");
$db->sql_query("INSERT INTO `".$prefix."_pages` VALUES ( '5', 'blog', '0', 'Голосование за&nbsp;схему метро', '<p><span style=\"background-color: white; \">Я&nbsp;считаю,  что голосование на&nbsp;</span><a href=\"http://dt.mos.ru/metro/\" style=\"background-color: white; \">конкурсе</a><span style=\"background-color: white; \"> устроено очень плохо и&nbsp;от этого получается предвзятым.</span></p><p>Во-первых,  нельзя было прямо на&nbsp;странице голосования писать авторов схем. Да,  три тысячи дизайнеров всё равно узнали бы,  где чьё. Но&nbsp;остальные выбирают не&nbsp;схему,  а&nbsp;автора: «О,  это схему делал один дизайнер,  а&nbsp;эту целая студия,  а&nbsp;вот у&nbsp;этой даже картограф есть,  цо-цо-цо».</p><p>-ссылка-</p>', '<p><span style=\"background-color: white; \">Во-вторых,  нельзя было вывешивать на&nbsp;голосование по-разному нашпигованные схемы. У&nbsp;Бирмана,  например нет тысячи электричковых линий,  а&nbsp;у схемы студии есть. В&nbsp;результате ребята выбирают не&nbsp;лучшую схему,  пытаясь вникнуть в&nbsp;суть,  а&nbsp;смотрят,  где побольше нарисовано. Если Бирмана допустили в&nbsp;конкурс без электричек,  то&nbsp;и&nbsp;с остальных схем надо было электрички снять на&nbsp;время голосования.</span></p><p>Ну и&nbsp;сам интерфейс голосования адский. Я,  например,  не&nbsp;смог проголосовать сначала. Не&nbsp;потому что сайт лежал. Нет,  он работал. Я&nbsp;не мог проголосовать,  потому что не&nbsp;дождался смски с кодом. Потому что,  оказывается,  надо было капчу ввести. Схуя ли? Мне написано «На&nbsp;ваш номер будет отправлено 1 СМС с кодом подтверждения. Введите номер,  нажмите кнопку». Ну дак я&nbsp;ввёл,  нажал и&nbsp;стал ждать смс. Если я&nbsp;уже нажал кнопку «ОК»,  то&nbsp;я&nbsp;не смотрю,  появилась ли там где-то капча. Если я&nbsp;с первого раза не&nbsp;смог,  могу себе представить,  сколько нормальный людей сыпется мимо голосования.</p>', '2013-10-16 20:46:41', '2013-10-16 20:48:57', '0', '1', '0', '0', '', '  ', '0', '1', '0.00', '', '', 'backup', '4', '0', '0');");
$db->sql_query("INSERT INTO `".$prefix."_pages` VALUES ( '6', 'blog', '0', 'Голосование за схему метро', '<p><span style=\"background-color: white; \">Я&nbsp;считаю,  что голосование на&nbsp;</span><a href=\"http://dt.mos.ru/metro/\" style=\"background-color: white; \">конкурсе</a><span style=\"background-color: white; \"> устроено очень плохо и&nbsp;от этого получается предвзятым.</span></p><p>Во-первых,  нельзя было прямо на&nbsp;странице голосования писать авторов схем. Да,  три тысячи дизайнеров всё равно узнали бы,  где чьё. Но&nbsp;остальные выбирают не&nbsp;схему,  а&nbsp;автора: «О,  это схему делал один дизайнер,  а&nbsp;эту целая студия,  а&nbsp;вот у&nbsp;этой даже картограф есть,  цо-цо-цо».</p>', '<p><span style=\"background-color: white; \">Во-вторых,  нельзя было вывешивать на&nbsp;голосование по-разному нашпигованные схемы. У&nbsp;Бирмана,  например нет тысячи электричковых линий,  а&nbsp;у схемы студии есть. В&nbsp;результате ребята выбирают не&nbsp;лучшую схему,  пытаясь вникнуть в&nbsp;суть,  а&nbsp;смотрят,  где побольше нарисовано. Если Бирмана допустили в&nbsp;конкурс без электричек,  то&nbsp;и&nbsp;с остальных схем надо было электрички снять на&nbsp;время голосования.</span></p><p>Ну и&nbsp;сам интерфейс голосования адский. Я,  например,  не&nbsp;смог проголосовать сначала. Не&nbsp;потому что сайт лежал. Нет,  он работал. Я&nbsp;не мог проголосовать,  потому что не&nbsp;дождался смски с кодом. Потому что,  оказывается,  надо было капчу ввести. Схуя ли? Мне написано «На&nbsp;ваш номер будет отправлено 1 СМС с кодом подтверждения. Введите номер,  нажмите кнопку». Ну дак я&nbsp;ввёл,  нажал и&nbsp;стал ждать смс. Если я&nbsp;уже нажал кнопку «ОК»,  то&nbsp;я&nbsp;не смотрю,  появилась ли там где-то капча. Если я&nbsp;с первого раза не&nbsp;смог,  могу себе представить,  сколько нормальный людей сыпется мимо голосования.</p>', '2013-10-16 20:46:41', '2013-10-16 20:50:46', '0', '1', '0', '0', '', '  ', '0', '1', '0.00', '', '', 'backup', '4', '0', '0');");
$db->sql_query("INSERT INTO `".$prefix."_pages` VALUES ( '7', 'blog', '0', 'Голосование за схему метро', '<p><span style=\"background-color: white; \">Я&nbsp;считаю,  что голосование на&nbsp;</span><a href=\"http://dt.mos.ru/metro/\" style=\"background-color: white; \">конкурсе</a><span style=\"background-color: white; \"> устроено очень плохо и&nbsp;от этого получается предвзятым.</span></p><p>Во-первых,  нельзя было прямо на&nbsp;странице голосования писать авторов схем. Да,  три тысячи дизайнеров всё равно узнали бы,  где чьё. Но&nbsp;остальные выбирают не&nbsp;схему,  а&nbsp;автора: «О,  это схему делал один дизайнер,  а&nbsp;эту целая студия,  а&nbsp;вот у&nbsp;этой даже картограф есть,  цо-цо-цо»…  …  </p>', '<p><span style=\"background-color: white; \">Во-вторых,  нельзя было вывешивать на&nbsp;голосование по-разному нашпигованные схемы. У&nbsp;Бирмана,  например нет тысячи электричковых линий,  а&nbsp;у схемы студии есть. В&nbsp;результате ребята выбирают не&nbsp;лучшую схему,  пытаясь вникнуть в&nbsp;суть,  а&nbsp;смотрят,  где побольше нарисовано. Если Бирмана допустили в&nbsp;конкурс без электричек,  то&nbsp;и&nbsp;с остальных схем надо было электрички снять на&nbsp;время голосования.</span></p><p>Ну и&nbsp;сам интерфейс голосования адский. Я,  например,  не&nbsp;смог проголосовать сначала. Не&nbsp;потому что сайт лежал. Нет,  он работал. Я&nbsp;не мог проголосовать,  потому что не&nbsp;дождался смски с кодом. Потому что,  оказывается,  надо было капчу ввести. Схуя ли? Мне написано «На&nbsp;ваш номер будет отправлено 1 СМС с кодом подтверждения. Введите номер,  нажмите кнопку». Ну дак я&nbsp;ввёл,  нажал и&nbsp;стал ждать смс. Если я&nbsp;уже нажал кнопку «ОК»,  то&nbsp;я&nbsp;не смотрю,  появилась ли там где-то капча. Если я&nbsp;с первого раза не&nbsp;смог,  могу себе представить,  сколько нормальный людей сыпется мимо голосования.</p>', '2013-10-16 20:46:41', '2013-10-16 20:50:57', '0', '1', '0', '0', '', '  ', '0', '1', '0.00', '', '', 'backup', '4', '0', '0');");
$db->sql_query("INSERT INTO `".$prefix."_pages` VALUES ( '8', 'blog', '0', 'Голосование за схему метро', '<p><span style=\"background-color: white; \">Я&nbsp;считаю,  что голосование на&nbsp;</span><a href=\"http://dt.mos.ru/metro/\" style=\"background-color: white; \">конкурсе</a><span style=\"background-color: white; \"> устроено очень плохо и&nbsp;от этого получается предвзятым.</span></p><p>Во-первых,  нельзя было прямо на&nbsp;странице голосования писать авторов схем. Да,  три тысячи дизайнеров всё равно узнали бы,  где чьё. Но&nbsp;остальные выбирают не&nbsp;схему,  а&nbsp;автора: «О,  это схему делал один дизайнер,  а&nbsp;эту целая студия,  а&nbsp;вот у&nbsp;этой даже картограф есть,  цо-цо-цо»…  </p>', '<p><span style=\"background-color: white; \">Во-вторых,  нельзя было вывешивать на&nbsp;голосование по-разному нашпигованные схемы. У&nbsp;Бирмана,  например нет тысячи электричковых линий,  а&nbsp;у схемы студии есть. В&nbsp;результате ребята выбирают не&nbsp;лучшую схему,  пытаясь вникнуть в&nbsp;суть,  а&nbsp;смотрят,  где побольше нарисовано. Если Бирмана допустили в&nbsp;конкурс без электричек,  то&nbsp;и&nbsp;с остальных схем надо было электрички снять на&nbsp;время голосования.</span></p><p>Ну и&nbsp;сам интерфейс голосования адский. Я,  например,  не&nbsp;смог проголосовать сначала. Не&nbsp;потому что сайт лежал. Нет,  он работал. Я&nbsp;не мог проголосовать,  потому что не&nbsp;дождался смски с кодом. Потому что,  оказывается,  надо было капчу ввести. Схуя ли? Мне написано «На&nbsp;ваш номер будет отправлено 1 СМС с кодом подтверждения. Введите номер,  нажмите кнопку». Ну дак я&nbsp;ввёл,  нажал и&nbsp;стал ждать смс. Если я&nbsp;уже нажал кнопку «ОК»,  то&nbsp;я&nbsp;не смотрю,  появилась ли там где-то капча. Если я&nbsp;с первого раза не&nbsp;смог,  могу себе представить,  сколько нормальный людей сыпется мимо голосования.</p>', '2013-10-16 20:46:41', '2013-10-16 21:14:57', '0', '1', '0', '0', '', '  ', '0', '1', '0.00', '', '', 'backup', '4', '0', '0');");

$db->sql_query("INSERT INTO `".$prefix."_pages_comments` VALUES ( '1', '2', 'Пророк', '', '', '<p>Отлично!!! </p>', 'unknown', '2013.10.16 20:05:44', '0', 'pages', '0', '', '', '1');");
?>