
{foreach from=$ppo->mapping->getCategories() key=stat item=infos}
    <a href="{copixurl dest="statistiques|default|index" stat=$stat}" class="button">{$infos.label}</a>
{/foreach}

<h2>{i18n key="statistiques.label.title"}</h2>

<form action="{copixurl dest="statistiques|default|index" stat=$ppo->stat}" method="post" class="edit">
    <p class="center">{i18n key='kernel|kernel.fields.oblig' noEscape=1}</p>
    
    <div class="field">
        <label for="context" class="form_libelle">Périmètre  <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /></label>
        {assign var=current value=$ppo->filter->target }
        <select name="target">
            <option value="">Sélectionnez un périmètre</option>
            {foreach from=$ppo->contexts item=choice}
                {if count($choice->getChoices()) }
                    <optgroup label="{$choice->getLabel()}">
                        {foreach from=$choice->getChoices() item=subChoice}
                            <option value="{$subChoice->computeKey()}" {if $subChoice->computeKey() == $current}selected="selected"{/if}>{$subChoice->getLabel()}</option>
                        {/foreach}
                    </optgroup>
                {else}
                    <option value="{$choice->computeKey()}" {if $choice->computeKey() == $current}selected="selected"{/if}>{$choice->getLabel()}</option>
                {/if}
            {/foreach}
        </select>
    </div>

    <div class="field">
        <label for="dateBegin" class="form_libelle"> Du <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /></label>
        <input type="text" name="publishedFrom" id="date_begin" required value="{if $ppo->filter->publishedFrom}{$ppo->filter->publishedFrom->format('d/m/Y')|escape}{/if}"/>
    </div>

    <div class="field">
        <label for="dateEnd" class="form_libelle"> Au <img src="{copixresource path="img/red-star.png"}" alt="{i18n key='kernel|kernel.required'}" /></label>
        <input type="text" name="publishedTo" id="date_end" required value="{if $ppo->filter->publishedTo}{$ppo->filter->publishedTo->format('d/m/Y')|escape}{/if}"/>
    </div>

    <div class="submit">
        <input type="submit" class="button button-confirm" value="{i18n key=copix:common.buttons.ok}" />
    </div>
</form>

<h2>{i18n key="statistiques.label.results"}</h2>
<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
    {foreach from=$ppo->mapping->getCategories() key=stat item=infos}
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
      jQuery('#date_begin, #date_end').datepicker({showOn: 'both', buttonImage: '../../../js/jquery/images/datepicker/calendar.gif', buttonImageOnly: true, numberOfMonths: 3, showButtonPanel: true, appendText: '(JJ/MM/AAAA)', constrainInput: true, maxDate:0});
  });
</script>
{/literal}
