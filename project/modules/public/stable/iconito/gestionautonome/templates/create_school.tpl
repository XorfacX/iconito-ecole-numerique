<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Ajout d'une école</h2>

<h3>Ecole</h3>

{if not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="school_creation" id="school_creation" action="{copixurl dest="|validateSchoolCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->parentId}" />
    <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->parentType}">
    
    <label for="type" class="form_libelle"> Type :</label>
    <select class="form" name="type" id="type">
  	  {html_options values=$ppo->types output=$ppo->types selected=$ppo->school->type}
  	</select>
    
    <div class="field">
      <label for="name" class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->school->nom}" />
    </div>
  </fieldset>
  
  <ul class="actions">
    <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
  	<li><input class="button" type="submit" name="save" id="save" value="Enregistrer" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
  });
  
  jQuery('#cancel').click(function() {
    
    document.location.href={/literal}'{copixurl dest=gestionautonome||showTree nodeId=$ppo->parentId nodeType=$ppo->parentType notxml=true}'{literal};
  });
//]]> 
</script>
{/literal}