<?php /* Smarty version 2.6.26, created on 2011-12-22 15:40:34
         compiled from /var/www/mobile_station/serverside/app/views/yk/pc/new3.tpl */ ?>
<center>
<form name='YkNew2Form' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
<table align="center" border="0px">
	<tr valign="bottom" align="center">
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/start.gif'); ?>
" width="100" height="20"></td>
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/1shimei.gif'); ?>
" width="100" height="25"></td>
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/2gijutu.gif'); ?>
" width="100" height="25"></td>
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/hiduke.gif'); ?>
" width="105" height="25"></td>
		<td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/arrowgradopp.gif'); ?>
" width="29" height="21"></td>
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
<table border='1' style='border-collapse: collapse;'>
	<tr align='center'>
		<td><?php if ($this->_tpl_vars['prevlink'] != ""): ?> <font size='5'><a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new3/<?php echo $this->_tpl_vars['sessionid']; ?>
/<?php echo $this->_tpl_vars['prevlink']; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'>&lt;</a></font> <?php else: ?> <font color='#AAAAAA' size='5'>&lt;</font> <?php endif; ?></td>
		<td colspan='5' align='center'><?php echo $this->_tpl_vars['calendar_header']; ?>
</td>
		<td><?php if ($this->_tpl_vars['nextlink'] != ""): ?> <font size='5'><a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new3/<?php echo $this->_tpl_vars['sessionid']; ?>
/<?php echo $this->_tpl_vars['nextlink']; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'>&gt;</a></font> <?php else: ?> <font color='#AAAAAA' size='5'>&gt;</font> <?php endif; ?></td>
	</tr>
	<tr align='center' bgcolor='#eeeeee'>
		<td width='90' bgcolor="#FFDCD9"><font size='5'>日</font></td>
		<td width='90'><font size='5'>月</font></td>
		<td width='90'><font size='5'>火</font></td>
		<td width='90'><font size='5'>水</font></td>
		<td width='90'><font size='5'>木</font></td>
		<td width='90'><font size='5'>金</font></td>
		<td width='90' bgcolor="#D5D9FF"><font size='5'>土</font></td>
	</tr>
	<?php $_from = $this->_tpl_vars['calendar']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['week']):
?>
	<tr align='center' height='45'>
		<?php $_from = $this->_tpl_vars['week']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['itm']):
?>
		<td><?php if ($this->_tpl_vars['itm'][0] > 0): ?> <?php if ($this->_tpl_vars['itm'][1] != ""): ?> <font size='5'><a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new4/<?php echo $this->_tpl_vars['sessionid']; ?>
/<?php echo $this->_tpl_vars['itm'][1]; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'><?php echo $this->_tpl_vars['itm'][0]; ?>
</a></font> <?php else: ?> <font color='#AAAAAA' size='5'><?php echo $this->_tpl_vars['itm'][0]; ?>
</font> <?php endif; ?> <?php endif; ?></td>
		<?php endforeach; endif; unset($_from); ?>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
</table>
<br />
<div class='buttonframe'><input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;"></div>
</form>
</center>