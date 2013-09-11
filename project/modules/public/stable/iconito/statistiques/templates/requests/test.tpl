<h2>{$ppo->label}</h2>

{assign var=fake value=$ppo->requestClass->getFakeCountAndAverage()}

<div class="statistics">
    <table>
        <tbody>
        <tr>
            <td>Nombre de blog ouvert :</td>
            <td><span class="results">{$ppo->requestClass->getFakeResult()}</span></td>
        </tr>
        <tr>
            <td>Nombre d'article rédigés :</td>
            <td><span class="results">{$fake.total}</span></td>
        </tr>
        <tr>
            <td>Nombre d'article par blog : </td>
            <td><span class="results">{$fake.average}</span></td>
        </tr>
        </tbody>
    </table>
</div>
