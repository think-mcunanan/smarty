<?php /* Smarty version 2.6.26, created on 2013-03-27 15:00:16
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/au/login.tpl */ ?>
<table width='90%' border='0'>
    <tr>
        <td>
            <p>●新規会員登録</p>
            新規登録は、<a href='mailto:<?php echo $this->_tpl_vars['salonmail']; ?>
'><?php echo $this->_tpl_vars['salonmail']; ?>
</a>　に<br />空メールを送信してください。<br />
            ユーザー名、パスワードを忘れてしまった場合も、<br />こちらに空メールを送信することでログインが可能です。<br /><br />
            ※迷惑メール防止のための設定をしている場合は、<br />下記ドメインを許可する設定を行ってください｡<br />
            <FORM action="" method="post"><FONT size="2"><INPUT type="text" value="@bmy.jp" size="19"></FONT></FORM>
            <hr />
            <p>●会員ログイン</p>
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