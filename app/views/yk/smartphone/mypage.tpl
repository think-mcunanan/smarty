<!-- Created by jonathanparel, 20160923; RM#1724; -------------------------ii-->
<br />
<div id="mypage">
		
    {foreach from = $nexttrans item="itemdata"}<br>
        <b>{$itemdata.date}のご予約</b></font><br />
        
        {if $itemdata.storename}
            <b>店舗：</b> {$itemdata.storename}<br>
        {/if}
        
        <b>施術:</b> {$itemdata.servicessys}<br />
        <b>日時:</b> {$itemdata.time}～<br />
        <b>担当　:</b> {$itemdata.staff}<br />
        
        <br />
        {if $itemdata.canselb == false}
            (ご予約の{$cancellim}時間前のため、webからのキャンセルはできません。）<br />
        {else}
            
            <form name='YkMyPageForm' action="{$form_action}" method="post">
                <input class="groovybutton" type="submit" name="p_delete{$itemdata.transdate}" value="この予約をキャンセル" title="" style="cursor: pointer;">
            </form>
            
        {/if}                                            
        <hr />
        
    {foreachelse}
        予約情報：なし
        <br />  
    {/foreach}
          
    {if $points1 || $points2}
        {$points1}{$points2}
        <br />
    {/if}

    
    <form name='YkMyPageForm' action="{$form_action}" method="post">
        {if $nextyoyaku == 1}
            {if $addyoyakub == 1}
                <div style="display: inline-block; max-width: 33%;">
                    <input type='submit' name='p_add' class="groovybutton" value="他の予約をする" title="" style="cursor: pointer; width: 100%;"><br />
                </div>
            {/if}
        {else}
            <div style="display: inline-block; max-width: 33%;">
                <input type="submit" name="p_new" class="groovybutton" value="新規予約を登録" title="" style="cursor: pointer; width: 100%;"><br />
            </div>
        {/if}
        
        <div style="display: inline-block; max-width: 33%;">
            <input type="submit" name="p_info" class="groovybutton" value="会員情報を更新" title="" style="cursor: pointer; width: 100%;"><br />
        </div>
        
        <div style="display: inline-block; max-width: 33%;">
            <input type='submit' name='p_logout' class="groovybutton" value='ログアウト' title="" style="cursor: pointer; width: 100%;"/><br />
        </div>
    </form>
</div>
<!-- Created by jonathanparel, 20160923; RM#1724; -------------------------xx-->
