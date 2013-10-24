<h2>{$ppo->label}</h2>
<p>Au {$ppo->filter->publishedTo->format('d/m/Y')}, il y avait {$ppo->requestClass->getNombreClasseurs()}  classeurs(s).</p>
<ul>
    {assign var=fichiers value=$ppo->requestClass->getNombreFichiersEtInfos()}
    <li>{$fichiers.count} fichiers ont été créés, avec un poids total de {$fichiers.total}Ko, soit  {$fichiers.average|string_format:"%.2f"}Ko par fichier.</li>
    <li>dont {$fichiers.casiers.count} fichiers ont été créés dans les casiers, avec un poids total de {$fichiers.casiers.total}Ko, soit  {$fichiers.casiers.average}Ko par fichier.</li>
</ul>

<p>
    Du {$ppo->filter->publishedFrom->format('d/m/Y')} au {$ppo->filter->publishedTo->format('d/m/Y')} :
    <ul>
        {assign var=dossiers value=$ppo->requestClass->getNombreDossiersEtRatio()}
        <li>{$dossiers.dossiers} dossiers ont été créés, soit {$dossiers.ratio} dossiers par classeur.</li>
        <li>{$dossiers.dossiers} dossiers ont été créés, soit {$dossiers.ratio} dossiers par classeur.</li>
    </ul>
</p>