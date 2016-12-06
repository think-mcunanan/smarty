<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------ii -->
<!-- IPHONE -->

<div class="new2">
    <hr id="hr_slim"/>
    <div id="bground_mat">
        <form id='YkNew2Form' action="{$form_action}" method="post">
            {foreach from=$services_list key=daibunrui item=services_sublist}

                <div style="text-align:left; padding-left: 10%;">
                    <u>{$daibunrui}</u>
                    <br />
                    {foreach from=$services_sublist key=srvkey item=service_item}
                        <label>
                            <input type="checkbox" name="services[]" value="{$srvkey}"
                            {if in_array($srvkey,$services)}checked="checked" {/if} />
                            {$service_item[0]}
                            {if $menu_name_only != 1} ({$service_item[1]}分){/if}
                        </label>
                        <br />
                    {/foreach}

                    
                </div>
                <hr style="width: 50%; margin: auto; padding-top: 1%; padding-bottom: 1%; height: 1px; visibility: hidden;"/>
            {/foreach}

            <hr id="hr_before-button"/>
            <input class="groovybutton" type='submit' name='p_back' value='戻る' style='width: 80%' />
            <hr id="hr_slim"/>
            <input class="groovybutton" type='submit' name='p_cancel' value='キャンセル' style='width: 80%' />
            <hr id="hr_slim"/>
            <input class="groovybutton" type='submit' name='p_next' value='次へ' style='width: 80%' /><br />
        </form>
    </div>
</div>