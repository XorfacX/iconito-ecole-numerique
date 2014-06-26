<?php
/**
 * @package standard
 * @subpackage auth 
 * 
 * @author		Frederic Mossmann
 * @copyright	CAP-TIC
 * @link		http://www.cap-tic.fr
 * @license		http://www.gnu.org/licenses/lgpl.html GNU General Lesser  Public Licence, see LICENCE file
 */

/**
 * Actiongroup contenant la parti administration des userhandler, grouphandler et credentialhandler
 * @package standard
 * @subpackage auth
 */
class ActionGroupSaml extends EnicActionGroup {
	
	public function processLogin (){
		
		require_once(COPIX_UTILS_PATH.'../../simplesamlphp/lib/_autoload.php');
		
		$asId = 'iconito-sql';
		if (CopixConfig::exists('default|conf_Saml_authSource') && CopixConfig::get('default|conf_Saml_authSource')) {
			$asId = CopixConfig::get('default|conf_Saml_authSource');
		}
		$as = new SimpleSAML_Auth_Simple($asId);
		
		$_SESSION['chartValid'] = false;
		$ppo = new CopixPPO ();
		$ppo->user = _currentUser();
		if($ppo->user->isConnected()){
			$url_return = CopixUrl::get ('kernel||doSelectHome');
			/*
			 * PATCH FOR CHARTE
			 */
			$this->user->forceReload();
			if(!$this->service('charte|CharteService')->checkUserValidation()){
				$this->flash->redirect = $url_return;
				return $this->go('charte|charte|valid');
			}
			return _arRedirect ($url_return);
			//return new CopixActionReturn (COPIX_AR_REDIRECT, $url_return);
		} else {
			
			$as->requireAuth();
			
			$attributes = $as->getAttributes();
			
			/*
			echo "<pre>";
			print_r($attributes);
			die();
			*/
			
			$uidAttribute = 'login_dbuser';
			if (CopixConfig::exists('default|conf_Saml_uidAttribute') && CopixConfig::get('default|conf_Saml_uidAttribute')) {
				$uidAttribute = CopixConfig::get('default|conf_Saml_uidAttribute');
			}

			$ppo->saml_user = null;
			if (isset($attributes[$uidAttribute]) && isset($attributes[$uidAttribute][0])) {
				$ppo->saml_user = $attributes[$uidAttribute][0];
			} else {
				$ppo->saml_error = 'bad-conf-uidattribute';
				return _arPpo ($ppo, 'saml-error.tpl');
			}
			
			if($ppo->saml_user) {

				$ppo->iconito_user = Kernel::getUserInfo( "LOGIN", $ppo->saml_user );
				
				if($ppo->iconito_user['login']) {
					_currentUser()->login(array('login'=>$ppo->iconito_user['login'], 'assistance'=>true));
					$url_return = CopixUrl::get ('kernel||doSelectHome');
					// $url_return = CopixUrl::get ('assistance||users');

					return new CopixActionReturn (COPIX_AR_REDIRECT, $url_return);
				} else {
					$ppo->saml_error = 'no-iconito-user';
					return _arPpo ($ppo, 'saml-error.tpl');
				}
			}
		}
		



		// $as->getLoginURL();
		
		/*
		if (!$as->isAuthenticated()) {
			$url = SimpleSAML_Module::getModuleURL('core/authenticate.php', array('as' => $asId));
			$params = array(
				'ErrorURL' => CopixUrl::get ('auth|saml|test_error'),
				'ReturnTo' => CopixUrl::get ('auth|saml|test_ok'),
			);
			$as->login($params);
		}
		*/
		
		/*
		$attributes = $as->getAttributes();
		
		echo "<pre>";
		print_r($attributes);
		die();
		*/
	}
	
	public function processLogout (){
		require_once(COPIX_UTILS_PATH.'../../simplesamlphp/lib/_autoload.php');
		
		$asId = 'iconito-sql';
		if (CopixConfig::exists('default|conf_Saml_authSource') && CopixConfig::get('default|conf_Saml_authSource')) {
			$asId = CopixConfig::get('default|conf_Saml_authSource');
		}
		$as = new SimpleSAML_Auth_Simple($asId);
		
		$ppo = new CopixPPO ();
		$ppo->user = _currentUser();
		if($ppo->user->isConnected()){
			CopixAuth::getCurrentUser ()->logout (array ());
			CopixEventNotifier::notify ('logout', array ('login'=>CopixAuth::getCurrentUser()->getLogin ()));
			CopixAuth::destroyCurrentUser ();
			CopixSession::destroyNamespace('default');
		}

		$as->logout(_url ().'simplesaml/saml2/idp/initSLO.php?RelayState='.urlencode(_url('auth|saml|logout_cas')));
		// $as->logout(_url ().'simplesaml/saml2/idp/initSLO.php?RelayState='.urlencode(_url() . 'logout.php'));

		
	}
	
	public function processLogout_Cas () {

		$ppo = new CopixPPO();

		$ppo->conf_Saml_CasLogoutUrl = (CopixConfig::exists('default|conf_Saml_caslogouturl')?CopixConfig::get ('default|conf_Saml_caslogouturl'):0);

		if($ppo->conf_Saml_CasLogoutUrl) {
			CopixHTMLHeader::addOthers ('<meta HTTP-EQUIV="REFRESH" content="3; url='._url('||').'"');
		}

		return _arPpo ($ppo, 'saml_logout_cas.tpl');
	}
	
}

?>
