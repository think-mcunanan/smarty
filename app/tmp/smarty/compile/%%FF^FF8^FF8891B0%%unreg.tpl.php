<?php /* Smarty version 2.6.26, created on 2013-05-16 14:00:18
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/smartphone/unreg.tpl */ ?>
<table width='90%' border='0'>
    <tr>
        <td align='left'>
            <form id='YkRegForm' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
<?php if ($this->_tpl_vars['complete'] === true): ?>
                ご利用ありがとうございました
<?php else: ?>
                本当に解約しますか？
<?php endif; ?>
                <br />
<?php if ($this->_tpl_vars['complete'] === false): ?>
                <br />
                <input type='submit' name='p_unreg' value='解約' /><br />
                <input type='submit' name='p_cancel' value='キャンセル' /><br />
<?php endif; ?>
            </form>
        </td>
    </tr>
</table>