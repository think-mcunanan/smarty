<!-- Created by jonathanparel, 20160923; RM#1724; -------------------------ii-->
<!-- SMARTPHONE -->

<div id="login">
    <div style="width: 80%; margin:auto;">
        ●新規会員登録
        <br />
        <br />
        新規登録は、<a href='mailto:{$salonmail}'>{$salonmail}</a>　に
        空メールを送信してください。
        ユーザー名、パスワードを忘れてしまった場合も、
        こちらに空メールを送信することでログインが可能です。
        <br />
        ※迷惑メール防止のための設定をしている場合は、
        下記ドメインを許可する設定を行ってください｡
        <br />
        <br />
        
        <Form action="" method="post">
            <font size="2"><input class="input_center" type="text" value="{php} echo '@'.EMAIL_DOMAIN; {/php}" size="19"></font>
        </form>
    </div>

    <hr />
    
        
    <div class="form">
        <br />
        <p>●会員ログイン</p>
        <form id='YkLoginForm' action="{$form_action}" method="post">
            会員ID：<br />
            <input class="input_center" type='text' name='username' value='{$username}' istyle="3" format="*x" mode="alphabet" /><br />
            {$logincomment}<br />
            <br />
            パスワード：<br />
            <input class="input_center" type='password' name='password' value='{$password}' istyle="3" format="*x" mode="alphabet" /><br />
            <br />
            <input class="groovybutton" type='submit' name='login' value='ログイン' /><br />
            <br />
        </form>
    </div>

    <br />
    #<a href='{$sitepath}'>{$storename}</a><br />
</div>
<!-- Created by jonathanparel, 20160923; RM#1724; -------------------------xx-->