<?php /* Smarty version 2.6.26, created on 2013-01-26 14:30:53
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/pc/login.tpl */ ?>
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
" size="30" style="width: 200px;" /><br /><font size ="2"><?php echo $this->_tpl_vars['logincomment']; ?>
</font></td>
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
                        <td colspan="1">&nbsp;</td>
                        <td colspan="2" align="left"><input type="submit" name="login" class="groovybutton" value="ログイン" title="" style="cursor: pointer;"><br />
                        </td>
                    </tr>
                </table>
                </form>
                </td>
                <td width="1">&nbsp;</td>
                <td width="305" height="333" background="<?php echo $this->_tpl_vars['html']->url('/img/login/bg_02.gif'); ?>
" style="background-repeat: no-repeat;color:#000;" valign="middle" align="center">
                <div style="margin: auto; width: 220px; text-align: left">
                    <strong>初めてのお客様は、下記のアドレスに<span style="color:red;">空メールを送信</span>して、会員登録をお願いします。</strong><br />
                    <br />
                    <center><a href='mailto:<?php echo $this->_tpl_vars['salonmail']; ?>
'><?php echo $this->_tpl_vars['salonmail']; ?>
</a></center><br /><br />
                    ★ユーザー名、またはパスワードを忘れてしまった場合も、空メールを送信することでログインが可能です。
                    <br /><br />
                    ★迷惑メール防止のための設定をしている場合は、下記ドメインを許可する設定を行ってください｡
                </div>

                <FORM action="" method="post"><FONT size="2"><INPUT type="text" value="@bmy.jp" size="19"></FONT></FORM>
                <br />
                </td>
            </tr>
        </table>
        </td>
    </tr>
</table>