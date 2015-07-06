<table width='90%' border='0'>
    <tr>
        <td>
            <p>●新規会員登録</p>
            新規登録は、<a href='mailto:{$salonmail}'>{$salonmail}</a>　に<br />空メールを送信してください。<br />
            ユーザー名、パスワードを忘れてしまった場合も、<br />こちらに空メールを送信することでログインが可能です。<br /><br />
            ※迷惑メール防止のための設定をしている場合は、<br />下記ドメインを許可する設定を行ってください｡<br />
            <FORM action="" method="post"><FONT size="2"><INPUT type="text" value="@bmy.jp" size="19"></FONT></FORM>
            <hr />
            <p>●会員ログイン</p>
            <form id='YkLoginForm' action="{$form_action}" method="post">
                会員ID：<br />
                <input type='text' name='username' value='{$username}' istyle="3" format="*x" mode="alphabet" /><br />
                {$logincomment}<br />
                <br />
                パスワード：<br />
                <input type='password' name='password' value='{$password}' istyle="3" format="*x" mode="alphabet" /><br />
                <input type='submit' name='login' value='ログイン' /><br />
            </form>
        </td>
    </tr>
</table>
<br />
#<a href='{$sitepath}'>{$storename}</a><br />
<br />
