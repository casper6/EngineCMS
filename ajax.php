<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
require_once("mainfile.php");
global $soderganie, $tip, $DBName, $prefix, $db, $module_name, $ModuleName, $admin;
if (isset($_REQUEST['func']))   $func = $_REQUEST['func']; else die(); 
if (isset($_REQUEST['type']))   $type = $_REQUEST['type']; else $type = 0;
if (isset($_REQUEST['id']))     $id = intval($_REQUEST['id']); else $id = 0;
if (isset($_REQUEST['string'])) $string = $_REQUEST['string'];
///////////////////////////////////////////////////////////
if ($func == "registration_form") {
	$info = "";
	$result = $db->sql_query("SELECT `id`, `name` FROM ".$prefix."_mainpage where `name`!='config' and `type`='10'");
	$cnt = $db->sql_numrows($result);
	if ($cnt == 0) $info .= "Для регистрации необходимо создать группу.";
	else {
	    // Вывести форму на экран 
	    $info .= "<form class='regforma' action='--register' method='post'> 
	    <input class='regname' type='text' name='na' value='' placeholder='Имя или ник'>
	    <br><input class='regpass' type='password' name='pa' value='' placeholder='Пароль'>
	    <br><input class='regmail' type='email' name='em' value='' placeholder='Email'>";
	    if ($cnt > 1) {
	        $info .= "<br><select name='groups' class='groups'><option value='0'>Выберите группу</option>";
	        while ($row = $db->sql_fetchrow($result)) {
	          $info .= "<option value='".$row['id']."'>".$row['name']."</option>";
	        }
	        $info .= "</select>";
	    }
	    $row = $db->sql_fetchrow($db->sql_query("SELECT `useit` FROM ".$prefix."_mainpage where `name`='config' and `type`='10'"));
	    if ($row['useit'] == 1) {
	        $info .= "<br>Выберите местоположение";
	        //$soderganie .= include("includes/regions/meny.html");
	    }
	    $info .= "<br><input type='submit' name='submit' value='Зарегистрироваться'></form>"; 
	}
	echo $info; exit();
}
///////////////////////////////////////////////////////////
if ($func == "savegolos") { // Сохраняем голосование
	$info = "";
	if (isset($golos_id) and isset($GLOBALS[$golos_id])) $tmp = $GLOBALS[$golos_id]; else $tmp = ""; // поставлено от голосования
	list($name, $gol) = explode("*@%", $string);
		//$bangolosdays = 30;
		$type = intval($type);
		$ip = getenv("REMOTE_ADDR"); // IP
		if ($gol == 6 and is_admin($admin)) {
			$db->sql_query("UPDATE ".$prefix."_pages SET golos='0' WHERE pid='$id';");
			$db->sql_query("DELETE from ".$prefix."_pages_golos WHERE num='$id';");
			$info = "Голоса удалены.";
			echo $info;	exit;
		}
		if ($gol>5) $gol=1; 
		if ($gol<0) $gol=1;
		if ($type != 2 and $type != 3) $gol = intval($gol);
		$dat = date("Y.m.d H:i:s");
		$golos_id = $prefix.'golos'.$id;

		if ($type == 0) { if ($gol != 1 and $gol != 2 and $gol != 3 and $gol != 4 and $gol != 5 ) $gol = 5; }
		if ($type == 1) { $gol = 1; }
		if ($type == 2 or $type == 3) { if ($gol != 1) $gol = -1; }
		if ($type != 0) { 
				$sql = "SELECT golos FROM ".$prefix."_pages where pid='$id'";
				$row2 = $db->sql_fetchrow($db->sql_query($sql));
				$resnum = $db->sql_query($sql);
				$numrows = $db->sql_numrows($resnum);
					if (($numrows > 0 and $tmp == $golos_id) or $id==0) {
						$info = "<b class=red>Вы уже голосовали!</b>";
					} else {
						$golos = $row2['golos'] + $gol;
						$db->sql_query("UPDATE ".$prefix."_pages SET golos='".$golos."' WHERE pid='$id';");
						$db->sql_query("INSERT INTO ".$prefix."_pages_golos (`gid`, `ip`, `golos`, `num`, `data`) VALUES ('', '$ip', '$gol', '$id', '$dat')");
						setcookie ("$golos_id", "$golos_id",time()+2678400,"/");
					}
		} else {
					$sql = "SELECT data FROM ".$prefix."_pages_golos WHERE ip='$ip' AND num='$id'";
					$resnum = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$date = $row['data'];
					$date2 = dateresize($date);
					$date = dateresize($dat);
					$numrows = $db->sql_numrows($resnum);
					if ($numrows > 0 or $tmp==$golos_id or $id==0) {
						$info = "<b class=red>Вы уже голосовали.</b>";
					} else {
						$db->sql_query("INSERT INTO ".$prefix."_pages_golos (`gid`, `ip`, `golos`, `num`, `data`) VALUES ('', '$ip', '$gol', '$id', '$dat')");
						setcookie ("$golos_id", "$golos_id",time()+2678400,"/");
					}
		}
		$sqlX = "SELECT module from ".$prefix."_pages where pid = '$id'";
		$resultX = $db->sql_query($sqlX);
		$rowX = $db->sql_fetchrow($resultX);
		$mod = $rowX['module'];
		recash("/-".$mod."_page_".$id); // Обновление кеша
		if ($type != 0) {
			$sql2 = "SELECT golos FROM ".$prefix."_pages where pid='$id'";
			$result2 = $db->sql_query($sql2);
			$row2 = $db->sql_fetchrow($result2);
			$golos = $row2['golos'];
		}
		if ($type == 1) 
			$info .= " <b class=green>Спасибо за ваше неравнодушие!</b>";
		if ($type == 2 or $type == 3) {
			$sql23 = "SELECT golos FROM ".$prefix."_pages_golos WHERE num='$id'";
			$result23 = $db->sql_query($sql23);
			$numrows23 = $db->sql_numrows($result23);
			$info .= " <b class=green>Спасибо за ваше неравнодушие!</b>";
		}
		if ($type == 0) {
			$sql2 = "SELECT golos FROM ".$prefix."_pages_golos WHERE num='$id'";
			$result2 = $db->sql_query($sql2);
			$numrows2 = $db->sql_numrows($result2);
			$golo=array();
			while($row2 = $db->sql_fetchrow($result2)) {
				$golo[] = $row2['golos'];
			}
			$proc = array_sum($golo)/$numrows2*10;
			$sersv = $proc*2;
			$sersv2 = number_format($proc,2)/10;
			$sersv1 = number_format($sersv2,2);
			$sersv = 90*$sersv/100;
			$info .= " <b class=green>Спасибо за ваше неравнодушие!</b>";
		}
	echo $info; exit();
}

?>


