<h2>{$ppo->label}</h2>
<p>Au {$ppo->filter->publishedEndDate->format('d/m/Y')}, il y avait :{$ppo->requestClass->getNombreAgendas()} agenda(s).</p>
<p>
    Du {$ppo->filter->publishedBeginDate->format('d/m/Y')} au {$ppo->filter->publishedEndDate->format('d/m/Y')} :
    <ul>
        {assign var=evenements value=$ppo->requestClass->getNombreEvenementsEtRatio()}
        <li>{$evenements.evenements} événements ont été créés, soit {$evenements.ratio} événements par agenda.</li>
    </ul>
</p>