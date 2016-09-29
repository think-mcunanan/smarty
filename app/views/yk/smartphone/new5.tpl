<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------ii -->
<!-- SMARTPHONE -->

<div id="new5">
    <form id='YkNew5Form' action="{$form_action}" method="post">
        <hr />
        <div class="form" style="font-size: 120%">
            <br />
            担当者:{$trans_staff}<br />
            日付:{$trans_date}<br />
            時間:{$trans_time}<br />
            メニュー選択: <br />
            {$trans_services}
            <br />
        </div>
        <hr />
            
        <div style="display: inline-block; max-width: 33%;">
            <input class="groovybutton" type='submit' name='p_back' value='戻る' style='width: 100px'><br />
        </div>
        
        <div style="display: inline-block; max-width: 33%;">
            <input class="groovybutton" type='submit' name='p_confirm' value='決定' style='width: 100px'><br />
        </div>

        <div style="display: inline-block; max-width: 33%;">
            <input class="groovybutton" type='submit' name='p_cancel' value='キャンセル' style='width: 100px'><br />
        </div>
    </form>
</div>
<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------xx -->