

<h2>Comptes</h2>
<p>Au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span>, il y a <strong>{$ppo->requestClass->getNombreComptes()}</strong> compte(s).</p>
<div>
    <h3>Nombre de compte par profil</h3>
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

<h2>Connexions</h2>

<div id="accountTypeSwitcherPlaceholder"></div>

<div id="accountTypeDataContainer">
    Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :

    <div class="linked-with-account-profile show-for-ALL">
        {copixzone process=statistiques|connectionsByProfile requestClass=$ppo->requestClass profile=''}
    </div>

    {foreach from=$ppo->requestClass->getProfils() key=profile item=libelle}
        <div class="linked-with-account-profile show-for-{$profile}">
            {copixzone process=statistiques|connectionsByProfile requestClass=$ppo->requestClass profile=$profile}
        </div>
    {/foreach}
</div>

{literal}
<script type="text/javascript">
    $(function(){
        prepareAccountTypeSwitcher(
            $('#accountTypeSwitcherPlaceholder'),
            $('#accountTypeDataContainer'),
            {/literal}{$ppo->requestClass->getJsonProfils()}{literal}
        );
    });
</script>
{/literal}