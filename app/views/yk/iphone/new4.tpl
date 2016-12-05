<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------ii -->
<!-- IPHONE -->

<div class="new4">
    <hr id="hr_slim"/>
    
    <div id="bground_mat">
        <form id='YkNew4Form' action="{$form_action}" method="post">
            {if $prevpage != 0}
                <a href='{$yoyaku_path}/new4/{$sessionid}/0/{$prevpage}/ts:{$ts}'>&lt;&lt; 前へ</a>
            {/if}

            {foreach from=$AvailableTimes key=val item=label}
                <a href='{$yoyaku_path}/new5/{$sessionid}/{$val}/ts:{$ts}' class="groovybutton2">{$label}</a>
                <hr style="width: 50%; margin: auto; padding-top: 1%; padding-bottom: 1%; height: 1px; visibility: hidden;"/>
            {/foreach}

            <br />
            {if $nextpage != 0}
                <a href='{$yoyaku_path}/new4/{$sessionid}/0/{$nextpage}/ts:{$ts}'>次へ　&gt;&gt;</a>
            {/if}
            <br />
            

            <hr id="hr_before-button"/>
            <div style="display: inline-block; max-width: 50%;">
                <input class="groovybutton" type='submit' name='p_back' value='戻る' style='width: 120px'><br />
            </div>

            <div style="display: inline-block; max-width: 50%;">
                <input class="groovybutton" type='submit' name='p_cancel' value='キャンセル' style='width: 120px'><br />
            </div>
        </form>
    </div>
</div>
<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------xx -->