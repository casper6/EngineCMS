CMS «ДвижОк». 2007-2013 гг.
Распространяется по лицензии GNU GPL 3 версии.
На данный момент исключительно русская версия системы администрирования сайтов. 

Основные типы создаваемых сайтов:
- блог, 
- каталог продукции,  
- новостной или статейный портал,  
- сайт компании,  
- внутренний корпоративный сайт,
- система упрощенного документооборота (электронные таблицы и статьи). 

Обновление системы
— Удалить все старые файлы и папки на сервере, кроме:
	— файл config.php
	- папка img
	- папка files
	- папка backup
	- папка theme
- Переписать на сервер все новые файлы и папки, кроме перечисленных и папки install (процесс можно ускорить, упаковав их и переписав с файлом-распаковщиком unzip.php, затем запустить его)

Установка системы (ЧИТАЕМ ВНИМАТЕЛЬНО!)
— Использовать PHP версии от 5.2.1 до 5.3 (выше пока не проверено) и MySQL 4.1 (или выше)
— Распаковать архив в корневую папку сервера (Пример: localhost/www ВНИМАНИЕ: в подпапках не работает!)
— Создать БД (базу данных) MySQL
— Перейти на сайт и следовать инструкции установщика
- После окончания установки перейти по ссылке в администрирование или набрать в адресной строке: сайт/red (например: localhost/red или example.com/red)
– После установки удалить папку install

Создание простого сайта
- Вставить свой дизайн в Главный дизайн:
	- из html-верстки взять всё от <body> до </body> (невключительно), прописать на месте, где будут отображаться страницы строку [содержание]
	- а саму страницу (ее содержание, контент), что будет отображаться на Главной сайта — вставить в раздел «Главная страница»
— Вставить содержимое css-файла (или вставки) в Главный стиль
— Создать нужные разделы и страницы в них (если нужны категории/подразделы — создайте также папки, а уже в них — страницы)
— Создать блок меню со ссылками на созданные разделы и прописать его в дизайне как [Название созданного блока], например [меню], если блок назвали — меню
— Создать необходимые страницы в разделах или отредактировать сами разделы, если страниц в них не будет (т.е. сам раздел будет одиночной страницей)
— Готово

Вопросы и ответы
Поддержка ведется через раздел Issues («Вопросы» — в репозитории-багтрекере https://github.com/dveezhok/Dveezhok/issues) и по скайпу angel13i — разработчик подробно ответит на ваши вопросы и расскажет о системе и создании сайтов на ней. Также можно писать на email - 13i@list.ru

Как упростить закачку CMS на хостинг?
— упаковать всё в архив и распаковать его на сервере. У большинства хостингов такая возможность есть в панели управления - файловый менеджер. Также для этой цели служит файл unzip.php в корне сайта - при его запуске он ищет и распаковывает все ZIP-архивы, которые найдет в корне — вы можете упаковать все файлы, кроме него и закачать файл архива и unzip.php на сервер.

Уменьшение «веса» CMS «ДвижОк»
Какие файлы необязательны? Можно удалить:
– папку ed или ed2 – если вы используете только один визуальный редактор. 1й — это папка ed (71 файл, 381 Кб), 2й — ed2 (10 файлов, 217 Кб)
– папка смайлов images/smilies (156 файлов, 1.3 Мб) – для полного их отключения пишем в config.php – $more_smile = 0;
– папка фоновых изображений для администрирования images/adfon (30 файлов, 1,5 Мб) — можно оставить понравившийся фон, остальные удалить
– папка includes/regions поставляется в архиве includes/regions.zip (95 файлов, 11.4 Мб) — не нужна, если вы не собираетесь использовать выбор регионов при создании дополнительных полей и где-либо на сайте
– папки includes/css-frameworks — если вы не используете css-фреймворки и normalize.css, можно удалить всё, кроме папок skeleton и kickstart (удаление составит 44 файла, 370 Кб)
— папка install – удаляется сразу после установки
================Получается:
в корне - 28 файлов, 685 Кб
ad - 14 файлов, 553 Кб
ed2 – 10 файлов, 217 Кб
images – 273 файлов, 3.5 Мб минус смайлы и фоновки = 87, 700 Кб
includes — 306 файлов, 14.1 Мб минус регионы и css-фреймворки = 167, 2.3 Мб
kcaptcha — 21 файлов, 152 Кб
page — 16 файлов, 328 Кб
= ИТОГО после чистки необязательных останется около 343 файла, 5 Мб


