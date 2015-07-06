<?php /* Smarty version 2.6.26, created on 2013-01-12 10:43:46
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/softbank/yoyakumail.tpl */ ?>
<center>
<?php if ($this->_tpl_vars['mail_send'] == 1): ?>
        ご利用ありがとうございました。
<?php else: ?>
        ご予約ありがとうございました。<br /><br />
        確認メールを送信する場合は、下記の「確認メールを送る」ボタンを押してください<br />
<?php endif; ?>
        <br />
        <br />

            <form name='YkRegForm' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
            <?php if ($this->_tpl_vars['mail_send'] == 0): ?>
            <input type="submit" name="p_sendmail" class="groovybutton" value="確認メールを送る" title="" style="cursor: pointer;"> &nbsp; 
            <?php endif; ?>
            <input type="submit" name="p_cancel" class="groovybutton" value="トップ画面に戻る" title="" style="cursor: pointer;"> &nbsp;
            <input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['companyid']; ?>
" />
            <input type="hidden" name="scd" value="<?php echo $this->_tpl_vars['storecode']; ?>
" />
            </form>
</center>