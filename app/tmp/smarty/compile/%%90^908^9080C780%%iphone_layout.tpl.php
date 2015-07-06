<?php /* Smarty version 2.6.26, created on 2012-12-22 17:21:29
         compiled from /var/www2/sipssbeauty/mobile_station_beauty/serverside/app/views/layouts/iphone_layout.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="-1" />
        <title><?php echo $this->_tpl_vars['title_for_layout']; ?>
</title>

<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<?php echo '
<script>
$(document).bind("mobileinit", function(){  
    $.mobile.page.prototype.options.addBackBtn = false;  
    $.mobile.ajaxEnabled = false;  
});  </script>
'; ?>

<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>

        
    </head>
    <body>
<?php if ($this->_tpl_vars['logo_image'] != ""): ?>
        <img border='0' src="<?php echo $this->_tpl_vars['logo_image']; ?>
" /><br />
<?php else: ?>
        <center>～ <?php echo $this->_tpl_vars['title_for_layout']; ?>
 ～</center>
<?php endif; ?>
        <br />
        <center>
<?php if ($this->_tpl_vars['top_message']): ?>
            <table width='90%' style='border-collapse:collapse;' border='1'>
                <tr>
                    <td bgcolor='#FFD6CF'>
                        <?php echo $this->_tpl_vars['top_message']; ?>

                    </td>
                </tr>
            </table>
<?php endif; ?>
            <?php echo $this->_tpl_vars['content_for_layout']; ?>

        </center>
        <hr />
        <ul>
<?php if ($this->_tpl_vars['unregpath']): ?>
            <li><a href="<?php echo $this->_tpl_vars['html']->url($this->_tpl_vars['unregpath']); ?>
">解約はこちら</a></li>
<?php endif; ?>
<?php if ($this->_tpl_vars['privacypath']): ?>
            <li><a target='_blank' href="<?php echo $this->_tpl_vars['html']->url($this->_tpl_vars['privacypath']); ?>
">プライバシーポリシー</a></li>
<?php endif; ?>
<?php if ($this->_tpl_vars['sitepath']): ?>
<li><a target='_blank' href="<?php echo $this->_tpl_vars['html']->url($this->_tpl_vars['sitepath']); ?>
">ホームページ</a></li>
<?php endif; ?>
        </ul>
        <br />
        <center>(C) 株式会社シンク<center>
    </body>
</html>