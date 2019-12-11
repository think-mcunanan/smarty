<!--Optimized by jonathanparel, 20160907 For Mobile Devices; RM#1724; ii-->
<div class="reg">
    <br />
    <div id="bground_mat" style="width: 80%; min-width: 400px;">
        <form name='YkRegForm' action="{$form_action}" method="post">

            <table border='0' cellpadding='6' align="center">
                <tr>
                    <td align='right' width='150'>名前
                        <font color='red'>*
                        </font>：
                    </td>
                    <td align='left'>
                        <input type='text' name='r_name' value='{$name}' size='16' maxlength='50' />
                    </td>
                </tr>

                <tr>
                    
                    {if $setEmailTextbox}
                        <td align='right' valign='top'>メールアドレス<font color='red'>*</font>：</td>
                        <td align='left'><input type='text' name='r_email' value='{$email}' size='16' maxlength='50' /></td>
                    {else}
                        <td align='right' valign='top'>メールアドレス：</td>
                        <td align='left' valign='top'>
                            <font size='4'>
                                <b>{$email}</b>
                                </font>
                        </td>
                    {/if}
                </tr>

                <tr>
                    <td align='right'>電話番号(携帯)<font color='red'>*</font>：</td>
                    <td align='left'><input type='text' name='r_phone' value='{$phone}' size='16' maxlength='20' /></td>
                </tr>

                <tr>
                    <td align='right'>性別：</td>
                    <td align='left'>

                        <select name="r_sex">
                            {html_options options=$sex_list selected=$sex}
                        </select>
                    </td>
                </tr>

                <tr>
                    <td align='right'>誕生日：</td>
                    <td align='left'>
                        <input type='text' name='r_year' value='{$year}' size='5' maxlength='4' />年
                        <input type='text' name='r_month' value='{$month}' size='3' maxlength='2' />月
                        <input type='text' name='r_day' value='{$day}' size='3' maxlength='2' />日
                    </td>
                </tr>
                {if $setPasswordFields}
                    <tr>
                        <td align='right' valign='top'>パスワード更新：
                            <br />(半角英数字のみ)
                        </td>

                        <td align='left'>
                            <input type='password' name='r_password1' value='' size='16' maxlength='50' />
                            <br />
                            <input type='password' name='r_password2' value='' size='16' maxlength='50' />（確認）
                        </td>
                    </tr>
                {/if}
                <tr>
                    <td align='right' valign='top'>メール配信：</td>
                    <td align='left'>
                        <input type='checkbox' name='r_mailkubun' value='1'
                            {if $mailkubun== 1}
                                checked='checked'
                            {/if} />
                </tr>
            </table>

            <hr id="hr_before-button"/>
            {if $setCancelButton}
                <input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;">
            {/if}
            <input type="submit" name="p_reg" class="groovybutton" value="登録" title="" style="cursor: pointer;">
        </form>
    </div>
</div>
<!--Optimized by jonathanparel, 20160907 For Mobile Devices; RM#1724; xx-->
