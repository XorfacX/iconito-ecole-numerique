{foreach from=$ppo->cities key=key item=city}
    <option value="{$city->id}">&nbsp;&nbsp;{$city->nom|escape}</option>
    {copixzone process=statistiques|school city_id=$city->id_vi}
{/foreach}

