<?php /* Smarty version 2.6.26, created on 2013-09-19 22:44:49
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/pc/unreg.tpl */ ?>
<center>
    <form name='YkRegForm' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
<?php if ($this->_tpl_vars['complete'] === true): ?>
        ご利用ありがとうございました
<?php else: ?>
        本当に解約しますか？
<?php endif; ?>
        <br />
        <br />
<?php if ($this->_tpl_vars['complete'] === false): ?>
        <div class='buttonframe'>
            <input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_unreg" class="groovybutton" value="解約" title="" style="cursor: pointer;">
        </div>
<?php endif; ?>
    </form>
</center>