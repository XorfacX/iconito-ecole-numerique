
<table class="welcome">
<tbody>
<tr>
<td class="ecoles">

{if $zoneEcoles}
	{$zoneEcoles}
{else}
	<img src="{copixresource path="img/iconito-home2.gif"}" width="209" height="234" alt="Iconito vous souhaite la bienvenue" style="margin-right:20px;" />
{/if}


</td>
<td>

{if $zonePhotos || $zoneActualites}

	{$zonePhotos}

	{$zoneActualites}

{else}
	<h3>Bienvenue sur Iconito, le portail num�rique scolaire libre.</h3>
	
	<p>Iconito est un portail �ducatif comprenant un ensemble d'outils et de ressources � destination des enseignants et des �l�ves, mais aussi des parents et des autres intervenants du syst�me scolaire. Il est d�velopp� sous licence libre (GNU GPL).</p>
	
	<a class="button_like" href="{copixurl dest="auth||login"}">Connexion &agrave; Iconito</a>
	
	<br/>
	
	</div>
	
	<br/>
	<div class="cartouche">
	<a href="{copixurl dest="public||"}"><img class="logo" src="{copixresource path="img/welcome/welcome-blog.gif"}" alt="Logo Blogs" border="0"/></a>
	<h4>Consultez les publications</h4>
	<p>
	Ecoles, classes, villes ou groupes de travail, ils peuvent tous publier des blogs.</p> 
	<br/>
	<a class="button_like" href="{copixurl dest="public||"}">{i18n key=public|public.blog.annuaire}</a>
	<span class="rss"><a title="RSS" href="{copixurl dest="public||rss"}"><img src="{copixresource path="img/blog/feed-icon-16x16.png"}" width="16" height="16" border="0" alt="RSS" title="RSS" /> Flux RSS</a></span>
	</div>
	
	<br/>
	<div class="astuce"><b>Astuce</b> - Vous pouvez t�l�charger un logo pour votre blog. Allez dans Administration du blog, Options, Modifier, puis t�l�charger le logo. Une bonne taille de logo est 150 x 150 pixels par exemple!
	</div>

{/if}
	

</td>
</tr>
</tbody>
</table>

<div class="small" style="clear:both;">
<hr/>
{if $isDemo}
Ceci est un site de d�monstration. Nous ne sommes pas responsables des contenus que les internautes peuvent publier sur ce site dans le cadre de leurs tests. Pour toute information, n'h�sitez pas � nous contacter: <a href="mailto:dev@iconito.org">dev@iconito.org</a><p>
{else}
Les derni�res informations sur le d�veloppement d'Iconito sont consultables sur <a href="http://www.iconito.org">iconito.org</a>. Pour toute information, n'h�sitez pas � contacter directement l'�quipe des d�veloppeurs: <a href="mailto:dev@iconito.org">dev@iconito.org</a>
{/if}
</div>
