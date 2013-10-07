<?php
# ДвижОк CMS: Резервная копия
# (c) 2006-2013 by Merkushev Vladimir
# Основано на Database Backup System (c) 2001 by Thomas Rudant (thomas.rudant@grunk.net) http://www.grunk.net http://www.securite-internet.org
ini_set('memory_limit', '64M');
if (!defined('ADMIN_FILE')) {
	die ("Доступ закрыт!");
}
global $prefix, $db, $admin_file, $siteurl, $cash;
if ($cash != 1) $cash = 0;
$aid = substr("$aid", 0,25);
$row = $db->sql_fetchrow($db->sql_query("SELECT realadmin FROM " . $prefix . "_authors WHERE aid='$aid'"));
if ($row['realadmin'] == 1) {
	$url = str_replace(".","_",str_replace("/","",str_replace(".ru","",str_replace("www.","",str_replace("http://","",$siteurl)))));
	switch($op) {
		case "backup":
		@set_time_limit(600);
			$crlf = PHP_EOL; // "\n";
			$strNoTablesFound = "В базе данных не найдено таблиц.";
			$strHost = "Хост";
			$strDatabase = "База данных ";
			$strTableStructure = "Табличная структура";
			$strDumpingData = "Выгружаемая информация таблицы";
			$strError = "Ошибка";
			$strSQLQuery = "SQL-запрос";
			$strMySQLSaid = "MySQL говорит: ";
			$strBack = "Назад";
			$strName = "База данных";
			$strDone = "Дата:";
			$strat = "Время:";
			$date_jour = str_replace(" ", "_", date2normal_view(date ("Y-m-d"),0,0,1));
			header( 'Content-Type: text/html; charset=utf-8' ); 
			$gzip_contents = "";
		// мы делаем особенную DOS-CRLF магию...
		//$client = $_SERVER["HTTP_USER_AGENT"];
		//if(ereg('[^(]*\((.*)\)[^)]*',$client,$regs)) {
		//	if (strpos($regs[1], 'Win') == true)
		//		$crlf = "\r\n";
		//}
		function my_handler($sql_insert) {
			global $crlf, $gzip_contents;
			$gzip_contents .= $sql_insert.";\");".$crlf;
		}
		function get_table_content($db, $table, $handler) {
		global $prefix;
		$table_name = str_replace($prefix."_","\".\$".'prefix."_',$table);
		
			$result = mysql_db_query($db, "SELECT * FROM $table") or mysql_die();
			$i = 0;
			while($row = mysql_fetch_row($result)) {
				$table_list = "(";
				for($j=0; $j<mysql_num_fields($result);$j++)
				$table_list .= mysql_field_name($result,$j).", ";
				$table_list = substr($table_list,0,-2);
				$table_list .= ")";
				
			if(isset($GLOBALS["showcolumns"])) $schema_insert = "\$db->sql_query(\"INSERT INTO `".$table_name."` ".$table_list." VALUES (";
			else $schema_insert = "\$db->sql_query(\"INSERT INTO `".$table_name."` VALUES (";
				for($j=0; $j<mysql_num_fields($result);$j++) {
					if(!isset($row[$j]))
					$schema_insert .= " NULL,";
					elseif($row[$j] != "")
					$schema_insert .= " '".addslashes($row[$j])."',";
					else
					$schema_insert .= " '',";
				}

				$schema_insert = ereg_replace(",$", "", $schema_insert);
				$schema_insert .= ")";

				$handler(trim($schema_insert));
				$i++;
			}
			return (true);
		}
		function get_table_def($db, $table, $crlf) {
		global $prefix;
		$table_name = str_replace($prefix,"\".\$".'prefix."',$table);
		
			$schema_create = "";
			$schema_create .= "\$db->sql_query(\"DROP TABLE IF EXISTS `".$table_name."`;\");".$crlf;
			$schema_create .= "\$db->sql_query(\"CREATE TABLE `".$table_name."` (".$crlf;
			$result = mysql_db_query($db, "SHOW FIELDS FROM `".$table."`") or mysql_die();
			while($row = mysql_fetch_array($result)) {
				$schema_create .= "   `".$row["Field"]."` ".$row["Type"];
				if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
				$schema_create .= " DEFAULT '".$row["Default"]."'";
				if($row["Null"] != "YES")
				$schema_create .= " NOT NULL";
				if($row["Extra"] != "")
				$schema_create .= " ".$row["Extra"];
				$schema_create .= ",".$crlf;
			}
			$schema_create = ereg_replace(",".$crlf."$", "", $schema_create);
			$result = mysql_db_query($db, "SHOW KEYS FROM $table") or mysql_die();
			while($row = mysql_fetch_array($result)) {
				$kname=$row['Key_name'];
				if(($kname != "PRIMARY") && ($row['Non_unique'] == 0))
				$kname="UNIQUE|".$kname;
				if(!isset($index[$kname]))
				$index[$kname] = array();
				$index[$kname][] = $row['Column_name'];
			}
			while(list($x, $columns) = @each($index)) {
				$schema_create .= ",".$crlf;
				if($x == "PRIMARY")
				$schema_create .= "   PRIMARY KEY (`" . implode($columns, "`, `") . "`)";
				elseif (substr($x,0,6) == "UNIQUE")
				$schema_create .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
				else
				$schema_create .= "   KEY ".$x." (`" . implode($columns, "`, `") . "`)";
			}
			$schema_create .= $crlf.")";
			return (stripslashes($schema_create));
		}
		function mysql_die($error = "") {
			global $gzip_contents;
			$gzip_contents .= "<b> $strError </b><p>";
			if(isset($sql_query) && !empty($sql_query)) {
				$gzip_contents .= $strSQLQuery.": <pre>".$sql_query."</pre><p>";
			}
			if(empty($error))
			$gzip_contents .= $strMySQLSaid.mysql_error();
			else
			$gzip_contents .= $strMySQLSaid.$error;
			$gzip_contents .= "<br><a href=\"javascript:history.go(-1)\">".$strBack."</a>";
			exit;
		}
		global $dbhost, $dbuname, $dbpass, $dbname;
		$con = mysqli_connect($dbhost, $dbuname, $dbpass);

		// Необходимая вставка для UTF-8 кодировки
		$con->query("SET NAMES 'utf8'");
		$con->set_charset('utf8');

		@mysql_select_db("$dbname") or die ("Ошибка: Не могу выбрать базу данных");
		//$tables = mysql_list_tables($dbname);
		
		// замена функции
		$sql = "SHOW TABLES FROM ".$dbname;
		$tables = mysql_query($sql) or die ("Ошибка: Не могу получить базу данных");

		$num_tables = @mysql_numrows($tables);
		if($num_tables == 0) {
			$gzip_contents .= $strNoTablesFound;
		} else {
			$i = 0;
			$heure_jour = date ("H:i");
			$gzip_contents .= "<?php
			$crlf
			require_once(\"mainfile.php\");$crlf
			global \$prefix;$crlf
			";
			while($i < $num_tables) {
				$table = mysql_tablename($tables, $i);
				if (strpos(" ".$table,$prefix)) {
				$gzip_contents .= $crlf;
					if ($cash==0) {
						$gzip_contents .= get_table_def($dbname, $table, $crlf).";\");".$crlf.$crlf;
					} else {
						if (strpos(" ".$table,"cash")) $gzip_contents .= get_table_def($dbname, $table, $crlf).";\");".$crlf.$crlf;
					}
				$gzip_contents .= $crlf;
					if ($cash==0) {
						if (!strpos(" ".$table,"cash")) get_table_content($dbname, $table, "my_handler");
					} else {
						if (strpos(" ".$table,"cash")) get_table_content($dbname, $table, "my_handler");
					}
				}
				$i++;
			}
			$gzip_contents .= "print (\"<center><h2>Обновление базы данных окончено!</h2><br>\");".$crlf."?>";
		}
		$backup_file = str_replace(":", "_", "backup/".$url."_".$date_jour);
		echo "Создаю файл... (если далее ничего нет - файл не создан, ошибка при записи файла)<br>";
		fputs(fopen($backup_file.".txt","wb"), $gzip_contents );
		echo "<b>Файл создан</b><br>
		".$backup_file.".txt - скачать неупакованный файл можно через FTP, если архив не будет создан.<br>
		Подключаю ZIP-библиотеку...<br>";
		include("includes/zip.lib.php");
		echo "Библиотека подключена<br>";
		$zip = new Zip;
		echo "Архив создается... (если далее ничего нет - архив не создан, используйте неупакованный файл)<br>";
		$zip->Add(Array(Array("install.php",$gzip_contents)),1);
		echo "<b>Архив создан</b><br>";
		fputs(fopen($backup_file.".zip","wb"), $zip->get_file() );
		unlink($backup_file.".txt");
		echo $backup_file.".zip — Скачать архив можно через FTP (прямой доступ к файлу закрыт в целях безопасности).";
		break;
	} // end switch
} else echo "Доступ закрыт!";
?>