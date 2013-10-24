<?php
	if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
	$aid = trim($aid);
	global $prefix, $db, $red;
	$sql = "select realadmin from ".$prefix."_authors where aid='$aid'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$realadmin = $row['realadmin'];
	if ($realadmin==1) {
////////////////////////////////////////////////////////
function users() {
	global $prefix, $db, $title_razdels_by_id;
	include("ad/ad-header.php");

	echo "<table style='width:100%; margin-top:5px; padding:0; background: #e2e5ea;' cellspacing=0 cellpadding=0><tr valign=top><td id='razdel_td' class='radius nothing' width=340>
			<div id='razdels' style='background:#e7e9ec;'>
			<div class='black_grad'>
			<button id=new_razdel_button title='Назад в настройки сайта' class='small black' onclick='location.href=\"/sys.php?op=options#1\"' style='float:left; margin:3px;'><span style='margin-right: -2px;' class=\"icon darkgrey small\" data-icon=\"{\"></span></button>
			<span class='h1'>Настройка пользователей</span></div>";
	
	echo "<div id='mainrazdel1' class='dark_pole2'><a class='base_page' onclick=\"options_show('1','show_recent')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"v\"></span><span class='plus20'>Настройка регистрации</span></div></a></div>";
	echo "<div class='right3 '><button id=mainrazdel4 style='float:right;' title='Добавить группу...' class='dark_pole2' onclick=\"options_show('4','show_add_group');\"><span class='mr-2 icon darkgrey small' data-icon='+'></span><span class='plus20'>Добавить</span></button></div>
		<div id='mainrazdel0' class='dark_pole2 dark_pole2sel'><a class='base_page' onclick=\"options_show('0','show_first')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"U\"></span><span class='plus20'>Группы пользователей</span></div></a></div>";
	echo "<div id='mainrazdel2' class='dark_pole2'><a class='base_page' onclick=\"options_show('2','show_user')\"><div id='mainrazdel".$id."'><span class=\"icon gray large\" data-icon=\"u\"></span><span class='plus20'>Пользователи</span></div></a></div>";
	echo "</div></td><td>";
echo "<div id='show_first' class='show_pole'>
    <div class='notice warning'>Используйте блок <b>[регистрация]</b> для вывода формы регистрации</div>
    <table class='table_light'>";
	$result = $db->sql_query("select * from ".$prefix."_mainpage where `type`='10' and `name`!='config'");
	while ($row = $db->sql_fetchrow($result)) {
		$html = $row['title'];
		$gid = $row['id'];
		$ids = explode(",", trim($row['text']));
		echo '<tr><td><h2>'.$row['name'].'&darr;</h2>'; 
		$res = $db->sql_query("select `title` from ".$prefix."_mainpage where `id`='".$html."'");
		$row = $db->sql_fetchrow($res);
		echo "<h3>Использует дизайн: ".$row['title']."</h3>
		<h3>Можно добавлять материалы в разделы: ";
		$titles = array();
		foreach($ids as $id) {
			if ($id != '') $titles[] = $title_razdels_by_id[$id]; // заменить на html ???
		}
		echo implode(", ",$titles).'</h3></td><td>
		<a href="sys.php?op=edit_group&amp;id='.$gid.'" title="Настроить"><img class="icon2 i38" src="/images/1.gif"></a>
		<a href="sys.php?op=html_group&amp;id='.$gid.'" title="Шаблон страницы пользователя"><img class="icon2 i34" src="/images/1.gif"></a>
		<a href="sys.php?op=del_group2&amp;id='.$gid.'" title="Удалить"><img class="icon2 i21" src="/images/1.gif"></a></td></tr>';
	}
	echo "</table></div><div id='show_recent' class='show_pole' style='display:none;'>";
	$result2 = $db->sql_query("select * from ".$prefix."_mainpage where `type`='10' and `name`='config'");
	while ($row = $db->sql_fetchrow($result2)) {
		$cat = $row['text'];
		$id = intval($row['title']);
		$htmlstr = $row['useit'];
		echo '<form action="sys.php?op=save_users" method="post">
		<p>Дизайн для модуля:<br><select name="html">';
		$res = $db->sql_query("select `id`, `title` from ".$prefix."_mainpage where `id`='".$id."'");
		while ($row = $db->sql_fetchrow($res)) {
			echo '<option value="'.$row['id'].'">'.$row['title'].'</option>'; 
		}
		$res2 = $db->sql_query("select `id`, `title` from ".$prefix."_mainpage where `type`='0' and `id`!='".$id."'");
		while ($row = $db->sql_fetchrow($res2)) {
			echo "<option value=\"".$row['id']."\">".$row['title']."</option>";
		}
        echo '</select><p>Минимальная длина пароля <input type="text" name="cat" size=3 value="'.$cat.'"> символов';
	    $numrows = $db->sql_numrows($db->sql_query("SELECT `id` FROM ".$prefix."_regions"));
			if ($numrows > 0) {
				echo '<h2>Использование регионов:</h2>';
				include("includes/regions/config.php");
				if ($stryktyra ==1) echo 'область-район-город';
				if ($stryktyra ==2) echo 'область-город';
				if ($stryktyra ==3) echo 'область-район';
				if ($stryktyra ==4) echo 'район-город';
				if ($stryktyra ==5) echo 'только области';
				if ($stryktyra ==6) echo 'только районы';
				if ($stryktyra ==7) echo 'только города';
				echo '</h5><select name="htmlstr">';
				if ($htmlstr == 0) echo "<option value=\"0\">Нет</option><option value=\"1\">Да</option>";
				if ($htmlstr == 1) echo "<option value=\"1\">Да</option><option value=\"0\">Нет</option>";
				echo '</select><Br>Если да то выбор региона будет обязателен для пользователя при регистрации';
			}
		echo '<Br><button type="submit" class="medium green"><span class="mr-2 icon white medium" data-icon="c" style="display: inline-block; "></span>Сохранить</button></form>'; 
	}
	echo "</div><div id='show_add_group' class='show_pole' style='display:none;'>
	<div class='block_white2 radius block add_oformlenie'>
	<h1>Вы решили добавить группу пользователей</h1>";
	echo '<form action="sys.php?op=add_group"  method="post">
	<p>Название группы: <input type="text" size="60" name="group" value="" class="w100" placeholder="Имя группы" autofocus></p>
	<button type="submit" class="medium green right3"><span class="mr-2 icon white medium" data-icon="c" style="display: inline-block; "></span>Добавить</button>
	<p>Дизайн группы: <select name="html"><option value="0">Выберите дизайн для группы</option>'; 
	$res3 = $db->sql_query("select `id`, `title` from ".$prefix."_mainpage where `type`='0'");
	while ($row = $db->sql_fetchrow($res3)) {
		echo "<option value=\"".$row['id']."\">".$row['title']."</option>";
	}
    echo '</select>
    <h2>Выберите разделы для публикаций</h2>
    <p>Все пользователи группы имеют право добавлять материалы в выбранные ниже разделы:</p>'; 
	$resl = $db->sql_query("select `id`, `title` from ".$prefix."_mainpage where `type`='2' and `id`!='24'");
	while ($row = $db->sql_fetchrow($resl)) {
		echo '<input type="checkbox" name="cat'.$row['id'].'" value="'.$row['id'].'">'.$row['title'].'<Br>';
	}
    echo '</form>';
	echo "</div></div><div id='show_user' class='show_pole' style='display:none;'>
	<form class='regforma' action='sys.php?op=adduser' method='post'>
	<h2>Вы решили добавить пользователя</h2>";
	$info = "";
	$result = $db->sql_query("SELECT `id`, `name` FROM ".$prefix."_mainpage where `name`!='config' and `type`='10'");
	$cnt = $db->sql_numrows($result);
	if ($cnt == 0) $info .= "Для регистрации необходимо создать группу.";
	else {
	    $info .= "<p>Email: <input class='regmail' type='email' name='em' value='' size=40 placeholder='Email' autofocus>";
	    if ($cnt > 1) {
	        $info .= "<p><select name='groups' class='groups'><option value='0'>Выберите группу</option>";
	        while ($row = $db->sql_fetchrow($result)) {
	        	$info .= "<option value='".$row['id']."'>".$row['name']."</option>";
	        }
	        $info .= "</select>";
	    }
	    $row = $db->sql_fetchrow($db->sql_query("SELECT `useit` FROM ".$prefix."_mainpage where `name`='config' and `type`='10'"));
	    if ($row['useit'] == 1) {
	        $info .= "<br>Выберите местоположение";
	        $soderganie .= include("includes/regions/meny.html");
	    }
	    $info .= '<p><button type="submit" class="medium green"><span class="mr-2 icon white medium" data-icon="c" style="display: inline-block; "></span>Добавить</button></form>'; 
	}
	echo $info.'</div></td></tr></table>';
}
////////////////////////////////////////////////////////
function add_group() {
	global $prefix, $db;
    $group=$_POST['group'];
    $html=$_POST['html'];
	$cat = "";
    $res = $db->sql_query("select `id` from ".$prefix."_mainpage where `type`='2' and `id`!='24'");
	while ($row = $db->sql_fetchrow($res)) {
		if ( isset($_POST['cat'.$row['id']]) ) $cat .= $row['id'].',';
	}
	$db->sql_query("INSERT INTO ".$prefix."_mainpage VALUES ( '', '10', '$group', '$html', '$cat', '0', '', '', '', '', '', '');") or die('Не удалось создать. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	Header("Location: sys.php?op=users");
}
////////////////////////////////////////////////////////
function save_users() {
	global $prefix, $db;
    $html=$_POST['html'];
	$htmlstr=$_POST['htmlstr'];
	$cat = $_POST['cat'];
	$db->sql_query("UPDATE ".$prefix."_mainpage SET `text`='$cat', `title`='$html', `useit`='$htmlstr' WHERE `type`='10' and `name`='config';") or die('Не удалось обновить. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	Header("Location: sys.php?op=users#recent");
}
////////////////////////////////////////////////////////
function del_group() {
	global $prefix, $db;
	$id= $_GET["id"];
	$id2= $_POST["group"];
	$result = $db->sql_query("select `user_id` from ".$prefix."_users where `user_group`='".$id."'");
	while ($row = $db->sql_fetchrow($result)) {
		$db->sql_query("UPDATE ".$prefix."_users SET `user_group`='".$id2."' WHERE `user_id`='".$row['user_id']."';") or die('Не удалось обновить. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	}
    $db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='".$id."'");
	Header("Location: sys.php?op=users");
}
////////////////////////////////////////////////////////
function del_group2() {
	global $prefix, $db;
	include("ad/ad-header.php");
	$result = $db->sql_query("select count(*) as `cnt` from ".$prefix."_mainpage where `type`='10' and `name`!='config'"); // заменить * на id
	$row = $db->sql_fetchrow($result);
	$a = $row['cnt'];
	if ($a == 1) { 
		echo 'Единственная группа не подлежит удалению';
	} else {
        echo 'Переместить всех пользователей из группы<form action="sys.php?op=del_group&amp;id='.$id.'" method="post">';   
		$result = $db->sql_query("select `name` from ".$prefix."_mainpage where `id`='".$id."'");
		while ($row = $db->sql_fetchrow($result)) {
			echo '<b>   '.$row['name'].'</b><br>в группу'; 
		}
		echo '<select name="group"><option value="0">Выберите группу</option>';
		$result = $db->sql_query("select `id`, `name` from ".$prefix."_mainpage where `id`!='".$id."' and `type`='10' and `name`!='config'");
		while ($row = $db->sql_fetchrow($result)) {
			echo '<option value="'.$row['id'].'">'.$row['name'].'</option>'; 
		}
		echo '</select><br><input type="submit" value="Удалить">
        </form>';
    }				
}
////////////////////////////////////////////////////////
function edit_group() {
	global $prefix, $db;
	include("ad/ad-header.php");
	echo '<form action="sys.php?op=s_group&amp;id='.$id.'"  method="post"><br/><p>'; 
	$result = $db->sql_query("select * from ".$prefix."_mainpage where `id`='".$id."'");
	while ($row = $db->sql_fetchrow($result)) {
		$html = $row['title'];
		echo 'Название группы: <input type="text" name="group" value="'.$row['name'].'"></p>
		<p>Дизайн группы: <select name="html"><option value="'.$html.'">';
		$res = $db->sql_query("select * from ".$prefix."_mainpage where `type`='0' and `id`='".$html."'");
		while ($row = $db->sql_fetchrow($res)) {
			echo $row['title'].'</option>';
		}
	}		   
	$res = $db->sql_query("select `id`, `title` from ".$prefix."_mainpage where `type`='0' and `id`!='".$html."'");
	while ($row = $db->sql_fetchrow($res)) {
		echo "<option value=\"".$row['id']."\">".$row['title']."</option>";
	}
    echo '</select></p><p>Группа имеет право добавлять материалы в разделы:</br>(Необходимо указать заново)</p>'; 
	$resl = $db->sql_query("select `id`, `title` from ".$prefix."_mainpage where `type`='2' and `id`!='24'");
	while ($row = $db->sql_fetchrow($resl)) {
		echo '<input type="checkbox" name="cat'.$row['id'].'" value="'.$row['id'].'">'.$row['title'].'<Br>';
	}
    echo '<Br><input type="submit" value="Изменить"></form>';
}
////////////////////////////////////////////////////////
function s_group() {
	global $prefix, $db;
	$id= $_GET["id"];
    $group=$_POST['group'];
    $html=$_POST['html'];
	$cat = "";
    $res = $db->sql_query("select `id` from ".$prefix."_mainpage where `type`='2' and `id`!='24'");
	while ($row = $db->sql_fetchrow($res)) {
		if ( isset($_POST['cat'.$row['id']]) ) $cat .= $row['id'].',';
	}
	$db->sql_query("UPDATE ".$prefix."_mainpage SET `name`='".$group."', `title`='".$html."', `text`='".$cat."' WHERE `id`='".$id."';") or die('Не удалось обновить. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	Header("Location: sys.php?op=users");
	
}
////////////////////////////////////////////////////////
function html_group() {
	global $prefix, $db;
	include("ad/ad-header.php");
	echo 'Здесь вы можете создать страницу пользователя с различными данными, которые он сможет в дальнейшем редактировать<Br>
	Для оформления страницы используйте заранее созданные поля [pole] <a class="help" href="sys.php?op=mainpage&name=spisok&type=4#1">создать поле</a><Br>Они же будут выполнять роль информационных блоков.<Br>
    <h2>Список доступных полей:<h2>';
	$a = "1,".$id;
	$sql = "select * from ".$prefix."_mainpage where `useit`='".$a."'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		echo '<b>'.$row['title'].'</b> ['.$row['name'].']<br>';
	}
	echo '<hr><form action="sys.php?op=s_html_group&id='.$id.'"  method="post">';
	$result2 = $db->sql_query("select `useit` from ".$prefix."_mainpage where `id`='".$id."'");
	$row = $db->sql_fetchrow($result2);
	$html = $row['useit'];
	echo "<textarea name=\"html\" rows=\"10\" cols=\"100\" style='width:100%;'>".$html."</textarea>";
	echo '<Br><input type="submit" value="Сохранить"></form>';
}
////////////////////////////////////////////////////////
function s_html_group() {
	global $prefix, $db;
	$id= $_GET["id"];
	$html = $_POST['html'];
	$db->sql_query("UPDATE ".$prefix."_mainpage SET `useit`='".$html."' WHERE `id`='".$id."';") or die('Не удалось обновить. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	Header("Location: sys.php?op=html_group&id=".$id);
}
////////////////////////////////////////////////////////
function adduser() {
	include("ad/ad-header.php");
	$regions = 0;
	global $prefix, $db, $sitename, $adminmail, $siteurl;
	require_once('page/functions_users.php');
	$email = $_POST['em']; 
	if (validate_email($email)) { 
		$query = "SELECT `user_id` FROM ".$prefix."_users WHERE email = '$email'"; 
		$result2 = $db->sql_query($query); 
		if ($result2 && $db->sql_fetchrow($result2) > 0) { 
			echo 'ОШИБКА - адрес электронной почты уже существует';
		} else {
			$hash = md5($email.$hash_padding);
			$result = $db->sql_query("SELECT `useit`, `text` FROM ".$prefix."_mainpage where `name`='config' and `type`='10'");
			while ($row = $db->sql_fetchrow($result)) { 
				if ($row['useit'] == 1) {
					$regions = $_POST['id_regions'];
				} 
				$simvol_pass = $row['text'];
			}
			$result = $db->sql_query("SELECT `id` FROM ".$prefix."_mainpage where `name`!='config' and `type`='10'"); $cnt = $db->sql_numrows($result);
			if ($cnt == 1) { 
				$row = $db->sql_fetchrow($result); 
				$group = $row['id'];
			} else { 
				$group = filter($_POST['groups'], "nohtml"); 
			}
			$password = generate_simvols($simvol_pass);
			$crypt_pwd = md5($password);
			$result = $db->sql_query("INSERT INTO ".$prefix."_users (user_id, user_group, name, photo,  password, email, remote_addr, confirm_hash, date_created, regions) VALUES ('', '$group', '', '', '$crypt_pwd', '$email', '', '$hash', NOW(), '$regions')");
			if (!$result) { 
				echo 'ОШИБКА - Ошибка базы данных'; 
			} else {
				$encoded_email = urlencode($_POST['em']); 
				$from = "Администратор";
				$mail_body = "Вас приглашают на ".$sitename.".<br>Запомните и никому не передавайте ваш пароль<br>Пароль: ".$password."<br>Теперь вы можете войти на сайт<br>".$siteurl;
				mail($email, "=?utf-8?b?" . base64_encode($sitename) . "?=", $mail_body, "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: =?utf-8?b?" . base64_encode($from) . "?= <" . $adminmail . ">");
				echo 'Пользователь '.$email.' добавленн, скоро он получит уведомление на почту';
			}
		}
	} else echo 'ОШИБКА: email неверный';
}
////////////////////////////////////////////////////////
	switch ($op) {
	    case "users":
		users();
	    break;
		
		case "add_group":
		add_group();
	    break;
		
		case "save_users":
		save_users();
	    break;
		
		case "del_group":
		del_group();
	    break;
		
		case "del_group2":
		del_group2();
	    break;
		 
		 case "edit_group":
		edit_group();
	    break;
		
		case "s_group":
		s_group();
	    break;
		
		case "s_html_group":
		s_html_group();
	    break;
		
		case "html_group":
		html_group();
	    break;
		
		case "adduser":
		adduser();
	    break;
	}
}
?>