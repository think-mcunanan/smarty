<!-- Created by jonathanparel, 20160923; RM#1724; -------------------------ii-->
<!-- SMARTPHONE -->

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
        <meta name="viewport" content ="width=device-width, initial-scale=1, user-scalable=yes" />
        
        <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
        
        <title>{$title_for_layout}</title>
        {$html->css('import')}
    </head>
    
    <body>
        <div class="smartphone">
            <div id="head">
                {if $logo_image != ""}
                    <img border='0' src="{$logo_image}" />
                {else}
                    {$title_for_layout} 
                {/if}
            </div>
            <hr />
            
            <div id="title">
                {if $top_message}
                    {$top_message}
                {/if}
            </div>
            
            <!-- Content to be inserted resides within its own DIV -->
            {$content_for_layout}
            
            <hr />
            <div id="foot">
                {if $unregpath}
                    <a href="{$html->url($unregpath)}">解約はこちら</a>
                {/if}
            
                {if $privacypath}
                    <a target='_blank' href="{$html->url($privacypath)}">プライバシーポリシー</a>
                {/if}
                
                {if $sitepath}
                    <li><a target='_blank' href="{$html->url($sitepath)}">ホームページ</a></li>
                {/if}
                
                <hr id="hr_slim"/>
                (C) 株式会社シンク
            </div>
        </div>
    </body>
</html>

<!-- Created by jonathanparel, 20160923; RM#1724; -------------------------xx-->