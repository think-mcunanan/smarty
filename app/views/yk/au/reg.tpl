<table width='90%' border='0'>
    <tr>
        <td align='left'>
        <form id='YkRegForm' action="{$form_action}" method="post">・名前<font color='red'>*</font>：<br />
        <input type='text' name='r_name' value='{$name}' size='16' maxlength='50' /><br />
        <br />
        ・メールアドレス：<br />
        {$email}<br />
        <br />
        ・携帯番号<font color='red'>*</font>：<br />
        <input type='text' name='r_phone' value='{$phone}' size='16' maxlength='20' format="*N" style="-wap-input-format:*N" /><br />
        <br />
        ・性別： <select name="r_sex">
            {html_options options=$sex_list selected=$sex}
        </select><br />
        <br />
        {if $showcnumber == 1}
       ・会員No(10桁:分かる場合のみ)<br />
        <input type='text' name='r_kaiin_no' value='' size='16' maxlength='10' format="*N" style="-wap-input-format:*N" /><br />
        <br />
        {/if}
        ・誕生日<br />
        <input type='text' name='r_year' value='{$year}' size='5' maxlength='4' format="*N" style="-wap-input-format:*N" />年 <input type='text' name='r_month' value='{$month}' size='3' maxlength='2' format="*N" style="-wap-input-format:*N" />月 <input type='text' name='r_day' value='{$day}' size='3' maxlength='2' format="*N" style="-wap-input-format:*N" />日<br />
        <br />
        ・パスワード更新：<br />
        　(半角英数字のみ)<br />
        <input type='password' name='r_password1' value='' size='10' maxlength='50' /><br />
        <input type='password' name='r_password2' value='' size='10' maxlength='50' />（確認）<br />
        <br />
        メール配信: <input type='checkbox' name='r_mailkubun' value='1' {if $mailkubun == 1}checked='checked' {/if} /><br />
        <br />
        <br />
        <input type='submit' name='p_reg' value='登録' style='width: 150px' /><br />
        <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px' /><br />
        </form>
        </td>
    </tr>
</table>
