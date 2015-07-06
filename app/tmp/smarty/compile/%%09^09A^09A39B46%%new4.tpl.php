<?php /* Smarty version 2.6.26, created on 2013-03-10 16:34:37
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/smartphone/new4.tpl */ ?>
<form id='YkNew4Form' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
    <table width='90%' border='0'>
        <tr>
            <td align='center'>
<?php if ($this->_tpl_vars['prevpage'] != 0): ?>
                <br />
                <a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new4/<?php echo $this->_tpl_vars['sessionid']; ?>
/0/<?php echo $this->_tpl_vars['prevpage']; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'>&lt;&lt; 前へ</a><br /><br />
<?php endif; ?>
<?php $_from = $this->_tpl_vars['AvailableTimes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val'] => $this->_tpl_vars['label']):
?>
                <a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new5/<?php echo $this->_tpl_vars['sessionid']; ?>
/<?php echo $this->_tpl_vars['val']; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'><?php echo $this->_tpl_vars['label']; ?>
</a><br />
<?php endforeach; endif; unset($_from); ?>
<?php if ($this->_tpl_vars['nextpage'] != 0): ?>
                <br />
                <a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new4/<?php echo $this->_tpl_vars['sessionid']; ?>
/0/<?php echo $this->_tpl_vars['nextpage']; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'>次へ　&gt;&gt;</a><br />
<?php endif; ?>
                <br />
            </td>
        </tr>
        <tr>
            <td align='center'>
                <input type='submit' name='p_back' value='戻る' style='width: 150px'><br />
                <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px'><br />
            </td>
        </tr>
    </table>                <input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['companyid']; ?>
" />
                <input type="hidden" name="scd" value="<?php echo $this->_tpl_vars['storecode']; ?>
" />
                <input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['companyid']; ?>
" />
                <input type="hidden" name="scd" value="<?php echo $this->_tpl_vars['storecode']; ?>
" />
</form>