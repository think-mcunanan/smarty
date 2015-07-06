
<center>
<table width='90%' style='border-collapse:collapse;' border='1'><tr align='center'><td bgcolor='#FFD6CF'>
{$top_message}
</td></tr></table>
<br />
<br /><br />
{if $result_url}
<table border='1'>
<tr><td>
結果メールURL
</td></tr><tr><td>
<a href='{$result_url}'>{$result_url}</a>
</td></tr>
</table>
{else}
<table width='90%' border='0'><tr><td>
<form id='YkLoginForm' action="" method="post">
あなたのメールアドレス (Your Email Address):<br />
<input type='text' name='email' value='' size='35' /><br />
<br />
店舗メールアドレス (StoreID)：<br />
<input type='text' name='storeid' value='' size='10' /><b>@mobilestation.jp</b><br />
<br />
<input type='submit' name='send' value='送信' /><br />
</form>
</td></td></table>
{/if}
<br /><br /><br />
</center>