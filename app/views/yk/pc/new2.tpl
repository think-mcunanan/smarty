<!--Optimized by jonathanparel, 20160907 For Mobile Devices; RM#1724; ii-->

<div class="new2">
    <br />
    <form name='YkNew1Form' action="{$form_action}" method="post">
        
        <div>
            <img src="{$html->url('/img/new/start.gif')}" height="20">
            <img src="{$html->url('/img/new/1shimei.gif')}" height="25">
            <img src="{$html->url('/img/new/gijutu.gif')}" height="25">
            <img src="{$html->url('/img/new/arrowgradopp.gif')}" height="21">
            <img src="{$html->url('/img/new/3hiduke.gif')}" height="25">
            <img src="{$html->url('/img/new/4jikan.gif')}" height="25">
            <img src="{$html->url('/img/new/5touroku.gif')}" height="25">
            <img src="{$html->url('/img/new/end.gif')}" height="20">
        </div>
            
        <hr id="hr_before-button">
        
        <div id="bground_mat" style="width:60%; min-width: 200px">
            <table border='1' style='border-collapse: collapse; border: 1px solid #dddddd;' width=100% cellpadding='5' align="center">
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
                                <td><input type="checkbox" name="services[]" value="{$srvkey}"
                                    {if in_array($srvkey,$services)}checked="checked"
                                    {/if} />
                                </td>

                                <td align='left'>{$service_item[0]}</td>
                                <td align='center'>
                                    {if $service_item[2]}{$service_item[2]}円（税込）
                                    {/if}
                                </td>
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
                                <td><input type="checkbox" name="services[]" value="{$srvkey}"
                                    {if in_array($srvkey,$services)}checked="checked"
                                    {/if} />
                                </td>
                                <td colspan='3' align='left'>{$service_item[0]}</td>
                            </tr>
                        {/foreach}
                    {/foreach}
                {/if}
            </table>

            <hr id="hr_before-button">
            <input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;">
            <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;">
            <input type="submit" name="p_next" class="groovybutton" value="次へ" title="" style="cursor: pointer;">

            <input type="hidden" name="cid" value="{$companyid}" />
            <input type="hidden" name="scd" value="{$storecode}" />
        </div>
    </form>
</div>
<!--Optimized by jonathanparel, 20160907 For Mobile Devices; RM#1724; xx-->