<!--Optimized by jonathanparel, 20160907 For Mobile Devices; RM#1724; ii-->
<div class="mypage" style="margin: auto; min-width: 400px;">
    <br />
    <div id="bground_mat" style="width:80%;">
        
        {foreach from = $nexttrans item="itemdata"}
        <div class="rbroundboxc">
            <div class="rbtopc">
                <div>
                </div>
            </div>

            <div class="rbcontentc">
                <font size='5'>
                    <b>{$itemdata.date}のご予約</b>
                </font>
                <br />

                {if $itemdata.storename}
                    <b>店舗：</b>
                    {$itemdata.storename}<br>
                {/if}
                <b>施術:</b> {$itemdata.servicessys}<br />
                <b>日時:</b> {$itemdata.time}～<br />
                <b>担当:</b> {$itemdata.staff}<br />

                {if $itemdata.canselb == false}
                    <br />（ご予約の{$cancellim}時間前を過ぎると、webでキャンセルができません。）<br />
                {else}
                    <form name='YkMyPageForm' action="{$form_action}" method="post" onSubmit="return check()">
                        <input type="submit" name="p_delete{$itemdata.transdate}" class="groovybutton" value="この予約をキャンセル" title="" style="cursor: pointer;">
                    </form>
                {/if}
				<hr id="hr_before-button">
            </div>

            <!-- /rbcontent -->
            <div class="rbbotc">
                <div>
                </div>
            </div>
        </div>

        <!-- /rbroundbox -->
        {foreachelse}
        <div class="rbroundboxc">
            <div class="rbtopc">
                <div>
                </div>
            </div>
            <div class="rbcontentc"><font size='5'>
                <b>予約：</b> なし
            </div>
            <div class="rbbotc">
                <div>
                </div>
            </div>
        </div>
        {/foreach}

        {if $points1 || $points2}
            <div class='fadebox'>{$points1}{$points2}
            </div>
        {/if}
        <br />
    
        <hr id="hr_before-button">
        <form name='YkMyPageForm' action="{$form_action}" method="post">

            {if $nextyoyaku == 1}
                <!--<input type="submit" name="p_delete" class="groovybutton" value="予約をキャンセル" title="" style="cursor: pointer;">-->
                {if $addyoyakub == 1}
                    <input type='submit' name='p_add' class="groovybutton" value="他の予約をする" title="" style="cursor: pointer;">
                {/if}
            {else}
                <input type="submit" name="p_new" class="groovybutton" value="新規予約を登録" title="" style="cursor: pointer;">
            {/if}
            <input type="submit" name="p_info" class="groovybutton" value="会員情報を更新" title="" style="cursor: pointer;">
        </form>
    </div>
</div>
<!--Optimized by jonathanparel, 20160907 For Mobile Devices; RM#1724; xx-->