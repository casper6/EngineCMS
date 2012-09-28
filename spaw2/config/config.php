<?php
// НАСТРОЙКИ РЕДАКТОРА SPAW 2.0.8.1 (2008-09-10)
// Полная русская локализация by 13i
// http://solmetra.com/en/disp.php/en_products/en_spaw/en_spaw_intro
// Перевод файла настроек - Кирилл Павлюков, kirill.in.ua
require_once(str_replace('\\\\','/',dirname(__FILE__)).'/../class/config.class.php');
require_once(str_replace('\\\\','/',dirname(__FILE__)).'/../class/util.class.php');
require_once(str_replace('\\\\','/',dirname(__FILE__)).'/../../config.php'); // Забираем настройку пути к сайту
global $http_siteurl;
// Определяет расположение корневой директории вашего сайта на сервере.
// если расчеты ошибочны (обычно когда веб-сервер - не апач) - настройте это самостоятельно
SpawConfig::setStaticConfigItem('DOCUMENT_ROOT', str_replace("\\","/",SpawVars::getServerVar("DOCUMENT_ROOT")));
if (!ereg('/$', SpawConfig::getStaticConfigValue('DOCUMENT_ROOT')))
SpawConfig::setStaticConfigItem('DOCUMENT_ROOT', SpawConfig::getStaticConfigValue('DOCUMENT_ROOT').'/');
// Определяет путь к папке SPAW на сервере.
// в основном прекрасно работает, но если расчеты ошибочны - настройте это самостоятельно
SpawConfig::setStaticConfigItem('SPAW_ROOT', str_replace("\\","/",realpath(dirname(__FILE__)."/..").'/'));
// Определяет путь от корня сайта до директории SPAW.
// если расчеты ошибочны - настройте это самостоятельно
SpawConfig::setStaticConfigItem('SPAW_DIR', '/'.str_replace(SpawConfig::getStaticConfigValue("DOCUMENT_ROOT"),'',SpawConfig::getStaticConfigValue("SPAW_ROOT")));

/*
// полуавтоматический расчет пути до редактора SPAW
// раскомментируйте эти настройки DOCUMENT_ROOT, SPAW_ROOT и SPAW_DIR
// и используйте их ТОЛЬКО если вышестоящие не справились со своей задачей.
// Настройте SPAW_DIR самостоятельно. Если доступ к странице - http://site.com/spaw2/demo/demo.php
// тогда SPAW_DIR = /spaw2/
SpawConfig::setStaticConfigItem('SPAW_DIR', '/spaw2/');
// и эти настройки тоже автоматические
SpawConfig::setStaticConfigItem('SPAW_ROOT', str_replace("\\","/",realpath(dirname(__FILE__)."/..").'/'));
SpawConfig::setStaticConfigItem('DOCUMENT_ROOT', substr(SpawConfig::getStaticConfigValue('SPAW_ROOT'),0,strlen(SpawConfig::getStaticConfigValue('SPAW_ROOT'))-strlen(SpawConfig::getStaticConfigValue('SPAW_DIR'))));
*/

/*
// Только для МикроСофт-сервера IIS - нужно будет использовать эти настройки. Не дай вам Бог :)
SpawConfig::setStaticConfigItem('DOCUMENT_ROOT', 'c:/inetpub/wwwroot/');
SpawConfig::setStaticConfigItem('SPAW_ROOT', 'c:/inetpub/wwwroot/spaw2/');
SpawConfig::setStaticConfigItem('SPAW_DIR', '/spaw2/');
*/


###############################################################################################
// ПО УМОЛЧАНИЮ: Эти параметры применяются, если они не будут переопределены в экземпляре SPAW.
###############################################################################################
// Язык интерфейса. Значение по умолчанию – en (English), но полностью переведенный на русский
SpawConfig::setStaticConfigItem('default_lang','en');
// Кодировка, используемая для вывода. Если значение - пустая строка - будет использована кодировка, указанная в языковом файле текущего языка.
SpawConfig::setStaticConfigItem('default_output_charset','');
// Имя темы интерфейса (оформление, skin).
SpawConfig::setStaticConfigItem('default_theme','spaw2lite');
// Набор панелей инструментов (toolbar) по-умолчанию.
SpawConfig::setStaticConfigItem('default_toolbarset','standard');
// Путь к таблице стилей CSS, которая по умолчанию будет применена к области редактирования.
SpawConfig::setStaticConfigItem('default_stylesheet',SpawConfig::getStaticConfigValue('SPAW_DIR').'wysiwyg.css');
// Ширина окна редактора по-умолчанию
SpawConfig::setStaticConfigItem('default_width','100%');
// Высота окна редактора по-умолчанию
SpawConfig::setStaticConfigItem('default_height','200px');


