<?php /* Smarty version 2.6.26, created on 2011-12-22 14:02:53
         compiled from /var/www/mobile_station/serverside/app/views/yk/pc/mypage.tpl */ ?>
<center>
	<table border='0' width='620' cellpadding='6'>
		<tr>
			<td width='300' valign='top'>
				<div class="rbroundboxc">
					<div class="rbtopc">
						<div></div>
					</div>
					<div class="rbcontentc">
						<font size='5'><b>前回</b></font><br>
						<br>
<?php if ($this->_tpl_vars['prevdate']): ?>
	<?php if ($this->_tpl_vars['prevstname']): ?>
						<b>店舗：</b> <?php echo $this->_tpl_vars['prevstname']; ?>
<br>
	<?php endif; ?>
						<b>来店日：</b> <?php echo $this->_tpl_vars['prevdate']; ?>
<br>
						<b>担当者：</b> <?php echo $this->_tpl_vars['prevstaff']; ?>
<br>
<?php else: ?>
						<b>来店日：</b> なし<br>
<?php endif; ?>
					</div>
					<!-- /rbcontent -->
					<div class="rbbotc">
						<div></div>
					</div>
				</div>
				<!-- /rbroundbox -->
			</td>
			<td width='20'>&nbsp;</td>
			<td width='300' valign='top'>
				<div class="rbroundboxc">
					<div class="rbtopc">
						<div></div>
					</div>
					<div class="rbcontentc"><font size='5'>
						<b>次回</b></font><br>
						<br>
<?php if ($this->_tpl_vars['nextdate']): ?>
	<?php if ($this->_tpl_vars['nextstname']): ?>
						<b>店舗：</b> <?php echo $this->_tpl_vars['nextstname']; ?>
<br>
	<?php endif; ?>
						<b>来店日：</b> <?php echo $this->_tpl_vars['nextdate']; ?>
<br>
						<b>時間：</b> <?php echo $this->_tpl_vars['nexttime']; ?>
<br>
						<b>担当者：</b> <?php echo $this->_tpl_vars['nextstaff']; ?>
<br>
<?php else: ?>
						<b>来店日：</b> なし
<?php endif; ?>
					</div>
					<!-- /rbcontent -->
					<div class="rbbotc">
						<div></div>
					</div>
				</div>
				<!-- /rbroundbox -->
			</td>
		</tr>
	</table>
<?php if ($this->_tpl_vars['points1'] || $this->_tpl_vars['points2']): ?>
	<div class='fadebox'><?php echo $this->_tpl_vars['points1']; ?>
<?php echo $this->_tpl_vars['points2']; ?>
</div>
<?php endif; ?> <br>
	<form name='YkMyPageForm' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
		<div class='buttonframe'>
<?php if ($this->_tpl_vars['cancelb'] == 1): ?>
			<input type="submit" name="p_delete" class="groovybutton" value="予約をキャンセル" title="" style="cursor: pointer;">
<?php elseif ($this->_tpl_vars['cancelb'] == 3): ?>
			（ご予約の<?php echo $this->_tpl_vars['cancellim']; ?>
時間前を過ぎるとキャンセルできません）<br>
<?php else: ?>
			<input type="submit" name="p_new" class="groovybutton" value="新規予約を登録" title="" style="cursor: pointer;">
<?php endif; ?>
			&nbsp; <input type="submit" name="p_info" class="groovybutton" value="会員情報を更新" title="" style="cursor: pointer;">
		</div>
	</form>
</center>