<?php /* Smarty version 2.6.26, created on 2012-12-07 14:06:17
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/login.tpl */ ?>
<table width='90%' border='0'>
    <tr>
        <td>
            新規登録は、下記のアドレスに空メールを送信してください<br />
            ユーザー名、パスワードを忘れてしまった場合でもこちらからログインできます<br />
            <a href='mailto:<?php echo $this->_tpl_vars['salonmail']; ?>
'><?php echo $this->_tpl_vars['salonmail']; ?>
</a><br />
            <br />
            <hr />
            <br />
            <form id='YkLoginForm' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
                会員ID：<br />
                <input type='text' name='username' value='<?php echo $this->_tpl_vars['username']; ?>
' istyle="3" format="*x" mode="alphabet" /><br />
                <?php echo $this->_tpl_vars['logincomment']; ?>
<br />
                <br />
                パスワード：<br />
                <input type='password' name='password' value='<?php echo $this->_tpl_vars['password']; ?>
' istyle="3" format="*x" mode="alphabet" /><br />
                <input type='submit' name='login' value='ログイン' /><br />
            </form>
        </td>
    </tr>
</table>
<br />
#<a href='<?php echo $this->_tpl_vars['sitepath']; ?>
'><?php echo $this->_tpl_vars['storename']; ?>
</a><br />
<br />