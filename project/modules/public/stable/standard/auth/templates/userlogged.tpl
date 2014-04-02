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
        <div class="userprofile">
            <span class="username">{$prenom} {$nom}</span><br/>
            <span class="userrole">{customi18n key="kernel|kernel.usertypes.%%"|cat:$ppo->usertype|cat:"%%" catalog=$ppo->vocabularyCatalogId}</span>
        </div>
	{/if}

{else}
    <div class="userlogon">
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
        <form action="{copixurl dest="auth|log|in"}" method="post" id="loginBar">
            <input type="hidden" name="auth_url_return" id="auth_url_return" value="{$url}" />
            <div class="loginPrompt">
                <p class="loginMsg">{i18n key=auth|auth.text.logon}<br />{if (false || $canNewAccount) }{i18n key=auth|auth.text.newAccount}<br/>{/if}</p>
                <p><label id="loginLabel" for="login">{i18n key=auth|auth.login}</label>
                <input id="login" type="text" name="login" class="login default-value label-overlayed" value=""
                 /></p>
                 <p><label id="passwordLabel" for="password">{i18n key=auth|auth.password}</label><input id="password" class="login" type="password" name="password" value=""
                 /></p><!--<input id="password-clear" class="login label-overlayed" type="text" value="{i18n key=auth|auth.password}"
                 />--><input id="submitUserLogin" type="submit" class="button button-confirm" value="" />
            </div>
        </form>
    {/if}
    </div>
{/if}