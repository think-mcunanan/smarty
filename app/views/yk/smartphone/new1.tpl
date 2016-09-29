<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------ii -->
<!-- SMARTPHONE -->

<div id="new1">
    <form id='YkNew1Form' action="{$form_action}" method="post">
        <hr />
        <div class="form"  style="font-size: 120%">
            <br />
            {if $error != 1}
                担当者：<br />
                {html_options name="staff" options="$staff_name_list" selected="$staff"}<br /><br />
            {/if}
        </div>    
                
        <hr />
        <div style="display: inline-block; max-width: 50%;">
            <input class="groovybutton" type='submit' name='p_cancel' value='キャンセル' style="width: 100px"><br />
        </div>
        
        {if $error != 1}        
            <div style="display: inline-block; max-width: 50%;">
                <input class="groovybutton" type='submit' name='p_next' value='次へ' style="width: 100px;"><br />
            </div>
        {/if}
    </form>
</div>
<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------xx -->