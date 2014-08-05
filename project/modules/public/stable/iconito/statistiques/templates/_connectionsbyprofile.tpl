Il y a eu <strong>{$ppo->requestClass->getNombreConnexions($ppo->profile)}</strong> connexion(s).

<h3 class="mt2 mb1">Statistiques annuelles {copixzone process=statistiques|exportcsv part='connexionsAnnuelles' options=$ppo->options}</h3>

<table class="viewItems visualize">
    <caption>Statistiques annuelles (nombre de connexions sur l'année entière)</caption>
    <thead>
        <tr>
            <td></td>
            {foreach from=$ppo->requestClass->getConnexionsAnnuelles($ppo->profile) key=year item=numberOfConnection}
                <th scope="col">{$year}</th>
            {/foreach}
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">Nb de connexions</th>
            {foreach from=$ppo->requestClass->getConnexionsAnnuelles($ppo->profile) key=year item=numberOfConnection}
                <td>{$numberOfConnection}</td>
            {/foreach}
        </tr>
    </tbody>
</table>


<h3 class="mt2 mb1">Statistiques mensuelles {copixzone process=statistiques|exportcsv part='connexionsMensuelles' options=$ppo->options}</h3>
{assign var=connexionsMensuelles value=$ppo->requestClass->getConnexionsMensuelles($ppo->profile)}
{if count($connexionsMensuelles.statistiques) <= 12}
    <table class="viewItems visualize">
        <caption>Statistiques mensuelles (nombre de connexions sur le mois entier)</caption>
        <thead>
            <tr>
                <td></td>
                {foreach from=$connexionsMensuelles.statistiques key=month item=numberOfConnection}
                    <th scope="col">{$month}</th>
                {/foreach}
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Nb de connexions</th>
                {foreach from=$connexionsMensuelles.statistiques key=month item=numberOfConnection}
                    <td>{$numberOfConnection}</td>
                {/foreach}
            </tr>
        </tbody>
    </table>
{else}
    <table class="viewItems">
        <caption>Statistiques mensuelles (nombre de connexions sur le mois entier)</caption>
        <thead>
            <tr>
                <td></td>
                <th scope="row">Nb de connexions</th>
            </tr>
        </thead>
        <tbody>{foreach from=$connexionsMensuelles.statistiques key=month item=numberOfConnection}
            <tr>
                <th scope="col">{$month}</th>
                <td>{$numberOfConnection}</td>
            </tr>{/foreach}
        </tbody>
    </table>
    <p class="info">La période est trop étendue pour présenter les données dans un graphique.</p>
{/if}

{if $connexionsMensuelles.afficherMoyennes}
    <h4>Moyennes</h4>
    <table class="viewItems visualize">
        <caption>Moyennes</caption>
        <thead>
            <tr>
                <td></td>
                {foreach from=$connexionsMensuelles.moyennes key=month item=numberOfConnection}
                    <th scope="col">{$month}</th>
                {/foreach}
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Nb de connexions</th>
                {foreach from=$connexionsMensuelles.moyennes key=month item=numberOfConnection}
                    <td {if $numberOfConnection.aide}title="{$numberOfConnection.aide}"{/if}>
                        {$numberOfConnection.valeur}
                    </td>
                {/foreach}
            </tr>
        </tbody>
    </table>
{/if}

<h3 class="mt2 mb1">Statistiques hebdomadaires {copixzone process=statistiques|exportcsv part='connexionsHebdomadaires' options=$ppo->options}</h3>

{assign var=connexionsHebdomadaires value=$ppo->requestClass->getConnexionsHebdomadaires($ppo->profile)}

{if count($connexionsHebdomadaires.statistiques) <= 10 }
    <table class="viewItems visualize">
        <caption>Statistiques hebdomadaires (nombre de connexions sur la semaine entière)</caption>
        <thead>
            <tr>
                <td></td>
                {foreach from=$connexionsHebdomadaires.statistiques key=week item=numberOfConnection}
                    <th scope="col">{$week}</th>
                {/foreach}
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Nb de connexions</th>
                {foreach from=$connexionsHebdomadaires.statistiques key=week item=numberOfConnection}
                    <td>{$numberOfConnection}</td>
                {/foreach}
            </tr>
        </tbody>
    </table>
{else}
    <table class="viewItems">
        <caption>Statistiques hebdomadaires (nombre de connexions sur la semaine entière)</caption>
        <thead>
            <tr>
                <td></td>
                <th scope="row">Nb de connexions</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$connexionsHebdomadaires.statistiques key=week item=numberOfConnection}
                <tr>
                    <th scope="col">{$week}</th>
                    <td>{$numberOfConnection}</td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <p class="info">La période est trop étendue pour présenter les données dans un graphique.</p>
{/if}

<h3 class="mt2 mb1">Statistiques journalières {copixzone process=statistiques|exportcsv part='connexionsJournalieres' options=$ppo->options}</h3>

{assign var=connexionsJournalieres value=$ppo->requestClass->getConnexionsJournalieres($ppo->profile)}
<table class="viewItems">
    <caption>Statistiques journalières</caption>
    <thead>
        <tr>
            <td></td>
            <th scope="col">Nb de connexions</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$connexionsJournalieres.statistiques key=day item=numberOfConnection}
            <tr>
                <th scope="row">{$day}</th>
                <td>{$numberOfConnection}</td>
            </tr>
        {/foreach}
    </tbody>
</table>

{if $connexionsJournalieres.afficherMoyennes}
    <h4>Moyennes</h4>
    <table class="viewItems visualize">
        <caption>Moyennes</caption>
        <thead>
            <tr>
                <td></td>
                {foreach from=$connexionsJournalieres.moyennes key=day item=numberOfConnection}
                    <th scope="col">{$day}</th>
                {/foreach}
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Nb de connexions</th>
                {foreach from=$connexionsJournalieres.moyennes key=day item=numberOfConnection}
                    <td {if $numberOfConnection.aide}title="{$numberOfConnection.aide}"{/if}>
                        {$numberOfConnection.valeur}
                    </td>
                {/foreach}
            </tr>
        </tbody>
    </table>
{/if}

<h3 class="mt2 mb1">Statistiques horaires {copixzone process=statistiques|exportcsv part='connexionsHoraires' options=$ppo->options}</h3>

{assign var=connexionsHoraires value=$ppo->requestClass->getConnexionsHoraires($ppo->profile)}
<table class="viewItems visualize">
    <caption>Statistiques horaires</caption>
    <thead>
        <tr>
            <td></td>
            {foreach from=$connexionsHoraires.moyennes key=hour item=numberOfConnection}
                <th scope="col">{$hour}</th>
            {/foreach}
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">Nb de connexions</th>
            {foreach from=$connexionsHoraires.moyennes key=hour item=numberOfConnection}
                <td {if $numberOfConnection.aide}title="{$numberOfConnection.aide}"{/if}>
                    {$numberOfConnection.valeur}
                </td>
            {/foreach}
        </tr>
    </tbody>
</table>