<p class="right"><a href="{copixurl dest="comptes||getUserExtMod" id=0}" class="button button-add">{i18n key="comptes|comptes.strings.add" noEscape=1}</a></p>
{i18n key="comptes|comptes.expl.getuserext" noEscape=1}

{if $userlist neq null}
	<table class="viewItems">
		<tr>
			<th>{i18n key="comptes|comptes.colonne.nom"}</th>
			<th>{i18n key="comptes|comptes.colonne.prenom"}</th>
			<th >{i18n key="comptes|comptes.colonne.login"}</th>
			<th>{i18n key="comptes|comptes.colonne.action"}</th>
		</tr>
		{counter assign="i" name="i" start="1"}
		{foreach from=$userlist item=user}
			{counter name="i"}
			<tr class="list_line{math equation="x%2" x=$i}">
				<td >{$user->ext_nom|escape}</td>
				<td >{$user->ext_prenom|escape}</td>
				<td ><i>{$user->bu2user->user_login}</i></td>
				<td><nobr>
				<a class="button button-update" href="{copixurl dest="comptes||getUserExtMod" id=$user->ext_id}">{i18n key="comptes|comptes.strings.mod"}</a>
				{if $user->ext_id != 1}
				<a class="button button-delete" href="{copixurl dest="comptes||getUserExtMod" id="-`$user->ext_id`"}">{i18n key="comptes|comptes.strings.del"}</a>
				{/if}
				</nobr>
				</td>
			</tr>
		{/foreach}
	</table>
{else}
	<p class="error">{i18n key="comptes|comptes.alert.nouserext"}</p>
{/if}
