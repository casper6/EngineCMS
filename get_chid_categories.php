<?php
require_once("mainfile.php");
global $prefix, $db;

if($_REQUEST)
{
	$id 	= $_REQUEST['parent_id'];
	$query  = "select id, name from ".$prefix."_regions where region_id = ".$id;
	$result  = $db->sql_query($query);
	$num_rows = @mysql_num_rows($result);
	if($num_rows > 0)
	{?>
		<select name="id_regions" class="parent">
		<option value="" selected="selected">Выберите</option>
		<?php
		while ($row = $db->sql_fetchrow($result)) {?>
			<option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
		<?php
		}?>
		</select>	
	<?php	
	}
	else{echo '<label style="padding:7px;float:left; font-size:12px;">Выбранно!</label>';}
}
?>