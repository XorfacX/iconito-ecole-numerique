<h2>{$ppo->label}</h2>

<p>Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :</p>
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

<p>Du {$ppo->filter->publishedFrom->format('d/m/Y')} au {$ppo->filter->publishedTo->format('d/m/Y')} :</p>

<p>
    <strong></strong>
    minimails envoyés, soit
    <strong></strong>
    minimails par groupe de travail
</p>

<h3>Moyennes de minimails envoyés par jour</h3>


<ul>

</ul>
