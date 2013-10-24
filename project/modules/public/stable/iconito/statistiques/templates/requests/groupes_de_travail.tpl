<h2>{$ppo->label}</h2>
<p>
    Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :
    <ul>
        {assign var=messages value=$ppo->requestClass->getNombreMessagesEtRatio()}
        <li><strong>{$messages.total}</strong> message(s) créée(s) <span class="average">(soit {$messages.average} messages par discussion)</span></li>
        {assign var=discussions value=$ppo->requestClass->getNombreDiscussionsEtRatio()}
        <li><strong>{$discussions.total}</strong> discussion(s) créée(s) <span class="average">(soit {$discussions.average} discussion(s) par groupe ayant le module forum activé)</span></li>
    </ul>
</p>