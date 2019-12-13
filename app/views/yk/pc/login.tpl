<!--Optimized by jonathanparel, 20160906 For Mobile Devices; RM#1724; ii-->

<div class="login">
    <div id="bground_mat" style="width: 60%; min-width: 400px;">
        <div>
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
                <input type="submit" name="login" class="groovybutton" value="ログイン" title="" style="cursor: pointer;">
            </form>
            <hr>
            <button class="loginBtn loginBtn--line" onclick="location.href='{$facebook_url}';">Lineでログイン</button>
            <br>
            <button class="loginBtn loginBtn--facebook" onclick="location.href='{$facebook_url}';">facebookでログイン</button>
            <br>
            <hr>
        </div>
        <br />
        <strong>初めてのお客様は、<span style="color:red;">SNSログインを行う</span>か、下記のアドレスに<span style="color:red;">空メールを送信</span>して、会員登録をお願いします。</strong>
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
    </div>
</div>
<!--Optimized by jonathanparel, 20160906 For Mobile Devices; RM#1724; xx-->
          