{foreach from=$ppo->mapping->getFiltersCategories() key=stat item=infos}
    <a href="{copixurl dest="statistiques|default|index" stat=$stat}" class="button">{$infos.label}</a>
{/foreach}

<h2>Sélectionnez un périmètre et une période</h2>

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

{if $ppo->stat && $ppo->filter->publishedFrom && $ppo->filter->publishedTo}
    {copixzone process=statistiques|apiRequest stat=$ppo->stat filter=$ppo->filter}
{/if}

<script type="text/javascript">
    setDatePicker('#date_begin, #date_end');
</script>