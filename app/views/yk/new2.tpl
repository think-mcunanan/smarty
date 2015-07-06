<form id='YkNew2Form' action="{$form_action}" method="post">
    <table width='90%' border='0'>
        <tr>
            <td align='left'><br />
{foreach from=$services_list key=daibunrui item=services_sublist}
                <br />
                <u>{$daibunrui}</u><br />
    {foreach from=$services_sublist key=srvkey item=service_item}
                <label>
                    <input type="checkbox" name="services[]" value="{$srvkey}" {if in_array($srvkey,$services)}checked="checked" {/if} /> {$service_item[0]}{if $menu_name_only != 1} ({$service_item[1]}分){/if}
                </label><br />
    {/foreach}
{/foreach}
                <br />
                <input type='submit' name='p_next' value='次へ' style='width: 150px' /><br />
                <input type='submit' name='p_back' value='戻る' style='width: 150px' /><br />
                <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px' /><br />
            </td>
        </tr>
    </table>
</form>
