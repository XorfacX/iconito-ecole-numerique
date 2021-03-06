	
	{assign var=personne value=1}
	
	{if $ppo->userext}
		{assign var=personne value=0}
		{assign var=index value=0}
		<h2>Personnes externes</h2>
		<table class="viewItems liste comptes_animateurs comptes_animateurs_new">
		<tr>
			<th class="liste_th">Login</th>
			<th class="liste_th">Nom</th>
			<th class="liste_th">Pr&eacute;nom</th>
			<th class="liste_th">Actions</th>
		</tr>
		{foreach from=$ppo->userext item=user name=user}
			<tr class="{if $index%2 eq 0}odd{else}even{/if}">
				<td>{$user->user_infos.login}</td>
				<td>{$user->ext_nom}</td>
				<td>{$user->ext_prenom}</td>
				<td><a class="button button-add" href="{copixurl dest="comptes|animateurs|edit" user_type="USER_EXT" user_id=$user->ext_id}">d&eacute;finir comme animateur</a></td>
			</tr>
			{assign var=index value=$index+1}
		{/foreach}
		</table>
	{/if}
	
	
	{if $ppo->pers.USER_ENS}
		{assign var=personne value=0}
		{assign var=index value=0}
		<h2>Enseignants</h2>
		<table class="viewItems liste comptes_animateurs comptes_animateurs_new">
		<tr>
			<th class="liste_th">Login</th>
			<th class="liste_th">Nom</th>
			<th class="liste_th">Pr&eacute;nom</th>
			<th class="liste_th">Actions</th>
		</tr>
		{foreach from=$ppo->pers.USER_ENS item=user name=user}
			<tr class="{if $index%2 eq 0}odd{else}even{/if}">
				<td>{$user->login_dbuser}</td>
				<td>{$user->nom}</td>
				<td>{$user->prenom}</td>
				<td><a class="button button-add" href="{copixurl dest="comptes|animateurs|edit" user_type=$user->bu_type user_id=$user->bu_id}">d&eacute;finir comme animateur</a></td>
			</tr>
			{assign var=index value=$index+1}
		{/foreach}
		</table>
	{/if}
	
	{if $ppo->pers.USER_VIL}
		{assign var=personne value=0}
		{assign var=index value=0}
		<h2>Agents de ville</h2>
		<table class="viewItems liste comptes_animateurs comptes_animateurs_new">
		<tr>
			<th class="liste_th">Login</th>
			<th class="liste_th">Nom</th>
			<th class="liste_th">Pr&eacute;nom</th>
			<th class="liste_th">Actions</th>
		</tr>
		{foreach from=$ppo->pers.USER_VIL item=user name=user}
			<tr class="{if $index%2 eq 0}odd{else}even{/if}">
				<td>{$user->login_dbuser}</td>
				<td>{$user->nom}</td>
				<td>{$user->prenom}</td>
				<td><a class="button button-add" href="{copixurl dest="comptes|animateurs|edit" user_type=$user->bu_type user_id=$user->bu_id}">d&eacute;finir comme animateur</a></td>
			</tr>
			{assign var=index value=$index+1}
		{/foreach}
		</table>
	{/if}
	
	{if $ppo->pers.USER_ADM}
		{assign var=personne value=0}
		{assign var=index value=0}
		<h2>Personnels administratif</h2>
		<table class="viewItems liste comptes_animateurs comptes_animateurs_new">
		<tr>
			<th class="liste_th">Login</th>
			<th class="liste_th">Nom</th>
			<th class="liste_th">Pr&eacute;nom</th>
			<th class="liste_th">Actions</th>
		</tr>
		{foreach from=$ppo->pers.USER_ADM item=user name=user}
			<tr class="{if $index%2 eq 0}odd{else}even{/if}">
				<td>{$user->login_dbuser}</td>
				<td>{$user->nom}</td>
				<td>{$user->prenom}</td>
				<td><a class="button button-add" href="{copixurl dest="comptes|animateurs|edit" user_type=$user->bu_type user_id=$user->bu_id}">d&eacute;finir comme animateur</a></td>
			</tr>
			{assign var=index value=$index+1}
		{/foreach}
		</table>
	{/if}
		

	{if $personne}<p><i>Il n'y a plus personne &agrave; ajouter en tant qu'animateur...</i></p>{/if}