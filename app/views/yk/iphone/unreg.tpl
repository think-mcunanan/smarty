<table width='90%' border='0'>
    <tr>
        <td align='left'>
            <form id='YkRegForm' action="{$form_action}" method="post">
                {if $complete === true}
                    ご利用ありがとうございました
                {else}
                    本当に解約しますか？
                {/if}
                <br />
                
                {if $complete === false}
                    <br />
                    <input type='submit' name='p_unreg' value='解約' style='width: 150px' /><br />
                    <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px' /><br />
                {/if}
            </form>
        </td>
    </tr>
</table>
