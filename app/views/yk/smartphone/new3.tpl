<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------ii -->
<!-- SMARTPHONE -->

<div id="new3">
    <hr />
    
    <form id='YkNew3Form' action="{$form_action}" method="post">
        <div class="form" style="padding-left: 10px; padding-right: 10px;">
            <br />
        <table width='100%' border='1' >
            <tr align='center'>
                <td>
                    {if $prevlink != ""}
                        <a href='{$yoyaku_path}/new3/{$sessionid}/{$prevlink}/ts:{$ts}'>&lt;</a>
                    {else}
                        <font color='#AAAAAA'>&lt;</font>
                    {/if}
                </td>
                
                <td colspan='5' align='center'>
                    {$calendar_header}
                </td>
                
                <td>
                    {if $nextlink != ""}
                        <a href='{$yoyaku_path}/new3/{$sessionid}/{$nextlink}/ts:{$ts}'>&gt;</a>
                    {else}
                        <font color='#AAAAAA'>&gt;</font>
                    {/if}
                </td>
            </tr>
            
            <tr align='center'>
                <td>
                    日
                </td>
                <td>
                    月
                </td>
                <td>
                    火
                </td>
                <td>
                    水
                </td>
                <td>
                    木
                </td>
                <td>
                    金
                </td>
                <td>
                    土
                </td>
            </tr>
            
            {foreach from=$calendar item=week}
                    <tr align='center'>
                        {foreach from=$week item=itm}
                            <td>   
                                {if $itm[0] > 0}
                                    {if $itm[1] != ""}
                                        <a href='{$yoyaku_path}/new4/{$sessionid}/{$itm[1]}/ts:{$ts}'>{$itm[0]}</a>
                                    {else}
                                        <font color='#AAAAAA'>{$itm[0]}</font>
                                    {/if}
                                {/if}
                            </td>
                        {/foreach}
                    </tr>
            {/foreach}
        </table>
        <br />
        </div>

        <hr />
        <div style="display: inline-block; max-width: 50%">
            <input class="groovybutton" type='submit' name='p_back' value='戻る' style='width: 100px' align="left">
        </div>
        
        <div style="display: inline-block; max-width: 50%">
            <input class="groovybutton" type='submit' name='p_cancel' value='キャンセル' style='width: 100px'><br />
        </div>
    </form>
</div>
<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------xx -->