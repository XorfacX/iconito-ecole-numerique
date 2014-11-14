<div class="dashboard module_dash tools_right ink_blue font_dash">
    <div class="dashpanel ">
        <div class="title">
            <div class="groupname"></div>
            <div class="wcontrol">
                <a class="dashclose" href="{$ppo->closeUrl}"></a>
            </div>
            <span>{i18n key="kernel|kernel.sso.title"}</span>
        </div>
        <div class="content content-auth">

            {if isset($errorLogin)}
                <div class="mesgErrors">
                    <ul><li>{i18n key="kernel|kernel.sso.error_message"}</li></ul>
                </div>
            {/if}

            <form action="" method="post" id="loginForm">
            <fieldset id="loginForm">
                
                <p>{i18n key="kernel|kernel.sso.login_message"}</p>

                <table>
                    <tr>
                        <th><label for="loginBig">{i18n key="kernel|kernel.sso.login"}</label></th>
                        <td>
                            <input type="text" name="username" id="loginBig" size="9" autofocus="autofocus" value="{if isset($typedUsername)}{$typedUsername}{/if}" />
                        </td>
                    </tr>

                    <tr>
                        <th><label for="passwordBig">{i18n key="kernel|kernel.sso.password"}</label></th>
                        <td><input type="password" name="password" id="passwordBig" size="9" /></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <input type="submit" name="submit" class="button button-confirm" value="{i18n key="kernel|kernel.sso.connect"}" />
                        </td>
                    </tr>
                </table>
            </fieldset>
            </form>
        </div>
    </div>
</div>