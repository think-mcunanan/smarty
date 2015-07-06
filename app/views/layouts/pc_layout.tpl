<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="ja" xml:lang="ja" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>{$title_for_layout}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css; charset=UTF-8" />
        <meta http-equiv="Content-Script-Type" content="text/javascript; charset=UTF-8" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="-1" />
        {$html->css('import')}
     {literal}
         <script type="text/javascript"><!--
function check(){
	if(window.confirm('この予約を削除してもしてよろしいですか？')){ // 確認ダイアログを表示
		return true; // 「OK」時は送信を実行
	}
	else{ // 「キャンセル」時の処理
		return false; // 送信を中止
	}
}
// -->
</script>        
{/literal}
     </head>
    <body bgcolor='#F9F9F9'>
        <table border='0' width='800' bgcolor='#FFFFFF' align='center' cellpadding="0" cellspacing="0">
            <tr>
                <td rowspan='2'>
{if $logo_image != ""}
                    <img border='0' align='left' src="{$logo_image}" />
{else}
                    <font size='5' align='left'>{$title_for_layout}</font>
{/if}
                    <br />
                </td>
                <td valign='top' align='right'>
{if $logoutpath}
                    <a href='{$html->url($logoutpath)}'><img border='0' src="{$html->url('/img/logout.gif')}" /></a>
{/if}
                </td>
            </tr>
            <tr>
                <td valign='bottom' align='right'>
                    <img border='0' src="{$html->url('/img/onlineyoyaku.jpg')}" />
                </td>
            </tr>
            <tr>
                <td height='2' bgcolor='#AAAAAA' colspan='2'></td>
            </tr>
            <tr>
                <td colspan='2'>
                    <br />
{if $top_message}
                    <center><p>{$top_message}</p></center>
{/if}
                    {$content_for_layout}
                    <br />
                </td>
            </tr>
            <tr>
                <td height='1' bgcolor='#999999' colspan='2'></td>
            </tr>
{if $unregpath}
            <tr>
                <td align='right' colspan="2">
                    <font size='2'><img border='0' src="{$html->url('/img/arrow.gif')}" /><a href="{$html->url($unregpath)}">解約はこちら</a></font>
                </td>
            </tr>
{/if}
{if $privacypath}
            <tr>
                <td align='right' colspan="2">
                    <font size='2'><img border='0' src="{$html->url('/img/arrow.gif')}" /><a target='_blank' href="{$html->url($privacypath)}">プライバシーポリシー</a></font>
                </td>
            </tr>
{/if}
            <tr style="height: 120px;">
                <td align="center" colspan="2">
                    <font size="-2">POWERED BY</font><br />
                    <br />
                    <img src="{$html->url('/img/logo_footer.gif')}" width="106" height="53" /><br />
                    <font size="-2">Copyright (C) Think Inc. All rights reserved.</font>
                </td>
            </tr>
        </table>
    </body>
</html>
