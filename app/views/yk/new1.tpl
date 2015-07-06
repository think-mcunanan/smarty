<form id='YkNew1Form' action="{$form_action}" method="post">
    <table width='90%' border='0'>
        <tr>
            <td align='left'><br />
{if $error != 1}
                担当者：<br />
                {html_options name="staff" options="$staff_name_list" selected="$staff"}<br /><br />
                <input type='submit' name='p_next' value='次へ' style='width: 150px'><br />
{/if}
                <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px'><br />
            </td>
        </tr>
    </table>
</form>
