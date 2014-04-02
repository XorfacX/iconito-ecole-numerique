<?php
class PluginAuth_Saml extends CopixPlugin {
    public function beforeSessionStart (){}
    public function beforeProcess (& $action){
  
                if(CopixConfig::get('conf_Saml_actif') != 1)
                    return;
		require_once(COPIX_UTILS_PATH.'../../simplesamlphp/lib/_autoload.php');
		
		$asId = 'iconito-sql';
		$as = new SimpleSAML_Auth_Simple($asId);
		$ppo->user = _currentUser();
		
		if ($as->isAuthenticated() && !$ppo->user->isConnected()) {
			$attributes = $as->getAttributes();
			$ppo->saml_user = $attributes['login_dbuser'][0];
			if($ppo->saml_user) {

				$ppo->iconito_user = Kernel::getUserInfo( "LOGIN", $ppo->saml_user );
				
				if($ppo->iconito_user['login']) {
					_currentUser()->login(array('login'=>$ppo->iconito_user['login'], 'assistance'=>true));
					$url_return = CopixUrl::get ('kernel||doSelectHome');
					// $url_return = CopixUrl::get ('assistance||users');
					return new CopixActionReturn (COPIX_AR_REDIRECT, $url_return);
				} else {
					$ppo->cas_error = 'no-iconito-user';
					return _arPpo ($ppo, 'cas.tpl');
				}
			}
		}
		
		if (!$as->isAuthenticated() && $ppo->user->isConnected()) {
			$ppo->user = _currentUser();
			if($ppo->user->isConnected()){
				CopixAuth::getCurrentUser ()->logout (array ());
				CopixEventNotifier::notify ('logout', array ('login'=>CopixAuth::getCurrentUser()->getLogin ()));
				CopixAuth::destroyCurrentUser ();
				CopixSession::destroyNamespace('default');
			}
		}
    }
    public function afterProcess ($actionreturn){}
    public function beforeDisplay (& $display){}
}

