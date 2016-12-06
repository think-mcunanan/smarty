<!-- Created by jonathanparel, 20160925; RM#1724; -------------------------ii-->
<!-- IPHONE -->

<div class="new0">
    <hr id="hr_slim"/>
    <div id="bground_mat">
        <form name='YkNew0Form' action="{$form_action}" method="post">
            <div style="text-align:left; padding-left: 30%;">
                {html_radios name='syscode' options=$gyoshukubun_list selected = $gyoshukubun separator='<br />'}
            </div>

            <hr id="hr_before-button"/>
            <div style="display: inline-block; max-width: 50%;">
                <input class="groovybutton" type="submit" name="p_cancel" value="キャンセル" title="" style="cursor: pointer; width:120px">
            </div> 


            <div style="display: inline-block; max-width: 50%;">
                <input class="groovybutton" type="submit" name="p_next"  value="次へ" style="cursor: pointer; width: 120px">
            </div>

            <input type="hidden" name="cid" value="{$companyid}" />
            <input type="hidden" name="scd" value="{$storecode}" />
        </form>
    </div>
</div>
    <!-- Created by jonathanparel, 20160925; RM#1724; -------------------------xx-->