<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------ii -->
<!-- SMARTPHONE -->

<div id="new4">
    <form id='YkNew4Form' action="{$form_action}" method="post">
        <hr />
        <div class="form">
            <br />
            {if $prevpage != 0}
                <br />
                <a href='{$yoyaku_path}/new4/{$sessionid}/0/{$prevpage}/ts:{$ts}'>&lt;&lt; 前へ</a>
                <br />
            {/if}
            
            {foreach from=$AvailableTimes key=val item=label}
                <a href='{$yoyaku_path}/new5/{$sessionid}/{$val}/ts:{$ts}' class="groovybutton2">{$label}</a>
                <br />
                <hr class="slim_hr"/>
            {/foreach}
            
            {if $nextpage != 0}
                <br />
                <a href='{$yoyaku_path}/new4/{$sessionid}/0/{$nextpage}/ts:{$ts}'>次へ　&gt;&gt;</a>
                <br />
            {/if}
            <br />
        </div>
                    
        <hr />
        <div style="display: inline-block; max-width: 50%;">
            <input class="groovybutton" type='submit' name='p_back' value='戻る' style='width: 100px'><br />
        </div>

        <div style="display: inline-block; max-width: 50%;">
            <input class="groovybutton" type='submit' name='p_cancel' value='キャンセル' style='width: 100px'><br />
        </div>
    </form>
</div>
<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------xx -->