<?php /* Smarty version 2.6.26, created on 2013-02-21 13:35:15
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/smartphone/new1.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', '/var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/smartphone/new1.tpl', 7, false),)), $this); ?>
<form id='YkNew1Form' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
    <table width='90%' border='0'>
        <tr>
            <td align='left'><br />
<?php if ($this->_tpl_vars['error'] != 1): ?>
                担当者：<br />
                <?php echo smarty_function_html_options(array('name' => 'staff','options' => ($this->_tpl_vars['staff_name_list']),'selected' => ($this->_tpl_vars['staff'])), $this);?>
<br /><br />
                <input type='submit' name='p_next' value='次へ' style='width: 150px'><br />
<?php endif; ?>
                <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px'><br />
                <input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['companyid']; ?>
" />
                <input type="hidden" name="scd" value="<?php echo $this->_tpl_vars['storecode']; ?>
" />
            </td>
        </tr>
    </table>
</form>