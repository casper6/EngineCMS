<?php


require_once("mainfile.php");

global $prefix;


$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_authors`;");
$db->sql_query("CREATE TABLE `".$prefix."_authors` (
 `aid` varchar(25) NOT NULL,
 `name` varchar(50),
 `pwd` varchar(40),
 `realadmin` tinyint(1) DEFAULT '1',
 `link` varchar(250) NOT NULL,
 `all` enum('0','1') NOT NULL,
 PRIMARY KEY (`aid`)
);");


$db->sql_query("INSERT INTO `".$prefix."_authors` VALUES ( 'admin', 'BOG', '43c16d460b053cdc0fea85dd5f25edf8', '1', '', '0');");

$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_banned_ip`;");
$db->sql_query("CREATE TABLE `".$prefix."_banned_ip` (
 `id` int(11) NOT NULL auto_increment,
 `ip_address` varchar(15) NOT NULL,
 `reason` varchar(255) NOT NULL,
 `date` date DEFAULT '0000-00-00' NOT NULL,
 PRIMARY KEY (`id`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_bases`;");
$db->sql_query("CREATE TABLE `".$prefix."_bases` (
 `id` int(10) NOT NULL auto_increment,
 `base` varchar(255) NOT NULL,
 `data` datetime NOT NULL,
 `user` varchar(255) DEFAULT 'Администратор' NOT NULL,
 `pass` varchar(255) NOT NULL,
 `pause` varchar(1) DEFAULT '0' NOT NULL,
 `info` mediumtext NOT NULL,
 PRIMARY KEY (`id`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_cash`;");
$db->sql_query("CREATE TABLE `".$prefix."_cash` (
 `id` int(10) NOT NULL auto_increment,
 `url` varchar(300) NOT NULL,
 `data` datetime NOT NULL,
 `text` longtext,
 PRIMARY KEY (`id`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_config`;");
$db->sql_query("CREATE TABLE `".$prefix."_config` (
 `sitename` varchar(255) NOT NULL,
 `startdate` smallint(1) NOT NULL,
 `adminmail` varchar(255) NOT NULL,
 `keywords` varchar(500) NOT NULL,
 `description` varchar(500) NOT NULL,
 `counter` varchar(5000) NOT NULL,
 `statlink` varchar(255) NOT NULL,
 `postlink` varchar(255) NOT NULL,
 `registr` tinyint(1) NOT NULL,
 `pogoda` tinyint(1) NOT NULL,
 `flash` tinyint(1) NOT NULL,
 `sgatie` mediumtext NOT NULL,
 `stopcopy` tinyint(1) NOT NULL,
 `nocashe` mediumtext NOT NULL,
 `adminmes` mediumtext NOT NULL,
 `red` tinyint(1) NOT NULL,
 `comment` tinyint(1) NOT NULL,
 `captcha_ok` tinyint(1) NOT NULL,
 `ht_backup` varchar(255) NOT NULL,
 PRIMARY KEY (`sitename`)
);");


$db->sql_query("INSERT INTO `".$prefix."_config` VALUES ( 'Название сайта', '2012', 'mail@gmail.com', '', '', '', '', 'http://gmail.com', '0', '0', '0', '|||||||||||||||||||||||||||||||||||||||||||||', '0', '1|1|1|1|1|0|0|1|17', '', '4', '0', '0', '.ht_backup');");

$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_golos`;");
$db->sql_query("CREATE TABLE `".$prefix."_golos` (
 `gid` int(10) NOT NULL auto_increment,
 `ip` varchar(15) NOT NULL,
 `golos` mediumtext NOT NULL,
 `num` int(10) NOT NULL,
 `data` varchar(19) NOT NULL,
 PRIMARY KEY (`gid`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_mainpage`;");
$db->sql_query("CREATE TABLE `".$prefix."_mainpage` (
 `id` int(10) NOT NULL auto_increment,
 `type` tinyint(1) NOT NULL,
 `name` varchar(255) NOT NULL,
 `title` varchar(255) NOT NULL,
 `text` mediumtext NOT NULL,
 `useit` mediumtext,
 `shablon` mediumtext NOT NULL,
 `counter` int(10) DEFAULT '0' NOT NULL,
 `tables` enum('pages','del','backup') DEFAULT 'pages' NOT NULL,
 `color` tinyint(1),
 `description` varchar(255) NOT NULL,
 `keywords` varchar(255) NOT NULL,
 PRIMARY KEY (`id`)
);");


$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '1', '0', '', 'Главный дизайн', 'что-то в шапке<br>[содержание]<br>футер сайта', '20', '', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '24', '2', 'index', 'Главная страница', 'pages|design=1', 'Текст главной страницы', '', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '20', '1', 'index', 'Главный стиль', 'body {
margin: 0px;
font-family: Calibri, Verdana, Geneva, sans-serif;
}

.img_left {float: left; padding-right: 10px;}
.img_right {float: right; padding-left: 10px;}

a:link {color: #0071ff;}
a:visited {color: #0071ff;}
a:hover {color: #FF0000;}
a:active {color: #000000;}

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

.main_cat_links {}

/* Для информации и списков в блоках */
.main_razdel {}
.pod_razdel {}
.pod_razdel a:link {}
.pod_razdel a:visited {}
.pod_razdel a:hover {}

/* Для категорий */
.cat_page {}
.cat_page_title {}
.cat_page_text {}
.cat_page_counter {}
.cat_page_comments {}
.cat_page_date {}
.cat_description {}

.cat_razdel {}

.cat_categorii_link {} 
.cat_podcategorii_link {}
div.cat_podcategorii_link a {}

a.cat_categorii_link:link, a.cat_categorii_link:visited {}
a.cat_categorii_link:hover, a.cat_categorii_link:active {}

.cat_title {}
.cat_page_commnum {}

.cat_maincategorii_link {}
.cat_mainpodcategorii_link {}
.cat_podcategorii {}
.cat_mainpodcategorii {}


/* Для страниц */
.page_comm, .page_another, .send_comment {}
.page_title {}
.page_opentext {}
.page_text {}
.page_comm_avtor {}
.page_comm_data {}
.page_date {}
.page_forum_avtor {}

.send_comment, a.send_comment, .send_post, a.send_post {}
a.comm_write {}

/* Поиск */
.main_search_input {}
.main_search {}
.main_search_button {}


select, option, input {}

.dalee {}

hr {}
i, em {}

.a_block_title {}
.main_title {}
.block_open_text {} 

li.block_li_title, .block_title {}
li.block_li_title a, .block_title a {}
li.block_li_title a:hover, .block_title a:hover {}

.venzel { display:none; }
.razdel { display:none; }

/* Открыть все */
.open_all {display: block; float: right;}
.open_all_small, a:link .open_all_small, a:visited .open_all_small, a:hover .open_all_small {}
a.open_all_link {}
', '', '', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '301', '2', 'contact', 'Контакты', 'pages|design=288&designpages=0&razdel_shablon=0&view=0&comment_shablon=0&page_shablon=0&limkol=1&lim=10&pagekol=0&pagenumbers=0&sort=date desc&reclama=&search=0&search_papka=0&no_html_in_opentext=0&no_html_in_text=0&table_light=0&menushow=1&razdel_link=1&podrazdel_show=1&podrazdel_active_show=1&tags=1&tag_text_show=Ключевые слова&tags_type=0&tags_show=1&golos=0&golosrazdel=0&golostype=0&titleshow=1&datashow=0&peopleshow=1&favorites=0&socialnetwork=0&put_in_blog=0&tipograf=1&comments=1&comments_main=0&comments_add=1&vetki=0&comments_desc=0&comments_mail=1&comments_adres=0&comments_tel=1&comments_num=0&comments_all=0&media_comment=0&comments_1=Комментарии&comments_2=Оставьте ваш вопрос или комментарий:&comments_3=Ваше имя:&comments_4=Ваш e-mail:&comments_5=Ваш адрес:&comments_6=Ваш телефон:&comments_7=Ваш вопрос или комментарий:&comments_8=Раскрыть все комментарии&tema_zapret_comm=0&post=0&show_add_post_on_first_page=0&show_add_post_fileform=0&tema=Открыть новую тему&tema_name=Ваше имя&tema_title=Название темы&tema_opis=Подробнее (содержание темы)&tema_zapret=1&calendar=&podrobno=1', '

<p>[название]</p>
<p>Здесь можно написать контактную информацию...</p>', '

', '0', 'pages', '0', '', '');");
$db->sql_query("INSERT INTO `".$prefix."_mainpage` VALUES ( '305', '2', 'news', 'Новости', 'pages|design=288&designpages=0&razdel_shablon=0&view=0&comment_shablon=2&page_shablon=0&limkol=1&lim=10&pagekol=0&pagenumbers=0&sort=date desc&reclama=&search=0&search_papka=0&no_html_in_opentext=0&no_html_in_text=0&table_light=0&menushow=1&razdel_link=1&podrazdel_show=1&podrazdel_active_show=1&tags=1&tag_text_show=Ключевые слова&tags_type=0&tags_show=1&golos=0&golosrazdel=0&golostype=0&titleshow=1&datashow=0&peopleshow=0&favorites=1&socialnetwork=0&put_in_blog=0&tipograf=1&comments=1&comments_main=0&comments_add=2&vetki=2&comments_desc=0&comments_mail=1&comments_adres=0&comments_tel=0&comments_num=0&comments_all=0&media_comment=0&comments_1=Комментарии&comments_2=Оставьте ваш вопрос или комментарий:&comments_3=Ваше имя:&comments_4=Ваш e-mail:&comments_5=Ваш адрес:&comments_6=Ваш телефон:&comments_7=Ваш вопрос или комментарий:&comments_8=Раскрыть все комментарии&tema_zapret_comm=2&post=0&show_add_post_on_first_page=0&show_add_post_fileform=0&tema=Открыть новую тему&tema_name=Ваше имя&tema_title=Название темы&tema_opis=Подробнее (содержание темы)&tema_zapret=1&calendar=&podrobno=1', '[содержание]', '', '0', 'pages', '0', '', '');");

$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_pages`;");
$db->sql_query("CREATE TABLE `".$prefix."_pages` (
 `pid` int(10) NOT NULL auto_increment,
 `module` varchar(255) NOT NULL,
 `cid` mediumint(6) NOT NULL,
 `title` varchar(2000) NOT NULL,
 `open_text` mediumtext NOT NULL,
 `main_text` mediumtext NOT NULL,
 `date` datetime DEFAULT '0000-00-00 00:00:00',
 `redate` datetime DEFAULT '0000-00-00 00:00:00',
 `counter` mediumint(6) NOT NULL,
 `active` tinyint(1) NOT NULL,
 `golos` mediumint(6) NOT NULL,
 `comm` mediumint(6) NOT NULL,
 `foto` varchar(255) NOT NULL,
 `search` mediumtext NOT NULL,
 `mainpage` tinyint(1) NOT NULL,
 `rss` tinyint(1) NOT NULL,
 `price` decimal(10,2) NOT NULL,
 `description` varchar(200) NOT NULL,
 `keywords` mediumtext NOT NULL,
 `tables` enum('pages','del','backup') DEFAULT 'pages' NOT NULL,
 `copy` int(10) DEFAULT '0' NOT NULL,
 `sort` smallint(5) NOT NULL,
 PRIMARY KEY (`pid`),
 KEY cid (`cid`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_pages_categories`;");
$db->sql_query("CREATE TABLE `".$prefix."_pages_categories` (
 `cid` mediumint(6) NOT NULL auto_increment,
 `module` varchar(255) NOT NULL,
 `title` varchar(255) NOT NULL,
 `description` mediumtext NOT NULL,
 `pic` varchar(255) NOT NULL,
 `sort` smallint(5) NOT NULL,
 `counter` mediumint(6) NOT NULL,
 `parent_id` mediumint(6) NOT NULL,
 `tables` enum('pages','del','backup') DEFAULT 'pages' NOT NULL,
 PRIMARY KEY (`cid`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_pages_comments`;");
$db->sql_query("CREATE TABLE `".$prefix."_pages_comments` (
 `cid` int(10) NOT NULL auto_increment,
 `num` int(10) DEFAULT '0' NOT NULL,
 `avtor` varchar(255) NOT NULL,
 `ava` varchar(255) NOT NULL,
 `mail` varchar(255) NOT NULL,
 `text` mediumtext NOT NULL,
 `ip` varchar(15) NOT NULL,
 `data` varchar(20) NOT NULL,
 `golos` smallint(2) DEFAULT '0',
 `tables` enum('pages','del','backup') DEFAULT 'pages' NOT NULL,
 `drevo` int(10) DEFAULT '0' NOT NULL,
 `adres` varchar(2000) NOT NULL,
 `tel` varchar(255) NOT NULL,
 `active` tinyint(1) DEFAULT '1' NOT NULL,
 PRIMARY KEY (`cid`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_pages_golos`;");
$db->sql_query("CREATE TABLE `".$prefix."_pages_golos` (
 `gid` int(10) NOT NULL auto_increment,
 `ip` varchar(15) NOT NULL,
 `golos` smallint(2) DEFAULT '0' NOT NULL,
 `num` int(10) DEFAULT '0' NOT NULL,
 `data` varchar(19) NOT NULL,
 `tables` enum('pages','del','backup') DEFAULT 'pages' NOT NULL,
 PRIMARY KEY (`gid`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_search`;");
$db->sql_query("CREATE TABLE `".$prefix."_search` (
 `id` int(10) NOT NULL auto_increment,
 `ip` varchar(20) NOT NULL,
 `slovo` varchar(255) NOT NULL,
 `data` datetime NOT NULL,
 `pages` varchar(20) NOT NULL,
 PRIMARY KEY (`id`)
);");



$db->sql_query("DROP TABLE IF EXISTS `".$prefix."_spiski`;");
$db->sql_query("CREATE TABLE `".$prefix."_spiski` (
 `id` int(10) NOT NULL auto_increment,
 `type` varchar(255) NOT NULL,
 `name` mediumtext,
 `opis` mediumtext NOT NULL,
 `sort` int(10) DEFAULT '0',
 `pages` mediumtext NOT NULL,
 `parent` int(10) DEFAULT '0' NOT NULL,
 PRIMARY KEY (`id`)
);");


print ("<center><h2>Обновление базы данных окончено!</h2><br>");
?>