<?php /* Smarty version 2.6.26, created on 2013-01-12 10:43:37
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/yk/softbank/new5.tpl */ ?>
<form id='YkNew5Form' action="<?php echo $this->_tpl_vars['form_action']; ?>
" method="post">
    <table width='90%' border='0'>
        <tr>
            <td align='left'><br />
                担当者:<?php echo $this->_tpl_vars['trans_staff']; ?>
<br />
                日付:<?php echo $this->_tpl_vars['trans_date']; ?>
<br />
                時間:<?php echo $this->_tpl_vars['trans_time']; ?>
<br />
                メニュー選択: <br />
                <?php echo $this->_tpl_vars['trans_services']; ?>
<br />
                <td>
                    </tr>
                    <tr><td align='center'>
                <input type='submit' name='p_confirm' value='決定' style='width: 150px'><br />
                <input type='submit' name='p_back' value='戻る' style='width: 150px'><br />
                <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px'><br />
            </td>
        </tr>
    </table>
                <input type="hidden" name="cid" value="<?php echo $this->_tpl_vars['companyid']; ?>
" />
                <input type="hidden" name="scd" value="<?php echo $this->_tpl_vars['storecode']; ?>
" />
</form>