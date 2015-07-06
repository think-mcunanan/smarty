<table width='90%' border='0'>
    <tr>
        <td>
            新規登録は、下記のアドレスに空メールを送信してください<br />
            ユーザー名、パスワードを忘れてしまった場合でもこちらからログインできます<br />
            <a href='mailto:{$salonmail}'>{$salonmail}</a><br />
            <br />
            <hr />
            <br />
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