###############################################################################################
// Настройки движка SPAW: Эти настройки определяют внутреннее поведение различных компонентов движка SPAW
###############################################################################################
// Определяет, должна ли языковая подсистема использовать функции iconv для преобразования строк к выбранной кодировке. Если в вашей сборке PHP iconv недоступен, установите этот параметр в false. В других ситуациях можете оставить этот параметр установленным в true, даже если вам не нужна функциональность конверсии символов.
SpawConfig::setStaticConfigItem('USE_ICONV',true);
// Определяет, какой метод рендеринга HTML должен быть использован. Доступные значения: xhtml и builtin. Режим xhtml предписывает SPAW использовать собственные механизмы рендеринга для генерации хорошо согласованного кода XHTML в различных браузерах, builtin – предписывает использовать механизмы текущего браузера. builtin работает быстрее, но генерирует разный код в разных браузерах.
SpawConfig::setStaticConfigItem('rendering_mode', 'xhtml', SPAW_CFG_TRANSFER_JS);
// Если установлено в true, подсистема рендеринга XHTML “украсит” конечную структуру html отступами и пр.
SpawConfig::setStaticConfigItem('beautify_xhtml_output', true, SPAW_CFG_TRANSFER_JS);
// Определяет строку, состоящую из протокола, хоста и порта (например, http://mydomain.com), которая будет добавлена к url, возвращаемым файл-менеджером. Используйте совместно с strip_absolute_urls для принудительного избавления от абсолютных путей. В данном случае переменная $http_siteurl берется из файла настройки CMS - config.php в корне сайта (обращение к нему - сверху)
SpawConfig::setStaticConfigItem('base_href', $http_siteurl, SPAW_CFG_TRANSFER_JS);
// Если установлено в true, SPAW обрежет части домена и пути из всех “локальных” (относящихся к данному веб-сайту и/или директории) url в ссылках и изображениях. Замечание: Microsoft Internet Explorer преобразовывает все url в абсолютные, поэтому данная настройка является обходным путем для избавления от абсолютной части, присоединяемой IE к введенным пользователями относительным url.
SpawConfig::setStaticConfigItem('strip_absolute_urls', true, SPAW_CFG_TRANSFER_JS);
// Определяет в каких направлениях пользователь может менять размер окна редактора. Доступыне значение: none, horizontal, vertical, both
SpawConfig::setStaticConfigItem('resizing_directions', 'vertical', SPAW_CFG_TRANSFER_JS);
// Указывает необходимость преобразования спецсимволов в соответствующие html-сущности (например © и др.)
SpawConfig::setStaticConfigItem('convert_html_entities', false, SPAW_CFG_TRANSFER_JS);


###############################################################################################
// Значения выпадающих списков
// Эти параметры содержат данные, используемые для заполнения выпадающих списков в панелях инструментов и диалогах. Их значения – массивы PHP, ключи которых трансформируются в значения элементов выпадающего меню, а значения наоборот – станут названиями этих элементов. Логичнее было бы сделать так, чтобы ключи оставались ключами, а значения – значениями. Но разрабочики, видимо, посчитали, что это будет сильно просто - прим. переводчика).
###############################################################################################
// Список классов CSS для выпадающего списка Стили. Пустая строка удалит аттрибут class HTML-элемента
SpawConfig::setStaticConfigItem("dropdown_data_core_style",
  array(
    '' => 'обычный',
    'white' => 'Белый',
    'red' => 'Красный',
    'orange' => 'Оранж',
     'yellow' => 'Желтый',
    'green' => 'Зеленый',
    'lightblue' => 'Голубой',
    'blue' => 'Синий',
    'violet' => 'Фиолет',
    'black' => 'Черный',
  )
);

// Список классов CSS для выпадающего списка Стили в диалоге создания Ссылки.
SpawConfig::setStaticConfigItem("hyperlink_styles",
  array(
    '' => 'обычный',    
    'text_link' => '_ _Текст_ _',

    'green_link' => 'Зеленый',
    'red_link' => 'Красный',

    'zip_link' => 'Архив. файл',
    'doc_link' => 'Текст. файл Word',
    'avi_link' => 'Видео файл',
    'mp3_link' => 'Муз. файл',
    'jpg_link' => 'Картинка файл',
    'list_link' => 'Пустой лист',
    'pdf_link' => 'PDF файл',
    'xls_link' => 'Таблица Excel файл',

    'love_link' => 'Сердечко',
    'arrow_link' => 'Стрелочка',
    'progress_link' => 'Прогресс (анимация фона)',
    'video_link' => 'Видео-экран',
    'home_link' => 'Домой',
    'user_link' => 'Лицо (для комментария)',
  )
);

