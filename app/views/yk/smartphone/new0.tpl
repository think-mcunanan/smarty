<form name='YkNew0Form' action="{$form_action}" method="post">
<table border='1' style='border-collapse: collapse; border: 1px solid #dddddd;' width='95%' cellpadding='5'>
        <tr>
            <td align='left'><br />
                {html_radios name='syscode' options=$gyoshukubun_list selected = $gyoshukubun separator='<br />'}
            </td>
        </tr>
</table>
<br />
<input type="submit" name="p_next"  value="次へ" width="250px" style="cursor: pointer;"><br />
<input type="submit" name="p_cancel" value="キャンセル" width="150px" title="" style="cursor: pointer;"><br />
        <input type="hidden" name="cid" value="{$companyid}" />
        <input type="hidden" name="scd" value="{$storecode}" />
</form>
