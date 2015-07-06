<center>
	<form name='YkNew1Form' action="{$form_action}" method="post">
		<table align="center" border="0px">
			<tr valign="bottom" align="center">
				<td><img src="{$html->url('/img/new/start.gif')}" width="100" height="20"></td>
				<td><img src="{$html->url('/img/new/shimei.gif')}" width="100" height="25"></td>
				<td><img src="{$html->url('/img/new/arrowgradopp.gif')}" width="29" height="21"></td>
				<td><img src="{$html->url('/img/new/2gijutu.gif')}" width="100" height="25"></td>
				<td><img src="{$html->url('/img/new/3hiduke.gif')}" width="105" height="25"></td>
				<td><img src="{$html->url('/img/new/4jikan.gif')}" width="120" height="25"></td>
				<td><img src="{$html->url('/img/new/5touroku.gif')}" width="110" height="25"></td>
				<td><img src="{$html->url('/img/new/end.gif')}" width="100" height="20"></td>
			</tr>
		</table>
		<hr align="center" width="85%" />
		<br />
{if $error != 1}
		<table border='0' cellpadding='8'>
			<tr>
				<td>
					<table border='1' style='border-collapse: collapse; border: 1px solid #dddddd;' width='760' cellpadding='5'>
						<tr bgcolor='#efefef'>
							<td colspan='4' align='left'><strong>指名</strong></td>
						</tr>
						{$staffhtmltr}
					</table>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<div class='buttonframe'><input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_next" class="groovybutton" value="次へ" title="" style="cursor: pointer;"></div>
{else}
                <div class='buttonframe'><input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;"></div>
{/if}
           <input type="hidden" name="cid" value="{$companyid}" />
           <input type="hidden" name="scd" value="{$storecode}" />
	</form>
</center>