//     'question_link' => 'Вопрос ?',
//    'wtf_link' => 'Восклицание !',

// Список классов CCS, которые используются в диалоге Свойства таблицы
SpawConfig::setStaticConfigItem("table_styles",
  array(
    '' => 'обычный',
    'white' => 'Белый!',
    'red' => 'Красный!',
    'orange' => 'Оранж!',
     'yellow' => 'Желтый!',
    'green' => 'Зеленый!',
    'lightblue' => 'Голубой!',
    'blue' => 'Синий!',
    'violet' => 'Фиолет!',
    'black' => 'Черный!',
  )
);
// Список имен шрифтов выпадающего списка Шрифт
SpawConfig::setStaticConfigItem("dropdown_data_core_fontname",
  array(
    'Arial' => 'Arial',
    'Courier' => 'Courier',
    'Tahoma' => 'Tahoma',
    'Times New Roman' => 'Times',
    'Verdana' => 'Verdana'
  )
);
// Список размеров шрифта выпадающего списка Размер
SpawConfig::setStaticConfigItem("dropdown_data_core_fontsize",
  array(
    '1' => '6px',
    '2' => '9px',
    '3' => '10px',
    '4' => '11px',
    '5' => '12px',
    '6' => '13px',
    '7' => '16px'
  )
);
// Список стилей параграфа выпадающего списка Параграф
SpawConfig::setStaticConfigItem("dropdown_data_core_formatBlock",
  array(
    '<H1>' => 'Заголовок 1 (большой)',
    '<H2>' => 'Заголовок 2',
    '<H3>' => 'Заголовок 3',
    '<H4>' => 'Заголовок 4',
    '<H5>' => 'Заголовок 5',
    '<H6>' => 'Заголовок 6 (маленький)',
    '<pre>' => 'Выделение',
    '<address>' => 'Автор и Источник',
    '<p>' => 'Параграф (абзац)'
  )
);
// Список целей (targets) ссылок диалога Ссылка
SpawConfig::setStaticConfigItem("a_targets",
  array(
    '_self' => 'Self',
    '_blank' => 'Blank',
    '_top' => 'Top',
    '_parent' => 'Parent'
  )
);


###############################################################################################
// Наборы панелей инстументов
// Набор панелей инструментов – это всего лишь составленный список панелей инструментов. Вы можете указать отображаемые панели инструментов в вашем экземпляре SPAW по одной или определить набор, содержащий все необходимые панели инструментов. Следующая таблица содержит список встроенных наборов панелей инструментов. Вы также можете создать свои собственные наборы панелей инструментов.
// Стандартные панели инструментов называются так: edit, format, font, insert, table, tools и plugins. Разработчики тем определяют место для размещения панелей инструментов, но вы можете подменить стандартную панель инструментов своей. (Имеется в виду, что если ваша собственная панель не помещается в отведенное разработчиком темы место, вы можете подменить одну из стандартных панелей - прим. переводчика.) Чтобы подменить стандартную панель, установите ключом массива имя стандартной панели инструментов (например, format), а значением - имя своей панели инструментов (например, format_mini). Посмотрите для наглядного примера определение набора панели инструментов “mini” из конфигурационного файла по-умолчанию. Элементы конфигурации, определяющие наборы панелей инструментов начинаются со строки ”toolbarset_” за которой следует имя панели инструментов. Для более точного понимания настройки панелей инструментов, прочитите соответствующий раздел. (Этот раздел будет переведен позже - прим. переводчика).
###############################################################################################
// Включает все стандартные панели инструментов, кроме панели font
SpawConfig::setStaticConfigItem('toolbarset_standard',
  array(
    "format" => "format",
    "style" => "style",
    "edit" => "edit",
    "table" => "table",
    "plugins" => "plugins",
    "insert" => "insert",
    "tools" => "tools"
  ) 
);
// Включает все стандартные панели инструментов
SpawConfig::setStaticConfigItem('toolbarset_all',
  array(
    "format" => "format",
    "style" => "style",
    "edit" => "edit",
    "table" => "table",
    "plugins" => "plugins",
    "insert" => "insert",
    "tools" => "tools",
    "font" => "font"   
  ) 
);
// Включает панели edit и tools, а также урезанную версию панели format
SpawConfig::setStaticConfigItem('toolbarset_mini',
  array(
    "format" => "format_mini",
    "edit" => "edit",
    "tools" => "tools"
  ) 
);
###############################################################################################
// Предопределенные палитры цветов: Начиная с версии 2.0.3, появилась возможность изменять предустановленные цвета в палитре диалога выбора цветов. Чтобы сделать это, установите параметр colorpicker_predefined_colors. Значением этого параметра должен быть массив из не более чем 16 элементов. Каждый элемент массива должен быть значением цвета в CSS-совместимом формате.
SpawConfig::setStaticConfigItem('colorpicker_predefined_colors',
  array(
    'black',
    'silver',
    'gray',
    'white',
    'maroon',
    'red',
    'purple',
    'fuchsia',
    'green',
    'lime',
    'olive',
    'yellow',
    'navy',
    'blue',
    '#fedcba',
    'aqua'
  ),
  SPAW_CFG_TRANSFER_SECURE
);


