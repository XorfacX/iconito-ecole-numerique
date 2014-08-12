<?php
class PluginAuth_Saml extends CopixPlugin {
    public function beforeSessionStart (){}
    public function beforeProcess (& $action){
  
		if(CopixConfig::get('conf_Saml_actif') != 1) return;
		
		require_once(COPIX_UTILS_PATH.'../../simplesamlphp/lib/_autoload.php');
		
		$asId = 'iconito-sql';
		if (CopixConfig::exists('default|conf_Saml_authSource') && CopixConfig::get('default|conf_Saml_authSource')) {
			$asId = CopixConfig::get('default|conf_Saml_authSource');
		}
		$as = new SimpleSAML_Auth_Simple($asId);
		$ppo = new CopixPPO();
        $ppo->user = _currentUser();
		
		if ($as->isAuthenticated() && !$ppo->user->isConnected()) {
			$attributes = $as->getAttributes();
			
			$uidAttribute = 'login_dbuser';
			if (CopixConfig::exists('default|conf_Saml_uidAttribute') && CopixConfig::get('default|conf_Saml_uidAttribute')) {
				$uidAttribute = CopixConfig::get('default|conf_Saml_uidAttribute');
			}
			
			$ppo->saml_user = null;
			if (isset($attributes[$uidAttribute]) && isset($attributes[$uidAttribute][0])) {
				$ppo->saml_user = $attributes[$uidAttribute][0];
			}
			

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

