<!-- Created by jonathanparel, 20160923; RM#1724; -------------------------ii-->
<!-- SMARTPHONE -->

<div class="login">
    <div id="title">
        ●新規会員登録
    </div>

    <hr id="hr_slim"/>
    <div id="centered_message">
        新規登録は、SNSログインを行うか、<a href='mailto:{$salonmail}'>{$salonmail}</a>　に空メールを送信してください。
        <br>
        ユーザー名、パスワードを忘れてしまった場合も、こちらに空メールを送信することでログインが可能です。
        <hr id="hr_slim"/>
        ※迷惑メール防止のための設定をしている場合は、下記ドメインを許可する設定を行ってください｡
        <hr id="hr_slim"/>
        
        <Form action="" method="post">
            <font size="2"><input class="input_center" type="text" value="{php} echo '@'.EMAIL_DOMAIN; {/php}" size="19"></font>
        </form>
        <hr />
    </div>
        
    <div id="bground_mat">
        <div id="title" style="font-size: 100%;">
            ●会員ログイン
        </div>
        
        <form id='YkLoginForm' action="{$form_action}" method="post">
            会員ID：<br />
            <input class="input_center" type='text' name='username' value='{$username}' istyle="3" format="*x" mode="alphabet" /><br />
            {$logincomment}
            <hr id="hr_slim"/>
            パスワード：<br />
            <input class="input_center" type='password' name='password' value='{$password}' istyle="3" format="*x" mode="alphabet" />
            <br>
            <br>
            <input class="groovybutton" type='submit' name='login' value='ログイン' style="width:80%;"/>
        </form>
        <hr>
         <button class="loginBtn loginBtn--line" onclick="location.href='{$line_url}';">Lineでログイン</button>
        <button class="loginBtn loginBtn--facebook" onclick="location.href='{$facebook_url}';">facebookでログイン</button>
        <button class="loginBtn loginBtn--google" onclick="location.href='{$google_url}';">Googleでログイン</button>
        <hr>
    </div>
    
    <hr id="hr_slim"/>
    #<a href='{$sitepath}'>{$storename}</a>
</div>
<!-- Created by jonathanparel, 20160923; RM#1724; -------------------------xx-->