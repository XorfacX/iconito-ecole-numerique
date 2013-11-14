<h2 class="mt2 mb1">{$ppo->label}</h2>
<p>Au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span>, il y a <strong>{$ppo->requestClass->getNombreComptes()}</strong> compte(s).</p>
<div>
    <h3 class="mt2 mb1">Nombre de compte par profil</h3>
    {assign var=comptesParProfil value=$ppo->requestClass->getNombreComptesParProfil()}
    <table class="viewItems visualize">
        <caption>Nombre de compte par profil</caption>
        <thead>
            <tr>
                <td></td>
                {foreach from=$comptesParProfil key=profile item=numberOfAccount}
                    <th scope="col">{$profile}</th>
                {/foreach}
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Nb de comptes</th>
                {foreach from=$comptesParProfil key=profile item=numberOfAccount}
                    <td>{$numberOfAccount}</td>
                {/foreach}
            </tr>
        </tbody>
    </table>
</div>


<h2 class="mt2 mb1">{i18n key="statistiques.label.connections"}</h2>
<div>
    Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :

    Il y a eu <strong>{$ppo->requestClass->getNombreConnexions()}</strong> connexion(s).

    <h3 class="mt2 mb1">Statistiques annuelles</h3>

    <table class="viewItems visualize">
        <caption>Statistiques annuelles</caption>
        <thead>
            <tr>
                <td></td>
                {foreach from=$ppo->requestClass->getConnexionsAnnuelles() key=year item=numberOfConnection}
                    <th scope="col">{$year}</th>
                {/foreach}
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">Nb de connexions</th>
                {foreach from=$ppo->requestClass->getConnexionsAnnuelles() key=year item=numberOfConnection}
                    <td>{$numberOfConnection}</td>
                {/foreach}
            </tr>
        </tbody>
    </table>


    <h3 class="mt2 mb1">Statistiques mensuelles</h3>
    {assign var=connexionsMensuelles value=$ppo->requestClass->getConnexionsMensuelles()}
    {if count($connexionsMensuelles.statistiques) <= 12}
        <table class="viewItems visualize">
            <caption>Statistiques mensuelles</caption>
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
            <caption>Statistiques mensuelles</caption>
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
                        <td>{$numberOfConnection}</td>
                    {/foreach}
                </tr>
            </tbody>
        </table>
    {/if}

    <h3 class="mt2 mb1">Statistiques hebdomadaires</h3>

    {assign var=connexionsHebdomadaires value=$ppo->requestClass->getConnexionsHebdomadaires()}
  
    {if count($connexionsHebdomadaires.statistiques) <= 10 }
        <table class="viewItems visualize">
            <caption>Statistiques hebdomadaires</caption>
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
            <caption>Statistiques hebdomadaires</caption>
            <thead>
                <tr>
                    <td></td>
                    <th scope="row">Nb de connexions</th>
                        
                    
                </tr>
            </thead>
            <tbody>{foreach from=$connexionsHebdomadaires.statistiques key=week item=numberOfConnection}
                <tr>
                    
                    <th scope="col">{$week}</th>
                        <td>{$numberOfConnection}</td>
                    
                </tr>{/foreach}
            </tbody>
        </table>
        <p class="info">La période est trop étendue pour présenter les données dans un graphique.</p>
    {/if}
    
    <h3 class="mt2 mb1">Statistiques journalières</h3>

    {assign var=connexionsJournalieres value=$ppo->requestClass->getConnexionsJournalieres()}
    <table class="viewItems">
        <caption>Statistiques journalières</caption>
        <thead>
            <tr>
                <td></td>
                <th scope="col">Nb de connexions</th>
            </tr>
        </thead>
        <tbody>{foreach from=$connexionsJournalieres.statistiques key=day item=numberOfConnection}
            <tr>
                
                    <th scope="row">{$day}</th>
                    <td>{$numberOfConnection}</td>
                
            </tr>{/foreach}
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
                        <td>{$numberOfConnection}</td>
                    {/foreach}
                </tr>
            </tbody>
        </table>
    {/if}

    <h3 class="mt2 mb1">Statistiques horaires</h3>

    {assign var=connexionsHoraires value=$ppo->requestClass->getConnexionsHoraires()}
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
                    <td>{$numberOfConnection}</td>
                {/foreach}
            </tr>
        </tbody>
    </table>
</div>