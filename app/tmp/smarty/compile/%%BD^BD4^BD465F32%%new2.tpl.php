<?php /* Smarty version 2.6.26, created on 2013-03-13 17:22:45
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/au/new2.tpl */ ?>
<form id='YkNew2Form' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
    <table width='90%' border='0'>
        <tr>
            <td align='left'><br />
<?php $_from = $this->_tpl_vars['services_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['daibunrui'] => $this->_tpl_vars['services_sublist']):
?>
                <br />
                <u><?php echo $this->_tpl_vars['daibunrui']; ?>
</u><br />
    <?php $_from = $this->_tpl_vars['services_sublist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['srvkey'] => $this->_tpl_vars['service_item']):
?>
                <label>
                    <input type="checkbox" name="services[]" value="<?php echo $this->_tpl_vars['srvkey']; ?>
" <?php if (in_array ( $this->_tpl_vars['srvkey'] , $this->_tpl_vars['services'] )): ?>checked="checked" <?php endif; ?> /> <?php echo $this->_tpl_vars['service_item'][0]; ?>
<?php if ($this->_tpl_vars['menu_name_only'] != 1): ?> (<?php echo $this->_tpl_vars['service_item'][1]; ?>
分)<?php endif; ?>
                </label><br />
    <?php endforeach; endif; unset($_from); ?>
<?php endforeach; endif; unset($_from); ?>
                <br />
                <input type='submit' name='p_next' value='次へ' style='width: 150px' /><br />
                <input type='submit' name='p_back' value='戻る' style='width: 150px' /><br />
                <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px' /><br />
            </td>
        </tr>
    </table>
<input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['companyid']; ?>
" />
<input type="hidden" name="scd" value="<?php echo $this->_tpl_vars['storecode']; ?>
" />
</form>