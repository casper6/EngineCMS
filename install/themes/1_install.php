<?php

$db->sql_query("INSERT INTO `".$prefix."_config` VALUES ( 'Мыльные пузыри России', '2013', '', '', '', '', '', 'http://gmail.com', '0', '0', '0', 'Рассвет|||||ООО «Рассвет+»|||||443106, г. Москва, ул. Преображенская, 24, офис 13|||||с 10 до 20|||||(945) 000-00-00|||||||||||||||mail@mail.ru|||||<img src=theme/map.jpg>|||||', '0', '1|1|1|1|1|1|0|1|22|1|1||0|theme/logotip.png|Мыльные пузыри оптом|51|8||0|monokai|monokai|monokai|monokai', 'Здесь можно оставлять записки, касающиеся работ над сайтом', '4', '0', '0', '.ht_backup');");

$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '1', '0', '', 'Главный дизайн', '<div id=\"main\">
 <header>
 <div id=\"logo\">
 <div id=\"logo_text\">
 <a href=\"\\\">[лого_проекта]</a>
 <h2>[название_проекта]</h2>
 </div>
 </div>
 <nav>
 <div id=\"menu_container\">[Главное меню]</div>
 </nav>
 </header>
 <div id=\"site_content\">
 <div id=\"sidebar_container\">[блоки справа]</div>
 <div id=\"content\">[содержание]</div>
 </div>
 <div id=\"scroll\">
 <a title=\"Наверх\" class=\"top\" href=\"#\"><img src=\"theme/top.png\" alt=\"top\" /></a>
 </div>
 <footer>
 <p>[год] [компания1], [название_проекта] [статистика]</p>
 </footer>
 </div>
<script>"."$(function() { "."$(\".top\").click(function() {"."$(\"html, body\").animate({scrollTop:0}, \"fast\"); return false;}); });</script>', '20', '', '0', 'pages', '0', '', '');");

$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '20', '1', 'index', 'Главный стиль', 'html { 
 height: 100%;
}

* { 
 margin: 0;
 padding: 0;
}

/* отрисовка элементов HTML 5 как блоков */
article, aside, figure, footer, header, hgroup, nav, section { 
 display:block;
}

body { 
 font: normal .85em arial, sans-serif;
 background: #F9F9F9;
 color: #555;
}

p { 
 padding: 0 0 20px 0;
 line-height: 1.7em;
}

img { 
 border: 0;
}

h1, h2, h3, h4, h5, h6 { 
 color: #362C20;
 letter-spacing: 0em;
 padding: 0 0 5px 0;
}

h1, h2, h4, .cat_title span { 
 font: normal 200% arial, sans-serif;
 text-shadow: 1px 1px #FFF;
 margin: 0 0 15px 0;
 padding: 15px 20px 5px 0;
 color: #555;
}

h2 { 
 font: normal 150% arial, sans-serif;
}

h4 { 
 font: normal 150% \"Yanone Kaffeesatz\", arial, sans-serif;
 padding: 5px 20px 5px 0;
 margin: 0;
 color: #555;
}

h3 { 
 color: #0FCFEB;
 padding: 10px 0 5px 0;
 font: normal 170% arial;
}

h5 { 
 color: #888;
 font: italic 95% arial;
 letter-spacing: normal;
 padding: 0 0 15px 0;
}

h6 { 
 padding: 5px 0 25px 0;
}

a, a:hover { 
 outline: none;
 text-decoration: underline;
 color: #0FCFEB;
}

a:hover { 
 text-decoration: none;
}

blockquote { 
 margin: 20px 0; 
 padding: 10px 20px 0 20px;
 border: 1px solid #E5E5DB;
 background: #FFF;
}

ul { 
 margin: 2px 0 22px 17px;
}

ul li { 
 list-style-type: circle;
 margin: 0 0 6px 0; 
 padding: 0 0 4px 5px;
 line-height: 1.5em;
}

ol { 
 margin: 8px 0 22px 20px;
}

ol li { 
 margin: 0 0 11px 0;
}

.left { 
 float: left;
 width: auto;
 margin-right: 10px;
}

.right { 
 float: right; 
 width: auto;
 margin-left: 10px;
}

.center { 
 display: block;
 text-align: center;
 margin: 20px auto;
}

#main, #logo, nav, #site_content, footer { 
 margin-left: auto; 
 margin-right: auto;
}

