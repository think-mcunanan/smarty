<?php /* Smarty version 2.6.26, created on 2012-12-26 12:54:32
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/softbank/reg.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', '/var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/softbank/reg.tpl', 14, false),)), $this); ?>
<table width='90%' border='0'>
    <tr>
        <td align='left'>
        <form id='YkRegForm' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">・名前<font color='red'>*</font>：<br />
        <input type='text' name='r_name' value='<?php echo $this->_tpl_vars['name']; ?>
' size='16' maxlength='50' /><br />
        <br />
        ・メールアドレス：<br />
        <?php echo $this->_tpl_vars['email']; ?>
<br />
        <br />
        ・携帯番号<font color='red'>*</font>：<br />
        <input type='text' name='r_phone' value='<?php echo $this->_tpl_vars['phone']; ?>
' size='16' maxlength='20' format="*N" style="-wap-input-format:*N" /><br />
        <br />
        ・性別： <select name="r_sex">
            <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['sex_list'],'selected' => $this->_tpl_vars['sex']), $this);?>

        </select><br />
        <br />
        ・誕生日<br />
        <input type='text' name='r_year' value='<?php echo $this->_tpl_vars['year']; ?>
' size='5' maxlength='4' format="*N" style="-wap-input-format:*N" />年 <input type='text' name='r_month' value='<?php echo $this->_tpl_vars['month']; ?>
' size='3' maxlength='2' format="*N" style="-wap-input-format:*N" />月 <input type='text' name='r_day' value='<?php echo $this->_tpl_vars['day']; ?>
' size='3' maxlength='2' format="*N" style="-wap-input-format:*N" />日<br />
        <br />
        ・パスワード更新：<br />
        　(半角英数字のみ)<br />
        <input type='password' name='r_password1' value='' size='10' maxlength='50' /><br />
        <input type='password' name='r_password2' value='' size='10' maxlength='50' />（確認）<br />
        <br />
        メール配信: <input type='checkbox' name='r_mailkubun' value='1' <?php if ($this->_tpl_vars['mailkubun'] == 1): ?>checked='checked' <?php endif; ?> /><br />
        <br />
        <br />
        <input type='submit' name='p_reg' value='登録' style='width: 150px' /><br />
        <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px' /><br />
        </form>
        </td>
    </tr>
</table>