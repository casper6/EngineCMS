<?
	define('DATE_TIME_FORMAT', 'd M Y H:i');
	//Label
		//Top Action
		define('LBL_ACTION_REFRESH', 'Обновить');
		define("LBL_ACTION_DELETE", 'Удалить отмеченные');
		//File Listing
	define('LBL_NAME', 'Имя');
	define('LBL_SIZE', 'Размер');
	define('LBL_MODIFIED', 'Изменено в');
		//File Information
	define('LBL_FILE_INFO', 'Информация о файле:');
	define('LBL_FILE_NAME', 'Имя:');	
	define('LBL_FILE_CREATED', 'Создан в:');
	define("LBL_FILE_MODIFIED", 'Изменен в:');
	define("LBL_FILE_SIZE", 'Размер файла:');
	define('LBL_FILE_TYPE', 'Тип файла:');
	define("LBL_FILE_WRITABLE", 'Запиcь?');
	define("LBL_FILE_READABLE", 'Чтение?');
		//Folder Information
	define('LBL_FOLDER_INFO', 'Информация о папке');
	define("LBL_FOLDER_PATH", 'Путь:');
	define("LBL_FOLDER_CREATED", 'Создана в:');
	define("LBL_FOLDER_MODIFIED", 'Изменена в:');
	define('LBL_FOLDER_SUDDIR', 'Подпапки:');
	define("LBL_FOLDER_FIELS", 'Файлы:');
	define("LBL_FOLDER_WRITABLE", 'Запиcь?');
	define("LBL_FOLDER_READABLE", 'Чтение?');
		//Preview
	define("LBL_PREVIEW", 'Предпросмотр');
	//Buttons
	define('LBL_BTN_SELECT', 'Выберите');
	define('LBL_BTN_CANCEL', 'Отмена');
	define("LBL_BTN_UPLOAD", 'Загрузить');
	define('LBL_BTN_CREATE', 'Создать');
	define("LBL_BTN_NEW_FOLDER", 'Имя_новой_папки');
	//ERROR MESSAGES
		//deletion
	define('ERR_NOT_FILE_SELECTED', 'Выберите файл.');
	define('ERR_NOT_DOC_SELECTED', 'Ничего не отмечено для удаления.');
	define('ERR_DELTED_FAILED', 'Невозможно удалить отмеченное.');
	define('ERR_FOLDER_PATH_NOT_ALLOWED', 'Ошибка при обращении к папке: путь не найден.');
		//class manager
	define("ERR_FOLDER_NOT_FOUND", 'Невозможно найти папку: ');
		//rename
	define('ERR_RENAME_FORMAT', 'Дайте имя, содержащее только буквы, цифры, подчеркивание и тире.');
	define('ERR_RENAME_EXISTS', 'Дайте уникальное имя — обнаружен повтор.');
	define('ERR_RENAME_FILE_NOT_EXISTS', 'Объект не существует.');
	define('ERR_RENAME_FAILED', 'Не получается переименовать, попробуйте еще раз.');
	define('ERR_RENAME_EMPTY', 'Введите имя.');
	define("ERR_NO_CHANGES_MADE", 'Без изменений.');
	define('ERR_RENAME_FILE_TYPE_NOT_PERMITED', 'Вы не можете изменить данный файл. Обратитесь к разработчику');
		//folder creation
	define('ERR_FOLDER_FORMAT', 'Дайте имя, содержащее только буквы, цифры, подчеркивание и тире.');
	define('ERR_FOLDER_EXISTS', 'Дайте папке уникальное имя — обнаружен повтор.');
	define('ERR_FOLDER_CREATION_FAILED', 'Не получается создать папку, попробуйте еще раз.');
	define('ERR_FOLDER_NAME_EMPTY', 'Введите имя.');
	
		//file upload
	define("ERR_FILE_NAME_FORMAT", 'Дайте имя, содержащее только буквы, цифры, подчеркивание и тире.');
	define('ERR_FILE_NOT_UPLOADED', 'Не выбран файл для загрузки на сервер! Выберите.');
	define('ERR_FILE_TYPE_NOT_ALLOWED', 'Вы не можете загрузить этот тип файла.');
	define('ERR_FILE_MOVE_FAILED', 'Ошибка при перемещении файла.');
	define('ERR_FILE_NOT_AVAILABLE', 'Файл не доступен.');
	define('ERROR_FILE_TOO_BID', 'Файл черезчур велик. (Максимум: %s)');
	

	//Tips
	define('TIP_FOLDER_GO_DOWN', 'Нажмите 1 раз для перехода в эту папку...');
	define("TIP_DOC_RENAME", 'Двойной клик для переименования...');
	define('TIP_FOLDER_GO_UP', 'Нажмите 1 раз для перехода в предыдущую папку...');
	define("TIP_SELECT_ALL", 'Выбрать все');
	define("TIP_UNSELECT_ALL", 'Снять выделение');
	//WARNING
	define('WARNING_DELETE', 'Вы уверены, что хотите удалить выбранное.');
	//Preview
	define('PREVIEW_NOT_PREVIEW', 'Нет ничего для предпросмотра.');
	define('PREVIEW_OPEN_FAILED', 'Не получается открыть файл.');
	define('PREVIEW_IMAGE_LOAD_FAILED', 'Ошибка при загрузке предпросмотра картинки');

	//Login
	define('LOGIN_PAGE_TITLE', 'Менеджер файлов');
	define('LOGIN_FORM_TITLE', 'Вход');
	define('LOGIN_USERNAME', 'Имя:');
	define('LOGIN_PASSWORD', 'Пароль:');
	define('LOGIN_FAILED', 'Неверные имя или пароль.');
	
	
?>