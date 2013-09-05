{foreach from=$ppo->classrooms key=key item=classroom}
    <option value="{$classroom->id}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$classroom->nom|escape}</option>
{/foreach}
