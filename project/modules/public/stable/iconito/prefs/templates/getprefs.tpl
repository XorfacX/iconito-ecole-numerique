<div class="prefs">

{if $msg}
<div align="center">
<table class="msg {$msg.type}" cellpadding="0" cellspacing="3">
<tr>
{if $msg.image_url}<td class="image" width="1"><img src="{$msg.image_url}" alt="{$msg.image_alt}" align="left"/></td>{/if}
<td class="text">{$msg.value}</td>
</table>
</div>
{/if}

{if $prefs neq null}
<form name="form" id="form" action="{copixurl dest="|setprefs"}" method="POST" enctype="multipart/form-data">
	<table class="prefs" border="0" width="100%" cellspacing="5">
	{foreach from=$prefs item=module}
  
    {if $module.name}
		<tr><td colspan="2">
		<h3 class="prefs" id="{$module.code}">{$module.name}</h3>
		</td></tr>
    {/if}
    
		{foreach from=$module.form item=form}
			{if $form.type == "titre"}
				<tr><td colspan="2">
				<h4 class="prefs">{$form.text}</h4>
				<i>{$form.expl}</i>
				</td></tr>
			{elseif $form.type == "separator"}
				<tr><td colspan="2"><hr /></td></tr>
			{elseif $form.type == "password"}
				<tr><th>{$form.text}</th><td><input {if $form.error}class="error" {/if}type="password" name="{$module.code}_{$form.code}" value="{$form.value}"/>
				{if $form.error}<br /><div class="errormsg">{$form.error}</div>{/if}
				</td></tr>
			{elseif $form.type == "string"}
				<tr><th>{$form.text}</th><td><input {if $form.error}class="error" {/if}type="text" name="{$module.code}_{$form.code}" value="{$form.value}"/>
				{if $form.error}<br /><div class="errormsg">{$form.error}</div>{/if}
				</td></tr>
			{elseif $form.type == "checkbox"}
				<tr><th><!-- {$form.text} --></th><td>
				<div style="maring: 0px; padding: 0px;"{if $form.error} class="error"{/if}>
				<input type="checkbox" name="{$module.code}_{$form.code}" value="1" {if $form.value}checked {/if}/>
				<b>{$form.text}</b>
				</div>
				{if $form.error}<div class="errormsg">{$form.error}</div>{/if}
				</td></tr>
			{elseif $form.type == "radio"}
				<tr><th>{$form.text}</th><td>
				<div style="maring: 0px; padding: 0px;"{if $form.error} class="error"{/if}>
				{foreach from=$form.choices key=choice_key item=choice_val}
				<input type="radio" name="{$module.code}_{$form.code}" value="{$choice_key}" {if $choice_key == $form.value}CHECKED{/if}/>{$choice_val}<br />
				{/foreach}
				</div>
				{if $form.error}<div class="errormsg">{$form.error}</div>{/if}
				</td></tr>
			{elseif $form.type == "upload"}
				<tr><th>{$form.text}</th><td>
				<div style="maring: 0px; padding: 0px;"{if $form.error} class="error"{/if}>
				<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
				<input type="file" name="{$module.code}_{$form.code}"/>{$choice_val}
				</div>
				{if $form.error}<div class="errormsg">{$form.error}</div>{/if}
				</td></tr>
			{elseif $form.type == "image"}
				{if $form.value}
				<tr><th>{$form.text}</th><td>
				<div style="maring: 0px; padding: 0px;"{if $form.error} class="error"{/if}>
				<img src="{copixurl}{$form.value}" alt="{$form.value}" />
				</div>
				{if $form.error}<div class="errormsg">{$form.error}</div>{/if}
				</td></tr>
				{/if}

<!--
code: alerte_mail_type
type: radio
text: A chaque message|Une fois par jour
value: always|onceaday
-->

			{else}
				<tr><th>Todo</th><td>
				{foreach from=$form key=element_type item=element_value}
					{if $element_type == ""}
					{elseif $element_type == ""}
					{else}
						{$element_type}: {$element_value}<br />
					{/if}
				{/foreach}
				</td></tr>
			{/if}

		{/foreach}
    
    {if $module.name}
		<tr><td colspan="2"><hr /></td></tr>
    {/if}
    
	{/foreach}
	</table>

<div align="right">
<input class="form_button" type="submit" value="{i18n key='prefs.config.submit'}" />
</div>

</form>
{/if}
</div>
