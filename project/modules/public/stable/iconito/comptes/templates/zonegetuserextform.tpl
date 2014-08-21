<form action="{copixurl dest="comptes|default|getUserExtMod" id=$user->ext_id}" method="post">

<input type="hidden" name="mode" value="{$mode}" />

{if $errors}
<div class="mesgErrors">
 {ulli values=$errors}
</div>
{/if}
<table class="editItems">
	<tr>
		<th class="form_libelle">{i18n key="comptes.colonne.nom"   } </th><td class="form_saisie"><input type="text" name="nom"    value="{$user->ext_nom|escape:'html'   }" class="form" />
		</td>
	</tr>
	<tr>
		<th class="form_libelle">{i18n key="comptes.colonne.prenom"} </th><td class="form_saisie"><input type="text" name="prenom" value="{$user->ext_prenom|escape:'html'}" class="form" /></td>
	</tr>
	
	{if !$user->ext_id}
	<tr>
		<th class="form_libelle">{i18n key="comptes.colonne.login"} </th><td class="form_saisie"><input type="text" name="login" value="{$user->ext_login|escape:'htmlall'}" class="form" {if $user->ext_id}disabled="disabled"{/if} />
		</td>
	</tr>
	{/if}
	
	<tr>
		<th class="form_libelle">{i18n key="comptes.colonne.passwd1"} </th><td class="form_saisie"><input type="password" name="passwd1" value="" class="form" />
		</td>
	</tr>
	<tr>
		<th class="form_libelle">{i18n key="comptes.colonne.passwd2"} </th><td class="form_saisie"><input type="password" name="passwd2" value="" class="form" />
		</td>
	</tr>
	<tr><td></td><td class="form_submit">
		<a class="button button-cancel" href="{copixurl dest="comptes||getUserExt"}">{i18n key="comptes.form.cancel"}</a>
		<input class="button button-save" type="submit" value="{i18n key="comptes.form.submit"}" />
	</td></tr>
	
</table>

</form>