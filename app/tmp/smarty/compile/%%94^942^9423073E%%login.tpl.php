<?php /* Smarty version 2.6.26, created on 2012-01-18 15:16:04
         compiled from /var/www/mobile_station/serverside/app/views/yk/pc/login.tpl */ ?>
<table align="center">
    <tr>
        <td width="460">
        <table width="782" height="333">
            <tr>
                <td background="<?php echo $this->_tpl_vars['html']->url('/img/login/bg_01.gif'); ?>
" style="background-repeat: no-repeat" width="460" height="333" valign="middle">
                <form name='YkLoginForm' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
                <table width="460">
                    <tr>
                        <td colspan="3">
                        <center><font size="+2"><strong>ログイン</strong></font>
                        <hr align="center" width="390" size="1" color="#666666">
                        </center>
                        <br />
                        </td>
                    </tr>
                    <tr>
                        <td width="150" align="right"><strong>ユーザー名</strong></td>
                        <td width="10">&nbsp;</td>
                        <td width="300" align="left"><input name="username" type="text" value="<?php echo $this->_tpl_vars['username']; ?>
" size="30" style="width: 200px;" /></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="150" align="right"><strong>パスワード</strong></td>
                        <td width="10">&nbsp;</td>
                        <td width="300" align="left"><input name="password" type="password" value="<?php echo $this->_tpl_vars['password']; ?>
" size="30" style="width: 200px;" /></td>
                    </tr>
                    <tr>
                        <td colspan="3" align="right"><br />
                        <hr align="center" width="390" size="1" color="#666666" />
                        <br />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td align="center"><input type="submit" name="login" class="groovybutton" value="ログイン" title="" style="cursor: pointer;"><br />
                        </td>
                    </tr>
                </table>
                </form>
                </td>
                <td width="1">&nbsp;</td>
                <td width="305" height="333" background="<?php echo $this->_tpl_vars['html']->url('/img/login/bg_02.gif'); ?>
" style="background-repeat: no-repeat" valign="middle" align="center">
                <div style="margin: auto; width: 220px; text-align: left">
                    <strong>新規登録は、下記のアドレスに空メールを送信してください<br />
                    <br />
                    ユーザー名、パスワードを忘れてしまった場合でもこちらからログインできます</strong>
                </div><br />
                <br />
                <a href='mailto:<?php echo $this->_tpl_vars['salonmail']; ?>
'><?php echo $this->_tpl_vars['salonmail']; ?>
</a> <br />
                <br />
                </td>
            </tr>
        </table>
        </td>
    </tr>
</table>