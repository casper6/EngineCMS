<?php
// Шаблоны стилей CSS
function shablon_style_show ($shablon_type, $shablon_id) {
	switch ($shablon_type) {
		case "block":
			switch ($shablon_id) {
				case "1": return ""; break;
			}
		break;
		case "razdel":
			switch ($shablon_id) {
				case "1": return ""; break;
			}
		break;
		case "catalog":
			switch ($shablon_id) {
				case "1": return ""; break;
			}
		break;
		case "page":
			switch ($shablon_id) {
				case "1": return ""; break;
			}
		break;
		case "comments":
		switch ($shablon_id) {
		case "0": return "";
		break;
		case "3": return "
		#page_comments {position:relative;overflow:hidden;padding-left:20px;margin-left:-20px;}
		.comm_razdel .message{font-size:13px;font-family: Arial, sans-serif;line-height:140%;padding-bottom:5px;padding-top:10px;}
		.comm_razdel .reply{}
		.comm_razdel .reply a.comm_write{font-size:11px;text-decoration:none;border-bottom:1px dashed;margin-right:10px;}
		.comm_razdel .reply a.edit_link, .comm_razdel .reply a.delete_link{font-size:11px;text-decoration:none;border-bottom:1px dashed; color:black; margin-left:10px;}
		.comm_razdel .info {font-size:11px;font-family:tahoma,sans-serif;line-height:18px;}
		.comm_razdel .info .avatar{float:left;margin-right:10px;width:24px !important;height:24px !important;display:block;overflow:hidden;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;}
		.comm_razdel .info .avatar img{display:block;width:24px;height:24px;}
		.comm_razdel .info .username{float:left;margin-top:3px;display:block;color:#666666;font-weight:bold;}
		.comm_razdel .info .comma{float:left;margin-top:3px;margin-right:10px;display:block;color:#666666;font-weight:bold;}
		.comm_razdel .info time{display:block;margin-top:3px;font-size:10px;color:#666666;float:left;margin-right:10px;}
		.comm_razdel .info a.link_to_comment{display:block;margin-top:3px;font-size:11px;float:left;margin-right:10px;text-decoration: none}
		.comm_razdel .info {position:relative;}
		.comm_razdel:hover > .comment_body .info > .folding-dot-holder{display:block;}
		.comm_razdel .info .folding-dot-holder{display:none;position:absolute;top:0;left:0;width:1px;height:1px;}
		.comm_razdel .info .folding-dot{width:500px;height:5px;background:red;right:1px;top:9px;position:absolute;background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAFCAIAAADKYVtkAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAFFJREFUeNpi/P//PwMDw88/f9lZmBlIBCxAvPfa46tPX2tLizpryZKkmQlo59UnrwW5OYDk15+/SdMMdK22rOj7Lz+AJDc7K0maGSnxM0CAAQBHCiEG+qfxeAAAAABJRU5ErkJggg==) repeat-x 100% 0;}
		#page_comments .comm_otvet, #page_comments .comm_razdel{margin-top:20px;}
		#page_comments .comm_otvet .comment_item{margin-left:20px;}";
		break;
		case "2": return "
		.comments_1_data {font-size:12px;color:gray;padding-left:10px;padding-right:10px;}
		#comments_1_commentbubble {margin-top:18px;font-family:Geneva, Arial, Helvetica, sans-serif;font-size:12px;}
		#comments_1_commentheader {width:100%;padding-left:2px;padding-top:2px;color:#333333;font-size:11px;font-weight:normal;}
		.comm_polosa {border-bottom:1px dotted; height:1px; padding-top:3px; margin-bottom:3px;}
		.comment_otvet {margin-top:10px; margin-left:45px;}
		a.comm_write:link, a.comm_write:visited {border-bottom:1px dotted; text-decoration:none; color:darkred;}
		a.comm_write:hover {color:red;}
		.comment_text {margin-top:10px;}
		.comment_avtor {font-size: 16px; font-weight:bold; color:#999999;}
		.comm_otvet {background: url(data:image/gif;base64,R0lGODlhEAAoAIABAMjIyAAAACH5BAEAAAEALAAAAAAQACgAAAIyjI+py+2vADxygmvxu9xOU3lfCJHbF5iO2rCMu8ARKic1clP0PvLiX/IFe0TgqVjiKAsAOw==) no-repeat;}";
		break;	
		case "1": return "
		#comments_1_commentbubble {margin-top:18px;font-family:Geneva, Arial, Helvetica, sans-serif;font-size:12px;}
		#comments_1_commentheader {width:100%;padding-left:2px;padding-top:2px;height: 26px;color:#333333;font-size:11px;font-weight:normal;}
		#comments_1_commentheader div{width:100%;height:90%;border-bottom: 1px dashed #999966;}
		.comments_1_commentnumber {font-size:14px;font-weight:bold; color:black;}
		#comments_1_commentbody2{height:100%;padding-left:2px;padding-top:0px;font-weight:normal; color:black;background-color:#E0E0D1;}
		#comments_1_comment{font-weight:normal; }
		#comments_1_commentusertype{font-size:9px;text-align:right;padding-right:2px;color:#666666;line-height:12px;}
		#comments_1_commentfooter2{height:25px;font-size:12px;text-align:left;font-style:italic;padding:25px 0px 0px 0px;color:#666666;font-weight:bold;}";
		break;
		}
		break;
	}
}
?>