<!--Optimized by jonathanparel, 20160909 For Mobile Devices; RM#1724; ii-->
<div class="new5" align="center">
    <form name='YkNew4Form' action="{$form_action}" method="post">

        <div>
            <img src="{$html->url('/img/new/start.gif')}" height="20">
            <img src="{$html->url('/img/new/1shimei.gif')}" height="25">
            <img src="{$html->url('/img/new/2gijutu.gif')}" height="25">
            <img src="{$html->url('/img/new/3hiduke.gif')}" height="25">
            <img src="{$html->url('/img/new/4jikan.gif')}" height="25">
            <img src="{$html->url('/img/new/touroku.gif')}" height="25">
            <img src="{$html->url('/img/new/arrowgradopp.gif')}" height="21">
            <img src="{$html->url('/img/new/end.gif')}" height="20">
        </div>

        <hr width="75%">
        <br />
        <table border='0' width='400'>
            <tr>
                <td>
                    <div class="rbroundboxc">
                        <div class="rbtopc">
                            <div>
                            </div>
                        </div>

                        <div class="rbcontentc">
                            <table border='0' cellpadding='6'>
                                <tr>
                                    <td align='right'><b>担当者:</b></td>
                                    <td align='left'>{$trans_staff}</td>
                                </tr>
                                <tr>
                                    <td align='right'><b>日付:</b></td>
                                    <td align='left'>{$trans_date}</td>
                                </tr>
                                <tr>
                                    <td align='right'><b>時間:</b></td>
                                    <td align='left'>{$trans_time}</td>
                                </tr>
                                <tr>
                                    <td align='right' valign='top'><b>メニュー選択:</b></td>
                                    <td align='left'>{$trans_services}</td>
                                </tr>
                            </table>
                        </div>
                                
                        <!-- /rbcontent -->
                        <div class="rbbotc">
                            <div>
                            </div>
                        </div>
                    </div>
                    <!-- /rbroundbox -->
                </td>
            </tr>
        </table>
        <br />
        
        <hr width="75%">
        <div class='buttonframe'>
            <input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;">
            <input type="submit" name="p_back" class="groovybutton" value="戻る" title="" style="cursor: pointer;">
            <input type="submit" name="p_confirm" class="groovybutton" value="決定" title="" style="cursor: pointer;">
        </div>
    </form>
</div>
<!--Optimized by jonathanparel, 20160909 For Mobile Devices; RM#1724; xx-->
