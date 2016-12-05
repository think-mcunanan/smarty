<!--Optimized by jonathanparel, 20160907 For Mobile Devices; RM#1724; ii-->

<div class="new0">
    <br />
    <form name='YkNew0Form' action="{$form_action}" method="post">

        <div>
            <img src="{$html->url('/img/new/arrowgradopp.gif')}" height="21">
            <img src="{$html->url('/img/new/start.gif')}" height="20">
            <img src="{$html->url('/img/new/1shimei.gif')}" height="25">
            <img src="{$html->url('/img/new/2gijutu.gif')}" height="25">
            <img src="{$html->url('/img/new/3hiduke.gif')}" height="25">
            <img src="{$html->url('/img/new/4jikan.gif')}" height="25">
            <img src="{$html->url('/img/new/5touroku.gif')}" height="25">
            <img src="{$html->url('/img/new/end.gif')}" height="20">
        </div>

        <hr id="hr_before-button">
        <div id="bground_mat" style="width: 80%">
            <br />
            施術を選択して下さい：
            <br />
            <br />
            {html_radios name='syscode' options=$gyoshukubun_list selected = $gyoshukubun separator='    '}
            <br />
            <br />
            <br />

        <hr id="hr_before-button">
        <input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;">
        <input type="submit" name="p_next" class="groovybutton" value="次へ" title="" style="cursor: pointer;">
        
        <input type="hidden" name="cid" value="{$companyid}" />
        <input type="hidden" name="scd" value="{$storecode}" />
</div>
    </form>
</div>
<!--Optimized by jonathanparel, 20160907 For Mobile Devices; RM#1724; xx-->