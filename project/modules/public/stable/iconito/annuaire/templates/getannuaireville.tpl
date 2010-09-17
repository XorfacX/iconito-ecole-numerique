<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_annuaire.css"}" />
<SCRIPT LANGUAGE="Javascript1.2" SRC="{copixurl}js/iconito/module_annuaire.js"></SCRIPT>

<div id="ecole_infos_bloc">

<div class="lucien"></div>

<div id="ecole_infos" class="block">

<div align="right">

{if !$kernel_ville_as_array || $kernel_ville_as_array|@count > 1}
<form name="formGo" id="formGo" action="{copixurl dest="annuaire||getAnnuaireVille"}" method="get">
{copixzone process="annuaire|combovilles" grville=$grville value=$ville.id fieldName=ville attribs='class="annu_combo_popup" onchange="this.form.submit();"'}
<input type="submit" value="{i18n key="annuaire.btn.go"}" class="button" />
</form>
{/if}



</div>

{if $ville.blog}<div style="text-align:right;"><a title="{$ville.blog}" href="{$ville.blog}">{i18n key="annuaire.blogVille"}</a><a href="{$ville.blog}" target="_blank"><img alt="{i18n key="public|public.openNewWindow"}" title="{i18n key="public|public.openNewWindow"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_window.png"}" hspace="4" /></a></div>{/if}

{ if $agents }
<h2>{i18n key="annuaire.agents"}</h2>
<div>
{foreach from=$agents item=agent}

{user label=$agent.prenom|cat:" "|cat:$agent.nom userType=$agent.type userId=$agent.id login=$agent.login dispMail=$canWrite_USER_VIL}

<br/>
{/foreach}
</div>
{/if}

</div>

<div id="div_user"></div>

</div>





{if $ecoles}

{assign var=current_type value=""}

<div id="eleves">

{foreach from=$ecoles item=ecole}
<div style="padding-bottom:2px;">

{if $ecole.type <> $current_type}
<!--<h3>{$ecole.type}</h3>-->
{assign var=current_type value=$ecole.type}
{/if}

<div class="ecole_web">{if $ecole.blog}<a title="{$ecole.blog}" href="{$ecole.blog}">{i18n key="annuaire.blog"}</a><a href="{$ecole.blog}" target="_blank"><img alt="{i18n key="public|public.openNewWindow"}" title="{i18n key="public|public.openNewWindow"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_window.png"}" hspace="4" /></a>{/if}

<a title="{i18n key="annuaire.fiche"}" href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}">{i18n key="annuaire.fiche"}</a><a class="fancybox" href="{copixurl dest="fichesecoles||fiche" id=$ecole.id popup=1}"><img alt="{i18n key="public|public.openPopup"}" title="{i18n key="public|public.openPopup"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_popup.png"}" hspace="1" /></a>

{if $ecole.web}<a target="_blank" title="{$ecole.web}" href="{$ecole.web}">{i18n key="annuaire.siteWeb"}</a>{/if}



</div>

<a href="{copixurl dest="|getAnnuaireEcole" ecole=$ecole.id}">{$ecole.nom}{if $ecole.type} ({$ecole.type}){/if}</a>

{if $ecole.directeur}
{assign var=sep value=""}
({foreach from=$ecole.directeur item=directeur}{$sep}{$directeur.prenom} {$directeur.nom|upper}{assign var=sep value=", "}{/foreach})
{/if}

</div>
{/foreach}

</div>
{else}
{i18n key="annuaire.noEcoles"}
{/if}



<br clear="all" />