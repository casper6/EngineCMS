<?php
$siteurl = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
if ($siteurl == "") $siteurl = "localhost";
echo '<meta http-equiv="refresh" content="0; url=http://'.$siteurl.'/red">'; exit;
?>