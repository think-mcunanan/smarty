<?php /* Smarty version 2.6.26, created on 2013-01-12 10:39:08
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/softbank/new0.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', '/var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/softbank/new0.tpl', 5, false),)), $this); ?>
<form name='YkNew0Form' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
<table border='1' style='border-collapse: collapse; border: 1px solid #dddddd;' width='95%' cellpadding='5'>
        <tr>
            <td align='left'><br />
                <?php echo smarty_function_html_radios(array('name' => 'syscode','options' => $this->_tpl_vars['gyoshukubun_list'],'selected' => $this->_tpl_vars['gyoshukubun'],'separator' => '<br />'), $this);?>

            </td>
        </tr>
</table>
<br />
<input type="submit" name="p_next"  value="次へ" width="250px" style="cursor: pointer;"><br />
<input type="submit" name="p_cancel" value="キャンセル" width="150px" title="" style="cursor: pointer;"><br />
        <input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['companyid']; ?>
" />
        <input type="hidden" name="scd" value="<?php echo $this->_tpl_vars['storecode']; ?>
" />
</form>