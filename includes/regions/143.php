<?php global $prefix, $db; if ($obl == 1) {
$db->sql_query("INSERT INTO `".$prefix."_regions` (`id`, `region_id`, `raion_id`, `name`) VALUES
('143', '0', '0', 'Ингушетия, ');"); } if ($raion == 1) {
$db->sql_query("INSERT INTO `".$prefix."_regions` (`id`, `region_id`, `raion_id`, `name`) VALUES 
('144', '143', '0', 'Назрановский, р-н'),
('145', '143', '0', 'Джейрахский, р-н'),
('146', '143', '0', 'Малгобекский, р-н'),
('147', '143', '0', 'Сунженский, р-н');"); } if ($gorod == 1) {
$db->sql_query("INSERT INTO `".$prefix."_regions` (`id`, `region_id`, `raion_id`, `name`) VALUES 
('11958', '144', '143', 'Назрань, г'),
('11959', '144', '143', 'Али-Юрт, с'),
('11960', '144', '143', 'Гази-Юрт, с'),
('11961', '144', '143', 'Долаково, с'),
('11962', '144', '143', 'Кантышево, с'),
('11963', '144', '143', 'Сурхахи, с'),
('11964', '144', '143', 'Экажево, с'),
('11965', '144', '143', 'Яндаре, с'),
('11966', '144', '143', 'Гайрбек-Юрт, с'),
('11967', '144', '143', 'Гиреевский, х'),
('11968', '144', '143', 'Альтиевский МО, тер'),
('11969', '144', '143', 'Барсукинский МО, тер'),
('11970', '144', '143', 'Гамурзиевский МО, тер'),
('11971', '144', '143', 'Насыр-Кортский МО, тер'),
('11972', '144', '143', 'Плиевский МО, тер'),
('11973', '144', '143', 'Центральный МО, тер'),
('11974', '145', '143', 'Джейрах, с'),
('11975', '145', '143', 'Гули, с'),
('11976', '145', '143', 'Ляжги, с'),
('11977', '145', '143', 'Ольгети, с'),
('11978', '145', '143', 'Армхи, с'),
('11979', '145', '143', 'Бейни, с'),
('11980', '145', '143', 'Бишти, с'),
('11981', '145', '143', 'Лейми, с'),
('11982', '145', '143', 'Пялинг, с'),
('11983', '145', '143', 'Эгикал, с'),
('11984', '146', '143', 'Малгобек, г'),
('11985', '146', '143', 'Аки-Юрт, с'),
('11986', '146', '143', 'Вежарий-Юрт, с'),
('11987', '146', '143', 'Верхние Ачалуки, с'),
('11988', '146', '143', 'Вознесенская, ст-ца'),
('11989', '146', '143', 'Зязиков-Юрт, с'),
('11990', '146', '143', 'Инарки, с'),
('11991', '146', '143', 'Нижние Ачалуки, с'),
('11992', '146', '143', 'Новый Редант, с'),
('11993', '146', '143', 'Пседах, с'),
('11994', '146', '143', 'Сагопши, с'),
('11995', '146', '143', 'Средние Ачалуки, с'),
('11996', '146', '143', 'Южный, п'),
('11997', '146', '143', 'Бековичи, п'),
('11998', '146', '143', 'Чкалово, п'),
('11999', '146', '143', '36 Участок, нп'),
('12000', '146', '143', 'Южное, п'),
('12001', '146', '143', 'Рустам, городок'),
('12002', '147', '143', 'Карабулак, г'),
('12003', '147', '143', 'Орджоникидзевская, ст-ца'),
('12004', '147', '143', 'Алкун, с'),
('12005', '147', '143', 'Алхасты, с'),
('12006', '147', '143', 'Аршты, с'),
('12007', '147', '143', 'Ассиновская, ст-ца'),
('12008', '147', '143', 'Галашки, с'),
('12009', '147', '143', 'Мужичи, с'),
('12010', '147', '143', 'Нестеровская, ст-ца'),
('12011', '147', '143', 'Серноводск, с'),
('12012', '147', '143', 'Троицкая, ст-ца'),
('12013', '147', '143', 'Чемульга, с'),
('12014', '147', '143', 'Верхний Алкун, с'),
('12015', '147', '143', 'Даттых, с'),
('12016', '147', '143', 'Берда-Юрт, с'),
('12017', '147', '143', '1-й, мкр'),
('12018', '147', '143', '2-й, мкр'),
('12019', '147', '143', '3-й, мкр');");
} ?>