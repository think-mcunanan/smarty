<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------ii -->
<!-- IPHONE -->

<div class="new1">
    <hr id="hr_slim"/>
    <div id="bground_mat">
        <form id='YkNew1Form' action="{$form_action}" method="post">
            
            {if $error != 1}
                <div id="title">
                    担当者：
                </div>
                <hr id="hr_slim"/>
                {html_options name="staff" options="$staff_name_list" selected="$staff"}
                <hr id="hr_slim"/>
            {/if}
                
            <hr id="hr_before-button"/>
            <div style="display: inline-block; max-width: 50%;">
                <input class="groovybutton" type='submit' name='p_cancel' value='キャンセル' style="width: 120px"><br />
            </div>

            {if $error != 1}        
                <div style="display: inline-block; max-width: 50%;">
                    <input class="groovybutton" type='submit' name='p_next' value='次へ' style="width: 120px;"><br />
                </div>
            {/if}
        </form>
    </div>
</div>
<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------xx -->