<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------ii -->
<!-- IPHONE -->

<div class="reg">
    <hr id="hr_slim"/>
    
    <div id="bground_mat" style="width: 90%;">
        <form id='YkRegForm' action="{$form_action}" method="post">
                
            ・名前<font color='red'>*</font>：<br />
            <input class="input_center" type='text' name='r_name' value='{$name}' size='16' maxlength='50' />

            <hr id="hr_before-button"/>
            
            {if $setEmailTextbox}
                ・メールアドレス<font color='red'>*</font>：<br />
                <input type="input_center" name='r_email' value='{$email}' size='16' maxlength='50' /><br />
            {else}
                ・メールアドレス：<br />
                {$email}<br />
            {/if}
            <hr id="hr_before-button"/>
            ・電話番号<font color='red'>*</font>：<br />
            <input class="input_center" type='text' name='r_phone' value='{$phone}' size='16' maxlength='20' format="*N" style="-wap-input-format:'*N'" />
                
            <hr id="hr_before-button"/>
            ・性別：
            <select name="r_sex">
                {html_options options=$sex_list selected=$sex}
            </select>
                
            <hr id="hr_before-button"/>
            ・誕生日<br />
            <input class="input_center" type='text' name='r_year' value='{$year}' size='5' maxlength='4' format="*N" style="-wap-input-format:'*N'" />年
            <input class="input_center" type='text' name='r_month' value='{$month}' size='3' maxlength='2' format="*N" style="-wap-input-format:'*N'" />月
            <input class="input_center" type='text' name='r_day' value='{$day}' size='3' maxlength='2' format="*N" style="-wap-input-format:'*N'" />日
            
            {if $setPasswordFields}
                <hr id="hr_before-button"/>
                ・パスワード更新：
                <input class="input_center" type='password' name='r_password1' value='' size='10' maxlength='50' />
                
                <hr id="hr_before-button"/>
                (半角英数字のみ)
                <input class="input_center" type='password' name='r_password2' value='' size='10' maxlength='50' />（確認）
            {/if}
            <hr id="hr_before-button"/>
            メール配信: <input type='checkbox' name='r_mailkubun' value='1' {if $mailkubun == 1}checked='checked' {/if} /><br />

            <hr id="hr_before-button"/>
            <div style="display: inline-block; max-width: 50%">
                <input class="groovybutton" type='submit' name='p_reg' value='登録' style='width: 120px' />
            </div>
            {if $setCancelButton}
                <div style="display: inline-block; max-width: 50%">
                    <input class="groovybutton" type='submit' name='p_cancel' value='キャンセル' style='width: 120px' />
                </div>
            {/if}
        </form>
    </div>
</div>
<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------xx -->