<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="-1" />
        <title>{$title_for_layout}</title>
    </head>
    <body>
{if $logo_image != ""}
        <img border='0' src="{$logo_image}" /><br />
{else}
        <center>～ {$title_for_layout} ～</center>
{/if}
        <br />
        <center>
{if $top_message}
            <table width='90%' style='border-collapse:collapse;' border='1'>
                <tr>
                    <td bgcolor='#FFD6CF'>
                        {$top_message}
                    </td>
                </tr>
            </table>
{/if}
            {$content_for_layout}
        </center>
        <hr />
        <ul>
{if $unregpath}
            <li><a href="{$html->url($unregpath)}">解約はこちら</a></li>
{/if}
{if $privacypath}
            <li><a target='_blank' href="{$html->url($privacypath)}">プライバシーポリシー</a></li>
{/if}
{if $sitepath}
<li><a target='_blank' href="{$html->url($sitepath)}">ホームページ</a></li>
{/if}
        </ul>
        <br />
        <center>(C) 株式会社シンク<center>
    </body>
</html>
