<!--Optimized by jonathanparel, 20160906 For Mobile Devices; RM#1724; ii-->

<div class="login" align="center">
    <div id="rounded_login">
        <form name='YkLoginForm' action="{$form_action}" method="post">
            <font size="+2"><strong>ログイン</strong></font>
            <hr>
            <br />

            <strong>ユーザー名</strong>
            <input name="username" type="text" value="{$username}" size="30" style="width: 200px;" />
            <br />
            <font size ="2">{$logincomment}</font></td>
            <br />
            <br />

            <strong>パスワード</strong>&nbsp;
            <input name="password" type="password" value="{$password}" size="30" style="width: 200px;" />
            <br />
            <br />
            <hr>
            <input type="submit" name="login" class="groovybutton" value="ログイン" title="" style="cursor: pointer;">
        </form>
    </div>

    <div id="filler">
        <br />
    </div>

    <div id="rounded_email">
        <strong>初めてのお客様は、下記のアドレスに<span style="color:red;">空メールを送信</span>して、会員登録をお願いします。</strong>
        <br />
        <br />
        <center><a href='mailto:{$salonmail}'>{$salonmail}</a></center>
        <br />
        ★ユーザー名、またはパスワードを忘れてしまった場合も、空メールを送信することでログインが可能です。
        <br />
        <br />
        ★迷惑メール防止のための設定をしている場合は、下記ドメインを許可する設定を行ってください｡
        <br />
        <br />

        <form action="" method="post">
            <center>
                <font size="2">
                    <input type="text" value="{php} echo '@'.EMAIL_DOMAIN; {/php}" size="19">
                </font>
            </center>
        </form>
        <br />
    </div>
</div>
<!--Optimized by jonathanparel, 20160906 For Mobile Devices; RM#1724; xx-->
          