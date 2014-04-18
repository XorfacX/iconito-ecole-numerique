<h2>{$ppo->label} {copixzone process=statistiques|exportcsv part='agenda'}</h2>
<p>Au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span>, il y a : <span class="dateStats">{$ppo->requestClass->getNombreAgendas()}</span> agenda(s).</p>
<p>
    Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :
    <ul>
        {assign var=evenements value=$ppo->requestClass->getNombreEvenementsEtRatio()}
        <li>{$evenements.evenements} événement(s) ont été créé(s) <span class="average">(soit {$evenements.ratio} événement(s) par agenda)</span></li>
    </ul>
</p>