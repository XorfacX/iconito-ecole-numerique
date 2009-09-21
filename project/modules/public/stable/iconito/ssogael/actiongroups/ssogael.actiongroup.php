<?php
/**
 * SsoGael - ActionGroup
 *
 * @package	Iconito
 * @subpackage  SsoGael
 * @version     $Id: ssogael.actiongroup.php,v 1.5 2007-02-15 16:12:44 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ActionGroupSsogael extends CopixActionGroup {

	public function beforeAction (){
		//_currentUser()->assertCredential ('group:[current_user]');

	}

   function doSsoGael () {

		// V�rification du profil : Le SSO est limit� aux enseignants et aux agents de ville...
		if( !Kernel::isEnseignant() && !Kernel::isAgentVille() ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('ssogael||error',array('err'=>'profil') ));
		}

		// V�rification de la configuration d'Iconito
		if( !CopixConfig::exists('|urlGael') || trim(CopixConfig::get('|urlGael'))=='' ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('ssogael||error',array('err'=>'config') ));
		}
		
		$mysession = Kernel::getSessionBU();

		// V�rification de la pr�sence de la cl� secrete de l'utilisateur (en provenance de Gael)
		if( trim($mysession['cle_privee'])=='' ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('ssogael||error',array('err'=>'secretkey') ));
		}
		
		// Demande de challenge � Gael
		$url = CopixConfig::get('|urlGael').'/sso-iconito.php?mode=challenge';
		$url.= '&identifiant='.$mysession['id'];

		$file = @fopen( $url, 'r' );
		if (!$file) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('ssogael||error',array('err'=>'unreachable') ));
		}
		
		$challenge = '';
		while (!feof($file)) {
			$challenge .= fread($file, 1024);
		}
		fclose ($file);

		if( ereg( '^\-ERR (.*)$', $challenge, $regs ) ) {
			die( 'erreur challenge : '.$regs[1]);
		}
		
		if( ! ereg( '^\+OK (.+)$', $challenge, $regs ) ) {
			die( 'erreur challenge inconnue');
		}
		
		$challenge = $regs[1];

		// Pr�paration de la r�ponse au challenge
		$challenge_crypt = md5($challenge.$mysession["ALL"]->pers_cle_privee);
		$url = CopixConfig::get('|urlGael').'/sso-iconito.php?mode=login';
		$url.= '&identifiant='.$mysession['id'];
		
		$node = $this->getRequest('id', null);
		if( $node ) {
			list( $node_type, $node_id) = explode( '-', $node, 2 );
			$url.= '&node_type='.$node_type;
			$url.= '&node_id='.$node_id;
		}
		$url.= '&key='.urlencode($challenge_crypt);
		
		return new CopixActionReturn (COPIX_AR_REDIRECT, $url );
	}

   function getError () {
   		if( !CopixI18N::exists('ssogael|ssogael.error.'._request("err")) )
			_request("err") = 'default';
			
		return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('ssogael|ssogael.error.'._request("err")),
			'back'=>CopixUrl::get ('||')));
			
   }	
}
?>
