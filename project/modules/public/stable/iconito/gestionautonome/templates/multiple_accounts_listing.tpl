<h2>Ajout d'une liste d'élèves</h2>

<div id="accounts-info">
  {copixzone process=gestionautonome|MultipleAccountsInfo}
</div>

<div style="margin-top: 20px; padding: 10px 0.7em 0 0.7em;" class="notice-light ui-state-highlight ui-corner-all"> 
  <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
	<strong>Elèves ajoutés !</strong>
</div>

<h4>Liste des élèves ajoutés</h4>

{foreach from=$ppo->students key=k item=student}

  {$student.firstname} {$student.lastname}
  {foreach from=$student.person key=j item=person}
    
    ({$person.firstname} {$person.lastname})
  {/foreach}
  ,
{/foreach}

<ul class="actions">
  <li><input class="button" type="button" value="Retour" id="back" /></li>
</ul>        

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();

  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
  });
  
  jQuery('#back').click(function() {

    document.location.href={/literal}'{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType notxml=true}'{literal};
  });
//]]> 
</script>
{/literal}