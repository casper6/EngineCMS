<?php
// Перевод кодировки базы данных на utf-8 (для MySQL >= v5.0)
require_once("mainfile.php");
global $db,$dbname,$prefix;
$db->sql_query("ALTER DATABASE `".$dbname."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;") or die ("Ошибка: Не могу перевести базу данных");

$result = $db->sql_query("SELECT CONCAT('ALTER TABLE `', t.`TABLE_SCHEMA`, '`.`', t.`TABLE_NAME`, '` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;') as sqlcode
  FROM `information_schema`.`TABLES` t
 WHERE 1
   AND t.`TABLE_SCHEMA` = '".$dbname."'
 ORDER BY 1");

while ($row = $db->sql_fetchrow($result)) {
	if (strpos(" ".$row['sqlcode'],$prefix."_")) {
		$sql = $row['sqlcode'];
		//print $sql."<br>";
		$db->sql_query($sql) or die ("Ошибка: Не могу перевести таблицу: ".$sql);
	}
}

///print_r ($crow);
//$db->sql_query($crow);
/*
$tables = mysql_query("SHOW TABLES FROM ".$dbname) or die ("Ошибка: Не могу получить список таблиц базы данных"); // Список таблиц БД
$i = 0;
$num_tables = @mysql_numrows($tables);
while($i < $num_tables) {
	$table = mysql_tablename($tables, $i);
	if (strpos(" ".$table,$prefix)) { // если таблица принадлежит к сайту, по префиксу
		echo $table." — OK<br>";
		$db->sql_query("ALTER TABLE `".$table."` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;") or die ("Ошибка: Не могу перевести таблицу ".$table); // CONVER
	}
}
*/

print ("<center><h2>Перевод кодировки базы данных «".$dbname."» на utf-8 окончен!</h2><br>");
?>