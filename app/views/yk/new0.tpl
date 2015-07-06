<form id='YkNew0Form' action="{$form_action}" method="post">
    <table width='90%' border='0'>
        <tr>
            <td align='left'><br />
{if $error != 1}
               　施術を選択して下さい：<br />
                {html_radios name='syscode' options=$gyoshukubun_list selected = $gyoshukubun separator='<br />'}
                <input type='submit' name='p_next' value='次へ' style='width: 150px'><br />
{/if}
                <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px'><br />
            </td>
        </tr>
    </table>
</form>
