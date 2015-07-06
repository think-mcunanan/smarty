<center>
    <form name='YkNew3Form' action="{$form_action}" method="post">
        <table align="center" border="0px">
            <tr valign="bottom" align="center">
                <td><img src="{$html->url('/img/new/start.gif')}" width="100" height="20"></td>
                <td><img src="{$html->url('/img/new/1shimei.gif')}" width="100" height="25"></td>
                <td><img src="{$html->url('/img/new/2gijutu.gif')}" width="100" height="25"></td>
                <td><img src="{$html->url('/img/new/3hiduke.gif')}" width="105" height="25"></td>
                <td><img src="{$html->url('/img/new/jikan.gif')}" width="120" height="25"></td>
                <td><img src="{$html->url('/img/new/arrowgradopp.gif')}" width="29" height="21"></td>
                <td><img src="{$html->url('/img/new/5touroku.gif')}" width="110" height="25"></td>
                <td><img src="{$html->url('/img/new/end.gif')}" width="100" height="20"></td>
            </tr>
        </table>
        <hr align="center" width="85%" />
        <p><font size="2">※ご予約可能な時間のみ表示されます</font></p>
        <table border='0' width='200'>
            <tr>
                <td colspan='2' align='center'>
                    <table border='1' style='border-collapse: collapse; border: 1px solid #cccccc;' width='760' cellpadding='5'>
{foreach from=$AvailableTimes key=val item=label}
                        <tr>
                            <td align='center'>
                                <font size='4'><a href='{$yoyaku_path}/new5/{$sessionid}/{$val}/ts:{$ts}'>{$label}</a></font>
                            </td>
                        </tr>
{/foreach}
                    </table>
                </td>
            </tr>
            <tr>
                <td align='left'>
{if $prevpage != 0}
                    <br />
                    <a href='{$yoyaku_path}/new4/{$sessionid}/0/{$prevpage}/ts:{$ts}'>&lt;&lt; 前へ</a>
{/if}
                </td>
                <td align='right'>
{if $nextpage != 0}
                    <br />
                    <a href='{$yoyaku_path}/new4/{$sessionid}/0/{$nextpage}/ts:{$ts}'>次へ &gt;&gt;</a>
{/if}
                </td>
            </tr>
        </table>
        <br />
        <br />
        <div class='buttonframe'><input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;"></div>
    </form>
</center>
