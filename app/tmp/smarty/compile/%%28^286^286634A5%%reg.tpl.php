<?php /* Smarty version 2.6.26, created on 2012-12-21 18:44:48
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/pc/reg.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', '/var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/pc/reg.tpl', 19, false),)), $this); ?>
<center>
<form name='YkRegForm' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
<table border='0' cellpadding='6'>
    <tr>
        <td align='right' width='150'>名前<font color='red'>*</font>：</td>
        <td align='left'><input type='text' name='r_name' value='<?php echo $this->_tpl_vars['name']; ?>
' size='16' maxlength='50' /></td>
    </tr>
    <tr>
        <td align='right' valign='top'>メールアドレス：</td>
        <td align='left' valign='top'><font size='4'><b><?php echo $this->_tpl_vars['email']; ?>
</b></font></td>
    </tr>
    <tr>
        <td align='right'>電話番号(携帯)<font color='red'>*</font>：</td>
        <td align='left'><input type='text' name='r_phone' value='<?php echo $this->_tpl_vars['phone']; ?>
' size='16' maxlength='20' /></td>
    </tr>
    <tr>
        <td align='right'>性別：</td>
        <td align='left'><select name="r_sex">
            <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['sex_list'],'selected' => $this->_tpl_vars['sex']), $this);?>

        </select></td>
    </tr>
    <tr>
        <td align='right'>誕生日：</td>
        <td align='left'><input type='text' name='r_year' value='<?php echo $this->_tpl_vars['year']; ?>
' size='5' maxlength='4' />年 <input type='text' name='r_month' value='<?php echo $this->_tpl_vars['month']; ?>
' size='3' maxlength='2' />月 <input type='text' name='r_day' value='<?php echo $this->_tpl_vars['day']; ?>
' size='3' maxlength='2' />日</td>
    </tr>
    <tr>
        <td align='right' valign='top'>パスワード更新：<br />(半角英数字のみ)</td>
        <td align='left'><input type='password' name='r_password1' value='' size='16' maxlength='50' /><br />
        <input type='password' name='r_password2' value='' size='16' maxlength='50' />（確認）</td>
    </tr>
    <tr>
        <td align='right' valign='top'>メール配信：</td>
        <td align='left'><input type='checkbox' name='r_mailkubun' value='1' <?php if ($this->_tpl_vars['mailkubun'] == 1): ?>checked='checked' <?php endif; ?> />

    </tr>
</table>
<br />
<br />
<div class='buttonframe'><input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_reg" class="groovybutton" value="登録" title="" style="cursor: pointer;"></div>
</form>
</center>