<select name="context">
    {foreach from=$ppo->citiesGroups key=key item=citiesGroup}
        <option value="{$citiesGroup->id}">{$citiesGroup->nom_groupe|escape}</option>
        {copixzone process=statistiques|city cities_group_id=$citiesGroup->id_grv}
    {/foreach}
</select>