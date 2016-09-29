<!-- Created by jonathanparel, 20160925; RM#1724; -------------------------ii-->
<!-- SMARTPHONE -->
<div id="new0" style="margin: 0;">
    <form name='YkNew0Form' action="{$form_action}" method="post">
        <div style="padding-left: 30%; padding-right: 30%; text-align: left;">
            <br />
            {html_radios name='syscode' options=$gyoshukubun_list selected = $gyoshukubun separator='<br />'}
        </div>

        <hr />
        <div style="display: inline-block; max-width: 50%;">
            <input class="groovybutton" type="submit" name="p_cancel" value="キャンセル" title="" style="cursor: pointer; width:100px">
        </div> 
        
        
        <div style="display: inline-block; max-width: 50%;">
            <input class="groovybutton" type="submit" name="p_next"  value="次へ" style="cursor: pointer; width: 100px">
        </div>
        
        <input type="hidden" name="cid" value="{$companyid}" />
        <input type="hidden" name="scd" value="{$storecode}" />
    </form>
</div>
    <!-- Created by jonathanparel, 20160925; RM#1724; -------------------------xx-->