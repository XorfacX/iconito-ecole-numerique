<h2>{$ppo->label}</h2>
<p>
    Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :
    <ul>
        {assign var=minimails value=$ppo->requestClass->getNombreMinimailsEtRatio()}
        <li>{$minimails.minimails} minimail(s) ont été envoyé(s)   <span class="average">(soit {$minimails.ratio} minimail(s) par compte ouvert)</span></li>
    </ul>
</p>