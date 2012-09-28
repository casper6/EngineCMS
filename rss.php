<?php
require_once("mainfile.php");
define('DATE_FORMAT_RFC822', 'r');
global $siteurl, $prefix, $db, $sitename, $adminmail;
header ("Content-Type: text/xml");

// Определение названий всех модулей
$sql3 = "select `name`, `title` from `".$prefix."_mainpage` where `type`='2' and name!='index'";
$result3 = $db->sql_query($sql3);
$m_title = array();
while ($row3 = $db->sql_fetchrow($result3)) {
$m_name = $row3['name'];
$m_title[$m_name] = $row3['title'];
}

// Определяем все разделы
$r_title = array();
$sql = "SELECT cid, title FROM ".$prefix."_pages_categories WHERE `tables`='pages'";
$result = $db->sql_query($sql);
while ($rows = $db->sql_fetchrow($result)) {
$module_id = $rows['cid'];
$r_title[$module_id] = $rows['title'];
}

$res2 = $db->sql_query("SELECT `pid`,`module`,`cid`,`title`,`open_text`,UNIX_TIMESTAMP(date) as pubdate FROM ".$prefix."_pages where active='1' and `tables`='pages' and rss='1' and title != '' order by date desc limit 0,50");
$numrows = $db->sql_numrows($res2);
$output = "";
if ($numrows > 0) {
	$modif = false;
	while ($row = $db->sql_fetchrow($res2)) {
		$p_pid = $row['pid'];
		$p_title = str_replace("&nbsp;"," ",strip_tags($row['title']));
		$p_module = $row['module'];
		$p_cid = $row['cid'];
		$p_date = date(DATE_FORMAT_RFC822, $row['pubdate']);
		$p_open_text = strip_tags(str_replace("&nbsp;"," ",$row['open_text']), '<b><i><a><strong><em>');
		$p_open_text = str_replace('alt="" ','',$p_open_text);
		$p_open_text = str_replace('border="0" ','',$p_open_text);
		$p_open_text = str_replace(' />','>',$p_open_text);
		$razdel = $m_title[$p_module]." -";
	
		$p_open_text = str_replace("[заголовок]","",$p_open_text);
		$p_open_text = html_entity_decode ($p_open_text);
		$p_open_text = nl2br ($p_open_text);
		$p_open_text = preg_replace('/[\x00-\x08\x0B-\x0C\x0E-\x19]/', '', $p_open_text);
	
		if ($modif==false) {
			$modif=true;
			header("Last-Modified: " . gmdate("D, d M Y H:i:s", $row['pubdate']) . " GMT"); 
			# дата самого свежего сообщения
		}
		if ($p_cid != 0) {
			if ($r_title[$p_cid] != "") $razdel .= " $r_title[$p_cid] -";
		}
		$link = "http://".$siteurl."/-".$p_module."_page_".$p_pid."";
		$output .= "<item>
		<guid isPermaLink='true'>$link</guid>
		<link>$link</link>
		<title>"."$razdel $p_title"."</title>
		<pubDate>".$p_date."</pubDate>
		<category>$razdel</category>
		<description><![CDATA[".$p_open_text."]]></description>
		</item>";
	}
}
$output .= "</channel>
</rss>";

echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<rss version=\"2.0\" xmlns:ya=\"http://blogs.yandex.ru/\" xmlns:wfw=\"http://wellformedweb.org/CommentAPI/\">
	<channel>
		<title>$siteurl RSS</title>
		<link>http://$siteurl/</link>
		<description>".$sitename." RSS</description>
		<language>ru</language>
		<managingEditor>".$adminmail."</managingEditor>
                <webMaster>13i@list.ru</webMaster>
                <image> 
		<link>http://$siteurl/</link> 
		<url>http://$siteurl/img/logo.gif</url> 
		<title>$siteurl</title> 
		</image> 
            ".$output;
?>