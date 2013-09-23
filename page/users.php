<?php // доделать
require_once('page/functions_users.php'); 
global $soderganie, $prefix, $db, $design;
if ($_POST["submit"] == "Добавить") { 
	if (!$_POST["user_name"] || !$_FILES["file"]["size"]) {
		$soderganie .= "<p class='errormes'>".ss("Вы не ввели своё имя или не выбрали фотографию.")."</p>";
	} else {
		// новое имя файла
		$cn_foto = md5(date("Y-M-D-h-m-s"));
		// проверяем размер файла
		if ($_FILES["file"]["size"] > 5242880) { // 1024*5*1024
			$soderganie .= "<p class='errormes'>".ss("Размер файла превышает 5Мб.")."</p>"; 
		} else {
			// Проверяем загружен ли файл
			if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
				// проверяем расширение файла
				if ($_FILES["file"]['type'] == "image/gif" || 
					$_FILES["file"]['type'] == "image/png" || 
					$_FILES["file"]['type'] == "image/jpg" || 
					$_FILES["file"]['type'] == "image/jpeg") {
					$imgname = str_replace("image/", ".", $_FILES["file"]['type']);
					move_uploaded_file($_FILES["file"]["tmp_name"], "img/user/".$cn_foto.$imgname);
					$user_name = $_POST["user_name"];
					$photo = "img/user/".$cn_foto.$imgname;
					$db->sql_query("UPDATE ".$prefix."_users SET `name`='".$user_name."', `photo`='".$photo."' WHERE `user_id`='".$_COOKIE['user_id']."' ;"); 
					user_set_tokens($_COOKIE['email']);
					$soderganie .= ss("Данные обновлены, дождитесь обновления страницы.");
					$soderganie .= "<meta http-equiv='Refresh' content='6'/>";
				} else {
					$soderganie .= "<p class='errormes'>".ss("Можно загружать только изображения в форматах jpg/jpeg, gif или png.");
				}
			} else {
				$soderganie .= "<p class='errormes'>".ss("Ошибка загрузки файла.")."</p>";
			} 
		}
	}
} else {
	if (!$_COOKIE['user_name']) {
		$soderganie .= "<p class='errormes'>".ss("Для начала работы введите ваше имя и загрузите фотогфию.")."</p><br>
        <form class='regforma' action='--users_".$_COOKIE['user_id']."' method='post' enctype='multipart/form-data'>
	    <br><input class='regname' type='name' name='user_name' value='' placeholder='".ss("Ваше имя")."'>
		<br><input class='regfile' type='file' name='file' placeholder='".ss("Выберите фотографию")."'>
		<br><input type='submit' name='submit' value='".ss("Добавить")."'></form>";
	} else {
		// блок пользователя фото и ссылки
		$soderganie .= '<div id="user_blok"><img src="/includes/phpThumb/phpThumb.php?src=/'.$_COOKIE['user_pfoto'].'&amp;w=150&amp;h=0&amp;q=0" title="'.$_COOKIE['user_name'].'"><br>
		<a href="--users_'.$_COOKIE['user_id'].'">'.ss('Моя страница').'</a><br>
		<a href="--adduser_'.$_COOKIE['user_group'].'_0">'.ss('Добавить материал').'</a><br>  
		<a href="--edituser_'.$_COOKIE['user_id'].'">'.ss('Редактировать профиль').'</a><br> 
		<a href="--logout">Выход</a></div>';
		// выводим шаблон страницы пользователя
		$result = $db->sql_query("select useit from ".$prefix."_mainpage where `id`='".$_COOKIE['user_group']."'");
		$row = $db->sql_fetchrow($result);
		$userhtml = '<div id="user_dinfo">'.$row['useit'].'</div>';
		// получаем список полей пользователя
		$result = $db->sql_query("select name, title from ".$prefix."_mainpage where `useit`='1,".$_COOKIE['user_group']."'");
		while ($row = $db->sql_fetchrow($result)) {
			$upole = $row['name'];
			$utitle = $row['title'];
			// содержание поля для пользователя
			$result = $db->sql_query("select name from ".$prefix."_spiski where `type`='$upole' and `pages`='1,".$_COOKIE['user_id']."'");
			while ($row = $db->sql_fetchrow($result)) {
				// выполняем замену имени блока на содержание
				$userhtml2 .= str_replace("[".$upole."]", "$utitle ".$row['name'], $userhtml);
			}
		}
		$soderganie .= $userhtml2;
		// что пользователь добавил
		$result = $db->sql_query("select pid, module, title, open_text from ".$prefix."_pages where `user`='".$_COOKIE['user_id']."'");
		while ($row = $db->sql_fetchrow($result)) {
			$soderganie .= '<div class="user_sp_add"><p class="title"><a href="-'.$row['module'].'_page_'.$row['pid'].'">'.$row['title'].'</a></p><br><p class="content">'.$row['open_text'].'</p></div>'; 
		}
	}
}
list($design_for_reg, $stil) = design_and_style($design);
if ($design_for_reg == "0") die(ss("Ошибка: «Адрес раздела»")." (".$name.") ")."введен неправильно. Перейдите на")." <a href=/>")."Главную страницу")."</a>."); 
$block = str_replace(aa("[содержание]"), $soderganie, $design_for_reg);
return array($block, $stil);
?>