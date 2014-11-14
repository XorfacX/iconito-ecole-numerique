<h2>{$ppo->label} {copixzone process=statistiques|exportcsv part='groupesDeTravail'}</h2>

<p>Au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span>, il y a <strong>{$ppo->requestClass->getNombreGroupDeTravail()}</strong> groupe(s) de travail, dont :</p>

<strong>{$ppo->requestClass->getNombreGroupDeTravail()} groupe(s) de travail existants</strong>

<div>
    <ul>
        {assign var=repartition value=$ppo->requestClass->getRepartitionGroupDeTravailParModule()}
        {foreach from=$repartition key=moduleName item=percent}
            <li><strong>{$percent}%</strong> ont le module <strong>{$moduleName}</strong> activé</li>
        {/foreach}
    </ul>
</div>

Au sein des forums :

<p>
    {assign var=nombreDiscussionsEtRatio value=$ppo->requestClass->getNombreDiscussionsEtRatio()}
    <strong>{$nombreDiscussionsEtRatio.number}</strong>
    discussions ouvertes <span class="average">(soit <strong>{$nombreDiscussionsEtRatio.ratio}</strong> discussions par groupe de travail ayant le module forum activé)</span>
</p>
<p>
    {assign var=nombreMessagesEtRatio value=$ppo->requestClass->getNombreMessagesEtRatio()}
    <strong>{$nombreMessagesEtRatio.number}</strong>
    messages <span class="average">(soit <strong>{$nombreMessagesEtRatio.ratio}</strong> messages par discussion)</span>
</p>

<p>Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :</p>

<p>
    {assign var=nombreMinimailsEtRatio value=$ppo->requestClass->getNombreMinimailEtRatio() }
    <strong>{$nombreMinimailsEtRatio.number}</strong>
    minimails envoyés via les listes de diffusion des groupes de travail <span class="average">(soit <strong>{$nombreMinimailsEtRatio.ratio}</strong> minimails par groupe de travail et <strong>{$nombreMinimailsEtRatio.average}</strong> minimails par jour pour l'ensemble des groupes)</span>
</p>
