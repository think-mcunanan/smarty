<?php /* Smarty version 2.6.26, created on 2013-02-19 16:44:52
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/docomo_new/mypage.tpl */ ?>
<table width='90%' border='0'>
		<tr>
		<td align='left'>
                        <?php $_from = $this->_tpl_vars['nexttrans']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['itemdata']):
?><br>
						<b><?php echo $this->_tpl_vars['itemdata']['date']; ?>
のご予約</b></font><br />
                                                <?php if ($this->_tpl_vars['itemdata']['storename']): ?>
                                                    <b>店舗：</b> <?php echo $this->_tpl_vars['itemdata']['storename']; ?>
<br>
                                                <?php endif; ?>
                                                <b>施術:</b> <?php echo $this->_tpl_vars['itemdata']['servicessys']; ?>
<br />
                                                <b>日時:</b> <?php echo $this->_tpl_vars['itemdata']['time']; ?>
～<br />
						<b>担当:</b> <?php echo $this->_tpl_vars['itemdata']['staff']; ?>
<br />
                                                <?php if ($this->_tpl_vars['itemdata']['canselb'] == false): ?>
                                                    (ご予約の<?php echo $this->_tpl_vars['cancellim']; ?>
時間前のため、webからのキャンセルはできません。）<br />
                                                <?php else: ?>
                                                <center>
                                                <form name='YkMyPageForm' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
                                                    <input type="submit" name="p_delete<?php echo $this->_tpl_vars['itemdata']['transdate']; ?>
" class="groovybutton" value="この予約をキャンセル" title="" style="cursor: pointer;">
                                                </form>
                                                </center>
                                                <?php endif; ?>
                                <hr />
                        <?php endforeach; else: ?>
予約情報：なし
                        <?php endif; unset($_from); ?>
			</td>
                  	<tr>
                        </tr>
	</table>
<?php if ($this->_tpl_vars['points1'] || $this->_tpl_vars['points2']): ?>
<?php echo $this->_tpl_vars['points1']; ?>
<?php echo $this->_tpl_vars['points2']; ?>

<?php endif; ?> <br />
<form name='YkMyPageForm' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
<?php if ($this->_tpl_vars['nextyoyaku'] == 1): ?>
                        <?php if ($this->_tpl_vars['addyoyakub'] == 1): ?>
                        <input type='submit' name='p_add' class="groovybutton" value="他の予約をする" title="" style="cursor: pointer;"><br />
                        <?php endif; ?>
<?php else: ?>
			<input type="submit" name="p_new" class="groovybutton" value="新規予約を登録" title="" style="cursor: pointer;"><br />
<?php endif; ?><input type="submit" name="p_info" class="groovybutton" value="会員情報を更新" title="" style="cursor: pointer;"><br />
<input type='submit' name='p_logout' class="groovybutton" value='ログアウト' style="cursor: pointer;" />
</form>