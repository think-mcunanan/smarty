<!-- Created by jonathanparel, 20160923; RM#1724; -------------------------ii-->
<!-- IPHONE -->

<div class="mypage">
    <hr id="hr_slim"/>
    <div id="bground_mat">
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
                (ご予約の{$cancellim}時間前のため、webからのキャンセルはできません。）
                <hr id="hr_slim"/>
            {else}
                <form name='YkMyPageForm' action="{$form_action}" method="post">
                    <input class="groovybutton2" type="submit" name="p_delete{$itemdata.transdate}" value="この予約をキャンセル" title="" style="cursor: pointer; width: 80%;">
                    <hr id="hr_slim"/>
                </form>
            {/if}                                            

        {foreachelse}
            予約情報：なし
            <hr id="hr_slim"/>
        {/foreach}

        {if $points1 || $points2}
            {$points1}{$points2}
            <hr id="hr_slim"/>
        {/if}

        <hr id="hr_before-button"/>
        <form name='YkMyPageForm' action="{$form_action}" method="post">
            {if $nextyoyaku == 1}
                {if $addyoyakub == 1}
                    <input type='submit' name='p_add' class="groovybutton" value="他の予約をする" title="" style="cursor: pointer; width: 80%;">
                {/if}
            {else}
                <input type="submit" name="p_new" class="groovybutton" value="新規予約を登録" title="" style="cursor: pointer; width: 80%;">
            {/if}
            <hr id="hr_slim"/>
            <input type="submit" name="p_info" class="groovybutton" value="会員情報を更新" title="" style="cursor: pointer; width: 80%;">
            
            <hr id="hr_slim"/>
            <input type='submit' name='p_logout' class="groovybutton" value='ログアウト' title="" style="cursor: pointer; width: 80%;"/>
        </form>
    </div>
</div>
<!-- Created by jonathanparel, 20160923; RM#1724; -------------------------xx-->
