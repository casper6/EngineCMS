<?php global $prefix, $db; if ($obl == 1) {
$db->sql_query("INSERT INTO `".$prefix."_regions` (`id`, `region_id`, `raion_id`, `name`) VALUES
('1981', '0', '0', 'Чукотский автономный округ, ');"); } if ($raion == 1) {
$db->sql_query("INSERT INTO `".$prefix."_regions` (`id`, `region_id`, `raion_id`, `name`) VALUES 
('1982', '1981', '0', 'Беринговский, р-н'),
('1983', '1981', '0', 'Билибинский, р-н'),
('1984', '1981', '0', 'Иультинский, р-н'),
('1985', '1981', '0', 'Провиденский, р-н'),
('1986', '1981', '0', 'Чаунский, р-н'),
('1987', '1981', '0', 'Чукотский, р-н'),
('1988', '1981', '0', 'Шмидтовский, р-н'),
('1989', '1981', '0', 'Анадырский, р-н');"); } if ($gorod == 1) {
$db->sql_query("INSERT INTO `".$prefix."_regions` (`id`, `region_id`, `raion_id`, `name`) VALUES 
('201464', '1982', '1981', 'Алькатваам, с'),
('201465', '1982', '1981', 'Зареченск, п'),
('201466', '1982', '1981', 'Мейныпильгыно, с'),
('201467', '1982', '1981', 'Хатырка, с'),
('201468', '1983', '1981', 'Билибино, г'),
('201469', '1983', '1981', 'Алискерово, пгт'),
('201470', '1983', '1981', 'Анюйск, с'),
('201471', '1983', '1981', 'Весенний, п'),
('201472', '1983', '1981', 'Илирней, с'),
('201473', '1983', '1981', 'Кепервеем, с'),
('201474', '1983', '1981', 'Омолон, с'),
('201475', '1983', '1981', 'Островное, с'),
('201476', '1983', '1981', 'Встречный, п'),
('201477', '1983', '1981', 'Дальний, п'),
('201478', '1983', '1981', 'Мандриково, п'),
('201479', '1984', '1981', 'Эгвекинот, рп'),
('201480', '1984', '1981', 'Амгуэма, с'),
('201481', '1984', '1981', 'Ванкарем, с'),
('201482', '1984', '1981', 'Конергино, с'),
('201483', '1984', '1981', 'Нутэпэльмен, с'),
('201484', '1984', '1981', 'Озерный, п'),
('201485', '1984', '1981', 'Уэлькаль, с'),
('201486', '1984', '1981', 'Восточный Прииск, нп'),
('201487', '1984', '1981', 'Иультин, п'),
('201488', '1984', '1981', 'Ленотап ручей, п'),
('201489', '1984', '1981', 'Пеньельхин ручей, п'),
('201490', '1984', '1981', 'Эгвекинот 2-й, рп'),
('201491', '1984', '1981', 'Эгвекинот 1-й, п'),
('201492', '1984', '1981', 'Мыс Шмидта, пгт'),
('201493', '1984', '1981', 'Биллингс, с'),
('201494', '1984', '1981', 'Ленинградский, пгт'),
('201495', '1984', '1981', 'Рыркайпий, с'),
('201496', '1984', '1981', 'Ушаковское, с'),
('201497', '1984', '1981', 'Полярный, п'),
('201498', '1985', '1981', 'Провидения, п'),
('201499', '1985', '1981', 'Новое Чаплино, с'),
('201500', '1985', '1981', 'Нунлигран, с'),
('201501', '1985', '1981', 'Сиреники, с'),
('201502', '1985', '1981', 'Урелики, с'),
('201503', '1985', '1981', 'Энмелен, с'),
('201504', '1985', '1981', 'Янракыннот, с'),
('201505', '1985', '1981', 'Провидения 2-е, п'),
('201506', '1986', '1981', 'Певек, г'),
('201507', '1986', '1981', 'Айон, с'),
('201508', '1986', '1981', 'Апапельгино, п'),
('201509', '1986', '1981', 'Бараниха, пгт'),
('201510', '1986', '1981', 'Быстрый, п'),
('201511', '1986', '1981', 'Валькумей, пгт'),
('201512', '1986', '1981', 'Комсомольский, пгт'),
('201513', '1986', '1981', 'Рыткучи, с'),
('201514', '1986', '1981', 'Янранай, с'),
('201515', '1986', '1981', 'Западный, п'),
('201516', '1986', '1981', 'Красноармейский, пгт'),
('201517', '1987', '1981', 'Лаврентия, с'),
('201518', '1987', '1981', 'Инчоун, с'),
('201519', '1987', '1981', 'Лорино, с'),
('201520', '1987', '1981', 'Нешкан, с'),
('201521', '1987', '1981', 'Уэлен, с'),
('201522', '1987', '1981', 'Энурмино, с'),
('201523', '1988', '1981', 'Биллингс, с'),
('201524', '1988', '1981', 'Ленинградский, пгт'),
('201525', '1988', '1981', 'Мыс Шмидта, пгт'),
('201526', '1988', '1981', 'Полярный, п'),
('201527', '1988', '1981', 'Рыркайпий, с'),
('201528', '1988', '1981', 'Ушаковское, с'),
('201529', '1989', '1981', 'Беринговский, рп'),
('201530', '1989', '1981', 'Алькатваам, с'),
('201531', '1989', '1981', 'Беринговский, пгт'),
('201532', '1989', '1981', 'Мейныпильгыно, с'),
('201533', '1989', '1981', 'Хатырка, с'),
('201534', '1989', '1981', 'Зареченск, п'),
('201535', '1989', '1981', 'Угольные Копи, п'),
('201536', '1989', '1981', 'Ваеги, с'),
('201537', '1989', '1981', 'Канчалан, с'),
('201538', '1989', '1981', 'Краснено, с'),
('201539', '1989', '1981', 'Ламутское, с'),
('201540', '1989', '1981', 'Марково, с'),
('201541', '1989', '1981', 'Снежное, с'),
('201542', '1989', '1981', 'Усть-Белая, с'),
('201543', '1989', '1981', 'Чуванское, с'),
('201544', '1989', '1981', 'Шахтерский, пгт'),
('201545', '1989', '1981', 'Угольные Копи 1-й, п'),
('201546', '1989', '1981', 'Угольные Копи 2-й, п'),
('201547', '1989', '1981', 'Угольные Копи 3-й, п'),
('201548', '1989', '1981', 'Угольные Копи 4-й, п'),
('201549', '1989', '1981', 'Угольные Копи АОПП, п');");
} ?>