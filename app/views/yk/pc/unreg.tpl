<center>
    <form name='YkRegForm' action="{$form_action}" method="post">
{if $complete === true}
        ご利用ありがとうございました
{else}
        本当に解約しますか？
{/if}
        <br />
        <br />
{if $complete === false}
        <div class='buttonframe'>
            <input type="submit" name="p_cancel" class="groovybutton" value="キャンセル" title="" style="cursor: pointer;"> &nbsp; <input type="submit" name="p_unreg" class="groovybutton" value="解約" title="" style="cursor: pointer;">
        </div>
{/if}
    </form>
</center>
