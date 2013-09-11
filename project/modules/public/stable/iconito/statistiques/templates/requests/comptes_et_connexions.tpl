<h2>{$ppo->label}</h2>
<p>Au {$ppo->filter->publishedEndDate->format('d/m/Y')}, il y avait {$ppo->requestClass->getNombreComptes()} compte(s).</p>
<p>
    Du {$ppo->filter->publishedBeginDate->format('d/m/Y')} au {$ppo->filter->publishedEndDate->format('d/m/Y')} :
    <ul>
        <li>{$ppo->requestClass->getNombreConnexionsAnnuelles()} connexion(s) ont été enregistrée(s) sur des statistiques annuelles.</li>
        <li>{$ppo->requestClass->getNombreConnexionsMensuelles()} connexion(s) ont été enregistrée(s) sur des statistiques mensuelles.</li>
        <li>{$ppo->requestClass->getNombreConnexionsHebdomadaires()} connexion(s) ont été enregistrée(s) sur des statistiques hebdomadaires.</li>
        <li>{$ppo->requestClass->getNombreConnexionsJournalieres()} connexion(s) ont été enregistrée(s) sur des statistiques journalieres.</li>
    </ul>
</p>