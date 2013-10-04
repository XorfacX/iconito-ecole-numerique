<h2>{i18n key="statistiques.label.title"}</h2>

<form action="{copixurl dest="statistiques|default|index" stat=$ppo->stat}" method="post" class="edit">
    <div class="field">
        <label for="context" class="form_libelle"> Périmètre </label>
        {copixzone process=statistiques|citiesGroup}
        {* À décommenter après le merge avec la branche feature-activity-stream-send, puis supprimer les zones autres que apirequest, et les vues associées *}
        {*<select name="context">
            {foreach from=$ppo->contexts key=key item=context}
                {if get_class($context.element) == 'CompiledDAORecordkernel_bu_groupe_villes'}
                    <option value="{$key}">{$context.element->nom_groupe}</option>
                {/if}
                {if get_class($context.element) == 'CompiledDAORecordkernel_bu_ville'}
                    <option value="{$key}">&nbsp;&nbsp;{$context.element->nom}</option>
                {/if}
                {if get_class($context.element) == 'CompiledDAORecordkernel_bu_ecole'}
                    <option value="{$key}">&nbsp;&nbsp;&nbsp;&nbsp;{$context.element->nom}</option>
                {/if}
                {if get_class($context.element) == 'CompiledDAORecordkernel_bu_ecole_classe'}
                    <option value="{$key}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$context.element->nom}</option>
                {/if}
            {/foreach}
        </select>*}
    </div>

    <div class="field">
        <label for="dateBegin" class="form_libelle"> Du </label>
        <input type="text" name="publishedFrom" id="date_begin" value="{if $ppo->filter->publishedFrom}{$ppo->filter->publishedFrom->format('d/m/Y')|escape}{/if}"/>
    </div>

    <div class="field">
        <label for="dateEnd" class="form_libelle"> Au </label>
        <input type="text" name="publishedTo" id="date_end" value="{if $ppo->filter->publishedTo}{$ppo->filter->publishedTo->format('d/m/Y')|escape}{/if}"/>
    </div>

    <div class="submit">
        <input type="submit" class="button button-confirm" value="{i18n key=copix:common.buttons.ok}" />
    </div>
</form>

<h2>{i18n key="statistiques.label.results"}</h2>
<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
    {foreach from=$ppo->mapping->getFiltersCategories() key=stat item=infos}
        <li class="ui-state-default ui-corner-top ui-tabs-active {if $stat eq $ppo->stat}ui-state-active{/if}"><a href="{copixurl dest="statistiques|default|index" stat=$stat}">{$infos.label}</a></li>
    {/foreach}
    </ul>
	<div class="container">
    {if $ppo->stat && $ppo->filter->publishedFrom && $ppo->filter->publishedTo}
        {copixzone process=statistiques|apiRequest stat=$ppo->stat filter=$ppo->filter}
    {/if}
    </div>
</div>

{literal}
<script type="text/javascript">
  jQuery(document).ready(function(){
      setDatePicker('#date_begin, #date_end');
  });
</script>
{/literal}