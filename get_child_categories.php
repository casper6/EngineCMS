<?php
require_once("mainfile.php");
global $prefix, $db;
if ($_REQUEST) {
	$id = $_REQUEST['parent_id'];
	$query = "select id, name from ".$prefix."_regions where region_id = ".$id;
	$result = $db->sql_query($query);
	$num_rows = @mysql_num_rows($result);
	if($num_rows > 0) {
		echo "<select name='id_regions' class='parent'>
		<option value='' selected='selected'>".aa("Выберите...")."</option>";
		while ($row = $db->sql_fetchrow($result))
			echo "<option value='".$row['id']."'>".$row['name']."</option>";
		echo "</select>";
	}
	else echo "<label class='green'>".aa("Готово")."</label>";
}
?>