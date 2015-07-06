<?php /* Smarty version 2.6.26, created on 2013-02-13 20:11:37
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/pc/new4.tpl */ ?>
<center>
    <form name='YkNew3Form' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
        <table align="center" border="0px">
            <tr valign="bottom" align="center">
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/start.gif'); ?>
" width="100" height="20"></td>
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/1shimei.gif'); ?>
" width="100" height="25"></td>
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/2gijutu.gif'); ?>
" width="100" height="25"></td>
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/3hiduke.gif'); ?>
" width="105" height="25"></td>
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/jikan.gif'); ?>
" width="120" height="25"></td>
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/arrowgradopp.gif'); ?>
" width="29" height="21"></td>
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/5touroku.gif'); ?>
" width="110" height="25"></td>
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/end.gif'); ?>
" width="100" height="20"></td>
            </tr>
        </table>
        <hr align="center" width="85%" />
        <p><font size="2">※ご予約可能な時間のみ表示されます</font></p>
        <table border='0' width='200'>
            <tr>
                <td colspan='2' align='center'>
                    <table border='1' style='border-collapse: collapse; border: 1px solid #cccccc;' width='760' cellpadding='5'>
<?php $_from = $this->_tpl_vars['AvailableTimes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['val'] => $this->_tpl_vars['label']):
?>
                        <tr>
                            <td align='center'>
                                <font size='4'><a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new5/<?php echo $this->_tpl_vars['sessionid']; ?>
/<?php echo $this->_tpl_vars['val']; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'><?php echo $this->_tpl_vars['label']; ?>
</a></font>
                            </td>
                        </tr>
<?php endforeach; endif; unset($_from); ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td align='left'>
<?php if ($this->_tpl_vars['prevpage'] != 0): ?>
                    <br />
                    <a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new4/<?php echo $this->_tpl_vars['sessionid']; ?>
/0/<?php echo $this->_tpl_vars['prevpage']; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'>&lt;&lt; 前へ</a>
<?php endif; ?>
                </td>
                <td align='right'>
<?php if ($this->_tpl_vars['nextpage'] != 0): ?>
                    <br />
                    <a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new4/<?php echo $this->_tpl_vars['sessionid']; ?>
/0/<?php echo $this->_tpl_vars['nextpage']; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'>次へ &gt;&gt;</a>
<?php endif; ?>
                </td>
            </tr>
        </table>
        <br />
        <br />
        <div class='buttonframe'><input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;"></div>
    </form>
</center>