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
		case "razdel":
			switch ($shablon_id) {
			// [page_id] [page_num] [page_link] [page_open_text] [page_title] [page_text] [page_data] 
			// [page_counter] [page_comments] [cat_id] [cat_name] [cat_link] [sred_golos] [all_golos] 
			// [plus_golos] [neo_golos] [minus_golos] [colspan] [active_color]    
			// [all_cat_link] [all_page_counter] [all_page_data] [all_page_comments]
			// [page_active] [page_golos] [page_foto_adres] [page_foto] [page_search] [page_price] [page_rss]
				// Форум
				case "1": return "<tr><td class=cat_page_title_forum><a href=[page_link]>[page_title]</a></td>
				<td class=cat_page_date_forum><nobr>[page_data]</nobr></td>
				<td class=cat_page_read_forum>".ss("читали:")."&nbsp;[page_counter]</td>
				<td class=cat_page_commnum_forum>[page_comments]</td></tr>"; break;
				############################################################################################
				case "2": return ""; break;
				############################################################################################
				case "3": return ""; break;
				############################################################################################
				// Рейтинги
				case "4": return "<A href=[page_link]><div class=reiting_title[active_color]>[page_title]</div></A></td>
				<td class=reiting_text[active_color]>[page_open_text][page_tags]</td>
				<td class=reiting_golos align=center[active_color]>[sred_golos]</td>
				<td class=reiting_golos align=center[active_color]>[all_golos]</td>
				<td class=reiting_golos align=center style='background: rgb(173, 222, 173);'>[plus_golos]</td>
				<td class=reiting_golos align=center style='background: rgb(241, 241, 148);'>[neo_golos]</td>
				<td class=reiting_golos align=center style='background: rgb(252, 192, 198);'>[minus_golos]"; break;
				############################################################################################
				default: return "<div class=page_link_title>[page_link_title]</div>
				<div class=page_open_text>[page_open_text][all_page_link][page_tags]</div>
				<div class=page_nums>
				<div class=cat_page_counter>[all_cat_link] [all_page_counter]</div>
				<div class=all_page_data>[all_page_data]</div>
				<div class=all_page_comments>[all_page_comments]</div>
				<div class=cat_golos>[cat_golos]</div>
				</div>"; break;
				############################################################################################
			}
		break;
############################################################################################################
		case "catalog":
			switch ($shablon_id) {
				case "1": return ""; break;
				############################################################################################
				default: return ""; break;
			}
		break;
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
				[page_search_news]
				[page_reiting]
				[page_comments]<br>
				[page_add_comments]
				"; break;
			}
		break;
		
############################################################################################################
		case "comments":
			switch ($shablon_id) {
			// [comment_id] [comment_num] [comment_text] [comment_avtor_type] [comment_avtor] [comment_data] [comment_time] [comment_admin]
				
############################################################################################
// Диалоговые комментарии аля Joomla
case "1": return "<div id=comments_1_commentbubble> 
<table border=0 cellpadding=0 cellspacing=0 width=100%> 
<tbody><tr> 
<td width=24><img src=images/shablon_comments/ang_h_g2.gif width=24 height=28></td> 
<td colspan=2 background=images/shablon_comments/top_center2.gif> 
<div id=comments_1_commentheader><div> 
<span class=comments_1_commentnumber><a id=comment_[comment_id] name=comment_[comment_id]></a>
[comment_num]. </span>[comment_data] ".ss("в")." [comment_time]
</div></div></td> 
<td width=24><img src='images/shablon_comments/ang_h_d2.gif' width=24 height=28></td> 
</tr> 
<tr> 
<td background='images/shablon_comments/left2.gif'>&nbsp;</td> 
<td colspan=2> 
<div id=comments_1_commentbody2><img src=/images/user.png align=bottom> <span class=comment_avtor>[comment_avtor]</span></div>
<div id=comments_1_comment class=comment_text>[comment_text]<br>
[comment_otvet]<span style='margin-left:10px;'>[comment_otvet_show]</span> [comment_admin]
</div> 
<div id=comments_1_commentusertype>[comment_avtor_type]</div> 
</td> 
<td background='images/shablon_comments/right2.gif'>&nbsp;</td> 
</tr> 
</tbody></table> 
<table border=0 cellpadding=0 cellspacing=0 width=100%> 
<tbody><tr valign=top> 
<td width=24><img src=images/shablon_comments/ang_b_g2.gif></td> 
<td background=images/shablon_comments/bottom_center2.gif>&nbsp;</td> 
<td width=24><img src=images/shablon_comments/ang_b_d2.gif></td> 
</tr> 
</tbody></table> 
</div>"; break;
// Диалоговые комментарии ArtistStyle
case "2": return "<div id='comments_1_commentbubble'><div class='comm_polosa'></div><a name='comment_[comment_id]'></a><span class='comment_avtor'>[comment_ipbox] [comment_avtor]</span> <span class='comments_1_data'>[comment_data] ".ss("в")." [comment_time]</span> [comment_admin]<div id='comments_1_comment' class='comment_text'>[comment_text]</div><div class='comment_otvet'>[comment_otvet] [comment_otvet_show]</div></div>"; break;

// <span class="to_chidren"><a href="#comment_7070750" data-id="7070664" data-parent_id="7070750" class="to_parent back_to_children">↓</a></span>
// <span class="parent_id" data-parent_id="0"></span>

// Комментарии аля Habrahabr.ru
case "3": return '<a name="comment_[comment_id]"></a><div class="comment_body"><div class="info"><div class="folding-dot-holder"><div class="folding-dot"></div></div>[comment_ipbox] <span class="username">[comment_avtor]</span><span class="comma">,</span><time> [comment_data] '.ss("в").' [comment_time]</time><a href="#comment_[comment_id]" class="link_to_comment">#</a><div class="clear"></div></div><div class="message">[comment_text]</div><div class="reply">[comment_otvet] [comment_otvet_show] [comment_admin]</div></div>';

// Стандартные комментарии
default: return "<a name='comment_[comment_id]'></a><span class='comments_1_commentnumber'>[comment_num]. </span> <img src='images/user.png' align='bottom' class='comments_1_commentavatar'> <span class='comment_avtor'>[comment_avtor]</span><span style='margin-left:15px;' class='comment_data'>[comment_data] ".ss("в")." [comment_time]</span><br><div class='comment_mail'>[comment_mail]</div><div class='comment_adres'>[comment_adres]</div><div class='comment_tel'>[comment_tel]</div><div class='page_comm_text'>[comment_text]</div><div class='page_comm_otvet'>[comment_otvet_show] <span style='margin-left:10px;'>[comment_otvet]</span></div> [comment_admin]<br>"; 
break;
				############################################################################################
			}
		break;
############################################################################################################
	}
	
}

?>