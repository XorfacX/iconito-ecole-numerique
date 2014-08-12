{if $ppo->saml_error eq "no-iconito-user"}
<div class="error">Erreur : Votre identifiant est correct, mais n'est pas associ&eacute; &agrave; un compte Iconito. Veuillez prendre contact avec votre administrateur.</div>
<div>&rarr; <a href="{copixurl dest="auth|saml|logout"}">D&eacute;connectez-vous</a></div>
{/if}
{if $ppo->saml_error eq "bad-conf-uidattribute"}
<div class="error">Erreur : La configuration de votre Iconito pose probl&egrave;me (configuration de "conf_Saml_uidAttribute"). Veuillez prendre contact avec votre administrateur.</div>
<div>&rarr; <a href="{copixurl dest="auth|saml|logout"}">D&eacute;connectez-vous</a></div>
{/if}
