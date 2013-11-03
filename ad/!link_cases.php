<?php
if (!eregi("sys.php", $_SERVER['PHP_SELF'])) { die ("Доступ закрыт!"); }
switch($op) {
    case "mainpage":
    case "mainpage_save":
    case "mainpage_del":
    case "mainpage_recycle_spiski":
    case "mainpage_create_module":
    case "mainpage_create_design":
    case "mainpage_create_css":
    case "mainpage_create_block":
    case "mainpage_razdel_color":
    include("ad/ad-mainpage.php");
    break;

    case "base_comments":
    case "base_comments_edit_comments":
    case "base_comments_edit_sv_comments":
    case "base_comments_status":
    include("ad/ad-comm.php");
    break;

    case "base_pages":
    case "base_pages_cat":
    case "base_pages_teleport":
    case "base_pages_add_category":
    case "base_pages_add_sub_category":
    case "edit_base_pages_category":
    case "base_pages_save_category":
    case "base_pages_del_category":
    case "base_pages_add_page":
    case "base_pages_save_page":
    case "base_pages_edit_page":
    case "base_pages_edit_sv_page":
    case "base_pages_delit_page":
    case "base_pages_status_page":
    case "base_pages_delit_comm":
    case "delete_noactive_comm":
    case "delete_system_comm":
    case "base_delit_comm":
    case "delete_all":
    case "base_pages_re":
    case "page_save_spiski":
    include("ad/ad-page.php");
    break;

    case "options":
    case "options_save":
    case "update_author":
    case "save_banned":
    case "ipban_delete":
    case "ipban_edit":
    case "ipban_save":
    case "subscribe":
    case "admins_list":
    case "admins_add":
    case "admins_edit":
    case "admins_edit_save":
    case "admins_delete":
    include("ad/ad-options.php");
    break;

    case "base_base":
    case "base_base_edit_base":
    case "base_base_edit_sv_base":
    case "base_base_delit_base":
    case "base_base_create_base":
    case "base_base_pause_pass":
    case "base_base_new_pass":
    include("ad/ad-baza.php");
    break;

    case "backup":
    include("ad/ad-backup.php");
    break;
    
// Добавлены пользователи
    case "users":
    case "add_group":
    case "save_users":
    case "del_group":
    case "del_group2":
    case "edit_group":
    case "s_group":
    case "s_html_group":
    case "html_group":
    case "adduser":
    include("ad/ad-users.php");
    break;
	
    case "regions_main":
    case "regions_install":
    case "regions_vibor":
    case "regions_addbase":
    case "regions_menu":
    include("ad/ad-regions.php");
    break;
	
// Импорт из txt и csv
    case "txt_and_csv_main":
    case "txt_and_csv_install":
    case "start_txt_and_csv":
    case "upload_txt_and_csv":
    case "step2_txt_and_csv":
    case "txt_and_csv_zip":
    include("ad/ad-csv.php");
    break;
	// парсер
    case "parser_main":
	case "parser_install":
	case "parser_temp":
	case "parser_temp_del":
	case "parser_temp_edit":
	case "parser_prev":
	case "parser_clear":
	case "parser_del":
    include("ad/ad-parser.php");
    break;
}
?>