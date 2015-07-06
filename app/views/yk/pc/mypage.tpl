<center>
	<table border='0' width='620' cellpadding='6'>
		<tr>
			<td width='60' valign='top'>
                        {foreach from = $nexttrans item="itemdata"}<br>
                            <div class="rbroundboxc">
					<div class="rbtopc">
						<div></div>
					</div>

					<div class="rbcontentc"><font size='5'>
						<b>{$itemdata.date}のご予約</b></font><br />
                                                {if $itemdata.storename}
                                                    <b>店舗：</b> {$itemdata.storename}<br>
                                                {/if}
                                                <b>施術:</b> {$itemdata.servicessys}<br />
                                                <b>日時:</b> {$itemdata.time}～<br />
						<b>担当:</b> {$itemdata.staff}<br />
                                                
                                                {if $itemdata.canselb == false}
                                                    <br />（ご予約の{$cancellim}時間前を過ぎると、webでキャンセルができません。）<br />
                                                    
                                                {else}
                                                <center>
                                                <form name='YkMyPageForm' action="{$form_action}" method="post" onSubmit="return check()">
                                                    <input type="submit" name="p_delete{$itemdata.transdate}" class="groovybutton" value="この予約をキャンセル" title="" style="cursor: pointer;">
                                                </form>
                                                </center>
                                                {/if}
					</div>
					<!-- /rbcontent -->
					<div class="rbbotc"><div></div></div>
			</div>
			<!-- /rbroundbox -->
                        {foreachelse}
                        <div class="rbroundboxc">
					<div class="rbtopc"><div></div></div>
					<div class="rbcontentc"><font size='5'><b>予約：</b> なし</div>
					<div class="rbbotc"><div></div></div>
			</div>
                        {/foreach}
			</td>
                  	<tr>
                        </tr>
	</table>
{if $points1 || $points2}
	<div class='fadebox'>{$points1}{$points2}</div>
{/if} <br>
	<form name='YkMyPageForm' action="{$form_action}" method="post">
		<div class='buttonframe'>
{if $nextyoyaku == 1}
			<!--<input type="submit" name="p_delete" class="groovybutton" value="予約をキャンセル" title="" style="cursor: pointer;">-->
                        {if $addyoyakub == 1}
                        <input type='submit' name='p_add' class="groovybutton" value="他の予約をする" title="" style="cursor: pointer;">
                        {/if}
{else}
			<input type="submit" name="p_new" class="groovybutton" value="新規予約を登録" title="" style="cursor: pointer;">
{/if}<input type="submit" name="p_info" class="groovybutton" value="会員情報を更新" title="" style="cursor: pointer;">
		</div>
	</form>
</center>
