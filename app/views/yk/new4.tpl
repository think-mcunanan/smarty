<form id='YkNew4Form' action="{$form_action}" method="post">
    <table width='90%' border='0'>
        <tr>
            <td align='center'>
{if $prevpage != 0}
                <br />
                <a href='{$yoyaku_path}/new4/{$sessionid}/0/{$prevpage}/ts:{$ts}'>&lt;&lt; 前へ</a><br /><br />
{/if}
{foreach from=$AvailableTimes key=val item=label}
                <a href='{$yoyaku_path}/new5/{$sessionid}/{$val}/ts:{$ts}'>{$label}</a><br />
{/foreach}
{if $nextpage != 0}
                <br />
                <a href='{$yoyaku_path}/new4/{$sessionid}/0/{$nextpage}/ts:{$ts}'>次へ　&gt;&gt;</a><br />
{/if}
                <br />
            </td>
        </tr>
        <tr>
            <td align='center'>
                <input type='submit' name='p_back' value='戻る' style='width: 150px'><br />
                <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px'><br />
            </td>
        </tr>
    </table>
</form>
