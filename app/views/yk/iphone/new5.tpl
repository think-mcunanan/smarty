<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------ii -->
<!-- IPHONE -->

<div class="new5">
    <hr id="hr_slim"/>
    
    <div id="bground_mat" style="font-size: 120%; width: 90%;">
        <form id='YkNew5Form' action="{$form_action}" method="post">
            担当者:{$trans_staff}<br />
            日付:{$trans_date}<br />
            時間:{$trans_time}<br />
            
            <hr id="hr_before-button">
            <div id="title" style="font-size: 100%;">
                <u>メニュー選択:</u>
            </div>
            <hr style="width: 50%; margin: auto; padding-top: 1%; padding-bottom: 1%; height: 1px; visibility: hidden;"/>
            
            <div style="text-align: left; padding-left: 10px;">
                {$trans_services}
            </div>

            <hr id="hr_before-button"/>
            <input class="groovybutton" type='submit' name='p_back' value='戻る' style='width: 80%'><br />
            <hr id="hr_slim"/>
            <input class="groovybutton" type='submit' name='p_confirm' value='決定' style='width: 80%'><br />
            <hr id="hr_slim"/>
            <input class="groovybutton" type='submit' name='p_cancel' value='キャンセル' style='width: 80%'><br />
        </form>
    </div>
</div>
<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------xx -->