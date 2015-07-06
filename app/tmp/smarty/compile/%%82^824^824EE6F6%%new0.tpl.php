<?php /* Smarty version 2.6.26, created on 2013-01-01 15:12:36
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/pc/new0.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', '/var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/pc/new0.tpl', 21, false),)), $this); ?>
<center>
<form name='YkNew0Form' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
<table align="center" border="0px">
	<tr valign="bottom" align="center">
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/arrowgradopp.gif'); ?>
" width="29" height="21"></td>
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/start.gif'); ?>
" width="100" height="20"></td>
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/1shimei.gif'); ?>
" width="100" height="25"></td>
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/2gijutu.gif'); ?>
" width="100" height="25"></td>
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/3hiduke.gif'); ?>
" width="105" height="25"></td>
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/4jikan.gif'); ?>
" width="120" height="25"></td>
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/5touroku.gif'); ?>
" width="110" height="25"></td>
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/end.gif'); ?>
" width="100" height="20"></td>
	</tr>
</table>
<hr align="center" width="85%">
<br />
<table border='1' style='border-collapse: collapse; border: 1px solid #dddddd;' width='760' cellpadding='5'>
        <tr>
            <td align='left'><br />
               　施術を選択して下さい：<br /><br />
                <?php echo smarty_function_html_radios(array('name' => 'syscode','options' => $this->_tpl_vars['gyoshukubun_list'],'selected' => $this->_tpl_vars['gyoshukubun'],'separator' => '    '), $this);?>

            </td>
        </tr>
</table>
<br />
	<div class='buttonframe'><input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_next" class="groovybutton" value="次へ" title="" style="cursor: pointer;"></div>
        <input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['companyid']; ?>
" />
        <input type="hidden" name="scd" value="<?php echo $this->_tpl_vars['storecode']; ?>
" />
</form>
</center>