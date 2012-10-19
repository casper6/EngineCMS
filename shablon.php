<?php
// Шаблоны
function shablon_show($shablon_type, $shablon_id) {
	switch ($shablon_type) {
		case "block":
			switch ($shablon_id) {
				case "1": return ""; break;
				############################################################################################
				case "5": return ""; break;
				############################################################################################
				# Блок папок ОТКРЫТОГО модуля
				case "8": return ""; break; 
				############################################################################################
			}
		break;
############################################################################################################
############################################################################################################
############################################################################################################
		case "razdel":
			switch ($shablon_id) {
			// [page_id] [page_num] [page_link] [page_open_text] [page_title] [page_text] [page_data] 
			// [page_counter] [page_comments] [cat_id] [cat_name] [cat_link] [sred_golos] [all_golos] 
			// [plus_golos] [neo_golos] [minus_golos] [colspan] [active_color]    
			// [all_cat_link] [all_page_counter] [all_page_data] [all_page_comments]
			// [page_active] [page_golos] [page_foto_adres] [page_foto] [page_search] [page_price] [page_rss]
				// Форум
				case "1": return "<tr><td class=cat_page_title><a href=[page_link]>[page_title]</a></td>
				<td class=cat_page_date><nobr>[page_data]</nobr></td>
				<td class=cat_page_date>читали:&nbsp;[page_counter]</td>
				<td class=cat_page_commnum>[page_comments]</td></tr>"; break;
				############################################################################################
				case "2": return ""; break;
				############################################################################################
				case "3": return ""; break;
				############################################################################################
				// Рейтинги
				case "4": return "<A href=[page_link]><div class=reiting_title[active_color]>[page_title]</div></A></td>
				<td class=reiting_text[active_color]>[page_open_text]</td>
				<td class=reiting_golos align=center[active_color]>[sred_golos]</td>
				<td class=reiting_golos align=center[active_color]>[all_golos]</td>
				<td class=reiting_golos align=center style='background: rgb(173, 222, 173);'>[plus_golos]</td>
				<td class=reiting_golos align=center style='background: rgb(241, 241, 148);'>[neo_golos]</td>
				<td class=reiting_golos align=center style='background: rgb(252, 192, 198);'>[minus_golos]"; break;
				############################################################################################
				default: return "<div class=page_link_title>[page_link_title]</div>
				<div class=page_open_text>[page_open_text]</div>
				<div class=page_nums>
				<div class=cat_page_counter>[all_cat_link] [all_page_counter]</div>
				<div class=all_page_data>[all_page_data]</div>
				<div class=all_page_comments>[all_page_comments]</div>
				<div class=cat_golos>[cat_golos]</div>
				<div class=cat_razdel></div>
				</div>"; break;
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
				default: return ""; break;
			}
		break;
############################################################################################################
############################################################################################################
############################################################################################################
		case "page":
			switch ($shablon_id) {
				case "1": return "

				[main_title]
				[page_title]
				[page_opentext]
				[page_text]
				[page_date]<br>
				[page_tags]<br>
				[page_socialnetwork]<br>
				[page_favorites]<br>
				[page_blog]
				[venzel]
				[page_search_news]
				[page_reiting]
				[page_comments]<br>
				[page_add_comments]

				"; break;
				############################################################################################
				default: return "

				[main_title]
				[page_title]
				[page_opentext]
				[page_text]
				[page_date]<br>
				[page_tags]<br>
				[page_socialnetwork]<br>
				[page_favorites]<br>
				[page_blog]
				[venzel]
				[page_search_news]
				[page_reiting]
				[page_comments]<br>
				[page_add_comments]

				"; break;
			}
		break;
		
############################################################################################################
############################################################################################################
############################################################################################################
		case "comments":
			switch ($shablon_id) {
			// [comment_id] [comment_num] [comment_text] [comment_avtor_type] [comment_avtor] [comment_data] [comment_time] [comment_admin]
				
############################################################################################
// Диалоговые комментарии аля Joomla
case "1": return "<DIV id=comments_1_commentbubble> 
<TABLE border=0 cellpadding=0 cellspacing=0 width=100%> 
<TBODY><TR> 
<TD width=24><IMG src=/images/shablon/comments/ang_h_g2.gif width=24 height=28></TD> 
<TD colspan=2 background=/images/shablon/comments/top_center2.gif> 
<DIV id=comments_1_commentheader><DIV> 
<SPAN class=comments_1_commentnumber><A id=comment_[comment_id] name=comment_[comment_id]></A>
[comment_num]. </SPAN>[comment_data] в [comment_time]
</DIV></DIV></TD> 
<TD width=24><IMG src=/images/shablon/comments/ang_h_d2.gif width=24 height=28></TD> 
</TR> 
<TR> 
<TD background=/images/shablon/comments/left2.gif>&nbsp;</TD> 
<TD colspan=2> 
<DIV id=comments_1_commentbody2><img src=/images/user.png align=bottom> <SPAN class=comment_avtor>[comment_avtor]</SPAN></DIV>
<DIV id=comments_1_comment class=comment_text>[comment_text]<br>
[comment_otvet]<span style='margin-left:10px;'>[comment_otvet_show]</span> [comment_admin]
</DIV> 
<DIV id=comments_1_commentusertype>[comment_avtor_type]</DIV> 
</TD> 
<TD background=/images/shablon/comments/right2.gif>&nbsp;</TD> 
</TR> 
</TBODY></TABLE> 
<TABLE border=0 cellpadding=0 cellspacing=0 width=100%> 
<TBODY><TR valign=top> 
<TD width=24><IMG src=/images/shablon/comments/ang_b_g2.gif></TD> 
<TD background=/images/shablon/comments/bottom_center2.gif>&nbsp;</TD> 
<TD width=24><IMG src=/images/shablon/comments/ang_b_d2.gif></TD> 
</TR> 
</TBODY></TABLE> 
</DIV>"; break;
// Диалоговые комментарии ArtistStyle
case "2": return "<DIV id=comments_1_commentbubble><div class='comm_polosa'></div>
<A name=comment_[comment_id]></A><SPAN class=comment_avtor>[comment_ipbox] [comment_avtor]</SPAN> <SPAN class=comments_1_data>[comment_data] в [comment_time]</SPAN> [comment_admin]
<DIV id=comments_1_comment class='comment_text'>[comment_text]</DIV>
<DIV class='comment_otvet'>[comment_otvet] [comment_otvet_show]</DIV>
</DIV>"; break;
// Стандартные комментарии
default: return "<A name=comment_[comment_id]></A><SPAN class=comments_1_commentnumber>[comment_num]. </SPAN> <img src=/images/user.png align=bottom class=comments_1_commentavatar> <SPAN class=comment_avtor>[comment_avtor]</SPAN><SPAN style='margin-left:15px;' class=comment_data>[comment_data] в [comment_time]</SPAN><br>
				<div class=comment_mail>[comment_mail]</div>
				<div class=comment_adres>[comment_adres]</div>
				<div class=comment_tel>[comment_tel]</div>
				<div class=page_comm_text>[comment_text]</div><div class=page_comm_otvet>[comment_otvet_show] <span style='margin-left:10px;'>[comment_otvet]</span></div> [comment_admin]
<div class=razdel></div><br>"; 
break;
				############################################################################################
			}
		break;
############################################################################################################
############################################################################################################
############################################################################################################
	}
	
}

?>