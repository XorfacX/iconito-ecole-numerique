<h2>{$ppo->label}</h2>
<p>Au {$ppo->filter->publishedTo->format('d/m/Y')}, il y avait :
    <ul>
        <li>{$ppo->requestClass->getNombreBlogs()} blogs ouverts.</li>
        {assign var=blogsVisibles value=$ppo->requestClass->getBlogVisibleAnnuaireEtNonVisible()}
        <li>{$blogsVisibles.visible_annuaire} blogs visibles dans l'annuaire</li>
        <li>{$blogsVisibles.non_visible} blogs non-visibles dans l'annuaire</li>
        {assign var=visibilite value=$ppo->requestClass->getBlogVisibleEtVisibilite()}
        <li>{$visibilite.visible_internet} blogs visibles sur internet</li>
        <li>{$visibilite.visible_membres_groupe} blogs visibles par les membres des groupes</li>
        <li>{$visibilite.visible_membres_iconito} blogs visibles par les membres Iconito</li>
    </ul>
</p>

<p>
    Du {$ppo->filter->publishedFrom->format('d/m/Y')} au {$ppo->filter->publishedTo->format('d/m/Y')} :
    <ul>
        {assign var=articles value=$ppo->requestClass->getArticlesRedigesSurPeriode()}
        <li>{$articles.articles} articles ont été rédigés, soit {$articles.nb_moyen_par_jour} articles par jour.</li>
        {assign var=commentaires value=$ppo->requestClass->getCommentairesRedigesSurPeriode()}
        <li>{$commentaires.commentaires} commentaires ont été rédigés, soit {$commentaires.nb_moyen_par_jour} commentaires par jour.</li>
    </ul>
</p>