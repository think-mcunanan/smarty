<form id='YkNew5Form' action="{$form_action}" method="post">
    <table width='90%' border='0'>
        <tr>
            <td align='center'><br />
                担当者:{$trans_staff}<br />
                日付:{$trans_date}<br />
                時間:{$trans_time}<br />
                選択メニュー: <br />
                {$trans_services}<br />
                <td>
                    </tr>
                    <tr><td align='center'>
                <input type='submit' name='p_confirm' value='決定' style='width: 150px'><br />
                <input type='submit' name='p_back' value='戻る' style='width: 150px'><br />
                <input type='submit' name='p_cancel' value='キャンセル' style='width: 150px'><br />
            </td>
        </tr>
    </table>
</form>