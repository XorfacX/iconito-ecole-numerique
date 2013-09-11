<h2>{$ppo->label}</h2>
<p>
    Du {$ppo->filter->publishedBeginDate->format('d/m/Y')} au {$ppo->filter->publishedEndDate->format('d/m/Y')} :
    <ul>
        {assign var=messages value=$ppo->requestClass->getNombreMessagesEtRatio()}
        <li>{$messages.total} discussions créées, soit {$messages.average} messages par discussion</li>
        {assign var=discussions value=$ppo->requestClass->getNombreDiscussionsEtRatio()}
        <li>{$discussions.total} discussions créées, soit {$discussions.average} discussions par groupe ayant le module forum activé</li>
    </ul>
</p>