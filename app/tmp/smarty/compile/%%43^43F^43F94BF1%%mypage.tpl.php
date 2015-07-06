<?php /* Smarty version 2.6.26, created on 2012-12-27 15:50:19
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/pc/mypage.tpl */ ?>
<center>
	<table border='0' width='620' cellpadding='6'>
		<tr>
			<td width='60' valign='top'>
                        <?php $_from = $this->_tpl_vars['nexttrans']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['itemdata']):
?><br>
                            <div class="rbroundboxc">
					<div class="rbtopc">
						<div></div>
					</div>

					<div class="rbcontentc"><font size='5'>
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
                                                    <br />（ご予約の<?php echo $this->_tpl_vars['cancellim']; ?>
時間前を過ぎると、webでキャンセルができません。）<br />
                                                    
                                                <?php else: ?>
                                                <center>
                                                <form name='YkMyPageForm' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post" onSubmit="return check()">
                                                    <input type="submit" name="p_delete<?php echo $this->_tpl_vars['itemdata']['transdate']; ?>
" class="groovybutton" value="この予約をキャンセル" title="" style="cursor: pointer;">
                                                </form>
                                                </center>
                                                <?php endif; ?>
					</div>
					<!-- /rbcontent -->
					<div class="rbbotc"><div></div></div>
			</div>
			<!-- /rbroundbox -->
                        <?php endforeach; else: ?>
                        <div class="rbroundboxc">
					<div class="rbtopc"><div></div></div>
					<div class="rbcontentc"><font size='5'><b>予約：</b> なし</div>
					<div class="rbbotc"><div></div></div>
			</div>
                        <?php endif; unset($_from); ?>
			</td>
                  	<tr>
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
<?php if ($this->_tpl_vars['nextyoyaku'] == 1): ?>
			<!--<input type="submit" name="p_delete" class="groovybutton" value="予約をキャンセル" title="" style="cursor: pointer;">-->
                        <?php if ($this->_tpl_vars['addyoyakub'] == 1): ?>
                        <input type='submit' name='p_add' class="groovybutton" value="他の予約をする" title="" style="cursor: pointer;">
                        <?php endif; ?>
<?php else: ?>
			<input type="submit" name="p_new" class="groovybutton" value="新規予約を登録" title="" style="cursor: pointer;">
<?php endif; ?><input type="submit" name="p_info" class="groovybutton" value="会員情報を更新" title="" style="cursor: pointer;">
		</div>
	</form>
</center>