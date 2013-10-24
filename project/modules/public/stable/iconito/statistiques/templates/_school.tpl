{foreach from=$ppo->schools key=key item=school}
  <option value="{$school->id}">&nbsp;&nbsp;&nbsp;&nbsp;{$school->nom|escape}</option>
  {copixzone process=statistiques|classroom school_id=$school->numero}
{/foreach}