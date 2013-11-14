<h2>{$ppo->label}</h2>

<p>Au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span>, il y a :</p>

<strong>{$ppo->requestClass->getNombreGroupDeTravail()} groupe(s) de travail</strong>

<div>
    <ul>
        {assign var=repartition value=$ppo->requestClass->getRepartitionGroupDeTravailParModule()}
        {foreach from=$repartition key=moduleName item=percent}
            <li><strong>{$percent}%</strong> ont le module <strong>{$moduleName}</strong> activé</li>
        {/foreach}
    </ul>
</div>

<p>
    {assign var=nombreDiscussionsEtRatio value=$ppo->requestClass->getNombreDiscussionsEtRatio()}
    <strong>{$nombreDiscussionsEtRatio.number}</strong>
    discussions ouvertes, soit
    <strong>{$nombreDiscussionsEtRatio.ratio}</strong>
    discussions par groupe de travail (ayant le module forum activé)
</p>
<p>
    {assign var=nombreMessagesEtRatio value=$ppo->requestClass->getNombreMessagesEtRatio()}
    <strong>{$nombreMessagesEtRatio.number}</strong>
    messages, soit
    <strong>{$nombreMessagesEtRatio.ratio}</strong>
    messages par discussion
</p>

<p>Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :</p>

<p>
    {assign var=nombreMinimailsEtRatio value=$ppo->requestClass->getNombreMinimailEtRatio() }
    <strong>{$nombreMinimailsEtRatio.number}</strong>
    minimails envoyés, soit
    <strong>{$nombreMinimailsEtRatio.ratio}</strong>
    minimails par groupe de travail.
    Et <strong>{$nombreMinimailsEtRatio.average}</strong>
    minimails par jour.
</p>
