<?php 
// ================================================
// SPAW v.2.0
// Russian language file
// ================================================

// charset to be used in dialogs
$spaw_lang_charset = 'utf-8';

// language text data array
// first dimension - block, second - exact phrase
// alternative text for toolbar buttons and title for dropdowns - 'title'

$spaw_lang_data = array(
  'cut' => array(
    'title' => 'Вырезать'
  ),
  'copy' => array(
    'title' => 'Копировать'
  ),
  'paste' => array(
    'title' => 'Вставить'
  ),
  'undo' => array(
    'title' => 'Отменить'
  ),
  'redo' => array(
    'title' => 'Повторить'
  ),
  'image' => array(
    'title' => 'КАРТИНКА: Вставить ИЛИ Закачать на сервер изображение'
  ),
  'image_prop' => array(
    'title' => 'КАРТИНКА: Свойства изображения',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отменить',
    'source' => 'Имя файла',
    'alt' => 'Краткое описание',
    'align' => 'Выравнивание',
    'left' => 'слева',
    'right' => 'справа',
    'width' => 'Ширина',
    'height' => 'Высота',
    'border' => 'Рамка',
    'hspace' => 'Отступ по гор.',
    'vspace' => 'по верт.',
    'dimensions' => 'Размеры',
    'reset_dimensions' => 'Восстановить размеры',
    'title_attr' => 'Заголовок',
    'constrain_proportions' => 'сохранять пропорции',
    'css_class' => 'Стиль',
    'error' => 'Ошибка',
    'error_width_nan' => 'Ширина не является числом',
    'error_height_nan' => 'Высота не является числом',
    'error_border_nan' => 'Рамка не является числом',
    'error_hspace_nan' => 'Горизонтаьные поля не является числом',
    'error_vspace_nan' => 'Вертикальные поля не является числом',
  ),
  'flash_prop' => array(                // <= new in 2.0
    'title' => 'Flash-анимация',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отменить',
    'source' => 'Источник',
    'width' => 'Ширина',
    'height' => 'Высота',
    'error' => 'Ошибка',
    'error_width_nan' => 'Ширина не является числом',
    'error_height_nan' => 'Высота не является числом',
  ),
  'inserthorizontalrule' => array(
    'title' => '[HR] Горизонтальная линия'
  ),
  'table_create' => array(
    'title' => 'ТАБЛИЦА: Создать таблицу'
  ),
  'table_prop' => array(
    'title' => 'ТАБЛИЦА: Параметры таблицы',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отменить',
    'rows' => 'Строки',
    'columns' => 'Столбцы',
    'css_class' => 'Стиль', // <=== new 1.0.6
    'width' => 'Ширина',
    'height' => 'Высота<br>(лучше не указывать)',
    'border' => 'Рамка',
    'pixels' => 'пикс.',
    'cellpadding' => 'Отступ от рамки',
    'cellspacing' => 'Растояние между ячейками',
    'bg_color' => 'Цвет фона',
    'background' => 'Фоновое изображение<br>(лучше не указывать)', // <=== new 1.0.6
    'error' => 'Ошибка',
    'error_rows_nan' => 'Строки не является числом',
    'error_columns_nan' => 'Столбцы не является числом',
    'error_width_nan' => 'Ширина не является числом',
    'error_height_nan' => 'Высота не является числом',
    'error_border_nan' => 'Рамка не является числом',
    'error_cellpadding_nan' => 'Отступ от рамки не является числом',
    'error_cellspacing_nan' => 'Растояние между ячейками не является числом',
  ),
  'table_cell_prop' => array(
    'title' => 'ТАБЛИЦА: Параметры ячейки',
    'horizontal_align' => 'Горизонтальное выравнивание',
    'vertical_align' => 'Вертикальное выравнивание',
    'width' => 'Ширина',
    'height' => 'Высота',
    'css_class' => 'Стиль',
    'no_wrap' => 'Без переноса',
    'bg_color' => 'Цвет фона',
    'background' => 'Фоновое изображение',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отменить',
    'left' => 'Слева',
    'center' => 'В центре',
    'right' => 'Справа',
    'top' => 'Сверху',
    'middle' => 'В центре',
    'bottom' => 'Снизу',
    'baseline' => 'Базовая линия текста',
    'error' => 'Ошибка',
    'error_width_nan' => 'Ширина не является числом',
    'error_height_nan' => 'Высота не является числом',
  ),
  'table_row_insert' => array(
    'title' => 'ТАБЛИЦА: Вставить строку'
  ),
  'table_column_insert' => array(
    'title' => 'ТАБЛИЦА: Вставить столбец'
  ),
  'table_row_delete' => array(
    'title' => 'ТАБЛИЦА: Удалить строку'
  ),
  'table_column_delete' => array(
    'title' => 'ТАБЛИЦА: Удалить столбец'
  ),
  'table_cell_merge_right' => array(
    'title' => 'ТАБЛИЦА: Объединить вправо (эту ячейку с правой)'
  ),
  'table_cell_merge_down' => array(
    'title' => 'ТАБЛИЦА: Объединить вниз (эту ячейку с нижней)'
  ),
  'table_cell_split_horizontal' => array(
    'title' => 'ТАБЛИЦА: Разделить по горизонтали'
  ),
  'table_cell_split_vertical' => array(
    'title' => 'ТАБЛИЦА: Разделить по вертикали'
  ),
  'style' => array(
    'title' => 'Стиль'
  ),
  'fontname' => array(
    'title' => 'Шрифт'
  ),
  'fontsize' => array(
    'title' => 'Размер'
  ),
  'formatBlock' => array(
    'title' => 'Абзац'
  ),
  'bold' => array(
    'title' => 'Жирный'
  ),
  'italic' => array(
    'title' => 'Курсив'
  ),
  'underline' => array(
    'title' => 'Подчеркнутый'
  ),
  'strikethrough' => array(
    'title' => 'Перечеркнутый'
  ),
  'insertorderedlist' => array(
    'title' => 'Упорядоченный список'
  ),
  'insertunorderedlist' => array(
    'title' => 'Неупорядоченный список'
  ),
  'indent' => array(
    'title' => 'Увеличить отступ'
  ),
  'outdent' => array(
    'title' => 'Уменьшить отступ'
  ),
  'justifyleft' => array(
    'title' => 'Выравнивание слева'
  ),
  'justifycenter' => array(
    'title' => 'Выравнивание по центру'
  ),
  'justifyright' => array(
    'title' => 'Выравнивание справа'
  ),
  'justifyfull' => array(
    'title' => 'Выравнивание по ширине'
  ),
  'fore_color' => array(
    'title' => 'Цвет текста'
  ),
  'bg_color' => array(
    'title' => 'Цвет фона'
  ),
  'design' => array(
    'title' => 'Переключиться в визуальный режим макетирования (WYSIWYG - что видишь, то и получишь)'
  ),
  'html' => array(
    'title' => 'Переключиться в режим редактирования кода (HTML - язык разметки гипертекста)'
  ),
  'colorpicker' => array(
    'title' => 'Выбор цвета',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отменить',
  ),
  'cleanup' => array(
    'title' => 'Чистка HTML - полезно нажимать 2-3 раза после вставки информации из Word\'а или Excel\'я',
    'confirm' => 'Эта операция уберет все стили, шрифты и ненужные тэги из текущего содержимого редактора. Часть или все ваше форматиолвание может быть утеряно.',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отменить',
  ),
  'toggle_borders' => array(
    'title' => 'Включить рамки таблиц',
  ),
  'hyperlink' => array(
    'title' => 'Создать ссылку',
    'url' => 'Адрес',
    'name' => 'Имя',
    'target' => 'Открыть',
    'css_class'  => 'Стиль',
    'title_attr' => 'Название',
	'a_type' => 'Тип',
	'type_link' => 'Ссылка',
	'type_anchor' => 'Якорь',
	'type_link2anchor' => 'Ссылка на якорь',
	'anchors' => 'Якоря',
	'quick_links' => "Быстрые ссылки", // <=== new in 2.0.6
    'ok' => 'ГОТОВО',
    'cancel' => 'Отменить',
  ),
  'hyperlink_targets' => array( 
  	'_self' => 'в том же фрейме (_self)',
	'_blank' => 'в новом окне (_blank)',
	'_top' => 'на все окно (_top)',
	'_parent' => 'в родительском фрейме (_parent)'
  ),
  'unlink' => array(
    'title' => 'Убрать ссылку'
  ),
  'table_row_prop' => array(
    'title' => 'Параметры строки',
    'horizontal_align' => 'Горизонтальное выравнивание',
    'vertical_align' => 'Вертикальное выравнивание',
    'css_class' => 'Стиль',
    'no_wrap' => 'Без переноса',
    'bg_color' => 'Цвет фона',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отменить',
    'left' => 'Слева',
    'center' => 'В центре',
    'right' => 'Справа',
    'top' => 'Сверху',
    'middle' => 'В центре',
    'bottom' => 'Снизу',
    'baseline' => 'Базовая линия текста',
  ),
  'symbols' => array(
    'title' => 'Спец. символы',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отменить',
  ),
  'templates' => array(
    'title' => 'Шаблоны',
  ),
  'page_prop' => array(
    'title' => 'Параметры страницы',
    'title_tag' => 'Заголовок',
    'charset' => 'Набор символов',
    'background' => 'Фоновое изображение',
    'bgcolor' => 'Цвет фона',
    'text' => 'Цвет текста',
    'link' => 'Цвет ссылок',
    'vlink' => 'Цвет посeщенных ссылок',
    'alink' => 'Цвет активных ссылок',
    'leftmargin' => 'Отступ слева',
    'topmargin' => 'Отступ сверху',
    'css_class' => 'Стиль',
    'ok' => 'ГОТОВО',
    'cancel' => 'Отменить',
  ),
  'preview' => array(
    'title' => 'Предварительный просмотр',
  ),
  'image_popup' => array(
    'title' => 'Popup изображения',
  ),
  'zoom' => array(
    'title' => 'Увеличение',
  ),
  'subscript' => array(
    'title' => 'Нижний индекс',
  ),
  'superscript' => array(
    'title' => 'Верхний индекс',
  ),
);
?>