header { 
 background: #202020 url(theme/back.png) repeat-x;
 height: 165px;
}

#logo { 
 width: 875px;
 position: relative;
 height: 120px;
 background: url(theme/logo.png) no-repeat bottom right;
}

#logo h1, #logo h2 { 
 font: normal 220% arial, sans-serif;
 border-bottom: 0;
 text-transform: none;
 text-shadow: none;
 margin: 0;
}

#logo h2 { 
 font: normal 140% \'Yanone Kaffeesatz\', arial, sans-serif;
}

#logo_text h1, #logo_text h1 a, #logo_text h1 a:hover { 
 padding: 25px 0 0 0;
 color: #FFF;
 text-decoration: none;
}

#logo_text h1 a .logo_colour { 
 color: #09D4FF;
 text-shadow: 0;
}

#logo_text a:hover .logo_colour { 
 color: #FFF;
}

#logo_text h2 { 
 padding: 0 0 0 0;
 color: #A8AA94;
}

nav { 
 width: 935px;
 height: 44px;
 padding: 0 0 0 4px;
} 

#site_content { 
 width: 885px;
 overflow: hidden;
 margin: 20px auto 0 auto;
 padding: 0 0 20px 0;
} 

#sidebar_container { 
 float: right;
 width: 254px;
}

.sidebar { 
 width: 220px;
 background: #fff;
 -webkit-box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 2px;
 -moz-box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 2px;
 box-shadow: rgba(0, 0, 0, 0.2) 0px 2px 2px;
 border: 1px solid #eee;
 border-radius: 15px 15px 15px 15px;
 -moz-border-radius: 15px 15px 15px 15px;
 -webkit-border: 15px 15px 15px 15px;
 padding: 10px 15px 15px 15px;
 margin: 0 0 17px 0;
}

.sidebar ul { 
 width: 178px; 
 padding: 4px 0 0 0; 
 margin: 4px 0 30px 0;
}

.sidebar li { 
 list-style: none; 
 padding: 0 0 7px 0;
}

.sidebar li a, .sidebar li a:hover { 
 padding: 0 0 0 25px;
 display: block;
} 

.sidebar li a.selected { 
 color: #444;
} 

#content { 
 text-align: left;
 width: 600px;
 padding: 0 0 0 5px;
 float: left;
}
 
#content ul { 
 margin: 2px 0 22px 0px;
}

#content ul li, .sidebar ul li { 
 list-style-type: none;
 background: url(theme/bullet.png) no-repeat;
 margin: 0 0 0 0; 
 padding: 0 0 4px 28px;
 line-height: 1.5em;
}

footer { 
 width: 100%;
 font-family: \'trebuchet ms\', sans-serif;
 font-size: 100%;
 height: 80px;
 padding: 25px 0 5px 0;
 text-align: center; 
 background: #333;
 color: #A8AA94;
}

footer p { 
 line-height: 1.5em;
 padding: 0 0 10px 0;
}

footer a { 
 color: #A8AA94;
 text-decoration: none;
}

footer a:hover { 
 color: #FFF;
 text-decoration: none;
}

.form_settings { 
 margin: 15px 0 0 0;
}

.form_settings p { 
 padding: 0 0 4px 0;
}

.form_settings span { 
 float: left; 
 width: 200px; 
 text-align: left;
}
 
.form_settings input, .form_settings textarea { 
 padding: 5px; 
 width: 299px; 
 font: 100% arial; 
 border: 1px solid #E5E5DB; 
 background: #FFF; 
 color: #47433F;
 border-radius: 7px 7px 7px 7px;
 -moz-border-radius: 7px 7px 7px 7px;
 -webkit-border: 7px 7px 7px 7px; 
}
 
