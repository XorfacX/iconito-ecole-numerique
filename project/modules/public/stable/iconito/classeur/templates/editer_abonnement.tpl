{copixzone process=classeur|affichageMenu classeurId=$ppo->classeurId dossierId=$ppo->dossierId current="editerAbonnement"}

<h2>
    {i18n key="classeur.message.subscribe"}
</h2>

{if $ppo->success}
    <p class="mesgSuccess">{i18n key="classeur.message.success"}</p>
{elseif not $ppo->erreurs eq null}
    <ul class="mesgErrors">
        {foreach from=$ppo->erreurs item=erreur}
            <li>{$erreur}</li>
        {/foreach}
    </ul>
{/if}

<form id="subscribe_form" action="{copixurl dest="classeur||editerAbonnement"}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeurId}" />

    <div class="row">
        <label for="subscribe">{i18n key="classeur.message.subscribe"}</label>
        <p class="field"><input type="checkbox" id="subscribe" name="subscribe" {if $ppo->abonnementStatus}checked{/if} /></p>
        <p class="field info" id="subscribe_note">{i18n key="classeur.message.subscribeDesc"}</p>
    </div>

    <div class="submit">
        <a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="button button-cancel" id="cancel">{i18n key="classeur.message.cancel"}</a>
        <input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.save"}" />
    </div>
</form>