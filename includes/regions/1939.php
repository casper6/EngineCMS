<?php global $prefix, $db; if ($obl == 1) {
$db->sql_query("INSERT INTO `".$prefix."_regions` (`id`, `region_id`, `raion_id`, `name`) VALUES
('1939', '0', '0', 'Санкт-Петербург, ');"); } if ($raion == 1 || $gorod == 1) {
$db->sql_query("INSERT INTO `".$prefix."_regions` (`id`, `region_id`, `raion_id`, `name`) VALUES 
('1940', '1939', '1939', 'Санкт-Петербург, г'),
('1941', '1939', '1939', 'Ленинград, г'),
('201803', '1939', '1939', 'Александровская (Курортный р-н), п'),
('201804', '1939', '1939', 'Александровская (Пушкинский р-н), п'),
('201805', '1939', '1939', 'Белоостров, п'),
('201806', '1939', '1939', 'Володарская, ст'),
('201807', '1939', '1939', 'Горелово, п'),
('201808', '1939', '1939', 'Горская, ст'),
('201809', '1939', '1939', 'Комарово, п'),
('201810', '1939', '1939', 'Лахта, п'),
('201811', '1939', '1939', 'Левашово, п'),
('201812', '1939', '1939', 'Лисий Нос, п'),
('201813', '1939', '1939', 'Металлострой, п'),
('201814', '1939', '1939', 'Можайская, ст'),
('201815', '1939', '1939', 'Молодежное, п'),
('201816', '1939', '1939', 'Ольгино, п'),
('201817', '1939', '1939', 'Парголово, п'),
('201818', '1939', '1939', 'Песочный, п'),
('201819', '1939', '1939', 'Петро-Славянка, п'),
('201820', '1939', '1939', 'Понтонный, п'),
('201821', '1939', '1939', 'Разлив, ст'),
('201822', '1939', '1939', 'Репино, п'),
('201823', '1939', '1939', 'Саперный, п'),
('201824', '1939', '1939', 'Серово, п'),
('201825', '1939', '1939', 'Смолячково, п'),
('201826', '1939', '1939', 'Солнечное, п'),
('201827', '1939', '1939', 'Старо-Паново, д'),
('201828', '1939', '1939', 'Тарховка, п'),
('201829', '1939', '1939', 'Торики, д'),
('201830', '1939', '1939', 'Тярлево, п'),
('201831', '1939', '1939', 'Усть-Ижора, п'),
('201832', '1939', '1939', 'Ушково, п'),
('201833', '1939', '1939', 'Шушары, п'),
('201834', '1939', '1939', 'Стрельна, п'),
('201835', '1939', '1939', 'Зеленогорск, г'),
('201836', '1939', '1939', 'Колпино, г'),
('201837', '1939', '1939', 'Красное Село, г'),
('201838', '1939', '1939', 'Кронштадт, г'),
('201839', '1939', '1939', 'Ломоносов, г'),
('201840', '1939', '1939', 'Павловск, г'),
('201841', '1939', '1939', 'Петергоф, г'),
('201842', '1939', '1939', 'Пушкин, г'),
('201843', '1939', '1939', 'Сестрорецк, г'),
('201844', '1939', '1939', 'Петродворец, г');");
} ?>
