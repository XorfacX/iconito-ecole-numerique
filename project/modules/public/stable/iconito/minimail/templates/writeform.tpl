
<form action="{copixurl dest="minimail||doSend"}" method="post" ENCTYPE="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="{$attachment_size}">
<input type="hidden" name="go" value="preview" />
<input type="hidden" name="format" value="{$format}" />
<input type="hidden" name="reply" value="{$reply}" />
<input type="hidden" name="forward" value="{$forward}" />

{if not $errors eq null}
	<div title="{i18n key=kernel|kernel.error.problem}">
	<ul class="mesgErrors">
	{foreach from=$errors item=error}
		<li>{$error}</li>
	{/foreach}
	</ul>
  </div>
{/if}

{if $preview and !$errors}
<h3>{i18n key="minimail.preview"}</h3>
<div class="minimail_message">
<div><b>{$title}</b></div>
<HR CLASS="minimail_hr" NOSHADE SIZE="1" />
<div>{$message|render:$format}</div>
</div>
{/if}

<br/>
<table class="editItems">
	<tr>
		<td CLASS="form_libelle"><nobr>{i18n key="minimail.form.dest"}{help mode="tooltip" text_i18n="minimail|minimail.help.dest"}</nobr>

</td><td CLASS="form_saisie">

    <textarea name="dest" id="dest" class="form" cols="78" rows="2">{$dest|escape:'htmlall'}</textarea>
    {$linkpopup}<br/><em class="legendDescription">{i18n key="minimail.form.destInfo"}</em>

    </td>
	</tr>
	<tr>
		<td CLASS="form_libelle">{i18n key="minimail.form.title"}</td><td CLASS="form_saisie"><input type="text" name="title" value="{$title|escape:'htmlall'}" class="form" style="width: 400px;" maxlength="80" /></td>
	</tr>
	<tr>
		<td CLASS="form_libelle">{i18n key="minimail.form.message"}</td><td CLASS="form_saisie">{$message_edition}</td>
	</tr>
	
	<tr>
		<td CLASS="form_libelle">{i18n key="minimail.form.attachments"}</td><td CLASS="form_saisie">
		
		<em class="legendDescription">{i18n key="minimail.form.attachmentsInfo" 1=$attachment_size|human_file_size} </em>
	<br/>1. <INPUT class="form" style="margin: 2px;" TYPE="file" NAME="attachment1" ></INPUT>
	<br/>2. <INPUT class="form" style="margin: 2px;" TYPE="file" NAME="attachment2" ></INPUT>
	<br/>3. <INPUT class="form" style="margin: 2px;" TYPE="file" NAME="attachment3" ></INPUT>
		
		</td>
	</tr>
	<tr>
    	<td colspan="2" class="form_submit center">
        	<input class="button button-cancel" onclick="self.location='{copixurl dest="minimail||getListRecv"}'" type="button" value="{i18n key="minimail.btn.cancel"}" /> 
            <input class="button button-view" type="submit" onClick="goMinimail(this.form, 'preview');" value="{i18n key="minimail.btn.preview"}" />
            <input class="button button-confirm" type="submit" onClick="goMinimail(this.form, 'save');" value="{i18n key="minimail.btn.send"}" /> 
        </td>
    </tr>
</table>
</form>

{literal}
<script type="text/javascript">
jQuery(document).ready(function($){
  $('#message').setCursorPosition(0);
  $('#message').focus();
});
</script>
{/literal}

