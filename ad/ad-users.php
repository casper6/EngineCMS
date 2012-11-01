<?php
	if (strpos($_SERVER['PHP_SELF'], 'sys.php') === false) { die ("Доступ закрыт!"); }
	$aid = trim($aid);
	global $prefix, $db, $red;
	$sql = "select realadmin from ".$prefix."_authors where aid='$aid'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$realadmin = $row['realadmin'];
	if ($realadmin==1) {

######################################################################################################
function users() {
	global $prefix, $db;
	include("ad-header.php");
	echo '  <ul class="tabs left">
                <li><a href="#popular">Группы пользователей</a></li><li><a href="#recent">Настройка регистрации</a></li><li><a href="#add">Добавить группу</a></li><li><a href="#user">Пользователи</a></li>
            </ul>
			<div id="popular" class="tab-content">
                ';
				$result = $db->sql_query("select * from ".$prefix."_mainpage where `type`='10' and `name`!='config'");
				while ($row = $db->sql_fetchrow($result)) {
				$html = $row['title'];
				$ids = explode(",",$row['text']);
				echo'
				<p><b>'.$row['name'].'</b>
				<a class="help" href="sys.php?op=edit_group&amp;id='.$row['id'].'">Настроить</a>
				<a class="help" href="sys.php?op=html_group&amp;id='.$row['id'].'">Страница пользователя</a>
				<a class="help" href="sys.php?op=del_group2&amp;id='.$row['id'].'">Удалить</a></p>
				<h2>Использует дизайн:   '; 
				$res = $db->sql_query("select * from ".$prefix."_mainpage where `id`='".$html."'");
				while ($row = $db->sql_fetchrow($res)) {
				echo $row['title']."</h2>";
				}
				echo "<h2>Можно добавлять материалы в разделы:</h2>";
				foreach($ids as $n => $v)
               {
				$res = $db->sql_query("select * from ".$prefix."_mainpage where `id`='".$v."'");
				while ($row = $db->sql_fetchrow($res)) {
				echo $row['title']."<br>";
				}
				 }
				}
				echo '
            </div>
            <div id="recent" class="tab-content">
			  ';
			  $result2 = $db->sql_query("select * from ".$prefix."_mainpage where `type`='10' and `name`='config'");
				while ($row = $db->sql_fetchrow($result2)) {
				$cat = $row['text'];
				$id = intval($row['title']);
				$htmlstr = $row['useit'];
				echo '
				<form action="sys.php?op=save_users"  method="post">
			   <p>Дизайн для модуля<select name="html">';
			   $res = $db->sql_query("select * from ".$prefix."_mainpage where `id`='".$id."'");
				while ($row = $db->sql_fetchrow($res)) {
			   echo '<option value="'.$row['id'].'">'.$row['title'].'</option>'; 
			   }
			   $res2 = $db->sql_query("select * from ".$prefix."_mainpage where `type`='0' and `id`!='".$id."'");
				while ($row = $db->sql_fetchrow($res2)) {
				echo "<option value=\"".$row['id']."\">".$row['title']."</option>";
				}
        echo '</select><Br>Минимальная длинна пароля<input type="text" name="cat" value="'.$cat.'">';
					$result = $db->sql_query("SELECT count(*) as cnt FROM ".$prefix."_regions");
while ($row = $db->sql_fetchrow($result)) { 
if ($row['cnt'] > 0){
		echo '<Br><p>Использовать регионы?
		<BR>ВЫ используете следующую структуру регионов:<h5>';
include("includes/regions/config.php");
if ($stryktyra ==1){echo 'область-район-город';}
if ($stryktyra ==2){echo 'область-город';}
if ($stryktyra ==3){echo 'область-район';}
if ($stryktyra ==4){echo 'район-город';}
if ($stryktyra ==5){echo 'только области';}
if ($stryktyra ==6){echo 'только районы';}
if ($stryktyra ==7){echo 'только города';}
echo '</h5><select name="htmlstr">';
		if ($htmlstr == 0){
		echo "<option value=\"0\">Нет</option><option value=\"1\">Да</option>";
		}
		if ($htmlstr == 1){
		echo "<option value=\"1\">Да</option><option value=\"0\">Нет</option>";
		}
		echo '</select><Br>Если да то выбор региона будет обязателен для пользователя при регистрации';
		}}echo '
		<Br><input type="submit" value="Сохранить">
        </form>
				'; 
				}
				echo '
            </div>
			 <div id="add" class="tab-content">
			   
			   <form action="sys.php?op=add_group"  method="post"><br/><p><input type="text" name="group" value="Имя группы"></p>
			   <p><select name="html"><option value="0">Выберите дизайн для группы</option>'; 
			   $res3 = $db->sql_query("select * from ".$prefix."_mainpage where `type`='0'");
				while ($row = $db->sql_fetchrow($res3)) {
				echo "<option value=\"".$row['id']."\">".$row['title']."</option>";
				}
        echo '</select></p><p>Группа имеет право добавлять материалы в разделы:</p>'; 
		$resl = $db->sql_query("select * from ".$prefix."_mainpage where `type`='2' and `id`!='24'");
				while ($row = $db->sql_fetchrow($resl)) {
				$a  = $row['id'];
		echo '<input type="checkbox" name="cat'.$a.'" value="'.$row['id'].'">'.$row['title'].'<Br>';
		}
        echo '<Br><input type="submit" value="Добавить">
        </form>
				</ul>
            </div><div id="user" class="tab-content">
			   <ul><center>Найти пользователя<br>
				<form action="sys.php?op=poiskuser"  method="post"><br/><p><input type="text" name="name" value="Кого ищем?"></p>Как ищем?
			   <p><select name="vibor"><option value="1">По имени</option><option value="1">По email</option></select></p><Br><input type="submit" value="Найти"></form>
				<Br><Br>Добавить пользователя<Br>
				<form action="sys.php?op=adduser"  method="post"><br/><p><input type="text" name="name" value="Имя пользователя"></p><p><input type="text" name="email" value="email"></p>В группу
				<p><select name="group">';
				$result = $db->sql_query("select * from ".$prefix."_mainpage where `name`!='config' and `type`='10'");
				while ($row = $db->sql_fetchrow($result)) {
				echo '<option value="'.$row['id'].'">'.$row['name'].'</option>'; 
				}
				echo '</select></p><Br><input type="submit" value="Добавить"></form></center></ul></div>';
}
function add_group() {
	global $prefix, $db;
     $group=$_POST['group'];
     $html=$_POST['html'];
	 $cat = "";
        $res = $db->sql_query("select * from ".$prefix."_mainpage where `type`='2' and `id`!='24'");
				while ($row = $db->sql_fetchrow($res)) {
			if(isset($_POST['cat'.$row['id']])){$cat .= $row['id'].',';}
		}
	 $db->sql_query("INSERT INTO ".$prefix."_mainpage VALUES ( '', '10', '$group', '$html', '$cat', '0', '', '', '', '', '', '');") or die('Не удалось создать. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	Header("Location: sys.php?op=users");
	
}
function save_users() {
	global $prefix, $db;
     $html=$_POST['html'];
	 $htmlstr=$_POST['htmlstr'];
	 $cat = $_POST['cat'];
	 $db->sql_query("UPDATE ".$prefix."_mainpage SET `text`='$cat', `title`='$html', `useit`='$htmlstr' WHERE `type`='10' and `name`='config';") or die('Не удалось обновить. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	Header("Location: sys.php?op=users#recent");
	
}
function del_group() {
	global $prefix, $db;
	$id= $_GET["id"];
	$id2= $_POST["group"];
	$result = $db->sql_query("select * from ".$prefix."_users where `user_group`='".$id."'");
				while ($row = $db->sql_fetchrow($result)) {
				$db->sql_query("UPDATE ".$prefix."_users SET `user_group`='$id2' WHERE `user_id`='".$row['user_id']."';") or die('Не удалось обновить. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
				}
            $db->sql_query("DELETE FROM ".$prefix."_mainpage WHERE id='$id'");
	        Header("Location: sys.php?op=users");
	
}
function del_group2() {
	global $prefix, $db;
	include("ad-header.php");
         echo 'Переместить всех пользователей из группы<form action="sys.php?op=del_group&amp;id='.$id.'"  method="post">';   
$result = $db->sql_query("select * from ".$prefix."_mainpage where `id`='".$id."'");
				while ($row = $db->sql_fetchrow($result)) {
				echo '<b>   '.$row['name'].'</b><br>в группу'; 
				}
				echo '<select name="group"><option value="0">Выберите группу</option>';
$result = $db->sql_query("select * from ".$prefix."_mainpage where `id`!='".$id."' and `type`='10'");
				while ($row = $db->sql_fetchrow($result)) {
				echo '<option value="'.$row['id'].'">'.$row['name'].'</option>'; 
				}
echo '</select><Br><input type="submit" value="Удалить">
        </form>'; 				
}
function edit_group() {
	global $prefix, $db;
	 include("ad-header.php");
	 echo '<form action="sys.php?op=s_group&amp;id='.$id.'"  method="post"><br/><p>'; 
	 $result = $db->sql_query("select * from ".$prefix."_mainpage where `id`='".$id."'");
				while ($row = $db->sql_fetchrow($result)) {
				$html=$row['title'];
	 echo 'Название группы: <input type="text" name="group" value="'.$row['name'].'"></p>
	 <p>Дизайн группы: <select name="html"><option value="'.$html.'">';
	 $res = $db->sql_query("select * from ".$prefix."_mainpage where `type`='0' and `id`='".$html."'");
				while ($row = $db->sql_fetchrow($res)) {
				echo $row['title'].'</option>';
				}
	 }
			   
			   $res = $db->sql_query("select * from ".$prefix."_mainpage where `type`='0' and `id`!='".$html."'");
				while ($row = $db->sql_fetchrow($res)) {
				echo "<option value=\"".$row['id']."\">".$row['title']."</option>";
				}
        echo '</select></p><p>Группа имеет право добавлять материалы в разделы:</br>(Необходимо указать заново)</p>'; 
		$resl = $db->sql_query("select * from ".$prefix."_mainpage where `type`='2' and `id`!='24'");
				while ($row = $db->sql_fetchrow($resl)) {
				$a  = $row['id'];
		echo '<input type="checkbox" name="cat'.$a.'" value="'.$row['id'].'">'.$row['title'].'<Br>';
		}
        echo '<Br><input type="submit" value="Изменить">
        </form>';
	
}
function s_group() {
	global $prefix, $db;
	 $id= $_GET["id"];
     $group=$_POST['group'];
     $html=$_POST['html'];
	 $cat = "";
        $res = $db->sql_query("select * from ".$prefix."_mainpage where `type`='2' and `id`!='24'");
				while ($row = $db->sql_fetchrow($res)) {
			if(isset($_POST['cat'.$row['id']])){$cat .= $row['id'].',';}
		}
	 $db->sql_query("UPDATE ".$prefix."_mainpage SET `name`='$group', `title`='$html', `text`='$cat' WHERE `id`='$id';") or die('Не удалось обновить. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	Header("Location: sys.php?op=users");
	
}
function html_group() {
	global $prefix, $db;
	include("ad-header.php");
	echo 'Здесь вы можете создать страницу пользователя с различными данными, которые он сможет в дальнейшем редактировать<Br>
	Для оформления страницы используйте заранее созданные поля [pole] <a class="help" href="sys.php?op=mainpage&name=spisok&type=4#1">создать поле</a><Br>
	Они же будут выполнять роль информационных блоков.<Br>
    <h2>Список доступных полей:<h2>';
	$a = "1,".$id;
	$sql = "select * from ".$prefix."_mainpage where `useit`='".$a."'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
	echo '<b>'.$row['title'].'</b>   ['.$row['name'].']<br>';
	}
	echo '<hr><form action="sys.php?op=s_html_group&id='.$id.'"  method="post">';
	$result2 = $db->sql_query("select * from ".$prefix."_mainpage where `id`='".$id."'");
				while ($row = $db->sql_fetchrow($result2)) {
				echo "<textarea name=\"html\" rows=\"10\" cols=\"100\" style='width:100%;'>".$row['htmlstr']."</textarea>";
				}
	echo '<Br><input type="submit" value="Сохранить">
        </form>';
}
function s_html_group() {
	global $prefix, $db;
	$id= $_GET["id"];
	$html = $_POST['html'];
	 $db->sql_query("UPDATE ".$prefix."_mainpage SET `useit`='$html' WHERE `id`='$id';") or die('Не удалось обновить. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	Header("Location: sys.php?op=html_group&id=".$id);
	
}
function adduser() {
	global $prefix, $db;
	$name = $_POST['name'];
	$email = $_POST['email'];
	$supersecret_hash_padding = 'hvgjkfdhjfdhuhbutbj'; 
	 // В БД не должно найтись совпадений, ни логину не email. 
      $query = "SELECT user_id  FROM user WHERE user_name = '$user_name' OR email = '$email'"; 
      $result = mysql_query($query); 
      if ($result && mysql_num_rows($result) > 0) { 
        $feedback = 'ОШИБКА - Имя пользователя или адрес электронной почты уже существует'; 
        return $feedback; 
      } else { 
    $first_name = $_POST['first_name']; 
    $last_name = $_POST['last_name']; 
        $password = md5($_POST['password1']); 
    $user_ip = $_SERVER['REMOTE_ADDR']; 
        // Создайте новый хэш для вставки в БД и подтверждение по электронной почте 
        $hash = md5($email.$supersecret_hash_padding); 

        $query = "INSERT INTO user (user_id, user_name, first_name, last_name,  
password, email, remote_addr, confirm_hash, is_confirmed, date_created) 
                  VALUES ('', '$user_name', '$first_name', '$last_name',  
'$password', '$email', '$user_ip', '$hash', 0, NOW())"; 
        $result = mysql_query($query); 
        if (!$result) { 
          $feedback = 'ОШИБКА - Ошибка базы данных'; 
          $feedback .= mysql_error(); 
          return $feedback; 
        } else { 
          // Отправить подтверждение по электронной почте 
          $encoded_email = urlencode($_POST['email']); 
          $mail_body = 'EOMAILBODY 
Спасибо за регистрацию на LPHP.RU. Щелкните по этой ссылке для подтверждения регистрации:
http:??localhost/confirm.php?hash=$hash&email=$encoded_email 
Как только вы увидите подтверждающее сообщение, вы будете зарегистрированы в LPHP.RU 
EOMAILBODY'; 
          mail ($email, 'LPHP.RU registration Confirmation', $mail_body, 
 'From: noreply@example.com'); 

      
          $feedback = 'Вы успешно зарегистрировались. 
 Вы вскоре получите подтверждение по электронной почте'; 
          return $feedback; 
        } 
      } 
}

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
