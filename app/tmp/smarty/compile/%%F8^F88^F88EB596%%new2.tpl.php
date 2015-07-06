<?php /* Smarty version 2.6.26, created on 2013-02-13 20:11:14
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/pc/new2.tpl */ ?>
<center>
    <form name='YkNew1Form' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
        <table align="center" border="0px">
            <tr valign="bottom" align="center">
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/start.gif'); ?>
" width="100" height="20"></td>
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/1shimei.gif'); ?>
" width="100" height="25"></td>
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/gijutu.gif'); ?>
" width="100" height="25"></td>
                <td><img src="<?php echo $this->_tpl_vars['html']->url('/img/new/arrowgradopp.gif'); ?>
" width="29" height="21"></td>
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
        <hr align="center" width="85%" />
        <br />
        <table border='1' style='border-collapse: collapse; border: 1px solid #dddddd;' width='760' cellpadding='5'>
<?php if ($this->_tpl_vars['menu_name_only'] != 1): ?>
            <tr bgcolor='#efefef'>
                <td colspan='2' align='left'>メニュー</td>
                <td align='center'>料金</td>
                <td align='center'>施術時間</td>
            </tr>
    <?php $_from = $this->_tpl_vars['services_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['daibunrui'] => $this->_tpl_vars['services_sublist']):
?>
            <tr bgcolor='#efefef'>
                <td colspan='4' align='left'><?php echo $this->_tpl_vars['daibunrui']; ?>
</td>
            </tr>
        <?php $_from = $this->_tpl_vars['services_sublist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['srvkey'] => $this->_tpl_vars['service_item']):
?>
            <tr>
                <td><input type="checkbox" name="services[]" value="<?php echo $this->_tpl_vars['srvkey']; ?>
" <?php if (in_array ( $this->_tpl_vars['srvkey'] , $this->_tpl_vars['services'] )): ?>checked="checked" <?php endif; ?> /></td>
                <td align='left'><?php echo $this->_tpl_vars['service_item'][0]; ?>
</td>
                <td align='center'><?php if ($this->_tpl_vars['service_item'][2]): ?><?php echo $this->_tpl_vars['service_item'][2]; ?>
円<?php endif; ?></td>
                <td align='center'><?php echo $this->_tpl_vars['service_item'][1]; ?>
分</td>
            </tr>
        <?php endforeach; endif; unset($_from); ?>
    <?php endforeach; endif; unset($_from); ?>
<?php else: ?>
            <tr bgcolor='#efefef'>
                <td colspan='4' align='left'>メニュー</td>
            </tr>
    <?php $_from = $this->_tpl_vars['services_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['daibunrui'] => $this->_tpl_vars['services_sublist']):
?>
            <tr bgcolor='#efefef'>
                <td colspan='4' align='left'><?php echo $this->_tpl_vars['daibunrui']; ?>
</td>
            </tr>
        <?php $_from = $this->_tpl_vars['services_sublist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['srvkey'] => $this->_tpl_vars['service_item']):
?>
            <tr>
                <td><input type="checkbox" name="services[]" value="<?php echo $this->_tpl_vars['srvkey']; ?>
" <?php if (in_array ( $this->_tpl_vars['srvkey'] , $this->_tpl_vars['services'] )): ?>checked="checked" <?php endif; ?> /></td>
                <td colspan='3' align='left'><?php echo $this->_tpl_vars['service_item'][0]; ?>
</td>
            </tr>
        <?php endforeach; endif; unset($_from); ?>
    <?php endforeach; endif; unset($_from); ?>
<?php endif; ?>
        </table>
        <br />
        <div class='buttonframe'><input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_next" class="groovybutton" value="次へ" title="" style="cursor: pointer;"></div>
           <input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['companyid']; ?>
" />
           <input type="hidden" name="scd" value="<?php echo $this->_tpl_vars['storecode']; ?>
" />
    </form>
</center>