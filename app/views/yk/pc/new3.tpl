<!--Optimized by jonathanparel, 20160909 For Mobile Devices; RM#1724; ii-->
<div class="new3" align="center">
    <form name='YkNew2Form' action="{$form_action}" method="post">
        <div>
            <img src="{$html->url('/img/new/start.gif')}" height="20">
            <img src="{$html->url('/img/new/1shimei.gif')}" height="25">
            <img src="{$html->url('/img/new/2gijutu.gif')}" height="25">
            <img src="{$html->url('/img/new/hiduke.gif')}" height="25">
            <img src="{$html->url('/img/new/arrowgradopp.gif')}" height="21">
            <img src="{$html->url('/img/new/4jikan.gif')}" height="25">
            <img src="{$html->url('/img/new/5touroku.gif')}" height="25">
            <img src="{$html->url('/img/new/end.gif')}" height="20">
        </div>
        
        <hr width="75%">
        <br />
        
        <table border='1' style='border-collapse: collapse;' align="center">
            <tr align='center'>
                <td>{if $prevlink != ""} <font size='5'><a href='{$yoyaku_path}/new3/{$sessionid}/{$prevlink}/ts:{$ts}'>&lt;</a></font> {else} <font color='#AAAAAA' size='5'>&lt;</font> {/if}</td>
                <td colspan='5' align='center'>{$calendar_header}</td>
                <td>{if $nextlink != ""} <font size='5'><a href='{$yoyaku_path}/new3/{$sessionid}/{$nextlink}/ts:{$ts}'>&gt;</a></font> {else} <font color='#AAAAAA' size='5'>&gt;</font> {/if}</td>
            </tr>
            <tr align='center' bgcolor='#eeeeee'>
                <td width='90' bgcolor="#FFDCD9"><font size='5'>日</font></td>
                <td width='90'><font size='5'>月</font></td>
                <td width='90'><font size='5'>火</font></td>
                <td width='90'><font size='5'>水</font></td>
                <td width='90'><font size='5'>木</font></td>
                <td width='90'><font size='5'>金</font></td>
                <td width='90' bgcolor="#D5D9FF"><font size='5'>土</font></td>
            </tr>
            {foreach from=$calendar item=week}
            <tr align='center' height='45'>
                {foreach from=$week item=itm}
                <td>{if $itm[0] > 0} {if $itm[1] != ""} <font size='5'><a href='{$yoyaku_path}/new4/{$sessionid}/{$itm[1]}/ts:{$ts}'>{$itm[0]}</a></font> {else} <font color='#AAAAAA' size='5'>{$itm[0]}</font> {/if} {/if}</td>
                {/foreach}
            </tr>
            {/foreach}
        </table>
        <br />
        
        <hr width="75%">
        <div class='buttonframe'>
            <input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;">
            <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;">
        </div>
    </form>
</div>
<!--Optimized by jonathanparel, 20160909 For Mobile Devices; RM#1724; xx-->
