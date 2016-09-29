<!--Optimized by jonathanparel, 20160909 For Mobile Devices; RM#1724; ii-->
<div class="new4" align="center">
    <form name='YkNew3Form' action="{$form_action}" method="post">
        
            <div>
                <img src="{$html->url('/img/new/start.gif')}" height="20">
                <img src="{$html->url('/img/new/1shimei.gif')}" height="25">
                <img src="{$html->url('/img/new/2gijutu.gif')}" height="25">
                <img src="{$html->url('/img/new/3hiduke.gif')}" height="25">
                <img src="{$html->url('/img/new/jikan.gif')}" height="25">
                <img src="{$html->url('/img/new/arrowgradopp.gif')}" height="21">
                <img src="{$html->url('/img/new/5touroku.gif')}" height="25">
                <img src="{$html->url('/img/new/end.gif')}" height="20">
            </div>
        
        <hr width="75%">
        
        <p><font size="2">※ご予約可能な時間のみ表示されます</font></p>
        
        <div>
            <table border='1' style='border-collapse: collapse; border: 1px solid #cccccc;' width='80%' cellpadding='5'>
                {foreach from=$AvailableTimes key=val item=label}
                    <tr>
                        <td align='center'>
                            <font size='4'><a href='{$yoyaku_path}/new5/{$sessionid}/{$val}/ts:{$ts}'>{$label}</a></font>
                        </td>
                    </tr>
                {/foreach}
            </table>
        
            <span style="margin-left:auto;">
                {if $prevpage != 0}
                    <br />
                    <a href='{$yoyaku_path}/new4/{$sessionid}/0/{$prevpage}/ts:{$ts}'>&lt;&lt; 前へ</a>
                {/if}
            </span>

            <span style="margin-right:auto;">
                {if $nextpage != 0}
                    <br />
                    <a href='{$yoyaku_path}/new4/{$sessionid}/0/{$nextpage}/ts:{$ts}'>次へ &gt;&gt;</a>
                {/if}
            </span>
        </div>
        
        <hr width="75%">
        <div class='buttonframe'>
            <input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;">
            <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;">
        </div>
    </form>
</div>
<!--Optimized by jonathanparel, 20160909 For Mobile Devices; RM#1724; xx-->