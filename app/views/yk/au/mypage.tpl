<table width='90%' border='0'>
		<tr>
		<td align='left'>
                        {foreach from = $nexttrans item="itemdata"}<br>
						<b>{$itemdata.date}のご予約</b></font><br />
                                                {if $itemdata.storename}
                                                    <b>店舗：</b> {$itemdata.storename}<br>
                                                {/if}
                                                <b>施術:</b> {$itemdata.servicessys}<br />
                                                <b>日時:</b> {$itemdata.time}～<br />
						<b>担当:</b> {$itemdata.staff}<br />
                                                {if $itemdata.canselb == false}
                                                    (ご予約の{$cancellim}時間前のため、webからのキャンセルはできません。）<br />
                                                {else}
                                                <center>
                                                <form name='YkMyPageForm' action="{$form_action}" method="post">
                                                    <input type="submit" name="p_delete{$itemdata.transdate}" class="groovybutton" value="この予約をキャンセル" title="" style="cursor: pointer;">
                                                </form>
                                                </center>
                                                {/if}
                <hr />
                        {foreachelse}
予約情報：なし
                        {/foreach}
			</td>
                  	<tr>
                        </tr>
	</table>
{if $points1 || $points2}
{$points1}{$points2}
{/if} <br />
<form name='YkMyPageForm' action="{$form_action}" method="post">
{if $nextyoyaku == 1}
                        {if $addyoyakub == 1}
                        <input type='submit' name='p_add' class="groovybutton" value="他の予約をする" title="" style="cursor: pointer;"><br />
                        {/if}
{else}
			<input type="submit" name="p_new" class="groovybutton" value="新規予約を登録" title="" style="cursor: pointer;"><br />
{/if}<input type="submit" name="p_info" class="groovybutton" value="会員情報を更新" title="" style="cursor: pointer;"><br />
<input type='submit' name='p_logout' class="groovybutton" value='ログアウト' style="cursor: pointer;" />
</form>
