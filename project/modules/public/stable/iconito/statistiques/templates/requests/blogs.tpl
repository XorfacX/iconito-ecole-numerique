<h2>{$ppo->label}</h2>
<p>Au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span>, il y a :
    <ul>
        <li><strong>{$ppo->requestClass->getNombreBlogs()}</strong> blog(s) ouvert(s).</li>
        {assign var=blogsVisibles value=$ppo->requestClass->getBlogVisibleAnnuaireEtNonVisible()}
        <li><strong>{$blogsVisibles.visible_annuaire}</strong> blog(s) visible(s) dans l'annuaire <span class="average">(<strong>{$blogsVisibles.non_visible}</strong> blogs non-visibles dans l'annuaire)</span></li>
        {assign var=visibilite value=$ppo->requestClass->getBlogVisibleEtVisibilite()}
        <li><strong>{$visibilite.visible_internet}</strong> blog(s) visible(s) sur internet</li>
        <li><strong>{$visibilite.visible_membres_groupe}</strong> blog(s) visible(s) par les membres des groupes</li>
        <li><strong>{$visibilite.visible_membres_iconito}</strong> blog(s) visible(s) par les membres Iconito</li>
        {assign var=nombreRubriquesEtRatio value=$ppo->requestClass->getNombreRubriquesEtRatio()}
        <li><strong>{$nombreRubriquesEtRatio.rubriques}</strong> rubrique(s) <span class="average">(<strong>{$nombreRubriquesEtRatio.ratio}</strong> rubrique(s) par blog)</span></li>
        {assign var=nombrePagesEtRatio value=$ppo->requestClass->getNombrePagesEtRatio()}
        <li><strong>{$nombrePagesEtRatio.pages}</strong> page(s) <span class="average">(<strong>{$nombrePagesEtRatio.ratio}</strong> page(s) par blog)</span></li>
    </ul>
</p>

<p>
    Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :
    <ul>

        {assign var=nombreVisitesEtRatio value=$ppo->requestClass->getNombreVisitesEtRatio()}
        <li><strong>{$nombreVisitesEtRatio.visites}</strong> visite(s) <span class="average">(<strong>{$nombreVisitesEtRatio.ratio}</strong> visite(s) par blog)</span></li>
        {assign var=articles value=$ppo->requestClass->getArticlesRedigesSurPeriode()}
        <li><strong>{$articles.articles}</strong> article(s) ont été rédigé(s) <span class="average">(soit {$articles.nb_moyen_par_jour} article(s) par jour)</span></li>
        {assign var=commentaires value=$ppo->requestClass->getCommentairesRedigesSurPeriode()}
        <li><strong>{$commentaires.commentaires}</strong> commentaire(s) ont été rédigé(s) <span class="average">(soit {$commentaires.nb_moyen_par_jour} commentaire(s) par jour)</span></li>
    </ul>
</p>

<h3>Détail des rédaction d'article par profil</h3>

<table class="viewItems visualize">
    <tbody>
    {foreach from=$ppo->requestClass->getNombreArticleParProfil() key=profile item=number}
        <tr>
            <th>{$profile}</th>
            <td>{$number}</td>
        </tr>
    {/foreach}
    </tbody>
</table>