<?php
	if (stristr(htmlentities($_SERVER['PHP_SELF']), "ipban.php")) {
		Header("Location: ../index.php");
		die();
	}

	global $prefix, $admin, $db, $http_siteurl, $admin;
if (is_admin($admin)) {
	// получаем список запрещенных админу страниц
	if (!is_array($admin)) {
	    $adm = base64_decode($admin);
	    $adm = addslashes($adm);
	    $adm = explode(":", $adm);
	  }
	  $aid = $adm[0];
	$sql = "select link from ".$prefix."_authors where aid='$aid'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$link = $row['link'];
	// получаем текущую страницу и запрещаем доступ
	if ($link == getenv("REQUEST_URI") and getenv("REQUEST_URI") != "") die('<h2 style="font-family:Calibri;">Эта страница запрещена к посещению Главным администратором.<br>Нажмите на клавиатуре &larr;Backspace.</h2>');
}
//////////////
	$ip = $_SERVER['REMOTE_ADDR'];
	$ip_all = explode(".", $ip);
	$ip_all = "$ip_all[0].$ip_all[1].$ip_all[2].*";
	
	$or = "";
	if (isset($_COOKIE["comment"])) {
	    $comment = $_COOKIE["comment"];
	    $comment = explode("|",$comment);
	    $ip2 = $comment[4];
	    if ($ip2 != "") {
	    	$ip_all2 = explode(".", $ip2);
			$ip_all2 = "$ip_all2[0].$ip_all2[1].$ip_all2[2].*";
			$or = " or ip_address='$ip' or ip_address='$ip_all'";
		}
	}

	if ($db->sql_numrows($db->sql_query("SELECT id FROM ".$prefix."_banned_ip WHERE ip_address='$ip' or ip_address='$ip_all'".$or)) != 0) {
	echo "<br><br><br><br><center><h1>До скорых встреч!</h1></center><!-- Ну и что ты здесь забыл? -->";
	die();
	}
?>