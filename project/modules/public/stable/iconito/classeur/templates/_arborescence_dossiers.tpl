{foreach from=$ppo->dossiers item=dossier}
  {assign var=dossierId value=$dossier->id}
  <li class="folder {if !isset($ppo->dossiersOuverts[$dossierId])}collapsed{else}open{/if}">
    <p class="{if $dossier->id eq $ppo->dossierCourant}current{/if}">
    {if $dossier->hasSousDossiers(!$dossier->casier || ($dossier->casier && $ppo->estAdmin))}
      <a href="#" class="expand-folder {$dossier->id}">
        {if !isset($ppo->dossiersOuverts[$dossierId])}
          <img src="{copixurl}themes/default/images/sort_right_off.png" alt="+" />
        {else}
          <img src="{copixurl}themes/default/images/sort_down_off.png" alt="-" />
        {/if}
      </a>
    {else}
      <img src="{copixurl}themes/default/images/sort_right_inactive.png" alt=">" />
    {/if}
    {if $ppo->field neq null && $ppo->format neq null}
    <a href="{copixurl dest="classeur||getClasseurPopup" classeurId=$ppo->classeurId dossierId=$dossier->id field=$ppo->field format=$ppo->format withPersonal=$ppo->withPersonal moduleType=$ppo->moduleType moduleId=$ppo->moduleId}">{$dossier->nom}</a>
    {else}
    <a href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$dossier->id}"{if $dossier->casier} class="locked"{/if}>{$dossier->nom}</a>
    {/if}
    </p>
    <ul class="child {if !isset($ppo->dossiersOuverts[$dossierId])}closed{/if}">
      {copixzone process=classeur|arborescenceDossiers classeurId=$ppo->classeurId dossierId=$dossier->id dossierCourant=$ppo->dossierCourant field=$ppo->field format=$ppo->format withPersonal=$ppo->withPersonal moduleType=$ppo->moduleType moduleId=$ppo->moduleId}
    </ul>
  </li>
{/foreach}