<h2>{$ppo->label}</h2>
<p>Au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span>, il y a <strong>{$ppo->requestClass->getNombreClasseurs()}</strong>  classeurs(s).</p>
<ul>
    {assign var=fichiers value=$ppo->requestClass->getNombreFichiersEtInfos()}
    <li><strong>{$fichiers.count}</strong> fichier(s) ont été créé(s), avec un poids total de {$fichiers.total}Ko <span class="average">(soit  {$fichiers.average|string_format:"%.2f"}Ko par fichier)</span></li>
    <li>dont <strong>{$fichiers.casiers.count}</strong> fichier(s) ont été créé(s) dans les casiers, avec un poids total de {$fichiers.casiers.total}Ko <span class="average">(soit  {$fichiers.casiers.average}Ko par fichier)</span></li>
</ul>

<p>
    Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :
    <ul>
        {assign var=dossiers value=$ppo->requestClass->getNombreDossiersEtRatio()}
        <li><strong>{$dossiers.dossiers}</strong> dossier(s) ont été créé(s) <span class="average">(soit {$dossiers.ratio} dossiers par classeur)</span></li>
        <li><strong>{$dossiers.dossiers}</strong> dossier(s) ont été créé(s)  <span class="average">(soit {$dossiers.ratio} dossiers par classeur)</span></li>
    </ul>
</p>