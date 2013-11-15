<h2>{$ppo->label}</h2>
<p>
    Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :
    <ul>
        {assign var=minimails value=$ppo->requestClass->getNombreMinimailsEtRatio()}
        <li>{$minimails.minimails} minimail(s) ont été envoyé(s)   <span class="average">(soit {$minimails.ratio} minimail(s) par compte ouvert)</span></li>
    </ul>
</p>

<h3>Détail par profil</h3>

<table class="viewItems visualize">
    <tbody>
        {foreach from=$ppo->requestClass->getNombreMinimailParProfil() key=profile item=number}
            <tr>
                <th>{$profile}</th>
                <td>{$number}</td>
            </tr>
        {/foreach}
    </tbody>
</table>