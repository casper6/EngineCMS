<?php
// Шаблоны стилей CSS
function shablon_style_show ($shablon_type, $shablon_id) {
	switch ($shablon_type) {
		case "block":
			switch ($shablon_id) {
				case "1": return ""; break;
				############################################################################################
			}
		break;
############################################################################################################
############################################################################################################
############################################################################################################
		case "razdel":
			switch ($shablon_id) {
				case "1": return ""; break;
				############################################################################################
			}
		break;
############################################################################################################
############################################################################################################
############################################################################################################
		case "catalog":
			switch ($shablon_id) {
				case "1": return ""; break;
				############################################################################################
			}
		break;
############################################################################################################
############################################################################################################
############################################################################################################
		case "page":
			switch ($shablon_id) {
				case "1": return ""; break;
				############################################################################################
			}
		break;
############################################################################################################
############################################################################################################
############################################################################################################
		case "comments":
			switch ($shablon_id) {
			// [comment_id] [comment_num] [comment_text] [comment_avtor_type] [comment_avtor] [comment_data] [comment_time] [comment_admin]
				case "0": return ""; break;
				############################################################################################
				case "2": return "
.comments_1_data {
font-size:12px;
color:gray;
padding-left:10px;
padding-right:10px;
}

#comments_1_commentbubble {
margin-top:18px;
font-family:Geneva, Arial, Helvetica, sans-serif;
font-size:12px;
}

#comments_1_commentheader {
width:100%;
padding-left:2px;
padding-top:2px;
color:#333333;
font-size:11px;
font-weight:normal;
}

.comm_polosa {border-bottom:1px dotted; height:1px; padding-top:3px; margin-bottom:3px;}
.comment_otvet {margin-top:10px; margin-left:45px;}
a.comm_write:link, a.comm_write:visited {border-bottom:1px dotted; text-decoration:none; color:darkred;}
a.comm_write:hover {color:red;}
.comment_text {margin-top:10px;}
.comment_avtor {font-size: 16px; font-weight:bold; color:#999999;}

.comm_otvet {background: url(/images/commotvet.gif) no-repeat;}
";
 break;
				
case "1": return "
#comments_1_commentbubble {
margin-top:18px;
font-family:Geneva, Arial, Helvetica, sans-serif;
font-size:12px;
}

#comments_1_commentheader {
width:100%;
padding-left:2px;
padding-top:2px;
height: 26px;
color:#333333;
font-size:11px;
font-weight:normal;
}

#comments_1_commentheader div{
width:100%;
height:90%;
border-bottom: 1px dashed #999966;
}

.comments_1_commentnumber {
font-size:14px;
font-weight:bold; 
color:black;
}

#comments_1_commentbody2{
height:100%;
padding-left:2px;
padding-top:0px;
font-weight:normal; 
color:black;
background-color:#E0E0D1;
}

#comments_1_comment{
font-weight:normal; 
}

#comments_1_commentusertype{
font-size:9px;
text-align:right;
padding-right:2px;
color:#666666;
line-height:12px;
}

#comments_1_commentfooter2{
height:25px;
font-size:12px;
text-align:left;
font-style:italic;
padding:25px 0px 0px 0px;
color:#666666;
font-weight:bold;
}
"; break;
				############################################################################################
			}
		break;
############################################################################################################
############################################################################################################
############################################################################################################
	}
	
}

?>