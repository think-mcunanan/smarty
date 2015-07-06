<?php /* Smarty version 2.6.26, created on 2013-03-02 20:27:53
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/docomo_new/new3.tpl */ ?>
<form id='YkNew3Form' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
    <br />
    <table width='100%' border='1'>
        <tr align='center'>
            <td>
<?php if ($this->_tpl_vars['prevlink'] != ""): ?>
                <a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new3/<?php echo $this->_tpl_vars['sessionid']; ?>
/<?php echo $this->_tpl_vars['prevlink']; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'>&lt;</a>
<?php else: ?>
                <font color='#AAAAAA'>&lt;</font>
<?php endif; ?>
            </td>
            <td colspan='5' align='center'>
                <?php echo $this->_tpl_vars['calendar_header']; ?>

            </td>
            <td>
<?php if ($this->_tpl_vars['nextlink'] != ""): ?>
                 <a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new3/<?php echo $this->_tpl_vars['sessionid']; ?>
/<?php echo $this->_tpl_vars['nextlink']; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'>&gt;</a>
<?php else: ?>
                 <font color='#AAAAAA'>&gt;</font>
<?php endif; ?>
            </td>
        </tr>
        <tr align='center'>
            <td>
                日
            </td>
            <td>
                月
            </td>
            <td>
                火
            </td>
            <td>
                水
            </td>
            <td>
                木
            </td>
            <td>
                金
            </td>
            <td>
                土
            </td>
        </tr>
<?php $_from = $this->_tpl_vars['calendar']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['week']):
?>
        <tr align='center'>
    <?php $_from = $this->_tpl_vars['week']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['itm']):
?>
            <td>   
        <?php if ($this->_tpl_vars['itm'][0] > 0): ?>
            <?php if ($this->_tpl_vars['itm'][1] != ""): ?>
                <a href='<?php echo $this->_tpl_vars['yoyaku_path']; ?>
/new4/<?php echo $this->_tpl_vars['sessionid']; ?>
/<?php echo $this->_tpl_vars['itm'][1]; ?>
/ts:<?php echo $this->_tpl_vars['ts']; ?>
'><?php echo $this->_tpl_vars['itm'][0]; ?>
</a>
            <?php else: ?>
                <font color='#AAAAAA'><?php echo $this->_tpl_vars['itm'][0]; ?>
</font>
            <?php endif; ?>
        <?php endif; ?>
            </td>
    <?php endforeach; endif; unset($_from); ?>
        </tr>
<?php endforeach; endif; unset($_from); ?>
    </table><br />
    <table width='100%' border='0'>
        <tr>
            <td align='center'>
                <input type='submit' name='p_back' value='戻る' style='width: 150px' align="left"><br />
                <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px'><br />
            </td>
        </tr>
    </table>
                <input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['companyid']; ?>
" />
                <input type="hidden" name="scd" value="<?php echo $this->_tpl_vars['storecode']; ?>
" />
</form>