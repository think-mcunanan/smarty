<center>
    <form name='YkNew1Form' action="{$form_action}" method="post">
        <table align="center" border="0px">
            <tr valign="bottom" align="center">
                <td><img src="{$html->url('/img/new/start.gif')}" width="100" height="20"></td>
                <td><img src="{$html->url('/img/new/1shimei.gif')}" width="100" height="25"></td>
                <td><img src="{$html->url('/img/new/gijutu.gif')}" width="100" height="25"></td>
                <td><img src="{$html->url('/img/new/arrowgradopp.gif')}" width="29" height="21"></td>
                <td><img src="{$html->url('/img/new/3hiduke.gif')}" width="105" height="25"></td>
                <td><img src="{$html->url('/img/new/4jikan.gif')}" width="120" height="25"></td>
                <td><img src="{$html->url('/img/new/5touroku.gif')}" width="110" height="25"></td>
                <td><img src="{$html->url('/img/new/end.gif')}" width="100" height="20"></td>
            </tr>
        </table>
        <hr align="center" width="85%" />
        <br />
        <table border='1' style='border-collapse: collapse; border: 1px solid #dddddd;' width='760' cellpadding='5'>
{if $menu_name_only != 1}
            <tr bgcolor='#efefef'>
                <td colspan='2' align='left'>メニュー</td>
                <td align='center'>料金</td>
                <td align='center'>施術時間</td>
            </tr>
    {foreach from=$services_list key=daibunrui item=services_sublist}
            <tr bgcolor='#efefef'>
                <td colspan='4' align='left'>{$daibunrui}</td>
            </tr>
        {foreach from=$services_sublist key=srvkey item=service_item}
            <tr>
                <td><input type="checkbox" name="services[]" value="{$srvkey}" {if in_array($srvkey,$services)}checked="checked" {/if} /></td>
                <td align='left'>{$service_item[0]}</td>
                <td align='center'>{if $service_item[2]}{$service_item[2]}円{/if}</td>
                <td align='center'>{$service_item[1]}分</td>
            </tr>
        {/foreach}
    {/foreach}
{else}
            <tr bgcolor='#efefef'>
                <td colspan='4' align='left'>メニュー</td>
            </tr>
    {foreach from=$services_list key=daibunrui item=services_sublist}
            <tr bgcolor='#efefef'>
                <td colspan='4' align='left'>{$daibunrui}</td>
            </tr>
        {foreach from=$services_sublist key=srvkey item=service_item}
            <tr>
                <td><input type="checkbox" name="services[]" value="{$srvkey}" {if in_array($srvkey,$services)}checked="checked" {/if} /></td>
                <td colspan='3' align='left'>{$service_item[0]}</td>
            </tr>
        {/foreach}
    {/foreach}
{/if}
        </table>
        <br />
        <div class='buttonframe'><input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_next" class="groovybutton" value="次へ" title="" style="cursor: pointer;"></div>
           <input type="hidden" name="cid" value="{$companyid}" />
           <input type="hidden" name="scd" value="{$storecode}" />
    </form>
</center>
