<?php
require_once("mainfile.php");
global $adminmail;
?>
<HTML><HEAD><TITLE>Написать письмо администрации сайта</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<META content="Написать письмо администрации сайта" name=description>
<META content="Написать письмо администрации сайта" name=keywords>
<LINK href="favicon.ico" type=image/x-icon rel=icon><LINK href="favicon.ico" type=image/x-icon rel="shortcut icon">
</HEAD>

<BODY>
<H3> <center><img src='images/mail.jpg'><br><font color='#1E90FF'>Написать письмо администрации сайта</font></H3> 
<center>
<table width=1 border=0> 
<form action='send.php' enctype='multipart/form-data' method='post' name='formsend'> 
<tr><td><nobr><b>Ваши Ф.И.О.*:</b></nobr></td><td align=right><input type='text' name='mail_subject' maxlength=32 size=40></td></tr> 
<tr><td><nobr><b>Эл. почта*:</b></nobr></td><td align=right><input type='text' name='mail_to' maxlength=64 size=40></td></tr> 
<tr><td><nobr><b>Телефон*:</b></nobr></td><td align=right><input type='text' name='mail_tel' maxlength=30 size=40 style='width:100%;'></td></tr> 
<tr><td colspan=2><b>Сообщение*:</b><br><textarea cols=50 rows=8 name='mail_msg'></textarea><br>
</td> 
<tr><td colspan=2>Также вы можете воспользоваться вашей обычной почтовой программой или сайтом (например, для отправки фотографий или других файлов) - пишите на <? echo $adminmail; ?></td></tr> 
</tr><tr><td colspan=2>
<center><input value="Отправить" type="button" onClick=" al=''; if (document.formsend.mail_subject.value=='') al = al + 'Вы не заполнили поле &quot;Ваши Ф.И.О.&quot;. '; if (document.formsend.mail_to.value=='') al = al + 'Вы не заполнили поле &quot;Эл. почта&quot;. '; if (document.formsend.mail_tel.value=='') al = al + 'Вы не заполнили поле &quot;Телефон&quot;. '; if (document.formsend.mail_msg.value=='') al = al + 'Вы не заполнили поле &quot;Сообщение&quot;. '; if (al) alert(al); else submit();" style="width:180px; height: 50px;"></center>
</td></tr> 
</form> 
</table>Все поля обязательны к заполнению. Если у вас нет электронной почты, вы можете указать только телефон.

</center> 

</DIV>
</BODY></HTML>