###############################################################################################
// Настройки плагина SPAW File Manager
// Плагин File Manager позволяет управлять файлами на сервере и вставлять их в редактируемый текст (например, изображения, flash-ролики). Также он дает возможность контролировать доступ ваших пользователей к их файлам, и что им позволено с этими файлами делать.
// Для настройки файл-менеджера SPAW под ваши потребности, вам нужно указать два главных параметра: глобальный массив настроек, который определяет, что может быть сделано с директориями, доступными через файл-менеджер SPAW, и собственно список директорий. Глобальные настройки могут быть переопределены для каждой конкретной директории, поэтому вы можете позволить обработку только изображений в директории “Изображения”, только flash-ролики в директории “Flash” и так далее. Вот детальное описание для каждого из этих двух главных параметров:
###############################################################################################
// Глобальные параметры для всех директорий, указанные в виде пар параметр => значение:
SpawConfig::setStaticConfigItem(
  'PG_SPAWFM_SETTINGS',
  array(
    'allowed_filetypes'   => array('any'),  // массив имен типов файлов (images/flash/documents/audio/video/archives/any), определяющий, файлы какого типа(ов) будут отображены в списке/позволенный для закачки. Вы можете добавить свой собственный тип файлов или изменить существующие добавив/отредактировав список расширений в файле /plugins/spawfm/config/config.php. Все определенные типы файлов позволены по-умолчанию;
    'allow_modify'        => true,         // логическое значение, определяющее возможность удаления файлов, по-умолчанию - false;
    'allow_upload'        => true,         // логическое значение, определяющее возможность загрузки файлов, по-умолчанию - false;
    'chmod_to'          => 0777,          // восьмиричное значени режима доступа, к которому будет сделана попытка chmod для загружаемых файлов, или false для пренебрежения (по-умолчанию);
                                            // (see PHP chmod() function description for details), or comment out to leave default
    'max_upload_filesize' => 5000000,      // целое значение, указывающее максимально возможный размер загружаемых файлов в байтах (будьте внимательны, что параметр upload_max_filesize из php.ini имеет больший приоритет, поэтому данный параметр должен быть меньше), или ноль для игнорирования данной опции (по-умолчанию);
    'max_img_width'       => 0,             // целое значение, определяющее максимально допустимую ширину загужаемых изображений, или ноль для игнорирования (по-умолчанию);
    'max_img_height'      => 0,             // целое значение, определяющее максимально допустимую высоту загужаемых изображений, или ноль для игнорирования (по-умолчанию);
    'recursive'           => true,         // логическое значение, определяющее возможность просмотра/открытия подкаталогов, false по-умолчанию;
    'allow_modify_subdirectories' => true, // логическое значение, определяющее возможность переименования/удаления подкаталогов (параметр recursive должен быть установлен в true), false по-умолчанию;
    'allow_create_subdirectories' => true, // логическое значение, определяющее возможность создания подкаталогов (параметр recursive должен быть установлен в true), false по-умолчанию;
    'forbid_extensions'   => array('php'),  // массив строк, определяющий небезопасные расширения. Файлы с такими расширениями нельзя загружать (а также переименовывать). Установите этот параметр в конфигурационном файле по-умолчанию как array(’php’), чтобы каждый раз не проверять, не пропущена ли эта настройка;
    'forbid_extensions_strict' => true,     // логическое значение, которое запрещает использование расширений внутри имен файлов. (Например, file.php.jpg. Некоторые сервера могут быть настроены так, что если пропущена настройка расширения JPG, они выполнят этот файл, как сценарий PHP. Поэтому использование расширений внутри имен файлов может быть опасным.) Этот параметр установлен в true и рассматривается как false в противном случае. Он проверяется только при установленном параметре forbid_extensions.
  ),
  SPAW_CFG_TRANSFER_SECURE
);

