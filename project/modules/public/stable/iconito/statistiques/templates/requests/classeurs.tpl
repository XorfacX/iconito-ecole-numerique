<h2>{$ppo->label}</h2>
<p>Au {$ppo->filter->publishedTo->format('d/m/Y')}, il y avait {$ppo->requestClass->getNombreClasseurs()}  classeurs(s).</p>
<p>
    Du {$ppo->filter->publishedFrom->format('d/m/Y')} au {$ppo->filter->publishedTo->format('d/m/Y')} :
    <ul>
        {assign var=dossiers value=$ppo->requestClass->getNombreDossiersEtRatio()}
        <li>{$dossiers.dossiers} dossiers ont été créés, soit {$dossiers.ratio} dossiers par classeur.</li>
        <li>{$dossiers.dossiers} dossiers ont été créés, soit {$dossiers.ratio} dossiers par classeur.</li>
    </ul>
</p>

{$ppo->requestClass->getNombreFichiersEtInfos()}