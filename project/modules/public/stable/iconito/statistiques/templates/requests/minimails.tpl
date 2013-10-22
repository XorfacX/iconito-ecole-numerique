<h2>{$ppo->label}</h2>
<p>
    Du {$ppo->filter->publishedFrom->format('d/m/Y')} au {$ppo->filter->publishedTo->format('d/m/Y')} :
    <ul>
        {assign var=minimails value=$ppo->requestClass->getNombreMinimailsEtRatio()}
        <li>{$minimails.minimails} minimails ont été envoyés, soit {$minimails.ratio} minimails par compte ouvert.</li>
    </ul>
</p>