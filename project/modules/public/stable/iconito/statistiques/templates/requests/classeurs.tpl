<h2>{$ppo->label}</h2>
<p>Au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span>, il y a :</p>
<ul>
    <li><strong>{$ppo->requestClass->getNombreClasseurs()}</strong> classeur(s)</li>
    {assign var=dossiersEtRatio value=$ppo->requestClass->getNombreDossiersEtRatio()}
    <li><strong>{$dossiersEtRatio.dossiers}</strong> dossier(s) <span class="average">(soit  {$dossiersEtRatio.ratio} dossier par classeur)</span></li>
    {assign var=fichiers value=$ppo->requestClass->getNombreFichiersEtInfos()}
    <li><strong>{$fichiers.count}</strong> fichier(s), avec un poids total de {$fichiers.total|string_format:"%.2f"}Ko <span class="average">(soit  {$fichiers.average|string_format:"%.2f"}Ko par fichier)</span></li>
    <li>dont <strong>{$fichiers.casiers.count}</strong> fichier(s) dans les casiers, avec un poids total de {$fichiers.casiers.total|string_format:"%.2f"}Ko <span class="average">(soit  {$fichiers.casiers.average|string_format:"%.2f"}Ko par fichier)</span></li>
</ul>