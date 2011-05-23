<h3>{i18n key="cahierdetextes.message.todoWork"} {if $ppo->estAdmin}<a class="button button-add" href="{copixurl dest="cahierdetextes||editerTravail" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee vue=$ppo->vue a_faire=1}">{i18n key="cahierdetextes.message.addTodoWork"}</a>{/if}</h3>

<div class="todo-works-list">
  {if $ppo->travaux neq null}
    {foreach from=$ppo->travaux item=travail}
      <div class="work">
        <h4>{$travail->nom} {if $ppo->estAdmin}
          <span class="actions">
          	<a href="{copixurl dest="cahierdetextes||voirConcernesParTravail" cahierId=$ppo->cahierId travailId=$travail->id}" title="{i18n key="cahierdetextes.message.seeConcerned"}"><img src="{copixurl}themes/default/images/menu_list_active.png" alt="{i18n key="cahierdetextes.message.seeConcerned"}" /></a>
            <a href="{copixurl dest="cahierdetextes||editerTravail" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee travailId=$travail->id}" title="{i18n key="cahierdetextes.message.modify"}"><img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" /></a> 
            <a href="{copixurl dest="cahierdetextes||supprimerTravail" cahierId=$ppo->cahierId travailId=$travail->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteWorkConfirm"}')" title="{i18n key="cahierdetextes.message.delete"}"><img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" /></a>
          </span>
        {/if}</h4>
        {$travail->description}
      </div>
    {/foreach}
  {else}
    <p class="no-work">{i18n key="cahierdetextes.message.noWork"}</p>
  {/if}
</div>