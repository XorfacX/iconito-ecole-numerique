
{if $titre}<div class="titre">{$titre}</div>{/if}

{assign var=i value=0}
{assign var=lastType value=''}
{assign var=lastVille value=''}
{if $dispFilter}
<div class="content-panel">
{literal}
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $("#select-school").change(function(){
                $(this).parent("form").submit();
            });
        });
    </script>
{/literal}
    {if $displayVille}
    <form action="{copixurl dest="public|default|ecoles"}" method="get" class="floatleft">
        <label for="select-school" class="hidden">Ville</label> 
        <select name="ville" id="select-school">
            <option value="-99">{i18n key="welcome.ecoles.all" noEscape=1}</option>
        {foreach from=$villes item=ville}
            <option {if $defaultVille == $ville.id_vi}selected="selected"{/if} value={$ville.id_vi}>{$ville.nom|utf8_encode}</option>
        {/foreach}
        </select>
    </form>
    {/if}

    <form action="{copixurl dest="public|default|ecoles"}" method="get" class="floatright">
        <label for="searchSchools" class="hidden">&Eacute;cole</label> <input type="text" name="search" class="default-value" value="{$searchInputValue}" id="searchSchools" />
        <input type="submit" class="button button-confirm" value="ok" />
    </form>
    <div class="clearBoth"></div>
</div>
{/if}
<div class="content-panel">

<!-- Tri par ville / type -->
{if $list && $groupBy != 'villeType'}

    {assign var=currentCity value=''}
    {assign var=currentType value=''}
    
    {foreach from=$list item=ecole}

        {if $ecole.id>0}

        {if $colonnes > 1}
  			{if $i%$parCols eq 0}
  				{if $i > 0}</ul></div>{/if}		
                <div style="float:left;width:{$widthColonne};">
                {assign var=currentCity value=''}
                {assign var=currentType value=''}
  			{/if}
  		{/if}

        {if $groupBy eq 'ville' && $currentCity neq $ecole.ville}
            {if $currentCity neq ''}</ul>{/if}
            {if $groupBy eq 'ville' && $ecole.ville neq $lastVille && $dispHeader}
                <h3>{$ecole.ville_nom|escape}</h3>
            {/if}
            <ul>
            {assign var=currentCity value=$ecole.ville}
        {/if}
        
        {if $groupBy eq 'type' && $currentType neq $ecole.type}
            {if $currentType neq ''}</ul>{/if}
            {if $groupBy eq 'type' && $ecole.type neq $lastType && $dispHeader}
                <h3>{if $ecole.type eq 'Elémentaire' || $ecole.type eq 'Elémentaire'}{i18n key="welcome|welcome.ecoles.type.elem"}
                {elseif $ecole.type eq 'Primaire'}{i18n key="welcome|welcome.ecoles.type.prim"}
                {elseif $ecole.type eq 'Maternelle'}{i18n key="welcome|welcome.ecoles.type.mat"}
                {elseif $ecole.type eq 'Centre de Loisirs'}{i18n key="welcome|welcome.ecoles.type.lois"}
                {elseif $ecole.type}{$ecole.type|escape}
                {/if}</h3>
            {/if}
            <ul>
            {assign var=currentType value=$ecole.type}
        {/if}
        
        <li>
            {if $ajaxpopup}
                <a class="fancyframe fancyframe-wfixed" href="{copixurl dest="fichesecoles||fiche" id=$ecole.id popup=1}">{$ecole.nom|escape}</a>
            {else}
                <a href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}">{$ecole.nom|escape}</a>
            {/if}
            {if $dispType && $ecole.type} <em>({$ecole.type|escape})</em> {/if}
        </li>

  		{assign var=i value=$i+1}

        {/if}
    {/foreach}
  
    {if $i > 0}
        </ul>
        {if $colonnes > 1}
            </div>
            <div class="clear"></div>
        {/if}
    {/if}

	
<!-- Tri par type de ville -->	
{elseif $list && $groupBy == 'villeType'}
   
    {foreach from=$list item=type key=ville}
        <h3>{$ville}</h3>
        {foreach from=$type item=ecoleCollection key=nomType}
            <h4>{$nomType}</h4>
            <ul class="listEcoles">
                {foreach from=$ecoleCollection item=ecole}
                <li>
                    {if $ajaxpopup}
                        <a class="fancybox" href="{copixurl dest="fichesecoles||fiche" id=$ecole.id popup=1}">{$ecole.nom|escape}</a>
                    {else}
                        <a href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}">{$ecole.nom|escape}</a>
                    {/if}
                </li>
        {/foreach}
            </ul>
        <div class="clear"></div>
        {/foreach}
    <hr />
    {/foreach}
    
{else}
    <p>{i18n key=welcome.ecoles.aucune}</p>
{/if}
</div>