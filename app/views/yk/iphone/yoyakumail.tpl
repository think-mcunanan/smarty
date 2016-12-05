<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------ii -->
<!-- IPHONE -->

<div class="yoyakumail">
    <hr id="hr_slim"/>
    
    <div id="bground_mat" style="width: 90%;">
        {if $mail_send == 1}
            ご利用ありがとうございました。
        {else}
            ご予約ありがとうございました。<br />
            <br />
            確認メールを送信する場合は、下記の「確認メールを送る」ボタンを押してください<br />
        {/if}
        <br />

        <hr id="hr_before-button"/>
        <form name='YkRegForm' action="{$form_action}" method="post">
            {if $mail_send == 0}
                <input type="submit" name="p_sendmail" class="groovybutton" value="確認メールを送る" title="" style="cursor: pointer; width: 180px;">
                <hr id="hr_slim"/>
            {/if}

            <input type="submit" name="p_cancel" class="groovybutton" value="トップ画面に戻る" title="" style="cursor: pointer; width: 180px;">
            <input type="hidden" name="cid" value="{$companyid}" />
            <input type="hidden" name="scd" value="{$storecode}" />
        </form>
    </div>
</div>
