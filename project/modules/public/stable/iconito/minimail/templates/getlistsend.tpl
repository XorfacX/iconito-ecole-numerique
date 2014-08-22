
{if $list|@count}

<FORM NAME="form" ID="form" ACTION="{copixurl dest="|doDelete"}" METHOD="POST">
    <INPUT TYPE="hidden" NAME="mode" VALUE="send" />
    <table class="viewItems">
        <tr>
            <th>{i18n key="minimail.list.title"}</th>
            <th>{i18n key="minimail.list.to"}</th>
            <th>{i18n key="minimail.list.attach"}</th>
            <th>{i18n key="minimail.list.date"}</th>
            <th>{i18n key="minimail.list.read"}</th>
            <th>{i18n key="minimail.list.delete"}</th>
        </tr>
		{counter assign="i" name="i"}
		{foreach from=$list item=mp}
			{counter name="i"}
            <tr class="{if $i%2 eq 0}odd{else}even{/if}">
                <td><a href="{copixurl dest="|getMessage" id=$mp->id}">{$mp->title}</a></td>
                <td>{assign var=sep value=""}{assign var=is_read value=0}{foreach from=$mp->destin item=dest}{$sep}

    {user label=$dest->to_id_infos userType=$dest->to.type userId=$dest->to.id linkAttribs='STYLE="text-decoration:none;";'}{assign var=sep value=", "}{if $dest->is_read eq 1}{assign var=is_read value=1}{/if}{/foreach}</td>
                <td class="center">{if $mp->attachment1 }<IMG src="{copixresource path="img/minimail/attachment.gif"}" ALT="{i18n key="minimail.msg.attachments"}" TITLE="{i18n key="minimail.msg.attachments"}" />{/if}</td>
                <td class="center"><NOBR>{$mp->date_send|datei18n:"date_short_time"}</NOBR></td>
                <td class="center"><img width="20" height="20" src="{copixresource path="img/minimail/status`$is_read*1`00.png"}" /></td>
                <td class="center"><input type="checkbox" name="messages[]" value="{$mp->id}" class="noBorder" /></td>
            </tr>
		{/foreach}
        <tr class="liste_footer">
            <td colspan="5"></td>
            <td class="center"><a class="button button-delete" href="javascript:deleteMsgs();">{i18n key="minimail.btn.delete"}</a></td>
        </tr>
    </table>

    {$pager}

</FORM>

{else}
<p>{i18n key="minimail.list.empty"}</p>
{/if}
