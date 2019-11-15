<!--Optimized by jonathanparel, 20160905 For Mobile Devices; RM#1724; ii-->
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
        
        <!-- Added by jonathanparel, 20160906; RM#1724; ii-->
        <meta name="viewport" content ="width=device-width, initial-scale=1, user-scalable=yes" />
        <!-- Added by jonathanparel, 20160906; RM#1724; xx-->
        
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

    <body>
        <div class="pc" style="text-align: left;">
            {if $logo_image != ""}
                <img border='0' align='left' src="{$logo_image}" />
            {else}
                <font size='5' align='left'>{$title_for_layout}</font>
            {/if}

            {if $logoutpath && $setLogoutButton}
                <a href='{$html->url($logoutpath)}'><img border='0' align="right" src="{$html->url('/img/logout.gif')}" /></a>
            {/if}
            <br />
            <img border='0' align="right" src="{$html->url('/img/onlineyoyaku.jpg')}" />
            <br />
            <br />

            <hr>
        </div>
        
        {if $top_message}
            <div id="title">
                {$top_message}
            </div>
        {/if}
        
        <!-- Data Insertion Point -->
        {$content_for_layout}
        
        <!-- Horizontal Line -->
        <hr>
        <div style="text-align: center;">
            {if $unregpath}
                <font size='2'><img border='0' src="{$html->url('/img/arrow.gif')}" /><a href="{$html->url($unregpath)}">解約はこちら</a></font>
                <br />
            {/if}

            {if $privacypath}
                <br />
                <font size='2'><img border='0' src="{$html->url('/img/arrow.gif')}" /><a target='_blank' href="{$html->url($privacypath)}">プライバシーポリシー</a></font>
                <br />
            {/if}

            <div align="center">
                <font size="-2">POWERED BY</font><br />
                <br />
                <img src="{$html->url('/img/logo_footer.gif')}" width="106" height="53" />

                <br />
                <font size="-2">Copyright (C) Think Inc. All rights reserved.</font>
                <br />
                <br />
            </div>
        </div>
    </body>
</html>
<!--Optimized by jonathanparel, 20160905 For Mobile Devices; RM#1724; xx-->