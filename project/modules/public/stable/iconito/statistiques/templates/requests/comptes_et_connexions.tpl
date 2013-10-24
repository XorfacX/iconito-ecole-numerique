<h2>{$ppo->label}</h2>
<p>Au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span>, il y a <strong>{$ppo->requestClass->getNombreComptes()}</strong> compte(s).</p>
<div>
    <h3>Nombre de compte par profil</h3>
    {assign var=comptesParProfil value=$ppo->requestClass->getNombreComptesParProfil()}
    <ul>
        {foreach from=$comptesParProfil key=profile item=numberOfAccount}
            <li>{$profile} : <strong>{$numberOfAccount}</strong></li>
        {/foreach}
    </ul>
</div>

<div>
    Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :

    Il y a eu <strong>{$ppo->requestClass->getNombreConnexions()}</strong> connexion(s).

    <h3>Statistiques annuelles</h3>

    <ul>
        {foreach from=$ppo->requestClass->getConnexionsAnnuelles() key=year item=numberOfConnection}
            <li>{$year} : <strong>{$numberOfConnection}</strong></li>
        {/foreach}
    </ul>

    <h3>Statistiques mensuelles</h3>

    {assign var=connexionsMensuelles value=$ppo->requestClass->getConnexionsMensuelles()}
    <ul>
        {foreach from=$connexionsMensuelles.statistiques key=month item=numberOfConnection}
            <li>{$month} : <strong>{$numberOfConnection}</strong></li>
        {/foreach}
    </ul>

    {if $connexionsMensuelles.afficherMoyennes}
        <h4>Moyennes</h4>

        <ul>
            {foreach from=$connexionsMensuelles.moyennes key=month item=numberOfConnection}
                <li>{$month} : <strong>{$numberOfConnection}</strong></li>
            {/foreach}
        </ul>
    {/if}

    <h3>Statistiques hebdomadaires</h3>

    {assign var=connexionsHebdomadaires value=$ppo->requestClass->getConnexionsHebdomadaires()}
    <ul>
        {foreach from=$connexionsHebdomadaires.statistiques key=week item=numberOfConnection}
            <li>{$week} : <strong>{$numberOfConnection}</strong></li>
        {/foreach}
    </ul>

    <h3>Statistiques journalières</h3>

    {assign var=connexionsJournalieres value=$ppo->requestClass->getConnexionsJournalieres()}
    <ul>
        {foreach from=$connexionsJournalieres.statistiques key=day item=numberOfConnection}
            <li>{$day} : <strong>{$numberOfConnection}</strong></li>
        {/foreach}
    </ul>

    {if $connexionsJournalieres.afficherMoyennes}
        <h4>Moyennes</h4>

        <ul>
            {foreach from=$connexionsJournalieres.moyennes key=day item=numberOfConnection}
                <li>{$day} : <strong>{$numberOfConnection}</strong></li>
            {/foreach}
        </ul>
    {/if}

    <h3>Statistiques horaires</h3>

    {assign var=connexionsHoraires value=$ppo->requestClass->getConnexionsHoraires()}
    <ul>
        {foreach from=$connexionsHoraires.moyennes key=hour item=numberOfConnection}
            <li>{$hour} : <strong>{$numberOfConnection}</strong></li>
        {/foreach}
    </ul>

</div>