// Определяет доступные через файл-менеджер SPAW директории. Указывается в виде массива, в котором каждой директории соответствует подмассив пар параметр => значение: 
// dir - путь к директории, относительно URL домена (или параметра base_href, если он установлен). Пример: если ваши изображения находятся в “www.domain.com/files/images/”, установите этот параметр в “/files/images/”. Начиная с версии 2.0.2 этот параметр может также быть абсолютным URL, но в таком случае параметр fsdir также должен быть установлен. Этот параметр обязателен.
// fsdir - абсолютный путь в файловой системе (необязательный). Если этот параметр установлен, он определяет, где необходимо производить поиск файлов, в противном случае поиск файлов будет производиться в расположении, указанном в параметре dir. В любом случае файл-менеджер SPAW вернет значение, указанное в параметре dir, как путь в сетевом адресе выбранного файла (предварив его значением параметра base_href, если последний указан). Пример: если ваш веб-узел размещается в директории “/usr/data/www/domain/” и доступен по адресу www.domain.com, но ваши изображений хранятся в “/usr/data/www/other_domain/images/”, но доступны по адресу http://www.domain.com/images/ - вам необходимо установить dir в “/images/” и fsdir в “/usr/data/www/other_domain/images/”.
// caption - название директории, которое отображается в файл-менеджере SPAW. Если этот параметр не определен, будет отображен путь к директории, поэтому caption – необязательный параметр.
// params - массив любых глобальных настроек, которые вы хотите переопределить для этой директории, а также необязательный параметр default_dir (устанавите его в true, чтобы эта директория открывалась по-умолчанию). Это необязательный параметр.
SpawConfig::setStaticConfigItem(
  'PG_SPAWFM_DIRECTORIES',
  array(
    array(
      'dir'     => SpawConfig::getStaticConfigValue('SPAW_DIR').'uploads/files/',
      'caption' => 'Документы, архивы, музыка, видео', 
      'params'  => array(
        'allowed_filetypes' => array('any')
      )
    ),
    array(
      'dir'     => SpawConfig::getStaticConfigValue('SPAW_DIR').'uploads/images/',
      'caption' => 'Основная папка изображений',
      'params'  => array(
        'allowed_filetypes' => array('any')
      )
    ),
    array(
      'dir'     => SpawConfig::getStaticConfigValue('SPAW_DIR').'uploads/images/fotos/',
      'caption' => 'Фото (или выберите другую папку)',
      'params'  => array(
        'default_dir' => true, // основная папка
        'allowed_filetypes' => array('any')
      )
    ),
        array(
      'dir'     => SpawConfig::getStaticConfigValue('SPAW_DIR').'uploads/images/firms/',
      'caption' => 'Баннеры',
      'params'  => array(
        'allowed_filetypes' => array('any')
      )
    ),
        array(
      'dir'     => SpawConfig::getStaticConfigValue('SPAW_DIR').'uploads/images/icons/',
      'caption' => 'Мини-картинки',
      'params'  => array(
        'allowed_filetypes' => array('any')
      )
    ),
        array(
      'dir'     => SpawConfig::getStaticConfigValue('SPAW_DIR').'uploads/images/pics/',
      'caption' => 'Рисунки и схемы',
      'params'  => array(
        'allowed_filetypes' => array('any')
      )
    ),
        array(
      'dir'     => SpawConfig::getStaticConfigValue('SPAW_DIR').'uploads/images/pages/',
      'caption' => 'Страницы (дополнительная папка)',
      'params'  => array(
        'allowed_filetypes' => array('any')
      )
    ),
        array(
      'dir'     => SpawConfig::getStaticConfigValue('SPAW_DIR').'uploads/images/news/',
      'caption' => 'Новое (дополнительная папка)',
      'params'  => array(
        'allowed_filetypes' => array('any')
      )
    ),
    array(
      'dir'     => SpawConfig::getStaticConfigValue('SPAW_DIR').'uploads/flash/',
      'caption' => 'Flash-анимация', 
      'params'  => array(
        'allowed_filetypes' => array('any')
      )
    ),
    
  ),
  SPAW_CFG_TRANSFER_SECURE
);
?>
