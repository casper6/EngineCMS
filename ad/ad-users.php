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
                <li><a href="#popular">Группы пользователей</a></li><li><a href="#recent">Настройка регистрации</a></li><li><a href="#add">Добавить группу</a></li><li><a href="#obl">Регионы</a></li>
            </ul>
			<div id="popular" class="tab-content">
                ';
				$result = $db->sql_query("select * from ".$prefix."_users_group where `group`!='rigion' and `group`!='config' and `group`!='obl'");
				while ($row = $db->sql_fetchrow($result)) {
				$html = $row['html'];
				$ids = explode(",",$row['cat']);
				echo'
				<p><b>'.$row['group'].'</b>
				<a class="help" href="sys.php?op=edit_group&amp;id='.$row['id'].'">Настроить</a>
				<a class="help" href="sys.php?op=html_group&amp;id='.$row['id'].'">Страница пользователя</a>
				<a class="help" href="sys.php?op=blok_group&amp;id='.$row['id'].'">Блок пользователя</a>
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
			  $result2 = $db->sql_query("select * from ".$prefix."_users_group where `group`='config'");
				while ($row = $db->sql_fetchrow($result2)) {
				$cat = $row['cat'];
				$id = $row['html'];
				$htmlstr = $row['htmlstr'];
				echo '
				<form action="sys.php?op=save_users"  method="post">
			   <p>Дизайн для модуля<select name="html">';
			   $res = $db->sql_query("select * from ".$prefix."_mainpage where `id`='".$id."'");
				while ($row = $db->sql_fetchrow($res)) {
			   echo '<option value="'.$row['id'].'">'.$row['title'].'</option>'; 
			   }
			   $res2 = $db->sql_query("select * from ".$prefix."_mainpage where `type`='0' and `id`!='".$row['html']."");
				while ($row = $db->sql_fetchrow($res2)) {
				echo "<option value=\"".$row['id']."\">".$row['title']."</option>";
				}
        echo '</select><Br>Минимальная длинна пароля<input type="text" name="cat" value="'.$cat.'">
		<Br><p>Использовать регионы<select name="htmlstr">';
		if ($htmlstr == 0){
		echo "<option value=\"0\">Нет</option><option value=\"1\">Да</option>";
		}
		if ($htmlstr == 1){
		echo "<option value=\"1\">Да</option><option value=\"0\">Нет</option>";
		}
		echo '</select><Br>Если да то выбор региона будет обязателен для пользователя при регистрации<Br><input type="submit" value="Сохранить">
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
            </div><div id="obl" class="tab-content">
			   <ul>'; 
        echo '<form action="sys.php?op=add_obl"  method="post"><br/><p><input type="text" name="obl" value="Новая область">
		<input type="submit" value="Добавить"></p>
        </form><h2>Список областей</h2>';  
		$result3 = $db->sql_query("select * from ".$prefix."_users_group where `group`='obl'");
				while ($row = $db->sql_fetchrow($result3)) {
				$html = $row['html'];
				echo'<p><b>'.$row['html'].'</b>
				<a class="help" href="sys.php?op=raion&amp;id='.$row['id'].'">Смотреть районы</a>
				<a class="help" href="sys.php?op=del_obl&amp;id='.$row['id'].'">Удалить область</a><br>'; 
				}
				echo '
            </div>';              
	
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
	 $db->sql_query("INSERT INTO ".$prefix."_users_group (`id`, `group`, `html`, `cat`, `htmlstr`) VALUES (NULL, '".$group."', '".$html."', '".$cat."', '');") or die('Не удалось создать. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	Header("Location: sys.php?op=users");
	
}
function save_users() {
	global $prefix, $db;
     $html=$_POST['html'];
	 $htmlstr=$_POST['htmlstr'];
	 $cat = $_POST['cat'];
	 $db->sql_query("UPDATE ".$prefix."_users_group SET `cat`='$cat', `html`='$html', `htmlstr`='$htmlstr' WHERE `id`='1';") or die('Не удалось обновить. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
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
            $db->sql_query("DELETE FROM ".$prefix."_users_group WHERE id='$id'");
	        Header("Location: sys.php?op=users");
	
}
function del_group2() {
	global $prefix, $db;
	include("ad-header.php");
         echo 'Переместить всех пользователей из группы<form action="sys.php?op=del_group&amp;id='.$id.'"  method="post">';   
$result = $db->sql_query("select * from ".$prefix."_users_group where `id`='".$id."'");
				while ($row = $db->sql_fetchrow($result)) {
				echo '<b>   '.$row['group'].'</b><br>в группу'; 
				}
				echo '<select name="group"><option value="0">Выберите группу</option>';
$result = $db->sql_query("select * from ".$prefix."_users_group where `group`!='rigion' and `group`!='config' and `group`!='obl' and `id`!='".$id."'");
				while ($row = $db->sql_fetchrow($result)) {
				echo '<option value="'.$row['id'].'">'.$row['group'].'</option>'; 
				}
echo '</select><Br><input type="submit" value="Удалить">
        </form>'; 				
}
function edit_group() {
	global $prefix, $db;
	 include("ad-header.php");
	 echo '<form action="sys.php?op=s_group&amp;id='.$id.'"  method="post"><br/><p>'; 
	 $result = $db->sql_query("select * from ".$prefix."_users_group where `id`='".$id."'");
				while ($row = $db->sql_fetchrow($result)) {
				$html=$row['html'];
	 echo 'Название группы: <input type="text" name="group" value="'.$row['group'].'"></p>
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
	 $db->sql_query("UPDATE ".$prefix."_users_group SET `group`='$group', `html`='$html', `cat`='$cat' WHERE `id`='$id';") or die('Не удалось обновить. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
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
	$result2 = $db->sql_query("select * from ".$prefix."_users_group where `id`='".$id."'");
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
	 $db->sql_query("UPDATE ".$prefix."_users_group SET `htmlstr`='$html' WHERE `id`='$id';") or die('Не удалось обновить. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	Header("Location: sys.php?op=html_group&id=".$id);
	
}
function blok_group() {
	global $prefix, $db;
	include("ad-header.php");
	echo '<h2>В разработке<h2>';
	
}
function del_obl() {
	global $prefix, $db;
	$id= $_GET["id"];
            $db->sql_query("DELETE FROM ".$prefix."_users_group WHERE id='$id'");
			$db->sql_query("DELETE FROM ".$prefix."_users_group WHERE cat='$id'");
	        Header("Location: sys.php?op=users#obl");
	
}
function add_obl() {
	global $prefix, $db;
	 $obl=$_POST['obl'];
     $db->sql_query("INSERT INTO ".$prefix."_users_group (`id`, `group`, `html`, `cat`, `htmlstr`) VALUES (NULL, 'obl', '".$obl."', '', '');") or die('Не удалось создать. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	        Header("Location: sys.php?op=users#obl");
	
}
function del_raion() {
	global $prefix, $db;
	$id= $_GET["id"];
            $db->sql_query("DELETE FROM ".$prefix."_users_group WHERE id='$id'");
	        Header("Location: sys.php?op=raion");
	
}
function raion() {
	global $prefix, $db;
	include("ad-header.php");
	$id= $_GET["id"];
     echo '<form action="sys.php?op=add_raion&amp;id='.$id.'"  method="post"><br/><p><input type="text" name="raion" value="Новый район">
		<input type="submit" value="Добавить район"></p>
        </form><h2>Список районов</h2>';  
		$result3 = $db->sql_query("select * from ".$prefix."_users_group where `cat`='".$id."' and `group`='rigion'");
				while ($row = $db->sql_fetchrow($result3)) {
				$html = $row['html'];
				echo'<p><b>'.$row['html'].'</b>
				<a class="help" href="sys.php?op=del_raion&amp;id='.$row['id'].'">Удалить район</a><br>'; 
				}
	
}
function add_raion() {
	global $prefix, $db;
	 $raion=$_POST['raion'];
	 $id= $_GET["id"];
     $db->sql_query("INSERT INTO ".$prefix."_users_group (`id`, `group`, `html`, `cat`, `htmlstr`) VALUES (NULL, 'rigion', '".$raion."', '".$id."', '');") or die('Не удалось создать. Попробуйте еще раз и в случае неудачи обратитесь к разработчику.');
	        Header("Location: sys.php?op=raion&id=".$id);
	
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
		
		case "blok_group":
		blok_group();
	    break;
		
		case "add_obl":
		add_obl();
	    break;
		
		case "del_obl":
		del_obl();
	    break;
		
		case "add_raion":
		add_raion();
	    break;
		
		case "del_raion":
		del_raion();
	    break;
		
		case "raion":
		raion();
	    break;
	}
}
?>
