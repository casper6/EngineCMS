<?php
$name = strip_tags($_REQUEST['name']); 
if ($name == "sort_data_base") {
	$fill = strip_tags($_REQUEST['fill']); 
	SetCookie("$name","$fill");
	header('Refresh: 0; URL='.getenv("HTTP_REFERER"));
	echo "секундочку...";
} else echo "Попытка взлома.";
?>
