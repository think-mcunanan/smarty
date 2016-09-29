<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------ii -->
<!-- SMARTPHONE -->

<div id="reg">
    <form id='YkRegForm' action="{$form_action}" method="post">
        <hr />
        
        <div class="form">
            <br />
            <div>
                ・名前<font color='red'>*</font>：<br />
                <input class="input_center" type='text' name='r_name' value='{$name}' size='16' maxlength='50' />
            </div>
            
            <hr class="slim_hr"/>
            <div>
                ・メールアドレス：<br />
                {$email}<br />
            </div>
            <hr class="slim_hr"/>
            
            <div>
                ・電話番号<font color='red'>*</font>：<br />
                <input class="input_center" type='text' name='r_phone' value='{$phone}' size='16' maxlength='20' format="*N" style="-wap-input-format:'*N'" />
            </div>
            <hr class="slim_hr"/>
            
            <div>・性別：
                <select name="r_sex">
                    {html_options options=$sex_list selected=$sex}
                </select>
            </div>
            <hr class="slim_hr"/>
            
            <div>
                ・誕生日<br />
                <input class="input_center" type='text' name='r_year' value='{$year}' size='5' maxlength='4' format="*N" style="-wap-input-format:'*N'" />年
                <input class="input_center" type='text' name='r_month' value='{$month}' size='3' maxlength='2' format="*N" style="-wap-input-format:'*N'" />月
                <input class="input_center" type='text' name='r_day' value='{$day}' size='3' maxlength='2' format="*N" style="-wap-input-format:'*N'" />日
            </div>
            <hr class="slim_hr"/>
            
            <div>
                ・パスワード更新：
                <input class="input_center" type='password' name='r_password1' value='' size='10' maxlength='50' />
                <hr class="slim_hr"/>
                (半角英数字のみ)
                <input class="input_center" type='password' name='r_password2' value='' size='10' maxlength='50' />（確認）
            </div>
            <hr class="slim_hr"/>
            
            <div>
                メール配信: <input type='checkbox' name='r_mailkubun' value='1' {if $mailkubun == 1}checked='checked' {/if} /><br />
                <br />
            </div>    
        </div>
                
        <hr />
        <div style="display: inline-block; max-width: 50%">
            <input class="groovybutton" type='submit' name='p_reg' value='登録' style='width: 100px' />
        </div>
        
        <div style="display: inline-block; max-width: 50%">
            <input class="groovybutton" type='submit' name='p_cancel' value='キャンセル' style='width: 100px' />
        </div>
    </form>
    
</div>
<!-- Created by jonathanparel, 20160916; RM#1744; --------------------------xx -->