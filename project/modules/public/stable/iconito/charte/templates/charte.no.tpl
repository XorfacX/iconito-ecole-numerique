<div id="dialog">
	<h2>{i18n key="charte.important" noEscape=1}</h2>
	<div class="content-info">
		{i18n key="charte.no.read" noEscape=1}
	</div>
	<div class="content-info">
		{i18n key="charte.no.noaccess" noEscape=1}
	</div>
	<div class="content-panel center">
	<a class="button button-cancel" href="{if $ppo->conf_Saml_actif}{copixurl dest='auth|saml|logout'}{else}{copixurl dest='auth|log|out'}{/if}">{i18n key="charte.no.logoff" noEscape=1}</a>
	</div>
</div>