<h2>{$ppo->label}</h2>
<p>
    Du {$ppo->filter->publishedBeginDate->format('d/m/Y')} au {$ppo->filter->publishedEndDate->format('d/m/Y')} :
    <ul>
        {assign var=memos value=$ppo->requestClass->getMemos()}
        <li>{$memos.total} mémos créés, soit {$memos.average} mémos par jour.</li>
        {assign var=aFaire value=$ppo->requestClass->getTravailAFaire()}
        <li>{$aFaire.total} travaux ont été donnés à faire, soit {$aFaire.average} travaux par jour.</li>
        {assign var=enClasse value=$ppo->requestClass->getTravailEnClasse()}
        <li>{$enClasse.total} travaux ont été donnés en classe, soit {$enClasse.average} travaux en classe par jour.</li>
    </ul>
</p>