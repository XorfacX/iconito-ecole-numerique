<h2>{$ppo->label}</h2>
<p>
    Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :
    <ul>
        {assign var=aFaire value=$ppo->requestClass->getTravailAFaire()}
        <li><strong>{$aFaire.total}</strong> travail(aux) ont été donné(s) à faire <span class="average">(soit {$aFaire.average} travaux par jour)</span></li>
        {assign var=enClasse value=$ppo->requestClass->getTravailEnClasse()}
        <li><strong>{$enClasse.total}</strong> travail(aux) ont été donné(s) en classe <span class="average">(soit {$enClasse.average} travaux en classe par jour)</span></li>
        {assign var=memos value=$ppo->requestClass->getMemos()}
        <li><strong>{$memos.total}</strong> mémo(s) créé(s) <span class="average">(soit {$memos.average} mémo(s) par jour)</span></li>
    </ul>
</p>