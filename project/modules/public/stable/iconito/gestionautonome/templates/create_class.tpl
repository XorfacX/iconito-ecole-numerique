<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Ajout d'une classe</h2>

<h3>Classe</h3>

{if not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="class_creation" id="class_creation" action="{copixurl dest="|validateClassCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->parentId}" />
    <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->parentType}" />
    
    <div class="field">
      <label for="name" class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->class->nom}" />
    </div>
    
    <div class="field">
      <label for="name" class="form_libelle"> Niveaux :</label>
      {html_checkboxes name='levels' values=$ppo->levelIds output=$ppo->levelNames selected=$ppo->levels}
    </div>
    
    <label for="type" class="form_libelle"> Type :</label>
    <select class="form" name="type" id="type">
      {html_options values=$ppo->typeIds output=$ppo->typeNames selected=$ppo->type} 
  	</select>
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