.form_settings .submit { 
 font: 100% arial; 
 border: 0; 
 width: 99px; 
 margin: 0 0 0 212px; 
 height: 33px;
 padding: 2px 0 3px 0;
 cursor: pointer; 
 background: #3B3B3B; 
 color: #FFF;
 border-radius: 7px 7px 7px 7px;
 -moz-border-radius: 7px 7px 7px 7px;
 -webkit-border: 7px 7px 7px 7px; 
}

.form_settings textarea, .form_settings select { 
 font: 100% arial; 
 width: 299px;
}

.form_settings select { 
 width: 310px;
}

.form_settings .checkbox { 
 margin: 4px 0; 
 padding: 0; 
 width: 14px;
 border: 0;
 background: none;
}

.separator { 
 width: 100%;
 height: 0;
 border-top: 1px solid #D9D5CF;
 border-bottom: 1px solid #FFF;
 margin: 0 0 20px 0;
}

/* scroll to top */
#scroll { 
 position: relative; 
 width: 900px;
 margin: 10px auto;
 bottom: 15px; 
 right: 0;
 background: red;
 padding: 0;
}
 
#scroll a { 
 float: right;
 margin: 0 0 0 0; 
 padding: 0 0 0 0;
}
 
#scroll a img { 
 float: right;
 padding: 0 0 0 0;
 margin: 0;
}


/* меню */
ul#nav { 
 float: left;
}

ul#nav ul { 
 background: #eee;
 margin-top: 0;
 border: 1px solid #ddd;
}

ul#nav li a { 
 padding:9px 25px 10px 25px;
 font: 155% \'Yanone Kaffeesatz\',helvetica,arial,verdana,sans;
 text-shadow: 1px 1px #FFF;
 text-decoration:none;
 color: #444;
}

ul#nav li a:hover, ul#nav li a:focus, ul#nav li.selected { 
 background: #FFF;
}

.menu-h-d { 
 min-height: 30px; 
 padding:0; 
 margin:0;
}
.menu-h-d li {
 height:30px;
}
.menu-h-d li a {
 color:black;
}
.menu-h-d li:hover a, .menu-h-d a.mainmenu_open {
 color: white;
}
.menu-h-d li:hover {
 background-color: gray;
}
.menu-h-d li:hover, .menu-h-d .li_mainmenu_open {
 -webkit-border-bottom-right-radius: 10px;
 -webkit-border-bottom-left-radius: 10px;
 -moz-border-radius-bottomright: 10px;
 -moz-border-radius-bottomleft: 10px;
 border-bottom-right-radius: 10px;
 border-bottom-left-radius: 10px;
}
.menu-h-d .li_mainmenu_open {
 background-color: black;
}
.menu-h-d ul { display: none; position: absolute; top: 30px; left: -1px; width: 300px; background: #666; border: 0; padding:0; margin:0;}
.menu-h-d ul ul { left: 100%; padding-top: 30px !important; }
}', '', '', '0', 'pages', '0', '', '');");

$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '24', '2', 'index', 'Главная страница', 'pages|design=1', 'Текст главной страницы', '', '0', 'pages', '0', '', '');");

$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '35', '0', '', 'Дизайн для блоков', '<div class=\"sidebar\">[содержание]</div>', '', '', '0', 'pages', '0', '', '');");

$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '36', '3', '2', 'Контакты', '[КОМПАНИЯ1]<br>
Адрес: [адрес компании1]<br>
Время работы: [время работы компании1]<br>
Тел.: [телефон компании1]<br>
Email: <a href=\"mailto:[почта компании1]\" title=\"Написать письмо\">[почта компании1]</a><br>
[карта компании1]', '|design=35&show_in_razdel=все&show_in_papka=&no_show_in_razdel=&html=0&titleshow=1', 'block-kontaktyi', '0', 'pages', '0', '', '');");

$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '37', '3', '2', 'блоки справа', '[Контакты]', '|design=0&show_in_razdel=все&show_in_papka=&no_show_in_razdel=&html=1&titleshow=0', 'block-bloki_sprava', '0', 'pages', '0', '', '');");

?>