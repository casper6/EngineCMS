<?php global $prefix, $db; if ($obl == 1) {
$db->sql_query("INSERT INTO `".$prefix."_regions` (`id`, `region_id`, `raion_id`, `name`) VALUES
('294', '0', '0', 'Северная Осетия, ');"); } if ($raion == 1) {
$db->sql_query("INSERT INTO `".$prefix."_regions` (`id`, `region_id`, `raion_id`, `name`) VALUES 
('295', '294', '0', 'Северная Осетия - Алания, Респ'),
('296', '294', '0', 'Алагирский, р-н'),
('297', '294', '0', 'Ардонский, р-н'),
('298', '294', '0', 'Дигорский, р-н'),
('299', '294', '0', 'Ирафский, р-н'),
('300', '294', '0', 'Кировский, р-н'),
('301', '294', '0', 'Моздокский, р-н'),
('302', '294', '0', 'Правобережный, р-н'),
('303', '294', '0', 'Пригородный, р-н');"); } if ($gorod == 1) {
$db->sql_query("INSERT INTO `".$prefix."_regions` (`id`, `region_id`, `raion_id`, `name`) VALUES 
('18494', '296', '294', 'Алагир, г'),
('18495', '296', '294', 'Верхний Бирагзанг, с'),
('18496', '296', '294', 'Бурон, п'),
('18497', '296', '294', 'Верхний Згид, п'),
('18498', '296', '294', 'Верхний Фиагдон, п'),
('18499', '296', '294', 'Дзуарикау, с'),
('18500', '296', '294', 'Горный Карца, с'),
('18501', '296', '294', 'Майрамадаг, с'),
('18502', '296', '294', 'Мизур, п'),
('18503', '296', '294', 'Архон, с'),
('18504', '296', '294', 'Нар, с'),
('18505', '296', '294', 'Зарамаг, с'),
('18506', '296', '294', 'Верхний Унал, с'),
('18507', '296', '294', 'Ногкау, с'),
('18508', '296', '294', 'Рамоново, с'),
('18509', '296', '294', 'Садон, п'),
('18510', '296', '294', 'Суадаг, с'),
('18511', '296', '294', 'Хаталдон, с'),
('18512', '296', '294', 'Холст, п'),
('18513', '296', '294', 'Црау, с'),
('18514', '296', '294', 'Красный Ход, с'),
('18515', '296', '294', 'Цаликово, с'),
('18516', '296', '294', 'Нузал, с'),
('18517', '296', '294', 'Тамиск, с'),
('18518', '296', '294', 'Зинцар, с'),
('18519', '296', '294', 'Лац, с'),
('18520', '296', '294', 'Тагардон, с'),
('18521', '296', '294', 'Варце, с'),
('18522', '296', '294', 'Згил, с'),
('18523', '296', '294', 'Камсхо, с'),
('18524', '296', '294', 'Калак, с'),
('18525', '296', '294', 'Лисри, с'),
('18526', '296', '294', 'Сагол, с'),
('18527', '296', '294', 'Сатат, с'),
('18528', '296', '294', 'Тиб, с'),
('18529', '296', '294', 'Тибсли, с'),
('18530', '296', '294', 'Тибели, с'),
('18531', '296', '294', 'Худисан, с'),
('18532', '296', '294', 'Цми, с'),
('18533', '296', '294', 'Гусыра, с'),
('18534', '296', '294', 'Кодахджин, с'),
('18535', '296', '294', 'Елгона, с'),
('18536', '296', '294', 'Зригатта, с'),
('18537', '296', '294', 'Кесатикау, с'),
('18538', '296', '294', 'Потыфаз, с'),
('18539', '296', '294', 'Регах, с'),
('18540', '296', '294', 'Саубын, с'),
('18541', '296', '294', 'Сахсат, с'),
('18542', '296', '294', 'Слас, с'),
('18543', '296', '294', 'Тапанкау, с'),
('18544', '296', '294', 'Цасем, с'),
('18545', '296', '294', 'Цемса, с'),
('18546', '296', '294', 'Биз, с'),
('18547', '296', '294', 'Дагом, с'),
('18548', '296', '294', 'Донисар, с'),
('18549', '296', '294', 'Цамад, с'),
('18550', '296', '294', 'Абайтикау, с'),
('18551', '296', '294', 'Хукали, с'),
('18552', '296', '294', 'Верхний Цей, с'),
('18553', '296', '294', 'Нижний Цей, с'),
('18554', '296', '294', 'Нижний Зарамаг, с'),
('18555', '296', '294', 'Верхний Зарамаг, с'),
('18556', '296', '294', 'Ход, с'),
('18557', '296', '294', 'Барзикау, с'),
('18558', '296', '294', 'Даллагкау, с'),
('18559', '296', '294', 'Цмити, с'),
('18560', '296', '294', 'Хидикус, с'),
('18561', '296', '294', 'Харисджин, с'),
('18562', '296', '294', 'Дзивгис, с'),
('18563', '296', '294', 'Кадат, с'),
('18564', '296', '294', 'Гилу, с'),
('18565', '296', '294', 'Урсдон, с'),
('18566', '296', '294', 'Инджынта, с'),
('18567', '296', '294', 'Луар, с'),
('18568', '296', '294', 'Ксурт, с'),
('18569', '296', '294', 'Дайкау, с'),
('18570', '296', '294', 'Бад, с'),
('18571', '296', '294', 'Галон, с'),
('18572', '296', '294', 'Курайтта, с'),
('18573', '296', '294', 'Кожа, с'),
('18574', '296', '294', 'Цыфта, с'),
('18575', '296', '294', 'Гори, с'),
('18576', '296', '294', 'Тоборза, с'),
('18577', '296', '294', 'Ецина, с'),
('18578', '296', '294', 'Зегтикау, с'),
('18579', '296', '294', 'Цементный Завод, пгт'),
('18580', '296', '294', 'Нижний Бирагзанг, с'),
('18581', '296', '294', 'Нижний Згид, п'),
('18582', '296', '294', 'Ногкау, с'),
('18583', '296', '294', 'Нижний Унал, с'),
('18584', '296', '294', 'Урикау, с'),
('18585', '297', '294', 'Ардон, г'),
('18586', '297', '294', 'Кадгарон, с'),
('18587', '297', '294', 'Кирово, с'),
('18588', '297', '294', 'Коста, с'),
('18589', '297', '294', 'Красногор, с'),
('18590', '297', '294', 'Мичурино, с'),
('18591', '297', '294', 'Нарт, с'),
('18592', '297', '294', 'Рассвет, с'),
('18593', '297', '294', 'Фиагдон, с'),
('18594', '297', '294', 'Бекан, п'),
('18595', '297', '294', 'Цмити, с'),
('18596', '298', '294', 'Дигора, г'),
('18597', '298', '294', 'Дур-Дур, с'),
('18598', '298', '294', 'Мостиздах, с'),
('18599', '298', '294', 'Николаевская, ст-ца'),
('18600', '298', '294', 'Карман-Синдзикау, с'),
('18601', '298', '294', 'Урсдон, с'),
('18602', '298', '294', 'Кора, с'),
('18603', '299', '294', 'Чикола, с'),
('18604', '299', '294', 'Ахсарисар, с'),
('18605', '299', '294', 'Галиат, с'),
('18606', '299', '294', 'Гулар, с'),
('18607', '299', '294', 'Лескен, с'),
('18608', '299', '294', 'Махческ, с'),
('18609', '299', '294', 'Задалеск, с'),
('18610', '299', '294', 'Новый Урух, с'),
('18611', '299', '294', 'Советское, с'),
('18612', '299', '294', 'Средний Урух, с'),
('18613', '299', '294', 'Стур-Дигора, с'),
('18614', '299', '294', 'Сурх-Дигора, с'),
('18615', '299', '294', 'Толдзгун, с'),
('18616', '299', '294', 'Хазнидон, с'),
('18617', '299', '294', 'Дзинага, с'),
('18618', '299', '294', 'Фаснал, с'),
('18619', '299', '294', 'Донифарс, с'),
('18620', '299', '294', 'Мацута, с'),
('18621', '299', '294', 'Ахсау, с'),
('18622', '299', '294', 'Вакац, с'),
('18623', '299', '294', 'Кусу, с'),
('18624', '299', '294', 'Моска, с'),
('18625', '299', '294', 'Одола, с'),
('18626', '299', '294', 'Нижний Задалеск, с'),
('18627', '299', '294', 'Верний Задалеск, с'),
('18628', '299', '294', 'Калух, с'),
('18629', '299', '294', 'Камунта, с'),
('18630', '299', '294', 'Дунта, с'),
('18631', '299', '294', 'Ногкау, с'),
('18632', '299', '294', 'Нижний Нар, с'),
('18633', '299', '294', 'Верхний Нар, с'),
('18634', '299', '294', 'Ханаз, с'),
('18635', '299', '294', 'Казахта, с'),
('18636', '299', '294', 'Калнахта, с'),
('18637', '299', '294', 'Фараската, с'),
('18638', '299', '294', 'Дзагепбарз, с'),
('18639', '299', '294', 'Каманта, с'),
('18640', '299', '294', 'Лезгор, с'),
('18641', '300', '294', 'Авдеевка, с'),
('18642', '300', '294', 'Антоновка, с'),
('18643', '300', '294', 'Архангеловка, с'),
('18644', '300', '294', 'Афанасьевка, с'),
('18645', '300', '294', 'Владимировка, с'),
('18646', '300', '294', 'Горные Ключи, пгт'),
('18647', '300', '294', 'Горный, пгт'),
('18648', '300', '294', 'Комаровка, с'),
('18649', '300', '294', 'Крыловка, с'),
('18650', '300', '294', 'Луговое, с'),
('18651', '300', '294', 'Марьяновка, с'),
('18652', '300', '294', 'Межгорье, с'),
('18653', '300', '294', 'Ольховка, с'),
('18654', '300', '294', 'Павло-Федоровка, с'),
('18655', '300', '294', 'Преображенка, с'),
('18656', '300', '294', 'Руновка, с'),
('18657', '300', '294', 'Степановка, с'),
('18658', '300', '294', 'Увальное, с'),
('18659', '300', '294', 'Уссурка, с'),
('18660', '300', '294', 'Хвищанка, с'),
('18661', '300', '294', 'Шмаковка, с'),
('18662', '300', '294', 'Большие Ключи, с'),
('18663', '300', '294', 'Еленовка, с'),
('18664', '300', '294', 'Подгорное, с'),
('18665', '300', '294', 'Родниковое, с'),
('18666', '300', '294', 'Краевский, ж/д_рзд'),
('18667', '300', '294', 'сдт Иночка 2, тер'),
('18668', '300', '294', 'сдт Березка, тер'),
('18669', '300', '294', 'СНТ Красная Заря, тер'),
('18670', '300', '294', 'СНТ Керамик, тер'),
('18671', '300', '294', 'СНТ Надежда, тер'),
('18672', '300', '294', 'сдт Восход, тер'),
('18673', '300', '294', 'СТ Поляна, тер'),
('18674', '300', '294', 'СТ Радуга, тер'),
('18675', '300', '294', 'СНТ Иночка, тер'),
('18676', '300', '294', 'Кулаковка, д'),
('18677', '300', '294', 'Зимнички, д'),
('18678', '300', '294', 'Косичино, д'),
('18679', '300', '294', 'Примерный, д'),
('18680', '300', '294', 'Кузнецы, д'),
('18681', '300', '294', 'Ближнее Натарово, д'),
('18682', '300', '294', 'Леонов Починок, д'),
('18683', '300', '294', 'Соломоновка, д'),
('18684', '300', '294', 'Пчелка, д'),
('18685', '300', '294', 'Мироновка, д'),
('18686', '300', '294', 'Коновка, д'),
('18687', '300', '294', 'Большое Заборье, д'),
('18688', '300', '294', 'Большуха, д'),
('18689', '300', '294', 'Малая Большуха, д'),
('18690', '300', '294', 'Черная, д'),
('18691', '300', '294', 'Бакеевка, д'),
('18692', '300', '294', 'Смирновка, д'),
('18693', '300', '294', 'Дурино, д'),
('18694', '300', '294', 'Голосиловка, д'),
('18695', '300', '294', 'Дебря, д'),
('18696', '300', '294', 'Анисово Городище, д'),
('18697', '300', '294', 'Вежи, д'),
('18698', '300', '294', 'Ужать, ж/д_ст'),
('18699', '300', '294', 'Барсуки, д'),
('18700', '300', '294', 'Прудки, д'),
('18701', '300', '294', 'Бережки, д'),
('18702', '300', '294', 'Новосельцы, д'),
('18703', '300', '294', 'Покров, д'),
('18704', '300', '294', 'Шубартовка, д'),
('18705', '300', '294', 'Засецкий, д'),
('18706', '300', '294', 'Малиновский, д'),
('18707', '300', '294', 'Ивановский, д'),
('18708', '300', '294', 'Михалево, д'),
('18709', '300', '294', 'Острая Слобода, д'),
('18710', '300', '294', 'Раменное, д'),
('18711', '300', '294', 'Калининский, д'),
('18712', '300', '294', 'Лосиное, д'),
('18713', '300', '294', 'Милеев, д'),
('18714', '300', '294', 'Овражек, д'),
('18715', '300', '294', 'Ракитня, д'),
('18716', '300', '294', 'Глиньково, д'),
('18717', '300', '294', 'Дальнее Натарово, д'),
('18718', '300', '294', 'Кушляновка, д'),
('18719', '300', '294', 'Мамоново, д'),
('18720', '300', '294', 'Пупово, д'),
('18721', '300', '294', 'Верхнее Синьгово, д'),
('18722', '300', '294', 'Нижнее Синьгово, д'),
('18723', '300', '294', 'Усохи, д'),
('18724', '300', '294', 'Устрожено, д'),
('18725', '300', '294', 'Ясная Поляна, д'),
('18726', '300', '294', 'Винозаводчик, д'),
('18727', '300', '294', 'Заря, д'),
('18728', '300', '294', 'Новоселки, д'),
('18729', '300', '294', 'Неполоть, д'),
('18730', '300', '294', 'Пробуждение, д'),
('18731', '300', '294', 'Пробуждение, ж/д_ст'),
('18732', '300', '294', 'Сельцы, д'),
('18733', '300', '294', 'Нижняя Песочня, д'),
('18734', '300', '294', 'Милеево, д'),
('18735', '300', '294', 'Зимницы, д'),
('18736', '300', '294', 'Нагорное, п'),
('18737', '300', '294', 'Дуб, п'),
('18738', '300', '294', 'Косичено, д'),
('18739', '300', '294', 'Б.Натарово, д'),
('18740', '300', '294', 'Дебри, д'),
('18741', '300', '294', 'О.Слобода, д'),
('18742', '300', '294', 'Момоново, д'),
('18743', '300', '294', 'Синьгово Верхнее, д'),
('18744', '300', '294', 'Синьгово Нижнее, д'),
('18745', '300', '294', 'Устрожино, д'),
('18746', '300', '294', 'Засецкий, п'),
('18747', '300', '294', 'Шубартовка, п'),
('18748', '300', '294', 'Малиновский, п'),
('18749', '300', '294', 'Примерный, п'),
('18750', '300', '294', 'Ивановский, п'),
('18751', '300', '294', 'Калининский, п'),
('18752', '300', '294', 'Овражек, п'),
('18753', '300', '294', 'Верхнее Синьково, д'),
('18754', '300', '294', 'Нижнее Синьково, д'),
('18755', '300', '294', 'Заря, п'),
('18756', '300', '294', 'Пробуждение, п'),
('18757', '300', '294', 'Милеево, п'),
('18758', '300', '294', 'Анненская горка, снт'),
('18759', '300', '294', 'Дружба, снт'),
('18760', '300', '294', 'Кировец, снт'),
('18761', '300', '294', 'Ленинградский мачтопропиточный завод, сн'),
('18762', '300', '294', 'Моряк-1, снт'),
('18763', '300', '294', 'Пелла, снт'),
('18764', '300', '294', 'Проектант, снт'),
('18765', '300', '294', 'Садоводство Монетного двора, снт'),
('18766', '300', '294', 'Радист, снт'),
('18767', '300', '294', 'КТС N3 ЦНИИ им академика А.Н.Крылова, сн'),
('18768', '300', '294', 'Грибное, снт'),
('18769', '300', '294', 'Дружба, снт'),
('18770', '300', '294', 'Кировчанин, снт'),
('18771', '300', '294', 'Колпинец, снт'),
('18772', '300', '294', 'Ласточка, снт'),
('18773', '300', '294', 'Ленгидропроект, снт'),
('18774', '300', '294', 'Нева, снт'),
('18775', '300', '294', 'Ручей, снт'),
('18776', '300', '294', 'Лоза, снт'),
('18777', '300', '294', 'Невдубстрой, массив'),
('18778', '300', '294', 'Айболит, снт'),
('18779', '300', '294', 'Лира, снт'),
('18780', '300', '294', 'Покровское, снт'),
('18781', '300', '294', 'Орешек, снт'),
('18782', '300', '294', 'Шлиссельбуржец, снт'),
('18783', '300', '294', 'Волна, снт'),
('18784', '300', '294', 'Отрадное, массив'),
('18785', '300', '294', 'Союз-Чернобыль, снт'),
('18786', '300', '294', 'Восход, массив'),
('18787', '300', '294', 'Агрохимик, снт'),
('18788', '300', '294', 'Восход Василеостровского района, снт'),
('18789', '300', '294', 'Восход Приморского района, снт'),
('18790', '300', '294', 'Восход Смольнинского района, снт'),
('18791', '300', '294', 'Восход Фрунзенского района, снт'),
('18792', '300', '294', 'Восход-1, снт'),
('18793', '300', '294', 'Восход-2, снт'),
('18794', '300', '294', 'Восход-3, снт'),
('18795', '300', '294', 'Восход-4, снт'),
('18796', '300', '294', 'Восход-5, снт'),
('18797', '300', '294', 'Восход-6, снт'),
('18798', '300', '294', 'Восход-7, снт'),
('18799', '300', '294', 'Восход-8, снт'),
('18800', '300', '294', 'Заря, снт'),
('18801', '300', '294', 'Ижорец, снт'),
('18802', '300', '294', 'Кировец-3, снт'),
('18803', '300', '294', 'Ладога Московского района, снт'),
('18804', '300', '294', 'Ладога-73, снт'),
('18805', '300', '294', 'Лесное, снт'),
('18806', '300', '294', 'Ольховское, снт'),
('18807', '300', '294', 'Петроградское, снт'),
('18808', '300', '294', 'Петрокрепость, снт'),
('18809', '300', '294', 'Приозерное, снт'),
('18810', '300', '294', 'ТЭМП, снт'),
('18811', '300', '294', 'Треугольник, снт'),
('18812', '300', '294', 'Синявино, массив'),
('18813', '300', '294', 'Липки, снт'),
('18814', '300', '294', 'Приозерное, снт'),
('18815', '300', '294', 'Синявинское, снт'),
('18816', '300', '294', 'Соловей, снт'),
('18817', '300', '294', 'Горы-1, массив'),
('18818', '300', '294', 'Бугры, снт'),
('18819', '300', '294', 'Вперед, снт'),
('18820', '300', '294', 'Геолог, снт'),
('18821', '300', '294', 'Дачное, снт'),
('18822', '300', '294', 'Ленгазовец, снт'),
('18823', '300', '294', 'Новинка, снт'),
('18824', '300', '294', 'Невский завод, снт'),
('18825', '300', '294', 'Строитель, снт'),
('18826', '300', '294', 'Транспортник, снт'),
('18827', '300', '294', 'ТЭЦ-2, снт'),
('18828', '300', '294', 'Электросила-3, снт'),
('18829', '300', '294', 'Электросила-9, снт'),
('18830', '300', '294', 'Энергия, снт'),
('18831', '300', '294', 'Горы-2, массив'),
('18832', '300', '294', 'Василеостровец, снт'),
('18833', '300', '294', 'Горы, снт'),
('18834', '300', '294', 'Имени Фрунзе, снт'),
('18835', '300', '294', 'Колпинское, снт'),
('18836', '300', '294', 'Куйбышевец, снт'),
('18837', '300', '294', 'Надежда, снт'),
('18838', '300', '294', 'Петроградское, снт'),
('18839', '300', '294', 'Пушкинское, снт'),
('18840', '300', '294', 'Рассвет, снт'),
('18841', '300', '294', 'Светлые горки, снт'),
('18842', '300', '294', 'Севзаптрансстрой, снт'),
('18843', '300', '294', 'Стрела, снт'),
('18844', '300', '294', 'Войтоловка, снт'),
('18845', '300', '294', 'Волна, снт'),
('18846', '300', '294', 'Заря, снт'),
('18847', '300', '294', 'Невское, снт'),
('18848', '300', '294', 'ст Жихарево, массив'),
('18849', '300', '294', 'Кирпичики-1, снт'),
('18850', '300', '294', 'Кирпичики-2, снт'),
('18851', '300', '294', 'Поселок Приладожский, массив'),
('18852', '300', '294', 'Приладожское, снт'),
('18853', '300', '294', 'ст Назия, массив'),
('18854', '300', '294', 'Звезда, снт'),
('18855', '300', '294', 'Импульс, снт'),
('18856', '300', '294', 'Назия, снт'),
('18857', '300', '294', 'Невское, снт'),
('18858', '300', '294', 'Омега, снт'),
('18859', '300', '294', 'Сирена, снт'),
('18860', '300', '294', 'Сокол, снт'),
('18861', '300', '294', 'Строитель, снт'),
('18862', '300', '294', 'Строитель-1, снт'),
('18863', '300', '294', 'Химик, снт'),
('18864', '300', '294', 'Эликсир, снт'),
('18865', '300', '294', 'Энергия-1, снт'),
('18866', '300', '294', 'Юлия, снт'),
('18867', '300', '294', 'Апраксин, массив'),
('18868', '300', '294', 'Апраксин-1, снт'),
('18869', '300', '294', 'Апраксин-2, снт'),
('18870', '300', '294', 'Вагонник, снт'),
('18871', '300', '294', 'Восток, снт'),
('18872', '300', '294', 'им Ф.Э.Дзержинского, снт'),
('18873', '300', '294', 'Индустриальный техникум трудовых резерво'),
('18874', '300', '294', 'Красная Бавария, снт'),
('18875', '300', '294', 'Красная Бавария-2, снт'),
('18876', '300', '294', 'Краснодеревщик, снт'),
('18877', '300', '294', 'Красный Маяк, снт'),
('18878', '300', '294', 'Ленинградская слюдяная фабрика, снт'),
('18879', '300', '294', 'Луч, снт'),
('18880', '300', '294', 'Металлист, снт'),
('18881', '300', '294', 'Мореходка, снт'),
('18882', '300', '294', 'Огнеупоры, снт'),
('18883', '300', '294', 'ПЗМ, снт'),
('18884', '300', '294', 'Рассвет, снт'),
('18885', '300', '294', 'Рубин, снт'),
('18886', '300', '294', 'Связист, снт'),
('18887', '300', '294', 'Северный завод, снт'),
('18888', '300', '294', 'Сигнал, снт'),
('18889', '300', '294', 'ПСК Спортсудостроитель, снт'),
('18890', '300', '294', 'УНР-77, снт'),
('18891', '300', '294', 'Химик-2, снт'),
('18892', '300', '294', 'Художник, снт'),
('18893', '300', '294', 'Горы-3, массив'),
('18894', '300', '294', 'Дозовец, снт'),
('18895', '300', '294', 'Заречье, снт'),
('18896', '300', '294', 'Комбинат хлебопродуктов им С.М.Кирова, с'),
('18897', '300', '294', 'Ленинградский Петрозавод, снт'),
('18898', '300', '294', 'Локомотив, снт'),
('18899', '300', '294', 'Локомотивное депо ТЧ-8, снт'),
('18900', '300', '294', 'Мебельщик, снт'),
('18901', '300', '294', 'Экспресс, снт'),
('18902', '300', '294', 'Келколово-1, массив'),
('18903', '300', '294', 'Заря, снт'),
('18904', '300', '294', 'Луч, снт'),
('18905', '300', '294', 'Мгинское, снт'),
('18906', '300', '294', 'Полимер (Стройполимер), снт'),
('18907', '300', '294', 'Пролетарий, снт'),
('18908', '300', '294', 'Русь, снт'),
('18909', '300', '294', 'Силикатчик, снт'),
('18910', '300', '294', 'Келколово-2, массив'),
('18911', '300', '294', 'Автомобилис, снт'),
('18912', '300', '294', 'Автомобилист, снт'),
('18913', '300', '294', 'Автомобилист-2, снт'),
('18914', '300', '294', 'Лотос, снт'),
('18915', '300', '294', 'Октябрьский, снт'),
('18916', '300', '294', 'Чайка, снт'),
('18917', '300', '294', 'Келколово-3, массив'),
('18918', '300', '294', 'Василеостровец, снт'),
('18919', '300', '294', 'Выборгское, снт'),
('18920', '300', '294', 'Дзержинец, снт'),
('18921', '300', '294', 'Фрунзенец, снт'),
('18922', '300', '294', 'Михайловский, массив'),
('18923', '300', '294', 'Завод АТИ, снт'),
('18924', '300', '294', 'Берёзка, снт'),
('18925', '300', '294', 'Берёзка-2, снт'),
('18926', '300', '294', 'Бирюза, снт'),
('18927', '300', '294', 'Восход им Козицкого, снт'),
('18928', '300', '294', 'Восход-2, снт'),
('18929', '300', '294', 'Геодезист-2, снт'),
('18930', '300', '294', 'Движенец, снт'),
('18931', '300', '294', 'Движенец-2, снт'),
('18932', '300', '294', 'Дзержинец-2, снт'),
('18933', '300', '294', 'Дружный, снт'),
('18934', '300', '294', 'Железобетон, снт'),
('18935', '300', '294', 'Калининец, снт'),
('18936', '300', '294', 'Ленгаз, снт'),
('18937', '300', '294', 'Лениздат, снт'),
('18938', '300', '294', 'Лидер, снт'),
('18939', '300', '294', 'Медик, снт'),
('18940', '300', '294', 'Метрополитеновец, снт'),
('18941', '300', '294', 'Михайловское завода им А.А.Кулакова, снт'),
('18942', '300', '294', 'Моряк, снт'),
('18943', '300', '294', 'Оргтехстрой, снт'),
('18944', '300', '294', 'Отрадненское, снт'),
('18945', '300', '294', 'Подъёмник, снт'),
('18946', '300', '294', 'Радуга, снт'),
('18947', '300', '294', 'Садко, снт'),
('18948', '300', '294', 'Северная верфь, снт'),
('18949', '300', '294', 'Скороход, снт'),
('18950', '300', '294', 'Строитель-2, снт'),
('18951', '300', '294', 'Университет, снт'),
('18952', '300', '294', 'Чайка-М, снт'),
('18953', '300', '294', 'Электроприбор, снт'),
('18954', '300', '294', 'Ягода, снт'),
('18955', '300', '294', 'Яхта, снт'),
('18956', '300', '294', 'Михайловское-2 (Красногвардеец), снт'),
('18957', '300', '294', 'Фотон (ПО завод Вибратор), снт'),
('18958', '300', '294', 'НСТ Техфлотец (КСТ), снт'),
('18959', '300', '294', 'Славянка, массив'),
('18960', '300', '294', 'Родник, снт'),
('18961', '300', '294', 'Белкино, снт'),
('18962', '300', '294', 'Берёзовка, снт'),
('18963', '300', '294', 'Заречное, снт'),
('18964', '300', '294', 'Импульс, снт'),
('18965', '300', '294', 'Магистраль, снт'),
('18966', '300', '294', 'Славянка-2, массив'),
('18967', '300', '294', 'Коммунальщик, снт'),
('18968', '300', '294', 'Парус, снт'),
('18969', '300', '294', 'Мишкино, снт'),
('18970', '300', '294', 'Октябрьское, снт'),
('18971', '300', '294', 'Русановка, снт'),
('18972', '300', '294', 'Славянка, снт'),
('18973', '300', '294', 'Радуга, снт'),
('18974', '300', '294', 'Орбита, снт'),
('18975', '300', '294', 'Ритм, снт'),
('18976', '300', '294', 'Междуречье, снт'),
('18977', '300', '294', 'Апраксин, снт'),
('18978', '300', '294', 'Поречье, снт'),
('18979', '300', '294', 'Факел, снт'),
('18980', '300', '294', 'Восход-2 Приморский район, снт'),
('18981', '300', '294', 'Геодезист, снт'),
('18982', '301', '294', 'Моздок, г'),
('18983', '301', '294', 'Веселое, с'),
('18984', '301', '294', 'Виноградное, с'),
('18985', '301', '294', 'Киевское, с'),
('18986', '301', '294', 'Кизляр, с'),
('18987', '301', '294', 'Луковская, ст-ца'),
('18988', '301', '294', 'Нижный Малгобек, с'),
('18989', '301', '294', 'Ново-Осетинская, ст-ца'),
('18990', '301', '294', 'Павлодольская, ст-ца'),
('18991', '301', '294', 'Предгорное, с'),
('18992', '301', '294', 'Притеречный, п'),
('18993', '301', '294', 'Раздольное, с'),
('18994', '301', '294', 'Сухотское, с'),
('18995', '301', '294', 'Терская, ст-ца'),
('18996', '301', '294', 'Троицкое, с'),
('18997', '301', '294', 'Хурикау, с'),
('18998', '301', '294', 'Тельман, п'),
('18999', '301', '294', 'Калининский, п'),
('19000', '301', '294', 'Ново-Георгиевское, с'),
('19001', '301', '294', 'Садовый, п'),
('19002', '301', '294', 'Комарово, с'),
('19003', '301', '294', 'Черноярская, ст-ца'),
('19004', '301', '294', 'Мирный, п'),
('19005', '301', '294', 'Веселовское, с'),
('19006', '301', '294', 'Новогеоргиевское, с'),
('19007', '301', '294', 'Новоосетинская, ст-ца'),
('19008', '301', '294', 'Дружба, п'),
('19009', '301', '294', 'Елбаев, х'),
('19010', '301', '294', 'Советский, п'),
('19011', '301', '294', 'Л.Кондратенко, п'),
('19012', '301', '294', 'Октябрьский, п'),
('19013', '301', '294', 'Луковский, п'),
('19014', '301', '294', 'Осетинский, п'),
('19015', '301', '294', 'Черноярская, ст'),
('19016', '301', '294', 'Кусово, с'),
('19017', '301', '294', 'Теркум, п'),
('19018', '301', '294', 'Моздок-2, п'),
('19019', '301', '294', 'Осетинская, ст'),
('19020', '301', '294', 'Малгобек, с'),
('19021', '301', '294', 'Мальый Малгобек, с'),
('19022', '301', '294', 'Елбаево, с'),
('19023', '302', '294', 'Беслан, г'),
('19024', '302', '294', 'Брут, с'),
('19025', '302', '294', 'Заманкул, с'),
('19026', '302', '294', 'Зильги, с'),
('19027', '302', '294', 'Новый Батако, с'),
('19028', '302', '294', 'Ольгинское, с'),
('19029', '302', '294', 'Раздзог, с'),
('19030', '302', '294', 'Батако, с'),
('19031', '302', '294', 'Фарн, с'),
('19032', '302', '294', 'Хумалаг, с'),
('19033', '302', '294', 'Цалык, с'),
('19034', '302', '294', '6 км, ст'),
('19035', '302', '294', '9 км, ст'),
('19036', '302', '294', 'Аэропорт Владикавказ, нп'),
('19037', '302', '294', 'Старый-Батакоюрт, с'),
('19038', '303', '294', 'Анатольская, ст'),
('19039', '303', '294', 'Антоновский Санаторий, п'),
('19040', '303', '294', 'Бабайлова, д'),
('19041', '303', '294', 'Баклушина, п'),
('19042', '303', '294', 'Балакино, с'),
('19043', '303', '294', 'Баронская, д'),
('19044', '303', '294', 'Башкарка, с'),
('19045', '303', '294', 'Беляковка, д'),
('19046', '303', '294', 'Большие Галашки, с'),
('19047', '303', '294', 'Боровая, д'),
('19048', '303', '294', 'Братчиково, п'),
('19049', '303', '294', 'Бродово, с'),
('19050', '303', '294', 'Бызово, с'),
('19051', '303', '294', 'Верхняя Алабашка, д'),
('19052', '303', '294', 'Верхняя Ослянка, с'),
('19053', '303', '294', 'Вилюй, п'),
('19054', '303', '294', 'Висим, п'),
('19055', '303', '294', 'Висимо-Уткинск, п'),
('19056', '303', '294', 'Волчевка, п'),
('19057', '303', '294', 'Горноуральский, пгт'),
('19058', '303', '294', 'Дальний, п'),
('19059', '303', '294', 'Дрягуново, с'),
('19060', '303', '294', 'Дубасова, д'),
('19061', '303', '294', 'Евстюниха, п'),
('19062', '303', '294', 'Еква, п'),
('19063', '303', '294', 'Елизаветинское, с'),
('19064', '303', '294', 'Запрудный, п'),
('19065', '303', '294', 'Заречная, д'),
('19066', '303', '294', 'Захаровка, д'),
('19067', '303', '294', 'Зональный, п'),
('19068', '303', '294', 'Зырянка, д'),
('19069', '303', '294', 'Ива, п'),
('19070', '303', '294', 'Ильинка, д'),
('19071', '303', '294', 'Кайгородское, с'),
('19072', '303', '294', 'Канава, п'),
('19073', '303', '294', 'Колмаки, д'),
('19074', '303', '294', 'Кондрашина, д'),
('19075', '303', '294', 'Корнилова, д'),
('19076', '303', '294', 'Краснополье, с'),
('19077', '303', '294', 'Лая, п'),
('19078', '303', '294', 'Лая, с'),
('19079', '303', '294', 'Леневка, п'),
('19080', '303', '294', 'Луговая, д'),
('19081', '303', '294', 'Малая Лая, с'),
('19082', '303', '294', 'Маркова, д'),
('19083', '303', '294', 'Матвеева, д'),
('19084', '303', '294', 'Мокроусское, с'),
('19085', '303', '294', 'Молодежный, п'),
('19086', '303', '294', 'Монзино, п'),
('19087', '303', '294', 'Мурзинка, с'),
('19088', '303', '294', 'Нижняя Алабашка, д'),
('19089', '303', '294', 'Нижняя Ослянка, д'),
('19090', '303', '294', 'Николо-Павловское, с'),
('19091', '303', '294', 'Новая, д'),
('19092', '303', '294', 'Новая Башкарка, д'),
('19093', '303', '294', 'Новоасбест, п'),
('19094', '303', '294', 'Новопаньшино, с'),
('19095', '303', '294', 'Отрадный, п'),
('19096', '303', '294', 'Первомайский, п'),
('19097', '303', '294', 'Петрокаменское, с'),
('19098', '303', '294', 'Покровское, с'),
('19099', '303', '294', 'Реши, д'),
('19100', '303', '294', 'Рябки, д'),
('19101', '303', '294', 'Ряжик, п'),
('19102', '303', '294', 'Сарапулка, д'),
('19103', '303', '294', 'Сартакова, д'),
('19104', '303', '294', 'Северка, п'),
('19105', '303', '294', 'Серебрянка, с'),
('19106', '303', '294', 'Сизикова, д'),
('19107', '303', '294', 'Синегорский, п'),
('19108', '303', '294', 'Слудка, д'),
('19109', '303', '294', 'Соседкова, д'),
('19110', '303', '294', 'Старая Паньшина, д'),
('19111', '303', '294', 'Студеный, п'),
('19112', '303', '294', 'Судорогина, д'),
('19113', '303', '294', 'Сулем, с'),
('19114', '303', '294', 'Таны, п'),
('19115', '303', '294', 'Темно-Осинова, д'),
('19116', '303', '294', 'Уралец, п'),
('19117', '303', '294', 'Усть-Утка, д'),
('19118', '303', '294', 'Фокинцы, д'),
('19119', '303', '294', 'Харенки, д'),
('19120', '303', '294', 'Хутор, д'),
('19121', '303', '294', 'Чауж, п'),
('19122', '303', '294', 'Чащино, п'),
('19123', '303', '294', 'Черемшанка, д'),
('19124', '303', '294', 'Черноисточинск, п'),
('19125', '303', '294', 'Шиловка, с'),
('19126', '303', '294', 'Шумиха, д'),
('19127', '303', '294', 'Южаково, с'),
('19128', '303', '294', 'Бобровка, п'),
('19129', '303', '294', 'Бутон, п'),
('19130', '303', '294', 'Нива, п'),
('19131', '303', '294', 'Салка, п'),
('19132', '303', '294', 'Старый Волковский, п'),
('19133', '303', '294', 'Утка, п'),
('19134', '303', '294', 'Висим, пгт'),
('19135', '303', '294', 'Висимо-Уткинск, пгт'),
('19136', '303', '294', 'Новоасбест, пгт'),
('19137', '303', '294', 'Синегорский, пгт'),
('19138', '303', '294', 'Уралец, пгт'),
('19139', '303', '294', 'Черноисточинск, пгт'),
('19140', '303', '294', 'Анатольская (Грань), д'),
('19141', '303', '294', 'Белая речка, тер'),
('19142', '303', '294', 'Березки, тер'),
('19143', '303', '294', 'Братчиков лог, тер'),
('19144', '303', '294', 'Демидово, тер'),
('19145', '303', '294', 'Катаба, тер'),
('19146', '303', '294', 'Сдт 1 ААГОК, тер'),
('19147', '303', '294', 'Сдт 1 ОАО НТМК, тер'),
('19148', '303', '294', 'Сдт 10 ОАО НТМК, тер'),
('19149', '303', '294', 'Сдт 10 УВЗ, тер'),
('19150', '303', '294', 'Сдт 11 УВЗ, тер'),
('19151', '303', '294', 'Сдт 12 ОАО НТМК, тер'),
('19152', '303', '294', 'Сдт 12 УВЗ, тер'),
('19153', '303', '294', 'Сдт 13 ОАО НТМК, тер'),
('19154', '303', '294', 'Сдт 14 УВЗ, тер'),
('19155', '303', '294', 'Сдт 15 ОАО НТМК, тер'),
('19156', '303', '294', 'Сдт 15 УВЗ, тер'),
('19157', '303', '294', 'Сдт 15а ОАО НТМК, тер'),
('19158', '303', '294', 'Сдт 16, тер'),
('19159', '303', '294', 'Сдт 16 ОАО НТМК, тер'),
('19160', '303', '294', 'Сдт 16 УВЗ, тер'),
('19161', '303', '294', 'Сдт 17 ОАО НТМК, тер'),
('19162', '303', '294', 'Сдт 17 УВЗ, тер'),
('19163', '303', '294', 'Сдт 18 УВЗ, тер'),
('19164', '303', '294', 'Сдт 19 УВЗ, тер'),
('19165', '303', '294', 'Сдт 2 ОАО НТМК, тер'),
('19166', '303', '294', 'Сдт 2 Уралхимпласт, тер'),
('19167', '303', '294', 'Сдт 20 ОАО НТМК, тер'),
('19168', '303', '294', 'Сдт 20 УВЗ, тер'),
('19169', '303', '294', 'Сдт 3, тер'),
('19170', '303', '294', 'Сдт 3 Уралхимпласт, тер'),
('19171', '303', '294', 'Сдт 3 река Белая Ватиха, тер'),
('19172', '303', '294', 'Сдт 3 треста 88, тер'),
('19173', '303', '294', 'Сдт 4, тер'),
('19174', '303', '294', 'Сдт 4 ОАО НТМК, тер'),
('19175', '303', '294', 'Сдт 4 Уралхимпласт, тер'),
('19176', '303', '294', 'Сдт 4а ОАО НТМК, тер'),
('19177', '303', '294', 'Сдт 5 Мечта, тер'),
('19178', '303', '294', 'Сдт 5 ОАО НТМК, тер'),
('19179', '303', '294', 'Сдт 5 Уралхимпласт, тер'),
('19180', '303', '294', 'Сдт 6 Николо-Павловский, тер'),
('19181', '303', '294', 'Сдт 6 ОАО НТМК, тер'),
('19182', '303', '294', 'Сдт 7а УВЗ (Ватиха), тер'),
('19183', '303', '294', 'Сдт 8 УВЗ, тер'),
('19184', '303', '294', 'Сдт Автомобилист 1, тер'),
('19185', '303', '294', 'Сдт Автомобилист 2, тер'),
('19186', '303', '294', 'Сдт Агрохимик, тер'),
('19187', '303', '294', 'Сдт Белая Ватиха, тер'),
('19188', '303', '294', 'Сдт Весна, тер'),
('19189', '303', '294', 'Сдт Взаимопомощь, тер'),
('19190', '303', '294', 'Сдт Витязь, тер'),
('19191', '303', '294', 'Сдт Геолог, тер'),
('19192', '303', '294', 'Сдт Горняк 3, тер'),
('19193', '303', '294', 'Сдт ДСК, тер'),
('19194', '303', '294', 'Сдт Дачник, тер'),
('19195', '303', '294', 'Сдт Долина, тер'),
('19196', '303', '294', 'Сдт Дружба, тер'),
('19197', '303', '294', 'Сдт Железнодорожник, тер'),
('19198', '303', '294', 'Сдт Зенит, тер'),
('19199', '303', '294', 'Сдт Ивушка, тер'),
('19200', '303', '294', 'Сдт Импульс, тер'),
('19201', '303', '294', 'Сдт Иса, тер'),
('19202', '303', '294', 'Сдт Капасиха, тер'),
('19203', '303', '294', 'Сдт Коммунальник, тер'),
('19204', '303', '294', 'Сдт Лесная поляна, тер'),
('19205', '303', '294', 'Сдт Лесник, тер'),
('19206', '303', '294', 'Сдт Лесной, тер'),
('19207', '303', '294', 'Сдт Лира, тер'),
('19208', '303', '294', 'Сдт Луковка, тер'),
('19209', '303', '294', 'Сдт Луч, тер'),
('19210', '303', '294', 'Сдт Междуречье, тер'),
('19211', '303', '294', 'Сдт Меркурий, тер'),
('19212', '303', '294', 'Сдт Металлист, тер'),
('19213', '303', '294', 'Сдт Мечта, тер'),
('19214', '303', '294', 'Сдт Монтажник, тер'),
('19215', '303', '294', 'Сдт Озерки, тер'),
('19216', '303', '294', 'Сдт Первомайский, тер'),
('19217', '303', '294', 'Сдт Пищевик, тер'),
('19218', '303', '294', 'Сдт Племзавод Тагил, тер'),
('19219', '303', '294', 'Сдт Подлипки, тер'),
('19220', '303', '294', 'Сдт Пригород, тер'),
('19221', '303', '294', 'Сдт Пригородагросервис, тер'),
('19222', '303', '294', 'Сдт Росинка, тер'),
('19223', '303', '294', 'Сдт Рыбак, тер'),
('19224', '303', '294', 'Сдт Северный 2, тер'),
('19225', '303', '294', 'Сдт Северный 3, тер'),
('19226', '303', '294', 'Сдт Северный 4, тер'),
('19227', '303', '294', 'Сдт Северный 5, тер'),
('19228', '303', '294', 'Сдт Сельхозтехника, тер'),
('19229', '303', '294', 'Сдт Совхозный, тер'),
('19230', '303', '294', 'Сдт Сокол 1, тер'),
('19231', '303', '294', 'Сдт Сокол 2, тер'),
('19232', '303', '294', 'Сдт Старатель, тер'),
('19233', '303', '294', 'Сдт Старатель 4, тер'),
('19234', '303', '294', 'Сдт Старатель 5, тер'),
('19235', '303', '294', 'Сдт Тагилстрой 3, тер'),
('19236', '303', '294', 'Сдт Уралдомнаремонт 1, тер'),
('19237', '303', '294', 'Сдт Уралдомнаремонт 2, тер'),
('19238', '303', '294', 'Сдт Уралец, тер'),
('19239', '303', '294', 'Сдт Уральские зори, тер'),
('19240', '303', '294', 'Сдт Фермер, тер'),
('19241', '303', '294', 'Сдт Фортуна, тер'),
('19242', '303', '294', 'Сдт Химик 4, тер'),
('19243', '303', '294', 'Сдт Химик 5, тер'),
('19244', '303', '294', 'Сдт Чауж, тер'),
('19245', '303', '294', 'Сдт Шахтостроитель, тер'),
('19246', '303', '294', 'Сдт Энергетик, тер'),
('19247', '303', '294', 'Солодов лог, тер'),
('19248', '303', '294', 'Гк Индивидуальный, тер'),
('19249', '303', '294', 'Гк Колос, тер'),
('19250', '303', '294', 'Гк Служебный, тер'),
('19251', '303', '294', 'Сдт Лесной 2, тер'),
('19252', '303', '294', 'Сдт Большая Леба, тер'),
('19253', '303', '294', 'Сдт Мемориал, тер'),
('19254', '303', '294', 'Журавлев лог, тер'),
('19255', '303', '294', 'Бежбала, тер');");
} ?>