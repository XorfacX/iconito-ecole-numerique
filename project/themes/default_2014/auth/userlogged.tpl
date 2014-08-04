{if $ppo->user->isConnected ()}
	{assign var=login value=$ppo->user->getLogin()}
	{assign var=nom value=$ppo->user->getExtra('nom')}
	{assign var=prenom value=$ppo->user->getExtra('prenom')}
	
	{if $ppo->animateur eq 1}
		<div class="prise_controle">
			<div class="message">Vous consultez le compte de {$prenom} {$nom}</div>
			<a class="button button-cancel" href="{copixurl dest="assistance||switch"}">Revenir à mon compte</a>
		</div> 
	{else}
        <p class="user h1-like txtright">{$prenom} {$nom}<br /><span class="smaller">{customi18n key="kernel|kernel.usertypes.%%"|cat:$ppo->usertype|cat:"%%" catalog=$ppo->vocabularyCatalogId}</span></p>
	{/if}

{else}
    {if $ppo->conf_Cas_actif}
        <a href="{copixurl dest="auth|cas|login"}" class="button button-confirm cas-login">Connectez-vous !</a>
    {elseif $ppo->conf_Saml_actif}
        <a href="{copixurl dest="auth|saml|login"}" class="button button-confirm cas-login">Connectez-vous !</a>
    {else}
        {if (false || $canNewAccount) } {*// TODO: lire conf pour savoir si on autorise la demande de compte, sur cette ligne et 4 lignes plus haut *}
            <div class="loginNew">
                <a class="usr-newaccount" alt="{i18n key=auth|auth.newAccount}" title="{i18n key=auth|auth.newAccount}" href="{copixurl dest="public|default|getreq"}"></a>
            </div>
        {/if}
        <!-- login form -->
			<form action="{copixurl dest="auth|log|in"}" class="login txtleft">
            	<input type="hidden" name="auth_url_return" id="auth_url_return" value="{$url}" />
				<fieldset class="">
					<legend class="visually-hidden">{i18n key=auth|auth.text.logon}<br />{if (false || $canNewAccount) }{i18n key=auth|auth.text.newAccount}<br/>{/if}</legend>
					<div class="field">
                    <label for="a1" class="">{i18n key=auth|auth.login}</label>
                    <input type="text" name="login" value="" placeholder="{i18n key=auth|auth.login}" id="a1" class="">
					</div>
                   <div class="field">
                   <label for="a2" class="">{i18n key=auth|auth.password}</label>
                    <input type="password" name="password" value="" placeholder="{i18n key=auth|auth.password}" id="a2" class="">
                    </div>
                    <div class="submit">
					<input type="submit" value="Ok">
					</div>
				</fieldset> 
			</form>
            
    {/if}
{/if}