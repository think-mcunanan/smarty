<center>
<form name='YkNew4Form' action="{$form_action}" method="post">
<table align="center" border="0px">
	<tr valign="bottom" align="center">
		<td><img src="{$html->url('/img/new/start.gif')}" width="100" height="20"></td>
		<td><img src="{$html->url('/img/new/1shimei.gif')}" width="100" height="25"></td>
		<td><img src="{$html->url('/img/new/2gijutu.gif')}" width="100" height="25"></td>
		<td><img src="{$html->url('/img/new/3hiduke.gif')}" width="105" height="25"></td>
		<td><img src="{$html->url('/img/new/4jikan.gif')}" width="120" height="25"></td>
		<td><img src="{$html->url('/img/new/touroku.gif')}" width="110" height="25"></td>
		<td><img src="{$html->url('/img/new/arrowgradopp.gif')}" width="29" height="21"></td>
		<td><img src="{$html->url('/img/new/end.gif')}" width="100" height="20"></td>
	</tr>
</table>
<hr align="center" width="85%">
<br />
<table border='0' width='400'>
	<tr>
		<td>
		<div class="rbroundboxc">
		<div class="rbtopc">
		<div></div>
		</div>
		<div class="rbcontentc">
		<table border='0' cellpadding='6'>
			<tr>
				<td align='right'><b>担当者:</b></td>
				<td align='left'>{$trans_staff}</td>
			</tr>
			<tr>
				<td align='right'><b>日付:</b></td>
				<td align='left'>{$trans_date}</td>
			</tr>
			<tr>
				<td align='right'><b>時間:</b></td>
				<td align='left'>{$trans_time}</td>
			</tr>
			<tr>
				<td align='right' valign='top'><b>メニュー選択:</b></td>
				<td align='left'>{$trans_services}</td>
			</tr>
		</table>
		</div>
		<!-- /rbcontent -->
		<div class="rbbotc">
		<div></div>
		</div>
		</div>
		<!-- /rbroundbox --></td>
	</tr>
</table>
<br />
<br />
<div class='buttonframe'><input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_confirm" class="groovybutton" value="決定" title="" style="cursor: pointer;"></div>
</form>
</center>
