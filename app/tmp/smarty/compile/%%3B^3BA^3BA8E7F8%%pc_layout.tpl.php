<?php /* Smarty version 2.6.26, created on 2011-12-22 14:01:44
         compiled from /var/www/mobile_station/serverside/app/views/layouts/pc_layout.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="ja" xml:lang="ja" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $this->_tpl_vars['title_for_layout']; ?>
</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css; charset=UTF-8" />
        <meta http-equiv="Content-Script-Type" content="text/javascript; charset=UTF-8" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="-1" />
        <?php echo $this->_tpl_vars['html']->css('import'); ?>

    </head>
    <body bgcolor='#EFEFEF'>
        <table border='0' width='800' bgcolor='#FFFFFF' align='center' cellpadding="0" cellspacing="0">
            <tr>
                <td rowspan='2'>
<?php if ($this->_tpl_vars['logo_image'] != ""): ?>
                    <img border='0' align='left' src="<?php echo $this->_tpl_vars['logo_image']; ?>
" />
<?php else: ?>
                    <font size='5' align='left'><?php echo $this->_tpl_vars['title_for_layout']; ?>
</font>
<?php endif; ?>
                    <br />
                </td>
                <td valign='top' align='right'>
<?php if ($this->_tpl_vars['logoutpath']): ?>
                    <a href='<?php echo $this->_tpl_vars['html']->url($this->_tpl_vars['logoutpath']); ?>
'><img border='0' src="<?php echo $this->_tpl_vars['html']->url('/img/logout.gif'); ?>
" /></a>
<?php endif; ?>
                </td>
            </tr>
            <tr>
                <td valign='bottom' align='right'>
                    <img border='0' src="<?php echo $this->_tpl_vars['html']->url('/img/onlineyoyaku.jpg'); ?>
" />
                </td>
            </tr>
            <tr>
                <td height='2' bgcolor='#AAAAAA' colspan='2'></td>
            </tr>
            <tr>
                <td colspan='2'>
                    <br />
<?php if ($this->_tpl_vars['top_message']): ?>
                    <center><p><?php echo $this->_tpl_vars['top_message']; ?>
</p></center>
<?php endif; ?>
                    <?php echo $this->_tpl_vars['content_for_layout']; ?>

                    <br />
                </td>
            </tr>
            <tr>
                <td height='1' bgcolor='#999999' colspan='2'></td>
            </tr>
<?php if ($this->_tpl_vars['unregpath']): ?>
            <tr>
                <td align='right' colspan="2">
                    <font size='2'><img border='0' src="<?php echo $this->_tpl_vars['html']->url('/img/arrow.gif'); ?>
" /><a href="<?php echo $this->_tpl_vars['html']->url($this->_tpl_vars['unregpath']); ?>
">解約はこちら</a></font>
                </td>
            </tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['privacypath']): ?>
            <tr>
                <td align='right' colspan="2">
                    <font size='2'><img border='0' src="<?php echo $this->_tpl_vars['html']->url('/img/arrow.gif'); ?>
" /><a target='_blank' href="<?php echo $this->_tpl_vars['html']->url($this->_tpl_vars['privacypath']); ?>
">プライバシーポリシー</a></font>
                </td>
            </tr>
<?php endif; ?>
            <tr style="height: 120px;">
                <td align="center" colspan="2">
                    <font size="-2">POWERED BY</font><br />
                    <br />
                    <img src="<?php echo $this->_tpl_vars['html']->url('/img/logo_footer.gif'); ?>
" width="106" height="53" /><br />
                    <font size="-2">Copyright (C) Think Inc. All rights reserved.</font>
                </td>
            </tr>
        </table>
    </body>
</html>